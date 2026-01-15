<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model{
	protected $table = 'customers';
	protected $connection = 'mysql2';
	protected $fillable = ['acc_id','type','customer_type','name','company_name','address','country','province','city','contact','email','status','action','username','date','time','branch_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
