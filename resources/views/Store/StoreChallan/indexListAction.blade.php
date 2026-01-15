<div class="dropdown">
    <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
    <ul class="dropdown-menu">
        <li>
            @if($data->type==1)                
            <li><a onclick="showDetailModelOneParamerter('stdc/viewStoreChallanVoucherDetail','{{ $data->store_challan_no }}<>{{ $data->type }}','View Store Challan Detail')"><i class="fa-regular fa-eye"></i> View SC</a></li>
            @else
            <li><a onclick="showDetailModelOneParamerter('stdc/viewStoreChallanVoucherDetail','{{ $data->id }}<>{{ $data->type }}','View Store Challan Detail')"><i class="fa-regular fa-eye"></i> View SC</a></li>
            @endif
            @if($data->store_challan_status == 1)
            <li><a href="{{ route('store.editStoreChallanForm', [$data->material_request_no, $data->material_request_date , $data->store_challan_no]) }}"><i class="fa-solid fa-pencil"></i> Edit</a></li>
            <li><a onclick="deleteStoreChallanDetail( '{{$data->material_request_no}}' , '{{$data->material_request_date}}')" ><i class="fa-solid fa-trash"></i> Delete</a></li>     
            @endif   
        </li>
    </ul>
</div>

<script>

function deleteStoreChallanDetail(material_request_no,material_request_date) {
    $.ajax({
        url: "{{ route('stad.StoreChallanDetail.deleteStoreChallanDetail') }}",
        type: 'GET',
        data: {
            material_request_no: material_request_no,
            material_request_date: material_request_date
        },
        success: function (response) {
            if(response.status == 'success')
            {
                window.location.reload();
            }
            else
            {
                alert(response.message)
            }
        }
    });
}


</script>    