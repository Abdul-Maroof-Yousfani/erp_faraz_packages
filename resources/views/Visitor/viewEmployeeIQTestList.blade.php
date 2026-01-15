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
                                    <span class="subHeadingLabelClass">View Employee IQ Test List</span>
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

                                                    <th class="text-center">Emp.Name</th>

                                                    <th class="text-center">Emp Email</th>
                                                    <th class="text-center">Emp Contact No</th>
                                                    <th class="text-center">Emp CNIC</th>
                                                    <th class="text-center">View</th>

                                                    </thead>
                                                    <tbody>
                                                    <?php $counter = 1;?>
                                                    @foreach($quiztest as  $row)
                                                        <tr>
                                                            <td class="text-center"><?php echo $counter++;?></td>
                                                            <td class="text-center"><?php echo CommonHelper::getEmpDataForExitClearance($row['emp_id'],0)?></td>
                                                            <td class="text-center"><?php echo CommonHelper::getEmpDataForExitClearance($row['emp_id'],2)?></td>
                                                            <td class="text-center"><?php echo CommonHelper::getEmpDataForExitClearance($row['emp_id'],3)?></td>
                                                            <td class="text-center"><?php echo CommonHelper::getEmpDataForExitClearance($row['emp_id'],4)?></td>
<td class="text-center"><a href="{{url('visitor/viewQuizTestResult/'.$row['emp_id'])}}" class="btn btn-xs btn-success">
        <span class="glyphicon glyphicon-eye-open"></span></a></td>

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