<?php

namespace App\Http\Controllers;


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
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
    }



    public function listProductionOrder(Request $request)
    {
        if ($request->ajax()) {
            $productions = ProductionPlane::join('production_plane_data as ppd', 'ppd.master_id', 'production_plane.id')
                ->join('material_requisitions as mr', 'production_plane.id', 'mr.production_id')
                ->where('production_plane.status', 1)
                ->where('ppd.status', 1)
                ->where('mr.status', 1)
                ->select('production_plane.id as pp_id', 'ppd.ppc_status', 'ppd.id as p_p_d_id', 'production_plane.sale_order_no as sale_order_no', 'production_plane.*', 'ppd.*', 'mr.approval_status')
                ->orderBy('ppd.id', 'desc')->get();

            return view('selling.production.listProductionOrderAjax', compact('productions'));
        }
        return view('selling.production.listProductionOrder');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProductionOrder()
    {
        $batch_code = ProductionHelper::getNewBatchCode();
        return view('selling.production.createProductionOrder',compact('batch_code'));
    }
    
    public function createGeneralProductionPlan()
    {
        return view('selling.production.createGeneralProductionPlan');
    }
    
    public function viewProductionOrder(Request $request)
    {
        $ProductionPlane = ProductionPlane::where('id', $request->id )->first();
        return view('selling.production.viewProductionOrder', compact('ProductionPlane'));
    }
    
    public function viewProductionOrderPrint(Request $request)
    {
        $ProductionPlane = ProductionPlane::where('id', $request->id )->first();

        $data = DB::connection('mysql2')->table('production_plane as pp')
                ->join('production_plane_data as ppd', 'pp.id', '=', 'ppd.master_id')
                ->join('subitem as s', 's.id', '=', 'ppd.finish_goods_id')
                //->join('sales_order as so', 'pp.sales_order_id', '=', 'so.id')
                // ->join('customers as c', 'so.buyers_id', '=', 'c.id')
                ->select(
                    'pp.pr_no',
                    'pp.order_no',
                    'ppd.planned_qty',
                    'ppd.start_date',
                    'ppd.delivery_date',
                    'ppd.color',
                    's.sub_ic',
                    's.item_code',
                    'pp.special_instructions',
                )
                ->where('pp.status', 1)
                ->where('ppd.status', 1)
                ->where('s.status', 1)
                // ->where('so.status', 1)
                // ->where('c.status', 1)
                ->where('pp.id', $request->id)
                ->first();

        $rawMaterialList = DB::connection('mysql2')->table('production_plane as pp')
                        ->join('production_plane_recipe as ppr', 'pp.id', '=', 'ppr.main_id')
                        ->select('ppr.bom_data_id', 'ppr.item_id','ppr.request_qty')
                        ->where('pp.status', 1)
                        ->where('ppr.status', 1)
                        ->where('pp.id', $request->id)
                        ->get();

        return view('selling.production.viewProductionOrderPrint', compact('data','rawMaterialList'));
    }

    public function editProductionOrder(Request $request)
    {
        $ProductionPlane = ProductionPlane::where('id', $request->id)->first();
        return view('selling.production.editProductionOrder', compact('ProductionPlane'));
    }

    public function getWorkOrderData(Request $request)
    {
        $production =  DB::connection('mysql2')->table('production_request')->where('id',$request->id)
            //->join('customers','customers.id','sales_order.buyers_id')
            //->select('sales_order.id as so_id','sales_order.so_no','customers.id as customer_id','customers.name as name')
            ->first();

        $production_data =  DB::connection('mysql2')->table('production_request_data')
            ->where('master_id',$request->id)
            ->where('status',1)->where('production_status',1)
            ->get();
        return view('selling.production.getWorkOrderData',compact('production','production_data'));
    }
    
    public function getRecipeOfItem(Request $request)
    {
        $item_id = $request->id;
        return view('selling.production.getRecipeOfItem',compact('item_id'));
    }
    
    public function getWorkOrderDataForView(Request $request)
    {

      
        $prodtions = ProductionPlane::where('id', $request->ppid )->first();
        $prodtion_datas =  ProductionPlaneData::where('master_id',$request->ppid)
        ->where('status',1)->get();

        $customerDetails =   Sales_Order::where('sales_order.id',$request->id)
        ->join('customers','customers.id','sales_order.buyers_id')
        ->select('sales_order.id as so_id','sales_order.so_no','customers.id as customer_id','customers.name as name')
        ->first();

        // $prodtion_datas =  Sales_Order_Data::where('master_id',$request->id)
        // ->where('status',1)->where('production_status','!=',0)
        // ->get();
        // echo "<pre>";
        // print_r($customerDetails);
        // exit();
        return view('selling.production.getWorkOrderDataForView',compact('prodtions','prodtion_datas','customerDetails'));
    }
    
    public function getWorkOrderDataForEdit(Request $request)
    {
        $prodtions = ProductionPlane::where('id', $request->ppid )->first();
        $prodtion_datas =  ProductionPlaneData::where('master_id',$request->ppid)
        ->where('status',1)->get();

        // $customerDetails =   Sales_Order::where('sales_order.id',$request->id)
        // ->join('customers','customers.id','sales_order.buyers_id')
        // ->select('sales_order.id as so_id','sales_order.so_no','customers.id as customer_id','customers.name as name')
        // ->first();

        // $prodtion_datas =  Sales_Order_Data::where('master_id',$request->id)
        // ->where('status',1)->where('production_status','!=',0)
        // ->get();
        // echo "<pre>";
        // print_r($customerDetails);
        // exit();
        return view('selling.production.getWorkOrderDataForEdit',compact('prodtions','prodtion_datas'));
    }

    public function storeProductionOrder(Request $request)
    {

        // echo "<pre>";
        // print_r($request->all());
        // exit();

        //type  '1=normal production, 2=general production'

        // dd($request->all());
        // DB::Connection('mysql2')->beginTransaction();
        // try {
            
        //     DB::connection('mysql2')->transaction(function () use ($request) {
        //         try {
        //             foreach($request->finish_good as $key=> $value)  
        //            {
         
         
        //              $validator = Validator::make([
        //                  'start_date' => $request->start_date[$key],
        //                  'delivery_date' => $request->delivery_date[$key],
        //              ], [
        //                  'start_date' => 'required',
        //                  'delivery_date' => 'required',
        //              ]);
         
        //              if ($validator->fails()) {
        //                  return redirect()->back()->withErrors($validator)->withInput();
        //              }
         
        //              $production = new ProductionPlane;
        //              $production->order_no = CommonHelper::generateUniquePosNoWithStatusOne('production_plane','order_no','PPC'); 
        //              $production->sales_order_id = $request->sales_order_id; 
        //              $production->order_date = date('Y-m-d'); 
        //              $production->ppc_status = 0; 
        //              $production->status = 1; 
        //              $production->type = 1; // '1=normal production, 2=general production'
        //              $production->customer = $request->customer_id;
         
        //              $production->wall_thickness_1 = $request->wall_thickness_1; 
        //              $production->wall_thickness_2 = $request->wall_thickness_2; 
        //              $production->wall_thickness_3 = $request->wall_thickness_3; 
        //              $production->wall_thickness_4 = $request->wall_thickness_4; 
        //              $production->pipe_outer = $request->pipe_outer; 
        //              $production->printing_on_pipe = $request->printing_on_pipe; 
        //              $production->special_instructions = $request->special_instructions; 
        //              $production->length = $request->length; 
                     
        //              $production->sale_order_no = $request->order_no; 
        //              $production->usernmae = Auth::user()->name; 
        //              $production->date = date('Y-m-d') ;
        //              $production->save(); 
         
         
        //              $production_data =  new ProductionPlaneData;
        //              $production_data->order_no = $production->order_no ; 
        //              $production_data->master_id = $production->id;
        //              $production_data->start_date = $request->start_date[$key]; 
        //              $production_data->delivery_date = $request->delivery_date[$key]; 
        //              $production_data->planned_qty = $request->order_qty[$key]; 
        //              $production_data->finish_goods_id = $request->finish_good[$key]; 
        //              $production_data->receipt_id = $request->receipt_id[$key]; 
        //              $production_data->ppc_status = 0; 
        //              $production_data->type = 1; 
        //              $production_data->save();
         
        //              $mr_no = CommonHelper::generateUniquePosNoWithStatusOne('material_requisitions','mr_no','MR');
        //              $production =   ProductionPlane::find($production->id);
                   
        //              $mr = new MaterialRequisition;
        //              $mr->mr_no = $mr_no;
        //              $mr->mr_date = date('Y-m-d');
        //              $mr->production_id = $production->id;
        //              $mr->production_plan_data_id = $production_data->id;
        //              $mr->sale_order_id = $production->sales_order_id;
        //              $mr->finish_good_id = $request->finish_good[$key];
        //              $mr->finish_good_qty = $request->order_qty[$key];
        //              $mr->receipt_id =$request->receipt_id[$key];
        //              $mr->mr_status = 1;
        //              $mr->save();
         
        //              foreach($request->category as $key2=> $recipe)
        //              {
        //                  $recipe = new ProductionPlaneRecipe;
        //                  $recipe->main_id = $production_data->id;
        //                  $recipe->master_id = $mr->id;
        //                  $recipe->bom_data_id =$request->category[$key2]; // raw Category id
        //                  $recipe->item_id =$request->item_id[$key2]; // item id of category if it selected .
        //                  $recipe->type =1;      // Type 
        //                  $recipe->issue_qty =0; // Issue Qty 
        //                  $recipe->receipe_id=$request->receipt_id[$key];
        //                  $recipe->request_qty= $request->requested_qty[$key2];
        //                  $recipe->username=Auth::user()->name;
        //                  $recipe->date=date('Y-m-d');
        //                  $recipe->status=1;
        //                  $recipe->save();
        //              }
         
        //              $sale_order_data =  Sales_Order_Data::find($request->sale_data_id[$key]); 
        //              $sale_order_data->production_plane_status =  2;
        //              $sale_order_data->production_status =  2;
        //              $sale_order_data->save();
        //            }
        //         } catch (Exception $e) {
        //             throw $e;
        //         }
        //     });
       

        //     //  DB::Connection('mysql2')->commit();
        //      return redirect()->route('listProductionOrder')->with('dataInsert','Sale Order Inserted');
        // }
        // catch ( Exception $e )
        // {
        //     // DB::Connection('mysql2')->rollBack();
        //     return redirect()->route('listProductionOrder')->with('error', $e->getMessage());
        // }
        try {
            DB::connection('mysql2')->transaction(function () use ($request) {

                foreach ($request->production_bom_id as $key => $value) {
                    $dunkey = "item_id{$value}";
                    // Validate dates
                    $validator = Validator::make([
                        'start_date' => $request->start_date[$key],
                        'delivery_date' => $request->delivery_date[$key],
                    ], [
                        'start_date' => 'required',
                        'delivery_date' => 'required',
                    ]);
        
                    if ($validator->fails()) {
                        throw new \Exception($validator->errors()->first());
                    }
                    foreach ($request->$dunkey as $key2 => $recipeCategory) {
                        $validator = Validator::make([
                            'item_id' => $request->$dunkey[$key2],
                        ], [
                            'item_id' => 'required',
                        ]);
            
                        if ($validator->fails()) {
                            throw new \Exception($validator->errors()->first());
                        }
                    }
        
                    // Create ProductionPlane
                    $production = new ProductionPlane;
                    $production->order_no = CommonHelper::generateUniquePosNoWithStatusOne('production_plane', 'order_no', 'PPC'); 
                    $production->pr_id = $request->pr_id; 
                    $production->pr_no = $request->pr_no;
                    $production->sales_order_id = 0; 
                    $production->order_date = date('Y-m-d'); 
                    $production->ppc_status = 0;
                    $production->batch_code = ProductionHelper::getNewBatchCode();
                    $production->status = 1; 
                    $production->type = 2; // '1=normal production, 2=general production'
                    $production->customer = 0;
                    $production->wall_thickness_1 = 0; 
                    $production->wall_thickness_2 = 0; 
                    $production->wall_thickness_3 = 0; 
                    $production->wall_thickness_4 = 0; 
                    $production->pipe_outer = 0; 
                    $production->printing_on_pipe = ''; 
                    $production->special_instructions = $request->special_instructions; 
                    $production->length = 0; 
                    $production->sale_order_no = 0; 
                    $production->usernmae = Auth::user()->name; 
                    $production->date = date('Y-m-d');
                    $production->save();
        
                    // Create ProductionPlaneData
                    $production_data = new ProductionPlaneData;
                    $production_data->order_no = $production->order_no;
                    $production_data->master_id = $production->id;
                    $production_data->start_date = $request->start_date[$key];
                    $production_data->delivery_date = $request->delivery_date[$key];
                    $production_data->planned_qty = $request->order_qty[$key];
                    $production_data->finish_goods_id = $request->finish_good[$key];
                    $production_data->production_request_data_id = $request->production_request_data_id[$key];
                    $production_data->color = $request->color[$key];
                    $production_data->receipt_id = $request->receipt_id[$key];
                    $production_data->ppc_status = 0;
                    $production_data->type = 1;
                    $production_data->save();
        
                    // Create MaterialRequisition
                    $mr_no = CommonHelper::generateUniquePosNoWithStatusOne('material_requisitions', 'mr_no', 'MR');
                    $mr = new MaterialRequisition;
                    $mr->mr_no = $mr_no;
                    $mr->mr_date = date('Y-m-d');
                    $mr->production_id = $production->id;
                    $mr->production_plan_data_id = $production_data->id;
                    $mr->sale_order_id = $production->sales_order_id;
                    $mr->finish_good_id = $request->finish_good[$key];
                    $mr->finish_good_qty = $request->order_qty[$key];
                    $mr->receipt_id = $request->receipt_id[$key];
                    $mr->mr_status = 1;
                    $mr->save();
        
                    // Create ProductionPlaneRecipe entries

                    if (isset($request->$dunkey) && is_array($request->$dunkey)) {
                        foreach ($request->$dunkey as $key2 => $recipeCategory) {
                            $recipe = new ProductionPlaneRecipe;
                            $recipe->main_id = $production_data->id;
                            $recipe->master_id = $mr->id;
                            $recipe->bom_data_id = 7; // raw Category id
                            $recipe->item_id = $recipeCategory; // item id of category if it selected
                            $recipe->type = 2;      // Type 
                            $recipe->issue_qty = 0; // Issue Qty 
                            $recipe->receipe_id = $request->receipt_id[$key];
                            $recipe->request_qty = $request->requested_qty[$key2];
                            $recipe->username = Auth::user()->name;
                            $recipe->date = date('Y-m-d');
                            $recipe->status = 1;
                            $recipe->save();
                        }
                    }
        
                    // Update Production request
                    DB::connection('mysql2')->table('production_request_data')->where('id',$request->production_request_data_id[$key])->update(['production_status' => 2]);
                }
            });
        
            return redirect()->route('listProductionOrder')->with('dataInsert', 'Successfully Saved');
        } catch (Exception $e) {
            return redirect()->route('createProductionOrder')->with('error', $e->getMessage());
        }
    }

    public function updateProductionOrder(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        
        try {
            $production = ProductionPlane::find($request->ppid);

            if ($production) {
                $production->pr_id = $request->pr_id; 
                $production->pr_no = $request->pr_no;
                $production->order_date = date('Y-m-d');
                $production->order_no = $request->production_no;
                $production->sales_order_id = 0; 
                $production->ppc_status = 0;
                $production->wall_thickness_1 = 0; 
                $production->wall_thickness_2 = 0; 
                $production->wall_thickness_3 = 0; 
                $production->wall_thickness_4 = 0; 
                $production->pipe_outer = 0; 
                $production->printing_on_pipe = ''; 
                $production->special_instructions = $request->special_instructions; 
                $production->length = 0; 
                $production->sale_order_no = 0; 
                $production->usernmae = Auth::user()->name;
                $production->status = 1;
                $production->date = date('Y-m-d');
                $production->save();

                foreach ($request->work_data_id as $work_data_id_key => $work_data_id_value) {
                    echo $work_data_id_value;
                    MaterialRequisition::where('production_id',$production->id)->where('production_plan_data_id', $work_data_id_value)->update([
                        'mr_status' => 0,
                        'status' => 0
                        ]);
                }

                foreach($request->finish_good as $key=> $value)  
                {

                    ProductionPlaneData::where('id',$request->work_data_id[$key])->update([
                        'status' => 0
                    ]);  
                    Sales_Order_Data::where('master_id',$request->work_data_id[$key])->where('item_id',$request->finish_good[$key])->update([
                        'production_plane_status' => 0,
                        'production_status' => 0
                    ]);
                

                    $production_data =  new ProductionPlaneData;
                    $production_data->order_no = $request->production_no ; 
                    $production_data->master_id = $production->id;
                    $production_data->start_date = $request->start_date[$key]; 
                    $production_data->delivery_date = $request->delivery_date[$key]; 
                    $production_data->planned_qty = $request->order_qty[$key]; 
                    $production_data->finish_goods_id = $request->finish_good[$key]; 
                    $production_data->receipt_id = $request->receipt_id[$key]; 
                    $production_data->ppc_status = 0; 
                    $production_data->type = 1; 
                    $production_data->save();
        
                    $mr_no = CommonHelper::generateUniquePosNoWithStatusOne('material_requisitions','mr_no','MR');
                    $production =   ProductionPlane::find($production->id);
                
                
                    $mr = new MaterialRequisition;
                    $mr->mr_no = $mr_no;
                    $mr->mr_date = date('Y-m-d');
                    $mr->production_id = $production->id;
                    $mr->production_plan_data_id = $production_data->id;
                    $mr->sale_order_id = $production->sales_order_id;
                    $mr->finish_good_id = $request->finish_good[$key];
                    $mr->finish_good_qty = $request->order_qty[$key];
                    $mr->receipt_id =$request->receipt_id[$key];
                    $mr->mr_status = 1;
                    $mr->save();
                    
                    foreach($request->item_id as $key2=> $recipe)
                    {
                        ProductionPlaneRecipe::where('main_id',$request->work_data_id[$key])->where('bom_data_id', $request->category[$key2])->update([
                            'status' => 0
                        ]);
                        $recipe = new ProductionPlaneRecipe;
                        $recipe->main_id = $production_data->id;
                        $recipe->master_id = $mr->id;
                        $recipe->item_id = $recipe;
                        $recipe->bom_data_id = 7; // raw Category id
                        $recipe->type =1;      // Type 
                        $recipe->issue_qty =0; // Issue Qty 
                        $recipe->receipe_id=$request->receipt_id[$key];
                        $recipe->request_qty= $request->requested_qty[$key2];
                        $recipe->username=Auth::user()->name;
                        $recipe->date=date('Y-m-d');
                        $recipe->status=1;
                        $recipe->save();
                    }
        
                    // $sale_order_data =  Sales_Order_Data::find($request->sale_data_id[$key]); 
                    // $sale_order_data->production_plane_status =  2;
                    // $sale_order_data->production_status =  2;
                    // $sale_order_data->save();
                }
        
                DB::Connection('mysql2')->commit();
                return redirect()->route('listProductionOrder')->with('dataInsert','record updated');
            }
        else {
            return redirect()->route('listProductionOrder')->with('error', 'record not found');
        }
        DB::Connection('mysql2')->commit();
        // return redirect()->route('listProductionOrder')->with('dataInsert','Sale Order update');
        }
        catch ( Exception $e ) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('listProductionOrder')->with('error', $e->getMessage());
        }
    }

    public function storeGeneralProductionOrder(Request $request)
    {

        ini_set('max_execution_time', 7000);

        // echo "<pre>";
        // print_r($request->all());
        // exit();
        
        DB::Connection('mysql2')->beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'start_date' => 'required',
                'delivery_date' => 'required',
                'order_qty' => 'required|numeric|min:1', // Order quantity is required and must be numeric with minimum value of 1
                'finish_good' => 'required', // Order quantity is required and must be numeric with minimum value of 1
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
                //type  '1=normal production, 2=general production'

        
                $production = new ProductionPlane;
                $production->order_no = CommonHelper::generateUniquePosNoWithStatusOne('production_plane','order_no','PPC'); 
                $production->sales_order_id = $request->sales_order_id; 
                $production->order_date = date('Y-m-d'); 
                $production->ppc_status = 0;
                $production->status = 1; 
                $production->type = 2; // '1=normal production, 2=general production'
                $production->customer = $request->customer_id;

                $production->wall_thickness_1 = $request->wall_thickness_1; 
                $production->wall_thickness_2 = $request->wall_thickness_2; 
                $production->wall_thickness_3 = $request->wall_thickness_3; 
                $production->wall_thickness_4 = $request->wall_thickness_4; 
                $production->pipe_outer = $request->pipe_outer; 
                $production->printing_on_pipe = $request->printing_on_pipe; 
                $production->special_instructions = $request->special_instructions; 
                $production->length = $request->length; 
                
                $production->sale_order_no = $request->order_no; 
                $production->usernmae = Auth::user()->name; 
                $production->date = date('Y-m-d') ;
                $production->save(); 


                $production_data =  new ProductionPlaneData;
                $production_data->order_no = $production->order_no ; 
                $production_data->master_id = $production->id;
                $production_data->start_date = $request->start_date; 
                $production_data->delivery_date = $request->delivery_date; 
                $production_data->planned_qty = $request->order_qty; 
                $production_data->finish_goods_id = $request->finish_good; 
                $production_data->receipt_id = $request->receipt_id; 
                $production_data->ppc_status = 0; 
                $production_data->type = 2;
                $production_data->qty_type =  $request->qty_type;
                $production_data->category_id =  $request->Category_main;
                $production_data->save();

                $mr_no = CommonHelper::generateUniquePosNoWithStatusOne('material_requisitions','mr_no','MR');
                
                $production_id = $production->id;
                $production_plan_data_id = $production_data->id;


                $mr = new MaterialRequisition;
                $mr->mr_no = $mr_no;
                $mr->mr_date = date('Y-m-d');
                $mr->production_id = $production_id;
                $mr->production_plan_data_id = $production_plan_data_id;
                $mr->finish_good_id = $request->finish_good;
                $mr->finish_good_qty = $request->order_qty;
                $mr->receipt_id =$request->receipt_id;
                $mr->mr_status = 1;
                $mr->save();

                foreach($request->category as $key2=> $recipe)
                {
                    $recipe = new ProductionPlaneRecipe;
                    $recipe->main_id = $production_plan_data_id;
                    $recipe->master_id = $mr->id;
                    $recipe->bom_data_id =$request->category[$key2]; // raw Category id
                    $recipe->item_id =$request->item_id[$key2]; // item id of category if it selected .
                    $recipe->type = 2;      // Type 
                    $recipe->issue_qty =0; // Issue Qty 
                    $recipe->receipe_id=$request->receipt_id;
                    $recipe->request_qty= $request->requested_qty[$key2];
                    $recipe->username=Auth::user()->name;
                    $recipe->date=date('Y-m-d');
                    $recipe->status=1;
                    $recipe->save();
                }

                DB::Connection('mysql2')->commit();
                return redirect()->route('listProductionOrder')->with('dataInsert','Successfully Saved');
        }
        catch ( Exception $e )
        {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('listProductionOrder')->with('error', $e->getMessage());
        }
    }

    public function deleteProduction(Request $request)
    {
        $production_id = $request->production_id;

        $production = ProductionPlane::find($request->production_id);
        $production_status = $production->status ?? '';
        $MaterialRequisition = MaterialRequisition::where('production_id',$production->id)->first();
        $approval_status = $MaterialRequisition->approval_status ?? '' ;
        if($production && $production_status == 1 && $approval_status == 1)
        {

            ProductionPlaneData::where('master_id',$production->id)->update([
                'status' => 0
            ]);  
            
            MaterialRequisition::where('production_id',$production->id)->update([
                'mr_status' => 0,
                'status' => 0
            ]);
                
            ProductionPlaneRecipe::where('master_id',$MaterialRequisition->id)->update([
                'status' => 0
            ]);
                
            Sales_Order_Data::where('master_id',$production->sales_order_id)->update([
                'production_plane_status' => 0,
                'production_status' => 0
            ]);


            $production->status = 0;
            $production->save();

            return 1;

        }
        
        return 2 ;

    }
    public function deleteGeneralProduction(Request $request)
    {
        $production_id = $request->production_id;

        $production = ProductionPlane::find($request->production_id);
        $production_status = $production->status ?? '';
        $MaterialRequisition = MaterialRequisition::where('production_id',$production->id)->first();
        $approval_status = $MaterialRequisition->approval_status ?? '' ;
        if($production && $production_status == 1 && $approval_status == 1 && $production->type == 2)
        {

            ProductionPlaneData::where('master_id',$production->id)->update([
                'status' => 0
            ]);  

            MaterialRequisition::where('production_id',$production->id)->update([
                'mr_status' => 0,
                'status' => 0
            ]);
                
            ProductionPlaneRecipe::where('master_id',$MaterialRequisition->id)->update([
                'status' => 0
            ]);

            $production->status = 0;
            $production->save();

            return 1;

        }
        
        return 2 ;

    }



    public function createDaiForm()
    {
        return view('Production.createDaiForm');
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

    public function editDaiForm()
    {
        return view('Production.editDaiForm');
    }

    public function create_labour_category()
    {
        return view('Production.create_labour_category');
    }


    public function daiList()
    {
        $Dai = DB::Connection('mysql2')->table('production_dai')->where('status', 1)->get();
        return view('Production.daiList', compact('Dai'));
    }

    public function labour_category_list()
    {
        $LabCat = DB::Connection('mysql2')->table('production_labour_category')->where('status', 1)->get();
        return view('Production.labour_category_list', compact('LabCat'));
    }


    public function create_routing()
    {

        $data = DB::Connection('mysql2')->table('production_work_order as a')
            ->join('subitem as b', 'a.finish_good_id', '=', 'b.id')
            ->select('a.finish_good_id', 'b.sub_ic')
            ->where('a.status', 1)
            ->groupBy('a.finish_good_id')
            ->get();

        return view('Production.create_routing', compact('data'));
    }

    public function edit_routing()
    {
        $data = DB::Connection('mysql2')->table('production_work_order as a')
            ->join('subitem as b', 'a.finish_good_id', '=', 'b.id')
            ->select('a.finish_good_id', 'b.sub_ic')
            ->where('a.status', 1)
            ->groupBy('a.finish_good_id')
            ->get();
        return view('Production.edit_routing', compact('data'));
    }


    public function routing_list()
    {
        return view('Production.routing_list');
    }

    public function createMoldForm()
    {
        return view('Production.createMoldForm');
    }

    public function editMoldForm()
    {
        return view('Production.editMoldForm');
    }

    public function moldList()
    {
        $Mold = DB::Connection('mysql2')->table('production_mold')->where('status', 1)->get();
        return view('Production.moldList', compact('Mold'));
    }

    public function createMachineForm()
    {
        return view('Production.createMachineForm');
    }

    public function editMachineForm()
    {
        return view('Production.editMachineForm');
    }


    public function insert_dai(Request $request)
    {
        $InsertData['dai_name'] = $request->input('DaiName');
        $InsertData['batch_code'] = $request->input('BatchCode');
        $InsertData['size'] = $request->input('Size');
        $InsertData['life'] = $request->input('life');
        $InsertData['status'] = 1;
        $InsertData['username'] = Auth::user()->name;
        $Count = DB::Connection('mysql2')->table('production_dai')->where('dai_name', $request->input('DaiName'))->where('status', 1)->count();
        if ($Count > 0) {
            echo 'duplicate';
        } else {
            $MainId = DB::Connection('mysql2')->table('production_dai')->InsertGetId($InsertData);
            ProductionHelper::production_activity($MainId, 1, 1);
            echo "yes";
        }
    }

    public function update_dai(Request $request)
    {
        $EditId = $request->input('edit_id');
        $UpdateData['dai_name'] = $request->input('DaiName');
        $UpdateData['batch_code'] = $request->input('BatchCode');
        $UpdateData['size'] = $request->input('Size');
        $UpdateData['life'] = $request->input('life');
        $UpdateData['status'] = 1;
        $UpdateData['username'] = Auth::user()->name;


        DB::Connection('mysql2')->table('production_dai')->where('id', '=', $EditId)->update($UpdateData);
        ProductionHelper::production_activity($EditId, 1, 2);
        echo "yes";
    }

    public function insert_mold(Request $request)
    {
        $InsertData['mold_name'] = $request->input('MoldName');
        $InsertData['batch_code'] = $request->input('BatchCode');
        $InsertData['size'] = $request->input('size');
        $InsertData['life'] = $request->input('life');
        $InsertData['status'] = 1;
        $InsertData['username'] = Auth::user()->name;
        $Count = DB::Connection('mysql2')->table('production_mold')->where('mold_name', $request->input('MoldName'))->where('status', 1)->count();
        if ($Count > 0) {
            echo 'duplicate';
        } else {
            $MainId = DB::Connection('mysql2')->table('production_mold')->InsertGetId($InsertData);
            ProductionHelper::production_activity($MainId, 3, 1);
            echo "yes";
        }
    }

    public function update_mold(Request $request)
    {
        $EditId = $request->input('edit_id');
        $UpdateData['mold_name'] = $request->input('MoldName');
        $UpdateData['batch_code'] = $request->input('BatchCode');
        $UpdateData['size'] = $request->input('size');
        $UpdateData['life'] = $request->input('life');
        $UpdateData['status'] = 1;
        $UpdateData['username'] = Auth::user()->name;

        DB::Connection('mysql2')->table('production_mold')->where('id', '=', $EditId)->update($UpdateData);
        ProductionHelper::production_activity($EditId, 3, 2);
        echo "yes";
    }


    public function getCheckingDuplicate(Request $request)
    {
        $MainId = $request->MainId;
        $BatchCode = $request->batch_code;

        $count = count($BatchCode);
        for ($i = 0; $i < $count; $i++) :
            $Count = DB::Connection('mysql2')->table('production_dai_detail')->where('batch_code', $request->batch_code[$i])->where('main_id', $MainId)->count();
            if ($Count > 0) {
                echo $request->batch_code[$i] . " Duplicate";
                break;
            }
        endfor;
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

        $InsertMaster['salvage_cost'] = $request->input('salvage_cost');;
        $InsertMaster['dep_cost'] = $request->input('dep_cost');;
        $InsertMaster['life'] = $request->input('life');;
        $InsertMaster['yearly_cost'] = $request->input('yearly_cost');;
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
            if ($data_id == 0) :
                DB::Connection('mysql2')->table('production_machine_data')->insert($InsertDetail);
            else :
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

    public function viewDieDetail()
    {
        return view('Production.viewDieDetail');
    }

    public function viewMoldDetail()
    {
        return view('Production.viewMoldDetail');
    }


    public function viewBomDetail()
    {
        return view('Production.viewBomDetail');
    }

    public function viewLabourWorkingDetail()
    {
        return view('Production.viewLabourWorkingDetail');
    }

    public function factory_over_head_cateogory_list()
    {
        return view('Production.factory_over_head_cateogory_list');
    }


    public function create_bill_of_material()
    {
        $finish_goods_data = DB::Connection('mysql2')->table('subitem as a')
            ->leftjoin('production_bom as b', 'a.id', '=', 'b.finish_goods')
            ->select('a.id', 'a.sub_ic', 'b.finish_goods', 'b.status as bom_status')
            ->where('a.status', 1)
            ->where('a.finish_good', 1)
            ->groupBy('a.id')
            ->get();

        return view('Production.create_bill_of_material', compact('finish_goods_data'));
    }

    public function edit_bill_of_material()
    {
        return view('Production.edit_bill_of_material');
    }

    public function bom_list(Request $request)
    {
        return view('Production.bom_list');
    }

    public function getCharges(Request $request)
    {
        $Charges = DB::Connection('mysql2')->table('production_labour_category')->where('status', 1)->whereIn('id', $request->vl)->sum('charges');
        echo $Charges;
    }


    public function insert_bom(Request $request)
    {

        //        echo "<pre>";
        //        print_r($request->input());
        //        die();

        $InsertMaster['finish_goods'] = $request->input('SubItemId');
        $InsertMaster['Description'] = $request->input('Description');
        $InsertMaster['status'] = 1;
        $InsertMaster['username'] = Auth::user()->name;
        $InsertMaster['date'] = date('Y-m-d');
        $MasterId = DB::Connection('mysql2')->table('production_bom')->InsertGetId($InsertMaster);


        $direct_material = $request->input('item_id');


        foreach ($direct_material as $key => $row2) {

            $data['master_id'] = $MasterId;
            $data['item_id'] = $request->input('item_id')[$key];
            $data['qty_mm'] = $request->input('qty_mm')[$key];
            $data['qty_ft'] = $request->input('qty_ft')[$key];
            $data['qty_20_length'] = $request->input('qty_20_length')[$key];
            $data['recover_sreacp'] = $request->input('recover_sreacp')[$key];
            $data['recover_chip'] = $request->input('recover_chip')[$key];
            $data['turning_scrap'] = $request->input('turning_scrap')[$key];
            $data['status'] = 1;
            $data['username'] = Auth::user()->name;
            DB::Connection('mysql2')->table('production_bom_data_direct_material')->insert($data);
        }

        $indirect_material = $request->input('in_direct_item_id');


        foreach ($indirect_material as $key => $row2) {
            if ($row2 != '') :
                $data2['main_id'] = $MasterId;
                $data2['item_id'] = $row2;
                $data2['qty'] = $request->input('Qty')[$key];
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                DB::Connection('mysql2')->table('production_bom_data_indirect_material')->insert($data2);
            endif;
        }

        ProductionHelper::production_activity($MasterId, 6, 1);
        return Redirect::to('production/bom_list?pageType=&&parentCode=&&m=' . $_GET['m'] . '#SFR');
    }


    public function update_bom(Request $request)
    {

        //        echo "<pre>";
        //        print_r($request->input());
        //        die();
        $EditId = $request->input('EditId');

        $UpdateMaster['finish_goods'] = $request->input('SubItemId');
        $UpdateMaster['Description'] = $request->input('Description');
        $UpdateMaster['status'] = 1;
        $UpdateMaster['username'] = Auth::user()->name;
        $UpdateMaster['date'] = date('Y-m-d');
        DB::Connection('mysql2')->table('production_bom')->where('id', '=', $EditId)->update($UpdateMaster);
        //    DB::Connection('mysql2')->table('production_bom_data_direct_material')->where('master_id', '=', $EditId)->delete();
        //   DB::Connection('mysql2')->table('production_bom_data_indirect_material')->where('main_id', '=', $EditId)->delete();


        $direct_material = $request->input('item_id');

        foreach ($direct_material as $key => $row2) {

            $data['master_id'] = $EditId;
            $data['item_id'] = $request->input('item_id')[$key];
            $data['qty_mm'] = $request->input('qty_mm')[$key];
            $data['qty_ft'] = $request->input('qty_ft')[$key];
            $data['qty_20_length'] = $request->input('qty_20_length')[$key];
            $data['recover_sreacp'] = $request->input('recover_sreacp')[$key];
            $data['recover_chip'] = $request->input('recover_chip')[$key];
            $data['turning_scrap'] = $request->input('turning_scrap')[$key];
            $data['status'] = 1;
            $data['username'] = Auth::user()->name;

            $id = $request->input('direct_data_id')[$key];

            if ($id == 0) :
                DB::Connection('mysql2')->table('production_bom_data_direct_material')->insert($data);
            else :
                DB::Connection('mysql2')->table('production_bom_data_direct_material')->where('id', $id)->update($data);
            endif;
        }

        $indirect_material = $request->input('in_direct_item_id');

        if (!empty($indirect_material)) :
            foreach ($indirect_material as $key => $row2) {
                if ($row2 != '') :
                    $data2['main_id'] = $EditId;
                    $data2['item_id'] = $row2;
                    $data2['qty'] = $request->input('Qty')[$key];
                    $data['status'] = 1;
                    $data['username'] = Auth::user()->name;


                    $id = $request->input('indirect_data_id')[$key];
                    if ($id == 0) :
                        DB::Connection('mysql2')->table('production_bom_data_indirect_material')->insert($data2);
                    else :
                        DB::Connection('mysql2')->table('production_bom_data_indirect_material')->where('id', $id)->update($data2);
                    endif;

                endif;
            }
        endif;
        ProductionHelper::production_activity($EditId, 6, 2);

        return Redirect::to('production/bom_list?pageType=&&parentCode=&&m=' . $_GET['m'] . '#SFR');
    }

    public function create_operation()
    {

        $finish_goods_data = DB::Connection('mysql2')->table('subitem as a')
            ->leftjoin('production_work_order as b', 'a.id', '=', 'b.finish_good_id')
            ->select('a.id', 'a.sub_ic', 'b.finish_good_id', 'b.status as oper_status')
            ->where('a.status', 1)
            ->where('a.finish_good', 1)
            ->groupBy('a.id')
            ->get();
        $Machine = DB::Connection('mysql2')->table('production_machine')->where('status', 1)->get();
        return view('Production.create_operation', compact('Machine', 'finish_goods_data'));
    }

    public function operation_list()
    {
        $Operation = DB::Connection('mysql2')->table('production_work_order')->where('status', 1)->get();
        return view('Production.operation_list', compact('Operation'));
    }

    public function insert_operation(Request $request)
    {
        $InsertData['operation_name'] = $request->input('OperationName');
        $InsertData['machine_id'] = $request->input('MachineId');
        $InsertData['status'] = 1;
        $InsertData['username'] = Auth::user()->name;
        $InsertData['date'] = date('Y-m-d');
        $MasterId = DB::Connection('mysql2')->table('production_operation')->InsertGetId($InsertData);
        echo "yes";
    }

    public function create_die_detail()
    {
        return view('Production.create_die_detail');
    }

    public function create_bom_detail()
    {
        return view('Production.create_bom_detail');
    }


    public function add_mould_detail()
    {
        return view('Production.add_mould_detail');
    }

    public function viewOperationDetail()
    {
        return view('Production.viewOperationDetail');
    }


    public function insert_mould_detail(Request $request)
    {
        $data = $request->bacth_code;
        $m = $request->m;
        foreach ($data as $key => $row) :
            $data1 = array(
                'mould_id' => $request->mould_id,
                'batch_code' => $row,
                'life' => $data = $request->input('life')[$key],
                'value' => $data = $request->input('value')[$key],
                'cost' => $data = $request->input('cost')[$key],
            );
            $MainId = DB::Connection('mysql2')->table('mould_detail')->InsertGetId($data1);
            ProductionHelper::production_activity($MainId, 4, 1);
        endforeach;

        return Redirect::to('production/moldList?m=' . $m . '&&mould_id=' . $MainId);
    }

    public function get_machine_by_finish_good(Request $request)
    {
        $finish_goods = $request->finish_goods;
        $Machine = DB::Connection('mysql2')->select('select a.id,a.machine_name from production_machine a
                                                      INNER JOIN production_machine_data b on b.master_id = a.id
                                                      WHERE finish_good = ' . $finish_goods . '
                                                      and a.status = 1
                                                      and b.status=1');
        return view('Production.get_machine_by_finish_good', compact('Machine'));
    }


    public function get_operation_data(Request $request)
    {
        $finish_goods = $request->finish_goods;
        $data = DB::Connection('mysql2')->table('production_work_order_data as a')
            ->join('production_work_order as b', 'a.master_id', '=', 'b.id')
            ->join('production_machine as c', 'c.id', '=', 'a.machine_id')
            ->select('c.machine_name', 'c.id as machine_id', 'a.id as operation_data_id', 'b.id as operation_id')
            ->where('b.status', 1)
            ->where('b.finish_good_id', $finish_goods)
            ->groupBy('c.id')
            ->get();

?>

        <div class="row">
            <?php
            $count = 1;
            foreach ($data as $row) :
            ?>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                    <fieldset style="border: solid 1px #b5afaf; border-radius: 10px; ">
                        <h4 class="well"><?php echo $row->machine_name ?> <span id="cls_counter<?php echo $count ?>" class="badge badge-secondary span"></span>
                        </h4>
                        <input name="orderby[]" onclick="set_rout('<?php echo $count ?>')" id="orderby<?php echo $count ?>" type="checkbox" class="form-control checbox">
                        <input type="hidden" name="orderbyy[]" class="orderby<?php echo $count ?> order" value="0" />
                        <input type="hidden" name="machine[]" value="<?php echo $row->machine_id ?>" />
                        <input type="hidden" name="operation_data_id[]" value="<?php echo $row->operation_data_id ?>" />
                        <input type="hidden" name="operation_id" value="<?php echo $row->operation_id ?>" />
                    </fieldset>
                </div>
                <?php
                if ($count == 3 || $count == 6 || $count == 9 || $count == 12 || $count == 15) : ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

                <?php endif; ?>

            <?php $count++;
            endforeach; ?>
        </div>
    <?php

    }

    public function  viewRoutingDetail()
    {
        return view('Production.viewRoutingDetail');
    }

    public function  create_factory_over_head()
    {
        return view('Production.create_factory_over_head');
    }

    public function  edit_factory_over_head()
    {
        return view('Production.edit_factory_over_head');
    }

    public function  create_labours_working()
    {
        return view('Production.create_labours_working');
    }

    public function  edit_labours_working()
    {
        return view('Production.edit_labours_working');
    }


    public function  factory_overhead_list()
    {
        return view('Production.factory_overhead_list');
    }

    public function  labour_working_list()
    {
        return view('Production.labour_working_list');
    }

    public function  view_factory_overhead_detail(Request $request)
    {
        $data = DB::Connection('mysql2')->table('production_factory_overhead')->where('status', 1)->where('id', $request->id)->first();
        $master_data = DB::Connection('mysql2')->table('production_factory_overhead_data')->where('status', 1)->where('master_id', $request->id)->get();
        return view('Production.view_factory_overhead_detail', compact('data', 'master_data'));
    }

    public function create_production_plane(Request $request)
    {

        $data = DB::Connection('mysql2')->table('production_bom')->where('status', 1)->select('finish_goods')->groupBy('finish_goods')->get();
        return view('Production.create_production_plane', compact('data'));
    }

    public function edit_production_plane()
    {

        $data = DB::Connection('mysql2')->table('production_bom')->where('status', 1)->select('finish_goods')->groupBy('finish_goods')->get();
        return view('Production.edit_production_plane', compact('data'));
    }

    public function  make_product_issue_items(Request $request)
    {

        // dd($request);
        $planId = $request->id;

        $issuance_data = DB::Connection('mysql2')->table('production_plane_issuence as a')
            // ->select('a.finish_goods_id')
            ->where('a.main_id', $planId)
            ->pluck('a.work_station_id')->toArray();
        $workName = WorkStation::whereIn('id', $issuance_data)->get();
        // dd($workName);
        return view('Production.ppc_make_product_item', compact('workName', 'planId'));
    }

    public function  insert_make_product_issue_items(Request $request)
    {
        // dd($request);
        $requestData = $request->all();
        $work_station_id = $request->work_station_id;
        DB::beginTransaction();
        $makeProductLastId = MakeProduct::orderBy('id', 'desc')->first();
        foreach ($request->plan_items as $planKey => $value) {
            // dd($requestData['bom_id_'.$planKey]);
            foreach ($requestData['bom_id_'.$planKey] as $key => $bom_id) {
                $mp_no = 'MP' . str_pad((($makeProductLastId) ? $makeProductLastId->id : 0)  + 1, 3, "0", STR_PAD_LEFT);


                try {
                    $recipeItemDetail = ProductionBom::find($bom_id);
                    // dd($requestData['item_'.$planKey][$key], $requestData['req_qty_'.$planKey][$key]);
                    $makeProduct['mp_no']                 = $mp_no;
                    $makeProduct['recipe_id']             = $bom_id;
                    $makeProduct['sub_item_name']         = ProductionBom::find($bom_id)->subItem->sub_ic;
                    $makeProduct['quantity']              = $requestData['req_qty_'.$planKey][$key];
                    $makeProduct['type']                  = $request->finish_good_type;
                    $makeProduct['location_id']           = $requestData['location_id_'.$planKey][$key] ?? 6;
                    $makeProduct['batch_code']            = $requestData['batch_code_'.$planKey][$key] ?? '';
                    $makeProduct['type']                  = $request->finish_good_type;
                    $makeProduct['average_cost']          = 0;
                    $makeProduct['created_by']            = Auth::user()->name;
                    $makeProductId = MakeProduct::insertGetId($makeProduct);


                    ReuseableCode::postStock($makeProductId, 0, $requestData['data_ppc_no_'.$planKey][$key], date('Y-m-d'), 11, 0, $requestData['item_'.$planKey][$key], $recipeItemDetail->sub_ic, $requestData['req_qty_'.$planKey][$key]);
                    // $productionBomDataDetailType1 = ProductionBomData::where('main_id', $bom_id)->where('type', 1)->where('work_station_id', $work_station_id)->get();
                    // $productionBomDataDetailType2 = ProductionBomData::where('main_id', $bom_id)->where('type', 2)->where('work_station_id', $work_station_id)->get();
                    // dd($request, $productionBomDataDetailType1, $productionBomDataDetailType2);
                    foreach ($requestData['data_item_'.$planKey.'_id'] as $key2 => $item) {
                        $requiredQty = $requestData['data_required_'.$planKey.'_qty'][$key2];
                        $issueQty = $requestData['data_issue_'.$planKey.'_qty'][$key2];
                        $productionBomDataDetailType1 = ProductionBomData::where('main_id', $requestData['data_bom_'.$planKey.'_id'][$key2])->where('type', 1)->where('work_station_id', $work_station_id)->first();
                        if ($requiredQty > $issueQty) {
                            DB::rollback();
                            $alertMessage = 'Item '. $productionBomDataDetailType1->subItem->sub_ic .' required quantity is greater then the issue qty.';
                            // dd($alertMessage);
                            return redirect()->back()->with('error', 'The item required quantity is greater then the issue qty.');
                        }
                        // dd($productionBomDataDetailType1);
                        // $totalQty = $item->qty/count($request->item);
                        $makeProductData['make_product_id']   = $makeProductId;
                        $makeProductData['recipe_data_id']    = $item;
                        $makeProductData['sub_item_name']     = $productionBomDataDetailType1->subItem->sub_ic;
                        $makeProductData['uom']               = 1;
                        $makeProductData['actual_qty']        = $requestData['data_required_'.$planKey.'_qty'][$key2]; // required quantity
                        $makeProductData['total_qty']         = $requestData['data_issue_'.$planKey.'_qty'][$key2]; // issued quantity
                        $makeProductData['rate_per_qty']      = 0;
                        $makeProductData['created_by']        = Auth::user()->name;
                        $makeProductDataId = MakeProductData::insertGetId($makeProductData);

                        ReuseableCode::postStock($makeProductId, $requestData['master_id'.$planKey], $requestData['data_ppc_no_'.$planKey][$key2], date('Y-m-d'), 9, 0, $item, $productionBomDataDetailType1->subItem->sub_ic, $requestData['data_required_'.$planKey.'_qty'][$key2]);
                    }
                    DB::commit();
                    // echo "success";
                    return redirect()->back()->with('succes', 'WIP product is created successfully from the raw material.');
                } catch (\Exception $th) {
                    DB::rollback();
                    // echo "error";
                    // dd($th);

                    dd($th->getMessage());
                 //   return redirect()->back()->with('error', 'Oops! something went wrong.');
                }
            }
        }
    }
    public function  create_make_product_issue_items(Request $request)
    {
        // dd($request->work_station);
        $id = $request->plan_id;
        $workStationId = $request->work_station;

        $plan_data = DB::Connection('mysql2')->table('production_plane as a')
            ->join('production_plane_data as b', 'a.id', '=', 'b.master_id')
            ->select('a.id as main_id', 'b.id as master_id', 'b.planned_qty', 'b.finish_goods_id', 'b.order_no')
            ->where('a.status', 1)
            ->where('b.ppc_status', 0)
            ->where('a.id', $id)
            ->get();
        // $plan_bom = ProductionBomData::where('finish_goods', $plan_data)->get();
        // dd(ProductionHelper::get_bom_for_indirect_extra($plan_data[0]->finish_goods_id, $workStationId));
        // dd($plan_data);
        return view('Production.ppc_create_make_product_item', compact('plan_data', 'workStationId'));
    }

    public function  ppc_issue_item(Request $request)
    {
        // dd($request);
        $planId = $request->id;

        $plan_data = DB::Connection('mysql2')->table('production_plane_data as a')
            ->join('production_plane as b', 'b.id', '=', 'a.master_id')
            // ->select('a.finish_goods_id')
            ->where('b.status', 1)
            ->where('a.ppc_status', 0)
            ->where('a.master_id', $planId)
            ->pluck('a.finish_goods_id')->toArray();
        // dd($plan_data);
        $temp = DB::Connection('mysql2')->table('production_bom as a')
            ->join('production_bom_data_indirect_material as b', 'a.id', '=', 'b.main_id')
            // ->select('b.work_station_id')
            ->where('a.status', 1)
            ->whereIn('a.finish_goods', $plan_data)
            ->where('b.type', 1)
            ->pluck('b.work_station_id')->toArray();
        $workName = DB::connection('mysql2')->table('work_station')
            ->whereIn('id', $temp)
            ->get();
        // dd($plan_data, $temp, $workName);
        return view('Production.ppc_issue_item', compact('workName', 'planId'));
    }










    public function  ppc_issue_item_filtered(Request $request)
    {
        // dd($request->work_station);
        $id = $request->plan_id;
        $workStationId = $request->work_station;
        $plan_data = DB::Connection('mysql2')->table('production_plane as a')
            ->join('production_plane_data as b', 'a.id', '=', 'b.master_id')
            ->select('a.id as main_id', 'b.id as master_id', 'b.planned_qty', 'b.finish_goods_id')
            ->where('a.status', 1)
            ->where('b.ppc_status', 0)
            ->where('a.id', $id)
            ->get();
        // dd(ProductionHelper::get_bom_for_indirect($plan_data[0]->finish_goods_id));

        return view('Production.ppc_issue_item_filtered', compact('plan_data', 'workStationId'));
    }

    public function edit_operation()
    {
        return view('Production.edit_operation');
    }

    public function production_plan_list()
    {
        $from = date('Y-m-01');
        $to   = date('Y-m-t');

        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->whereBetween('order_date', [$from, $to])->get();
        return view('Production.production_plan_list', compact('data'));
    }

    public static function get_route(Request $request)
    {
        $finish_good = $request->product_id;
        $data = DB::Connection('mysql2')->table('production_route')->where('status', 1)->where('finish_goods', $finish_good)->get(); ?>
        <option value="">Select Route</option>
        <?php foreach ($data as $row) : ?>
            <option value="<?php echo $row->id ?>"><?php echo $row->voucher_no ?></option>
<?php endforeach;
    }

    public static function save_issue_material(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {
            $work_station_id = $request->work_station_name;
            $location_to = $request->location_to_id;
            $main_id = $request->main_id;
            $master_id = $request->master_id;
            $type = $request->type;
            $bom_data_id = $request->bom_data_id;
            $issue_qty = $request->issue_qty;
            $location_id = $request->location_id;
            $batch_code = $request->batch_code;
            $item_id = $request->item_id;
            $request_qty = $request->request_qty;

            $cost = ReuseableCode::average_cost_sales($item_id, $location_id, $batch_code);

            $plan_detail = ProductionHelper::get_production_plane_detail($request->main_id);
            $voucher_no = $plan_detail->order_no;
            $data = array(
                'work_station_id' => $work_station_id,
                'location_to' => $location_to,
                'main_id' => $main_id,
                'master_id' => $master_id,
                'bom_data_id' => $bom_data_id,
                'type' => $type,
                'issue_qty' => $issue_qty,
                'warehouse_id' => $location_id,
                'batch_code' => $batch_code,
                'cost' => $cost,
                'status' => 1,
                'date' => date('Y-m-d'),
                'username' => Auth::user()->name,
                'request_qty' => $request_qty,
            );
            $id = DB::Connection('mysql2')->table('production_plane_issuence')->insertGetId($data);
            $stock_data = array(
                'main_id' => $master_id,
                'master_id' => $id,
                'voucher_no' => $voucher_no,
                'voucher_date' => date('Y-m-d'),
                'supplier_id' => 0,
                'customer_id' => 0,
                'voucher_type' => 9,
                'rate' => $cost,
                'sub_item_id' => $item_id,
                'batch_code' => $batch_code,
                'qty' => $issue_qty,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'amount' => $cost * $issue_qty,
                'status' => 1,
                'warehouse_id' => $location_id,
                'username' => Auth::user()->username,
                'created_date' => date('Y-m-d'),
                'opening' => 0,
                'so_data_id' => 0
            );
            DB::Connection('mysql2')->table('stock')->insert($stock_data);
            $stock_data = array(
                'main_id' => $master_id,
                'master_id' => $id,
                'voucher_no' => $voucher_no,
                'voucher_date' => date('Y-m-d'),
                'supplier_id' => 0,
                'customer_id' => 0,
                'voucher_type' => 12,
                'rate' => $cost,
                'sub_item_id' => $item_id,
                'batch_code' => $batch_code,
                'qty' => $issue_qty,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'amount' => $cost * $issue_qty,
                'status' => 1,
                'warehouse_id' => $location_to,
                'warehouse_id_to' => $location_to,
                'warehouse_id_from' => $location_id,
                'transfer' => 1,
                'transfer_status' => 1,
                'username' => Auth::user()->username,
                'created_date' => date('Y-m-d'),
                'opening' => 0,
                'so_data_id' => 0
            );
            DB::Connection('mysql2')->table('stock')->insert($stock_data);

            $ppc_data = ProductionHelper::get_production_plane_detail($request->main_id);
            $ppc_no = $ppc_data->order_no;


            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $ppc_no;
            $transaction->master_id = $id;
            $transaction->v_date = date('Y-m-d');

            if ($request->session()->get('run_company') == 1) :
                $acc_id = 856;
            elseif ($request->session()->get('run_company') == 3) :
                $acc_id = 840;
            endif;
            $transaction->acc_id = $acc_id;
         //   $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($acc_id) ??1;
            $transaction->particulars = 'Issue Item : ' . CommonHelper::get_item_name($item_id);
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $cost * $issue_qty;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 16;
            // $transaction->save();


            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $ppc_no;
            $transaction->master_id = $id;
            $transaction->v_date = date('Y-m-d');
            $acc_id = 97;

            $transaction->acc_id = $acc_id;
          //  $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($acc_id) ??1;
            $transaction->particulars = 'Issue Item : ' . CommonHelper::get_item_name($item_id);
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $cost * $issue_qty;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 16;
            // $transaction->save();

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {
            dd($ex->getMessage());
            DB::rollBack();
        }
    }

    public static function view_plan(Request $request)
    {

        if ($request->order_no != '') :
            $id = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('order_no', $request->order_no)->value('id');

        else :
            $id = $request->id;
        endif;

        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $id)->first();
        $master_data = DB::Connection('mysql2')->table('production_plane_data')->where('status', 1)->where('master_id', $id)->get();

        return view('Production.view_plan', compact('data', 'master_data'));
    }

    public static function material_return(Request $request)
    {
        $main_count = 1;
        $conversion = ProductionHelper::check_conversion($request->id);
        if ($conversion > 0) :
            echo 'Not Allowed';
            die;
        endif;
        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $request->id)->first();
        $master_data = DB::Connection('mysql2')->table('production_plane_data')->where('status', 1)->where('master_id', $request->id)->get();
        return view('Production.material_return', compact('data', 'master_data', 'main_count'));
    }

    public static function return_material(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {
            $issuence_data = DB::Connection('mysql2')->table('production_plane_issuence')->where('id', $request->production_plan_issuence_id)->first();
            $cost = ReuseableCode::average_cost_sales($request->item_id, $issuence_data->warehouse_id, $issuence_data->batch_code);
            $data = array(

                'production_plan_id' => $request->production_plan_id,
                'production_plan_data_id' => $request->production_plan_data_id,
                'production_plan_issuence_id' => $request->production_plan_issuence_id,
                'return_qty' => $request->return_qty,
                'cost' => $cost,
                'status' => 1,
                'date' => date('Y-m-d'),
                'username' => Auth::user()->name
            );
            $id = DB::Connection('mysql2')->table('production_plane_return')->insertGetId($data);

            $plan_detail = ProductionHelper::get_production_plane_detail($request->production_plan_id);
            $voucher_no = $plan_detail->order_no;
            $stock_data = array(
                'main_id' => $request->production_plan_data_id,
                'master_id' => $id,
                'voucher_no' => $voucher_no,
                'voucher_date' => date('Y-m-d'),
                'supplier_id' => 0,
                'customer_id' => 0,
                'voucher_type' => 10,
                'rate' => $cost,
                'sub_item_id' => $request->item_id,
                'batch_code' => $issuence_data->batch_code,
                'qty' => $request->return_qty,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'amount' => $cost * $request->return_qty,
                'status' => 1,
                'warehouse_id' => $issuence_data->warehouse_id,
                'username' => Auth::user()->username,
                'created_date' => date('Y-m-d'),
                'opening' => 0,
                'so_data_id' => 0
            );
            DB::Connection('mysql2')->table('stock')->insert($stock_data);

            $ppc_data = ProductionHelper::get_production_plane_detail($request->production_plan_id);
            $ppc_no = $ppc_data->order_no;




            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $ppc_no;
            $transaction->master_id = $request->production_plan_issuence_id;
            $transaction->v_date = date('Y-m-d');
            $acc_id = 97;

            $transaction->acc_id = $acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($acc_id);
            $transaction->particulars = 'Return Item : ' . CommonHelper::get_item_name($request->item_id);
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $cost * $request->return_qty;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 17;
            $transaction->save();




            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $ppc_no;
            $transaction->master_id = $request->production_plan_issuence_id;
            $transaction->v_date = date('Y-m-d');

            if ($request->session()->get('run_company') == 1) :
                $acc_id = 856;
            elseif ($request->session()->get('run_company') == 3) :
                $acc_id = 840;
            endif;
            $transaction->acc_id = $acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($acc_id);
            $transaction->particulars = 'Return Item : ' . CommonHelper::get_item_name($request->item_id);
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $cost * $request->return_qty;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 17;
            $transaction->save();

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
        }
    }

    public static function conversion(Request $request)
    {
        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $request->id)->first();
        $master_data = DB::Connection('mysql2')->table('production_plane_data')->where('status', 1)->where('master_id', $request->id)->get();
        return view('Production.conversion', compact('data', 'master_data'));
    }

    public static function conversion_cost(Request $request)
    {
        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $request->id)->first();
        $master_data = DB::Connection('mysql2')->table('production_plane_data')->where('status', 1)->where('master_id', $request->id)->get();

        return view('Production.conversion_cost', compact('data', 'master_data'));
    }

    public static function view_cost(Request $request)
    {

        if ($request->order_no != '') :
            $id = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('order_no', $request->order_no)->value('id');

        else :
            $id = $request->id;
        endif;
        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $id)->first();

        $costing_data = DB::Connection('mysql2')->table('costing_data as a')
            ->join('production_plane_data as b', 'a.production_plan_data_id', '=', 'b.id')
            ->select('a.*', 'b.finish_goods_id')
            ->where('a.status', 1)
            ->where('a.production_plan_id', $id)
            ->get();

        return view('Production.view_cost', compact('data', 'costing_data'));
    }

    public function production_activity_page()
    {
        return view('Production.production_activity_page');
    }

    public function production_activity_ajax()
    {
        return view('Production.production_activity_aja');
    }

    public function get_cost_data(Request $request)
    {
        $page = $request->cost_page;
        $id = $request->id;
        $planned_qty = $request->planned_qty;
        return view('Production.costing_view.' . $page, compact('id', 'planned_qty'));
    }
    public static function cost_insert(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {

            $production_plan_id = $request->production_plan_data_id;


            $ppc_detail = ProductionHelper::get_production_plane_detail($request->production_plan_id);
            $voucher_no = $ppc_detail->order_no;

            $total_labour_amount = 0;
            $total_die_mould = 0;
            $total_machine_cost = 0;
            $total_foh = 0;
            foreach ($production_plan_id as $key => $row) :
                $data = array(
                    'production_plan_id' => $request->production_plan_id,
                    'production_plan_data_id' => $row,
                    'direct_material_cost' => $request->input('db_direct_m_cost')[$key],
                    'indirect_material_cost' => $request->input('db_in_direct_m_cost')[$key],
                    'direct_labour' => $request->input('db_direct_labour')[$key],
                    'die_mould' => $request->input('db_die_mould')[$key],
                    'machine_cost' => $request->input('db_machine')[$key],
                    'foh' => $request->input('db_foh')[$key],
                    'dl_rate' => ProductionHelper::get_labour_rate(),
                    'status' => 1,
                    'username' => Auth::user()->name,
                    'date' => date('Y-m-d'),
                    'time' => date("h:i:s a"),
                );

                $id =  DB::Connection('mysql2')->table('costing_data')->insertGetId($data);


                $ppc_data = ProductionHelper::get_production_plane_detail_data($row);
                $planned_qty = $ppc_data->planned_qty;


                $total_labour_amount += $request->input('db_direct_labour')[$key];
                $total_die_mould += $request->input('db_die_mould')[$key];
                $total_machine_cost += $request->input('db_machine')[$key] * $planned_qty;
                $total_foh += $request->input('db_foh')[$key];

                $direct_material = $request->input('dm_cost' . $key);

                foreach ($direct_material as $index => $row1) :

                    $data1 = array(
                        'master_id' => $id,
                        'dmt_cost_formula' => $request->input('dm_cost_formula' . $key)[$index],
                        'dmt_cost' => $row1,
                        'dmt_spoil_formula' => $request->input('dmt_spoil_cost_formula' . $key)[$index],
                        'dmt_spoil_cost' => $request->input('dmt_spoil_cost' . $key)[$index],
                        'dmt_excess_formula' => $request->input('dmt_excess_formula' . $key)[$index],
                        'dmt_excess' => $request->input('dmt_excess' . $key)[$index],
                        'total_cost' => $request->input('dmt_total_cost' . $key)[$index],
                        'status' => 1,
                        'username' => Auth::user()->name,
                        'date' => date('Y-m-d'),
                        'time' => date("h:i:s a"),
                    );
                    DB::Connection('mysql2')->table('direct_material_costing')->insert($data1);
                endforeach;



                $idirect_material = $request->input('idm_cost' . $key);

                if (!empty($idirect_material)) :

                    foreach ($idirect_material as $index => $row2) :

                        $data2 = array(
                            'master_id' => $id,
                            'idmt_cost_formula' => $request->input('idm_cost_formula' . $key)[$index],
                            'idmt_cost' => $row2,
                            'idmt_spoil_formula' => $request->input('idm_spoilage_formula' . $key)[$index],
                            'idmt_spoil_cost' => $request->input('idm_spoilage' . $key)[$index],
                            'idmt_excess_formula' => $request->input('idm_excess_formula' . $key)[$index],
                            'idmt_excess' => $request->input('idm_excess_cost' . $key)[$index],
                            'total_cost' => $request->input('idm_total_cost' . $key)[$index],
                            'status' => 1,
                            'username' => Auth::user()->name,
                            'date' => date('Y-m-d'),
                            'time' => date("h:i:s a"),
                        );
                        DB::Connection('mysql2')->table('indirect_material_costing')->insert($data2);
                    endforeach;
                endif;



                $labour_data = $request->input('machine_id' . $key);

                foreach ($labour_data as $index => $row3) :

                    $data3 = array(
                        'master_id' => $id,
                        'machine_id' => $row3,
                        'setup_time' => $request->input('setup_time' . $key)[$index],
                        'working_time_formula' => $request->input('working_time_formula' . $key)[$index],
                        'working_time' => $request->input('working_time' . $key)[$index],
                        'operation_time_formula' => $request->input('operation_time_formula' . $key)[$index],
                        'operation_time' => $request->input('operation_time' . $key)[$index],
                        'capacity' => $request->input('capacity' . $key)[$index],
                        'total_time_mint_formula' => $request->input('total_time_mint_formula' . $key)[$index],
                        'total_time_mint' => $request->input('total_time_mint' . $key)[$index],
                        'status' => 1,
                        'username' => Auth::user()->name,
                        'date' => date('Y-m-d'),
                        'time' => date("h:i:s a"),
                    );
                    DB::Connection('mysql2')->table('direct_labour_costing')->insert($data3);
                endforeach;





                $die_mould_data = $request->input('machine_id_for_die_mould' . $key);

                foreach ($die_mould_data as $index => $row4) :

                    $data4 = array(
                        'master_id' => $id,
                        'machine_id' => $row4,
                        'die_formula' => $request->input('die_formula' . $key)[$index],
                        'die_cost' => $request->input('die_cost' . $key)[$index],
                        'mould_formula' => $request->input('mould_formula' . $key)[$index],
                        'mould_cost' => $request->input('mould_cost' . $key)[$index],
                        'status' => 1,
                        'username' => Auth::user()->name,
                        'date' => date('Y-m-d'),
                        'time' => date("h:i:s a"),
                    );
                    DB::Connection('mysql2')->table('die_mould_costing')->insert($data4);
                    if ($request->input('die_formula' . $key)[$index] != '') :
                        static::insert_die_usage(
                                $row4,
                                $ppc_detail->order_no,
                                $ppc_data->master_id,
                                $ppc_data->id,
                                $ppc_data->finish_goods_id
                            );
                    endif;


                    if ($request->input('mould_formula' . $key)[$index] != '') :
                        static::insert_mould_usage(
                                $row4,
                                $ppc_detail->order_no,
                                $ppc_data->master_id,
                                $ppc_data->id,
                                $ppc_data->finish_goods_id
                            );
                    endif;


                endforeach;




                $machine_data = $request->input('machine_for_machine' . $key);

                foreach ($machine_data as $index => $row5) :

                    $data4 = array(
                        'master_id' => $id,
                        'machine_id' => $row5,
                        'annual_dep' => $request->input('annual_dep' . $key)[$index],
                        'status' => 1,
                        'username' => Auth::user()->name,
                        'date' => date('Y-m-d'),
                        'time' => date("h:i:s a"),
                    );
                    DB::Connection('mysql2')->table('machine_costing')->insert($data4);
                endforeach;



                $foh_data = $request->input('foh' . $key);

                foreach ($foh_data as $index => $row6) :

                    $data4 = array(
                        'master_id' => $id,
                        'foh' => $row6,
                        'yearly_wages' => $request->input('yearly_wages' . $key)[$index],
                        'direct_labour_for_machine_foh_hidden' => $request->input('direct_labour_for_machine_foh_hidden' . $key)[$index],
                        'foh_applied_amount_foh_hidden' => $request->input('foh_applied_amount_foh_hidden' . $key)[$index],
                        'status' => 1,
                        'username' => Auth::user()->name,
                        'date' => date('Y-m-d'),
                        'time' => date("h:i:s a"),
                    );
                    DB::Connection('mysql2')->table('foh_costing')->insert($data4);
                endforeach;


            endforeach;



            $acc_code = '';
            if ($request->session()->get('run_company') == 1) :
                $acc_code = '1-2-1-2';
            elseif ($request->session()->get('run_company') == 3) :
                $acc_code = '1-2-16-3';
            endif;


            // $work in progress
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = ReuseableCode::get_acc_id_by_code($acc_code);
            $transaction->acc_code = $acc_code;
            $transaction->particulars = 'Cost';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_die_mould + $total_machine_cost + $total_foh;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 18;
            $transaction->save();



            // labour amount


            $acc_code = '1-2-16-2';
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = ReuseableCode::get_acc_id_by_code($acc_code);
            $transaction->acc_code = $acc_code;
            $transaction->particulars = 'Labour';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_labour_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 18;
            $transaction->save();

            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = ReuseableCode::get_acc_id_by_code('1-2-15-5');
            $transaction->acc_code = '1-2-15-5';
            $transaction->particulars = 'Cost';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_labour_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 18;
            $transaction->save();







            // die mould
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = ReuseableCode::get_acc_id_by_code('1-2-15-3');
            $transaction->acc_code = '1-2-15-3';
            $transaction->particulars = 'Cost';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_die_mould;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 18;
            $transaction->save();

            // machine
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = ReuseableCode::get_acc_id_by_code('1-2-15-2');
            $transaction->acc_code = '1-2-15-2';
            $transaction->particulars = 'Cost';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_machine_cost;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 18;
            $transaction->save();


            // foh
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = ReuseableCode::get_acc_id_by_code('1-2-15-4');
            $transaction->acc_code = '1-2-15-4';
            $transaction->particulars = 'Cost';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_foh;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 18;
            $transaction->save();



            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
        }

        return Redirect::to('production/production_plan_list?m=' . Session::get('run_company'));
    }


    function decline_cost(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {
            $data['status'] = 0;
            // costing data
            DB::Connection('mysql2')->table('costing_data')->where('production_plan_id', $request->id)->update($data);
            DB::Connection('mysql2')->table('costing_data')->where('production_plan_id', $request->id)->update($data);





            // production_conversion
            $conversion_data = DB::Connection('mysql2')->table('production_conversion')->where('production_plan_id', $request->id)->where('status', 1);
            $conversion_id = $conversion_data->first()->id;
            $conversion_data->update($data);

            // prouction_conversion_data
            DB::Connection('mysql2')->table('prouction_conversion_data')->where('master_id', $conversion_id)->update($data);


            // prouction_conversion_data
            DB::Connection('mysql2')->table('production_conversion_data_material')->where('production_conversion_id', $conversion_id)->update($data);


            $ppc_data =  ProductionHelper::get_production_plane_detail($request->id);
            $ppc_no = $ppc_data->order_no;

            $transaction_data = array(
                'status' => 0,

            );


            DB::Connection('mysql2')->table('die_usage_report')
                ->where('ppc_no', $ppc_no)
                ->update($transaction_data);


            DB::Connection('mysql2')->table('transactions')
                ->where('voucher_type', 18)
                ->where('voucher_no', $ppc_no)
                ->update($transaction_data);


            DB::Connection('mysql2')->table('production_wastage_data')
                ->where('ppc_no', $ppc_no)
                ->update($transaction_data);


            if ($request->type == 1) :
                $data['status'] = 0;
                DB::Connection('mysql2')->table('production_plane')->where('id', $request->id)->update($data);
                DB::Connection('mysql2')->table('production_plane_data')->where('master_id', $request->id)->update($data);
                DB::Connection('mysql2')->table('production_plane_issuence')->where('main_id', $request->id)->update($data);
                DB::Connection('mysql2')->table('production_plane_return')->where('production_plan_id', $request->id)->update($data);
                DB::Connection('mysql2')->table('stock')->where('voucher_no', $ppc_no)->update($data);

                DB::Connection('mysql2')->table('transactions')
                    ->where('voucher_no', $ppc_no)
                    ->update($data);

            endif;



            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
        }
    }

    function  get_ledger_data(Request $request)
    {
        $voucher_no = $request->voucher_no;
        $type = $request->type;
        $id = $request->id;
        if ($id != 0 || $request->cost == 1) :
            $data = DB::Connection('mysql2')->table('transactions')
                ->where('voucher_no', $voucher_no)
                ->where('status', 1)
                ->where('voucher_type', $type)
                ->where('master_id', $id)
                ->get();

            return view('Production.get_ledger_data', compact('data'));
        else :

            $issue_data = DB::Connection('mysql2')->table('transactions')
                ->where('voucher_no', $voucher_no)
                ->where('status', 1)
                ->where('voucher_type', 16)
                ->get();

            $return_data = DB::Connection('mysql2')->table('transactions')
                ->where('voucher_no', $voucher_no)
                ->where('status', 1)
                ->where('voucher_type', 17)

                ->get();
            return view('Production.get_issue_return_vouchers', compact('issue_data', 'return_data'));
        endif;
    }


    function approve_plan(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {

            $voucher_no = $request->order_no;
            $data_id = $request->data_id;


            foreach ($data_id as $key => $row1) :

                $plane_data = array(
                    'warehouse_id' => $request->input('warehouse_id')[$key],
                    'batch_code' => $request->input('batch_code')[$key],
                );

                DB::Connection('mysql2')->table('production_plane_data')->where('id', $row1)->update($plane_data);

            endforeach;



            $ppc_data =   DB::Connection('mysql2')->table('production_plane_data')
                ->where('status', 1)
                ->where('master_id', $request->id)
                ->get();


            foreach ($ppc_data as $row) :

                $conversion_data =  ProductionHelper::get_conversion_data_row($row->id);
                $produce_qty = $conversion_data->produce_qty;


                $cost = ProductionHelper::get_total_cost_of_plane($row->id);
                $stock_data = array(
                    'main_id' => $row->master_id,
                    'master_id' => $row->id,
                    'voucher_no' => $row->order_no,
                    'voucher_date' => date('Y-m-d'),
                    'supplier_id' => 0,
                    'customer_id' => 0,
                    'voucher_type' => 11,
                    'rate' => $cost / $produce_qty,
                    'sub_item_id' => $row->finish_goods_id,
                    'batch_code' => $row->batch_code,
                    'qty' => $produce_qty,
                    'discount_percent' => 0,
                    'discount_amount' => 0,
                    'amount' => $cost,
                    'status' => 1,
                    'warehouse_id' => $row->warehouse_id,
                    'username' => Auth::user()->username,
                    'created_date' => date('Y-m-d'),
                    'opening' => 0,
                    'so_data_id' => 0
                );
                DB::Connection('mysql2')->table('stock')->insert($stock_data);
            endforeach;

            $factory_overhead =  DB::Connection('mysql2')->table('transactions')
                ->where('status', 1)
                ->where('voucher_no', $voucher_no)
                ->where('voucher_type', 18)
                ->where('debit_credit', 0)
                ->whereIn('acc_id', array('847', '845', '846'))->sum('amount');


            $labour =  DB::Connection('mysql2')->table('transactions')
                ->where('status', 1)
                ->where('voucher_no', $voucher_no)
                ->where('voucher_type', 18)
                ->where('debit_credit', 0)
                ->whereIn('acc_id', array('848'))->sum('amount');



            $issuence =  DB::Connection('mysql2')->table('transactions')
                ->where('status', 1)
                ->where('voucher_no', $voucher_no)
                ->where('voucher_type', 16)
                ->where('debit_credit', 1)
                ->whereIn('acc_id', array('840'))->sum('amount');


            $return =  DB::Connection('mysql2')->table('transactions')
                ->where('status', 1)
                ->where('voucher_no', $voucher_no)
                ->where('voucher_type', 17)
                ->where('debit_credit', 0)
                ->whereIn('acc_id', array('840'))->sum('amount');

            $net_issued = $issuence - $return;



            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = 97;
            $transaction->acc_code = '1-2-1-1';
            $transaction->particulars = 'Cost';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $factory_overhead + $labour + $net_issued;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 19;
            $transaction->save();

            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = 842;
            $transaction->acc_code = '1-2-16-3';
            $transaction->particulars = 'Factory Overhead';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $factory_overhead;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 19;
            $transaction->save();


            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = 841;
            $transaction->acc_code = '1-2-16-2';
            $transaction->particulars = 'Labour';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $labour;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 19;
            $transaction->save();


            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $voucher_no;
            $transaction->master_id = 0;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = 840;
            $transaction->acc_code = '1-2-16-1';
            $transaction->particulars = 'Material';
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $net_issued;
            $transaction->username = Auth::user()->name;;
            $transaction->date = date('Y-m-d');
            $transaction->status = 1;
            $transaction->voucher_type = 19;
            $transaction->save();




            echo $factory_overhead;

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
        }
    }


    function delete_machine_data(Request $request)
    {
        $id = $request->id;
        DB::Connection('mysql2')->table('production_machine_data')->where('id', $id)->delete();
        echo $id;
    }


    function view_issuence(Request $request)
    {
        $id = $request->id;
        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $id)->first();

        $ppc_issue_data = DB::Connection('mysql2')->table('production_plane_issuence as a')
            ->join('production_plane_data as b', 'a.master_id', '=', 'b.id')
            ->select('a.type', 'a.issue_qty', 'b.finish_goods_id', 'a.bom_data_id', 'a.date', 'a.username')
            ->where('a.status', 1)
            ->where('a.main_id', $id)
            ->where('b.status', 1)
            ->orderBy('a.master_id')
            ->get();
        return view('Production.ppc_issuence_detail', compact('data', 'ppc_issue_data'));
    }

    public function consumption_edit(Request $request)
    {
        $id = decrypt($request->id);
        $main_data =   DB::Connection('mysql2')->table('internal_consumtion')->where('id', $id)->first();
        $child_data =   DB::Connection('mysql2')->table('internal_consumtion_data')->where('master_id', $id)->get();

        return view('Production.consumption_edit', compact('main_data', 'child_data'));
    }


    public function production_detail_report(Request $request)
    {

        return view('Production.production_detail_report');
    }

    public function get_production_detail_report(Request $request)
    {
        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $request->ppc_no)->first();
        $master_data = DB::Connection('mysql2')->table('production_plane_data')->where('status', 1)->where('master_id', $request->ppc_no)->get();
        return view('Production.get_production_detail_report', compact('master_data', 'data'));
    }

    public function costing_finish_goods(Request $request)
    {

        return view('Production.production_costing_report.costing_finish_goods');
    }

    public function get_finish_goods_data(Request $request)
    {
        $data = DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $request->ppc_no)->first();
        $master_data = DB::Connection('mysql2')->table('production_plane_data')->where('status', 1)->where('master_id', $request->ppc_no)->get();


        return view('Production.production_costing_report.get_finish_goods_data', compact('data', 'master_data'));
    }

    public function die_usage_report(Request $request)
    {

        return view('Production.Die Usage Reports.die_usage_report');
    }


    public function mould_usage_report(Request $request)
    {

        return view('Production.Mould Usage Reports.mould_usage_report');
    }

    public static function insert_die_usage($machine_id, $ppc_no, $plane_id, $plane_data, $finish_good_id)
    {
        $machine_data = ProductionHelper::get_machine_data($machine_id, $finish_good_id);
        $die_id = $machine_data->dai_id;

        if ($die_id != 0 && $die_id != '') :
            $detaild_data = ProductionHelper::get_die_bacth_detail($die_id);
            $die_detail_id = $detaild_data->id;
            $dataaaa = array(
                'production_plan_id' => $plane_id,
                'production_plan_data_id' => $plane_data,
                'ppc_no' => $ppc_no,
                'date' => date('Y-m-d'),
                'username' => Auth::user()->name,
                'die_id' => $die_id,
                'batch_code_id' => $die_detail_id,


            );
            DB::Connection('mysql2')->table('die_usage_report')->insert($dataaaa);
        endif;
    }


    public static function insert_mould_usage($machine_id, $ppc_no, $plane_id, $plane_data, $finish_good_id)
    {
        $machine_data = ProductionHelper::get_machine_data($machine_id, $finish_good_id);
        $mould_id = $machine_data->mold_id;

        if ($mould_id != 0 && $mould_id != '') :
            $mould_data = ProductionHelper::get_mould_bacth_detail($mould_id);
            $mould_detail_id = $mould_data->id;
            $dataaaa = array(
                'production_plan_id' => $plane_id,
                'production_plan_data_id' => $plane_data,
                'ppc_no' => $ppc_no,
                'date' => date('Y-m-d'),
                'username' => Auth::user()->name,
                'mould_id' => $mould_id,
                'batch_code_id' => $mould_detail_id,


            );
            DB::Connection('mysql2')->table('mould_usage_report')->insert($dataaaa);
        endif;
    }

    public function die_usage(Request $request)
    {
        $data = DB::Connection('mysql2')->table('die_mould_costing')->where('status', 1)->where('die_formula', '!=', '')->get();


        foreach ($data as $row) :

            $costing_data = DB::Connection('mysql2')->table('costing_data')->where('id', $row->master_id)->first();

            $ppc_data = DB::Connection('mysql2')->table('production_plane_data')->where('id', $costing_data->production_plan_data_id)->first();
            $order_no = $ppc_data->order_no;

            $machine_data = ProductionHelper::get_machine_data($row->machine_id, $ppc_data->finish_goods_id);
            $die_id = $machine_data->dai_id;

            $detaild_data = ProductionHelper::get_die_bacth_code($die_id);
            $die_detail_id = $detaild_data->id;


            $dataaaa = array(
                'production_plan_id' => $costing_data->production_plan_id,
                'production_plan_data_id' => $costing_data->production_plan_data_id,
                'ppc_no' => $order_no,
                'date' => date('Y-m-d'),
                'username' => Auth::user()->name,
                'die_id' => $die_id,
                'batch_code_id' => $die_detail_id,
                'ip' => $request->ip(),

            );
            DB::Connection('mysql2')->table('die_usage_report')->insert($dataaaa);
        endforeach;
    }

    public function mould_usage(Request $request)
    {
        $data = DB::Connection('mysql2')->table('die_mould_costing')->where('status', 1)->where('mould_formula', '!=', '')->get();


        foreach ($data as $row) :

            $costing_data = DB::Connection('mysql2')->table('costing_data')->where('id', $row->master_id)->first();

            $ppc_data = DB::Connection('mysql2')->table('production_plane_data')->where('id', $costing_data->production_plan_data_id)->first();
            $order_no = $ppc_data->order_no;

            $machine_data = ProductionHelper::get_machine_data($row->machine_id, $ppc_data->finish_goods_id);
            $mould_id = $machine_data->mold_id;

            $detaild_data = ProductionHelper::get_mould_bacth_code($mould_id);
            $mould_detail_id = $detaild_data->id;


            $dataaaa = array(
                'production_plan_id' => $costing_data->production_plan_id,
                'production_plan_data_id' => $costing_data->production_plan_data_id,
                'ppc_no' => $order_no,
                'date' => date('Y-m-d'),
                'username' => Auth::user()->name,
                'mould_id' => $mould_id,
                'batch_code_id' => $mould_detail_id,
                'ip' => $request->ip(),

            );
            DB::Connection('mysql2')->table('mould_usage_report')->insert($dataaaa);
        endforeach;
    }

    public function machine_usage_report(Request $request)
    {

        return view('Production.Machine Usage Reports.machine_usage_report');
    }
    public function get_machine_usage_data(Request $request)
    {

        $company_data = ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from = $company_data[0];
        $to = $request->to_date;

        return view('Production.Machine Usage Reports.get_machine_usage_data', compact('from', 'to'));
    }

    public function get_mould_usage_report(Request $request)
    {

        $company_data = ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from = $company_data[0];
        $to = $request->to_date;

        return view('Production.Mould Usage Reports.get_mould_usage_report', compact('from', 'to'));
    }

    public function die_mould_usage_report(Request $request)
    {

        $company_data = ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from = $company_data[0];
        $to = $request->to_date;

        return view('Production.Die Usage Reports.die_mould_usage_report', compact('from', 'to'));
    }

    public function finish_good_cost_history(Request $request)
    {

        return view('Production.Finish Goods Cost History.finish_good_cost_history');
    }

    public function get_finish_goods_history(Request $request)
    {
        $company_data = ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from = $company_data[0];
        $to = $request->to_date;

        return view('Production.Finish Goods Cost History.get_finish_goods_history', compact('from', 'to'));
    }
    public function scarp_report(Request $request)
    {

        return view('Production.Scarp Report.scarp_report');
    }

    public function get_scarp_report(Request $request)
    {
        $company_data = ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from = $company_data[0];
        $to = $request->to_date;

        return view('Production.Scarp Report.get_scarp_report', compact('from', 'to'));
    }

    public function costing_finish_goods_estimator(Request $request)
    {

        return view('Production.Estimator.costing_finish_goods_estimator');
    }

    public function get_data(Request  $request)
    {
        $finish_good = $request->finish_goods;
        $costing_data = DB::Connection('mysql2')->table('costing_data as a')
            ->join('production_plane_data as b', 'a.production_plan_data_id', '=', 'b.id')
            ->select('a.*', 'b.planned_qty')
            ->where('a.status', 1)
            ->where('b.finish_goods_id', $finish_good)
            ->orderBy('a.id', 'DESC')
            ->first();


        echo  $costing_data->indirect_material_cost . '/' .
            $costing_data->direct_labour . '/' .
            $costing_data->die_mould . '/' .
            $costing_data->machine_cost . '/' .
            $costing_data->foh . '/' .
            $costing_data->total_foh . '/' .
            $costing_data->planned_qty;
    }


    public function add_estimatore(Request $request)
    {

        return view('Production.Estimator.view_estimator');
    }

    public function get_production_plan_list(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $request_status = $request->status;

        $data = DB::Connection('mysql2')->table('production_plane')
            ->where('status', 1)
            ->whereBetween('order_date', [$from, $to])
            ->get();
        return view('Production.get_production_plan_list', compact('data', 'request_status'));
    }

    public function ccp_list(Request $request)
    {
        $qcs = QC::all();
        return view('Production.ccp_record.ccp_list',compact('qcs'));
    }
    public function add_ccp_form(Request $request)
    {
       $ProductionPlane = ProductionPlane::where('status',1)->get();
        return view('Production.ccp_record.add_ccp_form',compact('ProductionPlane'));

    }
    public function qc_edit(Request $request,$id)
    {
       $qcs = Qc::find($request->id);
       $qcs_data = QcData::where('qc_id',$request->id)->get();
       $ProductionPlane = ProductionPlane::where('status',1)->get();
       return view('Production.ccp_record.ccp_edit_form',compact('qcs','qcs_data','ProductionPlane'));
    }
    public function qc_delete(Request $request,$id)
    {
    DB::Connection('mysql2')->beginTransaction();
    try {
        $qcs = QC::find($request->id);
        $qcs_data = QcData::where('qc_id',$qcs->id)->get();
        foreach ($qcs_data as $value) {
           $value->delete();
        }
        if($qcs->delete())
        {
            DB::Connection('mysql2')->commit();
            return $request->id;
        }else{
            DB::rollBack();
            return 0;
        }
    } catch (Exception $ex) {

        DB::rollBack();
            dd($ex);
        $ex->getCode();
    }

    }

    public function viewQcInfo(Request $request)
    {

        $qcs = Qc::find($request->id);
        $qcs_data = QcData::where('qc_id',$request->id)->get();

         return view('Production.ccp_record.view_ccp_form',compact('qcs','qcs_data'));
    }

    public function getOverAllstock(Request $request)
    {
        $receipe_id = $request->receipe_id;
        $category_id = $request->category_id;
  
       $Bom =  ProductionBom::where('status',1)
       ->where('id',$receipe_id)
       ->where('category_id',$category_id)
       ->first();

       return $Bom->required_qty;
    }

    public function getItemByCategory(Request $request)
    {
        $value = '';
        $subitems = Subitem::where('status', 1)->whereIn('main_ic_id', [7, 8])->get();

        foreach ($subitems as $item) {
            $selected = ($request->item_id == $item->id) ? 'selected' : '';
            $value .= '<option ' . $selected . ' value="' . $item->id . '">' . $item->item_code.' -- '.$item->sub_ic . '</option>';
        }
        return $value;
    }

    public function getStockForProduction(Request $request)
    {
        $item_id = $request->id;
        $warehouse_id = $request->warehouse_id;
        $batch_code = $request->batch_code;
        return $stock = CommonHelper::in_stock_edit($item_id, $warehouse_id, $batch_code);
    }

    public function getStockForTransfer(Request $request)
    {
        $item_id = $request->id;
        $warehouse_id = $request->warehouse_id;
        $batch_code = $request->batch_code;
        return $stock = CommonHelper::in_stock_edit_with_amount($item_id, $warehouse_id, $batch_code);
    }

    public function getReceipeData(Request $request)
    {
        $id =  $request->id;
        $class_name = $request->class_name;
        $receipe =  ProductionBom::where('id',$id)->first();

        $receipe_data =  ProductionBomData::where('main_id',$id)->get();
        $raw_material = DB::Connection('mysql2')->table('subitem')
        ->select('id', 'sub_ic','uom','item_code')
        ->where('status', '=', 1)->whereIn('main_ic_id', [7, 8])->get();
        return view('selling.production.getReceipeData',compact('receipe','receipe_data' , 'class_name','raw_material')); 
    }

    public function getReceipeDataOfSingleItem(Request $request)
    {
        $id =  $request->id;
        $receipe_data =  ProductionBomData::where('main_id',$id)->get();
        return view('selling.production.getReceipeDataOfSingleItem',compact('receipe_data','receipe','raw_material')); 
    }
    
    public function getReceipeDataView(Request $request)
    {
        $id =  $request->id;
        $receipe =  ProductionBom::where('id',$id)->first();
        $receipe_data =  ProductionBomData::where('main_id',$id)->get();
        $raw_material = DB::Connection('mysql2')->table('subitem')
        ->select('id', 'sub_ic','uom','item_code')
        ->where('status', '=', 1)->where('main_ic_id', '=', 7)->get();
        return view('selling.production.getReceipeDataView',compact('receipe','receipe_data','raw_material'));
    }

    public function approveProductionPlanMr(Request $request)
    {
        $production_id =  $request->production_id;
        $production_plan_data_id =  $request->production_plan_data_id;
        $finish_good_id =  $request->finish_good_id;

        $approved = DB::connection('mysql2')
            ->table('material_requisitions')
            ->where('production_id', $production_id)
            ->where('production_plan_data_id', $production_plan_data_id)
            ->where('finish_good_id', $finish_good_id)
            ->where('status', 1)
            ->update(['approval_status' => 2]);

        if($approved) {
            return "true";
        } else {
            return "false";
        }


        // if ($affectedRows > 0) {
        //     return 1; // Success
        // } else {
        //     return 2; // No rows were updated
        // }
        // $receipe_data =  ProductionBomData::where('main_id',$id)->get();
        // return view('selling.production.getReceipeData',compact('receipe_data'));

    }

    public function createWaistageAndRecycle(Request $request){
        return view('selling.production.createWaistageAndRecycle');
    }

    public function editWaistageAndRecycle(Request $request){
        $id = $request->id;
        $recycle_wastage = DB::Connection('mysql2')->table('recycle_wastage')->where('id' , $id)->where('status' , 1)->first();
        $recycle_wastage_data = DB::Connection('mysql2')->table('recycle_wastage_data')->where('master_id' , $recycle_wastage->id)->where('status' , 1)->get();
        
        return view('selling.production.editWaistageAndRecycle' , compact('recycle_wastage' , 'recycle_wastage_data'));
    }

    public function listWaistageAndRecycle(Request $request){
        if($request->ajax())
        {
             
            $recycle_wastages = DB::Connection('mysql2')->table('recycle_wastage as wr')
            ->join('subitem as s','s.id','wr.item_id')
            ->join('warehouse as w','w.id','wr.warehouse_id')
            ->select('wr.*' , 's.sub_ic' , 's.item_code' , 'w.name')
            ->where('wr.status' , 1)
            ->get(); 
            
            return view('selling.production.listWaistageAndRecycleAjax',compact('recycle_wastages'));
        }
        return view('selling.production.listWaistageAndRecycle');
    }

    public function viewRecycleWastage(Request $request){
        $id = $request->id;
        $recycle_wastage = DB::Connection('mysql2')->table('recycle_wastage')->where('id' , $id)->where('status' , 1)->first();
        $recycle_wastage_data = DB::Connection('mysql2')->table('recycle_wastage_data')->where('master_id' , $recycle_wastage->id)->where('status' , 1)->get();
        // dd($recycle_wastage , $recycle_wastage_data);
        return view('selling.production.viewWaistageAndRecycle' , compact('recycle_wastage' , 'recycle_wastage_data') );

    }

    public function deleteRecycleWastage(Request $request){
        $id = $request->id;
        $recycle_wastage = DB::Connection('mysql2')->table('recycle_wastage')->where('id' , $id)->where('status' , 1)->first();
        $recycle_wastage_data = DB::Connection('mysql2')->table('recycle_wastage_data')->where('master_id' , $recycle_wastage->id)->where('status' , 1)->get();
        if ($recycle_wastage) { 
            
            DB::Connection('mysql2')->table('recycle_wastage')->where('id' , $id)->where('status' , 1)->update([ 
                'status' => 0
            ]);
            DB::Connection('mysql2')->table('recycle_wastage_data')->where('master_id' , $id)->where('status' , 1)->update([ 
                'status' => 0
            ]);
            return 1;
        }
        return 2;
    }

    
    public function approveRecycleWastage(Request $request){
        DB::Connection('mysql2')->beginTransaction();
        try { 
            $id = $request->id;
            $recycle_wastage = DB::Connection('mysql2')->table('recycle_wastage')->where('id' , $id)->where('status' , 1)->first();
            $recycle_wastage_data = DB::Connection('mysql2')->table('recycle_wastage_data')->where('master_id' , $recycle_wastage->id)->where('status' , 1)->get();
            
            if ($recycle_wastage->type == 1) {
                $stock = array
                (
                    'main_id' => $recycle_wastage->id,
                    'master_id' => 0,
                    'voucher_no' => 0, // $mr->mr_no,
                    'voucher_date' => $recycle_wastage->recycle_wastage_date,
                    'voucher_type' => 1,
                    'sub_item_id' =>$recycle_wastage->item_id,
                    'qty' => $recycle_wastage->qty,
                    'batch_code' =>  $recycle_wastage->batch_code??0,
                    // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                    'status'=> 1,
                    'warehouse_id' => $recycle_wastage->warehouse_id, //$request->$warehouseIdName[$itemKey],
                    // 'warehouse_id' => 12, //$request->$warehouseIdName[$itemKey],
                    'username'=>Auth::user()->username,
                    'created_date'=>date('Y-m-d'),
                    'created_date'=>date('Y-m-d'),
                    'opening'=>0,
                ); 
                DB::Connection('mysql2')->table('stock')->insert($stock);
    
                
            }
            elseif($recycle_wastage->type == 2){
                 
                foreach ($recycle_wastage_data as $key => $value) {
                    $item_id = $value->item_id;
                    $warehouse_id = $value->warehouse_id;
                    $batch_code = $value->batch_code;
                    $stock =   CommonHelper::in_stock_edit($item_id, $warehouse_id,$batch_code);
                    if ($stock == 0 ) {
                        DB::rollBack();
                        $item_name = CommonHelper::get_item_name($item_id);
                        return response()->json(['msg' => $item_name.' Item Not In stock']);
                    }
                    $stock = array
                        (
                            'main_id' => $value->id,
                            'master_id' => $value->master_id,
                            'voucher_no' => 0, // $mr->mr_no,
                            // 'voucher_date' => $request->input('recycle_date'),
                            'voucher_type' => 3,
                            'sub_item_id' =>$value->item_id,
                            'qty' => $value->qty,
                            'batch_code' => $value->batch_code??0,
                            // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                            'status'=> 1,
                            'warehouse_id' => $value->warehouse_id, //$request->$warehouseIdName[$itemKey],
                            'username'=>Auth::user()->username,
                            'created_date'=>date('Y-m-d'),
                            'created_date'=>date('Y-m-d'),
                            'opening'=>0,
                        );
                        
                    DB::Connection('mysql2')->table('stock')->insert($stock);
                }

                $stock = array
                (
                    'main_id' => $recycle_wastage->id,
                    'master_id' => 0,
                    'voucher_no' => 0, // $mr->mr_no,
                    'voucher_date' => $recycle_wastage->recycle_wastage_date,
                    'voucher_type' => 1,
                    'sub_item_id' =>$recycle_wastage->item_id,
                    'qty' => $recycle_wastage->qty,
                    'batch_code' =>  $recycle_wastage->batch_code??0,
                    // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                    'status'=> 1,
                    'warehouse_id' => $recycle_wastage->warehouse_id, //$request->$warehouseIdName[$itemKey],
                    // 'warehouse_id' => 12, //$request->$warehouseIdName[$itemKey],
                    'username'=>Auth::user()->username,
                    'created_date'=>date('Y-m-d'),
                    'created_date'=>date('Y-m-d'),
                    'opening'=>0,
                ); 
                DB::Connection('mysql2')->table('stock')->insert($stock);
            }

            $data['approval_status'] = 2;
            $data['approval_username'] = Auth::user()->name;
            DB::Connection('mysql2')->table('recycle_wastage')->where('id' , $id)->where('status' , 1)->update($data);
            
            DB::Connection('mysql2')->commit();
            
            return 1;
            
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            $th->getCode();
        }
    }

    public function storeWaistageAndRecycle(Request $request){
        // dd($request);
        DB::Connection('mysql2')->beginTransaction();
        try {
            if ($request->input('type') == 1) {
                # code...
                $InsertDetail['item_id'] = $request->input('output_item_id');
                $InsertDetail['qty'] = $request->input('output_qty');
                $InsertDetail['warehouse_id'] = $request->input('output_warehouse_id');
                $InsertDetail['batch_code'] = $request->input('output_batch_code')??0;
                $InsertDetail['recycle_wastage_date'] = $request->input('wastage_date');
                $InsertDetail['username'] = Auth::user()->name;
                $InsertDetail['date'] =  date('Y-m-d');
                $InsertDetail['status'] = 1;
                $InsertDetail['type'] = 1;
                $master_id = DB::Connection('mysql2')->table('recycle_wastage')->InsertGetId($InsertDetail);
                
                $item_id = $request->input('item_id');
    
                foreach ($item_id as $index => $row1) {
                    $data['master_id'] = $master_id;
                    $data['item_id'] = $request->item_id[$index];
                    $data['qty'] = $request->qty[$index];
                    $data['ppc'] = $request->ppc[$index];
    
                    DB::Connection('mysql2')->table('recycle_wastage_data')->InsertGetId($data);
                }
    
                // $stock = array
                //     (
                //         'main_id' => $master_id,
                //         'master_id' => 0,
                //         'voucher_no' => 0, // $mr->mr_no,
                //         'voucher_date' => $request->input('wastage_date'),
                //         'voucher_type' => 1,
                //         'sub_item_id' =>$request->input('output_item_id'),
                //         'qty' => $request->input('output_qty'),
                //         'batch_code' =>  $request->input('output_batch_code')??0,
                //         // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                //         'status'=> 1,
                //         'warehouse_id' => $request->input('output_warehouse_id'), //$request->$warehouseIdName[$itemKey],
                //         // 'warehouse_id' => 12, //$request->$warehouseIdName[$itemKey],
                //         'username'=>Auth::user()->username,
                //         'created_date'=>date('Y-m-d'),
                //         'created_date'=>date('Y-m-d'),
                //         'opening'=>0,
                //     );
    
    
                // DB::Connection('mysql2')->table('stock')->insert($stock);
            }
            elseif($request->input('type') == 2){
                // dd($request);
                $InsertDetail['item_id'] = $request->input('recycle_output_item_id');
                $InsertDetail['qty'] = $request->input('recycle_output_qty'); 
                $InsertDetail['warehouse_id'] = $request->input('recycle_output_warehouse_id');
                $InsertDetail['batch_code'] = $request->input('recycle_output_batch_code')??0;
                $InsertDetail['recycle_wastage_date'] = $request->input('recycle_date');
                $InsertDetail['username'] = Auth::user()->name;
                $InsertDetail['date'] =  date('Y-m-d');
                $InsertDetail['status'] = 1; 
                $InsertDetail['type'] = 2;
                $master_id = DB::Connection('mysql2')->table('recycle_wastage')->InsertGetId($InsertDetail);
                
                $item_id = $request->input('recycle_item_id');

                foreach ($item_id as $index => $row1) {
                    $data['master_id'] = $master_id;
                    $data['item_type'] = $request->item_type[$index];
                    $data['item_id'] = $request->recycle_item_id[$index];
                    $data['warehouse_id'] = $request->warehouse_id[$index];
                    $data['batch_code'] = $request->batch_code[$index]??0;
                    $data['qty'] = $request->recycle_qty[$index];  
    
                    $data_id = DB::Connection('mysql2')->table('recycle_wastage_data')->InsertGetId($data);

                    // $stock = array
                    //     (
                    //         'main_id' => $data_id,
                    //         'master_id' => $master_id,
                    //         'voucher_no' => 0, // $mr->mr_no,
                    //         // 'voucher_date' => $request->input('recycle_date'),
                    //         'voucher_type' => 3,
                    //         'sub_item_id' =>$request->recycle_item_id[$index],
                    //         'qty' => $request->recycle_qty[$index],
                    //         'batch_code' => $request->batch_code[$index]??0,
                    //         // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                    //         'status'=> 1,
                    //         'warehouse_id' => $request->warehouse_id[$index], //$request->$warehouseIdName[$itemKey],
                    //         'username'=>Auth::user()->username,
                    //         'created_date'=>date('Y-m-d'),
                    //         'created_date'=>date('Y-m-d'),
                    //         'opening'=>0,
                    //     );
                        
                    // DB::Connection('mysql2')->table('stock')->insert($stock);

                }

                // $stock = array
                //     (
                //         'main_id' => $master_id,
                //         'master_id' => 0,
                //         'voucher_no' => 0, // $mr->mr_no,
                //         'voucher_date' => $request->input('recycle_date'),
                //         'voucher_type' => 1,
                //         'sub_item_id' =>$request->input('recycle_output_item_id'),
                //         'qty' => $request->input('recycle_output_qty'),
                //         'batch_code' => $request->input('recycle_output_batch_code')??0,
                //         // 'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                //         'status'=> 1,
                //         'warehouse_id' => $request->input('recycle_output_warehouse_id'),
                //         // 'warehouse_id' => 12, //$request->$warehouseIdName[$itemKey],
                //         'username'=>Auth::user()->username,
                //         'created_date'=>date('Y-m-d'),
                //         'created_date'=>date('Y-m-d'),
                //         'opening'=>0,
                //     );
                    
                // DB::Connection('mysql2')->table('stock')->insert($stock);
    
            }

            DB::Connection('mysql2')->commit();
        }
        catch (Exception $ex) {

            DB::rollBack();
                dd($ex);
            $ex->getCode();
        }

        return redirect('selling/listWaistageAndRecycle');
        // return view('selling.production.createWaistageAndRecycle');
    }

    public function updateWaistageAndRecycle(Request $request){
        // dd($request);
        DB::Connection('mysql2')->beginTransaction();
        try {
            if ($request->input('type') == 1) {
                # code...
                $InsertDetail['item_id'] = $request->input('output_item_id');
                $InsertDetail['qty'] = $request->input('output_qty');
                $InsertDetail['warehouse_id'] = $request->input('output_warehouse_id');
                $InsertDetail['batch_code'] = $request->input('output_batch_code')??0;
                $InsertDetail['recycle_wastage_date'] = $request->input('wastage_date');
                $InsertDetail['username'] = Auth::user()->name;
                $InsertDetail['date'] =  date('Y-m-d');
                $InsertDetail['status'] = 1;
                // $InsertDetail['type'] = 1;
                $master_id = $request->input('id');
                DB::Connection('mysql2')->table('recycle_wastage')->where('id' , $master_id)->update($InsertDetail);
                DB::Connection('mysql2')->table('recycle_wastage_data')->where('master_id' , $master_id)->delete();
                $item_id = $request->input('item_id');
    
                foreach ($item_id as $index => $row1) {
                    $data['master_id'] = $master_id;
                    $data['item_id'] = $request->item_id[$index];
                    $data['qty'] = $request->qty[$index];
                    $data['ppc'] = $request->ppc[$index];
    
                    DB::Connection('mysql2')->table('recycle_wastage_data')->InsertGetId($data);
                }
    
                 
            }
            elseif($request->input('type') == 2){
                // dd($request);
                $InsertDetail['item_id'] = $request->input('recycle_output_item_id');
                $InsertDetail['qty'] = $request->input('recycle_output_qty'); 
                $InsertDetail['warehouse_id'] = $request->input('recycle_output_warehouse_id');
                $InsertDetail['batch_code'] = $request->input('recycle_output_batch_code')??0;
                $InsertDetail['recycle_wastage_date'] = $request->input('recycle_date');
                $InsertDetail['username'] = Auth::user()->name;
                $InsertDetail['date'] =  date('Y-m-d');
                $InsertDetail['status'] = 1; 
                $master_id = $request->input('id');
                DB::Connection('mysql2')->table('recycle_wastage')->where('id' , $master_id)->update($InsertDetail);
                DB::Connection('mysql2')->table('recycle_wastage_data')->where('master_id' , $master_id)->delete();
                
                $item_id = $request->input('recycle_item_id');

                foreach ($item_id as $index => $row1) {
                    $data['master_id'] = $master_id;
                    $data['item_type'] = $request->item_type[$index];
                    $data['item_id'] = $request->recycle_item_id[$index];
                    $data['warehouse_id'] = $request->warehouse_id[$index];
                    $data['batch_code'] = $request->batch_code[$index]??0;
                    $data['qty'] = $request->recycle_qty[$index];  
    
                    $data_id = DB::Connection('mysql2')->table('recycle_wastage_data')->InsertGetId($data);

                     

                }

                 
    
            }

            DB::Connection('mysql2')->commit();
        }
        catch (Exception $ex) {

            DB::rollBack();
                dd($ex);
            $ex->getCode();
        }

        return redirect('selling/listWaistageAndRecycle');
        // return view('selling.production.createWaistageAndRecycle');
    }


    public function createProductionOrderForm()
    {
        $pr_no = CommonHelper::generateProductionNumber();
        $sub_item = DB::Connection('mysql2')->table('category as c')
        ->leftJoin('sub_category as sc', 'c.id', '=', 'sc.category_id')
        ->join('subitem as s', 'c.id', '=', 's.main_ic_id')
        ->join(env('DB_DATABASE').'.uom as u', 's.uom', '=', 'u.id')
        ->where('sc.status', '=', 1)
        ->where('c.status', '=', 1)
        ->where('s.status', '=', 1)
        ->where('u.status', '=', 1)
        ->where('s.main_ic_id', '=', 8)
        ->select('s.id', 's.sub_ic','s.uom','s.item_code','u.uom_name','s.hs_code_id')
        ->groupBy('s.item_code')
        ->orderBy('s.id')
        ->get();

        $color = DB::Connection('mysql2')->table('subitem')->where('status', '=', 1)
        ->select('color')
        ->groupBy('color')
        ->get();
        // dd($sub_item);
        return view('FarazPackagesProduction.createProductionOrderForm', compact('pr_no','sub_item','color'));
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
        if(!empty($approval_status)) $query_string_second_part[] = "  AND approval_status ='$approval_status'";
        $query_string_second_part[] = " AND request_date BETWEEN '$from_date' AND '$to_date'";
        $query_string_second_part[] = " AND status = 1";
        $query_string_First_Part = "SELECT * FROM production_request  WHERE ";
        $query_string_third_part = ' ORDER BY id DESC';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $production_request = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        return view('FarazPackagesProduction.viewProductionOrderListDetail', compact('production_request','m'));
    }

    public function viewProductionOrderDetail(Request $request)
    {
        $production_request = DB::connection('mysql2')->table('production_request')->where('id', $request->id)->first();
        $production_request_data = DB::connection('mysql2')->table('production_request_data')->where('master_id', $request->id)->get();
        return view('FarazPackagesProduction.viewProductionOrderDetail', compact('production_request','production_request_data'));
    }

    public function editProductionOrderForm($id, Request $request)
    {
        $sub_item = DB::Connection('mysql2')->table('category as c')
        ->leftJoin('sub_category as sc', 'c.id', '=', 'sc.category_id')
        ->join('subitem as s', 'c.id', '=', 's.main_ic_id')
        ->join(env('DB_DATABASE').'.uom as u', 's.uom', '=', 'u.id')
        ->where('sc.status', '=', 1)
        ->where('c.status', '=', 1)
        ->where('s.status', '=', 1)
        ->where('u.status', '=', 1)
        ->where('s.main_ic_id', '=', 8)
        ->select('s.id', 's.sub_ic','s.uom','s.item_code','u.uom_name','s.hs_code_id')
        ->groupBy('s.item_code')
        ->orderBy('s.id')
        ->get();

        $color = DB::Connection('mysql2')->table('subitem')->where('status', '=', 1)
        ->select('color')
        ->groupBy('color')
        ->get();

        $production_request = DB::connection('mysql2')->table('production_request')->where('id', $id)->first();
        $production_request_data = DB::connection('mysql2')->table('production_request_data')->where('master_id', $id)->get();
        return view('FarazPackagesProduction.editProductionOrderForm', compact('sub_item','color','production_request','production_request_data'));
    }
    
}
