<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\TransferedLeaves;
$current_date = date('Y-m-d');
?>
<style>
    td{ padding: 0px !important;}
    th{ padding: 0px !important;}
</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @foreach($companiesList as $companyData)
                    <div class="table-responsive">
                        <?php $count =1;
                        CommonHelper::companyDatabaseConnection($companyData->id);
                        $departments = Employee::select('emp_department_id')->groupBy('emp_department_id')->get()->toArray();
                        ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                                <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo HrHelper::date_format($current_date);?></label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 30px !important; font-style: inherit;font-family: -webkit-body; font-weight: bold;">
                                        {{ $companyData->name}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                                <?php $nameOfDay = date('l', strtotime($current_date)); ?>
                                <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                            </div>
                            <div style="line-height:5px;">&nbsp;</div>
                        </div>
                        @foreach($departments as $value)

                            <table class="table table-responsive table-bordered table-condensed">
                                <thead>
                                <tr style="background-color: #ddd;" >
                                    <td colspan="28">
                                        <div class="row text-center">
                                            <h4><b><?= HrHelper::getMasterTableValueById($companyData->id,'department','department_name',$value["emp_department_id"])?>
                                                </b>
                                            </h4>
                                        </div>
                                    </td>
                                </tr>
                                </thead>
                                <thead>
                                <tr>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Acc No.</th>
                                    <th class="text-center">Emp Name </th>
                                    <th class="text-center">Casual</th>
                                    <th class="text-center">Sick</th>
                                    <th class="text-center">Annual</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php

                                CommonHelper::companyDatabaseConnection($companyData->id);
                                $all_emp = Employee::select("emp_code","emp_name",'leaves_policy_id')
                                    ->where([['status','=',1],['leaves_policy_id','>',0],["emp_department_id","=",$value["emp_department_id"]]])
                                    ->get()->toArray(); ?>
                                <?php
                                CommonHelper::reconnectMasterDatabase();

                                ?>
                                @foreach($all_emp as $value)
                                    <tr class="text-center">
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $value["emp_code"] }}</td>
                                        <td>{{ $value["emp_name"] }}</td>
                                        <td>
                                            <?php
                                            $TransferedCasualLeaves = TransferedLeaves::where([['emp_code','=',$value["emp_code"]],['leaves_policy_id','=',$value["leaves_policy_id"]],['status','=','1']])->value('casual_leaves');
                                            $total_casual_leaves = DB::table("leaves_data")
                                                ->select('no_of_leaves')
                                                ->where([['leave_type_id','=',3],['leaves_policy_id', '=', $value["leaves_policy_id"]]]);

                                            $taken_casual_leaves = DB::table("leave_application_data")
                                                ->select(DB::raw("SUM(no_of_days) as taken_casual_leaves"))
                                                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                ->where([['leave_application.emp_code', '=', $value["emp_code"]], ['leave_application.status', '=', '1'],
                                                    ['leave_application.approval_status', '=', '2'],['leave_application.leave_type','=','3'],['leave_application.leave_policy_id','=',$value["leaves_policy_id"]]])
                                                ->first();

                                            echo $total_casual_leaves->value('no_of_leaves')+$TransferedCasualLeaves-$taken_casual_leaves->taken_casual_leaves;
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            $total_sick_leaves = DB::table("leaves_data")
                                                ->select('no_of_leaves')
                                                ->where([['leave_type_id','=',2],['leaves_policy_id', '=', $value["leaves_policy_id"]]]);


                                            $taken_sick_leaves = DB::table("leave_application_data")
                                                ->select(DB::raw("SUM(no_of_days) as taken_sick_leaves"))
                                                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                ->where([['leave_application.emp_code', '=', $value["emp_code"]], ['leave_application.status', '=', '1'],
                                                    ['leave_application.approval_status', '=', '2'],['leave_application.leave_type','=','2'],['leave_application.leave_policy_id','=',$value["leaves_policy_id"]]])
                                                ->first();
                                            echo $total_sick_leaves->value('no_of_leaves')-$taken_sick_leaves->taken_sick_leaves;
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            $TransferedAnnualLeaves = TransferedLeaves::where([['emp_code','=',$value["emp_code"]],['leaves_policy_id','=',$value["leaves_policy_id"]],['status','=','1']])->value('annual_leaves');
                                            $total_annual_leaves = DB::table("leaves_data")
                                                ->select('no_of_leaves')
                                                ->where([['leave_type_id','=',1],['leaves_policy_id', '=', $value["leaves_policy_id"]]]);

                                            $taken_annual_leaves = DB::table("leave_application_data")
                                                ->select(DB::raw("SUM(no_of_days) as taken_annual_leaves"))
                                                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                ->where([['leave_application.emp_code', '=', $value["emp_code"]], ['leave_application.status', '=', '1'],
                                                    ['leave_application.approval_status', '=', '2'],['leave_application.leave_type','=','1'],['leave_application.leave_policy_id','=',$value["leaves_policy_id"]]])
                                                ->first();
                                            echo $total_annual_leaves->value('no_of_leaves')+$TransferedAnnualLeaves-$taken_annual_leaves->taken_annual_leaves;
                                            ?>
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
