<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

$FromDate = $_GET['FromDate'];
        //echo "<br>";
$ToDate = $_GET['ToDate'];
$VoucherNo = strtolower($_GET['VoucherNo']);
$VoucherNoL = strtolower($_GET['VoucherNo']);
$VoucherNoU = strtoupper($_GET['VoucherNo']);
$m = $_GET['m'];
//FinanceHelper::companyDatabaseConnection($m);
if($VoucherNo !="")
{
    $atData = DB::Connection('mysql2')->select('select * from sales_activity
                                                where action_date BETWEEN "'.$FromDate.'" and "'.$ToDate.'"
                                                and voucher_no like "%'.$VoucherNoL.'%"
                                                and table_name  != 5
                                                or voucher_no like "%'.$VoucherNoU.'%"
                                                ');


}
else
{
    $atData = DB::Connection('mysql2')->table('sales_activity')->whereBetween('action_date',[$FromDate,$ToDate])->where('table_name','!=',5)->get();
}

//FinanceHelper::reconnectMasterDatabase();

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

        $TableName = '';
        $VoucherNo='';
        $VoucherType = '';
        $SubStr = "";
        if($Fil->table_name == 1){$TableName = 'sales_order';$VoucherNo = 'so_no'; $VoucherType ='Sales Order'; $SubStr='';}
        elseif($Fil->table_name == 2){$TableName = 'delivery_note';$VoucherNo = 'gd_no'; $VoucherType ='Delivery Note'; $SubStr='';}
        elseif($Fil->table_name == 3){$TableName = 'sales_tax_invoice';$VoucherNo = 'gi_no'; $VoucherType ='Sale Tax Invoice'; $SubStr = substr($Fil->voucher_no,0,3);}
        elseif($Fil->table_name == 4){$TableName = 'credit_note';$VoucherNo = 'cr_no'; $VoucherType ='Sales Return'; $SubStr='';}
        elseif($Fil->table_name == 5){$TableName = '';$VoucherNo = ''; $VoucherType =''; $SubStr='';}
        else{$TableName = '';$Voucher = ''; $VoucherType='';}
        $SingleVoucher = DB::Connection('mysql2')->table($TableName)->where($VoucherNo,'=',$Fil->voucher_no)->first();

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
                <a onclick="showDetailModelOneParamerter('sales/viewSalesOrderDetail','<?php echo $SingleVoucher->id;?>','View Sales Order Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php elseif($Fil->table_name == 2):?>
                <a onclick="showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/<?php echo $SingleVoucher->id;?>','','View Delivery Note Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php elseif($Fil->table_name == 3):?>
                <a onclick="showDetailModelOneParamerter('fdc/viewBankRvDetailNew','<?php echo $SingleVoucher->id;?>','View Bank Reciept Voucher Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php elseif($Fil->table_name == 4):?>
                <a onclick="showDetailModelOneParamerter('fdc/viewCashRvDetailNew','<?php echo $SingleVoucher->id;?>','View Cash Reciept Voucher Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                <?php elseif($Fil->table_name == 5):?>
                <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $SingleVoucher->id;?>','View Purchase Voucher Detail (<?php echo $Fil->action?>)','<?php echo $m?>','audit_trial')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span> View</a>
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
