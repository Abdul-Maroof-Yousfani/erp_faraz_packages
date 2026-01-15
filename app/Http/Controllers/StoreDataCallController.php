<?php

namespace App\Http\Controllers;

use App\Helpers\ReuseableCode;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use Auth;
use DB;
use Config;
use Session;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\UOM;
use App\Models\Demand;
use App\Models\DemandData;
use App\Models\StoreChallan;
use App\Models\StoreChallanData;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\StoreChallanReturn;
use App\Models\StoreChallanReturnData;
use App\Models\Transactions;

class StoreDataCallController extends Controller
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

    public function filterDemandVoucherList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        $counter = 1;
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];
echo "aaa"; die;
        CommonHelper::companyDatabaseConnection($m);
        if($selectVoucherStatus == '0' && empty($selectSubDepartmentId)){
            $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->orderBy('id','desc')->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubDepartmentId)){
            $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('sub_department_id','=',$selectSubDepartmentId)->orderBy('id','desc')->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubDepartmentId)){
            $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_status','=','1')->where('sub_department_id','=',$selectSubDepartmentId)->orderBy('id','desc')->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId)){
            $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->orderBy('id','desc')->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId)){
            $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->where('status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->orderBy('id','desc')->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId)){
            $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_status','=','1')->orderBy('id','desc')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId)){
            $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_status','=','2')->orderBy('id','desc')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId)){
            $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->where('status','=','2')->orderBy('id','desc')->get();
        }


        //CommonHelper::companyDatabaseConnection($m);
        //$demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->where('demand_status','=','2')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Store.AjaxPages.filterDemandVoucherList',compact('demandDetail'));
    }

    public function viewDemandVoucherDetail(){
        return view('Purchase.AjaxPages.viewDemandVoucherDetail');
    }
    public function viewStockTransferDetail(){
        return view('Store.AjaxPages.viewStockTransferDetail');
    }
    public function viewIssuanceDetail(){
        return view('Store.AjaxPages.viewIssuanceDetail');
    }
    public function get_work_order_data(){
        return view('Store.AjaxPages.get_work_order_data');
    }


    public function viewMaterialRequestDetail($id){
       
        $materialReqDetail = DB::connection('mysql2')
            ->table('material_requests as mr')
            // ->leftJoin('job_cards as jc','mr.job_card_id','=','jc.id')
            ->select('mr.*')
            ->where('mr.id',$id)
            ->first();
        $materialReqDataDetail = DB::connection('mysql2')
            ->table('material_request_datas as mrd')
            ->leftJoin('subitem as s','mrd.sub_item_id','=','s.id')
            ->leftJoin('store_challan_data as scd','mrd.id','=','scd.material_request_data_id')
            ->select('mrd.*','s.item_code','s.sub_ic')
            ->where('mrd.material_request_no',$materialReqDetail->material_request_no)
            ->groupBy('mrd.id')
            ->get();
        return view('Store.MaterialRequest.viewMaterialRequestDetail',compact('materialReqDetail','materialReqDataDetail'));
    }


    public function filterApproveDemandListandCreateStoreChallan(){
        return view('Store.AjaxPages.filterApproveDemandListandCreateStoreChallan');
    }

    public function createStoreChallanDetailForm(Request $request){
        return view('Store.createStoreChallanDetailForm',compact('request'));
    }

    public function filterStoreChallanVoucherList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];

        CommonHelper::companyDatabaseConnection($m);
        if($selectVoucherStatus == '0' && empty($selectSubDepartmentId)){
            $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubDepartmentId)){
            $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubDepartmentId)){
            $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->where('status','=','1')->where('store_challan_status','=','1')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId)){
            $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->where('status','=','1')->where('store_challan_status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId)){
            $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->where('status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId)){
            $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->where('status','=','1')->where('store_challan_status','=','1')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId)){
            $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->where('status','=','1')->where('store_challan_status','=','2')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId)){
            $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->where('status','=','2')->get();
        }

        //$storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Store.AjaxPages.filterStoreChallanVoucherList',compact('storeChallanDetail'));
    }

    public function viewStoreChallanVoucherDetail(){
        return view('Store.AjaxPages.viewStoreChallanVoucherDetail');
    }

    public function getStockTransferDataAjax(){
        return view('Store.AjaxPages.getStockTransferDataAjax');
    }



    public function filterApproveDemandListandCreatePurchaseRequest(){
        return view('Store.AjaxPages.filterApproveDemandListandCreatePurchaseRequest');
    }

    public function filterApproveDemandListandCreatePurchaseRequestSale(){
        return view('Store.AjaxPages.filterApproveDemandListandCreatePurchaseRequestSale');
    }

    public function createPurchaseRequestDetailForm(Request $request){
        $m = $request->m;
        $po_against = $request->po_against;
        $pr_no = $request->pr_no;

        CommonHelper::companyDatabaseConnection($m);
        $supplierList = Supplier::select('*')->where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();
        //$demand_data_id = $request->checkAll;
        return view('Store.createPurchaseRequestDetailForm',compact('request','supplierList','po_against','pr_no','m'));
    }

    public function createPurchaseRequestSaleDetailForm(Request $request){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $supplierList = Supplier::select('name','id','acc_id')->where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Store.createPurchaseRequestSaleDetailForm',compact('request','supplierList'));
    }

    public function filterPurchaseRequestVoucherList(){

        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];
        $selectSupplier = $_GET['selectSupplier'];
        $selectSupplierId = $_GET['selectSupplierId'];
        CommonHelper::companyDatabaseConnection($m);
        $selectVoucherStatus;

        if($selectVoucherStatus == '0' && empty($selectSubDepartmentId) && empty($selectSupplierId)){


            //return 'One';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','1')->where('status','=','1')->where('purchase_request_status','!=','4')->orderBy('id','desc')->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Two';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('demand_type','=','1')->where('sub_department_id','=',$selectSubDepartmentId)->where('purchase_request_status','!=','4')->get();
        }else if($selectVoucherStatus == '0' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Three';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('demand_type','=','1')->where('supplier_id','=',$selectSupplierId)->where('purchase_request_status','!=','4')->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Four';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','1')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Five';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Six';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','2')->where('demand_type','=','1')->where('sub_department_id','=',$selectSubDepartmentId)->where('purchase_request_status','!=','4')->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Seven';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','1')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Eight';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','2')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Nine';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','2')->where('demand_type','=','1')->where('purchase_request_status','!=','4')->get();
        }
        else if($selectVoucherStatus == '4' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Nine';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','3')->get();
        }
        else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Ten';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','1')->where('supplier_id','=',$selectSupplierId)->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Eleven';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','2')->where('supplier_id','=',$selectSupplierId)->get();
        } else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Twelve';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','2')->where('demand_type','=','1')->where('supplier_id','=',$selectSupplierId)->where('purchase_request_status','!=','4')->get();
        } else if($selectVoucherStatus == '0' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Thirteen';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('supplier_id','=',$selectSupplierId)->where('demand_type','=','1')->where('sub_department_id','=',$selectSubDepartmentId)->where('purchase_request_status','!=','4')->get();
        } else if($selectVoucherStatus == '1' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Fourteen';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','1')->where('supplier_id','=',$selectSupplierId)->where('sub_department_id','=',$selectSubDepartmentId)->get();
        } else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Fifteen';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','2')->where('supplier_id','=',$selectSupplierId)->where('sub_department_id','=',$selectSubDepartmentId)->get();
        } else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Sixteen';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','2')->where('demand_type','=','1')->where('supplier_id','=',$selectSupplierId)->where('sub_department_id','=',$selectSubDepartmentId)->where('purchase_request_status','!=','4')->get();
        } else if($selectVoucherStatus == '5' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Sixteen';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('supplier_id','=',$selectSupplierId)->where('sub_department_id','=',$selectSubDepartmentId)->where('purchase_request_status','=','4')->get();
        } else if($selectVoucherStatus == '5' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Sixteen';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('supplier_id','=',$selectSupplierId)->where('purchase_request_status','=','4')->get();
        } else if($selectVoucherStatus == '5' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Sixteen';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','4')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        } else if($selectVoucherStatus == '5' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Sixteen';
            $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->where('demand_type','=','1')->where('purchase_request_status','=','4')->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Store.AjaxPages.filterPurchaseRequestVoucherList',compact('purchaseRequestDetail'));
    }


    public function filterPurchaseRequestSaleVoucherList(){

        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];
        $selectSupplier = $_GET['selectSupplier'];
        $selectSupplierId = $_GET['selectSupplierId'];
        CommonHelper::companyDatabaseConnection($m);
        if($selectVoucherStatus == '0' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'One';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Two';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->whereIn('status', array(1, 2))->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '0' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Three';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->whereIn('status', array(1, 2))->where('supplier_id','=',$selectSupplierId)->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Four';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','1')->where('purchase_request_status','=','1')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Five';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','1')->where('purchase_request_status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Six';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Seven';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','1')->where('purchase_request_status','=','1')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Eight';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','1')->where('purchase_request_status','=','2')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId) && empty($selectSupplierId)){
            //return 'Nine';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','2')->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Ten';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','1')->where('purchase_request_status','=','1')->where('supplier_id','=',$selectSupplierId)->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Eleven';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','1')->where('purchase_request_status','=','2')->where('supplier_id','=',$selectSupplierId)->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Twelve';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','2')->where('supplier_id','=',$selectSupplierId)->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Thirteen';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('supplier_id','=',$selectSupplierId)->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Fourteen';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','1')->where('purchase_request_status','=','1')->where('supplier_id','=',$selectSupplierId)->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Fifteen';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','1')->where('purchase_request_status','=','2')->where('supplier_id','=',$selectSupplierId)->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId) && !empty($selectSupplierId)){
            //return 'Sixteen';
            $purchaseRequestSaleDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('demand_type','=','2')->where('status','=','2')->where('supplier_id','=',$selectSupplierId)->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Store.AjaxPages.filterPurchaseRequestSaleVoucherList',compact('purchaseRequestSaleDetail'));
    }

    public function viewPurchaseRequestVoucherDetail(){
        return view('Store.AjaxPages.viewPurchaseRequestVoucherDetail');
    }

    public function viewPurchaseRequestSaleVoucherDetail(){
        return view('Store.AjaxPages.viewPurchaseRequestSaleVoucherDetail');
    }

    public function filterApproveStoreChallanandCreateStoreChallanReturn(){
        return view('Store.AjaxPages.filterApproveStoreChallanandCreateStoreChallanReturn');
    }

    public function createStoreChallanReturnDetailForm(Request $request){
        return view('Store.createStoreChallanReturnDetailForm',compact('request'));
    }

    public function filterStoreChallanReturnList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];

        CommonHelper::companyDatabaseConnection($m);
        if($selectVoucherStatus == '0' && empty($selectSubDepartmentId)){
            $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubDepartmentId)){
            $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubDepartmentId)){
            $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->where('status','=','1')->where('store_challan_return_status','=','1')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId)){
            $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->where('status','=','1')->where('store_challan_return_status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId)){
            $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->where('status','=','2')->where('sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId)){
            $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->where('status','=','1')->where('store_challan_return_status','=','1')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId)){
            $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->where('status','=','1')->where('store_challan_return_status','=','2')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId)){
            $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->where('status','=','2')->get();
        }
        //$storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->get();
        CommonHelper::reconnectMasterDatabase();
        //echo json_encode(array('data' => $storeChallanReturnDetail));
        return view('Store.AjaxPages.filterStoreChallanReturnList',compact('storeChallanReturnDetail'));
    }
    public function viewStoreChallanReturnDetail(){
        return view('Store.AjaxPages.viewStoreChallanReturnDetail');
    }

    public function filterViewDateWiseStockInventoryReport(){
        return view('Store.AjaxPages.filterViewDateWiseStockInventoryReport');
    }

    public function viewStockInventorySummaryDetail(){
        $m = $_GET['m'];
        $categoryIcId = $_GET['pOne'];
        $subIcId = $_GET['pTwo'];
        $filterDate = $_GET['pFour'];
        CommonHelper::companyDatabaseConnection($m);
        $itemOpeningQty = DB::table('fara')->where('main_ic_id','=',$categoryIcId)->where('sub_ic_id','=',$subIcId)->where('date','<=',$filterDate)->where('action','=',1)->first();
        $itemPurchaseData = DB::table('fara')->where('main_ic_id','=',$categoryIcId)->where('sub_ic_id','=',$subIcId)->where('date','<=',$filterDate)->where('action','=',3)->get();
        $itemStoreChallanData = DB::table('fara')->where('main_ic_id','=',$categoryIcId)->where('sub_ic_id','=',$subIcId)->where('date','<=',$filterDate)->where('action','=',2)->get();
        $itemStoreChallanReturnData = DB::table('fara')->where('main_ic_id','=',$categoryIcId)->where('sub_ic_id','=',$subIcId)->where('date','<=',$filterDate)->where('action','=',4)->get();
        $itemCashSaleData = DB::table('fara')->where('main_ic_id','=',$categoryIcId)->where('sub_ic_id','=',$subIcId)->where('date','<=',$filterDate)->where('action','=',5)->get();
        $itemCreditSaleData = DB::table('fara')->where('main_ic_id','=',$categoryIcId)->where('sub_ic_id','=',$subIcId)->where('date','<=',$filterDate)->where('action','=',6)->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Store.AjaxPages.viewStockInventorySummaryDetail',compact('itemOpeningQty','itemPurchaseData','itemStoreChallanData','itemStoreChallanReturnData','itemCashSaleData','itemCreditSaleData'));
    }

    public function p_detail_report()
    {
        $from_date  = $_GET['from_date'];
        $to_date    = $_GET['to_date'];
        $supplier = DB::Connection('mysql2')->select('SELECT s.id,s.name,s.company_name FROM supplier s INNER JOIN goods_receipt_note g ON s.id = g.supplier_id WHERE g.status=1 GROUP BY s.id');
        return view('Reports.p_detail_report_ajax', compact('supplier','from_date','to_date'));
    }

    public function stockDetailReport()
    {
        $from_date  = $_GET['from_date'];
        $to_date    = $_GET['to_date'];
        $region    = $_GET['region'];
        $stock = DB::Connection('mysql2')->select('SELECT stock.sub_item_id, subitem.sub_ic as name FROM stock
                                              INNER JOIN subitem ON subitem.id = stock.sub_item_id
                                              WHERE subitem.status=1 AND stock.status=1 GROUP BY stock.sub_item_id');
        return view('Store.AjaxPages.stockDetailReportAjax', compact('stock','from_date','to_date','region'));
    }

    public function approve_transfer(Request $request)
    {


        DB::Connection('mysql2')->beginTransaction();

        try {

            $id= $request->id;
            $data= DB::Connection('mysql2')->table('stock_transfer_data as a')
                ->join('stock_transfer as b','a.master_id','=','b.id')
                ->select('a.*','b.tr_date')
                ->where('b.status',1)->where('a.master_id',$id)->where('b.tr_status',1)->get();



            foreach($data as $row):
                $SumQty = DB::Connection('mysql2')->selectOne('SELECT sum(qty) SumQty FROM stock_transfer_data where master_id = '.$id.'
                 and item_id="'.$row->item_id.'" group by item_id,warehouse_from,batch_code');

                $qty=ReuseableCode::get_stock($row->item_id,$row->warehouse_from,$SumQty->SumQty,$row->batch_code);
                if ($qty<0):


                    echo $row->id;
                    die;
                endif;
            endforeach;


            foreach($data as $row):

                $average_cost=ReuseableCode::average_cost_sales($row->item_id,$row->warehouse_from,$row->batch_code);
                $stock=array
                (
                    'main_id'=>$row->id,
                    'master_id'=>$row->master_id,
                    'voucher_no'=>$row->tr_no,
                    'voucher_date'=>$row->tr_date,
                    'batch_code'=>$row->batch_code,
                    'supplier_id'=>0,
                    'voucher_type'=>3,
                    'rate'=>$row->rate,
                    'sub_item_id'=>$row->item_id,
                    'qty'=>$row->qty,
                    'amount'=>$row->qty*$average_cost,
                    'status'=>1,
                    'warehouse_id'=>$row->warehouse_from,
                    'warehouse_id_from'=>$row->warehouse_from,
                    'warehouse_id_to'=>$row->warehouse_to,
                    'transfer_status'=>1,
                    'description'=>'Transfer',
                    'username'=>Auth::user()->username,
                    'created_date'=>date('Y-m-d'),
                    'created_date'=>date('Y-m-d'),

                    'opening'=>0,
                );
                DB::Connection('mysql2')->table('stock')->insert($stock);


                $stock1=array
                (
                    'main_id'=>$row->id,
                    'master_id'=>$row->master_id,
                    'voucher_no'=>$row->tr_no,
                    'voucher_date'=>$row->tr_date,
                    'batch_code'=>$row->batch_code,
                    'supplier_id'=>0,
                    'voucher_type'=>1,
                    'rate'=>$row->rate,
                    'sub_item_id'=>$row->item_id,
                    'qty'=>$row->qty,
                    'amount'=>$row->qty*$average_cost,
                    'status'=>1,
                    'warehouse_id'=>$row->warehouse_to,
                    'warehouse_id_from'=>$row->warehouse_from,
                    'warehouse_id_to'=>$row->warehouse_to,
                    'transfer_status'=>1,
                    'description'=>'Transfer',
                    'username'=>Auth::user()->username,
                    'created_date'=>date('Y-m-d'),
                    'created_date'=>date('Y-m-d'),
                    'opening'=>0,
                );
                DB::Connection('mysql2')->table('stock')->insert($stock1);

            endforeach;

            $update_data=array
            (
                'tr_status'=>2,
            );
            DB::Connection('mysql2')->table('stock_transfer')->where('id', $id)->update($update_data);
            echo 0;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }


    }



    public function internal_cosum(Request $request)
    {


        DB::Connection('mysql2')->beginTransaction();

        try {

            $id= $request->id;
            $data= DB::Connection('mysql2')->table('internal_consumtion_data as a')
                ->join('internal_consumtion as b','a.master_id','=','b.id')
                ->select('a.*','b.voucher_date')
                ->where('b.status',1)->where('a.master_id',$id)->where('b.voucher_status',1)->get();



            foreach($data as $row):
                $SumQty = DB::Connection('mysql2')->selectOne('SELECT sum(qty) SumQty FROM internal_consumtion_data where master_id = '.$id.'
                 and item_id="'.$row->item_id.'" group by item_id,warehouse_from,batch_code');

                $qty=ReuseableCode::get_stock($row->item_id,$row->warehouse_from,$SumQty->SumQty,$row->batch_code);
                if ($qty<0):


                    echo $row->id;
                    die;
                endif;
            endforeach;

            $total_amount=0;
            foreach($data as $row):

                $average_cost=ReuseableCode::average_cost_sales($row->item_id,$row->warehouse_from,$row->batch_code);
                $stock=array
                (
                    'main_id'=>$row->id,
                    'master_id'=>$row->master_id,
                    'voucher_no'=>$row->voucher_no,
                    'voucher_date'=>$row->voucher_date,
                    'batch_code'=>$row->batch_code,
                    'supplier_id'=>0,
                    'voucher_type'=>8,
                    'rate'=>$row->rate,
                    'sub_item_id'=>$row->item_id,
                    'qty'=>$row->qty,
                    'amount'=>$row->qty*$average_cost,
                    'status'=>1,
                    'warehouse_id'=>$row->warehouse_from,
                    'transfer_status'=>0,
                    'description'=>'Internal Consumption',
                    'username'=>Auth::user()->username,
                    'created_date'=>date('Y-m-d'),
                    'opening'=>0,
                );
                DB::Connection('mysql2')->table('stock')->insert($stock);

                $amount=$row->qty*$average_cost;

                $transaction=new Transactions();
                $transaction=$transaction->SetConnection('mysql2');
                $transaction->voucher_no=$row->voucher_no;
                $transaction->v_date=$row->voucher_date;
                $transaction->acc_id=$row->acc_id;
                $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($row->acc_id);
                $transaction->particulars=$request->desc;
                $transaction->opening_bal=0;
                $transaction->debit_credit=1;
                $transaction->amount=$amount;
                $transaction->username=Auth::user()->name;;
                $transaction->status=1;
                $transaction->voucher_type=15;
                $transaction->date=date('Y-m-d');
                $transaction->save();
                $total_amount+=$amount;
            endforeach;

            $transaction=new Transactions();
            $transaction=$transaction->SetConnection('mysql2');
            $transaction->voucher_no=$row->voucher_no;
            $transaction->v_date=$row->voucher_date;
            $transaction->acc_id=97;
            $transaction->acc_code=FinanceHelper::getAccountCodeByAccId(97);
            $transaction->particulars=$request->desc;
            $transaction->opening_bal=0;
            $transaction->debit_credit=0;
            $transaction->amount=$total_amount;
            $transaction->username=Auth::user()->name;;
            $transaction->status=1;
            $transaction->date=date('Y-m-d');
            $transaction->voucher_type=15;
            $transaction->save();

            $update_data=array
            (
                'voucher_status'=>2,
            );
            DB::Connection('mysql2')->table('internal_consumtion')->where('id', $id)->update($update_data);
            echo 0;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }


    }

    public static function getVendorWiseOpeningData(Request $request)
    {
        $VendorId = $request->VendorId;
        ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Sr No.</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center" >Pi No</th>
                                        <th class="text-center">Po No</th>
                                        <th class="text-center">Invoice Amount</th>
                                        <th class="text-center">Balance Amount</th>
                                        <th class="text-center">Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $OpeningData = DB::Connection('mysql2')->table('vendor_opening_balance')->where('vendor_id',$VendorId)->get();
                                    $Counter =1;
                                    $TotalAmount = 0;
                                    $TotalBalance = 0;
                                    foreach($OpeningData as $Fil):
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $Counter++;?></td>
                                            <td>
                                                <span id="TdDate<?php echo $Fil->id?>"><?php echo CommonHelper::changeDateFormat($Fil->date);?></span>
                                                <span style="display: none;" id="InputDate<?php echo $Fil->id?>"><input type="date" id="UpdateDate<?php echo $Fil->id?>" name="UpdateDate<?php echo $Fil->id?>" value="<?php echo $Fil->date?>" class="form-control">
                                                    <span id="Error1<?php echo $Fil->id?>"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <span id="TdPiNo<?php echo $Fil->id?>"><?php echo $Fil->pi_no?></span>
                                                <span style="display: none;" id="InputPiNo<?php echo $Fil->id?>"><input type="text" id="UpdatePiNo<?php echo $Fil->id?>" class="form-control" value="<?php echo $Fil->pi_no?>">
                                                    <span id="Error2<?php echo $Fil->id?>"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <span id="TdPoNo<?php echo $Fil->id?>"><?php echo $Fil->po_no?></span>
                                                <span style="display: none;" id="InputPoNo<?php echo $Fil->id?>"><input type="text" id="UpdatePoNo<?php echo $Fil->id?>" class="form-control" value="<?php echo $Fil->po_no?>">
                                                    <span id="Error3<?php echo $Fil->id?>"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <span id="TdInvoiceAmount<?php echo $Fil->id?>"><?php echo number_format($Fil->invoice_amount,2); $TotalAmount+=$Fil->invoice_amount?></span>
                                                <span style="display: none" id="InputInvoiceAmount<?php echo $Fil->id?>"><input type="number" class="form-control" id="UpdateInvoiceAmount<?php echo $Fil->id?>" value="<?php echo $Fil->invoice_amount?>">
                                                    <span id="Error4<?php echo $Fil->id?>"></span>
                                                </span>
                                            <td>
                                                <?php  $TotalBalance+=$Fil->balance_amount?>
                                                <span id="TdBalanceAmount<?php echo $Fil->id?>"><?php echo number_format($Fil->balance_amount,2);?></span>
                                                <span style="display: none;" id="InputBalanceAmount<?php echo $Fil->id?>"><input type="number" class="form-control" id="UpdateBalanceAmount<?php echo $Fil->id?>" value="<?php echo $Fil->balance_amount?>">
                                                <span id="Error5<?php echo $Fil->id?>"></span>
                                                </span>
                                            </td>
                                            <td class="text-left">
                                                <button type="button" class="btn btn-xs btn-primary" id="BtnEdit<?php echo $Fil->id?>" onclick="EditVendorOpening('<?php echo $Fil->id?>')">Edit</button>
                                                <button type="button" class="btn btn-xs btn-success" id="BtnUpdate<?php echo $Fil->id?>" style="display: none;" onclick="UpdateVendorOpening('<?php echo $Fil->id?>')">Update
                                                    <span id="Loading<?php echo $Fil->id?>"></span></button>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                    <tr class="text-center">
                                        <td colspan="4"></td>
                                        <td><?php echo number_format($TotalAmount,2)?></td>
                                        <td><?php echo number_format($TotalBalance,2)?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script !src="">
            function EditVendorOpening(Id)
            {
                $('#TdDate'+Id).css('display','none');
                $('#InputDate'+Id).css('display','block');
                $('#TdPiNo'+Id).css('display','none');
                $('#InputPiNo'+Id).css('display','block');
                $('#TdPoNo'+Id).css('display','none');
                $('#InputPoNo'+Id).css('display','block');
                $('#TdInvoiceAmount'+Id).css('display','none');
                $('#InputInvoiceAmount'+Id).css('display','block');
                $('#TdBalanceAmount'+Id).css('display','none');
                $('#InputBalanceAmount'+Id).css('display','block');
                $('#BtnEdit'+Id).css('display','none');
                $('#BtnUpdate'+Id).css('display','block');
            }
            function UpdateVendorOpening(Id)
            {
                var UpdateDate = $('#UpdateDate'+Id).val();
                var UpdatePiNo = $('#UpdatePiNo'+Id).val();
                var UpdatePoNo = $('#UpdatePoNo'+Id).val();
                var UpdateInvoiceAmount = $('#UpdateInvoiceAmount'+Id).val();
                var UpdateBalanceAmount = $('#UpdateBalanceAmount'+Id).val();
                var VendorId = $('#vendor_id').val();
                if(UpdateDate !="" && UpdatePiNo !="" && UpdatePoNo != "" && UpdateInvoiceAmount !="" && UpdateBalanceAmount !="")
                {
                    $('#BtnUpdate'+Id).prop('disabled',true);
                    $('#Loading'+Id).html('<img src="<?php echo url('assets/img/loading.gif')?>" alt="">');
                    $.ajax({
                        url: '<?php echo url('/')?>/stdc/UpdateVendorOpening',
                        type: "GET",
                        data: {VendorId:VendorId,Id:Id,UpdateDate:UpdateDate,UpdatePiNo:UpdatePiNo,UpdatePoNo:UpdatePoNo,UpdateInvoiceAmount:UpdateInvoiceAmount,UpdateBalanceAmount:UpdateBalanceAmount},
                        success:function(data) {

                            $('#BtnUpdate'+Id).prop('disabled',false);
                            $('#TdDate'+Id).css('display','block');
                            $('#TdDate'+Id).html(UpdateDate);
                            $('#InputDate'+Id).css('display','none');

                            $('#TdPiNo'+Id).css('display','block');
                            $('#TdPiNo'+Id).html(UpdatePiNo);
                            $('#InputPiNo'+Id).css('display','none');

                            $('#TdPoNo'+Id).css('display','block');
                            $('#TdPoNo'+Id).html(UpdatePoNo);
                            $('#InputPoNo'+Id).css('display','none');

                            $('#TdInvoiceAmount'+Id).css('display','block');
                            $('#TdInvoiceAmount'+Id).html(UpdateInvoiceAmount);
                            $('#InputInvoiceAmount'+Id).css('display','none');

                            $('#TdBalanceAmount'+Id).css('display','block');
                            $('#TdBalanceAmount'+Id).html(UpdateBalanceAmount);
                            $('#InputBalanceAmount'+Id).css('display','none');

                            $('#BtnEdit'+Id).css('display','block');
                            $('#BtnUpdate'+Id).css('display','none');
                            $('#Loading'+Id).html('');
                        }
                    });
                }
                else
                {
                    if(UpdateDate !=""){$('#Error1'+Id).html('');}else{$('#Error1'+Id).html('<p class="text-danger">field required</p>');}
                    if(UpdatePiNo !=""){$('#Error2'+Id).html('');}else{$('#Error2'+Id).html('<p class="text-danger">field required</p>');}
                    if(UpdatePoNo !=""){$('#Error3'+Id).html('');}else{$('#Error3'+Id).html('<p class="text-danger">field required</p>');}
                    if(UpdateInvoiceAmount !=""){$('#Error4'+Id).html('');}else{$('#Error4'+Id).html('<p class="text-danger">field required</p>');}
                    if(UpdateBalanceAmount !=""){$('#Error5'+Id).html('');}else{$('#Error5'+Id).html('<p class="text-danger">field required</p>');}
                }


            }
        </script>
        <?php
    }

    public static function getBuyerWiseOpeningData(Request $request)
    {
        $BuyerId = $request->CustomerId;
        ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Sr No.</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center" >Si No</th>
                                        <th class="text-center">So No</th>
                                        <th class="text-center">Invoice Amount</th>
                                        <th class="text-center">Balance Amount</th>
                                        <th class="text-center">Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $OpeningData = DB::Connection('mysql2')->table('customer_opening_balance')->where('buyer_id',$BuyerId)->get();
                                    $Counter =1;
                                    $TotalAmount = 0;
                                    $TotalBalance = 0;
                                    foreach($OpeningData as $Fil):
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $Counter++;?></td>
                                            <td>
                                                <span id="TdDate<?php echo $Fil->id?>"><?php echo CommonHelper::changeDateFormat($Fil->date);?></span>
                                                <span style="display: none;" id="InputDate<?php echo $Fil->id?>"><input type="date" id="UpdateDate<?php echo $Fil->id?>" name="UpdateDate<?php echo $Fil->id?>" value="<?php echo $Fil->date?>" class="form-control">
                                                    <span id="Error1<?php echo $Fil->id?>"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <span id="TdSiNo<?php echo $Fil->id?>"><?php echo $Fil->si_no?></span>
                                                <span style="display: none;" id="InputSiNo<?php echo $Fil->id?>"><input type="text" id="UpdateSiNo<?php echo $Fil->id?>" class="form-control" value="<?php echo $Fil->si_no?>">
                                                    <span id="Error2<?php echo $Fil->id?>"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <span id="TdSoNo<?php echo $Fil->id?>"><?php echo $Fil->so_no?></span>
                                                <span style="display: none;" id="InputSoNo<?php echo $Fil->id?>"><input type="text" id="UpdateSoNo<?php echo $Fil->id?>" class="form-control" value="<?php echo $Fil->so_no?>">
                                                    <span id="Error3<?php echo $Fil->id?>"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <span id="TdInvoiceAmount<?php echo $Fil->id?>"><?php echo number_format($Fil->invoice_amount,2); $TotalAmount+=$Fil->invoice_amount?></span>
                                                <span style="display: none" id="InputInvoiceAmount<?php echo $Fil->id?>"><input type="number" class="form-control" id="UpdateInvoiceAmount<?php echo $Fil->id?>" value="<?php echo $Fil->invoice_amount?>">
                                                    <span id="Error4<?php echo $Fil->id?>"></span>
                                                </span>
                                            <td>
                                                <?php  $TotalBalance+=$Fil->balance_amount?>
                                                <span id="TdBalanceAmount<?php echo $Fil->id?>"><?php echo number_format($Fil->balance_amount,2);?></span>
                                                <span style="display: none;" id="InputBalanceAmount<?php echo $Fil->id?>"><input type="number" class="form-control" id="UpdateBalanceAmount<?php echo $Fil->id?>" value="<?php echo $Fil->balance_amount?>">
                                                <span id="Error5<?php echo $Fil->id?>"></span>
                                                </span>
                                            </td>
                                            <td class="text-left">
                                                <button type="button" class="btn btn-xs btn-primary" id="BtnEdit<?php echo $Fil->id?>" onclick="EditBueryOpening('<?php echo $Fil->id?>')">Edit</button>
                                                <button type="button" class="btn btn-xs btn-success" id="BtnUpdate<?php echo $Fil->id?>" style="display: none;" onclick="UpdateBuyerOpening('<?php echo $Fil->id?>')">Update
                                                    <span id="Loading<?php echo $Fil->id?>"></span></button>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                    <tr class="text-center">
                                        <td colspan="4"></td>
                                        <td><?php echo number_format($TotalAmount,2)?></td>
                                        <td><?php echo number_format($TotalBalance,2)?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script !src="">
            function EditBueryOpening(Id)
            {
                $('#TdDate'+Id).css('display','none');
                $('#InputDate'+Id).css('display','block');
                $('#TdSiNo'+Id).css('display','none');
                $('#InputSiNo'+Id).css('display','block');
                $('#TdSoNo'+Id).css('display','none');
                $('#InputSoNo'+Id).css('display','block');
                $('#TdInvoiceAmount'+Id).css('display','none');
                $('#InputInvoiceAmount'+Id).css('display','block');
                $('#TdBalanceAmount'+Id).css('display','none');
                $('#InputBalanceAmount'+Id).css('display','block');
                $('#BtnEdit'+Id).css('display','none');
                $('#BtnUpdate'+Id).css('display','block');
            }
            function UpdateBuyerOpening(Id)
            {
                var UpdateDate = $('#UpdateDate'+Id).val();
                var UpdateSiNo = $('#UpdateSiNo'+Id).val();
                var UpdateSoNo = $('#UpdateSoNo'+Id).val();
                var UpdateInvoiceAmount = $('#UpdateInvoiceAmount'+Id).val();
                var UpdateBalanceAmount = $('#UpdateBalanceAmount'+Id).val();
                var CustomerId = $('#customer_id').val();
                if(UpdateDate !="" && UpdateSiNo !="" && UpdateSoNo != "" && UpdateInvoiceAmount !="" && UpdateBalanceAmount !="")
                {
                    $('#BtnUpdate'+Id).prop('disabled',true);
                    $('#Loading'+Id).html('<img src="<?php echo url('assets/img/loading.gif')?>" alt="">');
                    $.ajax({
                        url: '<?php echo url('/')?>/stdc/UpdateBuyerOpening',
                        type: "GET",
                        data: {CustomerId:CustomerId,Id:Id,UpdateDate:UpdateDate,UpdateSiNo:UpdateSiNo,UpdateSoNo:UpdateSoNo,UpdateInvoiceAmount:UpdateInvoiceAmount,UpdateBalanceAmount:UpdateBalanceAmount},
                        success:function(data) {

                            $('#BtnUpdate'+Id).prop('disabled',false);
                            $('#TdDate'+Id).css('display','block');
                            $('#TdDate'+Id).html(UpdateDate);
                            $('#InputDate'+Id).css('display','none');

                            $('#TdSiNo'+Id).css('display','block');
                            $('#TdSiNo'+Id).html(UpdateSiNo);
                            $('#InputSiNo'+Id).css('display','none');

                            $('#TdSoNo'+Id).css('display','block');
                            $('#TdSoNo'+Id).html(UpdateSoNo);
                            $('#InputSoNo'+Id).css('display','none');

                            $('#TdInvoiceAmount'+Id).css('display','block');
                            $('#TdInvoiceAmount'+Id).html(UpdateInvoiceAmount);
                            $('#InputInvoiceAmount'+Id).css('display','none');

                            $('#TdBalanceAmount'+Id).css('display','block');
                            $('#TdBalanceAmount'+Id).html(UpdateBalanceAmount);
                            $('#InputBalanceAmount'+Id).css('display','none');

                            $('#BtnEdit'+Id).css('display','block');
                            $('#BtnUpdate'+Id).css('display','none');
                            $('#Loading'+Id).html('');
                        }
                    });
                }
                else
                {
                    if(UpdateDate !=""){$('#Error1'+Id).html('');}else{$('#Error1'+Id).html('<p class="text-danger">field required</p>');}
                    if(UpdateSiNo !=""){$('#Error2'+Id).html('');}else{$('#Error2'+Id).html('<p class="text-danger">field required</p>');}
                    if(UpdateSoNo !=""){$('#Error3'+Id).html('');}else{$('#Error3'+Id).html('<p class="text-danger">field required</p>');}
                    if(UpdateInvoiceAmount !=""){$('#Error4'+Id).html('');}else{$('#Error4'+Id).html('<p class="text-danger">field required</p>');}
                    if(UpdateBalanceAmount !=""){$('#Error5'+Id).html('');}else{$('#Error5'+Id).html('<p class="text-danger">field required</p>');}
                }


            }
        </script>
        <?php
    }

    public  static function UpdateBuyerOpening(Request $request)
    {
        $CustomerId = $request->CustomerId;
        $Id = $request->Id;
        $UpdateDate = $request->UpdateDate;
        $UpdateSiNo = $request->UpdateSiNo;
        $UpdateSoNo = $request->UpdateSoNo;
        $UpdateInvoiceAmount = $request->UpdateInvoiceAmount;
        $UpdateBalanceAmount = $request->UpdateBalanceAmount;
        $UpdateData['buyer_id'] = $CustomerId;
        $UpdateData['date'] = $UpdateDate;
        $UpdateData['si_no'] = $UpdateSiNo;
        $UpdateData['so_no'] = $UpdateSoNo;
        $UpdateData['invoice_amount'] = $UpdateInvoiceAmount;
        $UpdateData['balance_amount'] = $UpdateBalanceAmount;
        DB::Connection('mysql2')->table('customer_opening_balance')->where('id',$Id)->update($UpdateData);
        ReuseableCode::insert_si($request->UpdateSiNo);
        echo 'ok';
    }

    public  static function UpdateVendorOpening(Request $request)
    {
        $VendorId = $request->VendorId;
        $Id = $request->Id;
        $UpdateDate = $request->UpdateDate;
        $UpdatePiNo = $request->UpdatePiNo;
        $UpdatePoNo = $request->UpdatePoNo;
        $UpdateInvoiceAmount = $request->UpdateInvoiceAmount;
        $UpdateBalanceAmount = $request->UpdateBalanceAmount;
        $UpdateData['vendor_id'] = $VendorId;
        $UpdateData['date'] = $UpdateDate;
        $UpdateData['pi_no'] = $UpdatePiNo;
        $UpdateData['po_no'] = $UpdatePoNo;
        $UpdateData['invoice_amount'] = $UpdateInvoiceAmount;
        $UpdateData['balance_amount'] = $UpdateBalanceAmount;
        DB::Connection('mysql2')->table('vendor_opening_balance')->where('id',$Id)->update($UpdateData);

      

        ReuseableCode::hit_ledger_vendor_opening($VendorId);
        ReuseableCode::insert_pv($request->UpdatePiNo);

        echo 'ok';
    }

    public function getPoDataPoNoWise(Request $request)
    {
        $PoNo = $request->PoNo;
        $m = $request->m;
        $purchaseRequestDetail = DB::Connection('mysql2')->table('purchase_request')->where('status',1)->where('purchase_request_no','like', '%' . $PoNo . '%')->get();
        return view('Store.AjaxPages.getPoDataPoNoWise', compact('purchaseRequestDetail','m'));
    }

    public function approveIssuance(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {

            $id= $request->id;
            $data= DB::Connection('mysql2')->table('issuance_data as a')
                ->join('issuance as b','a.master_id','=','b.id')
                ->select('a.*','b.iss_date')
                ->where('b.status',1)->where('a.master_id',$id)->where('b.issuance_status',1)->get();
            foreach($data as $row):

                $stock=array
                (
                    'main_id'=>$row->id,
                    'master_id'=>$row->master_id,
                    'voucher_no'=>$row->iss_no,
                    'voucher_date'=>$row->iss_date,
                    'batch_code'=>$row->batch_code,
                    'supplier_id'=>0,
                    'voucher_type'=>7,
                    'rate'=>0,
                    'sub_item_id'=>$row->sub_item_id,
                    'qty'=>$row->qty,
                    'amount'=>0,
                    'status'=>1,
                    'warehouse_id'=>$row->warehouse_id,
                    'warehouse_id_from'=>0,
                    'warehouse_id_to'=>0,
                    'transfer_status'=>0,
                    'description'=>'Issuance',
                    'username'=>Auth::user()->username,
                    'created_date'=>date('Y-m-d'),
                    'created_date'=>date('Y-m-d'),
                    'opening'=>0,
                );
                DB::Connection('mysql2')->table('stock')->insert($stock);

            endforeach;

            $update_data=array
            (
                'issuance_status'=>2,
            );
            DB::Connection('mysql2')->table('issuance')->where('id', $id)->update($update_data);
            echo 0;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }


    }

    public function delete_issue(Request $request)
    {
        $data['status']=0;
        DB::Connection('mysql2')->table('stock')->where('voucher_no', $request->voucher_no)->update($data);

        $count=DB::Connection('mysql2')->table('issuence_for_production')->where('voucher_no', $request->voucher_no)->where('status',1)->count();

        if($count>0):
            return 0;
        die;
        endif;


        DB::Connection('mysql2')->table('product_creation')->where('voucher_no', $request->voucher_no)->update($data);
        DB::Connection('mysql2')->table('product_creation_data')->where('voucher_no', $request->voucher_no)->update($data);
        DB::Connection('mysql2')->table('transactions')->where('voucher_no', $request->voucher_no)->update($data);
        echo $request->voucher_no;
    }


    public function view_internal_consumtion_detail()
    {
        return View('Store.AjaxPages.view_internal_consumtion_detail');
    }

    public function delete_internal_cons(Request $request)
    {
        $id=$request->id;
        $voucher_no=$request->voucher_no;

        DB::Connection('mysql2')->table('internal_consumtion')
            ->where('id', $id)
            ->update(['status' => 0]);


        DB::Connection('mysql2')->table('internal_consumtion_data')
            ->where('master_id', $id)
            ->update(['status' => 0]);


        DB::Connection('mysql2')->table('stock')
            ->where('voucher_no', $voucher_no)
            ->update(['status' => 0]);


        DB::Connection('mysql2')->table('transactions')
            ->where('voucher_no', $voucher_no)
            ->update(['status' => 0 ]);

        echo $id;
    }

    public function getPendingPurchaseRequest(Request $request)
    {
        return $quotations = DB::Connection('mysql2')->table('quotation AS q')->join('quotation_data AS qd', 'q.id','=', 'qd.master_id')->where([['q.status','=', 1],['q.quotation_status','=', 2],['qd.quotation_status','=', 1]])->select('q.id','q.voucher_no')->distinct('q.id')->get();
    }

}