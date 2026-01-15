<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$receipe = '';
$count = 1;
?>

    <?php

    $production_bom = DB::connection('mysql2')->table('production_bom')
    ->where([['finish_goods','=', $production_data->item_id],['color','=', $production_data->color]])->get(); 
    ?>
    <table class="table table-bordered">
        <tbody id="row_of_data{{$count}}" class="row_of_data receipe_main recipe_details">
            <tr style="background-color: darkgray">
                <th class="text-center">Product</th>
                <th class="text-center">Color</th>
                <th class="text-center">Order Qty</th>
                <th class="text-center">Remaining Qty</th>
            </tr>

            @php
                $item = CommonHelper::get_item_by_id($production_data->item_id);
                $uom_name = CommonHelper::get_uom($production_data->item_id);
            @endphp

            <tr>
                <!-- <td class="text-center"></td> -->
                <td class="text-center">{{ $item->item_code.' -- '.$item->sub_ic}}</td>
                <td class="text-center">{{ $production_data->color}}</td>
                <td class="text-center">{{ $production_data->quantity }}
                <input type="hidden" name="work_data_id[]" value="{{ $production_data->id}}" />
                <input type="hidden" value="{{$production_data->item_id}}" name="finish_good" id="finish_good" />
                <input type="hidden" value="{{ $item->sub_ic }}" name="item_name" />
                <input type="hidden" value="{{$production_data->id}}"  name="production_request_data_id[]" />
                <input type="hidden" class="uom_name" value="{{$uom_name}}" name="uom_name[]" />
                <input type="hidden" class="color" value="{{ $production_data->color }}" name="color[]" />
                <input type="hidden" name="order_qty[]" class="form-control order_qty" value="{{ $production_data->quantity }}" />
                <input type="hidden" name="pr_id" class="form-control" value="{{ $production->id }}" />
                <input type="hidden" name="pr_data_id" class="form-control" value="{{ $production_data->id }}" />

                    @php
                        $item_id = $production_data->item_id;
                    @endphp
                </td>
                <td class="text-center col-sm-1">
                    <input readonly type="number" name="remaining_qty" id="remaining_qty" class="form-control" />
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Action</th>
                <th class="text-center">Primary Packaging</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Number of Pails/Drums</th>
                <th class="text-center">Secondary Packaging</th>
                <th class="text-center">Carton Quantity</th>
            </tr>
        </thead>
        
        <tbody id="appendHtml">
            <tr class="RemoveRows1">
                <td class="text-center"> <a href="#" class="btn btn-sm btn-primary" onclick="addMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span> </a></td>
                <td>
                    <select style="width: 100%" name="packing_item_id[]" id="packing_item_id_1" class="form-control select2"  onchange="checkPackagingType('1')">
                        <option value="">Select Option</option>
                        @foreach($packing_items as $key => $val)
                            <option data-pack-size="{{ $val->pack_size }}" data-uom="{{ $val->uom }}" value="{{ $val->id }}">{{ $val->item_code.' -- '.$val->sub_ic }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                <input type="number" name="quantity[]" id="quantity_1" class="form-control requiredField" placeholder="Enter actual weight/quantity" onkeyup="calculateNumberOfPails('1'),calculateRemainingQty(this),calculateCartonCount('1')" oninput="calculateNumberOfPails('1'),calculateRemainingQty(this),calculateCartonCount('1')" />
                </td>
                <td>
                    <input type="number" name="number_of_pails[]" id="number_of_pails_1" class="form-control requiredField" placeholder="Auto-calculated or enter manually" onkeyup="calculateRemainingQty(this),calculateCartonCount('1')" oninput="calculateRemainingQty(this),calculateCartonCount('1')" />
                </td>
                <td>
                    <input type="hidden" name="secondary_package[]" id="secondary_package_1" value="" />
                </td>
                <td>
                    <input type="hidden" name="carton_count[]" id="carton_count_1" value="" />
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td>
                    <input type="text" name="total_packed_qty" id="total_packed_qty" class="form-control" readonly />
                </td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @php
        $count++;
    @endphp

<input type="hidden" name="customer_name" value="" id="customer_name" />
<input type="hidden" name="customer_id" value="" id="customer_id" />
<input type="hidden" name="order_no" value="{{$production->pr_no}}" id="order_no" />

<script>

    var counter = 1;
    function addMoreDetails()
    {
        counter++;
        $('#appendHtml').append(`
        <tr class="RemoveRows${counter}">
            <td class="text-center"><a href="#" class="btn btn-sm btn-danger" onclick="RemoveSection(${counter})"><span class="glyphicon glyphicon-multiply">X</span> </a></td>
            <td>
                <select style="width: 100%" name="packing_item_id[]" id="packing_item_id_${counter}" class="form-control select2" onchange="checkPackagingType(${counter})">
                    <option value="">Select Option</option>
                    @foreach($packing_items as $key => $val)
                        <option data-pack-size="{{ $val->pack_size }}" data-uom="{{ $val->uom }}" value="{{ $val->id }}">{{ $val->item_code.' -- '.$val->sub_ic }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="quantity[]" id="quantity_${counter}" class="form-control requiredField" placeholder="Enter actual weight/quantity" onkeyup="calculateNumberOfPails('${counter}'),calculateRemainingQty(this),calculateCartonCount(${counter})" oninput="calculateNumberOfPails('${counter}'),calculateRemainingQty(this),calculateCartonCount(${counter})" />
            </td>
            <td>
                <input type="number" name="number_of_pails[]" id="number_of_pails_${counter}" class="form-control requiredField" placeholder="Auto-calculated or enter manually" onkeyup="calculateRemainingQty(this),calculateCartonCount(${counter})" oninput="calculateRemainingQty(this),calculateCartonCount(${counter})" />
            </td>
            <td>
                <input type="hidden" name="secondary_package[]" id="secondary_package_${counter}" value="" />
            </td>
            <td>
                <input type="hidden" name="carton_count[]" id="carton_count_${counter}" value="" />
            </td>
           
        </tr>`);

        $('#packing_item_id_'+counter).select2();
    }

    $('.select2').select2();


    function RemoveSection(Row) {
        $('.RemoveRows' + Row).remove();
        calculateRemainingQty();
    }

    function checkPackagingType(counter) {
        var packing_item_id = $('#packing_item_id_' + counter).val();
        var finish_good = $('#finish_good').val();

        if (packing_item_id) {
            $.ajax({
                url: '{{ url('/') }}/Production/Packing/checkPackagingType',
                type: 'GET',
                data: {
                    packing_item_id: packing_item_id,
                    finish_good: finish_good,
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function(response) {
                    var row = $('#packing_item_id_' + counter).closest('tr');

                    // Columns: 0 Action, 1 Primary, 2 Quantity, 3 Number of Pails, 4 Secondary, 5 Carton Qty
                    var secondaryPackagingCell = row.find('td').eq(4);
                    var cartonQuantityCell = row.find('td').eq(5);
                    
                    // Reset to hidden inputs first
                    secondaryPackagingCell.html('<input type="hidden" name="secondary_package[]" id="secondary_package_' + counter + '" value="" />');
                    cartonQuantityCell.html('<input type="hidden" name="carton_count[]" id="carton_count_' + counter + '" value="" />');

                    if (response.status === 'success' && response.is_carton) {

                        var cartonDropdown = `<select name="secondary_package[]" id="secondary_package_${counter}" class="form-control secondary-package" onchange="calculateCartonCount(${counter})">`;
                        if (response.cartons.length > 0) {
                            cartonDropdown += `<option value="">Select Option</option>`;
                            response.cartons.forEach(function(carton) {
                                cartonDropdown += `<option data-pack-size="${carton.pack_size}" value="${carton.id}">${carton.item_code} - ${carton.sub_ic}</option>`;
                            });
                        } else {
                            cartonDropdown += `<option value="">No cartons available</option>`;
                        }
                        cartonDropdown += `</select>`;

                        // place controls
                        secondaryPackagingCell.html(cartonDropdown);
                        cartonQuantityCell.html(`
                            <input type="number" name="carton_count[]" id="carton_count_${counter}" class="form-control carton-count requiredField" placeholder="Enter carton count" readonly />
                        `);

                        $('#secondary_package_'+counter).select2();
                    }

                    // keep quantity enabled and with correct handlers
                    var quantityField = row.find('input[name="quantity[]"]');
                    if (quantityField.length === 0) {
                        row.find('td').eq(2).html(`
                            <input type="number" name="quantity[]" id="quantity_${counter}" class="form-control requiredField" placeholder="Enter actual weight/quantity" onkeyup="calculateNumberOfPails('${counter}'),calculateRemainingQty(this),calculateCartonCount(${counter})" oninput="calculateNumberOfPails('${counter}'),calculateRemainingQty(this),calculateCartonCount(${counter})" />
                        `);
                    } else {
                        quantityField.prop('disabled', false);
                        quantityField.prop('readonly', false);
                        quantityField.attr('onkeyup', 'calculateNumberOfPails(\"' + counter + '\"),calculateRemainingQty(this),calculateCartonCount(' + counter + ')');
                        quantityField.attr('oninput', 'calculateNumberOfPails(\"' + counter + '\"),calculateRemainingQty(this),calculateCartonCount(' + counter + ')');
                    }

                    // auto-calc pails if qty already present
                    calculateNumberOfPails(counter);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    var row = $('#packing_item_id_' + counter).closest('tr');
                    var quantityField = row.find('input[name="quantity[]"]');
                    if (quantityField.length > 0) {
                        quantityField.prop('disabled', false);
                        quantityField.prop('readonly', false);
                    }
                }
            });
        }
    }

    function calculateNumberOfPails(counter) {
        var row = $('#packing_item_id_' + counter).closest('tr');
        var quantityInput = row.find('input[name="quantity[]"]');
        var numberOfPailsInput = row.find('input[name="number_of_pails[]"]');
        var packingSelect = row.find('select[name="packing_item_id[]"]');
        
        var quantity = parseFloat(quantityInput.val()) || 0;
        var packSize = parseFloat(packingSelect.find('option:selected').data('pack-size')) || 0;
        
        if (quantity > 0 && packSize > 0) {
            var calculatedPails = Math.round(quantity / packSize);
            if (calculatedPails === 0 && quantity > 0) {
                calculatedPails = 1;
            }
            numberOfPailsInput.val(calculatedPails);
        } else if (quantity === 0) {
            numberOfPailsInput.val('');
        }
        // if packSize missing, leave for manual entry
    }

    function calculateCartonCount(counters) {
        var row = $('#packing_item_id_' + counters).closest('tr');

        var numberOfPailsInput = row.find('input[name="number_of_pails[]"]');
        var numberOfPails = parseFloat(numberOfPailsInput.val()) || 0;

        var selectedCarton = row.find('select[name="secondary_package[]"] option:selected');
        var packsPerCarton = parseFloat(selectedCarton.data('pack-size')) || 0;

        // If no secondary package is selected
        if (!selectedCarton.val() || packsPerCarton === 0) {
            $('#carton_count_' + counters).val(0);
            return;
        }

        if (numberOfPails > 0 && packsPerCarton > 0) {
            // Calculate cartons based on number of pails and packs per carton
            var fullCartons = Math.floor(numberOfPails / packsPerCarton);
            $('#carton_count_' + counters).val(fullCartons);
        } else {
            $('#carton_count_' + counters).val(0);
        }
    }


    function calculateRemainingQty(element = null) {
        // allow partial packing; keep submit enabled unless over-packed
        $('#btn').removeAttr('disabled');
        let totalPackedQtyField = document.querySelector("input[name='total_packed_qty']");
        let orderQty = parseFloat($('.order_qty').val()) || 0;

        if (!totalPackedQtyField) {
            console.error("Missing total packed qty field.");
            return;
        }

        let totalPackedQty = 0;
        document.querySelectorAll("tbody#appendHtml tr").forEach(tr => {
            // use actual quantity entered
            let qtyInput = tr.querySelector("input[name='quantity[]']");
            let qty = parseFloat(qtyInput?.value) || 0;
            totalPackedQty += qty;
        });

        $('#remaining_qty').val(parseFloat(orderQty - totalPackedQty))
        if (element && totalPackedQty > orderQty) {
            alert("Total packed quantity cannot exceed order quantity!");
            if (element.name === 'quantity[]' || element.name === 'number_of_pails[]') {
                element.value = "";
            }
            $('#btn').attr('disabled', true);
            return;
        }

        totalPackedQtyField.value = totalPackedQty;
    }

</script>