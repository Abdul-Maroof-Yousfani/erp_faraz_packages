<?php

use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$accType = Auth::user()->acc_type;
if ($accType == 'client') { 
  $m = $_GET['m'];
} else { 
  $m = Auth::user()->company_id;
} 
$counter = 1;
$count = 1;
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
            <h2 class="subHeadingLabelClass">Edit Mixture Form</h2>
          </div>
          
          <div class="lineHeight">&nbsp;</div>

          <?php echo Form::open(array('url' => 'recipe/UpdateRecipe?m=' . $m . '', 'id' => 'saveExpense')); ?>
          <input type="hidden" name="formSection[]" id="formSection" value="1">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="recordId" value="{{ $recipe->id }}">

          <div class="panel">
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">Item Finish Goods</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <select
                        class="form-control select2 requiredField"
                        name="finish_goods"
                        id="finish_goods"
                        onchange="get_uom_name_by_item_id(this.value)"
                      >
                        <option>Select Finish Goods</option>
                        @foreach ($sub_item as $key => $value)
                        <option @if( $value->id  == $recipe->finish_goods) selected @endif  data-item-code="{{ $value->item_code }}" value="{{ $value->id }}">
                          {{ $value->item_code.' - '.$value->sub_ic }}
                        </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">uom</label>
                      <input
                        class="form-control"
                        type="text"
                        name="uom"
                        id="uom"
                        value="{{ CommonHelper::get_uom($recipe->finish_goods) }}"
                        readonly
                      />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">Color</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <select class="form-control select2 requiredField" name="color" id="color" onchange="generateRecipeName()">
                        <option value="">Select Color</option>
                        @foreach ($color as $key => $value)
                          <option @if($recipe->color == $value->color) selected @endif value="{{ $value->color }}"> {{ $value->color }} </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">Quantity</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <input
                        class="form-control requiredField"
                        type="text"
                        name="qty"
                        id="qty"
                        value=" {{ $recipe->qty }}" readonly
                      />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label for="">Recipe Name</label>
                      <input type="text" class="form-control requiredField" name="receipe_name" id="receipe_name" value="{{ $recipe->receipe_name }}">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label for="">Formulation Number</label>
                      <input type="text" class="form-control requiredField" name="formulation_no" id="formulation_no" value="{{ $recipe->formulation_no }}" readonly>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label class="sf-label">Description</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <textarea
                        name="description"
                        id="description"
                        rows="4"
                        cols="50"
                        style="resize: none; font-size: 11px"
                        class="form-control requiredField"
                        value=" {{ $recipe->description }}"
                      >{{ $recipe->description }}</textarea>
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
                        @foreach($recipeData as $key => $val)
                          <tr id="RemoveRows{{ $count }}">
                            <td class="text-center">{{ $counter++ }}</td>
                            <td>
                              <select style="width: 100%;" class="form-control requiredField select2 item_id" name="item_id[]" id="item_id{{$count}}" onchange="get_uom_name_by_item_id(this.value, '{{ $count }}')">
                                <option value="">Select Raw Material</option>
                                @foreach ($raw_material as $key => $value)
                                  <option @if($val->item_id == $value->id) selected @endif value="{{ $value->id }}"> {{ $value->item_code.' - '.$value->sub_ic }} </option>
                                @endforeach
                              </select>
                            </td>
                            <td class="text-center">
                              <input type="text" class="form-control" name="uom[]" id="uom{{$count}}" readonly>
                            </td>
                            <td class="text-center">
                              <input type="text" class="form-control requiredField required_qty" name="required_qty[]" id="required_qty{{$count}}" value="{{ $val->category_total_qty }}" onkeyup="calculateTotalQuantity()" />
                            </td>
                            <td class="text-center">
                              @if($count == 1)
                                <a href="javascript:;" class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span> </a>
                              @else
                              <a href="javascript:;" class="btn btn-sm btn-danger" onclick="RemoveSection('{{ $count }}')"><span class="glyphicon glyphicon-trash"></span> </a>
                              @endif
                            </td>
                          </tr>
                          @php $count++ @endphp
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="lineHeight">&nbsp;</div>
              <div class="row mb-20">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

                    <button type="submit" id="reset" class="btn btn-success">
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
        <?php echo Form::close(); ?>
      </div>
    </div>
  </div>
</div>
<script>

$(".btn-success").click(function(e){
      var formSection = new Array();
      var val;
      $("input[name='formSection[]']").each(function(){
        formSection.push($(this).val());
    });
      var _token = $("input[name='_token']").val();
      for (val of formSection) {
          jqueryValidationCustom();
          if(validate == 0){
              $('#saveExpense').submit();
          }  else {
              return false;
          }
      }
  });

document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.item_id').forEach(function(selectElement) {
    const selectedValue = selectElement.value;
    const rowId = selectElement.id.replace('item_id', '');

    if (selectedValue) {
      get_uom_name_by_item_id(selectedValue, rowId);
    }
    
  });
});

function generateRecipeName() {
    var finishGoods = document.getElementById('finish_goods');
    var color = document.getElementById('color');
    
    var finishGoodsSelected = finishGoods.options[finishGoods.selectedIndex];
    var colorSelected = color.value;

    if (finishGoodsSelected && colorSelected) {
        var itemCode = finishGoodsSelected.getAttribute('data-item-code');
        
        var recipeName = 'F-001 ' + itemCode + ' ' + colorSelected;

        document.getElementById('receipe_name').value = recipeName;
    }
}

  var Counter = {{$count}};

  function AddMoreDetails() {
    Counter++;
    $("#tableData").append(`
      <tr id="RemoveRows${Counter}">
        <td class="text-center">${Counter}</td>
        <td>
          <select style="width: 100%;" class="form-control requiredField select2 item_id" name="item_id[]" id="item_id${Counter}" onchange="get_uom_name_by_item_id(this.value, ${Counter})">
            <option value="">Select Raw Material</option>
            @foreach ($raw_material as $key => $value)
              <option value="{{ $value->id }}"> {{ $value->item_code.' - '.$value->sub_ic }} </option>
            @endforeach
          </select>
        </td>
        <td class="text-center">
          <input type="text" class="form-control" name="uom[]" id="uom${Counter}" readonly>
        </td>
        <td class="text-center">
          <input type="text" class="form-control requiredField required_qty" name="required_qty[]" id="required_qty${Counter}" onkeyup="calculateTotalQuantity()" />
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

  function  get_sub_item_by_id(instance,num,value)
	{

    $('#item_id'+num).empty();

		var category= instance.value;
	
    $(instance).closest('.main').find('#item_id').empty();
		$.ajax({
			url: '{{ url("/getSubItemByCategory") }}',
			type: 'Get',
			data: {category: category},
			success: function (response) {
                $('#item_id'+num).append(response);

                if(value != 0)
                {
                  console.log(value)
                  setTimeout(() => {
                    $('#item_id'+num).val(value).select2();
                  }, 2000);

                }
			}
		});
	}

  $(document).ready(function (){
    $('.category').trigger('change')
    $('.required_qty').number(true,2);
  });

  function get_uom_name_by_item_id(ItemId, num=null) {

$.ajax({
  url: '{{ url("pdc/get_uom_name_by_item_id") }}',
  type: 'Get',
  data: { ItemId: ItemId },
  success: function (response) {
    if(num == null) {
      $('#uom').val(response)
    } else {
      $('#uom'+num).val(response)
    }

    
  }
});
}
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection
