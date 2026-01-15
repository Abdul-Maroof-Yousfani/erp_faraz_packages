<?php
$m = Session::get('run_company');
use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(362);
$edit=ReuseableCode::check_rights(364);
//$delete=ReuseableCode::check_rights(359);
?>
@extends('layouts.default')

@section('content')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Labour Rate List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Start Date</th>
                                                                <th class="text-center">End Date</th>
                                                                <th class="text-center">Working Hours</th>
                                                                <th class="text-center">No of Worker</th>
                                                                <th class="text-center">Total Working Hours</th>
                                                                <th class="text-center">Username</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $counter = 1;
                                                            $Data = DB::Connection('mysql2')->table('production_labour_working')->whereIn('status',array(1,2))->get();
                                                            foreach($Data as $row):
                                                            ?>
                                                            <tr class="text-center" style="background: <?php if($row->status == 2): echo '#ccc'; endif;?>">
                                                                <td><?php echo $counter++;?></td>
                                                                <td><?php echo CommonHelper::changeDateFormat($row->start_date);?></td>
                                                                <td><?php echo CommonHelper::changeDateFormat($row->end_date);?></td>
                                                                <td><?php echo number_format($row->working_hours,2)?></td>
                                                                <td><?php echo number_format($row->no_of_worker,2)?></td>
                                                                <td><?php echo number_format($row->total_working_hours,2)?></td>
                                                                <td><?php echo $row->username?></td>
                                                                <td>
                                                                    <?php if($row->status == 1):?>
                                                                    <span class="text-success" style=""><i  aria-hidden="true"></i>ACTIVE</span>
                                                                    <?php else:?>
                                                                    <span class="text-danger" style=""><i  aria-hidden="true"></i>INACTIVE</span>
                                                                    <?php endif;?>
                                                                </td>
                                                                <td>
                                                                    <?php if($view == true):?>
                                                                        <button onclick="showDetailModelOneParamerter('production/viewLabourWorkingDetail?m=<?php echo $m?>','<?php echo $row->id;?>','View Labour Working Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                    <?php endif;?>
                                                                    <?php if($edit == true):?>
                                                                        <?php if($row->status == 1):?>
                                                                        <a href="<?php echo URL::asset('production/edit_labours_working?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                        <?php endif;?>
                                                                    <?php endif;?>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach;?>
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
        </div>
    </div>
    </div>

@endsection