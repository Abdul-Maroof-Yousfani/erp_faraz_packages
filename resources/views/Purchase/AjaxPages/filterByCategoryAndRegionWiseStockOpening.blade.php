
<?php
use App\Helpers\CommonHelper;
$CategoryId = $_GET['CategoryId'];
$RegionId = $_GET['RegionId'];


$Clause = '';
if($CategoryId == "" && $RegionId != "")
{
    $CategoryData = DB::Connection('mysql2')->select('select category.id,category.main_ic from category
                                             INNER JOIN subitem ON subitem.main_ic_id = category.id
                                             INNER JOIN stock ON stock.sub_item_id = subitem.id
                                             WHERE stock.opening = 1
                                             AND stock.region_id = '.$RegionId.'
                                            ');

}




foreach($CategoryData as $CatFil):
$data= DB::Connection('mysql2')->select('select * from stock where status=1 and opening=1 AND region_id = '.$RegionId.'');
        /*

        */



?>
<tr>
    <td><?php echo $CatFil->main_ic?></td>
</tr>

<?php
$total_amount=0;
foreach ($data as $row):?>

<?php

if ($row->qty>0 || $row->amount>0):
$sub_item_id=$row->sub_item_id;
$sub_item_data=CommonHelper::get_subitem_detail($sub_item_id);
$sub_item_data=explode(',',$sub_item_data);
$sub_item_name=$sub_item_data[4];
$category_id=$sub_item_data[5];
?>
<tr>
    <td><?php

        echo   $catgory_data=CommonHelper::get_category_name($category_id);


        ?></td>
    <td title="<?php echo $sub_item_id ?>"><?php echo $sub_item_name ?></td>
    <td><?php    $rgion_data= CommonHelper::get_rgion_name_by_id($row->region_id);
        echo  $rgion_data->region_name;
        ?></td>
    <td><?php echo $row->qty ?></td>
    <th class="text-center" ><?php echo number_format($row->amount,2) ;
        $total_amount+=$row->amount;
        ?></th>
</tr>
<?php endif; endforeach ?>
<tr style="background-color: darkgrey">
    <td colspan="4">Total</td>
    <td style="font-size: large;font-weight: bolder" colspan="1">{{number_format($total_amount,2)}}</td>
</tr>

<?php endforeach;?>
