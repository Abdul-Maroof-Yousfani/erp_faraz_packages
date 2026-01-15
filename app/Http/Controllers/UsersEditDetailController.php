<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\MainMenuTitle;
use App\Models\MenuPrivileges;
use App\Models\ApprovalSystem;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Hash;
use Session;
class UsersEditDetailController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function editUserPasswordDetail()
    {
        $data['password'] = Hash::make(Input::get('confirm_password'));
        $data['created_at'] = date('Y-m-d');
        $data['updated_at'] = date('Y-m-d');
        $data['identity'] = Input::get('confirm_password');


        DB::table('users')->where('id', Auth::user()->id)->update($data);
        Auth::logout();
        return redirect('/');
    }

    function editUserRoleDetail()
    {
        $main_modules ='';
        $menu ='';
        $sub_menu ='';
        $crud_rights = '';
        $regions = '';
        $departments = '';
        if(!empty(Input::get('employee_regions'))):
            foreach (Input::get('employee_regions') as $regionValue):
                $regions.=$regionValue.",";
            endforeach;
        endif;
        if(!empty(Input::get('department_permission'))):
            foreach (Input::get('department_permission') as $department):
                $departments.=$department.",";
            endforeach;
        endif;

        if(Input::get('approval_code_check') == 1):


            if(Input::get('approval_code') == '')
            {
                $data1['approval_code'] = Input::get('hiddenCode');
            }
            else
            {
                $data1['approval_code'] = Hash::make(Input::get('approval_code'));
            }
            $data1['approval_check']= 1;
            $data1['emp_code']        = Input::get('emp_code');
            $data1['username']      = date("Y-m-d");
            $data1['status']     	= 1;
            $data1['username']     	= Auth::user()->name;
            $data1['date']     		= date("Y-m-d");
            $data1['time']     		= date("H:i:s");

            DB::table('approval_system')->where([['emp_code','=',Input::get('emp_code')]])->update($data1);

        else:


            $data1['emp_code']        = Input::get('emp_code');
            $data1['approval_code'] = '';
            $data1['approval_check']= 2;
            $data1['username']      = date("Y-m-d");
            $data1['status']     	= 1;
            $data1['username']     	= Auth::user()->name;
            $data1['date']     		= date("Y-m-d");
            $data1['time']     		= date("H:i:s");
            DB::table('approval_system')->where([['emp_code','=',Input::get('emp_code')]])->update($data1);

        endif;

        foreach (Input::get('main_modules') as $moduleId):

            $main_modules.=$moduleId.",";
            foreach (Input::get('menu_title_'.$moduleId) as $title):
                $menu.=$title.",";
                if(Input::get('sub_menu_'.$title)):
                    foreach (Input::get('sub_menu_'.$title) as $submenu):
                        $sub_menu.= $submenu.",";
                    endforeach;
                endif;

                if(Input::get('crud_rights_'.$title)):
                    foreach (Input::get('crud_rights_'.$title) as $crudValue):
                        $crud_rights.= $crudValue."_".$title.",";
                    endforeach;
                endif;
            endforeach;
        endforeach;
        MenuPrivileges::where('emp_code', Input::get('emp_code'))->delete();
        $MenuPrivileges = new MenuPrivileges();

        $MenuPrivileges->emp_code = Input::get('emp_code');
        $MenuPrivileges->user_id = Input::get('emp_code');
        $MenuPrivileges->main_modules = substr($main_modules,0,-1);
        $MenuPrivileges->menu_titles = substr($menu,0,-1);
        $MenuPrivileges->submenu_id = substr($sub_menu,0,-1);
        $MenuPrivileges->crud_rights = substr($crud_rights,0,-1);
        $MenuPrivileges->company_list = Input::get('companyList');
        $MenuPrivileges->region_id = Input::get('region_id');
        $MenuPrivileges->regions_permission = substr($regions,0,-1);
        $MenuPrivileges->department_permission = substr($departments,0,-1);
        $MenuPrivileges->status = 1;
        $MenuPrivileges->username = Auth::user()->name;
        $MenuPrivileges->save();
        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('users/viewRoleList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function editApprovalCodeDetail()
    {
        $data1['approval_code'] = Hash::make(Input::get('approval_code'));
        $data1['approval_check']= 1;
        $data1['status']     	= 1;
        $data1['username']     	= Auth::user()->name;
        $data1['date']     		= date("Y-m-d");
        $data1['time']     		= date("H:i:s");
       // DB::table('approval_system')->where([['emp_code','=',Input::get('emp_code')]])->update($data1);
        ApprovalSystem::updateOrCreate(['emp_code' => Input::get('emp_code')],$data1);
        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('users/editUserProfile');
    }


}
