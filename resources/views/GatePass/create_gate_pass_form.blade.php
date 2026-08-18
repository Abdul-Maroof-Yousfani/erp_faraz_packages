@extends('layouts.default')
@section('content')
<?php
use App\Helpers\CommonHelper;

$m = $_GET['m'] ?? Session::get('run_company');
$currentDate = date('Y-m-d');
$currentTime = date('H:i');

$manualGatePassItems = $manualGatePassItems ?? [];
$manualGatePassOldRows = [];
$selectedSalesInvoiceIds = $selectedSalesInvoiceIds ?? [];
$selectedDeliveryNoteIds = $selectedDeliveryNoteIds ?? [];
$customers = $customers ?? collect();

$oldDescriptions = old('manual_description', []);
$oldPurposes = old('manual_purpose', []);
$oldQuantities = old('manual_qty', []);
$oldBagQtys = old('manual_bag_qty', []);
$oldPartyIds = old('manual_party_id', []);
$oldUomIds = old('manual_uom_id', []);

if (!empty($oldDescriptions) || !empty($oldPurposes) || !empty($oldQuantities) || !empty($oldBagQtys) || !empty($oldPartyIds) || !empty($oldUomIds)) {
    $manualRowCount = max(count($oldDescriptions), count($oldPurposes), count($oldQuantities), count($oldBagQtys), count($oldPartyIds), count($oldUomIds));
    for ($i = 0; $i < $manualRowCount; $i++) {
        $manualGatePassOldRows[] = [
            'description' => $oldDescriptions[$i] ?? '',
            'purpose' => $oldPurposes[$i] ?? '',
            'qty' => $oldQuantities[$i] ?? '',
            'bag_qty' => $oldBagQtys[$i] ?? '',
            'party_id' => $oldPartyIds[$i] ?? '',
            'uom_id' => $oldUomIds[$i] ?? '',
        ];
    }
} elseif (!empty($manualGatePassItems)) {
    foreach ($manualGatePassItems as $manualItem) {
        $manualGatePassOldRows[] = [
            'description' => $manualItem->item_name ?? '',
            'purpose' => $manualItem->purpose ?? '',
            'qty' => $manualItem->qty ?? '',
            'bag_qty' => $manualItem->bag_qty ?? '',
            'party_id' => $manualItem->party_id ?? '',
            'uom_id' => $manualItem->uom_id ?? ($manualItem->uom ?? ''),
        ];
    }
}
?>
@include('select2')

<style>
  /* ===== Page-scoped fix (sirf is form ke andar) — global CSS ko touch nahi kiya ===== */
 #gatePassForm .select2-container--default .select2-selection--single{height:44px !important;display:flex !important;align-items:center !important;border:1px solid #E4E7EC !important;border-radius:11px !important;background:#F7F8FC !important;}
#gatePassForm .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:normal !important;padding-left:16px !important;color:#3b3f5c !important;font-weight:500 !important;}
#gatePassForm .select2-container--default .select2-selection--single .select2-selection__arrow{height:42px !important;}
#gatePassForm .select2-container{width:100% !important;}
#gatePassForm select.form-control:not(.select2-hidden-accessible){height:44px !important;border:1px solid #E4E7EC !important;border-radius:11px !important;background:#F7F8FC !important;padding:10px 16px !important;}
.table-responsive{height:auto !important;}

</style>

<div class="container-fluid">
    <div class="row well_N">
        <div class="col-lg-12">
            <div class="panel panel-dark">
                <div class="panel-heading clearfix">
                    <div class="pull-left">
                        <h3 class="panel-title text-info"><b>Create Gate Pass</b></h3>
                        <div class="small text-info hide">Pick a source type, auto-load items, and keep the form compact.</div>
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
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="gate_pass_type">Input Type</label>
                                        <select class="form-control gp-select2" id="gate_pass_type" name="gate_pass_type" style="width: 100%;">
                                            <option value="">Select input type</option>
                                            <option value="1" @if(($selectedGatePassType ?? '') === '1') selected @endif>Against Direct Sale Invoice</option>
                                            <option value="2" @if(($selectedGatePassType ?? '') === '2') selected @endif>Against Delivery Note</option>
                                            <option value="3" @if(($selectedGatePassType ?? '') === '3') selected @endif>Manual</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="gate_pass_date">Date</label>
                                        <input type="date" class="form-control" id="gate_pass_date" name="gate_pass_date" value="{{ $gatePassDate ?? $currentDate }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="gate_pass_time">Time <span class="text-muted">optional</span></label>
                                        <input type="time" class="form-control" id="gate_pass_time" name="gate_pass_time" value="{{ $gatePassTime ?? $currentTime }}">
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" style="margin-top: 35px;">
                                        <label class="control-label small">&nbsp;</label>
                                        <button type="button" id="btnAddManualRow" class="btn btn-primary" onclick="handleAddManualRowClick()">
                                            Add Row
                                        </button>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:15px;">
                                    <div class="col-md-6">
                                        <div class="panel panel-default" id="direct_sale_section" hidden>
                                            <div class="panel-body">
                                                <label class="control-label small" for="sales_invoice_id">Select Invoice</label>
                                                <select class="form-control gp-select2" id="sales_invoice_id" name="sales_invoice_id[]" multiple  style="width: 100%;">
                                                    @forelse($directSaleInvoices as $invoice)
                                                        <option value="{{ $invoice->id }}" @if(in_array((string) $invoice->id, array_map('strval', $selectedSalesInvoiceIds ?? []), true)) selected @elseif(!empty($invoice->gate_pass_status) && (int) $invoice->gate_pass_status === 1) disabled @endif>
                                                            {{ strtoupper($invoice->gi_no) }}
                                                            @if(!empty($invoice->customer_name))
                                                                - {{ $invoice->customer_name }}
                                                            @endif
                                                            @if(!empty($invoice->gi_date))
                                                                - {{ CommonHelper::changeDateFormat($invoice->gi_date) }}
                                                            @endif
                                                            @if(!empty($invoice->gate_pass_status) && (int) $invoice->gate_pass_status === 1 && !in_array((string) $invoice->id, array_map('strval', $selectedSalesInvoiceIds ?? []), true))
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
                                                <select class="form-control gp-select2" id="delivery_note_id" name="delivery_note_id[]" multiple style="width: 100%;">
                                                    @forelse($deliveryNotes as $note)
                                                        <option value="{{ $note->id }}" @if(in_array((string) $note->id, array_map('strval', $selectedDeliveryNoteIds ?? []), true)) selected @elseif(!empty($note->gate_pass_status) && (int) $note->gate_pass_status === 1) disabled @endif>
                                                            {{ strtoupper($note->gd_no) }}
                                                            @if(!empty($note->customer_name))
                                                                - {{ $note->customer_name }}
                                                            @endif
                                                            @if(!empty($note->gd_date))
                                                                - {{ CommonHelper::changeDateFormat($note->gd_date) }}
                                                            @endif
                                                            @if(!empty($note->gate_pass_status) && (int) $note->gate_pass_status === 1 && !in_array((string) $note->id, array_map('strval', $selectedDeliveryNoteIds ?? []), true))
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
                                            <th width="90" class="text-center">Customer</th>
                                            <th width="50" class="text-center">Qty</th>
                                            <th width="80" class="text-center">Bag Qty</th>
                                            <th width="80" class="text-center">UOM</th>
                                            <th width="50" class="text-center">Purpose</th>
                                            <th width="50" class="text-center hide">Rate</th>
                                            <th width="50" class="text-center hide">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gatePassItemsBody">
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">Select a source to load items.</td>
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
                                                <th class="text-center">Customer / Party Name</th>
                                                <th class="text-center" width="140">Quantity</th>
                                                <th class="text-center">UOM</th>
                                                <th class="text-center" width="140">Bag Qty</th>
                                                <th class="text-center">Purpose</th>
                                                <th class="text-center" width="90">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="manualGatePassBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label small" for="description">Description <span class="text-muted">optional</span></label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Add gate pass description">{{ $description ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong class="small text-uppercase">Vehicle Details</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small" for="vehicle_no">Vehicle No</label>
                                        <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" required placeholder="Vehicle number" value="{{ $vehicleNo ?? '' }}">
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
                                <div class="row hide">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="control-label small" for="transporter_name">Transporter / Company</label>
                                        <input type="text" class="form-control" id="transporter_name" name="transporter_name" placeholder="Transporter name" value="{{ $transporterName ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-danger" onclick="resetGatePassForm()">Reset</button>
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
    const selectedSalesInvoiceIds = @json($selectedSalesInvoiceIds ?? []);
    const selectedDeliveryNoteIds = @json($selectedDeliveryNoteIds ?? []);
    const isGatePassEdit = @json(!empty($gatePassEdit));
    const uomOptions = @json($uoms->map(function ($uom) {
        return ['id' => $uom->id, 'name' => $uom->uom_name];
    })->values());
    const customerOptions = @json($customers->filter(function ($customer) {
        $name = strtolower(trim($customer->name ?? ''));
        return $name !== 'walk-in customer' && $name !== 'walk in customer';
    })->map(function ($customer) {
        return ['id' => $customer->id, 'name' => $customer->name];
    })->values());
    let gatePassItemsRequestToken = 0;

    // ===== Common select2 options — hamesha explicit width + body-level dropdown =====
    // (root fix: hidden parent ke andar init hone par select2 apna width 0 le leta tha,
    //  isi wajah se dropdown "chhota/misplaced" render hota tha aur uske andar wale
    //  remove(x) tags bhi click-able nahi rehte the)
    const gpSelect2Opts = { width: '100%', dropdownParent: $('body') };

    // Kuch shared layout/partial (select2 include) pehle se hi generic
    // $('.select2').select2(); chala deta hai (bina width/dropdownParent ke).
    // Ek dafa select2 init ho jaye to dobara .select2(options) call karne se
    // kuch nahi hota (silently ignore ho jata hai) — isi liye pehle destroy
    // kar ke apne sahi options ke sath dobara init karna zaroori hai.
    function gpInitSelect2($el) {
        if (typeof $.fn.select2 !== 'function') {
            console.warn('Gate Pass: select2 plugin load nahi hua - check select2 include asset path.');
            return;
        }
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }
        $el.select2(gpSelect2Opts);
    }

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
        let customerHtml = '<option value="">Select customer / party</option>';
        customerOptions.forEach(function (customer) {
            const selected = String(row.party_id ?? '') === String(customer.id) ? 'selected' : '';
            if (String(customer.name ?? '').trim().toLowerCase() === 'walk-in customer' || String(customer.name ?? '').trim().toLowerCase() === 'walk in customer') {
                return;
            }
            customerHtml += `<option value="${escapeHtml(customer.id)}" ${selected}>${escapeHtml(customer.name)}</option>`;
        });

        let uomHtml = '<option value="">Select UOM</option>';
        uomOptions.forEach(function (uom) {
            const selected = String(row.uom_id ?? '') === String(uom.id) ? 'selected' : '';
            uomHtml += `<option value="${escapeHtml(uom.id)}" ${selected}>${escapeHtml(uom.name)}</option>`;
        });

        return `
            <tr class="manual-gate-pass-row">
                <td>
                    <input type="text" name="manual_description[]" class="form-control" placeholder="Enter description" value="${escapeHtml(row.description)}">
                </td>
                <td>
                    <select name="manual_party_id[]" class="form-control gp-select2 manual-party-select" style="width: 100%;">
                        ${customerHtml}
                    </select>
                </td>
                <td>
                    <input type="number" name="manual_qty[]" class="form-control" step="any" min="0" placeholder="Enter Qty" value="${escapeHtml(row.qty)}">
                </td>
                <td>
                    <select name="manual_uom_id[]" class="form-control gp-select2 manual-uom-select" required style="width: 100%;">
                        ${uomHtml}
                    </select>
                </td>
                <td>
                    <input type="number" name="manual_bag_qty[]" class="form-control" step="any" min="0" placeholder="Enter Bag Qty" value="${escapeHtml(row.bag_qty)}">
                </td>
                <td>
                    <input type="text" name="manual_purpose[]" class="form-control" placeholder="Enter purpose" value="${escapeHtml(row.purpose)}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger" onclick="removeGatePassManualRow(this)">Remove</button>
                </td>
            </tr>
        `;
    }

    function hasManualRows() {
        return $('#manualGatePassBody .manual-gate-pass-row').length > 0;
    }

    function initManualPartySelects() {
        $('#manualGatePassBody .manual-party-select').each(function () {
            gpInitSelect2($(this));
        });
    }

    function initManualUomSelects() {
        $('#manualGatePassBody .manual-uom-select').each(function () {
            gpInitSelect2($(this));
        });
    }

    function getSourceRows(type, sourceIds) {
        const sourceMap = gatePassSourceData[String(type)] || {};
        const ids = Array.isArray(sourceIds) ? sourceIds : (sourceIds ? [sourceIds] : []);
        let rows = [];

        ids.forEach(function (id) {
            rows = rows.concat(sourceMap[String(id)] || []);
        });

        return rows;
    }

    function buildGatePassItemsHtml(rows) {
        if (!rows || !rows.length) {
            return '<tr><td colspan="9" class="text-center text-muted">No items found for the selected source.</td></tr>';
        }

        let html = '';
        rows.forEach(function (item, index) {
            const rowKey = item.source_row_key ?? item.source_item_id ?? index;
            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.item_name ?? ''}</td>
                    <td class="text-center">${escapeHtml(item.customer_name ?? '')}</td>
                    <td class="text-right">${formatNumber(item.qty)}</td>
                    <td class="text-right">${formatNumber(item.bag_qty)}</td>
                    <td class="text-center">${escapeHtml(item.uom_name ?? item.uom ?? '')}</td>
                    <td class="text-right hide">${formatNumber(item.rate)}</td>
                    <td class="text-right hide">${formatNumber(item.amount)}</td>
                    <td class="text-center">
                       <input type="text" name="item_purpose[${rowKey}]" class="form-control" placeholder="Enter purpose" value="${escapeHtml(item.purpose)}">
                    </td>
                </tr>
            `;
        });

        return html;
    }

    function renderManualRows(rows) {
        const body = document.getElementById('manualGatePassBody');
        body.innerHTML = '';

        if (!rows || !rows.length) {
            body.insertAdjacentHTML('beforeend', buildManualRow());
            initManualPartySelects();
            initManualUomSelects();
            return;
        }

        rows.forEach(function (row) {
            body.insertAdjacentHTML('beforeend', buildManualRow(row));
        });

        initManualPartySelects();
        initManualUomSelects();
    }

    function addGatePassManualRow() {
        const manualSection = document.getElementById('manual_section');
        manualSection.hidden = false;
        document.getElementById('manualGatePassBody').insertAdjacentHTML('beforeend', buildManualRow());
        initManualPartySelects();
        initManualUomSelects();
        const rows = document.querySelectorAll('#manualGatePassBody .manual-gate-pass-row');
        const lastRow = rows[rows.length - 1];
        if (lastRow) {
            const bagQtyInput = lastRow.querySelector('input[name="manual_bag_qty[]"]');
            if (bagQtyInput) {
                bagQtyInput.focus();
            }
        }
    }

    function handleAddManualRowClick() {
        const typeSelect = document.getElementById('gate_pass_type');

        if (!typeSelect.value) {
            // select2 active hai isliye value jQuery ke through set + change trigger karo
            // taake dropdown ka display bhi sync ho jaye (sirf .value= karne se select2 ka
            // visible box purana hi dikhta reh jata tha)
            $('#gate_pass_type').val('3').trigger('change');
            return;
        }

        document.getElementById('manual_section').hidden = false;
        addGatePassManualRow();
    }

    function removeGatePassManualRow(button) {
        const body = document.getElementById('manualGatePassBody');
        const row = button.closest('tr');

        if (body.querySelectorAll('tr').length > 1) {
            // row destroy hone se pehle uske andar wale select2 instances properly destroy
            // karo — warna leftover select2 containers body ke end mein reh jate hain
            $(row).find('.select2-hidden-accessible').each(function () {
                $(this).select2('destroy');
            });
            row.remove();
            return;
        }

        row.querySelectorAll('input').forEach(function (input) {
            input.value = '';
        });
        $(row).find('.manual-party-select, .manual-uom-select').val(null).trigger('change');
    }

    function renderGatePassItems(type, sourceId) {
        const body = document.getElementById('gatePassItemsBody');
        const itemsCard = document.getElementById('items_card');
        const manualSection = document.getElementById('manual_section');
        const rows = getSourceRows(type, sourceId);
        const hasSource = Array.isArray(sourceId) ? sourceId.length > 0 : !!sourceId;

        if (String(type) === '3') {
            itemsCard.hidden = true;
            manualSection.hidden = false;
            renderManualRows(manualGatePassExistingRows);
            return;
        }

        if (isGatePassEdit && Array.isArray(manualGatePassExistingRows) && manualGatePassExistingRows.length && !hasManualRows()) {
            renderManualRows(manualGatePassExistingRows);
        }

        if (!hasSource || !type) {
            body.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Select a source to load items.</td></tr>';
            itemsCard.hidden = true;
            manualSection.hidden = true;
            return;
        }

        itemsCard.hidden = false;
        manualSection.hidden = !hasManualRows();

        if (!rows.length) {
            const requestToken = ++gatePassItemsRequestToken;
            body.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Loading items...</td></tr>';

            $.get("{{ url('/pdc/getGatePassSourceItems') }}", {
                type: type,
                source_ids: Array.isArray(sourceId) ? sourceId : [sourceId]
            }).done(function (response) {
                if (requestToken !== gatePassItemsRequestToken) {
                    return;
                }

                const responseRows = (response && response.rows) ? response.rows : {};
                const mergedRows = [];

                (Array.isArray(sourceId) ? sourceId : [sourceId]).forEach(function (id) {
                    mergedRows.push(...(responseRows[String(id)] || []));
                });

                if (!mergedRows.length) {
                    mergedRows.push(...rows);
                }

                body.innerHTML = buildGatePassItemsHtml(mergedRows);
            }).fail(function () {
                if (requestToken !== gatePassItemsRequestToken) {
                    return;
                }

                body.innerHTML = buildGatePassItemsHtml(rows);
            });

            return;
        }

        body.innerHTML = buildGatePassItemsHtml(rows);
    }

    function syncMultiSelectValues(selector, values) {
        const normalizedValues = (values || []).map(function (value) {
            return String(value);
        });

        $(selector).val(normalizedValues).trigger('change.select2');
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
            if (isGatePassEdit) {
                syncMultiSelectValues('#sales_invoice_id', selectedSalesInvoiceIds);
            }
            renderGatePassItems(value, $('#sales_invoice_id').val() || []);
        } else if (value === '2') {
            deliverySection.hidden = false;
            deliverySelect.disabled = false;
            if (isGatePassEdit) {
                syncMultiSelectValues('#delivery_note_id', selectedDeliveryNoteIds);
            }
            renderGatePassItems(value, $('#delivery_note_id').val() || []);
        } else if (value === '3') {
            renderGatePassItems(value, '');
        } else {
            renderGatePassItems('', '');
        }

        if (value === '1' || value === '2') {
            manualSection.hidden = !hasManualRows();
        }
    }

    function resetGatePassForm() {
        document.getElementById('gatePassForm').reset();
        document.getElementById('gatePassItemsBody').innerHTML = '<tr><td colspan="9" class="text-center text-muted">Select a source to load items.</td></tr>';
        document.getElementById('manualGatePassBody').innerHTML = '';
        $('#sales_invoice_id').val(null).trigger('change');
        $('#delivery_note_id').val(null).trigger('change');
        $('#gate_pass_type').val('').trigger('change');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('gate_pass_type');
        const salesInvoiceSelect = document.getElementById('sales_invoice_id');
        const deliveryNoteSelect = document.getElementById('delivery_note_id');

        // NOTE: hum ".select2" generic class jaan-boojh kar use nahi kar rahe —
        // is site ke kisi shared/global script ne ".select2" class dekh kar khud
        // apne (bina width/dropdownParent ke) options se in fields ko already
        // init kar rakha tha, aur baad mein wapas bhi kar deta tha (isi wajah se
        // do select2-container ban rahe the — DevTools mein dikha tha).
        // ".gp-select2" is page ke liye unique hai, koi doosra script ise touch
        // nahi karega — sirf yehi init chalega.
        $('.gp-select2').each(function () {
            gpInitSelect2($(this));
        });

        // gate_pass_type ab select2 hai — jQuery 'change' se bind karo taake
        // select2 ka trigger('change') bhi reliably pakda jaye
        $(typeSelect).on('change', function () {
            setGatePassType($(this).val());
        });

        $(salesInvoiceSelect).on('change select2:select select2:unselect', function () {
            renderGatePassItems(typeSelect.value, $(this).val() || []);
        });

        $(deliveryNoteSelect).on('change select2:select select2:unselect', function () {
            renderGatePassItems(typeSelect.value, $(this).val() || []);
        });

        if (isGatePassEdit) {
            syncMultiSelectValues('#sales_invoice_id', selectedSalesInvoiceIds);
            syncMultiSelectValues('#delivery_note_id', selectedDeliveryNoteIds);
        }
        setGatePassType(typeSelect.value);
    });
</script>
@endsection