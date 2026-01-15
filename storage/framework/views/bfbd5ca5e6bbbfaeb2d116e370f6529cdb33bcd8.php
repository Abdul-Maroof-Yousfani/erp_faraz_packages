<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$approved=ReuseableCode::check_rights(8);
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
CommonHelper::companyDatabaseConnection($m);
$demandDetail = DB::table('demand')->where('demand_no','=',$id)->get();
CommonHelper::reconnectMasterDatabase();
foreach ($demandDetail as $row) {
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php if($row->demand_status == 1 && $row->status == 1){?>
        <?php if($approved == true):?>
        <?php echo e(Form::button('Approve', ['class' => 'btn btn-success btn-xs btn-abc hidden-print'])); ?>

        <?php endif;?>
        <?php }?>
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail','','1');?>


    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printDemandVoucherVoucherDetail">
    <?php //echo PurchaseHelper::displayApproveDeleteRepostButtonTwoTable($m,$row->demand_status,$row->status,$row->demand_no,'demand_no','demand_status','status','demand','demand_data');?>
    <?php echo Form::open(array('url' => 'pad/updateDemandDetailandApprove?m='.$m.'','id'=>'updateDemandDetailandApprove'));?>
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
    <input type="hidden" name="demandNo" value="<?php echo $id; ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">Purchase Request</h3>
                        </div>
                    </div>
                </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div>
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td>PR NO.</td>
                                <td class="text-center"><?php echo strtoupper($row->demand_no);?></td>
                            </tr>
                            <tr>
                                <td>PR Date</td>
                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->demand_date);?></td>
                            </tr>
                            <tr>
                                <td>Ref No.</td>
                                <td class="text-center"><?php echo $row->slip_no;?></td>
                            </tr>
                            <tr>
                                <td>Department / Sub Department</td>
                                <td class="text-center"><?php echo CommonHelper::getMasterTableValueById($m,'department','department_name',$row->sub_department_id);?></td>
                            </tr>

                            <input type="hidden" name="v_type" id="v_type" value="<?php echo e($row->p_type); ?>"/>


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
                                <th class="text-center">S.No</th>
                                <th class="text-center">Item</th>
                                <th class="text-center hide">Item Name</th>
                               
                                <th class="text-center">UOM</th>
                                <th class="text-center" style="width:100px;">Qty.</th>
                                <th class="text-center" style="width:100px;">Purpose</th>
                                <th class="text-center" style="width:100px;">Required Date</th>
                                <th class="text-center hide" style="width:100px;">Remaining Qty</th>

                                <!--
                                <?php if($row->demand_status == 1 && $row->status == 1){?>
                                <th class="text-center">Action</th>
                                <?php }else{?>
                                <th class="text-center">Demand Send Type</th>
                                <?php }?>
                                        <!-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection($m);
                            $demandDataDetail = DB::table('demand_data')->where('demand_no','=',$id)->get();
                            CommonHelper::reconnectMasterDatabase();
                            $counter = 1;
                            $totalCountRows = count($demandDataDetail);
                            foreach ($demandDataDetail as $row1){
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $counter++;?>
                                    <input type="hidden" name="rowId[]" id="rowId_<?php $row1->id;?>" value="<?php echo $row1->id;?>">
                                    <input type="hidden" name="subItemId_<?php echo $row1->id;?>" id="subItemId_<?php echo $row1->id;?>" value="<?php echo $row1->sub_item_id;?>">
                                </td>

                                <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
                                $sub_ic_detail= explode(',',$sub_ic_detail)
                                ?>
                               
                                <td><?php echo e($sub_ic_detail[3] .' -- '.CommonHelper::get_item_name($row1->sub_item_id)); ?></td>
                                <td> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                <td class="text-center"><?php echo number_format($row1->qty,2);?></td>
                                <td class="text-center"><?php echo e($row1->purpose); ?></td>
                                <td class="text-center"><?php echo e(CommonHelper::changeDateFormat($row1->required_date)); ?></td>
                                <td class="text-center hide"><?php echo CommonHelper::get_remaining_qty($row1->sub_item_id)?></td>
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
                                    <b>   <p><?php echo strtoupper($row->username);  ?></p></b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Checked By:</h6>
                                    <b>   <p><?php  ?></p></b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Approved By:</h6>
                                    <b>  <p><?php echo strtoupper($row->approve_username);  ?></p></b>
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

