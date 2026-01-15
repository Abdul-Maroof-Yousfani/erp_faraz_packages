<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gst extends Model{

    protected $table = 'gst';
    protected $connection ='mysql2';
    public $timestamps = false;
}
