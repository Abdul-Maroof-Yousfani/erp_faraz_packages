<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Payroll</span>
                        </div>
                    </div>
                    <div class="lineHeight"></div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'had/addPayrollDetail'));?>
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
                                            <label>Month - Year</label>
                                            <input type="month" name="month_year" id="month_year" max="{{ $current_date }}" class="form-control requiredField" />
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                            <input type="button" class="btn btn-sm btn-primary" id="showAttendenceReport" onclick="viewEmployeePayrollForm()" value="Calculate" style="margin-top: 30px;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="employeePayslipSection"></div>
                        </div>
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


        function viewEmployeePayrollForm(){
            var emp_code = $('.emp_code').val();
            var region_id = $('#region_id').val();
            var department_id = $('#department_id').val();
            var month_year = $('#month_year').val();
            var m = '{{ Input::get('m') }}';
            jqueryValidationCustom();
            if(validate == 0){
                $('#employeePayslipSection').html('<div class="loading"></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewEmployeePayrollForm',
                    type: "GET",
                    data: { emp_code:emp_code,region_id:region_id,department_id:department_id,month_year:month_year,m:m},
                    success:function(data) {
                        $('#employeePayslipSection').html('');
                        $('#employeePayslipSection').append('<div class="">'+data+'</div>');
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


        function payrollCalculation(id,netSalary,loan_per_month,advanceSalary)
        {
            var loan_amount = (parseInt(loan_per_month) - $("#loan_amount_"+id).val());
            var advance_salary = (parseInt(advanceSalary) - $("#advance_salary_amount_"+id).val());
            var other_amount = parseInt($("#other_amount_"+id).val());
            var hidden_allowance = parseInt($("#hidden_allowance_"+id).val());
            var allowance = parseInt($("#total_allowance_"+id).val() - hidden_allowance);
            var hidden_deduction = parseInt($("#hidden_deduction_"+id).val());
            var loan = parseInt($("#loan_amount_"+id).val());
            var advance_salary_amount = parseInt($("#advance_salary_amount_"+id).val());
            var net_salary = parseInt(netSalary);

            if( isNaN(advance_salary_amount) ){
                advance_salary_amount = 0;
            }
            if( isNaN(loan) ){
                loan = 0;
            }
            if( isNaN(other_amount) ){
                other_amount = 0;
            }
            if( isNaN(loan_amount) ){
                loan_amount = 0;
            }
            if( isNaN(allowance) ){
                allowance = 0;
            }

            $(".total_allowance2_"+id).val(parseInt(other_amount + hidden_allowance));
            $(".total_allowance2_"+id).html(parseInt(other_amount + hidden_allowance));

            $(".total_deduction2_"+id).val(parseInt(loan + advance_salary_amount + hidden_deduction));
            $(".total_deduction2_"+id).html(parseInt(loan + advance_salary_amount + hidden_deduction));

            $(".net_salary2_"+id).val(parseInt((net_salary - allowance)  + other_amount + allowance + loan_amount + advance_salary));
            $(".net_salary2_"+id).html(parseInt((net_salary - allowance) + other_amount + allowance + loan_amount + advance_salary));
        }


    </script>

@endsection