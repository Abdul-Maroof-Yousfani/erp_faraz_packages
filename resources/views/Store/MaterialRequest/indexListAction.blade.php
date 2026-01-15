<div class="dropdown">
    <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
    <ul class="dropdown-menu">
        <li>
            <a onclick="showDetailModelOneParamerter('stdc/viewMaterialRequestDetail/'+{{$data->id}}+'','0','View Material Request Detail')"><i class="fa-regular fa-eye"></i> View MR</a>
            @if($data->material_request_status == 1)
            <a href="{{ route('store.editMaterialRequestForm', ['id' => $data->id]) }}"><i class="fa-solid fa-pencil"></i> Edit</a>
            <a onclick="deleteMaterialRequestDetail( '{{$data->material_request_no}}' , '{{$data->material_request_date}}' )" ><i class="fa-solid fa-trash"></i> Delete</a>
            @endif   
        </li>
    </ul>
</div>

<script>

function deleteMaterialRequestDetail(material_request_no,material_request_date) {
    $.ajax({
        url: "{{ route('stad.deleteMaterialRequestDetail') }}",
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