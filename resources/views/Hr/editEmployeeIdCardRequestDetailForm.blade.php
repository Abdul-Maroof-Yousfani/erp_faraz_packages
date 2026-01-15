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

$id = $employee_card_request->id;

?>

<style>
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

    input[type="radio"],input[type="checkbox"]{ width:30px;
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
                        <?php echo Form::open(array('url' => 'had/editEmployeeIdCardRequestDetail',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                            <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                            <div class="gudia-gap">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        <div class="hr-border" style="border: 1px solid #e5e5e5b0; margin-top: 89px;"></div>
                                        <img id="img_file_1" class="img-circle" src="<?= Storage::url($employee['img_path'])?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12">
                                        <label class="sf-label">EMR No:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly name="emr_no" id="emr_no" type="text" value="{{ $employee_card_request['emr_no'] }}" class="form-control requiredField">
                                    </div>
                                    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12">
                                        <label class="sf-label">Employee Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly name="emp_name" id="emp_name" type="text" value="{{ $employee['emp_name'] }}" class="form-control requiredField">
                                    </div>
                                    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12">
                                        <label class="sf-label">Department:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly name="sub_department_name" id="sub_department_name" value="{{ $sub_department['sub_department_name'] }}"  type="text" class="form-control requiredField">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>Designation:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly name="designation_name" id="designation_name" type="text" value="{{ $designation['designation_name'] }}" class="form-control requiredField">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>CNIC:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly name="emp_cnic" id="emp_cnic" type="text" value="{{ $employee['emp_cnic'] }}" class="form-control requiredField">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>Joining Date:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly name="emp_joining_date" id="emp_joining_date" type="date" value="{{ $employee['emp_joining_date'] }}" class="form-control requiredField">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>Posted At:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input name="posted_at" id="posted_at" type="date" value="{{ $employee_card_request['posted_at'] }}" class="form-control requiredField">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>ID Card Image</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="file" name="card_image" id="card_image" type="date" value="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <b> Replacement Of Card: <input @if($employee_card_request->card_replacement == 1) checked @endif name="card_replacement" id="card_replacement" value="1" type="checkbox"> </b>
                                    </div>

                                    <span id="replacement-card" style="display: none">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <b><input @if($employee_card_request->replacement_type == 'lost') checked @endif name="replacement_type" class="replacement_type" type="radio" value="lost"> Lost </b>
                                            <b><input @if($employee_card_request->replacement_type == 'stolen') checked @endif name="replacement_type" class="replacement_type" type="radio" value="stolen"> Stolen </b>
                                            <b><input @if($employee_card_request->replacement_type == 'damaged') checked @endif name="replacement_type" class="replacement_type" type="radio" value="damaged"> Damaged </b>
                                            <b><input @if($employee_card_request->replacement_type == 'other') checked @endif name="replacement_type" class="replacement_type" type="radio" value="other"> Other </b>
                                        </div>

                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                            <div class="form-inline">
                                                <div class="form-group">
                                                    <label>Payment:</label>
                                                    <input type="number" class="form-control" id="payment" name="payment" value="{{$employee_card_request->payment}}">
                                                </div>

                                                &nbsp &nbsp &nbsp &nbsp
                                                <div class="form-group">
                                                    <label>Upload FIR Copy:</label>
                                                    <input type="file" class="form-control" name="fir_copy" id="fir_copy" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
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

    $(document).ready(function () {

        if($('#card_replacement').is(":checked") == true) {
            $("#replacement-card").show();
        }
        else {
            $("#replacement-card").hide();
        }

    });

    $('#card_replacement').click(function(){

        if($(this).is(":checked") == true) {
            $("#replacement-card").show();
        }
        else {
            $("#replacement-card").hide();
            $('#payment').val('');
            $('.replacement_type').prop('checked',false);
        }

    });


</script>
