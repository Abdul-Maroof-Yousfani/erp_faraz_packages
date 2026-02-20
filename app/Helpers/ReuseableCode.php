<?php
namespace App\Helpers;
use DB;
use Auth;
use Request;
use Config;
use Input;
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
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Region;
use App\Models\SurveryBy;
use App\Models\Client;
use App\Models\Type;
use App\Models\ProductType;
use App\Models\Cities;
use App\Models\ResourceAssigned;
use App\Models\ClientJob;
use App\Models\IncomeTaxDeduction;
use App\Models\NewPvData;
use App\Models\JobOrder;
use App\Models\JobOrderData;
use App\Models\Estimate;
use App\Models\NewPurchaseVoucherPayment;
use App\Models\NewPurchaseVoucher;
use App\Models\PaidTo;
use App\Models\Stock;
use App\Models\Emp;
use App\Models\Branch;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Models\NewPurchaseVoucherData;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;
use App\Helpers\SalesHelper;
use Illuminate\Support\Facades\Storage;
use Session;

class ReuseableCode
{

    public static function already_adjust_amount_fetch($supplier, $pv_id)
    {
        return DB::Connection('mysql2')->select('
        select * from  new_purchase_voucher_payment
        where status=1
        and supplier_id="' . $supplier . '"
        and new_purchase_voucher_id="' . $pv_id . '"');
    }

    public static function get_purchased_amount($id)
    {
        return DB::Connection('mysql2')->selectOne('
        select sum(amount)amount from  new_purchase_voucher_data
        where staus=1
        and master_id="' . $id . '"')->amount;
    }

    public static function pget_tax($id = null)
    {
        if (!empty($id)):
            $clause = "and acc_id ='$id'";
        else:
            $clause = '';
        endif;


        $data = DB::Connection('mysql2')->select('select a.acc_id,a.name,a.tax_rate from  invoice_tax a
                                                    inner join
                                                    accounts b
                                                    ON
                                                    a.acc_id=b.id
                                                    where b.status=1
                                                    ' . $clause . '');


        return $data;
    }

    public static function get_inv_totals($id)
    {
        return $data = DB::Connection('mysql2')->table('invoice_data_totals')->where('master_id', $id)->first();
    }


    public static function get_sum_stock($item, $from, $to)
    {

        $data = [];
        $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty from stock
        where status=1 and voucher_date BETWEEN "' . $from . '" and "' . $to . '" and sub_item_id="' . $item . '" and voucher_type in (1,3) and opening=0');


        $purchase_qty = $in->qty;

        $purchase_amount = $in->amount;
        ;

        $out = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty from stock
        where status=1 and voucher_date BETWEEN "' . $from . '" and "' . $to . '" and sub_item_id="' . $item . '" and voucher_type in (2) and opening=0');

        $issued_qty = $out->qty;
        $issuede_amount = $out->amount;

        $final_qty = $purchase_qty - $issued_qty;

        //        if ($final_qty<0):
//            $final_qty=$final_qty*-1;
//            endif;

        $final_amount = $purchase_amount - $issuede_amount;

        //        if ($final_amount<0):
//            $final_amount=$final_amount*-1;
//        endif;



        $data[0] = $final_qty;
        $data[1] = $final_amount;

        return $data;
    }


    public static function get_opening_stock($item, $from, $to)
    {
        $data = [];
        if ($from == '2020-07-01'):
            $stock = DB::Connection('mysql2')->selectOne('select sum(COALESCE(qty,0))qty,sum(COALESCE(amount,0))amount ,batch_code from stock where status=1 and sub_item_id="' . $item . '"
            and opening=1');
            $data[0] = $stock->qty;
            $data[1] = $stock->amount;
            $data[2] = $stock->batch_code;
        endif;

        return $data;


    }


    public static function opening_stock($sub_item_id, $warehouse)
    {
        return Stock::where('status', 1)->where('opening', 1)->where('sub_item_id', $sub_item_id)->where('warehouse_id', $warehouse)->first();
    }
    public static function get_amount_voucher_wise($from, $to, $item, $transfer_status, $voucher_type)
    {
        if ($voucher_type == 1):
            return $data = DB::Connection('mysql2')->selectOne('select sum(qty)qty,sum(amount)amount from stock
        where status=1
        and voucher_date BETWEEN "' . $from . '" and "' . $to . '"
        and sub_item_id="' . $item . '"
        and voucher_type="' . $voucher_type . '"
        and transfer="' . $transfer_status . '"');
        else:
            return $data = DB::Connection('mysql2')->selectOne('select sum(qty)qty from stock
        where status=1
        and voucher_date BETWEEN "' . $from . '" and "' . $to . '"
        and sub_item_id="' . $item . '"
        and voucher_type="' . $voucher_type . '"
        and transfer="' . $transfer_status . '"');
        endif;


    }
    public static function average_cost($item_id, $warehouse)
    {
        return DB::Connection('mysql2')->selectOne('select (sum(amount)/sum(qty)) as avergae_val from stock
        where status=1 and voucher_type=1 and sub_item_id="' . $item_id . '" and warehouse_id="' . $warehouse . '"')->avergae_val;
    }


    public static function average_cost_sales($item_id, $warehouse, $batch_code)
    {
        $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (1,4,6,10,11) and sub_item_id="' . $item_id . '" and warehouse_id="' . $warehouse . '" and batch_code="' . $batch_code . '"');

        $purchase_amount = $in->amount;
        $purchase_qty = $in->qty;

        $out = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (2,5,3,9) and sub_item_id="' . $item_id . '" and warehouse_id="' . $warehouse . '" and batch_code="' . $batch_code . '"');

        $sales_amount = $out->amount;
        $saless_qty = $out->qty;

        $qty = $purchase_qty - $saless_qty;
        $amount = $purchase_amount - $sales_amount;

        if ($qty != 0):
            return $average_cost = $amount / $qty;
        else:
            return 0;
        endif;
    }
    public static function get_stock($item_id,$warehouse_id,$qty,$batch_code)
    {
        $warehouse= $warehouse_id;
        $item= $item_id;

         $in= DB::Connection('mysql2')->table('stock')->whereIn('status',array(1,3))
            ->whereIn('voucher_type',[1,4,6,10,11])
            ->where('sub_item_id',$item)
            // ->where('warehouse_id',$warehouse)
            //  ->where('batch_code',$batch_code)
            ->select(DB::raw('SUM(qty) As qty'),DB::raw('SUM(amount) As amount'))
            ->first();

        $oout=  DB::Connection('mysql2')->table('stock')->whereIn('status',array(1,3))
            ->whereIn('voucher_type',[2,5,3,9])
            ->where('sub_item_id',$item)
            // ->where('batch_code',$batch_code)
            // ->where('warehouse_id',$warehouse)
            ->select(DB::raw('SUM(qty) As qty'),DB::raw('SUM(amount) As amount'))
            ->first();
            $out=$oout->qty+$qty;
            return  $in->qty-$out;

    }

    public static function get_stock_with_pack_size($item_id, $warehouse_id, $qty, $batch_code)
    {
        $warehouse = $warehouse_id;
        $item = $item_id;

        // STOCK IN
        $in = DB::connection('mysql2')->table('stock')
            ->leftJoin('subitem', 'subitem.id', '=', 'stock.sub_item_id')
            ->whereIn('stock.status', [1, 3])
            ->whereIn('stock.voucher_type', [1, 4, 6, 10, 11])
            ->where('stock.sub_item_id', $item)
            // ->where('stock.warehouse_id', $warehouse)
            // ->where('stock.batch_code', $batch_code)
            ->select(DB::raw('
            SUM(
                CASE 
                    WHEN subitem.pack_size IS NOT NULL AND subitem.pack_size > 0 
                    THEN stock.qty * subitem.pack_size
                    ELSE stock.qty
                END
            ) as qty
        '))
            ->first();

        // STOCK OUT
        $oout = DB::connection('mysql2')->table('stock')
            ->leftJoin('subitem', 'subitem.id', '=', 'stock.sub_item_id')
            ->whereIn('stock.status', [1, 3])
            ->whereIn('stock.voucher_type', [2, 5, 3, 9])
            ->where('stock.sub_item_id', $item)
            // ->where('stock.warehouse_id', $warehouse)
            // ->where('stock.batch_code', $batch_code)
            ->select(DB::raw('
            SUM(
                CASE 
                    WHEN subitem.pack_size IS NOT NULL AND subitem.pack_size > 0 
                    THEN stock.qty * subitem.pack_size
                    ELSE stock.qty
                END
            ) as qty
        '))
            ->first();

        $out = ($oout->qty ?? 0) + $qty;

        return ($in->qty ?? 0) - $out;
    }

    public static function get_vendor_info($id)
    {
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        return $supplier = $supplier->where('id', $id)->first();
    }

    public static function purchase_return_qty($id)
    {
        return DB::Connection('mysql2')->table('purchase_return_data')->where('grn_data_id', $id)->where('status', 1)->sum('return_qty');
    }


    public static function get_grn_additional_exp($id)
    {
        return DB::Connection('mysql2')->table('addional_expense')->where('main_id', $id)->where('status', 1)->get();
    }


    public static function get_invoice_tax()
    {
        return DB::Connection('mysql2')->table('invoice_tax')->where('status', 1)->get();
    }

    public static function check_rights($id)
    {
        $validate = true;

        if (Session::get('run_company') == 1 || Session::get('run_company') == 4):
            $crud = Auth::user()->crud_rights;
        elseif (Session::get('run_company') == 2):
            $crud = Auth::user()->crud_rights_2;
        elseif (Session::get('run_company') == 3):
            $crud = Auth::user()->crud_rights_3;
        elseif (Session::get('run_company') == 5):
            $crud = Auth::user()->crud_rights_4;
        elseif (Session::get('run_company') == 6):
            $crud = Auth::user()->crud_rights_5;
        endif;


        $crud = explode(',', $crud);
        $account_typ = Auth::user()->acc_type;
        if ($account_typ == 'user' && (!in_array($id, $crud))):
            $validate = false;
        endif;
        return $validate;
    }
    public static function get_vendor_opening_by_vendor_id($id)
    {
        return DB::Connection('mysql2')->table('vendor_opening_balance')->where('vendor_id', $id)->get();
    }


    public static function hit_ledger_vendor_opening($id)
    {
        $acc_id = CommonHelper::get_supplier_acc_id($id);


        $vendor_oprning_data = DB::Connection('mysql2')->table('vendor_opening_balance')->where('vendor_id', $id)->
            select(DB::raw('sum(balance_amount) as bal'), DB::raw('sum(invoice_amount) as invoice_amount'))->first();


        $amount = $vendor_oprning_data->invoice_amount - $vendor_oprning_data->bal;
        if ($amount >= 0):
            $debit_credit = 0;
        else:
            $debit_credit = 1;
        endif;
        $count = DB::Connection('mysql2')->table('transactions')->where('acc_id', $acc_id)->where('opening_bal', 1)->count();

        $data = array
        (
            'acc_id' => $acc_id,
            'acc_code' => FinanceHelper::getAccountCodeByAccId($acc_id),
            'particulars' => 'opening bal',
            'opening_bal' => 1,
            'debit_credit' => $debit_credit,
            'amount' => $vendor_oprning_data->bal,
            'username' => 'Amir Murshad@',
            'status' => 1,
        );

        if ($count > 0):
            DB::Connection('mysql2')->table('transactions')->where('acc_id', $acc_id)->where('opening_bal', 1)->update($data);
        else:
            DB::Connection('mysql2')->table('transactions')->insert($data);
        endif;

    }

    public static function insert_old_so($so_code)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {


            $data = DB::Connection('mysql2')->table('TABLE 188')->where('COL_2', $so_code)->groupBy('COL_2')->first();

            $so_no = SalesHelper::get_unique_no(date('y'), date('m'));
            $sales_order = new Sales_Order();
            $sales_order = $sales_order->SetConnection('mysql2');
            $sales_order->buyers_unit = '-';




            $cutomer = DB::Connection('mysql2')->table('customers')->where('name', $data->COL_4)->select('id', 'terms_of_payment');


            if ($cutomer->count() > 0):
                $terms_of_payment = $cutomer->first()->terms_of_payment;
                $customer_id = $cutomer->first()->id;

            else:
                $terms_of_payment = 0;
                $customer_id = 0;
            endif;

            $sales_order->so_no = $so_no;
            $sales_order->model_terms_of_payment = $terms_of_payment;
            $sales_order->order_no = $data->COL_5;
            $sales_order->so_date = $data->COL_1;
            $sales_order->description = $so_code;
            $sales_order->order_date = $data->COL_1;
            $sales_order->other_refrence = '-';
            $sales_order->desptch_through = '-';
            $sales_order->destination = $data->COL_1;
            $sales_order->terms_of_delivery = $customer_id;
            $sales_order->due_date = $data->COL_1;
            $sales_order->status = 1;
            $sales_order->username = 'nawaz';
            $sales_order->so_type = 1;
            $sales_order->date = date('Y-m-d');
            $sales_order->buyers_id = $customer_id;

            $sales_order->save();
            $master_id = $sales_order->id;


            $data1 = DB::Connection('mysql2')->table('TABLE 188')->where('COL_2', $so_code)->get();
            $counter = 1;
            $sales_tax_amount = 0;

            foreach ($data1 as $row1):

                $sales_order = new Sales_Order_Data();
                $sales_order = $sales_order->SetConnection('mysql2');

                $sales_order->master_id = $master_id;
                $sales_order->so_no = $so_no;


                $item_data = DB::Connection('mysql2')->table('subitem')->where('status', 1)->where('item_code', $row1->COL_6);
                if ($item_data->count() > 0):
                    $item = $item_data->first()->id;
                else:
                    $item = 0;
                endif;

                $sales_order->item_id = $item;
                $sales_order->master_id = $master_id;
                $sales_order->desc = $row1->COL_7;
                $sales_order->qty = $row1->COL_8;
                $sales_order->rate = $row1->COL_9;
                $sales_order->sub_total = $row1->COL_10;
                $sales_order->discount = 0;
                $sales_order->discount_amount = 0;
                $sales_order->amount = $row1->COL_10;

                $sales_order->status = 1;
                $sales_order->date = date('Y-m-d');
                $sales_order->username = 'nawaz';
                $sales_order->so_type = 1;
                $sales_order->groupby = $counter;

                $sales_tax_amount += $row1->COL_11;

                $sales_order->save();
                $counter++;
            endforeach;

            if ($sales_tax_amount > 0):
                $sales_order = new Sales_Order();
                $sales_order = $sales_order->SetConnection('mysql2');
                $sales_order = $sales_order->find($master_id);
                $sales_order->sales_tax = $sales_tax_amount;
                $sales_order->save();
            endif;

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();

        }

    }


    public static function insert_pv($pv_no)
    {
        $data = DB::Connection('mysql2')->table('vendor_opening_balance')->where('pi_no', $pv_no)->first();


        $count_data = DB::Connection('mysql2')->table('new_purchase_voucher')->where('slip_no', $data->pi_no);

        $pv_no = CommonHelper::uniqe_no_for_purcahseVoucher(date('y'), date('m'));
        $NewPurchaseVoucher = new NewPurchaseVoucher();
        $NewPurchaseVoucher = $NewPurchaseVoucher->SetConnection('mysql2');

        if ($count_data->count() > 0):
            $NewPurchaseVoucher = $NewPurchaseVoucher->find($count_data->first()->id);
        endif;

        $NewPurchaseVoucher->pv_no = $pv_no;
        $NewPurchaseVoucher->pv_date = $data->date;
        $NewPurchaseVoucher->grn_no = '';
        $NewPurchaseVoucher->grn_id = '';
        $NewPurchaseVoucher->slip_no = $data->pi_no;
        $NewPurchaseVoucher->bill_date = $data->date;
        $NewPurchaseVoucher->due_date = date('Y-m-d', strtotime($data->date . ' + 60 days'));
        ;
        $NewPurchaseVoucher->purchase_type = 1;
        $NewPurchaseVoucher->supplier = $data->vendor_id;
        $NewPurchaseVoucher->description = $data->pi_no . '||' . $data->po_no;
        $NewPurchaseVoucher->username = 'Amir Murshad@';
        $NewPurchaseVoucher->status = 1;
        $NewPurchaseVoucher->pv_status = 2;
        $NewPurchaseVoucher->date = date('Y-m-d');
        $NewPurchaseVoucher->save();
        $master_id = $NewPurchaseVoucher->id;

        $count_data = DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('slip_no', $data->pi_no);
        $NewPurchaseVoucherData = new NewPurchaseVoucherData();
        $NewPurchaseVoucherData = $NewPurchaseVoucherData->SetConnection('mysql2');
        if ($count_data->count() > 0):
            $NewPurchaseVoucherData = $NewPurchaseVoucherData->find($count_data->first()->id);
        endif;

        $NewPurchaseVoucherData->master_id = $master_id;
        $NewPurchaseVoucherData->pv_no = $pv_no;
        $NewPurchaseVoucherData->slip_no = $data->pi_no;
        $NewPurchaseVoucherData->grn_data_id = '';
        $NewPurchaseVoucherData->category_id = 0;
        $NewPurchaseVoucherData->sub_item = 0;
        $NewPurchaseVoucherData->uom = 0;
        $NewPurchaseVoucherData->qty = 0;
        $NewPurchaseVoucherData->rate = 0;
        $NewPurchaseVoucherData->amount = $data->balance_amount;
        $NewPurchaseVoucherData->discount_amount = 0;
        $NewPurchaseVoucherData->net_amount = $data->balance_amount;
        $NewPurchaseVoucherData->staus = 1;
        $NewPurchaseVoucherData->pv_status = 2;
        $NewPurchaseVoucherData->username = 'Amir Murshad@';
        $NewPurchaseVoucherData->date = date('Y-m-d');
        $NewPurchaseVoucherData->save();
    }



    public static function insert_si($si_no)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {
            $data = DB::Connection('mysql2')->table('customer_opening_balance')->where('si_no', $si_no)->first();


            $count_data = DB::Connection('mysql2')->table('sales_tax_invoice')->where('other_refrence', $data->si_no);

            $sales_tax_invoice = new SalesTaxInvoice();
            $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');

            if ($count_data->count() > 0):
                $sales_tax_invoice = $sales_tax_invoice->find($count_data->first()->id);
            endif;


            $gi_no = SalesHelper::get_unique_no_sales_tax_invoice(date('y'), date('m'));
            $sales_tax_invoice->gi_no = $gi_no;
            $sales_tax_invoice->gi_date = $data->date;

            $sales_tax_invoice->so_id = 0;

            $cutomer = DB::Connection('mysql2')->table('customers')->where('id', $data->buyer_id)->select('id', 'terms_of_payment');
            $sales_tax_invoice->model_terms_of_payment = $cutomer->first()->terms_of_payment;
            $sales_tax_invoice->order_date = $data->date;
            $sales_tax_invoice->other_refrence = $data->si_no;
            $sales_tax_invoice->despacth_document_no = $data->so_no;
            $sales_tax_invoice->despacth_document_date = $data->date;
            $sales_tax_invoice->despacth_through = '';
            $sales_tax_invoice->destination = '';
            $sales_tax_invoice->terms_of_delivery = 0;
            $sales_tax_invoice->due_date = date('Y-m-d', strtotime($data->date . ' + ' . $cutomer->first()->terms_of_payment . ' days'));
            $sales_tax_invoice->status = 1;
            $sales_tax_invoice->username = Auth::user()->name;
            $sales_tax_invoice->amount_in_words = '';
            $sales_tax_invoice->order_no = $data->so_no;
            $sales_tax_invoice->date = date('Y-m-d');
            $sales_tax_invoice->buyers_id = $cutomer->first()->id;
            $sales_tax_invoice->description = $data->si_no . '||' . $data->so_no;
            $sales_tax_data = 0;
            $sales_tax_invoice->sales_tax = 0;
            $sales_tax_invoice->sales_tax_further = 0;
            $sales_tax_invoice->acc_id = 0;
            $sales_tax_invoice->so_type = 1;
            $sales_tax_invoice->save();
            $id = $sales_tax_invoice->id;

            $count_data = DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('gd_no', $data->si_no);


            $sales_tax_invoice_data = new SalesTaxInvoiceData();
            $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');

            if ($count_data->count() > 0):
                $sales_tax_invoice_data = $sales_tax_invoice_data->find($count_data->first()->id);
            endif;
            $sales_tax_invoice_data->master_id = $id;
            $sales_tax_invoice_data->so_id = 0;

            $sales_tax_invoice_data->dn_data_ids = 0;
            $sales_tax_invoice_data->so_data_id = 0;

            $sales_tax_invoice_data->groupby = 1;
            // $sales_tax_invoice_data->gd_id = $request->delivery_note_id;
            $sales_tax_invoice_data->gi_no = $si_no;
            $sales_tax_invoice_data->so_no = $data->so_no;
            $sales_tax_invoice_data->gd_no = $data->si_no;


            $sales_tax_invoice_data->item_id = 0;

            $sales_tax_invoice_data->description = '';

            $qty = 0;
            $rate = 0;
            $amount = $data->balance_amount;
            $sales_tax_invoice_data->qty = 1;

            $sales_tax_invoice_data->rate = 1;
            $sales_tax_invoice_data->discount = 0;
            $sales_tax_invoice_data->discount_amount = 0;
            $sales_tax_invoice_data->amount = $amount;
            $sales_tax_invoice_data->warehouse_id = 0;
            $sales_tax_invoice_data->bundles_id = 0;
            $sales_tax_invoice_data->status = 1;
            $sales_tax_invoice_data->date = date('Y-m-d');
            $sales_tax_invoice_data->username = Auth::user()->name;
            $sales_tax_invoice_data->so_type = 1;
            $sales_tax_invoice_data->save();



            $acc_id = SalesHelper::get_customer_acc_id($cutomer->first()->id);


            $vendor_oprning_data = DB::Connection('mysql2')->table('customer_opening_balance')->where('buyer_id', $cutomer->first()->id)->
                select(DB::raw('sum(balance_amount) as bal'), DB::raw('sum(invoice_amount) as invoice_amount'))->first();


            $amount = $vendor_oprning_data->invoice_amount - $vendor_oprning_data->bal;
            if ($amount >= 0):
                $debit_credit = 1;
            else:
                $debit_credit = 0;
            endif;
            $count = DB::Connection('mysql2')->table('transactions')->where('acc_id', $acc_id)->where('opening_bal', 1)->count();

            $data = array
            (
                'acc_id' => $acc_id,
                'acc_code' => FinanceHelper::getAccountCodeByAccId($acc_id),
                'particulars' => 'opening bal customer',
                'opening_bal' => 1,
                'debit_credit' => $debit_credit,
                'amount' => $vendor_oprning_data->bal,
                'username' => 'Amir Murshad@',
                'status' => 1,
                'date' => date('Y-m-d'),
            );

            if ($count > 0):
                DB::Connection('mysql2')->table('transactions')->where('acc_id', $acc_id)->where('opening_bal', 1)->update($data);
            else:
                DB::Connection('mysql2')->table('transactions')->insert($data);
            endif;



            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();

        }

    }

    public static function get_po_total_amount($id)
    {
        if (is_array($id)) {
            return DB::Connection('mysql2')->table('purchase_request_data')->whereIn('master_id', $id)->sum('net_amount');

        } else {
            return DB::Connection('mysql2')->table('purchase_request_data')->where('master_id', $id)->sum('net_amount');
        }
    }

    public static function get_account_year_from_to($company_id)
    {
        CommonHelper::reconnectMasterDatabase();
        $financial_year = DB::table('company')->where('id', $company_id)->select('accyearfrom', 'accyearto')->first();
        $data[0] = $financial_year->accyearfrom;
        $data[1] = $financial_year->accyearto;
        return $data;
    }


    public static function get_opening($from, $to, $accyearfrom, $item_id, $open)
    {


        $data = [];

        $to = date('Y-m-d', strtotime('-1 day', strtotime($from)));
        if ($from == $accyearfrom):

            $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type=1 and opening=1 and sub_item_id="' . $item_id . '"');
            $data[0] = $in->qty;
            $data[1] = $in->amount;

        else:




            $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (1,4,6) and sub_item_id="' . $item_id . '" and voucher_date between "' . $accyearfrom . '" and "' . $to . '"');

            $purchase_amount = $in->amount;
            $purchase_qty = $in->qty;

            $out = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (5,2,3) and sub_item_id="' . $item_id . '" and voucher_date between "' . $accyearfrom . '" and "' . $to . '"');

            $sales_qty = $out->qty;
            $sales_amount = $out->amount;

            $data[0] = $purchase_qty - $sales_qty;
            $data[1] = $purchase_amount - $sales_amount;
        endif;


        return $data;
    }

    public static function get_opening_for_finance($from, $to, $accyearfrom, $open)
    {


        $data = [];

        $to = date('Y-m-d', strtotime('-1 day', strtotime($from)));
        if ($from == $accyearfrom):

            $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type=1 and opening=1');
            $data[0] = $in->qty;
            $data[1] = $in->amount;

        else:




            $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (1,4,6) and voucher_date between "' . $accyearfrom . '" and "' . $to . '"');

            $purchase_amount = $in->amount;
            $purchase_qty = $in->qty;

            $out = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (5,2,3)  and voucher_date between "' . $accyearfrom . '" and "' . $to . '"');

            $sales_qty = $out->qty;
            $sales_amount = $out->amount;

            $data[0] = $purchase_qty - $sales_qty;
            $data[1] = $purchase_amount - $sales_amount;
        endif;


        return $data;
    }

    public static function get_stock_type_wise($from, $to, $item_id, $type)
    {
        $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (' . $type . ') and sub_item_id="' . $item_id . '" and voucher_date between "' . $from . '" and "' . $to . '" and opening=0');

        $data[0] = $in->qty;
        $data[1] = $in->amount;

        return $data;
    }


    public static function get_stock_type_wise_finance($from, $to, $type)
    {
        $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (' . $type . ')  and voucher_date between "' . $from . '" and "' . $to . '" and opening=0');

        $data[0] = $in->qty;
        $data[1] = $in->amount;

        return $data;
    }


    public static function check_voucher($voucher)
    {
        $debit = DB::Connection('mysql2')->table('transactions')->where('voucher_no', $voucher)->where('debit_credit', 1)->where('status', 1)->sum('amount');
        $credit = DB::Connection('mysql2')->table('transactions')->where('voucher_no', $voucher)->where('debit_credit', 0)->where('status', 1)->sum('amount');

        return $debit - $credit;
    }

    public static function in_ledger_ceck($voucher_no, $table, $column)
    {
        $check = "Exists";
        $table_tr = DB::Connection('mysql2')->table($table)->where('status', 1)->where($column, $voucher_no)->sum('amount');
        $tra_tra = DB::Connection('mysql2')->table('transactions')->where('status', 1)->where('voucher_no', $voucher_no)->sum('amount');

        //    echo $table_tr.' *'.$tra_tra.' *'.$voucher_no;
        if ($table_tr != $tra_tra):
            $check = 'Not Exists';
        endif;
        return $check;

    }

    public static function invoice_created($id)
    {
        return DB::Connection('mysql2')->table('new_purchase_voucher')->where('grn_id', $id)->count();


    }


    public static function pi_get_net_amount($grn_id)
    {
        return $users = DB::Connection('mysql2')->table('new_purchase_voucher as a')
            ->join('new_purchase_voucher_data as b', 'a.id', '=', 'b.master_id')
            ->select(DB::raw("SUM(net_amount) as net_amount"), 'a.pv_date')
            ->where('a.status', 1)
            ->where('a.grn_id', $grn_id)
            ->first();
    }

    public static function return_amount($grn_id, $type)
    {

        return $users = DB::Connection('mysql2')->table('purchase_return as a')
            ->join('purchase_return_data as b', 'a.id', '=', 'b.master_id')
            ->select(DB::raw("SUM(net_amount) as net_amount"))
            ->where('a.status', 1)
            ->where('a.grn_id', $grn_id)
            ->where('a.type', $type)
            ->first()
            ->net_amount;
    }

    public static function return_amount_by_date($grn_id, $type, $from, $to)
    {

        return $users = DB::Connection('mysql2')->table('purchase_return as a')
            ->join('purchase_return_data as b', 'a.id', '=', 'b.master_id')
            ->select(DB::raw("SUM(net_amount) as net_amount"))
            ->where('a.status', 1)
            ->where('a.grn_id', $grn_id)
            ->where('a.type', $type)
            ->whereBetween('a.pr_date', [$from, $to])
            ->first()
            ->net_amount;
    }


    public static function get_main_sub_rights($user_id, $company_id)
    {
        $data = DB::table('menu_privileges')->where('emp_code', $user_id)->where('compnay_id', $company_id);
        if ($data->count() > 0):
            $data = $data->first();
            $main = $data->main_modules;
            $main = explode(',', $main);

            $sub = $data->submenu_id;
            $sub = explode(',', $sub);


            $crud = DB::table('users')->where('emp_code', $user_id)->first();

            if ($company_id == 1):
                $crud = $crud->crud_rights;


            elseif ($company_id == 2):
                $crud = $crud->crud_rights_2;


            elseif ($company_id == 3):
                $crud = $crud->crud_rights_3;

            elseif ($company_id == 5):
                $crud = $crud->crud_rights_4;
            endif;


            if (!empty($crud)):
                $crud = explode(',', $crud);
            else:
                $main = [0];
                $sub = [0];
                $crud = [0];
            endif;
        else:
            $main = [0];
            $sub = [0];
            $crud = [0];
        endif;
        $data1[0] = $main;
        $data1[1] = $sub;
        $data1[2] = $crud;
        return $data1;
    }

    public static function get_purchase_net_amount($id)
    {
        return DB::Connection('mysql2')->selectOne('
        select sum(net_amount)amount from  new_purchase_voucher_data
        where staus=1
        and master_id="' . $id . '"')->amount;
    }

    public static function get_total_paid_amount($id)
    {
        return DB::Connection('mysql2')->selectOne('
        select sum(amount)amount from  new_purchase_voucher_payment
        where status=1
        and new_purchase_voucher_id="' . $id . '"')->amount;
    }

    public static function stock_type_amount($from, $to, $type)
    {
        return DB::Connection('mysql2')->selectOne('SELECT sum(amount)amount from stock

                where voucher_date BETWEEN "' . $from . '" and "' . $to . '"
                and voucher_type="' . $type . '"
                and opening=0
                and status=1')->amount;
    }


    public static function get_dn_no($dn_ids)
    {

        $dn_ids = explode(',', $dn_ids);

        return $data = DB::Connection('mysql2')->table('delivery_note')->where('status', 1)->whereIn('id', $dn_ids)->select('gd_no', 'gd_date')->get();



    }

    public static function get_stock_amount_of_dn($dn_ids)
    {



        return $data = DB::Connection('mysql2')->table('stock')->where('status', 1)->whereIn('voucher_no', $dn_ids)->sum('amount');



    }


    public static function get_amount($id)
    {
        $debit = DB::Connection('mysql2')->table('transactions')->where('status', 1)->where('debit_credit', 0)->where('acc_id', $id)->sum('amount');
        $credit = DB::Connection('mysql2')->table('transactions')->where('status', 1)->where('debit_credit', 1)->where('acc_id', $id)->sum('amount');

        return $debit - $credit;
    }

    public static function get_opening_stock_for_finance($from, $to, $accyearfrom, $item_id, $open)
    {
        $data = [];

        $to = date('Y-m-d', strtotime('-1 day', strtotime($from)));
        if ($from == $accyearfrom):

            $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from transaction_supply_chain
        where status=1 and voucher_type=5 and opening=1 and item_id="' . $item_id . '"');
            $data[0] = $in->qty;
            $data[1] = $in->amount;

        else:




            $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from transaction_supply_chain
        where status=1 and voucher_type in (1,4,5) and item_id="' . $item_id . '" and voucher_date between "' . $accyearfrom . '" and "' . $to . '"');

            $purchase_amount = $in->amount;
            $purchase_qty = $in->qty;

            $out = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from transaction_supply_chain
        where status=1 and voucher_type in (2,3) and item_id="' . $item_id . '" and voucher_date between "' . $accyearfrom . '" and "' . $to . '"');

            $sales_qty = $out->qty;
            $sales_amount = $out->amount;

            $data[0] = $purchase_qty - $sales_qty;
            $data[1] = $purchase_amount - $sales_amount;
        endif;


        return $data;


    }


    public static function get_stock_type_wise_for_finance($from, $to, $item_id, $type)
    {
        $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from transaction_supply_chain
        where status=1 and voucher_type in (' . $type . ') and item_id="' . $item_id . '" and voucher_date between "' . $from . '" and "' . $to . '" and opening=0');

        $data[0] = $in->qty;
        $data[1] = $in->amount;

        return $data;
    }

    public static function get_bacth_code($warehouse_id, $item)
    {
        return DB::Connection('mysql2')->table('stock')->where('status', 1)
            ->where('warehouse_id', $warehouse_id)
            ->where('sub_item_id', $item)
            ->groupBy('batch_code')
            ->get();
    }

    public static function check_issuence_entry($id)
    {
        return DB::Connection('mysql2')->table('issuence_for_production')->where('status', 1)->where('main_id', $id)->count();
    }

    public static function get_stock_type_wise_for_in_recon($from, $to, $item_id, $type, $transfer)
    {
        $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (' . $type . ') and sub_item_id="' . $item_id . '" and voucher_date between "' . $from . '" and "' . $to . '" and opening=0
        and transfer_status="' . $transfer . '"
        and pos_status=0');

        $data[0] = $in->qty;
        $data[1] = $in->amount;

        return $data;
    }

    public static function get_stock_type_wise_for_in_recon_pos($from, $to, $item_id, $type, $po_status)
    {
        $in = DB::Connection('mysql2')->selectOne('select sum(amount)amount,sum(qty)qty  from stock
        where status=1 and voucher_type in (' . $type . ')
        and sub_item_id="' . $item_id . '"
        and voucher_date between "' . $from . '" and "' . $to . '"
        and opening=0
        and pos_status="' . $po_status . '"');

        $data[0] = $in->qty;
        $data[1] = $in->amount;

        return $data;
    }


    public static function sales_return($from, $to, $item_id, $type)
    {
        $data = DB::Connection('mysql2')->selectOne('SELECT sum(a.amount)amount from stock  a
        inner join
        credit_note b
        ON
        a.voucher_no=b.cr_no
        where a.status=1
        and b.status=1
        and b.type="' . $type . '"
        and a.voucher_date between "' . $from . '" and "' . $to . '"
        and a.sub_item_id="' . $item_id . '"
        ');

        $amount = 0;
        if (!empty($data->amount)):

            $amount = $data->amount;
        endif;
        return $amount;
    }

    public static function purchase_return_on_grn($from, $to, $item_id, $type)
    {
        $data = DB::Connection('mysql2')->selectOne('SELECT sum(a.amount)amount from stock  a
        inner join
        purchase_return b
        ON
        a.voucher_no=b.pr_no
        where a.status=1
        and b.status=1
        and b.type="' . $type . '"
        and a.voucher_date between "' . $from . '" and "' . $to . '"
        and a.sub_item_id="' . $item_id . '"
        ');

        $amount = 0;
        if (!empty($data->amount)):

            $amount = $data->amount;
        endif;
        return $amount;
    }

    public static function get_acc_id_by_code($code)
    {
        return DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('code', $code)->value('id');
    }

    public static function import_expense($pv_date, $master_id, $pv_no, $desc, $amount, $code)
    {
        $pv_data = new NewPvData();
        $pv_data = $pv_data->SetConnection('mysql2');
        $pv_data->master_id = $master_id;
        $pv_data->pv_no = $pv_no;
        $pv_data->pv_date = $pv_date;
        $acc_id = static::get_acc_id_by_code($code);
        $pv_data->acc_id = $acc_id;
        $pv_data->description = $desc;
        $pv_data->amount = $amount;
        $pv_data->debit_credit = 1;
        $pv_data->status = 1;
        $pv_data->pv_status = 1;
        $pv_data->date = date('Y-m-d');
        $pv_data->save();
    }


    public static function check_import_payment_in_pv($id, $type)
    {
        if ($type == 1):
            $type = 3;
        elseif ($type == 2):
            $type = 4;
        endif;
        return DB::Connection('mysql2')->table('new_pv')->where('status', 1)->where('import_payment_id', $id)->where('type', $type)->where('pv_status', 2);
    }

    public static function get_issuence($voucher_no)
    {
        return DB::Connection('mysql2')->table(' issuence_for_production')->where('status', 1)->where('voucher_no', $voucher_no)->count();
    }

    public static function make_account($parent_code, $name, $type)
    {

        $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $parent_code . '\' and status=1')->id;
        if ($max_id == '') {
            $code = $parent_code . '-1';
        } else {
            $max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\' and status=1')->code;
            $max_code2;
            $max = explode('-', $max_code2);
            $code = $parent_code . '-' . (end($max) + 1);
        }

        $level_array = explode('-', $code);
        $counter = 1;
        foreach ($level_array as $level):
            $data1['level' . $counter] = $level;
            $counter++;
        endforeach;
        $data1['code'] = $code;
        $data1['name'] = $name;
        $data1['parent_code'] = $parent_code;
        $data1['username'] = Auth::user()->name;

        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['action'] = 'create';
        $data1['operational'] = 0;
        $data1['type'] = $type;


        $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);
        return $acc_id;
    }

    public static function get_all_sales_tax()
    {
        return DB::Connection('mysql2')->table('gst')->get();
    }

    public static function get_quotation_amount_supp_wise($demand_data_id, $supplier)
    {

        return DB::Connection('mysql2')->table('quotation as a')
            ->join('quotation_data as b', 'a.id', '=', 'b.master_id')
            ->where('a.vendor_id', $supplier)
            ->where('b.pr_data_id', $demand_data_id)
            ->select('amount')
            ->value('amount');
    }

    public static function get_control_account($type)
    {
        return DB::Connection('mysql2')->table('control_account')->where('type', $type)->value('acc_id');
    }

    public static function invoice_tax()
    {
        return DB::Connection('mysql2')->table('invoice_tax')->where('status', 1)->get();
    }

    public static function invoice_tax_acc_id($tax)
    {
        return DB::Connection('mysql2')->table('invoice_tax')->where('tax_rate', $tax)->where('status', 1)->value('acc_id');
    }
    public static function Batch_code_generate($item_id)
    {
        $bacth_code = DB::Connection('mysql2')->table('stock')->where('sub_item_id', $item_id)->max('batch_code');
        if ($bacth_code == ''):
            $bacth_code = 0;
        endif;
        $bacth_code = intval($bacth_code) + 1;
        return $bacth_code = sprintf("%'03d", $bacth_code);
    }
    public static function postStock($main_id, $main_sub_id, $voucher_no, $date, $voucher_type, $amount, $item_id, $sub_item_name, $qty)
    {
        $stock = array(
            'main_id' => $main_id,
            'master_id' => $main_sub_id,
            'voucher_no' => $voucher_no,
            'voucher_date' => $date,
            'supplier_id' => 0,
            'voucher_type' => $voucher_type,
            'rate' => $amount,
            'sub_item_id' => $item_id,
            'batch_code' => 0,
            'qty' => $qty,
            'amount_before_discount' => 0,

            'discount_percent' => 0,
            'discount_amount' => 0,
            'amount' => $amount,

            'status' => 1,
            'warehouse_id' => 11,
            'description' => $sub_item_name,
            'username' => Auth::user()->username,
            'created_date' => date('Y-m-d'),
            'created_date' => date('Y-m-d'),
            'opening' => 0,
        );


        DB::Connection('mysql2')->table('stock')->insert($stock);
    }

    public static function stockOpening()
    {
        $data = DB::Connection('mysql2')->table('stock as a')
            ->join('subitem as b', 'a.sub_item_id', 'b.id')
            ->join('category as c', 'c.id', 'b.main_ic_id')
            ->select(DB::raw('sum(a.amount) amount'), 'c.acc_id')
            ->where('a.status', 1)
            ->where('b.status', 1)
            ->where('a.opening', 1)
            ->groupBy('c.acc_id')
            ->get();

        foreach ($data as $key => $value):
            $trans = new Transactions();
            $trans = $trans->SetConnection('mysql2');
            $trans = $trans->where('opening_bal', 1)->where('acc_id', $value->acc_id)->delete();

            $trans = new Transactions();
            $trans = $trans->SetConnection('mysql2');
            $trans->acc_id = $value->acc_id;
            $trans->acc_code = FinanceHelper::getAccountCodeByAccId($value->acc_id, '');
            ;
            $trans->master_id = 0;
            $trans->particulars = 'Opening';
            $trans->opening_bal = 1;
            $trans->debit_credit = 1;
            $trans->amount = $value->amount;
            ;
            $trans->voucher_no = '';
            $trans->voucher_type = 0;
            $trans->v_date = '2023-07-01';
            $trans->date = date('Y-m-d');
            $trans->action = 1;
            $trans->username = Auth::user()->name;

            $trans->save();


        endforeach;
    }

    public static function average_cost_without_wareHouse($item_id)
    {
        return DB::Connection('mysql2')->selectOne('select (sum(amount)/sum(qty)) as avergae_val from stock
        where status=1 and voucher_type=1 and sub_item_id="' . $item_id . '"')->avergae_val;
    }
}
