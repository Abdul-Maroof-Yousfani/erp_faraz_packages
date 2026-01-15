<?php

use App\Helpers\CommonHelper;
$from = $FromDate;
$to = $ToDate;
        $space = "";
        $class ="";
$end_credit = 0;
?>


<div class="">
    <div class="table-responsive" id="trial_bal">

        <table class="table table-bordered" id="table_export1">
            <thead>
                <th colspan="8" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
            </thead>
            <thead>
            <th colspan="8" class="text-center">
                <h3 style="text-align: center;">Trial Balance 5th Column</h3>
            </th>
            </thead>
            <thead>
                <th colspan="8"><h3 style="text-align: center">    <?php  echo date_format(date_create($from),'d-m-Y').' '.'To'.' '.date_format(date_create($to),'d-m-Y');?></h3></th>
            </thead>
            <thead>
                <th colspan="8" class="text-center"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
            </thead>

            <thead>
                <th colspan="3" class="text-center"></th>
                <th colspan="1" class="text-center">Opening Balance</th>
                <th colspan="2" class="text-center">Transactions</th>
                <th colspan="2" class="text-center">Closing Balance</th>
            </thead>

            <thead>
            <tr>
                <th class="text-center">Code</th>
                <th class="text-center">Account Name</th>
                <th class="text-center">Nature of Account</th>
                <th class="text-center">Open.Bal</th>
                <th  class="text-center">Dr During The Period</th>
                <th class="text-center">Cr During The Period</th>
                <th  class="text-center"> End.Dr</th>
                <th class="text-center"> End.Cr</th>
            </tr>
            </thead>
            <tbody id="tbl_id">
            <?php

                //$accounts = $this->db->query("SELECT * FROM accounts where `branch_id` = '$branch_id'  and hide_trial!='hide' and `status` = 'active' order by `level1`,`level2`,`level3`, `level4`,`level5`,`level6`,`level7`")->result_array();
            $accounts =DB::Connection('mysql2')->select("SELECT * FROM accounts where `status` = 1 order by `level1`,`level2`,`level3`,`level4`,`level5`,`level6`,`level7`");

            $counter = 1;
            $end_debit = 0;
            $debit_total = 0;
            $credit_total = 0;
            $debit_cl_total = 0;
            $debit_end_total = 0;
            $credit_cl_total = 0;
            $credit_end_total = 0;
            $paramOne = "fdc/getSummaryLedgerDetail?m=".$m;
            foreach($accounts as $row):
            $code = $row->code;
            $acc_id = $row->id;
            $array = explode('-',$code);
            $level = count($array);
            $nature = $array[0];
            ?>
            <tr  class="<?php if($level == 1){echo 'smr-purple';}
            elseif($level == 2){echo 'smr-pink';}
            elseif($level == 3){echo 'smr-orange';}
            elseif($level == 4){echo 'smr-yellow';}
            elseif($level == 5){echo 'smr-lightgreen';}
            elseif($level == 6){echo 'smr-green';}
            elseif($level == 7){echo 'smr-lightblue';}
            ?>" title="<?php if($level == 2){echo 'LEVEL TWO ACCOUNT';}
            elseif($level == 3){echo 'LEVEL THREE ACCOUNT';}
            elseif($level == 4){echo 'LEVEL FOUR ACCOUNT';}
            elseif($level == 5){echo 'LEVEL FIVE ACCOUNT';}
            elseif($level == 6){echo 'LEVEL SIX ACCOUNT';}
            elseif($level == 7){echo 'LEVEL SEVEN ACCOUNT';}
            ?>" >
                <td><?php echo "'".$code?></td>
                <td <?php  if ($row->operational==0): ?>style="font-size20px : red;font-weight: bold;font-weight: 900;"<?php endif; ?>  class="sf-uc-first text-left">


                    <?php if($level ==1)

                    {
                    if ($row->operational == 1): ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')"><?php echo  $row->name;?></div>

                            <?php else: ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')"><?php echo  $row->name;?></div>
                        <?php
                        endif;?>
                    <?php
                    }
                    elseif($level ==2)
                    {

                        if ($row->operational == 1): ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">


                            <?php
                            if ($space==1):
                                echo strtoupper($row->name);
                            else:
                                echo  '<span class="SpacesCls">&emsp;</span>'.strtoupper($row->name);
                            endif;?>
                        </div>


                    <?php
                        else:
                        ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">
                        <?php
                            if ($space==1):
                                echo strtoupper($row->name);
                            else:?>
                            <?php echo  '<span class="SpacesCls">&emsp;</span>'.strtoupper($row->name);
                            endif;
                                ?>
                                </div>
                            <?php
                        endif;
                    }
                    elseif($level ==3){



                    if ($row->operational==1): ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">
                        <?php
                        if ($space==1):

                            echo strtoupper($row->name);
                        else:

                            echo  '<span class="SpacesCls">&emsp;&emsp;</span>'.strtoupper($row->name); endif;?>
                    </div>


                    <?php else:
                            ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">
                            <?php
                            if ($space==1):

                            echo strtoupper($row->name);
                            else:?>

                            <?php echo  '<span class="SpacesCls">&emsp;&emsp;</span>'.strtoupper($row->name);
                            endif;
                                ?>
                        </div>
                            <?php
                        endif;
                    }
                    elseif($level ==4)

                    {

                    if ($row->operational==1): ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">


                        <?php
                        if ($space==1):

                            echo strtoupper($row->name);
                        else:
                            echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;</span>'.strtoupper($row->name);endif;  ?>
                    </div>


                    <?php else:
                        ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">
                    <?php
                            if ($space==1):

                            echo strtoupper($row->name);
                        else:?>

														<?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;</span>'.strtoupper($row->name);
                            endif;
                                ?>
                                </div>
                            <?php
                        endif;



                    }
                    elseif($level ==5){


                    if ($row->operational==1): ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">


                        <?php
                        if ($space==1):

                            echo strtoupper($row->name);
                        else:
                            echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;</span>'.strtoupper($row->name); endif; ?>
                    </div>


                    <?php else:
                            ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">
                        <?php
                            if ($space==1):

                        echo strtoupper($row->name);
                    else:?>

														<?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;</span>'.strtoupper($row->name); endif;
                                ?>
                                </div>
                            <?php
                            endif;





                    }
                    elseif($level ==6){

                    if ($row->operational==1): ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">


                        <?php

                        if ($space==1):

                            echo strtoupper($row->name);
                        else:
                            echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;</span>'.strtoupper($row->name); endif; ?>
                    </div>


                    <?php else:
                            ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">
                        <?php
                            if ($space==1):

                        echo strtoupper($row->name);
                    else: ?>

														<?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;</span>'.strtoupper($row->name);endif;
                                ?>
                                </div>
                            <?php
                            endif;


                    }
                    elseif($level ==7){

                    if ($row->operational==1): ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">


                        <?php
                        if ($space==1):

                            echo strtoupper($row->name);
                        else:
                            echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'.strtoupper($row->name);endif;  ?>
                    </div>


                    <?php else:
                            ?>
                        <div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')">
                        <?php
                            if ($space==1):

                        echo strtoupper($row->name);
                    else:?>

														<?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'.strtoupper($row->name); endif;
                            ?>
                            </div>
                        <?php
                        endif;



                    }


                    ?>
                </td>

                <td>
                    <?php if($nature ==01){echo $a='ASSETS';}
                    elseif($nature ==02){echo $a='LIABILITY';}
                    elseif($nature ==03){echo $a='EQUITY';}
                    elseif($nature ==04){echo $a='EXPENSES';}
                    elseif($nature ==05){echo $a='REVENUE';}
                    elseif($nature ==06){echo $a='Cost Of Sales';}
                    $len = strlen($code);
$bal = 0;

                    $bal = DB::Connection('mysql2')->select("select coalesce(sum(`amount`),0)-(select coalesce(sum(`amount`),0)
							from `transactions`
							where substring_index(`acc_code`,'-',$level) = '$code' and `debit_credit` = 0
							AND `status` = 1 AND `v_date` between '$from' and '$to') as bal
							from `transactions`
							where substring_index(`acc_code`,'-',$level) = '$code' and `debit_credit` = 1
							AND `status` = 1 AND `v_date` between '$from' and '$to'");

                        //$bal = $bal->bal;



                    if ($from==$AccYearFrom):



                        $clause="and opening_bal=0";

                    else:
                        $clause="";
                    endif;

                    $debit=DB::Connection('mysql2')->selectOne("select sum(amount)amount from transactions where status=1 and v_date between '$from' and '$to'
							and substring_index(`acc_code`,'-',$level) = '$code' and debit_credit=1 ".$clause."")->amount;





                    $creditt=DB::Connection('mysql2')->selectOne("select sum(amount)amount from transactions where status=1 and v_date between '$from' and '$to'
							and substring_index(`acc_code`,'-',$level) = '$code' and debit_credit=0 ".$clause."")->amount;

                    $newdate = strtotime('-1 day', strtotime($from));
                    $newdate = date('Y-m-d', $newdate);
                     $acc_year_from = $AccYearFrom;
                    ?>



                    <?php

                    if ($from==$AccYearFrom):


                        if ($nature==52 || $nature==52):
                            $cl_bal=0;
                        else:
                            $cl_bal = DB::Connection('mysql2')->selectOne("select coalesce(sum(`amount`),0)-(select coalesce(sum(`amount`),0)
							from `transactions`
							where substring_index(`acc_code`,'-',$level) = '$code' and `debit_credit` = 0
							AND  `status` = 1 and opening_bal=1) as bal
							from `transactions`
							where substring_index(`acc_code`,'-',$level) = '$code' and `debit_credit` = 1
							 AND `status` = 1 and opening_bal=1")->bal;

                        endif;
                    else:
                        $cl_bal = DB::Connection('mysql2')->selectOne("select coalesce(sum(`amount`),0)-(select coalesce(sum(`amount`),0)
							from `transactions`
							where substring_index(`acc_code`,'-',$level) = '$code' and `debit_credit` = 0
							AND  `status` = 1 AND `v_date` between '$acc_year_from' and '$newdate') as bal
							from `transactions`
							where substring_index(`acc_code`,'-',$level) = '$code' and `debit_credit` = 1
						 AND `status` = 1 AND `v_date` between '$acc_year_from' and '$newdate'")->bal;

                    endif;
                    ?>




                </td>

                <td class="text-right smr-text-red">


                    <?php

                    if ($cl_bal >0):
                        echo  number_format($cl_bal,2);  endif;
                    $debit_cl=$cl_bal;
                    if ($cl_bal <0):

                        if ($nature==2 || $nature==3):
                            echo number_format(-1*$cl_bal,2);
                        else:
                            echo '('.number_format(-1*$cl_bal,2).')';
                            $credit_cl=1*$cl_bal;
                        endif;



                    endif;

                    ?>
                </td>



                <td class="text-right smr-text-red">


                    <?php


                    echo  number_format($debit,2);
                    //	$debit=$bal;

                    ?>
                </td>

                <td class="text-right">

                    <?php


                    echo number_format($creditt,2);

                    $creditt=-1*$creditt;

                    ?>

                </td>






                <?php  $end=$cl_bal+$creditt+$debit;  ?>
                <td class="text-right">



                    <?php if ($end>0): echo number_format($end,2);endif; $end_debit+=$end;?>

                </td>

                <td class="text-right">



                    <?php if ($end<0): if ($nature==2 || $nature==3):
                        echo number_format(-1*$end,2);

                    else:
                        echo '('.number_format(-1*$end,2).')';
                        $end=1*$end;
                    endif;
                        $end_credit+=$end;
                    endif;?>

                </td>

                <?php  ?>

            </tr>


            <?php

            /*		if ( $bal>0):
                $debit_total += $bal;
            endif;

            if ( $bal<0):
                $credit_total += ($bal*-1);
            endif;*/
            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6')  ){$debit_total += $debit;}
            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') ){$credit_total += ($creditt*-1);}


            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') && $cl_bal>0){$debit_cl_total+=$cl_bal;}
            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') && $cl_bal<0){$credit_cl_total += ($cl_bal*-1);}



            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') && $end>0)

            {
                $debit_end_total+=$end;
                $end.' ';

            }

            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') && $end<0){$credit_end_total += ($end*-1);}
            ?>




            <?php  $counter++; endforeach; ?>




            <tr class="sf-table-total">
                <td colspan="2" class="text-center"><b>Total</b></td>



                <td><?php echo number_format($debit_cl_total,2); ?></td>
                <td class="text-right"><?php echo number_format($credit_cl_total,2); ?></td>

                <?php

                if($debit_total != $credit_total)
                {$class = 'class="bg-danger text-right"'; }

                ?>

                <td  <?php echo $class ?> style="text-align:right;"><b><?php
                        echo number_format($debit_total,2); ?></b></td>
                <td <?php echo $class ?> style="text-align:right;"><b><?php
                        echo number_format($credit_total,2); ?></b></td>

                <td class="text-right">

                <?php echo number_format($debit_end_total,2); ?>
                </td>

                <td class="text-right">

                <?php echo number_format($credit_end_total,2); ?>
                </td>


            </tr>
            <tr class="text-right">
                <td colspan="7"><b>Diffrence</b></td>
                <td class="amir text-right">

                    <?php echo number_format($debit_total-$credit_total+$debit_cl_total-$credit_cl_total+$debit_end_total-$credit_end_total,2); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


