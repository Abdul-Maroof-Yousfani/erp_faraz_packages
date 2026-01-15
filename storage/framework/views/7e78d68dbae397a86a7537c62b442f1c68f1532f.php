
<?php
use App\Helpers\CommonHelper;
$count = 1;
?>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="dp_sdw2">
                    <div class="row" id="printReport">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                                
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right hidden-print">
                                            <h1><?php CommonHelper::displayPrintButtonInView('printReport','','1');?></h1>
                                        </div>
                                    </div>

                                    <div class="contra">
                                        <div class="row">
                                            
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                <div class="">
                                                  <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?> 
  
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="contr2">
                                                    <div class="con_rew3" style="text-align: center;">
                                                        <h2>ISSUE: 1 </h2>
                                                        <h2>PAGE: 1/1</h2>
                                                        <h2>QC/FM/17</h2>
                                                    </div>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="bro_src">
                                        <div class="row" id="printReport">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="secc">
                                                    <hr style="border:1px solid #000">
                                                        <h2>INTERNAL TESTING REPORT</h2>
                                                    <hr style="border:1px solid #000">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="ode">
                                      
                                            </div>
                                            <div class="sal">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
                                                        <div class="ordeno">
                                                            <h2>Supplier:</h2>
                                                            <h2>GRN No:</h2>
                                                            <h2>GRN Date:</h2>
                                                            <h2>Item under Test:</h2>
                                                            <h2>QC Date:</h2>
                                                            <h2>Batch Code:</h2>
                                                            <h2>QC by:</h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-7">
                                                        <div class="ors_pra">
                                                            <p><?php echo e($mainData->supplier_name ?? 'N/A'); ?></p>
                                                            <p><?php echo e($mainData->grn_no ?? 'N/A'); ?></p>
                                                            <p><?php echo e($mainData->grn_date ? date("d-M-Y", strtotime($mainData->grn_date)) : 'N/A'); ?></p>
                                                            <p><?php echo e($mainData->sub_ic ?? 'N/A'); ?></p>
                                                            <p>
                                                                <?php echo e($mainData->qc_grn_date ? date("d-M-Y", strtotime($mainData->qc_grn_date)) : 'N/A'); ?>

                                                            </p>
                                                            <p><?php echo e($mainData->batch_code ?? 'N/A'); ?></p>
                                                            <p><?php echo e($mainData->qc_by ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="tasetb">
                                                <table class="userlittab3 table table-bordered sf-table-list3">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">S.No.</th>
                                                        <th class="text-center">Item Description</th>
                                                        <th class="wsale2 text-center">Required Values</th>
                                                        <th class="text-center">Test Observations</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="data">
                                                        <?php if(count($mechanicaltest) > 0): ?>
                                                        <tr>
                                                            <th colspan="4" class="">Mechanical Characteristic</th>
                                                        </tr>
                                                        <?php $__currentLoopData = $mechanicaltest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo e($count++); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->name ?? 'N/A'); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->standard_value ?? 'N/A'); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->test_value ?? 'N/A'); ?> - <?php echo e($value->remarks ?? 'N/A'); ?>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                        
                                                        <?php if(count($physicaltest) > 0): ?>
                                                        <?php $count = 1; ?>
                                                        <tr>
                                                            <th colspan="4" class="">Physical Characteristic</th>
                                                        </tr>
                                                        <?php $__currentLoopData = $physicaltest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo e($count++); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->name ?? 'N/A'); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->standard_value ?? 'N/A'); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->test_value ?? 'N/A'); ?> - <?php echo e($value->remarks ?? 'N/A'); ?>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                        
                                                        <?php if(count($chemicaltest) > 0): ?>
                                                        <?php $count = 1; ?>
                                                        <tr>
                                                            <th colspan="4" class="">Chemical Characteristic</th>
                                                        </tr>
                                                        <?php $__currentLoopData = $chemicaltest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo e($count++); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->name ?? 'N/A'); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->standard_value ?? 'N/A'); ?>

                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo e($value->test_value ?? 'N/A'); ?> - <?php echo e($value->remarks ?? 'N/A'); ?>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                        
                                                        <?php if(count($test_results) == 0): ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center">No test results available</td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="contra">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="">
                                                    <h2>FOR Zahabiya Chemicals INDUSTRIES (PVT) LIMITED </h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="con_rewB">
                                                   

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
