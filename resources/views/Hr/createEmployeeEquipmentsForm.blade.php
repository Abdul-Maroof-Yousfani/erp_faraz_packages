<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>

<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
</style>

<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')

    <div class="row">&nbsp;</div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">Create Employee Equipment Form </span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'had/addEmployeeEquipmentDetail',"enctype"=>"multipart/form-data"));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="company_id" value="<?=$m?>">
                    <input type="hidden" name="employeeSection[]" value="1" />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
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
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label>Employee Project</label>
                                            <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()">
                                                <option value="0">Select Project</option>
                                                @foreach($Employee_projects as $value)
                                                    <option value="{{$value->id}}">{{$value->project_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Employee:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="emr_no" id="emr_no" required></select>
                                            <div id="emp_loader"></div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-top: 28px ">
                                            <a class=" btn btn-primary" onclick="viewEmployeeEquipmentsForm()"> Search </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="equipmentSectionLoader"></div>
                            <div class="equipmentSection"></div>
                        </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var subDepartment = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function(){
                    subDepartment.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of subDepartment) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

            $('#emp_category_id').select2();
            $('#region_id').select2();
            $('#emr_no').select2();
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


        function viewEmployeeEquipmentsForm()
        {
            $('.equipmentSectionLoader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var emr_no = $('#emr_no').val();
            var m = '<?= Input::get('m')?>';
            if(emr_no) {
                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewEmployeeEquipmentsForm',
                    type: "GET",
                    data: { emr_no:emr_no,m:m},
                    success:function(data) {
                        $('.equipmentSection').html(data);
                        $('.equipmentSectionLoader').html('');
                    },
                    error: function () {
                        $('.equipmentSectionLoader').html('');
                        $('.equipmentSection').html('');
                    }
                });
            }
        }


        function insuranceCheck()
        {
            if ($('.insurance').is( ":checked" )) {
                $('#insurance_number').prop("disabled", false);
                $('#insurance_path').prop("disabled", false);
            }
            else {
                $('#insurance_number').prop("disabled", true);
                $('#insurance_path').prop("disabled", true);
            }

        }

        function eobiCheck()
        {
            if ($('.eobi').is( ":checked" )) {
                $('#eobi_number').prop("disabled", false);
                $('#eobi_path').prop("disabled", false);
            }
            else {
                $('#eobi_number').prop("disabled", true);
                $('#eobi_path').prop("disabled", true);
            }

        }

        function mobileCheck()
        {
            if ($('.mobile').is( ":checked" )) {
                $('#model_number').prop("disabled", false);
                $('#mobile_number').prop("disabled", false);
                $('#sim_number').prop("disabled", false);
            }
            else {
                $('#model_number').prop("disabled", true);
                $('#mobile_number').prop("disabled", true);
                $('#sim_number').prop("disabled", true);
            }

        }



    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection