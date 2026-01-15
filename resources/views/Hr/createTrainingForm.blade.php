<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
$current_date = date('Y-m-d');
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">Create Training Form</span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                            <b> Add Custom Participants</b>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <input style="height: 15px;width: 15px;" id="add_custom" type="checkbox" name="">
                        </div>
                    </div>
                    <div class="lineHeight"></div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'had/addTrainingDetail',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="employeeSection[]" value="1">
                            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
                            <input type="hidden" name="participant_type" id="participant_type" value="1">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
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
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Employee Project</label>
                                                <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()">
                                                    <option value="0">Select Project</option>
                                                    @foreach($Employee_projects as $value)
                                                        <option value="{{$value->id}}}">{{$value->project_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Trainer Name</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="trainer_name" id="trainer_name" class="form-control requiredField">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Participants:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                </div>
                                                <span id="custom_area">
                                                     <select class="form-control requiredField" name="participants_name[]" id="participants_name" multiple required>
                                                     </select>
                                                <div id="emp_loader"></div>
                                                </span>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Certificate Number</label>
                                                <span class="rflabelsteric"><strong></strong></span>
                                                <input type="text" name="certificate_number" id="certificate_number" class="form-control">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Certificate Upload</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="file" name="certificate_uploading[]" id="certificate_uploading[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Location</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="location_id" id="location_id" required>
                                                    <option value="">Select Location</option>
                                                    @foreach($employee_locations as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_location}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Training Date</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input required class="form-control requiredField" type="date" name="training_date" id="training_date" value="<?php echo $current_date;?>" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>Topic Name</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input required class="form-control requiredField" type="text" name="topic_name" id="topic_name"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="submit" class="btn btn-success" id="trainingSubmit" value="Submit" />
                                    </div>
                                </div>
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
                        //alert(response);
                    } else if (validate == 1) {
                        return false;
                    }
                }
            });

            $('#sub_department_id').select2();
            $('#participants_name').select2();
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $('#location_id').select2();
            $('#employee_project_id').select2();

        });

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
                            $('select[id="participants_name"]').empty();
                            $('select[id="participants_name"]').html(data);
                        }
                    });
                } else {
                    $('select[id="participants_name"]').empty();
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
                        data: {emp_category_id: emp_category_id, region_id: region_id, m: m},
                        success: function (data) {
                            $('#emp_loader').html('');
                            $('select[id="participants_name"]').empty();
                            $('select[id="participants_name"]').html(data);
                        }
                    });
                } else {
                    $('select[id="participants_name"]').empty();
                }
            }
        }
            $(function(){
            $( "#add_custom" ).click(function() {
                if ($('#add_custom').is(':checked')) {
                    $("#custom_area").html('<input class="form-control requiredField" type="text" name="participants_name" id="participants_name" placeholder="Write here" required/>');
                    $("#participant_type").val(2);
                }
                else{
                    $("#custom_area").html('<select class="form-control requiredField" name="participants_name[]" id="participants_name" multiple aria-multiselectable="true" required>' +
                        '</select><div id="emp_loader"></div>');
                    $("#participant_type").val(1);
                    $('#participants_name').select2();
                    var emp_category_id = $("#emp_category_id").val();
                    var region_id = $("#region_id").val();
                    if(region_id == ''){alert('Please Select Region !');return false;}
                    var m = '<?= Input::get('m'); ?>';
                    if(emp_category_id) {
                        $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                        $.ajax({
                            url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
                            type: "GET",
                            data: { emp_category_id:emp_category_id,region_id:region_id,m:m},
                            success:function(data) {
                                $('#emp_loader').html('');
                                $('select[id="participants_name"]').empty();
                                $('select[id="participants_name"]').html(data);
                            }
                        });
                    }else{
                        $('select[name="emr_no"]').empty();
                    }
                }
            });


        });

    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

@endsection