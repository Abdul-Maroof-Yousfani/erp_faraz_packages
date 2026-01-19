<?php 
    use App\Helpers\CommonHelper;
    $count = 1;
?>

<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr id="tr<?php echo e($count); ?>">
        <td><?php echo e($value->grn_no ?? $value->order_no); ?></td>
        <td><?php echo e(CommonHelper::changeDateFormat($value->qc_grn_date ?? $value->qc_packing_date)); ?></td>
        <td><?php echo e($value->qc_by); ?></td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    
                    <li>
                        <a href="<?php echo e(route('QaGrn.testResultOnReceiveItem', $value->qg_id ?? $value->id)); ?>" type="button" class="dropdown-item_sale_order_list dropdown-item">Test Result</a>
                    </li>
                    <li>
                        <a onclick="showDetailModelTwoParamerter('purchase/QaGrn/grnViewQaGrnDetail','<?php echo e($value->qg_id ?? $value->id); ?>','View QC GRN Detail')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('QaGrn.edit', $value->qg_id ?? $value->id)); ?>" type="button" class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                    </li>

                    <li>
                        <?php if(Auth::user()->acc_type == "client"): ?>
                            <a onclick="delete_row('#tr<?php echo e($count); ?>',<?php echo e($value->qg_id ?? $value->qp_id ?? $value->id); ?>,0 )" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    <?php 
        $count++;
    ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<script>
    function delete_row(tr, qc_grn_id, grn_id) {
        if (confirm('Are you sure you want to delete this QC GRN record?')) {
            $.ajax({
                url: '<?php echo e(url('/')); ?>/purchase/QaGrn/delete',
                type: 'Get',
                data: {
                    qc_grn_id: qc_grn_id,
                    grn_id: grn_id
                },
                success: function (response) {
                    if (response.success) {
                        $(tr).remove();
                        alert('QC GRN deleted successfully');
                    } else {
                        alert('Error deleting QC GRN: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr) {
                    alert('Error deleting QC GRN. Please try again.');
                }
            });
        }
    }

</script>