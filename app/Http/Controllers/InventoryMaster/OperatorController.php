<?php

namespace App\Http\Controllers\InventoryMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\InventoryMaster\Operator;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('mysql2')->table('operators')->where('status', 1);

            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->orderBy('id', 'desc')->get();

            return view('InventoryMaster.Operator.ajax.listOperatorAjax', compact('data'));
        }

        return view('InventoryMaster.Operator.listOperator');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('InventoryMaster.Operator.createOperator' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
    
        try {
        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

        
    
            $data = Operator::create(
                [
                    'name' => $request->name, 
                    'status' => 1, 
                    'username' => Auth()->user()->name,
                ]
            );            
    
            return redirect()->back()->with('success', 'Record inserted successfully');
        } catch (QueryException $e) {
            // Log or handle the exception as needed
            return redirect()->back()->withErrors('Error inserting record. Please try again.')->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Operator = Operator::where('id', $id)->where('status', 1)->first();

        if (!$Operator) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('InventoryMaster.Operator.updateOperator', compact('Operator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
               ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $Operator = Operator::find($id);

            if (!$Operator) {
                return redirect()->back()->withErrors('Record not found')->withInput();
            }

            $Operator->update([
                'name' => $request->name,
                'status' => 1,
                'username' => Auth()->user()->name,
            ]);

            return redirect('InventoryMaster/Operator/')->with('success', 'Record updated successfully');
        } catch (QueryException $e) {
            // Log or handle the exception as needed
            return redirect()->back()->withErrors('Error updating record. Please try again.')->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function deleteOperator($id)
    {
        Operator::find($id)->update([
            'status' => 0
        ]);
    }
}
