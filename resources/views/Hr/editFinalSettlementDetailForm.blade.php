<?php

$accType = Auth::user()->acc_type;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];
$currentDate = date('Y-m-d');

$designation_name = HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $designation_id, 'id');
$region = HrHelper::getMasterTableValueByIdAndColumn($m, 'regions', 'employee_region', $employee->region_id, 'id');
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
$gratuity = $gratuityAmount;
$others = $final_settlement['others'];
$notice_pay = $final_settlement['notice_pay'];
$advance = $final_settlement['advance'];
$mobile_bill = $final_settlement['mobile_bill'];
$toolkit = $final_settlement['toolkit'];
$mfm_id_card = $final_settlement['mfm_id_card'];
$uniform = $final_settlement['uniform'];
$laptop = $final_settlement['laptop'];
$any_others = $final_settlement['any_others'];


?>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body">
                        <form method="post" action="{{url('had/editFinalSettlementDetail')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="{{ $m }}">
                            <input type="hidden" name="id" id="id" value={{ $id }}>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Designation:</label>
                                        <input readonly name="designation" id="designation" type="text" value="{{ $designation_name }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>DOJ:</label>
                                        <input readonly name="emp_joining_date" id="emp_joining_date" type="date" value="{{ $emp_joining_date }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Last Working Data:</label>
                                        <input readonly name="last_working_date" id="last_working_date" type="date" value="{{ $last_working_date }}" class="form-control requiredField"/>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Reason Of Release:</label>
                                        <input readonly name="leaving_type" id="leaving_type" type="text" value="{{ $leaving_type }}" class="form-control requiredField"/>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Last Salary Rate:</label>
                                        <input readonly name="emp_salary" id="emp_salary" type="text" value="{{ $emp_salary }}" class="form-control requiredField"/>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Regiom:</label>
                                        <input readonly name="region_id" id="region_id" type="text" value="{{ $region }}" class="form-control requiredField"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Salary From</label>
                                        <input type="date" name="salary_from" id="salary_from" value="{{ $salary_from }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Salary To</label>
                                        <input type="date" name="salary_to" id="salary_to" value="{{ $salary_to }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Gratuity</label>
                                        <input readonly type="number" name="gratuity" id="gratuity" value="{{ $gratuity }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Others</label>
                                        <input type="number" name="others" id="others" value="{{ $others }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Notice Pay</label>
                                        <input type="number" name="notice_pay" id="notice_pay" value="{{ $notice_pay }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Advance</label>
                                        <input type="number" name="advance" id="advance" value="{{ $advance }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Mobile Bill</label>
                                        <input type="number" name="mobile_bill" id="mobile_bill" value="{{ $mobile_bill }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Tool Kit</label>
                                        <input type="number" name="toolkit" id="toolkit" value="{{ $toolkit }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>MFM ID Card</label>
                                        <input type="number" name="mfm_id_card" id="mfm_id_card" value="{{ $mfm_id_card }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Uniform</label>
                                        <input type="number" name="uniform" id="uniform" value="{{ $uniform }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Laptop</label>
                                        <input type="number" name="laptop" id="laptop" value="{{ $laptop }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Any Others</label>
                                        <input type="number" name="any_others" id="any_others" value="{{ $any_others }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div style="float: right;">
                                <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




