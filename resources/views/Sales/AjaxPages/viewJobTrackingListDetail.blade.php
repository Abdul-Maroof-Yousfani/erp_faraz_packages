<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
//$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
CommonHelper::companyDatabaseConnection($m);
$job_track = DB::table('job_tracking')->where('job_tracking_id','=',$id)->first();
$CustomerJob = $job_track->customer_job;
$client_id = $job_track->customer;
$region = $job_track->region;
$city = $job_track->city;

//$survey = DB::table('survey')->where('tracking_no','=',$job_track->job_tracking_no)->first();

$client = DB::table('client')->where('id','=',$client_id)->first();
$ClientName = $client->client_name;
$region = DB::table('region')->where('id','=',$region)->first();
$RegionName = $region->region_name;
$job_track = DB::table('job_tracking')->where('job_tracking_id','=',$id)->first();
$job_tracking_data = DB::table('job_tracking_data')->where('job_tracking_id','=',$id)->orderBy('job_tracking_data_id', 'ASC')->get();
CommonHelper::reconnectMasterDatabase();
$city = DB::table('cities')->where('id','=',$city)->first();
$CityName = $city->name;


?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="well" id="printDemandVoucherVoucherDetail">
    <?php //echo PurchaseHelper::displayApproveDeleteRepostButtonTwoTable($m,$row->demand_status,$row->status,$row->demand_no,'demand_no','demand_status','status','demand','demand_data');?>
    <?php echo Form::open(array('url' => 'pad/updateDemandDetailandApprove?m='.$m.'','id'=>'updateDemandDetailandApprove'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
    <input type="hidden" name="demandNo" value="<?php echo $id; ?>">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($currentDate);?></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php //echo CommonHelper::getCompanyName($m);?>
                            <h3 style="text-align: center;">Job Tracking Detail</h3>
                        </div>
                        <br />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php //PurchaseHelper::checkVoucherStatus($row->demand_status,$row->status);?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
                    <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                </div>
            </div>
            <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <img style="text-align: center; width: 30%" src="{{url('/storage/app/uploads/left.png')}}">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                    <?php //$nameOfDay = date('l', strtotime($currentDate)); ?>
                    <img style="text-align: center; width: 45%" src="{{url('/storage/app/uploads/right.png')}}">
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:35%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>

                            <tr>
                                <td style="width:40%;">Client Name</td>
                                <td style="width:60%;"><?php echo $ClientName; ?></td>
                            </tr>

                            <tr>
                                <td style="width:40%;">Customer Job</td>
                                <td style="width:60%;"><?php echo $CustomerJob?></td>
                            </tr>
                            <tr>
                                <td style="width:40%;">Region</td>
                                <td style="width:60%;"><?php echo $RegionName; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="width:35%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:40%;">Job Tracking #</td>
                                <td style="width:60%;"><?php echo $job_track->job_tracking_no;?></td>
                            </tr>
                            <tr>
                                <td>Job Tracking Date</td>
                                <td><?php echo FinanceHelper::changeDateFormat($job_track->job_tracking_date);?></td>
                            </tr>

                            <tr>
                                <td>City</td>
                                <td><?php echo $CityName; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td style="width:20%;">Job Description</td>
                            <td style="width:70%;"><?php echo $job_track->job_description; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:50px;">S.No</th>
                                <th class="text-center">Task</th>
                                <th class="text-center">Task Assigned</th>
                                <th class="text-center">Task Target Date</th>
                                <th class="text-center">Task Completed Date</th>
                                <th class="text-center" style="width: 80px;">Resource</th>
                                <th class="text-center" style="width:100px;">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $counter = 1;
                            foreach ($job_tracking_data as $row) {
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $counter++;?>
                                </td>
                                <td> <?php echo $row->task ;?> </td>
                                <td> <?php echo $row->task_assigned ;?> </td>
                                <td> <?php echo $row->task_target_date ;?> </td>
                                <td> <?php echo $row->task_completed_date ;?> </td>
                                <td> <?php $resource = CommonHelper::get_single_row('resource_assign','id',$row->resource);
                                    if (!empty($resource->resource_type)): echo  $resource->resource_type; endif;
                                //    print_r($resource);
                                    ?> </td>
                                <td> <?php echo $row->remarks; ?> </td>
                            </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
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
                                <b>   <p><?php //echo strtoupper($username);  ?></p></b>
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


