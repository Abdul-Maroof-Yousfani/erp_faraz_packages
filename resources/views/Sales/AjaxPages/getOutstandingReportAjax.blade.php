<?php

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

if($ClientId !=""):
    $CustomerData = DB::Connection('mysql2')->select('select a.* from customers a
                                                      INNER JOIN sales_tax_invoice b ON b.buyers_id = a.id
                                                      where a.status = 1
                                                      and b.status = 1
                                                      and b.buyers_id = '.$ClientId.'
                                                      and (b.gi_date between "'.$FromDate.'" and "'.$ToDate.'" or b.so_type=1)
                                                      group by b.buyers_id');
else:
    $CustomerData = DB::Connection('mysql2')->select('select a.* from customers a
                                                      INNER JOIN sales_tax_invoice b ON b.buyers_id = a.id
                                                      where a.status = 1
                                                      and b.status = 1
                                                      and (b.gi_date between "'.$FromDate.'" and "'.$ToDate.'" or b.so_type=1)
                                                      group by b.buyers_id');
endif;
$totalEnd=0;
$receivedEnd=0;
$remainingEnd=0;
$total_return_end=0;
$main_count=1;
?>
<style>
/* ===== Self-contained styling for Debtor Outstanding Report ===== */
/* Reset any inherited sticky/fixed positioning that can make a table's Total row bleed/overlap over the next stacked customer table while scrolling */
.tb-report-wrap{position:relative;background: #f7f8fc !important;border:1px solid #dde1ee;border-radius:12px;padding:20px 22px 4px 22px;margin-bottom:24px;overflow:hidden;z-index:auto;}
.tb-report-header{text-align:center;margin-bottom:14px;}
.tb-report-title{font-size:16px;font-weight:700;color:#1f2a5c;margin:0 0 4px 0;}
.tb-printed-on{font-size:11px;color:#6b7094;margin:0 0 6px 0;}
.tb-company-name{font-size:14px;font-weight:700;color:#2b3350;margin:0;}
.tb-table-scroll{overflow-x:auto;position:relative;}
table.tb-table{width:100%;border-collapse:collapse;font-size:12.5px;background:#ffffff;margin:0;}
table.tb-table thead tr.tb-col-row th{position:static !important;top:auto !important;background:#eef1f8;color:#2b3350;font-weight:700;text-transform:uppercase;font-size:11px;letter-spacing:.3px;padding:10px 8px;border:none;border-bottom:2px solid #1f2a5c;}
table.tb-table tbody td{padding:9px 8px;border:none;border-bottom:1px solid #eef0f6;color:#2a2f4a;background:#ffffff;}
table.tb-table tbody tr:hover td{background:#f7f9fd;}
/* Total row (tfoot) - forced static + opaque background so it can never float/stick over content from another table on the page */
table.tb-table tfoot,table.tb-table tfoot tr,table.tb-table tfoot td{position:static !important;top:auto !important;bottom:auto !important;}
table.tb-table tfoot td{background:#f4f6fb !important;color:#1f2a5c !important;font-weight:700 !important;border:none !important;border-top:2px solid #1f2a5c !important;padding:11px 8px !important;}
/* Grand Total card - dark navy banner,consistent with other ERP reports */
.GrandTotal{margin-bottom:10px;}
.GrandTotal .tb-table thead tr.tb-col-row th{background:#1f2a5c;color:#ffffff;border-bottom:none;}
.GrandTotal .tb-table tbody td{position:static !important;background:#1f2a5c !important;color:#ffffff !important;font-size:15px !important;font-weight:700 !important;padding:14px 8px !important;border:none !important;}

.sf-report-print-table thead th h3{margin:0 !important;font-size:16px !important;font-weight:800 !important;color:#1f2440 !important;}
.sf-report-print-table thead th p{margin:0 !important;font-size:16px !important;font-weight:800 !important;color:#1f2440 !important;}
p.tb-company-name{margin:0 !important;font-size:16px !important;font-weight:800 !important;color:#1f2440 !important;font-weight:500 !important;text-align:center;}
</style>
<script !src="">
    var n = 0;
</script>

<?php
foreach($CustomerData as $CustFil):

$Invoice = DB::Connection('mysql2')->select('select * from sales_tax_invoice
        where status=1
        and buyers_id="'.$CustFil->id.'"
        and (gi_date between "'.$FromDate.'" and "'.$ToDate.'" or so_type=1)');

if((!empty($Invoice))):
?>
<div class="AutoCounter table{{$main_count}}" id="export_table_to_excel_<?php echo $main_count?>">
    <!-- <div class="tb-report-header">
        <p class="tb-printed-on">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p>
        
    </div> -->
    <div class="tb-table-scroll">
        <table class="tb-table sf-report-print-table">
            <thead>
            </thead>
            <thead>
                <p class="tb-company-name"><?php echo CommonHelper::byers_name($CustFil->id)->name?></p>
            </thead>
            <thead>
            <tr class="tb-col-row">
                <th class="text-center">S.No</th>
                <th class="text-center">SI No</th>
                <th class="text-center">ST No</th>
                <th class="text-center">SI Date</th>
                <th class="text-center">SO No.</th>
                <th class="text-center">Buyer's Order No</th>
                <th class="text-center">Buyer's Unit</th>
                <th class="text-center">Due Date</th>
                <th class="text-right">Invoice Amount</th>
                <th class="text-right">Return Amount</th>
                <th class="text-right">Received Amount</th>
                <th class="text-right">Remaining Amount</th>
                <th class="text-center printHide">View</th>
            </tr>
            </thead>
            <tbody id="data">
            <?php
            $total=0;
            $received=0;
            $remaining=0;
            $Counter = 1;
            $total_return=0;
            ?>

            @foreach($Invoice as $row)
                <?php
                CommonHelper::companyDatabaseConnection($_GET['m']);
                $data=SalesHelper::getTotalAmountSalesTaxInvoice($row->id);
                $get_freight=SalesHelper::get_freight($row->id);
                $customer=CommonHelper::byers_name($row->buyers_id);
                $return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice_by_date($row->id,$FromDate,$ToDate);

                $rece = CommonHelper::bearkup_receievd($row->id,$FromDate,$ToDate);
                CommonHelper::reconnectMasterDatabase();
                $BuyersUnit = '';
                $BuyerOrderNo = '';
                if($row->so_id != 0 ):
                    $SoData = DB::Connection('mysql2')->table('sales_order')
                        ->where('id',$row->so_id)
                        ->select('purchase_order_no','buyers_unit')
                        ->first();
                    $BuyersUnit = $SoData->buyers_unit ?? '';
                    $BuyerOrderNo = $SoData->purchase_order_no ?? '';
                endif;

                $rema=$data->total+$get_freight-$return_amount-$rece;

                if($rema > 0.5):
                ?>
                <tr @if($rema==0) style="background-color: #bdefbd" @endif title="{{$row->id}}" id="{{$row->id}}">
                    <td class="text-center"><?php echo $Counter++;?></td>
                    <td class="text-center">{{strtoupper($row->gi_no)}}</td>
                    <td class="text-center">{{strtoupper($row->sc_no)}}</td>
                    <td class="text-center"><?php echo '`'.CommonHelper::changeDateFormat($row->gi_date); ?></td>
                    <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
                    <td class="text-center"><?php echo '`'.$BuyerOrderNo?></td>
                    <td class="text-center"><?php echo $BuyersUnit?></td>
                    <td title="{{$row->id}}" class="text-center">{{'`'.CommonHelper::changeDateFormat($row->due_date)}}</td>

                    <?php $inv=$data->total+$get_freight; ?>
                    <td class="text-right">{{number_format($inv,2)}}</td>
                    <td class="text-right">{{number_format($return_amount,2)}} <?php $total_return+=$return_amount; ?></td>
                    <td class="text-right">{{number_format($rece,2)}}</td>
                    <td class="text-right">{{number_format($rema,2)}}</td>

                    <?php
                    $total+=$inv;
                    $received+=$rece;
                    $remaining+=$rema;
                    ?>

                    <td class="text-center printHide">
                        <button onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice')" type="button" class="btn btn-success btn-xs">View</button>
                    </td>
                </tr>
                <?php endif;?>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td class="text-center" colspan="8">Total</td>
                <td class="text-right">{{number_format($total,2)}}<?php $totalEnd+=$total;?></td>
                <td class="text-right">{{number_format($total_return,2)}}<?php $total_return_end+=$total_return;?></td>
                <td class="text-right">{{number_format($received,2)}}<?php $receivedEnd+=$received;?></td>
                <td class="text-right <?php if ($remaining==0): ?>hidee{{$main_count}}<?php endif ?>" colspan="2">{{number_format($remaining,2)}}<?php $remainingEnd+=$remaining;?></td>
                <input type="hidden" value="{{$remaining}}" class="val" id="{{$main_count}}"/>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div style="height: 24px;"></div>
<?php
endif;
$main_count++;
endforeach;?>

<div class="tb-report-wrap GrandTotal" id="export_table_to_excel_<?php echo $main_count?>">
    <div class="tb-table-scroll">
    <table class="tb-table">
        <thead>
        <tr class="tb-col-row">
            <th class="text-center" colspan="7">Grand Total</th>
            <th class="text-right">Total Invoice Amount</th>
            <th class="text-right">Total Return Amount</th>
            <th class="text-right">Total Paid Amount</th>
            <th class="text-right">Total Remaining Amount</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center" colspan="7">Total</td>
            <td class="text-right">{{number_format($totalEnd,2)}}</td>
            <td class="text-right">{{number_format($total_return_end,2)}}</td>
            <td class="text-right">{{number_format($receivedEnd,2)}}</td>
            <td class="text-right" colspan="2">{{number_format($remainingEnd,2)}}</td>
        </tr>
        </tbody>
    </table>
    </div>
</div>

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
        });
        n = AutoCount;
        $('.GrandTotal').attr('id','export_table_to_excel_'+AutoCount);
    });

    //table to excel (multiple table)
    var array1 = new Array();

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