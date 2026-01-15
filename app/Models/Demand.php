<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model{
    protected $table = 'demand';
    protected $fillable = ['slip_no','demand_no','demand_date','sub_department_id','description','demand_status','status','date','time','username','approve_username','delete_username','p_type'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
