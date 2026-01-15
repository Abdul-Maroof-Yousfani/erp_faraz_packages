<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')
    <style>
        input[type="radio"], input[type="checkbox"]
        { width:30px;
            height:20px;
        }

        hr{border-top: 1px solid cadetblue}
        td{ padding: 0px !important;}
        th{ padding: 0px !important;}

    </style>
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Employee Project Transfer Form</span>
                            </div>
                        </div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addEmployeeTransferProject','id'=>'employeeProjectForm',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=$m?>">
                            <input type="hidden" name="employeeSection[]">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Regions:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="region_id" id="region_id" required>
                                                    <option value="">Select Region</option>
                                                    @foreach($employee_regions as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Category:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="emp_category_id" id="emp_category_id"  onchange="empCategory()" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($employee_category as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Employee Project</label>
                                                <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()" required>
                                                    <option value="0">Select Project</option>
                                                    @foreach($Employee_projects as $value)
                                                        <option value="{{$value->id}}}">{{$value->project_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Employee:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="emr_no" id="emr_no" required></select>
                                                <div id="emp_loader"></div>
                                            </div>
                                        </div>
                                        <div id="emp_data_loader"></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="emp_data"></div>

                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Transfer Project:</label>
                                                <select class="form-control requiredField" name="transfer_project_id" id="transfer_project_id" required>
                                                    <option value=""> Select Transfer Project</option>
                                                    @foreach($Employee_projects as $value)
                                                        <option value="{{$value->id}}}">{{$value->project_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Letter upload</label>
                                                <input type="file" name="letter_uploading[]" id="letter_uploading[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="employeeSection"></div>
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



        $(document).ready(function () {
            $('#location_id').select2();
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $('#emr_no').select2();
            $('#employee_project_id').select2();
            $("#transfer_project_id").select2();
        });

        var previousSalary;
        $('#emr_no').on('change', function() {
            $('#emp_data_loader').html('<div class="row">&nbsp;</div><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var emr_no = $(this).val();
            var m = '<?= Input::get('m'); ?>';
            if(emr_no) {
                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewPreviousEmployeeProject',
                    type: "GET",
                    data: { emr_no:emr_no,m:m},
                    success:function(data) {
                        $('#emp_data_loader').html('');
                        $("#emp_data").html('<div class="row">&nbsp;</div>'+data);
                        previousSalary = parseFloat($('#previousSalary').val());

                    }
                });
            }
            else
            {
                $('#emp_data_loader').html('');
                $("#emp_data").html('');
            }
        });

        $('#location_check').click(function(){
            if($(this).is(":checked") == true)
            {
                $("#different-package").show();
                $('#designation_id').addClass('requiredField');
                $('#grade_id').addClass('requiredField');
                $('#salary').addClass('requiredField');
                $('#increment').addClass('requiredField');
                $('#promotion_date').addClass('requiredField');
                $('#salary').val(previousSalary);
                $('#grade_id').select2();
                $('#designation_id').select2();

            }
            else
            {
                $("#different-package").hide();
                $('#designation_id').removeClass('requiredField');
                $('#grade_id').removeClass('requiredField');
                $('#salary').removeClass('requiredField');
                $('#increment').removeClass('requiredField');
                $('#promotion_date').removeClass('requiredField');
                $('#salary').val(null);
            }
        });

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

        function changeSalary()
        {
            $('#salary').val(previousSalary);
            var salary = parseFloat($('#salary').val());
            var increment = parseFloat($('#increment').val());
            $('#salary').val(salary + increment);

            if( $('#increment').val() == '')
                $('#salary').val(previousSalary);

        }






    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection