<?php use App\Helpers\CommonHelper; ?>

<div class="tb-report-wrap">
    <div class="tb-report-header">
        <p class="tb-company-name"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></p>
        <p class="tb-report-title">Client Wise Summary Report</p>
        <p class="tb-printed-on">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p>
    </div>

    <div class="tb-table-scroll">
    <table id="EmpExitInterviewList1" class="tb-table">
        <thead>
        <tr class="tb-col-row">
            <th class="text-center">S.No</th>
            <th class="text-left">Client Name</th>
            <th class="text-right">Amount</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $Counter = 1;
        $payable = 0;
        $advance = 0;
        $total_amount = 0;

        foreach ($Client as $Fil):
            $amount = CommonHelper::get_ledger_amount($Fil->acc_code, $m, 1, 0, $from, $to);

            if ($amount != 0):
                $displayAmount = $amount;
                if ($displayAmount < 0):
                    $total_amount += $displayAmount;
                    $displayAmount = $displayAmount * -1;
                    $advance += $displayAmount;
                    $displayAmount = number_format($displayAmount, 2);
                    $displayAmount = '(' . $displayAmount . ')';
                else:
                    $payable += $displayAmount;
                    $total_amount += $displayAmount;
                    $displayAmount = number_format($displayAmount, 2);
                endif;
        ?>
        <tr>
            <td class="text-center"><?php echo $Counter++; ?></td>
            <td class="text-left link_hide">
                <a class="linkRem" target="_blank" href="<?php echo URL('finance/viewLedgerReport?AccId='.$Fil->acc_id.'&&FromDate='.$from.'&&ToDate='.$to.'&&m='.$m)?>"><?php echo $Fil->name?></a>
            </td>
            <td class="text-right"><?php echo $displayAmount; ?></td>
        </tr>
        <?php endif; endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-center" colspan="2">Total</td>
            <td class="text-right"><?php echo number_format($total_amount, 2); ?></td>
        </tr>
        </tfoot>
    </table>
    </div>

    <div style="padding: 18px 32px;">
        <p>Receivable : {{number_format($payable,2)}}</p>
        <p>Advance : {{number_format($advance,2)}}</p>
        <?php
        $total_payables = $payable - $advance;
        if ($total_payables < 0):
            $total_payables = $total_payables * -1;
        endif;
        ?>
        <p><strong>Total Receivable : {{number_format($total_payables,2)}}</strong></p>
    </div>
</div>

<script !src="">
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