<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use App\Models\Sales_Order;
use Illuminate\Http\Request;
use App\Helpers\DashboardHelper;
use App\Helpers\ReuseableCode;

use DB;
use Session;


class ClientController extends Controller
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
    public function index()
    {
        $sale_orders = DB::connection('mysql2')
        ->table('sales_order')
        ->join('customers','customers.id','sales_order.buyers_id')
        ->where('sales_order.status', 1)
        ->paginate(10);

        $from = date('Y-m-01');
        $to  = date('Y-m-t');
                                //collection
        $collection = DB::Connection('mysql2')->table('received_paymet as a')
                    ->join('new_rvs as b','a.receipt_no','b.rv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.rv_date',[$from , $to])
                    ->sum('a.received_amount');

                    // total receipt

        $total_receipt = DB::Connection('mysql2')->table('received_paymet as a')
                    ->join('new_rvs as b','a.receipt_no','b.rv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.rv_date',[$from , $to])
                    ->count('a.id');

                    // total payment


        $total_payment = DB::Connection('mysql2')->table('new_purchase_voucher_payment as a')
                    ->join('new_pv as b','a.new_pv_no','b.pv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.pv_date',[$from , $to])
                    ->count('a.id');

                    $resultArray = [$total_receipt,$total_payment];
                    $jsonResult = json_encode($resultArray);

		return view('dClient.home',compact('sale_orders','collection','total_receipt','total_payment','jsonResult'));
    }
    public function financeDashboard()
    {
        $sale_orders = DB::connection('mysql2')
        ->table('sales_order')
        ->join('customers','customers.id','sales_order.buyers_id')
        ->where('sales_order.status', 1)
        ->paginate(10);

        $from = date('Y-m-01');
        $to  = date('Y-m-t');
                                //collection
        $collection = DB::Connection('mysql2')->table('received_paymet as a')
                    ->join('new_rvs as b','a.receipt_no','b.rv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.rv_date',[$from , $to])
                    ->sum('a.received_amount');

                    // total receipt

        $total_receipt = DB::Connection('mysql2')->table('received_paymet as a')
                    ->join('new_rvs as b','a.receipt_no','b.rv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.rv_date',[$from , $to])
                    ->count('a.id');

                    // total payment


        $total_payment = DB::Connection('mysql2')->table('new_purchase_voucher_payment as a')
                    ->join('new_pv as b','a.new_pv_no','b.pv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.pv_date',[$from , $to])
                    ->count('a.id');

                    $resultArray = [$total_receipt,$total_payment];
                    $jsonResult = json_encode($resultArray);

		return view('dClient.financeDashboard',compact('sale_orders','collection','total_receipt','total_payment','jsonResult'));
    }

    public function get_customer_sales_info(Request $request)
    {   
        $customer_total_amount = 0;
        foreach(DashboardHelper::CustomerWiseSales($request->year) as $key => $value){ 
            $customer_total_amount += $value->amount;
        ?>
             <tr>
                <td><?php echo $value->name; ?></td>
                <td><?php echo number_format($value->amount , 2); ?></td>
            </tr>
        <?php  
        }
        ?>
         <tr>
            <th class="text-center">Total</th>
            <th class="text-center"><?php echo number_format($customer_total_amount , 2); ?></th>
        </tr>
        <?php 

    }

    public function BusinessFlowChartAjax(Request $request)
    {
        $SalesFlowChart = DashboardHelper::SalesFlowChart($request->year);
        return response()->json([ 'SalesFlowChart' => $SalesFlowChart]);
    }

    public function trtpAjax(Request $request)
    {

        if(empty($request->monthyear))
        {

            $from = date('Y-m-01');
            $to  = date('Y-m-t');
        }
        else
        {
            $from = date("Y-m-01", strtotime($request->monthyear));
            $to = date("Y-m-t", strtotime($request->monthyear));
        }

        

        
        $total_receipt = DB::Connection('mysql2')->table('transactions as t')
                    ->where('t.status',1)
                    ->where('t.debit_credit',1)
        			->where('voucher_type', '>', 0)
                    ->whereIn('acc_id',[90,91,92,86,87,88,388,389])
                    ->whereBetween('t.v_date',[$from , $to])
                    ->sum('t.amount');

                    // total payment


        $total_payment = DB::Connection('mysql2')->table('transactions as t')
                        ->where('t.status',1)
                        ->where('t.debit_credit',0)
            			->where('voucher_type', '>', 0)
                        ->whereIn('acc_id',[90,91,92,86,87,88,388,389])
                        ->whereBetween('t.v_date',[$from , $to])
                        ->sum('t.amount');

                    // $resultArray = [$total_receipt,$total_payment];
                    // $jsonResult = json_encode($resultArray);
        return response()->json([$total_receipt,$total_payment]);
    }
    
    public function salesAgingAjax(Request $request)
    {

        $data =ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from=$data[0];
        $to=$request->as_on;
        if(empty($request->date))
        {
            $to  = date('Y-m-d');
        }
        else
        {
            $to = $request->date;
        }
        // echo "$from - $to";

        return view('dClient.agingAjax.salesAgingAjax',compact('from','to'));
        
        // return response()->json([$total_receipt,$total_payment]);
    }
    
    public function vendorAgingAjax(Request $request)
    {

        $data =ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from=$data[0];
        $to=$request->as_on;
        if(empty($request->date))
        {
            $to  = date('Y-m-d');
        }
        else
        {
            $to = $request->date;
        }
        // echo "$from - $to";

        return view('dClient.agingAjax.vendorAgingAjax',compact('from','to'));
        
        // return response()->json([$total_receipt,$total_payment]);
    }
    
    public function financeDashboardAjax(Request $request)
    {
        $SalesFlowChart = DashboardHelper::SalesFlowChart($request->year);
        return compact('SalesFlowChart');
    }

    public function clientCompanyMenu(){
        return view('dClient.home');
    }

    public function production_dashboard(){
        return view('dClient.production_dashboard');
    }

    public function mydesk(){
        return view('dClient.mydesk');
    }

    public function alert(){
        return view('dClient.alert');
    }


}

