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
                                        <td class="text-center col-sm-1">{{ $allowance->emp_code }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Employee Name</th>
                                        <td class="text-center col-sm-1">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee', 'emp_name', $allowance->emp_code, 'emp_code') }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Allowance Type</th>
                                        <td class="text-center col-sm-1">{{ HrHelper::getMasterTableValueById($m,'allowance_type','allowance_type',$allowance->allowance_type)}}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Amount</th>
                                        <td class="text-center col-sm-1">{{ number_format($allowance->allowance_amount,0) }}</td>
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


