<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakeProductData extends Model
{
    protected $connection = "mysql2";
    protected $guarded = [];

    public function recipeData()
    {
        return $this->belongsTo(RecipeData::class);
    }
}
