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
                                                                <th>Received Qty</th>
                                                                <th>Return Qty Sum Total</th>
                                                                <th>Price</th>
                                                                <th>Amount</th>
                                                                <th>Stock Qty</th>
                                                                <th>Return Qty</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php 
                                                                $Counter = 1; 
                                                            @endphp
                                                            @foreach($Detail as $Fil)
                                                                @php
                                                                    $currentInvoiceId = $Master->purchase_invoice_id ?? $Master->grn_id;
                                                                    $alreadyReturned = (float) DB::connection('mysql2')
                                                                        ->table('purchase_return_data')
                                                                        ->where('status', 1)
                                                                        ->where('purchase_invoice_id', $currentInvoiceId)
                                                                        ->where('sub_item_id', $Fil->sub_item_id)
                                                                        ->where('id', '!=', $Fil->id)
                                                                        ->sum('return_qty');

                                                                    $remainingQty = max((float)$Fil->recived_qty - $alreadyReturned, 0);
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
                                                                        <input type="hidden" name="BatchCode[]" id="BatchCode{{ $Fil->grn_data_id }}" value="{{ $Fil->batch_code }}">
                                                                    </td>

                                                                    <td class="text-center">
                                                                        {{ number_format($Fil->recived_qty, 2) }}
                                                                        <input type="hidden" name="PurchaseRecQty[]" id="purchase_recived_qty_{{ $Fil->grn_data_id }}" value="{{ $Fil->recived_qty }}">
                                                                    </td>

                                                                    <td class="text-center">{{ number_format($alreadyReturned, 2) }}</td>
                                                                    <input type="hidden" id="return_{{ $Fil->id }}" value="{{ $alreadyReturned }}">

                                                                    <td class="text-center">
                                                                        {{ number_format($Fil->rate, 2) }}
                                                                        <input type="hidden" name="Rate[]" id="rate_{{ $Fil->grn_data_id }}" value="{{ $Fil->rate }}">
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="0.01" class="form-control text-end" readonly 
                                                                               name="Amount[]" id="amount_{{ $Fil->grn_data_id }}" 
                                                                               value="{{ number_format($Fil->amount, 2, '.', '') }}">
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" class="form-control text-end" readonly 
                                                                               id="stock_qty{{ $Fil->grn_data_id }}" name="stock_qty[]" 
                                                                               value="{{ number_format($remainingQty, 2, '.', '') }}">
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="any" class="form-control text-end ReturnQty" 
                                                                               id="return_qty_{{ $Fil->grn_data_id }}" 
                                                                               name="ReturnQty[]" 
                                                                               value="{{ $Fil->return_qty ?? 0 }}" 
                                                                               onkeyup="check_val('{{ $Fil->grn_data_id }}')">
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
        $('#amount_' + Id).val((qty * rate).toFixed(2));
        calculate_return_summary();
    }

    function calculate_return_summary() {
        const taxPercent = parseFloat($('#original_sales_tax_percent').val()) || 0;
        let returnBeforeTax = 0;

        // Sum amount of all rows (since this is edit mode, all rows are active)
        $('input[name="Amount[]"]').each(function() {
            returnBeforeTax += parseFloat($(this).val()) || 0;
        });

        const returnTaxAmount = (returnBeforeTax * taxPercent) / 100;

        $('#return_before_tax').val(returnBeforeTax.toFixed(2));
        $('#return_sales_tax').val(returnTaxAmount.toFixed(2));
        $('#return_after_tax').val((returnBeforeTax + returnTaxAmount).toFixed(2));
    }

    $(document).ready(function() {
        calculate_return_summary();

        // Recalculate when any return qty changes
        $('.ReturnQty').on('keyup change', function() {
            calculate_return_summary();
        });
    });
</script>

<script src="{{ asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection