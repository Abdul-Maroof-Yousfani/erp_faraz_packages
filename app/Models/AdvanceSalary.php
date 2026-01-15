<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceSalary extends Model{
    protected $table = 'advance_salary';
    protected $fillable = ['emp_id','salary_needed_on','deduction_month','deduction_year','username','approval_status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
