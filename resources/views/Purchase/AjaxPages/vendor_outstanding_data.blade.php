<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$accType = Auth::user()->acc_type;

?>
<script !src="">
    var n = 0;
</script>
<div class="row" id="data">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo Form::open(array('url' => '/PaymentPurchaseVoucher','id'=>'bankPaymentVoucherForm'));?>
        <div class="panel">
            <div class="panel-body">
                <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <span id="MultiExport">
                            <h5 style="text-align: center" id="h3"></h5>
<?php
                              $clause='';
                            if ($vendor!=0):
                              $clause='and a.id='.$vendor.'';

                            endif;
                            $data=DB::Connection('mysql2')->select('select a.id,a.name from supplier as  a
                                 inner join
                                 new_purchase_voucher as b
                                 on
                                 a.id=b.supplier
                                 where b.status=1
                                 and (b.pv_date between "'.$from.'" and "'.$to.'" or grn_id=0)
                                 '.$clause.'
                                 group by a.id');
                            $total_pi_amountEnd=0;
                            $total_return_amountEnd=0;
                            $total_paid_amountEnd=0;
                            $total_remainigEnd=0;
                            $main_count=1;
                            ?>
                            @foreach ($data as $row)



                            <table style="width: 100%" class="table-bordered sf-table-list AutoCounter table{{$main_count}}" id="export_table_to_excel_<?php echo $main_count; ?>">
                                <thead>
                                    <th colspan="7" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
                                </thead>
                                <thead>
                                    <th colspan="7" class="text-center">Vendor Outstanding Report</th>
                                </thead>
                                <thead>
                                    <th colspan="7" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
                                </thead>
                                <thead>
                                <th colspan="7" class="text-center"><h3 class="table{{$main_count}}">{{$row->name}}</h3></th>
                                </thead>
                                <thead>

                                <th class="text-center">S.No</th>
                                <th class="text-center">PI. No.</th>
                                <th class="text-center">PI Date</th>
                                <th class="text-center">PI Amount</th>
                                <th class="text-center">Return Amount</th>
                                <th class="text-center">Paid Amount</th>
                                <th class="text-center">Remaining</th>

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
                                and (pv_date between "'.$from.'" and "'.$to.'" or grn_id=0)
                                and status=1');
                                ?>

                                @foreach($data1 as $row1)
                                    <?php
                                    $purchase_amount=ReuseableCode::get_purchase_net_amount($row1->id);
                                    $rerun_amount=ReuseableCode::return_amount_by_date($row1->grn_id,2,$from,$to);
                                    $paid_amount=CommonHelper::PaymentPurchaseAmountCheck_aging($row1->id,$from,$to);
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


