<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
//$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
CommonHelper::companyDatabaseConnection($m);
$job_order = DB::table('job_order')->where('status',1)->where('job_order_id','=',$id)->first();
$job_order_data = DB::table('job_order_data')->where('status',1)->where('job_order_id','=',$id)->get();
CommonHelper::reconnectMasterDatabase();
?>
<style>
    .table-bordered > thead > tr > th, .table-bordered > thead > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
    border: 1px solid #000;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php if($job_order->jo_status == 1):?>
        <button type="button" class="btn btn-xs btn-success" id="BtnApproved" onclick="ApprovedJobOrder('<?php echo $job_order->job_order_id?>')">Approved</button>
        <?php endif;?>
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
                            <?php //echo CommonHelper::getCompanyName($m);?>
                            <h3 style="text-align: center;">Job Order Detail</h3>
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
                    <div style="width:40%; float:left;">
                        <table class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td>Job Order No.</td>
                                <td class="text-center"><?php echo $job_order->job_order_no; ?></td>
                            </tr>
                            <tr>
                                <td>Invoice No.</td>
                                <td class="text-center"><?php echo $job_order->invoice_no; ?></td>
                            </tr>
                            <tr>
                                <td>Invoice Date</td>
                                <td class="text-center"><?php echo $job_order->invoice_date; ?></td>
                            </tr>
                            <tr>
                                <td>Ordered By</td>
                                <td class="text-center"><?php echo $job_order->ordered_by; ?></td>
                            </tr>

                            <tr>
                                <td>Date Ordered.</td>
                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($job_order->date_ordered); ?></td>
                            </tr>
                            <tr>
                                <td>Due Date</td>
                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($job_order->due_date); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="width:40%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td>Approval Date</td>
                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($job_order->approval_date); ?></td>
                            </tr>
                            <tr>
                                <td>Completion</td>
                                <td class="text-center"><?php echo $job_order->completion_date ?></td>
                            </tr>
                            <tr>
                                <td>Client Name</td>
                                <td class="text-center"><?php $client = CommonHelper::client_name($job_order->client_name); ?><?php echo $client->client_name; ?></td>
                            </tr>
                            <tr>
                                <td>Client Job</td>
                                <td class="text-center">
                                    <?php $ClientJob = CommonHelper::get_single_row('client_job','id',$job_order->client_job);

                                        echo $ClientJob->client_job;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Contact Person</td>
                                <td class="text-center"><?php echo $job_order->contact_person; ?></td>
                            </tr>
                            <tr>
                                <td>Conatct Number</td>
                                <td class="text-center"><?php echo $job_order->contact_no ?></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div style="width:40%; float:left;">
                        <table class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td>Address</td>
                                <td class="text-center"><?php echo $job_order->address; ?></td>
                            </tr>
                            <tr>
                                <td>Job Description</td>
                                <td class="text-center"><?php echo $job_order->job_description; ?></td>
                            </tr>
                            <?php if($job_order->region_id!=""){ ?>
                            <tr>
                                <td>Signsnow Region</td>
                                <td class="text-center"><?php $region = CommonHelper::get_rgion_name_by_id($job_order->region_id); echo $region->region_name;  ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="width:40%; float:right;">
                        <table class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td>Client Address</td>
                                <td class="text-center"><?php echo $job_order->client_address; ?></td>
                            </tr>
                            <tr>
                                <td>Job Location.</td>
                                <td class="text-center"><?php echo $job_order->job_location; ?></td>
                            </tr>
                            <tr>
                                <td>Installed By.</td>
                                <td class="text-center"><?php echo $job_order->installed; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Width</th>
                            <th class="text-center">Height</th>
                            <th class="text-center" style="width: 80px;">Depth</th>
                            <th class="text-center" style="width:100px;">Quantity.</th>
                            <th class="text-center">Description</th>
                            <th ondblclick="removee()" class="text-center removee">Estimate</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $counter = 1;
                        foreach ($job_order_data as $row) {
                        CommonHelper::companyDatabaseConnection($m);
                        $product = DB::table('product')->where('product_id','=',$row->product)->first();
                        CommonHelper::reconnectMasterDatabase();

                        if($row->type_id != 0){
                            $type = CommonHelper::get_all_type_by_id($row->type_id);
                            $type_name = $type->name;
                        } else{
                            $type_name = '-';
                        }

                        ?>
                        <tr style="">
                            <td class="text-center">
                                <?php echo $counter++;?>
                            </td>
                            <td class="text-center"> <?php echo $product->p_name; ?> </td>
                            <td class="text-center"> <?php echo $type_name; ?> </td>
                            <td class="text-center"> <?php echo $row->width ;?> </td>
                            <td class="text-center"> <?php echo $row->height ;?> </td>
                            <td class="text-center"> <?php echo $row->depth ;?> </td>
                            <td class="text-center"> <?php echo $row->quantity ;?> </td>
                            <td> <?php echo $row->description ;?> </td>
                            <td class="removee" id="estimate">
                                <button type="button" class="btn btn-xs btn-default" id="BtnShow<?php echo $row->job_order_data_id?>" onclick="ShowEstimate('<?php echo $row->job_order_data_id?>')"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-xs btn-detault" id="BtnHide<?php echo $row->job_order_data_id?>" style="display: none;" onclick="HideEstimate('<?php echo $row->job_order_data_id?>')"><i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr id="ShowHide<?php echo $row->job_order_data_id?>" style="display: none;">
                            <td colspan="8">
                                <table class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width:50px;">S.No</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Uom</th>
                                        <th class="text-center">Stock Value</th>
                                        <th class="text-center">Estimate Qty</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    CommonHelper::companyDatabaseConnection($m);
                                    $estimate = DB::table('estimate')->where('job_order_data_id','=',$row->job_order_data_id)->get();
                                    CommonHelper::reconnectMasterDatabase();
                                    $Counter = 1;
                                    foreach($estimate as $Fil):
                                    ?>
                                    <tr class="text-center">
                                        <td><?php echo $Counter++;?></td>
                                        <td><?php
                                            $item = CommonHelper::get_single_row('subitem','id',$Fil->item);
                                            echo $item->sub_ic;?></td>
                                        <td>
                                            <?php
                                            $uom = CommonHelper::get_uom_name($item->uom);
                                            echo $uom?></td>
                                        <td><?php echo CommonHelper::get_complete_stock($Fil->item,$Fil->region_id)?></td>
                                        <td><?php echo $Fil->qty;?></td>



                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <?php $edit_url= url('/purchase/ShowAllImages/'.$id.'?m='.$m);?>
                    <a target="_blank" href="<?php echo $edit_url;?>" class="btn btn-sm btn-info hidden-print">Show All Images</a>
                </div>
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
                                <p id="id1"><b>{{ ucwords($job_order->username) }} </b></p>
                                <h6 class="signature_bor">Prepared By: </h6>
                                <b>   <p> <input type="text" name="" id="1" onkeyup="InnPut(this.id)" style="border:none"> <?php  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <p id="id2">&nbsp;&nbsp;</p>
                                <h6 class="signature_bor">Checked By:</h6>
                                <b>   <p> <input type="text" name="" id="2" onkeyup="InnPut(this.id)"  style="border:none"> <?php  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <p id="id3"><?php if ($job_order->approve_username==''): echo '&nbsp;&nbsp;'; else: echo $job_order->approve_username; endif  ?></p>
                                <h6 class="signature_bor">Approved By:</h6>
                                <b>  <p> <input type="text" name="" id="3" onkeyup="InnPut(this.id)" style="border:none"> </p></b>
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

<script type="text/javascript">

    function InnPut(id){
      tex = $("#"+id).val();
      $("#id"+id).text(tex);
    }

    function ShowEstimate(SectionId)
    {
        $('#ShowHide'+SectionId).fadeIn('slow');
        $('#BtnShow'+SectionId).css('display','none');
        $('#BtnHide'+SectionId).css('display','block');
    }

    function HideEstimate(SectionId)
    {
        $('#ShowHide'+SectionId).fadeOut('slow');
        $('#BtnShow'+SectionId).css('display','block');
        $('#BtnHide'+SectionId).css('display','none');
    }

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
    function ApprovedJobOrder(JoId){
        var m = '<?php echo $_GET['m'];?>';
        $('#showDetailModelOneParamerter').modal('hide');
        $('#Loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $('#ShowHide').css('display','none');
        $.ajax({
            url: '<?php echo url('/')?>/sdc/ApprovedJobOrder',
            type: "GET",
            data: { JoId:JoId,m:m},
            success:function(data) {

                $('#'+data).append('');
                $('#'+data).html('<td  class="text-center"><span class="badge badge-success" style="background-color: #00c851 !important">Success</span></td>')
                $('#BtnAppend'+data).append('');
                $('#BtnAppend'+data).html('<a class="btn btn-info btn-xs" id="BtnInvoice<?php echo $row->job_order_id?>" href="<?php echo URL('sales/createInvoiceForm')?>/'+data+'?&&m='+m+'">Create Invoice</a>');
                $('#Loader').html('');
                $('#ShowHide').css('display', 'block');
//                setInterval(function() {
//
//                },1000);





            }
        });
    }

    function removee()
    {
        $('.removee').fadeOut(100);
    }
</script>

