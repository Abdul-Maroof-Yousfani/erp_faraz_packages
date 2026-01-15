<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

@extends('layouts.default')
@section('content')
    <script src="{{ URL::asset('assets/js/popper.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/summernote-bs4.css') }}">
    <script type="text/javascript" src="{{ URL::asset('assets/js/summernote-bs4.js') }}"></script>

    <script>
        $(function() {
            $('.summernote').summernote({
                height: 200
            });

        });
    </script>

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Working Hours Policy Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addWorkingHoursPolicyDetail?m='.$m.'','id'=>'addWorkingHoursPolicyDetail'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=$m?>">
                            <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                            <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="workingHoursSection[]" class="form-control" id="workingHoursSection" value="1" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                <label class="sf-label">Working Hours Policy:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="working_hours_policy" class="form-control requiredField" id="working_hours_policy" required />
                                            </div>
                                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Start Working Hours Time</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="time" name="start_working_hours_time" class="form-control requiredField" id="start_working_hours_time" required min="8:30" max="17:00" value="08:30 AM"/>
                                                        <span class="hours">Office hours: 8:30 AM to 5:00 PM</span>

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">End Working Hours Time</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="time" name="end_working_hours_time" class="form-control requiredField" id="end_working_hours_time" required min="8:30" max="17:00" value="08:30 AM"/>
                                                        <span class="hours">Office hours: 8:30 AM to 5:00 PM</span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Short Leave Time</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="time" name="short_leave_time" class="form-control requiredField" id="short_leave_time" required min="0"/>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Hald Day Time</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="time" name="half_day_time" class="form-control requiredField" id="half_day_time" required min="8:30" max="17:00" value="08:30 AM"/>
                                                {{--<span class="hours">Office hours: 8:30 AM to 5:00 PM</span>--}}
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Working Hours Grace Time</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="working_hours_grace_time" class="form-control requiredField" id="working_hours_grace_time" required min="0"/>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">End Time for Comming Deduct Half Day</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="time" name="end_time_for_comming_deduct_half_day" class="form-control requiredField" id="end_time_for_comming_deduct_half_day" required min="8:30" max="17:00" value="08:30 AM"/>
                                                <span class="hours">Office hours: 8:30 AM to 5:00 PM</span>
                                            </div>
                                        </div>
                                        <div class="row">&nbsp;
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Terms & Conditions:</label>
                                                <textarea name="terms_conditions" style="resize: none;" class="summernote" id="contents" title="Contents"></textarea>
                                            </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".btn-success").click(function(e){
            var workingHoursSection = new Array();
            var val;
            $("input[name='workingHoursSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of workingHoursSection) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }
        });
    </script>


@endsection