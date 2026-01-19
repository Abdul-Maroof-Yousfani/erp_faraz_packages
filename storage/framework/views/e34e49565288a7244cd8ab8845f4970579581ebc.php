<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
?>


<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('number_formate', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('select2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <style>
        .select2-container {
            font-size: 11px;
        }
    </style>

    <?php
     $main_count=1;
    $count=1;
    $sales_tax_count=1;
    ?>
    <?php echo Form::open(array('url' => 'pad/addPurchaseVoucherThorughGrn','id'=>'cashPaymentVoucherForm'));
    $val= count($ids);
    ?>
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

    <?php $__currentLoopData = $ids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php
        $rate = 0;
        $amt = 0;
        $TotAmt = 0;
        $total_amount=0;
        $sales_tax_amount=0;
        $id=$row;
        $good_recipt_note=CommonHelper::get_goodreciptnotedata($row,0);
        $purchase_reqiest=CommonHelper::get_goodreciptnotedata($row,1);
        $currency = $purchase_reqiest->currency_rate;
        $po_no = $good_recipt_note->po_no;
        if($good_recipt_note->type==0):
            $po_date=CommonHelper::changeDateFormat($purchase_reqiest->purchase_request_date);
        else:
            $po_date='';
        endif;
        $terms_of_paym = '';
        $no_days = '';
        $bill_date=$good_recipt_note->bill_date;
        $purchase_reqiest->terms_of_paym;
        if($purchase_reqiest->terms_of_paym == 1) {
            $terms_of_paym = 'Advance';
        } elseif($purchase_reqiest->terms_of_paym == 2) {
            $terms_of_paym = 'Against Delivery';
        } elseif($purchase_reqiest->terms_of_paym == 3) {
            $terms_of_paym = 'Credit';
        }
        $supplier = CommonHelper::getSupplierDetail($good_recipt_note->supplier_id);
        $no_days = $supplier->no_of_days;
        $Date = $good_recipt_note->grn_date;
        $due_date =date('Y-m-d', strtotime($Date. ' + '.$no_days.' days'));
        ?>
        <input type="hidden" name="grn_no<?php echo e($sales_tax_count); ?>" value="<?php echo e($good_recipt_note->grn_no); ?>">
        <input type="hidden" name="grn_id<?php echo e($sales_tax_count); ?>" value="<?php echo e($good_recipt_note->id); ?>">
        <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="<?php echo e($sales_tax_count); ?>" />
        <input type="hidden" name="dept_id<?php echo e($sales_tax_count); ?>" value="<?php echo e($good_recipt_note->sub_department_id); ?>"/>
        <input type="hidden" name="p_type_id<?php echo e($sales_tax_count); ?>" value="<?php echo e($good_recipt_note->p_type); ?>"/>

        <div class="row well_N">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Purchase Voucher Forms</span>
                        </div>
                    </div>
                    <h3 style="text-align: center"><?php echo e(strtoupper($good_recipt_note->grn_no)); ?></h3>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <?php $pv_no=CommonHelper::uniqe_no_for_purcahseVoucher(date('y'),date('m')); ?>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PV No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly  type="text" class="form-control requiredField"  placeholder="" name="pv_no<?php echo $sales_tax_count ?>" id="pv_no<?php echo $sales_tax_count ?>" value="<?php echo e($pv_no); ?>" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PV Date.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input onblur="" onchange="calculate_due_date('<?php echo $sales_tax_count?>')" type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="purchase_date<?php echo $sales_tax_count ?>" id="purchase_date<?php echo $sales_tax_count ?>" value="<?php echo date('Y-m-d') ?>" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PV Day.</label>
                                            <input readonly  type="text" class="form-control"  name="pv_day<?php echo $sales_tax_count ?>" id="pv_day<?php echo $sales_tax_count ?>"  />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly  type="text" class="form-control" placeholder="Ref / Bill No" name="slip_no<?php echo $sales_tax_count ?>" id="slip_no<?php echo $sales_tax_count ?>" value="<?php echo e($good_recipt_note->supplier_invoice_no); ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Bill Date.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input     readonly   type="date" class="form-control"  name="bill_date<?php echo $sales_tax_count ?>" id="bill_date<?php echo $sales_tax_count ?>" value="<?php echo e($bill_date); ?>" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Due Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly  autofocus  value="<?php echo e($due_date); ?>" type="date" name="due_date<?php echo $sales_tax_count ?>" id="due_date<?php echo $sales_tax_count ?>" class="form-control requiredField"/>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');" class="">Supplier</a></label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly class="form-control" name="supp_id<?php echo $sales_tax_count ?>" id="supp_id<?php echo $sales_tax_count ?>" value="<?php echo e(ucwords(CommonHelper::get_supplier_name($good_recipt_note->supplier_id))); ?>">
                                            <input type="hidden" id="supplier_id<?php echo $sales_tax_count ?>" name="supplier_id<?php echo $sales_tax_count ?>" value="<?php echo e($good_recipt_note->supplier_id); ?>"/>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Mode/ Terms Of Payment<span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly onkeyup="calculate_due_date('<?php echo $sales_tax_count?>')"  type="text" class="form-control"  name="model_terms_of_payment<?php echo $sales_tax_count?>" id="model_terms_of_payment<?php echo $sales_tax_count?>" value="<?php echo $terms_of_paym?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Supplier Current Amount   <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly  type="number" class="form-control"  name="current_amount<?php echo $sales_tax_count ?>" id="current_amount<?php echo $count ?>" value="" />
                                        </div>
                                        <?php if($good_recipt_note->grn_no!=''): ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">GRN No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly  type="text" class="form-control requiredField"  name="grn_no" id="grn_no" value="<?php echo e($good_recipt_note->grn_no); ?>" />
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Description</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="description<?php echo $sales_tax_count ?>" id="description<?php echo $sales_tax_count ?>" rows="4" cols="50" style="resize:none;" class="form-control requiredField"><?php echo e($po_no.'--'.$po_date); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <div class="addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">
                                                    <table  id="" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 150px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a>
                                                            <th style="width: 100px" class="text-center">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 200px;" class="text-center">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 200px;" class="text-center">Return Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 200px;" class="text-center">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 200px;" class="text-center hide">Amount. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 200px;" class="text-center hide">Discount Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 200px;" class="text-center">Net Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $counter=1; ?>
                                                        <?php $__currentLoopData = CommonHelper::get_grndata($id,$good_recipt_note->type); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                            <?php
                                                            $return_qty = ReuseableCode::purchase_return_qty($row1->id);
                                                            $qty = $row1->purchase_recived_qty - $row1->qc_qty;
                                                            $actual_qty = $qty-$return_qty;

                                                            $rate = $row1->rate;

                                                            $amount = $actual_qty * $rate * $currency ;
                                                            $discount_percent = $row1->discount_percent;

                                                            if ($discount_percent > 0):
                                                                $discount_amount = ($amount / 100) * $discount_percent;
                                                            else:
                                                                $discount_amount = 0;
                                                            endif;

                                                            $net_amount = $amount - $discount_amount;
                                                            $TotAmt += $net_amount;
                                                            ?>


                                                            <input type="hidden" name="demandDataSection_<?php echo e($sales_tax_count); ?>[]" class="form-control requiredField" id="demandDataSection_1" value="<?php echo e($count); ?>" />
                                                            <input type="hidden" name="grn_data_id_1_<?php echo $count ?>" id="grn_data_id_1_<?php echo $count ?>" value="<?php echo e($row1->id); ?>"/>
                                                            <tr>
                                                                <td title="<?php echo e(CommonHelper::get_item_name($row1->sub_item_id)); ?>" class="text-center" style="width: 30%;">
                                                                    <input type="hidden" name="sub_item_id_1_<?php echo $count; ?>" value="<?php echo e($row1->sub_item_id); ?>"/>
                                                                    <?php
                                                                    $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
                                                                   echo CommonHelper::get_item_name($row1->sub_item_id);
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <input readonly type="text" value="<?php echo e(CommonHelper::get_uom_name($sub_ic_detail[0])); ?>" name="uom_1_1" id="uom_1_1" class="form-control" />
                                                                    <input type="hidden" name="uom_id_1_<?php echo $count ?>" id="uom_id_1_<?php echo $count ?>" value="<?php echo e($sub_ic_detail[0]); ?>" />
                                                                </td>

                                                                <td>
                                                                    <input readonly value="<?php echo e($qty); ?>"  type="number" step="0.01" name="qty_1_<?php echo $count ?>" id="qty_1_<?php echo $count ?>" class="form-control qty" />
                                                                </td>

                                                                <td>
                                                                    <input readonly value="<?php echo e($return_qty); ?>"  type="number" step="0.01" name="return_qty_1_<?php echo $count ?>" id="qty_1_<?php echo $count ?>" class="form-control qty" />
                                                                </td>

                                                                <td>
                                                                    <?php

                                                                    if($row1->po_data_id !="")
                                                                    {
                                                                        $Rate = CommonHelper::get_rate($row1->po_data_id);
                                                                        $rate = explode('.',$Rate->rate);
                                                                        $amt = $rate[0]*$row1->purchase_recived_qty;
                                                                     //   $TotAmt += $amt;
                                                                    }
                                                                    else{$rate = 0; $amt=0; $TotAmt = 0;}
                                                                    ?>
                                                                    <input readonly onkeyup="calculation_amount(this.id,'<?php echo $count ?>','<?php echo $row1->grn_no?>')" value="<?php echo $row1->rate?>" type="text" step="0.01" name="rate_1_<?php echo $count ?>" id="rate_1_<?php echo $count ?>" class="form-control requiredField rate" />
                                                                </td>
                                                                <td class="hide">
                                                                    <input type="text" name="amount<?php echo $count ?>" id="amount<?php echo $count ?>" class="form-control requiredField amount<?php echo $row1->grn_no?>" value="<?php echo $amount;?>" readonly />
                                                                </td>
                                                                <td class="hide"><input readonly class="form-control" type="text" id="discount_amount<?php echo e($count); ?>" name="discount_amount<?php echo e($count); ?>" value="<?php echo e($discount_amount); ?>"></td>

                                                                <td><input readonly class="form-control" id="net_amoun<?php echo e($count); ?>"text" name="net_amount<?php echo e($count); ?>" value="<?php echo e($amount-$discount_amount); ?>"></td>
                                                            </tr>
                                                            <?php  $count++; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="text-center">
                                                            <td class="text-center" colspan="4"></td>
                                                            <td class="text-center" colspan="1">Total</td>
                                                            <td ><input type="text" maxlength="15" class="form-control text-right" name="Totalamount" value="<?php echo $TotAmt?>" id="Totalamount<?php echo $row1->grn_no?>" readonly="" ></td>
                                                        </tr>
                                                        <tr class="text-center" style="background: gainsboro">
                                                            <td class="text-center" colspan="1"></td>
                                                            <?php
                                                            $SalesTaxId = 0;
                                                            $SalesTaxAmount = 0;
                                                            $NetTot = 0;
                                                            $SalesTaxAccId = 0;
                                                            if($purchase_reqiest->sales_tax_amount != 0)
                                                            {

                                                                $SalesTaxId = $purchase_reqiest->sales_tax;
                                                                $SalesTaxAccId = $purchase_reqiest->sales_tax_acc_id;
                                                                $SalesTaxAmount = $purchase_reqiest->sales_tax_amount;
                                                                $sales_tax_amount=($TotAmt/100)*$purchase_reqiest->sales_tax;
                                                                $NetTot = $TotAmt+$sales_tax_amount;
                                                            }else{
                                                                $SalesTaxId = 0;
                                                                $NetTot = $TotAmt+$sales_tax_amount;
                                                                $SalesTaxAmount = 0;
                                                                $SalesTaxAccId = 0;
                                                            }
                                                            ?>
                                                            <td colspan="1">Sales Taxes</td>
                                                            <td colspan="3"><select name="SalesTaxesAccId<?php echo $sales_tax_count?>" class="form-control <?php echo $SalesTaxAccId;?>" id="SalesTaxesAccId<?php echo $good_recipt_note->grn_no?>" onchange="sales_tax_calc('<?php echo $good_recipt_note->grn_no?>')">
                                                                    <option value="">Select Head</option>
                                                                    <?php $__currentLoopData = FinanceHelper::get_accounts(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option <?php if($row->id == 903): ?> selected <?php endif; ?> value="<?php echo e($row->id); ?>"><?php echo e(ucwords($row->name)); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" name="SalesTaxAmount<?php echo $sales_tax_count?>" id="SalesTaxAmount<?php echo $good_recipt_note->grn_no?>" class="form-control text-right" value="<?php echo $sales_tax_amount?>" onkeyup="sales_tax_calc('<?php echo $good_recipt_note->grn_no?>')" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td id="rupees<?php echo e($main_count); ?>" class="text-center" colspan="4"></td>
                                                            <td class="text-center" colspan="1">Net Total</td>
                                                            <td colspan="2"><input type="text" name="NetTotal" id="NetTotal<?php echo $main_count?>" class="form-control number_form" readonly value="<?php echo $NetTot?>"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php $data=ReuseableCode::get_grn_additional_exp($id); ?>

                                                    <?php if(!empty($data)): ?>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered sf-table-list">
                                                                    <thead>
                                                                    <th class="text-center">Account Head</th>
                                                                    <th class="text-center">Expense Amount</th>
                                                                    <th class="text-center"> </th>
                                                                    </thead>
                                                                    <tbody id="AppendExpense">
                                                                    <?php
                                                                    $exp_count = 0;
                                                                    foreach($data as $row):

                                                                    ?>
                                                                    <tr id='RemoveExpenseRow<?php echo $exp_count++?>'>
                                                                        <td class="text-center">
                                                                            <input class="form-control" type="text" name="account_<?php echo e($sales_tax_count); ?>[]" value="<?php echo e(CommonHelper::get_account_name($row->acc_id)); ?>">
                                                                            <input type="hidden" name="acc_id_<?php echo e($sales_tax_count); ?>[]" value="<?php echo e($row->acc_id); ?>"/>
                                                                        </td>
                                                                        <td>
                                                                            <input readonly type='number' name='expense_amount_<?php echo e($sales_tax_count); ?>[]' id='' class='form-control requiredField' value="<?php echo $row->amount?>">
                                                                        </td>

                                                                    </tr>
                                                                    <?php endforeach;?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered" style="width: 40%"> </table>
                                    <table>
                                        <tr>

                                            <td id="rupees<?php echo $sales_tax_count ?>"></td>
                                            <input type="hidden" name="rupeess<?php echo $sales_tax_count ?>" id="rupeess<?php echo $sales_tax_count ?>"/>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="demandsSection"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $sales_tax_count++;  $main_count++;?>     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <input type="hidden" id="main_count" value="<?php echo e($main_count); ?>"/>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?php echo e(Form::submit('Submit', ['class' => 'btn btn-success'])); ?>

                    <!--
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" />
                                <!-->
        </div>
    </div>
    <?php echo Form::close();?>

    <script>
        function calculate_due_date(Row)
        {

            var date = new Date($("#purchase_date"+Row).val());
            var days=parseFloat($('#model_terms_of_payment'+Row).val());
            days = days;

            if(!isNaN(date.getTime()))
            {
                date.setDate(date.getDate() + days);


                var yyyy = date.getFullYear().toString();
                var mm = (date.getMonth()+1).toString(); // getMonth() is zero-based
                var dd  = date.getDate().toString();
                var new_d= yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]);


                $("#due_date"+Row).val(new_d);
            } else
            {
                alert("Invalid Date");
            }


        }

        $( document ).ready(function() {

            $('.number_form').number(true,2);
            var main_count=  $('#main_count').val();
            for (i=1; i<main_count; i++)
            {
                toWordss(i);
            }
        })



        $(".btn-success").click(function(e){
            var rvs = new Array();
            var val;
            $("input[name='demandsSection[]']").each(function(){
                rvs.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of rvs) {
                jqueryValidationCustom();
                if(validate == 0)
                {
                    //alert(response);
                }else{
                    return false;
                }
            }

        });


        var x = 1;
        function addMoreDemandsDetailRows(id){

            var auth=dept_amount_validation();
            var auth1= sales_tax_amount_validation();
            var auth2=cost_center_amount_validation();

            if (auth == 1 && auth1 == 1 && auth2 == 1) {

                x++;
                //alert(id+' ---- '+x);
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/addMorPurchaseVoucherRow',
                    type: "GET",
                    data: {counter: x, id: id, m: m},
                    success: function (data) {

                        data = data.split('+');


                        $('.addMoreDemandsDetailRows_' + id + '').append(data[0]);
                        //    $('.dept_part').append(data[1]);
                        //   $('.sales_tax_dept_part').append(data[2]);
                        //   $('.cost_center').append(data[3]);
                        $('#category_id_1_' + x + '').select2();
                        $('#sub_item_id_1_' + x + '').select2();

                        $('#department_1_' + x + '').select2();
                        $('#accounts_1_' + x + '').select2();
                        $('#category_id_1_' + x + '').focus();
                        $('#department_' + x + '_' + 1).select2();
                        $('#cost_center_department_' + x + '_' + 1).select2();
                        $('#sales_tax_department_' + x + '_' + 1).select2();

                        $('#amounttd_'+id+'_'+x+'').number(true,2);
                        $('#sales_tax_amounttd_'+id+'_'+x+'').number(true,2);
                        $('#net_amounttd_'+id+'_'+x+'').number(true,2);

                        $('#department_amount_'+x+'_1').number(true,2);
                        $('#total_dept'+x+'').number(true,2);


                        $('#cost_center_department_amount_'+x+'_1').number(true,2);
                        $('#cost_center_total_dept'+x+'').number(true,2);

                        $('#sales_tax_department_amount_'+x+'_1').number(true,2);
                        $('#sales_tax_total_dept'+x+'').number(true,2);

                        var idd=1;
                        //   window.scrollBy(0,180);
                    }
                });
            }
        }

        function removeDemandsRows(){

            var id=1;

            if (x > 1)
            {
                //  var elem = document.getElementById('removeDemandsRows_'+id+'_'+x+'');
                //   elem.parentNode.removeChild(elem);

                $('#removeDemandsRows_'+id+'_'+x+'').remove();

                $('.removeDemandsRows_dept_'+id+'_'+x+'').remove();

                x--;
                net_amount_func();

            }


        }
        function removeDemandsSection(id){
            var elem = document.getElementById('Demands_'+id+'');
            elem.parentNode.removeChild(elem);
        }

        function subItemListLoadDepandentCategoryId(id,value) {

            //alert(id+' --- '+value);
            var arr = id.split('_');
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/subItemListLoadDepandentCategoryId',
                type: "GET",
                data: { id:id,m:m,value:value},
                success:function(data) {

                    $('#sub_item_id_'+arr[2]+'_'+arr[3]+'').html(data);
                }
            });
        }

        function calculation_amount(id,count,GrnNo)
        {
            var quantity = $("#qty_1_"+count).val();
            var rate = $("#"+id).val();
            var amount = quantity*rate;
            $("#amount"+count).val(amount);
            var discount_amount = $('#discount_amount'+count).val();

            var net_amount = amount - discount_amount;

            $('#net_amoun'+count).val(net_amount);
            var net_amount=0;
            $('.amount'+GrnNo).each(function (i, obj) {
                var id=(obj.id);
                net_amount += +$('#'+id).val();
            });
            $('#Totalamount'+GrnNo).val(net_amount);
            var net_amount = parseFloat(net_amount);
            sales_tax_calc(GrnNo)

        }

        function sales_tax_calc(GrnNo)
        {
            var SalesTaxAmount = parseFloat($('#SalesTaxAmount'+GrnNo).val());
            var NetAmount = parseFloat($('#Totalamount'+GrnNo).val());
            var AccId = $('#SalesTaxesAccId'+GrnNo).val();
            if(AccId !='')
            {$('#SalesTaxAmount'+GrnNo).prop('disabled',false);}
            else{$('#SalesTaxAmount'+GrnNo).prop('disabled',true);
                SalesTaxAmount =0;
                $('#SalesTaxAmount'+GrnNo).val(0)}


            $('#NetTotal'+GrnNo).val(parseFloat(NetAmount+SalesTaxAmount).toFixed(2));
        }


        var th = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];
        var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        function toWordss(id) {

            s = $('#NetTotal'+id+'').val();


            s = s.toString();
            s = s.replace(/[\, ]/g,'');
            if (s != parseFloat(s)) return 'not a number';
            var x = s.indexOf('.');
            if (x == -1)
                x = s.length;
            if (x > 15)
                return 'too big';
            var n = s.split('');
            var str = '';
            var sk = 0;
            for (var i=0;   i < x;  i++) {
                if ((x-i)%3==2) {
                    if (n[i] == '1') {
                        str += tn[Number(n[i+1])] + ' ';
                        i++;
                        sk=1;
                    } else if (n[i]!=0) {
                        str += tw[n[i]-2] + ' ';
                        sk=1;
                    }
                } else if (n[i]!=0) { // 0235
                    str += dg[n[i]] +' ';
                    if ((x-i)%3==0) str += 'hundred ';
                    sk=1;
                }
                if ((x-i)%3==1) {
                    if (sk)
                        str += th[(x-i-1)/3] + ' ';
                    sk=0;
                }
            }

            var currency = $('#curren :selected').text().split('-');
			var currencyText = currency[0] == 'PKR' ? 'Rupees ' : currency[0] == 'USD' ? 'Dollars ' : '';
			var currencyText2 = currency[0] == 'PKR' ? 'Paisa ' : currency[0] == 'USD' ? 'Cents ' : '';

            var decimalWords = '';
			if (x != s.length) {
				str += 'and ';
				var decimalPart = s.slice(x + 1);

				// Ensure decimal part has 2 digits
				while (decimalPart.length < 2) {
					decimalPart += '0';
				}

				// Handle decimal part
				if (decimalPart[0] == '1') {
					decimalWords = tn[Number(decimalPart)];
				} else {
					if (decimalPart[0] != '0') {
						decimalWords += tw[decimalPart[0] - 2] + ' ';
					}
					if (decimalPart[1] != '0') {
						decimalWords += dg[decimalPart[1]];
					}
				}

				decimalWords = decimalWords ? decimalWords.trim() : 'Zero';
				str += decimalWords + ' ' + currencyText2;
			} else {
				str += 'and Zero Paisa Only';
			}

            var result = currencyText + str.trim() + '';
			$('#rupees').text('Amount In Words: ' + result);
			$('#rupees' + id).text('Amount In Words: ' + result);
			$('#rupees').val(result);
			$('#rupeess' + id).val(result);

        };
    </script>


    <script type="text/javascript">
        $('.select2').select2();
    </script>

    <script src="<?php echo e(URL::asset('assets/js/select2/js_tabindex.js')); ?>"></script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>