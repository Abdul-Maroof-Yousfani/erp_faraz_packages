<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{
	protected $table = 'roles';
	protected $fillable = ['role_no','name','description'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
