<?php
use App\Helpers\FinanceHelper;
use App\Models\Account;
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
    $accountCondition = ['rvs.voucherType' => '2'];
}

/*
if($selectVoucherStatus == '0'){
    $voucherStatusTitle = 'All Vouchers';
    $voucherCondition = ['rvs.voucherType' => '2'];
}else if($selectVoucherStatus == '1'){
    $voucherStatusTitle = 'Pending Vouchers';
    $voucherCondition = ['rvs.voucherType' => '2','rvs.rv_status' => '1','rvs.status' => '1'];
}else if($selectVoucherStatus == '2'){
    $voucherStatusTitle = 'Approve Vouchers';
    $voucherCondition = ['rvs.voucherType' => '2','rvs.rv_status' => '2','rvs.status' => '1'];
}else if($selectVoucherStatus == '3'){
    $voucherStatusTitle = 'Deleted Vouchers';
    $voucherCondition = ['rvs.voucherType' => '2','rvs.status' => '2'];
}
*/

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
$rvs = DB::table('rvs')
    ->select('rvs.rv_no','rvs.id','rvs.rv_date','rvs.slip_no','rvs.cheque_no','rvs.cheque_date','rvs.rv_status','rvs.status','rv_data.acc_id')
    ->join('rv_data', 'rvs.id', '=', 'rv_data.master_id')
    ->whereBetween('rvs.rv_date',[$fromDate,$toDate])
    ->where('rvs.status',1)
    ->where($accountCondition)
    ->groupBy('rvs.id')
        ->orderBy('rvs.rv_date')
    ->get();
?>
<tr>
    <td colspan="11" class="text-center">
        <strong>Filter By : (Account Head => <?php echo $selectAccountHeadTitle;?>)&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => <?php echo FinanceHelper::changeDateFormat($fromDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => <?php echo FinanceHelper::changeDateFormat($toDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => <?php //echo $voucherStatusTitle;?>)</strong>
    </td>
</tr>
<?php
foreach ($rvs as $row1) {

    $count=    DB::Connection('mysql2')->table('received_paymet')->where('receipt_id',$row1->id)->count();
?>
<tr title="<?php echo $row1->id ?>" <?php  if ($count >0): ?>style="background-color: darkgray" <?php  endif; ?>>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo $row1->rv_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>
    <td class="text-center"><?php
        $d_acc = DB::selectOne('select accounts.name name from `rv_data`
					inner join `accounts` on accounts.id = rv_data.acc_id where rv_data.debit_credit = 1 and rv_data.master_id = \''.$row1->id.'\'')->name;

        $c_acc = DB::selectOne('select accounts.name name from `rv_data`
					inner join `accounts` on accounts.id = rv_data.acc_id where rv_data.debit_credit = 0 and rv_data.master_id = \''.$row1->id.'\'')->name;

        $debit_amount = DB::selectOne("select sum(`amount`) total from `rv_data` where `debit_credit` = 1 and `master_id` = '".$row1->id."'")->total;

        $credit_amount = DB::selectOne("select sum(`amount`) total from `rv_data` where `debit_credit` = 0 and `master_id` = '".$row1->id."'")->total;
        echo 'Dr = '.$d_acc.'['.number_format($debit_amount,2).'] / Cr = '.$c_acc.'['.number_format($credit_amount,2).']';
        $edit_url= url('/finance/editBankReceiptVoucherForm/'.$row1->id.'?m='.$m);
        ?></td>
    <td class="text-center"><?php echo $row1->slip_no;?></td>
    <td class="text-center"><?php echo $row1->cheque_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
    <td class="text-center"><?php if($row1->rv_status == 2){echo "Approved";} else{echo "Pending";}?></td>
    <td class="text-right"><?php $makeTotalAmount += $debit_amount;?><?php echo number_format($debit_amount,2);?></td>
    <td class="text-right"><?php echo number_format($makeTotalAmount,2);?></td>
    <td class="text-center hidden-print"> <a onclick="showDetailModelOneParamerter('fdc/viewBankReceiptVoucherDetail','<?php echo $row1->id;?>','View Bank R.V Detail')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"> V</span>
        </a>
        <a href='<?php echo $edit_url ?>' type="button" class="btn btn-primary btn-xs">Edit</a>

        <?php

        date_default_timezone_set('Asia/karachi');
        $RvId = $row1->id;
        $RvNo = $row1->rv_no;
        $UserName = Auth::user()->username;
        $DeleteDate = date('Y-m-d');
        $DeleteTime = date('h:i:s');
        $ActivityType = 2;
        ?>
        <button class="btn btn-xs btn-danger"
                onclick="DeleteRvActivity('<?php echo $RvId;?>','<?php echo $RvNo;?>','<?php echo $UserName;?>','<?php echo $DeleteDate;?>','<?php echo $DeleteTime;?>','<?php echo $ActivityType;?>')">
            Delete</button>

    </td>
</tr>
<?php
}
?>
<tr>
    <th colspan="3" class="text-center"></th>
    <th class="text-center">Total Bank Receipt Voucher Amount</th>
    <th colspan="4" class="text-center"></th>
    <th class="text-right"><?php echo number_format($makeTotalAmount,2); ?></th>
    <th colspan="2" class="text-center"></th>
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
FinanceHelper::reconnectMasterDatabase();
?>
