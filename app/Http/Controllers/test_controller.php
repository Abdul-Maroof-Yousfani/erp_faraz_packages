<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Http\Requests;
use App\Models\Job;
use App\Models\RequestHiring;
use App\Models\FamilyStatus;

use Input;
use Auth;
use DB;
use Config;
class Test_controller extends Controller
{



    public function index(){
        return view('Visitor.visitorDashboard');
    }

    public function careers(){

        return view('Visitor.careers');
    }

    public function ViewandApplyDetail($companyId,$recordId){

        CommonHelper::companyDatabaseConnection($companyId);
        $hiringRequestDetail = RequestHiring::where([['id','=',$recordId]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Visitor.ViewandApplyDetail',compact('hiringRequestDetail'));

    }

    public function ThankyouForApply()
    {
        if(session('key_message') !='')
        {
            session()->put('key_message','');

            return view('Visitor.ThankyouForApply');
        }
        else
        {
            return view('Visitor.visitorDashboard');
        }
    }



    function addEmployeeFamilyStatusDetail(Request $request)
    {

        $family_status=new FamilyStatus();
        $family_status=$family_status->SetConnection('mysql2');
        $family_status->emp_id=$request->emp_id;
    }




}
