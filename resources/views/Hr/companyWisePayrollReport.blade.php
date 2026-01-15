<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;

?>
@extends('layouts.default')
@section('content')

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">View Monthly Payroll Report</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            @if(in_array('print', $operation_rights))
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                            @endif
                            @if(in_array('export', $operation_rights))
                                <?php echo CommonHelper::displayExportButton('regionWisePayrollReport','','1')?>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label>Month - Year</label>
                                            <input type="month" name="month_year" id="month_year" class="form-control requiredField" />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 30px;" >
                                            <input type="button" class="btn btn-sm btn-primary" onclick="companyWisePayrollReport()" value="Search" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="loader"></div>
                        </div>
                        <div class="employeeAttendenceReportSection" id="PrintEmployeeAttendanceList"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function companyWisePayrollReport(){
            var month_year = $('#month_year').val();
            var m = '<?=Input::get('m')?>';
            jqueryValidationCustom();
            if(validate == 0){
                $('#loader').html('<div class="loading"></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/hdc/companyWisePayrollReport',
                    type: "GET",
                    data: {m:m,month_year:month_year},
                    success:function(data) {
                        $('#loader').html('');
                        $('.employeeAttendenceReportSection').empty();
                        $('.employeeAttendenceReportSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
                    }
                });
            }else{
                return false;
            }
        }

        function approvePayroll(company_name,company_id)
        {
            if(confirm('Do you want to Approve'+company_name+' Payroll ?'))
            {
                var month_year = $('#month_year').val().split('-');
                var year  = month_year[0];
                var month  = month_year[1];
                $.ajax({
                    url: '<?php echo url('/')?>/cdOne/approvePayroll',
                    type: "GET",
                    data: {company_id:company_id,year:year,month:month},
                    success:function(data) {
                        alert(data);
                    }
                });
            }

        }

    </script>
@endsection