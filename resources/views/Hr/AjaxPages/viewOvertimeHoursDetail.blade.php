<?php
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Helpers\CommonHelper;
?>

<style>
    hr{border-top: 1px solid cadetblue}

    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="well">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 class="text-center">Working Days Overtime Hours</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Clock In</th>
                                    <th class="text-center">Clock Out</th>
                                    <th class="text-center">Duration</th>
                                    <th class="text-center">Overtime Hours</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $counter = 1;
                                    $total_working_hours = strtotime('9:00:00');
                                    ?>
                                    @if($attendance->count() > 0)
                                        @foreach($attendance->get() as $val)
                                            <?php
                                            $min = 0;
                                            $hours = 0;
                                            $otMin = 0;
                                            $otHours = 0;
                                            $total_hours_worked = 0;
                                            $overtime = 0;
                                            $total_hours_worked_for_overtime = 0;
                                            $clock_in = strtotime($val->clock_in);
                                            $clock_out = strtotime($val->clock_out);

                                            if($clock_in != '' && $clock_out != ''):
                                                $total_hours_worked_for_overtime = $clock_out - $clock_in;
                                                $total_hours_worked += abs($clock_out - $clock_in);
                                                $min = $total_hours_worked/60;
                                                $hours = floor($min/60);
                                                $min = $min%60;

                                                $overtime =  abs(strtotime($hours.":".$min) - strtotime("9:00"));
                                                $otMin = $overtime/60;
                                                $otHours = floor($otMin/60);
                                                $otMin = $otMin%60;

                                            endif;
                                            ?>
                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ HrHelper::date_format($val->attendance_date) }}</td>
                                                <td class="text-center">{{ $val->day }}</td>
                                                <td class="text-center">{{ $val->clock_in }}</td>
                                                <td class="text-center">{{ $val->clock_out }}</td>
                                                <td class="text-center">{{ $hours.' : '.$min }} </td>
                                                <td class="text-center"> @if($hours >= 9) {{ $otHours.' : '.$otMin }} @elseif($hours < 9 && $hours > 0) {{ '- '.$otHours.' : '.$otMin }} @endif </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 class="text-center">Holiday Days Overtime Hours</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Clock In</th>
                                    <th class="text-center">Clock Out</th>
                                    <th class="text-center">Duration</th>
                                    <th class="text-center">Overtime Hours</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $counter = 1;
                                    $total_working_hours = strtotime('9:00:00');
                                    ?>
                                    @if($off_days_attendance->count() > 0)
                                        @foreach($off_days_attendance->get() as $val2)
                                            <?php
                                            $min = 0;
                                            $hours = 0;
                                            $otMin = 0;
                                            $otHours = 0;
                                            $total_hours_worked = 0;
                                            $overtime = 0;
                                            $total_hours_worked_for_overtime = 0;
                                            $clock_in = strtotime($val2->clock_in);
                                            $clock_out = strtotime($val2->clock_out);

                                            if($clock_in != '' && $clock_out != ''):
                                                $total_hours_worked_for_overtime = $clock_out - $clock_in;
                                                $total_hours_worked += abs($clock_out - $clock_in);
                                                $min = $total_hours_worked/60;
                                                $hours = floor($min/60);
                                                $min = $min%60;

                                                $overtime =  abs(strtotime($hours.":".$min));
                                                $otMin = $overtime/60;
                                                $otHours = floor($otMin/60);
                                                $otMin = $otMin%60;

                                            endif;
                                            ?>
                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ HrHelper::date_format($val->attendance_date) }}</td>
                                                <td class="text-center">{{ $val2->day }}</td>
                                                <td class="text-center">{{ $val2->clock_in }}</td>
                                                <td class="text-center">{{ $val2->clock_out }}</td>
                                                <td class="text-center">{{ $hours.' : '.$min }}</td>
                                                <td class="text-center">{{ $hours.' : '.$min }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
