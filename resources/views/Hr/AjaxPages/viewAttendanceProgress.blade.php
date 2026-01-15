<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
$current_date = date('Y-m-d');
$count = 0;
$data = 'no';


?>
<style>
    .pointer:hover {
        cursor: pointer;
    }
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
</style>

<div class="panel">
    <div class="panel-body">
        {{ CommonHelper::headerPrintSectionInPrintView(Input::get('m')) }}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                {{ Form::open(array('url' => 'had/addAttendanceProgressDetail','id'=>'addAttendanceProgressDetail')) }}
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                    <input type="hidden" name="m" value="{{ Input::get('m') }}" />
                </div>
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered table-striped table-condensed table-hover">
                        <thead>
                        <th class="text-center">S.no</th>
                        <th class="text-center">Emp Code.</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Working Days</th>
                        <th class="text-center">Present Days</th>
                        <th class="text-center">Absent Days</th>
                        <th class="text-center">Net OT Hours</th>
                        <th class="text-center">OT Hours (Paid)</th>
                        <th class="text-center">Late Arrival</th>
                        <th class="text-center">Late Deduction Days</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Action</th>
                        </thead>
                        <tbody>
                        @foreach($employees as $value)
                            <?php
                            $present_days = 0;
                            $absent_days = 0;
                            $overtime_hours = 0;
                            $total_hours_worked = 0;
                            $total_late = 0;
                            $deduction_days = 0;
                            $grace_time = strtotime('10:00:00');
                            $count++;
                            $late = 0;
                            $leave_dates_array = [];
                            $leaves_from_dates2 = [];
                            $monthly_holidays = [];
                            $monthly_holidays_absents = [];
                            $emp_name = HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'), 'employee', 'emp_name', $value->emp_code, 'emp_code');

                            $total_month_days = cal_days_in_month(CAL_GREGORIAN, $month_year[1], $month_year[0]);
                            $total_working_hours = $total_month_days * 9;

                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $payrollData = PayrollData::where([['emp_code','=',$value->emp_code],['month','=',$month_year[1]],['year','=',$month_year[0]]]);
                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            @if($payrollData->count() > 0 )
                                <?php
                                $disabled = 'disabled';
                                $data = 'yes';
                                ?>

                                <tr>
                                    <td class="text-center">{{ $count }}</td>
                                    <td class="text-center">{{ $payrollData->value('emp_code') }}
                                        <input type="hidden" name="emp_code[]" value="{{ $payrollData->value('emp_code') }}">
                                    </td>
                                    <td class="text-center">{{ $emp_name }}</td>
                                    <td class="text-center">{{ $total_month_days }}
                                        <input type="hidden" name="working_days_{{$payrollData->value('emp_code')}}" id="working_days_{{$payrollData->value('emp_code')}}" value="{{ $total_month_days }}">
                                    </td>
                                    <td class="text-center">{{ $payrollData->value('present_days') }}
                                        <input type="hidden" name="present_days_{{$payrollData->value('emp_code')}}" id="present_days_{{$payrollData->value('emp_code')}}" value="{{ $payrollData->value('present_days') }}">
                                    </td>
                                    <td class="text-center pointer" onclick="showDetailModelTwoParamerter('hdc/viewAbsentAndLeaveApplicationDetail','{{ $value->emp_code.','.$month_year[0].','.$month_year[1] }}','View Absent And Leave Application Detail','{{ Input::get('m') }}')">
                                        {{ $payrollData->value('absent_days') }}
                                        <input type="hidden" name="absent_days_{{$payrollData->value('emp_code')}}" id="absent_days_{{$payrollData->value('emp_code')}}" value="{{ $payrollData->value('absent_days') }}">
                                    </td>
                                    <td class="text-center pointer" onclick="showDetailModelTwoParamerter('hdc/viewOvertimeHoursDetail','{{ $value->emp_code.','.$month_year[0].','.$month_year[1] }}','View Overtime Hours Detail','{{ Input::get('m') }}')">
                                        {{ $payrollData->value('net_overtime_hours') }}
                                        <input type="hidden" name="net_overtime_hours_{{$payrollData->value('emp_code')}}" id="net_overtime_hours_{{$payrollData->value('emp_code')}}" value="{{ $payrollData->value('net_overtime_hours') }}">
                                    </td>
                                    <td class="text-center">
                                        <input {{ $disabled }} type="number" step="any" class="form-control" name="overtime_hours_paid_{{$payrollData->value('emp_code')}}" id="overtime_hours_paid_{{$payrollData->value('emp_code')}}" value="{{ $payrollData->value('overtime_hours_paid') }}">
                                    </td>
                                    <td class="text-center pointer" onclick="showDetailModelTwoParamerter('hdc/viewLateArrivalDetail','{{ $value->emp_code.','.$month_year[0].','.$month_year[1] }}','View Late Arrival Days Detail','{{ Input::get('m') }}')">
                                        {{ $payrollData->value('late_arrival') }}
                                        <input type="hidden" name="late_arrival_{{$payrollData->value('emp_code')}}" id="late_arrival_{{$payrollData->value('emp_code')}}" value="{{ $payrollData->value('late_arrival') }}">
                                    </td>
                                    <td class="text-center">
                                        <input {{ $disabled }} type="number" step="any" class="form-control" name="late_deduction_days_{{$payrollData->value('emp_code')}}" id="late_deduction_days_{{$payrollData->value('emp_code')}}" value="{{ $payrollData->value('late_deduction_days') }}">
                                    </td>
                                    <td class="text-center" style="color: green">Submitted
                                        <input type="hidden" name="attendance_type_{{$payrollData->value('emp_code')}}" value="{{ $payrollData->value('attendance_type') }}">
                                        <input type="hidden" name="year_{{$payrollData->value('emp_code')}}" value="{{ $month_year[0] }}">
                                        <input type="hidden" name="month_{{$payrollData->value('emp_code')}}" value="{{ $month_year[1] }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="check_{{$payrollData->value('emp_code')}}" class="check" onclick="progressCheck('{{$payrollData->value('emp_code')}}')" value="1">
                                    </td>
                                </tr>

                            @else
                                <?php

                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $attendance = Attendance::where([['emp_code', '=', $value->emp_code],
                                        ['month', '=', $month_year[1]],['year','=', $month_year[0]],['status', '=', 1]]);
                                CommonHelper::reconnectMasterDatabase();
                                $disabled = '';
                                ?>
                                @if($attendance->count() > 0)
                                    <?php $data = 'yes'; ?>
                                    @if($attendance->value('attendance_type') == 2)
                                        <?php
                                        $LikeDate = "'".'%'.$month_year[0]."-".$month_year[1].'%'."'";

                                        $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date,leave_application_data.no_of_days from leave_application
                                            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                                            WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_code = '.$value->emp_code.'
                                            AND leave_application.status = 1 AND leave_application.approval_status = 2 AND leave_application.approval_status_m = 2 AND leave_application.view = "yes"
                                            OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.emp_code = '.$value->emp_code.'');

                                        if(!empty($leave_application_request_list)):
                                            foreach($leave_application_request_list as $value3):
                                                $leaves_from_dates = $value3->from_date;
                                                $leaves_to_dates = $value3->to_date;
                                                $leaves_from_dates2[] = $value3->from_date;
                                                $leaves_no_days[] = $value3->no_of_days;

                                                $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));

                                                foreach ($period as $date):
                                                    $leave_dates_array[] = $date->format("Y-m-d");
                                                endforeach;
                                            endforeach;
                                        endif;

                                        CommonHelper::companyDatabaseConnection(Input::get('m'));

                                        $holidays = Holidays::select('holiday_date')->where([['month', '=', $month_year[1]],['year','=', $month_year[0]],['status','=',1]]);
                                        if($holidays->count() > 0):
                                            foreach($holidays->get() as $value2):
                                                $monthly_holidays[] = $value2['holiday_date'];
                                            endforeach;
                                        else:
                                            $monthly_holidays =array();
                                        endif;

                                        $monthly_holidays_absents = array_merge($monthly_holidays,$leave_dates_array);

                                        $attendance2 = Attendance::where([['emp_code', '=', $value->emp_code],
                                                ['month', '=', $month_year[1]],['year','=', $month_year[0]],['status', '=', 1]])
                                                ->whereNotIn('attendance_date', $monthly_holidays_absents);

                                        CommonHelper::reconnectMasterDatabase();
                                        ?>
                                        @foreach($attendance2->get() as $val)

                                            <?php
                                            $late = 0;
                                            $clock_in = 0;
                                            $clock_out = 0;

                                            if($val->attendance_status == 1):
                                                $present_days++;
                                            elseif($val->attendance_status == 2):
                                                $absent_days++;
                                            endif;

                                            if($val->attendance_status == 1):
                                                $clock_in = strtotime($val->clock_in);
                                                $clock_out = strtotime($val->clock_out);

                                                if($clock_in != '' && $clock_out != ''):
                                                    $total_hours_worked += round(abs($clock_out - $clock_in) / 3600,1);
                                                endif;

                                                if($val->neglect_attendance == 'no'):
                                                    $late = (($clock_in - $grace_time) / 60);
                                                    if($late > 0):
                                                        $total_late++;
                                                        if($total_late >= 3):
                                                            $deduction_days++;
                                                        endif;
                                                    endif;
                                                endif;
                                            endif;
                                            ?>
                                        @endforeach
                                        <?php
                                        if($value->emp_department_id == '1' || $value->emp_department_id == '4'):
                                            $overtime_hours = $total_hours_worked - $total_working_hours;
                                            if($overtime_hours <= 0):
                                                $overtime_hours = 0;
                                            endif;
                                        else:
                                            $overtime_hours = 0;
                                        endif;
                                        ?>

                                        <tr>
                                            <td class="text-center">{{ $count }}</td>
                                            <td class="text-center">{{ $value->emp_code }}
                                                <input type="hidden" name="emp_code[]" value="<?=$value->emp_code?>">
                                            </td>
                                            <td class="text-center">{{ $emp_name }}</td>
                                            <td class="text-center">{{ $total_month_days }}
                                                <input type="hidden" name="working_days_<?=$value->emp_code?>" id="working_days_<?=$value->emp_code?>" value="{{ $total_month_days }}">
                                            </td>
                                            <td class="text-center">{{ $present_days }}
                                                <input type="hidden" name="present_days_<?=$value->emp_code?>" id="present_days_<?=$value->emp_code?>" value="{{ $present_days }}">
                                            </td>
                                            <td class="text-center pointer" onclick="showDetailModelTwoParamerter('hdc/viewAbsentAndLeaveApplicationDetail','{{ $value->emp_code.','.$month_year[0].','.$month_year[1] }}','View Absent And Leave Application Detail','{{ Input::get('m') }}')">
                                                {{ $absent_days }}
                                                <input type="hidden" name="absent_days_<?=$value->emp_code?>" id="absent_days_<?=$value->emp_code?>" value="{{ $absent_days }}">
                                            </td>
                                            <td class="text-center pointer" onclick="showDetailModelTwoParamerter('hdc/viewOvertimeHoursDetail','{{ $value->emp_code.','.$month_year[0].','.$month_year[1] }}','View Overtime Hours Detail','{{ Input::get('m') }}')">
                                                {{ $overtime_hours }}
                                                <input type="hidden" name="net_overtime_hours_<?=$value->emp_code?>" id="net_overtime_hours_<?=$value->emp_code?>" value="{{ $overtime_hours }}">
                                            </td>
                                            <td class="text-center">
                                                <input {{ $disabled }} type="number" step="any" class="form-control" name="overtime_hours_paid_<?=$value->emp_code?>" id="overtime_hours_paid_<?=$value->emp_code?>" value="{{ $overtime_hours }}">
                                            </td>
                                            <td class="text-center pointer" onclick="showDetailModelTwoParamerter('hdc/viewLateArrivalDetail','{{ $value->emp_code.','.$month_year[0].','.$month_year[1] }}','View Late Arrival Days Detail','{{ Input::get('m') }}')">
                                                {{ $total_late }}
                                                <input type="hidden" name="late_arrival_<?=$value->emp_code?>" id="late_arrival_<?=$value->emp_code?>" value="{{ $total_late }}">
                                            </td>
                                            <td class="text-center">
                                                <input {{ $disabled }} type="number" step="any" class="form-control" name="late_deduction_days_<?=$value->emp_code?>" id="late_deduction_days_<?=$value->emp_code?>" value="{{ $deduction_days }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" name="attendance_type_<?=$value->emp_code?>" value="{{ $attendance->value('attendance_type') }}">
                                                <input type="hidden" name="year_<?=$value->emp_code?>" value="{{ $month_year[0] }}">
                                                <input type="hidden" name="month_<?=$value->emp_code?>" value="{{ $month_year[1] }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" name="check_<?=$value->emp_code?>" class="check" onclick="progressCheck('<?=$value->emp_code?>')" value="1">
                                            </td>
                                        </tr>
                                    @elseif($attendance->value('attendance_type') == 1)
                                        <tr>
                                            <td class="text-center">{{ $count }}</td>
                                            <td class="text-center">{{ $value->emp_code }}
                                                <input type="hidden" name="emp_code[]" value="<?=$value->emp_code?>">
                                            </td>
                                            <td class="text-center">{{ $emp_name }}</td>
                                            <td class="text-center">{{ $total_month_days }}
                                                <input type="hidden" name="working_days_<?=$value->emp_code?>" id="working_days_<?=$value->emp_code?>" value="{{ $total_month_days }}">
                                            </td>
                                            <td class="text-center">{{ $attendance->value('present_days') }}
                                                <input type="hidden" name="present_days_<?=$value->emp_code?>" id="present_days_<?=$value->emp_code?>" value="{{ $attendance->value('present_days') }}">
                                            </td>
                                            <td class="text-center">{{ $attendance->value('absent_days') }}
                                                <input type="hidden" name="absent_days_<?=$value->emp_code?>" id="absent_days_<?=$value->emp_code?>" value="{{ $attendance->value('absent_days') }}">
                                            </td>
                                            <td class="text-center">{{ $overtime_hours }}
                                                <input type="hidden" name="net_overtime_hours_<?=$value->emp_code?>" id="net_overtime_hours_<?=$value->emp_code?>" value="{{ $overtime_hours }}">
                                            </td>
                                            <td class="text-center">
                                                <input {{ $disabled }} type="number" step="any" class="form-control" name="overtime_hours_paid_<?=$value->emp_code?>" id="overtime_hours_paid_<?=$value->emp_code?>" value="{{ $overtime_hours }}">
                                            </td>
                                            <td class="text-center">{{ $total_late }}
                                                <input type="hidden" name="late_arrival_<?=$value->emp_code?>" id="late_arrival_<?=$value->emp_code?>" value="{{ $total_late }}">
                                            </td>
                                            <td class="text-center">
                                                <input {{ $disabled }} type="number" step="any" class="form-control" name="late_deduction_days_<?=$value->emp_code?>" id="late_deduction_days_<?=$value->emp_code?>" value="{{ $deduction_days }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" name="attendance_type_<?=$value->emp_code?>" value="{{ $attendance->value('attendance_type') }}">
                                                <input type="hidden" name="year_<?=$value->emp_code?>" value="{{ $month_year[0] }}">
                                                <input type="hidden" name="month_<?=$value->emp_code?>" value="{{ $month_year[1] }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" name="check_<?=$value->emp_code?>" class="check" onclick="progressCheck('<?=$value->emp_code?>')" value="1">
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endif
                        @endforeach
                        @if($data == 'no')
                            <tr><td class="text-center" style="color: red" colspan="12">No Record Found !</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                @if($data != 'no')
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                        </div>
                        {{ Form::close() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='employeeSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of employee) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });

    });

    function progressCheck (id){
        $('#overtime_hours_paid_'+id).prop('disabled', function(i, v) { return !v; });
        $('#late_deduction_days_'+id).prop('disabled', function(i, v) { return !v; });

    }

</script>
