<table class="table table-bordered table-responsive">

    <thead>
    <th class="text-center">S.No</th>

    <th class="text-center">Voucher</th>
    <th class="text-center">Voucher Type</th>
    <th class="text-center">Voucher Date</th>
    <th class="text-center">QTY</th>
    <th class="text-center">Rate.</th>
    <th class="text-center">Amount</th>
    <th class="text-center">Balance QTY</th>
    <th class="text-center">Balance Amount</th>
    <th class="text-center hide">Cost</th>
    <th class="text-center hide">Update</th>
    </thead>
    <tbody id="filterDemandVoucherList">

    <?php

    $counter=1;

    $data=   DB::Connection('mysql2')->table('stock')->where('status',1)->where('sub_item_id',$_GET['sub_item_id'])
            ->where('warehouse_id',$_GET['warehouse_id'])
            ->orderBy('voucher_date','ASC')->get();
    $total_qty=0;
    $total_amount=0;

    ?>
    @foreach($data as $row)


        <?php
        $dn_data_id='';
        $voucher_type='';
        $dn_no='';
        if ($row->voucher_type==1 && $row->opening==1):
            $voucher_type='Opening';
            $total_qty+=$row->qty;
            $total_amount+=$row->amount;
        elseif($row->voucher_type==1 && $row->opening==0):
            $voucher_type='Goods Receipt Note';
            $total_qty+=$row->qty;
            $total_amount+=$row->amount;
        elseif($row->voucher_type==5 && $row->pos_status==0):
            $voucher_type='Delivery Note';
            $total_qty-=$row->qty;
            $total_amount-=$row->amount;

        elseif($row->voucher_type==5 && $row->pos_status==1):
            $voucher_type='POS';
            $total_qty-=$row->qty;
            $total_amount-=$row->amount;

        elseif($row->voucher_type==5 && $row->pos_status==2):
            $voucher_type='Production Issuence';
            $total_qty-=$row->qty;
            $total_amount-=$row->amount;


        elseif($row->voucher_type==2):
            $voucher_type='Purchase Return';
            $total_qty-=$row->qty;
            $total_amount-=$row->amount;

        elseif($row->voucher_type==6):
            $voucher_type='Credit Note';
            $total_qty+=$row->qty;
            $total_amount+=$row->amount;
            //   $so_id= DB::Connection('mysql2')->table('credit_note')->where('id',$row->main_id)->where('status',1)->value('so_id');
            //  $so_no= DB::Connection('mysql2')->table('sales_order')->where('id',$so_id)->where('status',1)->value('so_no');
            //   $dn_no= DB::Connection('mysql2')->table('delivery_note')->where('so_no',$so_no)->where('status',1)->value('gd_no');


            $dn_data_id= DB::Connection('mysql2')->table('credit_note_data')->where('id',$row->master_id)->where('status',1)->value('so_data_id');
            $dn_no= DB::Connection('mysql2')->table('delivery_note_data')->where('so_data_id',$dn_data_id)->where('status',1)->value('gd_no');
        endif;
        ?>

        <tr>
            <td class="text-center">{{$counter++}}</td>
            <td class="text-center"> {{strtoupper($row->voucher_no).' '.strtoupper($dn_no).''}} </td>
            <td style="color: red" class="text-center"> {{$voucher_type}}  </td>
            <td class="text-center">{{date('d-m-Y',strtotime($row->voucher_date)) }}</td>
            <td class="text-center">{{$row->qty}}</td>
            <td class="text-center">{{$row->rate}}</td>
            <td class="text-center">{{number_format($row->amount,2)}}</td>
            <td style="font-weight: bold" class="text-center">{{number_format($total_qty,2)}}</td>
            <td @if ($total_qty>0) title="{{number_format($total_amount/$total_qty,2)}}" @endif style="font-weight: bold" class="text-center">{{number_format($total_amount,2)}}</td>
            <td class="hide"><input class="form-control" type="number" id="val{{$row->id}}" value="{{$row->amount}}"></td>
            <td class="hide"><input  type="button" onclick="update_cost('{{$row->id}}')" value="Update"/> </td>
        </tr>
    @endforeach
    <tr style="font-size: large;font-weight: bold">
        <td colspan="4">Total</td>
        <td class="text-center">{{number_format($total_qty,2)}}</td>
        <td ></td>
        <td class="text-center">{{number_format($total_amount,2)}}</td>
    </tr>

    </tbody>
</table>