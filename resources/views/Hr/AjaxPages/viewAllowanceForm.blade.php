<?php

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;

use App\Models\Allowance;

use App\Models\ArrearsAllowance;
use App\Models\BlueMonitoringAllowance;
use App\Models\BonusPtbAllocation;
use App\Models\ConveyanceAllowance;
use App\Models\EidCoverageAllowance;
use App\Models\NightShiftAllowance;
use App\Models\OtFixedAllowance;
use App\Models\OtHoursAllowance;
use App\Models\OtherPerformanceBonus;
use App\Models\ReferralBonusAllowance;
use App\Models\WorkingDays;
use App\Models\FuelAllowance;
use App\Models\EmployeePromotion;
use App\Models\ApiEmployeeData;
use App\Models\ApiEmployeeShifts;
use App\Models\ConveyanceRate;
use App\Models\EomBravoRate;
$counter = 1;
?>

<style>

    input[type="radio"], input[type="checkbox"]{ width:20px;
        height:20px;
    }

    td{ padding: 2px !important;}
    th{ padding: 2px !important;}

</style>
<div class="panel">
    <div class="panel-body" id="PrintAllowance">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php echo CommonHelper::headerPrintSectionInPrintView($m)?>
            </div>
            <div class="">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive tableFixHead">
                        <table class="table table-bordered sf-table-list table-hover" id="AllowanceList">
                            <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Emp Code</th>
                                <th class="text-center">Emp Name</th>
                                <th class="text-center">Allowance Amount</th>
                                <th class="text-center">Status</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($employees as $row1)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $allowance = Allowance::where([['emp_code', '=', $row1->emp_code],['allowance_type', '=', $allowance_type]]);
                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                @if($allowance->count() > 0)
                                    <?php $allowance = $allowance->first() ?>
                                    <tr>
                                        <td class="text-center">{{ $counter++ }}</td>
                                        <td class="text-center">{{ $row1->emp_code }}
                                            <input name="emp_code[]" type="hidden" value="{{ $row1->emp_code }}">
                                        </td>
                                        <td>{{ $row1->emp_name }}</td>
                                        <td class="text-center">
                                            <input type="number" step='any' class="form-control" name="allowance_amount_{{$row1->emp_code}}" value="{{ $allowance->allowance_amount }}">
                                        </td>
                                        <td class="text-center" style="color: green">Submitted</td>
                                    </tr>

                                @else

                                    <tr>
                                        <td class="text-center">{{ $counter++ }}</td>
                                        <td class="text-center">{{ $row1->emp_code }}
                                            <input name="emp_code[]" type="hidden" value="{{ $row1->emp_code }}">
                                        </td>
                                        <td>{{ $row1->emp_name }}</td>
                                        <td class="text-center">
                                            <input type="number" step='any' class="form-control" name="allowance_amount_{{$row1->emp_code}}" value="">
                                        </td>
                                        <td class="text-center">-</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">&nbsp</div>
            <div class="row">&nbsp</div>
            <div class="row text-right" style="margin-right: 10px">
                <input type="submit" name="submit" class="btn btn-success" />
            </div>
        </div>
    </div>`
</div>





