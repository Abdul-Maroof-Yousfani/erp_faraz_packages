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
// if($_GET['SupplierId'] == 'all')
// {$Clause = '';}
// else{$Clause = 'and b.supplier="'.$_GET['SupplierId'].'"';}

$Supp = DB::Connection('mysql2')->select('select a.id,a.name,a.acc_id from supplier a
                                          INNER JOIN new_purchase_voucher b ON b.supplier = a.id
                                          inner join accounts c on a.acc_id=c.id
                                          WHERE b.status = 1
                                          and c.parent_code="2-2-1"
                                          '.$Clause.'
                                          and(b.pv_date between "'.$from.'" and "'.$to.'" or grn_id=0)
                                          GROUP BY b.supplier');
$MainCount =  count($Supp);
$VendorCounter=1;
$main_count=1;
?>
<table class="table table-bordered ApnaBorder hide" id="export_table_to_excel_1">
    <thead>
        <th colspan="15" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
    </thead>
    <thead>
        <th colspan="15" class="text-center">Vendor Ageing Summary Report</th>
    </thead>

    <thead>
    <th colspan="15" class="text-center">AS ON <?php echo e(date_format(date_create($to),'F d, Y')); ?></th>
    </thead>
    <thead>
        <th colspan="15" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
    </thead>
    <thead>
    <tr title="" class="text-center ApnaBorder ">
        <th colspan="8" class="text-center ApnaBorder ">Supplier</th>
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
<tr title="<?php echo e($VendorCounter++); ?>" class="text-center ApnaBorder yes">
    <th colspan="8" class="ApnaBorder text-center" ><?php echo CommonHelper::get_supplier_name($Sfil->id)?></th>
    <td class="ApnaBorder "><?php echo number_format($TotNotYet,2); $TotNotYetDueEnd+=$TotNotYet;?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_1_30,2); $Tot_1_30End+=$Tot_1_30;?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_31_60,2); $Tot_31_60End+=$Tot_31_60;?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_61_90,2); $Tot_61_90End+=$Tot_61_90;?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_91_180,2); $Tot_91_180End+=$Tot_91_180?></td>
    <td class="ApnaBorder "><?php echo number_format($Tot_180_1000,2); $Tot_180_1000End+=$Tot_180_1000;?></td>

    <td <?php if($amount!=$TotOverAll): ?> style="color: red;" <?php endif; ?> class="ApnaBorder Chnage-bg"><?php echo number_format($TotOverAll,2); $TotOverAllEnd+=$TotOverAll;?></td>
    <td class="ApnaBorder Chnage-bg hide"><?php echo number_format($amount,2)?></td>
    <?php $remaining+=$TotOverAll-$amount; ?>
    <td class="ApnaBorder Chnage-bg hide"><?php echo number_format($TotOverAll-$amount,2)?></td>
</tr>
<?php endif;?>
<?php endforeach;?>
<tr class="text-center ApnaBorder yes">
    <th colspan="8" class="ApnaBorder text-center" style="font-size: 20px;">Grand Total</th>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($TotNotYetDueEnd,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_1_30End,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_31_60End,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_61_90End,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_91_180End,2);?></td>
    <td class="ApnaBorder " style="font-size: 20px;"><?php echo number_format($Tot_180_1000End,2);?></td>
    <td class="ApnaBorder Chnage-bg" style="font-size: 20px;"><?php echo number_format($TotOverAllEnd,2);?></td>
    <td class="hide"><?php echo e(number_format($total_amount,2)); ?></td>
    <td class="hide"><?php echo e(number_format($remaining,2)); ?></td>
</tr>
</tbody>
</table>

<li>
    <h6>Payable bills you owe</h6>
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
<span class="hide" id="vendoragingtotal"><?php echo number_format($TotOverAllEnd,2);?></span>


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