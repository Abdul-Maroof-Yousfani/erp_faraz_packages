
<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\QuotationHelper; 
 use App\Helpers\ReuseableCode; 

$view = ReuseableCode::check_rights(396);
$edit = ReuseableCode::check_rights(397);
$delete = ReuseableCode::check_rights(398);
$summary = ReuseableCode::check_rights(399);

$count=1;
$pr_no = [];
?>
<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

  
<?php $status = 0 ;?>
   <?php if(!in_array($row->pr_no, $pr_no)): ?>
   <?php $pr_no[] = $row->pr_no; ?>
    <tr class="text-center">
    <td  colspan="10" style="font-weight: bold"> 
        <?php   echo 'PR No : '. strtoupper($row->pr_no);
        echo '<br>'.'PR Date : '.CommonHelper::changeDateFormat($row->demand_date);
        //echo '<br><p style="color: #c59f9f">'.'Status : '.QuotationHelper::check_quotation_status($row->quotation_approve).'</p>';
        ?></td> 
   <?php $status =  1 ?>
</tr>
<?php endif; ?>


<tr class="text-center">

<?php
if($row->currency_id == 3):
    $cur = 'PKR';
elseif($row->currency_id == 4):
    $cur = 'USD';
endif;
?>
    <td class="text-center"><?php echo e($count++); ?></td>
    <td class="text-center"><?php echo e(strtoupper($row->voucher_no)); ?></td>
    <td class="text-center"><?php echo e(CommonHelper::changeDateFormat($row->voucher_date)); ?></td>
    <td><?php echo e(CommonHelper::get_supplier_name($row->vendor_id)); ?></td>
    <td class="text-center"><?php echo e($row->ref_no); ?></td>
    <td><?php echo e($cur); ?> <?php echo e(number_format($row->amount + $row->gst_amount,2)); ?></td>
    <td class="text-center"><?php echo e(QuotationHelper::quotationStatus($row->quotation_status)); ?></td>
    <td>
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu">
                <li>
                    <?php if($view == true): ?>
                        <a onclick="showDetailModelOneParamerter('quotation/view_quotation?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Quotation')"
                        type="button" class="">View</a>
                    <?php endif; ?>

                    <?php if($edit == true && $row->quotation_status == 1): ?>
                        <a  href="<?php echo e(url('quotation/edit_quotation/'.$row->pr_id.'/'.$row->id)); ?>" class="">Edit</a>
                    <?php endif; ?>
                    <?php if($delete == true): ?>
                        <a onclick="delete_quotation('<?php echo e($row->id); ?>')" type="button" class="">Delete</a>
                    <?php endif; ?>
                    <?php if($summary == true): ?>
                        <a onclick="showDetailModelOneParamerter('quotation/qutation_summary?m=<?php echo Session::get('run_company')?>','<?php echo $row->pr_id;?>','Quotation')"
                        type="button" class="">Details</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
   </td>
</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

