<?php
use App\Helpers\FinanceHelper;
?>

<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><th colspan="4" class="text-center"><h2>Sales Tax Invoice</h2></th></thead>
            <thead>

            <th class="text-center">S.No</th>
            <th class="text-center">Si No</th>
            <th class="text-center">Si Date</th>
            <th class="text-center">Amount</th>
            </thead>
            <tbody>
            <?php
                    $TotalSalesTaxInvoiceAmount = 0;
            $Counter = 1;
            foreach($SalesTaxInvoice as $SFil):?>
            <tr class="text-center">
                <td><?php echo $Counter++;?></td>
                <td>
                    <a onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $SFil->id ?>','View Sales Tax Invoice')" style="cursor: pointer;">
                    <?php echo strtoupper($SFil->gi_no);?>
                    </a>
                </td>
                <td><?php echo FinanceHelper::changeDateFormat($SFil->gi_date);?></td>
                <td><?php echo number_format($SFil->amount,2); $TotalSalesTaxInvoiceAmount+=$SFil->amount?></td>
            </tr>
            <?php endforeach;?>
            <tr class="text-center">
                <td colspan="3"><strong>TOTAL</strong></td>
                <td><?php echo number_format($TotalSalesTaxInvoiceAmount,2);?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><th colspan="4" class="text-center"><h2>Sales Return</h2></th></thead>
            <thead>

            <th class="text-center">S.No</th>
            <th class="text-center">CR No</th>
            <th class="text-center">CR Date</th>
            <th class="text-center">Amount</th>
            </thead>
            <tbody>
            <?php
            $TotalSalesReturnAmount = 0;
            $Counter = 1;
            foreach($SalesReturn as $SRFil):?>
            <tr class="text-center">
                <td><?php echo $Counter++;?></td>
                <td>
                    <a onclick="showDetailModelOneParamerter('sales/viewCreditNoteDetail','<?php echo $SRFil->id ?>','View Sales Return')"
                            type="button" class="btn btn-success btn-xs" style="cursor: pointer;">
                    <?php echo strtoupper($SRFil->cr_no);?>
                    </a>
                </td>
                <td><?php echo FinanceHelper::changeDateFormat($SRFil->cr_date);?></td>
                <td><?php echo number_format($SRFil->amount,2); $TotalSalesReturnAmount+=$SRFil->amount;?></td>
            </tr>
            <?php endforeach;?>
            <tr class="text-center">
                <td colspan="3"><strong>TOTAL</strong></td>
                <td><?php echo number_format($TotalSalesReturnAmount,2);?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><th colspan="4" class="text-center"><h2>Sales Receipt Voucher</h2></th></thead>
            <thead>

            <th class="text-center">S.No</th>
            <th class="text-center">RV No</th>
            <th class="text-center">RV Date</th>
            <th class="text-center">Amount</th>
            </thead>
            <tbody>
            <?php
            $TotalReceiptAmount = 0;
            $Counter = 1;
            foreach($ReceiptVoucher as $RvFil):?>
            <tr class="text-center">
                <td><?php echo $Counter++;?></td>
                <td>
                    <a onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucher','<?php echo $RvFil->id;?>','View Bank Reciept Voucher Detail','<?php echo Session::get('run_company');?>','')" style="cursor: pointer;">
                        <?php echo strtoupper($RvFil->rv_no);?>
                    </a>
                </td>
                <td><?php echo FinanceHelper::changeDateFormat($RvFil->rv_date);?></td>
                <td><?php echo number_format($RvFil->amount,2); $TotalReceiptAmount+=$RvFil->amount;?></td>
            </tr>
            <?php endforeach;?>
            <tr class="text-center">
                <td colspan="3"><strong>TOTAL</strong></td>
                <td><?php echo number_format($TotalReceiptAmount,2);?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


