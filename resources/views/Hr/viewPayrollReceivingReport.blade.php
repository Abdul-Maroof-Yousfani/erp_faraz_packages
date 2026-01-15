<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>
@extends('layouts.default')
@section('content')
    <div class="">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="lineHeight">&nbsp;</div>
                <div class="">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Payroll Receiving Report</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                                <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>
                            </div>
                        </div>
                    </div>

                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Companies:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="company_id" id="company_id">
                                                <option value="All">All Companies</option>
                                                @foreach($companies as $companyData)
                                                    <option value="{{ $companyData->id}}">{{ $companyData->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label>Month-Year</label>
                                            <input type="month" name="month_year" id="month_year" max="" class="form-control requiredField" />

                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <input type="button" class="btn btn-sm btn-primary" id="showAttendenceReport" onclick="viewPayrollReceivingReport()" value="Search" style="margin-top: 32px;" />
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
        function viewPayrollReceivingReport(){
            var month_year = $('#month_year').val();
            var company_id = $('#company_id').val();
            jqueryValidationCustom();
            if(validate == 0){
                $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewPayrollReceivingReport',
                    type: "GET",
                    data: {company_id:company_id,month_year:month_year},
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



    </script>
@endsection