<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transactions;
use App\Models\CostCenter;
use DB;
use Config;
use Redirect;
use Session;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use Auth;
use Input;
use App\Models\Employee;

class FinanceDeleteController extends Controller
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
    public function deleteCompanyFinanceTwoTableRecords(){
		$m = $_GET['m'];
        FinanceHelper::companyDatabaseConnection($m);
        $voucherStatus = $_GET['voucherStatus'];
        $rowStatus = $_GET['rowStatus'];
        $columnValue = $_GET['columnValue'];
        $columnOne = $_GET['columnOne'];
        $columnTwo = $_GET['columnTwo'];
        $columnThree = $_GET['columnThree'];
        $tableOne = $_GET['tableOne'];
        $tableTwo = $_GET['tableTwo'];
        

        $updateDetails = array(
            $columnThree => 2,
            'delete_username' => Auth::user()->name
        );

        DB::table($tableOne)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        DB::table($tableTwo)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        Session::flash('dataDelete','successfully delete.');
        FinanceHelper::reconnectMasterDatabase();
    }

    public function repostCompanyFinanceTwoTableRecords(){
        $m = $_GET['m'];
        FinanceHelper::companyDatabaseConnection($m);
        $voucherStatus = $_GET['voucherStatus'];
        $rowStatus = $_GET['rowStatus'];
        $columnValue = $_GET['columnValue'];
        $columnOne = $_GET['columnOne'];
        $columnTwo = $_GET['columnTwo'];
        $columnThree = $_GET['columnThree'];
        $tableOne = $_GET['tableOne'];
        $tableTwo = $_GET['tableTwo'];
        

        $updateDetails = array(
            $columnThree => 1,
            'delete_username' => ''
        );

        DB::table($tableOne)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        DB::table($tableTwo)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        Session::flash('dataRepost','successfully repost.');
        FinanceHelper::reconnectMasterDatabase();
    }

    public function approveCompanyFinanceTwoTableRecords(){

        $m = $_GET['m'];
        FinanceHelper::companyDatabaseConnection($m);
        $voucherStatus = $_GET['voucherStatus'];
        $rowStatus = $_GET['rowStatus'];
        $columnValue = $_GET['columnValue'];
        $columnOne = $_GET['columnOne'];
        $columnTwo = $_GET['columnTwo'];
        $columnThree = $_GET['columnThree'];
        $tableOne = $_GET['tableOne'];
        $tableTwo = $_GET['tableTwo'];
        

        $updateDetails = array(
            $columnTwo => 2,
            'approve_username' => Auth::user()->name
        );

        DB::table($tableOne)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        DB::table($tableTwo)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        $tableTwoDetail = DB::table($tableTwo)
        ->where($columnOne, $columnValue)
        ->where($columnTwo, '2')->tosql();
        die;
        FinanceHelper::reconnectMasterDatabase();

        foreach ($tableTwoDetail as $row)
        {
            if($tableOne == 'pvs'){
                $vouceherType = 2;
                $voucherNo = $row->pv_no;
                $voucherDate = $row->pv_date;
            }else if($tableOne == 'rvs'){
                $vouceherType = 3;
                $voucherNo = $row->rv_no;
                $voucherDate = $row->rv_date;
            }else if($tableOne == 'jvs'){
                $vouceherType = 1;
                $voucherNo = $row->jv_no;
                $voucherDate = $row->jv_date;
            }
            $data['acc_id'] = $row->acc_id;
            CommonHelper::reconnectMasterDatabase();
           $data['acc_code'] =0;
            CommonHelper::companyDatabaseConnection($_GET['m']);
            $data['particulars'] = $row->description;
            $data['opening_bal'] = '0';
            $data['debit_credit'] = $row->debit_credit;
            $data['amount'] = $row->amount;
            $data['voucher_no'] = $voucherNo;
            $data['voucher_type'] = $vouceherType;
            $data['v_date'] = $voucherDate;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $data['username'] = Auth::user()->name;

            DB::table('transactions')->insert($data);
            FinanceHelper::reconnectMasterDatabase();
        }
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataApprove','successfully approve.');
        
    }


    public function deleteCompanyFinanceThreeTableRecords(){
        $m = $_GET['m'];
        FinanceHelper::companyDatabaseConnection($m);
        $voucherStatus = $_GET['voucherStatus'];
        $rowStatus = $_GET['rowStatus'];
        $columnValue = $_GET['columnValue'];
        $columnOne = $_GET['columnOne'];
        $columnTwo = $_GET['columnTwo'];
        $columnThree = $_GET['columnThree'];
        $tableOne = $_GET['tableOne'];
        $tableTwo = $_GET['tableTwo'];
        $tableThree = $_GET['tableThree'];
        

        $updateDetails = array(
            $columnThree => 2,
            'delete_username' => Auth::user()->name
        );

        DB::table($tableOne)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        DB::table($tableTwo)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        DB::table($tableThree)
        ->where('voucher_no', $columnValue)
        ->update($updateDetails);

        Session::flash('dataDelete','successfully delete.');
        FinanceHelper::reconnectMasterDatabase();
    }


    public function repostCompanyFinanceThreeTableRecords(){
        $m = $_GET['m'];
        FinanceHelper::companyDatabaseConnection($m);
        $voucherStatus = $_GET['voucherStatus'];
        $rowStatus = $_GET['rowStatus'];
        $columnValue = $_GET['columnValue'];
        $columnOne = $_GET['columnOne'];
        $columnTwo = $_GET['columnTwo'];
        $columnThree = $_GET['columnThree'];
        $tableOne = $_GET['tableOne'];
        $tableTwo = $_GET['tableTwo'];
        $tableThree = $_GET['tableThree'];
        

        $updateDetails = array(
            $columnThree => 1,
            'delete_username' => ''
        );

        DB::table($tableOne)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        DB::table($tableTwo)
        ->where($columnOne, $columnValue)
        ->update($updateDetails);

        DB::table($tableThree)
        ->where('voucher_no', $columnValue)
        ->update($updateDetails);

        Session::flash('dataRepost','successfully repost.');
        FinanceHelper::reconnectMasterDatabase();
    }

    public function deleteEmployeeTax(Request $request)
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $Employee = Employee::find(Input::get('emp_id'));
        $Employee->tax_id = 0;
        $Employee->save();
        CommonHelper::reconnectMasterDatabase();
    }
    public function deleteEmployeeEOBI(Request $request)
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $Employee = Employee::find(Input::get('emp_id'));
        $Employee->eobi_id = 0;
        $Employee->save();
        CommonHelper::reconnectMasterDatabase();
    }
    public function deletechartofaccount()
    {
        DB::Connection('mysql2')->beginTransaction();

        try {
                $id= Input::get('id');
                $accounts=new Account();
                $data['status']=0;
                $data['delete_date']=date('Y-m-d');
                $accounts=$accounts->setConnection('mysql2');
                $Acc = $accounts->where('id','=',$id)->first();
                $accounts->where('id','=',$id) ->update($data);
                $data1['status']=0;
                $transaction=new Transactions();
                $transaction=$transaction->setConnection('mysql2');
                $transaction->where('acc_id','=',$id)->update($data1);
                FinanceHelper::audit_trail($Acc->code,'','',5,'Delete');
                DB::Connection('mysql2')->commit();
            }
            catch(\Exception $e)
            {
                DB::Connection('mysql2')->rollback();
                echo "EROOR"; //die();
                dd($e->getMessage());
            }

    }

    public function deletecostcenter(Request $request)
    {

        $id=$request->id;
        $cost_center=new CostCenter();
        $cost_center=$cost_center->SetConnection('mysql2');
        $cost_center=$cost_center->find($id);
        $cost_center->status=0;
        $cost_center->delete_date=date('Y-m-d');
        $cost_center->delete_username=Auth::user()->name;
        $cost_center->save();

    }
}
