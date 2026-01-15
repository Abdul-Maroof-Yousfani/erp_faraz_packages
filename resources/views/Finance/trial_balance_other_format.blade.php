<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$m =Session::get('run_company');

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');


$data = DB::table('company')->select('accyearfrom','accyearto')->where('id',$m)->first();
$from = $data->accyearfrom;
$to = $data->accyearto;

?>

@extends('layouts.default')

@section('content')


    <div class="well_N">
    <div class="dp_sdw">    
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <span class="subHeadingLabelClass">Trial Balance Report</span>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div id="printBankPaymentVoucherList">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label for="">From Date</label>
                                        <input type="date" max="<?php echo $from?>" min="<?php echo $from?>" value="<?php echo $from?>" class="form-control" id="FromDate" name="FromDate">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label for="">To Date</label>
                                        <input type="date"   value="<?php echo $to?>" class="form-control" id="ToDate" name="ToDate">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label for="">Status</label>
                                        <select name="GetType" id="GetType" class="form-control">
                                            <option value="active">ACTIVE</option>
                                            <option value="all">ALL</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <button style="margin-top: 28px" class="btn btn-sm btn-primary" id="BtnGetData" onclick="GetTrialBalanceDataAjax()">Get Data</button>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span id="AjaxDataHere"></span>
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

    <script !src="">
        function GetTrialBalanceDataAjax()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var GetType = $('#GetType').val();

            var AccYearFrom = '<?php echo $from?>';
            var AccYearTo = '<?php echo $to?>';
            var m = '<?php echo $m?>';
            $('#AjaxDataHere').html('<div class="loader"></div>');
            $.ajax({
                url: '<?php echo url('/')?>/fdc/trial_balance_other_format',
                type: "GET",
                data: {FromDate:FromDate,ToDate:ToDate,AccYearFrom:AccYearFrom,AccYearTo:AccYearTo,m:m,GetType:GetType},
                success: function (data) {
                    $('#AjaxDataHere').html(data);
                }
            });
        }
    </script>
@endsection




