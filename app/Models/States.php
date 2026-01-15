<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model{
	protected $table = 'states';
	protected $fillable = ['name','country_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
