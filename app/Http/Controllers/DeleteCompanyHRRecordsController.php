<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\Payslip;
use Illuminate\Http\Request;
use App\Models\AdvanceSalary;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Employee;
use DB;
use Auth;
use Config;
use Redirect;
use Session;
use Input;

class DeleteCompanyHRRecordsController extends Controller
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
    public function deleteRowCompanyHRRecords()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = $_GET['recordId'];
        $tableName = $_GET['tableName'];
        DB::update('update '.$tableName.' set status = ? where id = ?',['2',$recordId]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully delete.');
    }

    public function approveAndRejectTableRecord()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'approval_status' => Input::get('approval_status'),
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully update.');
    }

    public function repostOneTableRecords()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        DB::update('update '.$tableName.' set status = ? where id = ?',['1',$recordId]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Repost.');
    }

    public function approveOneTableRecords()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        $column = Input::get('column');
        DB::update('update '.$tableName.' set '.$column.' = ? where id = ?',['2',$recordId]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Approved.');
    }

    public function rejectAdvanceSalaryWithPaySlip()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        $column = Input::get('column');

        $getAdvanceSalary = AdvanceSalary::select('deduction_month','deduction_year')->find($recordId)->toArray();
        if($getAdvanceSalary['deduction_month'][0] != '0' && $getAdvanceSalary['deduction_month'][0] != '1' ):
            $month = "0".$getAdvanceSalary['deduction_month'];
        else:
            $month = $getAdvanceSalary['deduction_month'];
        endif;
        Payslip::where([['month','=',$month],['year','=',$getAdvanceSalary['deduction_year']]])->delete();
        DB::update('update '.$tableName.' set '.$column.' = ? where id = ?',['3',$recordId]);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Approved.');
    }

    public function approveAdvanceSalaryWithPaySlip()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        DB::update('update advance_salary set approval_status = ? where id = ?',['2',$recordId]);
        $employeeData = Employee::select('id','emp_sub_department_id', 'emp_salary')->find(Input::get('emp_id'))->toArray();

          $totalAllowance = DB::table('allowance')
            ->select(DB::raw('SUM(allowance_amount) as allowance_amount'))
            ->first();

        $totalDeduction = DB::table('deduction')
            ->select(DB::raw('SUM(deduction_amount) as deduction_amount'))
            ->first();

       $netAmount = ($employeeData['emp_salary']+$totalAllowance->allowance_amount)-$totalDeduction->deduction_amount;
        $getAdvanceSalary = AdvanceSalary::select('deduction_month','deduction_year')->find($recordId)->toArray();

        if($getAdvanceSalary['deduction_month'][0] != '0' && $getAdvanceSalary['deduction_month'][0] != '1' ):
            $date = "0".$getAdvanceSalary['deduction_month'];
        else:
            $date = $getAdvanceSalary['deduction_month'];
        endif;

        $str = DB::selectOne("select max(convert(substr(`ps_no`,4,length(substr(`ps_no`,4))-4),signed integer)) reg from `payslip` where substr(`ps_no`,-4,2) = ".date('m')." and substr(`ps_no`,-2,2) = ".date('y')."")->reg;
        $ps_no = 'psc'.($str+1).date('my');

       $data['ps_no']                   = $ps_no;
       $data['emp_id']                  = $employeeData['id'];
       $data['emp_sub_department_id']   = $employeeData['emp_sub_department_id'];
       $data['month']                   = $date;
       $data['year']                    = $getAdvanceSalary['deduction_year'];
       $data['basic_salary']            = $employeeData['emp_salary'];
       $data['total_allowance']         = $totalAllowance->allowance_amount;
       $data['total_deduction']         = $totalDeduction->deduction_amount;
       $data['net_salary']              = $netAmount;
       $data['salary_status']           = 1;
       $data['status']                  = 1;
       $data['username']                = Auth::user()->name;
       $data['date']     		        = date("Y-m-d");
       $data['time']     		        = date("H:i:s");

       DB::table('payslip')->insert($data);
       CommonHelper::reconnectMasterDatabase();

    }

    public function deleteAdvanceSalaryWithPaySlip()
    {

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');


        $getAdvanceSalary = AdvanceSalary::select('deduction_month','deduction_year')->find($recordId)->toArray();
        if($getAdvanceSalary['deduction_month'][0] != '0' && $getAdvanceSalary['deduction_month'][0] != '1' ):
            $month = "0".$getAdvanceSalary['deduction_month'];
        else:
            $month = $getAdvanceSalary['deduction_month'];
        endif;
        Payslip::where([['month','=',$month],['year','=',$getAdvanceSalary['deduction_year']]])->delete();
        DB::update('update '.$tableName.' set status = ? where id = ?',['2',$recordId]);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully delete.');
    }


    public function deleteLeavesDataPolicyRows()
    {

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        DB::update('update leaves_policy set status = ? where id = ?',['2',Input::get('recordId')]);
        DB::update('update leaves_data set status = ? where leaves_policy_id = ?',['2',Input::get('recordId')]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully delete.');
    }

    public function repostMasterTableRecords()
    {

        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');

        DB::update('update '.$tableName.' set status = ? where id = ?',['1',$recordId]);

    }

    /* Loan Request functions Start Here */

    public function approveLoanRequest()
    {

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        $recordId = Input::get('recordId');
        DB::update('update loan_request set approval_status = ? where id = ?',['2',Input::get('recordId')]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Approved.');

    }

    public function rejectLoanRequest()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        $recordId = Input::get('recordId');
        DB::update('update loan_request set approval_status = ? where id = ?',['3',Input::get('recordId')]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Rejected.');

    }
    public function deleteLoanRequest()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        $recordId = Input::get('recordId');
        DB::update('update loan_request set status = ? where id = ?',['2',Input::get('recordId')]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Deleted.');
    }
    /* Loan Request functions  End */

    /* Delete Employee Bonus Start*/

    public function deleteEmployeeBonus()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));;
        DB::update('delete from bonus_issue where  id = ?',[Input::get('id')]);
        CommonHelper::reconnectMasterDatabase();
    }
    /* Delete Employee Bonus End*/

    public function deleteLeaveApplicationDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        DB::update('update leave_application set status = ? where id = ?',['2',Input::get('recordId')]);
        DB::update('update leave_application_data set status = ? where leave_application_id = ?',['2',Input::get('recordId')]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Deleted.');

    }


    public function approveAndRejectLeaveApplication()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        DB::update('update leave_application set approval_status = ? where id = ?',[Input::get('approval_status'),Input::get('recordId')]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Updated.');
    }

    public function approveAndRejectEmployeeRequisition()
    {

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        DB::update('update employee_requisition set approval_status = ? where id = ?',[Input::get('approval_status'),Input::get('recordId')]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Updated.');

    }

    public function deleteEmployeeAttendance()
    {
        $explodeMonthYear = explode("-",Input::get('month_year'));
        $employee_id = Input::get('employee_id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($employee_id == 'All'):

            DB::table('attendance')->where([['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->delete();
          //  $attendance = Attendance::where([['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->orderBy('attendance_date')->get();

        else:
            DB::table('attendance')->where([['emp_id','=',$employee_id],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->delete();

            //$attendance = Attendance::where([['emp_id','=',$employee_id],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->orderBy('attendance_date')->get();

        endif;
        CommonHelper::reconnectMasterDatabase();
        echo "<div class='row text-center'><b style='color:green'>Attendance Deleted Successfully !</b></div>";

    }

    public function approveAndRejectEmployeeExit()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $tableName = Input::get('tableName');
        $updateEmpoyeeExit=array(
            'approval_status' => Input::get('approval_status'),
            'username' => Auth::user()->name
        );

        DB::table($tableName)
            ->where('id', Input::get('recordId'))
            ->update($updateEmpoyeeExit);

        $updateEmpoyee=array(
            'status' => Input::get('employee_status'),
            'username' => Auth::user()->name
        );

        DB::table('employee')
            ->where('emp_code', Input::get('emp_code'))
            ->update($updateEmpoyee);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully update.');
    }

    public function restoreEmployee()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'status' => 1,
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', $recordId)
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','Successfully Activated.');
    }

    public function approveAndRejectForAjaxPages()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $tableName = Input::get('table_name');
        $updateDetails=array(
            'approval_status' => Input::get('approval_status'),
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', Input::get('record_id'))
            ->update($updateDetails);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','Successfully Update.');
    }

}

