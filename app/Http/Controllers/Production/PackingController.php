<?php

namespace App\Http\Controllers\Production;

use App\Helpers\ProductionHelper;
use App\Models\Subitem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sales_Order;

use App\Models\Production\Packing;
use App\Models\Production\PackingData;
use App\Models\MachineProccess;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Transactions;

class PackingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('mysql2')->table('packings')->where('status', 1)->orderBy('id', 'desc')->get();

            return view('Production.Packing.ajax.listPackingAjax', compact('data'));
        }

        return view('Production.Packing.listPacking');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $general_production_plan = DB::connection('mysql2')->table('production_plane as pp')
            ->join('machine_proccesses as mp', 'pp.id', '=', 'mp.production_plane_id')
            ->select('mp.mr_id', 'pp.id as pp_id', 'pp.order_no', 'pp.order_date')
            ->where('mp.status', 1)
            ->where('pp.status', 1)
            ->where('pp.type', 2)
            ->where('mp.finish_good_type', 'complete_finish_good')
            ->get();
        return view('Production.Packing.createPacking', compact( 'general_production_plan'));
    }

    public function createFinalPackingForm()
    {
        $general_production_plan = DB::connection('mysql2')->table('production_plane as pp')
            ->join('machine_proccesses as mp', 'pp.id', '=', 'mp.production_plane_id')
            ->join('production_plane_data as ppd', 'pp.id', '=', 'ppd.master_id')
            ->join('subitem as s', 's.id', '=', 'ppd.finish_goods_id')
            ->select('mp.mr_id', 'pp.id as pp_id', 'pp.order_no', 'pp.order_date', 's.sub_ic as finish_good')
            ->where('mp.status', 1)
            ->where('pp.status', 1)
            ->where('pp.type', 2)
            ->where('mp.proccess_status', 1)
            ->where('mp.finish_good_type', 'complete_finish_good')
            ->groupBy('mp.mr_id','pp.id','pp.order_no','pp.order_date','s.sub_ic')
            ->get();
        return view('Production.Packing.createFinalPackingForm', compact( 'general_production_plan'));
    }

    public function createPackingFormAjax(Request $request)
    {
        $production =  DB::connection('mysql2')->table('production_request')->where('id',$request->pr_id)->first();

        $production_data =  DB::connection('mysql2')->table('production_request_data')
            ->where('id',$request->pr_data_id)
            ->where('status',1)->where('packing_status',1)
            ->first();

        $packing_items = Subitem::where([['status','=', 1],['main_ic_id','=', 12]])->select('id','item_code','sub_ic','pack_size','uom')->get();
        return view('Production.Packing.ajax.createPackingFormAjax', compact( 'production','production_data','packing_items'));
    }

    public function getPurchaseRequestData(Request $request)
    {
        $production_data =  DB::connection('mysql2')->table('production_request_data AS prd')
            ->join('subitem AS s', 's.id','=', 'prd.item_id')
            ->where('prd.master_id',$request->id)
            ->where('prd.status',1)->where('prd.packing_status',1)
            ->select('prd.*','s.id AS item_id','s.item_code','s.sub_ic','s.pack_size','s.uom')
            ->get();

        return $production_data;
    }

    public function store(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        $total_qty = 0;
        try {

            if (empty($request->production_attached)) {
                $production_attached = 0;
                $attached_pp_id = 0;
                $attached_material_requisition_id = 0;
            } else {
                $production_attached = 1;
                $attached_pp_id = $request->attached_pp_id;
                $attached_material_requisition_id = $request->attached_material_requisition_id;
            }
            // dd($request->item_id);
            $packing = new Packing;
            $packing->so_id = $request->so_id ?? 0;
            $packing->pr_id = $request->pr_id;
            $packing->pr_data_id = $request->pr_data_id;
            $packing->material_requisition_id = $request->material_requisition_id ?? 0;
            $packing->production_plan_id = $request->pp_id ?? 0;
            $packing->dc_id = $request->delivery_challan_id ?? 0;

            $packing->customer_name = $request->customer_name ?? '';
            $packing->customer_id = $request->customer_id ?? '';
            $packing->packing_date = $request->packing_date ?? '';
            $packing->deliver_to = $request->deliver_to ?? '';
            $packing->packing_list_no = $request->packing_list_no;
            $packing->item_id = $request->finish_good;
            $packing->item_name = $request->item_name;

            $packing->production_attached = $production_attached;
            $packing->attached_pp_id = $attached_pp_id;
            $packing->attached_material_requisition_id = $attached_material_requisition_id;

            $packing->status = 1;
            $packing->username = Auth::user()->name;
            $packing->save();
            $packing_id = $packing->id;

            $total_qty = 0;
            foreach ($request->packing_item_id as $key => $value) {
                $quantity = $request->quantity[$key] ?? null;
                $number_of_pails = $request->number_of_pails[$key] ?? null;
                $bundle_no = $request->bundle_no[$key] ?? 0; // Default to 0 if not set
            
                // Skip iteration if quantity is missing
                if ($quantity === null) {
                    continue;
                }
            
                $total_qty += $quantity;
            
                $packing_data = new PackingData;
                $packing_data->packing_id = $packing_id;
                $packing_data->primary_packing_item_id = $value;
                $packing_data->qty = $quantity;
                $packing_data->number_of_pails = $number_of_pails;
                $packing_data->machine_proccess_data_id = 0;
                $packing_data->bundle_no = $bundle_no;
                $packing_data->status = 1;
                $packing_data->username = Auth::user()->name;
            
                // Only assign secondary packaging if it exists
                if (!empty($request->secondary_package[$key])) {
                    $packing_data->secondary_packing_item_id = $request->secondary_package[$key];
                    $packing_data->carton_count = $request->carton_count[$key] ?? null;
                }
            
                $packing_data->save();
            }

            DB::connection('mysql2')->table('production_request_data')->where('id', $request->pr_data_id)->update(['packing_status' => 2]);

            $packing->total_qty = $total_qty;
            $packing->save();

            DB::Connection('mysql2')->commit();

            return redirect()->back()->with('success', 'Record inserted successfully');
        } catch (QueryException $e) {
            // Log or handle the exception as needed
            DB::Connection('mysql2')->rollback();

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
        $packing = DB::connection('mysql2')->table('packings as p')
            ->join('subitem as s', 's.id', '=', 'p.item_id')
            ->join('uom as u', 'u.id', '=', 's.uom')
            ->leftJoin('production_request_data as prd', 'prd.id', '=', 'p.pr_data_id')
            ->where('p.id', $id)
            ->select('s.sub_ic', 's.item_code', 'p.packing_list_no', 'p.customer_name', 'p.deliver_to', 'p.packing_date', 'u.uom_name', 'prd.color')
            ->first();

        $packing_data = PackingData::where('packing_id', $id)->where('status', 1)->get();

        return view('Production.Packing.viewPacking', compact('packing', 'packing_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Packing = Packing::where('id', $id)->where('status', 1)->first();

        if (!$Packing) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('Production.Packing.updatePacking', compact('Packing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();

        $total_qty = 0;
        try {

            if (empty($request->production_attached)) {
                $production_attached = 0;
                $attached_pp_id = 0;
                $attached_material_requisition_id = 0;
            } else {
                $production_attached = 1;
                $attached_pp_id = $request->attached_pp_id;
                $attached_material_requisition_id = $request->attached_material_requisition_id;
            }
            // dd($request->item_id);
            $packing = Packing::find($request->packing_id);
            $packing->so_id = $request->so_id ?? 0;
            // $packing->pr_id = $request->pr_id;
            // $packing->pr_data_id = $request->pr_data_id;
            $packing->material_requisition_id = $request->mr_id ?? 0;
            $packing->production_plan_id = $request->pp_id ?? 0;
            $packing->dc_id = $request->delivery_challan_id ?? 0;

            $packing->customer_name = $request->customer_name ?? '';
            $packing->customer_id = $request->customer_id ?? '';
            $packing->packing_date = $request->packing_date ?? '';
            $packing->deliver_to = $request->deliver_to ?? '';
            $packing->wastage = $request->wastage ?? 0;
            // $packing->packing_list_no = $request->packing_list_no;
            // $packing->item_id = $request->finish_good;
            // $packing->item_name = $request->item_name;

            $packing->production_attached = $production_attached;
            $packing->attached_pp_id = $attached_pp_id;
            $packing->attached_material_requisition_id = $attached_material_requisition_id;

            $packing->status = 1;
            $packing->username = Auth::user()->name;
            $packing->save();
            $packing_id = $packing->id;

            $total_qty = 0;
            $batch_code = ProductionHelper::getNewBatchCode();
            foreach ($request->packing_item_id as $key => $value) {
                $quantity = $request->quantity[$key] ?? null;
                $number_of_pails = $request->number_of_pails[$key] ?? null;
                $bundle_no = $request->bundle_no[$key] ?? 0;
                $packing_data_id = $request->packing_data_id[$key] ?? null;
                $packing_item_rate = CommonHelper::in_stock_edit_with_amount($value, 1, null);

                $secondary_packing_amount = 0;
                $secondary_item_rate = null;
                $primary_packing_amount = $packing_item_rate['rate'] * $quantity;
                
                if ($quantity === null) {
                    continue;
                }
            
                $total_qty += $quantity;
            
                if (!empty($packing_data_id)) {
                    $packing_data = PackingData::find($packing_data_id);
                    if (!$packing_data) {
                        $packing_data = new PackingData();
                    }
                } else {
                    $packing_data = new PackingData();
                }
            
                $packing_data->packing_id = $packing_id;
                $packing_data->primary_packing_item_id = $value;
                $packing_data->qty = $quantity;
                $packing_data->number_of_pails = $number_of_pails;
                $packing_data->primary_packing_rate = $packing_item_rate['rate'];
                
                $packing_data->machine_proccess_data_id = 0;
                $packing_data->bundle_no = $bundle_no;
                $packing_data->status = 1;
                $packing_data->username = Auth::user()->name;
            
                // Only assign secondary packaging if it exists
                if (!empty($request->secondary_package[$key])) {
                    $packing_data->secondary_packing_item_id = $request->secondary_package[$key];
                    $packing_data->carton_count = $request->carton_count[$key] ?? null;

                    $secondary_item_rate = CommonHelper::in_stock_edit_with_amount($request->secondary_package[$key], 1, null);
                    
                    $packing_data->secondary_packing_rate = $secondary_item_rate['rate'];

                    $secondary_packing_amount = $secondary_item_rate['rate'] * ($request->carton_count[$key] ?? 0);

                }
            
                $packing_data->save();

                // Generate batch_no for stock entries
                $batch_no = CommonHelper::generateUniquePosNoForMachine('machine_proccess_datas','batch_no','PKG');

                // Deduct primary packaging from stock
                if (!empty($number_of_pails) && $number_of_pails > 0) {
                    $primary_packing_rate = $packing_item_rate['rate'] ?? 0;
                    $primary_packing_deduction_amount = $primary_packing_rate * $number_of_pails;
                    
                    $primary_packing_stock = array(
                        'main_id' => $request->pp_id,
                        'master_id' => $packing->id,
                        'voucher_no' => $batch_no,
                        'voucher_date' => $packing->packing_date,
                        'voucher_type' => 9, // Issuance Against Production
                        'sub_item_id' => $value, // primary_packing_item_id
                        'qty' => $number_of_pails, // positive value - system will subtract in reports
                        'amount' => $primary_packing_deduction_amount, // positive value - system will subtract in reports
                        'rate' => $primary_packing_rate,
                        'batch_code' => '', // Stock OUT - batch code is 0
                        'status' => 1,
                        'warehouse_id' => 1,
                        'username' => Auth::user()->username,
                        'created_date' => date('Y-m-d'),
                        'opening' => 0,
                    );
                    DB::Connection('mysql2')->table('stock')->insert($primary_packing_stock);
                    
                    // Create transaction for packing material stock out
                    $packing_category = DB::connection('mysql2')->table('category')
                        ->where('id', 12) // Packing material main category
                        ->where('status', 1)
                        ->select('acc_id')
                        ->first();
                    
                    if ($packing_category && $packing_category->acc_id) {
                        $packing_item = Subitem::where('id', $value)->first();
                        $packing_account_code = FinanceHelper::getAccountCodeByAccId($packing_category->acc_id);
                        
                        $packing_transaction = new Transactions();
                        $packing_transaction = $packing_transaction->SetConnection('mysql2');
                        $packing_transaction->voucher_no = $batch_no;
                        $packing_transaction->master_id = $packing->id;
                        $packing_transaction->v_date = $packing->packing_date;
                        $packing_transaction->acc_id = $packing_category->acc_id;
                        $packing_transaction->acc_code = $packing_account_code;
                        $packing_transaction->particulars = 'Packing Material Stock Out - ' . ($packing_item->sub_ic ?? '') . ' (' . ($packing_item->item_code ?? '') . ') - Qty: ' . $number_of_pails;
                        $packing_transaction->opening_bal = 0;
                        $packing_transaction->debit_credit = 0; // Credit (stock out)
                        $packing_transaction->amount = $primary_packing_deduction_amount;
                        $packing_transaction->username = Auth::user()->name;
                        $packing_transaction->date = date('Y-m-d');
                        $packing_transaction->status = 1;
                        $packing_transaction->voucher_type = 19; // Production voucher type
                        $packing_transaction->save();
                    }
                }

                // Deduct secondary packaging from stock
                if (!empty($request->secondary_package[$key]) && !empty($request->carton_count[$key]) && $request->carton_count[$key] > 0 && isset($secondary_item_rate)) {
                    $secondary_packing_rate = $secondary_item_rate['rate'] ?? 0;
                    $secondary_packing_qty = $request->carton_count[$key];
                    $secondary_packing_deduction_amount = $secondary_packing_rate * $secondary_packing_qty;
                    
                    $secondary_packing_stock = array(
                        'main_id' => $request->pp_id,
                        'master_id' => $packing->id,
                        'voucher_no' => $batch_no,
                        'voucher_date' => $packing->packing_date,
                        'voucher_type' => 9, // Issuance Against Production
                        'sub_item_id' => $request->secondary_package[$key], // secondary_packing_item_id
                        'qty' => $secondary_packing_qty, // positive value - system will subtract in reports
                        'amount' => $secondary_packing_deduction_amount, // positive value - system will subtract in reports
                        'rate' => $secondary_packing_rate,
                        'batch_code' => '', // Stock OUT - batch code is 0
                        'status' => 1,
                        'warehouse_id' => 1,
                        'username' => Auth::user()->username,
                        'created_date' => date('Y-m-d'),
                        'opening' => 0,
                    );
                    DB::Connection('mysql2')->table('stock')->insert($secondary_packing_stock);
                    
                    // Create transaction for secondary packing material stock out
                    $packing_category = DB::connection('mysql2')->table('category')
                        ->where('id', 12) // Packing material main category
                        ->where('status', 1)
                        ->select('acc_id')
                        ->first();
                    
                    if ($packing_category && $packing_category->acc_id) {
                        $secondary_packing_item = Subitem::where('id', $request->secondary_package[$key])->first();
                        $packing_account_code = FinanceHelper::getAccountCodeByAccId($packing_category->acc_id);
                        
                        $packing_transaction = new Transactions();
                        $packing_transaction = $packing_transaction->SetConnection('mysql2');
                        $packing_transaction->voucher_no = $batch_no;
                        $packing_transaction->master_id = $packing->id;
                        $packing_transaction->v_date = $packing->packing_date;
                        $packing_transaction->acc_id = $packing_category->acc_id;
                        $packing_transaction->acc_code = $packing_account_code;
                        $packing_transaction->particulars = 'Packing Material Stock Out - ' . ($secondary_packing_item->sub_ic ?? '') . ' (' . ($secondary_packing_item->item_code ?? '') . ') - Qty: ' . $secondary_packing_qty;
                        $packing_transaction->opening_bal = 0;
                        $packing_transaction->debit_credit = 0; // Credit (stock out)
                        $packing_transaction->amount = $secondary_packing_deduction_amount;
                        $packing_transaction->username = Auth::user()->name;
                        $packing_transaction->date = date('Y-m-d');
                        $packing_transaction->status = 1;
                        $packing_transaction->voucher_type = 19; // Production voucher type
                        $packing_transaction->save();
                    }
                }

                //add stock
                $mp = MachineProccess::where([['production_plane_id', '=', $request->pp_id], ['status', '=', 1]])->first();
                
                $primary_packing_id = $value;
                $primary_quantity = $request->quantity[$key];
                $secondary_packing_id = isset($request->secondary_package[$key]) ? $request->secondary_package[$key] : null;
                $carton_quantity = isset($request->carton_count[$key]) ? $request->carton_count[$key] : null;

                $primary_pack_data = Subitem::where('id', $primary_packing_id)->first();
                $secondary_pack_data = $secondary_packing_id ? Subitem::where('id', $secondary_packing_id)->first() : null;

                $act_quantity = $carton_quantity ?? $primary_quantity;

                // Check pack_size to determine checking logic
                // If pack_size <= 4kg: check both primary and secondary pack type
                // If pack_size > 4kg: check only primary pack type
                $pack_size = $primary_pack_data->pack_size ?? 0;
                $check_secondary = ($pack_size <= 4) && !empty($secondary_pack_data);

                $check_finish_good = Subitem::where('item_code', $request->finish_good_item_code)
                    ->where('pack_size', $primary_pack_data->pack_size)
                    ->where('primary_pack_type', $primary_pack_data->primary_pack_type)
                    ->where('uom', $primary_pack_data->uom)
                    ->where('color', $request->finish_good_color)
                    ->when($check_secondary, function ($query) use ($secondary_pack_data) {
                        // For items <= 4kg: check both primary and secondary pack type
                        return $query->where('secondary_pack_size', $secondary_pack_data->pack_size)
                                    ->where('secondary_pack_type', $secondary_pack_data->primary_pack_type)
                                    ->where('uom2', $secondary_pack_data->uom ?? $secondary_pack_data->uom);
                    })
                    ->first();

                $amount = $mp->rate * $primary_quantity + $primary_packing_amount + $secondary_packing_amount;
                
                if(empty($check_finish_good)) {
                    // Item doesn't exist - create new item
                    $data['main_ic_id'] = 8;
                    $data['sub_category_id'] = 1;
                    $data['item_code'] = $request->finish_good_item_code;
                    $data['sub_ic'] = $request->finish_good_item_name;
                    
                    $data['uom'] = $primary_pack_data->uom;
                    $data['pack_size'] = $primary_pack_data->pack_size;
                    $data['primary_pack_type'] = $primary_pack_data->primary_pack_type;

                    // Only set secondary pack data if pack_size <= 4kg and secondary_pack_data exists
                    if($check_secondary && $secondary_pack_data) {
                        $data['uom2'] = $secondary_pack_data->uom ?? $primary_pack_data->uom;
                        $data['secondary_pack_size'] = $secondary_pack_data->pack_size;
                        $data['secondary_pack_type'] = $secondary_pack_data->primary_pack_type;
                    } else {
                        // For items > 4kg, set secondary pack data to null or use primary values
                        $data['uom2'] = $primary_pack_data->uom;
                        $data['secondary_pack_size'] = $primary_pack_data->pack_size;
                        $data['secondary_pack_type'] = $primary_pack_data->primary_pack_type;
                    }
                    
                    $data['color'] = $request->finish_good_color;
                    $data['username'] = Auth::user()->username;
                    $data['date'] = date('Y-m-d');
                    $data['status'] = 1;
                    $data['type'] = 2;
                    $new_id = Subitem::insertGetId($data);

                    $stock2 = array
                    (
                        'main_id' => $request->pp_id,
                        'master_id' => $packing->id,
                        'voucher_no' => $batch_no ?? '', // $mr->mr_no,
                        'voucher_date' => $packing->packing_date,
                        'voucher_type' => 11,
                        'sub_item_id' => $new_id,
                        'qty' => $act_quantity,
                        'amount' => $amount,
                        'rate' => $amount / $act_quantity,
                        'batch_code' => $batch_code,
                        'status' => 1,
                        'warehouse_id' => 1, //$request->$warehouseIdName[$itemKey],
                        'username' => Auth::user()->username,
                        'created_date' => date('Y-m-d'),
                        'opening' => 0,
                    );
                    DB::Connection('mysql2')->table('stock')->insert($stock2);
                    
                    // Create transaction for finish good stock in
                    $finish_good_category = DB::connection('mysql2')->table('category')
                        ->where('id', 8) // Finish good main category
                        ->where('status', 1)
                        ->select('acc_id')
                        ->first();
                    
                    if ($finish_good_category && $finish_good_category->acc_id) {
                        $finish_good_account_code = FinanceHelper::getAccountCodeByAccId($finish_good_category->acc_id);
                        
                        $finish_good_transaction = new Transactions();
                        $finish_good_transaction = $finish_good_transaction->SetConnection('mysql2');
                        $finish_good_transaction->voucher_no = $batch_no;
                        $finish_good_transaction->master_id = $packing->id;
                        $finish_good_transaction->v_date = $packing->packing_date;
                        $finish_good_transaction->acc_id = $finish_good_category->acc_id;
                        $finish_good_transaction->acc_code = $finish_good_account_code;
                        $finish_good_transaction->particulars = 'Finish Good Stock In - ' . ($request->finish_good_item_name ?? '') . ' (' . ($request->finish_good_item_code ?? '') . ') - Qty: ' . $act_quantity;
                        $finish_good_transaction->opening_bal = 0;
                        $finish_good_transaction->debit_credit = 1; // Debit (stock in)
                        $finish_good_transaction->amount = $amount;
                        $finish_good_transaction->username = Auth::user()->name;
                        $finish_good_transaction->date = date('Y-m-d');
                        $finish_good_transaction->status = 1;
                        $finish_good_transaction->voucher_type = 19; // Production voucher type
                        $finish_good_transaction->save();
                    }

                } else {

                    $stock = array
                    (
                        'main_id' => $request->pp_id,
                        'master_id' => $packing->id,
                        'voucher_no' => $batch_no, // $mr->mr_no,
                        'voucher_date' => $packing->packing_date,
                        'voucher_type' => 11,
                        'sub_item_id' => $check_finish_good->id,
                        'qty' => $act_quantity,
                        'amount' => $amount,
                        'rate' => $amount / $act_quantity,
                        'batch_code' => $batch_code,
                        'status' => 1,
                        'warehouse_id' => 1, //$request->$warehouseIdName[$itemKey],
                        'username' => Auth::user()->username,
                        'created_date' => date('Y-m-d'),
                        'opening' => 0,
                    );

                    DB::Connection('mysql2')->table('stock')->insert($stock);
                    
                    // Create transaction for finish good stock in
                    $finish_good_category = DB::connection('mysql2')->table('category')
                        ->where('id', 8) // Finish good main category
                        ->where('status', 1)
                        ->select('acc_id')
                        ->first();
                    
                    if ($finish_good_category && $finish_good_category->acc_id) {
                        $finish_good_account_code = FinanceHelper::getAccountCodeByAccId($finish_good_category->acc_id);
                        
                        $finish_good_transaction = new Transactions();
                        $finish_good_transaction = $finish_good_transaction->SetConnection('mysql2');
                        $finish_good_transaction->voucher_no = $batch_no;
                        $finish_good_transaction->master_id = $packing->id;
                        $finish_good_transaction->v_date = $packing->packing_date;
                        $finish_good_transaction->acc_id = $finish_good_category->acc_id;
                        $finish_good_transaction->acc_code = $finish_good_account_code;
                        $finish_good_transaction->particulars = 'Finish Good Stock In - ' . ($request->finish_good_item_name ?? '') . ' (' . ($request->finish_good_item_code ?? '') . ') - Qty: ' . $act_quantity;
                        $finish_good_transaction->opening_bal = 0;
                        $finish_good_transaction->debit_credit = 1; // Debit (stock in)
                        $finish_good_transaction->amount = $amount;
                        $finish_good_transaction->username = Auth::user()->name;
                        $finish_good_transaction->date = date('Y-m-d');
                        $finish_good_transaction->status = 1;
                        $finish_good_transaction->voucher_type = 19; // Production voucher type
                        $finish_good_transaction->save();
                    }
                }
            }

            /**
             * Handle remaining quantity (unpacked) – move to production floor stock without packaging.
             */
            $orderQty = $request->order_qty[0] ?? 0;
            $wastage = $request->wastage ?? 0;
            $remainingQty = $orderQty - $total_qty - $wastage;

            if ($remainingQty > 0) {
                // find the base finish good item
                $baseFinishGood = Subitem::where('item_code', $request->finish_good_item_code ?? '')
                    ->where('color', $request->finish_good_color ?? '')
                    ->first();

                // fallback to packaged finish good if base not found
                $finishGoodIdForStock = $baseFinishGood->id ?? $request->finish_good ?? null;

                if ($finishGoodIdForStock) {
                    $mp = MachineProccess::where([['production_plane_id', '=', $request->pp_id], ['status', '=', 1]])->first();
                    $batch_no = CommonHelper::generateUniquePosNoForMachine('machine_proccess_datas','batch_no','PKG');
                    $batch_code = $batch_code ?? ProductionHelper::getNewBatchCode(); // reuse if already set

                    $amount = ($mp->rate ?? 0) * $remainingQty;

                    $stockRemainder = array
                    (
                        'main_id' => $request->pp_id,
                        'master_id' => $packing->id,
                        'voucher_no' => $batch_no,
                        'voucher_date' => $packing->packing_date,
                        'voucher_type' => 11,
                        'sub_item_id' => $finishGoodIdForStock,
                        'qty' => $remainingQty,
                        'amount' => $amount,
                        'rate' => $remainingQty > 0 ? ($amount / $remainingQty) : 0,
                        'batch_code' => $batch_code,
                        'status' => 1,
                        'warehouse_id' => 2,
                        'username' => Auth::user()->username,
                        'created_date' => date('Y-m-d'),
                        'opening' => 0,
                    );

                    DB::Connection('mysql2')->table('stock')->insert($stockRemainder);
                }
            }

            DB::connection('mysql2')->table('production_request_data')->where('id', $request->pr_data_id)->update(['packing_status' => 3]);

            DB::connection('mysql2')->table('machine_proccesses')->where('production_plane_id', $request->pp_id)->update(['proccess_status' => 3]);

            $packing->total_qty = $total_qty;
            $packing->save();

            // Record wastage transaction if wastage exists
            if ($wastage > 0) {
                // Get machine process to get the rate for wastage calculation
                $mp = MachineProccess::where([['production_plane_id', '=', $request->pp_id], ['status', '=', 1]])->first();
                $wastage_rate = $mp->rate ?? 0;
                $wastage_amount = $wastage * $wastage_rate;

                // if ($wastage_amount > 0) {
                    // Get inventory wastage expense account ID
                    // Try multiple variations of the account name
                    $wastage_account = DB::connection('mysql2')->table('accounts')
                        ->where(function($query) {
                            $query->where(DB::raw('LOWER(name)'), 'like', '%inventory wastage%')
                                  ->orWhere(DB::raw('LOWER(name)'), 'like', '%wastage%')
                                  ->orWhere(DB::raw('LOWER(name)'), 'like', '%inventory waste%')
                                  ->orWhere(DB::raw('LOWER(name)'), 'like', '%waste%');
                        })
                        ->where('status', 1)
                        ->where(function($query) {
                            $query->where('code', 'like', '4%') // Expense accounts typically start with 4
                                  ->orWhere('code', 'like', '5%'); // Some expense accounts might start with 5
                        })
                        ->first();

                    if (!$wastage_account) {
                        // Fallback: try without code restriction
                        $wastage_account = DB::connection('mysql2')->table('accounts')
                            ->where(function($query) {
                                $query->where(DB::raw('LOWER(name)'), 'like', '%inventory wastage%')
                                      ->orWhere(DB::raw('LOWER(name)'), 'like', '%wastage%')
                                      ->orWhere(DB::raw('LOWER(name)'), 'like', '%waste%');
                            })
                            ->where('status', 1)
                            ->first();
                    }

                    if ($wastage_account) {
                        $wastage_account_id = $wastage_account->id;
                        $wastage_account_code = $wastage_account->code;
                    } else {
                        $wastage_account_id = null;
                        $wastage_account_code = null;
                    }

                    // Get finish good category account (main_ic_id = 8) for credit entry
                    $finish_good_category = DB::connection('mysql2')->table('category')
                        ->where('id', 8) // Finish good main category
                        ->where('status', 1)
                        ->select('acc_id')
                        ->first();

                    if ($wastage_account_id && $finish_good_category && $finish_good_category->acc_id) {
                        // Get packing list number (pkg number)
                        $pkg_number = $packing->packing_list_no ?? '';
                        
                        // Get production plan order number
                        $production_plan = DB::connection('mysql2')->table('production_plane')
                            ->where('id', $request->pp_id)
                            ->select('order_no')
                            ->first();
                        $production_plan_no = $production_plan->order_no ?? '';
                        
                        // Use packing list number as voucher_no
                        $voucher_no = $pkg_number;
                        
                        // Build particulars with production plan number
                        $particulars = 'Wastage against production - Production Plan: ' . $production_plan_no . ' - ' . ($request->finish_good_item_name ?? '') . ' (' . ($request->finish_good_item_code ?? '') . ') - Qty: ' . $wastage;
                        
                        // Debit: Inventory Wastage Expense Account
                        $transaction_debit = new Transactions();
                        $transaction_debit = $transaction_debit->SetConnection('mysql2');
                        $transaction_debit->voucher_no = $voucher_no;
                        $transaction_debit->master_id = $packing->id; // Packaging ID
                        $transaction_debit->v_date = date('Y-m-d'); // Current date
                        $transaction_debit->acc_id = $wastage_account_id;
                        $transaction_debit->acc_code = $wastage_account_code;
                        $transaction_debit->particulars = $particulars;
                        $transaction_debit->opening_bal = 0;
                        $transaction_debit->debit_credit = 1; // Debit
                        $transaction_debit->amount = $wastage_amount;
                        $transaction_debit->username = Auth::user()->name;
                        $transaction_debit->date = date('Y-m-d');
                        $transaction_debit->status = 1;
                        $transaction_debit->voucher_type = 19; // Production voucher type
                        $transaction_debit->save();

                        // Credit: Finish Good Account (main_ic_id = 8)
                        $finish_good_account_code = FinanceHelper::getAccountCodeByAccId($finish_good_category->acc_id);
                        
                        // $transaction_credit = new Transactions();
                        // $transaction_credit = $transaction_credit->SetConnection('mysql2');
                        // $transaction_credit->voucher_no = $voucher_no;
                        // $transaction_credit->master_id = $packing->id; // Packaging ID
                        // $transaction_credit->v_date = date('Y-m-d'); // Current date
                        // $transaction_credit->acc_id = $finish_good_category->acc_id;
                        // $transaction_credit->acc_code = $finish_good_account_code;
                        // $transaction_credit->particulars = $particulars;
                        // $transaction_credit->opening_bal = 0;
                        // $transaction_credit->debit_credit = 0; // Credit
                        // $transaction_credit->amount = $wastage_amount;
                        // $transaction_credit->username = Auth::user()->name;
                        // $transaction_credit->date = date('Y-m-d');
                        // $transaction_credit->status = 1;
                        // $transaction_credit->voucher_type = 19; // Production voucher type
                        // $transaction_credit->save();
                    } else {
                        // Log or handle case where accounts are not found
                        // You can add logging here if needed
                        \Log::warning('Wastage transaction not created: wastage_account_id=' . ($wastage_account_id ?? 'null') . ', finish_good_category=' . ($finish_good_category ? 'found' : 'not found'));
                    }
                // }
            }

            DB::Connection('mysql2')->commit();

            Session::flash('dataInsert','Successfully Saved');
            return redirect()->back()->with('success', 'Record inserted successfully');
        } catch (QueryException $e) {
            // Log or handle the exception as needed
            DB::Connection('mysql2')->rollback();

            return redirect()->back()->withErrors('Error inserting record. Please try again.')->withInput();
        }
    }

    public function deletePacking($id)
    {
        Packing::find($id)->update([
            'status' => 0
        ]);
        PackingData::where('packing_id', $id)->update([
            'status' => 0
        ]);

        DB::connection('mysql2')->table('machine_proccess_datas AS mpd')
            ->join('packing_datas AS pd', 'pd.machine_proccess_data_id', '=', 'mpd.id')
            ->where('pd.packing_id', $id)
            ->update(['mpd.machine_process_stage' => 1]);
    }


    public function productionPlanAndCustomerAgainstSo(Request $request)
    {


        $material_requisition = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('pp.status', 1)
            ->where('pp.sales_order_id', $request->id)
            ->groupBy('mr.id')
            ->select('mr.id', 'pp.id as pp_id', 'pp.order_no', 'pp.order_date', 's.sub_ic')
            ->get();
        $delivery_challan = DB::Connection('mysql2')->table('delivery_note as d')
            ->join('delivery_note_data as dd', 'dd.master_id', 'd.id')
            ->join('subitem as s', 's.id', '=', 'dd.item_id')
            ->join('uom as u', 'u.id', '=', 's.uom')
            ->where('d.master_id', $request->id)
            ->where('d.status', 1)
            ->where('d.lot_no', '!=', null)
            ->select('d.id', 'd.gd_no', 'd.dc_no', 'd.so_no', 'dd.qty', 's.sub_ic', 'u.uom_name')
            ->get();
        // dd($delivery_challan);
        $customerDetails = Sales_Order::where('sales_order.id', $request->id)
            ->join('customers', 'customers.id', 'sales_order.buyers_id')
            ->select('sales_order.id as so_id', 'sales_order.purchase_order_no', 'sales_order.so_no', 'customers.id as customer_id', 'customers.name as name')
            ->first();

        return compact('material_requisition', 'customerDetails', 'delivery_challan');
    }

    public function getMachineProcessDataByMr(Request $request)
    {
        // $production_plan_id = $request->pp_id;
        // $machine_data = DB::connection('mysql2')->table('machine_proccesses AS mp')
        //     ->join('subitem AS s', 's.id', '=', 'mp.finish_good_id')
        //     ->select('mp.finish_good_id', 's.sub_ic')
        //     ->where('mp.status', 1)
        //     ->where('s.status', 1)
        //     ->where('mp.mr_id', $request->mr_id)
        //     ->first();
            
        // $mr_datas = DB::connection('mysql2')->table('machine_proccesses AS mp')
        //     ->join('machine_proccess_datas AS mpd', 'mp.id', '=', 'mpd.machine_proccess_id')
            
        //     ->select('mpd.id', 'mpd.batch_no', 'mpd.request_qty')
        //     ->where('mp.status', 1)
        //     ->where('mpd.status', 1)
        //     ->where('mpd.machine_process_stage', 2)
        //     ->where('mp.mr_id', $request->mr_id)
        //     // ->whereBetween('mpd.batch_no', [$request->range_1 , $request->range_2])
        //     ->get();
        
        $production =  DB::connection('mysql2')
            ->table('production_plane AS pp')
            ->join('production_plane_data AS ppd', 'ppd.master_id','=', 'pp.id')
            // ->join('production_request AS pr', 'pp.pr_id','=', 'pr.id')
            ->join('packings AS pk', 'pk.pr_data_id','=', 'ppd.production_request_data_id')
            ->where('pp.id',$request->pp_id)
            ->select('pk.*','ppd.color','ppd.planned_qty AS quantity','pk.wastage')->first();

        $packing_data =  DB::connection('mysql2')
            ->table('packing_datas')
            ->where('packing_id',$production->id)
            ->get();

        $packing_items = Subitem::where([['status','=', 1],['main_ic_id','=', 12]])->select('id','item_code','sub_ic','pack_size','uom')->get();

        $cartons = DB::connection('mysql2')->table('subitem')
            ->where('item_code', 'like', '%CT-%')
            ->where('status', 1)
            ->select('id', 'item_code', 'sub_ic','pack_size')
            ->get();

        return view('Production.Packing.getMachineProcessDataByMr', compact('production', 'packing_data','packing_items','cartons'));

    }

    public function checkPackagingType(Request $request)
    {
        $packing_item_id = $request->packing_item_id;
        $finish_good = $request->finish_good;

        $packaging = DB::connection('mysql2')->table('subitem')
            ->where('id', $packing_item_id)
            ->where('status', 1)
            ->select('uom2', 'secondary_pack_size', 'secondary_pack_type')
            ->first();

        if ($packaging) {
            $cartonPackSizes = [1, 2, 3, 4];

            $cartons = DB::connection('mysql2')->table('subitem')
                ->where('item_code', 'like', '%CT-%')
                ->where('status', 1)
                ->select('id', 'item_code', 'sub_ic','pack_size')
                ->get();

            $isCarton = in_array($packaging->secondary_pack_size, $cartonPackSizes);

            return response()->json([
                'status' => 'success',
                'data' => $packaging,
                'is_carton' => $isCarton,
                'cartons' => $cartons
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Packaging Type'
            ]);
        }
    }
}
