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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background: #ccc;"><h2>Inventory</h2></div>
</div>
<div class="row">
    {{--Top Five Vendor By Total Purchase Start--}}
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
        <div class="table-responsive" >
            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                <thead>
                <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Vendor By Total Purchase Share</strong></th>
                </thead>
                <thead>
                <th class="text-center">Sr No.</th>
                <th class="text-center">Vendor</th>
                <th class="text-center">Revenue</th>
                <th class="text-center">Percent Share</th>
                </thead>
                <tbody>
                <?php
                        $Counter = 1;
                        $TotAmount = 0;
                        $NotInIds = '';
                        $Data = DB::Connection('mysql2')->select('SELECT b.supplier,SUM(a.amount) am  FROM new_purchase_voucher_data a
                                                            INNER JOIN new_purchase_voucher b ON b.id = a.master_id
                                                            WHERE b.status = 1
                                                            AND a.amount > 0
                                                            GROUP BY b.supplier
                                                            order by am DESC
                                                            LIMIT 0,5');
                $OverAllTotal = DB::Connection('mysql2')->selectOne('SELECT SUM(a.amount) all_total  FROM new_purchase_voucher_data a
                                                    INNER JOIN new_purchase_voucher b ON b.id = a.master_id
                                                    WHERE b.status = 1
                                                    AND a.amount > 0');
                foreach($Data as $Fil):
                        $NotInIds .= $Fil->supplier.',';
                ?>
                <tr class="text-center">
                    <td><?php echo $Counter++;?></td>
                    <td><?php echo CommonHelper::get_supplier_name($Fil->supplier);?></td>
                    <td><?php echo number_format($Fil->am,2); $TotAmount+=$Fil->am;?></td>
                    <td><?php echo number_format($Fil->am/$OverAllTotal->all_total*100).'%';?></td>
                </tr>
                <?php endforeach;
                        $NotInIds = rtrim($NotInIds,',');
                $Other = DB::Connection('mysql2')->selectOne('SELECT SUM(a.amount) other  FROM new_purchase_voucher_data a
                                                    INNER JOIN new_purchase_voucher b ON b.id = a.master_id
                                                    WHERE b.status = 1
                                                    AND a.amount > 0
                                                    and b.supplier not in('.$NotInIds.')');
                ?>
                <tr class="text-center">
                    <td>6</td>
                    <td>Other</td>
                    <td><?php echo number_format($Other->other,2);?></td>
                    <td></td>
                </tr>
                <tr class="text-center">
                    <td></td>
                    <td>Total Amount</td>
                    <td>
                        <?php echo number_format($TotAmount+$Other->other,2);?>
                            <input type="hidden" id="TotalPurchaseShare" value="<?php echo $TotAmount+$Other->other?>">
                    </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    {{--Top Five Vendor By Total Purchase End--}}
    {{--Vendor Ageing Start Here--}}


    <?php

    $Clause = '';

    $Tot_1_30End = 0;
    $Tot_31_60End = 0;
    $Tot_61_90End = 0;
    $Tot_91_180End = 0;
    $Tot_180_1000End = 0;
    $TotOverAllEnd = 0;
    $TotNotYetDueEnd = 0;
    $company_data= ReuseableCode::get_account_year_from_to(Session::get('run_company'));
    $from=$company_data[0];
    $to = date('Y-m-d');
    $Clause = '';

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
               and(a.pv_date between "'.$from.'" and "'.$to.'" or grn_id=0)

                and a.supplier="'.$Sfil->id.'"
                group by a.id');
        ?>

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

        $amount=0;
        foreach($vendor_data as $fil):

            $no=0;
            $one=0;
            $two=0;
            $three=0;
            $four=0;
            $five=0;
            $InvoiceAmount = $fil->total;

            $PaidAmount = CommonHelper::PaymentPurchaseAmountCheck_aging($fil->id,$from,$to);
            $return_amount=ReuseableCode::return_amount_by_date($fil->grn_id,2,$from,$to);
            $BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;


            $diffss = strtotime($fil->due_date) - strtotime($fil->pv_date);

            $diffss = abs(round($diffss / 86400));



            $date1_ts = strtotime($fil->pv_date.'+'.$diffss.'day');
            $date2_ts = strtotime($to);
            $diff = $date2_ts - $date1_ts;
            $NoOfDays = round($diff / 86400);
            if($BalanceAmount > 0):
                if($NoOfDays <= 0){$TotNotYet+=$BalanceAmount; };
                if ( in_array($NoOfDays, range(1,30))){$Tot_1_30+=$BalanceAmount; $one=$BalanceAmount;}
                if ( in_array($NoOfDays, range(31,60))){  $Tot_31_60+=$BalanceAmount; $two=$BalanceAmount;}
                if ( in_array($NoOfDays, range(61,90))){  $Tot_61_90+=$BalanceAmount; $three=$BalanceAmount;}
                if ( in_array($NoOfDays, range(91,180))){  $Tot_91_180+=$BalanceAmount; $four=$BalanceAmount;}
                if ( in_array($NoOfDays, range(181,10000))){  $Tot_180_1000+=$BalanceAmount; $five=$BalanceAmount;}
                $TotOverAll+=$BalanceAmount;
                ?>

<?php
            endif;
        endforeach;?>
                            <?php if($TotOverAll > 0):
        $TotNotYetDueEnd+=$TotNotYet;
        $Tot_1_30End+=$Tot_1_30;
        $Tot_31_60End+=$Tot_31_60;
        $Tot_61_90End+=$Tot_61_90;
        $Tot_91_180End+=$Tot_91_180;
        $Tot_180_1000End+=$Tot_180_1000;
        $TotOverAllEnd+=$TotOverAll;

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
                <th class="text-center">Vendor Ageing</th>
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
                    <td><?php echo number_format($TotNotYetDueEnd,2)?></td>

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
    {{--Vendor Ageing End Here--}}
</div>

<div class="row">
    {{--Top Ten Inventory Items By Volume Start--}}
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
        <div class="table-responsive" >
            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                <thead>
                <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Ten Inventory Items by Volume</strong></th>
                </thead>
                <thead>
                <th class="text-center">Sr No.</th>
                <th class="text-center">Vendor</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Percent Share</th>
                </thead>
                <tbody>
                <?php
                $Counter = 1;
                //$StockData = DB::Connection('mysql2')->select('SELECT a.sub_item_id,SUM(a.qty) am  FROM stock a
                  //                                  WHERE a.status = 1
                    //                                AND a.qty > 0
                      //                              GROUP BY a.sub_item_id
                        //                            order by am DESC
                          //                          LIMIT 0,10');

                //foreach($StockData as $Fil):?>
                <tr class="text-center">
                    <td><?php //echo $Counter++;?></td>
                    <td><?php //echo CommonHelper::get_item_name($Fil->sub_item_id);?></td>
                    <td>0</td>
                    <td>%</td>
                </tr>
                <?php //endforeach;?>
                <tr class="text-center">
                    <td></td>
                    <td>Total Amount</td>
                    <td>0</td>
                    <td>100%</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    {{--Top Ten Inventory Items By Volume End--}}
    {{--Top Ten Inventory Items with High Profit Yield Start--}}
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
        <div class="table-responsive" >
            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                <thead>
                <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Ten Inventory Items with High Profit Yield</strong></th>
                </thead>
                <thead>
                <th class="text-center">Sr No.</th>
                <th class="text-center">Vendor</th>
                <th class="text-center">Revenue</th>
                <th class="text-center">Percent Share</th>
                </thead>
                <tbody>
                <?php for ($x = 1; $x <= 10; $x++):?>
                <tr class="text-center">
                    <td><?php echo $x;?></td>
                    <td></td>
                    <td>0</td>
                    <td>%</td>
                </tr>
                <?php endfor;?>
                <tr class="text-center">
                    <td></td>
                    <td>Total Amount</td>
                    <td>0</td>
                    <td>100%</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    {{--Top Ten Inventory Items with High Profit Yield End--}}
</div>
</div>
</div>
<script src="">
    function get_percentange(RowAmount)
    {
        var TotalValue = $('#TotalPurchaseShare').val();
        var Percentage = parseFloat(RowAmount/TotalValue*100).toFixed(2);
        return Percentage;
    }

</script>
@endsection
