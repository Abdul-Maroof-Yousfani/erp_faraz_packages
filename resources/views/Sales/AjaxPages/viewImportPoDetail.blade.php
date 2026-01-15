<?php use App\Helpers\CommonHelper;

$id = $_GET['id'];
$data= DB::Connection('mysql2')->table('import_po_data')
        ->where('master_id',$id)
        ->where('status',1)
        ->where('type',1)
        ->get();
$data22= DB::Connection('mysql2')->table('import_po_data')
        ->where('master_id',$id)
        ->where('status',1)
        ->where('type',2)
        ->get();
$LoopTotal = 0;
$LoopTotal2 = 0;
foreach($data as $f): $LoopTotal+=$f->amount;endforeach;
foreach($data22 as $f2): $LoopTotal2+=$f2->amount;endforeach;
$LoopTotal = $LoopTotal+$LoopTotal2;
?>
@include('number_formate')
<style>
    /*.panel{*/
        /*background-color: #e9e6ef !important;*/
    /*}*/
</style>
<div class="col-sm-12 text-right">
    <?php CommonHelper::displayPrintButtonInView('printCashSaleVoucherDetail','','1');?>
</div>
<span id="printCashSaleVoucherDetail">
<div class="row" >
    <div class="" >
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center">FCY Rate</th>
                            <th class="text-center">FCY Amount</th>
                            <th class="text-center" >Amount In Pkr</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $ImportPay = DB::Connection('mysql2')->table('import_payment')->where('import_id',$id)->where('status',1)->get();
                        $CounterP = 1;
                        $TotForeignCurrency = 0;
                        $TotAmountInPkr = 0;
                        foreach($ImportPay as $Fil):
                        $TotForeignCurrency+=$Fil->foreign_amount;
                        $TotAmountInPkr+=$Fil->amount_in_pkr;
                        ?>
                        <tr class="text-right">
                            <td><input type="text" name="CurrencyRate[]" id="CurrencyRate<?php echo $CounterP?>" value="<?php echo $Fil->cureency_rate?>" readonly  class="text-right number_format" disabled></td>
                            <td><input type="text" name="CurrencyAmount[]" id="CurrencyAmount<?php echo $CounterP?>" value="<?php echo $Fil->foreign_amount?>" readonly class="text-right foreign number_format" disabled></td>
                            <td><input type="text" name="AmountInPkr[]" id="AmountInPkr<?php echo $CounterP?>" value="<?php echo $Fil->amount_in_pkr?>" readonly  class="text-right pkr number_format" disabled></td>
                        </tr>
                        <?php
                        $CounterP++;
                        endforeach;
                        ?>
                        <tr>
                            <?php $CurrencyRate = 0;
                            if($TotAmountInPkr > 0):
                                $CurrencyRate = $TotAmountInPkr/$TotForeignCurrency;
                            else:
                                $CurrencyRate =0;
                            endif;
                            ?>
                            <th class="text-center"><?php echo number_format($CurrencyRate,2);?></th>
                            <th class="text-right number_format"><?php echo number_format($TotForeignCurrency,2)?></th>
                            <th class="text-right number_format"><?php echo number_format($TotAmountInPkr,2)?></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php

        $Duties=DB::Connection('mysql2')->select('select * from import_expense where import_id='.$id.' and status=1');

        $DCounter =0;
        $duty = 0; $DutyCounter = 0;
        $eto = 0; $EtoCounter = 0;
        $do = 0; $DoCounter = 0;
        $appraisal = 0; $AppraisalCounter = 0;
        $fright = 0; $FrightCounter = 0;
        $insurance = 0; $InsuranceCounter = 0;
        $expense = 0; $ExpenseCounter = 0;
        $other_expense = 0; $OtherExpenseCounter = 0;
        $AllExpTot = 0;
        if(count($Duties) > 0):
            foreach($Duties as $DFil):
                $DCounter++;
                $duty+=$DFil->duty;
                $eto+=$DFil->eto;
                $do+=$DFil->do;
                $appraisal+=$DFil->appraisal;
                $fright+=$DFil->fright;
                $insurance+=$DFil->insurance;
                $expense+=$DFil->expense;
                $other_expense+=$DFil->other_expense;
                if($duty > 0): $DutyCounter++; endif;
                if($eto > 0): $EtoCounter++; endif;
                if($do > 0): $DoCounter++; endif;
                if($appraisal > 0): $AppraisalCounter++; endif;
                if($fright > 0): $FrightCounter++; endif;
                if($insurance > 0): $InsuranceCounter++; endif;
                if($expense > 0): $ExpenseCounter++; endif;
                if($other_expense > 0): $OtherExpenseCounter++; endif;
                ?>

        <?php endforeach;
        endif;
        ?>
        {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>--}}
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="3">Book Expenses</th>
                        </tr>
                        </thead>
                        <tbody class="text-right">
                        <?php if($duty > 0): $AllExpTot+=$duty;?>
                        <tr>
                            <th>Duty</th>
                            <td><?php echo $DutyCounter;?></td>
                            <td><input type="text" name="duty" id="duty" value="<?php echo $duty?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($eto > 0): $AllExpTot+=$eto;?>
                        <tr>
                            <th>Eto</th>
                            <td><?php echo $EtoCounter;?></td>
                            <td><input type="text" name="eto" id="eto" value="<?php echo $eto?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($do > 0): $AllExpTot+=$do;?>
                        <tr>
                            <th>Do</th>
                            <td><?php echo $DoCounter?></td>
                            <td><input type="text" name="do" id="do" value="<?php echo $do?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($appraisal > 0): $AllExpTot+=$appraisal;?>
                        <tr>
                            <th>Appraisal</th>
                            <td><?php echo $AppraisalCounter?></td>
                            <td><input type="text" name="appraisal" id="appraisal" value="<?php echo $appraisal?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($fright > 0): $AllExpTot+=$fright;?>
                        <tr>
                            <th>Fright</th>
                            <td><?php echo $FrightCounter?></td>
                            <td><input type="text" name="fright" id="fright" value="<?php echo $fright?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($insurance > 0): $AllExpTot+=$insurance;?>
                        <tr>
                            <th>Insurance</th>
                            <td><?php echo $InsuranceCounter?></td>
                            <td><input type="text" name="insurance" id="insurance" value="<?php echo $insurance?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($expense > 0): $AllExpTot+=$expense;?>
                        <tr>
                            <th>Expense</th>
                            <td><?php echo $ExpenseCounter?></td>
                            <td><input type="text" name="expense" id="expense" value="<?php echo $expense?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($other_expense > 0): $AllExpTot+=$other_expense;?>
                        <tr>
                            <th>Other Expense</th>
                            <td><?php echo $OtherExpenseCounter?></td>
                            <td><input type="text" name="otherexpense" id="otherexpense" value="<?php echo $other_expense?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <tr>
                            <th colspan="2" class="text-center">TOTAL</th>

                            <td class="text-right number_format"><?php echo number_format($AllExpTot,2)?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <h3 style="text-align: center">Import By Piece</h3>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">


                <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                    <thead>
                    <tr>
                        <th class="text-center">S.NO</th>
                        <th class="text-center">Item</th>
                        <th class="text-center" >Uom</th>
                        <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>

                        <th class="text-center">FCY Rate</th>
                        <th class="text-center">FCY Amount%</th>



                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $counter=1;
                    $total=0;
                    $total_qtyt=0;
                    $total_cureency =0;
                    $total_amount=0;
                    foreach ($data as $row): ?>
                    <?php
                    $sub_ic_detail=CommonHelper::get_subitem_detail($row->item_id);
                    $sub_ic_detail= explode(',',$sub_ic_detail); ?>

                    <tr>
                        <td>{{$counter++}}</td>
                        <td>{{$sub_ic_detail[4]}}</td>
                        <td><?php echo  CommonHelper::get_uom_name($sub_ic_detail[0]); ?></td>
                        <td>{{$row->qty}}</td>
                        <td>{{$row->foreign_currency_price}}</td>
                        <td>{{$row->amount}}</td>
                    </tr>

                    <?php $total_qtyt+=$row->qty; $total_cureency+=$row->foreign_currency_price; $total_amount+=$row->amount;  endforeach ?>

                    <tr style="font-size:larger;font-weight: bolder">
                        <td colspan="3">Total</td>
                        <td>{{$total_qtyt}}</td>
                        <td>{{$total_cureency}}</td>
                        <td>{{number_format($total_amount,2)}}</td>

                    </tr>
                    <input type="hidden" name="voucher_no" id="voucher_no" value="{{$id}}"/>


                </table>
            </div>
        </div></div>


</div>

<div class="row">
    <h3 style="text-align: center">Import By Weight</h3>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">


                <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                    <thead>
                    <tr>
                        <th class="text-center">S.NO</th>
                        <th class="text-center">Item</th>
                        <th class="text-center" >Uom</th>
                        <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                        <th class="text-center">Total Weight</th>
                        <th class="text-center">Rate Per Weight</th>
                        <th class="text-center">Total As Per Weight</th>
                        <th class="text-center">FCY Rate</th>
                        <th class="text-center">FCY Amount%</th>



                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $counter2=1;
                    $total2=0;
                    $total_qtyt2=0;
                    $total_weight2 = 0;
                    $total_rate_per_weight2 = 0;
                    $as_per_weight2 = 0;
                    $total_cureency2 =0;
                    $total_amount2=0;
                    foreach ($data22 as $row): ?>
                    <?php
                    $sub_ic_detail=CommonHelper::get_subitem_detail($row->item_id);
                    $sub_ic_detail= explode(',',$sub_ic_detail); ?>

                    <tr>
                        <td>{{$counter2++}}</td>
                        <td>{{$sub_ic_detail[4]}}</td>
                        <td><?php echo  CommonHelper::get_uom_name($sub_ic_detail[0]); ?></td>
                        <td>{{$row->qty}}</td>
                        <td>{{$row->total_weight}}</td>
                        <td>{{$row->total_rate_per_weight}}</td>
                        <td>{{$row->as_per_weight}}</td>
                        <td>{{$row->foreign_currency_price}}</td>
                        <td>{{$row->amount}}</td>
                    </tr>

                    <?php $total_qtyt2+=$row->qty; $total_cureency2+=$row->foreign_currency_price; $total_amount2+=$row->amount;
                    $total_weight2+=$row->total_weight; $total_rate_per_weight2+= $row->total_rate_per_weight; $as_per_weight2+=$row->as_per_weight;
                    endforeach ?>

                    <tr style="font-size:larger;font-weight: bolder">
                        <td colspan="3">Total</td>
                        <td>{{$total_qtyt2}}</td>
                        <td>{{$total_weight2}}</td>
                        <td>{{$total_rate_per_weight2}}</td>
                        <td>{{$as_per_weight2}}</td>
                        <td>{{$total_cureency2}}</td>
                        <td>{{number_format($total_amount2,2)}}</td>
                        <input type="hidden" name="tot_amount" id="tot_amount" value="<?php echo $total_amount+$total_amount2;?>">
                    </tr>
                    <tr>
                        <td colspan="8"><storng style="font-size: 20px;">Grand Total</storng></td>
                        <td><strong style="font-size: 20px;"><?php echo $total_amount+$total_amount2;?></strong></td>
                    </tr>

                    <input type="hidden" name="voucher_no" id="voucher_no" value="{{$id}}"/>


                </table>
            </div>
        </div></div>


</div>

</span>

<script>

    $(document).ready(function(){
        $('.number_format').number(true,2);
    });

</script>
