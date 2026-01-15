<?php


use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

$count=1;

        $grand_total=0;
        $grand_profit=0;
$data=  DB::Connection('mysql2')->select('select a.gi_date,a.gi_no,b.*,a.buyers_id,a.id,dn_data_ids from sales_tax_invoice a
                INNER JOIN sales_tax_invoice_data b
                ON
                b.master_id = a.id
                WHERE  a.status = 1
                AND a.gi_date BETWEEN "'.$from.'" and "'.$to.'"
                and a.so_type=0
                group by b.dn_data_ids');
?>
<div class="text-right">
<input style="" type="button" value="print" onclick="print()" class="btn btn-primary">
</div>
<div id="print_sec">


    @foreach($data as $row)
        <h5>SR No: {{$count++}}</h5>
        <h5 style="cursor: pointer" onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice')">
            SI NO: {{strtoupper($row->gi_no)}}</h5>
        <h6>SI Date: {{CommonHelper::new_date_formate($row->gi_date)}}</h6>
        <h6>Customer Name: {{SalesHelper::get_customer_name($row->buyers_id)}}</h6>
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th>DN No</th>
                <th>Item</th>
                <th>QTY</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Discount Amount</th>
                <th>Net Amount</th>
                <th>Item Cost</th>
                <th>Profit</th>
            </tr>
            </thead>

            <?php $data1=DB::Connection('mysql2')->select('select b.item_id,b.desc,b.qty,b.rate,b.discount_percent,b.amount,
                    a.gd_no,b.id as dn_data_id,b.so_data_id,b.discount_amount from delivery_note a
                    inner join
                    delivery_note_data b
                    on
                    a.id=b.master_id
                    where a.status=1
                    and a.id in ("'.$row->dn_data_ids.'")

                    ');
            $total_amount=0;
           $total_profit=0;
            ?>

            @foreach($data1 as $row1)

             <?php

                $return_data=DB::Connection('mysql2')->selectOne('select sum(qty)qty from credit_note a
                inner join
                credit_note_data b
                on
                a.id=b.master_id
                where a.status=1
                and b.voucher_data_id="'.$row1->dn_data_id.'"
                and a.type=1
                ');

               $qty=$row1->qty;
               $return_qty=$return_data->qty;
               $net_qty=$qty-$return_qty;

               $amount=$net_qty*$row1->rate;
               $discount_percent=$row1->discount_percent;;
               $net_amount=$amount;

                  $discount_amount=0;
                if ($row1->discount_percent>0):
                   $discount_amount=($net_amount / 100)*$discount_percent;
                    $net_amount=$net_amount-$discount_amount;
                endif;
                $total_amount+=$net_amount;
                $grand_total+=$net_amount;


                $cost=0;

                if ($net_qty>0):
                    $cost=DB::Connection('mysql2')->table('stock')
                            ->where('status',1)
                            ->where('master_id',$row1->dn_data_id)
                            ->where('voucher_no',$row1->gd_no)
                            ->value('amount');
                    endif;
                $profit=$net_amount-$cost;
                $total_profit+=$profit;
                $grand_profit+=$profit;
                ?>
            <tr class="">
                <td>{{strtoupper($row1->gd_no)}}</td>
                <td>{{$row1->desc}}</td>
                <td>{{$net_qty}}</td>
                <td>{{$row1->rate}}</td>
                <td>{{number_format($amount,2)}}</td>
                <td>{{number_format($discount_amount,2)}}</td>
                <td>{{number_format($net_amount,2)}}</td>
                <?php
                ?>
                <td>{{number_format($cost,2)}}</td>
                <td>{{number_format($net_amount-$cost,2)}}</td>
            </tr>
            @endforeach
            <tr style="font-size: large;font-weight: bold">
                <?php  $gross=DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id',$row->id)->sum('amount');?>
                <td colspan="6">Total</td>
                <td>{{number_format($total_amount,2)}} @if($gross!=$total_amount) wrong {{number_format($gross,2)}} @endif</td>
                <td></td>
                <td>{{number_format($total_profit,2)}}</td>
            </tr>


        </table>



    @endforeach
    <table class="table">
        <tr class="thead-dark">
            <th>Grand Total</th>
            <th>Grand Profit</th>
        </tr>
        <tbody>
        <tr>
            <td>{{number_format($grand_total,2)}}</td>
            <td>{{number_format($grand_profit,2)}}</td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    function print()
    {

        var divToPrint=document.getElementById('print_sec');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();


    }
</script>



