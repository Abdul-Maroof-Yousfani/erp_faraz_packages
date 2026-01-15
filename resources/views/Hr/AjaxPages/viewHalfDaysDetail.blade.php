<?php
use App\Helpers\HrHelper;
?>
<div class="" id="PrintLeaveApplicationRequestList">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Late Arrival Date</th>
                    <th class="text-center">Day</th>
                    <th class="text-center" style="background-color: #FFC0CB;">Check In</th>
                    <th class="text-center">Check Out</th>
                    <th class="text-center" style="background-color: #FFC0CB;">Late</th>
                    <th class="text-center">Count</th>


                    </thead>
                    <tbody>
                    <?php $counter = 1;?>

                    @foreach($total_halfDay as $value)
                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                            <td class="text-center"><?php echo HrHelper::date_format($value->attendance_date);?></td>
                            <td class="text-center">{{$value->day}}</td>
                            <td class="text-center" style="background-color: #FFC0CB;">{{$value->clock_in}}</td>
                            <td class="text-center">{{$value->clock_out}}</td>
                            <td class="text-center" style="background-color: #FFC0CB;">{{$value->late}}</td>
                            <td class="text-center" style="color:red;">
                                <?php
                                if($value->late >= '04:00'):
                                    echo "Absent";
                                elseif($value->late >= '02:00' && $value->late < '04:00'):
                                    echo "Half Day";
                                endif;
                                ?>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
