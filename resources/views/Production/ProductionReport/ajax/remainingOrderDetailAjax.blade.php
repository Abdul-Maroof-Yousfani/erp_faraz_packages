@php

use App\Helpers\CommonHelper;

$count = 1;
@endphp


<table class="userlittab table table-bordered sf-table-list">
    <thead>
        <tr>
            <th style="vertical-align: middle;" class="text-center" rowspan="2">S NO</th>
            <th style="vertical-align: middle;" class="text-center" rowspan="2">SO NO.</th>
            <th style="vertical-align: middle;" class="text-center" rowspan="2">PARTY REF.</th>
            <th style="vertical-align: middle;" class="text-center" rowspan="2">Production Plane</th>
            <th style="vertical-align: middle;" class="text-center" rowspan="2">CABLE TYPE</th>
            <th style="vertical-align: middle;" class="text-center" rowspan="2">UOM</th>
            <th style="vertical-align: middle;" class="text-center" rowspan="2">PARTY NAME</th>
            <th colspan="6" style="text-align:center;"> QUANTITY DETAIL (KM)</th>
        </tr>
        <tr>
            <th>ORDER QTY.</th>
            <th>PROD.QTY</th>
            <th>REM.QTY</th>
            <th>DEL.QTY</th>
        </tr>
    </thead>
    <tbody>
       
        @foreach($list as $key => $value)
                    @php
                     
                        
                        $productName = CommonHelper::get_item_name($value->finish_goods_id) ?? '';
                        $uom = CommonHelper::get_item_by_id($value->finish_goods_id) ?? '';
                        if(!empty($uom))
                        {

                            $uom = $uom->uom_name;

                        }
                        else
                        {
                            $uom ='';
                        }
                        $party = CommonHelper::byers_name($value->customer);
                        $party = (!empty($party)) ? $party->name : '';

                        $remainingQty =$value->qty - $value->total_request_qty;

                    @endphp    

                    <tr>
                        <td>{{ $count++ }}</td>
                        <td> {{ $value->so_no }} </td>
                        <td> {{ $value->purchase_order_no }} </td>
                        <td> {{ $value->order_no }} </td>
                        <td> {{ $productName }} </td>
                        <td> {{ $uom }} </td>
                        <td> {{ $party }} </td>
                        <td> {{ $value->qty }} </td>
                        <td> {{ $value->total_request_qty }} </td>
                        <td> {{ $remainingQty }} </td>
                        <td> {{ $value->total_request_qty }} </td>
                    </tr>
                @endforeach
    </tbody>
</table>