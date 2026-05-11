@extends('layouts.default')
@section('content')
@include('select2')

<div class="container-fluid">
    <div class="well_N">
        <div class="dp_sdw">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="headquid">
                        <h2 class="subHeadingLabelClass">Edit Production Wastage</h2>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('FarProduction.WastageUpdate') }}?m={{ request('m', $m) }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ $wastage->id }}">
                <input type="hidden" name="pageType" value="{{ request('pageType') }}">
                <input type="hidden" name="parentCode" value="{{ request('parentCode') }}">
                <input type="hidden" name="m" value="{{ request('m', $m) }}">

                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label class="sf-label">Production Order</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="production_order_id" class="form-control select2 requiredField" required>
                                    <option value="">Select Production Order</option>
                                    @foreach($productionOrders as $order)
                                        <option value="{{ $order->id }}" {{ old('production_order_id', $wastage->production_order_id) == $order->id ? 'selected' : '' }}>
                                            {{ $order->pr_no }} {{ $order->ref_no ? ' -- ' . $order->ref_no : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label class="sf-label">Process</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="process" class="form-control select2 requiredField" required>
                                    <option value="">Select Process</option>
                                    @foreach($processes as $key => $label)
                                        <option value="{{ $key }}" {{ old('process', $wastage->process) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label class="sf-label">Wastage Date</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="wastage_date" class="form-control requiredField" value="{{ old('wastage_date', $wastage->wastage_date) }}" required>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>

                        <div class="table-responsive">
                            <table class="userlittab table table-bordered sf-table-list">
                                <thead>
                                    <tr>
                                        <th class="text-center col-sm-5">Item</th>
                                        <th class="text-center col-sm-2">Qty (KG)</th>
                                        <th class="text-center col-sm-4">Remarks</th>
                                        <th class="text-center col-sm-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="wastageRows">
                                    @forelse($wastageDetails as $key => $detail)
                                        <tr id="row_{{ $key + 1 }}">
                                            <td>
                                                <select name="item_id[]" id="item_id_{{ $key + 1 }}" class="form-control select2 requiredField" style="width: 100% !important;" required>
                                                    <option value="">Select Item</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" {{ $detail->item_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->item_code }} -- {{ $item->sub_ic }} {{ $item->uom_name ? '(' . $item->uom_name . ')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="any" min="0.01" name="qty[]" id="qty_{{ $key + 1 }}" class="form-control requiredField" value="{{ $detail->qty }}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="remarks[]" id="remarks_{{ $key + 1 }}" class="form-control" value="{{ $detail->ppc }}">
                                            </td>
                                            <td class="text-center">
                                                @if($key == 0)
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="addWastageRow()">
                                                        <span class="glyphicon glyphicon-plus-sign"></span>
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="removeWastageRow({{ $key + 1 }})">
                                                        <span class="glyphicon glyphicon-minus-sign"></span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="row_1">
                                            <td>
                                                <select name="item_id[]" id="item_id_1" class="form-control select2 requiredField" style="width: 100% !important;" required>
                                                    <option value="">Select Item</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" {{ $wastage->item_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->item_code }} -- {{ $item->sub_ic }} {{ $item->uom_name ? '(' . $item->uom_name . ')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="any" min="0.01" name="qty[]" id="qty_1" class="form-control requiredField" value="{{ $wastage->qty }}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="remarks[]" id="remarks_1" class="form-control" value="{{ $wastage->remarks }}">
                                            </td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="addWastageRow()">
                                                    <span class="glyphicon glyphicon-plus-sign"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mp-20 text-right">
                    <a href="{{ url('far_production/viewProductionWastageList?pageType='.request('pageType').'&&parentCode='.request('parentCode').'&&m='.request('m', $m)) }}" class="btn btn-default">Back</a>
                    <button type="submit" class="btnn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    var rowCounter = {{ max(1, $wastageDetails->count()) }};

    function addWastageRow() {
        rowCounter++;
        $('#wastageRows').append(`
            <tr id="row_${rowCounter}">
                <td>
                    <select name="item_id[]" id="item_id_${rowCounter}" class="form-control select2 requiredField" style="width: 100% !important;" required>
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->item_code }} -- {{ $item->sub_ic }} {{ $item->uom_name ? '(' . $item->uom_name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step="any" min="0.01" name="qty[]" id="qty_${rowCounter}" class="form-control requiredField" required>
                </td>
                <td>
                    <input type="text" name="remarks[]" id="remarks_${rowCounter}" class="form-control">
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="removeWastageRow(${rowCounter})">
                        <span class="glyphicon glyphicon-minus-sign"></span>
                    </a>
                </td>
            </tr>
        `);
        $('.select2').select2();
    }

    function removeWastageRow(rowId) {
        $('#row_' + rowId).remove();
    }
</script>
@endsection
