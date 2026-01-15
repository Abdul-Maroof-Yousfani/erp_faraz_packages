@php 
    $count = 1;
    use App\Helpers\CommonHelper;
@endphp

@foreach($machine_process as $proccessed)
    <tr id="tr{{$count}}">

        <td class="text-center">
            @if($proccessed->machine_process_stage == 1)
                <input type="checkbox" class="check" name="checkbox_raw" id="checkbox_raw{{$proccessed->id}}"
                    value="{{$proccessed->id}}">
                <input type="hidden" class="id{{$proccessed->id}}" name="id[]" value="{{$proccessed->id}}">
                <input type="hidden" class="machine_proccess_id{{$proccessed->id}}" name="machine_proccess_id[]"
                    value="{{$proccessed->machine_proccess_id}}">

                @if($proccessed->batch_no)
                    <input type="hidden" class="batch_no{{$proccessed->id}}" name="batch_no[]" value="{{$proccessed->batch_no}}">
                @else
                    <input type="hidden" class="batch_no{{$proccessed->id}}" name="batch_no[]" value="{{$proccessed->mr_no}}">
                @endif
            @endif
        </td>
        <td class="text-center">{{$count}}</td>
        {{-- <td class="text-center">{{$proccessed->machine_no}}</td> --}}
        <td class="text-center">{{ $proccessed->batch_no }}</td>
        <td class="text-center">{{ $proccessed->machine_name }}</td>
        <td class="text-center">{{ $proccessed->operator_name }}</td>
        <td class="text-center">{{ $proccessed->shift }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($proccessed->machine_process_date) }}</td>
        <td class="text-center">{{ $proccessed->request_qty }}</td>
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

        <td class="text-center hidden-print printListBtn">
            @if($proccessed->machine_process_stage == 1)

                <div class="dropdown">
                    <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i
                            class="fa-solid fa-ellipsis-vertical"></i></button>
                    <ul class="dropdown-menu">
                        <li>
                            <a onclick="delete_row('#tr{{ $count }}' , {{$proccessed->id }} , {{$proccessed->machine_proccess_id }} , @if($proccessed->batch_no)'{{$proccessed->batch_no}}'@else'{{$proccessed->mr_no}}'@endif )"
                                href="#" class=""> Delete</a>
                        </li>
                    </ul>
                </div>
            @endif

        </td>
        {{--
        <td class="text-center">
            <button class="btn btn-success" type="button">
                Print Tag
            </button>
        </td>
        --}}
    </tr>
    @php 
        $count++;
    @endphp
@endforeach


<script>
    function delete_row(tr, id, machine_proccess_id, batch_no) {
        $.ajax({
            url: '/selling/deleteMachineProcess',
            type: 'Get',
            data: {
                id: id,
                machine_proccess_id: machine_proccess_id,
                batch_no: batch_no
            },
            success: function (response) {
                $(tr).remove();
            }
        });
    }
</script>