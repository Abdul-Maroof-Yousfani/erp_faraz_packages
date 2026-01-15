<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyDocument extends Model{
    protected $table = 'survey_document';
    protected $fillable = ['image_file','survey_id','status'];
    protected $primaryKey = 'survey_document_id';
    public $timestamps = false;
}
