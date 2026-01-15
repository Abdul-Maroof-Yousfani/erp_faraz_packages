<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Change Labour Salary Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <?php echo Form::open(array('url' => 'had/addLabourSalaryDetail','id'=>'LanourSalary'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?= $m ?>">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label>Labour Salary:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="number" name="labour_salary" id="labour_salary" value="" class="form-control requiredField" required />
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
                </div>
            </div>
        </div>
    </div>

@endsection