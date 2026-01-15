<?php
        
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
//$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
CommonHelper::companyDatabaseConnection($m);
$job_order = DB::table('survey')->where('survey_id','=',$id)->first();
$survey_data = DB::table('survey_data')->where('survey_id','=',$id)->get();
$survey_document = DB::table('survey_document')->where('survey_id','=',$id)->get();
CommonHelper::reconnectMasterDatabase();


?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php if($job_order->survey_status == 1):?>
            <button type="button" class="btn btn-xs btn-success" id="BtnApproved" onclick="ApprovedSurvey('<?php echo $job_order->survey_id?>')">Approved</button>
        <?php endif;?>
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
                        <h3 style="text-align: center;">Survey Detail</h3>
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
                    <div style="width:30%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>

                            <tr>
                                <td style="width:40%;">Job Tracking No</td>
                                <td style="width:60%;"><?php echo $job_order->tracking_no; ?></td>
                            </tr>

                            <tr>
                                <td style="width:40%;">Client Name</td>
                                <td style="width:60%;"><?php echo CommonHelper::get_client_name_by_id($job_order->client_id); ?></td>
                            </tr>
                            <tr>
                                <td style="width:40%;">Branch Name</td>
                                <td style="width:60%;"><?php echo $job_order->branch_name; ?></td>
                            </tr>
                            <tr>
                                <td style="width:40%;">Name Of Contact Person</td>
                                <td style="width:60%;"><?php echo $job_order->contact_person;?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="width:30%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:40%;">Contact Number</td>
                                <td style="width:60%;"><?php echo $job_order->contact_number;?></td>
                            </tr>
                            <tr>
                                <td>Survery Date</td>
                                <td><?php echo FinanceHelper::changeDateFormat($job_order->survey_date);?></td>
                            </tr>

                            <tr>
                                <td>Survery By</td>
                                <td><?php echo CommonHelper::gey_survey_by_name($job_order->survery_by_id) ?></td>
                            </tr>

                            <tr>
                                <td>Surveyor Name</td>
                                <td><?php echo $job_order->surveyor_name ?></td>
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
                                <th class="text-center">Type</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Height</th>
                                <th class="text-center">Width</th>
                                <th class="text-center" style="width: 80px;">Depth</th>
                                <th class="text-center" style="width:100px;">Condition</th>
                                <th class="text-center">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $counter = 1;
                            foreach ($survey_data as $row) {
                            CommonHelper::companyDatabaseConnection($m);
                            $type="";
                            if($row->type_id !=""):
                            $type = DB::table('type')->where('type_id','=',$row->type_id)->first();
                            $type = $type->name;
                            else:
                            $type = "";
                            endif;
                            $ConditionName='';
                            $MultiIds = explode('@#',$row->condition_id);
                            foreach($MultiIds as $Fil)
                            {

                                $condition = DB::table('conditions')->where('condition_id','=',$Fil)->first();
                                $ConditionName .= $condition->name.',';
                            }

                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $counter++;?>
                                </td>
                                <td> <?php
                                  $product_data= CommonHelper::get_product_name_by_id($row->product);
                                        if($product_data !=""){
                                    echo  $product_data->p_name ;
                                    }
                                        else{}
                                    ?> </td>
                                <td> <?php echo $type;?> </td>
                                <td><?php echo $row->qty;?></td>
                                <td> <?php echo $row->height ;?> </td>
                                <td> <?php echo $row->width ;?> </td>
                                <td> <?php echo $row->depth ;?> </td>
                                <td> <?php echo $ConditionName;?> </td>
                                <td> <?php echo $row->remarks; ?> </td>
                            </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php $edit_url= url('/sales/ShowAllImages/'.$job_order->survey_id.'?m='.$m);?>
                    <a target="_blank" href="<?php echo $edit_url;?>" class="btn btn-sm btn-info hidden-print">Show All Images</a>
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

<script type="text/javascript">
    $(".btn-abc").click(function(e){
        var _token = $("input[name='_token']").val();
        jqueryValidationCustom();
        if(validate == 0){
            //alert(response);
        }else{
            return false;
        }
        formSubmitOne();
    });

    function formSubmitOne(e){

        var postData = $('#updateDemandDetailandApprove').serializeArray();
        var formURL = $('#updateDemandDetailandApprove').attr("action");
        $.ajax({
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data){
                $('#showDetailModelOneParamerter').modal('toggle');
                //alert(data);
                filterVoucherList();
            }
        });
    }

    function ApprovedSurvey(SurveyId){
        var m = '<?php echo $_GET['m'];?>';
        $('#showDetailModelOneParamerter').modal('hide');
        $('#Loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $('#ShowHide').css('display','none');
        $.ajax({
            url: '<?php echo url('/')?>/sdc/ApprovedSurvey',
            type: "GET",
            data: { SurveyId:SurveyId,m:m},
            success:function(data) {

                $('#'+data).append('');
                $('#'+data).html('<td  class="text-center"><span class="badge badge-success" style="background-color: #00c851 !important">Success</span></td>')
                setInterval(function() {
                            $('#Loader').html('');
                            $('#ShowHide').css('display', 'block');
                        },2000);





            }
        });
    }
</script>

