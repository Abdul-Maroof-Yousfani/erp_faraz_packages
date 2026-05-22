@extends('layouts.default')
@section('content')
    @include('select2')

    <?php
    use App\Helpers\CommonHelper;

    $Sales_Order = [];
    $Machine = [];
    $Operator = [];
                        ?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Production</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Create Production Bulk Cutting And
                        Sealing
                    </h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <!-- <ul class="cus-ul2">
                                        <li>
                                            <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                                        </li>
                                    </ul>  -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <form action="{{route('FarProduction.BulkCuttingAndSealing')}}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" id="for_disabled_btn">


                                        <div class="row">
                                            <div class="col-lg-12 cus-tab">
                                                <div class="row qout-h" style="padding: 10px">
                                                    <div class="col-md-12 bor-bo">
                                                        <div class="pips_create">
                                                            <h1 style="display: inline-block;">Cutting And Sealing</h1>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-12 padt pos-r">


                                                        <div class="row">



                                                            <div class="col-md-2">
                                                                <label for="">Production Order</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select class="form-control select2 requiredField"
                                                                    name="production_order_id" id="production_order_id"
                                                                    onchange="fetchRollingItems()">
                                                                    <option value="">Select Production Order</option>
                                                                    @foreach ($production_order as $key => $value)
                                                                        <option value="{{ $value->id }}" {{ (isset($id) && $id == $value->id) ? 'selected' : '' }}>
                                                                            {{ $value->pr_no }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="">Items</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select class="form-control select2 requiredField"
                                                                    id="printed_roll_item_filter"
                                                                    multiple
                                                                    data-placeholder="Select Items"
                                                                    onchange="renderSelectedPrintedRollItems()"
                                                                    style="width: 100% !important;">
                                                                </select>
                                                            </div>

                                                        </div>

                                                        <div class="row">

                                                            <h1 style="display: inline-block;">Cutting And Sealing Item
                                                                Detail</h1>

                                                            <div class="col-md-12 padt pos-r"
                                                                id="out_source_production_data_to_finish_received">


                                                            </div>


                                                            <div class="col-md-12 padt pos-r" id="">




                                                                <div class="row">

                                                                    <div class="col-md-12 padtb text-right">
                                                                        <button type="submit" id="save"
                                                                            class="btn btn-primary mr-1">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                    </form>

                                    <div class="row borderBtmMnd pTB40">

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

        $('.select2').select2()

        $(document).ready(function () {
            fetchRollingItems();
        });
        // Global variable to hold items fetched via AJAX
        let out_source_productions_items_js = [];
        // Store selected date per Printed Roll (roll_id => 'YYYY-MM-DD')
        let rollDateById = {};

        @if(isset($id) && $out_source_productions_item->count() > 0)
            // If loaded initially, we need to populate out_source_productions_items_js for the Add More button
            out_source_productions_items_js = [
                @foreach($out_source_productions_item as $item)
                    @php
                        $subInfo = $sub_item->where('id', $item->item_id)->first();
                    @endphp
                    @if($subInfo)
                                                                                            {
                        item_id: '{{ $item->item_id }}',
                        item_code: '{{ $subInfo->item_code }}',
                        sub_ic: '{{ $subInfo->sub_ic }}',
                        total_qty: {{ $item->total_qty ?? 0 }},
                        total_used_qty: {{ $item->total_used_qty ?? 0 }}
                                                                                            },
                    @endif
                @endforeach
                                                        ];
        @endif

            function fetchRollingItems() {
                let productionOrderId = $('#production_order_id').val();
                $('#printed_roll_item_filter').empty().trigger('change.select2');
                $('#out_source_production_data_to_finish_received').empty();
                out_source_productions_items_js = [];
                rollDateById = {};

                if (!productionOrderId) {
                    return;
                }

                $.ajax({
                    url: "{{ route('FarProduction.getPrintedRollItems') }}",
                    type: "GET",
                    data: {
                        production_order_id: productionOrderId,
                        m: '{{ $m ?? 1 }}'
                    },
                    success: function (response) {
                        out_source_productions_items_js = response.items;
                        populatePrintedRollItemDropdown(response.items);

                        if (response.items.length === 0) {
                            $('#out_source_production_data_to_finish_received').append(`
                                                <div class="col-12 text-center text-muted py-4" id="empty-state">
                                                    No printed roll items found for this production order.
                                                </div>
                                            `);
                        }
                    }
                });
            }

        function populatePrintedRollItemDropdown(items) {
            let itemDropdown = $('#printed_roll_item_filter');
            itemDropdown.empty();

            items.forEach(function (item) {
                let total     = parseFloat(item.total_qty) || 0;
                let used      = parseFloat(item.total_used_qty) || 0;
                let remaining = Math.round((total - used) * 100) / 100;
                itemDropdown.append(
                    `<option value="${item.item_id}">${item.sub_ic} (Remaining: ${remaining.toFixed(2)})</option>`
                );
            });

            itemDropdown.val(null).trigger('change.select2');
            $('.select2').select2();
        }

        function renderSelectedPrintedRollItems() {
            let selectedItems = $('#printed_roll_item_filter').val() || [];
            let container = $('#out_source_production_data_to_finish_received');

            // Remove cards no longer selected
            $('.card[data-item-id]').each(function () {
                let itemId = String($(this).data('item-id'));
                if (!selectedItems.includes(itemId)) {
                    $(this).remove();
                }
            });

            selectedItems.forEach(function (itemId, index) {
                let rowId = `row_printed_roll_${itemId}`;
                if ($('#' + rowId).length > 0) return;

                let item = out_source_productions_items_js.find(function (row) {
                    return String(row.item_id) == String(itemId);
                });

                if (item) {
                    container.append(renderRow(index, item));
                }
            });

            $('.select2').select2();
        }

        function renderRow(index, item) {
            let selectedOption = `<option value="${item.item_id}" selected>${item.sub_ic}</option>`;

            let operatorsHtml = `@foreach($operators as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let machinesHtml  = `@foreach($machines as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let shiftsHtml    = `@foreach($shifts as $val)<option value="{{$val->id}}">{{ $val->shift_type_name }}</option>@endforeach`;
            let subItemHtml   = `@foreach($sub_item as $val)<option value="{{$val->id}}">{{ $val->sub_ic }}@if(($val->type ?? '') == 'Gala Cutting') (Gala Cutting)@endif</option>@endforeach`;

            let dateValue    = item.date || "{{ date('Y-m-d') }}";
            let totalQty     = parseFloat(item.total_qty) || 0;
            let totalUsedQty = parseFloat(item.total_used_qty) || 0;
            let remaining    = Math.round((totalQty - totalUsedQty) * 100) / 100;

            // All individual roll-print IDs joined
            let rollIds = item.rows && item.rows.length
                ? item.rows.map(r => r.id).join(',')
                : (item.id || '');

            let rowId      = `row_printed_roll_${item.item_id}`;
            let shiftSelId = `shift_master_${item.item_id}`;

            if (item && item.item_id) {
                rollDateById[item.item_id] = dateValue;
            }

            return `
                <div class="card mb-3 shadow-sm border-0" id="${rowId}" data-item-id="${item.item_id}" style="border:1px solid #ddd;padding:23px 20px;background:#fff;box-shadow:1px 0px 4px #00000063;border-radius:10px;margin-bottom:40px;">
                    <div class="card-body">

                        {{-- Master row: Item + Shift + badges --}}
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-4">
                                <label class="font-weight-bold">Printed Roll Item <span class="text-danger">*</span></label>
                                <select style="width:100% !important;" class="form-control item-select select2" disabled>
                                    ${selectedOption}
                                </select>
                                <input type="hidden" name="raw_item_id[]" value="${item.item_id}">
                                <input type="hidden" name="printed_roll_qty_sum[]" class="printed-roll-qty-sum" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold">Shift <span class="text-danger">*</span></label>
                                <select style="width:100% !important;" id="${shiftSelId}"
                                    class="form-control requiredField select2 master-shift-select"
                                    onchange="propagateShift('${item.item_id}')" required>
                                    <option value="">Select Shift</option>
                                    ${shiftsHtml}
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="font-weight-bold">Date <span class="text-danger">*</span></label>
                                <input type="date" id="date_master_${item.item_id}"
                                    class="form-control roll-date"
                                    data-roll-id="${item.item_id}"
                                    value="${dateValue}" min="${dateValue}" required>
                            </div>
                            <div class="col-md-3">
                                <div class="bages-tot" style="text-align:right;">
                                    <span class="badge badge-info mr-2 p-2" style="font-size:0.9em;">Total Qty: ${totalQty}</span>
                                    <span class="badge badge-secondary mr-2 p-2" style="font-size:0.9em;">Used: ${totalUsedQty}</span>
                                    <span class="badge badge-success p-2" style="font-size:0.9em;">Remaining: <span class="remaining-display">${remaining.toFixed(2)}</span></span>
                                </div>
                            </div>
                        </div>

                        {{-- Detail heading + table --}}
                        <div style="margin-top:18px;margin-bottom:6px;">
                            <h5 style="font-weight:600;color:#444;border-left:4px solid #7367f0;padding-left:10px;margin:0;">
                                Cutting &amp; Sealing Detail
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" style="font-size:0.88rem;margin-bottom:0;">
                                <thead style="background:#f4f4f4;">
                                    <tr>
                                        <th class="text-center">C&amp;S Item <span class="text-danger">*</span></th>
                                        <th class="text-center">Operator <span class="text-danger">*</span></th>
                                        <th class="text-center">Machine <span class="text-danger">*</span></th>
                                        <th class="text-center">Qty (Consume) <span class="text-danger">*</span></th>
                                        <th class="text-center">Qty (Produce) <span class="text-danger">*</span></th>
                                        <th class="text-center">Available</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_${item.item_id}">
                                    <tr>
                                        {{-- hidden fields per row --}}
                                        <input type="hidden" name="shift_id[]" class="row-shift-val" value="">
                                        <input type="hidden" name="date[]" class="row-date-val" value="${dateValue}">
                                        <input type="hidden" name="roll_id[]" value="${rollIds}">
                                        <input type="hidden" name="row_raw_item_id[]" value="${item.item_id}">
                                        <td>
                                            <select style="width:100% !important;" name="item_id[]"
                                                class="form-control form-control-sm requiredField item-select real-item-id select2"
                                                onchange="itemSelected(this)">
                                                <option value="">Select</option>
                                                ${subItemHtml}
                                            </select>
                                        </td>
                                        <td>
                                            <select style="width:100% !important;" name="operator_id[]" class="form-control form-control-sm requiredField select2">
                                                <option value="">Select</option>
                                                ${operatorsHtml}
                                            </select>
                                        </td>
                                        <td>
                                            <select style="width:100% !important;" name="machine_id[]" class="form-control form-control-sm requiredField select2">
                                                <option value="">Select</option>
                                                ${machinesHtml}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="printed_roll_qty[]"
                                                class="form-control form-control-sm requiredField printed-roll-qty-input"
                                                oninput="validateRollQty(this)" required>
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="qty[]"
                                                class="form-control form-control-sm roll-qty-input requiredField" required>
                                        </td>
                                        <td class="text-center" style="vertical-align:middle;">
                                            <span class="badge badge-success p-2">${remaining.toFixed(2)}</span>
                                        </td>
                                        <td class="text-center" style="vertical-align:middle;">
                                            <button type="button" class="btn btn-sm btn-success" onclick="addDetailRow(this, '${item.item_id}', '${rollIds}')">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-right mt-2">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeDiv('${rowId}')">
                                <i class="fa fa-trash"></i> Remove
                            </button>
                        </div>

                    </div>
                </div>
            `;
        }

        // When master shift changes — push value into every row-shift-val hidden in this card
        function propagateShift(itemId) {
            let shiftVal = $('#shift_master_' + itemId).val();
            $('#row_printed_roll_' + itemId + ' .row-shift-val').val(shiftVal);
        }

        function propagateDate(itemId) {
            let dateVal = $('#date_master_' + itemId).val() || '';
            $('#row_printed_roll_' + itemId + ' .row-date-val').val(dateVal);
        }

        // Add a new detail row inside the table of a specific item card
        function addDetailRow(button, itemId, rollIds) {
            let operatorsHtml = `@foreach($operators as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let machinesHtml  = `@foreach($machines as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let subItemHtml   = `@foreach($sub_item as $val)<option value="{{$val->id}}">{{ $val->sub_ic }}@if(($val->type ?? '') == 'Gala Cutting') (Gala Cutting)@endif</option>@endforeach`;

            let minDate   = rollDateById[itemId] || "{{ date('Y-m-d') }}";
            let card      = $(button).closest('.card');
            let remaining = card.find('.remaining-display').first().text().trim() || '0';
            // Get current master shift value so new row is pre-synced
            let shiftVal  = $('#shift_master_' + itemId).val() || '';
            let dateVal   = $('#date_master_' + itemId).val() || minDate;

            let newRow = `
                <tr>
                    <input type="hidden" name="shift_id[]" class="row-shift-val" value="${shiftVal}">
                    <input type="hidden" name="date[]" class="row-date-val" value="${dateVal}">
                    <input type="hidden" name="roll_id[]" value="${rollIds}">
                    <input type="hidden" name="row_raw_item_id[]" value="${itemId}">
                    <td>
                        <select style="width:100% !important;" name="item_id[]"
                            class="form-control form-control-sm requiredField item-select real-item-id select2"
                            onchange="itemSelected(this)">
                            <option value="">Select</option>
                            ${subItemHtml}
                        </select>
                    </td>
                    <td>
                        <select style="width:100% !important;" name="operator_id[]" class="form-control form-control-sm requiredField select2">
                            <option value="">Select</option>
                            ${operatorsHtml}
                        </select>
                    </td>
                    <td>
                        <select style="width:100% !important;" name="machine_id[]" class="form-control form-control-sm requiredField select2">
                            <option value="">Select</option>
                            ${machinesHtml}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="any" name="printed_roll_qty[]"
                            class="form-control form-control-sm requiredField printed-roll-qty-input"
                            oninput="validateRollQty(this)" required>
                    </td>
                    <td>
                        <input type="number" step="any" name="qty[]"
                            class="form-control form-control-sm roll-qty-input requiredField" required>
                    </td>
                    <td class="text-center" style="vertical-align:middle;">
                        <span class="badge badge-success p-2">${remaining}</span>
                    </td>
                    <td class="text-center" style="vertical-align:middle;">
                        <button type="button" class="btn btn-sm btn-danger" onclick="$(this).closest('tr').remove()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            card.find('tbody').first().append(newRow);
            $('.select2').select2();
        }

        // Keep rollDateById in sync with the Printed Roll master date input
        $(document).on('change', '.roll-date', function () {
            let rollId = $(this).data('roll-id');
            let val = $(this).val();
            if (!rollId || !val) return;
            rollDateById[rollId] = val;
            propagateDate(rollId);

            let cardBody = $(this).closest('.card-body');
            cardBody.find('.row-date-val').val(val);
        });

        function itemSelected(selectElement) {
            let itemId = $(selectElement).val();
            let statsContainer = $(selectElement).closest('.card-body').find('.item-stats-container');
            let card = $(selectElement).closest('.card');


            if (!itemId) {
                statsContainer.attr('style', 'display: none !important;');
                return;
            }

            let itemData = out_source_productions_items_js.find(i => i.item_id == itemId);
            if (itemData) {
                statsContainer.find('.stat-total').text(itemData.total_qty || 0);
                statsContainer.find('.stat-used').text(itemData.total_used_qty || 0);

                let total = parseFloat(itemData.total_qty) || 0;
                let used = parseFloat(itemData.total_used_qty) || 0;
                statsContainer.find('.remaining-display').text(total - used);

                statsContainer.attr('style', 'display: flex !important;');

               
            }

            // Trigger revalidation in case changing the item causes an overallocation
            validateAllQty();
        }

        function validateRollQty(inputElement) {
            let parsedVal = parseFloat($(inputElement).val()) || 0;

            let mainCard = $(inputElement).closest('.card[data-roll-id]');

            if (mainCard.length === 0) {
                mainCard = $(inputElement).closest('.card');
            }

            let remainingDisplay = mainCard.find('.remaining-display').first();
            if (remainingDisplay.length === 0) return true;

            let remaining = parseFloat(remainingDisplay.text().trim()) || 0;

            let sum = 0;
            mainCard.find('input[name="printed_roll_qty[]"]').each(function () {
                sum += parseFloat($(this).val()) || 0;
            });

            mainCard.find('.printed-roll-qty-sum').val(sum);

            if (sum > remaining) {
                alert("Total Qty (Consume) (" + sum + ") cannot exceed Remaining Qty (" + remaining + ") for this Printed Roll.");
                let excess = sum - remaining;
                let newVal = parsedVal - excess;
                $(inputElement).val(newVal > 0 ? newVal : 0);

                // Recalculate sum after reverting
                sum = 0;
                mainCard.find('input[name="printed_roll_qty[]"]').each(function () {
                    sum += parseFloat($(this).val()) || 0;
                });
                mainCard.find('.printed-roll-qty-sum').val(sum);

                return false;
            }
            return true;
        }

        function validateAllQty() {
            let isValid = true;
            $('.printed-roll-qty-input').each(function () {
                if (!validateRollQty(this)) {
                    isValid = false;
                }
            });
            return isValid;
        }

        // Attach logic to form submission safeguard
        $('form').on('submit', function (e) {
            if (!validateAllQty()) {
                e.preventDefault();
            }
        });

        function validateUsedQty(input) {
            let maxAllowed = parseFloat(input.getAttribute('data-max')) || 0;
            let currentVal = parseFloat(input.value) || 0;

            if (currentVal > maxAllowed) {
                alert("Used Qty cannot be greater than remaining Qty (" + maxAllowed + ")");
                input.value = maxAllowed; // reset to max
            }
            if (currentVal < 0) {
                input.value = 0;
            }
        }


        function removeDiv(div) {
            let row = $('#' + div);
            let itemId = row.data('item-id');

            if (itemId) {
                let selectedItems = $('#printed_roll_item_filter').val() || [];
                selectedItems = selectedItems.filter(function (id) {
                    return String(id) != String(itemId);
                });
                $('#printed_roll_item_filter').val(selectedItems).trigger('change.select2');
                row.remove();
                return;
            }

            row.remove();
        }



        $(document).on("keydown", ".move-next", function (e) {
            if (e.key === "Tab") {
                e.preventDefault(); // Stop default tabbing

                // Get all inputs & selects
                let fields = $(".move-next");
                let index = fields.index(this);

                if (index > -1 && index + 1 < fields.length) {
                    fields.eq(index + 1).focus(); // Move to next field
                }
            }
        });

    </script>

@endsection
