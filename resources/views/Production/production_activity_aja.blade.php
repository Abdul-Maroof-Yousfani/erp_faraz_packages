<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;


$Machineview=ReuseableCode::check_rights(345);
$Bomview=ReuseableCode::check_rights(348);
$Operationview=ReuseableCode::check_rights(365);
$Routingview=ReuseableCode::check_rights(368);
$Fohview=ReuseableCode::check_rights(357);
$LabourWorkview=ReuseableCode::check_rights(362);

$FromDate = $_GET['FromDate'];
$ToDate = $_GET['ToDate'];
$m = $_GET['m'];
$DieData = DB::Connection('mysql2')->table('production_activity')->where('table',1)->get();
$DieDataDetail = DB::Connection('mysql2')->table('production_activity')->where('table',2)->get();
$MouldData = DB::Connection('mysql2')->table('production_activity')->where('table',3)->get();
$MouldDetail = DB::Connection('mysql2')->table('production_activity')->where('table',4)->get();
$MachineDetail = DB::Connection('mysql2')->table('production_activity')->where('table',5)->get();
$MachineDetail = DB::Connection('mysql2')->table('production_activity')->where('table',5)->get();
$BomDetail = DB::Connection('mysql2')->table('production_activity')->where('table',6)->get();
$OperationDetail = DB::Connection('mysql2')->table('production_activity')->where('table',7)->get();
$RoutingDetail = DB::Connection('mysql2')->table('production_activity')->where('table',8)->get();
$FactoryOverHeadDetail = DB::Connection('mysql2')->table('production_activity')->where('table',9)->get();
$FohCatDetail = DB::Connection('mysql2')->table('production_activity')->where('table',11)->get();
$LabourWorkDetail = DB::Connection('mysql2')->table('production_activity')->where('table',10)->get();
$Counter = 1;
?>
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
            <th colspan="7" class="text-center"><h3>Die</h3></th>
        </thead>
        <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Die Name</th>
            <th class="text-center">Size</th>
            <th class="text-center">Username</th>
            <th class="text-center">Created Date</th>
            <th class="text-center">Created Time</th>
            <th class="text-center">Action</th>
        </thead>
        <tbody>
            <?php
            $Action = "";
            foreach($DieData as $DieFil):
                    if($DieFil->action == 1):
                        $Action = "Insert";
                    elseif($DieFil->action == 2):
                        $Action = "Update";
                    else:
                        $Action = "Delete";
                    endif;
            $DieRow = DB::Connection('mysql2')->table('production_dai')->where('id',$DieFil->main_id)->first();
            ?>
            <tr class="text-center">
                <td><?php echo $Counter++;?></td>
                <td><?php echo $DieRow->dai_name?></td>
                <td><?php echo $DieRow->size?></td>
                <td><?php echo $DieFil->username?></td>
                <td><?php echo $DieFil->create_date?></td>
                <td><?php echo $DieFil->created_time?></td>
                <td><?php echo $Action?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Die Detail</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Die Name</th>
        <th class="text-center">Batch Code</th>
        <th class="text-center">Life(Pieces)</th>
        <th class="text-center">Value</th>
        <th class="text-center">Per Piece Cost</th>
        <th class="text-center">Username</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($DieDataDetail as $DieDetailFil):
        if($DieDetailFil->action == 1):
            $Action = "Insert";
        elseif($DieDetailFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_dai_detail')->where('id',$DieDetailFil->main_id)->first();
        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo DB::Connection('mysql2')->table('production_dai')->where('id',$Row->main_id)->select('dai_name')->first()->dai_name;?></td>
            <td><?php echo $Row->batch_code?></td>
            <td><?php echo $Row->life?></td>
            <td><?php echo $Row->value?></td>
            <td><?php echo $Row->cost?></td>
            <td><?php echo $DieDetailFil->username?></td>
            <td><?php echo $DieDetailFil->create_date?></td>
            <td><?php echo $DieDetailFil->created_time?></td>
            <td><?php echo $Action?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="7" class="text-center"><h3>Mould</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Mould Name</th>
        <th class="text-center">Size</th>
        <th class="text-center">Username</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($MouldData as $MouldFil):
        if($MouldFil->action == 1):
            $Action = "Insert";
        elseif($MouldFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_mold')->where('id',$MouldFil->main_id)->first();
        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo $Row->mold_name?></td>
            <td><?php echo $Row->size?></td>
            <td><?php echo $MouldFil->username?></td>
            <td><?php echo $MouldFil->create_date?></td>
            <td><?php echo $MouldFil->created_time?></td>
            <td><?php echo $Action?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Die Detail</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Die Name</th>
        <th class="text-center">Batch Code</th>
        <th class="text-center">Life(Pieces)</th>
        <th class="text-center">Value</th>
        <th class="text-center">Per Piece Cost</th>
        <th class="text-center">Username</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($MouldDetail as $MouldDetailFil):
        if($MouldDetailFil->action == 1):
            $Action = "Insert";
        elseif($MouldDetailFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('mould_detail')->where('id',$MouldDetailFil->main_id)->first();
        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo DB::Connection('mysql2')->table('production_mold')->where('id',$Row->mould_id)->select('mold_name')->first()->mold_name;?></td>
            <td><?php echo $Row->batch_code?></td>
            <td><?php echo $Row->life?></td>
            <td><?php echo $Row->value?></td>
            <td><?php echo $Row->cost?></td>
            <td><?php echo $MouldDetailFil->username?></td>
            <td><?php echo $MouldDetailFil->create_date?></td>
            <td><?php echo $MouldDetailFil->created_time?></td>
            <td><?php echo $Action?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Machine</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Name</th>
        <th class="text-center">Code</th>
        <th class="text-center">Total Equipment Cost </th>
        <th class="text-center">No. of Piece in Life</th>
        <th class="text-center">Cost per Unit</th>
        <th class="text-center">Username</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        <th class="text-center">View</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($MachineDetail as $MachineFil):
        if($MachineFil->action == 1):
            $Action = "Insert";
        elseif($MachineFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_machine')->where('status',1)->where('id',$MachineFil->main_id)->get();
        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo $Row->machine_name?></td>
            <td><?php echo $Row->code?></td>
            <td><?php echo $Row->equi_cost?></td>
            <td><?php echo $Row->life?></td>
            <td><?php echo $Row->cost?></td>
            <td><?php echo $Row->description?></td>
            <td><?php echo $MachineFil->username?></td>
            <td><?php echo $MachineFil->create_date?></td>
            <td><?php echo $MachineFil->created_time?></td>
            <td><?php echo $Action?></td>
            <td>
                <?php if($Machineview == true):?>
                <button onclick="showDetailModelOneParamerter('production/viewMachineDetail?m=<?php echo $m?>','<?php echo $Row->id;?>','View Machine Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                <?php endif;?>
            </td>

        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Bill OF Material</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Finish Goods</th>
        <th class="text-center">Description</th>
        <th class="text-center">Direct Marterail</th>
        <th class="text-center">InDirect Marterail</th>
        <th class="text-center">Username</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        <th class="text-center">View</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($BomDetail as $BomFil):
        if($BomFil->action == 1):
            $Action = "Insert";
        elseif($BomFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_bom')->where('status',1)->where('id',$BomFil->main_id)->get();
        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo CommonHelper::get_item_name($row->finish_goods);?></td>
            <td><?php echo $row->description?></td>
            <td>
                <?php $Direct = DB::Connection('mysql2')->table('production_bom_data_direct_material')->where('status',1)->where('master_id',$Row->id)->select('item_id')->get();
                $DirectCounter =0;
                foreach($Direct as $Dfil):
                    $DirectCounter++;
                    echo '<span>'.$DirectCounter.'</span>'.'='.CommonHelper::get_item_name($Dfil->item_id);
                    echo "<br>";
                endforeach;?>
            </td>
            <td>
                <?php $InDirect = DB::Connection('mysql2')->table('production_bom_data_indirect_material')->where('status',1)->where('main_id',$Row->id)->select('item_id')->get();
                $InDirectCounter =0;
                foreach($InDirect as $InDfil):
                    $InDirectCounter++;
                    echo '<span>'.$InDirectCounter.'</span>'.'='.CommonHelper::get_item_name($InDfil->item_id );
                    echo "<br>";
                endforeach;?>
            </td>
            <td><?php echo $BomFil->username?></td>
            <td><?php echo $BomFil->create_date?></td>
            <td><?php echo $BomFil->created_time?></td>
            <td><?php echo $Action?></td>
            <td>
                <?php if($Bomview == true):?>
                <button onclick="showDetailModelOneParamerter('production/viewBomDetail?m=<?php echo $m?>','<?php echo $Row->id;?>','View Bill Of Marterial Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                <?php endif;?>
            </td>

        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Operation</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Finish Goods</th>
        <th class="text-center">Description</th>
        <th class="text-center">Direct Marterail</th>
        <th class="text-center">InDirect Marterail</th>
        <th class="text-center">Username</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        <th class="text-center">View</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($OperationDetail as $OperationFil):
        if($OperationFil->action == 1):
            $Action = "Insert";
        elseif($OperationFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_work_order')->where('status', 1)->where('id',$OperationFil->main_id)->get();
        $FinishGood = CommonHelper::get_single_row('subitem','id',$Row->finish_good_id);
        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo $FinishGood->sub_ic?></td>
            <td><?php echo $OperationFil->username?></td>
            <td><?php echo $OperationFil->create_date?></td>
            <td><?php echo $OperationFil->created_time?></td>
            <td><?php echo $Action?></td>
            <td>
                <?php if($Operationview == true):?>
                <a onclick="showDetailModelOneParamerter('production/viewOperationDetail','<?php echo $Row->id;?>','View Operation Detail','<?php echo $_GET['m']?>','')" class="btn btn-xs btn-success">View</a>
                <?php endif;?>
            </td>

        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>



<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Routing</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Route Code</th>
        <th class="text-center">Finish Goods</th>
        <th class="text-center">Operation</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        <th class="text-center">View</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($RoutingDetail as $RoutingFil):
        if($RoutingFil->action == 1):
            $Action = "Insert";
        elseif($RoutingFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_route')->where('status',1)->where('id',$RoutingFil->main_id)->get();

        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo $Row->voucher_no?></td>
            <td><?php echo CommonHelper::get_item_name($Row->finish_goods);?></td>
            <td><?php echo $Row->operation_id?></td>

            <td><?php echo $RoutingFil->username?></td>
            <td><?php echo $RoutingFil->create_date?></td>
            <td><?php echo $RoutingFil->created_time?></td>
            <td><?php echo $Action?></td>
            <td>
                <?php if($Routingview == true):?>
                <button onclick="showDetailModelOneParamerter('production/viewRoutingDetail?m=<?php echo Session::get('run_company')?>','<?php echo $Row->id;?>','View Routing Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                <?php endif;?>
            </td>

        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Factory Overhead</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Factory overhead</th>
        <th class="text-center">Over Head Category</th>
        <th class="text-center">Accounts</th>
        <th class="text-center">Total Amount</th>
        <th class="text-center">Total No Of Pieces</th>
        <th class="text-center">FOH Cost per Unit</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        <th class="text-center">View</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($FactoryOverHeadDetail as $FohFil):
        if($FohFil->action == 1):
            $Action = "Insert";
        elseif($FohFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_factory_overhead')->where('status',1)->where('id',$FohFil->main_id)->get();

        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo $Row->name?></td>
            <td>
                <?php
                if($Row->over_head_category_id > 0):
                    echo ProductionHelper::get_over_head_cagegory_name($Row->over_head_category_id);
                endif;
                ?>
            </td>
            <?php $master_data=DB::Connection('mysql2')->table('production_factory_overhead_data')->where('master_id',$Row->id); ?>
            <td>
                <?php
                $account_count=1;
                foreach($master_data->get() as $row1):
                    echo  '('.$account_count++.') '.CommonHelper::get_account_name($row1->acc_id).'</br>';
                endforeach;
                ?></td>

            <td><?php echo  number_format($master_data->sum('amount'),2)?></td>
            <td><?php echo  number_format($master_data->sum('no_of_piece'),2)?></td>
            <td><?php echo  number_format($master_data->sum('cost'),2)?></td>
            <td><?php echo $FohFil->username?></td>
            <td><?php echo $FohFil->create_date?></td>
            <td><?php echo $FohFil->created_time?></td>
            <td><?php echo $Action?></td>
            <td>
                <?php if($Fohview == true):?>
                <button onclick="showDetailModelOneParamerter('production/view_factory_overhead_detail?m=<?php echo Session::get('run_company')?>','<?php echo $Row->id;?>','View Factory Overhead Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                <?php endif;?>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Factory Overhead Category</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Name</th>
        <th class="text-center">Remarks</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($FohCatDetail as $FohCatFil):
        if($FohCatFil->action == 1):
            $Action = "Insert";
        elseif($FohCatFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_over_head_category')->where('status',1)->where('id',$FohCatFil->main_id)->get();

        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo $Row->name;?></td>
            <td><?php echo $Row->remarks;?></td>
            <td><?php echo $FohCatFil->username?></td>
            <td><?php echo $FohCatFil->create_date?></td>
            <td><?php echo $FohCatFil->created_time?></td>
            <td><?php echo $Action?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th colspan="10" class="text-center"><h3>Labour Work</h3></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Remarks</th>
        <th class="text-center">Working Hours</th>
        <th class="text-center">No of Worker</th>
        <th class="text-center">Total Working Hours</th>
        <th class="text-center">Active/Inactive</th>
        <th class="text-center">Created Date</th>
        <th class="text-center">Created Time</th>
        <th class="text-center">Action</th>
        <th class="text-center">View</th>
        </thead>
        <tbody>
        <?php
        $Action = "";
        foreach($LabourWorkDetail as $LabourWorkFil):
        if($LabourWorkFil->action == 1):
            $Action = "Insert";
        elseif($LabourWorkFil->action == 2):
            $Action = "Update";
        else:
            $Action = "Delete";
        endif;
        $Row = DB::Connection('mysql2')->table('production_labour_working')->whereIn('status',array(1,2))->where('id',$LabourWorkFil->main_id)->get();

        ?>
        <tr class="text-center">
            <td><?php echo $Counter++;?></td>
            <td><?php echo $Row->remarks;?></td>
            <td><?php echo number_format($Row->working_hours,2)?></td>
            <td><?php echo number_format($Row->no_of_worker,2)?></td>
            <td><?php echo number_format($Row->total_working_hours,2)?></td>

            <td>
                <?php if($Row->status == 1):?>
                <span class="text-success" style="font-size: 20px;"><i class="fa fa-check" aria-hidden="true"></i>ACTIVE</span>
                <?php else:?>
                <span class="text-danger" style="font-size: 20px;"><i class="fa fa-ban" aria-hidden="true"></i>INACTIVE</span>
                <?php endif;?>
            </td>

            <td><?php echo $LabourWorkFil->username?></td>
            <td><?php echo $LabourWorkFil->create_date?></td>
            <td><?php echo $LabourWorkFil->created_time?></td>
            <td><?php echo $Action?></td>
            <td>
                <?php if($LabourWorkview == true):?>
                <button onclick="showDetailModelOneParamerter('production/viewLabourWorkingDetail?m=<?php echo $m?>','<?php echo $Row->id;?>','View Labour Working Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                <?php endif;?>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>