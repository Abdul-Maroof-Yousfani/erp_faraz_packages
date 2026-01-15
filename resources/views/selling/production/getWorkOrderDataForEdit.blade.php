<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$receipe='';
$count = 1;
?>

@foreach($prodtion_datas as $prodtion_data)

<?php

  $production_bom =  DB::connection('mysql2')->table('production_bom')
  ->where([['finish_goods','=', $prodtion_data->finish_goods_id],['color','=', $prodtion_data->color]])->get();

?>
<tbody id="row_of_data{{$count}}" class="row_of_data receipe_main recipe_details">
        <tr style="background-color: darkgray">
          <th class="text-center"><input type="checkbox" onchange="toggleRow(this)" /></th>
          <th class="text-center">Product</th>
          <th class="text-center">Color</th>
          <th class="text-center">Order Qty</th>
          <th class="text-center">Start Date</th>
          <th class="text-center">End Date</th>
        </tr>

      @php 
      $item = CommonHelper::get_item_by_id($prodtion_data->finish_goods_id);
      $uom_name = CommonHelper::get_uom($prodtion_data->finish_goods_id);
      @endphp

  <tr>
    <td class="text-center"></td>
    <td class="text-center">{{ $item->item_code.' -- '.$item->sub_ic}}</td>
    <td class="text-center">{{ $prodtion_data->color}}</td>
    <td class="text-center">
      {{$prodtion_data->planned_qty}}
      <input type="hidden" name="work_data_id[]" value="{{ $prodtion_data->id}}" />
      <input type="hidden" value="{{$prodtion_data->finish_goods_id}}" name="finish_good[]" />
      <input type="hidden" value="{{$prodtions->sales_order_id}}" name="sale_data_id[]" />
      <input type="hidden" class="order_qty order_qty" value="{{$prodtion_data->planned_qty}}" name="order_qty[]" />
      <input type="hidden" class="uom_name" value="{{$uom_name}}" name="uom_name[]" />
      <input type="hidden" class="color" value="{{ $prodtion_data->color }}" name="color[]" />
      @php
      $item_id = $prodtion_data->finish_goods_id;
      @endphp
    </td>
    <td class="text-center">
      <input type="date" value="{{$prodtion_data->start_date}}" name="start_date[]" class="form-control" id="start_date{{$count}}" />
    </td>
    <td class="text-center">
      <input type="date" value="{{$prodtion_data->delivery_date}}" name="delivery_date[]" class="form-control" id="delivery_date{{$count}}" />
    </td>
  </tr>

  <tr>
    <td>
      <label for="">Receipe</label>
      <select style="width: 100%;" class="form-control select2 receip_id cc{{$count}}" onchange="getReceipeData(this,{{$count}} , 'receipe{{$count}}');" name="receipt_id[]" id="">
        @foreach($production_bom as $bom)
        
          <option @if($prodtion_data->receipt_id == $bom->id) selected @endif value="{{$bom->id}}">{{$bom->receipe_name}}</option>
        
        @endforeach
    
      </select>
    </td>
  </tr>
  <tr class="receipe1 receipe{{$count}}">

  </tr>
    
</tbody>

<script>
    // Get all select elements with the class 'receip_id'
    const selectElements = document.querySelector('.cc{{$count}}');

    // Create a new 'change' event
    const event = new Event('change');

    // Dispatch the 'change' event on the current select element
    selectElements.dispatchEvent(event);

    </script>
@php
    $count++;
  @endphp
@endforeach
<input type="hidden" name="customer_name" value="" id="customer_name" />
<input type="hidden" name="customer_id" value="" id="customer_id" />
<input type="hidden" name="order_no" value="" id="order_no" />

