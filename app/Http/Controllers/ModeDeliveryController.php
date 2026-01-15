<?php

namespace App\Http\Controllers;

use App\Models\ModeDelivery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModeDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listModeDelivery()
    {
        $responses = ModeDelivery::where('status',1)->get();
        return view('modedelivery.listModeDelivery',compact('responses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function craeteModeDelivery()
    {
        return view('modedelivery.craeteModeDelivery');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeModeDelivery(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                $ModeDelivery =   new ModeDelivery;
                $ModeDelivery->name= $request->mode_delivery;
                $ModeDelivery->save();

        DB::Connection('mysql2')->commit();
          return redirect()->route('craeteModeDelivery')->with('dataInsert','Sale Delivery  Inserted');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('craeteModeDelivery')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ModeDelivery  $modeDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(ModeDelivery $modeDelivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ModeDelivery  $modeDelivery
     * @return \Illuminate\Http\Response
     */
    public function edit(ModeDelivery $modeDelivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ModeDelivery  $modeDelivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModeDelivery $modeDelivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ModeDelivery  $modeDelivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModeDelivery $modeDelivery)
    {
        //
    }
}
