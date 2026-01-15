<?php $count = 0; ?>

@foreach ($list as $key => $value)
    <tr>
        <td>{{ ++$count }}</td>
        <td>{{ $value->order_no ?? '' }}</td>
        <td>{{ $value->mr_no }}</td>
        <td>{{ $value->issuance_date }}</td>
        <td>{{ $value->item }}</td>
        <td>{{ $value->qty }}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a onclick="showDetailModelOneParamerter('selling/rawMaterialRequistion', '{{ $value->pp_id }},{{ $value->mr_id }},{{ $value->issuance_date }}', 'RAW MATERIAL REQUISITION')" type="button" class="">
                            View
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
