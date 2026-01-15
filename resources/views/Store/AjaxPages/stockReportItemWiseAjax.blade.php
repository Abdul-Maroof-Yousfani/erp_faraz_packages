<?php
use App\Helpers\ReuseableCode;
echo $from= $_GET['from_date'];
echo $to= $_GET['to_date'];
?>

<table style="font-size: small;"  class="table table-bordered table-responsive" >
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>
    <th class="text-center">Open. Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Open. Amount</th>

    <th class="text-center">Transaction Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Transaction Amount</th>

    </thead>
    <tbody id="filterDemandVoucherList">
    <?php $counter=1;$total=0; $purchase_total=0; $purchase_qty=0; $open_qty=0;$open_amount=0; ?>
    @foreach($data as $row)
        <?php
        if ($from=='2020-07-01'):
        $openin=ReuseableCode::get_opening_stock($row->sub_ic_id,$from,$to);
        else:
            $openin=[0,0];
        endif;
        $purchase=ReuseableCode::get_sum_stock($row->sub_ic_id,$from,$to);

        ?>

        <tr title="{{$row->sub_ic_id}}">
            <td>{{$counter++}}</td>
            <td class="text-center"><small>{{$row->sub_ic}}</small></td>
            <td class="text-right"><?php echo  number_format($openin[0],2) ?></td>
            <?php $open_qty+=$openin[0]; ?>
            <?php $open_amount+=$openin[1]; ?>
            <td></td>
            <td class="text-right"><?php echo number_format($openin[1],2) ?></td>
            <td class="text-right"><?php echo number_format($purchase[0],2) ?></td>
            <td class="text-right"></td>
            <td class="text-right"><?php echo number_format($purchase[1],2) ?></td>
        <?php
        $purchase_qty+=$purchase[0];
        $purchase_total+=$purchase[1]; ?>
        @endforeach
    <tr style="background-color: darkgray">
        <td colspan="2">Total</td>
        <td class="text-right">{{number_format($open_qty,2)}}</td>

        <td></td>
        <td class="text-right">{{number_format($open_amount,2)}}</td>
        <td class="text-right">{{number_format($purchase_qty,2)}}</td>
        <td></td>
        <td class="text-right">{{number_format($purchase_total,2)}}</td>
    </tr>
    <tr style="background-color: yellow">
        <td colspan="6">Net Total</td>
        <td class="text-right">{{number_format($open_qty+$purchase_qty,2)}}</td>
        <td class="text-right">{{number_format($open_amount+$purchase_total,2)}}</td>
    </tr>
    </tbody>

</table>



<table style="font-size: small;"  class="table table-bordered table-responsive" >
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>
    <th class="text-center">Purchase Qty</th>
    <th class="text-center">Average Rate</th>
    <th class="text-center">Purchase Amount</th>


    <th class="text-center">Transfer  Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Transfer Amount</th>



    <th class="text-center">Issued Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Amount</th>


    <th class="text-center">Rerurn Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Rate Amount</th>


    <th class="text-center">Remaining Qty</th>
    <th class="text-center">Remaining Amount</th>

    </thead>
    <tbody id="filterDemandVoucherList">
    <?php $counter=1; $net_qty=0; $net_amount=0; ?>
    @foreach($data as $row)
        <?php $rate=0;

     $type=0;

        ?>

        <tr title="{{$row->sub_ic_id}}">
            <td>{{$counter++}}</td>
            <td class="text-center"><small>{{$row->sub_ic}}</small></td>

            <?php $purchase_data=ReuseableCode::get_amount_voucher_wise($from,$to,$row->sub_ic_id,0,1);

            $purchase_qty=$purchase_data->qty;
            $purchase_amount=$purchase_data->amount;
            ?>

            <td style="background-color: lightyellow" class="text-right"><?php echo  number_format($purchase_qty,2) ?></td>
            <td style="background-color: lightyellow"  class="text-right"><?php if ($purchase_data->amount>0): $rate=$purchase_data->amount / $purchase_data->qty; echo  number_format($rate,2); endif; ?></td>
            <td style="background-color: lightyellow"  @if ($purchase_amount==0) style="background-color: antiquewhite" @endif class="text-right">
           <?php if ($purchase_amount>0):  echo  number_format($purchase_amount,2);  $type=1;else: $purchase_amount=0; endif?></td>



            <?php

                $transfer_data=ReuseableCode::get_amount_voucher_wise($from,$to,$row->sub_ic_id,1,1);
                $transfer_qty=$transfer_data->qty;
            ?>

            <td class="text-right"><?php  echo  number_format($transfer_qty,2) ?></td>
            <td class="text-right">{{number_format($rate,2)}}</td>
            <?php $tranfer_amount=$transfer_qty*$rate; ?>
            <td class="text-right">{{number_format($tranfer_amount,2)}}</td>

            <?php if ($transfer_qty>0): $type=1; endif; ?>


            <?php $issue_data=ReuseableCode::get_amount_voucher_wise($from,$to,$row->sub_ic_id,0,2);
            $issue_qty=$issue_data->qty;
            ?>

            <td style="background-color: lightyellow" class="text-right"><?php  echo  number_format($issue_qty,2) ?></td>
            <td style="background-color: lightyellow" class="text-right">{{number_format($rate,2)}}</td>
            <?php $issue_amount=$issue_qty*$rate; ?>
            <td style="background-color: lightyellow" class="text-right">{{number_format($issue_amount,2)}}</td>


            <?php if ($issue_qty>0): $type=1; endif; ?>


            <?php $return_data=ReuseableCode::get_amount_voucher_wise($from,$to,$row->sub_ic_id,0,3);
            $return_qty=$return_data->qty;
            ?>

            <td style="" class="text-right"><?php  echo  number_format($return_qty,2) ?></td>
            <td style="" class="text-right">{{number_format($rate,2)}}</td>
            <?php $return_amount=$return_qty*$rate; ?>
            <td style="" class="text-right">{{number_format($return_amount,2)}}</td>


            <?php if ($return_qty>0): $type=1; endif; ?>


            <?php $remining_qty=$purchase_qty+$transfer_qty-$issue_qty+$return_qty;
                  $remaining_amount=$purchase_amount+$tranfer_amount-$issue_amount+$return_amount;
            $net_qty+=$remining_qty;
            $net_amount+=$remaining_amount;
            ?>
            <td class="text-right">{{number_format($remining_qty,2)}}</td>
            <td class="text-right">{{number_format($remaining_amount,2)}}</td>
            {{--<td><input type="checkbox" value="{{$row->sub_ic_id}}" onclick="done({{$row->sub_ic_id}})"/> </td>--}}
            {{----}}
            <td>
            @if ($type==1)
                <?php $data=DB::Connection('mysql2')->table('stock')->where('status',1)->where('sub_item_id',$row->sub_ic_id)->where('amount',0)->where('qty','>',0)->
                    where('opening',1)->first();
                   if (!empty($data)): echo $data->sub_item_id; endif
                    ?>

                @endif
            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="12"></td>
        <td class="text-right">{{$net_qty}}</td>
        <td class="text-right">{{ number_format($net_amount,2)}}</td>
    </tr>


    </tbody>

</table>
