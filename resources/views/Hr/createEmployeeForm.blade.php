<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
$Company = DB::table('company')->where('status',1)->get();
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">

@extends('layouts.default')
@section('content')
@include('select2')
    <style>


        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
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
            z-index: 10000000;
        }

        .pointer {
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Employee Form</span>
                    </div>
                </div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'had/addEmployeeDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="company_id" value="<?=$m?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div class="hr-border" style="border: 1px solid #e5e5e5b0; margin-top: 89px;"></div>
                                    <img id="img_file_1" class="img-circle" src="<?= Storage::url('app/uploads/employee_images/user-dummy.png')?>">
                                </div>
                                <div class="text-center">
                                    <label>
                                        <input type="file" id="file_1" name="fileToUpload_1" accept="image/*" capture style="display:none"/>
                                        <img style="cursor:pointer;width: 50px;height: 50px;" src="<?= url('assets/img/cam.png')?>" id="upfile1" style="cursor:pointer" />
                                        Change Image
                                    </label>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4  style="text-decoration: underline;font-weight: bold;">Basic Information</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Company</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select name="comp_id_1" id="comp_id_1" class="requiredField form-control">
                                            <option value="">Select Company</option>
                                            <?php foreach($Company as $Fil):?>
                                            <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Emp Code</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <?php $EmCod = DB::Connection('mysql2')->table('employee')->max('emp_code')+1;

                                        ?>
                                        <input type="number" name="emp_code_1" id="emp_code_1" class="form-control requiredField" value="<?php echo $EmCod?>" readonly>
                                        <span style="color:red;font-weight: bold;" id="emrExistMessage"></span>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Employee Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" placeholder="Employee Name" name="employee_name_1" id="employee_name_1" value="" />
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Father / Husband Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" placeholder="Father Name" name="father_name_1" id="father_name_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Department / Sub Department</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField emp_department_id" name="emp_department_id_1" id="emp_department_id" onChange="departmentDependentDesignation()">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $key => $y)
                                                <option value="{{ $y->id}}">{{ $y->department_name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Designation</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField designation" id="designation_1" name="designation_1"></select>
                                        <div id="depentdentDesignation"></div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Region</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField region_id" id="region_id_1" name="region_id_1" onchange="locationDependentRegion()">
                                            <option value="">Select Region</option>
                                            @foreach($employee_regions as $key4 => $v1)
                                                <option value="{{ $v1->id}}">{{ $v1->employee_region}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Location / Branch</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField branch_id" id="branch_id_1" name="branch_id_1"></select>
                                        <div id="depentdentLoation"></div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Employment Status','job_type','job_type_name','employee_status','<?php echo $m; ?>')"><label class="pointer sf-label">Employment Status</label></a>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField employee_status" name="employee_status_1" id="employee_status_1">
                                            <option value="">Select Employment Status</option>
                                            @foreach($jobtype as $key3 => $value)
                                                <option value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Gender</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="gender_1" id="gender_1">
                                            <option value="">Select Gender</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">CNIC</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" placeholder="CNIC Number" name="cnic_1" id="cnic_1" value="" />
                                        <span style="color:red;font-weight: bold;" id="cnicExistMessage"></span>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">CNIC Expiry Date</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField" name="cnic_expiry_date_1" id="cnic_expiry_date_1" value="" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Life Time Validity</label>
                                        <input type="checkbox" class="form-control" name="life_time_cnic_1" id="life_time_cnic_1" value="1" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>Upload CNIC Copy:</label>
                                        <input type="file" class="form-control" name="cnic_path_1" id="cnic_path_1" multiple>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Marital Status','marital_status','marital_status_name','marital_status','<?php echo $m; ?>')"><label class="pointer sf-label">Marital Status</label></a>
                                        <select class="form-control marital_status" name="marital_status_1" id="marital_status_1">
                                            <option value="">Select Marital</option>
                                            @foreach($marital_status as $key4 => $value2)
                                                <option value="{{ $value2->id}}">{{ $value2->marital_status_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Date of Birth</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField" placeholder="Date of Birth" name="date_of_birth_1" id="date_of_birth_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Place of Birth</label>
                                        <input type="text" class="form-control" placeholder="Pace of Birth" name="place_of_birth_1" id="place_of_birth_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Joining Date</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField" placeholder="Joining Date" name="joining_date_1" id="joining_date_1" value="" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Nationality</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" placeholder="Nationality" name="nationality_1" id="nationality_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Province</label>
                                        <input type="text" class="form-control" placeholder="Province" name="province_1" id="province_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Cell No / Mobile No</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" placeholder="Cell No / Mobile No" name="contact_no_1" id="contact_no_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Home No# </label>
                                        <input type="text" class="form-control" placeholder="Home No" name="contact_home_1" id="contact_home_1" value="" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Office No#</label>
                                        <input type="text" class="form-control" placeholder="Office No" name="contact_office_1" id="contact_office_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Emergency No#</label>
                                        <input type="text" class="form-control" placeholder="Emergency No" name="emergency_no_1" id="emergency_no_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Joining Salary</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" step="any" class="form-control requiredField" placeholder="Joining Salary" name="emp_joining_salary_1" id="emp_joining_salary_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Current Salary</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" step="any" class="form-control requiredField" placeholder="Current Salary" name="salary_1" id="salary_1" value="" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Email</label>
                                        <input type="text" class="form-control" placeholder="Email Address" name="email_1" id="email_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Religion</label>
                                        <input type="text" class="form-control" placeholder="Religion Name" name="religion_1" id="religion_1" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Overtime Policy</label>
                                        <select class="form-control" name="working_hours_policy_id_1" id="working_hours_policy_id_1"></select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Eobi</label>
                                        <select class="form-control" name="eobi_id_1" id="eobi_id_1">
                                            <option value="0">--</option>
                                            @foreach($eobi as $value8)
                                                <option value="{{ $value8->id}}">
                                                    {{ $value8->EOBI_name}}
                                                    ({{ $value8->month_year}})
                                                    Amount=({{ $value8->EOBI_amount}})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">EOBI Number</label>
                                        <input type="text" class="form-control" placeholder="EOBI Number" name="eobi_number_1" id="eobi_number_1" value="" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">EOBI Upload</label>
                                        <input type="file" class="form-control" name="eobi_path_1" id="eobi_path_1" multiple>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <label class="sf-label">Leaves Policy</label>
                                            <select class="form-control" name="leaves_policy_1" id="leaves_policy_1">
                                                <option value="">Select</option>
                                                @foreach($leaves_policy as $key4 => $value3)
                                                    <option value="{{ $value3->id}}">{{ $value3->leaves_policy_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding-top: 35px;">
                                            <button type="button" class="btn-xs btn-success" id="leaves_policy_id_1">View Policy</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Passport No</label>
                                        <input type="text" class="form-control" placeholder="Passport No" name="passport_no_1" id="passport_no_1" value="" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Valid From</label>
                                        <input type="date" class="form-control" placeholder="Valid From" name="passport_valid_from_1" id="passport_valid_from_1" value="" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Valid To</label>
                                        <input type="date" class="form-control" placeholder="Valid To" name="passport_valid_to_1" id="passport_valid_to_1" value="" />
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Driving License No</label>
                                        <input type="text" class="form-control" placeholder="Driving License No" name="driving_license_no_1" id="driving_license_no_1" value="" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Valid From</label>
                                        <input type="date" class="form-control" placeholder="Valid From" name="driving_license_valid_from_1" id="driving_valid_from_1" value="" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Valid To</label>
                                        <input type="date" class="form-control" placeholder="Valid To" name="driving_license_valid_to_1" id="driving_valid_to_1" value="" />
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><br>
                                        <label class="sf-label">Transport Owned</label>
                                        &nbsp &nbsp
                                        <input type="radio" name="transport_check_1" id="transport_check_1" value="Yes" />&nbsp; Yes &nbsp;
                                        <input type="radio" name="transport_check_1" id="transport_check_1" value="No" checked/>&nbsp; No
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="transport_particular_area_1"></div>
                                </div>

                                {{--new employee details--}}
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Address</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Current Address</label>
                                        <textarea class="form-control" name="residential_address_1"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Permanent Address</label>
                                        <textarea class="form-control" name="permanent_address_1"></textarea>
                                    </div>
                                </div>

                                {{--family data--}}
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Immediate Family Data</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">
                                        <h4 style="text-decoration: underline;">
                                            Spouse, Children, Parents, Brothers, Sisters
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="family_data_check_1" id="family_data_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="family_data_area_1"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Bank Account Details</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input type="checkbox" name="bank_account_check_1" id="bank_account_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="bank_account_area_1"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Educational / Technical Background</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Start from Recent</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="education_check_1" id="education_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="education_area_1">

                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Language Proficiency</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input type="checkbox" name="language_check_1" id="language_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="language_area_1"></div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Health Information</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline"> Any disorder regarding</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="health_type_check_1" id="health_type_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="health_type_area_1"></div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Activities</h4>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <h4 style="text-decoration: underline;">
                                            Associations, societies, clubs you were / are member of
                                        </h4>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
                                        <input type="checkbox" name="activity_check_1" id="activity_check_1">
                                    </div>

                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="activity_area_1"> </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Work Experience</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Most recent first</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="work_experience_check_1" id="work_experience_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="work_experience_area_1"></div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Reference</h4>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <h4 style="text-decoration: underline;">
                                            Professional/Business references only ,
                                            Please include reference from your past employment
                                        </h4>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                        <input type="checkbox" name="reference_check_1" id="reference_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="reference_area_1"></div>


                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Next Of Kin Details</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">In Case of Employee Death</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="kins_check_1" id="kins_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="kins_area_1"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">
                                            Do you have any relatives in this company ?
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input checked type="radio" value="No" name="relative_check_1" id="relative_check_1">
                                            &nbsp;&nbsp;
                                            Yes : <input type="radio" value="Yes" name="relative_check_1" id="relative_check_1">
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="relative_area_1"></div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Have you ever been convicted of a crime ?</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input checked type="radio" value="No" name="crime_check_1" id="crime_check_1" class="relative_check">
                                            &nbsp;&nbsp;
                                            Yes : <input type="radio" value="Yes" name="crime_check_1" id="crime_check_1" class="relative_check">
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="crime_check_input_1"></div>
                                <div class="row">&nbsp;</div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Any Additional Information you wish to provide ?
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input checked type="radio" value="No" name="additional_info_check_1" id="additional_info_check_1" class="relative_check">
                                            &nbsp;&nbsp;
                                            Yes : <input type="radio" value="Yes" name="additional_info_check_1" id="additional_info_check_1" class="relative_check">
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="additional_info_input_1"></div>
                                <div class="row">&nbsp;</div>
                                <div class="row">&nbsp;</div>

                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Employee GSSP Verification</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Can Upload Multiple Files</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="gssp_verification_check" id="gssp_verification_check" value="1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div id="gssp_upload_area" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>

                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Employee Document Upload</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Can Upload Multiple Files

                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="documents_upload_check" id="documents_upload_check" value="1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div id="file_upload_area" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;font-weight: bold;">Login Credentials</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Can Login ? :
                                            <input type="checkbox" name="can_login_1" id="can_login_1" value="yes">
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="credential_area_1" style="display: none;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Account type</label>
                                        <select class="form-control" name="account_type_1">
                                            <option value="user">User</option>
                                            <option value="client">Client</option>
                                            <option value="company">Company</option>
                                            <option value="master">Master</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <label class="sf-label">Password</label>
                                        <input type="text" class="form-control" name="password_1">
                                    </div>
                                </div>
                                <div class="employeeSection"></div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <span id="emp_warning" style="color:red;font-weight:bold;"></span>
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success btn_disable']) }}
                                        <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                    </div>
                                </div>
                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

    <script>

        $(document).ready(function() {
            var employee = 1;

            var m = "<?= $_GET["m"]; ?>";

            $('.addMoreEmployeeSection').click(function (e){

                e.preventDefault();
                employee++;
                $('.employeeSection').append('<div class="row myloader_'+employee+'"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>')

                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormEmployeeDetail',
                    type: "GET",
                    data: { id:employee ,m : m},
                    success:function(data) {
                        $('.employeeSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sectionEmployee_'+employee+'"><a href="#" onclick="removeEmployeeSection('+employee+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                        $('.myloader_'+employee).remove();
                    }
                });
            });

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });
        });

        function removeEmployeeSection(id){
            var elem = document.getElementById('sectionEmployee_'+id+'');
            elem.parentNode.removeChild(elem);
        }

        function removeQualificationSection(id){
            $("#remove_area_"+id).remove();
        }

        function removeWorkExperienceSection(id){
            $("#remove_area1_"+id).remove();
        }

        function removeReferenceSection(id) {
            $(".remove_area2_"+id).remove();
        }

        function removeFamilyDataSection(id) {
            $(".remove_area3_"+id).remove();
        }

        function removeActivityDataSection(id) {
            $(".remove_area4_"+id).remove();
        }

        function removeEmergencyContactSection(id) {
            $(".remove_area5_"+id).remove();
        }

        function removeKinDetailsSection(id) {
            $(".remove_area6_"+id).remove();
        }

        function removeLanguageProficiencySection(id) {
            $("#remove_area7_"+id).remove();
        }

        function removeHealthDetailsSection(id) {
            $(".remove_area8_"+id).remove();
        }
        function removeRelativesDetailsSection(id) {
            $(".remove_area10_"+id).remove();
        }
        function removeEmployeeGsspDocumentDataSection(id) {
            $(".remove_area_"+id).remove();
        }


        $('#leaves_policy_id_1').click(function (e)
        {
            var leaves_policy_id = $('#leaves_policy_1').val();
            if(leaves_policy_id != ''){

                showDetailModelTwoParamerter('hdc/viewLeavePolicyDetail',leaves_policy_id,'View Leaves Policy Detail ','<?=Input::get('m')?>');
            }
            else
            {
                alert('Please Select Policy !');
            }
        });

        $('#view_tax_1').click(function (e)
        {
            var tax_id = $('#tax_id_1').val();
            if(tax_id != '0'){

                showDetailModelTwoParamerterJson('hdc/viewTax',tax_id,'View Tax  Detail ','<?=Input::get('m')?>');
            }
            else
            {
                alert('Please Select Tax !');
            }
        });

        $('#can_login_1').click(function (e)
        {
            if($("#can_login_1").prop('checked') == true)
            {
                $('#credential_area_1').fadeIn();
            }
            else
            {
                $('#credential_area_1').fadeOut();
            }

        })



        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img_file_1').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#file_1").change(function(){
            readURL(this);
        });

        $("#transport_yes_1").change(function(){
            if($("#transport_yes_1").prop('checked') == true)
            {
                $("#transport_particular").fadeIn();
                $("#transport_no_1").prop('checked', false);
            }
            else
            {
                $("#transport_particular").fadeOut();
                $("#transport_yes_1").prop('checked', false);
            }
        });

        $("#transport_no_1").change(function(){
            if($("#transport_no_1").prop('checked') == true)
            {
                $("#transport_particular").fadeOut();
                $("#transport_yes_1").prop('checked', false);
            }

        });




        $("input[name='crime_check_1']").click(function() {

            if($(this).val() == 'Yes')
            {
                $("#crime_check_input_1").html('<label class="sf-label">Detail</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="Detail" name="crime_detail_1" id="crime_detail_1" value="" />' +
                        '');
            }
            else
            {
                $("#crime_check_input_1").html('');
            }
        })


        $("input[name='additional_info_check_1']").click(function() {

            if($(this).val() == 'Yes')
            {
                $("#additional_info_input_1").html('<label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        ' <input type="text" class="form-control requiredField" placeholder="Detail" name="additional_info_detail_1" id="additional_info_detail_1" value="" />');
            }
            else
            {
                $("#additional_info_input_1").html('');
            }
        })


        $('#family_data_check_1').click(function(){

            if($(this).is(":checked") == true)
            {

                $("#family_data_area_1").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" name="family_data[]" id="get_rows3" value="1">' +
                        '<div class="table-responsive" id="family_append_area_1">' +
                        '<table class="table table-bordered sf-table-list get_rows3" id="get_clone3"><thead><th>Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<td class="text-center"><input class="form-control requiredField" name="family_name_1" id="family_name_1" required>' +'</td>' +
                        '</thead><thead><th>Relation<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                        '<input class="form-control requiredField" name="family_relation_1"  id="family_relation_1" required></td>' +'</thead><thead>' +
                        '<th>Age<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                        '<input type="number" class="form-control requiredField" name="family_age_1" id="family_age_1" required></td></thead><thead>' +
                        '<th>Designation / Occuption<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                        '<input class="form-control requiredField" name="family_occupation_1" id="family_occupation_1" required></td></thead><thead>' +
                        '<th>Organization<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                        '<input class="form-control requiredField" name="family_organization_1" id="family_organization_1" required>' +
                        '</td></thead></table></div></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">' +
                        '<button type="button" class="btn btn-xs btn-primary" id="addMoreFamilyData">Add More Family Data</button></div>' +
                        '');

                $("#addMoreFamilyData").click(function(e){
                    var form_rows_count = $(".get_rows3").length;
                    form_rows_count++;
                    $("#family_append_area_1").append('<table class="table table-bordered sf-table-list remove_area3_'+form_rows_count+' get_rows3" id="">' +
                            '<input type="hidden" name="family_data[]" value="'+form_rows_count+'">'+
                            '<thead><th>Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
                            '<td class="text-center"><input class="form-control requiredField" name="family_name_'+form_rows_count+'" id="family_name_'+form_rows_count+'" required>' +'</td>' +
                            '</thead><thead><th>Relation<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                            '<input class="form-control requiredField" name="family_relation_'+form_rows_count+'"  id="family_relation_'+form_rows_count+'" required></td>' +'</thead><thead>' +
                            '<th>Age<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                            '<input type="number" class="form-control requiredField" name="family_age_'+form_rows_count+'" id="family_age_'+form_rows_count+'" required></td></thead><thead>' +
                            '<th>Designation / Occuption<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                            '<input class="form-control requiredField" name="family_occupation_'+form_rows_count+'" id="family_occupation_'+form_rows_count+'" required></td></thead><thead>' +
                            '<th>Organization<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                            '<input class="form-control requiredField" name="family_organization_'+form_rows_count+'" id="family_organization_'+form_rows_count+'" required>' +
                            '</td></thead></table>' +
                            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right"><button type="button" onclick="removeFamilyDataSection('+form_rows_count+')" class="btn btn-xs btn-danger remove_area3_'+form_rows_count+'">Remove</button><div>');

                });
            }
            else
            {
                $("#family_data_area_1").html('');
            }

        });



        $('#bank_account_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#bank_account_area_1").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<input type="hidden" name="bank_account_data[]" value="1">'+
                        '<label class="sf-label">Title Of Account</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="Title Of Account" name="account_title" id="account_title" value="" />' +
                        '</div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Bank Name</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="Bank Name" name="bank_name" id="bank_name" value="" />' +
                        '</div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Account No</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="Account No" name="account_no" id="account_no" value="" />' +
                        '</div>');
            }
            else
            {
                $("#bank_account_area_1").html('');
            }

        });

        $('#education_check_1').click(function(){

            if($(this).is(":checked") == true)
            {

                $("#education_area_1").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="table-responsive">' +
                        '<input type="hidden" name="education_data[]" value="1"><table class="table table-bordered sf-table-list"><thead><th class="text-center col-sm-1">S.No</th>' +
                        '<th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>' +
                        '<th class="text-center">Degree / Diploma</th><th class="text-center">Major Subjects</th>' +
                        '<th class="text-center"><button type="button" id="addMoreQualification" class="btn btn-xs btn-primary">Add More Qualification</button></th>' +
                        '</thead><tbody id="insert_clone"><tr class="get_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1" value=""></td>' +
                        '<td class="text-center"><input name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1" value=""></td>' +
                        '<td class="text-center"><input name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1" value=""></td>' +
                        '<td class="text-center"><input type="hidden" name="qualificationSection[]">' +
                        '<select style="width:300px;" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1"><option value="">Select</option>'+
                        '@foreach($DegreeType as $DegreeTypeValue)<option value="{{ $DegreeTypeValue->id }}">{{ $DegreeTypeValue->degree_type_name }}</option>@endforeach<option value="other">Other</option></select><span id="other_option_1"></span></td>' +
                        '<td class="text-center"><input name="major_subjects_1" type="text" class="form-control requiredField" id="major_subjects_1" value=""></td>' +
                        '<td class="text-center">-</td></tr></tbody></table></div></div>');


                $("#addMoreQualification").click(function(e){
                    var clone = $(".get_clone_1").html();

                    var form_rows_count = $(".get_rows").length;
                    form_rows_count++;
                    $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' ><td class='text-center'>" +
                            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                            "<td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>" +
                            "<td class='text-center'><input name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                            "<td class='text-center'><input name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                            "<td><input type='hidden' name='education_data[]' value="+form_rows_count+">" +
                            "<select style='width:300px;' id='degree_type_"+form_rows_count+"' class='form-control requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                            "<span id='other_option_"+form_rows_count+"'></span></td>" +
                            "<td class='text-center'><input name='major_subjects_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='major_subjects_"+form_rows_count+"'></td>" +
                            "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='btn btn-xs btn-danger'>Remove</button>" +
                            "</td>" +
                            "</tr>");
                    $('#degree_type_'+form_rows_count+'').select2();

                });
                $('#degree_type_1').select2();
            }
            else
            {
                $("#education_area_1").html('');
            }

        });



        $('#language_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#language_area_1").html(' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                        '<input type="hidden" name="language_data[]" value="1"><div class="table-responsive">' +
                        '<table class="table table-bordered sf-table-list" ><thead><th class="text-center col-sm-1">S.No</th>' +
                        '<th class="text-center">Language<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center">Read<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center">Write<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center">Speak<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center"><button type="button" class="btn btn-xs btn-primary" id="addMoreLanguage">Add More Language</button></th>' +
                        '</thead><tbody id="insert_clone7"><tr class="get_rows7"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td id="get_clone7" class="text-center"><input class="form-control requiredField" name="language_name_1"  id="language_name_1" required>' +
                        '</td><td class="text-center"><b>Good : <input checked type="radio" name="reading_skills_1" value="Good"></b><b>Fair : <input type="radio" name="reading_skills_1" value="Fair">' +
                        '</b><b>Poor : <input type="radio" name="reading_skills_1" value="Poor"></b></td><td class="text-center"><b>Good : <input checked type="radio" name="writing_skills_1" value="Good"></b>' +
                        '<b>Fair : <input type="radio" name="writing_skills_1" value="Fair"></b><b>Poor : <input type="radio" name="writing_skills_1" value="Poor"></b>' +
                        '</td><td class="text-center"><b>Good : <input checked type="radio" name="speaking_skills_1" value="Good"></b><b>Fair : <input type="radio" name="speaking_skills_1" value="Fair"></b>' +
                        '<b>Poor : <input type="radio" name="speaking_skills_1" value="Poor"></b></td></tr></tbody></table> </div></div>');

                $("#addMoreLanguage").click(function(e){
                    var form_rows_count = $(".get_rows7").length;
                    form_rows_count++;
                    $("#insert_clone7").append("<tr class='get_rows7' id='remove_area7_"+form_rows_count+"' ><td class='text-center'>" +
                            '<input type="hidden" name="language_data[]" value="'+form_rows_count+'">' +
                            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                            "<td class='text-center'><input class='form-control requiredField' name='language_name_"+form_rows_count+"' value='' id='language_name_"+form_rows_count+"' required></td>" +
                            "<td class='text-center'><b>Good : <input checked type='radio' name='reading_skills_"+form_rows_count+"' value='Good'></b>" +
                            "<b>Fair : <input  type='radio' name='reading_skills_"+form_rows_count+"' value='Fair'></b>" +
                            "<b>Poor : <input type='radio' name='reading_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                            "<td class='text-center'><b>Good : <input checked type='radio' name='writing_skills_"+form_rows_count+"' value='Good'></b>" +
                            "<b>Fair : <input  type='radio' name='writing_skills_"+form_rows_count+"' value='Fair'></b>" +
                            "<b>Poor : <input type='radio' name='writing_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                            "<td class='text-center'><b>Good : <input checked type='radio' name='speaking_skills_"+form_rows_count+"' value='Good'></b>" +
                            "<b>Fair : <input  type='radio' name='speaking_skills_"+form_rows_count+"' value='Fair'></b>" +
                            "<b>Poor : <input type='radio' name='speaking_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                            "<td class='text-center'><button onclick='removeLanguageProficiencySection("+form_rows_count+")' type='button'class='btn btn-xs btn-danger'>Remove</button>" +
                            "</td>" +
                            "</tr>");


                });

            }
            else
            {
                $("#language_area_1").html('');
            }

        });

        $('#health_type_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#health_type_area_1").html(' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                        '<input type="hidden" name="health_data[]" value="1"><div class="table-responsive">' +
                        '<table class="table table-bordered sf-table-list" ><thead><th class="text-center col-sm-1">S.No</th>' +
                        '<th class="text-center">Health Type<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center">Yes / No<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center"><button type="button" class="btn btn-xs btn-primary" id="addMoreHealth">Add More Health</button></th>' +
                        '</thead>' +
                        '<tbody id="insert_clone8"><tr class="get_rows8"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td class="text-center"><select style="width:300px;" class="form-control" name="health_type_1" id="health_type_1" required>' +
                        '<option value="Speech">Speech</option>' +
                        '<option value="Hearing">Hearing</option>' +
                        '<option value="Sight">Sight</option>' +
                        '<option value="AIDS">AIDS</option>' +
                        '<option value="Hands">Hands</option>' +
                        '<option value="Feet">Feet</option>' +
                        '<option value="Skin">Skin</option>' +
                        '<option value="Cancer">Cancer</option>' +
                        '<option value="Epilespy">Epilespy</option>' +
                        '<option value="Asthma">Asthma</option>' +
                        '<option value="Tuberculosis">Tuberculosis</option>' +
                        '<option value="Hepatitis">Hepatitis</option>' +
                        ' </select></td><td class="text-center"><select class="form-control" id="health_check_1" name="health_check_1" required>' +
                        '<option value="Yes">Yes</option>' +
                        '<option value="No">No</option>' +
                        '</select></td><td class="text-center">-</td></tr></tbody></table></div>' +
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Any Physical Handicap</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span> ' +
                        '<input type="text" class="form-control requiredField" name="physical_handicap" id="physical_handicap" value="-" />' +
                        '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label class="sf-label">Height</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="" name="height" id="height"/>' +
                        '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label class="sf-label">Weight</label>' +
                        "<span class='rflabelsteric'><strong>*</strong></span>" +
                        "<input type='number' class='form-control requiredField' placeholder='80kg' name='weight' id='weight'  />" +
                        "</div> <div class='col-lg-3 col-md-3 col-sm-3 col-xs-12'><label class='sf-label'>Blood Group</label>" +
                        "<span class='rflabelsteric'><strong>*</strong></span>" +
                        "<input type='text' class='form-control requiredField' placeholder='A+' name='blood_group' id='blood_group'  />" +
                        "</div></div>");

                $("#addMoreHealth").click(function(e){
                    var clone_health_type = $("#health_type_1").html();
                    var clone_health_check = $("#health_check_1").html();
                    var form_rows_count = $(".get_rows8").length;
                    form_rows_count++;
                    $("#insert_clone8").append('<tr class="remove_area8_'+form_rows_count+' get_rows8" id="">' +
                            '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                            '<td class="text-center"><select style="width:300px;" class="form-control" name="health_type_'+form_rows_count+'" id="health_type_'+form_rows_count+'" required>'+clone_health_type+'</select></td>' +
                            '<td class="text-center"><select class="form-control" name="health_check_'+form_rows_count+'" id="health_check_'+form_rows_count+'" required>'+clone_health_check+'</select></td>' +
                            '<td class="text-center"><input type="hidden" name="health_data[]" value="'+form_rows_count+'">' +
                            '<button type="button" onclick="removeHealthDetailsSection('+form_rows_count+')" class="btn btn-xs btn-danger remove_area8_'+form_rows_count+'">Remove</button></td>' +
                            '</tr>');
                    $("#health_type_"+form_rows_count+"").select2();

                });
                $("#health_type_1").select2();
            }
            else
            {
                $("#health_type_area_1").html('');
            }

        });

        $('#activity_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#activity_area_1").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows4">' +
                        '<input type="hidden" name="activity_data[]" value="1"><div id="get_clone4">' +
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="sf-label">Name Of Institution</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span><input class="form-control requiredField" name="institution_name_1" id="institution_name_1" required>' +
                        '</div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="sf-label">Position Held</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="Particulars" name="position_held_1" id="position_held_1" value="" />' +
                        '</div></div><div class="row">&nbsp;</div><div class="row">&nbsp;</div><div id="insert_clone4"></div></div>' +
                        '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">' +
                        '<button type="button" class="btn btn-xs btn-primary" id="addMoreActivities">Add More Activities</button>' +
                        '</div>');

                $("#addMoreActivities").click(function(e){

                    var form_rows_count = $(".get_rows4").length;
                    form_rows_count++;
                    $("#insert_clone4").append('<div class="remove_area4_'+form_rows_count+' get_rows4" id=""><input type="hidden" name="activity_data[]" value="'+form_rows_count+'">' +
                            '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="sf-label">Name Of Institution</label>' +
                            '<span class="rflabelsteric"><strong>*</strong></span><input class="form-control requiredField" name="institution_name_'+form_rows_count+'" id="institution_name_'+form_rows_count+'" required>' +
                            '</div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="sf-label">Position Held</label>' +
                            '<span class="rflabelsteric"><strong>*</strong></span>' +
                            '<input type="text" class="form-control requiredField" placeholder="Particulars" name="position_held_'+form_rows_count+'" id="position_held_'+form_rows_count+'" value="" />' +
                            '</div></div>' +
                            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right"><button type="button" onclick="removeActivityDataSection('+form_rows_count+')" class="btn btn-xs btn-danger remove_area4_'+form_rows_count+'">Remove</button><div>');

                });
            }
            else
            {
                $("#activity_area_1").html('');
            }

        });
        $('#work_experience_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#work_experience_area_1").html(' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="table-responsive">' +
                        '<table class="table table-bordered sf-table-list"><thead><th class="text-center col-sm-1">S.No</th>' +
                        '<th class="text-center">Name Of Employeer<span class="rflabelsteric"><strong>*</strong></span></th><th class="text-center">Position Held<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center">Career Level</th><th class="text-center">From</th><th class="text-center">Till</th>' +
                        '<th class="text-center">Last Drawn Salary</th><th class="text-center">Reason For Leaving</th><th class="text-center">Upload File</th>' +
                        ' <th class="text-center"><button type="button" id="addMoreWorkExperience" class="btn btn-xs btn-primary">Add More Work Exp</button></th>' +
                        '</thead><tbody id="insert_clone1"><tr class="get_rows1"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td id="get_clone1" class="text-center"><input type="hidden" name="work_experience_data[]" value="1">' +
                        '<input type="text" name="employeer_name_1" id="employeer_name_1" class="form-control requiredField" required></td>' +
                        '<td class="text-center"><select class="form-control requiredField" name="position_held_1" id="position_held_1"><option value="">Select Department</option>' +
                        '@foreach($departments as $key => $y)<optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">' +
                        '<?php $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');?>' +
                        '@foreach($subdepartments as $key2 => $y2)<option value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>' +
                        '@endforeach</optgroup>@endforeach</select></td>' +
                        '<td class="text-center"><select class="form-control" name="career_level_1" id="career_level_1"><option value="-">-</option>' +
                        '<option value="Fresh">Fresh</option><option value="Skilled">Skilled</option>' +
                        '<option value="Highly Skilled">Highly Skilled</option>' +
                        '<option value="Subject Matter Experienced">Subject Matter Experienced</option>' +
                        '</select></td><td class="text-center"><input name="started_1" type="date" class="form-control" id="started_1">' +
                        '</td><td class="text-center"><input name="ended_1" id="ended_1"type="date" class="form-control" ></td>' +
                        '<td class="text-center"><input name="last_drawn_salary_1" id="last_drawn_salary_1" type="number" class="form-control"></td>' +
                        '<td class="text-center"><input name="reason_leaving_1" id="reason_leaving_1" type="text" class="form-control"></td>' +
                        '<td class="text-center"><input type="file" class="form-control" name="work_exp_path_1" id="work_exp_path_1" multiple></td>' +
                        '<td class="text-center">-</td></tr></tbody></table></div></div>' +
                        '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12"><br><label class="sf-label">Have you ever been dismissed / suspended from service ?</label>' +
                        '<input type="radio" name="suspend_check_1" id="suspend_check_1" value="no" checked/>&nbsp; No' +
                        '<input type="radio" name="suspend_check_1" id="suspend_check_1" value="yes" />&nbsp; Yes &nbsp;' +
                        '</div><div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" id="suspend_detail_1"></div>');

                $("#career_level_1").select2();

                $("input[name='suspend_check_1']").click(function() {
                    if($(this).val() == 'yes')
                    {
                        $("#suspend_detail_1").html('<label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>' +
                                '<input type="text" class="form-control requiredField" placeholder="Suspend Reason" name="suspend_reason_1" id="suspend_reason_1" value="" />');
                    }
                    else
                    {
                        $("#suspend_detail_1").html('');
                    }
                })

                $("#addMoreWorkExperience").click(function(e){
                    var form_rows_count = $(".get_rows1").length;
                    form_rows_count++;
                    $("#insert_clone1").append("<tr class='get_rows1' id='remove_area1_"+form_rows_count+"' ><td class='text-center'>" +
                            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td><td>" +
                            '<input type="hidden" name="work_experience_data[]" value="'+form_rows_count+'">' +
                            "<input type='text' name='employeer_name_"+form_rows_count+"' class='form-control requiredField' required></td>" +
                            "<td class='text-center'><select class='form-control requiredField' name='position_held_"+form_rows_count+"' id='position_held_"+form_rows_count+"'><option value=''>Select Department</option>" +
                            "@foreach($departments as $key => $y)" +
                            "<option value='{{ $y->id}}''>{{ $y->department_name}}</option>" +
                            "@endforeach</select></td>" +
                            '<td class="text-center"><select class="form-control" id="career_level_'+form_rows_count+'" name="career_level_'+form_rows_count+'"><option value="-">-</option>' +
                            '<option value="Fresh">Fresh</option><option value="Skilled">Skilled</option>' +
                            '<option value="Highly Skilled">Highly Skilled</option>' +
                            '<option value="Subject Matter Experienced">Subject Matter Experienced</option>' +'</select></td>'+
                            "<td class='text-center'><input name='started_"+form_rows_count+"' id='started_"+form_rows_count+"'  type='date' class='form-control' value=''></td>" +
                            "<td class='text-center'><input name='ended_"+form_rows_count+"' id='ended_"+form_rows_count+"' type='date' class='form-control' value=''></td>" +
                            "<td class='text-center'><input name='last_drawn_salary_"+form_rows_count+"' id='last_drawn_salary_"+form_rows_count+"' type='number' class='form-control' value='' id='last_drawn_salary[]'></td>"+
                            "<td class='text-center'><input name='reason_leaving_"+form_rows_count+"' id='reason_leaving_"+form_rows_count+"' type='text' class='form-control' value=''></td>" +
                            "<td class='text-center'><input type='file' class='form-control' name='work_exp_path_"+form_rows_count+"' id='work_exp_path_"+form_rows_count+"' multiple></td>" +
                            "<td class='text-center'><button onclick='removeWorkExperienceSection("+form_rows_count+")' type='button'class='btn btn-xs btn-danger'>Remove</button>" +
                            "</td>" +
                            "</tr>");
                    $("#career_level_"+form_rows_count+"").select2();

                });
            }
            else
            {
                $("#work_experience_area_1").html('');
            }

        });
        $('#reference_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#reference_area_1").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows2"><div class="table-responsive">' +
                        '<table class="table table-bordered sf-table-list" id="get_clone2"><thead>' +
                        '<th>Name<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                        '<input type="hidden" name="reference_data[]" value="1">' +
                        '<input class="form-control requiredField" name="reference_name_1" id="reference_name_1" required>' +
                        '</td> </thead><thead> <th>Designation<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<td class="text-center"><input class="form-control requiredField" name="reference_designation_1" id="reference_designation_1" required>' +
                        '</td> </thead> <thead><th>Organization<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                        '<input class="form-control requiredField" name="reference_organization_1" id="reference_organization_1" required>' +
                        '</td></thead><thead><th>Address<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                        '<input class="form-control" name="reference_address_1" id="reference_address_1" required></td></thead><thead>' +
                        '<th>Country<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                        '<input class="form-control requiredField" name="reference_country_1" id="reference_country_1" required></td>' +
                        '</thead><thead><th>Contact Number<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<td class="text-center"><input class="form-control" type="text" name="reference_contact_1"  id="reference_contact_1" required>' +
                        '</td></thead><thead><th>Relationship<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<td class="text-center"><input class="form-control requiredField" name="reference_relationship_1" id="reference_relationship_1" required></td>' +
                        '</thead></table><div id="insert_clone2"></div></div></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">' +
                        '<button type="button" class="btn btn-xs btn-primary" id="addMoreReference">Add More Reference</button></div>');


                $("#addMoreReference").click(function(e){

                    var form_rows_count = $(".get_rows2").length;
                    form_rows_count++;
                    $("#insert_clone2").append('<table class="table table-bordered sf-table-list remove_area2_'+form_rows_count+' get_rows2" id=""><thead>' +
                            '<th>Name<span class="rflabelsteric"><strong>*</strong></span></th> <td class="text-center">' +
                            '<input type="hidden" name="reference_data[]" value="'+form_rows_count+'">' +
                            '<input class="form-control requiredField" name="reference_name_'+form_rows_count+'" id="reference_name_'+form_rows_count+'" required>' +
                            '</td> </thead><thead> <th>Designation<span class="rflabelsteric"><strong>*</strong></span></th>' +
                            '<td class="text-center"><input class="form-control requiredField" name="reference_designation_'+form_rows_count+'" id="reference_designation_'+form_rows_count+'" required>' +
                            '</td> </thead> <thead><th>Organization<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                            '<input class="form-control requiredField" name="reference_organization_'+form_rows_count+'" id="reference_organization_'+form_rows_count+'" required>' +
                            '</td></thead><thead><th>Address<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                            '<input class="form-control" name="reference_address_'+form_rows_count+'" id="reference_address_'+form_rows_count+'" required></td></thead><thead>' +
                            '<th>Country<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">' +
                            '<input class="form-control requiredField" name="reference_country_'+form_rows_count+'" id="reference_country_'+form_rows_count+'" required></td>' +
                            '</thead><thead><th>Contact Number<span class="rflabelsteric"><strong>*</strong></span></th>' +
                            '<td class="text-center"><input class="form-control" type="text" name="reference_contact_'+form_rows_count+'"  id="reference_contact_'+form_rows_count+'" required>' +
                            '</td></thead><thead><th>Relationship<span class="rflabelsteric"><strong>*</strong></span></th>' +
                            '<td class="text-center"><input class="form-control requiredField" name="reference_relationship_'+form_rows_count+'" id="reference_relationship_'+form_rows_count+'" required></td>' +
                            '</thead></table>' +
                            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right"><button type="button" onclick="removeReferenceSection('+form_rows_count+')" class="btn btn-xs btn-danger remove_area2_'+form_rows_count+'">Remove</button><div>');

                });


            }
            else
            {
                $("#reference_area_1").html('');
            }

        });


        $('#kins_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#kins_area_1").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows9"><div class="table-responsive"> ' +
                        '<table class="table table-bordered sf-table-list" ><thead><th class="text-center col-sm-1">S.No</th>' +
                        '<th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center">Relation<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center">Percentage<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center">Address / Mobile No<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center"> <button type="button" class="btn btn-xs btn-primary" id="addMoreKinDetails">Add More Kin Details</button></th>' +
                        '</thead><tbody id="insert_clone9"><tr>' +
                        '<td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td class="text-center"><input type="hidden" name="kins_data[]" value="1">' +
                        '<input class="form-control requiredField" name="next_kin_name_1" id="next_kin_name_1" required></td>' +
                        '<td class="text-center"><input class="form-control requiredField" name="next_kin_relation_1" id="next_kin_relation_1" required></td>' +
                        '<td class="text-center"><input class="form-control requiredField" name="next_kin_percentage_1" id="next_kin_percentage_1" required></td>' +
                        '<td class="text-center"><input class="form-control requiredField" name="next_kin_address_1" id="next_kin_address_1" required></td>' +
                        '<td class="text-center">-</td></tr></tbody></table><div></div></div></div>');


                $("#addMoreKinDetails").click(function(e){

                    var form_rows_count = $(".get_rows9").length;
                    form_rows_count++;
                    $("#insert_clone9").append('<tr class="remove_area6_'+form_rows_count+' get_rows9" id="">' +
                            '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                            "<td class='text-center'><input type='hidden' name='kins_data[]' value="+form_rows_count+">" +
                            "<input class='form-control requiredField' name='next_kin_name_"+form_rows_count+"' id='next_kin_name_"+form_rows_count+"' required></td>" +
                            "<td class='text-center'><input class='form-control requiredField' name='next_kin_relation_"+form_rows_count+"' id='next_kin_relation_"+form_rows_count+"' required></td>" +
                            "<td class='text-center'><input class='form-control requiredField' name='next_kin_percentage_"+form_rows_count+"' id='next_kin_percentage_"+form_rows_count+"' required></td>" +
                            "<td class='text-center'><input class='form-control requiredField' name='next_kin_address_"+form_rows_count+"' id='next_kin_address_"+form_rows_count+"' required></td>" +
                            "<td class='text-center'><button type='button' onclick='removeKinDetailsSection("+form_rows_count+")' class='btn btn-xs btn-danger remove_area9_"+form_rows_count+"'>Remove</button></td>" +
                            '</tr>');

                });

            }
            else
            {
                $("#kins_area_1").html('');
            }

        });


        $("input[name='relative_check_1']").click(function() {

            if($(this).val() == 'Yes')
            {
                $("#relative_area_1").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows10">' +
                        '<input type="hidden" name="relatives_data[]" value="1"><div class="table-responsive"><table class="table table-bordered sf-table-list" >' +
                        '<thead><th class="text-center col-sm-1">S.No</th><th>Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th>Position<span class="rflabelsteric"><strong>*</strong></span></th>' +
                        '<th class="text-center"> <button type="button" class="btn btn-xs btn-primary" id="addMoreRelativesDetails">Add More Relatives Details</button></th>' +
                        '</thead><tbody id="insert_clone10"><tr><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td class="text-center"><input class="form-control requiredField" name="relative_name_1" id="relative_name_1" required></td>' +
                        '<td class="text-center"><input class="form-control requiredField" name="relative_position_1"  id="relative_position_1" required></td>' +
                        '<td class="text-center">-</td></tr></tbody></table><div></div></div></div>');

                $("#addMoreRelativesDetails").click(function(e){

                    var form_rows_count = $(".get_rows10").length;
                    form_rows_count++;
                    $("#insert_clone10").append('<tr class="remove_area10_'+form_rows_count+' get_rows10" id="">' +
                            '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                            '<td class="text-center"><input type="hidden" name="relatives_data[]" value='+form_rows_count+'>' +
                            '<input class="form-control requiredField" name="relative_name_'+form_rows_count+'" value="" id="relative_name_'+form_rows_count+'" required></td>' +
                            '<td class="text-center"><input class="form-control requiredField" name="relative_position_'+form_rows_count+'" value="" id="next_kin_relation_'+form_rows_count+'" required></td>' +
                            '<td class="text-center"><button type="button" onclick="removeRelativesDetailsSection('+form_rows_count+')" class="btn btn-xs btn-danger remove_area10_'+form_rows_count+'">Remove</button></td>' +
                            '</tr>');

                });

            }
            else
            {
                $("#relative_area_1").html('');
            }
        })




        $("input[name='transport_check_1']").click(function() {

            if($(this).val() == 'Yes')
            {
                $("#transport_particular_area_1").html(' <label class="sf-label">Particulars</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="Particulars" name="transport_particulars_1" id="transport_particulars_1" value="" />' +
                        '');
            }
            else
            {
                $("#transport_particular_area_1").html('');
            }
        });


        $('#gssp_verification_check').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#gssp_upload_area").html(' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="row"> &nbsp </div><div class="table-responsive">' +
                        '<table class="table table-bordered sf-table-list"><thead><th class="text-center col-sm-1">S.No</th>' +
                        '<th class="col-sm-4 text-center">Document Type:</th>' +
                        '<th class="col-sm-4 text-center">Document File</th>' +
                        '<th class="text-center"><button type="button" class="btn btn-primary btn-xs" id="addMoreDocument">Add More GSSP</button></th></thead>' +
                        '<tbody id="insert_gssp"><tr class="get_rows_gssp"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td id="get_clone_gssp" class="text-center"><input type="hidden" name="employeeGsspData[]" value="1">' +
                        '<select class="form-control requiredField" name="document_type_1" id="document_type_1" required>' +
                        '<option value="">Select</option><option value="Nadra">Nadra</option><option value="Police">Police</option>' +
                        '</select></td><td class="text-center"><input class="form-control" type="file" name="document_file_1" id="document_file_1" required></td>' +
                        '<td class="text-center">-</td></tr></tbody></table></div></div>');


                $("#addMoreDocument").click(function(e){
                    var form_rows_count = $(".get_rows_gssp").length;
                    form_rows_count++;
                    $("#insert_gssp").append("<tr class='get_rows_gssp remove_area_"+form_rows_count+"' >" +
                            "<td class='text-center'>" +
                            '<span class="badge badge-pill badge-secondary">'+form_rows_count+'<span></td>' +
                            '<td><input type="hidden" name="employeeGsspData[]" value="'+form_rows_count+'">' +
                            '<select class="form-control get_rows requiredField" required name="document_type_'+form_rows_count+'" id="document_type_'+form_rows_count+'">' +
                            '<option value="">Select</option><option value="Nadra">Nadra</option><option value="Police">Police</option></select></td>' +
                            '<td class="text-center"><input class="form-control requiredField" required type="file" name="document_file_'+form_rows_count+'" id="document_file_'+form_rows_count+'"></td>' +
                            '<td class="text-center"><button type="button" onclick="removeEmployeeGsspDocumentDataSection('+form_rows_count+')" class="btn btn-danger btn-xs">Remove</button>' +
                            '</td></tr>');

                });
            }
            else
            {
                $("#gssp_upload_area").html('');
            }

        });


        $('#documents_upload_check').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#file_upload_area").html('<label for="media">Upload File:</label>' +
                        '<input type="file" class="form-control" name="media[]" id="media" multiple>');
            }
            else
            {
                $("#file_upload_area").html('');
            }
        })


        $("#emp_code_1").change(function() {
            var emp_code = $("#emp_code_1").val();
            var m = '<?=Input::get('m')?>';
            $.ajax({
                url: '<?php echo url('/')?>/hdc/checkEmrNoExist',
                type: "POST",
                data: { _token: $('meta[name=csrf-token]').attr('content'), emp_code:emp_code ,m : m},
                success:function(data) {
                    if(data == 'success')
                    {
                        $('#emp_warning').html('');
                        $(".btn_disable").removeAttr("disabled");
                        $("#emrExistMessage").html('');
                    }
                    else
                    {
                        $('.btn_disable').attr('disabled', 'disabled');
                        $('#emp_warning').html('Please Remove Errors !');
                        $("#emrExistMessage").html(data);
                    }
                }
            });
        });

        $("#cnic_1").change(function() {
            var emp_cnic = $("#cnic_1").val();
            var m = '<?=Input::get('m')?>';
            $.ajax({
                url: '<?php echo url('/')?>/hdc/checkCnicNoExist',
                type: "POST",
                data: { _token: $('meta[name=csrf-token]').attr('content'), emp_cnic:emp_cnic, m: m},
                success:function(data) {
                    if(data == 'success')
                    {
                        $('#emp_warning').html('');
                        $(".btn_disable").removeAttr("disabled");
                        $("#cnicExistMessage").html('');
                    }
                    else
                    {
                        $('.btn_disable').attr('disabled', 'disabled');
                        $('#emp_warning').html('Please Remove Errors !');
                        $("#cnicExistMessage").html(data);
                    }
                }
            });
        });

        $('#life_time_cnic_1').click(function(){
            if($(this).is(":checked") == true)
            {
                $("#cnic_expiry_date_1").attr('disabled', 'disabled');
                $("#cnic_expiry_date_1").removeClass('requiredField');
            }

            else
            {
                $("#cnic_expiry_date_1").removeAttr('disabled');
                $("#cnic_expiry_date_1").addClass('requiredField');
            }

        });

        function departmentDependentDesignation() {

            var department_id = $("#emp_department_id").val();

            var m = '<?= Input::get('m'); ?>';
            if (department_id) {
                $('#depentdentDesignation').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/slal/departmentDependentDesignation',
                    type: "GET",
                    data: { department_id: department_id, m: m},
                    success: function (data) {
                        $('#depentdentDesignation').html('');
                        $('select[name="designation_1"]').empty();
                        $('select[name="designation_1"]').html(data);
                    }
                });
            } else {
                $('select[name="designation_1"]').empty();
            }

        }

        function locationDependentRegion() {
            var region_id = $("#region_id_1").val();

            if (region_id == '') {
                swal('Location','Select Region ');
                return false;
            } else {
                var m = '<?= Input::get('m'); ?>';
                if (region_id) {
                    $('#depentdentLoation').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/slal/locationDependentRegion',
                        type: "GET",
                        data: { region_id: region_id, m: m},
                        success: function (data) {
                            $('#depentdentLoation').html('');
                            $('select[name="branch_id_1"]').empty();
                            $('select[name="branch_id_1"]').html(data);
                        }
                    });
                } else {
                    $('select[name="branch_id_1"]').empty();
                }
            }
        }
     
    </script>
@endsection