<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRequisitionCandidates extends Model{
    protected $table = 'employee_requisition_candidates';
    protected $fillable = ['employee_requisition_id,email','contact_no','expected_salary','cv_path','status','data','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
