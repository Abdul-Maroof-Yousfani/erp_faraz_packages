<?php
use App\Helpers\CommonHelper;
$i=1;
?>

@foreach($mr_datas as $mr_data)

<tr>

    <td>{{$i}}
        <input type="hidden" value="{{$mr_data->id}}" name="mr_data_id[]">
    </td>
    <td><input type="text" readonly  class="form-control" value="{{CommonHelper::get_item_by_id($mr_data->raw_item_id)->sub_ic}}"></td>
    <td><input type="text" name="request_qty[]" class="form-control" value="{{$mr_data->issuance_qty}}" readonly></td>
</tr>
@php
 $i++;   
@endphp
@endforeach