<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

$selectedCustomer = null;
$selectedCustomerValue = '';
if (!empty($sale_tax_invoice->buyers_id)) {
    $selectedCustomer = DB::connection('mysql2')->table('customers')->where('id', $sale_tax_invoice->buyers_id)->first();
    if ($selectedCustomer) {
        $selectedCustomerValue = $selectedCustomer->id . '*' . ($selectedCustomer->cnic_ntn ?? '') . '*' . ($selectedCustomer->strn ?? '');
    }
}

$dnIds = $sale_tax_invoice_data->pluck('dn_data_ids')->filter()->first() ?? '';
$salesTaxRate = 0;
$furtherSalesTaxRate = 0;
foreach ($sale_tax_invoice_data as $taxRow) {
    $rowTax = $taxRow->tax ?? 0;
    if (is_string($rowTax) && strpos($rowTax, ',') !== false) {
        $taxParts = explode(',', $rowTax);
        $rowTax = $taxParts[1] ?? 0;
    }
    if ((float) $rowTax > 0) {
        $salesTaxRate = (float) $rowTax;
        break;
    }
}
foreach ($sale_tax_invoice_data as $taxRow) {
    if ((float) ($taxRow->sales_tax_further_per ?? 0) > 0) {
        $furtherSalesTaxRate = (float) $taxRow->sales_tax_further_per;
        break;
    }
}
?>

@extends('layouts.default')

@section('content')
    @include('loader')
    @include('number_formate')
    @include('select2')

    <style>
        * {
            font-size: 12px !important;
        }
        label {
            text-transform: capitalize;
        }
        .sales-tax-invoice-page .well {
            background: #ffffff;
            border: 1px solid #d7dde5;
            border-radius: 10px;
            padding: 18px 18px 12px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        }
        .sales-tax-invoice-page .subHeadingLabelClass {
            display: inline-block;
            font-size: 18px !important;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
        }
        .sales-tax-invoice-page .panel,
        .sales-tax-invoice-page .panel-body {
            border: 0;
            box-shadow: none;
            background: transparent;
            padding-left: 0;
            padding-right: 0;
        }
        .sales-tax-invoice-page .form-control,
        .sales-tax-invoice-page .select2-selection {
            min-height: 36px;
            border-radius: 6px !important;
            border-color: #cdd6e1;
            box-shadow: none;
            background: #f8fafc;
        }
        .sales-tax-invoice-page textarea.form-control {
            min-height: 60px;
            background: #fbfdff;
        }
        .sales-tax-invoice-page .sf-label {
            display: block;
            font-weight: 600;
            color: #526070;
            margin-bottom: 6px;
        }
        .sales-tax-invoice-page .invoice-grid > div,
        .sales-tax-invoice-page .invoice-meta-row > div,
        .sales-tax-invoice-page .invoice-desc-row > div {
            margin-bottom: 14px;
        }
        .sales-tax-invoice-page .invoice-table {
            margin-bottom: 18px;
            border: 1px solid #d9e1ea;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .sales-tax-invoice-page .invoice-table thead th {
            background: #dbe4ee;
            color: #344256;
            border-bottom: 1px solid #cbd5e1 !important;
            font-weight: 700;
            padding: 12px 10px !important;
            white-space: nowrap;
        }
        .sales-tax-invoice-page .invoice-table tbody td {
            padding: 8px 10px !important;
            vertical-align: middle !important;
            border-top: 1px solid #edf2f7 !important;
        }
        .sales-tax-invoice-page .invoice-table tbody tr:nth-child(even):not(.invoice-total-row):not(.invoice-grand-row) {
            background: #fbfdff;
        }
        .sales-tax-invoice-page .invoice-summary-label {
            font-weight: 600;
            color: #425466;
            background: #fff;
        }
        .sales-tax-invoice-page .invoice-total-row td {
            background: #b7bcc3 !important;
            color: #1f2937;
            font-weight: 700;
            border-top: 1px solid #a7afb8 !important;
        }
        .sales-tax-invoice-page .invoice-total-row input,
        .sales-tax-invoice-page .invoice-grand-row input {
            font-weight: 700;
        }
        .sales-tax-invoice-page .invoice-grand-row td {
            background: #9fa5ad !important;
            color: #111827;
            font-weight: 700;
            border-top: 1px solid #9098a3 !important;
        }
        .sales-tax-invoice-page .amount-words {
            margin-top: 12px;
            color: #5b6776;
            font-size: 11px !important;
        }
        .sales-tax-invoice-page .invoice-actions {
            margin-top: 18px;
            padding-top: 6px;
        }
        .sales-tax-invoice-page .invoice-actions .btn {
            min-width: 110px;
            border-radius: 6px;
            font-weight: 600;
            padding: 8px 16px;
        }
        .sales-tax-invoice-page .btn-success {
            background: #16a34a;
            border-color: #16a34a;
        }
        @media (max-width: 991px) {
            .sales-tax-invoice-page .well {
                padding: 14px 12px 10px;
            }
            .sales-tax-invoice-page .invoice-table {
                border-radius: 6px;
            }
        }
    </style>

    <div class="row well_N sales-tax-invoice-page" style="display: none;" id="main">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Sales Tax Invoice</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>

                <?php echo Form::open(array('url' => 'sad/updateSalesTaxInvoice?m=' . $m . '', 'id' => 'createSalesOrder'));?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ $sale_tax_invoice->id }}">
                <input type="hidden" name="sales_order_id" value="{{ $sale_tax_invoice->so_id }}">
                <input type="hidden" name="dn_ids" value="{{ $dnIds }}">
                <input type="hidden" name="gd_no" value="{{ $sale_tax_invoice->gd_no }}">
                <input type="hidden" name="model_terms_of_payment" value="{{ $sale_tax_invoice->model_terms_of_payment }}">
                <input type="hidden" name="other_refrence" value="{{ $sale_tax_invoice->other_refrence }}">
                <input type="hidden" name="order_no" value="{{ $sale_tax_invoice->order_no }}">
                <input type="hidden" name="order_date" value="{{ $sale_tax_invoice->order_date }}">
                <input type="hidden" name="despacth_document_no" value="{{ $sale_tax_invoice->despacth_document_no }}">
                <input type="hidden" name="despacth_document_date" value="{{ $sale_tax_invoice->despacth_document_date }}">
                <input type="hidden" name="despacth_through" value="{{ $sale_tax_invoice->despacth_through }}">
                <input type="hidden" name="destination" value="{{ $sale_tax_invoice->destination }}">
                <input type="hidden" name="terms_of_delivery" value="{{ $sale_tax_invoice->terms_of_delivery }}">
                <input type="hidden" name="due_date" value="{{ $sale_tax_invoice->due_date }}">
                <input type="hidden" name="acc_id" value="{{ $sale_tax_invoice->acc_id ?: 16 }}">
                <input type="hidden" name="advance_tax_rate" id="advance_tax_rate" value="{{ $sale_tax_invoice->advance_tax_rate ?? 0 }}">

                <div class="panel">
                    <div class="panel-body">
                        <div class="row invoice-grid">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Invoice No<span class="rflabelsteric"><strong>*</strong></span></label>
                                <input readonly type="text" class="form-control" name="gi_no" id="gi_no" value="{{ strtoupper($sale_tax_invoice->gi_no) }}" />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Invoice Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                <input type="date" class="form-control requiredField" name="gi_date" id="gi_date" value="{{ old('gi_date', $sale_tax_invoice->gi_date) }}" />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">SO NO.<span class="rflabelsteric"><strong>*</strong></span></label>
                                <input readonly type="text" class="form-control" name="so_no" id="so_no" value="{{ $sale_tax_invoice->so_no }}" />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">SO Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                <input readonly type="date" class="form-control" name="so_date" id="so_date" value="{{ $sale_tax_invoice->so_date }}" />
                            </div>
                        </div>

                        <div class="row invoice-meta-row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Buyer's Name <span class="rflabelsteric"><strong>*</strong></span></label>
                                <select style="width: 100%" name="buyers_id" id="ntn" class="form-control select2 requiredField">
                                    <option value="">Select</option>
                                    @foreach(SalesHelper::get_all_customer() as $row)
                                        <option @if(old('buyers_id', $selectedCustomerValue) == ($row->id . '*' . $row->cnic_ntn . '*' . $row->strn)) selected @endif value="{{ $row->id . '*' . $row->cnic_ntn . '*' . $row->strn }}">
                                            {{ $row->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Buyer's Ntn</label>
                                <input readonly type="text" class="form-control" name="buyers_ntn" id="buyers_ntn" value="{{ $selectedCustomer->cnic_ntn ?? '' }}" />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Buyer's Sales Tax No</label>
                                <input readonly type="text" class="form-control" name="buyers_sales" id="buyers_sales" value="{{ $selectedCustomer->strn ?? '' }}" />
                            </div>
                        </div>

                        <div class="row invoice-desc-row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label class="sf-label">Description</label>
                                <textarea name="description" id="description" rows="4" style="resize:none;text-transform: capitalize" class="form-control">{{ old('description', $sale_tax_invoice->description) }}</textarea>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed tableMargin invoice-table">
                                <thead>
                                <tr>
                                    <th class="text-center">S.NO</th>
                                    <th class="text-center">DN NO</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Uom</th>
                                    <th class="text-center">Orderd QTY</th>
                                    <th class="text-center">DN QTY</th>
                                    <th class="text-center">Return QTY</th>
                                    <th class="text-center">QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Net Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $rowCount = 0;
                                @endphp
                                @foreach($sale_tax_invoice_data as $index => $detail)
                                    @php
                                        $rowCount++;
                                        $orderedQtyObj = !empty($detail->so_data_id) ? CommonHelper::generic('sales_order_data', ['id' => $detail->so_data_id], ['qty'])->first() : null;
                                        $orderedQty = $orderedQtyObj->qty ?? $detail->qty;
                                        $returnQty = (!empty($detail->so_data_id) && !empty($detail->gd_no)) ? SalesHelper::return_qty(1, $detail->so_data_id, $detail->gd_no) : 0;
                                        $dnQty = ((float) $detail->qty) + ((float) $returnQty);
                                        $amount = (float) ($detail->amount ?? (((float) $detail->qty) * ((float) $detail->rate)));
                                        $itemName = trim($detail->description ?: CommonHelper::get_item_name($detail->item_id));
                                        $uomName = CommonHelper::get_uom_name($detail->uom);
                                        $rowTax = $detail->tax ?? 0;
                                        if (is_string($rowTax) && strpos($rowTax, ',') !== false) {
                                            $taxParts = explode(',', $rowTax);
                                            $rowTax = $taxParts[1] ?? 0;
                                        }
                                    @endphp
                                    <input type="hidden" name="so_data_id{{ $rowCount }}" id="so_data_id{{ $rowCount }}" value="{{ $detail->so_data_id }}">
                                    <input type="hidden" name="groupby{{ $rowCount }}" id="groupby{{ $rowCount }}" value="{{ $detail->groupby }}">
                                    <input type="hidden" name="bundles_id{{ $rowCount }}" id="bundles_id{{ $rowCount }}" value="{{ $detail->bundles_id }}">
                                    <input type="hidden" name="item_id{{ $rowCount }}" id="item_id{{ $rowCount }}" value="{{ $detail->item_id }}">
                                    <input type="hidden" name="warehouse_id{{ $rowCount }}" id="warehouse_id{{ $rowCount }}" value="{{ $detail->warehouse_id }}">
                                    <input type="hidden" name="item_desc{{ $rowCount }}" id="item_desc{{ $rowCount }}" value="{{ $itemName }}">
                                    <input type="hidden" name="tax_percent{{ $rowCount }}" id="tax_percent{{ $rowCount }}" value="{{ (float) $rowTax }}">
                                    <input type="hidden" name="tax_amount{{ $rowCount }}" id="tax_amount{{ $rowCount }}" value="{{ number_format((float) ($detail->tax_amount ?? 0), 3, '.', '') }}">
                                    <input type="hidden" name="sales_tax_further_per{{ $rowCount }}" id="sales_tax_further_per{{ $rowCount }}" value="{{ (float) ($detail->sales_tax_further_per ?? 0) }}">
                                    <input type="hidden" name="sales_tax_further{{ $rowCount }}" id="sales_tax_further{{ $rowCount }}" value="{{ number_format((float) ($detail->sales_tax_further ?? 0), 3, '.', '') }}">

                                    <tr>
                                        <td class="text-center">{{ $rowCount }}</td>
                                        <td class="text-center">{{ strtoupper($detail->gd_no) }}</td>
                                        <td>{{ $itemName }}</td>
                                        <td>{{ $uomName }}</td>
                                        <td class="text-center">{{ number_format((float) $orderedQty, 3, '.', '') }}</td>
                                        <td class="text-center">{{ number_format((float) $dnQty, 3, '.', '') }}</td>
                                        <td class="text-center">{{ number_format((float) $returnQty, 3, '.', '') }}</td>
                                        <td>
                                            <input type="text" class="form-control qty requiredField" name="qty{{ $rowCount }}" id="qty{{ $rowCount }}" value="{{ number_format((float) $detail->qty, 3, '.', '') }}" oninput="recalcRow({{ $rowCount }})" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control requiredField" name="rate{{ $rowCount }}" id="rate{{ $rowCount }}" value="{{ number_format((float) $detail->rate, 3, '.', '') }}" oninput="recalcRow({{ $rowCount }})" />
                                        </td>
                                        <td>
                                            <input readonly type="text" class="form-control amount-row" name="after_dis_amount{{ $rowCount }}" id="after_dis_amount{{ $rowCount }}" value="{{ number_format($amount, 3, '.', '') }}" />
                                        </td>
                                    </tr>
                                @endforeach

                                <input type="hidden" name="count" id="count" value="{{ $rowCount }}">

                                <tr class="invoice-total-row">
                                    <td id="total_" class="text-center" colspan="7">Total</td>
                                    <td><input readonly type="text" id="total_qty" class="form-control text-right" value="0.000" /></td>
                                    <td></td>
                                    <td><input readonly type="text" id="total_amount" class="form-control text-right" value="0.000" /></td>
                                </tr>

                                @if($salesTaxRate > 0)
                                    <tr>
                                        <td class="text-right invoice-summary-label" colspan="9">Sales Tax {{ number_format($salesTaxRate, 2) }}</td>
                                        <td><input readonly type="text" class="form-control text-right" name="sales_tax" id="sales_tax" value="{{ number_format((float) ($sale_tax_invoice->sales_tax ?? 0), 3, '.', '') }}" /></td>
                                    </tr>
                                @else
                                    <input type="hidden" name="sales_tax" id="sales_tax" value="0">
                                @endif

                                @if($furtherSalesTaxRate > 0)
                                    <tr>
                                        <td class="text-right invoice-summary-label" colspan="9">Further Sales Tax {{ number_format($furtherSalesTaxRate, 2) }}</td>
                                        <td><input readonly type="text" class="form-control text-right" name="sales_tax_further" id="sales_tax_further" value="{{ number_format((float) ($sale_tax_invoice->sales_tax_further ?? 0), 3, '.', '') }}" /></td>
                                    </tr>
                                @else
                                    <input type="hidden" name="sales_tax_further" id="sales_tax_further" value="0">
                                @endif

                                @if((float) ($sale_tax_invoice->advance_tax_amount ?? 0) > 0)
                                    <tr>
                                        <td class="text-right invoice-summary-label" colspan="9">Advance Tax {{ number_format((float) ($sale_tax_invoice->advance_tax_rate ?? 0), 2) }}</td>
                                        <td><input readonly type="text" class="form-control text-right" name="advance_tax_amount" id="advance_tax_amount" value="{{ number_format((float) $sale_tax_invoice->advance_tax_amount, 3, '.', '') }}" /></td>
                                    </tr>
                                @else
                                    <input type="hidden" name="advance_tax_amount" id="advance_tax_amount" value="0">
                                @endif

                                @if((float) ($sale_tax_invoice->cartage_amount ?? 0) > 0)
                                    <tr>
                                        <td class="text-right invoice-summary-label" colspan="9">Cartage Amount</td>
                                        <td><input readonly type="text" class="form-control text-right" name="cartage_amount" id="cartage_amount" value="{{ number_format((float) $sale_tax_invoice->cartage_amount, 3, '.', '') }}" /></td>
                                    </tr>
                                @else
                                    <input type="hidden" name="cartage_amount" id="cartage_amount" value="0">
                                @endif

                                <tr class="invoice-grand-row">
                                    <td class="text-center" colspan="9">Grand Total</td>
                                    <td><input readonly type="text" class="form-control text-right" id="grand_total" value="0.000" /></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <table class="amount-words">
                            <tr>
                                <td id="rupees" style="text-transform: capitalize;">Amount In Words : {{ $sale_tax_invoice->amount_in_words }}</td>
                                <input type="hidden" value="{{ $sale_tax_invoice->amount_in_words }}" name="rupeess" id="rupeess1" />
                            </tr>
                        </table>
                        <input type="hidden" id="d_t_amount_1" value="0">
                    </div>
                </div>

                <div class="row invoice-actions">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>

    <script>
        function parseNumber(value) {
            if (typeof value === 'undefined' || value === null) {
                return 0;
            }
            value = ('' + value).replace(/,/g, '');
            var number = parseFloat(value);
            return isNaN(number) ? 0 : number;
        }

        function syncCustomerMeta() {
            var customerValue = ($('#ntn').val() || '').toString();
            var parts = customerValue ? customerValue.split('*') : [];
            $('#buyers_ntn').val(parts[1] || '');
            $('#buyers_sales').val(parts[2] || '');
        }

        function recalcRow(rowNo) {
            var qty = parseNumber($('#qty' + rowNo).val());
            var rate = parseNumber($('#rate' + rowNo).val());
            var amount = qty * rate;
            var taxRate = parseNumber($('#tax_percent' + rowNo).val());
            var furtherRate = parseNumber($('#sales_tax_further_per' + rowNo).val());

            $('#after_dis_amount' + rowNo).val(amount.toFixed(3));
            $('#tax_amount' + rowNo).val(((amount * taxRate) / 100).toFixed(3));
            $('#sales_tax_further' + rowNo).val(((amount * furtherRate) / 100).toFixed(3));

            recalcTotals();
        }

        function recalcTotals() {
            var count = parseInt($('#count').val() || 0, 10);
            var totalQty = 0;
            var totalAmount = 0;
            var totalTax = 0;
            var totalFurtherTax = 0;

            for (var i = 1; i <= count; i++) {
                totalQty += parseNumber($('#qty' + i).val());
                totalAmount += parseNumber($('#after_dis_amount' + i).val());
                totalTax += parseNumber($('#tax_amount' + i).val());
                totalFurtherTax += parseNumber($('#sales_tax_further' + i).val());
            }

            $('#total_qty').val(totalQty.toFixed(3));
            $('#total_amount').val(totalAmount.toFixed(3));

            if ($('#sales_tax').length) {
                $('#sales_tax').val(totalTax.toFixed(3));
            }
            if ($('#sales_tax_further').length) {
                $('#sales_tax_further').val(totalFurtherTax.toFixed(3));
            }

            var advanceTax = parseNumber($('#advance_tax_amount').val());
            var cartageAmount = parseNumber($('#cartage_amount').val());
            var grandTotal = totalAmount + totalTax + totalFurtherTax + advanceTax + cartageAmount;

            $('#grand_total').val(grandTotal.toFixed(3));
            $('#d_t_amount_1').val(grandTotal.toFixed(3));

            if (typeof toWords === 'function') {
                toWords(1);
            }
        }

        $(document).ready(function () {
            $('.select2').select2();
            $('#ntn').on('change', syncCustomerMeta);
            syncCustomerMeta();

            var count = parseInt($('#count').val() || 0, 10);
            for (var i = 1; i <= count; i++) {
                recalcRow(i);
            }
            recalcTotals();
        });
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
