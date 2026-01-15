<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\BankDetail;
use DB;

class BankController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $datas = BankDetail::where('status', 1);
        if ($request->ajax()) {
            $data = $datas;
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){                                                                                                        
                    return view('Finance.Bank.listAction', compact('data'));
                })                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Finance.Bank.list', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = DB::Connection('mysql2')->table('accounts')
            ->where('status',1)
            ->where('level1',1)
            ->select('id','code','name','type')
            ->orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();
        return view('Finance.Bank.add',compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parent_code = $request->account_id;
        $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \''.$parent_code.'\' and status=1')->id;
		if($max_id == ''){
			$code = $parent_code.'-1';
		}else{
			$max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \''.$max_id.'\' and status=1')->code;
			$max_code2;
			$max = explode('-',$max_code2);
			$code = $parent_code.'-'.(end($max)+1);
		}

		$level_array = explode('-',$code);
		$counter = 1;
		foreach($level_array as $level):
			$data1['level'.$counter] = $level;
			$counter++;
		endforeach;
		$data1['code'] = $code;
		$data1['name'] = $request->bank_name.' - ('.$request->account_no.')';
		$data1['parent_code'] = $parent_code;
		$data1['username'] 		 	= auth()->user()->username;
        $data1['date']     		  = date("Y-m-d");
		$data1['time']     		  = date("H:i:s");
		$data1['action']     		  = 'create';
		$data1['operational']		= 1;
        $data1['type']		= 4;


		$acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);
        
        BankDetail::create([
            'acc_id' => $acc_id,
            'bank_name' => $request->bank_name,
            'account_title' => $request->account_title,
            'account_no' => $request->account_no,
            'iban_no' => $request->iban_no,
            'swift_code' => $request->swift_code,
            'bank_address' => $request->bank_address,
            'max_funded_facility' => $request->max_funded_facility,
            'max_non_funded_facility' => $request->max_non_funded_facility,
            'username' => auth()->user()->username,
            'status' => '1',
            'date' => date('Y-m-d')
        ]);
        return redirect()->back()->with('message', 'Data saved successfully!');
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
        $data = BankDetail::findOrFail($id);
        $accounts = DB::Connection('mysql2')->table('accounts')
        ->where('status',1)
        ->where('level1',1)
        ->select('id','code','name','type')
        ->orderBy('level1', 'ASC')
        ->orderBy('level2', 'ASC')
        ->orderBy('level3', 'ASC')
        ->orderBy('level4', 'ASC')
        ->orderBy('level5', 'ASC')
        ->orderBy('level6', 'ASC')
        ->orderBy('level7', 'ASC')
        ->get();

        return view('Finance.Bank.edit', compact('data', 'accounts'));
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
        $parent_code = $request->account_id;
        $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \''.$parent_code.'\' and status=1')->id;
		if($max_id == ''){
			$code = $parent_code.'-1';
		}else{
			$max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \''.$max_id.'\' and status=1')->code;
			$max_code2;
			$max = explode('-',$max_code2);
			$code = $parent_code.'-'.(end($max)+1);
		}

		$level_array = explode('-',$code);
		$counter = 1;
		foreach($level_array as $level):
			$data1['level'.$counter] = $level;
			$counter++;
		endforeach;
		$data1['code'] = $code;
		$data1['name'] = $request->bank_name.' - ('.$request->account_no.')';
		$data1['parent_code'] = $parent_code;
		$data1['username'] 		 	= auth()->user()->username;
        $data1['date']     		  = date("Y-m-d");
		$data1['time']     		  = date("H:i:s");
		$data1['action']     		  = 'create';
		$data1['operational']		= 1;
        $data1['type']		= 4;


		$acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);

        $bankDetail = BankDetail::where('id', $id)->first();

        if ($bankDetail) {
            // Update the fields
            $bankDetail->update([
                'acc_id' => $acc_id,
                'bank_name' => $request->bank_name,
                'account_title' => $request->account_title,
                'account_no' => $request->account_no,
                'iban_no' => $request->iban_no,
                'swift_code' => $request->swift_code,
                'bank_address' => $request->bank_address,
                'max_funded_facility' => $request->max_funded_facility,
                'max_non_funded_facility' => $request->max_non_funded_facility,
                'username' => auth()->user()->username,
                'status' => '1',
                'date' => date('Y-m-d'),
            ]);
        }

        return redirect()->back()->with('message', 'Data update successfully!');
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

    public function viewBankListDetail(Request $request){
        $getBankListDetail = BankDetail::where('id',$request->id)->first();
        return view('Finance.Bank.viewBankListDetail',compact('getBankListDetail'));
    }

    public function viewBankEditForm($id)
    {
        $data = BankDetail::findOrFail($id);
        return view('Finance.Bank.edit', compact('data'));
    }
}
