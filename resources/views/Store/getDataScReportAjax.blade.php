<?php
use App\Helpers\CommonHelper;
$Data = DB::Connection('mysql2')->table('transaction_supply_chain')->where('status',1)->whereBetween('voucher_date',[$FromDate,$ToDate])->where('voucher_type',$VoucherType)->get();
        $Counter = 1;
        $TotalAmount = 0;
        $total_sales_tax=0;
foreach($Data as $Fil):?>
    <tr class="text-center">
        <td><?php echo $Counter++;?></td>
        <td><?php echo $Fil->voucher_no?></td>
        <td><?php echo $Fil->voucher_date?></td>
        <td><?php echo CommonHelper::get_item_name($Fil->item_id)
            ?></td>
        <td><?php echo $Fil->qty?></td>
        <td><?php echo number_format($Fil->amount,2); $TotalAmount+=$Fil->amount;?></td>

      
    </tr>
<?php endforeach;?>
<tr class="text-center">
    <td colspan="5">TOTAL</td>
    <td><?php echo number_format($TotalAmount,2);?></td>
    <td><?php echo number_format($total_sales_tax,2);?></td>
</tr>
