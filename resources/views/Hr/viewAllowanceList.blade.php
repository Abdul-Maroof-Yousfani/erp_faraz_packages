<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
?>

<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')

    <style>
        td{ padding: 2px !important;}
        th{ padding: 2px !important;}
    </style>
    <div class="panel">
        <div class="panel-body">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <span class="subHeadingLabelClass">View Allowance List</span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <input type="number" id="emp_code" name="emp_code" class="form-control emp_code" placeholder="Search">
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintAllownceList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('AllowanceList','','1')?>
                                @endif
                            </div>
                        </div>
                        <div class="row"></div>
                        <div class="panel">
                            <div class="panel-body" id="PrintAllownceList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list table-hover table-striped" id="AllowanceList">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Emp Code</th>
                                                <th class="text-center">Employee Name</th>
                                                <th class="text-center">Allowance Type</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center col-sm-1 hidden-print">Action</th>
                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                @foreach($allowance as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $counter++ }}</td>
                                                        <td class="text-center">{{ $value->emp_code }}</td>
                                                        <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emp_code,'emp_code') }}</td>
                                                        <td>{{ HrHelper::getMasterTableValueById($m,'allowance_type','allowance_type',$value->allowance_type)  }}</td>
                                                        <td class="text-right">{{ number_format($value->allowance_amount,0) }}</td>
                                                        <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                        <td class="text-center hidden-print">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions <span class="caret"></span></button>
                                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                    @if(in_array('view', $operation_rights))
                                                                        <li role="presentation">
                                                                            <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewAllowanceDetail','<?php echo $value->id;?>','View Allowance Detail','<?php echo $m; ?>')">
                                                                                View
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if(in_array('edit', $operation_rights))
                                                                        <li role="presentation">
                                                                            <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editAllowanceDetailForm','<?php echo $value->id;?>','Edit Allowance Detail','<?php echo $m; ?>')">
                                                                                Edit
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if(in_array('delete', $operation_rights))
                                                                        @if($value->status == 1)
                                                                            <li role="presentation">
                                                                                <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value->id ?>','allowance')">
                                                                                    Delete
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                    @if(in_array('repost', $operation_rights))
                                                                        @if($value->status == 2)
                                                                            <li role="presentation">
                                                                                <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $value->id ?>','allowance')">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function() {
            var table = $('#AllowanceList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false

            });

            $('.emp_code').keyup( function() {
                table.search(this.value).draw();
            });

        });

    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection

