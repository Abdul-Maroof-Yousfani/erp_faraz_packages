<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
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
                                <span class="subHeadingLabelClass">Create Loan Request Form</span>
                            </div>
                        </div>
                        <div class="lineHeight"></div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addLoanRequestDetail'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?= Input::get('m') ?>">
                            <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="get_clone">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Regions:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" name="region_id" id="region_id" onchange="filterEmployee()">
                                                        <option value="">Select Region</option>
                                                        @foreach($employee_regions as $key2 => $y2)
                                                            <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Department:</label>
                                                    <select class="form-control" name="department_id" id="department_id" onchange="filterEmployee()">
                                                        <option value="">Select Department</option>
                                                        @foreach($employee_department as $key2 => $y2)
                                                            <option value="{{ $y2->id}}">{{ $y2->department_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Employee:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField emp_code" name="emp_code" id="emp_code" ></select>
                                                    <span id="emp_loader"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Needed on Month & Year:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="month" name="needed_on_date" id="needed_on_date" value="" class="form-control requiredField count_rows" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Loan Type</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select name="loan_type_id" class="form-control requiredField" id="loan_type_id">
                                                        <option value="">Select</option>
                                                        @foreach($loanTypes as $laonTypeValue)
                                                            <option value="{{ $laonTypeValue->id}}">{{ $laonTypeValue->loan_type_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Loan Amount</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="number" name="loan_amount" id="loan_amount" value="" class="form-control requiredField count_rows" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Per Month Deduction</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="number" name="per_month_deduction" id="per_month_deduction" value="" class="form-control requiredField count_rows" required />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
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
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <label class="sf-label">Loan Description</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <textarea required name="loan_description" class="form-control requiredField" id="contents"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="insert_clone"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>
                            </div>
                            <?php echo Form::close();?>
                        </div>
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
                    }else if(validate == 1){
                        return false;
                    }
                }
            });

            $('.addMoreLoanRequestSection').click(function (){
                var count_rows = $('.count_rows').length;
                count_rows++;
                var m = '<?= Input::get('m'); ?>';
                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormLoanRequestDetail',
                    type: "GET",
                    data: { count_rows:count_rows,m:m},
                    success:function(data) {
                        $('.insert_clone').append('<div id="sectionLoanRequest_'+count_rows+'"><button type="button"  onclick="removeLoanRequestSection('+count_rows+')" class="btn btn-xs btn-danger">Remove</button><div class="lineHeight">&nbsp;</div>'+data+'</div>');
                    }
                });
            });
            $('#loan_type_id').select2();
            $('#department_id').select2();
            $('.emp_code').select2();
            $('#region_id').select2();
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
                        $('select[name="emp_code"]').prepend("<option value='' selected>Select Employee</option>");
                    }
                });
            }
            else{
                $("#department_id").val('');
            }
        }


        function removeLoanRequestSection(id){
            var elem = document.getElementById('sectionLoanRequest_'+id+'');
            elem.parentNode.removeChild(elem);
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