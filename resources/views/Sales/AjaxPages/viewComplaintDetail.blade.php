<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
//$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
CommonHelper::companyDatabaseConnection($m);
$Complaint = DB::table('complaint')->where('id','=',$id)->first();
$ComplaintData = DB::table('complaint_product')->where('complaint_id','=',$id)->get();
CommonHelper::reconnectMasterDatabase();

?>
<?php $client_name = CommonHelper::client_name($Complaint->client_name); ?>
<style>
    .table-bordered > thead > tr > th, .table-bordered > thead > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 1px solid #000;
    }
</style>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        {{--< ?php if($job_order->jo_status == 1):?>--}}
        {{--<button type="button" class="btn btn-xs btn-success" id="BtnApproved" onclick="ApprovedJobOrder('< ?php echo $job_order->job_order_id?>')">Approved</button>--}}
        {{--< ?php endif;?>--}}
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printDemandVoucherVoucherDetail">
    <?php //echo PurchaseHelper::displayApproveDeleteRepostButtonTwoTable($m,$row->demand_status,$row->status,$row->demand_no,'demand_no','demand_status','status','demand','demand_data');?>
    <?php echo Form::open(array('url' => 'pad/updateDemandDetailandApprove?m='.$m.'','id'=>'updateDemandDetailandApprove'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
    <input type="hidden" name="demandNo" value="<?php echo $id; ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
            <div class="">

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <img style="text-align: center; width: 30%" src="{{url('/storage/app/uploads/left.png')}}">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                    <?php //$nameOfDay = date('l', strtotime($currentDate)); ?>
                    <img style="text-align: center; width: 45%" src="{{url('/storage/app/uploads/right.png')}}">
                </div>
            </div>

            <div style="line-height:5px;">&nbsp;</div>
            <div class="" >

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <h4><?php echo $client_name->client_name?></h4>
                    <p>COMPLAINT/MAINTENANCE REPORT</p>
                </div>
                <div class="container">
                <div class="row" style="border: solid 1px;">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <table class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Branch Name</td>
                            <td class="text-center"><?php echo $Complaint->branch_name; ?></td>
                        </tr>
                        <tr>
                            <td>Contact Person</td>
                            <td class="text-center"><?php echo $Complaint->contanct_person; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" >
                    <table class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Branch Code</td>
                            <td class="text-center"><?php echo $Complaint->branch_code; ?></td>
                        </tr>
                        <tr>
                            <td>Designation</td>
                            <td class="text-center"><?php echo $Complaint->designation; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" >
                    <table class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($Complaint->date); ?> </td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td class="text-center"><?php echo $Complaint->phone; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    <table class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td style="width: 15%;">Address</td>
                            <td class="text-center"><?php echo $Complaint->address; ?> </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px;"></div>


                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label class="sf-label">Maintenance Type </label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label class="sf-label">Monthly<input type="checkbox" class="form-control" disabled <?php if($Complaint->monthly == 1){echo "checked";}?>></label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label class="sf-label">Quaterly <input type="checkbox" class="form-control" disabled <?php if($Complaint->Quaterly == 1){echo "checked";}?>></label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label class="sf-label">Semi Annually <input type="checkbox" class="form-control" disabled <?php if($Complaint->Semi_Annually == 1){echo "checked";}?>></label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label class="sf-label">Annually <input type="checkbox" class="form-control" disabled <?php if($Complaint->Annually == 1){echo "checked";}?>></label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label class="sf-label">On Call <input type="checkbox" class="form-control" disabled <?php if($Complaint->On_Call == 1){echo "checked";}?>></label>
                        </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px;"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="buildyourform" class="table table-bordered">
                            <thead>
                            <tr>
                                {{--<th class="text-center">Serial No</th>--}}
                                <th class="text-center">Product</th>
                                <th class="text-center">Front</th>
                                <th class="text-center">Left</th>
                                <th class="text-center">Right</th>
                                <th class="text-center">Back</th>
                            </tr>
                            </thead>
                            <tbody >
                            <?php
                            $Counter=1;

                            foreach($ComplaintData as $Filter):?>
                            <tr>
                                {{--<td>< ?php echo $Counter++;?></td>--}}
                                <td class="text-center"><?php $produc_data=  CommonHelper::get_product_name_by_id($Filter->product);
                                    echo $produc_data->p_name;
                                    ?></td>
                                <td class="text-center"><?php echo $Filter->front;?></td>
                                <td class="text-center"><?php echo $Filter->p_left;?></td>
                                <td class="text-center"><?php echo $Filter->p_right;?></td>
                                <td class="text-center"><?php echo $Filter->back;?></td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>

                        </table>

                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px;"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3>Board Maintenance</h3></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px;"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><span style="border-bottom: solid 1px;"> Alucobond Sign Report</span></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label class="sf-label">Board Cleaning</label>
                    <input type="text" class="form-control" name="BoardCleaning" id="BoardCleaning" value="<?php echo $Complaint->board_cleaning?>" disabled>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <label class="sf-label">Led Stip</label>
                    <input type="text" class="form-control" name="LedStrip" id="LedStrip" value="<?php echo $Complaint->led_stip?>">
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <label class="sf-label">Led Wiring</label>
                    <input type="text" class="form-control" name="LedWiring" id="LedWiring" value="<?php echo $Complaint->led_wiring?>" disabled>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <label class="sf-label">Led Rope</label>
                    <input type="text" class="form-control" name="LedRope" id="LedRope" value="<?php echo $Complaint->led_rope?>" disabled>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <label class="sf-label">Power Supply</label>
                    <input type="text" class="form-control" name="PowerSupply" id="PowerSupply" value="<?php echo $Complaint->power_supply?>" disabled>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label class="sf-label">Note:</label>
                    <input type="text" class="form-control" name="sign_note" id="sign_note" value="<?php echo $Complaint->sign_note?>" disabled>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><span style="border-bottom: solid 1px;"> Sun Swith DB Box Report</span></div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label class="sf-label">Auto/Manual Selector</label>
                    <input type="text" class="form-control" name="AutoManualSelector" id="AutoManualSelector" value="<?php echo $Complaint->auto_manual?>" disabled>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label class="sf-label">Contractor</label>
                    <input type="text" class="form-control" name="Contractor" id="Contractor" value="<?php echo $Complaint->contractor?>" disabled>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label class="sf-label">Breaker</label>
                    <input type="text" class="form-control" name="Breaker" id="Breaker" value="<?php echo $Complaint->breaker?>" disabled>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label class="sf-label">Sun Switch</label>
                    <input type="text" class="form-control" name="SunSwitch" id="SunSwitch" value="<?php echo $Complaint->sun_switch?>" disabled>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label class="sf-label">Volt Led</label>
                    <input type="text" class="form-control" name="VoltLed" id="VoltLed" value="<?php echo $Complaint->volt_led?>" disabled>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label class="sf-label">Stabilizer/Lighting Device</label>
                    <input type="text" class="form-control" name="StabilizerLightingDevice" id="StabilizerLightingDevice" value="<?php echo $Complaint->stabilizer?>" disabled>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label class="sf-label">Note:</label>
                    <input type="text" class="form-control" name="Note" id="Note" value="<?php echo $Complaint->note?>" disabled>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px;"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><h3>Inside Branch Electric Work</h3></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px;"></div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <label class="sf-label">Timer Connection Disconnect & Connect With Breaker</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <label class="sf-label">Yes <input type="radio" class="form-control" name="timer_connection" <?php if($Complaint->timer_connection == 1){echo "checked";}?> disabled></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <label class="sf-label">No <input type="radio" class="form-control" name="timer_connection" value="2" id="No" <?php if($Complaint->timer_connection == 2){echo "checked";}?> disabled></label>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                    <label class="sf-label">Breaker Replaced</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <label class="sf-label">Yes <input type="radio" class="form-control" name="breaker_replaced" <?php if($Complaint->breaker_replaced == 1){echo "checked";}?> disabled></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <label class="sf-label">No <input type="radio" class="form-control" name="breaker_replaced" <?php if($Complaint->breaker_replaced == 2){echo "checked";}?> disabled></label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <label class="sf-label">Wiring Addional Installed</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <label class="sf-label">Yes <input type="radio" class="form-control" name="wiring_additional" <?php if($Complaint->wiring_additional == 1){echo "checked";}?> disabled></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <label class="sf-label">No <input type="radio" class="form-control" name="wiring_additional" <?php if($Complaint->wiring_additional == 2){echo "checked";}?> disabled></label>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <label class="sf-label">RFT</label>
                    <input type="text" name="Rft" id="Rft" class="form-control" disabled value="<?php echo $Complaint->rft?>">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px;"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label class="sf-label">Comments</label>
                    <input type="text" name="comments" id="comments" class="form-control" disabled value="<?php echo $Complaint->comments?>">
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px;"></div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="border-top: solid 1px;"></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" ></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="border-top: solid 1px;"></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><span>Sign Now</span></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><span><?php echo $client_name->client_name?></span></div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Super Visor Name</div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Branch Manager Name</div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="border-bottom: solid 1px;"></div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="border-bottom: solid 1px;"></div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Signature & Stamp</div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Signature & Stamp</div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="border-bottom: solid 1px;"></div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="border-bottom: solid 1px;"></div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>


            </div>
                    <p class="text-center">10C, Mezzanine Floor, Street No. 05 Baddar Commercial Area, Phase - V,Ext, Defence Housing Authority, Karachi</p>
                    <p class="text-center">Phone # 92-21-5344586, E-Mail: signsnow@cyber.net.pk<p>


        </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php $edit_url= url('/sales/ShowAllImagesComplaint/'.$Complaint->id.'?m='.$m);?>
                    <a target="_blank" href="<?php echo $edit_url;?>" class="btn btn-sm btn-info hidden-print">Show All Images</a>
                </div>
    </div>
</div>
    </div>
    </div>






