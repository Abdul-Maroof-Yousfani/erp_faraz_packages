<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

$m = $_GET['m'];
$FromDate = $_GET['FromDate'];
$ToDate = $_GET['ToDate'];
$Data = '';


$Data = DB::Connection('mysql2')->select('select a.id,a.sales_tax,a.gi_no,a.gi_date,a.sc_no,SUM(b.amount) total_amount,a.buyers_id from sales_tax_invoice a
                                          INNER JOIN sales_tax_invoice_data b ON b.master_id = a.id
                                          where a.status = 1
                                          and a.gi_date BETWEEN "'.$FromDate.'" and "'.$ToDate.'"
                                          GROUP BY b.master_id
                                          ');

$Counter = 1;
$TotSalesTax = 0;
$TotalAmount =0;
$TotFreightAmount = 0;
$OverAllTotal = 0;
?>
<div class="row">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
        </div>

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                <thead>
                    <th class="text-center">S.No</th>
                    <th class="text-center">SI No</th>
                    <th class="text-center">SI Date</th>
                    <th class="text-center">ST No</th>
                    <th class="text-center">Customer Name</th>
                    <th class="text-center">Gross Amount</th>
                    <th class="text-center">Sales Tax Amount</th>

                    <th class="text-center">Freight Amount</th>
                    <th class="text-center">Total</th>
                </thead>
                <tbody>
<?php
foreach($Data as $Fil):
        $FreightAmount = SalesHelper::get_freight($Fil->id);
?>
<tr class="text-center"  >
    <td><?php echo $Counter;?></td>
    <td style="cursor: pointer" onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $Fil->id ?>','View Sales Tax Invoice')"><?php echo strtoupper($Fil->gi_no)?></td>
    <td><?php echo CommonHelper::changeDateFormat($Fil->gi_date);?></td>
    <td><?php echo strtoupper($Fil->sc_no)?></td>
    <td><?php echo SalesHelper::get_customer_name($Fil->buyers_id)?></td>
    <td><?php echo number_format($Fil->total_amount,2); $TotalAmount+=$Fil->total_amount;?></td>
    <td><?php echo number_format($Fil->sales_tax,2); $TotSalesTax+=$Fil->sales_tax;?></td>
    <td><?php echo number_format($FreightAmount,2); $TotFreightAmount+=$FreightAmount?></td>
    <td style="font-weight: bold"><?php echo number_format($Fil->total_amount+$Fil->sales_tax+$FreightAmount,2); $OverAllTotal+=$Fil->total_amount+$Fil->sales_tax+$FreightAmount;?></td>

</tr>
<?php
$Counter++;
endforeach;?>
<tr class="text-center">
    <td colspan="5"><strong style="font-size: 20px;">TOTAL</strong></td>
    <td><strong style="font-size: 20px;"><?php echo number_format($TotalAmount,2);?></strong></td>
    <td><strong style="font-size: 20px;"><?php echo number_format($TotSalesTax,2);?></strong></td>
    <td><strong style="font-size: 20px;"><?php echo number_format($TotFreightAmount,2);?></strong></td>
    <td><strong style="font-size: 20px;"><?php echo number_format($OverAllTotal,2);?></strong></td>
</tr>
                </tbody>
                </table>

