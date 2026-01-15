<?php
$id = Input::get('id');
$m 	= Input::get('m');

?>
<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }

</style>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <?php echo Form::open(array('url' => 'had/editEmployeeAttendanceDetail?m='.$m.'','id'=>'editEmployeeAttendanceDetail'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="m" value="<?=$m;?>">
                    <input type="hidden" name="recordId" value="<?=$id;?>">
                    <input type="hidden" name="attendance_type" value="<?=$attendanceDetail[0]['attendance_type'];?>">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="attendanceSection[]" class="form-control" id="attendanceSection" value="1" />
                                </div>
                            </div>
                            @if($attendanceDetail[0]['attendance_type'] == 1)
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Date From</label>
                                    <input type="date" class="form-control requiredField" name="date_from" id="date_from" required value="<?=$attendanceDetail[0]['attendance_from']?>">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Date To</label>
                                    <input type="date" class="form-control requiredField" name="date_to" id="date_to" required value="<?=$attendanceDetail[0]['attendance_to']?>">
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="sf-label">Present Days:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="number" name="present_days" id="present_days" value="<?=$attendanceDetail[0]['present_days']?>" class="form-control requiredField" required />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="sf-label">Absent Days:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="number" name="absent_days" id="absent_days" value="<?=$attendanceDetail[0]['absent_days']?>" class="form-control requiredField" required />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="sf-label">Overtime:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="number" name="overtime" id="overtime" value="<?=$attendanceDetail[0]['overtime']?>" class="form-control requiredField" placeholder="Overtime in hours" required />
                                </div>
                            @elseif($attendanceDetail[0]['attendance_type'] == 2)

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Date:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="date" name="attendance_date" id="attendance_date" value="<?=$attendanceDetail[0]['attendance_date']?>" class="form-control requiredField" required />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Clock In:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="time" name="clock_in" id="clock_in" value="<?=$attendanceDetail[0]['clock_in']?>" class="form-control requiredField" required />
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Clock Out:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="time" name="clock_out" id="clock_out" value="<?=$attendanceDetail[0]['clock_out']?>" class="form-control requiredField" required />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Attendance Status:</label><span class="rflabelsteric"><strong>*</strong></span><br>
                                    <b><input @if($attendanceDetail[0]['attendance_status'] == '1') checked @endif type="radio" name="attendance_status" id="attendance_status" onclick="attendanceStatus(this.value)" value="1">Present</b> &nbsp;
                                    <b><input @if($attendanceDetail[0]['attendance_status'] == '2') checked @endif type="radio" name="attendance_status" id="attendance_status" onclick="attendanceStatus(this.value)" value="2">Absent</b>
                                </div>
                            @endif
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
<script type="text/javascript">
    $(".btn-success").click(function(e){
        var attendance = new Array();
        var val;
        $("input[name='attendanceSection[]']").each(function(){
            attendance.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val of attendance) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });

    function attendanceStatus(status){
        if(status == 2){
            $("#clock_in").prop("disabled", true);
            $("#clock_out").prop("disabled", true);
        }else{
            $("#clock_in").prop("disabled", false);
            $("#clock_out").prop("disabled", false);
        }
    }
</script>
