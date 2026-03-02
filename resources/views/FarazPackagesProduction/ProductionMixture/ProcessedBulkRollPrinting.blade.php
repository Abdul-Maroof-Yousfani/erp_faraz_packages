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
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Create Production Bulk Roll Printing
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
                                    <form action="{{route('FarProduction.RollPrint')}}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" id="for_disabled_btn">


                                        <div class="row">
                                            <div class="col-lg-12 cus-tab">
                                                <div class="row qout-h" style="padding: 10px">
                                                    <div class="col-md-12 bor-bo">
                                                        <div class="pips_create">
                                                            <h1 style="display: inline-block;">Roll Printing</h1>
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


                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12 text-right mr-4">
                                                                        <a onclick="addRawMaterial()"
                                                                            class="btn btn-primary mr-1">Add More</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h1 style="display: inline-block;">Printed Roll Item Detail</h1>

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
                if (!productionOrderId) {
                    $('#out_source_productions_data_to_finish_received').empty();
                    return;
                }

                $.ajax({
                    url: "{{ route('FarProduction.getRollingItems') }}",
                    type: "GET",
                    data: {
                        production_order_id: productionOrderId,
                        m: '{{ $m ?? 1 }}'
                    },
                    success: function (response) {
                        out_source_productions_items_js = response.items;
                        let container = $('#out_source_productions_data_to_finish_received');

                        // We keep only the rows we might need to add, or we can just empty it.
                        // The requirements say when an order is selected, load its items.
                        // But the Blade file structure has a single row containing ALL dropdowns. 
                        // Wait, ProcessBulkRollPrinting doesn't render rows individually, it renders them all in one flex-wrap div... Let's remove ALL children except Add More button.
                        // Actually the ID out_source_production_data_to_finish_received contains all items.
                        $('#out_source_production_data_to_finish_received').empty();

                        if (response.items.length === 0) {
                            $('#out_source_production_data_to_finish_received').append(`
                                <div class="col-12 text-center text-muted py-4" id="empty-state">
                                    No printed roll items found for this production order.
                                </div>
                            `);
                        } else {
                            $('#out_source_production_data_to_finish_received').empty();

                            response.items.forEach((item, index) => {
                                let html = renderRow(index, item);
                                $('#out_source_production_data_to_finish_received').append(html);
                            });

                            $('.select2').select2();
                        }
                    }
                });
            }

        function renderRow(index, item) {
            let operatorsHtml = `@foreach($operators as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let machinesHtml = `@foreach($machines as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let shiftsHtml = `@foreach($shifts as $val)<option value="{{$val->id}}">{{ $val->shift_type_name }}</option>@endforeach`;
            let brandsHtml = `@foreach($brands as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let colorsHtml = `@foreach($colors as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let dateValue = "{{ date('Y-m-d') }}";

            let selectedOption = `<option value="${item.item_id}" selected>${item.item_code} -- ${item.sub_ic}</option>`;

            let totalQty = item.total_qty || 0;
            let totalUsedQty = item.total_used_qty || 0;
            let remaining = totalQty - totalUsedQty;

            return `
                    <div class="card mb-3 shadow-sm border-0" id="row_${index}_a" style="background-color: #fcfcfc;">
                        <div class="card-body">

                         <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeDiv('row_${index}_a')"><i class="fa fa-trash"></i> Remove</button>
                            </div>

                            <div class="row mb-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="font-weight-bold">Item <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" class="form-control item-select select2" disabled>
                                        ${selectedOption}
                                    </select>
                                    <input type="hidden" name="item_id[]" class="real-item-id" value="${item.item_id}">
                                    <input type="hidden" name="roll_id[]" value="${item.id}">
                                </div>
                                <div class="col-md-7">
                                    <div class="d-flex justify-content-start align-items-center h-100 pb-2">
                                        <span class="badge badge-info mr-2 p-2" style="font-size: 0.9em;">Total Qty: ${totalQty}</span>
                                        <span class="badge badge-secondary mr-2 p-2" style="font-size: 0.9em;">Used: ${totalUsedQty}</span>
                                        <span class="badge badge-success p-2" style="font-size: 0.9em;">Remaining: <span class="remaining-display">${remaining}</span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Operator <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="operator_id[]" id="operator_id_${index}_a" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        ${operatorsHtml}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Machine <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="machine_id[]" id="machine_id_${index}_a" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        ${machinesHtml}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Shift <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="shift_id[]" id="shift_id_${index}_a" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        ${shiftsHtml}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Type <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="type_id[]" id="type_id_${index}_a" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        <option value="Printed">Printed</option>
                                        <option value="Non-Printed">Non-Printed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Brand <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="brand[]" id="brand_${index}_a" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        ${brandsHtml}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Color <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="color[]" id="color_${index}_a" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        ${colorsHtml}
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Remarks</label>
                                    <input type="text" name="remarks[]" class="form-control move-next">
                                </div>
                                <div class="col-md-2">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date[]" class="form-control move-next date" value="${dateValue}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Roll Qty <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="roll_qty[]" class="form-control roll-qty-input requiredField" oninput="validateRollQty(this)" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Printed Roll Qty <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="printed_roll_qty[]" class="form-control printed-roll-qty-input requiredField" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        }

        let count = 2000;
        function addRawMaterial() {
            $('#empty-state').remove();

            let itemOptions = `<option value="">Select</option>`;
            out_source_productions_items_js.forEach(function (item) {
                itemOptions += `<option value="${item.item_id}">${item.item_code} -- ${item.sub_ic}</option>`;
            });

            let html = `
                    <div class="card mb-3 shadow-sm border-0" id="row_${count}" style="background-color: #fcfcfc;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeDiv('row_${count}')"><i class="fa fa-trash"></i> Remove</button>
                            </div>

                            <div class="row mb-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="font-weight-bold">Item <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;"
                                        name="item_id[]"
                                        id="item_id${count}"
                                        class="form-control requiredField item-select real-item-id select2"
                                        onchange="itemSelected(this)">
                                        ${itemOptions}
                                    </select>
                                    <input type="hidden" name="roll_id[]" value="">
                                </div>
                                <div class="col-md-7">
                                    <div class="d-flex justify-content-start align-items-center h-100 pb-2 item-stats-container" style="display: none !important;">
                                        <span class="badge badge-info mr-2 p-2" style="font-size: 0.9em;">Total Qty: <span class="stat-total">0</span></span>
                                        <span class="badge badge-secondary mr-2 p-2" style="font-size: 0.9em;">Used: <span class="stat-used">0</span></span>
                                        <span class="badge badge-success p-2" style="font-size: 0.9em;">Remaining: <span class="remaining-display">0</span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Operator <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="operator_id[]" id="operator_id${count}" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        @foreach($operators as $val)
                                            <option value="{{$val->id}}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Machine <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="machine_id[]" id="machine_id${count}" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        @foreach($machines as $val)
                                            <option value="{{$val->id}}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Shift <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="shift_id[]" id="shift_id${count}" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        @foreach($shifts as $val)
                                            <option value="{{$val->id}}">{{ $val->shift_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Type <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="type_id[]" id="type_id${count}" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        <option value="Printed">Printed</option>
                                        <option value="Non-Printed">Non-Printed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Brand <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="brand[]" id="brand${count}" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        @foreach($brands as $val)
                                            <option value="{{$val->id}}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Color <span class="text-danger">*</span></label>
                                    <select style="width: 100% !important;" name="color[]" id="color${count}" class="form-control requiredField select2">
                                        <option value="">Select</option>
                                        @foreach($colors as $val)
                                            <option value="{{$val->id}}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Remarks</label>
                                    <input type="text" name="remarks[]" id="remarks_${count}" class="form-control move-next">
                                </div>
                                <div class="col-md-2">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date[]" id="date_${count}" class="form-control move-next date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Roll Qty <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="roll_qty[]" id="roll_qty_${count}" class="form-control roll-qty-input requiredField" oninput="validateRollQty(this)" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Printed Roll Qty <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="printed_roll_qty[]" id="printed_roll_qty_${count}" class="form-control printed-roll-qty-input requiredField" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

            $('#out_source_production_data_to_finish_received').append(html);
            $('.select2').select2();

            count++
        }

        function itemSelected(selectElement) {
            let itemId = $(selectElement).val();
            let statsContainer = $(selectElement).closest('.card-body').find('.item-stats-container');
            let card = $(selectElement).closest('.card');
            
            card.find('input[name="roll_id[]"]').val('');

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

                if (itemData.id) {       
                    card.find('input[name="roll_id[]"]').val(itemData.id);
                }
                console.log("haziq", itemData.id);
            }

            // Trigger revalidation in case changing the item causes an overallocation
            validateAllQty();
        }

        function validateRollQty(inputElement) {
            let parsedVal = parseFloat($(inputElement).val()) || 0;

            // Auto-fill the Printed Roll Qty exactly
            let cardBody = $(inputElement).closest('.card-body');
            cardBody.find('.printed-roll-qty-input').val($(inputElement).val());

            let itemId = cardBody.find('.real-item-id').val();
            if (!itemId) return; // Wait until an item is selected

            let itemData = out_source_productions_items_js.find(i => i.item_id == itemId);
            if (!itemData) return;

            let total = parseFloat(itemData.total_qty) || 0;
            let used = parseFloat(itemData.total_used_qty) || 0;
            let available = total - used;

            // Sum up ALL inputs with this item ID across the interface
            let sum = 0;
            $('.real-item-id').each(function () {
                if ($(this).val() == itemId) {
                    let rowQty = parseFloat($(this).closest('.card-body').find('.roll-qty-input').val()) || 0;
                    sum += rowQty;
                }
            });

            if (sum > available) {
                // Find how much this specific input is exceeding by taking the sum logic into account
                // Subtract this element's CURRENT value from the sum, and add back what is left from available
                let otherSum = sum - parsedVal;
                let maxAllowed = available - otherSum;

                if (maxAllowed < 0) maxAllowed = 0;

                alert('Error: You have exceeded the available quantity for this item. Maximum allowed remaining is ' + maxAllowed.toFixed(2));

                // Limit this input
                $(inputElement).val(maxAllowed > 0 ? maxAllowed : '');
                cardBody.find('.printed-roll-qty-input').val($(inputElement).val());
            }
        }

        function validateAllQty() {
            $('.roll-qty-input').each(function () {
                validateRollQty(this);
            });
        }

        // Attach logic to form submission safeguard
        $('form').on('submit', function (e) {
            validateAllQty();
            let invalid = false;
            $('.real-item-id').each(function () {
                let itemId = $(this).val();
                if (itemId) {
                    let itemData = out_source_productions_items_js.find(i => i.item_id == itemId);
                    if (itemData) {
                        let total = parseFloat(itemData.total_qty) || 0;
                        let used = parseFloat(itemData.total_used_qty) || 0;
                        let available = total - used;

                        let sum = 0;
                        $('.real-item-id').filter(function () { return $(this).val() == itemId; }).each(function () {
                            sum += parseFloat($(this).closest('.card-body').find('.roll-qty-input').val()) || 0;
                        });

                        if (sum > available) {
                            invalid = true;
                        }
                    }
                }
            });

            if (invalid) {
                e.preventDefault();
                alert('Cannot submit. One or more item quantities exceed their available limits.');
                return false;
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


        function cal_amt() {
            let issued_qty = parseFloat($('.issued_qty').val()) || 0;
            let used_qty_total = parseFloat($('.used_qty_total').val()) || 0;
            let issued_rate = parseFloat($('.issued_rate').val()) || 0;
            let raw_avg_amt = parseFloat($('.raw_avg_amt').val()) || 0;
            let initial_qty = parseFloat($('.initial_qty').val()) || 0;

            if (used_qty_total > issued_qty) {
                alert("Used Qty cannot be greater than Not Used Qty!");
                $('.used_qty_total').val(issued_qty); // reset to max allowed
                used_qty_total = issued_qty;
            }

            // Always update remaining qty
            $('#remaining_qty').val(issued_qty - used_qty_total);
            $('#issued_rate').val(raw_avg_amt / used_qty_total);

            // Get all roll_qty fields
            let roll_qty_inputs = $("input[name='roll_qty[]']");
            let printed_roll_qty_inputs = $("input[name='printed_roll_qty[]']");
            let count = roll_qty_inputs.length;

            // ---- CASE 1: Auto distribute when Used Qty changes ----
            if (count > 0 && used_qty_total > 0 && event?.type === "keyup" && $(event.target).hasClass("used_qty_total")) {
                let roll_qty_avg = used_qty_total / count;
                roll_qty_inputs.each(function () {
                    $(this).val(roll_qty_avg.toFixed(2));
                });
                printed_roll_qty_inputs.each(function () {
                    $(this).val(roll_qty_avg.toFixed(2));
                });
            }

            // ---- CASE 2: Validate manual roll_qty edits ----
            let sum_roll_qty = 0;
            roll_qty_inputs.each(function () {
                sum_roll_qty += parseFloat($(this).val()) || 0;
            });

            if (sum_roll_qty > used_qty_total) {
                alert("Total Raw Qty cannot exceed Used Qty!");
                $(event.target).val(0); // reset the last changed input
            }


            // ---- CASE 3: Calculate Finish Amount = Qty * Rate ----
            $("[id^='roll_qty_']").each(function (index) {
                let row = index + 1;

                let qty = parseFloat($(`#roll_qty_${row}`).val()) || 0;
                let rate = parseFloat($(`#finish_rate_${row}`).val()) || 0;

                let amount = qty * rate;
                let raw_amount = raw_avg_amt / initial_qty;
                let end_amount = amount + raw_amount;

                $(`#finish_amount_${row}`).val(amount.toFixed(2));
                $(`#finish_end_amount_${row}`).val(end_amount.toFixed(2));
            });



        }








        function removeDiv(div) {

            // Count total rows (cards)
            if ($('.card[id^="row_"]').length > 1) {
                $('#' + div).remove();
            } else {
                alert("At least one item must remain.");
            }

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