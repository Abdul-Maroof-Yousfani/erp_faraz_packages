<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Config;
use Session;

$counter = 1;
$m = $_GET['m'];
$data ='';
$selectBranch = $_GET['selectBranch'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];

if(!empty($selectBranch)){
    $selectBranchTitle = $selectBranch;
}else{
    $selectBranchTitle = 'All Branch';
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
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$data .='<tr><td colspan="10" class="text-center"><strong>Filter By : (Branch => '.$selectBranchTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => '.$voucherStatusTitle.')</strong></td></tr>';
?>

<?php
echo json_encode(array('data' => $data));
?>