<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\PurchaseHelper;

$id = $_GET['id'];
$m = $_GET['m'];

$currentDate = date('Y-m-d');
$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();

if (Request::get('finish')==1):
    $id = DB::Connection('mysql2')->table('production_bom')->where('status',1)->where('finish_goods',$id)->value('id');
    endif;
$Master = DB::Connection('mysql2')->table('production_bom')->where('status',1)->where('id',$id)->first();

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
                            <h3 style="text-align: center;">View Bill OF Material Detail</h3>
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
                            <td>Finish Goods</td>
                            <td class="text-center"><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$Master->finish_goods);?></td>
                        </tr>

                        <tr>
                            <td>Created Date</td>
                            <td class="text-center"><?php echo CommonHelper::new_date_formate($Master->date)?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home">Direct Material</a></li>
                        <li><a data-toggle="tab" href="#menu1">Indrect Material</a></li>

                    </ul>
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">Qty In mm</th>


                            <th style="" class="text-center" >QTY (Per One piece in FT).<span class="rflabelsteric"><strong>*</strong></span></th>
                            <th style="" class="text-center" >QTY (No Of Piece 20 Feet Length).<span class="rflabelsteric"><strong>*</strong></span></th>
                            <th style="" class="text-center" >Recoverable Scrap %<span class="rflabelsteric"><strong>*</strong></span></th>
                            <th style="" class="text-center" >Chips %<span class="rflabelsteric"><strong>*</strong></span></th>
                            <th style="" class="text-center" >Turning Scrap %<span class="rflabelsteric"><strong>*</strong></span></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php

                        $direct = DB::Connection('mysql2')->table('production_bom_data_direct_material')->where('master_id','=',$id)->get();
                        $counter = 1;
                        foreach ($direct as $row){
                        ?>
                        <tr class="tex-center">
                            <td class="tex-center">
                                <?php echo $counter++;?>
                            </td>
                            <td class="tex-center">
                                <?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row->item_id);?>
                            </td>
                            <td class="text-center"><?php echo number_format($row->qty_mm,2)?></td>
                            <td class="text-center"><?php echo number_format($row->qty_ft,2)?></td>
                            <td class="text-center"><?php echo number_format($row->qty_20_length,2)?></td>
                            <td class="text-center"><?php echo number_format($row->recover_sreacp,2)?></td>
                            <td class="text-center"><?php echo number_format($row->recover_chip,2)?></td>
                            <td class="text-center"><?php echo number_format($row->turning_scrap,2)?></td>



                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
                        <div id="menu1" class="tab-pane fade">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width:50px;">S.No</th>
                                    <th class="text-center">Item</th>
                                    <th style="width: 150px" class="text-center">Qty</th>

                                </tr>
                                </thead>

                                <tbody>
                                <?php

                                $indirect = DB::Connection('mysql2')->table('production_bom_data_indirect_material')->where('main_id','=',$id)->get();
                                $counter = 1;
                                foreach ($indirect as $row){
                                ?>
                                <tr class="tex-center">
                                    <td class="text-center">
                                        <?php echo $counter++;?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo CommonHelper::get_item_name($row->item_id);?>
                                    </td>
                                    <td class="text-center">{{$row->qty}}</td>




                                </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
            </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><?php echo 'Description: '. $Master->description;?></p>
                        </div>
                    </div>
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
                                <b>   <p><?php echo strtoupper($Master->username);  ?></p></b>
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



