<?php

$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
?>


<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('select2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('number_formate', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <style>
        .select2 {
            width: 100%;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">    
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Quotation Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <?php echo e(Form::open(array('url' => url('quotation/insert_quotation').'?m='.$m, 'id' => 'quotationForm', 'class' => 'stop'))); ?>

                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                            <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Quotation NO. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="pr_no" id="pr_no" value="<?php echo e(strtoupper($voucher_no)); ?>" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Quotation Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="demand_date_1" id="demand_date_1" value="<?php echo date('Y-m-d') ?>" />
                                            </div>
                                            <input type="hidden" name="pr_id" value="<?php echo e($id); ?>"/>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Ref No.</label>
                                                <input autofocus type="text" class="form-control" placeholder="Ref  No" name="ref_no" id="slip_no_1" value="" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Supplier <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select required class="form-control select2 requiredField" name="supplier" id="supplier">
                                                    <option value="">Select</option>
                                                <?php $__currentLoopData = CommonHelper::get_all_supplier(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($row->id); ?>"><?php echo e($row->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="demand_type" id="demand_type">

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')" class="">Currency</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="getrate();" name="currency_id" id="currency_id" class="form-control select2 requiredField">
                                                    <option value="">Select Currency</option>
                                                    <?php $__currentLoopData = CommonHelper::get_all_currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option <?php if($row->id == 3): ?> selected <?php endif; ?> data-value="<?php echo e($row->rate); ?>" value="<?php echo e($row->id.','.$row->rate); ?>"><?php echo e($row->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> Currency Rate</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input class="form-control requiredField" value="1.0" type="text" name="currency_rate" id="currency_rate" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <textarea name="description_1" id="description_1" rows="4" cols="50" style="resize:none;" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive" id="">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">Check</th>
                                                            <th class="text-center">SR NO</th>
                                                            <th class="text-center">Item</th>
                                                            <th style="width: 100px" class="text-center" >UOM<span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="" class="text-center" >QTY<span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="" class="text-center">Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="" class="text-center">Amount</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody id="AppnedHtml">
                                                        <?php $count=1; ?>
                                                        <?php $__currentLoopData = $request_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr class="text-center">
                                                                <td>
                                                                    <input type="checkbox" name="checked_rows[]" value="<?php echo e($key); ?>" id="check<?php echo e($key); ?>">
                                                                </td>
                                                                <td><?php echo e($count++); ?></td>
                                                                <td><?php echo e(CommonHelper::get_item_name($row->sub_item_id)); ?></td>
                                                                <td><?php echo e(CommonHelper::get_uom($row->sub_item_id)); ?></td>
                                                                <td><?php echo e($row->qty); ?></td>
                                                                <td><input onkeyup="calcu('<?php echo e($key); ?>','<?php echo e($row->qty); ?>')" onblur="calcu('<?php echo e($key); ?>','<?php echo e($row->qty); ?>')" class="form-control zerovalidate requiredField" step="0.001" type="number" name="rate[]" id="rate<?php echo e($key); ?>"/> </td>
                                                                <td><input readonly  class="form-control zerovalidate requiredField amount" step="0.01" type="number" name="amount[]" id="amount<?php echo e($key); ?>"/> </td>
                                                                <input type="hidden" name="pr_data_id[]" value="<?php echo e($row->id); ?>"/>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                        </tbody>

                                                        <tbody>
                                                        <tr  style="font-size:large;font-weight: bold">
                                                            <td class="text-center" colspan="6">Total</td>
                                                            <td id="" class="text-right" colspan="1"><input readonly class="form-control" type="text" id="net"/> </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: right;">
                                                            <table class="table table-bordered sf-table-list">
                                                                <thead>
                                                                <th class="text-center" colspan="3">Sales Tax Account Head</th>
                                                                <th class="text-center" colspan="3">Sales Tax Amount</th>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <input type="hidden" name="sales_amount" id="sales_tax_amount"/>

                                                                        <input type="hidden" name="gst_rate" id="gst_rate">
                                                                        <select style="width:100%" onchange="sales_tax(this.id)" class="form-control select2" id="sales_taxx" name="sales_taxx">
                                                                            <option value="0">Select Sales Tax</option>
                                                                        
                                                                            <?php $__currentLoopData = ReuseableCode::get_all_sales_tax(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option data-sale-tax="<?php echo e($row->rate); ?>" value="<?php echo e($row->id); ?>"><?php echo e($row->percent); ?></option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                    </td colspan="3">
                                                                    <td class="text-right" >
                                                                        <input readonly onkeyup="tax_by_amount(this.id)" type="text" class="form-control" name="sales_amount_td" id="sales_amount_td"/>
                                                                    </td>
                                                                </tr>

                                                                </tbody>
                            
                                                                <tbody>
                                                                <tr  style="font-size:large;font-weight: bold">
                                                                    <td class="text-center" colspan="3">Total Amount After Tax</td>
                                                                    <td id="" class="text-right" colspan="3"><input readonly class="form-control" type="text" id="net_after_tax"/> </td>
                                                                </tr>
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
                            <div class="demandsSection"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <?php echo e(Form::submit('Submit', ['class' => 'btn btn-success'])); ?>


                                </div>
                            </div>
                            <?php echo Form::close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <script type="text/javascript">

        $('.select2').select2();
        $('#net').number(true,3);
        $('#net_after_tax').number(true,2);
        $('#sales_amount_td').number(true,3);

        function getrate() {
            var selectedOption = document.querySelector('#currency_id option:checked');
            var rate = selectedOption.getAttribute('data-value');
            document.getElementById('currency_rate').value = rate ? rate : '1.0';
        }

        $(document).ready(function () {
    // Initially disable all inputs
    $('input[type="checkbox"][name="checked_rows[]"]').each(function () {
        const key = $(this).val();
        toggleInputs(key, false);
    });

    // Handle checkbox toggle
    $('input[type="checkbox"][name="checked_rows[]"]').on('change', function () {
        const key = $(this).val();
        toggleInputs(key, $(this).is(':checked'));
    });

    // Form submission logic
    $('#quotationForm').on('submit', function (e) {
        const isAnyChecked = $('input[type="checkbox"][name="checked_rows[]"]:checked').length > 0;

        if (!isAnyChecked) {
            e.preventDefault();
            alert("Please select at least one row before submitting the form.");
            return false;
        }
    });

    // Function to toggle related inputs
    function toggleInputs(key, isEnabled) {
        const rateInput = $(`#rate${parseInt(key)}`);
        const amountInput = $(`#amount${parseInt(key)}`);

        if (rateInput.length) {
            rateInput.prop('disabled', !isEnabled);
            isEnabled ? rateInput.addClass('requiredField') : rateInput.removeClass('requiredField');
        }

        if (amountInput.length) {
            amountInput.prop('disabled', !isEnabled);
            isEnabled ? amountInput.addClass('requiredField') : amountInput.removeClass('requiredField');
        }
    }
});


    function calcu(count,qty)
    {

        var qty = parseFloat(qty);
        var rate = parseFloat($('#rate'+count).val());
        var total = ( qty * rate ).toFixed(3);
        $('#amount'+count).val(total);
        sales_tax();
        total_amount();
    }

    function sales_tax(id)
        {
            var sales_tax = 0;
            var sales_tax_per_value = $('#sales_taxx').find('option:selected').data('sale-tax');       
            $('#gst_rate').val(sales_tax_per_value);
            if (sales_tax_per_value!='0')
            {
              var net = $('#net').val();
              var sales_tax = (parseFloat(net) / 100 * parseFloat(sales_tax_per_value)).toFixed(3);

            }

            $('#sales_amount_td').val(sales_tax);

            total_amount();
        }

        function total_amount()
        {
            var amount = 0;
                $('.amount').each(function () {

                  amount+=+$(this).val();
            
                });
            $('#net').val(amount);     
           var sales_tax=parseFloat($('#sales_amount_td').val()); 
            $('#net_after_tax').val(amount+sales_tax);      

        }

    </script>
    <script src="<?php echo e(URL::asset('assets/js/select2/js_tabindex.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>