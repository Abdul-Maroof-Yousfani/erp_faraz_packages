<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;
class StoreEditDetailControler extends Controller
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

    public function editStoreChallanVoucherDetail(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $storeChallanNo = Input::get('store_challan_no');
        $slip_no = Input::get('slip_no');
        $store_challan_date = Input::get('store_challan_date');
        $sub_department_id = Input::get('departmentId');
        $pageType = Input::get('pageType');
        $parentCode = Input::get('parentCode');
        $main_description = Input::get('description');
        DB::table('fara')->where('sc_no', $storeChallanNo)->delete();


        $data1['slip_no'] = $slip_no;
        $data1['store_challan_date'] = $store_challan_date;
        $data1['description'] = $main_description;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('store_challan')->where('store_challan_no','=',$storeChallanNo)->update($data1);
        $seletedStoreChallanRow = Input::get('storeChallanDataSection');
        foreach ($seletedStoreChallanRow as $row) {
            $recordId = Input::get('recordId_'.$row.'');
            $demandNo = Input::get('demandNo_'.$row.'');
            $demandDate = Input::get('demandDate_'.$row .'');
            $categoryId = Input::get('categoryId_'.$row.'');
            $subItemId = Input::get('subItemId_'.$row.'');
            $issue_qty = Input::get('issue_qty_'.$row.'');
            $demandAndRemainingQty = Input::get('demandAndRemainingQty_'.$row.'');

            $data2['store_challan_date'] = $store_challan_date;
            $data2['demand_date'] = $demandDate;
            $data2['issue_qty'] = $issue_qty;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");

            DB::table('store_challan_data')->where('store_challan_no','=',$storeChallanNo)->where('id','=',$recordId)->update($data2);
            if ($issue_qty == $demandAndRemainingQty) {
                DB::table('demand_data')
                    ->where('category_id', $categoryId)
                    ->where('sub_item_id', $subItemId)
                    ->where('demand_no', $demandNo)
                    ->update(['store_challan_status' => "2"]);

            }

            $data3['sc_no'] = $storeChallanNo;
            $data3['sc_date'] = $store_challan_date;
            $data3['demand_no'] = $demandNo;
            $data3['demand_date'] = $demandDate;
            $data3['main_ic_id'] = $categoryId;
            $data3['sub_ic_id'] = $subItemId;
            $data3['qty'] = $issue_qty;
            $data3['value'] = 0;
            $data3['username'] = Auth::user()->name;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['action'] = 2;
            $data3['status'] = 1;
            $data3['company_id'] = $m;
            DB::table('fara')->insert($data3);
        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully Update.');
        return Redirect::to('store/viewStoreChallanList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    public function editPurchaseRequestVoucherDetail(){
        echo "aa"; die;
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $purchaseRequestNo = Input::get('purchase_request_no');
        $slip_no = Input::get('slip_no');
        $purchase_request_date = Input::get('purchase_request_date');
        $sub_department_id = Input::get('departmentId');
        $pageType = Input::get('pageType');
        $parentCode = Input::get('parentCode');
        $main_description = Input::get('description');
        $supplier_id = Input::get('supplier_id');



        $data1['slip_no'] = $slip_no;
        $data1['purchase_request_date'] = $purchase_request_date;
        $data1['description'] = $main_description;
        $data1['supplier_id'] = $supplier_id;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('purchase_request')->where('purchase_request_no','=',$purchaseRequestNo)->update($data1);
        $seletedPurchaseRequestRow = Input::get('purchaseRequestDataSection');
        foreach ($seletedPurchaseRequestRow as $row) {
            $recordId = Input::get('recordId_'.$row.'');
            $demandNo = Input::get('demandNo_'.$row.'');
            $demandDate = Input::get('demandDate_'.$row .'');
            $categoryId = Input::get('categoryId_'.$row.'');
            $subItemId = Input::get('subItemId_'.$row.'');
            $purchase_request_qty = Input::get('purchase_request_qty_'.$row.'');
            $purchase_request_rate = Input::get('purchase_request_rate_'.$row.'');
            $purchase_request_sub_total = $purchase_request_qty*$purchase_request_rate;


            $data2['purchase_request_date'] = $purchase_request_date;
            $data2['demand_no'] = $demandNo;
            $data2['demand_date'] = $demandDate;
            $data2['purchase_request_qty'] = $purchase_request_qty;
            $data2['rate'] = $purchase_request_rate;
            $data2['sub_total'] = $purchase_request_sub_total;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");

            DB::table('purchase_request_data')->where('purchase_request_no','=',$purchaseRequestNo)->where('id','=',$recordId)->update($data2);

        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully Update.');
        return Redirect::to('store/viewPurchaseRequestList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

}

