<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\CommonHelper;
$current_date = date('Y-m-d');
?>

@extends('layouts.default')
@section('content')
	@include('select2')
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<span class="subHeadingLabelClass">View Employees Payslip</span>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
							@if(in_array('print', $operation_rights))
								<?php echo CommonHelper::displayPrintButtonInBlade('PrintPayslipList','','1');?>
							@endif
							@if(in_array('export', $operation_rights))
								<?php echo CommonHelper::displayExportButton('PayslipList','','1')?>
							@endif
						</div>
					</div>
					<div class="row">
						<?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="m" value="<?= Input::get('m') ?>">
						<input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<label class="sf-label">Regions:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<select class="form-control requiredField region_id" name="region_id" id="region_id" onchange="filterEmployee()">
												<option value="">Select Region</option>
												@foreach($employee_regions as $key2 => $y2)
													<option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<label class="sf-label">Department:</label>
											<select class="form-control department_id" name="department_id" id="department_id" onchange="filterEmployee()">
												<option value="">Select Department</option>
												@foreach($employee_department as $key2 => $y2)
													<option value="{{ $y2->id}}">{{ $y2->department_name}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<label class="sf-label">Employee:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<select class="form-control requiredField emp_code" name="emp_code" id="emp_code" required></select>
											<div id="emp_loader"></div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<label class="sf-label">Payslip Month:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<input type="month" name="month_year" id="month_year" class="form-control requiredField" />
										</div>
										@if(in_array('view', $operation_rights))
											<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
												<input type="button" class="btn btn-sm btn-primary" onclick="viewEmployeePayrollList()" value="View" style="margin-top: 27px;"  />
											</div>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div id="run_loader"></div>
						<div class="employeePayslipSection" id="PrintPayslipList"></div>
						<?php echo Form::close();?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>

		$(document).ready(function() {
			// Wait for the DOM to be ready
			$(".btn-success").click(function(e){
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
			$('.region_id').select2();
			$('.department_id').select2();

		});

		function viewEmployeePayrollList(){
			var region_id = $('#region_id').val();
			var emp_code = $('.emp_code').val();
			var month_year = $('#month_year').val();
			var rights_url = 'hr/viewPayrollList';
			var m = '<?= Input::get('m'); ?>';
			jqueryValidationCustom();
			if(validate == 0){
				$('#run_loader').html('<div class="loading"></div>');

				$.ajax({
					url: '<?php echo url('/')?>/hdc/viewEmployeePayrollList',
					type: "GET",
					data: {region_id:region_id,emp_code:emp_code,month_year:month_year,m:m, rights_url:rights_url},
					success:function(data) {
						$('.employeePayslipSection').empty();
						$('.employeePayslipSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
						$('#run_loader').html('');
					}
				});
			}else{
				return false;
			}
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
						$('select[name="emp_code"]').prepend("<option value='All' selected>All</option>");
					}
				});
			}
			else{
				$("#department_id").val('');
			}
		}

		function deleteEmployeePayroll(emr_no,year,month) {
			var m = '<?= Input::get('m'); ?>';
			if (confirm("Do you want to delete Payroll ?") == true) {
				$.ajax({
					url: '<?php echo url('/')?>/cdOne/deleteEmployeePayroll',
					type: "GET",
					data: { emr_no:emr_no,year:year,month:month,m:m},
					success:function(data) {
						//$("#payrollrow"+emr_no).remove();
						$("#payrollrow"+emr_no).slideUp(500, function() {
							$(this).remove();
						})
					}
				});
			}
		}
	</script>

@endsection