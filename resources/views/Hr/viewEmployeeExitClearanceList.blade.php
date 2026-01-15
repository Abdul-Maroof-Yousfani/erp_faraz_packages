<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\FinalSettlement;
use App\Models\Employee;
?>

<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>


@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">View Employee Exit Clearance List</span>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitCleareanceList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list table-hover" id="EmployExitCleareanceList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center">EMR No.</th>
                                            <th class="text-center">Employee Name</th>
                                            <th class="text-center">Supervisor Name</th>
                                            <th class="text-center">Signed By Supervisor</th>
											<th class="text-center">Last Working Date</th>
											<th class="text-center">Approval Status</th> 
											<th class="text-center">Action</th>

                                            </thead>
                                            <tbody>
                                            <?php $counter = 1;?>
                                            @foreach($employee_exit as  $row)
                                                <?php
                                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                $emp_name = Employee::select('emp_name')->where([['emp_code', '=', $row->emp_code],['status', '!=', 2]])->first();
                                                $final_settlement = FinalSettlement::where([['emp_code','=',$row->emp_code]])->count();
                                                CommonHelper::reconnectMasterDatabase();
                                                ?>
                                                <tr>
                                                    <td class="text-center">{{ $counter++ }}</td>
                                                    <td class="text-center">{{ $row->emp_code }}</td>
													<td>{{ $emp_name->emp_name }} </td>
                                                    <td>{{ $row->supervisor_name }}</td>
                                                    <td class="text-center">{{ $row->signed_by_supervisor }}</td>
                                                    <td class="text-center">{{HrHelper::date_format($row->last_working_date) }}</td>
													<td class="text-center">{{ HrHelper::getApprovalStatusLabel($row->approval_status) }}</td>

                                                    <td class="text-center hidden-print">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                @if(in_array('view', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewEmployeeExitClearanceDetail','<?php echo $row->id; ?>','View Employee Exit CLearance Detail','<?php echo $m;?>','hr/viewEmployeeExitClearanceList')">
                                                                            View Exit Clearance
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editEmployeeExitClearanceDetailForm','<?php echo $row->id; ?>','Edit Employee Exit CLearance Detail Form','<?php echo $m;?>')">
                                                                            Edit Exit Clearance
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('view', $operation_rights))
                                                                    @if($final_settlement > 0)
                                                                        <li role="presentation">
                                                                            <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewFinalSettlementDetail','<?php echo $row->id. '|'. $row->emp_code ?>','View Final Settlement Detail','<?php echo $m;?>','hr/viewEmployeeExitClearanceList')">
                                                                                View Final Settlement
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    @if($final_settlement > 0)
                                                                        <li role="presentation">
                                                                            <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editFinalSettlementDetailForm','<?php echo $row->id.'|'.$row->emp_code  ?>','Edit Final Settlement Detail Form','<?php echo $m;?>')">
                                                                                Edit Final Settlement
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('repost', $operation_rights))
                                                                    @if($row->status == 2)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $row->id; ?>','employee_exit')">
                                                                                Repost
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($row->status == 1)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="deleteEmployeeExitClearance('<?php echo $m ?>','<?php echo $row->id; ?>', '<?php echo $row->emp_code; ?>','employee_exit')">
                                                                                 Delete
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function deleteEmployeeExitClearance(companyId,recordId,emr_no,tableName){
            var companyId;
            var recordId;
            var tableName;
            var emr_no;

            if(confirm("Do you want to delete this record ?") == true){
                $.ajax({
                    url: '<?php echo url('/')?>/cdOne/deleteEmployeeExitClearance',
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,tableName:tableName, emr_no:emr_no},
                    success:function(data) {
                        location.reload();
                    }
                });
            }
            else{
                return false;
            }
        }
    </script>

@endsection