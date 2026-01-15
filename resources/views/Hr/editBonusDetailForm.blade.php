<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>
<div class="well">
    <div class="row">
        <?php echo Form::open(array('url' => 'had/editBonusDetail','id'=>'EOBIform'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
        <input type="hidden" name="recordId" value="<?php echo Input::get('id')?>">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Bonus Name:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" name="Bonus_name" id="Bonus_name" value="{{$bonus->bonus_name}}" class="form-control requiredField" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>% of Salary:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="number" name="percent_of_salary" id="percent_of_salary" value="{{$bonus->percent_of_salary}}" class="form-control requiredField" />
                    </div>
                </div>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>
