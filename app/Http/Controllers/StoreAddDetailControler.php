<?php

namespace App\Http\Controllers;

use App\Helpers\ReuseableCode;

use App\Http\Requests;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;
use App\Models\Issuance;
use App\Models\Subitem;

use App\Models\Transactions;
use App\Models\IssuanceData;
use App\Helpers\NotificationHelper;

use Mail;
use PDF;


class StoreAddDetailControler extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

     public function addStoreChallanDetail(){
        //dd('in', Input::all());
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
            $str = DB::selectOne("select max(convert(substr(`store_challan_no`,3,length(substr(`store_challan_no`,3))-4),signed integer)) reg from `store_challan` where substr(`store_challan_no`,-4,2) = ".date('m')." and substr(`store_challan_no`,-2,2) = ".date('y')."")->reg;
            $storeChallanNo = 'sc'.($str+1).date('my');
            $slip_no = Input::get('slip_no');
            $store_challan_date = Input::get('store_challan_date');
            $departmentId = Input::get('departmentId');
            $pageType = Input::get('pageType');
            $parentCode = Input::get('parentCode');
            $main_description = Input::get('main_description');
            $warehouse_from_id = Input::get('warehouse_from_id');
            $warehouse_to_id = Input::get('warehouse_to_id');

            $data1['slip_no'] = $slip_no ?? '0';
            $data1['store_challan_no'] = $storeChallanNo;
            $data1['material_request_no'] = Input::get('mrNo');
            $data1['material_request_date'] = Input::get('mrDate');
            $data1['warehouse_from_id'] = $warehouse_from_id;
            $data1['warehouse_to_id'] = $warehouse_to_id;
            $data1['store_challan_date'] = $store_challan_date;
            $data1['sub_department_id'] = 1;
            $data1['description'] = $main_description;
            $data1['username'] 		 	= Auth::user()->name;
            $data1['approve_username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['store_challan_status'] = 1;

            $store_challan_id = DB::table('store_challan')->insertGetId($data1);

            $seletedStoreChallanRow = Input::get('storeChallanData');
            // dd($seletedStoreChallanRow);
            foreach ($seletedStoreChallanRow as $row) {
                // $demandNo = Input::get('demandNo_' . $row . '');
                // $demandDate = Input::get('demandDate_' . $row . '');
                // $categoryId = Input::get('categoryId_' . $row . '');
                $subItemId = Input::get('sub_item_id_' . $row . '');
                $issue_qty = Input::get('store_challan_qty_' . $row . '');
                $demandAndRemainingQty = Input::get('remaining_store_challan_qty_' . $row . '');

                $categoryId = Subitem::find($subItemId)->main_ic_id ?? 0;

                $data2['material_request_data_id'] = $row;
                $data2['store_challan_no'] = $storeChallanNo;
                $data2['store_challan_date'] = $store_challan_date;
                $data2['demand_no'] = Input::get('mrNo');
                $data2['demand_date'] = Input::get('mrDate');
                $data2['category_id'] = $categoryId;
                $data2['sub_item_id'] = $subItemId;
                $data2['issue_qty'] = $issue_qty;
                $data2['username'] = Auth::user()->name;
                $data2['approve_username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                $data2['store_challan_status'] = 1;

                $store_challan_data_id = DB::table('store_challan_data')->insertGetId($data2);
                if ($issue_qty == $demandAndRemainingQty) {
                    DB::table('material_request_datas')
                    ->where('sub_item_id', $subItemId)
                    ->where('material_request_no', Input::get('mrNo'))
                    ->update(['store_challan_status' => "2"]);
                }
                // $data3['sc_no'] = $storeChallanNo;
                // $data3['sc_date'] = $store_challan_date;
                // $data3['demand_no'] = Input::get('mrNo');
                // $data3['demand_date'] = Input::get('mrDate');
                // $data3['main_ic_id'] = $categoryId;
                // $data3['sub_ic_id'] = $subItemId;
                // $data3['qty'] = $issue_qty;
                // $data3['value'] = 0;
                // $data3['username'] = Auth::user()->name;
                // $data3['date'] = date("Y-m-d");
                // $data3['time'] = date("H:i:s");
                // $data3['action'] = 2;
                // $data3['status'] = 1;
                // $data3['company_id'] = $m;
                // DB::table('stock')->insert($data3);
                $data3['main_id']       = $store_challan_id;
                $data3['master_id']     = $store_challan_data_id;
                $data3['voucher_no']    = $storeChallanNo;
                $data3['voucher_date']  = $store_challan_date;
                $data3['voucher_type']  = 7;
                // $data3['packing_qty']   = Input::get('packing_qty_' . $row . '');
                // $data3['packing_uom']   = Input::get('packing_uom_' . $row . '');
                $data3['batch_code']   = Input::get('batch_code_' . $row . '');
                $data3['supplier_id']   = Input::get('supplier_id_' . $row . '');
                $data3['sub_item_id']   = $subItemId;
                $data3['warehouse_id']   = $warehouse_from_id;
                $data3['warehouse_id_from']   = $warehouse_from_id;
                $data3['warehouse_id_to']   = $warehouse_to_id;
                $data3['qty']           = $issue_qty;
                $data3['status']        = 1;
                $data3['created_date']  = date("Y-m-d");
                $data3['username']      = Auth::user()->name;
                DB::table('stock')->insert($data3);

                // $data3['main_id']       = $store_challan_id;
                // $data3['master_id']     = $store_challan_data_id;
                // $data3['voucher_no']    = $storeChallanNo;
                // $data3['voucher_date']  = $store_challan_date;
                // $data3['voucher_type']  = 4;
                // $data3['packing_qty']   = Input::get('packing_qty_' . $row . '');
                // $data3['packing_uom']   = Input::get('packing_uom_' . $row . '');
                // $data3['batch_code']   = Input::get('batch_code_' . $row . '');
                // $data3['supplier_id']   = Input::get('supplier_id_' . $row . '');
                // $data3['sub_item_id']   = $subItemId;
                // $data3['warehouse_id']   = $warehouse_to_id;
                // $data3['warehouse_id_from']   = $warehouse_to_id;
                // $data3['warehouse_id_to']   = $warehouse_from_id;
                // $data3['qty']           = $issue_qty;
                // $data3['status']        = 1;
                // $data3['created_date']  = date("Y-m-d");
                // $data3['username']      = Auth::user()->name;
                // DB::table('stock')->insert($data3);
                
            }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('store/viewStoreChallanList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$m.'#SFR');
    }

    public function editStoreChallanDetail(Request $request){

        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);

            $storeChallanNo = Input::get('store_challan_no');
            $slip_no = Input::get('slip_no');
            $store_challan_date = Input::get('store_challan_date');
            $departmentId = Input::get('departmentId');
            $pageType = Input::get('pageType');
            $parentCode = Input::get('parentCode');
            $main_description = Input::get('main_description');
            $warehouse_from_id = Input::get('warehouse_from_id');
            $warehouse_to_id = Input::get('warehouse_to_id');

            $data1['slip_no'] = $slip_no ?? '0';
            $data1['material_request_no'] = Input::get('mrNo');
            $data1['material_request_date'] = Input::get('mrDate');
            $data1['warehouse_from_id'] = $warehouse_from_id;
            $data1['warehouse_to_id'] = $warehouse_to_id;
            $data1['store_challan_date'] = $store_challan_date;
            $data1['sub_department_id'] = 1;
            $data1['description'] = $main_description;
            $data1['username'] 		 	= Auth::user()->name;
            $data1['approve_username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['store_challan_status'] = 1;

            $store_challan_id = DB::table('store_challan')
                ->where([
                    ['store_challan_no', $storeChallanNo],
                    ['store_challan_date', $store_challan_date],
                ])
                ->update($data1);

            if (!$store_challan_id) {
                Session::flash('dataError', 'Sorry record not found.');
                return Redirect::to('store/viewStoreChallanList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $m . '#SFR');
            }

            $store_challan_data_update_result = DB::table('store_challan_data')
                ->where([
                    ['store_challan_no', $storeChallanNo],
                    ['store_challan_date', $store_challan_date],
                ])
                ->update([
                    'status' => 0,
                ]);

            $stock_update_result = DB::table('stock')
                ->where([
                    ['voucher_no' , $storeChallanNo ] ,
                    ['voucher_date' , $store_challan_date ] ,
                    ['status',1]
                ])
                ->update([
                    'status' => 0,
                ]);
                        

            $seletedStoreChallanRow = Input::get('storeChallanData');
            // dd($seletedStoreChallanRow);
            foreach ($seletedStoreChallanRow as $row) {
                // $demandNo = Input::get('demandNo_' . $row . '');
                // $demandDate = Input::get('demandDate_' . $row . '');
                // $categoryId = Input::get('categoryId_' . $row . '');
                $subItemId = Input::get('sub_item_id_' . $row . '');
                $issue_qty = Input::get('store_challan_qty_' . $row . '');
                $demandAndRemainingQty = Input::get('remaining_store_challan_qty_' . $row . '');

                $categoryId = Subitem::find($subItemId)->main_ic_id ?? 0;

                $data2['material_request_data_id'] = $row;
                $data2['store_challan_no'] = $storeChallanNo;
                $data2['store_challan_date'] = $store_challan_date;
                $data2['demand_no'] = Input::get('mrNo');
                $data2['demand_date'] = Input::get('mrDate');
                $data2['category_id'] = $categoryId;
                $data2['sub_item_id'] = $subItemId;
                $data2['issue_qty'] = $issue_qty;
                $data2['username'] = Auth::user()->name;
                $data2['approve_username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                $data2['store_challan_status'] = 1;

                $store_challan_data_id = DB::table('store_challan_data')->insertGetId($data2);
                if ($issue_qty == $demandAndRemainingQty) {
                    DB::table('material_request_datas')
                    ->where('sub_item_id', $subItemId)
                    ->where('material_request_no', Input::get('mrNo'))
                    ->update(['store_challan_status' => "2"]);
                }
                // $data3['sc_no'] = $storeChallanNo;
                // $data3['sc_date'] = $store_challan_date;
                // $data3['demand_no'] = Input::get('mrNo');
                // $data3['demand_date'] = Input::get('mrDate');
                // $data3['main_ic_id'] = $categoryId;
                // $data3['sub_ic_id'] = $subItemId;
                // $data3['qty'] = $issue_qty;
                // $data3['value'] = 0;
                // $data3['username'] = Auth::user()->name;
                // $data3['date'] = date("Y-m-d");
                // $data3['time'] = date("H:i:s");
                // $data3['action'] = 2;
                // $data3['status'] = 1;
                // $data3['company_id'] = $m;
                // DB::table('stock')->insert($data3);
                $data3['main_id']       = $store_challan_id;
                $data3['master_id']     = $store_challan_data_id;
                $data3['voucher_no']    = $storeChallanNo;
                $data3['voucher_date']  = $store_challan_date;
                $data3['voucher_type']  = 7;
                // $data3['packing_qty']   = Input::get('packing_qty_' . $row . '');
                // $data3['packing_uom']   = Input::get('packing_uom_' . $row . '');
                $data3['batch_code']   = Input::get('batch_code_' . $row . '');
                $data3['supplier_id']   = Input::get('supplier_id_' . $row . '');
                $data3['sub_item_id']   = $subItemId;
                $data3['warehouse_id']   = $warehouse_from_id;
                $data3['warehouse_id_from']   = $warehouse_from_id;
                $data3['warehouse_id_to']   = $warehouse_to_id;
                $data3['qty']           = $issue_qty;
                $data3['status']        = 1;
                $data3['created_date']  = date("Y-m-d");
                $data3['username']      = Auth::user()->name;
                DB::table('stock')->insert($data3);

                // $data3['main_id']       = $store_challan_id;
                // $data3['master_id']     = $store_challan_data_id;
                // $data3['voucher_no']    = $storeChallanNo;
                // $data3['voucher_date']  = $store_challan_date;
                // $data3['voucher_type']  = 4;
                // $data3['packing_qty']   = Input::get('packing_qty_' . $row . '');
                // $data3['packing_uom']   = Input::get('packing_uom_' . $row . '');
                // $data3['batch_code']   = Input::get('batch_code_' . $row . '');
                // $data3['supplier_id']   = Input::get('supplier_id_' . $row . '');
                // $data3['sub_item_id']   = $subItemId;
                // $data3['warehouse_id']   = $warehouse_to_id;
                // $data3['warehouse_id_from']   = $warehouse_to_id;
                // $data3['warehouse_id_to']   = $warehouse_from_id;
                // $data3['qty']           = $issue_qty;
                // $data3['status']        = 1;
                // $data3['created_date']  = date("Y-m-d");
                // $data3['username']      = Auth::user()->name;
                // DB::table('stock')->insert($data3);
                
            }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully updated.');
        return Redirect::to('store/viewStoreChallanList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$m.'#SFR');

    }
    
    public function deleteStoreChallanDetail(Request $request){

        $store_challan_id = DB::connection('mysql2')->table('store_challan')->where([
            ['material_request_no', $request->material_request_no],
            ['material_request_date', $request->material_request_date],
            ['status', 1]
        ])->first();
    
        if ($store_challan_id) {
            $store_challan_data_id = DB::connection('mysql2')->table('store_challan_data')
                ->where([
                    ['store_challan_no', $store_challan_id->store_challan_no],
                    ['status', 1]
                ])
                ->update([
                    'status' => 0
                ]);
    
            $stock = DB::connection('mysql2')->table('stock')
                ->where([
                    ['voucher_no', $store_challan_id->store_challan_no],
                    ['voucher_date', $store_challan_id->store_challan_date],
                    ['status', 1]
                ])
                ->update([
                    'status' => 0
                ]);
    
            DB::connection('mysql2')->table('store_challan')
                ->where([
                    ['material_request_no', $request->material_request_no],
                    ['material_request_date', $request->material_request_date],
                    ['status', 1]
                ])
                ->update([
                    'status' => 0
                ]);
    
            return response()->json(['status' => 'success', 'message' => 'Record updated successfully']);
        } else {
            // Return an error response with a message
            return response()->json(['status' => 'error', 'message' => 'Record not found']);
        }

    }

    public function addPurchaseRequestDetail(Request $request){
        DB::Connection('mysql2')->beginTransaction();
        try {
            $m = $_GET['m'];
            CommonHelper::companyDatabaseConnection($_GET['m']);

            $po_date= Input::get('po_date');
            $po_type=Input::get('po_type');

            $year= substr($po_date,2,2);
            $month= substr($po_date,5,2);

            $purchaseRequestNo=CommonHelper::get_unique_po_no_with_status($po_type);
            $purchase_request_date = Input::get('po_date');
            $departmentId = Input::get('dept_id');
            $slip_no = Input::get('slip_no');
            $term_of_del = Input::get('term_of_del');

            $terms_of_paym = Input::get('model_terms_of_payment');
            $destination = Input::get('destination');
            $supplier_id = Input::get('supplier_id');
            $due_date = Input::get('due_date');
            $supplier_id = explode('@#',$supplier_id);

            $currency_id = Input::get('curren');
            $currency_id=explode(',',$currency_id);
            $currency_id=$currency_id[0];
            $currency_rate = Input::get('currency_rate');
            $trn = Input::get('trn');
            $builty_no = Input::get('builty_no');
            $remarks = Input::get('remarks');
            $main_description = Input::get('main_description');
            $sales_tax = Input::get('sales_taxx');
            $sales_tax_amount = Input::get('sales_amount_td');
            $sales_tax_amount=str_replace(",","",$sales_tax_amount);
            $total_amount = str_replace(',', '', Input::get('total_amount'));
            $amount_in_words = Input::get('rupeess');
            $pageType = Input::get('pageType');
            $parentCode = Input::get('parentCode');
            $s_order_no = Input::get('s_order_no');

            $data1['purchase_request_no'] = $purchaseRequestNo;
            $data1['purchase_request_date'] = $purchase_request_date;
            $data1['sub_department_id'] = $departmentId;
            $data1['slip_no'] = $slip_no;
            $data1['term_of_del'] = $term_of_del;
            $data1['po_type'] =  Input::get('po_type');
            $data1['terms_of_paym'] = $terms_of_paym;
            $data1['destination'] = $destination;
            $data1['supplier_id'] = $supplier_id[0];
            $data1['due_date'] = $due_date;

            $data1['currency_id'] = $currency_id;
            $data1['currency_rate'] = Input::get('currency_rate');
            $data1['trn'] = $trn;
            $data1['builty_no'] = $builty_no;
            $data1['remarks'] = $remarks;
            $data1['description'] = $main_description;
            $data1['sales_tax'] = $sales_tax;
            $data1['sales_tax_amount'] 		 	= $sales_tax_amount;
            $data1['total_amount'] = (float) $total_amount;
            $data1['amount_in_words'] = $amount_in_words;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['purchase_request_status'] = 1;
            $data1['s_order_no'] = $s_order_no;
            $data1['p_type'] = Input::get('p_type_id');
            
            // Add no_of_days if payment term is Credit (3)
            if($terms_of_paym == '3') {
                $data1['no_of_days'] = Input::get('no_of_days');
            }

            $master_id=DB::table('purchase_request')->insertGetId($data1);

            $seletedPurchaseRequestRow = Input::get('seletedPurchaseRequestRow');
            $TotAmount = 0;
            
            $checkedRows = $request->input('checked_rows', []);
            foreach ($checkedRows as $row) {
                // Fetch the inputs for the checked row
                $demandNo = $request->input('demandNo_' . $row);
                $demandDate = $request->input('demandDate_' . $row);
                $subItemId = $request->input('subItemId_' . $row);
                $purchase_request_qty = $request->input('purchase_request_qty_' . $row);
                $purchase_approve_qty = str_replace(",", "", $request->input('purchase_approve_qty_' . $row));
                $description = $request->input('description_' . $row);
                $purchase_request_rate = str_replace(",", "", $request->input('rate_' . $row));
                $discount_percent = str_replace(",", "", $request->input('discount_percent_' . $row));
                $discount_amount = str_replace(",", "", $request->input('discount_amount_' . $row));
                $net_amount = str_replace(",", "", $request->input('after_dis_amountt_' . $row));
                $purchase_request_sub_total = $purchase_approve_qty * $purchase_request_rate;
                $demand_data_id = $request->input('demand_data_id' . $row);
            
                // Prepare the data for insertion
                $data2 = [
                    'purchase_request_no' => $purchaseRequestNo,
                    'purchase_request_date' => $purchase_request_date,
                    'demand_no' => $demandNo,
                    'master_id' => $master_id,
                    'demand_date' => $demandDate,
                    'sub_item_id' => $subItemId,
                    'purchase_request_qty' => $purchase_request_qty,
                    'description' => $description,
                    'purchase_approve_qty' => $purchase_approve_qty,
                    'rate' => $purchase_request_rate,
                    'sub_total' => $purchase_request_sub_total,
                    'demand_data_id' => $demand_data_id,
                    'username' => Auth::user()->name,
                    'date' => date("Y-m-d"),
                    'time' => date("H:i:s"),
                    'purchase_request_status' => 1,
                    'discount_percent' => $discount_percent,
                    'discount_amount' => $discount_amount,
                    'net_amount' => $net_amount,
                ];
                $TotAmount+=$net_amount;

                DB::table('purchase_request_data')->insert($data2);
                DB::table('demand_data')->where('id', $demand_data_id)->update(['demand_status' => "3"]);

                DB::table('quotation_data')->where('pr_data_id',$demand_data_id)
                ->where('quotation_status',1)->update(['quotation_status' =>'2']);

            }
            // die();
            CommonHelper::reconnectMasterDatabase();
            CommonHelper::inventory_activity($purchaseRequestNo,$purchase_request_date,$TotAmount+$sales_tax_amount,2,'Insert');

            $subject = 'Purchase Order For '.$demandNo;
            NotificationHelper::send_email('Purchase Order','Create',$departmentId,$purchaseRequestNo,$subject,Input::get('p_type_id'));
            DB::Connection('mysql2')->commit();
            
        } catch(\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        Session::flash('dataInsert', 'Purchase Order Successfully Saved.');
        return Redirect::to('store/viewPurchaseRequestList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }


    public function addPurchaseRequestSaleDetail(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $str = DB::selectOne("select max(convert(substr(`purchase_request_no`,3,length(substr(`purchase_request_no`,3))-4),signed integer)) reg from `purchase_request` where substr(`purchase_request_no`,-4,2) = ".date('m')." and substr(`purchase_request_no`,-2,2) = ".date('y')."")->reg;
        $purchaseRequestNo = 'pr'.($str+1).date('my');
        $slip_no = Input::get('slip_no');
        $purchase_request_date = Input::get('purchase_request_date');
        $departmentId = Input::get('departmentId');
        $supplier_id = Input::get('supplier_id');
        $pageType = Input::get('pageType');
        $parentCode = Input::get('parentCode');
        $main_description = Input::get('main_description');
        $mainDemandType = Input::get('demandType');

        $data1['slip_no'] = $slip_no;
        $data1['purchase_request_no'] = $purchaseRequestNo;
        $data1['purchase_request_date'] = $purchase_request_date;
        $data1['demand_type'] = $mainDemandType;
        $data1['sub_department_id'] = $departmentId;
        $data1['supplier_id'] = $supplier_id;
        $data1['description'] = $main_description;
        $data1['username'] 		 	= Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['purchase_request_status'] = 1;

        DB::table('purchase_request')->insert($data1);

        $seletedPurchaseRequestSaleRow = Input::get('seletedPurchaseRequestSaleRow');
        foreach ($seletedPurchaseRequestSaleRow as $row) {
            $demandNo = Input::get('demandNo_' . $row . '');
            $demandDate = Input::get('demandDate_' . $row . '');
            $demandType = Input::get('demandType_' . $row . '');
            $demandSendType = Input::get('demandSendType_' . $row . '');
            $categoryId = Input::get('categoryId_' . $row . '');
            $subItemId = Input::get('subItemId_' . $row . '');
            $purchase_request_qty = Input::get('purchase_request_qty_' . $row . '');
            $purchase_request_rate = Input::get('purchase_request_rate_' . $row . '');

            $purchase_request_sub_total = $purchase_request_qty*$purchase_request_rate;

            $data2['purchase_request_no'] = $purchaseRequestNo;
            $data2['purchase_request_date'] = $purchase_request_date;
            $data2['demand_no'] = $demandNo;
            $data2['demand_date'] = $demandDate;
            $data2['demand_type'] = $demandType;
            $data2['demand_send_type'] = $demandSendType;
            $data2['category_id'] = $categoryId;
            $data2['sub_item_id'] = $subItemId;
            $data2['purchase_request_qty'] = $purchase_request_qty;
            $data2['rate'] = $purchase_request_rate;
            $data2['sub_total'] = $purchase_request_sub_total;
            $data2['username'] = Auth::user()->name;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['purchase_request_status'] = 1;

            DB::table('purchase_request_data')->insert($data2);
        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('store/viewPurchaseRequestSaleList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }


    public function addStoreChallanReturnDetail(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $str = DB::selectOne("select max(convert(substr(`store_challan_return_no`,4,length(substr(`store_challan_return_no`,4))-4),signed integer)) reg from `store_challan_return` where substr(`store_challan_return_no`,-4,2) = ".date('m')." and substr(`store_challan_return_no`,-2,2) = ".date('y')."")->reg;
        $storeChallanReturnNo = 'scr'.($str+1).date('my');
        $slip_no = Input::get('slip_no');
        $store_challan_return_date = Input::get('store_challan_return_date');
        $departmentId = Input::get('departmentId');
        $pageType = Input::get('pageType');
        $parentCode = Input::get('parentCode');
        $main_description = Input::get('main_description');

        $data1['slip_no'] = $slip_no;
        $data1['store_challan_return_no'] = $storeChallanReturnNo;
        $data1['store_challan_return_date'] = $store_challan_return_date;
        $data1['sub_department_id'] = $departmentId;
        $data1['description'] = $main_description;
        $data1['username'] 		 	= Auth::user()->name;
        $data1['approve_username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['store_challan_return_status'] = 2;

        DB::table('store_challan_return')->insert($data1);

        $seletedStoreChallanReturnRow = Input::get('seletedStoreChallanReturnRow');
        foreach ($seletedStoreChallanReturnRow as $row) {
            $storeChallanNo = Input::get('storeChallanNo_' . $row . '');
            $storeChallanDate = Input::get('storeChallanDate_' . $row . '');
            $categoryId = Input::get('categoryId_' . $row . '');
            $subItemId = Input::get('subItemId_' . $row . '');
            $return_qty = Input::get('return_qty_' . $row . '');
            $storeChallanIssueQty = Input::get('storeChallanIssueQty_' . $row . '');

            $data2['store_challan_return_no'] = $storeChallanReturnNo;
            $data2['store_challan_return_date'] = $store_challan_return_date;
            $data2['store_challan_no'] = $storeChallanNo;
            $data2['store_challan_date'] = $storeChallanDate;
            $data2['category_id'] = $categoryId;
            $data2['sub_item_id'] = $subItemId;
            $data2['return_qty'] = $return_qty;
            $data2['username'] = Auth::user()->name;
            $data2['approve_username'] = Auth::user()->name;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['store_challan_return_status'] = 2;

            DB::table('store_challan_return_data')->insert($data2);

            $data3['scr_no'] = $storeChallanReturnNo;
            $data3['scr_date'] = $store_challan_return_date;
            $data3['sc_no'] = $storeChallanNo;
            $data3['sc_date'] = $storeChallanDate;
            $data3['main_ic_id'] = $categoryId;
            $data3['sub_ic_id'] = $subItemId;
            $data3['qty'] = $return_qty;
            $data3['value'] = 0;
            $data3['username'] = Auth::user()->name;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['action'] = 4;
            $data3['status'] = 1;
            $data3['company_id'] = $m;
            DB::table('fara')->insert($data3);
        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('store/viewStoreChallanReturnList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    public function Email_Sent()
    {
        $id = $_GET['id'];
        $m = $_GET['m'];
        $email = $_GET['email'];
        $EmailPrintSetting =$_GET['EmailPrintSetting'];

        $data = array('id'=>$id, 'm'=>$m, 'email'=>$email, 'EmailPrintSetting'=>$EmailPrintSetting );
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView('Store.AjaxPages.viewPurchaseRequestVoucherDetail', $data)->setPaper('a4', 'landscape')->setWarnings(false);

        Mail::send('Store.AjaxPages.viewPurchaseRequestVoucherDetail', $data, function($message)use($data,$pdf) {
            $message->to($data['email'], 'Purchase Order')->subject
            ('Purchase Order From Sign Now Pakistan');
            $message->attachData($pdf->output(),"invoice.pdf");
            $message->from('innovative.network93@gmail.com','Sign Now Pakistan');
        });
        echo "Email Sent with attachment. Check your inbox.";

    }

    public function UpdateTableDataSubitem()
    {
        $id = Input::get('id');
        $item_cost_classification_id = Input::get('item_cost_classification_id');
        if($id !=''){
            if($item_cost_classification_id != ''){
                $data['item_cost_classification_id'] = $item_cost_classification_id;
                DB::Connection('mysql2')->table('subitem')
                    ->where('id', $id)
                    ->update($data);
                echo "successfully Updated";
            } else{
                echo "Updated Not successfully ";
            }
        }
    }

    public function insertDirectPurchaseOrder(Request $request){

        $edit_mode= $request->id;
        DB::Connection('mysql2')->beginTransaction();
        try {
            $po_date= Input::get('po_date');
            $po_type=Input::get('po_type');


            $purchase_request=new PurchaseRequest();
            $purchase_request=$purchase_request->SetConnection('mysql2');

            if ($edit_mode!=''):
                $purchase_request=$purchase_request->find($edit_mode);
                $purchaseRequestNo=$request->po_no;
            else:
                $year= substr($po_date,2,2);
                $month= substr($po_date,5,2);
                $purchaseRequestNo=CommonHelper::get_unique_po_no_with_status($po_type);
                endif;

            $purchase_request->purchase_request_no =$purchaseRequestNo;
            $purchase_request->pr_no ='';
            $purchase_request->pr_date ='';
            $purchase_request->purchase_request_date =$request->po_date;

            $purchase_request->agent =$request->agent;
            $purchase_request->commission =$request->commission;


            $purchase_request->po_type =$request->po_type;
            $purchase_request->sub_department_id =$request->sub_department_id_1;

            $supplier=explode('@#',$request->supplier_id);

            $purchase_request->supplier_id =$supplier[0];

            $SalesTaxAccId = 0;
            $SalesTaxAmount= 0;
            $SalesTaxPer = 0;
            if ($request->input('sales_taxx')!=0):
            $salesTaxx = explode('@', $request->input('sales_taxx'));
            if ($salesTaxx[1] != "") {
                $SalesTaxPer = $salesTaxx[0];
                $SalesTaxAccId = $salesTaxx[1];
                $SalesTaxAmount = CommonHelper::check_str_replace($request->input('sales_amount_td'));
                $bolen = true;
            } else {
                $SalesTaxAccId = 0;
                $SalesTaxAmount = 0;
                $SalesTaxPer = 0;
            }
        endif;

            $purchase_request->term_of_del =$request->term_of_del;
            $purchase_request->terms_of_paym =$request->model_terms_of_payment;
            $purchase_request->due_date =$request->due_date;
            $purchase_request->destination =$request->destination;
            $purchase_request->currency_id =$request->curren;
            $purchase_request->currency_rate =$request->currency_rate;
            $purchase_request->sales_tax =$SalesTaxPer;
            $purchase_request->sales_tax_acc_id =$SalesTaxAccId;
            $purchase_request->sales_tax_amount =CommonHelper::check_str_replace($request->sales_amount_td);
            //$SalesTaxAmount = CommonHelper::check_str_replace($request->sales_amount_td);
            $purchase_request->amount_in_words =$request->rupeess;
            $purchase_request->trn =$request->trn;
            $purchase_request->builty_no =$request->builty_no;
            $purchase_request->remarks =$request->Remarks;
            $purchase_request->description =$request->main_description;
            $purchase_request->purchase_request_status =1;
            $purchase_request->status =1;
            $purchase_request->date =date('Y-m-d');
            $purchase_request->username =Auth::user()->name;
            $purchase_request->type =2;
            $purchase_request->save();


            if ($edit_mode!=''):
                $master_id=$edit_mode;
            else:
                $master_id=$purchase_request->id;
            endif;
            $data=$request->item_id;


            // Delete
            if ($edit_mode!=''):
                DB::Connection('mysql2')->table('purchase_request_data')->where('master_id', $edit_mode)->delete();
            endif;
            // End
            $TotAmount = 0;
            foreach($data as $key=>$row):

                $purch_request_data =new PurchaseRequestData();
                $purch_request_data=$purch_request_data->SetConnection('mysql2');
                $purch_request_data->master_id=$master_id;
                $purch_request_data->purchase_request_no=$purchaseRequestNo;
                $purch_request_data->purchase_request_date=$request->po_date;
                $purch_request_data->sub_item_id=$request->input('item_id')[$key];
                $purch_request_data->description=$row;
                $purch_request_data->purchase_request_qty=$request->input('actual_qty')[$key];
                $purch_request_data->purchase_approve_qty=$request->input('actual_qty')[$key];

                // $purch_request_data->no_of_carton=$request->input('no_of_carton')[$key];


                $purch_request_data->rate=$request->input('rate')[$key];
                $purch_request_data->amount=CommonHelper::check_str_replace($request->input('amount')[$key]);
                $purch_request_data->sub_total=CommonHelper::check_str_replace($request->input('amount')[$key]);
                $purch_request_data->discount_percent=CommonHelper::check_str_replace($request->input('discount_percent')[$key]);
                $purch_request_data->discount_amount=str_replace(',','',$request->input('discount_amount')[$key]);
                $purch_request_data->net_amount=CommonHelper::check_str_replace($request->input('after_dis_amount')[$key]);
                $TotAmount+=CommonHelper::check_str_replace($request->input('after_dis_amount')[$key]);
                $purch_request_data->purchase_request_status=1;
                $purch_request_data->status=1;
                $purch_request_data->date=date('Y-m-d');
                $purch_request_data->username=Auth::user()->name;
                $purch_request_data->save();
                 endforeach;

            CommonHelper::inventory_activity($purchaseRequestNo,$po_date,$TotAmount+$SalesTaxAmount,2,'Insert');
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getLine());
        }
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('store/viewPurchaseRequestList?pageType=view&&parentCode=001&&m='.$_GET['m']);
    }


    public function insert_opening_data(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {

            $warehouse = $request->warehouse;
            DB::Connection('mysql2')->table('stock')->where('sub_item_id', $request->sub_1)->where('opening', 1)->delete();
            foreach ($warehouse as $key => $row) :
                $qty =   $request->input('closing_stock')[$key];
                $amount = $request->input('closing_val')[$key];

                $data = array(
                    'voucher_type' => 1,
                    'sub_item_id' => $request->sub_1,
                    'batch_code' => $request->input('batch_code')[$key],
                    'qty' => $request->input('closing_stock')[$key],
                    'amount' => $request->input('closing_val')[$key],
                    'warehouse_id' => $row,
                    'opening' => 1,
                    'created_date' => date('Y-m-d'),
                    'username' => 'Amir Murshad',
                    'status' => 1

                );
                DB::Connection('mysql2')->table('stock')->insertGetId($data);

            endforeach;




            // $year = $request->year;
            // DB::Connection('mysql2')->table('year_wise_opening')->where('item_id', $request->sub_1)->delete();
            // foreach ($year as $key => $row1) :


            //     $data1 = array(
            //         'item_id' => $request->sub_1,
            //         'year' => $row1,
            //         'sales_qty' => $request->input('sales_qty')[$key],
            //         'purchase_qty' => $request->input('purchase_qty')[$key],
            //         'date' => date('Y-m-d'),
            //         'username' => Auth::user()->name,
            //         'action' => 1,
            //     );
            //     DB::Connection('mysql2')->table('year_wise_opening')->insertGetId($data1);

            // endforeach;


            $amount = DB::Connection('mysql2')->table('stock')->where('status', 1)->where('opening', 1)->sum('amount');

            $data2 = array(
                'amount' => $amount,
                'debit_credit' => 1,
                'action' => 'update'
            );

            DB::Connection('mysql2')->table('transactions')->where('acc_id', 97)->where('opening_bal', 1)->update($data2);

            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }

        return redirect()->back()->with('message', 'Submit!');
    }

    public function addConvertGrnData()
    {


        $demandDataSection = Input::get('GrnTotalAmount');

        $str = DB::Connection("mysql2")->selectOne("select max(convert(substr(`grn_no`,4,length(substr(`grn_no`,4))-4),signed integer)) reg from `goods_receipt_note` where substr(`grn_no`,-4,2) = " . date('m') . " and substr(`grn_no`,-2,2) = " . date('y') . "")->reg;
        $grn_no = 'grn' . ($str + 1) . date('my');
        DB::Connection('mysql2')->beginTransaction();
        try {

            $vendor_id = Input::get('vendor_id');
            $voucher_no = Input::get('voucher_no');
            $import_id = Input::get('import_id');

            $Master['supplier_id'] = $vendor_id;
            $Master['grn_no'] = $grn_no;
            $Master['grn_date'] = date('Y-m-d');
            $Master['import_id'] = $import_id;
            $Master['po_no'] = $voucher_no;
            $Master['type'] = 5;
            $Master['status'] = 1;
            $Master['date'] = date('Y-m-d');
            $Master['username'] = Auth::user()->name;
            //print_r($Master); die();

          $MasterId = DB::Connection('mysql2')->table('goods_receipt_note')->insertGetId($Master);
            $ItemId = Input::get('GrnItemId');
            $GetBatchCode = Input::get('GetBatchCode');
            $grn_qty = Input::get('grn_qty');
            $warehouse_id = Input::get('warehouse_id');

            $TotalAmount = Input::get('GrnTotalAmount');
            $total_qty = Input::get('total_qty');


            $GrnImportDataId = Input::get('GrnImportDataId');
            $RowNumber = Input::get('getLines');




            foreach ($demandDataSection as $key => $row2)
            {

               $Detail['master_id'] = $MasterId;

                $Detail['grn_no'] = $grn_no;
                $Detail['import_data_id'] = strip_tags($GrnImportDataId[$key]);
                $Detail['sub_item_id'] = strip_tags($ItemId[$key]);
                $Detail['batch_code'] = strip_tags($GetBatchCode[$key]);
                $Detail['purchase_recived_qty'] = $grn_qty[$key];


                $rate=$TotalAmount[$key]/$total_qty[$key];


                $Detail['rate'] = $TotalAmount[$key]/$total_qty[$key];
                $Detail['warehouse_id'] = strip_tags($warehouse_id[$key]);
                $amount=($rate*$grn_qty[$key]);
                $Detail['amount'] = $amount;
                $Detail['net_amount'] = $amount;
      DB::Connection('mysql2')->table('grn_data')->insertGetId($Detail);
            }


            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        return Redirect::to('sales/createTestForm?pageType=add&&parentCode=001&&m=1');
    }


    public function addIssuanceDetail(Request $request)
    {




        DB::Connection('mysql2')->beginTransaction();
        try {

            $wo=StoreHelper::unique_for_wo(date('y'),date('m'));




            $data=array
            (
                'voucher_no'=>$wo,
                'voucher_date'=>$request->voucher_date,
                'supplier_id'=>$request->supplier_id,
                'desc'=>$request->description_1,
                'status'=>1,
                'date'=>date('Y-m-d'),
                'username'=>Auth::user()->name,
            );
           $id= DB::Connection('mysql2')->table('product_creation')->insertGetId($data);
            $data1=$request->item_id;
            foreach ($data1 as $key => $row)
            {
                $data2=array
                (
                    'voucher_no'=>$wo,
                    'master_id'=>$id,
                    'product_id'=>$row,
                    'qty'=>$request->input('qty')[$key],
                    'maketype'=>$request->input('maketype')[$key],
                    'amount'=>$request->input('amount')[$key],
                    'net_amount'=>$request->input('net_amount')[$key],
                    'status'=>1,
                    'date'=>date('Y-m-d'),
                    'username'=>Auth::user()->name,
                );
                DB::Connection('mysql2')->table('product_creation_data')->insertGetId($data2);





            }


            CommonHelper::reconnectMasterDatabase();

            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        Session::flash('dataInsert', 'Purchase Request Successfully Saved.');

        return Redirect::to('store/issuanceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function updateIssuanceDetail(Request $request)
    {



        $count=   ReuseableCode::check_issuence_entry($request->EditId);
        if ($count>0):
            'Can Not Edit';
            Die;
            endif;


        DB::Connection('mysql2')->beginTransaction();
        try {
            $VoucherNo = $request->VoucherNo;
            $data=array
            (
                'voucher_no'=>$VoucherNo,
                'voucher_date'=>$request->voucher_date,
                'supplier_id'=>$request->supplier_id,
                'desc'=>$request->description_1,
                'status'=>1,
                'date'=>date('Y-m-d'),
                'username'=>Auth::user()->name,
            );
            $EditId = $request->EditId;
            DB::Connection('mysql2')->table('product_creation')->where('id','=',$EditId)->update($data);
            DB::Connection('mysql2')->table('product_creation_data')->where('master_id','=',$EditId)->delete();
            $data1=$request->item_id;
            foreach ($data1 as $key => $row)
            {
                $data2=array
                (
                    'voucher_no'=>$VoucherNo,
                    'master_id'=>$EditId,
                    'product_id'=>$row,
                    'qty'=>$request->input('qty')[$key],
                    'maketype'=>$request->input('maketype')[$key],
                    'amount'=>$request->input('amount')[$key],
                    'net_amount'=>$request->input('net_amount')[$key],
                    'status'=>1,
                    'date'=>date('Y-m-d'),
                    'username'=>Auth::user()->name,
                );
                DB::Connection('mysql2')->table('product_creation_data')->insertGetId($data2);





            }


            CommonHelper::reconnectMasterDatabase();

            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        Session::flash('dataInsert', 'Purchase Request Successfully Saved.');

        return Redirect::to('store/issuanceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }



        public function add_issuence(Request $request)
        {

            DB::Connection('mysql2')->beginTransaction();
            try {
                DB::Connection('mysql2')->table('issuence_for_production')->where('main_id',$request->input('main_id'))->delete();
                DB::Connection('mysql2')->table('stock')->where('voucher_no',$request->input('voucher_no'))->delete();
                DB::Connection('mysql2')->table('transactions')->where('voucher_no',$request->input('voucher_no'))->delete();
                $data =$request->count;
                $id=$request->input('main_id');
                $voucher_data=DB::Connection('mysql2')->table('product_creation')->where('status',1)->where('id',$id)->select('voucher_date','desc')->first();
                $voucher_date=$voucher_data->voucher_date;
                $desc=$voucher_data->desc;
                $total_amount=0;
            foreach ($data as $count=> $row):

              $product_id=$request->input('product_id');
              $product_dat_id=$request->input('master_id');

              $voucher_no=$request->input('voucher_no');
              $item=$request->input('item_id'.$row);



                foreach ($item as $key =>$row1):
                    $data1=array
                    (

                        'voucher_no'=>$voucher_no,
                        'item_id'=>$request->input('item_id'.$row)[$key],
                        'main_id'=>$id,
                        'master_id'=>$product_dat_id[$row],
                        'qty'=>$request->input('qty'.$row)[$key],
                        'batch_code'=>$request->input('batch_code'.$row)[$key],
                        'warehouse_id'=>$request->input('warehouse_from'.$row)[$key],
                        'rate'=>0,
                        'amount'=>0,
                        'status'=>1,
                        'date'=>date('Y-m-d'),
                        'username'=>Auth::user()->name,
                    );

                    $issuence_for_production_id=DB::Connection('mysql2')->table('issuence_for_production')->insertGetId($data1);

                    $qty=$request->input('qty'.$row)[$key];

                    $average_cost=ReuseableCode::average_cost_sales($request->input('item_id'.$row)[$key],$request->input('warehouse_from'.$row)[$key],$request->input('batch_code'.$row)[$key]);
                    $data3=array
                    (
                        'main_id'=>$id,
                        'master_id'=>$product_dat_id[$row],
                        'issuence_for_production_id'=>$issuence_for_production_id,
                        'voucher_no'=>$voucher_no,
                        'voucher_date'=>$voucher_date,
                        'voucher_type'=>5,
                        'sub_item_id'=>$request->input('item_id'.$row)[$key],
                        'batch_code'=>$request->input('batch_code'.$row)[$key],
                        'qty'=>$qty,
                        'rate'=>$average_cost,
                        'amount_before_discount'=>$average_cost*$qty,
                        'discount_percent'=>0,
                        'discount_amount'=>0,
                        'amount'=>$average_cost*$qty,
                        'warehouse_id'=>$request->input('warehouse_from'.$row)[$key],
                        'status'=>1,
                        'created_date'=>date('Y-m-d'),
                        'username'=>Auth::user()->name,
                        'pos_status'=>2,

                    );
                    $total_amount+=$average_cost*$qty;
                    DB::Connection('mysql2')->table('stock')->insertGetId($data3);

                endforeach;

             endforeach;


                $transaction=new Transactions();
                $transaction=$transaction->SetConnection('mysql2');
                $transaction->voucher_no=$voucher_no;
                $transaction->v_date=$voucher_date;
                $acc_id=DB::Connection('mysql2')->table('accounts')->where('code','1-2-1-2')->value('id');
                $transaction->acc_id=$acc_id;
                $transaction->acc_code='1-2-1-2';
                $transaction->particulars=$desc;
                $transaction->opening_bal=0;
                $transaction->debit_credit=1;
                $transaction->amount=$total_amount;
                $transaction->username=Auth::user()->name;;
                $transaction->status=1;
                $transaction->voucher_type=13;
                $transaction->save();


                $transaction=new Transactions();
                $transaction=$transaction->SetConnection('mysql2');
                $transaction->voucher_no=$voucher_no;
                $transaction->v_date=$voucher_date;
                $transaction->acc_id=97;
                $transaction->acc_code='1-2-1-1';
                $transaction->particulars=$desc;
                $transaction->opening_bal=0;
                $transaction->debit_credit=0;
                $transaction->amount=$total_amount;
                $transaction->username=Auth::user()->name;;
                $transaction->status=1;
                $transaction->voucher_type=13;
                $transaction->save();




                DB::Connection('mysql2')->commit();
            }
            catch ( Exception $ex )
            {

                DB::rollBack();
                $ex->getCode();


            }
            return Redirect::to('store/issuanceList');
        }


    public function issuence_return(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try
        {
            $data=$request->issuence_id;
            $total_amount=0;
            foreach($data as $key => $row):

              $qty=$request->input('return')[$key];
             $data1['return_qty']=$qty;
                DB::Connection('mysql2')->table('issuence_for_production')->where('id',$request->input('issuence_id')[$key])->update($data1);


                if ($qty!='' && $qty>0):

                $edit_id=$request->input('issuence_id')[$key];
                $issuence_data=DB::Connection('mysql2')->table('issuence_for_production')->where('id',$edit_id)->first();

                    $average_cost=DB::Connection('mysql2')->table('stock')->where('issuence_for_production_id',$row)->value('rate');
               // $average_cost=ReuseableCode::average_cost_sales($request->input('sub_item_id')[$key],$request->input('issuence_warehouse_id')[$key],$request->input('issuence_batch_code')[$key]);
                $data3=array
                (
                    'main_id'=>$request->input('main_id'),
                    'master_id'=>$request->input('issuence_master_id')[$key],
                    'voucher_no'=>$request->input('voucher_no'),
                    'voucher_date'=>date('Y-m-d'),
                    'voucher_type'=>1,
                    'sub_item_id'=>$request->input('sub_item_id')[$key],
                    'batch_code'=>$request->input('issuence_batch_code')[$key],
                    'qty'=>$qty,
                    'rate'=>$average_cost,
                    'amount_before_discount'=>$average_cost*$qty,
                    'discount_percent'=>0,
                    'discount_amount'=>0,
                    'amount'=>$average_cost*$qty,
                    'warehouse_id'=>$request->input('issuence_warehouse_id')[$key],
                    'status'=>1,
                    'created_date'=>date('Y-m-d'),
                    'username'=>Auth::user()->name,
                    'transfer_status'=>2,
                    'pos_status'=>3,

                );
                $total_amount+=$average_cost*$qty;
                DB::Connection('mysql2')->table('stock')->insertGetId($data3);
            endif;
            endforeach;



            $transaction=new Transactions();
            $transaction=$transaction->SetConnection('mysql2');
            $transaction->voucher_no=$request->input('voucher_no');
            $transaction->v_date=date('Y-m-d');
            $transaction->acc_id=97;
            $transaction->acc_code='1-2-1-1';
            $transaction->particulars='Return';
            $transaction->opening_bal=0;
            $transaction->debit_credit=1;
            $transaction->amount=$total_amount;
            $transaction->username=Auth::user()->name;;
            $transaction->status=1;
            $transaction->voucher_type=5;
            $transaction->action='Testing';
            $transaction->save();


            $transaction=new Transactions();
            $transaction=$transaction->SetConnection('mysql2');
            $transaction->voucher_no=$request->input('voucher_no');
            $transaction->v_date=date('Y-m-d');
            $acc_id=DB::Connection('mysql2')->table('accounts')->where('code','1-2-1-2')->value('id');
            $transaction->acc_id=$acc_id;
            $transaction->acc_code='1-2-1-2';
            $transaction->particulars='Return';
            $transaction->opening_bal=0;
            $transaction->debit_credit=0;
            $transaction->amount=$total_amount;
            $transaction->username=Auth::user()->name;;
            $transaction->status=1;
            $transaction->action='Testing';
            $transaction->voucher_type=5;
            $transaction->save();


            DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {

            DB::rollBack();
            $ex->getCode();


        }
        return Redirect::to('store/issuanceList');
        }


    public function add_production(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try
        {
            $data=$request->master_id;
            $total_amount=0;
            $work_in_progress=0;
            foreach($data as $key => $row):

              if ($request->input('warehouse_id')[$key]!='' && $request->input('bacth_code')[$key]!=''):

                    $data3=array
                    (
                        'main_id'=>$row,
                        'master_id'=>$request->input('master_id')[$key],
                        'voucher_no'=>$request->input('voucher_no'),
                        'voucher_date'=>date('Y-m-d'),
                        'voucher_type'=>1,
                        'sub_item_id'=>$request->input('product_id')[$key],
                        'batch_code'=>$request->input('bacth_code')[$key],
                        'qty'=>$request->input('make_qty')[$key],
                        'rate'=>$request->input('final_cost')[$key],
                        'amount_before_discount'=>$request->input('final_cost')[$key]*$request->input('make_qty')[$key],
                        'discount_percent'=>0,
                        'discount_amount'=>0,
                        'amount'=>$request->input('final_cost')[$key]*$request->input('make_qty')[$key],
                        'warehouse_id'=>$request->input('warehouse_id')[$key],
                        'supplier_id'=>$request->supplier,
                        'status'=>1,
                        'created_date'=>date('Y-m-d'),
                        'username'=>Auth::user()->name,
                        'pos_status'=>4,

                    );

                DB::Connection('mysql2')->table('stock')->insertGetId($data3);
                $total_amount+=$request->input('final_cost')[$key]*$request->input('make_qty')[$key];
                $work_in_progress+=$request->input('work_in_progress')[$key];


                  endif;
            endforeach;


            if ($total_amount>0):
            $pv_no=CommonHelper::uniqe_no_for_purcahseVoucher(date('y'),date('m'));

            $date=date('Y-m-d');
            $date = strtotime($date);
            $date = strtotime("+60 day", $date);
            $due_date= date('Y-m-d', $date);
            $data1=array
            (
                'pv_no'=>$pv_no,
                'pv_date'=>date('Y-m-d'),
                'grn_no'=>'',
                'grn_id'=>0,
                'slip_no'=>$request->voucher_no,
                'bill_date'=>date('Y-m-d'),
                'due_date'=>$due_date,
                'supplier'=>$request->supplier,
                'description'=>$request->voucher_no,
                'username'=>Auth::user()->name,
                'work_order_id'=>$request->main_id,
                'status'=>1,
                'pv_status'=>2,
                'date'=>date('Y-m-d'),
            );

            $master_id=DB::Connection('mysql2')->table('new_purchase_voucher')->insertGetId($data1);

            $w_data= DB::Connection('mysql2')->table('stock')->where('voucher_no',$request->voucher_no)->where('pos_status',4)->where('status',1)->get();
            $payable=0;
            foreach($data as $key => $row):

                if ($request->input('warehouse_id')[$key]!='' && $request->input('bacth_code')[$key]!=''):

                $data2=array
                (
                    'master_id'=>$master_id,
                    'pv_no'=>$pv_no,
                    'slip_no'=>'',
                    'grn_data_id'=>0,
                    'category_id'=>97,
                    'sub_item'=>$request->input('product_id')[$key],
                    'uom'=>0,
                    'qty'=>$request->input('make_qty')[$key],
                    'rate'=>$request->input('new_pv_rate')[$key],
                    'amount'=>$request->input('net_amount')[$key],
                    'discount_amount'=>0,
                    'net_amount'=>$request->input('net_amount')[$key],
                    'staus'=>1,
                    'pv_status'=>2,
                    'username'=>Auth::user()->name,
                    'date'=>date('Y-m-d'),
                    'additional_exp'=>0
                );
                DB::Connection('mysql2')->table('new_purchase_voucher_data')->insertGetId($data2);

                $data4['pi_no']=$pv_no;
                DB::Connection('mysql2')->table('product_creation_data')->where('id',$request->input('master_id')[$key])->update($data4);
                $payable+=$request->input('net_amount')[$key];
                    endif;
            endforeach;



            $transaction=new Transactions();
            $transaction=$transaction->SetConnection('mysql2');
            $transaction->voucher_no=$pv_no;
            $transaction->v_date=date('Y-m-d');
            $transaction->acc_id=97;
            $transaction->acc_code='1-2-1-1';
            $transaction->particulars=$request->input('voucher_no');
            $transaction->opening_bal=0;
            $transaction->debit_credit=1;
            $transaction->amount=$total_amount;
            $transaction->username=Auth::user()->name;;
            $transaction->status=1;
            $transaction->voucher_type=4;
            $transaction->save();


            $transaction=new Transactions();
            $transaction=$transaction->SetConnection('mysql2');
            $transaction->voucher_no=$pv_no;
            $transaction->v_date=date('Y-m-d');
            $acc_id=CommonHelper::get_supplier_acc_id($request->supplier);
            $transaction->acc_id=$acc_id;
            $transaction->acc_code=CommonHelper::get_account_code($acc_id);
            $transaction->particulars=$pv_no;
            $transaction->opening_bal=0;
            $transaction->debit_credit=0;
            $transaction->amount=$payable;
            $transaction->username=Auth::user()->name;;
            $transaction->status=1;
            $transaction->voucher_type=4;
            $transaction->save();


            $transaction=new Transactions();
            $transaction=$transaction->SetConnection('mysql2');
            $transaction->voucher_no=$pv_no;
            $transaction->v_date=date('Y-m-d');
            $acc_id=CommonHelper::get_supplier_acc_id($request->supplier);
            $acc_id=DB::Connection('mysql2')->table('accounts')->where('code','1-2-1-2')->value('id');
            $transaction->acc_id=$acc_id;
            $transaction->acc_code='1-2-1-2';
            $transaction->particulars=$pv_no;
            $transaction->opening_bal=0;
            $transaction->debit_credit=0;
            $transaction->amount=$work_in_progress;
            $transaction->username=Auth::user()->name;;
            $transaction->status=1;
            $transaction->voucher_type=4;
            $transaction->save();

            endif;
            DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {

            DB::rollBack();
            $ex->getCode();


        }
        return Redirect::to('store/issuanceList');
    }


    public function addMaterialRequestDetail(Request $request){
        // dd(Input::all());

        // echo "<pre>";
        // print_r($request->all());
        // exit();
        date_default_timezone_set("Asia/Karachi");
        $m = Session::get('run_company');
        try{
            $materialRequestsSection = Input::get('materialRequestsSection');
            foreach ($materialRequestsSection as $row) {
                $material_request_date = strip_tags(date("Y-m-d", strtotime(Input::get('material_request_date_' . $row . ''))));
                $description = strip_tags(Input::get('description_' . $row . ''));
                $sub_department_id = 0;
                $location_id = strip_tags(Input::get('location_id_'.$row.''));
                $materialRequestDataSection = Input::get('materialRequestDataSection');
                // dd($materialRequestDataSection);
                $str = DB::connection('mysql2')->selectOne("select max(convert(substr(`material_request_no`,3,length(substr(`material_request_no`,3))-4),signed integer)) reg from `material_requests` where substr(`material_request_no`,-4,2) = " . date('m') . " and substr(`material_request_no`,-2,2) = " . date('y') . "")->reg;
                // dd($str);
                $material_request_no = 'MR' . ($str + 1) . date('my');

                $data1['material_request_no'] = $material_request_no;
                $data1['material_request_date'] = $material_request_date;
                $data1['description'] = $description;
                $data1['sub_department_id'] = $request->sub_department_id_1;
                // $data1['job_card_id'] = $request->job_card_id;
                // $data1['machine_no'] = $request->machine_no;
                // $data1['operator_name'] = $request->operator_name;
                // $data1['warehouse_id'] = $location_id;
                $data1['user_id'] = Auth::user()->id;
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['material_request_status'] = 1;
                $data1['approve_user_id'] = Auth::user()->id;
                $data1['company_id'] = $m;

                DB::connection('mysql2')->table('material_requests')->insert($data1);
               
                $sub_item_id = Input::get('sub_item_id');                    

                foreach ($sub_item_id as $key => $row2) {

                    // $sub_item_id = strip_tags(Input::get('sub_item_id_' . $row . '_' . $row2 . ''));
                    // $qty = strip_tags(Input::get('qty_' . $row . '_' . $row2 . ''));
                    $qty = Input::get('qty');
                    // $subDescription = strip_tags(Input::get('sub_description_' . $row . '_' . $row2 . ''));
                    $subDescription = Input::get('sub_description');
                    
                    // dd($category_id,$sub_item_id,$qty,$subDescription);

                    $data2['material_request_no'] = $material_request_no;
                    $data2['material_request_date'] = $material_request_date;
                    $data2['sub_item_id'] = $sub_item_id[$key];
                    $data2['required_date'] = date("Y-m-d");
                    $data2['qty'] = $qty[$key];
                    $data2['sub_description'] = $subDescription[$key];
                    $data2['approx_cost'] = 0;
                    $data2['date'] = date("Y-m-d");
                    $data2['time'] = date("H:i:s");
                    $data2['user_id'] = Auth::user()->id;
                    $data2['username'] = Auth::user()->name;
                    $data2['status'] = 1;
                    $data2['material_request_status'] = 1;
                    $data2['company_id'] = $m;

                    DB::connection('mysql2')->table('material_request_datas')->insert($data2);

                }
            }
            
            
            return redirect()->to('store/viewMaterialRequestList')->with('success','Add Material Request Successfully!');
        }catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
            return redirect()->back()->with('error','Oops! There might be a issue '. $e->getMessage());
        }
    }




    public function editMaterialRequestDetail(Request $request){
        
        date_default_timezone_set("Asia/Karachi");
        $m = Session::get('run_company');
        try{
            $materialRequestsSection = Input::get('materialRequestsSection');
            foreach ($materialRequestsSection as $row) {
                $material_request_date = strip_tags(date("Y-m-d", strtotime(Input::get('material_request_date_' . $row . ''))));
                $description = strip_tags(Input::get('description_' . $row . ''));
                $sub_department_id = 0;
                $location_id = strip_tags(Input::get('location_id_'.$row.''));
                $materialRequestDataSection = Input::get('materialRequestDataSection');
                // dd($materialRequestDataSection);

                $data1['material_request_no'] = strip_tags(Input::get('material_request_no_' . $row . ''));
                $data1['material_request_date'] = $material_request_date;
                $data1['description'] = $description;
                $data1['sub_department_id'] = $request->sub_department_id_1;;
                // $data1['job_card_id'] = $request->job_card_id;
                // $data1['machine_no'] = $request->machine_no;
                // $data1['operator_name'] = $request->operator_name;
                // $data1['warehouse_id'] = $location_id;
                $data1['user_id'] = Auth::user()->id;
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['material_request_status'] = 1;
                $data1['approve_user_id'] = Auth::user()->id;
                $data1['company_id'] = $m;

                $request_data = DB::connection('mysql2')->table('material_requests')->where('id',strip_tags(Input::get('id_' . $row . '')))->first();

                // echo "<pre>";
                // print_r($request_data);
                // exit();
                DB::connection('mysql2')->table('material_requests')->where('id',strip_tags(Input::get('id_' . $row . '')))->update($data1);
                
                $abc = DB::connection('mysql2')->table('material_request_datas')
                ->where([
                    ['material_request_no',$request_data->material_request_no ],
                    ['material_request_date',$request_data->material_request_date ]

                ])
                ->delete();


                $sub_item_id = Input::get('sub_item_id');                    

                foreach ($sub_item_id as $key => $row2) {

                    // $sub_item_id = strip_tags(Input::get('sub_item_id_' . $row . '_' . $row2 . ''));
                    // $qty = strip_tags(Input::get('qty_' . $row . '_' . $row2 . ''));
                    $qty = Input::get('qty');
                    // $subDescription = strip_tags(Input::get('sub_description_' . $row . '_' . $row2 . ''));
                    $subDescription = Input::get('sub_description');
                    
                    // dd($category_id,$sub_item_id,$qty,$subDescription);

                    $data2['material_request_no'] = strip_tags(Input::get('material_request_no_' . $row . ''));
                    $data2['material_request_date'] = $material_request_date;
                    $data2['sub_item_id'] = $sub_item_id[$key];
                    $data2['required_date'] = date("Y-m-d");
                    $data2['qty'] = $qty[$key];
                    $data2['sub_description'] = $subDescription[$key];
                    $data2['approx_cost'] = 0;
                    $data2['date'] = date("Y-m-d");
                    $data2['time'] = date("H:i:s");
                    $data2['user_id'] = Auth::user()->id;
                    $data2['username'] = Auth::user()->name;
                    $data2['status'] = 1;
                    $data2['material_request_status'] = 1;
                    $data2['company_id'] = $m;

                    DB::connection('mysql2')->table('material_request_datas')->insert($data2);

                }
            }
            
            
            return redirect()->to('store/viewMaterialRequestList')->with('success','updated Material Request Successfully!');
        }catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
            return redirect()->back()->with('error','Oops! There might be a issue '. $e->getMessage());
        }
    }

    public function deleteMaterialRequestDetail(Request $request)
    {
        
        $request_data = DB::connection('mysql2')->table('material_requests')->where([
            ['material_request_no',$request->material_request_no ],
            ['material_request_date',$request->material_request_date ],
            ['status',1 ]

        ])->first();
        

        if($request_data)
        {

            DB::connection('mysql2')->table('material_requests')
            ->where([
                ['material_request_no',$request_data->material_request_no ],
                ['material_request_date',$request_data->material_request_date ]

            ])
            ->update([
                'status' => 0
            ]);
            
            DB::connection('mysql2')->table('material_request_datas')
            ->where([
                ['material_request_no',$request_data->material_request_no ],
                ['material_request_date',$request_data->material_request_date ]

            ])
            ->update([
                'status' => 0
            ]);


            return response()->json(['status' => 'success', 'message' => 'Record deleted successfully']);
        } else {
            // Return an error response with a message
            return response()->json(['status' => 'error', 'message' => 'Record not found']);
        }

    }

}

