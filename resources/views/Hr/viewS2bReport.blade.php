<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');

use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View S2B Report</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Regions:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="region_id" id="region_id">
                                                    <option value="">Select Region</option>
                                                    @foreach($employee_regions as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Category:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="emp_category_id" id="emp_category_id">
                                                    <option value="">Select Category</option>
                                                    @foreach($employee_category as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Employee Project</label>
                                                <select class="form-control" name="employee_project_id" id="employee_project_id">
                                                    <option value="0">Select Project</option>
                                                    @foreach($Employee_projects as $value)
                                                        <option value="{{$value->id}}">{{$value->project_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Month:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="month" name="payslip_month" id="payslip_month" value="" class="form-control requiredField" required />
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                                <input type="button" class="btn btn-sm btn-primary" id="viewS2bReport" onclick="viewS2bReport()" value="Search" style="margin-top: 32px;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="loader"></div>
                                <div class="S2BSection" id="PrintEmployeeAttendanceList"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(function () {

            $('select[name="employee_project_id"]').on('change', function() {
                var region_id = $('#region_id').val();
                var project_id = $(this).val();
                var m = '{{ Input::get('m') }}';
                if(region_id) {
                    $('.building_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getBuildingsList',
                        type: "GET",
                        data: { region_id:region_id,project_id:project_id,m:m},
                        success:function(data) {
                            $('.building_loader').html('');
                            $('select[name="building_id"]').empty();
                            $('select[name="building_id"]').html(data);
                        }
                    });
                }else{
                    alert('Select Region');
                    $('.building_loader').html('');
                    $('select[name="building_id"]').empty();
                }
            });
        });

        function viewS2bReport(){
            $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var payslip_month = $('#payslip_month').val();
            var region_id = $('#region_id').val();
            var emp_category_id = $('#emp_category_id').val();
            var employee_project_id = $('#employee_project_id').val();
            var building_id = $("#building_id").val();
            var m = '<?php echo Input::get('m'); ?>';
            jqueryValidationCustom();
            if(validate == 0){
                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewS2bReport',
                    type: "GET",
                    data: { emp_category_id:emp_category_id,region_id:region_id,payslip_month:payslip_month,m:m,
                        employee_project_id:employee_project_id, building_id:building_id},
                    success:function(data) {
                        $('#loader').html('');
                        $('.S2BSection').empty();
                        $('.S2BSection').append('<div class="">'+data+'</div>');
                    }
                });
            }else{
                return false;
            }
        }

        $(document).ready(function(){
            $('#sub_department_id').select2();
            $('#emr_no').select2();
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $('#employee_project_id').select2();
            $("#building_id").select2();
        });

    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

@endsection