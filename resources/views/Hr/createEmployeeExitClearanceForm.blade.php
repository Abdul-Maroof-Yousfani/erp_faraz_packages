<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <style>

        input[type="radio"]{ width:30px;
            height:20px;
        }

    </style>

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Employee Exit Clearance Form</span>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                                </div>
                            </div>
                            <form method="post" action="{{url('had/addEmployeeExitClearanceDetail')}}">
                                <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                                <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
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
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <label for="exit_clearance_check">Exit Clearance</label><br>
                                        <input type="radio" name="exit_clearance_check" id="exit_clearance_check" checked value="1">
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <label for="final_settlement_check">Final Settlement</label><br>
                                        <input type="radio" name="exit_clearance_check" id="final_settlement_check" value="2">
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <input type="button" class=" btn btn-primary" style="margin-top: 30px;" value="Search">
                                    </div>
                                </div>
                                <div id="exitSection"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function () {

            // Wait for the DOM to be ready
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
                        viewEmployeeExitClearance();
                    }else if(validate == 1){
                        return false;
                    }
                }

            });

            $('.emp_code').select2();
            $('.region_id').select2();
            $('.department_id').select2();
        });

        function viewEmployeeExitClearance() {

            var emp_code = $('.emp_code').val();
            var m = $('#company_id').val();

            if (emp_code != '')
            {
                $('#exitSection').html('<div class="loading"></div>');
                if($("Input[name='exit_clearance_check']:checked").val() == 1)
                {
                    $.ajax({
                        url: "{{ url('/') }}/hdc/viewEmployeeExitClearanceForm",
                        type: 'GET',
                        data: {emp_code: emp_code, m : m},
                        success: function (response){
                            $('#exitSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                            var result = response;
                            $('#exitSection').append(result);
                        }
                    });
                }
                else if($("Input[name='exit_clearance_check']:checked").val() == 2)
                {
                    $.ajax({
                        url: "{{ url('/') }}/hdc/viewFinalSettlement",
                        type: 'GET',
                        data: {emp_code: emp_code, m: m},
                        success: function (response) {
                            $('#exitSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                            $('#exitSection').append(response);

                        }
                    });
                }
            }
            else
            {
                $('#exitSection').html('');
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
                        $('select[name="emp_code"]').prepend("<option value='' selected>Select Employee</option>");
                    }
                });
            }
            else{
                $("#department_id").val('');
            }
        }
    </script>

@endsection