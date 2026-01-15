<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\LoanRequest;
use App\Models\EmployeePromotion;
use App\Models\Department;

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
    <div class="panel-body" id="PrintEmployeeAttendanceList">
        <div class="row">
            <div class="">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                    <div class="table-responsive wrapper">
                        <table class="table table-responsive table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center" style="font-size: 18px;">
                                    <b>{{ HrHelper::getMasterTableValueById($m,'regions','employee_region',$region_id) }}</b>
                                </th>
                            </tr>
                            </thead>
                        </table>

                        @foreach($departments as $department)
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
                            <table class="table table-responsive table-bordered tableFixHead table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th colspan="6"></th>
                                    <th colspan="2" class="text-center">Overtime</th>
                                    <th colspan="5" class="text-center">Allowances</th>
                                    <th></th>
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
                                                <b>{{ $department->department_name }}</b>
                                            </h4>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                $query_string_second_part = [];
                                CommonHelper::companyDatabaseConnection($m);
                                if (!empty($region_id)) $query_string_second_part[] = " AND region_id = '$region_id'";
                                if (!empty($emp_code)) $query_string_second_part[] = " AND emp_code = '$emp_code'";
                                $query_string_second_part[] = " AND department_id = '$department->id'";
                                $query_string_second_part[] = " AND month = '$month_year[1]'";
                                $query_string_second_part[] = " AND year = '$month_year[0]'";
                                $query_string_second_part[] = " AND status = 1";
                                $query_string_First_Part = "SELECT * FROM payslip WHERE";
                                $query_string_third_part = ' ORDER BY emp_code';
                                $query_string_second_part = implode(" ", $query_string_second_part);
                                $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
                                $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;

                                $payslip = DB::select(DB::raw($query_string));
                                ?>
                                @foreach($payslip as $value)
                                    <?php
                                    CommonHelper::companyDatabaseConnection($m);
                                    $total_loan = 0;
                                    $total_loan_paid = 0;
                                    $remaining_loan_amount = 0;
                                    if($value->loan_id != 0):
                                        $loanRequest = LoanRequest::where([['id','=', $value->loan_id],['emp_code', '=', $value->emp_code]]);
                                        if($loanRequest->count() > 0):
                                            $loan_per_month_deduction = $loanRequest->value('per_month_deduction');
                                            $total_loan = $loanRequest->value('loan_amount');
                                            $total_loan_paid = Payslip::where([['loan_id','=',$value->loan_id],['emp_code','=',$value->emp_code]])->sum('loan_amount');
                                            $remaining_loan_amount = ($total_loan - $total_loan_paid);
                                        endif;
                                    endif;
                                    CommonHelper::reconnectMasterDatabase();
                                    ?>

                                    @if(!empty($payslip))
                                        <?php
                                        $data = 'yes';

                                        $count_gross_salary += $value->gross_salary;
                                        $count_arrears_amount += $value->arrears_amount;
                                        $count_bonus_amount += $value->bonus_amount;
                                        $count_overtime_hours_paid += $value->overtime_hours_paid;
                                        $count_overtime_amount += $value->overtime_amount;
                                        $count_lunch_allowance += $value->lunch_allowance;
                                        $count_other_allowances += $value->other_allowances;
                                        $count_other_amount += $value->other_amount;
                                        $count_total_allowance += $value->total_allowance;
                                        $count_salary_with_allowance += $value->gross_salary + $value->total_allowance;
                                        $count_late_deduction_days += $value->late_deduction_days;
                                        $count_late_deduction_amount += $value->late_deduction_amount;
                                        $count_other_deduction += $value->other_deduction;
                                        $count_loan_amount += $value->loan_amount;
                                        $count_advance_salary_amount += $value->advance_salary_amount;
                                        $count_tax_amount += $value->tax_amount;
                                        $count_eobi_amount += $value->eobi_amount;
                                        $count_total_deduction += $value->total_deduction;
                                        $count_net_salary += $value->net_salary;

                                        $grand_gross_salary += $value->gross_salary;
                                        $grand_arrears_amount += $value->arrears_amount;
                                        $grand_bonus_amount += $value->bonus_amount;
                                        $grand_overtime_hours_paid += $value->overtime_hours_paid;
                                        $grand_overtime_amount += $value->overtime_amount;
                                        $grand_lunch_allowance += $value->lunch_allowance;
                                        $grand_other_allowances += $value->other_allowances;
                                        $grand_other_amount += $value->other_amount;
                                        $grand_total_allowance += $value->total_allowance;
                                        $grand_salary_with_allowance += $value->gross_salary + $value->total_allowance;
                                        $grand_late_deduction_days += $value->late_deduction_days;
                                        $grand_late_deduction_amount += $value->late_deduction_amount;
                                        $grand_other_deduction += $value->other_deduction;
                                        $grand_loan_amount += $value->loan_amount;
                                        $grand_advance_salary_amount += $value->advance_salary_amount;
                                        $grand_tax_amount += $value->tax_amount;
                                        $grand_eobi_amount += $value->eobi_amount;
                                        $grand_total_deduction += $value->total_deduction;
                                        $grand_net_salary += $value->net_salary;

                                        ?>
                                        <tr>
                                            <td class="text-center">{{ $value->emp_code }}</td>
                                            <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emp_code,'emp_code') }}</td>
                                            <td>{{ HrHelper::date_format(HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_joining_date',$value->emp_code,'emp_code')) }}</td>
                                            <td class="text-center">{{ $value->present_days }}</td>
                                            <td class="text-center">{{ $value->absent_days }}</td>
                                            <td class="text-right">{{ number_format($value->gross_salary,0) }}</td>
                                            <td class="text-right">{{ number_format($value->arrears_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($value->bonus_amount,0) }}</td>
                                            <td class="text-center">{{ $value->overtime_hours_paid }}</td>
                                            <td class="text-right">{{ number_format($value->overtime_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($value->lunch_allowance,0) }}</td>
                                            <td class="text-right">{{ number_format($value->other_allowances,0) }}</td>
                                            <td class="text-right">{{ number_format($value->other_amount,0) }}</td>
                                            <td>{{ $value->remarks }}</td>
                                            <td class="text-right">{{ number_format($value->total_allowance,0) }}</td>
                                            <td class="text-right">{{ number_format($value->gross_salary + $value->total_allowance,0) }}</td>
                                            <td class="text-center">{{ $value->late_deduction_days }}</td>
                                            <td class="text-right">{{ number_format($value->late_deduction_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($value->other_deduction,0) }}</td>
                                            <td class="text-right">{{ number_format($value->loan_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($total_loan,0) }}</td>
                                            <td class="text-right">{{ number_format($total_loan_paid,0) }}</td>
                                            <td class="text-right">{{ number_format($remaining_loan_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($value->advance_salary_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($value->tax_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($value->eobi_amount,0) }}</td>
                                            <td class="text-right">{{ number_format($value->total_deduction,0) }}</td>
                                            <td class="text-right">{{ number_format($value->net_salary,0) }}</td>
                                            <td class="text-center">{{ HrHelper::getEmployeeBankData(Input::get('m'),'1',$value->emp_code)}}</td>
                                            <td class="text-center"></td>
                                        </tr>
                                    @else
                                        <?php
                                        $recordNotFound[] = "<tr class='text-center'><td colspan='27'><b style='color:red;'> $value->emp_name Payroll Not Found !</b></td></tr>" ;
                                        ?>
                                    @endif
                                @endforeach
                                @if($data == 'yes')
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
                                        <td class="text-right">
                                            <b>{{ number_format($count_salary_with_allowance,0) }}</b>
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
                                @endif
                                </tbody>
                            </table>
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
                            <th class="text-center">Gross Salary With Allowance</th>
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
                                <td class="text-right">
                                    <b>{{ number_format($grand_salary_with_allowance,0) }}</b>
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
    </div>
</div>
