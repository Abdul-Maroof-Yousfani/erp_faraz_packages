<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRelativesData extends Model{
    protected $table = 'employee_relatives';
    protected $fillable = ['emp_code','relative_name','relative_position','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

