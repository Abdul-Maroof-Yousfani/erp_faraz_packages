<?php
use App\Helpers\CommonHelper;
$Counter = 1;
$TotalQty = 0;
$TotalAmount = 0;
foreach($StockData as $DataFil):
$SubItem = CommonHelper::get_single_row('subitem','id',$DataFil->sub_item_id);
?>
<tr class="text-center">
    <td><?php echo $Counter++;?></td>
    <td><?php echo $SubItem->sub_ic?></td>
    <td><?php echo $SubItem->id?></td>
    <td><?php echo $DataFil->region_id?></td>
    <td><?php if($DataFil->voucher_no != ""): echo $DataFil->voucher_no; else: echo 'Opening'; endif;?></td>
    <td><?php echo date('m-d-Y',strtotime($DataFil->voucher_date))?></td>
    <td><?php echo $DataFil->qty; $TotalQty +=$DataFil->qty;?></td>
    <td><?php echo $DataFil->rate?></td>
    <td @if ($DataFil->amount==0) style="background-color: red" @endif><?php echo $DataFil->amount; $TotalAmount += $DataFil->amount;?></td>
</tr>
<?php endforeach;?>
<tr style="background-color: darkgray" class="text-center">
    <td colspan="6" class="text-center">TOTAL</td>
    <td><?php echo $TotalQty;?></td>
    <td>{{number_format($TotalAmount/$TotalQty,2)}}</td>
    <td><?php echo $TotalAmount;?></td>
</tr>

