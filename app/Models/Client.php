<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model{
    protected $table = 'client';
    protected $fillable = ['client_name','ntn','strn','address','status','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

