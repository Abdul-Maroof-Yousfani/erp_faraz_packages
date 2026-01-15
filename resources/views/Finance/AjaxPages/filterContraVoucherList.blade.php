<?php
use App\Helpers\FinanceHelper;
use App\Models\Account;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
echo $m = $_GET['m'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectAccountHead = $_GET['selectAccountHead'];


if(!empty($selectAccountHead)){
    $selectAccountHeadTitle = $selectAccountHead;
    $selectAccountHeadId = $_GET['selectAccountHeadId'];
    $accountCondition = ['rv_data.acc_id' => $selectAccountHeadId];
}else{
    $selectAccountHeadTitle = 'All Account Head';
    $accountCondition = ['rvs.status' => 1];
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
$rvs = DB::table('contra as rvs')
        ->select('rvs.cv_no','rvs.id','rvs.cv_date','rvs.slip_no','rvs.cheque_no','rvs.cheque_date','rvs.rv_status','rvs.status','rv_data.acc_id')
        ->join('contra_data as rv_data', 'rvs.id', '=', 'rv_data.master_id')
        ->whereBetween('rvs.cv_date',[$fromDate,$toDate])
        ->where('rvs.status',1)
        ->where($accountCondition)
        ->groupBy('rvs.id')
        ->orderBy('rvs.cv_date')
        ->get();
?>
<tr>
    <td colspan="11" class="text-center">
        <strong>Filter By : (Account Head => <?php echo $selectAccountHeadTitle;?>)&nbsp;&nbsp;,&nbsp;&nbsp;(From  Date => <?php echo FinanceHelper::changeDateFormat($fromDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => <?php echo FinanceHelper::changeDateFormat($toDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => <?php // $voucherStatusTitle;?>)</strong>
    </td>
</tr>
<?php
foreach ($rvs as $row1) {
?>
<tr>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo $row1->cv_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cv_date);?></td>
    <td class="text-center"><?php
        $d_acc = DB::selectOne('select accounts.name name from `contra_data`
					inner join `accounts` on accounts.id = contra_data.acc_id where contra_data.debit_credit = 1 and contra_data.master_id = \''.$row1->id.'\'')->name;

        $c_acc = DB::selectOne('select accounts.name name from `contra_data`
					inner join `accounts` on accounts.id = contra_data.acc_id where contra_data.debit_credit = 0 and contra_data.master_id = \''.$row1->id.'\'')->name;

        $debit_amount = DB::selectOne("select sum(`amount`) total from `contra_data` where `debit_credit` = 1 and `master_id` = '".$row1->id."'")->total;

        $credit_amount = DB::selectOne("select sum(`amount`) total from `contra_data` where `debit_credit` = 0 and `master_id` = '".$row1->id."'")->total;
        echo 'Dr = '.$d_acc.'&nbsp;&nbsp;&nbsp;'.' '.' '.number_format($debit_amount,2).''.'</br>'.' Cr = '.$c_acc.'&nbsp;&nbsp;&nbsp;'.' '.' '.number_format($credit_amount,2).'';
        ?></td>
    <td class="text-center"><?php echo $row1->slip_no;?></td>
    <td class="text-center"><?php echo $row1->cheque_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
    <td class="text-center"><?php if($row1->rv_status == 2){echo "Approved";} else{echo "Pending";}?></td>
    <td class="text-right"><?php $makeTotalAmount += $debit_amount;?><?php echo number_format($debit_amount,2);?></td>

    <td class="text-center hidden-print"> <a onclick="showDetailModelOneParamerter('fdc/viewContraVoucherDetail','<?php echo $row1->id;?>','View Contra Voucher')" class="btn btn-xs btn-success"><span class=""> View</span>
        </a>
        <?php date_default_timezone_set('Asia/karachi');

        $CvId = $row1->id;
        $CvNo = $row1->cv_no;
        $UserName = Auth::user()->username;
        $DeleteDate = date('Y-m-d');
        $DeleteTime = date('h:i:s');
        $ActivityType = 2;
        ?>
        <a href="<?php echo  URL::to('/finance/editContraVoucher/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs">Edit</a>
        <button class="btn btn-xs btn-danger"
        onclick="DeleteCvActivity('<?php echo $CvId;?>','<?php echo $CvNo;?>','<?php echo $UserName;?>','<?php echo $DeleteDate;?>','<?php echo $DeleteTime;?>','<?php echo $ActivityType;?>')">
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
