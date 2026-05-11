<?php
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')
@include('select2')

<div class="container-fluid">
    <div class="well_N">
        <div class="dp_sdw">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="headquid">
                        <h2 class="subHeadingLabelClass">Add Production Wastage</h2>
                    </div>
                </div>
            </div>

            @if(Session::has('dataInsert'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ Session::get('dataInsert') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('FarProduction.Wastage') }}?m={{ request('m', $m) }}" method="post" id="productionWastageForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="pageType" value="{{ request('pageType') }}">
                <input type="hidden" name="parentCode" value="{{ request('parentCode') }}">
                <input type="hidden" name="m" value="{{ request('m', $m) }}">

                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label class="sf-label">Production Order</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="production_order_id" id="production_order_id" class="form-control select2 requiredField" required onchange="loadProductionOrderDetails()">
                                    <option value="">Select Production Order</option>
                                    @foreach($productionOrders as $order)
                                        <option value="{{ $order->id }}" {{ old('production_order_id') == $order->id ? 'selected' : '' }}>
                                            {{ $order->pr_no }} {{ $order->ref_no ? ' -- ' . $order->ref_no : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label class="sf-label">Process</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="process" id="process" class="form-control select2 requiredField" required>
                                    <option value="">Select Process</option>
                                    @foreach($processes as $key => $label)
                                        <option value="{{ $key }}" {{ old('process') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label class="sf-label">Wastage Date</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="wastage_date" id="wastage_date" class="form-control requiredField" value="{{ old('wastage_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>

                        <div class="row" id="productionOrderInfo" style="display: none;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="well">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>PO No.</label>
                                            <input type="text" id="po_no_text" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>PO Date</label>
                                            <input type="text" id="po_date_text" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Ref No.</label>
                                            <input type="text" id="po_ref_text" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Current Status</label>
                                            <input type="text" id="po_status_text" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered userlittab">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Sub Category</th>
                                                    <th class="text-center">Reason</th>
                                                    <th class="text-center">Required Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productionOrderDetails">
                                                <tr>
                                                    <td colspan="4" class="text-center">Select production order.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="headquid">
                            <h2 class="subHeadingLabelClass">Wastage Detail</h2>
                        </div>

                        <div class="table-responsive">
                            <table class="userlittab table table-bordered sf-table-list">
                                <thead>
                                    <tr>
                                        <th class="text-center col-sm-5">Item</th>
                                        <th class="text-center col-sm-2">Qty (KG)</th>
                                        <th class="text-center col-sm-4">Remarks</th>
                                        <th class="text-center col-sm-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="wastageRows">
                                    <tr id="row_1">
                                        <td>
                                            <select name="item_id[]" id="item_id_1" class="form-control select2 requiredField" style="width: 100% !important;" required>
                                                <option value="">Select Item</option>
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->item_code }} -- {{ $item->sub_ic }} {{ $item->uom_name ? '(' . $item->uom_name . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="any" min="0.01" name="qty[]" id="qty_1" class="form-control requiredField" required>
                                        </td>
                                        <td>
                                            <input type="text" name="remarks[]" id="remarks_1" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="addWastageRow()">
                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mp-20 text-right">
                    <button type="submit" class="btnn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    var rowCounter = 1;

    function addWastageRow() {
        rowCounter++;
        $('#wastageRows').append(`
            <tr id="row_${rowCounter}">
                <td>
                    <select name="item_id[]" id="item_id_${rowCounter}" class="form-control select2 requiredField" style="width: 100% !important;" required>
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->item_code }} -- {{ $item->sub_ic }} {{ $item->uom_name ? '(' . $item->uom_name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step="any" min="0.01" name="qty[]" id="qty_${rowCounter}" class="form-control requiredField" required>
                </td>
                <td>
                    <input type="text" name="remarks[]" id="remarks_${rowCounter}" class="form-control">
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="removeWastageRow(${rowCounter})">
                        <span class="glyphicon glyphicon-minus-sign"></span>
                    </a>
                </td>
            </tr>
        `);
        $('.select2').select2();
    }

    function removeWastageRow(rowId) {
        $('#row_' + rowId).remove();
    }

    function loadProductionOrderDetails() {
        var productionOrderId = $('#production_order_id').val();

        if (!productionOrderId) {
            $('#productionOrderInfo').hide();
            $('#productionOrderDetails').html('<tr><td colspan="4" class="text-center">Select production order.</td></tr>');
            return;
        }

        $('#productionOrderInfo').show();
        $('#productionOrderDetails').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');

        $.ajax({
            url: '{{ route('FarProduction.getProductionOrderWastageDetails') }}',
            type: 'GET',
            data: {
                production_order_id: productionOrderId,
                m: '{{ request('m', $m) }}'
            },
            success: function (response) {
                if (!response.order) {
                    $('#productionOrderDetails').html('<tr><td colspan="4" class="text-center">No detail found.</td></tr>');
                    return;
                }

                $('#po_no_text').val(response.order.pr_no || '');
                $('#po_date_text').val(response.order.request_date || '');
                $('#po_ref_text').val(response.order.ref_no || '');
                $('#po_status_text').val(response.order.curr_status || '');

                var rows = '';
                if (response.details.length > 0) {
                    $.each(response.details, function (index, detail) {
                        rows += '<tr>' +
                            '<td class="text-center">' + (index + 1) + '</td>' +
                            '<td>' + (detail.sub_category_name || '-') + '</td>' +
                            '<td>' + (detail.purpose || '-') + '</td>' +
                            '<td class="text-center">' + (detail.required_date || '-') + '</td>' +
                            '</tr>';
                    });
                } else {
                    rows = '<tr><td colspan="4" class="text-center">No detail found.</td></tr>';
                }

                $('#productionOrderDetails').html(rows);
            },
            error: function () {
                $('#productionOrderDetails').html('<tr><td colspan="4" class="text-center">Unable to load order detail.</td></tr>');
            }
        });
    }

    @if(old('production_order_id'))
        loadProductionOrderDetails();
    @endif
</script>
@endsection
