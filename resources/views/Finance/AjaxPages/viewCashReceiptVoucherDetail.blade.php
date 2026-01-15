<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
FinanceHelper::companyDatabaseConnection($m);
$rvs = DB::table('rvs')->where('id','=',$id)->get();
FinanceHelper::reconnectMasterDatabase();
foreach ($rvs as $row) {
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo FinanceHelper::displayApproveDeleteRepostButton($m,$row->rv_status,$row->status,$row->rv_no,'rv_no','rv_status','status');?>
        <?php echo CommonHelper::displayPrintButtonInView('printCashReceiptVoucherDetail','','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well" id="printCashReceiptVoucherDetail">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo FinanceHelper::changeDateFormat($currentDate);?></label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                     style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                                    <?php echo FinanceHelper::getCompanyName($m);?>
                                </div>
                                <br />
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                     style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                                    <?php FinanceHelper::checkVoucherStatus($row->rv_status,$row->status);?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                            <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
                            <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                        </div>
                    </div>
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:30%; float:left;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td style="width:40%;">RV No.</td>
                                        <td style="width:60%;"><?php echo strtoupper($row->rv_no);?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%;">Ref / Bill No.</td>
                                        <td style="width:60%;"><?php echo $row->slip_no;?></td>
                                    </tr>
                                    <tr>
                                        <td>RV Date</td>
                                        <td><?php echo FinanceHelper::changeDateFormat($row->rv_date);?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width:50px;">S.No</th>
                                        <th class="text-center">Account</th>
                                        <th class="text-center" style="width:150px;">Debit</th>
                                        <th class="text-center" style="width:150px;">Credit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    FinanceHelper::companyDatabaseConnection($m);
                                    $rvsDetail = DB::table('rv_data')->where('master_id','=',$id)->orderby('debit_credit','1')->get();
                                    FinanceHelper::reconnectMasterDatabase();
                                    $counter = 1;
                                    $g_t_debit = 0;
                                    $g_t_credit = 0;
                                    foreach ($rvsDetail as $row2) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $counter++;?></td>
                                        <td><?php  echo FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);?></td>
                                        <td class="debit_amount text-right">
                                            <?php
                                            if($row2->debit_credit == 1){
                                                $g_t_credit += $row2->amount;
                                                echo number_format($row2->amount,0);
                                            }else{}
                                            ?>
                                        </td>
                                        <td class="credit_amount text-right">
                                            <?php
                                            if($row2->debit_credit == 0){
                                                $g_t_debit += $row2->amount;
                                                echo number_format($row2->amount,0);
                                            }else{}
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr class="sf-table-total">
                                        <td colspan="2">
                                            <label for="field-1" class="sf-label"><b>Total</b></label>
                                        </td>
                                        <td class="text-right"><b><?php echo number_format($g_t_credit,0);?></b></td>
                                        <td class="text-right"><b><?php echo number_format($g_t_debit,0);?></b></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th colspan="5"><?php echo $row->description;?></th>
                                            </tr>
                                            <tr>
                                                <th style="width:15%;">Printed On</th>
                                                <th style="width:15%;"><?php echo Auth::user()->name; ?></th>
                                                <th style="width:15%;">Created By</th>
                                                <th style="width:15%;"><?php echo $row->username;?></th>
                                                <th style="width:20%;">Received By</th>
                                                <th style="width:20%;"></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Cash Receipt Voucher Detail'))!!} ">
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
