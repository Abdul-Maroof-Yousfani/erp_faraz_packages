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
foreach ($storeChallanDetail as $row){
    $paramOne = "stdc/viewStoreChallanVoucherDetail";
    $paramTwo = $row['store_challan_no'];
    $paramThree = "View Store Challan Detail";
    $data.='<tr><td class="text-center">'.$counter++.'</td><td class="text-center">'.$row->store_challan_no.'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->store_challan_date).'</td><td class="text-center">'.$row->slip_no.'</td><td class="text-center">'.CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id).'</td><td class="text-center">'.StoreHelper::checkVoucherStatus($row->store_challan_status,$row->status).'</td>
    
    
    <td class="text-center hidden-print">';
    $data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';
    $data.='&nbsp;'.StoreHelper::displayStoreChallanButton($m,$row->store_challan_status,$row->status,'1',$row->store_challan_no).'
    </td></tr>';
}
echo json_encode(array('data' => $data));
?>