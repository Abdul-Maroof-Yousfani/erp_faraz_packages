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
            <h2 class="subHeadingLabelClass">Add Formulation</h2>
          </div>
          <div class="lineHeight">&nbsp;</div>
          {{ Form::open(array('url' => 'recipe/insertRecipe?m=' . $m . '', 'id' => 'saveExpense')) }}
          <input type="hidden" name="formSection[]" id="formSection" value="1">
          <div class="panel">
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">Item Finish Goods</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <select class="form-control select2 requiredField" name="finish_goods" id="finish_goods"
                        onchange="get_uom_name_by_item_id(this.value)">
                        <option value="">Select Finish Goods</option>
                        @foreach ($sub_item as $key => $value)
              <option data-item-code="{{ $value->item_code }}" value="{{ $value->id }}">
                {{ $value->item_code . ' - ' . $value->sub_ic }}
              </option>
            @endforeach
                      </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">uom</label>
                      <input class="form-control" type="text" name="uom" id="uom" value="" readonly />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">Color</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <select class="form-control select2 requiredField" name="color" id="color"
                        onchange="generateRecipeName()">
                        <option value="">Select Color</option>
                        @foreach ($color as $key => $value)
              <option value="{{ $value->color }}"> {{ $value->color }} </option>
            @endforeach
                      </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">Quantity</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <input class="form-control requiredField" type="text" name="qty" id="qty" value="" readonly />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label for="">Recipe Name</label>
                      <input type="text" class="form-control requiredField" name="receipe_name" id="receipe_name"
                        readonly>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label for="">Formulation Number</label>
                      <input type="text" class="form-control requiredField" name="formulation_no" id="formulation_no"
                        value="{{ $formulation_no }}" readonly>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label class="sf-label">Description</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <textarea name="description" id="description" rows="4" cols="50"
                        style="resize: none; font-size: 11px" class="form-control requiredField"></textarea>
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
                        <th class="text-center col-sm-3">Item</th>
                        <th class="text-center col-sm-2">UOM</th>
                        <th class="text-center col-sm-2">Required QTY</th>
                        <th class="text-center col-sm-1">Action</th>
                      </thead>
                      <tbody id="tableData">
                        @if(isset($copied_recipe_data) && $copied_recipe_data->count() > 0)
                          @foreach($copied_recipe_data as $key => $item)
                            <tr id="RemoveRows{{ $counter }}">
                              <td class="text-center">{{ $counter }}</td>
                              <td>
                                <select style="width: 100%;" class="form-control requiredField select2 item_id"
                                  name="item_id[]" id="item_id{{ $counter }}" onchange="get_uom_name_by_item_id(this.value, {{ $counter }})">
                                  <option value="">Select Raw Material</option>
                                  @foreach ($raw_material as $raw_key => $raw_value)
                                    <option value="{{ $raw_value->id }}" @if($item->item_id == $raw_value->id) selected @endif> 
                                      {{ $raw_value->item_code . ' - ' . $raw_value->sub_ic }}
                                    </option>
                                  @endforeach
                                </select>
                              </td>
                              <td class="text-center">
                                <input type="text" class="form-control" name="uom[]" id="uom{{ $counter }}" 
                                  value="{{ $item->subItem && $item->subItem->uomData ? $item->subItem->uomData->uom_name : '' }}" readonly>
                              </td>
                              <td class="text-center">
                                <input type="text" class="form-control requiredField required_qty" name="required_qty[]"
                                  id="required_qty{{ $counter }}" value="{{ $item->category_total_qty ?? '' }}" 
                                  onkeyup="calculateTotalQuantity()">
                              </td>
                              <td class="text-center">
                                @if($key == 0)
                                  <a href="javascript:;" class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span
                                      class="glyphicon glyphicon-plus-sign"></span> </a>
                                @else
                                  <a href="javascript:;" class="btn btn-sm btn-danger" onclick="RemoveSection({{ $counter }})"><span 
                                      class="glyphicon glyphicon-trash"></span> </a>
                                @endif
                              </td>
                            </tr>
                            @php $counter++ @endphp
                          @endforeach
                        @else
                          <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td>
                              <select style="width: 100%;" class="form-control requiredField select2 item_id"
                                name="item_id[]" id="item_id1" onchange="get_uom_name_by_item_id(this.value, 1)">
                                <option value="">Select Raw Material</option>
                                @foreach ($raw_material as $key => $value)
                      <option value="{{ $value->id }}"> {{ $value->item_code . ' - ' . $value->sub_ic }}
                      </option>
                    @endforeach
                              </select>
                            </td>
                            <td class="text-center">
                              <input type="text" class="form-control" name="uom[]" id="uom1" readonly>
                            </td>
                            <td class="text-center">
                              <input type="text" class="form-control requiredField required_qty" name="required_qty[]"
                                id="required_qty1" onkeyup="calculateTotalQuantity()">
                            </td>
                            <td class="text-center">
                              <a href="javascript:;" class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span
                                  class="glyphicon glyphicon-plus-sign"></span> </a>
                            </td>
                          </tr>
                        @endif
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
        $('#saveExpense').submit();
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
          <select style="width: 100%;" class="form-control requiredField select2 item_id" name="item_id[]" id="item_id${Counter}" onchange="get_uom_name_by_item_id(this.value, ${Counter})">
            <option value="">Select Raw Material</option>
            @foreach ($raw_material as $key => $value)
          <option value="{{ $value->id }}"> {{ $value->item_code . ' - ' . $value->sub_ic }} </option>
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

  function generateRecipeName() {
    var finishGoods = document.getElementById('finish_goods'); // Access native DOM element
    var color = document.getElementById('color').value; // Access value of color input
    var formulation_no = document.getElementById('formulation_no').value; // Get formulation_no value

    if (finishGoods && finishGoods.selectedIndex !== -1 && color) {
      var finishGoodsSelected = finishGoods.options[finishGoods.selectedIndex]; // Get selected option
      var itemCode = finishGoodsSelected.getAttribute('data-item-code'); // Get data-item-code attribute

      if (itemCode) {
        var recipeName = formulation_no + ' ' + itemCode + ' ' + color;
        document.getElementById('receipe_name').value = recipeName; // Set recipe name
      }
    }
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


  // function get_sub_item_by_id(instance, num) {

  //   $('#item_id' + num).empty();

  //   var category = instance.value;

  //   $(instance).closest('.main').find('#item_id').empty();
  //   $.ajax({
  //     url: '{{ url("/getSubItemByCategory") }}',
  //     type: 'Get',
  //     data: { category: category },
  //     success: function (response) {
  //       $('#item_id' + num).append(response);
  //     }
  //   });
  // }

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
  $(document).ready(function() {
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