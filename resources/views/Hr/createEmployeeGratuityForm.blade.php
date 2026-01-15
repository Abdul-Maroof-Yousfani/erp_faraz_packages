<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
?>

@extends('layouts.default')
@section('content')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
    <style>
        
        input[type="checkbox"]{ width:30px;
            height:20px;
        }

    </style>

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">Employee Gratuity Form</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintGratuity','','1');?>
                            <?php echo CommonHelper::displayExportButton('Gratuity','','1')?>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                                    <input type="hidden" name="m" value="<?=Input::get('m')?>" />
                                </div>
                            </div>
                            <form method="post" action="{{url('had/addEmployeeGratuityDetail')}}">
                                <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                                <input type="hidden" name="m" id="m" value="<?php echo $m ?>">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Regions:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="region_id" id="region_id" required>
                                            <option value="">Select Region</option>
                                            @foreach($employee_regions as $key2 => $y2)
                                                <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Category:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="emp_category_id" id="emp_category_id" required onchange="empCategory()">
                                            <option value="">Select Category</option>
                                            @foreach($employee_category as $key2 => $y2)
                                                <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Employee Project</label>
                                        <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()">
                                            <option value="0">Select Project</option>
                                            @foreach($Employee_projects as $value)
                                                <option value="{{$value->id}}">{{$value->project_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Employee:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="emr_no" id="emr_no" required></select>
                                        <div id="emp_loader"></div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Till Date:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input class="form-control requiredField" type="date" name="till_date" id="till_date" required>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <label>Show All</label><br>
                                        <input type="checkbox" class="checkbox" id="show_all" name="show_all" value="1">
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                                        <input type="button" value="Calculate" class="btn btn-primary" style="margin-top: 28px;">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="gratuitySection"></div>
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
                        viewEmployeeGratuityForm();
                    }else if(validate == 1){
                        return false;
                    }
                }

            });

            $('#emr_no').select2();
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $("#employee_project_id").select2();
        });


        $('.checkbox').change(function () {
            if ($(this).is(':checked')) {
                $('#region_id').attr('disabled', true);
                $('#emp_category_id').attr('disabled', true);
                $('#emr_no').attr('disabled', true);
                $('#employee_project_id').attr('disabled', true);
                $('#region_id').removeClass('requiredField').removeAttr('required');
                $('#emp_category_id').removeClass('requiredField').removeAttr('required');
                $('#emr_no').removeClass('requiredField').removeAttr('required');
            } else {
                $('#region_id').attr('disabled', false);
                $('#emp_category_id').attr('disabled', false);
                $('#emr_no').attr('disabled', false);
                $('#employee_project_id').attr('disabled', false);
                $('#region_id').addClass('requiredField').attr('required');
                $('#emp_category_id').addClass('requiredField').attr('required');
                $('#emr_no').addClass('requiredField').attr('required');
            }
        });

        function viewEmployeeGratuityForm() {

            var region_id = $('#region_id').val();
            var emp_category_id = $('#emp_category_id').val();
            var employee_project_id = $('#employee_project_id').val();
            var emr_no = $('#emr_no').val();
            var till_date = $('#till_date').val();
            var m = $('#m').val();
            if($("#show_all").is(':checked')){var show_All = 'yes'}
            else{var show_All = 'no'}

            jqueryValidationCustom();
            if(validate == 0)
            {
                $('#gratuitySection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: "/hdc/viewEmployeeGratuityForm",
                    type: 'GET',
                    data: {show_All:show_All,region_id:region_id,emp_category_id:emp_category_id,
                        till_date:till_date,emr_no: emr_no, m : m,employee_project_id:employee_project_id},
                    success: function (response){
                        $('#gratuitySection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                        $('#gratuitySection').append(response);

                    }
                });
            }
            else
            {
                $('#gratuitySection').html('');
            }

        }


        function employeeProject() {
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            var employee_project_id = $("#employee_project_id").val();
            if(employee_project_id == ''){
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
                        }
                    });
                } else {
                    $('select[name="emr_no"]').empty();
                }
            }
        }

        function calculateGratuity(salary, id, month)
        {
            var totalPoints = 0;
            if(month >= 12)
            {
                $(".gratuity2_"+id).val(parseInt((salary / 12)  * month));

                $('.total_gratuity2').each(function(){
                    totalPoints += parseFloat($(this).val());
                });
                $('.set_total_gratuity').html(totalPoints);
            }

            else
            {
                $(".gratuity2_"+id).val('');
            }

        }


    </script>

    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection