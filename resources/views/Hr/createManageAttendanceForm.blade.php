<?php
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>

<style>
	input[type="radio"], input[type="checkbox"]{ width:30px;
		height:20px;
	}
	.a {
		font-size: 18px;
	}
	.i {
		margin-right: 8px;
		color: #2B3245;
	}

</style>
@extends('layouts.default')
@section('content')
	@include('select2')
	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					@if(Session::has('errorMsg'))
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;
								<div class="alert-danger" style="font-size: 18px"><span class="glyphicon glyphicon-warning-sign"></span><em> {!! session('errorMsg') !!}</em></div>
							</div>
						</div>
						<br>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="well">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<span class="subHeadingLabelClass">Manage Employees Attendance</span>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
								<i class="i fa fa-list"></i><a class="a" id="viewDayWiseAttendence" href="#">Add Day Wise Attendance</a>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
								<i class="i fa fa-list"></i><a class="a" id="viewMonthWiseAttendence" href="#">Add Month Wise Attendance</a>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
								<i class="i fa fa-list"></i><a class="a" id="viewUploadFileAttendance" href="#">Upload Attendance File</a>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="row">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="m" value="<?php echo Input::get('m'); ?>">
                            <input type="hidden" name="employeeSection[]" value="">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div id="attendance-area"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>

		$('#viewDayWiseAttendence').click(function() {
			$('#attendance-area').html('<div class="loading"></div>');
			var m = '<?php echo Input::get('m'); ?>';
			$.ajax({
				url: "{{ url('/') }}/hdc/viewDayWiseAttendence",
				type: 'GET',
				data: {m : m},
				success: function (response){
					$('#attendance-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
					$('#attendance-area').html(response);

				}
			});
		});

		$('#viewMonthWiseAttendence').click(function() {
			$('#attendance-area').html('<div class="loading"></div>');
			var m = '<?php echo Input::get('m'); ?>';
			$.ajax({
				url: "{{ url('/') }}/hdc/viewMonthWiseAttendence",
				type: 'GET',
				data: {m : m},
				success: function (response){
					$('#attendance-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
					$('#attendance-area').html(response);

				}
			});
		});

		$('#viewUploadFileAttendance').click(function() {
			$('#attendance-area').html('<div class="loading"></div>');
			var m = '<?php echo Input::get('m'); ?>';
			$.ajax({
				url: "{{ url('/') }}/hdc/viewUploadFileAttendance",
				type: 'GET',
				data: {m : m},
				success: function (response){
					$('#attendance-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
					$('#attendance-area').html(response);

				}
			});
		});




		function addManualyAttendance(){

        	var attendance_type = $('#attendance_type').val();

        	if(attendance_type == 1)
			{
				var month_year = $('#month_year').val();
				var emp_code = $('.emp_code').val();
				var present_days = $('#present_days').val();
				var absent_days = $('#absent_days').val();
				var no_of_leaves = $('#no_of_leaves').val();
				var m = '<?php echo $m;?>';

				$('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

				if(month_year != '' &&  emp_code != '' && present_days && absent_days && no_of_leaves)
				{
					$.ajax({
						url: '<?php echo url('/')?>/hadbac/addManualyAttendance',
						type: "GET",
						data: {emp_code: emp_code, month_year:month_year , present_days: present_days,
							absent_days:absent_days, no_of_leaves:no_of_leaves, attendance_type:attendance_type, m: m},
						success:function(data) {
							$('#loader').html('');
							swalAdd();
						}
					});
				}
				else
				{
					$('#loader').html('');
					return false;
				}

			}
        	else if(attendance_type == 2)
			{
				var attendance_status = $("input[name='attendance_status']:checked"). val();
				var emp_code = $('.emp_code').val();
				var clock_in = $('#clock_in').val();
				var clock_out = $('#clock_out').val();
				var attendance_date = $('#attendance_date').val();
				var m = '{{ $m }}';

				if(emp_code != '') {
					if (attendance_date != '') {
						if (attendance_status != null) {
							$('#loader').html('<div class="loading"></div>');

							$.ajax({
								url: '<?php echo url('/')?>/hadbac/addManualyAttendance',
								type: "GET",
								data: {
									emp_code: emp_code,
									attendance_date: attendance_date,
									clock_in: clock_in,
									clock_out: clock_out,
									attendance_status: attendance_status,
									attendance_type: attendance_type,
									m: m
								},
								success: function (data) {
									$('#loader').html('');
									swalAdd();
								}
							});
						}
						else {
							swalAlert('Attendance','Select Attendance Status');
						}
					}
					else{
						swalAlert('Attendance','Select Attendance Date')
					}
				}
				else{
					swalAlert('Attendance','Select Employee')
				}
			}
        }
		
	</script>

@endsection