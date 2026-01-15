<?php 
use App\Helpers\CommonHelper;

echo "<pre>";


$count = 1;
?>
<tr>
    <th>item</th>
    <th>Required Qty </th>
    <th>Order Qty</th>
</tr>
@foreach($receipe_data  as $data)

<tr class="row_recipe">
    <td class="hide">
        <select readonly name="category[]"
            @if(!empty($data->subItem)) {{ $data->subItem->id }}
                    onchange="get_sub_item_by_id(this,{{$count}},{{ $data->subItem->id }})" 
            @else
                    onchange="get_sub_item_by_id(this,{{$count}},0)" 
            @endif
            
        class="form-control category" id="">
            
            <option  value="">Select Category</option>
            @foreach(CommonHelper::get_sub_category()->get() as $sub_category)
            @if($data->category_id == $sub_category->id)
                    <option @if($data->category_id == $sub_category->id) selected  @endif value="{{$sub_category->id}}">{{$sub_category->sub_category_name}}</option>
                    @endif
            @endforeach
          </select> 
    </td>
    <td>
        <select  class="form-control select2 item_id" name="item_id[]" id="item_id{{$count}}">
            <option value="">Select Item</option>
            @foreach ($raw_material as $key => $value)
                <option @if($data->subItem->id == $value->id) selected @endif value="{{ $value->id }}"> {{ $value->item_code.' - '.$value->sub_ic }} </option>
            @endforeach
        </select>    
    </td>
    <td> 
        <input type="text" readonly class="form-control reqired_qty" name="required_qty[]" value="{{$data->category_total_qty}}" id="">
    </td>
    <td>
        <input type="text" readonly  class="form-control requested_qty" name="requested_qty[]" id="">
        <input type="hidden" class="recipe_qty" name="recipe_qty[]" value="{{ $receipe->qty }}" />
    </td>
</tr>
<?php
$count++;
?>
@endforeach

<script>
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
  });
</script>






