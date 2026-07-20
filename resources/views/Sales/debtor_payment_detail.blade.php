<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Transactions;
$export=ReuseableCode::check_rights(244);
?>
@extends('layouts.default')
@section('content')

<style>
    /* ===== Debtor Payment Detail - Redesigned Layout ===== */
    .dpd-wrap{
        padding:20px;
    }
    .dpd-toolbar{
        display:flex;
        justify-content:flex-end;
        margin-bottom:18px;
    }
    .dpd-toolbar .btn-warning{
        background:linear-gradient(135deg,#ff9a3d,#ff7a1a) !important;
        border:none !important;
        color:#fff !important;
        font-weight:600;
        padding:10px 22px;
        border-radius:8px !important;
        box-shadow:0 4px 10px rgba(255,122,26,0.25);
    }
    .dpd-toolbar .btn-warning:hover{
        filter:brightness(1.05);
    }

    .dpd-card{
        background:#fff;
        border-radius:14px !important;
        box-shadow:0 2px 14px rgba(20,30,60,0.08) !important;
        margin-bottom:26px;
        overflow:hidden;
        border:1px solid #edf0f5 !important;
    }

    .dpd-company-header{
        background:#eef1f8;
        padding:16px 20px;
        text-align:center;
    }
    .dpd-company-header h3{
        margin:0;
        font-size:18px;
        font-weight:700;
        color:#2b3350;
        letter-spacing:.3px;
    }

    .dpd-vendor-bar{
        display:flex;
        align-items:center;
        justify-content:space-between;
        flex-wrap:wrap;
        gap:10px;
        padding:16px 20px 12px 20px;
        border-bottom:2px solid #f0f2f7;
    }
    .dpd-vendor-bar h4{
        margin:0;
        font-size:16px;
        font-weight:600;
        color:#333;
    }
    .dpd-vendor-bar h4 span{
        color:#1e4bd1;
        font-weight:700;
    }
    .dpd-vendor-bar .btn-primary{
        background:#1e4bd1 !important;
        border:none !important;
        border-radius:8px !important;
        font-weight:600;
        padding:8px 16px;
    }

    .dpd-table-scroll{
        overflow-x:auto;
    }
    table.dpd-table{
        width:100%;
        border-collapse:collapse !important;
        margin:0 !important;
        border:none !important;
    }
    table.dpd-table thead th{
        background:#f7f8fb !important;
        color:#6b7280 !important;
        font-size:12px;
        text-transform:uppercase;
        letter-spacing:.4px;
        font-weight:700 !important;
        border:none !important;
        border-bottom:1px solid #ececf2 !important;
        padding:12px 14px !important;
        text-align:center;
    }
    table.dpd-table tbody td{
        border:none !important;
        border-bottom:1px solid #f2f3f7 !important;
        padding:12px 14px !important;
        text-align:center;
        color:#2b3350;
        font-size:13.5px;
        vertical-align:middle !important;
    }
    table.dpd-table tbody tr:hover td{
        background:#f9fafc;
    }
    table.dpd-table tbody tr:first-child td{
        border-top:none !important;
    }

    .dpd-badge{
        display:inline-block;
        padding:4px 12px;
        border-radius:20px;
        font-size:11.5px;
        font-weight:700;
        letter-spacing:.3px;
    }
    .dpd-badge-unpaid{
        background:#fdeaea;
        color:#d9364a;
    }
    .dpd-badge-view{
        background:#e8f7ef !important;
        color:#1c9a55 !important;
        border:none !important;
        border-radius:20px !important;
        padding:6px 14px !important;
        font-weight:600;
        font-size:12px;
    }
    .dpd-badge-view:hover{
        background:#d7f0e3 !important;
    }

    .dpd-total-row td{
        background:#f4f6fb !important;
        font-weight:700 !important;
        font-size:14px !important;
        color:#1a2340 !important;
        border-top:2px solid #e2e6f0 !important;
    }

    .dpd-grand-card{
        background:linear-gradient(135deg,#1e2a52,#2b3d78) !important;
        border-radius:14px !important;
        overflow:hidden;
        color:#fff;
    }
    .dpd-grand-card thead th{
        background:transparent !important;
        color:#c9d3f0 !important;
        border:none !important;
        text-transform:uppercase;
        font-size:12px;
        letter-spacing:.4px;
        padding:16px 14px !important;
    }
    .dpd-grand-card tbody td{
        border:none !important;
        color:#fff !important;
        font-size:18px !important;
        font-weight:700 !important;
        padding:6px 14px 22px 14px !important;
        text-align:center;
    }
</style>
 <div class="well_N">
    <div class="dpd-wrap">
        <div class="dp_sdw">
            <div class="dpd-toolbar">
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
                    <div class="dpd-card">
                    <table class="table dpd-table" id="EmpExitInterviewList{{$table_count}}">

                        <thead>
                        <tr>
                        <th colspan="15" class="text-center" style="padding:0 !important;border:none !important;">
                            <div class="dpd-company-header">
                                <h3><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3>
                            </div>
                        </th>
                        </tr>
                        </thead>
                        <thead>
                        <tr>
                            <td colspan="5" style="border:none !important;padding:0 !important;">
                                <div class="dpd-vendor-bar">
                                    <h4>Vendor : <span>{{$row->name}}</span></h4>
                                    <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx','','','<?php echo $table_count?>','<?php echo $row->name?>')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
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
                                            type="button" class="dpd-badge-view">Payment Detail</button>
                                    <?php else:?>
                                    <span class="dpd-badge dpd-badge-unpaid">Unpaid</span>
                                    <?php endif;?>
                                </td>

                            </tr>
                        @endforeach
                        <tr class="text-center dpd-total-row">
                            <td colspan="4">Total</td>
                            <td>{{number_format($total_invocie,2) }} <?php $total_invocieEnd+=$total_invocie;?></td>
                            <td>{{number_format($total_paid,2) }}<?php $total_paidEnd+=$total_paid;?></td>
                            <td>{{number_format($total_remaining,2) }} <?php $total_remainingEnd+=$total_remaining;?></td>
                        </tr>

                        </tbody>




                        @php $table_count++; @endphp

                    </table>
                    </div>
                @endforeach

                <div class="dpd-grand-card">
                <table class="table" id="EmpExitInterviewList{{$table_count}}" style="margin:0 !important;">
                    <thead>
                    <tr class="text-center">
                        <th colspan="4" class="text-center">Grand Total</th>
                        <th class="text-center">Total Invoice Amount</th>
                        <th class="text-center">Total Paid Amount</th>
                        <th class="text-center">Total Received Amount</th>
                    </tr>
                    </thead>
                    <tr class="text-center">
                        <td colspan="4"></td>
                        <td>{{number_format($total_invocieEnd,2)}}</td>
                        <td>{{number_format($total_paidEnd,2)}}</td>
                        <td>{{number_format($total_remainingEnd,2)}}</td>
                    </tr>
                </table>
                </div>
            </span>
        </div>
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