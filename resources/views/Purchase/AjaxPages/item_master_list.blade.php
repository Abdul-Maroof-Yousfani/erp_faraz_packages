<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$edit=ReuseableCode::check_rights(286);
$delete=ReuseableCode::check_rights(287);
?>

<table class="table table-bordered">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Category</th>
    <th class="text-center">Sub Category</th>
    <th class="text-center">Item Master Code</th>
    <th class="text-center">Transactions</th>

    <th class="text-center">Action</th>
    </thead>
    <tbody id="viewCategoryList">
    <?php
    $Counter = 1;

    foreach($data as $row):
    $multiIds = "";
    $Category = CommonHelper::get_single_row('category','id',$row->category_id);
    $SubCategory = CommonHelper::get_single_row('sub_category','id',$row->sub_category_id);
    $Count = DB::Connection('mysql2')->selectOne('SELECT COUNT(*) as data_count FROM stock a
                                                 INNER JOIN subitem b ON b.id = a.sub_item_id
                                                 WHERE b.item_master_id = '.$row->id.'
                                                  and a.status = 1')->data_count;

    ?>
    <tr class="text-center tr<?php echo $row->id?>" >
        <td><?php echo $Counter++;?></td>
        <td><?php echo $Category->main_ic;?></td>
        <td><?php echo $SubCategory->sub_category_name;?></td>
        <td><?php echo $row->item_master_code;?></td>
        <td> @if($Count>0){{'&#x2714;'}} @else {{'&#x2716;'}} @endif</td>
        <td class="text-center">
            <?php
            if($Count == 0):
            ?>
                <?php if($edit == true):?>
                    <a href="<?php echo  URL::to('/purchase/editItemMaster/'.$row->id.'?m='.Session::get('run_company')); ?>" type="button" class="btn btn-primary btn-xs">Edit</a>
                <?php endif;?>
                <?php if($delete == true):?>
                    <button type="button" class="btn btn-xs btn-danger" id="BtnDelete<?php echo $row->id?>" onclick="DeleteItemMaster('<?php echo $row->id?>')">Delete</button>
                <?php endif;?>

            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>