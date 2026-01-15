@foreach($prospects as $prospect)
    <tr id="{{ $prospect->id }}">
        <td>
            {{$prospect->contact->first_name . ' ' .$prospect->contact->last_name}}
        </td>
        <td>{{$prospect->company_name}}</td>
        {{-- <td>{{$prospect->company_address}}</td> --}}
        <td>Karachi</td>
        {{-- <td>Local</td> --}}
        <td>{{$prospect->contact->phone}} / {{$prospect->contact->cell}}</td>
        <td>
            <div class="dropdown">
             <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="" onclick="showDetailModelOneParamerter('prospect/viewProspect','{{ $prospect->id }}','View Prospect','','')"> View  </a>
                        
                        <a onclick="createprospects('prospect/editProspect/<?php echo $prospect->id ?>','','Edit Prospect','')"  class=" "> Edit</a>
                        <a onclick="delete_record({{ $prospect->id }})" class=""> Delete</a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach

<script>
    function delete_record(id) {
    if (confirm('Are you sure you want to delete this request')) {
        $.ajax({
            url: '/prospect/deleteProspect/' + id, // Updated URL with the parameter
            type: 'GET',
            success: function (response) {
                console.log(response);
                $('#' + id).remove();
            }
        });
    } else {
        // Handle cancellation
    }
}
</script>