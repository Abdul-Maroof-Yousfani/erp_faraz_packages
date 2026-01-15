<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model{
	protected $table = 'cities';
	protected $fillable = ['name','state_id','status'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
