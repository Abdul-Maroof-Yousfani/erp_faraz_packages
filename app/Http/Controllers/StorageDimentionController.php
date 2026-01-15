<?php

namespace App\Http\Controllers;

use App\Models\StorageDimention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class StorageDimentionController extends Controller
{
    public $path ;

    public function __construct()
    {
        $this->path ='selling.storage.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listStorageDemention()
    {
        $responses = StorageDimention::where('status',1)->get();
        return  view($this->path.'listStorageDemention',compact('responses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStorageDiemention()
    {
        return view($this->path.'createStorageDiemention');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStorageDiemention(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $SalesPool =   new StorageDimention;
                $SalesPool->name= $request->sales_pool;
                $SalesPool->save();

        DB::Connection('mysql2')->commit();
          return redirect()->route('createStorageDiemention')->with('dataInsert','Sale Pool  Inserted');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('createStorageDiemention')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StorageDimention  $storageDimention
     * @return \Illuminate\Http\Response
     */
    public function show(StorageDimention $storageDimention)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StorageDimention  $storageDimention
     * @return \Illuminate\Http\Response
     */
    public function edit(StorageDimention $storageDimention)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StorageDimention  $storageDimention
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StorageDimention $storageDimention)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StorageDimention  $storageDimention
     * @return \Illuminate\Http\Response
     */
    public function destroy(StorageDimention $storageDimention)
    {
        //
    }
}
