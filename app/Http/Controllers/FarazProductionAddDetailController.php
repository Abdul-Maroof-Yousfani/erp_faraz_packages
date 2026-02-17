<?php

namespace App\Http\Controllers;

use App\Helpers\ReuseableCode;
use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
class FarazProductionAddDetailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    function insert_dai_detail(Request $request)
    {
        $MainId = Input::get('main_id');
        $BatchCode = Input::get('batch_code');

        $count = count($BatchCode);
        for ($i = 0; $i < $count; $i++):
            $InserData['main_id'] = $MainId;
            $InserData['life'] = $request->life[$i];
            $InserData['batch_code'] = $request->batch_code[$i];
            $InserData['value'] = $request->value[$i];
            $InserData['cost'] = $request->cost[$i];
            $InserData['username'] = Auth::user()->name;
            $MainId = DB::Connection('mysql2')->table('production_dai_detail')->insertGetId($InserData);
            ProductionHelper::production_activity($MainId, 2, 1);
        endfor;

        return Redirect::to('production/daiList?m=' . $_GET['m'] . '#SFR');

    }

    function insert_bom_detail(Request $request)
    {
        $MainId = Input::get('main_id');
        $ItemId = Input::get('item_id');

        $count = count($ItemId);
        for ($i = 0; $i < $count; $i++):
            $InserData['main_id'] = $MainId;
            $InserData['item_id'] = $request->item_id[$i];
            $InserData['qty'] = $request->Qty[$i];
            $InserData['username'] = Auth::user()->name;
            DB::Connection('mysql2')->table('production_bom_data_indirect_material')->insert($InserData);
        endfor;

        return Redirect::to('production/bom_list?m=' . $_GET['m'] . '#SFR');

    }






    public function insert_labour_category(Request $request)
    {
        $InsertData['labour_category'] = $request->input('labour_category');
        $InsertData['charges'] = $request->input('charges');
        $InsertData['status'] = 1;
        $InsertData['username'] = Auth::user()->name;
        $Count = DB::Connection('mysql2')->table('production_labour_category')->where('labour_category', $request->input('labour_category'))->count();
        if ($Count > 0) {
            echo 'duplicate';
        } else {
            DB::Connection('mysql2')->table('production_labour_category')->insert($InsertData);
            echo "yes";
        }

    }

    function convertToHoursMins($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public function insert_operation_detail(Request $request)
    {
        //        echo "<pre>";
//        print_r($request->input());
//        die();


        $m = $request->input('m');
        $FinishGoodId = $request->input('finish_goods');
        $MasterDataInsert['finish_good_id'] = $FinishGoodId;
        $MasterDataInsert['status'] = 1;
        $MasterDataInsert['username'] = Auth::user()->name;
        $MasterDataInsert['date'] = date('Y-m-d');
        $MasterId = DB::Connection('mysql2')->table('production_work_order')->insertGetId($MasterDataInsert);

        $DetailSection = $request->input('machine_id');

        foreach ($DetailSection as $key => $row2):
            $WaitTime = sprintf("%02d:%02d:%02d", floor($request->input('wait_time')[$key] / 60), $request->input('wait_time')[$key] % 60, '00');
            $MoveTime = sprintf("%02d:%02d:%02d", floor($request->input('move_time')[$key] / 60), $request->input('move_time')[$key] % 60, '00');
            $QueTime = sprintf("%02d:%02d:%02d", floor($request->input('que_time')[$key] / 60), $request->input('que_time')[$key] % 60, '00');




            $DetailDataInsert['master_id'] = $MasterId;
            $DetailDataInsert['machine_id'] = $request->input('machine_id')[$key];
            $DetailDataInsert['capacity'] = $request->input('capacity')[$key];
            //            $DetailDataInsert['labour_category_id'] = $LabCatIds;
            $DetailDataInsert['wait_time'] = gmdate("H:i:s", $request->input('wait_time')[$key]);
            $DetailDataInsert['move_time'] = gmdate("H:i:s", $request->input('move_time')[$key]);
            $DetailDataInsert['que_time'] = gmdate("H:i:s", $request->input('que_time')[$key]);
            $DetailDataInsert['status'] = 1;
            $DetailDataInsert['date'] = date('Y-m-d');
            $DetailDataInsert['username'] = 'Amir Murshad';

            $DetailId = DB::Connection('mysql2')->table('production_work_order_data')->insertGetId($DetailDataInsert);
            //    $DetailSectionLab = $request->input('labour_category');
//            foreach ($DetailSectionLab as $key2 => $row3):
//                $LabCatDetailInsert['master_id'] = $MasterId;
////            $LabCatDetailInsert['detail_id'] = $DetailId;
////            $LabCatDetailInsert['machine_id'] = $request->input('machine_id')[$key];
//                $LabCatDetailInsert['labour_category_id'] = $request->input('labour_category')[$key2];
//                $LabCatDetailInsert['labour_category_value'] = $request->input('labour_category_value')[$key2];
//                $LabCatDetailInsert['username'] = Auth::user()->name;
//                DB::Connection('mysql2')->table('production_work_order_lab_cat_detail')->insert($LabCatDetailInsert);
//            endforeach;

        endforeach;
        ProductionHelper::production_activity($MasterId, 7, 1);

        return Redirect::to('production/operation_list?m=' . $m . '#SFR');

    }

    public function update_operation_detail(Request $request)
    {

        $EditId = $request->input('EditId');
        $m = $request->input('m');
        //DB::Connection('mysql2')->table('production_work_order_data')->where('master_id','=',$EditId)->delete();

        $DetailSection = $request->input('machine_id');

        foreach ($DetailSection as $key => $row2):
            $WaitTime = sprintf("%02d:%02d:%02d", floor($request->input('wait_time')[$key] / 60), $request->input('wait_time')[$key] % 60, '00');
            $MoveTime = sprintf("%02d:%02d:%02d", floor($request->input('move_time')[$key] / 60), $request->input('move_time')[$key] % 60, '00');
            $QueTime = sprintf("%02d:%02d:%02d", floor($request->input('que_time')[$key] / 60), $request->input('que_time')[$key] % 60, '00');




            $DetailDataInsert['master_id'] = $EditId;
            $DetailDataInsert['machine_id'] = $request->input('machine_id')[$key];
            $DetailDataInsert['capacity'] = $request->input('capacity')[$key];
            //            $DetailDataInsert['labour_category_id'] = $LabCatIds;
            $DetailDataInsert['wait_time'] = gmdate("H:i:s", $request->input('wait_time')[$key]);
            $DetailDataInsert['move_time'] = gmdate("H:i:s", $request->input('move_time')[$key]);
            $DetailDataInsert['que_time'] = gmdate("H:i:s", $request->input('que_time')[$key]);
            $DetailDataInsert['status'] = 1;
            $DetailDataInsert['date'] = date('Y-m-d');
            $DetailDataInsert['username'] = Auth::user()->name;

            DB::Connection('mysql2')->table('production_work_order_data')->where('id', $request->input('detail_id')[$key])->update($DetailDataInsert);
            if ($request->input('detail_id')[$key] == 0) {
                DB::Connection('mysql2')->table('production_work_order_data')->insert($DetailDataInsert);
            }
            //    $DetailSectionLab = $request->input('labour_category');
//            foreach ($DetailSectionLab as $key2 => $row3):
//                $LabCatDetailInsert['master_id'] = $MasterId;
////            $LabCatDetailInsert['detail_id'] = $DetailId;
////            $LabCatDetailInsert['machine_id'] = $request->input('machine_id')[$key];
//                $LabCatDetailInsert['labour_category_id'] = $request->input('labour_category')[$key2];
//                $LabCatDetailInsert['labour_category_value'] = $request->input('labour_category_value')[$key2];
//                $LabCatDetailInsert['username'] = Auth::user()->name;
//                DB::Connection('mysql2')->table('production_work_order_lab_cat_detail')->insert($LabCatDetailInsert);
//            endforeach;

        endforeach;
        ProductionHelper::production_activity($EditId, 7, 2);


        return Redirect::to('production/operation_list?m=' . $m . '#SFR');

    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function add_route(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {

            $data = array
            (
                'finish_goods' => $request->finish_goods,
                'voucher_no' => ProductionHelper::get_unique_code_for_routing(),
                'operation_id' => $request->operation_id,
                'status' => 1,
                'username' => Auth::user()->name,
                'date' => date('Y-m-d'),

            );

            $id = DB::Connection('mysql2')->table('production_route')->insertGetId($data);

            $data1 = $request->machine;
            foreach ($data1 as $key => $row):
                $data2 = array
                (
                    'master_id' => $id,
                    'machine_id' => $row,
                    'operation_data_id' => $request->input('operation_data_id')[$key],
                    'orderby' => $request->input('orderbyy')[$key],
                    'status' => 1,

                );
                DB::Connection('mysql2')->table('production_route_data')->insert($data2);
            endforeach;
            ProductionHelper::production_activity($id, 8, 1);
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();

        }
        return Redirect::to('production/routing_list');

    }

    public function update_route(Request $request)
    {
        $EditId = $request->input('EditId');
        DB::Connection('mysql2')->beginTransaction();
        try {

            $data1 = $request->machine;
            foreach ($data1 as $key => $row):
                $data2 = array
                (
                    'master_id' => $EditId,
                    'machine_id' => $row,
                    'operation_data_id' => $request->input('operation_data_id')[$key],
                    'orderby' => $request->input('orderbyy')[$key],
                    'status' => 1,

                );
                DB::Connection('mysql2')->table('production_route_data')->where('id', $request->input('detailed_id')[$key])->where('master_id', $EditId)->update($data2);
            endforeach;
            ProductionHelper::production_activity($EditId, 8, 2);
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();

        }
        return Redirect::to('production/routing_list');

    }



    public function add_factory_over_head(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {

            $data = array
            (
                'name' => $request->name,
                'over_head_category_id' => $request->over_head_category_id,
                'desc' => $request->desc,
                'status' => 1,
                'username' => Auth::user()->name,
                'date' => date('Y-m-d'),

            );

            $id = DB::Connection('mysql2')->table('production_factory_overhead')->insertGetId($data);

            $data1 = $request->acc_id;
            foreach ($data1 as $key => $row):
                $data2 = array
                (
                    'master_id' => $id,
                    'acc_id' => $row,
                    'amount' => $request->input('amount')[$key],
                    'no_of_piece' => $request->input('no_of_piece')[$key],
                    'cost' => $request->input('cost')[$key],
                    'status' => 1,
                    'date' => date('Y-m-d'),
                    'username' => Auth::user()->name,

                );
                DB::Connection('mysql2')->table('production_factory_overhead_data')->insert($data2);
            endforeach;
            ProductionHelper::production_activity($id, 9, 1);
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();

        }
        return Redirect::to('production/factory_overhead_list?m=' . $_GET['m'] . '#SFR');
    }

    public function update_factory_over_head(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        $EditId = $request->EditId;
        try {

            $data = array
            (
                'name' => $request->name,
                'over_head_category_id' => $request->over_head_category_id,
                'desc' => $request->desc,
                'status' => 1,
                'username' => Auth::user()->name,
                'date' => date('Y-m-d'),

            );

            DB::Connection('mysql2')->table('production_factory_overhead')->where('id', '=', $EditId)->update($data);
            DB::Connection('mysql2')->table('production_factory_overhead_data')->where('master_id', '=', $EditId)->delete();

            $data1 = $request->acc_id;
            foreach ($data1 as $key => $row):
                $data2 = array
                (
                    'master_id' => $EditId,
                    'acc_id' => $row,
                    'amount' => $request->input('amount')[$key],
                    'no_of_piece' => $request->input('no_of_piece')[$key],
                    'cost' => $request->input('cost')[$key],
                    'status' => 1,
                    'date' => date('Y-m-d'),
                    'username' => Auth::user()->name,

                );
                DB::Connection('mysql2')->table('production_factory_overhead_data')->insert($data2);
            endforeach;
            ProductionHelper::production_activity($EditId, 9, 2);
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();

        }
        return Redirect::to('production/factory_overhead_list?m=' . $_GET['m'] . '#SFR');
    }


    function insert_labours_working(Request $request)
    {
        $MasterInsert['remarks'] = $request->WorkingNoteRemarks;
        $MasterInsert['working_hours'] = $request->WorkingHours;
        $MasterInsert['no_of_worker'] = $request->NoOfWorker;
        $MasterInsert['total_working_hours'] = $request->TotalWorkingHours;
        $MasterInsert['date'] = date('Y-m-d');
        $MasterInsert['username'] = Auth::user()->name;
        $MasterId = DB::Connection('mysql2')->table('production_labour_working')->insertGetId($MasterInsert);


        $NoOfEmployee = Input::get('NoOfEmployee');

        $count = count($NoOfEmployee);
        for ($i = 0; $i < $count; $i++):
            $DetailInsert['master_id'] = $MasterId;
            $DetailInsert['description'] = $request->Description[$i];
            $DetailInsert['no_of_employee'] = $request->NoOfEmployee[$i];
            $DetailInsert['wages_work_amount'] = $request->WagesWork[$i];
            $DetailInsert['monthly_wages_amount'] = $request->MonthlyWages[$i];
            $DetailInsert['yearly_wages_amount'] = $request->YearlyWages[$i];

            DB::Connection('mysql2')->table('production_labour_working_data')->insert($DetailInsert);
        endfor;
        ProductionHelper::production_activity($MasterId, 10, 1);
        return Redirect::to('production/labour_working_list?m=' . $_GET['m'] . '#SFR');

    }

    function update_labours_working(Request $request)
    {
        $EditId = $request->EditId;
        $MasterInsert['remarks'] = $request->WorkingNoteRemarks;
        $MasterInsert['working_hours'] = $request->WorkingHours;
        $MasterInsert['no_of_worker'] = $request->NoOfWorker;
        $MasterInsert['total_working_hours'] = $request->TotalWorkingHours;
        $MasterInsert['date'] = date('Y-m-d');
        $MasterInsert['start_date'] = date('Y-m-d');
        $MasterInsert['username'] = Auth::user()->name;
        $Inactive['status'] = 2;


        $MasterId = DB::Connection('mysql2')->table('production_labour_working')->insertGetId($MasterInsert);
        DB::Connection('mysql2')->table('production_labour_working')->where('id', '=', $EditId)->update($Inactive);
        DB::Connection('mysql2')->table('production_labour_working_data')->where('master_id', '=', $EditId)->update($Inactive);


        $NoOfEmployee = Input::get('NoOfEmployee');

        $count = count($NoOfEmployee);
        for ($i = 0; $i < $count; $i++):
            $DetailInsert['master_id'] = $MasterId;
            $DetailInsert['description'] = $request->Description[$i];
            $DetailInsert['no_of_employee'] = $request->NoOfEmployee[$i];
            $DetailInsert['wages_work_amount'] = $request->WagesWork[$i];
            $DetailInsert['monthly_wages_amount'] = $request->MonthlyWages[$i];
            $DetailInsert['yearly_wages_amount'] = $request->YearlyWages[$i];

            DB::Connection('mysql2')->table('production_labour_working_data')->insert($DetailInsert);
        endfor;
        ProductionHelper::production_activity($MasterId, 10, 2);

        return Redirect::to('production/labour_working_list?m=' . $_GET['m'] . '#SFR');

    }



    function inser_over_head_category(Request $request)
    {
        $Name = Input::get('Name');
        $Remarks = Input::get('Remarks');

        $InserData['name'] = $Name;
        $InserData['remarks'] = $Remarks;
        $InserData['date'] = date('Y-m-d');
        $InserData['username'] = Auth::user()->name;

        $MainId = DB::Connection('mysql2')->table('production_over_head_category')->insertGetId($InserData);
        ProductionHelper::production_activity($MainId, 11, 1);

        return Redirect::to('production/factory_over_head_cateogory_list?m=' . Session::get('run_company'));

    }


    public function insert_ppc(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {
            $so_data = $request->so_no;
            $so_data = explode('*', $so_data);
            $order_no = ProductionHelper::ppc_no(date('y'), date('m'));
            $data = array
            (
                'order_no' => $order_no,
                'order_date' => $request->order_date,
                'due_date' => $request->due_date,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'ppc_status' => $request->status,
                'sales_order_id' => 0,
                'customer' => 0,
                'usernmae' => Auth::user()->name,
                'date' => date('Y-m-d'),

            );

            $id = DB::Connection('mysql2')->table('production_plane')->insertGetId($data);

            $data1 = $request->product;
            foreach ($data1 as $key => $row):
                $data2 = array
                (
                    'master_id' => $id,
                    'order_no' => $order_no,
                    'finish_goods_id' => $row,
                    'route' => $request->input('route')[$key] ?? 0,
                    'planned_qty' => $request->input('planned_qty')[$key],
                    'status' => 1,
                    'date' => date('Y-m-d'),
                    'username' => Auth::user()->name,

                );
                DB::Connection('mysql2')->table('production_plane_data')->insert($data2);
            endforeach;
            DB::Connection('mysql2')->commit();
            return Redirect::to('production/production_plan_list?m=' . Session::get('run_company'));
        } catch (Exception $ex) {

            DB::rollBack();

        }


        return redirect('production/ppc_issue_item?id=' . $id);
    }

    public function update_ppc(Request $request)
    {

        $EditId = $request->EditId;
        $order_no = $request->order_no;
        $DeletedIds = $request->DeletedIds;
        //die();

        $SaleOrderId = 0;
        $CustomerId = 0;
        if (isset($request->so_no)) {
            $so_data = explode('*', $request->so_no);
            $SaleOrderId = $so_data[0];
            $CustomerId = $so_data[2];
        }


        DB::Connection('mysql2')->select('Update production_plane_data set status = 0 where id in(' . $DeletedIds . ') and master_id = ' . $EditId . '');

        DB::Connection('mysql2')->beginTransaction();
        try {


            $data = array
            (
                'order_date' => $request->order_date,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'ppc_status' => $request->status,
                'sales_order_id' => $SaleOrderId,
                'customer' => $CustomerId,
                'usernmae' => Auth::user()->name,
                'date' => date('Y-m-d'),

            );


            DB::Connection('mysql2')->table('production_plane')->where('id', $EditId)->update($data);

            $data1 = $request->product;
            foreach ($data1 as $key => $row):
                $data2 = array
                (
                    'master_id' => $EditId,
                    'order_no' => $order_no,
                    'finish_goods_id' => $row,
                    'route' => $request->input('route')[$key],
                    'planned_qty' => $request->input('planned_qty')[$key],
                    'status' => 1,
                    'date' => date('Y-m-d'),
                    'username' => Auth::user()->name,

                );
                if ($request->input('detailed_id')[$key] != 0) {
                    DB::Connection('mysql2')->table('production_plane_data')->where('id', $request->input('detailed_id')[$key])->update($data2);
                } else {
                    DB::Connection('mysql2')->table('production_plane_data')->insert($data2);
                }

            endforeach;
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();

        }


        return redirect('production/production_plan_list');
    }




    public function insert_conversion(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {

            $data = array
            (
                'production_plan_id' => $request->production_plan_id,
                'status' => 1,
                'username' => Auth::user()->name,
                'date' => date('Y-m-d'),

            );

            $id = DB::Connection('mysql2')->table('production_conversion')->insertGetId($data);

            $production = ProductionHelper::get_production_plane_detail($request->production_plan_id);
            $voucher_no = $production->order_no;

            $data1 = $request->spoilage;
            foreach ($data1 as $key => $row):
                $data2 = array
                (
                    'master_id' => $id,
                    'production_plan_data_id' => $request->input('production_plan_data_id')[$key],
                    'spoilage' => $row,
                    'produce_qty' => $request->input('produce_qty')[$key],
                    'status' => 1,
                    'date' => date('Y-m-d'),
                    'username' => Auth::user()->name,

                );
                $production_plan_data_id = DB::Connection('mysql2')->table('prouction_conversion_data')->insertGetId($data2);


                $data3 = $request->input('production_plan_issuence_id' . $key);

                foreach ($data3 as $key2 => $row1):

                    $chip = $request->input('chip' . $key)[$key2];
                    if ($chip == 'Not Applicable'):
                        $chip = 0;
                    endif;

                    $turning = $request->input('turning' . $key)[$key2];
                    if ($turning == 'Not Applicable'):
                        $turning = 0;
                    endif;
                    $data4 = array
                    (
                        'production_conversion_id' => $id,
                        'production_conversion_data_id' => $production_plan_data_id,
                        'bom_data_id' => $request->input('bom_data_id' . $key)[$key2],
                        'issuence_id' => $row1,
                        'type' => $request->input('type' . $key)[$key2],
                        'chip' => $chip,
                        'turning' => $turning,
                        'status' => 1,
                        'username' => Auth::user()->name,
                        'date' => date('Y-m-d'),

                    );
                    DB::Connection('mysql2')->table('production_conversion_data_material')->insert($data4);


                endforeach;
            endforeach;

            $issuence_data = $request->wastage;

            foreach ($issuence_data as $key3 => $row):

                $wastage_data =
                    array
                    (

                        'issuence_id' => $request->input('issuence_id')[$key3],
                        'production_plan_data_id' => $request->input('bom_data')[$key3],
                        'ppc_no' => $voucher_no,
                        'wastage_per_pirece' => $row,
                        'date' => date('Y-m-d'),

                    );
                DB::Connection('mysql2')->table('production_wastage_data')->insert($wastage_data);
            endforeach;


            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();

        }
        $id = $request->production_plan_id;


        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $id)->first();
        $master_data = DB::Connection('mysql2')->table('production_plane_data')->where('status', 1)->where('master_id', $id)->get();

        return view('Production.conversion_cost', compact('data', 'master_data'));
    }

    public function update_internal_consum(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        //  $uniq=PurchaseHelper::get_unique_no_internal_consumtion(date('y'),date('m'));
        try {
            $id = $request->id;
            $data = array
            (
                'voucher_no' => $request->tr_no,
                'voucher_date' => $request->tr_date,
                'description' => $request->description,
                'status' => 1,
                'date' => $request->tr_date,
                'username' => Auth::user()->name,
            );
            DB::Connection('mysql2')->table('internal_consumtion')->where('id', $id)->update($data);

            $data1 = $request->item_id;
            $TotAmount = 0;
            foreach ($data1 as $key => $row):





                $data2 = array
                (
                    'master_id' => $id,
                    'voucher_no' => $request->tr_no,
                    'item_id' => $row,
                    'warehouse_from' => $request->input('warehouse_from')[$key],
                    'acc_id' => $request->input('warehouse_to')[$key],
                    'qty' => $request->input('qty')[$key],
                    'rate' => $request->input('rate')[$key],
                    'amount' => $request->input('amount')[$key],
                    'batch_code' => $request->input('batch_code')[$key],
                    'desc' => $request->input('des')[$key],
                    'status' => 1,
                );

                $TotAmount += $request->input('amount')[$key];
                $data_id = $request->input('data_id')[$key];
                if ($data_id == 0):
                    $master_data_id = DB::Connection('mysql2')->table('internal_consumtion_data')->insertGetId($data2);
                else:
                    $master_data_id = DB::Connection('mysql2')->table('internal_consumtion_data')->where('id', $data_id)->update($data2);
                endif;


            endforeach;

            CommonHelper::inventory_activity($request->tr_no, $request->tr_date, $TotAmount, 10, 'Update');


            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }

        Session::flash('dataInsert', 'Stock Transfer Successfully Saved.');

        return Redirect::to('store/internal_consumtion_list?pageType=view&&parentCode=95&&m=' . Session::get('run_company') . '#murtazaCorporation');

    }

    public function addProductionOrderDetail(Request $request)
    {
        // dd($request->all());
        DB::Connection('mysql2')->beginTransaction();
        try {
            $m = $_GET['m'];

            $data['pr_no'] = strip_tags($request->pr_no);
            $data['request_date'] = strip_tags($request->request_date);
            $data['ref_no'] = strip_tags($request->ref_no);
            $data['description'] = strip_tags($request->description);
            $data['username'] = Auth::user()->name;
            $data['approval_status'] = 1;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $master_id = DB::Connection('mysql2')->table('production_request')->insertGetId($data);

            foreach ($request->sub_category as $key => $row) {
                $data2['master_id'] = $master_id;
                $data2['sub_category_id'] = $row;
                $data2['purpose'] = $request->purpose[$key];
                $data2['required_date'] = $request->required_date[$key];
                $data2['username'] = Auth::user()->name;
                $data2['production_status'] = 1;
                $data2['status'] = 1;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                DB::Connection('mysql2')->table('production_request_data')->insert($data2);
            }

            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo $e->getMessage();
        }

        Session::flash('dataInsert', 'Successfully Saved.');
        return Redirect::to('far_production/viewProductionOrderList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m']);
    }


    public function editProductionOrderDetail(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $m = $_GET['m'];

            $data['request_date'] = strip_tags($request->request_date);
            $data['ref_no'] = strip_tags($request->ref_no);
            $data['description'] = strip_tags($request->description);
            $data['username'] = Auth::user()->name;
            $data['approval_status'] = 1;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            DB::Connection('mysql2')->table('production_request')->where('id', $request->id)->update($data);

            DB::Connection('mysql2')->table('production_request_data')->where('master_id', $request->id)->delete();
            foreach ($request->sub_category as $key => $row) {
                $data2['master_id'] = $request->id;
                $data2['sub_category_id'] = $row;
                // $data2['color'] = $request->color[$key];
                $data2['purpose'] = $request->purpose[$key];
                $data2['required_date'] = $request->required_date[$key];
                $data2['username'] = Auth::user()->name;
                $data2['production_status'] = 1;
                $data2['status'] = 1;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                DB::Connection('mysql2')->table('production_request_data')->insert($data2);
            }

            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo $e->getMessage();
        }

        Session::flash('dataInsert', 'Successfully Updated.');
        return Redirect::to('far_production/viewProductionOrderList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m']);
    }

    public function approveAndRejectProductionOrder(Request $request)
    {
        DB::connection('mysql2')->table('production_request')->where('id', $request->id)->update(['approval_status' => $request->approval_status]);
        return;
    }

    public function deleteProductionOrder(Request $request)
    {
        $production_request = DB::connection('mysql2')->table('production_request')->where('id', $request->id)->update(['status' => 0]);
        $production_request_data = DB::connection('mysql2')->table('production_request_data')->where('master_id', $request->id)->update(['status' => 0]);
        if ($production_request && $production_request_data) {
            return "true";
        }
        return "false";
    }



    // production mixing

    public function addProductionMixingDetail(Request $request)
    {
        // dd($request->all());
        DB::connection('mysql2')->beginTransaction();

        try {

            $m = $_GET['m'];

            $data = [
                'pm_no' => strip_tags($request->mixing_no),
                'produced_item_id' => strip_tags($request->finish_item_id),
                'production_order_id' => strip_tags($request->production_order_id),
                'date' => strip_tags($request->mixing_date),
                'qty' => strip_tags($request->qty),
                'description' => strip_tags($request->description),
                'username' => Auth::user()->name,
                'status' => 1,
            ];

            $master_id = DB::connection('mysql2')
                ->table('production_mixture')
                ->insertGetId($data);



            $finishItemDetail = CommonHelper::get_subitem_detail2($request->finish_item_id);

            $finishRate = $finishItemDetail->rate ?? 0;
            $finishName = $finishItemDetail->sub_ic ?? '';

            ReuseableCode::postStock(
                $master_id,
                0,
                $request->mixing_no,
                $request->mixing_date,
                11,
                $finishRate,
                $request->finish_item_id,
                $finishName,
                $request->qty
            );


            foreach ($request->item_id as $key => $itemId) {

                $requiredQty = $request->required_qty[$key];
                $machine_id = $request->machine_id[$key];

                $data2 = [
                    'production_mixture_id' => $master_id,
                    'item_id' => $itemId,
                    'qty' => $requiredQty,
                    'machine_id' => $machine_id,
                ];

                DB::connection('mysql2')
                    ->table('production_mixture_data')
                    ->insert($data2);


                $availableQty = ReuseableCode::get_stock($itemId, 0, $requiredQty, 0);

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return "This item " . CommonHelper::get_item_name($itemId) . " has 0 qty in stock";
                }


                $itemDetail = CommonHelper::get_subitem_detail2($itemId);

                $itemRate = $itemDetail->rate ?? 0;
                $itemName = $itemDetail->sub_ic ?? '';

                ReuseableCode::postStock(
                    $master_id,
                    0,
                    $request->mixing_no,
                    $request->mixing_date,
                    9,
                    $itemRate,
                    $itemId,
                    $itemName,
                    $requiredQty
                );
            }

            DB::connection('mysql2')->commit();

        } catch (\Exception $e) {

            DB::connection('mysql2')->rollBack();
            return $e->getMessage();
        }

        Session::flash('dataInsert', 'Successfully Saved.');

        return Redirect::to(
            'far_production/viewProductionMixingList?pageType=' .
            Input::get('pageType') .
            '&&parentCode=' .
            Input::get('parentCode') .
            '&&m=' . $_GET['m']
        );
    }

    public function addProductionRollingDetail(Request $request)
    {
        $request->validate([
            'item_id' => 'required|array',
            'machine_id' => 'required|array',
            'mixture_qty' => 'required|array',
            'roll_qty' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {

            $production_mixture = DB::connection('mysql2')
                ->table('production_mixture')
                ->where('pm_no', $request->code)
                ->first();

            if (!$production_mixture) {
                throw new \Exception('Production mixture not found');
            }

            foreach ($request->item_id as $key => $itemId) {

                $mixtureQty = $request->mixture_qty[$key] ?? 0;
                $rollQty = $request->roll_qty[$key] ?? 0;

                // ========================
                // CHECK STOCK (RAW MIXTURE)
                // ========================
                $availableQty = ReuseableCode::get_stock(
                    $request->raw_item_id[$key],
                    0,
                    $mixtureQty,
                    0
                );

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return "Insufficient stock for item " .
                        CommonHelper::get_item_name($request->raw_item_id[$key]);
                }

                // ========================
                // INSERT ROLLING RECORD
                // ========================
                $data2 = [
                    'production_order_id' => $production_mixture->production_order_id,
                    'production_mixture_id' => $production_mixture->id,
                    'item_id' => $itemId,
                    'machine_id' => $request->machine_id[$key],
                    'operator_id' => $request->operator_id[$key] ?? null,
                    'shift_id' => $request->shift_id[$key] ?? null,
                    'mixture_qty' => $mixtureQty,
                    'roll_qty' => $rollQty,
                    'per_roll_qty_kg' => $request->roll_qty_kg[$key] ?? 0,
                    'date' => $request->date[$key] ?? now(),
                    'status' => 1,
                    'username' => Auth::user()->name,
                ];

                $rollingId = DB::connection('mysql2')
                    ->table('production_rolling')
                    ->insertGetId($data2);

                // ========================
                // UPDATE MIXTURE USED QTY
                // ========================
                DB::connection('mysql2')
                    ->table('production_mixture_data')
                    ->where('production_mixture_id', $production_mixture->id)
                    ->where('item_id', $request->raw_item_id[$key])
                    ->update([
                        'used_qty' => $mixtureQty,
                    ]);

                // ========================
                // STOCK OUT (RAW MIXTURE)
                // ========================
                $rawDetail = CommonHelper::get_subitem_detail2($request->raw_item_id[$key]);

                ReuseableCode::postStock(
                    $rollingId,
                    0,
                    $request->code,
                    $request->date[$key] ?? now(),
                    9, // OUT
                    $rawDetail->rate ?? 0,
                    $request->raw_item_id[$key],
                    $rawDetail->sub_ic ?? '',
                    $mixtureQty
                );

                // ========================
                // STOCK IN (PRODUCED ROLL)
                // ========================
                $finishDetail = CommonHelper::get_subitem_detail2($itemId);

                ReuseableCode::postStock(
                    $rollingId,
                    0,
                    $request->code,
                    $request->date[$key] ?? now(),
                    11, // IN
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $rollQty
                );
            }

            DB::connection('mysql2')->commit();

        } catch (\Exception $e) {
            DB::connection('mysql2')->rollback();
            return $e->getMessage();
        }

        Session::flash('dataInsert', 'Successfully Saved.');

        return Redirect::to(
            'far_production/viewProductionRollingList?pageType=' .
            Input::get('pageType') .
            '&&parentCode=' .
            Input::get('parentCode') .
            '&&m=1'
        );
    }


    public function addProductionRollPrintingDetail(Request $request)
    {
        $request->validate([
            'item_id' => 'required|array',
            'machine_id' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {

            foreach ($request->item_id as $key => $itemId) {

                $printedQty = $request->printed_roll_qty[$key] ?? 0;


                // ========================
                // INSERT PRINTING RECORD
                // ========================
                $data2 = [
                    'production_rolling_id' => $request->roll_id,
                    'item_id' => $itemId,
                    'machine_id' => $request->machine_id[$key],
                    'operator_id' => $request->operator_id[$key] ?? null,
                    'shift_id' => $request->shift_id[$key] ?? null,
                    'type' => $request->type_id[$key] ?? null,
                    'color_id' => $request->color[$key] ?? null,
                    'brand_id' => $request->brand[$key] ?? null,
                    'remarks' => $request->remarks[$key] ?? null,
                    'no_of_roll' => $printedQty,
                    'date' => $request->date[$key] ?? now(),
                    'status' => 1,
                    'username' => Auth::user()->name,
                ];

                $printingId = DB::connection('mysql2')
                    ->table('production_roll_printing')
                    ->insertGetId($data2);

                // ========================
                // STOCK OUT (RAW ROLL)
                // ========================
                // $rawDetail = CommonHelper::get_subitem_detail2($request->raw_item_id[$key]);

                // ReuseableCode::postStock(
                //     $printingId,
                //     0,
                //     $request->roll_id,
                //     $request->date[$key] ?? now(),
                //     9,
                //     $rawDetail->rate ?? 0,
                //     $request->raw_item_id[$key],
                //     $rawDetail->sub_ic ?? '',
                //     $printedQty
                // );

                // ========================
                // STOCK IN (PRINTED ITEM)
                // ========================
                // $finishDetail = CommonHelper::get_subitem_detail2($itemId);

                // ReuseableCode::postStock(
                //     $printingId,
                //     0,
                //     $request->roll_id,
                //     $request->date[$key] ?? now(),
                //     11,
                //     $finishDetail->rate ?? 0,
                //     $itemId,
                //     $finishDetail->sub_ic ?? '',
                //     $printedQty
                // );
            }

            DB::connection('mysql2')
                ->table('production_rolling')
                ->where('id', $request->roll_id)
                ->update([
                    'printed_roll_qty' => $request->used_qty_total,
                ]);

            DB::connection('mysql2')->commit();

        } catch (\Exception $e) {
            DB::connection('mysql2')->rollback();
            return $e->getMessage();
        }

        Session::flash('dataInsert', 'Successfully Saved.');

        return Redirect::to(
            'far_production/viewProductionRollPrintingList?pageType=' .
            Input::get('pageType') .
            '&&parentCode=' .
            Input::get('parentCode') .
            '&&m=1'
        );
    }

    

    public function addProductionCuttingAndSealingDetail(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item_id' => 'required|array',
            'machine_id' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {

            foreach ($request->item_id as $key => $itemId) {

                $qty = $request->qty[$key] ?? 0;

                // ========================
                // CHECK STOCK (PRINTED ROLL)
                // ========================
                $availableQty = ReuseableCode::get_stock(
                    $request->raw_item_id[0],
                    0,
                    $request->no_of_roll[$key],
                    0
                );

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return "Insufficient stock for item " .
                        CommonHelper::get_item_name($request->raw_item_id[0]);
                }

                // ========================
                // INSERT CUTTING & SEALING
                // ========================
                $data2 = [
                    'printed_rolling_id' => $request->roll_id,
                    'item_id' => $itemId,
                    'machine_id' => $request->machine_id[$key] ?? null,
                    'operator_id' => $request->operator_id[$key] ?? null,
                    'shift_id' => $request->shift_id[$key] ?? null,
                    'qty' => $qty ?? 0,
                    'printed_roll_qty' => $request->no_of_roll[$key] ?? 0,
                    'date' => $request->date[$key] ?? now(),
                    'status' => 1,
                    'username' => Auth::user()->name,
                ];
// dd($data2);
                $csId = DB::connection('mysql2')
                    ->table('production_cutting_and_sealing')
                    ->insertGetId($data2);

                // ========================
                // STOCK OUT (PRINTED ROLL)
                // ========================
                $rawDetail = CommonHelper::get_subitem_detail2($request->raw_item_id[0]);

                ReuseableCode::postStock(
                    $csId,
                    0,
                    $request->roll_id,
                    $request->date[$key] ?? now(),
                    9,
                    $rawDetail->rate ?? 0,
                    $request->raw_item_id[0],
                    $rawDetail->sub_ic ?? '',
                    $request->no_of_roll[$key]
                );
                // ========================
                // STOCK IN (CUT/SEALED ITEM)
                // ========================
                $finishDetail = CommonHelper::get_subitem_detail2($itemId);

                ReuseableCode::postStock(
                    $csId,
                    0,
                    $request->roll_id,
                    $request->date[$key] ?? now(),
                    11,
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $qty
                );

            }
// dd("ok");

            // update used qty in printing
            DB::connection('mysql2')
                ->table('production_roll_printing')
                ->where('id', $request->roll_id)
                ->update([
                    'used_no_of_roll' => $request->used_qty_total,
                ]);

            DB::connection('mysql2')->commit();

        } catch (\Exception $e) {
            DB::connection('mysql2')->rollback();
            return $e->getMessage();
        }

        Session::flash('dataInsert', 'Successfully Saved.');

        return Redirect::to(
            'far_production/viewProductionCuttingAndSealingList?pageType=' .
            Input::get('pageType') .
            '&&parentCode=' .
            Input::get('parentCode') .
            '&&m=1'
        );
    }


    public function addProductionGalaCuttingDetail(Request $request)
    {
        $request->validate([
            'item_id' => 'required|array',
            'machine_id' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {

            foreach ($request->item_id as $key => $itemId) {

                $csQty = $request->qty[$key] ?? 0;   // raw qty
                $galaQty = $request->gala_qty[$key] ?? 0; // produced qty

                // ========================
                // CHECK STOCK (CUT/SEALED ITEM)
                // ========================
                $availableQty = ReuseableCode::get_stock(
                    $request->raw_item_id[0],
                    0,
                    $csQty,
                    0
                );

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return "Insufficient stock for item " .
                        CommonHelper::get_item_name($request->raw_item_id[0]);
                }

                // ========================
                // INSERT GALA CUTTING
                // ========================
                $data2 = [
                    'cutting_sealing_id' => $request->roll_id,
                    'item_id' => $itemId,
                    'machine_id' => $request->machine_id[$key],
                    'operator_id' => $request->operator_id[$key] ?? null,
                    'shift_id' => $request->shift_id[$key] ?? null,
                    'cs_qty' => $csQty,
                    'gala_qty' => $galaQty,
                    'date' => $request->date[$key] ?? now(),
                    'status' => 1,
                    'username' => Auth::user()->name,
                ];

                $galaId = DB::connection('mysql2')
                    ->table('production_gala_cutting')
                    ->insertGetId($data2);

                // ========================
                // STOCK OUT (CUT/SEALED ITEM)
                // ========================
                $rawDetail = CommonHelper::get_subitem_detail2($request->raw_item_id[0]);

                ReuseableCode::postStock(
                    $galaId,
                    0,
                    $request->roll_id,
                    $request->date[$key] ?? now(),
                    9,
                    $rawDetail->rate ?? 0,
                    $request->raw_item_id[0],
                    $rawDetail->sub_ic ?? '',
                    $csQty
                );

                // ========================
                // STOCK IN (GALA ITEM)
                // ========================
                $finishDetail = CommonHelper::get_subitem_detail2($itemId);

                ReuseableCode::postStock(
                    $galaId,
                    0,
                    $request->roll_id,
                    $request->date[$key] ?? now(),
                    11,
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $galaQty
                );
            }

            // update used qty in cutting & sealing
            DB::connection('mysql2')
                ->table('production_cutting_and_sealing')
                ->where('id', $request->roll_id)
                ->update([
                    'used_qty' => $request->used_qty_total,
                ]);

            DB::connection('mysql2')->commit();

        } catch (\Exception $e) {
            DB::connection('mysql2')->rollback();
            return $e->getMessage();
        }

        Session::flash('dataInsert', 'Successfully Saved.');

        return Redirect::to(
            'far_production/viewProductionGalaCuttingList?pageType=' .
            Input::get('pageType') .
            '&&parentCode=' .
            Input::get('parentCode') .
            '&&m=1'
        );
    }


    public function addProductionPackingDetail(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item_id' => 'required|array',
            'machine_id' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {

            foreach ($request->item_id as $key => $itemId) {

                // ========================
                // CHECK STOCK (CUT/SEALED ITEM)
                // ========================
                $availableQty = ReuseableCode::get_stock(
                    $request->raw_item_id[0],
                    0,
                    $request->qty[$key],
                    0
                );

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return "Insufficient stock for item " .
                        CommonHelper::get_item_name($request->raw_item_id[0]);
                }


                $rollField = ($request->cutting_type == 'cutting and sealing')
                    ? ['cutting_sealing_id' => $request->roll_id]
                    : ['gala_cutting_id' => $request->roll_id];

                $data2 = array_merge($rollField, [
                    'item_id' => $itemId,
                    'machine_id' => $request->machine_id[$key],
                    'operator_id' => $request->operator_id[$key] ?? null,
                    'shift_id' => $request->shift_id[$key] ?? null,
                    'cutting_qty' => $request->qty[$key] ?? 0,
                    'packing_bags_qty' => $request->bags_qty[$key] ?? 0,
                    'date' => $request->date[$key] ?? now(),
                    'status' => 1,
                    'username' => Auth::user()->name,
                ]);

                $packingId = DB::connection('mysql2')
                    ->table('production_packing')
                    ->insert($data2);


                    $rawDetail = CommonHelper::get_subitem_detail2($request->raw_item_id[0]);

                ReuseableCode::postStock(
                    $packingId,
                    0,
                    $request->roll_id,
                    $request->date[$key] ?? now(),
                    9,
                    $rawDetail->rate ?? 0,
                    $request->raw_item_id[0],
                    $rawDetail->sub_ic ?? '',
                    $request->qty[$key]
                );


                $finishDetail = CommonHelper::get_subitem_detail2($itemId);

                ReuseableCode::postStock(
                    $packingId,
                    0,
                    $request->roll_id,
                    $request->date[$key] ?? now(),
                    11, // IN
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $request->bags_qty[$key]
                );
            }

            // update used qty
            if ($request->cutting_type == 'cutting and sealing') {
                DB::connection('mysql2')
                    ->table('production_cutting_and_sealing')
                    ->where('id', $request->roll_id)
                    ->where('item_id', $request->raw_item_id[0])
                    ->update([
                        'used_qty' => $request->used_qty_total,
                    ]);
            } else {
                DB::connection('mysql2')
                    ->table('production_gala_cutting')
                    ->where('id', $request->roll_id)
                    ->where('item_id', $request->raw_item_id[0])
                    ->update([
                        'used_qty' => $request->used_qty_total,
                    ]);
            }

            DB::connection('mysql2')->commit();

        } catch (\Exception $e) {
            DB::connection('mysql2')->rollback();
            dd($e->getMessage());
        }

        Session::flash('dataInsert', 'Successfully Saved.');

        return Redirect::to(
            'far_production/viewProductionPackingList?pageType=' .
            Input::get('pageType') .
            '&&parentCode=' . Input::get('parentCode') .
            '&&m=1'
        );
    }

}
