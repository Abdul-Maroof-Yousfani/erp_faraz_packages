<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$TotInvoiceAmountEnd = 0;
$TotReturnAmountEnd = 0;
$TotPaidAmountEnd = 0;
$TotBalanceEnd = 0;
$total_not_yet_due_end=0;
$Tot_1_30End = 0;
$Tot_31_60End = 0;
$Tot_61_90End = 0;
$Tot_91_180End = 0;
$Tot_180_1000End = 0;
$TotOverAllEnd = 0;
$Clause = '';

$company_data= ReuseableCode::get_account_year_from_to(Session::get('run_company'));
$from=$company_data[0];
$as_on=Request::get('as_on');

if($_GET['CustomerId'] == 'all')
{$Clause = '';}
else{$Clause = 'and b.buyers_id="'.$_GET['CustomerId'].'"';}
        $Cust = DB::Connection('mysql2')->select('select a.id,a.name from customers a
                                          INNER JOIN sales_tax_invoice b ON b.buyers_id = a.id
                                          WHERE b.status = 1
                                          '.$Clause.'
                                          and (b.gi_date between "'.$from.'" and "'.$as_on.'" or b.so_type=1)
                                          GROUP BY b.buyers_id Order by a.name');
$MainCount =  count($Cust);
$BuyerCounter =1;
        $count=1;
?>
<script !src="">
    var ClsCounter = "";
    var n = 0;
</script>
<?php
foreach($Cust as $Cfil):
$vendor_data=DB::Connection('mysql2')->select('select a.id,a.model_terms_of_payment,a.due_date,a.gi_no,a.gi_date,a.so_id,(sum(b.amount)+a.sales_tax)total
                from sales_tax_invoice a
                inner join
                sales_tax_invoice_data b
                on
                a.id=b.master_id

                where a.status=1
               and (a.gi_date between "'.$from.'" and "'.$as_on.'" or a.so_type=1)
                and a.buyers_id  = '.$Cfil->id.'
                group by a.id');

?>

<table class="table table-bordered ApnaBorder  AutoCounter table{{$BuyerCounter}}"  id="export_table_to_excel_<?php echo $BuyerCounter; ?>">
    <thead>
        <th colspan="15" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
    </thead>
    <thead>
        <th colspan="15" class="text-center">Debtor Ageing Detail Report</th>
    </thead>
    <thead>
        <th colspan="15" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
    </thead>
    <span>
<thead class="ApnaBorder">
<tr class="ApnaBorder text-center">
    <th colspan="8" class="ApnaBorder text-center"><?php echo CommonHelper::byers_name($Cfil->id)->name;?>  <!--<span >  (AS ON  < ?php echo CommonHelper::changeDateFormat($as_on);?>)</span>--></th>

    <th class="ApnaBorder text-center Chnage-bg" colspan="7">Days</th>
</tr>
</thead>
<thead>
<tr class="text-center ApnaBorder">
    <th class="text-center ApnaBorder" style="width: 7%">SI Date</th>
    <th class="text-center ApnaBorder">SI NO</th>
    <th class="text-center ApnaBorder">Buyer Order No</th>
    <th class="text-center ApnaBorder">Buyer Unit</th>
    <th class="text-center ApnaBorder">Invoice Amount</th>
    <th class="text-center ApnaBorder">Return Amount</th>
    <th class="text-center ApnaBorder">Received Amount</th>
    <th class="text-center ApnaBorder">Balance</th>
    <th class="text-center ApnaBorder Chnage-bg">Not Yet Due</th>
    <th class="text-center ApnaBorder Chnage-bg">(1-30)</th>
    <th class="text-center ApnaBorder Chnage-bg">(31-60)</th>
    <th class="text-center ApnaBorder Chnage-bg">(61-90)</th>
    <th class="text-center ApnaBorder Chnage-bg">(91-180)</th>
    <th class="text-center ApnaBorder Chnage-bg">More Than 180 days</th>
    <th class="text-center ApnaBorder Chnage-bg">Total Amount</th>
    <th  class="text-center ApnaBorder Chnage-bg hide">Days Outstanding</th>

</tr>
</thead>
<tbody class="ApnaBorder">
<?php
$TotInvoiceAmount = 0;
$TotReturnAmount = 0;
$TotPaidAmount = 0;
$TotBalance = 0;
$total_not_yet_due=0;
$Tot_1_30 = 0;
$Tot_31_60 = 0;
$Tot_61_90 = 0;
$Tot_91_180 = 0;
$Tot_180_1000 = 0;
$TotOverAll = 0;
foreach($vendor_data as $fil):
$InvoiceAmount = $fil->total+SalesHelper::get_freight($fil->id);
$PaidAmount = CommonHelper::bearkup_receievd_approved($fil->id,$from,$as_on);
$return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice_by_date($fil->id,$from,$as_on);
$BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;
$date1_ts = strtotime($fil->gi_date.'+'.$fil->model_terms_of_payment.'day');
       
$date2_ts = strtotime($as_on);
$diff = $date2_ts - $date1_ts;// - $date1_ts;
$NoOfDays = round($diff / 86400);
//$NoOfDays = str_replace("-","",$NoOfDays);
        $MultiRvNO = DB::Connection('mysql2')->select('select rv_no from brige_table_sales_receipt where si_id = '.$fil->id.' group by rv_no ');

?>
<?php if($BalanceAmount  == 0):?>
<script !src="">
    $(document).ready(function(){
        ClsCounter = '<?php echo $BuyerCounter?>';
        $('#export_table_to_excel_'+ClsCounter).addClass("intro");
    });
</script>

<?php endif;?>


<?php
       if ($BalanceAmount>0):
?>

<?php
        $SalesOrder = DB::Connection('mysql2')->table('sales_order')->where('id',$fil->so_id)->select('buyers_unit','order_no')->first();

?>
<tr title="{{$count++}}" class="text-center ApnaBorder yes">
    <td class="ApnaBorder"><?php echo CommonHelper::changeDateFormat($fil->gi_date)?> </td>
    <td class="ApnaBorder"><?php echo strtoupper($fil->gi_no);//.' '.$fil->id?></td>
    <td class="ApnaBorder"><?php  if($fil->so_id !=0): echo $SalesOrder->order_no; endif;?></td>
    <td class="ApnaBorder"><?php  if($fil->so_id !=0): echo $SalesOrder->buyers_unit; endif;?></td>
    <td class="ApnaBorder">
        <a href="#" onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $fil->id ?>','View Sales Tax Invoice')"><?php echo number_format($InvoiceAmount,2); $TotInvoiceAmount+=$InvoiceAmount;?></a>

    </td>
    <td class="ApnaBorder"><?php $TotReturnAmount+=$return_amount; echo number_format($return_amount,2) ?></td>
    <td class="ApnaBorder">
        <?php if($PaidAmount > 0):?>
            <a href="#" onclick="showDetailModelOneParamerter('sales/viewReceivedAllVoucher','<?php echo $fil->id ?>','View Sales Tax Invoice')">
                <?php echo number_format($PaidAmount,2); $TotPaidAmount+=$PaidAmount?>
            </a>

        <?php else:?>
                0.00
        <?php endif;?>

    </td>
    <td class="ApnaBorder"><?php echo number_format($BalanceAmount,2); $TotBalance+=$BalanceAmount;?></td>
    <td class="ApnaBorder Chnage-bg"><?php if($NoOfDays <= 0){echo number_format($BalanceAmount,2); $total_not_yet_due+=$BalanceAmount;}?></td>
    <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(1,30))){ echo number_format($BalanceAmount,2); $Tot_1_30+=$BalanceAmount;}?></td>
    <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(31,60))){ echo number_format($BalanceAmount,2); $Tot_31_60+=$BalanceAmount;} ?></td>
    <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(61,90))){ echo number_format($BalanceAmount,2); $Tot_61_90+=$BalanceAmount;} ?></td>
    <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(91,180))){ echo number_format($BalanceAmount,2); $Tot_91_180+=$BalanceAmount;} ?></td>
    <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(181,10000))){ echo number_format($BalanceAmount,2); $Tot_180_1000+=$BalanceAmount;} ?></td>
    <td class="ApnaBorder Chnage-bg"><?php echo number_format($BalanceAmount,2); $TotOverAll+=$BalanceAmount;?></td>
    <td class="ApnaBorder Chnage-bg hide"><?php echo str_replace("-","",$NoOfDays);?></td>

</tr>



<?php  endif;
endforeach;?>
    </span>
<tr class="text-center ApnaBorder">
    <td colspan="4" class="ApnaBorder" style="font-weight: bold;">Sub Total :</td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotInvoiceAmount,2); $TotInvoiceAmountEnd+=$TotInvoiceAmount;?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotReturnAmount,2); $TotReturnAmountEnd+=$TotReturnAmount;?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotPaidAmount,2); $TotPaidAmountEnd+=$TotPaidAmount;?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotBalance,2); $TotBalanceEnd+=$TotBalance;?></td>
    <td class="ApnaBorder" style="font-weight: bold;">{{number_format($total_not_yet_due,2)}} <?php $total_not_yet_due_end+=$total_not_yet_due; ?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_1_30,2); $Tot_1_30End+=$Tot_1_30;?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_31_60,2); $Tot_31_60End+=$Tot_31_60;?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_61_90,2); $Tot_61_90End+=$Tot_61_90;?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_91_180,2); $Tot_91_180End+=$Tot_91_180;?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_180_1000,2); $Tot_180_1000End+=$Tot_180_1000?></td>
    <td    class="ApnaBorder <?php if ($TotOverAll==0): ?>  hidee{{$BuyerCounter}}<?php endif ?>" style="font-weight: bold;"><?php echo number_format($TotOverAll,2); $TotOverAllEnd+=$TotOverAll?></td>
    <input type="hidden" value="{{$TotOverAll}}" class="val" id="{{$BuyerCounter}}"/>
</tr>
</tbody>


<tr>
    <td colspan="15" style="background-color: black"></td>
</tr>

<?php
    $BuyerCounter++;
    endforeach;?>

</table>


<table class="table table-bordered ApnaBorder table{{$BuyerCounter}} GrandTotal"  id="export_table_to_excel_<?php echo $BuyerCounter; ?>">
    <tr class="text-center ApnaBorder">
        <th colspan="4" class="ApnaBorder"></th>
        <th class="text-center ApnaBorder">Total Invoice Amount</th>
        <th class="text-center ApnaBorder">Total Return Amount</th>
        <th class="text-center ApnaBorder">Total Received Amount</th>
        <th class="text-center ApnaBorder">Total Balance</th>
        <th class="text-center ApnaBorder Chnage-bg">Total Not Yet Due</th>
        <th class="text-center ApnaBorder Chnage-bg">Total (1-30)</th>
        <th class="text-center ApnaBorder Chnage-bg">Total (31-60)</th>
        <th class="text-center ApnaBorder Chnage-bg">Total (61-90)</th>
        <th class="text-center ApnaBorder Chnage-bg">Total (91-180)</th>
        <th class="text-center ApnaBorder Chnage-bg">Total More Than 180 days</th>
        <th class="text-center ApnaBorder Chnage-bg">Total Amount</th>

    </tr>
<tr class="text-center ApnaBorder">
    <td colspan="4" class="ApnaBorder" style="font-weight: bold;">Grand Total :</td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotInvoiceAmountEnd,2);?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotReturnAmountEnd,2); ?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotPaidAmountEnd,2); ?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotBalanceEnd,2); ?></td>
    <td class="ApnaBorder" style="font-weight: bold;">{{number_format($total_not_yet_due_end,2)}}</td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_1_30End,2); ?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_31_60End,2); ?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_61_90End,2); ?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_91_180End,2); ?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_180_1000End,2); ?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotOverAllEnd,2); ?></td>
</tr>
    </table>
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
        <?php if($_GET['ReportType'] == 1):?>
        $('.yes').remove();
        <?php else:?>
        <?php endif;?>

    });

    //table to excel (multiple table)
    var array1 = new Array();
    //var n = 0; //Total table


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

            document.getElementById("dlink").href = uri + base64(format(allOfIt, ctx));
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();
        }
    })();
    //$('.intro').css('display','none');
</script>