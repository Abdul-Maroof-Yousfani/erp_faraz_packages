<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(104);
$edit=ReuseableCode::check_rights(105);
$delete=ReuseableCode::check_rights(106);
$counter = 1;$total=0;
$open=0;
$parttial=0;
$complete=0;
?>

@foreach($sale_order as $row)
    <?php $customer=CommonHelper::byers_name($row->buyers_id);
    $data=SalesHelper::get_so_amount($row->id);
    $dn_data=SalesHelper::get_dn_amount_by_so_id($row->id);
    $dn_qty=0;
     if (!empty($dn_data->qty)):
            $dn_qty=$dn_data->qty;
            endif;
    $status='';
    $diffrence=round($data->qty-$dn_qty);
    $status='all';
    if ($dn_qty==''):
        $status='Open';
        $open++;
    elseif($dn_qty!='' && $diffrence!=0):
        $status='partial';
        $parttial++;
    elseif($diffrence==0):
        $status='Complete';
        $complete++;
    endif;

            $validation='all';
            if ($radio==1):
     if ($dn_qty==''):
            $validation=$status;
            endif;
            endif;;
         if ($radio==2):
    if($dn_qty!='' && $diffrence!=0):
         $validation=$status;
    endif; endif;;
             if ($radio==3):
     if($diffrence==0):
         $validation=$status;

     endif; endif;;
            if ($radio==''):
                $validation=$status;
            endif;






    $Am = DB::Connection('mysql2')->table('sales_order_data')->where('master_id',$row->id)->sum('amount');
    ?>
    @if ($validation==$status )
    <tr @if($status=='Open') style="background-color: #fdc8c8" @endif title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->so_date);?></td>
        <td class="text-center">{{$row['model_terms_of_payment']}}</td>
        <td class="text-center">{{$row['order_no']}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
        <td class="text-center">{{$customer->name}}</td>
        <td class="text-right"><?php echo number_format($Am+$row->sales_tax,2); $total += $Am+$row->sales_tax;?></td>
        {{--<td class="text-right">{{number_format($data->amount+$data->sales_tax,2) }} < ?php $total += $data->amount+$data->sales_tax;?></td>--}}
        <td>{{$status}}</td>
        <td class="text-center"><?php echo $row->username?></td>

        <td class="text-center">
            @if ($view==true)
                <button onclick="showDetailModelOneParamerter('sales/viewSalesOrderDetail','<?php echo $row->id ?>','View Sales Order')"
                        type="button" class="btn btn-success btn-xs">View</button>
            @endif
            @if ($edit==true)
                <a href="{{ URL::asset('sales/EditSalesOrder/'.$row->id.'?m='.$_GET['m']) }}" class="btn btn-warning btn-xs">Edit </a>
            @endif
            @if ($delete==true)
                <button onclick="sale_order_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                        type="button" class="btn btn-primery btn-xs">Delete</button>
            @endif
        </td>

        {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
    </tr>
    @endif
    <?php
    // for open
 ?>

@endforeach


<tr>
    <td class="text-center" colspan="7" style="font-size: 20px;">Total</td>
    <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($total,2)}}</td>
    <td class="text-center" colspan="2" style="font-size: 20px;"></td>

</tr>
<tr>
    <td colspan="7"></td>
    <td colspan="2" style="font-size: 18px;"><strong>Open</strong></td>
    <td style="font-size: 18px;"><strong><?php echo $open?></strong></td>
</tr>
<tr>
    <td colspan="7"></td>
    <td colspan="2" style="font-size: 18px;"><strong>Partial</strong></td>
    <td style="font-size: 18px;"><strong><?php echo $parttial?></strong></td>
</tr>
<tr>
    <td colspan="7"></td>
    <td colspan="2" style="font-size: 18px;"><strong>Complete</strong></td>
    <td style="font-size: 18px;"><strong><?php echo $complete?></strong></td>
</tr>