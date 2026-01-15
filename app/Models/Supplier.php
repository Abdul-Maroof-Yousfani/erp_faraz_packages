<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model{
	protected $table = 'supplier';
	protected $connection = 'mysql2';
	protected $fillable = ['company_name','type','name','address','country','province','city','contact','status','action','username','date','time','branch_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;

	/** Scopes */
    // get unique no
    function scopeUniqueNo($query)
    {
     $id = $query->max('id')+1;
    return  $number = sprintf('%03d',$id);

    }
}
