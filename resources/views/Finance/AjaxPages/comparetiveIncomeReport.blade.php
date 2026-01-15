@php
    //echo $filterYear;
    //echo '<br />';
    //print_r($filterMonth);
    use App\Helpers\CommonHelper;

    $revenueArray = [];
    $cogsArray = [];
    $expenseArray = [];
    $otherIncomeArray = [];

    $revenue_total = 0 ;
    $expense_total = 0 ;
    $other_total = 0;
  
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped Profit_Loss">
                <thead>
                    <tr>
                        <th class="text-center">Account Name</th>
                        <?php foreach($filterMonth as $fmRow){?>

                            <th class="text-center"><?php echo  date("F", mktime(0, 0, 0, $fmRow, 10));?></th>

                        <?php }?>
                        <th class="text-center">TOTAL</th>

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
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                        $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                ?>
                                    <td <?php if($head==3){ ?> style="text-align: left;font-size: large;font-weight: bolder" <?php } ?> class="text-right"  style="text-align: left;">
                                        <?php 
                                            $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row1->code,'1',0,1);
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
                                <td style="text-align: left;">
                                    @php
                                        if($revenue_total < 0 ):
                                            echo "(".number_format(abs((float)$revenue_total)).")";
                                        else:
                                           echo number_format($revenue_total);
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
                                        foreach($filterMonth as $fmRow){
                                            $makeMNumber = $fmRow;
                                            if($fmRow < 10){
                                                $makeMNumber = '0'.$fmRow;
                                            }
                                            $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                            $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                    ?>
                                        <th <?php if($head==3){ ?> style="text-align: left;font-size: large;font-weight: bolder" <?php } ?> class="text-right" style="text-align: left;">
                                            <?php 
                                                $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row2->code,'1',0,1);
                                                $revenueArray[$fmRow] = [$amount];

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
                                    
                                    <th style="text-align: left;">
                                        <?php 
                                        $revenueArrayTotal = array_sum(array_map('current', $revenueArray)); ?>
                                    
                                        @php
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
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                        $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                ?>
                                    <td <?php if($head==3){ ?> style="text-align: left;font-size: large;font-weight: bolder" <?php } ?> class="text-right" style="text-align: left;">
                                        <?php 
                                            $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row5->code,'1',1,0);
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
                            </tr>
                    <?php
                            }
                    ?>
                        <script>
                            hideExpenseRecordRow('costOfGoodsSoldRecordRow_','<?php echo $cCounter?>','<?php echo $headWiseTotalAmount?>');
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
                        if($cCounterTwo == 1){
                        //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                ?>
                        <tr>
                            <td>Total Cost of Goods Sold</td>
                            <?php
                                foreach($filterMonth as $fmRow){
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                    $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                            ?>
                                <td <?php if($head==3){ ?> style="text-align: left;font-size: large;font-weight: bolder" <?php } ?> class="text-right" style="text-align: left;">
                                    <?php 
                                        $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row6->code,'1',1,0);
                                        $cogsArray[$fmRow] = [$amount];
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
                           <td style="text-align: left;">
                            
                                @php
                                    $otherIncomeArrayTotal = array_sum(array_map('current', $otherIncomeArray));
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
                            foreach($filterMonth as $fmRow){
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                        ?>
                            <th style="background:#dfe5ec !important;" class="text-right" id="grossProfit_<?php echo $fmRow?>"><?php echo $revenueArray[$fmRow][0] - $cogsArray[$fmRow][0];?></th>
                        <?php }?>
                        <th style="background:#dfe5ec !important;text-align: left;">
                            @php
                                $grossProfitTotal = array_sum(array_map('current', $revenueArray)) - array_sum(array_map('current', $cogsArray));
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
                            if($bCounter == 1){
                                echo '<tr><td style="font-size: 20px !important;font-weight: bold"  colspan="50">Expense</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="expenseRecordRow_<?php echo $bCounter?>">
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
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
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                        $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                ?>
                                    <td <?php if($head==3){ ?> style="text-align: left;font-size: large;font-weight: bolder" <?php } ?> class="text-right" style="text-align: left;">
                                        <?php 
                                            $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row3->code,'1',1,0);
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
                                <td style="text-align: left;">
                                    @php
                                        if($expense_total < 0 ):
                                            echo "(".number_format(abs((float)$expense_total)).")";
                                        else:
                                        echo number_format($expense_total);
                                        endif;        
                                    @endphp 
                                </td>
                            </tr>
                    <?php
                            }
                    ?>
                    <script>
                        hideExpenseRecordRow('expenseRecordRow_','<?php echo $bCounter?>','<?php echo $headWiseTotalAmount?>');
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
                                        foreach($filterMonth as $fmRow){
                                            $makeMNumber = $fmRow;
                                            if($fmRow < 10){
                                                $makeMNumber = '0'.$fmRow;
                                            }
                                            $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                            $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                    ?>
                                        <th <?php if($head==3){ ?> style="text-align: left;font-size: large;font-weight: bolder" <?php } ?> class="text-right" style="text-align: left;">
                                            <?php 
                                                $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row4->code,'1',1,0);
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
                                        <th style="text-align: left;">
                                        
                                            @php
                                                $expenseArrayTotal = array_sum(array_map('current', $expenseArray));
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
                                    foreach($filterMonth as $fmRow){
                                        $makeMNumber = $fmRow;
                                        if($fmRow < 10){
                                            $makeMNumber = '0'.$fmRow;
                                        }
                                        $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                        $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                                ?>
                                    <td <?php if($head==3){ ?> style="text-align: left;font-size: large;font-weight: bolder" <?php } ?> class="text-right" style="text-align: left;">
                                        <?php 
                                            $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row7->code,'1',1,0);
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

                                <td style="text-align: left;">
                                    @php
                                        if($other_total < 0 ):
                                            echo "(".abs((float)$other_total).")";
                                        else:
                                           echo number_format($other_total);
                                        endif;
                                        
                                    @endphp
                               </td>
                            </tr>
                    <?php
                            }
                    ?>
                        <script>
                            hideExpenseRecordRow('otherIncomeRecordRow_','<?php echo $dCounter?>','<?php echo $headWiseTotalAmount?>');
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
                        if($dCounterTwo == 1){
                        //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                ?>
                        <tr>
                            <th>Total Other Income</th>
                            <?php
                                foreach($filterMonth as $fmRow){
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                    $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                            ?>
                                <th <?php if($head==3){ ?> style="text-align: left;font-size: large;font-weight: bolder" <?php } ?> class="text-right" style="text-align: left;">
                                    <?php 
                                        $amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row8->code,'1',1,0);
                                        $otherIncomeArray[$fmRow] = [$amount];
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

                            <?php }?>
                            <th style="text-align: left;">
                                @php
                                    $otherIncomeArrayTotal = array_sum(array_map('current', $otherIncomeArray));
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
                            foreach($filterMonth as $fmRow){
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                        ?>
                            <th style="text-align: left;" class="text-right" id="grossProfit_<?php echo $fmRow?>">
                                @php
                                    $NetProfitTotal = $revenueArray[$fmRow][0] - $cogsArray[$fmRow][0] - $expenseArray[$fmRow][0] + $otherIncomeArray[$fmRow][0];
                                    if($NetProfitTotal < 0 ):
                                        echo "(".number_format(abs((float)$NetProfitTotal)).")";
                                    else:
                                    echo number_format($NetProfitTotal);
                                    endif;        
                                @endphp 
                            </th>
                        <?php }?>

                        <th style="text-align: left;">
                                @php
                                    $NetProfitArrayTotal = array_sum(array_map('current', $revenueArray)) - array_sum(array_map('current', $cogsArray)) - array_sum(array_map('current', $expenseArray)) + array_sum(array_map('current', $otherIncomeArray)) ;
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