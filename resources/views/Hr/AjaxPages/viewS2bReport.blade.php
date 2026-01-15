<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\EmployeePromotion;
use App\Models\EmployeeBankData;
use App\Models\Regions;

$bank_detail = '';
$emp_name = '';
$emp_father_name = '';
$region_id = '';
$account_no = '';
?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#eee; }

    div.wrapper {

        height: 80%;
        overflow: auto;
    }

</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                    <div class="table-responsive wrapper">
                        <table class="table table-bordered table-condensed table-hover tableFixHead" id="LeavesPolicyList">
                            <thead>
                            <tr>
                                <th class="text-center">Identifier (Default value)</th>
                                <th class="text-center">Emp Project</th>
                                <th class="text-center">Payment Type (Default value)</th>
                                <th class="text-center">Processing Mode (Default value)</th>
                                <th class="text-center">Customer Reference 16 Char</th>
                                <th class="text-center">Debit A/C No. 13 digit</th>
                                <th class="text-center">Debit Currency 3 Char (Default value)</th>
                                <th class="text-center">Debit Bank Id 11 Char (Default value)</th>
                                <th class="text-center">Payment Date (dd/mm/yyyy)</th>
                                <th class="text-center">Payee Bank Name 40 Char</th>
                                <th class="text-center">Payee Bank Code* SWIFT Code 11 Char / IMD Codes 6digits</th>
                                <th class="text-center">Payee A/C No.* only digits (BBAN/IBAN)</th>
                                <th class="text-center">Payment Currency 3 Char (Default Value)</th>
                                <th class="text-center">Net Payment</th>
                                <th class="text-center">Payee Name Line 1 70 Char</th>
                                <th class="text-center">Payee Name Line 2 70 Char</th>
                                <th class="text-center">Payee Address 1 40 Char </th>
                                <th class="text-center">Payee Address 2 40 Char</th>
                                <th class="text-center">Payee Address 3 40 Char</th>
                                <th class="text-center">Payment detail 1 Branch Code 70 Char</th>
                                <th class="text-center">Payment detail 2 70 Char</th>
                                <th class="text-center">Clearing Zone Code 00 = LBC 'ON' (Default Value)</th>
                                <th class="text-center">Emailid</th>
                                <th class="text-center">Corporate Cheque Number 6 digit</th>
                                <th class="text-center">Purpose of Payment See POP codes</th>
                                <th class="text-center">Cheque - Deliver To</th>
                                <th class="text-center">Cheque - Delivery Method</th>
                                <th class="text-center">Cheque - Pickup Location</th>
                                <th class="text-center">Receiver ID type - CNIC/SNIC/NTN/PASSPORT/OTHERS</th>
                                <th class="text-center">Receiver ID</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php


                            foreach($employees as $value):

                                $emp_name = $value["emp_name"];
                                $emp_father_name = $value['emp_father_name'];
                                $region_id = $value['region_id'];
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $payslip = Payslip::where([['emr_no','=',$value["emr_no"]],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);
                                $payroll = PayrollData::where([['emr_no','=',$value["emr_no"]],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);

                                if($payslip->count() > 0 && $payroll->count() > 0):
                                    $payroll_data =$payroll->first();
                                    $payslip =$payslip->first();

                                    $bank_detail = EmployeeBankData::where([['status','=',1],['emr_no','=',$payslip->emr_no]])->value('account_no');
                                    $string = str_replace('-', ',', $bank_detail);
                                    $account_no =  preg_replace('/[^A-Za-z0-9\-]/', '', $string);

                                    if($payslip->payment_mode == 'Cheque'):
                                        $payment_type = 'CC';
                                        $processing_mode = 'ON';
                                        $deliverTo = 'C';
                                        $deliveryMethod = 'P';
                                        $payBankName = '';
                                        $payBankCode = '';
                                        $account_no = '';
                                        CommonHelper::reconnectMasterDatabase();
                                        $region_name = Regions::where([['status','=',1],['company_id','=',Input::get('m')],['id','=',$region_id]])->value('employee_region');
                                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                                        if($region_name == 'North'):
                                            $region = 'LHR';
                                        elseif($region_name == 'Central'):
                                            $region = 'ISB';
                                        elseif($region_name == 'South'):
                                            $region = 'KHI';
                                        endif;
                                        $email = 'm.zeeshan@mfm.net.pk';
                                   else:
                                       $payment_type = 'PAY';
                                       $processing_mode = 'BA';
                                       $deliverTo = '';
                                       $deliveryMethod = '';
                                       $region = '';
                                       $email = '';
                                       $payBankName = 'SCB';
                                       $payBankCode = 'SCBLPKKXXXX';
                                       $bank_detail = EmployeeBankData::where([['status','=',1],['emr_no','=',$payslip->emr_no]])->value('account_no');
                                       $string = str_replace('-', ',', $bank_detail);
                                       $account_no =  preg_replace('/[^A-Za-z0-9\-]/', '', $string);

                                    endif;
                                ?>
                                    <tr class="text-center">
                                        <td>P</td>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$value->employee_project_id)}}</td>
                                        <td>{{ $payment_type }}</td>
                                        <td>{{ $processing_mode }}</td>
                                        <td></td>
                                        <td>1709133001</td>
                                        <td>PKR</td>
                                        <td>SCBLPKKXXXX</td>
                                        <td>{{ HrHelper::date_format(date('Y-m-d')) }}</td>
                                        <td>{{ $payBankName }}</td>
                                        <td>{{ $payBankCode }}</td>
                                        <td>{{ $account_no }}</td>
                                        <td>PKR</td>
                                        <td class="text-right">{{ number_format($payslip->net_salary+$payslip->extra_allowance,0) }}</td>
                                        <td>{{ $emp_name }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $email }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $deliverTo }}</td>
                                        <td>{{ $deliveryMethod }}</td>
                                        <td>{{ $region }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php else:
                                    $recordNotFound[] = "<tr class='text-center'><td colspan='27'><b style='color:red;'> $emp_name Payroll Not Found !</b></td></tr>";
                                    CommonHelper::reconnectMasterDatabase();
                                endif; ?>
                            <?php endforeach; ?>
                            <?php CommonHelper::reconnectMasterDatabase(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
