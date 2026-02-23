<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Quotation_Data;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Models\Account;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Countries;
use App\Models\Subitem;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Models\Dispatch;
use App\Models\DispatchData;
use App\Models\Production\PackingData;
use App\Models\CreditNote;
use App\Models\CreditNoteData;
use App\Models\Region;
use App\Models\Cities;
use App\Models\Type;
use App\Models\Conditions;
use App\Models\SurveryBy;
use App\Models\Client;
use App\Models\Branch;
use App\Models\Survey;
use App\Models\SurveyData;
use App\Models\SurveyDocument;
use App\Models\JobTracking;
use App\Models\ProductType;
use App\Models\ResourceAssigned;
use App\Models\Quotation;
use App\Models\Complaint;
use App\Models\ComplaintProduct;
use App\Models\InvDesc;
use App\Models\NewRvs;
use App\Models\NewRvData;
use App\Models\Supplier;
use App\Helpers\NotificationHelper;

use App\Helpers\ReuseableCode;
use App\Helpers\SalesHelper;
use Input;
use Session;
use Redirect;
use App\Models\DeliveryNote;

use App\Models\DeliveryNoteData;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;
use App\Models\JobOrder;
use App\Models\JobOrderData;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\Product;
use App\Models\ClientJob;
use App\Models\LOGACTIVITY;

use App\Models\ComplaintDocument;


class SalesController extends Controller
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
        return view('Sales.toDayActivity');
    }

    public function addOpeningAgainstCustomerForm()
    {
        return view('Sales.addOpeningAgainstCustomerForm');
    }

    public function addReceiptVoucherAgainstSOForm()
    {
        // $salesOrdersListApprovedAndNotReceived = DB::Connection('mysql2')->table('sales_order')->where('so_status',4)->where('delivery_note_status',0)->where('amount_received_status',1)->get();
        $salesQuotationListApprovedAndNotReceived = DB::Connection('mysql2')->table('sales_order as so')
            ->join('customers as c', 'c.id', 'so.buyers_id')
            ->select('so.*', 'c.name')
            // ->where('sq.approved_status',1)
            ->get();

        // echo "<pre>";
        // print_r($salesQuotationListApprovedAndNotReceived);
        // exit();
        return view('Sales.addReceiptVoucherAgainstSOForm', compact('salesQuotationListApprovedAndNotReceived'));
    }

    public function topFiveSalesReportPage()
    {
        $Customers = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.topFiveSalesReportPage', compact('Customers'));
    }


    public function debtor_balance_page()
    {
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.debtor_balance_page', compact('Customer'));
    }
    public function commission_report_page()
    {
        $Agent = DB::Connection('mysql2')->select('select a.id,a.agent_name from sales_agent a
                                                  INNER  JOIN commision b ON b.agent = a.id
                                                  WHERE b.status = 1');
        return view('Sales.commission_report_page', compact('Agent'));
    }



    public function add_point_of_sale()
    {
        $BatchCode = DB::Connection('mysql2')->table('stock')->where('status', 1)->where('opening', 0)->select('batch_code')->groupBy('batch_code')->get();
        return view('Sales.add_point_of_sale', compact('BatchCode'));
    }

    public function salesActivityPage()
    {
        return view('Sales.salesActivityPage');
    }

    public function freight_collection_page()
    {
        return view('Sales.freight_collection_page');
    }


    public function salesActivityAjax()
    {
        return view('Sales.salesActivityAjax');
    }
    public function debtor_payment_detail()
    {
        $data = DB::Connection('mysql2')->table('customers as a')
            ->select('a.name', 'a.id')
            ->join('sales_tax_invoice as b', 'a.id', '=', 'b.buyers_id')
            ->where('a.status', 1)
            ->where('b.status', 1)
            ->groupBy('b.buyers_id')
            ->get();
        return view('Sales.debtor_payment_detail', compact('data'));
    }

    public function soTrackingQtyPage()
    {
        return view('Sales.soTrackingQtyPage');
    }

    public function salesAgingReport()
    {
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.salesAgingReport', compact('Customer'));
    }

    public function getAgingReportDataAjaxSales(Request $request)
    {
        if ($request->ReportType == 1) {
            return view('Sales.getAgingReportDataAjaxSalesSummary');
        } else {
            return view('Sales.getAgingReportDataAjaxSales');
        }

    }



    public function createCashCustomerForm()
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
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.createCashCustomerForm', compact('accounts', 'countries'));
    }

    public function viewCashCustomerList()
    {
        return view('Sales.viewCashCustomerList');
    }

    public function outstandingReportPage()
    {
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.outstandingReportPage', compact('Customer'));
    }

    public function soTrackingPage()
    {
        $SoNo = DB::Connection('mysql2')->table('sales_order')->where('status', 1)->select('so_no', 'id')->get();
        return view('Sales.soTrackingPage', compact('SoNo'));
    }


    public function ViewMultipleDeliveryNotesDetail()
    {
        return view('Sales.ViewMultipleDeliveryNotesDetail');

    }

    public function soReportPage()
    {
        return view('Sales.soReportPage');
    }
    public function dnReportPage()
    {
        return view('Sales.dnReportPage');
    }


    public function ViewMultipleSalesTaxInvoices()
    {
        return view('Sales.ViewMultipleSalesTaxInvoices');

    }
    public function ViewMultipleCreditNoteDetail()
    {
        return view('Sales.ViewMultipleCreditNoteDetail');

    }




    public function CreateMultipleSalesTaxInvoices()
    {
        return view('Sales.CreateMultipleSalesTaxInvoices');
    }



    public function createCreditCustomerForm()
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

            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.createCreditCustomerForm', compact('accounts', 'countries'));
    }

    public function editCustomerForm($id)
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

            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.editCustomerForm', compact('accounts', 'countries', 'id'));
    }



    public function viewCreditCustomerList()
    {
        return view('Sales.viewCreditCustomerList');
    }
    public function add_agent_list()
    {
        return view('Sales.add_agent_list');
    }


    public function jobTrackingSheet()
    {

        $customer = new Customer();
        $customer = $customer->SetConnection('mysql2');
        $customer = $customer->where('status', 1)->get();
        $region = new Region();
        $region = $region->SetConnection('mysql2');
        $region = $region->where('status', 1)->get();
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->where('survey_status', 2)->get();
        $cities = new Cities();
        //$cities = $cities->SetConnection('mysql2');
        $cities = $cities->where('status', 1)->whereIn('state_id', array(2723, 2724, 2725, 2726, 2727, 2728, 2729))->get();
        return view('Sales.jobTrackingSheet', compact('customer', 'region', 'survey', 'cities'));

    }

    public function createCreditSaleVoucherForm()
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
            ->where('code', 'like', '5%')
            ->get();
        $categories = new Category;
        $categories = $categories::where('status', '=', '1')->get();
        $Customers = new Customer;
        $Customers = $Customers::where('status', '=', '1')->where('customer_type', '=', '3')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.createCreditSaleVoucherForm', compact('accounts', 'categories', 'Customers'));
    }

    public function createCashSaleVoucherForm()
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $creditAccounts = new Account;
        $creditAccounts = $creditAccounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->where('code', 'like', '5%')
            ->get();

        $debitAccounts = new Account;
        $debitAccounts = $debitAccounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->where('code', 'like', '1-3')
            ->get();
        $categories = new Category;
        $categories = $categories::where('status', '=', '1')->get();
        $Customers = new Customer;
        $Customers = $Customers::where('status', '=', '1')->where('customer_type', '=', '2')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.createCashSaleVoucherForm', compact('creditAccounts', 'debitAccounts', 'categories', 'Customers'));
    }

    public function viewCashSaleVouchersList()
    {
        return view('Sales.viewCashSaleVouchersList');
    }

    public function viewCreditSaleVouchersList()
    {
        return view('Sales.viewCreditSaleVouchersList');
    }
    public function CreateSalesOrder()
    {
        return view('Sales.CreateSalesOrder');
    }
    public function CreateDirectSalesTaxInvoice()
    {
        return view('Sales.CreateDirectSalesTaxInvoice');
    }

    public function EditSalesOrder($id)
    {


        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $id)->first();
        $sales_order_id = $id;

        //        $sales_order_data=new Sales_Order_Data();
//        $sales_order_data=$sales_order_data->SetConnection('mysql2');
//        $sales_order_data=$sales_order_data->where('master_id',$id)->get();

        $sales_order_data = DB::Connection('mysql2')->select('select a.id,a.master_id,a.qty,a.rate,a.amount,a.bundles_id,a.desc,
        a.groupby,a.item_id,a.sub_total,a.tax,a.tax_amount,b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
         from sales_order_data a
        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.master_id="' . $id . '"

        group by a.groupby');

        $BuyerData = CommonHelper::get_single_row('customers', 'id', $sales_order->buyers_id);
        $Addional = DB::Connection('mysql2')->table('addional_expense_sales_order')->where('status', 1)->where('main_id', $id)->get();
        $accounts = new Account();
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->get();
        return view('Sales.EditSalesOrder', compact('sales_order', 'sales_order_data', 'id', 'BuyerData', 'sales_order_id', 'Addional', 'accounts'));
    }

    public function ShowAllImages($id)
    {
        $surveyDocs = new SurveyDocument();
        $surveyDocs = $surveyDocs->SetConnection('mysql2');
        $surveyDocs = $surveyDocs->where('status', 1)->where('survey_id', $id)->get();

        return view('Sales.ShowAllImages', compact('surveyDocs'));
    }
    public function customer_opening_list()
    {
        $data = DB::Connection('mysql2')->table('customers as a')
            ->select('a.name', 'a.id', 'a.acc_id', DB::raw('sum(b.balance_amount) as bal'))
            ->join('customer_opening_balance as b', 'a.id', '=', 'b.buyer_id')
            ->where('a.status', 1)
            ->groupBy('b.buyer_id')
            ->get();
        return view('Sales.customer_opening_list', compact('data'));
    }


    public function ShowAllImagesComplaint($id)
    {
        $ComplaintDocs = new ComplaintDocument();
        $ComplaintDocs = $ComplaintDocs->SetConnection('mysql2');
        $ComplaintDocs = $ComplaintDocs->where('status', 1)->where('complaint_id', $id)->get();

        return view('Sales.ShowAllImagesComplaint', compact('ComplaintDocs'));
    }


    public function viewSalesOrderList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');
        $sale_order = new Sales_Order();
        $sale_order = $sale_order->SetConnection('mysql2');
        $sale_order = $sale_order->where('status', 1)->whereBetween('so_date', [$currentMonthStartDate, $currentMonthEndDate])->orderBy('id', 'DESC')->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.viewSalesOrderList', compact('sale_order', 'Customer'));
    }
    public function viewSalesOrderDetail()
    {
        $id = Input::get('id');
        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $id)->first();


        $sales_order_data = new Sales_Order_Data();
        $sales_order_data = $sales_order_data->SetConnection('mysql2');
        $sales_order_data = $sales_order_data->where('master_id', $id)->get();

        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_order')->where('main_id', $id);

        return view('Sales.AjaxPages.viewSalesOrderDetail', compact('sales_order', 'sales_order_data', 'AddionalExpense'));
    }

    public function CreateDeliveryNoteList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');

        $sale_order = Sales_Order::where('status', 1)->where('delivery_note_status', 0)
            ->whereIn('so_status', [1, 2, 3, 4])
            ->whereBetween('so_date', [$currentMonthStartDate, $currentMonthEndDate])
            ->get();

        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.CreateDeliveryNoteList', compact('sale_order', 'Customer'));
    }

    public function CreateDeliveryChallanList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');
        $sale_order = new Sales_Order();
        $sale_order = $sale_order->SetConnection('mysql2');
        $sale_order = $sale_order->where('status', 1)->where('delivery_note_status', 0)
            ->whereIn('so_status', [1, 2, 3, 4])
            ->whereBetween('so_date', [$currentMonthStartDate, $currentMonthEndDate])->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        // dd($sale_order);
        return view('Sales.CreateDeliveryChallanList', compact('sale_order', 'Customer'));
    }

    public function CreateDispatchList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');
        $sale_order = new Sales_Order();
        $sale_order = $sale_order->SetConnection('mysql2');
        $sale_order = $sale_order->where('status', 1)->where('delivery_note_status', 0)
            ->whereIn('so_status', [1, 2, 3, 4])
            ->whereBetween('so_date', [$currentMonthStartDate, $currentMonthEndDate])->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        // dd($sale_order);
        return view('Sales.CreateDispatchList', compact('sale_order', 'Customer'));
    }

    public function EditDeliveryNote()
    {
        $id = Input::get('id');

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('id', $id)->where('status', 1)->first();

        // $delivery_note_data = DB::Connection('mysql2')->select('select a.id,a.master_id,a.so_data_id,a.qty,a.rate,a.amount,a.bundles_id,a.warehouse_id,a.batch_code,
        // a.item_id,a.groupby, a.tax, a.tax_amount, b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        // ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
        //  from delivery_note_data a
        // left join
        // bundles b
        // on
        // a.bundles_id=b.id
        // where a.master_id='.$id.'
        // group by a.groupby');

        $delivery_note_data = DB::connection('mysql2')
            ->table('delivery_note_data as a')
            ->leftJoin('bundles as b', 'a.bundles_id', '=', 'b.id')
            ->join('subitem as s', 's.id', '=', 'a.item_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->join('packaging_type as pt', 'pt.id', '=', 's.primary_pack_type')
            ->select(
                'a.id',
                'a.master_id',
                'a.so_data_id',
                'a.qty',
                'a.rate',
                'a.amount',
                'a.bundles_id',
                'a.warehouse_id',
                'a.batch_code',
                'a.out_qty_details',
                'a.item_id',
                'a.groupby',
                'a.tax',
                'a.tax_amount',
                'b.product_name',
                'b.rate as bundle_rate',
                'b.amount as bundle_amount',
                'b.discount_percent as b_percent',
                'b.discount_amount as b_dis_amount',
                'b.net_amount as b_net',
                'b.qty as bqty',
                'b.bundle_unit',
                's.sub_ic',
                's.uom',
                's.item_code',
                'u.uom_name',
                'pt.type',
                's.pack_size',
                's.primary_pack_type',
                's.color'
            )
            ->where('a.master_id', $id)
            ->groupBy('a.groupby')
            ->get();


        $FinalTot = DB::Connection('mysql2')->selectOne('select sum(amount) as amount from delivery_note_data where master_id = ' . $id . '')->amount;
        return view('Sales.EditDeliveryNote', compact('delivery_note', 'delivery_note_data', 'FinalTot'));
    }

    public function CreateDeliveryNote()
    {
        $id = Input::get('id');
        $sales_order = DB::connection('mysql2')->table('sales_order')->where('id', $id)->where('delivery_note_status', 0)->first();

        $sale_order_data = DB::connection('mysql2')
            ->table('sales_order_data as a')
            ->leftJoin('bundles as b', 'a.bundles_id', '=', 'b.id')
            ->join('subitem as s', 's.id', '=', 'a.item_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->join('packaging_type AS pt', 'pt.id', '=', 's.primary_pack_type')
            ->where('a.master_id', $id)
            ->where('a.status', 1)
            ->select([
                'a.id',
                'a.master_id',
                'a.qty',
                'a.rate',
                'a.amount',
                'a.bundles_id',
                'a.groupby',
                'a.item_id',
                'a.sub_total',
                'a.tax',
                'a.tax_amount',
                'a.desc',
                'b.product_name',
                'b.rate as bundle_rate',
                'b.amount as bundle_amount',
                'b.discount_percent as b_percent',
                'b.discount_amount as b_dis_amount',
                'b.net_amount as b_net',
                'b.qty as bqty',
                'b.bundle_unit',
                's.sub_ic',
                's.uom',
                's.item_code',
                'u.uom_name',
                'pt.type',
                's.pack_size',
                's.primary_pack_type',
                's.color'
            ])
            ->get();

        // $sale_order_data = DB::Connection('mysql2')->select('select 
        // a.id,
        // a.master_id,
        // a.qty,
        // a.rate,
        // a.amount,
        // a.bundles_id,
        // a.groupby,
        // a.groupby,
        // a.item_id,
        // a.sub_total,
        // a.tax,
        // a.tax_amount,
        // b.product_name,
        // b.rate as bundle_rate,
        // b.amount as bundle_amount,
        // b.discount_percent as b_percent,
        // b.discount_amount as b_dis_amount,
        // b.net_amount as b_net,
        // b.qty as bqty,
        // b.bundle_unit,
        // a.desc 
        // FORM sales_order_data a
        // LEFT JOIN bundles b ON a.bundles_id = b.id
        // INNER JOIN subitem AS s
        // WHERE a.master_id = "' . $id . '" AND a.status = 1');

        return view('Sales.CreateDeliveryNote', compact('sales_order', 'sale_order_data'));
    }

    public function CreateDeliveryChallan()
    {
        $id = Input::get('id');


        $sale_order_data_other = new Sales_Order_Data();
        $sale_order_data_other = $sale_order_data_other->SetConnection('mysql2');
        $sale_order_data_other_indi = $sale_order_data_other->where('id', $id)->where('bundles_id', '=', 0)->get();

        // echo "<pre>";
        // print_r($sale_order_data_other_indi);
        // exit();
        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $sale_order_data_other_indi[0]->master_id)->where('delivery_note_status', 0)->first();

        $sale_order_data = DB::Connection('mysql2')->select("
        select 
        sod.id,
        sod.master_id,
        sum(sod.qty) as qty,
        sod.rate,
        sod.amount,
        sod.bundles_id,
        sod.groupby,
        sod.groupby,
        sod.item_id,
        sod.sub_total,
        sod.tax,
        sod.tax_amount,
        sod.desc
        from sales_order_data sod
        where sod.status = 1 and sod.id = $id group by sod.id
        ");

        return view('Sales.CreateDeliveryChallan', compact('sales_order', 'sale_order_data', 'sale_order_data_other_indi'));
    }

    public function CreateDispatch(Request $request)
    {
        $id = Input::get('id');


        $sale_order_data_other = new Sales_Order_Data();
        $sale_order_data_other = $sale_order_data_other->SetConnection('mysql2');
        $sale_order_data_other_indi = $sale_order_data_other->where('id', $id)->where('bundles_id', '=', 0)->get();

        // echo "<pre>";
        // print_r($sale_order_data_other_indi);
        // exit();
        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $sale_order_data_other_indi[0]->master_id)->where('delivery_note_status', 0)->first();

        // $sale_order_data=DB::Connection('mysql2')->select("
        // select 
        // sod.id,
        // sod.master_id,
        // sum(sod.qty) as qty,
        // sod.rate,
        // sod.amount,
        // sod.bundles_id,
        // sod.groupby,
        // sod.groupby,
        // sod.item_id,
        // sod.sub_total,
        // sod.tax,
        // sod.tax_amount,
        // sod.desc
        // from sales_order_data sod
        // where sod.status = 1 and sod.id = $id group by sod.id
        // ");
        $sale_order_data = DB::Connection('mysql2')->select("
        select 
        sod.id,
        sod.master_id,
        sum(dcd.qty) as qty,
        sod.rate,
        sod.amount,
        sod.bundles_id,
        sod.groupby,
        sod.groupby,
        sod.item_id,
        sod.sub_total,
        sod.tax,
        sod.tax_amount,
        sod.desc
        from sales_order_data sod
        JOIN delivery_note_data dcd on dcd.so_data_id = sod.id
        JOIN delivery_note dc on dc.id = dcd.master_id
        JOIN packings p on p.dc_id = dc.id
        where sod.status = 1 and sod.id = $id and p.id = $request->p_id group by dcd.id
        ");



        $packing = DB::Connection('mysql2')->table('packings as p')
            ->select(
                'dc.dc_no',
                'pp.order_no',
                'p.packing_list_no',
                's.sub_ic',
                'so.so_no',
                'sc.sub_category_name',
                'so.description',
                'u.uom_name',
                'dc.id as dc_id',
                'pp.id as pp_id',
                'p.id as p_id',
                'mr.id as mr_id',
                's.id as s_id',
                'so.id as so_id',
                'p.customer_id',
                'p.customer_name',
                'dcd.qty'
            )
            ->join('sales_order as so', 'so.id', 'p.so_id')
            ->join('production_plane as pp', 'pp.id', 'p.production_plan_id')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('delivery_note as dc', 'dc.id', 'p.dc_id')
            ->join('delivery_note_data as dcd', 'dcd.master_id', 'dc.id')
            ->join('subitem as s', 's.id', 'p.item_id')
            ->join('uom as u', 'u.id', '=', 's.uom')
            ->join('sub_category as sc', 's.sub_category_id', '=', 'sc.id')
            // ->join('delivery_note as dc' , 'dc.id' ,'p.dc_id')
            ->where('p.id', $request->p_id)
            ->where('p.qc_status', 3)
            ->first();
        // dd($packing);
        $packing_data = PackingData::where('packing_id', $request->p_id)->where('status', 1)->get();
        return view('Sales.CreateDispatch', compact('sales_order', 'sale_order_data', 'sale_order_data_other_indi', 'packing', 'packing_data'));
    }
    public function CreateDeliveryChallanOld()
    {
        $id = Input::get('id');
        $packing_id = Input::get('packing_id');
        $qc_packing_id = Input::get('qc_packing_id');

        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $id)->where('delivery_note_status', 0)->first();

        $packing = db::connection('mysql2')->table('packings')->where('id', $packing_id)->where('status', 1)->first();


        $sale_order_data_other = new Sales_Order_Data();
        $sale_order_data_other = $sale_order_data_other->SetConnection('mysql2');
        $sale_order_data_other_indi = $sale_order_data_other->where('master_id', $id)->where('bundles_id', '=', 0)->get();

        $sale_order_data = DB::Connection('mysql2')->select("
        select 
        sod.id,
        sod.master_id,
        sum(pd.qty) as qty,
        sod.rate,
        sod.amount,
        sod.bundles_id,
        sod.groupby,
        sod.groupby,
        sod.item_id,
        sod.sub_total,
        sod.tax,
        sod.tax_amount,
        sod.desc,
        p.id packing_id, 
        pd.id packing_data_id
        from sales_order_data sod
        inner join packings p 
        ON p.item_id = sod.item_id
        INNER JOIN packing_datas pd 
        ON pd.packing_id = p.id
        inner join qc_packings qp 
        ON qp.packing_list_id = p.id
        
        where sod.status = 1 and p.status = 1 and pd.status = 1 and qp.status = 1 and  p.qc_status = 3 and sod.master_id = $id AND p.id = $packing_id
        group by p.id
        ");

        return view('Sales.CreateDeliveryChallanOld', compact('sales_order', 'sale_order_data', 'sale_order_data_other_indi', 'packing_id', 'qc_packing_id', 'packing'));
    }




    public function EditDispatch(Request $request)
    {
        $id = Input::get('id');

        $delivery_note = new Dispatch();
        // $delivery_note=new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('id', $id)->where('status', 1)->first();

        //        $delivery_note_data=new DeliveryNoteData();
//        $delivery_note_data=$delivery_note_data->SetConnection('mysql2');
//        $delivery_note_data=$delivery_note_data->where('master_id',$id)->get();

        // $delivery_note_data = DB::Connection('mysql2')->select('select a.id,a.master_id,a.so_data_id,a.qty,a.rate,a.amount,a.bundles_id,a.warehouse_id,a.batch_code,
        // a.item_id,a.groupby, a.tax, a.tax_amount, b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        // ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
        //  from delivery_note_data a
        // left join
        // bundles b
        // on
        // a.bundles_id=b.id
        // where a.master_id='.$id.'
        // group by a.groupby');
        $delivery_note_data = DB::Connection('mysql2')->select('select a.id,a.master_id,a.so_data_id,a.qty,a.rate,a.amount,a.bundles_id,a.warehouse_id,a.batch_code,
        a.item_id,a.groupby, a.tax, a.tax_amount, b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
         from delivery_note_data a
        left join
        bundles b
        on
        a.bundles_id=b.id
        join
        delivery_note dc
        on
        a.master_id=dc.id
        join
        dispatches dp
        on
        dc.id=dp.dc_id
        join
        dispatch_datas dpd
        on
        dp.id=dpd.dispatch_id
        where dpd.dispatch_id=' . $id . '
        group by a.groupby');
        //        echo "<pre>";
//        print_r($delivery_note_data); die();

        $FinalTot = DB::Connection('mysql2')->selectOne('select sum(amount) as amount from delivery_note_data where master_id = ' . $id . '')->amount;
        $packing = DB::Connection('mysql2')->table('packings as p')
            ->select(
                'dc.dc_no',
                'pp.order_no',
                'p.packing_list_no',
                's.sub_ic',
                'so.so_no',
                'sc.sub_category_name',
                'so.description',
                'u.uom_name',
                'dc.id as dc_id',
                'pp.id as pp_id',
                'p.id as p_id',
                'mr.id as mr_id',
                's.id as s_id',
                'so.id as so_id',
                'p.customer_id',
                'p.customer_name'
            )
            ->join('dispatches as dp', 'dp.packing_id', 'p.id')
            ->join('sales_order as so', 'so.id', 'p.so_id')
            ->join('production_plane as pp', 'pp.id', 'p.production_plan_id')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->join('delivery_note as dc', 'dc.id', 'p.dc_id')
            ->join('subitem as s', 's.id', 'p.item_id')
            ->join('uom as u', 'u.id', '=', 's.uom')
            ->join('sub_category as sc', 's.sub_category_id', '=', 'sc.id')
            // ->join('delivery_note as dc' , 'dc.id' ,'p.dc_id')
            ->where('dp.id', $id)
            // ->where('p.id' , $request->p_id)
            // ->where('p.qc_status' , 3)
            ->first();
        // dd($packing);
        $packing_data = PackingData::where('packing_id', $delivery_note->packing_id)->where('status', 1)->get();

        return view('Sales.EditDispatch', compact('delivery_note', 'delivery_note_data', 'FinalTot', 'packing', 'packing_data'));
    }

    public function editSalesReturn($id)
    {
        echo $id;

        $CreditNote = new CreditNote();
        $CreditNote = $CreditNote->SetConnection('mysql2');
        $CreditNote = $CreditNote->where('id', $id)->where('status', 1)->first();

        $CreditNoteData = new CreditNoteData();
        $CreditNoteData = $CreditNoteData->SetConnection('mysql2');
        $CreditNoteData = $CreditNoteData->where('master_id', $id)->get();

        return view('Sales.editSalesReturn', compact('CreditNote', 'CreditNoteData'));
    }


    public function editImportDocument($id)
    {
        $Master = DB::Connection('mysql2')->table('import_po')->where('status', 1)->where('id', $id)->first();
        $Detail = DB::Connection('mysql2')->table('import_po_data')->where('status', 1)->where('master_id', $id)->orderBy('id', 'ASC')->get();
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();

        return view('Sales.editImportDocument', compact('Master', 'Detail', 'id', 'supplier'));
    }

    public function viewDeliveryNoteList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('status', 1)->whereBetween('gd_date', [$currentMonthStartDate, $currentMonthEndDate])->orderBy('id', 'DESC')->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();

        return view('Sales.viewDeliveryNoteList', compact('delivery_note', 'Customer'));
    }

    public function viewDeliveryChallanList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('status', 1)
            ->whereBetween('gd_date', [$currentMonthStartDate, $currentMonthEndDate])
            ->orderBy('id', 'DESC')->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();

        return view('Sales.viewDeliveryChallanList', compact('delivery_note', 'Customer'));
    }
    public function viewDispatchList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');
        $dispatch = new Dispatch();
        $dispatch = $dispatch->SetConnection('mysql2');
        $dispatch = DB::Connection('mysql2')
            ->table('dispatches as d')
            ->select('d.dispatch_no', 'd.dispatch_date', 'd.id', 'so.so_no', 'p.packing_list_no', 'd.customer_id', 'pp.order_no', 'dc.dc_no', 'd.dispatch_status', 's.sub_ic')
            ->join('dispatch_datas as dd', 'dd.dispatch_id', '=', 'd.id')
            ->join('sales_order as so', 'so.id', '=', 'd.so_id')
            ->join('packings as p', 'p.id', '=', 'd.packing_id')
            ->join('delivery_note as dc', 'dc.id', '=', 'd.dc_id')
            ->join('production_plane as pp', 'pp.id', '=', 'd.production_plan_id')
            ->join('subitem as s', 's.id', '=', 'dd.item_id')
            ->where('d.status', 1)
            // ->whereBetween('d.dispatch_date',[$currentMonthStartDate,$currentMonthEndDate])
            ->groupby('dd.id')
            ->orderBy('d.id', 'DESC')->get();

        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        // dd($dispatch);
        return view('Sales.viewDispatchList', compact('dispatch', 'Customer'));
    }
    public function viewDeliveryNoteListOther()
    {
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('status', 1)->get();

        return view('Sales.viewDeliveryNoteListOther', compact('delivery_note'));
    }


    public function viewDeliveryNoteDetail($id)
    {
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('id', $id)->first();

        $delivery_note_data_other = new DeliveryNoteData();
        $delivery_note_data_other = $delivery_note_data_other->SetConnection('mysql2');
        $delivery_note_data = $delivery_note_data_other->join('subitem as s', 's.id', '=', 'delivery_note_data.item_id')
            ->join(env('DB_DATABASE') . '.uom as u', 's.uom', '=', 'u.id')
            ->join('packaging_type as pt', 'pt.id', '=', 's.primary_pack_type')
            ->select('delivery_note_data.*', 's.sub_ic', 's.uom', 's.item_code', 'u.uom_name', 'pt.type', 's.pack_size', 's.primary_pack_type', 's.color')->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewDeliveryNoteDetail', compact('delivery_note', 'delivery_note_data', 'id'));
    }

    public function viewDeliveryChallanDetail($id)
    {

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('id', $id)->first();

        // echo "<pre>";
        // print_r($delivery_note);
        // exit();
        $delivery_note_data_other = new DeliveryNoteData();
        $delivery_note_data_other = $delivery_note_data_other->SetConnection('mysql2');
        $delivery_note_data = $delivery_note_data_other->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewDeliveryChallanDetail', compact('delivery_note', 'delivery_note_data', 'id'));
    }

    public function viewDispatchDetail($id)
    {

        $dispatch = new Dispatch();
        $dispatch = $dispatch->SetConnection('mysql2');
        $dispatch = $dispatch->where('id', $id)->first();

        // echo "<pre>";
        // print_r($delivery_note);
        // exit();
        $delivery_note_data_other = new DeliveryNoteData();
        $delivery_note_data_other = $delivery_note_data_other->SetConnection('mysql2');
        $delivery_note_data = $delivery_note_data_other->where('master_id', $id)->get();

        $dispatch_data_other = new DispatchData();
        $dispatch_data_other = $dispatch_data_other->SetConnection('mysql2');
        $dispatch_data = $dispatch_data_other->where('dispatch_id', $id)->get();

        // $dispatch=DB::Connection('mysql2')
        // ->table('dispatches as d')
        // ->select('d.dispatch_no' , 'd.dispatch_date' , 'd.id' , 'so.so_no', 'p.packing_list_no' , 'd.customer_id' , 'pp.order_no' , 'dc.dc_no' , 'd.dispatch_status' , 's.sub_ic')
        // ->join('sales_order as so', 'so.id', '=', 'd.so_id')
        // ->join('packings as p', 'p.id', '=', 'd.packing_id')
        // ->join('delivery_note as dc', 'dc.id', '=', 'd.dc_id')
        // ->join('production_plane as pp', 'pp.id', '=', 'd.production_plan_id')
        // ->join('subitem as s', 's.id', '=', 'd.item_id')
        // ->where('d.status',1)->where('d.id',$id)->first();

        return view('Sales.AjaxPages.viewDispatchDetail', compact('dispatch', 'delivery_note_data', 'dispatch_data', 'id'));
    }

    public function viewDeliveryNoteDetailTwo($id)
    {

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('id', $id)->first();


        $delivery_note_data = DB::Connection('mysql2')->select('select a.id,a.master_id,a.qty,a.rate,a.amount,a.bundles_id,a.desc,
        a.item_id,a.discount_percent,a.discount_amount,b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
         from delivery_note_data a

        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.master_id="' . $id . '"

        group by a.groupby');
        $delivery_note_data_other = new DeliveryNoteData();
        $delivery_note_data_other = $delivery_note_data_other->SetConnection('mysql2');
        $delivery_note_data_other = $delivery_note_data_other->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewDeliveryNoteDetailTwo', compact('delivery_note', 'delivery_note_data', 'delivery_note_data_other', 'id'));
    }

    public function CreateSalesTaxInvoiceList()
    {
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('status', 1)->where('sales_tax_invoice', 0)->orderBy('id', 'DESC')->get();
        $Customers = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.CreateSalesTaxInvoiceList', compact('delivery_note', 'Customers'));
    }

    public function createInvoiceForm(Request $request)
    {

        $data = $request->job_order_id;
        $Id = $data[0];
        $data_id = implode(',', $data);

        $joborder = new JobOrder();
        $joborder = $joborder->SetConnection('mysql2');
        $joborder = $joborder->where('status', 1)->where('jo_status', 2)->where('job_order_id', $Id)->select('*')->first();

        $joborderdata = new JobOrderData();
        $joborderdata = $joborderdata->SetConnection('mysql2');
        $joborderdata = $joborderdata->where('status', 1)->whereIn('job_order_id', $data)->select('*')->get();

        // echo '<pre>';
        // print_r($joborderdata);


        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();

        $InvDesc = new InvDesc();
        $InvDesc = $InvDesc->SetConnection('mysql2');
        $InvDesc = $InvDesc->where('status', 1)->get();

        $Account = new Account();
        $Account = $Account->SetConnection('mysql2');
        $Account = $Account->where('status', 1)->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();


        return view('Sales.createInvoiceForm', compact('Id', 'joborder', 'joborderdata', 'client', 'InvDesc', 'Account', 'data_id'));
    }


    public function createInvoiceFormseprate($id)
    {



        echo 'sas';
    }
    public function editInvoice($Id)
    {
        $EditId = $Id;
        $Invoice = new Invoice();
        $Invoice = $Invoice->SetConnection('mysql2');
        $Invoice = $Invoice->where('status', 1)->where('id', $Id)->select('*')->first();
        $InvoiceData = new InvoiceData();
        $InvoiceData = $InvoiceData->SetConnection('mysql2');
        $InvoiceData = $InvoiceData->where('status', 1)->where('master_id', $Id)->select('*')->get();
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();
        $InvDesc = new InvDesc();
        $InvDesc = $InvDesc->SetConnection('mysql2');
        $InvDesc = $InvDesc->where('status', 1)->get();
        $Account = new Account();
        $Account = $Account->SetConnection('mysql2');
        $Account = $Account->where('status', 1)->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        return view('Sales.editInvoice', compact('EditId', 'Invoice', 'InvoiceData', 'client', 'InvDesc', 'Account'));
    }


    public function editQuotation($Id)
    {
        $EditId = $Id;
        $Quotation = new Quotation();
        $Quotation = $Quotation->SetConnection('mysql2');
        $Quotation = $Quotation->where('status', 1)->where('id', $Id)->select('*')->first();


        $QuotationData = new Quotation_Data();
        $QuotationData = $QuotationData->SetConnection('mysql2');
        $QuotationData = $QuotationData->where('status', 1)->where('master_id', $Id)->select('*')->get();
        return view('Sales.editQuotation', compact('EditId', 'Quotation', 'QuotationData'));
    }

    public function editClientBranchForm($BranchId)
    {


        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();
        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2');
        $Branch = $Branch->where('id', $BranchId)->where('status', 1)->select('id', 'acc_id', 'client_id', 'branch_name', 'ntn', 'strn', 'address')->first();


        return view('Sales.AjaxPages.editClientBranchForm', compact('Branch', 'client'));
    }



    public function addComplaint()
    {
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();

        $product = new Product();
        $product = $product->SetConnection('mysql2');
        $product = $product->where('p_status', 1)->select('*')->get();

        return view('Sales.addComplaint', compact('client', 'product'));
    }

    public function createTestForm()
    {
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();
        return view('Sales.createTestForm', compact('supplier'));
    }

    public function import_payment_process()
    {
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();
        return view('Sales.import_payment_process', compact('supplier'));
    }

    public function importDocumentList()
    {
        $ImportPo = DB::Connection('mysql2')->table('import_po')->where('status', 1)->get();
        return view('Sales.importDocumentList', compact('ImportPo'));
    }

    public function createCustomerOpeningBalance()
    {
        $Customers = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.createCustomerOpeningBalance', compact('Customers'));
    }

    public function creatVendorOpeningBalance()
    {
        $Supplier = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();
        return view('Sales.creatVendorOpeningBalance', compact('Supplier'));
    }




    public function complaintList()
    {
        $Complaint = new Complaint();
        $Complaint = $Complaint->SetConnection('mysql2');
        $Complaint = $Complaint->where('status', 1)->get();
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->select('*')->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->select('*')->get();

        return view('Sales.complaintList', compact('Complaint', 'Client', 'Region'));
    }

    public function CreateSalesTaxInvoice(Request $request)
    {

        // $sale_order_data = new DeliveryNoteData();
        // $sale_order_data = $sale_order_data->SetConnection('mysql2');
        // // $sale_order_data=DB::connection('mysql2')->table('delivery_note_data');
        // $sale_order_data = $sale_order_data->join('delivery_note', 'delivery_note.id', 'delivery_note_data.master_id')
        //     ->join('dispatches', 'dispatches.dc_id', 'delivery_note.id')
        //     ->join('dispatch_datas', 'dispatch_datas.dispatch_id', 'dispatches.id')
        //     ->select('delivery_note_data.*');
        // $sale_order_data = $sale_order_data->whereIn('dispatches.id', $request->checkbox)->orderBy('dispatches.id', 'ASC')->get();

        $ids = implode(',', $request->checkbox);

        $sale_order_data = DB::Connection('mysql2')->select('select a.item_id,a.groupby,a.id,b.master_id,b.bundles_id,a.id as so_data_id,a.desc,
        b.gd_no,sum(b.qty) as qty,a.master_id as so_id,b.warehouse_id,b.rate,b.tax,c.product_name,c.bundle_unit,c.qty as bqty,
        c.rate as bundle_rate,c.amount as bundle_amount ,c.discount_percent as b_percent,c.discount_amount as b_dis_amount,c.net_amount as b_net, s.hs_code_id,pt.type,s.pack_size,s.primary_pack_type,s.color

        from sales_order_data  a
        inner join
        delivery_note_data b
        on
        a.id=b.so_data_id
        join
        delivery_note d
        on
        d.id=b.master_id
        left join
        bundles c
        on
        a.bundles_id=c.id
        JOIN subitem AS s ON s.id = a.item_id
        JOIN packaging_type AS pt ON pt.id = s.primary_pack_type
        where a.status=1
        and b.status=1

        and d.id in (' . $ids . ')
        group by  a.groupby
        ');

        // echo "<pre>"; print_r($sale_order_data); die;

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_not = $delivery_note
            // ->join('dispatches', 'dispatches.dc_id', 'delivery_note.id')
            // ->select('delivery_note.*')
            // ->where('dispatches.status', 1)
            ->where('delivery_note.status', 1)
            ->whereIn('delivery_note.id', $request->checkbox)
            // ->whereIn('id',$request->checkbox)
            ->select('delivery_note.gd_no', 'delivery_note.gd_date', 'delivery_note.despacth_document_no', 'delivery_note.despacth_document_date', 'delivery_note.so_no', 'delivery_note.so_date', 'delivery_note.master_id', 'delivery_note.sales_tax_further_per', 'delivery_note.sales_tax_further', 'delivery_note.advance_tax_rate', 'delivery_note.advance_tax_amount', 'delivery_note.cartage_amount')->first();

        $so_id = $delivery_not->master_id;

        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order
            ->where('id', $so_id)->first();

        $accounts = new Account();
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->get();

        return view('Sales.CreateSalesTaxInvoice', compact('sales_order', 'sale_order_data', 'delivery_not', 'accounts', 'ids'));
    }

    public function EditSalesTaxInvoice($id)
    {
        // $id = Input::get('sales_order_id');
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        $sales_tax_invoice_data = new SalesTaxInvoiceData();
        $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');
        $sales_tax_invoice_data = $sales_tax_invoice_data->where('master_id', $id)->get();

        return view('Sales.EditSalesTaxInvoice', compact('sales_tax_invoice', 'sales_tax_invoice_data'));
    }

    public function viewSalesTaxInvoiceList()
    {

        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');

        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('status', 1)
            // ->whereBetween('gi_date',[$currentMonthStartDate,$currentMonthEndDate])
            ->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();

        return view('Sales.viewSalesTaxInvoiceList', compact('sales_tax_invoice', 'Customer'));
    }

    public function viewSalesTaxInvoiceDetailList()
    {

        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();

        return view('Sales.viewSalesTaxInvoiceDetailList', compact('Customer'));
    }

    public function viewSalesTaxInvoiceDetailListAjax(Request $request)
    {

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $customer_id = $request->customer_id;

        $data = DB::Connection('mysql2')->table('sales_tax_invoice as sti')
            ->join('sales_tax_invoice_data as stid', 'sti.id', '=', 'stid.master_id')
            ->join('customers as c', 'sti.buyers_id', '=', 'c.id')
            ->join('subitem as s', 'stid.item_id', '=', 's.id')
            ->join('uom as u', 'u.id', '=', 's.uom')
            ->join('sales_order as so', 'so.id', '=', 'sti.so_id')
            ->selectRaw('IFNULL(c.NTNNumber, c.cnic_ntn) as ntn, c.name, c.address, sti.gi_no, sti.gi_date, sum(stid.qty) qty, u.uom_name, SUM(stid.amount) as amount, so.sales_tax_rate, so.sales_tax_further');


        if ($fromDate) {
            $data = $data->where('sti.gi_date', '>=', $fromDate);
        }
        if ($toDate) {
            $data = $data->where('sti.gi_date', '<=', $toDate);

        }
        if ($customer_id) {
            $data = $data->where('c.id', $customer_id);

        }

        $data = $data->groupBy('so.id')->get();



        return view('Sales.AjaxPages.viewSalesTaxInvoiceDetailListAjax', compact('data'));
    }

    public function viewSalesTaxInvoiceDetail()
    {
        $ID = Input::get('id');
        $Checking = $ID;
        $Checking = explode(',', $Checking);

        if (count($Checking) > 1) {
            $si = DB::Connection('mysql2')->table('sales_tax_invoice')->where('gi_no', $Checking[1])->select('id')->first();
            $id = $si->id;
        } else {
            $id = $Checking[0];
        }
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        $sales_tax_invoice_data = DB::Connection('mysql2')->select('
            SELECT 
                a.item_id,
                a.uom,
                a.qty,
                a.rate,
                a.tax as tax,
                a.tax_amount,
                a.amount,
                a.gd_no,
                a.bundles_id,
                a.so_data_id,
                a.description,
                b.rate as bundle_rate,
                b.amount as bundle_amount,
                b.discount_percent as b_percent,
                b.discount_amount as b_dis_amount,
                b.net_amount as b_net,
                b.product_name,
                b.qty as bqty,
                b.bundle_unit,
                a.so_type,
                a.dn_data_ids,
                a.sales_tax_further,
                a.sales_tax_further_per,
                pt.type,
                s.pack_size,
                s.secondary_pack_size,
                s.hs_code_id,
                s.primary_pack_type,
                s.color,

                -- dynamic pack size based on UOM match
                CASE 
                    WHEN a.uom = s.uom2 AND s.uom2 IS NOT NULL 
                        THEN s.secondary_pack_size
                    ELSE s.pack_size
                END as final_pack_size

            FROM sales_tax_invoice_data a

            LEFT JOIN bundles b ON a.bundles_id = b.id
            JOIN subitem s ON s.id = a.item_id
            JOIN uom u ON u.id = a.uom
            JOIN packaging_type pt ON pt.id = s.primary_pack_type

            WHERE a.status = 1
            AND a.master_id = "' . $id . '"
            ');

// dd($sales_tax_invoice_data);

        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id', $id);
        $sales_tax_invoice_data_other = DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewSalesTaxInvoiceDetail', compact('sales_tax_invoice', 'sales_tax_invoice_data', 'AddionalExpense', 'sales_tax_invoice_data_other'));
    }

    public function viewReceivedAllVoucher()
    {
        $id = Input::get('id');
        $AllReceipt = DB::Connection('mysql2')->table('received_paymet')->where('status', 1)->where('sales_tax_invoice_id', $id)->get();
        return view('Sales.viewReceivedAllVoucher', compact('AllReceipt'));
    }

    public function PrintSalesTaxInvoice()
    {
        $id = Input::get('id');
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        //        $sales_tax_invoice_data=new SalesTaxInvoiceData();
//        $sales_tax_invoice_data=$sales_tax_invoice_data->SetConnection('mysql2');
//        $sales_tax_invoice_data=$sales_tax_invoice_data->where('master_id',$id)->get();


        $sales_tax_invoice_data = DB::Connection('mysql2')->select('select a.item_id,a.qty,a.rate,a.discount as discount_percent ,a.discount_amount,a.amount,a.gd_no,a.bundles_id,a.so_data_id,
        a.description,b.rate as bundle_rate, b.amount as bundle_amount , b.discount_percent as b_percent, b.discount_amount as b_dis_amount, b.net_amount as b_net, b.product_name, b.qty as bqty
        ,b.bundle_unit,a.so_type
        from sales_tax_invoice_data  a
        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.status=1
        and a.master_id  ="' . $id . '"
        group by  a.groupby
        ');



        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id', $id);

        return view('Sales.AjaxPages.PrintSalesTaxInvoice', compact('sales_tax_invoice', 'sales_tax_invoice_data', 'AddionalExpense'));
    }


    public function PrintSalesTaxInvoiceDirect()
    {
        $id = Input::get('id');
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        //        $sales_tax_invoice_data=new SalesTaxInvoiceData();
//        $sales_tax_invoice_data=$sales_tax_invoice_data->SetConnection('mysql2');
//        $sales_tax_invoice_data=$sales_tax_invoice_data->where('master_id',$id)->get();


        $sales_tax_invoice_data = DB::Connection('mysql2')->select('select a.item_id,a.qty,a.rate,a.tax as tax ,a.tax_amount,a.amount,a.gd_no,a.bundles_id,a.so_data_id,
        a.description,b.rate as bundle_rate, b.amount as bundle_amount , b.discount_percent as b_percent, b.discount_amount as b_dis_amount, b.net_amount as b_net, b.product_name, b.qty as bqty
        ,b.bundle_unit,a.so_type,a.dn_data_ids, a.sales_tax_further, a.sales_tax_further_per, pt.type, s.pack_size, s.hs_code_id, s.primary_pack_type, s.color
        from sales_tax_invoice_data  a
        left join
        bundles b
        on
        a.bundles_id=b.id
        JOIN subitem AS s ON s.id = a.item_id
        JOIN packaging_type AS pt ON pt.id = s.primary_pack_type
        where a.status=1
        and a.master_id  ="' . $id . '"
        ');



        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id', $id);
        $sales_tax_invoice_data_other = DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id', $id)->get();

        return view('Sales.AjaxPages.PrintSalesTaxInvoiceDirect', compact('sales_tax_invoice', 'sales_tax_invoice_data', 'AddionalExpense', 'id'));
    }

    public function CreateReceiptVoucherList()
    {
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        $SiMaster = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status', 1)->get();

        return view('Sales.CreateReceiptVoucherList', compact('Customer', 'SiMaster'));
    }
    public function receiptVoucherList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');
        $accounts = new Account;
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->select('id', 'name', 'code')->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();

        $NewRvs = new NewRvs();
        $NewRvs = $NewRvs->SetConnection('mysql2');
        $NewRvs = $NewRvs->where('status', 1)->where('sales', 1)->whereBetween('rv_date', [$currentMonthStartDate, $currentMonthEndDate])->orderBy('id', 'DESC')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.receiptVoucherList', compact('NewRvs', 'accounts'));
    }

    public function editVoucherList()
    {
        $id = $_GET['id'];
        $accounts = new Account;
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->select('id', 'name', 'code')->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();

        $NewRvs = new NewRvs();
        $NewRvs = $NewRvs->SetConnection('mysql2');
        $NewRvs = $NewRvs->where('status', 1)->where('sales', 1)->where('id', $id)->first();

        $NewRvsData = DB::Connection('mysql2')->table('new_rv_data')->where('status', 1)->where('master_id', '=', $id)->get();
        $brige_table = DB::Connection('mysql2')->table('brige_table_sales_receipt')->where('status', 1)->where('rv_id', '=', $id)->get();

        return view('Sales.editVoucherList', compact('NewRvs', 'NewRvsData', 'brige_table', 'accounts', 'id'));
    }

    public function undertaking()
    {

        $id = Input::get('id');
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        return view('Sales.undertaking', compact('sales_tax_invoice'));
    }
    public function CreateCustomerCreditNote()
    {
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('status', 1)->get();
        return view('Sales.CreateCustomerCreditNote', compact('sales_tax_invoice'));
    }

    public function addCustomerCredit_no(Request $request)
    {
        $values = $request->checkbox;
        $buyer_id = $request->buyer_id;
        $type = $request->type;

        return view('Sales.addCustomerCredit_no', compact('values', 'buyer_id', 'type'));
    }

    public function editCustomerCredit_no($id)
    {
        $credit_note = new CreditNote();
        $credit_note = $credit_note->SetConnection('mysql2');
        $credit_note = $credit_note->find($id);
        // dd($credit_note->creditNoteData);
        return view('Sales.editCustomerCredit_no', compact('credit_note'));
    }

    public function viewCustomerCreditNoteList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate = date('Y-m-t');

        $credit_note = new CreditNote();
        $credit_note = $credit_note->SetConnection('mysql2');
        $credit_note = $credit_note->where('status', 1)->whereBetween('cr_date', [$currentMonthStartDate, $currentMonthEndDate])->orderBy('id', 'DESC')->get();
        return view('Sales.viewCustomerCreditNoteList', compact('credit_note'));
    }

    public function uploadCreditCustomer(request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {

            $fileMimes = array(
                // 'text/x-comma-separated-values',
                // 'text/comma-separated-values',
                // 'application/octet-stream',
                // 'application/vnd.ms-excel',
                'application/x-csv',
                'text/x-csv',
                'text/csv',
                'application/csv',
                // 'application/excel',
                // 'application/vnd.msexcel',
                // 'text/plain'
            );

            // Validate whether selected file is a CSV file
            if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

                $row = 0;
                // add you row number for skip
                // hear we pass 1st row for skip in csv
                $skip_row_number = array("1");

                // Open uploaded CSV file with read-only mode
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                // Skip the first line
                fgetcsv($csvFile);

                // Parse data from CSV file line by line
                // Parse data from CSV file line by line
                while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {

                    if (in_array($row, $skip_row_number)) {
                        continue;
                        // skip row of csv
                    } else {

                        if ($getData[0] && $getData[1]) {

                            // (!empty($getData[1])) ? $city = DB::connection('mysql')->table('cities')->whereRaw('LOWER(name) = ?', [strtolower($getData[1])])->value('id') : 0;
                            CommonHelper::companyDatabaseConnection(Input::get('m'));



                            $account_head = Input::get('account_head');
                            $customer_code = SalesHelper::generateCustomerCode();
                            $customer_name = $getData[0];

                            if (DB::connection('mysql2')->table('customers')->where('status', 1)->where('name', $customer_name)->count() > 0) {
                                continue;
                            }

                            $state = 0;
                            //    $country=0;
                            //     if($city > 0){
                            //        $state= DB::connection('mysql')->table('cities')->select('state_id')->where('id',$city)->value('state_id');
                            //        $country = DB::connection('mysql')->table('states')->select('country_id')->where('id',$state)->value('country_id');

                            //     }


                            $country = $getData[5];
                            $tel = $getData[1];
                            $email = $getData[2];
                            $address = $getData[3];
                            $city = $getData[4];
                            $postal = $getData[6];
                            $contact_person = $getData[7];
                            $contact_person_no = $getData[8];
                            $contact_person_email = $getData[9];
                            $ntn = $getData[10];
                            $atl_status = $getData[11];
                            $sales_tax_status = $getData[12];
                            $status_us_236g_h = $getData[13];
                            $term = $getData[14];
                            $no_of_days = $getData[15];
                            $credit_limit = $getData[16];
                            $remarks = $getData[17];



                            $o_blnc_trans = Input::get('o_blnc_trans');
                            $o_blnc = Input::get('o_blnc');
                            $operational = '1';
                            $customer_type = '3';





                            $account_head = 'Accounts Receivables';
                            $sent_code = '1-2';//'Trade Receivables';
                            // $sent_code = $account_head;

                            $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $sent_code . '\'')->id;

                            if ($max_id == '') {
                                $code = $sent_code . '-1';
                            } else {
                                $max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
                                $max_code2;
                                $max = explode('-', $max_code2);
                                $code = $sent_code . '-' . (end($max) + 1);
                            }

                            $level_array = explode('-', $code);
                            $counter = 1;
                            foreach ($level_array as $level):
                                $data1['level' . $counter] = strip_tags($level);
                                $counter++;
                            endforeach;

                            $data1['code'] = strip_tags($code);
                            $data1['name'] = $customer_name;
                            $data1['parent_code'] = strip_tags($sent_code);
                            $data1['username'] = Auth::user()->name;
                            $data1['date'] = date("Y-m-d");
                            $data1['time'] = date("H:i:s");
                            $data1['action'] = 'create';
                            $data1['type'] = 1;
                            $data1['operational'] = 1;
                            $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);

                            $data2['acc_id'] = $acc_id;
                            $data2['name'] = $customer_name;
                            $data2['customer_code'] = $customer_code;
                            $data2['country'] = $country ?? '';
                            $data2['province'] = $state ?? '';
                            $data2['city'] = $city ?? '';
                            $data2['cnic_ntn'] = $ntn ?? '';
                            $data2['strn'] = $ntn ?? '';
                            $data2['contact_person'] = $contact_person ?? '';
                            $data2['contact'] = $tel ?? '';
                            // $data2['fax']   		    = $fax ?? '';
                            $data2['address'] = $address ?? '';

                            $data2['email'] = $email ?? '';
                            $data2['username'] = Auth::user()->name;
                            $data2['date'] = date("Y-m-d");
                            $data2['time'] = date("H:i:s");
                            $data2['action'] = 'create';
                            $data2['customer_type'] = $customer_type;

                            $data2['postal_address'] = $postal;
                            $data2['contact_person'] = $contact_person;
                            $data2['contact_person_no'] = $contact_person_no;
                            $data2['contact_person_email'] = $contact_person_email;
                            $data2['cnic_ntn'] = $ntn;
                            // $data2['atl_status']     = strtolower($atl_status) == 'active' ? 1 : 2;
                            if (strpos(strtolower($atl_status), 'inactive') !== false) {
                                $data2['atl_status'] = 2;
                            } elseif (strpos(strtolower($atl_status), 'active') !== false) {
                                $data2['atl_status'] = 1;
                            }
                            if (strpos(strtolower($sales_tax_status), 'unregistered') !== false) {
                                $data2['regd_in_sales_tax'] = 2;
                            } elseif (strpos(strtolower($sales_tax_status), 'registered') !== false) {
                                $data2['regd_in_sales_tax'] = 1;
                            }
                            // $data2['regd_in_sales_tax']     = strtolower($sales_tax_status) == 'registered' ? 1 : 2;
                            if (strpos(strtolower($status_us_236g_h), 'manufacturer') !== false) {
                                $data2['status_us_236g_h'] = 1;
                            } elseif (strpos(strtolower($status_us_236g_h), 'wholesaler') !== false || strpos(strtolower($status_us_236g_h), 'distributor') !== false) {
                                $data2['status_us_236g_h'] = 2;
                            } elseif (strpos(strtolower($status_us_236g_h), 'retailer') !== false || strpos(strtolower($status_us_236g_h), 'others') !== false) {
                                $data2['status_us_236g_h'] = 3;
                            }
                            if (strpos(strtolower($term), 'advance') !== false) {
                                $data2['terms_of_payment'] = 1;
                            } elseif (strpos(strtolower($term), 'against delivery') !== false) {
                                $data2['terms_of_payment'] = 2;
                            } elseif (strpos(strtolower($term), 'credit') !== false) {
                                $data2['terms_of_payment'] = 3;
                            }
                            $data2['no_of_days'] = $no_of_days;
                            $data2['creditLimit'] = $credit_limit;
                            $data2['remarks'] = $remarks;








                            // $data2['credit_days']     = $credit_days;
                            // $data2['discount_percent']     = $dicount;
                            $CustId = DB::table('customers')->insertGetId($data2);

                            $data3['acc_id'] = $acc_id;
                            $data3['acc_code'] = $code;
                            $data3['debit_credit'] = 1;
                            $data3['amount'] = 0.00;
                            $data3['opening_bal'] = 1;
                            $data3['username'] = Auth::user()->name;
                            $data3['date'] = date("Y-m-d");
                            $data3['v_date'] = '2023-07-01';
                            $data3['time'] = date("H:i:s");
                            $data3['action'] = 'create';
                            DB::table('transactions')->insert($data3);

                            $contact_person_more = Input::get('contact_person_more');
                            $contact_no_more = Input::get('contact_no_more');
                            $fax_more = Input::get('fax_more');
                            $address_more = Input::get('address_more');
                            if (isset($contact_person_more)):
                                foreach ($contact_person_more as $key => $row) {
                                    if ($contact_person_more[$key] != "" || $contact_no_more[$key] != "" || $fax_more[$key] != "" || $address_more[$key] != "") {
                                        $InfoData['cust_id'] = $CustId;
                                        $InfoData['contact_person'] = $contact_person_more[$key] ?? '';
                                        $InfoData['contact_no'] = $contact_no_more[$key] ?? 0;
                                        $InfoData['fax'] = $fax_more[$key] ?? '';
                                        $InfoData['address'] = $address_more[$key] ?? '';
                                        DB::Connection('mysql2')->table('customer_info')->insert($InfoData);
                                    }
                                }
                            endif;
                            CommonHelper::reconnectMasterDatabase();
                        }

                    }

                }

                // Close opened CSV file
                fclose($csvFile);

                CommonHelper::reconnectMasterDatabase();
                Session::flash('dataInsert', 'Successfully Saved.');

            } else {
                Session::flash('dataDelete', 'Please upload csv file');

            }

            DB::Connection('mysql2')->commit();

        } catch (Exception $ex) {


            DB::rollBack();
            dd($ex->getMessage());

        }

        return Redirect::to('/sales/createCreditCustomerForm?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('m') . '#SFR');

    }

    public function viewCustomer(Request $request)
    {
        $id = $request->id;
        return view('Sales.viewCustomer', compact('id'));
    }
    public function customerOrderTracking(Request $request)
    {
        return view('Sales.customerOrderTracking');
    }

    public function viewCreditNoteDetail()
    {
        $id = Input::get('id');
        $creit_note = new CreditNote();
        $creit_note = $creit_note->SetConnection('mysql2');
        $creit_note = $creit_note->where('id', $id)->first();

        $credit_note_data = new CreditNoteData();
        $credit_note_data = $credit_note_data->SetConnection('mysql2');
        $credit_note_data = $credit_note_data->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewCreditNoteDetail', compact('creit_note', 'credit_note_data'));
    }

    public function createType()
    {
        return view('Sales.createType');
    }

    public function createConditions()
    {
        return view('Sales.createConditions');
    }

    public function createSurveyBy()
    {
        return view('Sales.createSurveyBy');
    }

    public function typeList()
    {
        $type = new Type();
        $type = $type->SetConnection('mysql2');
        $type = $type->where('status', 1)->get();

        return view('Sales.typeList', compact('type'));
    }

    public function conditionList()
    {
        $conditions = new Conditions();
        $conditions = $conditions->SetConnection('mysql2');
        $conditions = $conditions->where('status', 1)->get();

        return view('Sales.conditionList', compact('conditions'));
    }

    public function clientJobList()
    {
        $ClientJob = new ClientJob();
        $ClientJob = $ClientJob->SetConnection('mysql2');
        $ClientJob = $ClientJob->where('status', 1)->get();

        return view('Sales.clientJobList', compact('ClientJob'));
    }


    public function branchList()
    {
        $survery_by = new SurveryBy();
        $survery_by = $survery_by->SetConnection('mysql2');
        $survery_by = $survery_by->where('status', 1)->get();

        return view('Sales.branchList', compact('survery_by'));
    }

    public function surveylist()
    {
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->get();
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->select('*')->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->select('*')->get();

        return view('Sales.surveylist', compact('survey', 'Client', 'Region'));
    }

    public function jobtrackinglist()
    {
        $jobtracking = new JobTracking();
        $jobtracking = $jobtracking->SetConnection('mysql2');
        $jobtracking = $jobtracking->where('status', 1)->get();

        return view('Sales.jobtrackinglist', compact('jobtracking'));
    }

    public function addquotationForm()
    {
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->where('survey_status', 2)->where('quotation_type', 0)->get();
        return view('Sales.addquotationForm', compact('survey'));
    }
    public function quotationList()
    {
        $quotation = new Quotation();
        $quotation = $quotation->SetConnection('mysql2');
        $quotation = $quotation->where('status', 1)->get();
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->select('*')->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->select('*')->get();
        return view('Sales.quotationList', compact('quotation', 'Client', 'Region'));
    }

    public function invoiceList()
    {
        $invoice = new Invoice();
        $invoice = $invoice->SetConnection('mysql2');
        $invoice = $invoice->where('status', 1)->where('type', 0)->orderBy('id', 'DESC')->get()->take(50);

        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->get();


        return view('Sales.invoiceList', compact('invoice', 'Client'));
    }




    public function addClient()
    {
        return view('Sales.addClient');
    }

    public function createBranch()
    {
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->get();
        return view('Sales.createBranch', compact('Client'));
    }

    public function addDesc()
    {
        return view('Sales.addDesc');
    }
    public function invoiceDescList()
    {

        $InvDesc = new InvDesc();
        $InvDesc = $InvDesc->SetConnection('mysql2');
        $InvDesc = $InvDesc->where('status', 1)->get();
        return view('Sales.invoiceDescList', compact('InvDesc'));
    }



    public function addClientJob()
    {
        return view('Sales.addClientJob');
    }

    public function addClientJobAjax()
    {
        return view('Sales.addClientJobAjax');
    }
    public function addBranchAjax()
    {
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->get();
        return view('Sales.addBranchAjax', compact('Client'));
    }


    public function clientList()
    {

        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();


        $Account = new Account();
        $Account = $Account->SetConnection('mysql2');
        $Account = $Account->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();

        return view('Sales.clientList', compact('client', 'Account'));
    }

    public function clientBranchList()
    {

        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2');
        $Branch = $Branch->where('status', 1)->get();

        return view('Sales.clientBranchList', compact('Branch'));
    }


    public function jobTrackingSheetCopy()
    {
        $customer = new Customer();
        $customer = $customer->SetConnection('mysql2');
        $customer = $customer->where('status', 1)->get();
        $region = new Region();
        $region = $region->SetConnection('mysql2');
        $region = $region->where('status', 1)->get();
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->get();
        $cities = new Cities();
        //$cities = $cities->SetConnection('mysql2');
        $cities = $cities->where('status', 1)->get();
        return view('Sales.jobTrackingSheetCopy', compact('customer', 'region', 'survey', 'cities'));
    }

    public function createProductType()
    {
        return view('Sales.createProductType');
    }

    public function createResourceAssigned()
    {
        return view('Sales.createResourceAssigned');
    }

    public function producttypeList()
    {
        $productType = new ProductType();
        $productType = $productType->SetConnection('mysql2');
        $productType = $productType->where('status', 1)->get();
        return view('Sales.producttypeList', compact('productType'));
    }

    public function resourceAssignedList()
    {
        $resourceAssign = new ResourceAssigned();
        $resourceAssign = $resourceAssign->SetConnection('mysql2');
        $resourceAssign = $resourceAssign->where('status', 1)->get();
        return view('Sales.resourceAssignedList', compact('resourceAssign'));
    }
    public function createInvoice()
    {
        $joborder = new JobOrder();
        $joborder = $joborder->SetConnection('mysql2');
        $joborder = $joborder->where('status', 1)->where('jo_status', 2)->where('invoice_created', 0)->select('*')->get();
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();
        return view('Sales.createInvoice', compact('joborder', 'client'));
    }

    public function logActivity()
    {
        return view('Sales.logActivity');
    }

    public function CreateSalesTaxInvoiceBySO(Request $request)
    {
        $so_no = $request->so_no;
        $so_no = Sales_Order::where('id', $so_no)->first()->so_no;
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note
            // ->join('dispatches as dp', 'delivery_note.id', 'dp.dc_id')
            ->select('delivery_note.*')
            ->where('delivery_note.status', 1)
            ->where('delivery_note.sales_tax_invoice', 0)
            // ->where('dp.dispatch_status', 2)
            // ->where('dp.sales_tax_invoice', 1)
            // ->where('dp.status', 1)
            ->where('delivery_note.so_no', $so_no)->get();
        // dd($delivery_note->toArray(),$so_no);
        return view('Sales.AjaxPages.CreateSalesTaxInvoiceBySO', compact('delivery_note'));
    }

    public function dn_without_Sales(Request $request)
    {


        return view('Sales.dn_without_Sales');
    }
    public function salesTaxInvoiceReportPage()
    {
        return view('Sales.salesTaxInvoiceReportPage');
    }

    public function cogs_si(Request $request)
    {
        return view('Sales.cogs_si');
    }
    public function pos_list(Request $request)
    {

        return view('Sales.pos_list');
    }

    public function po_detail(Request $request)
    {
        $id = $request->id;
        return view('Sales.AjaxPages.po_detail', compact('id'));
    }
    public function view_convert_grn(Request $request)
    {
        $id = $request->id;
        return view('Sales.view_convert_grn', compact('id'));
    }


    public function approve_so(Request $request)
    {
        $id = $request->id;
        $so_status = 0;
        $approve_user = '';
        $approve = '';
        $send_behavior = '';
        $so_data = DB::Connection('mysql2')->table('sales_order')->where('id', $id)->first();
        $so_no = $so_data->so_no;
        $dept_id = $so_data->department;
        $p_type = $so_data->p_type;

        if ($so_data->approve_user_1 == ''):
            $so_status = 1;
            $approve_user = 'approve_user_1';
            $approve = 'Approved';
            $send_behavior = 'Approve 1';
        elseif ($so_data->approve_user_2 == ''):
            $so_status = 3;
            $approve_user = 'approve_user_2';
            $approve = '2nd Approved';
            $send_behavior = 'Approve 2';
        elseif ($so_data->approve_user_3 == ''):
            $so_status = 4;
            $approve_user = 'approve_user_3';
            $approve = 'Approved';
            $send_behavior = 'Approve 3';
        endif;

        DB::Connection('mysql2')->table('sales_order')
            ->where('id', $id)
            ->update([$approve_user => Auth::user()->name, 'so_status' => $so_status]);



        $voucher_no = $so_no;
        $subject = 'Sales Order Approve For ' . $so_no;
        NotificationHelper::send_email('Sales Order', $send_behavior, $dept_id, $voucher_no, $subject, $p_type);

        echo $approve;
    }


    public static function si_approve(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $id = $request->id;
            $approve = '';
            $behavior = '';
            $si_data = DB::Connection('mysql2')->table('sales_tax_invoice')->where('id', $id)->first();

            $so_type = $si_data->si_status;
            $gi_no = $si_data->gi_no;
            $so_id = $si_data->so_id;
            $so_no = $si_data->so_no;

            if ($so_type == 0):
                DB::Connection('mysql2')->table('sales_tax_invoice')
                    ->where('id', $id)
                    ->update(['approve_user_1' => Auth::user()->name, 'si_status' => 2]);
                $approve = '1st Approved';
                $behavior = 'Approve 1';
            else:
                DB::Connection('mysql2')->table('sales_tax_invoice')
                    ->where('id', $id)
                    ->update(['approve_user_2' => Auth::user()->name, 'si_status' => 3]);


                DB::Connection('mysql2')->table('transactions')
                    ->where('voucher_no', $gi_no)
                    ->where('status', 100)
                    ->update(['status' => 1]);
                $approve = 'Approved';
                $behavior = 'Approve 2';

                $sales_tax_invoice_data = new SalesTaxInvoiceData();
                $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');
                $sales_tax_invoice_data = $sales_tax_invoice_data->where('status', 1)->where('master_id', $id)->get();
                if ($si_data->so_no == ''):
                    foreach ($sales_tax_invoice_data as $key => $row):

                        $qty = ReuseableCode::get_stock($row->item_id, $row->warehouse_id, $row->qty, 0);
                        if ($qty < 0):
                            DB::rollBack();
                            return 0;
                        endif;

                        $average_cost = ReuseableCode::average_cost_sales(
                            $row->item_id,
                            $row->warehouse_id,
                            0
                        );



                        $stock = array
                        (
                            'main_id' => $row->master_id,
                            'master_id' => $row->id,
                            'voucher_no' => $row->gi_no,
                            'voucher_date' => $si_data->gi_date,
                            'supplier_id' => 0,
                            'customer_id' => $row->buyers_id,
                            'voucher_type' => 5,
                            'rate' => $row->rate,
                            'sub_item_id' => $row->item_id,
                            'batch_code' => 0,
                            'qty' => $row->qty,
                            'discount_percent' => '',
                            'discount_amount' => '',
                            'amount' => $row->qty * $average_cost,
                            'status' => 1,
                            'warehouse_id' => $row->warehouse_id,
                            'username' => Auth::user()->username,
                            'created_date' => date('Y-m-d'),
                            'created_date' => date('Y-m-d'),
                            'opening' => 0,
                            'so_data_id' => '',
                        );
                        DB::Connection('mysql2')->table('stock')->insert($stock);
                    endforeach;
                endif;
            endif;
            if ($so_id != 0):
                $voucher_no = $gi_no;
                $dept_and_type = NotificationHelper::get_dept_id('sales_order', 'id', $so_id)->select('department', 'p_type')->first();
                $dept_id = $dept_and_type->department;
                $p_type = $dept_and_type->p_type;
                $subject = 'Sales Tax Invoice Approved For ' . $so_no;
                NotificationHelper::send_email('Sales tax Invoice', $behavior, $dept_id, $voucher_no, $subject, $p_type);
            endif;
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
            echo $ex->getLine();

        }
        return $approve;
    }

    public function editDirectSalesTaxInvoice($id)
    {
        $sale_tax_invoice = DB::Connection('mysql2')->table('sales_tax_invoice')->where('id', $id)->first();

        if ($sale_tax_invoice->si_status == 3):
            dd('Approved Voucher Can not be Edit');
        endif;
        $sale_tax_invoice_data = DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id', $id)->where('status', 1)->get();

        $additional_expense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id', $id)->get();
        return view('Sales.editDirectSalesTaxInvoice', compact('sale_tax_invoice', 'sale_tax_invoice_data', 'additional_expense'));

    }
}
