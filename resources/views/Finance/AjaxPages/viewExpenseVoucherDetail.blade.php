<?php



use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

$m = $_GET['m'];
$id = $_GET['id'];
if(isset($_GET['type'])){
    $Type = $_GET['type'];
} else{
    $Type="";
}

$currentDate = date('Y-m-d');
FinanceHelper::companyDatabaseConnection($m);
$ExpenseVoucher = DB::table('expense_voucher')->where('id','=',$id)->get();
FinanceHelper::reconnectMasterDatabase();
foreach ($ExpenseVoucher as $row) {

$username=$row->username;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php // FinanceHelper::displayApproveDeleteRepostButton($m,$row->pv_status,$row->status,$row->pv_no,'pv_no','pv_status','status');?>
        <?php echo CommonHelper::displayPrintButtonInView('printBankPaymentVoucherDetail','','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well" id="printBankPaymentVoucherDetail">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                        echo ' '.'('.date('D', strtotime($x)).')';?></label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 style="text-align: center;">Expense Voucher</h3>
                </div>

            </div>

            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:30%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:40%;">Voucher No</td>
                                <td style="width:60%;"><?php echo $row->ev_no;?></td>
                            </tr>
                            <tr>
                                <td>Voucher Date</td>
                                <td><?php echo FinanceHelper::changeDateFormat($row->ev_date);?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div style="width:40%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:40%;">Debit Account Head</td>
                                <td style="width:60%;"><?php echo CommonHelper::get_account_name($row->debit_acc_id);?></td>
                            </tr>
                            <tr>
                                <td>Credit Account Head</td>
                                <td style="width:60%;"><?php echo CommonHelper::get_account_name($row->credit_acc_id);?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div style="line-height:5px;">&nbsp;</div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:50px;">S.No</th>
                                <th class="text-center">So No</th>
                                <th class="text-center">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                FinanceHelper::companyDatabaseConnection($m);
                                $ExpenseVoucherData = DB::table('expense_voucher_data')->where('master_id','=',$id)->get();
                                FinanceHelper::reconnectMasterDatabase();
                                $Counter = 1;
                                $TotalAmount = 0;
                                foreach ($ExpenseVoucherData as $row2)
                                {
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $Counter++;?></td>
                                        <td class="text-center"><?php echo strtoupper($row2->so_no);?></td>
                                        <td class="text-center"><?php echo number_format($row2->amount,2); $TotalAmount+=$row2->amount;?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr class="text-center">
                                    <td colspan="2"><strong style="font-size: 20px">TOTAL</strong></td>
                                    <td><strong style="font-size: 18px"><?php echo $TotalAmount;?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="">Description</label>
                    <p><?php echo $row->description;?></p>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
}
?>