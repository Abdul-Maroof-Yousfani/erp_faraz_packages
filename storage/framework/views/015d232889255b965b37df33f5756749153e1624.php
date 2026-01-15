<?php 
    use App\Helpers\CommonHelper;
    $count = 1;
?>

<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr id="tr<?php echo e($count); ?>">
        <td><?php echo e($value->order_no); ?></td>
        <td><?php echo e(CommonHelper::changeDateFormat($value->qc_packing_date)); ?></td>
        <td><?php echo e($value->qc_by); ?></td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <!-- <li>
                        <a href="<?php echo e(route('QaPacking.testingOnReceiveItem', $value->id)); ?>" type="button" class="dropdown-item_sale_order_list dropdown-item">Perform Test</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('QaPacking.testResultOnReceiveItem', $value->id)); ?>" type="button" class="dropdown-item_sale_order_list dropdown-item">Test Result</a>
                    </li> -->
                    <li>
                        <a onclick="showDetailModelTwoParamerter('Production/QaPacking/viewQaPackingDetail','<?php echo e($value->id); ?>','View QC Detail')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('QaPacking.edit', $value->id)); ?>" type="button" class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                    </li>

                    <li>
                        <?php if(Auth::user()->acc_type == "client"): ?>
                            <a onclick="delete_row('#tr<?php echo e($count); ?>',<?php echo e($value->qp_id); ?>,0 )" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>
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
    function delete_row(tr, qc_packing_id, packing_list_id) {
        $.ajax({
            url: '<?php echo e(url('/')); ?>/Production/QaPacking/delete',
            type: 'Get',
            data: {
                qc_packing_id: qc_packing_id,
                packing_list_id: packing_list_id
            },
            success: function (response) {
                $(tr).remove();
            }
        });
    }

</script>