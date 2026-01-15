<?php



use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$approved=ReuseableCode::check_rights(217);
$Checking = $_GET['id'];
$m = $_GET['m'];

$Checking = explode(',',$Checking);
if($Checking[0] == 'other')
{
    FinanceHelper::companyDatabaseConnection($m);
    $jvs = DB::table('new_pv')->where('pv_no','=',$Checking[1])->first();
    FinanceHelper::reconnectMasterDatabase();
    $id = $jvs->id;
}
else{
    $id = $Checking;
}
if(isset($_GET['type'])){
    $Type = $_GET['type'];
} else{
    $Type="";
}

$currentDate = date('Y-m-d');
FinanceHelper::companyDatabaseConnection($m);
$pvs = DB::table('new_pv')->where('id','=',$id)->get();
FinanceHelper::reconnectMasterDatabase();
foreach ($pvs as $row) {
$username=$row->username;
$approved_user=$row->approved_user;
//print_r(session()->all());
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        @if($Type !='audit_trial')
            @if ($row->pv_status==1)
                <?php if($approved == true):?>
                <button onclick="approve_voucher('new_pv','new_pv_data','pv_status','pv_date','pv_no','2','{{$row->pv_no}}')" type="button" class="btn btn-primary btn-xs">Approve</button>
                <?php endif;?>
            @endif
        @endif
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
                    <h3 style="text-align: center;">@if ($row->payment_type==1) Bank Payment Voucher @else Cash Payment Voucher @endif</h3>
                </div>

            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:40%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:40%;">     @if($row->payment_type==1)BPV No. @else CPV No. @endif</td>
                                <td style="width:60%;"><?php echo strtoupper($row->pv_no);?></td>
                            </tr>
                            <tr>
                                <td style="width:40%;">Ref / Bill No.</td>
                                <td style="width:60%;"><?php echo $row->bill_no;?></td>
                            </tr>
                            <tr>
                                <td>@if($row->payment_type==1)BPV Date. @else CPV Date. @endif</td>
                                <td><?php echo FinanceHelper::changeDateFormat($row->pv_date);?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div style="width:40%; float:right;">
                        @if($row->payment_type==1)
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <tbody>
                                <tr>
                                    <td style="width:40%;">Cheque No.</td>
                                    <td style="width:60%;"><?php echo $row->cheque_no;?></td>
                                </tr>
                                <tr>
                                    <td>Cheque Date</td>
                                    <td><?php  echo  FinanceHelper::changeDateFormat($row->cheque_date); ;?></td>
                                </tr>
                                </tbody>
                            </table>
                        @endif


                    </div>
                </div>

                <div style="line-height:5px;">&nbsp;</div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:50px;">S.No</th>
                                <th class="text-center">Account</th>
                                <th class="text-center">Description</th>
                                <th class="text-center" style="width:150px;">Debit</th>
                                <th class="text-center" style="width:150px;">Credit</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            FinanceHelper::companyDatabaseConnection($m);
                            $pvsDetail = DB::table('new_pv_data')->where('master_id','=',$id)->orderby('debit_credit','1')->get();
                            $costing_data=$pvsDetail;
                            $type = 3;
                            FinanceHelper::reconnectMasterDatabase();
                            $counter = 1;
                            $g_t_debit = 0;
                            $g_t_credit = 0;
                            foreach ($pvsDetail as $row2)
                            {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td><?php  echo CommonHelper::get_account_code($row2->acc_id).'---'.FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);?></td>

                                <td><?php  echo $row2->description; ?></td>

                                {{--<td> < ?php echo CommonHelper::get_item_name($row2->sub_item);?></td>--}}

                                {{--<td class="text-center">< ?php if ($row2->qty!=''): echo $row2->qty;endif; ?></td>--}}
                                {{--<td class="text-center">< ?php echo number_format($row2->rate,2); ?></td>--}}

                                <td   class="debit_amount text-right">
                                    <?php
                                    if($row2->debit_credit == 1){
                                    $g_t_credit += $row2->amount;?>


                                    <a onclick="amount({{$row2->amount}})" style="cursor: pointer"> <?php echo  number_format($row2->amount,2); ?></a>
                                    <?php
                                    }else{}
                                    ?>
                                </td>
                                <td class="credit_amount text-right">
                                    <?php
                                    if($row2->debit_credit == 0){
                                    $g_t_debit += $row2->amount; ?>
                                    <a onclick="amount({{$row2->amount}})" style="cursor: pointer">  <?php echo     number_format($row2->amount,2);?></a>
                                    <?php
                                    }else{}
                                    ?>
                                </td>

                            </tr>
                            <?php
                            }
                            ?>
                            <tr class="sf-table-total">
                                <td colspan="3">
                                    <label for="field-1" class="sf-label"><b>Total</b></label>
                                </td>
                                <td id="" class="text-right"><b><?php echo number_format($g_t_credit,2);?></b></td>
                                <td class="text-right"><b><?php echo number_format($g_t_debit,2);?></b></td>
                            </tr>
                            <input id="d_t_amount_1" type="hidden" value="<?php echo $g_t_debit ?>"/>
                            <tr>

                                <td colspan="5" style="font-size: 15px;" id="rupees"><script>toWords(1);</script></td>
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
                            <h6 id="amount_in_words">Description: <?php echo strtoupper($row->description); ?></h6>
                        </div>
                    </div>
                    <style>
                        .signature_bor {
                            border-top:solid 1px #CCC;
                            padding-top:7px;
                        }
                    </style>
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
                                    <b>  <p><?php echo strtoupper($approved_user)?></p></b>
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
    <!--
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Bank Payment Voucher Detail'))!!} ">
                </div>
                  <!-->
</div>
</div>
</div>
</div>
<?php
}
?>
<script>

    var th = ['','thousand','million', 'billion','trillion'];
    var dg = ['zero','one','two','three','four', 'five','six','seven','eight','nine'];
    var tn = ['ten','eleven','twelve','thirteen', 'fourteen','fifteen','sixteen', 'seventeen','eighteen','nineteen'];
    var tw = ['twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

    function amount(s) {


        s = s.toString();
        s = s.replace(/[\, ]/g,'');
        if (s != parseFloat(s)) return 'not a number';
        var x = s.indexOf('.');
        if (x == -1)
            x = s.length;
        if (x > 15)
            return 'too big';
        var n = s.split('');
        var str = '';
        var sk = 0;
        for (var i=0;   i < x;  i++) {
            if ((x-i)%3==2) {
                if (n[i] == '1') {
                    str += tn[Number(n[i+1])] + ' ';
                    i++;
                    sk=1;
                } else if (n[i]!=0) {
                    str += tw[n[i]-2] + ' ';
                    sk=1;
                }
            } else if (n[i]!=0) { // 0235
                str += dg[n[i]] +' ';
                if ((x-i)%3==0) str += 'hundred ';
                sk=1;
            }
            if ((x-i)%3==1) {
                if (sk)
                    str += th[(x-i-1)/3] + ' ';
                sk=0;
            }
        }

        if (x != s.length) {
            var y = s.length;
            str += 'point ';
            for (var i=x+1; i<y; i++)
                str += dg[n[i]] +' ';
        }
        var v=str.replace(/\s+/g,' ');
        $('#rupees').html('Amount In Words: '+v);
    }
</script>