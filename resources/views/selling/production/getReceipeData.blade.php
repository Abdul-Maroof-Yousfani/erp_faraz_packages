<?php 
use App\Helpers\CommonHelper;

$count = 1;
?>
<tr>
    <th class="hide">Category</th>
    <th>item</th>
    <th>Qty (Per unit)</th>
    <th >Order Qty</th>
</tr>
<input type="hidden" name="production_bom_id[]" value="{{ $receipe->id }}" />
@foreach($receipe_data  as $data)
<tr class="row_recipe" >
    <td class="hide">
        <select name="category[]" class="form-control category abc{{$count}}" id="">
            @if(!empty($data->subItem)) {{ $data->subItem->id }}
                    onchange="get_sub_item_by_id(this,{{$count}},{{ $data->subItem->id }})" 
            @else
                    onchange="get_sub_item_by_id(this,{{$count}},0)" 
            @endif
            
            {{-- <option  value="">Select Category</option> --}}
            @foreach(CommonHelper::get_sub_category()->get() as $sub_category)
                @if($data->category_id == $sub_category->id) 
                    <option  {{ $data->category_id == $sub_category->id ? 'selected' : '' }} value="{{$sub_category->id}}">{{$sub_category->sub_category_name}}</option>
                @endif 
            @endforeach
        </select>
    </td>
    <td>
        <select style="width: 100%;" class="form-control select2 item_id requiredField" name="item_id{{ $receipe->id }}[]" id="item_id{{$count}}">
            <option value="">Select Item</option>
            @foreach ($raw_material as $key => $value)
                <option @if($data->subItem->id == $value->id) selected @endif value="{{ $value->id }}"> {{ $value->item_code.' - '.$value->sub_ic }} </option>
            @endforeach
        </select>
    </td>
    <td> 
        <input type="number" readonly class="form-control reqired_qty" name="required_qty[]" value="{{$data->category_total_qty}}" id="">
    </td>
    <td>
        <input type="number" readonly  class="form-control requested_qty requiredField" name="requested_qty[]" id="requested_qty{{$count}}">
        <input type="hidden" class="recipe_qty" name="recipe_qty[]" value="{{ $receipe->qty }}" />
        
    </td>
</tr>

<script>
  //$('.{{$class_name}} .abc{{$count}}').trigger('change');
</script>
<?php
$count++;
?>
@endforeach

<script>
  $(document).ready(function (){
    $('.select2').select2();
  });
</script>






