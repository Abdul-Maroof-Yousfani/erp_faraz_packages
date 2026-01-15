<?php
use App\Helpers\Payment_through_jvs;

?>
<table class="table table-bordered sf-table-list" id="journalVoucherList">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Refrence</th>
    <th class="text-center">V Type</th>
    <th class="text-center">V No</th>
    <th class="text-center">V Date</th>
    <th class="text-center">Debit</th>
    <th class="text-center">Credit</th>
    <th class="text-center">Balance</th>

    </thead>
    <tbody id="">
    <?php $count=1;
          $balance=0;
    $total_debit=0;
    $total_credit=0;
    ?>
        @foreach(Payment_through_jvs::data_main_id_wise($main_id,$supplier_id) as $row)
            <?php
            $debit=0;
            $credit=0;
            ?>
            <tr>
                <td>{{$count++}}</td>
                <td>{{$row->main_id}}</td>
                <td>{{Payment_through_jvs::voucher_type($row->voucher_type)}}</td>
                <td>{{$row->voucher_no}}</td>
                <td>{{$row->voucher_date}}</td>
                <td class="text-right">@if ($row->debit_credit==1) <?php $debit=$row->amount;  $total_debit+=$row->amount;?>  {{number_format($row->amount,2)}} @else <?php  ?>  @endif</td>
                <td class="text-right">@if ($row->debit_credit==0) <?php $credit=$row->amount; $total_credit+=$row->amount;?> {{number_format($row->amount,2)}} @else <?php   ?> @endif</td>
                <?php $balance= $debit+$balance-$credit  ?>
                <td style="font-weight: bolder" class="text-right">{{number_format($balance,2)}}</td>
            </tr>



            @endforeach
    <tr style="background-color: darkgray">
        <td colspan="5">Total</td>
        <td class="text-right">{{number_format($total_debit,2)}}</td>
        <td class="text-right">{{number_format($total_credit,2)}}</td>
        <td></td>
    </tr>
    </tbody>
</table>
