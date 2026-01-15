<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Transactions;
?>
@extends('layouts.default')
@section('content')
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">


    </div>

    @php
    $grand_total_invoice=0;
    $grand_total_balance=0;
    $main_count=0;
    $table_count=1;
    @endphp
    @foreach($data as $row)
    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList{{$table_count}}">


        @if ($row->bal>0)


                        <thead>
                            <tr>
                                <td colspan="5"><h4> {{'Vendor :'.$row->name}}</h4>
                                <button class="btn btn-sm btn-warning" onclick="exportView('EmpExitInterviewList{{$table_count}}','','1')" style="">
                                    <span class="glyphicon glyphicon-print"> Export to CSV</span>
                                </button>
                                </td>

                            </tr>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">PI</th>
                                <th class="text-center">PO</th>
                                <th class="text-center">Invoice Amount</th>
                                <th class="text-center">Balance Amount</th>
                                <th class="text-center">Exists</th>
                            </tr>
                        </thead>



                        <tbody id="filterContraVoucherList">
                        @php
                        $counter=1;
                        $total_balance_amount=0;
                        $total_invoice_amount=0;
                        @endphp
                        @foreach(ReuseableCode::get_vendor_opening_by_vendor_id($row->id) as $row1)

                            <?php $count=DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('slip_no',$row1->pi_no)->count(); ?>
                         <tr class="text-center">
                             <td>{{$counter++.' ('.$main_count++.')'}}</td>
                             <td>{{$row1->pi_no}}</td>
                             <td>{{$row1->po_no}}</td>
                             <td>{{number_format($row1->invoice_amount,2)}}</td>
                             <td>{{number_format($row1->balance_amount,2)}}</td>
                             <td>@if($count>0)Exists @else Not Exists @endif</td>
                             @php $total_balance_amount+=$row1->balance_amount;
                                  $total_invoice_amount+=$row1->invoice_amount;
                                  $grand_total_invoice+=$row1->invoice_amount;
                                  $grand_total_balance+=$row1->balance_amount;

                             @endphp

                         </tr>
                            @endforeach
                        <tr class="text-center" style="font-size: large;font-weight: bold">
                            <td colspan="3">Total</td>
                            <td>{{number_format($total_invoice_amount,2)}}</td>
                            <td>{{number_format($total_balance_amount,2)}}</td>
                        </tr>

                        </tbody>
                


        @endif
        @php $table_count++; @endphp

    </table>
    @endforeach


  <div style="text-align: right;font-size: large;font-weight: bolder">
      <p>Grand Total Of Invoices : {{number_format($grand_total_invoice,2)}}</p>
      <p>Grand Total Of Balance : {{number_format($grand_total_balance,2)}}</p>
  </div>

@endsection