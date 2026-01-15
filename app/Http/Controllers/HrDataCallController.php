<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Designation;
use App\Models\Diseases;
use App\Models\EmployeeGsspDocuments;
use App\Models\EmployeeMedical;
use App\Models\HrWarningLetter;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use Input;
use DateTime;
use DatePeriod;
use DateInterval;

use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Payroll;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\JobType;
use App\Models\SubDepartment;
use App\Models\MaritalStatus;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\CarPolicy;
use App\Models\Bonus;
use App\Models\LoanRequest;
use App\Models\Tax;
use App\Models\Eobi;
use App\Models\User;
use App\Models\RequestHiring;
use App\Models\Qualification;
use App\Models\ShiftType;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use App\Models\Holidays;
use App\Models\EmployeeDeposit;
use App\Models\LeaveApplicationData;
use App\Models\EmployeeExit;
use App\Models\Locations;
use App\Models\EmployeeCategory;
use App\Models\EmployeeCardRequest;
use App\Models\DegreeType;
use App\Models\Regions;
use App\Models\Grades;
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
use App\Models\EmployeePromotion;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeTransfer;
use App\Models\EmployeeFuelData;
use App\Models\HrTerminationFormat1Letter;
use App\Models\HrTerminationFormat2Letter;
use App\Models\HrContractConclusionLetter;
use App\Models\HrMfmSouthWithoutIncrementLetter;
use App\Models\HrMfmSouthIncrementLetter;
use App\Models\EmployeeHrAudit;
use App\Models\EmployeeEquipments;
use App\Models\Equipments;
use App\Models\LeaveApplication;
use App\Models\LetterFiles;
use App\Models\EmployeeMedicalDocuments;
use App\Models\Trainings;
use App\Models\FinalSettlement;
use App\Models\HrTransferLetter;
use App\Models\Gratuity;
use App\Models\AdvanceSalary;
use App\Models\TrainingCertificate;
use App\Models\TransferLetter;
use App\Models\PromotionLetter;
use App\Models\TransferEmployeeProject;
use App\Models\projectTransferLetter;
use App\Models\EmployeeProjects;


class HrDataCallController extends Controller
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


    public function filterEmployeeList()
    {
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];

        $selectEmployeeGradingStatus = $_GET['selectEmployeeGradingStatus'];
        $selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];

        CommonHelper::companyDatabaseConnection($m);
        if (empty($selectEmployeeGradingStatus) && empty($selectSubDepartmentId)) {
            $employeeList = Employee::get();
        } else if (empty($selectEmployeeGradingStatus) && !empty($selectSubDepartmentId)) {
            $employeeList = Employee::whereBetween('date', [$fromDate, $toDate])->whereIn('status', array(1, 2))->where('emp_sub_department_id', '=', $selectSubDepartmentId)->get();
        } else if (!empty($selectEmployeeGradingStatus) && !empty($selectSubDepartmentId)) {
            $employeeList = Employee::whereBetween('date', [$fromDate, $toDate])->whereIn('status', array(1, 2))->where('emp_sub_department_id', '=', $selectSubDepartmentId)->where('grading_system', '=', $selectEmployeeGradingStatus)->get();
        } else if (!empty($selectEmployeeGradingStatus) && empty($selectSubDepartmentId)) {
            $employeeList = Employee::whereBetween('date', [$fromDate, $toDate])->whereIn('status', array(1, 2))->where('grading_system', '=', $selectEmployeeGradingStatus)->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.filterEmployeeList', compact('employeeList'));
    }

    public function viewEmployeeDocuments()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('emp_code', 'emp_name', 'cnic_path')->where([['id', '=', Input::get('id')], ['status', '=', 1]])->first();
        $employeeDocuments = EmployeeDocuments::where([['emp_code', '=', $employee->emp_code], ['status', '=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeDocuments', compact('employee', 'employeeDocuments'));
    }

    public function viewEmployeeGsspVeriDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('emp_code', 'emp_name')->where([['id', '=', Input::get('id')], ['status', '=', 1]])->first();
        $viewEmployeeGsspVeriDetail = EmployeeGsspDocuments::where([['emp_code', '=', $employee->emp_code]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeGsspVeriDetail', compact('viewEmployeeGsspVeriDetail'));
    }

    public function viewEmployeeCnicCopy()
    {
        $array = explode('|', Input::get('id'));
        $emp_code = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id', 'emp_code', 'emp_name', 'cnic_path', 'cnic_name', 'cnic_type')->where([['emp_code', '=', $emp_code], ['status', '=', 1], ['cnic_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeCnicCopy', compact('employee'));
    }

    public function viewEmployeeEobiCopy()
    {
        $array = explode('|', Input::get('id'));
        $emp_code = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id', 'emp_code', 'emp_name', 'eobi_path', 'eobi_type')->where([['emp_code', '=', $emp_code], ['status', '!=', 2], ['eobi_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeEobiCopy', compact('employee'));
    }

    public function viewAdvanceSalaryDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $advance_salary = AdvanceSalary::select('*')->where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAdvanceSalaryDetail', compact('advance_salary'));
    }

    public function viewAllowanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowance = Allowance::where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAllowanceDetail', compact('allowance'));
    }

    public function viewDeductionDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $deduction = Deduction::where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDeductionDetail', compact('deduction'));
    }

    public function viewHolidaysDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHolidaysDetail', compact('holidays'));
    }

    public function viewAttendanceProgress()
    {
        $emp_code = Input::get('emp_code');
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        $month_year = explode('-', Input::get('month_year'));

        if($emp_code == 'All'):
            $emp_code = '';
        endif;

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if (!empty($department_id)) $query_string_second_part[] = " AND emp_department_id = '$department_id'";
        if (!empty($region_id)) $query_string_second_part[] = " AND region_id = '$region_id'";
        if (!empty($emp_code)) $query_string_second_part[] = " AND emp_code = '$emp_code'";
        $query_string_second_part[] = " AND status = 1";
        $query_string_First_Part = "SELECT id, emp_department_id, emp_code, emp_name, emp_father_name, emp_salary, designation_id,
        emp_contact_no, region_id, emp_joining_date, emp_cnic, emp_date_of_birth, eobi_id, status FROM employee WHERE";
        $query_string_third_part = ' ORDER BY emp_code';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;

        $employees = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewAttendanceProgress', compact('employees', 'month_year'));
    }

    public function viewEmployeeDetail()
    {
        $currentDate = date('Y-m-d');
        $id = Input::get('id');
        $CompanyId = Input::get('m');
        $location_id = '';

        CommonHelper::companyDatabaseConnection($CompanyId);
        $employee_detail = Employee::where([['id', '=', $id]])->first();
        $employee_family_detail = EmployeeFamilyData::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_bank_detail = EmployeeBankData::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_educational_detail = EmployeeEducationalData::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_language_proficiency = EmployeeLanguageProficiency::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_health_data = EmployeeHealthData::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_activity_data = EmployeeActivityData::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_work_experience = EmployeeWorkExperience::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_reference_data = EmployeeReferenceData::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_kins_data = EmployeeKinsData::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_relatives_data = EmployeeRelativesData::where([['emp_code', '=', $employee_detail->emp_code]]);
        $employee_other_details = EmployeeOtherDetails::where([['emp_code', '=', $employee_detail->emp_code]]);
        // $get_employee_location = EmployeeTransfer::where('emp_code','=',$employee_detail->emp_code)->orderBy('id', 'desc')->first();

        if ($employee_detail->can_login == 'yes'):

            $login_credentials = DB::Table('users')->select('acc_type')->where([['company_id', '=', Input::get('m')], ['emp_id', '=', $employee_detail->id]]);
        endif;

        CommonHelper::reconnectMasterDatabase();

        $login_credentials = '';
        $subdepartments = new SubDepartment;
        $leaves_policy = LeavesPolicy::where([['status', '=', '1']])->get();
        $jobtype = JobType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $qualification = Qualification::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $eobi = Eobi::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        //$tax= Tax::select('id','tax_name')->where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        // $employee_grades = Grades::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_locations = Locations::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewEmployeeDetail'
            , compact('login_credentials', 'employee_other_details', 'employee_relatives_data', 'employee_kins_data', 'employee_reference_data', 'employee_work_experience', 'employee_activity_data', 'employee_health_data', 'employee_language_proficiency', 'employee_educational_detail', 'employee_bank_detail', 'employee_family_detail', 'leaves_policy', 'employee_detail', 'DegreeType', 'tax', 'eobi', 'designation', 'qualification', '
            leaves_policy', 'departments', 'subdepartments', 'jobtype', 'marital_status',
                'employee_regions', 'employee_locations',
                'employee_category'));
    }

    public function viewLoanRequestDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loanRequest = LoanRequest::where([['id', '=', Input::get('id')], ['status', '=', 1]])->first();
        $paid_amount = DB::table("payslip")
            ->select(DB::raw("SUM(loan_amount) as paid_amount"))
            ->where([['emp_code', '=', $loanRequest->emp_code], ['loan_id', '=', $loanRequest->id]])
            ->first();
        $loan_Detail = DB::table('payslip')
            ->select('loan_amount', 'date', 'month', 'year')
            ->where([['loan_id', '=', $loanRequest->id], ['emp_code', '=', $loanRequest->emp_code]])
            ->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewLoanRequestDetail', compact('loanRequest', 'paid_amount', 'operation_rights2', 'loan_Detail'));
    }

    public function viewRangeWiseLeaveApplicationsRequests()
    {
        $region_id = Input::get('region_id');
        $emp_department_id = Input::get('emp_department_id');
        $m = Input::get('m');
        $gm_Approvals = Input::get('gm_Approvals');
        $all_employee = HrHelper::getAllEmployeeId($m, $emp_department_id, $region_id);
        if (input::get('employee_id') == 'All' && $gm_Approvals == 1):
            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.view', '=', 'yes'], ['leave_application.status', '=', 1], ['leave_application.approval_status_m', '=', Input::get('LeavesStatus')]])
                ->whereIn('leave_application.emp_code', $all_employee)
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'), Input::get('toDate')])
                ->orwhere([['first_second_half_date', '>=', Input::get('fromDate')], ['first_second_half_date', '<=', Input::get('toDate')],
                    ['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.view', '=', 'yes']])
                ->get();

        elseif (input::get('employee_id') == 'All' && $gm_Approvals != 1):

            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.view', '=', 'yes'], ['leave_application.status', '=', 1]])
                ->whereIn('leave_application.emp_code', $all_employee)
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'), Input::get('toDate')])
                ->orwhere([['first_second_half_date', '>=', Input::get('fromDate')], ['first_second_half_date', '<=', Input::get('toDate')],
                    ['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.view', '=', 'yes']])
                ->get();
        elseif (input::get('employee_id') != 'All' && $gm_Approvals == 1):

            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.view', '=', 'yes'], ['leave_application.emp_code', '=', Input::get('employee_id')], ['leave_application.status', '=', 1], ['leave_application.approval_status_m', '=', Input::get('LeavesStatus')]])
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'), Input::get('toDate')])
                ->orwhere([['first_second_half_date', '>=', Input::get('fromDate')], ['first_second_half_date', '<=', Input::get('toDate')],
                    ['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.view', '=', 'yes']])
                ->get();
        elseif (input::get('employee_id') != 'All' && $gm_Approvals != 1):

            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.view', '=', 'yes'], ['leave_application.emp_code', '=', Input::get('employee_id')], ['leave_application.status', '=', 1]])
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'), Input::get('toDate')])
                ->orwhere([['first_second_half_date', '>=', Input::get('fromDate')], ['first_second_half_date', '<=', Input::get('toDate')],
                    ['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.view', '=', 'yes']])
                ->get();
        else:
            $leave_application_request_list = DB::table('leave_application')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.emp_code', '=', Input::get('employee_id')], ['leave_application.view', '=', 'yes'], ['leave_application.status', '=', 1]])
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'), Input::get('toDate')])
                ->orwhere([['first_second_half_date', '>=', Input::get('fromDate')], ['first_second_half_date', '<=', Input::get('toDate')],
                    ['leave_application.approval_status', '=', Input::get('LeavesStatus')], ['leave_application.emp_code', '=', Input::get('employee_id')], ['leave_application.view', '=', 'yes']])->get();
        endif;

        return view('Hr.AjaxPages.viewRangeWiseLeaveApplicationsRequests', compact('leave_application_request_list'));
    }

    public function viewAbsentAndLeaveApplicationDetail()
    {
        $data = explode(',', Input::get('id'));
        $emp_code = $data[0];
        $month = $data[2];
        $year = $data[1];
        $leave_dates_array = [];
        $total_month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $from_date = $month . '-01-' . $year;
        $to_date = $month . '-' . $total_month_days . '-' . $year;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $monthly_holidays[] = '';
        $holidays = Holidays::select('holiday_date', 'holiday_name')->where([['month', '=', $month], ['year', '=', $year], ['status', '=', 1]]);
        if ($holidays->count() > 0):
            foreach ($holidays->get() as $value2):
                $monthly_holidays[] = $value2['holiday_date'];
            endforeach;
        else:
            $monthly_holidays = array();
        endif;

        $attendance = Attendance::where([['emp_code', '=', $emp_code], ['month', '=', $month], ['year', '=', $year], ['status', '=', 1]])->get();
        CommonHelper::reconnectMasterDatabase();
        foreach ($attendance as $value):
            $LikeDate = "'" . '%' . $year . "-" . $month . '%' . "'";

            $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date,leave_application_data.no_of_days from leave_application
                                            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                                            WHERE leave_application_data.from_date LIKE ' . $LikeDate . ' AND leave_application_data.emp_code = ' . $value->emp_code . '
                                            AND leave_application.status = 1 AND leave_application.approval_status = 2 AND leave_application.approval_status_m = 2 AND leave_application.view = "yes"
                                            OR leave_application_data.first_second_half_date LIKE ' . $LikeDate . ' and leave_application_data.emp_code = ' . $value->emp_code . '');

            $leaves_from_dates2 = [];
            if (!empty($leave_application_request_list)):
                foreach ($leave_application_request_list as $value3):
                    $leaves_from_dates = $value3->from_date;
                    $leaves_to_dates = $value3->to_date;
                    $leaves_type = $value3->leave_type;
                    $leaves_from_dates2[] = $value3->from_date;

                    $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates . '+1 day'));
                    foreach ($period as $date):
                        $leave_dates_array[] = $date->format("Y-m-d");
                    endforeach;
                endforeach;
            endif;
        endforeach;

        $monthly_holidays = array_merge($monthly_holidays, $leave_dates_array);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $absent_dates = Attendance::select("emp_code", "attendance_date", "clock_in", "clock_out", "attendance_status")
            ->where([['emp_code', '=', $emp_code], ['attendance_status', '=', 2], ['status', '=', 1]])
            ->whereNotIn('attendance_date', $monthly_holidays);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewAbsentAndLeaveApplicationDetail', compact('absent_dates', 'holidays', 'leave_application_request_list'));
    }

    public function  viewLateArrivalDetail()
    {
        $data = explode(',', Input::get('id'));
        $emp_code = $data[0];
        $month = $data[2];
        $year = $data[1];
        $total_month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $from_date = $month . '-01-' . $year;
        $to_date = $month . '-' . $total_month_days . '-' . $year;
        $grace_time = '10:00:00';
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance = Attendance::where([['emp_code', '=', $emp_code],
            ['month', '=', $month], ['year', '=', $year], ['attendance_status', '=', 1], ['status', '=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewLateArrivalDetail', compact('attendance', 'grace_time'));
    }

    public function viewOvertimeHoursDetail()
    {
        $data = explode(',', Input::get('id'));
        $emp_code = $data[0];
        $month = $data[2];
        $year = $data[1];
        $total_month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $from_date = $month . '-01-' . $year;
        $to_date = $month . '-' . $total_month_days . '-' . $year;

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance = Attendance::where([['emp_code', '=', $emp_code],
            ['month', '=', $month], ['year', '=', $year], ['attendance_status', '=', 1], ['status', '=', 1]]);

        $totalOffDates = [];
        $days_off = Attendance::select('attendance_date')->where([['day','=','Sun'],['emp_code','=',$emp_code],
            ['month', '=', $month], ['year', '=', $year]]);

        if($days_off->count() > 0):
            foreach($days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;
        else:
            $totalOffDates = array();
        endif;

        $monthly_holidays = [];
        $holidays = Holidays::select('holiday_date')->where([['month', '=', $month],['year','=', $year],['status','=',1]]);

        if($holidays->count() > 0):
            foreach ($holidays->get() as $value2):
                $monthly_holidays[] = $value2['holiday_date'];
            endforeach;
        else:
            $monthly_holidays = array();
        endif;

        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);

        $off_days_attendance = Attendance::where([['emp_code', '=', $emp_code],
            ['month', '=', $month], ['year', '=', $year], ['attendance_status', '=', 1], ['status', '=', 1]])
            ->whereIn('attendance_date',$monthly_holidays);

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewOvertimeHoursDetail', compact('attendance', 'total_month_days','off_days_attendance'));
    }

    public function viewAttendanceReport()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $month_year = explode('-', Input::get('month_year'));
        $emp_code = Input::get('emp_code');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance_check = Attendance::select('attendance_type')->where([['emp_code', '=', $emp_code], ['year', '=', $month_year[0]], ['month', '=', $month_year[1]]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDayWiseAttendanceReport', compact('attendance_check', 'emp_code', 'month_year', 'operation_rights2'));
    }

    public function viewExpiryAndUpcomingAlerts()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $expireDateOne = date('Y-m-d', strtotime(now() . '+1 month'));
        $settlementDate = date('Y-m-d', strtotime(now() . '+2 days'));
        $cnic_expiry_date_count = Employee::where([['status', '=', 1], ['emp_cnic_expiry_date', '<', $expireDateOne], ['emp_cnic_expiry_date', '>', date('Y-m-d')], ['emp_cnic_expiry_date', '!=', '']])->count();
        $upcoming_birthday_count = DB::select(DB::raw("SELECT count('emp_date_of_birth') as upcoming_birthday_count  FROM employee where DATE_FORMAT(emp_date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +1 month), '%m-%d') "));
        $over_age_employee_count = DB::select(DB::raw('SELECT count("emp_date_of_birth") as over_age_employee_count FROM employee WHERE status=1 and DATEDIFF(NOW(), emp_date_of_birth) / 365.25 >= 60'));
        $employee_missing_images = Employee::where([['status', '=', 1], ['img_path', '=', 'app/uploads/employee_images/user-dummy.png']])->count();

        //$settlementTermination1 = HrTerminationFormat1Letter::where([['status', '=', 1], ['settlement_date', '<', $settlementDate], ['settlement_date', '>', date('Y-m-d')], ['settlement_date', '!=', '']])->count();
        //$settlementTermination2 = HrTerminationFormat2Letter::where([['status', '=', 1], ['settlement_date', '<', $settlementDate], ['settlement_date', '>', date('Y-m-d')], ['settlement_date', '!=', '']])->count();
        //$settlementContract = HrContractConclusionLetter::where([['status', '=', 1], ['settlement_date', '<', $settlementDate], ['settlement_date', '>', date('Y-m-d')], ['settlement_date', '!=', '']])->count();

//        $employee_settlement = ($settlementTermination1 + $settlementTermination2 + $settlementContract);
        $nadra = EmployeeGsspDocuments::select('emp_code')->where([['document_type', '=', 'Nadra']]);
        if ($nadra->count()):
            $nonVerfiedNadraEmp = $nadra->get()->toArray();
            $non_verified_nadra_count = Employee::select('emp_code', 'emp_name')->whereNotIn('emp_code', $nonVerfiedNadraEmp)->count();
        else:
            $non_verified_nadra_count = 0;
        endif;

        $police = EmployeeGsspDocuments::select('emp_code')->where([['document_type', '=', 'Police']]);
        if ($police->count()):
            $nonVerfiedNadraEmp = $police->get()->toArray();
            $non_verified_police_count = Employee::select('emp_code', 'emp_name')->whereNotIn('emp_code', $nonVerfiedNadraEmp)->count();
        else:
            $non_verified_police_count = 0;
        endif;
        $warning_letter = HrWarningLetter::all()->count();
        $demiseEmployees = EmployeeExit::where([['leaving_type', '=', 5]])->count();
        $employeeProbationPeriodOverDetail = DB::select(DB::raw("SELECT count('emp_code') as totalOverProbationEmp FROM employee WHERE emp_employementstatus_id = '8' AND status = '1' AND emp_joining_date <= DATE_ADD('" . date("Y-m-d") . "',INTERVAL -6 MONTH)"));

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewExpiryAndUpcomingAlerts', compact('totalAuditHrEmp', 'employee_settlement', 'employee_missing_eobi', 'employee_missing_insurance', 'employeeProbationPeriodOverDetail', 'demiseEmployees', 'warning_letter', 'employee_missing_images', 'non_verified_police_count', 'non_verified_nadra_count', 'cnic_expiry_date_count', 'upcoming_birthday_count', 'over_age_employee_count'));
    }

    public function viewUpcomingBirthdaysDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $upcoming_birthdays_detail = DB::select(DB::raw("SELECT id,emp_code,emp_name,emp_date_of_birth FROM employee where DATE_FORMAT(emp_date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +1 month), '%m-%d') ORDER BY MONTH(emp_date_of_birth), DAYOFMONTH(emp_date_of_birth)"));
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewUpcomingBirthdaysDetail', compact('upcoming_birthdays_detail'));
    }

    public function viewEmployeeCnicExpireDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $expireDateOne = date('Y-m-d', strtotime(now() . '+1 month'));
        $cnic_expiry_date_detail = Employee::select('id', 'emp_name', 'emp_cnic', 'emp_code', 'emp_cnic_expiry_date')
            ->where([['status', '=', 1], ['emp_cnic_expiry_date', '<', $expireDateOne], ['emp_cnic_expiry_date', '>', date('Y-m-d')], ['emp_cnic_expiry_date', '!=', '']]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeCnicExpireDetail', compact('cnic_expiry_date_detail'));
    }

    public function viewEmployeeOverAgeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $over_age_employee_detail = DB::select(DB::raw("SELECT id,emp_code,emp_date_of_birth,emp_name FROM employee WHERE status=1 and DATEDIFF(NOW(), emp_date_of_birth) / 365.25 >= 60"));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeOverAgeDetail', compact('over_age_employee_detail'));
    }

    public function viewNonVerifiedNadraEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $nadra = EmployeeGsspDocuments::select('emp_code')->where([['document_type', '=', 'Nadra']]);
        if ($nadra->count()):
            $nonVerfiedNadraEmp = $nadra->get()->toArray();
            $nonVerfiedNadraEmpDetail = Employee::select('emp_code', 'emp_name', 'employee_category_id')->whereNotIn('emp_code', $nonVerfiedNadraEmp)->get()->toArray();
        else:
            $nonVerfiedNadraEmpDetail = array();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewNonVerifiedNadraEmployeeDetail', compact('nonVerfiedNadraEmpDetail'));
    }

    public function viewNonVerifiedPoliceEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $police = EmployeeGsspDocuments::select('emp_code')->where([['document_type', '=', 'Police']]);
        if ($police->count()):
            $nonVerfiedPoliceEmp = $police->get()->toArray();
            $nonVerfiedPoliceEmpDetail = Employee::select('emp_code', 'emp_name')->whereNotIn('emp_code', $nonVerfiedPoliceEmp)->get()->toArray();
        else:
            $nonVerfiedPoliceEmpDetail = array();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewNonVerifiedPoliceEmployeeDetail', compact('nonVerfiedPoliceEmpDetail'));
    }

    public function viewEmployeeMissingImageDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_images = Employee::select('emp_name', 'emp_code')->where([['status', '=', 1], ['img_path', '=', 'app/uploads/employee_images/user-dummy.png']]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeMissingImageDetail', compact('employee_missing_images'));
    }

    public function viewEmployeeWarningLetterDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $warningLetters = HrWarningLetter::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeWarningLetterDetail', compact('warningLetters'));
    }

    public function viewDemiseEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $DemiseEmployee = EmployeeExit::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDemiseEmployeeDetail', compact('DemiseEmployee'));
    }

    public function viewEmployeeProbationPeriodOverDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $probationEmployees = DB::select(DB::raw("SELECT emp_code,emp_joining_date,emp_name FROM employee
         WHERE emp_employementstatus_id = '8' AND status = '1'
         AND emp_joining_date <= DATE_ADD('" . date("Y-m-d") . "',INTERVAL -6 MONTH)"));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeProbationPeriodOverDetail', compact('probationEmployees'));
    }

    public function viewEmployeePreviousPromotionsDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_promotion = EmployeePromotion::where([['emp_code', '=', Input::get('emp_code')],['approval_status', '=', 2], ['status', '=', 1]])->orderBy('id','desc');
        $employee = Employee::where([['emp_code', '=', Input::get('emp_code')], ['status', '=', 1]])->select('emp_salary','emp_joining_date','designation_id')->first();

        if($employee_promotion->count() > 0):
            $employee_promotion = $employee_promotion->first();
            $salary = $employee_promotion->salary;
            $date = $employee_promotion->promotion_date;
        else:
            $salary = $employee->emp_salary;
            $date = $employee->emp_joining_date;
        endif;

        $last_designation = EmployeePromotion::where([['emp_code', '=', Input::get('emp_code')],['designation_id', '!=', ''],
            ['approval_status', '=', 2], ['status', '=', 1]])->select('designation_id')->orderBy('id','desc');

        if($last_designation->count() > 0):
            $last_designation = $last_designation->first();
            $designation_id = $last_designation->designation_id;
        else:
            $designation_id = $employee->designation_id;
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePreviousPromotionsDetail', compact('salary', 'date','designation_id'));
    }

    public function viewDayWiseAttendanceDetail()
    {
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        $emp_code = Input::get('emp_code');
        $attendance_date = Input::get('attendance_date');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if ($emp_code == 'All'):
            $employees = Employee::select("emp_code", "emp_name")
                ->where([['status', '=', '1'], ['emp_department_id', '=', $department_id], ['region_id', '=', $region_id]])->orderBy('emp_code')->get();
        else:
            $employees = Employee::select("emp_code", "emp_name")
                ->where([['status', '=', '1'], ['emp_department_id', '=', $department_id], ['region_id', '=', $region_id],['emp_code', '=', $emp_code]])->orderBy('emp_code')->get();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDayWiseAttendanceDetail', compact('employees','attendance_date'));
    }

    public function viewEmployeePromotionsDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeePromotions = EmployeePromotion::where([['emp_code','=',Input::get('emp_code')],['status','=',1]])->orderBy('id','desc');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePromotionsDetail', compact('employeePromotions','operation_rights2'));
    }

    public function viewPromotionLetter()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $promotion_letter = PromotionLetter::where('promotion_id', '=', $id);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewPromotionLetter', compact('promotion_letter'));
    }

    public function viewEmployeesBonus()
    {
        $emp_code = Input::get('emp_code');
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        $month_year = explode('-', Input::get('bonus_month_year'));

        if($emp_code == 'All'):
            $emp_code = '';
        endif;

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if (!empty($department_id)) $query_string_second_part[] = " AND emp_department_id = '$department_id'";
        if (!empty($region_id)) $query_string_second_part[] = " AND region_id = '$region_id'";
        if (!empty($emp_code)) $query_string_second_part[] = " AND emp_code = '$emp_code'";
        $query_string_second_part[] = " AND status = 1";
        $query_string_First_Part = "SELECT id, emp_department_id, emp_code, emp_name, emp_father_name, emp_salary, designation_id,
            region_id, status FROM employee WHERE";
        $query_string_third_part = ' ORDER BY emp_code';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;

        $employees = DB::select(DB::raw($query_string));

        $get_percent = Bonus::select('percent_of_salary')->where([['id', '=', Input::get('bonus_id')]])->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeesBonus', compact('employees', 'get_percent', 'month_year'));
    }

    public function viewEmployeePayrollForm()
    {
        $emp_code = Input::get('emp_code');
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        $month_year = explode('-', Input::get('month_year'));

        if($emp_code == 'All'):
            $emp_code = '';
        endif;
        $account = CommonHelper::get_all_bank_account();
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if (!empty($department_id)) $query_string_second_part[] = " AND emp_department_id = '$department_id'";
        if (!empty($region_id)) $query_string_second_part[] = " AND region_id = '$region_id'";
        if (!empty($emp_code)) $query_string_second_part[] = " AND emp_code = '$emp_code'";
        $query_string_second_part[] = " AND status = 1";
        $query_string_First_Part = "SELECT id, emp_department_id, emp_code, emp_name, emp_father_name, emp_salary, designation_id,
        emp_contact_no, region_id, emp_joining_date, emp_cnic, emp_date_of_birth, eobi_id, status FROM employee WHERE";
        $query_string_third_part = ' ORDER BY emp_code';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;

        $employees = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeePayrollForm', compact('employees','region_id', 'month_year','account'));
    }

    public function viewPayrollReport()
    {
        $emp_code = Input::get('emp_code');
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        $month_year = explode('-', Input::get('month_year'));
        $m = Input::get('m');

        if($emp_code == 'All'):
            $emp_code = '';
        endif;

        if($department_id == 'All'):
            $department_id = '';
        endif;

        if (!empty($department_id)) $query_string_second_part[] = " AND id = '$department_id'";
        $query_string_second_part[] = " AND status = '1'";
        $query_string_second_part[] = " AND company_id = '$m'";
        $query_string_First_Part = "SELECT * FROM department WHERE";
        $query_string_third_part = ' ORDER BY id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $departments = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewPayrollReport', compact('emp_code','region_id','departments','month_year','m'));
    }

    public function companyWisePayrollReport()
    {
        $month_year = explode('-', Input::get('month_year'));
        $companiesList = DB::Table('company')->select('id', 'name')->where([['status', '=', 1]])->get()->toArray();
        return view('Hr.AjaxPages.companyWisePayrollReport', compact('companiesList', 'month_year'));
    }

    public function viewEmployeePayrollList()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $emp_code = Input::get('emp_code');
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        $month_year = explode('-', Input::get('month_year'));

        if($emp_code == 'All'):
            $emp_code = '';
        endif;

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if (!empty($department_id)) $query_string_second_part[] = " AND emp_department_id = '$department_id'";
        if (!empty($region_id)) $query_string_second_part[] = " AND region_id = '$region_id'";
        if (!empty($emp_code)) $query_string_second_part[] = " AND emp_code = '$emp_code'";
        $query_string_second_part[] = " AND status = 1";
        $query_string_First_Part = "SELECT id, emp_department_id, emp_code, emp_name, emp_father_name, emp_salary, designation_id,
        region_id, emp_joining_date, emp_cnic, eobi_id, status FROM employee WHERE";
        $query_string_third_part = ' ORDER BY emp_code';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;

        $employees = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePayrollList', compact('employees', 'month_year', 'operation_rights2'));
    }

    public function viewAllowanceForm()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $emp_code = Input::get('emp_code');
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        $allowance_type = Input::get('allowance_type');
        $m = Input::get('m');

        if($emp_code == 'All'):
            $emp_code = '';
        endif;

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if (!empty($department_id)) $query_string_second_part[] = " AND emp_department_id = '$department_id'";
        if (!empty($region_id)) $query_string_second_part[] = " AND region_id = '$region_id'";
        if (!empty($emp_code)) $query_string_second_part[] = " AND emp_code = '$emp_code'";
        $query_string_second_part[] = " AND status = 1";
        $query_string_First_Part = "SELECT emp_code, emp_name FROM employee WHERE";
        $query_string_third_part = ' ORDER BY emp_code';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;

        $employees = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAllowanceForm', compact('employees', 'allowance_type', 'operation_rights2','m'));
    }

    public function viewHolidayCalender()
    {
        $month_year = Input::get('month_year');
        $m = Input::get('m');
        return view('Hr.AjaxPages.viewHolidayCalender', compact('month_year','m'));
    }

    //new code end


    public function viewPayrollReceivingReport()
    {
        $month_year = Input::get('month_year');
        $explodeMonthYear = explode('-', $month_year);
        if (Input::get('company_id') == 'All'):

            $companiesList = DB::Table('company')->select('id', 'name')->orderBy('order_by_no', 'asc')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id', 'name')->where([['id', '=', Input::get('company_id')]])->get()->toArray();

        endif;

        return view('Hr.AjaxPages.viewPayrollReceivingReport', compact('companiesList', 'explodeMonthYear'));
    }


    public function viewHiringRequestDetail()
    {

        $array[1] = '<span class="label label-warning">Pending</span>';
        $array[2] = '<span class="label label-success">Approved</span>';
        $array[3] = '<span class="label label-danger">Rejected</span>';
        $array1[1] = "<span class='label label-success'>Active</span>";
        $array1[2] = "<span class='label label-danger'>Deleted</span>";

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hiringRequestDetail = RequestHiring::where([['id', '=', Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $data['hiringRequestDetail'] = $hiringRequestDetail;
        $data['status'] = $array1[$hiringRequestDetail->status];
        $data['approval_status'] = $array[$hiringRequestDetail->ApprovalStatus];
        return view('Hr.AjaxPages.viewHiringRequestDetail', $data);

        /*<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.innovative-net.com/&display=popup" target="_blank"> share this facebook </a>*/

    }

    public function viewLeavePolicyDetail()
    {

        //CommonHelper::companyDatabaseConnection(Input::get('m'));
        $leavesPolicy = LeavesPolicy::where([['id', '=', Input::get('id')]])->first();
        $leavesData = LeavesData::where([['leaves_policy_id', '=', Input::get('id')]])->get();

        return view('Hr.AjaxPages.viewLeavePolicyDetail', compact('leavesPolicy', 'leavesData'));

    }


    public function viewCarPolicyCriteria()
    {
        if (Input::get('sub_department_id') == 'all'):

            $allsubDeparments = SubDepartment::select('id', 'sub_department_name', 'department_id')->where([['status', '=', '1'], ['company_id', '=', Input::get('m')]])->get();
        else:
            $allsubDeparments = SubDepartment::select('id', 'sub_department_name', 'department_id')->where([['id', '=', Input::get('sub_department_id')], ['status', '=', '1'], ['company_id', '=', Input::get('m')]])->get();
        endif;

        return view('Hr.AjaxPages.viewCarPolicyCriteria', compact('allsubDeparments'));
    }


    public function  viewCarPolicy()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicyData = CarPolicy::where([['id', '=', Input::get('id')], ['status', '=', '1']])->first();
        return view('Hr.AjaxPages.viewCarPolicy', compact('carPolicyData'));
    }


    public function viewTaxCriteria()
    {
        if (Input::get('sub_department_id') == 'all'):

            $allsubDeparments = SubDepartment::select('id', 'sub_department_name', 'department_id')->where([['status', '=', '1'], ['company_id', '=', Input::get('m')]])->get();
        else:
            $allsubDeparments = SubDepartment::select('id', 'sub_department_name', 'department_id')->where([['id', '=', Input::get('sub_department_id')], ['status', '=', '1'], ['company_id', '=', Input::get('m')]])->get();
        endif;
        return view('Hr.AjaxPages.viewTaxCriteria', compact('allsubDeparments'));

    }

    public function viewTax()
    {
        $tax = Tax::where([['id', '=', Input::get('id')], ['company_id', '=', Input::get('m')]])->first();
        return view('Hr.AjaxPages.viewTax', compact('tax'));
    }



    public function viewLeaveApplicationDetail()
    {
        $leave_day_type = Input::get('leave_day_type');

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $emp = Employee::select('id', 'leaves_policy_id', 'designation_id', 'emr_no')->where('emr_no', '=', Auth::user()->emr_no)->first();
        CommonHelper::reconnectMasterDatabase();

        if (Input::get('leave_day_type') == 1):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.leave_address', 'leave_application.emr_no', 'leave_application.approval_status', 'leave_application.reason', 'leave_application_data.no_of_days', 'leave_application_data.date', 'leave_application_data.from_date', 'leave_application_data.to_date')
                ->where([['leave_application_data.leave_application_id', '=', Input::get('id')], ['leave_application_data.leave_day_type', '=', Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];


        elseif (Input::get('leave_day_type') == 2):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.leave_address', 'leave_application.emr_no', 'leave_application.approval_status', 'leave_application.reason', 'leave_application_data.first_second_half', 'leave_application_data.date', 'leave_application_data.first_second_half_date')
                ->where([['leave_application_data.leave_application_id', '=', Input::get('id')], ['leave_application_data.leave_day_type', '=', Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        else:
            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.leave_address', 'leave_application.emr_no', 'leave_application.approval_status', 'leave_application.reason', 'leave_application_data.short_leave_time_from', 'leave_application_data.short_leave_time_to', 'leave_application_data.date', 'leave_application_data.short_leave_date')
                ->where([['leave_application_data.leave_application_id', '=', Input::get('id')], ['leave_application_data.leave_day_type', '=', Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        endif;


        $approval_array[1] = '<span class="label label-warning">Pending</span>';
        $approval_array[2] = '<span class="label label-success">Approved</span>';
        $approval_array[3] = '<span class="label label-danger">Rejected</span>';

        $approval_status = $approval_array[$leave_application_data->approval_status];

        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
            ->get();

        $total_leaves = DB::table("leaves_data")
            ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
            ->where([['leaves_policy_id', '=', $leaves_policy[0]->leaves_policy_id]])
            ->first();


        $taken_leaves = DB::table("leave_application_data")
            ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.emr_no', '=', Input::get('user_id')], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '=', '2']])
            ->first();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('emp_name', 'emp_sub_department_id', 'designation_id', 'emr_no')->where([['id', '=', $emp->id]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation_name = Designation::where([['id', '=', $emp->designation_id]])->value('designation_name');
        $getCurrentLeavePolicyYear = date('Y', strtotime($leaves_policy[0]->policy_date_from));
        $date = strtotime($getCurrentLeavePolicyYear . ' -1 year');
        $getPreviousLeavePolicyYear = date('Y', $date);
        $getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear . '%');
        $getPreviousUsedAnnualLeavesBalance = 0;
        $getPreviousUsedCasualLeavesBalance = 0;
        if ($getPreviousLeavePolicy->count() > 0):
            // print_r($getPreviousLeavePolicyId->first()->id);
            $getPreviousLeavePolicyId = $getPreviousLeavePolicy->first();

            $getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id', '=', 1], ['leaves_policy_id', '=', $getPreviousLeavePolicyId->id]])->value('no_of_leaves');
            $getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id', '=', 3], ['leaves_policy_id', '=', $getPreviousLeavePolicyId->id]])->value('no_of_leaves');

            $getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
                ->select(DB::raw("SUM(no_of_days) as no_of_days"))
                ->where([['emr_no', '=', Input::get('emr_no')], ['leave_policy_id', '=', $getPreviousLeavePolicyId->id], ['leave_type', '=', '1']])
                ->first();
            $getPreviousUsedCasualLeaves = DB::table("leave_application_data")
                ->select(DB::raw("SUM(no_of_days) as no_of_days"))
                ->where([['emr_no', '=', Input::get('emr_no')], ['leave_policy_id', '=', $getPreviousLeavePolicyId->id], ['leave_type', '=', '3']])
                ->first();

            $getPreviousUsedAnnualLeavesBalance = $getPreviousAnnualLeaves - $getPreviousUsedAnnualLeaves->no_of_days;
            $getPreviousUsedCasualLeavesBalance = $getPreviousCasualLeaves - $getPreviousUsedCasualLeaves->no_of_days;

        endif;

        $data['getPreviousUsedAnnualLeavesBalance'] = $getPreviousUsedAnnualLeavesBalance;
        $data['getPreviousUsedCasualLeavesBalance'] = $getPreviousUsedCasualLeavesBalance;
        $data['total_leaves'] = $total_leaves;
        $data['taken_leaves'] = $taken_leaves;
        $data['designation_name'] = $designation_name;
        $data['leave_day_type'] = $leave_day_type;
        $data['leave_application_data'] = $leave_application_data;
        $data['approval_status'] = $approval_status;

        $data['leave_type_name'] = Input::get('leave_type_name');
        $data['leave_day_type_label'] = $leave_day_type_label;
        $data['leaves_policy'] = $leaves_policy;
        return view('Hr.AjaxPages.viewLeaveApplicationDetail')->with($data);
    }

    public function viewLeaveApplicationRequestDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp = Employee::select('id', 'leaves_policy_id', 'designation_id')->where([['emp_code', '=', Input::get('user_id')]])->first();
        CommonHelper::reconnectMasterDatabase();

        $leave_day_type = Input::get('leave_day_type');

        if (Input::get('leave_day_type') == 1):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                //->join('employee', 'leave_application.emp_id', '=', 'employee.acc_no')
                ->select('leave_application.emp_code', 'leave_application.leave_address', 'leave_application.approval_status', 'leave_application.approval_status_m', 'leave_application.approval_status_hd', 'leave_application.reason', 'leave_application.status', 'leave_application_data.no_of_days', 'leave_application_data.date', 'leave_application_data.from_date', 'leave_application_data.to_date')
                ->where([['leave_application_data.leave_application_id', '=', Input::get('id')], ['leave_application_data.leave_day_type', '=', Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];
        elseif (Input::get('leave_day_type') == 2):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                //->join('employee', 'leave_application.emp_id', '=', 'employee.acc_no')
                ->select('leave_application.emp_code', 'leave_application.leave_address', 'leave_application.approval_status_hd_name', 'leave_application.approval_status', 'leave_application.approval_status_m', 'leave_application.approval_status_hd', 'leave_application.reason', 'leave_application.status', 'leave_application_data.first_second_half', 'leave_application_data.date', 'leave_application_data.first_second_half_date')
                ->where([['leave_application_data.leave_application_id', '=', Input::get('id')], ['leave_application_data.leave_day_type', '=', Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        else:
            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                //->join('employee', 'leave_application.emp_id', '=', 'employee.acc_no')
                ->select('leave_application.emp_code', 'leave_application.approval_status', 'leave_application.approval_status_m', 'leave_application.approval_status_hd', 'leave_application.leave_address', 'leave_application.reason',
                    'leave_application.status', 'leave_application_data.short_leave_time_from', 'leave_application_data.short_leave_time_to',
                    'leave_application_data.date', 'leave_application_data.short_leave_date')
                ->where([['leave_application_data.leave_application_id', '=', Input::get('id')], ['leave_application_data.leave_day_type', '=', Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        endif;

        $approval_array[1] = '<span class="label label-warning">Pending</span>';
        $approval_array[2] = '<span class="label label-success">Approved</span>';
        $approval_array[3] = '<span class="label label-danger">Rejected</span>';

        $approval_status = $approval_array[$leave_application_data->approval_status];
        $approval_status_m = $approval_array[$leave_application_data->approval_status_m];
        $approval_status_hd = $approval_array[$leave_application_data->approval_status_hd];
        CommonHelper::reconnectMasterDatabase();

        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
            ->get();


        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('emp_name', 'emp_department_id', 'designation_id', 'emp_code', 'leaves_policy_id')->where([['id', '=', $emp->id]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation_name = Designation::where([['id', '=', $emp->designation_id]])->value('designation_name');
        $leave_type_name = Input::get('leave_type_name');

        $data['designation_name'] = $designation_name;
        $data['leave_day_type'] = $leave_day_type;
        $data['leave_application_data'] = $leave_application_data;
        $data['approval_status'] = $approval_status;
        $data['approval_status_m'] = $approval_status_m;
        $data['approval_status_hd'] = $approval_status_hd;
        $data['emp_data'] = $emp_data;
        $data['leave_type_name'] = Input::get('leave_type_name');
        $data['leave_day_type_label'] = $leave_day_type_label;
        $data['leaves_policy'] = $leaves_policy;

        return view('Hr.AjaxPages.viewLeaveApplicationRequestDetail', compact('designation_name', 'leave_day_type', 'leave_application_data', 'approval_status', 'approval_status_m', 'emp_data', 'leave_type_name', 'leave_day_type_label', 'leaves_policy'));
    }


    public function filterWorkingHoursPolicList()
    {
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $m = $_GET['m'];

        CommonHelper::companyDatabaseConnection($m);
        if ($selectVoucherStatus == '0') {
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date', [$fromDate, $toDate])->get();
        }
        if ($selectVoucherStatus == '1') {
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date', [$fromDate, $toDate])->where('status', '=', '1')->get();
        }
        if ($selectVoucherStatus == '2') {
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date', [$fromDate, $toDate])->where('status', '=', '2')->get();
        } else {
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date', [$fromDate, $toDate])->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.filterWorkingHoursPolicList', compact('workingHoursPolicyDetail'));
    }

    public function viewLeavesBalances()
    {
        if (Input::get('company_id') == 'All'):

            $companiesList = DB::Table('company')->select('id', 'name')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id', 'name')->where([['id', '=', Input::get('company_id')]])->get()->toArray();

        endif;

        return view('Hr.AjaxPages.viewLeavesBalances', compact('companiesList'));


    }

    public function filterEmployeeAttendanceList()
    {
        $fromDateOne = date_create($_GET['fromDate']);
        $toDateOne = date_create($_GET['toDate']);

        $fromDate = date_format($fromDateOne, 'n/j/yyyy');
        $toDate = date_format($toDateOne, 'n/j/yyyy');

        //return $fromDate .' ---- '. $toDate;

        $m = $_GET['m'];

        $selectEmployee = $_GET['selectEmployee'];
        $selectEmployeeId = $_GET['selectEmployeeId'];
        $attendanceStatus = $_GET['attendanceStatus'];

        CommonHelper::companyDatabaseConnection($m);
        if (empty($selectEmployeeId) && empty($attendanceStatus)) {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->get();
        } else if (!empty($selectEmployeeId) && empty($attendanceStatus)) {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('acc_no', '=', $selectEmployeeId)->get();
        } else if (empty($selectEmployeeId) && $attendanceStatus == '1') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('absent', '=', '')->get();
        } else if (empty($selectEmployeeId) && $attendanceStatus == '2') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('absent', '!=', '')->get();
        } else if (empty($selectEmployeeId) && $attendanceStatus == '3') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('late', '!=', '')->get();
        } else if (empty($selectEmployeeId) && $attendanceStatus == '4') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('clock_in', '=', NULL)->get();
        } else if (empty($selectEmployeeId) && $attendanceStatus == '5') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('clock_out', '=', NULL)->get();
        } else if (!empty($selectEmployeeId) && $attendanceStatus == '1') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('acc_no', '=', $selectEmployeeId)->where('absent', '=', '')->get();
        } else if (!empty($selectEmployeeId) && $attendanceStatus == '2') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('acc_no', '=', $selectEmployeeId)->where('absent', '!=', '')->get();
        } else if (!empty($selectEmployeeId) && $attendanceStatus == '3') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('acc_no', '=', $selectEmployeeId)->where('late', '!=', '')->get();
        } else if (!empty($selectEmployeeId) && $attendanceStatus == '4') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('acc_no', '=', $selectEmployeeId)->where('clock_in', '=', NULL)->get();
        } else if (!empty($selectEmployeeId) && $attendanceStatus == '5') {
            $employeeAttendanceDetail = Attendance::whereBetween('ddate', [$fromDate, $toDate])->where('acc_no', '=', $selectEmployeeId)->where('clock_out', '=', NULL)->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.filterEmployeeAttendanceList', compact('employeeAttendanceDetail'));
    }

    public function viewEmployeeLeaveDetail()
    {
        return view('Hr.AjaxPages.viewEmployeeLeaveDetail');
    }

    public function viewApplicationDateWise()
    {
        $data = Input::get('id');
        $dataFilter = explode(',', $data);
        $acc_no = $dataFilter[0];
        $month_data = $dataFilter[1];
        $monthDataFilter = explode('-', $month_data);


        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $totalOffDates = '';
        $day_off_emp = Employee::select('day_off')->where([['acc_no', '=', $acc_no]])->value('day_off');
        $total_days_off = Attendance::select('attendance_date')->where([['day', '=', $day_off_emp], ['acc_no', '=', $acc_no]]);

        if ($total_days_off->count() > 0):

            foreach ($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates = array();
        endif;

        $monthly_holidays = '';
        $get_holidays = Holidays::select('holiday_date')->where([['status', '=', 1], ['month', '=', $monthDataFilter[0]], ['year', '=', $monthDataFilter[1]]]);
        if ($get_holidays->count() > 0):
            foreach ($get_holidays->get() as $value2):

                $monthly_holidays[] = $value2['holiday_date'];
            endforeach;

        else:
            $monthly_holidays = array();
        endif;
        $monthly_holidays = array_merge($monthly_holidays, $totalOffDates);
        $absent_dates = Attendance::select("late", "acc_no", "emp_name", "attendance_date", "clock_in", "clock_out")->where([['month', '=', $monthDataFilter[0]], ['year', '=', $monthDataFilter[1]], ['acc_no', '=', $acc_no]])
            ->whereNull('clock_in')
            ->whereNull('clock_out')
            ->whereNotIn('attendance_date', $monthly_holidays)
            ->orwhere([['late', '>=', '04:00'], ['month', '=', $monthDataFilter[0]], ['year', '=', $monthDataFilter[1]], ['acc_no', '=', $acc_no]])
            ->get()->toArray();

        //echo $acc_no;

//        $leave_application_request_list = DB::table('leave_application')
//            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
//            ->join('employee', 'employee.acc_no', '=', 'leave_application.emp_id')
//            ->select('leave_application.*', 'employee.emp_name','leave_application_data.*')
//            //->Where('leave_application_data.emp_id','=',$acc_no)
//            ->Where('leave_application_data.from_date','Like','%'.$monthDataFilter[1].'-'.$monthDataFilter[0].'%')
//            ->Where('leave_application.emp_id','=',$acc_no)
//            ->orWhere('leave_application_data.first_second_half_date','Like','%'.$monthDataFilter[1].'-'.$monthDataFilter[0].'%');
        $LikeDate = "'" . '%' . $monthDataFilter[1] . "-" . $monthDataFilter[0] . '%' . "'";
        CommonHelper::reconnectMasterDatabase();
        $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.first_second_half_date from leave_application
            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
            WHERE leave_application.view="yes" and leave_application_data.from_date LIKE' . $LikeDate . 'AND leave_application_data.emp_id = ' . $acc_no . '
            OR leave_application.view="yes" and leave_application_data.first_second_half_date LIKE' . $LikeDate . ' and leave_application_data.emp_id = ' . $acc_no . '');

        return view('Hr.AjaxPages.viewApplicationDateWise', compact('leave_application_request_list', 'absent_dates'));
    }

    public function viewHolidayDate()
    {
        $data = Input::get('id');
        $dataFilter = explode(',', $data);
        $acc_no = $dataFilter[0];
        $month_data = $dataFilter[1];
        $monthDataFilter = explode('-', $month_data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $HolidayData = Holidays::where([['status', '=', 1], ['month', '=', $monthDataFilter[0]], ['year', '=', $monthDataFilter[1]]])->get();
        $day_off_emp = Employee::select('day_off')->where([['acc_no', '=', $acc_no]])->value('day_off');
        $total_days_off = Attendance::select('attendance_date', 'day', 'month', 'year')->where([['day', '=', $day_off_emp], ['acc_no', '=', $acc_no],
            ['month', '=', $monthDataFilter[0]], ['year', '=', $monthDataFilter[1]]])->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHolidayDate', compact('HolidayData', 'total_days_off'));

    }

    public function viewHalfDaysDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',', $data);
        $acc_no = $dataFilter[0];
        $month_data = $dataFilter[1];

        $monthDataFilter = explode('-', $month_data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $totalOffDates = '';
        $day_off_emp = Employee::select('day_off')->where([['acc_no', '=', $acc_no]])->value('day_off');
        $total_days_off = Attendance::select('attendance_date')->where([['day', '=', $day_off_emp], ['acc_no', '=', $acc_no]]);

        if ($total_days_off->count() > 0):

            foreach ($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates = array();
        endif;
        $monthly_holidays = '';
        $get_holidays = Holidays::select('holiday_date')->where([['status', '=', 1], ['month', '=', $monthDataFilter[0]], ['year', '=', $monthDataFilter[1]]]);
        if ($get_holidays->count() > 0) {
            foreach ($get_holidays->get() as $value2) {

                $monthly_holidays[] = $value2['holiday_date'];
            }
        } else {
            $monthly_holidays = array();
        }
        $monthly_holidays = array_merge($monthly_holidays, $totalOffDates);
        $total_halfDay = Attendance::where([['neglect_attendance', '=', 'no'], ['month', '=', $monthDataFilter[0]], ['year', '=', $monthDataFilter[1]], ['acc_no', '=', $acc_no], ['late', '>=', '02:00'], ['late', '<', '04:00']])
            ->whereNotIn('attendance_date', $monthly_holidays)
            ->get();


        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewHalfDaysDetail', compact('total_halfDay'));

    }

    public function viewLeaveApplicationClientForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_code = Employee::select('id', 'leaves_policy_id')->where([['emp_code', '=', Input::get('emp_code')]])->first();
        $leaves_policy_id = $emp_code->leaves_policy_id;
        //if($leaves_policy_id == 0) { return "<table class='table table-bordered'><tr><td class='text-center' style='color: red'>Please Assign Leave Policy !</td></tr></table>";}
        if ($leaves_policy_id == 0) {
            return '<script> swalAlert("Leave Application","Please Assign Leave Policy !")</script>';
        }
        CommonHelper::reconnectMasterDatabase();

        //Break
        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $leaves_policy_id]])
            ->get();

        //if(empty($leaves_policy->toArray())){ return "<table class='table table-bordered'><tr><td class='text-center' style='color: red'>Please Assign New Leave Policy !</td></tr></table>";}
        if (empty($leaves_policy->toArray())) {
            return '<script> swalAlert("Leave Application","Please Assign New Leave Policy !")</script>';
        }

        $leaves_policy_validatity = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.id', 'leaves_data.id')
            ->where([['leaves_policy.id', '=', $leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
            ->count();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('emp_name', 'emp_department_id', 'designation_id', 'emp_code', 'leaves_policy_id')->where([['id', '=', $emp_code->id]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewLeaveApplicationClientForm', compact('leaves_policy_validatity', 'leaves_policy', 'emp_data'));
    }

    public function viewHolidaysMonthWise()
    {
        $monthData = explode('-', Input::get('monthYear'));
        $year = $monthData[0];
        $month = $monthData[1];
        $m = Input::get('m');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::where('month', '=', $month)->where('year', '=', $year)->where('status', '=', 1)->orderBy('holiday_date')->get();
        CommonHelper::reconnectMasterDatabase();
//            return view('Hr.viewHolidaysList',compact('holidays'));
        return view('Hr.AjaxPages.viewHolidaysMonthWise', compact('holidays', 'm'));
    }

    public function viewEmployeeDepositDetail()
    {

    }

    public function viewEmployeeListManageAttendence()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $getData['emr_no'] = Input::get('emr_no');
        $emp_data = Employee::select('emp_name', 'day_off')->where([['emr_no', '=', Input::get('emr_no')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $getData['emp_name'] = $emp_data['emp_name'];
        $getData['day_off'] = $emp_data['day_off'];
        $getData['sub_department_id'] = Input::get('sub_department_id');
        $monthYearDataFilter = explode('-', Input::get('month_year'));
        $getData['month'] = $monthYearDataFilter[1];
        $getData['year'] = $monthYearDataFilter[0];
        $getData['company_id'] = Input::get('m');

        return view('Hr.AjaxPages.createManuallyAttendanceForm', compact('getData'));

    }

    function  viewEmployeeExitClearanceForm($id = '')
    {
        $emp_code = Input::get('emp_code');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::where([['status', '!=', 2], ['emp_code', '=', $emp_code]])->select('emp_name', 'designation_id', 'branch_id')->first();
        if (!empty($employee)) {
            $designation_id = $employee->designation_id;

            $employeeCurrentPositions = EmployeePromotion::where([['emp_code', '=', Input::get('emp_code')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');
            if ($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
            endif;

            $exit_data = EmployeeExit::where([['status', '=', 1], ['emp_code', '=', $emp_code]]);

            CommonHelper::reconnectMasterDatabase();

            $count = $exit_data->count();
            $exit_employee_data = $exit_data->first();

            return view('Hr.AjaxPages.viewEmployeeExitClearanceForm', compact('employee', 'count', 'exit_employee_data', 'designation_id'));
        }
    }

    public function viewEmployeeExitClearanceDetail()
    {
        $id = Input::get('id');
        $m = Input::get('m');
        $type = Input::get('type');

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $exit_employee_data = EmployeeExit::where([['id', '=', $id]])->first();
        $employee = Employee::where([['status', '!=', 2], ['emp_code', '=', $exit_employee_data->emp_code]])->select('emp_name', 'designation_id', 'emp_department_id')->first();
        $designation_id = $employee->designation_id;
        $emp_department_id = $employee->emp_department_id;

        $employeeCurrentPositions = EmployeePromotion::where([['emp_code', '=', $exit_employee_data->emp_code], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');
        if ($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
        endif;

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeExitClearanceDetail', compact('exit_employee_data', 'employee', 'emp_department_id', 'designation_id', 'exit_employee_data', 'designation', 'location', 'operation_rights2', 'type'));
    }

    public function checkEmrNoExist()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_count = Employee::where([['emp_code', '=', Input::get('emp_code')], ['status', '!=', 2]])->count();
        CommonHelper::reconnectMasterDatabase();

        if ($employee_count > 0):
            echo "Emp Code. " . Input::get('emp_code') . " Already Exist !";
        else:
            echo "success";
        endif;
    }

    function  viewEmployeeIdCardRequest($id = '')
    {
        $emr_no = Input::get('emr_no');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::where([['status', '=', 1], ['emr_no', '=', $emr_no]])->select('img_path', 'designation_id', 'emp_joining_date', 'emp_cnic')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation = Designation::where([['status', '=', 1], ['id', '=', $employee['designation_id']]])->select('designation_name')->first();
        return view('Hr.AjaxPages.viewEmployeeIdCardRequest', compact('designation', 'employee', 'employee_card_request'));

    }

    function  viewEmployeeIdCardRequestDetail($id = '')
    {
        $id = Input::get('id');
        $m = Input::get('m');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_card_request = EmployeeCardRequest::where([['id', '=', $id]])->select('id', 'fir_copy_path', 'fir_copy_extension',
            'emr_no', 'posted_at', 'card_status', 'status', 'approval_status',
            'card_image_extension', 'card_image_path')->first();
        $employee = Employee::where([['emr_no', $employee_card_request->emr_no], ['status', '=', 1]])->select('emp_name', 'img_path', 'designation_id', 'emp_sub_department_id', 'emp_joining_date', 'emp_cnic')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation = Designation::where([['status', '=', 1], ['id', '=', $employee->designation_id]])->select('designation_name')->first();
        $sub_department = SubDepartment::where([['status', '=', 1], ['id', '=', $employee->emp_sub_department_id]])->select('sub_department_name')->first();
        return view('Hr.AjaxPages.viewEmployeeIdCardRequestDetail', compact('designation', 'employee_card_request', 'employee', 'sub_department', 'operation_rights2'));

    }





    function  viewEmployeeFilteredList()
    {
        $regions = CommonHelper::regionRights(Input::get('m'));
        $region_ids = '';
        foreach ($regions as $value):
            if (Auth::user()->acc_type == 'user'):
                $region_ids .= $value . ",";
            else:
                $region_ids .= $value['id'] . ",";
            endif;
        endforeach;

        $emp_department_id = Input::get('emp_department_id');
        $region_id = Input::get('region_id');
        $emp_name = Input::get('emp_name');
        $emp_code = Input::get('emp_code');

        if (!empty($emp_department_id)) $query_string_second_part[] = " AND emp_department_id = '$emp_department_id'";
        if (!empty($region_id)) $query_string_second_part[] = " AND region_id = '$region_id'";
        if (!empty($emp_name)) $query_string_second_part[] = " AND emp_name LIKE '%' '$emp_name' '%' ";
        if (!empty($emp_code)) $query_string_second_part[] = " AND emp_code = '$emp_code'";
      //  $query_string_second_part[] = " AND region_id IN(" . substr($region_ids, 0, -1) . ")";
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $query_string_First_Part = "SELECT id, emp_department_id, emp_code, emp_name, emp_salary,
        emp_contact_no, region_id, emp_joining_date, emp_cnic, emp_date_of_birth, status FROM employee WHERE";
        $query_string_third_part = ' ORDER BY emp_code';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;

        $employees = DB::select(DB::raw($query_string));

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeFilteredList', compact('employees'));

    }

    public function viewEmployeePreviousTransferDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $check_employee_promotion = DB::table('employee_promotion')
            ->join('employee_location', 'employee_promotion.emr_no', '=', 'employee_location.emr_no')
            ->where([['employee_promotion.emr_no', '=', Input::get('emr_no')], ['employee_promotion.status', '=', 1], ['employee_location.approval_status', '=', 2]])
            ->first();
        $check_employee_location = EmployeeTransfer::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->first();
        $transferEmployeeProject = DB::table('transfer_employee_project')
            ->join('employee_location', 'transfer_employee_project.emr_no', '=', 'employee_location.emr_no')
            ->where([['transfer_employee_project.emr_no', '=', Input::get('emr_no')], ['transfer_employee_project.status', '=', 1], ['employee_location.approval_status', '=', 2]])
            ->first();

        if (count($check_employee_promotion) != '0' && count($check_employee_location) == '0' && count($transferEmployeeProject) == '0') {
            $employeePromotion = DB::table('employee_promotion')
                ->join('employee', 'employee_promotion.emr_no', '=', 'employee.emr_no')
                ->select('employee_promotion.designation_id', 'employee_promotion.grade_id', 'employee_promotion.salary', 'employee_promotion.promotion_date', 'employee.branch_id', 'employee.employee_project_id')
                ->where([['employee_promotion.emr_no', '=', Input::get('emr_no')], ['employee_promotion.status', '=', 1], ['employee.active', '=', 1]])
                ->orderBy('employee_promotion.id', 'desc')
                ->first();

            $desigination = $employeePromotion->designation_id;
            $grade = $employeePromotion->grade_id;
            $salary = $employeePromotion->salary;
            $date = $employeePromotion->promotion_date;
            $locations = $employeePromotion->branch_id;
            $employee_project = $employeePromotion->employee_project_id;
        } elseif (count($check_employee_location) != '0' && count($check_employee_promotion) == '0' && count($transferEmployeeProject) == '0') {

            $employeeLocation = DB::table('employee_location')
                ->join('employee', 'employee_location.emr_no', '=', 'employee.emr_no')
                ->select('employee.designation_id', 'employee.grade_id', 'employee.emp_salary', 'employee_location.date', 'employee_location.location_id', 'employee.employee_project_id')
                ->where([['employee_location.emr_no', '=', Input::get('emr_no')], ['employee_location.status', '=', 1], ['employee.active', '=', 1]])
                ->orderBy('employee_location.id', 'desc')
                ->first();
            $desigination = $employeeLocation->designation_id;
            $grade = $employeeLocation->grade_id;
            $salary = $employeeLocation->emp_salary;
            $date = $employeeLocation->date;
            $locations = $employeeLocation->location_id;
            $employee_project = $employeeLocation->employee_project_id;
        } elseif (count($check_employee_location) == '0' && count($check_employee_promotion) == '0' && count($transferEmployeeProject) != '0') {

            $transfer_employee_project = DB::table('employee')
                ->join('transfer_employee_project', 'employee.emr_no', '=', 'transfer_employee_project.emr_no')
                ->select('employee.designation_id', 'employee.grade_id', 'employee.emp_salary', 'employee.date', 'employee.branch_id', 'transfer_employee_project.employee_project_id')
                ->where([['employee_location.emr_no', '=', Input::get('emr_no')], ['employee_location.status', '=', 1], ['transfer_employee_project.active', '=', 1]])
                ->first();
            $desigination = $transfer_employee_project->designation_id;
            $grade = $transfer_employee_project->grade_id;
            $salary = $transfer_employee_project->emp_salary;
            $date = $transfer_employee_project->date;
            $locations = $transfer_employee_project->branch_id;
            $employee_project = $transfer_employee_project->employee_project_id;
        } elseif (count($check_employee_location) != '0' && count($check_employee_promotion) != '0' && count($transferEmployeeProject) != '0') {

            $get_employee_data = DB::table('employee_location')
                ->join('employee_promotion', 'employee_location.emr_no', '=', 'employee_promotion.emr_no')
                ->join('transfer_employee_project', 'employee_location.emr_no', '=', 'transfer_employee_project.emr_no')
                ->select('employee_promotion.designation_id', 'employee_promotion.grade_id', 'employee_promotion.salary', 'employee_location.date', 'employee_location.location_id', 'transfer_employee_project.employee_project_id')
                ->where([['employee_promotion.emr_no', '=', Input::get('emr_no')], ['employee_location.status', '=', 1], ['employee_promotion.status', '=', 1], ['transfer_employee_project.active', '=', 1]])
                ->orderBy('employee_promotion.id', 'desc')
                ->orderBy('employee_location.id', 'desc')
                ->first();
            $desigination = $get_employee_data->designation_id;
            $grade = $get_employee_data->grade_id;
            $salary = $get_employee_data->salary;
            $date = $get_employee_data->date;
            $locations = $get_employee_data->location_id;
            $employee_project = $get_employee_data->employee_project_id;
        } elseif (count($check_employee_location) != '0' && count($check_employee_promotion) != '0' && count($transferEmployeeProject) != '0') {

            $get_employee_data = DB::table('employee_location')
                ->join('employee_promotion', 'employee_location.emr_no', '=', 'employee_promotion.emr_no')
                ->join('transfer_employee_project', 'employee_location.emr_no', '=', 'transfer_employee_project.emr_no')
                ->select('employee_promotion.designation_id', 'employee_promotion.grade_id', 'employee_promotion.salary', 'employee_location.date', 'employee_location.location_id', 'transfer_employee_project.employee_project_id')
                ->where([['employee_promotion.emr_no', '=', Input::get('emr_no')], ['employee_location.status', '=', 1], ['employee_promotion.status', '=', 1], ['transfer_employee_project.active', '=', 1]])
                ->orderBy('employee_promotion.id', 'desc')
                ->orderBy('employee_location.id', 'desc')
                ->first();
            $desigination = $get_employee_data->designation_id;
            $grade = $get_employee_data->grade_id;
            $salary = $get_employee_data->salary;
            $date = $get_employee_data->date;
            $locations = $get_employee_data->location_id;
            $employee_project = $get_employee_data->employee_project_id;
        } elseif (count($check_employee_location) != '0' && count($check_employee_promotion) != '0' && count($transferEmployeeProject) == '0') {

            $get_employee_data = DB::table('employee_location')
                ->join('employee_promotion', 'employee_location.emr_no', '=', 'employee_promotion.emr_no')
                ->join('employee', 'employee_location.emr_no', '=', 'employee.emr_no')
                ->select('employee_promotion.designation_id', 'employee_promotion.grade_id', 'employee_promotion.salary', 'employee_location.date', 'employee_location.location_id', 'employee.employee_project_id')
                ->where([['employee_promotion.emr_no', '=', Input::get('emr_no')], ['employee_location.status', '=', 1], ['employee_promotion.status', '=', 1], ['employee.active', '=', 1]])
                ->orderBy('employee_promotion.id', 'desc')
                ->orderBy('employee_location.id', 'desc')
                ->first();
            $desigination = $get_employee_data->designation_id;
            $grade = $get_employee_data->grade_id;
            $salary = $get_employee_data->salary;
            $date = $get_employee_data->date;
            $locations = $get_employee_data->location_id;
            $employee_project = $get_employee_data->employee_project_id;
        } else {

            $employee = Employee::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->first();
            $desigination = $employee->designation_id;
            $grade = $employee->grade_id;
            $date = $employee->date;
            $salary = $employee->emp_salary;
            $locations = $employee->branch_id;
            $employee_project = $employee->employee_project_id;
        }


        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePreviousTransferDetail', compact('desigination', 'grade', 'date', '', 'salary', 'locations', 'employee_project'));
    }


    public function viewEmployeeFuelDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeData = Employee::select('emr_no', 'branch_id', 'designation_id')->where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeFuelDetailForm', compact('employeeData'));
    }

    public function viewEmployeeFuelDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emr_no = Input::get('emr_no');
        $employeeFuelData = EmployeeFuelData::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('fuel_date');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeFuelDetail', compact('employeeFuelData', 'emr_no'));
    }

    public function viewEmployeeFilteredFuelDetail()
    {
        $emr_no = Input::get('emr_no');
        $fuel_month = Input::get('fuel_month');
        $fuel_year = Input::get('fuel_year');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeFuelData = EmployeeFuelData::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['fuel_month', '=', Input::get('fuel_month')], ['fuel_year', '=', Input::get('fuel_year')]])->orderBy('fuel_date');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeFilteredFuelDetail', compact('employeeFuelData', 'emr_no', 'fuel_month', 'fuel_year'));
    }

    public function viewHrEmployeeAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeAuditDetail = Employee::select('emr_no', 'employee_category_id', 'emp_name')->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrEmployeeAuditDetail', compact('employeeAuditDetail'));

    }

    public function viewHrLetters()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $emr_no = Input::get('emr_no');
        $m = Input::get('m');
        $emp_category_id = Input::get('emp_category_id');
        $employee_project_id = Input::get('employee_project_id');
        $region_id = Input::get('region_id');
        $letter_id = Input::get('letter_id');
        $show_all = Input::get('show_all');

        if ($show_all == 1) {
            $employee_all_emrno = HrHelper::getAllEmployeeId(Input::get('m'), $emp_category_id, $region_id, 'show_all');
        } else {
            $employee_all_emrno = HrHelper::getAllEmployeeId(Input::get('m'), $emp_category_id, $region_id, $employee_project_id);
        }


        if ($letter_id == 1) {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            if ($show_all == 1) {
                $hr_warning_letter = DB::table('hr_warning_letter')
                    ->join('employee', 'hr_warning_letter.emr_no', '=', 'employee.emr_no')
                    ->select('hr_warning_letter.id', 'employee.emp_name', 'hr_warning_letter.emr_no', 'hr_warning_letter.note', 'hr_warning_letter.date')
                    ->whereIn('hr_warning_letter.emr_no', $employee_all_emrno)
                    ->where('hr_warning_letter.status', '=', 1)
                    ->orderBy('hr_warning_letter.id', 'desc');
            } else if ($emr_no == 'All') {
                $hr_warning_letter = DB::table('hr_warning_letter')
                    ->join('employee', 'hr_warning_letter.emr_no', '=', 'employee.emr_no')
                    ->select('hr_warning_letter.id', 'employee.emp_name', 'hr_warning_letter.emr_no', 'hr_warning_letter.note', 'hr_warning_letter.date')
                    ->whereIn('hr_warning_letter.emr_no', $employee_all_emrno)
                    ->where('hr_warning_letter.status', '=', 1)
                    ->orderBy('hr_warning_letter.id', 'desc');
            } else {
                $hr_warning_letter = DB::table('hr_warning_letter')
                    ->join('employee', 'hr_warning_letter.emr_no', '=', 'employee.emr_no')
                    ->select('hr_warning_letter.id', 'employee.emp_name', 'hr_warning_letter.emr_no', 'hr_warning_letter.note', 'hr_warning_letter.date')
                    ->where([['hr_warning_letter.emr_no', '=', $emr_no], ['hr_warning_letter.status', '=', 1]])
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrWarningLetterList', compact('hr_warning_letter', 'operation_rights2'));
        } elseif ($letter_id == 2) {
            CommonHelper::companyDatabaseConnection(Input::get('m'));

            $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('id', 'desc');

            $employeeCurrentPositions = Employee::select('designation_id', 'emp_salary', 'emp_joining_date')->where([['emr_no', '=', Input::get('emr_no')], ['status', '!=', 2]])->first();
            $designation_id = $employeeCurrentPositions->designation_id;
            $current_salary = $employeeCurrentPositions->emp_salary;
            $performance_from_date = $employeeCurrentPositions->emp_joining_date;

            $employeeCurrentPositions = EmployeePromotion::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');

            $employeeAllowances = Allowance::where([['emr_no', '=', $emr_no], ['status', '=', 1]]);
            if ($employeeAllowances->count() > 0):
                $employeeAllowances = $employeeAllowances->get();
            endif;

            if ($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
                $new_salary = $employeeCurrentPositionsDetail->salary;
                $performance_to_date = $employeeCurrentPositionsDetail->promotion_date;

            else:
                return '<div class="text-center" style="color: red"><table class="table table-bordered"><tr><td>Record not found!!</td></tr></table></div>';
            endif;

            if ($employeeCurrentPositions->count() > 1):
                $employeeLastPositions = EmployeePromotion::select('designation_id', 'salary', 'promotion_date')->where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc')->skip(1)->take(1);
                $employeeLastPositionDetails = $employeeLastPositions->first();
                $designation_id = $employeeLastPositionDetails->designation_id;
                $current_salary = $employeeLastPositionDetails->salary;
                $performance_from_date = $employeeLastPositionDetails->promotion_date;
            endif;

            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrMfmSouthIncrementLetterList', compact('hr_mfm_south_increment_letter', 'operation_rights2', 'performance_from_date', 'performance_to_date', 'employeeAllowances', 'current_salary', 'new_salary', 'designation_id'));
        } elseif ($letter_id == 3) {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_mfm_south_without_increment_letter = HrMfmSouthWithoutIncrementLetter::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrMfmSouthWithoutIncrementLetterList', compact('hr_mfm_south_without_increment_letter', 'operation_rights2'));
        } elseif ($letter_id == 4) {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_contract_conclusion_letter = HrContractConclusionLetter::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrContractConclusionLetterList', compact('hr_contract_conclusion_letter', 'operation_rights2'));
        } elseif ($letter_id == 5) {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_termination_format1_letter = HrTerminationFormat1Letter::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTerminationFormat1LetterList', compact('hr_termination_format1_letter', 'operation_rights2'));
        } elseif ($letter_id == 6) {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_termination_format2_letter = HrTerminationFormat2Letter::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTerminationFormat2LetterList', compact('hr_termination_format2_letter', 'operation_rights2'));
        } elseif ($letter_id == 7) {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_transfer_letter = HrTransferLetter::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTransferLetterList', compact('hr_transfer_letter', 'operation_rights2'));
        } else {
            return;
        }

    }

    public function getEmployeeDateOfJoining()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date_of_joining = Employee::select('emp_joining_date')->where([['emr_no', '=', Input::get('emr_no')]])->value('emp_joining_date');
        CommonHelper::reconnectMasterDatabase();
        $data[] = date('F d, Y', strtotime(Input::get('settlement_date')));
        $data[] = date('F d, Y', strtotime($date_of_joining));
        return $data;
    }

    public function getConclusionLettersDate()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date_of_joining = Employee::select('emp_joining_date')->where([['emr_no', '=', Input::get('emr_no')]])->value('emp_joining_date');
        CommonHelper::reconnectMasterDatabase();
        $data[] = date('F d, Y', strtotime($date_of_joining));
        $data[] = date('F d, Y', strtotime(Input::get('conclude_date')));
        $data[] = date('F d, Y', strtotime(Input::get('settlement_date')));
        return $data;
    }

    public function getIncrementLettersDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('id', 'desc');
        $employeeCurrentPositions = Employee::select('designation_id', 'emp_salary', 'emp_joining_date')->where([['emr_no', '=', Input::get('emr_no')], ['status', '!=', 2]])->first();
        $designation_id = $employeeCurrentPositions->designation_id;
        $current_salary = $employeeCurrentPositions->emp_salary;
        $performance_from_date = $employeeCurrentPositions->emp_joining_date;


        $employeeCurrentPositions = EmployeePromotion::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');

        $employeeAllowances = Allowance::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]]);
        if ($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        if ($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id', 'salary', 'promotion_date')->where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails->designation_id;
            $current_salary = $employeeLastPositionDetails->salary;
            $performance_from_date = $employeeLastPositionDetails->promotion_date;
        endif;

        if ($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
            $new_salary = $employeeCurrentPositionsDetail->salary;
            $performance_to_date = $employeeCurrentPositionsDetail->promotion_date;

        else:
            return '1';
        endif;

        CommonHelper::reconnectMasterDatabase();
        $designation_name = Designation::where([['id', '=', $designation_id], ['status', '=', '1']])->select('designation_name')->first();

        $data[] = date('F d, Y', strtotime($performance_from_date));
        $data[] = date('F d, Y', strtotime($performance_to_date));
        $data[] = date('F d, Y', strtotime(Input::get('confirmation_from')));
        $data[] = $designation_name->designation_name;
        $data[] = $new_salary - $current_salary;
//        $data[] = $current_salary;
//        $data[] = $new_salary;
//        $data[] = $employeeAllowances;
        return $data;

    }

    public function getWithoutIncrementLettersDate()
    {
        $data[] = date('F d, Y', strtotime(Input::get('performance_from')));
        $data[] = date('F d, Y', strtotime(Input::get('performance_to')));
        $data[] = date('F d, Y', strtotime(Input::get('confirmation_from')));
        return $data;
    }

    public function getTransferLettersDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_transfer_letter = HrTransferLetter::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->orderBy('id', 'desc');
        $employeeCurrentPositions = Employee::select('designation_id', 'branch_id')->where([['emr_no', '=', Input::get('emr_no')], ['status', '!=', 2]])->first();
        $designation_id = $employeeCurrentPositions->designation_id;
        $transfer_from = $employeeCurrentPositions->branch_id;

        $employeeCurrentPositions = EmployeePromotion::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');
        $employeeCurrentLocations = EmployeeTransfer::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');

        $employeeAllowances = Allowance::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]]);
        if ($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        if ($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id', 'salary', 'promotion_date')->where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails->designation_id;
        endif;

        if ($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
        endif;

        if ($employeeCurrentLocations->count() > 1):
            $employeeLastLocation = EmployeeTransfer::select('location_id')->where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc')->skip(1)->take(1);
            $employeeLastLocationDetails = $employeeLastLocation->first();
            $transfer_from = $employeeLastLocationDetails->location_id;
        endif;

        if ($employeeCurrentLocations->count() > 0):
            $employeeCurrentLocationsDetail = $employeeCurrentLocations->first();
            $transfer_to = $employeeCurrentLocationsDetail->location_id;
        else:
            return '1';
        endif;

        CommonHelper::reconnectMasterDatabase();
        $designation_name = Designation::where([['id', '=', $designation_id], ['status', '=', '1']])->select('designation_name')->first();
        $transfer_from = Locations::where([['id', '=', $transfer_from], ['status', '=', '1']])->select('employee_location')->first();
        $transfer_to = Locations::where([['id', '=', $transfer_to], ['status', '=', '1']])->select('employee_location')->first();


        $data[] = $transfer_from->employee_location;
        $data[] = $transfer_to->employee_location;
        $data[] = date('F d, Y', strtotime(Input::get('transfer_date')));
        $data[] = $designation_name->designation_name;

        return $data;

    }

    public function viewHrLetterFiles()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hrLettersFile = LetterFiles::where([['id', '=', Input::get('id')], ['status', '=', 1]])->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrLetterFiles', compact('hrLettersFile'));
    }

    public function viewEmployeeEquipmentsForm()
    {
        $equipment = null;
        $employeeEquipment = Equipments::where([['company_id', '=', Input::get('m')], ['status', '=', 1]])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id', 'emr_no', 'eobi_number', 'eobi_path', 'insurance_number', 'insurance_path')->where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeEquipmentsForm', compact('employee', 'equipment', 'employeeEquipment'));
    }

    public function viewEmployeeEquipmentsDetail()
    {
        $equipment_detail = null;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_emr_no = EmployeeEquipments::where([['id', '=', Input::get('id')]])->first();
        $emr_no = $employee_emr_no->emr_no;
        $employee = Employee::select('id', 'emr_no', 'eobi_number', 'eobi_path', 'insurance_number', 'insurance_path')->where([['emr_no', '=', $emr_no], ['status', '!=', 2]])->first();
        $employeeEquipment = EmployeeEquipments::where([['emr_no', '=', $emr_no]])->pluck('equipment_id')->toArray();

        if (EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emr_no', '=', $emr_no], ['status', '=', 1], ['equipment_id', '=', 11]])->exists()):
            $equipment_detail = EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emr_no', '=', $emr_no], ['status', '=', 1], ['equipment_id', '=', 11]])->first();
        endif;

        $employee_eobi_copy = Employee::where([['emr_no', '=', $emr_no], ['status', '!=', 2], ['eobi_path', '!=', null]]);
        $employee_insurance_copy = Employee::where([['emr_no', '=', $emr_no], ['status', '!=', 2], ['insurance_path', '!=', null]]);

        CommonHelper::reconnectMasterDatabase();
        $equipment = Equipments::where([['status', '=', 1]])->orderBy('id')->get();

        return view('Hr.AjaxPages.viewEmployeeEquipmentsDetail', compact('employeeEquipment', 'emr_no', 'equipment', 'employee', 'equipment_detail', 'employee_insurance_copy', 'employee_eobi_copy'));

    }

    public function viewEmployeePreviousAllowancesDetail()
    {
        $emp_code = Input::get('emp_code');
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowances = Allowance::where([['emp_code', '=', $emp_code]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePreviousAllowancesDetail', compact('allowances'));
    }

    public function viewHrWarningLetter($id, $m)
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_warning_letter = HrWarningLetter::where([['id', '=', $id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrWarningLetter', compact('hr_warning_letter', 'operation_rights2'));
    }

    public function viewHrMfmSouthIncrementLetter($id, $m)
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['id', '=', $id]])->orderBy('id', 'desc')->first();

        $emr_no = $hr_mfm_south_increment_letter['emr_no'];
        $employeeCurrentPositions = Employee::select('designation_id', 'emp_salary', 'emp_joining_date')->where([['emr_no', '=', $emr_no], ['status', '=', 1]])->first();
        $designation_id = $employeeCurrentPositions['designation_id'];
        $current_salary = $employeeCurrentPositions['emp_salary'];

        $employeeAllowances = Allowance::where([['emr_no', '=', $emr_no], ['status', '=', 1]]);
        if ($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        $employeeCurrentPositions = EmployeePromotion::where([['emr_no', '=', $emr_no], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');

        if ($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id', 'salary', 'promotion_date')->where([['emr_no', '=', $emr_no], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first()->toArray();
            $designation_id = $employeeLastPositionDetails['designation_id'];
            $current_salary = $employeeLastPositionDetails['salary'];
        endif;

        if ($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
            $new_salary = $employeeCurrentPositionsDetail['salary'];

        else:
            return '<div class="text-center" style="color: red"><table class="table table-bordered"><tr><td>Record not found!!</td></tr></table></div>';
        endif;
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewHrMfmSouthIncrementLetter', compact('hr_mfm_south_increment_letter', 'designation_id', 'current_salary', 'new_salary', 'employeeAllowances', 'operation_rights2'));
    }

    public function viewHrMfmSouthWithoutIncrementLetter($id, $m)
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_mfm_south_without_increment_letter = HrMfmSouthWithoutIncrementLetter::where([['id', '=', $id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrMfmSouthWithoutIncrementLetter', compact('hr_mfm_south_without_increment_letter', 'operation_rights2'));
    }

    public function viewHrContractConclusionLetter($id, $m)
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_contract_conclusion_letter = HrContractConclusionLetter::where([['id', '=', $id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrContractConclusionLetter', compact('hr_contract_conclusion_letter', 'operation_rights2'));
    }

    public function viewHrTerminationFormat1Letter($id, $m)
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_termination_format1_letter = HrTerminationFormat1Letter::where([['id', '=', $id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTerminationFormat1Letter', compact('hr_termination_format1_letter', 'operation_rights2'));
    }

    public function viewHrTerminationFormat2Letter($id, $m)
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_termination_format2_letter = HrTerminationFormat2Letter::where([['id', '=', $id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTerminationFormat2Letter', compact('hr_termination_format2_letter', 'operation_rights2'));
    }

    public function viewHrTransferLetter($id, $m)
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_transfer_letter = HrTransferLetter::where([['id', '=', $id]])->orderBy('id', 'desc')->first();
        $emr_no = $hr_transfer_letter['emr_no'];

        $employeeCurrentPositions = Employee::select('designation_id')->where([['emr_no', '=', $emr_no], ['status', '=', 1]])->first();
        $designation_id = $employeeCurrentPositions['designation_id'];

        $employeeCurrentPositions = EmployeePromotion::where([['emr_no', '=', $emr_no], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');

        if ($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id')->where([['emr_no', '=', $emr_no], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails['designation_id'];
        endif;

        if ($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTransferLetter', compact('hr_transfer_letter', 'operation_rights2', 'designation_id'));
    }

    public function viewEmployeeExperienceDocuments()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_experience = EmployeeWorkExperience::select('id', 'emp_code', 'work_exp_path', 'work_exp_name', 'work_exp_type')->where([['emr_no', '=', $emr_no], ['status', '=', 1], ['work_exp_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeExperienceDocuments', compact('employee_experience'));
    }

    public function checkCnicNoExist()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_count = Employee::where([['emp_cnic', '=', Input::get('emp_cnic')]])->count();
        CommonHelper::reconnectMasterDatabase();

        if ($employee_count > 0):
            echo "CNIC No. " . Input::get('emp_cnic') . " Already Exist !";
        else:
            echo "success";
        endif;

    }

    public function viewMasterTableForm()
    {
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1'],])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewMasterTableForm', compact('departments'));
    }

    public function viewDayWiseAttendence()
    {
        $regions = CommonHelper::regionRights(Input::get('m'));
        $departments = CommonHelper::departmentRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$departments)->get();
        return view('Hr.AjaxPages.viewDayWiseAttendence', compact('employee_regions','employee_department'));
    }

    public function viewMonthWiseAttendence()
    {
        $regions = CommonHelper::regionRights(Input::get('m'));
        $departments = CommonHelper::departmentRights(Input::get('m'));
        $employee_regions = Regions::where([['status', '=', 1], ['company_id', '=', Input::get('m')]])->whereIn('id', $regions)->get();
        $employee_department = Department::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$departments)->get();
        return view('Hr.AjaxPages.viewMonthWiseAttendence', compact('employee_department', 'employee_regions'));
    }

    public function viewUploadFileAttendance()
    {
        return view('Hr.AjaxPages.viewUploadFileAttendance', compact('employees'));
    }


    public function viewEmployeeInsuranceCopy()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id', 'emr_no', 'emp_name', 'insurance_path', 'insurance_type')->where([['emr_no', '=', $emr_no], ['status', '!=', 2], ['insurance_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeInsuranceCopy', compact('employee'));
    }

    public function viewEmployeeEobiDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_eobi = Employee::select('emp_name', 'emr_no', 'eobi_path')->where([['status', '=', 1], ['eobi_path', '=', null]]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeEobiDetail', compact('employee_missing_eobi'));
    }

    public function viewEmployeeInsuranceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_insurance = Employee::select('emp_name', 'emr_no', 'insurance_path')->where([['status', '=', 1], ['insurance_path', '=', null]]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeInsuranceDetail', compact('employee_missing_insurance'));
    }

    public function viewEmployeeSettlementDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $settlementDate = date('Y-m-d', strtotime(now() . '+2 days'));
        $settlementTermination1 = HrTerminationFormat1Letter::where([['status', '=', 1], ['settlement_date', '<', $settlementDate], ['settlement_date', '>', date('Y-m-d')], ['settlement_date', '!=', '']]);
        $settlementTermination2 = HrTerminationFormat2Letter::where([['status', '=', 1], ['settlement_date', '<', $settlementDate], ['settlement_date', '>', date('Y-m-d')], ['settlement_date', '!=', '']]);
        $settlementContract = HrContractConclusionLetter::where([['status', '=', 1], ['settlement_date', '<', $settlementDate], ['settlement_date', '>', date('Y-m-d')], ['settlement_date', '!=', '']]);

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeSettlementDetail', compact('settlementTermination1', 'settlementTermination2', 'settlementContract'));
    }

    public function viewEmployeeMedicalDocuments()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_medical = EmployeeMedical::select('emr_no')->where([['id', '=', Input::get('id')], ['status', '=', 1]])->first();
        $employeeMedicalDocuments = EmployeeMedicalDocuments::where([['emr_no', '=', $employee_medical->emr_no], ['status', '=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeMedicalDocuments', compact('employee_medical', 'employeeMedicalDocuments'));
    }

    public function getMoreEmployeesDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $regions = CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '!=', '2'], ['status', '=', 1], ['emp_code', '>', Input::get('lastId')]])
        //    ->whereIn('region_id', $regions)
            ->select('id', 'emp_department_id', 'emp_code', 'emp_name', 'emp_salary', 'emp_contact_no', 'region_id', 'emp_joining_date', 'emp_cnic', 'emp_date_of_birth', 'status')
            ->offset(0)
            ->limit(20)
            ->orderBy('emp_code', 'asc')->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.getMoreEmployeesDetail', compact('employees', 'operation_rights2'));

    }

    public function viewTrainingDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $trainingsData = Trainings::where([['status', '=', 1], ['id', Input::get('id')]])->first();
        $TrainingCertificate = TrainingCertificate::where([['status', '=', 1], ['training_id', Input::get('id')]])->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTrainingDetail', compact('employee_regions', 'employee_category', 'employee_locations', 'trainingsData', 'employee', 'TrainingCertificate'));

    }

    public function viewFinalSettlement()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loan_amount = '';
        $gratuityAmount = '';
        $allowance_amount = '';
        $previous_loan_amount = '';
        $emp_code = Input::get('emp_code');

        if (EmployeeExit::where([['emp_code', '=', $emp_code]])->exists()):

            $employee = Employee::where([['status', '!=', 2], ['emp_code', '=', $emp_code]])->select('designation_id', 'region_id', 'emp_joining_date', 'emp_salary')->first();
            $designation_id = $employee->designation_id;
            $salary = $employee->emp_salary;

            $employeeCurrentPositions = EmployeePromotion::where([['emp_code', '=', Input::get('emp_code')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');
            if ($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
                $salary = $employeeCurrentPositionsDetail->salary;
            endif;

            //multiple loan
            $loan = LoanRequest::where([['emp_code', '=', $emp_code], ['approval_status', '=', 2], ['loan_status', '=', 0], ['status', '=', 1]])->orderBy('id', 'desc');
            if ($loan->count() > 0):
                foreach ($loan->get() as $val):
                    if (Payroll::where([['emp_code', '=', $emp_code], ['loan_id', '=', $val->id]])->exists()):
                        $payroll_deducted_amount = Payroll::where([['emp_code', '=', $emp_code], ['loan_id', '=', $val->id]])->sum('loan_amount_paid');
                        if ($payroll_deducted_amount < $val->loan_amount):
                            $loan_amount += $val->loan_amount - $payroll_deducted_amount;
                        endif;
                    else:
                        $loan_data = LoanRequest::select('loan_amount')->where([['id', '=', $val->id]])->first();
                        $loan_amount += $loan_data->loan_amount;
                    endif;
                endforeach;
            endif;

            $gratuity = Gratuity::where([['emp_code', Input::get('emp_code')]])->orderBy('id', 'desc');
            if ($gratuity->exists()):
                $gratuityDetails = $gratuity->first();
                $gratuityAmount = $gratuityDetails->gratuity;
            endif;
            $exit_data = EmployeeExit::where([['status', '=', 1], ['emp_code', '=', $emp_code]])->select('leaving_type', 'last_working_date')->first();
            $final_settlement = FinalSettlement::where([['status', '=', 1], ['emp_code', '=', $emp_code]]);
            CommonHelper::reconnectMasterDatabase();
            $count = $final_settlement->count();
            $final_settlement_data = $final_settlement->first();
            return view('Hr.AjaxPages.viewFinalSettlement', compact('employee', 'gratuityAmount', 'allowance_amount', 'exit_data', 'final_settlement_data', 'count', 'salary', 'loan_amount', 'previous_loan_amount', 'designation_id'));
        else:
            return "<div class='row'>&nbsp</div><div class='text-center' style='color: red; font-size: 18px;'>Create Exit Clearance Form First</div>";
        endif;

    }

    public function viewFinalSettlementDetail()
    {
        $type = Input::get('type');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $array = explode('|', Input::get('id'));
        $id = $array[0];
        $emp_code = $array[1];
        $final_settlement = FinalSettlement::where([['status', '=', 1], ['emp_code', '=', $emp_code]])->first();
        $gratuity = Gratuity::where([['emp_code', $emp_code]])->orderBy('id')->first();

        $employee = Employee::where([['status', '!=', 2], ['emp_code', '=', $emp_code]])->select('emp_code', 'emp_name', 'emp_department_id', 'designation_id', 'emp_joining_date', 'emp_salary', 'region_id')->first();
        $designation_id = $employee['designation_id'];
        $salary = $employee['emp_salary'];
        $emp_department_id = $employee['emp_department_id'];

        $employeeCurrentPositions = EmployeePromotion::where([['emp_code', '=', $emp_code], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');
        if ($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
            $salary = $employeeCurrentPositionsDetail['salary'];
        endif;

        $exit_data = EmployeeExit::where([['status', '=', 1], ['emp_code', '=', $emp_code]])->select('leaving_type', 'last_working_date')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewFinalSettlementDetail', compact('type', 'operation_rights2', 'gratuity', 'exit_data', 'final_settlement', 'salary', 'designation_id', 'employee', 'emp_department_id'));
    }

    public function viewEmployeeGratuityForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if (Input::get('show_All') == "yes"):
            $employee = Employee::where([['status', '!=', 2]])
                ->select('emp_joining_date', 'emp_salary', 'emr_no', 'emp_name', 'region_id', 'employee_category_id', 'employee_project_id')
                ->orderBy("emr_no")->get()->toArray();
        elseif (Input::get('emr_no') == 'All' && Input::get('employee_project_id') == '0'):
            $employee = Employee::where([['region_id', '=', Input::get('region_id')], ['employee_category_id', '=', Input::get('emp_category_id')], ['status', '!=', 2]])
                ->select('emp_joining_date', 'emp_salary', 'emr_no', 'emp_name', 'region_id', 'employee_category_id', 'employee_project_id')->orderBy("emr_no")
                ->get()->toArray();
        elseif (Input::get('emr_no') == 'All' && Input::get('employee_project_id') !== '0'):
            $employee = Employee::where([['region_id', '=', Input::get('region_id')], ['employee_category_id', '=', Input::get('emp_category_id')],
                ['employee_project_id', '=', Input::get('employee_project_id')], ['status', '!=', 2]])
                ->select('emp_joining_date', 'emp_salary', 'emr_no', 'emp_name', 'region_id', 'employee_category_id', 'employee_project_id')
                ->orderBy("emr_no")->get()->toArray();
        else:
            $employee = Employee::where([['status', '!=', 2], ['emr_no', '=', Input::get('emr_no')]])
                ->select('emp_joining_date', 'emp_salary', 'emr_no', 'emp_name', 'region_id', 'employee_category_id', 'employee_project_id')->orderBy("emr_no")
                ->get()->toArray();
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeGratuityForm', compact('employee'));

    }

    public function viewS2bReport()
    {
        $emp_category_id = Input::get('emp_category_id');
        $region_id = Input::get('region_id');
        $getPayslipMonth = Input::get('payslip_month');
        $explodeMonthYear = explode('-', $getPayslipMonth);
        $employee_project_id = Input::get('employee_project_id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if ($employee_project_id !== '0') {
            $employees = Employee::select("region_id", "emr_no", "emp_salary", "emp_name", "emp_father_name", "employee_project_id")
                ->where([['status', '=', '1'], ['employee_category_id', '=', $emp_category_id], ['region_id', '=', $region_id], ['employee_project_id', '=', $employee_project_id]])->get();
        } else {
            $employees = Employee::select("region_id", "emr_no", "emp_salary", "emp_name", "emp_father_name", "employee_project_id")
                ->where([['status', '=', '1'], ['employee_category_id', '=', $emp_category_id], ['region_id', '=', $region_id]])->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewS2bReport', compact('employees', 'explodeMonthYear'));
    }

    public function viewDashboardDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '!=', '2']])->count();
        $employees_onboard = Employee::where([['status', '=', '1']])->count();
        $employees_exit = EmployeeExit::where([['status', '=', '1'], ['approval_status', '=', 2]])->count();
        CommonHelper::reconnectMasterDatabase();
        $locations = Locations::where([['status', '=', '1']])->count();
        $regions = Regions::where([['status', '=', '1']])->count();
        $categories = EmployeeCategory::where([['status', '=', '1']])->count();
        return compact('employees', 'employees_onboard', 'employees_exit', 'locations', 'regions', 'categories');
    }

    public function viewPendingRequests()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loan = LoanRequest::where([['status', '=', '1'], ['approval_status', '=', 1]])->count();
        $advance_salary = AdvanceSalary::where([['status', '=', '1'], ['approval_status', '=', 1]])->count();
        $pomotion = EmployeePromotion::where([['status', '=', '1'], ['approval_status', '=', 1]])->count();
        $exit_clearance = EmployeeExit::where([['status', '=', '1'], ['approval_status', '=', 1]])->count();
        $id_card = EmployeeCardRequest::where([['status', '=', '1'], ['approval_status', '=', 1]])->count();
        CommonHelper::reconnectMasterDatabase();
        $leaves = LeaveApplication::where([['status', '=', '1'], ['approval_status', '=', 1]])->count();
        return view('Hr.AjaxPages.viewPendingRequests', compact('loan', 'advance_salary', 'pomotion', 'transfer', 'exit_clearance', 'id_card', 'leaves'));
    }

    public function viewS2bNewsReport()
    {
        $emp_category_id = Input::get('emp_category_id') . "<br>";
        $region_id = Input::get('region_id');
        $month_year = explode('-', Input::get('month_year'));
        $data = ['employee_category_id' => $emp_category_id, 'region_id' => $region_id];
        return view('Hr.AjaxPages.viewS2bNewsReport', compact('data', 'month_year'));

    }

    public function viewOtAndFuelReport()
    {
        $emp_category_id = Input::get('emp_category_id');
        $region_id = Input::get('region_id');
        $month_year = explode('-', Input::get('month_year'));
        $data = ['employee_category_id' => $emp_category_id, 'region_id' => $region_id];
        return view('Hr.AjaxPages.viewOtAndFuelReport', compact('data', 'month_year'));

    }


    public function viewEmployeePromotionDetailForLog()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeePromotions = EmployeePromotion::where([['id', '=', Input::get('id')], ['status', '=', 1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePromotionDetailForLog', compact('employeeData', 'employeePromotions'));

    }

    public function viewEmployeeTransferDetailForLog()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeTransfers = EmployeeTransfer::where([['id', '=', Input::get('id')], ['status', '=', 1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeTransferDetailForLog', compact('employeeData', 'employeeTransfers'));
    }

    public function viewLeaveApplicationRequestDetailForLog()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $leaveApplication = LeaveApplication::where([['id', '=', Input::get('id')]])->first();
        $leaveApplicationData = LeaveApplicationData::where([['leave_application_id', '=', Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewLeaveApplicationRequestDetailForLog', compact('leaveApplication', 'leaveApplicationData'));
    }

    public function employeeGetLeavesBalances()
    {

        if (Input::get('company_id') == 'All'):

            $companiesList = DB::Table('company')->select('id', 'name')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id', 'name')->where([['id', '=', Input::get('company_id')]])->get()->toArray();

        endif;
        $LeavePolicy = LeavesPolicy::where([['status', '=', 1]])->get();
        return view('Hr.AjaxPages.employeeGetLeavesBalances', compact('companiesList', 'LeavePolicy'));
    }


    public function viewTransferLetter()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $transfer_letter = transferLetter::where('emp_location_id', '=', $id)->get();
        $EmployeeTransfer = EmployeeTransfer::where('id', '=', $id)->first();
        $employee = Employee::where('emr_no', '=', $EmployeeTransfer->emr_no)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTransferLetter', compact('transfer_letter', 'employee'));

    }



    public function viewPreviousEmployeeProject()
    {
        $emr_no = Input::get('emr_no');
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        $transferEmployeeProject = transferEmployeeProject::where([['status', '=', 1], ['emr_no', '=', $emr_no]])->first();
        $check_employee_salary = EmployeePromotion::where([['emr_no', '=', Input::get('emr_no')], ['status', '=', 1]])->first();

        if (count($transferEmployeeProject) != '0' && count($check_employee_salary) == '0') {
            $employee = DB::table('transfer_employee_project')
                ->join('employee', 'transfer_employee_project.emr_no', '=', 'employee.emr_no')
                ->select('employee.emp_salary', 'employee.designation_id', 'employee.grade_id', 'transfer_employee_project.employee_project_id', 'transfer_employee_project.date')
                ->where([['transfer_employee_project.emr_no', '=', $emr_no], ['transfer_employee_project.status', '=', 1], ['employee.status', '=', 1]])
                ->orderBy('transfer_employee_project.id', 'desc')
                ->first();
            $salary = $employee->emp_salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        } else if (count($transferEmployeeProject) == '0' && count($check_employee_salary) != '0') {
            $employee = DB::table('employee_promotion')
                ->join('employee', 'employee_promotion.emr_no', '=', 'employee.emr_no')
                ->select('employee_promotion.salary', 'employee_promotion.designation_id', 'employee_promotion.grade_id', 'employee.employee_project_id', 'employee_promotion.date')
                ->where('employee.emr_no', '=', $emr_no)
                ->where('employee_promotion.status', '=', 1)
                ->where('employee.status', '=', 1)
                ->orderBy('employee_promotion.id', 'desc')
                ->first();
            $salary = $employee->salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        } else if (count($transferEmployeeProject) != '0' && count($check_employee_salary) != '0') {
            $employee = DB::table('employee_promotion')
                ->join('transfer_employee_project', 'employee_promotion.emr_no', '=', 'transfer_employee_project.emr_no')
                ->select('employee_promotion.salary', 'employee_promotion.designation_id', 'employee_promotion.grade_id', 'transfer_employee_project.employee_project_id', 'transfer_employee_project.date')
                ->where('transfer_employee_project.emr_no', '=', $emr_no)
                ->where('employee_promotion.status', '=', 1)
                ->where('transfer_employee_project.status', '=', 1)
                ->orderBy('transfer_employee_project.id', 'desc')
                ->first();
            $salary = $employee->salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        } else {
            $employee = Employee::where([['emr_no', '=', $emr_no], ['status', '=', 1]])->first();
            $salary = $employee->emp_salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewPreviousEmployeeProject', compact('salary', 'designation_id', 'grade_id', 'employee_project', 'date'));
    }

    public function checkManualLeaves()
    {
        $value = Input::get('value');
        $leave_type = Input::get('leave_type');
        $m = Input::get('m');
        $emr_no = Input::get('emr_no');
        $error_status = Input::get('error_status');
        CommonHelper::companyDatabaseConnection($m);
        $emp_leave_policy = Employee::where([['emr_no', '=', $emr_no], ['leaves_policy_id', '!=', 0]])->first();
        CommonHelper::reconnectMasterDatabase();
        $LeavesData = LeavesData::where([['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id], ['leave_type_id', '=', $leave_type]])->value('no_of_leaves');
        if ($value > $LeavesData) {
            echo 'Your' . ' ' . $error_status . ' ' . 'is greater than your leave policy';
        } else {
            echo 'done';
        }

    }

    public function getPendingLeaveApplicationDetail()
    {
        $getPendingLeaveApp = LeaveApplication::select('emr_no', 'id')->where([[Input::get('type'), '=', 1]])->orderBy('id', 'desc')->offset(0)->limit(1);

        if ($getPendingLeaveApp->count() == 0):
            return 0;
        endif;

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp = Employee::select('id', 'leaves_policy_id', 'designation_id')->where([['emr_no', '=', $getPendingLeaveApp->value('emr_no')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $leave_day_type = Input::get('leave_day_type');

        if (Input::get('leave_day_type') == 1):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emr_no', 'leave_application.leave_address', 'leave_application.approval_status', 'leave_application.approval_status_m', 'leave_application.approval_status_hd', 'leave_application.reason', 'leave_application.status', 'leave_application_data.no_of_days', 'leave_application_data.date', 'leave_application_data.from_date', 'leave_application_data.to_date')
                ->where([['leave_application.id', '=', $getPendingLeaveApp->value('id')]])->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        elseif (Input::get('leave_day_type') == 2):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emr_no', 'leave_application.leave_address', 'leave_application.approval_status', 'leave_application.approval_status_m', 'leave_application.approval_status_hd', 'leave_application.reason', 'leave_application.status', 'leave_application_data.first_second_half', 'leave_application_data.date', 'leave_application_data.first_second_half_date')
                ->where([['leave_application.id', '=', $getPendingLeaveApp->value('id')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        else:
            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emr_no', 'leave_application.approval_status', 'leave_application.approval_status_m', 'leave_application.approval_status_hd', 'leave_application.leave_address', 'leave_application.approval_status_hd_name', 'leave_application.reason',
                    'leave_application.status', 'leave_application_data.short_leave_time_from', 'leave_application_data.short_leave_time_to',
                    'leave_application_data.date', 'leave_application_data.short_leave_date')
                ->where([['leave_application.id', '=', $getPendingLeaveApp->value('id')]])->first();

            $leave_day_type_arr = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        endif;

        $approval_array[1] = '<span class="label label-warning">Pending</span>';
        $approval_array[2] = '<span class="label label-success">Approved</span>';
        $approval_array[3] = '<span class="label label-danger">Rejected</span>';

        $approval_status = $approval_array[$leave_application_data->approval_status];
        $approval_status_m = $approval_array[$leave_application_data->approval_status_m];
        $approval_status_hd = $approval_array[$leave_application_data->approval_status_hd];
        CommonHelper::reconnectMasterDatabase();
        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
            ->get();


        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $emp_data = Employee::select('emp_name', 'emp_sub_department_id', 'designation_id', 'emr_no', 'leaves_policy_id')->where([['id', '=', $emp->id]])->orderBy('id')->first();

        CommonHelper::reconnectMasterDatabase();

        $designation_name = Designation::where([['id', '=', $emp->designation_id]])->value('designation_name');
        $data['designation_name'] = $designation_name;
        $data['leave_day_type'] = $leave_day_type;
        $data['leave_application_data'] = $leave_application_data;
        $data['approval_status'] = $approval_status;
        $data['approval_status_m'] = $approval_status_m;
        $data['approval_status_hd'] = $approval_status_hd;
        $data['emp_data'] = $emp_data;
        $data['leave_type_name'] = Input::get('leave_type_name');
        $data['leave_day_type_label'] = $leave_day_type_label;
        $data['leaves_policy'] = $leaves_policy;
        $data['leave_application_id'] = $getPendingLeaveApp->value('id');

        return view('Hr.AjaxPages.getPendingLeaveApplicationDetail')->with($data);
    }

    public function viewProjectLetter()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $project_letter = projectTransferLetter::where([['emp_project_id', '=', $id], ['status', '=', 1]])->get();
        $TransferEmployeeProject = TransferEmployeeProject::where('id', '=', $id)->first();
        $employee = Employee::where('emr_no', '=', $TransferEmployeeProject->emr_no)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewProjectLetter', compact('project_letter', 'employee'));
    }
}