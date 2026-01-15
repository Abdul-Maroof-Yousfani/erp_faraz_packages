<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Buildings;

?>

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
    /*td{ padding: 0px !important;}*/
    /*th{ padding: 0px !important;}*/
    .img-circle {
        width: 150px;
        height: 150px;
        border: 2px solid #ccc;
        padding: 4px;
        border-radius: 50%;
        margin-bottom: 32px;
        margin-top: -78px;
        z-index: 10000000;}



</style>




<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-right">
                    <?php echo CommonHelper::displayPrintButtonInBlade('printEmployeeDetail','','1');?>
                </div>
            </div>
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="company_id" value="<?=Input::get('m')?>">
                <input type="hidden" name="login_check" value="<?=$employee_detail->can_login?>">
                <input type="hidden" name="emp_code" value="<?=$employee_detail->emp_code?>">

                <?php $acc_pass = DB::table('users')->select('password')->where([['emp_code','=',$employee_detail->emp_code]])->value('password'); ?>
                <input type="hidden" name="old_password" value="<?=$acc_pass?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel" id="printEmployeeDetail">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <div class="hr-border" style="border: 1px solid #e5e5e5b0; margin-top: 89px;" ></div>
                                <img id="img_file_1" class="img-circle" src="<?= Storage::url($employee_detail->img_path)?>">
                            </div>
                            <div class="text-center">
                                <label style="cursor:pointer;">
                                    <input type="file" id="file_1" name="fileToUpload_1" accept="image/*" capture style="display:none"/>
                                    <img style="width: 50px;height: 50px;" src="<?= url('assets/img/cam.png')?>" id="upfile1" style="cursor:pointer" />
                                    Change Image
                                </label>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row main">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                    <h4  style="text-decoration: underline;font-weight: bold;">Basic Information</h4>
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row table-responsive">
                                    <table style="table-layout: fixed;" class="table table-bordered sf-table-list ">
                                        <thead>
                                        <th class="text-center"> EMR-No </th>
                                        <td class="text-center"><?=$employee_detail->emp_code?></td>
                                        <th class="text-center"> Employee Name </th>
                                        <td class="text-center"><?=$employee_detail->emp_name?></td>
                                        <th class="text-center"> Father Name </th>
                                        <td class="text-center"><?=$employee_detail->emp_father_name?></td>
                                        </thead>

                                        <thead>
                                        <th class="text-center"> Date of Birth </th>
                                        <td class="text-center"><?=HrHelper::date_format($employee_detail->emp_date_of_birth) ?></td>
                                        <th colspan="2" class="text-center"> Department / Sub Department </th>
                                        <td colspan="2" class="text-center">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'department', 'department_name', $employee_detail->emp_department_id, 'id') }}
                                        </td>
                                        </thead>

                                        <thead>
                                        <th class="text-center"> Place Of Birth </th>
                                        <td class="text-center"><?=$employee_detail->emp_place_of_birth?></td>
                                        <th class="text-center"> Employment Status </th>
                                        <td class="text-center">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'job_type', 'job_type_name', $employee_detail->emp_employementstatus_id, 'id') }}
                                        </td>
                                        <th class="text-center"> Joining Date </th>
                                        <td class="text-center"><?=HrHelper::date_format($employee_detail->emp_joining_date)?></td>
                                        </thead>

                                        <thead>
                                        <th class="text-center"> Nationality </th>
                                        <td class="text-center"><?=$employee_detail->nationality?></td>
                                        <th class="text-center"> Gender </th>
                                        <td class="text-center">
                                            @if($employee_detail->emp_gender == 1) {{ 'Male' }} @endif
                                            @if($employee_detail->emp_gender == 2) {{ 'Female' }} @endif
                                        </td>
                                        <th class="text-center"> Province </th>
                                        <td class="text-center"><?=$employee_detail->province ?></td>
                                        </thead>

                                        <thead>
                                        <th class="text-center"> Email </th>
                                        <td class="text-center"><?=$employee_detail->emp_email ?></td>
                                        <th class="text-center"> CNIC </th>
                                        <td class="text-center"><?=$employee_detail->emp_cnic ?></td>
                                        <th class="text-center"> CNIC Expiry Date </th>
                                        <td class="text-center">
                                            @if($employee_detail->life_time_cnic == 1)
                                                Life Time Validity
                                            @else
                                                <?=HrHelper::date_format($employee_detail->emp_cnic_expiry_date) ?>
                                            @endif

                                        </td>

                                        </thead>

                                        <thead>
                                        <th class="text-center"> Cell No / Mobile No </th>
                                        <td class="text-center"><?=$employee_detail->emp_contact_no?></td>
                                        <th class="text-center"> Home No# </th>
                                        <td class="text-center"><?=$employee_detail->contact_home ?></td>
                                        <th class="text-center"> Office No# </th>
                                        <td class="text-center"><?=$employee_detail->contact_office ?></td>
                                        </thead>

                                        <thead>
                                        <th class="text-center"> Emergency No# </th>
                                        <td class="text-center">
                                            <?=$employee_detail->emergency_no?>
                                        </td>
                                        <th class="text-center"> Marital Status </th>
                                        <td class="text-center">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'marital_status', 'marital_status_name', $employee_detail->emp_marital_status, 'id') }}
                                        </td>
                                        <th class="text-center"> Religion </th>
                                        <td class="text-center"><?=$employee_detail->relegion ?></td>
                                        </thead>

                                        <thead>
                                        <th class="text-center"> Joining Salary </th>
                                        <td class="text-center">{{ number_format($employee_detail->emp_joining_salary,0) }}</td>
                                        <th class="text-center"> Current Salary </th>
                                        <td class="text-center">{{ number_format($employee_detail->emp_salary,0) }}</td>
                                        <th class="text-center"> Designation </th>
                                        <td class="text-center">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $employee_detail->designation_id, 'id') }}
                                        </td>
                                        </thead>

                                        <thead>
                                        <th class="text-center"> Region </th>
                                        <td class="text-center">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'regions', 'employee_region', $employee_detail->region_id, 'id') }}
                                        </td>
                                        {{--<th class="text-center"> Location / Branch </th>--}}
                                        {{--<td class="text-center">--}}
                                            {{--{{ HrHelper::getMasterTableValueByIdAndColumn($m, 'locations', 'employee_location',$location_id, 'id') }}--}}
                                        {{--</td>--}}

                                        </thead>

                                        <thead>
                                        <th class="text-center"> Overtime Policy </th>
                                        <td class="text-center">@if($employee_detail->working_hours_policy_id == 1) MFM (Salary / 30 / 12) @elseif($employee_detail->working_hours_policy_id == 2) Other (Salary / 210)*2 @else -- @endif</td>
                                        <th colspan="2" class="text-center"> Leaves Policy </th>
                                        <td colspan="2" class="text-center">{{ HrHelper::getMasterTableValueByIdAndColumn($m, 'leaves_policy', 'leaves_policy_name', $employee_detail->leaves_policy_id, 'id') }}</td>
                                        </thead>

                                        <thead>

                                        <th class="text-center"> Eobi </th>
                                        <td class="text-center">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'eobi', 'EOBI_name', $employee_detail->eobi_id, 'id') }}
                                        </td>

                                        </thead>


                                        <thead>
                                        <th class="text-center"> Passport No </th>
                                        <td class="text-center"><?=$employee_detail->passport_no ?></td>
                                        <th class="text-center"> Valid From </th>
                                        <td class="text-center"><?= HrHelper::date_format($employee_detail->passport_valid_from) ?></td>
                                        <th class="text-center"> Valid To </th>
                                        <td class="text-center"><?= HrHelper::date_format($employee_detail->passport_valid_to) ?></td>
                                        </thead>

                                        <thead>
                                        <th class="text-center"> Driving License No </th>
                                        <td class="text-center"><?=$employee_detail->driving_license_no ?></td>
                                        <th class="text-center"> Valid From </th>
                                        <td class="text-center"><?=HrHelper::date_format($employee_detail->driving_license_valid_from) ?></td>
                                        <th class="text-center"> Valid To </th>
                                        <td class="text-center"><?= HrHelper::date_format($employee_detail->driving_license_valid_to) ?></td>
                                        </thead>

                                        <thead>

                                        <th class="text-center"> Transport Owned </th>
                                        <td class="text-center">
                                            <input @if($employee_detail->transport_owned == 'Yes' ) checked @endif type="radio" name="transport_check_1" id="transport_check_1" value="Yes" />&nbsp; Yes &nbsp;
                                            <input @if($employee_detail->transport_owned == 'No' ) checked @endif type="radio" name="transport_check_1" id="transport_check_1" value="No" />&nbsp; No
                                        </td>

                                        <th class="text-center">
                                            @if($employee_detail->transport_owned == 'Yes')
                                                Particulars
                                            @endif
                                        </th>
                                        <td class="text-center">
                                            @if($employee_detail->transport_owned == 'Yes')
                                                <?=$employee_detail->transport_particular?>
                                            @endif
                                        </td>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            {{--new employee details--}}
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Address</h4>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table style="table-layout: fixed;" class="table table-bordered sf-table-list ">
                                        <thead>
                                        <th> Current Address </th>
                                        <td class="text-center">
                                            <?=$employee_detail->residential_address ?>
                                        </td>
                                        </thead>
                                        <thead>
                                        <th> Permanent Address </th>
                                        <td class="text-center">
                                            <?=$employee_detail->permanent_address ?>
                                        </td>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            {{--family data--}}
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Immediate Family Data</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">
                                    <h4 style="text-decoration: underline;"><?=$employee_family_detail->count()?>
                                        Spouse, Children, Parents, Brothers, Sisters
                                    </h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                    <input @if($employee_family_detail->count() > 0) checked @endif type="checkbox" name="family_data_check_1" id="family_data_check_1">
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="family_data_area_1">
                                @if($employee_family_detail->count() > 0)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <div class="table-responsive" id="family_append_area_1">
                                            @foreach($employee_family_detail->get() as $employeeFamilyData)

                                                <table style="table-layout: fixed;" class="table table-bordered sf-table-list get_rows3 remove_area3_<?=$employeeFamilyData->id?>" id="get_clone3">
                                                    <thead>
                                                    <th>Name<span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="hidden" name="family_data[]" id="get_rows3" value="{{$employeeFamilyData->id}}">
                                                    </th>
                                                    <td class="text-center">
                                                        <?=$employeeFamilyData->family_name?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Relation<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employeeFamilyData->family_relation?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Age<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employeeFamilyData->family_age?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Designation / Occuption<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employeeFamilyData->family_occupation?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Organization<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employeeFamilyData->family_organization?>
                                                    </td>
                                                    </thead>
                                                </table>
                                            @endforeach
                                        </div>
                                    </div>

                                @endif
                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Bank Account Details</h4>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <input @if($employee_bank_detail->count() > 0 ) checked @endif type="checkbox" name="bank_account_check_1" id="bank_account_check_1">
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="bank_account_area_1">
                                @if($employee_bank_detail->count() > 0 )
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table style="table-layout: fixed;" class="table table-bordered sf-table-list">
                                                <thead>
                                                <th class="text-center">Title Of Account</th>
                                                <th class="text-center">Bank Name</th>
                                                <th class="text-center">Account No</th>
                                                </thead>
                                                <tbody id="insert_clone">
                                                <tr class="get_rows_education">
                                                    <td class="text-center"><?=$employee_bank_detail->value('account_title')?></td>
                                                    <td class="text-center"><?=$employee_bank_detail->value('bank_name')?></td>
                                                    <td class="text-center"><?=$employee_bank_detail->value('account_no')?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Educational / Technical Background</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;">Start from Recent</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                    <input @if($employee_educational_detail->count() > 0 ) checked @endif type="checkbox" name="education_check_1" id="education_check_1">
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="education_area_1">
                                @if($employee_educational_detail->count() > 0 )
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>
                                                <th class="text-center">Degree / Diploma</th><th class="text-center">Major Subjects</th>
                                                </thead>
                                                <tbody id="insert_clone">
                                                <?php $counter =1; ?>
                                                @foreach($employee_educational_detail->get() as $employee_educational_value)
                                                    <tr class="get_rows_education" id="remove_area_<?=$employee_educational_value->id?>">
                                                        <td class="text-center">
                                                            <input type="hidden" name="education_data[]" value="{{$employee_educational_value->id}}">
                                                            <span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                                        <td class="text-center"><?=$employee_educational_value->institute_name?></td>
                                                        <td class="text-center"><?=$employee_educational_value->year_of_admission?></td>
                                                        <td class="text-center"><?=$employee_educational_value->year_of_passing?></td>
                                                        <td class="text-center"><input type="hidden" name="qualificationSection[]">
                                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'degree_type', 'degree_type_name', $employee_educational_value->degree_type, 'id') }}

                                                        </td>
                                                        <td class="text-center"><?=$employee_educational_value->major_subjects?></td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                @endif
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Language Proficiency</h4>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <input @if($employee_language_proficiency->count() > 0) checked @endif type="checkbox" name="language_check_1" id="language_check_1">
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="language_area_1">
                                @if($employee_language_proficiency->count() > 0)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list" >
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Language<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Read<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Write<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Speak<span class="rflabelsteric"><strong>*</strong></span></th>
                                                </thead>
                                                <tbody id="insert_clone7">
                                                <?php $counter2 = 1; ?>
                                                @foreach($employee_language_proficiency->get() as $language_proficiency_detail)
                                                    <tr class="get_rows7" id="remove_area7_<?=$language_proficiency_detail->id?>">
                                                        <td class="text-center">
                                                            <span class="badge badge-pill badge-secondary">{{$counter2++}}</span>
                                                            <input type="hidden" name="language_data[]" value="{{$language_proficiency_detail->id}}">
                                                        </td>
                                                        <td id="get_clone7" class="text-center">
                                                            <?=$language_proficiency_detail->language_name?>
                                                        </td>
                                                        <td class="text-center">
                                                            <b>Good : <input @if($language_proficiency_detail->reading_skills == 'Good') checked @endif type="radio" name="reading_skills_<?=$language_proficiency_detail->id?>" value="Good"></b>
                                                            <b>Fair : <input @if($language_proficiency_detail->reading_skills == 'Fair') checked @endif type="radio" name="reading_skills_<?=$language_proficiency_detail->id?>" value="Fair"></b>
                                                            <b>Poor : <input @if($language_proficiency_detail->reading_skills == 'Poor') checked @endif type="radio" name="reading_skills_<?=$language_proficiency_detail->id?>" value="Poor"></b>
                                                        </td>
                                                        <td class="text-center">
                                                            <b>Good : <input @if($language_proficiency_detail->reading_skills == 'Good') checked @endif type="radio" name="writing_skills_<?=$language_proficiency_detail->id?>" value="Good"></b>
                                                            <b>Fair : <input @if($language_proficiency_detail->reading_skills == 'Fair') checked @endif type="radio" name="writing_skills_<?=$language_proficiency_detail->id?>" value="Fair"></b>
                                                            <b>Poor : <input @if($language_proficiency_detail->reading_skills == 'Poor') checked @endif type="radio" name="writing_skills_<?=$language_proficiency_detail->id?>" value="Poor"></b>
                                                        </td>
                                                        <td class="text-center">
                                                            <b>Good : <input @if($language_proficiency_detail->reading_skills == 'Good') checked @endif type="radio" name="speaking_skills_<?=$language_proficiency_detail->id?>" value="Good"></b>
                                                            <b>Fair : <input @if($language_proficiency_detail->reading_skills == 'Fair') checked @endif type="radio" name="speaking_skills_<?=$language_proficiency_detail->id?>" value="Fair"></b>
                                                            <b>Poor : <input @if($language_proficiency_detail->reading_skills == 'Poor') checked @endif type="radio" name="speaking_skills_<?=$language_proficiency_detail->id?>" value="Poor"></b>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Health Information</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline"> Any disorder regarding</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                    <input @if($employee_health_data->count() > 0) checked @endif type="checkbox" name="health_type_check_1" id="health_type_check_1">
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="health_type_area_1">
                                @if($employee_health_data->count() > 0)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table style="table-layout: fixed;" class="table table-bordered sf-table-list" >
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Health Type<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Yes/No<span class="rflabelsteric"><strong>*</strong></span></th>
                                                </thead>
                                                <tbody id="insert_clone8">
                                                <?php $counter3=1; ?>
                                                @foreach($employee_health_data->get() as $employee_health_value)
                                                    <tr class="get_rows8 remove_area8_<?=$employee_health_value->id?>">
                                                        <td class="text-center">
                                                            <span class="badge badge-pill badge-secondary">{{$counter3++}}</span>
                                                            <input type="hidden" name="health_data[]" value="<?=$employee_health_value->id?>">
                                                        </td>
                                                        <td class="text-center">
                                                            <?= $employee_health_value->health_type ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= $employee_health_value->health_check ?>
                                                        </td>
                                                    </tr>
                                                    <script>
                                                        $(document).ready(function () {
                                                            $('#health_type_<?=$employee_health_value->id?>').select2();
                                                        });
                                                    </script>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive">
                                            <table style="table-layout: fixed;" class="table table-bordered sf-table-list" >
                                                <thead>
                                                <th class="text-center">Any Physical Handicap</th>
                                                <th class="text-center">Height</th>
                                                <th class="text-center">Weight</th>
                                                <th class="text-center">Blood Group</th>
                                                </thead>
                                                <tbody id="insert_clone8">
                                                <tr class="get_rows8 remove_area8_<?=$employee_health_value->id?>">
                                                    <td class="text-center"><?=$employee_health_value->physical_handicap?></td>
                                                    <td class="text-center"><?=$employee_health_value->height?></td>
                                                    <td class="text-center"><?=$employee_health_value->weight?></td>
                                                    <td class="text-center"><?=$employee_health_value->blood_group?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                @endif

                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Activities</h4>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                    <h4 style="text-decoration: underline;">
                                        Associations, societies, clubs you were / are member of
                                    </h4>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
                                    <input @if($employee_activity_data->count() > 0 ) checked @endif type="checkbox" name="activity_check_1" id="activity_check_1">
                                </div>

                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="activity_area_1">
                                @if($employee_activity_data->count() > 0 )
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table style="table-layout: fixed;" class="table table-bordered sf-table-list" >
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Name Of Institution<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Position Held<span class="rflabelsteric"><strong>*</strong></span></th>
                                                </thead>
                                                <tbody id="insert_clone8">
                                                <?php $counter3=1; ?>
                                                @foreach($employee_activity_data->get() as $employee_activity_detail )
                                                    <tr class="get_rows8 remove_area8_<?=$employee_activity_detail->id?>">
                                                        <td class="text-center">
                                                            <span class="badge badge-pill badge-secondary">{{$counter3++}}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <?=$employee_activity_detail->institution_name?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?=$employee_activity_detail->position_held?>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="insert_clone4"></div>

                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Work Experience</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;">Most recent first</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                    <input @if($employee_work_experience->count() > 0) checked @endif type="checkbox" name="work_experience_check_1" id="work_experience_check_1">
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="work_experience_area_1">
                                @if($employee_work_experience->count() > 0)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Name Of Employeer</th><th class="text-center">Position Held</th>
                                                <th class="text-center">Career Level</th><th class="text-center">Started</th><th class="text-center">Ended</th>
                                                <th class="text-center">Last Drawn Salary</th><th class="text-center">Reason For Leaving</th>
                                                </thead>
                                                <tbody id="insert_clone1">
                                                <?php $counter4 = 1; ?>
                                                @foreach($employee_work_experience->get() as $employee_work_experience_value)
                                                    <tr class="get_rows1" id="remove_area1_<?=$employee_work_experience_value->id?>"><td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter4++}}</span></td>
                                                        <td id="get_clone1" class="text-center">
                                                            <input type="hidden" name="work_experience_data[]" value="{{$employee_work_experience_value->id}}">
                                                            <?=$employee_work_experience_value->employeer_name?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?=$employee_work_experience_value->position_held?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= $employee_work_experience_value->career_level ?>
                                                        </td>
                                                        <td class="text-center"><?=$employee_work_experience_value->started?></td>
                                                        <td class="text-center"><?=$employee_work_experience_value->ended?></td>
                                                        <td class="text-center"><?=$employee_work_experience_value->last_drawn_salary?></td>
                                                        <td class="text-center"><?=$employee_work_experience_value->reason_leaving?></td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <br><label class="sf-label">Have you ever been dismissed / suspended from service ?</label>
                                        <input @if($employee_work_experience_value->suspend_check == 'no') checked @endif type="radio" name="suspend_check_1" id="suspend_check_1" value="no"/>&nbsp; No
                                        <input @if($employee_work_experience_value->suspend_check == 'yes') checked @endif type="radio" name="suspend_check_1" id="suspend_check_1" value="yes" />&nbsp; Yes &nbsp;'
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" id="suspend_detail_1">
                                        @if($employee_work_experience_value->suspend_check == 'yes')
                                            <label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control requiredField" value="<?= $employee_work_experience_value->suspend_reason ?>" name="suspend_reason_1" id="suspend_reason_1" value="" />
                                            <script>

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
                                            </script>
                                        @endif
                                    </div>

                                @endif
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
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
                                    <input @if($employee_reference_data->count() > 0 ) checked @endif type="checkbox" name="reference_check_1" id="reference_check_1">
                                </div>
                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row" id="reference_area_1">
                                @if($employee_reference_data->count() > 0 )
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="table-responsive">
                                            @foreach($employee_reference_data->get() as $employee_reference_value)
                                                <table style="table-layout: fixed;" class="table table-bordered sf-table-list get_rows2 remove_area2_<?=$employee_reference_value->id?>" id="get_clone2">
                                                    <thead>
                                                    <th>Name<span class="rflabelsteric"><strong>*</strong></span></th><td class="text-center">
                                                        <input type="hidden" name="reference_data[]" value="<?=$employee_reference_value->id?>">
                                                        <?=$employee_reference_value->reference_name?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Designation<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employee_reference_value->reference_designation?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Organization<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employee_reference_value->reference_organization?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Address<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employee_reference_value->reference_address?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Country<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employee_reference_value->reference_country?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Contact Number<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employee_reference_value->reference_contact?>
                                                    </td>
                                                    </thead>
                                                    <thead>
                                                    <th>Relationship<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <td class="text-center">
                                                        <?=$employee_reference_value->reference_relationship?>
                                                    </td>
                                                    </thead>
                                                </table>

                                            @endforeach
                                            <div id="insert_clone2"></div>
                                        </div>
                                    </div>

                                @endif
                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Next Of Kin Details</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;">In Case of Employee Death</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                    <input @if($employee_kins_data->count() > 0) checked @endif type="checkbox" name="kins_check_1" id="kins_check_1">
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="kins_area_1">
                                @if($employee_kins_data->count() > 0)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list" >
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Relation<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Percentage<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Address / Mobile No#<span class="rflabelsteric"><strong>*</strong></span></th>
                                                </thead>
                                                <tbody id="insert_clone9">
                                                <?php $counter5 = 1; ?>
                                                @foreach($employee_kins_data->get() as $employee_kins_value)
                                                    <tr class="remove_area6_<?=$employee_kins_value->id?> get_rows9">
                                                        <td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter5++}}</span></td>
                                                        <td class="text-center">
                                                            <input type="hidden" name="kins_data[]" value="<?=$employee_kins_value->id?>">
                                                        <?=$employee_kins_value->next_kin_name?>
                                                        <td class="text-center">
                                                            <?=$employee_kins_value->next_kin_relation?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?=$employee_kins_value->next_kin_percentage?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?=$employee_kins_value->next_kin_address?>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">
                                        Do you have any relatives in this company ?
                                    </h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4>
                                        No : <input @if($employee_relatives_data->count() == 0) checked @endif type="radio" value="No" name="relative_check_1" id="relative_check_1">
                                        &nbsp;&nbsp;
                                        Yes : <input @if($employee_relatives_data->count() > 0) checked @endif type="radio" value="Yes" name="relative_check_1" id="relative_check_1">
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                    </h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                            </div>
                            <div class="row" id="relative_area_1">
                                @if($employee_relatives_data->count() > 0)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list" >
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Position<span class="rflabelsteric"><strong>*</strong></span></th>

                                                </thead>
                                                <tbody id="insert_clone10">
                                                @foreach($employee_relatives_data->get() as $employee_relatives_value)
                                                    <tr class="get_rows10 remove_area10_<?=$employee_relatives_value->id?>">
                                                        <td class="text-center">
                                                            <input type="hidden" name="relatives_data[]" value="<?=$employee_relatives_value->id?>"><span class="badge badge-pill badge-secondary">1</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <?=$employee_relatives_value->relative_name?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?=$employee_relatives_value->relative_position?>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Have you ever been convicted of a crime ?</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4>
                                        No : <input
                                                @if($employee_other_details->count() > 0 )
                                                @if($employee_other_details->value('crime_check')== 'No') checked @endif
                                                @endif
                                                type="radio" value="No" name="crime_check_1" id="crime_check_1" class="relative_check">
                                        &nbsp;&nbsp;
                                        Yes : <input
                                                @if($employee_other_details->count() > 0 )
                                                @if($employee_other_details->value('crime_check') == 'Yes') checked @endif
                                                @endif
                                                type="radio" value="Yes" name="crime_check_1" id="crime_check_1" class="relative_check">
                                    </h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="crime_check_input_1">
                                @if($employee_other_details->count() > 0 )
                                    @if($employee_other_details->value('crime_check') == 'Yes')
                                        <label class="sf-label">Detail</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly type="text" class="form-control requiredField" placeholder="Detail" name="crime_detail_1" id="crime_detail_1" value="<?=$employee_other_details->value('crime_detail')?>" />
                                    @endif
                                @endif
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4 style="text-decoration: underline;font-weight: bold;">Any Additional Information you wish to provide ?
                                    </h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4>
                                        No : <input
                                                @if($employee_other_details->count() > 0 )
                                                @if($employee_other_details->value('additional_info_check') == 'No') checked @endif
                                                @endif
                                                type="radio" value="No" name="additional_info_check_1" id="additional_info_check_1" class="relative_check">
                                        &nbsp;&nbsp;
                                        Yes : <input
                                                @if($employee_other_details->count() > 0 )
                                                @if($employee_other_details->value('additional_info_check') == 'Yes') checked @endif
                                                @endif
                                                type="radio" value="Yes" name="additional_info_check_1" id="additional_info_check_1" class="relative_check">
                                    </h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="additional_info_input_1">
                                @if($employee_other_details->count() > 0 )
                                    @if($employee_other_details->value('additional_info_check') == 'Yes')
                                        <label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>' +
                                        <input readonly type="text" class="form-control requiredField" placeholder="Detail" name="additional_info_detail_1" id="additional_info_detail_1" value="<?=$employee_other_details->value('additional_info_detail')?>" />
                                    @endif
                                @endif

                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: gainsboro">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <h4 style="text-decoration: underline;">Login Credentials</h4>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <h4 style="text-decoration: underline;">Can Login ? :
                                        <input @if($employee_detail->can_login == 'yes'){{ 'checked' }} @endif type="checkbox" name="can_login_1" id="can_login_1" value="yes">
                                    </h4>
                                </div>
                            </div>

                            @if($login_credentials)
                                <div class="row" id="credential_area_1" style="display:@if($employee_detail->can_login == 'yes'){{ 'block' }} @else {{ 'none' }}@endif;">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Account type</label>
                                        <select class="form-control" name="account_type_1">
                                            <option @if($login_credentials->acc_type == 'user'){{ 'selected' }} @endif value="user">User</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <label class="sf-label">Password</label>
                                        <input type="password" class="form-control" name="password_1" value="">
                                    </div>
                                </div>
                            @else
                                <div class="row" id="credential_area_1" style="display: none;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Account type</label>
                                        <select class="form-control" name="account_type_1">
                                            <option value="user">User</option>
                                            {{--<option value="client">Client</option>--}}
                                            {{--<option value="company">Company</option>--}}
                                            {{--<option value="master">Master</option>--}}
                                        </select>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <label class="sf-label">Password</label>
                                        <input type="text" class="form-control" name="password_1">
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
