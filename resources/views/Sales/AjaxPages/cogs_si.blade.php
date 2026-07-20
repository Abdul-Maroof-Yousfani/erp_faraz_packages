<?php

use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$count=1;

$data=  DB::Connection('mysql2')->select('select a.gi_date,a.gi_no,b.*,a.buyers_id,a.id from sales_tax_invoice a
                INNER JOIN sales_tax_invoice_data b
                ON
                b.master_id = a.id
                WHERE  a.status = 1
                AND a.gi_date BETWEEN "'.$from.'" and "'.$to.'"
                and a.so_type=0
                
                group by b.dn_data_ids');
?>

<style>
    .tb-table tbody tr.negative-profit td {
        background: #FDEAEA !important;
        color: #e04b4b !important;
        font-weight: 700 !important;
    }
</style>

<div class="tb-report-wrap">
    <div class="tb-table-scroll">
    <table class="tb-table">
        <thead>
        <tr class="tb-col-row">
            <th class="text-center">S.No</th>
            <th class="text-center">SI No</th>
            <th class="text-center">SI Date</th>
            <th class="text-left">DN No</th>
            <th class="text-left">Customer</th>
            <th class="text-right">Gross Amount</th>
            <th class="text-right">Cost</th>
            <th class="text-right">Profit</th>
        </tr>
        </thead>
        <tbody id="data">
        <?php
        $total_on_si=0;
        $balance=0;
        $total_on_dn=0;
        $total_gross=0;
        $total_profit=0;
        ?>
        @foreach($data as $row)
            <?php
            $dn_nos=[];
            $data=ReuseableCode::get_dn_no($row->dn_data_ids);
            $on_si=DB::Connection('mysql2')->table('transactions')
                    ->where('voucher_no',$row->gi_no)
                    ->where('voucher_type',8)
                    ->where('acc_id',97)
                    ->where('status',1)
                    ->sum('amount');
            $total_on_si+=$on_si;
            $balance+=$on_si;

            $gross=DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id',$row->id)->sum('amount');
            $total_gross+=$gross;

            $on_dn=ReuseableCode::get_stock_amount_of_dn($dn_nos);
            $profit=$gross-$on_dn;
            ?>

            <tr class="text-center @if($profit<=0) negative-profit @endif">
                <td>{{$count++}}</td>
                <td>{{strtoupper($row->gi_no)}}</td>
                <td>{{$row->gi_date}}</td>

                <td class="text-left">
                    @foreach($data as $row1)
                        <?php
                        $dataa=DB::Connection('mysql2')->table('delivery_note')->where('gd_no',$row1->gd_no)->first();
                        echo strtoupper($row1->gd_no);
                        echo '<br>';
                        $dn_nos[]=$row1->gd_no;
                        ?>
                    @endforeach
                </td>

                <td class="text-left link_hide">{{SalesHelper::get_customer_name($row->buyers_id)}}</td>

                <td class="text-right"><?php echo number_format($gross,2); ?></td>

                <td class="text-right">
                    <?php
                    $on_dn=ReuseableCode::get_stock_amount_of_dn($dn_nos);
                    $total_on_dn+=$on_dn;
                    echo number_format($on_dn,2);
                    ?>
                </td>

                <td class="text-right">
                    <?php
                    echo number_format($profit,2);
                    $total_profit+=$profit;
                    ?>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5">Total</td>
            <td class="text-right">{{number_format($total_gross,2)}}</td>
            <td class="text-right">{{number_format($total_on_dn,2)}}</td>
            <td class="text-right">{{number_format($total_profit,2)}}</td>
        </tr>
        </tfoot>
    </table>
    </div>
</div>