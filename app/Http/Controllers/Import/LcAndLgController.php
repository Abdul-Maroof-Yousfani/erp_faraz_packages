<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Import\LcAndLg;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LcAndLgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection('mysql2')->table('lc_and_lg as l')
                ->join('accounts as a', 'a.id', '=', 'l.acc_id')
                ->where('a.status', 1)
                ->where('l.status', 1)
                ->select('l.id', 'a.name', 'l.limit', 'l.type');
            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->orderBy('l.id', 'desc')->get();

            return view('Import.LcAndLg.ajax.listLcAndLgAjax', compact('data'));
        }

        return view('Import.LcAndLg.listLcAndLg');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account = CommonHelper::get_all_account_operat_with_unique_code('1-1');
        return view('Import.LcAndLg.createLcAndLg', compact('account'));
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
            'type' => 'required',
            'acc_id' => 'required',
            'limit' => 'required',
        ]);
        try {

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // Create a new product instance
            $LcAndLg = new LcAndLg([
                'type' => $request->input('type'),
                'acc_id' => $request->input('acc_id'),
                'limit' => $request->input('limit'),
                'status' => 1,
                'username' => Auth()->user()->name,

            ]);

            // Save the LcAndLg to the database
            $LcAndLg->save();

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

        $LcAndLg = DB::connection('mysql2')->table('lc_and_lg as l')
            ->join('accounts as a', 'a.id', '=', 'l.acc_id')
            ->where('a.status', 1)
            ->where('l.id', $id)
            ->select('l.id', 'a.name', 'l.limit', 'l.type')->first();

        if (!$LcAndLg) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('Import.LcAndLg.viewLcAndLg', compact('LcAndLg'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $LcAndLg = LcAndLg::where('id', $id)->where('status', 1)->first();
        $account = CommonHelper::get_all_account_operat_with_unique_code('1-1');

        if (!$LcAndLg) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('Import.LcAndLg.updateLcAndLg', compact('LcAndLg', 'account'));
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
            'type' => 'required',
            'acc_id' => 'required',
            'limit' => 'required',
        ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $LcAndLg = LcAndLg::where('id', $id)->where('status', 1)->firstOrFail();


            // Update the fields if the record exists
            if ($LcAndLg) {


                $LcAndLg->update([
                    'type' => $request->input('type'),
                    'acc_id' => $request->input('acc_id'),
                    'limit' => $request->input('limit'),
                    'username' => Auth()->user()->name,
                ]);
                return redirect('import/LcAndLg/')->with('success', 'Record updated successfully');

            } else {
                return redirect()->back()->withErrors('Record not found.')->withInput();
            }
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

    public function deleteLcAndLg($id)
    {

        $LcAndLg = LcAndLg::where('id', $id)->where('status', 1)->first();

        // Check if the record is found
        if ($LcAndLg) {
            // Update the status to 0
            $LcAndLg->update(['status' => 0]);

            // Return JSON response with status true
            return response()->json(['status' => true]);
        } else {
            // If not found, return JSON response with status false
            return response()->json(['status' => false, 'message' => 'LcAndLg not found']);
        }
    }



    public function reportLcLg(Request $request)
    {
        if ($request->ajax()) {


            $data = DB::connection('mysql2')->table('lc_and_lg as l')
                ->join('accounts as a', 'a.id', '=', 'l.acc_id')
                ->where('a.status', 1)
                ->where('l.status', 1)
                ->select(
                    'l.id',
                    'l.type',
                    'a.name',
                    'l.limit',
                    DB::raw('l.limit_utilize as limit_utilized'),
                    DB::raw('l.limit - l.limit_utilize as un_utilized'),
                    // DB::raw('CONCAT(ROUND((l.limit - l.limit_utilize / (l.limit)) * 100, 2), " %") as remaining_percentage')
                    DB::raw('((l.limit - l.limit_utilize) / l.limit ) * 100 as remaining_percentage')
                );
            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->orderBy('l.id', 'desc')->get();

            return view('Import.LcAndLg.ajax.reportLcAndLgAjax', compact('data'));
        }

        return view('Import.LcAndLg.reportLcAndLg');
    }
}
