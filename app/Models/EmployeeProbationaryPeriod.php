<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeprobationaryPeriod extends Model{
    protected $table = 'employee_probatioary_period';
//    protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    protected $primaryKey = 'emp_id';
    public $timestamps = false;
}
