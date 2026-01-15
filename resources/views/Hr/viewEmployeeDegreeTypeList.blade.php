<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                @if(in_array('print', $operation_rights))
                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintMaritalStatusList','','1');?>
                @endif
                @if(in_array('export', $operation_rights))
                    <?php echo CommonHelper::displayExportButton('MaritalStatusList','','1')?>
                @endif
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">View Employee Degree Type List</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body" id="PrintMaritalStatusList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list table-hover" id="MaritalStatusList">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Employee Degree Type</th>
                                                <th class="text-center">Created By</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center hidden-print">Action</th>
                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                @foreach($EmployeeDegreeType as $key => $y)
                                                    <tr>
                                                        <td class="text-center"><?php echo $counter++;?></td>
                                                        <td><?php echo $y->degree_type_name;?></td>
                                                        <td><?php echo $y->username;?></td>
                                                        <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
                                                        <td class="text-center hidden-print">
                                                            @if(in_array('edit', $operation_rights))
                                                                <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('hr/editEmployeeDegreeTypeDetailForm','<?php echo $y->id ?>','Employee Degree Type Edit Detail Form','<?php echo $m?>')">
                                                                    <span class="glyphicon glyphicon-edit"></span>
                                                                </button>
                                                            @endif
                                                            @if(in_array('repost', $operation_rights))
                                                                @if($y->status == 2)
                                                                    <button class="delete-modal btn btn-xs btn-primary" onclick="repostMasterTableRecords('<?php echo $y->id ?>','degree_type')">
                                                                        <span class="glyphicon glyphicon-refresh"></span>
                                                                    </button>
                                                                @endif
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($y->status == 1)
                                                                    <button class="delete-modal btn btn-xs btn-danger" onclick="deleteRowMasterTable('<?php echo $y->degree_type_name ?>','<?php echo $y->id ?>','degree_type')">
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