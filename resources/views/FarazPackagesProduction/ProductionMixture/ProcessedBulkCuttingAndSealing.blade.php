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
                    url: "{{ route('FarProduction.getPrintedRollItems') }}",
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
            console.log(item);
            let itemOptions = `<option value="">Select</option>`;

            out_source_productions_items_js.forEach(function (val) {
                itemOptions += `<option value="${val.item_id}">${val.item_code} -- ${val.sub_ic}</option>`;
            });

            let selectedOption = `<option value="${item.item_id}" selected>${item.item_code} -- ${item.sub_ic}</option>`;

            let operatorsHtml = `@foreach($operators as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let machinesHtml = `@foreach($machines as $val)<option value="{{$val->id}}">{{ $val->name }}</option>@endforeach`;
            let shiftsHtml = `@foreach($shifts as $val)<option value="{{$val->id}}">{{ $val->shift_type_name }}</option>@endforeach`;
            let dateValue = "{{ date('Y-m-d') }}";


            let totalQty = item.total_qty || 0;
            let totalUsedQty = item.total_used_qty || 0;
            let remaining = totalQty - totalUsedQty;

            return `
                    <hr>
                                    <div class="card mb-6 shadow-sm border-0" id="row_${index}_a" style="background-color: #fcfcfc;">
                                        <div class="card-body">

                                            <div class="d-flex justify-content-end border-bottom pb-2 mb-3" style="float: right;">

                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeDiv('row_${index}_a')">
                                                    <i class="fa fa-trash"></i> Remove
                                                </button>
                                                {{-- &nbsp;&nbsp;
                                                <button type="button" class="btn btn-sm btn-primary" onclick="addRawMaterial(this)">
                                                    <i class="fa fa-plus"></i> Add Row
                                                </button> --}}

                                            </div>
                                            <div class="row mb-3 align-items-end">
                                                <div class="col-md-5">
                                                    <label class="font-weight-bold">Printed Roll <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;" class="form-control item-select select2" disabled>
                                                        ${selectedOption}
                                                    </select>
                                                    <input type="hidden" name="raw_item_id[]" value="${item.item_id}">
                                                    <input type="hidden" name="printed_roll_qty_sum[]" class="printed-roll-qty-sum" value="0">
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="d-flex justify-content-start align-items-center h-100 pb-2">
                                                        <span class="badge badge-info mr-2 p-2" style="font-size: 0.9em;">Total Qty: ${totalQty}</span>
                                                        <span class="badge badge-secondary mr-2 p-2" style="font-size: 0.9em;">Printed Qty: ${totalUsedQty}</span>
                                                        <span class="badge badge-success p-2" style="font-size: 0.9em;">Remaining: <span class="remaining-display">${remaining}</span></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <label class="font-weight-bold">C&S Item <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;"
                                                        name="item_id[]"
                                                        id="item_id${count}"
                                                        class="form-control requiredField item-select real-item-id select2"
                                                        onchange="itemSelected(this)">

                                                        <option value="">Select</option>

                                                        @foreach($sub_item as $val)
                                                            <option value="{{ $val->id }}">{{ $val->item_code }} -- {{ $val->sub_ic }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Operator <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;" name="operator_id[]" id="operator_id_${index}_a" class="form-control requiredField select2">
                                                        <option value="">Select</option>
                                                        ${operatorsHtml}
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>Machine <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;" name="machine_id[]" id="machine_id_${index}_a" class="form-control requiredField select2">
                                                        <option value="">Select</option>
                                                        ${machinesHtml}
                                                    </select>
                                                </div>
                                                    <div class="col-md-1">
                                                    <label>Shift <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;" name="shift_id[]" id="shift_id_${index}_a" class="form-control requiredField select2">
                                                        <option value="">Select</option>
                                                        ${shiftsHtml}
                                                    </select>
                                                </div>



                                                <div class="col-md-1">
                                                    <label>Date <span class="text-danger">*</span></label>
                                                    <input type="date" name="date[]" class="form-control move-next date" value="${dateValue}" required>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Qty (Consume)<span class="text-danger">*</span></label>
                                                    <input type="number" step="any" name="printed_roll_qty[]" class="form-control requiredField printed-roll-qty-input" oninput="validateRollQty(this)" required>
                                                    <input type="hidden" name="roll_id[]" value="${item.id}">
                                                
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Qty (Produce)<span class="text-danger">*</span></label>
                                                    <input type="number" step="any" name="qty[]" class="form-control roll-qty-input requiredField" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>Action</label>
                                                    <button type="button" class="btn btn-sm btn-success" onclick="addRawMaterial(this, ${item.id})"><i class="fa fa-add"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
        }

        let count = 2000;
        function addRawMaterial(button, rollId) {
            $('#empty-state').remove();

            let html = `
                                    <div class="card mb-3 shadow-sm border-0" id="row_${count}" style="background-color: #fcfcfc;">
                                        <div class="card-body">
                                            {{-- <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeDiv('row_${count}')"><i class="fa fa-trash"></i></button>
                                            </div> --}}
                                                    
                                            <div class="row mb-3">
                                             <div class="col-md-2">
                                                    <label class="font-weight-bold">C&S Item <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;"
                                                        name="item_id[]"
                                                        id="item_id${count}"
                                                        class="form-control requiredField item-select real-item-id select2"
                                                        onchange="itemSelected(this)">

                                                        <option value="">Select</option>

                                                        @foreach($sub_item as $val)
                                                            <option value="{{ $val->id }}">{{ $val->item_code }} -- {{ $val->sub_ic }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Operator <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;" name="operator_id[]" id="operator_id${count}" class="form-control requiredField select2">
                                                        <option value="">Select</option>
                                                        @foreach($operators as $val)
                                                            <option value="{{$val->id}}">{{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>Machine <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;" name="machine_id[]" id="machine_id${count}" class="form-control requiredField select2">
                                                        <option value="">Select</option>
                                                        @foreach($machines as $val)
                                                            <option value="{{$val->id}}">{{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>Shift <span class="text-danger">*</span></label>
                                                    <select style="width: 100% !important;" name="shift_id[]" id="shift_id${count}" class="form-control requiredField select2">
                                                        <option value="">Select</option>
                                                        @foreach($shifts as $val)
                                                            <option value="{{$val->id}}">{{ $val->shift_type_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-1">
                                                    <label>Date <span class="text-danger">*</span></label>
                                                    <input type="date" name="date[]" id="date_${count}" class="form-control move-next date" value="{{ date('Y-m-d') }}" required>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Qty (Consume)<span class="text-danger">*</span></label>
                                                    <input type="number" step="any" name="printed_roll_qty[]" id="printed_roll_qty_${count}" class="form-control requiredField printed-roll-qty-input" oninput="validateRollQty(this)" required>
                                                    <input type="hidden" name="roll_id[]" value="${rollId}">
                                                
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Qty (Produce)<span class="text-danger">*</span></label>
                                                    <input type="number" step="any" name="qty[]" id="qty_${count}" class="form-control roll-qty-input requiredField" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>Action</label>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeDiv('row_${count}')"><i class="fa fa-trash"></i></button>
                                                </div>
                                        </div>
                                    </div>
                                `;

            if (button) {
                $(button).closest('.card-body').append(html);
            } else {
                $('#out_source_production_data_to_finish_received').append(html);
            }
            $('.select2').select2();

            count++
        }

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

            let mainCard = $(inputElement).closest('.card[id^="row_"][id$="_a"]');

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