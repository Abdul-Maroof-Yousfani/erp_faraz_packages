<?php

namespace App\Http\Controllers;
use App\Models\ApprovalSystem;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\MainMenuTitle;
use App\Models\MenuPrivileges;
use App\User;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Redirect;

class UsersAddDetailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
    	$this->middleware('auth');
	}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
   	public function addMainMenuTitleDetail(){
		$main_menu_id = Input::get('main_menu_name');
		$title = Input::get('title_name');
		$title_id = preg_replace('/\s+/', '', $title);
		
		$data1['main_menu_id'] =	$main_menu_id;
		$data1['title'] = $title;
		$data1['title_id'] = $title_id;
        $data1['menu_type'] = Input::get('menu_type');
        $data1['date']     		  = date("Y-m-d");
        DB::table('main_menu_title')->insert($data1);
	}
	
	public function addSubMenuDetail(){
		
		$main_navigation_name = Input::get('main_navigation_name');
		$explodeMainNavigation = explode('_',$main_navigation_name);
		$subNavigationTitleName = Input::get('sub_navigation_title_name');
		$subNavigationUrl = Input::get('sub_navigation_url');
        $page_type = Input::get('page_type');
		$mainNavigationName = $explodeMainNavigation[0];
		$mainNavigationTitleId = $explodeMainNavigation[1];
		
		$max_id = DB::selectOne('SELECT max(`id`) as id  FROM `menu` WHERE `m_parent_code` = '.$mainNavigationName.'')->id;
		
		if($max_id == ''){
        	$code = $mainNavigationName.'-1';
		}else{
			$max_code2 = DB::selectOne('SELECT `m_code` FROM `menu` WHERE `m_parent_code` = '.$explodeMainNavigation[0].'')->m_code;
			$max_code2;
			$max_code2;
			$max = explode('-',$max_code2);
        	$code = $mainNavigationName.'-'.(end($max)+1);
		}
		$data1['m_code'] =	$code;
		$data1['m_parent_code'] = $explodeMainNavigation[0];
		$data1['m_type'] = '';
        $data1['m_main_title']     		  = $explodeMainNavigation[1];
		$data1['name'] = $subNavigationTitleName;
		$data1['m_controller_name'] = $subNavigationUrl;
        $data1['page_type'] = $page_type;
        $data1['date']     		  = date("Y-m-d");
        DB::table('menu')->insert($data1);
	}

    function addRoleDetail()
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

            ApprovalSystem::where('emr_no', Input::get('emp_code'))->delete();
            $data1['emp_code']        = Input::get('emp_code');
            $data1['approval_check']= 1;
            $data1['approval_code'] = Hash::make(Input::get('approval_code'));
            $data1['username']      = date("Y-m-d");
            $data1['status']     	= 1;
            $data1['username']     	= Auth::user()->name;
            $data1['date']     		= date("Y-m-d");
            $data1['time']     		= date("H:i:s");
            DB::table('approval_system')->insert($data1);
        else:
            ApprovalSystem::where('emp_code', Input::get('emp_code'))->delete();
            $data1['emp_code']        = Input::get('emp_code');
            $data1['approval_code'] = '';
            $data1['approval_check']= 2;
            $data1['username']      = date("Y-m-d");
            $data1['status']     	= 1;
            $data1['username']     	= Auth::user()->name;
            $data1['date']     		= date("Y-m-d");
            $data1['time']     		= date("H:i:s");
            DB::table('approval_system')->insert($data1);
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

     //   $data['rights']=substr($sub_menu,0,-1);
      //  $data['rights']=substr($crud_rights,0,-1);
      //  DB::table('users')->where('emp_code',Input::get('emp_code'))->update($data);

        return Redirect::to('users/viewRoleList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');


        /*$role_name = Input::get('role_name');
        $role_description = Input::get('role_description');
        $hr_control	= Input::get('ChartOfAccount_checkbox');
        $MainMenuTitles = new MainMenuTitle;
        $MainMenuTitles = $MainMenuTitles->groupBy('main_menu_id')->get();
        $str = DB::selectOne("select max(convert(substr(`role_no`,4,length(substr(`role_no`,4))-4),signed integer)) reg from `roles` where substr(`role_no`,-4,2) = ".date('m')." and substr(`role_no`,-2,2) = ".date('y')."")->reg;
        $role_no = 'rps'.($str+1).date('my');
        $data1['role_no'] = $role_no;
        $data1['name'] = $role_name;
        $data1['description'] = $role_description;
        DB::table('roles')->insert($data1);
        foreach($MainMenuTitles as $row1){
            $MainMenuTitlesSub = new MainMenuTitle;
            $MainMenuTitlesSub = $MainMenuTitlesSub->where('main_menu_id','=',$row1->main_menu_id)->get();
            foreach($MainMenuTitlesSub as $row2){
                $labelControlDetail	= Input::get(''.$row2->title_id.'_checkbox');
                if(!empty($labelControlDetail)){
                    $role_no;
                    $row2->title;
                    echo $row2->title_id;
                    $lableNameId = Input::get(''.$row2->title_id.'_checkbox_id');
                    $data2['role_no'] = $role_no;
                    $data2['menu_id'] = $lableNameId;

                    foreach($labelControlDetail as $row3 => $y){
                        $data2['right_'.strtolower($y).''] = 1;
                    }
                    DB::table('role_detail')->insert($data2);
                    echo "<pre>";
                    print_r($data2);
                    unset($data2);
                }
            }
        }*/
        //return Redirect::to('users/createRoleForm');
    }

    public function createNewUser(Request $request)
    {
        $category = db::Connection("mysql2")->table('category')->where('status' , 1)->get();
        return view('Users.createNewUser', compact('category'));   
    }

    public function userEditForm($id)
    {
        $Users =  DB::table('users')->where('id',$id)->where('status',1)->first();
        $category = db::Connection("mysql2")->table('category')->where('status' , 1)->get();

		return view('Users.userEditForm',compact('Users','category'));

	}

    public function storeNewUser(Request $request)
    {
        // dd($request);
        
        if(empty($request->category))
        {
            $category = 0 ; //implode($request->category , ',');
        }
        else
        {
            $category = implode(',', $request->category);
        }
        if(empty($request->dashboard_access))
        {
            $dashboard_access = 0 ; //implode($request->category , ',');
        }
        else
        {
            $dashboard_access = implode(',', $request->dashboard_access);
        }

        $validated = $request->validate([
            'name' => 'required',
            'acc_type' => 'required',
            'email' => 'email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'acc_type' => $request->acc_type,
            'email' => $request->email,
            'password' => $request->password,  
            'categories_id' => $category,
            'dashboard_access' => $dashboard_access,
            'company_id' => 1,            
            'emp_id' => 1,            
            'emp_code' => User::UniqueNo(),            
        ]);

        return redirect()->back();
        
    }

    public function editUser(Request $request)
    {
        // dd($request);

       
        
        if(empty($request->category))
        {
            $category = 0 ; //implode($request->category , ',');
        }
        else
        {
            $category = implode(',', $request->category);
        }
        if(empty($request->dashboard_access))
        {
            $dashboard_access = 0 ; //implode($request->category , ',');
        }
        else
        {
            $dashboard_access = implode(',', $request->dashboard_access);
        }

       
        $validated = $request->validate([
            'name' => 'required',
            'acc_type' => 'required',
        ]);
        
        $user = User::find($request->id);
        
        if ($user) {
            $user->update([
                'name' => $request->name,
                'acc_type' => $request->acc_type,
                'categories_id' => $category,
                'dashboard_access' => $dashboard_access,
                'company_id' => 1,
                'emp_id' => 1,
            ]);
        }


        $user = User::find($request->id);
        
        return redirect()->back();
        
    }
}
