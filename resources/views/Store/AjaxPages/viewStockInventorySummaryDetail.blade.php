<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
$m = $_GET['m'];
$currentDate = date('Y-m-d');
$categoryIcId = $_GET['pOne'];
$subIcId = $_GET['pTwo'];
$filterDate = $_GET['pFour'];
$subIcName = $_GET['pThree'];
?>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <label>To Date</label>
        <input type="Date" name="paramTwo" id="paramTwo" max="<?php echo $currentDate;?>"
        onchange="showDetailModelSixParamerter('stdc/viewStockInventorySummaryDetail','View Item Wise Summary Detail','<?php echo $categoryIcId?>','<?php echo $subIcId;?>','<?php echo $subIcName;?>',this.value)" value="<?php echo $filterDate;?>" class="form-control" />
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
        <?php echo CommonHelper::displayPrintButtonInBlade('printViewStockInventorySummaryDetail','margin-top: 32px','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div id="viewFilterDateWiseStockInventoryDetail">
<div class="well" id="printViewStockInventorySummaryDetail">
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
                     style="font-size: 15px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">

                    <?php echo 'Filter By : (Item Name => '.$subIcName.')&nbsp;&nbsp;,&nbsp;&nbsp;(As On Date => '.CommonHelper::changeDateFormat($filterDate).')'; ?>
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
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong>Purchase Item Detail</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Supplier Name</th>
                                        <th class="text-center">P.R. No</th>
                                        <th class="text-center">P.R. Date</th>
                                        <th class="text-center">G.R.N. No</th>
                                        <th class="text-center">G.R.N. Date</th>
                                        <th class="text-center">Invoice No</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Current Balance</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td colspan="6" class="text-center">Opening Qty</td>
                                            <td class="text-center"><?php echo $itemOpeningQty->qty;?></td>
                                            <td class="text-right"><?php echo $itemOpeningQty->value;?></td>
                                            <td class="text-center"><?php echo $itemOpeningQty->qty;?></td>
                                        </tr>
                                        <?php
                                            $pcounter = 2;
                                            $currentPurchaseBalance = $itemOpeningQty->qty;
                                            foreach ($itemPurchaseData as $row) {
                                                $currentPurchaseBalance += $row->qty;
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $pcounter++;?></td>
                                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supp_id);?></td>
                                                <td class="text-center"><?php echo $row->pr_no?></td>
                                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->pr_date);?></td>
                                                <td class="text-center"><?php echo $row->grn_no?></td>
                                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->grn_date);?></td>
                                                <td class="text-center"><?php echo CommonHelper::getInvoiceNoByGRNNo($m,$row->grn_no)?></td>
                                                <td class="text-center"><?php echo $row->qty?></td>
                                                <td class="text-right"><?php echo $row->value?></td>
                                                <td class="text-center"><?php echo $currentPurchaseBalance;?></td>
                                            </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong>Store Challan Issue Item Detail</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Demand No</th>
                                    <th class="text-center">Demand Date</th>
                                    <th class="text-center">S.C. No</th>
                                    <th class="text-center">S.C. Date</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Current Balance</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sccounter = 1;
                                    $currentStoreChallanQty = 0;
                                    foreach ($itemStoreChallanData as $row1) {
                                        $currentStoreChallanQty += $row1->qty;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $sccounter++;?></td>
                                        <td class="text-center"><?php echo $row1->demand_no?></td>
                                        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row1->demand_date);?></td>
                                        <td class="text-center"><?php echo $row1->sc_no?></td>
                                        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row1->sc_date);?></td>
                                        <td class="text-center"><?php echo $row1->qty?></td>
                                        <td class="text-center"><?php echo $currentStoreChallanQty;?></td>
                                    </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong>Store Challan Return Item Detail</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">S.C. No</th>
                                    <th class="text-center">S.C. Date</th>
                                    <th class="text-center">S.C.R. No</th>
                                    <th class="text-center">S.C.R. Date</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Current Balance</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $scrcounter = 1;
                                    $currentStoreChallanReturnQty = 0;
                                    foreach ($itemStoreChallanReturnData as $row2) {
                                    $currentStoreChallanReturnQty += $row2->qty;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $scrcounter++;?></td>
                                        <td class="text-center"><?php echo $row2->sc_no?></td>
                                        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row2->sc_date);?></td>
                                        <td class="text-center"><?php echo $row2->scr_no?></td>
                                        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row2->scr_date);?></td>
                                        <td class="text-center"><?php echo $row2->qty?></td>
                                        <td class="text-center"><?php echo $currentStoreChallanReturnQty;?></td>
                                    </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong>Cash Sale Item Detail</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center">Invoice No</th>
                                    <th class="text-center">Invoice Date</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Invoice Discount</th>
                                    <th class="text-center">Net Amount</th>
                                    <th class="text-center">Current Balance</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $cashSalecounter = 1;
                                    $currentCashSaleBalance = 0;
                                    foreach ($itemCashSaleData as $row) {
                                    $currentCashSaleBalance += $row->qty;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $cashSalecounter++;?></td>
                                        <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'customers','name',$row->customer_id);?></td>
                                        <td class="text-center"><?php echo $row->inv_no?></td>
                                        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->inv_date);?></td>
                                        <td class="text-center"><?php echo $row->qty?></td>
                                        <td class="text-right"><?php echo number_format($row->value)?></td>
                                        <td class="text-center"><?php echo $row->inv_against_discount?></td>
                                        <td class="text-right"><?php echo number_format($row->value - $row->value*$row->inv_against_discount/100)?></td>
                                        <td class="text-center"><?php echo $currentCashSaleBalance;?></td>
                                    </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong>Credit Sale Item Detail</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center">Invoice No</th>
                                    <th class="text-center">Invoice Date</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Invoice Discount</th>
                                    <th class="text-center">Net Amount</th>
                                    <th class="text-center">Current Balance</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $creditSalecounter = 1;
                                    $currentCreditSaleBalance = 0;
                                    foreach ($itemCreditSaleData as $row) {
                                    $currentCreditSaleBalance += $row->qty;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $creditSalecounter++;?></td>
                                        <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'customers','name',$row->customer_id);?></td>
                                        <td class="text-center"><?php echo $row->inv_no?></td>
                                        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->inv_date);?></td>
                                        <td class="text-center"><?php echo $row->qty?></td>
                                        <td class="text-right"><?php echo number_format($row->value)?></td>
                                        <td class="text-center"><?php echo $row->inv_against_discount?></td>
                                        <td class="text-right"><?php echo number_format($row->value - $row->value*$row->inv_against_discount/100)?></td>
                                        <td class="text-center"><?php echo $currentCreditSaleBalance;?></td>
                                    </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <tbody>
                                    <tr>
                                        <th>Opening + Purchase</th>
                                        <td class="text-right"><?php echo $currentPurchaseBalance;?></td>
                                    </tr>
                                    <tr>
                                        <th>Store Challan Issue</th>
                                        <td class="text-right"><?php echo $currentStoreChallanQty;?></td>
                                    </tr>
                                    <tr>
                                        <th>Store Challan Return</th>
                                        <td class="text-right"><?php echo $currentStoreChallanReturnQty;?></td>
                                    </tr>
                                    <tr>
                                        <th>Cash Sale</th>
                                        <td class="text-right"><?php echo $currentCashSaleBalance;?></td>
                                    </tr>
                                    <tr>
                                        <th>Credit Sale</th>
                                        <td class="text-right"><?php echo $currentCreditSaleBalance;?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Balance</th>
                                        <td class="text-right"><?php echo $currentPurchaseBalance + $currentStoreChallanReturnQty - $currentStoreChallanQty - $currentCashSaleBalance - $currentCreditSaleBalance;?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right hidden qrCodeDiv">
                            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Item Wise Summary Detail'))!!} ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function viewFilterDateWiseStockInventoryDetail() {
        alert();
    }
</script>
