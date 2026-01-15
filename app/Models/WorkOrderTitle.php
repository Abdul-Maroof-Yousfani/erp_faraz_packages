<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderTitle extends Model{
    protected $table = 'work_order_title';
    protected $fillable = ['title','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $connection = 'mysql2';
}
