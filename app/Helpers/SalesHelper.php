<?php
namespace App\Helpers;
use DB;
use Auth;
use Request;
use Config;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierInfo;
use App\Models\Subitem;
use App\Models\Employee;
use App\Models\EmployeeExitClearance;
use App\Models\MenuPrivileges;
use App\Models\Menu;
use App\Models\UOM;
use App\Models\PurchaseVoucher;
use App\Models\Account;
use App\Models\FinanceDepartment;
use App\Models\Transactions;
use App\Models\CostCenter;
use App\Models\DepartmentAllocation1;
use App\Models\SalesTaxDepartmentAllocation;
use App\Models\CostCenterDepartmentAllocation;
use App\Models\PurchaseType;
use App\Models\Currency;
use App\Models\GRNData;
use App\Models\DemandType;
use App\Models\Warehouse;
use App\Models\GoodsReceiptNote;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\PurchaseVoucherThroughGrn;
use App\Models\PurchaseVoucherThroughGrnData;
use App\Models\SubItemCharges;
use App\Models\Pvs;
use App\Models\Pvs_data;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;
use App\Models\Sales_Order;
use App\Models\Client;
use Illuminate\Support\Facades\DB as FacadesDB;

class SalesHelper
{

    public static function deliverMode($param)
    {
        if($param) {
            $array[1] = "Cash";
            $array[2] = "Cheque";
            $array[3] = "Credit";
            echo $array[$param];
        } else {
            echo "";
        }
    }
    public static function get_bacth_data_by_item_wise($id)
    {
        $batch= new Batch();
        $batch=$batch->SetConnection('mysql2');
      return  $batch=$batch->where('item_id',$id)->get();
    }

    public static function get_all_customers()
    {
        $customer= new Customer();
        $customer=$customer->SetConnection('mysql2');

          $customer=$customer->where('status',1)->get();
        echo '<pre>';
        print_r($customer);die;
    }

     public static function get_unique_no($year, $month)
    {

        $purchaseRequestNo = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`so_no`,7,length(substr(`so_no`,3))-3),signed integer)) reg
        from `sales_order` where substr(`so_no`,3,2) = " . $year . " and substr(`so_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $purchaseRequestNo = 'so' . $year . $month . $str;


    }

    public static	function  sales_activity($voucher_no,$voucher_date,$amount,$table,$action)
    {

        date_default_timezone_set("Asia/Karachi");
        $data=array
        (
            'voucher_no'=>$voucher_no,
            'voucher_date'=>$voucher_date,
            'amount'=>$amount,
            'table_name'=>$table,
            'action'=>$action,
            'action_date'=>date('Y-m-d'),
            'username'=>Auth::user()->name,
            'action_time'=>date('h:i:sa'),
        );

        DB::Connection('mysql2')->table('sales_activity')->insert($data);
    }

    public static function get_unique_no_delivery_note($year, $month)
    {
        $purchaseRequestNo = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`gd_no`,7,length(substr(`gd_no`,3))-3),signed integer)) reg
        from `delivery_note` where substr(`gd_no`,3,2) = " . $year . " and substr(`gd_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $purchaseRequestNo = 'dn' . $year . $month . $str;


    }

    public static function get_unique_no_sales_tax_invoice($year, $month)
    {
        $purchaseRequestNo = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`gi_no`,7,length(substr(`gi_no`,3))-3),signed integer)) reg
        from `sales_tax_invoice` where substr(`gi_no`,3,2) = " . $year . " and substr(`gi_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $purchaseRequestNo = 'si' . $year . $month . $str;


    }

    public static function get_unique_no_quotation($year, $month)
    {

        $quotation_no = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`quotation_no`,7,length(substr(`quotation_no`,3))-3),signed integer)) reg
        from `quotation` where substr(`quotation_no`,3,2) = " . $year . " and substr(`quotation_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $quotation_no = 'qo' . $year . $month . $str;


    }

    public static function get_unique_no_inv($year, $month)
    {

        $quotation_no = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`inv_no`,7,length(substr(`inv_no`,3))-3),signed integer)) reg
        from `invoice` where substr(`inv_no`,3,2) = " . $year . " and substr(`inv_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $quotation_no = 'in' . $year . $month . $str;


    }

    public static function get_unique_no_job_order($year, $month)
    {

        $quotation_no = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`job_order_no`,7,length(substr(`job_order_no`,3))-3),signed integer)) reg
        from `job_order` where substr(`job_order_no`,3,2) = " . $year . " and substr(`job_order_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $job_order_no = 'jo' . $year . $month . $str;


    }
    public static  function get_all_customer()
    {
        $customer=new Customer();
        $customer=$customer->SetConnection('mysql2');
        return $customer=$customer->select('id','name','cnic_ntn','strn','terms_of_payment')->get();
    }

    public static  function get_all_client()
    {
        $client=new Client();
        $client=$client->SetConnection('mysql2');
        return $client=$client->select('id','client_name','ntn','strn','address','status','username')->where('status',1)->get();
    }

    public static  function get_total_amount_for_sales_order_by_id($id)
    {

        return    DB::Connection('mysql2')->selectOne('select sum(b.amount)amount, sum(b.qty)qty,sum(c.amount)dn_amount,a.total_amount_after_sale_tax
        from sales_order a
        inner join
        sales_order_data b
        on
        a.id=b.master_id
        left join
        delivery_note_data c
        on
        c.so_data_id=b.id
        and c.status=1
        where b.master_id="'.$id.'"
        and a.status=1
        and b.status=1
        group by b.master_id
        ');

       //   $data=DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty from sales_order_data where master_id="'.$id.'"');

    }



    public static function get_so_amount($id)
    {
        return    DB::Connection('mysql2')->selectOne('select sum(b.amount)amount, sum(b.qty)qty,a.sales_tax_rate
        from sales_order a
        inner join
        sales_order_data b
        on
        a.id=b.master_id

        where b.master_id="'.$id.'"
        and a.status=1
        and b.status=1

        ');

    }



    public static function get_dn_amount_by_so_id($id)
    {
        return    DB::Connection('mysql2')->selectOne('select sum(b.qty)qty
        from delivery_note a
        inner join
        delivery_note_data b
        on
        a.id=b.master_id

        where b.so_id="'.$id.'"
        and a.status=1
        and b.status=1

        ');

    }
    public static  function get_total_amount_for_dn_by_id($id)
    {

        return    DB::Connection('mysql2')->selectOne('select sum(b.amount)amount, sum(b.qty)qty,sum(c.amount)dn_amount,a.sales_tax_amount
        from delivery_note a
        inner join
        delivery_note_data b
        on
        a.id=b.master_id
        left join
        sales_tax_invoice_data c
        on
        c.so_data_id=b.so_data_id
        and c.status=1
        where b.master_id="'.$id.'"
        and a.status=1
        and b.status=1
        ');

        //   $data=DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty from sales_order_data where master_id="'.$id.'"');

    }

    public static  function getTotalAmountSalesTaxInvoice($id)
    {


        $data=    DB::Connection('mysql2')->selectOne('select (sum(b.amount)+a.sales_tax+a.sales_tax_further)total,sum(b.qty)qty,sum(b.amount)amount
        from sales_tax_invoice a
        inner join
        sales_tax_invoice_data b
        on
        a.id=b.master_id

        where a.id="'.$id.'"
        and a.status=1
        group by a.id
        ');


      return $data;
     //   return  $data=DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty from sales_tax_invoice_data where master_id="'.$id.'"');

    }

    public static function get_freight($id)
    {
      return  DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id',$id)->where('status',1)->sum('amount');
    }

    public static  function get_total_amount_for_delivery_not_by_id($id)
    {
        return  $data=DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty from delivery_note_data where master_id="'.$id.'"
        and status=1');

    }
    public static  function get_total_amount_for_sales_tax_invoice_by_id($id)
    {
        return  $data=DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty,dn_data_ids,sum(tax_amount)tax_amount from sales_tax_invoice_data where master_id="'.$id.'"
        and status=1');

    }



    public static  function get_total_amount_for_credit_note_by_id($id)
    {
        return  $data=DB::Connection('mysql2')->selectOne('select sum(net_amount)amount,sum(qty)qty from credit_note_data where master_id="'.$id.'" and status =1');

    }

    public static  function get_return($voucher_no,$type)
    {
        return  $data=DB::Connection('mysql2')->selectOne('select sum(net_amount)amount,sum(qty)qty from credit_note_data where voucher_no="'.$voucher_no.'" and type="'.$type.'" and status =1');

    }

    public static function get_sales_tax_by_sales_order_id($id)
    {
        $sales_order=new Sales_Order();
        $sales_order=$sales_order->SetConnection('mysql2');
        return  $sales_order=$sales_order->where('id',$id)->select('sales_tax_rate','sales_tax_further','buyers_unit')->first();
        // return  $sales_order=$sales_order->where('id',$id)->select('sales_tax','sales_tax_further','buyers_unit','other_refrence')->first();
    }

    public static  function get_client_by_id($id)
    {
        $client=new Client();
        $client=$client->SetConnection('mysql2');
        return $client=$client->select('id','acc_id','client_name','ntn','strn')->where('status',1)->where('id',$id)->first();
    }

    public static function get_invoice_number_amount($id)
    {


        return  DB::Connection('mysql2')->selectOne('select a.inv_no, a.branch_id, a.client_ref, a.bill_to_client_id,b.net_value as  total_amount from invoice a
                inner join
                invoice_data_totals b
                ON
                a.id=b.master_id
                where a.id="'.$id.'"
                ');
    }

    public static function generateCustomerCode(){
        $customerDetail = DB::Connection('mysql2')->selectOne('SELECT MAX(customer_code)+1 as customerCode FROM customers');
        return $customerDetail->customerCode??1;
    }

    public static function priviousReceiptSummaryAgainstSO($id){
        return DB::Connection('mysql2')->select('SELECT * FROM `new_rvs` as a INNER JOIN new_rv_data as b on a.rv_no = b.rv_no WHERE a.buyer_acc_id = b.acc_id and a.so_id = '.$id.'');
    }

    public static function get_received_payment($id)
    {
        return DB::Connection('mysql2')->selectOne('select sum(received_amount)amount from  received_paymet where sales_tax_invoice_id='.$id.' and status = 1')->amount;
    }

    public static function get_received_payment_for_pos($id)
    {
        return DB::Connection('mysql2')->selectOne('select sum(received_amount)amount from  received_paymet where pos_id='.$id.' and status = 1')->amount;
    }
    public static function get_data_from_invoice_data($master_id, $type)
    {
        if ($type == 1):
            return $users = DB::Connection('mysql2')->table('delivery_note as a')
                ->join('delivery_note_data as b', 'a.id', '=', 'b.master_id')
                ->select('b.id', 'b.master_id', 'b.qty', 'b.rate', 'b.amount', 'b.tax', 'b.tax_amount', 'b.item_id', 'a.sales_tax_invoice_id')
                ->where('a.status', 1)
                ->where('b.master_id', $master_id)
                ->get();
        else:
            $sales_tax_invoice_data = new SalesTaxInvoiceData();
            $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');
            return $sales_tax_invoice_data = $sales_tax_invoice_data->where('master_id', $master_id)->get();
        endif;
    }

    public static function generateCreditNotNo($year,$month)
    {


        $purchaseRequestNo = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`cr_no`,7,length(substr(`cr_no`,3))-3),signed integer)) reg
        from `credit_note` where substr(`cr_no`,3,2) = " . $year . " and substr(`cr_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $purchaseRequestNo = 'cr' . $year . $month . $str;

    }

    public static function get_data_from_invoice($id, $type)
    {
        if ($type == 1):
            return $acc_id = DB::Connection('mysql2')->table('delivery_note_data as a')
                ->join('delivery_note as b', 'a.master_id', '=', 'b.id')
                ->select(
                    'a.gd_no as gi_no',
                    'b.gd_date as gi_date',
                    'a.qty',
                    'a.rate',
                    'a.tax',
                    'a.tax_amount',
                    'a.amount',
                    'a.item_id',
                    'a.so_id',
                    'a.warehouse_id',
                    'a.so_data_id',
                    'a.gd_no'
                )
                ->where('a.id', $id)
                ->first();

        else:

            return $acc_id = DB::Connection('mysql2')->table('sales_tax_invoice_data as a')
                ->join('sales_tax_invoice as b', 'a.master_id', '=', 'b.id')
                ->select('b.gi_no', 'a.id', 'b.gi_date', 'a.qty', 'a.rate', 'a.tax', 'a.tax_amount', 'a.amount', 'a.item_id', 'a.so_id', 'a.warehouse_id', 'a.so_data_id')
                ->where('a.id', $id)
                ->first();
        endif;
    }

    public static function get_dn_total_qty($id)
    {
       return $purchases = DB::Connection('mysql2')->table('delivery_note_data')
            ->where('so_data_id', '=', $id)
            ->where('status',1)
            ->sum('qty');
    }

    public  static function return_qty($type,$id,$voucher_no)
    {

        if ($type==1):

           return DB::Connection('mysql2')->table('credit_note_data')->where('status',1)
                ->where('so_data_id',$id)

                ->sum('qty');


            else:

     return   DB::Connection('mysql2')->table('credit_note_data')->where('status',1)
            ->where('type',$type)
            ->where('voucher_data_id',$voucher_no)
         ->where('voucher_no',$id)
             ->sum('qty');
                endif;
    }
    public  static function return_qty_for_dn($type,$id)
    {

        if ($type==1):

            return DB::Connection('mysql2')->table('credit_note_data')->where('status',1)
                ->where('so_data_id',$id)

                ->sum('qty');


        else:
            return   DB::Connection('mysql2')->table('credit_note_data')->where('status',1)
                ->where('type',$type)

                ->where('voucher_no',$id)
                ->sum('qty');
        endif;
    }

    public static function get_batch_code($id,$type)
    {
        if ($type==1):
      return    $purchases = DB::Connection('mysql2')->table('delivery_note_data')
            ->where('id',  $id)
            ->where('status',1)
            ->select('batch_code')
            ->first();


        else:

     return   $purchases = DB::Connection('mysql2')->table('delivery_note_data')
            ->where('so_data_id',  $id)
            ->where('status',1)
            ->select('batch_code')
            ->first();

        endif;
    }

    public static function dn_qty($so_data_id,$dn_ids)
    {
        return  DB::Connection('mysql2')->selectOne('select sum(qty)qty,rate,tax from delivery_note_data where status=1
            and so_data_id="'.$so_data_id.'"
            and master_id in ('.$dn_ids.')');
    }
    public static function dp_qty($so_data_id,$dn_ids)
    {
        return  DB::Connection('mysql2')->selectOne('select sum(qty)qty,rate from dispatch_datas where status=1
            
            and dispatch_id in ('.$dn_ids.')');
    }

    public static function get_customer_acc_id($id)
    {
        return DB::Connection('mysql2')->table('customers')->where('id',$id)->select('acc_id')->first()->acc_id;
    }

    public static function get_customer_name($id)
    {
        return DB::Connection('mysql2')->table('customers')->where('id',$id)->select('name')->first()->name;
    }

    public static function get_sales_detail_for_receipt($id)
    {
        $data= DB::Connection('mysql2')->selectOne('select a.gi_no,a.sc_no,c.so_no,(sum(b.amount)+a.sales_tax+a.sales_tax_further) invoice_amount,a.gi_no,a.so_id,a.buyers_id,a.so_type,a.description,sum(b.amount)as old_amount , b.tax_amount , c.sales_tax_group, b.sales_tax_further, a.advance_tax_amount, a.cartage_amount from sales_tax_invoice as  a
        inner join
        sales_tax_invoice_data as b
        on
        a.id=b.master_id

        left join
        sales_order c
        on
        a.so_id=c.id
        where
         a.status=1
        and a.id="'.$id.'"
        ');

      return $data;

    }

    public static function get_sales_return_from_sales_tax_invoice($id)
    {

      return  DB::Connection('mysql2')->selectOne('select (sum(a.net_amount)+c.sales_tax+c.sales_tax_further)amount from credit_note_data a

        inner join
        credit_note c
        on
        a.master_id=c.id

        inner join
        sales_tax_invoice_data b
        on
        a.voucher_data_id=b.id
        where a.status=1
        and a.type=2
        and b.master_id="'.$id.'"')->amount;
    }

    public static function get_sales_return_from_sales_tax_invoice_by_date($id,$from,$to)
    {

        return  DB::Connection('mysql2')->selectOne('select (sum(a.net_amount)+c.sales_tax+c.sales_tax_further)amount from credit_note_data a
        inner join
        credit_note c
        on
        a.master_id=c.id

        inner join
        sales_tax_invoice_data b
        on
        a.voucher_data_id=b.id
        where a.status=1
        and a.type=2
        and c.cr_date between "'.$from.'" and "'.$to.'"
        and b.master_id="'.$id.'"')->amount;
    }

    public static function get_sales_return_dn($id)
    {
        return  DB::Connection('mysql2')->selectOne('select (sum(a.net_amount)+c.sales_tax+c.sales_tax_further)amount from credit_note_data a

        inner join
        credit_note c
        on
        a.master_id=c.id

        inner join
        delivery_note_data b
        on
        a.voucher_data_id=b.id
        where a.status=1
        and a.type=1
        and b.master_id="'.$id.'"')->amount;
    }

    public static function get_sales_inv_amount($id)
    {
        return  DB::Connection('mysql2')->selectOne('select (sum(a.amount)+c.sales_tax+c.sales_tax_further) amount from sales_tax_invoice_data a
        inner join
        sales_tax_invoice c
        on
        a.master_id=c.id
        where a.status=1
        and c.so_id="'.$id.'"')->amount;
    }

    public static function get_buyer_opening_by_buyer_id($id)
    {
        return DB::Connection('mysql2')->table('customer_opening_balance')->where('buyer_id',$id)->get();
    }

    public static function get_received_data($id)
    {
       return DB::Connection('mysql2')->selectOne('select sum(net_amount)net_amount,sum(tax_amount)tax_amount from  brige_table_sales_receipt
        where rv_id="'.$id.'"');
    }

    public static function get_received_amount($id)
    {
        return DB::Connection('mysql2')->selectOne('select sum(received_amount)net_amount
        from  brige_table_sales_receipt as a
        inner join
        new_rvs b
        on
        a.rv_id=b.id
        where a.si_id="'.$id.'"
        and b.status=1')->net_amount;
    }

    public static  function get_total_amount_for_invoice_by_id($id)
    {

        return    DB::Connection('mysql2')->selectOne('select sum(b.amount)amount,sum(c.amount)addition_amount,a.sales_tax
        from  sales_tax_invoice a
        inner join
        sales_tax_invoice_data b
        on
        a.id=b.master_id
        left join
        addional_expense c
        on
        c.main_id=b.master_id
        and c.status=1
        where b.master_id="'.$id.'"
        and a.status=1
        and b.status=1
        ');

        //   $data=DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty from sales_order_data where master_id="'.$id.'"');

    }


    public static  function get_total_amount_si_status($id)
    {

        return    DB::Connection('mysql2')->selectOne('select sum(b.amount)amount,sum(c.amount)addition_amount,a.sales_tax,sum(d.received_amount) as received_amount
        from  sales_tax_invoice a
        inner join
        sales_tax_invoice_data b
        on
        a.id=b.master_id
        left join
        addional_expense c
        on
        c.main_id=b.master_id
        and c.status=1
        left join
        received_paymet d
        on
        d.sales_tax_invoice_id=a.id
        and d.status=1
        where b.master_id="'.$id.'"
        and a.status=1
        and b.status=1
        ');

        //   $data=DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty from sales_order_data where master_id="'.$id.'"');

    }

    public static function uniqe_no_for_pos($year, $month)
    {
        $variable = 100;
         $str = DB::Connection('mysql2')->selectOne("SELECT MAX(SUBSTR(pos_no, 8, 3)) AS ExtractString
        FROM pos  where substr(`pos_no`,4,2) = " . $year . " and substr(`pos_no`,6,2) = " . $month . "")->ExtractString;

        $str = $str + 1;
        $str = sprintf("%'03d", $str);

            $pv_no = 'pos' . $year . $month . $str;

        return $pv_no;
    }


    public static function get_pos_qty_amount($id,$type)
    {
      return  DB::Connection('mysql2')->selectOne('
      select sum(qty)qty,sum(amount)amount,sum(discount_amount)discount_amount
      from pos_data
      where status=1
      and master_id="'.$id.'"
      and additional_exp="'.$type.'"');
    }

    public static function get_pos_detail($id)
    {
       return DB::Connection('mysql2')->selectOne('select a.pos_no,sum(b.net_amount)amount from pos a
        inner join
        pos_data  b
        on
        a.id=b.master_id
        where a.status=1
        and a.id="'.$id.'"');
    }


    public static function get_return_data_against_pos($pos_id)
    {
      return  DB::Connection('mysql2')->selectOne('select sum(b.qty)qty,sum(b.net_amount)net_amount
        from credit_note as a
        inner join
        credit_note_data b
        on
        a.id=b.master_id
        where a.status=1
        and a.pos_id="'.$pos_id.'"');
    }


    public static function get_return_by_dn($dn_no)
    {
        return DB::Connection('mysql2')->selectOne('select sum(qty)qty,sum(net_amount)amount
        from credit_note a
        inner join
        credit_note_data b
        on
        a.id=b.master_id
        where a.status=1
        and b.voucher_no="'.$dn_no.'"');
    }


public static function approval_status($status)
{

    $approve = 'Pending';


           if($status==2):
                $approve = '1st Approved';
                elseif($status==3):
                    $approve ='Approved';

                    elseif($status==4):
                        $approve =   'Approved';

                        elseif($status==1):
                            $approve =   'Approved';

                    endif;
                    return $approve;
}

public static function si_status($status)
{
    $approve = 'Pending';


    if($status==2):
         $approve = '1st Approved';
         elseif($status==3):
             $approve ='Approved';

         endif;
             return $approve;
}

public static function sale_order_qty($id)
{
  $response =  FacadesDB::connection('mysql2')
    ->table('sales_order_data')
    ->leftJoin('delivery_note_data', function ($join) {
        $join->on('delivery_note_data.so_data_id', '=', 'sales_order_data.id');
    })
    ->select('sales_order_data.item_id', 
    FacadesDB::raw('SUM(sales_order_data.qty) - IFNULL(SUM(delivery_note_data.qty), 0) as quantity_difference'))
    ->groupBy('sales_order_data.item_id')
    ->where('sales_order_data.item_id',$id)
    ->first();
    if(!empty($response))
    {
      return  $response->quantity_difference;
    }else{
        return 0;
    }

    
   return $purchases = FacadesDB::Connection('mysql2')->table('delivery_note_data')
        ->where('so_data_id', '=', $id)
        ->where('status',1)
        ->sum('qty');
}
}
?>
