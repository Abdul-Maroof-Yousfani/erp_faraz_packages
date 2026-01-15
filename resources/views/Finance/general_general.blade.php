<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
?>
@extends('layouts.default')
@section('content')

<table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
    <thead>
    <th class="text-center col-sm-1">S.No</th>
    <th class="text-center col-sm-1">Account</th>
    <th class="text-center col-sm-1">Voucher No</th>
    <th class="text-center col-sm-1">Voucher Date</th>
    <th class="text-center col-sm-1">Debit</th>
    <th class="text-center col-sm-1">Credit</th>
    <th class="text-center col-sm-1">Usernmae</th>

    </thead>
    <tbody id="data">
    <?php $counter = 1;
    $total_debit=0;
    $total_credit=0;?>

    @foreach($data as $row)
    <?php $diff=ReuseableCode::check_voucher($row->voucher_no) ?>
    <tr @if ($diff!=0) style="background-color: lightblue" @endif >
        <td>{{$counter}}</td>
        <td>{{CommonHelper::get_account_name($row->acc_id)}}
        <?php $count=DB::Connection('mysql2')->table('accounts')->where('status','!=',1)->where('id',$row->acc_id)->count();

            if ($count>0):
                echo 'Delete';
                endif;
            ?>


        </td>
        <td>{{$row->voucher_no}}</td>
        <td>{{CommonHelper::changeDateFormat($row->v_date)}}</td>
        <td><?php if ($row->debit_credit==1): echo  number_format($row->amount,2); $total_debit+=$row->amount;  endif;?></td>
        <td><?php if ($row->debit_credit==0): echo  number_format($row->amount,2); $total_credit+=$row->amount;  endif;?></td>
        <td>{{$row->username}}</td>

        <td>@if ($diff!=0) {{'wrong'}} @endif</td>
    </tr>

    @endforeach
    <tr>
        <td colspan="4">Total</td>
        <td colspan="1">{{number_format($total_debit,2)}}</td>
        <td colspan="1">{{number_format($total_credit,2)}}</td>
    </tr>

    </tbody>
</table>
    @endsection