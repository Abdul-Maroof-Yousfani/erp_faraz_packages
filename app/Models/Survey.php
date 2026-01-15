<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model{
    protected $table = 'survey';
    protected $fillable = ['branch_name','contact_person','contact_number','survey_date','username','status','date'];
    protected $primaryKey = 'survey_id';
    public $timestamps = false;
}
