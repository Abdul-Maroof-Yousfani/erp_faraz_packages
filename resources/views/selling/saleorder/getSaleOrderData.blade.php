@if(!empty($sale_orders) || count($sale_orders) > 0)
@foreach($sale_orders as $sale_order)
<tr>
    <td></td>
    <td>{{$sale_order->sub_ic}}
    <input type="hidden" name="item_id[]" value="{{$sale_order->item_id}}" id="">
    <input type="hidden" name="sale_order_data_id[]" value="{{$sale_order->sale_order_data_id}}" id="">
    </td>
    <td>-</td>
    <td>{{$sale_order->qty}}
    
    <input type="hidden" name="sale_order_qty[]" value="{{$sale_order->qty}}" id="">
    </td>
    <td>{{$sale_order->uom_name}}</td>
    <td>{{$sale_order->diameter}}
    <input type="hidden" name="diameter[]"  value="{{$sale_order->diameter}}">
    </td>
    <td>-</td>
    <td>{{$sale_order->printing}}
        <input type="hidden" name="printing[]"  value="{{$sale_order->printing}}">
    </td>
    <td>{{$sale_order->delivery_date}} 
        <input type="hidden" name="delivery_date[]"  value="{{$sale_order->delivery_date}}">
    </td>
    <td>{{$sale_order->special_instruction}}
        <input type="hidden" name="special_instruction[]"  value="{{$sale_order->special_instruction}}">
    </td>
</tr>

@endforeach
@else
<tr>
    <td colspan="10" style="color: brown"> <h2> No data Found </h2></td>
</tr>

@endif