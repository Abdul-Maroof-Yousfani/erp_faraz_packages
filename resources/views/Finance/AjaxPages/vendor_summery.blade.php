
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
                                <span class="subHeadingLabelClass">Supplier Wise Summary Report</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="table-responsive">
                                            <table  id="export_table_to_excel_1" class="table table-bordered sf-table-list">
                                                <thead>
                                                    <th colspan="3" class="text-center"><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></th>
                                                </thead>
                                                <thead>
                                                    <th colspan="3" class="text-center">Vendor Wise Summary Report</th>
                                                </thead>

                                                <thead>
                                                <th colspan="3" class="text-center">AS ON {{date_format(date_create($to),'F d, Y')}}</th>
                                                </thead>
                                                <thead>
                                                    <th colspan="3" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
                                                </thead>
                                                <thead>
                                                <th style="" class="text-center">S.No</th>
                                                {{--<th class="text-center">Acc-Id</th>--}}

                                                <th class="text-left">Supplier Name</th>
                                                <th class="text-center">Amount</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $Counter =1;
                                                $payable=0;
                                                $advance=0;
                                                $total_amount=0;


                                                foreach($Supplier as $Fil):

                                                $amount=CommonHelper::get_ledger_amount($Fil->acc_code,Session::get('run_company'),0,1,$from,$to);


                                                        if ($amount!=0):
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $Counter++; echo '</br>'; echo $Fil->id;?></td>
                                                    {{--<td>< ?php echo $Fil->acc_id?></td>--}}

                                                    <td class="text-left"><b style="font-size: large;font-weight: bolder"> <a target="_blank" href="<?php echo URL('finance/viewLedgerReport?AccId='.$Fil->acc_id.'&&FromDate='.$from.'&&ToDate='.$to.'&&m='.$m)?>"><?php echo $Fil->name?></a></td>
                                                    <td class="text-right">
                                                        <?php
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
                                                <?php endif; endforeach;?>
                                                <tr style="background-color: darkgray">
                                                    <td class="text-center" style="font-weight: bold;font-size: large" colspan="2">Total</td>
                                                    <td class="text-right" style="font-weight: bold;font-size: large" colspan="1">{{number_format($total_amount,2)}}</td>
                                                </tr>
                                                </tbody>
                                            </table>


                                            <h4>Payables : {{number_format($payable,2)}}</h4>
                                            <h4>Advance : {{number_format($advance,2)}}</h4>
                                            <?php $total_payables=$payable-$advance;
                                            if ($total_payables<0):
                                                $total_payables=$total_payables*-1;
                                            endif;
                                            ?>
                                            <h4>Total Payable : {{number_format($total_payables,2)}}</h4>
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