<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Finance\CashFlowHead;
use App\Models\Finance\CashFlowSubHead;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CashFlowHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('mysql2')->table('cash_flow_heads')->where('status', 1);

            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->orderBy('id', 'desc')->get();

            return view('Finance.CashFlowHead.ajax.listCashFlowHeadAjax', compact('data'));
        }

        return view('Finance.CashFlowHead.listCashFlowHead');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Finance.CashFlowHead.createCashFlowHead' );
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
            'debit_credit' => 'required',
        ]);
    // dd($request);
        try {
        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

        
    
            $data = CashFlowHead::create(
                [
                    'name' => $request->name, 
                    'debit_credit' => $request->debit_credit,
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
        $CashFlowHead = CashFlowHead::where('id', $id)->where('status', 1)->first();

        if (!$CashFlowHead) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('Finance.CashFlowHead.updateCashFlowHead', compact('CashFlowHead'));
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
            'debit_credit' => 'required',
               ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $CashFlowHead = CashFlowHead::find($id);

            if (!$CashFlowHead) {
                return redirect()->back()->withErrors('Record not found')->withInput();
            }

            $CashFlowHead->update([
                'name' => $request->name,
                'debit_credit' => $request->debit_credit,
                'status' => 1,
                'username' => Auth()->user()->name,
            ]);

            return redirect('Finance/CashFlowHead/')->with('success', 'Record updated successfully');
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
    public function deleteCashFlowHead($id)
    {
        CashFlowHead::find($id)->update([
            'status' => 0
        ]);
    }

    public function CashFlowSubHead(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('mysql2')->table('cash_flow_sub_heads as csh')
            ->join('cash_flow_heads as ch' , 'ch.id' , 'csh.cash_flow_head_id')
            ->select('csh.*' , 'ch.name as head_name')
            ->where('csh.status', 1);

            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->orderBy('csh.id', 'desc')->get();

            return view('Finance.CashFlowHead.ajax.listCashFlowSubHeadAjax', compact('data'));
        }

        return view('Finance.CashFlowHead.listCashFlowSubHead');
    }

    public function CashFlowSubHeadcreate()
    {
        $parent_head = DB::connection('mysql2')->table('cash_flow_heads')->where('status', 1)->get();
        return view('Finance.CashFlowHead.createCashFlowSubHead' , compact('parent_head') );
    }

    public function CashFlowSubHeadstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cash_flow_head' => 'required',
        ]);
    // dd($request);
        try {
        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

        
    
            $data = CashFlowSubHead::create(
                [
                    'cash_flow_head_id' => $request->cash_flow_head, 
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
    
    public function CashFlowSubHeadedit($id)
    {
        $CashFlowHead = CashFlowSubHead::where('id', $id)->where('status', 1)->first();

        if (!$CashFlowHead) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }
        $parent_head = DB::connection('mysql2')->table('cash_flow_heads')->where('status', 1)->get();
        return view('Finance.CashFlowHead.updateCashFlowSubHead', compact('CashFlowHead' , 'parent_head'));
    }

    public function CashFlowSubHeadupdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cash_flow_head' => 'required',
            'name' => 'required'
               ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $CashFlowHead = CashFlowSubHead::find($id);

            if (!$CashFlowHead) {
                return redirect()->back()->withErrors('Record not found')->withInput();
            }

            $CashFlowHead->update([
                'cash_flow_head_id' => $request->cash_flow_head, 
                'name' => $request->name,
                'status' => 1,
                'username' => Auth()->user()->name,
            ]);

            return redirect('Finance/CashFlowSubHead/')->with('success', 'Record updated successfully');
        } catch (QueryException $e) {
            // Log or handle the exception as needed
            return redirect()->back()->withErrors('Error updating record. Please try again.')->withInput();
        }
    }

    public function deleteCashFlowSubHead($id)
    {
        // dd($id);
        CashFlowSubHead::find($id)->update([
            'status' => 0
        ]);
    }
}
