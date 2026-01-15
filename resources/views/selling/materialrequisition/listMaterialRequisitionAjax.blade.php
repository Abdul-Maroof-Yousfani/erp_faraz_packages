@php
    use App\Helpers\CommonHelper;
    $i = 1;
@endphp
@foreach($material_requisitions as $material_requisition)
    <tr>
        <!-- <td class="text-center"><input type="checkbox" name="" id=""></td> -->
        <td class="text-center">{{$i}}</td>
        <td class="text-center">{{$material_requisition->pr_no}}</td>
        <td class="text-center">{{$material_requisition->order_no}}</td>
        <td class="text-center">{{$material_requisition->mr_no}}</td>
        <td class="text-center">{{ CommonHelper::get_item_by_id($material_requisition->finish_good_id)->sub_ic}}</td>
        <td>{{ CommonHelper::changeDateFormat($material_requisition->mr_date) }}</td>
        <td>
            @if($material_requisition->mr_status == 2)
                Issued
            @elseif($material_requisition->mr_status == 3)
                Ongoing In Machine
            @else
                Panding
            @endif
        </td>
        <td>
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        @if($material_requisition->mr_status == 1)
                            <a href="{{route('viewProductionPlane', $material_requisition->id)}}"> Issue Raw Material </a>
                        @endif
                    </li>
                </ul>
            </div>
        </td>
    </tr>

    @php
        $i++;
    @endphp
@endforeach