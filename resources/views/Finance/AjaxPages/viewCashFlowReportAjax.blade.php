<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

$count = 1;
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="view_flow">
            <div class="view_header">
                <h2>Zahabiya Chemicals Industries (Pvt.) Ltd.</h2>
                <h3>CASH FLOW</h3>
                <h4>From July {{CommonHelper::changeDateFormat($from)}} to {{CommonHelper::changeDateFormat($to)}}</h4>
            </div>
            <div class="view_header2">
                <h2>CASH FLOW ACTIVITIES</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered sf-table-th sf-table-list profit_Loss_Statement viewcashflow_report"style="pading:10px 8px !important;" id="exportIncomeStatement1" style="background:#FFF !important;">

                    <thead>
                        <tr>
                            <th>Particulars</th>
                            <th>Amount</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $get_acc_id = array();
                            if ($acc_id) {
                                $get_acc_id = [intval($acc_id)];
                            }
                            else{
                                $get_acc_id = [90,91,92,86,87,88,388,389];
                            }
                            $opening_balance = CommonHelper::get_opening_bal_array($from,$to,$get_acc_id);
                            // dump($get_acc_id , $acc_id , $opening_balance);
                        @endphp
                        <tr>
                            <td scope="row">Opening Balance</td>
                            <td></td>
                            <td>{{$opening_balance}}</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th>CASH IN-FLOWS (Receipts)</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    @php
                        $total_debit_amount  = 0;
                        $total_credit_amount  = 0;


                        $debit_cash_flow = DB::connection('mysql2')->table('cash_flow_sub_heads as csh')
                        ->join('cash_flow_heads as ch' , 'ch.id' , 'csh.cash_flow_head_id')
                        ->select('csh.*')
                        ->where('csh.status' , 1)
                        ->where('ch.status' , 1)
                        ->where('ch.debit_credit' , 1)
                        ->get();
                    @endphp
                    <tbody>
                        <tr>
                            <td scope="row"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @foreach ($debit_cash_flow as $value)

                            @php
                                $transactions =  DB::connection('mysql2')
                                ->table('transactions')
                                ->whereBetween('v_date',[$from ,$to]);
                                if($acc_id)
                                {
                                    $transactions = $transactions->where('acc_id',$acc_id);
                                }
                                $transactions = $transactions->where('debit_credit' , 1)
                                ->where('status' , 1)
                                ->where('voucher_type' , '>'  , 0)
                                ->where('cash_flow_head_id' , $value->id)
                                ->select(DB::raw('SUM(amount) as total_amount'))
                                ->value('total_amount');

                                $total_debit_amount += $transactions;
                            @endphp
                            <tr>
                                <td scope="row">{{$value->name}}</td>
                                <td>{{number_format($transactions??0 , 2)}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        @php 
                            $debit_with_opening_amount  = $total_debit_amount + $opening_balance;
                        @endphp
                        <tr>
                            <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">TOTAL IN-FLOWS</td>
                            <td></td>
                            <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{number_format($total_debit_amount ,2)}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Cash And Cash Equivalents Available For Use</td>
                            <td></td>
                            <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{number_format($debit_with_opening_amount , 2)}}</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th>CASH OUT-FLOWS (PAYMENTS)</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    @php
                        $credit_cash_flow = DB::connection('mysql2')->table('cash_flow_heads')
                        ->where('status' , 1)
                        ->where('debit_credit' , 0)
                        ->get();
                    @endphp
                    <tbody>
                        @foreach ($credit_cash_flow as $value)
                            <tr>
                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">{{$value->name}}:</td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $sub_credit_cash_flow =   DB::connection('mysql2')->table('cash_flow_sub_heads')
                                ->where('cash_flow_head_id' ,$value->id )
                                ->where('status' , 1)->get();
                            @endphp
                            @foreach ($sub_credit_cash_flow as $value1)
                                @php
                                $transactions =  DB::connection('mysql2')
                                ->table('transactions')
                                ->whereBetween('v_date',[$from ,$to]);
                                if($acc_id)
                                {
                                    $transactions = $transactions->where('acc_id',$acc_id);
                                }
                                $transactions = $transactions->where('debit_credit' , 0)
                                ->where('status' , 1)
                                ->where('voucher_type' , '>'  , 0)
                                ->where('cash_flow_head_id' , $value1->id)
                                ->select(DB::raw('SUM(amount) as total_amount'))
                                ->value('total_amount');
                                //     $transactions =  DB::connection('mysql2')
                                // ->selectOne('select SUM(amount) amount from transactions where status=1  AND cash_flow_head_id = '.$value1->id.'
                                //     AND voucher_type > 0 AND debit_credit = 1')->amount;
                                $total_credit_amount += $transactions;
                                @endphp
                                <tr>
                                    <td scope="row">{{$value1->name}}</td>
                                    <td>{{number_format($transactions??0 , 2)}}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr>
                            <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">TOTAL OUT-FLOWS</td>
                            <td></td>
                            <td>{{number_format( $debit_with_opening_amount ? ($total_credit_amount * 100) / $debit_with_opening_amount : 0 , 2)}} %</td>
                            <td>({{ number_format($total_credit_amount , 2)}})</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th>Ending Balance [Surplus/(Deficit)]</th>
                            <th></th>
                            <th></th>
                            <th>{{number_format($debit_with_opening_amount - $total_credit_amount , 2)}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th style="background-color:yellow;">Breakup of Ending Balance</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sub_total_bank_amount = 0;
                        @endphp
                        @foreach ($get_acc_id as $value3)
                            @php
                                $debit_bank_amount =  DB::connection('mysql2')
                                ->table('transactions')
                                ->whereBetween('v_date',[$from ,$to])
                                ->where('debit_credit' , 1)
                                ->where('acc_id',$value3)
                                ->where('status' , 1)
                                ->where('voucher_type' , '>'  , 0)
                                ->where('cash_flow_head_id' , '!=' , null)
                                ->select(DB::raw('SUM(amount) as total_amount'))
                                ->value('total_amount');

                                $credit_bank_amount =  DB::connection('mysql2')
                                ->table('transactions')
                                ->whereBetween('v_date',[$from ,$to])
                                ->where('debit_credit' , 0)
                                ->where('acc_id',$value3)
                                ->where('status' , 1)
                                ->where('voucher_type' , '>'  , 0)
                                ->where('cash_flow_head_id' , '!=' , null)
                                ->select(DB::raw('SUM(amount) as total_amount'))
                                ->value('total_amount');

                                $total_bank_amount = $debit_bank_amount - $credit_bank_amount;
                                $sub_total_bank_amount += $total_bank_amount;
                            @endphp
                            <tr>
                                <td scope="row">{{CommonHelper::get_account_name($value3)}}</td>
                                <td></td>
                                <td></td>
                                <td>{{number_format($total_bank_amount , 2)}}</td>
                            </tr>
                        @endforeach
                        {{-- <tr>
                            <td scope="row">MEEZAN BANK- SA(0759)</td>
                            <td></td>
                            <td></td>
                            <td>xxxxxx</td>
                        </tr>
                        <tr>
                            <td scope="row">BANK AL HABIB- CA(30501)</td>
                            <td></td>
                            <td></td>
                            <td>xxxxxx</td>
                        </tr>
                        <tr>
                            <td scope="row">HABIB METRO- CA(7281)</td>
                            <td></td>
                            <td></td>
                            <td>xxxxxx</td>
                        </tr>
                        <tr>
                            <td scope="row">UNITED BANK LTD (CA 6765)</td>
                            <td></td>
                            <td></td>
                            <td>xxxxxx</td>
                        </tr>
                        <tr>
                            <td scope="row">HABIB METRO SA(7281)</td>
                            <td></td>
                            <td></td>
                            <td>xxxxxx</td>
                        </tr>
                        <tr>
                            <td scope="row">Cash In Hand</td>
                            <td></td>
                            <td></td>
                            <td>xxxxxx</td>
                        </tr>
                        <tr>
                            <td scope="row"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr> --}}
                    </tbody>
                    <thead>
                        <tr>
                            <th style="text-align:right;">Total</th>
                            <th></th>
                            <th></th>
                            <th>{{number_format($sub_total_bank_amount ,  2)}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

