<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
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
                            <span class="subHeadingLabelClass">View Loan Requests List</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            @if(in_array('print', $operation_rights))
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintLoanRequestList','','1');?>
                            @endif
                            @if(in_array('export', $operation_rights))
                                <?php echo CommonHelper::displayExportButton('LoanRequestList','','1')?>
                            @endif
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-body" id="PrintLoanRequestList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list table-hover table-striped" id="LoanRequestList">
                                            <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Emp Code</th>
                                                <th class="text-center">Employee Name</th>
                                                <th class="text-center">Month - Year</th>
                                                <th class="text-center">Loan Amount</th>
                                                <th class="text-center">Remarks</th>
                                                <th class="text-center">Approval Status</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center hidden-print">Action</th>
                                            </thead>
                                            <tbody>
                                            <?php $counter=1;?>
                                            @foreach($loanRequest as $key => $y)
                                                <tr>
                                                    <td class="text-center">{{ $counter++ }}</td>
                                                    <td class="text-center">{{ $y->emp_code }}</td>
                                                    <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$y->emp_code,'emp_code') }}</td>
                                                    <td class="text-center">{{ date('M - Y',strtotime($y->year.'-'.$y->month)) }}</td>
                                                    <td class="text-right">{{ number_format($y->loan_amount,0) }}</td>
                                                    <td class="text-center">
                                                        @if($y->loan_status == 1)
                                                            <span class='label label-success'>Paid</span>
                                                        @else
                                                            <span class='label label-warning'>Not Paid</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ HrHelper::getApprovalStatusLabel($y->approval_status) }}</td>
                                                    <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
                                                    <td class="text-center hidden-print">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                @if(in_array('approve', $operation_rights))
                                                                    @if ($y->approval_status != 2)
                                                                        <li role="presentation">
                                                                            <a class="edit-modal btn" onclick="approveAndRejectTableRecord('<?php echo $m; ?>','<?php echo $y->id;?>', '2', 'loan_request')">
                                                                                Approve
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('reject', $operation_rights))
                                                                    @if ($y->approval_status != 3)
                                                                        <li role="presentation">
                                                                            <a class="edit-modal btn" onclick="approveAndRejectTableRecord('<?php echo $m; ?>','<?php echo $y->id;?>', '3', 'loan_request')">
                                                                                Reject
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('view', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewLoanRequestDetail','<?php echo $y->id;?>','View Loan Request Detail','<?php echo $m; ?>','hr/viewLoanRequestList')">
                                                                            View
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editLoanRequestDetailForm','<?php echo $y->id;?>','Edit Loan Request Detail','<?php echo $m; ?>')">
                                                                            Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('repost', $operation_rights))
                                                                    @if($y->status == 2)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn"onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id;?>','loan_request')">
                                                                                Repost
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($y->status == 1)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $y->id;?>','loan_request')">
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

    <script src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
@endsection