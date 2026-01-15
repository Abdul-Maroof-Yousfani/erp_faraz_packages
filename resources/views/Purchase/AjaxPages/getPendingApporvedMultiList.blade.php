<?php
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;


if($tab == 'pr'):
$data='';

?>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="demandVoucherList">
        <thead>
            <th colspan="7" class="text-center"><strong><?php echo $ApprovedPendingLable?></strong></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">PR NO.</th>
        <th class="text-center">PR Date</th>
        <th class="text-center">Ref No.</th>
        <th class="text-center">Sub Department</th>
        <th class="text-center">Demand Status</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody>

<?php
$counter = 1;

foreach ($MultiData as $row){
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
$paramTwo = $row->demand_no;
$paramThree = "View Purchase Request List";
$paramFour = "purchase/editDemandVoucherForm";
$data.='<tr class="'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->demand_no).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->demand_date).'</td><td class="text-center">'.$row->slip_no.'</td><td class="text-center">'.CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id).'</td><td class="text-center">'.PurchaseHelper::checkVoucherStatus($row->demand_status,$row->status)
        .'</td><td class="text-center hidden-print">
        <button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
        //if($row->demand_status==1):
        $data.= $purchase_request_no;
        if($PO_data_count <= 0):
        $data.=
        '.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>
        <button id="'.$row->id.'" type="button" onclick="delete_records(this.id)" class="btn btn-danger btn-xs">Delete</button>';endif;

        // .'</td><td class="text-center hidden-print">';

        // $data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';
        // $data.='&nbsp'.PurchaseHelper::displayDemandVoucherListButton($m,$row->demand_status,$row->status,$row->demand_no,'demand_no','demand_status','status','demand','demand_data',$paramFour,'Demand Voucher Edit Detail Form').'&nbsp;';
        $data.='</td>
</tr>';
}
echo $data;
?>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'po'):


?>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list">
        <thead>
            <th colspan="10" class="text-center"><strong><?php echo $ApprovedPendingLable?></strong></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">P.O. No.</th>
        <th class="text-center">P.O. Date</th>
        <th class="text-center">TRN </th>
        <th class="text-center">Sub Department</th>
        <th class="text-center">Supplier Name</th>
        <th class="text-center">P.O Status</th>
        <th class="text-center">Created User</th>
        <th class="text-center">Amount</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody>
        <?php
        $view=ReuseableCode::check_rights(3);
        $edit=ReuseableCode::check_rights(4);
        $delete=ReuseableCode::check_rights(5);
        $counter = 1;
        $data ='';
        $total=0;
        foreach ($MultiData as $row){
            $edit_url= url('/store/editPurchaseRequestVoucherForm/'.$row->id.'?m='.$m);
            $edit_url_direct= url('/store/editDirectPurchaseRequestVoucherForm/'.$row->id.'?m='.$m);
            $net_amount= ReuseableCode::get_po_total_amount($row->id);
            $total+=$net_amount;
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

            $data.='<tr id="tr'.$row->id.'" class="'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->purchase_request_no).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->purchase_request_date).'</td><td class="text-center">'.$row->trn.'</td><td class="text-center">'.CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id).'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-center">'.StoreHelper::checkVoucherStatus($Tstatus,$row->status).'</td><td class="text-center">'.strtoupper($row->username).'</td>'.'</td><td class="text-center">'.number_format($net_amount,2).'</td><td class="text-center hidden-print">
            ';

            if ($view==true):
                $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
            endif;
            if ($row->status==1 && $row->purchase_request_status!=3):

            endif;
            if ($edit==true)
                if ($row->purchase_request_status==1 || CommonHelper::CheckGrnCount($row->purchase_request_no) == 0):
                    if($row->type == 2):
                        $data.='.<a href='.$edit_url_direct.' type="button" class="btn btn-primary btn-xs">Edit</a>';
                    else:
                        $data.='.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';
                    endif;
                    if ($delete)
                        $data.= '.<button id="'.$row->id.'" type="button" onclick="delete_records(this.id)" class="btn btn-danger btn-xs">Delete</button>';
                endif;

            if($row->purchase_request_status == 2):
                $data.='.<button type="button" class="btn btn-xs btn-danger" id="BtnReject'.$row->id.'" onclick="RejectPo('.$row->id.')">Reject</button>';
            endif;
            $data.='</td>
</tr>';
        }
        $data.='<tr style="font-size: large;font-weight: bold"><td colspan="8">Total</td><td>'.number_format($total,2).'</td></tr>'
        ?>

        <?php
        echo $data;
        ?>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'grn'):?>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="goodsReceiptNoteList">
        <thead>
            <th colspan="12" class="text-center"><strong><?php echo $ApprovedPendingLable?></strong></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">GRN NO..</th>
        <th class="text-center">GRN Date</th>
        <th class="text-center">TYPE</th>
        <th class="text-center">PO No.</th>
        <th class="text-center">PO Date</th>
        <th class="text-center">Supplier Invoice No.</th>
        <th class="text-center">Sub Department</th>
        <th class="text-center">Supplier Name</th>
        <th class="text-center">GRN Status</th>
        <th class="text-center">Created User</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody>
        <?php

        $counter = 1;
        $data ='';

        $view=ReuseableCode::check_rights(8);
        $edit=ReuseableCode::check_rights(9);
        $delete=ReuseableCode::check_rights(10);

        foreach ($MultiData as $row)
        {


            if($row->type==0 || $row->type == 5):
                $paramOne = "pdc/viewGoodsReceiptNoteDetail";
                $edit_url= url('/purchase/editGoodsReceiptNoteVoucherForm/'.$row->id.'/'.$row->grn_no.'?m='.$m);
            else:
                $paramOne = "pdc/viewGoodsReceiptNoteWPODetail";
                $edit_url= url('/purchase/editGoodsReceiptNoteWithoutPOForm/'.$row->id.'?m='.$m);
            endif;
            $paramTwo = $row->grn_no;
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

            $data.='<tr id="RemoveTr'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->grn_no).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->grn_date).'</td><td class="text-center">'.$type.'</td><td class="text-center">'.strtoupper($po_no).'</td><td class="text-center">'.$po_date.'</td><td class="text-center">'.$row->supplier_invoice_no.'</td><td class="text-center">'.CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id).'</td><td class="text-center">'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td  class="text-center '.$row->id.'">'.PurchaseHelper::checkVoucherStatus($row->grn_status,$row->status,$row->id).'</td><td class="text-center">'.strtoupper($row->username).'</td><td class="text-center">';
            //$data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';

            if ($view==true)
                if($row->type==0):
                    $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
                else:
                    $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
                endif;

            //$data.='.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';

            if ($edit==true):
                if($row->grn_status==1 && $row->status == 1 && $row->type!=3 && $row->type !=5):
                    $data.='.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';
                endif;
            endif;

            if ($delete==true):
                if($row->grn_status==1 && $row->status == 1 && $row->type!=3 && $row->type !=5):
                    $data.='<button id="'.$row->id.'" type="button" onclick="MasterDeleteGrn('.$row->id.')" class="btn btn-danger btn-xs">Delete</button>';
                    $data.'</td></tr>';



                elseif($row->grn_status==2 && $row->status == 1 && $row->type!=3):
                    $data.='.<button id="'.$row->id.'" type="button" onclick="MasterDeleteGrn('.$row->id.')" class="btn btn-danger btn-xs">Delete</button>';
                    $data.'</td></tr>';
                elseif($row->type==5):
                    $data.='.<button id="'.$row->id.'" type="button" onclick="MasterDeleteGrn('.$row->id.')" class="btn btn-danger btn-xs">Delete</button>';
                    $data.'</td></tr>';
                elseif($row->type==3):
                    $data.='.<button id="'.$row->id.'" type="button" onclick="alert(`Transfer Entry Can not Be Deleted`)" class="btn btn-danger btn-xs">Delete</button>';
                    $data.'</td></tr>';

                else:
                    $data.='</td></tr>';
                endif;
            else:
                $data.='</td></tr>';
            endif;

        }
        ?>

        <?php
        echo $data;
        ?>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'pi'):?>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
        <thead>
            <th colspan="13" class="text-center"><strong><?php echo $ApprovedPendingLable?></strong></th>
        </thead>
        <thead>
        <th class="text-center col-sm-1">S.No</th>
        <th class="text-center col-sm-1">PV No</th>
        <th class="text-center col-sm-1">PV Date</th>
        <th class="text-center col-sm-1">GRN No</th>
        <th class="text-center col-sm-1">Ref  No</th>
        <th class="text-center col-sm-1">Bill Date</th>
        <th class="text-center">Supplier</th>

        <th class="text-center">PV Status</th>
        <th class="text-center">Amount</th>
        <th class="text-center">GRN Amount</th>
        <th class="text-center">View</th>
        <th class="text-center">Edit</th>
        <th class="text-center">Delete</th>
        </thead>
        <tbody id="data">
        <?php $counter = 1;$total=0;
        $view=ReuseableCode::check_rights(22);
        $edit=ReuseableCode::check_rights(23);
        $delete=ReuseableCode::check_rights(24);
        ?>

        @foreach($MultiData as $row)
            <?php
            $net_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id',$row->id)->sum('net_amount');
            $net_amount_grn= DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->grn_id)->sum('net_amount');
            $t_amount= DB::Connection('mysql2')->table('transactions')->where('voucher_no',$row->pv_no)
                    ->where('debit_credit',1)->sum('amount');
            $total+=$net_amount?>
            <tr @if($t_amount!=$net_amount) @elseif($net_amount!=$net_amount_grn) style="background-color: cornflowerblue" @endif id="{{$row->id}}">
                <td class="text-center">{{$counter++}}</td>
                <td title="{{$row->id}}" class="text-center">{{strtoupper($row->pv_no)}}</td>
                <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->pv_date);?></td>
                <td title="{{$row->id}}" class="text-center">{{strtoupper($row->grn_no)}}</td>
                <td class="text-center">{{$row->slip_no}}</td>
                <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->bill_date);?></td>
                <td class="text-center">{{CommonHelper::get_supplier_name($row->supplier)}}</td>
                <td class="text-center {{$row->id}}">@if($row->pv_status==1)PENDING @else Approve @endif</td>
                <td class="text-right">{{number_format($net_amount,2)}}</td>
                <td class="text-right">{{number_format($net_amount_grn,2)}}</td>
                <?php //$total+=$row->total_net_amount; ?>
                <td class="text-center">
                    @if($view==true)
                        <button
                                onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')"
                                type="button" class="btn btn-success btn-xs">View</button>
                    @endif
                </td>

                <?php if($row->pv_status == 1):?>

                <td class="text-center">  @if($edit==true)<a href="{{ URL::asset('finance/editPurchaseVoucherFormNew/'.$row->id.'?m='.$m) }}" class="btn btn-success btn-xs">Edit </a>@endif</td>

                <?php else:?>
                <td></td>
                <?php endif;?>
                <td class="text-center">@if($delete==true)<button onclick="delete_record('<?php echo $row->id?>','<?php echo $row->grn_no ?>','<?php echo $row->pv_no?>')" type="button" class="btn btn-danger btn-xs">DELETE</button>@endif</td>
            </tr>


        @endforeach
        <tr>
            <td colspan="8">Total</td>
            <td colspan="1">{{number_format($total,2)}}</td>
        </tr>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'st'):?>
<div class="table-responsive" >
    <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
        <thead>
            <th colspan="6" class="text-center"><strong><?php echo $ApprovedPendingLable?></strong></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Stock Transfer No.</th>
        <th class="text-center">Stock Transfer Date</th>
        <th class="text-center">Desription</th>
        <th class="text-center">Status</th>
        <th class="text-center hidden-print">Action</th>
        </thead>

        <tbody id="ShowHide">

        <?php

        $view=ReuseableCode::check_rights(17);
        $edit=ReuseableCode::check_rights(18);
        $delete=ReuseableCode::check_rights(19);
        $Counter = 1;
        $paramOne = "stdc/viewStockTransferDetail?m=".$m;
        $paramThree = "View Issuance Detail";

        foreach($MultiData as $row):
        $edit_url= url('/store/editStockTransferForm/'.$row->id.'/'.$row->tr_no.'?m='.$m);
        ?>
        <tr class="text-center" id="RemoveTr<?php echo $row->id?>">
            <td><?php echo $Counter++;?></td>
            <td><?php echo strtoupper($row->tr_no);?></td>
            <td><?php echo CommonHelper::changeDateFormat($row->tr_date);?></td>
            <td><?php echo strtoupper($row->description);?></td>
            <td class="{{$row->id}}">@if($row->tr_status==1) Pending @else Approve @endif</td>
            <td>
                @if($view==true)
                    <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $row->tr_no;?>','View Stock Transfer Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                @endif
                @if($edit==true)
                    <a href="<?php echo $edit_url?>" type="button" class="btn btn-xs btn-primary">Edit</a>
                @endif
                @if($delete==true)
                    <button type="button" class="btn btn-danger btn-xs" id="BtnDelete<?php echo $row->id?>" onclick="DeleteStockTransfer('<?php echo $row->id?>','<?php echo $row->tr_no?>','<?php echo $row->tr_status?>')">Delete</button>
                @endif
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'tdn'):?>
<div class="table-responsive" >
    <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
        <thead>
        <th colspan="8" class="text-center"><strong><?php echo $ApprovedPendingLable?></strong></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Purchase Return No.</th>
        <th class="text-center">Purchase Return Date</th>
        <th class="text-center">Supplier Name</th>
        <th class="text-center">Grn No</th>
        <th class="text-center">Grn Date</th>
        <th class="text-center">Remarks</th>
        <th class="text-center hidden-print">Action</th>
        </thead>

        <tbody id="ShowHide">

        <?php

        $view=ReuseableCode::check_rights(13);
        $edit=ReuseableCode::check_rights(14);
        $delete=ReuseableCode::check_rights(15);

        $Counter = 1;
        $paramOne = "pdc/viewPurchaseReturnDetail?m=".$m;
        $paramThree = "View Issuance Detail";

        foreach($MultiData as $Fil):
        $edit_url= url('/purchase/editPurchaseReturnForm/'.$Fil->id.'/'.$Fil->pr_no.'?m='.$m);
        ?>
        <tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
            <td><?php echo $Counter++;?></td>
            <td><?php echo strtoupper($Fil->pr_no);?></td>
            <td><?php echo CommonHelper::changeDateFormat($Fil->pr_date);?></td>
            <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$Fil->supplier_id);?></td>
            <td><?php echo strtoupper($Fil->grn_no);?></td>
            <td><?php echo CommonHelper::changeDateFormat($Fil->grn_date);?></td>
            <td><?php echo $Fil->remarks;?></td>
            <td>
                @if($view==true)
                    <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $Fil->pr_no;?>','View Purchase Return Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                @endif
                @if($edit==true)
                    <a href='<?php echo $edit_url;?>' type="button" class="btn btn-primary btn-xs">Edit</a>
                @endif
                @if($delete==true)
                    <button type="button" class="btn btn-danger btn-xs" id="BtnDelete<?php echo $Fil->id?>" onclick="DeletePurchaseReturn('<?php echo $Fil->id?>','<?php echo $Fil->pr_no?>')">Delete</button>
                @endif
            </td>

        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php else:?>
<?php endif;?>
