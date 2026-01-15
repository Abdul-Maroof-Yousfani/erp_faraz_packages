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
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">View Employee Probationary Period</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpProbabtionList','','1');?>
                            <?php echo CommonHelper::displayExportButton('EmpProbabtionList','','1')?>
                        </div>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body" id="PrintEmpProbabtionList">
                        <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list" id="EmpProbabtionList">
                                        <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center">Emp.Code</th>
                                            <th class="text-center">Emp.Name</th>
                                            <th class="text-center">Location</th>
                                            <th class="text-center">Immediate Supervisor</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($employe_probationary as  $row)
                                            <tr>
                                                <td class="text-center"><?php echo $counter++;?></td>
                                                <td class="text-center"><?php echo CommonHelper::getEmpDataForExitClearance($row['emp_id'],1)?></td>
                                                <td class="text-center"><?php echo CommonHelper::getEmpDataForExitClearance($row['emp_id'],0)?></td>


                                                <td class="text-center"><?php echo $row['location']?></td>
                                                <td class="text-center"><?php echo $row['immidaite_supervisor'];?></td>

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