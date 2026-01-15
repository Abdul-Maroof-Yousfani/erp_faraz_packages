<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Config;
use Redirect;
use Session;
use Auth;
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\NotificationHelper;


class StoreDeleteController extends Controller
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

    public function deleteCompanyStoreThreeTableRecords(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
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
            $columnTwo => 2,
            'delete_username' => Auth::user()->name
        );

        DB::table($tableOne)
            ->where($columnOne, $columnValue)
            ->update($updateDetails);

        DB::table($tableTwo)
            ->where($columnOne, $columnValue)
            ->update($updateDetails);

        DB::table($tableThree)
            ->where($columnThree, $columnValue)
            ->delete();


        CommonHelper::reconnectMasterDatabase();
        //Session::flash('dataApprove','successfully approve.');
    }

    public function repostCompanyStoreThreeTableRecords(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $voucherStatus = $_GET['voucherStatus'];
        $rowStatus = $_GET['rowStatus'];
        $columnValue = $_GET['columnValue'];
        $columnOne = $_GET['columnOne'];
        $columnTwo = $_GET['columnTwo'];
        $columnThree = $_GET['columnThree'];
        $columnFour = $_GET['columnFour'];
        $tableOne = $_GET['tableOne'];
        $tableTwo = $_GET['tableTwo'];
        $tableThree = $_GET['tableThree'];


        $updateDetails = array(
            $columnTwo => 1,
            'delete_username' => ''
        );

        DB::table($tableOne)
            ->where($columnOne, $columnValue)
            ->update($updateDetails);

        DB::table($tableTwo)
            ->where($columnOne, $columnValue)
            ->update($updateDetails);

        $secondTableRecord = DB::table($tableTwo)->where($columnOne, $columnValue)->where('status','=', '1')->get();
        //return print($secondTableRecord);
        foreach ($secondTableRecord as $row){
            if($columnThree == 'demand_no'){
                $action = '3';
                $qty = $row->qty;
                $mcOne = '';
                $mcTwo = '';
                $mcThree = '';
                $mcFour = '';
            }else if($columnThree == 'sc_no'){
                $action = '2';
                $qty = $row->issue_qty;
                $mcOne = '';
                $mcTwo = '';
                $mcThree = '';
                $mcFour = '';
            }else if($columnThree == 'scr_no'){
                $action = '4';
                $qty = $row->return_qty;
                $data[$columnThree] = $row->store_challan_return_no;
                $data[$columnFour] = $row->store_challan_return_date;
                $data['sc_no'] = $row->store_challan_no;
                $data['sc_date'] = $row->store_challan_date;

            }
            $data['main_ic_id'] = $row->category_id;
            $data['sub_ic_id'] = $row->sub_item_id;
            $data['main_ic_id'] = $row->category_id;
            $data['sub_ic_id'] = $row->sub_item_id;
            $data['qty'] = $qty;
            $data['action'] = $action;
            $data['status'] = 1;
            $data['username'] = Auth::user()->name;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $data['company_id'] = $m;
            DB::table($tableThree)->insert($data);
        }

        CommonHelper::reconnectMasterDatabase();
    }

    public function approvePurchaseRequest(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);

         $purchase_order_id=$_GET['columnValue'];
        $data=array
        (
            'purchase_request_status'=>2,
            'approve_username'=>Auth::user()->name
        );
       DB::table('purchase_request')->where('id',$purchase_order_id)->update($data);
      $po_no = DB::table('purchase_request')->where('id',$purchase_order_id)->value('purchase_request_no');

        DB::table('purchase_request_data')->where('master_id',$purchase_order_id)->update($data);
        $pr_no=DB::table('purchase_request_data')->where('master_id',$purchase_order_id)->value('demand_no');

        $dept_and_pType = NotificationHelper::get_dept_id('purchase_request','id',$purchase_order_id)->select('sub_department_id','p_type')->first();
        $dept_id= $dept_and_pType->sub_department_id;
        $p_type= $dept_and_pType->p_type;
        $subject = 'Purchase Order Approved For '.$pr_no;
        NotificationHelper::send_email('Purchase Order','Approve',$dept_id,$po_no,$subject,$p_type);

        /*
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

        $firstTableRecord = DB::table($tableOne)->where($columnOne, $columnValue)->where('status','=', '1')->first();
        $secondTableRecord = DB::table($tableTwo)->where($columnOne, $columnValue)->where('status','=', '1')->get();
        $supplierId = $firstTableRecord->supplier_id;
        CommonHelper::reconnectMasterDatabase();
        $supplierAccId = CommonHelper::getAccountIdByMasterTable($m,$firstTableRecord->supplier_id,'supplier');
        CommonHelper::companyDatabaseConnection($m);
        $description = $firstTableRecord->description;
        $slipNo = $firstTableRecord->slip_no;
        $prNo = $columnValue;
        $prDate = $firstTableRecord->purchase_request_date;
        $str = DB::selectOne("select max(convert(substr(`jv_no`,3,length(substr(`jv_no`,3))-4),signed integer)) reg from `jvs` where substr(`jv_no`,-4,2) = ".date('m')." and substr(`jv_no`,-2,2) = ".date('y')."")->reg;
        $jv_no = 'jv'.($str+1).date('my');

        $data1['jv_date']   	    = date('Y-m-d');
        $data1['jv_no']   		    = $jv_no;
        $data1['pr_date']   	    = $prDate;
        $data1['pr_no']   		    = $prNo;
        $data1['slip_no']   	    = $slipNo;
        $data1['voucherType'] 	    = 2;
        $data1['description']       = '('.$description.') * (Purchase Request No => '.$prNo.') * (Purchase Request Date => '.$prDate.') * (Slip No => '.$slipNo.')';
        $data1['jv_status']  	    = 2;
        $data1['username'] 		    = Auth::user()->name;
        $data1['approve_username'] 	= Auth::user()->name;
        $data1['date'] 			    = date('Y-m-d');
        $data1['time'] 			    = date('H:i:s');
        //DB::table('jvs')->insert($data1);
        $master_id = DB::table('jvs')->insertGetId($data1);
        $overAllSubTotal = 0;
        $jvDataDescription = '';
        foreach ($secondTableRecord as $row)

        {
            $demandNo = $row->demand_no;
            $demandDate = $row->demand_date;
            $categoryId = $row->category_id;
            $subItemId = $row->sub_item_id;
            $qty = $row->purchase_request_qty;
            $rate = $row->rate;
            $subTotal = $row->sub_total;
            $overAllSubTotal += $subTotal;
            $jvDataDescription = '(Demand No => '.$demandNo.')*(Demand Date => '.$demandDate.')';
            CommonHelper::reconnectMasterDatabase();
            $subItemAccId = CommonHelper::getAccountIdByMasterTable($m,$row->sub_item_id,'subitem');
            CommonHelper::companyDatabaseConnection($m);
            $data2['debit_credit'] = 1;
            $data2['amount'] = $subTotal;
            $data2['jv_no']   		= $jv_no;
            $data2['jv_date']   	= date('Y-m-d');
            $data2['acc_id'] 		= $subItemAccId;
            $data2['description']   = $jvDataDescription;
            $data2['jv_status']   	= 2;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');
            $data2['master_id'] 			= $master_id;

        DB::table('jv_data')->insert($data2);
           // $master_id = DB::table('jvs')->insertGetId($data2);

        }

        $data6['debit_credit'] = 0;
        $data6['amount'] = $overAllSubTotal;
        $data6['jv_no']   		= $jv_no;
        $data6['jv_date']   	= date('Y-m-d');
        $data6['acc_id'] 		= $supplierAccId;
        $data6['description']   = $jvDataDescription;
        $data6['jv_status']   	= 2;
        $data6['username'] 		= Auth::user()->name;
        $data6['approve_username'] 		= Auth::user()->name;
        $data6['status']  		= 1;
        $data6['date'] 			= date('Y-m-d');
        $data6['time'] 			= date('H:i:s');
        $data6['master_id'] 			= $master_id;

        DB::table('jv_data')->insert($data6);
        $jvDataRecord = DB::table('jv_data')->where('jv_no', $jv_no)->where('status','=', '1')->get();
        foreach ($jvDataRecord as $row2) {
            $vouceherType = 3;
            $voucherNo = $row2->jv_no;
            $voucherDate = $row2->jv_date;
            CommonHelper::reconnectMasterDatabase();
            $accCode = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$m);
            CommonHelper::companyDatabaseConnection($m);

            $data4['acc_id'] = $row2->acc_id;
            $data4['acc_code'] = $accCode;
            $data4['particulars'] = $row2->description;
            $data4['opening_bal'] = '0';
            $data4['debit_credit'] = $row2->debit_credit;
            $data4['amount'] = $row2->amount;
            $data4['voucher_no'] = $voucherNo;
            $data4['voucher_type'] = $vouceherType;
            $data4['v_date'] = $voucherDate;
            $data4['date'] = date("Y-m-d");
            $data4['master_id'] = $master_id;
        //   $data4['time'] = date("H:i:s");
            $data4['username'] = Auth::user()->name;

           DB::table('transactions')->insert($data4);
        }
*/

        CommonHelper::reconnectMasterDatabase();
    }

    public function approvePurchaseRequestSale(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
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

        $firstTableRecord = DB::table($tableOne)->where($columnOne, $columnValue)->where('status','=', '1')->first();
        $secondTableRecord = DB::table($tableTwo)->where($columnOne, $columnValue)->where('status','=', '1')->get();
        $supplierId = $firstTableRecord->supplier_id;
        CommonHelper::reconnectMasterDatabase();
        $supplierAccId = CommonHelper::getAccountIdByMasterTable($m,$firstTableRecord->supplier_id,'supplier');
        CommonHelper::companyDatabaseConnection($m);
        $description = $firstTableRecord->description;
        $slipNo = $firstTableRecord->slip_no;
        $prNo = $columnValue;
        $prDate = $firstTableRecord->purchase_request_date;
        $str = DB::selectOne("select max(convert(substr(`jv_no`,3,length(substr(`jv_no`,3))-4),signed integer)) reg from `jvs` where substr(`jv_no`,-4,2) = ".date('m')." and substr(`jv_no`,-2,2) = ".date('y')."")->reg;
        $jv_no = 'jv'.($str+1).date('my');

        $data1['jv_date']   	    = date('Y-m-d');
        $data1['jv_no']   		    = $jv_no;
        $data1['pr_date']   	    = $prDate;
        $data1['pr_no']   		    = $prNo;
        $data1['slip_no']   	    = $slipNo;
        $data1['voucherType'] 	    = 2;
        $data1['description']       = '('.$description.') * (Purchase Request No => '.$prNo.') * (Purchase Request Date => '.$prDate.') * (Slip No => '.$slipNo.')';
        $data1['jv_status']  	    = 2;
        $data1['username'] 		    = Auth::user()->name;
        $data1['approve_username'] 	= Auth::user()->name;
        $data1['date'] 			    = date('Y-m-d');
        $data1['time'] 			    = date('H:i:s');
        DB::table('jvs')->insert($data1);
        $overAllSubTotal = 0;
        $jvDataDescription = '';
        foreach ($secondTableRecord as $row){
            $demandNo = $row->demand_no;
            $demandDate = $row->demand_date;
            $categoryId = $row->category_id;
            $subItemId = $row->sub_item_id;
            $qty = $row->purchase_request_qty;
            $rate = $row->rate;
            $subTotal = $row->sub_total;
            $overAllSubTotal += $subTotal;
            $jvDataDescription = '(Demand No => '.$demandNo.')*(Demand Date => '.$demandDate.')';
            CommonHelper::reconnectMasterDatabase();
            $subItemAccId = CommonHelper::getAccountIdByMasterTable($m,$row->sub_item_id,'subitem');
            CommonHelper::companyDatabaseConnection($m);
            $data2['debit_credit'] = 1;
            $data2['amount'] = $subTotal;
            $data2['jv_no']   		= $jv_no;
            $data2['jv_date']   	= date('Y-m-d');
            $data2['acc_id'] 		= $subItemAccId;
            $data2['description']   = $jvDataDescription;
            $data2['jv_status']   	= 2;
            $data2['username'] 		= Auth::user()->name;
            $data2['status']  		= 1;
            $data2['date'] 			= date('Y-m-d');
            $data2['time'] 			= date('H:i:s');

            DB::table('jv_data')->insert($data2);

        }

        $data3['debit_credit'] = 0;
        $data3['amount'] = $overAllSubTotal;
        $data3['jv_no']   		= $jv_no;
        $data3['jv_date']   	= date('Y-m-d');
        $data3['acc_id'] 		= $supplierAccId;
        $data3['description']   = $jvDataDescription;
        $data3['jv_status']   	= 2;
        $data3['username'] 		= Auth::user()->name;
        $data3['approve_username'] 		= Auth::user()->name;
        $data3['status']  		= 1;
        $data3['date'] 			= date('Y-m-d');
        $data3['time'] 			= date('H:i:s');

        DB::table('jv_data')->insert($data3);

        $updateDemandDetails = array(
            'purchase_request_status' => 2
        );

        DB::table('demand_data')
            ->where('demand_no', $demandNo)
            ->where('demand_status', '2')
            ->where('status', '1')
            ->update($updateDemandDetails);

        $jvDataRecord = DB::table('jv_data')->where('jv_no', $jv_no)->where('status','=', '1')->get();
        foreach ($jvDataRecord as $row2) {
            $vouceherType = 3;
            $voucherNo = $row2->jv_no;
            $voucherDate = $row2->jv_date;
            CommonHelper::reconnectMasterDatabase();
            $accCode = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$m);
            CommonHelper::companyDatabaseConnection($m);

            $data4['acc_id'] = $row2->acc_id;
            $data4['acc_code'] = $accCode;
            $data4['particulars'] = $row2->description;
            $data4['opening_bal'] = '0';
            $data4['debit_credit'] = $row2->debit_credit;
            $data4['amount'] = $row2->amount;
            $data4['voucher_no'] = $voucherNo;
            $data4['voucher_type'] = $vouceherType;
            $data4['v_date'] = $voucherDate;
            $data4['date'] = date("Y-m-d");
            $data4['time'] = date("H:i:s");
            $data4['username'] = Auth::user()->name;

            DB::table('transactions')->insert($data4);
        }


        CommonHelper::reconnectMasterDatabase();
    }

}
