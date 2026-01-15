<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\SaleHelper;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Config;
use Session;

$counter = 1;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];

$selectSubItem = $_GET['selectSubItem'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectCreditCustomer = $_GET['selectCustomer'];

if(!empty($selectSubItem)){
    $selectSubItemTitle = $selectSubItem;
}else{
    $selectSubItemTitle = 'All Sub Item';
}

if(!empty($selectCreditCustomer)){
    $selectCreditCustomerTitle = $selectCreditCustomer;
}else{
    $selectCreditCustomerTitle = 'All Credit Customers';
}

if($selectVoucherStatus == '0'){
    $voucherStatusTitle = 'All Vouchers';
}else if($selectVoucherStatus == '1'){
    $voucherStatusTitle = 'Pending Vouchers';
}else if($selectVoucherStatus == '2'){
    $voucherStatusTitle = 'Approve Vouchers';
}else if($selectVoucherStatus == '3'){
    $voucherStatusTitle = 'Deleted Vouchers';
}
$data ='';
$data .='<tr><td colspan="12" class="text-center"><strong>Filter By : (Sub Items => '.$selectSubItemTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(Credit Customers => '.$selectCreditCustomerTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => '.$voucherStatusTitle.')</strong></td></tr>';
$totalCreditSaleAmount = 0;
$totalCalculatedCreditSaleAmount = 0;
$totalReceiptAmount = 0;
$totalBalanceAmount = 0;
foreach ($creditSaleInvoiceDetail as $row){
    $paramOne = "sdc/viewCreditSaleVoucherDetail";
    $paramTwo = $row->inv_no;
    $paramThree = "View Credit Sale Voucher Detail";
    $totalCreditSaleAmount += SaleHelper::getInvoiceTotalAmountByInvoiceNoWithDiscountAmount($m,$row->inv_no);
    $totalAmount = SaleHelper::getInvoiceTotalAmountByInvoiceNoWithDiscountAmount($m,$row->inv_no);
    $totalCalculatedCreditSaleAmount += SaleHelper::getInvoiceTotalAmountByInvoiceNo($m,$row->inv_no);
    $totalReceiptAmount += SaleHelper::getReceiptTotalAmountByInvoiceNo($m,$row->inv_no);

    $receiptTotalAmount = SaleHelper::getReceiptTotalAmountByInvoiceNo($m,$row->inv_no);
    $totalInvoiceAmount = SaleHelper::getInvoiceTotalAmountByInvoiceNo($m,$row->inv_no);
    $balanceAmount = $totalInvoiceAmount - $receiptTotalAmount;
    $totalBalanceAmount += $balanceAmount;

    $data.='<tr><td class="text-center">'.$counter++.'</td><td class="text-center">'.$row->inv_no.'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->inv_date).'</td><td class="text-center">'.$row->dc_no.'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($m,'customers','name',$row->consignee).'</td><td class="text-right">'.number_format($totalAmount).'</td><td class="text-center">'.$row->inv_against_discount.'%</td><td class="text-right">'.number_format(SaleHelper::getInvoiceTotalAmountByInvoiceNo($m,$row->inv_no)).'</td><td class="text-right">'.number_format($receiptTotalAmount).'</td><td class="text-right">'.number_format($balanceAmount).'</td><td class="text-center">'.SaleHelper::checkVoucherStatus($row->inv_status,$row->status).'</td><td class="text-center hidden-print">';
    $data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';
    $data.='</td></tr>';
}
$data .= '<tr><th colspan="2" class="text-center">&nbsp;&nbsp;&nbsp;XX <---> XX&nbsp;&nbsp;&nbsp;</th><th colspan="3" class="text-center">Total Credit Sale Amount</th><th class="text-right">'.number_format($totalCreditSaleAmount).'</th><th class="text-center">&nbsp;&nbsp;&nbsp;XX <---> XX&nbsp;&nbsp;&nbsp;</th><th class="text-right">'.number_format($totalCalculatedCreditSaleAmount).'</th><th class="text-right">'.number_format($totalReceiptAmount).'</th><th class="text-right">'.number_format($totalBalanceAmount).'</th><th colspan="2" class="text-center">&nbsp;&nbsp;&nbsp;XX <---> XX&nbsp;&nbsp;&nbsp;</th></tr>';
echo json_encode(array('data' => $data));
?>