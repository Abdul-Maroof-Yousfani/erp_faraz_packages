<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ProductionPlane;
use App\Models\Sales_Order;
use App\Models\InventoryMaster\Machine;
use App\Models\InventoryMaster\Operator;
use Illuminate\Support\Facades\DB;
use Exception;
use Auth;

class ProductionReportController extends Controller
{

    public function hdpePipeLineReport(Request $request)
    {
        // byers_name
        if($request->ajax())
        {

            $mainData = DB::connection('mysql2')->table('production_plane as pp')
                        ->join('production_plane_data as ppd', 'pp.id', '=', 'ppd.master_id')
                        ->join('sales_order as so', 'so.id', '=', 'pp.sales_order_id')
                        ->select('ppd.finish_goods_id', 'pp.sales_order_id', DB::raw("CONCAT(pp.wall_thickness_1, ' MM') as wall_thickness"),'pp.customer','so.purchase_order_no')
                        ->where('pp.id', $request->pp_id)
                        ->first();


            $list = DB::connection('mysql2')->table('production_plane as pp')
                        ->join('machine_proccesses as mp', 'mp.production_plane_id', '=', 'pp.id')
                        ->join('machine_proccess_datas as mpd', 'mp.id', '=', 'mpd.machine_proccess_id')
                        ->join('operators as o', 'mpd.operator_id', '=', 'o.id')
                        ->join('machine as m', 'mpd.machine_id', '=', 'm.id')
                        ->select(
                            'mpd.recieved_date',
                            'mpd.shift',
                            'mpd.batch_no',
                            'mpd.request_qty',
                            'o.name',
                            'm.name as machineName',
                            DB::raw("IFNULL(mpd.remarks, '-') as remarks")
                        )
                        ->where('pp.id', $request->pp_id);

                        if(!empty($request->machine_id))
                        {
                            $list = $list->where('mpd.machine_id',$request->machine_id);
                        }
                        
                        if(!empty($request->operator_id))
                        {
                            $list = $list->where('mpd.operator_id', $request->operator_id);
                        }
                        
                        if(!empty($request->fromDate) && !empty($request->toDate))
                        {
                            $list = $list->where('mpd.recieved_date','>=', $request->fromDate)
                                            ->where('mpd.recieved_date','<=', $request->toDate);
                        }
                        
                        if(!empty($request->fromDate) && empty($request->toDate))
                        {
                            $list = $list->where('mpd.recieved_date','>=', $request->fromDate);
                        }
                        
                        if(empty($request->fromDate) && !empty($request->toDate))
                        {
                            $list = $list->where('mpd.recieved_date','<=', $request->toDate);
                        }

                        $list = $list->get();


                        $machineName = $request->machineName;


            return view('Production.ProductionReport.ajax.hdpePipeLineReportAjax',compact('mainData','list','machineName'));
        }

        $ProductionPlane = ProductionPlane::where('status',1)->where('type',1)->get();
        $Machine = Machine::where('status',1)->get();
        $Operator = Operator::where('status',1)->get();

        return view('Production.ProductionReport.hdpePipeLineReport', compact('ProductionPlane','Machine','Operator'));
    }

    public function remainingOrderDetail(Request $request)
    {

        if($request->ajax())
        {
            $list = DB::connection('mysql2')->table('production_plane as pp')
                        ->join('production_plane_data as ppd', 'pp.id', '=', 'ppd.master_id')
                        ->join('machine_proccesses as mp', 'mp.production_plane_id', '=', 'pp.id')
                        ->join('machine_proccess_datas as mpd', 'mp.id', '=', 'mpd.machine_proccess_id')
                        ->join('sales_order as so', 'so.id', '=', 'pp.sales_order_id')
                        ->join('sales_order_data as sod', function ($join) {
                            $join->on('sod.master_id', '=', 'pp.sales_order_id')
                                ->on('sod.item_id', '=', 'ppd.finish_goods_id');
                        })
                        ->select('so.so_no', 'pp.order_no', 'ppd.finish_goods_id', 'pp.customer', 'sod.qty','so.purchase_order_no', DB::raw('SUM(mpd.request_qty) as total_request_qty'));
                       
                        if(!empty($request->pp_id))
                        {
                            $list = $list->where('pp.id',$request->pp_id);
                        }
                        if(!empty($request->so_id))
                        {
                            $list = $list->where('so.id',$request->so_id);
                        }
                        if(!empty($request->customer))
                        {
                            $list = $list->where('pp.customer',$request->customer);
                        }
                        
                        $list = $list->groupBy('pp.id')->get();

            return view('Production.ProductionReport.ajax.remainingOrderDetailAjax',compact('list'));

        }


        $Sales_Order = Sales_Order::where('status',1)->get();
        $ProductionPlane = ProductionPlane::where('status',1)->where('type',1)->get();

        return view('Production.ProductionReport.remainingOrderDetail', compact('ProductionPlane','Sales_Order'));
    }
    
    public function incomingInspection(Request $request)
    {
        return view('Production.ProductionReport.incomingInspection');

    }


    public function rawmaterialrequisition(Request $request)
    {
        return view('Production.ProductionReport.rawmaterialrequisition');

    }


    public function rawmaterialrequirementsreport(Request $request)
    {
        return view('Production.ProductionReport.rawmaterialrequirementsreport');

    }


    public function rawmaterialconsumptionreport(Request $request)
    {
        return view('Production.ProductionReport.rawmaterialconsumptionreport');

    }

    public function copperworkorderstatusreport(Request $request)
    {
        return view('Production.ProductionReport.copperworkorderstatusreport');

    }

    


    public function stockflowfrom(Request $request)
    {
        return view('Production.ProductionReport.stockflowfrom');

    }

    public function  stockreport(Request $request)
    {
        return view('Production.ProductionReport.stockreport');

    }


}
