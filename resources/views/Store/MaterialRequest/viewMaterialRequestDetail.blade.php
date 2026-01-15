<?php
    use App\Helpers\CommonHelper;
    use App\Helpers\PurchaseHelper;
    use App\Helpers\ReuseableCode;
    $approved = ReuseableCode::check_rights(8);
    $m = session()->get('run_company');
    $currentDate = date('Y-m-d');
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php if($materialReqDetail->material_request_status == 1 && $materialReqDetail->status == 1){?>
            <?php if($approved == true):?>
                {{ Form::button('Approve', ['class' => 'btn btn-success btn-abc hidden-print']) }}
            <?php endif;?>
        <?php }?>
        <?php CommonHelper::displayPrintButtonInView('printMaterialRequestVoucherDetail','','1');?>


    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printMaterialRequestVoucherDetail">
    <?php echo Form::open(array('url' => 'pd/updateMaterialRequestandApprove?m='.$m.'','id'=>'updateMaterialRequestandApprove'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
        <input type="hidden" name="material_request_no" value="<?php echo $materialReqDetail->material_request_no; ?>">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                    echo ' '.'('.date('D', strtotime($x)).')';?></label>
                            </div>
                            <div style="line-height:20px;">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <img style=" width:180px;margin:-20px 0px 0px 0px;" src="{{ url('public/logooold.png') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="line-height:5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="voucherFloatLeft">
                            <table  class="table table-bordered table-striped table-condensed tableMargin tableBorder">
                                <tbody>
                                    <tr>
                                        <td style="font-weight:bold;color:#000;"><strong>MR NO.</strong></td> 
                                        <td><?php echo strtoupper($materialReqDetail->material_request_no);?></td> </tr> 
                                    <tr> 
                                        <td style="font-weight:bold;color:#000;"><strong>MR Date</strong></td>
                                        <td><?php echo CommonHelper::changeDateFormat($materialReqDetail->material_request_date);?></td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                        </div>
                       
                    </div>
                   
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped table-condensed tableMargin tableBorder">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:50px;">S.No</th>
                                        <th class="text-center">Item Code</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Qty.</th>
                                        <th class="text-center">Issued Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $counter = 1;
                                    $totalCountRows = count($materialReqDataDetail);
                                    foreach ($materialReqDataDetail as $row1){
                                ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php echo $counter++;?>
                                                <input type="hidden" name="rowId[]" id="rowId_<?php $row1->id;?>" value="<?php echo $row1->id;?>">
                                            </td>
                                            <td class="text-center">{{ $row1->item_code }}</td>
                                            <td title="{{$row1->sub_item_id}}">{{ $row1->sub_ic}}</td>
                                            <td>{{ $row1->sub_description}}</td>
                                            <td class="text-center"><?php echo $row1->qty;?></td>
                                            <td class="text-center"><?php if(empty($row1->totalIssueQty)): echo 0; else: echo $row1->totalIssueQty; endif;?></td>


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
                                <h6>Description: <?php echo strtoupper($materialReqDetail->description); ?></h6>
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
                                        <b>   <p><?php echo strtoupper($materialReqDetail->username);  ?></p></b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Checked By:</h6>
                                        <b>   <p><?php  ?></p></b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Approved By:</h6>
                                        <b>  <p><?php echo strtoupper($materialReqDetail->approve_username);  ?></p></b>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php //echo CommonHelper::get_company_footer_detail(Session::get('run_company'));?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        var postData = $('#updateMaterialRequestandApprove').serializeArray();
        var formURL = $('#updateMaterialRequestandApprove').attr("action");
        $.ajax({
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data){
                $('#showDetailModelOneParamerter').modal('toggle');
                location.reload();
            }
        });
    }
</script>