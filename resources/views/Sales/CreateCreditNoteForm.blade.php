<?php
$accType = Auth::user()->acc_type;
$m = ($accType == 'client') ? $_GET['m'] : Auth::user()->company_id;

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

$cr_no = SalesHelper::generateCreditNotNo(date('y'), date('m'));
$lineCounter = 1;
?>
@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Create Credit Note</span>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-body">
                                <?php echo Form::open(array('url' => 'sad/addCreditNote?m='.$m.'','id'=>'creditNoteForm'));?>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label>Credit Note No</label>
                                        <input type="text" class="form-control" name="credit_not_no" value="{{ $cr_no }}" readonly>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label>Credit Note Date</label>
                                        <input type="date" class="form-control" name="credit_date" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label>Customer</label>
                                        <input type="text" class="form-control" value="{{ $customer->name ?? '' }}" disabled>
                                        <input type="hidden" name="byer_id" value="{{ $customer->id ?? 0 }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label>Invoice No</label>
                                        <input type="text" class="form-control" value="{{ $invoice->gi_no ?? '' }}" disabled>
                                    </div>
                                </div>

                                <div class="row" style="margin-top:10px;">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label>Invoice Date</label>
                                        <input type="text" class="form-control" value="{{ !empty($invoice->gi_date) ? CommonHelper::changeDateFormat($invoice->gi_date) : '' }}" disabled>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label>Type</label>
                                        <input type="text" class="form-control" value="{{ ((int)$type === 1) ? 'Delivery Note' : 'Sales Tax Invoice' }}" disabled>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label>Description</label>
                                        <input type="text" class="form-control" name="description_1" placeholder="Optional description">
                                    </div>
                                </div>

                                <input type="hidden" name="type" value="{{ (int)$type }}">
                                <input type="hidden" name="so_id" value="{{ $invoice->so_id ?? 0 }}">

                                <div class="row" style="margin-top:15px;">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Item</th>
                                                        <th class="text-center">Invoice Qty</th>
                                                        <th class="text-center">Purchase Rate</th>
                                                        <th class="text-center">Item Amount</th>
                                                        <th class="text-center">Discount %</th>
                                                        <th class="text-center">Deduction</th>
                                                        <th class="text-center">Net Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(($invoiceLines ?? []) as $line)
                                                    <?php
                                                        $subItemDetail = CommonHelper::get_subitem_detail($line->item_id ?? 0);
                                                        $subItem = explode(',', (string)$subItemDetail);
                                                        $itemName = $subItem[4] ?? ('Item #'.($line->item_id ?? 0));

                                                        $invoiceQty = (float)($line->qty ?? 0);
                                                        $baseRate = (float)($line->rate ?? 0);
                                                        if ($baseRate <= 0 && $invoiceQty > 0) {
                                                            $baseRate = ((float)($line->amount ?? 0)) / $invoiceQty;
                                                        }

                                                        $lineAmount = (float)($line->amount ?? 0);
                                                        if ($lineAmount <= 0) {
                                                            $lineAmount = $invoiceQty * $baseRate;
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center">{{ $lineCounter }}</td>
                                                        <td>{{ $itemName }}</td>
                                                        <td class="text-right">{{ number_format($invoiceQty, 2) }}</td>
                                                        <td>
                                                            <input type="text" class="form-control text-right" value="{{ number_format($baseRate, 2, '.', '') }}" disabled>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control text-right js-item-amount" value="{{ number_format($lineAmount, 2, '.', '') }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0" step="0.01" class="form-control text-right js-discount-percent" data-row="{{ $lineCounter }}" value="0">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control text-right js-deduction-amount" data-row="{{ $lineCounter }}" value="0.00" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control text-right js-net-amount" data-row="{{ $lineCounter }}" value="{{ number_format($lineAmount, 2, '.', '') }}" readonly>
                                                        </td>
                                                    </tr>

                                                    <input type="hidden" name="item_id{{ $lineCounter }}" value="{{ $line->item_id ?? 0 }}">
                                                    <input type="hidden" name="invoice_data_id{{ $lineCounter }}" value="{{ $line->id ?? 0 }}">
                                                    <input type="hidden" name="discount_percent{{ $lineCounter }}" class="js-hidden-discount-percent" data-row="{{ $lineCounter }}" value="0">
                                                    <input type="hidden" name="discount_amount{{ $lineCounter }}" class="js-hidden-discount-amount" data-row="{{ $lineCounter }}" value="0">
                                                    <input type="hidden" name="line_net_amount{{ $lineCounter }}" class="js-hidden-net-amount" data-row="{{ $lineCounter }}" value="{{ number_format($lineAmount, 2, '.', '') }}">
                                                    <input type="hidden" name="count[]" value="{{ $lineCounter }}">

                                                    <?php $lineCounter++; ?>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="4" class="text-right">Gross Total</th>
                                                        <th><input type="text" id="gross_total" class="form-control text-right" value="0.00" readonly></th>
                                                        <th class="text-right">Total Discount</th>
                                                        <th><input type="text" id="total_discount" class="form-control text-right" value="0.00" readonly></th>
                                                        <th><input type="text" id="net_total" class="form-control text-right" value="0.00" readonly></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top:8px;">
                                    <div class="col-lg-12 text-right">
                                        <button type="submit" class="btn btn-success" id="saveDiscountNote">Save Credit Note</button>
                                    </div>
                                </div>

                                <?php echo Form::close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toNum(val) {
            var n = parseFloat(val);
            return isNaN(n) ? 0 : n;
        }

        function recalcRow(row) {
            var percentEl = $('.js-discount-percent[data-row="' + row + '"]');
            var deductionEl = $('.js-deduction-amount[data-row="' + row + '"]');
            var netEl = $('.js-net-amount[data-row="' + row + '"]');
            var itemAmountEl = percentEl.closest('tr').find('.js-item-amount');

            var itemAmount = toNum(itemAmountEl.val());
            var percent = toNum(percentEl.val());
            if (percent < 0) percent = 0;
            if (percent > 100) percent = 100;
            // Keep user-typed format (e.g. 10 instead of 10.00)
            percentEl.val(percent);

            var deduction = (itemAmount * percent) / 100;
            var netAmount = itemAmount - deduction;

            deductionEl.val(deduction.toFixed(2));
            netEl.val(netAmount.toFixed(2));

            $('.js-hidden-discount-percent[data-row="' + row + '"]').val(percent.toFixed(2));
            $('.js-hidden-discount-amount[data-row="' + row + '"]').val(deduction.toFixed(2));
            $('.js-hidden-net-amount[data-row="' + row + '"]').val(netAmount.toFixed(2));
        }

        function recalcTotals() {
            var gross = 0, discount = 0, net = 0;

            $('.js-item-amount').each(function () { gross += toNum($(this).val()); });
            $('.js-deduction-amount').each(function () { discount += toNum($(this).val()); });
            $('.js-net-amount').each(function () { net += toNum($(this).val()); });

            $('#gross_total').val(gross.toFixed(2));
            $('#total_discount').val(discount.toFixed(2));
            $('#net_total').val(net.toFixed(2));
        }

        function recalcAll() {
            $('.js-discount-percent').each(function () { recalcRow($(this).data('row')); });
            recalcTotals();
        }

        $(document).on('keyup change', '.js-discount-percent', function () {
            recalcRow($(this).data('row'));
            recalcTotals();
        });

        $(document).ready(function () {
            recalcAll();
        });

        $('#creditNoteForm').on('submit', function (e) {
            e.preventDefault();
            alert('Discount note save backend next step me connect karenge. Abhi calculation form ready hai.');
        });
    </script>
@endsection
