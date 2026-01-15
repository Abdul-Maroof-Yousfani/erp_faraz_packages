<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$m = Input::get('m');

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$id = Input::get('id');
$initialSalary = HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_salary', $employee_location->emr_no, 'emr_no');

?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
<style>

    input[type="radio"], input[type="checkbox"]
    { width:30px;
        height:20px;
    }
    hr
    {
        border-top: 1px solid cadetblue
    }

</style>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="panel">
                    <div class="panel-body">
                        <?php echo Form::open(array('url' => 'had/editEmployeeTransferDetail',"enctype"=>"multipart/form-data"));?>
                        <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                        <input type="hidden" name="employeeSection[]">
                        <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <input type="hidden" name="count" id="count" value="<?= $count ?>">

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">EMR No :</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input readonly name="emr_no" id="emr_no" type="text" value="{{ $employee_location->emr_no }}" class="form-control requiredField">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Employee Name :</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input readonly name="emp_name" id="emp_name" type="text" value="{{HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $employee_location->emr_no, 'emr_no') }}" class="form-control requiredField">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Location :</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" id="location_id" name="location_id" required>
                                    <option value="">Select Location</option>
                                    @foreach($location as $key5 => $value)
                                        <option @if($employee_location->location_id == $value->id) selected @endif value="{{ $value->id}}">{{ $value->employee_location}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Letter upload</label>
                                <span class="rflabelsteric"><strong></strong></span>
                                <input type="file" name="letter_uploading[]" id="letter_uploading[]" class="form-control" multiple>
                            </div>
                        </div>

                        <div class="row">&nbsp;</div>
                        <div class="row" style="background-color: gainsboro">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h4  style="text-decoration: underline;font-weight: bold;">Promotion Detail</h4>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <input @if($promotionCount == 1) checked @endif type="checkbox" class="location_checking" name="location_check"  value="1">
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row" id="different-package">
                            @if($promotionCount == 1)
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                    <label class="sf-label">Designation : </label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" id="designation_id" name="designation_id">
                                        <option value="">Select Designation</option>
                                        @foreach($designation as $value)
                                            <option @if($employee_promotion->designation_id == $value->id) selected @endif value="{{ $value->id}}">{{ $value->designation_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                    <label class="sf-label">Grade :</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" id="grade_id" name="grade_id">
                                        <option value="">Select Grade</option>
                                        @foreach($employee_grades as $value)
                                            <option @if($employee_promotion->grade_id == $value->id) selected @endif value="{{ $value->id}}">{{ $value->employee_grade_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                    <label class="sf-label">Edit Salary:</label><br>
                                    <input type="checkbox" class="" id="edit_salary" name="edit_salary" value="1">
                                </div>
                                <div id="div_salary" style="display: none">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <label class="sf-label">Increment:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" name="increment" id="increment" value="0" class="form-control requiredField" onkeyup="changeSalary()" required/>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <label class="sf-label">Salary :</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input name="salary" id="salary" type="number" value="{{ $employee_promotion->salary - $employee_promotion->increment }}" class="form-control requiredField" readonly>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row" style="background-color: gainsboro">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h4 style="text-decoration: underline;font-weight: bold;">Transfer Detail</h4>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <input @if($count == 2) checked @endif type="checkbox" class="transfer_project_check" name="transfer_project_check"  value="1">
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row click_transfer_project_package">
                            @if($count == 2)
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Project :</label>
                                    <select class="form-control requiredField" id="transfer_project_id" name="transfer_project_id">
                                        <option value="">Select Transfer Project</option>
                                        @foreach($Employee_projects as $value)
                                            <option <?php if($TransferEmployeeProject->employee_project_id == $value->id){ echo 'selected';} ?> value="{{$value->id }}">{{$value->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Transfer Date :</label>
                                    <input type="date" name="transfer_date" id="transfer_date" value="<?php echo $TransferEmployeeProject->date ?>" class="form-control requiredField" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Update</button>
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
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

        $('#location_id').select2();
        $('#designation_id').select2();
        $('#grade_id').select2();
        $('#transfer_project_id').select2();
    });



    $('#edit_salary').change(function () {
        if ($(this).is(':checked')) {
            $('#div_salary').show();
        }
        else {
            $('#div_salary').hide();
        }
    });


    var previousSalary = $('#salary').val();
    function changeSalary() {

        $('#salary').val(previousSalary);
        var salary = parseFloat($('#salary').val());
        var increment = parseFloat($('#increment').val());

        $('#salary').val(salary + increment);

        if ($('#increment').val() == '')
            $('#salary').val(previousSalary);

    }
    var initialSalary = '<?php echo $initialSalary ?>';
    function changeInitialSalary() {

        $('#salary').val(initialSalary);
        var salary = parseFloat($('#salary').val());
        var increment = parseFloat($('#increment').val());

        $('#salary').val(salary + increment);

        if ($('#increment').val() == '')
            $('#salary').val(initialSalary);

    }



    $('.location_checking').click(function(){
        if($(this).is(":checked") == true)
        {
            $('#different-package').html('<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="sf-label">Designation</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span><select class="form-control requiredField" id="designation_id" name="designation_id" required><option value="">Select Designation</option>' +
                    '@foreach($designation as $value)<option value="{{ $value->id}}">{{ $value->designation_name}}</option>@endforeach</select></div>'  +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="sf-label">Grade :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span><select class="form-control requiredField" id="grade_id" name="grade_id">' +
                    '<option value="">Select Grade</option>@foreach($employee_grades as $value)' +
                    '<option value="{{ $value->id}}">{{ $value->employee_grade_type}}</option>@endforeach' +
                    '</select></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Increment:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="number" name="increment" id="increment" value="0" class="form-control requiredField" onkeyup="changeInitialSalary()" required/></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Salary:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span><input name="salary" id="salary" value="{{ $initialSalary }}" type="number" class="form-control requiredField" required readonly></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Promotion Date :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span><input type="date" name="promotion_date" id="promotion_date" value="" class="form-control"</div>');
        }
        else
        {
            $("#different-package").html('');
            $("#salary").val('');
            $("#designation_id").val('');
            $( "#designation_id" ).removeClass( "requiredField" );
            $( "#grade_id" ).removeClass( "requiredField" );
            $( "#increment" ).removeClass( "requiredField" );
            $( "#salary" ).removeClass( "requiredField");
        }
        $('#designation_id').select2();
        $('#grade_id').select2();
    });


    $('.transfer_project_check').click(function() {
        if ($(this).is(":checked") == true) {
            $('.click_transfer_project_package').html('<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label class="sf-label">Transfer Project :</label>' +
                    '<select class="form-control requiredField" id="transfer_project_id" name="transfer_project_id">' +
                    '<option value="">Select Transfer Project</option>' +
                    '@foreach($Employee_projects as $value)' +
                    '<option value="{{$value->id}}}">{{$value->project_name}}</option>@endforeach</select>' +
                    '</div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label class="sf-label">Transfer Date :</label>' +
                    '<input type="date" name="transfer_date" id="transfer_date" value="" class="form-control requiredField" /></div>');
        }
        else {
            $('.click_transfer_project_package').html('');
            $('#transfer_date').val('');
            $('#transfer_project_id').val('');
        }
        $('#transfer_project_id').select2();
    });

   </script>
