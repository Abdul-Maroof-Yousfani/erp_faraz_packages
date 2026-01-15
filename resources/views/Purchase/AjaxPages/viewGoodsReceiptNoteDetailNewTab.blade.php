<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;

?>
@extends('layouts.default')

@section('content')
<?php
$approve=ReuseableCode::check_rights(23);
$id = $_GET['GrnNo'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$goodsReceiptNoteDetail = DB::table('goods_receipt_note')->where('grn_no','=',$id)->get();
$AddionalExpense = DB::table('addional_expense')->where('voucher_no','=',$id);

CommonHelper::reconnectMasterDatabase();

foreach ($goodsReceiptNoteDetail as $row) {
$demandType = $row->demand_type;
$grn_status = $row->grn_status;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        @if ($approve==true)
            @if($grn_status==1)
                <button onclick="approve_grn({{$row->id}}); btnDis()" id="BtnApproved"  type="button" class="btn btn-success btn-xs">Approve</button>
            @endif
        @endif
        <?php CommonHelper::displayPrintButtonInView('printGoodsReceiptNoteVoucherDetail','LinkHide','1');?>
    </div>
</div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row">
    <?php if($demandType == 2){?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <?php echo PurchaseHelper::displayApproveDeleteRepostButtonGoodsReceiptNote($m,$row->grn_status,$row->status,$row->grn_no,'grn_no','grn_status','status','goods_receipt_note','grn_data','1');?>
        </div>
        <div style="line-height:5px;">&nbsp;</div>
        <?php }?>
        <?php echo Form::open(array('url' => 'pad/createStoreChallanandApproveGoodsReceiptNote?m='.$m.'','id'=>'createStoreChallanandApproveGoodsReceiptNote'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">


        <input type="hidden" name="grnNo" value="<?php echo $id; ?>">
        <input type="hidden" name="grnDate" value="<?php echo $row->grn_date; ?>">
        <input type="hidden" name="prNo" value="<?php echo $row->po_no; ?>">
        <input type="hidden" name="prDate" value="<?php echo $row->po_date; ?>">
        <input type="hidden" name="supplier_id" value="<?php echo $row->supplier_id; ?>">
        <input type="hidden" name="sub_deparment_id" value="<?php echo $row->sub_department_id; ?>">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printGoodsReceiptNoteVoucherDetail">
            <div class="well">
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
                                <h3 style="text-align: center;">Goods Receipt Note</h3>
                            </div>
                        </div>
                    </div>
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:40%; float:left;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td style="width:40%;">GRN No.</td>
                                        <td style="width:60%;"><?php echo strtoupper($row->grn_no);?></td>
                                    </tr>
                                    <tr>
                                        <td>GRN Date</td>
                                        <td><?php echo CommonHelper::changeDateFormat($row->grn_date);?></td>
                                    </tr>
                                    <?php if($row->type != 5):?>
                                    <tr>
                                        <td style="width:40%;">PO No.</td>
                                        <td style="width:60%;"><?php echo strtoupper($row->po_no);?></td>
                                        <?php   $po_type=CommonHelper::get_po_type_query($row->po_no); ?>
                                    </tr>
                                    <tr>
                                        <td>PO Date</td>
                                        <td><?php if ($row->type==0): echo CommonHelper::changeDateFormat($row->po_date);endif;?></td>
                                    </tr>
                                    <tr>
                                        <td>Bill Date</td>
                                        <td><?php  echo CommonHelper::changeDateFormat($row->bill_date);?></td>
                                    </tr>
                                    <?php endif;?>
                                    <tr>
                                        <td>Supplier Invoice No</td>
                                        <td><?php  echo $row->supplier_invoice_no;?></td>
                                    </tr>

                                    <tr>
                                        <td>Delivery Challan No </td>
                                        <td><?php echo $row->delivery_challan_no;;?></td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>


                            <div style="width:40%; float:right;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>


                                    <tr>
                                        <td style="width:60%;">Delivery Detail/ Vehicle #.</td>
                                        <td style="width:40%;"><?php echo $row->delivery_detail;?></td>
                                    </tr>

                                    <tr>
                                        <td>Department / Sub Department</td>
                                        <td><?php echo CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id);?></td>
                                    </tr>

                                    <tr>
                                        <td>Supplier Name</td>
                                        <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id);?></td>
                                    </tr>
                                    <tr>
                                        <td>Supplier Address</td>
                                        <td width=""><?php echo CommonHelper::get_supplier_address($row->supplier_id);;?></td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <?php if($row->type == 5):?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>

                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Batch Code</th>
                                        <th class="text-center">Qty<span class="rflabelsteric"></span></th>
                                        <th class="text-center"> Rate</th>
                                        <th class="text-center"> Amount</th>
                                        <th class="text-center">Location</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    CommonHelper::companyDatabaseConnection($m);
                                    $grnDataDetail = DB::table('grn_data')->where('grn_no','=',$id)->get();


                                    CommonHelper::reconnectMasterDatabase();
                                    $counter = 1;
                                    $total_import_amount=0;
                                    $total_import_qty=0;
                                    foreach ($grnDataDetail as $row1)
                                    {

                                    $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail);
                                    ?>




                                    <?php
                                    echo    $item_name=  CommonHelper::get_item_name($row1->sub_item_id).'saasasasas';
                                    ?>
                                    <tr>
                                        <td title="{{$item_name}}"  class="text-center">
                                            <?php $accType = Auth::user()->acc_type;
                                            if($accType == 'client'):
                                            ?>
                                            <a class="LinkHide" href="<?php echo url('/') ?>/store/fullstockReportView?pageType=&&parentCode=97&&m=<?php echo Session::get('run_company');?>&&sub_item_id=<?php echo $row1->sub_item_id; ?>&&warehouse_id=<?php echo $row1->warehouse_id; ?>#SFR" target="_blank">
                                                <?php
                                                if($row1->description !=""):
                                                    echo $row1->description;
                                                else:
                                                    echo $item_name;
                                                endif;
                                                ?>
                                            </a>
                                            <?php else:?>
                                    <?php
                                                if($row1->description !=""):
                                                    echo $row1->description;
                                                else:
                                                    echo $item_name;
                                                endif;
                                                ?>
                                    <?php endif;?>


                                        </td>
                                        <td class="text-center"><?php echo $row1->batch_code?></td>
                                        <td  class="text-center"><?php echo  number_format($row1->purchase_recived_qty,2)?></td>
                                        <td  class="text-center"><?php echo  number_format($row1->rate,2)?></td>
                                        <td  class="text-center"><?php echo  number_format($row1->amount,2)?></td>
                                        <td class="text-center">
                                            <?php $Warehouse =  CommonHelper::get_single_row('warehouse','id',$row1->warehouse_id);
                                            echo $Warehouse->name;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php

                                    ?>

                                    <?php
                                    $counter++;
                                    $total_import_amount+=$row1->amount;
                                    $total_import_qty+=$row1->purchase_recived_qty;
                                    }
                                    ?>
                                    </tbody>
                                    <tr class="text-center" style="background-color: darkgrey">
                                        <td colspan="2">Total</td>
                                        <td style="font-size: larger;font-weight: bold"><?php echo  number_format($total_import_qty,2) ?></td>
                                        <td></td>
                                        <td style="font-size: larger;font-weight: bold"><?php echo  number_format($total_import_amount,2) ?></td>
                                        <td></td>
                                    </tr>
                                </table>
                                <div>
                                    <?php

                                    $bank_payment=  DB::Connection('mysql2')->table('import_payment')->where('import_id',$row->import_id)->sum('amount_in_pkr');

                                    $expense_data=DB::Connection('mysql2')->table('import_expense')->where('import_id',$row->import_id);
                                    $total_exp=$expense_data->sum('duty')+$expense_data->sum('eto')+$expense_data->sum('do')+$expense_data->sum('appraisal')+$expense_data->sum('fright')+$expense_data->sum('insurance')
                                            +$expense_data->sum('expense')+$expense_data->sum('other_expense');
                                    ?>



                                    <p>Total Paid Amount : {{number_format($bank_payment,2)}}</p>
                                    <p>Total Expense : {{number_format($total_exp,2)}}</p>
                                </div>
                            </div>
                        </div>
                        <?php else:?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">
                            <label for="">Show Detail <input type="checkbox"  id="CheckUnCheck"></label>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Sr No.</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Batch Code</th>
                                        <th class="text-center">Ordered Qty	<span class="rflabelsteric"></span></th>
                                        <th class="text-center">Received Qty<span class="rflabelsteric"></span></th>
                                        <th class="ShowHideRate text-center" style="display: none;" > Rate</th>
                                        <th class="ShowHideAmount text-center" style="display: none;" > Amount</th>
                                        <th class="ShowHideDiscountPercent text-center" style="display: none;" > Discount %</th>
                                        <th class="ShowHideDiscountAmount text-center" style="display: none;" > Discount Amount</th>
                                        <th class="ShowHideNetAmount text-center" style="display: none;" > Net Amount</th>
                                        <th class="text-center">BAL. QTY. Receivable</th>
                                        <th class="text-center">Location</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    CommonHelper::companyDatabaseConnection($m);
                                    $grnDataDetail = DB::table('grn_data')->where('grn_no','=',$id)->get();


                                    CommonHelper::reconnectMasterDatabase();
                                    $counter = 1;
                                    foreach ($grnDataDetail as $row1)
                                    {

                                    $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail);
                                    ?>




                                    <?php
                                    $item_name= CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->sub_item_id);
                                    ?>
                                    <tr>
                                        <td><?php echo $counter?></td>
                                        <td title="{{$item_name}}" class="text-center">

                                            <?php $accType = Auth::user()->acc_type;
                                            if($accType == 'client'):
                                            ?>
                                            <a class="LinkHide" href="<?php echo url('/') ?>/store/fullstockReportView?pageType=&&parentCode=97&&m=<?php echo Session::get('run_company');?>&&sub_item_id=<?php echo $row1->sub_item_id; ?>&&warehouse_id=<?php echo $row1->warehouse_id; ?>#SFR" target="_blank">
                                                <?php
                                                if($row1->description !=""):
                                                    echo $row1->description;
                                                else:
                                                    echo $item_name;
                                                endif;
                                                ?>
                                            </a>
                                            <?php else:?>
                                    <?php
                                                if($row1->description !=""):
                                                    echo $row1->description;
                                                else:
                                                    echo $item_name;
                                                endif;
                                                ?>
                                    <?php endif;?>
                                        </td>
                                        <td class="text-center"><?php echo $row1->batch_code?></td>
                                        <td class="text-center"><?php echo number_format($row1->purchase_approved_qty,2);?></td>
                                        <td  class="text-center"><?php echo  number_format($row1->purchase_recived_qty,2)?></td>
                                        <td  class="text-center ShowHideRate" style="display: none;"><?php echo  number_format($row1->rate,2)?></td>
                                        <td  class="text-center ShowHideAmount" style="display: none;"><?php echo  number_format($row1->amount,2)?></td>
                                        <td  class="text-center ShowHideDiscountPercent" style="display: none;"><?php echo  number_format($row1->discount_percent,2)?></td>
                                        <td  class="text-center ShowHideDiscountAmount" style="display: none;"><?php echo  number_format($row1->discount_amount,2)?></td>
                                        <td  class="text-center ShowHideNetAmount" style="display: none;"><?php echo  number_format($row1->net_amount,2)?></td>
                                        <td @if($row->type==0) @if($row1->purchase_recived_qty > $row1->purchase_approved_qty)style="background-color: yellow"@endif @endif class="text-center"><?php echo number_format($row1->bal_reciable,2);?></td>
                                        <td class="text-center">
                                            <?php $Warehouse =  CommonHelper::get_single_row('warehouse','id',$row1->warehouse_id);
                                            echo $Warehouse->name;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php

                                    ?>

                                    <?php  $counter++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php if($AddionalExpense->count() > 0){?>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="table-responsive">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <td colspan="3"><strong><h4>Addional Expense</h4></strong></td>
                                    </tr>
                                    <tr>

                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Ordered Qty	<span class="rflabelsteric"></span></th>
                                        <th class="text-center">Received Qty<span class="rflabelsteric"></span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $ExpCounter = 1;
                                    foreach($AddionalExpense->get() as $Fil):
                                    ?>
                                    <tr class="text-center">
                                        <td><?php echo $ExpCounter++;?></td>
                                        <td>
                                            <?php $Accounts = CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                            echo $Accounts->name;
                                            ?>
                                        </td>
                                        <td><?php echo $Fil->amount?></td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>


                                <?php }?>

                            </div>
                        </div>
                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h6>Description: <?php echo ucwords($row->main_description); ?></h6>
                                </div>
                            </div>
                            <style>
                                .signature_bor {
                                    border-top:solid 1px #CCC;
                                    padding-top:7px;
                                }
                            </style>
                        </div>
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
                                        <b>  <p><?php echo strtoupper($row->approve_username)?></p></b>
                                    </div>

                                </div>
                            </div>
                        </div>




                    </div>

                </div>
            </div>
            <?php }

            ?>


        </div>
    </div>


    <script type="text/javascript">


        function btnDis()
        {
            $('#BtnApproved').prop('disabled',true);
        }
        $(document).ready(function(){
            $('.ShowHideRate').fadeOut();
            $('.ShowHideAmount').fadeOut();
            $('.ShowHideDiscountPercent').fadeOut();
            $('.ShowHideDiscountAmount').fadeOut();
            $('.ShowHideNetAmount').fadeOut();
        });

        $('input[id="CheckUnCheck"]').click(function() {
            if($(this).prop("checked") == true) {
                $('.ShowHideRate').fadeIn();
                $('.ShowHideAmount').fadeIn();
                $('.ShowHideDiscountPercent').fadeIn();
                $('.ShowHideDiscountAmount').fadeIn();
                $('.ShowHideNetAmount').fadeIn();
                $('.ShowHideDesc').fadeOut();
            }
            else if($(this).prop("checked") == false) {
                $('.ShowHideRate').fadeOut();
                $('.ShowHideAmount').fadeOut();
                $('.ShowHideDiscountPercent').fadeOut();
                $('.ShowHideDiscountAmount').fadeOut();
                $('.ShowHideNetAmount').fadeOut();
                $('.ShowHideDesc').fadeIn();
            }
        });

        function import_costing(id,number)
        {
            alert();
        }

        function approveCompanyPurchaseGoodsReceiptNote(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo) {
            $.ajax({
                url: ''+baseUrl+'/pd/approveCompanyPurchaseGoodsReceiptNote',
                type: "GET",
                data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
                success:function(data) {
                    filterVoucherList();
                }
            });
        }

        $(".btn-abc-submit").click(function(e){
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

            var postData = $('#createStoreChallanandApproveGoodsReceiptNote').serializeArray();
            var formURL = $('#createStoreChallanandApproveGoodsReceiptNote').attr("action");

            $.ajax({
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data){
                    $('#showDetailModelOneParamerter').modal('toggle');
                    filterVoucherList();
                }
            });
        }

        function checkQty(paramOne,paramTwo,paramThree) {
            var remainingQty = $('#'+paramOne+'').val();
            if(parseInt(paramTwo) <= parseInt(remainingQty)){
            }else{
                $('#'+paramThree+'').val('');
                alert('Issue Qty not allow to max Demand Qty!');
            }
        }

        function makeSummarySection() {
            var netTotalAmount = $('#netTotalAmount').val();
            var totalPaymentAmount = $('#totalPaymentAmount').val();

            var totalBalanceAmount = parseInt(netTotalAmount) - parseInt(totalPaymentAmount);
            $('#cellTotalBalance').html(totalBalanceAmount);
            $('#cellTotalPaymentAmount').html(totalPaymentAmount);

        }
        makeSummarySection();

    </script>
@endsection