<?php
namespace App\Http\Controllers;


use App\Http\Requests;
use App\Helpers\CommonHelper;
use App\Models\Arrears;
use App\Models\Attendance;
use App\Models\Cities;
use App\Models\Deduction;
use App\Models\Diseases;
use App\Models\EmployeeGsspDocuments;
use App\Models\FinalSettlement;
use App\Models\States;
use Hamcrest\Core\AllOf;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Attendence;
use App\Models\Designation;
use App\Models\HealthInsurance;
use App\Models\EmployeeCategory;
use App\Models\JobType;
use App\Models\Countries;
use App\Models\Institute;
use App\Models\Qualification;
use App\Models\LeaveType;
use App\Models\LoanType;
use App\Models\AdvanceType;
use App\Models\ShiftType;
use App\Models\MaritalStatus;
use App\Models\RequestHiring;
use App\Models\Job;
use App\Models\AdvanceSalary;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\VehicleType;
use App\Models\CarPolicy;
use App\Models\LoanRequest;
use App\Models\Eobi;
use App\Models\Tax;
use App\Models\Bonus;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
use App\Models\DegreeType;
use App\Models\WorkingHoursPolicy;
use App\Models\Holidays;
use App\Models\EmployeeDeposit;
use App\Models\Regions;
use App\Models\Locations;
use App\Models\Grades;
use App\Models\EmployeeExit;
use App\Models\EmployeeFamilyData;
use App\Models\EmployeeBankData;
use App\Models\EmployeeEducationalData;
use App\Models\EmployeeLanguageProficiency;
use App\Models\EmployeeHealthData;
use App\Models\EmployeeActivityData;
use App\Models\EmployeeWorkExperience;
use App\Models\EmployeeReferenceData;
use App\Models\EmployeeKinsData;
use App\Models\EmployeeRelativesData;
use App\Models\EmployeeOtherDetails;
use App\Models\EmployeeCardRequest;
use App\Models\EmployeeProjects;
use App\Models\EmployeeDocuments;
use App\Models\EmployeePromotion;
use App\Models\EmployeeFuelData;
use App\Models\EmployeeEquipments;
use App\Models\Equipments;
use App\Models\EmployeeMedical;
use App\Models\EmployeeTransfer;
use App\Models\LetterFiles;
use App\Models\Trainings;
use App\Models\Gratuity;
use App\Models\TrainingCertificate;
use App\Models\TransferEmployeeProject;
use App\Models\Buildings;
use App\Models\AllowanceType;

use Input;
use Auth;
use DB;
use Config;
use Session;

use Illuminate\Pagination\LengthAwarePaginator;
class HrController extends Controller
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

    public function toDayActivity(){
        return view('Hr.toDayActivity');
    }

    public function departmentAddNView()
    {
        return view('Hr.departmentAddNView');
    }

    public function createDepartmentForm()
    {
        return view('Hr.createDepartmentForm');
    }

    public function createEmployeeExitInterviewForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::where([['status', '!=', '2']])
            ->select('id','emp_department_id','emp_code','emp_name','emp_salary','emp_contact_no','region_id', 'emp_joining_date', 'emp_cnic','emp_date_of_birth','status')

            ->orderBy('emp_code','asc')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.createEmployeeExitInterviewForm',compact('employee'));
    }

    public function viewDepartmentList()
    {
        $departments = Department::where([['company_id','=',Input::get('m')],['status', '=', 1]])->get();
        return view('Hr.viewDepartmentList',compact('departments'));
    }

    public function editDepartmentForm()
    {
        return view('Hr.editDepartmentForm');
    }

    public function createSubDepartmentForm()
    {
        $departments = Department::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        return view('Hr.createSubDepartmentForm',compact('departments'));
    }

    public function viewSubDepartmentList()
    {
        $SubDepartments = SubDepartment::where([['company_id','=',Input::get('m')],['status','=', 1]])->orderBy('id')->get();
        return view('Hr.viewSubDepartmentList', compact('SubDepartments'));
    }

    public function editSubDepartmentForm()
    {
        $departments = Department::where([['company_id','=',Input::get('m')],['status','=', 1]])->orderBy('id')->get();
        return view('Hr.editSubDepartmentForm',compact('departments'));
    }

    public function createDesignationForm()
    {
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.createDesignationForm',compact('departments'));
    }

    public function viewDesignationList()
    {
        $designations = Designation::where([['company_id','=',Input::get('m')],['status','=', 1]])->orderBy('department_id')->get();
        return view('Hr.viewDesignationList', compact('designations'));
    }

    public function editDesignationForm()
    {
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.editDesignationForm',compact('departments'));
    }

    public function createEmployeeForm(){

        $subdepartments = new SubDepartment;
        $leaves_policy = LeavesPolicy::where([['status','=','1']])->get();
        $jobtype = JobType::where([['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where([['status', '=', '1']])->orderBy('id')->get();
        $marital_status = MaritalStatus::where([['status', '=', '1']])->orderBy('id')->get();
        $designation = Designation::where([ ['status', '=', '1']])->orderBy('id')->get();
        $qualification = Qualification::where([['status', '=', '1']])->orderBy('id')->get();
        $eobi = Eobi::where([['status', '=', '1']])->orderBy('id')->get();
        $tax= Tax::select('id','tax_name')->where([['status', '=', '1']])->orderBy('id')->get();
        $DegreeType = DegreeType::where([['status', '=', '1']])->orderBy('id')->get();
        $employee_category = EmployeeCategory::where([['status', '=', '1']])->orderBy('id')->get();
        $employee_projects = EmployeeProjects::where([['status', '=', '1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status', '=', '1']])->orderBy('id')->get();
        $employee_grades = Grades::where([['status', '=', '1']])->orderBy('id')->get();
        $employee_locations = Locations::where([['status', '=', '1']])->orderBy('id')->get();

        return view('Hr.createEmployeeForm',compact('DegreeType','tax','eobi','designation','qualification','leaves_policy','departments','subdepartments','jobtype','marital_status', 'employee_regions', 'employee_grades', 'employee_locations', 'employee_category', 'employee_projects'));
    }

    public function editEmployeeDetailForm($id, $CompanyId)
    {
        $login_credentials ='';

        $leaves_policy = LeavesPolicy::where([['status','=','1']])->get();
        $jobtype = JobType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $qualification = Qualification::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $eobi = Eobi::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $tax= Tax::select('id','tax_name')->where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_projects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        // $employee_grades = Grades::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_locations = Locations::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection($CompanyId);
        $employee_detail = Employee::where([['id','=',$id]])->first();
        $emp_code = $employee_detail->emp_code;
        $employee_family_detail = EmployeeFamilyData::where([['emp_code','=',$emp_code]]);
        $employee_bank_detail = EmployeeBankData::where([['emp_code','=',$emp_code]]);
        $employee_educational_detail = EmployeeEducationalData::where([['emp_code','=',$emp_code]]);
        $employee_language_proficiency = EmployeeLanguageProficiency::where([['emp_code','=',$emp_code]]);
        $employee_health_data = EmployeeHealthData::where([['emp_code','=',$emp_code]]);
        $employee_activity_data = EmployeeActivityData::where([['emp_code','=',$emp_code]]);
        $employee_work_experience = EmployeeWorkExperience::where([['emp_code','=',$emp_code]]);
        $employee_reference_data = EmployeeReferenceData::where([['emp_code','=',$emp_code]]);
        $employee_kins_data = EmployeeKinsData::where([['emp_code','=',$emp_code]]);
        $employee_relatives_data = EmployeeRelativesData::where([['emp_code','=',$emp_code]]);
        $employee_other_details = EmployeeOtherDetails::where([['emp_code','=',$emp_code]]);
        $employee_documents = EmployeeDocuments::where([['emp_code', '=', $emp_code], ['status','=', 1]]);
        $employee_gssp_documents = EmployeeGsspDocuments::where([['emp_code', '=', $emp_code], ['status','=', 1]]);
        $employee_cnic_copy = Employee::where([['emp_code','=',$emp_code],['status','=',1],['cnic_path', '!=', null]]);
        $employee_eobi_copy = Employee::where([['emp_code','=',$emp_code],['status','=',1],['eobi_path', '!=', null]]);
        $employee_insurance_copy = Employee::where([['emp_code','=',$emp_code],['status','=',1],['insurance_path', '!=', null]]);
        $employee_work_experience_doc = EmployeeWorkExperience::where([['emp_code','=',$emp_code],['status','=',1],['work_exp_path', '!=', null]]);

        CommonHelper::reconnectMasterDatabase();
        if($employee_detail->can_login == 'yes'):

            $login_credentials = DB::Table('users')->select('acc_type')->where([['company_id', '=', Input::get('m')],['emp_code', '=', $employee_detail->emp_code]])->first();
        endif;

        return view('Hr.editEmployeeDetailForm'
            ,compact('login_credentials','employee_other_details', 'employee_eobi_copy', 'employee_insurance_copy','employee_relatives_data','employee_kins_data','employee_reference_data','employee_work_experience','employee_activity_data','employee_health_data','employee_language_proficiency','employee_educational_detail','employee_bank_detail','employee_family_detail','leaves_policy','employee_detail','DegreeType','tax','eobi','designation','qualification','
            leaves_policy','departments','jobtype','marital_status',
                'employee_regions', 'employee_grades', 'employee_locations','buildings'
                , 'employee_documents', 'employee_gssp_documents', 'employee_cnic_copy', 'employee_work_experience_doc'));

    }

    public function viewEmployeeList(){

        $regions =  CommonHelper::regionRights(Session::get('run_company'));
        CommonHelper::companyDatabaseConnection(Session::get('run_company'));
        $employees = Employee::where([['status', '!=', '2']])
            ->select('id','emp_department_id','emp_code','emp_name','emp_salary','emp_contact_no','region_id', 'emp_joining_date', 'emp_cnic','emp_date_of_birth','status')
            ->whereIn('region_id',$regions)
            ->orderBy('emp_code','asc')->get();
        CommonHelper::reconnectMasterDatabase();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])->orderBy('id')->get();
        $regions = Regions::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])
            ->whereIn('id',$regions)
            ->orderBy('id')->get();

        return view('Hr.viewEmployeeList',compact('employees', 'departments', 'employee_category', 'regions','Employee_projects','buildings'));
    }

    public function uploadEmployeeFileForm()
    {
        return view('Hr.uploadEmployeeFileForm');
    }

    public function createAllowanceForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])
            ->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $allowance_type = AllowanceType::where([['status','=',1]])->get();
        return view('Hr.createAllowanceForm',compact('employee_regions','employee_department','allowance_type'));
    }

    public function viewAllowanceList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowance = DB::table('employee')
            ->join('allowance', 'employee.emp_code', '=', 'allowance.emp_code')
            ->select('allowance.*')
            ->where([['employee.status','=', 1],['allowance.status', '=', 1]])
            ->whereIn('employee.region_id',$regions)
            ->orderBy('allowance.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewAllowanceList',compact('allowance','Employee_projects','regions'));
    }

    public function editAllowanceDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowance = Allowance::where([['id','=',Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        $allowance_type = AllowanceType::where([['status','=',1]])->get();
        return view('Hr.editAllowanceDetailForm',compact('employees','allowance','allowance_type'));
    }

    public function createAdvanceSalaryForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $account = CommonHelper::get_all_bank_account();
        return view('Hr.createAdvanceSalaryForm',compact('employee_department','employee_regions','account'));
    }

    public function viewAdvanceSalaryList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $advance_salary = DB::table('employee')
            ->join('advance_salary', 'employee.emp_code', '=', 'advance_salary.emp_code')
            ->select('advance_salary.*')
            ->where([['employee.status','=', 1],['advance_salary.status', '=', 1]])
            ->whereIn('employee.region_id',$regions)
            ->orderBy('advance_salary.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.viewAdvanceSalaryList',compact('advance_salary'));
    }

    public function editAdvanceSalaryDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $advance_salary = AdvanceSalary::select('*')->where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        $account = CommonHelper::get_all_bank_account();
        return view('Hr.editAdvanceSalaryDetailForm',compact('advance_salary','account'));
    }

    public function createDeductionForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createDeductionForm',compact('employee_regions','employee_category','Employee_projects','employee_department'));
    }

    public function viewDeductionList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $deduction = DB::table('employee')
            ->join('deduction', 'employee.emp_code', '=', 'deduction.emp_code')
            ->select('deduction.*')
            ->where([['employee.status','=', 1],['deduction.status', '=', 1]])
            ->whereIn('employee.region_id',$regions)
            ->orderBy('deduction.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewDeductionList',compact('deduction'));
    }

    public function editDeductionDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $deduction = Deduction::where([['id','=',Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editDeductionDetailForm',compact('employees','deduction','departments','subdepartments'));
    }

    public function createLoanRequestForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $loanTypes = LoanType::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $account = CommonHelper::get_all_bank_account();
        return view('Hr.createLoanRequestForm',compact('employee_category','employee_regions','loanTypes','employee_department','account'));
    }

    public function viewLoanRequestList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loanRequest = DB::table('employee')
            ->join('loan_request', 'employee.emp_code', '=', 'loan_request.emp_code')
            ->select('loan_request.*')
            ->where([['employee.status','=', 1],['loan_request.status', '=', 1]])
            ->whereIn('employee.region_id',$regions)
            ->orderBy('loan_request.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewLoanRequestList',compact('loanRequest'));
    }

    public function editLoanRequestDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loanRequest = LoanRequest::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $loanTypes = LoanType::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $account = CommonHelper::get_all_bank_account();
        return view('Hr.editLoanRequestDetailForm',compact('loanTypes','loanRequest','employee','account'));
    }

    public function createLeaveApplicationForm()
    {
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createLeaveApplicationFormClient', compact('employee_regions','employee_department'));
//        $leaves_policy_flag = true;
//        $new_leaves_policy_flag = true;
//        $accType = Auth::user()->acc_type;
//        if($accType == 'client')
//        {
//
//        }
//        else
//        {
//            CommonHelper::companyDatabaseConnection(Input::get('m'));
//            $emp = Employee::select('id','emr_no','leaves_policy_id')->where([['status', '=', 1],['emr_no', '=', Auth::user()->emr_no]])->first();
//            CommonHelper::reconnectMasterDatabase();
//
//            if($emp->leaves_policy_id == '' || $emp->leaves_policy_id == 0):
//                $leaves_policy_flag = false;
//                return view('Hr.createLeaveApplicationForm', compact('leaves_policy_flag','new_leaves_policy_flag'));
//            else:
//                $emr_no = $emp->emr_no;
//
//                $leaves_policy = DB::table('leaves_policy')
//                    //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
//                    ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//                    ->select('leaves_policy.*', 'leaves_data.*')
//                    ->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
//                    ->get();
//                if(empty($leaves_policy->toArray())):
//                    $new_leaves_policy_flag = false;
//                    return view('Hr.createLeaveApplicationForm', compact('leaves_policy_flag','new_leaves_policy_flag'));
//                endif;
//
//                $leaves_policy_validatity = DB::table('leaves_policy')
//                    //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
//                    ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//                    ->select('leaves_policy.id', 'leaves_data.id')
//                    ->where([['leaves_policy.id', '=', $emp->leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
//                    ->count();
//
//                //echo Auth::user()->emp_id; die();
//                $total_leaves = DB::table("leaves_data")
//                    ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
//                    ->where([['leaves_policy_id', '=', $leaves_policy[0]->leaves_policy_id]])
//                    ->first();
//
//                $taken_leaves = DB::table("leave_application_data")
//                    ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
//                    ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
//                    ->where([['leave_application.emr_no', '=', $emr_no], ['leave_application.status', '=', '1'],
//                        ['leave_application.approval_status', '=', '2']])
//                    ->first();
//
//                CommonHelper::companyDatabaseConnection(Input::get('m'));
//                $emp_data = Employee::select('emp_name', 'emp_sub_department_id','emp_department_id','designation_id', 'emr_no','emp_joining_date','emp_contact_no')->where([['emr_no', '=', $emr_no],['status', '=', 1]])->first();
//                CommonHelper::reconnectMasterDatabase();
//                $getCurrentLeavePolicyYear = date('Y',strtotime($leaves_policy[0]->policy_date_from));
//                $date = strtotime($getCurrentLeavePolicyYear.' -1 year');
//                $getPreviousLeavePolicyYear = date('Y', $date);
//                $getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear.'%');
//                $getPreviousUsedAnnualLeavesBalance = 0;
//                $getPreviousUsedCasualLeavesBalance = 0;
//                if($getPreviousLeavePolicy->count() > 0 ):
//                    // print_r($getPreviousLeavePolicyId->first()->id);
//                    $getPreviousLeavePolicyId=$getPreviousLeavePolicy->first();
//
//                    $getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',1],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
//                    $getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',3],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
//                    $getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
//                        ->select(DB::raw("SUM(no_of_days) as no_of_days"))
//                        ->where([['emr_no','=',Input::get('emr_no')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','1']])
//                        ->first();
//                    $getPreviousUsedCasualLeaves = DB::table("leave_application_data")
//                        ->select(DB::raw("SUM(no_of_days) as no_of_days"))
//                        ->where([['emr_no','=',Input::get('emr_no')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','3']])
//                        ->first();
//
//                    $getPreviousUsedAnnualLeavesBalance =  $getPreviousAnnualLeaves-$getPreviousUsedAnnualLeaves->no_of_days;
//                    $getPreviousUsedCasualLeavesBalance =$getPreviousCasualLeaves-$getPreviousUsedCasualLeaves->no_of_days;
//
//                endif;
//                return view('Hr.createLeaveApplicationForm', compact('getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','emr_no', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves','new_leaves_policy_flag','leaves_policy_flag'));
//            endif;
//        }
    }

    public function viewLeaveApplicationRequestList()
    {
        $leave_application_request_list = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
            ->select('leave_application.*')
            ->where([['leave_application.view','=','yes'],['leave_application.emp_code','!=','0']])
            ->get();
        $m = Input::get('m');
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $companies = DB::table('company')->where('status',1)->get();
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        return view('Hr.viewLeaveApplicationRequestList', compact('leave_application_request_list','employee_department','departments','employee_regions','m','companies'));
    }
    public function viewLeaveBalances()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
        $leavesPolicy = LeavesPolicy::all()->sortByDesc("id");
        return view('Hr.viewLeaveBalances', compact('companies','leavesPolicy'));
    }

    public function viewLeaveApplicationList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_code = Employee::select('emp_code')->where([['emp_code','=',Auth::user()->emp_code]])->value('emp_code');
        CommonHelper::reconnectMasterDatabase();
        $leave_application_list = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
            ->select('leave_application.*')
            ->where([['leave_application.emp_code', '=',$emp_code]])
            ->get();
        return view('Hr.viewLeaveApplicationList', compact('leave_application_list'));
    }

    public function editLeaveApplicationDetailForm()
    {
        $id_and_leaveId = explode('|', Input::get('id'));
        $emp_code = $id_and_leaveId[1];
        $leaveApplicationData  = LeaveApplicationData::where([['leave_application_id','=',$id_and_leaveId[0]]])->first()->toArray();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp = Employee::select('id','emp_code','leaves_policy_id')->where([['emp_code', '=', $emp_code]])->first();
        CommonHelper::reconnectMasterDatabase();
        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
            ->get();

        $leaves_policy_validatity = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.id', 'leaves_data.id')
            ->where([['leaves_policy.id', '=', $emp->leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
            ->count();

        //echo Auth::user()->emp_id; die();
        $total_leaves = DB::table("leaves_data")
            ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
            ->where([['leaves_policy_id', '=', $leaves_policy[0]->leaves_policy_id]])
            ->first();

        $taken_leaves = DB::table("leave_application_data")
            ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.emp_code', '=', $emp_code], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '=', '2']])
            ->first();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('leaves_policy_id','emp_name', 'emp_department_id', 'designation_id', 'emp_code')->where([['emp_code', '=', $emp_code]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        $getCurrentLeavePolicyYear = $leaves_policy[0]->policy_date_from;
        $date = strtotime($getCurrentLeavePolicyYear.' -1 year');
        $getPreviousLeavePolicyYear = date('Y', $date);
        $getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear.'%');
        $getPreviousUsedAnnualLeavesBalance = 0;
        $getPreviousUsedCasualLeavesBalance = 0;
        if($getPreviousLeavePolicy->count() > 0 ):
            // print_r($getPreviousLeavePolicyId->first()->id);
            $getPreviousLeavePolicyId=$getPreviousLeavePolicy->first();

            $getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',1],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
            $getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',3],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
            $getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
                ->select(DB::raw("SUM(no_of_days) as no_of_days"))
                ->where([['emp_code','=',Input::get('emp_code')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','1']])
                ->first();
            $getPreviousUsedCasualLeaves = DB::table("leave_application_data")
                ->select(DB::raw("SUM(no_of_days) as no_of_days"))
                ->where([['emp_code','=',Input::get('emp_code')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','3']])
                ->first();

            $getPreviousUsedAnnualLeavesBalance =  $getPreviousAnnualLeaves-$getPreviousUsedAnnualLeaves->no_of_days;
            $getPreviousUsedCasualLeavesBalance =$getPreviousCasualLeaves-$getPreviousUsedCasualLeaves->no_of_days;

        endif;

        return view('Hr.editLeaveApplicationDetailForm', compact('leaveApplicationData','getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','emp_code', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves'));
    }

    public function createEmployeePromotionForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createEmployeePromotionForm',compact('employee_regions','designation','employee_department'));
    }

    public function viewEmployeePromotions()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $m = Input::get('m');
        $regions =  CommonHelper::regionRights($m);
        $employee_regions = Regions::where([['status','=',1],['company_id','=',$m]])->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',$m]])->get();
        return view('Hr.viewEmployeePromotions', compact('employee_regions','operation_rights2','employee_department'));
    }

    public function editEmployeePromotionDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_promotion = EmployeePromotion::where([['id','=', $id]])->first();
        CommonHelper::reconnectMasterDatabase();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.editEmployeePromotionDetailForm', compact('designation', 'employee_promotion'));
    }

    public function createDiseasesForm()
    {
        return view('Hr.createDiseasesForm');
    }

    public function viewDiseasesList()
    {
        $disease = Diseases::where([['company_id','=',Input::get('m')],['status', '=', 1]])->orderBy('id')->get();
        return view('Hr.viewDiseasesList', compact('disease'));
    }

    public function editDiseasesDetailForm()
    {
        $disease = Diseases::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editDiseasesDetailForm', compact('disease'));
    }

    public function viewEmployeeMedicalList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeMedical = EmployeeMedical::where([['status', '=', 1]])->orderBy('id');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewEmployeeMedicalList', compact('employeeMedical'));
    }

    public function createEmployeeMedicalForm()
    {
        $disease = Diseases::where('status', '=', 1)->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createEmployeeMedicalForm', compact('disease', 'employee_regions', 'employee_department'));
    }

    public function editEmployeeMedicalDetailForm(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeMedical = EmployeeMedical::where([['id', '=', Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $disease = Diseases::where('status', '=', 1)->get();
        return view('Hr.editEmployeeMedicalDetailForm', compact('employeeMedical', 'disease'));
    }

    public function createAllowanceTypeForm()
    {
        return view('Hr.createAllowanceTypeForm');
    }

    public function viewAllowanceTypeList()
    {
        $allowance_type = AllowanceType::where([['status','=',1]]);
        return view('Hr.viewAllowanceTypeList', compact('allowance_type'));
    }

    public function editAllowanceTypeForm()
    {
        $allowance_type = AllowanceType::where([['id','=',Input::get('id')]])->first();
        return view('Hr.editAllowanceTypeForm', compact('allowance_type'));
    }

    public function createBonusForm()
    {
        return view('Hr.createBonusForm');
    }

    public function viewBonusList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus = Bonus::where([['status','=','1']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewBonusList',compact('bonus'));
    }

    public function editBonusDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus = Bonus::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editBonusDetailForm',compact('bonus'));
    }

    public function IssueBonusDetailForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus_list = Bonus::where([['status','=','1']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.IssueBonusDetailForm',compact('bonus_list','employee_department','employee_regions'));
    }



    //new code end









    public function createHealthInsuranceForm(){
        return view('Hr.createHealthInsuranceForm');
    }

    public function viewHealthInsuranceList(){

        $HealthInsurances = HealthInsurance::where([['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewHealthInsuranceList', compact('HealthInsurances'));
    }

    public function editHealthInsuranceForm(){
        return view('Hr.editHealthInsuranceForm');
    }

    public function createEmployeeCategoryForm(){
        return view('Hr.createEmployeeCategoryForm');
    }

    public function viewEmployeeCategoryList()
    {
        $EmployeeCategory = EmployeeCategory::where([['company_id','=',Input::get('m')],['status','=', 1]])->get();
        return view('Hr.viewEmployeeCategoryList', compact('EmployeeCategory'));
    }

    public function editEmployeeCategoryDetailForm(){
        return view('Hr.editEmployeeCategoryDetailForm');
    }

    public function createJobTypeForm(){
        return view('Hr.createJobTypeForm');
    }

    public function viewJobTypeList()
    {
        $JobTypes = JobType::where([['company_id','=',Input::get('m')],['status','=', 1]])->get();
        return view('Hr.viewJobTypeList',compact('JobTypes'));
    }

    public function editJobTypeForm(){
        return view('Hr.editJobTypeForm');
    }

    public function createQualificationForm(){

        $countries = Countries::where('status', '=', 1)->get();
        $institutes = Institute::where('status', '=', 1)->get();

        return view('Hr.createQualificationForm',compact('countries','institutes'));
    }

    public function viewQualificationList()
    {
        $Qualifications = Qualification::where([['company_id','=',Input::get('m')],['status','=', 1]])->get();
        return view('Hr.viewQualificationList',compact('Qualifications'));
    }

    public function editQualificationForm(){
        $qualificationDetail = DB::selectOne('select * from `qualification` where `id` = '.Input::get('id').'');
        $countries = Countries::where('status', '=', 1)->get();
        $states = States::where([['status', '=', 1],['country_id', '=', $qualificationDetail->country_id]])->get();
        $cities = Cities::where([['status', '=', 1],['state_id', '=', $qualificationDetail->state_id]])->get();
        $institutes = Institute::where('status', '=', 1)->get();
        return view('Hr.editQualificationForm',compact('states','cities','qualificationDetail','countries','institutes'));
    }

    public function createLeaveTypeForm(){
        return view('Hr.createLeaveTypeForm');
    }

    public function viewLeaveTypeList()
    {
        $LeaveTypes = LeaveType::where([['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.viewLeaveTypeList', compact('LeaveTypes'));
    }

    public function editLeaveTypeForm(){
        return view('Hr.editLeaveTypeForm');
    }

    public function createLoanTypeForm(){

        return view('Hr.createLoanTypeForm');
    }

    public function viewLoanTypeList()
    {
        $LoanTypes = LoanType::where([['status', '=', '1'],['company_id', '=', Input::get('m')]])->orderBy('id')->get();
        return view('Hr.viewLoanTypeList', compact('LoanTypes'));
    }

    public function editLoanTypeForm(){
        return view('Hr.editLoanTypeForm');
    }

    public function createAdvanceTypeForm(){
        return view('Hr.createAdvanceTypeForm');
    }

    public function viewAdvanceTypeList(){

        $AdvanceTypes = AdvanceType::where([['status','=', 1],['company_id', '=', Input::get('m')]])->get();
        return view('Hr.viewAdvanceTypeList', compact('AdvanceTypes'));
    }

    public function editAdvanceTypeForm(){
        return view('Hr.editAdvanceTypeForm');
    }

    public function createShiftTypeForm(){
        return view('Hr.createShiftTypeForm');
    }

    public function editShiftTypeForm(){
        return view('Hr.editShiftTypeForm');
    }

    public function createHiringRequestAddForm(){

        $departments = Department::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $JobTypes = JobType::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $Designations = Designation::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $Qualifications = Qualification::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $ShiftTypes = ShiftType::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        return view('Hr.createHiringRequestAddForm',compact('departments','JobTypes','Designations','Qualifications','ShiftTypes'));
    }

    public function viewHiringRequestList(){

        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        $RequestHiring = RequestHiring::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewHiringRequestList', ['RequestHiring' => $RequestHiring]);
    }

    public function editHiringRequestForm(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hiringRequestDetail = RequestHiring::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $departments = Department::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $JobTypes = JobType::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $Designations = Designation::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $Qualifications = Qualification::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $ShiftTypes = ShiftType::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();

        return view('Hr.editHiringRequestForm',compact('hiringRequestDetail','departments','JobTypes','Designations','Qualifications','ShiftTypes'));
    }

    public function createManageAttendanceForm()
    {
        return view('Hr.createManageAttendanceForm',compact('employees'));
    }

    public function ViewAttendanceProgress()
    {
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.ViewAttendanceProgress', compact('employee_regions','employee_department'));
    }

    public function viewEmployeeAttendanceList()
    {
        $regions = CommonHelper::regionRights(Input::get('m'));
        $departments = CommonHelper::departmentRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$departments)->get();
        return view('Hr.viewEmployeeAttendanceList', compact('employee_regions','employee_department'));
    }

    public function createPayrollForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])
            ->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createPayrollForm', compact('employee_regions','employee_department'));
    }

    public function viewPayrollList(){
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        return view('Hr.viewPayrollList', compact('employee_regions','employee_department'));
    }

    public function viewPayrollReport()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $departments = CommonHelper::departmentRights(Input::get('m'));
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$departments)->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        return view('Hr.viewPayrollReport', compact('employee_department','employee_regions'));
    }

    public function companyWisePayrollReport()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
        return view('Hr.companyWisePayrollReport',compact('companies'));
    }

    public function viewArrearsList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $arrears = DB::table('employee')
            ->join('arrears', 'employee.emp_code', '=', 'arrears.emp_code')
            ->select('arrears.*','employee.emp_department_id')
            ->where([['employee.status','=', 1],['arrears.status', '=', 1]])
            ->whereIn('employee.region_id',$regions);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewArrearsList', compact('arrears'));
    }

    public function viewS2bReport()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewS2bReport', compact('employee_regions', 'employee_category','Employee_projects'));
    }

    public function viewPayrollReceivingReport()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->orderBy('order_by_no', 'asc')->get()->toArray();
        return view('Hr.viewPayrollReceivingReport',compact('companies'));
    }

    public function createMaritalStatusForm()
    {
        return view('Hr.createMaritalStatusForm');

    }
    public function editMaritalStatusForm()
    {
        return view('Hr.editMaritalStatusForm');

    }

    public function viewMaritalStatuslist()
    {
        $maritalStatus = MaritalStatus::where([['status','=', 1],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        return view('Hr.viewMaritalStatuslist', compact('maritalStatus'));
    }




    public function createLeavesPolicyForm()
    {
        $leaves_types  = LeaveType::select('id','leave_type_name')->where([['company_id', '=',0]])->orderBy('id')->get();
        return view('Hr.createLeavesPolicyForm',compact('leaves_types'));
    }

    public function createManualLeaves()
    {
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();

        return view('Hr.createManualLeaves',compact('departments','subdepartments','Employee_projects','employee_regions','employee_category'));
    }

    public function viewLeavesPolicyList()
    {
        $leavesPolicy = LeavesPolicy::where([['status', '=', 1]])->orderBy('id', 'desc')->get();;
        return view('Hr.viewLeavesPolicyList',compact('leavesPolicy'));
    }

    public function editLeavesPolicyDetailForm()
    {
        $leavesType =   LeaveType::where([['company_id','=',0]])->get();
        $leavesPolicy = LeavesPolicy::where([['id','=',Input::get('id')]])->first();
        $leavesData =   LeavesData::where([['leaves_policy_id','=',Input::get('id')]])->get();

        return view('Hr.editLeavesPolicyDetailForm',compact('leavesPolicy','leavesData','leavesType'));
    }

    public function createVehicleTypeForm()
    {
        return view('Hr.createVehicleTypeForm');
    }

    public function viewVehicleTypeList()
    {
        $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewVehicleTypeList',compact('vehicleType'));
    }

    public function editVehicleTypeDetailForm()
    {
        $vehicleType = VehicleType::where([['id','=',Input::get('id')]])->get(['vehicle_type_name','vehicle_type_cc'])->first();
        return view('Hr.editVehicleTypeDetailForm',compact('vehicleType'));
    }

    public function createWorkingHoursPolicyDetailForm()
    {
        return view('Hr.createWorkingHoursPolicyDetailForm');
    }

    public function createHolidaysForm()
    {
        return view('Hr.createHolidaysForm');
    }

    public function viewHolidaysList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::orderBy('holiday_date')->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewHolidaysList',compact('holidays','departments'));
    }

    public function editHolidaysDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidaysDetail = Holidays::find(Input::get('id'))->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editHolidaysDetailForm',compact('holidaysDetail'));
    }

    public function viewWorkingHoursPolicyList()
    {
        return view('Hr.viewWorkingHoursPolicyList');
    }

    public function createCarPolicyForm()
    {
        $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
        $designation = Designation::where([['company_id','=',Input::get('m')]])->get();
        return view('Hr.createCarPolicyForm',compact('vehicleType','designation'));
    }

    public function viewCarPolicyList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicy = CarPolicy::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewCarPolicyList',compact('carPolicy'));
    }

    public function viewCarPolicyCriteria()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicy = CarPolicy::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        $departments = Department::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        return view('Hr.viewCarPolicyCriteria',compact('departments','carPolicy'));
    }

    public function editCarPolicyDetailForm()
    {   $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
        $designation = Designation::where([['company_id','=',Input::get('m')]])->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicy = CarPolicy::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editCarPolicyDetailForm',compact('carPolicy','vehicleType','designation'));
    }



    public function createEOBIForm()
    {
        return view('Hr.createEOBIForm');
    }

    public function viewEOBIList()
    {
        $eobi = Eobi::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewEOBIList',compact('eobi'));
    }

    public function editEOBIDetailForm()
    {
        $eobi = Eobi::select('id','EOBI_name','EOBI_amount','month_year')->where([['id','=',Input::get('id')],['company_id','=',Input::get('m')]])->first();
        return view('Hr.editEOBIDetailForm',compact('eobi'));
    }

    public function createTaxesForm()
    {
        return view('Hr.createTaxesForm');
    }

    public function viewTaxesList()
    {
        $tax = Tax::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewTaxesList',compact('tax'));
    }
    public function editTaxesDetailForm()
    {
        $tax = Tax::where([['id','=',Input::get('id')],['company_id','=',Input::get('m')]])->first();
        return view('Hr.editTaxesDetailForm',compact('tax'));
    }

    public function viewTaxCriteria()
    {
        $departments = Department::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        $taxes = Tax::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        return view('Hr.viewTaxCriteria',compact('departments','taxes'));
    }




    public function createEmployeeDepositForm()
    {
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',$_GET['m'])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.createEmployeeDepositForm',compact('departments','subdepartments'));
    }

    public function editEmployeeDepositDetail()
    {
        $empDepositId = Input::get('id');
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $EmployeeDepositData = EmployeeDeposit::where('id','=',$empDepositId)->first();
        $employee = Employee::all();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.editEmployeeDepositDetail',compact('subdepartments','departments','EmployeeDepositData','employee'));
    }

    public function viewEmployeeDepositList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeDeposit = EmployeeDeposit::all();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewEmployeeDepositList',compact('employeeDeposit'));
    }

    public function createEmployeeGradesForm(){
        return view('Hr.createEmployeeGradesForm');
    }

    public function editEmployeeGradesDetailForm(){
        $employeeGradesDetail = Grades::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEmployeeGradesDetailForm', compact('employeeGradesDetail'));
    }

    public function viewEmployeeGradesList()
    {
        $employee_grades = Grades::where([['status','=',1],['company_id','=',Input::get('m')]])->orderBy('employee_grade_type')->get();
        return view('Hr.viewEmployeeGradesList', ['EmployeeGrades' => $employee_grades]);
    }

    public function createEmployeeLocationsForm()
    {
        $region = Regions::where([['company_id',Input::get('m')], ['status','=','1']])->get();
        $project = EmployeeProjects::where([['company_id',Input::get('m')], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.createEmployeeLocationsForm', compact('region','project'));
    }

    public function editEmployeeLocationsDetailForm()
    {
        $region = Regions::where([['company_id',Input::get('m')], ['status','=','1']])->get();
        $project = EmployeeProjects::where([['company_id',Input::get('m')], ['status','=','1']])->orderBy('id')->get();
        $location = Locations::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEmployeeLocationsDetailForm', compact('region','project','location'));
    }

    public function viewEmployeeLocationsList()
    {
        $employee_locations = Locations::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewEmployeeLocationsList', compact('employee_locations'));
    }

    public function createEmployeeRegionsForm()
    {
        return view('Hr.createEmployeeRegionsForm');
    }

    public function editEmployeeRegionsDetailForm()
    {
        $employeeRegionsDetail = Regions::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEmployeeRegionsDetailForm', compact('employeeRegionsDetail'));
    }

    public function viewEmployeeRegionsList()
    {
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewEmployeeRegionsList', ['EmployeeRegions' => $employee_regions]);
    }

    public function createEmployeeDegreeTypeForm(){
        return view('Hr.createEmployeeDegreeTypeForm');
    }

    public function viewEmployeeDegreeTypeList(){
        $employee_degree_type = DegreeType::all();
        return view('Hr.viewEmployeeDegreeTypeList', ['EmployeeDegreeType' => $employee_degree_type]);
    }

    public function editEmployeeDegreeTypeDetailForm(){
        $employeeDegreeTypeDetail = DegreeType::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEmployeeDegreeTypeDetailForm', compact('employeeDegreeTypeDetail'));
    }

    public function createEmployeeExitClearanceForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])
            ->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createEmployeeExitClearanceForm', compact('employee', 'employee_regions','employee_department'));
    }

    public function viewEmployeeExitClearanceList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_exit = DB::table('employee')
            ->join('employee_exit', 'employee.emp_code', '=', 'employee_exit.emp_code')
            ->select('employee_exit.*')
            ->where([['employee.status','!=', 2],['employee_exit.status', '=', 1]])
            ->whereIn('employee.region_id',$regions)
            ->orderBy('employee_exit.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewEmployeeExitClearanceList', compact('employee_exit', 'employee'));
    }

    public function editEmployeeExitClearanceDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $exit_employee_data = EmployeeExit::where([['id','=',$id]])->first();
        $employee = Employee::select('emp_name', 'designation_id', 'emp_joining_date', 'emp_department_id')->where([['emp_code','=', $exit_employee_data->emp_code]])->first();
        $designation_id = $employee->designation_id;
        $emp_department_id = $employee->emp_department_id;
        $employeeCurrentPositions = EmployeePromotion::where([['emp_code','=',$exit_employee_data->emp_code],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
        endif;
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.editEmployeeExitClearanceDetailForm', compact('exit_employee_data', 'designation_id', 'employee', 'emp_department_id'));
    }

    public function editFinalSettlementDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $array = explode('|',Input::get('id'));
        $id = $array[0];
        $gratuityAmount = '';
        $emp_code = $array[1];
        $final_settlement = FinalSettlement::where([['status', '=', 1], ['emp_code', '=', $emp_code]])->first();
        $employee = Employee::where([['status', '!=', 2], ['emp_code', '=', $emp_code]])->select('emp_code','emp_name','emp_department_id','designation_id', 'emp_joining_date', 'emp_salary','region_id')->first();
        $designation_id = $employee['designation_id'];
        $salary = $employee['emp_salary'];
        $emp_department_id = $employee['emp_department_id'];

        $gratuity = Gratuity::where([['emp_code',$emp_code]])->orderBy('id','desc');
        if($gratuity->exists()):
            $gratuityDetails = $gratuity->first();
            $gratuityAmount = $gratuityDetails->gratuity;
        endif;

        $employeeCurrentPositions = EmployeePromotion::where([['emp_code','=',$emp_code],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
            $salary = $employeeCurrentPositionsDetail['salary'];
        endif;

        $exit_data = EmployeeExit::where([['status', '=', 1], ['emp_code', '=', $emp_code]])->select('leaving_type', 'last_working_date')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editFinalSettlementDetailForm', compact('exit_data','gratuityAmount', 'final_settlement','salary','designation_id', 'employee', 'emp_department_id'));
    }


    public function createEmployeeIdCardRequest()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createEmployeeIdCardRequest', compact('employee_category', 'employee_regions','Employee_projects'));
    }

    public function viewEmployeeIdCardRequestList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_card_request = DB::table('employee')
            ->join('employee_card_request', 'employee.emr_no', '=', 'employee_card_request.emr_no')
            ->select('employee_card_request.*')
            ->where([['employee.status','!=', 2],['employee_card_request.status', '=', 1]])
            ->whereIn('employee.region_id',$regions)
            ->orderBy('employee_card_request.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewEmployeeIdCardRequestList', compact('employee_card_request'));
    }

    public function editEmployeeIdCardRequestDetailForm()
    {
        $id = $_GET['id'];
        $m 	= $_GET['m'];

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_card_request=EmployeeCardRequest::where([['status', '=',1],['id', '=', $id]])->first();
        $employee=Employee::where([['emr_no', '=', $employee_card_request->emr_no],['status', '=',1]])->select('img_path','emp_name', 'designation_id', 'emp_sub_department_id', 'emp_joining_date', 'emp_cnic')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation=Designation::where([['status', '=', 1],['id',$employee->designation_id]])->select('designation_name')->first();
        $sub_department=SubDepartment::where([['status', '=', 1],['id',$employee->emp_sub_department_id]])->select('sub_department_name')->first();

        return view('Hr.editEmployeeIdCardRequestDetailForm', compact('designation', 'employee_card_request', 'employee', 'sub_department'));
    }

    public function createEmployeeProjectsForm()
    {
        return view('Hr.createEmployeeProjectsForm');
    }

    public function viewEmployeeProjectsList()
    {
        $employee_projects = EmployeeProjects::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewEmployeeProjectsList', ['EmployeeProjects' => $employee_projects]);
    }

    public function editEmployeeProjectsDetailForm()
    {
        $employee_projects = EmployeeProjects::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEmployeeProjectsDetailForm',compact('employee_projects'));
    }

    public function createEmployeeTransferForm()
    {   $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $location = Locations::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        $employee_grades = Grades::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        return view('Hr.createEmployeeTransferForm',compact('employee_regions','employee_category','designation','location', 'employee_grades','Employee_projects'));
    }

    public function viewEmployeeTransferList()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeLocations = EmployeeTransfer::where('status','=',1)->orderBy('emr_no')->orderBy('id','desc');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewEmployeeTransferList', compact('employeeLocations','operation_rights2','m'));
    }

    public function editEmployeeTransferDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_location = EmployeeTransfer::where([['id','=', $id]])->orderBy('id')->first();
        $employee_promotion_id = $employee_location->promotion_id;
        $employee_transfer_project_id = $employee_location->transfer_project_id;
        $count = 0;
        $promotionCount = 0;

        if($employee_promotion_id != 0)
        {
            $promotionCount = 1;
            $employee_promotion = EmployeePromotion::where([['id','=', $employee_promotion_id]])->orderBy('id')->first();
        }

        if($employee_transfer_project_id != 0)
        {
            $count = 2;
            $TransferEmployeeProject = TransferEmployeeProject::where([['id','=', $employee_transfer_project_id]])->orderBy('id')->first();
        }
        CommonHelper::reconnectMasterDatabase();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $location = Locations::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_grades = Grades::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.editEmployeeTransferDetailForm', compact('designation', 'employee_promotion','promotionCount','count', 'employee_location', 'location', 'employee_grades','Employee_projects','TransferEmployeeProject'));
    }

    public function createEmployeeFuelDetailForm()
    {
        $subdepartments = new SubDepartment;
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $location = Locations::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.createEmployeeFuelDetailForm',compact('departments','subdepartments','designation','location'));
    }

    public function viewEmployeeFuel()
    {
        $subdepartments = new SubDepartment;
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.viewEmployeeFuel',compact('departments','subdepartments','designation'));
    }

    public function editEmployeeFuelDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeFuelData = EmployeeFuelData::where([['status', '=', 1],['id', '=', Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.editEmployeeFuelDetailForm',compact('employeeFuelData'));
    }

    public function updateLabourSalaryForm()
    {
        return view('Hr.updateLabourSalaryForm');
    }

    public function createHrLetters()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createHrLetters', compact('employee_category', 'employee_regions','Employee_projects'));
    }

    public function viewHrLetters()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewHrLetters', compact('employee_regions', 'employee_category','Employee_projects'));
    }

    public function uploadLettersFile()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $uploaded_letters_list = LetterFiles::where([['status', '=', 1]])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.uploadLettersFile', compact('employee_regions', 'employee_category', 'uploaded_letters_list','Employee_projects'));
    }

    public function createEquipmentsForm()
    {
        return view('Hr.createEquipmentsForm');
    }

    public function viewEquipmentsList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $EmployeeEquipment = DB::table('employee')
            ->join('equipments', 'employee.emr_no', '=', 'equipments.emr_no')
            ->select('equipments.*')
            ->where([['employee.status','=', 1],['equipments.status', '=', 1]])
            ->whereIn('employee.region_id',$regions)
            ->orderBy('equipments.id')
            ->get();
        return view('Hr.viewEquipmentsList', compact('EmployeeEquipment'));
    }

    public function editEquipmentDetailForm()
    {
        $employeeEquipment = Equipments::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEquipmentDetailForm', compact('employeeEquipment'));
    }

    public function createEmployeeEquipmentsForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employeeEquipment = Equipments::where([['company_id','=',Input::get('m')],['status','=',1]])->orderBy('id')->get();
        return view('Hr.createEmployeeEquipmentsForm', compact('employee_category', 'employee_regions', 'employeeEquipment','Employee_projects'));
    }

    public function viewEmployeeEquipmentsList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeEquipment = EmployeeEquipments::where([['status', '=', 1]])->groupBy('emr_no')->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.viewEmployeeEquipmentsList', compact('employeeEquipment'));
    }

    public function editEmployeeEquipmentsDetailForm()
    {
        $equipment_detail = null;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_emr_no = EmployeeEquipments::where([['id','=', Input::get('id')]])->first();
        $emr_no = $employee_emr_no->emr_no;
        $employee = Employee::select('id','emr_no', 'eobi_number', 'eobi_path', 'insurance_number', 'insurance_path')->where([['emr_no','=',$emr_no],['status','!=',2]])->first();
        $employeeEquipment  = EmployeeEquipments::where([['emr_no','=', $emr_no]])->pluck('equipment_id')->toArray();

        if(EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emr_no','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->exists()):
            $equipment_detail = EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emr_no','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->first();
        endif;

        $employee_eobi_copy = Employee::where([['emr_no','=',$emr_no],['status','!=',2],['eobi_path', '!=', null]]);
        $employee_insurance_copy = Employee::where([['emr_no','=',$emr_no],['status','!=',2],['insurance_path', '!=', null]]);

        CommonHelper::reconnectMasterDatabase();
        $equipment = Equipments::where([['status','=', 1]])->orderBy('id')->get();

        return view('Hr.editEmployeeEquipmentsDetailForm', compact('employeeEquipment', 'emr_no', 'equipment', 'employee', 'equipment_detail', 'employee_insurance_copy', 'employee_eobi_copy'));
    }



    public function viewHrReports()
    {
        return view('Hr.viewHrReports');
    }

    public function editEmployeeAttendanceDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendanceDetail = Attendance::where([['id','=', Input::get('id')]])->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editEmployeeAttendanceDetailForm', compact('attendanceDetail'));
    }

    public function createTrainingForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_locations = Locations::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createTrainingForm', compact('employee_regions','employee_category','employee_locations','Employee_projects'));
    }

    public function viewTrainingList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $trainingsData = Trainings::where([['status','=',1]])->orderBy('training_date','desc')
            ->whereIn('region_id',$regions)
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewTrainingList', compact('trainingsData'));
    }

    public function editTrainingDetailForm()
    {
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_locations = Locations::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::where([['status', '=', 1]])->select('emr_no', 'emp_name')->get();
        $trainingsData = Trainings::where([['status','=',1],['id', Input::get('id')]])->first();
        $TrainingCertificate = TrainingCertificate::where([['status','=',1],['training_id', Input::get('id')]])->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editTrainingDetailForm', compact('employee_regions','employee_category','employee_locations', 'trainingsData','employee','TrainingCertificate'));

    }


    public function createEmployeeGratuityForm()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createEmployeeGratuityForm', compact('employee_category', 'employee_regions','Employee_projects'));
    }

    public function uploadOtAndFuelFile()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.uploadOtAndFuelFile', compact('employee_regions', 'employee_category'));
    }

    public function viewS2bNewsReport()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewS2bNewsReport', compact('employee_regions', 'employee_category','Employee_projects'));
    }

    public function viewOtAndFuelReport()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewOtAndFuelReport', compact('employee_regions', 'employee_category'));
    }

    public function employeeTransferLeaves()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
        $leavesPolicy = LeavesPolicy::all()->sortByDesc("id");
        return view('Hr.employeeTransferLeaves', compact('companies','leavesPolicy'));
    }

    public function createProjectTransferForm(){
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $location = Locations::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        $employee_grades = Grades::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        return view('Hr.createProjectTransferForm',compact('employee_regions','employee_category','designation','location', 'employee_grades','Employee_projects'));
    }

    public function viewProjectTransferList(){
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $transferEmployeeProject = TransferEmployeeProject::where('status','=',1)->orderBy('emr_no')->orderBy('id','desc');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewProjectTransferList', compact('transferEmployeeProject','operation_rights2','m'));
    }

    public function editEmployeeTransferProject()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $TransferEmployeeProject = TransferEmployeeProject::where([['id','=', $id]])->first();
        $employee = Employee::where('emr_no','=',$TransferEmployeeProject->emr_no)->first();
        CommonHelper::reconnectMasterDatabase();
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $location = Locations::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        $employee_grades = Grades::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        return view('Hr.editEmployeeTransferProject',compact('employee_regions','employee_category','designation','location', 'employee_grades','Employee_projects','TransferEmployeeProject','employee'));
    }

}