<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\StoreHelper;
use App\Helpers\PurchaseHelper;




//$PurchaseInvoice = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('grn_no',$GoodsReceiptNote->grn_no);
//$DebitNote = DB::Connection('mysql2')->table('purchase_return')->where('status',1)->where('grn_no',$GoodsReceiptNote->grn_no);
//print_r($PurchaseRequest); die();

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                <thead>
                <tr>
                    <th colspan="10" class="text-center"><strong style="font-size: 25px !important;">Purchase Order</strong></th>
                </tr>
                <tr>
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
                </tr>

                </thead>
                <tbody>
                <?php $counter = 1;$total=0;
                        $data='';
                $PoNo = "";
                foreach ($PurchaseRequest as $row){
                    $PoNo .= $row->purchase_request_no.',';
                    $net_amount= ReuseableCode::get_po_total_amount($row->id);
                    $total+=$net_amount;
                    $paramOne = "stdc/viewPurchaseRequestVoucherDetail?m=".$m;
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


                        $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';

                    if ($row->status==1 && $row->purchase_request_status!=3):

                    endif;
                    $data.='</td></tr>';
                }
                $data.='<tr style="font-size: large;font-weight: bold"><td colspan="8">Total</td><td>'.number_format($total,2).'</td></tr>';

                echo  $data;
                $PoNo = rtrim($PoNo,",");
                $GoodsReceiptNote = DB::Connection('mysql2')->select("select * from goods_receipt_note where status = 1 and po_no in ('$PoNo') ");
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if($GoodsReceiptNote):?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                <thead>
                <tr>
                    <th colspan="13" class="text-center"><strong style="font-size: 25px !important;">Goods Receipt Note</strong></th>
                </tr>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">GRN NO</th>
                    <th class="text-center">GRN Date</th>
                    <th class="text-center">PO No.</th>
                    <th class="text-center">Supplier Invoice No.</th>
                    <th class="text-center">Supplier Name</th>
                    <th class="text-center">GRN Net Amount</th>
                    <th class="text-center">Return Net Amount</th>
                    <th class="text-center">PI Net Amount</th>
                    <th class="text-center">Return Net Amount</th>
                    <th class="text-center">GRN Status</th>
                    <th class="text-center">Created User</th>
                    <th class="text-center hidden-print">Action</th>
                </tr>

                </thead>
                <tbody>
                <?php

                $OverAllTot = 0;
                $total_pi=0;
                $total_return_grn=0;
                $total_return_pi=0;
                        $data='';
                        $GrnNo = '';
                foreach ($GoodsReceiptNote as $row)
                {
                    $GrnNo .= $row->grn_no.',';
                    $addAmount = DB::Connection('mysql2')->table('addional_expense')->where('main_id',$row->id)->select('amount')->sum('amount');
                    $ntAmount = DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->id)->select('net_amount')->sum('net_amount');
                    $net_amount = $ntAmount+$addAmount;
                    $pi_amount= ReuseableCode::pi_get_net_amount($row->id);
                    $return_amount_grn= ReuseableCode::return_amount($row->id,1);
                    $return_amount_pi= ReuseableCode::return_amount($row->id,2);
                    $total_pi+=$pi_amount;
                    $total_return_grn+=$return_amount_grn;
                    $total_return_pi+=$return_amount_pi;
                    $wrong='';
                    if ($net_amount!=$pi_amount):
                        $wrong='';
                    endif;

                    $OverAllTot+=$net_amount;

                    if($row->type==0 || $row->type == 5):
                        $paramOne = "pdc/viewGoodsReceiptNoteDetail?m=".$m;
                    else:
                        $paramOne = "pdc/viewGoodsReceiptNoteWPODetail?m=".$m;
                    endif;
                    $paramTwo = $row->grn_no;
                    $paramThree = "View Goods Receipt Note Voucher Detail";

                    if($row->po_no!=""){
                        $po_no = $row->po_no;
                        $po_date = CommonHelper::changeDateFormat($row->po_date);
                    } else{
                        $po_no = "Direct";
                        $po_date = '---';
                    }

                    if($row->type==0){ $type = "Purchase"; } elseif($row->type==2){ $type = "Direct"; }elseif($row->type==5){ $type = "Import"; } else{ $type = "Transfer"; }

                    $data.='<tr  id="RemoveTr'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->grn_no).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->grn_date).'</td><td class="text-center">'.strtoupper($po_no).'</td><td class="text-center">'.$row->supplier_invoice_no.'</td><td class="text-center">'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-right">'.number_format($net_amount,2).'</td><td class="text-right">'.number_format($return_amount_grn,2).'</td><td class="text-right">'.number_format(ReuseableCode::pi_get_net_amount($row->id),2).'</td><td class="text-right">'.number_format($return_amount_pi,2).'</td><td  class="text-center '.$row->id.'">'.PurchaseHelper::checkVoucherStatus($row->grn_status,$row->status,$row->id).'</td><td class="text-center">'.strtoupper($row->username.' '.$wrong).'</td><td class="text-center">';
                    //$data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';


                        if($row->type==0):
                            $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
                        else:
                            $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
                        endif;


                        $data.='</td></tr>';
                }
                $data.= '<tr><td colspan="6"></td><td>'.number_format($OverAllTot,2).'</td><td>'.number_format($total_return_grn,2).'</td><td>'.number_format($total_pi,2).'</td><td>'.number_format($total_return_pi,2).'</td></tr>';
                  echo $data;
                echo $GrnNo = rtrim($GrnNo,",");
                $GrnNo=explode(',',$GrnNo);
                $PurchaseInvoice = DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->whereIn('grn_no',$GrnNo);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php endif;?>
<?php if($PurchaseInvoice->count() > 0):?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                <thead>
                <tr>
                    <th colspan="11" class="text-center"><strong style="font-size: 25px !important;">Purchase Invoice</strong></th>
                </tr>
                <tr><th class="text-center col-sm-1">S.No</th>
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
                </tr>

                </thead>
                <tbody id="data">
                <?php $counter = 1;$total=0;?>

                @foreach($PurchaseInvoice->get() as $row)
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
                        <td class="text-center">

                                <button
                                        onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')"
                                        type="button" class="btn btn-success btn-xs">View</button>
                        </td>

                    </tr>


                @endforeach
                <tr>
                    <td colspan="8">Total</td>
                    <td colspan="1">{{number_format($total,2)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif;?>
    <?php $PurchaseReturn = DB::Connection('mysql2')->table('purchase_return')->where('status',1)->whereIn('grn_no',$GrnNo);?>
<?php if($PurchaseReturn->count() > 0):?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                <thead>
                <tr>
                    <th colspan="9" class="text-center"><strong style="font-size: 25px !important;">Debit Note</strong></th>
                </tr>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">Purchase Return No.</th>
                    <th class="text-center">Purchase Return Date</th>
                    <th class="text-center">Supplier Name</th>
                    <th class="text-center">Grn No</th>
                    <th class="text-center">Grn Date</th>
                    <th class="text-center">Remarks</th>
                    <th class="text-center">Type</th>
                    <th class="text-center hidden-print">Action</th>
                </tr>

                </thead>
                <tbody>
                <?php

                $Counter = 1;
                $paramOne = "pdc/viewPurchaseReturnDetail?m=".$m;
                $paramThree = "View Issuance Detail";

                foreach($PurchaseReturn->get() as $Fil):
                ?>
                <tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
                    <td><?php echo $Counter++;?></td>
                    <td><?php echo strtoupper($Fil->pr_no);?></td>
                    <td><?php echo CommonHelper::changeDateFormat($Fil->pr_date);?></td>
                    <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$Fil->supplier_id);?></td>
                    <td><?php echo strtoupper($Fil->grn_no);?></td>
                    <td><?php echo CommonHelper::changeDateFormat($Fil->grn_date);?></td>

                    <td><?php echo $Fil->remarks;?></td>

                    <td><?php if ($Fil->type==1): echo 'GRN'; elseif($Fil->type==2): echo 'Purchase Invoice';   endif;?></td>
                    <td>
                        <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $Fil->pr_no;?>','View Purchase Return Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                    </td>

                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif;?>


