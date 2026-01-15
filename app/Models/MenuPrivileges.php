<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPrivileges extends Model{
    protected $table = 'menu_privileges';
    protected $fillable = ['user_id','department_id','main_modules','menu_titles','submenu_id','crud_rights','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = true;
}
