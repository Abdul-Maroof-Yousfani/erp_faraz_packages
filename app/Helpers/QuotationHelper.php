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
class QuotationHelper
{
  
    public static function get_quotation_amount_supp_wise($demand_data_id,$supplier)
    {
      
         return  DB::Connection('mysql2')->table('quotation as a')
                ->join('quotation_data as b','a.id','=','b.master_id')
                ->where('a.vendor_id',$supplier)
                ->where('b.pr_data_id',$demand_data_id)
                ->select('amount')
                ->value('amount');
    }


    public static function check_quotation_status($type)
    {
        
        $status = 'Pending';

        if ($type==1):
          $status = 'Approved';  
        endif;
        return $status;
    }

    public static function quotationStatus($status)
    {
        if ($status == 1):
          $status = 'Pending';
        elseif($status == 2): 
          $status = 'Approved';
        endif;
        return $status;
    }


}
?>