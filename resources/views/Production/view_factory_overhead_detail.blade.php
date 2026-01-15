<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\PurchaseHelper;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printMachineDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printMachineDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">View Factory Overhead  Detail</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Factory Overhead Name</td>
                            <td class="text-center"><?php echo strtoupper($data->name);?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>

            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Account Name</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">No Of Piece</th>
                            <th class="text-center">Cost</th>

                        </tr>
                        </thead>

                        <tbody>
                        <?php


                        $counter = 1;
                        foreach ($master_data as $row):
                        ?>
                        <tr class="tex-center">
                            <td class="tex-center"><?php echo $counter++;?></td>
                            <td class="tex-center">
                                <?php echo CommonHelper::get_account_name($row->acc_id);?>
                            </td>
                            <td class="tex-center"><?php echo $row->amount ?></td>
                            <td class="tex-center"><?php echo $row->no_of_piece ?></td>
                            <td class="text-center"><?php echo number_format($row->cost,2)?></td>


                        </tr>
                        <?php
                        endforeach
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style=""><?php echo $data->desc ?></div>
            <div style="line-height:8px;">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <style>
                    .signature_bor {
                        border-top:solid 1px #CCC;
                        padding-top:7px;
                    }
                </style>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                    <div class="container-fluid">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Prepared By: </h6>
                                <b>   <p><?php echo strtoupper($data->username);  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Checked By:</h6>
                                <b>   <p><?php  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Approved By:</h6>
                                <b>  <p></p></b>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


