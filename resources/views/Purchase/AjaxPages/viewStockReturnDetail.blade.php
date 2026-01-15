<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
CommonHelper::companyDatabaseConnection($m);
$demandDetail = DB::table('stock_return')->where('issuance_no','=',$id)->get();
CommonHelper::reconnectMasterDatabase();
foreach ($demandDetail as $row) {
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printDemandVoucherVoucherDetail">
    <?php //echo PurchaseHelper::displayApproveDeleteRepostButtonTwoTable($m,$row->demand_status,$row->status,$row->demand_no,'demand_no','demand_status','status','demand','demand_data');?>
    <?php //echo Form::open(array('url' => 'pad/updateDemandDetailandApprove?m='.$m.'','id'=>'updateDemandDetailandApprove'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
    <input type="hidden" name="demandNo" value="<?php echo $id; ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($currentDate);?></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php echo CommonHelper::getCompanyName($m);?>
                            <h3 style="text-align: center;">Stock Return</h3>
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
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div>
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td>Issuance No.</td>
                                <td class="text-center"><?php echo strtoupper($row->issuance_no);?></td>
                            </tr>
                            <tr>
                                <td>Issuance Date</td>
                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->issuance_date);?></td>
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
                                <th class="text-center">Category Name</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Job Order</th>
                                <th class="text-center" style="width:100px;">Qty.</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection($m);
                            $demandDataDetail = DB::table('stock_return_data')->where('issuance_no','=',$id)->get();
                            CommonHelper::reconnectMasterDatabase();
                            $counter = 1;
                            $totalCountRows = count($demandDataDetail);
                            foreach($demandDataDetail as $row1){
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $counter++;?>

                                </td>
                                <td>
                                    <?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'category','main_ic',$row1->category);?>

                                </td>
                                <td>
                                    <?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->subitem);?>
                                </td>

                                <td> <?php echo $row1->job_order;?></td>


                                <td class="text-center"><?php echo number_format($row1->qty,2);?></td>


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
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h6>Description: <?php echo strtoupper($row->description); ?></h6>
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
                <!--
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                    <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Demand Voucher Detail'))!!} ">
                </div>
                <!-->
            </div>
        </div>
    </div>
    <?php }?>

    <?php echo Form::close();?>
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
</script>

