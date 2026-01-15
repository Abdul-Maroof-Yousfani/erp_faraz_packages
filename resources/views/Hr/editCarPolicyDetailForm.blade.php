<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

<div class="lineHeight">&nbsp;</div>
<div class="row">
    <?php echo Form::open(array('url' => 'had/editCarPolicyDetail','id'=>'employeeForm'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="company_id" value="<?=$m?>">
    <input type="hidden" name="record_id" value="<?=Input::get('id')?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                    </div>
                </div>
                <div class="row">
                    <div class="form_area">
                        <div class="get_data">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Policy Name</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="policy_name" value="<?= $carPolicy->policy_name; ?>" class="form-control requiredField"/>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Designation:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="designation_id" class="form-control requiredField">
                                    <option value="">Select</option>
                                    @foreach($designation as $value2)
                                        <option @if($carPolicy->designation_id == $value2->id){{ 'selected' }} @endif value="{{ $value2->id }}">{{ $value2->designation_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Vehicle Type & CC:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="vehicle_type_id" class="form-control requiredField">
                                    <option value="">Select</option>
                                    @foreach($vehicleType as $value)
                                        <option @if($carPolicy->vehicle_type_id == $value->id ) {{ 'selected' }}@endif value="{{ $value->id }}">{{ $value->vehicle_type_name.'&nbsp;&nbsp;'.$value->vehicle_type_cc.'CC' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Start Salary Range</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="start_salary_range" value="<?= $carPolicy->start_salary_range; ?>" class="form-control requiredField"/>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">End Salary Range</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="end_salary_range" value="<?= $carPolicy->end_salary_range; ?>" class="form-control requiredField"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
        </div>
    </div>
    <?php echo Form::close();?>
</div>
