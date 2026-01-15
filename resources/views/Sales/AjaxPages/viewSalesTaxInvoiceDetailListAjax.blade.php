@php 
$count = 1 ;
@endphp

@foreach($data as $key => $value)

<tr>
<td>{{$count}}</td>
<td>{{$value->ntn}}</td>
<td>{{$value->name}}</td>
<td>{{$value->address}}</td>
<td>{{$value->gi_no}}</td>
<td>{{$value->gi_date}}</td>
<td>{{$value->qty}}</td>
<td>{{$value->uom_name}}</td>
<td>{{$value->amount}}</td>
<td>{{$value->sales_tax_rate}}</td>
<td>{{$value->sales_tax_further}}</td>

</tr>


@php 
$count ++ ;
@endphp

@endforeach