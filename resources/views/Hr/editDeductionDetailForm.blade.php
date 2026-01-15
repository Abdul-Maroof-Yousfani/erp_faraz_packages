<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');

?>

<div class="well">
    <div class="row">
        <?php echo Form::open(array('url' => 'had/editDeductionDetail','id'=>'employeeForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?=$m?>">
        <input type="hidden" name="deductionId" class="form-control" id="deductionId" value="<?= $deduction->id;?>" />
        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Deduction Type:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="deduction_type" id="deduction_type" class="form-control requiredField" value="<?= $deduction->deduction_type; ?>" required />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Deduction Amount:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="number" name="deduction_amount" id="deduction_amount" value="<?= $deduction->deduction_amount; ?>" class="form-control requiredField" required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                </div>
            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>
