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

<style>
/* ===================================================================== PROFIT & LOSS (Comparative) — LAYOUT (navy/lavender/amber report theme) ===================================================================== */
.pl-wrapper .table-responsive{background:#ffffff !important;border:1px solid #EDF0F8 !important;border-radius:16px !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;}
table.Profit_Loss{width:100% !important;border-collapse:collapse !important;margin:0 !important;}
table.Profit_Loss th,table.Profit_Loss td{white-space:nowrap !important;}
table.Profit_Loss thead th{background:#F0F3FB !important;color:#4A5268 !important;font-size:11.5px !important;font-weight:800 !important;letter-spacing:.4px !important;text-transform:uppercase !important;padding:12px 10px !important;text-align:right !important;border:none !important;border-bottom:2px solid #E3E7F3 !important;}
table.Profit_Loss thead th:first-child{text-align:left !important;min-width:220px !important;white-space:normal !important;}
table.Profit_Loss thead th:not(:first-child){min-width:110px !important;}
table.Profit_Loss tbody td,table.Profit_Loss tbody th{padding:10px !important;font-size:13px !important;font-weight:600 !important;color:#1B2333 !important;text-align:right !important;border:none !important;border-bottom:1px solid #F0F2F8 !important;vertical-align:middle !important;}
table.Profit_Loss tbody td:first-child,table.Profit_Loss tbody th:first-child{text-align:left !important;}
table.Profit_Loss tbody tr:hover td{background:#FAFBFE !important;}
table.Profit_Loss a{color:inherit !important;text-decoration:none !important;}
/* section headers:Revenue / Cost of Goods Sold / Expense / Other Income */
.pl-section-row td{background:#EEF1FA !important;font-size:14.5px !important;color:#0B1F59 !important;padding:13px 10px !important;text-transform:uppercase !important;letter-spacing:.4px !important;border-top:2px solid #E3E7F3 !important;border-bottom:2px solid #E3E7F3 !important;}
/* subtotal rows:Total Revenue / Total COGS / Total Expense / Total Other Income */
.pl-total-row td,.pl-total-row th{background:#F7F9FD !important;font-weight:800 !important;color:#1E3A8A !important;border-top:2px solid #E3E7F3 !important;border-bottom:2px solid #E3E7F3 !important;}
/* Gross Profit row */
.pl-gross-profit-row th,.pl-gross-profit-row td{background:#FFF4E5 !important;color:#B5651D !important;font-weight:800 !important;font-size:14.5px !important;border-top:2px solid #F3D9AE !important;border-bottom:2px solid #F3D9AE !important;}
/* Net Profit row */
.pl-net-profit-row th,.pl-net-profit-row td{background:#173ca7d1 !important;color:#ffffff !important;font-weight:800 !important;font-size:14.5px !important;border:none !important;padding:13px 10px !important;}
.pl-net-profit-row th:first-child{border-top-left-radius:10px !important;border-bottom-left-radius:10px !important;}
.pl-net-profit-row th:last-child,.pl-net-profit-row td:last-child{border-top-right-radius:10px !important;border-bottom-right-radius:10px !important;}
/* spacer row between sections */
.pl-spacer-row td{padding:6px !important;border:none !important;background:transparent !important;}
.report-header{background:linear-gradient(135deg,#eef1fb,#f7f8fd);border-radius:14px;border:1px solid #e3e7f5;padding:22px 28px 16px 28px;margin-bottom:24px;position:relative;text-align:center;}
.report-header .company-name{font-size:22px;font-weight:500;color:#1c2b4a;margin-bottom:6px;}
.report-header .report-title{font-size:16px;font-weight:500;color:#4a5aa8;margin-bottom:10px;}
.report-header .report-range{font-size:13.5px;color:#3a4256;font-weight:500;}
.report-header .report-range b{color:#1c2b4a;}
.report-header .printed-on{position:absolute;top:18px;right:22px;font-size:12.5px;font-weight:500;color:#6b7280;}

/* =====================================================================
   ACCORDION BEHAVIOUR - section headers clickable, detail rows collapse
   Smooth open/close is done via a max-height transition on an inner
   wrapper div inside every cell (animating a <tr>'s height directly is
   unreliable across browsers, so this is the robust way to do it).
   ===================================================================== */
.pl-section-row{cursor:pointer !important;user-select:none !important;}
.pl-section-row:hover td{background:#e7ebfa !important;}
.pl-section-row.active td{background:#e2e7f8 !important;}

/* chevron arrow - a clean CSS triangle, rotates 90deg when section is open */
.pl-section-row .acc-arrow{
    display:inline-block;
    width:0;height:0;
    margin-right:10px;
    border-top:5px solid transparent;
    border-bottom:5px solid transparent;
    border-left:7px solid #0B1F59;
    vertical-align:middle;
    transition:transform .25s ease;
}
.pl-section-row.active .acc-arrow{transform:rotate(90deg);}

/* detail rows always exist in the DOM (tr height can't animate reliably),
   the cell padding is stripped to 0 and the real content lives in
   .acc-cell-inner, whose max-height/opacity/padding is what animates. */
table.Profit_Loss tbody tr.acc-detail-row td,
table.Profit_Loss tbody tr.acc-detail-row th{
    padding:0 !important;
}
.acc-cell-inner{
    display:block;
    max-height:0;
    opacity:0;
    overflow:hidden;
    padding:0 10px;
    transition:max-height .32s ease, opacity .28s ease, padding .32s ease;
}
table.Profit_Loss tbody tr.acc-detail-row.open .acc-cell-inner{
    max-height:60px;
    opacity:1;
    padding:10px;
}
</style>

<div class="row pl-wrapper">
    <div class="report-header">
        <div class="printed-on">Printed On: {{ date('F d, Y') }}</div>
        <div class="company-name">{!! CommonHelper::get_company_name($CompanyId) !!}</div>
        <div class="report-title">Profit & Loss — Comparative</div>
        <div class="report-range">
            <b>{{ $filterYear }}</b> vs <b>{{ $compareYear }}</b>
        </div>
    </div>
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
                            $accColspan = (2 * count($filterMonth)) + 3;
                            
                        CommonHelper::reconnectMasterDatabase();

                        foreach($revenueAccount as $row1):
                            $head = strlen($row1->code);
                            $level = count(explode('-',$row1->code));
                            $paramOne = "fdc/getSummaryLedgerDetail?m=".$CompanyId;
                            $counter++;
                            if($counter == 1){
                                echo '<tr class="pl-section-row" data-group="revenue"><td style="font-weight: bold" colspan="'.$accColspan.'"><span class="acc-arrow"></span>Revenue</td></tr>';
                            }else{
                    ?>
                            <tr class="acc-detail-row" data-group="revenue">
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?>>
                                    <div class="acc-cell-inner">
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
                                    </div>
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
                                            <div class="acc-cell-inner">
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
                                            </div>
                                        </td>
                                        
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            <div class="acc-cell-inner">
                                            @php echo number_format(array_sum($revenue_amount),2); @endphp
                                            </div>
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
                                            <div class="acc-cell-inner">
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
                                            </div>
                                        </td>
                                
                                <?php }?>

                                        <td  style="text-align: left !important;" class="text-right">
                                            <div class="acc-cell-inner">
                                            @php echo number_format(array_sum($revenue_amount),2); @endphp
                                            </div>
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
                                <tr class="acc-detail-row pl-total-row" data-group="revenue">
                                    <th><div class="acc-cell-inner">Total Revenue</div></th>
                                    
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
                                                <div class="acc-cell-inner">
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
                                                </div>
                                            </th>
                                    <?php }?>
                                            <td  style="text-align: left !important;" class="text-right">
                                                <div class="acc-cell-inner">
                                                @php echo number_format(array_sum($total_revenue_amount),2); @endphp
                                                </div>
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
                                                <div class="acc-cell-inner">
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
                                                </div>
                                            </th>
                                    <?php }?>
                                            <td  style="text-align: left !important;" class="text-right">
                                                <div class="acc-cell-inner">
                                                @php echo number_format(array_sum($total_revenue_amount),2); @endphp
                                                </div>
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
                    <tr class="acc-detail-row pl-spacer-row" data-group="revenue">
                        <td colspan="100"><div class="acc-cell-inner">&nbsp;</div></td>
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
                                echo '<tr class="pl-section-row" data-group="cogs"><td colspan="'.$accColspan.'"><span class="acc-arrow"></span>Cost of Goods Sold</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="costOfGoodsSoldRecordRow_<?php echo $cCounter?>" class="acc-detail-row" data-group="cogs">
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <div class="acc-cell-inner">
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
                                    </div>
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
                                            <div class="acc-cell-inner">
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
                                            </div>
                                        </td>
                                    
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            <div class="acc-cell-inner">
                                            @php echo number_format(array_sum($cogs_amount),2); @endphp
                                            </div>
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
                                            <div class="acc-cell-inner">
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
                                            </div>
                                        </td>
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            <div class="acc-cell-inner">
                                            @php echo number_format(array_sum($cogs_amount),2); @endphp
                                            </div>
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
                        <tr class="acc-detail-row pl-total-row" data-group="cogs">
                            <td><div class="acc-cell-inner">Total Cost of Goods Sold</div></td>
                            
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
                                        <div class="acc-cell-inner">
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
                                        </div>
                                    </td>
                                    
                            <?php }?>

                                    <td  style="text-align: left !important;" class="text-right">
                                        <div class="acc-cell-inner">
                                        @php echo number_format(array_sum($total_cogs_amount),2); @endphp
                                        </div>
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
                                        <div class="acc-cell-inner">
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
                                        </div>
                                    </td>
                            <?php }?>

                                    <td  style="text-align: left !important;" class="text-right">
                                        <div class="acc-cell-inner">
                                        @php echo number_format(array_sum($total_cogs_amount),2); @endphp
                                        </div>
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

                    <tr class="acc-detail-row pl-gross-profit-row" data-group="cogs">
                        <th style="font-weight: bold;" ><div class="acc-cell-inner">Gross Profit</div></th>
                        
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
                                <th class="text-right" id="grossProfit_<?php echo $fmRow?>">
                                    <div class="acc-cell-inner">
                                    <?php 
                                            $gross_profit_amount[] = $revenueArray[$fmRow][0] - $cogsArray[$fmRow][0];
                                            echo ($revenueArray[$fmRow][0] - $cogsArray[$fmRow][0]);
                                            
                                    ?>
                                    </div>
                                </th>
                        <?php }?>
                                <td  style="text-align: left !important;" class="text-right">
                                    <div class="acc-cell-inner">
                                    @php echo number_format(array_sum($gross_profit_amount),2); @endphp
                                    </div>
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
                                <th class="text-right" id="grossProfit_<?php echo $fmRow?>">
                                    <div class="acc-cell-inner">
                                    <?php
                                            $gross_profit_amount[] = $revenueCompareArray[$fmRow][0] - $cogsCompareArray[$fmRow][0];
                                            echo ($revenueCompareArray[$fmRow][0] - $cogsCompareArray[$fmRow][0]);
                                    ?>
                                    </div>
                                </th>
                        <?php }?>

                                <td  style="text-align: left !important;" class="text-right">
                                    <div class="acc-cell-inner">
                                    @php echo number_format(array_sum($gross_profit_amount),2); @endphp
                                    </div>
                                </td>

                        <th class="hide" style="text-align: left;">
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

                    <tr class="acc-detail-row pl-spacer-row" data-group="cogs">
                        <td colspan="100"><div class="acc-cell-inner">&nbsp;</div></td>
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
                                echo '<tr class="pl-section-row" data-group="expense"><td style="font-weight: bold"  colspan="'.$accColspan.'"><span class="acc-arrow"></span>Expense</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="expenseRecordRow_<?php echo $bCounter?>" class="acc-detail-row" data-group="expense">
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?>  >
                                    <div class="acc-cell-inner">
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
                                    </div>
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
                                            <div class="acc-cell-inner">
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
                                            </div>
                                        </td>
                                        
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            <div class="acc-cell-inner">
                                            @php echo number_format(array_sum($expense_amount),2); @endphp
                                            </div>
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
                                            <div class="acc-cell-inner">
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
                                            </div>
                                        </td>
                                <?php }?>
                                        <td  style="text-align: left !important;" class="text-right">
                                            <div class="acc-cell-inner">
                                            @php echo number_format(array_sum($expense_amount),2); @endphp
                                            </div>
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
                                <tr class="acc-detail-row pl-total-row" data-group="expense">
                                    <th><div class="acc-cell-inner">Total Expense</div></th>
                                    
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
                                                <div class="acc-cell-inner">
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
                                                </div>
                                            </th>
                                            
                                    <?php }?>
                                            <td  style="text-align: left !important;" class="text-right">
                                                <div class="acc-cell-inner">
                                                @php echo number_format(array_sum($total_expense_amount),2); @endphp
                                                </div>
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
                                                <div class="acc-cell-inner">
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
                                                </div>
                                            </th>
                                    <?php }?>
                                            <td  style="text-align: left !important;" class="text-right">
                                                <div class="acc-cell-inner">
                                                @php echo number_format(array_sum($total_expense_amount),2); @endphp
                                                </div>
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
                                echo '<tr class="pl-section-row" data-group="other"><td colspan="'.$accColspan.'"><span class="acc-arrow"></span>Other Income</td></tr>';
                            }else{
                            //$amount = CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);
                    ?>
                            <tr id="otherIncomeRecordRow_<?php echo $dCounter?>" class="acc-detail-row" data-group="other">
                                <td class="text-left" <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <div class="acc-cell-inner">
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
                                    </div>
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
                                            <div class="acc-cell-inner">
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
                                            </div>
                                        </td>
                                
                                <?php }?>
                                        
                                        <td  style="text-align: left !important;" class="text-right">
                                            <div class="acc-cell-inner">
                                            @php echo number_format(array_sum($other_income_amount),2); @endphp
                                            </div>
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
                                            <div class="acc-cell-inner">
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
                                            </div>
                                        </td>
                                <?php }?>
                                        
                                        <td  style="text-align: left !important;" class="text-right">
                                            <div class="acc-cell-inner">
                                            @php echo number_format(array_sum($other_income_amount),2); @endphp
                                            </div>
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
                        <tr class="acc-detail-row pl-total-row" data-group="other">
                            <th><div class="acc-cell-inner">Total Other Income</div></th>
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
                                        <div class="acc-cell-inner">
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
                                        </div>
                                    </th>
                                
                            <?php }?>

                            
                                    <td  style="text-align: left !important;" class="text-right">
                                        <div class="acc-cell-inner">
                                        @php echo number_format(array_sum($total_other_income_amount),2); @endphp
                                        </div>
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
                                        <div class="acc-cell-inner">
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
                                        </div>
                                    </th>
                            <?php }?>

                            
                                    <td  style="text-align: left !important;" class="text-right">
                                        <div class="acc-cell-inner">
                                        @php echo number_format(array_sum($total_other_income_amount),2); @endphp
                                        </div>
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

                    <tr class="pl-net-profit-row">
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

<?php
    // NEW: chart data for the parent page's Line/Bar/Pie/Pivot switcher.
    // This view mixes two years, so the chart shows the FILTER YEAR totals only
    // (recomputed fresh here from the per-month arrays already filled above);
    // the note tag below tells the user that's what they're looking at.
    $plChartRevenue     = isset($revenueArray) ? array_sum(array_map('current', $revenueArray)) : 0;
    $plChartCogs        = isset($cogsArray) ? array_sum(array_map('current', $cogsArray)) : 0;
    $plChartExpense     = isset($expenseArray) ? array_sum(array_map('current', $expenseArray)) : 0;
    $plChartOtherIncome = isset($otherIncomeArray) ? array_sum(array_map('current', $otherIncomeArray)) : 0;
    $plChartNetProfit   = $plChartRevenue - $plChartCogs - $plChartExpense + $plChartOtherIncome;
?>
<script type="application/json" id="plChartDataJson">
{"labels":["Revenue","COGS","Expense","Other Income","Net Profit"],"values":[<?php echo json_encode((float)$plChartRevenue);?>,<?php echo json_encode((float)$plChartCogs);?>,<?php echo json_encode((float)$plChartExpense);?>,<?php echo json_encode((float)$plChartOtherIncome);?>,<?php echo json_encode((float)$plChartNetProfit);?>]}
</script>
<script type="text/plain" id="plChartReportNote">Chart sirf Filter Year ({{ $filterYear }}) ke totals dikha raha hai — table mein Compare Year ({{ $compareYear }}) side-by-side mojood hai.</script>

<script>
$(document).ready(function () {

    // Accordion behaviour: click a section header (Revenue / Cost of Goods Sold /
    // Expense / Other Income) -> its detail rows + section total (+ Gross Profit
    // for COGS) slide open together. Click again -> close. Opening one section
    // auto-closes whichever other section was open. Net Profit always stays visible.
    $(document).off('click', '.pl-section-row').on('click', '.pl-section-row', function () {

        var $clickedHeader = $(this);
        var group = $clickedHeader.data('group');
        var wasActive = $clickedHeader.hasClass('active');

        // close every section first (only one section open at a time)
        $('.pl-section-row').removeClass('active');
        $('.acc-detail-row').removeClass('open');

        // if it was already open, clicking again just leaves everything closed.
        // if it was closed, open this one.
        if (!wasActive) {
            $clickedHeader.addClass('active');
            $('.acc-detail-row[data-group="' + group + '"]').addClass('open');
        }
    });

});
</script>