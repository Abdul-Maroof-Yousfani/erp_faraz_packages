<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Http\Requests;
use App\Models\EmployeeRequisitionCandidates;
use Illuminate\Support\Facades\Storage;
use Cookie;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Redirect;

class VisitorAddDetailController extends Controller
{

    public function addVisitorApplyDetail(Request $request)
    {

        $file_name = $request->email.'_'.$request->contact_no.'.'.$request->file('cv')->extension();
        $path = $request->file('cv')->storeAs('uploads/candidates_cvs',$file_name);

        CommonHelper::companyDatabaseConnection(Input::get("companyId"));
        $RequestHiringCandidates = new EmployeeRequisitionCandidates;
        $RequestHiringCandidates->employee_requisition_id = $request->recordId;
        $RequestHiringCandidates->email = $request->email;
        $RequestHiringCandidates->contact_no = $request->contact_no;
        $RequestHiringCandidates->expected_salary = $request->expected_salary;
        $RequestHiringCandidates->cv_path = "app/".$path;
        $RequestHiringCandidates->status = 1;
        $RequestHiringCandidates->date = date("Y-m-d");
        $RequestHiringCandidates->time = date("H:i:s");
        $RequestHiringCandidates->save();
        CommonHelper::reconnectMasterDatabase();

        session()->put('key_message',csrf_token());
        return Redirect::to('visitor/ThankyouForApply');


    }

}
