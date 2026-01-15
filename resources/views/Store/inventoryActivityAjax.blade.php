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
    $atData = DB::table('inventory_activity')->whereBetween('action_date',[$FromDate,$ToDate] )->where('voucher_no', 'like', '%' . $VoucherNo . '%')->get();
}
else
{
    $atData = DB::table('inventory_activity')->whereBetween('action_date',[$FromDate,$ToDate] )->get();
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
        if($Fil->table_name == 1){$TableName = 'demand';$VoucherNo = 'demand_no'; $VoucherType ='Purchase Request'; $SubStr='';}
        elseif($Fil->table_name == 2){$TableName = 'purchase_request';$VoucherNo = 'purchase_request_no'; $VoucherType ='Purchase Order'; $SubStr='';}
        elseif($Fil->table_name == 3){$TableName = 'goods_receipt_note';$VoucherNo = 'grn_no'; $VoucherType ='Goods Receipt Notes'; $SubStr = substr($Fil->voucher_no,0,3);}
        elseif($Fil->table_name == 4){$TableName = 'purchase_return';$VoucherNo = 'pr_no'; $VoucherType ='Debit Note'; $SubStr='';}
        elseif($Fil->table_name == 5){$TableName = 'new_purchase_voucher';$VoucherNo = 'pv_no'; $VoucherType ='Purchase Invoice'; $SubStr='';}
        elseif($Fil->table_name == 6){$TableName = 'stock_transfer';$VoucherNo = 'tr_no'; $VoucherType ='Stock Transfer'; $SubStr='';}
        else{$TableName = '';$Voucher = ''; $VoucherType='';}
        $SingleVoucher = DB::table($TableName)->where($VoucherNo,'=',$Fil->voucher_no)->first();

        FinanceHelper::reconnectMasterDatabase();
        //print_r($SingleVoucher);
        //echo $TableName;
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
                    <button onclick="showDetailModelOneParamerter('pdc/viewDemandVoucherDetail?m=<?php echo $m?>','<?php echo $Fil->voucher_no?>','View Purchase Request List')" type="button" class="btn btn-success btn-xs">View</button>
                <?php elseif($Fil->table_name == 2):?>
                    <button onclick="showDetailModelOneParamerter('stdc/viewPurchaseRequestVoucherDetail','<?php echo $SingleVoucher->id?>','View Purchase Request List','<?php echo $m?>')" type="button" class="btn btn-success btn-xs">View</button>
                <?php else:?>
                <?php endif;?>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="10" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
