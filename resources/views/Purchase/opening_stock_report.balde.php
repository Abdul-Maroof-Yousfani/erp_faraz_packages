<?php
use App\Helpers\CommonHelper;
echo "abc"; 
?>

<table id="buildyourform" class="table table-bordered">
    <thead>
    <tr>
        <th>Category</a>

        </th>
        <th class="text-center">Item </th>
        <th class="text-center">Region</th>
        <th class="text-center" >Qty</th>
        <th class="text-center">Amount</th>
    </tr>
    </thead>
    <tbody class="addMoreJvsDetailRows_1" id="addMoreJvsDetailRows_1">
        <?php $data= DB::Connection('mysql2')->select('select * from stock where status=1 and opening=1');


        ?>

        <?php foreach ($data as $row):?>

            <?php
            $sub_item_id=$row->sub_item_id;
            $sub_item_data=CommonHelper::get_subitem_detail($sub_item_id);
            $sub_item_data=explode(',',$sub_item_data);
            $sub_item_name=$sub_item_data[4];
            ?>
        <tr>
           <td></td>
            <td><?php echo $sub_item_name ?></td>
            <td><?php echo CommonHelper::get_rgion_name_by_id($row->region_id)?></td>
            <td><?php echo $row->qty ?></td>
            <th class="text-center" ><?php echo $row->amount ?></th>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>