<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\SaleHelper;
use App\Helpers\CommonHelper;
use App\Models\Invoice;
use App\Models\InvoiceData;
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
$selectCashCustomer = $_GET['selectCustomer'];

if(!empty($selectSubItem)){
    $selectSubItemTitle = $selectSubItem;
}else{
    $selectSubItemTitle = 'All Sub Item';
}

if(!empty($selectCashCustomer)){
    $selectCashCustomerTitle = $selectCashCustomer;
}else{
    $selectCashCustomerTitle = 'All Cash Customers';
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
$data .='<tr><td colspan="10" class="text-center"><strong>Filter By : (Sub Items => '.$selectSubItemTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(Cash Customers => '.$selectCashCustomerTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => '.$voucherStatusTitle.')</strong></td></tr>';
$totalCashSaleAmount = 0;
$totalCalculatedCashSaleAmount = 0;
foreach ($cashSaleInvoiceDetail as $row){
    $paramOne = "sdc/viewCashSaleVoucherDetail";
    $paramTwo = $row->inv_no;
    $paramThree = "View Cash Sale Voucher Detail";
    $totalCashSaleAmount += SaleHelper::getInvoiceTotalAmountByInvoiceNoWithDiscountAmount($m,$row->inv_no);
    $totalAmount = SaleHelper::getInvoiceTotalAmountByInvoiceNoWithDiscountAmount($m,$row->inv_no);
    $totalCalculatedCashSaleAmount += SaleHelper::getInvoiceTotalAmountByInvoiceNo($m,$row->inv_no);
    $jvDetail = SaleHelper::getJournalVoucherNoByInvoiceNo($m,$row->inv_no);
    $rvDetail = SaleHelper::getReceiptVoucherNoByInvoiceNo($m,$row->inv_no);
    //print_r($rvDetail);
    //die;
    $data.='<tr><td class="text-center">'.$counter++.'</td><td class="text-center">'.$row->inv_no.'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->inv_date).'</td><td class="text-center">'.$row->dc_no.'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($m,'customers','name',$row->consignee).'</td><td class="text-right">'.number_format($totalAmount).'</td><td class="text-center">'.$row->inv_against_discount.'%</td><td class="text-right">'.number_format(SaleHelper::getInvoiceTotalAmountByInvoiceNo($m,$row->inv_no)).'</td><td class="text-center">'.SaleHelper::checkVoucherStatus($row->inv_status,$row->status).'</td><td class="text-center hidden-print">';
    $data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';
    if($row->status == 2){
        $data.=' <button class="repost-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCashSaleVoucher(\''.$m.'\',\''.$paramTwo.'\',\''.$jvDetail->jv_no.'\',\''.$rvDetail->rv_no.'\')"><span class="glyphicon glyphicon-refresh"></span> Repost Voucher</button>';
    }else{
    $data.=' <button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCashSaleVoucher(\''.$m.'\',\''.$paramTwo.'\',\''.$jvDetail->jv_no.'\',\''.$rvDetail->rv_no.'\')"><span class="glyphicon glyphicon-trash"></span> Delete Voucher</button>';
    }
    $data.='</td></tr>';
}
$data .= '<tr><th colspan="2" class="text-center">&nbsp;&nbsp;&nbsp;XX <------> XX&nbsp;&nbsp;&nbsp;</th><th colspan="3" class="text-center">Total Cash Sale Amount</th><th class="text-right">'.number_format($totalCashSaleAmount).'</th><th class="text-center">&nbsp;&nbsp;&nbsp;XX <------> XX&nbsp;&nbsp;&nbsp;</th><th class="text-right">'.number_format($totalCalculatedCashSaleAmount).'</th><th colspan="2" class="text-center">&nbsp;&nbsp;&nbsp;XX <------> XX&nbsp;&nbsp;&nbsp;</th></tr>';
echo json_encode(array('data' => $data));
?>