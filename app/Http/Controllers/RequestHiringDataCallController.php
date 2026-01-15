<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use Input;
use App\Helpers\FinanceHelper;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Payslip;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Designation;
use App\Models\RequestHiring;
use App\Models\Qualification;
use App\Models\ShiftType;
use App\Models\JobType;
class RequestHiringDataCallController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	   
	public function ViewandApplyDetail()
    {
        
        FinanceHelper::companyDatabaseConnection(Input::get('m'));
      	$requestHiring = DB::selectOne('select * from `requesthiring` where
		id = '.Input::get('id').'');
		FinanceHelper::reconnectMasterDatabase();
        $m = Input::get('m');
      	return view('RequestHiring.ajax_pages.ViewandApplyDetail',compact('requestHiring','m'));	
    }


    public function editRequestHiringForm()
    {

       $departments = new Department;
       $JobTypes = new JobType;
       $Designations = new Designation;
       $Qualifications = new Qualification;
       $ShiftTypes = new ShiftType;
       
       $departments = $departments::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
       $JobTypes = $JobTypes::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
       $Designations = $Designations::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
       $Qualifications = $Qualifications::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
       $ShiftTypes = $ShiftTypes::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
       return view('RequestHiring.editHiringRequestForm',compact('departments','JobTypes','Designations','Qualifications','ShiftTypes'));
   
        
    }
   
	
}
?>