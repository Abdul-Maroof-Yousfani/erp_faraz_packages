<?php
$m = $m ?? ($_GET['m'] ?? Session::get('run_company'));
$pageType = $pageType ?? ($_GET['pageType'] ?? '');
$parentCode = $parentCode ?? ($_GET['parentCode'] ?? '');
?>

@extends('layouts.default')

@section('content')
@include('select2')

<div class="well_N">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-info"><b>Gate Pass IN</b></h3>
            <div class="pull-right" style="margin-top:-18px;">
                <a href="{{ url('/pdc/viewGatePassInList?m=' . $m . '&pageType=' . ($pageType ?? '') . '&parentCode=' . ($parentCode ?? '')) }}" class="btn btn-xs btn-primary">View List</a>
            </div>
        </div>
        <div class="panel-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="post" action="{{ url('/pdc/storeGatePassIn?m=' . $m) }}">
                {{ csrf_field() }}
                <input type="hidden" name="m" value="{{ $m }}">
                <input type="hidden" name="pageType" value="{{ $pageType }}">
                <input type="hidden" name="parentCode" value="{{ $parentCode }}">

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="control-label small">Select Manual Gate Pass</label>
                        <select class="form-control select2" id="manual_gate_pass_id" name="manual_gate_pass_id" style="width:100%;" required>
                            <option value="">Select Gate Pass</option>
                            @foreach(($manualGatePasses ?? []) as $gatePass)
                                <?php $gatePassInCreated = (int) ($gatePass->gate_pass_in_status ?? 0) === 1; ?>
                                <option value="{{ $gatePass->id }}" @if($gatePassInCreated) disabled @endif>
                                    {{ $gatePass->gate_pass_no }}
                                    @if(!empty($gatePass->gate_pass_date))
                                        - {{ $gatePass->gate_pass_date }}
                                    @endif
                                    @if(!empty($gatePass->vehicle_no))
                                        - {{ $gatePass->vehicle_no }}
                                    @endif
                                    @if($gatePassInCreated)
                                        - Gate Pass IN Created
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row" style="margin-top:12px;">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label small">Gate Pass ID</label>
                        <input type="text" class="form-control" id="gate_pass_no" name="gate_pass_no" readonly>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label small">Input Type</label>
                        <input type="text" class="form-control" id="input_type" name="input_type" value="Manual" readonly>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label small">Date</label>
                        <input type="text" class="form-control" id="gate_pass_date" name="gate_pass_date" readonly>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label small">Time <span class="text-muted">optional</span></label>
                        <input type="text" class="form-control" id="gate_pass_time" name="gate_pass_time" readonly>
                    </div>
                </div>

                <div class="row" style="margin-top:12px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong class="small text-uppercase">Manual Items</strong>
                    </div>
                </div>

                <div class="row" style="margin-top:14px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:60px;">S.No</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Customer / Party Name</th>
                                        <th class="text-center" style="width:130px;">Quantity</th>
                                        <th class="text-center" style="width:130px;">UOM</th>
                                        <th class="text-center" style="width:130px;">Bag Qty</th>
                                        <th class="text-center">Purpose</th>
                                    </tr>
                                </thead>
                                <tbody id="gatePassInItems">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Select manual gate pass to load details.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:12px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label small">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" readonly></textarea>
                    </div>
                </div>

                <div class="row" style="margin-top:12px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label small">Gate Pass In Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" required id="gate_pass_in_description" name="gate_pass_in_description" rows="3"></textarea>
                    </div>
                </div>

                <div class="panel panel-default" style="margin-top:12px;">
                    <div class="panel-heading">
                        <strong class="small text-uppercase">Vehicle Details</strong>
                        <span class="text-muted small">optional</span>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label class="control-label small">Vehicle No</label>
                                <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" readonly>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label class="control-label small">Vehicle Type</label>
                                <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" readonly>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label class="control-label small">Driver Name</label>
                                <input type="text" class="form-control" id="driver_name" name="driver_name" readonly>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label class="control-label small">Contact</label>
                                <input type="text" class="form-control" id="contact_no" name="contact_no" readonly>
                            </div>
                        </div>
                        <div class="row" style="margin-top:12px;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label small">Description</label>
                                <textarea class="form-control" id="vehicle_description" name="vehicle_description" rows="3" readonly></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right" style="margin-top:15px;">
                    <button type="submit" class="btn btn-primary" id="saveGatePassIn" disabled>Save Gate Pass IN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#manual_gate_pass_id').select2();

    $('#manual_gate_pass_id').on('change', function () {
        loadManualGatePassDetails($(this).val());
    });
});

function escapeHtml(value) {
    return $('<div>').text(value || '').html();
}

function formatNumber(value) {
    var numericValue = parseFloat(value || 0);
    return numericValue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

function clearGatePassInDetails() {
    $('#gate_pass_no').val('');
    $('#gate_pass_date').val('');
    $('#gate_pass_time').val('');
    $('#vehicle_no').val('');
    $('#vehicle_type').val('');
    $('#driver_name').val('');
    $('#contact_no').val('');
    $('#description').val('');
    $('#vehicle_description').val('');
    $('#gate_pass_in_description').val('');
    $('#saveGatePassIn').prop('disabled', true);
    $('#gatePassInItems').html('<tr><td colspan="7" class="text-center text-muted">Select manual gate pass to load details.</td></tr>');
}

function loadManualGatePassDetails(gatePassId) {
    if (!gatePassId) {
        clearGatePassInDetails();
        return;
    }

    $('#gatePassInItems').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');

    $.ajax({
        url: '{{ url('/pdc/getGatePassInManualDetails') }}',
        type: 'GET',
        data: {
            gate_pass_id: gatePassId,
            m: '{{ $m }}'
        },
        success: function (response) {
            var gatePass = response.gate_pass || {};
            var items = response.items || [];

            $('#gate_pass_no').val(gatePass.gate_pass_no || '');
            $('#gate_pass_date').val(gatePass.gate_pass_date || '');
            $('#gate_pass_time').val(gatePass.gate_pass_time || '');
            $('#vehicle_no').val(gatePass.vehicle_no || '');
            $('#vehicle_type').val(gatePass.vehicle_type || '');
            $('#driver_name').val(gatePass.driver_name || '');
            $('#contact_no').val(gatePass.vehicle_contact || '');
            $('#description').val(gatePass.description || '');
            $('#vehicle_description').val(gatePass.description || '');
            $('#gate_pass_in_description').val(gatePass.gate_pass_in_description || '');
            $('#saveGatePassIn').prop('disabled', parseInt(gatePass.gate_pass_in_status || 0) === 1);

            var rows = '';

            if (!items.length) {
                $('#gatePassInItems').html('<tr><td colspan="7" class="text-center text-muted">No detail found against selected gate pass.</td></tr>');
                return;
            }

            $.each(items, function (index, item) {
                rows += '<tr>' +
                    '<td class="text-center">' + (index + 1) + '</td>' +
                    '<td>' +
                        '<input type="hidden" name="gate_pass_data_id[]" value="' + escapeHtml(item.id) + '">' +
                        '<input type="text" class="form-control" name="item_name[]" value="' + escapeHtml(item.item_name) + '" readonly>' +
                    '</td>' +
                    '<td><input type="text" class="form-control" name="row_party_name[]" value="' + escapeHtml(item.party_name) + '" readonly></td>' +
                    '<td><input type="text" class="form-control text-right" name="qty[]" value="' + formatNumber(item.qty) + '" readonly></td>' +
                    '<td><input type="text" class="form-control" name="uom[]" value="' + escapeHtml(item.uom) + '" readonly></td>' +
                    '<td><input type="text" class="form-control text-right" name="bag_qty[]" value="' + escapeHtml(item.bag_qty || '') + '" readonly></td>' +
                    '<td><input type="text" class="form-control" name="remarks[]" value="' + escapeHtml(item.purpose) + '" readonly></td>' +
                '</tr>';
            });

            $('#gatePassInItems').html(rows);
        },
        error: function () {
            $('#gatePassInItems').html('<tr><td colspan="7" class="text-center text-danger">Unable to load gate pass details.</td></tr>');
        }
    });
}
</script>
@endsection
