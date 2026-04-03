<?php

$counter = 1;
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'] ?? $m;
} else {
    $m = Auth::user()->company_id;
}
$rowCount = max($mixtureData->count(), 1);
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    @include('number_formate')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="headquid">
                            <h2 class="subHeadingLabelClass">Edit Production Mixture</h2>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        {{ Form::open(array('url' => 'far_prod/updateProductionMixingDetail?m=' . $m . '', 'id' => 'saveMixing')) }}
                        <input type="hidden" name="mixture_id" value="{{ $mixture->id }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Item Produced</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control select2 requiredField" name="finish_item_id"
                                                    id="finish_item_id" onchange="get_uom_name_by_item_id(this.value)">
                                                    <option value="">Select Item Produced</option>
                                                    @foreach ($sub_item as $key => $value)
                                                        <option data-item-code="{{ $value->item_code }}"
                                                            value="{{ $value->id }}"
                                                            @if ($mixture->produced_item_id == $value->id) selected @endif>
                                                            {{ $value->item_code . ' - ' . $value->sub_ic }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">uom</label>
                                                <input class="form-control" type="text" name="uom" id="uom" value=""
                                                    readonly />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Mixing Number</label>
                                                <input type="text" class="form-control requiredField" name="mixing_no"
                                                    id="mixing_no" value="{{ $mixture->pm_no }}" readonly>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Mixing Date</label>
                                                <input type="date" class="form-control" name="mixing_date" id="mixing_date"
                                                    value="{{ $mixture->date }}">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Quantity</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input class="form-control requiredField" type="text" name="qty" id="qty"
                                                    value="{{ $mixture->qty }}" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Production Order</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control select2 requiredField"
                                                    name="production_order_id" id="production_order_id">
                                                    <option value="">Select Production Order</option>
                                                    @foreach ($production_order as $key => $value)
                                                        <option value="{{ $value->id }}"
                                                            @if ($mixture->production_order_id == $value->id) selected @endif>
                                                            {{ $value->pr_no }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Mixture Machine</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control select2 requiredField" name="mixture_machine_id"
                                                    id="mixture_machine_id">
                                                    <option value="">Select Mixture Machine</option>
                                                    @foreach ($mixture_machines as $key => $value)
                                                        <option value="{{ $value->id }}"
                                                            @if ($mixture->mixture_machine_id == $value->id) selected @endif>
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"></span>
                                                <textarea name="description" id="description" rows="4" cols="50"
                                                    style="resize: none; font-size: 11px" class="form-control">{{ $mixture->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="text-end my-2" style="float: right; margin-bottom: 10px;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="AddMoreDetails()">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Add More
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="userlittab table table-bordered sf-table-list">
                                                <thead>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center col-sm-3">Item</th>
                                                    <th class="text-center col-sm-2">UOM</th>
                                                    <th class="text-center col-sm-1">In Stock</th>
                                                    <th class="text-center col-sm-2">QTY (KG)</th>
                                                    <th class="text-center col-sm-1">Action</th>
                                                </thead>
                                                <tbody id="tableData">
                                                    @forelse ($mixtureData as $md)
                                                        <tr id="RemoveRows{{ $counter }}">
                                                            <td class="text-center">{{ $counter }}</td>
                                                            <td>
                                                                <select style="width: 100%;"
                                                                    class="form-control requiredField select2 item_id"
                                                                    name="item_id[]" id="item_id{{ $counter }}"
                                                                    onchange="get_stock_qty(this.value,'{{ $counter }}');get_uom_name_by_item_id(this.value, {{ $counter }})">
                                                                    <option value="">Select Raw Material</option>
                                                                    @foreach ($raw_material as $key => $value)
                                                                        <option value="{{ $value->id }}"
                                                                            data-pack_size="{{ $value->pack_size ?? 1 }}"
                                                                            @if ($md->item_id == $value->id) selected @endif>
                                                                            {{ $value->item_code . ' - ' . $value->sub_ic }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="uom[]"
                                                                    id="uom{{ $counter }}" readonly>
                                                            </td>
                                                            <td class="text-center">
                                                                <input readonly class="form-control instock zerovalidate"
                                                                    name="instock[]" type="text" value=""
                                                                    id="instock{{ $counter }}" />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text"
                                                                    class="form-control requiredField required_qty"
                                                                    name="required_qty[]"
                                                                    id="required_qty{{ $counter }}"
                                                                    value="{{ $md->qty }}"
                                                                    onkeyup="validateRowQuantity({{ $counter }}); calculateTotalQuantity()">
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="javascript:;" class="btn btn-sm btn-danger"
                                                                    onclick="RemoveSection({{ $counter }})"><span
                                                                        class="glyphicon glyphicon-trash"></span> </a>
                                                            </td>
                                                        </tr>
                                                        @php $counter++ @endphp
                                                    @empty
                                                        <tr id="RemoveRows1">
                                                            <td class="text-center">1</td>
                                                            <td>
                                                                <select style="width: 100%;"
                                                                    class="form-control requiredField select2 item_id"
                                                                    name="item_id[]" id="item_id1"
                                                                    onchange="get_stock_qty(this.value,'1');get_uom_name_by_item_id(this.value, 1)">
                                                                    <option value="">Select Raw Material</option>
                                                                    @foreach ($raw_material as $key => $value)
                                                                        <option value="{{ $value->id }}"
                                                                            data-pack_size="{{ $value->pack_size ?? 1 }}">
                                                                            {{ $value->item_code . ' - ' . $value->sub_ic }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="uom[]"
                                                                    id="uom1" readonly>
                                                            </td>
                                                            <td class="text-center">
                                                                <input readonly class="form-control instock zerovalidate"
                                                                    name="instock[]" type="text" value=""
                                                                    id="instock1" />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text"
                                                                    class="form-control requiredField required_qty"
                                                                    name="required_qty[]" id="required_qty1"
                                                                    onkeyup="validateRowQuantity(1); calculateTotalQuantity()">
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="javascript:;" class="btn btn-sm btn-danger"
                                                                    onclick="RemoveSection(1)"><span
                                                                        class="glyphicon glyphicon-trash"></span> </a>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row mb-20">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <button type="submit" class="btn btn-success">
                                            Update
                                        </button>
                                        <a href="{{ url('far_production/viewProductionMixingList') }}?m={{ $m }}"
                                            class="btn btn-primary">Back to list</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".btn-success").click(function(e) {
            var formSection = new Array();
            var val;
            $("input[name='formSection[]']").each(function() {
                formSection.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of formSection) {
                jqueryValidationCustom();

                let isValidQty = true;
                $('.required_qty').each(function() {
                    var id = $(this).attr('id');
                    var num = id.replace('required_qty', '');
                    if (!validateRowQuantity(num)) {
                        isValidQty = false;
                        return false;
                    }
                });

                if (validate == 0 && isValidQty) {
                    $('#saveMixing').submit();
                } else {
                    return false;
                }
            }
        });

        var Counter = {{ $mixtureData->count() > 0 ? $mixtureData->count() + 1 : 2 }};

        function AddMoreDetails() {
            Counter++;
            $("#tableData").append(`
                                      <tr id="RemoveRows${Counter}">
                                        <td class="text-center">${Counter}</td>
                                       <td>
                                            <select style="width: 100%;"
                                                class="form-control requiredField select2 item_id"
                                                name="item_id[]" id="item_id${Counter}"
                                                onchange="get_stock_qty(this.value,${Counter});get_uom_name_by_item_id(this.value, ${Counter})">
                                                <option value="">Select Raw Material</option>
                                                @foreach ($raw_material as $key => $value)
                                                        <option value="{{ $value->id }}" data-pack_size="{{ $value->pack_size ?? 1 }}">
                                                        {{ $value->item_code . ' - ' . $value->sub_ic }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                          <input type="text" class="form-control" name="uom[]" id="uom${Counter}" readonly>
                                        </td>
                                        <td class="text-center">
                                          <input readonly   class="form-control instock"  type="text" name="instock[]" id="instock${Counter}"/>
                                        </td>
                                        <td class="text-center">
                                          <input type="text" class="form-control requiredField required_qty" name="required_qty[]" id="required_qty${Counter}" onkeyup="validateRowQuantity(${Counter}); calculateTotalQuantity()">
                                        </td>
                                        <td class="text-center">
                                          <a href="javascript:;" class="btn btn-sm btn-danger" onclick="RemoveSection(${Counter})"><span class="glyphicon glyphicon-trash"></span> </a>
                                        </td>
                                      </tr>`
            );

            $('.select2').select2();
        }

        function RemoveSection(Row) {
            let totalRows = $("#tableData tr").length;
            if (totalRows <= 1) {
                alert("At least 1 row is required.");
                return;
            }
            $("#RemoveRows" + Row).remove();
        }

        function get_stock_qty(warehouse, number) {
            var warehouse = null;
            var myArray = $('#item_id' + number).find(":selected").val();
            var item = myArray.split(",");
            var batch_code = 0;
            $.ajax({
                url: '<?php echo url('/') ?>/pdc/get_stock_location_wise?batch_code=' + batch_code,
                type: "GET",
                data: {
                    warehouse: warehouse,
                    item: item[0]
                },
                success: function(data) {
                    data = data.split('/');
                    var stock = parseFloat(data[0]) || 0;
                    $('#instock' + number).val(stock);
                    if (stock <= 0) {
                        $("#" + item).css("background-color", "red");
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Stock not available',
                                text: 'Selected item has 0 quantity in stock.',
                            });
                        } else {
                            alert('Selected item has 0 quantity in stock.');
                        }
                        $('#required_qty' + number).val('');
                    } else {
                        $("#" + item).css("background-color", "");
                    }
                    validateRowQuantity(number);
                }
            });
        }

        function validateRowQuantity(number) {
            var itemSelected = $('#item_id' + number).val();
            if (!itemSelected) {
                return true;
            }
            var instockinbag = parseFloat($('#instock' + number).val()) || 0;
            var bagsize = parseFloat(
                $('#item_id' + number).find(':selected').data('pack_size')
            ) || 0;
            var qtyField = $('#required_qty' + number);
            var entered = parseFloat(qtyField.val()) || 0;
            if (bagsize <= 0) {
                if (entered > 0) {
                    qtyField.val('');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pack size missing',
                            text: 'Pack size (subitem.pack_size) is not configured for the selected raw material.'
                        });
                    } else {
                        alert('Pack size (subitem.pack_size) is not configured for the selected raw material.');
                    }
                    return false;
                }
                return true;
            }
            var instock = instockinbag * bagsize;
            if (!entered) {
                return true;
            }
            if (instock === 0 && entered > 0) {
                qtyField.val('');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stock not available',
                        text: 'You cannot consume quantity because stock is 0.',
                    });
                } else {
                    alert('You cannot consume quantity because stock is 0.');
                }
                return false;
            }
            if (entered > instock) {
                qtyField.val(instock ? instock : '');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid quantity',
                        text: 'Required quantity cannot be greater than available stock (' + instock + ').',
                    });
                } else {
                    alert('Required quantity cannot be greater than available stock (' + instock + ').');
                }
                return false;
            }
            return true;
        }

        function calculateTotalQuantity() {
            let totalQuantity = 0;
            $('.required_qty').each(function() {
                const value = parseFloat($(this).val()) || 0;
                totalQuantity += value;
            });
            $('#qty').val(totalQuantity.toFixed(2));
        }
    </script>

    <script type="text/javascript">
        $(".select2").select2();

        function get_uom_name_by_item_id(ItemId, num = null) {
            $.ajax({
                url: '{{ url('pdc/get_uom_name_by_item_id') }}',
                type: 'Get',
                data: {
                    ItemId: ItemId
                },
                success: function(response) {
                    if (num == null) {
                        $('#uom').val(response)
                    } else {
                        $('#uom' + num).val(response)
                    }
                }
            });
        }

        $(document).ready(function() {
            var finishId = $('#finish_item_id').val();
            if (finishId) {
                get_uom_name_by_item_id(finishId);
            }
            @php $ridx = 1 @endphp
            @foreach ($mixtureData as $md)
                @if ($md->item_id)
                    get_uom_name_by_item_id({{ $md->item_id }}, {{ $ridx }});
                    get_stock_qty(null, {{ $ridx }});
                @endif
                @php $ridx++ @endphp
            @endforeach
        });
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection
