<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\EmployeeGsspDocuments;
use App\Models\EmployeeTransfer;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\LeavesData;
use App\Models\LeavesPolicy;
use App\Models\EmployeeDeposit;
use App\Models\EmployeeProjects;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeFuelData;
use App\Models\EmployeeLeavingReason;

use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use Hash;
use Helpers;
class HrEditDetailControler extends Controller
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

    public function editDepartmentDetail()
    {
        $departmentSection = Input::get('departmentSection');
        foreach ($departmentSection as $row) {
            $department_name = Input::get('department_name_' . $row . '');
            $department_id = Input::get('department_id_' . $row . '');
            $data1['department_name'] = strip_tags($department_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('department')->where('id', $department_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewDepartmentList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }


    public function editSubDepartmentDetail()
    {
        $subDepartmentSection = Input::get('subDepartmentSection');
        foreach ($subDepartmentSection as $row) {
            $department_id = Input::get('department_id_' . $row . '');
            $sub_department_name = Input::get('sub_department_name_' . $row . '');
            $sub_department_id = Input::get('sub_department_id_' . $row . '');
            $data1['department_id'] = strip_tags($department_id);
            $data1['sub_department_name'] = strip_tags($sub_department_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('sub_department')->where('id', $sub_department_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewSubDepartmentList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editDesignationDetail()
    {
        $designationSection = Input::get('designationSection');
        foreach ($designationSection as $row) {
            $department_id = Input::get('department_id_' . $row . '');
            $designation_name = Input::get('designation_name_' . $row . '');
            $designation_id = Input::get('designation_id_' . $row . '');
            $data1['department_id'] = strip_tags($department_id);
            $data1['designation_name'] = strip_tags($designation_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('designation')->where('id', $designation_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewDesignationList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editHealthInsuranceDetail()
    {
        $healthInsuranceSection = Input::get('healthInsuranceSection');
        foreach ($healthInsuranceSection as $row) {
            $health_insurance_name = Input::get('health_insurance_name_' . $row . '');
            $health_insurance_id = Input::get('health_insurance_id_' . $row . '');
            $data1['health_insurance_name'] = strip_tags($health_insurance_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('health_insurance')->where('id', $health_insurance_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewHealthInsuranceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editLifeInsuranceDetail()
    {
        $lifeInsuranceSection = Input::get('lifeInsuranceSection');
        foreach ($lifeInsuranceSection as $row) {
            $life_insurance_name = Input::get('life_insurance_name_' . $row . '');
            $life_insurance_id = Input::get('life_insurance_id_' . $row . '');
            $data1['life_insurance_name'] = strip_tags($life_insurance_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('life_insurance')->where('id', $life_insurance_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewLifeInsuranceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editJobTypeDetail()
    {
        $jobTypeSection = Input::get('jobTypeSection');
        foreach ($jobTypeSection as $row) {
            $job_type_name = Input::get('job_type_name_' . $row . '');
            $job_type_id = Input::get('job_type_id_' . $row . '');
            $data1['job_type_name'] = strip_tags($job_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('job_type')->where('id', $job_type_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewJobTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editShiftTypeDetail()
    {
        $shiftTypeSection = Input::get('shiftTypeSection');
        foreach ($shiftTypeSection as $row) {
            $shift_type_name = Input::get('shift_type_name_' . $row . '');
            $shift_type_id = Input::get('shift_type_id_' . $row . '');
            $data1['shift_type_name'] = strip_tags($shift_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('shift_type')->where('id', $shift_type_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewShiftTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editAdvanceTypeDetail()
    {
        $advanceTypeSection = Input::get('advanceTypeSection');
        foreach ($advanceTypeSection as $row) {
            $advance_type_name = Input::get('advance_type_name_' . $row . '');
            $advance_type_id = Input::get('advance_type_id_' . $row . '');
            $data1['advance_type_name'] = strip_tags($advance_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('advance_type')->where('id', $advance_type_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewAdvanceTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editLoanTypeDetail()
    {
        $loanTypeSection = Input::get('loanTypeSection');
        foreach ($loanTypeSection as $row) {
            $loan_type_name = Input::get('loan_type_name_' . $row . '');
            $loan_type_id = Input::get('loan_type_id_' . $row . '');
            $data1['loan_type_name'] = strip_tags($loan_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('loan_type')->where('id', $loan_type_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewLoanTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editLeaveTypeDetail()
    {
        $leaveTypeSection = Input::get('leaveTypeSection');
        foreach ($leaveTypeSection as $row) {
            $leave_type_name = Input::get('leave_type_name_' . $row . '');
            $leave_type_id = Input::get('leave_type_id_' . $row . '');
            $data1['leave_type_name'] = strip_tags($leave_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('leave_type')->where('id', $leave_type_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewLeaveTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }


    public function editHiringRequestDetail()
    {

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


        $data1['RequestHiringTitle'] = strip_tags($jobTitle);
        $data1['sub_department_id'] = strip_tags($subDepartmentId);
        $data1['job_type_id'] = strip_tags($jobTypeId);
        $data1['designation_id'] = strip_tags($designationId);
        $data1['qualification_id'] = strip_tags($qualificationId);
        $data1['shift_type_id'] = strip_tags($shiftTypeId);
        $data1['RequestHiringGender'] = strip_tags($gender);
        $data1['RequestHiringSalaryStart'] = strip_tags($salaryStart);
        $data1['RequestHiringSalaryEnd'] = strip_tags($salaryEnd);
        $data1['RequestHiringAge'] = strip_tags($age);
        $data1['RequestHiringDescription'] = $jobDescription;
        $data1['location'] = strip_tags($location);
        $data1['experience'] = strip_tags($experience);
        $data1['career_level'] = strip_tags($career_level);
        $data1['apply_before_date'] = strip_tags($apply_before_date);
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('requesthiring')->where('id', Input::get('RequestHiringId'))->update($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewHiringRequestList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('company_id') . '#SFR');
    }


    public function editEmployeeDetail(Request $request)
    {
        $path = '';
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $id = Input::get('id');

        $employeeSection = Input::get('employeeSection');
        foreach ($employeeSection as $row) {


            if ($request->file('fileToUpload_' . $row . '')):
                $file_name = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('fileToUpload_' . $row . '')->getClientOriginalExtension();
                $path = $request->file('fileToUpload_' . $row . '')->storeAs('uploads/employee_images', $file_name);
                $data1['img_path'] = 'app/' . $path;
            endif;

            //          cnic upload start
            if ($request->file('cnic_path_' . $row . '')):
                $file_name1 = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('cnic_path_' . $row . '')->getClientOriginalExtension();
                $path1 = 'app/' . $request->file('cnic_path_' . $row . '')->storeAs('uploads/employee_cnic_copy', $file_name1);
                $data1['cnic_path'] = $path1;
                $data1['cnic_name'] = $file_name1;
                $data1['cnic_type'] = $request->file('cnic_path_' . $row . '')->getClientOriginalExtension();
            endif;

//            eobi upload
            if ($request->file('eobi_path_' . $row . '')):
                $file_name1 = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('eobi_path_' . $row . '')->getClientOriginalExtension();
                $path1 = 'app/' . $request->file('eobi_path_' . $row . '')->storeAs('uploads/employee_eobi_copy', $file_name1);
                $data1['eobi_path'] = $path1;
                $data1['eobi_type'] = $request->file('eobi_path_' . $row . '')->getClientOriginalExtension();
            endif;

//            insurance upload



            /*Basic info start*/
            $employee_name = Input::get('employee_name_' . $row . '');
            $emp_code = Input::get('emp_code_' . $row . '');
            $father_name = Input::get('father_name_' . $row . '');

            $department_id = Input::get('emp_department_id_' . $row . '');
            $date_of_birth = Input::get('date_of_birth_' . $row . '');
            $joining_date = Input::get('joining_date_' . $row . '');
            $gender = Input::get('gender_' . $row . '');
            $cnic = Input::get('cnic_' . $row . '');
            $cnic_expiry_date = Input::get('cnic_expiry_date_' . $row . '');
            $eobi_number = Input::get('eobi_number_' . $row . '');

            $life_time_cnic = Input::get('life_time_cnic_' . $row . '');

            $contact_no = Input::get('contact_no_' . $row . '');
            $contact_home = Input::get('contact_home_' . $row . '');
            $contact_office = Input::get('contact_office_' . $row . '');
            $emergency_no = Input::get('emergency_no_' . $row . '');
            $employee_status = Input::get('employee_status_' . $row . '');
            $salary = Input::get('salary_' . $row . '');
            $emp_joining_salary = Input::get('emp_joining_salary_' . $row . '');

            $normal_sal_pay_days_sal = Input::get('normal_sal_pay_days_sal_' . $row . '');
            $email = Input::get('email_' . $row . '');
            $marital_status = Input::get('marital_status_' . $row . '');
            $leaves_policy = Input::get('leaves_policy_' . $row . '');
            $designation = Input::get('designation_' . $row . '');

            $eobi_id = Input::get('eobi_id_' . $row . '');

            $branch_id = Input::get('branch_id_' . $row . '');
            $region_id = Input::get('region_id_' . $row . '');

            $place_of_birth = Input::get('place_of_birth_' . $row . '');
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
            $data1['leaves_policy_id'] = strip_tags($leaves_policy);
            $data1['emp_no'] = strip_tags($employee_no);
            $data1['emp_name'] = strip_tags($employee_name);
            $data1['emp_father_name'] = strip_tags($father_name);
            $data1['emp_department_id'] = strip_tags($department_id);
            $data1['emp_date_of_birth'] = strip_tags($date_of_birth);
            $data1['emp_place_of_birth'] = strip_tags($place_of_birth);
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
            $data1['emp_joining_salary'] = strip_tags($emp_joining_salary);

            $data1['eobi_number'] = strip_tags($eobi_number);
            $data1['life_time_cnic'] = strip_tags($life_time_cnic);;


            $data1['normal_or_per_day_salary'] = strip_tags($normal_sal_pay_days_sal);
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
            $data1['can_login'] = (Input::get('can_login_' . $row . '') ? 'yes' : 'no');
           // $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('employee')->where('id', $id)->update($data1);

            if (Input::get('can_login_' . $row . '')):


                if (Input::get('login_check') == Input::get('can_login_' . $row . '')):

                    CommonHelper::reconnectMasterDatabase();
                    $oldPassword = Input::get('old_password');
                    $email = Input::get('email_' . $row . '');

                    if (Input::get('password_' . $row . '') == '') {
                        $employee_password = $oldPassword;

                    } else {
                        $employee_password = Input::get('password_' . $row . '');
                        $employee_password = Hash::make($employee_password);
                    }


                    $employee_name = Input::get('employee_name_' . $row . '');

                    $employee_account_type = Input::get('account_type_' . $row . '');


                    $dataCredentials['name'] = $employee_name;
                    $dataCredentials['username'] = $email;
                    $dataCredentials['email'] = $email;
                    $dataCredentials['password'] = $employee_password;
                    $dataCredentials['identity'] = Input::get('password_' . $row . '');
                    $dataCredentials['acc_type'] = $employee_account_type;
                    $dataCredentials['emp_code'] = (Input::get('emp_code_hidden') != $emp_code ? $emp_code : Input::get('emp_code_hidden'));
                    $dataCredentials['company_id'] = Input::get('company_id');
                    $dataCredentials['updated_at'] = date("Y-m-d");
                    $dataCredentials['created_at'] = date("Y-m-d");
                    $dataCredentials['company_id'] = Input::get('company_id');

                    DB::table('users')->where([['emp_code', '=', Input::get('emp_code_hidden')]])->update($dataCredentials);
                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));

                else:




                    CommonHelper::reconnectMasterDatabase();
                    $Getusers = DB::table('users')->where('emp_code',$emp_code)->get();

                    if(count($Getusers) == 0):

                        $email = Input::get('email_' . $row . '');
                        $employee_password = Input::get('password_' . $row . '');
                        $employee_name = Input::get('employee_name_' . $row . '');

                        $employee_account_type = Input::get('account_type_' . $row . '');


                        $dataCredentials['name'] = $employee_name;
                        $dataCredentials['username'] = $email;
                        $dataCredentials['email'] = $email;
                        $dataCredentials['password'] = Hash::make($employee_password);
                        $dataCredentials['identity'] = $employee_password;
                        $dataCredentials['acc_type'] = $employee_account_type;
                        $dataCredentials['emp_code'] = $emp_code;
                        $dataCredentials['company_id'] = Input::get('company_id');
                        $dataCredentials['updated_at'] = date("Y-m-d");
                        $dataCredentials['created_at'] = date("Y-m-d");
                        $dataCredentials['company_id'] = Input::get('company_id');

                        DB::table('users')->insert($dataCredentials);
                    endif;

                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));


                endif;


            else:
                CommonHelper::reconnectMasterDatabase();

                //DB::table('users')->where([['emp_code', '=', $emp_code]])->delete();

                CommonHelper::companyDatabaseConnection(Input::get('company_id'));

            endif;

        }

        /*family data start*/
        DB::table('employee_family_data')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_bank_data')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_educational_data')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_language_proficiency')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_health_data')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_activity_data')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_work_experience')->where('emp_code', '=', $emp_code)->delete();
        if (!empty(Input::get('work_experience_data'))):
            foreach (Input::get('work_experience_data') as $workExperienceRow):
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
        DB::table('employee_reference_data')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_kins_data')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_relatives')->where('emp_code', '=', $emp_code)->delete();
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
        DB::table('employee_other_details')->where('emp_code', '=', $emp_code)->delete();
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

//        file upload start
        $employee_documents_count = EmployeeDocuments::where([['status', '=', 1], ['emp_code', '=', $emp_code]])->max('counter');

        $counter = $employee_documents_count;
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
//        file upload end

        $employee_gssp_documents_count = EmployeeGsspDocuments::where([['status', '=', 1], ['emp_code', '=', $emp_code]])->max('counter');

        $counter = $employee_gssp_documents_count;
        if(Input::get('employeeGsspData')):
            foreach (Input::get('employeeGsspData') as $rows):
                if ($request->hasFile('document_file_'.$rows)):
                    $counter++;
                    $extension = $request->file('document_file_' . $rows . '')->getClientOriginalExtension();
                    $file_name = Input::get('emp_code') . '_' . $counter . time() . '.' . $request->file('document_file_' . $rows . '')->getClientOriginalExtension();
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
                endif;
            endforeach;
        endif;
        $log['table_name']         = 'employee';
        $log['activity_id']        = $id;
        $log['deleted_emr_no']     = null;
        $log['activity']           = 'Update';
        $log['module']             = 'hr';
        $log['username']           = Auth::user()->name;
        $log['date']               = date("Y-m-d");
        $log['time']               = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeList?pageType=viewlist&&parentCode=20&&m=' . Input::get('company_id') . '');
    }

    public function editEmployeeLeavingDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $emp_code = Employee::select('emp_code')->where([['id','=',Input::get('recordId')]])->value('emp_code');
        EmployeeLeavingReason::where([['emp_code','=',$emp_code]])->delete();
        $data['emp_code']             = $emp_code;
        $data['leaving_reason']     = Input::get('leaving_reason');
        $data['last_working_date']  = Input::get('last_working_date');
        $data['username']           = Auth::user()->name;
        $data['date']               = date("Y-m-d");
        $data['time']               = date("H:i:s");
        DB::table('employee')->where([['id','=',Input::get('recordId')]])->update(array('status'=>'4', 'can_login'=>'no'));
        DB::table('employee_leaving_reason')->insert($data);
        CommonHelper::reconnectMasterDatabase();
        DB::table('users')->where('emp_code', $emp_code)->delete();
        Session::flash('dataEdit', 'Successfully Updated.');
        return Redirect::to('hr/viewEmployeeList?m=' . Input::get('company_id') . '');
    }

    public function editAllowanceTypeDetail()
    {
        $data['allowance_type'] = Input::get('allowance_type');
        $data['status'] = 1;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        DB::table('allowance_type')->where('id', Input::get('id'))->update($data);
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewAllowanceTypeList?m=' . Input::get('company_id') . '');
    }



    //new code end












    public function editMaritalStatusDetail()
    {
        $data1['marital_status_name'] = strip_tags(Input::get('marital_status_name'));
        $data1['username'] = Auth::user()->name;
        $data1['company_id'] = $_GET['m'];
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('marital_status')->where('id', Input::get('id'))->update($data1);
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewMaritalStatuslist?pageType=viewlist&&parentCode=16&&m=' . $_GET['m'] . '');

    }

    public function editAllowanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $data1['allowance_type'] = Input::get('allowance_type');
        $data1['allowance_amount'] = Input::get('allowance_amount');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        DB::table('allowance')->where([['id', '=', Input::get('allowanceId')]])->update($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAllowanceList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    public function editDeductionDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $data1['deduction_type'] = Input::get('deduction_type');
        $data1['deduction_amount'] = Input::get('deduction_amount');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('deduction')->where([['id', '=', Input::get('deductionId')]])->update($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDeductionList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    public function editAdvanceSalaryDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $implode_date = explode("-", Input::get('deduction_month_year'));

        $data1['advance_salary_amount'] = Input::get('advance_salary_amount');
        $data1['salary_needed_on'] = Input::get('salary_needed_date');
        $data1['account_head_id'] = Input::get('account_head_id');
        $data1['account_id'] = Input::get('account_id');
        $data1['deduction_year'] = $implode_date[0];
        $data1['deduction_month'] = $implode_date[1];
        $data1['detail'] = Input::get('advance_salary_detail');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('advance_salary')->where([['id', '=', Input::get('id')]])->update($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAdvanceSalaryList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function editLeavesPolicyDetail()
    {

        $data1['leaves_policy_name'] = strip_tags(Input::get('leaves_policy_name'));
        $data1['policy_date_from'] = strip_tags(Input::get('PolicyDateFrom'));
        $data1['policy_date_till'] = strip_tags(Input::get('PolicyDateTill'));
        $data1['terms_conditions'] = Input::get('terms_conditions');
        $data1['fullday_deduction_rate'] = Input::get('full_day_deduction_rate');
        $data1['halfday_deduction_rate'] = Input::get('half_day_deduction_rate');
        $data1['per_hour_deduction_rate'] = Input::get('per_hour_deduction_rate');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['time'] = date("H:i:s");
        $data1['date'] = date("Y-m-d");

        LeavesPolicy::where([['id', '=', Input::get('record_id')]])->update($data1);
        LeavesData::where([['leaves_policy_id', '=', Input::get('record_id')]])->delete();
        if (Input::get('leaves_type_id')) {
            foreach (Input::get('leaves_type_id') as $key => $val):

                $data2['leaves_policy_id'] = Input::get('record_id');
                $data2['leave_type_id'] = $val;
                $data2['no_of_leaves'] = Input::get('no_of_leaves')[$key];
                $data2['username'] = Auth::user()->name;;
                $data2['status'] = 1;
                $data2['time'] = date("H:i:s");
                $data2['date'] = date("Y-m-d");
                DB::table('leaves_data')->insert($data2);

            endforeach;
        }

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLeavesPolicyList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    public function editVehicleTypeDetail()
    {
        $data1['vehicle_type_name'] = strip_tags(Input::get('vehicle_type_name'));
        $data1['vehicle_type_cc'] = strip_tags(Input::get('vehicle_type_cc'));
        $data1['username'] = Auth::user()->name;
        $data1['company_id'] = Input::get('m');
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");


        DB::table('vehicle_type')->where([['id', '=', Input::get('record_id')], ['company_id', '=', Input::get('company_id')]])->update($data1);
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewVehicleTypeList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    public function editCarPolicyDetail()
    {

        $data1['designation_id'] = Input::get('designation_id');
        $data1['vehicle_type_id'] = Input::get('vehicle_type_id');
        $data1['policy_name'] = Input::get('policy_name');
        $data1['start_salary_range'] = Input::get('start_salary_range');
        $data1['end_salary_range'] = Input::get('end_salary_range');
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        DB::table('car_policy')->where([['id', '=', Input::get('record_id')]])->update($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewCarPolicyList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    public function editQualificationDetail()
    {
        $qualificationSection = Input::get('qualificationSection');
        foreach ($qualificationSection as $row) {
            $qualification_name = Input::get('qualification_name_' . $row . '');
            $country = Input::get('country_' . $row . '');
            $state = Input::get('state_' . $row . '');
            $city = Input::get('city_' . $row . '');
            $institute = Input::get('institute_id_' . $row . '');
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

            DB::table('qualification')->where([['id', '=', Input::get('qualification_id_1')]])->update($data2);


        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewQualificationList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editLoanRequestDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $month_data = (explode("-", Input::get('needed_on_date')));
        $data1['year'] = $month_data[0];
        $data1['month'] = $month_data[1];

        $data1['loan_type_id'] = Input::get('loan_type_id');
        $data1['loan_amount'] = Input::get('loan_amount');
        $data1['per_month_deduction'] = Input::get('per_month_deduction');
        $data1['account_head_id'] = Input::get('account_head_id');
        $data1['account_id'] = Input::get('account_id');
        $data1['description'] = Input::get('loan_description');
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('loan_request')->where([['id', '=', Input::get('loanRequestId')]])->update($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLoanRequestList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    public function editEOBIDetail()
    {
        $data1['EOBI_name'] = Input::get('EOBI_name');
        $data1['EOBI_amount'] = Input::get('EOBI_amount');
        $data1['month_year'] = Input::get('month_year');
        $data1['username'] = Auth::user()->name;;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('eobi')->where([['company_id', '=', Input::get('company_id')], ['id', '=', Input::get('recordId')]])->update($data1);
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEOBIList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');


    }

    public function editTaxesDetail()
    {
        $data1['tax_name'] = Input::get('tax_name');
        $data1['salary_range_from'] = Input::get('salary_range_from');
        $data1['salary_range_to'] = Input::get('salary_range_to');
        $data1['tax_mode'] = Input::get('tax_mode');
        $data1['tax_percent'] = Input::get('tax_percent');
        $data1['tax_month_year'] = Input::get('tax_month_year');
        $data1['company_id'] = Input::get('company_id');
        $data1['username'] = Auth::user()->name;;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('tax')->where([['company_id', '=', Input::get('company_id')], ['id', '=', Input::get('recordId')]])->update($data1);
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewTaxesList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');


    }

    public function editBonusDetail()
    {

        $data1['bonus_name'] = Input::get('Bonus_name');
        $data1['percent_of_salary'] = Input::get('percent_of_salary');
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        DB::table('bonus')->where([['id', '=', Input::get('recordId')]])->update($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewBonusList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function editHolidayDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $month_year = explode('-', Input::get('holiday_date'));

        $data1['holiday_name'] = Input::get('holiday_name');
        $data1['holiday_date'] = Input::get('holiday_date');
        $data1['year'] = $month_year[0];
        $data1['month'] = $month_year[1];
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("d-m-Y");
        $data1['time'] = date("H:i:s");

        DB::table('holidays')->where([['id', '=', Input::get('record_id')]])->update($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewHolidaysList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function editEmployeeDepositDetail(Request $request)
    {
        $depositId = Input::get('depositId');
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));


        $month_and_year = explode('-', Input::get('to_be_deduct_on_date'));
        $employeeDepositUpdate['sub_department_id'] = Input::get('sub_department_id');
        $employeeDepositUpdate['acc_no'] = Input::get('employee_id');
        $employeeDepositUpdate['deposit_name'] = Input::get('deposit_name');
        $employeeDepositUpdate['deposit_amount'] = Input::get('deposit_amount');
        $employeeDepositUpdate['deduction_month'] = $month_and_year[1];
        $employeeDepositUpdate['deduction_year'] = $month_and_year[0];
        $employeeDepositUpdate['username'] = Auth::user()->name;
        $employeeDepositUpdate['status'] = 1;
        $employeeDepositUpdate['date'] = date("Y-m-d");
        $employeeDepositUpdate['time'] = date("H:i:s");
        DB::table('employee_deposit')->where([['id', '=', $depositId]])->update($employeeDepositUpdate);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataUpdate', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeDepositList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function editEmployeeCategoryDetail()
    {
        $employeeCategorySection = Input::get('employeeCategorySection');
        foreach ($employeeCategorySection as $row) {
            $employee_category_name = Input::get('employee_category_name_' . $row . '');
            $emp_category_id = Input::get('emp_category_id_' . $row . '');
            $data1['employee_category_name'] = strip_tags($employee_category_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('employee_category')->where('id', $emp_category_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeCategoryList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editEmployeeGradesDetail()
    {
        $emp_grade_id = Input::get('recordId');
        $data1['category'] = Input::get('category');
        $data1['employee_grade_type'] = Input::get('employee_grade_type');
        $data1['username'] = Auth::user()->name;
        $data1['company_id'] = $_GET['m'];
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('grades')->where('id', $emp_grade_id)->update($data1);

        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeGradesList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editEmployeeLocationsDetail()
    {
        $id = Input::get('id');
        $data['region_id'] = Input::get('region_id');
        $data['employee_location'] = Input::get('employee_location');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('m');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        DB::table('locations')->where([['employee_location','LIKE', Input::get('employee_location')]])->update($data);
        DB::table('locations')->where('id', $id)->update($data);

        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeLocationsList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editEmployeeRegionsDetail()
    {

        $emp_region_id = Input::get('recordId');
        $data1['employee_region'] = Input::get('employee_region');
        $data1['username'] = Auth::user()->name;
        $data1['company_id'] = $_GET['m'];
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('regions')->where('id', $emp_region_id)->update($data1);

        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeRegionsList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editEmployeeDegreeTypeDetail()
    {

        $emp_degree_type_id = Input::get('recordId');
        $data1['degree_type_name'] = Input::get('degree_type_name');
        $data1['username'] = Auth::user()->name;
        $data1['company_id'] = $_GET['m'];
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('degree_type')->where('id', $emp_degree_type_id)->update($data1);

        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeDegreeTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function editEmployeeExitClearanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $id = Input::get('id');

        $data1['leaving_type'] = Input::get('leaving_type');
        $data1['supervisor_name'] = Input::get('supervisor_name');
        $data1['signed_by_supervisor'] = Input::get('signed_by_supervisor');
        $data1['last_working_date'] = Input::get('last_working_date');

        $data1['room_key'] = Input::get('room_key');
        $data1['room_key_remarks'] = Input::get('room_key_remarks');
        $data1['mobile_sim'] = Input::get('mobile_sim');
        $data1['mobile_sim_remarks'] = Input::get('mobile_sim_remarks');
        $data1['fuel_card'] = Input::get('fuel_card');
        $data1['fuel_card_remarks'] = Input::get('fuel_card_remarks');
        $data1['mfm_employee_card'] = Input::get('mfm_employee_card');
        $data1['mfm_employee_card_remarks'] = Input::get('mfm_employee_card_remarks');
        $data1['client_access_card'] = Input::get('client_access_card');
        $data1['client_access_card_remarks'] = Input::get('client_access_card_remarks');
        $data1['medical_insurance_card'] = Input::get('medical_insurance_card');
        $data1['medical_insurance_card_remarks'] = Input::get('medical_insurance_card_remarks');
        $data1['eobi_card'] = Input::get('eobi_card');
        $data1['eobi_card_remarks'] = Input::get('eobi_card_remarks');
        $data1['biometric_scan'] = Input::get('biometric_scan');
        $data1['biometric_scan_remarks'] = Input::get('biometric_scan_remarks');
        $data1['payroll_deduction'] = Input::get('payroll_deduction');
        $data1['payroll_deduction_remarks'] = Input::get('payroll_deduction_remarks');
        $data1['info_sent_to_client'] = Input::get('info_sent_to_client');
        $data1['info_sent_to_client_remarks'] = Input::get('info_sent_to_client_remarks');
        $data1['client_exit_checklist'] = Input::get('client_exit_checklist');
        $data1['client_exit_checklist_remarks'] = Input::get('client_exit_checklist_remarks');
        $data1['exit_interview'] = Input::get('exit_interview');
        $data1['exit_interview_remarks'] = Input::get('exit_interview_remarks');
        $data1['laptop'] = Input::get('laptop');
        $data1['laptop_remarks'] = Input::get('laptop_remarks');
        $data1['desktop_computer'] = Input::get('desktop_computer');
        $data1['desktop_computer_remarks'] = Input::get('desktop_computer_remarks');
        $data1['email_account_deactivated'] = Input::get('email_account_deactivated');
        $data1['email_account_deactivated_remarks'] = Input::get('email_account_deactivated_remarks');
        $data1['toolkit_ppe'] = Input::get('toolkit_ppe');
        $data1['toolkit_ppe_remarks'] = Input::get('toolkit_ppe_remarks');
        $data1['uniform'] = Input::get('uniform');
        $data1['uniform_remarks'] = Input::get('uniform_remarks');
        $data1['advance_loan'] = Input::get('advance_loan');
        $data1['advance_loan_remarks'] = Input::get('advance_loan_remarks');
        $data1['extra_leaves'] = Input::get('extra_leaves');
        $data1['extra_leaves_remarks'] = Input::get('extra_leaves_remarks');
        $data1['final_settlement'] = Input::get('final_settlement');
        $data1['final_settlement_remarks'] = Input::get('final_settlement_remarks');

        $data1['username'] = Auth::user()->name;
        $data1['approval_status'] = 1;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_exit')->where('id', $id)->update($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeExitClearanceList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function editFinalSettlementDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $date1 = strtotime(Input::get('salary_to'));
        $date2 = strtotime(Input::get('salary_from'));
        $emp_salary = Input::get('emp_salary');

        $diff = $date1 - $date2;
        $days = round($diff / 86400) + 1;

        $salary = round($emp_salary/30*$days);

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

        DB::table('final_settlement')->where('id', Input::get('id'))->update($data);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeExitClearanceList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');
    }

    public function editEmployeeIdCardRequestDetail(Request $request)
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        if($request->hasFile('fir_copy')):
            $extension = $request->file('fir_copy')->getClientOriginalExtension();
            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('fir_copy')->getClientOriginalExtension();
            $path = $request->file('fir_copy')->storeAs('uploads/employee_id_card_fir_copy', $file_name);
            $data1['fir_copy_path'] =    'app/'.$path;
            $data1['fir_copy_extension'] =  $extension;
         endif;

        if($request->hasFile('card_image')):

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

        $id = Input::get('id');

        $data1['username']         = Auth::user()->name;
        $data1['posted_at']        = Input::get('posted_at');
        $data1['card_replacement'] = Input::get('card_replacement');
        $data1['replacement_type'] = Input::get('replacement_type');
        $data1['payment']          = Input::get('payment');
        $data1['approval_status']  = 1;
        $data1['status'] 		   = 1;
        $data1['date']     		   = date("Y-m-d");
        $data1['time']     		   = date("H:i:s");

        DB::table('employee_card_request')->where('id', $id)->update($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('hr/viewEmployeeIdCardRequestList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }


    public function editEmployeeProjectsDetail(){

            $data1['project_name']    = Input::get('project_name');
            $data1['username']        = Auth::user()->name;
            $data1['date']            = date("Y-m-d");
            $data1['time']            = date("H:i:s");

            DB::table('employee_projects')->where('id', Input::get('recordId'))->update($data1);

        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('hr/viewEmployeeProjectsList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    public function editEmployeePromotionDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $id = Input::get('id');
        $emp_code = Input::get('emp_code');
        $edit_salary = Input::get('edit_salary');
        if($edit_salary == 1):
            $data1['increment'] = 		    Input::get('increment');
            $data1['salary'] = 		        Input::get('salary');
        endif;

        $data1['designation_id'] = 		Input::get('designation_id');
        $data1['status']=               1;
        $data1['approval_status']=      1;
        $data1['username']        = Auth::user()->name;
        $data1['date']=                 date("Y-m-d");
        $data1['time']=                 date("H:i:s");

        DB::table('employee_promotion')->where('id', $id)->update($data1);

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
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeePromotions?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function editEmployeeTransferDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $location_check = Input::get('location_check');
        $edit_salary = Input::get('edit_salary');
        $transfer_project_check = Input::get('transfer_project_check');

        if ($location_check != '')
            $location_check = 1;
        else
            $location_check = 0;

        $location_id = Input::get('id');
        $employeeLocationDetail = EmployeeTransfer::select('emr_no', 'promotion_id','transfer_project_id')->where([['id', '=', $location_id]])->first()->toArray();

        $promotion_id = $employeeLocationDetail['promotion_id'];
        $transfer_project_id = $employeeLocationDetail['transfer_project_id'];

        if ($location_check == 1) {
            if ($promotion_id != 0) {
                if ($edit_salary == 1):
                    $data['increment'] = Input::get('increment');
                    $data['salary'] = Input::get('salary');
                endif;
                $data['designation_id'] = Input::get('designation_id');
                $data['grade_id'] = Input::get('grade_id');
                $data['status'] = 1;
                $data['approval_status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('employee_promotion')->where('id', $promotion_id)->update($data);

                $data1['location_id'] = Input::get('location_id');
                $data1['status'] = 1;
                $data1['approval_status'] = 1;
                $data1['username'] = Auth::user()->name;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('employee_location')->where('id', $location_id)->update($data1);
            }

            if ($promotion_id == 0) {
                $data['emr_no'] = Input::get('emr_no');
                $data['designation_id'] = Input::get('designation_id');
                $data['grade_id'] = Input::get('grade_id');
                $data['increment'] = Input::get('increment');
                $data['salary'] = Input::get('salary');
                $data['promotion_date'] = Input::get('promotion_date');
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['approval_status'] = 1;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('employee_promotion')->insert($data);
                $promotion_id = DB::getPdo()->lastInsertId();

                $data1['location_id'] = Input::get('location_id');
                $data1['promotion_id'] = $promotion_id;
                $data1['status'] = 1;
                $data1['username'] = Auth::user()->name;
                $data1['approval_status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('employee_location')->where('id', $location_id)->update($data1);
            }
        }
        elseif ($location_check == 0) {
            if ($promotion_id != 0) {
                DB::table('employee_promotion')->where('id', $promotion_id)->delete();

                $data1['location_id'] = Input::get('location_id');
                $data1['promotion_id'] = 0;
                $data1['status'] = 1;
                $data1['username'] = Auth::user()->name;
                $data1['approval_status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('employee_location')->where('id', $location_id)->update($data1);
            }

            if ($promotion_id == 0) {
                $data1['location_id'] = Input::get('location_id');
                $data1['status'] = 1;
                $data1['approval_status'] = 1;
                $data1['username'] = Auth::user()->name;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('employee_location')->where('id', $location_id)->update($data1);
            }
        }

        if($transfer_project_check == 1){
            if($transfer_project_id == '0'){
                $employee_data = Employee::where('emr_no','=',Input::get('emr_no'));
                $region_id = $employee_data->value('region_id');
                $employee_category_id = $employee_data->value('employee_category_id');
                $emr_no = Input::get('emr_no');
                $data2['emr_no'] = $emr_no;
                $data2['employee_project_id'] = Input::get('transfer_project_id');
                $data2['username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                $data2['emp_region_id'] = $region_id;
                $data2['emp_categoery_id'] = $employee_category_id;
                $transfer_id = DB::table('transfer_employee_project')->insertGetId($data2);
                $data5['active'] = 2;
                Employee::where('emr_no','=',$emr_no)->update($data5);
                $previous = DB::table('transfer_employee_project')->where([['emr_no','=',$emr_no],['id', '<', $transfer_id]])->max('id');
                if(count($previous) != '0'){
                    $data4['active'] = 2;
                    DB::table('transfer_employee_project')->where('id','=',$previous)->update($data4);
                }
                $data10['transfer_project_id'] = $transfer_id;
                DB::table('employee_location')->where('id', $location_id)->update($data10);
            }
            else{
                $transfer_project = Input::get('transfer_project_id');
                $emr_no = Input::get('emr_no');
                $m = Input::get('company_id');
                CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                $data2['emr_no'] = Input::get('emr_no');
                $data2['employee_project_id'] = $transfer_project;
                $data2['username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                DB::table('transfer_employee_project')->where([['id','=',$transfer_project_id],['active','=',1]])->update($data2);
            }
        }
        else{
            if($transfer_project_id != '0') {
                $transfer_project = Input::get('transfer_project_id');
                $emr_no = Input::get('emr_no');
                $m = Input::get('company_id');
                CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                $data2['emr_no'] = Input::get('emr_no');
                $data2['employee_project_id'] = $transfer_project;
                $data2['username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                $data2['active'] = 2;
                DB::table('transfer_employee_project')->where([['id','=',$transfer_project_id],['active','=',1]])->delete();

                $data10['transfer_project_id'] = 0;
                DB::table('employee_location')->where('id', $location_id)->update($data10);

                $data12['active'] = 1;
                Employee::where('emr_no','=',Input::get('emr_no'))->update($data12);

            }
        }


        $check_letter_uploading = $_FILES['letter_uploading']['name'][0];
        if ($check_letter_uploading != '') {
            $letter_uploadings = $request->file('letter_uploading');
            $extention = [];
            foreach ($letter_uploadings as $key => $value) {
                $file_name = time().'_'.Input::get('emr_no').'_'.$key.'_'.$value->getClientOriginalExtension();
                $paths = 'app/' . $value->storeAs('uploads/transfer_letter', $file_name);
                $path = $_FILES['letter_uploading']['name'][$key];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $extention['file_type'] = $ext;
                $extention['emp_location_id'] = $location_id;
                $extention['letter_uploading'] = $paths;
                $extention['date'] = date("Y-m-d");
                $extention['time'] = date("H:i:s");
                DB::table('transfer_letter')->where('emp_location_id', '=', $location_id)->insert($extention);

            }
        }

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeTransferList?pageType=viewlist&&parentCode=21&&m=' . Input::get('company_id') . '');

    }

    public function editEmployeeFuelDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $data['fuel_date'] = Input::get('fuel_date');
        $data['from'] = Input::get('from');
        $data['to'] = Input::get('to');
        $data['km'] = Input::get('km');
        $data['status'] = 1;
        $data['approval_status'] = 1;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        DB::table('employee_fuel_data')->where('id', Input::get('id'))->update($data);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeFuel?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function updateLabourSalary()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $data['emp_salary'] = Input::get('emp_salary');

        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        DB::table('employee')->where([['labour_law', '=', 1], ['status', '=', 1]])->update($data);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function editEquipmentsDetail()
    {
        $emp_equipment_id = Input::get('recordId');
        $data1['equipment_name'] = Input::get('equipment_name');
        $data1['username'] = Auth::user()->name;
        $data1['company_id'] = $_GET['m'];
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_equipments')->where('id', $emp_equipment_id)->update($data1);

        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('hr/viewEquipmentsList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }


    public function editEmployeeAttendanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if(Input::get('attendance_type') == 1):


            $data1['attendance_from']   = Input::get('date_from');
            $data1['attendance_to']     = Input::get('date_to');
            $data1['present_days']      = Input::get('present_days');
            $data1['absent_days']       = Input::get('absent_days');
            $data1['overtime']          = Input::get('overtime');
            $data1['username']          = Auth::user()->name;
            $data1['date']              = date("Y-m-d");
            $data1['time']              = date("H:i:s");

            DB::table('attendance')->where('id', Input::get('recordId'))->update($data1);

        elseif(Input::get('attendance_type') == 2):


            $data1['attendance_date'] = Input::get('attendance_date');
            $data1['clock_in'] = Input::get('clock_in');
            $data1['clock_out'] = Input::get('clock_out');
            $data1['attendance_status'] = Input::get('attendance_status');
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('attendance')->where('id', Input::get('recordId'))->update($data1);

        endif;

        $data3['table_name']         = 'attendance';
        $data3['activity_id']        = Input::get('recordId');
        $data3['deleted_emr_no']     = null;
        $data3['activity']           = 'Update';
        $data3['module']             = 'hr';
        $data3['username']           = Auth::user()->name;
        $data3['date']               = date("Y-m-d");
        $data3['time']               = date("H:i:s");
        DB::table('log')->insert($data3);
        CommonHelper::reconnectMasterDatabase();


        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('hr/viewEmployeeAttendanceList?&&m='.Input::get('m').'#mima');


    }

    public function editEmployeeEquipmentDetail(Request $request)
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
        $log['activity']           = 'Update';
        $log['module']             = 'hr';
        $log['username']           = Auth::user()->name;
        $log['date']               = date("Y-m-d");
        $log['time']               = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeEquipmentsList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function editEmployeeMedicalDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $emr_no = Input::get('emr_no');

        $counter = 0;
        if ($request->file('medical_file_path')) {
            foreach ($request->file('medical_file_path') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'EmrNo_' . $emr_no . '_employee_medical_file_' . time() .'_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_medical_documents', $file_name);

                    $fileUploadData['emr_no'] = $emr_no;
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

        $data1['disease_type_id'] = Input::get('disease_type_id');
        $data1['disease_date'] = Input::get('disease_date');
        $data1['amount'] = Input::get('amount');
        $data1['cheque_number'] = Input::get('cheque_number');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_medical')->where('id', Input::get('id'))->update($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeMedicalList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }

    public function editTrainingDetail(Request $request)
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
        $data1['trainer_name'] = Input::get('trainer_name');
        $data1['certificate_number'] = Input::get('certificate_number');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('trainings')->where('id', Input::get('id'))->update($data1);

        $certificate_uploading = $request->file('certificate_uploading');
        $extention = [];
        foreach ($certificate_uploading as $key => $value){
            $file_name = Input::get('certificate_number'). time() . '.' . $value->getClientOriginalExtension();
            $paths = 'app/' . $value->storeAs('uploads/training_certificate', $file_name);
            $path = $_FILES['certificate_uploading']['name'][$key];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $extention['file_type'] = $ext;
            $extention['certificate_uploading'] = $paths;
            $extention['training_id'] = Input::get('id');
            $extention['date'] = date("Y-m-d");
            $extention['time'] = date("H:i:s");

            DB::table('training_certificate')->where('training_id', Input::get('id'))->update($extention);
        }

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewTrainingList?pageType=viewlist&&parentCode=21&&m='.Input::get('m').'');


    }

    public function ediTransferProject(Request $request){
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $id = Input::get('transfer_id');

        $data1['emr_no'] = 		  Input::get('emr_no_id');
        $data1['emp_region_id'] = 		  Input::get('region_id');
        $data1['emp_categoery_id'] = 	  Input::get('emp_category_id');
        $data1['employee_project_id'] = 		  Input::get('transfer_project_id');
        $data1['status']=               1;
        $data1['approval_status']=      1;
        $data1['username']        = Auth::user()->name;
        $data1['date']=                 date("Y-m-d");
        $data1['time']=                 date("H:i:s");

        DB::table('transfer_employee_project')->where('id', $id)->update($data1);

        $check_letter_uploading = $_FILES['letter_uploading']['name'][0];
        if ($check_letter_uploading != '') {
            $letter_uploading = $request->file('letter_uploading');
            $extention = [];
            foreach ($letter_uploading as $key => $value) {
                $file_name = time().'_'.Input::get('emr_no').'_'.$key.'_'.$value->getClientOriginalExtension();
                $paths = 'app/' . $value->storeAs('uploads/promotions_letter', $file_name);
                $path = $_FILES['letter_uploading']['name'][$key];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $extention['file_type'] = $ext;
                $extention['emp_project_id'] = $id;
                $extention['letter_uploading'] = $paths;
                $extention['date'] = date("Y-m-d");
                $extention['time'] = date("H:i:s");
                DB::table('project_transfer_letter')->where('emp_project_id', '=', $id)->insert($extention);
            }
        }
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewProjectTransferList?pageType=viewlist&&parentCode=21&&m='.Input::get('company_id').'');
    }




}
