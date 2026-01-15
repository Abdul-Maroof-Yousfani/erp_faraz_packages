<?php

namespace App\Http\Controllers;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Http\Requests;
use App\Models\DeliveryNote;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
use Auth;
use DB;
use Config;
use App\Models\Customer;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\GRNData;
use App\Models\Batch;
use App\Models\Subitem;
use App\Models\UOM;
use App\Models\SalesTaxInvoiceData;
use App\Models\SalesTaxInvoice;
use App\Models\Region;
use App\Models\Cities;
use App\Models\JobTracking;
use App\Models\JobTrackingData;
use App\Models\Survey;
use App\Models\Conditions;
use App\Models\Product;
use App\Models\Client;
use App\Models\Branch;
use App\Models\SurveryBy;
use App\Models\ProductType;
use App\Models\ResourceAssigned;
use App\Models\Type;
use App\Models\SurveyData;
use App\Models\Complaint;
use App\Models\LOGACTIVITY;
use App\Models\Transactions;
use App\Models\JobOrder;
use App\Models\JobOrderData;
use App\Models\CreditNote;

use Illuminate\Support\Facades\Input;


class SalesDataCallController extends Controller
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
    public function sals_history()
    {

        $id = Input::get('id');

        $sub_item_des_clause='';
        $sub_item_des_heading='';
        $category_join='';
        $category_heading='';
        $category_clause='';
        $category_column='';
        $item_clause='';
        $item_join='';
        $item_heading='';
        $location_caluse='';
        $location_heading='';

        if ($id!=''):
            $sub_item_clause="and a.sub_item_id=".$id;
            $generic= CommonHelper::generic('subitem',array('id'=>$id),array('sub_ic'))->first();
            $sub_item_heading='Item :'.$generic->sub_ic;
        endif;

        $category=  DB::Connection('mysql2')->select('select a.* '.$category_column.' from stock a
            inner JOIN subitem b ON b.id=a.sub_item_id
            '.$category_join.' '.$item_join.' where b.status=1 and a.status=1
           '.$category_clause.'
            '.$item_clause.'
            '.$location_caluse.'
            '.$sub_item_clause.' group by a.sub_item_id, a.warehouse_id');

            $data=DB::Connection('mysql2')->table('delivery_note as a')
            ->join('delivery_note_data as b','a.id','=','b.master_id')
            ->where('item_id',$id)
            ->get();

        return view('Sales.AjaxPages.sals_history', compact('data','category','item_heading','location_heading','sub_item_heading','sub_item_des_heading','category_heading'));
    }

    public function viewPaymentDetail()
    {
        return view('Sales.AjaxPages.viewPaymentDetail');
    }
    public function get_commission_data_ajax()
    {
        return view('Sales.AjaxPages.get_commission_data_ajax');
    }

    public function getTopFiveSalesReport(Request $request)
    {

        $ItemId = $request->subItems;
        $CustomerId = $request->CustomerId;

        $SalesTaxInvoice = DB::Connection('mysql2')->select('select a.id,a.gi_no,a.gi_date,b.amount from sales_tax_invoice a
                                          INNER JOIN sales_tax_invoice_data b ON b.master_id = a.id
                                          where a.buyers_id = '.$CustomerId.'
                                          and b.item_id = '.$ItemId.'
                                          and a.status = 1
                                          order by b.id desc limit 0,5');
        $SalesReturn = DB::Connection('mysql2')->select('select a.id,a.cr_no,a.cr_date,b.amount from credit_note a
                                          INNER JOIN credit_note_data b ON b.master_id = a.id
                                          where a.buyer_id = '.$CustomerId.'
                                          and b.item = '.$ItemId.'
                                          and a.status = 1
                                          order by b.id desc limit 0,5');
        $AccId = DB::Connection('mysql2')->table('customers')->where('id',$CustomerId)->select('acc_id')->first();
        $ReceiptVoucher = DB::Connection('mysql2')->select('select a.id,a.rv_no,a.rv_date,b.amount from new_rvs a
                                          INNER JOIN new_rv_data b ON b.master_id = a.id
                                          where b.acc_id = '.$AccId->acc_id.'
                                          and a.sales = 1
                                          and a.status = 1
                                          order by b.id desc limit 0,5');

        return view('Sales.AjaxPages.getTopFiveSalesReport',compact('SalesTaxInvoice','SalesReturn','ReceiptVoucher'));
    }


    public function getCommissionColumn(Request $request)
    {
        $AgentId = $request->AgentId;
        $Data = DB::Connection('mysql2')->table('commision')->where('status',1)->where('agent',$AgentId)->get();
        echo '<option value="">Select</option>';
        foreach($Data as $Dfil):
            echo '<option value="'.$Dfil->id.'">Voucher No = '.$Dfil->voucher_no.' / From Date = '.CommonHelper::changeDateFormat($Dfil->from).' / To Date = '.CommonHelper::changeDateFormat($Dfil->to).'</option>';
        endforeach;
    }


    public function updateScNo(Request $request)
    {
        $ScNo = $request->ScNo;
        $Id = $request->Id;
        $UpdateData['sc_no'] = $ScNo;
        DB::Connection('mysql2')->table('sales_tax_invoice')->where('id',$Id)->update($UpdateData);
        echo "Update Successfully...!";
    }

    public function update_agent_in_customer(Request $request)
    {
         $AgentIds = $request->AgentIds;
        $Id = $request->Id;


        $UpdateData['sale_agent_id'] = 0;
        $UpdateData['purchase_agent_id'] = 0;
        DB::Connection('mysql2')->table('customers')->where('id',$Id)->update($UpdateData);

         $data = $request->AgentIds;


        if($AgentIds !="")
        {
            $AgentData = DB::Connection('mysql2')->table('sales_agent')->whereIn('id',$AgentIds)->get();



            foreach($AgentData as $ag):



                if($ag->type == 1)
                {
                    $UpdateData['sale_agent_id'] = $ag->id;
                    DB::Connection('mysql2')->table('customers')->where('id',$Id)->update($UpdateData);

                }


                if($ag->type == 2)
                {
                    $UpdateData['purchase_agent_id'] = $ag->id;
                    DB::Connection('mysql2')->table('customers')->where('id',$Id)->update($UpdateData);

                }



            endforeach;
        }
        else
        {
            $UpdateData['sale_agent_id'] = 0;
            $UpdateData['purchase_agent_id'] = 0;
            DB::Connection('mysql2')->table('customers')->where('id',$Id)->update($UpdateData);
        }

        echo 'Updated';
    }




    public function getSoTrackingQtyAjax()
    {
        return view('Sales.AjaxPages.getSoTrackingQtyAjax');
    }
    
    public function getSobyCustomer(Request $request)
    {
        $customer_id = $request->customer_id;
        $Data = DB::Connection('mysql2')->table('sales_order')->where('status',1)->where('buyers_id',$customer_id)->get();
        echo '<option value="">Select</option>';
        foreach($Data as $value):
            echo "<option value='$value->id'> $value->so_no </option>";
        endforeach;
    }
    public function getFreightCollectionReport()
    {
        return view('Sales.AjaxPages.getFreightCollectionReport');
    }




    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
   	public function viewCashCustomerList(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
		$customers = new Customer;
		$customers = $customers::where('status', '=', '1')
						->where('customer_type', '=', '2')->get();
        CommonHelper::reconnectMasterDatabase();
		$counter = 1;
		foreach($customers as $row){
	?>
		<tr>
			<td class="text-center"><?php echo $counter++;?></td>
			<td><?php echo $row['name'];?></td>
			<td class="text-center"><?php echo $row['contact'];?></td>
            <td class="text-center"><?php echo $row['cnic_ntn'];?></td>
            <td class="text-center"><?php echo $row['strn'];?></td>
			<td class="text-center"><?php echo $row['email'];?></td>
			<td></td>
		</tr>
	<?php
		}
	}

	public function viewCreditCustomerList(){

        $delete=ReuseableCode::check_rights(219);
        $createAccount=ReuseableCode::check_rights(220);

        CommonHelper::companyDatabaseConnection($_GET['m']);
		$customers = new Customer;
		$customers = $customers::where('status', '=', '1')->get();
        CommonHelper::reconnectMasterDatabase();
		$counter = 1;
		foreach($customers as $row){
	?>

            <tr id="<?php echo $row->id?>">
                <td class="text-center"><?php echo $counter++; ?></td>
                <td class="text-center"><?php echo $row->customer_code; ?></td>
                <td class="text-center"><?php echo strtoupper($row->name); ?></td>
                <td class="text-center"><?php echo $row->address ?></td>
                <td class="text-center"><?php echo  $row->contact_person ?></td>
                <td class="text-center"><?php echo  $row->contact ?></td>
                <td class="text-center hidden-print printListBtn">
                    <div class="dropdown">
                        <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        <ul class="dropdown-menu">
                            <li>
                            <a onclick="showDetailModelOneParamerter('sales/viewCustomer/<?php echo $row->id?>', '<?php echo $row->id?>', 'viewCustomer')" target="_blank" class="dropdown-item_sale_order_list dropdown-item">
                                <i class="fa-regular fa-eye"></i> View
                            </a>
                            <?php if($row->acc_id == 0):?>
                                <?php if($createAccount == true):?>
                                <span id="ShowHide<?php echo $row->id?>">
                                    <a type="button" class="" id="Btn<?php echo $row->id?>"  onclick="CreateAccount('<?php echo $row->acc_id?>','<?php echo $row->name?>','<?php echo $row->id?>')"> Create Account</a>
                                </span>
                                <?php endif;?>
                                
                                <?php else:?>
                                
                                <?php endif;?>
                                <a  href="<?php echo url('/')?>/sales/editCustomerForm/<?php echo $row->id?>?m=<?php echo $_GET['m']?>" class=""><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                                <?php if($delete == true):?>
                                <a type="button" onclick="CustomerDelete('<?php echo $row->id?>')"  class=""><i class="fa fa-trash-o" aria-hidden="true"></i>  Delete</a>
                                <?php endif;?>                                      
                            </li>
                        </ul>
                    </div>                           
                </td>
            </tr>
	<?php
		}
	}

    function customer_delete(Request $request)
    {
        $CustomerId = $request->id;

        $data['status']=0;

        DB::Connection('mysql2')->table('customers')->where('id',$CustomerId)->update($data);

        $acc_id=  DB::Connection('mysql2')->table('customers')->where('id',$CustomerId)->select('acc_id')->value('acc_id');

        if ($acc_id!=0):

            DB::Connection('mysql2')->table('accounts')->where('id',$acc_id)->update($data);
        endif;

        echo $CustomerId;


    }

    function createCustomerAccount()
    {
        $AccId = $_GET['AccId'];
        $CustomerName = $_GET['CustomerName'];
        $CustomerId = $_GET['CustomerId'];
        $acc_code = $_GET['value'];


        $Accounts = new Account();
        $Accounts = $Accounts->SetConnection('mysql2');
        $Count = $Accounts->where('status', 1)->where('id', $AccId)->count();
        if($Count > 0)
        {
            echo "Account Already Created...!";
        }
        else
        {
            $account_head ='Trade Payable';
            $sent_code=$acc_code;//'Trade Receivables';
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
            $data1['name'] = strip_tags($CustomerName);
            $data1['parent_code'] = strip_tags($sent_code);
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['action'] = 'create';
            $data1['type'] = 1;
            $data1['operational'] = 1;
            $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);
            $UpdateSupplier['acc_id'] = $acc_id;
            DB::Connection('mysql2')->table('customers')->where('id','=',$CustomerId)->update($UpdateSupplier);
            echo "yes";
        }
    }

    public function filterByClientAndRegionSurvey()
    {

        $ClientId = $_GET['ClientId'];
        $RegionId = $_GET['RegionId'];
        $m = $_GET['m'];

        if($ClientId != "" && $RegionId == "")
        {
            $survey=new Survey();
            $survey=$survey->SetConnection('mysql2');
            $survey=$survey->where('status',1)->where('client_id',$ClientId)->select('*')->get();
        }
        elseif($ClientId == "" && $RegionId != "")
        {
            $survey=new Survey();
            $survey=$survey->SetConnection('mysql2');
            $survey=$survey->where('status',1)->where('region_id',$RegionId)->select('*')->get();
        }
        elseif($ClientId != "" && $RegionId != "")
        {
            $survey=new Survey();
            $survey=$survey->SetConnection('mysql2');
            $survey=$survey->where('status',1)->where('client_id',$ClientId)->where('region_id',$RegionId)->select('*')->get();
        }
        else
        {
            $survey=new Survey();
            $survey=$survey->SetConnection('mysql2');
            $survey=$survey->where('status',1)->select('*')->get();
        }


        return view('Sales.AjaxPages.filterByClientAndRegionSurvey', compact('survey','m'));

    }

    public function filterByClientAndRegionQuotation()
    {

        $ClientId = $_GET['ClientId'];
        $RegionId = $_GET['RegionId'];
        $m = $_GET['m'];

        if($ClientId != "" && $RegionId == "")
        {
            $quotation=new Quotation();
            $quotation=$quotation->SetConnection('mysql2');
            $quotation=$quotation->where('status',1)->where('client_id',$ClientId)->select('*')->get();
        }
        elseif($ClientId == "" && $RegionId != "")
        {
            $quotation=new Quotation();
            $quotation=$quotation->SetConnection('mysql2');
            $quotation=$quotation->where('status',1)->where('region_id',$RegionId)->select('*')->get();
        }
        elseif($ClientId != "" && $RegionId != "")
        {
            $quotation=new Quotation();
            $quotation=$quotation->SetConnection('mysql2');
            $quotation=$quotation->where('status',1)->where('client_id',$ClientId)->where('region_id',$RegionId)->select('*')->get();
        }
        else
        {
            $quotation=new Quotation();
            $quotation=$quotation->SetConnection('mysql2');
            $quotation=$quotation->where('status',1)->select('*')->get();
        }


        return view('Sales.AjaxPages.filterByClientAndRegionQuotation', compact('quotation','m'));

    }

    public function filterByClientAndRegionComplaint()
    {

        $ClientId = $_GET['ClientId'];
        $RegionId = $_GET['RegionId'];
        $m = $_GET['m'];

        if($ClientId != "" && $RegionId == "")
        {
            $complaint=new Complaint();
            $complaint=$complaint->SetConnection('mysql2');
            $complaint=$complaint->where('status',1)->where('client_name',$ClientId)->select('*')->get();
        }
        elseif($ClientId == "" && $RegionId != "")
        {
            $complaint=new Complaint();
            $complaint=$complaint->SetConnection('mysql2');
            $complaint=$complaint->where('status',1)->where('region_id',$RegionId)->select('*')->get();
        }
        elseif($ClientId != "" && $RegionId != "")
        {
            $complaint=new Complaint();
            $complaint=$complaint->SetConnection('mysql2');
            $complaint=$complaint->where('status',1)->where('client_name',$ClientId)->where('region_id',$RegionId)->select('*')->get();
        }
        else
        {
            $complaint=new Complaint();
            $complaint=$complaint->SetConnection('mysql2');
            $complaint=$complaint->where('status',1)->select('*')->get();
        }


        return view('Sales.AjaxPages.filterByClientAndRegionComplaint', compact('complaint','m'));

    }

    public function getDataClientWise()
    {
        die;
        $ClientId = $_GET['ClientId'];
        $BranchId = $_GET['BranchId'];

        $m = $_GET['m'];
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();

        $joborder = new JobOrder();
        $joborder = $joborder->SetConnection('mysql2');

        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2');

        if($ClientId != "" && $BranchId!="")
        {

            $joborder = $joborder->where('status', 1)->where('jo_status', 2)->where('invoice_created', 0)->where('client_name',$ClientId)->where('branch_id',$BranchId)->select('*')->get();
            $Branch = $Branch->where('client_id',$ClientId);

        }

        elseif($ClientId != "" && $BranchId=="")
        {

            $Branch = $Branch->where('client_id',$ClientId)->get();
        }

        else{
            return "no Job Order";
            //$joborder = $joborder->where('status', 1)->where('jo_status', 2)->where('invoice_created', 0)->select('*')->get();
        }

        return view('Sales.AjaxPages.getDataClientWise', compact('client','joborder','Branch','ClientId','m'));

    }
    public function getRecieptDataClientWise()
    {

        $ClientId = $_GET['ClientId'];
        $m = $_GET['m'];


        $Invoice = new SalesTaxInvoice();
        $Invoice = $Invoice->SetConnection('mysql2');

        if($ClientId !="")
        {
            $Invoice = $Invoice->where('status',1)->where('buyers_id',$ClientId)->get();
        }
        else
        {
            $Invoice = $Invoice->where('status',1)->get();
        }
        $client = new Customer();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status',1)->get();


        return view('Sales.AjaxPages.getRecieptDataClientWise', compact('Invoice','client','ClientId','m'));

    }

    public function getOutstandingReportAjax()
    {

        $ClientId = $_GET['ClientId'];
        $FromDate = $_GET['FromDate'];
        $ToDate = $_GET['ToDate'];
        $m = $_GET['m'];


        $Invoice = new SalesTaxInvoice();
        $Invoice = $Invoice->SetConnection('mysql2');


        $client = new Customer();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status',1)->get();


        return view('Sales.AjaxPages.getOutstandingReportAjax', compact('client','ClientId','m','FromDate','ToDate'));

    }

    public function get_debtor_balance_ajax()
    {

        $ClientId = $_GET['ClientId'];
        $FromDate = $_GET['FromDate'];
        $ToDate = $_GET['ToDate'];
        $m = $_GET['m'];


        $Invoice = new SalesTaxInvoice();
        $Invoice = $Invoice->SetConnection('mysql2');


        $client = new Customer();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status',1)->get();


        return view('Sales.AjaxPages.get_debtor_balance_ajax', compact('client','ClientId','m','FromDate','ToDate'));

    }



	public function filterCreditSaleVoucherList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];

        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $selectSubItem = $_GET['selectSubItem'];
        $selectSubItemId = $_GET['selectSubItemId'];
        $selectCreditCustomer = $_GET['selectCustomer'];
        $selectCreditCustomerId = $_GET['selectCustomerId'];


        CommonHelper::companyDatabaseConnection($m);

        if($selectVoucherStatus == '0' && empty($selectSubItemId) && empty($selectCreditCustomerId)){
            //return 'One';
            $creditSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('invoiceType','=','3')->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubItemId) && empty($selectCreditCustomerId)){
            //return 'Two';
            $creditSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoiceType','=','3')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '0' && empty($selectSubItemId) && !empty($selectCreditCustomerId)){
            //return 'Three';
            $creditSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('consignee','=',$selectCreditCustomerId)->where('invoiceType','=','3')->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubItemId) && empty($selectCreditCustomerId)){
            //return 'Four';
            $creditSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.invoiceType','=','3')
                ->where('invoice.inv_status','=','1')
                ->where('invoice.status','=','1')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubItemId) && empty($selectCreditCustomerId)){
            //return 'Five';
            $creditSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.invoiceType','=','3')
                ->where('invoice.inv_status','=','2')
                ->where('invoice.status','=','1')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubItemId) && empty($selectCreditCustomerId)){
            //return 'Six';
            $creditSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.invoiceType','=','3')
                ->where('invoice.status','=','2')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubItemId) && empty($selectCreditCustomerId)){
            //return 'Seven';
            $creditSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','1')->where('inv_status','=','1')->where('invoice.invoiceType','=','3')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubItemId) && empty($selectCreditCustomerId)){
            //return 'Eight';
            $creditSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','1')->where('inv_status','=','2')->where('invoice.invoiceType','=','3')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubItemId) && empty($selectCreditCustomerId)){
            //return 'Nine';
            $creditSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','2')->where('invoice.invoiceType','=','3')->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubItemId) && !empty($selectCreditCustomerId)){
            //return 'Ten';
            $creditSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','1')->where('inv_status','=','1')->where('consignee','=',$selectCreditCustomerId)->where('invoice.invoiceType','=','3')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubItemId) && !empty($selectCreditCustomerId)){
            //return 'Eleven';
            $creditSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','1')->where('inv_status','=','2')->where('consignee','=',$selectCreditCustomerId)->where('invoice.invoiceType','=','3')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubItemId) && !empty($selectCreditCustomerId)){
            //return 'Twelve';
            $creditSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','2')->where('consignee','=',$selectCreditCustomerId)->where('invoice.invoiceType','=','3')->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubItemId) && !empty($selectCreditCustomerId)){
            //return 'Thirteen';
            $creditSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.consignee','=',$selectCreditCustomerId)
                ->where('invoice.invoiceType','=','3')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubItemId) && !empty($selectCreditCustomerId)){
            //return 'Fourteen';
            $creditSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.consignee','=',$selectCreditCustomerId)
                ->where('invoice.invoiceType','=','3')
                ->where('invoice.inv_status','=','1')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubItemId) && !empty($selectCreditCustomerId)){
            //return 'Fifteen';
            $creditSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.consignee','=',$selectCreditCustomerId)
                ->where('invoice.invoiceType','=','3')
                ->where('invoice.inv_status','=','2')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubItemId) && !empty($selectCreditCustomerId)){
            //return 'Sixteen';
            $creditSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.consignee','=',$selectCreditCustomerId)
                ->where('invoice.invoiceType','=','3')
                ->where('invoice.status','=','2')
                ->groupBy('invoice.inv_no')
                ->get();
        }

        CommonHelper::reconnectMasterDatabase();
        return view('Sales.AjaxPages.filterCreditSaleVoucherList',compact('creditSaleInvoiceDetail'));
    }

    public function filterCashSaleVoucherList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];

        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $selectSubItem = $_GET['selectSubItem'];
        $selectSubItemId = $_GET['selectSubItemId'];
        $selectCashCustomer = $_GET['selectCustomer'];
        $selectCashCustomerId = $_GET['selectCustomerId'];


        CommonHelper::companyDatabaseConnection($m);

        if($selectVoucherStatus == '0' && empty($selectSubItemId) && empty($selectCashCustomerId)){
            //return 'One';
            $cashSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('invoiceType','=','2')->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubItemId) && empty($selectCashCustomerId)){
            //return 'Two';
            $cashSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_status','invoice.inv_against_discount','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoiceType','=','2')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '0' && empty($selectSubItemId) && !empty($selectCashCustomerId)){
            //return 'Three';
            $cashSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('consignee','=',$selectCashCustomerId)->where('invoiceType','=','2')->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubItemId) && empty($selectCashCustomerId)){
            //return 'Four';
            $cashSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_status','invoice.status','invoice.inv_against_discount','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.invoiceType','=','2')
                ->where('invoice.inv_status','=','1')
                ->where('invoice.status','=','1')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubItemId) && empty($selectCashCustomerId)){
            //return 'Five';
            $cashSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.invoiceType','=','2')
                ->where('invoice.inv_status','=','2')
                ->where('invoice.status','=','1')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubItemId) && empty($selectCashCustomerId)){
            //return 'Six';
            $cashSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.invoiceType','=','2')
                ->where('invoice.status','=','2')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubItemId) && empty($selectCashCustomerId)){
            //return 'Seven';
            $cashSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','1')->where('inv_status','=','1')->where('invoice.invoiceType','=','2')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubItemId) && empty($selectCashCustomerId)){
            //return 'Eight';
            $cashSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','1')->where('inv_status','=','2')->where('invoice.invoiceType','=','2')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubItemId) && empty($selectCashCustomerId)){
            //return 'Nine';
            $cashSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','2')->where('invoice.invoiceType','=','2')->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubItemId) && !empty($selectCashCustomerId)){
            //return 'Ten';
            $cashSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','1')->where('inv_status','=','1')->where('consignee','=',$selectCashCustomerId)->where('invoice.invoiceType','=','2')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubItemId) && !empty($selectCashCustomerId)){
            //return 'Eleven';
            $cashSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','1')->where('inv_status','=','2')->where('consignee','=',$selectCashCustomerId)->where('invoice.invoiceType','=','2')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubItemId) && !empty($selectCashCustomerId)){
            //return 'Twelve';
            $cashSaleInvoiceDetail = Invoice::whereBetween('inv_date',[$fromDate,$toDate])->where('status','=','2')->where('consignee','=',$selectCashCustomerId)->where('invoice.invoiceType','=','2')->get();
        }else if($selectVoucherStatus == '0' && !empty($selectSubItemId) && !empty($selectCashCustomerId)){
            //return 'Thirteen';
            $cashSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.consignee','=',$selectCashCustomerId)
                ->where('invoice.invoiceType','=','2')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '1' && !empty($selectSubItemId) && !empty($selectCashCustomerId)){
            //return 'Fourteen';
            $cashSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.consignee','=',$selectCashCustomerId)
                ->where('invoice.invoiceType','=','2')
                ->where('invoice.inv_status','=','1')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '2' && !empty($selectSubItemId) && !empty($selectCashCustomerId)){
            //return 'Fifteen';
            $cashSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.consignee','=',$selectCashCustomerId)
                ->where('invoice.invoiceType','=','2')
                ->where('invoice.inv_status','=','2')
                ->groupBy('invoice.inv_no')
                ->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubItemId) && !empty($selectCashCustomerId)){
            //return 'Sixteen';
            $cashSaleInvoiceDetail = DB::table('invoice')
                ->select('invoice.inv_no','invoice.dc_no','invoice.consignee','invoice.inv_date','invoice.inv_against_discount','invoice.inv_status','invoice.status','inv_data.sub_item_id')
                ->join('inv_data', 'invoice.inv_no', '=', 'inv_data.inv_no')
                ->whereBetween('invoice.inv_date',[$fromDate,$toDate])
                ->where('inv_data.sub_item_id','=',$selectSubItemId)
                ->where('invoice.consignee','=',$selectCashCustomerId)
                ->where('invoice.invoiceType','=','2')
                ->where('invoice.status','=','2')
                ->groupBy('invoice.inv_no')
                ->get();
        }

        CommonHelper::reconnectMasterDatabase();
        return view('Sales.AjaxPages.filterCashSaleVoucherList',compact('cashSaleInvoiceDetail'));
    }

    public function viewCreditSaleVoucherDetail(){
        return view('Sales.AjaxPages.viewCreditSaleVoucherDetail');
    }
    public function viewReceiptVoucher(){
        return view('Sales.AjaxPages.viewReceiptVoucher');

    }
    public function viewReceiptVoucherPrint(){
        return view('Sales.AjaxPages.viewReceiptVoucherPrint');

    }

    public function viewReceiptVoucherDirect(){
        return view('Sales.AjaxPages.viewReceiptVoucherDirect');
    }



    public function viewCashSaleVoucherDetail()
    {
        return view('Sales.AjaxPages.viewCashSaleVoucherDetail');
    }

    public function viewQuotationDetail()
    {
        return view('Sales.AjaxPages.viewQuotationDetail');
    }
    public function viewInvoiceDetail(Request $request)
    {
        $Other = explode(",",$request->id);
        //echo $Other[1]; die();

        $id=Input::get('id');
        $sales_tax_invoice=new SalesTaxInvoice();
        $sales_tax_invoice=$sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice=$sales_tax_invoice->where('gi_no',$Other[1])->first();
        $id  = $sales_tax_invoice->id;

//        $sales_tax_invoice_data=new SalesTaxInvoiceData();
//        $sales_tax_invoice_data=$sales_tax_invoice_data->SetConnection('mysql2');
//        $sales_tax_invoice_data=$sales_tax_invoice_data->where('master_id',$id)->get();


        $sales_tax_invoice_data=DB::Connection('mysql2')->select('select a.item_id,a.qty,a.rate,a.discount as discount_percent ,a.discount_amount,a.amount,a.gd_no,a.bundles_id,a.so_data_id,
        a.description,b.rate as bundle_rate, b.amount as bundle_amount , b.discount_percent as b_percent, b.discount_amount as b_dis_amount, b.net_amount as b_net, b.product_name, b.qty as bqty
        ,b.bundle_unit,a.so_type,a.dn_data_ids
        from sales_tax_invoice_data  a
        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.status=1
        and a.master_id  ="'.$id.'"
        group by  a.groupby
        ');

        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id',$id);

        return view('Sales.AjaxPages.viewSalesTaxInvoiceDetail',compact('sales_tax_invoice','sales_tax_invoice_data','AddionalExpense'));
    }
    public function viewImportPoDetail()
    {
        return view('Sales.AjaxPages.viewImportPoDetail');
    }


    public function viewComplaintDetail()
    {
        return view('Sales.AjaxPages.viewComplaintDetail');
    }


    public function viewQuotationDetailTwo()
    {
        return view('Sales.AjaxPages.viewQuotationDetailTwo');
    }


    public function get_batch_detail(Request $request)
    {

        $id=$request->id;
        $data = DB::Connection('mysql2')->table('batch_detail as b')
            ->join('subitem as d', 'd.id', '=', 'b.item_id')
            ->select('b.expiry_date','b.mrp','b.tp','d.pack_size','b.batch_no')
            ->where('b.id',$id)
            ->first();

        $grn_data=new GRNData();
        $grn_data=$grn_data->SetConnection('mysql2');
        $stock_in_hand=$grn_data->where('batch_no',$data->batch_no)->sum('purchase_recived_qty');

        $expiry_date=CommonHelper::changeDateFormat($data->expiry_date);
        echo $expiry_date.','.$data->mrp.','.$data->tp.','.$data->pack_size.','.$stock_in_hand;


    }
    public function addSalesOrder(Request $request)
    {
        $counter=  $request->counter;

        ?>

        <div class="well">

            <div class="panel">
                <div class="panel-body">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="buildyourform" class="table table-bordered">
                                <thead>
                                <tr>

                                    <th colspan="2"   class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createCategoryFormAjax')" class="">Item</a></th>
                                    <th colspan="1"  class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax/0')" class="">Batch</a></th>
                                    <th colspan="1" style="width: 150px" class="text-center" >Uom <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th colspan="1" style="width: 150px" class="text-center " >Pack Size <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th colspan="1" class="text-center">Description <span class="rflabelsteric"><strong>*</strong></span></th>

                                    <th colspan="1" style="width: 180px" class="text-center">QTY. <span class="rflabelsteric"><strong>*</strong></span></th>




                                    <!--
                                    <th class="text-center" style="width:100px;">Action</th>
                                    <!-->
                                </tr>
                                </thead>
                                <tbody>
                                <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="1" />
                                <tr>

                                    <td colspan="2">
                                        <select   style="width: 100%" onchange="get_batch_detail(this.id,'<?php echo $counter ?>')"  name="item_id_<?php echo $counter ?>" id="item_id_<?php echo $counter ?>" class="form-control requiredField select2">
                                            <option>Select</option>
                                            <?php foreach(CommonHelper::get_all_subitem() as $row): ?>
                                                <option value="<?php echo $row->id ?>"><?php echo $row->sub_ic ?></option>
                                            <?php    endforeach ?>

                                        </select>
                                    </td>
                                    <td>
                                        <select style="width: 100%" onchange=""  name="batch_id_<?php echo $counter ?>" id="batch_id_<?php echo $counter ?>" class="form-control requiredField select2">
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" readonly type="text" id="uom_<?php echo $counter ?>" name="uom_<?php echo $counter ?>"/>
                                        <input type="hidden" id="uom_id_<?php echo $counter ?>" name="uom_id_<?php echo $counter ?>"/>
                                    </td>
                                    <td><input class="form-control" readonly type="text" id="pack_size_<?php echo $counter ?>" name="pack_size_<?php echo $counter ?>"/> </td>
                                    <td>
                                        <textarea style="resize: none" type="text" name="description_<?php echo $counter ?>" id="description_<?php echo $counter ?>" class="form-control requiredField" ></textarea></td>
                                    <td><input onkeyup="amount_calc(this.id,'<?php echo $counter ?>')" class="form-control"  type="text" id="qty_<?php echo $counter ?>" name="qty_<?php echo $counter ?>"/> </td>

                                </tr>

                                <tr>
                                    <th colspan="2" class="text-center">Per PCS item <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th colspan="1" class="text-center" >Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th colspan="1" class="text-center" >Discount %<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th colspan="1" class="text-center" >Discount Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th colspan="1" class="text-center" >Amount<span class="rflabelsteric"><strong>*</strong></span></th>

                                </tr>

                                <tr>
                                    <td>


                                    </td>
                                    <td>
                                        <input readonly step="0.01" type="number" name="per_pcs_item_<?php echo $counter ?>" id="per_pcs_item_<?php echo $counter ?>" class="form-control requiredField" />
                                    </td>
                                    <td><input onkeyup="amount_calc(this.id,'<?php echo $counter ?>')" class="form-control"  type="text" id="rate_<?php echo $counter ?>" name="rate_<?php echo $counter ?>"/> </td>
                                    <td class="text-right"><input onkeyup="amount_calc(this.id,'<?php echo $counter ?>')" class="form-right form-control"  type="text" id="discount_percent_<?php echo $counter ?>" name="discount_percent_<?php echo $counter ?>"/> </td>
                                    <td class="text-right"><input onkeyup="amount_calc(this.id,'<?php echo $counter ?>')" class="form-right form-control"  type="text" id="discount_amount_<?php echo $counter ?>" name="discount_amount_<?php echo $counter ?>"/> </td>
                                    <td class="text-right"><input style="font-weight: bold;font-size: x-large" readonly class="form-right form-control amount"  type="text" id="amount_<?php echo $counter ?>" name="amount_<?php echo $counter ?>"/> </td>


                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
    function get_batch_details(Request $request)
    {
        $item_id=$request->id;
        $bacth_detail=new Batch();
        $bacth_detail=$bacth_detail->SetConnection('mysql2');
        $bacth_detail=$bacth_detail->select('id','batch_no')->where('item_id',$item_id)->get();


        ?>
         <option>Select</option>
        <?php
        foreach($bacth_detail as $row):?>
        <option value="<?php echo $row->id ?>"><?php echo $row->batch_no ?></option>

        <?php  endforeach;
        echo '*';
            $subitem=new Subitem();
            $subitem=$subitem->SetConnection('mysql2');
            $subitem=$subitem->select('pack_size','description','uom')->where('id',$item_id)->first();
            $uom=new UOM();
            $uom=$uom->select('uom_name')->where('id',$subitem->uom)->first();
        echo     $subitem->pack_size.'*'.$subitem->description.'*'.$subitem->uom.'*'.$uom->uom_name;


    }

    function getSalesTaxInvoice(Request $request)
    {
//        $so=$request->so;
//        $invoice_data=new SalesTaxInvoice();
//        $invoice_data=$invoice_data->SetConnection('mysql2');
//        $invoice_data=$invoice_data->where('buyers_id',$customer)->where('status',1)->select('id','gi_no','gi_date')->get();
//        return view('Sales.AjaxPages.getSalesTaxInvoice',compact('invoice_data','customer'));
        $so=$request->so;
        $type=$request->type;

        if ($type==1):


            $dataa = DB::Connection('mysql2')->table('delivery_note as a')
                ->leftJoin('sales_order as b', 'a.master_id', '=', 'b.id')
                ->where('a.status',1)
                ->where('b.status',1)
                ->where('a.so_no',$so)
                ->select('a.id','a.gd_no as gi_no','a.gd_date as gi_date','b.buyers_id','a.master_id')
                ->get();


            else:

            $dataa=new SalesTaxInvoice();
            $dataa=$dataa->SetConnection('mysql2');
            $dataa=$dataa->where('status',1)->where(function ($query) use ($so) {
                $query->where('so_no', $so)
                      ->orWhere('gi_no', $so);
            })->select('id','gi_no','gi_date','buyers_id')->get();

            endif;

        return view('Sales.AjaxPages.getSalesTaxInvoice',compact('dataa','type'));

    }

    function viewSurveyListDetail()
    {
        return view('Sales.AjaxPages.viewSurveyListDetail');
    }

    function editConditionForm()
    {
        $ConditionId = $_GET['id'];
        $conditions = new Conditions();
        $conditions = $conditions->SetConnection('mysql2');
        $conditions=$conditions->where('condition_id',$ConditionId)->where('status',1)->select('condition_id','name')->first();
        return view('Sales.AjaxPages.editConditionForm',compact('conditions'));
    }

    function editProductTypeForm()
    {
        $product_type_id = $_GET['id'];
        $ProductType = new ProductType();
        $ProductType = $ProductType->SetConnection('mysql2');
        $ProductType = $ProductType->where('product_type_id',$product_type_id)->where('status',1)->select('product_type_id','type')->first();
        return view('Sales.AjaxPages.editProductTypeForm',compact('ProductType'));
    }

    function editResourceAssignedForm()
    {
        $id = $_GET['id'];
        $ResourceAssigned = new ResourceAssigned();
        $ResourceAssigned = $ResourceAssigned->SetConnection('mysql2');
        $ResourceAssigned = $ResourceAssigned->where('id',$id)->where('status',1)->select('id','resource_type')->first();
        return view('Sales.AjaxPages.editResourceAssignedForm',compact('ResourceAssigned'));
    }

    public function editProductForm()
    {
        $ProductId = $_GET['id'];
        $product = new Product();
        $product = $product->SetConnection('mysql2');
        $product=$product->where('product_id',$ProductId)->where('p_status',1)->select('product_id','p_name','type_id','acc_id')->first();
        return view('Sales.AjaxPages.editProductForm',compact('product'));
    }

    public function editClientForm()
    {
        $ClientId = $_GET['id'];
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client=$client->where('id',$ClientId)->where('status',1)->select('id','acc_id','client_name','ntn','strn','address')->first();

        return view('Sales.AjaxPages.editClientForm',compact('client'));
    }





    public function editSurveyByForm()
    {
        $Id = $_GET['id'];
        $surveyBy = new SurveryBy();
        $surveyBy = $surveyBy->SetConnection('mysql2');
        $surveyBy=$surveyBy->where('id',$Id)->where('status',1)->select('id','name','remarks')->first();

        return view('Sales.AjaxPages.editSurveyByForm',compact('surveyBy'));
    }

    public function editTypeList()
    {
        $Id = $_GET['id'];
        $Type = new Type();
        $Type = $Type->SetConnection('mysql2');
        $Type = $Type->where('type_id',$Id)->where('status',1)->select('type_id','name')->first();

        return view('Sales.AjaxPages.editTypeList',compact('Type'));
    }

    function viewJobTrackingListDetail()
    {
        return view('Sales.AjaxPages.viewJobTrackingListDetail');
    }

    function getTrackingSheet(Request $request)
    {
        $TrackNo = $request->TrackNo;
        $x = $request->x;
        $customer_job ="";

        $JobTrack = new JobTracking();
        $JobTrack = $JobTrack->SetConnection('mysql2');
        $JbCount = $JobTrack->where('status', 1)->where('job_tracking_no', $TrackNo)->count();

        if($x==2){
            $JobOrder = new JobOrder();
            $JobOrder = $JobOrder->SetConnection('mysql2');
            $JobOrder = $JobOrder->where('job_order_no', $TrackNo)->select('client_job', 'client_name', 'branch_id', 'region_id', 'date_ordered')->first();
            $customer_job = $JobOrder->client_job;
            $client_id = $JobOrder->client_name;
            $branch_id = $JobOrder->branch_id;
            $region_id = $JobOrder->region_id;
            $date = $JobOrder->date_ordered;
        } else {
            $m = $request->m;
            $job_survery = new Survey();
            $job_survery = $job_survery->SetConnection('mysql2');
            $job_survery = $job_survery->where('tracking_no', $TrackNo)->select('client_id', 'branch_id', 'region_id', 'city_id', 'survey_date')->first();

            $client_id = $job_survery->client_id;
            $branch_id = $job_survery->branch_id;
            $region_id = $job_survery->region_id;
            $city_id = $job_survery->city_id;
            $date = $job_survery->survey_date;

            $city_data = CommonHelper::get_all_cities_by_id($city_id);
            $city_name = $city_data->name;
        }

        $client_name = CommonHelper::get_client_name_by_id($client_id);
        $region_data = CommonHelper::get_rgion_name_by_id($region_id);
        $region_name = $region_data->region_name;

        if($JbCount > 0){
            $JobTrack = $JobTrack->where('status',1)->where('job_tracking_no',$TrackNo)->first();
            $city_id = $JobTrack->city;
            $type = $JobTrack->type;
            $JobTrackData = new JobTrackingData();
            $JobTrackData = $JobTrackData->SetConnection('mysql2');
            $JobTrackData = $JobTrackData->where('status',1)->where('job_tracking_no',$TrackNo)->orderBy('job_tracking_data_id','ASC')->get();
            $city_data = CommonHelper::get_all_cities_by_id($city_id);
            $city_name = $city_data->name;

            if($type==2){ ?>
                <input type="hidden" name="FormCondition" id="FormCondition" value="Update">
                <input type="hidden" name="ClientId" id="ClientId" value="<?php echo $client_id?>">
                <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id?>">
                <input type="hidden" name="RegionId" id="RegionId" value="<?php echo $region_id?>">
                <input type="hidden" name="x" id="x" value="<?php echo $type ?>">
            <?php } else { ?>
                <input type="hidden" name="FormCondition" id="FormCondition" value="Update">
                <input type="hidden" name="ClientId" id="ClientId" value="<?php echo $client_id?>">
                <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id?>">
                <input type="hidden" name="RegionId" id="RegionId" value="<?php echo $region_id?>">
                <input type="hidden" name="CityId" id="CityId" value="<?php echo $city_id?>">
                <input type="hidden" name="x" id="x" value="<?php echo $type ?>">
            <?php } ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Client Name.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control requiredField"  name="client_name" id="client_name"  required="required" tabindex="2" value="<?php echo $client_name ?>"/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Customer Job.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width: 100%" class="form-control  select2" name="customer_job" id="customer_job" >
                                                <option value="">---Select---</option>
                                                <?php foreach(CommonHelper::get_all_client_job() as $row):?>
                                                    <option value="<?php  echo $row->id ;?>" <?php if($JobTrack->customer_job == $row->id){echo "selected";}?>>
                                                        <?php echo ucwords($row->client_job);?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Region.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control requiredField"  name="region" id="region"  required="required" tabindex="2" value="<?php echo $region_name ?>"/>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Job Tracking Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" name="job_tracking_date" id="job_tracking_date" class="form-control" value="<?php echo $JobTrack->job_tracking_date?>" required="required" tabindex="3" >
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Job Tracking #</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" name="tracking_no" id="tracking_no" class="form-control" value="<?php echo $TrackNo?>" readonly tabindex="4">
                                        </div>
                                        <?php if($type==1){  ?>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">City.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input readonly type="text" class="form-control requiredField"  name="city" id="city"  required="required" tabindex="2" value="<?php echo $city_name ?>"/>
                                            </div>
                                        <?php } else {  ?>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">City.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%" class="form-control requiredField select2" name="CityId" id="CityId" >
                                                    <option value="">---Select---</option>
                                                    <?php $cities = CommonHelper::get_all_cities(); foreach($cities as $row):?>
                                                        <option value="<?php echo $row->id;?>" <?php if($row->id==$city_id){ echo "selected"; } ?> > <?php echo $row->name; ?> </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Job Description</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="job_desc" id="job_desc" rows="4" cols="50" style="resize:none;" class="form-control requiredField" tabindex="6"><?php echo $JobTrack->job_description?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="lineHeight">&nbsp;</div>
                            <?php ?>
                            <div class="well">
                                <div class="">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="buildyourform" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" style="width: 195px;">Task</th>
                                                            <th class="text-center">Task Assigned</th>
                                                            <th class="text-center">Task Target Date</th>
                                                            <th class="text-center">Task Completed Date</th>
                                                            <th class="text-center">Resource Assigned <br>Internal/External  </th>
                                                            <th class="text-center">Remarks </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="TrAppend">
                                                        <?php
                                                        $i = 1;
                                                        foreach($JobTrackData as $DataFil):

                                                            ?>
                                                            <tr>
                                                                <td>  <input type="text" name="task_<?= $i; ?>" id="task_<?= $i; ?>" class="form-control"  value="<?= $DataFil['task'] ?>" readonly>  </td>
                                                                <td>  <input type="date" name="taskAssigned_<?= $i; ?>" id="taskAssigned_<?= $i; ?>" value="<?php echo $DataFil['task_assigned']?>" class="form-control"> </td>
                                                                <td>  <input type="date" name="taskTarget_<?= $i; ?>" id="taskTarget_<?= $i; ?>" value="<?php echo $DataFil['task_target_date']?>" class="form-control"> </td>
                                                                <td>  <input type="date" name="taskCompeleted_<?= $i; ?>" id="taskCompeleted_<?= $i; ?>" value="<?php echo $DataFil['task_completed_date']?>" class="form-control"> </td>`
                                                                <td>
                                                                    <select style="width: 100%" name="resourcAssign_<?= $i; ?>" id=resourcAssign_<?= $i; ?> value="<?php echo $DataFil['resource']?>" class="form-control select2">

                                                                        <option>Select</option>
                                                                        <?php foreach(CommonHelper::get_all_resource() as $res): ?>
                                                                            <option <?php if ($DataFil['resource']==$res->id): echo 'selected'; endif; ?> value="<?php echo $res->id ?>"><?php echo $res->resource_type ?></option>
                                                                        <?php endforeach ?>
                                                                    </select>
                                                                </td>
                                                                <td>  <textarea name="remarks_<?= $i; ?>" id="remarks_<?= $i; ?>" cols="30" rows="2" style="resize: none;" class="form-control"><?php echo $DataFil['remarks']?></textarea> </td>
                                                            </tr>

                                                            <?php
                                                            $i++;   endforeach;
                                                        ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <input type="submit" class="btn btn-sm btn-success" value="Submit">
                        <?php //echo  Form::submit('Submit', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) ?>
                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                        <!-- <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" /> -->
                    </div>
                </div>
            </div>
        <?php } else {
            if($x==2){ ?>
                <input type="hidden" name="FormCondition" id="FormCondition" value="Create">
                <input type="hidden" name="ClientId" id="ClientId" value="<?php echo $client_id?>">
                <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id?>">
                <input type="hidden" name="RegionId" id="RegionId" value="<?php echo $region_id?>">
                <input type="hidden" name="x" id="x" value="<?php echo $x ?>">
            <?php }else{?>
                <input type="hidden" name="FormCondition" id="FormCondition" value="Create">
                <input type="hidden" name="ClientId" id="ClientId" value="<?php echo $client_id?>">
                <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id?>">
                <input type="hidden" name="RegionId" id="RegionId" value="<?php echo $region_id?>">
                <input type="hidden" name="CityId" id="CityId" value="<?php echo $city_id?>">
                <input type="hidden" name="x" id="x" value="<?php echo $x ?>">
                <?php
            }
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Client Name.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control requiredField"  name="client_name" id="client_name"  required="required" tabindex="2" value="<?php echo $client_name ?>"/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Customer Job.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width: 100%" class="form-control requiredField select2" name="customer_job" id="customer_job" >
                                                <option value="">---Select---</option>
                                                <?php foreach(CommonHelper::get_all_client_job() as $row):?>
                                                    <option value="<?php echo $row->id ;?>" <?php if($row->id==$customer_job){ echo "selected"; } ?> ><?php echo ucwords($row->client_job);?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Region.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control requiredField"  name="region" id="region"  required="required" tabindex="2" value="<?php echo $region_name ?>"/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Job Tracking Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" name="job_tracking_date" id="job_tracking_date" class="form-control" value="<?php echo date('Y-m-d')?>" required="required" tabindex="3">
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Job Tracking #</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" name="tracking_no" id="tracking_no" class="form-control" value="<?php echo $TrackNo?>" readonly tabindex="4">
                                        </div>
                                        <?php if($x==1){ ?>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">City.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input readonly type="text" class="form-control requiredField"  name="city" id="city"  required="required" tabindex="2" value="<?php echo "dasdas"; ?>"/>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">City.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%" class="form-control requiredField select2" name="CityId" id="CityId" >
                                                    <option value="">---Select---</option>
                                                    <?php $cities = CommonHelper::get_all_cities(); foreach($cities as $row):?>
                                                        <option value="<?php echo $row->id ;?>" > <?php echo $row->name; ?> </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Job Description</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="job_desc" id="job_desc" rows="4" cols="50" style="resize:none;" class="form-control requiredField" tabindex="6"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="lineHeight">&nbsp;</div>
                            <?php ?>
                            <div class="well">
                                <div class="">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="buildyourform" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" style="width: 195px;">Task</th>
                                                            <th class="text-center" style="width: 100px;">Task Assigned</th>
                                                            <th class="text-center">Task Target Date</th>
                                                            <th class="text-center">Task Completed Date</th>
                                                            <th class="text-center">Resource Assigned <br>Internal/External </th>
                                                            <th class="text-center">Remarks </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="TrAppend">
                                                        <?php
                                                        $one="";
                                                        for($i=1; $i<=17; $i++):

                                                            if($i==1){ $one = "Site Survey";  }
                                                            elseif($i==2){ $one = "Artwork Shared";  }
                                                            elseif($i==3){ $one = "Approve Artwork";  }
                                                            elseif($i==4){ $one = "Approve Quote";  }
                                                            elseif($i==5){ $one = "Generate Job Order";  }
                                                            elseif($i==6){ $one = "Estimate Quantities";  }
                                                            elseif($i==7){ $one = "Raised Requisition";  }
                                                            elseif($i==8){ $one = "Requisition Approved";  }
                                                            elseif($i==9){ $one = "Procure Material";  }
                                                            elseif($i==10){ $one = "Cut Vinyl";  }
                                                            elseif($i==11){ $one = "Pasting";  }
                                                            elseif($i==12){ $one = "Print Skin";  }
                                                            elseif($i==13){ $one = "Fabrication";  }
                                                            elseif($i==14){ $one = "Assemble Sign";  }
                                                            elseif($i==15){ $one = "Installed Sign";  }
                                                            elseif($i==16){ $one = "Invoice";  }
                                                            elseif($i==17){ $one = "Recovery";  }
                                                            else { $one = "";  }
                                                            ?>
                                                            <tr>
                                                                <td><input type="text" name="task_<?= $i; ?>" id="task_<?= $i; ?>" class="form-control"  value="<?= $one ?>" readonly>  </td>
                                                                <td>  <input type="date" name="taskAssigned_<?= $i; ?>" id="taskAssigned_<?= $i; ?>" value="" class="form-control"> </td>
                                                                <td>  <input type="date" name="taskTarget_<?= $i; ?>" id="taskTarget_<?= $i; ?>" value="" class="form-control"> </td>
                                                                <td>  <input type="date" name="taskCompeleted_<?= $i; ?>" id="taskCompeleted_<?= $i; ?>" value="<?php if($i == 1){ echo $date; } ?>" class="form-control"> </td>
                                                                <td>  <select style="width: 100%" name="resourcAssign_<?= $i; ?>" id=resourcAssign_<?= $i; ?> value="" class="form-control select2">

                                                                        <option>Select</option>
                                                                        <?php foreach(CommonHelper::get_all_resource() as $res): ?>
                                                                            <option value="<?php echo $res->id ?>"><?php echo $res->resource_type ?></option>
                                                                        <?php endforeach ?>
                                                                    </select>  </td>
                                                                <td>  <textarea name="remarks_<?= $i; ?>" id="remarks_<?= $i; ?>" cols="30" rows="2" style="resize: none;" class="form-control">  </textarea> </td>
                                                            </tr>

                                                            <?php
                                                        endfor;
                                                        ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <input type="submit" class="btn btn-sm btn-success" value="Submit">
                        <?php //echo  Form::submit('Submit', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) ?>
                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                        <!-- <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" /> -->
                    </div>
                </div>

            </div>
        <?php }  ?>

        <script>
            $(document).ready(function(){
                $('.select2').select2();
                $(".btn-success").click(function(e){
                    alert("dasd");
                    jqueryValidationCustom();
                    if(validate == 0){
                        $('#BtnSubmit').css('display','none');
                        //return false;
                    }else{
                        return false;
                    }
                });
            });
        </script>
        <?php
    }

    public function getQuatationForm(Request $request)
    {
        $survey_id=$request->TrackNo;
        if ($survey_id!='direct'):
        $survey=new Survey();
        $survey=$survey->SetConnection('mysql2');
        $survey=$survey->where('survey_id',$survey_id)->where('status',1)->first();

        $survey_data=new SurveyData();
        $survey_data=$survey_data->SetConnection('mysql2');
        $survey_data=$survey_data->where('survey_id',$survey_id)->get();
            endif;
        return view('Sales.AjaxPages.getQuatationForm',compact('survey','survey_data','survey_id'));
    }

    public function ApprovedSurvey()
    {
        $SurveyId = $_GET['SurveyId'];

        CommonHelper::companyDatabaseConnection($_GET['m']);
        $UpdateData['survey_status'] = 2;
        $UpdateData['approve_username'] = Auth::user()->name;
        DB::table('survey')->where('survey_id','=',$SurveyId)->update($UpdateData);
        DB::table('survey_data')->where('survey_id','=',$SurveyId)->update($UpdateData);
        CommonHelper::reconnectMasterDatabase();
        echo $SurveyId;
    }

    public function ApprovedQuotation()
    {
        $QuotationId = $_GET['QuotationId'];

        CommonHelper::companyDatabaseConnection($_GET['m']);
        $UpdateData['quotation_status'] = 2;
        $UpdateData['approve_username'] = Auth::user()->name;
        DB::table('quotation')->where('id','=',$QuotationId)->update($UpdateData);
        DB::table('quotation_data')->where('master_id','=',$QuotationId)->update($UpdateData);
        CommonHelper::reconnectMasterDatabase();
        echo $QuotationId;
    }


    public function ApprovedJobOrder()
    {
        $JoId = $_GET['JoId'];

        CommonHelper::companyDatabaseConnection($_GET['m']);


        $UpdateData['jo_status'] = 2;
        $UpdateData['approve_username'] = Auth::user()->name;
        DB::table('job_order')->where('job_order_id','=',$JoId)->update($UpdateData);
        DB::table('job_order_data')->where('job_order_id','=',$JoId)->update($UpdateData);


        CommonHelper::reconnectMasterDatabase();
        echo $JoId;
    }

    public function Activity_log_list_ajax()
    {
        $from = $_GET['from'];
        $to = $_GET['to'];
        $LOGACTIVITY = new LOGACTIVITY();
        $LOGACTIVITY = $LOGACTIVITY->SetConnection('mysql2');
        $LOGACTIVITY = $LOGACTIVITY->where('status',1)->where('date','>=',$from)->where('date','<=',$to)->select('*')->get();
        ?>

        <div class="panel">
            <div class="panel-body" id="PrintEmpExitInterviewList">
                <?php // echo CommonHelper::headerPrintSectionInPrintView($m);?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                <thead>
                                <th class="text-center col-sm-1">S.No</th>
                                <th class="text-center col-sm-1">Voucher No</th>
                                <th class="text-center col-sm-1">Voucher Date</th>
                                <th class="text-center col-sm-1">Table Name</th>
                                <th class="text-center col-sm-1">Username</th>
                                <th class="text-center col-sm-1">Created Date</th>
                                <th class="text-center col-sm-1">Action</th>
                                </thead>
                                <tbody id="data">
                                <?php $counter = 1; ?>

                                <?php foreach($LOGACTIVITY as $row): ?>
                                <tr id="">
                                    <td class="text-center"><?= $counter++ ?></td>
                                    <td class="text-center"><?php echo $row->voucher_no ?></td>
                                    <td class="text-center"><?php echo date('d-m-Y',strtotime($row->v_date)) ?></td>
                                    <td class="text-center"><?php echo $row->table_name ?></td>
                                    <td class="text-center"><?php echo $row->username ?></td>
                                    <td class="text-center"><?php echo date('d-m-Y',strtotime($row->date)) ?></td>
                                    <td class="text-center"><?php if($row->action==1){ echo "ADDED";} elseif($row->action==2){ echo "UPDATED"; } elseif($row->action==3){ echo "DELETED"; } ?> </td>
                                </tr>
                                <?php
                                    endforeach
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

    function  addData(Request $request)
    {
      $id= $request->id;

        $client=new Client();
        $client=$client->SetConnection('mysql2');
        $client=$client->where('id',$id)->select('client_name')->first();


        $account_head ='Trade Receivables';
        $sent_code='1-2-2';//'Trade Receivables';
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
        $data1['name'] = strip_tags($client->client_name);
        $data1['parent_code'] = strip_tags($sent_code);
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['action'] = 'create';
        $data1['type'] = 2;
        $data1['operational'] = 1;
        $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);
        $UpdateClient['acc_id'] = $acc_id;
        DB::Connection('mysql2')->table('client')->where('id','=',$id)->update($UpdateClient);
        echo $id;
    }
    public  function approve_invoice(Request $request)
    {
        $id=$request->id;
        DB::Connection('mysql2')->beginTransaction();

        try {
            $invoice_data = DB::Connection('mysql2')->table('inv_data as a')
                ->select('c.acc_id as prduct_acc_id','a.description','b.inv_no','b.sales_tax_acc_id','d.acc_id as client_acc_id',
                    'a.amount','a.id','b.inv_date','b.sales_tax_amount','b.discount_amount','b.advance_from_customer','a.branch_id',
                    'f.net_value','f.discount_amount as dis_am','f.advance_amount as adv_am','f.discount_percntage as dis_per','f.discount_amount_tax',
                    'f.advance_amount','f.advance_amount_tax','f.advance_percntage','b.bill_to_client_id','f.advance_amount_after_tax')
                ->join('invoice as b', 'b.id', '=', 'a.master_id')
                ->join('invoice_data_totals as f', 'f.master_id', '=', 'b.id')
                ->join('product as c', 'c.product_id', '=', 'a.product_id')
                ->join('client as d', 'd.id', '=', 'b.bill_to_client_id')
                ->where('a.master_id', $id)
                ->where('b.inv_status',1)
                ->get();

            $count=1;
            $tax=0;
            foreach ($invoice_data as $row):

                $inv_no = $row->inv_no;
                $inv_date = $row->inv_date;
                if ($count==1):

                    $inv_amount=DB::Connection('mysql2')->table('inv_data')->where('master_id',$id)->sum('amount');
                    $discount=$row->discount_amount;
                    $sales_tax=$row->sales_tax_amount;
                    $total_amount= $row->net_value;


                    $trans1 = new Transactions();
                    $trans1 = $trans1->SetConnection('mysql2');
                    $trans1->acc_id = $row->client_acc_id;
                    $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row->client_acc_id, '');;
                    $trans1->master_id = $row->id;
                    $trans1->particulars = $row->description;
                    $trans1->opening_bal = 0;
                    $trans1->debit_credit = 1;


                    $trans1->paid_to = $row->branch_id;
                    $trans1->paid_to_type = 5;


                    $trans1->amount = $total_amount;
                    $trans1->voucher_no = $row->inv_no;
                    $trans1->voucher_type = 5;
                    $trans1->v_date = $row->inv_date;
                    $trans1->date = date('Y-m-d');
                    $trans1->action = 1;
                    $trans1->status = 1;
                    $trans1->username = Auth::user()->name;
                    $trans1->save();





                    $taxes=DB::Connection('mysql2')->selectOne('select sum(txt_amount)amount from inv_data where master_id="'.$id.'" ')->amount;

                    if ($row->dis_am>0):
                        $trans1 = new Transactions();
                        $trans1 = $trans1->SetConnection('mysql2');
                        $trans1->acc_id = 612;
                        $trans1->acc_code = '4-1-3';;
                        $trans1->master_id = $row->id;
                        $trans1->particulars = 'Discount';
                        $trans1->opening_bal = 0;
                        $trans1->debit_credit = 1;

                        $trans1->paid_to = $row->bill_to_client_id;
                        $trans1->paid_to_type = 3;

                        $trans1->amount = $row->dis_am;
                        $trans1->voucher_no = $row->inv_no;
                        $trans1->voucher_type = 5;
                        $trans1->v_date = $row->inv_date;
                        $trans1->date = date('Y-m-d');
                        $trans1->action = 1;
                        $trans1->status = 1;
                        $trans1->username = Auth::user()->name;
                        $trans1->save();




                        if ($row->discount_amount_tax>0):
                            $discount_data=DB::Connection('mysql2')->select('select sum(txt_amount)amount,txt_acc_id from inv_data where master_id="'.$id.'" group by txt_acc_id');



                            foreach($discount_data as $row1):


                                $trans1 = new Transactions();
                                $trans1 = $trans1->SetConnection('mysql2');
                                $trans1->acc_id = $row1->txt_acc_id;

                                $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row1->txt_acc_id, '');
                                $trans1->master_id = 0;
                                $trans1->particulars = 'Discount Tax';
                                $trans1->opening_bal = 0;
                                $trans1->debit_credit = 1;

                                $trans1->paid_to = $row->bill_to_client_id;
                                $trans1->paid_to_type = 3;

                                $trans1->amount = ($row1->amount/$taxes)*$row->discount_amount_tax;
                                $trans1->voucher_no = $inv_no;
                                $trans1->voucher_type = 5;
                                $trans1->v_date = $inv_date;
                                $trans1->date = date('Y-m-d');
                                $trans1->action = 1;
                                $trans1->status = 1;
                                $trans1->username = Auth::user()->name;
                                $trans1->save();
                            endforeach;
                        endif;

                    endif;


                    if ($row->adv_am>0):
                        $trans1 = new Transactions();
                        $trans1 = $trans1->SetConnection('mysql2');
                        $trans1->acc_id = 521;
                        $trans1->acc_code = '3-2-3-8';;
                        $trans1->master_id = $row->id;
                        $trans1->particulars = 'Advance';

                        $trans1->paid_to = $row->bill_to_client_id;
                        $trans1->paid_to_type = 3;

                        $trans1->opening_bal = 0;
                        $trans1->debit_credit = 1;

                        // 09-dec-2020
                        if ($row->advance_amount_tax==0):
                            $advance_amount=$row->advance_amount_after_tax;
                            else:
                            $advance_amount=$row->adv_am;
                            endif;
                        // 09-dec-2020 End

                        $trans1->amount = $advance_amount;
                        $trans1->voucher_no = $row->inv_no;
                        $trans1->voucher_type = 5;
                        $trans1->v_date = $row->inv_date;
                        $trans1->date = date('Y-m-d');
                        $trans1->action = 1;
                        $trans1->status = 1;
                        $trans1->username = Auth::user()->name;
                        $trans1->save();




                        if ($row->advance_amount_tax>0):
                            $advance_data=DB::Connection('mysql2')->select('select sum(txt_amount)amount,txt_acc_id from inv_data where master_id="'.$id.'" group by txt_acc_id');

                            foreach($advance_data as $row1):


                                $trans1 = new Transactions();
                                $trans1 = $trans1->SetConnection('mysql2');
                                $trans1->acc_id = $row1->txt_acc_id;

                                $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row1->txt_acc_id, '');
                                $trans1->master_id = 0;
                                $trans1->particulars = 'Advance Tax';
                                $trans1->opening_bal = 0;
                                $trans1->debit_credit = 1;

                                $trans1->paid_to = $row->bill_to_client_id;
                                $trans1->paid_to_type =3;

                                $trans1->amount = ($row1->amount/$taxes)*$row->advance_amount_tax;
                                $trans1->voucher_no = $inv_no;
                                $trans1->voucher_type = 5;
                                $trans1->v_date = $inv_date;
                                $trans1->date = date('Y-m-d');
                                $trans1->action = 1;
                                $trans1->status = 1;
                                $trans1->username = Auth::user()->name;
                                $trans1->save();
                            endforeach;
                        endif;


                    endif;
                endif;
                if ($row->amount>0):
                    $trans1 = new Transactions();
                    $trans1 = $trans1->SetConnection('mysql2');
                    $trans1->acc_id = $row->prduct_acc_id;
                    $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row->prduct_acc_id, '');;
                    $trans1->master_id = $row->id;
                    $trans1->particulars = $row->description;
                    $trans1->opening_bal = 0;

                    $trans1->paid_to = $row->branch_id;
                    $trans1->paid_to_type = 5;

                    $trans1->debit_credit = 0;
                    $trans1->amount = $row->amount;
                    $trans1->voucher_no = $row->inv_no;
                    $trans1->voucher_type = 5;
                    $trans1->v_date = $row->inv_date;
                    $trans1->date = date('Y-m-d');
                    $trans1->action = 1;
                    $trans1->status = 1;
                    $trans1->username = Auth::user()->name;
                    $trans1->save();
                endif;





                $count++;
            endforeach;


            $advance_data=DB::Connection('mysql2')->select('select sum(txt_amount)amount,txt_acc_id,branch_id from inv_data where master_id="'.$id.'" group by txt_acc_id');

            foreach($advance_data as $row1):

                if ($row1->amount>0):
                    $trans1 = new Transactions();
                    $trans1 = $trans1->SetConnection('mysql2');
                    $trans1->acc_id = $row1->txt_acc_id;

                    $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row1->txt_acc_id, '');
                    $trans1->master_id = 0;
                    $trans1->particulars = '';
                    $trans1->opening_bal = 0;
                    $trans1->debit_credit = 0;

                    $trans1->paid_to = $row1->branch_id;
                    $trans1->paid_to_type = 5;

                    $trans1->amount = $row1->amount;
                    $trans1->voucher_no = $inv_no;
                    $trans1->voucher_type = 5;
                    $trans1->v_date = $inv_date;
                    $trans1->date = date('Y-m-d');
                    $trans1->action = 1;
                    $trans1->status = 1;
                    $trans1->username = Auth::user()->name;
                    $trans1->save();
                endif;
            endforeach;

            if ($row->sales_tax_amount>0):
                $trans1 = new Transactions();
                $trans1 = $trans1->SetConnection('mysql2');
                $trans1->acc_id = $row->sales_tax_acc_id;
                $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row->sales_tax_acc_id, '');;
                $trans1->master_id = $row->id;
                $trans1->particulars = $row->description;
                $trans1->opening_bal = 0;
                $trans1->debit_credit = 0;

                $trans1->paid_to = $row->branch_id;
                $trans1->paid_to_type = 5;

                $trans1->amount = $row->sales_tax_amount;
                $trans1->voucher_no = $row->inv_no;
                $trans1->voucher_type = 5;
                $trans1->v_date = $row->inv_date;
                $trans1->date = date('Y-m-d');
                $trans1->action = 1;
                $trans1->status = 1;
                $trans1->username = Auth::user()->name;
                //     $trans1->save();
            endif;



            $invoice_data=new InvoiceData();
            $invoice_data=$invoice_data->SetConnection('mysql2');
            $invoice_data=$invoice_data->where('master_id',$id)->update(['inv_status'=>2]);

            $invoice=new Invoice();
            $invoice=$invoice->SetConnection('mysql2');
            $invoice=$invoice->where('id',$id)->update(['inv_status'=>2]);
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }

        echo $id;




    }

    public  function approve_dispatch(Request $request){
        $id = $_GET['id'];
        $id=$request->id;
        DB::Connection('mysql2')->beginTransaction();
        $countOfGroupBy = 0;
        try {
            $dispatch_datas = DB::connection('mysql2')->table('dispatch_datas as dpd')
            ->join('dispatches as dp' , 'dp.id' , 'dpd.dispatch_id')
            ->where('dpd.dispatch_id' , $id)->get();
            foreach ($dispatch_datas as $key => $value) {

                $average_cost=ReuseableCode::average_cost_sales($request->item_id,$request->warehouse_id,$request->batch_code);
    
                $stock=array
                (
                    'main_id'=>$id,
                    'master_id'=>$value->id,
                    'voucher_no'=>$value->dispatch_no,
                    'voucher_date'=>$value->dispatch_date,
                    'supplier_id'=>0,
                    'customer_id'=>$value->customer_id,
                    'voucher_type'=>5,
                    'rate'=>$value->rate,
                    'sub_item_id'=>$value->item_id,
                    'batch_code'=>$value->batch_code,
                    'qty'=>$value->qty,
                    // 'discount_percent'=> $request->input('send_discount' . $i),
                    // 'discount_amount'=> 0 , // $request->input('send_discount_amount' . $i),
                    'amount'=>$value->qty*$average_cost,
                    'status'=>1,
                    'warehouse_id'=>$value->warehouse_id,
                    'username'=>Auth::user()->username,
                    'created_date'=>date('Y-m-d'),
                    'created_date'=>date('Y-m-d'),
                    'opening'=>0,
                    // 'so_data_id'=>$request->input('data_id' . $i)
                );
                $amount =$value->qty*$average_cost;
               $total_amount =0;
               DB::Connection('mysql2')->table('stock')->insert($stock);
    
                $countOfGroupBy ++ ;
            }
            $data3['dispatch_status'] = 2;
            $data3['approve_date'] = date('Y-m-d');
            $data3['approve_username'] = Auth::user()->name;
            DB::connection('mysql2')->table('dispatches')->where('id', $id)->update($data3);
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
            
        }
        echo $id;

        

    }
    public function TrackingDelete()
    {
        $id = $_GET['id'];
        $affected = DB::Connection('mysql2')->table('job_tracking')
            ->where('job_tracking_id', $id)
            ->update(['status' => 2]);

        $affected = DB::Connection('mysql2')->table('job_tracking_data')
            ->where('job_tracking_id', $id)
            ->update(['status' => 2]);
    }

    public function invoice_list(Request $request)
    {
         $from=$request->from;
        $to=$request->to;
        $ClientId=$request->ClientId;

         $m=$request->m;
        if($ClientId !="")
        {
            $invoice = DB::Connection('mysql2')->table('invoice')
                ->select('invoice.*','invoice_data_totals.net_value')
                ->join('invoice_data_totals', 'invoice.id', '=', 'invoice_data_totals.master_id')
                ->whereBetween('invoice.inv_date',[$from,$to])
                ->where('invoice.bill_to_client_id','=',$ClientId)
                ->where('invoice.status','=','1')
                ->get();
        }
        else
        {
            $invoice = DB::Connection('mysql2')->table('invoice')
                ->select('invoice.*','invoice_data_totals.net_value')
                ->join('invoice_data_totals', 'invoice.id', '=', 'invoice_data_totals.master_id')
                ->whereBetween('invoice.inv_date',[$from,$to])
                ->where('invoice.status','=','1')
                ->get();
        }



        return view('Sales.AjaxPages.invoiceList',compact('invoice','m'));
    }


    public function check_item_master_code(Request $request)
    {
        $category=$request->category;
        $sub_category=$request->sub_category;
        $Count = DB::Connection('mysql2')->selectOne('SELECT count(*) as count FROM `item_master` WHERE `category_id` = '.$category.' AND sub_category_id = '.$sub_category.'')->count;

        //    echo $ItemMasterCode->count;
        //die();
        if($Count > 0)
        {
            $ItemMasterCode = DB::Connection('mysql2')->selectOne('SELECT item_master_code FROM `item_master` WHERE `category_id` = '.$category.' AND sub_category_id = '.$sub_category.' order by id desc')->item_master_code;
            echo $ItemMasterCode;
        }
        else
        {
            echo '';
        }

    }
    public static function get_bundels_data(Request $request)
    {
        $product_id=$request->product_id;
        $data=DB::Connection('mysql2')->table('bundles_data as a')
            ->join('bundles as b','a.master_id','=','b.id')
            ->where('a.master_id',$product_id)
            ->where('b.id',$product_id)
            ->select('a.*','b.bundle_unit','b.qty as bundle_qty','b.rate as bundle_rate','b.amount as bundle_amount','b.discount_percent as bundle_percent','b.discount_amount as discount_bunle_amount'
            ,'b.net_amount as bundle_amount')
            ->get();
        return view('Sales.AjaxPages.bundles_data',compact('product_id','data'));
    }

    public static function get_import_data(Request $request)
    {
               $voucher_no=$request->value;

               $data= DB::Connection('mysql2')->table('import_po_data')
                 ->where('master_id',$voucher_no)
                ->where('status',1)
                   ->where('type',1)
                ->get();
        $data22= DB::Connection('mysql2')->table('import_po_data')
            ->where('master_id',$voucher_no)
            ->where('status',1)
            ->where('type',2)
            ->get();

        return view('Sales.AjaxPages.get_import_details',compact('data','data22','voucher_no'));

    }


    public static function get_pay_form(Request $request)
    {
        $type=$request->type;
        $voucher_no=$request->voucher_no;
        $payId = $request->id;

        $pv_data=ReuseableCode::check_import_payment_in_pv($payId,$type);
        if ($pv_data->count()>0):
        return '<h1 style="color:red">Can Not Edit</h2>';

        endif;
        return view('Sales.AjaxPages.payment_against_import',compact('data','voucher_no','type','payId'));

    }

    public static function getSoReportBySoNo(Request $request)
    {
        $SoId=$request->SoId;
        $m=$request->m;
        $SalesOrder = DB::Connection('mysql2')->table('sales_order')->where('id',$SoId)->get();
        $DeliveryNote = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->where('master_id',$SoId);
        $SalesTaxInvoice = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('so_id',$SoId);
        $SalesReturn = DB::Connection('mysql2')->table('credit_note')->where('status',1)->where('so_id',$SoId);

        return view('Sales.AjaxPages.getSoReportBySoNo',compact('SalesOrder','DeliveryNote','SalesTaxInvoice','SalesReturn','SoId','m'));

    }

    public static function getDeliveryNoteFilterWise(Request $request)
    {
        $fromDate = $request->from;
        $to = $request->to;
        $m=$request->m;
        $SearchText = $request->SearchText;
        $radioValue = $request->radioValue;
        $FilterType = $request->FilterType;
        $BuyerId = $request->BuyerId;
        $radio = $request->radio;
        if($FilterType == 2)
        {
            if($radioValue == 1 && $SearchText !="")
            {
                $delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->where('so_no','like', '%' . $SearchText . '%')->orderBy('id', 'DESC')->get();
            }
            elseif($radioValue == 2 && $SearchText !="")
            {
                $delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->where('gd_no','like', '%' . $SearchText . '%')->orderBy('id', 'DESC')->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Enter Voucher No</strong></td></tr>';
            }
        }
        elseif($FilterType == 3)
        {
            if($BuyerId !="")
            {
                $delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->where('buyers_id',$BuyerId)->orderBy('id', 'DESC')->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Select Buyer</strong></td></tr>';
            }
        }
        else
        {

            $delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->whereBetween('gd_date',[$fromDate,$to])->orderBy('id', 'DESC')->get();

        }

        return view('Sales.AjaxPages.getDeliveryNoteFilterWise',compact('delivery_note','m','radio'));

    }

    public static function getDeliveryChallanFilterWise(Request $request)
    {
        $fromDate = $request->from;
        $to = $request->to;
        $m=$request->m;
        $SearchText = $request->SearchText;
        $radioValue = $request->radioValue;
        $FilterType = $request->FilterType;
        $BuyerId = $request->BuyerId;
        $radio = $request->radio;
        $delivery_note = [];
        if($FilterType == 2)
        {
            if($radioValue == 1 && $SearchText !="")
            {
                $delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->where('so_no','like', '%' . $SearchText . '%')->get();
            }
            elseif($radioValue == 2 && $SearchText !="")
            {
                $delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->where('gd_no','like', '%' . $SearchText . '%')->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Enter Voucher No</strong></td></tr>';
            }
        }
        elseif($FilterType == 3)
        {
            if($BuyerId !="")
            {
                $delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->where('buyers_id',$BuyerId)->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Select Buyer</strong></td></tr>';
            }
        }
        else
        {

            $delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->whereBetween('gd_date',[$fromDate,$to])->get();

        }

        return view('Sales.AjaxPages.getDeliveryChallanFilterWise',compact('delivery_note','m','radio'));

    }

    public static function getSalesTaxInvoiceeFilterWise(Request $request)
    {
        $fromDate = $request->from;
        $to = $request->to;
        $m=$request->m;
        $SearchText = strtolower($request->SearchText);
        $radioValue = $request->radioValue;
        $radio = $request->radio;
        $FilterType = $request->FilterType;
        $BuyerId = $request->BuyerId;
        if($FilterType == 2)
        {
            if($radioValue == 1 && $SearchText !="")
            {
                $sales_tax_invoice  = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('so_no','like', '%' . $SearchText . '%')->get();
            }
            elseif($radioValue == 2 && $SearchText !="")
            {
                $sales_tax_invoice= DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('gi_no','like', '%' . $SearchText . '%')->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Enter Voucher No</strong></td></tr>';
            }
        }
        elseif($FilterType == 3)
        {
            if($BuyerId !="")
            {
                $sales_tax_invoice = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('buyers_id',$BuyerId)->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Select Buyer</strong></td></tr>';
            }
        }
        else
        {
            $sales_tax_invoice = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->whereBetween('gi_date',[$fromDate,$to])->get();
        }

        return view('Sales.AjaxPages.getSalesTaxInvoiceeFilterWise',compact('sales_tax_invoice','m','radio'));

    }



    public static function getSalesOrderDateWise(Request $request)
    {
        $FromDate = $request->from;
        $ToDate = $request->to;
        $SoNo = $request->SoNo;
        $FilterType = $request->FilterType;
        $BuyerId = $request->BuyerId;
        $m = $request->m;
        $radio = $request->radio;
        if($FilterType ==2 && $SoNo !="")
        {
            if($SoNo !="")
            {
                $sale_order=new Sales_Order();
                $sale_order=$sale_order->SetConnection('mysql2');
                $sale_order=$sale_order->where('status',1)->where('so_no','like', '%' . $SoNo . '%')->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Enter So No</strong></td></tr>';
            }

        }
        elseif($FilterType ==3)
        {
            if($BuyerId !="")
            {
                $sale_order=new Sales_Order();
                $sale_order=$sale_order->SetConnection('mysql2');
                $sale_order=$sale_order->where('status',1)->where('buyers_id',$BuyerId)->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Select Buyer</strong></td></tr>';
            }
        }

        else
        {
            $sale_order=new Sales_Order();
            $sale_order=$sale_order->SetConnection('mysql2');
            $sale_order=$sale_order->where('status',1)->whereBetween('so_date',[$FromDate,$ToDate])->orderBy('so_date','ASC')->get();
        }

        return view('Sales.AjaxPages.getSalesOrderDateWise',compact('sale_order','m','radio'));
    }

    public static function getSalesOrderDateWiseForDeliveryNote(Request $request)
    {
        $FromDate = $request->from;
        $ToDate = $request->to;
        $SoNo = $request->SoNo;
        $FilterType = $request->FilterType;
        $BuyerId = $request->BuyerId;
        $m = $request->m;

        if ($FilterType == 2 && $SoNo != "") {
            if ($SoNo != "") {
                $sale_order = new Sales_Order();
                $sale_order = $sale_order->SetConnection('mysql2');
                $sale_order = $sale_order->where('status', 1)->where('delivery_note_status', 0)->where('so_no', 'like', '%' . $SoNo . '%')->get();
            } else {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Enter So No</strong></td></tr>';
            }

        } elseif ($FilterType == 3) {
            if ($BuyerId != "") {
                $sale_order = new Sales_Order();
                $sale_order = $sale_order->SetConnection('mysql2');
                $sale_order = $sale_order->where('status', 1)->where('delivery_note_status', 0)->where('buyers_id', $BuyerId)->get();
            } else {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Select Buyer</strong></td></tr>';
            }
        } else {
            $sale_order = new Sales_Order();
            $sale_order = $sale_order->SetConnection('mysql2');
            $sale_order = $sale_order->where('status', 1)->where('delivery_note_status', 0)->whereBetween('so_date', [$FromDate, $ToDate])->orderBy('so_date', 'ASC')->get();
        }

        return view('Sales.AjaxPages.getSalesOrderDateWiseForDeliveryNote', compact('sale_order', 'm'));
    }

    public static function getSalesOrderDateWiseForDeliveryChallan(Request $request)
    {
        $FromDate = $request->from;
        $ToDate = $request->to;
        $SoNo = $request->SoNo;
        $FilterType = $request->FilterType;
        $BuyerId = $request->BuyerId;
        $m = $request->m;
       
        
        $sale_order = [];

       
        if($FilterType ==2)
        {
          
            
                
                $sale_order = DB::Connection('mysql2')
                                    ->table('sales_order as so')
                                    ->select('so.*','sod.id as sod_id','sod.item_id', 'pp.order_no')
                                    ->join('sales_order_data as sod', 'so.id', '=', 'sod.master_id')
                                    ->join('production_plane as pp', 'pp.sales_order_id', '=', 'so.id')
                                    ->join('production_plane_data as ppd', 'ppd.master_id', '=', 'pp.id')
                                    ->where('ppd.finish_goods_id', '=', DB::raw('sod.item_id'))
                                    ->where('so.status', 1)
                                    ->where('sod.status', 1)
                                    ->where('pp.status', 1)
                                    ->where('ppd.status', 1)
                                    ->groupBy('sod.id');

                                    if($SoNo !="")
                                    {
                                        $sale_order = $sale_order->where('so.so_no','like', '%' . $SoNo . '%');
                                    }   

                                    $sale_order = $sale_order->get();

            
            if(count($sale_order) < 1)
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>data not found</strong></td></tr>';
            }

        }
        elseif($FilterType == 3)
        {
            

                $sale_order = DB::Connection('mysql2')
                                    ->table('sales_order as so')
                                    ->select('so.*', 'sod.id as sod_id','sod.item_id', 'pp.order_no')
                                    ->join('sales_order_data as sod', 'so.id', '=', 'sod.master_id')
                                    ->join('production_plane as pp', 'pp.sales_order_id', '=', 'so.id')
                                    ->join('production_plane_data as ppd', 'ppd.master_id', '=', 'pp.id')
                                    ->where('ppd.finish_goods_id', '=', DB::raw('sod.item_id'))
                                    ->where('so.status', 1)
                                    ->where('sod.status', 1)
                                    ->where('pp.status', 1)
                                    ->where('ppd.status', 1)
                                    ->groupBy('sod.id');

                if($BuyerId !="")
                {
                    
                    $sale_order = $sale_order->where('so.buyers_id',$BuyerId);
                }

                $sale_order = $sale_order->get();


            if(count($sale_order) < 1)
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>data not found</strong></td></tr>';
            }
        }

        else
        {
            
            $sale_order = DB::Connection('mysql2')
                                ->table('sales_order as so')
                                ->select('so.*','sod.id as sod_id','sod.item_id', 'pp.order_no')
                                ->join('sales_order_data as sod', 'so.id', '=', 'sod.master_id')
                                ->join('production_plane as pp', 'pp.sales_order_id', '=', 'so.id')
                                ->join('production_plane_data as ppd', 'ppd.master_id', '=', 'pp.id')
                                ->where('ppd.finish_goods_id', '=', DB::raw('sod.item_id'))
                                ->where('so.status', 1)
                                ->where('sod.status', 1)
                                ->where('pp.status', 1)
                                ->where('ppd.status', 1)
                                ->groupBy('sod.id')
                                ->whereBetween('so.so_date',[$FromDate,$ToDate])->orderBy('so.so_date','ASC')->get();


            if(count($sale_order) < 1)
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>data not found</strong></td></tr>';
            }

        }

        return view('Sales.AjaxPages.getSalesOrderDateWiseForDeliveryChallan',compact('sale_order','m'));
    }

    public static function getSalesOrderDateWiseForDispatch(Request $request)
    {
        $FromDate = $request->from;
        $ToDate = $request->to;
        $SoNo = $request->SoNo;
        $FilterType = $request->FilterType;
        $BuyerId = $request->BuyerId;
        $m = $request->m;
       
        
        $sale_order = [];

       
        if($FilterType ==2)
        {
          
            
                
                $sale_order = DB::Connection('mysql2')
                                    ->table('sales_order as so')
                                    ->select('so.*','sod.id as sod_id','sod.item_id', 'pp.order_no', 'p.packing_list_no' ,  DB::raw('count(pd.id) as no_bundle') , 'dc.dc_no')
                                    ->join('sales_order_data as sod', 'so.id', '=', 'sod.master_id')
                                    ->join('delivery_note as dc', 'dc.master_id', '=', 'so.id')
                                    ->join('packings as p', 'p.so_id', '=', 'so.id')
                                    ->join('production_plane as pp', 'pp.sales_order_id', '=', 'so.id')
                                    ->join('production_plane_data as ppd', 'ppd.master_id', '=', 'pp.id')
                                    ->where('ppd.finish_goods_id', '=', DB::raw('sod.item_id'))
                                    ->where('so.status', 1)
                                    ->where('sod.status', 1)
                                    ->where('p.status', 1)
                                    ->where('pp.status', 1)
                                    ->where('ppd.status', 1)
                                    ->where('dc.status', 1)
                                    ->where('p.qc_status', 1)
                                    ->groupBy('dc.id');

                                    if($SoNo !="")
                                    {
                                        $sale_order = $sale_order->where('so.so_no','like', '%' . $SoNo . '%');
                                    }   

                                    $sale_order = $sale_order->get();

            
            if(count($sale_order) < 1)
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>data not found</strong></td></tr>';
            }

        }
        elseif($FilterType == 3)
        {
            

                $sale_order = DB::Connection('mysql2')
                                    ->table('sales_order as so')
                                    ->select('so.*','sod.id as sod_id','sod.item_id', 'pp.order_no', 'p.packing_list_no' ,  DB::raw('count(pd.id) as no_bundle') , 'dc.dc_no')
                                    ->join('sales_order_data as sod', 'so.id', '=', 'sod.master_id')
                                    ->join('delivery_note as dc', 'dc.master_id', '=', 'so.id')
                                    ->join('packings as p', 'p.so_id', '=', 'so.id')
                                    ->join('production_plane as pp', 'pp.sales_order_id', '=', 'so.id')
                                    ->join('production_plane_data as ppd', 'ppd.master_id', '=', 'pp.id')
                                    ->where('ppd.finish_goods_id', '=', DB::raw('sod.item_id'))
                                    ->where('so.status', 1)
                                    ->where('sod.status', 1)
                                    ->where('pp.status', 1)
                                    ->where('p.qc_status', 3)
                                    ->where('p.status', 1)
                                    ->where('dc.status', 1)
                                    ->where('ppd.status', 1)
                                    ->groupBy('dc.id');

                if($BuyerId !="")
                {
                    
                    $sale_order = $sale_order->where('so.buyers_id',$BuyerId);
                }

                $sale_order = $sale_order->get();


            if(count($sale_order) < 1)
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>data not found</strong></td></tr>';
            }
        }

        else
        {
            
            $sale_order = DB::Connection('mysql2')
                                ->table('sales_order as so')
                                ->select('so.*','sod.id as sod_id','sod.item_id', 'pp.order_no', 'p.packing_list_no' ,  DB::raw('count(pd.id) as no_bundle') , 'dc.dc_no', 'p.id as p_id')
                                // ->select('so.*','sod.id as sod_id','sod.item_id', 'pp.order_no', 'p.packing_list_no' , 'dc.dc_no', 'p.id as p_id')
                                ->join('sales_order_data as sod', 'so.id', '=', 'sod.master_id')
                                ->join('packings as p' , function($join){
                                    $join->on('p.so_id'  , 'so.id');
                                    $join->on('p.item_id'  , 'sod.item_id');
                                })
                                // ->join('packings as p', 'p.so_id', '=', 'so.id')
                                ->join('delivery_note as dc', 'dc.id', '=', 'p.dc_id')
                                ->join('packing_datas as pd', 'pd.packing_id', '=', 'p.id')
                                ->join('production_plane as pp', 'pp.id', '=', 'p.production_plan_id')
                                ->join('production_plane_data as ppd', 'ppd.master_id', '=', 'pp.id')
                                ->where('ppd.finish_goods_id', '=', DB::raw('sod.item_id'))
                                ->where('so.status', 1)
                                ->where('sod.status', 1)
                                ->where('pp.status', 1)
                                ->where('ppd.status', 1)
                                ->where('dc.status', 1)
                                ->where('p.status', 1)
                                ->where('p.qc_status', 3)
                                // ->groupBy('sod_id' , 'sod.item_id' , 'p_id')
                                ->groupBy('p_id')
                                ->whereBetween('so.so_date',[$FromDate,$ToDate])->orderBy('so.so_date','ASC')->get();
            // dd($sale_order);

            if(count($sale_order) < 1)
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>data not found</strong></td></tr>';
            }

        }

        return view('Sales.AjaxPages.getSalesOrderDateWiseForDispatch',compact('sale_order','m'));
    }

    public static function getSalesOrderDateWiseForDeliveryChallanOld(Request $request)
    {
        $FromDate = $request->from;
        $ToDate = $request->to;
        $SoNo = $request->SoNo;
        $FilterType = $request->FilterType;
        $BuyerId = $request->BuyerId;
        $m = $request->m;
        $sale_order = [];
        if($FilterType ==2 && $SoNo !="")
        {
            if($SoNo !="")
            {
                
                $sale_order = DB::Connection('mysql2')->table('sales_order as so')
                            ->join('packings as p', 'so.id', '=', 'p.so_id')
                            ->join('qc_packings as qp', 'p.id', '=', 'qp.packing_list_id')
                            ->where('qp.status', 1)
                            ->where('so.status', 1)
                            ->where('qp.dc_status', 1)
                            ->where('p.qc_status', 3)
                            ->select('so.*','p.packing_list_no','p.id as packing_id','qp.id as qc_packing_id')
                            ->where('so.delivery_note_status',0)
                            ->where('so.so_no','like', '%' . $SoNo . '%')->get();

                            // echo "<pre>";
                            // print_r($sale_order);exit();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Enter So No</strong></td></tr>';
            }

        }
        elseif($FilterType ==3)
        {
            if($BuyerId !="")
            {

                $sale_order =  DB::Connection('mysql2')->table('sales_order as so')
                ->join('packings as p', 'so.id', '=', 'p.so_id')
                ->join('qc_packings as qp', 'p.id', '=', 'qp.packing_list_id')
                ->where('qp.status', 1)
                ->where('so.status', 1)
                ->where('qp.dc_status', 1)
                ->where('p.qc_status', 3)
                ->select('so.*','p.packing_list_no','p.id as packing_id','qp.id as qc_packing_id')
                ->where('so.delivery_note_status',0)
                ->where('so.buyers_id',$BuyerId)->get();
            }
            else
            {
                echo '<tr class="text-center"><td class="text-danger" colspan="11" style="font-size: 18px;"><strong>Please Select Buyer</strong></td></tr>';
            }
        }

        else
        {
            
            $sale_order =  DB::Connection('mysql2')->table('sales_order as so')
            ->join('packings as p', 'so.id', '=', 'p.so_id')
            ->join('qc_packings as qp', 'p.id', '=', 'qp.packing_list_id')
            ->where('qp.status', 1)
            ->where('so.status', 1)
            ->where('qp.dc_status', 1)
            ->where('p.qc_status', 3)
            ->select('so.*','p.packing_list_no','p.id as packing_id','qp.id as qc_packing_id')
            ->where('so.delivery_note_status',0)
            ->whereBetween('so.so_date',[$FromDate,$ToDate])->orderBy('so.so_date','ASC')->get();

        }

        return view('Sales.AjaxPages.getSalesOrderDateWiseForDeliveryChallanOld',compact('sale_order','m'));
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
                                        <th class="text-center">Si No</th>
                                        <th class="text-center">So No</th>
                                        <th class="text-center">Invoice Amount</th>
                                        <th class="text-center">Balance Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $OpeningData = DB::Connection('mysql2')->table('customer_opening_balance')->where('buyer_id',$BuyerId)->get();
                                    $Counter =1;
                                    foreach($OpeningData as $Fil):
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $Counter++;?></td>
                                            <td><?php echo CommonHelper::changeDateFormat($Fil->date);?></td>
                                            <td><?php echo $Fil->si_no?></td>
                                            <td><?php echo $Fil->so_no?></td>
                                            <td><?php echo number_format($Fil->invoice_amount,2)?></td>
                                            <td><?php echo number_format($Fil->balance_amount,2)?></td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function getSoDetailDateWise(Request $request)
    {
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $m = $request->m;
        $SoData = DB::Connection('mysql2')->select('select a.sales_tax,a.buyers_id,a.so_date,b.* from sales_order a Inner join sales_order_data b on b.master_id = a.id
        where a.status = 1
        and a.so_date between "'.$FromDate.'" and "'.$ToDate.'"');

        return view('Sales.AjaxPages.getSoDetailDateWise',compact('SoData','m'));
    }

    public function getDnDetailDateWise(Request $request)
    {
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $m = $request->m;
        $DnData = DB::Connection('mysql2')->select('select a.gd_no,a.gd_date,a.buyers_id,a.so_no,a.so_date,b.* from delivery_note a
                                                    Inner join delivery_note_data b on b.master_id = a.id
                                                    where a.status = 1
                                                    and a.gd_date between "'.$FromDate.'" and "'.$ToDate.'"');

        return view('Sales.AjaxPages.getDnDetailDateWise',compact('DnData','m'));
    }

    public function getCustomerCreditNoteData(Request $request)
    {
        $FromDate = $request->from;
        $ToDate = $request->to;
        $m = $request->m;

        $credit_note=new CreditNote();
        $credit_note=$credit_note->SetConnection('mysql2');
        $credit_note=$credit_note->where('status',1)->whereBetween('cr_date',[$FromDate,$ToDate])->get();
        return view('Sales.AjaxPages.getCustomerCreditNoteData',compact('credit_note','m'));
    }

    public function getSalesTaxInvoiceReportData(Request $request)
    {
        $FromDate = $request->from;
        $ToDate = $request->to;
        $m = $request->m;
        $buyer=$request->buyer;
        return view('Sales.AjaxPages.getSalesTaxInvoiceReportData',compact('FromDate','ToDate','m','buyer'));
    }

    public function getDnWithoutSalesTax(Request $request)
    {
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $m = $request->m;
        return view('Sales.AjaxPages.dn_without_SalesAjax',compact('FromDate','ToDate','m'));
    }

    public function cogs_si(Request $request)
    {
            $from = $request->from;
             $to = $request->to;
             $radio = $request->radio;

        if ($radio==1):
        return view('Sales.AjaxPages.cogs_si',compact('from','to'));
         else:
        return view('Sales.AjaxPages.cogs_si_item_wise',compact('from','to'));
        endif;
    }

    public function delete_sales_return(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {
            $id=  $request->id;
             $cr_on=$request->cr_no;
            $data= DB::Connection('mysql2')->table('credit_note as a')
            ->join('credit_note_data as b','a.id','=','b.master_id')
            ->join('stock as c','a.cr_no','=','c.voucher_no')
            ->where('a.status',1)
            ->where('a.id',$id)
            ->select('b.item','b.qty','c.batch_code','c.warehouse_id','b.qty')
            ->get();

        $validation=1;
        foreach($data as $row):

            $row->qty;

          $qty=  ReuseableCode::get_stock($row->item,$row->warehouse_id,$row->qty,$row->batch_code);
           if ($qty<0):
          $validation=0;
           endif;
        endforeach;

        if ($validation==1):


         $dataa['status']=0;

        DB::Connection('mysql2')->table('credit_note')->where('id',$id)->update($dataa);
        DB::Connection('mysql2')->table('credit_note_data')->where('master_id',$id)->update($dataa);
        DB::Connection('mysql2')->table('stock')->where('voucher_no',$cr_on)->update($dataa);
        DB::Connection('mysql2')->table('transactions')->where('voucher_no',$cr_on)->update($dataa);
          echo $id;
            else:
             echo '0';
            endif;

            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }

    }

    public function pos_list_flter_wise(Request $request)
    {
        $from=$request->from;
        $to=$request->to;

        $data=DB::Connection('mysql2')->table('pos')->where('status',1)->get();
        return view('Sales.AjaxPages.pos_list_flter_wise',compact('data'));
    }


    public function delete_pos(Request $request)
    {
        $id=$request->id;
        $pos_no=DB::Connection('mysql2')->table('pos')->where('id',$id)->select('pos_no')->value('pos_no');
        $payment_count=DB::Connection('mysql2')->table('received_paymet')->where('status',1)->where('pos_id',$id)->count();
        $credit_note=DB::Connection('mysql2')->table('credit_note')->where('status',1)->where('pos_id',$id)->count();
        $data['status']=0;
        $data1['status']=5;

        if ($payment_count==0 && $credit_note==0):

        DB::Connection('mysql2')->table('pos')->where('id',$id)->update($data);
        DB::Connection('mysql2')->table('pos_data')->where('master_id',$id)->update($data);
        DB::Connection('mysql2')->table('stock')->where('voucher_no',$pos_no)->update($data1);
        DB::Connection('mysql2')->table('transactions')->where('voucher_no',$pos_no)->update($data1);
        echo $id;
        else:
            echo 0;
        endif;
    }

    function import_payment_delete(Request $request)
    {
        $id=$request->id;
        $data['status']=0;

       $pv_data= ReuseableCode::check_import_payment_in_pv($id,$request->type);

        if ($pv_data->count()<1000):

        if ($request->type==1):
        DB::Connection('mysql2')->table('import_payment')->where('id',$id)->update($data);
        endif;

        if ($request->type==2):
            DB::Connection('mysql2')->table('import_expense')->where('id',$id)->update($data);
        endif;

        if ($request->type==1):
        $pv_data=DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('import_payment_id',$id)->where('type',3);

            endif;

        if ($request->type==2):
            $pv_data=DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('import_payment_id',$id)->where('type',4);

        endif;
        if ($pv_data->count()>0):
            $pv_id=$pv_data->first()->id;
            $pv_no=$pv_data->first()->pv_no;
            $data1['status']=0;
            $pv_data=DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('id',$pv_id)->update($data1);
            $pv_data=DB::Connection('mysql2')->table('new_pv_data')->where('status',1)->where('master_id',$pv_id)->update($data1);
            $pv_data=DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$pv_no)->update($data1);
            endif;
        echo $id;
            else:
                echo 'no';
                endif;
    }

    public function get_import_docs(Request $request)
    {
        $SupplierId = $request->SupplierId;
        $data = DB::Connection('mysql2')->table('import_po')->where('status', 1)->where('vendor', $SupplierId)->get();
        echo '<option value="">Select</option>';
        foreach ($data as $Dfil) {
            echo '<option value="' . $Dfil->id . '">' . $Dfil->voucher_no . '</option>';
        }


    }

        public function import_delete(Request $request)
        {
            $id=$request->id;
            $payment_count=CommonHelper::table_counting('import_payment','import_id',$id);
            if ($payment_count>0):
                return 'no';
                endif;

            $expense_count=CommonHelper::table_counting('import_expense','import_id',$id);
            if ($expense_count>0):
                return 'no';
            endif;


            if ($payment_count==0 && $expense_count==0):
            $data['status']=0;
            DB::Connection('mysql2')->table('import_po')->where('id',$id)->update($data);
            DB::Connection('mysql2')->table('import_po_data')->where('master_id',$id)->update($data);

                return $id;
                endif;

        }

        public  function  update_cost(Request  $request)
        {
            $si_no = $request->si_no;
            $value = $request->value;

            $data=array
            (
                    'amount'=>$value,

            );

            DB::Connection('mysql2')->table('transactions')
                ->where('status',1)
                ->where('voucher_no',$si_no)
                ->where('voucher_type', 8)
                ->update($data);
        }
}
