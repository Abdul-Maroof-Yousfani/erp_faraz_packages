
<?php
$recordNotFound = [];
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payslip;
use App\Models\Tax;
use App\Models\Eobi;
use App\Models\Bonus;
use App\Models\BonusIssue;
use App\Models\Attendence;
use App\Models\PayrollData;
use App\Models\LoanRequest;
use App\Models\EmployeeBankData;
?>
<style>
    .panel-heading {
        padding: 0px 15px;}
    .space{margin:50px;}
    @media all {
        .page-break { display: none; }
    }

    @media print {
        .page-break { display: block; page-break-before: always; }
    }
</style>

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

    $bank_acc_no = EmployeeBankData::where([['status','=',1],['emp_code','=',$value->emp_code]])->value('account_no');
    CommonHelper::reconnectMasterDatabase();
    ?>

    @if ($payslip->count() > 0)

        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div id="payrollrow{{ $value->emp_code }}">
                        <div class="row" style="padding-left: 15px; padding-right: 15px">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                {{ CommonHelper::headerPrintSectionInPrintView(Input::get('m')) }}
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                <tbody>
                                                <tr>
                                                    <th>Emp Code:</th>
                                                    <td>{{ $value->emp_code }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Employee Name:</th>
                                                    <td>{{ $value->emp_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>CNIC:</th>
                                                    <td>{{ $value->emp_cnic }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Department:</th>
                                                    <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$value->emp_department_id) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h2 style="text-decoration: underline;font-weight:bold;"><?= 'PAYSLIP' ?></h2>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                <tbody>
                                                <tr>
                                                    <th>Salary Status:</th>
                                                    <td>
                                                        @if($payslip->value('salary_status') == 1)
                                                            Paid
                                                        @elseif($payslip->value('salary_status') == 2)
                                                            Up-Paid
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Month - Year:</th>
                                                    <td>{{ $month_year[1] . '-' . $month_year[0] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Bank Ac.No:</th>
                                                    <td>{{ $bank_acc_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Standard Salary</th>
                                                    <td>{{ number_format($payslip->value('emp_salary'),0) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                <tbody>
                                                <tr>
                                                    <th>Total Present</th>
                                                    <th class="text-right">{{ $payslip->value('present_days') }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Total Absent</th>
                                                    <th class="text-right">{{ $payslip->value('absent_days') }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Eid Bonus</th>
                                                    <th class="text-right">{{ number_format($payslip->value('bonus_amount'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>O.T Hours</th>
                                                    <th class="text-right">{{ $payslip->value('overtime_hours_paid') }}</th>
                                                </tr>
                                                <tr>
                                                    <th>O.T Amount</th>
                                                    <th class="text-right">{{ number_format($payslip->value('overtime_amount'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Allowances</th>
                                                    <th class="text-right">{{ number_format($payslip->value('lunch_allowance') + $payslip->value('other_allowances'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Other Amount</th>
                                                    <th class="text-right">{{ number_format($payslip->value('other_amount'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Total Allowance</th>
                                                    <th class="text-right">{{ number_format($payslip->value('total_allowance'),0) }}</th>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                <tbody>
                                                <tr>
                                                    <th>Late Deduction Days</th>
                                                    <th class="text-right">{{ $payslip->value('late_deduction_days') }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Late Deduction Amount</th>
                                                    <th class="text-right">{{ number_format($payslip->value('late_deduction_amount'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Other Deduction</th>
                                                    <th class="text-right">{{ number_format($payslip->value('other_deduction'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Loan Deduction This Month</th>
                                                    <th class="text-right">{{ number_format($payslip->value('loan_amount'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Total Loan</th>
                                                    <th class="text-right">{{ number_format($total_loan,0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Remaining Loan</th>
                                                    <th class="text-right">{{ number_format($remaining_loan_amount,0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Paid Loan</th>
                                                    <th class="text-right">{{ number_format($total_loan_paid,0) }}</th>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div> &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                <tbody>
                                                <tr>
                                                    <th>Advance Salary</th>
                                                    <th class="text-right">{{ number_format($payslip->value('advance_salary_amount'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>EOBI</th>
                                                    <th class="text-right">{{ number_format($payslip->value('eobi_amount'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Tax</th>
                                                    <th class="text-right">{{ number_format($payslip->value('tax_amount'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Total Deduction</th>
                                                    <th class="text-right">{{ number_format($payslip->value('total_deduction'),0) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Net Salary</th>
                                                    <th class="text-right">{{ number_format($payslip->value('net_salary'),0) }}</th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div> &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="page-break"></div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <?php
        $recordNotFound[] = "<tr class='text-center'><td colspan='27'><b style='color:red;'> $value->emp_name Payroll Not Found !</b></td></tr>" ;
        ?>
    @endif
@endforeach

@if(!empty($recordNotFound))
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <?php
                foreach ($recordNotFound as $value):
                    echo $value;
                endforeach;
                ?>
            </div>
        </div>
    </div>
@endif

