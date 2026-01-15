<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if ($count==1):
echo $issue_date=$exit_employe_data->issue_date;
echo '*';
echo $dose=$exit_employe_data->dos;
echo '*';
$leaving_type=$exit_employe_data->leaving_type;
$relieved_duties=$exit_employe_data->relieved_duties;
$item_returned=$exit_employe_data->item_returned;
$transaction_completed_concern=$exit_employe_data->transaction_completed_concern;
$relieved_duties_remarks=$exit_employe_data->relieved_duties_remarks;
$item_returned_remarks=$exit_employe_data->item_returned_remarks;
$transaction_completed_remarks_concern=$exit_employe_data->transaction_completed_remarks_concern;
$working_condition=$exit_employe_data->working_condition;
$email_account_closed=$exit_employe_data->email_account_closed;
$transaction_completed_it=$exit_employe_data->transaction_completed_it;
$working_condition_remarks=$exit_employe_data->working_condition_remarks;
$email_account_closed_remarks=$exit_employe_data->email_account_closed_remarks;
$transaction_completed_it_remarks=$exit_employe_data->transaction_completed_it_remarks;
$salary_adjusted=$exit_employe_data->salary_adjusted;
$Loan_settled=$exit_employe_data->Loan_settled;
$transaction_completed_finance=$exit_employe_data->transaction_completed_finance;
$salary_adjusted_remarks=$exit_employe_data->salary_adjusted_remarks;
$Loan_settled_remarks=$exit_employe_data->Loan_settled_remarks;
$transaction_completed_finance_remarks=$exit_employe_data->transaction_completed_finance_remarks;
$office_material=$exit_employe_data->office_material;
$received_company_assets=$exit_employe_data->received_company_assets;
$transaction_completed_administration=$exit_employe_data->transaction_completed_administration;
$office_material_remarks=$exit_employe_data->office_material_remarks;
$received_company_assets_remarks=$exit_employe_data->received_company_assets_remarks;
$transaction_completed_administration_remarks=$exit_employe_data->transaction_completed_administration_remarks;
$office_card=$exit_employe_data->office_card;
$balance_leav_annual=$exit_employe_data->balance_leav_annual;
$balance_leav_casual=$exit_employe_data->balance_leav_casual;
$balance_leav_sick=$exit_employe_data->balance_leav_sick;
$transaction_completed_hr=$exit_employe_data->transaction_completed_hr;
$office_card_remarks=$exit_employe_data->office_card_remarks;
$balance_leav_remarks=$exit_employe_data->balance_leav_remarks;
$transaction_completed_hr_remarks=$exit_employe_data->transaction_completed_hr_remarks;
else:


      echo   $issue_date='';echo '*';
    echo    $dos='';echo '*';
        $leaving_type='';
        $relieved_duties='';
        $item_returned='';
        $transaction_completed_concern='';
        $relieved_duties_remarks='';
        $item_returned_remarks='';
        $transaction_completed_remarks_concern='';
        $working_condition='';
        $email_account_closed='';
        $transaction_completed_it='';
        $working_condition_remarks='';
        $email_account_closed_remarks='';
        $transaction_completed_it_remarks='';
        $salary_adjusted='';
        $Loan_settled='';
        $transaction_completed_finance='';
        $salary_adjusted_remarks='';
        $Loan_settled_remarks='';
        $transaction_completed_finance_remarks='';
        $office_material='';
        $received_company_assets='';
        $transaction_completed_administration='';
        $office_material_remarks='';
        $received_company_assets_remarks='';
        $transaction_completed_administration_remarks='';
        $office_card='';
        $balance_leav_annual='';
        $balance_leav_casual='';
        $balance_leav_sick='';
        $transaction_completed_hr='';
        $office_card_remarks='';
        $balance_leav_remarks='';
        $transaction_completed_hr_remarks='';
    endif;

?>







        <div class="container gudia-gap">

<input type="hidden" name="save_update" value="<?php echo $count; ?>">

            @if($count==1)
            <input type="hidden" name="emp_id" value="<?php echo $exit_employe_data->emp_id; ?>">
            @endif
            <div class="row">

                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group text-center">
                        <label>Resignation:</label>
                        <div class="radio">
                            <label><input @if($leaving_type==1)checked @endif value="1" type="radio" name="leaving_type"></label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group text-center">
                        <label>Retirement:</label>
                        <div class="radio">
                            <label><input @if($leaving_type==2)checked @endif  value="2" type="radio" name="leaving_type"></label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group text-center">
                        <label>Termination:</label>
                        <div class="radio">
                            <label><input @if($leaving_type==3)checked @endif  value="3" type="radio" name="leaving_type"></label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group text-center">
                        <label>Dismissal:</label>
                        <div class="radio">
                            <label><input @if($leaving_type==4)checked @endif  value="4" type="radio" name="leaving_type"></label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group text-center">
                        <label>Demise:</label>
                        <div class="radio">
                            <label><input @if($leaving_type==5)checked @endif  value="5" type="radio" name="leaving_type"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-center">
                <div class="depart-row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <h4>DEPARTMENT</h4>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <h4>VERIFICATION</h4>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <h4>Yes / No</h4>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <h4>REMARKS</h4>
                    </div>
                </div>

            </div>

            <!--  CONCERNED DEPARTMENT BEGIN-->
            <div class="row concerned-department">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center title-center">
                    <h3>CONCERNED <br/> DEPARTMENT</h3>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <ul>
                        <li>May be relieved of his/her duties</li>
                        <br/>
                        <hr>
                        <li>Any item to be returned (details)</li>
                        <br/>
                        <hr>
                        <li>Any transaction to be completed (details)</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">



                    <label class="radio-inline"><input @if($relieved_duties==1) checked @endif type="radio"  name="relieved_duties" value="1">Yes</label>
                    <label class="radio-inline"><input @if($relieved_duties==2) checked @endif type="radio" name="relieved_duties" value="2">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>
                    <label class="radio-inline"><input @if($item_returned==1) checked @endif type="radio" value="1" name="item_returned">Yes</label>
                    <label class="radio-inline"><input @if($item_returned==2) checked @endif type="radio" value="2" name="item_returned">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>
                    <label class="radio-inline"><input @if($transaction_completed_concern==1) checked @endif type="radio" value="1" name="transaction_completed_concern">Yes</label>
                    <label class="radio-inline"><input @if($transaction_completed_concern==2) checked @endif type="radio" value="2" name="transaction_completed_concern">No</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <textarea class="form-control" rows="2" name="relieved_duties_remarks">@if($relieved_duties_remarks!=''){{trim($relieved_duties_remarks)}}@endif</textarea>
                    <br />


                    <textarea class="form-control"   name="item_returned_remarks" rows="2">@if($item_returned_remarks!='') {{trim($item_returned_remarks)}} @endif</textarea>


                    <br />
                    <textarea class="form-control" name="transaction_completed_remarks_concern" rows="2">@if($transaction_completed_remarks_concern!=''){{trim($transaction_completed_remarks_concern)}} @endif</textarea>
                </div>
            </div>
            <div class="row text-center">
                <div class="depart-row-two">
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">

                        <h4>Head of Department</h4>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                        <h4>Signature:</h4>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">


                        <div class="col-lg-5 col-md-5 col-sm-6">
                            <h4>Date:</h4>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-6">
                            <input readonly type="date" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
            <!--  CONCERNED DEPARTMENT END-->

            <!--INFORMATION TECHNOLOGY BEGIN-->
            <div class="row concerned-department">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center title-center">
                    <h3>INFORMATION <br/> TECHNOLOGY</h3>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <ul>
                        <li>P.C/Laptop/Printer received in proper working condition</li>
                        <br/>
                        <hr>
                        <li>E-mail account closed</li>
                        <br/>
                        <hr>
                        <li>Any transaction to be completed (details)</li>
                    </ul>
                </div>

               
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">

                    <label class="radio-inline"><input type="radio" @if($working_condition==1) checked @endif value="1" name="working_condition">Yes</label>
                    <label class="radio-inline"><input type="radio" @if($working_condition=2) checked @endif value="2" name="working_condition">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>
                    <label class="radio-inline"><input type="radio" @if($email_account_closed==1) checked @endif value="1" name="email_account_closed">Yes</label>
                    <label class="radio-inline"><input type="radio" @if($email_account_closed==2) checked @endif value="2" name="email_account_closed">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>
                    <label class="radio-inline"><input type="radio" @if($transaction_completed_it==1) checked @endif value="1" name="transaction_completed_it">Yes</label>
                    <label class="radio-inline"><input type="radio" @if($transaction_completed_it==2) checked @endif value="2" name="transaction_completed_it">No</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <textarea class="form-control" name="working_condition_remarks" rows="2">@if($working_condition_remarks!='') {{trim($working_condition_remarks)}} @endif</textarea>
                    <br />
                    <textarea class="form-control" name="email_account_closed_remarks" rows="2">@if($email_account_closed_remarks!='') {{trim($email_account_closed_remarks)}} @endif</textarea>
                    <br />
                    <textarea class="form-control" name="transaction_completed_it_remarks" rows="2">@if($item_returned_remarks!='') {{trim($item_returned_remarks)}} @endif</textarea>
                </div>
            </div>
            <div class="row text-center">
                <div class="depart-row-two">
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">

                        <h4>Head of Department</h4>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                        <h4>Signature:</h4>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">


                        <div class="col-lg-5 col-md-5 col-sm-6">
                            <h4>Date:</h4>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-6">
                            <input readonly type="date" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
            <!--INFORMATION TECHNOLOGY END-->

            <!--FINANCE BEGIN-->
            <div class="row concerned-department">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center title-center">
                    <h3>FINANCE</h3>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <ul>
                        <li>Advance against salary adjusted </li>
                        <br/>
                        <hr>
                        <li>Loan settled</li>
                        <br/>
                        <hr>
                        <li>Any transaction to be completed (details)</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">

                    <label class="radio-inline"><input type="radio"  @if($salary_adjusted==1) checked @endif value="1" name="salary_adjusted">Yes</label>
                    <label class="radio-inline"><input type="radio"  @if($salary_adjusted==2) checked @endif value="2" name="salary_adjusted">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>
                    <label class="radio-inline"><input type="radio"  @if($Loan_settled==1) checked @endif value="1" name="Loan_settled">Yes</label>
                    <label class="radio-inline"><input type="radio"  @if($Loan_settled==2) checked @endif value="2" name="Loan_settled">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>
                    <label class="radio-inline"><input type="radio"  @if($transaction_completed_finance==1) checked @endif value="1" name="transaction_completed_finance">Yes</label>
                    <label class="radio-inline"><input type="radio"  @if($transaction_completed_finance==2) checked @endif value="2" name="transaction_completed_finance">No</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <textarea class="form-control" name="salary_adjusted_remarks" rows="2">@if($salary_adjusted_remarks!='') {{trim($salary_adjusted_remarks)}} @endif</textarea>
                    <br />
                    <textarea class="form-control" name="Loan_settled_remarks" rows="2">@if($Loan_settled_remarks!='') {{trim($Loan_settled_remarks)}} @endif</textarea>
                    <br />
                    <textarea class="form-control" name="transaction_completed_finance_remarks" rows="2">@if($transaction_completed_finance_remarks!='') {{trim($transaction_completed_finance_remarks)}} @endif</textarea>
                </div>
            </div>
            <div class="row text-center">
                <div class="depart-row-two">
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">

                        <h4>Head of Department</h4>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                        <h4>Signature:</h4>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">


                        <div class="col-lg-5 col-md-5 col-sm-6">
                            <h4>Date:</h4>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-6">
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
            <!--FINANCE END-->

            <!--ADMINISTRATION BEGIN-->
            <div class="row concerned-department">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center title-center">
                    <h3>ADMINISTRATION</h3>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <ul>
                        <li>Received office goods/material, if any (Calculator, Fax Machine etc) </li>
                        <br/>
                        <hr>
                        <li>Received Car, M/Cycle, Mobile Phone in good condition</li>
                        <br/>
                        <hr>
                        <li>Any transaction to be completed (details)</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">

                    <label class="radio-inline"><input type="radio" @if($office_material==1) checked @endif value="1" name="office_material">Yes</label>
                    <label class="radio-inline"><input type="radio" @if($office_material==2) checked @endif value="2" name="office_material">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>
                    <label class="radio-inline"><input type="radio" @if($received_company_assets==1) checked @endif value="1" name="received_company_assets">Yes</label>
                    <label class="radio-inline"><input type="radio" @if($received_company_assets==2) checked @endif value="2" name="received_company_assets">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>
                    <label class="radio-inline"><input type="radio" value="1" @if($transaction_completed_administration==1) checked @endif name="transaction_completed_administration">Yes</label>
                    <label class="radio-inline"><input type="radio" value="2" @if($transaction_completed_administration==2) checked @endif name="transaction_completed_administration">No</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <textarea class="form-control" name="office_material_remarks" rows="2">@if($salary_adjusted_remarks!='') {{trim($office_material_remarks)}} @endif</textarea>
                    <br />
                    <textarea class="form-control" name="received_company_assets_remarks" rows="2">@if($salary_adjusted_remarks!='') {{trim($received_company_assets_remarks)}} @endif</textarea>
                    <br />
                    <textarea class="form-control" name="transaction_completed_administration_remarks" rows="2">@if($salary_adjusted_remarks!='') {{trim($transaction_completed_administration_remarks)}} @endif</textarea>
                </div>
            </div>
            <div class="row text-center">
                <div class="depart-row-two">
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">

                        <h4>Head of Department</h4>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                        <h4>Signature:</h4>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">


                        <div class="col-lg-5 col-md-5 col-sm-6">
                            <h4>Date:</h4>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-6">
                            <input readonly type="date" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
            <!--ADMINISTRATION END-->

            <!--HUMAN RESOURCE BEGIN-->
            <div class="row concerned-department">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center title-center">
                    <h3>HUMAN <br/> RESOURCE</h3>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <ul>
                        <li>Received Office card (if any)</li>
                        <br/>
                        <hr>
                        <li>Balance of earned leave</li>
                        <br/>
                        <hr>
                        <li>Any transaction to be completed (details)</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">

                    <label class="radio-inline"><input type="radio" @if($office_card==1) checked @endif value="1" name="office_card">Yes</label>
                    <label class="radio-inline"><input type="radio" @if($office_card==1) checked @endif value="2" name="office_card">No</label>
                    <div class="clearfix"></div>
                    <br/>
                    <hr>

                    <div class="col-lg-4 col-md-4">
                        <div class="">
                            <label>ANNUAL:</label>
                            <select name="balance_leav_annual" class="form-control">
                               @for($i=1; $i<=9; $i++)
                                   <option @if($balance_leav_annual==$i)selected @endif; value="{{$i}}">{{$i}}</option>
                                   @endfor;
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="">
                            <label>CAUSAL:</label>
                            <select name="balance_leav_casual" class="form-control">
                                @for($i=1; $i<=9; $i++)
                                    <option @if($balance_leav_casual==$i)selected @endif; value="{{$i}}">{{$i}}</option>
                                @endfor;
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="">
                            <label>SICK:</label>
                            <select name="balance_leav_sick" class="form-control">
                                @for($i=1; $i<=9; $i++)
                                    <option @if($balance_leav_sick==$i)selected @endif; value="{{$i}}">{{$i}}</option>
                                @endfor;
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <hr>
                    <label class="radio-inline"><input type="radio" @if($transaction_completed_hr==1) checked @endif  value="1" name="transaction_completed_hr">Yes</label>
                    <label class="radio-inline"><input type="radio" @if($transaction_completed_hr==2) checked @endif  value="2" name="transaction_completed_hr">No</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <textarea class="form-control" name="office_card_remarks" rows="2">@if($office_card_remarks!='') {{trim($office_card_remarks)}} @endif</textarea>
                    <br />
                    <textarea class="form-control" name="balance_leav_remarks" rows="2">@if($balance_leav_remarks!='') {{trim($balance_leav_remarks)}} @endif</textarea>
                    <br />
                    <textarea class="form-control" name="transaction_completed_hr_remarks" rows="2">@if($transaction_completed_hr_remarks!='') {{trim($transaction_completed_hr_remarks)}} @endif</textarea>
                </div>
            </div>
            <div class="row text-center">
                <div class="depart-row-two">
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">

                        <h4>Head of Department</h4>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                        <h4>Signature:</h4>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">


                        <div class="col-lg-5 col-md-5 col-sm-6">
                            <h4>Date:</h4>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-6">
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
            <!--HUMAN RESOURCE END-->

            <div class="row gudia-gap">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4>Managing Director_________________</h4>
                </div>
            </div>



        </div>





