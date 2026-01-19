<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
echo CommonHelper::headerPrintSectionInPrintView($m);?>
<?php echo Form::open(array('url' => 'purchase/createPurchaseVoucherFormThroughGrn?m=1','id'=>'cashPaymentVoucherForm'));?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                <thead>
                <th class="text-center"></th>
                <th class="text-center col-sm-1">S.No</th>
                <th class="text-center">GRN No</th>
                <th class="text-center">Grn Date</th>
                <th class="text-center">Supplier Invoice No.</th>
                <th class="text-center">PO No</th>
                
                <th class="text-center">Supplier Name</th>
                <th class="text-center">Voucher Type</th>
                <th class="text-center">View</th>
                </thead>
                <tbody>
                <?php $counter = 1;?>

                <?php $__currentLoopData = $good_recipt_note; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr <?php if($row->type==1): ?>style="background-color: lightblue"<?php endif; ?>>
                        <td class="text-center">
                            <input name="checkbox[]" onclick="check('<?php echo $row->supplier_id?>')" class="checkbox1 form-control AddRemoveClass<?php echo $row->supplier_id?>" id="<?php echo $row->supplier_id?>" type="checkbox" value="<?php echo e($row->id); ?>" style="width: 30px">
                        </td>
                        <td class="text-center"><?php echo e($counter++); ?></td>
                        <td class="text-center"><?php echo e(strtoupper($row->grn_no)); ?></td>
                        <td class="text-center"><?php echo e(CommonHelper::changeDateFormat($row->grn_date)); ?></td>
                        <td class="text-center"><?php echo e(strtoupper($row->supplier_invoice_no)); ?></td>
                        <td class="text-center"><?php echo e(strtoupper($row->po_no)); ?></td>
                        


                        
                        <td class="text-center"><?php echo e(CommonHelper::get_supplier_name($row->supplier_id)); ?></td>
                        <?php
                        $VoucherType = "";
                        if($row->po_no == "" && $row->type==2){$VoucherType = "Direct Grn";}
                        if($row->po_no != "" && $row->type==0){$VoucherType = "Through PO";}
                        ?>
                        <td class="text-center"><?php echo $VoucherType;?></td>
                        <td class="text-center"><button
                                    onclick="showDetailModelOneParamerter('pdc/viewGoodsReceiptNoteDetail','<?php echo $row->grn_no ?>','View Goods Receipt Note Voucher Detail','<?php echo $m;?>')"
                                    type="button" class="btn btn-success btn-xs">View</button></td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="submit" value="Create Purchase Invoice" class="btn btn-xs btn-success pull-left" id="add" disabled="">
            </div>
        </div>
    </div>
</div>
<?php Form::close(); ?>