<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$Clause = '';

$Tot_1_30End = 0;
$Tot_31_60End = 0;
$Tot_61_90End = 0;
$Tot_91_180End = 0;
$Tot_180_1000End = 0;
$TotOverAllEnd = 0;
$TotNotYetDueEnd = 0;
$remaining=0;

?>
<?php
if($_GET['SupplierId'] == 'all')
{$Clause = '';}
else{$Clause = 'and b.supplier="'.$_GET['SupplierId'].'"';}

$Supp = DB::Connection('mysql2')->select('select a.id,a.name,a.acc_id from supplier a
                                          INNER JOIN new_purchase_voucher b ON b.supplier = a.id
                                          WHERE b.status = 1
                                          '.$Clause.'
                                          and (b.pv_date between "'.$from.'" and "'.$to.'" or (b.grn_id=0 and b.work_order_id=0))
                                          GROUP BY b.supplier');
$MainCount =  count($Supp);
$VendorCounter=1;
$main_count=1;
?>

<style>
   .var-report-card{background:#fff;border:1px solid #dde1ee;border-radius:14px;padding:26px 30px 30px 30px;}
.var-report-card .print-header{text-align:center;margin-bottom:8px;}

.var-table-wrap{margin-top:22px;border:1px solid #1f2a5c;border-radius:10px;overflow:hidden;}
.var-table-scroll{overflow-x:auto;}
table.var-aging-table{width:100%;min-width:960px;border-collapse:collapse;font-size:14px;background:#fff;}

table.var-aging-table thead th{color:#2b3350;font-weight:700;text-transform:uppercase;letter-spacing:.3px;font-size:11.5px;text-align:center;padding:14px 10px;border:none;border-bottom:2px solid #1f2a5c;}
table.var-aging-table thead th:first-child{text-align:left;padding-left:20px;}

/* Supplier header */
table.var-aging-table thead th.var-h-supplier{background:#eef1f8;}
/* Aging bucket headers - severity gradient: green (not due / fresh) -> red (very overdue) */
table.var-aging-table thead th.var-h-notdue{background:#e3f5ea;color:#1c7a45;}
table.var-aging-table thead th.var-h-1-30{background:#eaf3fb;color:#245b9c;}
table.var-aging-table thead th.var-h-31-60{background:#fdf3d9;color:#8a6b0c;}
table.var-aging-table thead th.var-h-61-90{background:#fce8d6;color:#a85c17;}
table.var-aging-table thead th.var-h-91-180{background:#fbdede;color:#b13333;}
table.var-aging-table thead th.var-h-180plus{background:#f8d0d0;color:#9c1f1f;}
table.var-aging-table thead th.var-h-total{background:#1f2a5c;color:#fff;}

table.var-aging-table tbody td,table.var-aging-table tbody th{padding:12px 10px;text-align:center;border:none;border-bottom:1px solid #eef0f5;color:#333;font-weight:400;}
table.var-aging-table tbody th{text-align:left;padding-left:20px;font-weight:600;color:#2b3350;background:#f7f8fb;}
table.var-aging-table tbody tr:hover td,table.var-aging-table tbody tr:hover th{background:#eef3ff;}

/* subtle tint per bucket column in body, lighter than header */
table.var-aging-table tbody td.var-b-notdue{background:#f4fbf6;}
table.var-aging-table tbody td.var-b-1-30{background:#f5f9fd;}
table.var-aging-table tbody td.var-b-31-60{background:#fefaee;}
table.var-aging-table tbody td.var-b-61-90{background:#fef6ee;}
table.var-aging-table tbody td.var-b-91-180{background:#fdf1f1;}
table.var-aging-table tbody td.var-b-180plus{background:#fceded;}
table.var-aging-table tbody tr:hover td.var-b-notdue,
table.var-aging-table tbody tr:hover td.var-b-1-30,
table.var-aging-table tbody tr:hover td.var-b-31-60,
table.var-aging-table tbody tr:hover td.var-b-61-90,
table.var-aging-table tbody tr:hover td.var-b-91-180,
table.var-aging-table tbody tr:hover td.var-b-180plus{background:#eef3ff;}

table.var-aging-table .var-total-col{background:#eef1fb !important;font-weight:700;color:#1f2a5c;}
table.var-aging-table .var-mismatch{color:#c0293c;font-weight:700;}

table.var-aging-table .var-grand-total-row th,
table.var-aging-table .var-grand-total-row td{
    font-size:15px !important;
    font-weight:700;
    border-top:2px solid #1f2a5c;
    border-bottom:none;
    background:#1f2a5c !important;
    color:#fff !important;
    padding:16px 10px !important;
}
table.var-aging-table .var-grand-total-row .var-total-col{background:#141c40 !important;color:#fff !important;}

.hide{display:none;}

@media print{
    body{-webkit-print-color-adjust:exact;print-color-adjust:exact;color-adjust:exact;}
    .var-report-card{border:none;padding:0;}
    .var-table-wrap{border:1px solid #1f2a5c;border-radius:0;}
    .var-table-scroll{overflow:visible;}
    table.var-aging-table{width:100%;min-width:0;table-layout:fixed;font-size:10.5px;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
    table.var-aging-table thead th{padding:6px 4px;font-size:9px;}
    table.var-aging-table tbody td,table.var-aging-table tbody th{padding:6px 4px;}
    table.var-aging-table tbody th{padding-left:6px;}
    table.var-aging-table thead th:first-child{padding-left:6px;}
    table.var-aging-table .var-grand-total-row th,
    table.var-aging-table .var-grand-total-row td{padding:8px 4px !important;font-size:11px !important;}
}
</style>

<div class="var-report-card">

    <?php echo CommonHelper::headerPrintSectionInPrintView(Session::get('run_company'), 'Vendor Ageing Summary Report', 'AS ON '.date_format(date_create($to),'F d, Y'));?>

    <div class="var-table-wrap">
    <div class="var-table-scroll">
    <table class="table var-aging-table" id="export_table_to_excel_1">
        <thead>
        </thead>
        <thead>
        <tr title="" class="text-center">
            <th colspan="8" class="text-center var-h-supplier">Supplier</th>
            <th class="text-center var-h-notdue">Not Yet Due</th>
            <th class="text-center var-h-1-30">(1-30)</th>
            <th class="text-center var-h-31-60">(31-60)</th>
            <th class="text-center var-h-61-90">(61-90)</th>
            <th class="text-center var-h-91-180">(91-180)</th>
            <th class="text-center var-h-180plus">More Than 180 days</th>
            <th class="text-center var-h-total">Total Amount</th>
        </tr>
        </thead>
        <tbody>

<?php
        $couter=1;
        $total_amount=0;
foreach($Supp as $Sfil):

    $vendor_data=DB::Connection('mysql2')->select('select a.id,a.due_date,a.pv_no,a.pv_date,a.slip_no,(sum(b.net_amount)+a.sales_tax_amount)total,a.grn_id
                from new_purchase_voucher a
                inner join
                new_purchase_voucher_data b
                on
                a.id=b.master_id

                where a.status=1
               and(a.pv_date between "'.$from.'" and "'.$to.'" or grn_id=0 and work_order_id=0)

                and a.supplier="'.$Sfil->id.'"
                group by a.id');
    ?>

<?php
$TotInvoiceAmount = 0;
$TotReturnAmount = 0;
$TotPaidAmount = 0;
$TotBalance = 0;
$Tot_1_30 = 0;
$Tot_31_60 = 0;
$Tot_61_90 = 0;
$Tot_91_180 = 0;
$Tot_180_1000 = 0;
$TotOverAll = 0;
$TotNotYet = 0;



   $debit=   DB::Connection('mysql2')->selectOne('select sum(amount)amount from transactions where status=1 and debit_credit=0 and acc_id="'.$Sfil->acc_id.'"
   and v_date between "'.$from.'" and "'.$to.'"')->amount;
    $credit=   DB::Connection('mysql2')->selectOne('select sum(amount)amount from transactions where status=1 and debit_credit=1 and acc_id="'.$Sfil->acc_id.'"
   and   v_date between "'.$from.'" and "'.$to.'"')->amount;

 $amount=$debit-$credit;
$total_amount+=$amount;
//$amount=   DB::Connection('mysql2')->selectOne('select sum(balance_amount)amount from vendor_opening_balance where vendor_id="'.$Sfil->id.'"')->amount;
       // $amount=0;
foreach($vendor_data as $fil):

$no=0;
$one=0;
$two=0;
$three=0;
$four=0;
$five=0;
$InvoiceAmount = $fil->total;
//  $PaidAmount = CommonHelper::PaymentPurchaseAmountCheck_aging($fil->id);
$PaidAmount = CommonHelper::PaymentPurchaseAmountCheck_aging($fil->id,$from,$to);
$return_amount=ReuseableCode::return_amount_by_date($fil->grn_id,2,$from,$to);
$BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;



// Calculating the difference in timestamps
$diffss = strtotime($fil->due_date) - strtotime($fil->pv_date);

// 1 day = 24 hours
// 24 * 60 * 60 = 86400 seconds
$diffss = abs(round($diffss / 86400));



$date1_ts = strtotime($fil->pv_date.'+'.$diffss.'day');
$date2_ts = strtotime($to);
$diff = $date2_ts - $date1_ts;
$NoOfDays = round($diff / 86400);
if($BalanceAmount > 0):
if($NoOfDays <= 0){$TotNotYet+=$BalanceAmount; };
if ( in_array($NoOfDays, range(1,30))){$Tot_1_30+=$BalanceAmount; $one=$BalanceAmount;}
if ( in_array($NoOfDays, range(31,60))){  $Tot_31_60+=$BalanceAmount; $two=$BalanceAmount;}
if ( in_array($NoOfDays, range(61,90))){  $Tot_61_90+=$BalanceAmount; $three=$BalanceAmount;}
if ( in_array($NoOfDays, range(91,180))){  $Tot_91_180+=$BalanceAmount; $four=$BalanceAmount;}
if ( in_array($NoOfDays, range(181,10000))){  $Tot_180_1000+=$BalanceAmount; $five=$BalanceAmount;}
$TotOverAll+=$BalanceAmount;
?>

<?php
endif;
endforeach;?>
<?php if($TotOverAll > 0):?>
<tr title="{{$VendorCounter++}}" class="text-center yes">
    <th colspan="8" class="text-center"><?php echo CommonHelper::get_supplier_name($Sfil->id)?></th>
    <td class="var-b-notdue"><?php echo number_format($TotNotYet,2); $TotNotYetDueEnd+=$TotNotYet;?></td>
    <td class="var-b-1-30"><?php echo number_format($Tot_1_30,2); $Tot_1_30End+=$Tot_1_30;?></td>
    <td class="var-b-31-60"><?php echo number_format($Tot_31_60,2); $Tot_31_60End+=$Tot_31_60;?></td>
    <td class="var-b-61-90"><?php echo number_format($Tot_61_90,2); $Tot_61_90End+=$Tot_61_90;?></td>
    <td class="var-b-91-180"><?php echo number_format($Tot_91_180,2); $Tot_91_180End+=$Tot_91_180?></td>
    <td class="var-b-180plus"><?php echo number_format($Tot_180_1000,2); $Tot_180_1000End+=$Tot_180_1000;?></td>

    <td class="var-total-col <?php if($amount!=$TotOverAll) echo 'var-mismatch'; ?>"><?php echo number_format($TotOverAll,2); $TotOverAllEnd+=$TotOverAll;?></td>
    <td class="var-total-col hide"><?php echo number_format($amount,2)?></td>
    <?php $remaining+=$TotOverAll-$amount; ?>
    <td class="var-total-col hide"><?php echo number_format($TotOverAll-$amount,2)?></td>
</tr>
<?php endif;?>
<?php endforeach;?>
<tr class="text-center var-grand-total-row">
    <th colspan="8" class="text-center">Grand Total</th>
    <td><?php echo number_format($TotNotYetDueEnd,2);?></td>
    <td><?php echo number_format($Tot_1_30End,2);?></td>
    <td><?php echo number_format($Tot_31_60End,2);?></td>
    <td><?php echo number_format($Tot_61_90End,2);?></td>
    <td><?php echo number_format($Tot_91_180End,2);?></td>
    <td><?php echo number_format($Tot_180_1000End,2);?></td>
    <td class="var-total-col"><?php echo number_format($TotOverAllEnd,2);?></td>
    <td class="hide">{{number_format($total_amount,2)}}</td>
    <td class="hide">{{number_format($remaining,2)}}</td>
</tr>
</tbody>
</table>
    </div>
    </div>
</div>

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