<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$currentDate = date('Y-m-d');
?>
@extends('layouts.default')

@section('content')
<div class="panel-body">
		<div class="row">
			<div class="lineHeight">&nbsp;</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<span class="subHeadingLabelClass">View Payslip Employees</span>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintPayslipList','','1');?>
                                <?php echo CommonHelper::displayExportButton('PayslipList','','1')?>
							</div>
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<label>From Date</label>
							<input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
							<input type="text" readonly class="form-control text-center" value="Between" /></div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<label>To Date</label>
							<input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
							<input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="row">
						<?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<label class="sf-label">Department:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<select class="form-control requiredField" name="department_id" id="department_id">
												<option value="">Select</option>
												@foreach($departments as $key => $y)
													<option value="{{ $y->id }}">{{ $y->department_name }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<label class="sf-label">Employee:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<select class="form-control requiredField" name="employee_id" id="employee_id" required>
											</select>
											<div id="emp_loader"></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<label class="sf-label">Payslip Month:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<input type="month" name="payslip_month" id="payslip_month" value="" class="form-control requiredField" required />
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<input type="button" class="btn btn-sm btn-primary" onclick="viewEmployeePayslip()" value="View Employee Payslip" style="margin-top: 32px;" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="run_loader"></div>
						<div class="employeePayslipSection" id="PrintPayslipList">

						</div>
					<?php echo Form::close();?>
					</div>
				</div>
			</div>
		</div>
	</div>

<script>
	function viewEmployeePayslip(){
		var department_id = $('#department_id').val();
		var employee = $('#employee_id').val();
		var payslip_month = $('#payslip_month').val();
        var m = '<?= Input::get('m'); ?>';
		jqueryValidationCustom();
		if(validate == 0){
            $('#run_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
				url: '<?php echo url('/')?>/hdc/viewEmployeePaysilpList',
				type: "GET",
				data: { department_id:department_id,employee:employee,payslip_month:payslip_month,m:m},
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
	$(function(){
		$('select[name="department_id"]').on('change', function() {
            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var department_id = $(this).val();
            var m = '<?= Input::get('m'); ?>';
			if(department_id) {
				$.ajax({
					url: '<?php echo url('/')?>/slal/MachineEmployeeListDeptWise',
					type: "GET",
					data: { department_id:department_id,m:m},
					success:function(data) {
                        $('#emp_loader').html('');
						$('select[name="employee_id"]').empty();
						$('select[name="employee_id"]').html(data);
                    }
                });
            }else{
                $('select[name="employee_id"]').empty();
            }
        });
	});
</script>
@endsection