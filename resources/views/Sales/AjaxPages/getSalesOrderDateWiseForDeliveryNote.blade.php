<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$counter = 1;
$total = 0;
?>

@foreach($sale_order as $row)
    <?php $data = SalesHelper::get_so_amount($row->id); ?>
    <?php $customer = CommonHelper::byers_name($row->buyers_id); ?>
    <tr @if ($row->so_type==1) style="background-color: lightyellow" @endif title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td title="{{$row->id}}" class="text-center">@if ($row->so_type==0) {{strtoupper($row->so_no)}} @else {{strtoupper($row->so_no.' ('.$row->description.')')}}@endif</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($row->so_date) }}</td>
        <td class="text-center">{{ $row->model_terms_of_payment }}</td>
        <td class="text-center">{{ $row->order_no }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($row->order_date) }}</td>
        <td class="text-center">{{ $customer->name }}</td>
        @php
        $total_tax_ammount = $data->amount / 100 * $data->sales_tax_rate;
        $total_further_tax_ammount = $data->amount / 100 * $row->sales_tax_further;
        $total_advance_tax_ammount = $data->amount / 100 * $row->advance_tax;
        @endphp
        <td class="text-right">{{number_format($data->amount + $total_tax_ammount + $total_further_tax_ammount + $total_advance_tax_ammount + $row->cartage_amount,2)}}
            <?php $total += $data->amount + $total_tax_ammount + $total_further_tax_ammount + $total_advance_tax_ammount + $row->cartage_amount ?>
        </td>
        <td class="text-center"><button onclick="showDetailModelOneParamerter('selling/viewSaleOrderPrint/{{$row->id}}',{{$row->id}},'View Sale Order ')" type="button" class="btn btn-success btn-xs">View</button></td>

        <td class="text-center"><button onclick="delivery_note('<?php echo $row->id?>','<?php echo $m ?>')" type="button" class="btn btn-primery btn-xs">Create DN</button></td>
    </tr>

@endforeach

<tr>
    <td class="text-center" colspan="7"><b>Total</b></td>
    <td class="text-right" colspan="1"><b>{{ number_format($total,2) }}</b></td>
    <td class="text-center" colspan="2"></td>
</tr>