<?php

namespace App\Http\Controllers;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\FinanceHelper;
use App\Helpers\SalesHelper;
use App\Helpers\Payment_through_jvs;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\FinanceDepartment;
use App\Models\CostCenter;
use App\Models\DepartmentAllocation1;
use App\Models\CostCenterDepartmentAllocation;
use App\Models\IncomeTaxDeduction;
use App\Models\FbrTxtDeduction;
use App\Models\SrbTxtDeduction;
use App\Models\Transactions;
use App\Models\PraTxtDeduction;
use App\Models\NewPurchaseVoucher;
use App\Models\NewPurchaseVoucherData;
use App\Models\PaidTo;
use App\User;

use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
class FinanceAddDetailControler extends Controller
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


	public function uploadAccountDetail(request $request)
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
				'application/excel',
				// 'application/vnd.msexcel',
				// 'text/plain'
			);

			// Validate whether selected file is a CSV file
			if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

				$row = 0;
				$skip_row_number = array("1");
				$csvFile = fopen($_FILES['file']['tmp_name'], 'r');

				fgetcsv($csvFile);

		
				while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {

					if ($getData[0] != '') {
						FinanceHelper::reconnectMasterDatabase();
						FinanceHelper::companyDatabaseConnection(Input::get('m'));

						$account_type = DB::table('accounts')->where('status', 1)->where('name', $getData[1]);
						if ($account_type->count() > 0) {
							$account_type = $account_type->first();
							$parent_code = $account_type->code;
						} else {
							DB::rollBack();
							dd("Parent code Account type ($getData[0].'-'.$getData[1]) not exist, create first");
						}

						// 		if($getData[2] == 'ASSETS'){
						// 			$parent_code = '1-2-19';
						// 		}elseif($getData[2] == 'EXPENCE'){
						// 			$parent_code = '4-1';
						// 		}



						$acc_name = $getData[0];
						$o_blnc = $getData[2];
						$o_blnc_trans = 1;
						if ($getData[3] == '1') {
							$o_blnc_trans = 1;
						} elseif ($getData[3] == '0') {
							$o_blnc_trans = 0;
						}
						$operational = 1;
						$sent_code = $parent_code;


						$max_id = DB::selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $parent_code . '\' and status=1')->id;
						if ($max_id == '') {
							$code = $sent_code . '-1';
						} else {
							$max_code2 = DB::selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\' and status=1')->code;
							$max_code2;
							$max = explode('-', $max_code2);
							$code = $sent_code . '-' . (end($max) + 1);
						}

						$level_array = explode('-', $code);
						$counter = 1;
						foreach ($level_array as $level):
							$data1['level' . $counter] = $level;
							$counter++;
						endforeach;
						$data1['code'] = $code;
						$data1['name'] = $acc_name;
						$data1['parent_code'] = $parent_code;
						$data1['username'] = Auth::user()->name;

						$data1['date'] = date("Y-m-d");
						$data1['time'] = date("H:i:s");
						$data1['action'] = 'create';
						$data1['status'] = 1;
						$data1['operational'] = $operational;

						if (DB::table('accounts')->where('name', $acc_name)->where('parent_code', $parent_code)->count() > 0) {
							$account = DB::table('accounts')->where('name', $acc_name)->where('parent_code', $parent_code);
							DB::table('transactions')
								->where('acc_id', $account->value('id'))
								->where('acc_code', $account->value('code'))
								->update([
									'amount' => $o_blnc
								]);
						} else {
							$acc_id = DB::table('accounts')->insertGetId($data1);
							$data2['acc_id'] = $acc_id;
							$data2['acc_code'] = $code;
							$data2['debit_credit'] = $o_blnc_trans;
							$data2['amount'] = $o_blnc;
							$data2['opening_bal'] = 1;
							$data2['username'] = Auth::user()->name;

							$data2['date'] = date("Y-m-d");
							$data2['v_date'] = '2026-07-01';
							$data2['time'] = date("H:i:s");
							$data2['action'] = 'create';
							$data2['status'] = 1;
							DB::table('transactions')->insert($data2);

							FinanceHelper::reconnectMasterDatabase();
							FinanceHelper::audit_trail($code, '', '', 5, 'Insert');

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

		return Redirect::to('/finance/createAccountForm?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('m') . '#SFR');

	}


	public function addAccountDetail(){


		DB::Connection('mysql2')->beginTransaction();

		try {

			FinanceHelper::companyDatabaseConnection($_GET['m']);
		$parent_code = Input::get('account_id');

		$acc_name = Input::get('acc_name');
		$o_blnc = Input::get('o_blnc');
		$o_blnc_trans = Input::get('o_blnc_trans');
		$operational = Input::get('operational');
		$sent_code = $parent_code;


		$max_id = DB::selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \''.$parent_code.'\' and status=1')->id;
		if($max_id == '')
		{
			$code = $sent_code.'-1';
		}
		else
		{
			$max_code2 = DB::selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \''.$max_id.'\' and status=1')->code;
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
		$data1['name'] = $acc_name;
		$data1['parent_code'] = $parent_code;
		$data1['username'] 		 	= Auth::user()->name;

		$data1['date']     		  = date("Y-m-d");
		$data1['time']     		  = date("H:i:s");
		$data1['action']     		  = 'create';
		$data1['operational']		= $operational;


		$acc_id = DB::table('accounts')->insertGetId($data1);


		//$acc_id = $data1->id;

		$data2['acc_id'] =	$acc_id;
		$data2['acc_code']=	$code;
		$data2['debit_credit']=	$o_blnc_trans;
		$data2['amount'] 	  = 	$o_blnc;
		$data2['opening_bal'] 	  = 	1;
		$data2['username'] 		 	= Auth::user()->name;

		$data2['date']     		  = date("Y-m-d");
		$data2['v_date']     		= '2023-07-01';
		$data2['time']     		  = date("H:i:s");
		$data2['action']     		  = 'create';
		DB::table('transactions')->insert($data2);

		FinanceHelper::reconnectMasterDatabase();
			FinanceHelper::audit_trail($code,'','',5,'Insert');
			DB::Connection('mysql2')->commit();
		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e->getMessage());

		}

		return Redirect::to('finance/createAccountForm?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}



	public function addTaxSectionDetail(){

		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$Section = Input::get('Section');

		$TaxPaymentNature = Input::get('TaxPaymentNature');
		$Code = Input::get('Code');
		$Rate = Input::get('Rate');
		if($Rate == "")
		{
			$Rate = 0;
		}
		$TaxPaymentSection = Input::get('TaxPaymentSection');

		$TaxData['section'] = $Section;
		$TaxData['tax_payment_nature'] = $TaxPaymentNature;
		$TaxData['code'] = $Code;
		$TaxData['rate'] = $Rate;
		$TaxData['tax_payment_section'] = $TaxPaymentSection;
		$TaxData['created_date'] = date("Y-m-d");
		$TaxData['created_time'] = date("H:i:s");
		$TaxData['status'] = 1;
		DB::table('tax_section')->insert($TaxData);

		FinanceHelper::reconnectMasterDatabase();
		return Redirect::to('finance/viewTaxSectionList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}


	function addJournalVoucherDetail(Request $request)
	{


		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$jvsSection = Input::get('jvsSection');
		foreach($jvsSection as $row)
		{

			//   $str = DB::selectOne("select max(convert(substr(`jv_no`,3,length(substr(`jv_no`,3))-4),signed integer)) reg from `jvs` where substr(`jv_no`,-4,2) = ".date('m')." and substr(`jv_no`,-2,2) = ".date('y')."")->reg;
			$str = DB::selectOne("select count(id)id from jvs where status=1 and jv_date='".Input::get('jv_date_'.$row)."'")->id;
			$jv_no = 'jv'.($str+1);
			$slip_no = Input::get('slip_no_'.$row);
			$jv_date = Input::get('jv_date_'.$row);
			$description = Input::get('description_'.$row);

			$data1['jv_date']   	= $jv_date;
			$data1['jv_no']   		= $jv_no;
			$data1['slip_no']   	= $slip_no;
			$data1['voucherType'] 	= 1;
			$data1['description']   = $description;
			$data1['jv_status']  	= 1;

			$data1['bill_date']   = Input::get('bill_date');
			$data1['due_date']  	=Input::get('due_date');
			$data1['with_items']   = Input::get('items');
			$total_net_amount =  str_replace(',','',Input::get('d_t_amount_1'));
			$data1['total_net_amount']=$total_net_amount;
			$data1['username'] 		= Auth::user()->name;
			$data1['date'] 			= date('Y-m-d');
			$data1['time'] 			= date('H:i:s');

			// advance option
			$payment_advnavce=$request->payment_id;
			$payment_advnavce=explode(',',$payment_advnavce);
			$payment_advnavce=$payment_advnavce[0];
			$data1['payment_id']=$payment_advnavce;

			if ($payment_advnavce!=0):
				$data1['through_advance']=1;

				$adv_amount= $request->adv_amount;
				$total_net_amount=$request->d_t_amount_1;
				$adv_amount=    str_replace(',', '', $adv_amount);
				$total_net_amount=    str_replace(',', '', $total_net_amount);


				if ($adv_amount==$total_net_amount):
					$data1['paid']=1;
				endif;

			endif;
			$master_id = DB::table('jvs')->insertGetId($data1);
			$refrence_no='jv'.$master_id;
			$unique_refrence_no['unique_refrence_no']   = $refrence_no;
			DB::table('jvs')->where('id',$master_id)->update($unique_refrence_no);

			if ($payment_advnavce!=0):


			endif;

			// for advance option

			if ($payment_advnavce!=0):
				$data['advance_paid']=2;
				$data['purchase_id']=$master_id;
				DB::Connection('mysql2')->table('pvs')->where('id',$payment_advnavce)->update($data);
			endif;


			//end



			$jvsDataSection = Input::get('jvsDataSection_'.$row);
		//	print_r($jvsDataSection);
			$count=1;
			foreach($jvsDataSection as $row1)

			{
				//	$d_amount =  Input::get('d_amount_'.$row.'_'.$row1.'');
				//	$c_amount =  Input::get('c_amount_'.$row.'_'.$row1.'');

				$d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
				$c_amount =  str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));
				$account  =  Input::get('account_id_'.$row.'_'.$row1.'');

				$account=explode('~',$account);

				$supplier=$account[1];

				if ($supplier==1):


					$breakup_=$request->breakup_id;
					if ($breakup_!=0):
					$breakup_=explode(',',$request->breakup_id);
					$breakup_main_id=$breakup_[1];
					else:
						$breakup_main_id=$breakup_;
						endif;
					$jvs['supplier_id']=$account[2];
					$jvs['purchase']=1;
					$jvs['total_net_amount']=$c_amount;


					$payment_id=0;
					$payment_id=explode(',',$payment_id);
					$breakup_data['jv_id']=$master_id;
					$breakup_data['pv_id']=0;
					$breakup_data['slip_no']=$slip_no;
					$breakup_data['voucher_no']=$jv_no;
					$breakup_data['voucher_date']=$jv_date;
					$breakup_data['voucher_type']=1;

					if ($c_amount>0):
						$breakup_data['debit_credit']=0;
						$breakup_data['amount']=$c_amount;;
					endif;
					if ($d_amount>0):
						$breakup_data['debit_credit']=1;
						$breakup_data['amount']=$d_amount;;
					endif;
					$breakup_data['supplier_id']=$account[2];

					if ($breakup_main_id=='0'):

						$breakup_data['main_id']=$refrence_no;

					elseif($breakup_main_id=='new'):
						$breakup_data['main_id']=Input::get('new_ref').'(j'.$master_id.')';
						$breakup_data['refrence_nature']=3;

					else:

						$main_id=$request->breakup_id;
						$main_id=explode(',',$main_id);

						$check_supplier_on_refrence=Payment_through_jvs::check_supplier_on_refrence($account[2],$main_id[1]);

						if ($check_supplier_on_refrence==0):
							 $check_supplier_on_refrence.' '.$account[2];

						$check_supplier_on_refrence=explode('*',$check_supplier_on_refrence);
						$breakup_data['refrence_nature']=$check_supplier_on_refrence[1];
						else:
							$breakup_data['refrence_nature']=0;
							endif;
						$breakup_data['main_id']=$main_id[1];

					endif;
					DB::Connection('mysql2')->table('breakup_data')->insert($breakup_data);

					DB::Connection('mysql2')->table('jvs')->where('id', $master_id)->update($jvs);
				endif;

				$account=$account[0];
				$sub_item  = Input::get('sub_item_id_'.$row.'_'.$row1.'');

				$qty  = Input::get('qty_'.$row.'_'.$row1.'');
				$qty =  str_replace(',','',Input::get('qty_'.$row.'_'.$row1.''));

				$rate  = Input::get('rate_'.$row.'_'.$row1.'');
				$rate =  str_replace(',','',Input::get('rate_'.$row.'_'.$row1.''));
				if($d_amount >0)
				{
					$data2['debit_credit'] = 1;
					$data2['amount'] = $d_amount;
					$data['debit_credit'] = 1;
					$data3['amount'] = $d_amount;
				}
				else if($c_amount >0)

				{
					$data2['debit_credit'] = 0;
					$data2['amount'] = $c_amount;
					$data3['amount'] = $c_amount;
					$data3['debit_credit'] =0;
				}

				$data2['jv_no']   		= $jv_no;
				$data2['jv_date']   	= $jv_date;
				$data2['acc_id'] 		= $account;
				$data2['description']   = $description;
				$data2['jv_status']   	= 1;
				$data2['username'] 		= Auth::user()->name;
				$data2['status']  		= 1;
				$data2['date'] 			= date('Y-m-d');
				$data2['time'] 			= date('H:i:s');
				$data2['master_id'] 			= $master_id;
				$data2['qty']   = $qty;
				$data2['rate']   = $rate;
				$data2['item_id']=$sub_item;

				$other_id=DB::table('jv_data')->insertGetId($data2);


				$data3['acc_id'] = $account;
				$data3['particulars'] =$description;
				$data3['opening_bal'] = '0';
				$data3['voucher_no'] = $jv_no;
				$data3['voucher_type'] = 1;
				$data3['v_date'] = $jv_date;
				$data3['date'] = date("Y-m-d");
				$data3['time'] = date("H:i:s");
				$data3['master_id'] = $master_id;
				$data3['username'] = Auth::user()->name;
				DB::table('transactions')->insert($data3);



				// for department

				$allow_null = $request->input('dept_check_box' . $row1);
				if ($allow_null == 0):
					$department1 = $request->input('department' . $row1);
					$counter = 0;
					foreach ($department1 as $row2):
						$dept_allocation1 = new DepartmentAllocation1();
						$dept_allocation1 = $dept_allocation1->SetConnection('mysql2');
						$dept_allocation1->Main_master_id = $master_id;
						$dept_allocation1->master_id = $other_id;
						$dept_allocation1->pv_no = $jv_no;
						$dept_allocation1->type = 5;
						$dept_allocation1->dept_id = $row2;
						$perccent = $request->input('percent' . $row1);
						$dept_allocation1->percent = $perccent[$counter];
						$amount = $request->input('department_amount' . $row1);
						$amount = str_replace(",", "", $amount);
						$dept_allocation1->amount = $amount[$counter];
						$dept_allocation1->item = $request->input('sub_item_id_1_' . $row1);
						$dept_allocation1->save();
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
						$costcenter->Main_master_id = $master_id;
						$costcenter->master_id = $other_id;
						$costcenter->pv_no = $jv_no;
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
						$costcenter->save();
						$counter++;

					endforeach;

				endif;
				// End for Cost center department

			}


		}

		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');

		$parent_code=33;
		return Redirect::to('finance/createJournalVoucherForm?pageType='.'&&parentCode='.$parent_code.'&&m='.$_GET['m'].'#SFR');


	}

	public function addPurchasePaymentVoucherDetail(Request $request){
		$m = Input::get('m');
		$paymentType = $request->paymentType;
		$pvStr = DB::Connection('mysql2')->selectOne("select max(convert(substr(`pv_no`,4,length(substr(`pv_no`,4))-4),signed integer)) pvReg from `new_pv` where substr(`pv_no`,-4,2) = ".date('m')." and substr(`pv_no`,-2,2) = ".date('y')."")->pvReg;
		if($paymentType == 1){
			$pv_no = 'BPV'.($pvStr+1).date('my');
		}else{
			$pv_no = 'CPV'.($pvStr+1).date('my');
		}
		$pageType = $request->pageType;
		$parentCode = $request->parentCode;
		$poDetail = $request->poDetail;
		$poNo = $request->poNo;
		$poDate = $request->poDate;
		$poId = $request->poId;
		$supplierId = $request->supplierId;
		$supplierAccId = $request->supplierAccId;
		$slip_no = $request->slip_no;
		$pv_date = $request->pv_date;
		$debit_amount = $request->debit_amount;
		$cheque_no = $request->cheque_no;
		$cheque_date = $request->cheque_date;
		$main_description = $request->main_description;
		$seletedPurchaseOrderRow = $request->seletedPurchaseOrderRow;

		$totalDebitAmount = 0;

		$data1['pv_no']   		= $pv_no;
		$data1['pv_date']   	= $pv_date;
		$data1['po_id'] = $poId;
		if($paymentType == 1){
			$data1['cheque_no'] 	= $cheque_no;
			$data1['cheque_date']   = $cheque_date;
		}
		$data1['payment_type'] 	= $paymentType;
		$data1['description']   = $main_description;
		$data1['pv_status']  	= 1;
		$data1['date'] 			= date('Y-m-d');
		$data1['status'] 			= 1;
		$data1['username'] 		= Auth::user()->name;
		$data1['type'] 	= 3;

		$master_id = DB::Connection('mysql2')->table('new_pv')->insertGetId($data1);

		foreach($seletedPurchaseOrderRow as $row){
			$account_id = Input::get('account_id_'.$row.'');
			$credit = Input::get('credit_'.$row.'');
			$totalDebitAmount += $credit;

			$data2['master_id'] = $master_id;
			$data2['pv_no'] = $pv_no;
			$data2['pv_date'] = $pv_date;
			$data2['acc_id'] = $account_id;
			$data2['paid_to_id'] = $supplierId;
			$data2['paid_to_type'] = 2;
			$data2['debit_credit'] = 0;
			$data2['amount'] = $credit;
			$data2['description'] = $main_description;
			$data2['date'] 			= date('Y-m-d');
			$data2['status'] 			= 1;
			$data2['pv_status'] = 1;

			DB::Connection('mysql2')->table('new_pv_data')->insert($data2);

		}

		$data3['master_id'] = $master_id;
		$data3['pv_no'] = $pv_no;
		$data3['pv_date'] = $pv_date;
		$data3['acc_id'] = $supplierAccId;
		$data3['paid_to_id'] = $supplierId;
		$data3['paid_to_type'] = 2;
		$data3['debit_credit'] = 1;
		$data3['amount'] = $totalDebitAmount;
		$data3['description'] = $main_description;
		$data3['date'] 			= date('Y-m-d');
		$data3['status'] 			= 1;
		$data3['pv_status'] = 1;

		DB::Connection('mysql2')->table('new_pv_data')->insert($data3);

		if($debit_amount == $totalDebitAmount){
			DB::Connection('mysql2')->table('purchase_request')->where('purchase_request_no',$poNo)->update(['payment_status'=>2]);
		}else{
			DB::Connection('mysql2')->table('purchase_request')->where('purchase_request_no',$poNo)->update(['advanced_paid_amount'=>2]);
		}
		return Redirect::to('finance/addPaymentVoucherAgainstPO?pageType='.$pageType.'&&parentCode='.$parentCode.'&&m='.$m.'#SFR');
	}

	function addCashPaymentVoucherDetail(Request $request){
		FinanceHelper::companyDatabaseConnection($_GET['m']);

		$pvsSection = Input::get('pvsSection');
		foreach($pvsSection as $row){
			//$str = DB::selectOne("select max(convert(substr(`pv_no`,4,length(substr(`pv_no`,4))-4),signed integer)) reg from `pvs` where substr(`pv_no`,-4,2) = ".date('m')." and substr(`pv_no`,-2,2) = ".date('y')."")->reg;
			$str = DB::selectOne("select count(id)id from pvs where status=1 and pv_date='".Input::get('pv_date_'.$row)."'
			and voucherType=1")->id;
			$pv_no = 'cpv'.($str+1);
			$slip_no = Input::get('slip_no_'.$row);
			$pv_date = Input::get('pv_date_'.$row);
			$description = Input::get('description_'.$row);

			$data1['pv_date']   	= $pv_date;
			$data1['pv_no']   		= $pv_no;
			$data1['slip_no']   	= $slip_no;
			$data1['voucherType'] 	= 1;
			$data1['description']   = $description;
			$data1['pv_status']  	= 1;
			$data1['username'] 		= Auth::user()->name;
			$data1['date'] 			= date('Y-m-d');
			$data1['time'] 			= date('H:i:s');
			$data1['bill_date']   = Input::get('bill_date');
			$master_id = DB::table('pvs')->insertGetId($data1);
			$pvsDataSection = Input::get('pvsDataSection_'.$row);
			$count=1;
			foreach($pvsDataSection as $row1)

			{
				$d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
				$c_amount =  str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));
				$account  =  Input::get('account_id_'.$row.'_'.$row1.'');


				$sub_item  = Input::get('sub_item_id_'.$row.'_'.$row1.'');
				$qty  = Input::get('qty_'.$row.'_'.$row1.'');
				$rate  =str_replace(',','',Input::get('rate_'.$row.'_'.$row1.''));

				if ($account!=''):
					if($d_amount >0)
					{
						$data2['debit_credit'] = 1;
						$data2['amount'] = $d_amount;
						$data['amount'] = $d_amount;
						$data['debit_credit'] =1;
					}
					else if($c_amount >0){
						$data2['debit_credit'] = 0;
						$data2['amount'] = $c_amount;
						$data['amount'] = $c_amount;
						$data['debit_credit'] =0;
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
					$data2['master_id']=	  $master_id;
					$data2['qty']   = $qty;
					$data2['rate']   = $rate;
					$data2['sub_item']   = $sub_item;


					//	DB::table('pv_data')->insert($data2);
					$other_id = DB::table('pv_data')->insertGetId($data2);


					$data['acc_id'] = $account;
					$data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
					$data['particulars'] =$description;
					$data['opening_bal'] = '0';
					$data['voucher_no'] = $pv_no;
					$data['voucher_type'] = 2;
					$data['v_date'] = $pv_date;
					$data['date'] = date("Y-m-d");
					$data['time'] = date("H:i:s");
					$data['master_id'] = $master_id;
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
								$dept_allocation1->Main_master_id = $master_id;
								$dept_allocation1->master_id = $other_id;
								$dept_allocation1->pv_no = $pv_no;
								$dept_allocation1->type = 4;
								$dept_allocation1->dept_id = $row1;
								$perccent = $request->input('percent' . $count);
								$dept_allocation1->percent = $perccent[$counter];
								$amount = $request->input('department_amount' . $count);
								$amount = str_replace(",", "", $amount);
								$dept_allocation1->amount = $amount[$counter];
								$dept_allocation1->item = $request->input('sub_item_id_1_' . $count);
								if($row1 != "" && $amount[$counter] > 0):
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
								//if($amount = $request->input('cost_center_department_amount' . $count[$counter]) == )
								$costcenter = new CostCenterDepartmentAllocation();
								$costcenter = $costcenter->SetConnection('mysql2');
								$costcenter->Main_master_id = $master_id;
								$costcenter->master_id = $other_id;
								$costcenter->pv_no = $pv_no;
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
								if($row3!='' && $amount[$counter] > 0):
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
		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');

		return Redirect::to('finance/viewCashPaymentVoucherList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}

	function addBankPaymentVoucherDetail(Request $request){



		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$pvsDataSection = Input::get('pvsDataSection_1');
		count($pvsDataSection);
		$pvsSection = Input::get('pvsSection');

		foreach($pvsSection as $row){
			//
			//$str = DB::selectOne("select max(convert(substr(`pv_no`,4,length(substr(`pv_no`,4))-4),signed integer)) reg from `pvs` where substr(`pv_no`,-4,2) = ".date('m')." and substr(`pv_no`,-2,2) = ".date('y')."")->reg;
			$payment_mod = Input::get('payment_mod');
			if ($payment_mod==1):
				$str = DB::selectOne("select count(id)id from pvs where status=1 and pv_date='".Input::get('pv_date_'.$row)."'")->id;
				$pv_no = 'pv'.($str+1);
				$v_type=2;
				$cheque_no = Input::get('cheque_no_'.$row);
			else:
				$str = DB::selectOne("select count(id)id from pvs where status=1 and pv_date='".Input::get('pv_date_'.$row)."'
			")->id;
				$pv_no = 'pv'.($str+1);
				$v_type=1;
				$cheque_no = '';
			endif;

			$slip_no = Input::get('slip_no_'.$row);
			$pv_date = Input::get('pv_date_'.$row);
			//	$cheque_no = Input::get('cheque_no_'.$row);
			$cheque_date = Input::get('cheque_date_'.$row);
			$description = Input::get('description_'.$row);
			$cheque_status=Input::get('with_cheque');
			$SupplierOtherPay=Input::get('SupplierOtherPay');
			$SupplierId=Input::get('SupplierId');
			$OtherPayables=Input::get('OtherPayables');
			if($SupplierOtherPay == 1)
			{
				$data1['supp_other_pay_type'] = $SupplierOtherPay;
				$data1['supp_or_acc_id'] = $SupplierId;
			}
			if($SupplierOtherPay == 2)
			{
				$data1['supp_other_pay_type'] = $SupplierOtherPay;
				$data1['supp_or_acc_id'] = $OtherPayables;
			}



			$data1['pv_date']   	= $pv_date;
			$data1['pv_no']   		= $pv_no;
			$data1['slip_no']   	= $slip_no;
			$data1['bill_date']   	= Input::get('bill_date');
			$data1['with_cheque']   	= $cheque_status;
			if ($cheque_status==0):
				$data1['cheque_no']   		= $cheque_no;
				$data1['cheque_date']   	= $cheque_date;
			endif;
			$data1['voucherType'] 	= Input::get('v_type');
			$data1['description']   = $description;
			$data1['username'] 		= Auth::user()->name;
			$data1['pv_status']  	= 1;
			$data1['date'] 			= date('Y-m-d');
			$data1['time'] 			= date('H:i:s');
			$data1['payment_type'] 			=Input::get('type');
			$data1['btl_exempt']=Input::get('optradio');
			$data1['exempt_code']=Input::get('VendorCode');

			if (Input::get('type')==2)
			{
				$data1['advance_paid'] 			=1;
			}
			$id= Input::get('purchase_id');
			$data1['purchase_id'] 			=$id;
			$master_id = DB::table('pvs')->insertGetId($data1);
			$refrence_no='pv'.$master_id;
			$unique_refrence_no['unique_refrence_no']   = $refrence_no;
			DB::table('pvs')->where('id',$master_id)->update($unique_refrence_no);

			$pvsDataSection = Input::get('pvsDataSection_'.$row);



			$count=1;
			foreach($pvsDataSection as $row1)
			{
				$d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
				$c_amount =   str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));
				$account  = Input::get('account_id_'.$row.'_'.$row1.'');




				if ($row1<3 ||  $row1 >14 ):

					$account = explode('~', $account);

					$supplier = $account[1];
					$account_id = $account[0];

				else:
					$supplier = 0;
					$account_id = $account;
				endif;





				$sub_item  = Input::get('sub_item_id_'.$row.'_'.$row1.'');
				$qty  = Input::get('qty_'.$row.'_'.$row1.'');
				$rate  = str_replace(',','',Input::get('rate_'.$row.'_'.$row1.''));
				if ($account!=''):
					if($d_amount >0)
					{


						$data2['debit_credit'] = 1;
						$data2['amount'] = $d_amount;
						$data['debit_credit'] = 1;

						$debit_credit=1;
						$data['amount'] = $d_amount;
						$breakup_amount=$d_amount;
					}
					else if($c_amount >0){
						$data2['debit_credit'] = 0;
						$data2['amount'] = $c_amount;
						$data['debit_credit'] = 0;
						$data['amount'] = $c_amount;

						$breakup_amount=$c_amount;
					}





					if ($supplier==1):

						if (Input::get('type')==2):

							$refrence_type=Input::get('refrence');
							if ($refrence_type==1):
								$refrence=Input::get('purchase_refrence').'(p'.$master_id.')';
							else:




								$refrence=Input::get('purchase_refrence');
								$refrence=explode(',',$refrence);
								$refrence=$refrence[1];


								$check_supplier_on_refrence=Payment_through_jvs::check_supplier_on_refrence($account[2],$refrence);

								if ($check_supplier_on_refrence==0):
									$check_supplier_on_refrence.' '.$account[2];

									$check_supplier_on_refrence=explode('*',$check_supplier_on_refrence);
									$breakup_data['refrence_nature']=$check_supplier_on_refrence[1];
								else:
									$breakup_data['refrence_nature']=0;
								endif;


							endif;

							$breakup_data['refrence_nature']=2;
							$breakup_data['debit_credit']=$debit_credit;
							$breakup_data['main_id']=$refrence;
							$breakup_data['supplier_id']=$account[2];
							$breakup_data['pv_id']=$master_id;
							$breakup_data['voucher_date']=$pv_date;
							$breakup_data['voucher_type']=2;
							$breakup_data['voucher_type']=2;
							$breakup_data['slip_no']=$slip_no;
							$breakup_data['amount']=CommonHelper::check_str_replace($breakup_amount);
							DB::Connection('mysql2')->table('breakup_data')->insert($breakup_data);
						endif;
					endif;



					$data2['pv_no']   		= $pv_no;
					if (Input::get('type')==1):
						$data2['sub_item']   		= $sub_item;
					endif;
					$data2['pv_date']   	= $pv_date;
					$data2['acc_id'] 		= $account_id;
					if($SupplierOtherPay == 1)
					{
						$data2['supp_other_pay_type'] = $SupplierOtherPay;
						$data2['supp_or_acc_id'] = $SupplierId;
					}
					if($SupplierOtherPay == 2)
					{
						$data2['supp_other_pay_type'] = $SupplierOtherPay;
						$data2['supp_or_acc_id'] = $OtherPayables;
					}

					$data2['description']   = $description;
					$data2['qty']   = $qty;
					$data2['rate']   = $rate;
					$data2['pv_status']   	= 1;
					$data2['username'] 		= Auth::user()->name;
					$data2['status']  		= 1;
					$data2['date'] 			= date('Y-m-d');
					$data2['time'] 			= date('H:i:s');
					$data2['master_id']=$master_id;
					if ($count==4 && $c_amount > 0):
						$data2['srb']=1;
					endif;
					//	DB::table('pv_data')->insert($data2);
					$other_id = DB::table('pv_data')->insertGetId($data2);



					// for Inocme Tax Deduction
					if ($row1 >2 && $row1 <6):

					echo 	$request->input('c_amount_1_'.$row1);
							if ($request->input('c_amount_1_'.$row1)>0):
								$get_income_tax_count=$row1-2;
								$income_tax_deduction= new IncomeTaxDeduction();
								$income_tax_deduction=$income_tax_deduction->SetConnection('mysql2');
								$income_tax_deduction->pvs_id=$master_id;
								$income_tax_deduction->pv_data_id=$other_id;
								$income_tax_deduction->pv_no=$pv_no;
								$income_tax_deduction->supplier_id=$request->supplier_id;
								$income_tax_deduction->nature=$request->input('nature'.$get_income_tax_count);
								$income_tax_deduction->income_tax_slab_id=$request->input('income_tax_id'.$get_income_tax_count);
								$income =   str_replace(',','',$request->input('income'.$get_income_tax_count));

								$income_tax_deduction->amount=$income;
								$income_tax_deduction->tax_payment_section=$request->input('tax_payment_section'.$get_income_tax_count);
								$c_amount =   str_replace(',','',$request->input('c_amount_'.$row.'_'.$row1.''));
								$income_tax_deduction->deduct_amount=$c_amount;
								$income_tax_deduction->save();
							endif;
					endif;
					// income tax end


					$data['acc_id'] = $account_id;
					//	$data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
					$data['particulars'] =$description;
					$data['opening_bal'] = '0';
					$data['voucher_no'] = $pv_no;
					$data['voucher_type'] = 2;
					$data['v_date'] = $pv_date;
					$data['date'] = date("Y-m-d");
					$data['time'] = date("H:i:s");
					$data['master_id'] = $master_id;
					$data['username'] = Auth::user()->name;
					DB::table('transactions')->insert($data);


					// for department
					if (Input::get('type')==1 || Input::get('type')==2):

						$allow_null = $request->input('dept_check_box' . $row1);
						if ($allow_null == 0):
							$department1 = $request->input('department' . $row1);
							$counter = 0;

						if ($row1 < 3 || $row1 > 8):

							foreach ($department1 as $row2):
								$dept_allocation1 = new DepartmentAllocation1();
								$dept_allocation1 = $dept_allocation1->SetConnection('mysql2');
								$dept_allocation1->Main_master_id = $master_id;
								$dept_allocation1->master_id = $other_id;
								$dept_allocation1->pv_no = $pv_no;
								$dept_allocation1->type = 3;
								$dept_allocation1->dept_id = $row2;
								$perccent = $request->input('percent' . $row1);


								$dept_allocation1->percent = $perccent[$counter];
								$amount = $request->input('department_amount' . $row1);
								$amount = str_replace(",", "", $amount);

								$dept_allocation1->amount = $amount[$counter];
								$dept_allocation1->item = $request->input('sub_item_id_1_' . $row1);
								if($row2 != "" && $amount[$counter] > 0):
									$dept_allocation1->save();
								endif;
								$counter++;

							endforeach;
							endif;
						endif;

						// end for department





						// for Cost center department

						$allow_null = $request->input('cost_center_check_box' . $row1);
						if ($allow_null == 0):
							$sales_tax_department = $request->input('cost_center_department' . $row1);
							$counter = 0;

							if ($row1 < 3 || $row1 > 8):
							foreach ($sales_tax_department as $row3):
								$costcenter = new CostCenterDepartmentAllocation();
								$costcenter = $costcenter->SetConnection('mysql2');
								$costcenter->Main_master_id = $master_id;
								$costcenter->master_id = $other_id;
								$costcenter->pv_no = $pv_no;
								$costcenter->type = 3;
								$costcenter->dept_id = $row3;
								$perccent = $request->input('cost_center_percent' . $row1);
								$costcenter->percent = $perccent[$counter];
								$amount = $request->input('cost_center_department_amount' . $row1);
								$amount = str_replace(",", "", $amount);
							//	if ($amount[$counter] != ''):
									$costcenter->amount = $amount[$counter];
							//	endif;
								$costcenter->item = $request->input('sub_item_id_1_' . $row1);
								if($row3 != "" && $amount[$counter] > 0):
									$costcenter->save();
								endif;
								$counter++;

							endforeach;
						endif;
						endif;
					endif;
					// End for Cost center department
				endif;









				$count++;
			}
		}

		$type = Input::get('type');
		if ($type==3):
			$id= Input::get('purchase_id');
			$id=explode(',',$id);
			$data3['pv_status']=3;
			$data3['payment_id']=$master_id;

			DB::Connection('mysql2')->table('purchase_voucher')->whereIn('id', $id)->update($data3);
		endif;
		Session::flash('dataInsert','successfully saved.');
		$page_type='add';
		$parnet_code=22;

		if ($type==3):

			//	endforeach;
			FinanceHelper::reconnectMasterDatabase();
			if ($payment_mod==1):
				return Redirect::to('finance/createBankPaymentVoucherForm?pageType='.$page_type.'&&parentCode='.$parnet_code.'&&m='.$_GET['m'].'#SFR');
			else:
				return Redirect::to('finance/createBankPaymentVoucherForm?pageType='.$page_type.'&&parentCode='.$parnet_code.'&&m='.$_GET['m'].'#SFR');
			endif;
		else:
			return Redirect::to('finance/createBankPaymentVoucherForm?pageType='.Input::get('pageType').'&&parentCode='.$parnet_code.'&&m='.$_GET['m'].'#SFR');
		endif;
	}

	function addCashReceiptVoucherDetail(){
		FinanceHelper::companyDatabaseConnection($_GET['m']);

		$rvsSection = Input::get('rvsSection');
		foreach($rvsSection as $row){

			$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),2);
			$slip_no = Input::get('slip_no_'.$row);
			$rv_date = Input::get('rv_date_'.$row);
			$description = Input::get('description_'.$row);

			$data1['rv_date']   	= $rv_date;
			$data1['rv_no']   		= $rv_no;
			$data1['slip_no']   	= $slip_no;
			$data1['voucherType'] 	= 1;
			$data1['description']   = $description;
			$data1['rv_status']  	= 1;
			$data1['username'] 		= Auth::user()->name;
			$data1['date'] 			= date('Y-m-d');
			$data1['time'] 			= date('H:i:s');
			$master_id=DB::table('rvs')->insertGetId($data1);
			$rvsDataSection = Input::get('rvsDataSection_'.$row);
			foreach($rvsDataSection as $row1){
				$d_amount =  Input::get('d_amount_'.$row.'_'.$row1.'');
				$c_amount =  Input::get('c_amount_'.$row.'_'.$row1.'');
				$account  =  Input::get('account_id_'.$row.'_'.$row1.'');
				if($d_amount !="")
				{
					$data2['debit_credit'] = 1;
					$data2['amount'] = $d_amount;
					$data['debit_credit'] = 1;
					$data['amount'] = $d_amount;
				}else if($c_amount !="")
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
				$data2['status']  		= 1;
				$data2['username'] 		= Auth::user()->name;
				$data2['date'] 			= date('Y-m-d');
				$data2['time'] 			= date('H:i:s');
				$data2['master_id'] 			= $master_id;

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
				$data['master_id'] = $master_id;
				$data['username'] = Auth::user()->name;
				DB::table('transactions')->insert($data);
			}
		}
		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/viewCashReceiptVoucherList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}


	function addBankReceiptVoucherDetail(Request $request){

		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$rvsSection = Input::get('rvsSection');
		foreach($rvsSection as $row){

			$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
			$slip_no = Input::get('slip_no_'.$row);
			$rv_date = Input::get('rv_date_'.$row);
			$cheque_no = Input::get('cheque_no_'.$row);
			$cheque_date = Input::get('cheque_date_'.$row);
			$description = Input::get('description_'.$row);

			$data1['rv_date']   	= $rv_date;
			$data1['rv_no']   		= $rv_no;
			$data1['slip_no']   	= $slip_no;
			$data1['cheque_no']   		= $cheque_no;
			$data1['cheque_date']   	= $cheque_date;
			$data1['voucherType'] 	= 2;
			$data1['description']   = $description;
			$data1['rv_status']  	= 1;
			$data1['username'] 		= Auth::user()->name;
			$data1['date'] 			= date('Y-m-d');
			$data1['time'] 			= date('H:i:s');
			$data1['sales']=		Input::get('sales');
			$data1['currency_id']=		Input::get('curren');
			$data1['exchange_rate']=		Input::get('exchange_rate');
			$data1['foreign_currency']=		CommonHelper::check_str_replace(Input::get('exchange_amunt'));
			$master_id=DB::table('rvs')->insertGetId($data1);
			$data2['master_id'] 			= $master_id;
			$rvsDataSection = Input::get('rvsDataSection_'.$row);
			foreach($rvsDataSection as $row1)
			{


				$d_amount =  Input::get('d_amount_'.$row.'_'.$row1.'');
				$d_amount = CommonHelper::check_str_replace($d_amount);


				$c_amount =  Input::get('c_amount_'.$row.'_'.$row1.'');
				$c_amount = CommonHelper::check_str_replace($c_amount);
				$account  =  Input::get('account_id_'.$row.'_'.$row1.'');


				// for breakup

				$account=explode('~',$account);

				$supplier=$account[1];

				if ($supplier==1):

					$breakup_=explode(',',$request->breakup_id);
					$breakup_main_id=$breakup_[1];
					$payment_id=0;
					$payment_id=explode(',',$payment_id);
					$breakup_data['rv_id']=$master_id;
					$breakup_data['pv_id']=0;
					$breakup_data['jv_id']=0;
					$breakup_data['slip_no']=$slip_no;
					$breakup_data['voucher_no']=$rv_no;
					$breakup_data['voucher_date']=$rv_date;
					$breakup_data['voucher_type']=3;

					if ($c_amount>0):
						$breakup_data['debit_credit']=0;
						$breakup_data['amount']=$c_amount;
					endif;
					if ($d_amount>0):
						$breakup_data['debit_credit']=1;
						$breakup_data['amount']=$d_amount;;
					endif;
					$breakup_data['supplier_id']=$account[2];

					if ($breakup_main_id=='0'):

						$refrence_no='rv'.'(r'.$master_id.')';
						$breakup_data['main_id']=$refrence_no;
					else:

						$main_id=$request->breakup_id;
						$main_id=explode(',',$main_id);

						$check_supplier_on_refrence=Payment_through_jvs::check_supplier_on_refrence($account[2],$main_id[1]);

						if ($check_supplier_on_refrence==0):
							$check_supplier_on_refrence.' '.$account[2];

							$check_supplier_on_refrence=explode('*',$check_supplier_on_refrence);
							$breakup_data['refrence_nature']=$check_supplier_on_refrence[1];
						else:
							$breakup_data['refrence_nature']=0;
						endif;
						$breakup_data['main_id']=$main_id[1];

					endif;
					DB::Connection('mysql2')->table('breakup_data')->insert($breakup_data);
					endif;
					// end breakup

				if($d_amount >0 )
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
				$data2['acc_id'] 		= $account[0];
				$data2['description']   = $description;
				$data2['rv_status']   	= 1;
				$data2['status']  		= 1;
				$data2['username'] 		= Auth::user()->name;
				$data2['date'] 			= date('Y-m-d');
				$data2['time'] 			= date('H:i:s');


				DB::table('rv_data')->insert($data2);


				$data['acc_id'] = $account[0];
				//	$data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
				$data['particulars'] =$description;
				$data['opening_bal'] = '0';
				$data['voucher_no'] = $rv_no;
				$data['voucher_type'] = 3;
				$data['v_date'] = $rv_date;
				$data['date'] = date("Y-m-d");
				$data['time'] = date("H:i:s");
				$data['master_id'] = $master_id;
				$data['username'] = Auth::user()->name;
				DB::table('transactions')->insert($data);
			}
		}


		$sales=Input::get('sales');
		if ($sales==1):
			$slip_no=	Input::get('slip_no');
			$amount=	Input::get('amount');


			$sales_tax_invoice_id=	Input::get('ids');
			$count=0;
			foreach($slip_no as $row):

				$data3['sales_tax_invoice_id']=$sales_tax_invoice_id[$count];
				$data3['receipt_id']=$master_id;
				$data3['sales_tax_invoice_no']=$row;
				$data3['receipt_no']=$rv_no;
				$amount =   CommonHelper::check_str_replace(Input::get('amount')[$count]);
				$data3['received_amount']=$amount;
				$data3['slip_no']=$row;
				$count++;
				DB::Connection('mysql2')->table('received_paymet')->insert($data3);
			endforeach;

		endif;

		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/createBankReceiptVoucherForm?pageType=add&&parentCode=24&&m='.$_GET['m'].'#SFR');
	}



	function addContraVoucherDetail(){
		FinanceHelper::companyDatabaseConnection($_GET['m']);

		$rvsSection = Input::get('rvsSection');
		foreach($rvsSection as $row){
			//$str = DB::selectOne("select max(convert(substr(`rv_no`,4,length(substr(`rv_no`,4))-4),signed integer)) reg from `rvs` where substr(`rv_no`,-4,2) = ".date('m')." and substr(`rv_no`,-2,2) = ".date('y')."")->reg;
			$str = DB::selectOne("select count(id)id from contra  where status=1 and cv_date='".Input::get('rv_date_'.$row)."'

			")->id;
			$rv_no = 'cv'.($str+1);

			$rv_date = Input::get('rv_date_'.$row);
			$cheque_no = Input::get('cheque_no_'.$row);
			$cheque_date = Input::get('cheque_date_'.$row);
			$description = Input::get('description_'.$row);

			$data1['cv_date']   	= $rv_date;
			$data1['cv_no']   		= $rv_no;

			$data1['cheque_no']   		= $cheque_no;
			$data1['cheque_date']   	= $cheque_date;

			$data1['description']   = $description;
			$data1['rv_status']  	= 1;
			$data1['username'] 		= Auth::user()->name;
			$data1['date'] 			= date('Y-m-d');
			$data1['time'] 			= date('H:i:s');
			$master_id=DB::table('contra')->insertGetId($data1);
			$data2['master_id'] 			= $master_id;
			$rvsDataSection = Input::get('rvsDataSection_'.$row);
			foreach($rvsDataSection as $row1){

				$d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
				$c_amount =   str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));



				$account  =  Input::get('account_id_'.$row.'_'.$row1.'');
				if($d_amount !="" && $d_amount >0){
					$data2['debit_credit'] = 1;
					$data2['amount'] = $d_amount;

					$data['debit_credit'] = 1;
					$data['amount'] = $d_amount;
				}

				else if($c_amount !="" && $c_amount >0){
					$data2['debit_credit'] = 0;
					$data2['amount'] = $c_amount;

					$data['debit_credit'] = 0;
					$data['amount'] = $c_amount;
				}

				$data2['cv_no']   		= $rv_no;
				$data2['cv_date']   	= $rv_date;
				$data2['acc_id'] 		= $account;
				$data2['description']   = $description;
				$data2['rv_status']   	= 1;
				$data2['status']  		= 1;
				$data2['username'] 		= Auth::user()->name;
				$data2['date'] 			= date('Y-m-d');
				$data2['time'] 			= date('H:i:s');
				$data2['master_id'] 			= $master_id;

				DB::table('contra_data')->insert($data2);


				$data['acc_id'] = $account;
				$data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
				$data['particulars'] =$description;
				$data['opening_bal'] = '0';
				$data['voucher_no'] = $rv_no;
				$data['voucher_type'] = 6;
				$data['v_date'] = $rv_date;
				$data['date'] = date("Y-m-d");
				$data['time'] = date("H:i:s");
				$data['master_id'] = $master_id;
				$data['username'] = Auth::user()->name;
				DB::table('transactions')->insert($data);
			}
		}





		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/viewContraVoucherList?pageType=view&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}
	function addPurchaseCashPaymentVoucherDetail(){
		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$str = DB::selectOne("select max(convert(substr(`pv_no`,4,length(substr(`pv_no`,4))-4),signed integer)) reg from `pvs` where substr(`pv_no`,-4,2) = ".date('m')." and substr(`pv_no`,-2,2) = ".date('y')."")->reg;
		$pv_no = 'cpv'.($str+1).date('my');
		$slip_no = Input::get('slip_no');
		$pv_date = Input::get('pv_date');
		$debitAccId = Input::get('supplierAccId');
		$mainDescription = Input::get('main_description');

		$data1['pv_date']   	= $pv_date;
		$data1['pv_no']   		= $pv_no;
		$data1['grn_date']   	= Input::get('grnDate');
		$data1['grn_no']   		= Input::get('grnNo');
		$data1['slip_no']   	= $slip_no;
		$data1['voucherType'] 	= 3;
		$data1['description']   = $mainDescription;
		$data1['username'] 		= Auth::user()->name;
		$data1['approve_username'] 		= Auth::user()->name;
		$data1['pv_status']  	= 2;
		$data1['date'] 			= date('Y-m-d');
		$data1['time'] 			= date('H:i:s');
		DB::table('pvs')->insert($data1);
		$pvsDataSection = Input::get('seletedGoodsReceiptNoteRow');
		$totalDebitAmount = 0;
		foreach($pvsDataSection as $row){
			$c_amount               =  Input::get('credit_'.$row.'');
			$totalDebitAmount       += $c_amount;
			$account                =  Input::get('account_id_'.$row.'');
			$data2['debit_credit']  = 0;
			$data2['amount']        = $c_amount;
			$description            = $mainDescription;

			$data2['pv_no']   		= $pv_no;
			$data2['pv_date']   	= $pv_date;
			$data2['acc_id'] 		= $account;
			$data2['description']   = $description;
			$data2['pv_status']   	= 2;
			$data2['username'] 		= Auth::user()->name;
			$data2['approve_username'] 		= Auth::user()->name;
			$data2['status']  		= 1;
			$data2['date'] 			= date('Y-m-d');
			$data2['time'] 			= date('H:i:s');

			DB::table('pv_data')->insert($data2);
		}

		$d_amount =  Input::get('debit_amount');
		$description = $mainDescription;

		$data3['debit_credit']  = 1;
		$data3['amount']        = $totalDebitAmount;
		$data3['pv_no']   		= $pv_no;
		$data3['pv_date']   	= $pv_date;
		$data3['acc_id'] 		= $debitAccId;
		$data3['description']   = $description;
		$data3['pv_status']   	= 2;
		$data3['username'] 		= Auth::user()->name;
		$data3['approve_username'] 		= Auth::user()->name;
		$data3['status']  		= 1;
		$data3['date'] 			= date('Y-m-d');
		$data3['time'] 			= date('H:i:s');

		DB::table('pv_data')->insert($data3);

		$tableTwoDetail = DB::table('pv_data')
			->where('pv_no', $pv_no)
			->where('status', '1')
			->where('pv_status', '2')->get();
		FinanceHelper::reconnectMasterDatabase();
		foreach ($tableTwoDetail as $row2) {

			$acc_code = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);

			$vouceherType = 4;
			$voucherNo = $row2->pv_no;
			$voucherDate = $row2->pv_date;

			$data4['acc_id'] = $row2->acc_id;
			$data4['acc_code'] = $acc_code;
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
			FinanceHelper::companyDatabaseConnection($_GET['m']);
			DB::table('transactions')->insert($data4);
			FinanceHelper::reconnectMasterDatabase();
		}


		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/viewPurchaseCashPaymentVoucherList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}


	function addPurchaseBankPaymentVoucherDetail(){
		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$str = DB::selectOne("select max(convert(substr(`pv_no`,4,length(substr(`pv_no`,4))-4),signed integer)) reg from `pvs` where substr(`pv_no`,-4,2) = ".date('m')." and substr(`pv_no`,-2,2) = ".date('y')."")->reg;
		$pv_no = 'bpv'.($str+1).date('my');
		$slip_no = Input::get('slip_no');
		$pv_date = Input::get('pv_date');
		$cheque_no = Input::get('cheque_no');
		$cheque_date = Input::get('cheque_date');
		$debitAccId = Input::get('supplierAccId');
		$mainDescription = Input::get('main_description');

		$data1['pv_date']   	= $pv_date;
		$data1['pv_no']   		= $pv_no;
		$data1['grn_date']   	= Input::get('grnDate');
		$data1['grn_no']   		= Input::get('grnNo');
		$data1['slip_no']   	= $slip_no;
		$data1['cheque_no']   	= $cheque_no;
		$data1['cheque_date']   	= $cheque_date;
		$data1['voucherType'] 	= 4;
		$data1['description']   = $mainDescription;
		$data1['username'] 		= Auth::user()->name;
		$data1['approve_username'] 		= Auth::user()->name;
		$data1['pv_status']  	= 2;
		$data1['date'] 			= date('Y-m-d');
		$data1['time'] 			= date('H:i:s');
		//	DB::table('pvs')->insert($data1);
		$master_id=DB::table('pvs')->insertGetId($data1);
		$pvsDataSection = Input::get('seletedGoodsReceiptNoteRow');
		$totalDebitAmount = 0;
		foreach($pvsDataSection as $row){
			$c_amount               =  Input::get('credit_'.$row.'');
			$totalDebitAmount       += $c_amount;
			$account                =  Input::get('account_id_'.$row.'');
			$data2['debit_credit']  = 0;
			$data2['amount']        = $c_amount;
			$description            = $mainDescription;

			$data2['pv_no']   		= $pv_no;
			$data2['pv_date']   	= $pv_date;
			$data2['acc_id'] 		= $account;
			$data2['description']   = $description;
			$data2['pv_status']   	= 2;
			$data2['username'] 		= Auth::user()->name;
			$data2['approve_username'] 		= Auth::user()->name;
			$data2['status']  		= 1;
			$data2['date'] 			= date('Y-m-d');
			$data2['time'] 			= date('H:i:s');
			$data2['master_id'] 			=$master_id;

			DB::table('pv_data')->insert($data2);
		}

		$d_amount =  Input::get('debit_amount');
		$description = $mainDescription;

		$data3['debit_credit']  = 1;
		$data3['amount']        = $totalDebitAmount;
		$data3['pv_no']   		= $pv_no;
		$data3['pv_date']   	= $pv_date;
		$data3['acc_id'] 		= $debitAccId;
		$data3['description']   = $description;
		$data3['pv_status']   	= 2;
		$data3['username'] 		= Auth::user()->name;
		$data3['approve_username'] 		= Auth::user()->name;
		$data3['status']  		= 1;
		$data3['date'] 			= date('Y-m-d');
		$data3['time'] 			= date('H:i:s');
		$data3['master_id'] 			=$master_id;

		DB::table('pv_data')->insert($data3);

		$tableTwoDetail = DB::table('pv_data')
			->where('pv_no', $pv_no)
			->where('status', '1')
			->where('pv_status', '2')->get();
		FinanceHelper::reconnectMasterDatabase();
		foreach ($tableTwoDetail as $row2) {

			$acc_code = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);

			$vouceherType = 4;
			$voucherNo = $row2->pv_no;
			$voucherDate = $row2->pv_date;

			$data4['acc_id'] = $row2->acc_id;
			$data4['acc_code'] = $acc_code;
			$data4['particulars'] = $row2->description;
			$data4['opening_bal'] = '0';
			$data4['debit_credit'] = $row2->debit_credit;
			$data4['amount'] = $row2->amount;
			$data4['voucher_no'] = $voucherNo;
			$data4['voucher_type'] = $vouceherType;
			$data4['v_date'] = $voucherDate;
			$data4['date'] = date("Y-m-d");
			$data4['master_id'] = $master_id;
			$data4['username'] = Auth::user()->name;
			FinanceHelper::companyDatabaseConnection($_GET['m']);
			DB::table('transactions')->insert($data4);
			FinanceHelper::reconnectMasterDatabase();
		}


		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/viewPurchaseBankPaymentVoucherList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}

	function addSaleCashReceiptVoucherDetail(){
		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$str = DB::selectOne("select max(convert(substr(`rv_no`,4,length(substr(`rv_no`,4))-4),signed integer)) reg from `rvs` where substr(`rv_no`,-4,2) = ".date('m')." and substr(`rv_no`,-2,2) = ".date('y')."")->reg;
		$rv_no = 'crv'.($str+1).date('my');
		$slip_no = Input::get('slip_no');
		$rv_date = Input::get('rv_date');
		$creditAccId = Input::get('customerAccId');
		$mainDescription = Input::get('main_description');

		$data1['rv_date']   	= $rv_date;
		$data1['rv_no']   		= $rv_no;
		$data1['inv_date']   	= Input::get('invoiceDate');
		$data1['inv_no']   		= Input::get('invoiceNo');
		$data1['slip_no']   	= $slip_no;
		$data1['voucherType'] 	= 4;
		$data1['sale_receipt_type'] 	= 1;
		$data1['description']   = $mainDescription;
		$data1['username'] 		= Auth::user()->name;
		$data1['approve_username'] 		= Auth::user()->name;
		$data1['rv_status']  	= 2;
		$data1['date'] 			= date('Y-m-d');
		$data1['time'] 			= date('H:i:s');
		DB::table('rvs')->insert($data1);
		$rvsDataSection = Input::get('seletedInvoiceRow');
		$totalCreditAmount = 0;
		foreach($rvsDataSection as $row){
			$d_amount               =  Input::get('debit_'.$row.'');
			$totalCreditAmount       += $d_amount;
			$account                =  Input::get('account_id_'.$row.'');
			$data2['debit_credit']  = 1;
			$data2['amount']        = $d_amount;
			$description            = $mainDescription;

			$data2['rv_no']   		= $rv_no;
			$data2['rv_date']   	= $rv_date;
			$data2['acc_id'] 		= $account;
			$data2['description']   = $description;
			$data2['rv_status']   	= 2;
			$data2['username'] 		= Auth::user()->name;
			$data2['approve_username'] 		= Auth::user()->name;
			$data2['status']  		= 1;
			$data2['date'] 			= date('Y-m-d');
			$data2['time'] 			= date('H:i:s');

			DB::table('rv_data')->insert($data2);
		}

		$d_amount =  Input::get('debit_amount');
		$description = $mainDescription;

		$data3['debit_credit']  = 0;
		$data3['amount']        = $totalCreditAmount;
		$data3['rv_no']   		= $rv_no;
		$data3['rv_date']   	= $rv_date;
		$data3['acc_id'] 		= $creditAccId;
		$data3['description']   = $description;
		$data3['rv_status']   	= 2;
		$data3['username'] 		= Auth::user()->name;
		$data3['approve_username'] 		= Auth::user()->name;
		$data3['status']  		= 1;
		$data3['date'] 			= date('Y-m-d');
		$data3['time'] 			= date('H:i:s');

		DB::table('rv_data')->insert($data3);

		$tableTwoDetail = DB::table('rv_data')
			->where('rv_no', $rv_no)
			->where('status', '1')
			->where('rv_status', '2')->get();
		FinanceHelper::reconnectMasterDatabase();
		foreach ($tableTwoDetail as $row2) {

			$acc_code = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);

			$vouceherType = 4;
			$voucherNo = $row2->rv_no;
			$voucherDate = $row2->rv_date;

			$data4['acc_id'] = $row2->acc_id;
			$data4['acc_code'] = $acc_code;
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
			FinanceHelper::companyDatabaseConnection($_GET['m']);
			DB::table('transactions')->insert($data4);
			FinanceHelper::reconnectMasterDatabase();
		}


		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/viewSaleCashReceiptVoucherList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}

	function addSaleBankReceiptVoucherDetail(){
		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$str = DB::selectOne("select max(convert(substr(`rv_no`,4,length(substr(`rv_no`,4))-4),signed integer)) reg from `rvs` where substr(`rv_no`,-4,2) = ".date('m')." and substr(`rv_no`,-2,2) = ".date('y')."")->reg;
		$rv_no = 'brv'.($str+1).date('my');
		$slip_no = Input::get('slip_no');
		$rv_date = Input::get('rv_date');
		$cheque_no = Input::get('cheque_no');
		$cheque_date = Input::get('cheque_date');
		$creditAccId = Input::get('customerAccId');
		$mainDescription = Input::get('main_description');

		$data1['rv_date']   	= $rv_date;
		$data1['rv_no']   		= $rv_no;
		$data1['inv_date']   	= Input::get('invoiceDate');
		$data1['inv_no']   		= Input::get('invoiceNo');
		$data1['slip_no']   	= $slip_no;
		$data1['cheque_no']   	= $cheque_no;
		$data1['cheque_date']   	= $cheque_date;
		$data1['voucherType'] 	= 4;
		$data1['sale_receipt_type'] 	= 2;
		$data1['description']   = $mainDescription;
		$data1['username'] 		= Auth::user()->name;
		$data1['approve_username'] 		= Auth::user()->name;
		$data1['rv_status']  	= 2;
		$data1['date'] 			= date('Y-m-d');
		$data1['time'] 			= date('H:i:s');
		DB::table('rvs')->insert($data1);
		$rvsDataSection = Input::get('seletedInvoiceRow');
		$totalCreditAmount = 0;
		foreach($rvsDataSection as $row){
			$d_amount               =  Input::get('debit_'.$row.'');
			$totalCreditAmount       += $d_amount;
			$account                =  Input::get('account_id_'.$row.'');
			$data2['debit_credit']  = 1;
			$data2['amount']        = $d_amount;
			$description            = $mainDescription;

			$data2['rv_no']   		= $rv_no;
			$data2['rv_date']   	= $rv_date;
			$data2['acc_id'] 		= $account;
			$data2['description']   = $description;
			$data2['rv_status']   	= 2;
			$data2['username'] 		= Auth::user()->name;
			$data2['approve_username'] 		= Auth::user()->name;
			$data2['status']  		= 1;
			$data2['date'] 			= date('Y-m-d');
			$data2['time'] 			= date('H:i:s');

			DB::table('rv_data')->insert($data2);
		}

		$d_amount =  Input::get('debit_amount');
		$description = $mainDescription;

		$data3['debit_credit']  = 0;
		$data3['amount']        = $totalCreditAmount;
		$data3['rv_no']   		= $rv_no;
		$data3['rv_date']   	= $rv_date;
		$data3['acc_id'] 		= $creditAccId;
		$data3['description']   = $description;
		$data3['rv_status']   	= 2;
		$data3['username'] 		= Auth::user()->name;
		$data3['approve_username'] 		= Auth::user()->name;
		$data3['status']  		= 1;
		$data3['date'] 			= date('Y-m-d');
		$data3['time'] 			= date('H:i:s');

		DB::table('rv_data')->insert($data3);

		$tableTwoDetail = DB::table('rv_data')
			->where('rv_no', $rv_no)
			->where('status', '1')
			->where('rv_status', '2')->get();
		FinanceHelper::reconnectMasterDatabase();
		foreach ($tableTwoDetail as $row2) {

			$acc_code = FinanceHelper::getAccountCodeByAccId($row2->acc_id,$_GET['m']);

			$vouceherType = 4;
			$voucherNo = $row2->rv_no;
			$voucherDate = $row2->rv_date;

			$data4['acc_id'] = $row2->acc_id;
			$data4['acc_code'] = $acc_code;
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
			FinanceHelper::companyDatabaseConnection($_GET['m']);
			DB::table('transactions')->insert($data4);
			FinanceHelper::reconnectMasterDatabase();
		}


		FinanceHelper::reconnectMasterDatabase();
		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/viewSaleBankReceiptVoucherList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
	}

	public function addEmployeeTaxDetail(Request $request)
	{
		CommonHelper::companyDatabaseConnection(Input::get('m'));
		$Employee = Employee::find(Input::get('employee_id'));
		$Employee->tax_id = $request->tax_id;
		$Employee->save();
		CommonHelper::reconnectMasterDatabase();

		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/viewEmployeeTaxList?pageType=viewlist&&parentCode=20&&m='.Input::get('m').'');

	}

	public function addEmployeeEOBIDetail(Request $request)
	{
		CommonHelper::companyDatabaseConnection(Input::get('m'));
		$Employee = Employee::find(Input::get('employee_id'));
		$Employee->eobi_id = $request->eobi_id;
		$Employee->save();
		CommonHelper::reconnectMasterDatabase();

		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('finance/viewEmployeeEOBIList?pageType=viewlist&&parentCode=20&&m='.Input::get('m').'');

	}

	public function addDepartmentForm(Request $request)
	{
		$name=$request->dept_name;
		$department=new FinanceDepartment();
		$department=$department->SetConnection('mysql2');
		$department_count=$department->where('status',1)->where('name',$name)->count();
		if ($department_count >0):
			Session::flash('dataDelete',$name.' '.'Already Exists.');
			return Redirect::to('finance/createDepartmentForm?pageType=add&&parentCode=82&&m=1#SFR');
		else:
			$parent_code = $request->account_id;
			$sent_code = $parent_code;


			if ($request->first_level==1):
				$max_id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `finance_department` WHERE `first_level`=1')->id;
				$code=$max_id+1;
				$level=$code;
				$department->level1=$level;

			else:


				$max_id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `finance_department` WHERE `parent_code` LIKE \''.$parent_code.'\'')->id;
				if($max_id == '')
				{
					$code = $sent_code.'-1';
				}
				else
				{
					$max_code2 = DB::connection('mysql2')->selectOne('SELECT `code`  FROM `finance_department` WHERE `id` LIKE \''.$max_id.'\'')->code;
					$max_code2;
					$max = explode('-',$max_code2);
					$code = $sent_code.'-'.(end($max)+1);
				}



				$level_array = explode('-',$code);
				$counter = 1;
				foreach($level_array as $level):
					$levell='level'.$counter.'';
					$department->$levell=$level;

					$counter++;
				endforeach;


			endif;
			$department->code = $code;
			$department->parent_code = $parent_code;
			$department->first_level = $request->first_level;
			$department->name=$name;
			$department->username=Auth::user()->name;
			$department->date=date('Y-m-d');
			$department->save();
			Session::flash('dataInsert','successfully saved.');
			return Redirect::to('finance/createDepartmentForm?pageType=add&&parentCode=82&&m=1#SFR');
		endif;

	}

	public function addCostCenterForm(Request $request)
	{
		$name=$request->cost_center;
		$cost_center=new CostCenter();
		//$cost_center=$cost_center->SetConnection('mysql2');
	//	$cost_center_count=$cost_center->where('status',1)->where('name',$name)->count();
		$cost_center_count=0;
		if ($cost_center_count >0):
			Session::flash('dataDelete',$name.' '.'Already Exists.');
			return Redirect::to('finance/createCostCenterForm?pageType=add&&parentCode=82&&m=1#SFR');
		else:
			$parent_code = $request->parent_cost_center;
			$sent_code = $parent_code;
			if ($request->first_level==1):
				$max_id = DB::selectOne('SELECT max(`id`) as id  FROM `cost_center` WHERE `first_level`=1')->id;
				$code=$max_id+1;
				$level=$code;
				$cost_center->level1=$level;
			else:
				$max_id = DB::selectOne('SELECT max(`id`) as id  FROM `cost_center` WHERE `parent_code` LIKE \''.$parent_code.'\'')->id;
				if($max_id == '')
				{
					$code = $sent_code.'-1';
				}
				else
				{
					$max_code2 = DB::selectOne('SELECT `code`  FROM `cost_center` WHERE `id` LIKE \''.$max_id.'\'')->code;
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
			$MenuType = 0 ;
			$MainMenuId = $request->MainMenuId;
			$SubMenuId = $request->SubMenuId;

			$cost_center->code = $code;
			$cost_center->parent_code = $parent_code;
			$cost_center->first_level = $request->first_level;
			$cost_center->name=$name;
			$cost_center->main_menu_id=$MainMenuId;
			$cost_center->sub_menu_id=$SubMenuId;
			$cost_center->menu_type=$request->MenuType;
			$cost_center->username=Auth::user()->name;
			$cost_center->date=date('Y-m-d');
			$cost_center->save();
			Session::flash('dataInsert','successfully saved.');
			return Redirect::to('finance/createCostCenterForm?pageType=add&&parentCode=82&&m=1#SFR');
		endif;

	}

	function addBankPaymentVoucherDetail_through_jvs(Request $request)
	{



		FinanceHelper::companyDatabaseConnection($_GET['m']);
		$pvsDataSection = Input::get('pvsDataSection_1');
		count($pvsDataSection);
		$pvsSection = Input::get('pvsSection');
		$type = Input::get('type');
		foreach($pvsSection as $row){
			//
			//$str = DB::selectOne("select max(convert(substr(`pv_no`,4,length(substr(`pv_no`,4))-4),signed integer)) reg from `pvs` where substr(`pv_no`,-4,2) = ".date('m')." and substr(`pv_no`,-2,2) = ".date('y')."")->reg;
			$payment_mod = Input::get('payment_mod');
			if ($payment_mod==1):
				$str = DB::selectOne("select count(id)id from pvs where status=1 and pv_date='".Input::get('pv_date_'.$row)."'
			and voucherType=2
			")->id;
				$pv_no = 'pv'.($str+1);
				$v_type=2;
				$cheque_no = Input::get('cheque_no_'.$row);
			else:
				$str = DB::selectOne("select count(id)id from pvs where status=1 and pv_date='".Input::get('pv_date_'.$row)."'
			and voucherType=1
			")->id;
				$pv_no = 'pv'.($str+1);
				$v_type=1;
				$cheque_no = '';
			endif;

			$slip_no = Input::get('slip_no_'.$row);
			$pv_date = Input::get('pv_date_'.$row);
			//	$cheque_no = Input::get('cheque_no_'.$row);
			$cheque_date = Input::get('cheque_date_'.$row);
			$description = Input::get('description_'.$row);
			$cheque_status=Input::get('with_cheque');
			$data1['pv_date']   	= $pv_date;
			$data1['pv_no']   		= $pv_no;
			$data1['slip_no']   	= $slip_no;
			$data1['slip_no']   	= $slip_no;
			$data1['with_cheque']   	= $cheque_status;
			if ($cheque_status==0):
				$data1['cheque_no']   		= $cheque_no;
				$data1['cheque_date']   	= $cheque_date;
			endif;

			$data1['voucherType'] 	= $v_type;
			$data1['description']   = $description;
			$data1['username'] 		= Auth::user()->name;
			$data1['pv_status']  	= 1;
			$data1['date'] 			= date('Y-m-d');
			$data1['time'] 			= date('H:i:s');
			$data1['payment_type'] 			=Input::get('type');
			if (Input::get('type')==2)
			{
				$data1['advance_paid'] 			=1;
			}
			$id= Input::get('purchase_id');
			$data1['purchase_id'] 			=$id;

			$data1['btl_exempt']=Input::get('optradio');
			$data1['exempt_code']=Input::get('VendorCode');

			$master_id = DB::table('pvs')->insertGetId($data1);
			$pvsDataSection = Input::get('pvsDataSection_'.$row);
			$count=1;
			$other_count=1;
			foreach($pvsDataSection as $row1)
			{
				$d_amount =  str_replace(',','',Input::get('d_amount_'.$row.'_'.$row1.''));
				$c_amount =   str_replace(',','',Input::get('c_amount_'.$row.'_'.$row1.''));
				$account  = Input::get('account_id_'.$row.'_'.$row1.'');
				$sub_item  = Input::get('sub_item_id_'.$row.'_'.$row1.'');
				$qty  = Input::get('qty_'.$row.'_'.$row1.'');
				$rate  = Input::get('rate_'.$row.'_'.$row1.'');
				if ($account!=''):
					if($d_amount >0)
					{


						$data2['debit_credit'] = 1;
						$data2['amount'] = $d_amount;
						$data['debit_credit'] = 1;
						$data['amount'] = $d_amount;
					}
					else if($c_amount >0)
					{
						$data2['debit_credit'] = 0;
						$data2['amount'] = $c_amount;
						$data['debit_credit'] = 0;
						$data['amount'] = $c_amount;
					}

					$data2['pv_no']   		= $pv_no;
					if (Input::get('type')==1):
						$data2['sub_item']   		= $sub_item;
					endif;
					$data2['pv_date']   	= $pv_date;
					$data2['acc_id'] 		= $account;
					$data2['description']   = $description;
					$data2['qty']   = $qty;
					$data2['rate']   = $rate;
					$data2['pv_status']   	= 1;
					$data2['username'] 		= Auth::user()->name;
					$data2['status']  		= 1;
					$data2['date'] 			= date('Y-m-d');
					$data2['time'] 			= date('H:i:s');
					$data2['master_id']=$master_id;

					//	DB::table('pv_data')->insert($data2);
					$other_id = DB::table('pv_data')->insertGetId($data2);

					$data['acc_id'] = $account;

					$data['particulars'] =$description;
					$data['opening_bal'] = '0';
					$data['voucher_no'] = $pv_no;
					$data['voucher_type'] = 2;
					$data['v_date'] = $pv_date;
					$data['date'] = date("Y-m-d");
					$data['time'] = date("H:i:s");
					$data['master_id'] = $master_id;
					$data['username'] = Auth::user()->name;
					DB::table('transactions')->insert($data);


					// for Inocme Tax Deduction
					if ($row1 >2 && $row1 <11):

						if ($type==3):
							if ($request->input('c_amount_1_'.$row1)>0):
								$get_income_tax_count=$row1-2;
								$income_tax_deduction= new IncomeTaxDeduction();
								$income_tax_deduction=$income_tax_deduction->SetConnection('mysql2');
								$income_tax_deduction->pvs_id=$master_id;
								$income_tax_deduction->pv_data_id=$other_id;
								$income_tax_deduction->pv_no=$pv_no;
								$income_tax_deduction->nature=$request->input('nature'.$get_income_tax_count);
								$income_tax_deduction->income_tax_slab_id=$request->input('income_tax_id'.$get_income_tax_count);
								$income_tax_deduction->tax_payment_section=$request->input('tax_payment_section'.$get_income_tax_count);
								$income =   str_replace(',','',$request->input('income'.$get_income_tax_count));
								$income_tax_deduction->amount=$income;
								$c_amount =   str_replace(',','',$request->input('c_amount_'.$row.'_'.$row1.''));
								$income_tax_deduction->deduct_amount=$c_amount;
								$income_tax_deduction->save();
							endif;
						endif;
					endif;

					// For FBR Tax Deduction

					if ($row1==11):
						if ($type==3):
							$fbr = new FbrTxtDeduction();
							$fbr=$fbr->SetConnection('mysql2');
							$fbr->pv_id=$master_id;
							$fbr->pv_data_id=$other_id;
							$fbr->pv_no=$pv_no;
							$amount =   str_replace(',','',$request->input('fbr_amount'));
							$fbr->amount=$amount;
							$c_amount =   str_replace(',','',$request->input('c_amount_'.$row.'_'.$row1.''));
							$fbr->deduct_amount=$c_amount;
							$fbr->register_in_sales_tax=$request->registerd;
							$fbr->active_in_sales_tax=$request->active_status;
							$fbr->advertisment_service=$request->advertisment;
							$fbr->save();
						endif;

					endif;


					// For SRB Tax Deduction
					if ($row1==12):
						if ($type==3):
							$srb= new SrbTxtDeduction();
							$srb=$srb->SetConnection('mysql2');
							$srb->pv_id=$master_id;
							$srb->pv_data_id=$other_id;
							$srb->pv_no=$pv_no;
							$amount =   str_replace(',','',$request->input('srb_amount'));
							$srb->amount=$amount;
							$c_amount =   str_replace(',','',$request->input('c_amount_'.$row.'_'.$row1.''));
							$srb->deduct_amount=$c_amount;
							$srb->register_in_srb=$request->registerd_in_srb;
							$srb->advertisment=$request->advertisment_srb;
							$srb->exclusion=$request->exclusion;
							$srb->exclusion_type=$request->exclusion_type;
							$srb->percent=$request->srb_percent;
							$srb->save();
						endif;
					endif;

					// For PRA Tax Deduction
					if ($row1==13):
						if ($type==3):
							$pra= new PraTxtDeduction();
							$pra=$pra->SetConnection('mysql2');
							$pra->pv_id=$master_id;
							$pra->pv_data_id=$other_id;
							$pra->pv_no=$pv_no;
							$amount =   str_replace(',','',$request->input('pra_amount'));
							$pra->amount=$amount;
							$c_amount =   str_replace(',','',$request->input('c_amount_'.$row.'_'.$row1.''));
							$pra->deduct_amount=$c_amount;
							$pra->register_in_punjab_sales_tax=$request->registerd_in_pra;
							$pra->company=$request->company_pra;
							$pra->active=$request->active_pra;
							$pra->percent=$request->pra_percent;
							$pra->advertisment=$request->advertisment_pra;
							$pra->save();

						endif;
					endif;
					// for department

					// End for Cost center department
				endif;

				$count++;
			}

		}

		$breakup_dataa=Input::get('breakup_amount');
		$jv_id=Input::get('jv_id');
		$main_id=Input::get('main_id');
		$slip_no=Input::get('slip_no');
		$count_breakup=0;
		foreach($breakup_dataa as $row):
			$breakup_data['jv_id']=$jv_id[$count_breakup];
			$breakup_data['debit_credit']=1;
			$breakup_data['main_id']=$main_id[$count_breakup];
			$breakup_data['pv_id']=$master_id;
			$breakup_data['supplier_id']=$request->supp;
			$breakup_data['slip_no']=$slip_no[$count_breakup];
			$breakup_data['amount']=CommonHelper::check_str_replace($row);
			$breakup_data['voucher_type']=2;
			DB::Connection('mysql2')->table('breakup_data')->insert($breakup_data);
			$count_breakup++;
		endforeach;

		$type = Input::get('type');
		if ($type==3):
			$id= Input::get('purchase_id');
			$id=explode(',',$id);
			$data3['jv_status']=3;
			$data3['paid']=1;
			$data3['payment_id']=$master_id;
			DB::Connection('mysql2')->table('jvs')->whereIn('id', $id)->update($data3);
		endif;
		Session::flash('dataInsert','successfully saved.');


		$page_type='view';
		$parent_code=22;

		return Redirect::to('finance/viewOutstanding_bills_through_jvs?pageType='.$page_type.'&&parentCode='.$parent_code.'&&m='.$_GET['m'].'#SFR');
//		if ($type==3):
//			$page_type='view';
//			$parent_code=22;
//			//	endforeach;
//			FinanceHelper::reconnectMasterDatabase();
//			if ($payment_mod==1):
//				return Redirect::to('finance/viewBankPaymentVoucherList?pageType='.$page_type.'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
//			else:
//				return Redirect::to('finance/viewCashPaymentVoucherList?pageType='.$page_type.'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
//			endif;
//		else:
//			return Redirect::to('finance/viewOutstanding_bills_through_jvs?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
//		endif;
	}

	function updateBankReceiptVoucherDetail_against_sales()
	{

		DB::Connection('mysql2')->beginTransaction();
		try {
			//FinanceHelper::companyDatabaseConnection($_GET['m']);
			$rvs_id = Input::get('rvs_id');
			$rv_no = Input::get('rv_no');
			$rvsSection = Input::get('rvsSection');
			foreach($rvsSection as $row){

				$slip_no = Input::get('slipno');
				$rv_date = Input::get('rv_date_'.$row);
				$cheque_no = Input::get('cheque_no_'.$row);
				$cheque_date = Input::get('cheque_date_'.$row);
				$description = Input::get('description_'.$row);

				//$data1['rv_no']   		= $rv_no;
				$data1['rv_date']   	= $rv_date;
				$data1['ref_bill_no']   = $slip_no;
				$data1['cheque_no']   	= $cheque_no;
				$data1['cheque_date']   = $cheque_date;
				$data1['rv_type'] 		= 1;
				$data1['description']   = $description;
				$data1['rv_status']  	= 1;
				$data1['username'] 		= Auth::user()->name;
				$data1['date'] 			= date('Y-m-d');
				$data1['status'] 		= 1;
				$data1['sales']			= Input::get('sales');
				//$data1['currency_id']=		Input::get('curren');
				//$data1['exchange_rate']=		Input::get('exchange_rate');
				//$data1['foreign_currency']=		CommonHelper::check_str_replace(Input::get('exchange_amunt'));
				DB::Connection('mysql2')->table('new_rvs')->where('id',$rvs_id)->update($data1);
				DB::Connection('mysql2')->table('new_rv_data')->where('master_id',$rvs_id)->delete();

				$data2['master_id'] = $rvs_id;
				$rvsDataSection = Input::get('rvsDataSection_'.$row);
				foreach($rvsDataSection as $row1)
				{
					$d_amount =  Input::get('d_amount_'.$row.'_'.$row1.'');
					$d_amount = CommonHelper::check_str_replace($d_amount);

					$c_amount =  Input::get('c_amount_'.$row.'_'.$row1.'');
					$c_amount = CommonHelper::check_str_replace($c_amount);
					$account  =  Input::get('account_id_'.$row.'_'.$row1.'');
					$branch  =  Input::get('branch_id_'.$row.'_'.$row1.'');
					$desc  =  Input::get('desc_'.$row.'_'.$row1.'');

					if($d_amount >0 )
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
					$data2['acc_id'] 		= $account;
					$data2['paid_to_id']   	= $branch;
					$data2['description']   = $desc;
					$data2['paid_to_type']   = 5;
					$data2['rv_status']   	= 1;
					$data2['status']  		= 1;
					DB::Connection('mysql2')->table('new_rv_data')->insert($data2);


					$data['acc_id'] = $account;
					//	$data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
					$data['particulars'] =$description;
					$data['opening_bal'] = '0';
					$data['voucher_no'] = $rv_no;
					$data['voucher_type'] = 3;
					$data['v_date'] = $rv_date;
					$data['date'] = date("Y-m-d");
					$data['time'] = date("H:i:s");
					$data['master_id'] = $rvs_id;
					$data['username'] = Auth::user()->name;
					//DB::table('transactions')->insert($data);
				}
			}

			DB::Connection('mysql2')->table('received_paymet')->where('receipt_id',$rvs_id)->delete();

			$sales=Input::get('sales');
			if ($sales==1):
				$slip_no=	Input::get('slip_no');
				$amount=	Input::get('amount');


				$sales_tax_invoice_id=	Input::get('ids');
				$count=0;
				foreach($slip_no as $row):

					$data3['sales_tax_invoice_id']=$sales_tax_invoice_id[$count];
					$data3['receipt_id']=$rvs_id;
					$data3['sales_tax_invoice_no']=$row;
					$data3['receipt_no']=$rv_no;
					$amount =   CommonHelper::check_str_replace(Input::get('amount')[$count]);
					$data3['received_amount']=$amount;
					$data3['slip_no']=$row;
					$count++;
					DB::Connection('mysql2')->table('received_paymet')->insert($data3);
				endforeach;

			endif;

			$voucher_no     = $rv_no;
			$voucher_date   = $rv_date;
			$action_type    = 1;
			$client_id      = '';
			$table_name     = "rvs";
			CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);

			//FinanceHelper::reconnectMasterDatabase();
			DB::Connection('mysql2')->commit();
		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e->getMessage());
		}

		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('sales/receiptVoucherList?pageType=add&&parentCode=24&&m='.$_GET['m'].'#SFR');
	}

	function addBankReceiptVoucherDetail_against_sales(Request $request){
		//echo "<pre>";
		//print_r($_POST); die;
		//FinanceHelper::companyDatabaseConnection($_GET['m']);
		DB::Connection('mysql2')->beginTransaction();
		try {
		$rvsSection = Input::get('rvsSection');
		foreach($rvsSection as $row){
			$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
			//$str = DB::selectOne("select count(id)id from new_rvs where status=1 and rv_date='".Input::get('rv_date_'.$row)."' ")->id;
			$slip_no = Input::get('slipno');
			$rv_date = Input::get('rv_date_'.$row);
			$cheque_no = Input::get('cheque_no_'.$row);
			$cheque_date = Input::get('cheque_date_'.$row);
			$description = Input::get('description_'.$row);

			$data1['rv_no']   		= $rv_no;
			$data1['rv_date']   	= $rv_date;
			$data1['ref_bill_no']   = $slip_no;
			$data1['cheque_no']   	= $cheque_no;
			$data1['cheque_date']   = $cheque_date;
			$data1['rv_type'] 		= 1;
			$data1['description']   = $description;
			$data1['rv_status']  	= 1;
			$data1['username'] 		= Auth::user()->name;
			$data1['date'] 			= date('Y-m-d');
			$data1['status'] 		= 1;
			$data1['sales']			= Input::get('sales');
			//$data1['currency_id']=		Input::get('curren');
			//$data1['exchange_rate']=		Input::get('exchange_rate');
			//$data1['foreign_currency']=		CommonHelper::check_str_replace(Input::get('exchange_amunt'));
			$master_id=DB::Connection('mysql2')->table('new_rvs')->insertGetId($data1);

			$data2['master_id'] = $master_id;
			$rvsDataSection = Input::get('rvsDataSection_'.$row);
			foreach($rvsDataSection as $row1)
			{
				$d_amount =  Input::get('d_amount_'.$row.'_'.$row1.'');
				$d_amount = CommonHelper::check_str_replace($d_amount);

				$c_amount =  Input::get('c_amount_'.$row.'_'.$row1.'');
				$c_amount = CommonHelper::check_str_replace($c_amount);
				$account  =  Input::get('account_id_'.$row.'_'.$row1.'');
				$branch  =  Input::get('branch_id_'.$row.'_'.$row1.'');
				$desc  =  Input::get('desc_'.$row.'_'.$row1.'');

				if($d_amount >0 )
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
				$data2['acc_id'] 		= $account;
				$data2['paid_to_id']   	= $branch;
				$data2['description']   = $desc;
				$data2['paid_to_type']   = 5;
				$data2['rv_status']   	= 1;
				$data2['status']  		= 1;
				DB::Connection('mysql2')->table('new_rv_data')->insert($data2);


				$data['acc_id'] = $account;
				//	$data['acc_code'] = FinanceHelper::getAccountCodeByAccId($account,'');
				$data['particulars'] =$description;
				$data['opening_bal'] = '0';
				$data['voucher_no'] = $rv_no;
				$data['voucher_type'] = 3;
				$data['v_date'] = $rv_date;
				$data['date'] = date("Y-m-d");
				$data['time'] = date("H:i:s");
				$data['master_id'] = $master_id;
				$data['username'] = Auth::user()->name;
				//DB::table('transactions')->insert($data);
			}
		}


		$sales=Input::get('sales');
		if ($sales==1):
			$slip_no=	Input::get('slip_no');
			$amount=	Input::get('amount');


			$sales_tax_invoice_id=	Input::get('ids');
			$count=0;
			foreach($slip_no as $row):

				$data3['sales_tax_invoice_id']=$sales_tax_invoice_id[$count];
				$data3['receipt_id']=$master_id;
				$data3['sales_tax_invoice_no']=$row;
				$data3['receipt_no']=$rv_no;
				$amount =   CommonHelper::check_str_replace(Input::get('amount')[$count]);
				$data3['received_amount']=$amount;
				$data3['slip_no']=$row;
				$count++;
				DB::Connection('mysql2')->table('received_paymet')->insert($data3);
			endforeach;

		endif;

		//FinanceHelper::reconnectMasterDatabase();

		$voucher_no     = $rv_no;
		$voucher_date   = $rv_date;
		$action_type    = 1;
		$client_id      = '';
		$table_name     = "rvs";
		CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);
			DB::Connection('mysql2')->commit();
		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e->getMessage());
		}


		Session::flash('dataInsert','successfully saved.');
		return Redirect::to('sales/receiptVoucherList?pageType=add&&parentCode=24&&m='.$_GET['m'].'#SFR');
	}

	public function addPaymentVoucherDetail(Request $request)
	{
//		echo "<pre>";
//		print_r($_POST); die;
		//dd($request);

		DB::Connection('mysql2')->beginTransaction();

		try {

			$pv_no=CommonHelper::uniqe_no_for_purcahseVoucher(date('y'),date('m'));

		$total_salesTax = str_replace('%', '', $request->total_salesTax);
		$NewPurchaseVoucher = new NewPurchaseVoucher();
		$NewPurchaseVoucher = $NewPurchaseVoucher->SetConnection('mysql2');
		$NewPurchaseVoucher->pv_no 	 = $pv_no;
		$NewPurchaseVoucher->pv_date = $request->purchase_date;
		$NewPurchaseVoucher->slip_no = $request->slip_no;
		$NewPurchaseVoucher->bill_date = $request->bill_date;
		$NewPurchaseVoucher->purchase_date = $request->purchase_date;
		$NewPurchaseVoucher->purchase_type = $request->p_type;
		//$NewPurchaseVoucher->current_amount = $request->current_amount;
		$NewPurchaseVoucher->due_date = $request->due_date;
		$NewPurchaseVoucher->supplier = $request->supplier;

//		if($request->sales_tax_percent > 0)
//		{
//			$NewPurchaseVoucher->sales_tax_acc_id=$request->AccId;
//		}
//		$NewPurchaseVoucher->sales_tax_percent=CommonHelper::check_str_replace($request->sales_tax_percent);
//		$NewPurchaseVoucher->sales_tax_amount=CommonHelper::check_str_replace($request->sales_tax_amount);
		$NewPurchaseVoucher->description = $request->description;
		$NewPurchaseVoucher->adjust_amount = $request->advance_from_customer;
		//$currency = $request->curren;
		//$currency = explode(',', $currency);
		//$NewPurchaseVoucher->currency = $currency[0];

		$NewPurchaseVoucher->username = Auth::user()->name;
		$NewPurchaseVoucher->date = date('Y-m-d');
		$NewPurchaseVoucher->save();
		$master_id = $NewPurchaseVoucher->id;


		$purchase_voucher_dataa = $request->demandDataSection_1;
		$total_amount=0;
		$Counter=1;
		foreach ($purchase_voucher_dataa as $row):

			$NewPurchaseVoucherData = new NewPurchaseVoucherData();
			$NewPurchaseVoucherData = $NewPurchaseVoucherData->SetConnection('mysql2');

			$NewPurchaseVoucherData->master_id = $master_id;
			$NewPurchaseVoucherData->pv_no = $pv_no;
			$NewPurchaseVoucherData->category_id = $request->input('category_id_1_' . $Counter);
			$NewPurchaseVoucherData->sub_item = $request->input('sub_item_id_1_' . $Counter);

			$NewPurchaseVoucherData->uom = $request->input('uom_id_1_' . $Counter);
			$NewPurchaseVoucherData->qty = $request->input('qty_1_' . $Counter);
			$NewPurchaseVoucherData->rate = $request->input('rate_1_' . $Counter);
			$NewPurchaseVoucherData->amount = str_replace(',', '', $request->input('amounttd_1_' . $Counter));
			$total_amount+=str_replace(',', '', $request->input('amounttd_1_' . $Counter));

			//$NewPurchaseVoucherData->sales_tax_per = $request->input('accounts_1_' . $Counter);
			//$NewPurchaseVoucherData->sales_tax_amount = $request->input('sales_tax_amount_1_' . $Counter);
			//$NewPurchaseVoucherData->net_amount = $request->input('net_amount_1_' . $Counter);
			//$NewPurchaseVoucherData->txt_nature = $request->input('txt_nature_1_' . $Counter);
			//$NewPurchaseVoucherData->income_txt_nature = $request->input('income_txt_nature_1_' . $Counter);

			$NewPurchaseVoucherData->username = Auth::user()->name;
			$NewPurchaseVoucherData->date = date('Y-m-d');
//			$purchase_voucher->pv_no = $pv_no;
			$NewPurchaseVoucherData->save();
//			$other_id = $purchase_voucher_data->id;
		$Counter++;
		endforeach;
		FinanceHelper::audit_trail($pv_no,$request->purchase_date,$total_amount,4,'Insert');
			DB::Connection('mysql2')->commit();
		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e->getMessage());

		}
		return Redirect::to('finance/createPurchaseVoucherForm?pageType=add&&parentCode=117&&m=1#SFR');
	}

	public function addPaidTo(Request $request)
	{


		$PaidTo = new PaidTo();
		$PaidTo = $PaidTo->SetConnection('mysql2');
		$PaidTo->name   = $request->PaidToName;
		$PaidTo->type   = $request->Type;
		$PaidTo->mobil_no   = $request->MobilNo;
		$PaidTo->status=1;
		$PaidTo->date=date('Y-m-d');
		$PaidTo->username = Auth::user()->name;
		$PaidTo->save();
		$m=Input::get('m');
		return Redirect::to('finance/paidToCreateAndView?pageType=add&&parentCode=118&&m='.$m.'#SFR');
	}

	public function updatePurchaseVoucher(Request $request)
	{
		DB::Connection('mysql2')->beginTransaction();

		try {
			$purchase_voucher_dataa = $request->demandDataSection_1;
			//print_r($purchase_voucher_dataa); die();
			$SalesTaxAccId = 0;
			$SalesTaxAmount = 0;

			$NewPurchaseVoucher = new NewPurchaseVoucher();
			$NewPurchaseVoucher = $NewPurchaseVoucher->SetConnection('mysql2');
			$NewPurchaseVoucher=$NewPurchaseVoucher->find($request->EditId);
			$NewPurchaseVoucher->pv_date = $request->purchase_date;
			$NewPurchaseVoucher->slip_no = $request->slip_no;
			$NewPurchaseVoucher->bill_date = $request->bill_date;
			$NewPurchaseVoucher->purchase_date = $request->purchase_date;
			$NewPurchaseVoucher->purchase_type = $request->p_type;
			//$NewPurchaseVoucher->current_amount = $request->current_amount;
			$NewPurchaseVoucher->due_date = $request->due_date;
			$NewPurchaseVoucher->supplier = $request->supplier;
			$NewPurchaseVoucher->description = $request->description;

			if($request->input('SalesTaxesAccId') !="")
			{
				$SalesTaxAccId = $request->input('SalesTaxesAccId');
				$SalesTaxAmount = $request->input('SalesTaxAmount');
			}else
			{
				$SalesTaxAccId = 0;
				$SalesTaxAmount = 0;
			}

			//$currency = $request->curren;
			//$currency = explode(',', $currency);
			//$NewPurchaseVoucher->currency = $currency[0];
			$NewPurchaseVoucher->sales_tax_acc_id =$SalesTaxAccId;
			$NewPurchaseVoucher->sales_tax_amount = $SalesTaxAmount;
			$NewPurchaseVoucher->username = Auth::user()->name;
			$NewPurchaseVoucher->date = date('Y-m-d');
			$NewPurchaseVoucher->save();
			$NewPurchaseVoucherData = new NewPurchaseVoucherData();
			$NewPurchaseVoucherData = $NewPurchaseVoucherData->SetConnection('mysql2');

			$NewPurchaseVoucherData->where('master_id', $request->EditId)->delete();


			$total_amount=0;
			$Counter=1;

			foreach ($purchase_voucher_dataa as $row):

				$NewPurchaseVoucherData = new NewPurchaseVoucherData();
				$NewPurchaseVoucherData = $NewPurchaseVoucherData->SetConnection('mysql2');

				$NewPurchaseVoucherData->master_id = $request->EditId;
				$NewPurchaseVoucherData->pv_no = $request->pv_no;
				if($request->input('grn_data_id_' . $Counter) != ''):
					$NewPurchaseVoucherData->grn_data_id = $request->input('grn_data_id_' . $Counter);
				endif;
				$NewPurchaseVoucherData->category_id = $request->input('category_id_1_' . $Counter);
				$NewPurchaseVoucherData->sub_item = $request->input('sub_item_id_1_' . $Counter);

				$NewPurchaseVoucherData->uom = $request->input('uom_id_1_' . $Counter);
				$NewPurchaseVoucherData->qty = $request->input('qty_1_' . $Counter);
				$NewPurchaseVoucherData->rate = $request->input('rate_1_' . $Counter);
				$NewPurchaseVoucherData->amount = str_replace(',', '', $request->input('amounttd_1_' . $Counter));
				$total_amount+=str_replace(',', '', $request->input('amounttd_1_' . $Counter));

				//$NewPurchaseVoucherData->sales_tax_per = $request->input('accounts_1_' . $Counter);
				//$NewPurchaseVoucherData->sales_tax_amount = $request->input('sales_tax_amount_1_' . $Counter);
				$NewPurchaseVoucherData->net_amount = $request->input('net_amount' . $Counter);
				//$NewPurchaseVoucherData->txt_nature = $request->input('txt_nature_1_' . $Counter);
				//$NewPurchaseVoucherData->income_txt_nature = $request->input('income_txt_nature_1_' . $Counter);

				$NewPurchaseVoucherData->username = Auth::user()->name;
				$NewPurchaseVoucherData->date = date('Y-m-d');
	//			$purchase_voucher->pv_no = $pv_no;
				$NewPurchaseVoucherData->save();
	//			$other_id = $purchase_voucher_data->id;
				$Counter++;
			endforeach;
			FinanceHelper::audit_trail($request->pv_no,$request->purchase_date,$total_amount,4,'Update');
			DB::Connection('mysql2')->commit();
		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e->getMessage());

		}

		return Redirect::to('purchase/viewPurchaseVoucherListThroughGrn?pageType=add&&parentCode=117&&m=1#SFR');

	}

	public function addExpenseVoucherDetail(Request $request)
	{

		DB::Connection('mysql2')->beginTransaction();

		try
		{
			$EvNo = CommonHelper::get_unique_ev_no();
			$InsertEv['ev_no'] = $EvNo;
			$InsertEv['ev_date'] = $request->VoucherDate;
			$InsertEv['debit_acc_id'] = $request->DebitAccId;
			$InsertEv['credit_acc_id'] = $request->CreditAccId;
			$InsertEv['description'] = $request->Desc;

			$InsertEv['status'] = 1;
			$InsertEv['date'] = date('Y-m-d');
			$InsertEv['username'] = Auth::user()->name;
			$MasterId = DB::Connection('mysql2')->table('expense_voucher')->insertGetId($InsertEv);
			$Loop=$request->SoNo;

			foreach($Loop as $key=>$row):

				$InsertEvData['master_id'] = $MasterId;
				$InsertEvData['ev_no'] = $EvNo;
				$InsertEvData['so_no'] = $request->input('SoNo')[$key];
				$InsertEvData['amount'] = $request->input('Amount')[$key];
				$InsertEvData['status'] = 1;
				//$request->input('warehouse_from')[$key],
				DB::Connection('mysql2')->table('expense_voucher_data')->insert($InsertEvData);
//				echo "<pre>";
//				print_r($InsertEvData);
				endforeach;
			DB::Connection('mysql2')->commit();
		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e->getMessage());
		}
		return Redirect::to('finance/expenseVoucherList?pageType=&&parentCode=152&&m='.$_GET['m'].'#SFR');
	}


	public function addSalesReceipt(Request $request)
	{
		DB::Connection('mysql2')->beginTransaction();
		// try {
			$rv_type = 0;
			$bank = 0;
			$select_acc_id = 0;
			if ($request->pay_mode == '1,1'):
				$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'), date('m'), 1);
				$rv_type = 2;
				$pay_mode = 1;
				$rv_type = 1;
				$bank = $request->bank;
				$select_acc_id = $request->bank;
			elseif ($request->pay_mode == '2,2'):
				$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'), date('m'), 2);
				$pay_mode = 2;
				$rv_type = 2;
				$select_acc_id = $request->acc_id;
			elseif ($request->pay_mode == '3,1'):
				$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'), date('m'), 1);
				$rv_type = 1;
				$pay_mode = 3;
				$bank = $request->bank;
			endif;



			$data = array
			(
				'rv_no' => $rv_no,
				'rv_date' => $request->v_date,
				'ref_bill_no' => $request->ref_bill_no,
				'cheque_no' => $request->cheque,
				'cheque_date' => $request->cheque_date,
				'rv_type' => $rv_type,
				//'description'=>$request->ref_bill_no,
				'rv_status' => 1,
				'username' => Auth::user()->name,
				'date' => date('Y-m-d'),
				'sales' => 1,
				'status' => 1,
				'description' => $request->desc,
				'bank' => $bank,
				'pay_mode' => $pay_mode
			);

			$master_id = DB::Connection('mysql2')->table('new_rvs')->insertGetId($data);

			$brig = $request->si_id;
			$net_amount = 0;
			$tax_amount = 0;
			$tax_acc_id = 0;
			$total_amount = 0;
			$discount_amount = 0;

			foreach ($brig as $key => $row):
				$data1 = array
				(
					'si_id' => $row,
					'so_id' => $request->input('so_id')[$key],
					'rv_id' => $master_id,
					'rv_no' => $rv_no,
					'received_amount' => CommonHelper::check_str_replace($request->input('receive_amount')[$key]),
					'tax_percent' => $request->input('percent')[$key],
					'tax_amount' => $request->input('tax_amount')[$key],
					'discount_amount' => $request->input('discount')[$key],
					'net_amount' => CommonHelper::check_str_replace($request->input('net_amount')[$key]),
				);
				// if ($request->input('percent')[$key] != 0):
				// 	$tax_acc_id = CommonHelper::generic('gst', array('percent' => $request->input('percent')[$key], 'id' => $request->input('tax_rate_id')[$key]), 'acc_id')->first()->acc_id;
				// endif;
				// dd($tax_acc_id);

				$net_amount += CommonHelper::check_str_replace($request->input('receive_amount')[$key]);
				$discount_amount += $request->input('discount')[$key];

				if ($request->input('percent')[$key] != 0):
					$tax_amount += $request->input('tax_amount')[$key];
				else:
					$tax_amount += 0;
				endif;
				$total_amount += CommonHelper::check_str_replace($request->input('net_amount')[$key]);
				DB::Connection('mysql2')->table('brige_table_sales_receipt')->insert($data1);

				$received_paymet = array
				(
					'sales_tax_invoice_id' => $row,
					'receipt_id' => $master_id,
					'receipt_no' => $rv_no,
					'received_amount' => CommonHelper::check_str_replace($request->input('receive_amount')[$key]),
					'slip_no' => $request->cheque,
					'status' => 1,
				);
				DB::Connection('mysql2')->table('received_paymet')->insert($received_paymet);
			endforeach;

			$transaction = new Transactions();
			$transaction = $transaction->SetConnection('mysql2');

			$data2 = array
			(
				'master_id' => $master_id,
				'rv_no' => $rv_no,
				'acc_id' => $select_acc_id,
				'amount' => $total_amount - $tax_amount,
				'debit_credit' => 1,
				'status' => 1,
				'rv_status' => 1,
				'description' => $request->desc,

			);
			DB::Connection('mysql2')->table('new_rv_data')->insert($data2);

			if ($tax_amount > 0):
				
				$tax_acc_id = 780;
				$data3 = array
				(
					'master_id' => $master_id,
					'rv_no' => $rv_no,
					'acc_id' => $tax_acc_id,
					'amount' => $tax_amount,
					'debit_credit' => 1,
					'status' => 1,
					'rv_status' => 1,
					'description' => $request->desc,
				);
				DB::Connection('mysql2')->table('new_rv_data')->insert($data3);

			endif;

			if ($discount_amount > 0):
				$disc_acc_id = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('name', 'SALE DISCOUNT (LIABLITY)')->first()->id;
				$data6 = array
				(
					'master_id' => $master_id,
					'rv_no' => $rv_no,
					'acc_id' => $disc_acc_id,
					'amount' => $discount_amount,
					'debit_credit' => 1,
					'status' => 1,
					'rv_status' => 1,
					'description' => $request->desc,
				);
				DB::Connection('mysql2')->table('new_rv_data')->insert($data6);
			endif;

			$customer_acc_id = SalesHelper::get_customer_acc_id($request->buyers_id);
			$data4 = array
			(
				'master_id' => $master_id,
				'rv_no' => $rv_no,
				'acc_id' => $customer_acc_id,
				'amount' => $net_amount,
				'debit_credit' => 0,
				'status' => 1,
				'rv_status' => 1,
				'description' => $request->desc,
			);
			DB::Connection('mysql2')->table('new_rv_data')->insert($data4);

			SalesHelper::sales_activity($rv_no, $request->v_date, $total_amount, 5, 'Insert');

			DB::Connection('mysql2')->commit();

		// } catch (\Exception $e) {
		// 	DB::Connection('mysql2')->rollback();
		// 	echo "EROOR"; //die();
		// 	dd($e->getMessage());
		// 	dd($e->getFile());

		// }

		$SavePrintVal = Input::get('SavePrintVal');

		//Testing Redirect
		if ($SavePrintVal == 1) {
			$Url = url('sdc/viewReceiptVoucherDirect?id=' . $master_id . '&&pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Session::get('run_company') . '#Murtaza Corp');
			return Redirect::to($Url);
		} else {
			return Redirect::to('sales/receiptVoucherList?m=' . $request->m);
		}
		die();
	}





	public function pos_payment(Request $request)
	{
		DB::Connection('mysql2')->beginTransaction();
		try
		{
			$rv_type=0;
			$bank=0;

			if ($request->pay_mode=='1,1'):
				$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
				$rv_type=2;
				$pay_mode=1;
				$rv_type=1;
				$bank=$request->bank;
			elseif ($request->pay_mode=='2,2'):
				$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),2);
				$pay_mode=2;
				$rv_type=2;
			elseif ($request->pay_mode=='3,1'):
				$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
				$rv_type=1;
				$pay_mode=3;
				$bank=$request->bank;
			endif;



			$data=array
			(
				'rv_no'=>$rv_no,
				'rv_date'=>$request->v_date,
				'ref_bill_no'=>$request->ref_bill_no,
				'cheque_no'=>$request->cheque,
				'cheque_date'=>$request->cheque_date,
				'rv_type'=>$rv_type,
				//'description'=>$request->ref_bill_no,
				'rv_status'=>1,
				'username'=>Auth::user()->name,
				'date'=>date('Y-m-d'),
				'sales'=>1,
				'status'=>1,
				'description'=>$request->desc,
				'bank'=>$bank,
				'pay_mode'=>$pay_mode


			);

			$master_id=DB::Connection('mysql2')->table('new_rvs')->insertGetId($data);

			$brig=$request->pos_id;
			$net_amount=0;
			$tax_amount=0;
			$tax_acc_id=0;
			$total_amount=0;
			$discount_amount=0;

			foreach($brig as $key=>$row):
				$data1=array
				(
					'pos_id'=>$row,
					'rv_id'=>$master_id,
					'rv_no'=>$rv_no,
					'received_amount'=>CommonHelper::check_str_replace($request->input('receive_amount')[$key]),
					'discount_amount'=>$request->input('discount_amount')[$key],
					'net_amount'=>CommonHelper::check_str_replace($request->input('net_amount')[$key]),
					'type'=>1
				);

				$net_amount+=CommonHelper::check_str_replace($request->input('receive_amount')[$key]);
				$discount_amount+=$request->input('discount_amount')[$key];


				$total_amount+=CommonHelper::check_str_replace($request->input('net_amount')[$key]);
				DB::Connection('mysql2')->table('brige_table_sales_receipt')->insert($data1);



				$received_paymet=array
				(
					'pos_id'=>$row,
					'receipt_id'=>$master_id,
					'receipt_no'=>$rv_no,
					'received_amount'=>CommonHelper::check_str_replace($request->input('receive_amount')[$key]),
					'slip_no'=>$request->cheque,
					'status'=>1,
					'type'=>1
				);
				DB::Connection('mysql2')->table('received_paymet')->insert($received_paymet);
			endforeach;

			$transaction=new Transactions();
			$transaction=$transaction->SetConnection('mysql2');
			//   $count=$transaction->where('acc_id',$row->acc_id)->where('opening_bal',1)->count();





			$data2=array
			(
				'master_id'=>$master_id,
				'rv_no'=>$rv_no,
				'acc_id'=>813,
				'amount'=>$total_amount,
				'debit_credit'=>1,
				'description'=>'',
				'status'=>1,
				'rv_status'=>1,
				'rv_status'=>1,

			);
			DB::Connection('mysql2')->table('new_rv_data')->insert($data2);




			if ($discount_amount>0):
				$disc_acc_id=DB::Connection('mysql2')->table('accounts')->where('status',1)->where('name','Sales Discount')->first()->id;
				$data6=array
				(
					'master_id'=>$master_id,
					'rv_no'=>$rv_no,
					'acc_id'=>$disc_acc_id,
					'amount'=>$discount_amount,
					'debit_credit'=>1,
					'description'=>'',
					'status'=>1,
					'rv_status'=>1,
				);
				DB::Connection('mysql2')->table('new_rv_data')->insert($data6);
			endif;



		//	$customer_acc_id=	SalesHelper::get_customer_acc_id($request->buyers_id);



			$data4=array
			(
				'master_id'=>$master_id,
				'rv_no'=>$rv_no,
				'acc_id'=>380,
				'amount'=>$net_amount,
				'debit_credit'=>0,
				'description'=>'',
				'status'=>1,
				'rv_status'=>1,
			);
			DB::Connection('mysql2')->table('new_rv_data')->insert($data4);
			// lala



		//	SalesHelper::sales_activity($rv_no,$request->pos_date,$total_amount,5,'Insert');

			DB::Connection('mysql2')->commit();

		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e->getMessage());

		}

		$SavePrintVal = Input::get('SavePrintVal');

		//Testing Redirect
		if($SavePrintVal == 1)
		{
			$Url = url('sdc/viewReceiptVoucherDirect?id='.$master_id.'&&pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.Session::get('run_company').'#Murtaza Corp');
			return Redirect::to($Url);
		}
		else
		{
			return Redirect::to('sales/receiptVoucherList?m='.Session::get('run_company'));
		}
		die();
	}





	public function add_role(Request $request)
	{


		if(empty($request->category))
        {
            $category = 0 ; //implode($request->category , ',');
        }
        else
        {
            $category = implode(',', $request->category);
        }
        if(empty($request->dashboard_access))
        {
            $dashboard_access = 0 ; //implode($request->category , ',');
        }
        else
        {
            $dashboard_access = implode(',', $request->dashboard_access);
        }

		$user = User::find($request->users);
        
        if ($user) {
            $user->update([
							'categories_id' => $category,
							'dashboard_access' => $dashboard_access,
            			]);
        }


		$m = $request->m;



		// for main
		$main_menu_id = $request->main;
		if(!empty($main_menu_id))
		{
	
			$main_menu_id = implode(',',$main_menu_id);


			// for sub
			$sub_menu_id = $request->sub;
			$sub_menu_id = implode(',',$sub_menu_id);


			// for crud

			if (!empty($request->rights)):
				
				$rights = $request->rights;
				
				$rights = implode(',',$rights);

				if ($request->CompanyId == 1):
					$data2['crud_rights'] = $rights;
				elseif($request->CompanyId == 2):
					$data2['crud_rights_2'] = $rights;
				elseif($request->CompanyId == 3):
					$data2['crud_rights_3'] = $rights;
				elseif($request->CompanyId == 5):
					$data2['crud_rights_4'] = $rights;
				endif;

					DB::table('users')->where('emp_code',$request->users)->update($data2);
			endif;



			$data1['main_modules'] = $main_menu_id;
			$data1['emp_code'] = $request->users;
			$data1['submenu_id'] = $sub_menu_id;
			$data1['compnay_id'] = $request->CompanyId;


			$count_to_be_check = DB::table('menu_privileges')->where('emp_code',$request->users)->where('compnay_id',$request->CompanyId)->count();

			if ($count_to_be_check > 0):

				DB::table('menu_privileges')->where('emp_code',$request->users)->where('compnay_id',$request->CompanyId)->update($data1);
			
			else:
			
				DB::table('menu_privileges')->insert($data1);
			
			endif;
		}

		return Redirect::to('finance/viewCostCenterList?m='.$m);
	}

	public function commision_form(Request $request)
	{
		DB::Connection('mysql2')->beginTransaction();
		try
		{
			$id  = DB::Connection('mysql2')->selectOne('SELECT MAX(id) as id  FROM `commision`')->id;
			if ($id!=''):
			$co_no  = DB::Connection('mysql2')->selectOne('SELECT voucher_no  FROM `commision` where id =  '.$id.'')->voucher_no;
				else:
					$co_no='co0';
					endif;
			$co_no= substr($co_no,2);
			$co_no = $co_no + 1;
			$co_no = sprintf("%'03d", $co_no);

			$data=$request->brigde_id;

			if (!empty($data)):

				$data1=array
				(
					'voucher_no'=>$co_no,
					'from'=>$request->from,
					'to'=>$request->to,
					'agent'=>$request->agent,
					'status'=>1,
					'date'=>date('Y-m-d'),
					'username'=>Auth::user()->name,
				);
				$id=DB::Connection('mysql2')->table('commision')->insertGetId($data1);

				foreach($data as $key => $row):
				$data2=array
				(
					'master_id'=>$id,
					'voucher_no'=>$request->from,
					'brigde_id'=>$row,
					'percent'=>$request->input('percent')[$key],
					'amount'=>$request->input('amount')[$key],
					'commision_amount'=>$request->input('commision')[$key],
					'no_of_days'=>$request->input('no_days')[$key],
					'cond'=>$request->input('cond')[$key],
					'status'=>1,

				);
				DB::Connection('mysql2')->table('commision_data')->insert($data2);

				endforeach;

				endif;


			DB::Connection('mysql2')->commit();
		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
		//	echo "EROOR"; //die();
			dd($e->getMessage());

		}
		return Redirect::to('finance/commission');
	}
}
