<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use Auth;
use DB;
use Config;
use Session;


//$view=ReuseableCode::check_rights(3);
$counter = 1;
$m = $_GET['m'];
$selectSubDepartment = $_GET['selectSubDepartment'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectSupplier = $_GET['selectSupplier'];

 $view=ReuseableCode::check_rights(13);
 $edit=ReuseableCode::check_rights(14);
 $delete=ReuseableCode::check_rights(15);
$reject=ReuseableCode::check_rights(210);
$total=0;

if(!empty($selectSubDepartment)){
    $selectSubDepartmentTitle = $selectSubDepartment;
}else{
    $selectSubDepartmentTitle = 'All Department';
}

if(!empty($selectSupplier)){
    $selectSupplierTitle = $selectSupplier;
}else{
    $selectSupplierTitle = 'All Suppliers';
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
else if($selectVoucherStatus == '4'){
    $voucherStatusTitle = 'Approve Vouchers';
}
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$data ='';
$cur = '';
//$data .='<tr><td colspan="10" class="text-center"><strong>Filter By : (Sub Department => '.$selectSubDepartmentTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(Suppliers => '.$selectSupplierTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => '.$voucherStatusTitle.')</strong></td></tr>';
foreach ($purchaseRequestDetail as $row){

    if($row->currency_id == 3):
        $cur = 'PKR';
    elseif($row->currency_id == 4):
        $cur = 'USD';
    endif;

    $edit_url= url('/store/editPurchaseRequestVoucherForm/'.$row->id.'?m='.$m);
    $print_url = url('/pdc/purchase_order/'.$row->id.'?m='.$m);
    $edit_url_direct= url('/store/editDirectPurchaseRequestVoucherForm/'.$row->id.'?m='.$m);
    //$net_amount= ReuseableCode::get_po_total_amount($row->id);
    $total += $row->total_amount;
    $paramOne = "stdc/viewPurchaseRequestVoucherDetail";
    $paramTwo = $row['id'];
    $paramThree = "View Purchase Order Detail";
    $Tstatus = '';
    if($row->purchase_request_status == 3)
        {
            if(CommonHelper::CheckGrnCount($row->purchase_request_no) == 0)
            {
                $Tstatus = 2;
            }
            else
            {
                $Tstatus = $row->purchase_request_status;
            }
        }
    else
        {
            $Tstatus = $row->purchase_request_status;
        }

    $data.='<tr id="tr'.$row->id.'" class="'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->purchase_request_no).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->purchase_request_date).'</td><td class="text-center hide">'.$row->trn.'</td><td class="text-center hide">'.$row->remarks.'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-center">'.StoreHelper::checkVoucherStatus($Tstatus,$row->status).'</td><td class="text-center">'.strtoupper($row->username).'</td>'.'</td><td class="text-center">'.$cur.' '.number_format($row->total_amount,2).'</td><td class="text-center hidden-print">';

    $data.='<div class="dropdown">
    <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
    <ul class="dropdown-menu">
    <li>';


    if ($view==true):
        $data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>';
        $data .= '<a onClick="window.location.href=\'' . $print_url . '\'" class="dropdown-item_sale_order_list dropdown-item" href="'.$print_url.'">Print</a>';
    endif;
    if ($row->status == 1 && $row->purchase_request_status == 1):
    endif;


    if ($edit==true)
    if ($row->purchase_request_status==1):
        if($row->type == 2):
            $data .= '<a onClick="window.location.href=\'' . $edit_url_direct . '\'" type="button" class="dropdown-item_sale_order_list dropdown-item ">Edit</a>';
        else:
            $data .= '<a onClick="window.location.href=\'' . $edit_url . '\'" type="button" class="dropdown-item_sale_order_list dropdown-item ">Edit</a>';
        endif;
        if ($delete)
            $data.= '<a onclick="delete_records('.$row->id.','.'2)" id="'.$row->id.'" type="button" class="dropdown-item_sale_order_list dropdown-item ">Delete</a>';
    endif;


    if($row->purchase_request_status == 2):
        if($reject == true):
            // $data.='<a type="button" class="dropdown-item_sale_order_list dropdown-item " id="BtnReject'.$row->id.'" onclick="RejectPo('.$row->id.')">Reject</a>';
        endif;
    endif;

    $data.=' </li>
    </ul>
</div>';

    $data.='
    </td>
</tr>';
}
$data.='<tr style="font-size: large;font-weight: bold !important"><td colspan="5"></td><td>Total</td><td class="text-center">PKR '.number_format($total,2).'</td><td></td></tr>'
?>

<?php
echo json_encode(array('data' => $data));
?>