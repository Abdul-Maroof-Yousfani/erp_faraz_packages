<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
$m = Input::get('m');

?>

<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}

</style>

@extends('layouts.default')
@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Advance Salary List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintAdvancedSalaryList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('AdvancedSalayList','','1')?>
                                @endif
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-body" id="PrintAdvancedSalaryList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list table-hover table-striped" id="AdvancedSalayList">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Emp Code</th>
                                                <th class="text-center">Employee Name</th>
                                                <th class="text-center">Amount Needed</th>
                                                <th class="text-center">Salary Need On</th>
                                                <th class="text-center">Deduction Month - year</th>
                                                <th class="text-center">Remarks</th>
                                                <th class="text-center">Approval Status</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center col-sm-1 hidden-print">Action</th>
                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                @foreach($advance_salary as $key => $y)
                                                    <tr>
                                                        <td class="text-center">{{ $counter++ }}</td>
                                                        <td class="text-center">{{ $y->emp_code }}</td>
                                                        <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$y->emp_code,'emp_code') }}</td>
                                                        <td class="text-right">{{ number_format($y->advance_salary_amount,0) }}</td>
                                                        <td class="text-center">{{ HrHelper::date_format($y->salary_needed_on) }}</td>
                                                        <td class="text-center">{{ date('M - Y',strtotime($y->deduction_year.'-'.$y->deduction_month)) }}</td>
                                                        <td class="text-center">
                                                            @if($y->advance_salary_status == 1)
                                                                <span class='label label-success'>Paid</span>
                                                            @else
                                                                <span class='label label-warning'>Not Paid</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">{{HrHelper::getApprovalStatusLabel($y->approval_status)}}</td>
                                                        <td class="text-center">{{HrHelper::getStatusLabel($y->status)}}</td>
                                                        <td class="text-center hidden-print">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions <span class="caret"></span></button>
                                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                    @if(in_array('approve', $operation_rights))
                                                                        @if ($y->approval_status != 2)
                                                                            <li role="presentation">
                                                                                <a class="edit-modal btn" onclick="approveAndRejectTableRecord('<?php echo $m; ?>','<?php echo $y->id;?>', '2', 'advance_salary')">
                                                                                    Approve
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                    @if(in_array('reject', $operation_rights))
                                                                        @if ($y->approval_status != 3)
                                                                            <li role="presentation">
                                                                                <a class="edit-modal btn" onclick="approveAndRejectTableRecord('<?php echo $m; ?>','<?php echo $y->id;?>', '3', 'advance_salary')">
                                                                                    Reject
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                    @if(in_array('view', $operation_rights))
                                                                        <li role="presentation">
                                                                            <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewAdvanceSalaryDetail','<?php echo $y->id;?>','View Advance Salary Detail','<?php echo $m; ?>')">
                                                                                View
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if(in_array('edit', $operation_rights))
                                                                        <li role="presentation">
                                                                            <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editAdvanceSalaryDetailForm','<?php echo $y->id;?>','Edit Advance Salary Detail','<?php echo $m; ?>')">
                                                                                Edit
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if(in_array('repost', $operation_rights))
                                                                        @if ($y->status == 2)
                                                                            <li role="presentation">
                                                                                <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $y->id ?>','advance_salary','approval_status')">
                                                                                    Repost
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                    @if(in_array('delete', $operation_rights))
                                                                        @if ($y->status == 1)
                                                                            <li role="presentation">
                                                                                <a class="delete-modal btn" onclick="deleteAdvanceSalaryWithPaySlip('<?php echo $m ?>','<?php echo $y->id ?>','advance_salary')">
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
    </div>
    <script src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
@endsection