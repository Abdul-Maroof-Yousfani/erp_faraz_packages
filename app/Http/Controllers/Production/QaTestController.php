<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Production\QaTest;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class QaTestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('mysql2')->table('qa_tests')->where('status', 1);

            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->orderBy('id', 'desc')->get();

            return view('Production.QaTest.ajax.listQaTestAjax', compact('data'));
        }

        return view('Production.QaTest.listQaTest');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Production.QaTest.createQaTest' );
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

        
    
            $data = QaTest::create(
                [
                    'name' => $request->name, 
                    'status' => 1, 
                    'qc_type' => $request->qc_type, 
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
        $QaTest = QaTest::where('id', $id)->where('status', 1)->first();

        if (!$QaTest) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('Production.QaTest.updateQaTest', compact('QaTest'));
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
            'name' => 'required',
            'qc_type' => 'required'
               ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $QaTest = QaTest::find($id);

            if (!$QaTest) {
                return redirect()->back()->withErrors('Record not found')->withInput();
            }

            $QaTest->update([
                'name' => $request->name,
                'qc_type' => $request->qc_type,
                'status' => 1,
                'username' => Auth()->user()->name,
            ]);

            return redirect('Production/QaTest/')->with('success', 'Record updated successfully');
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
    public function deleteQaTest($id)
    {
        QaTest::find($id)->update([
            'status' => 0
        ]);
    }
}
