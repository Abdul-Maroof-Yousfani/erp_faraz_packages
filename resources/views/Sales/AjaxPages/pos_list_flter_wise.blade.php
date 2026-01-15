<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\SalesHelper; ?>
<?php echo Form::open(array('url' => 'finance/CreateReceiptVoucherForSales?type=pos','id'=>'cashPaymentVoucherForm'));?>
<table class="table table-bordered sf-table-list" id="pos">
    <thead>
    <th></th>
    <th class="text-center">S.No</th>
    <th class="text-center">POS NO</th>
    <th class="text-center">POS Date</th>
    <th class="text-center">Customer Name</th>

    <th class="text-center">Total Amount</th>

    <th class="text-center">Additional Expenses</th>
    <th class="text-center">Net Amount</th>
    <th class="text-center">Return Amount</th>
    <th class="text-center">Paid Amount</th>
    <th class="text-center">Remaining Amount</th>

    <th class="text-center">Action</th>

    </thead>
    <tbody id="data">

    <?php

    $counter=1;
    $total_amount=0;
     $total_paid=0;
     $total_remaining=0;
    ?>
    @foreach($data as $row)
        <?php

       $dataa= SalesHelper::get_pos_qty_amount($row->id,0);
       $additional_data= SalesHelper::get_pos_qty_amount($row->id,1);
       $total=$dataa->amount-$dataa->discount_amount+$additional_data->amount;
       $rece=SalesHelper::get_received_payment_for_pos($row->id);
       $return_data=SalesHelper::get_return($row->pos_no,3);
        $return_amount=0;
        if (!empty($return_data)):


            $return_amount=$return_data->amount;
            endif;



       $rmaining=  $total-$rece-$return_amount;
       $total_paid+=$rece;
       $total_amount+=$total;
        $total_remaining+=$rmaining;
        ?>
        <tr title="" id="{{$row->id}}">

            <td class="text-center"> @if ($rmaining>0)<input name="checkbox[]"  type="checkbox" value="{{$row->id}}" style="height: 30px;">@endif</td>
            <td class="text-center">{{$counter++}}</td>
            <td class="text-center"><?php echo strtoupper($row->pos_no)?></td>
            <td class="text-center">{{CommonHelper::changeDateFormat($row->pos_date)}}</td>
            <td title="{{$row->id}}" class="text-center">{{ucwords($row->customer_name)}}</td>

            <td class="text-right"><?php   echo number_format($dataa->amount,2);?></td>

            <td class="text-right"><?php   echo number_format($additional_data->amount,2);?></td>
            <td class="text-right"><?php   echo number_format($total,2);?></td>
            <td class="text-right"><?php   echo number_format($return_amount,2);?></td>
            <td class="text-right"><?php   echo number_format($rece,2);?></td>
            <td class="text-right"><?php   echo number_format($rmaining,2);?></td>


            <td class="text-center">

                <button onclick="showDetailModelOneParamerter('sales/po_detail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                        type="button" class="btn btn-success btn-xs">View</button>
                @if ($rece==0)
                <button onclick="showDetailModelOneParamerter('sales/po_detail?return=1','<?php echo $row->id ?>','View Sales Tax Invoice')"
                        type="button" class="btn btn-primary btn-xs">Return</button>
                @endif


                <button onclick="delete_pos('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">Delete</button>



            </td>


        </tr>


    @endforeach
    <tr style="font-size: large;font-weight: bold">
        <td colspan="7">Total</td>
        <td class="text-right" colspan="1">{{number_format($total_amount,2)}}</td>
        <td></td>
        <td class="text-right" colspan="1">{{number_format($total_paid,2)}}</td>
        <td class="text-right" colspan="1">{{number_format($total_remaining,2)}}</td>
        <td></td>
    </tr>



    </tbody>
</table>
    <div>
        <input class="btn btn-primary" type="submit">
    </div>
<?php Form::close(); ?>