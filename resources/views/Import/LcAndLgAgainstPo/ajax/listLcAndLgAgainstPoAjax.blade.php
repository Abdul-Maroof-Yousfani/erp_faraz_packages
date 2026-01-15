@php 
    $count = 1;
@endphp    

@foreach ($data as $value)
<tr id="tr{{ $count }}">
    <td>{{$value->purchase_request_no}}</td>
    <td>{{$value->buyer_name}}</td>
    <td>{{$value->beneficiary_name}}</td>
    <td>{{$value->advising_bank}}</td>
    <td>{{$value->amount}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"
                        type="button" data-toggle="dropdown"
                        aria-expanded="false">
                    ...
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="btn btn-sm btn-success" 
                        onclick="showDetailModelOneParamerter('import/LcAndLgAgainstPo/show/{{$value->id}}',{{$value->id}},'View LC Against Po  ',{{ Session::get('run_company')}})"
                         target="_blank">
                            <i class="fa fa-eye" aria-hidden="true"></i> View
                        </a>
                        <a href="{{route('LcAndLgAgainstPo.edit', $value->id)}}"  class="btn btn-sm btn-warning " target="_blank"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                        <a onclick="delete_row('#tr{{ $count }}' , {{$value->id }})"  href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
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
                url: '/import/LcAndLgAgainstPo/delete/' + id,
                type: 'Get',
                data: {
                        id:id,
                    },
                success: function (response) {
                    if(response.status)
                    {
                        $(tr).remove();

                    }
                }   
            });
    }

</script>
