<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$receipe='';
?>

<?php      
    
  $production_bom =  DB::connection('mysql2')->table('production_bom')
->where('finish_goods',$item_id)->get(); 

?>
<tbody id="row_of_data" class="row_of_data receipe_main recipe_details">
        <tr style="background-color: darkgray">
              <th></th>
              <th>
                Product
              </th>
              <th>Order Qty</th>
              <th>Start Date</th>
              <th>Delivery Date</th>
           
        </tr>

      @php 
      $item = CommonHelper::get_item_by_id($item_id);
      @endphp

  <tr>
    <td></td>
    <td>{{$item->sub_ic}}</td>
    <td>
      <label for="unlimited">unlimited</label>
        <input checked type="radio" onclick="checkQtyType(event)" name="qty_type" id="unlimited" value="unlimited">
      <label for="limited">limited</label>
        <input type="radio" onclick="checkQtyType(event)" name="qty_type" id="limited" value="limited">
       
      <input
        type="number"
        class="order_qty order_qty"
        step="any"
        name="order_qty"
        onkeyup="qtyCalculation()"
        value="1000000000"
        style="display:none"
      />
      <input type="hidden" id="uom" class="form-control" value="{{ CommonHelper::get_item_by_id($item_id)->uom_name }}" />
  
    </td>
    <td>
      <input type="date" name="start_date" class="form-control" />
    </td>
    <td>
      <input type="date" name="delivery_date" class="form-control" />
    </td>
  </tr>

    <tr>
            <td>
              <label for="">Receipe</label>
              <select class="form-control receip_id" onchange="getReceipeDataOfSingleItem(this);" name="receipt_id" id="">
                @foreach($production_bom as $bom)
              <option value="{{$bom->id}}">{{$bom->receipe_name}}</option>
            @endforeach
            
          </select>
        </td>
    </tr>
    <tr class="receipe1">
  
    </tr>
    
</tbody>

@if( count($production_bom) > 0 )
  <script>
    $('.mr-1').removeAttr('disabled');
  </script>
@else
<script>
    $('.mr-1').attr('disabled','disabled');
  </script>
@endif

<script>

function checkQtyType(e)
{
  let type = e.target.value;
  if(type == 'unlimited')
  {
    $('.order_qty').hide().val(1000000000);
    qtyCalculation();
    // $('.receip_id').trigger('change')
  }
  else if(type == "limited")
  {
    $('.order_qty').show().val(0);
    qtyCalculation();

  }
}

function qtyCalculation()
{
  let reqired_qty = document.querySelectorAll('.reqired_qty');
  let requested_qty = document.querySelectorAll('.requested_qty');
  let uomArray = ['Metre','Mtrs'];
  let uom = $('#uom').val()
  let total = 0 ;
  let order_qty =  Number($('.order_qty').val());

  console.log(order_qty);

  reqired_qty.forEach((e,index)=>{


      total = (Number(parseFloat(order_qty) / 1000)) * parseFloat(e.value);  

      if(uomArray.includes(uom))
      {  
            total = (Number(parseFloat(order_qty) / 1000)) * parseFloat(e.value); 
            requested_qty[index].value = total; 
      }
      else
      {
            total = Number(parseFloat(order_qty)) * parseFloat(e.value);   
            requested_qty[index].value = total; 


      }


  });
  
}
  
</script>