<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientJob extends Model{
    protected $table = 'client_job';
    protected $fillable = ['client_job','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
