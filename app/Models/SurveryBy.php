<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveryBy extends Model{
    protected $table = 'survey_by';
    protected $fillable = ['name','remarks','status','date','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
