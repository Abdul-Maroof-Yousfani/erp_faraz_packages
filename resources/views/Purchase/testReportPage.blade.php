<?php
use App\Helpers\CommonHelper;
$DataOne = DB::Connection('mysql2')->select('select a.acc_id,a.voucher_no,a.amount as tran_amount from transactions a
                                             where a.status = 1
                                             AND a.opening_bal = 0
                                             AND a.debit_credit = 1
                                             AND a.voucher_type = 4
                                             GROUP  BY a.voucher_no');

?>
@extends('layouts.default')
@section('content')
<div class="well_N">
<div class="dp_sdw">    
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <th class="text-center">Sr No</th>
            <th class="text-center">Voucher No</th>
            <th class="text-center">Acc Name</th>
            {{--<th class="text-center">Debit</th>--}}
            <th class="text-center">Purchase</th>
            <th class="text-center">Work Order</th>
        </thead>
        <tbody>
        <?php $Counter = 1;
                $TotPurchase = 0;
                $TotWorkOrder = 0;

        foreach($DataOne as $OneFil):
        $Prow = DB::Connection('mysql2')->selectOne('select SUM(amount) p_amount from new_purchase_voucher_data
                                              WHERE staus = 1
                                              AND pv_no = "'.$OneFil->voucher_no.'"
                                              and grn_data_id !=0
                                              group by pv_no');

                $ProwSecond = DB::Connection('mysql2')->selectOne('select SUM(b.amount) amount from new_purchase_voucher a
                                                                    INNER JOIN new_purchase_voucher_data b on b.master_id = a.id
                                                                    where a.status = 1
                                                                    and b.staus = 1
                                                                    and a.work_order_id != 0
                                                                    and a.pv_no = "'.$OneFil->voucher_no.'"
                                                                    group by b.pv_no');

        ?>
            <tr class="text-center">
                <td><?php echo $Counter++;?></td>
                <td><?php echo $OneFil->voucher_no?></td>
                <td><?php echo CommonHelper::get_account_name($OneFil->acc_id);?></td>
                <td><?php
                        if(!empty($Prow->p_amount)):
                            echo number_format($Prow->p_amount,2); $TotPurchase+=$Prow->p_amount;
                        endif;
                    ?></td>
                <td><?php
                        if(!empty($ProwSecond->amount)):
                        echo number_format($ProwSecond->amount,2); $TotWorkOrder+=$ProwSecond->amount;
                        endif;

                    ?></td>
            </tr>
        <?php endforeach;?>
        <tr class="text-center">
            <td colspan="3">TOTAL</td>
            <td><?php echo number_format($TotPurchase,2);?></td>
            <td><?php echo number_format($TotWorkOrder,2);?></td>
        </tr>
        </tbody>
    </table>
</div>
</div>
</div>

@endsection