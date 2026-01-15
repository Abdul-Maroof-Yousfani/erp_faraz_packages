<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionData;
use App\Models\ProductionPlane;
use App\Models\ProductionPlaneData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;
use Input;
use Auth;
use Config;
use Redirect;
use Illuminate\Support\Facades\Validator;

class MaterialRequisitionController extends Controller
{

    public function listMaterialRequisition(Request $request)
    {
        if($request->ajax())
        {
            // $material_requisitions = MaterialRequisition::where('status',1)->where('approval_status',2)->get();
            $material_requisitions = DB::connection('mysql2')->table('material_requisitions as mr')
            ->join('production_plane as pp', 'mr.production_id', '=', 'pp.id')
            ->select('mr.*', 'pp.pr_no', 'pp.order_no')
            ->where('mr.status',1)
            ->where('mr.approval_status',2)
            ->orderBy('id','desc')
            ->get();
        

            return view('selling.materialrequisition.listMaterialRequisitionAjax',compact('material_requisitions'));
        }
        return view('selling.materialrequisition.listMaterialRequisition');

    }

    public function viewProductionPlane(Request $request)
    {
        $material_requisitions = MaterialRequisition::where('id', $request->id)->where('status', 1)->first();
        return view('selling.production.viewProductionPlane', compact('material_requisitions'));
    }

    public function createMaterialRequisition(Request $request)
    {
       $prodtion_data = ProductionPlaneData::find($request->id);
       return view('selling.materialrequisition.createMaterialRequisition',compact('prodtion_data'));
    }

    public function storeMaterialRequisition(Request $request)
    {
 
        DB::Connection('mysql2')->beginTransaction();
        try {
            $mr_no =CommonHelper::generateUniquePosNo('material_requisitions','mr_no','MR');
            $production =   ProductionPlane::find($request->production_id);
            $mr = new MaterialRequisition;
            $mr->mr_no = $mr_no;
            $mr->mr_date = $request->requisition_date;
            $mr->production_id = $request->production_id;
            $mr->work_id = $production->work_order_id;
            $mr->finish_good_id = $request->finish_goods_id;
            $mr->finish_good_qty = $request->finish_good_qty;
            $mr->category_id = $request->category;
            $mr->over_all_required_qty = $request->qty_for_making_product;
            $mr->mr_status = 1;
            $mr->save();




            foreach($request->item as $key=>$item)
            {
               $mr_data = new MaterialRequisitionData;
               $mr_data->mr_no =  $mr_no;
               $mr_data->mr_id =$mr->id;
               $mr_data->production_data_id = $request->production_data_id;
               $mr_data->receipe_id = $request->receipt_id;
               $mr_data->raw_item_id = $request->item[$key];
               $mr_data->request_qty = $request->required_qty[$key];
               $mr_data->warehouse_id = $request->warehouse_id[$key];
               $mr_data->mr_status = 1;
               $mr_data->save();
               
              $product_plan =  ProductionPlaneData::find($request->production_data_id);
              $product_plan->ppc_status =1;
              $product_plan->save();
            }



            DB::Connection('mysql2')->commit();
            return redirect()->route('listMaterialRequisition')->with('dataInsert','Sale Order Inserted');
       }
       catch ( Exception $e )
       {
           DB::Connection('mysql2')->rollBack();
           return redirect()->route('listMaterialRequisition')->with('error', $e->getMessage());
       }

    }


    public function issueMaterial(Request $request)
    {

        $request_qty = 0;
        $issue_qty = 0;
        $recipt_total = 0;
        DB::Connection('mysql2')->beginTransaction();
        try {

            // foreach($request->item as $key=>$item)
            // {

            //    $mr_data = new MaterialRequisitionData;
            //    $mr_data->mr_no =  $mr->mr_no;
            //    $mr_data->mr_id =$mr->id;
            //    $mr_data->raw_item_id = $request->item[$key];
            //    $mr_data->category_id = CommonHelper::get_category_by_itemid($request->item[$key]);
            //    $mr_data->issuance_qty = $request->required_qty[$key];
            //    $mr_data->warehouse_id = $request->warehouse_id[$key];
            //    $mr_data->issuance_date =  date('Y-m-d');
            //    $mr_data->mr_status = 1;
            //    $mr_data->save();  

            // }
            // foreach($request->recipe_qty  as $key1=>$qty_receipe)
            // {
            //     $recipt_total +=  $qty_receipe;
            // }
            $mr = MaterialRequisition::find($request->mr_id);
            $voucher_no = PurchaseHelper::get_unique_no_transfer(date('Y'), date('m'));
            $stock_transfer_id = null;
            
            foreach ($request->category as $key => $value) {
                $itemName = "item_${key}_${value}";
                $production_floor_stock = "production_floor_stock_${key}_${value}";
                $recipe_item = "recipe_item_${key}_${value}";
                $requiredQtyName = "required_qty_${key}_${value}";
                $warehouseIdName = "warehouse_id_${key}_${value}";
                $issuanceDateName = "issuance_date_${key}_${value}";
                $batchcodeName = "batch_code_${key}_${value}";
               
                if (!empty($request->$itemName)) {
                    foreach ($request->$itemName as $itemKey => $itemValue) {

                        $validator = Validator::make(
                            [

                                'issuance_qty' => $request->$requiredQtyName[$itemKey],
                                'warehouse_id' => $request->$warehouseIdName[$itemKey],
                                'issuance_date' => $request->$issuanceDateName[$itemKey],
                            ],
                            [
                                'issuance_qty' => 'required',
                                'warehouse_id' => 'required',
                                'issuance_date' => 'required',
                            ]
                        );

                        if ($validator->fails()) {
                            return redirect()->back()->withErrors($validator)->withInput();
                        }

                        $avg_amount = ReuseableCode::average_cost_without_wareHouse(
                            $itemValue,
                            $request->$warehouseIdName[$itemKey],
                            ($request->has($batchcodeName) && is_array($request->$batchcodeName)) ? $request->$batchcodeName[$itemKey] : 0
                        );
                    
                        //$amount = $avg_amount ? $avg_amount * $request->$requiredQtyName[$itemKey] : 0;
                    
                        // Insert into MaterialRequisitionData
                        $mrData = new MaterialRequisitionData;
                        
                       
                        if ($request->$recipe_item[$itemKey] == $itemValue) {
                            $mrData->raw_item_id = $itemValue;
                            $mrData->replacement_item_id = 0;
                        } else {
                            $mrData->raw_item_id = $request->$recipe_item[$itemKey];
                            $mrData->replacement_item_id = $itemValue;
                        }
                    
                        $mrData->mr_no = $mr->mr_no;
                        $mrData->mr_id = $mr->id;
                        $mrData->category_id = 7;
                        $mrData->issuance_qty = $request->$requiredQtyName[$itemKey];
                        $mrData->warehouse_id = $request->$warehouseIdName[$itemKey];
                        $mrData->avg_rate = $avg_amount;
                        $mrData->issuance_date = $request->$issuanceDateName[$itemKey];
                        $mrData->batch_code = ($request->has($batchcodeName) && is_array($request->$batchcodeName)) ? $request->$batchcodeName[$itemKey] : '';
                        $mrData->mr_status = 1;
                        $mrData->material_stage = 1;
                    
                       $mrData->save();
                    
                        $id = $mrData->id;

                        $production_qty = $request->$production_floor_stock[$itemKey];
                        $total_qty_issued = $production_qty + $request->$requiredQtyName[$itemKey];
                         // Insert into stock
                        if($total_qty_issued >= $request->recipe_qty[$key]) {

                            if($production_qty > 0) {

                                if($production_qty <= $request->recipe_qty[$key]) {
                                    
                                    $stock = [
                                        'main_id' => $mr->id,
                                        'master_id' => $id,
                                        'issuence_for_production_id' => $mr->production_id,
                                        'voucher_date' => $request->$issuanceDateName[$itemKey],
                                        'voucher_type' => 9,
                                        'voucher_no' => $mr->mr_no,
                                        'sub_item_id' => $itemValue,
                                        'qty' => $production_qty,
                                        'rate' => $avg_amount,
                                        'amount' => $production_qty * $avg_amount,
                                        'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName)) ? $request->$batchcodeName[$itemKey] : '',
                                        'status' => 1,
                                        'warehouse_id' => 2,
                                        'username' => Auth::user()->username,
                                        'created_date' => date('Y-m-d'),
                                        'opening' => 0,
                                    ];
                                   DB::connection('mysql2')->table('stock')->insert($stock);

                                    $stock2 = [];
                                    if ($total_qty_issued == $request->recipe_qty[$key]) {
                                        $stock2 = [
                                            'qty' => $total_qty_issued - $production_qty,
                                            'amount' => ($total_qty_issued - $production_qty) * $avg_amount,
                                        ];
                                    } else if ($request->$requiredQtyName[$itemKey] > $request->recipe_qty[$key]) {
                                        $stock2 = [
                                            'qty' => $request->recipe_qty[$key],
                                            'amount' => $request->recipe_qty[$key] * $avg_amount,
                                        ];
                                    } else if ($total_qty_issued > $request->recipe_qty[$key]) {
                                        $stock2 = [
                                            'qty' => $request->recipe_qty[$key] - $production_qty,
                                            'amount' => ($request->recipe_qty[$key] - $production_qty) * $avg_amount,
                                        ];
                                    }
                                    
                                    $stock2 = array_merge($stock2, [
                                        'main_id' => $mr->id,
                                        'master_id' => $id,
                                        'issuence_for_production_id' => $mr->production_id,
                                        'voucher_date' => $request->$issuanceDateName[$itemKey],
                                        'voucher_type' => 9,
                                        'voucher_no' => $mr->mr_no,
                                        'sub_item_id' => $itemValue,
                                        'rate' => $avg_amount,
                                        'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName)) ? $request->$batchcodeName[$itemKey] : '',
                                        'status' => 1,
                                        'warehouse_id' => 1,
                                        'username' => Auth::user()->username,
                                        'created_date' => date('Y-m-d'),
                                        'opening' => 0,
                                    ]);
                                    
                                    DB::connection('mysql2')->table('stock')->insert($stock2);

                                } elseif($production_qty > $request->recipe_qty[$key]) {
                                   
                                    $stock = [
                                        'main_id' => $mr->id,
                                        'master_id' => $id,
                                        'issuence_for_production_id' => $mr->production_id,
                                        'voucher_date' => $request->$issuanceDateName[$itemKey],
                                        'voucher_type' => 9,
                                        'voucher_no' => $mr->mr_no,
                                        'sub_item_id' => $itemValue,
                                        'qty' => $request->recipe_qty[$key],
                                        'rate' => $avg_amount,
                                        'amount' => $request->recipe_qty[$key] * $avg_amount,
                                        'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName)) ? $request->$batchcodeName[$itemKey] : '',
                                        'status' => 1,
                                        'warehouse_id' => 2,
                                        'username' => Auth::user()->username,
                                        'created_date' => date('Y-m-d'),
                                        'opening' => 0,
                                    ];
                                   DB::connection('mysql2')->table('stock')->insert($stock);
                                }
                            } elseif($production_qty == 0) {
                                $stock = [
                                    'main_id' => $mr->id,
                                    'master_id' => $id,
                                    'issuence_for_production_id' => $mr->production_id,
                                    'voucher_date' => $request->$issuanceDateName[$itemKey],
                                    'voucher_type' => 9,
                                    'voucher_no' => $mr->mr_no,
                                    'sub_item_id' => $itemValue,
                                    'qty' => $request->recipe_qty[$key],
                                    'rate' => $avg_amount,
                                    'amount' => $request->recipe_qty[$key] * $avg_amount,
                                    'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName)) ? $request->$batchcodeName[$itemKey] : '',
                                    'status' => 1,
                                    'warehouse_id' => $request->$warehouseIdName[$itemKey],
                                    'username' => Auth::user()->username,
                                    'created_date' => date('Y-m-d'),
                                    'opening' => 0,
                                ];
                                DB::connection('mysql2')->table('stock')->insert($stock);

                            }

                            if ($total_qty_issued > $request->recipe_qty[$key] && $production_qty < $request->recipe_qty[$key]) {
                               
                                $excess_qty = $total_qty_issued - $request->recipe_qty[$key];
                                $excess_stock = [
                                    'main_id' => $mr->id,
                                    'master_id' => $id,
                                    'issuence_for_production_id' => $mr->production_id,
                                    'voucher_date' => $request->$issuanceDateName[$itemKey],
                                    'voucher_type' => 3,
                                    'voucher_no' => $voucher_no,
                                    'sub_item_id' => $itemValue,
                                    'qty' => $excess_qty,
                                    'rate' => $avg_amount,
                                    'amount' => $excess_qty * $avg_amount,
                                    'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName)) ? $request->$batchcodeName[$itemKey] : '',
                                    'status' => 1,
                                    'warehouse_id' => 2, //production floor
                                    'warehouse_id_from' => $request->$warehouseIdName[$itemKey],
                                    'warehouse_id_to' => 2, //production floor
                                    'transfer' => 1,
                                    'username' => Auth::user()->username,
                                    'created_date' => date('Y-m-d'),
                                    'opening' => 0,
                                ];
                               DB::connection('mysql2')->table('stock')->insert($excess_stock);
                        
                                // Insert into stock_transfer only once
                                if ($stock_transfer_id === null) {
                                    $stock_transfer = [
                                        'tr_no' => $voucher_no,
                                        'tr_date' => date('Y-m-d'),
                                        'description' => 'Excess Raw material transferred to production floor',
                                        'status' => 1,
                                        'tr_status' => 1,
                                        'username' => Auth::user()->username,
                                        'date' => date('Y-m-d'),
                                    ];
                                   $stock_transfer_id = DB::connection('mysql2')->table('stock_transfer')->insertGetId($stock_transfer);
                                }
                        
                                // Insert into stock_transfer_data for each item
                                $stock_transfer_data = [
                                    'master_id' => $stock_transfer_id,
                                    'tr_no' => $voucher_no,
                                    'item_id' => $itemValue,
                                    'warehouse_from' => $request->$warehouseIdName[$itemKey],
                                    'warehouse_to' => 2,
                                    'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName)) ? $request->$batchcodeName[$itemKey] : '',
                                    'qty' => $excess_qty,
                                    'rate' => $avg_amount,
                                    'amount' => $excess_qty * $avg_amount,
                                    'status' => 1,
                                ];
                               DB::connection('mysql2')->table('stock_transfer_data')->insert($stock_transfer_data);
                            }
                        } 

                        // $description = '';
                        // $itemName = CommonHelper::get_item_name($itemValue);
                        // $itemAccountDetail = CommonHelper::get_sub_category_acc_id_and_acc_code_by_item_id($itemValue);

                        // if ($request->has($batchcodeName) && is_array($request->$batchcodeName)) {
                        //     $description = "this item ($itemName)($itemValue) is belong to batch code ({$request->{$batchcodeName}[$itemKey]}) and these transactions are belong to issues against this mr ($mr->mr_no) AND qty is {$request->{$requiredQtyName}[$itemKey]} and average amount is {$avg_amount}";
                        // } else {
                        //     $description = "there is no batch code of this item ($itemName)($itemValue) and these transactions belong to issues against this mr ($mr->mr_no) AND qty is {$request->{$requiredQtyName}[$itemKey]} and average amount is {$avg_amount}";
                        // }

                        // $transactionData = [
                        //     'acc_id' => $itemAccountDetail->acc_id,
                        //     'acc_code' => 0,
                        //     'particulars' => $description,
                        //     'amount' => $amount,
                        //     'opening_bal' => '0',
                        //     'voucher_no' => $mr->mr_no,
                        //     'voucher_type' => 9, // issue type
                        //     'v_date' => $request->$issuanceDateName[$itemKey],
                        //     'date' => date("Y-m-d"),
                        //     'time' => date("H:i:s"),
                        //     'master_id' => $id,
                        //     'username' => Auth::user()->name
                        // ];

                        // $transactions = $transactionData;
                        // $transactions['debit_credit'] = 0;
                        // DB::Connection('mysql2')->table('transactions')->insert($transactions);

                        // $transactions2 = $transactionData;

                        // $finish_good_id = CommonHelper::get_main_category_name_by_item_id($mr->finish_good_id);
                        // $main_category = $finish_good_id->main_ic;

                        // if(empty($mr->sale_order_id))
                        // {
                        //     if($main_category == 'SEMI FINISHED')
                        //     {
                        //         $transactions2['acc_code'] = '1-2-1-2-12-3';
                        //         $transactions2['acc_id'] = 453;

                        //     }
                        //     else
                        //     {
                        //         $transactions2['acc_code'] = '1-2-1-2-12-2';
                        //         $transactions2['acc_id'] = 452;
                        //     }
                            
                        // }
                        // else{
                        //         $transactions2['acc_code'] = '1-2-1-2-12-1';
                        //         $transactions2['acc_id'] = 449;
                        
                        // }
                        // $transactions2['debit_credit'] = 1;
                        // DB::Connection('mysql2')->table('transactions')->insert($transactions2);
                        
                    }
                }
            }         

            foreach ($request->recipe_qty as $key1 => $qty_receipe) {
                $recipt_total += $qty_receipe;
            }

            $get_mr_data = MaterialRequisitionData::where('mr_id', $request->mr_id)
                ->select(DB::raw('sum(issuance_qty) as issuance_qty'))
                ->groupBy('mr_id')
                ->first();

            $issue_qty = $get_mr_data->issuance_qty ?? '0';
            $request_qty = $recipt_total;

            if ($request_qty <= $issue_qty) {
                $mm_r = MaterialRequisition::find($request->mr_id);
                $mm_r->mr_status = 2;
                $mm_r->save();
            }

            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();

            echo $e->getMessage();
            dd($e->getline());
        }
        Session::flash('dataInsert', 'Purchase Request Successfully Saved.');


        return redirect()->route('listMaterialRequisition');
    }

    public function getStock(Request $request)
    {
        return CommonHelper::in_stock_edit($request->item_id, $request->warehouse_id, 0);
    }

    public function getProductionFloorStock(Request $request)
    {
        $item = $request->item_id;
        $warehouse = $request->warehouse_id;
        $in = DB::Connection('mysql2')->table('stock')->where('status', 1)
            ->whereIn('voucher_type', [1, 4, 6, 3, 10, 11])
            ->where('sub_item_id', $item)
            ->where('warehouse_id', $warehouse);

        // if ($batch_code !== null) {
        //     $in->where('batch_code', $batch_code);
        // }

        $inResult = $in->select(
            DB::raw('COALESCE(SUM(qty), 0) AS qty'),
            DB::raw('COALESCE(SUM(amount), 0) AS amount')
        )->first();

        $out = DB::connection('mysql2')->table('stock')
            ->where('status', 1)
            ->whereIn('voucher_type', [2, 5, 9, 8])
            ->where('sub_item_id', $item)
            ->where('warehouse_id', $warehouse);

        // if ($batch_code !== null) {
        //     $out->where('batch_code', $batch_code);
        // }

        $outResult = $out->select(
            DB::raw('COALESCE(SUM(qty), 0) AS qty'),
            DB::raw('COALESCE(SUM(amount), 0) AS amount')
        )->first();

        $qty = $inResult->qty - $outResult->qty;
        $amount = $inResult->amount;
        $rate = 0;

        if ($qty > 0) {
            $rate = number_format($amount / $inResult->qty, 2, '.', '');
            return $qty;
        } else {
            $qty = 0;
            $amount = 0;
            return $qty;
        }

    }
}
