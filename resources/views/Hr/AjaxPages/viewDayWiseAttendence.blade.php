<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

{{ Form::open(array('url' => 'had/addDayWiseAttendanceDetail')) }}
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <input type="hidden" name="attendance_type" id="attendance_type" value="2">
            <input type="hidden" name="m" id="m" value="{{ $m }}">
            <input type="hidden" name="employeeSection[]" value="1">
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
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="department_id" id="department_id" onchange="filterEmployee()">
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
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Date:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" name="attendance_date" id="attendance_date" value="{{ $currentDate }}" class="form-control requiredField"  />
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-top: 30px">
                <input type="button" class="btn btn-sm btn-primary" onclick="viewDayWiseAttendanceDetail()" value="View">
            </div>
        </div>
    </div>
</div>
<div id="daywiseAttendanceArea"></div>
{{ Form::close() }}

<script>

    $(document).ready(function () {
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

    function viewDayWiseAttendanceDetail() {

        var m = '{{ Input::get('m') }}';
        var region_id = $("#region_id").val();
        var department_id = $("#department_id").val();
        var emp_code = $(".emp_code").val();
        var attendance_date = $("#attendance_date").val();
        jqueryValidationCustom();
        if(validate == 0) {
            $('#daywiseAttendanceArea').html('<div class="loading"></div>');
            $.ajax({
                url: "{{ url('/') }}/hdc/viewDayWiseAttendanceDetail",
                type: 'GET',
                data: {
                    region_id: region_id,
                    department_id: department_id,
                    emp_code: emp_code,
                    attendance_date: attendance_date,
                    m: m
                },
                success: function (response) {
                    $('#daywiseAttendanceArea').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#daywiseAttendanceArea').html(response);

                }
            });
        }
    }

    function filterEmployee(){
        var region_id = $("#region_id").val();
        var department_id = $("#department_id").val();
        var m = "{{ Input::get('m') }}";
        var url = '{{ url('/') }}/slal/getEmployeeRegionList';

        if(region_id != '' && department_id != ''){
            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                type:'GET',
                url:url,
                data:{department_id:department_id,region_id:region_id,m:m},
                success:function(res){
                    $('#emp_loader').html('');
                    $('select[name="emp_code"]').empty();
                    $('select[name="emp_code"]').html(res);
                    $('select[name="emp_code"]').prepend("<option value='All' selected>All</option>");
                }
            });
        }
        else{
            $("#department_id").val('');
        }
    }


</script>