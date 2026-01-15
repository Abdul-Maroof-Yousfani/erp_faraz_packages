<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankFacility extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'bank_facility';
    public $timestamps = false;
    protected $guarded = [];
}
