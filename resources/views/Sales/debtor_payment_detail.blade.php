<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Transactions;
$export=ReuseableCode::check_rights(244);
?>
@extends('layouts.default')
@section('content')
<div class="well_N">
<div class="dp_sdw">    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php if($export == true):?>
        <a id="dlink" style="display:none;"></a>
        <button type="button" class="btn btn-warning" onclick="ExportToExcelAll('xlsx','','','','All')">All Export <b>(xlsx)</b></button>
        <?php endif;?>
    </div>
<span id="AllExport">

    @php
    $total_invocieEnd=0;
    $total_paidEnd=0;
    $total_remainingEnd=0;
    $grand_total_invoice=0;
    $grand_total_balance=0;
    $main_count=0;
    $table_count=1;
    @endphp
    @foreach($data as $row)
        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList{{$table_count}}">




            <thead>
            <th colspan="15" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
            </thead>
            <thead>
            <tr>
                <td colspan="5"><h4> {{'Vendor :'.$row->name}}</h4>
                    <?php if($export == true):?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx','','','<?php echo $table_count?>','<?php echo $row->name?>')">Export <b>(xlsx)</b></button>
                    <?php endif;?>
                </td>

            </tr>
            <tr>
                <th class="text-center">S.No</th>
                <th class="text-center">PI</th>
                <th class="text-center">PI Date</th>
                <th class="text-center">Order No</th>
                <th class="text-center">Invoice Amount</th>
                <th class="text-center">Paid Amount</th>
                <th class="text-center">Received Amount</th>
                <th class="text-center">Payment Detail</th>
            </tr>
            </thead>



            <tbody id="filterContraVoucherList">
            @php
            $counter=1;
            $total_invocie=0;
            $total_paid=0;
            $total_remaining=0;
            @endphp

            <?php

            $vendor_data=DB::Connection('mysql2')->select('select a.id,a.gi_no,a.gi_date,a.order_no,(sum(b.amount)+a.sales_tax)total
                from sales_tax_invoice a
                inner join
                sales_tax_invoice_data b
                on
                a.id=b.master_id
                where a.status=1
                and a.buyers_id="'.$row->id.'"
                group by a.id');
            ?>

            @foreach($vendor_data as $row1)
                <tr class="text-center">
                    <td>{{$counter++}}</td>
                    <td>{{$row1->gi_no}}</td>
                    <td>{{CommonHelper::changeDateFormat($row1->gi_date)}}</td>
                    <td>{{$row1->order_no}}</td>
                    <?php
                        $Addional = DB::Connection('mysql2')->selectOne('select sum(amount) amount from addional_expense_sales_tax_invoice where main_id = '.$row1->id.' and status = 1');

                    $total=$row1->total+$Addional->amount;
                    $paid=CommonHelper::PaymentDebtorAmountCheck($row1->id);
                    $remaining=   $total-$paid;

                    $total_invocie+=$total;
                    $total_paid+=$paid;
                    $total_remaining+=$remaining;
                    ?>

                    <td>{{number_format($total,2)}}</td>
                    <?php  ?>
                    <td>{{number_format($paid,2)}}</td>
                    <td>{{number_format($remaining,2)}}</td>
                    <td class="text-center">
                        <?php
                        if($paid > 0):
                        ?>
                        <button
                                onclick="showDetailModelOneParamerter('sdc/viewPaymentDetail','<?php echo $row1->id ?>','View Payment Voucher Detail')"
                                type="button" class="btn btn-success btn-xs">Payment Detail</button>
                        <?php else:?>
                        <p class="text-danger">Unpaid</p>
                        <?php endif;?>
                    </td>

                </tr>
            @endforeach
            <tr class="text-center" style="font-size: large;font-weight: bold">
                <td colspan="4">Total</td>
                <td>{{number_format($total_invocie,2) }} <?php $total_invocieEnd+=$total_invocie;?></td>
                <td>{{number_format($total_paid,2) }}<?php $total_paidEnd+=$total_paid;?></td>
                <td>{{number_format($total_remaining,2) }} <?php $total_remainingEnd+=$total_remaining;?></td>
            </tr>

            </tbody>




            @php $table_count++; @endphp

        </table>
    @endforeach
    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList{{$table_count}}">
        <thead>
        <tr class="text-center">
            <th colspan="4" class="text-center">Grand Total</th>
            <th class="text-center">Total Invoice Amount</th>
            <th class="text-center">Total Paid Amount</th>
            <th class="text-center">Total Received Amount</th>
        </tr>
        </thead>
        <tr class="text-center" style="font-size: large;font-weight: bold">
            <td colspan="4"></td>
            <td>{{number_format($total_invocieEnd,2)}}</td>
            <td>{{number_format($total_paidEnd,2)}}</td>
            <td>{{number_format($total_remainingEnd,2)}}</td>
        </tr>
    </table>
</span>
</div>
</div>
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl,Table,DebtorName) {
            var elt = document.getElementById('EmpExitInterviewList'+Table);
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Debtor Payment Detail '+DebtorName+' <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
        function ExportToExcelAll(type, fn, dl) {
            var elt = document.getElementById('AllExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Debtor Payment Detail All <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
@endsection
