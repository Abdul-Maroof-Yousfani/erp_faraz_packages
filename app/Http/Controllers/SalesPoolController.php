<?php

namespace App\Http\Controllers;

use App\Models\SalesPool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesPoolController extends Controller
{

    public $path ;

    public function __construct()
    {
        $this->path ='selling.salespool.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function salesPoolList(Request $request)
    {
        $responses = SalesPool::where('status',1)->get();
        return  view($this->path.'salesPoolList',compact('responses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function salesPoolCreate()
    {
        return view($this->path.'salesPoolCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salesPoolStore(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $SalesPool =   new SalesPool;
                $SalesPool->name= $request->sales_pool;
                $SalesPool->save();

        DB::Connection('mysql2')->commit();
          return redirect()->route('salesPoolCreate')->with('dataInsert','Sale Pool  Inserted');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('salesPoolCreate')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SalesPool  $salesPool
     * @return \Illuminate\Http\Response
     */
    public function show(SalesPool $salesPool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SalesPool  $salesPool
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesPool $salesPool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SalesPool  $salesPool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesPool $salesPool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SalesPool  $salesPool
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesPool $salesPool)
    {
        //
    }
}
