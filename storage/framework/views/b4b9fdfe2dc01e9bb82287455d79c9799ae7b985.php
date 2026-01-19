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


// if($_GET['CustomerId'] == 'all')
// {$Clause = '';}
// else{$Clause = 'and b.buyers_id="'.$_GET['CustomerId'].'"';}
$Cust = DB::Connection('mysql2')->select('select a.id,a.name,a.acc_id from customers a
                                          INNER JOIN sales_tax_invoice b ON b.buyers_id = a.id
                                          WHERE b.status = 1
                                          '.$Clause.'
                                          and (b.gi_date between "'.$from.'" and "'.$to.'" or b.so_type=1)
                                          GROUP BY b.buyers_id');
$MainCount =  count($Cust);
$BuyerCounter =1;
$count=1;
?>
<script !src="">
    var ClsCounter = "";
    var n = 0;
</script>
<table class="table table-bordered ApnaBorder hide" id="export_table_to_excel_1">
    <thead>
    <th colspan="15" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
    </thead>
    <thead>
    <th colspan="15" class="text-center">Debtor Ageing Summary Report</th>
    </thead>


    <thead>
    <th colspan="15" class="text-center"><?php echo 'As On : '.date_format(date_create($to),'F d, Y') ?></th>
    </thead>


    <thead>
    <th colspan="15" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
    </thead>
<thead>
<tr class="text-center ApnaBorder">
    <th colspan="8" class="text-center ApnaBorder ">Buyer</th>
    <th class="text-center ApnaBorder ">Not Yet Due</th>
    <th class="text-center ApnaBorder ">(1-30)</th>
    <th class="text-center ApnaBorder ">(31-60)</th>
    <th class="text-center ApnaBorder ">(61-90)</th>
    <th class="text-center ApnaBorder ">(91-180)</th>
    <th class="text-center ApnaBorder ">More Than 180 days</th>
    <th class="text-center ApnaBorder Chnage-bg">Total Amount</th>
</tr>
</thead>
<tbody class="ApnaBorder">
<?php
foreach($Cust as $Cfil):
    $vendor_data=DB::Connection('mysql2')->select('select a.id,a.model_terms_of_payment,a.due_date,a.gi_no,a.gi_date,(sum(b.amount)+a.sales_tax+a.sales_tax_further)total
                from sales_tax_invoice a
                inner join
                sales_tax_invoice_data b
                on
                a.id=b.master_id

                where a.status=1
                and (a.gi_date between "'.$from.'" and "'.$to.'" or b.so_type=1)
                and a.buyers_id  = '.$Cfil->id.'
                group by a.id');



     $debit=   DB::Connection('mysql2')->selectOne('select sum(amount)amount from transactions where status=1 and debit_credit=1 and acc_id="'.$Cfil->acc_id.'"')->amount;
    $credit=   DB::Connection('mysql2')->selectOne('select sum(amount)amount from transactions where status=1 and debit_credit=0 and acc_id="'.$Cfil->acc_id.'"')->amount;

       $amount=$debit-$credit;
    ?>

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
$PaidAmount = CommonHelper::bearkup_receievd_approved($fil->id,$from,$to);
$return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice_by_date($fil->id,$from,$to);
$BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;
$date1_ts = strtotime($fil->gi_date.'+'.$fil->model_terms_of_payment.'day');
$date2_ts = strtotime($to);
$diff = $date2_ts - $date1_ts;// - $date1_ts;
$NoOfDays = round($diff / 86400);
//$NoOfDays = str_replace("-","",$NoOfDays);
$MultiRvNO = DB::Connection('mysql2')->select('select rv_no from brige_table_sales_receipt where si_id = '.$fil->id.' group by rv_no ');

?>
<?php
if ($BalanceAmount>0):
if($NoOfDays <= 0){$total_not_yet_due+=$BalanceAmount;}
if ( in_array($NoOfDays, range(1,30))){$Tot_1_30+=$BalanceAmount;}
if ( in_array($NoOfDays, range(31,60))){ $Tot_31_60+=$BalanceAmount;}
if ( in_array($NoOfDays, range(61,90))){ $Tot_61_90+=$BalanceAmount;}
if ( in_array($NoOfDays, range(91,180))){$Tot_91_180+=$BalanceAmount;}
if ( in_array($NoOfDays, range(181,10000))){$Tot_180_1000+=$BalanceAmount;}
$TotOverAll+=$BalanceAmount;
?>

<?php  endif;
endforeach;
if($TotOverAll > 0):
?>

<tr class="text-center ApnaBorder">

    <td colspan="8" class="ApnaBorder text-center"><?php echo CommonHelper::byers_name($Cfil->id)->name;?>  </td>
    <td class="ApnaBorder "><?php echo number_format($total_not_yet_due,2); $total_not_yet_due_end+=$total_not_yet_due;?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_1_30,2); $Tot_1_30End+=$Tot_1_30;?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_31_60,2); $Tot_31_60End+=$Tot_31_60;?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_61_90,2); $Tot_61_90End+=$Tot_61_90;?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_91_180,2); $Tot_91_180End+=$Tot_91_180;?></td>
    <td   class="ApnaBorder "><?php echo number_format($Tot_180_1000,2); $Tot_180_1000End+=$Tot_180_1000;?></td>
    <td class="ApnaBorder Chnage-bg"><?php echo number_format($TotOverAll,2); $TotOverAllEnd+=$TotOverAll;?></td>
    <td  class="ApnaBorder Chnage-bg hide"><?php echo number_format($amount,2); ?></td>

</tr>

<?php
endif;
endforeach;?>
<tr class="text-center ApnaBorder">
    <th colspan="8" class="ApnaBorder text-center" style="font-size: 20px;">Grand Total</th>
    <td class="ApnaBorder" style="font-size: 20px;"><?php echo number_format($total_not_yet_due_end,2)?></td>
    <td class="ApnaBorder" style="font-size: 20px;"><?php echo number_format($Tot_1_30End,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_31_60End,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_61_90End,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_91_180End,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_180_1000End,2);?></td>
    <td class="ApnaBorder Chnage-bg"><?php echo number_format($TotOverAllEnd,2);?></td>
</tr>
</tbody>
</table>

<li>
    <h6>Invoice payable to you</h6>
    <ul>
        <li>
            <p>Due in 1-30 days</p>
            <p>
                <?php echo number_format($Tot_1_30End,2);?>
            </p>
        </li>
        <li>
            <p>Due in 31-60 days</p>
            <p>
                <?php echo number_format($Tot_31_60End,2);?>
            </p>
        </li>
        <li>
            <p>Due in 61-90 days</p>
            <p>
                <?php echo number_format($Tot_61_90End,2);?>
            </p>
        </li>
    </ul>
</li>
<span class="hide" id="salesagingtotal"><?php echo number_format($TotOverAllEnd,2);?></span>

<script !src="">
    //table to excel (multiple table)
    var array1 = new Array();
    var n = 1; //Total table

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
