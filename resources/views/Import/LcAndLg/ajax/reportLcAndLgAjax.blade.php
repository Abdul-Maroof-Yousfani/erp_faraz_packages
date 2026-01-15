@php 
    $count = 1;
@endphp    

@foreach ($data as $value)
<tr id="tr{{ $count }}">
    <td>{{ str_replace('_', ' ', $value->type) }}</td>
    <td>{{$value->name}}</td>
    <td>{{number_format($value->limit , 2)}}</td>
    <td>{{number_format($value->limit_utilized , 2)}}</td>
    <td>{{number_format($value->un_utilized , 2)}}</td>
    <td>{{ sprintf("%.2f", $value->remaining_percentage)}} %</td>
     
    </tr>
    @php 
    $count ++;
    @endphp
@endforeach

