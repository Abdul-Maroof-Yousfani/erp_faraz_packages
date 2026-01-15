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

$view=ReuseableCode::check_rights(13);
$edit=ReuseableCode::check_rights(14);
$delete=ReuseableCode::check_rights(15);
$reject=ReuseableCode::check_rights(210);
$total=0;

$data ='';
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
    $paramTwo = $row->id;
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

        $data.='<tr id="tr'.$row->id.'" class="'.$row->id.'"><td style="text-align: center">'.$counter++.'</td><td style="text-align: center">'.strtoupper($row->purchase_request_no).'</td><td style="text-align: center">'.CommonHelper::changeDateFormat($row->purchase_request_date).'</td><td class="text-center hide">'.$row->trn.'</td><td class="text-center hide">'.$row->remarks.'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-center">'.StoreHelper::checkVoucherStatus($Tstatus,$row->status).'</td><td class="text-center">'.strtoupper($row->username).'</td>'.'</td><td style="text-align: center">'.$cur.' '.number_format($row->total_amount,2).'</td><td style="text-align: center" class="hidden-print">';


            $data.='<div class="dropdown  dropdown-menu_sale_order_list">
            <i class="fa-solid fa-ellipsis-vertical dropdown-toggle action_cursor" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
        <div class="dropdown-menu dropdown-menu_sale_order_list" aria-labelledby="dropdownMenuButton">
        ';


        if ($view==true):
            $data.='<div onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')">';
            $data.='<a type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>';
            $data.='</div>';
            $data .= '<div onClick="window.location.href=\'' . $print_url . '\'" style="cursor: pointer;">';
            $data .= '<a class="dropdown-item_sale_order_list dropdown-item" href="'.$print_url.'">Print</a>';
            $data .= '</div>';
        endif;
        if ($row->status==1 && $row->purchase_request_status!=3):
        endif;


        if ($edit==true)
        if ($row->purchase_request_status == 1):
        if($row->type == 2):
            $data .= '<div onClick="window.location.href=\'' . $edit_url_direct . '\'" style="cursor: pointer;">';
            $data .= '<a href="'.$edit_url_direct.'" type="button" class="dropdown-item_sale_order_list dropdown-item ">Edit</a>';
            $data .= '</div>';
        else:
    
            $data .= '<div onClick="window.location.href=\'' . $edit_url . '\'" style="cursor: pointer;">';
            $data .= '<a href="'.$edit_url.'" type="button" class="dropdown-item_sale_order_list dropdown-item ">Edit</a>';
            $data .= '</div>';
        endif;
        if ($delete)
            $data.='<div onclick="delete_records('.$row->id.','.'2)">';
            $data.= '<a id="'.$row->id.'" type="button" class="dropdown-item_sale_order_list dropdown-item ">Delete</a>';
            $data.='</div>';
        endif;
    
    
        if($row->purchase_request_status == 2):
            if($reject == true):
                $data.='<div>';
                $data.='<a type="button" class="dropdown-item_sale_order_list dropdown-item " id="BtnReject'.$row->id.'" onclick="RejectPo('.$row->id.')">Reject</a>';
                $data.='</div>';
            endif;
        endif;
    
    
    
        $data.='</div>
    </div>';
    
        $data.='
        
        
        
        </td>
    </tr>';
    }
  $data.='<tr style="font-size: large;font-weight: bold !important"><td colspan="5"></td><td>Total</td><td style="text-align: center">'.$cur. ' '. number_format($total,2).'</td><td></td></tr>'
    ?>
    
    <?php
    echo $data;
    ?>