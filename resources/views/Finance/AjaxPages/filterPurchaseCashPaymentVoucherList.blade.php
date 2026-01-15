<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use App\Models\Account;
use App\Models\Pvs;
use App\Models\Pvs_data;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectAccountHead = $_GET['selectAccountHead'];
if(!empty($selectAccountHead)){
    $selectAccountHeadTitle = $selectAccountHead;
    $selectAccountHeadId = $_GET['selectAccountHeadId'];
    $accountCondition = ['pv_data.acc_id' => $selectAccountHeadId];
}else{
    $selectAccountHeadTitle = 'All Account Head';
    $accountCondition = ['pvs.voucherType' => '3'];
}

if($selectVoucherStatus == '0'){
    $voucherStatusTitle = 'All Vouchers';
    $voucherCondition = ['pvs.voucherType' => '3'];
}else if($selectVoucherStatus == '1'){
    $voucherStatusTitle = 'Pending Vouchers';
    $voucherCondition = ['pvs.voucherType' => '3','pvs.pv_status' => '1','pvs.status' => '1'];
}else if($selectVoucherStatus == '2'){
    $voucherStatusTitle = 'Approve Vouchers';
    $voucherCondition = ['pvs.voucherType' => '3','pvs.pv_status' => '2','pvs.status' => '1'];
}else if($selectVoucherStatus == '3'){
    $voucherStatusTitle = 'Deleted Vouchers';
    $voucherCondition = ['pvs.voucherType' => '3','pvs.status' => '2'];
}
$counter = 1;
$makeTotalAmount = 0;
CommonHelper::companyDatabaseConnection($_GET['m']);
$accounts = new Account;
$accounts = $accounts::orderBy('level1', 'ASC')
    ->orderBy('level2', 'ASC')
    ->orderBy('level3', 'ASC')
    ->orderBy('level4', 'ASC')
    ->orderBy('level5', 'ASC')
    ->orderBy('level6', 'ASC')
    ->orderBy('level7', 'ASC')
    ->get();
$pvs = DB::table('pvs')
    ->select('pvs.pv_no','pvs.pv_date','pvs.slip_no','pvs.pv_status','pvs.status','pv_data.acc_id')
    ->join('pv_data', 'pvs.pv_no', '=', 'pv_data.pv_no')
    ->whereBetween('pvs.pv_date',[$fromDate,$toDate])
    ->where($voucherCondition)
    ->where($accountCondition)
    ->groupBy('pvs.pv_no')
    ->get();


?>
<tr>
    <td colspan="9" class="text-center">
        <strong>Filter By : (Account Head => <?php echo $selectAccountHeadTitle;?>)&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => <?php echo FinanceHelper::changeDateFormat($fromDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => <?php echo FinanceHelper::changeDateFormat($toDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => <?php echo $voucherStatusTitle;?>)</strong>
    </td>
</tr>
<?php
foreach ($pvs as $row1) {
?>
<tr>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo $row1->pv_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
    <td class="text-center"><?php
        $d_acc = DB::selectOne('select accounts.name name from `pv_data`
					inner join `accounts` on accounts.id = pv_data.acc_id where pv_data.debit_credit = 1 and pv_data.pv_no = \''.$row1->pv_no.'\'')->name;

        $c_acc = DB::selectOne('select accounts.name name from `pv_data`
					inner join `accounts` on accounts.id = pv_data.acc_id where pv_data.debit_credit = 0 and pv_data.pv_no = \''.$row1->pv_no.'\'')->name;

        $debit_amount = DB::selectOne("select sum(`amount`) total from `pv_data` where `debit_credit` = 1 and `pv_no` = '".$row1->pv_no."'")->total;

        $credit_amount = DB::selectOne("select sum(`amount`) total from `pv_data` where `debit_credit` = 0 and `pv_no` = '".$row1->pv_no."'")->total;
        echo 'Dr = '.$d_acc.'['.number_format($debit_amount,0).'] / Cr = '.$c_acc.'['.number_format($credit_amount,0).']';
        ?></td>
    <td class="text-center"><?php echo $row1->slip_no;?></td>
    <td class="text-center"><?php FinanceHelper::checkVoucherStatus($row1->pv_status,$row1->status);?></td>
    <td class="text-right"><?php $makeTotalAmount += $debit_amount;?><?php echo number_format($debit_amount,0);?></td>
    <td class="text-right"><?php echo number_format($makeTotalAmount,0);?></td>
    <td class="text-center hidden-print"><a onclick="showDetailModelOneParamerter('fdc/viewCashPaymentVoucherDetail','<?php echo $row1->pv_no;?>','View Cash P.V Detail')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"> V</span></a></td>
</tr>
<?php
}
?>
<tr>
    <th colspan="3" class="text-center">X <------> X</th>
    <th class="text-center">Total Purchase Cash Paymant Voucher Amount</th>
    <th colspan="2" class="text-center">X <------> X</th>
    <th class="text-right"><?php echo number_format($makeTotalAmount,0); ?></th>
    <th colspan="2" class="text-center">X <------> X</th>
</tr>
<script type="text/javascript">
    setTimeout(function() {
        $('.alert-danger').fadeOut('fast');
    }, 500);

    setTimeout(function() {
        $('.alert-warning').fadeOut('fast');
    }, 500);

    setTimeout(function() {
        $('.alert-success').fadeOut('fast');
    }, 500);

    setTimeout(function() {
        $('.alert-info').fadeOut('fast');
    }, 500);
</script>
<?php
CommonHelper::reconnectMasterDatabase();
?>