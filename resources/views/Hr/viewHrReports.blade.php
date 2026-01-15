<?php
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>

<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
    td{ padding: 0px !important;}
    th{ padding: 0px !important;}
    .reports li a {
        font-size: 18px;
    }
    .reports li {
        border-bottom: 1px solid darkgrey;
        padding: 6px 0;
    }
    .reports li i {
        margin-right: 8px;
        color: #2B3245;
    }
</style>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')

@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="m" id="m" value="<?php echo Input::get('m'); ?>">
                    @if(in_array('print', $operation_rights))
                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintHrReport','','1');?>
                    @endif
                    @if(in_array('export', $operation_rights))
                        <?php echo CommonHelper::displayExportButton('HrReport','','1')?>
                    @endif
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">View HR Reports</span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <ul class="list-unstyled reports">
                                    <li><i class="fa fa-clock-o"></i> <a id="viewTurnoverReportForm" href="#">View Turnover Report</a></li>
                                    <li><i class="fa fa-info-circle"></i> <a id="viewOnboardReportForm" href="#">View Onboard Report</a></li>
                                    <li><i class="fa fa-line-chart"></i> <a id="viewIncrementReportForm"href="#">View Increment Report</a></li>
                                    <!-- <li><i class="fa fa-folder-open"></i> <a href="#" id="viewWarningReportForm">View Warning Report</a></li>-->
                                    <li><i class="fa fa-table"></i><a href="#" id="viewEmployeeReportForm">View Employee Report</a></li>
                                    <!--<li><i class="fa fa-folder-open"></i><a href="#" id="viewTransferReportForm">View Transfer Report</a></li>-->
                                    <li><i class="fa fa-medkit"></i><a href="#" id="viewMedicalReportForm">View Medical Report</a></li>
                                    <!--<li><i class="fa fa-trophy"></i><a href="#" id="viewTrainingReportForm">View Training Report</a></li>
                                    <li><i class="fa fa-table"></i><a href="#" id="viewGratuityReportForm">View Gratuity Report</a></li>
                                    <li><i class="fa fa-table"></i><a href="#" id="viewEmployeeExpReportForm">View Emp Exp & Edu Report</a></li>-->
                                </ul>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" id="report-form-area"></div>
                        </div>

                    </div>
                    <br>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <input type="hidden" name="HrReports[]">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id=""></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="report-area"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>

        $('#viewTurnoverReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewTurnoverReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewOnboardReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewOnboardReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewEmployeeReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewEmployeeReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewTransferReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewTransferReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewWarningReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewWarningReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewIncrementReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewIncrementReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewMedicalReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewMedicalReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewTrainingReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewTrainingReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewGratuityReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewGratuityReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });

        $('#viewEmployeeExpReportForm').click(function() {
            $('#report-area').html('');
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            $.ajax({
                url: "/HrReports/viewEmployeeExpReportForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-form-area').html(response);

                }
            });
        });


    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

@endsection