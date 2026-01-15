<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ProductionHelper;


$id = $_GET['id'];
$m = $_GET['m'];

$currentDate = date('Y-m-d');
$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
$Master = DB::Connection('mysql2')->table('production_route')->where('status',1)->where('id',$id)->first();
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
                            <h3 style="text-align: center;">View Routing Detail</h3>
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
                            <td>Finish Good</td>
                            <td class="text-center"><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$Master->finish_goods);?></td>
                        </tr>
                        <tr>
                            <td>Operation</td>
                            <td class="text-center"><?php echo $Master->operation_id;?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {{--<p>< ?php echo $Master->description;?></p>--}}
                </div>
            </div>
            <?php
           $data= DB::Connection('mysql2')->table('production_route_data')->where('master_id',$id)->where('orderby','!=',0)->orderBy('orderby')->get();
            ?>
            <div class="row">
                <?php
                $count=1;
                foreach ($data as $row):
                ?>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                    <fieldset style="border: solid 1px #b5afaf; border-radius: 10px; ">
                        <h4 class="well"><?php echo ProductionHelper::get_machine_name($row->machine_id) ?> <span id="cls_counter<?php echo $count ?>" class="badge badge-secondary"></span> </h4>
                    </fieldset>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-center" style="margin: 25px 0px 0px 0px;">
                    <i class="fa fa-arrow-right" aria-hidden="true" style="font-size: 35px;"></i>
                </div>

                <?php
                if($count == 3 || $count == 6 || $count == 9 || $count== 12 || $count == 15):?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

                <?php endif;?>

                <?php $count++; endforeach;?>
            </div>
            <?php
            $data= DB::Connection('mysql2')->table('production_route_data')->where('master_id',$id)->where('orderby',0);

             if ($data->count()>0):


            ?>
            <table style="width: 25%" class="table table-bordered table-striped table-condensed tableMargin">
                <thead>
                <h4>Skipped Machines</h4>
                <tr>
                    <th class="text-center">Sr No</th>
                    <th class="text-center">Machine Name</th>

                </tr>
                </thead>
                <tbody>
                <?php
                $count=1;
                foreach ($data->get() as $row):?>
                <tr class="text-center">
                    <td><?php echo $count++?></td>
                    <td><?php  echo ProductionHelper::get_machine_name($row->machine_id)?></td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>

            <?php endif; ?>


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



