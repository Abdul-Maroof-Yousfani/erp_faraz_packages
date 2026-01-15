<?php
//$Array = $_GET['id'];
//$Array = explode(',',$Array);
$FromDate = $_GET['from'];
$ToDate = $_GET['to'];
$AccId = $_GET['acc_code'];
$code=$AccId.'-%';
$total_sales_invoice = 0;
$total_debit_note = 0;

$array = explode('-',$AccId);
$level = count($array);

use App\Helpers\CommonHelper;

$m=Session::get('run_company');


?>
@extends('layouts.default')
@section('content')
<style>
    .hov:hover {
        background-color: yellow;

    }
    p
    {
        font-weight: bold;
        font-size: large;
    }
</style>

{{--<button type="button" id="print2" class="btn btn-primary btn-xs printt-btn">&nbsp; Print &nbsp;</button>--}}
<a id="dlink" style=""></a>

<div id="" class="well_N">
    <input type="button" class="btn btn-sm btn-warning" onclick="tablesToExcel(array1, 'Sheet1', 'myfile.xls')" value="Export to Excel"/>
    <?php
    $all_debit=0;
    $all_credit=0;

    $data1=  DB::Connection('mysql2')->select('select b.id,b.name,b.code from accounts b
                                      inner join transactions a
                                      ON
                                      a.acc_id=b.id
                                      where a.amount>0
                                      and substring_index(b.code,"-","'.$level.'") = "'.$AccId.'"

                                      and a.status=1
                                       and b.status=1
                                       group by b.id');


    $MainCount =  count($data1);
    $LedgerCount =1;
    $total_cr_note=0;
    $total_purchase_invoice=0;
    ?>

    @foreach($data1 as $row1)
        <?php
        $code=$row1->code;
        $level=explode('-',$code);
        $level=$level[0];
        ?>

        <table class="table table-bordered sf-table-th sf-table-list" id="export_table_to_excel_<?php echo $LedgerCount; ?>">
            <?php
            CommonHelper::companyDatabaseConnection($m);
            $quarter = DB::select("SELECT * from  transactions
  											WHERE acc_id = ".$row1->id." and opening_bal=0  AND status=1 AND v_date
  											 between '".$FromDate."' and '".$ToDate."'  ORDER BY v_date");

            CommonHelper::reconnectMasterDatabase();

            ?>
            <thead>
            <tr>
                <td colspan="7" style="background-color:#ffffff; color:black;">
                    <h3 style="text-align: center">{{$row1->code.' '.$row1->name}}</h3>
                </td>
            </tr>

            </thead>
            <thead>



            <tr>
                <td colspan="9" style="background-color:#ffffff; color:black;">

                </td>
            </tr>
            <tr>
                <th style="width: 100px" class="text-center">Voucher No</th>
                <th style="width: 120px" class="text-center">Date</th>
                <th style="width: 150px" class="text-center">Voucher Type</th>
                <th style="width: 120px" class="text-center">Cheque No</th>
                <th class="text-center">Description</th>
                <th class="text-center" style="width:100px;">Dr</th>
                <th class="text-center" style="width:100px;">Cr.</th>
                <th class="text-center" style="width:100px;">Balance</th>
            </tr>
            </thead>
            <tbody id="<?php // echo $member_id; ?>">
            <?php
            $acc_code=CommonHelper::get_single_row('accounts','id',$row1->id)->code;
            $amount=CommonHelper::get_opening_ball($FromDate,$ToDate,$row1->id,$m,$code);

            $level=explode('-',$acc_code)      ;
            $level=$level[0];


            $total_debit=0;
            $total_credit=0;
            $balance=0;

            ?>
            <tr>
                <td></td>
                <td></td>
                <td class="text-left" colspan="3">Opening Balance</td>
                <td class="text-right"><?php if ($amount>=0): echo number_format($amount,2); $balance=$amount; $all_debit+=$amount;  endif; ?></td>
                <td class="text-right"><?php if ($amount < 0): $balance=$amount;     $amount=$amount*-1;  echo number_format($amount,2);  $all_credit+=$amount; endif; ?></td>
                <td class="text-right">

                    <?php
                    if ($level==2 || $level==3 || $level==4):
                    if ($balance<0):
                    $balance=$balance*-1;
                    else:
                    $balance=$balance*-1;
                    endif;
                    endif;
                    ?>
                    <?php if ($balance>=0): echo number_format($balance,2); else:  echo '('.number_format($balance*-1,2).')';  endif;  ?>
                </td>
            </tr>
            <?php



            foreach($quarter as $trow):

            $debit=0;
            $credit=0;

            $type='';
            $detail='';
            $PageTitle='';
            $VoucherId = '';
            $page_typ='';
            if ($trow->voucher_type==1):
            $detail='fdc/viewJournalVoucherDetail';
            $PageTitle = 'View Journal Voucher Detail';
            endif;


             if ($trow->voucher_type==8):
            $page_typ='Sales Tax Invoice';
             endif;


            if ($trow->voucher_type==9):
            $page_typ='Credit Note';
                    $total_cr_note+=$trow->amount;
            endif;
        $category='';
            if ($trow->voucher_type==4):
            $detail='fdc/viewPurchaseVoucherDetail';
            $PageTitle = 'View Purchase Voucher Detail';
            $page_typ='Purchase Invoice';
            $type='Purchase Invoice';

            $work_order_id=  DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('pv_no',$trow->voucher_no)
            ->where('work_order_id','!=',0)->count();

                    if ($work_order_id >0):
                    $category='Againts Work Order';
                    endif;

                    if ($trow->debit_credit==1):
            $total_purchase_invoice+=$trow->amount;
                    endif;
            endif;


            $cheque_no='';
            $cheque_date='';
            if ($trow->voucher_type==3):
            $VNo = substr($trow->voucher_no, 0, 3);
            if($VNo == 'crv')
            {
            $detail='fdc/viewCashRvDetailNew';
            }
            else
            {
            $detail='fdc/viewBankRvDetailNew';
            }

            $PageTitle = 'View Receipt Voucher Detail';
            CommonHelper::companyDatabaseConnection($m);

            $cheque_data=DB::table('rvs')->where('rv_no',$trow->voucher_no)->first();

            if (isset($cheque_data->cheque_no)):
            $cheque_no=$cheque_data->cheque_no;
            else:
            $cheque_no='';
            endif;
            $cheque_date=$cheque_date;
            CommonHelper::reconnectMasterDatabase();
            endif;


            if ($trow->voucher_type==6  || $trow->voucher_type==8):
            $detail='sdc/viewInvoiceDetail';
            $PageTitle = 'Invoice';
            $type='Sales Tax Invoice';
            $total_sales_invoice+=$trow->amount;

            endif;
            if ($trow->voucher_type==5):
            $detail='pdc/viewPurchaseReturnDetail';
            $page_typ='Debit Not';~
            $total_debit_note+=$trow->amount;

            $PageTitle = 'Purchase Return';
            $type='Purchase Return';

            endif;

            if ($trow->voucher_type==2):
            $detail='fdc/viewBankPaymentVoucherDetailInDetail';
            $PageTitle = 'View Payement Voucher Detail';
            CommonHelper::companyDatabaseConnection($m);

            $cheque_data=DB::table('new_pv')->where('pv_no',$trow->voucher_no)->first();
            $cheque_no=$cheque_data->cheque_no;
            $cheque_date=$cheque_data->cheque_date;
            CommonHelper::reconnectMasterDatabase();
            endif;
            ?>

            <tr title="<?php echo $trow->voucher_type ?>"  class="hov" style="">
                <td><?php echo strtoupper($trow->voucher_no) ?></td>
                <td class="text-center" style="width: 180px;"> <a onclick="showDetailModelOneParamerter('<?php echo $detail?>','<?php echo 'other'.','.$trow->voucher_no;?>','<?php echo $PageTitle?>','<?php echo $m?>','')" class="btn btn-xs btn-success"><?php echo  date_format(date_create($trow->v_date), 'd-M-Y'); ?></a></td>
                <td>{{$page_typ}}<br>{{$category}}</td>

                <td class="text-left"><?php echo $cheque_no.'</br>';if ($cheque_date!=''):echo date_format(date_create($cheque_date), 'd-m-Y');endif; ?></td>
                <td class="text-left" style="text-align:left !important"><?php echo $trow->particulars; ?></td>
                <td class="text-right"><?php if($trow->debit_credit==1)
                    { $debit=$trow->amount; echo number_format($trow->amount,2);
                    $total_debit+=$trow->amount; $all_debit+=$trow->amount;} ?></td>
                <td class="text-right"><?php if($trow->debit_credit==0){ $credit=$trow->amount; echo number_format($trow->amount,2); $total_credit+=$trow->amount; $all_credit+=$trow->amount;} ?></td>
                <?php



                ?>
                <td class="text-right"> <?php
                    if ($level==2 || $level==3):
                    $balance=$credit-$debit+$balance;
                    else:
                    $balance=$debit-$credit+$balance;
                    endif;

                    if ($balance>=0):
                    echo number_format($balance,2);

                    else:
                    echo '('.number_format($balance*-1,2).')';
                    endif;
                    ?></td>

            </tr>

            <?php endforeach; ?>
            <tr>
                <td class="text-center" colspan="5"><b style="font-size: large;">TOTAL</b></td>
                <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_debit,2) ?></b></td>
                <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_credit,2) ?></b></td>
                <td  class="text-center" colspan="1"><b style="font-size: large;color: #ff9999"><?php // echo  number_format($total_debit-$total_credit) ?></b></td>

            </tr>

            </tbody>
        </table>

        <?php $LedgerCount++;?>
    @endforeach

    <p>Total Debit = {{number_format($all_debit,2)}}</p>
    <p>Total Credit = {{number_format($all_credit,2)}}</p>
    @if($level==2 || $level==3 || $level==4)

        <?php $total=$all_credit-$all_debit;
        if ($total<0):
        $total=$total*-1;
        $total=number_format($total,2);
        $total='('.$total.')';
        else:
        $total=number_format($total,2);
        endif;

        ?>
        <p>Diffrence = {{$total}}</p>
    @else
        <?php $total=$all_debit-$all_credit;
        if ($total<0):
        $total=$total*-1;
        $total=number_format($total,2);
        $total='('.$total.')';
        else:
        $total=number_format($total,2);
        endif;

        ?>
        <p>Diffrence = {{$total}}</p>
    @endif

    Total Sales Tax Invoice {{number_format($total_sales_invoice,2)}}<br>
    Total Debit Note  {{number_format($total_debit_note,2)}}<br>
    Total Purchase Invoice  {{number_format($total_purchase_invoice,2)}}<br>
    Total Cr Note {{number_format($total_cr_note,2)}}
</div>
<script>
    $(document).ready(function(e) {
        $('#print2').click(function(){
            $("div").removeClass("table-responsive");
            $("div").removeClass("well");
            $("a").removeAttr("href");
            //$("a.link_hide").contents().unwrap();
            var content = $("#content").html();
            document.body.innerHTML = content;
            //var content = document.getElementById('header').innerHTML;
            //var content2 = document.getElementById('content').innerHTML;
            window.print();
//            location.reload();
        });
    });

    //table to excel (multiple table)
    var array1 = new Array();
    var n = '<?php echo $MainCount?>'; //Total table

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
    $( document ).ready(function() {
        $('.val').each(function(i, obj) {
            var value=$(this).val();
            value=parseFloat(value);

            if (value==0)
            {
                var id=obj.id;
                $('.table'+id+'').remove();
            }


        });
    });
</script>
@endsection
