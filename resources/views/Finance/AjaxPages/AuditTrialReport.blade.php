<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

$FromDate = $_GET['FromDate'];
$ToDate = $_GET['ToDate'];
$VoucherNo = strtolower($_GET['VoucherNo']);
$m = $_GET['m'];
FinanceHelper::companyDatabaseConnection($m);
if($VoucherNo !="")
{
    $atData = DB::table('audit_trail')->whereBetween('action_date',[$FromDate,$ToDate] )->whereNotIn('table_name', [5])->where('voucher_no', 'like', '%' . $VoucherNo . '%')->get();
    $Accounts = DB::table('audit_trail')->where('table_name',5)->whereBetween('action_date',[$FromDate,$ToDate] )->where('voucher_no', 'like', '%' . $VoucherNo . '%')->get();
}
else
{
    $atData = DB::table('audit_trail')->whereBetween('action_date',[$FromDate,$ToDate] )->whereNotIn('table_name', [5])->get();
    $Accounts = DB::table('audit_trail')->where('table_name',5)->whereBetween('action_date',[$FromDate,$ToDate] )->get();
}

FinanceHelper::reconnectMasterDatabase();
?>
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Voucher No</th>
        <th class="text-center">Voucher Date</th>
        <th class="text-center">Voucher Type</th>
        <th class="text-center">Action</th>
        <th class="text-center">Action Date</th>
        <th class="text-center">Action Time</th>
        <th class="text-center">Amount</th>
        <th class="text-center">Username</th>
        <th class="text-center">View</th>
        </thead>
        <tbody id="filterBankPaymentVoucherList">
        <?php
        $counter = 1;

        foreach ($atData as $Fil) {
        FinanceHelper::companyDatabaseConnection($m);
        $TableName = '';
        $VoucherNo='';
        $VoucherType = '';
        $SubStr = "";
        if($Fil->table_name == 1){$TableName = 'new_jvs';$VoucherNo = 'jv_no'; $VoucherType ='Journal Voucher'; $SubStr='';}
        elseif($Fil->table_name == 2){$TableName = 'new_pv';$VoucherNo = 'pv_no'; $VoucherType ='Payment Voucher'; $SubStr='';}
        elseif($Fil->table_name == 3){$TableName = 'new_rvs';$VoucherNo = 'rv_no'; $VoucherType ='Receipt Voucher'; $SubStr = substr($Fil->voucher_no,0,3);}
        elseif($Fil->table_name == 4){$TableName = 'new_purchase_voucher';$VoucherNo = 'pv_no'; $VoucherType ='Purchase Voucher'; $SubStr='';}
        else{$TableName = '';$Voucher = ''; $VoucherType='';}
        $SingleVoucher = DB::table($TableName)->where($VoucherNo,'=',$Fil->voucher_no)->first();
        FinanceHelper::reconnectMasterDatabase();
        //echo $TableName;

        if(!empty($SingleVoucher))
        {
        
        ?>
        <tr class="text-center">
            <td><?php echo $counter++;?></td>
            <td><?php echo strtoupper($Fil->voucher_no);?></td>
            <td><?php echo FinanceHelper::changeDateFormat($Fil->voucher_date);?></td>
            <td><?php echo $VoucherType;?></td>
            <td><?php echo $Fil->action?></td>
            <td><?php echo FinanceHelper::changeDateFormat($Fil->action_date)?></td>
            <td><?php echo strtoupper($Fil->action_time)?></td>
            <td><?php echo number_format($Fil->amount,2);?></td>
            <td><b><?php echo $Fil->username;?></b></td>
            <td>
                <?php if($Fil->table_name == 1):?>
                <a onclick="showDetailModelOneParamerter('fdc/viewJournalVoucherDetail','<?php echo $SingleVoucher->id;?>','View Journal Voucher Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php elseif($Fil->table_name == 2):?>
                <a onclick="showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetail','<?php echo $SingleVoucher->id;?>','View Bank P.V Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php elseif($Fil->table_name == 3 && $SubStr == 'brv'):?>
                <a onclick="showDetailModelOneParamerter('fdc/viewBankRvDetailNew','<?php echo $SingleVoucher->id;?>','View Bank Reciept Voucher Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php elseif($Fil->table_name == 3 && $SubStr == 'crv'):?>
                <a onclick="showDetailModelOneParamerter('fdc/viewCashRvDetailNew','<?php echo $SingleVoucher->id;?>','View Cash Reciept Voucher Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php elseif($Fil->table_name == 4):?>
                <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $SingleVoucher->id;?>','View Purchase Voucher Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php else:?>
                <?php endif;?>
            </td>
        </tr>
        <?php
        }
        }
        ?>
        <tr>
            <th colspan="10" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
<?php  

    if(count($Accounts)>0):
    
?>

<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Account-Code</th>
        <th class="text-center">Account Name</th>
        <th class="text-center">Action</th>
        <th class="text-center">Action Date</th>
        <th class="text-center">Action Time</th>
        <th class="text-center">Username</th>
        </thead>
        <tbody id="filterBankPaymentVoucherList">
        <?php
        $counter = 1;

        foreach ($Accounts as $aFil) {
        FinanceHelper::companyDatabaseConnection($m);
        $TableName = '';
        $VoucherNo='';
        $VoucherType = '';
        $SubStr = "";

        $SingleAcc = DB::table('accounts')->where('code','=',$aFil->voucher_no)->first();
        FinanceHelper::reconnectMasterDatabase();
        //echo $TableName;
        if(!empty($SingleAcc))
        {
        ?>
        <tr class="text-center">
            <td><?php echo $counter++;?></td>
            <td><?php echo strtoupper($aFil->voucher_no);?></td>

            <td><?php echo $SingleAcc->name;?></td>
            <td><?php echo $aFil->action?></td>
            <td><?php echo FinanceHelper::changeDateFormat($aFil->action_date)?></td>
            <td><?php echo strtoupper($aFil->action_time)?></td>
            <td><b><?php echo $aFil ->username;?></b></td>
        </tr>
        <?php
        }
        }
        ?>
        <tr>
            <th colspan="10" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
<?php endif;?>