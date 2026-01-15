<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
$counter = 1;
$total=0;
$total_exchange=0;
foreach ($PurchaseRequestData as $row1){
        $Supplier = CommonHelper::get_single_row('supplier','id',$row1->supplier_id);

        $Tstatus = '';
        if($row1->purchase_request_status == 3)  {
            if(CommonHelper::CheckGrnCount($row1->purchase_request_no) == 0) {
                $Tstatus = 2;
            }
            else {
                $Tstatus = $row1->purchase_request_status;
            }
        }
        else {
            $Tstatus = $row1->purchase_request_status;
        }

?>
<tr>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->purchase_request_no);?></td>
    <td class="text-center"><?php echo  CommonHelper::changeDateFormat($row1->purchase_request_date);?></td>

    <td>{{ CommonHelper::get_item_name($row1->sub_item_id) }}</td>

    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
    $sub_ic_detail= explode(',',$sub_ic_detail)
    ?>

    <td> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?> </td>
    <td><?php echo $Supplier->name?></td>
    <td class="text-center"><?php echo $row1->purchase_approve_qty;?></td>
    <td class="text-center"><?php echo number_format($row1->rate,2);?></td>
    <!-- <td class="text-right"><?php echo number_format($row1->sub_total,2);?></td> -->
    <!-- <td class="text-right"><?php echo number_format($row1->discount_percent,2);?></td>
    <td class="text-right"><?php echo number_format($row1->discount_amount,2);?></td> -->
    <td class="text-right"><?php echo number_format($row1->net_amount,2);?></td>
    <td class="text-right">{{ StoreHelper::checkVoucherStatus($Tstatus,$row1->status) }}</td>
    <?php $total+=$row1->net_amount;  ?>
</tr>
<?php

}

?>
<tr class="text-center" style="font-size: large;font-weight: bold">
    <td colspan="8"> Total</td>
    <td colspan="1">{{number_format($total,2)}}</td>
    <td colspan="1"></td>
</tr>
