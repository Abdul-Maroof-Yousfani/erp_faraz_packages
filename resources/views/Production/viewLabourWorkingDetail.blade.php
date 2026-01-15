<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\PurchaseHelper;

$id = $_GET['id'];
$m = $_GET['m'];

$currentDate = date('Y-m-d');

$Master = DB::Connection('mysql2')->table('production_labour_working')->where('id',$id)->first();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printMachineDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printMachineDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">View Labour Working Detail</h3>
                            <h3 class="text-center">
                                <?php if($Master->status == 1):?>
                                <span class="text-success" style="font-size: 20px;"><i class="fa fa-check" aria-hidden="true"></i>ACTIVE</span>
                                <?php else:?>
                                <span class="text-danger" style="font-size: 20px;"><i class="fa fa-ban" aria-hidden="true"></i>INACTIVE</span>
                                <?php endif;?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive" id="">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="text-center">
                                <th colspan="5" class="text-center">Labours Working Detail</th>
                            </tr>
                            <tr>
                                <th class="text-center">Sr No.</th>
                                <th class="text-center" style="width: 40% !important;">Description</th>
                                <th style="" class="text-center">No of Employee</th>
                                <th style="" class="text-center">Wages / Work</th>
                                <th style="" class="text-center">Monthly Wages</th>
                                <th style="" class="text-center">Yearly Wages</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $Counter = 1;
                            $TotalNoOfEmp = 0;
                            $TotalWagesWorkAmount = 0;
                            $TotalMonthlyWagesAmount = 0;
                            $TotalYearlyWagesAmount = 0;
                            $DetailData = DB::Connection('mysql2')->table('production_labour_working_data')->where('master_id',$id)->get();
                            foreach($DetailData as $Fil):
                            ?>
                            <tr id="" class="AutoNo">
                                <td class="text-center"><?php echo $Counter++;?></td>
                                <td><?php echo $Fil->description;?></td>
                                <td class="text-center"><?php echo number_format($Fil->no_of_employee); $TotalNoOfEmp+=$Fil->no_of_employee;?></td>
                                <td><?php echo number_format($Fil->wages_work_amount,2); $TotalWagesWorkAmount+=$Fil->wages_work_amount;?></td>
                                <td><?php echo number_format($Fil->monthly_wages_amount,2); $TotalMonthlyWagesAmount+=$Fil->monthly_wages_amount;?></td>
                                <td><?php echo number_format($Fil->yearly_wages_amount,2); $TotalYearlyWagesAmount+=$Fil->yearly_wages_amount;?></td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>
                            <tbody>
                            <tr class="text-center">
                                <td colspan="4" style="font-size: 20px !important; font-weight: 700;">TOTAL</td>
                                <td id="TotalMonthlyWages" style="font-size: 20px !important; font-weight: 700;"><?php echo number_format($TotalMonthlyWagesAmount,2);?></td>
                                <td id="TotalYearlyWages" style="font-size: 20px !important; font-weight: 700;"><?php echo number_format($TotalYearlyWagesAmount,2);?></td>
                            </tr>
                            <tr class="text-center">
                                <td rowspan="2" colspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;">D/L Rate</td>
                                <td id="TotalYearlyWagesSecond" style="font-size: 20px !important; font-weight: 700; border-bottom: solid"><?php echo number_format($TotalYearlyWagesAmount,2);?></td>
                                <td rowspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;">Per Hour</td>
                                <td id="PerHour" rowspan="2" colspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;"><?php echo number_format($TotalYearlyWagesAmount/$Master->total_working_hours,2);?></td>
                            </tr>
                            <tr class="text-center">
                                <td id="TotalWorkingHoursTd" rowspan="2" style="font-size: 20px !important; font-weight: 700;"><?php echo number_format($Master->total_working_hours,2)?></td>
                                <td rowspan="2" colspan="2"></td>
                            </tr>
                            </tbody>
                            <tbody>
                            <tr>
                                <td colspan="2" style="font-size: 20px !important; font-weight: 700;">Working Note</td>
                                <td colspan="5" style="font-size: 20px !important; font-weight: 700;">Working Note Remarks</td>
                            </tr>
                            <tr>
                                <td style="font-size: 14px !important; font-weight: 700;">Working Hours</td>
                                <td class="text-right"><?php echo number_format($Master->working_hours,2);?></td>
                                <td rowspan="3" colspan="5">
                                    <?php echo $Master->remarks;?>
                                </td>
                            </tr>

                           
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>




