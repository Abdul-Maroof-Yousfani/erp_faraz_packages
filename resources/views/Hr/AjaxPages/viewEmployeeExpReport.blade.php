<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeePromotion;
use App\Models\EmployeeTransfer;
$location_id = '';

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
                            <th class="text-center">Employee Project</th>
                            <th class="text-center">Emp Joining Date</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Region</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Location</th>
                            <th class="text-center">Qualification</th>
                            <th class="text-center">Last Company</th>
                            <th class="text-center">Year of Exp</th>
                            <th class="text-center">No. of Services with MFM</th>
                            <th class="text-center">Status</th>

                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($employee_detail->get() as $key => $y)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $EmployeeTransfer = EmployeeTransfer::where([['emr_no', '=', $y->emr_no]])->orderBy('location_id','desc')->first();
                                if(count($EmployeeTransfer) != '0'){
                                    $location_id = $EmployeeTransfer->location_id;
                                }
                                else{
                                    $location_id = $y->branch_id;
                                }

                                $last_qualification  = \App\Models\EmployeeEducationalData::select('degree_type')->where([['emr_no','=',$y->emr_no],['status','=',1]])
                                    ->orderBy('id','desc')
                                    ->offset(0)->limit(1);
                                $last_work_exp  = \App\Models\EmployeeWorkExperience::where([['emr_no','=',$y->emr_no],['status','=',1]])
                                    ->orderBy('id','desc')
                                    ->offset(0)->limit(1);

                                $current_salary = $y->emp_salary;
                                if(EmployeePromotion::where([['emr_no', '=', $y->emr_no]])->exists()):
                                    $employee_promotion = EmployeePromotion::where([['emr_no', '=', $y->emr_no]])->orderBy('id', 'desc')->first();
                                    $current_salary = $employee_promotion->salary;
                                endif;
                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center ">{{ $y->emr_no}}</td>
                                    <td class="text-center">{{ $y->emp_name}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$y->employee_project_id)}}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->emp_joining_date)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$y->designation_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$y->region_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$y->employee_category_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',$location_id)}}</td>
                                    <td class="text-center">
                                        <?php
                                        if($last_qualification->count() > 0):
                                            echo HrHelper::getMasterTableValueById(Input::get('m'),'degree_type','degree_type_name',$last_qualification->value('degree_type'));
                                        else:
                                            echo "-";
                                        endif;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if($last_work_exp->count() > 0):
                                            echo $last_work_exp->value('employeer_name');
                                        else:
                                            echo "-";
                                        endif;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if($last_work_exp->count() > 0):

                                            if($last_work_exp->value('started') != '' && $last_work_exp->value('ended') != ''):

                                                $diff = abs(strtotime($last_work_exp->value('ended')) - strtotime($last_work_exp->value('started')));
                                                $years = floor($diff / (365*60*60*24));
                                                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                                                printf("%d years, %d months, %d days\n", $years, $months, $days);

                                            else:
                                                echo "-";
                                            endif;
                                        else:
                                            echo "-";
                                        endif;
                                        ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        $diff = abs(strtotime(date('Y-m-d')) - strtotime($y->emp_joining_date));
                                        $years = floor($diff / (365*60*60*24));
                                        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                                        printf("%d years, %d months, %d days\n", $years, $months, $days);
                                        ?>
                                    </td>
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