<?php

use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\NotificationHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$getAllInput = $request->all();
?>


<?php echo $__env->make('select2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('number_formate', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script>
    var counter=1;
</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
        <div class="dp_sdw">    
            
            <?php echo e(Form::open(array('url' => 'stad/addPurchaseRequestDetail?m='.$m.'','id'=>'addPurchaseRequestDetail', 'method' => 'POST'))); ?>

            <?php

            $purchaseRequestNo=CommonHelper::get_unique_po_no(1);

            $counter = 1;
            
            if ($po_against == 1) {
                $data = DB::Connection('mysql2')->table('demand_data as a')->join('demand as b', 'a.master_id', '=', 'b.id')
                    ->leftJoin('quotation_data as qd','qd.pr_data_id','=', 'a.id')
                    ->where([['b.id', '=', $pr_no],['b.status','=', 1],['b.demand_status','=', 2],['a.demand_status','=', 1]])
                    ->whereNull('qd.pr_data_id')
                    ->select('a.sub_item_id', 'a.id','a.demand_no', 'a.demand_date','a.qty','b.sub_department_id')->get();
            } elseif ($po_against == 2) {
                $data = DB::Connection('mysql2')->table('demand_data as a')
                    ->join('demand as b', 'a.master_id', '=', 'b.id')->join('quotation_data as c','c.pr_data_id','=', 'a.id')
                    ->join('quotation as d', 'd.id', '=', 'c.master_id')
                    ->where([['d.id', '=', $pr_no],['d.status','=', 1],['d.quotation_status','=', 2],['c.quotation_status','=', 1]])
                    ->select('a.sub_item_id', 'a.id','a.demand_no', 'a.demand_date','a.qty','c.rate', 'c.amount', 'b.sub_department_id','d.gst','b.p_type','d.vendor_id')->get();
            }
            
            $vendor_id= $data[0]->vendor_id ?? 0; 
            $dept_id= $data[0]->sub_department_id ?? 0;  
            $p_type= $data[0]->p_type ?? '';
            $sales_tax= $data[0]->gst ?? 0; 

            ?>
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
            <input type="hidden" name="dept_id" value="<?php echo e($dept_id); ?>"/>

            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">PO No.</label>
                                    <input readonly type="text" class="form-control requiredField" placeholder="" name="po_no" id="po_no" value="<?php echo e(strtoupper($purchaseRequestNo)); ?>" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">PO Date.</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="date" required class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="po_date" id="po_date" value="<?php echo date('Y-m-d') ?>" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Department</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="sub_department_name" id="sub_department_name" class="form-control" readonly value="<?php echo e(CommonHelper::get_sub_dept_name($dept_id)); ?>" >
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none">
                                    <label class="sf-label">Supplier Reference No.</label>
                                    <input autofocus type="text" class="form-control" placeholder="Ref No" name="slip_no" id="slip_no" value="-" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                    <label class="sf-label">Terms Of Delivery</label>
                                    <input type="text" class="form-control" placeholder="Terms Of Delivery" name="term_of_del" id="term_of_del" value="" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                    <label class="sf-label">PO Type</label>
                                    <select onchange="get_po(this.id)" name="po_type" id="po_type" class="form-control">
                                        <option  value="1">Purchase Local</option>
                                        <option  value="2">Self</option>
                                        <option  value="3">International</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');" class="">Vendor</a></label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select required onchange="get_address()" name="supplier_id" id="supplier_id" class="form-control requiredField select2">
                                        <option value="">Select Vendor</option>
                                        <?php $__currentLoopData = $supplierList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $address= CommonHelper::get_supplier_address($row1->id); ?>
                                        <option <?php if($vendor_id == $row1->id): ?>selected <?php endif; ?> value="<?php echo $row1->id.'@#'.$address.'@#'.$row1->ntn.'@#'.$row1->terms_of_payment.'@#'.$row1->strn.'@#'.($row1->no_of_days ?? '')?>"><?php echo ucwords($row1->name)?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Destination</label>
                                    <input style="text-transform: capitalize;"  type="text" class="form-control" placeholder="" name="destination" id="destination" value="" />
                                </div>
                                
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')" class="">Currency</a></label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select required onchange="claculation(1);getrate();" name="curren" id="curren" class="form-control select2 requiredField">
                                        <option value="">Select Currency</option>
                                        <?php $__currentLoopData = CommonHelper::get_all_currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($row->id == 3): ?> selected <?php endif; ?> data-value="<?php echo e($row->rate); ?>" value="<?php echo e($row->id.','.$row->rate); ?>"><?php echo e($row->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label"> Currency Rate</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input required class="form-control requiredField" value="1.0" type="text" name="currency_rate" id="currency_rate" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Due Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input required type="date" class="form-control requiredField" name="due_date" id="due_date" min="<?php echo e(date('Y-m-d')); ?>" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Payment Term</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select required onchange="calculate_due_date()" name="model_terms_of_payment" id="model_terms_of_payment" class="form-control select2 requiredField">
                                        <option value="">Select Payment Term</option>
                                        <option value="1">Advance</option>
                                        <option value="2">Against Delivery</option>
                                        <option value="3">Credit</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" id="no_of_days_container" style="display: none;">
                                    <label class="sf-label">No. of Days</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="number" class="form-control requiredField" name="no_of_days" id="no_of_days" min="1" value="" onchange="calculate_due_date()" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hide">
                                    <label class="sf-label">Mode/ Terms Of Payment <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input   type="number" class="form-control requiredField" placeholder="" name="" id="" value="" />
                                </div>
                                <input type="hidden" value="<?php echo e($p_type); ?>"   name="p_type_id" />
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div class="row hide">
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                    <label class="sf-label">Supplier's Address</label>
                                    <input style="text-transform: capitalize;" readonly type="text" class="form-control" placeholder="" name="address" id="addresss" value="" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="sf-label">Supplier's NTN</label>
                                    <input readonly type="text" class="form-control" placeholder="Ntn" name="ntn" id="ntn_id" value="" />
                                </div>
                            </div>
                            <div class="row hide">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">STRN <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input type="text" name="trn" id="trn" class="form-control" placeholder="STRN">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Builty No</label>
                                    <input type="text" name="builty_no" id="builty_no" class="form-control" placeholder="Builty No">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Remarks</label>
                                    <textarea  name="remarks" id="remarks" class="form-control" placeholder="Terms & Condition"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="sf-label">Terms & Condition</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
<textarea  name="main_description" id="main_description" rows="4" cols="50" style="resize:none;font-size: 11px;" class="form-control requiredField">
1. Above price is Inclusive of GST.
2. Delivery Chalan in duplicate should accompany the supply
3. Defected/ Rejected supply should be picked up within 7 days of intimation
4. Signed Delivery Chalan copy should accompany the invoice
5. PO Number should be mentioned on invoice.
6. WHT Declaration for Import/ GD Copy or Exemption Letter should accompany invoice. Otherwise, WHT as per 
prescribed rate would be deducted
7. Required with Shipment/Delivery: Technical Data      Safety Data      Product Analysis Certificate
8. Payment Terms
</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                        <th class="text-center">Check</th>
                                        <th class="text-center">Sr No</th>
                                        <th class="text-center hide" >Pr No</th>
                                        <th class="text-center hide" >Pr Date</th>
                                        <th class="text-center" colspan="1">Item Name</th>
                                        <th class="text-center" >UOM</th>
                                        <th class="text-center" >Qty</th>
                                        <th class="text-center" >Rate</th>
                                        <th class="text-center hide" >Amount</th>
                                        <th class="text-center hide" >Discount %</th>
                                        <th class="text-center hide" >Discount</th>
                                        <th class="text-center" >Net Amount</th>
                                        <th class="text-center" >Remarks</th>

                                    </thead>
                                    <tbody id="filterDemandVoucherList">
                                <?php
                                $counter1 = 1;
                                $all_total=0;
                                foreach ($data as $key => $row) { ?>

                                
                                    <tr id="removeSelectedPurchaseRequestRow_<?php echo $counter1;?>" class="text-center">
                                        
                                        <input type="hidden" name="seletedPurchaseRequestRow[]" readonly id="seletedPurchaseRequestRow" value="<?php echo $counter1;?>" class="form-control" />
                                        <input type="hidden" name="demandNo_<?php echo $counter1;?>" readonly id="demandNo_<?php echo $counter1;?>" value="<?php echo $row->demand_no;?>" class="form-control" />
                                        <input type="hidden" name="demandDate_<?php echo $counter1;?>" readonly id="demandDate_<?php echo $counter1;?>" value="<?php echo $row->demand_date;?>" class="form-control" />
                                        <input type="hidden" name="demandType_<?php echo $counter1;?>" readonly id="demandType_<?php echo $counter1;?>" value="<?php ?>" class="form-control" />
                                        <input type="hidden" name="demandSendType_<?php echo $counter1;?>" readonly id="demandSendType_<?php echo $counter1;?>" value="<?php ?>" class="form-control" />
                                        <input type="hidden" name="demand_data_id<?php echo $counter1;?>"  id="demand_data_id<?php echo $counter1;?>" value="<?php echo e($row->id); ?>">
                                        <input type="hidden" name="purchase_request_qty_<?php echo $counter1 ?>" value="<?php echo e($row->qty); ?>" id="purchase_request_qty_<?php echo $counter1 ?>"/>


                                        <?php   $sub_ic_detail=CommonHelper::get_subitem_detail($row->sub_item_id);
                                       // echo "<pre>"; print_r($sub_ic_detail); die;

                                        $sub_ic_detail= explode(',',$sub_ic_detail);
                                        // $rate = $sub_ic_detail[2];
                                        // if ($rate == '' || $rate == 0):
                                        //     $rate = 0;
                                        // endif;
                                        //echo $rate; die;
                                        $rate = $row->rate ?? 0;
                                        
                                        ?>

                                        <td>
                                            <input type="checkbox" name="checked_rows[]" value="<?php echo $counter1; ?>" id="check<?php echo $counter1; ?>">
                                        </td>
                                        <td class="text-center"><?php echo $counter1?></td>
                                        <td class="hide"><?php echo strtoupper($row->demand_no)?></td>
                                        <td class="hide"><?php echo CommonHelper::changeDateFormat($row->demand_date)?></td>
                                        <td colspan="1">
                                            <?php $sub_item_id = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row->sub_item_id);?>

                                            <a href="<?php echo url('/') ?>/store/item_detaild_supplier_wise?&sub_item_id=<?php echo $row->sub_item_id ?>" target="_blank"><?php echo e($sub_item_id); ?></a>

                                            <input type="hidden" name="subItemId_<?php echo $counter1;?>" readonly id="subItemId_<?php echo $counter1;?>" value="<?php echo $row->sub_item_id;?>" class="form-control" />
                                        </td>
                                        <td > <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>
                                        <td class="text-center">
                                            <input onkeyup="claculation('<?php echo  $counter1 ?>')" type="text" name="purchase_approve_qty_<?php echo $counter1?>" id="purchase_approve_qty_<?php echo $counter1?>" class="form-control requiredField approveQty" min="1" value="<?php echo e($row->qty); ?>" />
                                        </td>
                                        <td class="text-center">
                                            <input onkeyup="claculation('<?php echo $counter1 ?>')" type="text" name="rate_<?php echo $counter1?>" id="rate_<?php echo $counter1?>" class="form-control requiredField ApproveRate" step="any" value="<?php echo e($rate); ?>" />
                                        </td>

                                        <td class="text-center hide">
                                            <input  readonly style="text-align: right" type="text" name="amount_<?php echo $counter1?>" id="amount_<?php echo $counter1?>" class="form-control requiredField amount text-right" min="1" value="<?php echo e($total = $rate * $row->qty); ?>" step="any" />
                                        </td>
                                        <td class="text-center hide">
                                            <input onkeyup="discount_percent(this.id)" value="0" class="form-control requiredField" type="text" name="discount_percent_<?php echo $counter1?>" id="discount_percent_<?php echo $counter1?>"/>
                                        </td>
                                        <td class="text-center hide">
                                            <input onkeyup="discount_amount(this.id)" class="form-control requiredField" type="text" name="discount_amount_<?php echo $counter1?>" id="discount_amount_<?php echo $counter1?>"/>
                                        </td>
                                        <td class="text-center">
                                            <input readonly class="form-control net_amount_dis" type="text" value="<?php echo e($rate * $row->qty); ?>" name="after_dis_amountt_<?php echo $counter1?>" id="after_dis_amountt_<?php echo $counter1?>"/>
                                        </td>
                                        <td><textarea class="form-control" name="description_<?php echo $counter1;?>"></textarea></td>
                                    </tr>
                                    <script>
                                        counter='  <?php echo $counter1;  ?>';
                                    </script>
                                    <?php
                                    $all_total+=$total;
                                    $counter1++;
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: right;">
                            <table class="table table-bordered sf-table-list">
                                <thead>
                                <th class="text-center" colspan="3">Sales Tax Account Head</th>
                                <th class="text-center" colspan="3">Sales Tax Amount</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select onchange="sales_tax(this.id);open_sales_tax(this.id)" class="form-control select2" id="sales_taxx" name="sales_taxx">
                                            <option value="0">Select Sales Tax</option>
                                        
                                            <?php $__currentLoopData = ReuseableCode::get_all_sales_tax(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    
                                                <option <?php if($sales_tax==$row->rate): ?> selected <?php endif; ?>  value="<?php echo e($row->rate); ?>"><?php echo e($row->rate); ?> %</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                    <td class="text-right"  colspan="3">
                                        <input onkeyup="tax_by_amount(this.id)" type="text" class="form-control" name="sales_amount_td" id="sales_amount_td"/>
                                    </td>
                                    <input type="hidden" name="sales_amount" id="sales_tax_amount"/>
                                </tr>

                                <tr>
                                    <td style="background-color: darkgray" colspan="3" class="text-center"> Total</td>

                                    <td style="background-color: darkgray" colspan="2" class="text-right">
                                        <input style="background-color: darkgray;text-align: right;font-weight: bold" class="td_amount form-control" type="text" name="total_amount" id="d_t_amount_1" value="<?php echo e($all_total); ?>"/>
                                        
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <table>
                        <tr>
                            <td style="text-transform: capitalize;" id="rupees"></td>
                            <input type="hidden" value="<?php echo e(0); ?>" name="rupeess" id="rupeess1"/>
                        </tr>
                    </table>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <?php echo e(Form::submit('Submit', ['class' => 'btn btn-success'])); ?>

                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo Form::close();?>
        </div>
    </div>
</div>
    
<script>

$(document).ready(function () {
    console.log("Document ready triggered.");

    // Disable all fields on page load
    $('input[name="checked_rows[]"]').each(function () {
        const key = $(this).val();
        const approveQtyInput = $(`input[name="purchase_approve_qty_${key}"]`);
        const rateInput = $(`input[name="rate_${key}"]`);
        const amountInput = $(`input[name="amount_${key}"]`);

        approveQtyInput.prop('disabled', true);
        rateInput.prop('disabled', true);
        amountInput.prop('disabled', true);

        // Toggle enable/disable based on checkbox state
        $(this).on('change', function () {
            if ($(this).is(':checked')) {
                approveQtyInput.prop('disabled', false);
                rateInput.prop('disabled', false);
                amountInput.prop('disabled', false);
            } else {
                approveQtyInput.prop('disabled', true);
                rateInput.prop('disabled', true);
                amountInput.prop('disabled', true);
            }
        });
    });

    // Form submission validation
    $('#addPurchaseRequestDetail').on('submit', function (e) {
        console.log("Form submission triggered.");
        let isAnyChecked = false;
        let validationFailed = false;

        $('input[name="checked_rows[]"]').each(function () {
            if ($(this).is(':checked')) {
                isAnyChecked = true;
                const key = $(this).val();
                const approveQtyInput = $(`input[name="purchase_approve_qty_${key}"]`);
                const rateInput = $(`input[name="rate_${key}"]`);

                // Validate fields
                if (approveQtyInput.val() === '' || parseFloat(approveQtyInput.val()) <= 0) {
                    alert("Please enter a valid Approved Qty.");
                    approveQtyInput.css('border-color', 'red');
                    validationFailed = true;
                } else {
                    approveQtyInput.css('border-color', '');
                }

                if (rateInput.val() === '' || parseFloat(rateInput.val()) <= 0) {
                    alert("Please enter a valid Rate.");
                    rateInput.css('border-color', 'red');
                    validationFailed = true;
                } else {
                    rateInput.css('border-color', '');
                }
            }
        });

        if (!isAnyChecked) {
            alert("Please select at least one row before submitting the form.");
            e.preventDefault();
            return false;
        }

        if (validationFailed) {
            e.preventDefault();
            return false;
        }

        console.log("Form validated successfully.");
    });
});

    function get_po(id)
    {
        var number=$('#'+id).val();
        
        var po=$('#po_no').val();
        if (number==1)
        {
            var res = po.slice(2, 9);
            var pl_no='PL'+res;
            $('#po_no').val(pl_no);

        }
        if (number==2)
        {
            var res = po.slice(2, 9);
            var pl_no='PS'+res;
            $('#po_no').val(pl_no);

        }
        if (number==3)
        {
            var res = po.slice(2, 9);
            var pl_no='PI'+res;
            $('#po_no').val(pl_no);

        }
    }

</script>
<script>
    var x=0;


    function getrate() {
        var selectedOption = document.querySelector('#curren option:checked');
        var rate = selectedOption.getAttribute('data-value');
        document.getElementById('currency_rate').value = rate ? rate : '1.0';
    }


   

    function tax_by_amount(id)
    {
        var tax_percentage=$('#sales_taxx').val();
        if (tax_percentage==0)
        {

            $('#'+id).val(0);
        }
        else
        {
            var tax_amount=parseFloat($('#'+id).val());

            // highlight end

            if (isNaN(tax_amount)==true)
            {
                tax_amount=0;
            }
            var count=1;
            var amount = 0;
            $('.net_amount_dis').each(function () {


                amount += +$('#after_dis_amountt_' + count).val();
                count++;
            });
            var total=parseFloat(tax_amount+amount).toFixed(3);
            $('#d_t_amount_1').val(total);


        }
        toWords(1);
    }

    function sales_tax(id)
    {
        var sales_tax = 0;
        var sales_tax_per_value = $('#sales_taxx').val();
    
        var net = net_amount();
        if (sales_tax_per_value!='0')
        {
    
        
            var sales_tax = (net / 100)*sales_tax_per_value;

        }

        $('#sales_amount_td').val(sales_tax);

        $('#d_t_amount_1').val(net+sales_tax);

        toWords(1);
    }

    function discount_percent(id)
    {
        var  number= id.replace("discount_percent_","");
        var amount = $('#amount_' + number).val();

        var x = parseFloat($('#'+id).val());

        if (x >100)
        {
            alert('Percentage Cannot Exceed by 100');
            $('#'+id).val(0);
            x=0;
        }

        x=x*amount;
        var discount_amount =parseFloat( x / 100).toFixed(3);
        $('#discount_amount_'+number).val(discount_amount);
        var discount_amount=$('#discount_amount_'+number).val();

        if (isNaN(discount_amount))
        {

            $('#discount_amount_'+number).val(0);
            discount_amount=0;
        }



        var amount_after_discount=amount-discount_amount;

        $('#after_dis_amountt_'+number).val(amount_after_discount);
        var amount_after_discount=$('#after_dis_amountt_'+number).val();

        if (amount_after_discount==0)
        {
            $('#after_dis_amountt_'+number).val(amount);
            $('#net_amounttd_'+number).val(amount);
            $('#net_amount_'+number).val(amount_after_discount);
        }

        else
        {
            $('#net_amounttd_'+number).val(amount_after_discount);
            $('#net_amount_'+number).val(amount_after_discount);
        }

        $('#cost_center_dept_amount'+number).text(amount_after_discount);
        $('#cost_center_dept_hidden_amount'+number).val(amount_after_discount);


        sales_tax('sales_taxx');

        toWords(1);
    }

    function sales_taxx(id)
    {
        var sales_tax_per_value = $('#'+id).val();

        if (sales_tax_per_value!=0)
        {
            var sales_tax_per = $('#' + id + ' :selected').text();
            sales_tax_per = sales_tax_per.split('(');
            sales_tax_per = sales_tax_per[1];
            sales_tax_per = sales_tax_per.replace('%)', '');

        }

        else
        {
            sales_tax_per=0;
        }

        count=1;
        var amount = 0;
        $('.net_amount_dis').each(function () {


            amount += +$('#after_dis_amountt_' + count).val();
            count++;
        });


        var x = parseFloat(sales_tax_per * amount);
        var s_tax_amount =parseFloat( x / 100).toFixed(3);

        $('#sales_tax_amount').val(s_tax_amount);
        $('#sales_amount_td').val(s_tax_amount);

        var amount = 0;
        count=1;
        $('.net_amount_dis').each(function () {


            amount += +$('#after_dis_amountt_' + count).val();
            count++;
        });
        amount=parseFloat(amount);
        s_tax_amount=parseFloat(s_tax_amount);
        var total_amount=(amount+s_tax_amount).toFixed(2);
        $('.td_amount').text(total_amount);
        $('#d_t_amount_1').val(total_amount);
        toWords(1);
    }

    function net_amount()
    {
        var amount = 0;
        $('.net_amount_dis').each(function () {


                amount += +$(this).val();
                
            });

            return amount;
    }

    function calculate_due_date()
    {
        var paymentTerm = $('#model_terms_of_payment').val();
        var days = 0;
        
        if(paymentTerm == '3') { // Credit
            days = parseFloat($('#no_of_days').val()) || 0;
            $('#no_of_days_container').show();
            $('#no_of_days').attr('required', true);
        } else {
            days = parseFloat(paymentTerm) - 1;
            $('#no_of_days_container').hide();
            $('#no_of_days').removeAttr('required');
        }
        
        var tt = document.getElementById('po_date').value;
        if(!tt) return;

        var date = new Date(tt);
        var newdate = new Date(date);
        newdate.setDate(newdate.getDate() + days);
        var dd = newdate.getDate();

        var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
        var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
        var y = newdate.getFullYear();
        var someFormattedDate =  + y+'-'+ mm +'-'+dd;

        document.getElementById('due_date').value = someFormattedDate;
    }




    $(document).ready(function() {
        
        for(i=1; i<=counter; i++)
        {
            $('#amount_'+i).number(true,3);
            //   $('#rate_'+i).number(true,2);
            $('#purchase_approve_qty_'+i).number(true,3);
            $('#discount_percent_'+i).number(true,3);
            $('#discount_amount_'+i).number(true,3);
            $('#after_dis_amountt_'+i).number(true,3);
            $('#rate_'+i).number(true,3);

            claculation(i);
            
        }
        get_address();
        $('#d_t_amount_1').number(true,2);
        $('#sales_amount_td').number(true,3);
        
        // Check if payment term is Credit on page load
        if($('#model_terms_of_payment').val() == '3') {
            $('#no_of_days_container').show();
            $('#no_of_days').attr('required', true);
        }

    });
    function removeSeletedPurchaseRequestRows(id,counter){
        var totalCounter = $('#totalCounter').val();
        if(totalCounter == 1){
            alert('Last Row Not Deleted');
        }else{
            var lessCounter = totalCounter - 1;
            var totalCounter = $('#totalCounter').val(lessCounter);
            var elem = document.getElementById('removeSelectedPurchaseRequestRow_'+counter+'');
            elem.parentNode.removeChild(elem);
        }

    }

    $(document).ready(function() {
        toWords(1);
    });


    function claculation(number)
    {
        
        var  qty=$('#purchase_approve_qty_'+number).val();
        var  rate=$('#rate_'+number).val();
        
        var total=parseFloat(qty*rate).toFixed(3);

        $('#amount_'+number).val(total);

        var amount = 0;
        count=1;
        $('.net_amount_dis').each(function () {


            amount += +$('#after_dis_amountt_' + count).val();
            count++;
        });
        amount=parseFloat(amount);


        sales_tax('sales_taxx');
        discount_percent('discount_percent_'+number);
        toWords(1);
    }

    function get_address()
    {
        var supplier= $('#supplier_id').val();

        supplier=  supplier.split('@#');
        $('#addresss').val(supplier[1]);

        $('#ntn_id').val(supplier[2]);
        $('#model_terms_of_payment').val(supplier[3]).change();
        $('#trn').val(supplier[4]);
        
        // Set no_of_days if available (index 5)
        if(supplier[5] && supplier[5] != '') {
            $('#no_of_days').val(supplier[5]);
        }
        
        calculate_due_date();
    }


    function get_rate()
    {
        var currency_id= $('#curren').val();
        currency_id=currency_id.split(',');
        $('#currency_rate').val(currency_id[1]);
    }

</script>
<script>
    function open_sales_tax(id)
    {

        var dept_name = $('#' + id + ' :selected').text();


        if (dept_name=='Add New')
        {

            showDetailModelOneParamerter('fdc/createAccountFormAjax/sales_taxx')
        }

    }

    function discount_amount(id)
    {
        var  number= id.replace("discount_amount_","");
        var amount=parseFloat($('#amount_'+number).val());

        var discount_amount=parseFloat($('#'+id).val());

        if (discount_amount > amount)
        {
            alert('Amount Cannot Exceed by '+amount);
            $('#discount_amount_'+number).val(0);
            discount_amount=0;
        }

        if (isNaN(discount_amount))
        {

            $('#discount_amount_'+number).val(0);
            discount_amount=0;
        }

        var percent=(discount_amount / amount *100).toFixed(3);
        $('#discount_percent_'+number).val(percent);
        var amount_after_discount=amount-discount_amount;
        $('#after_dis_amountt_'+number).val(amount_after_discount);


        //  $('#net_amounttd_'+number).val(amount_after_discount);
        //   $('#net_amount_'+number).val(amount_after_discount);
        sales_tax('sales_taxx');
        toWords(1);
        //   net_amount_func();


    }

</script>

<script type="text/javascript">

    $('.select2').select2();
</script>

<script src="<?php echo e(URL::asset('assets/js/select2/js_tabindex.js')); ?>"></script>