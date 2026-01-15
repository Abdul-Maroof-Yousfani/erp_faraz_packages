<?php

namespace App\Http\Controllers\Import;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Import\HsCode;
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


class HsCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $HsCode = HsCode::select('id', 'description', 'hs_code', 'utilise_under_benefit_of', 'applicable_sro_benefit')
                ->where('status', 1);

            // if ($request->rate_date) {
            //     $HsCode = $HsCode->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $HsCode = $HsCode->where('er.rate_date', '<=', $request->to_date);
            // }

            $HsCode = $HsCode->orderBy('id', 'desc')->get();

            return view('Import.HsCode.ajax.listHsCodeAjax', compact('HsCode'));
        }

        return view('Import.HsCode.listHsCode');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Import.HsCode.createHsCode');
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
            'hs_code' => 'required',
            'description' => 'required',
            'utilise_under_benefit_of' => 'required',
            'applicable_sro_benefit' => 'required',
        ]);

        try {

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // Create a new product instance
            $HsCode = new HsCode([
                'hs_code' => $request->input('hs_code'),
                'description' => $request->input('description'),
                'custom_duty' => $request->input('custom_duty', 0),
                'regulatory_duty' => $request->input('regulatory_duty', 0),
                'federal_excise_duty' => $request->input('federal_excise_duty', 0),
                'additional_custom_duty' => $request->input('additional_custom_duty', 0),
                'sales_tax' => $request->input('sales_tax', 0),
                'additional_sales_tax' => $request->input('additional_sales_tax', 0),
                'income_tax' => $request->input('income_tax', 0),
                'clearing_expense' => $request->input('clearing_expense', 0),
                'total_duty_without_taxes' => $request->input('total_duty_without_taxes', 0),
                'total_duty_with_taxes' => $request->input('total_duty_with_taxes', 0),
                'utilise_under_benefit_of' => $request->input('utilise_under_benefit_of'),
                'applicable_sro_benefit' => $request->input('applicable_sro_benefit'),
                'status' => 1,
                'username' => Auth()->user()->name,

            ]);

            // Save the HsCode to the database
            $HsCode->save();

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
        $HsCode = HsCode::where('id', $id)->where('status', 1)->first();

        if (!$HsCode) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('Import.HsCode.viewHsCode', compact('HsCode'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $HsCode = HsCode::where('id', $id)->where('status', 1)->first();

        if (!$HsCode) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('Import.HsCode.updateHsCode', compact('HsCode'));
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
            'hs_code' => 'required',
            'description' => 'required',
        ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Find the HsCode instance by ID and check if it exists
            // $HsCode = HsCode::findOrFail($id)->where('status', 1)->first();
            $HsCode = HsCode::where('id', $id)->where('status', 1)->firstOrFail();


            // Update the fields if the record exists
            if ($HsCode) {


                $HsCode->update([
                    'hs_code' => $request->input('hs_code'),
                    'description' => $request->input('description'),
                    'custom_duty' => $request->input('custom_duty', 0),
                    'regulatory_duty' => $request->input('regulatory_duty', 0),
                    'federal_excise_duty' => $request->input('federal_excise_duty', 0),
                    'additional_custom_duty' => $request->input('additional_custom_duty', 0),
                    'sales_tax' => $request->input('sales_tax', 0),
                    'additional_sales_tax' => $request->input('additional_sales_tax', 0),
                    'income_tax' => $request->input('income_tax', 0),
                    'clearing_expense' => $request->input('clearing_expense', 0),
                    'total_duty_without_taxes' => $request->input('total_duty_without_taxes', 0),
                    'total_duty_with_taxes' => $request->input('total_duty_with_taxes', 0),
                    'utilise_under_benefit_of' => $request->input('utilise_under_benefit_of'),
                    'applicable_sro_benefit' => $request->input('applicable_sro_benefit'),
                    'username' => Auth()->user()->name,
                ]);
                return redirect('import/HsCode/')->with('success', 'Record updated successfully');

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

    public function deleteHsCode($id)
    {
        HsCode::find($id)->update([
            'status' => 0
        ]);
    }
}
