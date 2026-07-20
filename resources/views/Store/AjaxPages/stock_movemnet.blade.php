<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
?>
<style>
#EmpExitInterviewList.sf-report-print-table{font-family:'Inter',sans-serif;border:none !important;border-collapse:separate !important;border-spacing:0 !important;width:100%;font-size:13px;table-layout:auto;}
/* header info block (company / title / date range / printed on) — forced centered,high specificity to beat global th resets */
#EmpExitInterviewList.sf-report-print-table thead tr th.company-name-row{text-align:center !important;background:linear-gradient(150deg,#F7F9FD,#F0F1FA) !important;border:none !important;padding:18px 0 8px !important;font-weight:800 !important;font-size:18px !important;color:#1B2333 !important;}
#EmpExitInterviewList.sf-report-print-table thead tr th.report-title-row{text-align:center !important;background:linear-gradient(150deg,#F7F9FD,#F0F1FA) !important;border:none !important;padding:4px 0 8px !important;font-weight:700 !important;font-size:15px !important;color:#4A5268 !important;}
#EmpExitInterviewList.sf-report-print-table thead tr th.report-sub-row{text-align:center !important;background:linear-gradient(150deg,#F7F9FD,#F0F1FA) !important;border:none !important;padding:4px 0 !important;font-size:13px !important;color:#8A93A6 !important;font-weight:600 !important;}
#EmpExitInterviewList.sf-report-print-table thead tr th.report-printed-row{text-align:right !important;background:linear-gradient(150deg,#F7F9FD,#F0F1FA) !important;border:none !important;padding:4px 20px 18px !important;font-size:12px !important;color:#8A93A6 !important;font-weight:600 !important;}
/* column header row - light theme,matches Trial Balance look. Scoped to the actual last header row via a dedicated class to avoid relying on:last-child specificity fights */
#EmpExitInterviewList.sf-report-print-table thead tr.col-header-row th{background:#F7F8FC !important;color:#0B1F59 !important;font-weight:800 !important;font-size:11px !important;text-transform:uppercase !important;letter-spacing:.4px !important;padding:11px 10px !important;border:none !important;border-bottom:2px solid #173CA7 !important;white-space:nowrap !important;text-align:center !important;}
#EmpExitInterviewList.sf-report-print-table thead tr.col-header-row th:first-child{border-top-left-radius:10px !important;}
#EmpExitInterviewList.sf-report-print-table thead tr.col-header-row th:last-child{border-top-right-radius:10px !important;}
/* body rows - accent left border jaisa Trial Balance */
#EmpExitInterviewList.sf-report-print-table tbody td{padding:9px 10px !important;border:none !important;border-bottom:1px solid #EEF0F7 !important;color:#1B2333 !important;white-space:nowrap;font-weight:500 !important;}
#EmpExitInterviewList.sf-report-print-table tbody td small{color:inherit !important;font-size:13px !important;font-weight:500 !important;}
#EmpExitInterviewList.sf-report-print-table tbody tr{position:relative;}
#EmpExitInterviewList.sf-report-print-table tbody td:first-child{border-left:3px solid #ff8244 !important;font-weight:700 !important;}
#EmpExitInterviewList.sf-report-print-table tbody tr:nth-child(even) td{background:#FAFBFE !important;}
#EmpExitInterviewList.sf-report-print-table tbody tr:hover td{background:#F5F7FD !important;}
/* total row */
#EmpExitInterviewList.sf-report-print-table tbody tr:last-child td{background:linear-gradient(90deg,#EAF1FF 0%,#F1F9F4 100%) !important;font-weight:800 !important;color:#0B1F59 !important;border-top:2px solid #173CA7 !important;border-bottom:none !important;}
#EmpExitInterviewList.sf-report-print-table tbody tr:last-child td:first-child{border-left:3px solid #173CA7 !important;}
#EmpExitInterviewList.sf-report-print-table tbody tr:last-child td small{color:inherit !important;font-weight:800 !important;}
#EmpExitInterviewList.sf-report-print-table thead tr th.company-name-row{font-size:22px !important;font-weight:800 !important;color:var(--erp-navy-2,#0B1F59) !important;margin:0 0 6px !important;}
#EmpExitInterviewList.sf-report-print-table thead tr th.report-title-row{font-size:16px !important;font-weight:700 !important;color:var(--erp-navy-1,#173CA7) !important;margin:0 0 10px !important;}
#EmpExitInterviewList.sf-report-print-table thead tr th.report-sub-row{font-size:14px !important;font-weight:600 !important;color:var(--erp-text,#3b3f5c) !important;margin:0 !important;}
#EmpExitInterviewList.sf-report-print-table thead tr th.report-printed-row{position:absolute !important;top:24px !important;right:32px !important;font-size:11.5px !important;font-weight:600 !important;color:var(--erp-label,#5E5873) !important;margin:0 !important;}
/* column header row - light theme,matches Trial Balance look. Scoped to the actual last header row via a dedicated class to avoid relying on:last-child specificity fights */
/* summary lines below table */
.stock-movement-summary{margin-top:18px;padding:16px 20px;background:#FBFCFE;border:1px solid #EEF0F7;border-radius:12px;font-size:13px;color:#4A5268;}
.stock-movement-summary p{margin:0 0 6px;}
.stock-movement-summary p:last-child{margin-bottom:0;}
.stock-movement-summary strong{color:#1B2333;}

</style>
<div class="table-responsive">
    <table id="EmpExitInterviewList" class="table table-bordered sf-report-print-table tb-table">
        <thead>
        <tr>
            <th colspan="12" class="text-center company-name-row"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></th>
        </tr>
        <tr>
            <th colspan="12" class="text-center report-title-row">Stock Movement Report Inventory</th>
        </tr>
        <tr>
            <th colspan="12" class="text-center report-sub-row">From : {{CommonHelper::changeDateFormat($from).' TO: '.CommonHelper::changeDateFormat($to)}}</th>
        </tr>
        <tr>
            <th colspan="12" class="text-right report-printed-row">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></th>
        </tr>
        <tr class="col-header-row">
        <th class="text-center">S.No</th>
        <th class="text-center">Item</th>
        <th class="text-center">Open. QTY</th>
        <th class="text-center">Open. Amount</th>
        <th class="text-center">IN QTY</th>
        <th class="text-center">IN Amount</th>
        <th class="text-center">OUT QTY</th>
        <th class="text-center">OUT Amount</th>
        <th class="text-center">Open DN</th>
        <th class="text-center">Open Return</th>
        <th class="text-center">IN Stock QTY</th>
        <th class="text-center">IN Stock Amount</th>
        </tr>
        </thead>
        <tbody id="">
            @php
            $count=1;
            $total_open_qty=0;
            $total_open_amount=0;
            $total_in_qty=0;
            $total_in_amount=0;
            $cl_qty=0;
            $cl_amount=0;
            $tot_out_qty=0;
            $tot_out_amount=0;
            $total_incomplete_dn=0;
            $total_incomplete_return=0;
            $total_purchase_side=0;
            @endphp
            <?php
            $cr_no=[];
        $dataa= DB::Connection('mysql2')->select('select a.cr_no from credit_note a
                inner JOIN credit_note_data b
                ON a.id=b.master_id
                inner join delivery_note_data c
                ON b.voucher_data_id=c.id
                inner join delivery_note d
                ON c.master_id=d.id
                where a.status=1
                and b.status=1
                and a.type=1
                and d.sales_tax_invoice=1
                GROUP by a.cr_no');


                    foreach($dataa as $row):

                    $cr_no[]='"'.$row->cr_no.'"';


                    endforeach;
                    $cr_value= implode(',',$cr_no);
                    //implode(',',$data->cr_no);

            ?>
            @foreach($data as $row)

            <?php

            $purchase_side=0;
                            // open process
            $open_data=ReuseableCode::get_opening($from,$to,$accyeafrom,$row->sub_item_id,1);
            $open_qty=$open_data[0];
            $open_amount=$open_data[1];

                    // in process

            $type='1,4,6,11,10';
            $in_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,$type);
            $in_qty=$in_data[0];
            $in_amount=$in_data[1];


                    // out process
            $type='5,2,3,8,9';
            $out_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,$type);
            $out_qty=$out_data[0];
            $out_amount=$out_data[1];

            $remianig_amount=0;
            $remianig_qty=0;
            $remianig_qty=$open_qty+$in_qty-$out_qty;
        $remianig_amount=$open_amount+$in_amount-$out_amount;


            ?>
            <tr title="{{$row->sub_item_id}}">
                <td>{{$count++}}</td>
                <td><small>{{$row->sub_ic}}</small></td>
                <td><small>{{number_format($open_qty,2)}}</small></td>
                <td><small>{{number_format($open_amount,2)}}</small></td>
                <td><small>{{number_format($in_qty,2)}}</small></td>
                <td><small>{{number_format($in_amount,2)}}</small></td>
                <td><small>{{number_format($out_qty,2)}}</small></td>
                <td><small>{{number_format($out_amount,2)}}</small></td>

                <?php


            $incomplete_dn_on_dn=DB::Connection('mysql2')->selectOne('select sum(b.amount)amount from  delivery_note a
            inner join
                stock b
                on
                a.gd_no=b.voucher_no
                where a.status=1
                and b.status=1
                and a.gd_date between "'.$from.'" and "'.$to.'"
                and b.sub_item_id="'.$row->sub_item_id.'"
                and a.sales_tax_invoice=0');


                $incomplete_dn_on_supply_chain=DB::Connection('mysql2')->selectOne('select sum(amount)amount
                    from  transaction_supply_chain
                    where status=1
                    and item_id="'.$row->sub_item_id.'"
                    and voucher_type=3
                    and ref_date between "'.$from.'" and "'.$to.'"
                    and voucher_date not between "'.$from.'" and "'.$to.'"');


                $incomplete_dn=0;
                if (!empty($incomplete_dn_on_dn->amount)):
                    $incomplete_dn_on_dn->amount;
                    $incomplete_dn+=$incomplete_dn_on_dn->amount;

                    endif;

                if (!empty($incomplete_dn_on_supply_chain->amount)):
                    $incomplete_dn+=$incomplete_dn_on_supply_chain->amount;

                endif;


            $incomplete_val=0;
                $incomplete_return=   DB::Connection('mysql2')->selectOne('select sum(c.amount)amount
                from  stock as c
                inner join
                purchase_return as a
                on
                c.voucher_no=a.pr_no
                where a.type=1
                and a.status=1
                and a.pr_date between "'.$from.'" and "'.$to.'"
                and c.sub_item_id="'.$row->sub_item_id.'"');;

                    if (!empty($incomplete_return->amount)):
                        $incomplete_dn+=$incomplete_return->amount;
                        $incomplete_val=$incomplete_return->amount;
                    endif;

                ?>

                <td>@if (!empty($incomplete_dn)) {{number_format($incomplete_dn,2)}}  @php $total_incomplete_dn+=$incomplete_dn; @endphp @endif</td>

                <?php
                    $clause='';
                if ($cr_value!=''):

                $clause='and a.cr_no not in ('.$cr_value.')';
            
                    endif;
                $incomplete_sales_retrun=   DB::Connection('mysql2')->selectOne('select sum(c.amount)amount
                from  stock as c
                inner join
                credit_note a
                on
                c.voucher_no=a.cr_no
                where a.type=1
                '.$clause.'
                and a.status=1
                and a.cr_date between "'.$from.'" and "'.$to.'"
                and c.sub_item_id="'.$row->sub_item_id.'"');;
                ?>


                <td>

                    <?php
                    if (!empty($incomplete_sales_retrun->amount)):
                        $incomplete_sales_retrun=$incomplete_sales_retrun->amount;
                    else:
                        $incomplete_sales_retrun=0;
                        endif;
                    echo number_format($incomplete_val+$incomplete_sales_retrun,2);
                    $total_purchase_side+=$incomplete_val+$incomplete_sales_retrun ?></td>

                <td style="font-weight: bold"><small>{{number_format($remianig_qty,2)}}</small></td>
                <td title="" @if ($remianig_amount<0) style="color:#dc2626;font-weight:bold" @else style="font-weight:bold" @endif><small>{{number_format($remianig_amount,2)}}</small></td>
            </tr>

                <?php
                $total_open_qty+=$open_qty;
                $total_open_amount+=$open_amount;
                $total_in_qty+=$in_qty;
                $total_in_amount+=$in_amount;

                $tot_out_qty+=$out_qty;
                $tot_out_amount+=$out_amount;

                $cl_qty+=$remianig_qty;
                $cl_amount+=$remianig_amount;
                ?>

            @endforeach
            <tr>
                <td colspan="2">Total</td>
                <td colspan="1">{{number_format($total_open_qty,2)}}</td>
                <td colspan="1">{{number_format($total_open_amount,2)}}</td>
                <td colspan="1">{{number_format($total_in_qty,2)}}</td>
                <td colspan="1">{{number_format($total_in_amount,2)}}</td>

                <td colspan="1">{{number_format($tot_out_qty,2)}}</td>
                <td colspan="1">{{number_format($tot_out_amount,2)}}</td>
                <td colspan="1">{{number_format($total_incomplete_dn,2)}}</td>
                <td colspan="1">{{number_format($total_purchase_side,2)}}</td>

                <td colspan="1">{{number_format($cl_qty,2)}}</td>
                <td colspan="1">{{number_format($cl_amount+$total_incomplete_dn-$total_purchase_side,2)}}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="stock-movement-summary">
    <p><strong>Purchase:</strong> {{ReuseableCode::stock_type_amount($from,$to,1)}}</p>
    <p><strong>Purchase Return:</strong> {{ReuseableCode::stock_type_amount($from,$to,2)}}</p>
    <p><strong>Sales Return:</strong> {{ReuseableCode::stock_type_amount($from,$to,6)}}</p>
</div>