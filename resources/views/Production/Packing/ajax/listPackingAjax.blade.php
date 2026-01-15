@php 
    $count = 1;
@endphp    

@foreach ($data as $value)
<tr id="tr{{ $count }}">
    <td>{{$value->packing_list_no}}</td>
    <td>{{$value->packing_date}}</td>
    <td>{{$value->item_name}}</td>
    <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{route('Packing.show', $value->id)}}">
                            View
                        </a>

                        @if($value->qc_status == 1)
                            <a onclick="delete_row('#tr{{$count}}',{{$value->id}})">
                                delete
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </td>

    </tr>
    @php 
    $count ++;
    @endphp
@endforeach


<script>
    function delete_row(tr, id)
    {
        $.ajax({
                url: '/Production/Packing/delete/' + id,
                type: 'Get',
                success: function (response) {
                    $(tr).remove();
                }   
            });
    }

</script>
