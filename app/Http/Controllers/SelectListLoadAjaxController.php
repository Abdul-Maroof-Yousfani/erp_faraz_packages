<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\Employee;
use App\Models\Locations;
use App\Models\Designation;
class SelectListLoadAjaxController extends Controller
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
	 
	 public function stateLoadDependentCountryId(){
	 	$country_id = Input::get('id');
	 	$states = States::where('status', '=', 1)
				->where('country_id','=',$country_id)->get();
                    foreach($states as $row){
     ?>
                <option value="<?php echo $row['id']?>"><?php echo $row['name'];?></option>
     <?php
                    }
     ?>
         <script>
             $(document).ready(function() {
                 $("#state_1").on('change', function () {

                     var stateID = $('#state_1').val();
                     if (stateID) {
                         $.ajax({
                             url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                             type: "GET",
                             data: {id: stateID},
                             success: function (data) {
                                 $('#city_1').html(data);
                             }
                         });
                     }

                 });

             })
         </script>

	 <?php }
	 
	 public function cityLoadDependentStateId(){
	 	$state_id = Input::get('id');
	 	$cities = Cities::where('status', '=', 1)
				->where('state_id','=',$state_id)->get();
	 	foreach($cities as $row){?>
            <option value="<?php echo $row['id']?>"><?php echo $row['name'];?></option>
        <?php
             }
     }
	 
	 public function employeeLoadDependentDepartmentID(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where('status', '=', 1)
				->where('emp_sub_department_id','=',Input::get('sub_department_id'))->get();
	?>
		<option value="All">All Employees</option>
	<?php
		foreach($employees as $row){
	?>
		<option value="<?php echo $row['id']?>"><?php echo $row['emp_name'];?></option>
	<?php
		 }
         CommonHelper::reconnectMasterDatabase();
	 }




    public function employeeListDeptWise(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::select('id','emp_name')->where('status', '=', 1)
            ->where('emp_department_id','=',Input::get('department_id'));

        if($employees->count() > 0):
            echo "<option value='All'>All</option>";
        foreach($employees->get() as $row){ ?>
            <option value="<?php echo $row['id']?>"><?php echo $row['emp_name'];?></option>
            <?php
        }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;
        CommonHelper::reconnectMasterDatabase();
    }


	public function MachineEmployeeListDeptWise()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::select('attendance_machine_id','emp_name')->where('status', '=', 1)
            ->where('emp_department_id','=',Input::get('department_id'));

        if($employees->count() > 0):
            echo "<option value='All'>All</option>";
            foreach($employees->get() as $row){ ?>
                <option value="<?php echo $row['attendance_machine_id']?>"><?php echo $row['emp_name'];?></option>
                <?php
            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;
        CommonHelper::reconnectMasterDatabase();
    }

    public function getEmployeeRegionList()
    {
        $exit_clearance = Input::get('exit_clearance');
        $region_id = Input::get('region_id');
        $department_id = Input::get('department_id');
        if($exit_clearance == 'exit_clearance'){
            $status = [1,3];
        }
        else{
            $status = [1];
        }

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($region_id != '' && $department_id == ''){
            $employee = Employee::select('emp_code','emp_name')->where('region_id','=',$region_id)->whereIn('status',$status);
        }
        else{
            $employee = Employee::select('emp_code','emp_name')->where([['region_id','=',$region_id],['emp_department_id','=',$department_id]])->whereIn('status',$status);
        }
        CommonHelper::reconnectMasterDatabase();

        if(count($employee->get()) != '0'){
            ?>
            <?php
            foreach($employee->get() as $value){
                ?>
                <option value="<?php echo $value->emp_code ?>"><?php echo 'Emp Code: ' . $value->emp_code . ' -- ' . $value->emp_name; ?></option>
                <?php
            }
        }
        else{
            echo "<option value=''>No Record Found</option>";
        }
    }

    public function locationDependentRegion()
    {
        $locations = Locations::select('employee_location', 'id')
            ->where([['region_id','=', Input::get('region_id')], ['status', '=', '1']]);
        if ($locations->count() > 0):
            echo "<option value=''>Select Location</option>";
            foreach ($locations->get() as $row) { ?>
                <option
                    value="<?php echo $row['id'] ?>"><?php echo $row['employee_location'] ?></option>
                <?php
            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;
    }

    public function departmentDependentDesignation()
    {
        $designations = Designation::select('designation_name', 'id')
            ->where([['department_id','=', Input::get('department_id')], ['status', '=', '1']]);
        if ($designations->count() > 0):
            echo "<option value=''>Select Designation</option>";
            foreach ($designations->get() as $row) { ?>
                <option
                    value="<?php echo $row['id'] ?>"><?php echo $row['designation_name'] ?></option>
                <?php
            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;
    }
}
