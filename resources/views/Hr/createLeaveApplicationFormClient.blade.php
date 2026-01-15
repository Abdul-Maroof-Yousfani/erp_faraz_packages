<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
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
                            <span class="subHeadingLabelClass">Create Leave Application</span>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="m" id="m" value="<?= Input::get('m') ?>">
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
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <input type="button" class="btn btn-sm btn-primary" onclick="manageEmployeeApplication()" value="View Application" style="margin-top: 30px;" />
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

        function manageEmployeeApplication(){
            var emp_code = $('select[name="emp_code"]').val();
            var m = $("#m").val();
            jqueryValidationCustom();
            if(validate == 0) {
                $('#run_loader').html('<div class="loading"></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewLeaveApplicationClientForm',
                    type: "GET",
                    data: {emp_code: emp_code, m: m},
                    success: function (data) {
                        $('.employeePayslipSection').empty();
                        $('.employeePayslipSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' + data + '</div>');
                        $('#run_loader').html('');
                    }
                });
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

        $(document).ready(function(){
            $('.emp_code').select2();
            $('.region_id').select2();
            $('.department_id').select2();
        });


    </script>
@endsection