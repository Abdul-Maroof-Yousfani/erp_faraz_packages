<?php
use App\Helpers\CommonHelper;
$m = Session::get('run_company');
?>
@extends('layouts.default')
@section('content')
<div class="panel-body well_N">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">Notification List</span>
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
                                            <th class="text-center col-sm-1">Department</th>
                                            <th class="text-center">Step name</th>
                                            <th class="text-center">Step behvaior</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Email 1</th>
                                            <th class="text-center">Email 2</th>
                                            <th class="text-center">Email 3</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($notifications_data->get() as  $row)
                                            <tr>
                                                <td class="text-center"><?php echo $counter++;?></td>
                                                <td class="text-center"><?php echo CommonHelper::get_sub_dept_name($row->dept_id)?></td>
                                                <td class="text-center"><?php echo $row->step_name?></td>
                                                <td class="text-center"><?php echo $row->b_name?></td>
                                                <td class="text-center"><?php echo $row->v_name?></td>
                                                <td class="text-center"><?php echo $row->email_1?></td>
                                                <td class="text-center"><?php echo $row->email_2?></td>
                                                <td class="text-center"><?php echo $row->email_3?></td>
                                    
                                      

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