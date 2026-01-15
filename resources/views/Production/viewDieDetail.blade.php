<?php

use App\Helpers\CommonHelper;



$m = Session::get('run_company');


$id = $_GET['id'];
$DaiData = DB::Connection('mysql2')->table('production_dai')->where('id',$id)->first();
$SelectedData = DB::Connection('mysql2')->table('production_dai_detail')->where('main_id',$id)->get();
?>


<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">View Die Detail</span>
                </div>

            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <input type="hidden" value="<?php echo $DaiData->id?>" id="main_id" name="main_id">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table id="buildyourform" class="table table-bordered">
                                            <thead>
                                            <tr class="text-center">
                                                <th colspan="6" class="text-center"><h3>(<?php echo $DaiData->dai_name?>)</h3> </th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Sr No</th>
                                                <th class="text-center">Batch Code</th>
                                                <th class="text-center">Life in (Pieces)<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Depreciable Cost<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Depreciation per Piece <span class="rflabelsteric"><strong>*</strong></span></th>

                                            </tr>
                                            </thead>
                                            <tbody class="" id="AppendHtml">
                                            <?php
                                            $Counter = 1;
                                            foreach($SelectedData as $fil):?>
                                            <tr class="text-center AutoNo">
                                                <td><?php echo $Counter++;?></td>
                                                <td><?php echo $fil->batch_code?></td>
                                                <td><?php echo $fil->life?></td>
                                                <td><?php echo $fil->value?></td>
                                                <td><?php echo $fil->cost?></td>
                                            </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                        </table>

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