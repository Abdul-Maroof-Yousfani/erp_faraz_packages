<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
$RcCount = $RecCount;
?>
<div style="text-align: center">
    <h3>Stock Movement Report Inventory</h3>
    <h4>From : {{CommonHelper::changeDateFormat($from).' TO: '.CommonHelper::changeDateFormat($to)}}</h4>
</div>
<table id="EmpExitInterviewList" class="table table-bordered table-responsive">
    <thead>
    <th class="text-center">S.No</th>
    <th  class="text-center">Item</th>
    <th class="text-center">Open. QTY</th>
    <th class="text-center">Open. Amount</th>
    <th class="text-center">IN QTY</th>
    <th class="text-center">IN Amount</th>
    <th class="text-center">OUT QTY</th>
    <th class="text-center">OUT Amount</th>
    <th class="text-center">Open DN</th>
    <th class="text-center">Open Return</th>
    <th class="text-center">IN Stock QTY</th>
    <th class="text-center">IN Stock Amount</th>
    </thead>
    <tbody id="AppendData">
    @php
    $count=1;
    $total_open_qty=0;
    $total_open_amount=0;
    $total_in_qty=0;
    $total_in_amount=0;
    $cl_qty=0;
    $cl_amount=0;
    $tot_out_qty=0;
    $tot_out_amount=0;
    $total_incomplete_dn=0;
    $total_incomplete_return=0;
    $total_purchase_side=0;
    @endphp
    <?php
    $cr_no=[];
    $dataa= DB::Connection('mysql2')->select('select a.cr_no from credit_note a
               inner JOIN credit_note_data b
               ON a.id=b.master_id
               inner join delivery_note_data c
               ON b.voucher_data_id=c.id
               inner join delivery_note d
               ON c.master_id=d.id
               where a.status=1
               and b.status=1
               and a.type=1
               and d.sales_tax_invoice=1
               GROUP by a.cr_no');


    foreach($dataa as $row):

        $cr_no[]='"'.$row->cr_no.'"';
    endforeach;
    $cr_value= implode(',',$cr_no);
    //implode(',',$data->cr_no);

    ?>
    @foreach($data as $row)

        <?php

        $purchase_side=0;
        // open process
        $open_data=ReuseableCode::get_opening($from,$to,$accyeafrom,$row->sub_item_id,1);
        $open_qty=$open_data[0];
        $open_amount=$open_data[1];

        // in process

        $type='1,4,6';
        $in_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,$type);
        $in_qty=$in_data[0];
        $in_amount=$in_data[1];


        // out process
        $type='5,2,3';
        $out_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,$type);
        $out_qty=$out_data[0];
        $out_amount=$out_data[1];

        $remianig_amount=0;
        $remianig_qty=0;
        $remianig_qty=$open_qty+$in_qty-$out_qty;
        $remianig_amount=$open_amount+$in_amount-$out_amount;


        ?>
        <tr title="{{$row->sub_item_id}}">
            <td class="LoopCount">{{$count++}}</td>
            <td><small>{{$row->sub_ic}}</small></td>
            <td><small>{{number_format($open_qty,2)}}</small></td>
            <td><small>{{number_format($open_amount,2)}}</small></td>
            <td><small>{{number_format($in_qty,2)}}</small></td>
            <td><small>{{number_format($in_amount,2)}}</small></td>
            <td><small>{{number_format($out_qty,2)}}</small></td>
            <td><small>{{number_format($out_amount,2)}}</small></td>
            <input type="hidden" class="OpenQty" value="<?php if($open_qty != ""){echo $open_qty;}else{echo "0";}?>">
            <input type="hidden" class="OpenAmount" value="<?php if($open_amount != ""){echo $open_amount;}else{echo "0";}?>">
            <input type="hidden" class="InQty" value="<?php if($in_qty > 0){echo $in_qty;}else{echo "0";}?>">
            <input type="hidden" class="InAmount" value="<?php if($in_amount > 0 ){echo $in_amount;}else{echo "0";}?>">
            <input type="hidden" class="OutQty" value="<?php if($out_qty > 0){echo $out_qty;}else{echo "0";}?>">
            <input type="hidden" class="OutAmount" value="<?php if($out_amount > 0 ){echo $out_amount;}else{echo "0";}?>">

            <?php


            $incomplete_dn_on_dn=DB::Connection('mysql2')->selectOne('select sum(b.amount)amount from  delivery_note a
           inner join
             stock b
             on
            a.gd_no=b.voucher_no
             where a.status=1
            and b.status=1
            and a.gd_date between "'.$from.'" and "'.$to.'"
            and b.sub_item_id="'.$row->sub_item_id.'"
            and a.sales_tax_invoice=0');


            $incomplete_dn_on_supply_chain=DB::Connection('mysql2')->selectOne('select sum(amount)amount
                from  transaction_supply_chain
                where status=1
                and item_id="'.$row->sub_item_id.'"
                and voucher_type=3
                and ref_date between "'.$from.'" and "'.$to.'"
                and voucher_date not between "'.$from.'" and "'.$to.'"');


            $incomplete_dn=0;
            if (!empty($incomplete_dn_on_dn->amount)):
                $incomplete_dn_on_dn->amount;
                $incomplete_dn+=$incomplete_dn_on_dn->amount;

            endif;

            if (!empty($incomplete_dn_on_supply_chain->amount)):
                $incomplete_dn+=$incomplete_dn_on_supply_chain->amount;

            endif;


            $incomplete_val=0;
            $incomplete_return=   DB::Connection('mysql2')->selectOne('select sum(c.amount)amount
            from  stock as c
            inner join
            purchase_return as a
            on

            c.voucher_no=a.pr_no
            where a.type=1
            and a.status=1
            and a.pr_date between "'.$from.'" and "'.$to.'"
            and c.sub_item_id="'.$row->sub_item_id.'"');;

            if (!empty($incomplete_return->amount)):
                $incomplete_dn+=$incomplete_return->amount;
                $incomplete_val=$incomplete_return->amount;
            endif;

            ?>

            <td>@if (!empty($incomplete_dn)) {{number_format($incomplete_dn,2)}}  @php $total_incomplete_dn+=$incomplete_dn; @endphp @endif</td>
            <input type="hidden" class="OpenDn" value="<?php if(!empty($incomplete_dn)){echo $incomplete_dn;}else{echo "0";}?>">
            <?php
            $clause='';
            if ($cr_value!=''):

                $clause='and a.cr_no not in ('.$cr_value.')';

            endif;
            $incomplete_sales_retrun=   DB::Connection('mysql2')->selectOne('select sum(c.amount)amount
            from  stock as c
            inner join
            credit_note a
            on
            c.voucher_no=a.cr_no
            where a.type=1
            '.$clause.'
            and a.status=1
            and a.cr_date between "'.$from.'" and "'.$to.'"
            and c.sub_item_id="'.$row->sub_item_id.'"');;
            ?>


            <td>

                <?php
                if (!empty($incomplete_sales_retrun->amount)):
                    $incomplete_sales_retrun=$incomplete_sales_retrun->amount;
                else:
                    $incomplete_sales_retrun=0;
                endif;
                echo number_format($incomplete_val+$incomplete_sales_retrun,2);
                $total_purchase_side+=$incomplete_val+$incomplete_sales_retrun ?></td>
            <input type="hidden" class="OpenReturn" value="<?php echo $incomplete_val+$incomplete_sales_retrun;?>">

            <td style="font-weight: bold"><small>{{number_format($remianig_qty,2)}}</small></td>
            <input type="hidden" class="InStockQty" value="<?php if($remianig_qty !=""){echo $remianig_qty;}else{echo "0";}?>">
            <td title="" @if ($remianig_amount<0) style="color: red" @endif style="font-weight: bold"><small>{{number_format($remianig_amount,2)}}</small></td>
            <input type="hidden" class="InStockAmount" value="<?php if($remianig_amount !=""){echo $remianig_amount;}else{echo "0";}?>">
        </tr>

        <?php
        $total_open_qty+=$open_qty;
        $total_open_amount+=$open_amount;
        $total_in_qty+=$in_qty;
        $total_in_amount+=$in_amount;

        $tot_out_qty+=$out_qty;
        $tot_out_amount+=$out_amount;

        $cl_qty+=$remianig_qty;
        $cl_amount+=$remianig_amount;
        ?>

    @endforeach
    </tbody>
    <tr id="AjaxMoreData"></tr>
    <tr>
        <td colspan="2">Total</td>
        <td colspan="1" id="OpenQtyHtml">{{number_format($total_open_qty,2)}}</td>
        <td colspan="1" id="OpenAmountHtml">{{number_format($total_open_amount,2)}}</td>
        <td colspan="1" id="InQtyHtml">{{number_format($total_in_qty,2)}}</td>
        <td colspan="1" id="InAmountHtml">{{number_format($total_in_amount,2)}}</td>

        <td colspan="1" id="OutQtyHtml">{{number_format($tot_out_qty,2)}}</td>
        <td colspan="1" id="OutAmountHtml">{{number_format($tot_out_amount,2)}}</td>
        <td colspan="1" id="OpenDnHtml">{{number_format($total_incomplete_dn,2)}}</td>
        <td colspan="1" id="OpenReturnHtml">{{number_format($total_purchase_side,2)}}</td>

        <td colspan="1" id="InStockQtyHtml">{{number_format($cl_qty,2)}}</td>
        <td colspan="1" id="InStockAmountHtml">{{number_format($cl_amount+$total_incomplete_dn-$total_purchase_side,2)}}</td>
    </tr>
    <tr class="text-center">
        <td id="LoadMoreArea" colspan="12">
            <button style="width: 50%" class="btn btn-md btn-primary" id="BtnLoadMore" onclick="GetMoreData('<?php echo $RcCount?>')">
                <strong style="font-size: 20px;">Load More <span id="Loading"></span></strong>
            </button>
        </td>
    </tr>

</table>


<p>Purchase: {{ReuseableCode::stock_type_amount($from,$to,1)}}</p>
<p>Purchase Return: {{ReuseableCode::stock_type_amount($from,$to,2)}}</p>
<p>Sales Return: {{ReuseableCode::stock_type_amount($from,$to,6)}}</p>
<script !src="">
    var QCounter = 0;
    function GetMoreData(RCount)
    {
        var ActualRecored = '<?php echo $RcCount?>';
        $('#BtnLoadMore').prop('disabled',true);
        $('#Loading').html('<img src="<?php echo url('/')?>/storage/app/uploads/loading.gif" style="height: 35px;" alt="">');
        $('.LoopCount').each(function() {
            QCounter++;
        });


        var to_date = '<?php echo $to?>';
        var from_date = '<?php echo $from?>';
        var accyearfrom = '<?php echo $accyeafrom?>';


        $('#AjaxMoreData').html('<td class="loader" colspan="12"></td>');
        $.ajax({
            url: '<?php echo url('/')?>/store/stock_movemnetAjaxMoreData',
            method:'GET',
            data:{RCount:RCount,to_date:to_date,accyearfrom:accyearfrom,from_date:from_date,QCounter:QCounter},
            error: function()
            {
                alert('error');
            },
            success: function(response){
                $('#AjaxMoreData').html('');
                $('#AppendData').append(response);
                ///////
                var TotalOpenQty = 0;
                var TotalOpenAmount = 0;
                var TotalInQty = 0;
                var TotalInAmount = 0;
                var TotalOutQty = 0;
                var TotalOutAmount = 0;
                var TotalOpenDn = 0;
                var TotalOpenReturn = 0;
                var TotalInStockQty = 0;
                var TotalInStockAmount = 0;
                $('.OpenQty').each(function() {
                    TotalOpenQty += parseFloat($(this).val());
                });

                $('#OpenQtyHtml').html(TotalOpenQty.toFixed(2));



                $('.OpenAmount').each(function() {
                    TotalOpenAmount += parseFloat($(this).val());
                });

                $('#OpenAmountHtml').html(TotalOpenAmount.toFixed(2));
                ///////

                $('.InQty').each(function() {
                    TotalInQty += parseFloat($(this).val());
                    //if(isNaN(TotalInQty)){TotalInQty=0;}
                });
                //TotalInQty = addCommas(TotalInQty.toFixed(2));
                $('#InQtyHtml').html(TotalInQty.toFixed(2));
                ///////

                $('.InAmount').each(function() {
                    TotalInAmount += parseFloat($(this).val());
                   //if(isNaN(TotalInAmount)){TotalInAmount=0;}
                });
                $('#InAmountHtml').html(TotalInAmount.toFixed(2));
                ///////

                $('.OutQty').each(function() {
                    TotalOutQty += parseFloat($(this).val());
                   // if(isNaN(TotalOutQty)){TotalOutQty=0;}
                });

                $('#OutQtyHtml').html(TotalOutQty.toFixed(2));
                ///////

                $('.OutAmount').each(function() {
                    TotalOutAmount += parseFloat($(this).val());
                   // if(isNaN(TotalOutAmount)){TotalOutAmount=0;}
                });
                //TotalOutAmount = ;
                $('#OutAmountHtml').html(TotalOutAmount.toFixed(2));
                ///////

                $('.OpenDn').each(function() {

                    TotalOpenDn += parseFloat($(this).val());

                });
                //TotalOpenDn = ;
                $('#OpenDnHtml').html(TotalOpenDn.toFixed(2));
                ///////

                $('.OpenReturn').each(function() {
                    TotalOpenReturn += parseFloat($(this).val());
                    //if(isNaN(TotalOpenReturn)){TotalOpenReturn=0;}
                });
                //TotalOpenReturn = addCommas(TotalOpenReturn.toFixed(2));
                $('#OpenReturnHtml').html(TotalOpenReturn.toFixed(2));
                ///////

                $('.InStockQty').each(function() {
                    TotalInStockQty += parseFloat($(this).val());
                    //if(isNaN(TotalInStockQty)){TotalInStockQty=0;}
                });
                //TotalInStockQty = ;
                $('#InStockQtyHtml').html(TotalInStockQty.toFixed(2));
                ///////

                $('.InStockAmount').each(function() {
                    TotalInStockAmount += parseFloat($(this).val());
                    //if(isNaN(TotalInStockAmount)){TotalInStockAmount=0;}
                });
                //TotalInStockAmount = ;
                var abc = parseFloat(TotalInStockAmount+TotalOpenDn-TotalOpenReturn)
                //TotalInStockAmount = addCommas(TotalInStockAmount+TotalOpenDn-TotalOpenReturn);

                $('#InStockAmountHtml').html(abc.toFixed(2));
                //alert(TotalInStockAmount+'===='+TotalOpenDn+'==='+TotalOpenReturn);
                ///////

//                QCounter=0;
//                $('.LoopCount').each(function() {
//                    QCounter++;
//                });
//                if(ActualRecored == QCounter){$('#LoadMoreArea').html('<strong>Data Compelete..!</strong>');}
                QCounter=0;
                $('#BtnLoadMore').prop('disabled',false);
                $('#Loading').html('');


            }
        });




    }

    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
</script>