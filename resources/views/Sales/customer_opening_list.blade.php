<?php

use App\Helpers\FinanceHelper;
use App\Models\Transactions;
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
$export=ReuseableCode::check_rights(261);
?>
@extends('layouts.default')
@section('content')
<div class="well_N">
    @php
    $grand_total_invoice=0;
    $grand_total_balance=0;
    $table_count=1;
    @endphp
    @foreach($data as $row)
        @if ($row->bal>0)
           <td><h4> {{'Buyer :'.$row->name}}</h4>
               <?php if($export == true):?>
               <button type="button" class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx','','','<?php echo $table_count?>','<?php echo $row->name?>')">Export <b>(xlsx)</b></button>
               <?php endif;?>
           </td>

            <table class="table table-bordered sf-table-list" id="bankReceiptVoucherList{{$table_count}}">
                <thead>
                <th class="text-center">S.No</th>
                <th class="text-center">SI</th>
                <th class="text-center">SO</th>
                <th class="text-center">Invoice Amount</th>
                <th class="text-center">Balance Amount</th>

                </thead>



                <tbody id="filterContraVoucherList">
                @php
                $counter=1;
                $total_balance_amount=0;
                $total_invoice_amount=0;
                @endphp

                @foreach(FinanceHelper::get_buyer_opening_by_buyer_id($row->id) as $row1)
                    <tr class="text-center">
                        <td>{{$counter++}}</td>
                        <td>{{$row1->si_no}}</td>
                        <td>{{$row1->so_no}}</td>
                        <td>{{number_format($row1->invoice_amount,2)}}</td>
                        <td>{{number_format($row1->balance_amount,2)}}</td>
                        @php $total_balance_amount+=$row1->balance_amount;
                        $total_invoice_amount+=$row1->invoice_amount;
                        $grand_total_invoice+=$row1->invoice_amount;
                        $grand_total_balance+=$row1->balance_amount;
                        @endphp

                    </tr>
                    <?php //ReuseableCode::insert_si($row1->si_no); ?>
                @endforeach

                <?php $open=CommonHelper::get_opening_bal(0,0,$row->acc_id); ?>
                <tr @if($open!=$total_balance_amount) style="background-color: lightcoral" @endif class="text-center" style="font-size: large;font-weight: bold">
                    <td colspan="3">Total</td>
                    <td>{{number_format($total_invoice_amount,2)}}</td>
                    <td>{{number_format($total_balance_amount,2)}}</td>
                </tr>

                </tbody>

            </table>
           @php $table_count++; @endphp
            <?php
            $amount=$total_invoice_amount-$total_balance_amount;

            if ($amount>=0):
                $debit_credit=0;
            else:
                $debit_credit=1;
            endif;

            $transaction=new Transactions();
            $transaction=$transaction->SetConnection('mysql2');
            //   $count=$transaction->where('acc_id',$row->acc_id)->where('opening_bal',1)->count();



            $transaction->acc_id=$row->acc_id;
            $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($row->acc_id);
            $transaction->particulars='Opening';
            $transaction->opening_bal=1;
            $transaction->debit_credit=$debit_credit;
            $transaction->amount=$row->bal;
            $transaction->username='Amir Murshad@';;
            $transaction->status=1;
            //  $transaction->save();
            ?>  @endif
    @endforeach


    <div style="text-align: right;font-size: large;font-weight: bolder">
        <p>Grand Total Of Invoices : {{number_format($grand_total_invoice,2)}}</p>
        <p>Grand Total Of Balance : {{number_format($grand_total_balance,2)}}</p>
    </div>
</div>

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl,Table,Bname) {
            var elt = document.getElementById('bankReceiptVoucherList'+Table);
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || (Bname+' Opening <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
@endsection
