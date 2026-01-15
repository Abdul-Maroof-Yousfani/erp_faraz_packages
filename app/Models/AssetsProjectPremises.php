<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsProjectPremises extends Model{
    protected $table = 'assets_project_premises';
    protected $fillable = ['project_id','premises_name','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
