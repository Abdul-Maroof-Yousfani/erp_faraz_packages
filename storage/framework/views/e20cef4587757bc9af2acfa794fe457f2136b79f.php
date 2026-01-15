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
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                    <input type="hidden" name="grn_id" value="<?php echo e($qc_grn->grn_id); ?>">
                                    <input type="hidden" name="qc_grn_id" value="<?php echo e($qc_grn->qc_grn_id); ?>">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class=" qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>QC GRN TESTING RESULT</h1>
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
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Item Code</th>
                                                                <th>Received Qty</th>
                                                                <th>Batch Code</th>
                                                                <th>Action</th> 
                                                            </tr>
                                                            <tbody id="more_details">
                                                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo e($i++); ?>

                                                                        </td>
                                                                        
                                                                        <td>
                                                                            <?php echo e($value->sub_ic ?? 'N/A'); ?>

                                                                        </td>
                                                                        <td>
                                                                            <?php echo e($value->purchase_recived_qty ?? 0); ?>

                                                                        </td>
                                                                        <td>
                                                                            <?php echo e($value->batch_code ?? 'N/A'); ?>

                                                                        </td>
                                                                        <td>
                                                                            <a class="btn btn-sm btn-success" onclick="showDetailModelOneParamerter('purchase/QaGrn/testResultOnReceiveItemAjax/<?php echo e($value->id); ?>',<?php echo e($value->id); ?>,'View QC GRN Test Result')" >
                                                                                <i class="fa fa-eye" aria-hidden="true"></i> view
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>        
                                    </div>
                                </div>
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