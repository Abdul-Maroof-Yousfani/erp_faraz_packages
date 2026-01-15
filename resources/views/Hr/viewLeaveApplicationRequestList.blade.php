<?php
$m = Input::get('m');

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <style>
        hr{border-top: 1px solid cadetblue}
        td{ padding: 0px !important;}
        th{ padding: 0px !important;}

        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }
    </style>

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">View Leave Application Request List</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeaveApplicationRequestList','','1');?>
                                        <?php echo CommonHelper::displayExportButton('LeaveApplicationRequestList','','1')?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Regions:</label>
                                        <select class="form-control requiredField" name="region_id" id="region_id" onchange="filterEmployee()">
                                            <option value="">Select Region</option>
                                            @foreach($employee_regions as $key2 => $y2)
                                                <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Department:</label>
                                        <select class="form-control department_id" name="department_id" id="department_id" onchange="filterEmployee()">
                                            <option value="">Select Department</option>
                                            @foreach($employee_department as $key2 => $y2)
                                                <option value="{{ $y2->id}}">{{ $y2->department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Employee:</label>
                                        <select class="form-control requiredField emp_code" name="emp_code" id="emp_code" required></select>
                                        <div id="emp_loader"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>From Date</label>
                                        <input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>To Date</label>
                                        <input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>Leaves Status</label>
                                        <select class="form-control" id="LeavesStatus" name="LeavesStatus">
                                            <option value="1">Pending</option>
                                            <option value="2">Approved</option>
                                            <option value="3">Reject</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <label for="hr_Approval">Hr Approval</label><br>
                                        <input type="checkbox" id="hr_Approval" name="hr_Approval" checked disabled>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <label for="gm_Approval">GM Approval</label><br>
                                        <input type="checkbox" id="gm_Approval" name="gm_Approval">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <input type="button" value="Search Leave Applications" class="btn btn-sm btn-danger" onclick="viewRangeWiseLeaveApplicationsRequests();" style="margin-top: 25px;" />
                                    </div>
                                    <div id="leavesLoader"></div>
                                </div>
                                <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                                <div class="row">&nbsp;</div>
                                <div class="row">&nbsp;</div>
                                <div class="panel">
                                    <div class="panel-body" id="PrintLeaveApplicationRequestList">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive LeavesData">
                                                    <table class="table table-bordered sf-table-list table-hover table-striped" id="LeaveApplicationRequestList">
                                                        <thead>
                                                        <th class="text-center">S No.</th>
                                                        <th class="text-center">Emp Code</th>
                                                        <th class="text-center">Employee Name</th>
                                                        <th class="text-center">Leave Type</th>
                                                        <th class="text-center">Day Type</th>
                                                        <th class="text-center">Approval Status (HR)</th>
                                                        <th class="text-center">Approval Status (GM)</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center hidden-print">Action</th>

                                                        </thead>
                                                        <tbody>
                                                        @foreach($leave_application_request_list as $value)
                                                        <?php $counter = 1;?>
                                                            <tr>
                                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                                                <td class="text-center">{{ $value->emp_code  }}</td>
                                                                <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emp_code,'emp_code') }}</td>
                                                                <td class="text-center">{{ $leave_type_name = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$value->leave_type)}}</td>
                                                                <td class="text-center">{{ $leave_day_type[$value->leave_day_type] }}</td>
                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_m) }}</td>
                                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                                <td class="text-center hidden-print">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                            @if(in_array('view', $operation_rights))
                                                                                <li role="presentation">
                                                                                    <a class="delete-modal btn" onclick="LeaveApplicationRequestDetail('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emp_code?>','<?=$m?>')">
                                                                                        View
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                            @if(in_array('edit', $operation_rights))
                                                                                <li role="presentation">
                                                                                    <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_code;?>','Edit Leave Application Detail','<?=$m?>')">
                                                                                        Edit
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                            @if(in_array('delete', $operation_rights))
                                                                                @if($value->status == 1)
                                                                                    <li role="presentation">
                                                                                        <a class="delete-modal btn" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')">
                                                                                            Delete
                                                                                        </a>
                                                                                    </li>
                                                                                @endif
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="10">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                                        <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                        @endforeach
                                                        </tbody>
                                                    </table>
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
    </div>

    <script>

        function viewRangeWiseLeaveApplicationsRequests()
        {
            jqueryValidationCustom();
            if(validate == 0) {
                $('#leavesLoader').append('<div class="row">&nbsp;</div><div class="loader"></div>');
                var data = '';
                var gm_Approvals;
                var fromDate = $("#fromDate").val();
                var toDate = $("#toDate").val();
                var LeavesStatus = $("#LeavesStatus").val();
                var region_id = $("#region_id").val();
                var emp_category_id = $("#emp_category_id").val();
                var employee_project_id = $("#employee_project_id").val();
                var employee_id = $("#emp_code").val();
                var company_id = $("#company_id").val();
                var m = '<?= Input::get('m') ?>';
                var url = '<?php echo url('/')?>/hdc/viewRangeWiseLeaveApplicationsRequests';
                if($("#gm_Approval").prop("checked") == true){
                     gm_Approvals = 1;
                     data = {fromDate: fromDate, toDate: toDate, LeavesStatus: LeavesStatus,employee_id:employee_id,m:m,company_id:company_id,region_id:region_id,emp_category_id:emp_category_id,employee_project_id:employee_project_id,gm_Approvals:gm_Approvals}
                }
                else{
                    data = {fromDate: fromDate, toDate: toDate, LeavesStatus: LeavesStatus,employee_id:employee_id,m:m,company_id:company_id,region_id:region_id,emp_category_id:emp_category_id,employee_project_id:employee_project_id}
                }
                $.ajax({
                    url: url,
                    type: "GET",
                    data:data,
                    success: function (data) {
                        $('#leavesLoader').html("");
                        $('.LeavesData').html(data);


                    }
                })
            }

        }
        function LeaveApplicationRequestDetail(id,leave_day_type,leave_type_name,user_id,company_id)
        {
            $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
            var m = '<?= Input::get('m'); ?>';
            var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationRequestDetail';
            $.ajax({
                url: url,
                type: "GET",
                data: {id:id,leave_day_type:leave_day_type,leave_type_name:leave_type_name,user_id:user_id,m:company_id},
                success: function (data) {

                    jQuery('#showDetailModelTwoParamerter').modal('show', {backdrop: 'false'});
                    jQuery('#showDetailModelTwoParamerter .modalTitle').html('View Leave Application Detail');
                    jQuery('#showDetailModelTwoParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    jQuery('#showDetailModelTwoParamerter .modal-body').html(data);


                }
            })
        }

        function editLeaveApplicationRequestDetail(id,leave_day_type,leave_type_name,user_id,company_id)
        {
            // alert();
            $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
            var m = '<?= Input::get('m'); ?>';
            var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationRequestDetail';
            var data = {id:id,leave_day_type:leave_day_type,leave_type_name:leave_type_name,user_id:user_id,m:company_id};
            $.getJSON(url, data ,function(result){
                $.each(result, function(i, field){
                    $('#leave_area'+id).html('<hr>' +
                        '<div class="row text-center" style="background-color: gainsboro">' +
                        '<h4><b>Leave Application Details</b></h4>' +
                        '</div>' +
                        '<div class="row">&nbsp;</div>'+field);

                });
            })

        }

        function approveAndRejectLeaveApplication(recordId,approval_status,leave_day_type)
        {

            var check = (approval_status == 2) ? "Approve":"Reject";
            var url= '<?php echo url('/')?>/cdOne/approveAndRejectLeaveApplication';
            var companyId = '<?= Input::get('m'); ?>';

            if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
            {

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,approval_status:approval_status},
                    success:function(data) {
                        getPendingLeaveApplicationDetail('approval_status',leave_day_type);
                    }
                });
            }
        }

        function approveAndRejectLeaveApplication2(recordId,approval_status_m,leave_day_type)
        {

            var check = (approval_status_m == 2) ? "Approve":"Reject";
            var url= '<?php echo url('/')?>/cdOne/approveAndRejectLeaveApplication2';
            var companyId = '<?= Input::get('m'); ?>';

            if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
            {

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,approval_status_m:approval_status_m},
                    success:function(data) {
                        getPendingLeaveApplicationDetail('approval_status_m',leave_day_type);
                    }
                });
            }
        }

        function approveAndRejectLeaveApplication3(recordId,approval_status_hd,leave_day_type)
        {

            var check = (approval_status_hd == 2) ? "Approve":"Reject";
            var url= '<?php echo url('/')?>/cdOne/approveAndRejectLeaveApplication3';
            var companyId = '<?= Input::get('m'); ?>';

            if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
            {

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,approval_status_hd:approval_status_hd},
                    success:function(data) {
                        getPendingLeaveApplicationDetail('approval_status_hd',approval_status_hd,leave_day_type);
                    }
                });
            }
        }

        function RepostLeaveApplicationData(companyId,recordId)
        {
            if(confirm('Do you want to Repost Leave Applicaiton ?'))
            {
                repostMasterTableRecords(recordId,'leave_application');
            }

        }

        function getPendingLeaveApplicationDetail(type,leave_day_type) {
            var companyId = '<?= Input::get('m'); ?>';
            jQuery('#showDetailModelTwoParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '<?php echo url('/')?>/hdc/getPendingLeaveApplicationDetail',
                type: "GET",
                data: {m:companyId,type:type,leave_day_type:leave_day_type},
                success:function(data) {
                    if(data == 0)
                    {
                        location.reload();
                    }else{
                        jQuery('#showDetailModelTwoParamerter .modal-body').html(data);
                    }

                }
            });
        }

        function filterEmployee(){
            var region_id = $("#region_id").val();
            var department_id = $("#department_id").val();
            var m = "{{ Input::get('m') }}";
            var url = '{{ url('/') }}/slal/getEmployeeRegionList';
            var data;

            if(region_id != ''){
                data = {region_id:region_id,m:m};
            }
            if(department_id != '' && region_id != ''){
                data = {department_id:department_id,region_id:region_id,m:m};
            }

            if(region_id != ''){
                $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    type:'GET',
                    url:url,
                    data:data,
                    success:function(res){
                        $('#emp_loader').html('');
                        $('select[name="emp_code"]').empty();
                        $('select[name="emp_code"]').html(res);
                        $('select[name="emp_code"]').prepend("<option value='' selected>Select Employee</option>");
                    }
                });
            }
            else{
                $("#department_id").val('');
            }
        }


        $(document).ready(function(){
            $("#region_id").select2();
            $(".emp_code").select2();
            $("#department_id").select2();
            $("#company_id").select2();
        });

    </script>
    <script type="text/javascript" src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
@endsection                                      