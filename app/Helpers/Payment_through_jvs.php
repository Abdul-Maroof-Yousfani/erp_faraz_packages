<?php
namespace App\Helpers;
use DB;
use Auth;
use Request;
use Config;

use App\Models\Jvs;
use App\Models\BreakupData;

use Input;

class Payment_through_jvs
{

    public static function get_supplier_id_from_purchase_voucher($id)
    {


     //   $jvs = new Jvs();
    //    $jvs=$jvs->SetConnection('mysql2');
       // $jvs = $jvs->where('status', 1)->where('unique_refrence_no', $id)->select('supplier_id')->first();

        $jvs=  DB::Connection('mysql2')->table('breakup_data')->where('main_id',$id)->select('supplier_id')->first();

        return $jvs->supplier_id;
    }

    public static function get_purchase_net_amount($val)
    {

            $total_net_amount = DB::connection('mysql2')->selectOne("select sum(total_net_amount)amount from jvs
          where status=1 and id IN (' . $val . ') ")->amount;

        return $total_net_amount;
    }

    public static function get_purchase_detail($id)
    {


      //      $purchase_voucher = DB::connection('mysql2')->select('select id,total_net_amount,slip_no,through_advance,payment_id from jvs
     //   where status=1 and id IN (' . $id . ')');


        $purchase_voucher = DB::connection('mysql2')->select('select main_id,amount,slip_no,pv_id as payment_id,jv_id from breakup_data
       where status=1 and main_id IN (' . $id . ') group by main_id');

        //  $purchase_voucher=$purchase_voucher->whereIn('id',array($id))->select('total_net_amount','slip_no')->get();
        return $purchase_voucher;
    }


    public static function data_main_id_wise($main_id,$supplier_id)
    {
        $breakup=new BreakupData();
        $breakup=$breakup->SetConnection('mysql2');
      return  $breakup=$breakup->where('main_id',$main_id)->where('supplier_id',$supplier_id)
          ->where('status',1)->get();
    }
    public static function check_supplier_on_refrence($supplier_id,$main_id)
    {
        $value=0;
        $breakup=new BreakupData();
        $breakup=$breakup->SetConnection('mysql2');
        $breakup=$breakup->where('supplier_id',$supplier_id)->where('main_id',$main_id)->where('refrence_nature',"!=",0)->count();
        $value=$breakup;

        if ($value==0):


            $breakup=new BreakupData();
            $breakup=$breakup->SetConnection('mysql2');
            $breakup=$breakup->where('main_id',$main_id)->select('refrence_nature')->where('refrence_nature',"!=",0)->first();
            $value=$value.'*'.$breakup->refrence_nature;


            endif;
        return $value;
    }

 public static   function voucher_type($type)
    {
       
        if ($type==1):
            $type='Journal';
            endif;
        if ($type==2):
            $type='Payment';
        endif;

        if ($type==3):
            $type='Receipt';
        endif;
        return $type;
    }

    public static function get_remaining_amount($main_id,$supplier_id)
    {
         $debit = DB::Connection('mysql2')->table("breakup_data")->where('main_id',$main_id)->where('debit_credit',1)->where('supplier_id',$supplier_id)->groupBy('supplier_id')->sum('amount');
        $credit = DB::Connection('mysql2')->table("breakup_data")->where('main_id',$main_id)->where('debit_credit',0)->where('supplier_id',$supplier_id)->groupBy('supplier_id')->sum('amount');
        return $debit-$credit;
//        $debit=  DB::Connection('mysql2')->sele('select sum(amount)amount from breakup_data where main_id="'.$main_id.'"  and debit_credit=1 group by supplier_id');
//        if ($debit->count()>0):
//           $debit= $debit->amount;
//            endif;
//
//        return $debit;
//        $credit=  DB::Connection('mysql2')->selectOne('select sum(amount)amount from breakup_data where main_id="'.$row->main_id.'"  and debit_credit=0 group by supplier_id');
//        $amount=$debit-$credit;
    }
}
?>