<?php
$accType = Auth::user()->acc_type;
use App\Helpers\HrHelper;
$m = Input::get('m');
$currentDate = date('Y-m-d');
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">

@extends('layouts.default')
@section('content')

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Manual Leaves</span>
                        </div>
                    </div>
                    <div class="lineHeight"></div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'had/addManuallyLeaves'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
                            <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
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
                                                <label class="sf-label">Employee:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="emr_no" id="emr_no" required>
                                                </select>
                                                <div id="emp_loader"></div>
                                            </div>
                                        </div>
                                         <div class="row">
                                             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Casual:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="casual_leaves" id="casual_leaves" value="" class="form-control requiredField" required onchange="checkManualLeaves(this.value,'3','casual-leaves','<?php echo $m ?>','casual_leaves')" />
                                                <span class="rflabelsteric" id="casual-leaves"></span>
                                             </div>
                                             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Sick:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="sick_leaves" id="sick_leaves" value="" class="form-control requiredField" required onchange="checkManualLeaves(this.value,'2','sick-leaves','<?php echo $m ?>','sick_leaves')" />
                                                <span class="rflabelsteric" id="sick-leaves"></span>
                                             </div>
                                             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Annual:</label>
                                                <span class="rflabelsteric"></span>
                                                <input type="number" name="annual_leaves" id="annual_leaves" value="" class="form-control requiredField" required onchange="checkManualLeaves(this.value,'1','annuals-leaves','<?php echo $m ?>','annual_leaves')" />
                                                <span class="rflabelsteric" id="annuals-leaves"></span>
                                             </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="submit" class="btn btn-success" id="create" value="Submit" />
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
                        data: {emp_category_id: emp_category_id, region_id: region_id, m: m},
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

            $('#emr_no').select2();
            $('#department_id').select2();
            $('#region_id').select2();
            $('#emp_category_id').select2();
            $('#employee_project_id').select2();

        });

        function checkManualLeaves(value,leave_type,error_status,m,main_id){
            var emr_no = $("#emr_no").val();
            var casual_leaves = $("#casual-leaves").html();
            var sick_leaves = $("#sick-leaves").html();
            var annual_leaves = $("#annuals-leaves").html();
            var error = 'Your'+' '+error_status+' '+'is greater than your leave policy';
            if(emr_no != null) {
                $.ajax({
                    type: 'GET',
                    url: '<?php echo url('/') ?>/hdc/checkManualLeaves',
                    data: {value: value, leave_type: leave_type, error_status: error_status, m: m,emr_no:emr_no},
                    success: function (res) {
                        if(res != 'done'){
                            $("#"+error_status).html(res);
                            $("#"+main_id).val('');
                            $( "#create" ).prop( "disabled", true);
                        }
                        else{
                            $('#'+error_status).html('');
                            $( "#create" ).prop( "disabled", false );
                        }
                    }
                });
            }
            else{
                $("#casual_leaves").val('');
                $("#sick_leaves").val('');
                $("#annual_leaves").val('');
                alert('Please Select Employee');
            }
        }

    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection