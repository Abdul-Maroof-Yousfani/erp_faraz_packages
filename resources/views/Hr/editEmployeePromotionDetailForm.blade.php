<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
?>

<style>
    hr{border-top: 1px solid cadetblue}

    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
</style>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body">
                            <?php echo Form::open(array('url' => 'had/editEmployeePromotionDetail',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="{{ $m }}">
                            <input type="hidden" name="id" id="id" value="{{ $employee_promotion->id }}">
                            <div class="gudia-gap">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Emp Code:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly name="emp_code" id="emp_code" type="text" value="{{ $employee_promotion->emp_code }}" class="form-control requiredField">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Employee Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly name="emp_name" id="emp_name" type="text" value="{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $employee_promotion->emp_code, 'emp_code') }}" class="form-control requiredField">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Designation:</label>
                                        <select class="form-control" id="designation_id" name="designation_id">
                                            <option value="">Select</option>
                                            @foreach($designation as $value)
                                                <option @if($employee_promotion->designation_id == $value->id) selected @endif value="{{ $value->id}}">{{ $value->designation_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>Promotion Letter</label>
                                        <span class="rflabelsteric"><strong></strong></span>
                                        <input type="file" name="promotion_letter[]" id="promotion_letter" class="form-control" multiple>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Edit Salary:</label><br>
                                        <input type="checkbox" class="" id="edit_salary" name="edit_salary" value="1">
                                    </div>
                                    <div id="div_salary" style="display: none">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Increment:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" name="increment" id="increment" value="0" class="form-control requiredField" onkeyup="changeSalary()" required/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Salary:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input name="salary" id="salary" type="number" value="{{ $employee_promotion->salary - $employee_promotion->increment }}" class="form-control requiredField" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div style="float: right;">
                                <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

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


    $(document).ready(function () {
        $('#designation_id').select2();
    });
</script>

<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

