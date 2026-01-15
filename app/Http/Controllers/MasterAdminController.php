<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MasterAdminController extends Controller
{
    public function index(){
        return view('cmaster.home');
    }
}
