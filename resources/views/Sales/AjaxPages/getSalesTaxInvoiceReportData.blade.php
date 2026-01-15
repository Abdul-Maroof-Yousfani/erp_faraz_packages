<?php

if ($buyer != ''):
    $data = DB::Connection('mysql2')->select('select a.id as main_id, a.gi_date,a.gi_no,b.* from sales_tax_invoice a
                INNER JOIN sales_tax_invoice_data b
                ON
                b.master_id = a.id
                WHERE  a.status = 1
                AND a.gi_date BETWEEN "' . $FromDate . '" and "' . $ToDate . '"
                and a.so_type=0
                and a.buyers_id="' . $buyer . '"
                group by b.dn_data_ids');
else:
    $data = DB::Connection('mysql2')->select('select a.id as main_id, a.gi_date,a.gi_no,b.* from sales_tax_invoice a
                INNER JOIN sales_tax_invoice_data b
                ON
                b.master_id = a.id
                WHERE  a.status = 1
                AND a.gi_date BETWEEN "' . $FromDate . '" and "' . $ToDate . '"
                and a.so_type=0

                group by b.dn_data_ids');
endif;



use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
$count = 1;


?>

<table class="table table-bordered sf-table-list">
    <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">SI No</th>
        <th class="text-center">SI Date</th>
        <th class="text-center">DN No</th>
        <th class="text-center">DN Date</th>
        <th class="text-center">Cost On DN</th>
        <th class="text-center">Return On DN</th>
        <th class="text-center">Cost On SI</th>

        <th class="text-center">Gross Amount</th>
        <th class="text-center">PL Amount</th>

        <th class="text-center">Balance</th>
        <th class="text-center" colspan="2">Action</th>
    </thead>
    <tbody id="data">
        <?php

$total_on_si = 0;
$balance = 0;
$total_on_dn = 0;
$total_gross = 0;
$total_cogs = 0;
$return_total = 0;
    ?>
        @foreach($data as $row)
                <?php
            $dn_nos = [];
            $main_id = $row->main_id;

            $data = ReuseableCode::get_dn_no($row->dn_data_ids);
            $on_si = DB::Connection('mysql2')->table('transactions')
                ->where('voucher_no', $row->gi_no)
                ->where('voucher_type', 8)
                ->where('acc_id', 97)
                ->where('status', 1)
                ->sum('amount');
            $total_on_si += $on_si;
            $balance += $on_si;


                ?>

                <tr class="text-center">
                    <td>{{$count++}}</td>
                    <td>{{$row->gi_no}}</td>
                    <td>{{CommonHelper::changeDateFormat($row->gi_date)}}</td>
                    <?php    $total_return = 0;
            $si_no = $row->gi_no;?>
                    <td>@foreach($data as $row)

                                <?php        $dataa = DB::Connection('mysql2')->table('delivery_note')->where('gd_no', $row->gd_no)->first();


                        $return = DB::Connection('mysql2')->selectOne('select a.cr_no,a.type
                                from credit_note a
                                inner join
                                credit_note_data b
                                on
                                a.id=b.master_id

                                where a.status=1
                                and b.voucher_no="' . $row->gd_no . '" ');

                        if (!empty($return->cr_no)):
                            echo 'Issue ' . $return->cr_no . ' ' . $return->type;
                            $total_return += DB::Connection('mysql2')->table('stock')->where('status', 1)->where('voucher_no', $return->cr_no)->sum('amount');
                        endif;
                            ?>
                    <?php        $dn_nos[] = $row->gd_no ?> @endforeach
                    </td>
                    <td>@foreach($data as $row) {{CommonHelper::changeDateFormat($row->gd_date)}} @endforeach</td>
                    <td>
                        <?php

            $on_dn = ReuseableCode::get_stock_amount_of_dn($dn_nos);
            $total_on_dn += $on_dn;
            echo number_format($on_dn, 2);
                    ?>

                    </td>

                    <?php    $dn_actual = $on_dn - $total_return;  ?>
                    <td> <?php    echo number_format($total_return, 2);
            $return_total += $total_return ?></td>

                    <td @if($on_si != $on_dn) style="background-color: red" @endif>{{number_format($on_si, 2)}}</td>


                    <?php
            $gross_amount = DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id', $main_id)->where('status', 1)->sum('amount');
            $total_gross += $gross_amount;
            $pl_amount = $gross_amount - $on_si;
            $total_cogs += $pl_amount;
                ?>
                    <td> <?php    echo number_format($gross_amount, 2); ?></td>
                    <td> {{number_format($pl_amount, 2)}}</td>
                    <td>{{number_format($balance, 2)}}</td>
                    <?php    $status = $on_dn - $total_return - $on_si  ?>
                    <td>@if ($status != 0) wrong {{ $status }} @endif</td>


                    <td>@if ($status != 0) <button id="{{ $si_no }}" onclick="update('{{ $si_no }}' , {{ $dn_actual }})"
                    type="button" class="btn btn-success">Update</button> @endif</td>




                </tr>

        @endforeach

        <tr style="font-weight: bold" class="text-center">
            <td colspan="5">Total</td>
            <td>{{number_format($total_on_dn, 2)}}</td>
            <td>{{ number_format($return_total, 2) }}</td>
            <td>{{number_format($total_on_si, 2)}}</td>
            <td>{{number_format($total_gross, 2)}}</td>
            <td>{{number_format($total_cogs, 2)}}</td>
        </tr>
    </tbody>
</table>

<script>
    function update(si_no, value) {
        $("#" + si_no).prop("disabled", true);
        $.ajax({
            url: '{{ url('sdc/update_cost') }}',
            type: "GET",
            data: { si_no: si_no, value: value },
            success: function (data) {
                $('#' + si_no).remove();
            }
        });
    }
</script>