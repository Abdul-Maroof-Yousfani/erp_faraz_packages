<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Transactions;
$export=ReuseableCode::check_rights(244);
?>
@extends('layouts.default')
@section('content')

<div class="">
    <div class="well_N">
        <div class="dp_sdw">      

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <a id="dlink" style="display:none;"></a>
        <button type="button" class="btn btn-warning" onclick="ExportToExcelAll('xlsx','','','')">All Export <b>(xlsx)</b></button>
    </div>

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
        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList{{$table_count}}">



            <thead>
                <th colspan="8" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
            </thead>
            <thead>
                <th colspan="8" class="text-center">Vendor Payment Detail Report</th>
            </thead>
            <thead>
                <th colspan="8" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
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
                        <?php  ?>
                        <td>{{number_format($paid,2)}}</td>
                        <td>{{number_format($remaining,2)}}</td>
                        <td class="text-center">
                            <?php
                            if($paid > 0):
                            ?>
                            <button
                                    onclick="showDetailModelOneParamerter('pdc/viewPaymentDetail','<?php echo $row1->id ?>','View Payment Voucher Detail')"
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
            <th class="text-center">Total Remaining Amount</th>
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
