<?php
$m = Input::get('m');
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">

@extends('layouts.default')
@section('content')
    <style>
        .name-d-d ul li {
            font-size: 17px;
            margin: 10px 0px 22px 0px;
        }

        .name-d-d-input ul li {
            margin: 7px 0px 10px 0px;
        }

        .depart-row .col-lg-3 {
            background-color: #080808;
            color: #fff;
            border-left: 1px solid #fff;
        }

        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }

        .depart-row-two .col-lg-4 {
            background-color: #999;
            color: #fff;
            border-left: 1px solid #fff;
            padding: 7px 0px 2px 0px;
        }

    </style>

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Employee ID Card Request Form</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body">
                            <?php echo Form::open(array('url' => 'had/addEmployeeIdCardRequestDetail',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                                <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                                <div class="gudia-gap">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <figure>
                                                <img src="assets/img/logo.jpg" class="img-responsive" title="" alt="">
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Regions:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="region_id" id="region_id">
                                                <option value="">Select Region</option>
                                                @foreach($employee_regions as $key2 => $y2)
                                                    <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Category:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="emp_category_id" id="emp_category_id" onchange="empCategory()">
                                                <option value="">Select Category</option>
                                                @foreach($employee_category as $key2 => $y2)
                                                    <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label>Employee Project</label>
                                            <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()">
                                                <option value="0">Select Project</option>
                                                @foreach($Employee_projects as $value)
                                                    <option value="{{$value->id}}}">{{$value->project_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Employee:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="emr_no" id="emr_no"></select>
                                            <div id="emp_loader"></div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 30px ">
                                            <input type="button" class=" btn btn-primary" value="Search" onclick="viewEmployeeIdCardRequest();">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="IdCardSection"> </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            // Wait for the DOM to be ready
            $(".btn-success").click(function (e) {
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function () {
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                    } else {
                        return false;
                    }
                }

            });
            $('#emr_no').select2();
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $("#employee_project_id").select2();
        });

        function viewEmployeeIdCardRequest() {

            var emr_no = $('#emr_no').val();
            var m = $('#company_id').val();
            var emp_category_id = $('#emp_category_id').val();

            if (emr_no != '') {
                if(emp_category_id != '') {
                    $('#IdCardSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    $.ajax({
                        url: "/hdc/viewEmployeeIdCardRequest",
                        type: 'GET',
                        data: {emr_no: emr_no, m: m},
                        success: function (response) {
                            $('#IdCardSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                            var result = response;
                            $('#IdCardSection').append(result);
                        }
                    });
                }
                else {
                    $('#IdCardSection').html('');
                    alert('Please Select Employee');
                    return false;

                }
            }
            else {
                $('#IdCardSection').html('');
                alert('You Did`nt Select Any Employee');
                return false;

            }

        }


        function employeeProject() {
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            var employee_project_id = $("#employee_project_id").val();
            if(employee_project_id == '0'){
                empCategory()
            }
            if (region_id == '') {
                alert('Please Select Region !');
                return false;
            } else if (emp_category_id == '') {
                alert('Please Select Cateogery !');
                return false;
            } else {
                var m = '<?= Input::get('m'); ?>';
                if (employee_project_id) {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getEmployeeProjectList',
                        type: "GET",
                        data: {
                            emp_category_id: emp_category_id,
                            region_id: region_id,
                            employee_project_id: employee_project_id,
                            m: m
                        },
                        success: function (data) {
                            $('#emp_loader').html('');
                            $('select[name="emr_no"]').empty();
                            $('select[name="emr_no"]').html(data);
                            $("#emr_no option[value='All']").remove();
                            $("#emr_no").prepend("<option value='' selected='selected'>Select Employee</option>");
                        }
                    });
                } else {
                    $('select[name="emr_no"]').empty();
                }
            }
        }

        function empCategory() {
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            if (region_id == '') {
                alert('Please Select Region !');
                return false;
            } else {
                var m = '<?= Input::get('m'); ?>';
                if (emp_category_id) {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
                        type: "GET",
                        data: {emp_category_id: emp_category_id, region_id: region_id, m: m,exit_clearance:'exit_clearance'},
                        success: function (data) {
                            $('#emp_loader').html('');
                            $('select[name="emr_no"]').empty();
                            $('select[name="emr_no"]').html(data);
                            $("#emr_no option[value='All']").remove();
                            $("#emr_no").prepend("<option value='' selected='selected'>Select Employee</option>");
                        }
                    });
                } else {
                    $('select[name="emr_no"]').empty();
                }
            }
        }
    </script>

    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection