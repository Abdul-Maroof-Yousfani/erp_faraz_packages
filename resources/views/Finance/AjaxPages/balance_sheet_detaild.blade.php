<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

$from_date = Input::get('from_date');
$to_date = Input::get('to_date');
$m = Input::get('m');
$clause='';
?>


<h5 class="text-center topp">Balance Sheet</h5>
<b>	<h4 class="text-center"> As On  <?php echo FinanceHelper::changeDateFormat($to_date) ?></h4></b>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php CommonHelper::companyDatabaseConnection($m);
            $index=0;
            $net_profit_cal=0;
            $owner_equity=0;
            for ($i=1; $i<=3; $i++):
         //   $accounts =  DB::select('select a.id,a.name,a.code from accounts a
        //    inner join
         //   transactions b
         //   ON
         //   a.id=b.acc_id
         //  where a.status="1" and b.status=1 and b.v_date between "'.$from_date.'" and "'.$to_date.'"
         //  and b.amount>0
         //  and a.code like "'.$i.'-%" or code="'.$i.'"  group by a.id order by level1,level2,level3,level4,level5,level6,level7');
           //->result_array();
            $accounts =  DB::select('select id,name,code,operational from accounts where status="1"  and code like "'.$i.'-%" or code="'.$i.'" order by level1,level2,level3,level4,level5,level6,level7 ');
            //            echo "<pre>";
            //            print_r($trial);
            $Counter=1;
            ?>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <table id="header-fixed" class="table table-bordered sf-table-th sf-table-list" style="background:#FFF;">
                    <thead>
                    <tr>
                        <th  class="text-center">Acc_code</th>
                        <th  class="text-center">Acc. Name</th>
                        <th  class="text-center ">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total=0;

                    foreach($accounts as $key => $row):
                    $amount = CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,$row->code,'1',1,0);

                    $code=$row->code;
                    $array=explode('-',$code);
                    $level=$array[0];
                    $index=$array[0];

                    if ($level==1):

                        $total_heading='Total Assests';
                        if ($amount<0):
                            $amount=$amount*-1;
                            $amount=number_format($amount,2);
                            $amount='('.$amount.')';
                        else:
                            $amount=number_format($amount,2);
                        endif;

                    endif;
                    if ($level==2):

                        $total_heading="Total Owner's Equity";
                    endif;


                    if ($level==3):
                        $total_heading="Total Liabilties";
                    endif;

                    if ($level==2 || $level==3):



                        if ($code=='2'):
                            $owner_equity=$amount;
                            if ($owner_equity>0):


                                $owner_equity=$owner_equity*-1;

                            else:
                                    $owner_equity=$owner_equity*-1;
                            endif;

                        endif;

                        if ($amount>0):

                            $amount=number_format($amount,2);
                            $amount='('.$amount.')';

                        else:
                            $amount=$amount*-1;
                            $amount=number_format($amount,2);

                        endif;
                    endif;




                    if ($row->code=='1' || $row->code=='2' || $row->code=='3'):
                        $total=$amount;

                    endif;
                    //   $total += $amount;

                    $array = explode('-',$row->code);
                    $level = count($array);

                    $paramOne = "fdc/getSummaryLedgerDetail?m=".$m;


                    if ($code!='2-3'):
                    ?>

                    <tr>
                        <td class="text-center"><?php echo $row->code ?></td>
                        <td  @if($row->operational==0)style="font-weight: bolder;font-size: large;color: black" @endif class="">
                            <?php if($level == 1){ ?>
                            <b style="font-size: large;font-weight: bolder"> <a @if($row->operational==0)style="font-weight: bolder;font-size: large;color: black" @endif href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?= $row->name; ?></b></a>
                            <?php } elseif($level == 2) {  ?>
                                <span class="SpacesCls">&emsp;&emsp;</span><a @if($row->operational==0)style="font-weight: bolder;font-size: large;color: black" @endif href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?= $row->name; ?></a>
                            <?php } elseif($level == 3){ ?>
                                <span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;</span><a @if($row->operational==0)style="font-weight: bolder;font-size: large;color: black" @endif href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?= $row->name; ?></a>
                            <?php } elseif($level == 4){ ?>
                                <span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span><a @if($row->operational==0)style="font-weight: bolder;font-size: large;color: black" @endif href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?= $row->name; ?></a>
                            <?php } elseif($level == 5){ ?>
                                <span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span><a @if($row->operational==0)style="font-weight: bolder;font-size: large;color: black" @endif href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?= $row->name; ?></a>
                            <?php } elseif($level == 6){ ?>
                                <span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span><a @if($row->operational==0)style="font-weight: bolder;font-size: large;color: black" @endif href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?= $row->name; ?></a>
                            <?php } elseif($level == 7){ ?>
                                <span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span><a @if($row->operational==0)style="font-weight: bolder;font-size: large;color: black" @endif href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?= $row->name; ?></a>
                            <?php } ?>

                        </td>
                        <td class="text-center"><?php echo $amount; ?></td>
                    </tr>

                    <?php elseif($code=='2-3'): ?>

                    <tr style="font-weight: bold;font-size: large">

                        <td  colspan="2" class="">

                            Net Profit
                            <?php

                            $revenue = $revenue=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,4,'1',0,1);
                            if ($revenue<0):
                                $revenue=$revenue;
                            endif;
                            $expense=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,5,'1',1,0);
                            if ($expense<0):
                                $expense=$expense;
                            endif;
                            $net_profit=$revenue-$expense;


                            if ($net_profit<0):


                                $net_profit_cal=$net_profit;
                                $net_profit=$net_profit*-1;
                                $net_profitt=number_format($net_profit,2);
                                $net_profit='('.$net_profitt.')';

                            else:

                                $net_profit_cal=$net_profit;
                                $net_profitt=$net_profit;
                                $net_profit=number_format($net_profit,2);

                            endif;

                            ?>

                        </td>
                        <td class="text-right"><?php echo $net_profit; ?></td>
                    </tr>
                    <?php endif; endforeach; ?>
                    <tr style="background-color: darkgray;font-size: large">
                        <td class="text-center" colspan="2"><?php echo $total_heading ?></td>

                        <?php if ($index==2):

                            $equty= $owner_equity+$net_profit_cal;
                            if ($equty<0):
                                $total= $equty*-1;
                                $total=$total;
                                $total=number_format($total,2);
                                $total='('.$total.')';
                            else:
                                $total=$equty;


                            endif;

                        endif;
                        ?>
                        <td class="text-right" colspan="1"><?php echo $total ?></td>
                    </tr>
                    </tbody>

                    <?php  ?>
                    <?php if ($index==3): ?>

                    <?php

                    ?>


                    <tr style="background-color: darkgray">
                        <td class="text-center" colspan="2">Liabilties +  Owner's Equity</td>
                        <?php

                        $equity=CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,2,'1',1,0);
                        if ($equity<0):
                            $equity=$equity*-1;
                        else:

                            $equity=$equity*-1;
                        endif;

                        $liblaty=CommonHelper::get_parent_and_account_amount($m,$from_date,$to_date,3,'1',1,0);

                        if ($liblaty<0):
                            $liblaty=$liblaty*-1;
                        else:
                            $liblaty=$liblaty*-1;
                        endif;

                        $total_liblaty_equty=number_format($equity+$liblaty+$net_profit_cal,2);

                        ?>
                        <td style="font-size: large;font-weight: bolder" class="text-right" colspan="1"><?php echo $total_liblaty_equty; ?></td>
                    </tr>


                    <?php endif; ?>
                </table>
            </div>



            <?php endfor; ?>
        </div>
    </div>
</div>
<?php

