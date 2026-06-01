<?php
$accType = Auth::user()->acc_type;
$m = ($accType == 'client') ? $_GET['m'] : Auth::user()->company_id;

use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Credit Note</span>
                            </div>
                        </div>

                        <div class="row">&nbsp;</div>

                        <div class="panel">
                            <div class="panel-body">
                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <label>Customer</label>
                                        <select id="customer_id" class="form-control">
                                            <option value="">Select Customer</option>
                                            @foreach(($customers ?? []) as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <label>Invoice</label>
                                        <select id="invoice_no" class="form-control">
                                            <option value="">Select Invoice</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <label>&nbsp;</label>
                                        <input type="hidden" id="credit_note_type" value="">
                                        <button type="button" id="open_create_form" class="btn btn-success form-control" disabled>Open Create Form</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#customer_id').select2();
            $('#invoice_no').select2();

            $('#customer_id').on('change', function () {
                loadCustomerInvoices();
            });

            $('#invoice_no').on('change', function () {
                var hasInvoice = ($(this).val() || '') !== '';
                $('#credit_note_type').val(hasInvoice ? ($(this).find(':selected').data('type') || '') : '');
                $('#open_create_form').prop('disabled', !hasInvoice);
            });

            $('#open_create_form').on('click', function () {
                var customerId = $('#customer_id').val();
                var invoiceNo = $('#invoice_no').val();
                var type = $('#credit_note_type').val();

                if (!customerId || !invoiceNo || !type) {
                    alert('Please select customer and invoice.');
                    return;
                }

                var m = '{{ $m }}';
                var parentCode = '{{ request('parentCode', 258) }}';
                var pageType = '{{ request('pageType', 'view') }}';

                var targetUrl = "{{ url('/sales/CreateCreditNoteForm') }}"
                    + "?customer_id=" + encodeURIComponent(customerId)
                    + "&invoice_no=" + encodeURIComponent(invoiceNo)
                    + "&type=" + encodeURIComponent(type)
                    + "&pageType=" + encodeURIComponent(pageType)
                    + "&parentCode=" + encodeURIComponent(parentCode)
                    + "&m=" + encodeURIComponent(m);

                window.location.href = targetUrl;
            });
        });

        function loadCustomerInvoices() {
            var customerId = $('#customer_id').val();
            $('#credit_note_type').val('');
            $('#open_create_form').prop('disabled', true);
            $('#invoice_no').html('<option value="">Select Invoice</option>').trigger('change.select2');

            if (!customerId) {
                return;
            }

            $.ajax({
                url: "{{ url('/sdc/getCustomerInvoicesForCreditNote') }}",
                type: 'GET',
                data: {customer_id: customerId},
                success: function (data) {
                    var options = '<option value="">Select Invoice</option>';
                    $.each(data, function(index, row) {
                        var voucherDate = row.voucher_date ? (' - ' + row.voucher_date) : '';
                        options += '<option value="' + row.voucher_no + '" data-type="' + row.type + '">' + row.voucher_no + voucherDate + '</option>';
                    });
                    $('#invoice_no').html(options).trigger('change.select2');
                }
            });
        }
    </script>
@endsection
