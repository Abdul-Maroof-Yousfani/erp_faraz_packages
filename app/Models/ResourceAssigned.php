<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceAssigned extends Model{
    protected $table = 'resource_assign';
    protected $fillable = ['resource_type','status','username','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
