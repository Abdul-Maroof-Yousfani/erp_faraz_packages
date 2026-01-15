<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$receipe = '';
$count = 1;
?>

@foreach($production_data as $val)
    <?php      

    $production_bom = DB::connection('mysql2')->table('production_bom')
    ->where([['finish_goods','=', $val->item_id],['color','=', $val->color]])->get(); 
    ?>
    <tbody id="row_of_data{{$count}}" class="row_of_data receipe_main recipe_details">
      <tr style="background-color: darkgray">
        <th class="text-center">
          <input type="checkbox" @if($production_bom->isEmpty()) checked disabled @endif id="checkbox_{{ $val->item_id }}" onchange="toggleRow(this)" />
              <script>
                toggleRow(document.getElementById('checkbox_{{ $val->item_id }}'));
              </script>
            
        </th>
        <th class="text-center">Product</th>
        <th class="text-center">Color</th>
        <th class="text-center">Order Qty</th>
        <th class="text-center">Start Date</th>
        <th class="text-center">End Date</th>
      </tr>

      @php 
        $item = CommonHelper::get_item_by_id($val->item_id);
        $uom_name = CommonHelper::get_uom($val->item_id);
      @endphp

      <tr>
        <td class="text-center"></td>
        <td class="text-center">{{ $item->item_code.' -- '.$item->sub_ic}}</td>
        <td class="text-center">{{ $val->color}}</td>
        <td class="text-center">
          {{$val->quantity}}
          <input type="hidden" name="work_data_id[]" value="{{ $val->id}}" />
          <input type="hidden" value="{{$val->item_id}}" name="finish_good[]" />
          <input type="hidden" value="{{$val->id}}"  name="production_request_data_id[]" />
          <input type="hidden" class="order_qty order_qty" value="{{$val->quantity}}" name="order_qty[]" />
          <input type="hidden" class="uom_name" value="{{$uom_name}}" name="uom_name[]" />
          <input type="hidden" class="color" value="{{ $val->color }}" name="color[]" />
          @php
            $item_id = $val->item_id;
          @endphp
        </td>
        <td class="text-center">
          <input type="date" name="start_date[]" class="form-control requiredField start_date" value="{{ date('Y-m-d') }}" id="start_date{{$count}}"/>
        </td>
        <td class="text-center">
          <input type="date" name="delivery_date[]" class="form-control requiredField delivery_date" id="delivery_date{{$count}}"/>
        </td>
      </tr>

      <tr>
        <td>
          <label for="">Receipe</label>
          <select style="width: 100%;" class="form-control select2 receip_id cc{{$count}}" onchange="getReceipeData(this,{{$count}} , 'receipe{{$count}}');" name="receipt_id[]" id="">
            @foreach($production_bom as $bom)
              <option value="{{$bom->id}}">{{$bom->receipe_name}}</option>
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
<input type="hidden" name="order_no" value="{{$production->pr_no}}" id="order_no" />

<script>
//   function getReceipeData(instance , count , class_name) {
//     let abc = instance;
//     let id = instance.value;
//     let parentRecipeDetails = instance.closest('#row_of_data'+count);
//     let receipe1 = parentRecipeDetails.querySelector('.receipe'+count);
//     receipe1.innerHTML = ''; // Clear previous content
//     console.log(class_name  , 'class_name');
//     fetch('<?php echo url('/')?>/selling/getReceipeData?id=' + id + '&class_name='+class_name)
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Network response was not ok');
//             }
//             return response.text();
//         })
//         .then(responsedata => {
//             receipe1.innerHTML = responsedata;
            
//             let order_qty = parentRecipeDetails.closest('#row_of_data'+count).querySelector('.order_qty').value;
//             parentRecipeDetails.querySelectorAll('.row_recipe').forEach(row => {
//                 let required_qty = row.querySelector('.reqired_qty').value;
//                 let total = (parseFloat(order_qty) / 1000) * parseFloat(required_qty);
//                 row.querySelector('.requested_qty').value = total;
//             });
//         })
//         .catch(error => {
//             console.error('Error fetching recipe data:', error);
//         });
// }
</script>