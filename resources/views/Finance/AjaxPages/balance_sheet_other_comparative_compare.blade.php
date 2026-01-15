<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$m = Session::get('run_company');
$clause='';

$net_profit_array = [];
$owner_equity_array = [];
$net_profit_compare_array = [];
$owner_equity_compare_array = [];
?>
<style>
    table {
        border: 1px solid #CCC !important;
        border-collapse: collapse !important;
    }

    td {
        border: none !important;
    }
</style>
<span id="MultiExport">
<h2 class="text-center topp">compare</h2>


<div class="row" id="data">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive" style="overflow-x: scroll;">

            <table id="table1" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                    <th class="text-center">ASSESTS</th>
                    <?php foreach($monthArray as $fmRow){?>

                        <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10)) ."- $filterYear" ;?></th>

                    <?php }?>

                        <th class="text-center">Total - {{$filterYear}}</th>

                    <?php foreach($monthArray as $fmRow){?>

                        <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10)) ."- $compareYear";?></th>

                    <?php }?>

                    <th class="text-center">Total - {{$compareYear}}</th>

                </thead>
                <tbody>

                @php 
                    $asset_total_amount_first = [] ;
                    $asset_total_amount_second = [] ;
                @endphp

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
                        <td  style="cursor: pointer">
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
                            $asset_total_amount = [];
                            $amount = 0;
                        @endphp
                        @foreach($monthArray as $fmRow)
                                      
                        <?php
                            $old_amount = 0 ;
                            $makeMNumber = $fmRow;
                            if($fmRow < 10){
                                $makeMNumber = '0'.$fmRow;
                            }
                            $from_date = date('Y-m-01', strtotime($filterYear . '-' . $makeMNumber . '-01'));
                            $to_date = date('Y-m-t', strtotime($filterYear . '-' . $makeMNumber . '-01'));    
                            
                            if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                $amount = 0;
                            }

                            $old_amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',1,0);
                            $amount += $old_amount;
                            // dump($amount , $old_amount);
                            $asset_total_amount[] = (float)$old_amount;
                            $asset_total_amount_first[] = (float)$old_amount;
                            if($amount !=0 )  $counter = 1 ;
                            
                            
                        ?>
                            
                        <td  style="text-align: left !important;" class="text-right">
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

                        <td  style="text-align: left !important;" class="text-right">
                            @php echo number_format(array_sum($asset_total_amount),2); @endphp
                        </td>

                        @php
                            $asset_total_amount = [] ;
                            $asset_total_amount_second = [] ;
                            $amount_compare = 0;
                        @endphp
                        @foreach($monthArray as $fmRow)
                                      
                        <?php
                        
                            $makeMNumber = $fmRow;
                            if($fmRow < 10){
                                $makeMNumber = '0'.$fmRow;
                            }
                            
                            $from_date_compare = date('Y-m-01', strtotime($compareYear . '-' . $makeMNumber . '-01'));
                            $to_date_compare = date('Y-m-t', strtotime($compareYear . '-' . $makeMNumber . '-01'));    

                            // if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                            //     $amount_compare = 0;
                            // }

                            $old_amount_compare = CommonHelper::get_parent_and_account_amount($m,$from_date_compare,$to_date_compare,$y->code,'1',1,0);
                            $amount_compare += $old_amount_compare;
                            $asset_total_amount[] = $old_amount_compare;
                            $asset_total_amount_second[] = $old_amount_compare;
                            
                            
                            if($amount_compare !=0 )  $counter = 1 ;
                            

                            
                        ?>
                            

                        <td  style="text-align: left !important;" class="text-right">
                            @if($level == 1)
                                <b style="font-size: large;font-weight: bolder">   <?php echo number_format($amount_compare,2)?> </b>
                            @elseif($level == 2)
                                <b style="font-weight: bolder">     &emsp;<?php echo number_format($amount_compare,2)?></b>
                            @elseif($level == 3)
                                <b style="font-weight: bolder">      &emsp;&emsp;<?php echo number_format($amount_compare,2)?></b>
                                @elseif($level == 4)
                                &emsp;&emsp;&emsp;<?php echo number_format($amount_compare,2)?>
                                @elseif($level == 5)
                                &emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount_compare,2)?>
                                @elseif($level == 6)
                                &emsp;&emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount_compare,2)?>
                                @elseif($level == 7)
                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount_compare,2)?>
                            @endif
                        </td>
                        @endforeach

                        <td  style="text-align: left !important;" class="text-right">
                            @php echo number_format(array_sum($asset_total_amount),2); @endphp
                        </td>

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

                        $old_total_assets = $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,'1','1',1,0);
                        $total_assets += $old_total_assets;
                    ?>

                        <td  style="text-align: left !important;" class="text-right"> <?php echo number_format($total_assets,2) ?></td>
                    @endforeach
                    
                    <td  style="text-align: left !important;" class="text-right">
                            @php echo number_format(array_sum($asset_total_amount_first),2); @endphp
                    </td>

                    @php
                        $total_assets_compare = 0;
                    @endphp
                    @foreach($monthArray as $fmRow)
                                      
                    <?php
                        

                        $makeMNumber = $fmRow;
                        if($fmRow < 10){
                            $makeMNumber = '0'.$fmRow;
                        }
                        
                        $from_date_compare = date('Y-m-01', strtotime($compareYear . '-' . $makeMNumber . '-01'));
                        $to_date_compare = date('Y-m-t', strtotime($compareYear . '-' . $makeMNumber . '-01'));    

                        
                        // if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                        //     $total_assets_compare = 0;
                        // }
                        $old_total_assets_compare = $amount_compare = CommonHelper::get_parent_and_account_amount($m,$from_date_compare,$to_date_compare,'1','1',1,0);
                        $total_assets_compare += $old_total_assets_compare;
                    ?>

                        <td  style="text-align: left !important;" class="text-right"> <?php echo number_format($total_assets,2) ?></td>
                    @endforeach
                    <td  style="text-align: left !important;" class="text-right">
                        @php echo number_format(array_sum($asset_total_amount_second),2); @endphp   
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive" style="overflow-x: scroll;">

            <table id="table2" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                    <th class="text-center">OWNERS EQUITY</th>
                    <?php foreach($monthArray as $fmRow){?>

                        <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10)) ."- $filterYear" ;?></th>

                    <?php }?>

                    <th class="text-center">Total - {{$filterYear}}</th>

                    <?php foreach($monthArray as $fmRow){?>

                        <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10)) ."- $compareYear";?></th>

                    <?php }?>

                    <th class="text-center">Total - {{$compareYear}}</th>

                </thead>
                <tbody>
                @php 
                    $owners_equity_amount_first = [] ;
                    $owners_equity_total_amount_second = [] ;
                @endphp
                @foreach($accounts3 as $key => $y)

                    <?php

                    $counter = 0;
                    $flag = true;

                    $array = explode('-',$y->code);
                    $level = count($array);
                    $nature = $array[0];

                    ?>

                    <tr id="{{$y->id}}">

                        <td style="cursor: pointer">
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
                            $owners_equity_total_amount = [];
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
                                
                                $old_amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',1,0);
                                $amount += $old_amount;
                                
                                $owners_equity_total_amount[] = (float)$old_amount;
                                $owners_equity_total_amount_first[] = (float)$old_amount;
                                if($amount !=0 )  $counter = 1 ;
                                
                            ?>

                        <td  style="text-align: left !important;" class="text-left">
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

                        
                        <td  style="text-align: left !important;" class="text-right">
                            @php echo number_format(array_sum($owners_equity_total_amount), 2); @endphp   
                        </td>

                        @php
                            $owners_equity_total_amount = [];
                            $amount_compare = 0;
                        @endphp
                        @foreach($monthArray as $fmRow)
                            <?php
                            
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                
                                $from_date_compare = date('Y-m-01', strtotime($compareYear . '-' . $makeMNumber . '-01'));
                                $to_date_compare = date('Y-m-t', strtotime($compareYear . '-' . $makeMNumber . '-01'));    
    
                                // if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                //     $amount_compare = 0;
                                // }
                                
                                $old_amount_compare = CommonHelper::get_parent_and_account_amount($m,$from_date_compare,$to_date_compare,$y->code,'1',1,0);
                                $amount_compare += $old_amount_compare;
                                
                                $owners_equity_total_amount[] = (float)$old_amount_compare;
                                $owners_equity_total_amount_second[] = (float)$old_amount_compare;
                                if($amount_compare !=0 )  $counter = 1 ;


                                
                            ?>

                        <td  style="text-align: left !important;" class="text-left">
                            @if($level == 1)
                                <b style="font-weight: bolder">     <?php echo number_format($amount_compare,2)?></b>
                            @elseif($level == 2)
                                <b style="font-weight: bolder">    &emsp;<?php echo number_format($amount_compare,2)?></b>
                            @elseif($level == 3)
                                <b style="font-weight: bolder">     &emsp;&emsp;<?php echo number_format($amount_compare,2)?></b>
                                @elseif($level == 4)
                                &emsp;&emsp;&emsp;<?php echo number_format($amount_compare,2)?>
                                @elseif($level == 5)
                                &emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount_compare,2)?>
                                @elseif($level == 6)
                                &emsp;&emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount_compare,2)?>
                                @elseif($level == 7)
                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php echo number_format($amount_compare,2)?>
                            @endif
                        </td>
                        @endforeach
                        
                        <td  style="text-align: left !important;" class="text-right">
                            @php
                                echo number_format(array_sum($owners_equity_total_amount),2); 
                            @endphp   
                        </td>

                    </tr>
                    @if($counter == 0)
                    <script>
                        removeRaw({{ $y->id }})
                    </script>

                    @endif

                @endforeach

                

                <tr style="font-weight: bolder;font-size: large">
                    <td>Net Income</td>
                    @php
                        $net_income_total_amount = []; 
                        $owner_equity = 0;
                        $net_profit = 0;
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
                                    $owner_equity = 0;
                                    $net_profit = 0;
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
                                $net_profit+=$old_net_profit;
                                $net_income_total_amount[] = $net_profit;
                                $net_profit_array[$fmRow] = $net_profit;
                                $owner_equity_array[$fmRow] = $owner_equity;
                                
                    ?>

                    <td  style="text-align: left !important;"><?php echo number_format($net_profit,2) ?></td>
                @endforeach

                <td  style="text-align: left !important;" class="text-right">
                    @php
                        echo number_format(array_sum($net_income_total_amount),2); 
                        $net_income_total_amount = [];
                        $owner_equity_compare = 0;
                        $net_profit_compare = 0;
                    @endphp   
                </td>
                @foreach($monthArray as $fmRow)
                          
                    <?php
                            
                            $makeMNumber = $fmRow;
                            if($fmRow < 10){
                                $makeMNumber = '0'.$fmRow;
                            }

                            $from_date_compare = date('Y-m-01', strtotime($compareYear . '-' . $makeMNumber . '-01'));
                            $to_date_compare = date('Y-m-t', strtotime($compareYear . '-' . $makeMNumber . '-01'));    

                            // if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                            //     $owner_equity_compare = 0;
                            //     $net_profit_compare = 0;
                            // }

                            $amount_compare = CommonHelper::get_parent_and_account_amount($m,$from_date_compare,$to_date_compare,$y->code,'1',1,0);

                            if($amount !=0 )  $counter = 1 ;
                                                    
                            $old_owner_equity_compare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,3,'1',0,1);
                            $owner_equity_compare += $old_owner_equity_compare;

                            $revenue_compare = $revenue_compare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,5,'1',1,0);

                            //    $cogs =CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,'6-1','1',1,0);

                            $cogs_dr_compare = DB::Connection('mysql2')->table('transactions')
                                    ->where('status',1)
                                    ->where('debit_credit',1)
                                    ->where('acc_id',768)
                                    ->where('voucher_type','!=',5)
                                    ->whereBetween('v_date',[$from_date_compare,$to_date_compare])
                                    ->where('opening_bal',0)
                                    ->sum('amount');

                            $cogs_cr_compare = DB::Connection('mysql2')->table('transactions')
                                    ->where('status',1)
                                    ->where('debit_credit',0)
                                    ->where('acc_id',768)
                                    ->where('voucher_type','!=',5)
                                    ->whereBetween('v_date',[$from_date_compare,$to_date_compare])
                                    ->where('opening_bal',0)
                                    ->sum('amount');

                            $cogs_compare = $cogs_dr_compare - $cogs_cr_compare;

                            if ($revenue_compare < 0):
                                $revenue_compare = $revenue_compare *- 1;
                            endif;
                            $revenue_compare = $revenue_compare - $cogs_compare;
                            $expense_compare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,4,'1',1,0);
                            if ($expense_compare < 0):
                                $expense_compare = $expense_compare;
                            endif;
                            $old_net_profit_compare = $revenue_compare - $expense_compare;
                            $net_profit_compare = $old_net_profit_compare;
                            $net_income_total_amount[] = $net_profit_compare;

                            $net_profit_compare_array[$fmRow] = $net_profit_compare;
                            $owner_equity_compare_array[$fmRow] = $owner_equity_compare;
                            
                    ?>
                    <td  style="text-align: left !important;"><?php echo number_format($net_profit_compare,2) ?></td>
                @endforeach

                <td  style="text-align: left !important;" class="text-right">
                    @php
                        echo number_format(array_sum($net_income_total_amount), 2); 
                        $net_income_total_amount = [];

                        $total_owner_equity_total_amount_first = [];
                        $total_owner_equity_total_amount_second = [];
                    @endphp   
                </td>
                </tr>
                
                
                <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                    <td>Total Owner's Equity</td>

                    @foreach($monthArray as $fmRow)

                        <td  style="text-align: left !important;" class="text-right">
                            <?php 
                                $total_owner_equity_total_amount_first[] = $net_profit_array[$fmRow] + $owner_equity_array[$fmRow];
                                echo number_format($net_profit_array[$fmRow] + $owner_equity_array[$fmRow] ,2) 
                            ?>
                        </td>
                    @endforeach
                    <td  style="text-align: left !important;" class="text-right">
                        @php
                            echo number_format(array_sum($total_owner_equity_total_amount_first),2); 
                        @endphp   
                    </td>
                    @foreach($monthArray as $fmRow)

                        <td  style="text-align: left !important;" class="text-right">
                            <?php
                                $total_owner_equity_total_amount_second[] = $net_profit_compare_array[$fmRow] + $owner_equity_compare_array[$fmRow];

                                echo number_format($net_profit_compare_array[$fmRow] + $owner_equity_compare_array[$fmRow] ,2);
                            ?>
                        </td>
                    @endforeach

                    <td  style="text-align: left !important;" class="text-right">
                        @php
                            echo number_format(array_sum($total_owner_equity_total_amount_second),2); 
                        @endphp   
                    </td>

                    <?php $owner_equity= $owner_equity+$net_profit; ?>
                </tr>
                
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive" style="overflow-x: scroll;"> 

            <table id="table3" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                <th class="text-center">LIABILTIES</th>
                <?php foreach($monthArray as $fmRow){?>

                    <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10)) ."- $filterYear" ;?></th>

                <?php }?>

                <th class="text-center">Total - {{$filterYear}}</th>

                <?php foreach($monthArray as $fmRow){?>
                    
                    <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10)) ."- $compareYear";?></th>

                <?php }?>

                <th class="text-center">Total - {{$compareYear}}</th>
                </thead>
                <tbody>

                @php 
                    $liabilties_total_amount_first = [] ;
                    $liabilties_total_amount_second = [] ;
                    $liabilties_total_amount = [];

                @endphp
                
                @foreach($accounts2 as $key => $y)



                    <?php
                    $counter = 0;

                    $array = explode('-',$y->code);
                    $level = count($array);
                    $nature = $array[0];
                   
                    ?>

                    <tr id='{{$y->id}}' title="{{$y->id}}" @if($y->type==1)style="background-color:lightblue" @endif
                    @if($y->type==4)style="background-color:lightgray"  @endif
                        id="{{$y->id}}">
                        <td style="cursor: pointer">
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
                                $liabilties_total_amount = [];
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
                                $liabilties_total_amount[] = (float)$old_amount;
                                
                                if($amount !=0 )  $counter = 1 ;
                                
                            ?>

                        <td style="text-align: left !important;" class="text-left">
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

                        <td  style="text-align: left !important;" class="text-right">
                            @php
                                echo number_format(array_sum($liabilties_total_amount),2); 
                                $liabilties_total_amount = [];
                                $amount_compare = 0;
                            @endphp   
                        </td>

                        @foreach($monthArray as $fmRow)
                            <?php
                            
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
    
                                $from_date_compare = date('Y-m-01', strtotime($compareYear . '-' . $makeMNumber . '-01'));
                                $to_date_compare = date('Y-m-t', strtotime($compareYear . '-' . $makeMNumber . '-01'));    

                                // if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                //     $amount_compare = 0;
                                // }

                                $old_amount_compare = CommonHelper::get_parent_and_account_amount($m,$from_date_compare,$to_date_compare,$y->code,'1',0,1);
                                $amount_compare += $old_amount_compare;

                                $liabilties_total_amount[] = (float)$old_amount_compare;
                                
                                if($amount_compare !=0 )  $counter = 1 ;
                                
                            ?>

                        <td style="text-align: left !important;" class="text-left">
                            @if($level == 1)
                                <b style="font-weight: bolder">  <?php echo number_format($amount_compare,2)?></b>
                            @elseif($level == 2)
                                <b style="font-weight: bolder">    &emsp;<?php  echo number_format($amount_compare,2)?></b>
                            @elseif($level == 3)
                                <b style="font-weight: bolder">   &emsp;&emsp;<?php echo  number_format($amount_compare,2)?></b>
                                @elseif($level == 4)
                                &emsp;&emsp;&emsp;<?php echo  number_format($amount_compare,2)?>
                                @elseif($level == 5)
                                &emsp;&emsp;&emsp;&emsp;<?php echo  number_format($amount_compare,2)?>
                                @elseif($level == 6)
                                &emsp;&emsp;&emsp;&emsp;&emsp;<?php echo  number_format($amount_compare,2)?>
                                @elseif($level == 7)
                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php echo  number_format($amount_compare,2)?>
                            @endif
                        </td>
                        @endforeach

                        <td  style="text-align: left !important;" class="text-right">
                            @php
                                echo number_format(array_sum($liabilties_total_amount),2); 
                                
                            @endphp   
                        </td>
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
                                $liabilties_total_amount_first[] = $old_liblaty;
                        ?>
                        <td style="text-align: left !important;" class="text-right"> <?php echo number_format($liblaty,2) ?></td>
                    @endforeach

                    <td style="text-align: left !important;" class="text-right">
                            @php
                                echo number_format(array_sum($liabilties_total_amount_first),2); 
                                $liblaty_compare = 0;
                            @endphp   
                    </td>

                    @foreach($monthArray as $fmRow)
                        <?php 
                                // $makeMNumber = $fmRow;
                                // if ( date('m') < $makeMNumber && date('Y') ==  $filterYear) {
                                //     $liblaty_compare = 0;
                                // }
                                $old_liblaty_compare = CommonHelper::get_parent_and_account_amount($m,$from_date_compare,$to_date_compare,'2','1',0,1);
                                $liblaty_compare += $old_liblaty_compare;
                                $liblaty_compare_array[$fmRow] = $liblaty_compare;
                                $liabilties_total_amount_second[] = $old_liblaty_compare;

                        ?>
                        <td style="text-align: left !important;" class="text-right"> <?php echo number_format($liblaty_compare,2) ?></td>
                    @endforeach

                    <td style="text-align: left !important;" class="text-right">
                            @php
                                echo number_format(array_sum($liabilties_total_amount_second),2); 
                                $liabilties_owner_total_amount_first = [];
                                $liabilties_owner_total_amount_second = [];
                            @endphp   
                    </td>

                </tr>

                <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                    <td>Liabilties + Owner's Equity</td>
                    
                    @foreach($monthArray as $fmRow)
                        <td style="text-align: left !important;" class="text-right"> 
                            <?php 
                                $liabilties_owner_total_amount_first[] = $net_profit_array[$fmRow] + $owner_equity_array[$fmRow] + $liblaty_array[$fmRow];
                                echo number_format($net_profit_array[$fmRow] + $owner_equity_array[$fmRow] + $liblaty_array[$fmRow],2);
                            ?>
                        </td>
                    @endforeach
                    <td style="text-align: left !important;" class="text-right">
                            @php
                                echo number_format(array_sum($liabilties_owner_total_amount_first),2);
                            @endphp   
                    </td>
                    
                    @foreach($monthArray as $fmRow)
                        <td style="text-align: left !important;" class="text-right"> 
                            <?php 
                                $liabilties_owner_total_amount_second[] = $net_profit_compare_array[$fmRow] + $owner_equity_compare_array[$fmRow] + $liblaty_compare_array[$fmRow];
                                echo number_format($net_profit_compare_array[$fmRow] + $owner_equity_compare_array[$fmRow] + $liblaty_compare_array[$fmRow],2); 

                            ?>
                        </td>
                    @endforeach
                    <td style="text-align: left !important;" class="text-right">
                            @php
                                echo number_format(array_sum($liabilties_owner_total_amount_second),2);
                            @endphp   
                    </td>

                </tr>
                </tbody>
            </table>
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

