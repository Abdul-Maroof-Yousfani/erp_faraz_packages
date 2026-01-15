<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Attendance;
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="DiseaseList">
                        <thead>
                        <th class="text-center col-sm-1">S.No</th>
                        <th class="text-center">Emp Code</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Clock In</th>
                        <th class="text-center">Clock Out</th>
                        <th class="text-center">Attendance Status</th>
                        <th class="text-center">Remarks</th>
                        </thead>
                        <tbody>
                        <?php $counter = 1;?>
                        @foreach($employees as $key => $val)
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $attendance = Attendance::where([['emp_code', '=', $val->emp_code],['attendance_date', '=', $attendance_date],['status', '=', 1]]);
                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            @if($attendance->count() > 0)
                                <?php
                                if($attendance->value('attendance_status') == 2):
                                    $disabled = 'disabled';
                                    $requiredField = '';
                                else:
                                    $disabled = '';
                                    $requiredField = 'requiredField';
                                endif;
                                ?>

                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ $val->emp_code }}</td>
                                    <td class="">{{ $val->emp_name }}</td>
                                    <td class="text-center">
                                        <input type="time" @if($attendance->value('attendance_status') == 2) {{ $disabled }} @endif name="clock_in_{{ $val->emp_code }}" id="clock_in_{{ $val->emp_code }}" value="{{ $attendance->value('clock_in') }}" class="form-control {{ $requiredField }}" />
                                    </td>
                                    <td class="text-center">
                                        <input type="time" @if($attendance->value('attendance_status') == 2) {{ $disabled }} @endif name="clock_out_{{ $val->emp_code }}" id="clock_out_{{ $val->emp_code }}" value="{{ $attendance->value('clock_out') }}" class="form-control" />
                                    </td>
                                    <td class="text-center">
                                        <b><input type="radio" @if($attendance->value('attendance_status') == 1) checked @endif name="attendance_status_{{ $val->emp_code }}" id="present_{{ $val->emp_code }}" value="1" onclick="attendanceStatus(this.value, '{{ $val->emp_code }}')">
                                            <label for="present_{{ $val->emp_code }}">Present</label></b> &nbsp;
                                        <b><input type="radio" @if($attendance->value('attendance_status') == 2) checked @endif name="attendance_status_{{ $val->emp_code }}" id="absent_{{ $val->emp_code }}" value="2" onclick="attendanceStatus(this.value, '{{ $val->emp_code }}')">
                                            <label for="absent_{{ $val->emp_code }}">Absent</label></b>
                                    </td>
                                    <td class="text-center" style="color: green">Submitted</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ $val->emp_code }}</td>
                                    <td class="">{{ $val->emp_name }}</td>
                                    <td class="text-center">
                                        <input type="time" name="clock_in_{{ $val->emp_code }}" id="clock_in_{{ $val->emp_code }}" value="09:30" class="form-control requiredField" />
                                    </td>
                                    <td class="text-center">
                                        <input type="time" name="clock_out_{{ $val->emp_code }}" id="clock_out_{{ $val->emp_code }}" value="" class="form-control" />
                                    </td>
                                    <td class="text-center">
                                        <b><input type="radio" name="attendance_status_{{ $val->emp_code }}" id="present_{{ $val->emp_code }}" value="1" checked onclick="attendanceStatus(this.value, '{{ $val->emp_code }}')">
                                            <label for="present_{{ $val->emp_code }}">Present</label></b> &nbsp;
                                        <b><input type="radio" name="attendance_status_{{ $val->emp_code }}" id="absent_{{ $val->emp_code }}" value="2" onclick="attendanceStatus(this.value, '{{ $val->emp_code }}')">
                                            <label for="absent_{{ $val->emp_code }}">Absent</label></b>
                                    </td>
                                    <td class="text-center"></td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
            </div>
        </div>
    </div>
</div>

<script>

    function attendanceStatus(status, id){
        if(status == 2){
            $('#clock_in_'+id).removeClass('requiredField');
            $('#clock_out_'+id).removeClass('requiredField');
            $('#clock_in_'+id).prop("disabled", true);
            $('#clock_out_'+id).prop("disabled", true);
            $('#clock_in_'+id).val('');
            $('#clock_out_'+id).val('');
        }else{
            $('#clock_in_'+id).prop("disabled", false);
            $('#clock_in_'+id).addClass('requiredField');
            $('#clock_out_'+id).prop("disabled", false);
            $('#clock_in_'+id).val('09:30');
            // $('#clock_out').addClass('requiredField');
        }
    }
</script>