<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th colspan="6" class="text-center">Recipe Data List</th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <th class="text-center">Item</th>
                <th class="text-center">Uom<span class="rflabelsteric"><strong>*</strong></span></th>
                <th class="text-center">Actual Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                <th class="text-center">Total Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                <th class="text-center">Rate Per Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                <th class="text-center">Total Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="recipeData">
            @foreach ($recipeData as $key => $data)
                <tr id="RemoveRows{{ $key }}">
                    <td>
                        <select class="form-control select2 items" name="item_id[]" id="item_id{{ $key }}"
                            onchange="selectSubItem(1)">
                            <option>Select Item</option>
                            @foreach (App\Helpers\CommonHelper::get_all_subitem_by_demand_type(2) as $value)
                                <option value="{{ $value->id }}" @if ($data->item_id == $value->id) selected @endif
                                    data-uom="{{ $value->uomData->uom_name }}"
                                    data-stock="{{ App\Helpers\ReuseableCode::get_stock($value->id, 5, 0, 0) }}">{{ $value->sub_ic }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="recipeDataId[]" value="{{ $data->id }}">
                    </td>
                    <td>
                        <input readonly type="text" class="form-control" name="uom_id[]"
                            id="uom_id{{ $key }}" value="{{ $data->subItem->uomData->uom_name }}">
                    </td>
                    <td>
                        <input type="number" class="form-control requiredField ActualQty" name="actual_qty[]"
                            id="actual_qty{{ $key }}" placeholder="ACTUAL QTY" value="{{ $data->quantity }}"
                            onkeyup="qtCal()">
                    </td>
                    <td>
                        <input readonly type="number" class="form-control requiredField totalQuantity"
                            name="total_qty[]" id="total_qty{{ $key }}" placeholder="Total QTY"
                            value="">
                    </td>
                    <td>
                        <input readonly type="number" class="form-control requiredField rate_per_qty"
                            name="rate_per_qty[]" id="rate_per_qty{{ $key }}" placeholder="Rate Per Qty"
                            value="{{ $data->rate }}">
                    </td>
                    <td>
                        <input readonly type="number" class="form-control requiredField total_rate"
                            name="total_rate[]" id="total_rate{{ $key }}" placeholder="Total Rate"
                            value="">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" id="BtnRemove" readonly
                            onclick="RemoveSection({{ $key }})"> -
                        </button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-right">Total</td>
                <td colspan="2" class="text-left" id="totalrate">0</td>
                <input type="hidden" name="totalrateInput" id="totalrateInput">
            </tr>
        </tbody>
    </table>
</div>


<script>
    function RemoveSection(Row) {
        $('#RemoveRows' + Row).remove();
    }
</script>
