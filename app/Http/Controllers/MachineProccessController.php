<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Models\ProductionPlane;
use App\Models\ProductionPlaneData;
use App\Models\MachineProccess;
use App\Models\MachineProccessData;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionData;
use App\Models\Sales_Order;
use App\Models\InventoryMaster\Machine;
use App\Models\InventoryMaster\Operator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Auth;

class MachineProccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function createMachineProccess()
    {
        // Exclude production plans already completed (proccess_status = 3)
        $material_requisition = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->leftJoin('machine_proccesses as mp', function ($join) {
                $join->on('mp.production_plane_id', '=', 'pp.id')
                     ->where('mp.status', 1);
            })
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('pp.status', 1)
            ->where('pp.type', 2)
            ->where(function ($q) {
                $q->whereNull('mp.proccess_status')
                  ->orWhere('mp.proccess_status', '!=', 3);
            })
            ->select('mr.id', 'pp.order_no', 'pp.order_date','pp.id as pp_id','s.sub_ic')
            ->groupBy('mr.id')
            ->get();
          
        $machine = Machine::where('status',1)->get();
        $operator = Operator::where('status',1)->get();
        $sales_Order = Sales_Order::where('status',1)->get();
    
        return view('selling.machineproccess.createMachineProccess',compact('material_requisition','machine','operator','sales_Order'));
    }

    public function getMaterialIssueList(Request $request)
    {

        $ProductionPlane = ProductionPlane::where('status',1)->where('type',1)->get();

        if($request->ajax())
        {

            $list = DB::connection('mysql2')->table('production_plane as pp')
                ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
                ->join('material_requisition_datas as mrd', 'mr.id', '=', 'mrd.mr_id')
                ->join('subitem as s', 's.id', '=', 'mrd.raw_item_id')
                ->join('uom as u', 's.uom', '=', 'u.id')
                ->where('mr.status', 1)
                ->where('mrd.status', 1)
                ->where('pp.status', 1)
                ->where('pp.id', $request->pp_id)
                ->groupBy('pp.id', 'mrd.issuance_date')
                ->select(
                    'pp.id as pp_id',
                    'mr.id as mr_id',
                    'pp.order_no',
                    'mr.mr_no',
                    'mrd.issuance_date',
                    DB::raw('GROUP_CONCAT(s.sub_ic) as item'),
                    DB::raw('GROUP_CONCAT(CONCAT(mrd.issuance_qty, " ", u.uom_name)) as qty')
                );

                if(!empty($request->fromDate) && empty($request->toDate))
                {
                    $list = $list->where('mrd.issuance_date','>=', $request->fromDate);
                }
                
                if(empty($request->fromDate) && !empty($request->toDate))
                {
                    $list = $list->where('mrd.issuance_date','<=', $request->toDate);
                }

                $list = $list->get();

            return view('selling.machineproccess.ajax.getMaterialIssueListAjax',compact('list'));

        }

        return view('selling.machineproccess.getMaterialIssueList',compact('ProductionPlane'));
    }

    public function rawMaterialRequistion(Request $request)
    {
        $data = explode(",",$request->id);
        $pp_id = $data[0];
        $mr_id = $data[1];
        $issuance_date = $data[2];

        $list = DB::connection('mysql2')->table('production_plane as pp')
        ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
        ->join('material_requisition_datas as mrd', 'mr.id', '=', 'mrd.mr_id')
        ->join('subitem as s', 's.id', '=', 'mrd.raw_item_id')
        ->join('uom as u', 's.uom', '=', 'u.id')
        ->where('mr.status', 1)
        ->where('mrd.status', 1)
        ->where('pp.status', 1)
        ->where('pp.id', $pp_id)
        ->select(
            'pp.id as pp_id',
            'mr.id as mr_id',
            'pp.order_no',
            'mr.mr_no',
            'mrd.issuance_date',
            's.sub_ic as item',
            DB::raw('CONCAT(mrd.issuance_qty, " ", u.uom_name) as qty')
        )->where('mrd.issuance_date','=', $issuance_date)->get();

        $mainData = DB::connection('mysql2')->table('production_plane as pp')
            ->join('production_plane_data as ppd', 'pp.id', '=', 'ppd.master_id')
            ->join('sales_order as so', 'so.id', '=', 'pp.sales_order_id')
            ->join('sales_order_data as sod', function($join) {
                $join->on('sod.master_id', '=', 'pp.sales_order_id')
                    ->on('ppd.finish_goods_id', '=', 'sod.item_id');
            })
            ->join('subitem as s', 's.id', '=', 'ppd.finish_goods_id')
            ->where('pp.id',$pp_id)
            ->select(
                'so.purchase_order_no',
                's.sub_ic',
                'pp.wall_thickness_1'
            )->first();

        return view('selling.machineproccess.ajax.rawMaterialRequistion',compact('list','issuance_date','mainData'));
       
    }
    
    public function createGeneralMachineProccess()
    {
        $Machine = Machine::where('status',1)->get();
        $Operator = Operator::where('status',1)->get();
        
        $material_requisition = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('pp.status', 1)
            ->where('pp.type', 2)
            ->groupBy('mr.id')
            ->select('mr.id', 'pp.order_no', 'pp.order_date','pp.id as pp_id','s.sub_ic')
            ->get();
    
        return view('selling.machineproccess.createGeneralMachineProccess',compact('material_requisition','Machine','Operator'));
    }
    
    public function GeneralMachineProccessToSo()
    {
        $Machine = Machine::where('status',1)->get();
        $Operator = Operator::where('status',1)->get();
        
        $material_requisition_general = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('pp.status', 1)
            ->where('pp.type', 2)
            ->groupBy('mr.id')
            ->select('mr.id', 'pp.order_no', 'pp.order_date','pp.id as pp_id','s.sub_ic')
            ->get();

        $material_requisition_so = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('pp.status', 1)
            ->where('pp.type', 1)
            ->groupBy('mr.id')
            ->select('mr.id', 'pp.order_no', 'pp.order_date','pp.id as pp_id','s.sub_ic')
            ->get();
    
        return view('selling.machineproccess.GeneralMachineProccessToSo',compact('material_requisition_general','material_requisition_so','Machine','Operator'));
    }

    public function productionPlanAgainstSo(Request $request)
    {
       $material_requisition = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('subitem as s', 's.id', '=', 'mr.finish_good_id')
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('pp.status', 1)
            ->where('pp.type', $request->type);

            if($request->type == 1){
                $material_requisition = $material_requisition->where('pp.sales_order_id',$request->id);
            }


            $material_requisition = $material_requisition
            ->groupBy('mr.id')
            ->select('mr.id', 'pp.order_no', 'pp.order_date','pp.id as pp_id','s.sub_ic')
            ->get();

       ?>
        <option value="">Select Production Plan</option>
        <?php foreach ($material_requisition as $item): ?>
            <option value="<?php echo $item->id ?>" data-value="<?php echo  $item->pp_id ?>"> <?php echo $item->order_no." -- ".$item->order_date." -- ".$item->sub_ic ?>  </option>
        <?php
        endforeach;

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMachineProccess(Request $request)
    {
        ini_set('max_execution_time', 12000);

        DB::Connection('mysql2')->beginTransaction();
        try {
            if (empty($request->cmp)) {

                $mrData = MaterialRequisition::where('id', $request->mr_id)->where('status', 1)->get();
  
                foreach ($mrData as $key => $value) {
                    $finish_amount = 0;
                    $mr = MaterialRequisition::where('status', 1)->where('id', $value->id)->first();

                    $machine_no = CommonHelper::generateUniquePosNo('machine_proccesses', 'machine_no', 'MRP');
                    $machine_process = new MachineProccess;
                    $machine_process->mr_id = $mr->id;
                    $machine_process->production_plane_id = $mr->production_id;
                    $machine_process->finish_good_id = $mr->finish_good_id;
                    $machine_process->finish_good_qty = $mr->finish_good_qty;
                    $machine_process->so_id = $request->so_id;
                    $machine_process->serial_no = $request->serial_no;
                    $machine_process->machine_process_date = $request->machine_process_date;

                    $machine_process->save();
                    $mr->mr_status = 3;
                    $mr->save();

                    $machine_process_id = $machine_process->id;
                }
                $raw_item_array = [];
                foreach ($request->mr_data_id as $key => $value) {
                    $materialRequisitionData = MaterialRequisitionData::find($value);
                    if ($materialRequisitionData) {
                        if (!in_array($materialRequisitionData->raw_item_id, $raw_item_array)) {
                            $finish_amount += $materialRequisitionData->avg_rate;
                            $raw_item_array[] = $materialRequisitionData->raw_item_id;
                        }
        
                        $materialRequisitionData->material_stage = 2;
                        $materialRequisitionData->save();
                    }
                }

                $machine_process->rate = $finish_amount;
                $machine_process->save();
                
                $machine_process_id = $machine_process->id;

            } else {
                $machine_process_id = $request->cmp;
            }


            $Bundle = ($request->Bundle) ? $request->Bundle : 1;

            $mp = MachineProccess::findOrFail($machine_process_id);
            $mr = MaterialRequisition::where('status', 1)->where('id', $mp->mr_id)->first();

            for ($i = 0; $i < $Bundle; $i++) {
                # code...

                if (!$mp) {
                    return 2;
                } else {
                    $code = ($mp->serial_no) ? $mp->serial_no : 'PKG';
                }

                $batch_no = ProductionHelper::getNewBatchCode();

                if ($mp->finish_good_qty == $request->remaining) {
                    $mp->proccess_status = 3;
                } else {
                    $mp->proccess_status = 2; 
                }

                $mchaine_data = new MachineProccessData;
                $mchaine_data->machine_proccess_id = $machine_process_id;
                $mchaine_data->mr_data_id = $mp->mr_id;
                $mchaine_data->finish_good_id = $mp->finish_good_id;
                $mchaine_data->request_qty = $request->remaining;
                $mchaine_data->color_line = $request->color;
                $mchaine_data->remarks = $request->remarks;
                $mchaine_data->batch_no = $batch_no;

                $mchaine_data->machine_id = $request->machine_id;
                $mchaine_data->operator_id = $request->operator_id;
                $mchaine_data->shift = $request->shift;

                $mchaine_data->recieved_date = $mp->machine_process_date;
                //   $mchaine_data->recieved_date = date('Y-m-d');

                // if(!$mchaine_data->save()){
                //     return 2;
                // }

                $mchaine_data->save();

                // $mchaine_data_id = $mchaine_data->id;

                // $stock = array
                // (
                //     'main_id' => $mchaine_data_id,
                //     'master_id' => $machine_process_id,
                //     'voucher_no' => $batch_no, // $mr->mr_no,
                //     'voucher_date' => $mp->machine_process_date,
                //     'voucher_type' => 1,
                //     'sub_item_id' => $mp->finish_good_id,
                //     'qty' => $request->remaining,
                //     'amount' => $mp->rate *  $request->remaining,
                //     'rate' => $mp->rate,
                //     // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                //     'status' => 1,
                //     'warehouse_id' => 1, //$request->$warehouseIdName[$itemKey],
                //     'username' => Auth::user()->username,
                //     'created_date' => date('Y-m-d'),
                //     'created_date' => date('Y-m-d'),
                //     'opening' => 0,
                // );

                // DB::Connection('mysql2')->table('stock')->insert($stock);
            }

            DB::Connection('mysql2')->commit();
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('createMachineProccess')->with('error', $e->getMessage());
        }
        return redirect()->route('createMachineProccess')->with('dataInsert', 'MR goining to procees SuccessFully');
    }
    
    public function GeneralMachineProccessToSoStore(Request $request)
    {
        ini_set('max_execution_time', 12000);

        $g_mr_id = $request->g_mr_id;
        $g_pp_id = $request->g_pp_id;
        $so_mr_id = $request->so_mr_id;
        $so_pp_id = $request->so_pp_id;
        $old_machine_proccess_id = $request->machine_proccess_id;
        $machine_proccess_id = 0;
        
        
        DB::Connection('mysql2')->beginTransaction();
        try
        {

            $checked = MachineProccess::where('mr_id',$so_mr_id)->where('production_plane_id',$so_pp_id)->first();

            if(empty($checked))
            {
                $ProductionPlane = ProductionPlane::where('id',$so_pp_id)->first();
                $ProductionPlaneData = ProductionPlaneData::where('master_id',$so_pp_id)->first();

                $MachineProccesss = new MachineProccess;
                $MachineProccesss->mr_id = $so_mr_id;
                $MachineProccesss->production_plane_id = $so_pp_id;
                $MachineProccesss->work_order_id = 0;
                $MachineProccesss->status = 1;
                $MachineProccesss->ready_qty = 0;
                $MachineProccesss->finish_good_id = $ProductionPlaneData->finish_goods_id;
                $MachineProccesss->finish_good_qty = $ProductionPlaneData->planned_qty;
                $MachineProccesss->proccess_status = 1;
                $MachineProccesss->so_id = $ProductionPlane->sales_order_id;
                $MachineProccesss->machine_process_date = date('Y-m-d');
                $MachineProccesss->finish_good_type = 'complete_finish_good';
                
                $MachineProccesss->save();

                $machine_proccess_id = $MachineProccesss->id;
            }
            else
            {
                $machine_proccess_id = $checked->id;

            }

            foreach ($request->process_data_id as $key => $value) {
                
                $check_condition = $request["check_condition_${value}"];
			    $id = $request["id${value}"];

                if($check_condition == 1)
                {

                    MachineProccessData::where('id',$id)->update([
                        'machine_proccess_id' => $machine_proccess_id,
                        'old_machine_proccess_id' => $old_machine_proccess_id,
                    ]);

                }

            }

            DB::Connection('mysql2')->commit();
        }
        catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('GeneralMachineProccessToSo')->with('error', $e->getMessage());
        }
        return redirect()->route('GeneralMachineProccessToSo')->with('dataInsert','attached SuccessFully');
        
    }

    public function storeGeneralMachineProccess(Request $request)
    {
        ini_set('max_execution_time', 120000);

        // echo $request->serial_no;
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        DB::Connection('mysql2')->beginTransaction();
        try {
                if(empty($request->cmp))
                {

                    $mrData =  MaterialRequisition::where('id',$request->mr_id)->where('status',1)->get();
                    // echo "<pre>";
                    // print_r($mrData);
                    // exit();
                    foreach ($mrData as $key => $value) {
                        # code...
                        $mr =  MaterialRequisition::where('status',1)->where('id',$value->id)->first();

                        $machine_no =CommonHelper::generateUniquePosNo('machine_proccesses','machine_no','MRP');
                        $machine_process = new MachineProccess;
                        $machine_process->mr_id = $mr->id;
                        $machine_process->production_plane_id = $mr->production_id;
                        $machine_process->finish_good_id =$mr->finish_good_id;
                        $machine_process->finish_good_qty =$mr->finish_good_qty;
                        // $machine_process->so_id = $request->so_id;
                        $machine_process->finish_good_type = $request->finish_good_type;
                        $machine_process->serial_no = $request->serial_no;
                        $machine_process->machine_process_date = $request->machine_process_date;


                        // echo "<pre>";
                        // print_r($machine_process);
                        // exit();
                        $machine_process->save();
                        $mr->mr_status = 3;
                        $mr->save();

                        $machine_process_id = $machine_process->id;
                    }
                    foreach ($request->mr_data_id as $key => $value) {
                        $MaterialRequisitionData =  MaterialRequisitionData::find($value);
                        $MaterialRequisitionData->material_stage = 2 ;
                        $MaterialRequisitionData->save();
                    }

                }
                else
                {
                    $machine_process_id = $request->cmp;
                }



                //direct creating machine process

                $Bundle = ($request->finish_good_type == 'complete_finish_good') ? $request->received_length : 1 ;
                $received_length = ($request->finish_good_type == 'semi_finish_good') ? $request->received_length : 1 ;

                // $mp =  MachineProccess::findORfail($machine_process_id);
                $mp = MachineProccess::findOrFail($machine_process_id);
                $mr =  MaterialRequisition::where('status',1)->where('id',$mp->mr_id)->first();

                for ($i=0; $i < $Bundle ; $i++) { 
                    # code...
        
                    if(!$mp)
                    { 
                        return 2;
                    }
                    else
                    {
                        if($request->serial_no)
                        {
                            $code = $request->serial_no;
                        }
                        else
                        {
                            $code = ($mp->serial_no) ? $mp->serial_no : '';
                        }
                        
                        $batch_no = ($code) ? CommonHelper::generateUniquePosNoForMachine('machine_proccess_datas','batch_no',$code) : '';
                    }
        
                    // echo "<pre>";
                    // print_r($batch_no);
                    // exit();
                 
                    if($mp->finish_good_qty == $request->received_length)
                    {
                        $mp->proccess_status =  3;
                    }else{
                        $mp->proccess_status =  2;
                    }
                    $mchaine_data =  new MachineProccessData;
                    $mchaine_data->machine_proccess_id = $machine_process_id;
                    $mchaine_data->mr_data_id = $mp->mr_id;
                    $mchaine_data->finish_good_id = $mp->finish_good_id;
                    $mchaine_data->request_qty = $received_length;
                    $mchaine_data->color_line = $request->color;
                    $mchaine_data->remarks = $request->remarks;
                    $mchaine_data->batch_no = $batch_no;
                    
                    $mchaine_data->machine_id = $request->machine_id;
                    $mchaine_data->operator_id = $request->operator_id;
                    $mchaine_data->shift = $request->shift;
        
                    $mchaine_data->recieved_date = $mp->machine_process_date;

                    $mchaine_data->save();

                    $mchaine_data_id = $mchaine_data->id;

                    $stock = array
                        (
                            'main_id' => $mchaine_data_id,
                            'master_id' => $machine_process_id,
                            'voucher_no' => ($batch_no) ? $batch_no : $mr->mr_no,
                            'voucher_date' => $mp->machine_process_date,
                            'voucher_type' => 1,
                            'sub_item_id' => $mp->finish_good_id,
                            'qty' => $received_length,
                           // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                            'status'=> 1,
                            'warehouse_id' => 12, //$request->$warehouseIdName[$itemKey],
                            'username'=>Auth::user()->username,
                            'created_date'=>date('Y-m-d'),
                            'created_date'=>date('Y-m-d'),
                            'opening'=>0,
                        );
        
        
                    DB::Connection('mysql2')->table('stock')->insert($stock);
                }






                DB::Connection('mysql2')->commit();
            }
            catch (Exception $e) {
                DB::Connection('mysql2')->rollBack();
                return redirect()->route('createGeneralMachineProccess')->with('error', $e->getMessage());
            }
        return redirect()->route('createGeneralMachineProccess')->with('dataInsert','MR goining to procees SuccessFully');
    }

    public function deleteMachineProcess(Request $request)
    {

        $data['status'] = 0;
        DB::Connection('mysql2')->beginTransaction();
        try {
        MachineProccessData::where([
                ['id',$request->id],
              
            ])
            ->update($data);

        DB::Connection('mysql2')->table('stock')
            ->where([
                        ['main_id',$request->id],
                        ['master_id',$request->machine_proccess_id],
                        ['voucher_no',$request->batch_no],
                        ['status', 1],
                    ])
            ->update($data);                

            DB::Connection('mysql2')->commit();
        }
        catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('createGeneralMachineProccess')->with('error', $e->getMessage());
        }
    }

    public function delete_all_old(Request $request)
    {

      
        ini_set('max_execution_time', 12000);

        DB::Connection('mysql2')->beginTransaction();
        try {


        $data['status'] = 0;
        foreach ($request->id as $key => $value) {
            # code...

        MachineProccessData::where([
                ['id',$request->id[$key]],
            ])
            ->update($data);

        DB::Connection('mysql2')->table('stock')
            ->where([
                        ['main_id',$request->id[$key]],
                        ['master_id',$request->machine_proccess_id[$key]],
                        ['voucher_no',$request->batch_no[$key]],
                        ['status', 1],
                    ])
            ->update($data);                

        }


             DB::Connection('mysql2')->commit();

             return 2;
        }
        catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return $e->getMessage();
        }
    }
    
    public function delete_all(Request $request)
    {
        ini_set('max_execution_time', 12000);

        DB::Connection('mysql2')->beginTransaction();
        try {

            $data['status'] = 0;
                # code...

            MachineProccessData::whereIn('id', $request->id)
                ->update($data);

            DB::Connection('mysql2')->table('stock')
                ->whereIn('main_id', $request->id)
                ->whereIn('master_id', $request->machine_proccess_id)
                ->whereIn('voucher_no', $request->batch_no)
                ->update($data);                

        
            DB::Connection('mysql2')->commit();

            return 2;
        }
        catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MachineProccess  $machineProccess
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MachineProccess  $machineProccess
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MachineProccess  $machineProccess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MachineProccess  $machineProccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MachineProccess  $machineProccess
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    public function getMrData(Request $request)
    {
        $mr_datas = MaterialRequisitionData::where('mr_id', $request->id)->get();
        return view('selling.machineproccess.getMrData', compact('mr_datas'));
    }

    public function getMrDataWithProductionPlanId(Request $request)
    {
        $mr_datas = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('material_requisition_datas as mrd', 'mr.id', '=', 'mrd.mr_id')
            ->where('mr.status', 1)
            ->where('mrd.status', 1)
            ->where('mrd.material_stage', 1)
            ->where('pp.status', 1)
            ->where('mrd.mr_id', $request->id)
            //->where('pp.id',$request->id)
            ->select('mrd.*')
            ->get();
        return view('selling.machineproccess.getMrData', compact('mr_datas'));
    }

    public function pipeMachineList()
    {
    
       return view('selling.production.pipeMachineList');
    }

    public function viewProductInProccess()
    {
        $machine_process = MachineProccess::
            join('production_plane', 'production_plane.id', 'machine_proccesses.production_plane_id')
            ->join('subitem', 'subitem.id', 'machine_proccesses.finish_good_id')
            ->leftjoin('machine_proccess_datas AS mpd', 'mpd.machine_proccess_id', 'machine_proccesses.id')
            ->select(
                'machine_proccesses.machine_process_date',
                'production_plane.sale_order_no',
                'subitem.item_code',
                'machine_proccesses.machine_no',
                'production_plane.order_no',
                'machine_proccesses.finish_good_qty',
                'machine_proccesses.id',
                DB::raw('sum(mpd.request_qty) as received_qty'),
                'mpd.remarks',
                'mpd.color_line',
                'mpd.machine_id',
                'mpd.operator_id',
                'mpd.shift',
            )
            ->where(['machine_proccesses.status' => 1])
            ->whereIn('machine_proccesses.proccess_status', [1, 2])
            ->groupBy('machine_proccesses.id', 'mpd.machine_proccess_id')
            ->get();

        $Machine = Machine::where('status', 1)->get();
        $Operator = Operator::where('status', 1)->get();

        return view('selling.machineproccess.viewProductInProccess', compact('machine_process', 'Machine', 'Operator'));
    }

    public function viewProductProccessComplete(Request $request)
    {
        $machine_process = MachineProccess::join('machine_proccess_datas as mpd', 'machine_proccesses.id', '=', 'mpd.machine_proccess_id')
            ->join('machine as m', 'mpd.machine_id', '=', 'm.id')
            ->join('operators as o', 'mpd.operator_id', '=', 'o.id')
            ->join('material_requisitions as mr', 'mr.id', '=', 'mpd.mr_data_id')
            ->select('mpd.id', 'mpd.batch_no', 'm.name as machine_name', 'o.name as operator_name', 'mpd.shift', 'machine_proccesses.machine_process_date', 'mpd.request_qty', 'mpd.machine_process_stage', 'mpd.machine_proccess_id', 'mr.mr_no')
            ->where('mpd.status', '=', 1);

        if (!empty($request->machine_proccess_id)) {
            $machine_process = $machine_process->where('mpd.machine_proccess_id', '=', $request->machine_proccess_id);
        } else {
            $machine_process = $machine_process->take(10);

        }

        // if ($request->type == 'sales_order') {
        //     $machine_process = $machine_process->whereNotNull('machine_proccesses.so_id');
        // } else {
        //     $machine_process = $machine_process->whereNull('machine_proccesses.so_id');
        // }




        $machine_process = $machine_process->get();
        return view('selling.machineproccess.viewProductProccessComplete', compact('machine_process'));
    }

    public function machineProcessAttachedToSoProduction(Request $request)
    {
        $range_1 = $request->range_1;
        $range_2 = $request->range_2;

        $machine_process = MachineProccess::join('machine_proccess_datas as mpd', 'machine_proccesses.id', '=', 'mpd.machine_proccess_id')
            ->join('machine as m', 'mpd.machine_id', '=', 'm.id')
            ->join('operators as o', 'mpd.operator_id', '=', 'o.id')
            ->join('material_requisitions as mr', 'mr.id', '=', 'mpd.mr_data_id')
            ->select('mpd.id', 'mpd.batch_no', 'm.name as machine_name', 'o.name as operator_name', 'mpd.shift', 'machine_proccesses.machine_process_date', 'mpd.request_qty', 'mpd.machine_process_stage','mpd.machine_proccess_id' ,'mr.mr_no')
            
            ->where('mpd.status', '=', 1);
        if ($range_1 && $range_2) {
            $machine_process = $machine_process->whereRaw("CAST(SUBSTRING_INDEX(mpd.batch_no, '-', -1) AS UNSIGNED) BETWEEN CAST(SUBSTRING_INDEX(?, '-', -1) AS UNSIGNED) AND CAST(SUBSTRING_INDEX(?, '-', -1) AS UNSIGNED)", [$range_1, $range_2])
            ->orderByRaw("CAST(SUBSTRING_INDEX(mpd.batch_no, '-', -1) AS UNSIGNED) ASC");
        }
        if (!empty($request->machine_proccess_id)) {
            $machine_process = $machine_process->where('mpd.machine_proccess_id', '=', $request->machine_proccess_id);
        } else {
            $machine_process = $machine_process->take(10);
        }

        if ($request->type == 'sales_order') {
            $machine_process = $machine_process->whereNotNull('machine_proccesses.so_id');
        } else {
            $machine_process = $machine_process->whereNull('machine_proccesses.so_id');
        }

        $machine_process = $machine_process->get();
        return view('selling.machineproccess.machineProcessAttachedToSoProduction',compact('machine_process'));  
    }

    public function received_length(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        $Bundle = ($request->Bundle) ? $request->Bundle : 1 ;

        for ($i=0; $i < $Bundle ; $i++) { 
            # code...
            $mp =  MachineProccess::find($request->machine_proccess_id);

            if(!$mp)
            { 
                return 2;
            }
            else
            {
                $code = ($mp->serial_no) ? $mp->serial_no : 'PKG';
            }

            $batch_no =CommonHelper::generateUniquePosNoForMachine('machine_proccess_datas','batch_no',$code);
         
            if($mp->finish_good_qty == $request->received_length)
            {
                $mp->proccess_status =  3;
            }else{
                $mp->proccess_status =  2;
            }

            $mchaine_data =  new MachineProccessData;
            $mchaine_data->machine_proccess_id = $request->machine_proccess_id;
            $mchaine_data->mr_data_id = $mp->mr_id;
            $mchaine_data->finish_good_id = $mp->finish_good_id;
            $mchaine_data->request_qty = $request->received_length;
            $mchaine_data->color_line = $request->color;
            $mchaine_data->remarks = $request->remarks;
            $mchaine_data->batch_no = $batch_no;
            
            $mchaine_data->machine_id = $request->machine_id;
            $mchaine_data->operator_id = $request->operator_id;
            $mchaine_data->shift = $request->shift;

            $mchaine_data->recieved_date = date('Y-m-d');
            if(!$mchaine_data->save()){
                return 2;
            }
        }

        return 1;

    }

    public function RemainingQtyOfSaleOrder(Request $request)
    {

        $result = DB::Connection('mysql2')->table('machine_proccesses as mp')
            ->leftJoin('machine_proccess_datas as mpd', 'mp.id', '=', 'mpd.machine_proccess_id')
            ->where('mpd.status', '=', 1)
            ->where('mp.production_plane_id', '=', $request->pp_id)
            ->groupBy('mp.production_plane_id')
            ->selectRaw('IFNULL(SUM(mpd.request_qty), 0) as total_request_qty')
            ->get();

        $readyQty = $result[0]->total_request_qty ?? 0;
        
        $request_qty = DB::Connection('mysql2')->table('material_requisitions')
            ->where('production_id', '=', $request->pp_id)
            ->sum('finish_good_qty');
        $request_qty = $request_qty ?? 0;

        $remaining = $request_qty - $readyQty;
        return [$readyQty, $request_qty, $remaining];
    }

    public function getMachineProcessAgainstPP(Request $request)
    {
        $data = MachineProccess::where('status',1)->where('production_plane_id', $request->pp_id )->get();
        return $data;
        
        ?>
        <option value="">Select Machine Process </option>
        <?php foreach ($data as $value): ?>
            <option value="<?php echo $value->id ?>" > <?php echo $value->serial_no  == null ? '' : $value->serial_no." -- ".$value->machine_process_date ?>  </option>
        <?php
        endforeach;
    }
}
