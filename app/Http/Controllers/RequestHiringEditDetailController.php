<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Helpers\FinanceHelper;
use Auth;
use DB;
use Config;
use Session;
use Redirect;
use Helpers;
use Input;

class RequestHiringEditDetailController extends Controller
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

    public function editHiringRequest(){
        

        FinanceHelper::companyDatabaseConnection(Input::get('company_id'));
        $jobTitle = Input::get('job_title');
        $jobTypeId = Input::get('job_type_id');
        $subDepartmentId = Input::get('sub_department_id');
        $designationId = Input::get('designation_id');
        $qualificationId = Input::get('qualification_id');
        $shiftTypeId = Input::get('shift_type_id');
        $gender = Input::get('gender');
        $salaryStart = Input::get('salary_start');
        $salaryEnd = Input::get('salary_end');
        $age = Input::get('age');
        $jobDescription = Input::get('job_description');

        $data1['RequestHiringTitle']        = strip_tags($jobTitle);
        $data1['sub_department_id']         = strip_tags($subDepartmentId);
        $data1['job_type_id']   = strip_tags($jobTypeId);
        $data1['designation_id']        = strip_tags($designationId);
        $data1['qualification_id']  = strip_tags($qualificationId);
        $data1['shift_type_id']     = strip_tags($shiftTypeId);
        $data1['RequestHiringGender']           = strip_tags($gender);
        $data1['RequestHiringSalaryStart']              = strip_tags($salaryStart);
        $data1['RequestHiringSalaryEnd']            = strip_tags($salaryEnd);
        $data1['RequestHiringAge']      = strip_tags($age);
        $data1['RequestHiringDescription']          = strip_tags($jobDescription);
        $data1['RequestHiringStatus']       = 1;
        $data1['username']          = Auth::user()->name;
        $data1['date']              = date("Y-m-d");
        $data1['time']              = date("H:i:s");

        DB::table('requesthiring')->where('id', Input::get('RequestHiringId'))->update($data1);
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('RequestHiring/viewHiringRequestList');
    }

	
    
    
}
