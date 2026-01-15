<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\EmployeeCategory;
use App\Models\Regions;
use App\Models\EmployeePromotion;
use App\Models\LoanRequest;

$grand_gross_salary = 0;
$grand_arrears_amount = 0;
$grand_bonus_amount = 0;
$grand_overtime_hours_paid = 0;
$grand_overtime_amount = 0;
$grand_lunch_allowance = 0;
$grand_other_allowances = 0;
$grand_other_amount = 0;
$grand_total_allowance = 0;
$grand_salary_with_allowance = 0;
$grand_late_deduction_days = 0;
$grand_late_deduction_amount = 0;
$grand_other_deduction = 0;
$grand_loan_amount = 0;
$grand_advance_salary_amount = 0;
$grand_tax_amount = 0;
$grand_eobi_amount = 0;
$grand_total_deduction = 0;
$grand_net_salary = 0;
$count = 1;
$data = '';

?>
<style>
    /*fix head css*/
    div.wrapper {
        overflow:auto;
        height:80%;
    }

    .tableFixHead {
        overflow: auto;
        height: 100px;
        width:300px;
    }

    .tableFixHead table {
        border-collapse: collapse;
        width: 100%;
    }

    .tableFixHead th,
    .tableFixHead td {
        padding: 8px 16px;
    }

    td:first-child, th:first-child {
        position:sticky;
        left:0;
        z-index:1;
        background:#f9f9f9;
    }
    td:nth-child(2),th:nth-child(2)  {
        position:sticky;
        left:56px;
        z-index:1;
        background:#f9f9f9;
    }
    .tableFixHead th {
        position: sticky;
        top: 0;
        background: #f9f9f9;
        z-index:2
    }
    th:first-child , th:nth-child(2) {
        z-index:3
    }

</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @foreach($companiesList as $companyData)
                    <?php echo CommonHelper::headerPrintSectionInPrintView($companyData->id);?>
                    <div class="table-responsive wrapper">
                        <?php
                        $count =1;
                        $regions = Regions::select('employee_region','id')->where([['status','=',1],['company_id','=',$companyData->id]])->get()->toArray();
                        CommonHelper::companyDatabaseConnection($companyData->id);
                        ?>
                        @foreach($regions as $regionsValue)
                            <?php
                                $count_gross_salary = 0;
                                $count_arrears_amount = 0;
                                $count_bonus_amount = 0;
                                $count_overtime_hours_paid = 0;
                                $count_overtime_amount = 0;
                                $count_lunch_allowance = 0;
                                $count_other_allowances = 0;
                                $count_other_amount = 0;
                                $count_total_allowance = 0;
                                $count_salary_with_allowance = 0;
                                $count_late_deduction_days = 0;
                                $count_late_deduction_amount = 0;
                                $count_other_deduction = 0;
                                $count_loan_amount = 0;
                                $count_advance_salary_amount = 0;
                                $count_tax_amount = 0;
                                $count_eobi_amount = 0;
                                $count_total_deduction = 0;
                                $count_net_salary = 0;
                            ?>
                            <table class="table table-responsive table-bordered tableFixHead table-hover" id="regionWisePayrollReport">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th colspan="6"></th>
                                    <th colspan="2" class="text-center">Overtime</th>
                                    <th colspan="5" class="text-center">Allowances</th>
                                    <th class="text-center"></th>
                                    <th colspan="11" class="text-center">Deductions</th>
                                    <th colspan="3"></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Emp Code</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Joining Date</th>
                                    <th class="text-center">Present Days</th>
                                    <th class="text-center">Absent Days</th>
                                    <th class="text-center">Gross Salary</th>
                                    <th class="text-center">Arrears</th>
                                    <th class="text-center">Bonus</th>
                                    <th class="text-center">O.T hours</th>
                                    <th class="text-center">O.T Amount</th>
                                    <th class="text-center">Lunch</th>
                                    <th class="text-center">Other</th>
                                    <th class="text-center">Other Amount</th>
                                    <th class="text-center">Remarks</th>
                                    <th class="text-center">Total Allowance</th>
                                    <th class="text-center">Gross With Allowance</th>
                                    <th class="text-center">Late Deduction Days</th>
                                    <th class="text-center">Deduction Amount</th>
                                    <th class="text-center">Other</th>
                                    <th class="text-center">IOU</th>
                                    <th class="text-center">Total IOU</th>
                                    <th class="text-center">IOU Paid</th>
                                    <th class="text-center">IOU Remaining</th>
                                    <th class="text-center">Adv Salary</th>
                                    <th class="text-center">Tax</th>
                                    <th class="text-center">EOBI</th>
                                    <th class="text-center">Total Deduction</th>
                                    <th class="text-center">Net Salary</th>
                                    <th class="text-center">Bank Acc. No</th>
                                    <th class="text-center">Signature</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr style="background-color: #ddd;" >
                                    <td colspan="30">
                                        <div class="row text-center">
                                            <h4>
                                                <b>{{ HrHelper::getMasterTableValueById($companyData->id,'regions','employee_region',$regionsValue["id"]) }}</b>
                                            </h4>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                                CommonHelper::companyDatabaseConnection($companyData->id);
                                $employees = Employee::select("designation_id","emp_cnic","emp_code","emp_salary","eobi_id","emp_name","emp_date_of_birth")
                                        ->where([["region_id","=",$regionsValue["id"]]])->orderBy('emp_code')
                                        ->get();
                                CommonHelper::reconnectMasterDatabase();
                                ?>

                                @foreach($employees as $value)
                                    <?php

                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $payslip = Payslip::where([['emp_code','=',$value->emp_code],["month","=",$month_year[1]],["year","=",$month_year[0]]]);

                                    $total_loan = 0;
                                    $total_loan_paid = 0;
                                    $remaining_loan_amount = 0;
                                    if($payslip->value('loan_id') != 0):
                                        $loanRequest = LoanRequest::where([['id','=', $payslip->value('loan_id')],['emp_code', '=', $value->emp_code]]);
                                        if($loanRequest->count() > 0):
                                            $loan_per_month_deduction = $loanRequest->value('per_month_deduction');
                                            $total_loan = $loanRequest->value('loan_amount');
                                            $total_loan_paid = Payslip::where([['loan_id','=',$payslip->value('loan_id')],['emp_code','=',$value->emp_code]])->sum('loan_amount');
                                            $remaining_loan_amount = ($total_loan - $total_loan_paid);
                                        endif;
                                    endif;
                                    CommonHelper::reconnectMasterDatabase();
                                    ?>
                                    @if($payslip->count() > 0)
                                        <?php
                                        $data = 'yes';

                                        $count_gross_salary += $value->gross_salary;
                                        $count_arrears_amount += $payslip->value('arrears_amount');
                                        $count_bonus_amount += $payslip->value('bonus_amount');
                                        $count_overtime_hours_paid += $payslip->value('overtime_hours_paid');
                                        $count_overtime_amount += $payslip->value('overtime_amount');
                                        $count_lunch_allowance += $payslip->value('lunch_allowance');
                                        $count_other_allowances += $payslip->value('other_allowances');
                                        $count_other_amount += $payslip->value('other_amount');
                                        $count_total_allowance += $payslip->value('total_allowance');
                                        $count_salary_with_allowance += $value->gross_salary + $value->total_allowance;
                                        $count_late_deduction_days += $payslip->value('late_deduction_days');
                                        $count_late_deduction_amount += $payslip->value('late_deduction_amount');
                                        $count_other_deduction += $payslip->value('other_deduction');
                                        $count_loan_amount += $payslip->value('loan_amount');
                                        $count_advance_salary_amount += $payslip->value('advance_salary_amount');
                                        $count_tax_amount += $payslip->value('tax_amount');
                                        $count_eobi_amount += $payslip->value('eobi_amount');
                                        $count_total_deduction += $payslip->value('total_deduction');
                                        $count_net_salary += $payslip->value('net_salary');

                                        $grand_gross_salary += $value->gross_salary;
                                        $grand_arrears_amount += $payslip->value('arrears_amount');
                                        $grand_bonus_amount += $payslip->value('bonus_amount');
                                        $grand_overtime_hours_paid += $payslip->value('overtime_hours_paid');
                                        $grand_overtime_amount += $payslip->value('overtime_amount');
                                        $grand_lunch_allowance += $payslip->value('lunch_allowance');
                                        $grand_other_allowances += $payslip->value('other_allowances');
                                        $grand_other_amount += $payslip->value('other_amount');
                                        $grand_total_allowance += $payslip->value('total_allowance');
                                        $grand_salary_with_allowance += $value->gross_salary + $value->total_allowance;
                                        $grand_late_deduction_days += $payslip->value('late_deduction_days');
                                        $grand_late_deduction_amount += $payslip->value('late_deduction_amount');
                                        $grand_other_deduction += $payslip->value('other_deduction');
                                        $grand_loan_amount += $payslip->value('loan_amount');
                                        $grand_advance_salary_amount += $payslip->value('advance_salary_amount');
                                        $grand_tax_amount += $payslip->value('tax_amount');
                                        $grand_eobi_amount += $payslip->value('eobi_amount');
                                        $grand_total_deduction += $payslip->value('total_deduction');
                                        $grand_net_salary += $payslip->value('net_salary');

                                        ?>

                                        <tr>
                                            <td class="text-center">{{ $value->emp_code }}</td>
                                            <td class="text-center">{{ $value->emp_name }}</td>
                                            <td class="text-center">{{ HrHelper::date_format($value->emp_joining_date) }}</td>
                                            <td class="text-center">{{ $payslip->value('present_days') }}</td>
                                            <td class="text-center">{{ $payslip->value('absent_days') }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('emp_salary'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('arrears_amount'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('bonus_amount'),0) }}</td>
                                            <td class="text-center">{{ $payslip->value('overtime_hours_paid') }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('overtime_amount'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('lunch_allowance'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('other_allowances'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('other_amount'),0) }}</td>
                                            <td>{{ $payslip->value('remarks') }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('total_allowance'),0) }}</td>
                                            <td class="text-right">{{ number_format($value->gross_salary + $value->total_allowance,0) }}</td>
                                            <td class="text-center">{{ $payslip->value('late_deduction_days') }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('late_deduction_amount'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('other_deduction'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('loan_amount'),0) }}</td>
                                            <td class="text-right">{{ number_format($total_loan,0) }}</td>
                                            <td class="text-right">{{ number_format($total_loan_paid,0) }}</td>
                                            <td class="text-right">{{ number_format($remaining_loan_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('advance_salary_amount'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('tax_amount'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('eobi_amount'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('total_deduction'),0) }}</td>
                                            <td class="text-right">{{ number_format($payslip->value('net_salary'),0) }}</td>
                                            <td class="text-center">{{ HrHelper::getEmployeeBankData(Input::get('m'),'1',$value->emp_code)}}</td>
                                            <td class="text-center"></td>
                                        </tr>
                                    @else
                                        <?php
                                        $recordNotFound[] = "<tr class='text-center'><td colspan='27'><b style='color:red;'> $value->emp_name Payroll Not Found !</b></td></tr>" ;
                                        ?>
                                    @endif
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_gross_salary,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_arrears_amount,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_bonus_amount,0) }}</b>
                                    </td>
                                    <td class="text-center">
                                        <b>{{ $count_overtime_hours_paid }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_overtime_amount,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_lunch_allowance,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_other_allowances,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_other_amount,0) }}</b>
                                    </td>
                                    <td></td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_total_allowance,0) }}</b>
                                    </td>
                                    <td class="text-center">
                                        <b>{{ $count_late_deduction_days }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_late_deduction_amount,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_other_deduction,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_loan_amount,0) }}</b>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_advance_salary_amount,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_tax_amount,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_eobi_amount,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_total_deduction,0) }}</b>
                                    </td>
                                    <td class="text-right">
                                        <b>{{ number_format($count_net_salary,0) }}</b>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php CommonHelper::reconnectMasterDatabase(); ?>
                                </tbody>
                            </table>
                        @endforeach
                    </div>

                @endforeach
                <table class="table table-responsive table-bordered">
                    <thead>
                    <th class="text-center" colspan="18"><b style="font-size:30px;text-decoration: underline">Grand Total</b></th>
                    </thead>
                    <thead>
                    <th class="text-center">Gross Salary</th>
                    <th class="text-center">Arrears</th>
                    <th class="text-center">Bonus</th>
                    <th class="text-center">O.T hours</th>
                    <th class="text-center">O.T Amount</th>
                    <th class="text-center">Lunch</th>
                    <th class="text-center">Other</th>
                    <th class="text-center">Other Amount</th>
                    <th class="text-center">Total Allowance</th>
                    <th class="text-center">Late Deduction Days</th>
                    <th class="text-center">Deduction Amount</th>
                    <th class="text-center">Other</th>
                    <th class="text-center">IOU</th>
                    <th class="text-center">Adv Salary</th>
                    <th class="text-center">Tax</th>
                    <th class="text-center">EOBI</th>
                    <th class="text-center">Total Deduction</th>
                    <th class="text-center">Net Salary</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-right">
                            <b>{{ number_format($grand_gross_salary,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_arrears_amount,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_bonus_amount,0) }}</b>
                        </td>
                        <td class="text-center">
                            <b>{{ $grand_overtime_hours_paid }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_overtime_amount,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_lunch_allowance,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_other_allowances,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_other_amount,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_total_allowance,0) }}</b>
                        </td>
                        <td class="text-center">
                            <b>{{ $grand_late_deduction_days }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_late_deduction_amount,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_other_deduction,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_loan_amount,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_advance_salary_amount,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_tax_amount,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_eobi_amount,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_total_deduction,0) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_net_salary,0) }}</b>
                        </td>
                    <tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

