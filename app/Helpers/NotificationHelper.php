<?php
namespace App\Helpers;
use DB;
use Auth;
use Request;
use Config;
use Input;
use App\Mail\purchase_request;
use Mail;
use Illuminate\Support\Facades\Storage;
use Session;

class NotificationHelper
{

    public static function get_all_steps()
    {
       return DB::Connection('mysql2')->table('steps')->where('status',1)->get();
    }

    public static function get_behvaior($id)
    {
        return DB::Connection('mysql2')->table('behavior')->where('status',1)->where('step_id',$id)->get();
    }

    public static function get_notification_data(array $request)
    {
    
        return DB::Connection('mysql2')->table('notifications')
        ->where('step_id',$request['steps'])
        ->where('behavior_id',$request['behavior'])
        ->where('dept_id',$request['dept'])
        ->where('voucher_type',$request['v_type'])
        ->get();
    }

    public static  function notify_list()
    {
       return DB::Connection('mysql2')->table('notifications as a')
        ->join('steps as b','a.step_id','=','b.id')
        ->join('behavior as c','c.id','=','a.behavior_id')
        ->join('voucher_type as d','d.id','=','a.voucher_type')
        ->select('b.name as step_name','c.name as b_name','a.*','d.name as v_name');
        
    }

    public static function get_email_data($step_name ,$behvaior_name,$dept_id,$v_type)
    {
      return  static::notify_list()
      ->where('b.name',$step_name)
      ->where('c.name',$behvaior_name)
      ->where('a.dept_id',$dept_id)
      ->where('a.voucher_type',$v_type);
    }
   
    public static function get_dept_id($table,$column,$voucher_no)
    {
     return   $dept_id = DB::Connection('mysql2')->table($table)
     ->where($column,$voucher_no)->where('status',1);
    }

    public static function get_all_type()
    {
     return   $type = DB::Connection('mysql2')->table('voucher_type')->where('status',1)->get();
    }

    public static function get_type_name($id)
    {
       $type = DB::Connection('mysql2')->table('voucher_type')->where('status',1)->where('id',$id)->select('name')->first();
       return $type->name;
    }
    public static function send_email($step_name , $behvaior_name ,$dept_id,$voucher_no,$subject,$v_type = 3)
    {
          //  echo $step_name.' '.$behvaior_name.' '.$subject;
            $email_data= NotificationHelper::get_email_data($step_name,$behvaior_name,$dept_id,$v_type);
            // dd($email_data->count(), $step_name,$behvaior_name,$dept_id,$v_type);
      //   print_r($email_data->first());
            if ($email_data->count()>0):
               
            $email_data =$email_data->first();    
           
            $emails =  array($email_data->email_1 , $email_data->email_2,$email_data->email_3);
            $body =  array($email_data->body_1 , $email_data->body_2,$email_data->body_3);
         
            $data = [];
            for ($i=0; $i<=2; $i++):
            $data['body'] = $body[$i];
            $data['pr_no'] = strtoupper($voucher_no);
            $data['subject'] = $subject;
            
            if (isset($emails[$i]) && $emails[$i]!=''):
            Mail::to($emails[$i])->send(new purchase_request($data));
            endif;

            endfor;
        endif;
    }
}
