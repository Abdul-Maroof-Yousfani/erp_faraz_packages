
<?php use App\Helpers\CommonHelper; ?>

<?php $count=1; ?>
<?php $__currentLoopData = $data->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr class="text-center">
    <td><?php echo e($count++); ?></td>
    <td><?php echo e(strtoupper($row->demand_no)); ?></td>
    <td><?php echo e(CommonHelper::changeDateFormat($row->demand_date)); ?></td>
    <td><?php echo e($row->slip_no); ?></td>
    <td><?php echo e(CommonHelper::get_sub_dept_name($row->sub_department_id)); ?></td>
    <td><a style="padding: 7px; font-size: 12px;"href="<?php echo e(url('quotation/quotation_form/'.$row->id)); ?>" type="button" class="btn btn-sm btn-success">Create Quotation</a></td>
</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>