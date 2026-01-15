<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\SubDepartment;

?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <style>
        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }
    </style>
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Advance Salary Form</span>
                            </div>
                        </div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addAdvanceSalaryDetail','id'=>'employeeForm'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="{{ $m }}">
                            <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
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
                                                <select class="form-control" name="department_id" id="department_id" onchange="filterEmployee()">
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
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Amount:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="advance_salary_amount" id="advance_salary_amount" value="" class="form-control requiredField" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Advance Salary to be Needed On</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField" name="salary_needed_on" id="salary_needed_on" value="" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Deduction Month & Year</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="month" class="form-control requiredField" name="deduction_month_year" id="deduction_month_year" value="" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Account head</label>
                                                <span class="rflabelsteric"><strong>*</strong></span><br>
                                                <input checked type="radio" name="account_head_id" id="cash" value="1" onclick="accountCheck(this.value)"> <label for="cash">Cash</label> &nbsp;
                                                <input type="radio" name="account_head_id" id="bank" value="2" onclick="accountCheck(this.value)"> <label for="bank">Bank</label> &nbsp;
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Accounts</label>
                                                <select disabled name="account_id" id="account_id" class="form-control">
                                                    <option value="">Select Account</option>
                                                    @foreach($account as $key => $val)
                                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Reason (Detail)</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="advance_salary_detail" class="form-control requiredField"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="employeeSection"></div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>
                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function () {

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

            $('#department_id').select2();
            $('.emp_code').select2();
            $('#region_id').select2();
            $('#account_id').select2();

        });

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
                        $('select[name="emp_code"]').prepend("<option value='0' selected>Select Employee</option>");
                    }
                });
            }
            else{
                $("#department_id").val('');
            }
        }

        function accountCheck(value)
        {
            if(value == 1){
                $('#account_id').removeClass('requiredField');
                $('#account_id').prop("disabled", true);
                $('#account_id').val('');
            }else if(value == 2) {
                $('#account_id').addClass('requiredField');
                $('#account_id').prop("disabled", false);
            }
        }
    </script>
@endsection