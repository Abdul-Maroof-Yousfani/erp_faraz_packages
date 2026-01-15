<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use Input;
use Session;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Payslip;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\JobType;
use App\Models\SubDepartment;
use App\Models\MaritalStatus;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\CarPolicy;
use App\Models\LeaveApplicationData;
use App\Models\LeaveApplication;



class HrEditDetailByAjaxController extends Controller
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


    public function EditEmployeeCarPolicyDetail()
    {

          CommonHelper::companyDatabaseConnection(Input::get('m'));
          DB::table('employee')->where('id', Input::get('id'))->update(['car_policy_id'=>Input::get('policy_id')]);

            $policy = Employee::select('id')->where([['id','=',Input::get('id')],['car_policy_id','>','0']])->count();

            if($policy == '0'):
                $label ='';
                $cancel_assign_btn  = ' <li role="presentation text-center">
                       <a style="cursor:pointer;" class="edit-modal" onclick="assignCarPolicy('.Input::get('id').','.Input::get('assign_id').')">Assign</a>
                       </li>';

            else:
                $label = 'Policy Status: <span class="label label-success">Assigned</span>';
                $cancel_assign_btn  = ' <li role="presentation text-center">
                       <a style="cursor:pointer;" class="edit-modal" onclick="cancelCarPolicy('.Input::get('id').','.Input::get('policy_id').')">Cancel</a>
                       </li>';

            endif;



            $object = ['label'=>$label,'cancel_assign_btn'=>$cancel_assign_btn];
          echo json_encode(['data'=>$object]);
    }


    public function EditEmployeeTaxDetail()
    {


        CommonHelper::companyDatabaseConnection(Input::get('m'));
        DB::table('employee')->where('id', Input::get('id'))->update(['tax_id'=>Input::get('tax_id')]);

        $policy = Employee::select('id')->where([['id','=',Input::get('id')],['tax_id','>','0']])->count();

        if($policy == '0'):
            $label ='';
            $cancel_assign_btn  = ' <li role="presentation text-center">
                       <a style="cursor:pointer;" class="edit-modal" onclick="assignTax('.Input::get('id').','.Input::get('assign_id').')">Assign</a>
                       </li>';

        else:
            $label = 'Policy Status: <span class="label label-success">Assigned</span>';
            $cancel_assign_btn  = ' <li role="presentation text-center">
                       <a style="cursor:pointer;" class="edit-modal" onclick="cancelTax('.Input::get('id').','.Input::get('tax_id').')">Cancel</a>
                       </li>';

        endif;
        
        $object = ['label'=>$label,'cancel_assign_btn'=>$cancel_assign_btn];
        echo json_encode(['data'=>$object]);

    }

    public function editLeaveApplicationDetail()
    {
        $leave_application_id = Input::get('leave_application_id');
			
        $leaveApplicationData['emr_no']          = Input::get('emp_id');
        $leaveApplicationData['leave_policy_id'] = Input::get('leave_policy_id');
        $leaveApplicationData['company_id']      = Input::get('company_id');
        $leaveApplicationData['leave_type']      = Input::get('leave_type');
        $leaveApplicationData['leave_day_type']  = Input::get('leave_day_type');
        $leaveApplicationData['reason']          = Input::get('reason');
        $leaveApplicationData['leave_address']   = Input::get('leave_address');


        DB::table('leave_application')->where([['id','=',$leave_application_id]])->update($leaveApplicationData);

        /* Annual Leaves ID = 3 */
        if(Input::get('leave_type') == 1):

            $maternityLeavesData['emr_no']               = Input::get('emp_id');
            $maternityLeavesData['leave_policy_id']      = Input::get('leave_policy_id');
            $maternityLeavesData['leave_type']           = Input::get('leave_type');
            $maternityLeavesData['leave_day_type']       = Input::get('leave_day_type');
            $maternityLeavesData['no_of_days']           = Input::get('no_of_days');
            $maternityLeavesData['from_date']            = Input::get('from_date');
            $maternityLeavesData['to_date']              = Input::get('to_date');


            DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($maternityLeavesData);

        elseif(Input::get('leave_type') == 2):

            /* Full Day Leaves */
            if(Input::get('leave_day_type') == 1):

                $annualLeavesData['emr_no']               = Input::get('emr_no');
                $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $annualLeavesData['leave_type']           = Input::get('leave_type');
                $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
                $annualLeavesData['no_of_days']           = Input::get('no_of_days');
                $annualLeavesData['from_date']            = Input::get('from_date');
                $annualLeavesData['to_date']              = Input::get('to_date');


                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($annualLeavesData);

            /* Half Day Leaves */
            elseif(Input::get('leave_day_type') == 2):

                $halfdayLeavesData['emr_no']                   = Input::get('emr_no');
                $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $halfdayLeavesData['leave_type']               = Input::get('leave_type');
                $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
                $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
                $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
                $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');


                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($halfdayLeavesData);

            else:
                /* Short Leaves */

                $shortLeavesData['emr_no']               = Input::get('emr_no');
                $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $shortLeavesData['leave_type']           = Input::get('leave_type');
                $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
                $shortLeavesData['no_of_days']           = Input::get('no_of_days');
                $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
                $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
                $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');



                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($shortLeavesData);

            endif;

        elseif(Input::get('leave_type') == 3):


            /* Full Day Leaves */
            if(Input::get('leave_day_type') == 1):

                $annualLeavesData['emr_no']               = Input::get('emr_no');
                $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $annualLeavesData['leave_type']           = Input::get('leave_type');
                $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
                $annualLeavesData['no_of_days']           = Input::get('no_of_days');
                $annualLeavesData['from_date']            = Input::get('from_date');
                $annualLeavesData['to_date']              = Input::get('to_date');


                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($annualLeavesData);

            /* Half Day Leaves */
            elseif(Input::get('leave_day_type') == 2):

                $halfdayLeavesData['emr_no']                   = Input::get('emr_no');
                $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $halfdayLeavesData['leave_type']               = Input::get('leave_type');
                $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
                $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
                $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
                $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');

                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($halfdayLeavesData);

            else:
                /* Short Leaves */

                $shortLeavesData['emr_no']               = Input::get('emr_no');
                $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $shortLeavesData['leave_type']           = Input::get('leave_type');
                $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
                $shortLeavesData['no_of_days']           = Input::get('no_of_days');
                $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
                $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
                $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');


                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($shortLeavesData);

            endif;

        else:


            /* Full Day Leaves */
            if(Input::get('leave_day_type') == 1):

                $annualLeavesData['emr_no']               = Input::get('emr_no');
                $annualLeavesData['leave_policy_id']      = Input::get('leave_policy_id');
                $annualLeavesData['leave_type']           = Input::get('leave_type');
                $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
                $annualLeavesData['no_of_days']           = Input::get('no_of_days');
                $annualLeavesData['from_date']            = Input::get('from_date');
                $annualLeavesData['to_date']              = Input::get('to_date');


                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($annualLeavesData);

            /* Half Day Leaves */
            elseif(Input::get('leave_day_type') == 2):

                $halfdayLeavesData['emr_no']                   = Input::get('emr_no');
                $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $halfdayLeavesData['leave_type']               = Input::get('leave_type');
                $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
                $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
                $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
                $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');

                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($halfdayLeavesData);

            else:
                /* Short Leaves */

                $shortLeavesData['emr_no']               = Input::get('emr_no');
                $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $shortLeavesData['leave_type']           = Input::get('leave_type');
                $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
                $shortLeavesData['no_of_days']           = Input::get('no_of_days');
                $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
                $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
                $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');


                DB::table('leave_application_data')->where([['leave_application_id','=',$leave_application_id]])->update($shortLeavesData);

            endif;

        endif;
        return "1";

        Session::flash('dataInsert','successfully saved.');
        CommonHelper::reconnectMasterDatabase();

    }

    public function NeglectEmployeeAttendance()
    {
        $attendance_date = date("Y-m-d",strtotime(Input::get('attendance_date')));
        $emp_code = Input::get('emp_code');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        DB::table('attendance')->where([['id','=',Input::get('attendance_id')]])->update(['neglect_attendance'=>'yes']);
        $month_year = explode('-',$attendance_date);
        $data['emp_code']        = strip_tags($emp_code);
        $data['query_type']     = 'late';
        $data['query_date']    = $attendance_date;
        $data['month']         = $month_year[1];
        $data['year']          = $month_year[0];
        $data['remarks']       = strip_tags(Input::get('reason'));
        $data['username']      = Auth::user()->name;
        $data['status']        = 1;
        $data['date']          = date("Y-m-d");
        $data['time']          = date("H:i:s");

        DB::table('user_query')->where([['emp_code','=',$emp_code],['query_date','=',$attendance_date],['query_type','=','late']])->delete();
        DB::table('user_query')->insert($data);

        CommonHelper::reconnectMasterDatabase();
    }

}
?>