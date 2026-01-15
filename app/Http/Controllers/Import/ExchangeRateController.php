<?php

namespace App\Http\Controllers\import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Import\ExchangeRate;
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
class ExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $exchange_rates = DB::connection('mysql2')->table('exchange_rates AS er')
                ->join('currency AS c', 'c.id', '=', 'er.currency')
                ->select('er.id', 'c.name', 'er.rate', 'er.rate_date', DB::raw('IFNULL(er.to_date, CURRENT_DATE()) AS end_date'))
                ->whereIn('er.created_at', function($query) {
                    $query->select(DB::raw('MAX(created_at)'))
                        ->from('exchange_rates')
                        ->groupBy('currency');
                })
                ->where('c.status', 1)
                ->where('er.status', 1);

            if ($request->rate_date) {
                $exchange_rates = $exchange_rates->where('er.rate_date', '>=', $request->rate_date);
            }
            if ($request->to_date) {
                $exchange_rates = $exchange_rates->where('er.rate_date', '<=', $request->to_date);
            }
            if ($request->currency) {
                $exchange_rates = $exchange_rates->where('c.id', '=', $request->currency);
            }

            $exchange_rates = $exchange_rates->orderBy('er.id', 'desc')->get();
           
            return view('Import.ExchangeRate.ajax.listExchangeRateAjax', compact('exchange_rates'));
        }

        $Currency = Currency::where('status', 1)->select('id', 'name')->get();

        return view('Import.ExchangeRate.listExchangeRate',compact('Currency'));
    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            $exchange_rates = DB::connection('mysql2')->table('exchange_rates AS er')
                ->join('currency AS c', 'c.id', '=', 'er.currency')
                ->select('er.id', 'c.name', 'er.rate', 'er.rate_date', 'er.to_date', DB::raw('IFNULL(er.to_date, CURRENT_DATE()) AS end_date'))
                
                ->where('c.status', 1)
                ->where('er.status', 1);

            if ($request->rate_date) {
                $exchange_rates = $exchange_rates->where('er.rate_date', '>=', $request->rate_date);
            }
            if ($request->to_date) {
                $exchange_rates = $exchange_rates->where('er.rate_date', '<=', $request->to_date);
            }
            if ($request->currency) {
                $exchange_rates = $exchange_rates->where('c.id', '=', $request->currency);
            }

            $exchange_rates = $exchange_rates->orderBy('er.id', 'desc')->get(); 
           
            return view('Import.ExchangeRate.ajax.reportExchangeRateAjax', compact('exchange_rates'));
        }
        
        $Currency = Currency::where('status', 1)->select('id', 'name')->get();
        
        return view('Import.ExchangeRate.reportExchangeRate' , compact('Currency'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Currency = Currency::where('status', 1)->where('id','!=',3)->select('id', 'name')->get();

        return view('Import.ExchangeRate.createExchangeRate' , compact('Currency'));
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
            'currency' => 'required|integer',
            'rate_date' => 'required|date',
            'rate' => 'required|numeric|min:0',
        ]);
    
        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $exchangeRate = ExchangeRate::where('currency', $request->currency)
            ->where('status', 1)
            ->whereNull('to_date')
            ->first();
        
            if($exchangeRate)
            {
               
                if($exchangeRate->rate_date < $request->rate_date )
                {
                    $rateDate = Carbon::parse($request->rate_date)->subDays(1)->toDateString();
                   
                    ExchangeRate::where('currency', $request->currency)->where('status', 1)
                    ->whereNull('to_date')
                    ->update([
                        'to_date' => $rateDate
                    ]);
                }
                if($exchangeRate->rate_date == $request->rate_date )
                {
                   
                    ExchangeRate::where('currency', $request->currency)->where('status', 1)
                    ->whereNull('to_date')
                    ->update([
                        'to_date' => $request->rate_date
                    ]);
                }


            }
    
            $data = ExchangeRate::create(
                [
                    'currency' => $request->currency, 
                    'rate_date' => $request->rate_date, 
                    'rate' => $request->rate, 
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
        $exchangeRate = ExchangeRate::where('id', $id)->where('status', 1)->first();

        if (!$exchangeRate) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        $Currency = Currency::where('status', 1)->where('id','!=',3)->select('id', 'name')->get();
        return view('Import.ExchangeRate.updateExchangeRate', compact('Currency', 'exchangeRate'));
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
            'currency' => 'required|integer',
            'rate_date' => 'required|date',
            'rate' => 'required|numeric|min:0',
        ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $exchangeRate = ExchangeRate::find($id);

            if (!$exchangeRate) {
                return redirect()->back()->withErrors('Record not found')->withInput();
            }

            $exchangeRate->update([
                'currency' => $request->currency,
                'rate_date' => $request->rate_date,
                'rate' => $request->rate,
                'status' => 1,
                'username' => Auth()->user()->name,
            ]);

            return redirect('import/ExchangeRate/')->with('success', 'Record updated successfully');
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
    public function deleteExchangeRate($id)
    {
        ExchangeRate::find($id)->update([
            'status' => 0
        ]);
    }


    public function currency()
    {
        $data['currency'] = Currency::where('status' , 1)->get();
        return view('Import.Currency.listCurrency' , $data);
    }
    public function create_currency()
    {
        return view('Import.Currency.createCurrency');
    }

    public function edit_currency($id)
    {
        $data['currency'] = Currency::findOrFail($id);
        return view('Import.Currency.editCurrency' , $data);
    }

    public function store_update_currency(Request $request , Currency $currency){

        $msg = $currency->id ? 'Updated successfully' : 'added successfully';
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'rate' => 'required|numeric|min:0',
        ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            

            $currency->name = $request->name;
            // $currency->rate = $request->rate;
            $currency->status = 1;
            $currency->username = Auth()->user()->name;
            // $currency->date = date('Y-m-d');
            // $currency->date_stamp = date('Y-m-d H:i:s');
            $currency->save();
            

            return redirect('import/Currency/')->with('success', $msg);
        } catch (QueryException $e) {
            // Log or handle the exception as needed
            return redirect()->back()->withErrors('Error updating record. Please try again.')->withInput();
        }
    }

}
