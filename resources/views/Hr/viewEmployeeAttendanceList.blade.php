<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');

?>
@extends('layouts.default')
@section('content')
@include('select2')
    <style>
        td{ padding: 2px !important;}
        th{ padding: 2px !important;}
    </style>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">View Employee Attendance List</span>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
                                        <input type="hidden" name="employeeSection[]" value="1">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Regions:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="region_id" id="region_id" onchange="filterEmployee()">
                                                <option value="">Select Region</option>
                                                @foreach($employee_regions as $key2 => $y2)
                                                    <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Department:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="department_id" id="department_id" onchange="filterEmployee()">
                                                <option value="">Select Department</option>
                                                @foreach($employee_department as $key2 => $y2)
                                                    <option value="{{ $y2->id}}">{{ $y2->department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Employee:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField emp_code" name="emp_code" id="emp_code" ></select>
                                            <span id="emp_loader"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Month - Year:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="month" name="month_year" id="month_year" max="" class="form-control requiredField" />
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                            <input type="button" class="btn btn-sm btn-primary" id="showAttendenceReport" style="margin-top: 25px;" onclick="viewAttendanceReport()" value="View" />
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

        $(document).ready(function () {
            $(".btn-primary").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

            $('.emp_code').select2();
            $('#region_id').select2();
            $('#department_id').select2();
        });

        function filterEmployee(){
            var region_id = $("#region_id").val();
            var department_id = $("#department_id").val();
            var m = "{{ Input::get('m') }}";
            var url = '{{ url('/') }}/slal/getEmployeeRegionList';

            if(region_id != '' && department_id != ''){
                $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    type:'GET',
                    url:url,
                    data:{department_id:department_id,region_id:region_id,m:m},
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


        function viewAttendanceReport(){
            $('.employeeAttendenceReportSection').empty();
            var emp_code = $('.emp_code').val();
            var region_id = $('#region_id').val();
            var month_year = $('#month_year').val();
            var m = '<?php echo Input::get('m'); ?>';
            var rights_url = 'hr/viewEmployeeAttendanceList';
            if(emp_code != '') {
                jqueryValidationCustom();
                if (validate == 0) {
                    $('#loader').html('<div class="loading"></div>');
                    $.ajax({
                        url: '<?php echo url('/')?>/hdc/viewAttendanceReport',
                        type: "GET",
                        data: {
                            month_year: month_year, m: m, emp_code: emp_code, region_id: region_id,
                            rights_url: rights_url
                        },
                        success: function (data) {
                            $('#loader').html('');
                            $('.employeeAttendenceReportSection').empty();
                            $('.employeeAttendenceReportSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' + data + '</div>');
                        }
                    });
                }
            }else {
                swal('Attendance','Select Employee');
            }
        }

        function deleteAttendanceReport()
        {
            if(confirm("Do you want to delete attendance ?")){
                $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                var emp_code = $('#emp_code').val();
                var month_year = $('#month_year').val();
                var m = '<?php echo Input::get('m'); ?>';
                jqueryValidationCustom();
                if(validate == 0){
                    $.ajax({
                        url: '<?php echo url('/')?>/cdOne/deleteEmployeeAttendance',
                        type: "GET",
                        data: {month_year:month_year,m:m,emp_code:emp_code},
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

        }

	</script>

@endsection                                                                                               