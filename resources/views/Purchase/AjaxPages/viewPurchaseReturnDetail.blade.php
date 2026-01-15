<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\PurchaseHelper;

$Checking = $_GET['id'];
$m = $_GET['m'];
$Checking = explode(',',$Checking);

if($Checking[0] == 'other')
{
    $id = $Checking[1];
}
else{
    $id = $Checking[0];
}


$currentDate = date('Y-m-d');
$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
CommonHelper::companyDatabaseConnection($m);
$MasterData = DB::table('purchase_return')->where('pr_no','=',$id)->get();
CommonHelper::reconnectMasterDatabase();
foreach ($MasterData as $row) {
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
                            <h3 style="text-align: center;">Purchase Return</h3>
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
                            <td>Purchase Return No.</td>
                            <td class="text-center"><?php echo strtoupper($row->pr_no);?></td>
                        </tr>
                        <tr>
                            <td>Purchase Return Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->pr_date);?></td>
                        </tr>
                        <tr>
                            <td>Supplier Name</td>
                            <td class="text-center"><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id);?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>

                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">

                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>

                            <td>GRN No</td>
                            <td class="text-center"><?php echo strtoupper($row->grn_no); ?></td>
                        </tr>
                        <tr>
                            <td>GRN Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->grn_date);?></td>
                        </tr>
                        <tr>
                            <?php $po_no=     DB::Connection('mysql2')->table('goods_receipt_note')->where('grn_no',$row->grn_no)->value('po_no'); ?>
                            <td>PO NO</td>
                            <td class="text-center"><?php echo strtoupper($po_no); ?></td>
                        </tr>
                        <tr>
                            <td>Created Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->created_date);?></td>
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
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">Received Qty</th>
                                <th style="display: none" class="text-center">Rate</th>
                                <th style="display: none" class="text-center">Amount</th>
                                <th class="text-center">Return Qty</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection($m);
                            $DetailData = DB::table('purchase_return_data')->where('pr_no','=',$id)->get();
                            CommonHelper::reconnectMasterDatabase();
                            $counter = 1;
                            $totalCountRows = count($DetailData);
                            foreach ($DetailData as $row1){
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $counter++;?>

                                </td>
                                <td title="{{$row1->sub_item_id}}">
                                    <?php $accType = Auth::user()->acc_type;
                                    if($accType == 'client'):
                                    ?>
                                    <a class="LinkHide" href="<?php echo url('/') ?>/store/fullstockReportView?pageType=&&parentCode=97&&m=<?php echo Session::get('run_company');?>&&sub_item_id=<?php echo $row1->sub_item_id; ?>&&warehouse_id=<?php echo $row1->warehouse_id; ?>#SFR" target="_blank">
                                        <?php
                                        echo $row1->description;
                                        ?>
                                    </a>
                                    <?php else:?>
                                    <?php
                                            echo $row1->description;
                                        ?>
                                    <?php endif;?>


                                </td>
                                <td>
                                    <?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$row1->warehouse_id);?>

                                </td>
                                <td class="text-center"><?php echo number_format($row1->recived_qty,2);?></td>
                                <td style="display: none"  class="text-center"><?php echo number_format($row1->rate,2);?></td>
                                <td style="display: none"  class="text-center"><?php echo number_format($row1->amount,2);?></td>
                                <td class="text-center"><?php echo number_format($row1->return_qty,2);?></td>

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
                            <h6>Remarks: <?php echo strtoupper($row->remarks); ?></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="check">
                                Show Voucher
                                <input id="check"  type="checkbox" onclick="checkk()" class="check">
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    FinanceHelper::companyDatabaseConnection($m);

                    $rvsDetail = DB::table('transactions')->where('voucher_no','=',$row->pr_no)->where('status',1)->orderby('debit_credit','1')->get();

                    $costing_data=$rvsDetail;
                     $type = 5;
                    FinanceHelper::reconnectMasterDatabase();
                    $counter = 1;
                    $g_t_debit = 0;
                    $g_t_credit = 0;

                    ?>

                    <table style="display: none;"  id=""  class="table table-bordered tra">
                        <tr class="">
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Account</th>




                            <th class="text-center" style="width:150px;">Debit</th>
                            <th class="text-center" style="width:150px;">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($rvsDetail as $row2) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $counter++;?></td>
                            <td><?php echo FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);?></td>



                            <td class="debit_amount text-right">

                                <?php
                                if($row2->debit_credit == 1)
                                {
                                    $g_t_credit += $row2->amount;
                                    echo number_format($row2->amount,2);
                                }
                                ?>
                            </td>
                            <td class="credit_amount text-right">
                                <?php
                                if($row2->debit_credit == 0)
                                {
                                    $g_t_debit += $row2->amount;
                                    echo number_format($row2->amount,2);
                                }
                                ?>
                            </td>

                        </tr>
                        <?php
                        }
                        ?>
                        <tr class="sf-table-total">
                            <td colspan="2">
                                <label for="field-1" class="sf-label"><b>Total</b></label>
                            </td>
                            <td class="text-right"><b><?php echo number_format($g_t_credit,2);?></b></td>
                            <td class="text-right"><b><?php echo number_format($g_t_debit,2);?></b></td>
                        </tr>
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
                                    <b>   <p><?php echo strtoupper($row->username);  ?></p></b>
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

    function checkk()
    {

        if ($("#check").is(":checked"))
        {


            $('.tra').css('display','block');
        }

        else
        {
            $('.tra').css('display','none');
        }
    }

    

</script>

