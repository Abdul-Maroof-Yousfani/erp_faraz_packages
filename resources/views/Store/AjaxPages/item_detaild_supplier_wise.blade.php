
@extends('layouts.default')
@section('content')
<?php use App\Helpers\CommonHelper; ?>
<table  class="table table-bordered table-striped table-condensed tableMargin">
    <thead>
    <tr>
        <th class="text-center" style="width:50px;">S.No</th>
        <th class="text-center">Item Name</th>
        <th class="text-center">Supplier Name</th>
        <th class="text-center">QTY</th>
        <th class="text-center">Avergae Rate</th>
        <th class="text-center">Amount</th>

    </tr>
    </thead>

    <tbody>

    @php $counter=1; @endphp
    @foreach ($data as $row1):

    <tr>
        <td>{{$counter++}}</td>
        <td>{{CommonHelper::generic('subitem',array('id'=>$item),'sub_ic')->first()->sub_ic}}</td>
        <td>{{CommonHelper::generic('supplier',array('id'=>$row1->supplier_id),'name')->value('name')}}</td>
        <td class="text-right">{{$row1->qty}}</td>
        <td class="text-right">{{number_format($row1->amount/$row1->qty,2)}}</td>
        <td class="text-right">{{number_format($row1->amount,2)}}</td>
    </tr>

   @endforeach
    </tbody>
</table>
@endsection