<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTransfer extends Model{
    protected $table = 'employee_location';
    protected $fillable = ['emp_code','location_id','department_id','promotion_id','approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
