<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\BankDetail;
use App\Models\BankFacility;
use DB;

class BankFacilityController extends Controller
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
        $datas = DB::Connection('mysql2')->table('bank_facility as bf')
            ->join('bank_detail as bd','bf.bank_id','=','bd.id')
            ->select('bd.bank_name','bf.id','bf.facility_name','bf.from_days','bf.to_days','bf.loan_amount','bf.interest_rate')
            ->get();
        if ($request->ajax()) {
            $data = $datas;
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){                                                                                                        
                    return view('Finance.BankFacility.listAction', compact('data'));
                })                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Finance.BankFacility.list',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bankList = BankDetail::where('status','1')->get();
        return view('Finance.BankFacility.add',compact('bankList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        BankFacility::create([
            'bank_id' => $request->bank_id,
            'calculate_days_type' => $request->calculate_days_type,
            'bank_type_id' => $request->bank_type_id,
            'facility_name' => $request->facility_name,
            'from_days' => $request->from_days,
            'to_days' => $request->to_days,
            'loan_amount' => $request->loan_amount,
            'interest_rate' => $request->interest_rate,
            'created_by' => auth()->user()->username,
            'status' => '1',
            'created_at' => date('Y-m-d')
        ]);
        return redirect()->back()->with('message', 'Data saved successfully!');
    }

    public function loadBorrowedTypeList(Request $request){
        $borrowed_type_id = $request->borrowed_type_id;
        if($borrowed_type_id == 2){
            $tableName = 'supplier';
        }else{
            $tableName = 'customers';
        }
        $getData = DB::connection('mysql2')->table($tableName)->get();
        // dd($getData);
        foreach($getData as $gdRow){
    ?>
            <option value="<?php echo $gdRow->id?>"><?php echo $gdRow->name?></option>
    <?php
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
        $data = DB::Connection('mysql2')->table('bank_facility as bf')
            ->join('bank_detail as bd','bf.bank_id','=','bd.id')
            ->select('bf.bank_id', 'bd.bank_name','bf.id','bf.facility_name','bf.from_days','bf.to_days','bf.loan_amount','bf.interest_rate', 'bf.calculate_days_type', 'bf.bank_type_id')
            ->where('bf.id', $id)
            ->first();

        $bankList = BankDetail::where('status','1')->get();

            return view('Finance.BankFacility.edit',compact('data', 'bankList'));
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
        $bankFacility = BankFacility::where('id', $id)->first();

        if ($bankFacility) {
            // Update the fields
            $bankFacility->update([
                'bank_id' => $request->bank_id,
                'calculate_days_type' => $request->calculate_days_type,
                'bank_type_id' => $request->bank_type_id,
                'facility_name' => $request->facility_name,
                'from_days' => $request->from_days,
                'to_days' => $request->to_days,
                'loan_amount' => $request->loan_amount,
                'interest_rate' => $request->interest_rate,
                'created_by' => auth()->user()->username,
                'status' => '1',
                'created_at' => date('Y-m-d')
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

    public function loadBankFacililtyDetailForm(Request $request){
        $bankFacilityList = BankFacility::where('status','1')->where('bank_id',$request->bank_id)->get();
        return view('Finance.BankFacility.loadBankFacililtyDetailForm',compact('bankFacilityList'));
    }

    public function viewBankFacilityListDetail(Request $request){
        $getBankFacilityListDetail = DB::Connection('mysql2')->table('bank_facility as bf')
            ->join('bank_detail as bd','bf.bank_id','=','bd.id')
            ->select('bd.bank_name','bf.id','bf.facility_name','bf.from_days','bf.to_days','bf.loan_amount','bf.interest_rate')
            ->where('bf.id',$request->id)
            ->first();
        return view('Finance.BankFacility.viewBankFacilityListDetail',compact('getBankFacilityListDetail'));
    }
}
