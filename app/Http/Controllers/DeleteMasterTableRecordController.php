<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Config;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session as FacadesSession;
use Redirect;


class DeleteMasterTableRecordController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteMasterTableReceord()
    {
		$id = $_GET['id'];
       
        $tableName = $_GET['tableName'];
        DB::table($tableName)->where('id', $id)->update(['status' => '0']);
        FacadesSession::flash('dataDelete','successfully delete.');
    }

   
}
