<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$m = Input::get('m');

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$emp_name = $employee->emp_name;

$branch_id = $employee->branch_id;

$id = $exit_employee_data->id;
$emr_no = $exit_employee_data->emr_no;
$leaving_type = $exit_employee_data->leaving_type;
$last_working_date = $exit_employee_data->last_working_date;
$approval_status = $exit_employee_data->approval_status;
$supervisor_name = $exit_employee_data->supervisor_name;
$signed_by_supervisor = $exit_employee_data->signed_by_supervisor;

$room_key=$exit_employee_data->room_key;
$mobile_sim=$exit_employee_data->mobile_sim;
$fuel_card=$exit_employee_data->fuel_card;
$mfm_employee_card=$exit_employee_data->mfm_employee_card;
$client_access_card=$exit_employee_data->client_access_card;
$medical_insurance_card=$exit_employee_data->medical_insurance_card;
$eobi_card=$exit_employee_data->eobi_card;
$biometric_scan=$exit_employee_data->biometric_scan;
$payroll_deduction=$exit_employee_data->payroll_deduction;
$info_sent_to_client=$exit_employee_data->info_sent_to_client;
$client_exit_checklist=$exit_employee_data->client_exit_checklist;
$exit_interview=$exit_employee_data->exit_interview;

$laptop=$exit_employee_data->laptop;
$desktop_computer=$exit_employee_data->desktop_computer;
$email_account_deactivated=$exit_employee_data->email_account_deactivated;
$toolkit_ppe=$exit_employee_data->toolkit_ppe;
$uniform=$exit_employee_data->uniform;

$advance_loan=$exit_employee_data->advance_loan;
$extra_leaves=$exit_employee_data->extra_leaves;
$final_settlement=$exit_employee_data->final_settlement;


$room_key_remarks=$exit_employee_data->room_key_remarks;
$mobile_sim_remarks=$exit_employee_data->mobile_sim_remarks;
$fuel_card_remarks=$exit_employee_data->fuel_card_remarks;
$mfm_employee_card_remarks=$exit_employee_data->mfm_employee_card_remarks;
$client_access_card_remarks=$exit_employee_data->client_access_card_remarks;
$medical_insurance_card_remarks=$exit_employee_data->medical_insurance_card_remarks;
$eobi_card_remarks=$exit_employee_data->eobi_card_remarks;
$biometric_scan_remarks=$exit_employee_data->biometric_scan_remarks;
$payroll_deduction_remarks=$exit_employee_data->payroll_deduction_remarks;
$info_sent_to_client_remarks=$exit_employee_data->info_sent_to_client_remarks;
$client_exit_checklist_remarks=$exit_employee_data->client_exit_checklist_remarks;
$exit_interview_remarks=$exit_employee_data->exit_interview_remarks;

$laptop_remarks=$exit_employee_data->laptop_remarks;
$desktop_computer_remarks=$exit_employee_data->desktop_computer_remarks;
$email_account_deactivated_remarks=$exit_employee_data->email_account_deactivated_remarks;
$toolkit_ppe_remarks=$exit_employee_data->toolkit_ppe_remarks;
$uniform_remarks=$exit_employee_data->uniform_remarks;

$advance_loan_remarks=$exit_employee_data->advance_loan_remarks;
$extra_leaves_remarks=$exit_employee_data->extra_leaves_remarks;
$final_settlement_remarks=$exit_employee_data->final_settlement_remarks;

?>

<div class="container">
    <div class="row text-right">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			@if($type != 'log')
				@if(in_array('approve', $operation_rights2))
					@if ($approval_status != 2)
						<button type="button" class="btn btn-primary btn-xs" onclick="approveAndRejectEmployeeExit('<?php echo $m ?>','<?php echo $id;?>', '2', 'employee_exit', '<?php echo $emr_no ?>', '3')"> Approve </button>
					@endif
				@endif
				@if(in_array('reject', $operation_rights2))
					@if ($approval_status != 3)
						<button type="button" class="btn btn-danger btn-xs" onclick="approveAndRejectEmployeeExit('<?php echo $m ?>','<?php echo $id;?>', '3', 'employee_exit', '<?php echo $emr_no ?>', '1')"> Reject </button>
					@endif
				@endif

				@if(in_array('print', $operation_rights2))
					@if ($approval_status == 2)
						<?php echo CommonHelper::displayPrintButtonInBlade('PrintExitClearenceDetail','','1');?>
					@endif
				@endif
			@endif
        </div>
    </div>
</div>
<br>
<div class="container" id="PrintExitClearenceDetail">
    <div class="print-font2">
		<!--<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
				<img src="../assets/img/mima_logo1.png" alt="" class="mima-logo">
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
				<h4 class="text-bold print-font-size2 text-center">EMPLOYEE EXIT CHECKLIST <br>MIMA FACILITY MANAGEMENT</h4>
			</div>
		</div>-->
		<div class="row">&nbsp</div>
		<div class="row war-margin1">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<p class="text-bold print-font-size">Name: {{ $emp_name }}</p>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<p class="text-bold print-font-size">Job Tille: {{ HrHelper::getMasterTableValueById($m, 'designation', 'designation_name', $designation_id ) }}</p>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<p class="text-bold print-font-size">Date: {{ date('d-M-Y') }} </p>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<p class="text-bold print-font-size">Location: {{ HrHelper::getMasterTableValueById($m, 'locations', 'employee_location', $branch_id ) }}</p>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<p class="text-bold print-font-size">Last day of employment: {{ HrHelper::date_format($last_working_date) }}</p>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<p class="text-bold print-font-size">EMR #: {{ $emr_no }}</p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<table class="table table-bordered warning-mar2" >
				<tbody>
				<tr>
					<td></td>
					<td class="text-center text-bold print-sett">Items</td>
					<td colspan="3" class="text-center text-bold print-sett">Status</td>
					<td class="text-center text-bold print-sett">Remarks (If any)</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td class="text-center text-bold print-sett">Yes</td>
					<td class="text-center text-bold print-sett">No</td>
					<td class="text-center text-bold print-sett">N/A</td>
					<td></td>
				</tr>
				<tr>
					<td class="text-center print-black text-bold print-sett">1</td>
					<td class="print-black text-bold print-sett">HR & Admin</td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						Room and drawer key(s) returned
					</td>
					<td class="text-center print-sett">@if($room_key == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($room_key == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($room_key == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $room_key_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Mobile Phone and Sim recovered
					</td>
					<td class="text-center print-sett">@if($mobile_sim == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($mobile_sim == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($mobile_sim == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $mobile_sim_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Fuel Card recovered
					</td>
					<td class="text-center print-sett">@if($fuel_card == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($fuel_card == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($fuel_card == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $fuel_card_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						MFM Employee Card returned
					</td>
					<td class="text-center print-sett">@if($mfm_employee_card == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($mfm_employee_card == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($mfm_employee_card == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $mfm_employee_card_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">e</td>
					<td class="print-sett">
						Client's Access Card returned
					</td>
					<td class="text-center print-sett">@if($client_access_card == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($client_access_card == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($client_access_card == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $client_access_card_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">f</td>
					<td class="print-sett">
						Medical Insurance Card recovered and Database updated
					</td>
					<td class="text-center print-sett">@if($medical_insurance_card == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($medical_insurance_card == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($medical_insurance_card == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $medical_insurance_card_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">g</td>
					<td class="print-sett">
						EOBI Card recovered and Database updated
					</td>
					<td class="text-center print-sett">@if($eobi_card == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($eobi_card == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($eobi_card == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $eobi_card_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">h</td>
					<td class="print-sett">
						Biometric Scan entry detection
					</td>
					<td class="text-center print-sett">@if($biometric_scan == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($biometric_scan == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($biometric_scan == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $biometric_scan_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">i</td>
					<td class="print-sett">
						Payroll Deduction information sent to Accounts
					</td>
					<td class="text-center print-sett">@if($payroll_deduction == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($payroll_deduction == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($payroll_deduction == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $payroll_deduction_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">j</td>
					<td class="print-sett">
						Information sent to Client if emloyee was posted at Client's site
					</td>
					<td class="text-center print-sett">@if($info_sent_to_client == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($info_sent_to_client == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($info_sent_to_client == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $info_sent_to_client_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">k</td>
					<td class="print-sett">
						Client's Exit Checklist formalities completed
					</td>
					<td class="text-center print-sett">@if($client_exit_checklist == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($client_exit_checklist == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($client_exit_checklist == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $client_exit_checklist_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">l</td>
					<td class="print-sett">
						Exit Interview with HR Manager (for Category lead & above only)
					</td>
					<td class="text-center print-sett">@if($exit_interview == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($exit_interview == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($exit_interview == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $exit_interview_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-black text-bold print-sett">2</td>
					<td class="print-black text-bold print-sett">IT & Store</td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">Laptop recovered (Model/Tag)</td>
					<td class="text-center print-sett">@if($laptop == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($laptop == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($laptop == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $laptop_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Desktop Computer recovered (Model/Tag)
					</td>
					<td class="text-center print-sett">@if($desktop_computer == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($desktop_computer == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($desktop_computer == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $desktop_computer_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Email Account and access deactivated
					</td>
					<td class="text-center print-sett">@if($email_account_deactivated == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($email_account_deactivated == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($email_account_deactivated == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $email_account_deactivated_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						Tool Kits and PPE's recovered
					</td>
					<td class="text-center print-sett">@if($toolkit_ppe == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($toolkit_ppe == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($toolkit_ppe == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $toolkit_ppe_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">e</td>
					<td class="print-sett">Complete Uniform and Shoes recovered</td>
					<td class="text-center print-sett">@if($uniform == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($uniform == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($uniform == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $uniform_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-black text-bold print-sett">3</td>
					<td class="print-black text-bold print-sett">Finance</td>
					<td class="print-black text-center print-sett"></td>
					<td class="print-black text-center print-sett"></td>
					<td class="print-black text-center print-sett"></td>
					<td class="print-black print-sett"></td>
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						Advance/Loan adjusted if any taken by the employee
					</td>
					<td class="text-center print-sett">@if($advance_loan == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($advance_loan == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($advance_loan == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $advance_loan_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Extra Leaves adjusted from final settlement
					</td>
					<td class="text-center print-sett">@if($extra_leaves == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($extra_leaves == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($extra_leaves == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $extra_leaves_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Final settlement processed
					</td>
					<td class="text-center print-sett">@if($final_settlement == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($final_settlement == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($final_settlement == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $final_settlement_remarks }}</td>
				</tr>
				</tbody>
            </table>
			</div>
		</div>

		<div class="row">&nbsp</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For Supervisor:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For HR Department:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For IT and Store:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For Finance:</b></p></div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: ______________________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: ______________________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: ______________________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: ______________________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Name: _____________________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Name: _____________________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Name: _____________________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Name: _____________________</b></p>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $(':radio:not(:checked)').attr('disabled', true);
</script>

