<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$receipe = '';
$count = 1;
?>

    <?php

    $production_bom = DB::connection('mysql2')->table('production_bom')
    ->where([['finish_goods','=', $production->item_id],['color','=', $production->color]])->get(); 
    ?>
    <table class="table table-bordered">
        <tbody id="row_of_data{{$count}}" class="row_of_data receipe_main recipe_details">
            <tr style="background-color: darkgray">
                <th class="text-center" style="width: 30%">Product</th>
                <th class="text-center" style="width: 15%">Color</th>
                <th class="text-center" style="width: 15%">Order Qty</th>
                <th class="text-center" style="width: 15%">Wastage</th>
                <th class="text-center" style="width: 15%">Remaining Qty</th>
            </tr>

            @php
                $item = CommonHelper::get_item_by_id($production->item_id);
                $uom_name = CommonHelper::get_uom($production->item_id);
            @endphp

            <tr>
                <td class="text-center">{{ $item->item_code.' -- '.$item->sub_ic}}
                    <input type="hidden" name="finish_good_item_code" value="{{  $item->item_code }}">
                    <input type="hidden" name="finish_good_item_name" value="{{  $item->sub_ic }}">
                    <input type="hidden" name="finish_good_color" value="{{  $production->color }}">
                </td>
                <td class="text-center">{{ $production->color}}</td>
                <td class="text-center">{{ $production->quantity }}
                <input type="hidden" name="order_qty[]" class="form-control order_qty" value="{{ $production->quantity }}" />
                <input type="hidden" name="packing_id" value="{{ $packing_data[0]->packing_id }}">

                    @php
                        $item_id = $production->item_id;
                    @endphp
                </td>
                <td class="text-center col-sm-1">
                    <input type="number" name="wastage" id="wastage" class="form-control" placeholder="Enter wastage" step="0.01" min="0" value="{{ $production->wastage ?? 0 }}" onkeyup="calculateRemainingQty(this)" oninput="calculateRemainingQty(this)" />
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
                <th class="text-center" style="width: 5%">Action</th>
                <th class="text-center" style="width: 25%">Primary Packaging</th>
                <th class="text-center" style="width: 15%">Number of Pails/Drums</th>
                <th class="text-center" style="width: 15%">Quantity</th>
                <th class="text-center" style="width: 25%">Secondary Packaging</th>
                <th class="text-center" style="width: 10%">Carton Quantity</th>
            </tr>
        </thead>
        
        <tbody id="appendHtml">
            @foreach($packing_data as $key => $value)
                <tr class="RemoveRows{{ $count }}">
                    @if($key == 0)
                        <td class="text-center"> <a href="#" class="btn btn-sm btn-primary" onclick="addMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span> </a></td>
                    @else
                        <td class="text-center"><a href="#" class="btn btn-sm btn-danger" onclick="RemoveSection('{{ $count }}')"><span class="glyphicon glyphicon-multiply">X</span> </a></td>
                    @endif
                    
                    <td>
                        <input type="hidden" name="packing_data_id[]" value="{{ $value->id }}">
                        <select name="packing_item_id[]" id="packing_item_id_{{ $count }}" class="form-control select2"  onchange="checkPackagingType({{ $count }})">
                            <option value="">Select Option</option>
                            @foreach($packing_items as $key2 => $val)
                                <option @if($value->primary_packing_item_id == $val->id) selected @endif data-pack-size="{{ $val->pack_size }}" data-uom="{{ $val->uom }}" value="{{ $val->id }}">{{ $val->item_code.' -- '.$val->sub_ic }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="number_of_pails[]" id="number_of_pails_{{ $count }}" class="form-control requiredField" placeholder="Enter number of pails/drums" onkeyup="calculateRemainingQty(this),calculateCartonCount({{ $count }})" value="{{ $value->number_of_pails ?? '' }}" data-counter="{{ $count }}" />
                    </td>
                    <td>
                        <input type="number" name="quantity[]" id="quantity_{{ $count }}" class="form-control requiredField" placeholder="Enter actual weight/quantity" onkeyup="calculateRemainingQty(this),calculateCartonCount({{ $count }})" value="{{ $value->qty }}" data-counter="{{ $count }}" />
                    </td>
                    <td>
                        @if($value->secondary_packing_item_id != 0)
                            <select name="secondary_package[]" id="secondary_package_{{ $count }}" class="form-control select2 secondary-package" onchange="calculateCartonCount({{ $count }})">
                                <option value="">Select Option</option>
                                @foreach($cartons as $key2 => $val2)
                                    <option @if($value->secondary_packing_item_id == $val2->id) selected @endif data-pack-size="{{ $val2->pack_size }}" value="{{ $val2->id }}">{{  $val2->item_code.' - '.$val2->sub_ic }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    <td>
                        @if($value->secondary_packing_item_id != 0)
                            <input type="number" name="carton_count[]" id="carton_count_{{ $count }}" class="form-control carton-count requiredField" placeholder="Enter carton count" readonly value="{{ $value->carton_count }}" />
                        @endif
                    </td>
                </tr>
                @php $count++ @endphp
            @endforeach
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

<script>

    $(document).ready(function() {
        $('select[name="packing_item_id[]"]').each(function() {
            var counter = $(this).attr('id').split('_').pop(); 
        });

        // initialize remaining qty and carton counts for existing rows
        $('input[name="quantity[]"]').each(function () {
            var counter = $(this).data('counter');
            calculateCartonCount(counter);
        });
        calculateRemainingQty();

        $('input[name="quantity[]"]').on('blur', function () {
            var counter = $(this).data('counter');
            calculateCartonCount(counter);
            calculateRemainingQty();
        });

        $('.select2').select2();
    });

    var counter = {{ $count - 1 }};
    function addMoreDetails()
    {
        counter++;
        $('#appendHtml').append(`
        <tr class="RemoveRows${counter}">
            <td class="text-center"><a href="#" class="btn btn-sm btn-danger" onclick="RemoveSection(${counter})"><span class="glyphicon glyphicon-multiply">X</span> </a></td>
            <td>
                <select name="packing_item_id[]" id="packing_item_id_${counter}" class="form-control select2" onchange="checkPackagingType(${counter})">
                    <option value="">Select Option</option>
                    @foreach($packing_items as $key => $val)
                        <option data-pack-size="{{ $val->pack_size }}" data-uom="{{ $val->uom }}" value="{{ $val->id }}">{{ $val->item_code.' -- '.$val->sub_ic }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="quantity[]" class="form-control requiredField" onkeyup="calculateRemainingQty(this)" data-counter="{${counter}}" />
            </td>
            <td></td>
            <td></td>
           
        </tr>`);

        $('#packing_item_id_'+counter).select2();
    }

    $('.select2').select2();
    const selectElements = document.querySelector('.cc{{$count}}');
    const event = new Event('change');
    selectElements.dispatchEvent(event);

    function RemoveSection(Row) {
        $('.RemoveRows' + Row).remove();
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

                    // columns: 0 action, 1 primary, 2 number_of_pails, 3 quantity, 4 secondary, 5 carton qty
                    row.find('td:eq(4), td:eq(5)').html('');

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

                        row.find('td:eq(4)').html(cartonDropdown);

                        row.find('td:eq(5)').html(`
                            <input type="number" name="carton_count[]" id="carton_count_${counter}" class="form-control carton-count requiredField" placeholder="Enter carton count" readonly />
                        `);

                        $('#secondary_package_'+counter+'').select2();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
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
        // allow submit even with remaining; only block on overage
        $('#btn').removeAttr('disabled');
        let totalPackedQtyField = document.querySelector("input[name='total_packed_qty']");
        let orderQty = parseFloat($('.order_qty').val()) || 0;
        let wastage = parseFloat($('#wastage').val()) || 0;

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

        // Calculate remaining qty: Order Qty - Total Packed Qty - Wastage
        let remainingQty = orderQty - totalPackedQty - wastage;
        $('#remaining_qty').val(parseFloat(remainingQty).toFixed(2));
        
        // Check if total packed qty + wastage exceeds order qty
        if (element && (totalPackedQty + wastage) > orderQty) {
            alert("Total packed quantity plus wastage cannot exceed order quantity!");
            if (element.id === 'wastage') {
                element.value = "";
            } else {
                element.value = "";
            }
            $('#btn').attr('disabled', true);
            calculateRemainingQty();
            return;
        }

        totalPackedQtyField.value = totalPackedQty;
    }

</script>