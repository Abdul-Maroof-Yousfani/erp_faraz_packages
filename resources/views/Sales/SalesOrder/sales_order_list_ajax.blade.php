
<?php
use App\Helpers\CommonHelper;
$i =1;
?>

@foreach($sale_orders as $sale_order)
<tr>
<td class="text-center">{{$i}}</td>
<td class="text-center">{{$sale_order->so_no}}</td>
<td class="text-center">{{$sale_order->so_date}}</td>
<td class="text-center">{{$sale_order->purchase_order_no}}</td>
<td class="text-center">{{$sale_order->purchase_order_date}}</td>
<?php $customer=CommonHelper::byers_name($sale_order->buyers_id);?>
<td class="text-center">{{$customer->name}}</td>
<td class="text-center">{{$sale_order->so_status}}</td>
<td  class="text-center">
    <button onclick="showDetailModelOneParamerterUpadted('<?php echo route('salesorder.show', $sale_order->id );?>','<?php echo $sale_order->id ?>','View Sales Order')"
        type="button" class="btn btn-success btn-sm">View</button>
        <a href="{{route('salesorder.edit',$sale_order->id)}}" class="btn btn-warning btn-sm">Edit </a>
    <button onclick="sale_order_delete('<?php echo $sale_order->id?>')"
        type="button" class="btn btn-primary btn-sm">Delete</button>
</td>
</tr>
@php
$i++;    
@endphp
@endforeach