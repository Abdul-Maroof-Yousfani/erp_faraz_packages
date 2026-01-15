<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\PurchaseHelper;

$id = $_GET['id'];
$m = $_GET['m'];

$currentDate = date('Y-m-d');
$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
$Master = DB::Connection('mysql2')->table('production_machine')->where('status',1)->where('id',$id)->first();
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
                            <h3 style="text-align: center;">View Machine Detail</h3>
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
                            <td>Total Equipment Cost</td>
                            <td class="text-center"><?php echo number_format($Master->equi_cost,2);?></td>
                        </tr>
                        <tr>
                            <td>Salvage cost</td>
                            <td class="text-center"><?php echo number_format($Master->salvage_cost,2);?></td>
                        </tr>
                        <tr>
                            <td>Depreciable Cost</td>
                            <td class="text-center"><?php echo number_format($Master->dep_cost,2);?></td>
                        </tr>

                        <tr>
                            <td>Life</td>
                            <td class="text-center"><?php echo number_format($Master->life,2);?></td>
                        </tr>

                        <tr>
                            <td> Depreciation Per Piece</td>
                            <td class="text-center"><?php echo number_format($Master->yearly_cost,2);?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>

                        <tr>
                            <td>Machine Name</td>
                            <td class="text-center"><?php echo $Master->machine_name;?></td>
                        </tr>
                        <tr>
                            <td>Machine Code</td>
                            <td class="text-center"><?php echo $Master->code;?></td>
                        </tr>

                        <tr>
                            <td>Setup Time</td>
                            <td class="text-center"><?php

                                $hours = $Master->setup_time;
                                $minuts=explode(':',$hours);
                                $minuts=$minuts[0]*60+$minuts[1];
                                 echo $minuts.' (Minutes)';

                                ?></td>
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
                            <th class="text-center">Finish Goods</th>
                            <th class="text-center">Die</th>
                            <th class="text-center">Mould</th>
                            <th class="text-center">Pieces Per Hour</th>
                            <th class="text-center">KW Electricity Per Hour</th>
                            <th class="text-center">Remove</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php

                        $Detail = DB::Connection('mysql2')->table('production_machine_data')->where('master_id','=',$id)->get();
                        $counter = 1;
                        foreach ($Detail as $Fil){
                        ?>
                        <tr id="{{$Fil->id}}" class="tex-center">
                            <td class="tex-center">
                                <?php echo $counter++;?>
                            </td>
                            <td class="tex-center" title="{{ $Fil->finish_good }}">
                                <?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$Fil->finish_good);?>
                            </td>
                            <td class="tex-center">
                                <?php
                                $DaiCount = 1;
                                if($Fil->dai_id !="")
                                {
                                    $MuliDai = explode(',',$Fil->dai_id);
                                    foreach($MuliDai as $Dai)
                                    {
                                        $DaiData = DB::Connection('mysql2')->table('production_dai')->where('status',1)->where('id',$Dai)->first();
                                        if($DaiData)
                                        {
                                            echo $DaiCount.')'.$DaiData->dai_name.'<br>';
                                            $DaiCount++;
                                        }

                                    }
                                }

                                ?>
                            </td>
                            <td class="tex-center">
                                <?php
                                    $MoldCount = 1;
                                if($Fil->mold_id !="")
                                {
                                    $MuliMold = explode(',',$Fil->mold_id);
                                    foreach($MuliMold as $Mold)
                                    {
                                        $MoldData = DB::Connection('mysql2')->table('production_mold')->where('status',1)->where('id',$Mold)->first();
                                        if($MoldData)
                                            {
                                                echo $MoldCount.')'.$MoldData->mold_name.'<br>';
                                                $MoldCount++;
                                            }

                                    }
                                }

                                ?>
                            </td>

                            <td class="text-center"><?php echo number_format($Fil->qty_per_hour,2)?></td>
                            <td class="text-center"><?php echo number_format($Fil->electricity_per_hour,2)?></td>
                            <td><button onclick="delete_data('{{ $Fil->id }}')" type="button" class="btn btn-danger btn-xs">Delete</button></td>

                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
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
<script>
    function delete_data(id)
    {
        //alert(id); return false;

            var base_url='<?php echo URL::to('/'); ?>';
            $.ajax({
                url: base_url+'/production/delete_machine_data',
                type: 'GET',
                data: {id: id},
                success: function (response)
                {
                    alert('Deleted');
                    $('#'+id).remove();
                }
            });

    }
</script>

