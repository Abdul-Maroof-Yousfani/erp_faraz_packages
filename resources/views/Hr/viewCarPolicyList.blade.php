<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
?>

@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintCarPolicyList','','1');?>
                        <?php echo CommonHelper::displayExportButton('CarPolicyList','','1')?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        @include('Hr.'.$accType.'hrMenu')
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Car Policy List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="panel">
                                <div class="panel-body" id="PrintCarPolicyList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list" id="CarPolicyList">
                                                    <thead>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center">Policy Name</th>
                                                    <th class="text-center">Designation</th>
                                                    <th class="text-center">Vehicle Type</th>
                                                    <th class="text-center">Salary Range</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center hidden-print">Action</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php $counter = 1;?>
                                                    @foreach($carPolicy as $key => $value)
                                                        <tr>
                                                            <td class="text-center"><?php echo $counter++;?></td>
                                                            <td class="text-center"><?php echo $value['policy_name']?></td>
                                                            <td class="text-center"><?php echo CommonHelper::getMasterTableValueById($m,'designation','designation_name',$value['designation_id']);?></td>
                                                            <td class="text-center">
                                                                <?php
                                                                 $vehicleType =  DB::table('vehicle_type')->select('vehicle_type_name','vehicle_type_cc')->where('id','=',$value['vehicle_type_id'])->first();
                                                                  echo $vehicleType->vehicle_type_name."&nbsp;".$vehicleType->vehicle_type_cc."CC";
                                                                 ?>
                                                            </td>
                                                            <td class="text-center"><?php echo $value['start_salary_range']."--".$value['end_salary_range'];?></td>
                                                            <td class="text-center"><?php HrHelper::getStatusLabel($value['status']); ?></td>
                                                            <td class="text-center hidden-print">
                                                                <button class="edit-modal btn-xs btn btn-primary" onclick="showDetailModelTwoParamerterJson('hdc/viewCarPolicy','<?php echo $value['id']; ?>','View Car Policy Detail ','<?php echo $m; ?>')">
                                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                                </button>
                                                                <button class="edit-modal btn-xs btn btn-info" onclick="showMasterTableEditModel('hr/editCarPolicyDetailForm','<?php echo $value['id'] ?>','Leave Type Edit Detail Form','<?php echo $m?>')">
                                                                    <span class="glyphicon glyphicon-edit"></span>
                                                                </button>
                                                                <button class="delete-modal btn-xs btn btn-danger" onclick="deleteRowCompanyRecords('<?php echo $m?>','<?php echo $value['id']?>','car_policy')">
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                </button>
                                                                @if($value['status'] == '2')
                                                                    <button class="delete-modal btn-xs btn btn-primary" onclick="repostOneTableRecords('<?php echo $m?>','<?php echo $value['id'] ?>','car_policy','status')">
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