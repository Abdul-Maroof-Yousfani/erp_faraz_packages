<?php

namespace App\Http\Controllers;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use Redirect;

class ClientCompaniesController extends Controller
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
    public function toDayActivity()
    {
		return view('dClient.companies.toDayActivity');
    }

    public function addCompanyDetail(){
        $companiesSection = Input::get('companiesSection');
        foreach($companiesSection as $row){
            $company_name = Input::get('company_name_'.$row.'');
            $company_address = Input::get('company_address_'.$row.'');
            $company_contact_no = Input::get('company_contact_no_'.$row.'');
            $data1['name'] = $company_name;
            $data1['address'] = $company_address;
            $data1['contact_no'] = $company_contact_no;
            $data1['username']        = Auth::user()->name;
            $data1['date']            = date("Y-m-d");
            $data1['time']            = date("H:i:s");

            DB::table('company')->insert($data1);    
        }
        return Redirect::to('companies/c');
    }
}
