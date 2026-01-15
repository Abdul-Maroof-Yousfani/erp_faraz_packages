<?php
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
$m = Session::get('run_company');

$view=ReuseableCode::check_rights(345);
$edit=ReuseableCode::check_rights(346);
$delete=ReuseableCode::check_rights(347);


$FinishGoodId =  $_GET['FinishGoodId'];
$Counter =  $_GET['Counter'];


$array= $_GET['array'];
$array=explode(',',$array);

$IdArray = array();
$Data = DB::Connection('mysql2')->select('select a.* from production_machine a
                                  INNER JOIN production_machine_data b ON b.master_id = a.id
                                  WHERE b.finish_good = '.$FinishGoodId.'
                                  and a.status = 1 group by a.id');

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <th class="text-center">S.No</th>
                <th class="text-center">Name</th>
                <th class="text-center">Code</th>
                <th class="text-center">Total Equipment Cost </th>
                <th class="text-center">No. of Piece in Life</th>
                <th class="text-center">Cost per Unit</th>
                <th class="text-center">Description</th>
                </thead>
                <tbody>


<tr class="text-center">
    <td colspan="8"><h3 class="text-success"><?php echo CommonHelper::get_item_name($FinishGoodId);?> Included In Machines</h3></td>
</tr>
<?php
$counter = 1;
foreach($Data as $row):
array_push($IdArray,$row->id);

?>
<tr @if (!in_array($row->id,$array)) style="background-color: lightcoral" @endif class="text-center" id="RemoveTr<?php echo $row->id?>" >
    <td><?php echo $counter++;?></td>
    <td><?php echo $row->machine_name?></td>
    <td><?php echo $row->code?></td>
    <td><?php echo $row->equi_cost?></td>
    <td><?php echo $row->life?></td>
    <td><?php echo $row->cost?></td>
    <td><?php echo $row->description?></td>
    <td>
        @if (!in_array($row->id,$array))
            <button type="button" class="btn btn-sm btn-success" id="BtnGet<?php echo $row->id?>" onclick="get_other_data('<?php echo $row->id?>','<?php echo $row->machine_name?>')">
                Get
            </button>
        @endif
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
<tr class="text-center" id="RemoveTr<?php echo $row2->id?>" >
    <td><?php echo $counter++;?></td>
    <td><?php echo $row2->machine_name?></td>
    <td><?php echo $row2->code?></td>
    <td><?php echo $row2->equi_cost?></td>
    <td><?php echo $row2->life?></td>
    <td><?php echo $row2->cost?></td>
    <td><?php echo $row2->description?></td>


</tr>
<?php endforeach;?>
<?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script !src="">
    var Counter = '<?php echo $Counter?>';
    function get_other_data(MachineId,MachineName)
    {
        Counter++;
        $('#AppendOtherRow').append('<tr class="text-center other_attach">' +
                '<td>'+MachineName+
                '<input type="hidden" id="machine_id" name="machine_id[]" value="'+MachineId+'">' +
                '<input type="hidden" name="detail_id[]" value="0">' +
                '</td>' +
                '<td>' +
                '<input type="number" class="form-control requiredField" name="capacity[]" value="" id="capacity'+Counter+'">' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control requiredField" name="que_time[]" "' +
                'oninput="this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");"  value="" id="que_time'+Counter+'">' +
                '</td>' +
                '<td>' +
                '<input  type="text" class="form-control requiredField" name="move_time[]" ' +
                'oninput="this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");" value="" id="move_time'+Counter+'">' +
                '</td>' +
                '<td>' +
                '<input  type="text" class="form-control requiredField" name="wait_time[]" ' +
                'oninput="this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");" value="" id="wait_time'+Counter+'">' +
                '</td>' +
                '</tr>');
        $('#BtnGet'+MachineId).css('display','none');
    }
</script>
