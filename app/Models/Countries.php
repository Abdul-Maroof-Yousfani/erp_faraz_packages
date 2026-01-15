<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model{
	protected $table = 'countries';
	protected $fillable = ['sortname','name','phonecode','status'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
