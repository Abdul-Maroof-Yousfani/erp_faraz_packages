<style>
    .panel-heading {
        padding: 0px 15px;}
</style>
<?php

use App\Helpers\CommonHelper;
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


foreach($departments as $row){
?>
<div class="panel">

    <div class="panel-body">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <?php
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            if($getEmployee == 'All'){
                $employees = Employee::where('emp_department_id', '=', $row['id'])->get(['attendance_machine_id','id','tax_id','eobi_id','emp_code','emp_name','emp_salary']);
            }else{
                $employees = Employee::where('emp_department_id', '=', $row['id'])->where('attendance_machine_id','=',$getEmployee)->get(['attendance_machine_id','id','tax_id','eobi_id','emp_code','emp_name','emp_salary']);

            }


            foreach($employees as $row1) {

            $payslips  = Payslip::where([['emp_id', '=', $row1['attendance_machine_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
            $bonus_amount = 0;
            $bonus = BonusIssue::where([['employee_id', '=', $row1['id']],['bonus_year', '=', $explodeMonthYear[0]],['bonus_month', '=', $explodeMonthYear[1]]]);
            if($bonus->count() > 0):

                $bonus_issue = $bonus->first();
                $bonus_name = Bonus::select('bonus_name')->where([['id', '=', $bonus_issue->bonus_id]])->value('bonus_name');
                $bonus_amount = $bonus_issue->bonus_amount;
            endif;

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

            if ($payslips->count() > 0) {

            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <tr>
                                        <th>Emp. No.:</th>
                                        <td><?php echo $row1['emp_code']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Emp. Name:</th>
                                        <td><?php echo $row1['emp_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Month - Year:</th>
                                        <td><?php echo $explodePaysilpMonth[1] . '-' . $explodePaysilpMonth[0]; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                           <h2 style="text-decoration: underline;font-weight:bold;"><?= 'PAYSLIP' ?></h2>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <?php foreach ($payslips->get() as $row2) {
                                    $emp_id = Employee::select('id')->where([['attendance_machine_id','=',$row2['emp_id']]])->value('id');

                                    ?>
                                    <tr>
                                        <th>Payslip Code:</th>
                                        <td><?php echo $row2['ps_no']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Salary Status:</th>
                                        <td>
                                            <?php
                                            if ($row2['salary_status'] == 1) {
                                                echo 'Paid';
                                            } else if ($row2['salary_status'] == 0) {
                                                echo 'Up-Paid';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Days Deduction</th>
                                        <td><?=$row2['deduction_days'] ?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $acounter = 1;
                        $allowances_total=0;
                        $count_allowance ='';

                        $allowances = Allowance::where('emp_id', '=', $emp_id);

                        if($allowances->count() > 0):?>
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
                                                    foreach($allowances->get() as $value):
                                                       
                                                        $allowances_total+=(($value['allowance_amount']/100)*($row1['emp_salary']));

                                                    endforeach;

                                                    $total_allowance = 0; $count=0;
                                                    foreach($allowances->get() as $value1):$count++;

                                                    $no_days_worked = (30)-($payslips->value('deduction_days'));
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
                        <?php else:?>
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


                                                            <td class="text-center">--</td>
                                                            <td class="text-center">--</td>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                         <?php
                            $dcounter = 1;
                            $count_deduction ='';
                            $deductions = Deduction::where('emp_id', '=', $emp_id);
                            if($deductions->count() > 0):
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
                                                    <?php
                                                    $count2=0;
                                                    foreach ($deductions->get() as $row4) {
                                                    $count_deduction+=$row4['deduction_amount'];
                                                    ?>
                                                    <tr>

                                                        <td class="text-center"><?php echo $row4['deduction_type']; ?></td>
                                                        <td class="text-center"><?php echo number_format($row4['deduction_amount'], 0); ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td class="text-center"><b>Total</b></td>
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
                        <?php else:?>
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
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">Particular</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                            <td class="text-center">--</td>
                                                            <td class="text-center">--</td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
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
                                                            <td class="text-center"><?='--' ?></td>
                                                            <td class="text-center"><?=  '--' ?></td>
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
                                                            <td class="text-center"><?=$eobi->EOBI_name ?></td>
                                                            <td class="text-center"><?= $eobi->EOBI_amount?></td>
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
                                                            <td>
                                                                <input disabled type="text" value="<?= '--' ?>" class="form-control countrow2" />
                                                            </td>
                                                            <td>
                                                                <input disabled type="text" value="<?= '--' ?>" class="form-control" />
                                                            </td>
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
                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-right"> </td>
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <?php foreach ($payslips->get() as $row5) {

                                    ?>

                                    <tr>
                                        <th>Total Salary:</th>
                                        <th class="text-right"><?php echo number_format($row5['total_salary'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Basic Salary:</th>
                                        <th class="text-right"><?php echo number_format($row5['basic_salary'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Allowance:</th>
                                        <th class="text-right"><?php echo number_format($row5['total_allowance'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Deduction:</th>
                                        <th class="text-right"><?php echo number_format($row5['total_deduction'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Gross Salary:</th>
                                        <th class="text-right"><?php echo number_format($bonus_amount+$row5['net_salary'], 0); ?></th>
                                    </tr>



                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div> &nbsp;
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <?php foreach ($payslips->get() as $row6) {

                                     $deduction= PayrollData::where([['emp_id','=',$row6['emp_id']],['year', '=',$row6['year']],['month', '=', $row6["month"]]]);
                                   if($deduction->count() > 0):
                                     $deduction_days= $deduction->first();
                                     ?>
                                    <tr>
                                        <th>Total Days:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_days ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Present:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_present ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Absent:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_absent ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Holidays:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_holidays ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Late Arrivals:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_late_arrivals ?></th>
                                    </tr>


                                    <?php
                                            else:
                                            echo "<h3 class='row text-center' style='color:red'>Payroll Not Found !</h3>";
                                    endif;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            else
            {?>
            <div class="row text-center" style="color:red"><b>Payslip Not Found !</b></div>
            <?php }
            }
            ?>
        </div>
    </div>
</div>
<?php
}?>