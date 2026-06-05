@php
    use App\Helpers\CommonHelper;
    use Illuminate\Support\Facades\DB;

    // Fix: Get 'm' from request safely
    $m = request()->get('m') ?? $_GET['m'] ?? '';
@endphp

@extends('layouts.default')

@section('content')
@include('number_formate')
@include('select2')

<div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12">
                                    <span class="subHeadingLabelClass">Edit Purchase Return Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            {!! Form::open(['url' => 'pad/updatePurchaseReturnDetail?m='.$m, 'id' => 'addPurchaseReturnDetail']) !!}
             <!-- Header Fields -->
                                        <div class="row mt-4">
                                            <div class="col-lg-4 col-md-4">
                                                <label>Purchase Return No</label>
                                                <input name="PrNo" type="text" class="form-control" 
                                                       value="{{ strtoupper($Master->pr_no) }}" readonly>
                                            </div>

                                            <div class="col-lg-4 col-md-4">
                                                <label>Purchase Return Date</label>
                                                <input type="date" id="PurchaseReturnDate" name="PurchaseReturnDate" 
                                                       value="{{ $Master->pr_date }}" class="form-control">
                                            </div>

                                            <div class="col-lg-4 col-md-4">
                                                <label>Purchase Invoice Date</label>
                                                <input type="hidden" name="EditId" value="{{ $Master->id }}">
                                                <input type="hidden" name="supplier" value="{{ $Master->supplier_id }}">

                                                <input type="date" id="InvoiceDate" name="InvoiceDate" 
                                                       value="{{ $PurchaseInvoiceData->pv_date ?? $Master->grn_date ?? '' }}" 
                                                       class="form-control" readonly>

                                                <input type="hidden" id="PurchaseInvoiceNo" name="PurchaseInvoiceNo" 
                                                       value="{{ $PurchaseInvoiceData->pv_no ?? $Master->grn_no ?? '' }}">
                                                <input type="hidden" id="PurchaseInvoiceId" name="PurchaseInvoiceId" 
                                                       value="{{ $PurchaseInvoiceData->id ?? $Master->grn_id }}">
                                            </div>

                                            <div class="col-lg-12 mt-3">
                                                <label>Remarks <span class="text-danger">*</span></label>
                                                <textarea name="Remarks" id="Remarks" rows="3" 
                                                    class="form-control requiredField">{{ $Master->remarks }}</textarea>
                                            </div>
                                        </div>

                            <div class="row">
                                <div class="panel">
                                    <div class="panel-body">

                                        <!-- Table -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th>Sr.No</th>
                                                                <th>Item Name</th>
                                                                <th>Location</th>
                                                                <th class="hide">Batch Code</th>
                                                                <th>Bags</th>
                                                                <th>Qty KGs</th>
                                                                <th>Qty LBS</th>
                                                                <th>Return Qty Sum Total</th>
                                                                <th>Price</th>
                                                                <th>Rate Cal By</th>
                                                                <th>Amount</th>
                                                                <th>Remaining KGs</th>
                                                                <th>Return Qty KGs</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php 
                                                                $Counter = 1; 
                                                            @endphp
                                                            @foreach($Detail as $Fil)
                                                                @php
                                                                    $lineKey = $loop->index;
                                                                    $currentInvoiceId = $Master->purchase_invoice_id ?? $Master->grn_id;
                                                                    $alreadyReturned = (float) DB::connection('mysql2')
                                                                        ->table('purchase_return_data')
                                                                        ->where('status', 1)
                                                                        ->where('purchase_invoice_id', $currentInvoiceId)
                                                                        ->where('sub_item_id', $Fil->sub_item_id)
                                                                        ->where('id', '!=', $Fil->id)
                                                                        ->sum('return_qty');

                                                                    $remainingQty = max((float)$Fil->recived_qty - $alreadyReturned, 0);
                                                                    $purchaseBagQty = (float) ($Fil->source_bag_qty ?? 0);
                                                                    if ($purchaseBagQty <= 0 && (float) ($Fil->source_pack_size ?? 0) > 0) {
                                                                        $purchaseBagQty = (float) $Fil->recived_qty / (float) $Fil->source_pack_size;
                                                                    }
                                                                    $purchaseLbsQty = (float) ($Fil->source_lbs_qty ?? ((float) $Fil->recived_qty * 2.2));
                                                                    $expectedLbsQty = (float) $Fil->recived_qty * 2.2;
                                                                    if ((float) $Fil->recived_qty > 0) {
                                                                        $lbsRatio = $purchaseLbsQty / (float) $Fil->recived_qty;
                                                                        if ($purchaseLbsQty <= 0 || abs($lbsRatio - 2.2) > 0.05) {
                                                                            $purchaseLbsQty = $expectedLbsQty;
                                                                        }
                                                                    } else {
                                                                        $purchaseLbsQty = $expectedLbsQty;
                                                                    }
                                                                    $rateCalBy = (int) ($Fil->rate_cal_by ?? 2);
                                                                    $rateCalByLabel = $rateCalBy === 1 ? 'By BAGS' : ($rateCalBy === 3 ? 'By LBS' : 'By KGS');
                                                                @endphp

                                                                <tr class="text-center">
                                                                    <td>{{ $Counter++ }}</td>

                                                                    <input type="hidden" name="grn_data_id[]" value="{{ $Fil->grn_data_id }}">
                                                                    <input type="hidden" name="GrnDataId[]" value="{{ $Fil->grn_data_id }}">

                                                                    <td>
                                                                        <input type="hidden" name="SubItemId[]" id="subItemId_{{ $Fil->grn_data_id }}" value="{{ $Fil->sub_item_id }}">
                                                                        <textarea name="item_desc[]" readonly id="item_desc{{ $Fil->grn_data_id }}" 
                                                                            class="form-control" style="resize:none; height:90px;">
                                                                            {{ $Fil->description }}
                                                                        </textarea>
                                                                    </td>

                                                                    <td>
                                                                        {{ CommonHelper::getCompanyDatabaseTableValueById($m, 'warehouse', 'name', $Fil->warehouse_id) }}
                                                                        <input type="hidden" name="WarehouseId[]" id="warehouse_id_{{ $Fil->grn_data_id }}" value="{{ $Fil->warehouse_id }}">
                                                                    </td>

                                                                    <td class="hide">
                                                                        {{ $Fil->batch_code }}
                                                                        <input type="hidden" name="BatchCode[]" id="BatchCode{{ $lineKey }}" value="{{ $Fil->batch_code }}">
                                                                    </td>

                                                                    <td class="text-center">
                                                                        {{ number_format($purchaseBagQty, 2) }}
                                                                    </td>

                                                                    <td class="text-center">
                                                                        {{ number_format($Fil->recived_qty, 2) }}
                                                                        <input type="hidden" name="PurchaseRecQty[]" id="purchase_recived_qty_{{ $lineKey }}" value="{{ $Fil->recived_qty }}">
                                                                    </td>

                                                                    <td class="text-center">{{ number_format($purchaseLbsQty, 2) }}</td>

                                                                    <td class="text-center">{{ number_format($alreadyReturned, 2) }}</td>
                                                                    <input type="hidden" id="return_{{ $lineKey }}" value="{{ $alreadyReturned }}">

                                                                    <td class="text-center">
                                                                        {{ number_format($Fil->rate, 2) }}
                                                                        <input type="hidden" name="Rate[]" id="rate_{{ $lineKey }}" value="{{ $Fil->rate }}">
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <input type="text" class="form-control text-end" value="{{ $rateCalByLabel }}" readonly>
                                                                        <small class="text-muted" id="rate_basis_note_{{ $lineKey }}">Rate Qty: 0.00 {{ $rateCalBy === 1 ? 'BAGS' : ($rateCalBy === 3 ? 'LBS' : 'KGS') }}</small>
                                                                        <input type="hidden" name="rate_cal_by[]" id="rate_cal_by_{{ $lineKey }}" value="{{ $rateCalBy }}">
                                                                        <input type="hidden" name="source_bag_qty[]" id="source_bag_qty_{{ $lineKey }}" value="{{ number_format($purchaseBagQty, 2, '.', '') }}">
                                                                        <input type="hidden" name="source_lbs_qty[]" id="source_lbs_qty_{{ $lineKey }}" value="{{ number_format($purchaseLbsQty, 2, '.', '') }}">
                                                                        <input type="hidden" name="source_pack_size[]" id="source_pack_size_{{ $lineKey }}" value="{{ number_format((float) ($Fil->source_pack_size ?? 0), 2, '.', '') }}">
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="0.01" class="form-control text-end" readonly 
                                                                               name="Amount[]" id="amount_{{ $lineKey }}" 
                                                                               value="{{ number_format($Fil->amount, 2, '.', '') }}">
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" class="form-control text-end" readonly 
                                                                               id="stock_qty{{ $lineKey }}" name="stock_qty[]" 
                                                                               value="{{ number_format($remainingQty, 2, '.', '') }}">
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="any" class="form-control text-end ReturnQty" 
                                                                               id="return_qty_{{ $lineKey }}" 
                                                                               name="ReturnQty[]" 
                                                                               value="{{ $Fil->return_qty ?? 0 }}" 
                                                                               onkeyup="check_val('{{ $lineKey }}')">
                                                                        <input type="hidden" name="LoopVal[]" value="{{ $loop->index }}">
                                                                    </td>

                                                                    <input type="hidden" name="discount_percent[]" value="{{ $Fil->discount_percent }}">
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                           
                                        <!-- Tax Summary Section -->
                                        <div class="row mt-4">
                                            <div class="col-lg-6">
                                                <table class="table table-bordered sf-table-list hide">
                                                    <thead>
                                                        <tr><th colspan="2" class="text-center">Purchase Invoice Summary</th></tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Total Amount Before Tax</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="original_before_tax" 
                                                                       value="{{ number_format($originalBeforeTaxAmount ?? 0, 2, '.', '') }}" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sales Tax %</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="original_sales_tax_percent" 
                                                                       value="{{ number_format($originalTaxPercent ?? 0, 2, '.', '') }}" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sales Tax Amount</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="original_sales_tax" 
                                                                       value="{{ number_format($originalTaxAmount ?? 0, 2, '.', '') }}" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Amount After Tax</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="original_after_tax" 
                                                                       value="{{ number_format($originalAfterTaxAmount ?? 0, 2, '.', '') }}" readonly>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6">
                                                <table class="table table-bordered sf-table-list" style="background:#fff;">
                                                    <thead>
                                                        <tr class="hide"><th colspan="2" class="text-center">Current Return Summary</th></tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Total Return Bags</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="return_bag_qty" value="0.00" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Return KGs</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="return_qty_kg_total" value="0.00" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Return LBS</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="return_qty_lbs_total" value="0.00" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Return Amount Before Tax</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="return_before_tax" value="0.00" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Adjusted Sales Tax %</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="return_sales_tax_percent" 
                                                                       value="{{ number_format($originalTaxPercent ?? 0, 2, '.', '') }}" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr class="hide">
                                                            <td>Adjusted Sales Tax Amount</td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="return_sales_tax" value="0.00" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Total Return Amount After Tax</strong></td>
                                                            <td class="text-end">
                                                                <input type="text" class="form-control text-end" id="return_after_tax" value="0.00" readonly>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-12 text-right">
                                    {!! Form::submit('Update Return', ['class' => 'btn btn-success', 'id' => 'BtnSubmit']) !!}
                                    <button type="reset" class="btn btn-primary">Reset</button>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function get_quantity_meta(Id, qty) {
        const purchaseQty = parseFloat($('#purchase_recived_qty_' + Id).val()) || 0;
        const rateCalBy = parseInt($('#rate_cal_by_' + Id).val() || 2);
        const sourceBagQty = parseFloat($('#source_bag_qty_' + Id).val()) || 0;
        const sourceLbsQty = parseFloat($('#source_lbs_qty_' + Id).val()) || ((parseFloat(qty) || 0) * 2.2);
        const sourcePackSize = parseFloat($('#source_pack_size_' + Id).val()) || 0;
        const qtyKg = parseFloat(qty) || 0;

        let bagQty = 0;
        if (sourcePackSize > 0) {
            bagQty = qtyKg / sourcePackSize;
        } else if (sourceBagQty > 0 && purchaseQty > 0) {
            bagQty = (qtyKg / purchaseQty) * sourceBagQty;
        }

        const lbsQty = sourceLbsQty > 0 && purchaseQty > 0
            ? ((qtyKg / purchaseQty) * sourceLbsQty)
            : (qtyKg * 2.2);

        let rateBasisQty = qtyKg;
        let rateBasisLabel = 'KGS';

        if (rateCalBy === 1) {
            rateBasisQty = bagQty;
            rateBasisLabel = 'BAGS';
        } else if (rateCalBy === 3) {
            rateBasisQty = lbsQty;
            rateBasisLabel = 'LBS';
        }

        return {
            bagQty: bagQty,
            qtyKg: qtyKg,
            qtyLbs: lbsQty,
            rateBasisQty: rateBasisQty,
            rateBasisLabel: rateBasisLabel
        };
    }

    function check_val(Id) {
        const stockQty     = parseFloat($('#stock_qty' + Id).val()) || 0;
        const returnQty    = parseFloat($('#return_qty_' + Id).val()) || 0;
        const actualQty    = parseFloat($('#purchase_recived_qty_' + Id).val()) || 0;
        const alreadyRet   = parseFloat($('#return_' + Id).val()) || 0;

        const maxAllowed = actualQty - alreadyRet;

        update_line_amount(Id);

        if (returnQty > maxAllowed || returnQty > stockQty) {
            alert('Return quantity cannot exceed remaining stock!');
            $('#return_qty_' + Id).val(0);
            $('#amount_' + Id).val('0.00');
            calculate_return_summary();
        }
    }

    function update_line_amount(Id) {
        const qty  = parseFloat($('#return_qty_' + Id).val()) || 0;
        const rate = parseFloat($('#rate_' + Id).val()) || 0;
        const quantityMeta = get_quantity_meta(Id, qty);

        $('#amount_' + Id).val((quantityMeta.rateBasisQty * rate).toFixed(2));
        $('#rate_basis_note_' + Id).text('Rate Qty: ' + quantityMeta.rateBasisQty.toFixed(2) + ' ' + quantityMeta.rateBasisLabel);
        calculate_return_summary();
    }

    function calculate_return_summary() {
        const taxPercent = parseFloat($('#original_sales_tax_percent').val()) || 0;
        let returnBeforeTax = 0;
        let returnBagQty = 0;
        let returnQtyKgTotal = 0;
        let returnQtyLbsTotal = 0;

        $('input[name="Amount[]"]').each(function() {
            returnBeforeTax += parseFloat($(this).val()) || 0;
        });

        $('input[name="ReturnQty[]"]').each(function(index) {
            const quantityMeta = get_quantity_meta(index, parseFloat($(this).val()) || 0);
            returnBagQty += quantityMeta.bagQty;
            returnQtyKgTotal += quantityMeta.qtyKg;
            returnQtyLbsTotal += quantityMeta.qtyLbs;
        });

        const returnTaxAmount = (returnBeforeTax * taxPercent) / 100;

        $('#return_bag_qty').val(returnBagQty.toFixed(2));
        $('#return_qty_kg_total').val(returnQtyKgTotal.toFixed(2));
        $('#return_qty_lbs_total').val(returnQtyLbsTotal.toFixed(2));
        $('#return_before_tax').val(returnBeforeTax.toFixed(2));
        $('#return_sales_tax_percent').val(taxPercent.toFixed(2));
        $('#return_sales_tax').val(returnTaxAmount.toFixed(2));
        $('#return_after_tax').val((returnBeforeTax + returnTaxAmount).toFixed(2));
    }

    $(document).ready(function() {
        $('input[name="ReturnQty[]"]').each(function(index) {
            update_line_amount(index);
        });

        calculate_return_summary();

        $('.ReturnQty').on('keyup change', function() {
            calculate_return_summary();
        });
    });
</script>

<script src="{{ asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
