<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class ClearingAgentMember extends Model
{
    protected $table = 'clearing_agent_member';
    protected $connection = 'mysql2';
    protected $guarded = [];
    public $timestamps = false;
}
