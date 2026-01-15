<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceDepartment extends Model{
    protected $table = 'finance_department';
 //   protected $fillable = ['company_id','EOBI_name','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
