<?php

$accType = Auth::user()->acc_type;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];
$currentDate = date('Y-m-d');

$emp_salary = $salary;
$emp_joining_date = $employee->emp_joining_date;
$last_working_date = $exit_data->last_working_date;

$leaving_type = "";
if($exit_data->leaving_type == 1):
    $leaving_type = "Resignation";
elseif($exit_data->leaving_type == 2):
    $leaving_type = "Retirement";
elseif($exit_data->leaving_type == 3):
    $leaving_type = "Termination";
elseif($exit_data->leaving_type == 4):
    $leaving_type = "Dismissal";
elseif($exit_data->leaving_type == 4):
    $leaving_type = "Demise";

endif;

$id = $final_settlement['id'];
$salary_from = $final_settlement['salary_from'];
$salary_to = $final_settlement['salary_to'];

$date1 = strtotime($salary_to);
$date2 = strtotime($salary_from);

$diff = $date1 - $date2;
$days = round($diff / 86400) + 1;

$round_salary = round($emp_salary/30*$days);

$gratuity = $gratuity['gratuity'];
$others = $final_settlement['others'];
$notice_pay = $final_settlement['notice_pay'];
$advance = $final_settlement['advance'];
$mobile_bill = $final_settlement['mobile_bill'];
$toolkit = $final_settlement['toolkit'];
$mfm_id_card = $final_settlement['mfm_id_card'];
$uniform = $final_settlement['uniform'];
$laptop = $final_settlement['laptop'];
$any_others = $final_settlement['any_others'];

$total_addition = $final_settlement['total_addition'];
$total_deduction = $final_settlement['total_deduction'];
$grand_total = $final_settlement['grand_total'];


?>
<style>
    @media print {
        .border {
            border-bottom: solid 1px #000000 !important;
        }
    }

    .border {
        border-bottom: solid 1px #000000;
    }
</style>

<div class="container">
    <div class="row text-right">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if($type != 'log')
                @if(in_array('print', $operation_rights2))
                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintExitClearenceDetail','','1');?>
                @endif
            @endif
        </div>
    </div>
</div>
<br>

<div class="container" id="PrintExitClearenceDetail">
    <div class="print-font2">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <!--  <h2 class="text-bold print-font-size2 text-center">MIMA FACILITY MANAGEMENT <br></h2>-->
                <h4 class="text-bold print-font-size2 text-center"> FINAL SETTLEMENT</h4>
            </div>
        </div>
        <hr>
        <div class="row"></div>
        <div class="row war-margin1">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 print-font-size">
                <b>PART-1</b>
            </div>
            <br>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>Name: <span class="border"> {{ $employee->emp_name }}</span></b></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>Emp Code: <span class="border"> {{ $employee->emp_code }}</span></b> </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>Designation. <span class="border">{{ HrHelper::getMasterTableValueById($m, 'designation', 'designation_name', $designation_id ) }}</span></b></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>Department: <span class="border">{{ HrHelper::getMasterTableValueById($m, 'department', 'department_name', $emp_department_id ) }}</span></b></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>D.O. Appt: <span class="border">{{ HrHelper::date_format($employee->emp_joining_date) }}</span></b></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>D.O. Discharge: <span class="border">{{ HrHelper::date_format($last_working_date) }}</span></b></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>Reason of Release: <span class="border">{{ $leaving_type }}</span></b></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>Last Salary Rate: <span class="border">{{ number_format($salary,0) }}</span></b></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
                <p class="text-bold print-font-size"><b>Region: <span class="border">{{ HrHelper::getMasterTableValueByIdAndColumn($m, 'regions', 'employee_region', $employee->region_id, 'id') }}</span></b></p>
            </div>
        </div>
        <br>
        <div class="row war-margin1">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 print-font-size">
                <b>PART-2</b>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Payment</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">

            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center"><b>Amount (RS)</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center"><b>Verified By (SE)</b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Salary For The Period</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center"><b>{{ HrHelper::date_format($salary_from) }} to {{ HrHelper::date_format($salary_to) }} ({{ $days }} days) ({{ number_format($salary,0) }} / 30 * {{$days}})</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center"><b>{{ number_format($round_salary,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center"><b> </b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Gratuity</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($gratuity,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Others</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($others,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>Total </b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>PKR {{ number_format($total_addition,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Notice Pay</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($notice_pay,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Advance</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($advance,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Mobile Bill</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($mobile_bill,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Tool Kit</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($toolkit,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>MFM ID Card</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($mfm_id_card,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Uniform</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($uniform,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Laptop</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($laptop,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Any Others</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>{{ number_format($any_others,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>Total </b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>PKR {{ number_format($total_deduction,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>Net Payable / Receivable</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size text-center "><b>PKR {{ number_format($grand_total,0) }}</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size text-center "><b></b></p>
            </div>
        </div>

        <br><br>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>____________________</b></p>
                <p class="text-bold print-font-size"><b>HR Manager</b></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size"><b>____________________</b></p>
                <p class="text-bold print-font-size"><b>Finance Manager</b></p>

            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size ">
                <p class="text-bold print-font-size"><b>____________________</b></p>
                <p class="text-bold print-font-size"><b>Director Operation</b></p>

            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 print-font-size">
                <p class="text-bold print-font-size"><b>____________________</b></p>
                <p class="text-bold print-font-size"><b>COO</b></p>

            </div>
        </div>







    </div>
</div>
