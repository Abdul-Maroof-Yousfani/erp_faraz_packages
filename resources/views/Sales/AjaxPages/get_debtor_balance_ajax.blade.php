
<?php

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;












//echo Form::open(array('url' => 'finance/CreateReceiptVoucherForSales?m='.$m,'id'=>'cashPaymentVoucherForm'));
if($ClientId !=""):
    $CustomerData = DB::Connection('mysql2')->select('select a.* from customers a
                                                              INNER JOIN sales_tax_invoice b ON b.buyers_id = a.id
                                                              where a.status = 1
                                                              and b.status = 1
                                                              and b.buyers_id = '.$ClientId.'
                                                              and b.gi_date between "'.$FromDate.'" and "'.$ToDate.'"
                                                              group by b.buyers_id');
//$CustomerData = DB::Connection('mysql2')->table('customers')->where('id',$ClientId)->where('status',1)->get();
else:
    $CustomerData = DB::Connection('mysql2')->select('select a.* from customers a
                                                              INNER JOIN sales_tax_invoice b ON b.buyers_id = a.id
                                                              where a.status = 1
                                                              and b.status = 1
                                                              and b.gi_date between "'.$FromDate.'" and "'.$ToDate.'"
                                                              group by b.buyers_id');
    //$CustomerData = DB::Connection('mysql2')->table('customers')->where('status',1)->get();
endif;
$totalEnd=0;
$receivedEnd=0;
$remainingEnd=0;
$total_return_end=0;
$main_count=1;
echo $FromDate;
echo $ToDate;
?>
<script !src="">
    var n = 0;
</script>

<?php
foreach($CustomerData as $CustFil):

//$Invoice = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('buyers_id',$CustFil->id)->whereBetween('gi_date',[$FromDate,$ToDate])->get();


$Invoice =    DB::Connection('mysql2')->select('select * from sales_tax_invoice
        where status=1
        and buyers_id="'.$CustFil->id.'"
        and gi_date between "'.$FromDate.'" and "'.$ToDate.'"');


if((!empty($Invoice))):
?>
<table class="table table-bordered sf-table-list AutoCounter table{{$main_count}}" id="export_table_to_excel_<?php echo $main_count?>">
    <thead>
    <th colspan="12" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
    </thead>
    <thead>
    <th colspan="12" class="text-center">Debtor Balance Report</th>
    </thead>
    <thead>
        <th colspan="12" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
    </thead>
    <thead>
    <th colspan="12" class="text-center"><strong style="font-size: 22px"><?php echo CommonHelper::byers_name($CustFil->id)->name?></strong></th>
    </thead>
    <thead>
    <th class="text-center col-sm-1">S.Nooo</th>
    <th class="text-center col-sm-1">SI No</th>
    <th class="text-center col-sm-1">SI Date</th>
    <th class="text-center col-sm-1">SO No.</th>
    <th class="text-center">Buyer's Order No</th>
    <th class="text-center">Buyer's Unit</th>
    <th class="text-center">Due Date</th>
    <th class="text-center">Invoice Amount</th>
    <th class="text-center">Return Amount</th>
    <th class="text-center">Received Amount</th>
    <th class="text-center">Remaining Amount</th>
    <th class="text-center printHide">view</th>
    </thead>
    <tbody id="data">
    <?php $counter = 1;
    $total=0;
    $received=0;
    $remaining=0;
    $total_return=0;
    ?>

    @foreach($Invoice as $row)
        <?php

        CommonHelper::companyDatabaseConnection($_GET['m']);
        $data=SalesHelper::getTotalAmountSalesTaxInvoice($row->id);
        $get_freight=SalesHelper::get_freight($row->id);
        $customer=CommonHelper::byers_name($row->buyers_id);
        $return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice_by_date($row->id,$FromDate,$ToDate);


        //$rece=SalesHelper::get_received_payment($row->id);
        $rece = CommonHelper::bearkup_receievd_use_for_balance($row->id);
        CommonHelper::reconnectMasterDatabase();
        $BuyersUnit = '';
        $BuyerOrderNo = '';
        if($row->so_id != 0 ):
            $SoData = DB::Connection('mysql2')->table('sales_order')->where('id',$row->so_id)->select('order_no','buyers_unit')->first();
            $BuyersUnit = $SoData->buyers_unit;
            $BuyerOrderNo = $SoData->order_no;
        endif;

        $rema=$data->total+$get_freight-$return_amount-$rece;
        if($rema > 0):
        ?>
        <tr  @if($rema==0) style="background-color: #bdefbd" @endif title="{{$row->id}}" id="{{$row->id}}">

            <td class="text-center">
                @if($rema>0)
                    <input name="checkbox[]" onclick="check(),supplier_check('',this.id)"
                           class="checkbox1 form-control AllCheckbox AddRemoveClass<?php echo $row->buyers_id?>"
                           id="<?php echo $row->buyers_id?>" type="checkbox" value="{{$row->id}}"
                           onchange="CheckUncheck('<?php echo $counter?>','<?php echo $row->buyers_id?>')" style="height: 30px;">
                @else<p>Clear</p>
                @endif
            </td>

            <td class="text-center">{{strtoupper($row->gi_no)}}</td>
            <td class="text-center"> <?php echo CommonHelper::changeDateFormat($row->gi_date); ?></td>
            <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
            <td class="text-center"><?php echo $BuyerOrderNo?></td>
            <td class="text-center"><?php echo $BuyersUnit?></td>
            <td title="{{$row->id}}" class="text-center">{{CommonHelper::changeDateFormat($row->due_date)}}</td>

            <?php

            $inv=$data->total+$get_freight; ?>

            <td class="text-right">{{number_format($inv,2)}}</td>
            <td class="text-right">{{number_format($return_amount,2)}} <?php $total_return+=$return_amount; ?></td>
            <?php
            $rema=$data->total+$get_freight-$return_amount-$rece;?>
            <td class="text-right">{{number_format($rece,2)}}</td>
            <td class="text-right">{{number_format($rema,2)}}</td>

            <?php

            $total+=$inv;
            $received+=$rece;
            $remaining+=$rema;
            ?>
            <td class="text-center printHide"><button
                        onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                        type="button" class="btn btn-success btn-xs">View</button></td>




            {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
            {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
        </tr>
        <?php endif;?>

    @endforeach


    <tr>
        <td class="text-center" colspan="7" style="font-size: 20px;">Total</td>
        <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($total,2)}}<?php $totalEnd+=$total;?></td>
        <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($total_return,2)}}<?php $total_return_end+=$total_return;?></td>
        <td class="text-right" colspan="1" style="font-size: 20px;">{{number_format($received,2)}}<?php $receivedEnd+=$received;?></td>
        <td class="text-right <?php if ($remaining==0): ?>  hidee{{$main_count}}<?php endif ?>" colspan="2" style="font-size: 20px;">{{number_format($remaining,2)}}<?php $remainingEnd+=$remaining;?></td>

        <input type="hidden" value="{{$remaining}}" class="val" id="{{$main_count}}"/>
    </tr>
    <tr>
        <td colspan="10">
            {{--<input type="submit" value="Create Receipt" class="btn btn-sm btn-primary BtnEnDs BtnSub" id="add">--}}
        </td>
    </tr>
    </tbody>
</table>
<?php
endif;
$main_count++;
endforeach;?>
<table class="table table-bordered sf-table-list GrandTotal" id="export_table_to_excel_<?php echo $main_count?>">
    <thead>
    <tr>
        <th class="text-center" colspan="7">Grand Total</th>
        <th class="text-center">Total Invoice Amount</th>
        <th class="text-center">Total Return Amount</th>
        <th class="text-center">Total Paid Amount</th>
        <th class="text-center">Total Remaining Amount</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="text-center" colspan="7" style="font-size: 20px;">Total</td>
        <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($totalEnd,2)}}</td>
        <td class="text-right" colspan="1" style="font-size: 20px;">{{number_format($total_return_end,2)}}</td>
        <td class="text-right" colspan="1" style="font-size: 20px;">{{number_format($receivedEnd,2)}}</td>
        <td class="text-right" colspan="2" style="font-size: 20px;">{{number_format($remainingEnd,2)}}</td>
    </tr>
    </tbody>
</table>
<?php //Form::close(); ?>

<script>
    $( document ).ready(function() {
        $('.val').each(function(i, obj) {
            var value=$(this).val();
            value=parseFloat(value);

            if (value==0)
            {
                var id=obj.id;
                $('.table'+id+'').remove();
            }


        });

        var AutoCount = 1;
        $(".AutoCounter").each(function(){

            $(this).attr('id','export_table_to_excel_'+AutoCount);
            AutoCount++;


//            $('#wrapper [id$="123"]').attr('id', function (_, id) {
//                return id.replace('123', '321');
//            });
        });
        n = AutoCount;
        $('.GrandTotal').attr('id','export_table_to_excel_'+AutoCount);
    });



    //table to excel (multiple table)
    var array1 = new Array();
    //var n = ''; //Total table

    for ( var x=1; x<=n; x++ ) {
        array1[x-1] = 'export_table_to_excel_' + x;
    }
    var tablesToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>'
                , templateend = '</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>'
                , body = '<body>'
                , tablevar = '<table>{table'
                , tablevarend = '}</table>'
                , bodyend = '</body></html>'
                , worksheet = '<x:ExcelWorksheet><x:Name>'
                , worksheetend = '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>'
                , worksheetvar = '{worksheet'
                , worksheetvarend = '}'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
                , wstemplate = ''
                , tabletemplate = '';

        return function (table, name, filename) {
            var tables = table;
            var wstemplate = '';
            var tabletemplate = '';

            wstemplate = worksheet + worksheetvar + '0' + worksheetvarend + worksheetend;
            for (var i = 0; i < tables.length; ++i) {
                tabletemplate += tablevar + i + tablevarend;
            }

            var allTemplate = template + wstemplate + templateend;
            var allWorksheet = body + tabletemplate + bodyend;
            var allOfIt = allTemplate + allWorksheet;

            var ctx = {};
            ctx['worksheet0'] = name;
            for (var k = 0; k < tables.length; ++k) {
                var exceltable;
                if (!tables[k].nodeType) exceltable = document.getElementById(tables[k]);
                ctx['table' + k] = exceltable.innerHTML;
            }

            document.getElementById("dlink").href = uri + base64(format(allOfIt, ctx));;
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();
        }
    })();

</script>
