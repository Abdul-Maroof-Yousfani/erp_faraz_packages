<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
FinanceHelper::companyDatabaseConnection($m);
$pvs = DB::table('pvs')->where('id','=',$id)->get();
FinanceHelper::reconnectMasterDatabase();

foreach ($pvs as $row) {
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        @if ($row->verified_by==null && $row->pv_status==1 && auth()->user()->acc_type == 'client')    
            <button onclick="verify_voucher('new_pv','new_pv_data','pv_status','pv_date','pv_no','2','{{$row->pv_no}}')" type="button" class="btn btn-primary btn-xs">Verify</button>
        @endif
        @if ($row->verified_by!=null && $row->pv_status==1)

            <button onclick="approve_voucher('new_pv','new_pv_data','pv_status','pv_date','pv_no','2','{{$row->pv_no}}', 'Cash Payment Voucher')" type="button" class="btn btn-primary btn-xs">Approve</button>
        @endif
        <?php // FinanceHelper::displayApproveDeleteRepostButton($m,$row->pv_status,$row->status,$row->pv_no,'pv_no','pv_status','status');?>
        <?php echo CommonHelper::displayPrintButtonInView('printCashPaymentVoucherDetail','','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well" id="printCashPaymentVoucherDetail">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo FinanceHelper::changeDateFormat($currentDate);?></label>
                            <label>
                                <input onclick="show_costing()" type="checkbox" id="costing">Show Costing
                            </label>
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
                                    <?php FinanceHelper::checkVoucherStatus($row->pv_status,$row->status);?>
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
                                        <td style="width:40%;">CPV No.</td>
                                        <td style="width:60%;"><?php echo strtoupper($row->pv_no);?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%;">Ref / Bill No.</td>
                                        <td style="width:60%;"><?php echo $row->slip_no;?></td>
                                    </tr>
                                    <tr>
                                        <td>CPV Date</td>
                                        <td><?php echo FinanceHelper::changeDateFormat($row->pv_date);

                                            $username=$row->username;

                                            ?></td>
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
                                        <th class="text-center hide">Item Name</th>

                                        <th  class="text-center hide" style="width:100px;">Qty.</th>
                                        <th class="text-center hide" style="width:100px;">Rate.</th>
                                        <th class="text-center" style="width:150px;">Debit</th>
                                        <th class="text-center" style="width:150px;">Credit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    FinanceHelper::companyDatabaseConnection($m);
                                    $pvsDetail = DB::table('pv_data')->where('master_id','=',$id)->orderby('debit_credit','1')->get();
                                    $costing_data=$pvsDetail;
                                    $type = 4;
                                    FinanceHelper::reconnectMasterDatabase();
                                    $counter = 1;
                                    $g_t_debit = 0;
                                    $g_t_credit = 0;
                                    foreach ($pvsDetail as $row2) {
                                    $acc_code=   CommonHelper::get_account_code($row2->acc_id);
                                    $acc_name=    FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $counter++;?></td>
                                        <td><?php   echo $acc_code.'---'.$acc_name?></td>

                                        <td class="hide"> <?php echo CommonHelper::get_item_name($row2->sub_item);?></td>

                                        <td class="text-center hide"><?php if ($row2->qty!=''): echo $row2->qty;endif; ?></td>
                                        <td class="text-center hide"><?php echo number_format($row2->rate,2); ?></td>
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

                        @include('Finance.AjaxPages.view_costing_for_vouchers')

                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                                        <div class="container-fluid">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                                    <h6 class="signature_bor">Prepared By: </h6>
                                                    <b>   <p><?php echo strtoupper($username);  ?></p></b>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                                    <h6 class="signature_bor">Verified By:</h6>
                                                    <b>   <p><?php  ?></p></b>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                                    <h6 class="signature_bor">Approved By:</h6>
                                                    <b>  <p></p></b>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                                    <h6 class="signature_bor">Recieved By</h6>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Cash Payment Voucher Detail'))!!} ">
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    }
?>
