<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?> 

@extends('layouts.default')
@section('content')

<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
<style>
    td{ padding: 0px !important;}
    th{ padding: 0px !important;}

    .checkbox-lg .custom-control-label::before,
    .checkbox-lg .custom-control-label::after {
      top: .8rem;
      width: 1.55rem;
      height: 1.55rem;
    }
</style>

<div class="well">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <span class="subHeadingLabelClass">View Hr Letters</span>
        </div>
    </div>
    <div class="lineHeight">&nbsp;</div>
    <div class="row">
        <?php echo Form::open(array('url' => 'had/addHrLetters','id'=>'HrLetters', 'method' => 'post'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="show_all_status" id="show_all_status" >
        <input type="hidden" name="company_id" value="<?=$m?>">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Region:</label>
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
                            <select class="form-control requiredField" name="emp_category_id" id="emp_category_id" onchange="empCategory()" required>
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
                            <label class="sf-label">Letter:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" name="letter_id" id="letter_id" required>
                                <option value="">Select Letters</option>
                                <option value="1">Warning Letter</option>
                                <option value="2">MFM South Increment Letter</option>
                                <option value="3">MFM South Without Increment Letter</option>
                                <option value="4">Contract Conclusion Letter</option>
                                <option value="5">Termination Letter Format 1</option>
                                <option value="6">Termination Letter Format 2</option>
                                <option value="7">Transfer Letter</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                            <label for="show_all">Show All</label><br>
                            <input type="checkbox"class="checkboxs" id="show_all" name="show_all" value="1" style="width: 20px;height: 20px;">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 25px">
                            <button class="btn btn-primary" id="search" onclick="searchLetters()" type="button">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="dataLoader"></div>
            <div class="letterSection"></div>
        </div>
        <?php echo Form::close();?>
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
            $('#designation_id').select2();
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $('#emr_no').select2();
            $('#letter_id').select2();
            $('#employee_project_id').select2();

            $('.checkboxs').change(function () {
                if ($(this).is(':checked')) {
                    $('#emp_category_id').attr('disabled', true);
                    $('#emr_no').attr('disabled', true);
                    $('#employee_project_id').attr('disabled', true);
                    $('#region_id').attr('disabled', true);
                    $('#emp_category_id').removeClass('requiredField').removeAttr('required');
                    $('#region_id').removeClass('requiredField').removeAttr('required');
                    $(this).val('1');
                } else {
                    $('#emr_no').attr('disabled', false);
                    $('#emp_category_id').attr('disabled', false);
                    $('#employee_project_id').attr('disabled', false);
                    $('#region_id').attr('disabled', false);
                    $('#emp_category_id').addClass('requiredField').attr('required');
                    $('#region_id').addClass('requiredField').attr('required');
                    $(this).val('0');
                }
            });

        });


        $('#letter_id').on('change', function() {
            var emr_no = $('#emr_no').val();
            var letter_id = $('#letter_id').val();


            if(letter_id == 2)
            {

            }

            else if(letter_id == 3)
            {

            }

            else if(letter_id == 4)
            {

            }
            else if(letter_id == 1 || letter_id == 5 || letter_id == 6)
            {

            }

        });

        $('.checkboxs').change(function() {
                if(this.checked) {
                    $("#show_all_status").val('1');
                }
            });

        function searchLetters() {
            var emr_no = $('#emr_no').val();
            var m = '<?= Input::get('m'); ?>';
            var letter_id = $('#letter_id').val();
            var employee_project_id = $('#employee_project_id').val();
            var rights_url = 'hr/viewHrLetters';
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            var show_all = $("#show_all_status").val();

            var performance_from = $('#performance_from').val();
            var performance_to = $('#performance_to').val();
            var confirmation_from = $('#confirmation_from').val();
            var conclude_date = $('#conclude_date').val();
            var settlement_date = $('#settlement_date').val();

            jqueryValidationCustom();

            if(validate == 0) {
                    data = {emr_no: emr_no, m: m, letter_id: letter_id,
                        performance_from:performance_from, performance_to:performance_to,
                        confirmation_from:confirmation_from,conclude_date:conclude_date,
                        settlement_date:settlement_date,rights_url:rights_url,
                        employee_project_id:employee_project_id,emp_category_id:emp_category_id,
                        region_id:region_id,show_all:show_all};

                $('#dataLoader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/hdc/viewHrLetters',
                        type: "GET",
                        data:data,
                        success: function (data) {
                            $('#dataLoader').html('');
                            $("#show_all_status").val('');
                            $(".letterSection").html(data);
                        }
                    });

            }
            else
            {
                return false;
            }
        }



    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection