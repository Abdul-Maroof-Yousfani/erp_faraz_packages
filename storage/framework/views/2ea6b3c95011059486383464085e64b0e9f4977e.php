
<?php use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(37);
$edit=ReuseableCode::check_rights(211);
$delete=ReuseableCode::check_rights(38);
?>

<?php $counter = 1;$total=0;?>

<?php $__currentLoopData = $purchase_voucher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
    $net_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id',$row->id)->sum('net_amount');
    $net_amount_grn= DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->grn_id)->sum('net_amount');
    $grn_date= DB::Connection('mysql2')->table('goods_receipt_note')->where('id',$row->grn_id)->value('grn_date');
    $t_amount= DB::Connection('mysql2')->table('transactions')->where('voucher_no',$row->pv_no)
    ->where('debit_credit',1)->sum('amount');
    $total+=$net_amount?>
    <tr <?php if($t_amount!=$net_amount): ?> <?php elseif($net_amount!=$net_amount_grn): ?> style="background-color: cornflowerblue" <?php endif; ?> id="<?php echo e($row->id); ?>">
        <td class="text-center"><?php echo e($counter++); ?></td>
        <td title="<?php echo e($row->id); ?>" class="text-center"><?php echo e(strtoupper($row->pv_no)); ?></td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->pv_date);?></td>
        <td title="<?php echo e($row->id); ?>" class="text-center"><?php echo e(strtoupper($row->grn_no)); ?>

        </br>
            <?php echo e($grn_date); ?>

        </td>
        <td class="text-center"><?php echo e($row['slip_no']); ?></td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->bill_date);?></td>
        <td id="app<?php echo e($row->id); ?>" class="text-center text-danger"><?php if($row->pv_status==1): ?> Pending <?php elseif($row->pv_status==3): ?> 1st Approve  <?php else: ?> Approved <?php endif; ?> </td>
        <td class="text-center"><?php echo e(CommonHelper::get_supplier_name($row->supplier)); ?></td>

        <td class="text-right">PKR <?php echo e(number_format($net_amount,2)); ?></td>
        <td class="text-right hide"><?php echo e(number_format($net_amount_grn,2)); ?></td>
        <?php $total+=$row['total_net_amount']; ?>
        <td class="text-center hidden-print">
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        <?php if($view == true): ?>
                            <?php if($row->grn_no == ''): ?>
                                <a onclick="showDetailModelOneParamerter('fdc/viewDirectPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>
                            <?php else: ?>
                                <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($row->pv_status == 1): ?>
                            <?php if($edit == true ): ?>
                                <?php if($row->grn_no == '0'): ?>
                                    <a type="button" href="<?php echo e(URL::asset('finance/editDirectPurchaseVoucherForm/'.$row->id.'?m='.$m)); ?>" class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                                <?php else: ?>
                                    <a type="button" href="<?php echo e(URL::asset('finance/editPurchaseVoucherFormNew/'.$row->id.'?m='.$m)); ?>" class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($delete==true && $row->pv_status != 2): ?>
                            <a type="button" onclick="delete_record('<?php echo $row->id?>','<?php echo $row->grn_no ?>','<?php echo $row->pv_no?>')" class="dropdown-item_sale_order_list dropdown-item">Delete</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </td>
    </tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td colspan="8">Total</td>
    <td class="text-right" colspan="1">PKR <?php echo e(number_format($total,2)); ?></td>
    <td></td>
</tr>