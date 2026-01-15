<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$id = Input::get('id');

?>

<style>

    input[type="radio"], input[type="checkbox"]
    { width:30px;
        height:20px;
    }
    hr{border-top: 1px solid cadetblue}

    .img-circle {width: 150px;
        height: 150px;
        border: 2px solid #ccc;
        padding: 4px;
        border-radius: 50%;
        margin-bottom: 32px;
        margin-top: -78px;
        z-index: 10000000;}

    .name-d-d ul li {
        font-size: 17px;
        margin: 10px 0px 22px 0px;
    }

    .name-d-d-input ul li {
        margin: 7px 0px 10px 0px;
    }

    .depart-row .col-lg-3 {
        background-color: #080808;
        color: #fff;
        border-left: 1px solid #fff;
    }

    input[type="radio"]{ width:30px;
        height:20px;
    }

    .depart-row-two .col-lg-4 {
        background-color: #999;
        color: #fff;
        border-left: 1px solid #fff;
        padding: 7px 0px 2px 0px;
    }

</style>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body">
                        <form method="post" action="{{url('had/editEmployeeLocationChangeDetail')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                            <input type="hidden" name="id" id="id" value="<?= $id ?>">
                            <div class="gudia-gap">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">EMR No:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly name="emr_no" id="emr_no" type="text" value="{{ $employee_location->emr_no }}" class="form-control requiredField">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Employee Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly name="emp_name" id="emp_name" type="text" value="{{HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $employee_location->emr_no, 'emr_no') }}" class="form-control requiredField">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Location :</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" id="location_id" name="location_id" required>
                                            <option value="">Select Location</option>
                                            @foreach($location as $key5 => $value)
                                                <option @if($employee_location->location_id == $value->id) selected @endif value="{{ $value->id}}">{{ $value->employee_location}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 24px;">
                                        <b><input @if($count ==1) checked @endif type="checkbox" name="location_check" id="location_check" value="1"> &nbsp Different Package</b>
                                    </div>

                                    <div id="different-package">
                                        @if($count == 1):
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Designation</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" id="designation_id" name="designation_id">
                                                <option value="">Select Designation</option>
                                                @foreach($designation as $value)
                                                    <option @if($employee_promotion->designation_id == $value->id) selected @endif value="{{ $value->id}}">{{ $value->designation_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Salary:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input name="salary" id="salary" type="number" value="{{ $employee_promotion->salary }}" class="form-control requiredField">
                                        </div>
                                        @endif;
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div style="float: right;">
                                <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#location_check').click(function(){
        if($(this).is(":checked") == true)
        {
            $('#different-package').html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Designation</label>' +
            '<span class="rflabelsteric"><strong>*</strong></span><select class="form-control requiredField" id="designation_id" name="designation_id" required><option value="">Select Designation</option>' +
            '@foreach($designation as $value)<option value="{{ $value->id}}">{{ $value->designation_name}}</option>@endforeach</select></div>'  +
            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Salary:</label>' +
            '<span class="rflabelsteric"><strong>*</strong></span><input name="salary" id="salary" type="number" class="form-control requiredField" required></div>');
        }
        else
        {
            $("#different-package").html('');
            $("#salary").val('');
            $("#designation_id").val('');

        }
    });
</script>
