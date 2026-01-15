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
                                <?php echo Form::open(array('url' => 'had/editVehicleTypeDetail','id'=>'employeeForm'));?>
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
                                                <span class="form_area">
                                                <span class="get_data">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Vehicle Type:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" name="vehicle_type_name" id="vehicle_type_name" value="<?= $vehicleType->vehicle_type_name; ?>" class="form-control requiredField" required />

                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Vehicle CC:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="number" name="vehicle_type_cc" id="vehicle_type_cc" value="<?= $vehicleType->vehicle_type_cc; ?>" class="form-control requiredField" required />
                                                </div>
                                                </span>
                                                </span>
                                                </span>
                                            </div>
                                            <div class="employeeSection"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>
                                <?php echo Form::close();?>
                            </div>

