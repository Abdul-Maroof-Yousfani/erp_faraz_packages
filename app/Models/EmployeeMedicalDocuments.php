<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMedicalDocuments extends Model{
    protected $table = 'employee_medical_documents';
    protected $fillable = ['emp_code','medical_file_name','medical_file_type','medical_file_path','counter','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
