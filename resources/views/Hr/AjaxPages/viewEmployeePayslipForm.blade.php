<style>
    .panel-heading {
        padding: 0px 15px;}
</style>


<?php
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payslip;
use App\Models\Tax;
use App\Models\Eobi;
use App\Models\BonusIssue;
use App\Models\Bonus;
use App\Models\Attendence;
use App\Models\PayrollData;
?>

<?php
foreach($departments as $row){

?>
<div class="panel">
    <div class="panel-body">
        <?php $company_name =  CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <?php
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            if($getEmployee == 'All'){
                $employees = Employee::where('emp_department_id', '=', $row['id'])->get(['id','tax_id','eobi_id','emp_code','emp_name','emp_salary','attendance_machine_id']);
            }else{
                $employees = Employee::where('emp_department_id', '=', $row['id'])->where('attendance_machine_id','=',$getEmployee)->get(['attendance_machine_id','id','tax_id','eobi_id','emp_code','emp_name','emp_salary']);

            }

            foreach($employees as $row1){
            $emp_name = $row1['emp_name'];
            $payroll_deduction = PayrollData::where([['emp_id', '=', $row1['attendance_machine_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
            if($payroll_deduction->count()>0):$deduction_days = $payroll_deduction->value('deduction_days');


            $paySlip = Payslip::where([['emp_id', '=', $row1['attendance_machine_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
            $bonus = BonusIssue::where([['employee_id', '=', $row1['id']],['bonus_year', '=', $explodeMonthYear[0]],['bonus_month', '=', $explodeMonthYear[1]]]);
            $bonus_amount = 0;

            if($bonus->count() > 0):

                $bonus_issue = $bonus->first();
                $bonus_name = Bonus::select('bonus_name')->where([['id', '=', $bonus_issue->bonus_id]])->value('bonus_name');
                $bonus_amount = $bonus_issue->bonus_amount;
            endif;

            $allowance = Allowance::where([['emp_id', '=', $row1['id']]]);
            $deduction = Deduction::where([['emp_id', '=', $row1['id']]]);
            // ['status','=','1']

            CommonHelper::reconnectMasterDatabase();
            if($row1['tax_id'] != '0'):

                $tax = Tax::select('tax_percent','tax_name')->where([['id','=',$row1['tax_id']],['company_id','=',Input::get('m')],['status','=','1']])->first();
                $tax_deduct = (($tax->tax_percent/100)*$row1['emp_salary']);
            else:
                $tax_deduct = 0;
            endif;
            if($row1['eobi_id'] != '0'):

                $eobi = Eobi::where([['id','=',$row1['eobi_id']],['company_id','=',Input::get('m')],['status','=','1']])->first();
                $eobi_deduct = $eobi->EOBI_amount;
            else:
                $eobi_deduct = 0;

            endif;
            CommonHelper::companyDatabaseConnection(Input::get('m'));

            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h4>
                                    <b class="text-center"> Emp Name: <?php echo $row1['emp_name'];?></b>
                                </h4>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                                <h2 style="text-decoration: underline;font-weight:bold;"><?= 'PAYSLIP' ?></h2>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h4 style="text-decoration: underline;font-weight:bold;">Dept Name: <?=$row['department_name'] ?></h4>

                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                            <div class="row">
                                <?php
                                $count_allowance ='';
                                $allowances_total=0;
                                if($allowance->count() > 0):
                                ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Allowances</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Particular</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $allowances_total='';
                                                            foreach($allowance->get() as $value):

                                                                $allowances_total+=(($value['allowance_amount']/100)*($row1['emp_salary']));

                                                            endforeach;

                                                            $total_allowance = 0; $count=0;
                                                            foreach($allowance->get() as $value1):$count++;

                                                            $deduction_days = PayrollData::where([['emp_id', '=', $row1['attendance_machine_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]])->first();

                                                            $no_days_worked = (30)-($deduction_days->deduction_days);
                                                            $total_days_worked = ($allowances_total+$row1['emp_salary'])/(30);
                                                            $rate_of_pay = $total_days_worked*$no_days_worked;
                                                            $basic_pay_allowance = ($rate_of_pay)/(1.5);
                                                            $count_allowance+=round(($value1['allowance_amount']/100)*($basic_pay_allowance));
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?= $value1['allowance_type']; ?></td>
                                                                <td class="text-center"><?= round(($value1['allowance_amount']/100)*($basic_pay_allowance)) ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td class="text-right"><b>Total</b></td>
                                                                <td class="text-center"><b> <?php echo number_format($count_allowance,0); ?></b></td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Allowances</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Particular</th>
                                                                <th class="text-center">Amount</th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center">--</td>
                                                                <td class="text-center">--</td>
                                                            </tr>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="3" class="text-right">
                                                                </td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php

                                $count_deduction ='';
                                if($deduction->count() > 0):
                                ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Deductions</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Particular</th>
                                                                <th class="text-center">Amount</th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            <?php $count2=0;foreach($deduction->get() as $value2): $count2++; $count_deduction+=$value2['deduction_amount'];?>
                                                            <tr>
                                                                <td class="text-center"><?= $value2['deduction_type'];?></td>
                                                                <td class="text-center"><?= $value2['deduction_amount'];?> </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td class="text-right"><b>Total</b></td>
                                                                <td class="text-center"><b> <?php echo $count_deduction; ?></b></td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Deductions</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Particular</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center">--</td>
                                                                <td class="text-center">--</td>
                                                            </tr>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="3" class="text-right">
                                                                </td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <?php if($row1['tax_id'] != '0'):?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Taxes</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Tax</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr>
                                                                <td class="text-center"><?= $tax->tax_name; ?></td>
                                                                <td class="text-center"><?= $tax_deduct; ?></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Taxes</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Tax</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center">--</td>
                                                                <td class="text-center">--</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if($row1['eobi_id'] != '0'):?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">EOBI</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">EOBI</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center"><?= $eobi->EOBI_name?></td>
                                                                <td class="text-center"><?= $eobi->EOBI_amount?></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">EOBI</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">EOBI</th>
                                                                <th class="text-center">Amount</th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center">--</td>
                                                                <td class="text-center">--</td>
                                                            </tr>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="3" class="text-right">
                                                                </td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if($bonus->count() > 0 ):?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Bonus</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Bonus</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center"><?= $bonus_name ?></td>
                                                                <td class="text-center"><?= $bonus_amount ?></td>

                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif;
                                 $deduction_days = PayrollData::where([['emp_id', '=', $row1['attendance_machine_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]])->first();
                                 $total_days_worked = ($row1['emp_salary']+$allowances_total)/(30);
                                 $rate_of_pay = $total_days_worked*(30-$deduction_days->deduction_days);
                                 $basic_pay_allowance = ($rate_of_pay)/(1.5);
                                 $total_deduction = ($count_deduction+$tax_deduct+$eobi_deduct);
                                 $netSalary = $basic_pay_allowance + $count_allowance - $total_deduction;
                                ?>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <?php if($paySlip->count() > 0):

                                            ?>
                                            <div class="row">

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Days</label>
                                                    <input type="number" id="basic_salary_<?php echo $row1['id'];?>" name="basic_salary_<?php echo $row1['id'];?>" value="<?php echo $deduction_days->total_days;?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Present</label>
                                                    <input type="number" id="total_allowance_<?php echo $row1['id'];?>" name="total_allowance_<?php echo $row1['id'];?>" value="<?= $deduction_days->total_present ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Absent</label>
                                                    <input type="number" id="total_deduction_<?php echo $row1['id'];?>" name="total_deduction_<?php echo $row1['id'];?>" value="<?php echo $deduction_days->total_absent  ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Holidays</label>
                                                    <input type="number" id="net_salary_<?php echo $row1['id'];?>" name="net_salary_<?php echo $row1['id'];?>" value="<?= $deduction_days->total_holidays ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Late Arrival</label>
                                                    <input readonly type="number" id="deduction_days_<?php echo $row1['id'];?>" name="deduction_days_<?php echo $row1['id'];?>" value="<?=$deduction_days->total_late_arrivals?>" class="form-control" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Deduction Days</label>
                                                    <input readonly type="number" id="deduction_days_<?php echo $row1['id'];?>" name="deduction_days_<?php echo $row1['id'];?>" value="<?=$deduction_days->deduction_days?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Salary</label>
                                                    <input type="number" id="total_salary_<?php echo $row1['id'];?>" name="total_salary_<?php echo $row1['id'];?>" value="<?php echo $row1['emp_salary']+$allowances_total;?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Basic</label>
                                                    <input type="number" id="basic_salary_<?php echo $row1['id'];?>" name="basic_salary_<?php echo $row1['id'];?>" value="<?php echo $row1['emp_salary'];?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Allowance</label>
                                                    <input type="number" id="total_allowance_<?php echo $row1['id'];?>" name="total_allowance_<?php echo $row1['id'];?>" value="<?= round($count_allowance); ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Deduction</label>
                                                    <input type="number" id="total_deduction_<?php echo $row1['id'];?>" name="total_deduction_<?php echo $row1['id'];?>" value="<?php echo $total_deduction = round($count_deduction+$tax_deduct+$eobi_deduct); ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Gross Salary</label>
                                                    <input type="number" id="net_salary_<?php echo $row1['id'];?>" name="net_salary_<?php echo $row1['id'];?>" value="<?= round($netSalary)?>" class="form-control" readonly="" />
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Status</label>
                                                    <select name="salary_status_<?php echo $row1['id'];?>" id="salary_status_<?php echo $row1['id'];?>" class="form-control RequiredField">
                                                        <option value="">Select</option>
                                                        <option <?php if($paySlip->first()->salary_status == '1') echo "selected"; ?> value="1">Paid</option>
                                                        <option <?php if($paySlip->first()->salary_status == '2') echo "selected"; ?> value="2">Un-Paid</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <?php else: ?>
                                            <div class="row">

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Days</label>
                                                    <input type="number" id="basic_salary_<?php echo $row1['id'];?>" name="basic_salary_<?php echo $row1['id'];?>" value="<?php echo $deduction_days->total_days;?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Present</label>
                                                    <input type="number" id="total_allowance_<?php echo $row1['id'];?>" name="total_allowance_<?php echo $row1['id'];?>" value="<?= $deduction_days->total_present ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Absent</label>
                                                    <input type="number" id="total_deduction_<?php echo $row1['id'];?>" name="total_deduction_<?php echo $row1['id'];?>" value="<?php echo $deduction_days->total_absent  ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Holidays</label>
                                                    <input type="number" id="net_salary_<?php echo $row1['id'];?>" name="net_salary_<?php echo $row1['id'];?>" value="<?= $deduction_days->total_holidays ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Late Arrival</label>
                                                    <input readonly type="number" id="deduction_days_<?php echo $row1['id'];?>" name="deduction_days_<?php echo $row1['id'];?>" value="<?=$deduction_days->total_late_arrivals?>" class="form-control" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Deduction Days</label>
                                                    <input readonly type="number" id="deduction_days_<?php echo $row1['id'];?>" name="deduction_days_<?php echo $row1['id'];?>" value="<?=$deduction_days->deduction_days?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Salary</label>
                                                    <input type="number" id="total_salary_<?php echo $row1['id'];?>" name="total_salary_<?php echo $row1['id'];?>" value="<?php echo round($row1['emp_salary']+$allowances_total);?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Basic</label>
                                                    <input type="number" id="basic_salary_<?php echo $row1['id'];?>" name="basic_salary_<?php echo $row1['id'];?>" value="<?php echo $row1['emp_salary'];?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Allowance</label>
                                                    <input type="number" id="total_allowance_<?php echo $row1['id'];?>" name="total_allowance_<?php echo $row1['id'];?>" value="<?= round($count_allowance); ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Deduction</label>
                                                    <?php $total_deduction = ($count_deduction+$tax_deduct+$eobi_deduct); ?>
                                                    <input type="number" id="total_deduction_<?php echo $row1['id'];?>" name="total_deduction_<?php echo $row1['id'];?>" value="<?php echo $total_deduction = round($count_deduction+$tax_deduct+$eobi_deduct); ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Gross Salary</label>
                                                    <input type="number" id="net_salary_<?php echo $row1['id'];?>" name="net_salary_<?php echo $row1['id'];?>" value="<?= round($netSalary)?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Status</label>
                                                    <select name="salary_status_<?php echo $row1['id'];?>" id="salary_status_<?php echo $row1['id'];?>" class="form-control RequiredField">
                                                        <option value="2">Un-Paid</option>
                                                        <option value="1">Paid</option>

                                                    </select>
                                                </div>

                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                else:
                    echo "<div class='row text-center' style='color:red'><b>Employee = $emp_name   Month=$explodeMonthYear[1] Year=$explodeMonthYear[0] Attendance Not Found !</b></div>";


                endif;
            }
            ?>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <input type="submit" name="submit" class="btn btn-success" />
            </div>
        </div>
    </div>
</div>
<?php
}
?>