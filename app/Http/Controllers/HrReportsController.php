<?php

namespace App\Http\Controllers;

use App\Helpers\HrHelper;
use App\Http\Requests;
use App\Models\Gratuity;
use App\Models\JobType;
use Auth;
use DB;
use Config;
use Input;

use App\Helpers\CommonHelper;
use App\Models\User;

use App\Models\Employee;
use App\Models\EmployeeMedical;
use App\Models\HrWarningLetter;
use App\Models\EmployeeExit;
use App\Models\EmployeePromotion;
use App\Models\EmployeeTransfer;
use App\Models\Regions;
use App\Models\EmployeeCategory;
use App\Models\Trainings;
use App\Models\EmployeeProjects;







class HrReportsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewTurnoverReportForm()
    {
        return view('Hr.AjaxPages.viewTurnoverReportForm');
    }

    public function viewOnboardReportForm()
    {
        return view('Hr.AjaxPages.viewOnboardReportForm');
    }

    public function viewIncrementReportForm()
    {
        return view('Hr.AjaxPages.viewIncrementReportForm');
    }

    public function viewWarningReportForm()
    {
        return view('Hr.AjaxPages.viewWarningReportForm');
    }

    public function viewEmployeeReportForm()
    {
        $regionsRights =  CommonHelper::regionRights(Input::get('m'));
        $regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regionsRights)->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $job_type = JobType::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewEmployeeReportForm', compact('employee_category', 'regions','job_type','Employee_projects'));
    }

    public function viewTransferReportForm()
    {

        $regionsRights =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regionsRights)->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewTransferReportForm',compact('employee_category', 'regions','Employee_projects'));
    }

    public function viewTrainingReportForm()
    {
        $regionsRights =  CommonHelper::regionRights(Input::get('m'));
        $regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regionsRights)->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $Employeeprojects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewTrainingReportForm',compact('employee_category', 'regions','Employeeprojects'));
    }

    public function viewMedicalReportForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status','=', 1]])->select('id', 'emr_no', 'emp_name')->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employeeprojects = Employeeprojects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.AjaxPages.viewMedicalReportForm', compact('employees','Employeeprojects','employee_regions','employee_category'));
    }

    public function viewTurnoverReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_exit = EmployeeExit::where([['status','=', 1 ],['approval_status', '=', 2]])->orderBy('emr_no','asc');
        else:
            $employee_exit = EmployeeExit::where([['status','=', 1 ],['approval_status', '=', 2]])->whereBetween('last_working_date', [$from, $to])->orderBy('last_working_date','asc');;
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTurnoverReport', compact('employee_exit'));
    }

    public function viewOnboardReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_detail = Employee::where([['status', '=', 1]])->select('id','emr_no','emp_name','emp_father_name', 'employee_category_id', 'emp_joining_date',
                'region_id', 'designation_id', 'emp_cnic','branch_id')->orderBy('emr_no');
        else:
            $employee_detail = Employee::whereBetween('emp_joining_date', [$from, $to])->select('id','emr_no','emp_name','emp_father_name', 'employee_category_id', 'emp_joining_date',
                'region_id', 'designation_id', 'emp_cnic','branch_id')->orderBy('emp_joining_date');;
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewOnboardReport', compact('employee_detail'));
    }

    public function viewEmployeeReport()
    {


        $show_all = Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if($show_all == 1):
            $employee_detail = Employee::select('id','emp_employementstatus_id','status','emr_no','emp_name','emp_father_name', 'employee_category_id', 'emp_date_of_birth',
                'region_id', 'designation_id','emp_joining_date', 'emp_cnic','branch_id', 'emp_contact_no', 'emergency_no', 'emp_joining_salary',  'emp_salary','employee_project_id')
            ->where([['status','=',1]]);
        else:
            $employee_detail = Employee::where([['status','=',1]])->select('id','emp_employementstatus_id','status','emr_no','emp_name','emp_father_name', 'employee_category_id', 'emp_date_of_birth',
                'region_id', 'designation_id', 'emp_joining_date','emp_cnic','branch_id', 'emp_contact_no', 'emergency_no', 'emp_joining_salary',  'emp_salary','employee_project_id');

            if (Input::get('employee_category_id')) {
                $employee_detail->where('employee_category_id', Input::get('employee_category_id'));
            }
            if (Input::get('region_id')) {
                $employee_detail->where('region_id', Input::get('region_id'));
            }
            if (Input::get('emp_gender')) {
                $employee_detail->where('emp_gender', Input::get('emp_gender'));
            }
            if (Input::get('job_type_id')) {
                $employee_detail->where('emp_employementstatus_id', Input::get('job_type_id'));
            }
            if(Input::get('employee_project_id')){
                $employee_detail->where('employee_project_id', Input::get('employee_project_id'));
            }

        endif;
        $employee_detail->orderBy('emr_no');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeReport', compact('employee_detail'));
    }

    public function viewWarningReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $warning_letter = HrWarningLetter::where([['status', '=', 1]])->orderBy('emr_no', 'asc');
        else:
            $warning_letter = HrWarningLetter::whereBetween('date', [$from, $to])->orderBy('date', 'asc');
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewWarningReport', compact('warning_letter'));
    }

    public function viewTransferReport()
    {
        $employee_category_id = Input::get('employee_category_id');
        $employee_project_id = Input::get('employee_project_id');
        $region_id = Input::get('region_id');
        $show_all = Input::get('show_all');
        $from_date =Input::get('from_date');
        $to_date =Input::get('to_date');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_transfer = EmployeeTransfer::where([['status', '=', 1],['approval_status', '=', 2]])->orderBy('emr_no', 'asc')->get();
            $employee_project_id = '0';
        else:
            $employee_transfer = EmployeeTransfer::where([['emp_category_id','=',$employee_category_id],['emp_region_id','=',$region_id],['approval_status', '=', 2]])
                ->whereBetween('date',[$from_date,$to_date])
                ->orderBy('emr_no', 'asc')
                ->get();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTransferReport', compact('employee_transfer','employee_project_id'));
    }


    public function viewIncrementReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_promotion = EmployeePromotion::where([['status', '=', 1]])->orderBy('emr_no', 'asc');
        else:
            $employee_promotion = EmployeePromotion::whereBetween('promotion_date', [$from, $to])->orderBy('promotion_date', 'asc');
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewIncrementReport', compact('employee_promotion'));
    }

    public function viewMedicalReport()
    {
        $emr_no = Input::get('emr_no');
        $show_all = Input::get('show_all');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $employee_project_id = Input::get('employee_project_id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_medical = EmployeeMedical::where('status','=',1)->orderBy('emr_no', 'asc')
                                                ->orderBy('disease_date', 'asc');
        else:
            $employee_medical = EmployeeMedical::where('status','=',1)
                                                ->whereBetween('disease_date',[$from_date,$to_date])
                                                ->orderBy('disease_date', 'asc');
        endif;
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewMedicalReport', compact('employee_medical','employee_project_id'));
    }

    public function viewTrainingReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $trainingsData =Trainings::where('status','=',1)->orderBy('training_date', 'asc');
        else:
            $trainingsData = Trainings::where([['employee_category_id','=',Input::get('employee_category_id')],['region_id','=',Input::get('region_id')]])
                                                ->whereBetween('training_date',[$from_date,$to_date])
                                                ->orderBy('training_date', 'asc');
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTrainingReport', compact('trainingsData'));

    }

    public function viewEmployeeExpReportForm()
    {
        $regions = Regions::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $Employeeprojects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewEmployeeExpReportForm', compact('employee_category', 'regions','Employeeprojects'));

    }

    public function viewGratuityReportForm()
    {
        $employee_regions  = Regions::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $Employeeprojects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewGratuityReportForm', compact('employee_category', 'employee_regions','Employeeprojects'));
    }


    public function viewGratuityReport()
    {
        $employee_project_id = Input::get('employee_project_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $emr_no = Input::get('emr_no');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
            if(Input::get('show_all') == '1'):
                $gratuityDetails =Gratuity::select('*');
            else:
                $gratuityDetails =Gratuity::select('*')->where([['employee_category_id','=',Input::get('employee_category_id')],['region_id','=',Input::get('region_id')]])
                                                        ->whereBetween('date',[$from_date,$to_date]);
            endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewGratuityReport', compact('gratuityDetails','employee_project_id'));

    }

    public function viewEmployeeExpReport()
    {
        $show_all = Input::get('show_all');
        $employee_project_id = Input::get('employee_project_id');
        $emr_no = Input::get('emr_no');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $get_all_emr_no = HrHelper::getAllEmployeeId(Input::get('m'),Input::get('employee_category_id'),Input::get('region_id'),$employee_project_id);
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if($show_all == 1):
            $employee_detail = Employee::select('id','status','emp_joining_date','emr_no','emp_name', 'employee_category_id',
                'region_id', 'designation_id' ,'branch_id','employee_project_id')
                ->orderBy('emr_no');
        elseif($emr_no == 'All' && $employee_project_id == '0'):
            $employee_detail = Employee::select('id','status','emp_joining_date','emr_no','emp_name', 'employee_category_id',
                'region_id', 'designation_id' ,'branch_id','employee_project_id')
                ->where([['employee_category_id', '=', Input::get('employee_category_id')],['region_id','=',Input::get('region_id')]])
                ->whereIn('emr_no',$get_all_emr_no)
                ->whereBetween('emp_joining_date',[$from_date,$to_date])
                ->orderBy('emr_no');
        elseif($emr_no == 'All' && $employee_project_id != '0'):
            $employee_detail = Employee::select('id','status','emp_joining_date','emr_no','emp_name', 'employee_category_id',
                'region_id', 'designation_id' ,'branch_id','employee_project_id')
                ->where([['employee_category_id', '=', Input::get('employee_category_id')],['region_id','=',Input::get('region_id')],['employee_project_id','=',$employee_project_id]])
                ->whereIn('emr_no',$get_all_emr_no)
                ->whereBetween('emp_joining_date',[$from_date,$to_date])
                ->orderBy('emr_no');
        elseif($emr_no != 'All' && $employee_project_id != '0'):
            $employee_detail = Employee::where([['employee_category_id','=',Input::get('employee_category_id')],['region_id','=',Input::get('region_id')],['employee_project_id','=',$employee_project_id]])
                ->whereBetween('emp_joining_date',[$from_date,$to_date])
                ->where('emr_no','=',$emr_no)
                ->select('id','status','emp_joining_date','emr_no','emp_name', 'employee_category_id',
                    'region_id', 'designation_id' ,'branch_id','employee_project_id')->orderBy('emr_no');
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeExpReport', compact('employee_detail'));
    }
}