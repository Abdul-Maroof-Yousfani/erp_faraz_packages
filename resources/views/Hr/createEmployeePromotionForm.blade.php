<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <style>
        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }
        td{ padding: 0px !important;}
        th{ padding: 0px !important;}
    </style>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Create Employee Promotion Form</span>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <?php echo Form::open(array('url' => 'had/addEmployeePromotionDetail','id'=>'employeePromotionForm',"enctype"=>"multipart/form-data"));?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="company_id" value="<?=$m?>">
                <input type="hidden" name="employeeSection[]">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
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

                            <div id="emp_data_loader">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="emp_data"></div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Designation</label>
                                    <select class="form-control" id="designation_id" name="designation_id">
                                        <option value="">Select</option>
                                        @foreach($designation as $key5 => $value5)
                                            <option value="{{ $value5->id}}">{{ $value5->designation_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Promotion Letter</label>
                                    <span class="rflabelsteric"><strong></strong></span>
                                    <input type="file" name="promotion_letter[]" id="promotion_letter" class="form-control" multiple>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Increment :</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="increment" id="increment" onkeyup="changeSalary()" value="" class="form-control requiredField" required/>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Salary :</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="salary" id="salary" value="" class="form-control requiredField" required readonly/>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Promotion Date :</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="date" name="promotion_date" id="promotion_date" value="" class="form-control requiredField" required/>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Add Allowances :</label><br>
                                    <input type="checkbox" name="addAllowancesCheck" id="addAllowancesCheck" value="1"/>
                                </div>

                                <div class="form_area" id="addAllowancesArea" style="display: none;"></div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="allowanceLoader"></div>
                            <div class="allowanceData"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreAllowanceSection" value="Add More Allowance" id="addMoreAllowancesBtn" style="display: none" />
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {

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


            var m = '<?= Input::get('m'); ?>';

            $('.addMoreAllowanceSection').click(function (e){
                var form_rows_count = $(".get_data").length;
                var data = $('.count_rows').html();
                $('.allowanceData').append('<div class="row" id="remove_area_'+form_rows_count+'"><div class="count_rows">' +
                    '' +
                    '</div>' +
                    '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">' +
                    ' <label class="sf-label">Allowance Type:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" name="allowance_type[]" id="allowance_type[]" value="" class="form-control requiredField" /></div>' +
                    '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">' +
                    '<label class="sf-label">Amount:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="number" name="allowance_amount[]" id="allowance_amount[]" value="" class="form-control requiredField" /></div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><br>' +
                    '<button onclick="removeEmployeeSection('+form_rows_count+')" type="button" class="btn btn-xs btn-danger">Remove</button>' +
                    '</div>' +
                    '</div>');

            });

            $('.emp_code').select2();
            $('#region_id').select2();
            $('#designation_id').select2();
            $('#department_id').select2();

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


        var previousSalary;
        $('.emp_code').on('change', function() {
            $('#emp_data_loader').html('<div class="loading"></div>');
            var emp_code = $(this).val();
            var m = '<?= Input::get('m'); ?>';
            if(emp_code) {
                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewEmployeePreviousPromotionsDetail',
                    type: "GET",
                    data: { emp_code:emp_code,m:m},
                    success:function(data) {
                        $('#emp_data_loader').html('');
                        $("#emp_data").html('<div class="row">&nbsp;</div>'+data);
                        previousSalary = parseFloat($('#previousSalary').val());
                        $('#salary').val(previousSalary);
                    }
                });
            }
            else
                {
                    $('#emp_data_loader').html('');
                    $("#emp_data").html('');
                }
        });

        function removeEmployeeSection(id){
            $("#remove_area_"+id).remove();
        }

        $('#addAllowancesCheck').click(function(){
            if($(this).is(":checked") == true) {

                $('.allowanceLoader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                var emp_code = $('.emp_code').val();
                var m = '<?= Input::get('m'); ?>';
                if(emp_code) {
                    $.ajax({
                        url: '<?php echo url('/')?>/hdc/viewEmployeePreviousAllowancesDetail',
                        type: "GET",
                        data: { emp_code:emp_code,m:m},
                        success:function(data) {
                            $('.allowanceLoader').html('');
                            $(".allowanceData").html('<div class="row">&nbsp;</div>'+data);
                        }
                    });
                }
                else
                {
                    $('.allowanceLoader').html('');
                    $(".allowanceData").html('');
                }
                $('#addMoreAllowancesBtn').show();
            }
            else {
                $('#addMoreAllowancesBtn').hide();
                $(".allowanceData").html('');
            }
        });

        function changeSalary(){

            $('#salary').val(previousSalary);
            var salary = parseFloat($('#salary').val());
            var increment = parseFloat($('#increment').val());
            $('#salary').val(salary + increment);

            if ($('#increment').val() == '')
                $('#salary').val(previousSalary);

        }

    </script>

@endsection