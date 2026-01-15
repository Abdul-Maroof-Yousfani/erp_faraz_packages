<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

?>

<div class="lineHeight">&nbsp;</div>
<?php echo Form::open(array('url' => 'had/editDegreeTypeDetail','id'=>'EOBIform'));?>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
<input type="hidden" name="recordId" value="{{ $degree_type->id }}">
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="EOBISection[]" class="form-control" id="sectionEOBI" value="1" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Degree Type Name:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="degree_type_name" id="degree_type_name" value="{{ $degree_type->degree_type_name }}" class="form-control requiredField" />
            </div>

        </div>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>
<div class="EOBISection"></div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
    </div>
</div>
<?php echo Form::close();?>
