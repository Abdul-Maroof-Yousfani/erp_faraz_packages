<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
$m = Input::get('m');
?>
<table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
    <thead>
    <th class="text-center">S No.</th>
    <th class="text-center">Emp Name</th>
    <th class="text-center">Emr No</th>
    <th class="text-center">Region Name</th>
    <th class="text-center">Categeory Name</th>
    <th class="text-center">Project Name</th>
    <th class="text-center">Leave Type</th>
    <th class="text-center">Day Type</th>
    <th class="text-center">Approval Status(HR)</th>
    <th class="text-center">Approval Status(GM)</th>
    <th class="text-center">Status</th>
    <th class="text-center hidden-print">Action</th>

    </thead>
    <tbody>
    <?php $counter = 1;
        if(count($leave_application_request_list) != '0'){
    ?>

    @foreach($leave_application_request_list as $value)
        <?php
        CommonHelper::companyDatabaseConnection($value->company_id);
        $emp_name =  Employee::where([['leaves_policy_id','=',$value->leave_policy_id],['emr_no','=',$value->emr_no]]);
        CommonHelper::reconnectMasterDatabase();
        if($emp_name->first() != ''){
        ?>
        <tr>
            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
            <td class="text-center">{{$emp_name->value('emp_name')}}</td>
            <td class="text-center">{{ $emp_name->value('emr_no') }}</td>
            <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$emp_name->value('region_id'))}}</td>
            <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$emp_name->value('employee_category_id'))}}</td>
            <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$emp_name->value('employee_project_id'))}}</td>
            <td class="text-center" style="color:green">{{ $leave_type_name = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$value->emr_no)}}</td>
            <td class="text-center" style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_m) }}</td>
            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
            <td class="text-center hidden-print">
                <button class="btn-info btn-xs" onclick="showDetailModelTwoParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emr_no;?>','Edit Leave Application Detail','<?=$value->company_id?>')">
                    <span class="glyphicon glyphicon-edit"></span>
                </button>
                <button onclick="LeaveApplicationRequestDetail('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emr_no?>','<?=$value->company_id?>')" class="btn btn-xs btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample<?=$value->id?>" aria-expanded="false" aria-controls="collapseExample">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </button>
                @if ($value->status == 2)
                    <button data-toggle="tooltip" data-placement="right" title="Repost" onclick="RepostLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-info" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                @else
                    <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-danger" type="button"><span class="glyphicon glyphicon-remove"></span></button>
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                    <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                </div>
            </td>
        </tr>
        <?php } ?>
    @endforeach
    <?php } else { ?>
        <tr>
            <td colspan="12" class="text-danger text-center">No Record Found</td>
        </tr>
    <?php } ?>
    </tbody>
</table>