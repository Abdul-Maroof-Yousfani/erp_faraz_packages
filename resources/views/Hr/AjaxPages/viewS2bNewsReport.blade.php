<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeeBankData;
use App\Models\Regions;
use App\Models\Overtime;
use App\Models\Fuel;
use App\Models\DriversAllowances;

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
            <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
            <div class="">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive wrapper">
                        <table class="table table-responsive table-bordered table-condensed tableFixHead" id="LeavesPolicyList">
                            <thead>
                            <tr>
                                <th class="text-center">Identifier (Default value)</th>
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
                            CommonHelper::companyDatabaseConnection(Input::get('m'));


                            foreach($fuel as $value):
                            CommonHelper::reconnectMasterDatabase();
                            $emp_name = HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value->emr_no,'emr_no');

                            $payment_type = 'PAY';
                            $processing_mode = 'BA';
                            $deliverTo = '';
                            $deliveryMethod = '';
                            $region = '';
                            $email = '';
                            $payBankName = 'SCB';
                            $payBankCode = 'SCBLPKKXXXX';
                            $bank_detail = $value->bank_account_no;
                            $string = str_replace('-', ',', $bank_detail);
                            $account_no =  preg_replace('/[^A-Za-z0-9\-]/', '', $string);

                            ?>
                            <tr class="text-center">
                                <td>P</td>
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
                                <td class="text-right">{{ number_format($value->amount,0) }}</td>
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
                            <?php endforeach; ?>
                            </tbody>

                            <tbody>
                            <?php
                            foreach($overtime as $value):
                            $emp_name = HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value->emr_no,'emr_no');

                            $payment_type = 'PAY';
                            $processing_mode = 'BA';
                            $deliverTo = '';
                            $deliveryMethod = '';
                            $region = '';
                            $email = '';
                            $payBankName = 'SCB';
                            $payBankCode = 'SCBLPKKXXXX';
                            $bank_detail = $value->bank_account_no;
                            $string = str_replace('-', ',', $bank_detail);
                            $account_no =  preg_replace('/[^A-Za-z0-9\-]/', '', $string);

                            ?>
                            <tr class="text-center">
                                <td>P</td>
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
                                <td class="text-right">{{ number_format($value->ot_for_month,0) }}</td>
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
                            <?php endforeach; ?>
                            </tbody>

                            <tbody>
                            <?php
                            foreach($drivers_allowances as $value):

                            $emp_name = $value->emp_name;

                            $payment_type = 'PAY';
                            $processing_mode = 'BA';
                            $deliverTo = '';
                            $deliveryMethod = '';
                            $region = '';
                            $email = '';
                            $payBankName = 'SCB';
                            $payBankCode = 'SCBLPKKXXXX';
                            $bank_detail = $value->bank_account_no;
                            $string = str_replace('-', ',', $bank_detail);
                            $account_no =  preg_replace('/[^A-Za-z0-9\-]/', '', $string);

                            ?>
                            <tr class="text-center">
                                <td>P</td>
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
                                <td class="text-right">{{ number_format($value->total_allowance,0) }}</td>
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
                            <?php endforeach; ?>
                            <?php CommonHelper::reconnectMasterDatabase();  ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
