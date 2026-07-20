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
 .vpd-wrapper{padding:20px;}
.vpd-topbar{text-align:right;margin-bottom:16px;}
.vpd-card{background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);padding:24px 28px;margin-bottom:24px;overflow:hidden;}
.vpd-card-header{background:#eef1f8;border-radius:8px;padding:18px 20px;text-align:center;margin-bottom:18px;}
.vpd-company-name{font-size:20px;font-weight:700;color:#1f2a5c;margin:0 0 4px 0;}
.vpd-report-title{font-size:14px;font-weight:600;letter-spacing:.5px;text-transform:uppercase;color:#3a3f55;margin:0;}
.vpd-printed-on{text-align:right;font-size:12px;color:#555;margin-top:10px;}
.vpd-vendor-row{display:flex;align-items:center;justify-content:space-between;border-bottom:2px solid #1f2a5c;padding-bottom:10px;margin-bottom:14px;}
.vpd-vendor-name{font-size:16px;font-weight:700;color:#1f2a5c;margin:0;}
table.vpd-table{width:100%;border-collapse:collapse;font-size:13px;}
table.vpd-table thead th{background:#eef1f8;color:#3a3f55;font-weight:700;text-transform:uppercase;letter-spacing:.3px;text-align:center;padding:10px 8px;border:none;font-size:12px;}
table.vpd-table tbody td{padding:9px 8px;text-align:center;border-bottom:1px solid #eef0f5;color:#333;font-weight: bold;}
table.vpd-table tbody tr:nth-child(even){background:#f8f9fc;}
table.vpd-table tbody tr:hover{background:#eef3ff;}
.vpd-total-row td{font-weight:700;font-size:14px;background:#eef1f8 !important;color:#1f2a5c;border-top:2px solid #1f2a5c;}
.vpd-grand-total-card table.vpd-table thead th{font-size:13px;}
.text-danger.vpd-unpaid{font-weight:600;}
.vpd-export-btn{border-radius:6px;}

</style>
<div class="well_N">
    <div class="vpd-wrapper">    
        <span id="MultiExport">
    
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
            <div class="vpd-card">
            <table class="table vpd-table" id="EmpExitInterviewList{{$table_count}}">
    
                <thead>
                    <tr>
                        <th colspan="8" style="background:transparent;padding:0;border:none;">
                            <div class="vpd-card-header">
                                <p class="vpd-company-name"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></p>
                                <p class="vpd-report-title">Vendor Payment Detail Report</p>
                                <p class="vpd-printed-on">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p>
                            </div>
                        </th>
                    </tr>
                </thead>
    
                <thead>
                    <tr>
                        <td colspan="8" style="border:none;padding:0;">
                            <div class="vpd-vendor-row">
                                <h4 class="vpd-vendor-name">Vendor : {{$row->name}}</h4>
                                <?php if($export == true):?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning vpd-export-btn" onclick="ExportToExcel('xlsx','','','<?php echo $table_count?>','<?php echo $row->name?>')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center">S.No</th>
                        <th class="text-center">PI</th>
                        <th class="text-center">PI Date</th>
                        <th class="text-center">Slip No</th>
                        <th class="text-center">Invoice Amount</th>
                        <th class="text-center">Paid Amount</th>
                        <th class="text-center">Remaining Amount</th>
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
                    $vendor_data=DB::Connection('mysql2')->select('select a.id,a.pv_no,a.pv_date,a.slip_no,(sum(b.net_amount)+a.sales_tax_amount)total,sum(c.amount)freight
                    from new_purchase_voucher a
                    inner join
                    new_purchase_voucher_data b
                    on
                    a.id=b.master_id
                    left join
                    addional_expense c
                    on
                    a.grn_id=c.main_id
                    where a.status=1
                    and a.supplier="'.$row->supplier.'"
                    group by a.id');
                    ?>
    
                    @foreach($vendor_data as $row1)
                        <tr class="text-center">
                            <td>{{$counter++}}</td>
                            <td>{{$row1->pv_no}}</td>
                            <td>{{CommonHelper::changeDateFormat($row1->pv_date)}}</td>
                            <td>{{$row1->slip_no}}</td>
                            <?php
                            $total=$row1->total+$row1->freight;
                            $paid=CommonHelper::PaymentPurchaseAmountCheck($row1->id);
                            $remaining=   $total-$paid;
    
                            $total_invocie+=$total;
                            $total_paid+=$paid;
                            $total_remaining+=$remaining;
                            ?>
                            <td>{{number_format($total,2)}}</td>
                            <td>{{number_format($paid,2)}}</td>
                            <td>{{number_format($remaining,2)}}</td>
                            <td class="text-center">
                                <?php if($paid > 0): ?>
                                <button
                                        onclick="showDetailModelOneParamerter('pdc/viewPaymentDetail','<?php echo $row1->id ?>','View Payment Voucher Detail')"
                                        type="button" class="btn btn-success btn-xs">Payment Detail</button>
                                <?php else:?>
                                <p class="text-danger vpd-unpaid">Unpaid</p>
                                <?php endif;?>
                            </td>
                        </tr>
                    @endforeach
    
                    <tr class="vpd-total-row">
                        <td colspan="4">Total</td>
                        <td>{{number_format($total_invocie,2) }} <?php $total_invocieEnd+=$total_invocie;?></td>
                        <td>{{number_format($total_paid,2) }}<?php $total_paidEnd+=$total_paid;?></td>
                        <td>{{number_format($total_remaining,2) }} <?php $total_remainingEnd+=$total_remaining;?></td>
                        <td></td>
                    </tr>
                </tbody>
    
                @php $table_count++; @endphp
    
            </table>
            </div>
        @endforeach
    
        <div class="vpd-card vpd-grand-total-card">
            <table class="table vpd-table" id="EmpExitInterviewList{{$table_count}}">
                <thead>
                <tr>
                    <th colspan="4" class="text-center">Grand Total</th>
                    <th class="text-center">Total Invoice Amount</th>
                    <th class="text-center">Total Paid Amount</th>
                    <th class="text-center">Total Remaining Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr class="vpd-total-row">
                    <td colspan="4"></td>
                    <td>{{number_format($total_invocieEnd,2)}}</td>
                    <td>{{number_format($total_paidEnd,2)}}</td>
                    <td>{{number_format($total_remainingEnd,2)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    
        </span>
    
    </div>
</div>

<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
<script !src="">
    function ExportToExcel(type, fn, dl,Table,VendorName) {
        var elt = document.getElementById('EmpExitInterviewList'+Table);
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || (VendorName+' Payment Detail <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
    }
    function ExportToExcelAll(type, fn, dl,Table) {
        $('.btn-primary').css('display','none');
        var elt = document.getElementById('MultiExport');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('All Vendor Payment Detail <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        $('.btn-primary').css('display','block');
    }
</script>

<script !src="">
    //table to excel (multiple table)
    var array1 = new Array();
    var n = '<?php echo $table_count?>'; //Total table

    for ( var x=1; x<=n; x++ ) {
        array1[x-1] = 'EmpExitInterviewList' + x;
    }

    var tablesToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>'
                , templateend = '</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>'
                , body = '<body>'
                , tablevar = '<table>{table'
                , tablevarend = '}</table>'
                , bodyend = '</body></html>'
                , worksheet = '<x:ExcelWorksheet><x:Name>'
                , worksheetend = '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>'
                , worksheetvar = '{worksheet'
                , worksheetvarend = '}'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
                , wstemplate = ''
                , tabletemplate = '';

        return function (table, name, filename) {
            var tables = table;
            var wstemplate = '';
            var tabletemplate = '';

            wstemplate = worksheet + worksheetvar + '0' + worksheetvarend + worksheetend;
            for (var i = 0; i < tables.length; ++i) {
                tabletemplate += tablevar + i + tablevarend;
            }

            var allTemplate = template + wstemplate + templateend;
            var allWorksheet = body + tabletemplate + bodyend;
            var allOfIt = allTemplate + allWorksheet;

            var ctx = {};
            ctx['worksheet0'] = name;
            for (var k = 0; k < tables.length; ++k) {
                var exceltable;
                if (!tables[k].nodeType) exceltable = document.getElementById(tables[k]);
                ctx['table' + k] = exceltable.innerHTML;
            }

            document.getElementById("dlink").href = uri + base64(format(allOfIt, ctx));
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();
        }
    })();
</script>
@endsection