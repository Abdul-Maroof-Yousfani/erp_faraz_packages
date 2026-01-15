<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailNotify extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'notifications';
    protected $fillable = ['step_id','behavior_id','dept_id','email_1','email_2','body_1','body_2'];
    protected $primaryKey = 'id';
    public $timestamps  =false;
}
