<?php 
use App\Helpers\CommonHelper;
?>
<style>
    .mainheading{
        background: lightgray;
        text-align: center;
        border: solid 1px;
        font-weight: bold;
    }
    .border{
        border: solid 1px;
        padding: 0%;
        text-align: center;
    }
    .th_border{
        background: lightgray;
        border:solid 1px;
        border-top:solid 1px;
        border-bottom:solid 1px black;
    }
    .td_border
    {
      
        border:solid 1px;
        border-top:solid 1px black;
        border-bottom:solid 1px;
    }
</style>
<div class="row">
    <div class="col-md-4">
    </div>
    
    <div class="col-md-4 mainheading" >
        Contract Review  
    </div>   
    <div class="col-md-4">
    </div>
</div>

<hr>
<div class="row">
    <div class="col-md-4">
    </div>
    
    <div class="col-md-4 text-center" >
        Section -A
    </div>   
    <div class="col-md-4">
    </div>
</div>
<hr>






<div class="col-md-12">



<div class="row">


<div class="col-md-6">
<div class="table-responsive" >
    <table >
        <tr>
            <th colspan="2">To be completed by the Marketing Department</th>
        </tr>
        <tr>
            <th>Sale Order No </th>
            <th>{{$sale_orders->so_no}}</th>
        </tr>
        <tr>
            <th>Sale Order Date</th>
            <th>{{$sale_orders->so_date}}</th>
        </tr>
        <tr>
            <th>Purchase Order No</th>
            <th>{{$sale_orders->purchase_order_no}}</th>
        </tr>
        <tr>
            <th>Purchase Order Date</th>
            <th>{{$sale_orders->purchase_order_date}}</th>
        </tr>
    </table>
</div>

</div>
<div class="col-md-2">
    &nbsp;
</div>
        <div class="col-md-4">
            <div class="border">
                Amendment Required

            </div><br>
            <div class="border">
                Product:
            </div>
        </div>

</div>
{{-- Details Of Table  --}}

<table class="table table-border">
    <thead class="th_border">
        <tr>
        <th class="th_border">Cable Type</th>
        <th class="th_border">Qty</th>
        <th class="th_border">Unit</th>
        <th class="th_border">Delivery Date</th>
        <th class="th_border"> ID Tape Printing</th>
        <th class="th_border"> Special request (if any)</th>
        {{-- <th class="th_border"> Part#</th>
        <th class="th_border"> Cable Printing</th> --}}
    </tr>
    </thead>
    @php
       $sale_order_data = DB::Connection('mysql2')->table('sales_order_data')->where('master_id',$sale_orders->id)->get()
    @endphp
  <tbody class="td_border">
    @foreach($sale_order_data as $item)
    <tr>
        <td class="td_border">{{CommonHelper::get_item_name($item->item_id)}}</td>
        <td class="td_border">{{$item->qty}}</td>
        <td class="td_border">{{CommonHelper::get_uom($item->item_id)}}</td>
        <td class="td_border">{{$item->delivery_date}}</td>
        <td class="td_border">{{$item->printing}}</td>
        <td class="td_border">{{$item->special_instruction}}</td>
        {{-- <td class="td_border"></td>
        <td class="td_border"></td> --}}
      

    </tr>
    @endforeach

  </tbody>
</table>






</div>