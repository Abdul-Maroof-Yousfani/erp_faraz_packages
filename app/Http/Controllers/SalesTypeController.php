<?php

namespace App\Http\Controllers;

use App\Models\SalesType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesTypeController extends Controller
{
    public $path ;

    public function __construct()
    {
        $this->path ='selling.salestype.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function salesTypeList()
    {
        $responses = SalesType::where('status',1)->get();
        return  view($this->path.'salesTypeList',compact('responses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function salesTypeCreate()
    {
        return view($this->path.'salesTypeCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salesTypeStore(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $SalesPool =   new SalesType;
                $SalesPool->name= $request->sales_pool;
                $SalesPool->save();

        DB::Connection('mysql2')->commit();
          return redirect()->route('salesTypeCreate')->with('dataInsert','Sale Pool  Inserted');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('salesTypeCreate')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SalesType  $salesType
     * @return \Illuminate\Http\Response
     */
    public function show(SalesType $salesType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SalesType  $salesType
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesType $salesType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SalesType  $salesType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesType $salesType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SalesType  $salesType
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesType $salesType)
    {
        //
    }
}
