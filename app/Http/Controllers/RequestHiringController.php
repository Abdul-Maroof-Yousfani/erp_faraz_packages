<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use App\Models\MainMenuTitle;
use App\Models\Menu;
use App\Models\Job;
use App\Models\RequestHiring;
use HelperRequestHiring;

class RequestHiringController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function viewHiringRequestList(){

       
        $jobs = new Job;
		$jobs = $jobs::orderBy('id')->get();
        return view('RequestHiring.viewHiringRequestList',compact('jobs'));
    }

    public function approveRequestHiring()
    {
        
    }
    
    
}
