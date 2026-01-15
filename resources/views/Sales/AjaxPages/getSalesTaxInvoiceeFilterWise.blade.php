<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(118);
$edit=ReuseableCode::check_rights(119);
$delete=ReuseableCode::check_rights(120);

$counter = 1;
$total = 0;
$open = 0;
$parttial = 0;
$complete = 0;
?>

@foreach($sales_tax_invoice as $row)
    <?php
    $data=SalesHelper::get_total_amount_for_sales_tax_invoice_by_id($row->id);
    $fright=SalesHelper::get_freight($row->id);

    $amount=$data->amount+$row->sales_tax+$fright;

    $received_amount=SalesHelper::get_received_amount($row->id);
    $main_amount=$amount;
    $diffrence= $main_amount-$received_amount;

    if ($diffrence < 0):
        $diffrence = 0;
    endif;

    if ($diffrence==$main_amount):
        $status='Open';
        $open++;
    elseif($main_amount!='' && $diffrence!=0):
        $status='partial';
        $parttial++;
    elseif($diffrence==0):
        $status='Complete';
        $complete++;
    endif;

    $customer=CommonHelper::byers_name($row->buyers_id);
    $BuyersUnit = '';
    $BuyerOrderNo = '';
    if($row->so_id != 0 ):
        $SoData = DB::Connection('mysql2')->table('sales_order')->where('id',$row->so_id)->select('purchase_order_no','buyers_unit')->first();
        $BuyersUnit = $SoData->buyers_unit;
        $BuyerOrderNo = $SoData->purchase_order_no;
    endif;
    ?>
    <tr @if($status=='Open')  @elseif($status=='partial') @endif title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td title="{{$row->id}}" class="text-center">@if(!empty($row->so_no)){{strtoupper($row->so_no)}}@else Direct Sale @endif</td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gi_no)}}</td>
        <td class="hide">
            <input type="text" id="ScNo<?php echo $row->id?>" class="form-control" value="<?php echo $row->sc_no ?>">
            <button type="button" class=" btn btn-xs btn-success" id="BtnUpdate<?php echo $row->id?>" onclick="UpdateValue('<?php echo $row->id?>')">Update</button>
            <span id="ScNoError<?php echo $row->id?>"></span>
        </td>
        <td class="text-center"><?php echo $BuyersUnit?></td>
        <td class="text-center"><?php echo $BuyerOrderNo?></td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->gi_date);?></td>
        <td class="text-center hide">{{ $row->model_terms_of_payment }}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
        <td class="text-center">{{$customer->name??''}}</td>
        <td class="text-right">{{number_format($data->amount+$row->sales_tax+$row->sales_tax_further+$fright+$row->cartage_amount + $row->advance_tax_amount,2)}}</td>
        <td class="text-center">{{$status}}</td>
        <td id="stat{{ $row->id }}" class="text-center"><?php echo SalesHelper::si_status($row->si_status)?></td>
        <?php $total+=$data->amount+$row->sales_tax+$fright; ?>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        @if($view == true)
                            <a onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                                type="button" class=""><i class="fa-regular fa-eye"></i> View</a>
                            <a target="_blank" class="" href="<?php echo url('/')?>/sales/PrintSalesTaxInvoiceDirect?id=<?php echo $row->id?>"><i class="fa-solid fa-print"></i> Print</a>
                        @endif
                        @if($edit == true)
                            @if($row->si_status == 1)
                                <a target="_blank" href="{{ route('editDirectSalesTaxInvoice', ['id' => $row->id, 'm' => $m]) }}"><i class="fa-solid fa-pencil"></i> Edit</a>
                            @endif
                        @endif
                        
                        @if($delete == true)
                            <a onclick="sales_tax_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                                type="button" class=""><i class="fa-solid fa-trash"></i> Delete</a>
                        @endif
                    </li>
                </ul>
            </div>
        </td>
        {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class=""><i class="fa-solid fa-pencil"></i> Edit </a></td>--}}
        {{--<td class="text-center"><a onclick="delete_record('{{$row->id}}')" type="button" class=""><i class="fa-solid fa-trash"></i>  DELETE</b></td>--}}
    </tr>


@endforeach

<tr>
    <td class="text-center" colspan="10" >Total</td>
    <td class="text-right">{{number_format($total,2)}}</td>
    <td class="text-center" colspan="3"></td>

</tr>
<tr>
    <td class="text-center" colspan="10"><strong>Open</strong></td>
    <td><strong><?php echo $open?></strong></td>
    <td class="text-center" colspan="3"></td>
</tr>
<tr>
    <td class="text-center" colspan="10"><strong>Partial</strong></td>
    <td><strong><?php echo $parttial?></strong></td>
    <td class="text-center" colspan="3"></td>
</tr>
<tr>
    <td class="text-center" colspan="10"><strong>Complete</strong></td>
    <td><strong><?php echo $complete?></strong></td>
    <td class="text-center" colspan="3"></td>
</tr>