<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$accType = Auth::user()->acc_type;


$view=ReuseableCode::check_rights(37);


?>
<script !src="">
    var n = 0;
</script>

<style>
    /* ===== Vendor Balance Report - card style matching Trial Balance layout ===== */
    #data .vbr-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(30, 39, 87, 0.08);
        padding: 24px 24px 12px 24px;
        margin-bottom: 20px;
    }

    #data .vbr-header-block {
        text-align: center;
        margin-bottom: 10px;
    }

    #data .vbr-header-block h5,
    #data .vbr-header-block h3 {
        color: #1e2757;
    }

    #data table.sf-report-print-table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 28px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(30, 39, 87, 0.06);
    }

    /* Vendor name title row */
    #data table.sf-report-print-table thead:first-of-type th {
        background: #eef0fa;
        border-left: 4px solid #6c5ce7;
        text-align: left !important;
        padding: 12px 16px;
    }

    #data table.sf-report-print-table thead:first-of-type h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1e2757;
    }

    /* Column header row */
    #data table.sf-report-print-table thead:nth-of-type(2) th {
        background: #f5f6fb;
        color: #444a6b;
        font-weight: 600;
        font-size: 13px;
        padding: 10px 12px;
        border-bottom: 1px solid #e2e4f0;
    }

    #data table.sf-report-print-table tbody td {
        padding: 10px 12px;
        font-size: 13.5px;
        color: #2a2f4a;
        border-bottom: 1px solid #eef0f6;
    }

    #data table.sf-report-print-table tbody tr:hover {
        background: #fafbff;
    }

    /* Total row per vendor */
    #data table.sf-report-print-table tbody tr:last-child td {
        border-top: 2px solid #1e2757;
        border-bottom: none;
        background: #f8f9fd;
        font-size: 14px !important;
        font-weight: 700;
        color: #1e2757;
    }

    /* Grand total table */
    #data table.GrandTotal {
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(30, 39, 87, 0.06);
    }

    #data table.GrandTotal thead th {
        background: #1e2757;
        color: #ffffff;
        padding: 12px;
        font-size: 13.5px;
        font-weight: 600;
    }

    #data table.GrandTotal tbody td,
    #data table.GrandTotal tr td {
        padding: 12px;
        font-size: 14px;
        font-weight: 700;
        color: #1e2757;
        background: #f5f6fb;
    }

    #data .btn-success.btn-xs {
        border-radius: 6px;
    }
</style>

<div class="row" id="data">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo Form::open(array('url' => '/PaymentPurchaseVoucher','id'=>'bankPaymentVoucherForm'));?>
        <div class="panel">
            <div class="panel-body">
                <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="vbr-card">
                            <span id="MultiExport">
                            <div class="vbr-header-block">
                                <h5 style="text-align: center" id="h3"></h5>
                                <?php echo CommonHelper::headerPrintSectionInPrintView(Session::get('run_company'), 'Vendor Balance Report', 'From '.CommonHelper::changeDateFormat($from).' To '.CommonHelper::changeDateFormat($to)); ?>
                            </div>
                                <?php
                                $clause='';
                                if ($vendor!=0):
                                $clause='and a.id='.$vendor.'';

                                endif;
                                $data=DB::Connection('mysql2')->select('select a.id,a.name from supplier as  a
                                 inner join
                                 new_purchase_voucher as b
                                 on
                                 a.id=supplier
                                 where b.status=1
                                 and b.pv_date between "'.$from.'" and "'.$to.'"
                                 '.$clause.'
                                 group by a.id');
                                $total_pi_amountEnd=0;
                                $total_return_amountEnd=0;
                                $total_paid_amountEnd=0;
                                $total_remainigEnd=0;
                                $main_count=1;
                                ?>
                                @foreach ($data as $row)



                                    <table style="width: 100%" class="table-bordered sf-table-list AutoCounter table{{$main_count}} sf-report-print-table" id="export_table_to_excel_<?php echo $main_count; ?>">
                                        <thead>
                                        </thead>
                                        <thead>
                                        <th colspan="8" class="text-center"><h3 class="table{{$main_count}}">{{$row->name}}</h3></th>
                                        </thead>
                                        <thead>

                                        <th class="text-center">S.No</th>
                                        <th class="text-center">PI. No.</th>
                                        <th class="text-center">PI Date</th>
                                        <th class="text-center">PI Amount</th>
                                        <th class="text-center">Return Amount</th>
                                        <th class="text-center">Paid Amount</th>
                                        <th class="text-center">Remaining</th>
                                        <th class="text-center printHide">View</th>
                                        </thead>
                                        <tbody id="filterBankPaymentVoucherList">
                                        <?php


                                        $total_pi_amount=0;
                                        $total_return_amount=0;
                                        $total_paid_amount=0;
                                        $total_remainig=0;

                                        $counter=1;
                                        //  $data1=DB::Connection('mysql2')->table('new_purchase_voucher')->where('supplier',$row->id)->
                                        //  whereBetween('pv_date', [$from, $to])->get();

                                        $data1=DB::Connection('mysql2')->select('select * from new_purchase_voucher
                                where supplier="'.$row->id.'"
                                and pv_date between "'.$from.'" and "'.$to.'"
                                and status=1');
                                        ?>

                                        @foreach($data1 as $row1)
                                            <?php
                                            $purchase_amount=ReuseableCode::get_purchase_net_amount($row1->id);
                                            $rerun_amount=ReuseableCode::return_amount_by_date($row1->grn_id,2,$from,$to);
                                            $paid_amount=CommonHelper::PaymentPurchaseAmountCheck_aging_use_for_balance($row1->id);
                                            $remaining_data=  $purchase_amount-$rerun_amount-$paid_amount;
                                            ?>
                                            @if($remaining_data>0)
                                                <tr title="grn_id={{$row1->grn_id}}">

                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center">{{$row1->pv_no}}</td>
                                                    <td class="text-center">{{CommonHelper::changeDateFormat($row1->pv_date)}}</td>
                                                    <td class="text-right">{{number_format($purchase_amount,2)}}</td>
                                                    <td class="text-right">{{number_format($rerun_amount,2)}}</td>
                                                    <td class="text-right">{{number_format($paid_amount,2)}}</td>
                                                    <td class="text-right">{{number_format($remaining_data,2)}}</td>
                                                    @if($view==true)
                                                        <td class="text-center printHide">
                                                            <button
                                                                    onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row1->id ?>','View Purchase Voucher','<?php echo Session::get('run_company')?>')"
                                                                    type="button" class="btn btn-success btn-xs">View</button>
                                                        </td>
                                                    @endif
                                                    <?php
                                                    $total_pi_amount+=$purchase_amount;
                                                    $total_return_amount+=$rerun_amount;
                                                    $total_paid_amount+=   $paid_amount;
                                                    $total_remainig+=$remaining_data;
                                                    ?>
                                                </tr>
                                            @endif

                                        @endforeach

                                        <tr style="font-size: large;font-weight: bold">
                                            <td colspan="3">Total</td>
                                            <td class="text-right" colspan="1">{{number_format($total_pi_amount,2)}} <?php $total_pi_amountEnd+=$total_pi_amount;?></td>
                                            <td class="text-right" colspan="1">{{number_format($total_return_amount,2)}}<?php $total_return_amountEnd+=$total_return_amount;?></td>
                                            <td class="text-right" colspan="1">{{number_format($total_paid_amount,2)}}<?php $total_paid_amountEnd+=$total_paid_amount;?></td>
                                            <td class="text-right <?php if ($total_remainig==0): ?>  hidee{{$main_count}}<?php endif ?>" colspan="1">{{number_format($total_remainig,2)}}<?php $total_remainigEnd+=$total_remainig?></td>

                                            <input type="hidden" value="{{$total_remainig}}" class="val" id="{{$main_count}}"/>
                                        </tr>


                                        </tbody>
                                    </table>
                                    <?php $main_count++; ?>
                                @endforeach

                                <br>
                            <table style="width: 100%" class="table-bordered sf-table-list GrandTotal" id="export_table_to_excel_<?php echo $main_count; ?>">
                                <thead>
                                <tr>
                                    <th class="text-center" colspan="3">Grand Total</th>
                                    <th class="text-center">Total PI Amount</th>
                                    <th class="text-center">Total Return Amount</th>
                                    <th class="text-center">Total Paid Amount</th>
                                    <th class="text-center">Total Remaining Amount</th>
                                </tr>
                                </thead>

                                <tr style="font-size: large;font-weight: bold">
                                    <td colspan="3">Total</td>
                                    <td class="text-right" colspan="1">{{number_format($total_pi_amountEnd,2)}}</td>
                                    <td class="text-right" colspan="1">{{number_format($total_return_amountEnd,2)}}</td>
                                    <td class="text-right" colspan="1">{{number_format($total_paid_amountEnd,2)}}</td>
                                    <td class="text-right" colspan="1">{{number_format($total_remainigEnd,2)}}</td>
                                </tr>
                            </table>


                            </span>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>

<script>
    $(document).ready(function(){

    });

    function checking()
    {
        $('.checkbox1').each(function()
        {
            if ($(this).is(':checked'))
            {
                $('#BtnPayment').prop('disabled',false);
            }
            else
            {
                $('#BtnPayment').prop('disabled',false);
            }
        });
    }


    $( document ).ready(function() {
        $('.val').each(function (i, obj) {
            var value = $(this).val();
            value = parseFloat(value);

            if (value == 0) {
                var id = obj.id;
                //  $('.table'+id+'').remove();
                $('.table' + id + '').remove();
            }


        });

        var AutoCount = 1;
        $(".AutoCounter").each(function(){

            $(this).attr('id','export_table_to_excel_'+AutoCount);
            AutoCount++;


//            $('#wrapper [id$="123"]').attr('id', function (_, id) {
//                return id.replace('123', '321');
//            });
        });
        n = AutoCount;
        $('.GrandTotal').attr('id','export_table_to_excel_'+AutoCount);
    });



    //table to excel (multiple table)
    var array1 = new Array();
    //var n = ''; //Total table

    for ( var x=1; x<=n; x++ ) {
        array1[x-1] = 'export_table_to_excel_' + x;
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

            document.getElementById("dlink").href = uri + base64(format(allOfIt, ctx));;
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();
        }
    })();
</script>