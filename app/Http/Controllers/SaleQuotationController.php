<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\SaleQuotation;
use App\Models\SaleQuotationData;
use App\Models\Subitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Subtotal;

class SaleQuotationController extends Controller
{
    public function createSaleQuotation(Request $request)
    {
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
        return view('selling.salequotation.createSaleQuotation', compact('sub_item'));
    }

    public function get_customer(Request $request)
    {

        $id = $request->id;
        $cutsomer = Customer::leftjoin(env('DB_DATABASE') . '.countries as country', 'country.id', 'customers.country')
            ->leftjoin(env('DB_DATABASE') . '.cities as city', 'city.id', 'customers.city')
            ->select('country.name as country_name', 'customers.*', 'city.name as city_name')
            ->where('customers.id', $id)->first();
        return response()->json($cutsomer);
    }

    public function editSaleQuotation($id)
    {
        $salequoatation = SaleQuotation::find($id);
        $salequoatationdata = SaleQuotationData::where('sale_quotaion_id', $id)->get();

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

        return view('selling.salequotation.editSaleQuotation', compact('salequoatation', 'salequoatationdata','sub_item'));
    }

    public function saleQuotaionStore(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {

            if (isset($request->revison_status)) {
                $quoate = SaleQuotation::find($request->quoatation_id);
                $quoate->so_status = 3;
                $quoate->revision_no = $quoate->revision_no . ',' . $request->revision;
                $quoate->save();
                $quotation_no = $quoate->quotation_no;
            } else {
                $quotation_no = CommonHelper::generateUniquePosNo('sale_quotations', 'quotation_no', 'QUO');
            }

            $currency_id = $request->currency_id;
            $currency_id = explode(',', $currency_id);
            $currency_id = $currency_id[0];

            $quotation = new SaleQuotation;
            $quotation->quotation_no = $quotation_no;
            $quotation->customer_type = $request->customer_type;
            $quotation->prospect_id = $request->prospect_id;
            $quotation->quotation_date = $request->quotation_date;
            $quotation->q_valid_up_to = $request->valid_up_to;
            $quotation->revision_no = $request->revision;
            $quotation->revision_count = $request->revision_count;
            $quotation->customer_id = $request->customer_id;
            $quotation->currency_id = $currency_id;
            $quotation->inquiry_reference_date = $request->inquiry_refer_date;
            $quotation->exchange_rate = $request->exchange_rate;
            $quotation->sales_poal = $request->sale_pool;
            $quotation->type = $request->type;
            $quotation->subject_line = $request->subject_line;
            $quotation->storage_dimension = $request->storgae_dimension;
            if (!empty($request->sale_taxt_group)) {
                $sale_tax_id = explode(',', $request->sale_taxt_group);
                $quotation->sale_tax_group = $sale_tax_id[0];
                $quotation->sales_tax_rate = $request->sale_tax_rate;
            }

            if (!empty($request->further_taxes_group)) {
                $further_tax_id = explode(',', $request->further_taxes_group);
                $quotation->further_tax_group = $further_tax_id[0];
                $quotation->further_tax = $request->further_tax;
            }

            if (!empty($request->advance_tax_rate)) {
                $advance_tax_id = explode(',', $request->advance_tax_rate);
                $quotation->advance_tax_group = $advance_tax_id[0];
                $quotation->advance_tax = $request->advance_tax;
            }
            $quotation->cartage_amount = $request->cartage_amount ?? 0;
            $quotation->mode_of_delivery = $request->mode_of_delivry;
            $quotation->delivery_term = $request->delivery_term;
            $quotation->terms_condition = $request->term_condition;
            $quotation->created_by = $request->created_by;
            $quotation->designation = $request->designation;
            $quotation->company_name = $request->company_name;
            $quotation->total_amount_after_sale_tax = $request->grand_total_with_tax;
            if (isset($request->save_as_draft)) {
                $quotation->so_status = 2;
            }
            $quotation->save();

            foreach ($request->item_id as $key => $item) {
                $item_data = explode('@',$item);
                $q_data = new SaleQuotationData;
                $q_data->sale_quotaion_id = $quotation->id;
                $q_data->quotation_no = $quotation_no;
                // $q_data->pack_size_id = $request->pack_type[$key];
                $q_data->item_id = $item_data[0];
                $q_data->item_description = '';
                $q_data->qty = $request->qty[$key];
                $q_data->color = $request->color[$key];
                $q_data->uom_id = $request->uom[$key];
                $q_data->delivery_type = '';
                $q_data->bundle_length = '';
                $q_data->unit_price = $request->unit_price[$key];
                $q_data->total_amount = $request->total[$key];
                $q_data->sales_tax_rate = $request->sale_tax_rate;
                $q_data->further_tax = $request->further_tax;
                $q_data->advance_tax = $request->advance_tax;
                $sale_tax_amount = $request->total[$key] / 100 * $request->sale_tax_rate;
                $further_tax_amount = $request->total[$key] / 100 * $request->further_tax;
                $advance_tax_amount = $request->total[$key] / 100 * $request->advance_tax;
                $q_data->amount_after_sale_tax = $request->total[$key] + $sale_tax_amount + $further_tax_amount + $advance_tax_amount;
                $q_data->save();
            }

            DB::Connection('mysql2')->commit();
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('listSaleQuotation')->with('error', $e->getMessage());
        }
        return redirect()->route('listSaleQuotation')->with('dataInsert', 'Quotation successFully saved');
    }

    public function listSaleQuotation(Request $request)
    {
        if ($request->ajax()) {
            $sale_quotations = SaleQuotation::leftjoin('customers', 'customers.id', 'sale_quotations.customer_id')
                ->select('customers.id AS customer_id', 'customers.*', 'sale_quotations.*')
                ->whereIn('sale_quotations.so_status', [0, 1, 2])
                ->where('sale_quotations.status', 1)
                ->orderBy('sale_quotations.id','desc')->get();
            return view('selling.salequotation.listSaleQuotationAjax', compact('sale_quotations'));
        }
        return view('selling.salequotation.listSaleQuotation');
    }


    public function get_item_by_id(Request $request)
    {
        $id = $request->id;
        $sub_item = CommonHelper::get_item_by_id($id);
        return response()->json($sub_item);


    }
    public function get_quotation_data(Request $request)
    {
        $id = $request->id;
        $saleQuot = SaleQuotation::find($id);
        $quotation_data = SaleQuotationData::
            join('subitem as s', 's.id', 'sale_quotation_datas.item_id')
            ->join(env('DB_DATABASE') . '.uom AS u', 'u.id', 's.uom')
            ->join('packaging_type AS pt' ,'pt.id','=', 's.primary_pack_type')
            ->where('sale_quotaion_id', $id)
            ->select('sale_quotation_datas.*', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id','pt.type','s.pack_size','s.primary_pack_type','s.color')
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
            
        return view('selling.saleorder.sale_quotation_data', compact('quotation_data', 'saleQuot','sub_item'));
    }

    public function addProduct(Request $request)
    {
        return view('selling.saleorder.addProduct');
    }

    public function get_all_currenecy(Request $request)
    {
        $html = '<option value="">Select Currency </option>';
        $currencies = Currency::where('status', 1)->get();
        foreach ($currencies as $currency) {
            $html .= '<option value="' . $currency->name . '">' . $currency->name . '</option>';
        }
        return $html;
    }

    public function viewSaleQuotation(Request $request)
    {
        $id = $request->id;
        $sale_quotations = SaleQuotation::join('sale_quotation_datas', 'sale_quotation_datas.sale_quotaion_id', 'sale_quotations.id')
            ->leftjoin('customers', 'customers.id', 'sale_quotations.customer_id')
            ->leftjoin('prospects', 'prospects.id', 'sale_quotations.prospect_id')
            ->join('subitem AS s', 's.id', 'sale_quotation_datas.item_id')
            ->leftJoin('currency', 'currency.id', 'sale_quotations.currency_id')
            ->leftJoin('gst', 'gst.id', 'sale_quotations.sale_tax_group')
            ->leftJoin('sales_pools', 'sales_pools.id', 'sale_quotations.sales_poal')
            ->leftJoin('sales_types', 'sales_types.id', 'sale_quotations.type')
            ->leftJoin('storage_dimentions', 'storage_dimentions.id', 'sale_quotations.storage_dimension')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->join('packaging_type AS pt' ,'pt.id','=', 's.primary_pack_type')
            ->select(
                'sale_quotation_datas.*',
                'sale_quotations.*',
                'prospects.company_name as prospect_company_name',
                'prospects.company_address as prospect_company_address',
                'sale_quotations.customer_type as type_customer',
                's.*',
                'customers.*',
                'customers.name as customer_name',
                'gst.rate AS sales_tax_group_name',
                'sales_pools.name AS sales_pool_name',
                'sales_types.name AS sales_type_name',
                'storage_dimentions.name AS storage_dimention_name',
                'sale_quotations.id as sale_quotations_id',
                'sale_quotations.mode_of_delivery',
                'pt.type',
                'u.uom_name'
            )
            ->where('sale_quotations.id', $id) ->get();

        return view('selling.salequotation.viewSaleQuotation', compact('sale_quotations'));
    }

    public function viewSaleQuotation1(Request $request)
    {
        $id = $request->id;
        $sale_quotations = SaleQuotation::find($id);
        $sale_quotation_data = SaleQuotationData::where('sale_quotaion_id', $id)->get();

        // echo "<pre>";
        // print_r($sale_quotations);

        // echo "<pre>";
        // print_r($sale_quotation_data);
        // exit();
        return view('selling.salequotation.viewSaleQuotation-saqib', compact('sale_quotations', 'sale_quotation_data'));
    }

    public function approveSaleQuoatation(Request $request)
    {

        $id = $request->id;
        $sale_quotations = SaleQuotation::find($id);
        $sale_quotations->approved_status = 1;


        if ($sale_quotations->customer_type == 'prospect') {

            $prospect = Prospect::find($sale_quotations->prospect_id);
            $prospect->prospect_type = 2;
            $prospect->save();

            $contact = Contact::find($prospect->contact_id);
            $account_head = 'Trade Payable';
            $sent_code = '1-2-2';     //'Trade Receivables';
            // $sent_code = $account_head;
            $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $sent_code . '\'')->id;

            if ($max_id == '') {
                $code = $sent_code . '-1';
            } else {
                $max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
                $max_code2;
                $max = explode('-', $max_code2);
                $code = $sent_code . '-' . (end($max) + 1);
            }

            $level_array = explode('-', $code);
            $counter = 1;
            foreach ($level_array as $level):
                $data1['level' . $counter] = strip_tags($level);
                $counter++;
            endforeach;



            $customer_code = SalesHelper::generateCustomerCode();
            $data1['code'] = strip_tags($code);
            $data1['name'] = $prospect->company_name;
            $data1['parent_code'] = strip_tags($sent_code);
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['action'] = 'create';
            $data1['type'] = 1;
            $data1['operational'] = 1;
            $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);
            $data2['acc_id'] = $acc_id;
            $data2['prospect_id'] = $prospect->id;
            $data2['name'] = $prospect->company_name;
            $data2['customer_code'] = $customer_code;
            $data2['country'] = 0;
            $data2['province'] = 0;
            $data2['city'] = 0;
            $data2['cnic_ntn'] = 0;
            $data2['strn'] = 0;
            $data2['contact_person'] = $contact->first_name ?? '';
            $data2['contact'] = $contact->phone ?? 0;
            $data2['fax'] = 0;
            $data2['address'] = $prospect->company_address ?? '';
            $data2['email'] = $contact->email ?? '';
            $data2['username'] = Auth::user()->name;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['action'] = 'create';
            $data2['customer_type'] = 3;
            $CustId = DB::connection('mysql2')->table('customers')->insertGetId($data2);
            $sale_quotations->customer_id = $CustId;

        }
        $sale_quotations->save();

        return redirect()->route('listSaleQuotation')->with('success', 'Approved Successfully');
    }

    public function rejectSaleQuoatation(Request $request)
    {
        $id = $request->id;
        $sale_quotations = SaleQuotation::find($id);
        $sale_quotations->approved_status = 3;
        $sale_quotations->save();

        return redirect()->route('listSaleQuotation')->with('success', 'Reject Successfully');
    }

    public function removeDraft(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {

            $sale_quotation = SaleQuotation::find($request->id);
            $sale_quotation->so_status = 0;
            $sale_quotation->save();
            DB::Connection('mysql2')->commit();
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
        return response()->json(['success' => 'successfully Added .']);
    }

    public function getItemColors(Request $request)
    {
        $colors = Subitem::where('id', $request->item_id)
                ->where('status', 1)
                ->distinct()
                ->pluck('color');

        return response()->json($colors);
    }

    public function getItemPackSize(Request $request)
    {
        $item = Subitem::where('id', $request->item_id)
            ->where('status', 1)
            ->first();

        if ($item) {
            $packing = Subitem::where('item_code', $item->item_code)
                ->join('packaging_type AS pt' ,'pt.id','=', 'subitem.primary_pack_type')
                ->join('uom AS u' ,'u.id','=', 'subitem.uom')
                ->where('subitem.status', 1)
                ->select('subitem.id','pt.type','subitem.pack_size','subitem.primary_pack_type','subitem.uom','u.uom_name')
                ->get();
        } else {
            $packing = collect();
        }

        return response()->json($packing);
    }

    
}
