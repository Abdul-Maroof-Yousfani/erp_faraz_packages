<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$i=1;
?>
@foreach($work_order as $work_ord)
<tr>
    <td>{{$i}}</td>
    <td>{{$work_ord->work_no}}</td>
  
    <td>
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"
                type="button" data-toggle="dropdown"
                aria-expanded="false">
                ...
            </button>
            <ul class="dropdown-menu">
                <li>
                <a  class="btn btn-sm btn-success" >
                    <i class="fa fa-eye" aria-hidden="true"></i>  View</a>

                <a href="#"  class="btn btn-sm btn-warning "><i class="fa fa-pencil" aria-hidden="true"></i>  Edit</a>
                <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                </li>
            </ul>
        </div>
    </td>
</tr>
@php
$i++;
@endphp
@endforeach