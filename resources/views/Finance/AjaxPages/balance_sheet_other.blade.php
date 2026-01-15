<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
 $from_date = Input::get('from_date');
$to_date = Input::get('to_date');
$m = Session::get('run_company');
$clause='';
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
<h2 class="text-center topp"></h2>





<div class="row" id="data">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">

            <table id="table1" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                    <th colspan="2" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
                </thead>
                <thead>
                <th colspan="2" class="text-center"><h3 class="text-center topp">Balance Sheet <b> As On  <?php echo FinanceHelper::changeDateFormat($to_date) ?></b></h3></th>
                </thead>
                <thead>
                <th colspan="2" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
                </thead>
                <thead>
                <th class="text-center">ASSESTS</th>
                <th style="text-align: right !important;" class="">Current Balance</th>

                </thead>
                <tbody>
                <?php $counter = 1;?>
                @foreach($accounts1 as $key => $y)
                    <?php
                    $array = explode('-',$y->code);
                    $level = count($array);
                    $nature = $array[0];

                    $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y ->code,'1',1,0);

                    if ($amount!=0):
                    ?>

                    <tr title="{{$y->id}}"

                        id="{{$y->id}}">
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

                    </tr>
                    <?php endif; ?>
                @endforeach

                <?php $total_assets=$amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,'1','1',1,0); ?>
                <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                    <td>Total Assets</td>
                    <td  style="text-align: right !important;" class="text-right"> <?php echo number_format($total_assets,2) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">

            <table id="table2" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                <th class="text-center">OWNERS EQUITY</th>
                <th  style="text-align: right !important;" class="text-center">Current Balance</th>

                </thead>
                <tbody>
                <?php $counter = 1;?>
                @foreach($accounts3 as $key => $y)

                    <?php


                    $array = explode('-',$y->code);
                    $level = count($array);
                    $nature = $array[0];

                    $amount=CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',0,1);

                    if ($amount!=0):
                    ?>

                    <tr

                            id="{{$y->id}}">
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

                    </tr>

                    <?php endif ?>
                @endforeach
                <tr style="font-weight: bolder;font-size: large">
                    <td>Net Income</td>
                    <?php
                    $owner_equity = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,3,'1',0,1);


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
                        echo '</br>';
                     $revenue=$revenue-$cogs;
                     $expense=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,4,'1',1,0);
                    if ($expense<0):
                        $expense=$expense;
                    endif;
                    $net_profit=$revenue-$expense;

                    ?>
                    <td  style="text-align: right !important;"><?php echo number_format($net_profit,2) ?></td>
                </tr>

                <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                    <td>Total Owner's Equity</td>
                    <td  style="text-align: right !important;" class="text-right"><?php echo number_format($owner_equity+$net_profit,2) ?></td>

                    <?php $owner_equity= $owner_equity+$net_profit; ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">

            <table id="table3" class="table table-bordered sf-table-list Balance_Sheet">
                <thead>
                <th class="text-center">LIABILTIES</th>
                <th  style="text-align: right !important;" class="text-center">Current Balance</th>

                </thead>
                <tbody>
                <?php $counter = 1;?>
                @foreach($accounts2 as $key => $y)



                    <?php


                    $array = explode('-',$y->code);
                    $level = count($array);
                    $nature = $array[0];
                    $amount=CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$y->code,'1',0,1);

                    if ($amount!=0):
                    ?>

                    <tr title="{{$y->id}}" @if($y->type==1)style="background-color:lightblue" @endif
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

                    </tr>
                    <?php endif; ?>
                @endforeach

                <?php $liblaty= CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,'2','1',0,1); ?>
                <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                    <td>Total Liabilities</td>
                    <td style="text-align: right !important;" class="text-right"> <?php echo number_format($liblaty,2) ?></td>
                </tr>

                <tr style="background-color: lightblue;font-size: larger;font-weight: bolder">
                    <td>Liabilties + Owner's Equity</td>
                    <td style="text-align: right !important;" class="text-right"> <?php echo number_format($owner_equity+$liblaty,2) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
</div>
</span>

<script>
        function get_detai(url,from,to,code,name)
    {
        showDetailModelOneParamerter(url,from+','+to+','+code+','+name);
    }
</script>
<?php

