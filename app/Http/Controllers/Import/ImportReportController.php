<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Import\ClearingAgentMember;

class ImportReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function underClearance(Request $request)
    {
        if ($request->ajax()) {
            return view('Import/ImportReport/ajax/underClearance' , compact('request'));
        }   
        return view('Import/ImportReport/underClearance');
    }
    
    public function maturitySheet(Request $request)
    {
        if ($request->ajax()) {
            
            return view('Import/ImportReport/ajax/maturitySheet' , compact('request'));
        }
        return view('Import/ImportReport/maturitySheet');
    }
    
    public function bankWisePADSummary(Request $request)
    {
        if ($request->ajax()) {
            
            return view('Import/ImportReport/ajax/bankWisePADSummary' , compact('request'));
        }
        return view('Import/ImportReport/bankWisePADSummary');
    }
    
    public function lcandLgBankLimit(Request $request)
    {
        if ($request->ajax()) {
            
            return view('Import/ImportReport/ajax/lcandLgBankLimit' , compact('request'));
        }
        return view('Import/ImportReport/lcandLgBankLimit');
    }
    
    public function exchangeAndGainLossReport(Request $request)
    {
        if ($request->ajax()) {
            
            return view('Import/ImportReport/ajax/exchangeAndGainLossReport' , compact('request'));
        }
        return view('Import/ImportReport/exchangeAndGainLossReport');
    }

    public function clearingAgent(Request $request)
    {
        if ($request->ajax()) {
            
            return view('Import/ImportReport/ajax/clearingAgentReport' , compact('request'));
        }
        return view('Import/ImportReport/clearingAgentReport');
    }
    public function InsuranceDetail(Request $request)
    {
        if ($request->ajax()) {
            
            return view('Import/ImportReport/ajax/InsuranceDetailReport' , compact('request'));
        }
        return view('Import/ImportReport/InsuranceDetailReport');
    }
    
    public function securityDepositReport(Request $request)
    {
        $clearing_agent = ClearingAgentMember::where('status' , 1)->get();
        if ($request->ajax()) {
            
            return view('Import/ImportReport/ajax/securityDepositReport' , compact('request'));
        }
        return view('Import/ImportReport/securityDepositReport', compact('clearing_agent'));
    }
    public function rawMaterialDutySheet(Request $request)
    {
        // $clearing_agent = ClearingAgentMember::where('status' , 1)->get();
        if ($request->ajax()) {
            
            return view('Import/ImportReport/ajax/rawMaterialDutySheet' , compact('request'));
        }
        return view('Import/ImportReport/rawMaterialDutySheet');
    }
    
    
}
