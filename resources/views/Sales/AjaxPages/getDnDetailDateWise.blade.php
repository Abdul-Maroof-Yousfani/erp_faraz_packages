<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

$Counter = 1;
$TotalDnAmount = 0;
$TotalDnAmountReturn = 0;
$TotalStiAmount = 0;
?>

<?php foreach ($DnData as $Fil): ?>
    <?php $Customer = CommonHelper::get_single_row('customers', 'id', $Fil->buyers_id); ?>
    <tr class="text-center">
        <td><?php echo $Counter++; ?></td>
        <td><?php echo $Fil->so_no; ?></td>
        <td><?php echo date_format(date_create($Fil->so_date), 'd-m-Y'); ?></td>
        <td><?php echo $Fil->gd_no; ?></td>
        <td><?php echo date_format(date_create($Fil->gd_date), 'd-m-Y'); ?></td>
        <td><?php echo $Customer->name ?? '-'; ?></td>
        <td><?php echo $Fil->net_amount + $Fil->sales_tax_amount; $TotalDnAmount += $Fil->net_amount + $Fil->sales_tax_amount; ?></td>
        <td><?php echo SalesHelper::get_sales_return_dn($Fil->dn_id); $TotalDnAmountReturn += SalesHelper::get_sales_return_dn($Fil->dn_id); ?></td>
        <td><?php echo SalesHelper::get_sales_inv_amount($Fil->so_id); $TotalStiAmount += SalesHelper::get_sales_inv_amount($Fil->so_id); ?></td>
    </tr>
<?php endforeach; ?>

<tr class="text-center">
    <td colspan="6"><strong>TOTAL</strong></td>
    <td><?php echo $TotalDnAmount; ?></td>
    <td><?php echo $TotalDnAmountReturn; ?></td>
    <td><?php echo $TotalStiAmount; ?></td>
</tr>
