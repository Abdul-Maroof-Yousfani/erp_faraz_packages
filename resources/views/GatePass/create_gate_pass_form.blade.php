@extends('layouts.default')
@section('content')
<?php
use App\Helpers\CommonHelper;

$m = $_GET['m'] ?? Session::get('run_company');
$currentDate = date('Y-m-d');
$currentTime = date('H:i');

$manualGatePassItems = $manualGatePassItems ?? [];
$manualGatePassOldRows = [];

$oldDescriptions = old('manual_description', []);
$oldPurposes = old('manual_purpose', []);
$oldQuantities = old('manual_qty', []);

if (!empty($oldDescriptions) || !empty($oldPurposes) || !empty($oldQuantities)) {
    $manualRowCount = max(count($oldDescriptions), count($oldPurposes), count($oldQuantities));
    for ($i = 0; $i < $manualRowCount; $i++) {
        $manualGatePassOldRows[] = [
            'description' => $oldDescriptions[$i] ?? '',
            'purpose' => $oldPurposes[$i] ?? '',
            'qty' => $oldQuantities[$i] ?? '',
        ];
    }
} elseif (!empty($manualGatePassItems)) {
    foreach ($manualGatePassItems as $manualItem) {
        $manualGatePassOldRows[] = [
            'description' => $manualItem->item_name ?? '',
            'purpose' => $manualItem->purpose ?? '',
            'qty' => $manualItem->qty ?? '',
        ];
    }
}
?>

<div class="container-fluid">
    <div class="row well_N">
        <div class="col-lg-12">
            <div class="panel panel-dark">
                <div class="panel-heading clearfix">
                    <div class="pull-left">
                        <h3 class="panel-title text-info">Create Gate Pass</h3>
                        <div class="small text-info">Pick a source type, auto-load items, and keep the form compact.</div>
                    </div>
                    <div class="pull-right text-right">
                        <div><span class="label label-primary">Gate Pass ID</span></div>
                        <strong class="text-primary">{{ $gatePassNo }}</strong>
                    </div>
                </div>

                <div class="panel-body">
                    <form id="gatePassForm" method="post" action="{{ !empty($gatePassEdit) ? url('/pdc/updateGatePass/' . $gatePassEdit->id . '?m=' . $m . '&pageType=' . ($pageType ?? '') . '&parentCode=' . ($parentCode ?? '')) : url('/pdc/storeGatePass?m=' . $m . '&pageType=' . ($pageType ?? '') . '&parentCode=' . ($parentCode ?? '')) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="m" value="{{ $m }}">
                        <input type="hidden" name="pageType" value="{{ $pageType ?? '' }}">
                        <input type="hidden" name="parentCode" value="{{ $parentCode ?? '' }}">
                        @if(!empty($gatePassEdit))
                            <input type="hidden" name="gate_pass_id" value="{{ $gatePassEdit->id }}">
                        @endif

                        <div class="panel panel-default">
                            {{-- <div class="panel-heading">
                                <strong class="small text-uppercase">Gate Pass Source</strong>
                            </div> --}}
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="gate_pass_type">Input Type</label>
                                        <select class="form-control" id="gate_pass_type" name="gate_pass_type">
                                            <option value="">Select input type</option>
                                            <option value="1" @if(($selectedGatePassType ?? '') === '1') selected @endif>Against Direct Sale Invoice</option>
                                            <option value="2" @if(($selectedGatePassType ?? '') === '2') selected @endif>Against Delivery Note</option>
                                            <option value="3" @if(($selectedGatePassType ?? '') === '3') selected @endif>Manual</option>
                                        </select>
                                        {{-- <div class="help-block">Direct invoice and delivery note will load read-only items.</div> --}}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="gate_pass_date">Date</label>
                                        <input type="date" class="form-control" id="gate_pass_date" name="gate_pass_date" value="{{ $gatePassDate ?? $currentDate }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="gate_pass_time">Time <span class="text-muted">optional</span></label>
                                        <input type="time" class="form-control" id="gate_pass_time" name="gate_pass_time" value="{{ $gatePassTime ?? $currentTime }}">
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">&nbsp;</label>
                                        <button type="button" id="btnAddManualRow" class="btn btn-primary btn-block" onclick="handleAddManualRowClick()">
                                            Add Row
                                        </button>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:15px;">
                                    <div class="col-md-6">
                                        <div class="panel panel-default" id="direct_sale_section" hidden>
                                            <div class="panel-body">
                                                <label class="control-label small" for="sales_invoice_id">Select Invoice</label>
                                                <select class="form-control" id="sales_invoice_id" name="sales_invoice_id">
                                                    <option value="">Select direct sale invoice</option>
                                                    @forelse($directSaleInvoices as $invoice)
                                                        <option value="{{ $invoice->id }}" @if((string)($selectedSalesInvoiceId ?? '') === (string) $invoice->id) selected @elseif(!empty($invoice->gate_pass_status) && (int) $invoice->gate_pass_status === 1) disabled @endif>
                                                            {{ strtoupper($invoice->gi_no) }}
                                                            @if(!empty($invoice->customer_name))
                                                                - {{ $invoice->customer_name }}
                                                            @endif
                                                            @if(!empty($invoice->gi_date))
                                                                - {{ CommonHelper::changeDateFormat($invoice->gi_date) }}
                                                            @endif
                                                            @if(!empty($invoice->gate_pass_status) && (int) $invoice->gate_pass_status === 1 && (string)($selectedSalesInvoiceId ?? '') !== (string) $invoice->id)
                                                                - Gate Pass Created
                                                            @endif
                                                        </option>
                                                    @empty
                                                        <option value="">No direct sale invoices found</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="panel panel-default" id="delivery_note_section" hidden>
                                            <div class="panel-body">
                                                <label class="control-label small" for="delivery_note_id">Select Delivery Note</label>
                                                <select class="form-control" id="delivery_note_id" name="delivery_note_id">
                                                    <option value="">Select delivery note</option>
                                                    @forelse($deliveryNotes as $note)
                                                        <option value="{{ $note->id }}" @if((string)($selectedDeliveryNoteId ?? '') === (string) $note->id) selected @elseif(!empty($note->gate_pass_status) && (int) $note->gate_pass_status === 1) disabled @endif>
                                                            {{ strtoupper($note->gd_no) }}
                                                            @if(!empty($note->customer_name))
                                                                - {{ $note->customer_name }}
                                                            @endif
                                                            @if(!empty($note->gd_date))
                                                                - {{ CommonHelper::changeDateFormat($note->gd_date) }}
                                                            @endif
                                                            @if(!empty($note->gate_pass_status) && (int) $note->gate_pass_status === 1 && (string)($selectedDeliveryNoteId ?? '') !== (string) $note->id)
                                                                - Gate Pass Created
                                                            @endif
                                                        </option>
                                                    @empty
                                                        <option value="">No delivery notes found</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default" id="items_card" hidden>
                            <div class="panel-heading">
                                <strong class="small text-uppercase">Source Items</strong>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th width="50" class="text-center">#</th>
                                            <th width="50" class="text-center">Item Name</th>
                                            <th width="50" class="text-center">Qty</th>
                                            <th width="50" class="text-center">Purpose</th>
                                            <th width="50" class="text-center hide">Rate</th>
                                            <th width="50" class="text-center hide">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gatePassItemsBody">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Select a source to load items.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default" id="manual_section" hidden>
                            <div class="panel-heading clearfix">
                                <strong class="small text-uppercase pull-left">Manual Items</strong>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">Purpose</th>
                                                <th class="text-center" width="140">Quantity</th>
                                                <th class="text-center" width="90">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="manualGatePassBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            {{-- <div class="panel-heading clearfix">
                                <strong class="small text-uppercase pull-left">Common Details</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label small" for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Add gate pass description">{{ $description ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong class="small text-uppercase">Vehicle Details</strong>
                                <span class="text-muted small">optional</span>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="vehicle_no">Vehicle No</label>
                                        <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" placeholder="Vehicle number" value="{{ $vehicleNo ?? '' }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="vehicle_type">Vehicle Type</label>
                                        <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" placeholder="Truck, van, etc." value="{{ $vehicleType ?? '' }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="driver_name">Driver Name</label>
                                        <input type="text" class="form-control" id="driver_name" name="driver_name" placeholder="Driver name" value="{{ $driverName ?? '' }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="vehicle_contact">Contact</label>
                                        <input type="text" class="form-control" id="vehicle_contact" name="vehicle_contact" placeholder="Optional contact" value="{{ $vehicleContact ?? '' }}">
                                    </div>
                                </div>
                                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label small" for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Add gate pass description">{{ $description ?? '' }}</textarea>
                                    </div>
                                <div class="row hide">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="control-label small" for="transporter_name">Transporter / Company</label>
                                        <input type="text" class="form-control" id="transporter_name" name="transporter_name" placeholder="Transporter name" value="{{ $transporterName ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-default" onclick="resetGatePassForm()">Reset</button>
                            <button type="submit" class="btn btn-primary">Save Gate Pass</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const gatePassSourceData = @json([
        '1' => $directSaleInvoiceItems ?? [],
        '2' => $deliveryNoteItems ?? [],
    ]);
    const manualGatePassExistingRows = @json($manualGatePassOldRows);

    function formatNumber(value) {
        const num = parseFloat(value) || 0;
        return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function buildManualRow(row = {}) {
        return `
            <tr class="manual-gate-pass-row">
                <td>
                    <input type="text" name="manual_description[]" class="form-control" placeholder="Enter description" value="${escapeHtml(row.description)}">
                </td>
                <td>
                    <input type="text" name="manual_purpose[]" class="form-control" placeholder="Enter purpose" value="${escapeHtml(row.purpose)}">
                </td>
                <td>
                    <input type="number" name="manual_qty[]" class="form-control" step="any" min="0" placeholder="Enter Qty" value="${escapeHtml(row.qty)}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-danger" onclick="removeGatePassManualRow(this)">Remove</button>
                </td>
            </tr>
        `;
    }

    function renderManualRows(rows) {
        const body = document.getElementById('manualGatePassBody');
        body.innerHTML = '';

        if (!rows || !rows.length) {
            body.insertAdjacentHTML('beforeend', buildManualRow());
            return;
        }

        rows.forEach(function (row) {
            body.insertAdjacentHTML('beforeend', buildManualRow(row));
        });
    }

    function addGatePassManualRow() {
        document.getElementById('manualGatePassBody').insertAdjacentHTML('beforeend', buildManualRow());
    }

    function handleAddManualRowClick() {
        const typeSelect = document.getElementById('gate_pass_type');

        if (typeSelect.value !== '3') {
            typeSelect.value = '3';
            setGatePassType('3');
            return;
        }

        addGatePassManualRow();
    }

    function removeGatePassManualRow(button) {
        const body = document.getElementById('manualGatePassBody');
        const row = button.closest('tr');

        if (body.querySelectorAll('tr').length > 1) {
            row.remove();
            return;
        }

        row.querySelectorAll('input').forEach(function (input) {
            input.value = '';
        });
    }

    function renderGatePassItems(type, sourceId) {
        const body = document.getElementById('gatePassItemsBody');
        const itemsCard = document.getElementById('items_card');
        const manualSection = document.getElementById('manual_section');
        const addManualButton = document.getElementById('btnAddManualRow');
        const rows = (gatePassSourceData[String(type)] || {})[String(sourceId)] || [];

        if (String(type) === '3') {
            itemsCard.hidden = true;
            manualSection.hidden = false;
            renderManualRows(manualGatePassExistingRows);
            return;
        }

        if (!sourceId || !type) {
            body.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Select a source to load items.</td></tr>';
            itemsCard.hidden = true;
            manualSection.hidden = true;
            return;
        }

        itemsCard.hidden = false;
        manualSection.hidden = true;

        if (!rows.length) {
            body.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No items found for the selected source.</td></tr>';
            return;
        }

        let html = '';
        rows.forEach(function (item, index) {
            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.item_name ?? ''}</td>
                    <td class="text-right">${formatNumber(item.qty)}</td>
                    <td class="text-right hide">${formatNumber(item.rate)}</td>
                    <td class="text-right hide">${formatNumber(item.amount)}</td>
                    <td class="text-center">
                       <input type="text" name="item_purpose[${index}]" class="form-control" placeholder="Enter purpose" value="${escapeHtml(item.purpose)}">
                    </td>
                </tr>
            `;
        });

        body.innerHTML = html;
    }

    function setGatePassType(value) {
        const directSection = document.getElementById('direct_sale_section');
        const deliverySection = document.getElementById('delivery_note_section');
        const manualSection = document.getElementById('manual_section');
        const directSelect = document.getElementById('sales_invoice_id');
        const deliverySelect = document.getElementById('delivery_note_id');

        directSection.hidden = true;
        deliverySection.hidden = true;
        manualSection.hidden = true;
        directSelect.disabled = true;
        deliverySelect.disabled = true;

        if (value === '1') {
            directSection.hidden = false;
            directSelect.disabled = false;
            renderGatePassItems(value, directSelect.value);
        } else if (value === '2') {
            deliverySection.hidden = false;
            deliverySelect.disabled = false;
            renderGatePassItems(value, deliverySelect.value);
        } else if (value === '3') {
            manualSection.hidden = false;
            renderManualRows(manualGatePassExistingRows);
            renderGatePassItems(value, '');
        } else {
            renderGatePassItems('', '');
        }
    }

    function resetGatePassForm() {
        document.getElementById('gatePassForm').reset();
        document.getElementById('gatePassItemsBody').innerHTML = '<tr><td colspan="5" class="text-center text-muted">Select a source to load items.</td></tr>';
        document.getElementById('manualGatePassBody').innerHTML = '';
        setGatePassType('');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('gate_pass_type');
        const salesInvoiceSelect = document.getElementById('sales_invoice_id');
        const deliveryNoteSelect = document.getElementById('delivery_note_id');

        typeSelect.addEventListener('change', function () {
            setGatePassType(this.value);
        });

        salesInvoiceSelect.addEventListener('change', function () {
            renderGatePassItems(typeSelect.value, this.value);
        });

        deliveryNoteSelect.addEventListener('change', function () {
            renderGatePassItems(typeSelect.value, this.value);
        });

        setGatePassType(typeSelect.value);
    });
</script>
@endsection
