<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$approved=ReuseableCode::check_rights(156);
$m = $_GET['m'];
$id = $_GET['id'];
$currentDate = date('Y-m-d');
FinanceHelper::companyDatabaseConnection($m);
$jvs = DB::table('new_jvs')->where('id','=',$id)->get();
FinanceHelper::reconnectMasterDatabase();
        ?>
@extends('layouts.default')

@section('content')
    <?php
foreach ($jvs as $row) {
$username=$row->username;
$approved_user=$row->approved_user;
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

            @if ($row->jv_status==1)
                <?php if($approved == true):?>
                <button onclick="approve_voucher('new_jvs','new_jv_data','jv_status','jv_date','jv_no','1','{{$row->jv_no}}')" type="button" class="btn btn-primary btn-xs">Approve</button>
                <?php endif;?>
            @endif

        <?php // FinanceHelper::displayApproveDeleteRepostButton($m,$row->jv_status,$row->status,$row->jv_no,'jv_no','jv_status','status');?>

    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="printCashPaymentVoucherDetail" class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                            <h3 style="text-align: center;">Journal Voucher</h3>
                        </div>
                    </div>
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:30%; float:left;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td style="width:40%;">JV No.</td>
                                        <td style="width:60%;"><?php echo strtoupper($row->jv_no);?></td>
                                    </tr>
                                    {{--<tr>--}}
                                    {{--<td style="width:40%;">Ref / Bill No.</td>--}}
                                    {{--<td style="width:60%;">< ?php echo $row->slip_no;?></td>--}}
                                    {{--</tr>--}}
                                    <tr>
                                        <td>JV Date</td>
                                        <td><?php echo FinanceHelper::changeDateFormat($row->jv_date);?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{--<div style="width:30%; float:right;">--}}
                            {{--<table  class="table table-bordered table-striped table-condensed tableMargin">--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                            {{--<td style="width:40%;">Bill Date</td>--}}
                            {{--<td style="width:60%;">< ?php  echo FinanceHelper::changeDateFormat($row->bill_date);?></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td style="width:40%;">Due Date</td>--}}
                            {{--<td style="width:60%;">< ?php  echo FinanceHelper::changeDateFormat($row->due_date);?></td>--}}
                            {{--</tr>--}}

                            {{--</tbody>--}}
                            {{--</table>--}}
                            {{--</div>--}}
                        </div>
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
                                    $jvsDetail = DB::table('new_jv_data')->where('master_id','=',$id)->orderby('debit_credit','1')->get();
                                    $costing_data=$jvsDetail;
                                    $type = 5;
                                    FinanceHelper::reconnectMasterDatabase();
                                    $counter = 1;
                                    $g_t_debit = 0;
                                    $g_t_credit = 0;
                                    foreach ($jvsDetail as $row2) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $counter++;?></td>
                                        <td><?php
                                            $acc_code=   CommonHelper::get_account_code($row2->acc_id);
                                            echo $acc_code.'---'.FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);?></td>

                                        <td><?php  echo $row2->description;?></td>
                                        <td class="debit_amount text-right">

                                            <?php
                                            if($row2->debit_credit == 1)
                                            {
                                                $g_t_credit += $row2->amount;
                                                echo number_format($row2->amount,2);
                                            }
                                            ?>
                                        </td>
                                        <td class="credit_amount text-right">
                                            <?php
                                            if($row2->debit_credit == 0)
                                            {
                                                $g_t_debit += $row2->amount;
                                                echo number_format($row2->amount,2);
                                            }
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
                                            <b><p><?php echo strtoupper($approved_user)?></p></b>
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
</div>
<?php }?>


<script !src="">
    $(document).ready(function(){
//        printView('printCashPaymentVoucherDetail','','1');
//        function printView(param1,param2,param3) {


        $( ".qrCodeDiv" ).removeClass( "hidden" );
//            if(param2!="")
//            {
//                $('.'+param2).prop('href','');
//            }
        $('.printHide').css('display','none');
        var printContents = document.getElementById('printCashPaymentVoucherDetail').innerHTML;
//        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
//        document.body.innerHTML = originalContents;
        //if(param3 == 0){
//        window.onafterprint = function(){
//            window.close()
//        }

        //}
//        }
    });
</script>
@endsection
