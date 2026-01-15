<?php
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
$i =1;
?>
@foreach($sub_items as $sub_item)
@php
$i++;
$sal_order = SalesHelper::sale_order_qty($sub_item->id);    
$in_stock = CommonHelper::stock_in_hand($sub_item->id);

@endphp
<tr>
    <td>{{$i}}</td>
    <td>
        {{CommonHelper::get_sub_category_name($sub_item->sub_category_id)}}
    </td>
    <td>{{$sub_item->sub_ic}}</td>
    <td>
        {{CommonHelper::get_uom($sub_item->uom)}}
    </td>
   
 
    <td>
        {{number_format($in_stock,2)}}
    </td>
    <td>
        {{number_format($sal_order,2)}}
    </td>
    <td>
        {{number_format($sal_order+$in_stock,2)}}
    </td>

    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>


@endforeach