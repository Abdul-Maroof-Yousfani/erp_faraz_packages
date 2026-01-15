<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserColumnPreference extends Model {
    protected $table = 'user_column_preferences';
    protected $fillable = ['user_id', 'list_id', 'column_names', 'column_order'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $connection = 'mysql2';
}
