<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

$m = Session::get('run_company');
$clause='';

$net_profit_array = [];
$owner_equity_array = [];

// indentation helper for account-name column (kept in sync with the amount-column tabs below)
function bs_indent($level) {
    $tabs = max(0, $level - 2);
    return str_repeat('&emsp;', $tabs);
}
?>
<style>
   table.Balance_Sheet{border:1px solid #dfe2f5 !important;border-collapse:collapse !important;width:100%;background:#fff;}
table.Balance_Sheet td{border:none !important;border-bottom:1px solid #edeef7 !important;padding:8px 12px;font-size:13px;color:#2b2f4a;}
.bs-card{border:1px solid #e6e8f5;border-radius:12px;overflow:hidden;margin-bottom:24px;box-shadow:0 1px 3px rgba(30,30,80,.08);}
.bs-card table.Balance_Sheet{border:none !important;}
.bs-banner{display:flex;align-items:center;gap:10px;padding:16px 20px;color:#fff;font-size:17px;font-weight:500;letter-spacing:.01em;}
.bs-banner .bs-icon{width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center;font-size:16px;}
.bs-banner-assets{background:linear-gradient(135deg,#24265f,#4a56c9);}
.bs-banner-equity{background:linear-gradient(135deg,#7c3aed,#a855f7);}
.bs-banner-liabilities{background:linear-gradient(135deg,#173ca7,#173ca7);}
.bs-card thead th{background:#eef0fb;color:#241e6b;font-size:13px;font-weight:500;text-transform:uppercase;letter-spacing:.04em;padding:12px 14px;border-bottom:2px solid #241e6b !important;}
.bs-card tbody tr:nth-child(odd){background:#f8f9fe;}
.bs-card tbody tr:hover{background:#eef0ff;}
.bs-card tbody tr{border-left:4px solid transparent;}
.bs-card tbody tr.bs-level-1,.bs-card tbody tr.bs-level-2{border-left-color:#241e6b;}
.bs-card tbody tr.bs-level-3,.bs-card tbody tr.bs-level-4,.bs-card tbody tr.bs-level-5,.bs-card tbody tr.bs-level-6,.bs-card tbody tr.bs-level-7{border-left-color:#f59e0b;}
.bs-name-cell{cursor:pointer;}
.bs-name-cell:hover{color:#3452d1;text-decoration:underline;}
.bs-level-1 .bs-name-cell{font-size:15px;font-weight:500;color:#1f2440;}
.bs-level-2 .bs-name-cell{font-weight:500;color:#1f2440;}
.bs-level-3 .bs-name-cell{font-weight:500;color:#33375a;}
.bs-level-4 .bs-name-cell,.bs-level-5 .bs-name-cell,.bs-level-6 .bs-name-cell,.bs-level-7 .bs-name-cell{color:#3452d1;font-weight:400;}
.bs-level-1 .bs-amt-cell{font-size:15px;font-weight:500;color:#1f2440;}
.bs-level-2 .bs-amt-cell,.bs-level-3 .bs-amt-cell{font-weight:500;color:#1f2440;}
.bs-card tbody tr.bs-type-highlight{background:#eaf2ff !important;}
.bs-card tbody tr.bs-type-muted{background:#f2f3f6 !important;}
tr.bs-subtotal{background:#eef0ff !important;font-weight:500;font-size:14px;border-left:4px solid #6d63e0 !important;}
tr.bs-subtotal td{color:#241e6b;}
tr.bs-grandtotal{background:#eef0ff !important;font-weight:500;font-size:14.5px;border-left:4px solid #241e6b !important;}
tr.bs-grandtotal td{color:#1f2440 !important;border-top:1px solid #d7dbf5 !important;}
.table-responsive{height:auto !important;}
</style>
<span id="MultiExport">
<h2 class="text-center topp"></h2>


<div class="row" id="data">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="bs-card">
        <div class="bs-banner bs-banner-assets"><span class="bs-icon">&#127963;</span> Assets</div>
        <div class="table-responsive">

            <table id="table1" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                    <th class="text-center">Assets</th>
                    <th class="text-center" style="text-align: right !important;"><?php echo  $financial_year[0].' - '.$financial_year[1] ?></th>
                </thead>
                <tbody>

                @foreach($accounts1 as $key => $y)
                    <?php

                        $counter = 0;
                        $flag = true;
                        $array = explode('-',$y->code);
                        $level = count($array);
                        $nature = $array[0];
                    ?>
                    <tr title="{{$y->id}}" id="{{$y->id}}" class="bs-level-<?php echo $level; ?>">

                        <td class="bs-name-cell" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $y->code?>')">
                            <?php echo bs_indent($level); ?><?php echo $level == 1 ? strtoupper($y->name) : $y->name; ?>
                        </td>

                        <?php
                            $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',1,0);
                            if($amount !=0 )  $counter = 1 ;
                        ?>

                        <td class="text-right bs-amt-cell" style="text-align: right !important;">
                            <?php echo bs_indent($level); ?><?php echo number_format($amount,2)?>
                        </td>

                    </tr>
                    @if($counter == 0)
                    <script>
                        removeRaw({{ $y->id }})
                    </script>
                    @endif
                @endforeach

                <tr class="bs-grandtotal">
                    <td>Total Assets</td>

                    <?php
                        $total_assets = $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,'1','1',1,0);
                    ?>

                    <td class="text-right" style="text-align: right !important;"><?php echo number_format($total_assets,2) ?></td>

                </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="bs-card">
        <div class="bs-banner bs-banner-equity"><span class="bs-icon">&#9878;</span> Owner's Equity</div>
        <div class="table-responsive">

            <table id="table2" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                    <th colspan="2" class="text-center">Owners Equity</th>
                </thead>
                <tbody>

                @foreach($accounts3 as $key => $y)

                    <?php
                    $counter = 0;
                    $flag = true;

                    $array = explode('-',$y->code);
                    $level = count($array);
                    $nature = $array[0];
                    ?>

                    <tr id="{{$y->id}}" class="bs-level-<?php echo $level; ?>">

                        <td class="bs-name-cell" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $y->code?>')">
                            <?php echo bs_indent($level); ?><?php echo $level == 1 ? strtoupper($y->name) : $y->name; ?>
                        </td>

                        <?php
                            $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',1,0);
                            if($amount !=0 )  $counter = 1 ;
                        ?>

                        <td class="text-right bs-amt-cell" style="text-align: right !important;">
                            <?php echo bs_indent($level); ?><?php echo number_format($amount,2)?>
                        </td>
                    </tr>
                    @if($counter == 0)
                    <script>
                        removeRaw({{ $y->id }})
                    </script>
                    @endif

                @endforeach

                <tr class="bs-subtotal">
                    <td>Net Income</td>
                    <?php
                        $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',1,0);
                        if($amount !=0 )  $counter = 1 ;

                        $owner_equity = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,3,'1',0,1);

                        $revenue = $revenue=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,5,'1',1,0);

                        $cogs_dr=DB::Connection('mysql2')->table('transactions')
                                ->where('status',1)
                                ->where('debit_credit',1)
                                ->where('acc_id',768)
                                ->where('voucher_type','!=',5)
                                ->whereBetween('v_date',[$from_date,$to_date])
                                ->where('opening_bal',0)
                                ->sum('amount');

                        $cogs_cr=DB::Connection('mysql2')->table('transactions')
                                ->where('status',1)
                                ->where('debit_credit',0)
                                ->where('acc_id',768)
                                ->where('voucher_type','!=',5)
                                ->whereBetween('v_date',[$from_date,$to_date])
                                ->where('opening_bal',0)
                                ->sum('amount');

                        $cogs=$cogs_dr-$cogs_cr;

                        if ($revenue<0):
                            $revenue=$revenue*-1;
                        endif;
                        $revenue=$revenue-$cogs;
                        $expense=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,4,'1',1,0);
                        if ($expense<0):
                            $expense=$expense;
                        endif;
                        $net_profit=$revenue-$expense;
                        $net_profit = $net_profit;
                        $owner_equity = $owner_equity;
                    ?>

                    <td class="text-right" style="text-align: right !important;"><?php echo number_format($net_profit,2) ?></td>

                </tr>


                <tr class="bs-grandtotal">
                    <td>Total Owner's Equity</td>

                    <td class="text-right" style="text-align: right !important;">
                        <?php echo number_format($net_profit + $owner_equity ,2) ?>
                    </td>

                    <?php $owner_equity= $owner_equity+$net_profit; ?>
                </tr>

                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="bs-card">
        <div class="bs-banner bs-banner-liabilities"><span class="bs-icon">&#128179;</span> Liabilities</div>
        <div class="table-responsive">

            <table id="table3" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                <th colspan="2" class="text-center">Liabilities</th>
                </thead>
                <tbody>

                @foreach($accounts2 as $key => $y)

                    <?php
                    $counter = 0;

                    $array = explode('-',$y->code);
                    $level = count($array);
                    $nature = $array[0];

                    $amount=CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',0,1);
                    ?>

                    <tr id='{{$y->id}}' title="{{$y->id}}" class="bs-level-<?php echo $level; ?> <?php if($y->type==1) echo 'bs-type-highlight'; if($y->type==4) echo 'bs-type-muted'; ?>">
                        <td class="bs-name-cell" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $y->code?>')">
                            <?php echo bs_indent($level); ?><?php echo $level == 1 ? strtoupper($y->name) : $y->name; ?>
                        </td>

                        <?php
                            $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',0,1);
                            if($amount !=0 )  $counter = 1 ;
                        ?>

                        <td class="text-right bs-amt-cell" style="text-align: right !important;">
                            <?php echo bs_indent($level); ?><?php echo  number_format($amount,2)?>
                        </td>
                    </tr>
                    @if($counter == 0)
                    <script>
                        removeRaw({{ $y->id }})
                    </script>
                    @endif
                @endforeach

                <tr class="bs-grandtotal">
                    <td>Total Liabilities</td>

                    <?php
                        $liblaty = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,'2','1',0,1);
                    ?>
                    <td class="text-right" style="text-align: right !important;"> <?php echo number_format($liblaty,2) ?></td>

                </tr>

                <tr class="bs-grandtotal">
                    <td>Liabilities + Owner's Equity</td>
                    <td class="text-right" style="text-align: right !important;"> <?php echo number_format($net_profit + $owner_equity + $liblaty,2) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

</span>

<script>
    function get_detai(url,from,to,code,name)
    {
        showDetailModelOneParamerter(url,from+','+to+','+code+','+name);
    }
</script>