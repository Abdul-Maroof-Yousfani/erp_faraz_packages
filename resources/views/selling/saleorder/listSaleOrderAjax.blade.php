@foreach ($sale_orders as $sale_order)
    <tr>
        <td class="text-center">{{$sale_order->so_no}}</td>
        <td>{{$sale_order->name}}</td>
        <td class="text-center">{{ \App\Helpers\CommonHelper::changeDateFormat($sale_order->so_date) }}</td>
        <td class="text-right">{{ number_format($sale_order->total_amount_after_sale_tax, 2) }}</td>
        <td>
            @if($sale_order->so_status == 0)
                New Sale Order
            @else
                Invoice Created
            @endif
        </td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        {{-- <a class="" href="{{route('viewSaleOrder', $sale_order->id)}}" target="_blank">
                            View
                        </a> --}}
                        <a class=""
                            onclick="showDetailModelOneParamerter('selling/viewSaleOrderPrint/{{$sale_order->id}}',{{$sale_order->id}},'View Sale Order ')"
                            target="_blank">
                            View
                        </a>
                        {{-- <a class="" href="{{route('saleOrderSectionA', $sale_order->id)}}" target="_blank">
                            Section A / B
                        </a> --}}
                        <a href="{{route('editSaleOrder', $sale_order->id)}}" class="" target="_blank"> Edit</a>
                        <a href="#" class="">Delete</a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>

@endforeach