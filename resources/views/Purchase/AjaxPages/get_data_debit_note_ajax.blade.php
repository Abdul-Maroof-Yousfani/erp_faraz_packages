<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;


$view=ReuseableCode::check_rights(31);
$edit=ReuseableCode::check_rights(32);
$delete=ReuseableCode::check_rights(33);
$Claus = '';
if($VoucherType == 'all')
    {
        $Claus = "1".","."2";
    }
else{
    $Claus = $VoucherType;
}

CommonHelper::companyDatabaseConnection($m);
if($SupplierId == 'all'):
    $MasterData = DB::select('select * from purchase_return where status = 1 and pr_date BETWEEN "'.$fromDate.'" and "'.$to.'" and type in ('.$Claus.') order by id desc');
else:
    $MasterData = DB::select('select * from purchase_return where status = 1 and pr_date BETWEEN "'.$fromDate.'" and "'.$to.'" and type in ('.$Claus.') and supplier_id = '.$SupplierId.' order by id desc');
endif;
CommonHelper::reconnectMasterDatabase();

$Counter = 1;
$paramOne = "pdc/viewPurchaseReturnDetail?m=".$m;
$paramThree = "View Issuance Detail";
$total_return=0;
$total_net_stock=0;
foreach($MasterData as $Fil):
$edit_url= url('/purchase/editPurchaseReturnForm/'.$Fil->id.'/'.$Fil->pr_no.'?m='.$m);
$net_stock = DB::Connection('mysql2')->table('stock')->where('voucher_no',$Fil->pr_no)->select('amount')->sum('amount');
$total_net_stock+=$net_stock;
$return_amount=  ReuseableCode::return_amount($Fil->grn_id,$Fil->type);


$po_no=     DB::Connection('mysql2')->table('goods_receipt_note')->where('grn_no',$Fil->grn_no)->value('po_no');
?>
<tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
    <td><?php echo $Counter++;?></td>
    <td><?php echo strtoupper($Fil->pr_no);?></td>
    <td><?php echo CommonHelper::changeDateFormat($Fil->pr_date);?></td>
    <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$Fil->supplier_id);?></td>
    <td><?php echo strtoupper($Fil->grn_no.'</br>'.$po_no);?></td>
    <td><?php echo CommonHelper::changeDateFormat($Fil->grn_date);?></td>
    <td><?php echo $Fil->remarks;?></td>
    <td class="text-right hide">{{number_format($return_amount,2)}}</td>
    <td class="text-right hide">{{number_format($net_stock,2)}}</td>
    <td><?php if ($Fil->type==1): echo 'GRN'; elseif($Fil->type==2): echo 'Purchase Invoice';   endif;?></td>
    <td style="text-align: center" class="hidden-print">
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu">
                <li>
            
                    @if($view==true)
                        <a onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $Fil->pr_no;?>','View Purchase Return Detail')" type="button" class="dropdown-item_sale_order_list dropdown-item">View</a>
                    @endif
                    @if($edit==true)
                        <a href='<?php echo $edit_url;?>'  type="button" class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                    @endif
                    @if($delete==true)
                        <a id="BtnDelete<?php echo $Fil->id?>" onclick="DeletePurchaseReturn('<?php echo $Fil->id?>','<?php echo $Fil->pr_no?>')" type="button" class="dropdown-item_sale_order_list dropdown-item">Delete</a>
                    @endif
                </li>
            </ul>
        </div>
    </td>
    <?php $total_return+=$return_amount; ?>
</tr>
<?php endforeach;?>