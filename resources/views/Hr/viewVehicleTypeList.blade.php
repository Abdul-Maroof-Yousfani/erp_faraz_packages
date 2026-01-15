<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintVehicleTypeList','','1');?>
                    <?php echo CommonHelper::displayExportButton('VehicleTypeList','','1')?>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        @include('Hr.'.$accType.'hrMenu')
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Shift Type List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="panel">
                                <div class="panel-body" id="PrintVehicleTypeList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list" id="VehicleTypeList">
                                                    <thead>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center">Vehicle Type Name</th>
                                                    <th class="text-center">Vehicle Type CC</th>
                                                    <th class="text-center">Created By</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center hidden-print">Action</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php $counter = 1;?>
                                                    @foreach($vehicleType as $key => $value)
                                                        <tr>
                                                            <td class="text-center"><?php echo $counter++;?></td>
                                                            <td><?php echo $value->vehicle_type_name;?></td>
                                                            <td><?php echo $value->vehicle_type_cc;?></td>
                                                            <td><?php echo $value->username;?></td>
                                                            <td><?php HrHelper::getStatusLabel($value->status);?></td>
                                                            <td class="text-center hidden-print">
                                                                <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('hr/editVehicleTypeDetailForm','<?php echo $value->id ?>','Vehicle Type Edit Detail Form','<?php echo $m?>')">
                                                                    <span class="glyphicon glyphicon-edit"></span>
                                                                </button>
                                                                 <button class="delete-modal btn btn-xs btn-danger" onclick="deleteRowMasterTable('<?php echo $value->shift_type_name ?>','<?php echo $value->id ?>','vehicle_type')">
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                </button>
                                                                @if($value->status == '2')
                                                                <button class="delete-modal btn btn-xs btn-primary" onclick="repostMasterTableRecords('<?php echo $value->id ?>','vehicle_type')">
                                                                    <span class="glyphicon glyphicon-refresh"></span>
                                                                </button>
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
    </div>
@endsection