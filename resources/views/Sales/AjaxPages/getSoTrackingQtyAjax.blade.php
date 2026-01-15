<?php
use App\Helpers\CommonHelper;
$m = $_GET['m'];
$SoNo = $_GET['SoNo'];
$BuyerOrderNo = $_GET['BuyerOrderNo'];
$FilterType = $_GET['FilterType'];
$Data = '';
if($FilterType == 2)
{
    $Data = DB::Connection('mysql2')->select('select a.so_no,a.so_date,a.order_no,b.id data_id,b.item_id,b.desc,b.qty from sales_order a
                                              INNER JOIN sales_order_data b ON b.master_id = a.id
                                              where a.status = 1
                                              AND a.so_no = "'.$SoNo.'"');
}
else if($FilterType == 3)
{
    $Data = DB::Connection('mysql2')->select('select a.so_no,a.so_date,a.order_no,b.id data_id,b.item_id,b.desc,b.qty from sales_order a
                                              INNER JOIN sales_order_data b ON b.master_id = a.id
                                              where a.status = 1
                                              AND a.order_no = "'.$BuyerOrderNo.'"');
}
else
{
 $Data = '';
}
        $Counter = 1;
        $TotSoQty = 0;
        $TotDnQty = 0;
        $TotBalanceQty =0;
foreach($Data as $Fil):
?>
<tr class="text-center">
    <td><?php echo $Counter.' '.$Fil->data_id;?></td>
    <td><?php echo $Fil->so_no?></td>
    <td><?php echo CommonHelper::changeDateFormat($Fil->so_date);?></td>
    <td><?php echo $Fil->order_no?></td>
    <td><?php echo $Fil->desc?></td>
    <td><?php echo number_format($Fil->qty,2); $TotSoQty+=$Fil->qty;?></td>
    <td>
        <?php

        $dn_qty=    DB::Connection('mysql2')->table('delivery_note_data')->where('status',1)->where('so_data_id',$Fil->data_id)->sum('qty');
        echo number_format($dn_qty,2); $TotDnQty+=$dn_qty;
            $BlncQty = $Fil->qty-$dn_qty;
        ?>
    </td>
    <td><?php echo number_format($BlncQty,2); $TotBalanceQty+=$BlncQty;?></td>
</tr>
<?php
$Counter++;
endforeach;?>
<tr class="text-center">
    <td colspan="5"><strong style="font-size: 20px;">TOTAL</strong></td>
    <td><strong style="font-size: 20px;"><?php echo number_format($TotSoQty,2);?></strong></td>
    <td><strong style="font-size: 20px;"><?php echo number_format($TotDnQty,2);?></strong></td>
    <td><strong style="font-size: 20px;"><?php echo number_format($TotBalanceQty,2);?></strong></td>
</tr>

