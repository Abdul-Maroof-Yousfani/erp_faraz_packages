<?php
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$invoiceDetail = DB::table('invoice')->where('inv_no','=',$id)->get();
CommonHelper::reconnectMasterDatabase();
foreach ($invoiceDetail as $row) {
    $invoiceAgainstDiscountPer = $row->inv_against_discount;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo SaleHelper::creditSaleVoucherApprove($m,$id,$row->inv_status);?>
        <?php CommonHelper::displayPrintButtonInView('printCreditSaleVoucherDetail','','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well" id="printCreditSaleVoucherDetail">
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
                        </div>
                        <br />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php SaleHelper::checkVoucherStatus($row->inv_status,$row->status);?>
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
                    <div style="width:30%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:50%;">Invoice No.</td>
                                <td style="width:50%;"><?php echo $row->inv_no;?></td>
                            </tr>
                            <tr>
                                <td>Invoice Date</td>
                                <td><?php echo CommonHelper::changeDateFormat($row->inv_date);?></td>
                            </tr>
                            <tr>
                                <td>Customer Name</td>
                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'customers','name',$row->consignee);?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="width:30%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:60%;">DC No.</td>
                                <td style="width:40%;"><?php echo $row->dc_no;?></td>
                            </tr>
                            <tr>
                                <td style="width:60%;">Vehicle No.</td>
                                <td style="width:40%;"><?php echo $row->vehicle_no;?></td>
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
                                <th class="text-center">Description</th>
                                <th class="text-center" style="width:150px;">Price</th>
                                <th class="text-center" style="width:150px;">Qty</th>
                                <th class="text-center" style="width:150px;">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection($m);
                            $invoiceDataDetail = DB::table('inv_data')->where('inv_no','=',$id)->get();
                            CommonHelper::reconnectMasterDatabase();
                            $counter = 1;
                            $totalAmount = 0;
                            foreach ($invoiceDataDetail as $row1){
                                $totalAmount += $row1->amount;
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'category','main_ic',$row1->category_id);?></td>
                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->sub_item_id);?></td>
                                <td><?php echo $row1->description;?></td>
                                <td class="text-center"><?php echo $row1->price;?></td>
                                <td class="text-center"><?php echo $row1->qty;?></td>
                                <td class="text-center"><?php echo $row1->amount;?></td>
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
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <?php echo SaleHelper::saleReceiptVoucherSummaryDetail($m,$id);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th colspan="5"><?php echo $row->main_description;?></th>
                                            </tr>
                                            <tr>
                                                <th style="width:15%;">Printed On</th>
                                                <th style="width:15%;"><?php echo Auth::user()->name; ?></th>
                                                <th style="width:15%;">Created By</th>
                                                <th style="width:15%;"><?php echo $row->username;?></th>
                                                <th style="width:20%;">Approved By</th>
                                                <th style="width:20%;"><?php //echo $row->approve_username;?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="table-responsive">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th>Total Amount</th>
                                        <td class="text-right"><?php echo number_format($totalAmount,2);?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Discount</th>
                                        <td class="text-right">
                                            <?php
                                            echo '('.number_format($invoiceAgainstDiscountPer,1).')% - ';
                                            $calculatedTotalDiscount = $totalAmount*$invoiceAgainstDiscountPer/100;
                                            ?>
                                            <?php echo number_format($calculatedTotalDiscount,2);?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Net Total Amount</th>
                                        <td class="text-right">
                                            <?php echo number_format($totalAmount - $calculatedTotalDiscount,2);?>
                                            <input type="hidden" readonly name="netTotalAmount" id="netTotalAmount" value="<?php echo $totalAmount - $calculatedTotalDiscount?>">
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>Total Receipt Amount</th>
                                        <td class="text-right" id="cellTotalReceiptAmount">
                                            <?php number_format($totalAmount - $calculatedTotalDiscount,2);?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Balance</th>
                                        <td class="text-right" id="cellTotalBalance">
                                            <?php number_format($totalAmount - $calculatedTotalDiscount,2);?>
                                        </td>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Credit Sale Voucher Detail'))!!} ">
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>
<script>
    function makeSummarySection() {
        var netTotalAmount = $('#netTotalAmount').val();
        var totalReceiptAmount = $('#totalReceiptAmount').val();

        var totalBalanceAmount = parseInt(netTotalAmount) - parseInt(totalReceiptAmount);
        $('#cellTotalBalance').html(addSeparatorsNF(totalBalanceAmount,'.','.',','));
        $('#cellTotalReceiptAmount').html(addSeparatorsNF(totalReceiptAmount,'.','.',','));

    }
    makeSummarySection();
</script>
