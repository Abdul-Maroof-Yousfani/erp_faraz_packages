
<?php use App\Helpers\CommonHelper; ?>
<style>
    @media print {
        a[href]:after {
            content: none !important;
        }
    }

    tr:hover {
        background-color: yellow;
    }
</style>
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Client Wise Summary Report</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row" id="PrintEmpExitInterviewList">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="table-responsive">
                                            <table  id="EmpExitInterviewList1" class="table table-bordered sf-table-list">
                                                <thead>
                                                    <th colspan="3" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
                                                </thead>
                                                <thead>
                                                    <th colspan="3" class="text-center">Client Wise Summary Report</th>
                                                </thead>
                                                <thead>
                                                <th colspan="3" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
                                                </thead>
                                                <thead>
                                                <th style="" class="text-center">S.No</th>
                                                {{--<th class="text-center">Acc-Id</th>--}}

                                                <th class="text-left">CLient Name</th>
                                                <th class="text-center">Amount</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $Counter =1;
                                                $payable=0;
                                                $advance=0;
                                                $total_amount=0;
                                                foreach($Client as $Fil):

                                                $amount=CommonHelper::get_ledger_amount($Fil->acc_code,$m,1,0,$from,$to);

                                                        if ($amount!=0):

                                           //         $customer_ope=    DB::Connection('mysql2')->table('customer_opening_balance')->where('buyer_id',$Fil->id)->sum('balance_amount');

                                                ?>

                                                <tr>
                                                    <td class="text-center"><?php echo $Counter++;?></td>
                                                    {{--<td>< ?php echo $Fil->acc_id?></td>--}}

                                                    <td class="text-left "><b style="font-size: large;font-weight: bolder"> <a class="linkRem" target="_blank" href="<?php echo URL('finance/viewLedgerReport?AccId='.$Fil->acc_id.'&&FromDate='.$from.'&&ToDate='.$to.'&&m='.$m)?>">
                                                                <?php echo $Fil->name?></a>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php   $amount=CommonHelper::get_ledger_amount($Fil->acc_code,$m,1,0,$from,$to);
                                                            $amountt=$amount;
                                                        if ($amount<0):
                                                            $total_amount+=$amount;
                                                            $amount=$amount*-1;
                                                            $advance+=$amount;
                                                            $amount=number_format($amount,2);
                                                            $amount='('.$amount.')';


                                                        else:
                                                            $payable+=$amount;
                                                            $total_amount+=$amount;
                                                            $amount=number_format($amount,2);
                                                        endif;
                                                        echo $amount;
                                                        ?>
                                                    </td>



                                                </tr>
                                                <?php endif; ?>
                                                <?php

                                                ?>

                                                <?php endforeach;?>
                                                <tr style="background-color: darkgray">
                                                    <td class="text-center" style="font-weight: bold;font-size: large" colspan="2">Total</td>
                                                    <td class="text-right" style="font-weight: bold;font-size: large" colspan="1">{{number_format($total_amount,2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><h4>Receivable : {{number_format($payable,2)}}</h4></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><h4>Advance : {{number_format($advance,2)}}</h4></td>
                                                </tr>
                                                <?php $total_payables=$payable-$advance;
                                                if ($total_payables<0):
                                                    $total_payables=$total_payables*-1;
                                                endif;
                                                ?>

                                                <tr>
                                                    <td colspan="3"><h4>Total Receivable : {{number_format($total_payables,2)}}</h4></td>
                                                </tr>
                                                </tbody>
                                            </table>


                                            <h4>Receivable : {{number_format($payable,2)}}</h4>
                                            <h4>Advance : {{number_format($advance,2)}}</h4>
                                            <?php $total_payables=$payable-$advance;
                                            if ($total_payables<0):
                                                $total_payables=$total_payables*-1;
                                            endif;
                                            ?>
                                            <h4>Total Receivable : {{number_format($total_payables,2)}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script !src="">



    //table to excel (multiple table)
    var array1 = new Array();
    var n = 1; //Total table

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

            document.getElementById("dlink").href = uri + base64(format(allOfIt, ctx));;
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();
        }
    })();

</script>