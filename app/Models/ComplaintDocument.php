<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintDocument extends Model{
    protected $table = 'complaint_document';
    protected $fillable = ['image_file','complaint_id','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
