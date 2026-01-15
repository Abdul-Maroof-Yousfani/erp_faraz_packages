<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SuperAdminController extends Controller
{
    public function index(){
        return view('superadmin.home');
    }
}
