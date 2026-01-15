<?php
namespace App\Helpers;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\GRNData;
use App\Models\DemandType;
use App\Models\PurchaseRequest;
class PurchaseHelper
{
    public static function homePageURL()
    {
        return url('/');
    }

    public static function generateVendorCode(){
        $vendorDetail = DB::Connection('mysql2')->selectOne('SELECT MAX(vendor_code)+1 as vendorCode FROM supplier');
        return $vendorDetail->vendorCode??1;
    }

    public static function priviousPaymentSummaryAgainstPO($poId){
        return DB::Connection('mysql2')->select('SELECT * FROM `new_pv` as a INNER JOIN new_pv_data as b on a.pv_no = b.pv_no WHERE a.po_id = '.$poId.' and b.debit_credit = 1');
    }

    public static function categoryList($param1,$param2){
        echo '<option value="">Select Category</option>';
        CommonHelper::companyDatabaseConnection($param1);
        $categoryList = new Category;
        $categoryList = $categoryList::where([['status', '=', '1'], ])->select('id','main_ic')->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        foreach($categoryList as $row){
    ?>
        <option value="<?php echo $row['id'];?>" <?php if($param2 == $row['id']){echo 'selected';}?>><?php echo $row['main_ic'];?></option>
    <?php
        }
    }

    public static function subItemList($param1,$param2,$param3,$subitem){
       // echo "$subitem";
        echo '<option value="">Select Item</option>';
        CommonHelper::companyDatabaseConnection($param1);
        $subItemList = new Subitem;
        $subItemList = $subItemList::where([['status', '=', '1'],['main_ic_id', '=', $param3], ])->select('id','sub_ic')->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        foreach($subItemList as $row){
    ?>
            <option value="<?php echo $row['id'];?>" <?php if($subitem == $row['id']){echo 'selected';}?>><?php echo $row['sub_ic'];?></option>
    <?php
        }
    }

    public static function checkVoucherStatus($param1,$param2,$param3=null){
        if($param1 == 1 && $param2 == 1)
        {
            return 'Pending';
        }
        else if($param2 == 2)
        {
            return 'Deleted';
        }
        else if($param1 == 2 && $param2 == 1)
        {
            return 'Approved';
        }

       $count= ReuseableCode::invoice_created($param3);
        if ($count>0):
            return 'Invoice Created';
            endif;
    }

    public static function displayApproveDeleteRepostButtonOneTable(){

    }

    public static function displayApproveDeleteRepostButtonTwoTable($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
    ?>
        <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
        <span class="glyphicon glyphicon-ok"></span> Approve Voucher
        </a>

        <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
        <span class="glyphicon glyphicon-trash"></span> Delete Voucher
        </a>
    <?php
        }else if($param3 == 2 && $param2 == 1){
    ?>
        <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
        <span class="glyphicon glyphicon-edit"></span> Repost Voucher
        </a>
    <?php
        }
    }

    public static function displayApproveDeleteRepostButtonGoodsReceiptNote($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseGoodsReceiptNote('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </a>

            <?php /*?><a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </a><?php */?>
        <?php }else if($param3 == 2 && $param2 == 1){?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
        <?php }
    }

    public static function displayDemandVoucherListButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11){
        $tableOne = $param8;
        $tableTwo = $param9;
        if($param3 == 1 && $param2 == 1){
            return '<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel(\''.$param10.'\',\''.$param4.'\',\''.$param11.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"> P</span></button> <button class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteCompanyPurchaseTwoTableRecords(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-trash"> P</span></button>';
        }else if($param3 == 2 && $param2 == 1){
            return '<button class="delete-modal btn btn-xs btn-warning btn-xs" onclick="repostCompanyPurchaseTwoTableRecords(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-edit"> P</span></button>';
        }
    }

    public static function displayGoodsReceiptNoteVoucherListButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11){
        $tableOne = $param8;
        $tableTwo = $param9;
        if($param3 == 1 && $param2 == 1){
            return '<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel(\''.$param10.'\',\''.$param4.'\',\''.$param11.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"> P</span></button> <button class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteCompanyPurchaseTwoTableRecords(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-trash"> P</span></button>';
        }else if($param3 == 2 && $param2 == 1){
            return '<button class="delete-modal btn btn-xs btn-warning btn-xs" onclick="repostCompanyPurchaseTwoTableRecords(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-edit"> P</span></button>';
        }
    }



    public static function changeActionButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11){
        $tableOne = $param8;
        $tableTwo = $param9;
    ?>
        <?php if($param3 == 1 && $param2 == 1){?>
            <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('<?php echo $param10;?>','<?php echo $param4 ?>','<?php echo $param11 ?>','<?php echo $param1?>')">
                <span class="glyphicon glyphicon-edit"> P</span>
            </button>
            <button class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"> P</span>
            </button>
        <?php }else if($param3 == 2 && $param2 == 1){?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"> P</span>
            </button>
        <?php }?>


        <?php if($param3 != 2 && $param2 == 2){?>
            <button class="edit-modal btn btn-xs btn-info hidden" onclick="showMasterTableEditModel('<?php echo $param8;?>','<?php echo $param4 ?>','<?php echo $param9 ?>','<?php echo $param1?>')">
                <span class="glyphicon glyphicon-edit"> A</span>
            </button>
            <button class="delete-modal btn btn-xs btn-danger btn-xs hidden" onclick="deleteCompanyFinanceThreeTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions')">
                <span class="glyphicon glyphicon-trash"> A</span>
            </button>
        <?php }else if($param3 == 2 && $param2 == 2){?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs hidden" onclick="repostCompanyFinanceThreeTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions')">
                <span class="glyphicon glyphicon-edit"> A</span>
            </button>
        <?php }?>
    <?php
    }


    public static function getCreditAccountHeadNameForInvoice($param1,$param2){
        CommonHelper::companyDatabaseConnection($param2);
        $accountName = DB::selectOne('select `name` from `accounts` where `id` = '.$param1.'')->name;
        CommonHelper::reconnectMasterDatabase();
        return $accountName;
    }

    public static function purchasePaymentVoucherSummaryDetail($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $result = \DB::table("pv_data")
            ->select("pv_data.pv_no","pv_data.amount","pv_data.acc_id","pv_data.debit_credit","pv_data.id","pvs.pv_no","pvs.grn_no")
            ->join('pvs','pv_data.pv_no','=','pvs.pv_no')
            ->where(['pvs.grn_no' => $param2,'pvs.status' => '1','pvs.pv_status' => '2','pvs.pv_status' => '2','pv_data.debit_credit' => '0'])
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $data='';
        $data.='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="table-responsive"><table class="table table-bordered sf-table-list"><thead><th class="text-center">S.No</th><th class="text-center">PV No</th><th class="text-center">Account Head</th><th class="text-center col-sm-3">Amount</th></thead><tbody>';
        $counter = 1;
        $totalPaymentAmount = 0;
        foreach($result as $row){
            $totalPaymentAmount += $row->amount;
            $data .='<tr><td class="text-center">'.$counter++.'</td>';
            $data .='<td class="text-center">'.$row->pv_no.'</td>';
            $data .='<td class="text-center">'.static::getCreditAccountHeadNameForInvoice($row->acc_id,$param1).'</td>';
            $data .='<td class="text-right">'.$row->amount.'</td></tr>';
        }
        $data.='</tbody></table></div></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" readonly name="totalPaymentAmount" id="totalPaymentAmount" value="'.$totalPaymentAmount.'" class="form-control" /></div></div>';
        return $data;
    }

    public static function monthWisePurchaseActivitySupplierWise($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
            $supplierId = $param2;
            $monthStartDate = date(''.$param3.'-01');
            $monthEndDate   = date(''.$param3.'-t');
            $resultFara = DB::table('fara')
                ->select('grn_no','grn_date','main_ic_id','sub_ic_id','supp_id','qty','price','value','action')
                ->where('supp_id','=',$param2)
                ->whereBetween('date', array($monthStartDate, $monthEndDate))
                ->get();
        CommonHelper::reconnectMasterDatabase();
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list">
                        <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Category Name</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">GRN. No.</th>
                                <th class="text-center">GRN. Date</th>
                                <th class="text-center">Qty.</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $counter = 1;
                            $totalPurchaseAmount = 0;
                            foreach($resultFara as $row){
                                $totalPurchaseAmount += $row->value;
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center"><?php echo CommonHelper::getCompanyDatabaseTableValueById($param1,'category','main_ic',$row->main_ic_id);?></td>
                                <td class="text-center"><?php echo CommonHelper::getCompanyDatabaseTableValueById($param1,'subitem','sub_ic',$row->sub_ic_id);?></td>
                                <td class="text-center"><?php echo $row->grn_no;?></td>
                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->grn_date);?></td>
                                <td class="text-center"><?php echo $row->qty;?></td>
                                <td class="text-center"><?php echo $row->value/$row->qty;?></td>
                                <td class="text-right"><?php echo $row->value;?></td>
                            </tr>
                        <?php
                            }
                        ?>
                            <tr>
                                <td colspan="7">Total Amount</td>
                                <td class="text-right"><?php echo $totalPurchaseAmount;?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    public static function monthWisePaymentVoucherActivitySupplierWise($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
        $supplierId = $param2;
        $monthStartDate = date(''.$param3.'-01');
        $monthEndDate   = date(''.$param3.'-t');
        $accId = $param4;
        $result = \DB::table("pv_data")
            ->select("pv_data.pv_no","pv_data.pv_date","pv_data.amount","pv_data.acc_id","pv_data.debit_credit","pv_data.id","pvs.pv_no","pvs.grn_no","pvs.cheque_no","pvs.cheque_date","pvs.voucherType")
            ->join('pvs','pv_data.pv_no','=','pvs.pv_no')
            ->where(['pv_data.acc_id' => $accId,'pvs.status' => '1','pvs.pv_status' => '2'])
            ->whereBetween('pv_data.pv_date', array($monthStartDate, $monthEndDate))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $data='';
        $data.='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="table-responsive"><table class="table table-bordered sf-table-list"><thead><th class="text-center">S.No</th><th class="text-center">PV No</th><th class="text-center">PV Date</th><th class="text-center">Cheque No</th><th class="text-center">Cheque Date</th><th class="text-center col-sm-3">Amount</th></thead><tbody>';
        $counter = 1;
        $totalPaymentAmount = 0;
        foreach($result as $row){
            $totalPaymentAmount += $row->amount;
            $data .='<tr><td class="text-center">'.$counter++.'</td>';
            $data .='<td class="text-center">'.$row->pv_no.'</td>';
            $data .='<td class="text-center">'.CommonHelper::changeDateFormat($row->pv_date).'</td>';
            if($row->voucherType == 4 || $row->voucherType == 2){
                $data .='<td class="text-center">'.$row->cheque_no.'</td>';
                $data .='<td class="text-center">'.CommonHelper::changeDateFormat($row->cheque_date).'</td>';
            }else{
                $data .='<td class="text-center">---</td>';
                $data .='<td class="text-center">---</td>';
            }
            $data .='<td class="text-right">'.$row->amount.'</td></tr>';
        }
        $data.='</tbody></table></div></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" readonly name="totalPaymentAmount" id="totalPaymentAmount" value="'.$totalPaymentAmount.'" class="form-control" /></div></div>';
        return $data;
    }

    public static function get_detail_purchase_voucher($id)
    {
        $id = $id;
        $sub_item=CommonHelper::sub_item_connection()->where('status',1)->where('id',$id)->select('uom','rate','pack_size','description','itemType')->first();
        $uom=CommonHelper::uom_connection()->where('status',1)->where('id',$sub_item->uom)->select('uom_name')->first();
        $grn_data=new GRNData();
        $grn_data=$grn_data->SetConnection('mysql2');
        $grn_data=$grn_data->where('status',1)->where('grn_status',4);

        $demand_type=new DemandType();
        $demand_type=$demand_type->SetConnection('mysql2');
        $demand_type=$demand_type->where('status',1)->where('id',$sub_item->itemType)->select('name')->first();
        $demand_type_name=$demand_type->name;


        if ($grn_data->count()>0):
            $qty=$grn_data->select('sum(purchaseRequestQty)as qty')->first();
        else:
            $qty=0;
        endif;

        $grn_data=DB::Connection('mysql2')->selectOne('select max(purchase_approved_qty)approve_qty,max(purchase_recived_qty)recived_qty from grn_data where status=1
        and sub_item_id="'.$id.'"');



        return $uom->uom_name.'*'.$sub_item->rate.'*'.$sub_item->uom.'*'.$sub_item->pack_size.'*'
        .$sub_item->description.'*'.$demand_type_name.'*'.$qty.'*'.$sub_item->itemType.'*'.
        $grn_data->approve_qty.'*'.$grn_data->recived_qty;

    }

    public static function checkPO($id)
    {

        $purchase_request=new PurchaseRequest();
        $purchase_request=$purchase_request->SetConnection('mysql2');
        $purchase_request=$purchase_request->where('id',$id)->select('po_type')->first();
        return $purchase_request->po_type;

    }

    public static function get_unique_no_transfer($year, $month)
    {

        $quotation_no = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`tr_no`,7,length(substr(`tr_no`,3))-3),signed integer)) reg
        from `stock_transfer` where substr(`tr_no`,3,2) = " . $year . " and substr(`tr_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $job_order_no = 'tr' . $year . $month . $str;


    }

    public static function get_unique_no_internal_consumtion($year, $month)
    {

        $quotation_no = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`voucher_no`,7,length(substr(`voucher_no`,3))-3),signed integer)) reg
        from `internal_consumtion` where substr(`voucher_no`,3,2) = " . $year . " and substr(`voucher_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $job_order_no = 'ic' . $year . $month . $str;


    }

}
?>