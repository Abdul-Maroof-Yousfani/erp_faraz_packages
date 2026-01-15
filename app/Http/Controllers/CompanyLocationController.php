<?php

namespace App\Http\Controllers;

use App\Models\CompanyLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CompanyLocationController extends Controller
{
    public $path ;

    public function __construct()
    {
        $this->path ='company.location.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function companyLocationList()
    {
        $company_list = CompanyLocation::where('status',1)->get();
      return  view($this->path.'companyLocationList',compact('company_list'));
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCompanyLocation()
    {
       
        return view($this->path.'createCompanyLocation');
    }

    public function companyLocationStore(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $location =   new CompanyLocation;
                $location->name= $request->location_name;
                $location->save();
        DB::Connection('mysql2')->commit();
          return redirect()->route('companyLocationList')->with('dataInsert','Location Inserted');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('companyLocationList')->with('error', $e->getMessage());
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CompanyLocation  $companyLocation
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyLocation $companyLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompanyLocation  $companyLocation
     * @return \Illuminate\Http\Response
     */
    public function editCompanyLocation(Request $request)

    {   
        $locations = CompanyLocation::where('status',1)->where('id',$request->id)->first();

        return view($this->path.'editCompanyLocation',compact('locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompanyLocation  $companyLocation
     * @return \Illuminate\Http\Response
     */
    public function updateCompanyLocation(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $location =   CompanyLocation::find($request->id);
                $location->name= $request->location_name;
                $location->save();
        DB::Connection('mysql2')->commit();
          return redirect()->route('companyLocationList')->with('dataInsert','Location Updated');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('companyLocationList')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompanyLocation  $companyLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyLocation $companyLocation)
    {
        //
    }
}
