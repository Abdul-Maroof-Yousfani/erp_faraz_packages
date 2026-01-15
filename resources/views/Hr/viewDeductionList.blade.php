<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

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
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <span class="subHeadingLabelClass">View Employee Deduction List</span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <input type="number" id="emr_no" name="emr_no" class="form-control" placeholder="Search">
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintDeductionList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('DeductionList','','1')?>
                                @endif
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-body" id="PrintDeductionList">
                                <span id="deduction-list">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list table-hover table-striped" id="DeductionList">
                                                    <thead>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center">Emp Code</th>
                                                    <th class="text-center">Employee Name</th>
                                                    <th class="text-center">Deduction Type</th>
                                                    <th class="text-center">Deduction Amount</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center col-sm-1 hidden-print">Action</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php $counter = 1;?>
                                                    @foreach($deduction as $key => $value)
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td class="text-center">{{ $value->emp_code }}</td>
                                                            <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emp_code,'emp_code') }}</td>
                                                            <td>{{ $value->deduction_type }}</td>
                                                            <td class="text-right">{{ number_format($value->deduction_amount,0) }}</td>
                                                            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                            <td class="text-center hidden-print">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                        @if(in_array('view', $operation_rights))
                                                                            <li role="presentation">
                                                                                <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewDeductionDetail','<?php echo $value->id;?>','View Deduction Detail Form','<?php echo $m; ?>')">
                                                                                    View
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                        @if(in_array('edit', $operation_rights))
                                                                            <li role="presentation">
                                                                                <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editDeductionDetailForm','<?php echo $value->id;?>','Edit Deduction Detail Form','<?php echo $m; ?>')">
                                                                                    Edit
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                        @if(in_array('delete', $operation_rights))
                                                                            @if($value->status == 1)
                                                                                <li role="presentation">
                                                                                    <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value->id ?>','deduction')">
                                                                                        Delete
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                        @endif
                                                                        @if(in_array('repost', $operation_rights))
                                                                            @if($value->status == 2)
                                                                                <li role="presentation">
                                                                                    <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $value->id ?>','deduction')">
                                                                                        Repost
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
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>

        $(document).ready(function() {
            var table = $('#DeductionList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false

            });

            $('#emr_no').keyup( function() {
                table.search(this.value).draw();
            });

        });

    </script>
@endsection