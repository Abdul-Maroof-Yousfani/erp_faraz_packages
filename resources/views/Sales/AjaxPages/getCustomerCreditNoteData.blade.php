<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;


$view=ReuseableCode::check_rights(124);
$edit=ReuseableCode::check_rights(125);
$export=ReuseableCode::check_rights(258);
$counter = 1;$total=0;
$OverAllTotal = 0;

?>

@foreach($credit_note as $row)
    <?php $customer=CommonHelper::byers_name($row->buyers_id);
    $data=SalesHelper::get_total_amount_for_sales_order_by_id($row->so_id);
    $SoNo = "";
    if($row->so_id > 0):
        $SoData = CommonHelper::get_single_row('sales_order','id',$row->so_id);
        $SoNo = $SoData->so_no;
    else:
        $SoNo = "";
    endif;
    $NetAmount = DB::Connection('mysql2')->table('stock')->where('voucher_no',$row->cr_no)->select('amount')->sum('amount');

    $t_amount = DB::Connection('mysql2')->table('transactions')->where('voucher_no',$row->cr_no)->where('status',1)->
    where('debit_credit',1)->where('voucher_type',9)->select('amount')->sum('amount');
    $OverAllTotal+=$NetAmount;
    ?>
    <tr title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center"><?php echo strtoupper($SoNo)?></td>
        <td class="text-center">@if($row->type==1) DN @else SI @endif</td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->cr_no)}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->cr_date);?></td>
        <td class="text-center"><?php   $customer=CommonHelper::byers_name($row->buyer_id);
            echo   $customer->name;
            ?></td>
        <td class="text-center"><?php echo number_format($NetAmount,2).'</br>'.number_format($t_amount,2);?></td>



        <td class="text-center">
            <?php if($view == true):?>
            <button onclick="showDetailModelOneParamerter('sales/viewCreditNoteDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                    type="button" class="btn btn-success btn-xs">View</button>
            <?php endif;?>
            <?php if($edit == true):?>
            <a href="{{ URL::asset('sales/editSalesReturn/'.$row->id.'?m='.$m) }}" class="btn btn-primary btn-xs">Edit </a>
                <button onclick="delete_sales_return('{{$row->id}}','{{$row->cr_no}}')" type="button" class="btn btn-danger btn-xs">Delete</button>
            <?php endif;?>
        </td>

        {{--<td class="text-center"><button onclick="DeleteSalesReturn('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
    </tr>


@endforeach
<tr>
    <td colspan="6"><strong>TOTAL</strong></td>
    <td class="text-center"><?php echo number_format($OverAllTotal,2)?></td>
</tr>