<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\NewPurchaseVoucherPayment;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\category;
use App\Models\Account;
use App\Models\TaxSection;
use App\Models\Jvs;
use App\Models\Jvs_data;
use App\Models\NewJvs;
use App\Models\NewJvData;
use App\Models\NewRvs;
use App\Models\NewRvData;
use App\Models\Contra;
use App\Models\Contra_data;
use App\Models\Pvs;
use App\Models\Employee;
use App\Models\Pvs_data;
use App\Models\Supplier;
use App\Models\Rvs;
use App\Models\Rvs_data;
use App\Models\GRNData;
use App\Models\InvoiceData;
use App\Models\Invoice;
use App\Models\Department;
use App\Models\Tax;
use App\Models\Eobi;
use App\Models\PurchaseVoucher;
use App\Models\PurchaseVoucherThroughGrn;
use App\Helpers\FinanceHelper;
use App\Models\FinanceDepartment;
use App\Models\CostCenter;
use App\Models\NewPv;
use App\Models\NewPvData;
use App\Models\NewPurchaseVoucher;
use App\Models\NewPurchaseVoucherData;
use App\Models\PaidTo;
use App\Models\Transactions;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;


class AllInOnePaymentVoucherController extends Controller
{
    public function createAllPaymentVoucherForm(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::where('status',1)->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();

        $department=new FinanceDepartment();
        $department=$department->SetConnection('mysql2');
        $department=$department->where('status',1)->select('id','name','code')
            ->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->get();
        $PaidTo = new PaidTo();
        $PaidTo = $PaidTo::where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Finance.createAllPaymentVoucherForm',compact('accounts','department','PaidTo'));

    }

    public function editAllPaymentNew($id)
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();

        $NewPv = new NewPv();
        $NewPv = $NewPv->SetConnection('mysql2');
        $NewPv = $NewPv->where('id',$id)->first();

        $NewPvData = new NewPvData();
        $NewPvData = $NewPvData->SetConnection('mysql2');
        $NewPvData = $NewPvData->where('master_id',$id)->Orderby('id','ASC')->get();

        CommonHelper::reconnectMasterDatabase();
        $departments = Department::where([['status', '=', '1'], ])->select('id','department_name')->orderBy('id')->get();
        return view('Finance.editAllPaymentNew',compact('accounts','NewPv','NewPvData','id', 'departments'));
    }

    public function viewAllPaymentNewVoucherList(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate   = date('Y-m-t');
        $accounts = new Account;
        $accounts = $accounts::where('status',1)->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        $pvs = new NewPv();
        $pvs = $pvs::where('status','=','1')->where('type','=','1')->whereBetween('pv_date',[$currentMonthStartDate,$currentMonthEndDate])->orderBy('id', 'DESC')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Finance.viewAllPaymentNewVoucherList',compact('accounts','pvs'));
    }


    public function get_pv_merge_chunk(Request $request)
    {
        return view('Finance.AjaxPages.pv_merge_chunk');
    }

    public function pv_acount_head_po_pi_chunk(Request $request)
    {
        return view('Finance.AjaxPages.pv_acount_head_po_pi_chunk');
    }



    public function insertAllPayment(Request $request)
    {


//        dd($request);
        if($request->paid_to_type == 0){
            DB::Connection('mysql2')->beginTransaction();
            try {
                $pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),$request->payment_type);
                $payment = new NewPv();
                $payment=$payment->SetConnection('mysql2');
                $payment->pv_no=$pv_no;
                $payment->pv_date=$request->pv_date_1;
                $payment->bill_no=$request->slip_no_1;
                $payment->bill_date=$request->bill_date;
                if($request->payment_type == 1){
                    $payment->cheque_no=$request->cheque_no_1;
                    $payment->cheque_date=$request->cheque_date_1;
                }

                $payment->payment_type=$request->payment_type;
                $payment->description=$request->description_1;
                $payment->date=date('Y-m-d');
                $payment->status=1;
                $payment->payment_for=$request->payment_for;
                $payment->username=Auth::user()->name;
                $payment->pv_status=1;
                $payment->save();
                $master_id=$payment->id;

                $account_data=$request->account_id;
                $index=0;

                $total_amount=0;
                $debit_credit=0;
                foreach($account_data as $row):

                    $pv_data=new NewPvData();
                    $pv_data=$pv_data->SetConnection('mysql2');
                    $pv_data->master_id=$master_id;
                    $pv_data->pv_no=$pv_no;
                    $pv_data->pv_date=$request->pv_date_1;

                    $acc_id=$row;
                    $acc_id=explode(',',$acc_id);


                    $desc=$request->desc;
                    $paid_to=$request->paid_to;
                    $debit=$request->d_amount;
                    $credit=$request->c_amount;


                    if ($debit[$index]>0):
                        $amount=$debit[$index];
                        $debit_amount=$amount;
                        $debit_amount=  CommonHelper::check_str_replace($debit_amount);
                        $total_amount+=$debit_amount;
                        $debit_credit=1;
                    else:
                        $amount=$credit[$index];
                        $debit_credit=0;
                    endif;
                    $pv_data->acc_id=$row;
                    $pv_data->amount=CommonHelper::check_str_replace($amount);
                    $pv_data->debit_credit=$debit_credit;

                    $pv_data->description=$desc[$index];

                    $pv_data->status=1;
                    $pv_data->pv_status=1;

                    $pv_data->save();
                    $pv_data_id=$pv_data->id;



                    $index++;
                endforeach;
                $id=$master_id;
                $m=Input::get('m');

                FinanceHelper::audit_trail($pv_no,$request->pv_date_1,$total_amount,2,'Insert');
                $subject = 'New Cash Payment Voucher Created ' . $pv_no;
                NotificationHelper::send_email('Cash Payment Voucher','Create',26,$pv_no,$subject);
                DB::Connection('mysql2')->commit();
            }
            catch(\Exception $e)
            {
                DB::Connection('mysql2')->rollback();
                echo "ERROR"; //die();
                dd($e->getMessage());

            }
        }elseif($request->paid_to_type == 2){
            DB::Connection('mysql2')->beginTransaction();
            try {
                $pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),$request->payment_type);
                $payment = new NewPv();
                $payment=$payment->SetConnection('mysql2');
                $payment->pv_no=$pv_no;
                $payment->pv_date=$request->pv_date_1;
                //uncommit by naveed
                $payment->bill_no=$request->slip_no_1;
                $payment->bill_date=$request->bill_date;
                //uncommit by naveed
                if($request->payment_type == 1):
                    $payment->cheque_no=$request->cheque_no_1;
                    $payment->cheque_date=$request->cheque_date_1;
                endif;
                $payment->payment_type=$request->payment_type;
                $payment->description=$request->description_1;
                $payment->date=date('Y-m-d');
                $payment->status=1;
                $payment->pv_status=1;
                // $payment->type=2;
                $payment->username=Auth::user()->name;
                $payment->save();
                $master_id=$payment->id;

                $new_purchase_voucher_id = $request->checkbox;

//                dd($new_purchase_voucher_id);
                foreach($new_purchase_voucher_id as $id):
                    $NewPurchaseVoucherPayment = new NewPurchaseVoucherPayment();
                    $NewPurchaseVoucherPayment = $NewPurchaseVoucherPayment->SetConnection('mysql2');
                    $NewPurchaseVoucherPayment->new_purchase_voucher_id     = $id;
                    $NewPurchaseVoucherPayment->pv_no       = $request->post('pv_no'.$id);
                    $NewPurchaseVoucherPayment->pv_date     = $request->post('purchase_date'.$id);
                    $NewPurchaseVoucherPayment->amount      = $request->post('amount'.$id);
                    $NewPurchaseVoucherPayment->status      = 1;
                    $NewPurchaseVoucherPayment->username    = Auth::user()->name;
                    $NewPurchaseVoucherPayment->date        = date('Y-m-d');
                    $NewPurchaseVoucherPayment->supplier_id        = $request->supplier_id;
                    $NewPurchaseVoucherPayment->new_pv      = $master_id;
                    $NewPurchaseVoucherPayment->new_pv_no   = $pv_no;
                    $NewPurchaseVoucherPayment->purchase_voucher_type   = $request->purchase_voucher_type;
                    $NewPurchaseVoucherPayment->save();


                    $voucher_no =$request->post('pv_no'.$id);
                    $grn_id = DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->value('grn_id');
                    $dept_id = NotificationHelper::get_dept_id('goods_receipt_note','id',$grn_id)->value('sub_department_id');
                    $subject = 'Payment Againts Purchase Invoice';
                    //    NotificationHelper::send_email('Purchase Payment Voucher','Create', $dept_id,$voucher_no,$subject,0);

                endforeach;

                $account_data=$request->account_id;
                $index=0;
                foreach($account_data as $row):
                    if(!empty($row))
                    {
                        $pv_data=new NewPvData();
                        $pv_data=$pv_data->SetConnection('mysql2');
                        $pv_data->master_id=$master_id;
                        $pv_data->pv_no=$pv_no;
                        $debit=$request->d_amount;
                        $credit=$request->c_amount;
                        $pv_data->pv_date=$request->pv_date_1;
                        $pv_data->paid_to_id = $request->paid_to;
                        $pv_data->paid_to_type = 2;

                        if ($debit[$index]!='' || $debit[$index]>0):
                            $amount = $debit[$index];
                            $debit_credit=1;
                        else:
                            $amount=$credit[$index];
                            $debit_credit=0;
                        endif;
                        $pv_data->acc_id=$row;
                        $pv_data->amount=$amount;
                        $pv_data->debit_credit=$debit_credit;
                        $pv_data->status=1;
                        $pv_data->pv_status=1;

                        $pv_data->save();

                        $index++;
                    }
                endforeach;
                DB::Connection('mysql2')->commit();
                $m=Input::get('m');
            }
            catch(\Exception $e)
            {
                DB::Connection('mysql2')->rollback();
                echo "EROOR"; //die();
                dd($e->getMessage());
            }

        }
        // return Redirect::to('finance/viewAllPaymentNewVoucherList?m='.$m);
        if ($request->SavePrintVal == 1) {
            return redirect('finance/viewAllPaymentVoucherDetailPrint?m='.$m)->with('success', 'Record updated successfully');
            # code...
        }
        else{
            return redirect('finance/viewAllPaymentNewVoucherList?m='.$m)->with('success', 'Record updated successfully');
        }

    }



    public function updateAllPaymentNew(Request $request)
    {
//dd($request);


//        echo "<pre>";
//        print_r($_POST);die;
        DB::Connection('mysql2')->beginTransaction();

        try {
            $id      = $request->id;
            $pv_no   = $request->pv_no;

            $payment = new NewPv();
            $payment = $payment->SetConnection('mysql2');
            $payment = $payment->find($id);
            if($request->payment_type != $payment->payment_type){
                $pv_no = CommonHelper::uniqe_no_for_pv(date('y'), date('m'), $request->payment_type);
                $payment->payment_type=$request->payment_type;
                $payment->pv_no     = $pv_no;

            }
            if($request->payment_type == 1){
                $payment->cheque_no=$request->cheque_no_1;
                $payment->cheque_date=$request->cheque_date_1;
            }else{
                $payment->cheque_no=null;
                $payment->cheque_date=null;
            }
            $payment->pv_date   = $request->pv_date_1;
            $payment->bill_no   = $request->slip_no_1;
            $payment->payment_for   = $request->payment_for;
            $payment->bill_date = $request->bill_date;



            $payment->description=$request->description_1;
            $payment->date=date('Y-m-d');
            $payment->status=1;
            // $payment->verified_by = '';
            //$payment->pv_status=1;
            $payment->save();

            DB::Connection('mysql2')->table('new_pv_data')->where('master_id',$id)->delete();

            $account_data=$request->account_id;
            $index=0;
            $total_amount=0;
            foreach($account_data as $row):

                $pv_data=new NewPvData();
                $pv_data=$pv_data->SetConnection('mysql2');
                $pv_data->master_id = $id;
                $pv_data->pv_no     = $pv_no;
                $pv_data->pv_date   = $request->pv_date_1;

                $acc_id=$row;
                $acc_id=explode(',',$acc_id);

                $desc=$request->desc;
                $sub_department_id = $request->sub_department_id;
                $paid_to=$request->paid_to;
                $debit=$request->d_amount;
                $credit=$request->c_amount;

                if ($debit[$index]>0):
                    $amount=$debit[$index];
                    $debit_amount=$amount;
                    $debit_amount=  CommonHelper::check_str_replace($debit_amount);
                    $total_amount+=$debit_amount;
                    $debit_credit=1;
                else:
                    $amount=$credit[$index];
                    $debit_credit=0;
                endif;
                $pv_data->acc_id=$row;
                $pv_data->sub_department_id = $sub_department_id[$index] ?? 0;
                $pv_data->amount=CommonHelper::check_str_replace($amount);
                $pv_data->description=$desc[$index];
//            $PaidTo = explode(',',$paid_to[$index]);
                $pv_data->paid_to_id= $paid_to;
//            $pv_data->paid_to_type= $PaidTo[1];
                $pv_data->debit_credit=$debit_credit;
                $pv_data->status=1;
                $pv_data->pv_status=2;
                $pv_data->save();

                $index++;
            endforeach;


            $count=CommonHelper::check_entry_in_transactions($pv_no,'new_pv_data',$request->pv_date_1,2,'pv_no');
            $count=CommonHelper::update_outstanding($pv_no,2);
            //$id=$master_id;
            $m=Input::get('m');
            FinanceHelper::audit_trail($pv_no,$request->pv_date_1,$total_amount,2,'Update');
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/viewAllPaymentNewVoucherList?m='.$m);
    }




//    Data call
    public function viewAllPaymentVoucherDetailPrint(){
        return view('Finance.AjaxPages.viewAllPaymentVoucherDetailPrint');
    }

    public function getAllpvsDateAccontWiseAndTypeWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;
        $m = $request->m;
        $pvs=new NewPv();
        $pvs=$pvs->SetConnection('mysql2');

        $VoucherStatus = $request->VoucherStatus;
        $PaymentType = $request->PaymentType;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'AND a.pv_status = '.$VoucherStatus;}

        if($AccountId !=""):

            $pvs= DB::Connection('mysql2')->select('select a.* from new_pv a
            inner join new_pv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            '.$Clause1.'
            and a.payment_type = 1
            and a.type = 1
            and c.id="'.$AccountId.'"
            and a.pv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            order by a.id desc
            ');
        else:


            if($VoucherStatus !="")
            {
                $pvs=$pvs->where('pv_status',$VoucherStatus);
            }
            if($PaymentType !=""){
                $pvs=$pvs->whereIn('payment_type',$PaymentType);
            }
            $pvs=$pvs->where('status',1)->where('type',1)->whereBetween('pv_date',array($FromDate,$ToDate))->orderBy('id' , 'desc')->get();

//        else
//        {
//            $pvs=$pvs->where('status',1)->where('payment_type',1)->where('type',1)->whereBetween('pv_date',array($FromDate,$ToDate))->get();
//        }

        endif;



        return view('Finance.AjaxPages.getAllpvsDateAndAccontWise',compact('pvs','m','FromDate','ToDate'));
    }



}
