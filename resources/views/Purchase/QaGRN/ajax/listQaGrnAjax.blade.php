@php 
    use App\Helpers\CommonHelper;
    $count = 1;
@endphp

@foreach ($data as $value)
    <tr id="tr{{ $count }}">
        <td>{{$value->grn_no ?? $value->order_no}}</td>
        <td>{{ CommonHelper::changeDateFormat($value->qc_grn_date ?? $value->qc_packing_date) }}</td>
        <td>{{$value->qc_by}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    {{-- <li>
                        <a href="{{ route('QaGrn.testingOnReceiveItem', $value->qg_id ?? $value->id)}}" type="button" class="dropdown-item_sale_order_list dropdown-item">Perform Test</a>
                    </li> --}}
                    <li>
                        <a href="{{ route('QaGrn.testResultOnReceiveItem', $value->qg_id ?? $value->id)}}" type="button" class="dropdown-item_sale_order_list dropdown-item">Test Result</a>
                    </li>
                    <li>
                        <a onclick="showDetailModelTwoParamerter('purchase/QaGrn/grnViewQaGrnDetail','{{ $value->qg_id ?? $value->id }}','View QC GRN Detail')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>
                    </li>
                    <li>
                        <a href="{{ route('QaGrn.edit', $value->qg_id ?? $value->id)}}" type="button" class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                    </li>

                    <li>
                        @if (Auth::user()->acc_type == "client")
                            <a onclick="delete_row('#tr{{$count}}',{{$value->qg_id ?? $value->qp_id ?? $value->id}},0 )" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>
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
    function delete_row(tr, qc_grn_id, grn_id) {
        if (confirm('Are you sure you want to delete this QC GRN record?')) {
            $.ajax({
                url: '{{ url('/') }}/purchase/QaGrn/delete',
                type: 'Get',
                data: {
                    qc_grn_id: qc_grn_id,
                    grn_id: grn_id
                },
                success: function (response) {
                    if (response.success) {
                        $(tr).remove();
                        alert('QC GRN deleted successfully');
                    } else {
                        alert('Error deleting QC GRN: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr) {
                    alert('Error deleting QC GRN. Please try again.');
                }
            });
        }
    }

</script>