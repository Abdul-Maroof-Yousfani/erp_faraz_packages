<?php
use App\Helpers\HrHelper;
$data='';
$data.='
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list">
                    <thead>
                        <th class="text-center col-sm-1">Job Title</th>
                        <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->job_title.'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">No. Of Emp Required</th>
                        <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->no_of_emp_required.'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">Ex-Emp Seperation Date</th>
                        <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->ex_emp_seperation_date.'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">Ex-Emp Benefits</th>
                        <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->ex_emp_benefits.'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">additional_replacement</th>
                        <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->additional_replacement.'</td>
                    </thead>

                     <thead>
                         <th class="text-center col-sm-1"> Department </th>
                         <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$EmployeeRequisitionDetail->department_id).'</td>
                    </thead>
                    <thead>
                         <th class="text-center col-sm-1">Employment</th>
                         <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'job_type','job_type_name',$EmployeeRequisitionDetail->job_type_id).'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">Location</th>
                        <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->location.'</td>
                    </thead>
                     <thead>
                         <th class="text-center col-sm-1">Experience </th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->experience.'</td>
                    </thead>


                     <thead>
                         <th class="text-center col-sm-1">Age Group From & To</th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->age_group_from.'-'.$EmployeeRequisitionDetail->age_group_to.'</td>
                    </thead>
                    <thead>
                         <th class="text-center col-sm-1">Gender</th>
                         <td class="text-center col-sm-1">'.($EmployeeRequisitionDetail->gender == 1 ? 'Male':'Female').'</td>
                   </thead>
                   <thead>
                        <th class="text-center col-sm-1">Approval Status</th>
                        <td class="text-center col-sm-1">'.$approval_status.'</td>
                    </thead>
                </table>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list">
                    <thead>
                         <th class="text-center col-sm-1">Job description Exist</th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->job_description_exist.'</td>
                    </thead>
                      <thead>
                         <th class="text-center col-sm-1">Job description Attached</th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->job_description_attached.'</td>
                    </thead>
                      <thead>
                         <th class="text-center col-sm-1">Requisitioned By</th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->requisitioned_by.'</td>
                    </thead>
                      <thead>
                         <th class="text-center col-sm-1"> recommended_by </th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->recommended_by.'</td>
                    </thead>
                      <thead>
                         <th class="text-center col-sm-1">Chairman Approval</th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->chairman_approval.'</td>
                    </thead>


                    <thead>
                        <th class="text-center col-sm-1">Designation</th>
                        <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$EmployeeRequisitionDetail->designation_id).'</td>
                    </thead>
                     <thead>
                         <th class="text-center col-sm-1">Qualification</th>
                         <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'qualification','qualification_name',$EmployeeRequisitionDetail->qualification_id).'</td>
                    </thead>
                    <thead>
                         <th class="text-center col-sm-1">Career Level</th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->career_level.'</td>
                    </thead>

                     <thead>
                        <th class="text-center col-sm-1">Apply Before Date</th>
                        <td class="text-center col-sm-1">'.HrHelper::date_format($EmployeeRequisitionDetail->apply_before_date).'</td>
                     </thead>
                     <thead>
                         <th class="text-center col-sm-1">Posted on Date</th>
                         <td class="text-center col-sm-1">'.HrHelper::date_format($EmployeeRequisitionDetail->date).'</td>
                    </thead>
                     <thead>
                         <th class="text-center col-sm-1">Created By</th>
                         <td class="text-center col-sm-1">'.$EmployeeRequisitionDetail->username.'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">Status</th>
                        <td class="text-center col-sm-1">'.$status.'</td>
                    </thead>

                </table>
            </div>
          </div>
      </div>
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
          <table class="table table-bordered sf-table-list">
          <thead>
               <th class="text-center">Other Requirment</th>
           </thead>
           <thead>
               <th class="text-center">'.$EmployeeRequisitionDetail->other_requirment.'</th>
          </thead>
          </table>
        </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
          <table class="table table-bordered sf-table-list">
           <thead>
               <th class="text-center">Replacement Description</th>
           </thead>
           <thead>
               <th class="text-center">'.$EmployeeRequisitionDetail->replacement_description.'</th>
          </thead>
           </table>
        </div>
      </div>
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
          <table class="table table-bordered sf-table-list">
          <thead>
               <th class="text-center">Additional Description</th>
          </thead>
           <thead>
               <th class="text-center">'.$EmployeeRequisitionDetail->additional_description.'</th>
          </thead>
           </table>
        </div>
      </div>

</div>';

$data.=' ';
echo json_encode(array('data'=>$data));



?>
