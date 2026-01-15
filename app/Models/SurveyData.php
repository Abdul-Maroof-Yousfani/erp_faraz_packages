<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyData extends Model{
    protected $table = 'survey_data';
    protected $fillable = ['product','type_id','height','width','depth','condition_id','remarks','survey_id','username','status','date'];
    protected $primaryKey = 'survey_data_id';
    public $timestamps = false;
}
