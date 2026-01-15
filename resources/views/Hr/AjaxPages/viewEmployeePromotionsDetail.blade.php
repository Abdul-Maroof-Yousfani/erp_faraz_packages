<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\PromotionLetter;
?>
<style>
    hr{border-top: 1px solid cadetblue}
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="panel">

    <div class="panel-body" id="PrintLeaveApplicationRequestList">
        <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive LeavesData">
                    <table class="table table-bordered sf-table-list table-hover" id="LeaveApplicationRequestList">
                        <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Emp Code</th>
                            <th class="text-center">Emp Name</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Increment</th>
                            <th class="text-center">Salary</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Approval Status</th>
                            <th class="text-center">Status</th>
                            <th class="text-center hidden-print">Action</th>
                        </thead>
                        <tbody>
                        <?php $counter = 1;?>
                        @if($employeePromotions->count() > 0)
                            @foreach($employeePromotions->get() as $value)
                                <?php
                                CommonHelper::companyDatabaseConnection($m);
                                $employeeData = Employee::where('emp_code','=',$value->emp_code)->select('emp_name','emp_department_id');
                                $promotion_letter = PromotionLetter::where('promotion_id','=',$value->id);
                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ $value->emp_code }}</td>
                                    <td class="text-center">{{ $employeeData->value('emp_name') }}</td>
                                    <td class="text-center">{{HrHelper::getMasterTableValueById($m,'department','department_name',$employeeData->value('emp_department_id'))}}</td>
                                    <td class="text-center">{{HrHelper::getMasterTableValueById($m,'designation','designation_name',$value->designation_id)}}</td>
                                    <td class="text-right">{{ number_format($value->increment,0) }}</td>
                                    <td class="text-right">{{ number_format($value->salary,0) }}</td>
                                    <td class="text-center">{{HrHelper::date_format($value->promotion_date)}}</td>
                                    <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                    <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                @if(in_array('view', $operation_rights2))
                                                    @if($promotion_letter->count() > 0)
                                                        <li role="presentation">
                                                            <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewPromotionLetter','<?php echo $value->id ?>','View Promotion Letter','<?php echo $m ?>')">
                                                                View Letter
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if(in_array('approve', $operation_rights2))
                                                    @if($value->approval_status != '2')
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="approveAndRejectForAjaxPages('{{ $m }}','{{ $value->id }}','2','employee_promotion')">
                                                            Approve
                                                        </a>
                                                    </li>
                                                    @endif
                                                @endif
                                                @if(in_array('reject', $operation_rights2))
                                                    @if($value->approval_status != '3')
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="approveAndRejectForAjaxPages('{{ $m }}','{{ $value->id }}','3' ,'employee_promotion')">
                                                            Reject
                                                        </a>
                                                    </li>
                                                    @endif
                                                @endif
                                                @if(in_array('edit', $operation_rights2))
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editEmployeePromotionDetailForm','<?= $value->id ?>','View Employee Promotions Detail','<?php echo $m; ?>')">
                                                            Edit
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(in_array('delete', $operation_rights2))
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m; ?>','<?=$value->id?>', 'employee_promotion')">
                                                            Delete
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="12" style="color:red;font-weight: bold;">Record Not Found !</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
