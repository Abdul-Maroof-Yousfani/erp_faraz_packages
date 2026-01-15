<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Holidays;
use App\Models\Attendance;


?>
<div class="panel">
    <div class="panel-heading">

    </div>
    <div class="panel-body">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <span class="label" style="background-color:#FFC0CB;">&nbsp;&nbsp;&nbsp;</span> = Holidays
            <span class="label" style="background-color:#FFC0CB;">&nbsp;&nbsp;&nbsp;</span> = Absent
            <span class="label" style="background-color:lightcoral;">&nbsp;&nbsp;&nbsp;</span> = Late Arrivals
            @if(in_array('delete', $operation_rights))
                <input type="button" class="btn btn-sm btn-danger" id="deleteAttendenceReport" onclick="deleteAttendanceReport()" value="Delete Attendence" style="float: right" />
            @endif
        </div>
        <br><br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12" id="PrintEmployeeAttendanceList">
                <div class="table-responsive" >
                    <table class="table table-responsive table-bordered table-condensed" id="EmployeeAttendanceList">
                        <thead>
                        <th>S.no</th>
                        <th>EMP Code</th>
                        <th>Emp Name</th>
                        <th>Attendace Date</th>
                        <th>Attendance Status</th>
                        <th>Day</th>
                        <th>Month</th>
                        <th>Year</th>

                        </thead>
                        <tbody>
                        <?php $count =1;?>
                        @foreach($attendance as $value)
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));

                            $day_off_emp =  'Sun';
                            $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['emp_code','=',$value->emp_code]]);

                            if($total_days_off->count() > 0):
                                foreach($total_days_off->get()->toArray() as $offDates):
                                    $totalOffDates[] = $offDates['attendance_date'];
                                endforeach;
                            else:
                                $totalOffDates =array();
                            endif;

                            $get_holidays = Holidays::select('holiday_date')->where([['status','=',1],['month','=',$value->month],['year','=',$value->year]]);
                            if($get_holidays->count() > 0):
                                foreach($get_holidays->get() as $value2):
                                    $monthly_holidays[]=$value2['holiday_date'];
                                endforeach;
                            else:
                                $monthly_holidays =array();
                            endif;

                            $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            <tr @if(in_array($value->attendance_date,$monthly_holidays)) style="background-color: #FFC0CB;" @endif>
                                <td>{{$count++}}</td>
                                <td>{{$value->emp_code}}</td>
                                <td>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'), 'employee', 'emp_name', $value->emp_code, 'emp_code') }}</td>
                                <td>{{ HrHelper::date_format($value->attendance_date) }}</td>
                                <td> @if($value->attendance_type == 1) Present @else Absent @endif</td>
                                <td>{{ $value->day }}</td>
                                <td>{{ $value->month }}</td>
                                <td>{{ $value->year }}</td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>

