<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$currentDate = date('Y-m-d');

$m 	= $_GET['m'];

$designation_name = HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $designation_id, 'id');
$department_name = HrHelper::getMasterTableValueByIdAndColumn($m, 'department', 'department_name', $emp_department_id, 'id');

$id = $_GET['id'];
$emp_code = $exit_employee_data->emp_code;
$supervisor_name = $exit_employee_data->supervisor_name;
$signed_by_supervisor = $exit_employee_data->signed_by_supervisor;
$last_working_date= $exit_employee_data->last_working_date;
$leaving_type=$exit_employee_data->leaving_type;

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
$note = $exit_employee_data->note;
?>

    <style>


        input[type="radio"]{ width:30px;
            height:20px;
        }

        .name-d-d ul li {
            font-size: 17px;
            margin: 10px 0px 22px 0px;
        }

        .name-d-d-input ul li {
            margin: 7px 0px 10px 0px;
        }

        .depart-row .col-lg-3 {
            background-color: #080808;
            color: #fff;
            border-left: 1px solid #fff;
        }

        .depart-row-two .col-lg-4 {
            background-color: #999;
            color: #fff;
            border-left: 1px solid #fff;
            padding: 7px 0px 2px 0px;
        }
        .table-row-heading {
            background-color: #999;
            color: #fff;
            border-left: 1px solid #fff;
        }

    </style>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body">
                            <form method="post" action="{{url('had/editEmployeeExitClearanceDetail')}}">
                                <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                                <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
								<input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                                <div class="gudia-gap">

                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Emp Code:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                             <input readonly name="emp_code" id="emp_code" type="text" value="{{ $emp_code }}" class="form-control">
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Employee:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly name="emp_name" id="emp_name" type="text" value="{{ $employee->emp_name }}" class="form-control">
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Department:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly name="sub_department" id="sub_department" type="text" value="{{ $department_name }}" class="form-control">
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label>Designation:</label>
                                                <input readonly name="designation" id="designation" type="text" value="{{ $designation_name }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Supervisor Name:</label>
                                                <input name="supervisor_name" id="supervisor_name" type="text" value="{{ $supervisor_name }}" class="form-control">
                                            </div> 
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Last Working Date:</label>
                                                <input name="last_working_date" id="last_working_date" type="date" value="{{ $last_working_date }}" class="form-control requiredField">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label>Signed by supervisor:</label>
                                            <br>
                                            <b><input @if($signed_by_supervisor=='yes')checked @endif  value="yes" type="radio" name="signed_by_supervisor"> Yes</b> &nbsp &nbsp
                                            <b><input @if($signed_by_supervisor=='no')checked @endif  value="no" type="radio" name="signed_by_supervisor"> No</b>
                                        </div>
                                    </div>
                                    <div class="row" style="background-color: gainsboro">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <h4 style="text-decoration: underline;font-weight: bold;">Leaving Reason</h4>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">

                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        </div>
                                    </div>
                                    <span id="new">
                                        <div id="" class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <div class="form-group text-center">
                                                <label>Contract End:</label>
                                                <div class="radio">
                                                    <label><input @if($leaving_type==6)checked @endif  value="6" type="radio" name="leaving_type"></label>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <div class="form-group text-center">
                                                    <label>Resignation:</label>
                                                    <div class="radio">
                                                        <label><input @if($leaving_type==1)checked @endif value="1" type="radio" name="leaving_type"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <div class="form-group text-center">
                                                    <label>Retirement:</label>
                                                    <div class="radio">
                                                        <label><input @if($leaving_type==2)checked @endif value="2" type="radio" name="leaving_type"></label>
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <div class="form-group text-center">
                                                    <label>Termination:</label>
                                                    <div class="radio">
                                                        <label><input @if($leaving_type==3)checked @endif value="3" type="radio" name="leaving_type"></label>
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                                <div class="form-group text-center">
                                                    <label>Dismissal:</label>
                                                    <div class="radio">
                                                        <label><input @if($leaving_type==4)checked @endif value="4" type="radio" name="leaving_type"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                                <div class="form-group text-center">
                                                    <label>Demise:</label>
                                                    <div class="radio">
                                                        <label><input @if($leaving_type==5)checked @endif value="5" type="radio" name="leaving_type"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                                <div class="form-group text-center">
                                                    <label>Branch Closed:</label>
                                                    <div class="radio">
                                                        <label><input @if($leaving_type==7)checked @endif  value="7" type="radio" name="leaving_type"></label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <br>
                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <h2>CLEARANCE FROM LINE MANAGER</h2>
                                        </div>
                                         <br>

                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead class="table-row-heading">
                                                <tr>
                                                    <th scope="col" class="text-center"><h3>DEPARTMENT</h3></th>
                                                    <th scope="col" class="text-center"><h3>VERIFICATION</h3></th>
                                                    <th scope="col" class="text-center"><h3>STATUS</h3></th>
                                                    <th scope="col" class="text-center"><h3>REMARKS</h3></th>
                                                </tr>
                                                </thead>

                                                {{--hr and admin department start--}}
                                                <tbody>
                                                <tr>
                                                    <td rowspan="13" class="text-center">
                                                        <h3>HR & ADMIN <br/> DEPARTMENT</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        Room and drawer key(s) returned
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($room_key==1) checked @endif type="radio" name="room_key" value="1"> Yes </b>
                                                        <b><input @if($room_key==2) checked @endif type="radio" name="room_key" value="2"> No </b>
                                                        <b><input @if($room_key==3) checked @endif type="radio" name="room_key" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="room_key_remarks"> @if($room_key_remarks!='') {{trim($room_key_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Mobile Phone and Sim recovered
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($mobile_sim==1) checked @endif type="radio" name="mobile_sim" value="1"> Yes </b>
                                                        <b><input @if($mobile_sim==2) checked @endif type="radio" name="mobile_sim" value="2"> No </b>
                                                        <b><input @if($mobile_sim==3) checked @endif type="radio" name="mobile_sim" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="mobile_sim_remarks"> @if($mobile_sim_remarks!='') {{trim($mobile_sim_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Fuel Card recovered
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($fuel_card==1) checked @endif type="radio" name="fuel_card" value="1"> Yes </b>
                                                        <b><input @if($fuel_card==2) checked @endif type="radio" name="fuel_card" value="2"> No </b>
                                                        <b><input @if($fuel_card==3) checked @endif type="radio" name="fuel_card" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="fuel_card_remarks"> @if($fuel_card_remarks!='') {{trim($fuel_card_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        MFM Employee Card returned
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($mfm_employee_card==1) checked @endif type="radio" name="mfm_employee_card" value="1"> Yes </b>
                                                        <b><input @if($mfm_employee_card==2) checked @endif type="radio" name="mfm_employee_card" value="2"> No </b>
                                                        <b><input @if($mfm_employee_card==3) checked @endif type="radio" name="mfm_employee_card" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="mfm_employee_card_remarks"> @if($mfm_employee_card_remarks!='') {{trim($mfm_employee_card_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Client's Access Card returned
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($client_access_card==1) checked @endif type="radio" name="client_access_card" value="1"> Yes </b>
                                                        <b><input @if($client_access_card==2) checked @endif type="radio" name="client_access_card" value="2"> No </b>
                                                        <b><input @if($client_access_card==3) checked @endif type="radio" name="client_access_card" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="client_access_card_remarks"> @if($client_access_card_remarks!='') {{trim($client_access_card_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Medical Insurance Card recovered and Database updated
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($medical_insurance_card==1) checked @endif type="radio" name="medical_insurance_card" value="1"> Yes </b>
                                                        <b><input @if($medical_insurance_card==2) checked @endif type="radio" name="medical_insurance_card" value="2"> No </b>
                                                        <b><input @if($medical_insurance_card==3) checked @endif type="radio" name="medical_insurance_card" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="medical_insurance_card_remarks"> @if($medical_insurance_card_remarks!='') {{trim($medical_insurance_card_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        EOBI Card recovered and Database updated
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($eobi_card==1) checked @endif type="radio" name="eobi_card" value="1"> Yes </b>
                                                        <b><input @if($eobi_card==2) checked @endif type="radio" name="eobi_card" value="2"> No </b>
                                                        <b><input @if($eobi_card==3) checked @endif type="radio" name="eobi_card" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="eobi_card_remarks"> @if($eobi_card_remarks!='') {{trim($eobi_card_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Biometric Scan entry detection
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($biometric_scan==1) checked @endif type="radio" name="biometric_scan" value="1"> Yes </b>
                                                        <b><input @if($biometric_scan==2) checked @endif type="radio" name="biometric_scan" value="2"> No </b>
                                                        <b><input @if($biometric_scan==3) checked @endif type="radio" name="biometric_scan" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="biometric_scan_remarks"> @if($biometric_scan_remarks!='') {{trim($biometric_scan_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Payroll Deduction information sent to Accounts
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($payroll_deduction==1) checked @endif type="radio" name="payroll_deduction" value="1"> Yes </b>
                                                        <b><input @if($payroll_deduction==2) checked @endif type="radio" name="payroll_deduction" value="2"> No </b>
                                                        <b><input @if($payroll_deduction==3) checked @endif type="radio" name="payroll_deduction" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="payroll_deduction_remarks"> @if($payroll_deduction_remarks!='') {{trim($payroll_deduction_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Information sent to Client if emloyee was posted at Client's site
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($info_sent_to_client==1) checked @endif type="radio" name="info_sent_to_client" value="1"> Yes </b>
                                                        <b><input @if($info_sent_to_client==2) checked @endif type="radio" name="info_sent_to_client" value="2"> No </b>
                                                        <b><input @if($info_sent_to_client==3) checked @endif type="radio" name="info_sent_to_client" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="info_sent_to_client_remarks"> @if($info_sent_to_client_remarks!='') {{trim($info_sent_to_client_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Client's Exit Checklist formalities completed
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($client_exit_checklist==1) checked @endif type="radio" name="client_exit_checklist" value="1"> Yes </b>
                                                        <b><input @if($client_exit_checklist==2) checked @endif type="radio" name="client_exit_checklist" value="2"> No </b>
                                                        <b><input @if($client_exit_checklist==3) checked @endif type="radio" name="client_exit_checklist" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="client_exit_checklist_remarks"> @if($client_exit_checklist_remarks!='') {{trim($client_exit_checklist_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Exit Interview with HR Manager (for Category lead & above only)
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($exit_interview==1) checked @endif type="radio" name="exit_interview" value="1"> Yes </b>
                                                        <b><input @if($exit_interview==2) checked @endif type="radio" name="exit_interview" value="2"> No </b>
                                                        <b><input @if($exit_interview==3) checked @endif type="radio" name="exit_interview" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="exit_interview_remarks"> @if($exit_interview_remarks!='') {{trim($exit_interview_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                {{--hr and admin department end --}}

                                                                                        {{--it and store department start--}}
                                                <tbody>
                                                <tr>
                                                    <td rowspan="6" class="text-center">
                                                        <h3>IT & STORE <br/> DEPARTMENT</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        Laptop recovered (Model/Tag)
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($laptop==1) checked @endif type="radio" name="laptop" value="1"> Yes </b>
                                                        <b><input @if($laptop==2) checked @endif type="radio" name="laptop" value="2"> No </b>
                                                        <b><input @if($laptop==3) checked @endif type="radio" name="laptop" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="laptop_remarks"> @if($laptop_remarks!='') {{trim($laptop_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Desktop Computer recovered (Model/Tag)
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($desktop_computer==1) checked @endif type="radio" name="desktop_computer" value="1"> Yes </b>
                                                        <b><input @if($desktop_computer==2) checked @endif type="radio" name="desktop_computer" value="2"> No </b>
                                                        <b><input @if($desktop_computer==3) checked @endif type="radio" name="desktop_computer" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="desktop_computer_remarks"> @if($desktop_computer_remarks!='') {{trim($desktop_computer_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Email Account and access deactivated
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($email_account_deactivated==1) checked @endif type="radio" name="email_account_deactivated" value="1"> Yes </b>
                                                        <b><input @if($email_account_deactivated==2) checked @endif type="radio" name="email_account_deactivated" value="2"> No </b>
                                                        <b><input @if($email_account_deactivated==3) checked @endif type="radio" name="email_account_deactivated" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="email_account_deactivated_remarks"> @if($email_account_deactivated_remarks!='') {{trim($email_account_deactivated_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Tool Kits and PPE's recovered
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($toolkit_ppe==1) checked @endif type="radio" name="toolkit_ppe" value="1"> Yes </b>
                                                        <b><input @if($toolkit_ppe==2) checked @endif type="radio" name="toolkit_ppe" value="2"> No </b>
                                                        <b><input @if($toolkit_ppe==3) checked @endif type="radio" name="toolkit_ppe" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="toolkit_ppe_remarks"> @if($toolkit_ppe_remarks!='') {{trim($toolkit_ppe_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Complete Uniform and Shoes recovered
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($uniform==1) checked @endif type="radio" name="uniform" value="1"> Yes </b>
                                                        <b><input @if($uniform==2) checked @endif type="radio" name="uniform" value="2"> No </b>
                                                        <b><input @if($uniform==3) checked @endif type="radio" name="uniform" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="uniform_remarks"> @if($uniform_remarks!='') {{trim($uniform_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                {{--it and store department end--}}

                                                                                        {{--finance department start--}}
                                                <tbody>
                                                <tr>
                                                    <td rowspan="4" class="text-center">
                                                        <h3>FINANCE <br/> DEPARTMENT</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        Advance/Loan adjusted if any taken by the employee
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($advance_loan==1) checked @endif type="radio" name="advance_loan" value="1"> Yes </b>
                                                        <b><input @if($advance_loan==2) checked @endif type="radio" name="advance_loan" value="2"> No </b>
                                                        <b><input @if($advance_loan==3) checked @endif type="radio" name="advance_loan" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="advance_loan_remarks"> @if($advance_loan_remarks!='') {{trim($advance_loan_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Extra Leaves adjusted from final settlement
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($extra_leaves==1) checked @endif type="radio" name="extra_leaves" value="1"> Yes </b>
                                                        <b><input @if($extra_leaves==2) checked @endif type="radio" name="extra_leaves" value="2"> No </b>
                                                        <b><input @if($extra_leaves==3) checked @endif type="radio" name="extra_leaves" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="extra_leaves_remarks"> @if($extra_leaves_remarks!='') {{trim($extra_leaves_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        Final settlement processed
                                                    </td>
                                                    <td class="text-center">
                                                        <b><input @if($final_settlement==1) checked @endif type="radio" name="final_settlement" value="1"> Yes </b>
                                                        <b><input @if($final_settlement==2) checked @endif type="radio" name="final_settlement" value="2"> No </b>
                                                        <b><input @if($final_settlement==3) checked @endif type="radio" name="final_settlement" value="3"> N/A </b>
                                                    </td>
                                                    <td class="text-center">
                                                        <textarea class="form-control" rows="2" name="final_settlement_remarks"> @if($final_settlement_remarks!='') {{trim($final_settlement_remarks)}} @endif </textarea>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                {{--finance department end--}}

                                            </table>
                                        </div>
                                        <div>
                                            <label for=""> Note :</label><br>
                                            <textarea style="max-width: 100%" class="form-control" name="note" id="note" >@if($note!='') {{trim($note)}} @endif</textarea>
                                        </div>
                                    </span>
									
									<br>
                                    <div style="float: right;">
                                        <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Update</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	

