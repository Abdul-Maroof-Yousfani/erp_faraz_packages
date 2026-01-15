@php 
    $count = 1;
@endphp    

@foreach ($HsCode as $value)
<tr id="tr{{ $count }}">
    <td>{{$value->hs_code}}</td>
    <td>{{$value->description}}</td>
    <td>{{$value->utilise_under_benefit_of}}</td>
    <td>{{$value->applicable_sro_benefit}}</td>
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
                        onclick="showDetailModelOneParamerter('import/HsCode/show/{{$value->id}}',{{$value->id}},'View HS Code  ',{{ Session::get('run_company')}})"
                         target="_blank">
                            <i class="fa fa-eye" aria-hidden="true"></i> View
                        </a>
                        <a href="{{route('HsCode.edit', $value->id)}}"  class="btn btn-sm btn-warning " target="_blank"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
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
            url: "{{ url('/import/HsCode/delete') }}/" + id,
            type: 'Get',
            data: {
                id:id,
            },
            success: function (response) {
                $(tr).remove();
            }   
        });
    }

</script>
