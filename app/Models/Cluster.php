<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model{
    protected $table = 'cluster';
    protected $fillable = ['cluster_name','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
