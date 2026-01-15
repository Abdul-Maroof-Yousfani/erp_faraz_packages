<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRequisition extends Model{
	protected $table = 'employee_requisition';
	protected $fillable = ['employee_requisition_No','no_of_emp_required','job_title','department_id',
        'job_type_id','designation_id','qualification_id','additional_replacement','replacement_description','additional_description',
        'ex_emp_seperation_date','ex_emp_benefits','other_requirment','age_group_from','age_group_to','job_description_exist',
        'job_description_attached','requisitioned_by','recommended_by','chairman_approval',
        'form_recieved_in_hrd_on','verified_against_position','hr_comments','form_recieved_by',
        'location','experience','career_level','apply_before_date','gender','ApprovalStatus','status',
        'username','date','time'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
