<style>

    .field_width {
        width: 100px;
        height: 30px;
    }

    table { font-size: 13px; }

    div.wrapper {
        overflow:auto;
        max-height:80%;
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
        background: #f9f9f9;
    }
    td:nth-child(2),th:nth-child(2)  {
        position:sticky;
        left:50px;
        z-index:1;
        background: #f9f9f9;
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
    .show_fuel {
        display: none;;
    }

</style>
<?php

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payroll;
use App\Models\Tax;
use App\Models\Eobi;
use App\Models\BonusIssue;
use App\Models\Bonus;
use App\Models\PayrollData;
use App\Models\LoanRequest;
use App\Models\AdvanceSalary;
use App\Models\EmployeePromotion;
use App\Models\EmployeeBankData;
use App\Models\Arrears;
use App\Models\EmployeeTransfer;

$employeeArray = [];
$recordNotFound = [];

?>
@include('select2')
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <input type="hidden" name="abc" value="<?php print_r(1)  ?>">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive wrapper">
                    <table class="table table-bordered sf-table-list table-hover tableFixHead" id="TaxesList">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th colspan="6"></th>
                            <th colspan="2" class="text-center">Overtime</th>
                            <th colspan="5" class="text-center">Allowances</th>
                            <th colspan="8" class="text-center">Deductions</th>
                            <th colspan="4"></th>
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
                            <th class="text-center">Late Deduction Days</th>
                            <th class="text-center">Deduction Amount</th>
                            <th class="text-center">Other</th>
                            <th class="text-center">IOU</th>
                            <th class="text-center">Adv Salary</th>
                            <th class="text-center">Tax</th>
                            <th class="text-center">EOBI</th>
                            <th class="text-center">Total Deduction</th>
                            <th class="text-center">Net Salary</th>
                            <th class="text-center">Emp Bank Acc No</th>
                            <th class="text-center">Account Head</th>
                            <th class="text-center">Arrears</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $row1)

                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));

                            $designation_id = $row1->designation_id;
                            $employeeCurrentPositions = EmployeePromotion::where([['emp_code','=',$row1->emp_code],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
                            if($employeeCurrentPositions->count() > 0):
                                $designation_id = $employeeCurrentPositions->value('designation_id');
                            endif;

                            $emp_name = $row1->emp_name;
                            $joining_date = $row1->emp_joining_date;
                            $department_id = $row1->emp_department_id;
                            $region_id = $row1->region_id;
                            $payroll_data = PayrollData::where([['emp_code', '=', $row1->emp_code],['year', '=', $month_year[0]],['month', '=', $month_year[1]]]);
                            ?>
                            @if($payroll_data->count() > 0)

                                <?php
                                $employeeArray[] = $row1->id;
                                $working_days = $payroll_data->value('working_days');
                                $present_days = $payroll_data->value('present_days');
                                $absent_days = $payroll_data->value('absent_days');
                                $net_overtime_hours = $payroll_data->value('net_overtime_hours');
                                $overtime_hours_paid = $payroll_data->value('overtime_hours_paid');
                                $late_arrival = $payroll_data->value('late_arrival');
                                $late_deduction_days = $payroll_data->value('late_deduction_days');

                                $arrears = Arrears::select("id","arrears_amount")->where([['emp_code','=',$row1->emp_code],['status','=',1]]);
                                $arrears_amount = 0;
                                $arrears_id = 0;
                                if($arrears->count() > 0):
                                    $arrears_amount = $arrears->value("arrears_amount");
                                    $arrears_id = $arrears->value("id");
                                else:
                                    $arrears_amount = 0;
                                    $arrears_id = 0;
                                endif;

                                $loanRequest = LoanRequest::select('id','per_month_deduction')->where([['approval_status','=',2],['loan_status','=',0],['emp_code', '=', $row1->emp_code]]);
                                $loan_per_month_deduction = 0;
                                $loan_id = 0;
                                if($loanRequest->count() > 0):
                                    $loan_per_month_deduction = $loanRequest->value('per_month_deduction');
                                    $loan_id = $loanRequest->value('id');
                                else:
                                    $loan_per_month_deduction = 0;
                                    $loan_id = 0;
                                endif;

                                $advanceSalary = AdvanceSalary::where([['approval_status','=',2],['advance_salary_status','=', 0],['emp_code', '=', $row1->emp_code]]);
                                $advance_salary_amount = 0;
                                $advance_salary_id = 0;
                                if($advanceSalary->count() > 0):
                                    $advance_salary_amount = $advanceSalary->value("advance_salary_amount");
                                    $advance_salary_id = $advanceSalary->value("id");
                                else:
                                    $advance_salary_amount = 0;
                                    $advance_salary_id = 0;
                                endif;

                                $bonus = BonusIssue::where([['status', '=', 1],['emp_code', '=', $row1->emp_code],['bonus_year', '=', $month_year[0]],['bonus_month', '=', $month_year[1]]]);
                                $bonus_amount = 0;
                                if($bonus->count() > 0):
                                    $bonus_issue = $bonus->first();
                                    $bonus_name = Bonus::select('bonus_name')->where([['id', '=', $bonus_issue->bonus_id]])->value('bonus_name');
                                    $bonus_amount = $bonus_issue->bonus_amount;
                                endif;

                                $lunch = 0;
                                $other_allowances = 0;
                                $lunchAllowance = Allowance::where([['emp_code', '=', $row1->emp_code],['status', '=', 1],['allowance_type', '=', 3]]);
                                $allowance = Allowance::where([['emp_code', '=', $row1->emp_code],['status', '=', 1],['allowance_type', '!=', 3]]);

                                if($lunchAllowance->count() > 0):
                                    $lunch = $lunchAllowance->value('allowance_amount') * $present_days;
                                else:
                                    $lunch = 0;
                                endif;

                                if($allowance->count() > 0):
                                    $other_allowances = $allowance->sum('allowance_amount');
                                else:
                                    $other_allowances = 0;
                                endif;

                                $other_deduction = 0;
                                $deduction = Deduction::where([['emp_code', '=', $row1->emp_code],['status', '=', 1]]);
                                if($deduction->count() > 0):
                                    $other_deduction = $deduction->sum('deduction_amount');
                                else:
                                    $other_deduction = 0;
                                endif;

                                $promoted_salary = EmployeePromotion::select('salary','emp_code')->where([['emp_code','=',$row1->emp_code],['status','=',1],['approval_status','=',2]])->orderBy('id', 'desc');
                                if($promoted_salary->count() > 0):
                                    $emp_salary = $promoted_salary->value('salary');
                                else:
                                    $emp_salary = $row1->emp_salary;
                                endif;

                                $gross_salary = $emp_salary;

                                $joining_date = array();
                                $joining_date2 = $row1->emp_joining_date;
                                $joining_date = explode('-', $row1->emp_joining_date);
                                if($joining_date[0] == $month_year[0] && $joining_date[1] == $month_year[1]):
                                    $salary_days = ($working_days - $joining_date[2]) + 1;
                                    $gross_salary = ($gross_salary / $working_days) * $salary_days;
                                endif;

                                $bankAccDetail = EmployeeBankData::where([['status','=',1],['emp_code','=',$row1->emp_code]]);
                                if($bankAccDetail->count() > 0):
                                    $bank_acc_no =  $bankAccDetail->value('account_no');
                                else:
                                    $bank_acc_no = '-';
                                endif;

                                CommonHelper::reconnectMasterDatabase();

                                if($row1->eobi_id != '0'):
                                    $eobi = Eobi::where([['id','=',$row1->eobi_id],['company_id','=',Input::get('m')],['status','=','1']])->first();
                                    $eobi_deduct = $eobi->EOBI_amount;
                                else:
                                    $eobi_deduct = 0;
                                endif;

                                $per_day_salary = ($emp_salary/$working_days);
                                $per_hour_salary = ($per_day_salary/9);
                                $overtime_amount = round($per_hour_salary * $overtime_hours_paid);

                                $late_deduction_amount = round($late_deduction_days * $per_day_salary);

                                $total_allowances = $lunch + $overtime_amount + $other_allowances;
                                $taxable_salary = $gross_salary + $total_allowances + $bonus_amount;
                                $tax_deduct = 0;
                                $tax_deduct = HrHelper::getIncomeTax($taxable_salary);
                                $total_deduction = $late_deduction_amount + $other_deduction + $loan_per_month_deduction + $advance_salary_amount + $eobi_deduct + $tax_deduct;
                                $net_salary = round(($emp_salary + $total_allowances + $arrears_amount + $bonus_amount) - $total_deduction);

                                ?>
                                <tr>
                                    <td class="text-center"> {{ $row1->emp_code }}
                                        <input name="id[]" type="hidden" value="{{ $row1->id }}">
                                        <input name="emp_code_{{$row1->id}}" type="hidden" value="{{  $row1->emp_code }}">
                                        <input name="designation_id_{{$row1->id}}" type="hidden" value="{{ $designation_id }}">
                                        <input name="department_id_{{$row1->id}}" type="hidden" value="{{ $department_id }}">
                                        <input name="region_id_{{$row1->id}}" type="hidden" value="{{ $region_id }}">
                                        <input name="working_days_{{$row1->id}}" type="hidden" value="{{ $working_days }}">
                                        <input name="emp_salary_{{$row1->id}}" type="hidden" value="{{ $emp_salary }}">
                                    </td>
                                    <td>{{ $emp_name }}</td>
                                    <td class="text-center">{{ HrHelper::date_format($joining_date2) }}</td>
                                    <td class="text-center"> {{ $present_days }}
                                        <input type="hidden" id="present_days_{{ $row1->id }}" name="present_days_{{ $row1->id }}" value="{{ $present_days }}" readonly="" />
                                    </td>
                                    <td class="text-center"> {{ $absent_days }}
                                        <input type="hidden" id="absent_days_{{ $row1->id }}" name="absent_days_{{ $row1->id }}" value="{{ $absent_days }}" readonly="" />
                                    </td>
                                    <td class="text-right"> {{ number_format($gross_salary,0) }}
                                        <input name="gross_salary_{{$row1->id}}" type="hidden" value="{{ $gross_salary }}">
                                    </td>
                                    <td class="text-right"> {{ number_format($arrears_amount,0) }}
                                        <input type="hidden" id="arrears_id_{{ $row1->id }}" name="arrears_id_{{ $row1->id }}" value="{{ $arrears_id }}" />
                                        <input type="hidden" id="arrears_amount_{{ $row1->id }}" name="arrears_amount_{{ $row1->id }}" value="{{ $arrears_amount }}" />
                                    </td>
                                    <td class="text-right"> {{ number_format($bonus_amount,0) }}
                                        <input type="hidden" id="bonus_amount_{{ $row1->id }}" name="bonus_amount_{{ $row1->id }}" value="{{ $bonus_amount }}" />
                                    </td>
                                    <td class="text-center">{{ $overtime_hours_paid }}
                                        <input type="hidden" id="overtime_hours_paid_{{ $row1->id }}" name="overtime_hours_paid_{{ $row1->id }}" value="{{ $overtime_hours_paid }}">
                                    </td>
                                    <td class="text-right">{{ number_format($overtime_amount,0) }}
                                        <input type="hidden" id="overtime_amount_{{ $row1->id }}" name="overtime_amount_{{ $row1->id }}" value="{{ $overtime_amount }}">
                                    </td>
                                    <td class="text-right"> {{ number_format($lunch,0) }}
                                        <input type="hidden" id="lunch_allowance_{{ $row1->id }}" name="lunch_allowance_{{ $row1->id }}" value="{{ $lunch }}" />
                                    </td>
                                    <td class="text-right"> {{ number_format($other_allowances,0) }}
                                        <input type="hidden" id="other_allowances_{{ $row1->id }}" name="other_allowances_{{ $row1->id }}" value="{{ $other_allowances }}" />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" onkeyup="payrollCalculation('{{ $row1->id }}','{{ $net_salary }}','{{ $loan_per_month_deduction }}','{{ $advance_salary_amount }}')" id="other_amount_{{ $row1->id }}" name="other_amount_{{ $row1->id }}" class="form-control field_width">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" id="remarks_{{ $row1->id }}" name="remarks_{{ $row1->id }}" value="" class="form-control field_width">
                                    </td>
                                    <td class="text-right">
                                        <span class="total_allowance2_{{ $row1->id }}">{{ number_format($total_allowances,0) }}</span>
                                        <input type="hidden" id="total_allowance_{{ $row1->id }}" name="total_allowance_{{ $row1->id }}" value="{{ $total_allowances }}" class="total_allowance2_{{ $row1->id }}">
                                        <input type="hidden" id="hidden_allowance_{{ $row1->id }}" name="hidden_allowance_{{ $row1->id }}" value="{{ $allowance->sum('allowance_amount') + $lunch + $overtime_amount }}" />
                                    </td>
                                    <td class="text-center">{{ $late_deduction_days }}
                                        <input type="hidden" id="late_deduction_days_{{ $row1->id }}" name="late_deduction_days_{{ $row1->id }}" value="{{ $late_deduction_days }}">
                                    </td>
                                    <td class="text-right">{{ number_format($late_deduction_amount,0) }}
                                        <input type="hidden" id="late_deduction_amount_{{ $row1->id }}" name="late_deduction_amount_{{ $row1->id }}" value="{{ $late_deduction_amount }}">
                                    </td>
                                    <td class="text-right">{{ number_format($other_deduction,0) }}
                                        <input type="hidden" id="other_deduction_{{ $row1->id }}" name="other_deduction_{{ $row1->id }}" value="{{ $other_deduction }}">
                                    </td>
                                    <td class="text-right">
                                        <input type="hidden" id="loan_id_{{ $row1->id }}" name="loan_id_{{ $row1->id }}" value="{{ $loan_id }}" />
                                        @if($loanRequest->count() > 0)
                                            <input onkeyup="payrollCalculation('{{ $row1->id }}','{{ $net_salary }}','{{ $loan_per_month_deduction }}','{{ $advance_salary_amount }}')" type="number" id="loan_amount_{{ $row1->id }}"
                                                   name="loan_amount_{{ $row1->id }}" value="{{ $loan_per_month_deduction }}" class="form-control field_width" />
                                        @else
                                            {{ $loan_per_month_deduction }}
                                            <input type="hidden" id="loan_amount_{{ $row1->id }}" name="loan_amount_{{ $row1->id }}" value="{{ $loan_per_month_deduction }}" />
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <input type="hidden" id="advance_salary_id_{{ $row1->id }}" name="advance_salary_id_{{ $row1->id }}" value="{{ $advance_salary_id }}" />
                                        @if($advanceSalary->count() > 0)
                                            <input onkeyup="payrollCalculation('{{ $row1->id }}','{{ $net_salary }}','{{ $loan_per_month_deduction }}','{{ $advance_salary_amount }}')" type="number" id="advance_salary_amount_{{ $row1->id }}"
                                                   name="advance_salary_amount_{{ $row1->id }}" value="{{ $advance_salary_amount }}" class="form-control field_width" />
                                        @else
                                            {{ $advance_salary_amount }}
                                            <input type="hidden" id="advance_salary_amount_{{ $row1->id }}" name="advance_salary_amount_{{ $row1->id }}" value="{{ $advance_salary_amount }}" />
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($tax_deduct,0) }}
                                        <input type="hidden" name="tax_amount_{{ $row1->id }}" id="tax_amount_{{ $row1->id }}" value="{{  $tax_deduct }}" />
                                    </td>
                                    <td class="text-right"> {{ number_format($eobi_deduct,0) }}
                                        <input type="hidden" id="eobi_amount_{{ $row1->id }}" name="eobi_amount_{{ $row1->id }}" value="{{ $eobi_deduct }}" />
                                    </td>
                                    <td class="text-right">
                                        <span class="total_deduction2_{{ $row1->id }}">{{ number_format($total_deduction,0) }}</span>
                                        <input type="hidden" id="total_deduction_{{ $row1->id }}" name="total_deduction_{{ $row1->id }}" value="{{ $total_deduction }}" class="total_deduction2_{{ $row1->id }}">
                                        <input type="hidden" id="hidden_deduction_{{ $row1->id }}" name="hidden_deduction_{{ $row1->id }}" value="{{ $late_deduction_amount + $other_deduction + $eobi_deduct + $tax_deduct }}" />
                                    </td>
                                    <td class="text-center">
                                        <span class="net_salary2_{{ $row1->id }}"> {{ number_format($net_salary,0) }}</span>
                                        <input type="hidden" id="net_salary_{{ $row1->id }}" name="net_salary_{{ $row1->id }}" value="{{ $net_salary }}" class="net_salary2_{{ $row1->id }}">
                                    </td>
                                    <td class="text-center">
                                        {{ $bank_acc_no }}
                                    </td>
                                    <td>
                                        <select name="account_id_{{ $row1->id }}" id="account_id_{{ $row1->id }}" class="form-control" style="width: 140px;">
                                            <option value="">Select Account</option>
                                            @foreach($account as $key => $val)
                                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="arrears_check_{{ $row1->id  }}" style="transform: scale(2);">
                                    </td>
                                </tr>
                                <script>
                                    $('#account_id_'+'{{ $row1->id }}').select2();
                                </script>

                            @else
                                <?php $recordNotFound[] = $row1->emp_name.' Month  = '.$month_year[1].' Year = '.$month_year[0] ?>
                            @endif
                            <?php CommonHelper::reconnectMasterDatabase(); ?>

                        @endforeach
                        </tbody>
                    </table>

                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                @foreach ($recordNotFound as $value)
                                    <table class="table table-bordered"><tr><td class='text-center hidden-print' style='color:red; padding: 0px !important;'><b> {{ $value }} Attendance Not Found ! </b></td></tr></table>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">&nbsp;</div>
            <div class="row">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <input type="submit" name="submit" class="btn btn-success" />
            </div>
        </div>
    </div>`
</div>





