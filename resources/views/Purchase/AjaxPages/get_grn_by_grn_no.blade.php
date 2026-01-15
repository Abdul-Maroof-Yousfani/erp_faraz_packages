<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use Auth;
use DB;
use Config;
use Session;

$counter = 1;
$m = $_GET['m'];
$data ='';

$view=ReuseableCode::check_rights(20);
$edit=ReuseableCode::check_rights(21);
$delete=ReuseableCode::check_rights(22);
$inspection=ReuseableCode::check_rights(23);


$OverAllTot = 0;
$total_pi=0;
$total_return_grn=0;
$total_return_pi=0;

$inspection = 'Production/Report/incomingInspection';
$inspectionHeading = 'INCOMING INSPECTION';
foreach ($goodsReceiptNoteDetail as $row)
{
    $addAmount = DB::Connection('mysql2')->table('addional_expense')->where('main_id',$row->id)->select('amount')->sum('amount');
    $ntAmount = DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->id)->select('net_amount')->sum('net_amount');
    $pi_no=DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_id',$row->id)->select('pv_no')->value('pv_no');
    $currency = DB::Connection('mysql2')->table('purchase_request')->where('purchase_request_no',$row->po_no)->select('currency_id')->first();
    $pi_date=DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_id',$row->id)->select('pv_date')->value('pv_date');
    $net_amount = $ntAmount;
    //  $pi_amount= ReuseableCode::pi_get_net_amount($row->id);
    // $pi_date=$pi_amount->pv_date;
    // $pi_amount=$pi_amount->net_amount;
    //   $return_amount_grn= ReuseableCode::return_amount($row->id,1);
    //   $return_amount_pi= ReuseableCode::return_amount($row->id,2);
    //   $total_pi+=$pi_amount;
    //   $total_return_grn+=$return_amount_grn;
    //   $total_return_pi+=$return_amount_pi;
    $wrong='';
    //  if ($net_amount!=$pi_amount):
    //   $wrong='';
    //   endif;

    if($currency->currency_id == 3):
        $cur = 'PKR';
    elseif($currency->currency_id == 4):
        $cur = 'USD';
    endif;

    $OverAllTot+=$net_amount;

    if($row->type==0 || $row->type == 5):
        $paramOne = "pdc/viewGoodsReceiptNoteDetail";
        $edit_url= url('/purchase/editGoodsReceiptNoteVoucherForm/'.$row->id.'/'.$row->grn_no.'?m='.$m);
    else:
        $paramOne = "pdc/viewGoodsReceiptNoteWPODetail";
        $edit_url= url('/purchase/editGoodsReceiptNoteWithoutPOForm/'.$row->id.'?m='.$m);
    endif;
    $qc='pdc/qc';
    $paramTwo = $row['grn_no'];
    $paramThree = "View Goods Receipt Note Voucher Detail";
    $paramFour = "purchase/editGoodsReceiptNoteVoucherForm";

    if($row->po_no!=""){
        $po_no = $row->po_no;
        $po_date = CommonHelper::changeDateFormat($row->po_date);
    } else{
        $po_no = "Direct";
        $po_date = '---';
    }

    if($row->type==0){ $type = "Purchase"; } elseif($row->type==2){ $type = "Direct"; }elseif($row->type==5){ $type = "Import"; } else{ $type = "Transfer"; }

    $data.='<tr  id="RemoveTr'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->grn_no).'</td><td class="text-center">'.strtoupper($pi_no).'<br>'.CommonHelper::changeDateFormat($pi_date).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->grn_date).'</td><td class="text-center">'.strtoupper($po_no).'</td><td class="text-center">'.$row->supplier_invoice_no.'</td><td class="text-center">'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-center">PKR '.number_format($net_amount,2).'</td><td class="text-center '.$row->id.'">'.PurchaseHelper::checkVoucherStatus($row->grn_status,$row->status,$row->id).'</td><td class="text-center">'.strtoupper($row->username.' '.$wrong).'</td><td class="text-center hidden-print">';
    //$data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';

    $data.='<div class="dropdown">
    <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
    <ul class="dropdown-menu">
    <li>';

    if ($view==true):
        $data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>';
    endif;

    // $data.='<div onclick="showDetailModelOneParamerter(\''.$inspection.'\',\''.$paramTwo.'\',\''.$inspectionHeading.'\')">';
    // $data.='<a type="button" class="dropdown-item_sale_order_list dropdown-item">Inspection</a>';
    // $data.='</div>';
    

    if ($edit==true):
        if($row->grn_status==1 && $row->status == 1 && $row->type!=3 && $row->type !=5):
            $data.='<a type="button" href='.$edit_url.' class="dropdown-item_sale_order_list dropdown-item">Edit</a>';
            //$data.='&nbsp;<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';
        endif;
    endif;

    if ($row->grn_status == 1):
        $data.='<a onclick="showDetailModelOneParamerter(\''.$qc.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" type="button" class="dropdown-item_sale_order_list dropdown-item">Approve</a>';
    endif;
    if ($delete==true):
        if($row->grn_status==1 && $row->status == 1 && $row->type!=3 && $row->type !=5):
            $data.='<a onclick="MasterDeleteGrn('.$row->id.')" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>';
        elseif($row->grn_status==2 && $row->status == 1 && $row->type!=3):
            $data.='<a onclick="MasterDeleteGrn('.$row->id.')" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>';
        elseif($row->type==5):
            $data.='<a onclick="MasterDeleteGrn('.$row->id.')" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>';
        elseif($row->type==3):
            $data.='<a onclick="alert(`Transfer Entry Can not Be Deleted`)" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>';

            //$data.='<div onclick="showDetailModelOneParamerter(\''.$qc.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"><a type="button" class="dropdown-item_sale_order_list dropdown-item">Approve</a></div>';
        endif;
    endif;

    $data.=' </li>
    </ul>
</div>';

$data.='
</td>
</tr>';

}
$data.= '<tr><td colspan="7"></td><td class="text-center">PKR '.number_format($OverAllTot,2).'</td><td></td><td></td><td></td></tr>';
?>

<?php
echo $data;
?>
