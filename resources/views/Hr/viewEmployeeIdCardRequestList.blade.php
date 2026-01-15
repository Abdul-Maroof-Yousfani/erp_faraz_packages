<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
//$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Employee ID Card Request</span>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitCleareanceList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list table-hover" id="EmployIdCardRequestList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center">EMR No.</th>
                                            <th class="text-center">Employee Name</th>
                                            <th class="text-center">Posted At</th>
                                            <th class="text-center">Replacement type</th>
                                            <th class="text-center">Payment</th>
                                            <th class="text-center">Approval Status</th>
                                            <th class="text-center">Action</th>

                                            </thead>
                                            <tbody>
                                            <?php $counter = 1;?>
                                            @foreach($employee_card_request as  $row)

                                                <tr>
                                                    <td class="text-center">{{ $counter++ }}</td>
                                                    <td class="text-center">{{ $row->emr_no }}</td>
                                                    <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $row->emr_no, 'emr_no') }} </td>
                                                    <td class="text-center">{{HrHelper::date_format($row->posted_at) }}</td>
                                                    <td class="text-center">
                                                        @if($row->replacement_type != '')
                                                            {{$row->replacement_type}}
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($row->payment != 0)
                                                            {{ number_format($row->payment,0) }}
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ HrHelper::getApprovalStatusLabel($row->approval_status) }}</td>

                                                    <td class="text-center hidden-print">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                @if(in_array('approve', $operation_rights))
                                                                    @if ($row->approval_status != 2)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="approveAndRejectTableRecord('<?php echo $m ?>','<?php echo $row->id;?>', '2', 'employee_card_request')">
                                                                                Approve
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('reject', $operation_rights))
                                                                    @if ($row->approval_status != 3)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="approveAndRejectTableRecord('<?php echo $m ?>','<?php echo$row->id;?>', '3', 'employee_card_request')">
                                                                                Reject
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('view', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewEmployeeIdCardRequestDetail','<?php echo $row->id; ?>','View Employee ID Card Request Detail','<?php echo $m;?>','hr/viewEmployeeIdCardRequestList')">
                                                                            View
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editEmployeeIdCardRequestDetailForm','<?php echo $row->id; ?>','Edit Employee ID Card Request Detail','<?php echo $m;?>')">
                                                                            Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('repost', $operation_rights))
                                                                    @if($row->status == 2)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $row->id; ?>','employee_card_request')">
                                                                                Repost
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($row->status == 1)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $row->id; ?>','employee_card_request')">
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

@endsection