<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\StoreHelper;
class SaleApprovalDataCallController extends Controller
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

    public function creditSaleVoucherApprove(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
            $invoiceNo = $_GET['invoiceNo'];
            $totalAmount = 0;
        $updateDetails = array(
            'inv_status' => 2,
            'approve_username' => Auth::user()->name
        );

        DB::table('invoice')
            ->where('inv_no', $invoiceNo)
            ->update($updateDetails);

        DB::table('inv_data')
            ->where('inv_no', $invoiceNo)
            ->update($updateDetails);


        $invoiceDataDetail = DB::table('inv_data')
            ->where('inv_no', $invoiceNo)
            ->where('inv_status', '2')->get();
        $invoiceDetail = DB::table('invoice')->where('inv_no','=',$invoiceNo)->where('status','=',1)->first();
        foreach ($invoiceDataDetail as $row){
            $totalAmount += $row->amount;
            $data3['inv_no']         = $row->inv_no;
            $data3['inv_date']       = $row->inv_date;
            $data3['main_ic_id']       = $row->category_id;
            $data3['sub_ic_id']       = $row->sub_item_id;
            $data3['customer_id']       = $invoiceDetail->consignee;
            $data3['qty']               = $row->qty;
            $data3['price']               = $row->price;
            $data3['inv_against_discount']              = $invoiceDetail->inv_against_discount;
            $data3['value']               = $row->amount;
            $data3['action']               = '6';
            $data3['date']     		  	= date("Y-m-d");
            $data3['time']     		  	= date("H:i:s");
            $data3['username']          = Auth::user()->name;
            $data3['status']            = 1;
            $data3['company_id']     = $m;

            DB::table('fara')->insert($data3);
        }
        $calculatedTotalDiscount = $totalAmount*$invoiceDetail->inv_against_discount/100;

        $str_jv = DB::selectOne("select max(convert(substr(`jv_no`,3,length(substr(`jv_no`,3))-4),signed integer)) reg from `jvs` where substr(`jv_no`,-4,2) = ".date('m')." and substr(`jv_no`,-2,2) = ".date('y')."")->reg;
        $jv_no = 'jv'.($str_jv+1).date('my');

        $data_jvs['jv_no'] 	 = $jv_no;
        $data_jvs['jv_date']   = $invoiceDetail->inv_date;
        $data_jvs['inv_no'] 	 = $invoiceNo;
        $data_jvs['inv_date']   = $invoiceDetail->inv_date;
        $data_jvs['slip_no'] = $invoiceDetail->dc_no;
        $data_jvs['description'] 	 = '('.$invoiceDetail->main_description.') * ( Invoice No  => '.$invoiceNo.' ) * ( Invoice Date  => '.$invoiceDetail->inv_date.' ) * ( Slip No => '.$invoiceDetail->dc_no.' )';
        $data_jvs['jv_status'] = 2;
        $data_jvs['voucherType'] 	  = 4;
        $data_jvs['status'] 	  = 1;
        $data_jvs['username']  = Auth::user()->name;
        $data_jvs['date'] 	  = date('Y-m-d');
        $data_jvs['time'] 	  = date('H:i:s' );
        $data_jvs['approve_username']   = Auth::user()->name;

        DB::table('jvs')->insert($data_jvs);

        CommonHelper::reconnectMasterDatabase();
        $congsinee_acc = CommonHelper::getAccountIdByMasterTable($m,$invoiceDetail->consignee,'customers');
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $data_jvdebit['acc_id'] 		= $congsinee_acc;
        $data_jvdebit['amount'] 		= $totalAmount - $calculatedTotalDiscount;
        $data_jvdebit['debit_credit']  = '1';
        $data_jvdebit['jv_no'] 		 = $jv_no;
        $data_jvdebit['jv_date'] 		 = $invoiceDetail->inv_date;
        $data_jvdebit['description'] = '('.$invoiceDetail->main_description.') * ( Invoice No  => '.$invoiceNo.' ) * ( Invoice Date  => '.$invoiceDetail->inv_date.' ) * ( Slip No => '.$invoiceDetail->dc_no.' )';
        $data_jvdebit['username']  = Auth::user()->name;
        $data_jvdebit['date'] 	  = date('Y-m-d');
        $data_jvdebit['time'] 	  = date('H:i:s' );
        $data_jvdebit['approve_username']   = Auth::user()->name;
        $data_jvdebit['jv_status'] = 2;


        $data_jvcredit['acc_id'] 	   = $invoiceDetail->credit_acc_id;
        $data_jvcredit['amount'] 		= $totalAmount - $calculatedTotalDiscount;
        $data_jvcredit['debit_credit']  = '0';
        $data_jvcredit['jv_no'] 		 = $jv_no;
        $data_jvcredit['jv_date'] 		 = $invoiceDetail->inv_date;
        $data_jvcredit['description'] = '('.$invoiceDetail->main_description.') * ( Invoice No  => '.$invoiceNo.' ) * ( Invoice Date  => '.$invoiceDetail->inv_date.' ) * ( Slip No => '.$invoiceDetail->dc_no.' )';
        $data_jvcredit['username']  = Auth::user()->name;
        $data_jvcredit['date'] 	  = date('Y-m-d');
        $data_jvcredit['time'] 	  = date('H:i:s' );
        $data_jvcredit['approve_username']   = Auth::user()->name;
        $data_jvcredit['jv_status'] = 2;

        DB::table('jv_data')->insert($data_jvdebit);
        DB::table('jv_data')->insert($data_jvcredit);

        $jvsDataDetail = DB::table('jv_data')
            ->where('jv_no', $jv_no)
            ->where('jv_status', '2')->get();
        foreach ($jvsDataDetail as $jvRow){
            $jvdata['acc_id'] = $jvRow->acc_id;
            CommonHelper::reconnectMasterDatabase();
            $jvdata['acc_code'] = FinanceHelper::getAccountCodeByAccId($jvRow->acc_id,$m);
            CommonHelper::companyDatabaseConnection($_GET['m']);
            $jvdata['particulars'] = $jvRow->description;
            $jvdata['opening_bal'] = '0';
            $jvdata['debit_credit'] = $jvRow->debit_credit;
            $jvdata['amount'] = $jvRow->amount;
            $jvdata['voucher_no'] = $jvRow->jv_no;
            $jvdata['voucher_type'] = 5;
            $jvdata['v_date'] = $jvRow->jv_date;
            $jvdata['date'] = date("Y-m-d");
            $jvdata['time'] = date("H:i:s");
            $jvdata['username'] = Auth::user()->name;

            DB::table('transactions')->insert($jvdata);
        }


        CommonHelper::reconnectMasterDatabase();
    }

    public function checkQuantityinStock(){
        $m = $_GET['m'];
        $categoryIdValue = $_GET['categoryIdValue'];
        $itemIdValue = $_GET['itemIdValue'];
        $inputIdValue = $_GET['inputIdValue'];
        //'Item Id Value => '.$itemIdValue.'<= Input Id Value => '.$inputIdValue;
        return StoreHelper::checkItemWiseCurrentBalanceQty($m,$categoryIdValue,$itemIdValue);
    }

    public function cashSaleVoucherDelete(){
        $m = $_GET['m'];
        $invoiceNo = $_GET['invoiceNo'];
        $journalVouhcer = $_GET['journalVouhcer'];
        $receiptVoucher = $_GET['receiptVoucher'];
        CommonHelper::companyDatabaseConnection($m);
            DB::table('fara')->where('inv_no', '=', $invoiceNo)->delete();
            //DB::table('jvs')->where('jv_no', '=', $journalVouhcer)->delete();
            //DB::table('jv_data')->where('jv_no', '=', $journalVouhcer)->delete();
            DB::table('transactions')->where('voucher_no', '=', $journalVouhcer)->delete();
            //DB::table('rvs')->where('rv_no', '=', $receiptVoucher)->delete();
            //DB::table('rv_data')->where('rv_no', '=', $receiptVoucher)->delete();
            DB::table('transactions')->where('voucher_no', '=', $receiptVoucher)->delete();

        $updateInvoiceDetails = array(
            'status' => 2,
            'inv_status' => 1,
            'delete_username' => Auth::user()->name
        );
        DB::table('invoice')
            ->where('inv_no', $invoiceNo)
            ->update($updateInvoiceDetails);

        DB::table('inv_data')
            ->where('inv_no', $invoiceNo)
            ->update($updateInvoiceDetails);


        $updateJVDetails = array(
            'status' => 2,
            'jv_status' => 1,
            'delete_username' => Auth::user()->name
        );
        DB::table('jvs')
            ->where('jv_no', $journalVouhcer)
            ->update($updateJVDetails);

        DB::table('jv_data')
            ->where('jv_no', $journalVouhcer)
            ->update($updateJVDetails);

        $updateRVDetails = array(
            'status' => 2,
            'rv_status' => 1,
            'delete_username' => Auth::user()->name
        );
        DB::table('rvs')
            ->where('rv_no', $receiptVoucher)
            ->update($updateRVDetails);

        DB::table('rv_data')
            ->where('rv_no', $receiptVoucher)
            ->update($updateRVDetails);
        CommonHelper::reconnectMasterDatabase();

        echo $m .'   ---   '.$invoiceNo.'   ---   '.$journalVouhcer.'   ---   '.$receiptVoucher;
    }

    public function cashSaleVoucherRepost(){
        $m = $_GET['m'];
        $invoiceNo = $_GET['invoiceNo'];
        $journalVouhcer = $_GET['journalVouhcer'];
        $receiptVoucher = $_GET['receiptVoucher'];
        CommonHelper::companyDatabaseConnection($m);
        DB::table('fara')->where('inv_no', '=', $invoiceNo)->delete();
        //DB::table('jvs')->where('jv_no', '=', $journalVouhcer)->delete();
        //DB::table('jv_data')->where('jv_no', '=', $journalVouhcer)->delete();
        DB::table('transactions')->where('voucher_no', '=', $journalVouhcer)->delete();
        //DB::table('rvs')->where('rv_no', '=', $receiptVoucher)->delete();
        //DB::table('rv_data')->where('rv_no', '=', $receiptVoucher)->delete();
        DB::table('transactions')->where('voucher_no', '=', $receiptVoucher)->delete();

        $updateInvoiceDetails = array(
            'status' => 1,
            'inv_status' => 2,
            'delete_username' => ''
        );
        DB::table('invoice')
            ->where('inv_no', $invoiceNo)
            ->update($updateInvoiceDetails);

        DB::table('inv_data')
            ->where('inv_no', $invoiceNo)
            ->update($updateInvoiceDetails);


        $invoiceDataDetail = DB::table('inv_data')
            ->where('inv_no', $invoiceNo)
            ->where('inv_status', '2')->get();
        $invoiceDetail = DB::table('invoice')->where('inv_no','=',$invoiceNo)->where('status','=',1)->first();
        $totalAmount = 0;
        foreach ($invoiceDataDetail as $row){
            $totalAmount += $row->amount;
            $data3['inv_no']         = $row->inv_no;
            $data3['inv_date']       = $row->inv_date;
            $data3['main_ic_id']       = $row->category_id;
            $data3['sub_ic_id']       = $row->sub_item_id;
            $data3['customer_id']       = $invoiceDetail->consignee;
            $data3['qty']               = $row->qty;
            $data3['price']               = $row->price;
            $data3['inv_against_discount']              = $invoiceDetail->inv_against_discount;
            $data3['value']               = $row->amount;
            $data3['action']               = '6';
            $data3['date']     		  	= date("Y-m-d");
            $data3['time']     		  	= date("H:i:s");
            $data3['username']          = Auth::user()->name;
            $data3['status']            = 1;
            $data3['company_id']     = $m;

            DB::table('fara')->insert($data3);
        }


        $updateJVDetails = array(
            'status' => 1,
            'jv_status' => 2,
            'delete_username' => ''
        );
        DB::table('jvs')
            ->where('jv_no', $journalVouhcer)
            ->update($updateJVDetails);

        DB::table('jv_data')
            ->where('jv_no', $journalVouhcer)
            ->update($updateJVDetails);


        $jvsDataDetail = DB::table('jv_data')
            ->where('jv_no', $journalVouhcer)
            ->where('jv_status', '2')->get();
        foreach ($jvsDataDetail as $jvRow){
            $jvdata['acc_id'] = $jvRow->acc_id;
            CommonHelper::reconnectMasterDatabase();
            $jvdata['acc_code'] = FinanceHelper::getAccountCodeByAccId($jvRow->acc_id,$m);
            CommonHelper::companyDatabaseConnection($m);
            $jvdata['particulars'] = $jvRow->description;
            $jvdata['opening_bal'] = '0';
            $jvdata['debit_credit'] = $jvRow->debit_credit;
            $jvdata['amount'] = $jvRow->amount;
            $jvdata['voucher_no'] = $jvRow->jv_no;
            $jvdata['voucher_type'] = 1;
            $jvdata['v_date'] = $jvRow->jv_date;
            $jvdata['date'] = date("Y-m-d");
            $jvdata['time'] = date("H:i:s");
            $jvdata['username'] = Auth::user()->name;

            DB::table('transactions')->insert($jvdata);
        }


        $updateRVDetails = array(
            'status' => 1,
            'rv_status' => 2,
            'delete_username' => ''
        );
        DB::table('rvs')
            ->where('rv_no', $receiptVoucher)
            ->update($updateRVDetails);

        DB::table('rv_data')
            ->where('rv_no', $receiptVoucher)
            ->update($updateRVDetails);


        $rvsDataDetail = DB::table('rv_data')
            ->where('rv_no', $receiptVoucher)
            ->where('rv_status', '2')->get();
        foreach ($rvsDataDetail as $rvRow){
            $rvdata['acc_id'] = $rvRow->acc_id;
            CommonHelper::reconnectMasterDatabase();
            $rvdata['acc_code'] = FinanceHelper::getAccountCodeByAccId($rvRow->acc_id,$m);
            CommonHelper::companyDatabaseConnection($m);
            $rvdata['particulars'] = $rvRow->description;
            $rvdata['opening_bal'] = '0';
            $rvdata['debit_credit'] = $rvRow->debit_credit;
            $rvdata['amount'] = $rvRow->amount;
            $rvdata['voucher_no'] = $rvRow->rv_no;
            $rvdata['voucher_type'] = 3;
            $rvdata['v_date'] = $rvRow->rv_date;
            $rvdata['date'] = date("Y-m-d");
            $rvdata['time'] = date("H:i:s");
            $rvdata['username'] = Auth::user()->name;

            DB::table('transactions')->insert($rvdata);
        }
        CommonHelper::reconnectMasterDatabase();

        //echo $m .'   ---   '.$invoiceNo.'   ---   '.$journalVouhcer.'   ---   '.$receiptVoucher;
    }
}