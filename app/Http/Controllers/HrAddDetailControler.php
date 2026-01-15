<?php

namespace App\Http\Controllers;
use App\Models\Arrears;
use App\Models\Designation;
use App\Models\EmployeeCategory;
use App\Models\EmployeeFuelData;
use App\Models\EmployeeLocation;
use App\Models\Grades;
use App\Models\Locations;
use App\Models\Regions;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\LoanRequest;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payslip;
use App\Models\Attendence;
use App\Models\EmployeeDeposit;
use App\Models\LeavesData;
use App\Models\LeavesPolicy;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeEquipments;
use App\Models\UsersImport;
use App\Models\EmployeeBankData;
use App\Models\EmployeePromotion;
use App\Models\TransferedLeaves;
use DateTime;
use App\Models\PayrollData;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

use Hash;
use File;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;



class HrAddDetailControler extends Controller
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

    public function addDepartmentDetail()
    {
        $departmentSection = Input::get('departmentSection');
        foreach ($departmentSection as $row) {
            $department_name = Input::get('department_name_' . $row . '');
            $data1['department_name'] = strip_tags($department_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('department')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDepartmentList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addSubDepartmentDetail()
    {
        $subDepartmentSection = Input::get('subDepartmentSection');
        foreach ($subDepartmentSection as $row) {
            $department_id = Input::get('department_id_' . $row . '');
            $sub_department_name = Input::get('sub_department_name_' . $row . '');
            $data1['department_id'] = strip_tags($department_id);
            $data1['sub_department_name'] = strip_tags($sub_department_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('sub_department')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewSubDepartmentList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addDesignationDetail()
    {
        $designationSection = Input::get('designationSection');
        foreach ($designationSection as $row) {
            $department_id = Input::get('department_id_' . $row . '');
            $designation_name = Input::get('designation_name_' . $row . '');
            $data1['department_id'] = strip_tags($department_id);
            $data1['designation_name'] = strip_tags($designation_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('designation')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDesignationList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addHealthInsuranceDetail()
    {
        $healthInsuranceSection = Input::get('healthInsuranceSection');
        foreach ($healthInsuranceSection as $row) {
            $healthInsurance_name = Input::get('healthInsurance_name_' . $row . '');
            $data1['health_insurance_name'] = strip_tags($healthInsurance_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('health_insurance')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewHealthInsuranceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addLifeInsuranceDetail()
    {
        $lifeInsuranceSection = Input::get('lifeInsuranceSection');
        foreach ($lifeInsuranceSection as $row) {
            $lifeInsurance_name = Input::get('lifeInsurance_name_' . $row . '');
            $data1['life_insurance_name'] = strip_tags($lifeInsurance_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('life_insurance')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLifeInsuranceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addJobTypeDetail()
    {
        $jobTypeSection = Input::get('jobTypeSection');
        foreach ($jobTypeSection as $row) {
            $job_type_name = Input::get('job_type_name_' . $row . '');
            $data1['job_type_name'] = strip_tags($job_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('job_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewJobTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addQualificationDetail()
    {
        $qualificationSection = Input::get('qualificationSection');
        foreach ($qualificationSection as $row) {
            $qualification_name = Input::get('qualification_name_' . $row . '');
            $institute_name = Input::get('institute_name_' . $row . '');
            $country = Input::get('country_' . $row . '');
            $state = Input::get('state_' . $row . '');
            $city = Input::get('city_' . $row . '');
            $institute = Input::get('institute_name_' . $row . '');
            $data2['qualification_name'] = strip_tags($qualification_name);
            $data2['institute_id'] = strip_tags($institute);
            $data2['country_id'] = strip_tags($country);
            $data2['state_id'] = strip_tags($state);
            $data2['city_id'] = strip_tags($city);
            $data2['username'] = Auth::user()->name;
            $data2['status'] = 1;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['company_id'] = $_GET['m'];
            DB::table('qualification')->insert($data2);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewQualificationList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addLeaveTypeDetail()
    {
        $leaveTypeSection = Input::get('leaveTypeSection');
        foreach ($leaveTypeSection as $row) {
            $leave_type_name = Input::get('leave_type_name_' . $row . '');
            $data1['leave_type_name'] = strip_tags($leave_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('leave_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLeaveTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addLoanTypeDetail()
    {
        $loanTypeSection = Input::get('loanTypeSection');
        foreach ($loanTypeSection as $row) {
            $loan_type_name = Input::get('loan_type_name_' . $row . '');
            $data1['loan_type_name'] = strip_tags($loan_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('loan_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLoanTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addAdvanceTypeDetail()
    {
        $advanceTypeSection = Input::get('advanceTypeSection');
        foreach ($advanceTypeSection as $row) {
            $advance_type_name = Input::get('advance_type_name_' . $row . '');
            $data1['advance_type_name'] = strip_tags($advance_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('advance_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAdvanceTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addShiftTypeDetail()
    {
        $shiftTypeSection = Input::get('shiftTypeSection');
        foreach ($shiftTypeSection as $row) {
            $shift_type_name = Input::get('shift_type_name_' . $row . '');
            $data1['shift_type_name'] = strip_tags($shift_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('shift_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewShiftTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }


    public function addHiringRequestDetail()
    {
        $d = Input::get('dbName');
        $companyId = Input::get('company_id');
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
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
        $location = Input::get('location');
        $experience = Input::get('experience');
        $career_level = Input::get('career_level');
        $apply_before_date = Input::get('apply_before_date');

        $str = DB::selectOne("select max(convert(substr(`RequestHiringNo`,4,length(substr(`RequestHiringNo`,4))-4),signed integer))
        reg from `requesthiring` where substr(`RequestHiringNo`,-4,2) = " . date('m') . " 
        and substr(`RequestHiringNo`,-2,2) = " . date('y') . "")->reg;
        $RequestHiringNo = 'rhn' . ($str + 1) . date('my');

        $data1['RequestHiringNo'] = strip_tags($RequestHiringNo);
        $data1['RequestHiringTitle'] = strip_tags($jobTitle);
        $data1['sub_department_id'] = strip_tags($subDepartmentId);
        $data1['job_type_id'] = strip_tags($jobTypeId);
        $data1['designation_id'] = strip_tags($designationId);
        $data1['qualification_id'] = strip_tags($qualificationId);
        $data1['shift_type_id'] = strip_tags($shiftTypeId);
        $data1['location'] = strip_tags($location);
        $data1['experience'] = strip_tags($experience);
        $data1['career_level'] = strip_tags($career_level);
        $data1['apply_before_date'] = strip_tags($apply_before_date);
        $data1['RequestHiringGender'] = strip_tags($gender);
        $data1['RequestHiringSalaryStart'] = strip_tags($salaryStart);
        $data1['RequestHiringSalaryEnd'] = strip_tags($salaryEnd);
        $data1['RequestHiringAge'] = strip_tags($age);
        $data1['RequestHiringDescription'] = $jobDescription;
        $data1['ApprovalStatus'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('requesthiring')->insert($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewHiringRequestList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $companyId . '#mima');
    }

    function addEmployeeDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $employeeSection = Input::get('employeeSection');
        foreach ($employeeSection as $row) {


            /*Image uploading start*/
            if ($request->file('fileToUpload_' . $row . '')):
                $file_name = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('fileToUpload_' . $row . '')->getClientOriginalExtension();
                $path = 'app/' . $request->file('fileToUpload_' . $row . '')->storeAs('uploads/employee_images', $file_name);
            else:
                $path = 'app/uploads/employee_images/user-dummy.png';
            endif;
            /*Image uploading end*/

//          cnic upload start
            if ($request->file('cnic_path_' . $row . '')):
                $file_name1 = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('cnic_path_' . $row . '')->getClientOriginalExtension();
                $path1 = 'app/' . $request->file('cnic_path_' . $row . '')->storeAs('uploads/employee_cnic_copy', $file_name1);
                $data1['cnic_path'] = $path1;
                $data1['cnic_name'] = $file_name1;
                $data1['cnic_type'] = $request->file('cnic_path_' . $row . '')->getClientOriginalExtension();
            else:
                $data1['cnic_path'] = null;
                $data1['cnic_name'] = null;
                $data1['cnic_type'] = null;
            endif;

//            eobi upload
            if ($request->file('eobi_path_' . $row . '')):
                $file_name1 = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('eobi_path_' . $row . '')->getClientOriginalExtension();
                $path1 = 'app/' . $request->file('eobi_path_' . $row . '')->storeAs('uploads/employee_eobi_copy', $file_name1);
                $data1['eobi_path'] = $path1;
                $data1['eobi_type'] = $request->file('eobi_path_' . $row . '')->getClientOriginalExtension();
            else:
                $data1['eobi_path'] = null;
                $data1['eobi_type'] = null;
            endif;



            /*Basic info start*/
            $comp_id = Input::get('comp_id_' . $row . '');
            $employee_name = Input::get('employee_name_' . $row . '');
            $emp_code = Input::get('emp_code_' . $row . '');
            $father_name = Input::get('father_name_' . $row . '');

            $department_id = Input::get('emp_department_id_' . $row . '');
            $date_of_birth = Input::get('date_of_birth_' . $row . '');
            $joining_date = Input::get('joining_date_' . $row . '');
            $gender = Input::get('gender_' . $row . '');
            $cnic = Input::get('cnic_' . $row . '');
            $cnic_expiry_date = Input::get('cnic_expiry_date_' . $row . '');

            $contact_no = Input::get('contact_no_' . $row . '');
            $contact_home = Input::get('contact_home_' . $row . '');
            $contact_office = Input::get('contact_office_' . $row . '');
            $emergency_no = Input::get('emergency_no_' . $row . '');
            $employee_status = Input::get('employee_status_' . $row . '');
            $salary = Input::get('salary_' . $row . '');
            $email = Input::get('email_' . $row . '');
            $marital_status = Input::get('marital_status_' . $row . '');
            $leaves_policy = Input::get('leaves_policy_' . $row . '');
            $designation = Input::get('designation_' . $row . '');

            $eobi_id = Input::get('eobi_id_' . $row . '');

            $branch_id = Input::get('branch_id_' . $row . '');
            $region_id = Input::get('region_id_' . $row . '');


            $place_of_birth = Input::get('place_of_birth_' . $row . '');

            $emp_joining_salary = Input::get('emp_joining_salary_' . $row . '');

            $eobi_number = Input::get('eobi_number_' . $row . '');

            $life_time_cnic = Input::get('life_time_cnic_' . $row . '');

            $relegion = Input::get('religion_' . $row . '');
            $nationality = Input::get('nationality_' . $row . '');
            $province = Input::get('province_' . $row . '');
            $transport_owned = Input::get('transport_check_' . $row . '');
            $transport_particular = Input::get('transport_particulars_' . $row . '');
            $passport_no = Input::get('passport_no_' . $row . '');
            $passport_valid_from = Input::get('passport_valid_from_' . $row . '');
            $passport_valid_to = Input::get('passport_valid_to_' . $row . '');
            $driving_license_no = Input::get('driving_license_no_' . $row . '');
            $driving_license_valid_from = Input::get('driving_license_valid_from_' . $row . '');
            $driving_license_valid_to = Input::get('driving_license_valid_to_' . $row . '');
            $working_hours_policy_id = Input::get('working_hours_policy_id_' . $row . '');
            $residential_address = Input::get('residential_address_' . $row . '');
            $permanent_address = Input::get('permanent_address_' . $row . '');


            $str = DB::selectOne("select max(convert(substr(`emp_no`,4,length(substr(`emp_no`,4))-4),signed integer)) reg from `employee` where substr(`emp_no`,-4,2) = " . date('m') . " and substr(`emp_no`,-2,2) = " . date('y') . "")->reg;
            $employee_no = 'Emp' . ($str + 1) . date('my');


            $data1['relegion'] = strip_tags($relegion);
            $data1['nationality'] = strip_tags($nationality);
            $data1['province'] = strip_tags($province);
            $data1['branch_id'] = strip_tags($branch_id);
            $data1['region_id'] = strip_tags($region_id);

            $data1['designation_id'] = strip_tags($designation);

            $data1['eobi_id'] = strip_tags($eobi_id);
            $data1['emp_code'] = strip_tags($emp_code);
            $data1['company_id'] = strip_tags($comp_id);

            $data1['leaves_policy_id'] = strip_tags($leaves_policy);
            $data1['emp_no'] = strip_tags($employee_no);
            $data1['emp_name'] = strip_tags($employee_name);
            $data1['emp_father_name'] = strip_tags($father_name);
            $data1['emp_department_id'] = strip_tags($department_id);
            $data1['emp_date_of_birth'] = strip_tags($date_of_birth);
            $data1['emp_place_of_birth'] = strip_tags($place_of_birth);

            $data1['emp_joining_salary'] = strip_tags($emp_joining_salary);

            $data1['emp_joining_date'] = strip_tags($joining_date);
            $data1['emp_gender'] = strip_tags($gender);
            $data1['emp_cnic'] = strip_tags($cnic);
            $data1['emp_cnic_expiry_date'] = strip_tags($cnic_expiry_date);
            $data1['emp_contact_no'] = strip_tags($contact_no);
            $data1['contact_home'] = strip_tags($contact_home);
            $data1['contact_office'] = strip_tags($contact_office);
            $data1['emergency_no'] = strip_tags($emergency_no);
            $data1['emp_employementstatus_id'] = strip_tags($employee_status);
            $data1['emp_salary'] = strip_tags($salary);
            $data1['transport_owned'] = strip_tags($transport_owned);
            $data1['transport_particular'] = strip_tags($transport_particular);
            $data1['passport_no'] = strip_tags($passport_no);
            $data1['passport_valid_from'] = strip_tags($passport_valid_from);
            $data1['passport_valid_to'] = strip_tags($passport_valid_to);
            $data1['driving_license_no'] = strip_tags($driving_license_no);
            $data1['driving_license_valid_from'] = strip_tags($driving_license_valid_from);
            $data1['driving_license_valid_to'] = strip_tags($driving_license_valid_to);
            $data1['working_hours_policy_id'] = strip_tags($working_hours_policy_id);
            $data1['emp_email'] = strip_tags($email);
            $data1['residential_address'] = strip_tags($residential_address);
            $data1['permanent_address'] = strip_tags($permanent_address);
            $data1['emp_marital_status'] = strip_tags($marital_status);

            $data1['eobi_number'] = strip_tags($eobi_number);

            $data1['life_time_cnic'] = strip_tags($life_time_cnic);;
            $data1['can_login'] = (Input::get('can_login_' . $row . '') ? 'yes' : 'no');
            $data1['img_path'] = $path;

            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $last_user_id = DB::table('employee')->insertGetId($data1);
            CommonHelper::reconnectMasterDatabase();

            /*Basic info end*/

            if (Input::get('can_login_' . $row . '')):
                CommonHelper::reconnectMasterDatabase();
                $email = Input::get('email_' . $row . '');
                $employee_password = Input::get('password_' . $row . '');
                $employee_name = Input::get('employee_name_' . $row . '');
                $employee_account_type = Input::get('account_type_' . $row . '');


                $dataCredentials['name'] = $employee_name;
                $dataCredentials['username'] = $email;
                $dataCredentials['email'] = $email;
                $dataCredentials['password'] = Hash::make($employee_password);
                $dataCredentials['acc_type'] = $employee_account_type;
                $dataCredentials['emp_code'] = $emp_code;
                $dataCredentials['company_id'] = $comp_id;
                $dataCredentials['updated_at'] = date("Y-m-d");
                $dataCredentials['created_at'] = date("Y-m-d");
                $dataCredentials['identity'] = $employee_password;

                DB::table('users')->insert($dataCredentials);

            endif;
        }

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        /*family data start*/
        if (!empty(Input::get('family_data'))):
            foreach (Input::get('family_data') as $familyRow):
                $familyData['emp_code'] = $emp_code;
                $familyData['family_name'] = Input::get('family_name_' . $familyRow . '');
                $familyData['family_relation'] = Input::get('family_relation_' . $familyRow . '');
                $familyData['family_age'] = Input::get('family_age_' . $familyRow . '');
                $familyData['family_occupation'] = Input::get('family_occupation_' . $familyRow . '');
                $familyData['family_organization'] = Input::get('family_organization_' . $familyRow . '');
                $familyData['status'] = 1;
                $familyData['username'] = Auth::user()->name;
                $familyData['date'] = date("Y-m-d");
                $familyData['time'] = date("H:i:s");
                DB::table('employee_family_data')->insert($familyData);
            endforeach;
        endif;
        /*family data end*/

        /*Bank Account data start*/
        if (Input::get('bank_account_check_1') == 'on'):
            $bankData['emp_code'] = $emp_code;
            $bankData['account_title'] = Input::get('account_title');
            $bankData['bank_name'] = Input::get('bank_name');
            $bankData['account_no'] = Input::get('account_no');
            $bankData['status'] = 1;
            $bankData['username'] = Auth::user()->name;
            $bankData['date'] = date("Y-m-d");
            $bankData['time'] = date("H:i:s");
            DB::table('employee_bank_data')->insert($bankData);


        endif;
        /*Bank Account data end*/
        /*Educational data start*/
        if (!empty(Input::get('education_data'))):
            foreach (Input::get('education_data') as $educationalRow):
                $educationalData['emp_code'] = $emp_code;
                $educationalData['institute_name'] = Input::get('institute_name_' . $educationalRow . '');
                $educationalData['year_of_admission'] = Input::get('year_of_admission_' . $educationalRow . '');
                $educationalData['year_of_passing'] = Input::get('year_of_passing_' . $educationalRow . '');
                $educationalData['degree_type'] = Input::get('degree_type_' . $educationalRow . '');
                $educationalData['major_subjects'] = Input::get('major_subjects_' . $educationalRow . '');
                $educationalData['status'] = 1;
                $educationalData['username'] = Auth::user()->name;
                $educationalData['date'] = date("Y-m-d");
                $educationalData['time'] = date("H:i:s");
                DB::table('employee_educational_data')->insert($educationalData);

            endforeach;
        endif;
        /*Educational data end*/

        /*Language data end*/
        if (!empty(Input::get('language_data'))):
            foreach (Input::get('language_data') as $languageRow):
                $languageData['emp_code'] = $emp_code;
                $languageData['language_name'] = Input::get('language_name_' . $languageRow . '');
                $languageData['reading_skills'] = Input::get('reading_skills_' . $languageRow . '');
                $languageData['writing_skills'] = Input::get('writing_skills_' . $languageRow . '');
                $languageData['speaking_skills'] = Input::get('speaking_skills_' . $languageRow . '');
                $languageData['status'] = 1;
                $languageData['username'] = Auth::user()->name;
                $languageData['date'] = date("Y-m-d");
                $languageData['time'] = date("H:i:s");

                DB::table('employee_language_proficiency')->insert($languageData);
            endforeach;
        endif;
        /*Language data end*/

        /*Health data end*/
        if (!empty(Input::get('health_data'))):
            foreach (Input::get('health_data') as $healthRow):
                $healthData['emp_code'] = $emp_code;
                $healthData['health_type'] = Input::get('health_type_' . $healthRow . '');
                $healthData['health_check'] = Input::get('health_check_' . $healthRow . '');
                $healthData['physical_handicap'] = Input::get('physical_handicap');
                $healthData['height'] = Input::get('height');
                $healthData['weight'] = Input::get('weight');
                $healthData['blood_group'] = Input::get('blood_group');
                $healthData['status'] = 1;
                $healthData['username'] = Auth::user()->name;
                $healthData['date'] = date("Y-m-d");
                $healthData['time'] = date("H:i:s");

                DB::table('employee_health_data')->insert($healthData);
            endforeach;
        endif;
        /*Activity data end*/
        if (!empty(Input::get('activity_data'))):
            foreach (Input::get('activity_data') as $activityRow):
                $activityData['emp_code'] = $emp_code;
                $activityData['institution_name'] = Input::get('institution_name_' . $activityRow . '');
                $activityData['position_held'] = Input::get('position_held_' . $activityRow . '');
                $activityData['status'] = 1;
                $activityData['username'] = Auth::user()->name;
                $activityData['date'] = date("Y-m-d");
                $activityData['time'] = date("H:i:s");
                DB::table('employee_activity_data')->insert($activityData);
            endforeach;
        endif;
        /*Activity data end*/

        /*work experience data start*/
        $counter = 1;
        if (!empty(Input::get('work_experience_data'))):
            foreach (Input::get('work_experience_data') as $workExperienceRow):

                if ($request->hasFile('work_exp_path_1')):

                    $extension = $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $file_name3 = $emp_code . '_'  .$counter .'_'. time() . '.' . $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $path3 = 'app/' . $request->file('work_exp_path_' . $workExperienceRow . '')->storeAs('uploads/employee_experience_documents', $file_name3);

                    $workExperienceData['work_exp_path'] = $path3;
                    $workExperienceData['work_exp_name'] = $file_name3;
                    $workExperienceData['work_exp_type'] = $extension;
                else:
                    $workExperienceData['work_exp_path'] = null;
                    $workExperienceData['work_exp_name'] = null;
                    $workExperienceData['work_exp_type'] = null;
                endif;

                $counter++;
                $workExperienceData['emp_code'] = $emp_code;
                $workExperienceData['employeer_name'] = Input::get('employeer_name_' . $workExperienceRow . '');
                $workExperienceData['position_held'] = Input::get('position_held_' . $workExperienceRow . '');
                $workExperienceData['career_level'] = Input::get('career_level_' . $workExperienceRow . '');
                $workExperienceData['started'] = Input::get('started_' . $workExperienceRow . '');
                $workExperienceData['ended'] = Input::get('ended_' . $workExperienceRow . '');
                $workExperienceData['last_drawn_salary'] = Input::get('last_drawn_salary_' . $workExperienceRow . '');
                $workExperienceData['reason_leaving'] = Input::get('reason_leaving_' . $workExperienceRow . '');
                $workExperienceData['suspend_check'] = Input::get('suspend_check_1');
                $workExperienceData['suspend_reason'] = Input::get('suspend_reason_1');

                $workExperienceData['status'] = 1;
                $workExperienceData['username'] = Auth::user()->name;
                $workExperienceData['date'] = date("Y-m-d");
                $workExperienceData['time'] = date("H:i:s");
                DB::table('employee_work_experience')->insert($workExperienceData);

            endforeach;
        endif;
        /*work experience data end*/

        /*Reference data start*/
        if (!empty(Input::get('reference_data'))):
            foreach (Input::get('reference_data') as $referenceRow):
                $referenceData['emp_code'] = $emp_code;
                $referenceData['reference_name'] = Input::get('reference_name_' . $referenceRow . '');
                $referenceData['reference_designation'] = Input::get('reference_designation_' . $referenceRow . '');
                $referenceData['reference_organization'] = Input::get('reference_organization_' . $referenceRow . '');
                $referenceData['reference_address'] = Input::get('reference_address_' . $referenceRow . '');
                $referenceData['reference_country'] = Input::get('reference_country_' . $referenceRow . '');
                $referenceData['reference_contact'] = Input::get('reference_contact_' . $referenceRow . '');
                $referenceData['reference_relationship'] = Input::get('reference_relationship_' . $referenceRow . '');
                $referenceData['status'] = 1;
                $referenceData['username'] = Auth::user()->name;
                $referenceData['date'] = date("Y-m-d");
                $referenceData['time'] = date("H:i:s");
                DB::table('employee_reference_data')->insert($referenceData);
            endforeach;
        endif;
        /*Reference data end*/

        /*kins data start*/
        if (!empty(Input::get('kins_data'))):
            foreach (Input::get('kins_data') as $kinsRow):
                $kinsData['emp_code'] = $emp_code;
                $kinsData['next_kin_name'] = Input::get('next_kin_name_' . $kinsRow . '');
                $kinsData['next_kin_relation'] = Input::get('next_kin_relation_' . $kinsRow . '');
                $kinsData['next_kin_percentage'] = Input::get('next_kin_percentage_' . $kinsRow . '');
                $kinsData['next_kin_address'] = Input::get('next_kin_address_' . $kinsRow . '');
                $kinsData['status'] = 1;
                $kinsData['username'] = Auth::user()->name;
                $kinsData['date'] = date("Y-m-d");
                $kinsData['time'] = date("H:i:s");
                DB::table('employee_kins_data')->insert($kinsData);
            endforeach;
        endif;

        /*kins data end*/

        /*relatives data start*/
        if (!empty(Input::get('relatives_data'))):
            foreach (Input::get('relatives_data') as $relativesRow):
                $relativesData['emp_code'] = $emp_code;
                $relativesData['relative_name'] = Input::get('relative_name_' . $relativesRow . '');
                $relativesData['relative_position'] = Input::get('relative_position_' . $relativesRow . '');
                $relativesData['status'] = 1;
                $relativesData['username'] = Auth::user()->name;
                $relativesData['date'] = date("Y-m-d");
                $relativesData['time'] = date("H:i:s");
                DB::table('employee_relatives')->insert($relativesData);
            endforeach;
        endif;

        /*relatives data end*/

        /*other detail start*/

        $otherDetails['emp_code'] = $emp_code;
        $otherDetails['crime_check'] = Input::get('crime_check_1');
        $otherDetails['crime_detail'] = Input::get('crime_detail_1');
        $otherDetails['additional_info_check'] = Input::get('additional_info_check_1');
        $otherDetails['additional_info_detail'] = Input::get('additional_info_detail_1');
        $otherDetails['status'] = 1;
        $otherDetails['username'] = Auth::user()->name;
        $otherDetails['date'] = date("Y-m-d");
        $otherDetails['time'] = date("H:i:s");
        DB::table('employee_other_details')->insert($otherDetails);

        /*other detail end*/

        /*FileUploding Documents detail start*/
        $counter = 0;
        if ($request->file('media')) {
            foreach ($request->file('media') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'EmpCode_' . $emp_code . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_documents', $file_name);

                    $fileUploadData['emp_code'] = $emp_code;
                    $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                    $fileUploadData['file_name'] = $file_name;
                    $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('employee_documents')->insert($fileUploadData);
                }
            }
        }

        $counter = 0;
        if ($request->hasFile('document_file_1')):
            foreach (Input::get('employeeGsspData') as $rows):
                $counter++;
                $extension = $request->file('document_file_' . $rows . '')->getClientOriginalExtension();
                $file_name = Input::get('emr_no') . '_' . $counter . time() . '.' . $request->file('document_file_' . $rows . '')->getClientOriginalExtension();
                $path = $request->file('document_file_' . $rows . '')->storeAs('uploads/employee_gssp_documents', $file_name);

                $employee_gssp['emp_code'] = $emp_code;
                $employee_gssp['document_type'] = Input::get('document_type_' . $rows . '');
                $employee_gssp['document_path'] = 'app/' . $path;
                $employee_gssp['document_extension'] = $extension;
                $employee_gssp['counter'] = $counter;
                $employee_gssp['status'] = 1;
                $employee_gssp['username'] = Auth::user()->name;
                $employee_gssp['date'] = date("Y-m-d");
                $employee_gssp['time'] = date("H:i:s");
                DB::table('employee_gssp_documents')->insert($employee_gssp);
            endforeach;
        endif;

        /*FileUploding Documents  detail end*/

        $log['table_name']         = 'employee';
        $log['activity_id']        = $last_user_id;
        $log['deleted_emr_no']     = null;
        $log['activity']           = 'Insert';
        $log['module']             = 'hr';
        $log['username']           = Auth::user()->name;
        $log['date']               = date("Y-m-d");
        $log['time']               = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeList?pageType=viewlist&&parentCode=20&&m=' . Input::get('company_id') . '');


    }

    public function uploadEmployeeFileDetail(Request $request)
    {
        $data  = Excel::toArray(new UsersImport, request()->file('employeeFile'));

        $counter = 1;

        if(trim($data[0][1][0]) == 'S.no' && trim($data[0][1][1]) == 'Employee Name' && trim($data[0][1][2]) == 'Father Name' &&
            trim($data[0][1][3]) == 'Address' && trim($data[0][1][4]) == 'Phone No' && trim($data[0][1][5]) == 'Designation' &&
            trim($data[0][1][6]) == 'Location/Site' && trim($data[0][1][7]) == 'Salary' &&
            trim($data[0][1][8]) == 'Emp Code' && trim($data[0][1][9]) == 'CNIC' && trim($data[0][1][10]) == 'DOB' &&
            trim($data[0][1][11]) == 'DOJ' && trim($data[0][1][12]) == 'Region'):

            foreach ($data[0] as $value) {
                if ($counter++ == 2) continue;
                if( $value[9] == '' ||  $value[9] == 'Emp Code' || $value[9] == 'Emp Code ') continue ;
                if ($value['9'] != '') {
                    $designation = Designation::select('id')->where([['status','=',1],['designation_name','=',$value['5']]]);
                    if ($designation->count() > 0) {
                        $designation_id = $designation->value('id');
                    } else {
                        Session::flash('errorMsg', $value['5'].' designation not found. Please create and try again.');
                        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('company_id') . '#mima');
                    }

                    $branch = Locations::select('id')->where([['status','=',1],['employee_location','=',$value['6']]]);
                    if ($branch->count() > 0) {
                        $branch_id = $branch->value('id');
                    } else {
                        Session::flash('errorMsg', $value['6'].' location not found. Please create and try again.');
                        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('company_id') . '#mima');
                    }

                    $region = Regions::select('id')->where([['status','=',1],['employee_region','=',$value['12'] ]]);
                    if ($region->count() > 0) {
                        $region_id = $region->value('id');
                    } else {
                        Session::flash('errorMsg', $value['12'].' region not found. Please create and try again.');
                        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('company_id') . '#mima');
                    }

                    $excelDate = $value['10']; //2018-11-03
                    $miliseconds = ($excelDate - (25567 + 2)) * 86400 * 1000;
                    $seconds = $miliseconds / 1000;
                    $dob =  date("Y-m-d", $seconds);

                    $excelDate2 = $value['11']; //2018-11-03
                    $miliseconds2 = ($excelDate2 - (25567 + 2)) * 86400 * 1000;
                    $seconds2 = $miliseconds2 / 1000;
                    $doj =  date("Y-m-d", $seconds2);

                    $data2['emp_name'] = ($value['1'] == '' ? '-' : $value['1']);
                    $data2['emp_father_name'] = ($value['2'] == '' ? '-' : $value['2']);
                    $data2['emp_salary'] = ($value['7'] == '' ? '-' : $value['7']);
                    $data2['permanent_address'] = ($value['3'] == '' ? '-' : $value['3']);
                    $data2['emp_contact_no'] = ($value['4'] == '' ? '-' : $value['4']);
                    $data2['emp_code'] = ($value['8'] == '' ? '-' : $value['8']);
                    $data2['emp_cnic'] = ($value['9'] == '' ? '-' : $value['9']);
                    $data2['emp_date_of_birth'] = ($value['10'] == '' ? '-' : $dob);
                    $data2['emp_joining_date'] = ($value['11'] == '' ? '-' : $doj);
                    $data2['designation_id'] = $designation_id;
                    $data2['branch_id'] = $branch_id;
                    $data2['region_id'] = $region_id;
                    $data2['username'] = Auth::user()->name;
                    $data2['date'] = date("Y-m-d");
                    $data2['time'] = date("H:i:s");
                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));

                    $employeeCount = Employee::where([['emp_code', '=', $value['9']]])->count();
                    if ($employeeCount > 0) {
                        DB::table('employee')->where([['emp_code', '=', $value['9']]])->update($data2);
                    } else {
                        DB::table('employee')->insert($data2);
                    }

                    CommonHelper::reconnectMasterDatabase();

                }
            }

            CommonHelper::companyDatabaseConnection(Input::get('company_id'));
            $log['table_name']         = 'employee';
            $log['activity_id']        = null;
            $log['deleted_emr_no']     = null;
            $log['activity']           = 'Upload';
            $log['module']             = 'hr';
            $log['username']           = Auth::user()->name;
            $log['date']               = date("Y-m-d");
            $log['time']               = date("H:i:s");
            DB::table('log')->insert($log);
            CommonHelper::reconnectMasterDatabase();

        else:
            Session::flash('errorMsg', 'Please upload file with the given format.');
            return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=20&&m='.Input::get('company_id').'');
        endif;

        Session::flash('dataInsert', 'Successfully saved.');
        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=20&&m='.Input::get('company_id').'');
    }

    function addManageAttendenceDetail()
    {

        FinanceHelper::companyDatabaseConnection(Input::get('m'));
        $sub_department_id = Input::get('sub_department_id_1');
        $attendence_date = Input::get('attendence_date');
        $emp_id = Input::get('emp_id_');
        foreach ($emp_id as $row1) {
            $attendence_type = Input::get('attendence_status_' . $row1 . '');
            $attendence_remarks = Input::get('attendence_remarks_' . $row1 . '');

            $data1['emp_id'] = strip_tags($row1);
            $data1['sub_department_id'] = strip_tags($sub_department_id);
            $data1['attendence_date'] = strip_tags($attendence_date);
            $data1['attendence_type'] = strip_tags($attendence_type);
            $data1['remarks'] = strip_tags($attendence_remarks);
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $attendance = Attendence::where([['attendence_date', '=', Input::get('attendence_date')], ['emp_id', '=', $row1]]);

            if ($attendance->count() > 0):
                DB::table('attendence')->where([['attendence_date', '=', Input::get('attendence_date')], ['emp_id', '=', $row1]])->update($data1);
            else:
                DB::table('attendence')->insert($data1);
            endif;
        }

        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeAttendanceList?pageType=viewlist&&parentCode=8&&m=' . Input::get('m') . '#mima');
    }

    public function addEmployeeAllowanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $allowance_type = Input::get('allowance_type');
        foreach (Input::get('emp_code') as $key => $val):

            if(Input::get('allowance_amount_'.$val) != ''):
                $data1['emp_code'] = $val;
                $data1['allowance_type'] = $allowance_type;
                $data1['allowance_amount'] = Input::get('allowance_amount_'.$val);
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");
                DB::table('allowance')->insert($data1);
            endif;
        endforeach;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createAllowanceForm?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function addMaritalStatusDetail()
    {
        $martitalStatus = Input::get('martitalStatusSection');
        foreach ($martitalStatus as $row) {
            $martitalStatus_name = Input::get('marital_status_name_' . $row . '');
            $data1['marital_status_name'] = strip_tags($martitalStatus_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('marital_status')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewMaritalStatuslist?pageType=viewlist&&parentCode=21&&m=' . $_GET['m'] . '');
    }

    public function addEmployeeDeductionDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('deduction_type') as $key => $val):

            $data1['emp_code'] = strip_tags(Input::get('emp_code'));
            $data1['deduction_type'] = strip_tags($val);
            $data1['deduction_amount'] = strip_tags(Input::get('deduction_amount')[$key]);
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('deduction')->insert($data1);
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDeductionList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function addAdvanceSalaryDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $implode_date = explode("-", Input::get('deduction_month_year'));

        $data1['emp_code'] = Input::get('emp_code');
        $data1['advance_salary_amount'] = Input::get('advance_salary_amount');
        $data1['salary_needed_on'] = Input::get('salary_needed_on');
        $data1['account_head_id'] = Input::get('account_head_id');
        $data1['account_id'] = Input::get('account_id');
        $data1['deduction_year'] = $implode_date[0];
        $data1['deduction_month'] = $implode_date[1];
        $data1['detail'] = Input::get('advance_salary_detail');
        $data1['username'] = Auth::user()->name;
        $data1['approval_status'] = 1;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('advance_salary')->insert($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAdvanceSalaryList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function addLoanRequestDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $month_data = (explode("-", Input::get('needed_on_date')));
        $data1['emp_code'] = Input::get('emp_code');
        $data1['year'] = $month_data[0];
        $data1['month'] = $month_data[1];
        $data1['loan_type_id'] = Input::get('loan_type_id');
        $data1['loan_amount'] = Input::get('loan_amount');
        $data1['per_month_deduction'] = Input::get('per_month_deduction');
        $data1['account_head_id'] = Input::get('account_head_id');
        $data1['account_id'] = Input::get('account_id');
        $data1['description'] = Input::get('loan_description');
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        DB::table('loan_request')->insert($data1);

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLoanRequestList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function addDayWiseAttendanceDetail()
    {
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        $emp_code = Input::get('emp_code');
        $attendance_type = Input::get('attendance_type');

        $date = Input::get('attendance_date');
        $day = strtotime($date);
        $day = date('D', $day);

        $month = strtotime($date);
        $month = date('m', $month);

        $year = strtotime($date);
        $year = date('Y', $year);
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if ($emp_code == 'All'):
            $employees = Employee::select("emp_code")
                ->where([['status', '=', '1'], ['emp_department_id', '=', $department_id], ['region_id', '=', $region_id]])->orderBy('emp_code')->get();
        else:
            $employees = Employee::select("emp_code")
                ->where([['status', '=', '1'], ['emp_department_id', '=', $department_id], ['region_id', '=', $region_id],['emp_code', '=', $emp_code]])->orderBy('emp_code')->get();
        endif;

        foreach($employees as $val):
            $employeeArray[] = $val['emp_code'];
        endforeach;

        foreach ($employeeArray as $row):
            if(DB::table('attendance')->where([['emp_code','=',$row],['attendance_date', '=', $date], ['attendance_type', '=', 2]])->exists()):
                DB::table('attendance')->where([['emp_code','=',$row],['attendance_date', '=', $date]])->delete();
            endif;

            $data1['emp_code'] = $row;
            $data1['attendance_date'] = $date;
            $data1['clock_in'] = Input::get('clock_in_'.$row);
            $data1['clock_out'] = Input::get('clock_out_'.$row);
            $data1['attendance_status'] = Input::get('attendance_status_'.$row);
            $data1['day'] = $day;
            $data1['month'] = $month;
            $data1['year'] = $year;

            $data1['attendance_type'] = $attendance_type;
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('attendance')->insert($data1);
        endforeach;

        $data3['table_name']         = 'attendance';
        $data3['activity_id']        = null;
        $data3['deleted_emr_no']     = null;
        $data3['activity']           = 'Insert';
        $data3['module']             = 'hr';
        $data3['username']           = Auth::user()->name;
        $data3['date']               = date("Y-m-d");
        $data3['time']               = date("H:i:s");
        DB::table('log')->insert($data3);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?pageType=viewlist&&parentCode=21&&m=' . Input::get('m') . '');
    }

    public function addEmployeeMedicalDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $emp_code = Input::get('emp_code');

        $counter = 0;
        if ($request->file('medical_file_path')) {
            foreach ($request->file('medical_file_path') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'Emp Code ' . $emp_code . ' employee medical file ' . ' ' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_medical_documents', $file_name);

                    $fileUploadData['emp_code'] = $emp_code;
                    $fileUploadData['medical_file_name'] = $file_name;
                    $fileUploadData['medical_file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['medical_file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('employee_medical_documents')->insert($fileUploadData);
                }
            }
        }

        $data1['emp_code'] = $emp_code;
        $data1['disease_type_id'] = Input::get('disease_type_id');
        $data1['disease_date'] = Input::get('disease_date');
        $data1['amount'] = Input::get('amount');
        $data1['cheque_number'] = Input::get('cheque_number');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_medical')->insert($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeMedicalList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function addAllowanceTypeDetail()
    {
        foreach (Input::get('allowance_type') as $key => $val):

            $data['allowance_type'] = strip_tags($val);
            $data['username'] = Auth::user()->name;
            $data['company_id'] = Input::get('company_id');
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            DB::table('allowance_type')->insert($data);
        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAllowanceTypeList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    function addEmployeeBonusDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('bonus_month_year'));

        if (Input::get('check_list')):
            foreach (Input::get('check_list') as $key => $value):
                $emp_and_bonus = (explode("_", $value));
                $data1['emp_code'] = $emp_and_bonus[0];
                $data1['bonus_id'] = Input::get('bonus_id');
                $data1['bonus_amount'] = $emp_and_bonus[1];
                $data1['bonus_month'] = $month_year[1];
                $data1['bonus_year'] = $month_year[0];
                $data1['username'] = Auth::user()->name;
                $data1['bonus_status'] = 1;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");
                DB::table('bonus_issue')->insert($data1);
            endforeach;
        endif;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/IssueBonusDetailForm?pageType=viewlist&&parentCode=21&&m=' . Input::get('m') . '');
    }

    function addPayrollDetail()
    {
        $month_year = explode('-', Input::get('month_year'));
        $total_month_days = cal_days_in_month(CAL_GREGORIAN, $month_year[1], $month_year[0]);

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        foreach (Input::get('id') as $row):

            $emp_code = Input::get('emp_code_' .$row);

            if (Input::get('arrears_check_' . $row . '') == 'on'):
                $data3['arrears_check'] = "1";
                $arrearsData['emp_code'] = $emp_code;
                $arrearsData['arrears_amount'] = Input::get('net_salary_' . $row . '');
                $arrearsData['month'] = strip_tags($month_year[1]);
                $arrearsData['year'] = strip_tags($month_year[0]);
                $arrearsData['arrear_status'] = 1;
                $arrearsData['username'] = Auth::user()->name;
                $arrearsData['status'] = 1;
                $arrearsData['date'] = date("Y-m-d");
                $arrearsData['time'] = date("H:i:s");
                DB::table('arrears')->where([['emp_code', '=', Input::get('emp_code_' . $row . '')], ['year', '=', $month_year[0]], ['month', '=', $month_year[1]]])->delete();
                DB::table('arrears')->insert($arrearsData);
            else:
                $data3['arrears_check'] = "2";
            endif;

            $str = DB::selectOne("select max(convert(substr(`ps_no`,4,length(substr(`ps_no`,4))-4),signed integer)) reg from `payslip` where substr(`ps_no`,-4,2) = " . date('m') . " and substr(`ps_no`,-2,2) = " . date('y') . "")->reg;
            $ps_no = 'psc' . ($str + 1) . date('my');
            $arrears_id = Input::get('arrears_id_' .$row);

            $data3['ps_no'] = strip_tags($ps_no);
            $data3['emp_code'] = strip_tags($emp_code);
            $data3['month'] = strip_tags($month_year[1]);
            $data3['year'] = strip_tags($month_year[0]);
            $data3['month_year'] = strip_tags($month_year[0].'-'.$month_year[1].'-'.$total_month_days);
            $data3['working_days'] = strip_tags(Input::get('working_days_' .$row));
            $data3['region_id'] = strip_tags(Input::get('region_id_' .$row));
            $data3['designation_id'] = strip_tags(Input::get('designation_id_' .$row));
            $data3['department_id'] = strip_tags(Input::get('department_id_' .$row));
            $data3['present_days'] = strip_tags(Input::get('present_days_' .$row));
            $data3['absent_days'] = strip_tags(Input::get('absent_days_' .$row));
            $data3['emp_salary'] = strip_tags(Input::get('emp_salary_' .$row));
            $data3['gross_salary'] = strip_tags(Input::get('gross_salary_' .$row));
            $data3['arrears_id'] = strip_tags($arrears_id);
            $data3['arrears_amount'] = strip_tags(Input::get('arrears_amount_' .$row));
            $data3['bonus_amount'] = strip_tags(Input::get('bonus_amount_' .$row));
            $data3['overtime_hours_paid'] = strip_tags(Input::get('overtime_hours_paid_' .$row));
            $data3['overtime_amount'] = strip_tags(Input::get('overtime_amount_' .$row));
            $data3['lunch_allowance'] = strip_tags(Input::get('overtime_amount_' .$row));
            $data3['other_allowances'] = strip_tags(Input::get('other_allowances_' .$row));
            $data3['other_amount'] = strip_tags(Input::get('other_amount_' .$row));
            $data3['remarks'] = strip_tags(Input::get('remarks_' .$row));
            $data3['total_allowance'] = strip_tags(Input::get('total_allowance_' .$row));
            $data3['late_deduction_days'] = strip_tags(Input::get('late_deduction_days_' .$row));
            $data3['late_deduction_amount'] = strip_tags(Input::get('late_deduction_amount_' .$row));
            $data3['other_deduction'] = strip_tags(Input::get('other_deduction_' .$row));
            $data3['loan_id'] = strip_tags(Input::get('loan_id_' .$row));
            $data3['loan_amount'] = strip_tags(Input::get('loan_amount_' .$row));
            $data3['advance_salary_amount'] = strip_tags(Input::get('advance_salary_amount_' .$row));
            $data3['advance_salary_id'] = strip_tags(Input::get('advance_salary_id_' .$row));
            $data3['tax_amount'] = strip_tags(Input::get('tax_amount_' .$row));
            $data3['eobi_amount'] = strip_tags(Input::get('eobi_amount_' .$row));
            $data3['total_deduction'] = strip_tags(Input::get('total_deduction_' .$row));
            $data3['net_salary'] = strip_tags(Input::get('net_salary_' .$row));
            $data3['username'] = Auth::user()->name;
            $data3['status'] = 1;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");

            Arrears::where([['emp_code', '=', $emp_code], ['id', '=', $arrears_id]])->update(['arrear_status' => 2]);
            Payslip::where([['emp_code', '=', $emp_code], ['year', '=', $month_year[0]], ['month', '=', $month_year[1]]])->delete();
            DB::table('payslip')->insert($data3);

            $log['table_name'] = 'payslip';
            $log['activity_id'] = null;
            $log['deleted_emr_no'] = null;
            $log['activity'] = 'Insert';
            $log['module'] = 'hr';
            $log['username'] = Auth::user()->name;
            $log['date'] = date("Y-m-d");
            $log['time'] = date("H:i:s");
            DB::table('log')->insert($log);

        endforeach;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewPayrollList?pageType=viewlist&&parentCode=9&&m=' . Input::get('m') . '');
    }


    //new code end






    public function addWorkingHoursPolicyDetail()
    {
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $data['working_hours_policy'] = strip_tags(Input::get('working_hours_policy'));
        $data['start_working_hours_time'] = strip_tags(Input::get('start_working_hours_time'));
        $data['end_working_hours_time'] = strip_tags(Input::get('end_working_hours_time'));
        $data['working_hours_grace_time'] = strip_tags(Input::get('working_hours_grace_time'));
        $data['end_time_for_comming_deduct_half_day'] = strip_tags(Input::get('end_time_for_comming_deduct_half_day'));
        $data['short_leave_time'] = strip_tags(Input::get('short_leave_time'));
        $data['half_day_time'] = strip_tags(Input::get('half_day_time'));
        $data['terms_conditions'] = strip_tags(Input::get('terms_conditions'));
        $data['username'] = Auth::user()->name;
        $data['user_id'] = Auth::user()->id;
        $data['status'] = 1;
        $data['time'] = date("H:i:s");
        $data['date'] = date("Y-m-d");
        DB::table('working_hours_policy')->insert($data);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewWorkingHoursPolicyList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $m . '');
    }

    public function addLeavesPolicyDetail()
    {
        $data1['leaves_policy_name'] = strip_tags(Input::get('leaves_policy_name'));
        $data1['policy_date_from'] = Input::get('PolicyDateFrom');
        $data1['policy_date_till'] = Input::get('PolicyDateTill');
        $data1['total_leaves'] = Input::get('totalLeaves');
        $data1['terms_conditions'] = Input::get('terms_conditions');
        $data1['fullday_deduction_rate'] = Input::get('full_day_deduction_rate');
        $data1['halfday_deduction_rate'] = Input::get('half_day_deduction_rate');
        $data1['per_hour_deduction_rate'] = Input::get('per_hour_deduction_rate');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['time'] = date("H:i:s");
        $data1['date'] = date("Y-m-d");

        $last_id = DB::table('leaves_policy')->insertGetId($data1);

        foreach (Input::get('leaves_type_id') as $key => $val):

            $data2['leaves_policy_id'] = $last_id;
            $data2['leave_type_id'] = $val;
            $data2['no_of_leaves'] = Input::get('no_of_leaves')[$key];
            $data2['username'] = Auth::user()->name;;
            $data2['status'] = 1;
            $data2['time'] = date("H:i:s");
            $data2['date'] = date("Y-m-d");
            DB::table('leaves_data')->insert($data2);

        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLeavesPolicyList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }
    public function addManuallyLeaves()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $leave_policy = Employee::where([['emr_no','=',Input::get('emr_no')],['leaves_policy_id','!=',0]]);
        if($leave_policy->count() == 0 ){
            Session::flash('dataDelete', 'Please Select Leaves Policy For User !');
            return Redirect::to('hr/createManualLeaves?pageType=viewlist&&parentCode=21&&m=' . Input::get('m') . '');
        }

        $emp_leave_policy = $leave_policy->first();
        CommonHelper::reconnectMasterDatabase();
        $policy_date_from = LeavesPolicy::where([['id','=',$emp_leave_policy->leaves_policy_id]])->value('policy_date_from');

        $casual_leaves = LeavesData::where([['leaves_policy_id','=',$emp_leave_policy->leaves_policy_id],['leave_type_id','=','3']])->value('no_of_leaves');
        $annual_leaves = LeavesData::where([['leaves_policy_id','=',$emp_leave_policy->leaves_policy_id],['leave_type_id','=','1']])->value('no_of_leaves');
        $sick_leaves =   LeavesData::where([['leaves_policy_id','=',$emp_leave_policy->leaves_policy_id],['leave_type_id','=','2']])->value('no_of_leaves');

        $leaves [1] = $annual_leaves-Input::get('annual_leaves');
        $leaves [3]= $casual_leaves-Input::get('casual_leaves');
        $leaves [2]= $sick_leaves-Input::get('sick_leaves');

        TransferedLeaves::where([['emr_no','=',Input::get('emr_no')],['leaves_policy_id','=',$emp_leave_policy->leaves_policy_id]])->delete();
        LeaveApplication::where([['view','=','no'],['emr_no','=',Input::get('emr_no')],['leave_policy_id','=',$emp_leave_policy->leaves_policy_id]])->delete();
        LeaveApplicationData::where([['view','=','no'],['emr_no','=',Input::get('emr_no')],['leave_policy_id','=',$emp_leave_policy->leaves_policy_id]])->delete();

        foreach ($leaves as $key=>$value)
        {
            if($key == 1 )
            {

                $leaveApplicationData['emr_no']          = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type']      = $key;
                $leaveApplicationData['leave_day_type']  = 1;
                $leaveApplicationData['reason']          = "-";
                $leaveApplicationData['leave_address']   = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status']          = 1;
                $leaveApplicationData['username']        = Auth::user()->name;
                $leaveApplicationData['date']            = date("Y-m-d");
                $leaveApplicationData['time']            = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

                $annualLeavesData['emr_no']               = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] =$emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type']           = $key;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['leave_day_type']       = 1;
                $annualLeavesData['no_of_days']           = $value;
                $annualLeavesData['from_date']            = date("Y-m-d");
                $annualLeavesData['to_date']              = date("Y-m-d");
                $annualLeavesData['status']               = 1;
                $annualLeavesData['username']             = Auth::user()->name;
                $annualLeavesData['date']                 = date("Y-m-d");
                $annualLeavesData['time']                 = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);

            }
            elseif($key == 2 )
            {
                $leaveApplicationData['emr_no']          = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type']      = $key;
                $leaveApplicationData['leave_day_type']  = 1;
                $leaveApplicationData['reason']          = "-";
                $leaveApplicationData['leave_address']   = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status']          = 1;
                $leaveApplicationData['username']        = Auth::user()->name;
                $leaveApplicationData['date']            = date("Y-m-d");
                $leaveApplicationData['time']            = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

                $annualLeavesData['emr_no']               = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] =$emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type']           = $key;
                $annualLeavesData['leave_day_type']       = 1;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['no_of_days']           = $value;
                $annualLeavesData['from_date']            = date("Y-m-d");
                $annualLeavesData['to_date']              = date("Y-m-d");
                $annualLeavesData['status']               = 1;
                $annualLeavesData['username']             = Auth::user()->name;
                $annualLeavesData['date']                 = date("Y-m-d");
                $annualLeavesData['time']                 = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);
            }
            elseif($key == 3 )
            {
                $leaveApplicationData['emr_no']          = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type']      = $key;
                $leaveApplicationData['leave_day_type']  = 1;
                $leaveApplicationData['reason']          = "-";
                $leaveApplicationData['leave_address']   = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status']          = 1;
                $leaveApplicationData['username']        = Auth::user()->name;
                $leaveApplicationData['date']            = date("Y-m-d");
                $leaveApplicationData['time']            = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);
                $annualLeavesData['emr_no']               = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] =$emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type']           = $key;
                $annualLeavesData['leave_day_type']       = 1;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['no_of_days']           = $value;
                $annualLeavesData['from_date']            = date("Y-m-d");
                $annualLeavesData['to_date']              = date("Y-m-d");
                $annualLeavesData['status']               = 1;
                $annualLeavesData['username']             = Auth::user()->name;
                $annualLeavesData['date']                 = date("Y-m-d");
                $annualLeavesData['time']                 = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);
            }


        }

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManualLeaves?pageType=viewlist&&parentCode=21&&m=' . Input::get('m') . '');


    }


    public function addVehicleTypeDetail()
    {

        foreach (Input::get('vehicle_type') as $key => $val):

            $data1['vehicle_type_name'] = strip_tags($val);
            $data1['vehicle_type_cc'] = strip_tags(Input::get('vehicle_cc')[$key]);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('vehicle_type')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewVehicleTypeList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');


    }

    public function addCarPolicyDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('designation_id') as $key => $val):

            $data1['designation_id'] = $val;
            $data1['vehicle_type_id'] = Input::get('vehicle_type_id')[$key];
            $data1['policy_name'] = Input::get('policy_name')[$key];
            $data1['start_salary_range'] = Input::get('start_salary_range')[$key];
            $data1['end_salary_range'] = Input::get('end_salary_range')[$key];
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('car_policy')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewCarPolicyList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }



    public function addEOBIDetail()
    {
        foreach (Input::get('EOBI_name') as $key => $val):

            $data1['EOBI_name'] = $val;
            $data1['EOBI_amount'] = Input::get('EOBI_amount')[$key];
            $data1['month_year'] = Input::get('month_year')[$key];
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('eobi')->insert($data1);

        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEOBIList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');


    }


    public function addTaxesDetail()
    {

        foreach (Input::get('tax_name') as $key => $val):

            $data1['tax_name'] = $val;
            $data1['salary_range_from'] = Input::get('salary_range_from')[$key];
            $data1['salary_range_to'] = Input::get('salary_range_to')[$key];
            $data1['tax_mode'] = Input::get('tax_mode')[$key];
            $data1['tax_percent'] = Input::get('tax_percent')[$key];
            $data1['tax_month_year'] = Input::get('tax_month_year')[$key];
            $data1['status'] = 1;
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('tax')->insert($data1);

        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewTaxesList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    public function addBonusDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('Bonus_name') as $key => $val):
            $data1['bonus_name'] = $val;
            $data1['percent_of_salary'] = Input::get('percent_of_salary')[$key];
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('bonus')->insert($data1);

        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewBonusList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    function addHolidaysDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $data1['holiday_name'] = Input::get('holiday_reason');
        $data1['holiday_date'] = Input::get('holiday_date');
        $data1['year'] = Input::get('year');
        $data1['month'] = Input::get('month');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date']    = date("Y-m-d");
        $data1['time']    = date("H:i:s");

        DB::table('holidays')->insert($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createHolidaysForm?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function addAttendanceProgressDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        foreach (Input::get('emp_code') as $value):

            DB::table('payroll_data')->where([['emp_code', '=', $value], ['month', '=', Input::get('month_' . $value)], ['year', '=', Input::get('year_' . $value)]])->delete();
            DB::table('payslip')->where([['emp_code', '=', $value], ['month', '=', Input::get('month_' . $value)], ['year', '=', Input::get('year_' . $value)]])->delete();

            $data["emp_code"]               = $value;
            $data["month"]                  = Input::get('month_' . $value);
            $data["year"]                   = Input::get('year_' . $value);
            $data["working_days"]           = Input::get('working_days_' . $value);
            $data["attendance_type"]        = Input::get('attendance_type_' . $value);
            $data["present_days"]           = Input::get('present_days_' . $value);
            $data["absent_days"]            = Input::get('absent_days_' . $value);
            $data["net_overtime_hours"]     = Input::get('net_overtime_hours_' . $value);
            $data["overtime_hours_paid"]    = Input::get('overtime_hours_paid_' . $value);
            $data["late_arrival"]           = Input::get('late_arrival_' . $value);
            $data["late_deduction_days"]    = Input::get('late_deduction_days_' . $value);
            $data['username']               = Auth::user()->name;
            $data['status']                 = 1;
            $data['date']                   = date("Y-m-d");
            $data['time']                   = date("H:i:s");

            $payroll_data = DB::table('payroll_data')->where([['emp_code', '=', $value], ['month', '=', Input::get('month_' . $value)], ['year', '=', Input::get('year_' . $value)]]);

            if($payroll_data->count() > 0):
                if(Input::get('check_'.$value)):
                    DB::table('payroll_data')->where([['emp_code', '=', $value], ['month', '=', Input::get('month_' . $value)], ['year', '=', Input::get('year_' . $value)]])->delete();
                    DB::table('payslip')->where([['emp_code', '=', $value], ['month', '=', Input::get('month_' . $value)], ['year', '=', Input::get('year_' . $value)]])->delete();

                    DB::table('payroll_data')->insert($data);
                else:
                    // no change
                endif;
            else:
                DB::table('payroll_data')->insert($data);
            endif;

        endforeach;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/ViewAttendanceProgress?pageType=viewlist&&parentCode=21&&m=' . Input::get('m') . '');

    }

    public function addEmployeeDepositDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $employeeDeposit = new EmployeeDeposit;

        $month_and_year = explode('-', $request->to_be_deduct_on_date);
        $employeeDeposit->sub_department_id = $request->sub_department_id;
        $employeeDeposit->acc_no = $request->employee_id;
        $employeeDeposit->deposit_name = $request->deposit_name;
        $employeeDeposit->deposit_amount = $request->deposit_amount;
        $employeeDeposit->deduction_month = $month_and_year[1];
        $employeeDeposit->deduction_year = $month_and_year[0];
        $employeeDeposit->username = Auth::user()->name;
        $employeeDeposit->status = 1;
        $employeeDeposit->date = date("Y-m-d");
        $employeeDeposit->time = date("H:i:s");

        $employeeDeposit->save();
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeDepositList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function addManualyAttendance()
    {
        //echo "<pre>";
        //print_r(Input::get('attendance_date')); die;

        $name_array = $_POST['attendance_date'];

        for ($i = 0; $i < count($name_array); $i++)
        {

            $manualyAttData['acc_no'] = Input::get('acc_no')[$i];
            $manualyAttData['emp_name'] = Input::get('emp_name')[$i];
            $manualyAttData['day'] = Input::get('day')[$i];
            $manualyAttData['month'] = Input::get('month')[$i];
            $manualyAttData['year'] = Input::get('year')[$i];
            $manualyAttData['attendance_date'] = Input::get('attendance_date')[$i];
            $manualyAttData['clock_in'] = Input::get('clock_in')[$i];
            $manualyAttData['clock_out'] = Input::get('clock_out')[$i];
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            DB::table('attendance')->insert($manualyAttData);
            CommonHelper::reconnectMasterDatabase();

        }
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#mima');
    }

    public function addEmployeeAttendanceFileDetail()
    {
        $data  = Excel::toArray(true,request()->file('employeeAttendanceFile'));
        $month_year = explode('-', Input::get('month_year'));
        $counter1  = 1;
        $counter2  = 1;

        if(trim($data[0][0][0]) == 'S. No.' && trim($data[0][0][1]) == 'EMP Code' && trim($data[0][0][2]) == 'Employee Name' &&
            trim($data[0][0][3]) == 'Present Days' && trim($data[0][0][4]) == 'Absent Days' && trim($data[0][0][5]) == 'Leaves (Sick, Casual, Annual)' &&
            trim($data[0][0][6]) == 'Remarks'):

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            foreach($data as $value):
                if($counter1 == 1 || $counter1 == 2 || $counter1 == 3):
                    foreach($value as $value2):
                        if( $value2[1] == '' ||  $value2[1] == 'EMP Code' || $value2[1] == 'EMP Code ') continue ;
                        DB::table('attendance')->where([['attendance_type','=',1],['month','=',$month_year[1]],['year','=',$month_year[0]],['emp_code','=',$value2[1]]])->delete();
                        DB::table('payroll_data')->where([['month','=',$month_year[1]],['year','=',$month_year[0]],['emp_code','=',$value2[1]]])->delete();

                        $data1['emp_code']           = $value2[1];
                        $data1['present_days']       = $value2[3];
                        $data1['absent_days']        = $value2[4];
                        $data1['no_of_leaves']       = $value2[5];
                        $data1['remarks']            = $value2[6];
                        $data1['month']              = $month_year[1];
                        $data1['year']               = $month_year[0];
                        $data1['username']           = Auth::user()->name;
                        $data1['attendance_type']    = 1;
                        $data1['status']             = 1;
                        $data1['date']               = date("Y-m-d");
                        $data1['time']               = date("H:i:s");

                        DB::table('attendance')->insert($data1);

                    endforeach;
                endif;
                $counter1++;
            endforeach;

            $log['table_name']         = 'attendance';
            $log['activity_id']        = null;
            $log['deleted_emr_no']     = null;
            $log['activity']           = 'Upload';
            $log['module']             = 'hr';
            $log['username']           = Auth::user()->name;
            $log['date']               = date("Y-m-d");
            $log['time']               = date("H:i:s");
            DB::table('log')->insert($log);
            CommonHelper::reconnectMasterDatabase();

        else:
            Session::flash('errorMsg', 'Please upload file with the given format.');
            return Redirect::to('hr/createManageAttendanceForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('m') . '#mima');
        endif;


        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('m') . '#mima');

    }

    public function addEmployeeCategoryDetail()
    {
        foreach (Input::get('employee_category_name') as $key => $val):
            $data1['employee_category_name'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('employee_category')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeCategoryList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addEmployeeGradesDetail()
    {
        foreach (Input::get('employee_grade_type') as $key => $val):
            $data1['employee_grade_type'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('grades')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeGradesList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addEmployeeLocationsDetail()
    {

        $EmployeeLocationSection = Input::get('EmployeeLocationSection');
        foreach ($EmployeeLocationSection as $row) {
            $region_id = Input::get('region_id_' . $row . '');
            $location_name = Input::get('employee_location_' . $row . '');
            $data1['region_id'] = strip_tags($region_id);
            $data1['employee_location'] = strip_tags($location_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('locations')->insert($data1);
        }

        Session::flash('dataInsert', 'Successfully Saved.');
        return Redirect::to('hr/viewEmployeeLocationsList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addEmployeeRegionsDetail()
    {
        foreach (Input::get('employee_region') as $key => $val):
            $data1['employee_region'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('regions')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeRegionsList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addEmployeeDegreeTypeDetail()
    {
        foreach(Input::get('degree_type_name') as $key => $val):
            $data1['degree_type_name']        = $val;
            $data1['company_id'] 	   = Input::get('company_id');
            $data1['username']         = Auth::user()->name;;
            $data1['status'] 		   = 1;
            $data1['date']     		   = date("Y-m-d");
            $data1['time']     		   = date("H:i:s");
            DB::table('degree_type')->insert($data1);
        endforeach;
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeDegreeTypeList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function addEmployeeExitClearanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $exit_clearance_check = Input::get('exit_clearance_check');

        if($exit_clearance_check == 1):

            $emp_code = Input::get('emp_code');
            $data1['emp_code']           = $emp_code;

            $data1['leaving_type'] = 			Input::get('leaving_type');
            $data1['supervisor_name'] = 		Input::get('supervisor_name');
            $data1['signed_by_supervisor'] = 	Input::get('signed_by_supervisor');
            $data1['last_working_date'] = 		Input::get('last_working_date');

            $data1['room_key'] = 					            Input::get('room_key');
            $data1['room_key_remarks'] = 			            Input::get('room_key_remarks');
            $data1['mobile_sim'] = 				                Input::get('mobile_sim');
            $data1['mobile_sim_remarks'] = 		                Input::get('mobile_sim_remarks');
            $data1['fuel_card'] = 		                        Input::get('fuel_card');
            $data1['fuel_card_remarks'] =                       Input::get('fuel_card_remarks');
            $data1['mfm_employee_card'] = 				        Input::get('mfm_employee_card');
            $data1['mfm_employee_card_remarks']= 			    Input::get('mfm_employee_card_remarks');
            $data1['client_access_card'] = 				        Input::get('client_access_card');
            $data1['client_access_card_remarks'] = 		        Input::get('client_access_card_remarks');
            $data1['medical_insurance_card'] = 				    Input::get('medical_insurance_card');
            $data1['medical_insurance_card_remarks'] = 		    Input::get('medical_insurance_card_remarks');
            $data1['eobi_card'] = 				                Input::get('eobi_card');
            $data1['eobi_card_remarks'] = 					    Input::get('eobi_card_remarks');
            $data1['biometric_scan'] = 			                Input::get('biometric_scan');
            $data1['biometric_scan_remarks'] = 				    Input::get('biometric_scan_remarks');
            $data1['payroll_deduction'] = 		                Input::get('payroll_deduction');
            $data1['payroll_deduction_remarks'] = 		        Input::get('payroll_deduction_remarks');
            $data1['info_sent_to_client'] =                     Input::get('info_sent_to_client');
            $data1['info_sent_to_client_remarks'] = 		    Input::get('info_sent_to_client_remarks');
            $data1['client_exit_checklist']= 		        	Input::get('client_exit_checklist');
            $data1['client_exit_checklist_remarks'] = 		    Input::get('client_exit_checklist_remarks');
            $data1['exit_interview'] = 		                    Input::get('exit_interview');
            $data1['exit_interview_remarks'] = 				    Input::get('exit_interview_remarks');
            $data1['laptop'] = 		                            Input::get('laptop');
            $data1['laptop_remarks'] = 				            Input::get('laptop_remarks');
            $data1['desktop_computer'] = 				        Input::get('desktop_computer');
            $data1['desktop_computer_remarks']= 		  	    Input::get('desktop_computer_remarks');
            $data1['email_account_deactivated'] = 				Input::get('email_account_deactivated');
            $data1['email_account_deactivated_remarks'] = 		Input::get('email_account_deactivated_remarks');
            $data1['toolkit_ppe'] = 				            Input::get('toolkit_ppe');
            $data1['toolkit_ppe_remarks'] = 		            Input::get('toolkit_ppe_remarks');
            $data1['uniform'] = 				                Input::get('uniform');
            $data1['uniform_remarks'] = 				        Input::get('uniform_remarks');
            $data1['advance_loan'] = 				            Input::get('advance_loan');
            $data1['advance_loan_remarks'] = 		            Input::get('advance_loan_remarks');
            $data1['extra_leaves'] = 				            Input::get('extra_leaves');
            $data1['extra_leaves_remarks'] = 		            Input::get('extra_leaves_remarks');
            $data1['final_settlement'] = 				        Input::get('final_settlement');
            $data1['final_settlement_remarks'] = 				Input::get('final_settlement_remarks');
            $data1['note'] =                                    Input::get('note');

            $data1['approval_status']  = 1;
            $data1['status'] 		   = 1;
            $data1['company_id'] 	   = Input::get('company_id');
            $data1['username']         = Auth::user()->name;
            $data1['date']     		   = date("Y-m-d");
            $data1['time']     		   = date("H:i:s");

            DB::table('employee_exit')->where([['emp_code', $emp_code]])->delete();
            DB::table('employee_exit')->insert($data1);

        elseif($exit_clearance_check == 2):

            $emp_code = Input::get('emp_code');

            $date1 = strtotime(Input::get('salary_to'));
            $date2 = strtotime(Input::get('salary_from'));
            $emp_salary = Input::get('emp_salary');

            $diff = $date1 - $date2;
            $days = round($diff / 86400) + 1;

            $salary = round($emp_salary/30*$days);

            $data['emp_code']        = $emp_code;
            $data['salary_from']   = Input::get('salary_from');
            $data['salary_to']     = Input::get('salary_to');
            $data['others']        = Input::get('others');
            $data['notice_pay']    = Input::get('notice_pay');
            $data['advance']       = Input::get('advance');
            $data['mobile_bill']   = Input::get('mobile_bill');
            $data['toolkit']       = Input::get('toolkit');
            $data['mfm_id_card']   = Input::get('mfm_id_card');
            $data['uniform']       = Input::get('uniform');
            $data['laptop']        = Input::get('laptop');
            $data['any_others']    = Input::get('any_others');

            $total_addition  = Input::get('gratuity') + Input::get('others') + $salary;
            $total_deduction = Input::get('notice_pay') + Input::get('advance') + Input::get('mobile_bill') + Input::get('toolkit') +
                Input::get('mfm_id_card') + Input::get('uniform') + Input::get('laptop') + Input::get('any_others');

            $data['total_addition'] = $total_addition;
            $data['total_deduction'] = $total_deduction;
            $data['grand_total'] = $total_addition - $total_deduction;

            $data['approval_status']  = 1;
            $data['status'] 		   = 1;
            $data['username']         = Auth::user()->name;
            $data['date']     		   = date("Y-m-d");
            $data['time']     		   = date("H:i:s");

            DB::table('final_settlement')->where([['emp_code', $emp_code]])->delete();
            DB::table('final_settlement')->insert($data);

        endif;

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeExitClearanceList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }


    public function addEmployeeIdCardRequestDetail(Request $request)
    {
        $counter =0;
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        if($request->hasFile('fir_copy')):
            $counter++;
            $extension = $request->file('fir_copy')->getClientOriginalExtension();
            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('fir_copy')->getClientOriginalExtension();
            $path = $request->file('fir_copy')->storeAs('uploads/employee_id_card_fir_copy', $file_name);
            $data1['fir_copy_path'] =    'app/'.$path;
            $data1['fir_copy_extension'] =  $extension;
        endif;

        if($request->hasFile('card_image')):
            $counter++;
            $extension = $request->file('card_image')->getClientOriginalExtension();
            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('card_image')->getClientOriginalExtension();
            $path = $request->file('card_image')->storeAs('uploads/employee_id_card_images', $file_name);
            $data1['card_image_path'] =    'app/'.$path;
            $data1['card_image_extension'] =  $extension;
        endif;

        if(Input::get('card_replacement') == 0)
        {
            $data1['fir_copy_path'] =    null;
            $data1['fir_copy_extension'] = null;
        }

        $emr_no = Input::get('employee_id');

        $data1['emr_no']           = Input::get('emr_no');
        $data1['posted_at']        = Input::get('posted_at');
        $data1['card_replacement'] = Input::get('card_replacement');
        $data1['replacement_type'] = Input::get('replacement_type');
        $data1['payment']          = Input::get('payment');
        $data1['username']         = Auth::user()->name;
        $data1['approval_status']  = 1;
        $data1['status'] 		   = 1;
        $data1['card_status']      = 1;
        $data1['date']     		   = date("Y-m-d");
        $data1['time']     		   = date("H:i:s");

        DB::table('employee_card_request')->where([['emr_no', $emr_no]])->delete();

        DB::table('employee_card_request')->insert($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeIdCardRequestList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function addEmployeePromotionDetail(Request $request)
    {
        $emp_code =  Input::get('emp_code');
        $addAllowancesCheck = Input::get('addAllowancesCheck');

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $data['emp_code'] = 		        $emp_code;
        $data['designation_id'] = 		Input::get('designation_id');
        $data['increment'] = 		    Input::get('increment');
        $data['salary'] = 		        Input::get('salary');
        $data['promotion_date'] = 		Input::get('promotion_date');
        $data['status']=               1;
        $data['approval_status']=      1;
        $data['username']           = Auth::user()->name;
        $data['date']=                 date("Y-m-d");
        $data['time']=                 date("H:i:s");

        $id = DB::table('employee_promotion')->insertGetId($data);

        $counter = 0;
        if ($request->file('promotion_letter')) {
            foreach ($request->file('promotion_letter') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'Emp Code '.$emp_code . ' promotion letter ' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/promotion_letter', $file_name);

                    $fileUploadData['promotion_id'] = $id;
                    $fileUploadData['file_name'] = $file_name;
                    $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('promotion_letter')->insert($fileUploadData);
                }
            }
        }

        if($addAllowancesCheck == 1)
        {
            DB::table('allowance')->where([['emp_code', $emp_code]])->delete();

            foreach (Input::get('allowance_type') as $key => $val):
                $data1['emp_code'] = $emp_code;
                $data1['allowance_type'] = strip_tags($val);
                $data1['allowance_amount'] = strip_tags(Input::get('allowance_amount')[$key]);
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('allowance')->insert($data1);
            endforeach;
        }
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/createEmployeePromotionForm?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }


    public function addEmployeeProjectsDetail()
    {
        $employeeProjectSection = Input::get('project_name');
        foreach($employeeProjectSection as $row) {

            $data1['project_name'] = $row;
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('employee_projects')->insert($data1);
        }
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeProjectsList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function addEmployeeTransferDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $promotion_id = '';
        $transfer_id = '';
        $location_check = Input::get('location_check');
        $transfer_project_check = Input::get('transfer_project_check');
        if($location_check == 1)
        {
            $data['emr_no'] = Input::get('emr_no');
            $data['designation_id'] = Input::get('designation_id');
            $data['grade_id'] = Input::get('grade_id');
            $data['increment'] = Input::get('increment');
            $data['salary'] = Input::get('salary');
            $data['promotion_date'] = Input::get('promotion_date');
            $data['username'] = Auth::user()->name;
            $data['approval_status'] = 1;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");

            $promotion_id = DB::table('employee_promotion')->insertGetId($data);
        }

        if($transfer_project_check == 1)
        {
            $region_id = Input::get('region_id');
            $emp_category_id = Input::get('emp_category_id');
            $transfer_project_id = Input::get('transfer_project_id');
            $emr_no = Input::get('emr_no');
            $m = Input::get('company_id');
            CommonHelper::companyDatabaseConnection($m);
            $data2['emr_no'] = $emr_no;
            $data2['employee_project_id'] = $transfer_project_id;
            $data2['emp_region_id'] = $region_id;
            $data2['emp_categoery_id'] = $emp_category_id;
            $data2['username'] = Auth::user()->name;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $transfer_id = DB::table('transfer_employee_project')->insertGetId($data2);
            $data5['active'] = 2;
            Employee::where('emr_no','=',$emr_no)->update($data5);
            $previous = DB::table('transfer_employee_project')->where([['emr_no','=',$emr_no],['id', '<', $transfer_id]])->max('id');
            if(count($previous) != '0'){
                $data4['active'] = 2;
                DB::table('transfer_employee_project')->where('id','=',$previous)->delete();
            }
        }

        $data1['emr_no'] = Input::get('emr_no');
        $data1['location_id'] = Input::get('location_id');
        $data1['promotion_id'] = $promotion_id;
        $data1['transfer_project_id'] = $transfer_id;
        $data1['approval_status'] = 1;
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        $id = DB::table('employee_location')->insertGetId($data1);

        $check_letter_uploading = $_FILES['letter_uploading']['name'][0];
        if($check_letter_uploading != '') {
            $letter_uploading = $request->file('letter_uploading');
            $extention = [];
            foreach ($letter_uploading as $key => $value) {
                $file_name =  time().'_'. Input::get('emr_no').'_'.$key.'_'.$value->getClientOriginalExtension();
                $paths = 'app/' . $value->storeAs('uploads/transfer_letter', $file_name);
                $path = $_FILES['letter_uploading']['name'][$key];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $extention['file_type'] = $ext;
                $extention['letter_uploading'] = $paths;
                $extention['emp_location_id'] = $id;
                $extention['date'] = date("Y-m-d");
                $extention['time'] = date("H:i:s");

                DB::table('transfer_letter')->insert($extention);
            }
        }
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeTransferList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function addEmployeeFuelDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        if(!empty(Input::get('fuel_data'))):
            foreach (Input::get('fuel_data') as $fuel_rows):

                $fuel_date = Input::get('fuel_date_'.$fuel_rows.'');

                if (EmployeeFuelData::where([['emr_no', '=', Input::get('emr_no')],['fuel_date', '=',$fuel_date],['status', '=', 1]])->exists()) {
                    DB::table('employee_fuel_data')->where([['emr_no', '=', Input::get('emr_no')],['fuel_date', '=',$fuel_date],['status', '=', 1]])->delete();
                }
                $data['emr_no'] =           Input::get('emr_no');
                $data['fuel_date'] =        $fuel_date;
                $data['from'] =             Input::get('from_'.$fuel_rows.'');
                $data['to'] =               Input::get('to_'.$fuel_rows.'');
                $data['km'] =               Input::get('km_'.$fuel_rows.'');
                $data['fuel_month'] =       date('m',strtotime($fuel_date));
                $data['fuel_year'] =        date('Y',strtotime($fuel_date));

                $data['approval_status'] =  1;
                $data['status'] =           1;
                $data['username'] =         Auth::user()->name;
                $data['date'] =             date("Y-m-d");
                $data['time'] =             date("H:i:s");

                DB::table('employee_fuel_data')->insert($data);
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeFuel?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function addHrLetters()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $letter_id = Input::get('letter_id');

        if($letter_id == 1)
        {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_warning_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrWarningLetter/'.$last_id.'/'.Input::get('company_id').'?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');

        }

        if($letter_id == 2)
        {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['confirmation_from'] = Input::get('confirmation_from');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id =DB::table('hr_mfm_south_increment_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrMfmSouthIncrementLetter/'.$last_id.'/'.Input::get('company_id').'?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');

        }

        if($letter_id == 3)
        {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['performance_from'] = Input::get('performance_from');
            $data1['performance_to'] = Input::get('performance_to');
            $data1['confirmation_from'] = Input::get('confirmation_from');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id =DB::table('hr_mfm_south_without_increment_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrMfmSouthWithoutIncrementLetter/'.$last_id.'/'.Input::get('company_id').'?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');

        }
        if($letter_id == 4)
        {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['conclude_date'] = Input::get('conclude_date');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_contract_conclusion_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrContractConclusionLetter/'.$last_id.'/'.Input::get('company_id').'?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');

        }
        if($letter_id == 5)
        {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id =DB::table('hr_termination_format1_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrTerminationFormat1Letter/'.$last_id.'/'.Input::get('company_id').'?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');


        }
        if($letter_id == 6)
        {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_termination_format2_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrTerminationFormat2Letter/'.$last_id.'/'.Input::get('company_id').'?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');

        }

        if($letter_id == 7)
        {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['transfer_date'] = Input::get('transfer_date');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_transfer_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrTransferLetter/'.$last_id.'/'.Input::get('company_id').'?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');

        }

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
    }


    public function AddLettersFile(Request $request)
    {


        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        /*Image uploading start*/

        $extension = $request->file('letter_file')->getClientOriginalExtension();
        $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('letter_file')->getClientOriginalExtension();
        $path = 'app/'.$request->file('letter_file')->storeAs('uploads/employee_hr_letters', $file_name);

        /*Image uploading end*/

        $data1['emr_no']        = Input::get('emr_no');
        $data1['letter_type']   = Input::get('letter_type');
        $data1['letter_path']   = $path;
        $data1['file_type']     = $extension;
        $data1['status']        = 1;
        $data1['username']      = Auth::user()->name;
        $data1['date']          = date("Y-m-d");
        $data1['time']          = date("H:i:s");


        DB::table('letter_files')->insert($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/uploadLettersFile?&&m=' . Input::get('company_id'). '#mima');


    }

    public function addEquipmentDetail()
    {
        foreach (Input::get('equipment_name') as $key => $val):
            $data1['equipment_name'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('employee_equipments')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEquipmentsList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#mima');
    }

    public function addEmployeeEquipmentDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        DB::table('employee_equipments')->where('emr_no', Input::get('emr_no'))->delete();
        foreach (Input::get('equipment_id') as $key => $val):

            if(strip_tags($val) == 11):
                $data['model_number'] = Input::get('model_number');
                $data['mobile_number'] = Input::get('mobile_number');
                $data['sim_number'] = Input::get('sim_number');
            endif;

            $data['equipment_id'] = strip_tags($val);
            $data['emr_no'] = Input::get('emr_no');
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['approval_status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");

            $last_insert_id = DB::table('employee_equipments')->insertGetId($data);

            if(strip_tags($val) == 9):

                if ($request->file('insurance_path')):
                    $file_name1 = Input::get('emr_no') . '_' . time() . '.' . $request->file('insurance_path')->getClientOriginalExtension();
                    $path1 = 'app/' . $request->file('insurance_path')->storeAs('uploads/employee_insurance_copy', $file_name1);
                    $data1['insurance_path'] = $path1;
                    $data1['insurance_type'] = $request->file('insurance_path')->getClientOriginalExtension();
                endif;

                $data1['insurance_number'] = Input::get('insurance_number');

                DB::table('employee')->where('emr_no', Input::get('emr_no'))->update($data1);
            endif;

            if(strip_tags($val) == 10):

                if ($request->file('eobi_path')):
                    $file_name1 = Input::get('emr_no') . '_' . time() . '.' . $request->file('eobi_path')->getClientOriginalExtension();
                    $path1 = 'app/' . $request->file('eobi_path')->storeAs('uploads/employee_eobi_copy', $file_name1);
                    $data2['eobi_path'] = $path1;
                    $data2['eobi_type'] = $request->file('eobi_path')->getClientOriginalExtension();
                endif;

                $data2['eobi_number'] = Input::get('eobi_number');

                DB::table('employee')->where('emr_no', Input::get('emr_no'))->update($data2);
            endif;

        endforeach;

        $log['table_name']         = 'employee_equipments';
        $log['activity_id']        = $last_insert_id;
        $log['deleted_emr_no']     = null;
        $log['activity']           = 'Insert';
        $log['module']             = 'hr';
        $log['username']           = Auth::user()->name;
        $log['date']               = date("Y-m-d");
        $log['time']               = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeEquipmentsList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function addDiseaseDetail()
    {
        foreach (Input::get('disease_type') as $key => $val):
            $data1['disease_type'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = Input::get('company_id');
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('diseases')->insert($data1);
        endforeach;

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewDiseasesList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }



    public function addTrainingDetail(Request $request)
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if(Input::get('participant_type') == '1'):
            $participants = implode(Input::get('participants_name'),',');
        else:
            $participants = Input::get('participants_name');
        endif;

        $data1['region_id']          = Input::get('region_id');
        $data1['participant_type']   = Input::get('participant_type');
        $data1['employee_category_id'] = Input::get('emp_category_id');
        $data1['participants'] = $participants;
        $data1['location_id'] = Input::get('location_id');
        $data1['training_date'] = Input::get('training_date');
        $data1['topic_name'] = Input::get('topic_name');
        $data1['username'] = Auth::user()->name;
        $data1['trainer_name'] = Input::get('trainer_name');
        $data1['certificate_number'] = Input::get('certificate_number');
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        $id = DB::table('trainings')->insertGetId($data1);
        $certificate_uploading = $request->file('certificate_uploading');
        $extention = [];
        foreach ($certificate_uploading as $key => $value){
            $file_name = Input::get('certificate_number'). time() . '.' . $value->getClientOriginalExtension();
            $paths = 'app/' . $value->storeAs('uploads/training_certificate', $file_name);
            $path = $_FILES['certificate_uploading']['name'][$key];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $extention['file_type'] = $ext;
            $extention['certificate_uploading'] = $paths;
            $extention['training_id'] = $id;
            $extention['date'] = date("Y-m-d");
            $extention['time'] = date("H:i:s");

            DB::table('training_certificate')->insert($extention);
        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/createTrainingForm?pageType=viewlist&&parentCode=21&&m='.Input::get('m').'');


    }

    public function addEmployeeGratuityDetail()
    {

        $acc_no = (unserialize(base64_decode(Input::get('emr_no'))));

        foreach ($acc_no as $value):


            $data1['emr_no']          = $value;
            $data1['from_date']       = Input::get('from_date_'.$value);
            $data1['to_date']         = Input::get('till_date_'.$value);
            $data1['year_month']      = Input::get('year_month_'.$value);
            $data1['gratuity']        = Input::get('gratuity_'.$value);
            $data1['employee_category_id']=Input::get('emp_category_id_'.$value);
            $data1['region_id']       = Input::get('region_id_'.$value);
            $data1['username']        = Auth::user()->name;
            $data1['status']          = 1;
            $data1['date']            = date("Y-m-d");
            $data1['time']            = date("H:i:s");

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            DB::table('gratuity')->where('emr_no',$value)->delete();
            DB::table('gratuity')->insert($data1);
            CommonHelper::reconnectMasterDatabase();
        endforeach;

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/createEmployeeGratuityForm?pageType=viewlist&&parentCode=21&&m='.Input::get('m').'');

    }

    public function uploadOvertimeAndFuelFile()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month = date('m',strtotime(Input::get('month_year')));
        $year = date('Y',strtotime(Input::get('month_year')));
        echo "<pre>";
        $data = Excel::toArray(true, request()->file('overtimeAndFuelFile'));


        $counter1  = 1;
        foreach($data as $value):
            foreach($value as $value2):
                if($counter1 == 1):

                    if( $value2[4] == '' ||  $value2[4] == 'EMR #' || $value2[4] == 'EMR # ') continue ;

                    DB::table('overtime')->where([['month','=',$month],['year','=',$year],['emr_no','=',$value2[4]]])->delete();

                    $overtime['employee_category_id']      = Input::get('emp_category_id');
                    $overtime['region_id']                 = Input::get('region_id');
                    $overtime['emr_no']                    = $value2[4];
                    $overtime['month']                     = $month;
                    $overtime['year']                      = $year;
                    $overtime['gross_salary']              = round($value2[5]);
                    $overtime['ot_claimed_hours']          = $value2[6];
                    $overtime['ot_verified_hours']         = $value2[7];
                    $overtime['per_hour_ot_rate']          = round($value2[8]);
                    $overtime['ot_for_month']              = round($value2[9]);
                    $overtime['bank_account_no']           = $value2[10];
                    $overtime['username']                  = Auth::user()->name;
                    $overtime['status']                    = 1;
                    $overtime['date']                      = date("Y-m-d");
                    $overtime['time']                      = date("H:i:s");

                    DB::table('overtime')->insert($overtime);

                    $account_no = EmployeeBankData::where([['emr_no','=',$value2[4]],['status','=',1]]);
                    if($account_no->count() > 0):
                        $accno['account_no'] = $value2[10];
                        EmployeeBankData::where([['emr_no','=',$value2[4]]])->update($accno);

                    else:
                        $data2['emr_no']             = $value2[4];
                        $data2['account_title']      = "-";
                        $data2['bank_name']          = "-";
                        $data2['account_no']         = $value2[10];
                        $data2['username']           = Auth::user()->name;
                        $data2['status']             = 1;
                        $data2['date']               = date("Y-m-d");
                        $data2['time']               = date("H:i:s");
                        DB::table('employee_bank_data')->insert($data2);
                    endif;

                elseif($counter1 == 2):

                    if( $value2[5] == '' ||  $value2[5] == 'EMR ' || $value2[5] == 'EMR') continue ;

                    DB::table('fuel')->where([['month','=',$month],['year','=',$year],['emr_no','=',$value2[5]]])->delete();

                    $fuel['employee_category_id']      = Input::get('emp_category_id');
                    $fuel['region_id']                 = Input::get('region_id');
                    $fuel['emr_no']                    = $value2[5];
                    $fuel['month']                     = $month;
                    $fuel['year']                      = $year;
                    $fuel['monthly_salary']            = round($value2[6]);
                    $fuel['km']                        = round($value2[7]);
                    $fuel['rate']                      = round($value2[8]);
                    $fuel['amount']                    = round($value2[9]);
                    $fuel['bank_account_no']           = $value2[10];
                    $fuel['username']                  = Auth::user()->name;
                    $fuel['status']                    = 1;
                    $fuel['date']                      = date("Y-m-d");
                    $fuel['time']                      = date("H:i:s");

                    DB::table('fuel')->insert($fuel);

                    $account_no = EmployeeBankData::where([['emr_no','=',$value2[5]],['status','=',1]]);
                    if($account_no->count() > 0):
                        $accno['account_no'] = $value2[10];
                        EmployeeBankData::where([['emr_no','=',$value2[5]]])->update($accno);

                    else:
                        $data2['emr_no']             = $value2[5];
                        $data2['account_title']      = "-";
                        $data2['bank_name']          = "-";
                        $data2['account_no']         = $value2[10];
                        $data2['username']           = Auth::user()->name;
                        $data2['status']             = 1;
                        $data2['date']               = date("Y-m-d");
                        $data2['time']               = date("H:i:s");
                        DB::table('employee_bank_data')->insert($data2);
                    endif;

                elseif($counter1 == 3):

                    if( $value2[1] == '' ||  $value2[1] == 'Name ' || $value2[1] == 'Name') continue ;

                    DB::table('drivers_allowance')->where([['month','=',$month],['year','=',$year],['emp_name','=',$value2[1]]])->delete();

                    $driver['employee_category_id']      = Input::get('emp_category_id');
                    $driver['region_id']                 = Input::get('region_id');
                    $driver['month']                     = $month;
                    $driver['year']                      = $year;
                    $driver['emp_name']                  = $value2[1];
                    $driver['designation']               = $value2[2];
                    $driver['location']                  = $value2[3];
                    $driver['cost_center']               = $value2[4];
                    $driver['psgl']                      = $value2[5];
                    $driver['hours']                     = round($value2[6]);
                    $driver['salary']                    = round($value2[7]);
                    $driver['rate']                      = round($value2[8]);
                    $driver['ot_labour_law']             = round($value2[10]);
                    $driver['allowance_on_holiday']      = round($value2[11]);
                    $driver['allowance_on_workingday']   = round($value2[12]);
                    $driver['parking_charges']           = round($value2[13]);
                    $driver['out_of_city']               = round($value2[14]);
                    $driver['puncture']                  = round($value2[15]);
                    $driver['mobile_charges']            = $value2[16];
                    $driver['total_allowance']           = round($value2[17]);
                    $driver['bank_account_no']           = $value2[18];
                    $driver['username']                  = Auth::user()->name;
                    $driver['status']                    = 1;
                    $driver['date']                      = date("Y-m-d");
                    $driver['time']                      = date("H:i:s");

                    DB::table('drivers_allowance')->insert($driver);

                endif;
            endforeach;
            $counter1++;
        endforeach;

        $log1['table_name']         = 'overtime';
        $log1['activity_id']        = null;
        $log1['deleted_emr_no']     = null;
        $log1['activity']           = 'Upload';
        $log1['module']             = 'hr';
        $log1['username']           = Auth::user()->name;
        $log1['date']               = date("Y-m-d");
        $log1['time']               = date("H:i:s");
        DB::table('log')->insert($log1);

        $log2['table_name']         = 'fuel';
        $log2['activity_id']        = null;
        $log2['deleted_emr_no']     = null;
        $log2['activity']           = 'Upload';
        $log2['module']             = 'hr';
        $log2['username']           = Auth::user()->name;
        $log2['date']               = date("Y-m-d");
        $log2['time']               = date("H:i:s");
        DB::table('log')->insert($log2);

        $log3['table_name']         = 'drivers_allowance';
        $log3['activity_id']        = null;
        $log3['deleted_emr_no']     = null;
        $log3['activity']           = 'Upload';
        $log3['module']             = 'hr';
        $log3['username']           = Auth::user()->name;
        $log3['date']               = date("Y-m-d");
        $log3['time']               = date("H:i:s");
        DB::table('log')->insert($log3);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/uploadOtAndFuelFile?pageType=viewlist&&parentCode=8&&m=' . Input::get('m') . '#mima');

    }

    public function addEmployeeTransferLeave()
    {
        $duplicate_leave_policy = DB::table('transfered_leaves')->where([['leaves_policy_id', '=', Input::get('leaves_policy_id')],['status', '=', 1]])->first();
        if (Input::get('assign_all_emp') != '' && count($duplicate_leave_policy) == '0') {
            $empCode = unserialize(base64_decode(Input::get('empCode')));

            foreach ($empCode as $value):
                DB::table('transfered_leaves')->where([['leaves_policy_id', '=', Input::get('leaves_policy_id')], ['emr_no', '=', $value]])->delete();
                $data['emr_no'] = $value;
                $data['leaves_policy_id'] = Input::get('leaves_policy_id');
                $data['casual_leaves'] = (Input::get('casualLeaves_' . $value) < 1 ? 0 : Input::get('casualLeaves_' . $value));
                $data['sick_leaves'] = 0;
                $data['annual_leaves'] = (Input::get('annualLeaves_' . $value) < 1 ? true : Input::get('annualLeaves_' . $value));
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");
                DB::table('transfered_leaves')->insert($data);
            endforeach;

            $companiesList = DB::Table('company')->select('id', 'name')->get()->toArray();
            foreach ($companiesList as $companyData):
                CommonHelper::companyDatabaseConnection($companyData->id);
                $employees = Employee::select('emr_no')->where([['status', '=', 1]])->get()->toArray();
                foreach ($employees as $employeesValue):

                    if (in_array($employeesValue['emr_no'], $empCode)):
                        DB::Table('employee')->where([['emr_no', '=', $employeesValue['emr_no']]])->update(array('leaves_policy_id' => Input::get('leaves_policy_id')));
                    endif;

                endforeach;
                CommonHelper::reconnectMasterDatabase();
            endforeach;
            Session::flash('dataInsert', 'successfully saved.');
            return Redirect::to('hr/employeeTransferLeaves?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
        } else {
            return Redirect::to('hr/employeeTransferLeaves?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
        }
    }

    public function addEmployeeTransferProject(Request $request){

        $region_id = Input::get('region_id');
        $emp_category_id = Input::get('emp_category_id');
        $transfer_project_id = Input::get('transfer_project_id');
        $emr_no = Input::get('emr_no');
        $m = Input::get('company_id');
        CommonHelper::companyDatabaseConnection($m);
        $data['emr_CommonHelperno'] = $emr_no;
        $data['employee_project_id'] = $transfer_project_id;
        $data['emp_region_id'] = $region_id;
        $data['emp_categoery_id'] = $emp_category_id;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        $id = DB::table('transfer_employee_project')->insertGetId($data);
        $data1['active'] = 2;
        Employee::where('emr_no','=',$emr_no)->update($data1);
        $previous = DB::table('transfer_employee_project')->where([['emr_no','=',$emr_no],['id', '<', $id]])->max('id');
        if(count($previous) != '0'){
            $data1['active'] = 2;
            DB::table('transfer_employee_project')->where('id','=',$previous)->update($data1);
        }

        $check_letter_uploading = $_FILES['letter_uploading']['name'][0];

        if($check_letter_uploading != '') {
            $letter_uploading = $request->file('letter_uploading');
            $extention = [];
            foreach ($letter_uploading as $key => $value) {
                $file_name = time().'_'.$emr_no.'_'.$key.'_'.$value->getClientOriginalExtension();
                $paths = 'app/' . $value->storeAs('uploads/promotions_letter', $file_name);
                $path = $_FILES['letter_uploading']['name'][$key];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $extention['file_type'] = $ext;
                $extention['letter_uploading'] = $paths;
                $extention['emp_project_id'] = $id;
                $extention['date'] = date("Y-m-d");
                $extention['time'] = date("H:i:s");

                DB::table('project_transfer_letter')->insert($extention);
            }
        }

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewProjectTransferList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }


}
