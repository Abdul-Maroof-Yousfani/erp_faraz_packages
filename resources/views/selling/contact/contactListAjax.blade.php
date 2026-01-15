
@php
$i =1; 
@endphp
@foreach($contacts as $contact)
<tr>
    <td>{{$i}}</td>
    <td>{{ $contact->first_name}}</td>
    <td>{{$contact->personal_title}}</td>
    <td>{{$contact->cell }} / {{$contact->phone}}  </td>
    <td>{{$contact->email}}</td>
    <td>
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"
                type="button" data-toggle="dropdown"
                aria-expanded="false">
                ...
            </button>
            <ul class="dropdown-menu">
            <li>
                {{-- <a  class="btn btn-sm btn-success" ><i class="fa fa-eye" aria-hidden="true"></i>  View</a> --}}
                <a href="{{route('editContact',$contact->id)}}"  class="btn btn-sm btn-warning "><i class="fa fa-pencil" aria-hidden="true"></i>  Edit</a>
                <a href="{{route('contactDelete',$contact->id)}}" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
               
                </li>
            </ul>
        </div>

    </td>
</tr>
@php
$i ++;
@endphp
@endforeach