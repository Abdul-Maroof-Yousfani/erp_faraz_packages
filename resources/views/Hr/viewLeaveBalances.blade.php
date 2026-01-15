<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>
@extends('layouts.default')
@section('content')
    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">View Leaves Balances Report</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                            <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>
                        </div>
                    </div>
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
                                            <input type="button" class="btn btn-sm btn-primary" id="showAttendenceReport" onclick="viewLeavesBalances()" value="Search" style="margin-top: 32px;" />
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
        function viewLeavesBalances(){
            var company_id = $('#company_id').val();
            var leaves_policy_id = $("#leaves_policy_id").val();
            jqueryValidationCustom();
            if(validate == 0){
                $('#loader').html('<div class="loading"></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewLeavesBalances',
                    type: "GET",
                    data: {company_id:company_id,leaves_policy_id:leaves_policy_id},
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