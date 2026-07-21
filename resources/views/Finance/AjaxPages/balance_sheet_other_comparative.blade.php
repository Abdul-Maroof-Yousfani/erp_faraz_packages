<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$from_date = Input::get('from_date');
$to_date = Input::get('to_date');
$m = Session::get('run_company');
$clause='';

$net_profit_array = [];
$owner_equity_array = [];
?>
<style>
   /* ---------- Base Reset ---------- */
 .Balance_Sheet,.Balance_Sheet *{box-sizing:border-box;}
table{border:1px solid #CCC !important;border-collapse:collapse !important;}
td{border:none !important;}
/* ---------- Card Wrapper for each table ---------- */
 .report-card{background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(30,41,59,0.06);border:1px solid #eef0f5;margin-bottom:28px;overflow:hidden;}
.report-card-title{display:flex;align-items:center;gap:10px;padding:16px 20px;font-size:15px;font-weight:500;letter-spacing:0.4px;color:#fff;}
.report-card-title.assets{background:linear-gradient(135deg,#2b3a67,#4a5aa8);}
.report-card-title.equity{background:linear-gradient(135deg,#6a3fbd,#9163e0);}
.report-card-title.liabilities{background:linear-gradient(135deg,#d9822b,#f0a94e);}
.table-responsive{overflow-x:auto;scrollbar-width:thin;height:auto !important;}
.table-responsive::-webkit-scrollbar{height:8px;}
.table-responsive::-webkit-scrollbar-thumb{background:#d8dce6;border-radius:8px;}
/* ---------- Table ---------- */
 table.sf-table-list{width:100%;border-collapse:collapse !important;border:none !important;font-family:'Segoe UI',Roboto,sans-serif;font-size:13.5px;font-weight:400;min-width:900px;}
table.sf-table-list thead th{background:#f6f7fb;color:#5b6472;font-weight:500;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;padding:12px 16px;border-bottom:2px solid #e7e9f0 !important;white-space:nowrap;position:sticky;top:0;z-index:2;}
table.sf-table-list thead th:first-child{text-align:left;position:sticky;left:0;z-index:3;background:#f6f7fb;}
table.sf-table-list tbody td{padding:10px 16px;border-bottom:1px solid #f0f1f5 !important;border:none;color:#2e3440;font-weight:400 !important;font-variant-numeric:tabular-nums;white-space:nowrap;}
/* Neutralize the inline <b style="font-weight:bolder"> from the PHP */
 table.sf-table-list tbody td b{font-weight:500 !important;font-size:inherit !important;}
table.sf-table-list tbody td:first-child{position:sticky;left:0;background:#fff;z-index:1;box-shadow:2px 0 4px -2px rgba(0,0,0,0.06);font-weight:400;}
table.sf-table-list tbody tr:hover td{background:#f8f9fd;}
table.sf-table-list tbody tr:hover td:first-child{background:#f2f3fb;}
/* zero / muted values */
 table.sf-table-list td:not(:first-child){color:#a4a9b3;font-weight:400;}
/* Total rows — only these stay clearly bold */
 table.sf-table-list tr[style*="lightblue"] td{background:#eef3ff !important;color:#1c2b4a !important;font-weight:500 !important;border-top:2px solid #d7e2ff !important;border-bottom:2px solid #d7e2ff !important;}
table.sf-table-list tr[style*="lightblue"] td:first-child{background:#e4ecff !important;}
table.sf-table-list tr[style*="lightblue"] td b{font-weight:500 !important;}
table.sf-table-list tr[style*="lightgray"] td{background:#f4f5f7 !important;}
/* Net income row */
 table.sf-table-list tr[style*="bolder;font-size:large"] td{color:#6a3fbd;font-weight:500;}
/* Account name cell clickable */
 table.sf-table-list td[onclick]{cursor:pointer;transition:color 0.15s ease;}
table.sf-table-list td[onclick]:hover{color:#4a5aa8;text-decoration:underline;}
.SpacesCls{display:inline-block;width:14px;}
 .report-header{background:linear-gradient(135deg,#eef1fb,#f7f8fd);border-radius:14px;border:1px solid #e3e7f5;padding:22px 28px 16px 28px;margin-bottom:24px;position:relative;text-align:center;}
.report-header .company-name{font-size:22px;font-weight:500;color:#1c2b4a;margin-bottom:6px;}
.report-header .report-title{font-size:16px;font-weight:500;color:#4a5aa8;margin-bottom:10px;}
.report-header .report-range{font-size:13.5px;color:#3a4256;font-weight:500;}
.report-header .report-range b{color:#1c2b4a;}
.report-header .printed-on{position:absolute;top:18px;right:22px;font-size:12.5px;font-weight:500;color:#6b7280;}

</style>
<span id="MultiExport">
<div class="report-header">
    <div class="printed-on">Printed On: {{ date('F d, Y') }}</div>
    <div class="company-name">{!! CommonHelper::get_company_name($m) !!}</div>
    <div class="report-title">Balance Sheet {{ isset($compareYear) ? '(Compare)' : '' }}</div>
    <div class="report-range">
         <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?>
    </div>
</div>


<div class="row" id="data">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="report-card">
            <div class="report-card-title assets">
                <i class="fa fa-university"></i> Assets
            </div>
            <div class="table-responsive" style="overflow-x: scroll;">

                <table id="table1" class="table table-bordered sf-table-list Balance_Sheet">
                    <thead>
                        <th class="text-center">ASSESTS</th>
                        <?php foreach($monthArray as $fmRow){?>

                            <th class="text-center" style="text-align: right !important;"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10));?></th>

                        <?php }?>

                    </thead>
                    <tbody>

                    
                    @foreach($accounts1 as $key => $y)
                        <?php 
                          
                        ?>
                        <tr title="{{$y->id}}" id="{{$y->id}}">

                            <?php
                                $counter = 0;
                                $flag = true;                
                                $array = explode('-',$y->code);
                                $level = count($array);
                                $nature = $array[0];                           
                            ?>
                            <td  style="cursor: pointer" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $y->code?>')">
                                @if($level == 1)
                                    <b style="font-size: large;font-weight: bolder">{{ strtoupper($y->name)}}</b>
                                @elseif($level == 2)
                                    <b style="font-weight: bolder">   <?php echo  ''. $y->name?></b>
                                @elseif($level == 3)
                                    <b style="font-weight: bolder">    <?php echo  ''. $y->name?> </b>
                                @elseif($level == 4)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @elseif($level == 5)
                                    <?php echo  '<span class="SpacesCls"</span>'. $y->name?>
                                @elseif($level == 6)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @elseif($level == 7)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @endif
                            </td>
                            @php
                                $amount = 0;
                            @endphp
                            @foreach($monthArray as $fmRow)
                                          
                            <?php
                            
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                $from_date = date('Y-m-01', strtotime($filterYear . '-' . $makeMNumber . '-01'));
                                $to_date = date('Y-m-t', strtotime($filterYear . '-' . $makeMNumber . '-01'));    

                                if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                    $amount = 0;
                                }
                                $oldamount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',1,0);
                                
                                $amount += $oldamount;
                                if($amount !=0 )  $counter = 1 ;
                                
                            ?>
                                
                            <td  style="text-align: right !important;" class="text-right">
                                @if($level == 1)
                                    <b style="font-size: large;font-weight: bolder">   <?php echo number_format($amount,2)?> </b>
                                @elseif($level == 2)
                                    <b style="font-weight: bolder">     &emsp;<?php echo number_format($amount,2)?></b>
                                @elseif($level == 3)
                                    <b style="font-weight: bolder">      &emsp;&emsp;<?php echo number_format($amount,2)?></b>
                                    @elseif($level == 4)
                                    &emsp;&emsp;&emsp;<?php echo number_format($amount,2)?>
                                    @elseif($level == 5)
                                    &emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount,2)?>
                                    @elseif($level == 6)
                                    &emsp;&emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount,2)?>
                                    @elseif($level == 7)
                                    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount,2)?>
                                @endif
                            </td>

                            @endforeach
                        </tr>
                        @if($counter == 0)
                        <script>
                            removeRaw({{ $y->id }})
                        </script>

                        @endif
                    @endforeach

                    
                    <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                        <td>Total Assets</td>
                        @php
                            $total_assets = 0;
                        @endphp
                        @foreach($monthArray as $fmRow)
                                          
                        <?php
                            
                            $makeMNumber = $fmRow;
                            if($fmRow < 10){
                                $makeMNumber = '0'.$fmRow;
                            }
                            $from_date = date('Y-m-01', strtotime($filterYear . '-' . $makeMNumber . '-01'));
                            $to_date = date('Y-m-t', strtotime($filterYear . '-' . $makeMNumber . '-01'));    

                            if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                $total_assets = 0;
                            }

                            $oldamount = $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,'1','1',1,0);

                            $total_assets += $oldamount;
                        ?>

                            <td  style="text-align: right !important;" class="text-right"> <?php echo number_format($total_assets,2) ?></td>
                        @endforeach

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="report-card">
            <div class="report-card-title equity">
                <i class="fa fa-balance-scale"></i> Owner's Equity
            </div>
            <div class="table-responsive" style="overflow-x: scroll;">

                <table id="table2" class="table table-bordered sf-table-list Balance_Sheet">
                    <thead>
                        <th class="text-center">OWNERS EQUITY</th>
                        <?php foreach($monthArray as $fmRow){?>

                            <th class="text-center" style="text-align: right !important;"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10));?></th>

                        <?php }?>
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

                        <tr id="{{$y->id}}">

                            <td style="cursor: pointer" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $y->code?>')">
                                @if($level == 1)
                                    <b style="font-size: large;font-weight: bolder">{{ strtoupper($y->name)}}</b>
                                @elseif($level == 2)
                                    <b style="font-weight: bolder">    <?php echo  '<span class="SpacesCls"></span>'. $y->name?> </b>
                                @elseif($level == 3)
                                    <b style="font-weight: bolder">   <?php echo  '<span class="SpacesCls"></span>'. $y->name?></b>
                                @elseif($level == 4)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @elseif($level == 5)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @elseif($level == 6)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @elseif($level == 7)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @endif
                            </td>
                            @php
                                $amount = 0;
                            @endphp
                            @foreach($monthArray as $fmRow)
                                <?php
                                
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date = date('Y-m-01', strtotime($filterYear . '-' . $makeMNumber . '-01'));
                                    $to_date = date('Y-m-t', strtotime($filterYear . '-' . $makeMNumber . '-01'));    
        
                                    if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                        $amount = 0;
                                    }
                                    $oldamount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',1,0);

                                    $amount += $oldamount;
                                    if($amount !=0 )  $counter = 1 ;
                                    
                                ?>

                            <td  style="text-align: right !important;" class="text-left">
                                @if($level == 1)
                                    <b style="font-weight: bolder">     <?php echo number_format($amount,2)?></b>
                                @elseif($level == 2)
                                    <b style="font-weight: bolder">    &emsp;<?php echo number_format($amount,2)?></b>
                                @elseif($level == 3)
                                    <b style="font-weight: bolder">     &emsp;&emsp;<?php echo number_format($amount,2)?></b>
                                    @elseif($level == 4)
                                    &emsp;&emsp;&emsp;<?php echo number_format($amount,2)?>
                                    @elseif($level == 5)
                                    &emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount,2)?>
                                    @elseif($level == 6)
                                    &emsp;&emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount,2)?>
                                    @elseif($level == 7)
                                    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount,2)?>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @if($counter == 0)
                        <script>
                            removeRaw({{ $y->id }})
                        </script>

                        @endif

                    @endforeach

                    

                    <tr style="font-weight: 500;font-size: large">
                        <td>Net Income</td>
                        @php
                            $net_profit = 0;
                            $owner_equity = 0;
                        @endphp
                        @foreach($monthArray as $fmRow)
                                <?php
                                
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date = date('Y-m-01', strtotime($filterYear . '-' . $makeMNumber . '-01'));
                                    $to_date = date('Y-m-t', strtotime($filterYear . '-' . $makeMNumber . '-01'));    
                                    if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                        $net_profit = 0;
                                        $owner_equity = 0;
                                    }
                                    $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',1,0);
        
                                    if($amount !=0 )  $counter = 1 ;
                                    
                             
                                    $old_owner_equity = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,3,'1',0,1);
                                    $owner_equity += $old_owner_equity;

                                    $revenue = $revenue=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,5,'1',1,0);


                       //    $cogs =CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,'6-1','1',1,0);


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
                        $old_net_profit=$revenue-$expense;
                        $net_profit += $old_net_profit;
                        $net_profit_array[$fmRow] = $net_profit;
                        $owner_equity_array[$fmRow] = $owner_equity;
                        
                        ?>

                        <td  style="text-align: right !important;"><?php echo number_format($net_profit,2) ?></td>
                    @endforeach

                    </tr>
                    
                    
                    <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                        <td>Total Owner's Equity</td>
                        @foreach($monthArray as $fmRow)

                            <td  style="text-align: right !important;" class="text-right">
                                <?php echo number_format($net_profit_array[$fmRow] + $owner_equity_array[$fmRow] ,2) ?>
                            </td>
                        @endforeach

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

        <div class="report-card">
            <div class="report-card-title liabilities">
                <i class="fa fa-file-invoice-dollar"></i> Liabilities
            </div>
            <div class="table-responsive" style="overflow-x: scroll;">

                <table id="table3" class="table table-bordered sf-table-list Balance_Sheet">
                    <thead>
                    <th class="text-center">LIABILTIES</th>
                    <?php foreach($monthArray as $fmRow){?>

                        <th class="text-center" style="text-align: right !important;"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10));?></th>

                    <?php }?>

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

                        <tr id='{{$y->id}}' title="{{$y->id}}" @if($y->type==1)style="background-color:lightblue" @endif
                        @if($y->type==4)style="background-color:lightgray"  @endif
                            id="{{$y->id}}">
                            <td style="cursor: pointer" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $y->code?>')">
                                @if($level == 1)
                                    <b style="font-size: large;font-weight: bolder">{{ strtoupper($y->name)}}</b>
                                @elseif($level == 2)
                                    <b style="font-weight: bolder">  <?php echo  '<span class="SpacesCls"></span>'. $y->name?> </b>
                                @elseif($level == 3)
                                    <b style="font-weight: bolder"> <?php echo  '<span class="SpacesCls"></span>'. $y->name?> </b>
                                @elseif($level == 4)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @elseif($level == 5)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @elseif($level == 6)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @elseif($level == 7)
                                    <?php echo  '<span class="SpacesCls"></span>'. $y->name?>
                                @endif


                            </td>
                            @php
                                $amount = 0;
                            @endphp
                            @foreach($monthArray as $fmRow)
                                <?php
                                
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date = date('Y-m-01', strtotime($filterYear . '-' . $makeMNumber . '-01'));
                                    $to_date = date('Y-m-t', strtotime($filterYear . '-' . $makeMNumber . '-01'));    

                                    if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                        $amount = 0;
                                    }
                                    
                                    $old_amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',0,1);
                                    $amount += $old_amount;
                                    if($amount !=0 )  $counter = 1 ;
                                    
                                ?>

                            <td style="text-align: right !important;" class="text-left">
                                @if($level == 1)
                                    <b style="font-weight: bolder">  <?php echo number_format($amount,2)?></b>
                                @elseif($level == 2)
                                    <b style="font-weight: bolder">    &emsp;<?php  echo number_format($amount,2)?></b>
                                @elseif($level == 3)
                                    <b style="font-weight: bolder">   &emsp;&emsp;<?php echo  number_format($amount,2)?></b>
                                    @elseif($level == 4)
                                    &emsp;&emsp;&emsp;<?php echo  number_format($amount,2)?>
                                    @elseif($level == 5)
                                    &emsp;&emsp;&emsp;&emsp;<?php echo  number_format($amount,2)?>
                                    @elseif($level == 6)
                                    &emsp;&emsp;&emsp;&emsp;&emsp;<?php echo  number_format($amount,2)?>
                                    @elseif($level == 7)
                                    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php echo  number_format($amount,2)?>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @if($counter == 0)
                        <script>
                            removeRaw({{ $y->id }})
                        </script>

                        @endif
                    @endforeach

                    
                    <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                        <td>Total Liabilities</td>
                        @php
                            $liblaty = 0;
                        @endphp
                        @foreach($monthArray as $fmRow)
                            <?php 
                                    $makeMNumber = $fmRow;
                                    if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                        $liblaty = 0;
                                    }
                                    $old_liblaty = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,'2','1',0,1);
                                    $liblaty += $old_liblaty;
                                    $liblaty_array[$fmRow] = $liblaty;
                            ?>
                            <td style="text-align: right !important;" class="text-right"> <?php echo number_format($liblaty,2) ?></td>
                        @endforeach

                    </tr>

                    <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                        <td>Liabilties + Owner's Equity</td>
                        @foreach($monthArray as $fmRow)
                            <td style="text-align: right !important;" class="text-right"> <?php echo number_format($net_profit_array[$fmRow] + $owner_equity_array[$fmRow] + $liblaty_array[$fmRow],2) ?></td>
                        @endforeach

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
<?php