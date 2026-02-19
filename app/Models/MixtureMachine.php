<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MixtureMachine extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'mixture_machines';
    protected $guarded = [];
}
