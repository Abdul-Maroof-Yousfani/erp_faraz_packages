<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
?>
<div style="text-align: center">
    <h3>Stock Movement Report Finance</h3>
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

    <th class="text-center">IN Stock QTY</th>
    <th class="text-center">IN Stock Amount</th>
    </thead>
    <tbody id="">
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
    @endphp
    @foreach($data as $row)
        <?php
        // open process
        $open_data=ReuseableCode::get_opening_stock_for_finance($from,$to,$accyeafrom,$row->item_id,1);
        $open_qty=$open_data[0];
        $open_amount=$open_data[1];

        // in process

        $type='1,4';
        $in_data=ReuseableCode::get_stock_type_wise_for_finance($from,$to,$row->item_id,$type);
        $in_qty=$in_data[0];
        $in_amount=$in_data[1];


        // out process
        $type='2,3';
        $out_data=ReuseableCode::get_stock_type_wise_for_finance($from,$to,$row->item_id,$type);
        $out_qty=$out_data[0];
        $out_amount=$out_data[1];

        $remianig_amount=0;
        $remianig_qty=0;
        $remianig_qty=$open_qty+$in_qty-$out_qty;
        $remianig_amount=$open_amount+$in_amount-$out_amount;


        ?>
        <tr title="{{$row->item_id}}">
            <td>{{$count++}}</td>
            <td><small>{{$row->sub_ic}}</small></td>
            <td><small>{{number_format($open_qty,2)}}</small></td>
            <td><small>{{number_format($open_amount,2)}}</small></td>
            <td><small>{{number_format($in_qty,2)}}</small></td>
            <td><small>{{number_format($in_amount,2)}}</small></td>
            <td><small>{{number_format($out_qty,2)}}</small></td>
            <td><small>{{number_format($out_amount,2)}}</small></td>



            <td style="font-weight: bold"><small>{{number_format($remianig_qty,2)}}</small></td>
            <td @if ($remianig_amount<0) style="color: red" @endif style="font-weight: bold"><small>{{number_format($remianig_amount,2)}}</small></td>
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
    <tr>
        <td colspan="2">Total</td>
        <td colspan="1">{{number_format($total_open_qty,2)}}</td>
        <td colspan="1">{{number_format($total_open_amount,2)}}</td>
        <td colspan="1">{{number_format($total_in_qty,2)}}</td>
        <td colspan="1">{{number_format($total_in_amount,2)}}</td>

        <td colspan="1">{{number_format($tot_out_qty,2)}}</td>
        <td colspan="1">{{number_format($tot_out_amount,2)}}</td>


        <td colspan="1">{{number_format($cl_qty,2)}}</td>
        <td colspan="1">{{number_format($cl_amount,2)}}</td>
    </tr>
    </tbody>
</table>

<p>Purchase: {{ReuseableCode::stock_type_amount($from,$to,1)}}</p>
<p>Purchase Return: {{ReuseableCode::stock_type_amount($from,$to,2)}}</p>
<p>Sales Return: {{ReuseableCode::stock_type_amount($from,$to,6)}}</p>