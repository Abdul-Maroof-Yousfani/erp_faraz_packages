<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\Account;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];
//$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectAccountHead = $_GET['selectAccountHead'];
if(!empty($selectAccountHead)){
    $selectAccountHeadTitle = $selectAccountHead;
    $selectAccountHeadId = $_GET['selectAccountHeadId'];
    $accountCondition = ['pv_data.acc_id' => $selectAccountHeadId];
}else{
    $selectAccountHeadTitle = ' All Account Head ';
    $accountCondition = ['pvs.voucherType' => '1'];
}

/*
if($selectVoucherStatus == '0'){
    $voucherStatusTitle = 'All Vouchers';
    $voucherCondition = ['pvs.voucherType' => '1'];
}else if($selectVoucherStatus == '1'){
    $voucherStatusTitle = 'Pending Vouchers';
    $voucherCondition = ['pvs.voucherType' => '1','pvs.pv_status' => '1','pvs.status' => '1'];
}else if($selectVoucherStatus == '2'){
    $voucherStatusTitle = 'Approve Vouchers';
    $voucherCondition = ['pvs.voucherType' => '1','pvs.pv_status' => '2','pvs.status' => '1'];
}else if($selectVoucherStatus == '3'){
    $voucherStatusTitle = 'Deleted Vouchers';
    $voucherCondition = ['pvs.voucherType' => '1','pvs.status' => '2'];
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
$pvs = DB::table('pvs')
    ->select('pvs.pv_no','pvs.id','pvs.pv_date','pvs.slip_no','pvs.pv_status','pvs.status','pv_data.acc_id','pvs.payment_type')
    ->join('pv_data', 'pvs.id', '=', 'pv_data.master_id')
    ->whereBetween('pvs.pv_date',[$fromDate,$toDate])
    ->where('pvs.status',1)
    ->where($accountCondition)
    ->groupBy('pvs.id')
        ->orderBy('pvs.pv_date')
    ->get();
?>
<tr>
    <td colspan="9" class="text-center">
        <strong>Filter By : (Account Head => <?php echo $selectAccountHeadTitle;?>)&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => <?php echo FinanceHelper::changeDateFormat($fromDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => <?php echo FinanceHelper::changeDateFormat($toDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => <?php ;?>)</strong>
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
					inner join `accounts` on accounts.id = pv_data.acc_id where pv_data.debit_credit = 1 and pv_data.master_id = \''.$row1->id.'\'')->name;

        $c_acc = DB::selectOne('select accounts.name name from `pv_data`
					inner join `accounts` on accounts.id = pv_data.acc_id where pv_data.debit_credit = 0 and pv_data.master_id = \''.$row1->id.'\'')->name;

        $debit_amount = DB::selectOne("select sum(`amount`) total from `pv_data` where `debit_credit` = 1 and `master_id` = '".$row1->id."'")->total;

        $credit_amount = DB::selectOne("select sum(`amount`) total from `pv_data` where `debit_credit` = 0 and `master_id` = '".$row1->id."'")->total;
        echo 'Dr = '.$d_acc.'&nbsp;&nbsp;&nbsp;'.' '.' '.number_format($debit_amount,2).''.'</br>'.' Cr = '.$c_acc.'&nbsp;&nbsp;&nbsp;'.' '.' '.number_format($credit_amount,2).'';
        ?></td>
    <td class="text-center"><?php echo $row1->slip_no;?></td>
    <td class="text-center"><?php if($row1->pv_status == 2){echo "Approved";} else{echo "Pending";}?></td>
    <td class="text-right"><?php $makeTotalAmount += $debit_amount;?><?php echo number_format($debit_amount,2);?></td>

    <?php   $count=CommonHelper::check_amount_in_ledger($row1->pv_no,$row1->id,2) ?>
    <td @if ($count >0) style="background-color: lightgray" @endif  class="text-center"><?php $makeTotalAmount += $debit_amount;?>

        <?php  if ($row1->payment_type==1): echo 'Direct';endif; if ($row1->payment_type==2): echo 'Advanced';endif;
        if ($row1->payment_type==3): echo 'Through Bill';endif;

        ?></td>
<td>
    <button onclick="showDetailModelOneParamerter('fdc/viewCashPaymentVoucherDetail','<?php echo $row1->id;?>','View Cash P.V Detail')" type="button" class="btn btn-success btn-xs">View</button>
    <a href="<?php echo  URL::to('/finance/editCashPaymentVoucherForm/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs">Edit </a>
    <?php date_default_timezone_set('Asia/karachi');

    $PvId = $row1->id;
    $PvNo = $row1->pv_no;
    $UserName = Auth::user()->username;
    $DeleteDate = date('Y-m-d');
    $DeleteTime = date('h:i:s');
    $ActivityType = 2;
    ?>
    <button class="btn btn-xs btn-danger disabled"
            onclick="DeletePvActivity('<?php echo $PvId;?>','<?php echo $PvNo;?>','<?php echo $UserName;?>','<?php echo $DeleteDate;?>','<?php echo $DeleteTime;?>','<?php echo $ActivityType;?>')">
        Delete</button>
</td>
</tr>
<?php
}
?>
<tr>
    <th colspan="3" class="text-center"></th>
    <th class="text-center">Total Cash Paymant Voucher Amount</th>
    <th colspan="2" class="text-center"></th>
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
