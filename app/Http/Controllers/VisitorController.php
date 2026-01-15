<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Http\Requests;
use App\Models\Job;
use App\Models\EmployeeRequisition;
use App\Models\Employee;
use App\Models\QuizTest;

use Input;
use Auth;
use DB;
use Config;
class VisitorController extends Controller
{



    public function index(){
       
        return view('Visitor.visitorDashboard');
    }

    public function careers(){

        return view('Visitor.careers');
    }

    public function ViewandApplyDetail($companyId,$recordId){

        CommonHelper::companyDatabaseConnection($companyId);
        $EmployeeRequisitionDetail = EmployeeRequisition::where([['id','=',$recordId]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Visitor.ViewandApplyDetail',compact('EmployeeRequisitionDetail','companyId'));

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


    public function quizTest()
    {


        return view('Visitor.quizTest', compact(''));
    }

    public function viewQuizTestResult($id)
    {
        $employe=new Employee();
        $employe=$employe->SetConnection('mysql2');
        $employe=$employe->where('status',1)->where('id',$id)->first(['emp_email','emp_name','emp_father_name','emp_contact_no','emp_date_of_birth']);
        $quiztest=new QuizTest();
        $quiztest=$quiztest->SetConnection('mysql2');
        $quiztest=$quiztest->where('status',1)->where('emp_id',$id)->first(['emp_answer']);
        $correct_ansers= DB::connection('mysql2')->select('select right_answer from correctc_answer')[0];
        return view('Visitor.viewQuizTestResult', compact('employe','quiztest','correct_ansers'));
       //  return view('Visitor.ThankyouForApply', compact(''));
    }

    public function viewEmployeeIQTestList(){

        $quiztest=new QuizTest();
        $quiztest=$quiztest->SetConnection('mysql2');
        $quiztest=$quiztest->where('status',1)->where('status',1)->get(['emp_id']);
        return view('Visitor.viewEmployeeIQTestList',compact('quiztest'));

    }
}
