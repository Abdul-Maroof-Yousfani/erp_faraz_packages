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
    $cost=DB::Connection('mysql2')->table('stock')->where('status',1)->where('voucher_no',$row->gd_no)->sum('amount');
    $total+=$cost;
    ?>
    <?php


 //   $data1=SalesHelper::get_total_amount_for_dn_by_id($row->id);
//    $status='';
   // $diffrence=round($data1->amount-$data1->dn_amount);
    $data=SalesHelper::get_total_amount_for_delivery_not_by_id($row->id);

    $status='all';
    if ($row->sales_tax_invoice==0):
        $status='Open';
        $total_open+=$cost;
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
     //   $parttial++;

        $complete++;
    endif;




    $validation='all';
    if ($radio==1):
        if ($row->sales_tax_invoice==0):


            $return_qty_data=SalesHelper::get_return_by_dn($row->gd_no);
            $return_qty=0;
            if (!empty($return_qty_data->qty)):
                $return_qty=$return_qty_data->qty;
                endif;
            $remaining_qty=$data->qty-$return_qty;
            if ($remaining_qty==0):
                $status='Returned';
            endif;

            $validation=$status;
        endif;
    endif;
    if ($radio==3):
        if($row->sales_tax_invoice==1):
            $validation=$status;
        endif; endif;

    if ($radio==''):
        $validation=$status;
    endif;





    ?>



    <?php $customer=CommonHelper::byers_name($row->buyers_id); ?>
    @if ($validation==$status )
    <tr @if($status=='Open') style="background-color: #fdc8c8" @elseif($status=='partial') style="background-color: #c9d6ec" @endif title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center"><?php echo  strtoupper($row->so_no) ?></td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gd_no)}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->gd_date);?></td>

        <?php $dn_date = date("m",strtotime($row->gd_date)); ?>

        <td class="text-center">{{$row->order_no}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
        <td class="text-center">{{$customer->name}}</td>
        <td class="text-right">{{number_format($data->qty,3)}}</td>
        <td class="text-right">{{number_format($data->amount+$row->sales_tax_amount,3)}}</td>
        <td>{{$status}}</td>


        <td class="text-center"><?php echo $row->username?></td>




        {{--<td class="text-right">--}}

        {{--</td>--}}


        <td class="text-center">

            <?php if($view == true):?>
            <button onclick="showDetailModelOneParamerter('sales/viewDeliveryChallanDetail/<?php echo $row->id ?>','','View Delivery Challan')"
                    type="button" class="btn btn-success btn-xs">View</button>

            <?php endif;?>
            <?php if($edit == true):?>
            <button onclick="delivery_note('<?php echo $row->id?>','<?php echo $m ?>')"
                    type="button" class="btn btn-parimay btn-xs">Edit</button>
                <?php endif;?>
                <?php if($delete == true):?>
            <button onclick="delivery_note_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                    type="button" class="btn btn-danger btn-xs">Delete</button>
            <?php endif;?>

        </td>

        <td>{{number_format($cost,2)}}</td>
        {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
        {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
    </tr>
    @endif


@endforeach
<tr style="background-color: lightgrey">
    <td colspan="11">Total</td>
    <td title="total cost">{{number_format($total,2)}}</td>
    <td title="total open cost">{{number_format($total_open,2)}}</td>
</tr>
{{--<tr>--}}
    {{--<td colspan="12">Total</td>--}}
    {{--<td class="text-right">{{number_format($total_cost,2)}}</td>--}}
    {{--<td>{{number_format($total_open_dn,2)}}</td>--}}
{{--</tr>--}}