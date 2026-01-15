<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model{
    protected $table = 'test';
    protected $fillable = ['col1','col2','col3','col4','col5'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
