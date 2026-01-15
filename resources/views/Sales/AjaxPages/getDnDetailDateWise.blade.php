<?php
use App\Helpers\CommonHelper;
$counter = 1;
$total=0;
$total_exchange=0;
        $sti_amount =0;
foreach ($DnData as $row1){
$Customer = CommonHelper::get_single_row('customers','id',$row1->buyers_id);
        if($row1->so_data_id != 0):
$sti = DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('status',1)->where('so_data_id',$row1->so_data_id)->select('amount')->first();

        endif;



?>
<tr>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->so_no);?></td>
    <td class="text-center"><?php echo  CommonHelper::changeDateFormat($row1->so_date);?></td>
    <td class="text-center"><?php echo strtoupper($row1->gd_no);?></td>
    <td class="text-center"><?php echo  CommonHelper::changeDateFormat($row1->gd_date);?></td>
    <td><?php echo $Customer->name?></td>
    <td><?php echo $row1->desc;//echo CommonHelper::get_item_name($row1->sub_item_id);?></td>

    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
    $sub_ic_detail= explode(',',$sub_ic_detail)
    ?>

    <td> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>
    <td class="text-center"><?php echo $row1->qty;?></td>
    <td class="text-center"><?php echo number_format($row1->rate,2);?></td>
    <td class="text-right"><?php echo number_format($row1->amount,2);?></td>
    <td class="text-right"><?php
            if($sti !="")
                {
                    echo $sti->amount;
                }
        
        //echo number_format($sti_amount,2);?></td>

</tr>
<?php

}
?>
