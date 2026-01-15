<?php

$accType = Auth::user()->acc_type;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <input type="hidden" name="attendance_type" id="attendance_type" value="1">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Regions:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="region_id" id="region_id" onchange="filterEmployee()">
                    <option value="">Select Region</option>
                    @foreach($employee_regions as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Department:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="department_id" id="department_id" onchange="filterEmployee()">
                    <option value="">Select Department</option>
                    @foreach($employee_department as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->department_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Employee:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField emp_code" name="emp_code" id="emp_code" ></select>
                <span id="emp_loader"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Month - Year</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="month" class="form-control requiredField" name="month_year" id="month_year" required value="">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Present Days:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" name="present_days" id="present_days" value="" class="form-control requiredField" required />
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Absent Days:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" name="absent_days" id="absent_days" value="" class="form-control requiredField" required />
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Leaves:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" name="no_of_leaves" id="no_of_leaves" value="" class="form-control requiredField" required />
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-top: 30px;">
                <button class="btn btn-success" onclick="addManualyAttendance()">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

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

        $('.emp_code').select2();
        $('#region_id').select2();
        $('#department_id').select2();

    });


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
                    $('select[name="emp_code"]').prepend("<option value='' selected>Select Employee</option>");
                }
            });
        }
        else{
            $("#department_id").val('');
        }
    }



</script>