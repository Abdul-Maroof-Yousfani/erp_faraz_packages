<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\ProductionHelper;
use Auth;
use DB;
use Config;
use App\Models\Department;
use App\Models\Category;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\Subitem;
use App\Models\Demand;
use Input;
use Yajra\DataTables\DataTables;
class StoreController extends Controller
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
    public function toDayActivity()
    {
        return view('Store.toDayActivity');
    }

    public  function viewDemandList(){
        return view('Store.viewDemandList');
    }
    public  function scReportPage(){
        return view('Store.scReportPage');
    }
    public  function getDataScReportAjax(Request $request){
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $VoucherType = $request->VoucherType;
        return view('Store.getDataScReportAjax',compact('FromDate','ToDate','VoucherType'));
    }



    public  function inventoryActivityPage(){
        return view('Store.inventoryActivityPage');
    }
    public  function inventoryActivityAjax(){
        return view('Store.inventoryActivityAjax');
    }


    public  function stock_transfer_form(){
        return view('Store.stock_transfer_form');
    }
    public  function stock_transfer_list(){
        return view('Store.stock_transfer_list');
    }

    public  function itemWiseOpening(){
        $OpeningItemWise = DB::Connection('mysql2')->table('stock')->where('opening',1)->where('status',1)->where('voucher_type',1)->where('amount','>',0)->where('warehouse_id','!=',0)->get();
        return view('Store.itemWiseOpening',compact('OpeningItemWise'));
    }

    public function editStockTransferForm($id,$Trno){

        $Master = DB::Connection('mysql2')->table('stock_transfer')->where('status',1)->where('id',$id)->first();
        $Detail = DB::Connection('mysql2')->table('stock_transfer_data')->where('status',1)->where('master_id',$id)->get();
        return view('Store.editStockTransferForm',compact('Master','Detail'));
    }

    public  function itemCostClassification(){
        $Subitem= new Subitem();
        $Subitem=$Subitem->SetConnection('mysql2');
        $Subitem=$Subitem->where('status',1)->get();
        
        $item_cost_classification = DB::Connection('mysql2')->table('item_cost_classification')->get();

        return view('Store.itemCostClassification',compact('Subitem','item_cost_classification'));
    }

    public function createStoreChallanForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.createStoreChallanForm',compact('departments'));
    }
    public function createIssuanceForm()
    {
        return view('Store.createIssuanceForm');
    }
    public function editIssuanceForm()
    {
        return view('Store.editIssuanceForm');
    }

    public function issuanceList(){
        return view('Store.issuanceList',compact('data'));
    }


    // public  function viewStoreChallanList(){
    //     return view('Store.viewStoreChallanList');
    // }

    public  function viewStoreChallanList(Request $request)
    {  
        $fromDate = $request->fromDate ? date("Y-m-d", strtotime($request->fromDate)) : date('Y-m-01');
        $toDate =  $request->fromDate ? date("Y-m-d", strtotime($request->toDate)) : date('Y-m-t');
        $datas = DB::Connection('mysql2')->table('store_challan as sc')
            ->leftJoin('material_requests as mr','sc.material_request_no','=','mr.material_request_no')
            // ->leftJoin('job_cards as jc','mr.job_card_id','=','jc.id')
            ->select('sc.id','sc.material_request_no','sc.material_request_date','sc.store_challan_no','sc.store_challan_date','sc.store_challan_status','sc.description', DB::raw('1 as type'))
            ->where('sc.status', 1)
            ->whereBetween('sc.store_challan_date', [$fromDate, $toDate]);
            
            $material_requisitions = DB::connection('mysql2')->table('material_requisitions')
            ->join('material_requisition_datas', 'material_requisition_datas.mr_id', '=', 'material_requisitions.id')
            ->select('material_requisitions.id', 'material_requisitions.mr_no as material_request_no', 'material_requisitions.mr_date as material_request_date', DB::raw('null as store_challan_no'), DB::raw('null as store_challan_date'), DB::raw('2 as store_challan_status'), DB::raw('null as description'), DB::raw('2 as type'))
            ->where('material_requisitions.status', 1)
            ->whereBetween('material_requisitions.mr_date', [$fromDate, $toDate])
            ->groupBy('material_requisition_datas.mr_id');
        $result = $datas->union($material_requisitions)->get();
        
       
            
        if ($request->ajax()) {
            $data = $result;
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('store_challan_date', function($data){
                    return CommonHelper::changeDateFormat($data->store_challan_date);   
                })
                ->addColumn('material_request_date', function($data){
                    return CommonHelper::changeDateFormat($data->material_request_date);   
                })
                ->addColumn('store_challan_status', function($data){
                    if($data->store_challan_status == 2):
                        return 'Approved';
                    else:
                        return 'Pending';
                    endif;
                })
                ->addColumn('action', function($data){                                                                                                        
                    return view('Store.StoreChallan.indexListAction', compact('data'));
                })                
                ->rawColumns(['action'])
                ->make(true);
        }              
        return view('Store.viewStoreChallanList');
    }


    public  function editStoreChallanVoucherForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.AjaxPages.editStoreChallanVoucherForm',compact('departments'));
    }

    public function createPurchaseRequestForm()
    {
        $departments = Department::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $pr_no = DB::connection('mysql2')->table('demand AS d')->join('demand_data AS dd', 'd.id','=', 'dd.master_id')
            ->leftJoin('quotation_data AS qd', 'qd.pr_data_id', '=', 'dd.id')
            ->leftJoin('purchase_request_data AS prd', 'dd.id', '=', 'prd.demand_data_id')
            ->where([
                ['d.status', '=', 1],
                ['d.demand_status', '=', 2],
                ['d.quotation_approve', '=', 0]
            ])
            ->whereNull('prd.demand_data_id')
            ->whereNull('qd.id')
            ->select('d.id', 'd.demand_no')
            ->distinct() 
            ->get();
        return view('Store.createPurchaseRequestForm',compact('departments','pr_no'));
    }



    public  function viewPurchaseRequestList(){
        return view('Store.viewPurchaseRequestList');
    }

    public  function editPurchaseRequestVoucherForm($id)
    {
        // for department
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();

        // for purchase order
        $purchase_order=new PurchaseRequest();
        $purchase_order=$purchase_order->SetConnection('mysql2');
        $purchase_order=$purchase_order->where('id',$id)->first();

        // for purchase order
        $purchase_order_data= new PurchaseRequestData();
        $purchase_order_data=$purchase_order_data->SetConnection('mysql2');
        $purchase_order_data=$purchase_order_data->where('master_id',$id)->get();

        $supplierList = DB::connection('mysql2')->table('supplier')->select('name','id','acc_id','terms_of_payment','ntn','strn','no_of_days')->where('status','=','1')->get();

        return view('Store.editPurchaseRequestVoucherForm',compact('supplierList','departments','purchase_order','purchase_order_data','id'));
    }

    public function addMoreMaterialRequestsDetailRows(){
        $counter = Input::get('counter');
       
        $id = Input::get('id');
        ?>
        <tr id="removeMaterialRequestsRows_<?php echo $id?>_<?php echo $counter?>">
            <input type="hidden" name="materialRequestDataSection[]" class="form-control requiredField materialRequestDataSection_1" id="materialRequestDataSection_<?php echo $id?>" value="0" />
            <td>
            <select style="width:100%;" name="sub_item_id[]" id="item_1"
                class="form-control select2 requiredField" required>
                <option value="">Select</option>
                <?php
                foreach (CommonHelper::get_item_by_category(7) as $item){
                ?>
                    <option value="<?php echo $item->id ?>" data-uom="<?php echo $item->uom_name ?>">
                        <?php echo $item->sub_ic ?>
                    </option>
                <?php } ?>
            </select>
            </td>
            <td>
                <input type="number" name="qty[]" id="qty_<?php echo $id?>_<?php echo $counter?>" step="0.0001" class="form-control requiredField" />
            </td>
            <td>
                <input type="text" name="sub_description[]" id="sub_description_<?php echo $id?>_<?php echo $counter?>" value="-" class="form-control requiredField" />
            </td>
            <td class="text-center"><button  onclick="removeMaterialRequestsRows('<?php echo $id?>','<?php echo $counter?>')" class="btn btn-xs btn-danger">Remove</button></td>
        </tr>
        <script>
            $(function () {
                $("select").select2();
            });
        </script>
        <?php
    }

    public  function addMaterialRequestForm()
    {
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->select('id','department_name')->orderBy('id')->get();
        
        return view('Store.addMaterialRequestForm', compact('departments'));
    }

    public  function editMaterialRequestForm($id)
    {

        
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->select('id','department_name')->orderBy('id')->get();
      
        $material_requests = DB::connection('mysql2')->table('material_requests')->where([ ['id',$id] , ['status', 1] ])->first();

        return view('Store.editMaterialRequestForm', compact('departments','material_requests'));
    }

    public  function viewMaterialRequestList(Request $request)
    {
        
        $fromDate = $request->fromDate ? date("Y-m-d", strtotime($request->fromDate)) : date('Y-m-01');
        $toDate =  $request->fromDate ? date("Y-m-d", strtotime($request->toDate)) : date('Y-m-t');
        $datas = DB::Connection('mysql2')->table('material_requests as mr')
            // ->leftJoin('job_cards as jc','mr.job_card_id','=','jc.id')
            ->select('mr.*')
            ->where('mr.status', 1)
            ->whereBetween('mr.material_request_date', [$fromDate, $toDate]);
        if ($request->ajax()) {
            $data = $datas;
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('material_request_date', function($data){
                    return CommonHelper::changeDateFormat($data->material_request_date);   
                })
                ->addColumn('material_request_status', function($data){
                    if($data->material_request_status == 2):
                        return 'Approved';
                    else:
                        return 'Pending';
                    endif;
                })
                ->addColumn('action', function($data){                                                                                                        
                    return view('Store.MaterialRequest.indexListAction', compact('data'));
                })                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Store.viewMaterialRequestList');
    }


    public  function addStoreChallanForm()
    {
       
        $materialRequestDatas = DB::connection('mysql2')->table('material_request_datas')
            ->select(
                'material_requests.id',
                'material_request_datas.material_request_no',
                'material_request_datas.material_request_date',
                'material_requests.sub_department_id',
                'material_requests.required_date',
                'material_requests.username',
                // 'jc.job_card_no',
                // 'jc.description as job_card_description'
            )
            ->join('material_requests', 'material_request_datas.material_request_no', '=', 'material_requests.material_request_no')
            // ->join('job_cards as jc','material_requests.job_card_id','=','jc.id')
            ->where('material_request_datas.store_challan_status', '=', '1')
            ->where('material_request_datas.material_request_status', '=', '2')
            ->groupBy('material_request_datas.material_request_no')
            ->get();
        return view('Store.addStoreChallanForm', compact('materialRequestDatas'));
    }

    public  function editStoreChallanForm($material_request_no,$material_request_date,$store_challan_no)
    {

        $materialRequestDatas = DB::connection('mysql2')->table('material_request_datas')
            ->select(
                'material_requests.id',
                'material_request_datas.material_request_no',
                'material_request_datas.material_request_date',
                'material_requests.sub_department_id',
                'material_requests.required_date',
                'material_requests.username'
            )
            ->join('material_requests', 'material_request_datas.material_request_no', '=', 'material_requests.material_request_no')
            // ->where('material_request_datas.store_challan_status', '=', '1')
            ->where('material_request_datas.material_request_status', '=', '2')
            ->where('material_request_datas.material_request_no', $material_request_no)
            ->where('material_request_datas.material_request_date', $material_request_date)
            ->groupBy('material_request_datas.material_request_no')
            ->get();
        
        
        
            
        return view('Store.editStoreChallanForm', compact('materialRequestDatas','store_challan_no'));
    }

    public function makeFormStoreChallanDetailByMRNo(){
        // $m = Session::get('run_company');
        $makeGetValue = explode('<*>',$_GET['mrNo']);
        $mrNo = $makeGetValue[0];
        $mrDate = $makeGetValue[1];
        
        $getMaterialRequestDetail = DB::connection('mysql2')->selectOne("select 
            material_requests.id,
            material_requests.company_id,
            material_requests.material_request_no,
            material_requests.material_request_date,
            material_requests.warehouse_id,
            material_requests.required_date,
            material_requests.description,
            material_requests.material_request_status,
            material_requests.status,
            material_requests.username,
            material_requests.user_id,
            material_requests.approve_username,
            material_requests.approve_date,
            material_requests.delete_username
        from material_requests 
        where material_requests.material_request_no = '".$mrNo."'");
        $getMaterialRequestDataDetail = DB::connection('mysql2')->select("select
            material_request_datas.id,
            material_request_datas.company_id,
            material_request_datas.material_request_no,
            material_request_datas.material_request_date,
            material_request_datas.required_date,
            material_request_datas.qty,
            material_request_datas.approx_cost,
            material_request_datas.approx_sub_total,
            material_request_datas.material_request_status,
            material_request_datas.store_challan_status,
            material_request_datas.status,
            material_request_datas.date,
            material_request_datas.time,
            material_request_datas.username,
            material_request_datas.user_id,
            material_request_datas.approve_username,
            material_request_datas.delete_username,
            material_request_datas.sub_item_id,
            material_request_datas.uom_id,
            subitem.item_code,
            subitem.sub_ic,
            material_request_datas.uom_id as totalIssueQty
        from material_request_datas
        INNER JOIN subitem ON material_request_datas.sub_item_id = subitem.id
        where material_request_datas.material_request_no = '".$mrNo."' and material_request_datas.store_challan_status = 1");

        // location wise current balances array
        // $locations = Location::all();
        // $itemsBalanceLocationWise = [];
        // foreach($getMaterialRequestDataDetail as $itemKey => $gmrddRow){
        //     foreach ($locations as $key => $location) {
        //         // $itemsBalanceLocationWise[$itemKey][$location->id] = CommonFacades::checkItemWiseCurrentBalanceQtyNew($m,$gmrddRow->category_id,$gmrddRow->sub_item_id,'',date('Y-m-d'),$location->id);
        //         $itemsBalanceLocationWise[$itemKey][$location->id] = CommonFacades::stockLocationWiseSum($gmrddRow->category_id, $gmrddRow->sub_item_id, $location->id, 1);           
        //     }
        // }
        // dd($getMaterialRequestDataDetail);
        return view('Store.AjaxPages.makeFormStoreChallanDetailByMRNo',compact('getMaterialRequestDetail','getMaterialRequestDataDetail'));
        
    }

    public function makeEditFormStoreChallanDetailByMRNo(){
        // $m = Session::get('run_company');
        $makeGetValue = explode('<*>',$_GET['mrNo']);
        $mrNo = $makeGetValue[0];
        $mrDate = $makeGetValue[1];
        
        
        $store_challan_no = $_GET['store_challan_no'];
    
        
        $getMaterialRequestDetail = DB::connection('mysql2')->selectOne("select 
            material_requests.id,
            material_requests.company_id,
            material_requests.material_request_no,
            material_requests.material_request_date,
            material_requests.warehouse_id,
            material_requests.required_date,
            material_requests.description,
            material_requests.material_request_status,
            material_requests.status,
            material_requests.username,
            material_requests.user_id,
            material_requests.approve_username,
            material_requests.approve_date,
            material_requests.delete_username
        from material_requests 
        where material_requests.material_request_no = '".$mrNo."'");
        $getMaterialRequestDataDetail = DB::connection('mysql2')->select("select
            material_request_datas.id,
            material_request_datas.company_id,
            material_request_datas.material_request_no,
            material_request_datas.material_request_date,
            material_request_datas.required_date,
            material_request_datas.qty,
            material_request_datas.approx_cost,
            material_request_datas.approx_sub_total,
            material_request_datas.material_request_status,
            material_request_datas.store_challan_status,
            material_request_datas.status,
            material_request_datas.date,
            material_request_datas.time,
            material_request_datas.username,
            material_request_datas.user_id,
            material_request_datas.approve_username,
            material_request_datas.delete_username,
            material_request_datas.sub_item_id,
            material_request_datas.uom_id,
            subitem.item_code,
            subitem.sub_ic,
            material_request_datas.uom_id as totalIssueQty
        from material_request_datas
        INNER JOIN subitem ON material_request_datas.sub_item_id = subitem.id
        where material_request_datas.material_request_no = '".$mrNo."' 
        -- and material_request_datas.store_challan_status = 1");

        $data1['material_request_no'] = Input::get('mrNo');
        $data1['material_request_date'] = Input::get('mrDate');

        $store_challan_id =DB::connection('mysql2')->table('store_challan')->where([
            ['material_request_no' , $mrNo ] ,
            ['material_request_date' , $mrDate],
            ['store_challan_no' , $store_challan_no],
            ['status',1]
        ])->first();

        $store_challan_data_id = DB::connection('mysql2')->table('store_challan_data')
        ->where([
            ['store_challan_no' , $store_challan_id->store_challan_no ],
            ['status',1]
        ])
        ->get();

       
        $stock = DB::connection('mysql2')->table('stock')
        ->where([
            ['voucher_no' , $store_challan_id->store_challan_no ] ,
            ['voucher_date' , $store_challan_id->store_challan_date ] ,
            ['status',1]
        ])
        ->get();
        //  echo "<pre>";
        // print_r($getMaterialRequestDataDetail);
        // exit();
        

        return view('Store.AjaxPages.makeEditFormStoreChallanDetailByMRNo',compact('getMaterialRequestDetail','getMaterialRequestDataDetail','store_challan_id','store_challan_data_id','stock'));
        
    }

    public function getBatchCodes(){
        
        $sub_item_id=Input::get('sub_item_id');
        $warehouse_id=Input::get('ware_house_id');
        $batch_codes = ReuseableCode::get_bacth_code($warehouse_id,$sub_item_id);
       
        return response()->json($batch_codes);
    }

    public function getStockBatchWise(Request $request)
    {
        $stock = ReuseableCode::get_stock($request->item_id, $request->warehouse_id, 0, $request->batch_code);

        $batchDetail = DB::connection('mysql2')->table('stock')->select('stock.batch_code', 'uom_subitem.uom_name as subitem_uom', 'stock.supplier_id', 'supplier.name as supplier_name')
        // ->join(config('database.connections.mysql.database').'.uom', 'uom.id', '=', 'stock.packing_uom')
        ->join('subitem', 'subitem.id', '=', 'stock.sub_item_id')
        ->join('supplier', 'supplier.id', '=', 'stock.supplier_id')
        ->join(config('database.connections.mysql.database').'.uom as uom_subitem', 'uom_subitem.id', '=', 'subitem.uom')
        ->where(['stock.sub_item_id'=>$request->item_id,'stock.batch_code'=>$request->batch_code])
        ->first();
        // dd($stock, $batchDetail);
        $response['current_stock'] = $stock;
        $response['batch_detail'] = $batchDetail;

        return response()->json($response);
    }

    public  function editDirectPurchaseRequestVoucherForm($id)
    {
        // for department
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->select('id','department_name')->orderBy('id')->get();

        // for purchase order
        $purchase_order=new PurchaseRequest();
        $purchase_order=$purchase_order->SetConnection('mysql2');
        $purchase_order=$purchase_order->where('id',$id)->first();

        // for purchase order
        $purchase_order_data= new PurchaseRequestData();
        $purchase_order_data=$purchase_order_data->SetConnection('mysql2');
        $purchase_order_data=$purchase_order_data->where('master_id',$id)->get();
        $supplierList = DB::Connection('mysql2')->table('supplier')->where('status',1)->get();

        return view('Store.editDirectPurchaseRequestVoucherForm',compact('departments','purchase_order','purchase_order_data','supplierList','id'));
    }


    public function createPurchaseRequestSaleForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.createPurchaseRequestSaleForm',compact('departments'));
    }

    public  function viewPurchaseRequestSaleList(){
        return view('Store.viewPurchaseRequestSaleList');
    }

    public  function editPurchaseRequestSaleVoucherForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.AjaxPages.editPurchaseRequestSaleVoucherForm',compact('departments'));
    }



    public function createStoreChallanReturnForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.createStoreChallanReturnForm',compact('departments'));
    }

    public  function viewStoreChallanReturnList(){
        return view('Store.viewStoreChallanReturnList');
    }

    public  function editStoreChallanReturnForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.AjaxPages.editStoreChallanReturnForm',compact('departments'));
    }

    public function viewDateWiseStockInventoryReport(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $categorys = new Category;
        $categorys = $categorys::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Store.viewDateWiseStockInventoryReport',compact('categorys'));
    }

    public function stockReportView()
    {

       $category= DB::Connection('mysql2')->table('stock as a')
        ->join('subitem as b','a.sub_item_id','=','b.id')
       ->join('category as c','c.id','=','b.main_ic_id')

        ->select('c.id','c.main_ic')
           ->where('a.status',1)
           ->groupBy('c.id')

       ->get();



        return view('Store.stockReportView',compact('category'));
    }
    public function stockReportBatchWiseView()
    {

        $category= DB::Connection('mysql2')->table('stock as a')
            ->join('subitem as b','a.sub_item_id','=','b.id')
            ->join('category as c','c.id','=','b.main_ic_id')

            ->select('c.id','c.main_ic')
            ->where('a.status',1)
            ->groupBy('c.id')

            ->get();

        $batch_code= DB::Connection('mysql2')->table('stock as a')
            ->select('a.batch_code')
            ->where('a.status',1)
            ->groupBy('a.batch_code')
            ->get();

        return view('Store.stockReportBatchWiseView',compact('category','batch_code'));
    }


    public function fullstockReportView()
    {
        return view('Store.fullstockReportView');
    }
    public function fullstockReportViewBatch()
    {
        return view('Store.fullstockReportViewBatch');
    }


    public function StockOpeningValuesUpdate()
    {
        $Subitem= new Subitem();
        $Subitem=$Subitem->SetConnection('mysql2');
        $Subitem=$Subitem->where('status',1)->get();
        return view('Store.StockOpeningValuesUpdate',compact('Subitem'));
    }

    public function stockDetailReport()
    {
        return view('Store.stockDetailReport');
    }

    public function InventoryStockReport()
    {
        return view('Store.InventoryStockReport');
    }
    public function checkPurchasingPage()
    {
        $SubItem = DB::Connection('mysql2')->select('select * from subitem where status = 1');
        return view('Store.checkPurchasingPage',compact('SubItem'));
    }
    public function getCheckPurchasingDataAjax()
    {
        $SubItemId = Input::get('SubItemId');
        $StockData = DB::Connection('mysql2')->select('select * from stock where status = 1 AND sub_item_id = '.$SubItemId.' and voucher_type in(1) and transfer = 0 ORDER BY voucher_date asc');
        return view('Store.AjaxPages.getCheckPurchasingDataAjax', compact('StockData'));
    }


    public function rateAndAmountupdate()
    {
        return view('Store.rateAndAmountupdate');
    }

    public function InventoryStockReportAjax()
    {
        $from_date  = $_GET['from_date'];
        $to_date    = $_GET['to_date'];
     //   $stock = DB::Connection('mysql2')->select('SELECT s.*, gd.po_data_id as po_id FROM stock s
                                               //   INNER JOIN grn_data gd ON gd.grn_no = s.voucher_no
                                                //  WHERE s.status=1 AND s.voucher_type=1 AND s.voucher_date BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');

        $stock = DB::Connection('mysql2')->select('select b.sub_item_id,a.supplier_id,a.grn_no,a.grn_date,a.type,b.region,b.region_to,b.purchase_recived_qty,b.purchase_recived_qty,b.amount,b.rate
        from goods_receipt_note a
         inner join grn_data b
         ON
         a.id=b.master_id
         where a.grn_date BETWEEN  "'.$from_date.'" and "'.$to_date.'"
         and a.status=1
         and a.grn_status in (2,3)');


        $issuence=DB::Connection('mysql2')->select('select a.iss_no,a.issuance_type,a.iss_date,a.region,b.id,b.rate,b.amount,b.sub_item_id,b.qty,a.description from issuance a
        inner join
        issuance_data b
        ON
        a.id=b.master_id
        where a.iss_date BETWEEN "'.$from_date.'" and "'.$to_date.'"
        and a.status=1
        and a.issuance_status=2
        Order by b.sub_item_id
        ');



        $return=DB::Connection('mysql2')->select('select a.issuance_no as iss_no,a.issuance_type,a.issuance_date as iss_date,a.region,b.subitem as sub_item_id,b.rate,b.amount,b.stock_return_data_id,b.qty,a.description from stock_return a
        inner join
        stock_return_data b
        ON
        a.stock_return_id=b.stock_return_id
        where a.issuance_date BETWEEN "'.$from_date.'" and "'.$to_date.'"
        and a.status=1
        and a.return_status=2');

        return view('Store.AjaxPages.InventoryStockReportAjax', compact('stock','from_date','to_date','issuence','return'));


    }

    public function rateAndAmountupdateAjax()
    {
        $from_date  = $_GET['from_date'];
        //$to_date    = $_GET['to_date'];
        $dateArray = explode('-',$from_date);
        $d=cal_days_in_month(CAL_GREGORIAN,$dateArray[1],$dateArray[0]);
        $From = $from_date.'-01';
        $To = $from_date.'-'.$d;
        $to_date = $To;


        //   $stock = DB::Connection('mysql2')->select('SELECT s.*, gd.po_data_id as po_id FROM stock s
        //   INNER JOIN grn_data gd ON gd.grn_no = s.voucher_no
        //  WHERE s.status=1 AND s.voucher_type=1 AND s.voucher_date BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');

        $stock = DB::Connection('mysql2')->select('select b.sub_item_id,a.supplier_id,a.grn_no,a.grn_date,a.type,b.region,b.region_to,b.purchase_recived_qty,b.id,b.rate,b.amount
        ,a.grn_date
        from goods_receipt_note a
         inner join grn_data b
         ON
         a.id=b.master_id
         
         where a.grn_date BETWEEN "'.$From.'" and "'.$To.'"
         and a.status=1
         and a.grn_status in (2,3)');


        $issuence=DB::Connection('mysql2')->select('select a.iss_no,a.issuance_type,a.iss_date,a.region,b.id,b.rate,b.amount,b.sub_item_id,b.qty,a.description,a.iss_date,a.region from issuance a
        inner join
        issuance_data b
        ON
        a.id=b.master_id
        where a.iss_date BETWEEN "'.$From.'" and "'.$To.'"
        and a.status=1
        and a.issuance_status=2
        Order by b.sub_item_id
        ');



        $return=DB::Connection('mysql2')->select('select a.issuance_no as iss_no,a.issuance_type,a.issuance_date as iss_date,a.region,b.subitem as sub_item_id,b.rate,b.amount,b.stock_return_data_id,b.qty,a.description,a.region from stock_return a
        inner join
        stock_return_data b
        ON
        a.stock_return_id=b.stock_return_id
        where a.issuance_date BETWEEN "'.$From.'" and "'.$To.'"
        and a.status=1
        and a.return_status=2');

        return view('Store.AjaxPages.rateAndAmountupdateAjax', compact('stock','from_date','to_date','issuence','return'));


    }



    function UpdateRateAmount()
    {
        $Id = Input::get('Id');
        $Rate = Input::get('Rate');
        $Amount = Input::get('Amount');
        $UpdateData['rate'] = $Rate;
        $UpdateData['amount'] = $Amount;
        //Grn Data And Stock update
        DB::connection('mysql2')->table('issuance_data')->where('id',$Id)->update($UpdateData);
        DB::connection('mysql2')->table('stock')->where('master_id',$Id)->where('voucher_type',2)->update($UpdateData);
    }

    function UpdateRateAmountGrn()
    {
        $Id = Input::get('Id');
        $Rate = Input::get('Rate');
        $Amount = Input::get('Amount');
        $UpdateData['rate'] = $Rate;
        $UpdateData['amount'] = $Amount;
        //Issuance Data And Stock update
        DB::connection('mysql2')->table('grn_data')->where('id',$Id)->update($UpdateData);
        DB::connection('mysql2')->table('stock')->where('master_id',$Id)->where('voucher_type',1)->update($UpdateData);
    }
    function UpdateRateAmountReturn()
    {
        $Id = Input::get('Id');
        $Rate = Input::get('Rate'); 
        $Amount = Input::get('Amount');
        $UpdateData['rate'] = $Rate;
        $UpdateData['amount'] = $Amount;
        //Return Stock Data And Stock update
        DB::connection('mysql2')->table('stock_return_data')->where('stock_return_data_id',$Id)->update($UpdateData);
        DB::connection('mysql2')->table('stock')->where('master_id',$Id)->where('voucher_type',3)->update($UpdateData);
    }

    function stockReportItemWisePage()
    {
        return view('Store.stockReportItemWisePage');
    }


    function stockReportItemWiseAjax(Request $request)
    {
        $from=$request->from;
        $to=$request->to_date;

        $data= DB::Connection('mysql2')->select('SELECT a.qty,a.rate,a.amount,b.sub_ic,b.id,b.id as sub_ic_id from stock a
        INNER JOIN
        subitem b
        ON
        a.sub_item_id=b.id
        where a.status=1
        and qty>0
        

        and a.voucher_date BETWEEN "'.$from.'" and "'.$to.'"  group by a.sub_item_id');
//        $data=DB::Connection('mysql2')->select('SELECT a.qty,a.rate,a.amount,b.sub_ic,c.region_name from stock
//        where voucher_date BETWEEN  "2020-07-01" and "2020-07-31" and status=1
//        group by sub_item_id,sub_item_id');
        return view('Store.AjaxPages.stockReportItemWiseAjax',compact('data'));
    }

     public function item_detaild_supplier_wise(Request $request)
     {
          $item=$request->sub_item_id;

       $data= DB::Connection('mysql2')->table('stock')->where('status',1)
            ->where('sub_item_id',$item)->where('voucher_type',1)->where('opening',0)->get();
         return view('Store.AjaxPages.item_detaild_supplier_wise',compact('data','item'));
     }

    public function add_opening(Request $request)
    {
  
        return view('Store.add_opening');
    }

    public function average_cost(Request $request)
    {
        $m=$request->m;
        return view('Store.average_cost',compact('m'));
    }

    public function inventory_movement()
    {


        $SubItem = DB::Connection('mysql2')->select('select a.id,a.sub_ic from subitem a
                                          INNER JOIN stock b ON b.sub_item_id = a.id
                                          WHERE a.status = 1
                                          GROUP BY b.sub_item_id');

        return view('Store.inventory_movement',compact('SubItem'));
    }

    public function inventory_movement_test()
    {


        $SubItem = DB::Connection('mysql2')->select('select a.id,a.sub_ic from subitem a
                                          INNER JOIN stock b ON b.sub_item_id = a.id
                                          WHERE a.status = 1
                                          GROUP BY b.sub_item_id');

        return view('Store.inventory_movement_test',compact('SubItem'));
    }


    public function stock_movemnet(Request $request)
    {
        ini_set('memory_limit', '-1');
        $ReportType=$request->ReportType;
        $from=$request->from_date;
        $to=$request->to_date;
        $accyeafrom=$request->accyearfrom;
        $ItemId=$request->ItemId;
        $purchase=$request->purchase;
        $sales=$request->sales;

        if($ItemId == 'all'):

        $data=DB::Connection('mysql2')->table('stock as a')
            ->join('subitem as b','a.sub_item_id','=','b.id')
            ->where('a.status',1)
            ->where('amount','>',0)
            ->select('a.*','b.sub_ic')
            ->groupby('a.sub_item_id')

            ->get();

        else:
        $data=DB::Connection('mysql2')->table('stock as a')
            ->join('subitem as b','a.sub_item_id','=','b.id')
            ->where('a.status',1)
            ->where('a.sub_item_id',$ItemId)
            ->select('a.*','b.sub_ic')
            ->groupby('a.sub_item_id')
            ->get();
        endif;
        if($ReportType == 1):
            if ($purchase==0 && $sales==0):
     
           return view('Store.AjaxPages.stock_movemnet',compact('from','to','accyeafrom','data'));
            elseif($purchase==1):
                return view('Store.AjaxPages.stock_movement_in',compact('from','to','accyeafrom','data'));

            elseif($sales==1):
                return view('Store.AjaxPages.stock_movement_out',compact('from','to','accyeafrom','data'));
            endif;

        else:

            if($ItemId == 'all'):
                $data=DB::Connection('mysql2')->table('transaction_supply_chain as a')
                    ->join('subitem as b','a.item_id','=','b.id')
                    ->where('a.status',1)
                    ->select('a.*','b.sub_ic')
                    ->groupby('a.item_id')
                    ->get();
            else:
                $data=DB::Connection('mysql2')->table('transaction_supply_chain as a')
                    ->join('subitem as b','a.item_id','=','b.id')
                    ->where('a.status',1)
                    ->where('a.item_id',$ItemId)
                    ->select('a.*','b.sub_ic')
                    ->groupby('a.item_id')
                    ->get();
            endif;
            return view('Store.AjaxPages.stock_movemnet_finance',compact('from','to','accyeafrom','data'));
        endif;

    }


    public function stock_movemnet_test(Request $request)
    {
        ini_set('memory_limit', '-1');
        $ReportType=$request->ReportType;
        $from=$request->from_date;
        $to=$request->to_date;
        $accyeafrom=$request->accyearfrom;
        $ItemId=$request->ItemId;

        if($ItemId == 'all'):
            $RecCount=DB::Connection('mysql2')->table('stock as a')
                ->join('subitem as b','a.sub_item_id','=','b.id')
                ->where('a.status',1)
                ->where('a.amount','>',0)
                ->select('a.*','b.sub_ic')
                ->groupby('a.sub_item_id')->get();

            $RecCount = $RecCount->count();

            $data = DB::Connection('mysql2')->select('select a.*,b.sub_ic from stock a
            INNER JOIN subitem b on b.id = a.sub_item_id
            WHERE a.status = 1
            and a.amount > 0
            group by a.sub_item_id limit 0,1000');


        else:
            $data=DB::Connection('mysql2')->table('stock as a')
                ->join('subitem as b','a.sub_item_id','=','b.id')
                ->where('a.status',1)
                ->where('a.sub_item_id',$ItemId)
                ->select('a.*','b.sub_ic')
                ->groupby('a.sub_item_id')->get();

        endif;

        return view('Store.AjaxPages.stock_movemnet_test',compact('from','to','accyeafrom','data','RecCount'));
    }
    public function stock_movemnetAjaxMoreData(Request $request)
    {

        $from=$request->from_date;
        $to=$request->to_date;
        $accyeafrom=$request->accyearfrom;
        $RCount=$request->RCount;
        $LmFrom=$request->QCounter;

        $data = DB::Connection('mysql2')->select('select a.*,b.sub_ic from stock a INNER JOIN subitem b on b.id = a.sub_item_id WHERE a.status = 1 and a.amount > 0 group by a.sub_item_id limit '.$LmFrom.',1000');
        
        return view('Store.AjaxPages.stock_movemnetAjaxMoreData',compact('from','to','accyeafrom','data','LmFrom'));

    }







    public function issuence_against_product(Request $request)
    {
         $id=$request->id;
        $data=DB::Connection('mysql2')->table('product_creation_data')->where('master_id',$id)->where('status',1)->get();

        $check=DB::Connection('mysql2')->table('product_creation_data')->where('status',1)->where('master_id',$id)->where('pi_no','=',null)->count();

        if ($check==0):
            echo 'Access Denied';
            die;
        endif;
        return view('Store.issuence_against_product',compact('data'));
    }

    public function inventory_movement_fi()
    {


        $SubItem = DB::Connection('mysql2')->select('select a.id,a.sub_ic from subitem a
                                          INNER JOIN transaction_supply_chain b
                                          ON b.item_id = a.id
                                          WHERE a.status = 1
                                          GROUP BY b.item_id');

        return view('Store.inventory_movement_fi',compact('SubItem'));
    }

    public function add_internal_consumtion()
    {

        return view('Store.add_internal_consumtion');
    }

    public function internal_consumtion_list()
    {

        return view('Store.internal_consumtion_list');
    }

    public function add_finish(Request $request)
    {
         $data=  $request->item;
        foreach($data as $row):
          $item_data=explode(',',$row);
          $dataa['finish_good']=$item_data[0];
          DB::Connection('mysql2')->table('subitem')->where('id',$item_data[1])->update($dataa);
            endforeach;
    }

    public function add_bom()
    {
        try {
      $data=  DB::Connection('mysql2')->table('bom_data')->get();

        foreach ($data as $row):

       if ($row->finish_good_id!='Finish Good'):
      $finish_good=DB::Connection('mysql2')->table('subitem')->where('item_code',$row->finish_good_id)->first()->id;
       $data1=array
       (
            'finish_goods'=>$finish_good,
            'description'=>$row->finish_good_id,
           'date'=>date('Y-m-d'),
           'status'=>1,
           'username'=>Auth::user()->name,
       );

  //     $id= DB::Connection('mysql2')->table('production_bom')->insertGetId($data1);


            $finish_good=DB::Connection('mysql2')->table('subitem')->where('item_code',$row->direct_material)->first()->id;
            $data2=array
            (
                'master_id'=>$id,
                'item_id'=>$finish_good,
                'qty_mm'=>$row->d_qty,
                'qty_ft'=>$row->d_qty / 304.8,
                'date'=>date('Y-m-d'),
                'status'=>1,
                'username'=>Auth::user()->name,
            );

          //  DB::Connection('mysql2')->table('production_bom_data_direct_material')->insertGetId($data2);

            if ($row->indirect_material!=''):
            $finish_good= DB::Connection('mysql2')->table('subitem')->where('item_code',$row->indirect_material)->first()->id;
            $data3=array
            (
                'main_id'=>$id,
                'item_id'=>$finish_good,
                'qty'=>$row->in_qty,
                'status'=>1,
                'username'=>Auth::user()->name,
            );

         //   DB::Connection('mysql2')->table('production_bom_data_indirect_material')->insertGetId($data3);
                endif;
            endif;
        endforeach;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            dd($e->getMessage());
        }
    }


    function add_operation_data()
    {
        try {

          $data=  DB::Connection('mysql2')->table('production_bom')->where('status',1)->get();

            foreach ($data as $row):


                $production_work_order=array
                (
                    'finish_good_id'=>$row->finish_goods,
                    'status'=>1,
                    'username'=>Auth::user()->name,
                    'date'=>date('Y-m-d'),
                );

                $id= DB::Connection('mysql2')->table('production_work_order')->insertGetId($production_work_order);

            $data1=  DB::Connection('mysql2')->table('production_machine_data')->where('status',1)->where('finish_good',$row->finish_goods)->get();


            foreach ($data1 as $row1):

                $production_work_order_data=array
                (
                    'master_id'=>$id,
                    'machine_id'=>$row1->master_id,
                    'capacity'=>70,
                    'labour_category_id'=>'',
                    'wait_time'=>'00:00:12',
                    'move_time'=>'00:00:06',
                    'que_time'=>0,
                    'status'=>1,
                    'date'=>date('Y-m-d'),
                    'username'=>Auth::user()->name,
                );
                 DB::Connection('mysql2')->table('production_work_order_data')->insert($production_work_order_data);
            endforeach;

            endforeach;


            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            dd($e->getMessage());
        }
    }

    public function Create_routing()
    {
        $data = DB::Connection('mysql2')->table('production_work_order')->where('status',1)->get();



        foreach ($data as $row):

            $data1=array
            (
                'finish_goods'=>$row->finish_good_id,
                'voucher_no'=>ProductionHelper::get_unique_code_for_routing(),
                'operation_id'=>$row->id,
                'status'=>1,
                'username'=>Auth::user()->name,
                'date'=>date('Y-m-d'),

            );
            $id= DB::Connection('mysql2')->table('production_route')->insertGetId($data1);
            $data2 = DB::Connection('mysql2')->table('production_work_order_data')->where('status',1)->where('master_id',$row->id)->get();

          $count=1;
            foreach ($data2 as $row1):
                $orderby=0;
                if ($row1->machine_id==28 || $row1->machine_id==29):
                    $orderby=0;
                    else:
                    $orderby=$count++;
                    endif;

                $data2=array
                (
                    'master_id'=>$id,
                    'operation_data_id'=>$row1->id,
                    'machine_id'=>$row1->machine_id,
                    'orderby'=>$orderby,
                    'status'=>1,

                );

                DB::Connection('mysql2')->table('production_route_data')->insert($data2);
                endforeach;
            endforeach;

    }

}
