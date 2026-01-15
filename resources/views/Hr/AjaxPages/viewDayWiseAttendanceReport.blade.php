<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Holidays;
use App\Models\Attendance;
?>
<div class="panel">
    <div class="panel-body">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <span class="label" style="background-color:#FFC0CB;">&nbsp;&nbsp;&nbsp;</span>&nbsp; Holidays &nbsp;&nbsp;
            <span class="label" style="background-color:#d6d6d6;">&nbsp;&nbsp;&nbsp;</span>&nbsp; Absent &nbsp;&nbsp;
            <span class="label" style="background-color:#ea9696;">&nbsp;&nbsp;&nbsp;</span>&nbsp; Late Arrivals &nbsp;&nbsp;
            @if(in_array('delete', $operation_rights2))
                <!--<input type="button" class="btn btn-sm btn-danger" id="deleteAttendenceReport" onclick="deleteAttendanceReport()" value="Delete Attendence" style="float: right" />-->
            @endif
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12" id="PrintEmployeeAttendanceList">
                <div class="table-responsive" >
                    <table class="table table-responsive table-bordered table-condensed table-hover" id="EmployeeAttendanceList">
                        <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Emp Code</th>
                            <th class="text-center">Emp Name</th>
                            <th class="text-center">Attendance Date</th>
                            <th class="text-center">Day</th>
                            <th class="text-center">Clock In</th>
                            <th class="text-center">Clock Out</th>
                            <th class="text-center">Attendance Status</th>
                            <th class="text-center">Action</th>
                        </thead>
                        <tbody>
                        <?php
                        $count = 1;
                        $grace_time = strtotime('10:00:00');
                        $late = 0;
                        ?>
                        @if($attendance_check->count() > 0)
                            @if($attendance_check->value('attendance_type') == 2)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $attendance = Attendance::where([['emp_code', '=', $emp_code], ['year', '=', $month_year[0]], ['month', '=', $month_year[1]]])
                                        ->orderBy('attendance_date', 'asc')->get();
                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                @foreach($attendance as $value)

                                    <?php
                                    //CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    //$day_off_emp = 'Sun';
                                    //$total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['emp_code','=',$value->emp_code]]);

                                    //if($total_days_off->count() > 0):
                                    //    foreach($total_days_off->get()->toArray() as $offDates):
                                    //        $totalOffDates[] = $offDates['attendance_date'];
                                    //    endforeach;
                                    //else:
                                    //    $totalOffDates =array();
                                    //endif;

                                    //$get_holidays = Holidays::select('holiday_date')->where([['status','=',1],['month','=',$value->month],['year','=',$value->year]]);
                                    //if($get_holidays->count() > 0):
                                    //    foreach($get_holidays->get() as $value2):
                                    //        $monthly_holidays[]=$value2['holiday_date'];
                                    //    endforeach;
                                    //else:
                                    //    $monthly_holidays =array();
                                    //endif;
                                    //$monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
                                    //CommonHelper::reconnectMasterDatabase();

                                    if($value->attendance_status == 1):
                                        $clock_in = strtotime($value->clock_in);
                                        $late = (($clock_in - $grace_time) / 60);
                                    endif;
                                    ?>
                                    <tr @if($value->attendance_status == 2) style="background-color: #d6d6d6;" @elseif($late > 0) style="background-color: #ea9696;" @endif>
                                        <td class="text-center">{{ $count++ }}</td>
                                        <td class="text-center">{{ $value->emp_code }}</td>
                                        <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'), 'employee', 'emp_name', $value->emp_code, 'emp_code') }}</td>
                                        <td class="text-center">{{ HrHelper::date_format($value->attendance_date) }}</td>
                                        <td class="text-center">{{ $value->day }}</td>
                                        <td class="text-center">{{ $value->clock_in }}</td>
                                        <td class="text-center">{{ $value->clock_out }}</td>
                                        <td class="text-center"> @if($value->attendance_status == 1) Present @else Absent @endif</td>
                                        <td class="text-center">
                                            @if(in_array('edit', $operation_rights2))
                                                <button class="btn btn-primary btn-xs" onclick="showDetailModelTwoParamerter('hr/editEmployeeAttendanceDetailForm','<?php echo $value->id;?>','Edit Employee Attendance Detail','<?php echo Input::get('m'); ?>')">Edit</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td class="text-center" style="color: red" colspan="9">Day Wise Attendance Not Found !</td></tr>
                            @endif
                        @else
                            <tr><td class="text-center" style="color: red" colspan="9">No record Found !</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>

