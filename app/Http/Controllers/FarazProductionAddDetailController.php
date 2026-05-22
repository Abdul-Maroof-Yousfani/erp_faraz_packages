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
                'mixture_machine_id' => strip_tags($request->mixture_machine_id),
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
                $request->qty,
                null
            );


            foreach ($request->item_id as $key => $itemId) {

                $requiredQty = $request->required_qty[$key];
                // $machine_id = $request->machine_id[$key];
                $data2 = [
                    'production_mixture_id' => $master_id,
                    'item_id' => $itemId,
                    'qty' => $requiredQty,
                    // 'machine_id' => $machine_id,
                ];

                DB::connection('mysql2')
                    ->table('production_mixture_data')
                    ->insert($data2);


                $availableQty = ReuseableCode::get_stock_with_pack_size($itemId, 0, $requiredQty, 0);

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return "This item " . CommonHelper::get_item_name($itemId) . " has 0 qty in stock";
                }


                $itemDetail = CommonHelper::get_subitem_detail2($itemId);

                $itemRate = $itemDetail->rate ?? 0;
                $itemName = $itemDetail->sub_ic ?? '';
                $packSize = (float) ($itemDetail->pack_size ?? 0);
                // Convert KG -> bags using the item's pack_size (subitem.pack_size)
                if ($packSize <= 0) {
                    DB::connection('mysql2')->rollBack();
                    return 'Pack size (subitem.pack_size) is missing/invalid for item ' . CommonHelper::get_item_name($itemId);
                }
                $requiredQtyInBags = (float) $requiredQty / $packSize;
                // dd($requiredQtyInBags);

                ReuseableCode::postStock(
                    $master_id,
                    0,
                    $request->mixing_no,
                    $request->mixing_date,
                    9,
                    $itemRate,
                    $itemId,
                    $itemName,
                    $requiredQtyInBags,
                    null
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

    public function updateProductionMixingDetail(Request $request)
    {
        DB::connection('mysql2')->beginTransaction();

        try {
            $m = $request->input('m', $_GET['m'] ?? '');
            $mixtureId = (int) $request->input('mixture_id');

            $existing = DB::connection('mysql2')
                ->table('production_mixture')
                ->where('id', $mixtureId)
                ->where('status', 1)
                ->first();

            if (!$existing) {
                DB::connection('mysql2')->rollBack();
                Session::flash('dataEdit', 'Mixture not found.');
                return Redirect::to('far_production/viewProductionMixingList?m=' . $m);
            }

            if ((float) ($existing->used_qty ?? 0) > 0) {
                DB::connection('mysql2')->rollBack();
                Session::flash('dataEdit', 'This mixture cannot be edited because it is already used in the next production step.');
                return Redirect::to('far_production/viewProductionMixingList?m=' . $m);
            }

            $pmNo = $existing->pm_no;

            DB::connection('mysql2')->table('stock')
                ->where('main_id', $mixtureId)
                ->where('voucher_no', $pmNo)
                ->whereIn('voucher_type', [9, 11])
                ->delete();

            DB::connection('mysql2')->table('production_mixture')->where('id', $mixtureId)->update([
                'produced_item_id' => strip_tags($request->finish_item_id),
                'production_order_id' => strip_tags($request->production_order_id),
                'mixture_machine_id' => strip_tags($request->mixture_machine_id),
                'date' => strip_tags($request->mixing_date),
                'qty' => strip_tags($request->qty),
                'description' => strip_tags($request->description ?? ''),
                'username' => Auth::user()->name,
            ]);

            DB::connection('mysql2')
                ->table('production_mixture_data')
                ->where('production_mixture_id', $mixtureId)
                ->delete();

            $itemIds = array_values(array_filter((array) $request->input('item_id', []), function ($v) {
                return $v !== null && $v !== '';
            }));
            if (count($itemIds) === 0) {
                DB::connection('mysql2')->rollBack();
                Session::flash('dataEdit', 'Add at least one raw material line.');
                return Redirect::to('far_production/viewProductionMixingList?m=' . $m);
            }

            $finishItemDetail = CommonHelper::get_subitem_detail2($request->finish_item_id);
            $finishRate = $finishItemDetail->rate ?? 0;
            $finishName = $finishItemDetail->sub_ic ?? '';

            ReuseableCode::postStock(
                $mixtureId,
                0,
                $pmNo,
                $request->mixing_date,
                11,
                $finishRate,
                $request->finish_item_id,
                $finishName,
                $request->qty,
                null
            );

            foreach ($request->item_id as $key => $itemId) {
                if ($itemId === '' || $itemId === null) {
                    continue;
                }
                $requiredQty = $request->required_qty[$key];

                $data2 = [
                    'production_mixture_id' => $mixtureId,
                    'item_id' => $itemId,
                    'qty' => $requiredQty,
                ];

                DB::connection('mysql2')
                    ->table('production_mixture_data')
                    ->insert($data2);

                $availableQty = ReuseableCode::get_stock_with_pack_size($itemId, 0, $requiredQty, 0);

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    Session::flash(
                        'dataEdit',
                        'This item ' . CommonHelper::get_item_name($itemId) . ' has 0 qty in stock'
                    );
                    return Redirect::to('far_production/viewProductionMixingList?m=' . $m);
                }

                $itemDetail = CommonHelper::get_subitem_detail2($itemId);
                $itemRate = $itemDetail->rate ?? 0;
                $itemName = $itemDetail->sub_ic ?? '';
                $packSize = (float) ($itemDetail->pack_size ?? 0);
                if ($packSize <= 0) {
                    DB::connection('mysql2')->rollBack();
                    Session::flash(
                        'dataEdit',
                        'Pack size (subitem.pack_size) is missing/invalid for item ' . CommonHelper::get_item_name($itemId)
                    );
                    return Redirect::to('far_production/viewProductionMixingList?m=' . $m);
                }
                $requiredQtyInBags = (float) $requiredQty / $packSize;

                ReuseableCode::postStock(
                    $mixtureId,
                    0,
                    $pmNo,
                    $request->mixing_date,
                    9,
                    $itemRate,
                    $itemId,
                    $itemName,
                    $requiredQtyInBags,
                    null
                );
            }

            DB::connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::connection('mysql2')->rollBack();
            Session::flash('dataEdit', $e->getMessage());
            return Redirect::to('far_production/viewProductionMixingList?m=' . $request->input('m', $_GET['m'] ?? ''));
        }

        Session::flash('dataInsert', 'Successfully updated.');

        return Redirect::to(
            'far_production/viewProductionMixingList?pageType=' .
            $request->input('pageType', '') .
            '&&parentCode=' .
            $request->input('parentCode', '') .
            '&&m=' . $m
        );
    }

    public function addProductionRollingDetail(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item_id' => 'required|array',
            'machine_id' => 'required|array',
            'mixture_qty' => 'required|array',
            'roll_qty' => 'required|array',
            'production_mixture_ids' => 'required|array',
            'raw_item_id' => 'required',
            'used_qty_total' => 'required',
            'shift_id' => 'required',
            'date' => 'required|date',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {

            $productionMixtureIds = array_values(array_filter((array) $request->production_mixture_ids));
            $productionMixtureRows = DB::connection('mysql2')
                ->table('production_mixture')
                ->whereIn('id', $productionMixtureIds)
                ->orderBy('date')
                ->orderBy('id')
                ->get();

            if ($productionMixtureRows->isEmpty()) {
                throw new \Exception('Production mixture not found');
            }

            // for rolling header linkage (same production order restriction already exists on selection)
            $productionOrderId = $productionMixtureRows->first()->production_order_id ?? null;
            $rollingDateInput = $request->input('date');
            $rollingDate = is_array($rollingDateInput)
                ? ($rollingDateInput[0] ?? now()->format('Y-m-d'))
                : ($rollingDateInput ?: now()->format('Y-m-d'));

            $rollingIds = [];
            foreach ($request->item_id as $key => $itemId) {
                $mixtureQty = $request->mixture_qty[$key] ?? 0;

                $rollQty = $request->roll_qty[$key] ?? 0;
                $subCategoryId = $request->sub_category_id[$key] ?? null;

                // ========================
                // INSERT ROLLING RECORD
                // ========================
                $data2 = [
                    'production_order_id' => $productionOrderId,
                    // keep reference to first mixture id (detail consumption is distributed below)
                    'production_mixture_id' => $productionMixtureRows->first()->id,
                    'item_id' => $itemId,
                    'sub_category_id' => $subCategoryId,
                    'machine_id' => $request->machine_id[$key] ?? null,
                    'operator_id' => $request->operator_id[$key] ?? null,
                    'shift_id' => $request->shift_id,
                    'mixture_qty' => $mixtureQty,
                    'roll_qty' => $rollQty,
                    'rolls_qty_kg' => $request->roll_qty_kg[$key] ?? 0,
                    'date' => $rollingDate,
                    'status' => 1,
                    'username' => Auth::user()->name,
                ];

                $rollingId = DB::connection('mysql2')
                    ->table('production_rolling')
                    ->insertGetId($data2);
                $rollingIds[] = $rollingId;



                $finishDetail = CommonHelper::get_subitem_detail2($itemId);

                ReuseableCode::postStock(
                    $rollingId,
                    0,
                    $request->code,
                    $rollingDate,
                    11, // IN
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $request->roll_qty_kg[$key] ?? 0,
                    null
                );
            }


            $rawItemId = $request->raw_item_id;

            $rawQty = (float) $request->used_qty_total;


            $availableQty = ReuseableCode::get_stock_with_pack_size(
                $rawItemId,
                0,
                $rawQty,
                0
            );

            if ($availableQty < 0) {
                DB::connection('mysql2')->rollBack();
                return "Insufficient stock for item " .
                    CommonHelper::get_item_name($rawItemId);
            }

            // Distribute consumed mixture qty across selected mixture rows (FIFO by date,id)
            $remainingToConsume = $rawQty;
            foreach ($productionMixtureRows as $pmRow) {
                if ($remainingToConsume <= 0) {
                    break;
                }

                $rowQty = (float) ($pmRow->qty ?? 0);
                $rowUsed = (float) ($pmRow->used_qty ?? 0);
                $rowRemaining = $rowQty - $rowUsed;

                if ($rowRemaining <= 0) {
                    continue;
                }

                $consumeFromThis = min($rowRemaining, $remainingToConsume);

                DB::connection('mysql2')
                    ->table('production_mixture')
                    ->where('id', $pmRow->id)
                    ->update([
                        'used_qty' => DB::raw('COALESCE(used_qty,0) + ' . $consumeFromThis),
                    ]);

                $remainingToConsume -= $consumeFromThis;
            }

            if ($remainingToConsume > 0.000001) {
                DB::connection('mysql2')->rollBack();
                return "Consumed mixture qty exceeds available mixture balance for code " . $request->code;
            }


            $rawDetail = CommonHelper::get_subitem_detail2($rawItemId);

            ReuseableCode::postStock(
                ($rollingIds[0] ?? 0),
                0,
                $request->code,
                $rollingDate,
                9, // OUT
                $rawDetail->rate ?? 0,
                $rawItemId,
                $rawDetail->sub_ic ?? '',
                $rawQty,
                null
            );

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
        // dd($request->all());
        $request->validate([
            'item_id' => 'required|array',
            'machine_id' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {
            $rollIds = (array) $request->roll_id;
            $firstRollId = null;
            foreach ($rollIds as $rollIdValue) {
                $parsedRollIds = array_filter(array_map('intval', explode(',', (string) $rollIdValue)));
                if (!empty($parsedRollIds)) {
                    $firstRollId = reset($parsedRollIds);
                    break;
                }
            }
            $sourceRoll = $firstRollId
                ? DB::connection('mysql2')->table('production_rolling')->where('id', $firstRollId)->first()
                : null;

            $pro_no = DB::connection('mysql2')
                ->table('production_request')
                ->where('id', $sourceRoll->production_order_id ?? $request->production_order_id)
                ->value('pr_no');
            $pro_no = $pro_no ?: $firstRollId;

            // We'll collect per-roll updates here (only update rolls that were actually used)
            $rollUpdates = [];

            foreach ($request->item_id as $key => $itemId) {
                $printedQty = (float) ($request->printed_roll_qty[$key] ?? 0);

                $rollIdValue = $rollIds[$key] ?? $firstRollId;
                $rowRollIds = array_values(array_filter(array_map('intval', explode(',', (string) $rollIdValue))));
                $rollId = $rowRollIds[0] ?? $firstRollId;

                // ───────────────────────────────────────────────
                // INSERT PRINTING RECORD
                // ───────────────────────────────────────────────
                $data2 = [
                    'production_rolling_id' => $rollId,   // can be null for new/fresh material
                    'item_id' => $itemId,
                    'machine_id' => $request->machine_id[$key] ?? null,
                    'operator_id' => $request->operator_id[$key] ?? null,
                    'shift_id' => $request->shift_id[$key] ?? null,
                    'type' => $request->type_id[$key] ?? null,
                    'color_id' => $request->color[$key] ?? null,
                    'brand_id' => $request->brand[$key] ?? null,
                    'remarks' => $request->remarks[$key] ?? null,
                    'no_of_roll' => $printedQty,
                    'used_no_of_roll' => 0,
                    'date' => $request->date[$key] ?? now(),
                    'status' => 1,
                    'username' => Auth::user()->name,
                ];

                $printingId = DB::connection('mysql2')
                    ->table('production_roll_printing')
                    ->insertGetId($data2);

                // ───────────────────────────────────────────────
                // STOCK OUT (RAW ROLL)
                // ───────────────────────────────────────────────
                $rawDetail = CommonHelper::get_subitem_detail2($itemId);
                ReuseableCode::postStock(
                    $printingId,
                    0,
                    $pro_no,
                    $request->date[$key] ?? now(),
                    9,                           // assuming 9 = stock out
                    $rawDetail->rate ?? 0,
                    $itemId,
                    $rawDetail->sub_ic ?? '',
                    $printedQty,
                    null
                );

                // ───────────────────────────────────────────────
                // STOCK IN (PRINTED ITEM)
                // ───────────────────────────────────────────────
                $finishDetail = CommonHelper::get_subitem_detail2($itemId);
                ReuseableCode::postStock(
                    $printingId,
                    0,
                    $pro_no,
                    $request->date[$key] ?? now(),
                    11,                          // assuming 11 = stock in
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $printedQty,
                    $request->type_id[$key] ?? null
                );

                // Collect quantity per source roll. Grouped rows can contain multiple roll IDs.
                if (!empty($rowRollIds)) {
                    $remainingQtyToApply = $printedQty;
                    $sourceRolls = DB::connection('mysql2')
                        ->table('production_rolling')
                        ->whereIn('id', $rowRollIds)
                        ->orderBy('date')
                        ->orderBy('id')
                        ->get();

                    foreach ($sourceRolls as $sourceRow) {
                        if ($remainingQtyToApply <= 0) {
                            break;
                        }

                        $sourceTotal = (float) ($sourceRow->roll_qty ?? $sourceRow->rolls_qty_kg ?? 0);
                        $sourceUsed = (float) ($sourceRow->printed_rolls_qty_kg ?? 0);
                        $sourceRemaining = max($sourceTotal - $sourceUsed, 0);

                        if ($sourceRemaining <= 0) {
                            continue;
                        }

                        $qtyForThisRoll = min($remainingQtyToApply, $sourceRemaining);
                        $rollUpdates[$sourceRow->id] = ($rollUpdates[$sourceRow->id] ?? 0) + $qtyForThisRoll;
                        $remainingQtyToApply -= $qtyForThisRoll;
                    }

                    if ($remainingQtyToApply > 0.0001) {
                        DB::connection('mysql2')->rollBack();
                        return back()->withErrors(['error' => 'Printed roll quantity exceeds remaining rolling quantity for item ' . CommonHelper::get_item_name($itemId)])->withInput();
                    }
                } elseif ($rollId) {
                    $rollUpdates[$rollId] = ($rollUpdates[$rollId] ?? 0) + $printedQty;
                }
            }

            // Now update each production_rolling record with **its own** total printed qty
            foreach ($rollUpdates as $rollId => $qtyForThisRoll) {
                $qtyForThisRoll = round($qtyForThisRoll, 2);
                DB::connection('mysql2')
                    ->table('production_rolling')
                    ->where('id', $rollId)
                    ->update([
                        'printed_rolls_qty_kg' => DB::raw("ROUND(COALESCE(printed_rolls_qty_kg,0) + $qtyForThisRoll, 2)")
                    ]);
            }

            DB::connection('mysql2')->commit();

            Session::flash('dataInsert', 'Successfully Saved.');
            return Redirect::to(
                'far_production/viewProductionRollPrintingList?pageType=' .
                Input::get('pageType') .
                '&&parentCode=' .
                Input::get('parentCode') .
                '&&m=1'
            );
        } catch (\Exception $e) {
            DB::connection('mysql2')->rollback();
            \Log::error('Production roll printing failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
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
            $wastageItemId = $request->input('wastage_item_id');
            $wastageQty = (float) $request->input('wastage_qty', 0);
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
                    $request->no_of_roll[$key],
                    null
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
                    $qty,
                    null
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

            // wastage section
            $wastageItemId = $request->input('wastage_item_id');
            $wastageQty = (float) $request->input('wastage_qty', 0);

            if ($wastageItemId && $wastageQty > 0) {


                $this->recordProductionWastage('cutting_and_sealing', $wastageItemId, $wastageQty, $request->date[0] ?? now());

                // Stock OUT for wastage
                $wDetail = CommonHelper::get_subitem_detail2($wastageItemId);

                ReuseableCode::postStock(
                    $csId ?? null,
                    0,
                    $request->roll_id[0] ?? $request->roll_id ?? '',
                    $request->date[0] ?? $request->date ?? now(),
                    11,                          // IN
                    $wDetail->rate ?? 0,
                    $wastageItemId,
                    $wDetail->sub_ic ?? '',
                    $wastageQty,
                    null
                );
            }

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

    public function addBulkProductionCuttingAndSealingDetail(Request $request)
    {
        $request->validate([
            'item_id'    => 'required|array',
            'machine_id' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {
            // ── Resolve production order no (same pattern as Roll Printing) ──────
            $allRollIds = [];
            foreach ((array) $request->roll_id as $rv) {
                foreach (array_filter(array_map('intval', explode(',', (string) $rv))) as $rid) {
                    $allRollIds[] = $rid;
                }
            }
            $firstRollPrintId = $allRollIds[0] ?? null;

            // Get the production_rolling_id from the first roll_printing record
            $firstRollPrint = $firstRollPrintId
                ? DB::connection('mysql2')->table('production_roll_printing')->where('id', $firstRollPrintId)->first()
                : null;

            $pro_no = null;
            if ($firstRollPrint && $firstRollPrint->production_rolling_id) {
                $sourceRoll = DB::connection('mysql2')
                    ->table('production_rolling')
                    ->where('id', $firstRollPrint->production_rolling_id)
                    ->first();
                if ($sourceRoll) {
                    $pro_no = DB::connection('mysql2')
                        ->table('production_request')
                        ->where('id', $sourceRoll->production_order_id)
                        ->value('pr_no');
                }
            }
            $pro_no = $pro_no ?: ($firstRollPrintId ?? 'CS');

            // ── Build a map: raw_item_id keyed by roll_id string ─────────────────
            // raw_item_id[] is one per card (master), roll_id[] is one per detail row
            // We need to know which raw_item_id corresponds to which roll_id group
            $rollIdToRawItem = [];
            if ($request->raw_item_id) {
                foreach ($request->raw_item_id as $idx => $rawId) {
                    // printed_roll_qty_sum is also per card — use same index
                    $rollIdToRawItem[$idx] = $rawId;
                }
            }

            // ── Per-roll-group consume tracking ──────────────────────────────────
            $rollConsumeMap = []; // rollIdsStr => ['raw_item_id'=>, 'total_consume'=>, 'roll_ids_arr'=>]
            $csIdLast = null;

            foreach ($request->item_id as $key => $itemId) {
                $qty        = (float) ($request->qty[$key] ?? 0);
                $consumeQty = (float) ($request->printed_roll_qty[$key] ?? 0);
                $rawRollId  = $request->roll_id[$key] ?? '';
                $rollIdFirst = (int) explode(',', $rawRollId)[0];

                // raw_item_id per row — submitted as row_raw_item_id[]
                $rawItemId = $request->row_raw_item_id[$key] ?? ($request->raw_item_id[0] ?? null);

                // ── INSERT CUTTING & SEALING ──────────────────────────────────────
                $data2 = [
                    'printed_rolling_id' => $rollIdFirst,
                    'item_id'            => $itemId,
                    'machine_id'         => $request->machine_id[$key] ?? null,
                    'operator_id'        => $request->operator_id[$key] ?? null,
                    'shift_id'           => $request->shift_id[$key] ?? null,
                    'qty'                => $qty,
                    'used_qty'           => 0,
                    'printed_roll_qty'   => $consumeQty,
                    'remarks'            => $request->remarks[$key] ?? null,
                    'date'               => $request->date[$key] ?? now(),
                    'status'             => 1,
                    'username'           => Auth::user()->name,
                ];
                $csId = DB::connection('mysql2')
                    ->table('production_cutting_and_sealing')
                    ->insertGetId($data2);
                $csIdLast = $csId;

                // ── STOCK IN (produced cut/sealed item) ───────────────────────────
                $finishDetail = CommonHelper::get_subitem_detail2($itemId);
                ReuseableCode::postStock(
                    $csId, 0, $pro_no,
                    $request->date[$key] ?? now(),
                    11,
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $qty,
                    null
                );

                // ── Accumulate consume per unique raw_item + roll group ────────────
                $mapKey = $rawItemId . '|' . $rawRollId;
                if (!isset($rollConsumeMap[$mapKey])) {
                    $rollConsumeMap[$mapKey] = [
                        'raw_item_id'   => $rawItemId,
                        'total_consume' => 0,
                        'roll_ids_arr'  => array_values(array_filter(array_unique(
                            array_map('intval', explode(',', $rawRollId))
                        ))),
                        'csId'          => $csId,
                    ];
                }
                $rollConsumeMap[$mapKey]['total_consume'] += $consumeQty;
            }

            // ── Per roll group: stock out + update used_no_of_roll ────────────────
            foreach ($rollConsumeMap as $rollIdsStr => $entry) {
                $rawItemId    = $entry['raw_item_id'];
                $totalConsume = $entry['total_consume'];
                $rollIdsArr   = $entry['roll_ids_arr'];
                $csId         = $entry['csId'];

                if (!$rawItemId || $totalConsume <= 0) continue;

                // CHECK STOCK
                $availableQty = ReuseableCode::get_stock($rawItemId, 0, $totalConsume, 0);
                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return back()->withErrors(['error' =>
                        'Insufficient stock for item ' . CommonHelper::get_item_name($rawItemId)
                    ])->withInput();
                }

                $rawDetail = CommonHelper::get_subitem_detail2($rawItemId);

                // STOCK OUT (consumed printed roll material)
                ReuseableCode::postStock(
                    $csId, 0, $pro_no,
                    $request->date[0] ?? now(),
                    9,
                    $rawDetail->rate ?? 0,
                    $rawItemId,
                    $rawDetail->sub_ic ?? '',
                    $totalConsume,
                    null
                );

                // UPDATE used_no_of_roll — distribute proportionally across merged rolls
                if (count($rollIdsArr) === 1) {
                    DB::connection('mysql2')
                        ->table('production_roll_printing')
                        ->where('id', $rollIdsArr[0])
                        ->update(['used_no_of_roll' => DB::raw("ROUND(COALESCE(used_no_of_roll,0) + {$totalConsume}, 2)")]);
                } else {
                    $rollRows   = DB::connection('mysql2')
                        ->table('production_roll_printing')
                        ->whereIn('id', $rollIdsArr)
                        ->select('id', 'no_of_roll', DB::raw('ROUND(COALESCE(used_no_of_roll,0),2) as used_no_of_roll'))
                        ->get();

                    // Distribute by filling each roll sequentially (oldest first) up to its remaining
                    $remaining = $totalConsume;
                    foreach ($rollRows as $rollRow) {
                        if ($remaining <= 0) break;
                        $rollRemaining = round(max((float)$rollRow->no_of_roll - (float)$rollRow->used_no_of_roll, 0), 2);
                        $share = round(min($remaining, $rollRemaining), 2);
                        if ($share <= 0) continue;
                        DB::connection('mysql2')
                            ->table('production_roll_printing')
                            ->where('id', $rollRow->id)
                            ->update(['used_no_of_roll' => DB::raw("ROUND(COALESCE(used_no_of_roll,0) + {$share}, 2)")]);
                        $remaining = round($remaining - $share, 2);
                    }
                }
            }

            // ── Wastage section ───────────────────────────────────────────────────
            $wastageItemId = $request->input('wastage_item_id');
            $wastageQty    = (float) $request->input('wastage_qty', 0);

            if ($wastageItemId && $wastageQty > 0) {
                $this->recordProductionWastage('cutting_and_sealing', $wastageItemId, $wastageQty, $request->date[0] ?? now());

                $wDetail = CommonHelper::get_subitem_detail2($wastageItemId);
                ReuseableCode::postStock(
                    0, 0,
                    $request->roll_id[0] ?? '',
                    $request->date[0] ?? now(),
                    11,
                    $wDetail->rate ?? 0,
                    $wastageItemId,
                    $wDetail->sub_ic ?? '',
                    $wastageQty,
                    null
                );
            }

            DB::connection('mysql2')->commit();

        } catch (\Exception $e) {
            DB::connection('mysql2')->rollback();
            \Log::error('Bulk C&S failed: ' . $e->getMessage(), [
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'request' => $request->all(),
            ]);
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
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
        // dd($request->all());
        $request->validate([
            'item_id' => 'required|array',
            'machine_id' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {

            foreach ($request->item_id as $key => $itemId) {

                $csQty = $request->qty[$key] ?? 0;   // raw qty
                $galaQty = $request->gala_qty[$key] ?? 0; // produced qty
                $rollIds = array_values(array_filter(array_unique(
                    array_map('intval', explode(',', (string) ($request->roll_id[$key] ?? '')))
                )));
                $firstRollId = $rollIds[0] ?? 0;

                // ========================
                // CHECK STOCK (CUT/SEALED ITEM)
                // ========================
                $availableQty = ReuseableCode::get_stock(
                    $request->raw_item_id[$key],
                    0,
                    $csQty,
                    0
                );

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return "Insufficient stock for item " .
                        CommonHelper::get_item_name($request->raw_item_id[$key]);
                }

                // ========================
                // INSERT GALA CUTTING
                // ========================
                $data2 = [
                    'cutting_sealing_id' => $firstRollId,
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
                $rawDetail = CommonHelper::get_subitem_detail2($request->raw_item_id[$key]);

                ReuseableCode::postStock(
                    $galaId,
                    0,
                    $request->roll_id[$key],
                    $request->date[$key] ?? now(),
                    9,
                    $rawDetail->rate ?? 0,
                    $request->raw_item_id[$key],
                    $rawDetail->sub_ic ?? '',
                    $csQty,
                    null
                );

                // ========================
                // STOCK IN (GALA ITEM)
                // ========================
                $finishDetail = CommonHelper::get_subitem_detail2($itemId);

                ReuseableCode::postStock(
                    $galaId,
                    0,
                    $request->roll_id[$key],
                    $request->date[$key] ?? now(),
                    11,
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $galaQty,
                    null
                );

                $remainingConsumeQty = (float) $csQty;
                $cuttingRows = DB::connection('mysql2')
                    ->table('production_cutting_and_sealing')
                    ->whereIn('id', $rollIds)
                    ->select('id', 'qty', DB::raw('ROUND(COALESCE(used_qty,0),2) as used_qty'))
                    ->orderBy('date')
                    ->orderBy('id')
                    ->get();

                foreach ($cuttingRows as $cuttingRow) {
                    if ($remainingConsumeQty <= 0) {
                        break;
                    }

                    $rowRemainingQty = round(max((float) $cuttingRow->qty - (float) $cuttingRow->used_qty, 0), 2);
                    $consumeShare = round(min($remainingConsumeQty, $rowRemainingQty), 2);

                    if ($consumeShare <= 0) {
                        continue;
                    }

                    DB::connection('mysql2')
                        ->table('production_cutting_and_sealing')
                        ->where('id', $cuttingRow->id)
                        ->update([
                            'used_qty' => DB::raw("ROUND(COALESCE(used_qty,0) + {$consumeShare}, 2)")
                        ]);

                    $remainingConsumeQty = round($remainingConsumeQty - $consumeShare, 2);
                }

                if ($remainingConsumeQty > 0) {
                    DB::connection('mysql2')->rollBack();
                    return "Gala cutting quantity exceeds available C&S quantity for item " .
                        CommonHelper::get_item_name($request->raw_item_id[$key]);
                }
            }

            // wastage section
            $wastageItemId = $request->input('wastage_item_id');
            $wastageQty = (float) $request->input('wastage_qty', 0);

            if ($wastageItemId && $wastageQty > 0) {


                $this->recordProductionWastage('gala_cutting', $wastageItemId, $wastageQty, $request->date[0] ?? now());

                // Stock OUT for wastage
                $wDetail = CommonHelper::get_subitem_detail2($wastageItemId);

                ReuseableCode::postStock(
                    0,
                    0,
                    $request->roll_id[0] ?? $request->roll_id ?? '',
                    $request->date[0] ?? $request->date ?? now(),
                    11,                          // IN
                    $wDetail->rate ?? 0,
                    $wastageItemId,
                    $wDetail->sub_ic ?? '',
                    $wastageQty,
                    null
                );
            }


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
                $sourceType = !empty($request->cutting_type)
                    ? (($request->cutting_type == 'gala cutting') ? 'gala' : 'cutting and sealing')
                    : ($request->secondary_cutting_type[$key] ?? 'cutting and sealing');
                $sourceType = ($sourceType === 'gala cutting') ? 'gala' : $sourceType;
                $rollIds = array_values(array_filter(array_unique(
                    array_map('intval', explode(',', (string) ($request->roll_id[$key] ?? '')))
                )));
                $firstRollId = $rollIds[0] ?? 0;

                // ========================
                // CHECK STOCK (CUT/SEALED ITEM)
                // ========================
                $availableQty = ReuseableCode::get_stock(
                    $request->raw_item_id[$key],
                    0,
                    $request->qty[$key],
                    0
                );

                if ($availableQty < 0) {
                    DB::connection('mysql2')->rollBack();
                    return "Insufficient stock for item " .
                        CommonHelper::get_item_name($request->raw_item_id[$key]);
                }


                $rollField = ($sourceType === 'gala')
                    ? ['gala_cutting_id' => $firstRollId]
                    : ['cutting_sealing_id' => $firstRollId];

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
                    ->insertGetId($data2);


                $rawDetail = CommonHelper::get_subitem_detail2($request->raw_item_id[$key]);

                ReuseableCode::postStock(
                    $packingId,
                    0,
                    $request->roll_id[$key],
                    $request->date[$key] ?? now(),
                    9,
                    $rawDetail->rate ?? 0,
                    $request->raw_item_id[$key],
                    $rawDetail->sub_ic ?? '',
                    $request->qty[$key],
                    null
                );


                $finishDetail = CommonHelper::get_subitem_detail2($itemId);

                ReuseableCode::postStock(
                    $packingId,
                    0,
                    $request->roll_id[$key],
                    $request->date[$key] ?? now(),
                    11, // IN
                    $finishDetail->rate ?? 0,
                    $itemId,
                    $finishDetail->sub_ic ?? '',
                    $request->bags_qty[$key],
                    null
                );

                $remainingConsumeQty = (float) ($request->qty[$key] ?? 0);
                $sourceTable = ($sourceType === 'gala')
                    ? 'production_gala_cutting'
                    : 'production_cutting_and_sealing';
                $sourceQtyColumn = ($sourceType === 'gala') ? 'gala_qty' : 'qty';

                $sourceRows = DB::connection('mysql2')
                    ->table($sourceTable)
                    ->whereIn('id', $rollIds)
                    ->select('id', $sourceQtyColumn . ' as source_qty', DB::raw('ROUND(COALESCE(used_qty,0),2) as used_qty'))
                    ->orderBy('date')
                    ->orderBy('id')
                    ->get();

                foreach ($sourceRows as $sourceRow) {
                    if ($remainingConsumeQty <= 0) {
                        break;
                    }

                    $rowRemainingQty = round(max((float) $sourceRow->source_qty - (float) $sourceRow->used_qty, 0), 2);
                    $consumeShare = round(min($remainingConsumeQty, $rowRemainingQty), 2);

                    if ($consumeShare <= 0) {
                        continue;
                    }

                    DB::connection('mysql2')
                        ->table($sourceTable)
                        ->where('id', $sourceRow->id)
                        ->update([
                            'used_qty' => DB::raw("ROUND(COALESCE(used_qty,0) + {$consumeShare}, 2)")
                        ]);

                    $remainingConsumeQty = round($remainingConsumeQty - $consumeShare, 2);
                }

                if ($remainingConsumeQty > 0) {
                    DB::connection('mysql2')->rollBack();
                    return "Packing quantity exceeds available source quantity for item " .
                        CommonHelper::get_item_name($request->raw_item_id[$key]);
                }
            }

            // update used qty

            // wastage section
            $wastageItemId = $request->input('wastage_item_id');
            $wastageQty = (float) $request->input('wastage_qty', 0);

            if ($wastageItemId && $wastageQty > 0) {


                $this->recordProductionWastage('packing', $wastageItemId, $wastageQty, $request->date[0] ?? now());

                // Stock OUT for wastage
                $wDetail = CommonHelper::get_subitem_detail2($wastageItemId);

                ReuseableCode::postStock(
                    0,
                    0,
                    $request->roll_id[0] ?? $request->roll_id ?? '',
                    $request->date[0] ?? $request->date ?? now(),
                    11,                          // IN
                    $wDetail->rate ?? 0,
                    $wastageItemId,
                    $wDetail->sub_ic ?? '',
                    $wastageQty,
                    null
                );
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

    public function addProductionWastageDetail(Request $request)
    {
        $request->validate([
            'production_order_id' => 'required',
            'process' => 'required',
            'wastage_date' => 'required|date',
            'item_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {
            $totalQty = 0;
            $firstItemId = 0;
            $detailRows = [];

            foreach ($request->item_id as $key => $itemId) {
                $qty = (float) ($request->qty[$key] ?? 0);

                if (!$itemId || $qty <= 0) {
                    continue;
                }

                $firstItemId = $firstItemId ?: $itemId;
                $totalQty += $qty;
                $detailRows[] = [
                    'item_id' => $itemId,
                    'qty' => $qty,
                    'ppc' => $request->remarks[$key] ?? null,
                ];
            }

            if (empty($detailRows)) {
                DB::connection('mysql2')->rollBack();
                return back()->withErrors(['error' => 'Please add at least one wastage item with qty.'])->withInput();
            }

            $masterId = DB::connection('mysql2')->table('wastage')->insertGetId([
                'production_order_id' => $request->production_order_id,
                'type' => 'production',
                'process' => $request->process,
                'item_id' => $firstItemId,
                'warehouse_id' => 0,
                'batch_code' => '',
                'qty' => $totalQty,
                'remarks' => implode(' | ', array_filter((array) $request->remarks)),
                'wastage_date' => $request->wastage_date,
                'username' => Auth::user()->name,
                'status' => 1,
                'date' => date('Y-m-d'),
            ]);

            foreach ($detailRows as $row) {
                $row['master_id'] = $masterId;
                DB::connection('mysql2')->table('wastage_data')->insert($row);
            }

            DB::connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::connection('mysql2')->rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        Session::flash('dataInsert', 'Wastage Successfully Saved.');

        return Redirect::to(
            'far_production/addProductionWastage?pageType=' .
            Input::get('pageType') .
            '&&parentCode=' . Input::get('parentCode') .
            '&&m=' . Input::get('m')
        );
    }

    public function updateProductionWastageDetail(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'production_order_id' => 'required',
            'process' => 'required',
            'wastage_date' => 'required|date',
            'item_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        DB::connection('mysql2')->beginTransaction();

        try {
            $itemIds = (array) $request->item_id;
            $qtys = (array) $request->qty;
            $remarks = (array) $request->remarks;
            $totalQty = 0;
            $firstItemId = 0;
            $detailRows = [];

            foreach ($itemIds as $key => $itemId) {
                $qty = (float) ($qtys[$key] ?? 0);

                if (!$itemId || $qty <= 0) {
                    continue;
                }

                $firstItemId = $firstItemId ?: $itemId;
                $totalQty += $qty;
                $detailRows[] = [
                    'master_id' => $request->id,
                    'item_id' => $itemId,
                    'qty' => $qty,
                    'ppc' => $remarks[$key] ?? null,
                ];
            }

            if (empty($detailRows)) {
                DB::connection('mysql2')->rollBack();
                return back()->withErrors(['error' => 'Please add at least one wastage item with qty.'])->withInput();
            }

            DB::connection('mysql2')->table('wastage')
                ->where('id', $request->id)
                ->update([
                    'production_order_id' => $request->production_order_id,
                    'type' => 'production',
                    'process' => $request->process,
                    'item_id' => $firstItemId,
                    'qty' => $totalQty,
                    'remarks' => implode(' | ', array_filter($remarks)),
                    'wastage_date' => $request->wastage_date,
                    'username' => Auth::user()->name,
                ]);

            DB::connection('mysql2')->table('wastage_data')->where('master_id', $request->id)->delete();

            foreach ($detailRows as $row) {
                DB::connection('mysql2')->table('wastage_data')->insert($row);
            }

            DB::connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::connection('mysql2')->rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        Session::flash('dataInsert', 'Wastage Successfully Updated.');

        return Redirect::to(
            'far_production/viewProductionWastageList?pageType=' .
            Input::get('pageType') .
            '&&parentCode=' . Input::get('parentCode') .
            '&&m=' . Input::get('m')
        );
    }

    public function deleteProductionWastage(Request $request)
    {
        DB::connection('mysql2')->table('wastage')
            ->where('id', $request->id)
            ->update([
                'status' => 0,
                'username' => Auth::user()->name,
            ]);

        return 'true';
    }

    private function recordProductionWastage($process, $itemId, $qty, $date, $productionOrderId = null, $remarks = null)
    {
        $masterId = DB::connection('mysql2')->table('wastage')->insertGetId([
            'production_order_id' => $productionOrderId,
            'type' => 'production',
            'process' => $process,
            'item_id' => $itemId,
            'warehouse_id' => 0,
            'batch_code' => '',
            'qty' => $qty,
            'remarks' => $remarks,
            'wastage_date' => $date,
            'username' => Auth::user()->name,
            'status' => 1,
            'date' => date('Y-m-d'),
        ]);

        DB::connection('mysql2')->table('wastage_data')->insert([
            'master_id' => $masterId,
            'item_id' => $itemId,
            'qty' => $qty,
            'ppc' => $remarks,
        ]);

        return $masterId;
    }

}
