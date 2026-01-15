<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$Clause = '';
$TotInvoiceAmountEnd = 0;
$TotReturnAmountEnd = 0;
$TotPaidAmountEnd = 0;
$TotBalanceEnd = 0;
$Tot_1_30End = 0;
$Tot_31_60End = 0;
$Tot_61_90End = 0;
$Tot_91_180End = 0;
$Tot_180_1000End = 0;
$TotOverAllEnd = 0;
$TotNotYetDueEnd = 0;
?>
<?php if($_GET['ReportType'] == 2):?>
<style>
    .ShowHideRow{
        display: block;
    }
</style>
<?php else:?>
<style>
    .ShowHideRow{
        display: none;
    }
</style>
<?php endif;?>


<script !src="">
    var n = 0;
</script>
<?php
if($_GET['SupplierId'] == 'all')
{$Clause = '';}
else{$Clause = 'and b.supplier="'.$_GET['SupplierId'].'"';}

$Supp = DB::Connection('mysql2')->select('select a.id,a.name,a.acc_id from supplier a
                                          INNER JOIN new_purchase_voucher b ON b.supplier = a.id
                                          WHERE b.status = 1
                                          '.$Clause.'

                                         and(b.pv_date between "'.$from.'" and "'.$to.'" or grn_id=0)

                                          GROUP BY b.supplier');
$MainCount =  count($Supp);
$VendorCounter=1;
$main_count=1;
foreach($Supp as $Sfil):

$vendor_data=DB::Connection('mysql2')->select('select a.id,a.due_date,a.pv_no,a.pv_date,a.slip_no,(sum(b.net_amount)+a.sales_tax_amount)total,a.grn_id
                from new_purchase_voucher a
                inner join
                new_purchase_voucher_data b
                on
                a.id=b.master_id

                where a.status=1

                and(a.pv_date between "'.$from.'" and "'.$to.'"  or grn_id=0 and work_order_id=0)

                and a.supplier="'.$Sfil->id.'"
                group by a.id');
?>
<table class="table table-bordered ApnaBorder AutoCounter table{{$main_count}} " id="export_table_to_excel_<?php echo $VendorCounter; ?>">

    <thead>
        <th colspan="15" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
    </thead>
    <thead>
        <th colspan="15" class="text-center">Vendor Ageing Detail Report</th>
    </thead>
    <thead>
        <th colspan="15" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
    </thead>
    <thead class="ApnaBorder">
    <tr class="ApnaBorder text-center">

        <?php   ?>
        <th colspan="8" class="ApnaBorder text-center" ><?php echo CommonHelper::get_supplier_name($Sfil->id)?></th>

        <th class="ApnaBorder text-center Chnage-bg" colspan="7">Days</th>
    </tr>
    </thead>
    <thead>
    <tr class="text-center ApnaBorder ">
        <th class="text-center ApnaBorder" style="width: 7%">PI Date</th>
        <th class="text-center ApnaBorder">PI NO</th>
        <th class="text-center ApnaBorder">PO Date</th>
        <th class="text-center ApnaBorder">PO NO</th>
        <th class="text-center ApnaBorder">Invoice Amount</th>
        <th class="text-center ApnaBorder">Return Amount</th>
        <th class="text-center ApnaBorder">Paid Amount</th>
        <th class="text-center ApnaBorder">Balance</th>
        <th class="text-center ApnaBorder Chnage-bg">Not Yet Due</th>
        <th class="text-center ApnaBorder Chnage-bg">(1-30)</th>
        <th class="text-center ApnaBorder Chnage-bg">(31-60)</th>
        <th class="text-center ApnaBorder Chnage-bg">(61-90)</th>
        <th class="text-center ApnaBorder Chnage-bg">(91-180)</th>
        <th class="text-center ApnaBorder Chnage-bg">More Than 180 days</th>
        <th class="text-center ApnaBorder Chnage-bg">Total Amount</th>
        {{--<th class="text-center ApnaBorder Chnage-bg">Days Outstanding</th>--}}
    </tr>
    </thead>
    <tbody class="ApnaBorder">
    <?php
    $TotInvoiceAmount = 0;
    $TotReturnAmount = 0;
    $TotPaidAmount = 0;
    $TotBalance = 0;
    $Tot_1_30 = 0;
    $Tot_31_60 = 0;
    $Tot_61_90 = 0;
    $Tot_91_180 = 0;
    $Tot_180_1000 = 0;
    $TotOverAll = 0;
    $TotNotYet = 0;




    foreach($vendor_data as $fil):

    $no=0;
    $one=0;
    $two=0;
    $three=0;
    $four=0;
    $five=0;
    $InvoiceAmount = $fil->total;
  //  $PaidAmount = CommonHelper::PaymentPurchaseAmountCheck_aging($fil->id);
    $PaidAmount = CommonHelper::PaymentPurchaseAmountCheck_aging($fil->id,$from,$to);
    $return_amount=ReuseableCode::return_amount_by_date($fil->grn_id,2,$from,$to);
    $BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;



    // Calculating the difference in timestamps
    $diffss = strtotime($fil->due_date) - strtotime($fil->pv_date);

    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    $diffss = abs(round($diffss / 86400));



    $date1_ts = strtotime($fil->pv_date.'+'.$diffss.'day');
    $date2_ts = strtotime(date('Y-m-d'));
    $diff = $date2_ts - $date1_ts;
    $NoOfDays = round($diff / 86400);
      if($BalanceAmount > 0):
    ?>
    <tr class="text-center ApnaBorder yes">
        <td class="ApnaBorder"><?php echo CommonHelper::changeDateFormat($fil->pv_date)?></td>
        <td class="ApnaBorder"><?php echo $fil->pv_no?></td>
        <td class="ApnaBorder"></td>
        <td class="ApnaBorder"></td>
        <td class="ApnaBorder"><?php echo number_format($InvoiceAmount,2); $TotInvoiceAmount+=$InvoiceAmount;?></td>
        <td class="ApnaBorder">{{$return_amount,2}}<?php $TotReturnAmount+=$return_amount;?></td>
        <td class="ApnaBorder"><?php echo number_format($PaidAmount,2); $TotPaidAmount+=$PaidAmount?></td>
        <td class="ApnaBorder"><?php echo number_format($BalanceAmount,2); $TotBalance+=$BalanceAmount;?></td>

        <td class="ApnaBorder Chnage-bg"><?php if($NoOfDays <= 0){echo number_format($BalanceAmount,2); $TotNotYet+=$BalanceAmount; $no=$BalanceAmount;};?></td>

        <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(1,30))){ echo number_format($BalanceAmount,2); $Tot_1_30+=$BalanceAmount; $one=$BalanceAmount;} ?></td>
        <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(31,60))){ echo number_format($BalanceAmount,2); $Tot_31_60+=$BalanceAmount; $two=$BalanceAmount;} ?></td>
        <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(61,90))){ echo number_format($BalanceAmount,2); $Tot_61_90+=$BalanceAmount; $three=$BalanceAmount;} ?></td>
        <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(91,180))){ echo number_format($BalanceAmount,2); $Tot_91_180+=$BalanceAmount; $four=$BalanceAmount;} ?></td>
        <td class="ApnaBorder Chnage-bg"><?php if ( in_array($NoOfDays, range(181,10000))){ echo number_format($BalanceAmount,2); $Tot_180_1000+=$BalanceAmount; $five=$BalanceAmount;} ?></td>
        <td class="ApnaBorder Chnage-bg"><?php  echo number_format($BalanceAmount,2); $TotOverAll+=$BalanceAmount;?></td>
        <td style="display: none" class="ApnaBorder Chnage-bg"><?php echo str_replace("-","",$NoOfDays);?></td>

        <?php  ?>
    </tr>
    <?php
    endif;
    endforeach;?>
    <tr class="text-center ApnaBorder">
        <td colspan="4" class="ApnaBorder" style="font-weight: bold;">Sub Total :</td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotInvoiceAmount,2); $TotInvoiceAmountEnd+=$TotInvoiceAmount;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotReturnAmount,2); $TotReturnAmountEnd+=$TotReturnAmount;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotPaidAmount,2);$TotPaidAmountEnd+=$TotPaidAmount;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php   $diff=ReuseableCode::get_amount($Sfil->acc_id);

            echo number_format($TotBalance,2); $TotBalanceEnd+=$TotBalance;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotNotYet,2);$TotNotYetDueEnd+=$TotNotYet;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_1_30,2); $Tot_1_30End+=$Tot_1_30;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_31_60,2); $Tot_31_60End+=$Tot_31_60;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_61_90,2); $Tot_61_90End+=$Tot_61_90;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_91_180,2); $Tot_91_180End+=$Tot_91_180;?></td>
        <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_180_1000,2); $Tot_180_1000End+=$Tot_180_1000;?></td>
        <td @if($TotOverAll!=$TotBalance) style="background-color: red" @endif  class="ApnaBorder <?php if ($TotOverAll==0): ?>  hidee{{$main_count}}<?php endif ?>" style="font-weight: bold;"><?php echo number_format($TotOverAll,2); $TotOverAllEnd+=$TotOverAll;?></td>
        <input type="hidden" value="{{$TotOverAll}}" class="val" id="{{$main_count}}"/>

    </tr>
    <tr>
        <td colspan="15"><hr style="border-color: black;"></td>
    </tr>
    </tbody>


    <?php
    $VendorCounter++;
    $main_count++; endforeach;?>

</table>
<table class="table table-bordered ApnaBorder table{{$VendorCounter}} GrandTotal" id="export_table_to_excel_<?php echo $VendorCounter; ?>">
    <tr class="text-center ApnaBorder">
        <th class="text-center ApnaBorder" style="width: 7%" colspan="4"></th>
        <th class="text-center ApnaBorder">Total Invoice Amount</th>
        <th class="text-center ApnaBorder">Total Return Amount</th>
        <th class="text-center ApnaBorder">Total Paid Amount</th>
        <th class="text-center ApnaBorder">Total Balance</th>
        <th class="text-center ApnaBorder Chnage-bg">Total Not Yet Due</th>
        <th class="text-center ApnaBorder Chnage-bg">Total (1-30)</th>
        <th class="text-center ApnaBorder Chnage-bg">Total (31-60)</th>
        <th class="text-center ApnaBorder Chnage-bg">Total (61-90)</th>
        <th class="text-center ApnaBorder Chnage-bg">Total (91-180)</th>
        <th class="text-center ApnaBorder Chnage-bg">Total More Than 180 days</th>
        <th class="text-center ApnaBorder Chnage-bg">Total Amount</th>
        {{--<th class="text-center ApnaBorder Chnage-bg">Days Outstanding</th>--}}
    </tr>
<tr class="text-center ApnaBorder">
    <td colspan="4" class="ApnaBorder" style="font-weight: bold;">Grand Total :</td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotInvoiceAmountEnd,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotReturnAmountEnd,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotPaidAmountEnd,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotBalanceEnd,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotNotYetDueEnd,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_1_30End,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_31_60End,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_61_90End,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_91_180End,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($Tot_180_1000End,2)?></td>
    <td class="ApnaBorder" style="font-weight: bold;"><?php echo number_format($TotOverAllEnd,2)?></td>
</tr>
    </table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>



    $( document ).ready(function() {
        $('.val').each(function(i, obj) {
            var value=$(this).val();
            value=parseFloat(value);

            if (value==0)
            {
                var id=obj.id;
              //  $('.table'+id+'').remove();
                $('.table'+id+'').remove();
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
        <?php if($_GET['ReportType'] == 1):?>
        $('.yes').remove();
        <?php else:?>
        <?php endif;?>

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

         