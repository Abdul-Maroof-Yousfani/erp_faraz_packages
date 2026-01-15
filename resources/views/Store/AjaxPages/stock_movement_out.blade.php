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
    <th class="text-center">Sales On DN</th>
    <th class="text-center">Sales Return On DN</th>
    <th class="text-center">Purchase  Return GRN</th>
    <th class="text-center">Purchase  Return PI</th>
    <th class="text-center">Stock Transfer</th>
    <th class="text-center">Work Order Issuence</th>



    </thead>
    <tbody id="">

    <?php
    $total_sales_on_dn=0;
    $total_stock_return_on_grn=0;
    $total_stock_return_on_pi=0;
    $total_transfer=0;
    $total_work_oorder_issuence=0;
    $total_sales_return_on_dn=0;
    $count=1;
    ?>
    @foreach($data as $row)

        <?php
                //
        $sales_dn=ReuseableCode::get_stock_type_wise_for_in_recon($from,$to,$row->sub_item_id,5,0);
        $sales_dn=$sales_dn[1];
        $total_sales_on_dn+=$sales_dn;

                //

                    //
        $return_data_on_grn=ReuseableCode::purchase_return_on_grn($from,$to,$row->sub_item_id,1);
        $total_stock_return_on_grn+=$return_data_on_grn;
                //


        //
        $return_data_on_pi=ReuseableCode::purchase_return_on_grn($from,$to,$row->sub_item_id,2);
        $total_stock_return_on_pi+=$return_data_on_pi;
        //


        //
        $transfer=ReuseableCode::get_stock_type_wise_for_in_recon($from,$to,$row->sub_item_id,3,1);
        $transfer=$transfer[1];
        $total_transfer+=$transfer;
        //

        //
        $work_oorder_issuence=ReuseableCode::get_stock_type_wise_for_in_recon_pos($from,$to,$row->sub_item_id,5,2);
        $work_oorder_issuence=$work_oorder_issuence[1];
        $total_work_oorder_issuence+=$work_oorder_issuence;
        //




                //
        $return_on_dn=ReuseableCode::sales_return($from,$to,$row->sub_item_id,1);
        $total_sales_return_on_dn+=$return_on_dn;
                //

        ?>
        <tr class="text-center" title="{{$row->sub_item_id}}">
            <td title="opeing">{{$count++}}</td>
            <td title="sub item"><small>{{$row->sub_ic}}</small></td>
            <td title="Sales Value On DN"><small>{{number_format($sales_dn,2)}}</small></td>
            <td title="Sales Return On DN"><small>{{number_format($return_on_dn,2)}}</small></td>
            <td title="Purchase Return On GRN"><small>{{number_format($return_data_on_grn,2)}}</small></td>
            <td title="Purchase Return On PI"><small>{{number_format($return_data_on_pi,2)}}</small></td>
            <td title="transfer"><small>{{number_format($transfer,2)}}</small></td>
            <td title="work order issuence"><small>{{number_format($work_oorder_issuence,2)}}</small></td>




    @endforeach
    <tr style="font-weight: bold" class="text-center">
        <td colspan="2">Total</td>

        <td><?php echo number_format($total_sales_on_dn,2); ?></td>
        <td><?php echo number_format($total_sales_return_on_dn,2); ?></td>
        <td><?php echo number_format($total_stock_return_on_grn,2); ?></td>
        <td><?php echo number_format($total_stock_return_on_pi,2); ?></td>
        <td><?php echo number_format($total_transfer,2); ?></td>
        <td><?php echo number_format($total_work_oorder_issuence,2); ?></td>


    </tr>
    </tbody>
</table>



