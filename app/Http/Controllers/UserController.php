<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Models\EmailNotify;
use App\Models\MainMenuTitle;
use App\Models\Menu;
use DB;
use Cache;
use Auth;
class UserController extends Controller
{

    /**
     * Show user online status.
     *
     */
    public function check_status()
    {
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            if (Cache::has('user-is-online-' . $user->id)):
                echo "User " . $user->name . " is online.";
                echo '</br>';
            endif;


        }
    }

    // AlertController.php mein add karo
public function getRecentNotifications()
{
    $notifications = DB::table('alerts')
        ->where('user_id', Auth::user()->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    $html = '';
    if ($notifications->count() > 0) {
        foreach ($notifications as $n) {
            $html .= '<li style="padding:12px 15px; border-bottom:1px solid #f1f1f1;">
                        <p style="margin:0; font-size:13px; font-weight:500;">'.e($n->title ?? $n->message).'</p>
                        <small style="color:#999;">'.\Carbon\Carbon::parse($n->created_at)->diffForHumans().'</small>
                      </li>';
        }
    } else {
        $html = '<li style="padding:15px; text-align:center; color:#999;">No notifications</li>';
    }

    return $html;
}

public function getNotificationCount()
{
    $count = DB::table('alerts')
        ->where('user_id', Auth::user()->id)
        ->where('is_read', 0) // agar tumhare table mein read/unread column ka naam alag hai to yahan badlo
        ->count();

    return response()->json(['count' => $count]);
}
    

    public function add_notifications()
    {
        return view('Users.add_notifications');
    }

    public function get_behavior(Request $request)
    {

        $steps =   $request->steps;
        $behavior = NotificationHelper::get_behvaior($steps);?>
        <option value="">Select</option>
        <?php   foreach($behavior as $row):?>
        <option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
       <?php endforeach;

    }

    public function get_notification_data(Request $request)
    {

        $notifications_data = NotificationHelper::get_notification_data($request->all());
        return view('Users.notifications_data',compact('notifications_data'));
    }


    public function insert_notifications(Request $request)
    {
       $id= $request->id;
       $notification = new EmailNotify();

       if ($id!=0):
        $notification = $notification->find($id);
       endif;
       $notification->step_id = $request->step_id;
       $notification->behavior_id = $request->behavior;
       $notification->voucher_type = $request->v_type;
       $notification->dept_id = $request->dept;;
       $notification->email_1 = $request->email_1;;
       $notification->email_2 = $request->email_2;;
       $notification->body_1 = $request->body_1;;
       $notification->body_2 = $request->body_2;;
       $notification->email_3 = $request->email_3;;
       $notification->body_3 = $request->body_3;;
       $notification->save();

       return redirect('users/notifications_list');


    }

    public function notifications_list()
    {
        $notifications_data = NotificationHelper::notify_list();
        return view('Users.notifications_list',compact('notifications_data'));
    }

    public function menu_delete($id)
    {

        MainMenuTitle::find($id)->update(['status'=>0]);
        return back();
    }

    public function submenu_delete($id)
    {

        Menu::find($id)->update(['status'=>0]);
        return back();
    }
}
