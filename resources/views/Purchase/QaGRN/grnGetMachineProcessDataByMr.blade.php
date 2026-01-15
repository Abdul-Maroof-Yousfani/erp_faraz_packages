<?php
use App\Helpers\CommonHelper;
$i=1;
?>

@foreach($mr_datas as $mr_data)

<tr>
    <td>
        <input type="hidden" name="machine_proccess_data_id[]" id="" value="{{$mr_data->id}}">
        <input type="checkbox" class="checkbox" onclick="setValueOnCheckBox(this,{{$mr_data->id}})" name="checkBox{{$mr_data->id}}">
        <input type="hidden" class="checkbox" id="checkBox{{$mr_data->id}}" name="checkBox{{$mr_data->id}}" value="0">
    </td>
    <td>{{$i}}</td>
    <td>
        {{$mr_data->batch_no}}
        <input type="hidden" name="bundle_no[]" id="" value="{{$mr_data->batch_no}}">

    </td>
    <td>
        {{$mr_data->request_qty}}
        <input type="hidden" name="qty[]" id="" value="{{$mr_data->request_qty}}">

    </td>
</tr>

@php
 $i++;   
@endphp
@endforeach

<input type="hidden" id="item_id" name="item_id" value="{{$machine_data->finish_good_id}}">
<input type="hidden" id="item_name" name="item_name" value="{{$machine_data->sub_ic}}">