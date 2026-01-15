<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeePromotion;
use App\Models\EmployeeTransfer;


?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="HrReport">
                        @if($employee_detail->count() > 0)
                            <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">EMR NO</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Father Name</th>
                                <th class="text-center">Designation</th>
                                <th class="text-center">Region</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Employee Project</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">CNIC</th>
                                <th class="text-center">Contact No</th>
                                <th class="text-center">Emergency No</th>
                                <th class="text-center col-sm-1">Joining Date</th>
                                <th class="text-center col-sm-1">Birth Date</th>
                                <th class="text-center">Joining Salary</th>
                                <th class="text-center">Current Salary</th>
                                <th class="text-center">Job Type</th>
                                {{--<th class="text-center">Fuel Allowances</th>--}}
                                {{--<th class="text-center">Supervisory Allowances</th>--}}
                                <th class="text-center">Status</th>
                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($employee_detail->get() as $key => $y)
                                <?php

                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $current_salary = $y->emp_salary;
                                    if(EmployeePromotion::where([['emr_no', '=', $y->emr_no]])->exists()):
                                        $employee_promotion = EmployeePromotion::where([['emr_no', '=', $y->emr_no]])->orderBy('id', 'desc')->first();
                                        $current_salary = $employee_promotion->salary;
                                    endif;
                                    $EmployeeTransfer = EmployeeTransfer::where([['emr_no', '=', $y->emr_no]])->orderBy('id','desc')->first();
                                    if(count($EmployeeTransfer) != '0'){
                                        $location_id = $EmployeeTransfer->location_id;
                                    }
                                    else{
                                        $location_id = $y->branch_id;
                                    }
                                     CommonHelper::reconnectMasterDatabase();
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center ">{{ $y->emr_no}}</td>
                                    <td class="text-center">{{ $y->emp_name}}</td>
                                    <td class="text-center">{{ $y->emp_father_name}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$y->designation_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$y->region_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$y->employee_category_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$y->employee_project_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',$location_id)}}</td>
                                    <td class="text-center">{{ $y->emp_cnic}}</td>
                                    <td class="text-center">{{ $y->emp_contact_no}}</td>
                                    <td class="text-center">{{ $y->emergency_no}}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->emp_joining_date) }}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->emp_date_of_birth) }}</td>
                                    <td class="text-center">{{ number_format($y->emp_joining_salary,0) }}</td>
                                    <td class="text-center">{{ number_format($current_salary,0) }}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'job_type','job_type_name',$y->emp_employementstatus_id)}}</td>
                                    <td class="text-center">{{HrHelper::getStatusLabel($y->status)}}</td>

                                </tr>
                            @endforeach
                            @else
                                <tr><td class="text-center" style="color:red;font-weight: bold;" colspan="14">Record Not Found !</td></tr>
                            @endif
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>