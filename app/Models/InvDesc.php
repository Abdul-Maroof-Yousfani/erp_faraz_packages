<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvDesc extends Model{
    protected $table = 'invoice_desc';
    protected $fillable = ['invoice_desc','status','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
