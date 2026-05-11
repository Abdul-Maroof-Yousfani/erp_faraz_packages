@php 
    $count = 1;
@endphp    

@foreach ($data as $value)
<tr id="tr{{ $count }}">
    <td>{{$value->name}}</td>
    <td>{{ $value->department_name ?? '-' }}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"
                        type="button" data-toggle="dropdown"
                        aria-expanded="false">
                    ...
                </button>
                <ul class="dropdown-menu">
                    <li>
                      
                        <a href="{{route('Operator.edit', $value->id)}}"  class="btn btn-sm btn-warning " target="_blank"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                        <a onclick="delete_row('#tr{{ $count }}' , {{$value->id }}); return false;"  href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
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
                url: '/InventoryMaster/Operator/delete/' + id,
                type: 'Get',
                data: {
                        id:id,
                    },
                success: function (response) {
                    $(tr).remove();
                    $('#operatorMessage')
                        .removeClass()
                        .addClass('alert alert-success alert-dismissible')
                        .html('<button type="button" class="close" data-dismiss="alert">&times;</button>' + response.message)
                        .show();
                },
                error: function () {
                    $('#operatorMessage')
                        .removeClass()
                        .addClass('alert alert-danger alert-dismissible')
                        .html('<button type="button" class="close" data-dismiss="alert">&times;</button>Record delete nahi hua. Please try again.')
                        .show();
                }   
            });
    }

</script>
