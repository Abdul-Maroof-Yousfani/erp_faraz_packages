<?php 
    $count = 1;
?>    

<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr id="tr<?php echo e($count); ?>">
    <td><?php echo e(str_replace('_', ' ', $value->type)); ?></td>
    <td><?php echo e($value->name); ?></td>
    <td><?php echo e(number_format($value->limit , 2)); ?></td>
    <td><?php echo e(number_format($value->limit_utilized , 2)); ?></td>
    <td><?php echo e(number_format($value->un_utilized , 2)); ?></td>
    <td><?php echo e(sprintf("%.2f", $value->remaining_percentage)); ?> %</td>
     
    </tr>
    <?php 
    $count ++;
    ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

