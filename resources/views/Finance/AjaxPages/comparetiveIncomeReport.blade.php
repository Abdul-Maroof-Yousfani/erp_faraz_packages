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

<style>
/* ===================================================================== PROFIT & LOSS — LAYOUT (navy/lavender/amber report theme) ===================================================================== */
.pl-wrapper .table-responsive{background:#ffffff !important;border:1px solid #EDF0F8 !important;border-radius:16px !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;overflow-x:auto !important;}
table.Profit_Loss{width:100% !important;border-collapse:collapse !important;margin:0 !important;}
table.Profit_Loss thead th{background:#F0F3FB !important;color:#4A5268 !important;font-size:11.5px !important;font-weight:500 !important;letter-spacing:.4px !important;text-transform:uppercase !important;padding:12px 10px !important;text-align:right !important;border:none !important;border-bottom:2px solid #E3E7F3 !important;}
table.Profit_Loss thead th:first-child{text-align:left !important;border-top-left-radius:10px !important;}
table.Profit_Loss thead th:last-child{border-top-right-radius:10px !important;}
table.Profit_Loss tbody td,table.Profit_Loss tbody th{padding:10px !important;font-size:13px !important;font-weight:500 !important;color:#1B2333 !important;text-align:right !important;border:none !important;border-bottom:1px solid #F0F2F8 !important;vertical-align:middle !important;}
table.Profit_Loss tbody td:first-child,table.Profit_Loss tbody th:first-child{text-align:left !important;}
table.Profit_Loss tbody tr:hover td{background:#FAFBFE !important;}
table.Profit_Loss a{color:inherit !important;text-decoration:none !important;}
/* section headers:Revenue / Cost of Goods Sold / Expense / Other Income */
.pl-section-row td{background:#EEF1FA !important;font-size:14.5px !important;font-weight:500 !important;color:#0B1F59 !important;padding:13px 10px !important;text-transform:uppercase !important;letter-spacing:.4px !important;border-top:2px solid #E3E7F3 !important;border-bottom:2px solid #E3E7F3 !important;}
/* top-level account rows (level 1 / head==3) */
.pl-level-1{font-size:13.5px !important;font-weight:500 !important;color:#0B1F59 !important;}
.pl-level-detail{font-weight:500 !important;color:#4A5268 !important;}
.pl-indent{display:inline-block !important;}
/* subtotal rows:Total Revenue / Total COGS / Total Expense / Total Other Income */
.pl-total-row td,.pl-total-row th{background:#F7F9FD !important;font-weight:500 !important;color:#1E3A8A !important;border-top:2px solid #E3E7F3 !important;border-bottom:2px solid #E3E7F3 !important;}
/* Gross Profit row */
.pl-gross-profit-row th{background:#FFF4E5 !important;color:#B5651D !important;font-weight:500 !important;font-size:14.5px !important;border-top:2px solid #F3D9AE !important;border-bottom:2px solid #F3D9AE !important;}
/* Net Profit row */
.pl-net-profit-row th{background:#173ca7d1 !important;color:#ffffff !important;font-weight:500 !important;font-size:15.5px !important;border:none !important;padding:14px 10px !important;}
.pl-net-profit-row:first-child th:first-child{border-top-left-radius:10px !important;}
.pl-net-profit-row th:last-child{border-top-right-radius:10px !important;}
/* spacer row between sections */
.pl-spacer-row td{padding:6px !important;border:none !important;background:transparent !important;}
 .report-header{background:linear-gradient(135deg,#eef1fb,#f7f8fd);border-radius:14px;border:1px solid #e3e7f5;padding:22px 28px 16px 28px;margin-bottom:24px;position:relative;text-align:center;}
.report-header .company-name{font-size:22px;font-weight:500;color:#1c2b4a;margin-bottom:6px;}
.report-header .report-title{font-size:16px;font-weight:500;color:#4a5aa8;margin-bottom:10px;}
.report-header .report-range{font-size:13.5px;color:#3a4256;font-weight:500;}
.report-header .report-range b{color:#1c2b4a;}
.report-header .printed-on{position:absolute;top:18px;right:22px;font-size:12.5px;font-weight:500;color:#6b7280;}
</style>

<div class="row pl-wrapper">
    <?php
    $__firstMonth = min($filterMonth);
    $__lastMonth  = max($filterMonth);
    $__fromStr = date('F d, Y', mktime(0,0,0,$__firstMonth,1,$filterYear));
    $__toStr   = date('F d, Y', mktime(0,0,0,$__lastMonth,date('t',mktime(0,0,0,$__lastMonth,1,$filterYear)),$filterYear));
?>

<div class="report-header">
    <div class="printed-on">Printed On: {{ date('F d, Y') }}</div>
    <div class="company-name">{!! CommonHelper::get_company_name($CompanyId) !!}</div>
    <div class="report-title">Profit & Loss</div>
    <div class="report-range">
        FROM <b>{{ $__fromStr }}</b> TO <b>{{ $__toStr }}</b>
    </div>
</div>
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
                                echo '<tr class="pl-section-row"><td colspan="50">Revenue</td></tr>';
                            }else{
                    ?>
                            <tr>
                                <td class="text-left <?php if($head==3){ echo 'pl-level-1'; } else { echo 'pl-level-detail'; } ?>" style="padding-left: <?php echo 10 + (($level - 1) * 16); ?>px;">
                                    <?php if($level == 1):?>
                                        <a href="#"><?php echo strtoupper($row1->name)?></a>
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
                                    <td class="text-right <?php if($head==3){ echo 'pl-level-1'; } ?>">
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
                                <td class="text-right">
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
                                <tr class="pl-total-row">
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
                                        <th class="text-right">
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
                                    
                                    <th class="text-right">
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
                    <tr class="pl-spacer-row">
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
                                echo '<tr class="pl-section-row"><td colspan="50">Cost of Goods Sold</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="costOfGoodsSoldRecordRow_<?php echo $cCounter?>">
                                <td colspan="2" class="text-left <?php if($head==3){ echo 'pl-level-1'; } else { echo 'pl-level-detail'; } ?>" style="padding-left: <?php echo 10 + (($level - 1) * 16); ?>px;">
                                    <?php if($level == 1):?>
                                        <a href="#"><?php echo strtoupper($row5->name)?></a>
                                    <?php elseif($level == 2):?>
                                        <a href="#"><?php echo  ''. $row5->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#"><?php echo  ''. $row5->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#"><?php echo  ''. $row5->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#"><?php echo  ''. $row5->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#"><?php echo  ''. $row5->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#"><?php echo  ''. $row5->name?></a>
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
                                    <td  class="text-right <?php if($head==3){ echo 'pl-level-1'; } ?>">
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
                        <tr class="pl-total-row">
                            <th>Total Cost of Goods Sold</th>
                            <?php
                                foreach($filterMonth as $fmRow){
                                    $makeMNumber = $fmRow;
                                    if($fmRow < 10){
                                        $makeMNumber = '0'.$fmRow;
                                    }
                                    $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                    $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                            ?>
                                <th class="text-right">
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
                                </th>
                            <?php }?>
                           <th class="text-right">
                            
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
                    {{-- Cost Of Goods Sold End --}}

                    {{-- Gross Profit Start --}}

                    <tr class="pl-gross-profit-row">
                        <th>Gross Profit</th>
                        <?php
                            foreach($filterMonth as $fmRow){
                                $makeMNumber = $fmRow;
                                if($fmRow < 10){
                                    $makeMNumber = '0'.$fmRow;
                                }
                                $from_date = date($filterYear.'-'.$makeMNumber.'-01');
                                $to_date = date($filterYear.'-'.$makeMNumber.'-t');
                        ?>
                            <th class="text-right" id="grossProfit_<?php echo $fmRow?>"><?php echo $revenueArray[$fmRow][0] - $cogsArray[$fmRow][0];?></th>
                        <?php }?>
                        <th class="text-right">
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

                    <tr class="pl-spacer-row">
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
                                echo '<tr class="pl-section-row"><td colspan="50">Expense</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="expenseRecordRow_<?php echo $bCounter?>">
                                <td class="text-left <?php if($head==3){ echo 'pl-level-1'; } else { echo 'pl-level-detail'; } ?>" style="padding-left: <?php echo 10 + (($level - 1) * 16); ?>px;">
                                    <?php if($level == 1):?>
                                        <a href="#"><?php echo strtoupper($row3->name)?></a>
                                    <?php elseif($level == 2):?>
                                        <a href="#"><?php echo  ''. $row3->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#"><?php echo  ''. $row3->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#"><?php echo  ''. $row3->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#"><?php echo  ''. $row3->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#"><?php echo  ''. $row3->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#"><?php echo  ''. $row3->name?></a>
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
                                    <td class="text-right <?php if($head==3){ echo 'pl-level-1'; } ?>">
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
                                <td class="text-right">
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
                                <tr class="pl-total-row">
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
                                        <th class="text-right">
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
                                        <th class="text-right">
                                        
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
                                echo '<tr class="pl-section-row"><td colspan="50">Other Income</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="otherIncomeRecordRow_<?php echo $dCounter?>">
                                <td class="text-left <?php if($head==3){ echo 'pl-level-1'; } else { echo 'pl-level-detail'; } ?>" style="padding-left: <?php echo 10 + (($level - 1) * 16); ?>px;">
                                    <?php if($level == 1):?>
                                        <a href="#"><?php echo strtoupper($row7->name)?></a>
                                    <?php elseif($level == 2):?>
                                        <a href="#"><?php echo  ''. $row7->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#"><?php echo  ''. $row7->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#"><?php echo  ''. $row7->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#"><?php echo  ''. $row7->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#"><?php echo  ''. $row7->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#"><?php echo  ''. $row7->name?></a>
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
                                    <td class="text-right <?php if($head==3){ echo 'pl-level-1'; } ?>">
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

                                <td class="text-right">
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
                        <tr class="pl-total-row">
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
                                <th class="text-right">
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
                            <th class="text-right">
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

                    <tr class="pl-net-profit-row">
                        <th>Net Profit</th>
                        <?php
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
                                    if($NetProfitTotal < 0 ):
                                        echo "(".number_format(abs((float)$NetProfitTotal)).")";
                                    else:
                                    echo number_format($NetProfitTotal);
                                    endif;        
                                @endphp 
                            </th>
                        <?php }?>

                        <th class="text-right">
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