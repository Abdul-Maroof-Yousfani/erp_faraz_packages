<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class BLDetail extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'bl_details';
    protected $guarded = [];
}
