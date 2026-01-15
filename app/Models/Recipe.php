<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $connection = 'mysql2';
    protected $fillable = [
        'item_id',
        'description',
        'status',
        'created_by',


    ];


    public function recipeDatas()
    {
       return $this->hasMany(RecipeData::class,'recipe_id')->where('status', 1);
    }
   
    public function subItem()
    {
        return $this->belongsTo(Subitem::class,'item_id');
    }
    
}

