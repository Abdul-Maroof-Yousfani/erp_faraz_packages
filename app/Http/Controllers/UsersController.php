<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\MainMenuTitle;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\MenuPrivileges;
use App\Models\Employee;
use App\Helpers\CommonHelper;
use App\Models\ApprovalSystem;
use App\Models\Regions;
use App\Models\EmployeeCategory;

use Input;
use Auth;
use DB;
use Config;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('dUser.home');
    }
   
   	public function toDayActivity()
    {
		return view('Users.toDayActivity');
   	}
	
	public function createUsersForm()
    {
		return view('Users.createUsersForm');
	}
	
	public function createMainMenuTitleForm()
    {
		return view('Users.createMainMenuTitleForm');
	}
	
	public function createSubMenuForm()
    {
		$MainMenuTitles = new MainMenuTitle;
		$MainMenuTitles = $MainMenuTitles::where('status', '=', '1')->get();
		return view('Users.createSubMenuForm',compact('MainMenuTitles'));
	}

    public function createRoleForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $regions_list = Regions::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        return view('Users.createRoleForm',compact('regions_list','departments','employee_regions'));
    }

    public function viewRoleList()
    {

      $MenuPrivilegesQuery = "SELECT t1.* FROM menu_privileges t1";

        $MenuPrivileges = DB::select(DB::raw($MenuPrivilegesQuery));
        return view('Users.viewRoleList',compact('MenuPrivileges'));
    }

    public function viewEmployeePrivileges($emp_code)
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $regions_list = Regions::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $ApprovalSystem = ApprovalSystem::where([['emp_code','=',$emp_code]]);
        $MenuPrivileges = MenuPrivileges::where([['emp_code','=',$emp_code]])->get()->toArray();
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::select('emp_name','emp_code','region_id')->where('emp_code','=',$emp_code)->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Users.viewEmployeePrivileges',['employee_regions'=>$employee_regions,'departments' => $departments,
            'regions_list'=>$regions_list,'ApprovalSystem'=>$ApprovalSystem,'MenuPrivileges'=>$MenuPrivileges,'employees'=>$employees]);
    }

    public function editUserProfile()
    {
        return view('Users.editUserProfile');
    }

}
