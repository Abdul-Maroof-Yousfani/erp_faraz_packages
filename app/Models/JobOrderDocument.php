<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOrderDocument extends Model{
    protected $table = 'job_order_document';
    protected $fillable = ['image_file','job_order_id','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
