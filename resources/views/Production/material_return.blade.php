<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;


use App\Helpers\SalesHelper;

$so_no='';
$customer='';
if($data->sales_order_id!=''):
    $so_data=SalesHelper::get_sales_tax_by_sales_order_id($data->sales_order_id);
    $so_no=$so_data->so_no;
    $customer=SalesHelper::get_customer_name($so_data->buyers_id);
endif;
?>
<style>
    .modalWidth{
        width: 100%;
    }
    .bold {
        font-size: large;
        font-weight: bold;
    }
</style>
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
                            <h3 style="text-align: center;">Material Return</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Order Name</td>
                            <td class="text-center"><?php echo strtoupper($data->order_no);?></td>
                        </tr>
                        <tr>
                            <td>Order Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($data->order_date);?></td>
                        </tr>

                        <tr>
                            <td>Due Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($data->due_date);?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Type</td>
                            <td class="text-center">@if($data->type==1) Standard @else Make To Order @endif</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td class="text-center">@if($data->status==1) Planned @else Release @endif</td>
                        </tr>

                        <tr>
                            <td>Sales Order</td>
                            <td class="text-center"><?php echo strtoupper($so_no);?></td>
                        </tr>

                        <tr>
                            <td>Customer</td>
                            <td class="text-center"><?php strtoupper($customer) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Route</th>
                            <th class="text-center">Planned Quantity</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php


                        $counter = 1;
                        foreach ($master_data as $row):
                        ?>
                        <tr class="tex-center">
                            <td class="tex-center"><?php echo $counter++;?></td>
                            <td  class="text-center bold">
                                <?php echo CommonHelper::get_item_name($row->finish_goods_id);?>
                            </td>

                            <td class="text-center bold"><?php echo  ProductionHelper::get_route_code($row->route) ?></td>
                            <td class="text-center bold"><?php echo $row->planned_qty?></td>

                        </tr>

                        <tr class="InnerDetail" style="; background: radial-gradient(black, transparent)">
                            <td colspan="7">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Requested QTY</th>
                                        <th class="text-center">Issue QTY</th>
                                        <th class="text-center">Previous  QTY</th>
                                        <th style="width: 150px" class="text-center">Return QTY</th>
                                        <th style="width: 150px" class="text-center">Net Issued QTY</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @include('Production.bom_data_return')

                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <?php

                        endforeach

                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style=""><?php  ?></div>


        </div>
    </div>
</div>


