<?php

namespace App\Http\Controllers;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\ReuseableCode;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;


use App\Models\PurchaseVoucher;
use App\Models\PurchaseVoucherData;
use App\Models\Transactions;
use App\Models\DepartmentAllocation1;
use App\Models\SalesTaxDepartmentAllocation;
use App\Models\CostCenterDepartmentAllocation;
use App\Helpers\FinanceHelper;
use App\Models\PurchaseRequest;
use App\Models\Stock;
use App\Models\PurchaseRequestData;
use App\Models\GoodsReceiptNote;
use App\Models\GRNData;
use App\Models\SubItemCharges;


class PurchaseEditDetailControler extends Controller
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

    public function editSupplierDetail(Request $request){

        CommonHelper::companyDatabaseConnection($_GET['m']);
        $id = Input::get('supplier_id');
        $name = Input::get('vendor_name');
        $company_name = Input::get('company_name');
        $country = Input::get('country');
        $state = Input::get('state');
        $city = Input::get('city');
        $email = Input::get('email');
        $o_blnc_trans = Input::get('o_blnc_trans');
        $register_income_tax=Input::get('regd_in_income_tax');
        $business_type=Input::get('optradiooo');
        $ntn = Input::get('ntn');
        $cnic = Input::get('cnic');
        $regd_in_sales_tax = Input::get('register_sales_tax');
        $strn = Input::get('strn');
        $regd_in_srb = Input::get('register_srb');
        $srb = Input::get('srb');
        $regd_in_pra = Input::get('regd_in_pra');
        $pra = Input::get('pra');

        $contact_person = Input::get('contact_person');
        $contact_no  = Input::get('mobile_no');
        $address  = Input::get('address');
        $registration_no = Input::get('registration_no');
        $product_services_provided = Input::get('product_services_provided');
        $contact_person_no = Input::get('contact_person_no');
        $contact_person_email = Input::get('contact_person_email');
        $account_representative_name = Input::get('account_representative_name');
        $account_representative_no = Input::get('account_representative_no');
        $account_representative_email = Input::get('account_representative_email');
        $no_of_days = Input::get('no_of_days');
        $account_title = Input::get('account_title');
        $account_no = Input::get('account_no');
        $ibn = Input::get('ibn');

        $company_status = (Input::get('company_status'))? Input::get('company_status') : [];  
        $company_status = (count($company_status) > 0 ) ? implode(', ', $company_status) : '';

        $print_check_as = Input::get('print_check_as');
        $vendor_type = Input::get('vendor_type');
        $website = Input::get('website');
        $credit_limit = Input::get('credit_limit');
        $acc_no = Input::get('acc_no');
        $bank_name = Input::get('bank_name');
        $bank_address = Input::get('bank_address');
        $branch_name = Input::get('branch_name');
        $swift_code = Input::get('swift_code');
        $open_date = Input::get('open_date');
        $term = Input::get('term');

        $address = Input::get('address');
        $o_blnc = Input::get('o_blnc');

        $data2['resgister_income_tax'] = strip_tags($register_income_tax);
        $data2['business_type']     = strip_tags($business_type);
        $data2['cnic']     	        = strip_tags($cnic);
        $data2['ntn']     	        = strip_tags($ntn);
        $data2['register_sales_tax'] = strip_tags($regd_in_sales_tax);
        $data2['strn']     	        = strip_tags($strn);
        $data2['register_srb']      = $regd_in_srb;
        $data2['srb']     	        = strip_tags($srb);
        $data2['register_pra']     	= strip_tags($regd_in_pra);
        $data2['pra']     	        = strip_tags($pra);
        $data2['name']     		    = strip_tags($name);
        $data2['company_name']     	= strip_tags($company_name);
        $data2['country']     		= strip_tags($country);
        $data2['province']     	    = strip_tags($state);
        $data2['city']     	        = strip_tags($city);
        $data2['email']   		    = strip_tags($email);
        $data2['username']	 	    = Auth::user()->name;
        $data2['date']     		    = date("Y-m-d");
        $data2['time']     		    = date("H:i:s");
        $data2['action']     		= 'create';
        $data2['company_id']        = $_GET['m'];
        $data2['company_status']    = $company_status;

        $data2['print_check_as']     		= $print_check_as;
        $data2['vendor_type']	 	    = $vendor_type;
        $data2['website']     		    = $website;
        $data2['credit_limit']     		    = $credit_limit;
        $data2['acc_no']     		= $acc_no;
        $data2['bank_name']     		= $bank_name;
        $data2['bank_address']     		= $bank_address;
        $data2['swift_code']     		= $swift_code;
        $data2['branch_name']     		= $branch_name;
        $data2['opening_bal_date']     		= $open_date;
        $data2['terms_of_payment'] = $term;
        $data2['contact_person'] = $contact_person;
        $data2['mobile_no'] = $contact_no;
        $data2['address'] = $address;
        $data2['registration_no'] = $registration_no;
        $data2['product_services_provided'] = $product_services_provided;
        $data2['contact_person_no'] = $contact_person_no;
        $data2['contact_person_email'] = $contact_person_email;
        $data2['account_representative_name'] = $account_representative_name;
        $data2['account_representative_no'] = $account_representative_no;
        $data2['account_representative_email'] = $account_representative_email;
        $data2['no_of_days'] = $no_of_days;
        $data2['account_title'] = $account_title;
        $data2['account_no'] = $account_no;
        $data2['ibn'] = $ibn;


        DB::table('supplier')->where('id',$id)->update($data2);



        $acc_data['name']=strip_tags($name);
        $acc_id=CommonHelper::get_supplier_acc_id($id);
        DB::table('accounts')->where('id',$acc_id)->update($acc_data);


        $data3['debit_credit'] = $request->nature;
        $data3['amount'] = $request->o_blnc;
        $data3['opening_bal'] = 1;
        $data3['username'] = Auth::user()->name;
        $data3['date'] = date("Y-m-d");
        $data3['action'] = 'update';
        DB::table('transactions')->where('acc_id',$acc_id)->where('opening_bal',1)->update($data3);



        DB::table('supplier_info')->where('supp_id',$id)->delete();


        $contact_person = Input::get('contact_person');
        $contact_no  = Input::get('contact_no');
        $fax  = Input::get('fax');
        $address  = Input::get('address');
        $work_phone  = Input::get('work_phone');


        // foreach($contact_person as $key => $row)
        // {
        //     if($contact_person[$key] != "" || $contact_no[$key] !="" || $fax[$key] !="" || $address[$key] !="" || $work_phone[$key] !="")
        //     {
        //         $InfoData['supp_id'] = $id;
        //         $InfoData['contact_person'] = $contact_person[$key];
        //         $InfoData['contact_no'] = $contact_no[$key];
        //         $InfoData['fax'] = $fax[$key] ?? '';
        //         $InfoData['address'] = $address[$key];
        //         $InfoData['work_phone'] = $work_phone[$key] ?? 0;
        //         DB::Connection('mysql2')->table('supplier_info')->insert($InfoData);
        //     }
        // }
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('purchase/viewSupplierList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    public function editSubItemDetail(Request $request)
    {
        $EditId = Input::get('EditId');
        $CategoryId = Input::get('CategoryId');
        $item_code = Input::get('item_code');
        $sub_item_name = Input::get('sub_item_name');
        $uom_id = Input::get('uom_id');
        $uom2 = Input::get('uom2');
        $pack_size = Input::get('pack_size');
        $hs_code_id = Input::get('hs_code_id');
        $rate = Input::get('rate');
        $remark = Input::get('remark');
        $packing_type = Input::get('packing_type');

        $maintain_inventory = Input::get('maintain');
        $itemType = Input::get('maintain');
        $UpdateData['sub_ic'] = $sub_item_name;
        $UpdateData['description'] = $sub_item_name;
        $UpdateData['uom'] = $uom_id;
        $UpdateData['pack_size'] = $request->pack_size;
        $UpdateData['primary_pack_type'] = $request->primary_pack_type ?? 0;
        $UpdateData['uom2'] = $uom2;
        $UpdateData['secondary_pack_size'] = $request->secondary_pack_size;
        $UpdateData['secondary_pack_type'] = $request->secondary_pack_type ?? 0;
        $UpdateData['hs_code_id'] = $hs_code_id;
        $UpdateData['color'] = $request->color;
        $UpdateData['rate'] = $rate;
        $UpdateData['item_code'] = $request->item_code;
        $UpdateData['sku_code'] = $request->sku ?? '';
        $UpdateData['sub_category_id'] = $request->SubCategoryId;
        //    $UpdateData['maintain_inventory'] = $maintain_inventory;
        $UpdateData['stockType'] = $itemType;
        $UpdateData['remark'] = $remark;
        $UpdateData['packing_type'] = $packing_type;
        DB::Connection('mysql2')->table('subitem')->where('id',$EditId)->update($UpdateData);

        Stock::where('status',1)->where('opening',1)->where('sub_item_id',$EditId)->delete();
        foreach($request->warehouse as $key => $row):
            Stock::create([
                'voucher_type' => 1,
                'sub_item_id' => $EditId,
                'batch_code' => 0,
                'qty' => $request->input('closing_stock')[$key],
                'amount' => $request->input('closing_val')[$key],
                'batch_code' => $request->input('batch_code')[$key],
                'warehouse_id' => $row,
                'opening' => 1,
                'created_date' => date('Y-m-d'),
                'username' => 'Amir Murshad',
                'status' => 1
            ]);

        endforeach;

        ReuseableCode::stockOpening();

        return Redirect::to('purchase/viewSubItemList?pageType=view&&parentCode=2&&m='.Session::get('run_company'));
    }

    public function editCategoryDetail(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $recordId = Input::get('recordId');
        $recordAccId = Input::get('recordAccId');
        $category_name = Input::get('category_name');

        $updateCategoryDetails = array(
            'main_ic' => $category_name
        );

        $updateAccountDetails = array(
            'name' => $category_name
        );

        DB::table('category')
            ->where('status', 1)
            ->where('id', $recordId)
            ->update($updateCategoryDetails);

        DB::table('accounts')
            ->where('id', $recordAccId)
            ->where('status', 1)
            ->update($updateAccountDetails);

        CommonHelper::reconnectMasterDatabase();
        echo 'Done';
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }

    public function editDemandVoucherDetail()
    {


        $id=Input::get('id');
        $m=Input::get('m');
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $slip_no = strip_tags(Input::get('slip_no_1'));
        $demand_date = strip_tags(Input::get('demand_date_1'));
        $demand_type = strip_tags(Input::get('demand_type'));
        $description = strip_tags(Input::get('description_1'));
        $sub_department_id = strip_tags(Input::get('sub_department_id_1'));
        $demandDataSection = Input::get('demandDataSection_1');
        $data1['demand_no'] = Input::get('pr_no');
        $data1['slip_no'] = $slip_no;
        $data1['demand_date'] = $demand_date;
        $data1['description'] = $description;
        $data1['sub_department_id'] = $sub_department_id;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['demand_status'] = 1;
        DB::table('demand')->where('id',$id)->update($data1);



        DB::table('demand_data')->where('master_id', $id)->delete();
        $demandDataSection = Input::get('demandDataSection_1');
        foreach($demandDataSection as $row1){

            $category_id = strip_tags(Input::get('category_id_1_' . $row1 . ''));
            $sub_item_id = strip_tags(Input::get('sub_item_id_1_' . $row1 .''));
            $description = strip_tags(Input::get('description_1_' . $row1 .''));
            $demand_type_id = strip_tags(Input::get('demand_type_id_1_' . $row1 . ''));
            $qty = strip_tags(Input::get('qty_1_' . $row1 .''));
            $data2['demand_no'] = Input::get('pr_no');
            $data2['master_id'] = $id;
            $data2['demand_send_type'] = $demand_type_id;
            $data2['demand_date'] = $demand_date;
            $data2['category_id'] = $category_id;
            $data2['sub_item_id'] = $sub_item_id;
            $data2['description'] = $description;
            $data2['qty'] = $qty;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['username'] = Auth::user()->name;
            $data2['status'] = 1;
            $data2['demand_status'] = 1;
            DB::table('demand_data')->insert($data2);
        }
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('purchase/viewDemandList?m='.$m);
    }

    public function updateDemandDetailandApprove(){
        CommonHelper::companyDatabaseConnection($_GET['m']);

        $demand_no = Input::get('demandNo');
       $p_type = Input::get('v_type');


        $updateDetails = array(
            'demand_status' => 2,
            'approve_username' => Auth::user()->name
        );

        DB::table('demand')
            ->where('demand_no', $demand_no)
            ->update($updateDetails);

        $rowId = Input::get('rowId');


            // $data2['demand_status']   	= 2;

            // $data2['date'] 			    = date('Y-m-d');
            // $data2['time'] 			    = date('H:i:s');


            // DB::table('demand_data')->where('demand_no',$demand_no)->update($data2);

            $dept_ids = NotificationHelper::get_dept_id('demand','demand_no',$demand_no)->select('sub_department_id','p_type')->first();

            $dept_id=$dept_ids->sub_department_id;
            $p_type=$dept_ids->p_type;

            $subject = 'Purchase Request Approved For '.$demand_no;
            NotificationHelper::send_email('Purchase Request','Approve',$dept_id,$demand_no,$subject,$p_type);

        echo 'Done';
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
    }

    function editPurchaseVoucher(Request $request,$id)
    {

        $cn = DB::connection('mysql2');
        $cn->beginTransaction();
        // $str = DB::connection('mysql2')->selectOne("select count(id)id from purchase_voucher where status=1 and purchase_date='".Input::get('purchase_date')."'")->id;
        //  $pv_no = 'pv'.($str+1);
        $total_salesTax=str_replace('%','',$request->total_salesTax);
        $purchase_voucher = new PurchaseVoucher();
        $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
        $purchase_voucher=$purchase_voucher->find($id);
        $purchase_voucher->pv_no=$request->pv_no;
        $purchase_voucher->slip_no=$request->slip_no;
        $purchase_voucher->purchase_date=date("Y-m-d", strtotime($request->purchase_date));
        $purchase_voucher->bill_date=date("Y-m-d", strtotime($request->bill_date));;
        $purchase_voucher->purchase_type=$request->p_type;
        $purchase_voucher->current_amount=$request->current_amount;
        $purchase_voucher->amount_in_words=$request->rupeess;
        $purchase_voucher->due_date=date("Y-m-d", strtotime($request->due_date));;
        $purchase_voucher->supplier=$request->supplier;
        $purchase_voucher->total_qty=$request->total_qty;
        $purchase_voucher->total_rate=$request->total_rate;
        $purchase_voucher->total_amount=$request->total_amount;

        $purchase_voucher->total_salesTax_amount=$request->total_salesTax_amount;
        $purchase_voucher->total_net_amount=$request->total_net_amount;
        $purchase_voucher->username= Auth::user()->name;
        $purchase_voucher->edit_date=date('Y-m-d');
        $purchase_voucher->edit=1;
        $purchase_voucher->description=$request->description;
        $purchase_voucher->pv_date=$request->purchase_date;



        $currency=$request->curren;
        $currency=explode(',',$currency);
        $purchase_voucher->currency=$currency[0];
        $purchase_voucher->save();
        $master_id=$id;
        $id=$id;
        $purchase_voucher_dataa=$request->demandDataSection_1;



        // delete data


        $purchase_voucher_data=new PurchaseVoucherData();
        $purchase_voucher_data=$purchase_voucher_data->SetConnection('mysql2');
        $purchase_voucher_data->where('master_id',$id)->delete();

        $department=new DepartmentAllocation1();
        $department=$department->SetConnection('mysql2');
        $department->where('Main_master_id',$id)->delete();

        $cost_center=new CostCenterDepartmentAllocation();
        $cost_center=$cost_center->SetConnection('mysql2');
        $cost_center->where('Main_master_id',$id)->delete();


        $sales_tax=new SalesTaxDepartmentAllocation();
        $sales_tax=$sales_tax->SetConnection('mysql2');
        $sales_tax->where('Main_master_id',$id)->delete();


        $tran=new Transactions();
        $tran=$tran->SetConnection('mysql2');
        $tran->where('master_id',$id)->where('voucher_type',4)->delete();
        //end


        $count=1;
        foreach($purchase_voucher_dataa as $row):

            $purchase_voucher_data = new PurchaseVoucherData();
            $purchase_voucher_data = $purchase_voucher_data->SetConnection('mysql2');
            $request->input('sales_tax_amount_1_'.$count);
            $purchase_voucher_data->master_id=$master_id;
            $purchase_voucher_data->pv_no=$request->pv_no;
            $purchase_voucher_data->category_id=$request->input('category_id_1_'.$count);;
            $purchase_voucher_data->sub_item=$request->input('sub_item_id_1_'.$count);;

            $purchase_voucher_data->uom=$request->input('uom_id_1_'.$count);;
            $purchase_voucher_data->qty=$request->input('qty_1_'.$count);;
            $purchase_voucher_data->rate=$request->input('rate_1_'.$count);;
            $purchase_voucher_data->amount=str_replace(',','',$request->input('amount_1_'.$count));
            $purchase_voucher_data->sales_tax_per=$request->input('accounts_1_'.$count);
            $purchase_voucher_data->sales_tax_amount=$request->input('sales_tax_amount_1_'.$count);
            $purchase_voucher_data->net_amount=$request->input('net_amount_1_'.$count);;
            $purchase_voucher_data->username=Auth::user()->name;
            $purchase_voucher_data->date=date('Y-m-d');
            $purchase_voucher->pv_no=$request->pv_no;
            $purchase_voucher_data->txt_nature = $request->input('txt_nature_1_' . $count);;
            $purchase_voucher_data->income_txt_nature = $request->input('income_txt_nature_1_' . $count);;
            $purchase_voucher_data->save();
            $other_id=$purchase_voucher_data->id;



            $trans=new Transactions();
            $trans = $trans->SetConnection('mysql2');
            $account=$request->input('category_id_1_'.$count);
            //    $account=CommonHelper::get_item_acc_id($sub_ic_id);

            $trans->acc_id=$account;
            $trans->acc_code=FinanceHelper::getAccountCodeByAccId($account,'');;
            $trans->master_id=$master_id;
            $trans->particulars=$request->description;
            $trans->opening_bal=0;
            $trans->debit_credit=1;
            $trans->amount=str_replace(',','',$request->input('amount_1_'.$count));;
            $trans->voucher_no=$request->pv_no;
            $trans->voucher_type=4;
            $trans->v_date=$request->purchase_date;
            $trans->date=date('Y-m-d');
            $trans->action=1;
            $trans->username=Auth::user()->name;
            $trans->save();

            // for tax
            if ($request->input('accounts_1_'.$count)!=0):
                $trans1=new Transactions();
                $trans1 = $trans1->SetConnection('mysql2');
                $account=$request->input('accounts_1_'.$count);
                $trans1->acc_id=$account;
                $trans1->acc_code=FinanceHelper::getAccountCodeByAccId($account,'');;
                $trans1->master_id=$master_id;
                $trans1->particulars=$request->description;
                $trans1->opening_bal=0;
                $trans1->debit_credit=1;
                $trans1->amount=str_replace(',','',$request->input('sales_tax_amount_1_'.$count));;
                $trans1->voucher_no=$request->pv_no;
                $trans1->voucher_type=4;
                $trans1->v_date=$request->purchase_date;
                $trans1->date=date('Y-m-d');
                $trans1->action=1;
                $trans1->username=Auth::user()->name;
                $trans1->save();
            endif;
            // end for tax

            // for department

            $allow_null=$request->input('dept_check_box'.$count);
            if ($allow_null==0):
                $department1=$request->input('department'.$count);
                $counter=0;
                foreach($department1 as $row1):
                    $dept_allocation1=new DepartmentAllocation1();
                    $dept_allocation1 = $dept_allocation1->SetConnection('mysql2');
                    $dept_allocation1->Main_master_id=$master_id;
                    $dept_allocation1->master_id=$other_id;
                    $dept_allocation1->pv_no=$request->pv_no;
                    $dept_allocation1->dept_id=$row1;
                    $perccent=$request->input('percent'.$count);
                    $dept_allocation1->percent=$perccent[$counter];
                    $amount=$request->input('department_amount'.$count);
                    $amount=str_replace(",","",$amount);
                    $dept_allocation1->amount=$amount[$counter];
                    $dept_allocation1->item=$request->input('sub_item_id_1_'.$count);
                    $dept_allocation1->save();
                    $counter++;

                endforeach;
            endif;

            // end for department


            // for sales tax department

            $allow_null=$request->input('sales_tax_check_box'.$count);
            if ($allow_null==0):
                $sales_tax_department=$request->input('sales_tax_department'.$count);
                $counter=0;
                foreach($sales_tax_department as $row2):
                    if ($row2!=0):
                        $salestaxdepartment=new SalesTaxDepartmentAllocation();
                        $salestaxdepartment = $salestaxdepartment->SetConnection('mysql2');
                        $salestaxdepartment->Main_master_id=$master_id;
                        $salestaxdepartment->master_id=$other_id;
                        $salestaxdepartment->pv_no=$request->pv_no;
                        $salestaxdepartment->dept_id=$row2;
                        $perccent=$request->input('sales_tax_percent'.$count);
                        $salestaxdepartment->percent=$perccent[$counter];
                        $amount=$request->input('sales_tax_department_amount'.$count);
                        $amount=str_replace(",","",$amount);
                        $salestaxdepartment->amount=$amount[$counter];
                        $salestaxdepartment->sales_tax=$request->input('accounts_1_'.$count);

                        $salestaxdepartment->save();
                    endif;
                    $counter++;

                endforeach;
            endif;

            // End for sales tax department


            // for Cost center department

            $allow_null=$request->input('cost_center_check_box'.$count);
            if ($allow_null==0):
                $sales_tax_department=$request->input('cost_center_department'.$count);
                $counter=0;
                foreach($sales_tax_department as $row3):
                    if ($row3!=0):
                        $costcenter=new CostCenterDepartmentAllocation();
                        $costcenter = $costcenter->SetConnection('mysql2');
                        $costcenter->Main_master_id=$master_id;
                        $costcenter->master_id=$other_id;
                        $costcenter->pv_no=$request->pv_no;
                        $costcenter->dept_id=$row3;
                        $perccent=$request->input('cost_center_percent'.$count);
                        $costcenter->percent=$perccent[$counter];
                        $amount=$request->input('cost_center_department_amount'.$count);
                        $amount=$amount[$counter];
                        $amount=str_replace(",","",$amount);
                        $costcenter->amount=$amount;

                        $costcenter->item=$request->input('sub_item_id_1_'.$count);
                        $costcenter->save();
                    endif;
                    $counter++;

                endforeach;
            endif;

            // End for Cost center department


            $count++;  endforeach;


        $trans2=new Transactions();
        $trans2 = $trans2->SetConnection('mysql2');
        $account=CommonHelper::get_supplier_acc_id($request->supplier);
        $trans2->acc_id=$account;
        $trans2->acc_code=FinanceHelper::getAccountCodeByAccId($account,'');;
        $trans2->master_id=$master_id;
        $trans2->particulars=$request->description;
        $trans2->opening_bal=0;
        $trans2->debit_credit=0;
        $trans2->amount=$request->total_net_amount;
        $trans2->voucher_no=$request->pv_no;
        $trans2->voucher_type=4;
        $trans2->v_date=$request->purchase_date;
        $trans2->date=date('Y-m-d');
        $trans2->action=1;
        $trans2->username=Auth::user()->name;

        $trans2->save();

        $cn->rollBack();
        return Redirect::to('pdc/viewPurchaseVoucherDetailAfterSubmit/'.$id);


    }

    function editPurchaseRequestVoucherDetail(Request $request)
    {
         $count=$request->count-1;
        $id=$request->id;
        DB::Connection('mysql2')->beginTransaction();
        try
        {

            // for currency
            $currency=$request->curren;
            $currency=explode(',',$currency);
            // for supplier
            $supplier=$request->supplier_id;
            $supplier=explode('+',$supplier);

            $purchase_orde=new PurchaseRequest();
            $purchase_orde=$purchase_orde->SetConnection('mysql2');
            // for update command
            $purchase_orde = $purchase_orde->find($id);
            $total_amount = str_replace(',', '', Input::get('total_amount'));
            $authentic_po_type=PurchaseHelper::checkPO($id);

            if ($authentic_po_type==$request->po_type):
                $po_no=$request->po_no;
            else:
                $po_date= $request->po_date;
                $year= substr($po_date,2,2);
                $month= substr($po_date,5,2);
                $po_type=$request->po_type;
                $po_no=CommonHelper::get_unique_no($year,$month,$po_type);

            endif;
            $purchase_orde->due_date = $request->due_date;
            $purchase_orde->purchase_request_no=$po_no;
            $purchase_orde->purchase_request_date=$request->po_date;
            $purchase_orde->slip_no=$request->slip_no;
            $purchase_orde->po_type=$request->po_type;
            $purchase_orde->supplier_id=$supplier[0];
            $purchase_orde->term_of_del=$request->term_of_del;
            $purchase_orde->terms_of_paym=$request->model_terms_of_payment;
            
            // Add no_of_days if payment term is Credit (3)
            if($request->model_terms_of_payment == '3') {
                $purchase_orde->no_of_days = $request->no_of_days;
            } else {
                $purchase_orde->no_of_days = null;
            }
            
            $purchase_orde->destination=$request->destination;
            $purchase_orde->currency_id = $currency[0];
            $purchase_orde->currency_rate = $currency[1];
            $purchase_orde->sales_tax=$request->sales_taxx;
            $sales_tax_amount=CommonHelper::check_str_replace($request->sales_amount_td);
            $purchase_orde->sales_tax_amount=$sales_tax_amount;

            $purchase_orde->total_amount = (float)$total_amount;
            $purchase_orde->amount_in_words = Input::get('rupeess');
            $purchase_orde->trn = Input::get('trn');
            $purchase_orde->builty_no = Input::get('builty_no');
            $purchase_orde->description=$request->main_description;
            $purchase_orde->save();

            $count=$request->count-1;
            for ($i=1; $i<=$count; $i++):
                $purchase_orde_id=$request->input('order_data_id'.$i);


                $purchase_order_data= new PurchaseRequestData();
                $purchase_order_data=$purchase_order_data->SetConnection('mysql2');
                $purchase_order_data=$purchase_order_data->find($purchase_orde_id);
                $purchase_order_data->description=$request->input('description_'.$i);
                $purchase_order_data->purchase_request_no=$po_no;
                $purchase_approve_qty_=CommonHelper::check_str_replace($request->input('purchase_approve_qty_'.$i));
                $purchase_order_data->purchase_approve_qty=$purchase_approve_qty_;

                $rate=CommonHelper::check_str_replace($request->input('rate_'.$i));
                $purchase_order_data->rate=$rate;

                $amount=CommonHelper::check_str_replace($request->input('amount_'.$i));
                $purchase_order_data->sub_total=$amount;


                $purchase_order_data->discount_percent=$request->input('discount_percent_'.$i);
                $purchase_order_data->discount_amount=$request->input('discount_amount_'.$i);

                $net_amount_=CommonHelper::check_str_replace($request->input('after_dis_amountt_'.$i));
                $purchase_order_data->net_amount=$net_amount_;
                $purchase_order_data->save();

            endfor;
            DB::Connection('mysql2')->commit();
        }

        catch ( Exception $ex )
        {

            DB::rollBack();

        }
        return Redirect::to('store/viewPurchaseRequestList?pageType=view&&parentCode=44&&m='.$_GET['m'].'#SFR');
    }

//    function editGoodsReceiptNoteDetail(Request $request)
//    {
////        echo "<pre>";
////        print_r($_POST);die;
//        $grn_id = $request->input('grn_id');
//
//        $good_receipt_note = new GoodsReceiptNote();
//        $good_receipt_note = $good_receipt_note->SetConnection('mysql2');
//        $good_receipt_note = $good_receipt_note->find($grn_id);
//        // for update command
//
////        echo $good_receipt_note->grn_no =  $request->input('grn_no');
//        $good_receipt_note->grn_date =  $request->input('grn_date');
//        $prNo = $request->input('po_no');
//        //      echo $good_receipt_note->po_no =  $request->input('po_no');
//        $good_receipt_note->po_date =  $request->input('po_date');
//        $good_receipt_note->demand_type =  $request->input('demandType');
//        $good_receipt_note->bill_date =  $request->input('bill_date');
//        $good_receipt_note->sub_department_id =  $request->input('subDepartmentId');
//        $good_receipt_note->supplier_id =  $request->input('supplierId');
//        $good_receipt_note->main_description =  $request->input('main_description');
//        $good_receipt_note->supplier_invoice_no =  $request->input('invoice_no');
//        $good_receipt_note->delivery_challan_no =  $request->input('del_chal_no');
//        $good_receipt_note->delivery_detail =  $request->input('del_detail');
//        $good_receipt_note->warehouse =  $request->input('warehouse_id');
//        $good_receipt_note->status = 1;
//        $good_receipt_note->grn_status = 1;
//        $good_receipt_note->username = Auth::user()->name;
//        $good_receipt_note->date = date("Y-m-d");
//        $good_receipt_note->time = date("H:i:s");
//        $good_receipt_note->save();
//        //  goods_receipt_note
//
//        $variableCheck=0;
//        $seletedPurchaseRequestRows =  $request->input('seletedPurchaseRequestRow');
//        foreach($seletedPurchaseRequestRows as $seletedPurchaseRequestRow):
//
//            $grndata = new GRNData();
//            $grndata = $grndata->SetConnection('mysql2');
//            $grndata = $grndata->find($seletedPurchaseRequestRow);
//            // print_r($grndata);die;
//
//            // echo $request->input('po_data_id'.$seletedPurchaseRequestRow);
//            // echo $request->input('demandSendType_'.$seletedPurchaseRequestRow);
//            // echo $request->input('categoryId_'.$seletedPurchaseRequestRow);
//            // echo $request->input('subItemId_'.$seletedPurchaseRequestRow);
//            //  echo $request->input('approved_qty_'.$seletedPurchaseRequestRow);
//            $grndata->carton               = $request->input('carton_'.$seletedPurchaseRequestRow);
//            $grndata->packunit             = $request->input('carton_pack_size_'.$seletedPurchaseRequestRow);
//            $grndata->loose_carton         = $request->input('loose_carton_'.$seletedPurchaseRequestRow);
//            $grndata->loose_packunit       = $request->input('loose_pack_size_'.$seletedPurchaseRequestRow);
//            $grndata->purchase_recived_qty = $request->input('total_qty_'.$seletedPurchaseRequestRow);
//            $grndata->bal_reciable         = $request->input('balqty_'.$seletedPurchaseRequestRow);
//            $grndata->remarks              = $request->input('description_'.$seletedPurchaseRequestRow);
//            $grndata->manufac_date         = $request->input('maufac_date_'.$seletedPurchaseRequestRow);
//            $grndata->expiry_date          = $request->input('expiry_date_'.$seletedPurchaseRequestRow);
//            $grndata->batch_no             = $request->input('batch_no_'.$seletedPurchaseRequestRow);
//            $grndata->no_pkg_item          = $request->input('no_pack_per_item_'.$seletedPurchaseRequestRow);
//            $grndata->gross_item           = $request->input('gross_'.$seletedPurchaseRequestRow);
//            $grndata->net_item             = $request->input('net_'.$seletedPurchaseRequestRow);
//            $grndata->rate                 = $request->input('rate_'.$seletedPurchaseRequestRow);
//            $grndata->amount               = $request->input('amount_'.$seletedPurchaseRequestRow);
//            $grndata->status                    = 1;
//            $grndata->grn_status                 = 1;
//            $grndata->username                  = Auth::user()->name;
//            $grndata->date                      = date("Y-m-d");
//            $grndata->time                      = date("H:i:s");
//            $grndata->save();
//
//            $purchase_approve_qty_check = $request->input('approved_qty_'.$seletedPurchaseRequestRow);
//            $po_data_id = $request->input('po_data_id'.$seletedPurchaseRequestRow);
//            $grn_data = DB::Connection('mysql2')->table('grn_data')->select(DB::raw('SUM(purchase_recived_qty) as purchase_recived_qty'))->where('po_data_id',$po_data_id)->groupBy('po_data_id');
//            if($grn_data->count()>0) {
//                $grn_data = $grn_data->first();
//                $grn_purchase_recived_qty = $grn_data->purchase_recived_qty;
//            } else{
//                $grn_purchase_recived_qty = 0;
//            }
//            $total_recieved_qty = $grn_purchase_recived_qty;
//            if($total_recieved_qty < $purchase_approve_qty_check)
//            {
//                $variableCheck = 1;
//            }
//
//
//            $costing = $request->input('costing_'.$seletedPurchaseRequestRow);
//            if($costing==1) {
//                $sub_item_charges_data = CommonHelper::import_costing_exists($seletedPurchaseRequestRow);
//                if ($sub_item_charges_data->count() >0) {
//                    // when update data
//                    $sub_item_charges = new SubItemCharges();
//                    $sub_item_charges = $sub_item_charges->SetConnection('mysql2');
//                    $sub_item_charges = $sub_item_charges->find($seletedPurchaseRequestRow);
//                }else{
//                    // when insert data
//                    $sub_item_charges = new SubItemCharges();
//                    $sub_item_charges = $sub_item_charges->SetConnection('mysql2');
//                    $sub_item_charges->grn_data_id = $seletedPurchaseRequestRow;
//                }
//                //echo $sub_item_charges-> = $request->input('exchange_rate');
//                $sub_item_charges->bank_contract_date = $request->input('bank_contract');
//                $sub_item_charges->commercial_invoice_date = $request->input('commercial_invoice_date');
//                //echo $sub_item_charges-> = $request->input('good_rec_date');
//                $sub_item_charges->bc_ope_chgs = $request->input('b_c_opening_charges_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->bs_ship_guar_chgs = $request->input('b_c_shipping_charges_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->remittance_chgs = $request->input('remittance_charges_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->other_bank_chgs = $request->input('other_bank_charges_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->insurance_expns = $request->input('insurance_exp_' . $seletedPurchaseRequestRow);
//                //echo $sub_item_charges-> = $request->input('cost_in_pkr_'.$seletedPurchaseRequestRow);
//                $sub_item_charges->custom_duty = $request->input('custome_duty_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->additional_custom_duty = $request->input('additional_custom_duty_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->excise_taxation = $request->input('excise_taxation_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->wharfage_gdown_etc = $request->input('whage_godown_charges_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->air_freight = $request->input('air_freight_' . $seletedPurchaseRequestRow);
//                //echo $sub_item_charges->sales_tax = $request->input('total_landed_'.$seletedPurchaseRequestRow);
//                $sub_item_charges->sales_tax = $request->input('sales_tax_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->income_tax = $request->input('income_tax_' . $seletedPurchaseRequestRow);
//                //  echo $sub_item_charges-> = $request->input('total_cash_flow_'.$seletedPurchaseRequestRow);
//                $sub_item_charges->sachet_foli_per_pack = $request->input('sachet_foli_per_pack_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->sachet_foli_per_item = $request->input('sachet_foli_per_item_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->uniit_carton_per_pack = $request->input('uniit_carton_per_pack_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->unit_carton_per_item = $request->input('unit_carton_per_item_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->leaf_insert_per_pack = $request->input('leaf_insert_per_pack_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->leaf_insert_per_item = $request->input('leaf_insert_per_item_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->master_carton_per_pack = $request->input('master_carton_per_pack_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->master_carton_per_item = $request->input('master_carton_per_item_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->packing_cahrges_per_pack = $request->input('packing_cahrges_per_pack_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->packing_cahrges_per_item = $request->input('packing_cahrges_per_item_' . $seletedPurchaseRequestRow);
//                $sub_item_charges->save();
//
//            } else {
//                $sub_item_charges_data = CommonHelper::import_costing_exists($seletedPurchaseRequestRow);
//                if ($sub_item_charges_data->count() >0) {
//                    $sub_item_charges = new SubItemCharges();
//                    $sub_item_charges = $sub_item_charges->SetConnection('mysql2');
//                    $sub_item_charges = $sub_item_charges->find($seletedPurchaseRequestRow);
//                    $sub_item_charges->delete();
//                }
//            }
//        endforeach;
//
//        if($variableCheck==1){
//            $data = array(
//                'purchase_request_status'=>2
//            );
//
//            DB::Connection('mysql2')->table('purchase_request')
//                ->where('purchase_request_no',$prNo)
//                ->where('status',1)
//                ->update($data);
//
//            $data4 = array(
//                'grn_status' => 1,
//                'purchase_request_status'=>2
//            );
//
//            DB::Connection('mysql2')->table('purchase_request_data')
//                ->where('purchase_request_no', $prNo)
//                ->where('status',1)
//                ->update($data4);
//        } else {
//            $data = array(
//                'purchase_request_status'=>3
//            );
//
//            DB::Connection('mysql2')->table('purchase_request')
//                ->where('purchase_request_no',$prNo)
//                ->where('status',1)
//                ->update($data);
//
//            $data4 = array(
//                'grn_status' => 2,
//                'purchase_request_status'=>3
//            );
//
//            DB::Connection('mysql2')->table('purchase_request_data')
//                ->where('purchase_request_no', $prNo)
//                ->where('status',1)
//                ->update($data4);
//        }
//
//        return Redirect::to('purchase/viewGoodsReceiptNoteList?pageType=viewlist&&parentCode=50&&m='.$_GET['m'].'#SFR');
//        // print_r($_POST);
//
//    }

    public function editGoodsReceiptNoteDetail(Request $request)
    {
        $GrnId = $request->grn_id;

        DB::Connection('mysql2')->beginTransaction();
        try {
            $m = $_GET['m'];
            CommonHelper::companyDatabaseConnection($_GET['m']);

            $grn_no = strip_tags(Input::get('grn_no'));
            $prNo = strip_tags(Input::get('po_no'));
            $prDate = strip_tags(Input::get('po_date'));
            $subDepartmentId = strip_tags(Input::get('subDepartmentId'));
            $supplierId = strip_tags(Input::get('supplierId'));
            $invoice_no = strip_tags(Input::get('invoice_no'));
            $grn_date = strip_tags(Input::get('grn_date'));
            $bill_date = strip_tags(Input::get('bill_date'));
            $main_description = strip_tags(Input::get('main_description'));
            $delivery_challan_no=strip_tags(Input::get('del_chal_no'));
            $delivery_vehicale=strip_tags(Input::get('del_detail'));



            $data1['grn_no'] = $grn_no;
            $data1['grn_date'] = $grn_date;
            $data1['po_no'] = $prNo;
            $data1['po_date'] = $prDate;

            $data1['sub_department_id'] = $subDepartmentId;
            $data1['supplier_id'] = $supplierId;
            $data1['bill_date'] = $bill_date;
            $data1['main_description'] = $main_description;
            $data1['supplier_invoice_no'] = $invoice_no;
            $data1['delivery_challan_no'] = $delivery_challan_no;
            $data1['delivery_detail'] = $delivery_vehicale;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['username'] = Auth::user()->name;
            $filePath = '';
            if ($request->hasFile('attachment')) {
                // Get the uploaded file
                $file = $request->file('attachment');

                // Store the file in the specified directory
                $filePath = 'uploads/' . $file->getClientOriginalName();
                $file->move('uploads', $file->getClientOriginalName());
                // Redirect or return a response as needed
            }
            $data1['file_path'] = $filePath;
//            echo "<pre>";
//            print_r($data1);
//            die();

            DB::table('goods_receipt_note')->where('id',$GrnId)->update($data1);
            DB::table('grn_data')->where('master_id',$GrnId)->delete();
            DB::table('addional_expense')->where('main_id',$GrnId)->where('voucher_no',$grn_no)->delete();

            $variableCheck=0;
            $TotAmount = 0;
            $seletedPurchaseRequestRow = Input::get('seletedPurchaseRequestRow');
            foreach ($seletedPurchaseRequestRow as $row1)
            {

                $sub_item_id = strip_tags(Input::get('subItemId_' . $row1 . ''));
                $purchase_approved_qty = strip_tags(Input::get('approved_qty_' . $row1 . ''));
                $batch_code = strip_tags(Input::get('batch_code' . $row1 . ''));
                $purchase_recived_qty = strip_tags(Input::get('rec_qty_' . $row1 . ''));

                $discount_percent = strip_tags(Input::get('discount_percent' . $row1 . ''));
                $discount_amount = strip_tags(Input::get('discount_amount' . $row1 . ''));
                $after_discount_amount = strip_tags(Input::get('after_discount_amount' . $row1 . ''));
                $balance_qty_recived = strip_tags(Input::get('balqty_' . $row1 . ''));
                $warehouse_id = strip_tags(Input::get('warehouse_id_' . $row1 . ''));

                $po_data_id = strip_tags(Input::get('po_data_id' . $row1 . ''));


                $purchase_request_data_check = DB::Connection('mysql2')->table('purchase_request_data')->where('status', 1)->where('id', $po_data_id)->first();
                $purchase_approve_qty_check = $purchase_request_data_check->purchase_approve_qty;

                $grn_data = DB::Connection('mysql2')->table('grn_data')->select(DB::raw('SUM(purchase_recived_qty) as purchase_recived_qty'))->where('po_data_id',$po_data_id)->groupBy('po_data_id');
                if($grn_data->count()>0) {
                    $grn_data = $grn_data->first();
                    $grn_purchase_recived_qty = $grn_data->purchase_recived_qty;
                } else{
                    $grn_purchase_recived_qty = 0;
                }

                $total_recieved_qty = $grn_purchase_recived_qty+$purchase_recived_qty;

                if($total_recieved_qty < $purchase_approve_qty_check)
                {
                    $variableCheck = 1;
                }

                $rate = $purchase_request_data_check->rate;

                $amount = $rate*$purchase_recived_qty;
                $data2['master_id'] = $GrnId;
                $data2['po_data_id'] = $po_data_id;
                $data2['grn_no'] = $grn_no;
                $data2['grn_date'] = $grn_date;
                $data2['sub_item_id'] = $sub_item_id;
                $data2['batch_code'] = $batch_code;
                $data2['purchase_approved_qty'] = $purchase_approved_qty;
                $data2['purchase_recived_qty'] = $purchase_recived_qty;
                $data2['rate'] = $rate;
                $data2['amount'] = $amount;
                $data2['discount_percent'] = $discount_percent;
                $data2['discount_amount'] = $discount_amount;
                $data2['net_amount'] = $after_discount_amount;
                $TotAmount+=$after_discount_amount;
                $data2['bal_reciable'] = $balance_qty_recived;
                $data2['warehouse_id'] = $warehouse_id;
                $data2['description'] = Input::get('des' . $row1 . '');
                $data2['expiry_date'] = Input::get('expiry_date' . $row1 . '');



                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                $data2['username'] = Auth::user()->name;
                $grn_data_id= DB::table('grn_data')->insertGetId($data2);
            }


            $Loop = Input::get('account_id');

            if($Loop !="")
            {
                $Counta = 0;
                foreach($Loop as $LoopFil)
                {
                    $ExpData['voucher_no'] = $grn_no;
                    $ExpData['main_id'] = $GrnId;
                    $ExpData['acc_id'] = Input::get('account_id')[$Counta];
                    $ExpData['amount'] = Input::get('expense_amount')[$Counta];
                    $TotAmount+=Input::get('expense_amount')[$Counta];
                    $ExpData['created_date'] = date('Y-m-d');
                    $ExpData['username'] = Auth::user()->name;
                    $Counta++;
                    DB::table('addional_expense')->insert($ExpData);
                }
            }

//            echo "<pre>";
//            print_r($ExpData);
//            die();

            if($variableCheck==1){
                $data = array(
                    'purchase_request_status' => 2
                );
                DB::table('purchase_request')
                    ->where('purchase_request_no', $prNo)
                    ->where('status', 1)
                    ->update($data);

                $data4 = array(
                    'grn_status' => 1,
                    'purchase_request_status' => 2
                );

                DB::table('purchase_request_data')
                    ->where('purchase_request_no', $prNo)
                    ->where('status', 1)
                    ->update($data4);
            } else {
                $data = array(
                    'purchase_request_status' => 3
                );
                DB::table('purchase_request')
                    ->where('purchase_request_no', $prNo)
                    ->where('status', 1)
                    ->update($data);

                $data4 = array(
                    'grn_status' => 2,
                    'purchase_request_status' => 3
                );

                DB::table('purchase_request_data')
                    ->where('purchase_request_no', $prNo)
                    ->where('status', 1)
                    ->update($data4);
            }

            CommonHelper::reconnectMasterDatabase();

            CommonHelper::inventory_activity($grn_no,$grn_date,$TotAmount,3,'Update');

            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        Session::flash('dataInsert', 'Goods Receipt Note Successfully Saved.');
        return Redirect::to('purchase/viewGoodsReceiptNoteList?pageType=view&&parentCode=001&&m=' . $_GET['m'] . '#SFR');
    }



}
?>
