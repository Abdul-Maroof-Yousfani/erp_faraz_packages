<?php
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
$m = Session::get('run_company');

$view=ReuseableCode::check_rights(345);
$edit=ReuseableCode::check_rights(346);
$delete=ReuseableCode::check_rights(347);


$FinishGoodId =  $_GET['FinishGoodId'];
        $IdArray = array();
$Data = DB::Connection('mysql2')->select('select a.* from production_machine a
                                  INNER JOIN production_machine_data b ON b.master_id = a.id
                                  WHERE b.finish_good = '.$FinishGoodId.'
                                  and a.status = 1 group by a.id');

?>
<tr class="text-center">
    <td colspan="8"><h3 class="text-success"><?php echo CommonHelper::get_item_name($FinishGoodId);?> Included In Machines</h3></td>
</tr>
<?php
$counter = 1;
foreach($Data as $row):
array_push($IdArray,$row->id);
?>
<tr class="text-center" id="RemoveTr<?php echo $row->id?>" style="background-color: #a7d8da;">
    <td><?php echo $counter++;?></td>
    <td><?php echo $row->machine_name?></td>
    <td><?php echo $row->code?></td>
    <td><?php echo $row->equi_cost?></td>
    <td><?php echo $row->life?></td>
    <td><?php echo $row->cost?></td>
    <td><?php echo $row->description?></td>

    <td>
        <?php if($view == true):?>
        <button onclick="showDetailModelOneParamerter('production/viewMachineDetail?m=<?php echo $m?>','<?php echo $row->id;?>','View Machine Detail')"   type="button" class="btn btn-success btn-xs">View</button>
        <?php endif;?>
        <?php if($edit == true):?>
        <a href="<?php echo URL::asset('production/editMachineForm?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
        <?php endif;?>
        <?php if($delete== true):?>
        <button onclick="DeleteMachine('<?php echo $row->id?>','<?php echo $m ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
        <?php endif;?>
    </td>
</tr>
<?php endforeach;
$Data2 = DB::Connection('mysql2')->table('production_machine')->where('status',1)->whereNotIn('id',$IdArray)->get();
?>
<?php if(count($Data2) > 0):?>
<tr class="text-center">
    <td colspan="8"><h3 class="text-danger"><?php echo CommonHelper::get_item_name($FinishGoodId);?> Not Included In Machines</h3></td>
</tr>
<?php

$counter = 1;
foreach($Data2 as $row2):?>
<tr class="text-center bg-danger" id="RemoveTr<?php echo $row2->id?>" >
    <td><?php echo $counter++;?></td>
    <td><?php echo $row2->machine_name?></td>
    <td><?php echo $row2->code?></td>
    <td><?php echo $row2->equi_cost?></td>
    <td><?php echo $row2->life?></td>
    <td><?php echo $row2->cost?></td>
    <td><?php echo $row2->description?></td>

    <td>
        <?php if($view == true):?>
        <button onclick="showDetailModelOneParamerter('production/viewMachineDetail?m=<?php echo $m?>','<?php echo $row2->id;?>','View Machine Detail')"   type="button" class="btn btn-success btn-xs">View</button>
        <?php endif;?>
        <?php if($edit == true):?>
        <a href="<?php echo URL::asset('production/editMachineForm?edit_id='.$row2->id);?>" class="btn btn-xs btn-primary">Edit</a>
        <?php endif;?>
        <?php if($delete== true):?>
        <button onclick="DeleteMachine('<?php echo $row2->id?>','<?php echo $m ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
        <?php endif;?>
    </td>
</tr>
<?php endforeach;?>
<?php endif;?>
