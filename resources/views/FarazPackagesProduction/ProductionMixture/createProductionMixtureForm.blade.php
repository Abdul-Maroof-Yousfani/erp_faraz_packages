<?php

use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
$counter = 1;
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
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
                            <h2 class="subHeadingLabelClass">Add Production Mixture</h2>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        {{ Form::open(array('url' => 'far_prod/addProductionMixingDetail?m=' . $m . '', 'id' => 'saveMixing')) }}
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
                                                            value="{{ $value->id }}">
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
                                                    id="mixing_no" value="{{ $pm_no }}" readonly>
                                            </div>
                                            
                                            
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Mixing Date</label>
                                                <input type="date" class="form-control" name="mixing_date" id="mixing_date"
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Quantity</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input class="form-control requiredField" type="text" name="qty" id="qty"
                                                    name="finish_qty" value="" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Production Order</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control select2 requiredField"
                                                    name="production_order_id" id="production_order_id">
                                                    <option value="">Select Production Order</option>
                                                    @foreach ($production_order as $key => $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->pr_no }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Mixture Machine</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control select2 requiredField"
                                                    name="mixture_machine_id" id="mixture_machine_id">
                                                    <option value="">Select Mixture Machine</option>
                                                    @foreach ($mixture_machines as $key => $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>



                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"></span>
                                                <textarea name="description" id="description" rows="4" cols="50"
                                                    style="resize: none; font-size: 11px" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="userlittab table table-bordered sf-table-list">
                                                <thead>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    {{-- <th class="text-center col-sm-3">Category</th> --}}
                                                    <th class="text-center col-sm-3">Item</th>
                                                    <th class="text-center col-sm-2">UOM</th>
                                                    <th class="text-center col-sm-2">QTY (KG)</th>
                                                    {{-- <th class="text-center col-sm-2">Machine</th> --}}
                                                    <th class="text-center col-sm-1">Action</th>
                                                </thead>
                                                <tbody id="tableData">

                                                    <tr>
                                                        <td class="text-center">{{ $counter++ }}</td>
                                                        <td>
                                                            <select style="width: 100%;"
                                                                class="form-control requiredField select2 item_id"
                                                                name="item_id[]" id="item_id1"
                                                                onchange="get_uom_name_by_item_id(this.value, 1)">
                                                                <option value="">Select Raw Material</option>
                                                                @foreach ($raw_material as $key => $value)
                                                                    <option value="{{ $value->id }}">
                                                                        {{ $value->item_code . ' - ' . $value->sub_ic }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        {{-- <td>
                                                            <select style="width: 100% !important;"
                                                                onchange="get_sub_item('category_id1')" name="category[]"
                                                                id="category_id1"
                                                                class="form-control category select2 requiredField">
                                                                <option value="">Select</option>
                                                                @foreach (CommonHelper::get_all_category() as $category)
                                                                <option value="{{ $category->id }}">
                                                                    {{ $category->main_ic }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select style="width: 100% !important;"
                                                                onchange="get_uom_name_by_item_id(this.value, 1)"
                                                                name="item_id[]" id="item_id1"
                                                                class="form-control requiredField select2">
                                                                <option>Select</option>
                                                            </select>
                                                        </td> --}}
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="uom[]" id="uom1"
                                                                readonly>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text"
                                                                class="form-control requiredField required_qty"
                                                                name="required_qty[]" id="required_qty1"
                                                                onkeyup="calculateTotalQuantity()">
                                                        </td>
                                                        {{-- <td class="text-center">

                                                            <select style="width: 100% !important;" name="machine_id[]"
                                                                id="machine_id1" class="form-control requiredField select2">
                                                                <option value="">Select</option>
                                                                @foreach($machines as $val)
                                                                <option value="{{$val->id}}">
                                                                    {{ $val->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </td> --}}

                                                        <td class="text-center">
                                                            <a href="javascript:;" class="btn btn-sm btn-primary"
                                                                onclick="AddMoreDetails()"><span
                                                                    class="glyphicon glyphicon-plus-sign"></span> </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row mb-20">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <button type="submit" class="btn btn-success">
                                            Submit
                                        </button>
                                        <button type="reset" id="reset" class="btn btn-primary">
                                            Clear Form
                                        </button>
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

        $(".btn-success").click(function (e) {
            var formSection = new Array();
            var val;
            $("input[name='formSection[]']").each(function () {
                formSection.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of formSection) {
                jqueryValidationCustom();
                if (validate == 0) {
                    $('#saveMixing').submit();
                } else {
                    return false;
                }
            }
        });

        var Counter = {{ isset($copied_recipe_data) && $copied_recipe_data->count() > 0 ? $copied_recipe_data->count() + 1 : 2 }};

        function AddMoreDetails() {
            Counter++;
            $("#tableData").append(`
                      <tr id="RemoveRows${Counter}">
                        <td class="text-center">${Counter}</td>
                       <td>
                            <select style="width: 100%;"
                                class="form-control requiredField select2 item_id"
                                name="item_id[]" id="item_id${Counter}"
                                onchange="get_uom_name_by_item_id(this.value, ${Counter})">
                                <option value="">Select Raw Material</option>
                                @foreach ($raw_material as $key => $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->item_code . ' - ' . $value->sub_ic }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                          <input type="text" class="form-control" name="uom[]" id="uom${Counter}" readonly>
                        </td>
                        <td class="text-center">
                          <input type="text" class="form-control requiredField required_qty" name="required_qty[]" id="required_qty${Counter}" onkeyup="calculateTotalQuantity()">
                        </td>

                        <td class="text-center">
                          <a href="javascript:;" class="btn btn-sm btn-danger" onclick="RemoveSection(${Counter})"><span class="glyphicon glyphicon-trash"></span> </a>
                        </td>
                      </tr>`
            );

            $('.select2').select2();
        }

        function RemoveSection(Row) {
            $("#RemoveRows" + Row).remove();
            Counter--;
        }



        function calculateTotalQuantity() {
            let totalQuantity = 0;

            $('.required_qty').each(function () {
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
                url: '{{ url("pdc/get_uom_name_by_item_id") }}',
                type: 'Get',
                data: { ItemId: ItemId },
                success: function (response) {
                    if (num == null) {
                        $('#uom').val(response)
                    } else {
                        $('#uom' + num).val(response)
                    }


                }
            });
        }

        // Populate UOM for copied items on page load
        $(document).ready(function () {
            @if(isset($copied_recipe_data) && $copied_recipe_data->count() > 0)
                @foreach($copied_recipe_data as $key => $item)
                    @if($item->subItem && $item->subItem->uom)
                        get_uom_name_by_item_id({{ $item->item_id }}, {{ $key + 1 }});
                    @endif
                @endforeach
                // Recalculate total quantity for copied items
                calculateTotalQuantity();
            @endif
                  });
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection