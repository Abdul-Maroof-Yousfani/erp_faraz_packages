<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">View Employee Promotions </span>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="company_id" value="<?=$m?>">
                    <input type="hidden" name="employeeSection[]">
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
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <input type="button" class="btn btn-sm btn-primary" id="searchBtn" style="margin-top: 25px;" onclick="viewEmployeePromotionsDetail()" value="View" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="promotionSection"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {

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
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });
            $('.emp_code').select2();
            $('#region_id').select2();
            $('#department_id').select2();

        });


        function viewEmployeePromotionsDetail(){
            var emp_code  = $('.emp_code').val();
            var m = '{{ $m }}';
            var rights_url = 'hr/viewEmployeePromotions';
            var url = '{{ url('/') }}/hdc/viewEmployeePromotionsDetail';

            if(emp_code != '') {
                jqueryValidationCustom();
                if (validate == 0) {
                    $('#promotionSection').html('<div class="loading"></div>');
                    $.ajax({
                        type:'GET',
                        url:url,
                        data:{emp_code:emp_code,m:m, rights_url:rights_url},
                        success:function(res){
                            $('#promotionSection').html(res);

                        }
                    });
                }
            }else {
                swal('Promotion','Select Employee');
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