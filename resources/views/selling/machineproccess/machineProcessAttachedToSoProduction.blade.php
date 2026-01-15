@php 
$count = 1;
@endphp 

@foreach($machine_process as $proccessed)
<tr id="tr{{$count}}">
    <td class="text-center">
    @if($proccessed->machine_process_stage == 1)
        <input type="checkbox" class="check" onclick="singleChecked(event)" name="checkbox_raw" id="checkbox_raw{{$proccessed->id}}" value="{{$proccessed->id}}">
        <input type="hidden" class="id{{$proccessed->id}}" name="id{{$proccessed->id}}" value="{{$proccessed->id}}">    
        <input type="hidden" class="process_data_id" name="process_data_id[]" value="{{$proccessed->id}}">    
        <input type="hidden" class="check_condition_{{$proccessed->id}}" name="check_condition_{{$proccessed->id}}" value="0">    
        <input type="hidden" class="machine_proccess_id" name="machine_proccess_id" value="{{$proccessed->machine_proccess_id}}">    
    @endif
    </td>
    <td class="text-center">{{$count}}</td>
    <td class="text-center">{{$proccessed->batch_no}}</td>
    <td class="text-center">{{$proccessed->machine_name}}</td>
    <td class="text-center">{{$proccessed->operator_name}}</td>
    <td class="text-center">{{$proccessed->shift}}</td>
    <td class="text-center">{{$proccessed->machine_process_date}}</td>
    <td class="text-center">{{$proccessed->request_qty}}</td>
    <td class="text-center">
        @if($proccessed->machine_process_stage == 1)
            Received
        @elseif($proccessed->machine_process_stage == 2)
            Packing
        @elseif($proccessed->machine_process_stage == 3)
            On Qc
        @elseif($proccessed->machine_process_stage == 4)
            Qc Passed 
        @endif    
    </td>
</tr>
@php 
$count++;
@endphp 
@endforeach

