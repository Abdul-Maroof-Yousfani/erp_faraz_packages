<?php

use App\Helpers\CommonHelper;
$from = $FromDate;
$to = $ToDate;
$space = "";
$class = "";
$end_credit = 0;
?>

<style>
  .tb-filter-bar{display:flex;align-items:center;flex-wrap:wrap;gap:14px;padding:14px 4px;margin-bottom:14px;background:transparent;border:none;}
.tb-filter-bar .tb-date-group{display:flex;flex-direction:column;font-size:12px;color:#5b5f7a;font-weight:600;}
.tb-filter-bar .tb-date-group input[type="date"]{margin-top:4px;border:1px solid #d7dbf0;border-radius:8px;padding:7px 10px;font-size:13px;color:#2b2f4a;}
.tb-nature-group{display:flex;gap:8px;flex-wrap:wrap;}
.tb-nature-pill{display:inline-flex;align-items:center;gap:6px;border:1px solid #dfe1ec;border-radius:999px;padding:8px 18px;font-size:13px;font-weight:600;color:#8a8fa8;cursor:pointer;background:#fff;transition:all .15s ease;user-select:none;letter-spacing:.02em;}
.tb-nature-pill input{accent-color:#8a8fa8;}
.tb-nature-pill:hover{border-color:#b9bdd6;}
.tb-nature-pill.active{border-color:#241e6b;color:#241e6b;}
.tb-nature-pill.active input{accent-color:#241e6b;}
#trial_bal .sf-report-print-table{border-collapse:collapse;width:100%;background:#fff;}
#trial_bal .sf-report-print-table thead th{background:#eef0fb;color:#241e6b;font-size:12.5px;font-weight:700;text-transform:uppercase;letter-spacing:.03em;padding:10px 8px;border:1px solid #dfe2f5;}
#trial_bal .sf-report-print-table tbody td{padding:8px 10px;font-size:13px;border:1px solid #edeef7;color:#2b2f4a;white-space:nowrap;}
#trial_bal .sf-report-print-table tbody tr{border-left:4px solid transparent;}
#trial_bal .sf-report-print-table tbody tr:nth-child(odd){background:#f8f9fe;}
#trial_bal .sf-report-print-table tbody tr:hover{background:#eef0ff;}
/* level accent stripes on the left edge of each row,matching the sample report */
 #trial_bal tr.smr-purple{border-left-color:#241e6b;}
#trial_bal tr.smr-pink{border-left-color:#241e6b;}
#trial_bal tr.smr-orange{border-left-color:#f59e0b;}
#trial_bal tr.smr-yellow{border-left-color:#f59e0b;}
#trial_bal tr.smr-lightgreen{border-left-color:#f59e0b;}
#trial_bal tr.smr-green{border-left-color:#f59e0b;}
#trial_bal tr.smr-lightblue{border-left-color:#f59e0b;}
/* level 1 & 2 category rows (e.g. "Assets","Liabilities") shown bold+dark,matching sample */
 #trial_bal tr.smr-purple td,#trial_bal tr.smr-pink td{font-weight:700;color:#1f2440;}
#trial_bal .link_hide{cursor:pointer;color:#3452d1;}
#trial_bal tr.smr-purple .link_hide,#trial_bal tr.smr-pink .link_hide{color:#1f2440;}
#trial_bal .link_hide:hover{color:#3730a3;text-decoration:underline;}
#trial_bal .sf-table-total{background:#eafbea !important;border-top:2px solid #22c55e;font-weight:700;}
#trial_bal .sf-table-total td{color:#14532d;}
#trial_bal tr.tb-diff-row td{font-weight:700;background:#fff7e6;color:#92400e;}
#trial_bal .bg-danger{background:#fde2e2 !important;color:#991b1b !important;}
.table-responsive{height:600px;}
#trial_bal{margin-top:0px !important;}
span.SpacesCls{padding-left:20px;}
</style>

<div class="">

    <form class="tb-filter-bar" method="GET" action="{{ url()->current() }}" id="tbFilterForm">
        <div class="tb-date-group">
            From Date
            <input type="date" name="from" value="<?php echo date_format(date_create($from),'Y-m-d'); ?>" onchange="document.getElementById('tbFilterForm').submit()">
        </div>
        <div class="tb-date-group">
            To Date
            <input type="date" name="to" value="<?php echo date_format(date_create($to),'Y-m-d'); ?>" onchange="document.getElementById('tbFilterForm').submit()">
        </div>

        <div class="tb-nature-group" id="tbNatureGroup">
            <label class="tb-nature-pill"><input type="radio" name="nature" value=""  checked> ALL</label>
            <label class="tb-nature-pill"><input type="radio" name="nature" value="1"> ASSETS</label>
            <label class="tb-nature-pill"><input type="radio" name="nature" value="2"> LIABILITIES</label>
            <label class="tb-nature-pill"><input type="radio" name="nature" value="3"> CAPITAL</label>
            <label class="tb-nature-pill"><input type="radio" name="nature" value="4"> EXPENSES</label>
            <label class="tb-nature-pill"><input type="radio" name="nature" value="5"> REVENUE</label>
        </div>
    </form>
    <?php echo CommonHelper::headerPrintSectionInPrintView(Session::get('run_company'), 'Trial Balance 5th Column', date_format(date_create($from),'d-m-Y').' To '.date_format(date_create($to),'d-m-Y')); ?>

    <div class="table-responsive" id="trial_bal">

        <table class="table table-bordered sf-report-print-table" id="table_export1">
            <thead>
                <th colspan="3" class="text-center"></th>
                <th colspan="2" class="text-center">Opening Balance</th>
                <th colspan="2" class="text-center">Transactions</th>
                <th colspan="2" class="text-center">Closing Balance</th>
            </thead>

            <thead>
            <tr>
                <th class="text-center">Sr.No</th>
                <th class="text-center">Code</th>
                <th class="text-center">Account Name</th>
                <th class="text-center">Open.Dr</th>
                <th class="text-center">Open.Cr</th>
                <th class="text-center">Dr During The Period</th>
                <th class="text-center">Cr During The Period</th>
                <th class="text-center">End.Dr</th>
                <th class="text-center">End.Cr</th>
            </tr>
            </thead>
            <tbody id="tbl_id">
            <?php

            $accounts = DB::Connection('mysql2')->select("SELECT * FROM accounts where `status` = 1 order by `level1`,`level2`,`level3`,`level4`,`level5`,`level6`,`level7`");

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
            <tr data-nature="<?php echo $nature; ?>" class="<?php if($level == 1){echo 'smr-purple';}
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
                <td class="text-center"><?php echo $counter; ?></td>
                <td><?php echo "'".$code?></td>
                <td <?php  if ($row->operational==0): ?>style="font-weight: 900;"<?php endif; ?>  class="sf-uc-first text-left">

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

                <?php
                    $a = '';
                    if($nature ==01){$a='ASSETS';}
                    elseif($nature ==02){$a='LIABILITY';}
                    elseif($nature ==03){$a='EQUITY';}
                    elseif($nature ==04){$a='EXPENSES';}
                    elseif($nature ==05){$a='REVENUE';}
                    elseif($nature ==06){$a='Cost Of Sales';}

                    $len = strlen($code);
                    $bal = 0;

                    $bal = DB::Connection('mysql2')->select("select coalesce(sum(`amount`),0)-(select coalesce(sum(`amount`),0)
							from `transactions`
							where substring_index(`acc_code`,'-',$level) = '$code' and `debit_credit` = 0
							AND `status` = 1 AND `v_date` between '$from' and '$to') as bal
							from `transactions`
							where substring_index(`acc_code`,'-',$level) = '$code' and `debit_credit` = 1
							AND `status` = 1 AND `v_date` between '$from' and '$to'");

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

                <!-- Open.Dr -->
                <td class="text-right">
                    <?php if ($cl_bal > 0): echo number_format($cl_bal,2); endif; $debit_cl = $cl_bal; ?>
                </td>

                <!-- Open.Cr -->
                <td class="text-right smr-text-red">
                    <?php
                    if ($cl_bal < 0):
                        if ($nature==2 || $nature==3):
                            echo number_format(-1*$cl_bal,2);
                        else:
                            echo number_format(-1*$cl_bal,2);
                            $credit_cl = 1*$cl_bal;
                        endif;
                    endif;
                    ?>
                </td>

                <td class="text-right smr-text-red">
                    <?php echo  number_format($debit,2); ?>
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
                        echo number_format(-1*$end,2);
                        $end=1*$end;
                    endif;
                        $end_credit+=$end;
                    endif;?>
                </td>

            </tr>

            <?php
            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6')  ){$debit_total += $debit;}
            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') ){$credit_total += ($creditt*-1);}

            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') && $cl_bal>0){$debit_cl_total+=$cl_bal;}
            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') && $cl_bal<0){$credit_cl_total += ($cl_bal*-1);}

            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') && $end>0)
            {
                $debit_end_total+=$end;
            }
            if(($code == '1' ||$code == '2'||$code == '3'||$code == '4'||$code == '5' ||$code == '6') && $end<0){$credit_end_total += ($end*-1);}
            ?>

            <?php  $counter++; endforeach; ?>

            <tr class="sf-table-total">
                <td colspan="3" class="text-center"><b>Total</b></td>

                <td class="text-right"><?php echo number_format($debit_cl_total,2); ?></td>
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
            <tr class="tb-diff-row">
                <td colspan="8" class="text-right"><b>Difference</b></td>
                <td class="text-right">
                    <?php echo number_format($debit_total-$credit_total+$debit_cl_total-$credit_cl_total+$debit_end_total-$credit_end_total,2); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
(function() {
    var natureGroup = document.getElementById('tbNatureGroup');
    if (!natureGroup) return;

    function applyActivePillStyle() {
        natureGroup.querySelectorAll('.tb-nature-pill').forEach(function(pill) {
            var input = pill.querySelector('input[type="radio"]');
            pill.classList.toggle('active', input.checked);
        });
    }

    function filterRowsByNature() {
        var checked = natureGroup.querySelector('input[name="nature"]:checked');
        var nature = checked ? checked.value : '';
        var rows = document.querySelectorAll('#tbl_id tr[data-nature]');
        rows.forEach(function(row) {
            if (!nature || row.getAttribute('data-nature') === nature) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    natureGroup.querySelectorAll('input[name="nature"]').forEach(function(input) {
        input.addEventListener('change', function() {
            applyActivePillStyle();
            filterRowsByNature();
        });
    });

    applyActivePillStyle();
})();
</script>