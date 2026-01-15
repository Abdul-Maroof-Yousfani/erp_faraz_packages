<?php $__env->startSection('content'); ?>
<?php echo $__env->make('select2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php
    use App\Helpers\CommonHelper;
    $i=1;
?>
<style>
    .my-lab label {
    padding-top:0px; 
}
</style>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Production</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Test on Item</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
        
        </div>
    </div>
    <div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form action="<?php echo e(route('QaGrn.storeTestResult')); ?>" method="post">
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                    <input type="hidden" name="grn_id" value="<?php echo e($qc_grn->grn_id); ?>">
                                    <input type="hidden" name="qc_grn_id" value="<?php echo e($qc_grn->qc_grn_id); ?>">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class=" qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>QC GRN TESTING</h1>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>GRN No</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input disabled id="grn_no"  value="<?php echo e($qc_grn->grn_no); ?>" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">GRN Date</label>
                                                        <div class="col-sm-8">
                                                            <input disabled id="grn_date" value="<?php echo e(CommonHelper::changeDateFormat($qc_grn->grn_date)); ?>" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Supplier Name</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input disabled id="supplier_name"  value="<?php echo e($qc_grn->supplier_name); ?>" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                </div>
 
                                                <div class="col-md-6">
 
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>QC GRN Date</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input disabled id="qc_grn_date"  value="<?php echo e(CommonHelper::changeDateFormat($qc_grn->qc_grn_date)); ?>" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">QC by</label>
                                                        <div class="col-sm-8">
                                                            <input disabled id="qc_by"  value="<?php echo e($qc_grn->qc_by); ?>" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                               
                                                </div>
                                            </div>
                                            <div class="col-md-12 padt">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-12" style="overflow-x: scroll;">
                                                        <table class="table">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Item Code</th>
                                                                <th>Received Qty</th>
                                                                <?php $__currentLoopData = $test_column; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <th>
                                                                        <table>

                                                                            <tr>
                                                                                <th class="text-center"><?php echo e($value->name); ?></th>
                                                                            </tr>

                                                                            <tr>
                                                                                <th>Remarks</th>
                                                                                <th>Test Result</th>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                                                                <th>QC Status</th> 
                                                            </tr>
                                                            <tbody id="more_details">
                                                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="grn_data_id[]" value="<?php echo e($value->id); ?>">
                                                                            <?php echo e($i++); ?>

                                                                        </td>
                                                                        
                                                                        <td>
                                                                            <?php echo e($value->sub_ic ?? 'N/A'); ?>

                                                                        </td>
                                                                        <td>
                                                                            <?php echo e($value->purchase_recived_qty ?? 0); ?>

                                                                        </td>
                                                                        <?php $__currentLoopData = $test_column; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test_column_key => $test_column_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <td>
                                                                            <table>
                                                                                <tr>
                                                                                    <td><?php echo e($test_column_value->remarks ?? 'N/A'); ?></td>
                                                                                    <td>
                                                                                        <input type="hidden" name="qa_test_id<?php echo e($value->id); ?>[]" value="<?php echo e($test_column_value->id); ?>">
                                                                                        <input type="text" name="test_result<?php echo e($value->id); ?>[]">
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
                                                                        <td>
                                                                            <select name="qc_test_status<?php echo e($value->id); ?>" id="">
                                                                                <option value="1">Pending</option>
                                                                                <option value="2">Pass</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-primary mr-1" id="btn" data-dismiss="modal">Save</button>
                                                </div>    
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>