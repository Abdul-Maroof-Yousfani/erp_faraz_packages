<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$m = $_GET['m'];

// Support both GRN-based and Invoice-based calls
if (isset($_GET['GrnValue'])) {
    $makeGetValue = explode('*', $_GET['GrnValue']);
} else {
    $makeGetValue = explode('*', $_GET['PurchaseInvoiceNo'] ?? '');
}
$InvoiceId   = $makeGetValue[0] ?? 0;
$InvoiceNo   = $makeGetValue[1] ?? '';
$InvoiceDate = $makeGetValue[2] ?? '';
?>
@include('number_formate')
@include('select2')
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <?php
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`pr_no`,3,length(substr(`pr_no`,3))-4),signed integer)) reg from `purchase_return` where substr(`pr_no`,-4,2) = " . date('m') . " and substr(`pr_no`,-2,2) = " . date('y') . "")->reg;
        $PurchaseReturnNo = 'dr' . ($str + 1) . date('my');
        ?>
        <label for="">Purchase Return No</label>
        <input type="text" class="form-control" id="" value="<?php echo strtoupper($PurchaseReturnNo)?>" readonly>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label for="">Purchase Return Date</label>
        <input type="date" id="PurchaseReturnDate" name="PurchaseReturnDate" value="<?php echo date('Y-m-d')?>" class="form-control">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label for="">Purchase Invoice Date</label>
        <input type="date" id="InvoiceDate" name="InvoiceDate" value="<?php echo $InvoiceDate?>" class="form-control" readonly>
        <input type="hidden" id="PurchaseInvoiceNo"   name="PurchaseInvoiceNo"   value="<?php echo $InvoiceNo?>"   class="form-control" readonly>
        <input type="hidden" id="PurchaseInvoiceId"   name="PurchaseInvoiceId"   value="<?php echo $InvoiceId?>"   class="form-control" readonly>
        <input type="hidden" id="PurchaseInvoiceDate" name="PurchaseInvoiceDate" value="<?php echo $InvoiceDate?>" class="form-control" readonly>
        {{-- GRN aliases so controller fallback works for both flows --}}
        <input type="hidden" name="GrnId"   value="<?php echo $InvoiceId?>">
        <input type="hidden" name="GrnNo"   value="<?php echo $InvoiceNo?>">
        <input type="hidden" name="GrnDate" value="<?php echo $InvoiceDate?>">
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label for="">Remarks</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <textarea name="Remarks" id="Remarks" cols="30" rows="3" class="form-control requiredField"></textarea>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list">
                <thead>
                    <th class="text-center">Sr.No</th>
                    <th class="text-center">Item Name</th>
                    <th class="text-center">Location</th>
                    <th class="text-center hide">Batch Code</th>
                    <th class="text-center">Bags</th>
                    <th class="text-center">Qty KGs</th>
                    <th class="text-center">Qty LBS</th>
                    <th class="text-center">Return Qty Sum Total</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Rate Cal By</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Remaining KGs</th>
                    <th class="text-center">Return Qty KGs</th>
                    <th class="text-center">Enable/Disable</th>
                </thead>
                <tbody>
                <?php
                $Counter = 1;
                $Count = 0;
                foreach($DataDetail as $Fil):
                    $lineKey = $Count;
                    $grnDataId = $Fil->purchase_grn_data_id ?? 0;
                    $itemId = $Fil->sub_item ?? 0;
                    $warehouseId = $Fil->warehouse_id ?? 0;
                    $batchCode = $Fil->batch_code ?? '';
                    $purchaseQty = $Fil->purchase_recived_qty ?? 0;
                    $rate = $Fil->rate ?? 0;
                    $amount = $Fil->amount ?? 0;
                    $discountPercent = $Fil->discount_percent ?? 0;
                    $rateCalBy = (int) ($Fil->rate_cal_by ?? 2);
                    $sourceBagQty = (float) ($Fil->source_bag_qty ?? 0);
                    $sourceLbsQty = (float) ($Fil->source_lbs_qty ?? (((float) $purchaseQty) * 2.2));
                    $sourcePackSize = (float) ($Fil->source_pack_size ?? 0);
                    $expectedLbsQty = ((float) $purchaseQty) * 2.2;
                    if ($purchaseQty > 0) {
                        $lbsRatio = $sourceLbsQty / $purchaseQty;
                        if ($sourceLbsQty <= 0 || abs($lbsRatio - 2.2) > 0.05) {
                            $sourceLbsQty = $expectedLbsQty;
                        }
                    } else {
                        $sourceLbsQty = $expectedLbsQty;
                    }
                    $purchaseBagQty = $sourceBagQty > 0
                        ? $sourceBagQty
                        : ($sourcePackSize > 0 ? ($purchaseQty / $sourcePackSize) : 0);
                    $rateCalByLabel = $rateCalBy === 1 ? 'By BAGS' : ($rateCalBy === 3 ? 'By LBS' : 'By KGS');
                    $reurn = 0;
                    if (!empty($InvoiceId) && !empty($itemId)) {
                        $reurn = (float) DB::connection('mysql2')->table('purchase_return_data')
                            ->where('status', 1)
                            ->where('purchase_invoice_id', $InvoiceId)
                            ->where('sub_item_id', $itemId)
                            ->sum('return_qty');
                    }
                    $remainingQty = max(((float) $purchaseQty) - $reurn, 0);
                    $remainingRateBasisQty = $remainingQty;
                    if ($rateCalBy === 1) {
                        if ($sourcePackSize > 0) {
                            $remainingRateBasisQty = $remainingQty / $sourcePackSize;
                        } else {
                            $remainingRateBasisQty = $sourceBagQty > 0 && $purchaseQty > 0
                                ? (($remainingQty / $purchaseQty) * $sourceBagQty)
                                : $remainingQty;
                        }
                    } elseif ($rateCalBy === 3) {
                        $remainingRateBasisQty = $sourceLbsQty > 0 && $purchaseQty > 0
                            ? (($remainingQty / $purchaseQty) * $sourceLbsQty)
                            : ($remainingQty * 2.2);
                    }
                    $remainingAmount = $remainingRateBasisQty * (float) $rate;
                ?>
                <input type="hidden" name="grn_data_id[]" value="{{ $grnDataId }}"/>
                    <tr class="text-center">
                        <td><?php echo $Counter++;?></td>

                        <input type="hidden" name="GrnDataId[]" readonly value="<?php echo $grnDataId; ?>" class="form-control" />
                        <td>
                            <input type="hidden" name="SubItemId[]" readonly id="subItemId_<?php echo $lineKey; ?>" value="<?php echo $itemId; ?>" class="form-control" />
                            <textarea name="item_desc[]" readonly id="item_desc<?php echo $lineKey; ?>" class="form-control" style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;"><?php echo CommonHelper::get_item_name($itemId);?></textarea>
                        </td>
                        <td>
                            <?php echo $warehouseId ? CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$warehouseId) : ''; ?>
                            <input value="<?php echo $warehouseId?>" type="hidden" name="WarehouseId[]" id="warehouse_id_<?php echo $lineKey; ?>"/>
                        </td>
                        <td class="hide">
                            <?php echo $batchCode; ?>
                            <input type="hidden" name="BatchCode[]" id="BatchCode<?php echo $lineKey?>" value="<?php echo $batchCode; ?>">
                        </td>
                        <td class="text-center"><?php echo number_format($purchaseBagQty,2);?></td>
                        <td class="text-center"><?php echo number_format($purchaseQty,2);?>
                            <input value="<?php echo $purchaseQty?>" type="hidden" name="PurchaseRecQty[]" id="purchase_recived_qty_<?php echo $lineKey; ?>"/>
                        </td>
                        <td class="text-center"><?php echo number_format($sourceLbsQty,2);?></td>
                        <td class="text-center"><?php echo number_format($reurn,2); ?></td>
                        <input type="hidden" id="return_<?php echo $lineKey; ?>" value="<?php echo $reurn; ?>"/>

                        <td class="text-center"><?php echo number_format($rate,2);?>
                            <input value="<?php echo $rate?>" type="hidden" name="Rate[]" id="rate_<?php echo $lineKey; ?>"/>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control" value="<?php echo $rateCalByLabel; ?>" readonly>
                            <small class="text-muted" id="rate_basis_note_<?php echo $lineKey; ?>">Rate Qty: 0.00 <?php echo $rateCalBy === 1 ? 'BAGS' : ($rateCalBy === 3 ? 'LBS' : 'KGS'); ?></small>
                            <input type="hidden" name="rate_cal_by[]" id="rate_cal_by_<?php echo $lineKey; ?>" value="<?php echo $rateCalBy; ?>">
                            <input type="hidden" name="source_bag_qty[]" id="source_bag_qty_<?php echo $lineKey; ?>" value="<?php echo $sourceBagQty; ?>">
                            <input type="hidden" name="source_lbs_qty[]" id="source_lbs_qty_<?php echo $lineKey; ?>" value="<?php echo $sourceLbsQty; ?>">
                            <input type="hidden" name="source_pack_size[]" id="source_pack_size_<?php echo $lineKey; ?>" value="<?php echo $sourcePackSize; ?>">
                        </td>
                        <td class="text-center">
                            <input type="number" step="0.01" class="form-control" readonly name="Amount[]" id="amount_<?php echo $lineKey; ?>" data-base-amount="<?php echo number_format($remainingAmount,2,'.',''); ?>" value="<?php echo number_format($remainingAmount,2,'.',''); ?>"/>
                        </td>

                        <td>
                            <input type="number" class="form-control" id="stock_qty<?php echo $lineKey?>" name="stock_qty[]" value="{{ number_format($remainingQty,2,'.','') }}" readonly>
                        </td>
                        <td>
                            <input type="number" step="any" class="form-control" id="return_qty_<?php echo $lineKey?>" name="ReturnQty[]" value="0.00" readonly onkeyup="check_val('<?php echo $lineKey?>')">
                        </td>
                        <td>
                            <input type="checkbox" name="enable_disable[]" id="enable_disable_<?php echo $lineKey?>" value="<?php echo $lineKey;?>" class="form-control amount" style="height: 25px !important;" onclick="ChkUnChk('<?php echo $lineKey?>')">
                        </td>

                        <input type="hidden" name="discount_percent[]" value="<?php echo $discountPercent; ?>"/>
                    </tr>
                <?php
                    $Count++;
                endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <table class="table table-bordered sf-table-list hide" style="background:#fff;">
            <thead>
                <tr>
                    <th colspan="2" class="text-center">Purchase Invoice Summary</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Amount Before Tax</td>
                    <td>
                        <input type="text" class="form-control text-right" id="original_before_tax" value="{{ number_format($originalBeforeTaxAmount,2,'.','') }}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Sales Tax %</td>
                    <td>
                        <input type="text" class="form-control text-right" id="original_sales_tax_percent" value="{{ number_format($originalTaxPercent,2,'.','') }}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Sales Tax Amount</td>
                    <td>
                        <input type="text" class="form-control text-right" id="original_sales_tax" value="{{ number_format($originalTaxAmount,2,'.','') }}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Total Amount After Tax</td>
                    <td>
                        <input type="text" class="form-control text-right" id="original_after_tax" value="{{ number_format($originalAfterTaxAmount,2,'.','') }}" readonly>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <table class="table table-bordered sf-table-list" style="background:#fff;">
            <thead>
                <tr class="hide">
                    <th colspan="2" class="text-center">Current Return Summary</th>
                </tr>
            </thead>
                                                    <tbody>
                <tr>
                    <td>Total Return Bags</td>
                    <td>
                        <input type="text" class="form-control text-right" id="return_bag_qty" value="0.00" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Total Return KGs</td>
                    <td>
                        <input type="text" class="form-control text-right" id="return_qty_kg_total" value="0.00" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Total Return LBS</td>
                    <td>
                        <input type="text" class="form-control text-right" id="return_qty_lbs_total" value="0.00" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Return Amount Before Tax</td>
                    <td>
                        <input type="text" class="form-control text-right" id="return_before_tax" value="0.00" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Adjusted Sales Tax %</td>
                    <td>
                        <input type="text" class="form-control text-right" id="return_sales_tax_percent" value="{{ number_format($originalTaxPercent,2,'.','') }}" readonly>
                    </td>
                </tr>
                <tr class="hide">
                    <td>Adjusted Sales Tax Amount</td>
                    <td>
                        <input type="text" class="form-control text-right" id="return_sales_tax" value="0.00" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Total Return Amount After Tax</td>
                    <td>
                        <input type="text" class="form-control text-right" id="return_after_tax" value="0.00" readonly>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script !src="">

    $('.ReturnQty').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    function check_val(Id)
    {
        var stock_qty=parseFloat($('#stock_qty'+Id).val());
        var ReturnQty = parseFloat($('#return_qty_'+Id).val());

        var ActualQty = parseFloat($('#purchase_recived_qty_'+Id).val());
        var returnn = parseFloat($('#return_'+Id).val());

        ActualQty=ActualQty - returnn;

        update_line_amount(Id);

        if(ReturnQty > ActualQty)
        {
            alert('Please Correct Your Return Qty....!');
            $('#return_qty_'+Id).val('');
            $('#amount_'+Id).val('0.00');
            calculate_return_summary();
        }
        else
        {
           if (ReturnQty >stock_qty)
           {
               alert('Please Correct Your Return Qty....!');
               $('#return_qty_'+Id).val('');
               $('#amount_'+Id).val('0.00');
               calculate_return_summary();
           }
        }
    }

    function update_line_amount(Id)
    {
        var qty = parseFloat($('#return_qty_' + Id).val());
        var rate = parseFloat($('#rate_' + Id).val());
        var quantityMeta = get_quantity_meta(Id, qty);
        var rateBasisQty = quantityMeta.rateBasisQty;

        $('#amount_' + Id).val((rateBasisQty * rate).toFixed(2));
        $('#rate_basis_note_' + Id).text('Rate Qty: ' + rateBasisQty.toFixed(2) + ' ' + quantityMeta.rateBasisLabel);
        calculate_return_summary();
    }

    function get_quantity_meta(Id, qty) {
        var purchaseQty = parseFloat($('#purchase_recived_qty_' + Id).val());
        var rateCalBy = parseInt($('#rate_cal_by_' + Id).val() || 2);
        var sourceBagQty = parseFloat($('#source_bag_qty_' + Id).val());
        var sourceLbsQty = parseFloat($('#source_lbs_qty_' + Id).val());
        var sourcePackSize = parseFloat($('#source_pack_size_' + Id).val());

        if (isNaN(qty)) {
            qty = 0;
        }
        if (isNaN(purchaseQty) || purchaseQty <= 0) {
            purchaseQty = qty;
        }
        if (isNaN(sourceBagQty)) {
            sourceBagQty = 0;
        }
        if (isNaN(sourceLbsQty)) {
            sourceLbsQty = qty * 2.2;
        }
        if (isNaN(sourcePackSize)) {
            sourcePackSize = 0;
        }

        var bagQty = 0;
        if (sourcePackSize > 0) {
            bagQty = qty / sourcePackSize;
        } else if (sourceBagQty > 0 && purchaseQty > 0) {
            bagQty = (qty / purchaseQty) * sourceBagQty;
        }

        var lbsQty = sourceLbsQty > 0 && purchaseQty > 0
            ? ((qty / purchaseQty) * sourceLbsQty)
            : (qty * 2.2);

        var rateBasisQty = qty;
        var rateBasisLabel = 'KGS';

        if (rateCalBy === 1) {
            rateBasisQty = bagQty;
            rateBasisLabel = 'BAGS';
        } else if (rateCalBy === 3) {
            rateBasisQty = lbsQty;
            rateBasisLabel = 'LBS';
        }

        return {
            bagQty: bagQty,
            qtyKg: qty,
            qtyLbs: lbsQty,
            rateBasisQty: rateBasisQty,
            rateBasisLabel: rateBasisLabel
        };
    }

    function ChkUnChk(Id) {
        if ($('#enable_disable_' + Id).prop("checked") == true) {
            $('#return_qty_' + Id).prop('readonly', false).addClass('requiredField');
            $('#return_qty_' + Id).val('0.00');
            $('#amount_' + Id).val('0.00');
        } else {
            $('#return_qty_' + Id).prop('readonly', true).removeClass('requiredField');
            $('#return_qty_' + Id).val('0.00');
            $('#amount_' + Id).val('0.00');
        }
        update_line_amount(Id);
        calculate_return_summary();
    }

    function calculate_return_summary()
    {
        var originalSalesTaxPercent = parseFloat($('#original_sales_tax_percent').val()) || 0;
        var returnBeforeTax = 0;
        var returnBagQty = 0;
        var returnQtyKgTotal = 0;
        var returnQtyLbsTotal = 0;

        $('input[name="enable_disable[]"]:checked').each(function () {
            var rowId = $(this).val();
            returnBeforeTax += parseFloat($('#amount_' + rowId).val()) || 0;
            var quantityMeta = get_quantity_meta(rowId, parseFloat($('#return_qty_' + rowId).val()) || 0);
            returnBagQty += quantityMeta.bagQty;
            returnQtyKgTotal += quantityMeta.qtyKg;
            returnQtyLbsTotal += quantityMeta.qtyLbs;
        });

        var returnSalesTax = (returnBeforeTax * originalSalesTaxPercent) / 100;

        $('#return_bag_qty').val(returnBagQty.toFixed(2));
        $('#return_qty_kg_total').val(returnQtyKgTotal.toFixed(2));
        $('#return_qty_lbs_total').val(returnQtyLbsTotal.toFixed(2));
        $('#return_before_tax').val(returnBeforeTax.toFixed(2));
        $('#return_sales_tax_percent').val(originalSalesTaxPercent.toFixed(2));
        $('#return_sales_tax').val(returnSalesTax.toFixed(2));
        $('#return_after_tax').val((returnBeforeTax + returnSalesTax).toFixed(2));
    }

    function validateForm(event) {
        if ($('input[type="checkbox"]:checked').length === 0) {
            alert('Please select at least one checkbox before submitting the form.');
            event.preventDefault();
            return false;
        }
        return true;
    }

    $(document).ready(function () {
        $('#addPurchaseReturnDetail').on('submit', validateForm);
        $('input[name="rate_cal_by[]"]').each(function () {
            var rowId = $(this).attr('id').replace('rate_cal_by_', '');
            update_line_amount(rowId);
        });
        calculate_return_summary();
    });

</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
