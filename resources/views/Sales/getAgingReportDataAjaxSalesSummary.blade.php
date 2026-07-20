<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$TotInvoiceAmountEnd = 0;
$TotReturnAmountEnd = 0;
$TotPaidAmountEnd = 0;
$TotBalanceEnd = 0;
$total_not_yet_due_end=0;
$Tot_1_30End = 0;
$Tot_31_60End = 0;
$Tot_61_90End = 0;
$Tot_91_180End = 0;
$Tot_180_1000End = 0;
$TotOverAllEnd = 0;
$Clause = '';

$company_data= ReuseableCode::get_account_year_from_to(Session::get('run_company'));
$from=$company_data[0];
$as_on=Request::get('as_on');

if($_GET['CustomerId'] == 'all')
{$Clause = '';}
else{$Clause = 'and b.buyers_id="'.$_GET['CustomerId'].'"';}
$Cust = DB::Connection('mysql2')->select('select a.id,a.name,a.acc_id from customers a
                                          INNER JOIN sales_tax_invoice b ON b.buyers_id = a.id
                                          WHERE b.status = 1
                                          '.$Clause.'
                                          and (b.gi_date between "'.$from.'" and "'.$as_on.'" or b.so_type=1)
                                          GROUP BY b.buyers_id');
$MainCount =  count($Cust);
$BuyerCounter =1;
$count=1;
?>
<script !src="">
    var ClsCounter = "";
    var n = 0;
</script>

<div class="tb-report-wrap">
    <div class="tb-report-header">
        <p class="tb-company-name"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></p>
        <p class="tb-report-title">Debtor Ageing Summary Report</p>
        <p class="tb-date-range">As On : <?php echo date_format(date_create($as_on),'F d, Y') ?></p>
        <p class="tb-printed-on">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p>
    </div>

    <div class="tb-table-scroll">
    <table class="tb-table" id="export_table_to_excel_1">
        <thead>
        <tr class="tb-col-row">
            <th colspan="8" class="text-center">Buyer</th>
            <th class="text-right">Not Yet Due</th>
            <th class="text-right">(1-30)</th>
            <th class="text-right">(31-60)</th>
            <th class="text-right">(61-90)</th>
            <th class="text-right">(91-180)</th>
            <th class="text-right">More Than 180 days</th>
            <th class="text-right">Total Amount</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($Cust as $Cfil):
            $vendor_data=DB::Connection('mysql2')->select('select a.id,a.model_terms_of_payment,a.due_date,a.gi_no,a.gi_date,(sum(b.amount)+a.sales_tax+a.sales_tax_further)total
                        from sales_tax_invoice a
                        inner join
                        sales_tax_invoice_data b
                        on
                        a.id=b.master_id

                        where a.status=1
                        and (a.gi_date between "'.$from.'" and "'.$as_on.'" or b.so_type=1)
                        and a.buyers_id  = '.$Cfil->id.'
                        group by a.id');

            $debit=   DB::Connection('mysql2')->selectOne('select sum(amount)amount from transactions where status=1 and debit_credit=1 and acc_id="'.$Cfil->acc_id.'"')->amount;
            $credit=   DB::Connection('mysql2')->selectOne('select sum(amount)amount from transactions where status=1 and debit_credit=0 and acc_id="'.$Cfil->acc_id.'"')->amount;

            $amount=$debit-$credit;

            $TotInvoiceAmount = 0;
            $TotReturnAmount = 0;
            $TotPaidAmount = 0;
            $TotBalance = 0;
            $total_not_yet_due=0;
            $Tot_1_30 = 0;
            $Tot_31_60 = 0;
            $Tot_61_90 = 0;
            $Tot_91_180 = 0;
            $Tot_180_1000 = 0;
            $TotOverAll = 0;
            foreach($vendor_data as $fil):
                $InvoiceAmount = $fil->total+SalesHelper::get_freight($fil->id);
                $PaidAmount = CommonHelper::bearkup_receievd_approved($fil->id,$from,$as_on);
                $return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice_by_date($fil->id,$from,$as_on);
                $BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;
                $date1_ts = strtotime($fil->gi_date.'+'.$fil->model_terms_of_payment.'day');
                $date2_ts = strtotime($as_on);
                $diff = $date2_ts - $date1_ts;
                $NoOfDays = round($diff / 86400);
                $MultiRvNO = DB::Connection('mysql2')->select('select rv_no from brige_table_sales_receipt where si_id = '.$fil->id.' group by rv_no ');

                if ($BalanceAmount>0):
                    if($NoOfDays <= 0){$total_not_yet_due+=$BalanceAmount;}
                    if ( in_array($NoOfDays, range(1,30))){$Tot_1_30+=$BalanceAmount;}
                    if ( in_array($NoOfDays, range(31,60))){ $Tot_31_60+=$BalanceAmount;}
                    if ( in_array($NoOfDays, range(61,90))){ $Tot_61_90+=$BalanceAmount;}
                    if ( in_array($NoOfDays, range(91,180))){$Tot_91_180+=$BalanceAmount;}
                    if ( in_array($NoOfDays, range(181,10000))){$Tot_180_1000+=$BalanceAmount;}
                    $TotOverAll+=$BalanceAmount;
                endif;
            endforeach;

            if($TotOverAll > 0):
        ?>
        <tr>
            <td colspan="8" class="text-left link_hide"><?php echo CommonHelper::byers_name($Cfil->id)->name;?></td>
            <td class="text-right"><?php echo number_format($total_not_yet_due,2); $total_not_yet_due_end+=$total_not_yet_due;?></td>
            <td class="text-right"><?php echo number_format($Tot_1_30,2); $Tot_1_30End+=$Tot_1_30;?></td>
            <td class="text-right"><?php echo number_format($Tot_31_60,2); $Tot_31_60End+=$Tot_31_60;?></td>
            <td class="text-right"><?php echo number_format($Tot_61_90,2); $Tot_61_90End+=$Tot_61_90;?></td>
            <td class="text-right"><?php echo number_format($Tot_91_180,2); $Tot_91_180End+=$Tot_91_180;?></td>
            <td class="text-right"><?php echo number_format($Tot_180_1000,2); $Tot_180_1000End+=$Tot_180_1000;?></td>
            <td class="text-right"><?php echo number_format($TotOverAll,2); $TotOverAllEnd+=$TotOverAll;?></td>
            <td class="text-right hide"><?php echo number_format($amount,2); ?></td>
        </tr>
        <?php
            endif;
        endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="8" class="text-center">Grand Total</th>
            <td class="text-right"><?php echo number_format($total_not_yet_due_end,2)?></td>
            <td class="text-right"><?php echo number_format($Tot_1_30End,2);?></td>
            <td class="text-right"><?php echo number_format($Tot_31_60End,2);?></td>
            <td class="text-right"><?php echo number_format($Tot_61_90End,2);?></td>
            <td class="text-right"><?php echo number_format($Tot_91_180End,2);?></td>
            <td class="text-right"><?php echo number_format($Tot_180_1000End,2);?></td>
            <td class="text-right"><?php echo number_format($TotOverAllEnd,2);?></td>
        </tr>
        </tfoot>
    </table>
    </div>
</div>

<script !src="">
    //table to excel (multiple table)
    var array1 = new Array();
    var n = 1; //Total table

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