<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(111);
$edit=ReuseableCode::check_rights(112);
$delete=ReuseableCode::check_rights(113);
$counter = 1;$total=0;
$total_cost=0;
$total_open_dn=0;
$complete=0;
$open=0;

$total=0;
$total_open=0;
?>

@foreach($delivery_note as $row)

    <?php

    $data=SalesHelper::get_total_amount_for_delivery_not_by_id($row->id);
    $status='';
    if ($row->sales_tax_invoice==0):
        $status='Open';
        $return_qty_data=SalesHelper::get_return_by_dn($row->gd_no);

        $return_qty=0;
        if (!empty($return_qty_data->qty)):
            $return_qty=$return_qty_data->qty;
        endif;

        $remaining_qty=$data->qty-$return_qty;
        if ($remaining_qty==0):
            $status='Returned';
        endif;
        $open++;
    elseif($row->sales_tax_invoice==1):
        $status='Complete';
        $complete++;
    endif;
    ?>
    <?php $customer=CommonHelper::byers_name($row->buyers_id); ?>
    <tr @if($status=='Open') style="background-color: #fdc8c8" @elseif($status=='partial') style="background-color: #c9d6ec"  @endif title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center">{{ strtoupper($row->so_no) }}</td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gd_no)}}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($row->gd_date) }}</td>

        <td class="text-center">{{ $row->order_no }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($row->order_date) }}</td>
        <td class="text-center">{{$customer->name}}</td>
        <td class="text-right">{{number_format($data->qty,3)}}</td>
        <td class="text-right">{{number_format($data->amount+$row->sales_tax_amount,3)}}</td>
        <td class="text-center">{{$status}}</td>
        <td class="text-center">{{ $row->username }}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        @if($view == true)
                            <a onclick="showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/<?php echo $row->id ?>','','View Delivery Note')"
                                >
                                View
                            </a>
                        @endif
                        @if($edit == true && $status!='Complete')
                            <a onclick="delivery_note('<?php echo $row->id?>','<?php echo $m ?>')"
                                >
                                Edit
                            </a>
                        @endif
                        @if($delete == true && $status!='Complete')
                            <a onclick="delivery_note_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                                >
                                Delete
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </td>
    </tr>

@endforeach
<tr style="background-color: lightgrey">
    <td colspan="8">Total</td>
    <td title="total cost">{{number_format($total,2)}}</td>
    <td title="total open cost">{{number_format($total_open,2)}}</td>
    <td colspan="2"></td>
</tr>