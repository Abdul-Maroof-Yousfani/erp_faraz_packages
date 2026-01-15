<?php
use App\Helpers\FinanceHelper;
use App\Models\Account;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];


$selectAccountHead = $_GET['selectAccountHead'];
if(!empty($selectAccountHead)){
    $selectAccountHeadTitle = $selectAccountHead;
    $selectAccountHeadId = $_GET['selectAccountHeadId'];
    $accountCondition = ['jv_data.acc_id' => $selectAccountHeadId];
}else{
    $selectAccountHeadTitle = ' All Account Head ';
    $accountCondition = ['jvs.voucherType' => '1'];
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
    ->select('jvs.jv_no','jvs.id','jvs.slip_no','jvs.jv_date','jvs.jv_status','jvs.status','jvs.voucherType','jv_data.acc_id','jvs.paid','jvs.id')
    ->join('jv_data', 'jvs.id', '=', 'jv_data.master_id')
    ->whereBetween('jvs.jv_date',[$fromDate,$toDate])
    ->where('jvs.status',1)
    ->where($accountCondition)
   ->groupBy('jvs.id')
    ->get();
?>
<tr>
    <td colspan="9" class="text-center">
        <strong>Filter By : (Account Head => <?php echo $selectAccountHeadTitle;?>)&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => <?php echo FinanceHelper::changeDateFormat($fromDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => <?php echo FinanceHelper::changeDateFormat($toDate);?>)&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => <?php ?>)</strong>
    </td>
</tr>
<?php
foreach ($jvs as $row1) {

        $paid=$row1->paid;
?>
<tr class="{{$row1->id}}" title="{{$row1->id}}" @if($paid==1) style="background-color:#ccc" @endif>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo $row1->id;?></td>
    <td class="text-center"><?php echo $row1->jv_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->jv_date);?></td>
    <td class="text-center"><?php
        $d_acc = DB::selectOne('select accounts.name name from `jv_data`
					inner join `accounts` on accounts.id = jv_data.acc_id where jv_data.debit_credit = 1 and jv_data.master_id = \''.$row1->id.'\'')->name;

        $c_acc = DB::selectOne('select accounts.name name from `jv_data`
					inner join `accounts` on accounts.id = jv_data.acc_id where jv_data.debit_credit = 0 and jv_data.master_id = \''.$row1->id.'\'')->name;

        $debit_amount = DB::selectOne("select sum(`amount`) total from `jv_data` where `debit_credit` = 1 and `master_id` = '".$row1->id."'")->total;

        $credit_amount = DB::selectOne("select sum(`amount`) total from `jv_data` where `debit_credit` = 0 and `master_id` = '".$row1->id."'")->total;
        echo 'Dr'.'='.$d_acc.' ['.number_format($debit_amount,2).'] / Cr'.'='.".$c_acc".'['.number_format($credit_amount,2).']';
        ?></td>
    <td class="text-center"><?php echo $row1->slip_no;?></td>
    <td class="text-center"><?php if($row1->jv_status == 2){echo "Approved";} else{echo "Pending";}?></td>
    <td class="text-right"><?php $makeTotalAmount += $debit_amount;?><?php echo number_format($debit_amount,2);?></td>
    <td class="text-right"><?php echo number_format($makeTotalAmount,2);?></td>
    <td class="text-center hidden-print">
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu">
                <li>

                    <a onclick="showDetailModelOneParamerter('fdc/viewJournalVoucherDetail','<?php echo $row1->id;?>','View Journal Voucher Detail')" class=""><i class="fa-regular fa-eye"></i> View</a>
                    @if($paid==0)
                    <a href="<?php echo  URL::to('/finance/editJournalVoucherForm/'.$row1->id.'?m='.$m); ?>" type="button" class=""><i class="fa-solid fa-pencil"></i> Edit</a>
                    <?php date_default_timezone_set('Asia/karachi');
                    $JvId = $row1->id;
                    $JvNo = $row1->jv_no;
                    $UserName = Auth::user()->username;
                    $DeleteDate = date('Y-m-d');
                    $DeleteTime = date('h:i:s');
                    $ActivityType = 2;
                    ?>
                    <a class="" onclick="DeleteJvActivity('<?php echo $JvId;?>','<?php echo $JvNo;?>','<?php echo $UserName;?>','<?php echo $DeleteDate;?>','<?php echo $DeleteTime;?>','<?php echo $ActivityType;?>')"><i class="fa-solid fa-trash"></i> Delete</a>
                    @endif
                </li>
            </ul>
        </div>
    </td>
</tr>
<?php
}
?>
<tr>
    <th colspan="3" class="text-center"></th>
    <th class="text-center">Total Journal Voucher Amount</th>
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
