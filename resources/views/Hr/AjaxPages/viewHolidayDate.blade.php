<?php use App\Helpers\HrHelper;?>
<div class="" id="PrintLeaveApplicationRequestList">
    <div class="row">
        <h2 class="text-center">Off Days</h2>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                    <thead>
                        <th class="text-center">S No.</th>
                        <th class="text-center">Days</th>
                        <th class="text-center">Dates</th>
                        <th class="text-center">Month</th>
                        <th class="text-center">Year</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>

                    @foreach($total_days_off as $value)
                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                            <td class="text-center">{{$value->day}}</td>
                            <td class="text-center"><?php echo HrHelper::date_format($value->attendance_date);?></td>
                            <td class="text-center">{{$value->month}}</td>
                            <td class="text-center">{{$value->year}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <h2 class="text-center">Public Holidays</h2>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Holiday Name</th>
                    <th class="text-center">Holiday Dates</th>
                    <th class="text-center">Month</th>
                    <th class="text-center">Year</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>

                    @foreach($HolidayData as $value2)
                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                            <td class="text-center">{{$value2->holiday_name}}</td>
                            <td class="text-center"><?php echo HrHelper::date_format($value2->holiday_date);?></td>
                            <td class="text-center">{{$value2->month}}</td>
                            <td class="text-center">{{$value2->year}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
