<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];
$counter = 1;
$remaining_amount = $loanRequest->loan_amount-$paid_amount->paid_amount;
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
                                        <th class="text-center col-sm-1">Employee Name</th>
                                        <td class="text-center col-sm-1">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$loanRequest->emp_code,'emp_code') }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Month - Year</th>
                                        <td class="text-center col-sm-1">{{ date('M - Y',strtotime($loanRequest->year.'-'.$loanRequest->month)) }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Description</th>
                                        <td class="text-center">{{ $loanRequest->description }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Per Month Deduction</th>
                                        <td class="text-center col-sm-1">{{ number_format($loanRequest->per_month_deduction,0) }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Loan Amount</th>
                                        <td class="text-center col-sm-1">{{ number_format($loanRequest->loan_amount,0) }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Paid loan Amount</th>
                                        <td class="text-center col-sm-1" style="color: red;">{{ number_format($paid_amount->paid_amount,0)}}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center col-sm-1">Remaining Amount</th>
                                        <td class="text-center col-sm-1" style="color: red;">{{number_format($remaining_amount,0)}}</td>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php if(count($loan_Detail) != '0'){ ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: gainsboro">
                            <h4 style="text-decoration: underline;font-weight: bold;">Loan Detail</h4>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list" id="LoanRequestList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Deduction Month & Date</th>
                                        <th class="text-center">Deduction Amount</th>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach($loan_Detail as $value){
                                            $monthNum  = $value->month;
                                            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                            $monthName = $dateObj->format('F'); // March
                                             ?>
                                              <tr>
                                              <td class="text-center">{{ $counter++ }}</td>
                                              <td class="text-center"><?php echo $monthName.' '.$value->year ?></td>
                                              <td class="text-center">{{ number_format($value->loan_amount_paid,0) }}</td>
                                              </tr>
                                                <?php
                                            }
                                        ?>
                                        <tr>
                                            <th colspan="2" class="text-right">Total</th>
                                            <th class="text-center">{{ number_format($paid_amount->paid_amount,0)}}</th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


