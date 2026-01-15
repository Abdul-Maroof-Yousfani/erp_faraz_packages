<?php
use App\Helpers\CommonHelper;
$Counter = 1;
foreach($Region as $RFil):
CommonHelper::companyDatabaseConnection($m);
$Stock = DB::table('stock')->where('status', 1)
        ->where('opening', 1)
        ->where('sub_item_id', $SubItemId)
        ->where('region_id', $RFil->id)
        ->get();
CommonHelper::reconnectMasterDatabase();
$Region = CommonHelper::get_single_row('region','id',$RFil->id);
$SubItem = CommonHelper::get_single_row('subitem','id',$SubItemId);
$Category = CommonHelper::get_single_row('category','id',$SubItem->main_ic_id);
?>
<table class="table table-bordered sf-table-list">
    <thead>
    <th class="text-center" colspan="6" style="font-size: 20px; color: red;"><?php echo $Region->region_name;?></th>
    </thead>
    <thead>
    <th class="text-center">Category</th>
    <th class="text-center">Item Name</th>
    <th class="text-center">Region</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Qty</th>
    <th class="text-center">Amount</th>
    <th class="text-center hidden-print">Action</th>
    </thead>
    <tbody id="GetData">

    <?php
    if($Stock->count() > 0):
    foreach($Stock as $SFil):?>
    <tr class="text-center">
        <td><?php echo $Category->main_ic?><input type="hidden" name="CategoryId<?php echo $Region->id?>" id="CategoryId<?php echo $Region->id?>" value="<?php echo $Category->id?>"></td>
        <td><?php echo $SubItem->sub_ic?><input type="hidden" name="SubItemId<?php echo $Region->id?>" id="SubItemId<?php echo $Region->id?>" value="<?php echo $SubItem->id?>"></td>
        <td><?php echo $Region->region_name;?><input type="hidden" name="RegionId<?php echo $Region->id?>" id="RegionId<?php echo $Region->id?>" value="<?php echo $Region->id?>"></td>
        <td><input type="number" step="any" id="Rate<?php echo $Region->id?>"class="form-control Rate<?php echo $Region->id?>" value="<?php echo $SubItem->rate?>" onkeyup="calc('<?php echo $Region->id?>')"></td>
        <td><input type="number" step="any" id="Qty<?php echo $Region->id?>"class="form-control Qty<?php echo $Region->id?>" value="<?php echo $SFil->qty?>" onkeyup="calc('<?php echo $Region->id?>')"></td>
        <td><input type="number" step="any" id="Amount<?php echo $Region->id?>"class="form-control Amount<?php echo $Region->id?>" value="<?php echo $SFil->amount?>"></td>
        <td>
            <button type="button" class="btn btn-sm btn-success" onclick="InsertOrUpdateOpening('<?php echo $Region->id?>','Update');" >Update</button>
        </td>
    </tr>
    <?php endforeach;
    else:
    ?>
    <tr class="text-center">
        <td><?php echo $Category->main_ic?><input type="hidden" id="CategoryId<?php echo $Region->id?>" value="<?php echo $Category->id?>"></td>
        <td><?php echo $SubItem->sub_ic?><input type="hidden" name="SubItemId<?php echo $Region->id?>" id="SubItemId<?php echo $Region->id?>" value="<?php echo $SubItem->id?>"></td>
        <td><?php echo $Region->region_name;?><input type="hidden" name="RegionId<?php echo $Region->id?>" id="RegionId<?php echo $Region->id?>" value="<?php echo $Region->id?>"></td>
        <td><input type="number" step="any" id="Rate<?php echo $Region->id?>"class="form-control Rate<?php echo $Region->id?>" value="<?php echo $SubItem->rate?>" onkeyup="calc('<?php echo $Region->id?>')"></td>
        <td><input type="number" step="any" id="Qty<?php echo $Region->id?>" class="form-control Qty<?php echo $Region->id?>" value="" placeholder="Quantity" onkeyup="calc('<?php echo $Region->id?>')"></td>
        <td><input type="number" step="any" id="Amount<?php echo $Region->id?>" class="form-control Amount<?php echo $Region->id?>" value="" placeholder="Amount"></td>
        <td>
            <button type="button" class="btn btn-sm btn-success" onclick="InsertOrUpdateOpening('<?php echo $Region->id?>','Insert')">Add</button>
        </td>
    </tr>
    <?php endif;?>
    </tbody>
</table>

<?php endforeach;?>
<script !src="">
    function InsertOrUpdateOpening(Id,Command)
    {
        var CategoryId = $('#CategoryId'+Id).val();
        var m = '<?php echo $m?>';
        var SubItemId = $('#SubItemId'+Id).val();
        var RegionId = $('#RegionId'+Id).val();
        var Rate = $('#Rate'+Id).val();
        var Qty = $('#Qty'+Id).val();
        var Amount = $('#Amount'+Id).val();

        $.ajax({
            url: '<?php echo url('/')?>/pdc/insertOrUpdateOpeningStock',
            method: 'GET',
            data: {UpdateId:Id,
                Command:Command,
                CategoryId: CategoryId,
                SubItemId:SubItemId,
                RegionId:RegionId,
                Rate:Rate,
                Qty:Qty,
                Amount:Amount,
                m: m},
            error: function () {
                alert('error');
            },
            success: function (response) {
                alert(response);
            }
        });
    }
    function calc(Count)
    {
        var Rate = parseFloat($('.Rate'+Count).val());
        var Qty = parseFloat($('.Qty'+Count).val());
        var Amount = parseFloat(Rate*Qty).toFixed(2);
        $('.Amount'+Count).val(Amount)

    }
</script>