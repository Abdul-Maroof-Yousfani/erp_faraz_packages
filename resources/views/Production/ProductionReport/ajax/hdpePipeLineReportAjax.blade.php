@php

use App\Helpers\CommonHelper;

$productName = CommonHelper::get_item_name($mainData->finish_goods_id) ?? '';
$uom = CommonHelper::get_item_by_id($mainData->finish_goods_id) ?? '';
if(!empty($uom))
{

    $uom = $uom->uom_name;

}
else
{
    $uom ='';
}

$totalQty = 0 ;

$party = CommonHelper::byers_name($mainData->customer);
$party = (!empty($party)) ? $party->name : '';
$count = 1;
@endphp

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="tect_Cc test22">
                <p>PIPE :</p>
                <div class="tes">
                    <p>{{ $productName }}</p>
                </div>
            </div>
            <div class="tect_Cc test22">
                <p>ORDER NO. : </p>
                <div class="tes">
                    <p>{{ $mainData->purchase_order_no }}</p>
                </div>
            </div>
            <div class="tect_Cc test22">
                <p>WALL THICKNESS : </p>
                <div class="tes">
                    <p>{{ $mainData->wall_thickness }}</p>
                </div>
            </div>

            <div class="tect_Cc test22 hide" >
                <p>WALL THICKNESS :</p>
                <div class="tes">
                    <p>786/PIPE/39/2021-22</p>
                </div>
            </div>
            <div class="tect_Cc test22">
                <p>PARTY :</p>
                <div class="tes">
                    <p>{{ $party }}</p>
                </div>
            </div>
            <div class="tect_Cc test22">
                <p>MACHINE NAME / NO. :</p>
                <div class="tes">
                    <p>{{$machineName}}</p>
                </div>
            </div>
        </div>                                    
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
    </div>

    <br>
    <br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <table class="userlittab table table-bordered sf-table-list">
                <thead>
                <tr>
                    <th>S NO </th>
                    <th>DATE</th>
                    <th>SHIFT</th>
                    <th>PROD.SERIAL NO</th>
                    <th>LENGTH</th>
                    <th>OPERATOR</th>
                    <th>MACHINE</th>
                    <th>REMARKS</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($list as $key => $value)
                    @php
                        $totalQty += $value->request_qty;
                    @endphp    

                    <tr>
                        <td>{{ $count++ }}</td>
                        <td> {{ $value->recieved_date }} </td>
                        <td> {{ $value->shift }} </td>
                        <td style="text-center"> {{ $value->batch_no }} </td>
                        <td> {{ $value->request_qty }} {{$uom}} </td>
                        <td> {{ $value->name }} </td>
                        <td> {{ $value->machineName }} </td>
                        <td> {{ $value->remarks }} </td>
                    </tr>
                @endforeach
                    <tr>
                        <td> </td>
                        <td> Total </td>
                        <td> </td>
                        <td style="text-center">  </td>
                        <td> {{ $totalQty }} {{$uom}} </td>
                        <td> </td>
                        <td> </td>
                        <td>  </td>
                    </tr>

            </tbody>
            </table>
        </div>
    </div>
</div>