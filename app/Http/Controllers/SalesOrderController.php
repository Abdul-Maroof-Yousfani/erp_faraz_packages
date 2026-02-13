<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Helpers\SalesHelper;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Config;
use Redirect;
use Session;
use Exception;
use App\Models\Transactions;
use App\Models\SaleQuotation;


class SalesOrderController extends Controller
{
    public $path;

    public function __construct()
    {
        $this->path = 'selling.saleorder.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listSaleOrder(Request $request)
    {
        if ($request->ajax()) {
            $sale_orders = DB::Connection('mysql2')->table('sales_order')
                ->join('customers', 'sales_order.buyers_id', 'customers.id')
                ->where('sales_order.status', 1)->select('sales_order.*', 'customers.name');
            // if(!empty($request->to) && !empty($request->from)){
            //     $from = $request->from;
            //     $to = $request->to;
            //     $sale_orders->whereBetween('sales_order.so_date',[$from,$to]);

            // }
            if (!empty($request->Filter)) {
                $sale_orders->where('sales_order.so_no', 'Like', '%' . $request->SoNo . '%');
            }

            $sale_orders = $sale_orders->orderBy('id','desc')->get();

            return view('selling.saleorder.listSaleOrderAjax', compact('sale_orders'));

        }

        return view('selling.saleorder.listSaleOrder');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSaleOrder()
    {
        $sub_item = DB::Connection('mysql2')->table('category as c')
            // ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'c.id', '=', 's.main_ic_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            // ->join('packaging_type AS pt' ,'pt.id','=', 's.primary_pack_type')
            // ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id','s.pack_size','s.color')
            // ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();
        return view('selling.saleorder.createSaleOrder', compact('sub_item'));
    }

    // new code
    public function viewSaleOrder_saquib(Request $request)
    {
        // $sale_order = Sales_Order::where('id', $request->id)->where('status', 1)->first();
        // $sale_order_data = Sales_Order_Data::where('master_id', $request->id)->where('status', 1)->get();
        $sale_order = DB::Connection('mysql2')->table('sales_order')
            ->join('sales_order_data', 'sales_order.id', 'sales_order_data.master_id')
            ->join('customers', 'customers.id', 'sales_order.buyers_id')
            ->join('subitem AS s', 's.id', 'sales_order_data.item_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            // ->join('packaging_type AS pt' ,'pt.id','=', 's.primary_pack_type')
            ->select('sales_order_data.id as sale_order_data_id', 's.item_code', 'u.uom_name', 'sales_order_data.*', 'sales_order.*', 'customers.name AS customer_name','s.pack_size','s.primary_pack_type','s.color','s.id', 's.sub_ic', 's.uom', 's.item_code',)
            ->where('sales_order.id', $request->id)
            ->where('sales_order_data.status', 1)
            // ->where('sales_order.status', 1)
            ->get();
        return view('selling.saleorder.viewSaleOrder_saquib', compact('sale_order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
// dd($request->all());
        DB::Connection('mysql2')->beginTransaction();
        try {

            $data = $request->item_id;
            if (empty($data)) {
                return redirect()->route('createSaleOrder')->with('error', 'Product Details can not be null');
            }

            $byers_id = $request->customer;
            $grand_amount = 0;
            $sale_tax_mount_total = 0;
            $so_no = CommonHelper::generateUniquePosNo('sales_order', 'so_no', 'SO');
            $currency_id = $request->currency_id;
            $currency_id = explode(',', $currency_id);
            $currency_id = $currency_id[0];

            $sales_order = new Sales_Order();
            $sales_order = $sales_order->SetConnection('mysql2');
            $sales_order->so_no = $so_no;
            $sales_order->so_date = $request->sale_order_date;
            $sales_order->purchase_order_no = $request->purchase_order_no ?? '';
            $sales_order->purchase_order_date = $request->purchase_order_date;
            $sales_order->purchase_order_contract = $request->quotation_id ?? '';
            $sales_order->currency_id = $currency_id ?? '';
            $sales_order->exchange_rate = $request->exchange_rate ?? '';
            $sales_order->amount_in_words = $request->rupeess ?? '';

            if (!empty($request->sale_taxt_group)) {
                $sale_tax_id = explode(',', $request->sale_taxt_group);
                $sales_order->sales_tax_group = $sale_tax_id[0];
                $sales_order->sales_tax_rate = $request->sale_tax_rate;
            }

            if (!empty($request->further_taxes_group)) {
                $further_taxes_group = explode(',', $request->further_taxes_group);
                $sales_order->further_taxes_group = $further_taxes_group[0];
                $sales_order->sales_tax_further = $request->further_tax;
            }

            if (!empty($request->advance_tax_rate)) {
                $advance_tax_id = explode(',', $request->advance_tax_rate);
                $sales_order->advance_tax_group = $advance_tax_id[0];
                $sales_order->advance_tax = $request->advance_tax;
            }

            $sales_order->cartage_amount = $request->cartage_amount;
            $sales_order->priority = $request->priority;
            $sales_order->status = 1;
            $sales_order->username = Auth::user()->name;
            $sales_order->date = date('Y-m-d');
            $sales_order->buyers_id = $byers_id;
            $sales_order->save();

            $master_id = $sales_order->id;
            $data = $request->item_id;

            $count = 1;
            foreach ($data as $key => $row):

                $sales_order_data = new Sales_Order_Data();
                $sales_order_data = $sales_order_data->SetConnection('mysql2');
                $sales_order_data->master_id = $master_id;
                $sales_order_data->so_no = $so_no;
                $sales_order_data->item_id = $request->item_id[$key];
                $sales_order_data->desc = $request->item_id[$key];
                $sales_order_data->thickness = 0;
                $sales_order_data->diameter = 0;
                $sales_order_data->item_description = $request->item_description[$key] ?? '';
                $sales_order_data->qty = $request->qty[$key];
                $sales_order_data->qty_lbs = $request->qty_lbs[$key];
                $sales_order_data->rate = $request->rate[$key];
                $sales_order_data->printing = $request->printing[$key] ?? '';
                $sales_order_data->special_instruction = $request->special_ins[$key] ?? '';
                $sales_order_data->delivery_date = $request->delivery_date[$key] ?? '';
                $sales_order_data->amount = $request->total[$key];

                $sales_order_data->length_bundle = $request->length_bundle[$key] ?? '';
                // $sales_order_data->delivery_type=$request->total[$key];   Delivery type
                $sales_order_data->tax = $request->sale_tax_rate;
                $sales_order_data->further_tax = $request->further_tax;
                $sales_order_data->advance_tax = $request->advance_tax;

                $sale_tax_amount = $request->total[$key] / 100 * $request->sale_tax_rate;
                $further_tax_amount = $request->total[$key] / 100 * $request->further_tax;
                $advance_tax_amount = $request->total[$key] / 100 * $request->advance_tax;
                
                $sales_order_data->tax_amount = $sale_tax_amount;
                $sales_order_data->further_tax_amount = $further_tax_amount;
                $sales_order_data->advance_tax_amount = $advance_tax_amount;

                $sales_order_data->sub_total = $sale_tax_amount + $further_tax_amount + $advance_tax_amount + $request->total[$key];
                $sales_order_data->status = 1;
                $sales_order_data->date = date('Y-m-d');
                $sales_order_data->username = Auth::user()->name;
                $sales_order_data->groupby = $count;

                $sales_order_data->save();

                $grand_amount += $request->total[$key];

                $count++;
            endforeach;
            if (!empty($request->quotation_id)) {
                $s_qt = SaleQuotation::find($request->quotation_id);
                $s_qt->so_status = 1;
                $s_qt->save();
            }

            $sales_order->total_amount = $grand_amount;
            $sales_order->total_amount_after_sale_tax = $request->grand_total_with_tax;
            $sales_order->save();
            SalesHelper::sales_activity($so_no, $request->sale_order_date, '0', 1, 'Insert');
            $voucher_no = $so_no;
            $subject = 'Sales Order Created ' . $so_no;

            DB::Connection('mysql2')->commit();
            return redirect()->route('createSaleOrder')->with('dataInsert', 'Sale Order Inserted');
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('createSaleOrder')->with('error', $e->getMessage());
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
        $sale_orders = DB::Connection('mysql2')->table('sales_order')->find($id);
        return view($this->path . 'sales_order_view', compact('sale_orders'));
    }


    public function getSaleOrderDataCategory(Request $request)
    {
        $html = '<option>Select category</option>';
        $sale_orders = DB::Connection('mysql2')->table('sales_order_data')
            ->join('subitem', 'subitem.id', 'sales_order_data.item_id')
            ->join('sub_category', 'sub_category.id', 'subitem.sub_category_id')
            ->where('sales_order_data.master_id', $request->id)
            ->select('sub_category.id as sub_category_id', 'sub_category.sub_category_name')
            ->where('sales_order_data.status', 1)
            ->where('subitem.status', 1)
            ->groupBy('sub_category.id')
            ->get();
        foreach ($sale_orders as $sale_order) {
            $html .= '<option value="' . $sale_order->sub_category_id . '">' . $sale_order->sub_category_name . '</option>';
        }
        return $html;
    }

    public function getSaleOrderData(Request $request)
    {
        $sale_orders = DB::Connection('mysql2')->table('sales_order_data')
            ->join('subitem', 'subitem.id', 'sales_order_data.item_id')
            ->join('uom', 'uom.id', 'subitem.uom')
            ->select('sales_order_data.id as sale_order_data_id', 'subitem.*', 'uom.*', 'sales_order_data.*')
            ->where('sales_order_data.master_id', $request->so_id)
            //    ->where('sales_order_data.production_status',0)
            ->where('sales_order_data.status', 1)
            ->where('subitem.sub_category_id', $request->category_id)
            ->get();
        return view($this->path . 'getSaleOrderData', compact('sale_orders'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale_orders = DB::Connection('mysql2')->table('sales_order')->find($id);

        $sales_order_data = DB::Connection('mysql2')->table('sales_order_data')
            ->join('subitem', 'subitem.id', 'sales_order_data.item_id')
            ->join('uom', 'uom.id', 'subitem.uom')
            ->select('sales_order_data.id as sale_order_data_id', 'subitem.*', 'uom.*', 'sales_order_data.*')
            ->where('sales_order_data.master_id', $id)
            // ->where('sales_order_data.production_status',0)
            ->where('sales_order_data.status', 1)
            ->get();

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->join('packaging_type AS pt' ,'pt.id','=', 's.primary_pack_type')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id','pt.type','s.pack_size','s.primary_pack_type','s.color')
            // ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        return view($this->path . 'editSaleOrder', compact('sale_orders', 'sales_order_data', 'sub_item'));

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
        // dd($request->all());
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        DB::Connection('mysql2')->beginTransaction();
        try {

            $grand_amount = 0;
            $sale_tax_mount_total = 0;
            $currency_id = $request->currency_id;
            $currency_id = explode(',', $currency_id);
            $currency_id = $currency_id[0];

            // Assuming you have a Sales_Order model
            $sales_order = Sales_Order::findOrFail($id);
            $sales_order = $sales_order->SetConnection('mysql2');
            // Update Sales_Order information
            $sales_order->so_date = $request->sale_order_date;
            $sales_order->purchase_order_no = $request->purchase_order_no ?? '';
            $sales_order->purchase_order_date = $request->purchase_order_date;
            $sales_order->purchase_order_contract = $request->quotation_id ?? '';
            $sales_order->currency_id = $currency_id ?? '';
            $sales_order->exchange_rate = $request->exchange_rate ?? '';
            $sales_order->amount_in_words = $request->rupeess ?? '';

            if (!empty($request->sale_taxt_group)) {
                $sale_tax_id = explode(',', $request->sale_taxt_group);
                $sales_order->sales_tax_group = $sale_tax_id[0];
                $sales_order->sales_tax_rate = $request->sale_tax_rate;
            } else {
                $sales_order->sales_tax_group = 0;
                $sales_order->sales_tax_rate = 0;
            }

            if (!empty($request->further_taxes_group)) {
                $further_taxes_group = explode(',', $request->further_taxes_group);
                $sales_order->further_taxes_group = $further_taxes_group[0];
                $sales_order->sales_tax_further = $request->further_tax;
            }

            if (!empty($request->advance_tax_rate)) {
                $advance_tax_id = explode(',', $request->advance_tax_rate);
                $sales_order->advance_tax_group = $advance_tax_id[0];
                $sales_order->advance_tax = $request->advance_tax;
            }

            $sales_order->cartage_amount = $request->cartage_amount;
            $sales_order->priority = $request->priority;
            $sales_order->buyers_id = $request->customer;
            $sales_order->save();

            // foreach ($request->sale_order_data_id as $key => $value) {
            //     # code...
            //     db::Connection('mysql2')->table('sales_order_data')->where('id', $value)->update([
            //         'status' => 2
            //     ]);
            // }

            $master_id = $id;

            DB::Connection('mysql2')->table('sales_order_data')->where('master_id', $master_id)->update([
                'status' => 2
            ]);

          
            $data = $request->item_id;
            $count = 1;
            foreach ($data as $key => $row):

                $sales_order_data = new Sales_Order_Data();
                $sales_order_data = $sales_order_data->SetConnection('mysql2');
                $sales_order_data->master_id = $master_id;
                $sales_order_data->so_no = $request->sale_order_no;
                $sales_order_data->item_id = $request->item_id[$key];
                $sales_order_data->desc = $request->item_id[$key];
                $sales_order_data->thickness = 0;
                $sales_order_data->diameter = 0;
                $sales_order_data->item_description = $request->item_description[$key] ?? null;
                $sales_order_data->qty = $request->qty[$key];
                $sales_order_data->qty_lbs = $request->qty_lbs[$key];
                $sales_order_data->rate = $request->rate[$key];
                $sales_order_data->printing = $request->printing[$key] ?? null;
                $sales_order_data->special_instruction = $request->special_ins[$key] ?? null;
                $sales_order_data->delivery_date = $request->delivery_date[$key] ?? null;
                $sales_order_data->amount = $request->total[$key];
                $sales_order_data->length_bundle = $request->length_bundle[$key] ?? null;

                $sales_order_data->tax = $request->sale_tax_rate;
                $sales_order_data->further_tax = $request->further_tax;
                $sales_order_data->advance_tax = $request->advance_tax;

                $sale_tax_amount = $request->total[$key] / 100 * $request->sale_tax_rate;
                $further_tax_amount = $request->total[$key] / 100 * $request->further_tax;
                $advance_tax_amount = $request->total[$key] / 100 * $request->advance_tax;

                $sales_order_data->tax_amount = $sale_tax_amount;
                $sales_order_data->further_tax_amount = $further_tax_amount;
                $sales_order_data->advance_tax_amount = $advance_tax_amount;

                $sales_order_data->sub_total = $sale_tax_amount + $further_tax_amount + $advance_tax_amount + $request->total[$key];
                $sales_order_data->status = 1;
                $sales_order_data->date = date('Y-m-d');
                $sales_order_data->username = Auth::user()->name;
                $sales_order_data->groupby = $count;
                $sales_order_data->save();
                $grand_amount += $request->total[$key];
                $count++;

            endforeach;

            if (!empty($request->quotation_id)) {
                $s_qt = SaleQuotation::find($request->quotation_id);
                $s_qt->so_status = 1;
                $s_qt->save();
            }

            // Update Sales_Order total_amount information
            $sales_order->total_amount = array_sum($request->total);
            $sales_order->total_amount_after_sale_tax = $request->grand_total_with_tax;
            $sales_order->save();

            DB::Connection('mysql2')->commit();
            return redirect('selling/listSaleOrder')->with('dataInsert', 'Sale Order Updated');
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('editSaleOrder', ['id' => $id])->with('error', $e->getMessage());
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

    public function viewSaleOrder($id)
    {
        $sale_orders = DB::Connection('mysql2')->table('sales_order')
            ->join('sales_order_data', 'sales_order.id', 'sales_order_data.master_id')
            ->join('customers', 'customers.id', 'sales_order.buyers_id')
            ->join('subitem', 'subitem.id', 'sales_order_data.item_id')
            ->join('sub_category', 'sub_category.id', 'subitem.sub_category_id')
            ->join('uom', 'uom.id', 'subitem.uom')
            ->select('sales_order_data.id as sale_order_data_id', 'subitem.item_code', 'uom.uom_name', 'sales_order_data.*', 'sales_order.*', 'customers.name AS customer_name', 'sub_category.sub_category_name')
            ->where('sales_order.id', $id)
            ->where('sales_order_data.status', 1)
            ->where('sales_order.status', 1)
            ->get();

        return view('selling.saleorder.viewSaleOrder', compact('sale_orders'));
    }

    public function saleOrderSectionA(Request $request)
    {
        $sale_orders = Sales_Order::find($request->id);
        return view('selling.saleorder.saleOrderSectionA', compact('sale_orders'));
    }
    // public function saleOrderSectionB(Request $request)
    // {
    //     return view('selling.saleorder.viewSaleOrder', compact('sale_orders'));
    // }
}
