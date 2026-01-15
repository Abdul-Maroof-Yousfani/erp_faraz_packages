<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Config;
use Session;

$counter = 1;
$m = $_GET['m'];
$selectSubDepartment = $_GET['selectSubDepartment'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
if(!empty($selectSubDepartment)){
    $selectSubDepartmentTitle = $selectSubDepartment;
}else{
    $selectSubDepartmentTitle = 'All Department';
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
$data ='';
$data .='<tr><td colspan="10" class="text-center"><strong>Filter By : (Sub Department => '.$selectSubDepartmentTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => '.$voucherStatusTitle.')</strong></td></tr>';
foreach ($demandDetail as $row){
    $paramOne = "pdc/viewDemandVoucherDetail";
    $paramTwo = $row['demand_no'];
    $paramThree = "View Demand Detail";
    $data.='<tr><td class="text-center">'.$counter++.'</td><td class="text-center">'.$row->demand_no.'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->demand_date).'</td><td class="text-center">'.$row->slip_no.'</td><td class="text-center">'.CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id).'</td><td class="text-center">'.StoreHelper::checkVoucherStatus($row->demand_status,$row->status).'</td><td class="text-center hidden-print">';
    $data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';
    $data.='</td></tr>';
}
?>

<?php
echo json_encode(array('data' => $data));
?>