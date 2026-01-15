<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>

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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Regions List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintMaritalStatusList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('MaritalStatusList','','1')?>
                                @endif
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-body" id="PrintMaritalStatusList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list table-hover table-striped" id="MaritalStatusList">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Region</th>
                                                <th class="text-center">Created By</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                @foreach($EmployeeRegions as $key => $y)
                                                    <tr>
                                                        <td class="text-center"><?php echo $counter++;?></td>
                                                        <td><?php echo $y->employee_region;?></td>
                                                        <td><?php echo $y->username;?></td>
                                                        <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
                                                        <td class="text-center">
                                                            @if(in_array('edit', $operation_rights))
                                                                <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('hr/editEmployeeRegionsDetailForm','<?php echo $y->id ?>','Employee Regions Edit Detail Form','<?php echo $m?>')">
                                                                    <span class="glyphicon glyphicon-edit"></span>
                                                                </button>
                                                            @endif
                                                            @if(in_array('repost', $operation_rights))
                                                                @if($y->status == 2)
                                                                    <button class="delete-modal btn btn-xs btn-primary" onclick="repostMasterTableRecords('<?php echo $y->id ?>','regions')">
                                                                        <span class="glyphicon glyphicon-refresh"></span>
                                                                    </button>
                                                                @endif
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($y->status == 1)
                                                                    <button class="delete-modal btn btn-xs btn-danger" onclick="deleteRowMasterTable('<?php echo $y->id ?>','regions')">
                                                                        <span class="glyphicon glyphicon-trash"></span>
                                                                    </button>
                                                                @endif
                                                            @endif
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

@endsection