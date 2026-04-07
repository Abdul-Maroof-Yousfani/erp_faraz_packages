<?php

namespace App\Http\Controllers;


use App\Models\ProductionCuttingAndSealing;
use App\Models\ProductionGalaCutting;
use App\Models\ProductionMixture;
use App\Models\ProductionMixtureData;
use App\Models\ProductionOrder;
use App\Models\ProductionPacking;
use App\Models\ProductionRolling;
use App\Models\ProductionRollPrinting;
use Illuminate\Http\Request;
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\FinanceHelper;
use App\Models\Transactions;
use App\Helpers\CommonHelper;
use App\Models\MakeProduct;
use App\Models\MakeProductData;
use App\Models\MaterialRequisition;
use App\Models\Product;
use App\Models\ProductionBom;
use App\Models\ProductionBomData;
use App\Models\WorkStation;
use App\Models\Qc;
use App\Models\QcData;
use App\Models\ProductionPlane;
use App\Models\ProductionPlaneData;
use App\Models\ProductionWorkOrder;
use App\Models\ProductionWorkOrderData;
use App\Models\Subitem;
use App\Models\ProductionPlaneRecipe;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FarazProductionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $m;

    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->m = Session::get('run_company');

    }


    public function get_machine_data_by_finish_good()
    {
        return view('Production.get_machine_data_by_finish_good');
    }

    public function get_machine_data_by_finish_good_for_operation()
    {
        return view('Production.get_machine_data_by_finish_good_for_operation');
    }


    public function production_dashboard()
    {
        return view('dashboard.production_dashboard');
    }


    public function createMachineForm()
    {
        return view('Production.createMachineForm');
    }

    public function editMachineForm()
    {
        return view('Production.editMachineForm');
    }



    public function insert_machine(Request $request)
    {


        $SetupTime = sprintf("%02d:%02d:%02d", floor($request->input('setup_time') / 60), $request->input('setup_time') % 60, '00');
        $InsertMaster['machine_name'] = $request->input('MachineName');
        $InsertMaster['setup_time'] = $SetupTime;
        $InsertMaster['code'] = $request->input('Code');
        $InsertMaster['equi_cost'] = $request->input('equi_cost');
        $InsertMaster['description'] = $request->input('Description');
        $InsertMaster['status'] = 1;
        $InsertMaster['username'] = Auth::user()->name;
        $InsertMaster['date'] = date('Y-m-d');


        // add later

        $InsertMaster['salvage_cost'] = $request->input('salvage_cost');
        ;
        $InsertMaster['dep_cost'] = $request->input('dep_cost');
        ;
        $InsertMaster['life'] = $request->input('life');
        ;
        $InsertMaster['yearly_cost'] = $request->input('yearly_cost');
        ;
        $MasterId = DB::Connection('mysql2')->table('production_machine')->InsertGetId($InsertMaster);


        $DetailSection = $request->input('SubItemId');


        foreach ($DetailSection as $key => $row2) {

            $InsertDetail['master_id'] = $MasterId;
            $InsertDetail['finish_good'] = $request->input('SubItemId')[$key];


            $mold = $request->input('MoldId' . $key . '');
            if ($mold != "") {
                $mold = implode(',', $mold);
                $InsertDetail['mold_id'] = $mold;
            } else {
                $InsertDetail['mold_id'] = "";
            }


            $die = $request->input('DaiId' . $key . '');
            if ($die != "") {
                $die = implode(',', $die);
                $InsertDetail['dai_id'] = $die;
            } else {
                $InsertDetail['dai_id'] = "";
            }


            $InsertDetail['qty_per_hour'] = $request->input('QtyPerHour')[$key];
            $InsertDetail['electricity_per_hour'] = $request->input('ElectricityPerHour')[$key];
            $InsertDetail['status'] = 1;
            DB::Connection('mysql2')->table('production_machine_data')->insert($InsertDetail);
        }
        ProductionHelper::production_activity($MasterId, 5, 1);
        return Redirect::to('production/machine_list?pageType=&&parentCode=&&m=' . Session::get('run_company') . '#');
    }


    public function update_machine(Request $request)
    {
        //        echo "<pre>";
        //        print_r($request->input()); die();
        $EditId = $request->input('edit_id');
        $SetupTime = sprintf("%02d:%02d:%02d", floor($request->input('setup_time') / 60), $request->input('setup_time') % 60, '00');
        $UpdateMaster['machine_name'] = $request->input('MachineName');
        $UpdateMaster['setup_time'] = $SetupTime;
        $UpdateMaster['code'] = $request->input('Code');
        $UpdateMaster['equi_cost'] = $request->input('equi_cost');
        $UpdateMaster['salvage_cost'] = $request->input('salvage_cost');
        $UpdateMaster['dep_cost'] = $request->input('dep_cost');
        $UpdateMaster['life'] = $request->input('life');
        $UpdateMaster['yearly_cost'] = $request->input('yearly_cost');
        $UpdateMaster['status'] = 1;
        $UpdateMaster['username'] = Auth::user()->name;

        //DB::Connection('mysql2')->table('production_machine')->where('id,',$EditId)->update($UpdateMaster);
        DB::Connection('mysql2')->table('production_machine')->where('id', '=', $EditId)->update($UpdateMaster);
        //   DB::Connection('mysql2')->table('production_machine_data')->where('master_id', '=', $EditId)->delete();


        $DetailSection = $request->input('SubItemId');


        foreach ($DetailSection as $key => $row2) {

            $data_id = $request->input('data_id')[$key];
            $InsertDetail['master_id'] = $EditId;
            $InsertDetail['finish_good'] = $request->input('SubItemId')[$key];

            $mold = $request->input('MoldId' . $key . '');
            if ($mold != "") {
                $mold = implode(',', $mold);
                $InsertDetail['mold_id'] = $mold;
            } else {
                $InsertDetail['mold_id'] = "";
            }


            $die = $request->input('DaiId' . $key . '');
            if ($die != "") {
                $die = implode(',', $die);
                $InsertDetail['dai_id'] = $die;
            } else {
                $InsertDetail['dai_id'] = "";
            }


            $InsertDetail['qty_per_hour'] = $request->input('QtyPerHour')[$key];
            $InsertDetail['electricity_per_hour'] = $request->input('ElectricityPerHour')[$key];


            $InsertDetail['status'] = 1;
            if ($data_id == 0):
                DB::Connection('mysql2')->table('production_machine_data')->insert($InsertDetail);
            else:
                DB::Connection('mysql2')->table('production_machine_data')->where('id', $data_id)->update($InsertDetail);
            endif;
        }
        ProductionHelper::production_activity($EditId, 5, 2);
        return Redirect::to('production/machine_list?pageType=&&parentCode=&&m=' . Session::get('run_company') . '#SFR');
    }


    public function machine_list(Request $request)
    {
        return view('Production.machine_list');
    }

    public function viewMachineDetail()
    {
        return view('Production.viewMachineDetail');
    }



    public function production_activity_page()
    {
        return view('Production.production_activity_page');
    }

    public function production_activity_ajax()
    {
        return view('Production.production_activity_aja');
    }


    // production order
    public function createProductionOrderForm()
    {
        $pr_no = CommonHelper::generateProductionNumber();
        $sub_categories = DB::Connection('mysql2')->table('category as c')
            ->leftJoin('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('sc.category_id', '=', 8)
            ->select('sc.id', 'sc.sub_category_name')
            ->groupBy('sc.id')
            ->orderBy('sc.id')
            ->get();

        $color = DB::Connection('mysql2')->table('subitem')->where('status', '=', 1)
            ->select('color')
            ->groupBy('color')
            ->get();
        // dd($sub_item);
        return view('FarazPackagesProduction.createProductionOrderForm', compact('pr_no', 'sub_categories', 'color'));
    }

    public function viewProductionOrderList()
    {
        return view('FarazPackagesProduction.viewProductionOrderList');
    }

    public function viewProductionOrderListDetail(Request $request)
    {
        $m = $request->m;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $approval_status = $request->approval_status;
        CommonHelper::companyDatabaseConnection($m);
        if (!empty($approval_status))
            $query_string_second_part[] = "  AND approval_status ='$approval_status'";
        $query_string_second_part[] = " AND request_date BETWEEN '$from_date' AND '$to_date'";
        $query_string_second_part[] = " AND status = 1";
        $query_string_First_Part = "SELECT * FROM production_request  WHERE ";
        $query_string_third_part = ' ORDER BY id DESC';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $production_request = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        return view('FarazPackagesProduction.viewProductionOrderListDetail', compact('production_request', 'm'));
    }

    public function viewProductionOrderDetail(Request $request)
    {
        $production_request = DB::connection('mysql2')->table('production_request')->where('id', $request->id)->first();
        $production_request_data = DB::connection('mysql2')->table('production_request_data')->where('master_id', $request->id)->get();
        return view('FarazPackagesProduction.viewProductionOrderDetail', compact('production_request', 'production_request_data'));
    }
    public function viewProductionOrderDetailTrack(Request $request)
    {
        $order = ProductionOrder::with(
            'productionRollings.printings.cuttingAndSealings'
        )->find($request->id);
        return view('FarazPackagesProduction.viewProductionOrderDetailTrack', compact('order'));
    }

    public function editProductionOrderForm($id, Request $request)
    {
        $sub_categories = DB::Connection('mysql2')->table('category as c')
            ->leftJoin('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('sc.category_id', '=', 8)
            ->select('sc.id', 'sc.sub_category_name')
            ->groupBy('sc.id')
            ->orderBy('sc.id')
            ->get();

        $color = DB::Connection('mysql2')->table('subitem')->where('status', '=', 1)
            ->select('color')
            ->groupBy('color')
            ->get();

        $production_request = DB::connection('mysql2')->table('production_request')->where('id', $id)->first();
        $production_request_data = DB::connection('mysql2')->table('production_request_data')->where('master_id', $id)->get();
        return view('FarazPackagesProduction.editProductionOrderForm', compact('sub_categories', 'color', 'production_request', 'production_request_data'));
    }

    // production mixture
    public function addProductionMixture(Request $request)
    {
        $categories_id = explode(',', Auth::user()->categories_id);
        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->leftJoin('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'c.id', '=', 's.main_ic_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 14)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $raw_material = DB::Connection('mysql2')->table('subitem')
            ->select('id', 'sub_ic', 'uom', 'item_code', 'pack_size')
            ->where('status', '=', 1)->where('main_ic_id', '=', 7)->get();

        $production_order = DB::Connection('mysql2')->table('production_request')
            ->where('status', '=', 1)
            // ->where('approval_status', '=', 2)
            ->select('id', 'pr_no')->get();
        $mixture_machines = DB::Connection('mysql2')->table('mixture_machines')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();
        $pm_no = CommonHelper::generateProductionMixtureNumber();

        return view('FarazPackagesProduction.ProductionMixture.createProductionMixtureForm', compact('sub_item', 'raw_material', 'production_order', 'mixture_machines', 'pm_no'));
    }

    public function viewProductionMixingList()
    {
        $mixingList = ProductionMixture::with('productionOrder')
            ->withCount('productionRollings as usage_count')
            ->where('status', '=', 1)->get();
        $m = $this->m;
        return view('FarazPackagesProduction.ProductionMixture.viewProductionMixing', compact('mixingList', 'm'));
    }

    public function viewMixingInfo(Request $request)
    {
        $mixture = ProductionMixture::where('id', $request->id)->where('status', 1)->orWhere('produced_item_id', $request->id)->first();
        return view('FarazPackagesProduction.ProductionMixture.viewMixingInfo', compact('mixture'));
    }

    public function mixtureEdit(Request $request)
    {
        $m = $request->m;
        $mixture = ProductionMixture::where('status', 1)->where('id', $request->id)->first();
        if (!$mixture) {
            Session::flash('dataEdit', 'Mixture not found.');
            return redirect()->to('far_production/viewProductionMixingList?m=' . $m);
        }
        if ((float) ($mixture->used_qty ?? 0) > 0) {
            Session::flash('dataEdit', 'This mixture cannot be edited because it is already used in the next production step.');
            return redirect()->to('far_production/viewProductionMixingList?m=' . $m);
        }

        $mixtureData = ProductionMixtureData::where('production_mixture_id', $mixture->id)
            ->orderBy('id', 'ASC')
            ->get();

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->leftJoin('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'c.id', '=', 's.main_ic_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 14)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $raw_material = DB::Connection('mysql2')->table('subitem')
            ->select('id', 'sub_ic', 'uom', 'item_code', 'pack_size')
            ->where('status', '=', 1)->where('main_ic_id', '=', 7)->get();

        $production_order = DB::Connection('mysql2')->table('production_request')
            ->where('status', '=', 1)
            ->select('id', 'pr_no')->get();

        $mixture_machines = DB::Connection('mysql2')->table('mixture_machines')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.editMixture', compact(
            'mixture',
            'mixtureData',
            'sub_item',
            'raw_material',
            'production_order',
            'mixture_machines',
            'm'
        ));
    }

    public function danaReport(Request $request)
    {
        $accType = Auth::user()->acc_type;
        $m = $accType === 'client' ? ($request->query('m') ?? '') : Auth::user()->company_id;

        $date = $request->query('date') ?: date('Y-m-d');
        $rawItemId = (int) ($request->query('raw_item_id') ?: 0);
        $prevDate = date('Y-m-d', strtotime($date . ' -1 day'));

        $qtyExpr = "SUM(CASE WHEN si.pack_size IS NOT NULL AND si.pack_size > 0 THEN s.qty * si.pack_size ELSE s.qty END)";

        $inTypes = [1, 4, 6, 10, 11];
        $outTypes = [2, 3, 5, 9];

        $rawItems = DB::connection('mysql2')->table('subitem')
            ->where('status', 1)
            ->where('main_ic_id', 7)
            ->orderBy('sub_ic')
            ->select('id', 'item_code', 'sub_ic')
            ->get();

        $openingInQuery = DB::connection('mysql2')->table('stock as s')
            ->join('subitem as si', 'si.id', '=', 's.sub_item_id')
            ->whereIn('s.status', [1, 3])
            ->where('si.status', 1)
            ->where('si.main_ic_id', 7)
            ->whereIn('s.voucher_type', $inTypes)
            ->whereDate('s.voucher_date', '<=', $prevDate)
            ->when($rawItemId > 0, function ($q) use ($rawItemId) {
                return $q->where('s.sub_item_id', $rawItemId);
            });

        $openingIn = $openingInQuery
            ->groupBy('s.sub_item_id', 'si.sub_ic')
            ->select('s.sub_item_id', 'si.sub_ic', DB::raw("$qtyExpr as qty_in"))
            ->get()
            ->keyBy('sub_item_id');

        $openingOutQuery = DB::connection('mysql2')->table('stock as s')
            ->join('subitem as si', 'si.id', '=', 's.sub_item_id')
            ->whereIn('s.status', [1, 3])
            ->where('si.status', 1)
            ->where('si.main_ic_id', 7)
            ->whereIn('s.voucher_type', $outTypes)
            ->whereDate('s.voucher_date', '<=', $prevDate)
            ->when($rawItemId > 0, function ($q) use ($rawItemId) {
                return $q->where('s.sub_item_id', $rawItemId);
            });

        $openingOut = $openingOutQuery
            ->groupBy('s.sub_item_id', 'si.sub_ic')
            ->select('s.sub_item_id', 'si.sub_ic', DB::raw("$qtyExpr as qty_out"))
            ->get()
            ->keyBy('sub_item_id');

        $purchaseQuery = DB::connection('mysql2')->table('stock as s')
            ->join('subitem as si', 'si.id', '=', 's.sub_item_id')
            ->whereIn('s.status', [1, 3])
            ->where('si.status', 1)
            ->where('si.main_ic_id', 7)
            ->whereIn('s.voucher_type', $inTypes)
            ->whereDate('s.voucher_date', '=', $date)
            ->when($rawItemId > 0, function ($q) use ($rawItemId) {
                return $q->where('s.sub_item_id', $rawItemId);
            });

        $purchase = $purchaseQuery
            ->groupBy('s.sub_item_id', 'si.sub_ic')
            ->select('s.sub_item_id', 'si.sub_ic', DB::raw("$qtyExpr as qty_purchase"))
            ->get()
            ->keyBy('sub_item_id');

        $consumeQuery = DB::connection('mysql2')->table('stock as s')
            ->join('subitem as si', 'si.id', '=', 's.sub_item_id')
            ->whereIn('s.status', [1, 3])
            ->where('si.status', 1)
            ->where('si.main_ic_id', 7)
            ->whereIn('s.voucher_type', $outTypes)
            ->whereDate('s.voucher_date', '=', $date)
            ->when($rawItemId > 0, function ($q) use ($rawItemId) {
                return $q->where('s.sub_item_id', $rawItemId);
            });

        $consume = $consumeQuery
            ->groupBy('s.sub_item_id', 'si.sub_ic')
            ->select('s.sub_item_id', 'si.sub_ic', DB::raw("$qtyExpr as qty_consume"))
            ->get()
            ->keyBy('sub_item_id');

        $allIds = collect([])
            ->merge($openingIn->keys())
            ->merge($openingOut->keys())
            ->merge($purchase->keys())
            ->merge($consume->keys())
            ->unique()
            ->values();

        $items = [];
        $totals = [
            'opening' => 0,
            'purchase' => 0,
            'consume' => 0,
            'closing' => 0,
        ];

        foreach ($allIds as $id) {
            $subIc = $purchase[$id]->sub_ic
                ?? $consume[$id]->sub_ic
                ?? $openingIn[$id]->sub_ic
                ?? $openingOut[$id]->sub_ic
                ?? '';

            $opIn = (float) ($openingIn[$id]->qty_in ?? 0);
            $opOut = (float) ($openingOut[$id]->qty_out ?? 0);
            $opening = $opIn - $opOut;

            $p = (float) ($purchase[$id]->qty_purchase ?? 0);
            $c = (float) ($consume[$id]->qty_consume ?? 0);
            $closing = $opening + $p - $c;

            $items[] = (object) [
                'sub_item_id' => $id,
                'description' => $subIc,
                'opening' => $opening,
                'purchase' => $p,
                'consume' => $c,
                'closing' => $closing,
                'balance' => $closing,
            ];

            $totals['opening'] += $opening;
            $totals['purchase'] += $p;
            $totals['consume'] += $c;
            $totals['closing'] += $closing;
        }

        usort($items, function ($a, $b) {
            return strcmp($a->description, $b->description);
        });

        return view('FarazPackagesProduction.Reports.danaReport', compact('items', 'totals', 'date', 'm', 'rawItems', 'rawItemId'));
    }

    public function thekedarDanaDailyReport(Request $request)
    {
        $accType = Auth::user()->acc_type;
        $m = $accType === 'client' ? ($request->query('m') ?? '') : Auth::user()->company_id;

        $from = $request->query('from_date') ?: date('Y-m-d');
        $to = $request->query('to_date') ?: $from;

        if (strtotime($from) > strtotime($to)) {
            $tmp = $from;
            $from = $to;
            $to = $tmp;
        }

        // Packing (Cutting qty in KG, Packing bags qty in bags)
        $packingRows = DB::connection('mysql2')->table('production_packing as pp')
            ->leftJoin('subitem as si', 'si.id', '=', 'pp.item_id')
            ->leftJoin('machine as mac', 'mac.id', '=', 'pp.machine_id')
            ->leftJoin('operators as op', 'op.id', '=', 'pp.operator_id')
            ->where('pp.status', 1)
            ->whereBetween('pp.date', [$from, $to])
            ->orderBy('pp.date')
            ->orderBy('pp.machine_id')
            ->orderBy('si.sub_ic')
            ->select(
                DB::raw('DATE(pp.date) as report_date'),
                'pp.machine_id',
                'op.name as operator_name',
                'si.sub_ic as size_name',
                DB::raw('SUM(COALESCE(pp.packing_bags_qty,0)) as bags'),
                DB::raw('SUM(COALESCE(pp.cutting_qty,0)) as kgs')
            )
            ->groupBy(DB::raw('DATE(pp.date)'), 'pp.machine_id', 'op.name', 'si.sub_ic')
            ->get();

        // Previous balance for packing (before each day)
        $packingPrev = DB::connection('mysql2')->table('production_packing as pp')
            ->where('pp.status', 1)
            ->whereDate('pp.date', '<', $from)
            ->select(
                DB::raw('SUM(COALESCE(pp.packing_bags_qty,0)) as bags'),
                DB::raw('SUM(COALESCE(pp.cutting_qty,0)) as kgs')
            )
            ->first();

        // Dana operator (from production_mixture: produced_item_id, qty in KG, machine = mixture_machine_id, name = username)
        $danaRows = DB::connection('mysql2')->table('production_mixture as pm')
            ->leftJoin('subitem as si', 'si.id', '=', 'pm.produced_item_id')
            ->where('pm.status', 1)
            ->whereBetween('pm.date', [$from, $to])
            ->orderBy('pm.date')
            ->orderBy('pm.mixture_machine_id')
            ->orderBy('si.sub_ic')
            ->select(
                DB::raw('DATE(pm.date) as report_date'),
                'pm.mixture_machine_id as machine_id',
                'pm.username as operator_name',
                'si.sub_ic as dana_name',
                'si.pack_size as pack_size',
                DB::raw('SUM(COALESCE(pm.qty,0)) as kgs')
            )
            ->groupBy(DB::raw('DATE(pm.date)'), 'pm.mixture_machine_id', 'pm.username', 'si.sub_ic', 'si.pack_size')
            ->get()
            ->map(function ($r) {
                $pack = (float) ($r->pack_size ?? 0);
                $kgs = (float) ($r->kgs ?? 0);
                $bags = $pack > 0 ? ($kgs / $pack) : 0;
                $r->bags = $bags;
                return $r;
            });

        $danaPrev = DB::connection('mysql2')->table('production_mixture as pm')
            ->leftJoin('subitem as si', 'si.id', '=', 'pm.produced_item_id')
            ->where('pm.status', 1)
            ->whereDate('pm.date', '<', $from)
            ->select(
                DB::raw('SUM(COALESCE(pm.qty,0)) as kgs'),
                // bags based on per-row pack_size; we approximate using weighted average (sum(kgs/pack_size))
                DB::raw('SUM(CASE WHEN si.pack_size IS NOT NULL AND si.pack_size > 0 THEN (pm.qty / si.pack_size) ELSE 0 END) as bags')
            )
            ->first();

        // Printing Operator (from production_roll_printing: no_of_roll; KGS derived from subitem.pack_size if configured)
        $printingRows = DB::connection('mysql2')->table('production_roll_printing as prp')
            ->leftJoin('subitem as si', 'si.id', '=', 'prp.item_id')
            ->leftJoin('operators as op', 'op.id', '=', 'prp.operator_id')
            ->where('prp.status', 1)
            ->whereBetween('prp.date', [$from, $to])
            ->orderBy('prp.date')
            ->orderBy('prp.machine_id')
            ->orderBy('si.sub_ic')
            ->select(
                DB::raw('DATE(prp.date) as report_date'),
                'prp.machine_id',
                'op.name as operator_name',
                'si.sub_ic as size_name',
                DB::raw('SUM(COALESCE(prp.no_of_roll,0)) as rolls'),
                DB::raw('SUM(COALESCE(prp.no_of_roll,0) * COALESCE(si.pack_size,0)) as kgs')
            )
            ->groupBy(DB::raw('DATE(prp.date)'), 'prp.machine_id', 'op.name', 'si.sub_ic')
            ->get();

        $printingPrev = DB::connection('mysql2')->table('production_roll_printing as prp')
            ->where('prp.status', 1)
            ->whereDate('prp.date', '<', $from)
            ->select(
                DB::raw('SUM(COALESCE(prp.no_of_roll,0)) as rolls'),
                DB::raw('SUM(COALESCE(prp.no_of_roll,0) * COALESCE((select pack_size from subitem where id = prp.item_id),0)) as kgs')
            )
            ->first();

        // Build date list
        $dates = [];
        $cursor = $from;
        while (strtotime($cursor) <= strtotime($to)) {
            $dates[] = $cursor;
            $cursor = date('Y-m-d', strtotime($cursor . ' +1 day'));
        }

        // Group rows by date
        $packingByDate = [];
        foreach ($packingRows as $r) {
            $packingByDate[$r->report_date][] = $r;
        }
        $danaByDate = [];
        foreach ($danaRows as $r) {
            $danaByDate[$r->report_date][] = $r;
        }
        $printingByDateMachine = [];
        foreach ($printingRows as $r) {
            $printingByDateMachine[$r->report_date][$r->machine_id][] = $r;
        }

        return view('FarazPackagesProduction.Reports.thekedarDanaDailyReport', compact(
            'm',
            'from',
            'to',
            'dates',
            'packingByDate',
            'danaByDate',
            'printingByDateMachine',
            'packingPrev',
            'danaPrev',
            'printingPrev'
        ));
    }

    public function multiMixtureRolling(Request $request)
    {
        $m = $request->query('m');

        // Handle multiple IDs properly (comma-separated string from URL)
        $ids = $request->query('ids');

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No mixture IDs provided.');
        }

        // Convert comma-separated string to array
        $idArray = array_map('trim', explode(',', $ids));
        $production_mixture_ids = $idArray;

        // Get first mixture for master data (you can change logic if needed)
        $production_mixture = DB::connection('mysql2')
            ->table('production_mixture')
            ->whereIn('id', $idArray)
            ->first();

        if (!$production_mixture) {
            return redirect()->back()->with('error', 'Production mixture not found.');
        }

        // Master record
        $out_source_productions = DB::connection('mysql2')
            ->table('production_request')
            ->where('id', $production_mixture->production_order_id)
            ->first();

        if (!$out_source_productions) {
            return redirect()->back()->with('error', 'Out Source Production not found.');
        }

        // Totals for all selected mixtures
        $out_source_productions_item = DB::connection('mysql2')
            ->table('production_mixture')
            ->select(
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(used_qty) as total_used_qty'),
                'produced_item_id'
            )
            ->whereIn('id', $idArray)
            ->first();

        // Detail items for all selected mixtures
        $out_source_productions_details = DB::connection('mysql2')
            ->table('production_mixture_data')
            ->whereIn('production_mixture_id', $idArray)
            ->get();

        $categories_id = explode(',', Auth::user()->categories_id ?? '');

        $sub_item = DB::connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)  // Uncomment if needed
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $machines = DB::connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)
            ->get();

        $operators = DB::connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)
            ->get();

        $shifts = DB::connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)
            ->get();

        return view(
            'FarazPackagesProduction.ProductionMixture.ProcessedMixtureRolling',
            compact(
                'production_mixture',
                'production_mixture_ids',
                'out_source_productions',
                'out_source_productions_item',
                'out_source_productions_details',
                'sub_item',
                'machines',
                'operators',
                'shifts',
                'm'
            )
        );
    }


    public function mixtureRolling(Request $request)
    {
        $m = $request->m;

        $production_mixture = DB::connection('mysql2')
            ->table('production_mixture')
            ->where('id', $request->id)
            ->first();

        $production_mixture_ids = [$request->id];
        // Master record
        $out_source_productions = DB::connection('mysql2')
            ->table('production_request')
            ->where('id', $production_mixture->production_order_id)
            ->first();

        if (!$out_source_productions) {
            return redirect()->back()->with('error', 'Out Source Production not found.');
        }

        // Totals
        $out_source_productions_item = DB::connection('mysql2')
            ->table('production_mixture')
            ->select(
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(used_qty) as total_used_qty'),
                'produced_item_id'
            )
            ->where('id', $request->id)
            ->first();

        // Detail items
        $out_source_productions_details = DB::connection('mysql2')
            ->table('production_mixture_data')
            ->where('production_mixture_id', $request->id)
            ->get();

        $categories_id = explode(',', Auth::user()->categories_id);

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.ProcessedMixtureRolling', compact('production_mixture', 'production_mixture_ids', 'out_source_productions', 'out_source_productions_item', 'out_source_productions_details', 'sub_item', 'machines', 'operators', 'shifts', 'm'));
    }

    public function viewProductionRollingList()
    {
        $rollingList = ProductionRolling::with('productionOrder')
            ->withCount('printings as usage_count')
            ->where('status', '=', 1)
            ->where('roll_qty', '!=', 0)
            ->get();
        $m = $this->m;
        return view('FarazPackagesProduction.ProductionMixture.viewProductionRolling', compact('rollingList', 'm'));
    }

    public function viewProductionRollPrintingList()
    {
        $rollPricingList = ProductionRollPrinting::with(
            'productionRoll.productionOrder',
            'brand',
            'color'
        )
            ->withCount('cuttingAndSealings as usage_count')
            ->where('status', '=', 1)->get();
        $m = $this->m;
        return view('FarazPackagesProduction.ProductionMixture.viewProductionRollPrinting', compact('rollPricingList', 'm'));
    }

    public function viewProductionCuttingAndSealingList()
    {
        $cuttingAndSealingList = ProductionCuttingAndSealing::with(
            'printedRoll.productionRoll.productionOrder',
            'galaCutting',
            'packing'
        )
            ->where('status', '=', 1)->get();
        $m = $this->m;
        return view('FarazPackagesProduction.ProductionMixture.viewProductionCuttingAndSealing', compact('cuttingAndSealingList', 'm'));
    }

    public function viewProductionPackingList()
    {
        $packingList = ProductionPacking::with([
            'cuttingAndSealing.printedRoll.productionRoll.productionOrder',
            'galaCutting.cuttingAndSealing.printedRoll.productionRoll.productionOrder',
        ])->where('status', 1)->get();

        $m = $this->m;
        return view('FarazPackagesProduction.ProductionMixture.viewProductionPacking', compact('packingList', 'm'));
    }

    public function viewProductionGalaCuttingList()
    {
        $galaCuttingList = ProductionGalaCutting::with(
            'cuttingAndSealing.printedRoll.productionRoll.productionOrder',
            'packing'
        )
            ->where('status', '=', 1)->get();
        $m = $this->m;
        return view('FarazPackagesProduction.ProductionMixture.viewProductionGalaCutting', compact('galaCuttingList', 'm'));
    }

    public function rollPrinting(Request $request)
    {
        $m = $request->m;



        // Totals
        $out_source_productions_item = DB::connection('mysql2')
            ->table('production_rolling')
            ->select(
                DB::raw('SUM(rolls_qty_kg) as total_qty'),
                DB::raw('SUM(printed_rolls_qty_kg) as total_used_qty'),
                'date',
                'item_id',
                'id'
            )
            ->where('id', $request->id)
            ->first();

        // Detail items
        $out_source_productions_details = DB::connection('mysql2')
            ->table('production_rolling')
            ->where('id', $request->id)
            ->get();

        $categories_id = explode(',', Auth::user()->categories_id);

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            // ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        $brands = DB::Connection('mysql2')->table('brands')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $colors = DB::Connection('mysql2')->table('colors')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.ProcessedRollPrinting', compact('out_source_productions_item', 'out_source_productions_details', 'sub_item', 'machines', 'operators', 'shifts', 'brands', 'colors', 'm'));
    }

    public function bulkRollPrinting(Request $request)
    {
        $m = $request->m;
        $id = $request->id;

        $production_order = DB::connection('mysql2')
            ->table('production_request')->where('status', 1)->get();

        // Totals
        if ($id) {
            $out_source_productions_item = DB::connection('mysql2')
                ->table('production_rolling')
                ->select(
                    'roll_qty as total_qty',
                    'printed_rolls_qty_kg as total_used_qty',
                    'date',
                    'item_id',
                    'id'
                )
                ->where('roll_qty', '>', 0)
                ->where('production_order_id', $id)
                ->get();
        } else {
            $out_source_productions_item = collect([]);
        }



        $categories_id = explode(',', Auth::user()->categories_id);

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            // ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        $brands = DB::Connection('mysql2')->table('brands')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $colors = DB::Connection('mysql2')->table('colors')
            ->select('id', 'name')
            ->where('status', '=', 1)->get(); // dd($out_source_productions_item);
        return view('FarazPackagesProduction.ProductionMixture.ProcessedBulkRollPrinting', compact('id', 'out_source_productions_item', 'sub_item', 'machines', 'operators', 'shifts', 'brands', 'colors', 'production_order', 'm'));
    }

    public function getRollingItemsForBulkPrinting(Request $request)
    {
        $m = $request->m;
        $production_order_id = $request->production_order_id;

        $items = DB::connection('mysql2')
            ->table('production_rolling as pr')
            ->join('subitem as s', 'pr.item_id', '=', 's.id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->select(
                'pr.id',
                'pr.item_id',
                'pr.rolls_qty_kg as total_qty',
                'pr.printed_rolls_qty_kg as total_used_qty',
                'pr.date',
                's.item_code',
                's.sub_ic',
                'u.uom_name'
            )
            ->where('pr.production_order_id', $production_order_id)
            ->where('roll_qty', '>', 0)
            ->get();

        return response()->json(['items' => $items]);
    }

    public function getPrintedRollItemsForBulkCuttingAndSealing(Request $request)
    {
        $m = $request->m;
        $production_order_id = $request->production_order_id;

        $items = DB::connection('mysql2')
            ->table('production_rolling as pr')
            ->join('production_roll_printing as prp', 'pr.id', '=', 'prp.production_rolling_id')
            ->join('subitem as s', 'prp.item_id', '=', 's.id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->select(
                'prp.id',
                'prp.item_id',
                'prp.no_of_roll as total_qty',
                'prp.used_no_of_roll as total_used_qty',
                'prp.date',
                's.item_code',
                's.sub_ic',
                'u.uom_name'
            )
            ->where('pr.production_order_id', $production_order_id)
            ->where('prp.no_of_roll', '>', 0)
            ->get();

        return response()->json(['items' => $items]);
    }

    public function getSealingItemForBulkGalaCutting(Request $request)
    {
        $m = $request->m;
        $production_order_id = $request->production_order_id;

        $items = DB::connection('mysql2')
            ->table('production_rolling as pr')
            ->join('production_roll_printing as prp', 'pr.id', '=', 'prp.production_rolling_id')
            ->join('production_cutting_and_sealing as pcs', 'prp.id', '=', 'pcs.printed_rolling_id')
            ->join('subitem as s', 'pcs.item_id', '=', 's.id')
            ->join('sub_category as sc', 's.sub_category_id', '=', 'sc.id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->select(
                'pcs.id',
                'pcs.item_id',
                'pcs.qty as total_qty',
                'pcs.used_qty as total_used_qty',
                'pcs.date',
                's.item_code',
                's.sub_ic',
                'u.uom_name'
            )
            ->where('pr.production_order_id', $production_order_id)
            ->where('pcs.qty', '>', 0)
            ->where('sc.type', '=', 'Gala Cutting')
            ->get();

        return response()->json(['items' => $items]);
    }

    public function getCuttingAndSealingItemsForBulkPacking(Request $request)
    {
        $m = $request->m;
        $production_order_id = $request->production_order_id;
        $cutting_type = $request->cutting_type;

        if ($cutting_type == 'cutting and sealing') {
            $items = DB::connection('mysql2')
                ->table('production_rolling as pr')
                ->join('production_roll_printing as prp', 'pr.id', '=', 'prp.production_rolling_id')
                ->join('production_cutting_and_sealing as pcs', 'prp.id', '=', 'pcs.printed_rolling_id')
                ->join('subitem as s', 'pcs.item_id', '=', 's.id')
                ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
                ->join('sub_category as sc', 's.sub_category_id', '=', 'sc.id')

                ->select(
                    'pcs.id',
                    'pcs.item_id',
                    'pcs.qty as total_qty',
                    'pcs.used_qty as total_used_qty',
                    'pcs.date',
                    's.item_code',
                    's.sub_ic',
                    'u.uom_name',
                    DB::raw("'' as cutting_type")
                )
                ->where('pr.production_order_id', $production_order_id)
                ->where('pcs.qty', '>', 0)
                ->whereNull('sc.type')
                ->get();

            return response()->json(['items' => $items]);
        } elseif ($cutting_type == 'gala cutting') {

            $items = DB::connection('mysql2')
                ->table('production_rolling as pr')
                ->join('production_roll_printing as prp', 'pr.id', '=', 'prp.production_rolling_id')
                ->join('production_cutting_and_sealing as pcs', 'prp.id', '=', 'pcs.printed_rolling_id')
                ->join('production_gala_cutting as pgs', 'pcs.id', '=', 'pgs.cutting_sealing_id')
                ->join('subitem as s', 'pgs.item_id', '=', 's.id')
                ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
                ->select(
                    'pgs.id',
                    'pgs.item_id',
                    'pgs.gala_qty as total_qty',
                    'pgs.used_qty as total_used_qty',
                    'pgs.date',
                    's.item_code',
                    's.sub_ic',
                    'u.uom_name',
                    DB::raw("'' as cutting_type")
                )
                ->where('pr.production_order_id', $production_order_id)
                ->where('pgs.gala_qty', '>', 0)
                ->get();

            return response()->json(['items' => $items]);
        } else {

            // ── Common base ───────────────────────────────────────
            $baseQuery = DB::connection('mysql2')
                ->table('production_rolling as pr')
                ->join('production_roll_printing as prp', 'pr.id', '=', 'prp.production_rolling_id')
                ->join('production_cutting_and_sealing as pcs', 'prp.id', '=', 'pcs.printed_rolling_id')
                ->join('subitem as s', 'pcs.item_id', '=', 's.id')
                ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
                ->where('pr.production_order_id', $production_order_id);

            // ── Simple cut & seal items ───────────────────────────
            $simpleItems = (clone $baseQuery)
                ->join('sub_category as sc', 's.sub_category_id', '=', 'sc.id')

                ->where('pcs.qty', '>', 0)
                ->whereNull('sc.type')

                ->select(
                    'pcs.id',
                    'pcs.item_id',
                    'pcs.qty as total_qty',
                    'pcs.used_qty as total_used_qty',
                    'pcs.date',
                    's.item_code',
                    's.sub_ic',
                    'u.uom_name',
                    DB::raw("'' as cutting_type")   // ← helps frontend distinguish
                )
                ->get();

            // ── Gala cutting items ────────────────────────────────
            $galaItems = (clone $baseQuery)
                ->join('production_gala_cutting as pgs', 'pcs.id', '=', 'pgs.cutting_sealing_id')
                ->where('pgs.gala_qty', '>', 0)
                ->select(
                    'pgs.id',
                    'pgs.item_id',
                    'pgs.gala_qty as total_qty',
                    'pgs.used_qty as total_used_qty',
                    'pgs.date',
                    's.item_code',
                    's.sub_ic',
                    'u.uom_name',
                    DB::raw("'gala' as cutting_type")     // ← helps frontend distinguish
                )
                ->get();

            // Combine both collections
            $allItems = $simpleItems->merge($galaItems);

            return response()->json([
                'items' => $allItems,
                'simple_count' => $simpleItems->count(),
                'gala_count' => $galaItems->count(),
            ]);
        }
    }

    public function cuttingAndSealing(Request $request)
    {
        $m = $request->m;



        // Totals
        $out_source_productions_item = DB::connection('mysql2')
            ->table('production_roll_printing')
            ->select(
                DB::raw('SUM(no_of_roll) as total_qty'),
                DB::raw('SUM(used_no_of_roll) as total_used_qty'),
                'date',
                'item_id',
                'id'
            )
            ->where('id', $request->id)
            ->first();

        // Detail items
        $out_source_productions_details = DB::connection('mysql2')
            ->table('production_roll_printing')
            ->where('id', $request->id)
            ->get();

        $categories_id = explode(',', Auth::user()->categories_id);

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();
        $sub_item_wastage = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 15)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();
        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.ProcessedCuttingAndSealing', compact('out_source_productions_item', 'out_source_productions_details', 'sub_item', 'sub_item_wastage', 'machines', 'operators', 'shifts', 'm'));
    }

    public function bulkCuttingAndSealing(Request $request)
    {
        $m = $request->m;
        $id = $request->id;

        $production_order = DB::connection('mysql2')
            ->table('production_request')->where('status', 1)->get();

        // Totals
        if ($id) {
            $out_source_productions_item = DB::connection('mysql2')
                ->table('production_roll_printing')
                ->select(
                    'no_of_roll as total_qty',
                    'used_no_of_roll as total_used_qty',
                    'date',
                    'item_id',
                    'id'
                )
                ->where('no_of_roll', '>', 0)
                ->whereIn('production_rolling_id', function ($query) use ($id) {
                    $query->select('id')
                        ->from('production_rolling')
                        ->where('production_order_id', $id);
                })
                ->get();
        } else {
            $out_source_productions_item = collect([]);
        }

        $categories_id = explode(',', Auth::user()->categories_id);

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();
        $sub_item_wastage = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 15)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();
        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $brands = DB::Connection('mysql2')->table('brands')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $colors = DB::Connection('mysql2')->table('colors')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.ProcessedBulkCuttingAndSealing', compact('id', 'out_source_productions_item', 'sub_item', 'sub_item_wastage', 'machines', 'brands', 'colors', 'operators', 'shifts', 'production_order', 'm'));
    }

    public function galaCutting(Request $request)
    {
        $m = $request->m;



        // Totals
        $out_source_productions_item = DB::connection('mysql2')
            ->table('production_cutting_and_sealing')
            ->select(
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(used_qty) as total_used_qty'),
                'date',
                'item_id',
                'id'
            )
            ->where('id', $request->id)
            ->first();

        // Detail items
        $out_source_productions_details = DB::connection('mysql2')
            ->table('production_cutting_and_sealing')
            ->where('id', $request->id)
            ->get();

        $categories_id = explode(',', Auth::user()->categories_id);

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $sub_item_wastage = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 15)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();



        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.ProcessedGalaCutting', compact('out_source_productions_item', 'out_source_productions_details', 'sub_item', 'sub_item_wastage', 'machines', 'operators', 'shifts', 'm'));
    }

    public function bulkgalaCutting(Request $request)
    {
        $m = $request->m;
        $id = $request->id;

        $production_order = DB::connection('mysql2')
            ->table('production_request')->where('status', 1)->get();

        // Totals
        if ($id) {
            $out_source_productions_item = DB::connection('mysql2')
                ->table('production_cutting_and_sealing')
                ->select(
                    'roll_qty as total_qty',
                    'printed_roll_qty as total_used_qty',
                    'date',
                    'item_id',
                    'id'
                )
                ->where('roll_qty', '>', 0)
                ->where('production_order_id', $id)
                ->get();
        } else {
            $out_source_productions_item = collect([]);
        }



        $categories_id = explode(',', Auth::user()->categories_id);

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            // ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $sub_item_wastage = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 15)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        $brands = DB::Connection('mysql2')->table('brands')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $colors = DB::Connection('mysql2')->table('colors')
            ->select('id', 'name')
            ->where('status', '=', 1)->get(); // dd($out_source_productions_item);
        return view('FarazPackagesProduction.ProductionMixture.ProcessedBulkGalaCutting', compact('id', 'out_source_productions_item', 'sub_item', 'sub_item_wastage', 'machines', 'operators', 'shifts', 'brands', 'colors', 'production_order', 'm'));
    }

    public function bulkPacking(Request $request)
    {
        $m = $request->m;
        $id = $request->id;

        $production_order = DB::connection('mysql2')
            ->table('production_request')->where('status', 1)->get();

        // Totals
        if ($id) {
            $out_source_productions_item = DB::connection('mysql2')
                ->table('production_cutting_and_sealing')
                ->select(
                    'roll_qty as total_qty',
                    'printed_roll_qty as total_used_qty',
                    'date',
                    'item_id',
                    'id'
                )
                ->where('roll_qty', '>', 0)
                ->where('production_order_id', $id)
                ->get();
        } else {
            $out_source_productions_item = collect([]);
        }



        $categories_id = explode(',', Auth::user()->categories_id);
        $sub_item_wastage = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 15)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            // ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        $brands = DB::Connection('mysql2')->table('brands')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $colors = DB::Connection('mysql2')->table('colors')
            ->select('id', 'name')
            ->where('status', '=', 1)->get(); // dd($out_source_productions_item);
        return view('FarazPackagesProduction.ProductionMixture.ProcessedBulkPacking', compact('id', 'out_source_productions_item', 'sub_item', 'sub_item_wastage', 'machines', 'operators', 'shifts', 'brands', 'colors', 'production_order', 'm'));
    }
    public function packing(Request $request)
    {
        $m = $request->m;

        if ($request->cutting_type == 'cutting and sealing') {
            // Totals
            $out_source_productions_item = DB::connection('mysql2')
                ->table('production_cutting_and_sealing')
                ->select(
                    DB::raw('SUM(qty) as total_qty'),
                    DB::raw('SUM(used_qty) as total_used_qty'),
                    'date',
                    'item_id',
                    'id'
                )
                ->where('id', $request->id)
                ->first();

            // Detail items
            $out_source_productions_details = DB::connection('mysql2')
                ->table('production_cutting_and_sealing')
                ->where('id', $request->id)
                ->get();
        } else {
            $out_source_productions_item = DB::connection('mysql2')
                ->table('production_gala_cutting')
                ->select(
                    DB::raw('SUM(gala_qty) as total_qty'),
                    DB::raw('SUM(used_qty) as total_used_qty'),
                    'date',
                    'item_id',
                    'id'
                )
                ->where('id', $request->id)
                ->first();

            // Detail items
            $out_source_productions_details = DB::connection('mysql2')
                ->table('production_gala_cutting')
                ->where('id', $request->id)
                ->get();
        }

        $categories_id = explode(',', Auth::user()->categories_id);
        $sub_item_wastage = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 15)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();
        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        $cutting_type = $request->cutting_type;
        return view('FarazPackagesProduction.ProductionMixture.ProcessedPacking', compact('out_source_productions_item', 'out_source_productions_details', 'sub_item', 'sub_item_wastage', 'machines', 'operators', 'shifts', 'cutting_type', 'm'));
    }
}
