<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsPreventive extends Model{
    protected $table = 'assets_preventive';
    protected $fillable = ['asset_id','last_pm_date','pm_frequency_id','next_pm_date','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}

