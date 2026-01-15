<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
?>
@extends('layouts.default')
@section('content')
    <?php
    $currentDate = date('Y-m-d');
    ?>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Payslip Employees</span>
                    </div>
                </div>
                <div class="lineHeight"></div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="m" value="<?= Input::get('m') ?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Department:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="department_id" id="department_id">
                                            <option value="">Select Department</option>
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
                                        <input type="button" class="btn btn-sm btn-primary" onclick="manageEmployeePayslip()" value="Create Employee Payslip" style="margin-top: 32px;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="run_loader"></div>
                    <div class="employeePayslipSection"></div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>
</div>

	<script>
        function manageEmployeePayslip(){

            var department_id = $('#department_id').val();
            var employee = $('#employee_id').val();
            var payslip_month = $('#payslip_month').val();
            var m = '<?= Input::get('m'); ?>';
            jqueryValidationCustom();
            if(validate == 0){
                $('#run_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewEmployeePaysilpForm',
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