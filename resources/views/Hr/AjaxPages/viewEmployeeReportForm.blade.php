<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><b><u>Employee Report Form</u></b></h3>
    </div>
</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Search By Region:</label>
                <select class="form-control" name="region_id" id="region_id" >
                    <option value="">Select Region</option>
                    @foreach($regions as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Search By Category:</label>
                <select class="form-control" name="employee_category_id" id="employee_category_id">
                    <option value="">Select </option>
                    @foreach($employee_category as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>Employee Project</label>
                <select class="form-control" name="employee_project_id" id="employee_project_id">
                    <option value="">Select Project</option>
                    @foreach($Employee_projects as $value)
                        <option value="{{$value->id}}">{{$value->project_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Gender</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control" name="emp_gender" id="emp_gender">
                    <option value="">Select Gender</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Job Type</label>
                <select class="form-control" name="job_type_id" id="job_type_id">
                    <option value="">Select </option>
                    @foreach($job_type as $y3)
                        <option value="{{ $y3->id}}">{{ $y3->job_type_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>&nbsp</div>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12"></div>
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12 text-right">
                <label>Show All
                <input type="checkbox" class="checkbox" id="show_all" name="show_all" value="1" style="margin-top: -2px;">
                </label>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
                <button class="btn btn-info" id="search">Search</button>
            </div>
        </div>
    </div>
</div>

<script>


    $(document).ready(function(){

        $('.checkbox').change(function () {
            if ($(this).is(':checked')) {
                $('#employee_category_id').attr('disabled', true);
                $('#region_id').attr('disabled', true);
                $('#emp_gender').attr('disabled', true);
                $('#job_type_id').attr('disabled', true);
                $('#employee_project_id').attr('disabled', true);
                $('#employee_project_id').attr('disabled', true);

            } else {
                $('#employee_category_id').attr('disabled', false);
                $('#region_id').attr('disabled', false);
                $('#emp_gender').attr('disabled', false);
                $('#job_type_id').attr('disabled', false);
                $('#employee_project_id').attr('disabled', false);
            }
        });

        $(".btn-info").click(function(e){
            var degreeType = new Array();
            var val;
            $("input[name='HrReports[]']").each(function(){
                degreeType.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of degreeType) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });


        $('#region_id').select2();
        $('#emp_gender').select2();
        $('#employee_category_id').select2();
        $('#job_type_id').select2();
        $('#employee_project_id').select2();

    });


    $('#search').click(function() {

        var employee_category_id = $('#employee_category_id').val();
        var region_id = $('#region_id').val();
        var emp_gender = $('#emp_gender').val();
        var job_type_id = $('#job_type_id').val();
        var employee_project_id = $("#employee_project_id").val();
        var show_all = $("input[name='show_all']:checked"). val();
        var m = $('#m').val();

        if(show_all == 1)
        {
            var data = {show_all: show_all, m:m};
        }
        else {

            var data = {'employee_category_id':employee_category_id, 'region_id':region_id, 'emp_gender':emp_gender,'job_type_id':job_type_id,m:m,employee_project_id:employee_project_id};
        }


        $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        if(validate == 0)
        {
            $.ajax({
                url: "/HrReports/viewEmployeeReport",
                type: 'GET',
                data: data,
                success: function (response){
                    $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-area').html(response);

                }
            });
        }
        else
        {
            $('#report-area').html('');
            return false;
        }
    });



</script>
<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
