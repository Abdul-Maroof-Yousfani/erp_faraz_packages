<?php use App\Helpers\CommonHelper; ?>
<style>
 @media print{a[href]:after{content:none !important;}
}
tr:hover{background-color:yellow;}
</style>
<div class="well">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Supplier Wise Summary Report</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                      
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="tb-report-wrap">
                                                <div class="tb-report-header">
                                                    <p class="tb-company-name"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></p>
                                                    <p class="tb-report-title">Vendor Wise Summary Report</p>
                                                    <p class="tb-date-range">AS ON {{date_format(date_create($to),'F d, Y')}}</p>
                                                    <p class="tb-printed-on">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p>
                                                </div>
    
                                                <div class="tb-table-scroll">
                                                    <table id="export_table_to_excel_1" class="tb-table">
                                                        <thead>
                                                        <tr class="tb-col-row">
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-left">Supplier Name</th>
                                                            <th class="text-right">Amount</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $Counter = 1;
                                                        $payable = 0;
                                                        $advance = 0;
                                                        $total_amount = 0;
    
                                                        foreach ($Supplier as $Fil):
                                                            $amount = CommonHelper::get_ledger_amount($Fil->acc_code, Session::get('run_company'), 0, 1, $from, $to);
    
                                                            if ($amount != 0):
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $Counter++; ?></td>
                                                            <td class="text-left link_hide">
                                                                <a target="_blank" href="<?php echo URL('finance/viewLedgerReport?AccId='.$Fil->acc_id.'&&FromDate='.$from.'&&ToDate='.$to.'&&m='.$m)?>"><?php echo $Fil->name?></a>
                                                            </td>
                                                            <td class="text-right">
                                                                <?php
                                                                if ($amount < 0):
                                                                    $total_amount += $amount;
                                                                    $amount = $amount * -1;
                                                                    $advance += $amount;
                                                                    $amount = number_format($amount, 2);
                                                                    $amount = '(' . $amount . ')';
                                                                else:
                                                                    $payable += $amount;
                                                                    $total_amount += $amount;
                                                                    $amount = number_format($amount, 2);
                                                                endif;
                                                                echo $amount;
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php endif; endforeach; ?>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td class="text-center" colspan="2">Total</td>
                                                            <td class="text-right"><?php echo number_format($total_amount, 2); ?></td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
    
                                                <div style="padding: 18px 32px;">
                                                    <p>Payables : {{number_format($payable,2)}}</p>
                                                    <p>Advance : {{number_format($advance,2)}}</p>
                                                    <?php
                                                    $total_payables = $payable - $advance;
                                                    if ($total_payables < 0):
                                                        $total_payables = $total_payables * -1;
                                                    endif;
                                                    ?>
                                                    <p><strong>Total Payable : {{number_format($total_payables,2)}}</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>