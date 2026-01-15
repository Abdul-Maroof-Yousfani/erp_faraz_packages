<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$approved=ReuseableCode::check_rights(137);
$m = $_GET['m'];
$Checking = $_GET['id'];
$Checking = explode(',',$Checking);
if($Checking[0] == 'other')
{
    FinanceHelper::companyDatabaseConnection($m);
    $rvs = DB::table('new_rvs')->where('rv_no','=',$Checking[1])->first();
    FinanceHelper::reconnectMasterDatabase();
    $id = $rvs->id;
}
else{
    $id = $Checking[0];
}
$currentDate = date('Y-m-d');
FinanceHelper::companyDatabaseConnection($m);
$rvs = DB::table('new_rvs')->where('id','=',$id)->get();
FinanceHelper::reconnectMasterDatabase();
foreach ($rvs as $row) {
$username=$row->username;
$approved_user=$row->approved_user;
?>
@extends('layouts.default')
@section('content')
<div class="row">


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

        @if ($row->rv_status==1)
            <?php if($approved == true):?>
            <button onclick="approve_voucher('new_rvs','new_rv_data','rv_status','rv_date','rv_no','3','{{$row->rv_no}}')" type="button" class="btn btn-primary btn-xs">Approve</button>
            <?php  endif;?>
        @endif
        <button class="btn btn-xs btn-info" onclick="printView('viereceiptvoucher','','1')" style="">
            <span class="glyphicon glyphicon-print"> Print</span>
        </button>
        <?php // FinanceHelper::displayApproveDeleteRepostButton($m,$row->jv_status,$row->status,$row->jv_no,'jv_no','jv_status','status');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



        <div class="well" id="viereceiptvoucher">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-left">

                    {{--<label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo CommonHelper::changeDateFormat($currentDate);?></label>--}}
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <h3 style="text-align: center;">@if($row->rv_type==1)Bank Reciept Voucher @else Cash Reciept Voucher @endif</h3>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    {{--< ?php $nameOfDay = date('l', strtotime($currentDate)); ?>--}}
                    {{--<label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo '&nbsp;'.$nameOfDay;?></label>--}}

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:40%; float:left;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td style="width:40%;">RV No.</td>
                                        <td style="width:60%;"><?php echo strtoupper($row->rv_no);?></td>
                                    </tr>

                                    <tr>
                                        <td>RV Date</td>
                                        <td><?php echo FinanceHelper::changeDateFormat($row->rv_date);?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div style="width:40%; float:right;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td style="width:40%;">Cheque No</td>
                                        <td style="width:60%;"><?php  echo $row->cheque_no;?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%;">Cheque Date</td>
                                        <td style="width:60%;"><?php  echo FinanceHelper::changeDateFormat($row->cheque_date);?></td>
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
                                    $rvsDetail = DB::table('new_rv_data')->where('master_id','=',$id)->orderby('debit_credit','1')->get();
                                    $costing_data=$rvsDetail;
                                    $type = 5;
                                    FinanceHelper::reconnectMasterDatabase();
                                    $counter = 1;
                                    $g_t_debit = 0;
                                    $g_t_credit = 0;
                                    foreach ($rvsDetail as $row2) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $counter++;?></td>
                                        <td><?php  // echo FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);
                                            $acc_code=   CommonHelper::get_account_code($row2->acc_id);
                                            $acc_name = FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);
                                            echo $acc_code.'---'.$acc_name ;
                                        ?></td>

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
                                        <td class="text-center" colspan="2">
                                            <label for="field-1" class="sf-label"><b>Total</b></label>
                                        </td>
                                        <td class="text-right"><b><?php echo number_format($g_t_credit,2);?></b></td>
                                        <td class="text-right"><b><?php echo number_format($g_t_debit,2);?></b></td>
                                    </tr>
                                    </tbody>
                                </table>



                                <?php $breakup=DB::Connection('mysql2')->table('brige_table_sales_receipt as a')
                                        ->join('sales_tax_invoice as c','a.si_id','=','c.id')
                                        ->where('a.status',1)
                                        ->where('a.rv_id',$row->id)
                                        ->select('a.*','c.gi_no','c.so_type','c.description','c.so_no','c.sales_tax');



                                $count=1;

                                ?>

                                @if ($breakup->count()>0)
                                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px;">S.No</th>
                                            <th class="text-center">SI NO</th>
                                            <th class="text-center" style="width:150px;">SO NO</th>
                                            <th class="text-center" style="width:150px;">Invoice Amount</th>
                                            <th class="text-center" style="width:150px;">Receivable</th>
                                            <th class="text-center" style="width:150px;">Tax Amount</th>
                                            <th class="text-center" style="width:150px;">Discount Amount</th>
                                            <th class="text-center" style="width:150px;">Received Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $data =$breakup->get();
                                        $invoice_amount = 0;
                                        $received_amount=0;
                                        $tax_amount=0;
                                        $net_amount = 0;

                                        ?>
                                        @foreach($data as $row3)
                                            <?php $freight=SalesHelper::get_freight($row3->si_id); ?>
                                            <tr>
                                                <td>{{$count++}}</td>
                                                <td>{{$row3->gi_no}}</td>
                                                <td>@if($row3->so_type==0){{$row3->so_no}} @else {{$row3->description}} @endif</td>
                                                <td class="text-right">{{number_format(SalesHelper::get_total_amount_for_sales_tax_invoice_by_id($row3->si_id)->amount+$row3->sales_tax+$freight,2)}}</td>
                                                <td class="text-right">{{number_format($row3->received_amount,2)}}</td>
                                                <td class="text-right">{{number_format($row3->tax_amount,2)}}</td>
                                                <td class="text-right">{{number_format($row3->discount_amount,2)}}</td>
                                                <td class="text-right">{{number_format($row3->net_amount,2)}}</td>
                                                <?php

                                                $invoice_amount+=SalesHelper::get_total_amount_for_sales_tax_invoice_by_id($row3->si_id)->amount+$row3->sales_tax+$freight;
                                                $received_amount+=$row3->received_amount;
                                                $tax_amount+=$row3->tax_amount;
                                                $net_amount+=$row3->net_amount;
                                                ?>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3">Total</td>
                                            <td class="text-right">{{number_format($invoice_amount,2)}}</td>
                                            <td class="text-right">{{number_format($received_amount,2)}}</td>
                                            <td class="text-right">{{number_format($tax_amount,2)}}</td>
                                            <td></td>
                                            <td class="text-right">{{number_format($net_amount,2)}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                            Descriptions : {{$row->description}}
                        </div>

                        {{--@include('Finance.AjaxPages.view_costing_for_vouchers')--}}


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
        </div>
    </div>
</div>
<?php }?>
@endsection