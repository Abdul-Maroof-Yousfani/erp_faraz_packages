
<?php use App\Helpers\CommonHelper; ?>
<style>
    @media print {
        a[href]:after {
            content: none !important;
        }
    }

    tr:hover {
        background-color: yellow;
    }
</style>
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Employee Wise Summary Report</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="table-responsive">
                                            <table  id="myTable" class="table table-bordered sf-table-list">
                                                <thead>
                                                <th style="" class="text-center">S.No</th>
                                                <th class="text-center">Account Name</th>

                                                <th class="text-left">Employee Name</th>
                                                <th class="text-center">Amount</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $Counter =1;
                                                $payable=0;
                                                $advance=0;
                                                $total_amount=0;
                                                foreach($Employee as $Fil):?>
                                                <tr>
                                                    <td class="text-center"><?php echo $Counter++;?></td>
                                                    <td><?php $Accounts =  CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                                        echo $Accounts->name;
                                                        ?></td>

                                                    <td class="text-left"><b style="font-size: large;font-weight: bolder"> <a target="_blank" href="<?php echo URL('finance/viewLedgerReport?AccId='.$Fil->acc_id.'&&FromDate='.$from.'&&ToDate='.$to.'&&m='.$m)?>">
                                                                <?php echo $Fil->emp_name?></a>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php   $amount=CommonHelper::get_ledger_amount($Fil->acc_code,$m,1,0,$from,$to);
                                                        if ($amount<0):
                                                            $total_amount+=$amount;
                                                            $amount=$amount*-1;
                                                            $advance+=$amount;
                                                            $amount=number_format($amount,2);
                                                            $amount='('.$amount.')';


                                                        else:
                                                            $payable+=$amount;
                                                            $total_amount+=$amount;
                                                            $amount=number_format($amount,2);
                                                        endif;
                                                        echo $amount;
                                                        ?>
                                                    </td>

                                                </tr>
                                                <?php

                                                ?>

                                                <?php endforeach;?>
                                                <tr style="background-color: darkgray">
                                                    <td class="text-center" style="font-weight: bold;font-size: large" colspan="2">Total</td>
                                                    <td class="text-right" style="font-weight: bold;font-size: large" colspan="1">{{number_format($total_amount,2)}}</td>
                                                </tr>
                                                </tbody>
                                            </table>


                                            <h4>Receivable : {{number_format($payable,2)}}</h4>
                                            <h4>Advance : {{number_format($advance,2)}}</h4>
                                            <?php $total_payables=$payable-$advance;
                                            if ($total_payables<0):
                                                $total_payables=$total_payables*-1;
                                            endif;
                                            ?>
                                            <h4>Total Receivable : {{number_format($total_payables,2)}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>