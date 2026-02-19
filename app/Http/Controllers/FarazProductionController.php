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
            ->where('s.main_ic_id', '=', 7)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();

        $raw_material = DB::Connection('mysql2')->table('subitem')
            ->select('id', 'sub_ic', 'uom', 'item_code')
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
        $mixtureData = ProductionMixtureData::where('status', 1)->where('production_mixture_id', $mixture->id)->get();

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

        $raw_material = DB::Connection('mysql2')->table('subitem')
            ->select('id', 'sub_ic', 'uom', 'item_code')
            ->where('status', '=', 1)->where('main_ic_id', '=', 7)->get();


        return view('FarazPackagesProduction.ProductionMixture.editMixture', compact('mixture', 'mixtureData', 'sub_item', 'raw_material', 'm'));
    }

    public function mixtureRolling(Request $request)
    {
        $m = $request->m;

        $production_mixture = DB::connection('mysql2')
            ->table('production_mixture')
            ->where('id', $request->id)
            ->first();
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
            ->table('production_mixture_data')
            ->select(
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(used_qty) as total_used_qty')
            )
            ->where('production_mixture_id', $request->id)
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

        return view('FarazPackagesProduction.ProductionMixture.ProcessedMixtureRolling', compact('production_mixture', 'out_source_productions', 'out_source_productions_item', 'out_source_productions_details', 'sub_item', 'machines', 'operators', 'shifts', 'm'));
    }

    public function viewProductionRollingList()
    {
        $rollingList = ProductionRolling::with('productionOrder')
            ->where('status', '=', 1)->get();
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
            ->where('status', '=', 1)->get();
        $m = $this->m;
        return view('FarazPackagesProduction.ProductionMixture.viewProductionRollPrinting', compact('rollPricingList', 'm'));
    }

    public function viewProductionCuttingAndSealingList()
    {
        $cuttingAndSealingList = ProductionCuttingAndSealing::with(
            'printedRoll.productionRoll.productionOrder'
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
            'cuttingAndSealing.printedRoll.productionRoll.productionOrder'
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
                DB::raw('SUM(roll_qty) as total_qty'),
                DB::raw('SUM(printed_roll_qty) as total_used_qty'),
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

        $brands = DB::Connection('mysql2')->table('brands')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $colors = DB::Connection('mysql2')->table('colors')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.ProcessedRollPrinting', compact('out_source_productions_item', 'out_source_productions_details', 'sub_item', 'machines', 'operators', 'shifts', 'brands', 'colors', 'm'));
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

        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.ProcessedCuttingAndSealing', compact('out_source_productions_item', 'out_source_productions_details', 'sub_item', 'machines', 'operators', 'shifts', 'm'));
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

        $machines = DB::Connection('mysql2')->table('machine')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $operators = DB::Connection('mysql2')->table('operators')
            ->select('id', 'name')
            ->where('status', '=', 1)->get();

        $shifts = DB::Connection('mysql')->table('shift_type')
            ->select('id', 'shift_type_name')
            ->where('status', '=', 1)->get();

        return view('FarazPackagesProduction.ProductionMixture.ProcessedGalaCutting', compact('out_source_productions_item', 'out_source_productions_details', 'sub_item', 'machines', 'operators', 'shifts', 'm'));
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
        return view('FarazPackagesProduction.ProductionMixture.ProcessedPacking', compact('out_source_productions_item', 'out_source_productions_details', 'sub_item', 'machines', 'operators', 'shifts', 'cutting_type', 'm'));
    }
}
