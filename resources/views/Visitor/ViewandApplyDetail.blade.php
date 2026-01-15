<?php use App\Helpers\HrHelper; ?>

@include('includes._normalUserNavigation')
<style>
    .form-control{padding:4px;}
    .single-job .totalbusiness-button {
        width: 160px;
        text-align: center;
    }
    .totalbusiness-button.small, input[type="button"].small, input[type="submit"].small {
        min-width: 110px;
    }
    .totalbusiness-button.small {
        font-size: 11px;
        padding: 11px 17px;
    }
    .totalbusiness-button, input[type="button"], input[type="submit"] {
        border: 1px solid #000;
        border-radius: 0 !important;
        background: transparent;
        color: #000;
        margin-right: 0;
    }
    .totalbusiness-button, input[type="button"], input[type="submit"] {
        display: inline-block;
        margin-bottom: 15px;
        outline: 0;
        cursor: pointer;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
        -moz-border-radius: 3px;

    }
    .guideLine{
        text-decoration: underline;
        color:#d58512;
    }
    td{ padding: 3px !important;}
    th{ padding: 3px !important;}
</style>

<div class="well">
    <div class="panel">
         <div class="panel-body">
         <div class="row">
            <div class="col-lg-12 col-md-122 col-xs-12 text-center">
                <h1 style="text-transform: uppercase;font-weight: bold;"><?=$EmployeeRequisitionDetail->job_title?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-122 col-xs-12">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered sf-table-list">
                            <thead>
                                <th>Job Title</th>
                                <td><?=$EmployeeRequisitionDetail->job_title?></td>
                            </thead>
                            <thead>
                                <th>No. Of Emp Required</th>
                                <td><?=$EmployeeRequisitionDetail->no_of_emp_required?></td>
                            </thead>
                            <thead>
                                <th>Ex-Emp Seperation Date</th>
                                <td><?=$EmployeeRequisitionDetail->ex_emp_seperation_date?></td>
                            </thead>
                            <thead>
                                <th>Ex-Emp Benefits</th>
                                <td><?=$EmployeeRequisitionDetail->ex_emp_benefits?></td>
                            </thead>
                            <thead>
                                <th>additional_replacement</th>
                                <td><?=$EmployeeRequisitionDetail->additional_replacement?></td>
                            </thead>

                            <thead>
                                <th> Department </th>
                                <td><?=HrHelper::getMasterTableValueById($companyId,'department','department_name',$EmployeeRequisitionDetail->department_id)?></td>
                            </thead>
                            <thead>
                                <th>Employment</th>
                                <td><?=HrHelper::getMasterTableValueById($companyId,'job_type','job_type_name',$EmployeeRequisitionDetail->job_type_id)?></td>
                            </thead>
                            <thead>
                                <th>Location</th>
                                <td><?=$EmployeeRequisitionDetail->location?></td>
                            </thead>
                      </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered sf-table-list">
                            <thead>
                                <th>Job description Exist</th>
                                <td><?=$EmployeeRequisitionDetail->job_description_exist?></td>
                            </thead>
                            <thead>
                                <th>Designation</th>
                                <td><?=HrHelper::getMasterTableValueById($companyId,'designation','designation_name',$EmployeeRequisitionDetail->designation_id)?></td>
                            </thead>
                            <thead>
                                <th>Qualification</th>
                                <td><?=HrHelper::getMasterTableValueById($companyId,'qualification','qualification_name',$EmployeeRequisitionDetail->qualification_id)?></td>
                            </thead>
                            <thead>
                                <th>Career Level</th>
                                <td><?=$EmployeeRequisitionDetail->career_level?></td>
                            </thead>
                            <thead>
                                <th>Experience </th>
                                <td><?=$EmployeeRequisitionDetail->experience?></td>
                            </thead>
                            <thead>
                                <th>Age Group From & To</th>
                                <td><?=$EmployeeRequisitionDetail->age_group_from.'-'.$EmployeeRequisitionDetail->age_group_to?></td>
                            </thead>
                            <thead>
                                <th>Gender</th>
                                <td><?=($EmployeeRequisitionDetail->gender == 1 ? 'Male':'Female')?></td>
                            </thead>
                            <thead>
                                <th>Apply Before Date</th>
                                <td><?=HrHelper::date_format($EmployeeRequisitionDetail->apply_before_date)?></td>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
                <div class="col-lg-12 col-md-122 col-xs-12">
                    <table class="table table-bordered sf-table-list">
                        <thead>
                        <th>Other Requirment</th>
                        </thead>
                        <thead>
                        <td><?=$EmployeeRequisitionDetail->other_requirment?></td>
                        </thead>
                    </table>
                </div>
             </div>
             <?php echo Form::open(array('method' => 'POST', 'url' => 'vad/addVisitorApplyDetail','id'=>'addVisitorApplyDetail',"enctype"=>"multipart/form-data"));?>
             <div class="col-lg-12 col-md-122 col-xs-12">

                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                 <input type="hidden" name="recordId" value="{{ Request::segment(4) }}">
                 <input type="hidden" name="companyId" value="{{ Request::segment(3) }}">
                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                     <div class="form-group">
                         <label>Email</label>
                         <input type="email" class="form-control" name="email" required>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
                     <div class="form-group">
                        <label>Contact no</label>
                         <input type="number" class="form-control" name="contact_no" required>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                     <div class="form-group">
                         <label>Exprected Salary</label>
                         <input type="number" class="form-control" name="expected_salary" required>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                     <div class="form-group">
                         <label>CV / Resume</label>
                         <input type="file" class="form-control" name="cv" required>
                     </div>
                 </div>

             </div>
             <div class="col-lg-12 col-md-122 col-xs-12">&nbsp;</div>
             <div class="col-lg-12 col-md-122 col-xs-12">
                     <div class="col-lg-8 col-md-3 col-sm-8 col-xs-12">
                        <p>
                            <b>
                                If you,re up for the challenge, please send a cover letter and resume via email with the subject line
                                <span class="guideLine"><?=$EmployeeRequisitionDetail->job_title?></span> to <span class="guideLine">careers@company.com</span>
                                &nbsp;&nbsp;
                                <span style="font-size: 20px;">OR</span>
                            </b>
                        </p>
                     </div>
                 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <button type="submit" class="btn btn-success totalbusiness-button small">Apply Now</button>
                 </div>
             </div>

             </div>
             <?php echo Form::close();?>

        </div>
    </div>
</div>
