<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');

?>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body" id="PrintLoanRequestList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list" id="LoanRequestList">
                                        <thead>
                                        <th class="text-center col-sm-1">Holiday</th>
                                        <td class="text-center col-sm-1">{{ $holidays->holiday_name }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Holiday Date</th>
                                        <td class="text-center col-sm-1">{{ HrHelper::date_format($holidays->holiday_date) }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Month</th>
                                        <td class="text-center col-sm-1">{{ $holidays->month }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Year</th>
                                        <td class="text-center col-sm-1">{{ $holidays->year }}</td>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


