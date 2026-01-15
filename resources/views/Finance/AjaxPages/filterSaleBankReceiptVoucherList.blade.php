<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use App\Models\Account;
use App\Models\Rvs;
use App\Models\Rvs_data;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectAccountHead = $_GET['selectAccountHead'];
if(!empty($selectAccountHead)){
    $selectAccountHeadTitle = $selectAccountHead;
    $selectAccountHeadId = $_GET['selectAccountHeadId'];
    $accountCondition = ['rv_data.acc_id' => $selectAccountHeadId];
}else{
    $selectAccountHeadTitle = 'All Account Head';
    $accountCondition = ['rvs.sale_receipt_type' => '2'];
}

if($selectVoucherStatus == '0'){
    $voucherStatusTitle = 'All Vouchers';
    $voucherCondition = ['rvs.sale_receipt_type' => '2'];
}else if($selectVoucherStatus == '1'){
    $voucherStatusTitle = 'Pending Vouchers';
    $voucherCondition = ['rvs.sale_receipt_type' => '2','rvs.rv_status' => '1','rvs.status' => '1'];
}else if($selectVoucherStatus == '2'){
    $voucherStatusTitle = 'Approve Vouchers';
    $voucherCondition = ['rvs.sale_receipt_type' => '2','rvs.rv_status' => '2','rvs.status' => '1'];
}else if($selectVoucherStatus == '3'){
    $voucherStatusTitle = 'Deleted Vouchers';
    $voucherCondition = ['rvs.sale_receipt_type' => '2','rvs.status' => '2'];
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
$rvs = DB::table('rvs')
    ->select('rvs.rv_no','rvs.rv_date','rvs.inv_no','rvs.inv_date','rvs.cheque_no','rvs.cheque_date','rvs.rv_status','rvs.slip_no','rvs.status','rvs.voucherType','rvs.sale_receipt_type','rv_data.acc_id')
    ->join('rv_data', 'rvs.rv_no', '=', 'rv_data.rv_no')
    ->whereBetween('rvs.rv_date',[$fromDate,$toDate])
    ->whereIn('voucherType', array(3, 4))
    ->where($voucherCondition)
    ->where($accountCondition)
    ->groupBy('rvs.rv_no')
    ->get();
?>
<tr>
    <td colspan="12" class="text-center">
        <strong>Filter By : (Account Head => <?php echo $selectAccountHeadTitle;?>)&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => <?php echo FinanceHelper::changeDateFormat($fromDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => <?php echo FinanceHelper::changeDateFormat($toDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => <?php echo $voucherStatusTitle;?>)</strong>
    </td>
</tr>
<?php
foreach ($rvs as $row1) {
?>
<tr>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo $row1->rv_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>
    <td class="text-center"><?php
        $d_acc = DB::selectOne('select accounts.name name from `rv_data`
					inner join `accounts` on accounts.id = rv_data.acc_id where rv_data.debit_credit = 1 and rv_data.rv_no = \''.$row1->rv_no.'\'')->name;

        $c_acc = DB::selectOne('select accounts.name name from `rv_data`
					inner join `accounts` on accounts.id = rv_data.acc_id where rv_data.debit_credit = 0 and rv_data.rv_no = \''.$row1->rv_no.'\'')->name;

        $debit_amount = DB::selectOne("select sum(`amount`) total from `rv_data` where `debit_credit` = 1 and `rv_no` = '".$row1->rv_no."'")->total;

        $credit_amount = DB::selectOne("select sum(`amount`) total from `rv_data` where `debit_credit` = 0 and `rv_no` = '".$row1->rv_no."'")->total;
        echo 'Dr = '.$d_acc.'['.number_format($debit_amount,0).'] / Cr = '.$c_acc.'['.number_format($credit_amount,0).']';
        ?></td>
    <td class="text-center"><?php echo $row1->cheque_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
    <td class="text-center"><?php echo $row1->inv_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->inv_date);?></td>
    <td class="text-center"><?php FinanceHelper::checkVoucherStatus($row1->rv_status,$row1->status);?></td>
    <td class="text-right"><?php $makeTotalAmount += $debit_amount;?><?php echo number_format($debit_amount,0);?></td>
    <td class="text-right"><?php echo number_format($makeTotalAmount,0);?></td>
    <td class="text-center hidden-print"><a onclick="showDetailModelOneParamerter('fdc/viewBankReceiptVoucherDetail','<?php echo $row1->rv_no;?>','View Cash R.V Detail')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"> V</span></a></td>
</tr>
<?php
}
?>
<tr>
    <th colspan="3" class="text-center">X <------> X</th>
    <th class="text-center">Total Cash Sale Receipt Voucher Amount</th>
    <th colspan="5" class="text-center">X <------> X</th>
    <th class="text-right"><?php echo number_format($makeTotalAmount,0); ?></th>
    <th colspan="2" class="text-center">X <------> X</th>
</tr>
<?php
CommonHelper::reconnectMasterDatabase();
?>