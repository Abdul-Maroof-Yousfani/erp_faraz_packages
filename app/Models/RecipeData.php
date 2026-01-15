<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeData extends Model
{
    protected $connection = 'mysql2';
    protected $fillable = [
        'recipe_id',
        'item_id',
        'quantity',
        'status',
        'created_by',
    ];
    
    public function subItem(){
        return $this->belongsTo(SubItem::class,'item_id');
    }
}
