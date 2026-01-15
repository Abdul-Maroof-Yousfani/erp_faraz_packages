@php
    //echo $filterYear;
    //echo '<br />';
    //print_r($filterMonth);
    use App\Helpers\CommonHelper;

    $revenueArray = [];
    $revenueCompareArray = [];
    $cogsArray = [];
    $cogsCompareArray = [];
    $expenseArray = [];
    $expenseCompareArray = [];
    $otherIncomeArray = [];
    $otherIncomeCompareArray = [];

    $revenue_total = 0 ;
    $revenue_total_compare = 0 ;
    $expense_total = 0 ;
    $expense_total_compare = 0 ;
    $other_total = 0;
    $other_total_compare = 0;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive" style="overflow-x: scroll;">
            <table class="table table-bordered table-striped Profit_Loss">
                <thead>
                    <tr>
                        <th class="text-center">Account Name</th>
                        <?php foreach($filterMonth as $fmRow){?>

                            <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10)) ."- $filterYear" ;?></th>
                          
                        <?php }?>
                        <th class="text-center">TOTAL - {{ $filterYear }}</th>
                        <?php foreach($filterMonth as $fmRow){?>
 
                            <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10)) ."- $compareYear";?></th>

                        <?php }?>
                        <th class="text-center">TOTAL - {{ $compareYear }}</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                        CommonHelper::companyDatabaseConnection($CompanyId);

                        if($comparetive == 2)
                        {

                            $revenueAccount = DB::select("SELECT * FROM accounts where `status` = '1' and level1 = 5
                                                    and level4 = 0
                                                    and level5 = 0
                                                    and level6 = 0
                                                    and level7 = 0
                    
                                                    order by level1,level2,level3,level4,level5,level6,level7");
                            
                            $expenseAccount = DB::select("SELECT * FROM accounts where `status` = '1' and  level1 = 4 
                                                    and level4 = 0
                                                    and level5 = 0
                                                    and level6 = 0
                                                    and level7 = 0
                    
                                                    order by level1,level2,level3,level4,level5,level6,level7");

                            $cogsAccount = DB::select("SELECT * FROM accounts where `status` = '1' and  level1 = 7 
                                                    and level4 = 0
                                                    and level5 = 0
                                                    and level6 = 0
                                                    and level7 = 0
                    
                                                    order by level1,level2,level3,level4,level5,level6,level7");
                            
                            $otherIncomeAccount = DB::select("SELECT * FROM accounts where `status` = '1' and  level1 = 6
                                                    and level4 = 0
                                                    and level5 = 0
                                                    and level6 = 0
                                                    and level7 = 0
                    
                                                    order by level1,level2,level3,level4,level5,level6,level7");
                        }
                        else
                        {
                            $revenueAccount = DB::select("SELECT * FROM accounts where `status` = '1' and level1 = 5  order by level1,level2,level3,level4,level5,level6,level7");
                            
                            $expenseAccount = DB::select("SELECT * FROM accounts where `status` = '1' and  level1 = 4 order by level1,level2,level3,level4,level5,level6,level7");

                            $cogsAccount = DB::select("SELECT * FROM accounts where `status` = '1' and  level1 = 7 order by level1,level2,level3,level4,level5,level6,level7");
                            
                            $otherIncomeAccount = DB::select("SELECT * FROM accounts where `status` = '1' and  level1 = 6 order by level1,level2,level3,level4,level5,level6,level7");
                        }
                        
                        
                        
                        
                            $counter = 0;
                            $counterTwo = 0;
                            $bCounter = 0;
                            $bCounterTwo = 0;
                            $cCounter = 0;
                            $cCounterTwo = 0;
                            $dCounter = 0;
                            $dCounterTwo = 0;
                            
                        CommonHelper::reconnectMasterDatabase();

                        foreach($revenueAccount as $row1):
                            $head = strlen($row1->code);
                            $level = count(explode('-',$row1->code));
                            $paramOne = "fdc/getSummaryLedgerDetail?m=".$CompanyId;
                            $counter++;
                            if($counter == 1){
                                echo '<tr><td style="font-size: 20px !important;font-weight: bold" colspan="50">Revenue</td></tr>';
                            }else{
                    ?>
                            <tr>
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?>>
                                    <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bold"><a href="#"><?php echo strtoupper($row1->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#"><?php echo  ''. $row1->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#"><?php echo  ''. $row1->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#"><?php echo  ''. $row1->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#"><?php echo  ''. $row1->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#"><?php echo  ''. $row1->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#"><?php echo  ''. $row1->name?></a>
                                    <?php endif;?>
                                </td>
                                <?php 
                                    $revenue_amount = [];

                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                        $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                ?>
                                        <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right">
                                            <?php 
                                                $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row1->code,'1',0,1);
                                                $revenue_amount[] = $amount;
                                                $revenue_amount_first[] = $amount;
                                                $revenue_total += $amount;

                                                if ($amount<0):
                                                    $amount=($amount*-1);
                                                    $amount=number_format($amount);
                                                    $amount='('.$amount.')';
                                                else:
                                                    $amount=number_format($amount);
                                                endif;
                                                echo $amount;

                                                
                                            ?>
                                        </td>
                                        
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            @php echo number_format(array_sum($revenue_amount),2); @endphp
                                        </td>

                                <?php 
                                    $revenue_amount = [];

                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                        $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                                ?>
                                        <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right">
                                            <?php 
                                                $amountCompare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,$row1->code,'1',0,1);
                                                $revenue_amount[] = $amountCompare;
                                                $revenue_amount_second[] = $amountCompare;

                                                if ($amountCompare<0):
                                                    $amountCompare=($amountCompare*-1);
                                                    $amountCompare=number_format($amountCompare);
                                                    $amountCompare='('.$amountCompare.')';
                                                else:
                                                    $amountCompare=number_format($amountCompare);
                                                endif;
                                                echo $amountCompare;

                                                $revenue_total_compare += $amountCompare;
                                            ?>
                                        </td>
                                
                                <?php }?>

                                        <td  style="text-align: left !important;" class="text-right">
                                            @php echo number_format(array_sum($revenue_amount),2); @endphp
                                        </td>

                                <td class="hide"  style="text-align: left;"> 
                                    @php
                                        $revenue_total_sum = $revenue_total - $revenue_total_compare;
                                        if($revenue_total_sum < 0 ):
                                            echo "(".number_format(abs((float)$revenue_total_sum)).")";
                                        else:
                                           echo number_format($revenue_total_sum);
                                        endif;        
                                    @endphp 
                                </td>
                            </tr>
                    <?php
                            }
                        endforeach;
                        
                        foreach($revenueAccount as $row2):
                            $counterTwo++;
                            if($counterTwo == 1){
                    ?>
                                <tr>
                                    <th>Total Revenue</th>
                                    
                                    <?php 
                                        $total_revenue_amount = [];

                                        foreach($filterMonth as $fmRow){
                                            $makeMNumber = $fmRow;
                                            if($fmRow < 10){
                                                $makeMNumber = '0'.$fmRow;
                                            }
                                            $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                            $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                    ?>
                                            <th <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right">
                                                <?php 
                                                    $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row2->code,'1',0,1);
                                                    $revenueArray[$fmRow] = [$amount];
                                                    $total_revenue_amount[] = $amount;

                                                    if ($amount<0):
                                                        $amount=($amount*-1);
                                                        $amount=number_format($amount);
                                                        $amount='('.$amount.')';
                                                    else:
                                                        $amount=number_format($amount);
                                                    endif;
                                                    echo $amount;
                                                ?>
                                            </th>
                                    <?php }?>
                                            <td  style="text-align: left !important;" class="text-right">
                                                @php echo number_format(array_sum($total_revenue_amount),2); @endphp
                                            </td>
                                    
                                    <?php 
                                        $total_revenue_amount = [];

                                        foreach($filterMonth as $fmRow){
                                            $makeMNumber = $fmRow;
                                            if($fmRow < 10){
                                                $makeMNumber = '0'.$fmRow;
                                            }
                                            $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                            $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                                    ?>
                                            
                                            <th <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right">
                                                <?php 
                                                    $amountCompare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,$row2->code,'1',0,1);
                                                    $revenueCompareArray[$fmRow] = [$amountCompare];
                                                    $total_revenue_amount[] = $amountCompare;
                                                    
                                                    if ($amountCompare < 0):
                                                        $amountCompare = ($amountCompare*-1);
                                                        $amountCompare = number_format($amountCompare);
                                                        $amountCompare = '('.$amountCompare.')';
                                                    else:
                                                        $amountCompare = number_format($amountCompare);
                                                    endif;
                                                    echo $amountCompare;
                                                ?>
                                            </th>
                                    <?php }?>
                                            <td  style="text-align: left !important;" class="text-right">
                                                @php echo number_format(array_sum($total_revenue_amount),2); @endphp
                                            </td>
                                    <th class="hide" style="text-align: left;">
                                        
                                        @php
                                            $revenueArrayTotal = array_sum(array_map('current', $revenueArray)) - array_sum(array_map('current', $revenueCompareArray));
                                            if($revenueArrayTotal < 0 ):
                                                echo "(".number_format(abs((float)$revenueArrayTotal)).")";
                                            else:
                                            echo number_format($revenueArrayTotal);
                                            endif;        
                                        @endphp 
                                    </th>
                                </tr>
                    <?php
                            }
                        endforeach;
                    ?>
                    {{-- Revenue End --}}
                    <tr>
                        <td colspan="100">&nbsp;</td>
                    </tr>
                    {{-- Cost Of Goods Sold Start --}}
                    <?php 
                        foreach ($cogsAccount as $row5) {
                            $head = strlen($row5->code);
                            $level = count(explode('-',$row5->code));
                            $cCounter++;
                            $headWiseTotalAmount = 0;
                            $headWiseTotalAmountCompare = 0 ;
                            if($cCounter == 1){
                                echo '<tr><td colspan="50">Cost of Goods Sold</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="costOfGoodsSoldRecordRow_<?php echo $cCounter?>">
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#"><?php echo strtoupper($row5->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row5->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row5->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row5->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row5->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row5->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row5->name?></a>
                                    <?php endif;?>
                                </td>

                                <?php
                                    $cogs_amount = [];
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                        $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                ?>
                                        <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right">
                                            <?php 
                                                $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row5->code,'1',1,0);
                                                $cogs_amount[] = $amount;
                                                if($amount != 0){
                                                    $headWiseTotalAmount = 1;
                                                }
                                                if ($amount<0):
                                                    $amount=($amount*-1);
                                                    $amount=number_format($amount);
                                                    $amount='('.$amount.')';
                                                else:
                                                    $amount=number_format($amount);
                                                endif;
                                                echo $amount;
                                            ?>
                                        </td>
                                    
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            @php echo number_format(array_sum($cogs_amount),2); @endphp
                                        </td>

                                <?php
                                    $cogs_amount = [];
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }

                                        $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                        $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                                ?>
                                        <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right">
                                            <?php 
                                                $amountCompare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,$row5->code,'1',1,0);
                                                $cogs_amount[] = $amountCompare;
                                                if($amountCompare != 0){
                                                    $headWiseTotalAmountCompare = 1;
                                                }
                                                if ($amountCompare<0):
                                                    $amountCompare=($amountCompare*-1);
                                                    $amountCompare=number_format($amountCompare);
                                                    $amountCompare='('.$amountCompare.')';
                                                else:
                                                    $amountCompare=number_format($amountCompare);
                                                endif;
                                                echo $amountCompare;
                                            ?>
                                        </td>
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            @php echo number_format(array_sum($cogs_amount),2); @endphp
                                        </td>
                            </tr>
                    <?php
                            }
                    ?>
                        <script>
                            hideExpenseRecordRow('costOfGoodsSoldRecordRow_','<?php echo $cCounter?>','<?php echo $headWiseTotalAmount?>');
                            hideExpenseRecordRow('costOfGoodsSoldRecordRow_','<?php echo $cCounter?>','<?php echo $headWiseTotalAmountCompare?>');
                        </script>
                    <?php
                        }
                    ?>
                    <?php 
                    foreach ($cogsAccount as $row6) {
                        $head = strlen($row6->code);
                        $level = count(explode('-',$row6->code));
                        $cCounterTwo++;
                        $headWiseTotalAmount = 0;
                        $headWiseTotalAmountCompare = 0 ;

                        if($cCounterTwo == 1){
                        //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                ?>
                        <tr>
                            <td>Total Cost of Goods Sold</td>
                            
                            <?php
                                $total_cogs_amount = [];
                                foreach($filterMonth as $fmRow){
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                    $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                            ?>
                                    <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                        <?php 
                                            $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row6->code,'1',1,0);
                                            $cogsArray[$fmRow] = [$amount];
                                            $total_cogs_amount[] = $amount;
                                            if($amount != 0){
                                                $headWiseTotalAmount = 1;
                                            }
                                            if ($amount<0):
                                                $amount=($amount*-1);
                                                $amount=number_format($amount);
                                                $amount='('.$amount.')';
                                            else:
                                                $amount=number_format($amount);
                                            endif;
                                            echo $amount;
                                        ?>
                                    </td>
                                    
                            <?php }?>

                                    <td  style="text-align: left !important;" class="text-right">
                                        @php echo number_format(array_sum($total_cogs_amount),2); @endphp
                                    </td>
                            
                            <?php
                                $total_cogs_amount = [];
                                foreach($filterMonth as $fmRow){
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                    $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                            ?>
                                
                                    <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                        <?php 
                                            $amountCompare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,$row6->code,'1',1,0);
                                            $cogsCompareArray[$fmRow] = [$amountCompare];
                                            $total_cogs_amount[] = $amountCompare;

                                            $headWiseTotalamountCompare = 0 ;
                                            if($amountCompare != 0){
                                                $headWiseTotalamountCompare = 1;
                                            }
                                            if ($amountCompare<0):
                                                $amountCompare=($amountCompare*-1);
                                                $amountCompare=number_format($amountCompare);
                                                $amountCompare='('.$amountCompare.')';
                                            else:
                                                $amountCompare=number_format($amountCompare);
                                            endif;
                                            echo $amountCompare;
                                        ?>
                                    </td>
                            <?php }?>

                                    <td  style="text-align: left !important;" class="text-right">
                                        @php echo number_format(array_sum($total_cogs_amount),2); @endphp
                                    </td>

                           <td class="hide" style="text-align: left;">
                                
                                @php
                                    $otherIncomeArrayTotal = array_sum(array_map('current', $otherIncomeArray)) - array_sum(array_map('current', $otherIncomeCompareArray));
                                    if($otherIncomeArrayTotal < 0 ):
                                        echo "(".number_format(abs((float)$otherIncomeArrayTotal)).")";
                                    else:
                                    echo number_format($otherIncomeArrayTotal);
                                    endif;        
                                @endphp 
                                
                            </td>
                        </tr>
                <?php
                        }
                    }
                ?>
                    {{-- Cost Of Goods Sold End --}}

                    {{-- Gross Profit Start --}}

                    <tr>
                        <th style="font-size: 20px !important;font-weight: bold; background:#dfe5ec !important;" >Gross Profit</th>
                        
                        <?php
                            $gross_profit_amount = [];
                            foreach($filterMonth as $fmRow){
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                        ?>
                                <th style="background:#dfe5ec !important;" class="text-right" id="grossProfit_<?php echo $fmRow?>">
                                    <?php 
                                            $gross_profit_amount[] = $revenueArray[$fmRow][0] - $cogsArray[$fmRow][0];
                                            echo ($revenueArray[$fmRow][0] - $cogsArray[$fmRow][0]);
                                            
                                    ?>
                                </th>
                        <?php }?>
                                <td  style="text-align: left !important;" class="text-right">
                                    @php echo number_format(array_sum($gross_profit_amount),2); @endphp
                                </td>
                        <?php
                            $gross_profit_amount = [];
                            foreach($filterMonth as $fmRow){
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                        ?>
                                <th style="background:#dfe5ec !important;" class="text-right" id="grossProfit_<?php echo $fmRow?>">
                                    <?php
                                            $gross_profit_amount[] = $revenueCompareArray[$fmRow][0] - $cogsCompareArray[$fmRow][0];
                                            echo ($revenueCompareArray[$fmRow][0] - $cogsCompareArray[$fmRow][0]);
                                    ?>
                                </th>
                        <?php }?>

                                <td  style="text-align: left !important;" class="text-right">
                                    @php echo number_format(array_sum($gross_profit_amount),2); @endphp
                                </td>

                        <th class="hide" style="background:#dfe5ec !important;text-align: left;">
                            @php
                                $grossProfitTotal = (array_sum(array_map('current', $revenueArray)) - array_sum(array_map('current', $cogsArray))) - (array_sum(array_map('current', $revenueCompareArray)) - array_sum(array_map('current', $cogsCompareArray)));
                                if($grossProfitTotal < 0 ):
                                    echo "(".number_format(abs((float)$grossProfitTotal)).")";
                                else:
                                echo number_format($grossProfitTotal);
                                endif;        
                            @endphp 
                        </th>

                    </tr>
                    
                    {{-- Gross Profit End --}}

                    <tr>
                        <td colspan="100">&nbsp;</td>
                    </tr>
                    {{-- Expense Start --}}
                    <?php
                        foreach($expenseAccount as $row3):
                            $head = strlen($row3->code);
                            $level = count(explode('-',$row3->code));
                            $bCounter++;
                            $headWiseTotalAmount = 0;
                            $headWiseTotalAmountCompare = 0 ;

                            if($bCounter == 1){
                                echo '<tr><td style="font-size: 20px !important;font-weight: bold"  colspan="50">Expense</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="expenseRecordRow_<?php echo $bCounter?>">
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?>  >
                                    <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#"><?php echo strtoupper($row3->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row3->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row3->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row3->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row3->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row3->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row3->name?></a>
                                    <?php endif;?>
                                </td>

                                <?php
                                    $expense_amount = [] ;
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                        $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                ?>
                                        <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                            <?php 
                                                $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row3->code,'1',1,0);
                                                $expense_amount[] = $amount ;
                                                
                                                $expense_total += (int)$amount ?? 0 ; 

                                                if($amount != 0){
                                                    $headWiseTotalAmount = 1;
                                                }
                                                if ($amount<0):
                                                    $amount=($amount*-1);
                                                    $amount=number_format($amount);
                                                    $amount='('.$amount.')';
                                                else:
                                                    $amount=number_format($amount);
                                                endif;
                                                echo $amount;

                                            ?>
                                        </td>
                                        
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            @php echo number_format(array_sum($expense_amount),2); @endphp
                                        </td>

                                <?php
                                    $expense_amount = [] ;
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                        $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                                ?>
                                        <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                            <?php 
                                                $amountCompare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,$row3->code,'1',1,0);
                                                $expense_amount[] = $amountCompare ;
                                                $expense_total_compare += (int)$amountCompare ?? 0 ; 
                                                $headWiseTotalAmountCompare = 0;
                                                if($amountCompare != 0){
                                                    $headWiseTotalAmountCompare = 1;
                                                }
                                                if ($amountCompare<0):
                                                    $amountCompare=($amountCompare*-1);
                                                    $amountCompare=number_format($amountCompare);
                                                    $amountCompare='('.$amountCompare.')';
                                                else:
                                                    $amountCompare=number_format($amountCompare);
                                                endif;
                                                echo $amountCompare;

                                            ?>
                                        </td>
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            @php echo number_format(array_sum($expense_amount),2); @endphp
                                        </td>

                                <td class="hide" style="text-align: left;">
                                    @php
                                        $expense_total_sum = $expense_total - $expense_total_compare; 
                                        if($expense_total_sum < 0 ):
                                            echo "(".number_format(abs((float)$expense_total_sum)).")";
                                        else:
                                        echo number_format($expense_total_sum);
                                        endif;        
                                    @endphp 
                                </td>
                            </tr>
                    <?php
                            }
                    ?>
                    <script>
                        hideExpenseRecordRow('expenseRecordRow_','<?php echo $bCounter?>','<?php echo $headWiseTotalAmount?>');
                        hideExpenseRecordRow('expenseRecordRow_','<?php echo $bCounter?>','<?php echo $headWiseTotalAmountCompare?>');
                    </script>
                    <?php
                        endforeach;
                        foreach($expenseAccount as $row4):
                            $bCounterTwo++;
                            if($bCounterTwo == 1){
                    ?>
                                <tr>
                                    <th>Total Expense</th>
                                    
                                    <?php 
                                        $total_expense_amount = [];
                                        foreach($filterMonth as $fmRow){
                                            $makeMNumber = $fmRow;
                                            if($fmRow < 10){
                                                $makeMNumber = '0'.$fmRow;
                                            }
                                            $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                            $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                    ?>
                                            <th <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                                <?php 
                                                    $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row4->code,'1',1,0);
                                                    $total_expense_amount[] = $amount; 
                                                    $expenseArray[$fmRow] = [$amount];
                                                    if ($amount<0):
                                                        $amount=($amount*-1);
                                                        $amount=number_format($amount);
                                                        $amount='('.$amount.')';
                                                    else:
                                                        $amount=number_format($amount);
                                                    endif;
                                                    echo $amount;
                                                ?>
                                            </th>
                                            
                                    <?php }?>
                                            <td  style="text-align: left !important;" class="text-right">
                                                @php echo number_format(array_sum($total_expense_amount),2); @endphp
                                            </td>

                                    
                                    <?php 
                                        $total_expense_amount = [];
                                        foreach($filterMonth as $fmRow){
                                            $makeMNumber = $fmRow;
                                            if($fmRow < 10){
                                                $makeMNumber = '0'.$fmRow;
                                            }
                                            $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                            $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                                    ?>
                                            <th <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                                <?php 
                                                    $amountCompare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,$row4->code,'1',1,0);
                                                    $total_expense_amount[] = $amountCompare; 
                                                    $expenseCompareArray[$fmRow] = [$amountCompare];
                                                    if ($amountCompare<0):
                                                        $amountCompare=($amountCompare*-1);
                                                        $amountCompare=number_format($amountCompare);
                                                        $amountCompare='('.$amountCompare.')';
                                                    else:
                                                        $amountCompare=number_format($amountCompare);
                                                    endif;
                                                    echo $amountCompare;
                                                ?>
                                            </th>
                                    <?php }?>
                                            <td  style="text-align: left !important;" class="text-right">
                                                @php echo number_format(array_sum($total_expense_amount),2); @endphp
                                            </td>


                                        <th class="hide" style="text-align: left;">
                                            @php
                                                $expenseArrayTotal = array_sum(array_map('current', $expenseArray)) - array_sum(array_map('current', $expenseCompareArray));
                                                if($expenseArrayTotal < 0 ):
                                                    echo "(".number_format(abs((float)$expenseArrayTotal)).")";
                                                else:
                                                echo number_format($expenseArrayTotal);
                                                endif;        
                                            @endphp
                                        </th>

                                </tr>
                    <?php
                            }
                        endforeach;
                    ?>
                    {{-- Expense End --}}

                    {{-- Other Income Start --}}
                    <?php 
                        foreach ($otherIncomeAccount as $row7) {
                            $head = strlen($row7->code);
                            $level = count(explode('-',$row7->code));
                            $dCounter++;
                            $headWiseTotalAmount = 0;
                            $headWiseTotalAmountCompare = 0 ;

                            if($dCounter == 1){
                                echo '<tr><td colspan="50">Other Income</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="otherIncomeRecordRow_<?php echo $dCounter?>">
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#"><?php echo strtoupper($row7->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row7->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row7->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row7->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row7->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row7->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#"><?php echo  '<span class="SpacesCls"></span>'. $row7->name?></a>
                                    <?php endif;?>
                                </td>
                                <?php
                                    $other_income_amount = [];
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                        $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                ?>
                                        <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                            <?php 
                                                $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row7->code,'1',1,0);
                                                $other_income_amount[] = $amount;

                                                $other_total += $amount; 
                                                if($amount != 0){
                                                    $headWiseTotalAmount = 1;
                                                }
                                                if ($amount<0):
                                                    $amount=($amount*-1);
                                                    $amount=number_format($amount);
                                                    $amount='('.$amount.')';
                                                else:
                                                    $amount=number_format($amount);
                                                endif;
                                                echo $amount;

                                            ?>
                                        </td>
                                
                                <?php }?>
                                        
                                        <td  style="text-align: left !important;" class="text-right">
                                            @php echo number_format(array_sum($other_income_amount),2); @endphp
                                        </td>
                              
                                <?php
                                    $other_income_amount = [];
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                        $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                                ?>
                                       
                                        <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                            <?php 
                                                $amountCompare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,$row7->code,'1',1,0);
                                                $other_income_amount[] = $amountCompare;

                                                $other_total_compare += $amountCompare; 
                                                if($amountCompare != 0){
                                                    $headWiseTotalAmountCompare = 1;
                                                }
                                                if ($amountCompare<0):
                                                    $amountCompare=($amountCompare*-1);
                                                    $amountCompare=number_format($amountCompare);
                                                    $amountCompare='('.$amountCompare.')';
                                                else:
                                                    $amountCompare=number_format($amountCompare);
                                                endif;
                                                echo $amountCompare;

                                            ?>
                                        </td>
                                <?php }?>
                                        
                                        <td  style="text-align: left !important;" class="text-right">
                                            @php echo number_format(array_sum($other_income_amount),2); @endphp
                                        </td>

                                <td class="hide" style="text-align: left;">
                                    @php
                                        $other_total_sum = $other_total - $other_total_compare;
                                        if($other_total_sum < 0 ):
                                            echo "(".abs((float)$other_total_sum).")";
                                        else:
                                           echo number_format($other_total_sum);
                                        endif;
                                        
                                    @endphp
                                </td>
                            </tr>
                    <?php
                            }
                    ?>
                        <script>
                            hideExpenseRecordRow('otherIncomeRecordRow_','<?php echo $dCounter?>','<?php echo $headWiseTotalAmount?>');
                            hideExpenseRecordRow('otherIncomeRecordRow_','<?php echo $dCounter?>','<?php echo $headWiseTotalAmountCompare?>');
                        </script>
                    <?php
                        }
                    ?>
                    <?php 
                    foreach ($otherIncomeAccount as $row8) {
                        $head = strlen($row8->code);
                        $level = count(explode('-',$row8->code));
                        $dCounterTwo++;
                        $headWiseTotalAmount = 0;
                        $headWiseTotalAmountCompare = 0 ;

                        if($dCounterTwo == 1){
                        //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                ?>
                        <tr>
                            <th>Total Other Income</th>
                            <?php
                                $total_other_income_amount = [];
                                foreach($filterMonth as $fmRow){
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                    $to_date = date($filterYear.'-'.$makeMNumber.'-t'); 
                            ?>
                                    <th <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                        <?php 
                                            $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row8->code,'1',1,0);
                                            $otherIncomeArray[$fmRow] = [$amount];
                                            $total_other_income_amount[] = $amount;
                                            if($amount != 0){
                                                $headWiseTotalAmount = 1;
                                            }
                                            if ($amount<0):
                                                $amount=($amount*-1);
                                                $amount=number_format($amount);
                                                $amount='('.$amount.')';
                                            else:
                                                $amount=number_format($amount);
                                            endif;
                                            echo $amount;
                                        ?>
                                    </th>
                                
                            <?php }?>

                            
                                    <td  style="text-align: left !important;" class="text-right">
                                        @php echo number_format(array_sum($total_other_income_amount),2); @endphp
                                    </td>
                      
                            <?php
                                $total_other_income_amount = [];
                                foreach($filterMonth as $fmRow){
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date_compare = date($compareYear.'-'.$makeMNumber.'-01');
                                    $to_date_compare = date($compareYear.'-'.$makeMNumber.'-t');
                            ?>
                                  
                                    <th <?php if($head==3){ ?> style="font-size: large;font-weight: bolder;text-align: left;" <?php } ?> class="text-right" style="text-align: left;">
                                        <?php 
                                            $amountCompare = CommonHelper::get_parent_and_account_amount(1,$from_date_compare,$to_date_compare,$row8->code,'1',1,0);
                                            $otherIncomeCompareArray[$fmRow] = [$amountCompare];
                                            $total_other_income_amount[] = $amountCompare;

                                            if($amountCompare != 0){
                                                $headWiseTotalAmountCompare = 1;
                                            }
                                            if ($amountCompare<0):
                                                $amountCompare=($amountCompare*-1);
                                                $amountCompare=number_format($amountCompare);
                                                $amountCompare='('.$amountCompare.')';
                                            else:
                                                $amountCompare=number_format($amountCompare);
                                            endif;
                                            echo $amountCompare;
                                        ?>
                                    </th>
                            <?php }?>

                            
                                    <td  style="text-align: left !important;" class="text-right">
                                        @php echo number_format(array_sum($total_other_income_amount),2); @endphp
                                    </td>
            
            
            
                            <th class="hide" style="text-align: left;">
                             
                                @php
                                    $otherIncomeArrayTotal = array_sum(array_map('current', $otherIncomeArray)) - array_sum(array_map('current', $otherIncomeCompareArray));
                                    if($otherIncomeArrayTotal < 0 ):
                                        echo "(".number_format(abs((float)$otherIncomeArrayTotal)).")";
                                    else:
                                    echo number_format($otherIncomeArrayTotal);
                                    endif;        
                                @endphp 
                             
                            </th>
                        </tr>
                <?php
                        }
                    }
                ?>
                    {{-- Other Income End --}}



                    {{-- Net Profit Start --}}

                    <tr>
                        <th> Net Profit</th>
                        <?php

                            $net_profit_amount = [];
                            foreach($filterMonth as $fmRow){
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                        ?>
                                <th class="text-right" id="grossProfit_<?php echo $fmRow?>">

                                    @php
                                        $NetProfitTotal = $revenueArray[$fmRow][0] - $cogsArray[$fmRow][0] - $expenseArray[$fmRow][0] + $otherIncomeArray[$fmRow][0];
                                        
                                        $net_profit_amount[] = $NetProfitTotal ;

                                        if($NetProfitTotal < 0 ):
                                            echo "(".number_format(abs((float)$NetProfitTotal)).")";
                                        else:
                                        echo number_format($NetProfitTotal);
                                        endif;        
                                    @endphp 
                                </th>
                               
                        <?php }?>

                            
                                <td  style="text-align: left !important;" class="text-right">
                                    @php echo number_format(array_sum($net_profit_amount),2); @endphp
                                </td>

                        <?php

                            $net_profit_amount = [];
                            foreach($filterMonth as $fmRow){
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                        ?>
                                <th class="text-right" id="grossProfit_<?php echo $fmRow?>">
                                
                                    @php
                                        $CompareNetProfitTotal = $revenueCompareArray[$fmRow][0] - $cogsCompareArray[$fmRow][0] - $expenseCompareArray[$fmRow][0] + $otherIncomeCompareArray[$fmRow][0];

                                        $net_profit_amount[] = $CompareNetProfitTotal ;

                                        if($CompareNetProfitTotal < 0 ):
                                            echo "(".number_format(abs((float)$CompareNetProfitTotal)).")";
                                        else:
                                        echo number_format($CompareNetProfitTotal);
                                        endif;        
                                    @endphp 
                                </th>
                        <?php }?>

                            
                                <td  style="text-align: left !important;" class="text-right">
                                    @php echo number_format(array_sum($net_profit_amount),2); @endphp
                                </td>


                                
                        <th class="hide" style="text-align: left;">
                                @php
                                    $NetProfitArrayTotal = (array_sum(array_map('current', $revenueArray)) - array_sum(array_map('current', $cogsArray)) - array_sum(array_map('current', $expenseArray)) + array_sum(array_map('current', $otherIncomeArray))) - (array_sum(array_map('current', $revenueCompareArray)) - array_sum(array_map('current', $cogsCompareArray)) - array_sum(array_map('current', $expenseCompareArray)) + array_sum(array_map('current', $otherIncomeCompareArray))) ;
                                    if($NetProfitArrayTotal < 0 ):
                                        echo "(".number_format(abs((float)$NetProfitArrayTotal)).")";
                                    else:
                                    echo number_format($NetProfitArrayTotal);
                                    endif;        
                                @endphp
                        </th>
                    </tr>
                    
                    {{-- Net Profit End --}}
                </tbody>
            </table> 
        </div>
    </div>
</div>