<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;


//$view=ReuseableCode::check_rights(174);


?>

@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body" id="PrintPanel">
                    <div class="row" id="ShowHide">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <h5 style="text-align: center" id="h3"></h5>
                                <table class="table table-bordered sf-table-list" id="TableExportToCsv">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Voucher No</th>
                                    <th class="text-center">Voucher Date</th>
                                    <th class="text-center">Amount</th>

                                    </thead>

                                    <?php
                                $from='2021-06-01';
                                $to='2021-12-31';
                                 $data=   DB::Connection('mysql2')->table('transactions')
                                            ->where('status',1)
                                            ->where('debit_credit',0)
                                            ->where('voucher_type',8)
                                            ->where('acc_id',97)
                                            ->whereBetween('v_date', [$from, $to])
                                            ->get();

                                    $count=1;
                                    $total=0;
                                    ?>
                                    <tbody id="data">

                                    @foreach($data as $row)

                                        <tr class="text-center">
                                            <td>{{$count++}}</td>
                                            <td>{{$row->voucher_no}}</td>
                                            <td>{{$row->v_date}}</td>
                                            <td>{{number_format($row->amount,2)}}</td>
                                        </tr>

                                        <?php $total+=$row->amount; ?>
                                    @endforeach
                                <tr class="text-center" style="font-weight: bold">
                                    <td colspan="3">Total</td>
                                    <td>{{number_format($total,2)}}</td>
                                </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
