<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
?>

<h2 style="text-align: center">Stock Summary Report</h2>




<table id="data" class="table table-bordered table-responsive">
    <label for="">Show Detail</label>
    <input type="checkbox" id="CheckUnCheck" onclick="ShowHideDetail()">

    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>
    <th class="text-center">UOM</th>
    <th class="text-center">Location</th>
    <th class="text-center ShowHideTd" style="display: none">Average Cost</th>

    <th class="text-center ShowHideTd" style="display: none">Purchase QTY.</th>
    <th class="text-center ShowHideTd" style="display: none">Purchase Return.</th>
    <th class="text-center ShowHideTd" style="display: none">Transferd QTY.</th>

    <th class="text-center ShowHideTd" style="display: none">Sales QTY.</th>
    <th class="text-center ShowHideTd" style="display: none">Sales Return QTY.</th>
    <th class="text-center">In Stock</th>

    </thead>
    <tbody id="filterDemandVoucherList">
    <?php
    $counter=1;
    ?>
    @foreach($category as $data)
        <?php



        $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
        $sub_ic_data=explode(',',$sub_ic_data);
        $uom=$sub_ic_data[0];
        $sub_item_id=$sub_ic_data[4];


        $purchase_amount= CommonHelper::get_amount_from_stock(1,$data->sub_item_id,$data->warehouse_id);
        $purchase_return= CommonHelper::get_amount_from_stock(2,$data->sub_item_id,$data->warehouse_id);
        $stock_tarnsfer= CommonHelper::get_amount_from_stock(3,$data->sub_item_id,$data->warehouse_id);
        $stock_received= CommonHelper::get_amount_from_stock(4,$data->sub_item_id,$data->warehouse_id);
        $sales_qty= CommonHelper::get_amount_from_stock(5,$data->sub_item_id,$data->warehouse_id);
        $sales_return_qty= CommonHelper::get_amount_from_stock(6,$data->sub_item_id,$data->warehouse_id);


        // for amount
        $purchase_value= CommonHelper::get_value_stock(1,$data->sub_item_id,$data->warehouse_id);
        $purchase_value_return= CommonHelper::get_value_stock(2,$data->sub_item_id,$data->warehouse_id);
        $stock_value_transfer= CommonHelper::get_value_stock(3,$data->sub_item_id,$data->warehouse_id);
        $stock_value_received= CommonHelper::get_value_stock(4,$data->sub_item_id,$data->warehouse_id);
        $sales_value= CommonHelper::get_value_stock(5,$data->sub_item_id,$data->warehouse_id);
        $sales_value_return= CommonHelper::get_value_stock(6,$data->sub_item_id,$data->warehouse_id);
        //end



        //if ($purchase_amount >0 || $purchase_return>0 || $stock_tarnsfer>0 || $sales_qty>0):
        $actual_qtyt= $purchase_amount-$purchase_return-$stock_tarnsfer+$stock_received-$sales_qty;
        $actual_amount=$purchase_value-$purchase_value_return-$stock_value_transfer+$stock_value_received-$sales_value;
        if ($actual_amount>0):
            if ($actual_qtyt==0):
                $actual_qtyt=1;
            endif;
            $average=$actual_amount / $actual_qtyt;
        else:
            $average=0;
        endif;
        ?>
        <tr>
            <td class="text-center">{{$counter++}}</td>
            <td class="text-center">
                <a class="" href="<?php echo url('/') ?>/store/fullstockReportView?pageType=&&parentCode=97&&m=<?php echo $_GET['m'] ?>&&sub_item_id=<?php echo $data->sub_item_id ?>&&warehouse_id=<?php echo $data->warehouse_id ?>#SFR" target="_blank">
                    {{CommonHelper::get_item_name($data->sub_item_id)}}</a>
            </td>
            <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
            <td class="text-center">{{CommonHelper::get_name_warehouse($data->warehouse_id)}}</td>

            <td class="text-center ShowHideTd" style="display: none;">{{number_format($average,2)}}</td>
            <td class="text-center ShowHideTd" style="display: none;">{{number_format($purchase_amount,2)}}</td>


            <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($purchase_return,2); ?></td>
            <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($stock_tarnsfer,2); ?></td>
            <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($sales_qty,2); ?></td>
            <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($sales_return_qty,2); ?></td>

            <td class="text-center"> <?php $in_stock=$purchase_amount-$purchase_return-$stock_tarnsfer-$sales_qty+$sales_return_qty;
                echo number_format($in_stock,2);
                ?></td>
        </tr>
        <?php //endif; ?>
    @endforeach

    </tbody>
</table>
<script !src="">
    function ShowHideDetail()
    {
        //alert(); return false;
        if($('#CheckUnCheck').is(":checked"))
        {
            $('.ShowHideTd').fadeIn();
        }
        else{
            $('.ShowHideTd').fadeOut();
        }
    }
</script>

