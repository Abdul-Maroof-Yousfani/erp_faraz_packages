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
                                        <th class="text-center col-sm-1">Emp Code</th>
                                        <td class="text-center col-sm-1">{{ $advance_salary->emp_code }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Employee Name</th>
                                        <td class="text-center col-sm-1">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee', 'emp_name', $advance_salary->emp_code, 'emp_code') }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Deduction Month-Year</th>
                                        <td class="text-center col-sm-1">{{ date('M - Y',strtotime($advance_salary->deduction_year.'-'.$advance_salary->deduction_month)) }}</td>
                                        <thead>
                                        <th class="text-center col-sm-1">Amount</th>
                                        <td class="text-center col-sm-1">{{ $advance_salary->advance_salary_amount }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Advance Salary to be Needed on</th>
                                        <td class="text-center col-sm-1" style="color: red;">{{ HrHelper::date_format($advance_salary->salary_needed_on) }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Reason (Detail)</th>
                                        <td class="text-center">{{ $advance_salary->detail }}</td>
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


