<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$counter = 1;$total=0;?>

@foreach($sale_order as $row)
    <?php $data=SalesHelper::get_so_amount($row->id); ?>
    <?php $customer=CommonHelper::byers_name($row->buyers_id); ?>
    <tr @if ($row->so_type==1) style="background-color: lightyellow" @endif title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td title="{{$row->id}}" class="text-center">@if ($row->so_type==0) {{strtoupper($row->so_no)}} @else {{strtoupper($row->so_no.' ('.$row->description.')')}}@endif</td>
        <td class="text-center">{{$row->packing_list_no}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->so_date);?></td>
        <td class="text-center">{{$row->model_terms_of_payment}}</td>
        <td class="text-center">{{$customer->name}}</td>
        @php
        $total_tax_ammount = $data->amount/100*$data->sales_tax_rate;


        @endphp
    

        <td class="text-center"><button
                    onclick="showDetailModelOneParamerter('selling/viewSaleOrderPrint/{{$row->id}}',{{$row->id}},'View Sale Order ')"
                    type="button" class="btn btn-success btn-xs">View</button></td>

        <td class="text-center"><button
                    onclick="delivery_challan('<?php echo $row->id?>','<?php echo $row->packing_id?>','<?php echo $row->qc_packing_id?>','<?php echo $m ?>')"
                    type="button" class="btn btn-primery btn-xs">Create Delivery Note</button></td>
    </tr>


@endforeach