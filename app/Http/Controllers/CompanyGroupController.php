<?php

namespace App\Http\Controllers;

use App\Models\CompanyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CompanyGroupController extends Controller
{

    public $path ;

    public function __construct()
    {
        $this->path ='company.group.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function companyGroupList()
    {
        $company_list = CompanyGroup::where('status',1)->get();
        return  view($this->path.'companyGroupList',compact('company_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCompanyGroup()
    {
        return view($this->path.'createCompanyGroup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function companyGroupStore(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $location =   new CompanyGroup;
                $location->name= $request->location_name;
                $location->save();
        DB::Connection('mysql2')->commit();
          return redirect()->route('companyGroupList')->with('dataInsert','Group Inserted');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('companyGroupList')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CompanyGroup  $companyGroup
     * @return \Illuminate\Http\Response
     */
    public function editCompanyGroup(Request $request)
    {
        $locations = CompanyGroup::where('status',1)->where('id',$request->id)->first();
        return view($this->path.'editCompanyGroup',compact('locations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompanyGroup  $companyGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyGroup $companyGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompanyGroup  $companyGroup
     * @return \Illuminate\Http\Response
     */
    public function updateCompanyGroup(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $location =    CompanyGroup::find($request->id);
                $location->name= $request->location_name;
                $location->save();
        DB::Connection('mysql2')->commit();
          return redirect()->route('companyGroupList')->with('dataInsert','Group Inserted');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('companyGroupList')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompanyGroup  $companyGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyGroup $companyGroup)
    {
        //
    }
}
