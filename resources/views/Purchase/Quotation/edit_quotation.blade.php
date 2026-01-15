<?php

$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    @include('number_formate')

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
                                <span class="subHeadingLabelClass">Edit Quotation Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <?php echo Form::open(['url' => url('quotation/update_quotation/'.$quotation->id) . '?m=' . $m . '', 'id' => 'cashPaymentVoucherForm', 'class' => 'stop']); ?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Quotation NO. <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField"
                                                    placeholder="" name="pr_no" id="pr_no"
                                                    value="{{ strtoupper($quotation->voucher_no) }}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Quotation Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField"
                                                    name="demand_date_1" id="demand_date_1"
                                                    value="{{ $quotation->voucher_date }}" />
                                            </div>
                                            <input type="hidden" name="pr_id" value="{{ $id }}" />

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Ref No.</label>
                                                <input autofocus type="text" class="form-control"
                                                    placeholder="Ref  No" name="ref_no" id="slip_no_1"
                                                    value="{{ $quotation->ref_no }}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"
                                                    onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');">Supplier
                                                    <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select class="form-control select2 requiredField" name="supplier"
                                                    id="supplier">
                                                    <option value="">Select</option>
                                                    @foreach (CommonHelper::get_all_supplier() as $row)
                                                        <option value="{{ $row->id }}"
                                                            {{ $row->id == $quotation->vendor_id ? 'selected' : '' }}>
                                                            {{ $row->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <input type="hidden" name="demand_type" id="demand_type">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')" class="">Currency</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="getrate();" name="currency_id" id="currency_id" class="form-control select2 requiredField">
                                                    <option value="">Select Currency</option>
                                                    @foreach(CommonHelper::get_all_currency() as $row)
                                                        <option @if($row->id == $quotation->currency_id) selected @endif data-value="{{ $row->rate }}" value="{{$row->id.','.$row->rate}}">{{$row->name}}</option>
                                                    @endforeach;
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> Currency Rate</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input class="form-control requiredField" value="{{ $quotation->currency_rate }}" type="text" name="currency_rate" id="currency_rate" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <textarea name="description_1" id="description_1" rows="4" cols="50" style="resize:none;"
                                                    class="form-control">{{ $quotation->description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="lineHeight">&nbsp;</div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive" id="">
                                                    <table class="table table-bordered">
                                                        <thead>
                                            
                                                            <tr>
                                                                <th class="text-center">SR NO</th>
                                                                <th class="text-center">Item</th>
                                                                <th style="width: 100px" class="text-center">UOM<span
                                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                                                <th style="" class="text-center">QTY<span
                                                                        class="rflabelsteric"><strong>*</strong></span>
                                                                </th>
                                                           
                                                           
                                                                <th style="" class="text-center">Rate<span
                                                                        class="rflabelsteric"><strong>*</strong></span>
                                                                </th>
                                                                <th style="" class="text-center">Amount</th>
                                                         
                                                                </th>
                                                            
                                                            </tr>
                                                        </thead>
                                                        <tbody id="AppnedHtml">
                                                            <?php $count = 1; ?>
                                                            @foreach ($quotation->quotationDatas as $row)
                                                             
                                                                <tr class="text-center">
                                                                    <td>{{ $count++ }}</td>
                                                                    <td>{{ CommonHelper::get_item_name($row->demandData->sub_item_id) }}
                                                                    </td>
                                                                    <td>{{ CommonHelper::get_uom($row->demandData->sub_item_id) }}
                                                                    </td>
                                                                    <td>{{ $row->demandData->qty }}</td>
                                                                    </td>
                                                                   
                                                                    <td><input
                                                                            onkeyup="calcu('{{ $count }}','{{ $row->demandData->qty }}')"
                                                                            onblur="calcu('{{ $count }}','{{ $row->demandData->qty }}')"
                                                                            class="form-control requiredField"
                                                                            step="0.001" type="number" name="rate[]"
                                                                            id="rate{{ $count }}"
                                                                            value="{{ $row->rate }}" /> </td>
                                                                    <td><input readonly
                                                                            class="form-control requiredField amount"
                                                                            step="0.001" type="number" name="amount[]"
                                                                            id="amount{{ $count }}"
                                                                            value="{{ $row->amount }}" /> </td>
                                                                    
                                                                  
                                                              
                                                                    <input type="hidden" name="pr_data_id[]"
                                                                        value="{{ $row->demandData->id }}" />
                                                                    <input type="hidden" name="quotation_data_id[]"
                                                                        value="{{ $row->id }}" />
                                                                </tr>
                                                            @endforeach

                                                        </tbody>

                                                        <tbody>
                                                            <tr
                                                                style="font-size:large;font-weight: bold">
                                                                <td class="text-center" colspan="5">Total</td>
                                                                <td id="" class="text-right" colspan="1">
                                                                    <input readonly class="form-control" type="text"
                                                                        id="net" />
                                                                </td>
                                                                {{-- <td></td> --}}
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"
                                                            style="float: right;">
                                                            <table class="table table-bordered sf-table-list">
                                                                <thead>
                                                                    <th class="text-center">Sales Tax Account Head</th>
                                                                    <th class="text-center">Sales Tax Amount</th>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                        <input type="hidden" name="gst_rate" id="gst_rate">
                                                                            <select onchange="sales_tax(this.id)"
                                                                                class="form-control select2"
                                                                                id="sales_taxx" name="sales_taxx">
                                                                                <option value="0">Select Sales Tax </option>
                                                                                @foreach(ReuseableCode::get_all_sales_tax() as $row)
                                                                                    <option @if($quotation->gst_id == $row->id) selected @endif data-sale-tax="{{ $row->rate }}" value="{{ $row->id }}"> {{ $row->percent }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <input readonly
                                                                                onkeyup="tax_by_amount(this.id)"
                                                                                type="text" class="form-control"
                                                                                name="sales_amount_td"
                                                                                id="sales_amount_td" />
                                                                        </td>
                                                                        <input type="hidden" name="sales_amount"
                                                                            id="sales_tax_amount" />
                                                                    </tr>


                                                                </tbody>

                                                                <tbody>
                                                                    <tr
                                                                        style="font-size:large;font-weight: bold">
                                                                        <td class="text-center">Total Amount After Tax</td>
                                                                        <td id="" class="text-right"><input
                                                                                readonly class="form-control"
                                                                                type="text" id="net_after_tax" /> </td>
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
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

                                </div>
                            </div>
                            <?php echo Form::close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script type="text/javascript">
        $('.select2').select2();
        $('#net').number(true, 3);
        $('#net_after_tax').number(true, 2);
        $('#sales_amount_td').number(true, 3);
    </script>

    <script>

        function getrate() {
            var selectedOption = document.querySelector('#currency_id option:checked');
            var rate = selectedOption.getAttribute('data-value');
            document.getElementById('currency_rate').value = rate ? rate : '1.0';
        }

        $(function() {
            sales_tax('sales_taxx');
        })

        function discount_percent(id) {
            let total = $('#amount' + id).val();
            let discount_percent = parseFloat($('#discount_percent' + id).val()) || 0
            if (discount_percent > 100) {
                alert("Discount Percent must be greater or equal 100");
                $('#discount_percent' + id).val(0)
                $('#discount_amount' + id).val(0)
                $('#net_amount' + id).val(total);
                sales_tax();
                return
            }

            if (discount_percent > 0) {
                let disAmount = (total * discount_percent) / 100
                $('#discount_amount' + id).val(disAmount.toFixed(3))
                total = total - disAmount
            } else {
                $('#discount_amount' + id).val(0)
            }
            $('#net_amount' + id).val(total);
            sales_tax();
        }

        function discount_amount(id) {
            let total = $('#amount' + id).val();
            let discount_amount = parseFloat($('#discount_amount' + id).val()) || 0
            if (discount_amount > 0) {
                let disAmount = (discount_amount / total) * 100
                if (disAmount > 100) {
                    alert("Discount Percent must be greater or equal 100");
                    $('#discount_percent' + id).val(0)
                    $('#discount_amount' + id).val(0)
                    $('#net_amount' + id).val(total);
                    sales_tax();
                    return
                }
                $('#discount_percent' + id).val(disAmount.toFixed(3))
                total = total - discount_amount

            } else {
                $('#discount_percent' + id).val(0)
            }
            $('#net_amount' + id).val(total);
            sales_tax();
        }

        function calcu(count, qty) {

            var qty = parseFloat(qty);
            var rate = parseFloat($('#rate' + count).val());
            var total = (qty * rate).toFixed(3);
            $('#amount' + count).val(total);
            $('#net_amount' + count).val(total);
            discount_percent(count)
            sales_tax();
            total_amount();
        }

        $("form").submit(function(e) {

            var validate = form_validate();
            if (validate == false) {
                e.preventDefault();
                return false;
            }
            if (validate == 1) {
                $('form').submit();

            }
        });


        function sales_tax(id) {
            total_amount();

           
            var sales_tax = 0;
            var sales_tax_per_value = $('#sales_taxx').find('option:selected').data('sale-tax');       
            $('#gst_rate').val(sales_tax_per_value);

            if (sales_tax_per_value != '0' || sales_tax_per_value != 0) {
                var net = $('#net').val();
                var sales_tax = (net / 100) * sales_tax_per_value;
            }
            $('#sales_amount_td').val(sales_tax);
            total_amount();
        }

        function total_amount() {
            var amount = 0;
            $('.amount').each(function() {

                amount += +$(this).val();

            });
            $('#net').val(amount);
            var sales_tax = parseFloat($('#sales_amount_td').val());
            $('#net_after_tax').val(amount + sales_tax);

        }
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
