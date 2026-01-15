<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\FinanceHelper;
?>
@extends('layouts.default')

@section('content')

<div class="well_N">
<div class="dp_sdw">    

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background: #ccc;"  ><h2>Sales</h2></div>
</div>
<div class="row">
    {{--Top Five Customer Revenue Start--}}
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
        <div class="table-responsive" >
            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                <thead>
                <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Customers By Revenue Share</strong></th>
                </thead>
                <thead>
                <th class="text-center">Sr No.</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Revenue</th>
                <th class="text-center">Percent Share</th>
                </thead>
                <tbody>
                <?php
                $Dta = DB::Connection('mysql2')->select('SELECT b.buyers_id,SUM(a.amount) am  FROM `sales_tax_invoice_data` a
INNER JOIN sales_tax_invoice b ON b.id = a.master_id
WHERE b.status = 1
AND a.amount > 0
GROUP BY b.buyers_id
order by am DESC
LIMIT 0,5');
                $Counter = 1;
                $TotAmount = 0;
                foreach($Dta as $ff):?>
                <tr class="text-center">
                    <td><?php echo $Counter++;?></td>
                    <td><?php echo CommonHelper::byers_name($ff->buyers_id)->name; ?></td>
                    <td><?php echo number_format($ff->am); $TotAmount +=$ff->am;?></td>
                    <td>%</td>
                </tr>
                <?php endforeach;?>
                <tr class="text-center">
                    <td></td>
                    <td>Total Amount</td>
                    <td><?php echo number_format($TotAmount,2);?></td>
                    <td>100%</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    {{--Top Five Customer Revenue Ent--}}
    {{--Top Five Customer Profit Margin Start--}}
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
        <div class="table-responsive" >
            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                <thead>
                <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Customers By Profit</strong></th>
                </thead>
                <thead>
                <th class="text-center">Sr No.</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Profit</th>
                <th class="text-center">Percent Share</th>
                </thead>
                <tbody>
                <?php
                $custome=[];
                $data=DB::Connection('mysql2')->select('select a.net,b.name,a.cost,a.gross,b.id as customer_id from si_criteria as a
                                     left join
                                     customers b
                                     on
                                     a.buyers=b.id
                                     order by net desc limit 5');

                $total=DB::Connection('mysql2')->selectOne('select sum(net)net from si_criteria')->net;

                $count=1;
                $total_amount=0;
                ?>
                @foreach($data as $row)
                    <tr class="text-center">
                        <td><?php echo $count++;?></td>
                        <td>{{$row->name}}</td>
                        <td>{{number_format($row->net,2)}}</td>
                        <td title="{{number_format($total,2)}}">{{number_format(($row->net/$total)*100).'%'}}</td>

                        <?php
                        $customer[]=$row->customer_id;
                        $total_amount+=$row->net;
                        ?>
                        @endforeach
                    </tr>
                    <?php
                    $customer=implode(',',$customer);
                    $others=DB::Connection('mysql2')->selectOne('select sum(net)net from si_criteria where buyers not in  ('.$customer.')')->net; ?>
                    <tr class="text-center">
                        <td>{{$count++}}</td>
                        <td>Others</td>
                        <td>{{number_format($others,2)}}</td>
                        <td></td>

                    </tr>
                    <tr class="text-center">
                        <td colspan="2">Total Amount</td>

                        <?php $net=$total_amount+$others; ?>
                        <td>{{number_format($net,2)}}</td>

                        <td></td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    {{--Top Five Customer Profit Margin Ent--}}
</div>
<div class="row">
    {{--Customer Ageing Start Here--}}
    <?php


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
    $as_on=date('Y-m-d');

    $Clause = '';
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
    <?php
    foreach($Cust as $Cfil):
        $vendor_data=DB::Connection('mysql2')->select('select a.id,a.model_terms_of_payment,a.due_date,a.gi_no,a.gi_date,(sum(b.amount)+a.sales_tax)total
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
        ?>

                            <?php
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
            $PaidAmount = CommonHelper::bearkup_receievd($fil->id,$from,$as_on);
            $return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice_by_date($fil->id,$from,$as_on);
            $BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;
            $date1_ts = strtotime($fil->gi_date.'+'.$fil->model_terms_of_payment.'day');
            $date2_ts = strtotime($as_on);
            $diff = $date2_ts - $date1_ts;// - $date1_ts;
            $NoOfDays = round($diff / 86400);
//$NoOfDays = str_replace("-","",$NoOfDays);
            $MultiRvNO = DB::Connection('mysql2')->select('select rv_no from brige_table_sales_receipt where si_id = '.$fil->id.' group by rv_no ');

            ?>
<?php
            if ($BalanceAmount>0):
                if($NoOfDays <= 0){$total_not_yet_due+=$BalanceAmount;}
                if ( in_array($NoOfDays, range(1,30))){$Tot_1_30+=$BalanceAmount;}
                if ( in_array($NoOfDays, range(31,60))){ $Tot_31_60+=$BalanceAmount;}
                if ( in_array($NoOfDays, range(61,90))){ $Tot_61_90+=$BalanceAmount;}
                if ( in_array($NoOfDays, range(91,180))){$Tot_91_180+=$BalanceAmount;}
                if ( in_array($NoOfDays, range(181,10000))){$Tot_180_1000+=$BalanceAmount;}
                $TotOverAll+=$BalanceAmount;
                ?>

<?php  endif;
        endforeach;
        if($TotOverAll > 0):
            ?>


                                <?php $total_not_yet_due_end+=$total_not_yet_due;?>
                                <?php $Tot_1_30End+=$Tot_1_30;?>
                                <?php $Tot_31_60End+=$Tot_31_60;?>
                                <?php $Tot_61_90End+=$Tot_61_90;?>
                                <?php  $Tot_91_180End+=$Tot_91_180;?>
                                <?php $Tot_180_1000End+=$Tot_180_1000;?>
                                <?php  $TotOverAllEnd+=$TotOverAll;?>



                            <?php
        endif;
    endforeach;?>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
        <div class="table-responsive" >
            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                {{--<thead>--}}
                {{--<th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Customers By Profit Margin</strong></th>--}}
                {{--</thead>--}}
                <thead>
                <th class="text-center">Sr No.</th>
                <th class="text-center">Customer Ageing</th>
                <th class="text-center">Amount</th>

                </thead>
                <tbody>

                <tr class="text-center">
                    <td>1</td>
                    <td>1 To 30</td>
                    <td><?php echo number_format($Tot_1_30End,2);?></td>

                </tr>
                <tr class="text-center">
                    <td>2</td>
                    <td>31 To 60</td>
                    <td><?php echo number_format($Tot_31_60End,2);?></td>

                </tr>
                <tr class="text-center">
                    <td>3</td>
                    <td>61 To 90</td>
                    <td><?php echo number_format($Tot_61_90End,2);?></td>

                </tr>
                <tr class="text-center">
                    <td>4</td>
                    <td>91 To 180</td>
                    <td><?php echo number_format($Tot_91_180End,2);?></td>

                </tr>
                <tr class="text-center">
                    <td>5</td>
                    <td>More Than 180</td>
                    <td><?php echo number_format($Tot_180_1000End,2);?></td>

                </tr>
                <tr class="text-center">
                    <td>6</td>
                    <td>Not Yet Due	</td>
                    <td><?php echo number_format($total_not_yet_due_end,2)?></td>

                </tr>

                <tr class="text-center">
                    <td></td>
                    <td>Total Amount</td>
                    <td><?php echo number_format($TotOverAllEnd,2)?></td>

                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
    
@endsection