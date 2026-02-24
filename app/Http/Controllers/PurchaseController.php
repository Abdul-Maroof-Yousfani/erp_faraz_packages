<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Issuance;
use App\Models\IssuanceData;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Config;
use App\Models\Account;
use App\Models\PurchaseVoucher;
use App\Models\Countries;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Department;
use App\Models\PurchaseRequestData;
use App\Models\SupplierInfo;
use App\Models\FinanceDepartment;
use App\Models\CostCenter;
use App\Models\UOM;
use App\Models\PurchaseVoucherData;
use App\Models\DepartmentAllocation1;
use App\Models\DemandType;
use App\Models\Warehouse;
use App\Models\GoodsReceiptNote;
use App\Models\PurchaseRequest;
use App\Models\PurchaseVoucherThroughGrn;
use App\Models\PurchaseVoucherThroughGrnData;
use App\Models\Subitem;
use App\Models\Demand;
use App\Models\DemandData;
use App\Models\GRNData;
use App\Models\JobOrder;
use App\Models\JobOrderData;
use App\Models\Product;
use App\Models\Type;
use App\Models\Conditions;
use App\Models\Survey;
use App\Models\SurveyData;
use App\Models\SurveyDocument;
use App\Models\Quotation;
use App\Models\JobOrderDocument;
use App\Models\Client;
use App\Models\Region;
use App\Models\ClientJob;
use App\Models\NewPurchaseVoucher;
use App\Models\Cluster;
use App\Models\Brand;
use App\Models\Transactions;

use App\Models\Production\Packing;
use App\Models\Production\PackingData;
use App\Models\Production\QaTest;
use App\Models\Production\QcPacking;
use App\Models\Production\QcPackingData;
use App\Models\Production\PackingQcTesting;
use App\Models\Production\QcValue;
use App\Models\Production\QcValueData;

use Session;

class PurchaseController extends Controller
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
        return view('Purchase.toDayActivity');
    }

    public function addOpeningAgainstVendorForm()
    {
        return view('Purchase.addOpeningAgainstVendorForm');
    }



    public function testReportPage()
    {
        return view('Purchase.testReportPage');
    }
    public function add_another_data_page()
    {
        $Data = DB::Connection('mysql2')->select('SELECT a.* FROM `sales_tax_invoice_data` a
                                        INNER JOIN sales_tax_invoice b ON b.id = a.master_id
                                        WHERE b.status = 1
                                        and a.dn_data_ids != 0
                                        GROUP BY dn_data_ids');
        return view('Purchase.add_another_data_page', compact('Data'));
    }





    //Dashboard
    public function inventory_page()
    {
        return view('dashboard.inventory_page');
    }
    public function purchase_page()
    {
        return view('dashboard.purchase_page');
    }
    public function sales_page()
    {
        return view('dashboard.sales_page');
    }


    //Dashboard
    public function purchaseDetailReportPage()
    {
        return view('Purchase.purchaseDetailReportPage');
    }
    public function vendor_balance_page()
    {
        return view('Purchase.vendor_balance_page');
    }

    public function viewAgingReportPage()
    {
        $Supplier = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();
        return view('Purchase.viewAgingReportPage', compact('Supplier'));
    }

    public function purchaseInvoiceReportPage()
    {
        return view('Purchase.purchaseInvoiceReportPage');
    }

    public function aqmsStockReportPage()
    {

        return view('Purchase.aqmsStockReportPage');
    }

    public function in_stock_recon()
    {
        return view('Purchase.in_stock_recon');
    }
    public function detailReportPage()
    {
        return view('Purchase.detailReportPage');
    }

    public function poTrackingPage()
    {
        $PoNo = DB::Connection('mysql2')->table('purchase_request')->where('status', 1)->select('purchase_request_no', 'id')->get();
        return view('Purchase.poTrackingPage', compact('PoNo'));
    }


    public function job_order_next_step(Request $request)
    {

        $master_id = $request->session()->get('master_id');
        $region_id = $request->session()->get('region_id');
        $m = $request->session()->get('m');
        return view('Purchase.AjaxPages.job_order_next_step', compact('master_id', 'm', 'type', 'region_id'));
    }

    public function opening_stock_report(Request $request)
    {
        $Category = new Category();
        $Category = $Category->SetConnection('mysql2')->where('status', 1)->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2')->where('status', 1)->get();

        return view('Purchase.opening_stock_report', compact('Category', 'Region'));
    }

    public function ItemWiseReport(Request $request)
    {
        //echo ""; die;
        $data = new Subitem();
        $data = $data->SetConnection('mysql2')->where('status', 1)->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2')->where('status', 1)->get();

        return view('Purchase.ItemWiseReport', compact('data', 'Region'));
    }

    public function job_order_next_step_edit(Request $request)
    {
        $master_id = $request->session()->get('master_id');
        $region_id = $request->session()->get('region_id');
        $m = $request->session()->get('m');
        return view('Purchase.AjaxPages.job_order_next_step_edit', compact('master_id', 'm', 'type', 'region_id'));
    }
    public function poReportPage()
    {
        return view('Purchase.poReportPage');
    }

    public function createstockreturn()
    {
        return view('Purchase.createstockreturn');
    }

    public function addSurveyForm()
    {
        $product = new Product();
        $product = $product->SetConnection('mysql2');
        $product = $product->where('p_status', 1)->select('*')->get();
        $type = new Type();
        $type = $type->SetConnection('mysql2');
        $type = $type->where('status', 1)->get();
        $conditions = new Conditions();
        $conditions = $conditions->SetConnection('mysql2');
        $conditions = $conditions->where('status', 1)->get();
        return view('Purchase.addSurveyForm', compact('product', 'type', 'conditions'));
    }


    public function ShowAllImages($id)
    {
        $jobOrderDocs = new JobOrderDocument();
        $jobOrderDocs = $jobOrderDocs->SetConnection('mysql2');
        $jobOrderDocs = $jobOrderDocs->where('status', 1)->where('job_order_id', $id)->get();

        return view('Purchase.ShowAllImages', compact('jobOrderDocs'));
    }





    public function createPurchaseVoucherForm()
    {

        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();



        return view('Purchase.createPurchaseVoucherForm', compact('supplier'));
    }

    public function editSubItemForm()
    {

        $id = $_GET['id'];

        // get uom
        $uom = new UOM;
        $uom = $uom->where('status', '=', '1')->select('uom_name', 'id')->get();

        // get category
        $categories = new Category;
        $categories = $categories->SetConnection('mysql2');
        $categories = $categories->where('status', '=', '1')->select('main_ic', 'id')->get();


        // get demand type

        $demand_type = new DemandType();
        $demand_type = $demand_type->SetConnection('mysql2');
        $demand_type = $demand_type->where('status', 1)->select('name', 'id')->get();


        // get sub item
        $sub_item = new Subitem();
        $sub_item = $sub_item->SetConnection('mysql2');
        $sub_item = $sub_item->where('status', 1)->where('id', $id)->select('*')->first();
        $pack_type = DB::Connection('mysql2')->table('packaging_type')->where('status', 1)->get();
        return view('Purchase.AjaxPages.editSubItemForm', compact('categories', 'uom', 'sub_item', 'demand_type', 'id', 'pack_type'));
    }


    public function viewSupplierDetail()
    {
        return view('Purchase.AjaxPages.viewSupplierDetail');
    }

    public function createCategoryForm()
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')

            ->get();
        return view('Purchase.createCategoryForm', compact('accounts'));
    }


    //saquib
    // Supplier
    public function createSupplierForm()
    {
        $countries = new Countries;
        $countries = $countries::where('status', '=', 1)->get();
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->where('status', 1)
            ->get();
        $v_code = Supplier::UniqueNo();
        return view('Purchase.createSupplierForm', compact('accounts', 'countries', 'v_code'));
    }
    public function editSupplierForm($id)
    {

        $countries = new Countries;
        $countries = $countries::where('status', '=', 1)->get();
        $supplier = Supplier::where('id', $id)->first();
        $supplier_info = SupplierInfo::where('supp_id', $id)->first();
        $transactions = Transactions::where('acc_id', $supplier->acc_id)->where('opening_bal', 1)->first();

        return view('Purchase.AjaxPages.editSupplierForm', compact('countries', 'id', 'supplier', 'supplier_info', 'transactions'));
    }

    public function viewSupplierFormlist($id)
    {
        return view('Purchase.viewSupplierFormlist', compact('id'));
    }
    public function viewSupplierList()
    {
        return view('Purchase.viewSupplierList');
    }

    //Abdul
    public function createSubCategoryForm()
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $categories = new Category;
        $categories = $categories::get();
        CommonHelper::reconnectMasterDatabase();
        return view('Purchase.createSubCategoryForm', compact('categories'));
    }
    //ABdul



    public function viewCategoryList()
    {
        return view('Purchase.viewCategoryList');
    }

    public function addRegionForm()
    {
        $Cluster = new Cluster();
        $Cluster = $Cluster->SetConnection('mysql2');
        $Cluster = $Cluster->where('status', 1)->get();

        return view('Purchase.addRegionForm', compact('Cluster'));
    }

    public function regionList()
    {
        return view('Purchase.regionList');
    }

    public function addCluster()
    {
        return view('Purchase.addCluster');
    }

    public function clusterList()
    {
        $Cluster = new Cluster();
        $Cluster = $Cluster->SetConnection('mysql2');
        $Cluster = $Cluster->where('status', 1)->get();
        return view('Purchase.clusterList', compact('Cluster'));
    }




    public function viewCategoryDetail()
    {
        return view('Purchase.AjaxPages.viewCategoryDetail');
    }

    public function editCategoryForm()
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->where('code', '=', '1-2')
            ->get();
        return view('Purchase.AjaxPages.editCategoryForm', compact('accounts'));
    }

    public function editPurchaseVoucherForm($id)
    {
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();
        $department = new FinanceDepartment();
        $department = $department->SetConnection('mysql2');
        $department = $department->where('status', 1)->select('id', 'name', 'code')
            ->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->get();

        $purchase_voucher = new PurchaseVoucher();
        $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
        $purchase_voucher = $purchase_voucher->where('id', $id)->select(
            'pv_no',
            'pv_date',
            'slip_no',
            'purchase_date',
            'purchase_type'
            ,
            'due_date',
            'supplier',
            'description',
            'currency',
            'total_net_amount',
            'amount_in_words'
        )->first();


        $purchase_voucher_data = new PurchaseVoucherData();
        $purchase_voucher_data = $purchase_voucher_data->SetConnection('mysql2');
        $purchase_voucher_data = $purchase_voucher_data->where('master_id', $id)->select(
            'id',
            'pv_no',
            'category_id',
            'sub_item',
            'uom',
            'qty'
            ,
            'rate',
            'amount',
            'sales_tax_per',
            'sales_tax_amount',
            'net_amount',
            'txt_nature',
            'income_txt_nature'
        )->orderBy('id', 'ASC')->get();

        $type = 0;

        return view('Purchase.editPurchaseVoucherForm', compact('supplier', 'department', 'purchase_voucher', 'id', 'purchase_voucher_data', 'type'));
    }

    public function editJobOrder($id)
    {
        $JobOrder = new JobOrder();
        $JobOrder = $JobOrder->SetConnection('mysql2');
        $JobOrder = $JobOrder->where('status', 1)->where('job_order_id', $id)->first();
        $uom = new UOM;
        $uom = $uom->where('status', '=', '1')->select('uom_name', 'id')->get();

        $JobOrderData = new JobOrderData();
        $JobOrderData = $JobOrderData->SetConnection('mysql2');
        $JobOrderData = $JobOrderData->where('status', 1)->where('job_order_id', $id)->get();

        $JobOrderDocument = new JobOrderDocument();
        $JobOrderDocument = $JobOrderDocument->SetConnection('mysql2');
        $JobOrderDocument = $JobOrderDocument->where('status', 1)->where('job_order_id', $id)->get();
        $EditId = $id;

        return view('Purchase.editJobOrder', compact('JobOrder', 'uom', 'JobOrderData', 'EditId', 'JobOrderDocument'));
    }

    public function editSurvey($id)
    {

        $Survey = new Survey();
        $Survey = $Survey->SetConnection('mysql2');
        $Survey = $Survey->where('status', 1)->where('survey_id', $id)->first();

        $SurveyData = new SurveyData();
        $SurveyData = $SurveyData->SetConnection('mysql2');
        $SurveyData = $SurveyData->where('status', 1)->where('survey_id', $id)->get();

        $SurveyDocument = new SurveyDocument();
        $SurveyDocument = $SurveyDocument->SetConnection('mysql2');
        $SurveyDocument = $SurveyDocument->where('status', 1)->where('survey_id', $id)->get();

        $product = new Product();
        $product = $product->SetConnection('mysql2');
        $product = $product->where('p_status', 1)->select('*')->get();
        $type = new Type();
        $type = $type->SetConnection('mysql2');
        $type = $type->where('status', 1)->get();
        $conditions = new Conditions();
        $conditions = $conditions->SetConnection('mysql2');
        $conditions = $conditions->where('status', 1)->get();


        return view('Purchase.editSurvey', compact('Survey', 'SurveyData', 'SurveyDocument', 'product', 'type', 'conditions', 'id'));
    }

    public function editGoodIssuance($id)
    {
        $Issuance = new Issuance();
        $Issuance = $Issuance->SetConnection('mysql2');
        $Issuance = $Issuance->where('status', 1)->where('id', $id)->first();

        $IssuanceData = new IssuanceData();
        $IssuanceData = $IssuanceData->SetConnection('mysql2');
        $IssuanceData = $IssuanceData->where('status', 1)->where('master_id', $id)->get();
        $JobOrder = new JobOrder();
        $JobOrder = $JobOrder->SetConnection('mysql2');
        $JobOrder = $JobOrder->where('status', 1)->get();

        return view('Purchase.editGoodIssuance', compact('Issuance', 'IssuanceData', 'id', 'JobOrder'));
    }

    public function editStockReturn($id)
    {
        $stock_return = DB::Connection('mysql2')->table('stock_return')->where('status', 1)->where('stock_return_id', $id)->first();
        $stock_return_data = DB::Connection('mysql2')->table('stock_return_data')->where('status', 1)->where('stock_return_id', $id)->get();
        $JobOrder = new JobOrder();
        $JobOrder = $JobOrder->SetConnection('mysql2');
        $JobOrder = $JobOrder->where('status', 1)->get();

        return view('Purchase.editStockReturn', compact('stock_return', 'stock_return_data', 'id', 'JobOrder'));
    }

    public function createSubItemForm()
    {
        $uom = new UOM;
        $uom = $uom::where('status', '=', '1')->get();


        CommonHelper::companyDatabaseConnection(Session::get('run_company'));
        $categories = new Category;
        $categories = $categories::where('status', '=', '1')->get();

        $brand = DB::Connection('mysql2')->table('brands')->where('status', 1)->get();
        $pack_type = DB::Connection('mysql2')->table('packaging_type')->where('status', 1)->get();

        return view('Purchase.createSubItemForm', compact('categories', 'uom', 'brand', 'pack_type'));
    }

    public function viewSubItemList()
    {
        return view('Purchase.viewSubItemList');
    }

    public function viewSubItemDetail()
    {
        $id = $_GET['id'];
        $sub_item = new Subitem();
        $sub_item = $sub_item->SetConnection('mysql2');
        $sub_item = $sub_item->where('status', 1)->where('id', $id)->select(
            'id',
            'sub_ic',
            'main_ic_id',
            'rate',
            'pack_size',
            'description',
            'uom',
            'itemType',
            'open_qty',
            'open_val'
        )->first();

        return view('Purchase.AjaxPages.viewSubItemDetail', compact('sub_item'));
    }


    public function createUOMForm()
    {
        return view('Purchase.createUOMForm');
    }

    public function viewUOMList()
    {
        return view('Purchase.viewUOMList');
    }

    public function createDemandForm()
    {
        $departments = new Department;
        $departments = $departments::where([['status', '=', '1'],])->select('id', 'department_name')->orderBy('id')->get();
        return view('Purchase.createDemandForm', compact('departments'));
    }
    public function createDemandFormAgainstMr()
    {
        $departments = new Department;
        $departments = $departments::where([['status', '=', '1'],])->select('id', 'department_name')->orderBy('id')->get();
        return view('Purchase.createDemandFormAgainstMr', compact('departments'));
    }


    public function viewDemandList()
    {
        $departments = Department::where([['status', '=', '1'],])->select('id', 'department_name')->orderBy('id')->get();
        return view('Purchase.viewDemandList', compact('departments'));
    }

    public function purchaseReturnForm()
    {
        return view('Purchase.purchaseReturnForm');
    }

    public function purchaseReturnList()
    {
        $Supplier = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();
        return view('Purchase.purchaseReturnList', compact('Supplier'));
    }

    public function stockreturnlist()
    {
        return view('Purchase.stockreturnlist');
    }

    public function editDemandVoucherForm($id)
    {

        $demand = new Demand();
        $demand = $demand->SetConnection('mysql2');
        $demand = $demand->where('id', $id)->where('status', 1)->first();



        $demand_data = new DemandData();
        $demand_data = $demand_data->SetConnection('mysql2');
        $demand_data = $demand_data->where('master_id', $id)->orderBy('id', 'ASC')->get();
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'],])->select('id', 'department_name')->orderBy('id')->get();
        return view('Purchase.editDemandVoucherForm', compact('demand', 'demand_data', 'id', 'departments'));
    }


    public function createGoodsReceiptNoteForm()
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $PurchaseRequestData = new PurchaseRequestData;
        $PurchaseRequestDatas = $PurchaseRequestData::distinct()->where('grn_status', '=', '1')->where('purchase_request_status', '=', '2')->get(['purchase_request_no', 'purchase_request_date']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->where('status', 1)
            ->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Purchase.createGoodsReceiptNoteForm', compact('PurchaseRequestDatas', 'accounts'));
    }

    public function viewGoodsReceiptNoteList()
    {
        return view('Purchase.viewGoodsReceiptNoteList');
    }

    public function AddGoodsquality()
    {
        return view('Purchase.addGoodQuality');
    }

    public function editGoodsReceiptNoteVoucherForm($id, $GrnNo)
    {
        $check = DB::Connection('mysql2')->table('purchase_return')->where('status', 1)->where('grn_id', $id)->count();

        if ($check > 0):
            echo '<h1>Purchase Return Booked Against This GRN</h1>';
            die;
        endif;

        $good_receipt_note = new GoodsReceiptNote();
        $good_receipt_note = $good_receipt_note->SetConnection('mysql2');
        $good_receipt_note = $good_receipt_note->where('id', $id)->first();

        if ($good_receipt_note->grn_status == 2):
            echo 'GRN APPROVED CAN NOT EDIT';
            die;
        endif;

        $grn_data = new GRNData();
        $grn_data = $grn_data->SetConnection('mysql2');
        $detail_data = $grn_data->where('master_id', $id)->get();
        $accounts = new Account();
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->get();
        $Addional = DB::Connection('mysql2')->table('addional_expense')->where('status', 1)->where('main_id', $id)->where('voucher_no', $GrnNo)->get();
        return view('Purchase.editGoodsReceiptNoteVoucherForm', compact('good_receipt_note', 'detail_data', 'accounts', 'Addional'));
    }

    public function editPurchaseReturnForm($id, $PrNo)
    {
        $Master = DB::Connection('mysql2')->table('purchase_return')->where('status', 1)->where('id', $id)->first();
        $Detail = DB::Connection('mysql2')->table('purchase_return_data')->where('status', 1)->where('master_id', $id)->get();
        return view('Purchase.editPurchaseReturnForm', compact('Master', 'Detail'));
    }


    public function editGoodsReceiptNoteWithoutPOForm($id)
    {
        $good_receipt_note = new GoodsReceiptNote();
        $good_receipt_note = $good_receipt_note->SetConnection('mysql2');
        $good_receipt_note = $good_receipt_note->where('id', $id)->first();

        $grn_data = new GRNData();
        $grn_data = $grn_data->SetConnection('mysql2');
        $grn_data = $grn_data->where('master_id', $id)->get();
        return view('Purchase.editGoodsReceiptNoteWithoutPOForm', compact('good_receipt_note', 'grn_data'));
    }

    public function createGoodsForwardOrderForm()
    {
        return view('Purchase.createGoodsForwardOrderForm');
    }

    public function viewGoodsForwardOrderList()
    {
        return view('Purchase.viewGoodsForwardOrderList');
    }


    public function viewPurchaseVoucherList()
    {


        $purchase_voucher = new PurchaseVoucher();
        $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
        $purchase_voucher = $purchase_voucher->where('status', 1)->select('id', 'pv_no', 'pv_date', 'supplier', 'slip_no', 'bill_date', 'total_net_amount')->orderBy('pv_date', 'ASC')->get();

        return view('Purchase.viewPurchaseVoucherList', compact('purchase_voucher'));

    }

    public function viewJobOrder()
    {
        $joborder = new JobOrder();
        $joborder = $joborder->SetConnection('mysql2');
        $joborder = $joborder->where('status', 1)->select('*')->get();
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->select('*')->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->select('*')->get();

        $ClientJob = new ClientJob();
        $ClientJob = $ClientJob->SetConnection('mysql2');
        $ClientJob = $ClientJob->where('status', 1)->select('*')->get();
        return view('Purchase.viewJobOrder', compact('joborder', 'Client', 'Region', 'ClientJob'));
    }

    public function viewJobOrderTwo()
    {
        $joborder = new JobOrder();
        $joborder = $joborder->SetConnection('mysql2');
        $joborder = $joborder->where('status', 1)->select('*')->get();
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->select('*')->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->select('*')->get();

        $ClientJob = new ClientJob();
        $ClientJob = $ClientJob->SetConnection('mysql2');
        $ClientJob = $ClientJob->where('status', 1)->select('*')->get();
        return view('Purchase.viewJobOrderTwo', compact('joborder', 'Client', 'Region', 'ClientJob'));
    }




    public function viewProduct()
    {
        $product = new Product();
        $product = $product->SetConnection('mysql2');
        $product = $product->where('p_status', 1)->select('*')->get();
        return view('Purchase.viewProduct', compact('product'));
    }

    public function viewPurchaseVoucherListThroughGrn()
    {

        $first_day_this_month = date('Y-m-01');
        $last_day_this_month = date('Y-m-t');
        $purchase_voucher = new NewPurchaseVoucher();
        $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
        $purchase_voucher = $purchase_voucher->where('status', 1)
            ->whereBetween('pv_date', [$first_day_this_month, $last_day_this_month])
            ->where('grn_id', '!=', 0)
            ->orderBy('pv_date', 'DCS')->get();
        $Supplier = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();

        return view('Purchase.viewPurchaseVoucherListThroughGrn', compact('purchase_voucher', 'Supplier', 'first_day_this_month', 'last_day_this_month'));

    }

    public function viewPurchaseVoucherListThroughWithoutGrn()
    {

        $first_day_this_month = date('Y-m-01');
        $last_day_this_month = date('Y-m-t');
        $purchase_voucher = new NewPurchaseVoucher();
        $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
        $purchase_voucher = $purchase_voucher->where('status', 1)
            ->whereBetween('pv_date', [$first_day_this_month, $last_day_this_month])
            ->where('grn_id', '=', 0)

            ->orderBy('pv_date', 'DCS')->get();
        $Supplier = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();

        return view('Purchase.viewPurchaseVoucherListThroughWithoutGrn', compact('purchase_voucher', 'Supplier', 'first_day_this_month', 'last_day_this_month'));

    }

    public function createDemandTypeForm()
    {
        return view('Purchase.createDemandTypeForm');
    }


    public function createWarehouseForm()
    {
        return view('Purchase.createWarehouseForm');
    }
    public function viewDemandTypeList()
    {

        $demand_type = new DemandType();
        $demand_type = $demand_type->SetConnection('mysql2');
        $demand_type = $demand_type->where('status', 1)->select('name')->get();

        return view('Purchase.viewDemandTypeList', compact('demand_type'));
    }
    public function viewWarehouseList()
    {

        $warehouse = new Warehouse();
        $warehouse = $warehouse->SetConnection('mysql2');
        $warehouse = $warehouse->where('status', 1)->select('name')->get();

        return view('Purchase.viewWarehouseList', compact('warehouse'));
    }

    public function createGoodReceiptNoteForWithoutPO()
    {
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();


        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'],])->select('id', 'department_name')->orderBy('id')->get();
        return view('Purchase.createGoodReceiptNoteForWithoutPO', compact('departments', 'supplier'));
    }
    public function viewGrnListForPurchaseVoucher()
    {

        $Supplier = new Supplier();
        $Supplier = $Supplier->SetConnection('mysql2');
        $Supplier = $Supplier->where('status', 1)->get();
        return view('Purchase.viewGrnListForPurchaseVoucher', compact('Supplier'));
    }

    public function createPurchaseVoucherFormThroughGrn(Request $request)
    {
        $ids = $request->checkbox;

        $department = new FinanceDepartment();
        $department = $department->SetConnection('mysql2');
        $department = $department->where('status', 1)->select('id', 'name', 'code')
            ->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->get();

        return view('Purchase.createPurchaseVoucherFormThroughGrn', compact('department', 'ids'));
    }


    public function createDirectPurchaseVoucherForm(Request $request)
    {
        $supplierList = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->select('id', 'department_name')->orderBy('id')->get();
        return view('Purchase.createDirectPurchaseVoucherForm', compact('supplierList', 'departments'));
    }

    public function createJobOrder()
    {
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->select('tracking_no')->get();





        $quotation = new Quotation();
        $quotation = $quotation->SetConnection('mysql2');
        $quotation = $quotation->where('status', 1)->where('quotation_status', 2)->select('quotation_no', 'id')->get();
        $uom = new UOM;
        $uom = $uom->where('status', '=', '1')->select('uom_name', 'id')->get();

        return view('Purchase.createJobOrder', compact('survey', 'quotation', 'uom'));
    }


    public function createProduct()
    {
        return view('Purchase.createProduct');
    }

    public function add_item_master()
    {

        return view('Purchase.add_item_master');
    }
    public function editItemMaster($id)
    {
        $ItemMaster = DB::Connection('mysql2')->table('item_master')->where('status', 1)->where('id', $id)->first();
        return view('Purchase.editItemMaster', compact('ItemMaster'));
    }

    public function viewSubCategoryList()
    {
        $SubCategory = DB::Connection('mysql2')->table('sub_category')->where('status', 1)->get();
        return view('Purchase.viewSubCategoryList', compact('SubCategory'));
    }
    public function viewItemMasterList()
    {
        $ItemMaster = DB::Connection('mysql2')->table('item_master')->where('status', 1)->get();
        return view('Purchase.viewItemMasterList', compact('ItemMaster'));
    }

    public function purchase_request_form()
    {
        return view('Purchase.purchase_request_form');
    }
    public function directPurchaseOrderForm()
    {
        $supplierList = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->select('id', 'department_name')->orderBy('id')->get();
        return view('Purchase.directPurchaseOrderForm', compact('supplierList', 'departments'));
    }

    public function purchase_order_status()
    {
        return view('Purchase.purchase_order_status');
    }
    public function vendor_opening_list()
    {

        $data = DB::Connection('mysql2')->table('supplier as a')
            ->select('a.name', 'a.id', 'a.acc_id', DB::raw('sum(b.balance_amount) as bal'))
            ->join('vendor_opening_balance as b', 'a.id', '=', 'b.vendor_id')
            ->where('a.status', 1)
            ->groupBy('b.vendor_id')
            ->get();

        return view('Purchase.vendor_opening_list', compact('data'));
    }

    public function vendor_report()
    {

        $data = DB::Connection('mysql2')->table('supplier as a')
            ->select('a.name', 'a.id', 'b.supplier')
            ->join('new_purchase_voucher as b', 'a.id', '=', 'b.supplier')
            ->where('a.status', 1)
            ->where('b.status', 1)
            ->groupBy('b.supplier')
            ->get();

        return view('Purchase.vendor_report', compact('data'));
    }

    public function vendor_outstanding()
    {
        return view('Purchase.vendor_outstanding');
    }

    public static function getPoReportByPoNo(Request $request)
    {
        $PoId = $request->PoId;
        $m = $request->m;
        $PurchaseRequest = DB::Connection('mysql2')->table('purchase_request')->where('id', $PoId)->get();


        return view('Purchase.AjaxPages.getPoReportByPoNo', compact('PurchaseRequest', 'PoId', 'm'));


    }
    public static function deleteItemMaster(Request $request)
    {
        $DeleteId = $request->ItemMasterId;
        $UpdateData['status'] = 2;
        DB::Connection('mysql2')->table('item_master')->where('id', $DeleteId)->update($UpdateData);

    }

    public static function directPurchaseInvoice()
    {


        $supplierList = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->select('id', 'department_name')->orderBy('id')->get();
        return view('Purchase.directPurchaseInvoice', compact('supplierList', 'departments'));
    }

    public function vendor_opening()
    {

        DB::Connection('mysql2')->beginTransaction();

        try {

            $data = DB::Connection('mysql2')->table('vendor_balance___ap_module_1')->get();

            foreach ($data as $row):

                $acc_id = DB::Connection('mysql2')->table('supplier')->where('name', strtoupper($row->name))->where('status', 1)->value('acc_id');

                if ($acc_id != null):
                    $acc_code = CommonHelper::get_account_code($acc_id);

                    $v_date = '2022-07-01';
                    $amount = str_replace(",", "", $row->bal);

                    if ($amount > 0):

                        $debit_credit = 0;
                        if ($row->bal < 0):
                            $debit_credit = 1;
                        endif;
                        $data1 = array
                        (
                            'acc_id' => $acc_id,
                            'acc_code' => $acc_code,
                            'opening_bal' => 1,
                            'debit_credit' => $debit_credit,
                            'amount' => $amount,
                            'v_date' => $v_date,
                            'date' => date('Y-m-d')
                        );

                        $count = DB::Connection('mysql2')->table('transactions')->where('opening_bal', 1)->where('acc_id', $acc_id)->count();
                        if ($count > 0):
                            DB::Connection('mysql2')->table('transactions')->where('acc_id', $acc_id)
                                ->where('opening_bal', 1)->update($data1);
                            echo 'update ' . $acc_id;
                            echo '<br>';
                        else:
                            DB::Connection('mysql2')->table('transactions')->insert($data1);
                            echo 'insert ' . $acc_id;
                            echo '<br>';
                        endif;
                    endif;
                endif;

            endforeach;
            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo $acc_id;

        }


    }

    public function customer_opening()
    {
        die;
        DB::Connection('mysql2')->beginTransaction();

        try {

            $data = DB::Connection('mysql2')->table('customer_balances___ar_module_v1')->get();

            foreach ($data as $row):


                $acc_id = DB::Connection('mysql2')->table('customers')->where('name', strtoupper($row->name))->where('status', 1);

                if ($acc_id->count() > 0):
                    $acc_id = $acc_id->value('acc_id');

                    if ($acc_id != null):
                        $acc_code = CommonHelper::get_account_code($acc_id);

                        $v_date = '2022-07-01';
                        $amount = str_replace(",", "", $row->bal);


                        if ($amount > 0):

                            $debit_credit = 1;
                            if ($row->bal < 0):
                                $debit_credit = 0;
                                $amount = $amount * -1;
                            endif;
                            $data1 = array
                            (
                                'acc_id' => $acc_id,
                                'acc_code' => $acc_code,
                                'opening_bal' => 1,
                                'debit_credit' => $debit_credit,
                                'amount' => $amount,
                                'v_date' => $v_date,
                                'date' => date('Y-m-d')
                            );

                            $count = DB::Connection('mysql2')->table('transactions')->where('opening_bal', 1)->where('acc_id', $acc_id)->count();
                            if ($count > 0):
                                DB::Connection('mysql2')->table('transactions')->where('acc_id', $acc_id)
                                    ->where('opening_bal', 1)->update($data1);
                                echo 'update ' . $acc_id;
                                echo '<br>';
                            else:
                                DB::Connection('mysql2')->table('transactions')->insert($data1);
                                echo 'insert ' . $acc_id;
                                echo '<br>';
                            endif;
                        endif;
                    endif;
                else:
                    echo 'Account Not Exists ' . $row->name;
                endif;

            endforeach;
            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo $acc_id;

        }


    }


    public function grnAddQcValues(Request $request)
    {
        $QaTest = QaTest::where('status', 1)->where('qc_type', QaTest::TYPE_GRN)->get();
        $categories_id = explode(',', Auth::user()->categories_id);
        $sub_item = DB::Connection('mysql2')->table('category as c')
            ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
            ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->where('sc.status', '=', 1)
            ->where('c.status', '=', 1)
            ->where('s.status', '=', 1)
            ->where('u.status', '=', 1)
            ->where('s.main_ic_id', '=', 8)
            ->select('s.id', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 's.hs_code_id')
            // ->whereIn('c.id', $categories_id)
            ->groupBy('s.item_code')
            ->orderBy('s.id')
            ->get();
        return view('Purchase.QaGRN.grnAddQcValues', compact('sub_item', 'QaTest'));
    }

    public function grnStoreQcValues(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|array',
            'test_id' => 'required|array',
            'test_id.*' => 'integer',
            'standard_value.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            foreach ($request->item_id as $index => $itemId) {
                $qcValue = QcValue::create([
                    'item_id' => $itemId,
                    'qc_type' => 2,
                    'status' => 1,
                ]);

                foreach ($request->test_id as $index => $testId) {
                    QcValueData::create([
                        'master_id' => $qcValue->id,
                        'test_id' => $testId,
                        'standard_value' => $request->standard_value[$index],
                        'status' => 1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('grnQcValuesList')->with('dataInsert', 'QC values saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function grnQcValuesList(Request $request)
    {
        $qcValues = QcValue::join('subitem', 'qc_values.item_id', '=', 'subitem.id')
            ->select('qc_values.*', 'subitem.sub_ic as item_name', 'subitem.item_code')
            ->where('qc_type', 2)
            ->get();

        return view('Purchase.QaGRN.grnQcValuesList', compact('qcValues'));
    }

    public function grnViewQcValuesDetail(Request $request)
    {
        $qcValues = QcValue::join('qc_values_data', 'qc_values.id', '=', 'qc_values_data.master_id')
            ->join('subitem', 'qc_values.item_id', '=', 'subitem.id')
            ->join('qa_tests', 'qc_values_data.test_id', '=', 'qa_tests.id')
            ->where('qc_values.id', $request->id)
            ->select(
                'qc_values.*',
                'subitem.sub_ic as item_name',
                'subitem.item_code',
                'qa_tests.name AS test_name',
                'qc_values_data.standard_value'
            )
            ->get();

        return view('Purchase.QaGRN.grnViewQcValuesDetail', compact('qcValues'));
    }

    public function grnEditQcValues($id)
    {
        $qcValue = QcValue::findOrFail($id);

        $qcValuesData = QcValueData::where('master_id', $id)
            ->join('qa_tests', 'qc_values_data.test_id', '=', 'qa_tests.id')
            ->select('qc_values_data.*', 'qa_tests.name AS test_name')
            ->get();

        $item = Subitem::findOrFail($qcValue->item_id);
        // dd($item);
        // $item = DB::table('subitem')->where('id', $qcValue->item_id)->select('id', 'sub_category_id', 'item_code', 'sub_ic')->first();
        // $sub_category = DB::table('sub_category')->where('id', $item->sub_category_id)->select('id', 'category_id')->first();
        // $category = DB::table('category')->where('main_ic_id', $sub_category->category_id)->select('id')->first();


        return view('Purchase.QaGRN.grnEditQcValues', compact('qcValue', 'qcValuesData', 'item'));
    }

    public function grnUpdateQcValues(Request $request)
    {
        $qcValue = QcValue::findOrFail($request->id);

        foreach ($request->test_id as $key => $testId) {
            QcValueData::where('master_id', $request->id)
                ->where(
                    'test_id',
                    $testId
                )
                ->update(['standard_value' => $request->standard_value[$key]]);
        }

        return redirect()->route('grnQcValuesList')->with('dataInsert', 'QC Values updated successfully!');
    }

    public function grnDeleteQcValue(Request $request)
    {
        try {
            $qcValue = QcValue::findOrFail($request->id);
            QcValueData::where('master_id', $qcValue->id)->update(['status' => 0]);

            $qcValue->update(['status' => 0]);

            return response()->json([
                'success' => true,
                'message' => 'QC Value status updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating QC Value status: ' . $e->getMessage(),
            ]);
        }
    }

    public function grnGetQcValueForm(Request $request)
    {
        $finish_good_id = DB::Connection('mysql2')->table('material_requisitions')->where('id', $request->material_requisition_id)->pluck('finish_good_id');

        $qc_values = QcValue::with('qcValuesData')
            ->join('qc_values_data', 'qc_values.id', '=', 'qc_values_data.master_id') // Correct join
            ->join('qa_tests', 'qa_tests.id', '=', 'qc_values_data.test_id') // Ensure this matches your database schema
            ->where('qc_values.item_id', $finish_good_id)
            ->select('qa_tests.id AS qa_test_id', 'qa_tests.name', 'qc_values_data.*')
            ->get();

        // If editing, get existing QC packing data
        $existing_qc_data = [];
        $existing_qc_status = [];
        $existing_qc_type = [];
        if (!empty($request->qc_packing_id)) {
            $existing_qc_records = QcPackingData::where('qc_packing_id', $request->qc_packing_id)
                ->where('status', 1)
                ->get();

            foreach ($existing_qc_records as $record) {
                $existing_qc_data[$record->qa_test_id] = $record->test_value;
                $existing_qc_status[$record->qa_test_id] = $record->test_status;
                $existing_qc_type[$record->qa_test_id] = $record->test_type;
            }
        }

        return view('Purchase.QaGRN.grnGetQcValueForm', compact('qc_values', 'existing_qc_data', 'existing_qc_status', 'existing_qc_type'));
    }
}
