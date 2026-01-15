<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
?>

@extends('layouts.default')
@section('content')
    <style>
        .fileContainer {
            overflow: hidden;
            position: relative;
        }

        .fileContainer [type=file] {
            cursor: inherit;
            display: block;
            font-size: 999px;
            filter: alpha(opacity=0);
            min-height: 100%;
            min-width: 100%;
            opacity: 0;
            position: absolute;
            right: 0;
            text-align: right;
            top: 0;
        }


        .fileContainer {
            color:#fff;
            background: #3071a9;
            border-radius: .5em;
            float: left;
            padding: 2px;
        }

        .fileContainer [type=file] {
            cursor: pointer;
        }


        hr{border-top: 1px solid cadetblue}

        td{ padding: 0px !important;}
        th{ padding: 0px !important;}

        .img-circle {width: 150px;
            height: 150px;
            border: 2px solid #ccc;
            padding: 4px;
            border-radius: 50%;
            margin-bottom: 32px;
            margin-top: -78px;
            z-index: 10000000;}
    </style>

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Edit Employee Detail Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeDetail','','1');?>
                                        </div>
                                        <div class="row" id="PrintEmployeeDetail">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                <div class="hr-border" style="border: 1px solid #e5e5e5b0; margin-top: 89px;"></div>
                                                <img class="img-circle" src="<?= Storage::url($employee_detail->img_path)?>">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th>Emp Code</th>
                                                        <td><?=$employee_detail->emp_code;?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Name</th>
                                                        <td><?=$employee_detail->emp_name?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp S/O,D/O,W/O</th>
                                                        <td><?=$employee_detail->so_wo_do;?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp S/O,D/O,W/O (Name)</th>
                                                        <td><?=$employee_detail->emp_father_name;?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Designation</th>
                                                        <td>
                                                            <?php
                                                            echo CommonHelper::getMasterTableValueById($m,'designation','designation_name',$employee_detail->designation_id);
                                                            ?>
                                                        </td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp DOB</th>
                                                        <td><?=HrHelper::date_format($employee_detail->emp_date_of_birth)?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Gender</th>
                                                        <td>
                                                        <?php switch ($employee_detail->emp_gender){ case "1": echo "Male";break;case "2": echo "Female";break;}?>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Department / Subdepartment</th>
                                                        <td>
                                                            <?php
                                                            echo CommonHelper::getMasterTableValueById($m,'department','department_name',$employee_detail->emp_department_id);

                                                            ?>

                                                        </td>
                                                        </thead>
                                                        <thead>
                                                        <th>Company</th>
                                                        <td>
                                                            @if($employee_detail->company == '1')
                                                                {{ 'Gudia Pvt Ltd.' }}
                                                            @elseif($employee_detail->company == '2')
                                                                {{ 'Gudia Packaging' }}
                                                            @elseif($employee_detail->company == '3')
                                                                {{ 'Gudia International' }}
                                                            @else
                                                                {{ 'Gudia Corporation' }}
                                                            @endif

                                                        </td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Email</th>
                                                        <td><?=$employee_detail->emp_email?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Nationality</th>
                                                        <td><?=$employee_detail->nationality?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp No.Of Dependants</th>
                                                        <td><?=$employee_detail->no_of_dependants?></td>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th>Emp. CNIC</th>
                                                        <td><?=$employee_detail->emp_cnic?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp. CNIC Issue Date</th>
                                                        <td><?=HrHelper::date_format($employee_detail->cnic_issue_date)?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp. CNIC Expiry Date</th>
                                                        <td><?=HrHelper::date_format($employee_detail->cnic_expiry_date)?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp. Contact No</th>
                                                        <td><?=$employee_detail->emp_contact_no?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp. Landline No</th>
                                                        <td><?=$employee_detail->landline?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp. Salary</th>
                                                        <td><?=$employee_detail->emp_salary?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Joining Date</th>
                                                        <td><?=HrHelper::date_format($employee_detail->emp_joining_date)?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Marital Status</th>
                                                        <td><?php
                                                            echo CommonHelper::getMasterTableValueById($m,'marital_status','marital_status_name',$employee_detail->emp_marital_status);
                                                            ?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Division</th>
                                                        <td>
                                                            @if($employee_detail->division == '1')
                                                                {{ 'Raw Material' }}
                                                            @elseif($employee_detail->division == '2')
                                                                {{ 'Packaging' }}
                                                            @elseif($employee_detail->division == '3')
                                                                {{ 'Machinery' }}
                                                            @else
                                                                {{ 'Allied Food Products' }}
                                                            @endif

                                                        </td>
                                                        </thead>
                                                        <thead>
                                                        <th>Emp Grade</th>
                                                        <td><?=$employee_detail->emp_grade?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Created At</th>
                                                        <td class="text-center"><?=$employee_detail->time?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Created By</th>
                                                        <td><?=$employee_detail->username?></td>
                                                        </thead>

                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th class="text-center"> Residential Address</th>
                                                        </thead>
                                                        <thead>
                                                        <td class="text-center"> <?=$employee_detail->residential_address?></td>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th class="text-center"> Permanent Address</th>
                                                        </thead>
                                                        <thead>
                                                        <td class="text-center"> <?=$employee_detail->permanent_address?></td>
                                                        </thead>
                                                    </table>
                                                </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row" style="background-color: gainsboro">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h4 style="text-decoration: underline;">Qualifications</h4>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h4 style="text-decoration: underline;">Start from Recent</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th class="text-center col-sm-1">S.No</th>
                                                        <th class="text-center">Type Of Degree</th>
                                                        <th class="text-center">School/College/University</th>
                                                        <th class="text-center">Year Of Passing</th>
                                                        <th class="text-center">Grade/Division CGPA%</th>
                                                        </thead>
                                                        <tbody id="insert_clone">
                                                        <?php $count1= 0; ?>
                                                        @foreach($EmployeeQualification as $EmployeeQualificationValue)
                                                            <?php $count1++ ?>
                                                            <tr class="get_rows" id='remove_area_<?=$count1?>'>
                                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $count1 }}</span></td>
                                                                <td id="get_clone" class="text-center"><?= HrHelper::getMasterTableValueById($m,'degree_type','degree_type_name',$EmployeeQualificationValue->degree_type) ?></td>
                                                                <td class="text-center"><?= $EmployeeQualificationValue->school_college_university?></td>
                                                                <td class="text-center"><?= HrHelper::date_format($EmployeeQualificationValue->year_of_passing)?></td>
                                                                <td class="text-center"><?= $EmployeeQualificationValue->grade_div_cgpa?></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="row">&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th class="text-center">
                                                            <b>Please list your areas of highest proficiency, special skills
                                                                or other items that may contribute to your abilities in performing
                                                                the above mentioned position.
                                                            </b>
                                                        </th>
                                                        </thead>
                                                        <thead>
                                                            <td class="text-center"> <?=$employee_detail->special_skills?></td>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">&nbsp;</div>
                                            <div class="row">&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row" style="background-color: gainsboro">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h4 style="text-decoration: underline;">Work Experience</h4>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h4 style="text-decoration: underline;">Start from Recent</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th class="text-center col-sm-1">S.No</th>
                                                        <th class="text-center">Name Of Employeer</th>
                                                        <th class="text-center">Position Held</th>
                                                        <th class="text-center">Started</th>
                                                        <th class="text-center">Ended</th>
                                                        <th class="text-center">Reason For Leaving</th>
                                                        </thead>
                                                        <tbody id="insert_clone1">
                                                        <?php $count2=0; ?>
                                                        @foreach($employeeWorkExperience as $employeeWorkExperienceValue)
                                                            <?php $count2++ ?>
                                                            <tr class="get_rows1" id='remove_area1_<?=$count2?>'>
                                                                <td class="text-center"><span class="badge badge-pill badge-secondary"><?=$count2?></span></td>
                                                                <td id="get_clone1" class="text-center"><?=$employeeWorkExperienceValue->employeer_name?></td>
                                                                <td class="text-center"><?=$employeeWorkExperienceValue->position_held?></td>
                                                                <td class="text-center"><?=HrHelper::date_format($employeeWorkExperienceValue->started)?></td>
                                                                <td class="text-center"><?=HrHelper::date_format($employeeWorkExperienceValue->ended)?></td>
                                                                <td class="text-center"><?=$employeeWorkExperienceValue->reason_leaving?></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="row">&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row" style="background-color: gainsboro">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <h4 style="text-decoration: underline;">Salary Detail</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                            <th>Last Drawn Salary</th>
                                                            <td><?= $salary_detail->last_drawn_salary ?></td>
                                                        </thead>
                                                        <thead>
                                                            <th>Last Drawn Benefits</th>
                                                            <td><?= $salary_detail->last_drawn_benefits ?></td>
                                                        </thead>
                                                        <thead>
                                                            <th>Expected Salary</th>
                                                            <td><?= $salary_detail->expected_salary ?></td>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th>Expected Benefits</th>
                                                        <td><?= $salary_detail->expected_benefits ?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Notice Period</th>
                                                        <td><?= $salary_detail->notice_period ?></td>
                                                        </thead>
                                                        <thead>
                                                        <th>Possible Date of Joining</th>
                                                        <td><?= HrHelper::date_format($salary_detail->possbile_doj) ?></td>
                                                        </thead>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">&nbsp;</div>
                                                <div class="row" style="background-color: gainsboro">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <h4 style="text-decoration: underline;">Reference</h4>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-left">
                                                        <p style="text-decoration: underline;">
                                                            Professional/Business references only
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-left">
                                                        <p style="text-decoration: underline;">
                                                            Please include reference from your past employment
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">

                                                    <div class="row" id="insert_clone2">
                                                        @foreach($EmployeeReference as $EmployeeReferenceValue)
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <table class="table table-bordered sf-table-list">
                                                                    <thead>
                                                                        <th>Name</th>
                                                                        <td><?=$EmployeeReferenceValue->reference_name?></td>
                                                                    </thead>
                                                                    <thead>
                                                                        <th>Designation</th>
                                                                        <td><?=$EmployeeReferenceValue->reference_designation?></td>
                                                                    </thead>
                                                                    <thead>
                                                                        <th>Organization</th>
                                                                        <td><?=$EmployeeReferenceValue->reference_organization?></td>
                                                                    </thead>
                                                                    <thead>
                                                                        <th>Address</th>
                                                                        <td><?=$EmployeeReferenceValue->reference_address?></td>
                                                                    </thead>
                                                                    <thead>
                                                                        <th>Country</th>
                                                                        <td><?=$EmployeeReferenceValue->reference_country?></td>
                                                                    </thead>
                                                                    <thead>
                                                                        <th>Contact Number</th>
                                                                        <td><?=$EmployeeReferenceValue->reference_contact?>
                                                                    </td>
                                                                    </thead>
                                                                    <thead>
                                                                        <th>Relationship</th>
                                                                        <td><?=$EmployeeReferenceValue->reference_relationship	?></td>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>

                                                <div class="row">&nbsp;</div>
                                                @if($employee_detail->can_login == 'yes')
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: gainsboro">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <h4 style="text-decoration: underline;">Login Credentials</h4>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <h4 style="text-decoration: underline;">Can Login ? : <span style="color:green;">YES</span></h4>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered sf-table-list">
                                                                    <thead>
                                                                    <th class="text-center">Account Type</th>
                                                                    <td class="text-center">{{ $currentuser->acc_type }}</td>
                                                                    </thead>

                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered sf-table-list">
                                                                    <thead>
                                                                    <th class="text-center">Username</th>
                                                                    <td class="text-center">{{ $currentuser->username }}</td>
                                                                    </thead>

                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered sf-table-list">
                                                                    <thead>
                                                                    <th class="text-center">Password</th>
                                                                    <td class="text-center">{{ '****' }}</td>
                                                                    </thead>

                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @else
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: gainsboro">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <h4 style="text-decoration: underline;">Login Credentials</h4>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <h4 style="text-decoration: underline;">Can Login ? : <span style="color:red;">NO</span></h4>
                                                        </div>
                                                    </div>

                                                @endif
                                                <div class="row">&nbsp;</div>

                                                @if($eobi)
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h3 style="text-decoration: underline;" class="text-center">EOBI</h3>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered sf-table-list">
                                                                <thead>
                                                                <th class="text-center col-sm-1">EOBI Name</th>
                                                                <td class="text-center col-sm-1"><?=$eobi->EOBI_name?></td>
                                                                </thead>
                                                                <thead>
                                                                <th class="text-center col-sm-1">EOBI Amount</th>
                                                                <td class="text-center col-sm-1"><?=$eobi->EOBI_amount?></td>
                                                                </thead>
                                                                <thead>
                                                                <th class="text-center col-sm-1">EOBI Month & Year</th>
                                                                <td class="text-center col-sm-1"><?=$eobi->month_year?></td>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h3 style="text-decoration: underline;" class="text-center">EOBI</h3>
                                                        <div class="table-responsive">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered sf-table-list">
                                                                    <thead>
                                                                    <th class="text-center col-sm-1">No Record Found !</th>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif
                                                @if($tax)
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h3 style="text-decoration: underline;" class="text-center">Tax</h3>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered sf-table-list">
                                                                <thead>
                                                                <th class="text-center col-sm-1">Tax Name</th>
                                                                <td class="text-center col-sm-1"><?=$tax->tax_name?></td>
                                                                </thead>
                                                                <thead>
                                                                <th class="text-center col-sm-1">Tax Deduction </th>
                                                                <td class="text-center col-sm-1"><?=$tax->tax_percent.'%'?></td>
                                                                </thead>
                                                                <thead>
                                                                <th class="text-center col-sm-1">Tax Month & Year</th>
                                                                <td class="text-center col-sm-1"><?=$tax->tax_month_year?></td>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h3 style="text-decoration: underline;" class="text-center">Tax</h3>
                                                        <div class="table-responsive">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered sf-table-list">
                                                                    <thead>
                                                                    <th class="text-center col-sm-1">No Record Found !</th>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="row">&nbsp;</div>

                                                <h3 style="text-decoration: underline;" class="text-center">Leaves Policy</h3>
                                                <div class="row">
                                                    <?php echo Form::open(array('url' => '','id'=>''));?>
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="company_id" value="<?=$m?>">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="panel">
                                                            @if($leavesPolicy)
                                                                <div class="panel-body">

                                                                    <div class="row">
                                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                                            <label class="sf-label">Leaves Policy Name:</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <input disabled type="text" value="<?=$leavesPolicy->leaves_policy_name;?>" class="form-control">
                                                                        </div>
                                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">

                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Policy Month & Year from:</label>
                                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                                <input disabled value="<?=$leavesPolicy->policy_date_from?>" type="text" class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Policy Month & Year till:</label>
                                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                                <input disabled type="text" value="<?= $leavesPolicy->policy_date_till?>" class="form-control">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                            <label class="sf-label">Full Day Deduction Rate:</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>

                                                                            <select disabled class="form-control requiredField" name="full_day_deduction_rate" required>
                                                                                <option value="">select</option>
                                                                                <option @if($leavesPolicy->fullday_deduction_rate == '1') {{ 'selected' }}@endif value="1">1/1&nbsp;&nbsp;(First Quarter)</option>
                                                                                <option @if($leavesPolicy->fullday_deduction_rate == '0.5') {{ 'selected' }}@endif value="0.5">1/2&nbsp;&nbsp;(Second Quarter)</option>
                                                                                <option @if($leavesPolicy->fullday_deduction_rate == '0.33333333333') {{ 'selected' }}@endif value="0.33333333333">1/3&nbsp;&nbsp;(Third Quarter)</option>
                                                                                <option @if($leavesPolicy->fullday_deduction_rate == '0.25') {{ 'selected' }}@endif value="0.25">1/4&nbsp;&nbsp;(Fourth Quarter)</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                            <label class="sf-label">Half Day Deduction Rate:</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>

                                                                            <select disabled class="form-control requiredField" name="half_day_deduction_rate" required>
                                                                                <option value="">select</option>
                                                                                <option @if($leavesPolicy->halfday_deduction_rate == '1') {{ 'selected' }}@endif value="1">1/1&nbsp;&nbsp;(First Quarter)</option>
                                                                                <option @if($leavesPolicy->halfday_deduction_rate == '0.5') {{ 'selected' }}@endif value="0.5">1/2&nbsp;&nbsp;(Second Quarter)</option>
                                                                                <option @if($leavesPolicy->halfday_deduction_rate == '0.33333333333') {{ 'selected' }}@endif value="0.33333333333">1/3&nbsp;&nbsp;(Third Quarter)</option>
                                                                                <option @if($leavesPolicy->halfday_deduction_rate == '0.25') {{ 'selected' }}@endif value="0.25">1/4&nbsp;&nbsp;(Fourth Quarter)</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                            <label class="sf-label">Per Hour Deduction Rate:</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <select disabled class="form-control requiredField" name="per_hour_deduction_rate" required>
                                                                                <option value="">select</option>
                                                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.1') {{ 'selected' }}@endif value="0.1">1/1&nbsp;&nbsp;(Equivalent to 1 Hour)</option>
                                                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.2') {{ 'selected' }}@endif value="0.2">1/2&nbsp;&nbsp;(Equivalent to 2 Hour)</option>
                                                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.3') {{ 'selected' }}@endif value="0.3">1/3&nbsp;&nbsp;(Equivalent to 3 Hour)</option>
                                                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.4') {{ 'selected' }}@endif value="0.4">1/4&nbsp;&nbsp;(Equivalent to 4 Hour)</option>
                                                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.5') {{ 'selected' }}@endif value="0.5">1/5&nbsp;&nbsp;(Equivalent to 5 Hour)</option>
                                                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.6') {{ 'selected' }}@endif value="0.6">1/6&nbsp;&nbsp;(Equivalent to 6 Hour)</option>
                                                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.7') {{ 'selected' }}@endif value="0.7">1/7&nbsp;&nbsp;(Equivalent to 7 Hour)</option>
                                                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.8') {{ 'selected' }}@endif value="0.8">1/8&nbsp;&nbsp;(Equivalent to 8 Hour)</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <table class="table table-bordered sf-table-list">
                                                                        <thead>
                                                                        <th>Leaves Type</th>
                                                                        <th>No. of Leaves</th>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($leavesData as $value)
                                                                            <tr>
                                                                                <td>{{ HrHelper::getMasterTableValueById($m,'leave_type','leave_type_name',$value->leave_type_id) }}</td>
                                                                                <td>{{ $value->no_of_leaves }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <label class="sf-label">Terms & Condition</label>
                                                                    <div disabled class="row"><?= $leavesPolicy->terms_conditions; ?></div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <?php echo Form::close();?>
                                                </div>
                                                @else
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered sf-table-list">
                                                                    <thead>
                                                                    <th class="text-center col-sm-1">No Record Found !</th>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection