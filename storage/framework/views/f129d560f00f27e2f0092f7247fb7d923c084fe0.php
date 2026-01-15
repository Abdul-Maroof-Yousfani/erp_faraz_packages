<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;

$id = $_GET['id'];

$m = Session::get('run_company');
$approve=ReuseableCode::check_rights(16);

$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$purchaseRequestDetail = DB::table('purchase_request')->where('id','=',$id)->get();
$purchaseRequestDataDetail = DB::table('purchase_request_data')->where('master_id','=',$id)->get();
$quotation_ref = DB::table('purchase_request_data AS prd')->join('demand AS d','d.demand_no','=', 'prd.demand_no')
->leftJoin('quotation AS q','d.id','=','q.pr_id')->where('prd.master_id','=',$id)->select(['q.ref_no'])->first();
CommonHelper::reconnectMasterDatabase();

if($_GET['pageType']=='viewlist'){
    $EmailPrintSetting = $_GET['EmailPrintSetting'];
} else {
    $EmailPrintSetting = $_GET['EmailPrintSetting'];
}
?>

<div id="Pdfsetting" <?php if($EmailPrintSetting==2){ ?> style="display: none;" <?php } ?> >
    <button onclick="change()" type="button" class="btn btn-primary btn-xs">Show PKR</button>

    <style>
        textarea {
            border-style: none;
            border-color: Transparent;

        }
    </style>
    <div style="line-height:5px;">&nbsp;</div>
</div>

<?php
    foreach ($purchaseRequestDetail as $row) {
        if($row->currency_id == 3):
            $cur = 'PKR';
        elseif($row->currency_id == 4):
            $cur = 'USD';
        endif;
?>

<div class="row" >
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 printHide">
        <input type="text" name="email" id="email" value="" class="form-control" placeholder="Enter Email Address">
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 printHide">
        <button class="btn btn-primary btn-sm" onclick="EmailSent()"> Email Sent </button>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?php
        if ($approve==true):
        echo StoreHelper::displayApproveDeleteRepostButtonPurchaseRequest($m,$row->purchase_request_status,$row->status,$row->id,'purchase_request_no','purchase_request_status','status','purchase_request','purchase_request_data');
        endif;
        ?>
        <?php if($approve == true): ?>:
            <?php if($row->purchase_request_status == 1 || $row->purchase_request_status == 2): ?>
                <button class="delete-modal btn btn-danger-2" data-dismiss="modal" aria-hidden="true" onclick="RejectPo('<?php echo e($row->id); ?>')">
                    Reject
                </button>
            <?php endif; ?>
        <?php endif; ?>
        <?php CommonHelper::displayPrintButtonInView('po_detail','LinkHide','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="po_detail">
        <div class="well">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                    <div class="head-text">
                        <h3 class="subHeadingLabelClass" style="text-align: center;">Purchase Order</h3>
                        <h4>
                            <span style="font-size: 16px">Zahabiya Chemical Industries (Pvt.) Ltd.</span> <br>
                            Plot #: 186, Sector 24, Korangi Industrial Area, Karachi, <br>
                            Tel: +92-21-35051890-91, Email: info@zahabiya.com, <br> Web: https://zahabiya.com
                            NTN: 5356581-6, <br> STRN: 3277876169232
                        </h4>
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                    <div class="table-responsive">
                        <table class="table sale_older_tab3 table table-bordered sf-table-list" style="width: 100%" >
                            <tbody>
                                <tr>
                                    <td style="text-align:center; border:none !important;" colspan="2">ZCI-PM.05</td>
                                </tr>
                                <tr>
                                    <td style="border:none !important;">Rev. #:</td>
                                    <td style="border:none !important;">01</td>
                                </tr>
                                <tr>
                                    <td style="border:none !important;">Rev. Date:</td>
                                    <td style="border:none !important;"><?php echo e(date('M Y')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php  $supplier = CommonHelper::getSupplierDetail($row->supplier_id); ?>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:45%; float:left;">
                        <div class="table-responsive">
                            <table  class="table sale_older_tab3 table table-bordered sf-table-list">
                                <tbody>
                                <tr>
                                    <td>Supplier Name</td>
                                    <td><?php echo e($supplier->name); ?></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td><?php echo e($supplier->address); ?></td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td><?php echo e($supplier->mobile_no); ?></td>
                                </tr>
                                <tr>
                                    <td>Supplier Representative</td>
                                    <td><?php echo e($supplier->contact_person); ?></td>
                                </tr>
                                <tr>
                                    <td>NTN / STRN</td>
                                    <td><?php echo e($supplier->ntn); ?></td>
                                </tr>
                                <tr>
                                    <td>Quotation Ref</td>
                                    <td><?php echo e($quotation_ref->ref_no ?? ''); ?></td>
                                </tr>
                            
                                <!-- <tr>
                                    <td>PR No.</td>
                                    <td><?php echo CommonHelper::get_po_type($row->po_type);?></td>
                                </tr> -->
                                
                                <!-- <tr>
                                    <td>Builty No</td>
                                    <td><?php echo $row->builty_no;?></td>
                                </tr> -->
                                <!-- <tr>
                                    <td  class="showw" style="width:60%;">Agent </td>
                                    <td class="showw" style="width:40%;"><?php echo e(CommonHelper::get_sub_dept_name($row->agent) ?? ''); ?></td>
                                </tr> -->
                                <!-- <tr>
                                    <td  class="showw" style="width:60%;">Remarks </td>
                                    <td class="showw" style="width:40%;"><?php echo e($row->remarks ?? ''); ?></td>
                                </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div style="width:45%; float:right;">
                        <div class="table-responsive">
                            <table  class="table sale_older_tab3 table table-bordered sf-table-list">
                                <tbody>
                                <tr>
                                    <td style="width:60%;">PR No.</td>
                                    <td style="width:40%;"><?php echo e(strtoupper($purchaseRequestDataDetail[0]->demand_no)); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;">PO NO.</td>
                                    <td style="width:50%;"><?php echo e(strtoupper($row->purchase_request_no)); ?></td>
                                </tr>
                                <tr>
                                    <td>PO Date</td>
                                    <td><?php echo e(CommonHelper::changeDateFormat($row->purchase_request_date)); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:60%;">Destination</td>
                                    <td style="width:40%;"><?php echo e($row->destination); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:60%;">Due Date</td>
                                    <td style="width:40%;"><?php echo e(CommonHelper::changeDateFormat($row->due_date)); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:60%;">Payment Terms</td>
                                    <td style="width:40%;">
                                        <?php if($row->terms_of_paym == 1): ?> Advance 
                                        <?php elseif($row->terms_of_paym == 2): ?> Against Delivery
                                        <?php elseif($row->terms_of_paym == 3): ?> Credit
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:60%;">No. of Days</td>
                                    <td style="width:40%;"><?php echo e($row->no_of_days ?? $supplier->no_of_days ?? ''); ?></td>
                                </tr>


                                <!-- <tr>
                                    <td style="width:60%;">Supplier Reference No.</td>
                                    <td style="width:40%;"><?php echo $row->trn;?></td>
                                </tr>
                                <tr class="hide">
                                    <td>Department / Sub Department</td>
                                    <td><?php echo CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id);?></td>
                                </tr> -->
                                <!-- <tr>
                                    <td style="width:60%;">Terms Of Delivery</td>
                                    <td style="width:40%;"><?php echo $row->term_of_del;?></td>
                                </tr>
                                <tr>
                                    <td style="width:60%;">Terms Of Payment</td>
                                    <td style="width:40%;"><?php echo $row->terms_of_paym;?></td>
                                </tr> -->

                                
                                <!-- <?php $currency= CommonHelper::get_curreny_name($row->currency_id);?>

                                <tr>
                                    <td  class="showw" style="width:60%;">Currency  </td>
                                    <td class="showw" style="width:40%;"><?php echo e($currency); ?></td>
                                </tr>
                                <tr>
                                    <td  class="showw" style="width:60%;">Currency Rate </td>
                                    <td class="showw" style="width:40%;"><?php echo e($row->currency_rate); ?></td>
                                </tr> -->
                                <!-- <tr>
                                    <td  class="showw" style="width:60%;">Commission </td>
                                    <td class="showw" style="width:40%;"><?php echo e($row->commission); ?></td>
                                </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table  class="table sale_older_tab3 table table-bordered sf-table-list">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">S.NO</th>
                                        <!-- <th class="text-center">PR NO</th>
                                        <th style="font-size: 13px;" class="text-center">PR  Date </th> -->

                                        <th style="text-align: center">Item Name</th>
                                        <th style="text-align: center">UOM</th>
                                        <th style="text-align: center" >Qty</th>
                                        <!-- <th class="text-center hide" >No Of Carton <span class="rflabelsteric"><strong>*</strong></span></th> -->
                                        <th style="text-align: center">Rate</th>
                                        <!-- <th class="text-center">Amount(PKR)</th> -->
                                        <!-- <th class="text-center">Amount</th> -->

                                        <!-- <th class="text-center">Discount%</th>
                                        <th class="text-center">Discount Amount</th> -->
                                        <th style="text-align: center">Net Amount</th>
                                        <!-- <th class="text-center showw" style="display: none">Amount In PKR</th> -->
                                        <th style="text-align: center" class="printHide">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                            $total=0;
                                    $total_exchange=0;
                                    $actual_amount =0;
                                    foreach ($purchaseRequestDataDetail as $row1){
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo e($counter++); ?></td>
                                        
                                        

                                        <td title="item_name=<?php echo e(CommonHelper::get_item_name($row1->sub_item_id)); ?>">
                                            <?php $accType = Auth::user()->acc_type;
                                            if($accType == 'client'):
                                            ?>
                                            <a class="LinkHide" href="<?php echo url('/') ?>/store/stockReportView?item_id=<?php echo $row1->sub_item_id?>&&pageType=&&parentCode=126&&m=1" target="_blank">
                                                <?php echo CommonHelper::get_item_name($row1->sub_item_id);//echo CommonHelper::get_item_name($row1->sub_item_id);?>
                                            </a>
                                            <?php else:?>
                                            <?php echo CommonHelper::get_item_name($row1->sub_item_id);?>
                                            <?php endif;?>
                                        </td>


                                        <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
                                        $sub_ic_detail= explode(',',$sub_ic_detail)
                                        ?>

                                        <td> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?>
                                            <input type="hidden" value="<?php echo $row1->sub_item_id?>" id="sub_<?php echo $counter?>">
                                        </td>
                                        <td style="text-align: center"><?php echo $row1->purchase_approve_qty;?></td>
                                        <td class="text-center hide"><?php echo $row1->no_of_carton;?></td>
                                        <td style="text-align: center"><?php echo e($cur); ?> <?php echo e(number_format($row1->rate, 3)); ?></td>
                                        <!-- <td class="text-right"><?php echo number_format($row1->rate * $row1->purchase_approve_qty * $row->currency_rate,3);?></td> -->
                                        <!-- <td class="text-right"><?php echo number_format($row1->rate * $row1->purchase_approve_qty,3);?></td> -->  

                                        <td style="text-align: center"><?php echo e($cur); ?> <?php echo e(number_format($row1->net_amount,3)); ?></td>
                                        <td style="display: none"  class="text-right showw"><?php echo number_format($row1->net_amount*$row->currency_rate,3);?></td>
                                        <td style="text-align: center" class="printHide">
                                            <input onclick="view_history(<?php echo $counter?>)" type="checkbox" id="view_history<?php echo $counter?>">
                                        </td>
                                    </tr>
                                    <?php
                                            $actual_amount +=  $row1->rate * $row1->purchase_approve_qty;
                                            $total+=$row1->net_amount;
                                    $total_exchange+=$row1->net_amount*$row->currency_rate;
                                    }
                                    ?>

                                    <tr>

                                        <td colspan="5">Total</td>
                                        <!-- <td style="background-color: darkgray" class="text-right"  ><?php echo e(number_format($actual_amount,2)); ?> (<?php echo e($currency); ?>)</td> -->
                                        <td style="text-align: center" ><?php echo e($cur); ?> <?php echo e(number_format($total,2)); ?></td>
                                        <!-- <td style="background-color: darkgray;display: none" class="text-right showw"><?php echo e(number_format($total_exchange,2)); ?></td> -->
                                        <td class="printHide"></td>
                                    </tr>

                                    <tr>
                                        <?php
                                            $pkr_sale_tax_amount = ($total_exchange*$row->sales_tax)/100;
                                        ?>
                                        <td colspan="5"><?php echo e('Sales Tax :'. $row->sales_tax.' %'); ?></td>
                                        <td style="text-align: center"><?php echo e($cur); ?> <?php echo e(number_format($row->sales_tax_amount,2)); ?></td>
                                        <!-- <td class="text-right showw" style="display: none" ><?php echo e(number_format($pkr_sale_tax_amount,2)); ?></td> -->
                                        <td class="printHide"></td>
                                    </tr>

                                    <tr>

                                        <td class="text-center" colspan="5">Grand Total</td>
                                        <td style="text-align: center" ><?php echo e($cur); ?> <?php echo e(number_format($total+$row->sales_tax_amount,2)); ?></td>
                                        <!-- <td style="background-color: darkgray;display: none"  class="text-right showw" colspan="6"><?php echo e(number_format($total_exchange+$pkr_sale_tax_amount,2)); ?>


                                        </td> -->
                                        <td class="printHide"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="text-transform: capitalize;">Amount In Words : <?php echo e($row->amount_in_words); ?> </td>
                                        <td class="printHide"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div style="line-height:8px;">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span>
                                <b>Note:</b> <br>
                                <textarea style="font-size: 11px; resize: none; font-weight:600; border:none !important;line-height: 19px!important;" cols="100" rows="10"><?php echo e($row->description); ?></textarea>
                            </span>
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
                                    <b>  <p><?php echo $row->approve_username?></p></b>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
                <!--
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                    <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Request Voucher Detail (Office Use)'))!!} ">
                </div>
                <!-->
            </div>
        </div>
    </div>
    <?php
        }
    ?>
</div>

    <script>

        function view_history(id) {

            var v = $('#sub_' + id).val();


            if ($('#view_history' + id).is(":checked")) {
                if (v != null) {
                    showDetailModelTwoParamerter('pdc/viewHistoryOfItem_directPo?id=' + v);
                }
                else {
                    alert('Select Item');
                }

            }
        }

    function change()
    {
        if(!$('.showw').is(':visible'))
        {
            $(".showw").fadeIn();

        }
        else
        {
            $(".showw").fadeOut();
        }
    }

    function EmailSent()
    {
        if (confirm('Are you sure you want to Sent Email to this request'))
        {
            pageType="pageType1";
            EmailPrintSetting = "2";
            id = "<?php echo $id; ?>";
            m = "<?php echo $m; ?>";
            email = $("#email").val();
            $.ajax({
                url: '<?php echo url('/') ?>/stad/Email_Sent',
                type: 'get',
                data: {email:email, id:id, m:m,pageType:pageType, EmailPrintSetting:EmailPrintSetting},
                success: function (response)
                {
                    alert(response);
                }
            });
        } else
        {
            alert("Email Not Sended");
        }
    }




</script>

