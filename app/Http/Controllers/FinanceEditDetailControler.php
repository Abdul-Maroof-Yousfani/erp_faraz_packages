<?php
namespace App\Http\Controllers;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\CostCenter;
use App\Models\DepartmentAllocation1;
use App\Models\CostCenterDepartmentAllocation;

use App\Models\Rvs;
use App\Models\Rvs_data;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
class FinanceEditDetailControler extends Controller
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

    function editJournalPendingVoucherDetail()
    {
        FinanceHelper::companyDatabaseConnection($_GET['m']);

        $jv_no = Input::get('jv_no');
        $id = Input::get('id');
        //   DB::table('jvs')->where('jv_no', $jv_no)->delete();


        $slip_no = Input::get('slip_no');
        $jv_date = Input::get('jv_date');
        $description = Input::get('description');

        $data1['jv_date'] = $jv_date;
        $data1['jv_no'] = $jv_no;
        $data1['slip_no'] = $slip_no;
        $data1['voucherType'] = 1;
        $data1['description'] = $description;
        $data1['username'] = Auth::user()->name;
        $data1['jv_status'] = 1;
        $data1['date'] = date('Y-m-d');
        $data1['time'] = date('H:i:s');

        //  DB::table('jvs')->insert($data1);

        DB::table('jvs')->where('id', $id)->update($data1);
        DB::table('jv_data')->where('master_id', $id)->delete();
        DB::table('transactions')->where('master_id', $id)->where('voucher_type', 1)->delete();
        $jvsDataSection = Input::get('jvsDataSection_1');
        foreach ($jvsDataSection as $row1) {
            $d_amount = Input::get('d_amount_1_' . $row1 . '');
            $c_amount = Input::get('c_amount_1_' . $row1 . '');
            {
                $account = Input::get('account_id_1_' . $row1 . '');
                if ($d_amount != "") {
                    $data2['debit_credit'] = 1;
                    $data2['amount'] = $d_amount;

                    $data['debit_credit'] = 1;
                    $data['amount'] = $d_amount;
                } else if ($c_amount != "") {
                    $data2['debit_credit'] = 0;
                    $data2['amount'] = $c_amount;

                    $data['debit_credit'] = 0;
                    $data['amount'] = $c_amount;
                }

                $data2['jv_no'] = $jv_no;
                $data2['jv_date'] = $jv_date;
                $data2['acc_id'] = $account;
                $data2['description'] = $description;
                $data2['jv_status'] = 1;
                $data2['username'] = Auth::user()->name;
                $data2['status'] = 1;
                $data2['date'] = date('Y-m-d');
                $data2['time'] = date('H:i:s');
                $data2['master_id'] = $id;

                DB::table('jv_data')->insert($data2);


                $data['acc_id'] = $account;
                $data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account, '');
                $data['particulars'] = $description;
                $data['opening_bal'] = '0';
                $data['voucher_no'] = $jv_no;
                $data['voucher_type'] = 1;
                $data['v_date'] = $jv_date;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");
                $data['master_id'] = $id;
                $data['username'] = Auth::user()->name;
                DB::table('transactions')->insert($data);
            }
            echo 'Done';
            FinanceHelper::reconnectMasterDatabase();
            Session::flash('dataEdit', 'successfully edit.');
        }
    }
    function editJournalApproveVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);

        $jv_no = Input::get('jv_no');

        DB::table('jvs')->where('jv_no', $jv_no)->delete();
        DB::table('jv_data')->where('jv_no', $jv_no)->delete();
        DB::table('transactions')->where('voucher_no', $jv_no)->delete();




        $slip_no = Input::get('slip_no');
        $jv_date = Input::get('jv_date');
        $description = Input::get('description');

        $data1['jv_date']   	= $jv_date;
        $data1['jv_no']   		= $jv_no;
        $data1['slip_no']   	= $slip_no;
        $data1['voucherType'] 	= 1;
        $data1['description']   = $description;
        $data1['username'] 		= Auth::user()->name;
        $data1['jv_status']  	= 2;
        $data1['date'] 			= date('Y-m-d');
        $data1['time'] 			= date('H:i:s');

        DB::table('jvs')->insert($data1);

        $jvsDataSection = Input::get('jvsDataSection_1');
        foreach($jvsDataSection as $row1){
            $d_amount =  Input::get('d_amount_1_'.$row1.'');
            $c_amount =  Input::get('c_amount_1_'.$row1.'');
            $account  =  Input::get('account_id_1_'.$row1.'');
            if($d_amount !=""){
                $data2['debit_credit'] = 1;
                $data2['amount'] = $d_amount;
            }else if($c_amount !=""){
                $data2['debit_credit'] = 0;
                $data2['amount'] = $c_amount;
            }

            $data2['jv_no']   		= $jv_no;
            $data2['jv_date']   	= $jv_date;
            $data2['acc_id'] 		= $account;
            $data2['description']   = $description;
            $data2['jv_status']   	= 2;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');

            DB::table('jv_data')->insert($data2);
        }


        $tableTwoDetail = DB::table('jv_data')
            ->where('jv_no', $jv_no)
            ->where('jv_status', '2')->get();
        FinanceHelper::reconnectMasterDatabase();
        foreach ($tableTwoDetail as $row2) {
            $vouceherType = 1;
            $voucherNo = $row2->jv_no;
            $voucherDate = $row2->jv_date;

            $data3['acc_id'] = $row2->acc_id;
            $data3['acc_code'] = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);
            $data3['particulars'] = $row2->description;
            $data3['opening_bal'] = '0';
            $data3['debit_credit'] = $row2->debit_credit;
            $data3['amount'] = $row2->amount;
            $data3['voucher_no'] = $voucherNo;
            $data3['voucher_type'] = $vouceherType;
            $data3['v_date'] = $voucherDate;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['username'] = Auth::user()->name;

            DB::table('transactions')->insert($data3);
            FinanceHelper::reconnectMasterDatabase();
        }
        echo 'Done';
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }

    function editCashPaymentPendingVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);


        $id = Input::get('id');
        $pv_no = Input::get('pv_no');





        $slip_no = Input::get('slip_no');
        $pv_date = Input::get('pv_date');
        $description = Input::get('description');

        $data1['pv_date']   	= $pv_date;
        $data1['pv_no']   		= $pv_no;
        $data1['slip_no']   	= $slip_no;
        $data1['voucherType'] 	= 1;
        $data1['description']   = $description;
        $data1['username'] 		= Auth::user()->name;
        $data1['pv_status']  	= 1;
        $data1['date'] 			= date('Y-m-d');
        $data1['time'] 			= date('H:i:s');

        DB::table('pvs')->insert($data1);
        DB::table('pvs')->where('id', $id)->update($data1);


        DB::table('pv_data')->where('master_id', $id)->delete();
        DB::table('transactions')->where('master_id', $id)->where('voucher_type',2)->delete();
        $pvsDataSection = Input::get('pvsDataSection_1');
        foreach($pvsDataSection as $row1){
            $d_amount =  Input::get('d_amount_1_'.$row1.'');
            $c_amount =  Input::get('c_amount_1_'.$row1.'');
            $account  =  Input::get('account_id_1_'.$row1.'');
            if($d_amount !=""){
                $data2['debit_credit'] = 1;
                $data2['amount'] = $d_amount;
                $data['debit_credit'] = 1;
                $data['amount'] = $d_amount;
            }else if($c_amount !=""){
                $data2['debit_credit'] = 0;
                $data2['amount'] = $c_amount;

                $data['debit_credit'] = 0;
                $data['amount'] = $c_amount;
            }

            $data2['pv_no']   		= $pv_no;
            $data2['pv_date']   	= $pv_date;
            $data2['acc_id'] 		= $account;
            $data2['description']   = $description;
            $data2['pv_status']   	= 1;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');
            $data2['master_id'] 			=$id;

            DB::table('pv_data')->insert($data2);


            $data['acc_id'] = $account;
            $data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
            $data['particulars'] =$description;
            $data['opening_bal'] = '0';
            $data['voucher_no'] = $pv_no;
            $data['voucher_type'] = 2;
            $data['v_date'] = $pv_date;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $data['master_id'] = $id;
            $data['username'] = Auth::user()->name;
            DB::table('transactions')->insert($data);
        }
        echo 'Done';
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }

    function editCashPaymentApproveVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);

        $pv_no = Input::get('pv_no');

        DB::table('pvs')->where('pv_no', $pv_no)->delete();
        DB::table('pv_data')->where('pv_no', $pv_no)->delete();
        DB::table('transactions')->where('voucher_no', $pv_no)->delete();



        $slip_no = Input::get('slip_no');
        $pv_date = Input::get('pv_date');
        $description = Input::get('description');

        $data1['pv_date']   	= $pv_date;
        $data1['pv_no']   		= $pv_no;
        $data1['slip_no']   	= $slip_no;
        $data1['voucherType'] 	= 1;
        $data1['description']   = $description;
        $data1['username'] 		= Auth::user()->name;
        $data1['pv_status']  	= 2;
        $data1['date'] 			= date('Y-m-d');
        $data1['time'] 			= date('H:i:s');

        DB::table('pvs')->insert($data1);

        $pvsDataSection = Input::get('pvsDataSection_1');
        foreach($pvsDataSection as $row1){
            $d_amount =  Input::get('d_amount_1_'.$row1.'');
            $c_amount =  Input::get('c_amount_1_'.$row1.'');
            $account  =  Input::get('account_id_1_'.$row1.'');
            if($d_amount !=""){
                $data2['debit_credit'] = 1;
                $data2['amount'] = $d_amount;
            }else if($c_amount !=""){
                $data2['debit_credit'] = 0;
                $data2['amount'] = $c_amount;
            }

            $data2['pv_no']   		= $pv_no;
            $data2['pv_date']   	= $pv_date;
            $data2['acc_id'] 		= $account;
            $data2['description']   = $description;
            $data2['pv_status']   	= 2;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');

            DB::table('pv_data')->insert($data2);
        }


        $tableTwoDetail = DB::table('pv_data')
            ->where('pv_no', $pv_no)
            ->where('pv_status', '2')->get();
        FinanceHelper::reconnectMasterDatabase();
        foreach ($tableTwoDetail as $row2) {
            $vouceherType = 2;
            $voucherNo = $row2->pv_no;
            $voucherDate = $row2->pv_date;

            $data3['acc_id'] = $row2->acc_id;
            $data3['acc_code'] = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);
            $data3['particulars'] = $row2->description;
            $data3['opening_bal'] = '0';
            $data3['debit_credit'] = $row2->debit_credit;
            $data3['amount'] = $row2->amount;
            $data3['voucher_no'] = $voucherNo;
            $data3['voucher_type'] = $vouceherType;
            $data3['v_date'] = $voucherDate;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['username'] = Auth::user()->name;

            DB::table('transactions')->insert($data3);
            FinanceHelper::reconnectMasterDatabase();
        }
        echo 'Done';
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }

    function editBankPaymentPendingVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);
        $id = Input::get('id');
        $pv_no = Input::get('pv_no');

        //DB::table('pvs')->where('pv_no', $pv_no)->delete();




        $slip_no = Input::get('slip_no');
        $pv_date = Input::get('pv_date');
        $description = Input::get('description');
        $cheque_no = Input::get('cheque_no');
        $cheque_date = Input::get('cheque_date');

        $data1['pv_date']   	= $pv_date;
        $data1['pv_no']   		= $pv_no;
        $data1['slip_no']   	= $slip_no;
        $data1['cheque_no']   	= $cheque_no;
        $data1['cheque_date']   = $cheque_date;
        $data1['voucherType'] 	= 2;
        $data1['description']   = $description;
        $data1['username'] 		= Auth::user()->name;
        $data1['pv_status']  	= 1;
        $data1['date'] 			= date('Y-m-d');
        $data1['time'] 			= date('H:i:s');
        DB::table('pvs')->where('id', $id)->update($data1);
        //   DB::table('pvs')->insert($data1);
        DB::table('pv_data')->where('master_id', $id)->delete();
        DB::table('transactions')->where('master_id', $id)->where('voucher_type',2)->delete();
        $pvsDataSection = Input::get('pvsDataSection_1');
        foreach($pvsDataSection as $row1)
        {
            $d_amount =  Input::get('d_amount_1_'.$row1.'');
            $c_amount =  Input::get('c_amount_1_'.$row1.'');
            $account  =  Input::get('account_id_1_'.$row1.'');
            if($d_amount !="")
            {
                $data2['debit_credit'] = 1;
                $data2['amount'] = $d_amount;

                $data['debit_credit'] = 1;
                $data['amount'] = $d_amount;
            }
            else if($c_amount !="")
            {
                $data2['debit_credit'] = 0;
                $data2['amount'] = $c_amount;

                $data['debit_credit'] = 0;
                $data['amount'] = $c_amount;
            }

            $data2['pv_no']   		= $pv_no;
            $data2['pv_date']   	= $pv_date;
            $data2['acc_id'] 		= $account;
            $data2['description']   = $description;
            $data2['pv_status']   	= 1;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');
            $data2['master_id'] 			= $id;

            DB::table('pv_data')->insert($data2);

            $data['acc_id'] = $account;
            $data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
            $data['particulars'] =$description;
            $data['opening_bal'] = '0';
            $data['voucher_no'] = $pv_no;
            $data['voucher_type'] = 2;
            $data['v_date'] = $pv_date;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $data['master_id'] = $id;
            $data['username'] = Auth::user()->name;
            DB::table('transactions')->insert($data);

        }
        echo 'Done';
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }

    function editBankPaymentApproveVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);

        $pv_no = Input::get('pv_no');

        DB::table('pvs')->where('pv_no', $pv_no)->delete();
        DB::table('pv_data')->where('pv_no', $pv_no)->delete();
        DB::table('transactions')->where('voucher_no', $pv_no)->delete();



        $slip_no = Input::get('slip_no');
        $pv_date = Input::get('pv_date');
        $description = Input::get('description');
        $cheque_no = Input::get('cheque_no');
        $cheque_date = Input::get('cheque_date');

        $data1['pv_date']   	= $pv_date;
        $data1['pv_no']   		= $pv_no;
        $data1['slip_no']   	= $slip_no;
        $data1['cheque_no']   	= $cheque_no;
        $data1['cheque_date']   = $cheque_date;
        $data1['voucherType'] 	= 2;
        $data1['description']   = $description;
        $data1['username'] 		= Auth::user()->name;
        $data1['pv_status']  	= 2;
        $data1['date'] 			= date('Y-m-d');
        $data1['time'] 			= date('H:i:s');

        DB::table('pvs')->insert($data1);

        $pvsDataSection = Input::get('pvsDataSection_1');
        foreach($pvsDataSection as $row1){
            $d_amount =  Input::get('d_amount_1_'.$row1.'');
            $c_amount =  Input::get('c_amount_1_'.$row1.'');
            $account  =  Input::get('account_id_1_'.$row1.'');
            if($d_amount !=""){
                $data2['debit_credit'] = 1;
                $data2['amount'] = $d_amount;
            }else if($c_amount !=""){
                $data2['debit_credit'] = 0;
                $data2['amount'] = $c_amount;
            }

            $data2['pv_no']   		= $pv_no;
            $data2['pv_date']   	= $pv_date;
            $data2['acc_id'] 		= $account;
            $data2['description']   = $description;
            $data2['pv_status']   	= 2;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');

            DB::table('pv_data')->insert($data2);
        }


        $tableTwoDetail = DB::table('pv_data')
            ->where('pv_no', $pv_no)
            ->where('pv_status', '2')->get();
        FinanceHelper::reconnectMasterDatabase();
        foreach ($tableTwoDetail as $row2) {
            $vouceherType = 2;
            $voucherNo = $row2->pv_no;
            $voucherDate = $row2->pv_date;

            $data3['acc_id'] = $row2->acc_id;
            $data3['acc_code'] = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);
            $data3['particulars'] = $row2->description;
            $data3['opening_bal'] = '0';
            $data3['debit_credit'] = $row2->debit_credit;
            $data3['amount'] = $row2->amount;
            $data3['voucher_no'] = $voucherNo;
            $data3['voucher_type'] = $vouceherType;
            $data3['v_date'] = $voucherDate;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['username'] = Auth::user()->name;

            DB::table('transactions')->insert($data3);
            FinanceHelper::reconnectMasterDatabase();
        }
        echo 'Done';
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }


    function editCashReceiptPendingVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);

        $rv_no = Input::get('rv_no');
        $id = Input::get('id');
        //	DB::table('rvs')->where('rv_no', $rv_no)->delete();




        $slip_no = Input::get('slip_no');
        $rv_date = Input::get('rv_date');
        $description = Input::get('description');

        $data1['rv_date']   	= $rv_date;
        $data1['rv_no']   		= $rv_no;
        $data1['slip_no']   	= $slip_no;
        $data1['voucherType'] 	= 1;
        $data1['description']   = $description;
        $data1['username'] 		= Auth::user()->name;
        $data1['rv_status']  	= 1;
        $data1['date'] 			= date('Y-m-d');
        $data1['time'] 			= date('H:i:s');

        // DB::table('rvs')->insert($data1);
        DB::table('rvs')->where('id', $id)->update($data1);
        DB::table('rv_data')->where('master_id', $id)->delete();
        DB::table('transactions')->where('master_id', $id)->where('voucher_type',3)->delete();
        $rvsDataSection = Input::get('rvsDataSection_1');
        foreach($rvsDataSection as $row1){
            $d_amount =  Input::get('d_amount_1_'.$row1.'');
            $c_amount =  Input::get('c_amount_1_'.$row1.'');
            $account  =  Input::get('account_id_1_'.$row1.'');
            if($d_amount !="")
            {
                $data2['debit_credit'] = 1;
                $data2['amount'] = $d_amount;

                $data['debit_credit'] = 1;
                $data['amount'] = $d_amount;
            }
            else if($c_amount !="")
            {
                $data2['debit_credit'] = 0;
                $data2['amount'] = $c_amount;

                $data['debit_credit'] = 0;
                $data['amount'] = $c_amount;
            }

            $data2['rv_no']   		= $rv_no;
            $data2['rv_date']   	= $rv_date;
            $data2['acc_id'] 		= $account;
            $data2['description']   = $description;
            $data2['rv_status']   	= 1;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');
            $data2['master_id']         =$id;

            DB::table('rv_data')->insert($data2);

            $data['acc_id'] = $account;
            $data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
            $data['particulars'] =$description;
            $data['opening_bal'] = '0';
            $data['voucher_no'] = $rv_no;
            $data['voucher_type'] = 3;
            $data['v_date'] = $rv_date;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $data['master_id'] = $id;
            $data['username'] = Auth::user()->name;
            DB::table('transactions')->insert($data);
        }
        echo 'Done';
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }

    function editCashReceiptApproveVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);

        $rv_no = Input::get('rv_no');

        DB::table('rvs')->where('rv_no', $rv_no)->delete();
        DB::table('rv_data')->where('rv_no', $rv_no)->delete();
        DB::table('transactions')->where('voucher_no', $rv_no)->delete();



        $slip_no = Input::get('slip_no');
        $rv_date = Input::get('rv_date');
        $description = Input::get('description');

        $data1['rv_date']   	= $rv_date;
        $data1['rv_no']   		= $rv_no;
        $data1['slip_no']   	= $slip_no;
        $data1['voucherType'] 	= 1;
        $data1['description']   = $description;
        $data1['username'] 		= Auth::user()->name;
        $data1['rv_status']  	= 2;
        $data1['date'] 			= date('Y-m-d');
        $data1['time'] 			= date('H:i:s');

        DB::table('rvs')->insert($data1);

        $rvsDataSection = Input::get('rvsDataSection_1');
        foreach($rvsDataSection as $row1){
            $d_amount =  Input::get('d_amount_1_'.$row1.'');
            $c_amount =  Input::get('c_amount_1_'.$row1.'');
            $account  =  Input::get('account_id_1_'.$row1.'');
            if($d_amount !=""){
                $data2['debit_credit'] = 1;
                $data2['amount'] = $d_amount;
            }else if($c_amount !=""){
                $data2['debit_credit'] = 0;
                $data2['amount'] = $c_amount;
            }

            $data2['rv_no']   		= $rv_no;
            $data2['rv_date']   	= $rv_date;
            $data2['acc_id'] 		= $account;
            $data2['description']   = $description;
            $data2['rv_status']   	= 2;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');

            DB::table('rv_data')->insert($data2);
        }


        $tableTwoDetail = DB::table('rv_data')
            ->where('rv_no', $rv_no)
            ->where('rv_status', '2')->get();
        FinanceHelper::reconnectMasterDatabase();
        foreach ($tableTwoDetail as $row2) {
            $vouceherType = 2;
            $voucherNo = $row2->rv_no;
            $voucherDate = $row2->rv_date;

            $data3['acc_id'] = $row2->acc_id;
            $data3['acc_code'] = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);
            $data3['particulars'] = $row2->description;
            $data3['opening_bal'] = '0';
            $data3['debit_credit'] = $row2->debit_credit;
            $data3['amount'] = $row2->amount;
            $data3['voucher_no'] = $voucherNo;
            $data3['voucher_type'] = $vouceherType;
            $data3['v_date'] = $voucherDate;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['username'] = Auth::user()->name;

            DB::table('transactions')->insert($data3);
            FinanceHelper::reconnectMasterDatabase();
        }
        echo 'Done';
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }


    function editBankReceiptPendingVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);
        DB::beginTransaction();
        try {
            $id = Input::get('id');
            $rv_no = Input::get('rv_no');

            //	DB::table('rvs')->where('rv_no', $rv_no)->delete();
            //	DB::table('rv_data')->where('rv_no', $rv_no)->delete();
            $slip_no = Input::get('slip_no');
            $rv_date = Input::get('rv_date');
            $description = Input::get('description');
            $cheque_no = Input::get('cheque_no');
            $cheque_date = Input::get('cheque_date');

            $data1['rv_date'] = $rv_date;
            $data1['rv_no'] = $rv_no;
            $data1['slip_no'] = $slip_no;
            $data1['cheque_no'] = $cheque_no;
            $data1['cheque_date'] = $cheque_date;
            $data1['voucherType'] = 2;
            $data1['description'] = $description;
            $data1['username'] = Auth::user()->name;
            $data1['rv_status'] = 1;
            $data1['date'] = date('Y-m-d');
            $data1['time'] = date('H:i:s');

            // DB::table('rvs')->insert($data1);
            DB::table('rvs')->where('id', $id)->update($data1);
          DB::table('rv_data')->where('master_id', $id)->delete();
            DB::table('transactions')->where('master_id', $id)->where('voucher_type',3)->delete();
            $rvsDataSection = Input::get('rvsDataSection_1');
            foreach ($rvsDataSection as $row1) {
                $d_amount = Input::get('d_amount_1_' . $row1 . '');
                $c_amount = Input::get('c_amount_1_' . $row1 . '');
                $account = Input::get('account_id_1_' . $row1 . '');
                if ($d_amount != "")
                {
                    $data2['debit_credit'] = 1;
                    $data2['amount'] = $d_amount;

                    $data['debit_credit'] = 1;
                    $data['amount'] = $d_amount;
                }
                else if ($c_amount != "")
                {
                    $data2['debit_credit'] = 0;
                    $data2['amount'] = $c_amount;

                    $data['debit_credit'] = 0;
                    $data['amount'] = $c_amount;
                }

                $data2['rv_no'] = $rv_no;
                $data2['rv_date'] = $rv_date;
                $data2['acc_id'] = $account;
                $data2['description'] = $description;

                $data2['username'] = Auth::user()->name;
                $data2['status'] = 1;
                $data2['date'] = date('Y-m-d');
                $data2['time'] = date('H:i:s');
                $data2['master_id'] = $id;


                DB::table('rv_data')->insert($data2);


                $data['acc_id'] = $account;
                $data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
                $data['particulars'] =$description;
                $data['opening_bal'] = '0';
                $data['voucher_no'] = $rv_no;
                $data['voucher_type'] = 3;
                $data['v_date'] = $rv_date;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");
                $data['master_id'] = $id;
                $data['username'] = Auth::user()->name;
                DB::table('transactions')->insert($data);
            }
            echo 'Done';
            DB::commit();

        }
        catch(Exception $e)
        {
            DB::rollback();
        }
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }

    function editBankReceiptApproveVoucherDetail(){
        FinanceHelper::companyDatabaseConnection($_GET['m']);

        $rv_no = Input::get('rv_no');

        DB::table('rvs')->where('rv_no', $rv_no)->delete();
        DB::table('rv_data')->where('rv_no', $rv_no)->delete();
        DB::table('transactions')->where('voucher_no', $rv_no)->delete();



        $slip_no = Input::get('slip_no');
        $rv_date = Input::get('rv_date');
        $description = Input::get('description');
        $cheque_no = Input::get('cheque_no');
        $cheque_date = Input::get('cheque_date');

        $data1['rv_date']   	= $rv_date;
        $data1['rv_no']   		= $rv_no;
        $data1['slip_no']   	= $slip_no;
        $data1['cheque_no']   	= $cheque_no;
        $data1['cheque_date']   = $cheque_date;
        $data1['voucherType'] 	= 2;
        $data1['description']   = $description;
        $data1['username'] 		= Auth::user()->name;
        $data1['rv_status']  	= 2;
        $data1['date'] 			= date('Y-m-d');
        $data1['time'] 			= date('H:i:s');

        DB::table('rvs')->insert($data1);

        $rvsDataSection = Input::get('rvsDataSection_1');
        foreach($rvsDataSection as $row1){
            $d_amount =  Input::get('d_amount_1_'.$row1.'');
            $c_amount =  Input::get('c_amount_1_'.$row1.'');
            $account  =  Input::get('account_id_1_'.$row1.'');
            if($d_amount !=""){
                $data2['debit_credit'] = 1;
                $data2['amount'] = $d_amount;
            }else if($c_amount !=""){
                $data2['debit_credit'] = 0;
                $data2['amount'] = $c_amount;
            }

            $data2['rv_no']   		= $rv_no;
            $data2['rv_date']   	= $rv_date;
            $data2['acc_id'] 		= $account;
            $data2['description']   = $description;
            $data2['rv_status']   	= 2;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');

            DB::table('rv_data')->insert($data2);
        }


        $tableTwoDetail = DB::table('rv_data')
            ->where('rv_no', $rv_no)
            ->where('rv_status', '2')->get();
        FinanceHelper::reconnectMasterDatabase();
        foreach ($tableTwoDetail as $row2) {
            $vouceherType = 2;
            $voucherNo = $row2->rv_no;
            $voucherDate = $row2->rv_date;

            $data3['acc_id'] = $row2->acc_id;
            $data3['acc_code'] = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);
            $data3['particulars'] = $row2->description;
            $data3['opening_bal'] = '0';
            $data3['debit_credit'] = $row2->debit_credit;
            $data3['amount'] = $row2->amount;
            $data3['voucher_no'] = $voucherNo;
            $data3['voucher_type'] = $vouceherType;
            $data3['v_date'] = $voucherDate;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['username'] = Auth::user()->name;

            DB::table('transactions')->insert($data3);
            FinanceHelper::reconnectMasterDatabase();
        }
        echo 'Done';
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }
    public function editEmployeeDetail()
    {
        FinanceHelper::companyDatabaseConnection(Input::get('company_id'));

        $employeeSection = Input::get('employeeSection');
        foreach($employeeSection as $row){
            $employee_name = Input::get('employee_name_'.$row.'');
            $father_name = Input::get('father_name_'.$row.'');
            $sub_department_id = Input::get('sub_department_id_'.$row.'');
            $date_of_birth = Input::get('date_of_birth_'.$row.'');
            $joining_date = Input::get('joining_date_'.$row.'');
            $gender = Input::get('gender_'.$row.'');
            $cnic = Input::get('cnic_'.$row.'');
            $contact_no = Input::get('contact_no_'.$row.'');
            $employee_status = Input::get('employee_status_'.$row.'');
            $salary = Input::get('salary_'.$row.'');
            $email = Input::get('email_'.$row.'');
            $marital_status = Input::get('marital_status_'.$row.'');
            //$department_name = Input::get('department_name');

            $str = DB::selectOne("select max(convert(substr(`emp_no`,4,length(substr(`emp_no`,4))-4),signed integer)) reg from `employee` where substr(`emp_no`,-4,2) = ".date('m')." and substr(`emp_no`,-2,2) = ".date('y')."")->reg;
            $employee_no = 'Emp'.($str+1).date('my');

            $data1['emp_no'] 				 = strip_tags($employee_no);
            $data1['emp_name'] 				 = strip_tags($employee_name);
            $data1['emp_father_name'] 		 = strip_tags($father_name);
            $data1['emp_sub_department_id'] 	 = strip_tags($sub_department_id);
            $data1['emp_date_of_birth'] 	 = strip_tags($date_of_birth);
            $data1['emp_joining_date'] 		 = strip_tags($joining_date);
            $data1['emp_gender'] 			 = strip_tags($gender);
            $data1['emp_cnic'] 				 = strip_tags($cnic);
            $data1['emp_contact_no'] 		 = strip_tags($contact_no);
            $data1['emp_employementstatus_id'] = strip_tags($employee_status);
            $data1['emp_salary'] 			 = strip_tags($salary);
            $data1['emp_email'] 			 = strip_tags($email);
            $data1['emp_marital_status'] 	 = strip_tags($marital_status);
            $data1['username'] 		 		 = Auth::user()->name;
            $data1['status'] 		 		 = 1;
            $data1['date']     		  		 = date("Y-m-d");
            $data1['time']     		  		 = date("H:i:s");


            DB::table('employee')->where('id', Input::get('id'))->update($data1);
        }


        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeList?pageType=viewlist&&parentCode=20&&m='.Input::get('company_id').'');

    }
    public function editAccountDetail($id){

        DB::Connection('mysql2')->beginTransaction();

        try {
        FinanceHelper::companyDatabaseConnection($_GET['m']);
        $parent_code = Input::get('account_id');

        $acc_name = Input::get('acc_name');
        $o_blnc = Input::get('o_blnc');
        $o_blnc_trans = Input::get('o_blnc_trans');
        $operational = Input::get('operational');
        if ($operational==''):
            $operational=0;
        endif;
        $sent_code = $parent_code;

        $acttual_parent_code=Input::get('parent_code');

        if ($acttual_parent_code!=$parent_code):
            $max_id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \''.$parent_code.'\'')->id;
            if($max_id == '')
            {
                $code = $sent_code.'-1';
            }
            else
            {
                $max_code2 = DB::connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \''.$max_id.'\'')->code;
                $max_code2;
                $max = explode('-',$max_code2);
                $code = $sent_code.'-'.(end($max)+1);
            }

            $level_array = explode('-',$code);
            $counter = 1;
            foreach($level_array as $level):
                $data1['level'.$counter] = $level;
                $counter++;
            endforeach;
            $data1['code'] = $code;
            $data1['parent_code'] = $parent_code;

        endif;
        $data1['name'] = $acc_name;

        $data1['username'] 		 	= Auth::user()->name;

        $data1['update_date']     		  = date("Y-m-d");
        $data1['time']     		  = date("H:i:s");
        $data1['action']     		  = 'update';
        $data1['operational']		= $operational;


        $acc_id = DB::connection('mysql2')->table('accounts')->where('id',$id)->update($data1);


        //$acc_id = $data1->id;


      //  if ($acttual_parent_code!=$parent_code):
    //   $data2['acc_code']=	$code;
    //    endif;

        $code=FinanceHelper::getAccountCodeByAccId($id);
        $data2['acc_code']=	$code;
        $data2['debit_credit']=	$o_blnc_trans;
        $data2['amount'] 	  = 	$o_blnc;
        $data2['opening_bal'] 	  = 	1;
        $data2['username'] 		 	= Auth::user()->name;

        $data2['date']     		  = date("Y-m-d");
        $data2['v_date']     		= '2023-07-01';
        $data2['time']     		  = date("H:i:s");
        $data2['action']     		  = 'create';

        //  check data for transaction

       $count= DB::Connection('mysql2')->table('transactions')->where('acc_id',$id)->where('opening_bal',1)->count();
        if ($count>0):

        DB::connection('mysql2')->table('transactions')->where('acc_id',$id)->where('opening_bal',1)->update($data2);
        else:
            $data2['acc_id']=	$id;

            $data2['acc_code']=	$code;
            DB::Connection('mysql2')->table('transactions')->insert($data2);

            endif;

                    // for breakup data process
            $check_account=CommonHelper::supplier_account_exists($id);
            if ($check_account==true):
                $supplier_data=CommonHelper::get_supplier_id_by_account($id);
                $supplier_id=$supplier_data->id;
                $breakup_data['main_id']='opening'.$supplier_data->id;
                $breakup_data['debit_credit']=$o_blnc_trans;
                $breakup_data['amount']=$o_blnc;
                $breakup_data['supplier_id']=$supplier_id;
                $breakup_data['refrence_nature']=1;
                DB::Connection('mysql2')->table('breakup_data')->insert($breakup_data);
                endif;
                      // end breakup data


        FinanceHelper::reconnectMasterDatabase();

        $page_type='viewlist';
            FinanceHelper::audit_trail($code,'','',5,'Update');
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/viewChartofAccountList?pageType='.$page_type.'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    public function editCostCenterForm(Request $request,$id)
    {
        $name=$request->cost_center;
        $operational=$request->operational;
        if ($operational==''):
            $operational=0;
        endif;
        $cost_center=new CostCenter();
        $cost_center=$cost_center->SetConnection('mysql2');
        $cost_center=$cost_center->find($id);
        //$cost_center_count=$cost_center->where('status',1)->where('name',$name)->count();
        //     if ($cost_center_count >0):
        ////       Session::flash('dataDelete',$name.' '.'Already Exists.');
        //  return Redirect::to('finance/createCostCenterForm?pageType=add&&parentCode=82&&m=1#SFR');
        //  else:
        $parent_code = $request->parent_cost_center;
        $sent_code = $parent_code;


        $acttual_parent_code=Input::get('parent_code');
        if ($acttual_parent_code!=$parent_code):
            if ($request->first_level==1):

                $max_id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `cost_center` WHERE `first_level`=1')->id;
                $code=$max_id+1;
                $level=$code;
                $cost_center->level1=$level;
            else:
                $max_id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `cost_center` WHERE `parent_code` LIKE \''.$parent_code.'\'')->id;
                if($max_id == '')
                {
                    $code = $sent_code.'-1';
                }
                else
                {
                    $max_code2 = DB::connection('mysql2')->selectOne('SELECT `code`  FROM `cost_center` WHERE `id` LIKE \''.$max_id.'\'')->code;
                    $max_code2;
                    $max = explode('-',$max_code2);
                    $code = $sent_code.'-'.(end($max)+1);
                }
                $level_array = explode('-',$code);
                $counter = 1;
                foreach($level_array as $level):
                    $levell='level'.$counter.'';
                    $cost_center->$levell=$level;
                    $counter++;
                endforeach;
            endif;
            $cost_center->code = $code;
            $cost_center->parent_code = $parent_code;
        endif;
        // $cost_center->first_level = $request->first_level;
        $cost_center->name=$name;
        $cost_center->operational=$operational;
        $cost_center->username=Auth::user()->name;
        $cost_center->date=date('Y-m-d');
        $cost_center->save();

        return Redirect::to('finance/viewCostCenterList?pageType=view&&parentCode=82&&m=1#SFR');
        //     endif;

    }

    function editCashPaymentVoucherDetail(Request $request)
    {


        $id=$request->edit_id;
        $pvsSection = Input::get('pvsSection');

        foreach($pvsSection as $row)
        {


            $slip_no = Input::get('slip_no_'.$row);
            $pv_date = Input::get('pv_date_'.$row);
            $bill_date = Input::get('bill_date');
            $description = Input::get('description_'.$row);
            $voucher_no=Input::get('voucher_no');

            $data1['pv_date']   	= $pv_date;
            $data1['pv_no']   		= $voucher_no;
            $data1['slip_no']   	= $slip_no;
            $data1['voucherType'] 	= 1;
            $data1['description']   = $description;

            $data1['username'] 		= Auth::user()->name;
            $data1['bill_date'] 		= $bill_date;
            $data1['date'] 			= date('Y-m-d');
            $data1['time'] 			= date('H:i:s');
            //  $master_id = DB::Connection('mysql2')->table('pvs')->where('id',$id)->update($data1);
            $pvsDataSection = Input::get('pvsDataSection_'.$row);

            $count=1;

            DB::Connection('mysql2')->table('transactions')->where('master_id', $id)->delete();
            DB::Connection('mysql2')->table('pv_data')->where('master_id', $id)->delete();
            DB::Connection('mysql2')->table('department_allocation')->where('Main_master_id', $id)->delete();
            DB::Connection('mysql2')->table('cost_center_department_allocation')->where('Main_master_id', $id)->delete();
            foreach($pvsDataSection as $row1)

            {

                $d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
                $c_amount =  str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));
                $account  =  Input::get('account_id_'.$row.'_'.$row1.'');


                $sub_item  = Input::get('sub_item_id_'.$row.'_'.$row1.'');
                $qty  = Input::get('qty_'.$row.'_'.$row1.'');
                $rate  = Input::get('rate_'.$row.'_'.$row1.'');

                if ($account!=''):
                    if($d_amount >0)
                    {
                        $data2['debit_credit'] = 1;
                        $data2['amount'] = $d_amount;
                        $data['amount'] = $d_amount;
                        $data['debit_credit'] =1;
                    }
                    else if($c_amount >0)
                    {
                        $data2['debit_credit'] = 0;
                        $data2['amount'] = $c_amount;
                        $data['amount'] = $c_amount;
                        $data['debit_credit'] =0;
                    }

                    $data2['pv_no']   		= $voucher_no;
                    $data2['pv_date']   	= $pv_date;
                    $data2['acc_id'] 		= $account;
                    $data2['description']   = $description;
                    $data2['pv_status']   	= 1;
                    $data2['username'] 		= Auth::user()->name;
                    $data2['status']  		= 1;
                    $data2['date'] 			= date('Y-m-d');
                    $data2['time'] 			= date('H:i:s');
                    $data2['master_id']=$id;
                    $data2['qty']   = $qty;
                    $data2['rate']   = $rate;
                    $data2['sub_item']=$sub_item;

                    $other_id = DB::Connection('mysql2')->table('pv_data')->insertGetId($data2);


                    $data['acc_id'] = $account;
                    $data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
                    $data['particulars'] =$description;
                    $data['opening_bal'] = '0';
                    $data['voucher_no'] = $voucher_no;
                    $data['voucher_type'] = 2;
                    $data['v_date'] = $pv_date;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    $data['master_id'] = $id;
                    $data['username'] = Auth::user()->name;
                    //	DB::table('transactions')->insert($data);


                    // for department
                    if (Input::get('type')==1):
                        $allow_null = $request->input('dept_check_box' . $count);
                        if ($allow_null == 0):
                            $department1 = $request->input('department' . $count);
                            $counter = 0;
                            foreach ($department1 as $row1):

                                $dept_allocation1 = new DepartmentAllocation1();
                                $dept_allocation1 = $dept_allocation1->SetConnection('mysql2');
                                $dept_allocation1->Main_master_id = $id;
                                $dept_allocation1->master_id = $other_id;
                                $dept_allocation1->pv_no = $voucher_no;
                                $dept_allocation1->type = 4;
                                $dept_allocation1->dept_id = $row1;
                                $perccent = $request->input('percent' . $count);
                                $dept_allocation1->percent = $perccent[$counter];
                                $amount = $request->input('department_amount' . $count);
                                $amount = str_replace(",", "", $amount);
                                $dept_allocation1->amount = $amount[$counter];
                                $dept_allocation1->item = $request->input('sub_item_id_1_' . $count);
                                if ($amount[$counter]>0):
                                    $dept_allocation1->save();
                                endif;
                                $counter++;

                            endforeach;
                        endif;

                        // end for department





                        // for Cost center department

                        $allow_null = $request->input('cost_center_check_box' . $count);
                        if ($allow_null == 0):
                            $sales_tax_department = $request->input('cost_center_department' . $count);
                            $counter = 0;
                            foreach ($sales_tax_department as $row3):
                                $costcenter = new CostCenterDepartmentAllocation();
                                $costcenter = $costcenter->SetConnection('mysql2');
                                $costcenter->Main_master_id = $id;
                                $costcenter->master_id = $other_id;
                                $costcenter->pv_no = $voucher_no;
                                $costcenter->type = 4;
                                $costcenter->dept_id = $row3;
                                $perccent = $request->input('cost_center_percent' . $count);
                                $costcenter->percent = $perccent[$counter];
                                $amount = $request->input('cost_center_department_amount' . $count);
                                $amount = str_replace(",", "", $amount);
                                if ($amount[$counter] != ''):
                                    $costcenter->amount = $amount[$counter];
                                endif;
                                $costcenter->item = $request->input('sub_item_id_1_' . $count);
                                if ($amount[$counter]>0):
                                    $costcenter->save();
                                endif;
                                $counter++;

                            endforeach;
                        endif;
                    endif;
                    // End for Cost center department

                endif;
                $count++;
            }

        }

        Session::flash('dataInsert','successfully saved.');

     return Redirect::to('finance/viewCashPaymentVoucherList?pageType=viewlist&&parentCode=21&&m='.$_GET['m'].'#SFR');
    }

    function editBankReceiptVoucherForm(request $request){
//        print_r($_POST);
//        die();
        $indent= $request->indent;

        $rvs_id = $request->input('rvs_id');
        $rvs = new Rvs();
        $rvs = $rvs->SetConnection('mysql2');
        $rvs = $rvs->find($rvs_id);

        $rvs->rv_date = $request->input('rv_date_1');
        $rvs->slip_no = $request->input('slip_no_1');
        $rvs->cheque_no = $request->input('cheque_no_1');
        $rvs->cheque_date = $request->input('cheque_date_1');
        $rvs->description = $request->input('description_1');
        if ($indent==1):

        $rvs->currency_id = $request->input('curren');
        $rvs->foreign_currency = $request->input('foreign_currency');
        $rvs->exchange_rate = $request->input('exchange_rate');
        else:

            $rvs->currency_id=0;
            $rvs->exchange_rate=0;
            $rvs->foreign_currency=0;
            endif;
        $rvs->rv_status = 2;
        $rvs->voucherType = 2;
        $rvs->username = Auth::user()->name;
        $rvs->status = 1;
        $rvs->date = date("Y-m-d");
        $rvs->time = date("H:i:s");
        $rvs->save();

        $rvs_data1 = new Rvs_data();
        $rvs_data1 = $rvs_data1->SetConnection('mysql2');

        $rvs_data1 = $rvs_data1->find($rvs_id);
        $rvs_data1->delete();
        DB::Connection('mysql2')->table('transactions')->where('master_id',$rvs_id)->where('voucher_type',3)->delete();

        $rvsDataSection_1 = Input::get('rvsDataSection_1');

        foreach($rvsDataSection_1 as $value):
            $rvs_data = new Rvs_data();
            $rvs_data = $rvs_data->SetConnection('mysql2');

            $d_amount = $request->input('d_amount_1_'.$value);
            $d_amount = CommonHelper::check_str_replace($d_amount);
            $c_amount =  $request->input('c_amount_1_'.$value);
            $c_amount = CommonHelper::check_str_replace($c_amount);

            if($d_amount > 0)
            {
                $rvs_data->debit_credit = 1;
                $rvs_data->amount = $d_amount;
                $data['amount'] = $d_amount;
            }
            elseif($c_amount > 0)
            {
                $rvs_data->debit_credit = 0;
                $rvs_data->amount = $c_amount;
                $data['amount'] = $c_amount;
            }

            $rvs_data->rv_no = $request->input('rv_no');
            $rvs_data->rv_date = $request->input('rv_date_1');
            $rvs_data->acc_id = $request->input('account_id_1_'.$value);
            $rvs_data->description = $request->input('description_1');
            $rvs_data->rv_status = 2;
            $rvs_data->status = 1;
            $rvs_data->username = Auth::user()->name;
            $rvs_data->date = date('Y-m-d');
            $rvs_data->time = date('H:i:s');
            $rvs_data->master_id = $rvs_id;
            $rvs_data->save();


            $data['acc_id'] = $request->input('account_id_1_'.$value);
            //	$data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
            $data['particulars'] =$request->input('description_1');
            $data['opening_bal'] = '0';
            $data['voucher_no'] = $request->input('rv_no');
            $data['voucher_type'] = 3;
            $data['v_date'] = $request->input('rv_date_1');
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $data['master_id'] = $rvs_id;

            $data['username'] = Auth::user()->name;
            DB::Connection('mysql2')->table('transactions')->insert($data);

        endforeach;

        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('finance/viewBankReceiptVoucherList?pageType=viewlist&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    function updateJournalVoucherDetail(Request $request)
    {
        $id=$request->edit_id;
        $jvsSection = Input::get('jvsSection');

        foreach($jvsSection as $row)
        {


            $slip_no = Input::get('slip_no_'.$row);
            $jv_date = Input::get('jv_date_'.$row);
            $bill_date = Input::get('bill_date');
            $description = Input::get('description_'.$row);
            $voucher_no=Input::get('voucher_no');

            $data1['jv_date']       = $jv_date;
            $data1['jv_no']         = $voucher_no;
            $data1['slip_no']       = $slip_no;
            $data1['voucherType']   = 1;
            $data1['description']   = $description;

            $data1['username']      = Auth::user()->name;
            $data1['bill_date']         = $bill_date;
            $data1['date']          = date('Y-m-d');
            $data1['time']          = date('H:i:s');
            DB::Connection('mysql2')->table('jvs')->where('id',$id)->update($data1);

            $jvsDataSection = Input::get('jvsDataSection_'.$row);

            $count=1;

            DB::Connection('mysql2')->table('transactions')->where('master_id', $id)->delete();
            DB::Connection('mysql2')->table('jv_data')->where('master_id', $id)->delete();
            DB::Connection('mysql2')->table('department_allocation')->where('Main_master_id', $id)->delete();
            DB::Connection('mysql2')->table('cost_center_department_allocation')->where('Main_master_id', $id)->delete();
            DB::Connection('mysql2')->table('transactions')->where('master_id', $id)->where('voucher_type',1)->delete();
            foreach($jvsDataSection as $row1)

            {

                $d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
                $c_amount =  str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));
                $account  =  Input::get('account_id_'.$row.'_'.$row1.'');

                $account=explode('~',$account);

                $supplier=$account[1];
                if ($supplier==1):
                    $jvs['supplier_id']=$account[2];
                    $jvs['purchase']=1;
                    $jvs['total_net_amount']=$c_amount;
                    DB::Connection('mysql2')->table('jvs')->where('id', $id)->update($jvs);
                endif;
                $account=$account[0];
                $sub_item  = Input::get('sub_item_id_'.$row.'_'.$row1.'');
                $qty  = Input::get('qty_'.$row.'_'.$row1.'');
                $rate  = Input::get('rate_'.$row.'_'.$row1.'');

                if ($account!=''):
                    if($d_amount >0)
                    {
                        $data2['debit_credit'] = 1;
                        $data2['amount'] = $d_amount;
                        $data['amount'] = $d_amount;
                        $data['debit_credit'] =1;
                    }
                    else if($c_amount >0)
                    {
                        $data2['debit_credit'] = 0;
                        $data2['amount'] = $c_amount;
                        $data['amount'] = $c_amount;
                        $data['debit_credit'] =0;
                    }

                    $data2['jv_no']         = $voucher_no;
                    $data2['jv_date']       = $jv_date;
                    $data2['acc_id']        = $account;
                    $data2['description']   = $description;
                    $data2['jv_status']     = 1;
                    $data2['username']      = Auth::user()->name;
                    $data2['status']        = 1;
                    $data2['date']          = date('Y-m-d');
                    $data2['time']          = date('H:i:s');
                    $data2['master_id']=$id;
                    $data2['qty']   = $qty;
                    $data2['rate']   = $rate;
                    $data2['item_id']=$sub_item;

                    $other_id = DB::Connection('mysql2')->table('jv_data')->insertGetId($data2);


                    $data['acc_id'] = $account;
                    $data['particulars'] =$description;
                    $data['opening_bal'] = '0';
                    $data['voucher_no'] = $voucher_no;
                    $data['voucher_type'] = 1;
                    $data['v_date'] = $jv_date;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    $data['action'] = 1;
                    $data['master_id'] = $id;
                    $data['username'] = Auth::user()->name;
                     DB::Connection('mysql2')->table('transactions')->insert($data);



                    // for department
                    if (Input::get('type')==1):
                        $allow_null = $request->input('dept_check_box' . $row1);
                        if ($allow_null == 0):
                            $department1 = $request->input('department' . $row1);
                            $counter = 0;
                            foreach ($department1 as $row2):

                                $dept_allocation1 = new DepartmentAllocation1();
                                $dept_allocation1 = $dept_allocation1->SetConnection('mysql2');
                                $dept_allocation1->Main_master_id = $id;
                                $dept_allocation1->master_id = $other_id;
                                $dept_allocation1->pv_no = $voucher_no;
                                $dept_allocation1->type = 5;
                                $dept_allocation1->dept_id = $row2;
                                $perccent = $request->input('percent' . $row1);
                                $dept_allocation1->percent = $perccent[$counter];
                                $amount = $request->input('department_amount' . $row1);
                                $amount = str_replace(",", "", $amount);
                                $dept_allocation1->amount = $amount[$counter];
                                $dept_allocation1->item = $request->input('sub_item_id_1_' . $row1);
                                if ($amount[$counter]>0):
                                    $dept_allocation1->save();
                                endif;
                                $counter++;

                            endforeach;
                        endif;

                        // end for department





                        // for Cost center department

                        $allow_null = $request->input('cost_center_check_box' . $row1);
                        if ($allow_null == 0):
                            $sales_tax_department = $request->input('cost_center_department' . $row1);
                            $counter = 0;
                            foreach ($sales_tax_department as $row3):
                                $costcenter = new CostCenterDepartmentAllocation();
                                $costcenter = $costcenter->SetConnection('mysql2');
                                $costcenter->Main_master_id = $id;
                                $costcenter->master_id = $other_id;
                                $costcenter->pv_no = $voucher_no;
                                $costcenter->type = 5;
                                $costcenter->dept_id = $row3;
                                $perccent = $request->input('cost_center_percent' . $row1);
                                $costcenter->percent = $perccent[$counter];
                                $amount = $request->input('cost_center_department_amount' . $row1);
                                $amount = str_replace(",", "", $amount);
                                if ($amount[$counter] != ''):
                                    $costcenter->amount = $amount[$counter];
                                endif;
                                $costcenter->item = $request->input('sub_item_id_1_' . $row1);
                                if ($amount[$counter]>0):
                                    $costcenter->save();
                                endif;
                                $counter++;

                            endforeach;
                        endif;
                    endif;
                    // End for Cost center department

                endif;
                $count++;
            }
            date_default_timezone_set('Asia/karachi');
            $InsertData['jv_id'] = $id;
            $InsertData['jv_no'] = $voucher_no;
            $InsertData['username'] = Auth::user()->name;
            $InsertData['date'] = date('Y-m-d');
            $InsertData['time'] = date('H:i:s');
            $InsertData['activity_type'] = 1;//Type 1 for Updated
            DB::Connection('mysql2')->table('jvs_activity')->insert($InsertData);
        }

        Session::flash('dataInsert','successfully saved.');

        return Redirect::to('finance/viewJournalVoucherList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    //Update Bank Payment Voucher Detail
    function updateBankPaymentVoucherDetail(Request $request)
    {
        $id=$request->edit_id;
        $pvsSection = Input::get('pvsSection');

        foreach($pvsSection as $row)
        {

            $slip_no = Input::get('slip_no_'.$row);
            $pv_no = Input::get('pv_no');
            $pv_date = Input::get('pv_date_'.$row);
            $cheque_no = Input::get('cheque_no_'.$row);
            $cheque_date = Input::get('cheque_date_'.$row);
            $description = Input::get('description_'.$row);
            $cheque_status=Input::get('with_cheque');

            $data1['pv_date']   	= $pv_date;
            $data1['slip_no']   	= $slip_no;
            $data1['bill_date']   	= Input::get('bill_date');
            $data1['with_items']   = Input::get('items');
            $data1['with_cheque']   	= $cheque_status;
            if ($cheque_status==0):
                $data1['cheque_no']   		= $cheque_no;
                $data1['cheque_date']   	= $cheque_date;
            endif;
            $data1['voucherType'] 	= 2;
            $data1['description']   = $description;
            $data1['username'] 		= Auth::user()->name;
            $data1['pv_status']  	= 1;
            $data1['date'] 			= date('Y-m-d');
            $data1['time'] 			= date('H:i:s');
            $data1['payment_type'] 			=Input::get('type');
            DB::Connection('mysql2')->table('pvs')->where('id',$id)->update($data1);

            $pvsDataSection = Input::get('pvsDataSection_'.$row);

            $count=1;

            DB::Connection('mysql2')->table('transactions')->where('master_id', $id)->delete();
            DB::Connection('mysql2')->table('pv_data')->where('master_id', $id)->delete();
            DB::Connection('mysql2')->table('department_allocation')->where('Main_master_id', $id)->delete();
            DB::Connection('mysql2')->table('cost_center_department_allocation')->where('Main_master_id', $id)->delete();
            foreach($pvsDataSection as $row1)

            {

                $d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
                $c_amount =  str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));
                $account  =  Input::get('account_id_'.$row.'_'.$row1.'');


                $sub_item  = Input::get('sub_item_id_'.$row.'_'.$row1.'');
                $qty  = str_replace(',','',Input::get('qty_'.$row.'_'.$row1.''));
                $rate  = str_replace(',','',Input::get('rate_'.$row.'_'.$row1.''));

                if ($account!=''):
                    if($d_amount >0)
                    {
                        $data2['debit_credit'] = 1;
                        $data2['amount'] = $d_amount;
                        $data['amount'] = $d_amount;
                        $data['debit_credit'] =1;
                    }
                    else if($c_amount >0)
                    {
                        $data2['debit_credit'] = 0;
                        $data2['amount'] = $c_amount;
                        $data['amount'] = $c_amount;
                        $data['debit_credit'] =0;
                    }

                    $data2['pv_no']         = $pv_no;
                    $data2['pv_date']       = $pv_date;
                    $data2['acc_id']        = $account;
                    $data2['description']   = $description;
                    $data2['pv_status']     = 1;
                    $data2['username']      = Auth::user()->name;
                    $data2['status']        = 1;
                    $data2['date']          = date('Y-m-d');
                    $data2['time']          = date('H:i:s');
                    $data2['master_id']=$id;
                    $data2['qty']   = $qty;
                    $data2['rate']   = $rate;
                    $data2['sub_item']=$sub_item;

                    $other_id = DB::Connection('mysql2')->table('pv_data')->insertGetId($data2);


                    $data['acc_id'] = $account;
                    $data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
                    $data['particulars'] =$description;
                    $data['opening_bal'] = '0';
                    $data['voucher_no'] = $pv_no;
                    $data['voucher_type'] = 2;
                    $data['v_date'] = $pv_date;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    $data['master_id'] = $id;
                    $data['username'] = Auth::user()->name;
                    //  DB::table('transactions')->insert($data);



                    // for department
                    if (Input::get('type')==1):
                        $allow_null = $request->input('dept_check_box' . $count);
                        if ($allow_null == 0):
                            $department1 = $request->input('department' . $count);
                            $counter = 0;
                            foreach ($department1 as $row1):

                                $dept_allocation1 = new DepartmentAllocation1();
                                $dept_allocation1 = $dept_allocation1->SetConnection('mysql2');
                                $dept_allocation1->Main_master_id = $id;
                                $dept_allocation1->master_id = $other_id;
                                $dept_allocation1->pv_no = $pv_no;
                                $dept_allocation1->type = 3;
                                $dept_allocation1->dept_id = $row1;
                                $perccent = $request->input('percent' . $count);
                                $dept_allocation1->percent = $perccent[$counter];
                                $amount = $request->input('department_amount' . $count);
                                $amount = str_replace(",", "", $amount);
                                $dept_allocation1->amount = $amount[$counter];
                                $dept_allocation1->item = $request->input('sub_item_id_1_' . $count);
                                if ($amount[$counter]>0):
                                    $dept_allocation1->save();
                                endif;
                                $counter++;

                            endforeach;
                        endif;

                        // end for department





                        // for Cost center department

                        $allow_null = $request->input('cost_center_check_box' . $count);
                        if ($allow_null == 0):
                            $sales_tax_department = $request->input('cost_center_department' . $count);
                            $counter = 0;
                            foreach ($sales_tax_department as $row3):
                                $costcenter = new CostCenterDepartmentAllocation();
                                $costcenter = $costcenter->SetConnection('mysql2');
                                $costcenter->Main_master_id = $id;
                                $costcenter->master_id = $other_id;
                                $costcenter->pv_no = $pv_no;
                                $costcenter->type = 3;
                                $costcenter->dept_id = $row3;
                                $perccent = $request->input('cost_center_percent' . $count);
                                $costcenter->percent = $perccent[$counter];
                                $amount = $request->input('cost_center_department_amount' . $count);
                                $amount = str_replace(",", "", $amount);
                                if ($amount[$counter] != ''):
                                    $costcenter->amount = $amount[$counter];
                                endif;
                                $costcenter->item = $request->input('sub_item_id_1_' . $count);
                                if ($amount[$counter]>0):
                                    $costcenter->save();
                                endif;
                                $counter++;

                            endforeach;
                        endif;
                    endif;
                    // End for Cost center department

                endif;
                $count++;
            }
            date_default_timezone_set('Asia/karachi');
            $InsertData['pv_id'] = $id;
            $InsertData['pv_no'] = $pv_no;
            $InsertData['username'] = Auth::user()->name;
            $InsertData['date'] = date('Y-m-d');
            $InsertData['time'] = date('H:i:s');
            $InsertData['activity_type'] = 1;//Type 2 for Updated
            DB::Connection('mysql2')->table('pvs_activity')->insert($InsertData);
        }

        Session::flash('dataInsert','successfully saved.');

        return Redirect::to('finance/viewBankPaymentVoucherList?pageType=viewlist&&parentCode=22&&m='.$_GET['m'].'#SFR');
    }

    function updateContraVoucherDetail(Request $request)
    {
        $id=$request->edit_id;
        $cvsSection = Input::get('cvsSection');

        foreach($cvsSection as $row)
        {

            $cv_date = Input::get('rv_date_'.$row);
            $cheque_no = Input::get('cheque_no_'.$row);
            $cheque_date = Input::get('cheque_date_'.$row);
            $description = Input::get('description_'.$row);
            $ContraNo = Input::get('ContraNo');


            $data1['cv_date']   	= $cv_date;
            $data1['cheque_no']   		= $cheque_no;
            $data1['cheque_date']   	= $cheque_date;

            $data1['description']   = $description;



            DB::Connection('mysql2')->table('contra')->where('id',$id)->update($data1);
            DB::Connection('mysql2')->table('transactions')->where('master_id', $id)->delete();
            DB::Connection('mysql2')->table('contra_data')->where('master_id', $id)->delete();

            $cvsDataSection = Input::get('rvsDataSection_'.$row);

            foreach($cvsDataSection as $row1)
            {
                $d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
                $c_amount =  str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));
                $account  =  Input::get('account_id_'.$row.'_'.$row1.'');

                if ($account!=''):
                    if($d_amount >0)
                    {
                        $data2['debit_credit'] = 1;
                        $data2['amount'] = $d_amount;
                        $data['amount'] = $d_amount;
                        $data['debit_credit'] =1;
                    }
                    else if($c_amount >0)
                    {
                        $data2['debit_credit'] = 0;
                        $data2['amount'] = $c_amount;
                        $data['amount'] = $c_amount;
                        $data['debit_credit'] =0;
                    }

                    $data2['cv_no']         = $ContraNo;
                    $data2['cv_date']       = $cv_date;
                    $data2['acc_id']        = $account;
                    $data2['description']   = $description;
                    $data2['rv_status']     = 1;
                    $data2['username']      = Auth::user()->name;
                    $data2['status']        = 1;
                    $data2['date']          = date('Y-m-d');
                    $data2['time']          = date('H:i:s');
                    $data2['master_id']=$id;
                    $other_id = DB::Connection('mysql2')->table('contra_data')->insertGetId($data2);


                    $data['acc_id'] = $account;
                    $data['particulars'] =$description;
                    $data['opening_bal'] = '0';
                    $data['voucher_no'] = $ContraNo;
                    $data['voucher_type'] = 6;
                    $data['v_date'] = $cv_date;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    $data['action'] = 1;
                    $data['master_id'] = $id;
                    $data['username'] = Auth::user()->name;
                    DB::Connection('mysql2')->table('transactions')->insert($data);
                endif;

            }


            date_default_timezone_set('Asia/karachi');
            $InsertData['cv_id'] = $id;
            $InsertData['cv_no'] = $ContraNo;
            $InsertData['username'] = Auth::user()->name;
            $InsertData['date'] = date('Y-m-d');
            $InsertData['time'] = date('H:i:s');
            $InsertData['activity_type'] = 1;//Type 1 for Updated
            DB::Connection('mysql2')->table('contra_activity')->insert($InsertData);
        }

//        die();

        Session::flash('dataInsert','successfully saved.');

        return Redirect::to('finance/viewContraVoucherList?pageType=view&&parentCode=88&&m=1#SFR');
    }
}
