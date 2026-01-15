<?php
namespace App\Helpers;
use DB;
use Config;
use DateTime;
use App\Helpers\CommonHelper;
use App\Models\Attendance;
use App\Models\EmployeeBankData;
use App\Models\Employee_projects;
use App\Models\Employee;
use App\Models\TransferEmployeeProject;
use App\Models\Holidays;
class HrHelper{


                                        /*company_id*/ /*table name*/ /*column name*/ /*column_id*/

    public static function takenLeavesLeaveTypeWise(){
        return 'abc';
    }

    public static function totalLateForThisRange($param1,$param2,$param3,$param4){
        $totalLateForThisRange = 0;
        CommonHelper::companyDatabaseConnection($param1);
        $fromDateOne = date_create($param2);
        $toDateOne = date_create($param3);
        $fromDate = date_format($fromDateOne,'n/j/yyyy');
        $toDate = date_format($toDateOne,'n/j/yyyy');
        $countTotalLateForThisRange = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$param4)->where('late','!=','')->get();
        CommonHelper::reconnectMasterDatabase();
        $totalLateForThisRange = count($countTotalLateForThisRange);

        return $totalLateForThisRange;
    }

    public static function totalAbsentForThisRange($param1,$param2,$param3,$param4){
        $totalAbsentForThisRange = 0;
        CommonHelper::companyDatabaseConnection($param1);
        $fromDateOne = date_create($param2);
        $toDateOne = date_create($param3);
        $fromDate = date_format($fromDateOne,'n/j/yyyy');
        $toDate = date_format($toDateOne,'n/j/yyyy');
        $countTotalAbsentForThisRange = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$param4)->where('absent','!=','')->get();
        CommonHelper::reconnectMasterDatabase();
        $totalAbsentForThisRange = count($countTotalAbsentForThisRange);

        return $totalAbsentForThisRange;
    }

    public static function totalLateForThisAccountingYear(){
        return '0';
    }

    public static function totalAbsentForThisAccountingYear(){
        return '0';
    }

    /*company_id*/ /*table name*/ /*column name*/ /*column_id*/
    public static function getMasterTableValueById($param1,$param2,$param3,$param4){
        //DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' )->$param3;
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        if($param4 != '' && $param4 != 0):
            $detailName = DB::table($param2)->select($param3)->where([['status', '=', 1],['company_id', '=', $param1],['id', '=', $param4]]);
            if($detailName):
                return $detailName->value($param3);
            else:
                return '';
            endif;
        else:
            return '';
        endif;
    }


    public static function getMasterTableValueByIdAndColumn($param1,$param2,$param3,$param4,$param5){
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' );

        if($detailName):
            return $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and '.$param5.' = '.$param4.'' )->$param3;
        else:
            return ;
        endif;
    }
    public static function getCompanyTableValueByIdAndColumn($param1,$param2,$param3,$param4,$param5){

        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');

        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where  '.$param5.' = '.$param4.'' );

        if($detailName):
            $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where '.$param5.' = '.$param4.'' )->$param3;
        else:
            $detailName = '';
        endif;
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        return $detailName;

    }


    public static function getCompanyTableValueById($param1,$param2,$param3,$param4){

        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');

        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and id = '.$param4.'' );

        if($detailName):
            $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and id = '.$param4.'' )->$param3;
        else:
            $detailName = '<span style="color:red">Deleted</span>';
        endif;
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        return $detailName;

    }

    public static function getStatusLabel($param)
    {
        $array[1] ="<span class='label label-success'>Active</span>";
        $array[2] ="<span class='label label-danger'>Deleted</span>";
        $array[3] ="<span class='label label-danger'>Exit</span>";
        $array[4] ="<span class='label label-warning'>InActive</span>";

        echo $array[$param];
    }

    public static function getApprovalStatusLabel($param)
    {
        $array[1] ='<span class="label label-warning">Pending</span>';
        $array[2] ='<span class="label label-success">Approved</span>';
        $array[3] ='<span class="label label-danger">Rejected</span>';
        echo $array[$param];
    }

    public static function date_format($str)
    {
         return date("d-m-Y", strtotime($str));
    }

    public static function hr_date_format($str)
    {
        $myDateTime = date_create_from_format('Y-m-d',$str);
        $new_date = $myDateTime->format('F d, Y');
        return $new_date;
    }

    public static function getIdCardStatus($param)
    {
        $array[1] ='<span class="label label-warning">Pending</span>';
        $array[2] ='<span class="label label-info">Printed</span>';
        $array[3] ='<span class="label label-success">Delivered</span>';
        echo $array[$param];
    }

    public static function getEmployeeBankData($param1,$param2,$param3)
    {
        CommonHelper::companyDatabaseConnection($param1);
           $EmployeeBankData =  EmployeeBankData::where([['status','=',$param2],['emp_code','=',$param3]])->value('account_no');
        CommonHelper::reconnectMasterDatabase();
            return $EmployeeBankData;
    }

    public static function getAllEmployeeId($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
        $employeeEmpCodeArray = [];
            if($param3 != '0'){
               $employee_emp_code = Employee::select('emp_code')->where([['emp_department_id','=',$param2],['region_id','=',$param3],['status','!=',2]])
                   ->get();
            }
            else if($param3 == '0'){
                $employee_emp_code = Employee::select('emp_code')->where([['emp_department_id','=',$param2],['region_id','=',$param3],['status','!=',2]])
                                ->get();
            }
           else{
               $employee_emp_code = Employee::select('emp_code')->where('status','!=',2)
                                  ->get();
           }

         CommonHelper::reconnectMasterDatabase();
         foreach ($employee_emp_code as $value){
             $employeeEmpCodeArray[] = $value->emp_code;
         }
         return $employeeEmpCodeArray;
    }

    public static function getEmployeeData($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
            $employee_data = Employee::select($param2)->where($param3,'=',$param4)->get();
            foreach ($employee_data as $value){
                 return $value->$param2;
            }
    }

    public static function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if (($number %100) >= 11 && ($number%100) <= 13)
            return $abbreviation = $number. 'th';
        else
            return $abbreviation = $number. $ends[$number % 10];
    }

   public static function getActiveProjectId($param1,$param2){
     $projectdataArray = [];
       CommonHelper::companyDatabaseConnection($param1);
         $TransferEmployeeProject = TransferEmployeeProject::where([['emr_no','=',$param2],['status','=',1],['active','=',1]]);
         $employee = Employee::where([['emr_no','=',$param2],['status','=',1],['active','=',1]]);
         CommonHelper::reconnectMasterDatabase();

         if($TransferEmployeeProject->count() > 0){
              $projectData = $TransferEmployeeProject->get();
          }
         else{
              if($employee->count() > 0){
               $projectData = $employee->get();
              }
         }

         foreach($projectData as $value){
              $projectdataArray[] = $value->employee_project_id;

          }
          return $projectdataArray;
   }

   public static function getProjectName($param1,$param2,$param3){

        $projectName = DB::table($param1)->select($param2)->whereIn('id',$param3)->get();

        foreach($projectName as $value){
            echo $value->$param2;
            return;
        }

   }
   
   public static function getIncomeTax($taxable_salary)
    {
        $tax_slabs = DB::table('tax_slabs')->where([['status','=','1'],['tax_id', '=',1 ]])->get();
        $payable_salary_taxable = ($taxable_salary * 12) / 1.1;
        $tax = 0;
        foreach($tax_slabs as $value):
            if($payable_salary_taxable > $value->salary_range_from && $payable_salary_taxable <= $value->salary_range_to):
                $tax_percent = $value->tax_percent;
                $tax_amount = $value->tax_amount;
                $income_tax = round((($payable_salary_taxable - $value->salary_range_from) / 100) * $tax_percent) + $tax_amount;
                $tax = round($income_tax/12);
            endif;
        endforeach;
        return $tax;
    }

    public static function checkHolidayStatus($day, $month, $year, $hijri, $m)
    {
        $holiday_date = $year.'-'.$month.'-'.$day;
        CommonHelper::companyDatabaseConnection($m);
        $holiday = Holidays::where([['holiday_date', '=', $holiday_date],['status', '=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        if($holiday->count() > 0):
            $id = $holiday->value('id');
            $date = new DateTime($holiday->value('holiday_date'));
            $now = new DateTime();

            if($date < $now) :
                $style = 'style="background-color: red; color: white !important"';
            else:
                $style = 'style="background-color: #5fd65f;"';
            endif;
            echo "<td $style class='mouse-hover' onclick='functionModal(".$id.")'>".$day." ".$hijri."</td>";
        else:
            echo "<td class='mouse-hover' onclick='addHolidaysDetail(".$day.",".$month.",".$year.")'>".$day." ".$hijri."</td>";
        endif;
    }

}
?>