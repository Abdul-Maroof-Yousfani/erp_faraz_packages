<?php



use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$view=ReuseableCode::check_rights(5);
$edit=ReuseableCode::check_rights(6);
$delete=ReuseableCode::check_rights(7);
$counter = 1;
$m = $_GET['m'];
$data ='';
//$selectSubDepartment = $_GET['selectSubDepartment'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
if(!empty($selectSubDepartment)){
  //  $selectSubDepartmentTitle = $selectSubDepartment;
}else{
    $selectSubDepartmentTitle = 'All Department';
}
if($selectVoucherStatus == '0')
{
    $voucherStatusTitle = 'All Vouchers';
}
else if($selectVoucherStatus == '1')
{
    $voucherStatusTitle = 'Pending Vouchers';
}
else if($selectVoucherStatus == '2')
{
    $voucherStatusTitle = 'Approve Vouchers';
}
else if($selectVoucherStatus == '3')
{
    $voucherStatusTitle = 'Deleted Vouchers';
}
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

//$data .='<tr><td colspan="10" class="text-center"><strong>Filter By : (Sub Department => '.$selectSubDepartmentTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => '.$voucherStatusTitle.')</strong></td></tr>';
foreach ($demandDetail as $row){
    $demand_no = $row->demand_no;
    $PO_data = DB::connection('mysql2')->table('purchase_request_data')->select('purchase_request_no', 'demand_no')->where('demand_no','=',$demand_no)->where('status','=',1);
    $PO_data_count= $PO_data->count();
    if($PO_data_count > 0){
        $PO_datas = $PO_data->first();
        $purchase_request_no = $PO_datas->purchase_request_no;
    }else{
        $purchase_request_no = "";
    }

    $edit_url=URL::asset('purchase/editDemandVoucherForm/'.$row->id.'?m='.$m);
    $paramOne = "pdc/viewDemandVoucherDetail?m=".$m;
    $paramTwo = $row['demand_no'];
    $paramThree = "View Purchase Request List";
    $paramFour = "purchase/editDemandVoucherForm";
    $data.='<tr class="'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->demand_no).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->demand_date).'</td><td class="text-center">'.$row->slip_no.'</td><td class="text-center">'.CommonHelper::getMasterTableValueById($m,'department','department_name',$row->sub_department_id).'</td><td class="text-center">'.PurchaseHelper::checkVoucherStatus($row->demand_status,$row->status)
            .'</td>
            
            
            <td class="text-center hidden-print">';

        $data.='<div class="dropdown">
        <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
        <ul class="dropdown-menu">
        <li>';

   
    if($view == true):
        $data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="dropdown-item_sale_order_list dropdown-item "><i class="fa-regular fa-eye"></i> View</a>';
    endif;
   
    
        if($PO_data_count <= 0):
            if($row->demand_status==1):
                if($edit == true):
                    $data.='<a href='.$edit_url.' type="button" class="dropdown-item_sale_order_list dropdown-item "><i class="fa-solid fa-pencil"></i> Edit</a>';
                endif;
            endif;
            if($delete == true):
                $data.='<a id="'.$row->id.'" type="button" onclick="delete_records('.$row->id.','.'1)" class="dropdown-item_sale_order_list dropdown-item"><i class="fa-solid fa-trash"></i> Delete</a>';
            endif;
        endif;
    

    $data.=' </li>
    </ul>
</div>';


    $data.='</td>
</tr>';
}
?>

<?php
echo json_encode(array('data' => $data));
?>