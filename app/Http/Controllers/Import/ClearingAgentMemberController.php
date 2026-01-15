<?php

namespace App\Http\Controllers\import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Import\ClearingAgentMember;
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

class ClearingAgentMemberController extends Controller
{
    public function agent(Request $request){
        $data['claering_agent'] = ClearingAgentMember::where('status' , 1)->get();
        return view('Import.ClearingAgent.listAgent' , $data);
    }

    public function create_agent()
    {
        // $Currency = Currency::where('status',1)->select('id','name')->get();
        return view('Import.ClearingAgent.createAgent');
    }

    public function edit_agent($id)
    {
        $claering_agent = ClearingAgentMember::where('id', $id)->where('status', 1)->first();

        if (!$claering_agent) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }
        return view('Import.ClearingAgent.editAgent', compact('claering_agent'));
    }

    
    public function store_update_agent(Request $request , ClearingAgentMember $agent)
    {
        // $Currency = Currency::where('status',1)->select('id','name')->get();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // dd($agent);
            $agent->agent_name = $request->name;
            $agent->save();

        } catch (\Throwable $th) {
           return redirect()->back()->withErrors('Something went wrong')->withInput();
        }
        return redirect('import/Agent')->with('success', 'Record inserted successfully');
        // return view('Import.ClearingAgent.createAgent');
    }
}
