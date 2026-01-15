

@extends('layouts.default')

@section('content')
<?php

$count=1;
$Data = DB::Connection('mysql2')->select('
        SELECT
        b.dn_data_ids, b.gi_no,a.gi_date
        FROM sales_tax_invoice_data b
        INNER JOIN  sales_tax_invoice a ON b.master_id = a.id
        WHERE   a.status = 1


             AND b.item_id != 0
        group by b.dn_data_ids');?>
<table class="table table-bordered sf-table-list" id="expToExcel">
    <thead>

    <th class="text-center">SR.NO </th>
    <th class="text-center">SI NO </th>
    <th class="text-center">DN NO</th>
    <th class="text-center">Amount</th>

    </thead>
    <tbody id="data">
    <?php
    $Counter=1;
    $grand_total=0;
    foreach($Data as $fil):?>
    {{$count++}}
    <?php
    $Stock = DB::Connection('mysql2')->select('select * from stock where status = 1 and voucher_type = 5 and pos_status=0 and main_id in('.$fil->dn_data_ids.')');
    $total_amount=0;
    foreach($Stock as $ff):
    $InsertData['main_id'] = $ff->main_id;
    $InsertData['master_id'] = 0;
    $InsertData['voucher_no'] = $fil->gi_no;
    $InsertData['item_id'] = $ff->sub_item_id;
    $InsertData['qty'] = $ff->qty;
    $InsertData['amount'] = $ff->amount;
    $InsertData['opening'] = 0;
    $InsertData['status'] = 1;
    $InsertData['username'] = 'software';
    $InsertData['voucher_type'] = 3;
    $InsertData['voucher_date'] = $fil->gi_date;
    $InsertData['ref_no'] = $ff->voucher_no;
    $InsertData['ref_date'] = $ff->voucher_date;
//  DB::Connection('mysql2')->table('transaction_supply_chain')->insert($InsertData);
    ?>
    <tr>
        <td><?php echo $Counter++;?></td>
        <td><?php echo $fil->dn_data_ids?></td>
        <td><?php echo $fil->gi_no;

            $on_si=DB::Connection('mysql2')->table('transactions')
                    ->where('voucher_no',$fil->gi_no)
                    ->where('voucher_type',8)
                    ->where('acc_id',97)
                    ->where('status',1)
                    ->sum('amount');


            $supply_chan_amount=DB::Connection('mysql2')->table('transaction_supply_chain')
                ->where('ref_no',$ff->voucher_no)
                ->where('status',1)
                ->sum('amount');
            ?></td>
        <td><?php echo $ff->voucher_no;?></td>
        <td  class="text-right"><?php echo number_format($ff->amount,2);
            $total_amount+=$ff->amount;
            $grand_total+=$ff->amount;
            ?></td>

    </tr>
    <?php endforeach;?>
            <tr style="font-size: large;font-weight: bold">
                <td colspan="3">Total {{number_format($on_si,2)}}</td>
                <td @if($on_si!=$total_amount) {{number_format($on_si,2)}} style="background-color: brown" @endif class="text-right">{{number_format($total_amount,2)}}</td>
                <td>@if($supply_chan_amount!=$total_amount) wrong {{ $supply_chan_amount - $total_amount }} @endif</td>
            </tr>
    <?php endforeach;?>
    <tr style="font-size: large;font-weight: bold">
        <td colspan="3">Grand Total</td>
        <td class="text-right">{{number_format($grand_total,2)}}</td>
    </tr>
    </tbody></table>

@endsection
