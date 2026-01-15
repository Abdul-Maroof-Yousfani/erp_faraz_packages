<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\ProductionWorkOrder;
use App\Models\ProductionWorkOrderData;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
   public function createWorkOrder()
   {
      $sale_orders =  DB::connection('mysql2')->table('sales_order')->where('status',1)->get();
        return view('selling.workorder.createWorkOrder',compact('sale_orders'));
      }
      public function workOrderList(Request $request)
      {
         $work_order = ProductionWorkOrder::where('status',1)->get();
         if($request->ajax())
         {
            return view('selling.workorder.workOrderListAjax',compact('work_order'));
         }
         return view('selling.workorder.workOrderList',compact('work_order'));
         
      }
   public function workOrderStore(Request $request)
   {
      DB::Connection('mysql2')->beginTransaction();
      try {

        $wo_no = CommonHelper::generateUniquePosNo('production_work_order','work_no','WO');
        $work_order =  new ProductionWorkOrder;
        $work_order->sale_order_id =  $request->so_id;
        $work_order->work_no =$wo_no;
        $work_order->category_id =$request->category_id;
        $work_order->username = Auth::user()->name;
        $work_order->date = $request->work_order_date;
        $work_order->status = 1;
        $work_order->save();
// dd($request->all());
        foreach($request->item_id as $key=>$value)
        {
         $work_order_data =  new ProductionWorkOrderData;
         $work_order_data->master_id = $work_order->id;
         $work_order_data->sale_order_data_id = $request->sale_order_data_id[$key];
         $work_order_data->item_id = $request->item_id[$key];
         $work_order_data->order_qty = $request->sale_order_qty[$key];
         $work_order_data->printing = $request->printing[$key];
         $work_order_data->special_instruction = $request->special_instruction[$key];
         $work_order_data->delivery_date =  $request->delivery_date[$key];      // Delivery date 
         $work_order_data->diameter =  $request->diameter[$key];      // Delivery date 
         $work_order_data->status =  1;     
         $work_order_data->date = date('y-m-d');       
         $work_order_data->username =   Auth::user()->name;     
         $work_order_data->save();

         $so_data =  Sales_Order_Data::find($request->sale_order_data_id[$key]);
         $so_data->production_status =  1;
         $so_data->save();
        }

           DB::Connection('mysql2')->commit();
    }
    catch (Exception $e) {
        DB::Connection('mysql2')->rollBack();
        return redirect()->route('createWorkOrder')->with('error', $e->getMessage());
    }
        return redirect()->route('createWorkOrder')->with('dataInsert','Work Order create SuccessFully');
   }







   public function listRawMaterial(Request $request)
   {
      return view('selling.workorder.listRawMaterial');
   }

   public function viewRawMaterial(Request $request)
   {
      return view('selling.workorder.viewRawMaterial');
   }
   public function createPurcahseRawMaterial(Request $request)
   {
      return view('selling.workorder.createPurcahseRawMaterial');
   }
   public function createPurcahseRawMaterialForm(Request $request)
   {
      return view('selling.workorder.createPurcahseRawMaterialForm');
   }
   public function createRawMaterialIssuance(Request $request)
   {
      return view('selling.workorder.createRawMaterialIssuance');
   }
}
