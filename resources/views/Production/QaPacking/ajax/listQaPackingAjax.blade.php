@php 
    use App\Helpers\CommonHelper;
    $count = 1;
@endphp

@foreach ($data as $value)
    <tr id="tr{{ $count }}">
        <td>{{$value->order_no}}</td>
        <td>{{ CommonHelper::changeDateFormat($value->qc_packing_date) }}</td>
        <td>{{$value->qc_by}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <!-- <li>
                        <a href="{{ route('QaPacking.testingOnReceiveItem', $value->id)}}" type="button" class="dropdown-item_sale_order_list dropdown-item">Perform Test</a>
                    </li>
                    <li>
                        <a href="{{ route('QaPacking.testResultOnReceiveItem', $value->id)}}" type="button" class="dropdown-item_sale_order_list dropdown-item">Test Result</a>
                    </li> -->
                    <li>
                        <a onclick="showDetailModelTwoParamerter('Production/QaPacking/viewQaPackingDetail','{{ $value->id }}','View QC Detail')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>
                    </li>
                    <li>
                        <a href="{{ route('QaPacking.edit', $value->id)}}" type="button" class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                    </li>

                    <li>
                        @if (Auth::user()->acc_type == "client")
                            <a onclick="delete_row('#tr{{$count}}',{{$value->qp_id}},0 )" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>
                        @endif
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    @php 
        $count++;
    @endphp
@endforeach


<script>
    function delete_row(tr, qc_packing_id, packing_list_id) {
        $.ajax({
            url: '{{ url('/') }}/Production/QaPacking/delete',
            type: 'Get',
            data: {
                qc_packing_id: qc_packing_id,
                packing_list_id: packing_list_id
            },
            success: function (response) {
                $(tr).remove();
            }
        });
    }

</script>