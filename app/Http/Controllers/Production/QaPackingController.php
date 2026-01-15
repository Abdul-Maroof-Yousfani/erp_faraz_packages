<?php

namespace App\Http\Controllers\Production;

use App\Models\Subitem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sales_Order;
use App\Models\Production\Packing;
use App\Models\Production\PackingData;
use App\Models\Production\QaTest;
use App\Models\Production\QcPacking;
use App\Models\Production\QcPackingData;
use App\Models\Production\PackingQcTesting;
use App\Models\Production\QcValue;
use App\Models\Production\QcValueData;

use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class QaPackingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $data = DB::connection('mysql2')->table('qc_packings')->where('status', 1);
            $data = DB::connection('mysql2')->table('qc_packings as qp')
                // ->join('packings as p', 'p.id', '=', 'qp.packing_list_id')
                // ->join('sales_order as so', 'so.id', '=', 'qp.so_id')
                // ->leftJoin('delivery_note as dn', 'dn.qc_packing_id', '=', 'qp.id')
                ->join('production_plane as pp', 'pp.id', '=', 'qp.production_plan_id')
                ->select('qp.id', 'qp.qc_by', 'qp.qc_packing_date', 'qp.customer_name', 'pp.order_no', 'qp.id as qp_id');
            // ->get();
            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->where([
                ['qp.status', 1],
                ['pp.status', 1]
            ])->orderBy('qp.id', 'desc')->get();

            return view('Production.QaPacking.ajax.listQaPackingAjax', compact('data'));
        }

        return view('Production.QaPacking.listQaPacking');
    }

    public function create()
    {
        $Sales_Order = Sales_Order::where('status', 1)->get();
        $QaTest = QaTest::where('status', 1)->get();
        $material_requisition = DB::Connection('mysql2')
            ->table('material_requisitions as mr')
            ->leftJoin('machine_proccesses as mp', 'mp.mr_id', '=', 'mr.id')
            ->leftJoin('machine_proccess_datas as mpd', 'mpd.machine_proccess_id', '=', 'mp.id')
            ->join('production_plane as pp', 'pp.id', '=', 'mr.production_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->select('mr.id', 'pp.id AS pp_id', 'pp.order_no', 'pp.order_date', 's.sub_ic')
            ->where('pp.status', 1)
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('mp..machine_process_stage', 1)
            ->whereNotNull('mp.id') // Exclude rows where `machine_proccesses` is null
            ->groupBy('mr.id')
            ->get();

        return view('Production.QaPacking.createQaPacking', compact('material_requisition','Sales_Order','QaTest'));
    }

    public function store(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {
            $QaPacking = new QcPacking;
            $QaPacking->so_id = $request->so_id ?? 0;
            $QaPacking->material_requisition_id = $request->material_requisition_id;
            $QaPacking->production_plan_id = $request->pp_id ?? 0;
            $QaPacking->packing_list_id = $request->packing_list_id ?? 0;
            $QaPacking->dc_id = $request->delivery_challan_id ?? 0;
            $QaPacking->customer_name = $request->customer_name ?? '';
            $QaPacking->customer_id = $request->customer_id ?? 0;
            $QaPacking->qc_packing_date = $request->qc_packing_date;
            $QaPacking->qc_by = $request->qc_by;
            $QaPacking->status = 1;
            $QaPacking->username = Auth::user()->name;
            $QaPacking->save();
            $id = $QaPacking->id;

            foreach ($request->qc_test_id as $key => $value) {
                if ($request->input('checkBox' . $value) == 1) {
                    $qa_packing_data = new QcPackingData;
                    $qa_packing_data->qc_packing_id = $id;
                    $qa_packing_data->qa_test_id = $value;
                    $qa_packing_data->standard_value = $request->input('standard_value' . $value) ?? '';
                    $qa_packing_data->test_value = $request->input('test_value' . $value) ?? '';
                    $qa_packing_data->test_type = $request->input('test_type' . $value);
                    $qa_packing_data->test_status = $request->input('test_status' . $value);
                    $qa_packing_data->status = 1;
                    $qa_packing_data->username = Auth::user()->name;
                    $qa_packing_data->save();
                }
            }

            // DB::connection('mysql2')->table('packings as p')
            //     ->where('p.status', 1)
            //     ->where('p.id', $request->packing_list_id)->update([
            //             'qc_status' => 2
            //         ]);

            DB::connection('mysql2')->table('material_requisition_datas AS mrd')
                ->join('material_requisitions as mr', 'mr.id', '=', 'mrd.mr_id')
                ->join('machine_proccess_datas as mpd', 'mpd.mr_data_id', '=', 'mrd.id')
                ->where('mpd.machine_process_stage', 1)
                ->where('mpd.status', 1)
                ->where('mr.id', $request->material_requisition_id)
                ->update(['mpd.machine_process_stage' => 2]); // 2 = QC done, 3 = Packaging done

            DB::Connection('mysql2')->commit();

            return redirect()->back()->with('dataInsert', 'Sccessfully Saved');
        } catch (QueryException $e) {
            // Log or handle the exception as needed
            DB::Connection('mysql2')->rollback();

            return redirect()->back()->withErrors('Error inserting record. Please try again.')->withInput();
        }
    }

    public function viewQaPackingDetail(Request $request)
    {
        $qc_test = DB::Connection('mysql2')->table('qc_packings AS qp')
            ->join('production_plane AS pp', 'pp.id', '=', 'qp.production_plan_id')
            ->join('material_requisitions AS mr', 'mr.id', '=', 'qp.material_requisition_id')
            ->where('qp.id', $request->id)->first();

        $qc_test_data = DB::Connection('mysql2')->table('qc_packing_datas AS qpd')
            ->join('qa_tests AS qt', 'qt.id', '=', 'qpd.qa_test_id')
            ->where('qpd.qc_packing_id', $request->id)->get();

        return view('Production.QaPacking.viewQaPackingDetail', compact('qc_test', 'qc_test_data'));
    }

    public function show($id)
    {
        $packing = DB::connection('mysql2')->table('packings as p')
            ->join('sales_order as so', 'p.so_id', '=', 'so.id')
            ->join('subitem as s', 's.id', '=', 'p.id')
            ->join('sub_category as sc', 's.sub_category_id', '=', 'sc.id')
            ->join('sales_order_data as sod', function ($join) {
                $join->on('p.item_id', '=', 'sod.item_id')
                    ->on('sod.master_id', '=', 'p.so_id');
            })
            ->where('p.id', $id)
            ->select('s.sub_ic', 'sc.sub_category_name', 'so.description', 'so.so_no', 'so.purchase_order_no', 'p.packing_list_no', 'p.customer_name', 'p.deliver_to', 'p.packing_date')
            ->first();

        $packing_data = QcPackingData::where('packing_id', $id)->where('status', 1)->get();

        return view('Production.QaPacking.viewQaPacking', compact('packing', 'packing_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $QaPacking = QcPacking::where('id', $id)->where('status', 1)->first();

        if (!$QaPacking) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        $Sales_Order = Sales_Order::where('status', 1)->get();
        $QaTest = QaTest::where('status', 1)->get();
        $material_requisition = DB::Connection('mysql2')
            ->table('material_requisitions as mr')
            ->leftJoin('machine_proccesses as mp', 'mp.mr_id', '=', 'mr.id')
            ->leftJoin('machine_proccess_datas as mpd', 'mpd.machine_proccess_id', '=', 'mp.id')
            ->join('production_plane as pp', 'pp.id', '=', 'mr.production_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->select('mr.id', 'pp.id AS pp_id', 'pp.order_no', 'pp.order_date', 's.sub_ic')
            ->where('pp.status', 1)
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->groupBy('mr.id')
            ->get();

        // Get existing QC data
        $qc_packing_data = QcPackingData::where('qc_packing_id', $id)->where('status', 1)->get();

        return view('Production.QaPacking.updateQaPacking', compact('QaPacking', 'material_requisition', 'Sales_Order', 'QaTest', 'qc_packing_data'));
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
        DB::Connection('mysql2')->beginTransaction();

        try {
            $QaPacking = QcPacking::find($id);

            if (!$QaPacking) {
                return redirect()->back()->withErrors('Record not found')->withInput();
            }

            // Update main QC packing record
            $QaPacking->qc_packing_date = $request->qc_packing_date;
            $QaPacking->qc_by = $request->qc_by;
            $QaPacking->username = Auth::user()->name;
            $QaPacking->save();

            // Delete existing QC packing data
            QcPackingData::where('qc_packing_id', $id)->update(['status' => 0]);

            // Insert updated QC packing data
            if ($request->qc_test_id) {
                foreach ($request->qc_test_id as $key => $value) {
                    if ($request->input('checkBox' . $value) == 1) {
                        $qa_packing_data = new QcPackingData;
                        $qa_packing_data->qc_packing_id = $id;
                        $qa_packing_data->qa_test_id = $value;
                        $qa_packing_data->standard_value = $request->input('standard_value' . $value) ?? '';
                        $qa_packing_data->test_value = $request->input('test_value' . $value) ?? '';
                        $qa_packing_data->test_type = $request->input('test_type' . $value);
                        $qa_packing_data->test_status = $request->input('test_status' . $value);
                        $qa_packing_data->status = 1;
                        $qa_packing_data->username = Auth::user()->name;
                        $qa_packing_data->save();
                    }
                }
            }

            DB::Connection('mysql2')->commit();

            return redirect('Production/QaPacking/')->with('success', 'Record updated successfully');
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            return redirect()->back()->withErrors('Error updating record. Please try again.')->withInput();
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
    public function deleteQaPacking(Request $request)
    {
        QcPacking::where('id', $request->qc_packing_id)->update([
            'status' => 0
        ]);
        QcPackingData::where('qc_packing_id', $request->qc_packing_id)->update([
            'status' => 0
        ]);
        PackingQcTesting::where('qc_packing_id', $request->qc_packing_id)->where('packing_id', $request->packing_list_id)->update([
            'status' => 0
        ]);

        DB::connection('mysql2')->table('packings as p')
            ->where('p.id', $request->packing_list_id)->update([
                    'qc_status' => 1
                ]);

        DB::connection('mysql2')->table('packings as p')
            ->join('packing_datas as pd', 'p.id', '=', 'pd.packing_id')
            ->join('machine_proccess_datas as mpd', 'pd.machine_proccess_data_id', '=', 'mpd.id')
            ->whereIn('mpd.machine_process_stage', [3, 4])
            ->where('p.id', $request->packing_list_id)
            ->update(['mpd.machine_process_stage' => 2]);

    }


    public function productionPlanAndCustomerAgainstSo(Request $request)
    {

        //    $material_requisition = ProductionPlane::where('status',1)->where('sales_order_id',$request->id)->get();

        //    $material_requisition = DB::Connection('mysql2')->table('production_plane as pp')
        //         ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
        //         ->where('mr.status', 1)
        //         ->where('mr.approval_status', 2)
        //         ->where('pp.status', 1)
        //         ->where('pp.sales_order_id',$request->id)
        //         ->groupBy('mr.id')
        //         ->select('mr.id','pp.id as pp_id', 'pp.order_no', 'pp.order_date')
        //         ->get();

        $material_requisition = DB::Connection('mysql2')->table('packings as p')
            ->join('material_requisitions as mr', 'mr.id', '=', 'p.material_requisition_id')
            ->join('production_plane as pp', 'pp.id', '=', 'p.production_plan_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->select('mr.id', 'pp.id AS pp_id', 'pp.order_no', 'pp.order_date', 's.sub_ic')
            ->where('p.status', 1)
            ->where('pp.status', 1)
            ->where('mr.status', 1)
            ->where('p.so_id', $request->id)
            ->where('mr.approval_status', 2)
            ->groupBy('mr.id')
            ->get();

        $delivery_challan = DB::Connection('mysql2')->table('delivery_note as d')
            ->join('packings as p', 'p.dc_id', 'd.id')
            ->join('delivery_note_data as dd', 'dd.master_id', 'd.id')
            ->join('subitem as s', 's.id', '=', 'dd.item_id')
            ->join('uom as u', 'u.id', '=', 's.uom')
            ->where('d.master_id', $request->id)
            ->where('d.status', 1)
            ->where('p.status', 1)
            ->where('d.lot_no', '!=', null)
            ->select('d.id', 'd.gd_no', 'd.dc_no', 'd.so_no', 'dd.qty', 's.sub_ic', 'u.uom_name')
            ->groupBy('d.id')
            ->get();
        $customerDetails = Sales_Order::where('sales_order.id', $request->id)
            ->join('customers', 'customers.id', 'sales_order.buyers_id')
            ->select('sales_order.id as so_id', 'sales_order.purchase_order_no', 'sales_order.so_no', 'customers.id as customer_id', 'customers.name as name')
            ->first();

        return compact('material_requisition', 'customerDetails', 'delivery_challan');
    }

    public function getPackingListNo(Request $request)
    {
        $packing = Packing::where('status', 1)
            ->where('material_requisition_id', $request->id)
            ->where('dc_id', $request->dc_id)
            ->where('qc_status', 1)
            ->get();

        return compact('packing');
    }

    public function testingOnReceiveItem(Request $request)
    {

        $qc_packings = DB::connection('mysql2')->table('qc_packings as qp')
            ->select('p.id as packing_id', 'qp.id as qc_packing_id', 'pp.sale_order_no', 'pp.order_no', 'p.packing_list_no', 'qp.customer_name', 'qp.qc_packing_date', 'qp.qc_by')
            ->join('packings as p', 'qp.packing_list_id', '=', 'p.id')
            ->join('production_plane as pp', 'p.production_plan_id', '=', 'pp.id')
            ->where('p.status', '=', 1)
            ->where('qp.status', '=', 1)
            ->where('p.qc_status', '=', 2)
            ->where('qp.id', '=', $request->id)
            ->first();

        $test_column = DB::connection('mysql2')->table('qc_packing_datas as qpd')
            ->select('qt.id', 'qt.name', 'qpd.test_value')
            ->join('qa_tests as qt', 'qt.id', '=', 'qpd.qa_test_id')
            ->where('qpd.status', '=', 1)
            ->where('qt.status', '=', 1)
            ->where('qpd.qc_packing_id', '=', $request->id)
            ->orderBy('qpd.id')
            ->get();

        $items = DB::connection('mysql2')->table('qc_packings as qp')
            ->select('pd.id', 'pd.bundle_no', 'pd.qty', 'qpd.qa_test_id')
            ->join('qc_packing_datas as qpd', 'qp.id', '=', 'qpd.qc_packing_id')
            ->join('packings as p', 'qp.packing_list_id', '=', 'p.id')
            ->join('packing_datas as pd', 'p.id', '=', 'pd.packing_id')
            ->join('machine_proccess_datas as mpd', 'mpd.id', '=', 'pd.machine_proccess_data_id')
            ->where('qp.status', '=', 1)
            ->where('qpd.status', '=', 1)
            ->where('p.status', '=', 1)
            ->where('pd.status', '=', 1)
            ->where('p.qc_status', '=', 2)
            ->where('mpd.machine_process_stage', '=', 3)
            ->where('qpd.qc_packing_id', '=', $request->id)
            ->orderBy('qpd.id', 'ASC')
            ->groupBy('pd.id')
            ->get();


        return view('Production.QaPacking.testingOnReceiveItem', compact('qc_packings', 'test_column', 'items'));

    }

    public function testResultOnReceiveItem(Request $request)
    {
        $qc_packings = DB::connection('mysql2')->table('qc_packings as qp')
            ->select('p.id as packing_id', 'qp.id as qc_packing_id', 'pp.sale_order_no', 'pp.order_no', 'p.packing_list_no', 'qp.customer_name', 'qp.qc_packing_date', 'qp.qc_by')
            ->join('packings as p', 'qp.packing_list_id', '=', 'p.id')
            ->join('production_plane as pp', 'p.production_plan_id', '=', 'pp.id')
            ->where('p.status', '=', 1)
            ->where('qp.status', '=', 1)
            ->whereIn('p.qc_status', [3, 4])
            ->where('qp.id', '=', $request->id)
            ->first();

        $test_column = DB::connection('mysql2')->table('qc_packing_datas as qpd')
            ->select('qt.id', 'qt.name', 'qpd.test_value')
            ->join('qa_tests as qt', 'qt.id', '=', 'qpd.qa_test_id')
            ->where('qpd.status', '=', 1)
            ->where('qt.status', '=', 1)
            ->where('qpd.qc_packing_id', '=', $request->id)
            ->orderBy('qpd.id')
            ->get();

        $items = DB::connection('mysql2')->table('qc_packings AS qp')
            ->join('qc_packing_datas AS qpd', 'qp.id', '=', 'qpd.qc_packing_id')
            ->join('packings AS p', 'qp.packing_list_id', '=', 'p.id')
            ->join('packing_datas AS pd', 'p.id', '=', 'pd.packing_id')
            ->join('packing_qc_testings AS pqt', 'pd.id', '=', 'pqt.packing_data_id')
            ->join('machine_proccess_datas AS mpd', 'pd.machine_proccess_data_id', '=', 'mpd.id')
            ->select('pd.*', 'pqt.packing_data_id')
            ->where('qp.status', 1)
            ->where('qpd.status', 1)
            ->where('p.status', 1)
            ->where('pd.status', 1)
            ->where('pqt.status', 1)
            ->where('mpd.status', 1)
            ->whereIn('p.qc_status', [3, 4])
            ->where('mpd.machine_process_stage', 4)
            ->where('pqt.test_perform_on', 2)
            ->where('qpd.qc_packing_id', '=', $request->id)
            ->groupBy('pqt.packing_data_id')
            ->orderBy('qpd.id', 'ASC')
            ->get();

        return view('Production.QaPacking.testResultOnReceiveItem', compact('qc_packings', 'test_column', 'items'));
    }

    public function testResultOnReceiveItemAjax(Request $request)
    {

        $mainData = DB::connection('mysql2')->table('packings AS p')
            ->join('packing_datas AS pd', 'p.id', '=', 'pd.packing_id')
            ->join('qc_packings AS qp', 'qp.packing_list_id', '=', 'p.id')
            ->join('sales_order AS so', 'p.so_id', '=', 'so.id')
            ->select('p.customer_name', 'so.so_no', 'so.purchase_order_no', 'qp.qc_by', 'qp.qc_packing_date', 'pd.bundle_no')
            ->where('p.status', 1)
            ->where('pd.status', 1)
            ->where('qp.status', 1)
            ->where('so.status', 1)
            ->where('pd.id', $request->id)
            ->first();

        // $mechanicaltest = DB::connection('mysql2')->table('packing_qc_testings AS pqt')
        //                     ->join('qc_packing_datas AS qpd', 'pqt.qc_test_id', '=', 'qpd.qa_test_id')
        //                     ->join('qa_tests AS qt', 'qt.id', '=', 'pqt.qc_test_id')
        //                     ->select('qt.name', 'qpd.test_value', 'pqt.test_result')
        //                     ->where('pqt.status', 1)
        //                     ->where('qpd.status', 1)
        //                     ->where('qt.status', 1)
        //                     ->where('pqt.packing_data_id',  $request->id)
        //                     ->where('pqt.test_perform_on', 2)
        //                     ->where('qpd.test_type', 'Mechanical')

        //                     ->get();      

        $mechanicaltest = DB::connection('mysql2')->table('packing_qc_testings as pqt')
            ->join('qc_packings as qp', 'pqt.qc_packing_id', '=', 'qp.id')
            ->join('qc_packing_datas as qpd', function ($join) {
                $join->on('qp.id', '=', 'qpd.qc_packing_id')
                    ->on('pqt.qc_test_id', '=', 'qpd.qa_test_id');
            })
            ->join('qa_tests as qt', 'qt.id', '=', 'pqt.qc_test_id')
            ->select('qt.name', 'qpd.test_value', 'pqt.test_result')
            ->where('pqt.status', 1)
            ->where('qpd.status', 1)
            ->where('qt.status', 1)
            ->where('pqt.packing_data_id', $request->id)
            ->where('pqt.test_perform_on', 2)
            ->where('qpd.test_type', 'Mechanical')
            ->groupBy('qt.id')
            ->get();

        // $physicaltest = DB::connection('mysql2')->table('packing_qc_testings AS pqt')
        //                     ->join('qc_packing_datas AS qpd', 'pqt.qc_test_id', '=', 'qpd.qa_test_id')
        //                     ->join('qc_packings' , 'qc_packings.id' , 'qpd.qc_packing_id')
        //                     ->join('qa_tests AS qt', 'qt.id', '=', 'pqt.qc_test_id')
        //                     ->select('qt.name', 'qpd.test_value', 'pqt.test_result')
        //                     ->where('pqt.status', 1)
        //                     ->where('qpd.status', 1)
        //                     ->where('qt.status', 1)
        //                     ->where('pqt.packing_data_id',  $request->id)
        //                     ->where('pqt.test_perform_on', 2)
        //                     ->where('qpd.test_type', 'Physical')
        //                     // ->groupBy('qpd.id')
        //                     ->get();        

        $physicaltest = DB::connection('mysql2')->table('packing_qc_testings as pqt')
            ->join('qc_packings as qp', 'pqt.qc_packing_id', '=', 'qp.id')
            ->join('qc_packing_datas as qpd', function ($join) {
                $join->on('qp.id', '=', 'qpd.qc_packing_id')
                    ->on('pqt.qc_test_id', '=', 'qpd.qa_test_id');
            })
            ->join('qa_tests as qt', 'qt.id', '=', 'pqt.qc_test_id')
            ->select('qt.name', 'qpd.test_value', 'pqt.test_result')
            ->where('pqt.status', 1)
            ->where('qpd.status', 1)
            ->where('qt.status', 1)
            ->where('pqt.packing_data_id', $request->id)
            ->where('pqt.test_perform_on', 2)
            ->where('qpd.test_type', 'Physical')
            ->groupBy('qt.id')
            ->get();
        // $physicaltest = DB::connection('mysql2')->table('packing_qc_testings AS pqt')
        //                     ->join('qc_packing_datas AS qpd', 'pqt.qc_test_id', '=', 'qpd.qa_test_id')
        //                     ->join('qa_tests AS qt', 'qt.id', '=', 'pqt.qc_test_id')
        //                     ->select('qt.name', 'qpd.test_value', 'pqt.test_result')
        //                     ->where('pqt.status', 1)
        //                     ->where('qpd.status', 1)
        //                     ->where('qt.status', 1)
        //                     ->where('pqt.packing_data_id',  $request->id)
        //                     ->where('pqt.test_perform_on', 2)
        //                     ->where('qpd.test_type', 'Physical')
        //                     // ->groupBy('pqt.packing_data_id')
        //                     // ->groupBy('qt.id')
        //                     ->get();                    
        // echo "<pre>";
        // print_r($mainData);
        // echo "<pre>";
        // print_r($mechanicaltest);
        // echo "<pre>";
        // print_r($physicaltest);
        // exit();

        return view('Production.QaPacking.ajax.testResultOnReceiveItemAjax', compact('mainData', 'mechanicaltest', 'physicaltest'));

    }

    public function storeTestResult(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        DB::Connection('mysql2')->beginTransaction();

        try {


            foreach ($request->packing_data_id as $key => $value) {
                // if ($request->input('checkBox' . $value) == 1) 

                foreach ($request->input('qc_test_id' . $value) as $key1 => $value1) {

                    $packing_qc_testing = new PackingQcTesting;
                    $packing_qc_testing->packing_id = $request->packing_id;
                    $packing_qc_testing->qc_packing_id = $request->qc_packing_id;
                    $packing_qc_testing->packing_data_id = $value;
                    $packing_qc_testing->qc_test_id = $value1;
                    $packing_qc_testing->test_result = $request->input('test_result' . $value)[$key1] ?? '';
                    $packing_qc_testing->qc_test_status = 2;
                    $packing_qc_testing->test_perform_on = ($request->input('qc_test_status' . $value) == 2) ? $request->input('qc_test_status' . $value) : 1;
                    $packing_qc_testing->status = 1;
                    $packing_qc_testing->username = Auth::user()->name;
                    $packing_qc_testing->save();
                }
            }

            $PackingData = DB::Connection('mysql2')->table('packings as p')
                ->join('packing_datas as pd', 'p.id', '=', 'pd.packing_id')
                ->select('p.id', 'pd.id as pd_id', 'p.packing_date', 'p.item_id', 'pd.qty', 'p.packing_list_no', 'p.so_id')
                ->where('p.status', 1)
                ->where('pd.status', 1)
                ->where('p.qc_status', 2)
                ->where('p.id', $request->packing_id)
                ->get();

            // foreach ($PackingData as $key => $value) {

            //     $stock=array
            //             (
            //                 'main_id' => $value->id,
            //                 'master_id' => $value->pd_id,
            //                 'voucher_no' => $value->packing_list_no,
            //                 'voucher_date' => $value->packing_date,
            //                 'voucher_type' => 1,
            //                 'sub_item_id' => $value->item_id,
            //                 'qty' => $value->qty,
            //                // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
            //                 'status'=> 1,
            //                 'warehouse_id' => 12, //$request->$warehouseIdName[$itemKey],
            //                 'username'=>Auth::user()->username,
            //                 'created_date'=>date('Y-m-d'),
            //                 'created_date'=>date('Y-m-d'),
            //                 'opening'=>0,
            //             );


            //             DB::Connection('mysql2')->table('stock')->insert($stock);

            // }

            DB::connection('mysql2')->table('packings as p')
                ->where('p.status', 1)
                ->where('p.id', $request->packing_id)->update([
                        'qc_status' => 3
                    ]);

            DB::connection('mysql2')->table('packings as p')
                ->join('packing_datas as pd', 'p.id', '=', 'pd.packing_id')
                ->join('machine_proccess_datas as mpd', 'pd.machine_proccess_data_id', '=', 'mpd.id')
                ->where('mpd.machine_process_stage', 3)
                ->where('p.status', 1)
                ->where('pd.status', 1)
                ->where('mpd.status', 1)
                ->where('p.id', $request->packing_id)
                ->update(['mpd.machine_process_stage' => 4]);

            DB::Connection('mysql2')->commit();

            return redirect('Production/QaPacking/')->with('success', 'Record inserted successfully');

        } catch (QueryException $e) {
            // Log or handle the exception as needed
            DB::Connection('mysql2')->rollback();

            return redirect()->back()->withErrors('Error inserting record. Please try again.')->withInput();
        }
    }

    public function addQcValues(Request $request)
    {
        $QaTest = QaTest::where('status', 1)->get();
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
        return view('Production.QaPacking.addQcValues', compact( 'sub_item','QaTest'));
    }

    public function storeQcValues(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|array',
            'test_id' => 'required|array',
            'test_id.*' => 'integer',
            'standard_value.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            foreach ($request->item_id as $index => $itemId) {
                $qcValue = QcValue::create([
                    'item_id' => $itemId,
                    'status' => 1,
                ]);

                foreach ($request->test_id as $index => $testId) {
                    QcValueData::create([
                        'master_id' => $qcValue->id,
                        'test_id' => $testId,
                        'standard_value' => $request->standard_value[$index],
                        'status' => 1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('qcValuesList')->with('dataInsert', 'QC values saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function qcValuesList(Request $request)
    {
        $qcValues = QcValue::join('subitem', 'qc_values.item_id', '=', 'subitem.id')
            ->select('qc_values.*', 'subitem.sub_ic as item_name', 'subitem.item_code')
            ->get();

        return view('Production.QaPacking.qcValuesList', compact('qcValues'));
    }

    public function viewQcValuesDetail(Request $request)
    {
        $qcValues = QcValue::join('qc_values_data', 'qc_values.id', '=', 'qc_values_data.master_id')
            ->join('subitem', 'qc_values.item_id', '=', 'subitem.id')
            ->join('qa_tests', 'qc_values_data.test_id', '=', 'qa_tests.id')
            ->where('qc_values.id', $request->id)
            ->select(
                'qc_values.*',
                'subitem.sub_ic as item_name',
                'subitem.item_code',
                'qa_tests.name AS test_name',
                'qc_values_data.standard_value'
            )
            ->get();

        return view('Production.QaPacking.viewQcValuesDetail', compact('qcValues'));
    }

    public function editQcValues($id)
    {
        $qcValue = QcValue::findOrFail($id);
    
        $qcValuesData = QcValueData::where('master_id', $id)
            ->join('qa_tests', 'qc_values_data.test_id', '=', 'qa_tests.id')
            ->select('qc_values_data.*', 'qa_tests.name AS test_name')
            ->get();
    
        $item = Subitem::findOrFail($qcValue->item_id);
    
        return view('Production.QaPacking.editQcValues', compact('qcValue', 'qcValuesData', 'item'));
    }

    public function updateQcValues(Request $request)
    {
        $qcValue = QcValue::findOrFail($request->id);

        foreach ($request->test_id as $key => $testId) {
            QcValueData::where('master_id', $request->id)
                ->where('test_id', 
                $testId)
                ->update(['standard_value' => $request->standard_value[$key]]);
        }

        return redirect()->route('qcValuesList')->with('dataInsert', 'QC Values updated successfully!');
    }

    public function deleteQcValue(Request $request)
    {
        try {
            $qcValue = QcValue::findOrFail($request->id);
            QcValueData::where('master_id', $qcValue->id)->update(['status' => 0]);
    
            $qcValue->update(['status' => 0]);
    
            return response()->json([
                'success' => true,
                'message' => 'QC Value status updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating QC Value status: ' . $e->getMessage(),
            ]);
        }
    }

    public function getQcValueForm(Request $request)
    {
        $finish_good_id = DB::Connection('mysql2')->table('material_requisitions')->where('id', $request->material_requisition_id)->pluck('finish_good_id');

        $qc_values = QcValue::with('qcValuesData')
            ->join('qc_values_data', 'qc_values.id', '=', 'qc_values_data.master_id') // Correct join
            ->join('qa_tests', 'qa_tests.id', '=', 'qc_values_data.test_id') // Ensure this matches your database schema
            ->where('qc_values.item_id', $finish_good_id)
            ->select('qa_tests.id AS qa_test_id', 'qa_tests.name', 'qc_values_data.*')
            ->get();
        
        // If editing, get existing QC packing data
        $existing_qc_data = [];
        $existing_qc_status = [];
        $existing_qc_type = [];
        if (!empty($request->qc_packing_id)) {
            $existing_qc_records = QcPackingData::where('qc_packing_id', $request->qc_packing_id)
                ->where('status', 1)
                ->get();
            
            foreach ($existing_qc_records as $record) {
                $existing_qc_data[$record->qa_test_id] = $record->test_value;
                $existing_qc_status[$record->qa_test_id] = $record->test_status;
                $existing_qc_type[$record->qa_test_id] = $record->test_type;
            }
        }
        
        return view('Production.QaPacking.getQcValueForm', compact('qc_values', 'existing_qc_data', 'existing_qc_status', 'existing_qc_type'));
    }

}
