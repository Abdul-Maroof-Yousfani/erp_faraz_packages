<?php
use App\Helpers\FinanceHelper;
use App\Models\Account;
use App\Models\Jvs;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];

$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectAccountHead = $_GET['selectAccountHead'];
if(!empty($selectAccountHead)){
    $selectAccountHeadTitle = $selectAccountHead;
    $selectAccountHeadId = $_GET['selectAccountHeadId'];
    $accountCondition = ['jv_data.acc_id' => $selectAccountHeadId];
}else{
    $selectAccountHeadTitle = ' All Account Head ';
    $accountCondition = [];
}

if($selectVoucherStatus == '0'){
    $voucherStatusTitle = 'All Vouchers';
    $voucherCondition = [];
}else if($selectVoucherStatus == '1'){
    $voucherStatusTitle = 'Pending Vouchers';
    $voucherCondition = ['jvs.jv_status' => '1','jvs.status' => '1'];
}else if($selectVoucherStatus == '2'){
    $voucherStatusTitle = 'Approve Vouchers';
    $voucherCondition = ['jvs.jv_status' => '2','jvs.status' => '1'];
}else if($selectVoucherStatus == '3'){
    $voucherStatusTitle = 'Deleted Vouchers';
    $voucherCondition = ['jvs.status' => '2'];
}



$counter = 1;
$makeTotalAmount = 0;
FinanceHelper::companyDatabaseConnection($_GET['m']);
$accounts = new Account;
$accounts = $accounts::orderBy('level1', 'ASC')
    ->orderBy('level2', 'ASC')
    ->orderBy('level3', 'ASC')
    ->orderBy('level4', 'ASC')
    ->orderBy('level5', 'ASC')
    ->orderBy('level6', 'ASC')
    ->orderBy('level7', 'ASC')
    ->get();
$jvs = DB::table('jvs')
    ->select('jvs.jv_no','jvs.slip_no','jvs.jv_date','jvs.jv_status','jvs.status','jvs.voucherType','jv_data.acc_id')
    ->join('jv_data', 'jvs.jv_no', '=', 'jv_data.jv_no')
    ->whereBetween('jvs.jv_date',[$fromDate,$toDate])
    ->whereIn('voucherType', array(3, 4))
    ->where($voucherCondition)
    ->where($accountCondition)
    ->groupBy('jvs.jv_no')
    ->get();
/*$jvs = new Jvs;
$jvs = $jvs::whereBetween('jv_date',[$fromDate,$toDate])
    ->whereIn('voucherType', array(3, 4))
    ->get();*/
?>
<tr>
    <td colspan="9" class="text-center">
        <strong>Filter By : (Account Head => <?php echo $selectAccountHeadTitle;?>)&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => <?php echo FinanceHelper::changeDateFormat($fromDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => <?php echo FinanceHelper::changeDateFormat($toDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => <?php echo $voucherStatusTitle;?>)</strong>
    </td>
</tr>
<?php
foreach ($jvs as $row1) {
?>
<tr>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo $row1->jv_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->jv_date);?></td>
    <td class="text-center"><?php
        $d_acc = DB::selectOne('select accounts.name name from `jv_data`
					inner join `accounts` on accounts.id = jv_data.acc_id where jv_data.debit_credit = 1 and jv_data.jv_no = \''.$row1->jv_no.'\'')->name;

        $c_acc = DB::selectOne('select accounts.name name from `jv_data`
					inner join `accounts` on accounts.id = jv_data.acc_id where jv_data.debit_credit = 0 and jv_data.jv_no = \''.$row1->jv_no.'\'')->name;

        $debit_amount = DB::selectOne("select sum(`amount`) total from `jv_data` where `debit_credit` = 1 and `jv_no` = '".$row1->jv_no."'")->total;

        $credit_amount = DB::selectOne("select sum(`amount`) total from `jv_data` where `debit_credit` = 0 and `jv_no` = '".$row1->jv_no."'")->total;
        echo 'Dr = ['.number_format($debit_amount,0).'] / Cr = ['.number_format($credit_amount,0).']';
        ?></td>
    <td class="text-center"><?php echo $row1->slip_no;?></td>
    <td class="text-center"><?php FinanceHelper::checkVoucherStatus($row1->jv_status,$row1->status);?></td>
    <td class="text-right"><?php $makeTotalAmount += $debit_amount;?><?php echo number_format($debit_amount,0);?></td>
    <td class="text-right"><?php echo number_format($makeTotalAmount,0);?></td>
    <td class="text-center"><a onclick="showDetailModelOneParamerter('fdc/viewJournalVoucherDetail','<?php echo $row1->jv_no;?>','View Journal Voucher Detail')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"> V</span></a></td>
</tr>
<?php
}
?>
<tr>
    <th colspan="3" class="text-center">X <------> X</th>
    <th class="text-center">Total Sale Journal Voucher Amount</th>
    <th colspan="2" class="text-center">X <------> X</th>
    <th class="text-right"><?php echo number_format($makeTotalAmount,0); ?></th>
    <th colspan="2" class="text-center">X <------> X</th>
</tr>
<?php
FinanceHelper::reconnectMasterDatabase();
?>
