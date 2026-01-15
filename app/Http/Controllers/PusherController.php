<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

use Pusher\Pusher;
 
class PusherController extends Controller
{
 
    public function sendNotification()
    {
        //Remember to change this with your cluster name.
        $options = array(
            'cluster' => 'ap2', 
            'encrypted' => true
        );
 
       //Remember to set your credentials below.
        $pusher = new Pusher(
            'key',
            'secret',
            'app_id',
            $options
        );
        
        $message= "Hello User";
        
        //Send a message to notify channel with an event name of notify-event
        $pusher->trigger('notify', 'notify-event', $message);  
    }
    public function edit_sub_ca(Request $request)
    {
       $data= DB::Connection('mysql2')->table('sub_category')->where('id',$request->id)->select('sub_category_name','category_id')->first();
        $id=$request->id;
        return view('Purchase.AjaxPages.edit_sub_ca',compact('data','id'));
    }




    public function item_master_list(Request $request)
    {
        $data= DB::Connection('mysql2')->table('item_master')->where('category_id',$request->category)->get();
        $id=$request->category_id;
        return view('Purchase.AjaxPages.item_master_list',compact('data','id'));
    }
}