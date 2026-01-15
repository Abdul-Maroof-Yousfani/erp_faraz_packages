<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
?>
<div style="text-align: center">
    <h3>Stock Movement Report Inventory</h3>
    <h4>From : {{CommonHelper::changeDateFormat($from).' TO: '.CommonHelper::changeDateFormat($to)}}</h4>
</div>
<table id="EmpExitInterviewList" class="table table-bordered table-responsive">
    <thead>
    <th class="text-center">S.No</th>
    <th  class="text-center">Item</th>
    <th class="text-center">Open. Amount</th>
    <th class="text-center">Purchase Amount On GRN</th>
    <th class="text-center">Purchase Return Amount On GRN</th>





    <th class="text-center">Purchase Amount On Work Order</th>

    <th class="text-center">Sales Return Amount On DN</th>
    <th class="text-center">Sales Return Amount On SI</th>
    <th class="text-center">Stock Return Amount On Work Order   </th>
    <th class="text-center">Stock Transfer Amount </th>
    <th class="text-center">Total Amount</th>



    </thead>
    <tbody id="">

    <?php
    $count=1;
    $total_open=0;
    $total_purchase_amount=0;
    $total_purchase_amount_on_work_order=0;
    $total_sales_return_on_dn=0;
    $total_sales_return_on_si=0;
    $total_stock_transfer=0;
    $total_sales_return_on_work_order=0;

     $total_return_data_on_grn=0;
    ?>
    @foreach($data as $row)

        <?php

        $purchase_side=0;
        // open process
        $open_data=ReuseableCode::get_opening($from,$to,$accyeafrom,$row->sub_item_id,1);
      //  $open_qty=$open_data[0];
        $open_amount=$open_data[1];
        $total_open+=$open_amount;

        // for purchase
        $type='1';
        $transfer_status=0;
        $in_data=ReuseableCode::get_stock_type_wise_for_in_recon($from,$to,$row->sub_item_id,$type,$transfer_status);
       // $in_qty=$in_data[0];
        $purchase_amount=$in_data[1];
        $total_purchase_amount+=$purchase_amount;



                // return on grn
        $type=1;
        $return_data=ReuseableCode::purchase_return_on_grn($from,$to,$row->sub_item_id,$type);
        $total_return_data_on_grn+=$return_data;

        // for purchase on work order
        $type='1';
        $pos_status=0;
        $in_data=ReuseableCode::get_stock_type_wise_for_in_recon_pos($from,$to,$row->sub_item_id,$type,4);
        // $in_qty=$in_data[0];
        $purchase_amount_on_work_order=$in_data[1];
        $total_purchase_amount_on_work_order+=$purchase_amount_on_work_order;

        // for sales return on dn
        $type='6';
        $type=1;
        $in_data=ReuseableCode::sales_return($from,$to,$row->sub_item_id,$type);
        // $in_qty=$in_data[0];
        $sales_return_amount_on_dn=$in_data;
        $total_sales_return_on_dn+=$sales_return_amount_on_dn;


        // for sales return on si
        $type='6';
        $type=2;
        $in_data=ReuseableCode::sales_return($from,$to,$row->sub_item_id,$type);
        // $in_qty=$in_data[0];
        $sales_return_amount_on_si=$in_data;
        $total_sales_return_on_si+=$sales_return_amount_on_si;


        // for sales return on work order
        $type='1';
        $pos_status=3;
        $in_data=ReuseableCode::get_stock_type_wise_for_in_recon_pos($from,$to,$row->sub_item_id,$type,$pos_status);
        // $in_qty=$in_data[0];
        $sales_return_amount_on_work_order=$in_data[1];
        $total_sales_return_on_work_order+=$sales_return_amount_on_work_order;


        // stock transfer amount
        $type='1';
        $transfer_status=1;
        $in_data=ReuseableCode::get_stock_type_wise_for_in_recon($from,$to,$row->sub_item_id,$type,$transfer_status);
        // $in_qty=$in_data[0];
        $stock_transfer_amount=$in_data[1];
        $total_stock_transfer+=$stock_transfer_amount;



        ?>
        <tr class="text-center" title="{{$row->sub_item_id}}">
            <td title="opeing">{{$count++}}</td>
            <td title="sub item"><small>{{$row->sub_ic}}</small></td>
            <td title="opening amount"><small>{{number_format($open_amount,2)}}</small></td>
            <td title="purchase amount"><small>{{number_format($purchase_amount,2)}}</small></td>
            <td title="purchase return"><small>{{number_format($return_data,2)}}</small></td>
            <td title="purchase amount from work order"><small>{{number_format($purchase_amount_on_work_order,2)}}</small></td>
            <td title="sales return on dn"><small>{{number_format($sales_return_amount_on_dn,2)}}</small></td>
            <td title="sales return on  si"><small>{{number_format($sales_return_amount_on_si,2)}}</small></td>
            <td title="sales return on work order"><small>{{number_format($sales_return_amount_on_work_order,2)}}</small></td>
            <td title="stoc transfer"><small>{{number_format($stock_transfer_amount,2)}}</small></td>
            <td><small>{{number_format(
            $open_amount+
            $purchase_amount+
            $purchase_amount_on_work_order+
            $sales_return_amount_on_dn+
            $sales_return_amount_on_si+
            $sales_return_amount_on_work_order+
            $stock_transfer_amount,2)}}</small></td>




    @endforeach
    <tr class="text-center">
        <td colspan="2">Total</td>
        <td><?php echo number_format($total_open,2); ?></td>
        <td><?php echo number_format($total_purchase_amount,2); ?></td>
        <td><?php echo number_format($total_return_data_on_grn,2); ?></td>
        <td><?php echo number_format($total_purchase_amount_on_work_order,2); ?></td>
        <td><?php echo number_format($total_sales_return_on_dn,2); ?></td>
        <td><?php echo number_format($total_sales_return_on_si,2); ?></td>
        <td><?php echo number_format($total_sales_return_on_work_order,2); ?></td>
        <td><?php echo number_format($total_stock_transfer,2); ?></td>
        <?php $total=
                $total_purchase_amount-
                $total_return_data_on_grn+
                $total_purchase_amount_on_work_order-
                $total_sales_return_on_dn+
                $total_sales_return_on_si+
                $total_sales_return_on_work_order-
                $total_stock_transfer; ?>
        <td><?php echo number_format($total,2) ?></td>


    </tr>
    </tbody>
</table>



