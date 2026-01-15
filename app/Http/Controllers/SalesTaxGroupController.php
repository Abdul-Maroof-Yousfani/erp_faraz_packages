<?php

namespace App\Http\Controllers;

use App\Models\Gst;
use App\Models\SalesTaxGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesTaxGroupController extends Controller
{
    public $path ;

    public function __construct()
    {
        $this->path ='selling.saletax.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saleTaxGroupList()
    {
        $responses = Gst::where('status',1)->get();
        return  view($this->path.'saleTaxGroupList',compact('responses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saleTaxGroupCreate()
    {
        return view($this->path.'saleTaxGroupCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSaleTaxGroup(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $SalesPool =   new Gst();
                $SalesPool->percent= $request->rate;
                $SalesPool->acc_id= $request->account_id;
                $SalesPool->rate= $request->rate;
                $SalesPool->save();

        DB::Connection('mysql2')->commit();
          return redirect()->route('saleTaxGroupCreate')->with('dataInsert','Sale Pool  Inserted');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('saleTaxGroupCreate')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SalesTaxGroup  $salesTaxGroup
     * @return \Illuminate\Http\Response
     */
    public function show(SalesTaxGroup $salesTaxGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SalesTaxGroup  $salesTaxGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesTaxGroup $salesTaxGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SalesTaxGroup  $salesTaxGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesTaxGroup $salesTaxGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SalesTaxGroup  $salesTaxGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesTaxGroup $salesTaxGroup)
    {
        //
    }
}
