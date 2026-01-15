<?php use App\Helpers\CommonHelper; ?>


<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
<table class="table table-bordered">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Account Name</th>
    <th class="text-center">Description</th>
    <th class="text-center">Debit</th>
    <th class="text-center">Credit</th>

    </thead>
    <tbody>
    <?php
    $count = 1;
    $total_debit=0;
    $total_credit=0;
    ?>
    @foreach($issue_data as $row)


        <tr class="text-center">
            <td>{{$count++}}</td>
            <td>{{CommonHelper::get_account_name($row->acc_id)}}</td>
            <td>{{$row->particulars}}</td>
            <td>@if ($row->debit_credit==1){{number_format($row->amount,2)}}<?php  $total_debit+=$row->amount ?>@endif</td>
            <td>@if ($row->debit_credit==0){{number_format($row->amount,2)}}<?php  $total_credit+=$row->amount ?>@endif</td>
        </tr>
        <?php

        ?>
    @endforeach
    <tr style="font-weight: bold; " class="text-center">
        <td colspan="3">Total</td>
        <td>{{number_format($total_debit,2)}}</td>
        <td>{{number_format($total_credit,2)}}</td>
    </tr>
    </tbody>
</table>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <table class="table table-bordered">
            <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Account Name</th>
            <th class="text-center">Description</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Credit</th>

            </thead>
            <tbody>
            <?php
            $count = 1;
            $total_debit=0;
            $total_credit=0;
            ?>
            @foreach($return_data as $row)


                <tr class="text-center">
                    <td>{{$count++}}</td>
                    <td>{{CommonHelper::get_account_name($row->acc_id)}}</td>
                    <td>{{$row->particulars}}</td>
                    <td>@if ($row->debit_credit==1){{number_format($row->amount,2)}}<?php  $total_debit+=$row->amount ?>@endif</td>
                    <td>@if ($row->debit_credit==0){{number_format($row->amount,2)}}<?php  $total_credit+=$row->amount ?>@endif</td>
                </tr>
                <?php

                ?>
            @endforeach
            <tr style="font-weight: bold; " class="text-center">
                <td colspan="3">Total</td>
                <td>{{number_format($total_debit,2)}}</td>
                <td>{{number_format($total_credit,2)}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


