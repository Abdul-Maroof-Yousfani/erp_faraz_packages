<?php
namespace App\Helpers;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Models\Category;
use App\Models\Subitem;
class StoreHelper
{
    public static function homePageURL()
    {
        return url('/');
    }


    public static function checkVoucherStatus($param1,$param2)
    {
        if ($param1 == 1 && $param2 == 1) {
            return 'Pending';
        } else if ($param2 == 2) {
            return 'Deleted';
        } else if ($param1 == 2 && $param2 == 1) {
            return 'Approved';
        } else if ($param1 == 3 && $param2 == 1) {
            return 'GRN CREATED';
        } else if($param1 == 4) {
            return 'Rejected';
        }
    }



    public static function checkItemWiseCurrentBalanceQty($param1,$param2,$param3,$param4,$param5){
        //return $param1.'----'.$param2.'----'.$param3.'<br />';
        CommonHelper::companyDatabaseConnection($param1);
        $openingBalance = DB::selectOne('select `qty` from `fara` where `action` = 1 and `status` = 1 and `main_ic_id` = '.$param2.' and `sub_ic_id` = '.$param3.' ')->qty;
        $purchaseBalance = '';
        //$sendBalance = DB::selectOne('select `qty` from `fara` where `action` = 2 and `status` = 1 and `main_ic_id` = '.$param2.' and `sub_ic_id` = '.$param3.' ');
        $sendBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '2'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        $returnBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '4'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        $purchaseBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '3'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();

        $cashSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '5'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();

        $creditSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '6'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalSendBalance = 0;
        foreach ($sendBalance as $row){
            $totalSendBalance += $row->qty;
        }
        $totalReturnBalance = 0;
        foreach ($returnBalance as $row){
            $totalReturnBalance += $row->qty;
        }
        $totalPurchaseBalance = 0;
        foreach ($purchaseBalance as $row){
            $totalPurchaseBalance += $row->qty;
        }

        $totalCashSaleBalance = 0;
        foreach ($cashSaleBalance as $row){
            $totalCashSaleBalance += $row->qty;
        }

        $totalCreditSaleBalance = 0;
        foreach ($creditSaleBalance as $row){
            $totalCreditSaleBalance += $row->qty;
        }
        $currentBalanceInStore = $openingBalance + $totalPurchaseBalance + $totalReturnBalance - $totalSendBalance  - $totalCashSaleBalance  - $totalCreditSaleBalance;

        return $currentBalanceInStore;
    }

    public static function issueQtyItemWiseDetail($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("store_challan_data")
            ->select(DB::raw("SUM(issue_qty) as issue_qty"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where([$param5 => $param4])
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalQty = 0;
        foreach ($data as $row){
            $totalQty += $row->issue_qty;
        }
        return $totalQty;
    }

    public static function demandAndRemainingQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::issueQtyItemWiseDetail($param1,$param2,$param3,$param4,$param5);
        return 'Demand and Remaining Qty Item Wise';
    }

    public static function displayApproveDeleteRepostButtonPurchaseRequest($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param3 == 1 && $param2 == 1){

     
            ?>
            <button class="delete-modal btn btn-success" data-dismiss="modal" aria-hidden="true" onclick="approvePurchaseRequest('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                Approve
            </button>

            <!-- <button class="delete-modal btn btn-danger" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete
            </button> -->
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
    }

    public static function displayApproveDeleteRepostButtonPurchaseRequestSale($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param3 == 1 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approvePurchaseRequestSale('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>

            <?php /*?><button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </button><?php */?>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
    }

    public static function displayApproveDeleteRepostButtonTwoTable($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>

            <button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </button>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
    }

    public static function displayStoreChallanButton($param1,$param2,$param3,$param4,$param5){
        if($param4 == '1'){
            $paramOne = "store/editStoreChallanVoucherForm";
            $paramTwo = $param5;
            $paramThree = "Store Challan Voucher Edit Detail Form";
            $paramFour = $param1;
            return '<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\',\''.$paramFour.'\')"><span class="glyphicon glyphicon-edit"> P</span></button>';
        }
    }

    public static function displayPurchaseChallanButton($param1,$param2,$param3,$param4,$param5){
        if($param3 == 1 && $param2 == 1) {
            $paramOned = "store/editPurchaseRequestVoucherForm";
            $paramTwod = $param5;
            $paramThreed = "Purchase Request Voucher Edit Detail Form";
            $paramFourd = $param1;
            return '<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel(\'' . $paramOned . '\',\'' . $paramTwod . '\',\'' . $paramThreed . '\',\'' . $paramFourd . '\')"><span class="glyphicon glyphicon-edit"> P</span></button>';
        }else{

        }
    }

    public static function displayStoreChallanReturnButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12,$param13)
    {
        if ($param3 == 1) {
            return '<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel(\'' . $param6 . '\',\'' . $param4 . '\',\'' . $param7 . '\',\'' . $param1 . '\')"><span class="glyphicon glyphicon-edit"> P</span></button> <button class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteCompanyStoreThreeTableRecords(\'' . $param1 . '\',\'' . $param2 . '\',\'' . $param3 . '\',\'' . $param4 . '\',\'' . $param5 . '\',\'' . $param8 . '\',\'' . $param9 . '\',\'' . $param10 . '\',\'' . $param11 . '\',\'' . $param12 . '\')"><span class="glyphicon glyphicon-trash"> A</span></button>';
        }else{
            return '<button class="delete-modal btn btn-xs btn-warning btn-xs" onclick="repostCompanyStoreThreeTableRecords(\'' . $param1 . '\',\'' . $param2 . '\',\'' . $param3 . '\',\'' . $param4 . '\',\'' . $param5 . '\',\'' . $param8 . '\',\'' . $param9 . '\',\'' . $param10 . '\',\'' . $param11 . '\',\'' . $param12 . '\',\'' . $param13 . '\')"><span class="glyphicon glyphicon-edit"> P</span></button>';
        }
    }

    public static function getDemandQtyByDemandNo($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("demand_data")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where([$param5 => $param4])
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalQty = 0;
        foreach ($data as $row){
            $totalQty += $row->qty;
        }
        return $totalQty;
    }

    public static function getReturnQtyByStoreChallanNo($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("store_challan_return_data")
            ->select(DB::raw("SUM(return_qty) as return_qty"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where([$param5 => $param4])
            ->where('status','=',1)
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalQty = 0;
        foreach ($data as $row){
            $totalQty += $row->return_qty;
        }
        return $totalQty;
    }

    public static function getReturnQtyByDemandNo($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("store_challan_data")
            ->select(DB::raw("store_challan_no"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where([$param5 => $param4])
            ->where('status','=',1)
            ->get();
        $totalQty = 0;
        foreach ($data as $row){
            $dataOne = DB::table("store_challan_return_data")
                ->select(DB::raw("SUM(return_qty) as return_qty"))
                ->where(['category_id' => $param2,'sub_item_id' => $param3])
                ->where(['store_challan_no' => $row->store_challan_no])
                ->where('status','=',1)
                ->groupBy(DB::raw("sub_item_id"))
                ->get();
            foreach ($dataOne as $rowOne){
                $totalQty += $rowOne->return_qty;
            }
        }
        CommonHelper::reconnectMasterDatabase();


        return $totalQty;
    }

    public static function itemWiseCreatedStoreChallan($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
            $data = DB::table('store_challan_data')
                ->select('store_challan_no','store_challan_date','issue_qty')
                ->where(['category_id' => $param3,'sub_item_id' => $param4,'demand_no' => $param2])
                ->where('store_challan_no','!=',$param5)
                ->get();
        CommonHelper::reconnectMasterDatabase();
        foreach ($data as $row){
            echo $row->store_challan_no.'  ---  ';
            echo CommonHelper::changeDateFormat($row->store_challan_date).'  ---  ';
            echo $row->issue_qty;
            echo '<br />';
        }
        if($data->isEmpty()){
            echo 'Not Found!';
        }
    }
    public static function unique_for_wo($year, $month)
    {
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`voucher_no`,7,length(substr(`voucher_no`,3))-3),signed integer)) reg
         from `product_creation` where substr(`voucher_no`,3,2) = " . $year . " and substr(`voucher_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);

        $wo = 'wo' . $year . $month . $str;
        return $wo;
    }

    public static function get_sum_qty($table,$master_id,$qty)
    {
       return DB::Connection('mysql2')->table($table)->where('master_id',$master_id)->sum($qty);
    }
}
?>