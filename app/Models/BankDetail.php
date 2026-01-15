<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'bank_detail';
    public $timestamps = false;
    protected $guarded = [];
}
