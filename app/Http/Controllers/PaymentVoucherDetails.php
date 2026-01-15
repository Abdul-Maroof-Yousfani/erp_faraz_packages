<?php

namespace App\Http\Controllers;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\NewPv;
use App\Models\NewPvData;
use App\Models\IncomeTaxDeduction;
use App\Models\NewJvs;
use App\Models\NewJvData;
use App\Models\NewRvs;
use App\Models\Invoice;
use App\Models\Invoice_totals;
use App\Models\NewRvData;
use App\Models\NewPurchaseVoucherPayment;
use App\Models\InvoiceData;
use App\Models\NewPurchaseVoucherData;
use App\Models\NewPurchaseVoucher;
use App\Models\Transactions;
use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\SalesHelper;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class PaymentVoucherDetails extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function insertBankPayment(Request $request)
    {
        //echo "<pre>";
        //print_r($_POST); die;
        DB::Connection('mysql2')->beginTransaction();

 
            $pv_no = CommonHelper::uniqe_no_for_pv(date('y'), date('m'), 1);
            $payment = new NewPv();
            $payment = $payment->SetConnection('mysql2');
            $payment->pv_no = $pv_no;
            $payment->pv_date = $request->pv_date_1;
            $payment->bill_no = $request->slip_no_1;
            $payment->bill_date = $request->bill_date;
            $payment->cheque_no = $request->cheque_no_1;
            $payment->cheque_date = $request->cheque_date_1;
            $payment->payment_type = 1;
            $payment->description = $request->description_1;
            $payment->date = date('Y-m-d');
            $payment->status = 1;
            $payment->username = Auth::user()->name;
            $payment->pv_status = 1;
            $payment->save();
            $master_id = $payment->id;

            $account_data = $request->account_id;
            $index = 0;
            $total_amount = 0;
            foreach ($account_data as $row):

                $pv_data = new NewPvData();
                $pv_data = $pv_data->SetConnection('mysql2');
                $pv_data->master_id = $master_id;
                $pv_data->pv_no = $pv_no;
                $pv_data->pv_date = $request->pv_date_1;

                $acc_id = $row;
                $acc_id = explode(',', $acc_id);

                // for income tax data
                $income_tax_id = 0;
                if ($acc_id[1] == 1):


                    $income_tax = new IncomeTaxDeduction();
                    $income_tax = $income_tax->SetConnection('mysql2');
                    $income_tax->pvs_id = $master_id;
                    $income_tax->pv_data_id = 0;
                    $income_tax->pv_no = $pv_no;
                    $income_tax->nature = $acc_id[2];
                    $income_tax->tax_payment_section = $acc_id[3];
                    $income_tax->amount = $acc_id[4];
                    $income_tax->deduct_amount = $acc_id[5];
                    $income_tax->income_tax_slab_id = $acc_id[6];
                    $income_tax->supplier_id = $acc_id[7];
                    $income_tax->save();
                    $income_tax_id = $income_tax->id;
                    $pv_data->tax_nature = 1;
                    $pv_data->slab_id = $acc_id[6];
                    $pv_data->income_master_id = $income_tax_id;
                    $pv_data->taxtaion_rate = $acc_id[4];
                    $pv_data->taxation_amount = $acc_id[5];
                endif;
                //end

                $fbr = 0;
                if ($acc_id[1] == 2):

                    $pv_data->tax_nature = 2;
                    $pv_data->slab_id = $acc_id[2];
                    $pv_data->slab_id = $acc_id[2];
                    $pv_data->taxtaion_rate = $acc_id[3];
                    $pv_data->taxation_amount = $acc_id[4];
                endif;
                //end


                $srb = 0;
                if ($acc_id[1] == 3):

                    $pv_data->tax_nature = 3;
                    $pv_data->slab_id = $acc_id[2];
                    $pv_data->taxtaion_rate = $acc_id[3];
                    $pv_data->taxation_amount = $acc_id[4];
                    $pv_data->percentage = $acc_id[5];
                    $pv_data->exclusion = $acc_id[6];
                endif;
                //end


                // pra

                $srb = 0;
                if ($acc_id[1] == 4):

                    $pv_data->tax_nature = 4;
                    $pv_data->slab_id = $acc_id[2];
                    $pv_data->taxtaion_rate = $acc_id[3];
                    $pv_data->taxation_amount = $acc_id[4];
                    $pv_data->percentage = $acc_id[5];

                endif;
                //end


                //$desc = $request->desc;
                // $sub_department_id = $request->sub_department_id;
                //$paid_to = $request->paid_to;
                $debit = $request->d_amount;
                $credit = $request->c_amount;

                //$desc = (count($desc) > 1) ? $desc[$index] : $desc[0];
                // $sub_department_id = (count($sub_department_id) > 1) ? $sub_department_id[$index] : $sub_department_id[0];

               if (isset($debit[$index]) && $debit[$index] > 0):
                    $amount = $debit[$index];
                    $debit_amount = $amount;
                    $debit_amount = CommonHelper::check_str_replace($debit_amount);
                    $total_amount += $debit_amount;
                    $debit_credit = 1;
                elseif (isset($credit[$index]) && $credit[$index] > 0):
                    $amount = $credit[$index];
                    $debit_credit = 0;
                endif;

                $pv_data->acc_id = $row;
                $pv_data->sub_department_id = 0;
                $pv_data->amount = CommonHelper::check_str_replace($amount);
                $pv_data->debit_credit = $debit_credit;
                $pv_data->description = '';
                //                    $PaidTo = explode(',', $paid_to[$index]);
//                    $pv_data->paid_to_id = $PaidTo[0];
//                    $pv_data->paid_to_type = $PaidTo[1];
                $pv_data->status = 1;
                $pv_data->pv_status = 1;


                $pv_data->save();
                $pv_data_id = $pv_data->id;

                if ($income_tax_id > 0):
                    $income_tax = new IncomeTaxDeduction();
                    $income_tax = $income_tax->SetConnection('mysql2');
                    $income_tax = $income_tax->find($income_tax_id);
                    $income_tax->pv_data_id = $pv_data_id;
                    $income_tax->save();
                endif;

                $index++;
            endforeach;

            $id = $master_id;
            $m = Input::get('m');
            FinanceHelper::audit_trail($pv_no, $request->pv_date_1, $total_amount, 2, 'Insert');
            $subject = 'New Bank Payment Voucher Created ' . $pv_no;
            NotificationHelper::send_email('Bank Payment Voucher', 'Create', 26, $pv_no, $subject);
            DB::Connection('mysql2')->commit();
        

        return Redirect::to('finance/viewBankPaymentNewVoucherList?m=' . $m);
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
                $payment->type=2;
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
        return Redirect::to('finance/viewAllPaymentNewVoucherList?m='.$m);

    }

    public function insertCashPayment(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {

            $pv_no = CommonHelper::uniqe_no_for_pv(date('y'), date('m'), 2);
            $payment = new NewPv();
            $payment = $payment->SetConnection('mysql2');
            $payment->pv_no = $pv_no;
            $payment->pv_date = $request->pv_date_1;
            $payment->bill_no = $request->slip_no_1;
            $payment->bill_date = $request->bill_date;

            $payment->payment_type = 2;
            $payment->description = $request->description_1;
            $payment->date = date('Y-m-d');
            $payment->status = 1;
            $payment->username = Auth::user()->name;
            $payment->pv_status = 1;
            $payment->save();
            $master_id = $payment->id;

            $account_data = $request->account_id;
            $index = 0;

            $total_amount = 0;
            $debit_credit = 0;
            foreach ($account_data as $row):

                $pv_data = new NewPvData();
                $pv_data = $pv_data->SetConnection('mysql2');
                $pv_data->master_id = $master_id;
                $pv_data->pv_no = $pv_no;
                $pv_data->pv_date = $request->pv_date_1;

                $acc_id = $row;
                $acc_id = explode(',', $acc_id);

                $desc = $request->desc;
                $paid_to = $request->paid_to;
                $debit = $request->d_amount;
                $credit = $request->c_amount;

                if ($debit[$index] > 0):
                    $amount = $debit[$index];
                    $debit_amount = $amount;
                    $debit_amount = CommonHelper::check_str_replace($debit_amount);
                    $total_amount += $debit_amount;
                    $debit_credit = 1;
                else:
                    $amount = $credit[$index];
                    $debit_credit = 0;
                endif;
                $pv_data->acc_id = $row;
                $pv_data->amount = CommonHelper::check_str_replace($amount);
                $pv_data->debit_credit = $debit_credit;

                $pv_data->description = $desc[$index];

                $pv_data->status = 1;
                $pv_data->pv_status = 1;

                $pv_data->save();
                $pv_data_id = $pv_data->id;

                $index++;
            endforeach;
            $id = $master_id;
            $m = Input::get('m');

            FinanceHelper::audit_trail($pv_no, $request->pv_date_1, $total_amount, 2, 'Insert');
            $subject = 'New Cash Payment Voucher Created ' . $pv_no;
            NotificationHelper::send_email('Cash Payment Voucher', 'Create', 26, $pv_no, $subject);
            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/viewCashPaymentVoucherList?m=' . $m);

    }

    public function approvedPaymentVoucher(Request $request)
    {
        $CheckBoxes = $request->checkbox;
        if($CheckBoxes)
        {
            foreach($CheckBoxes as $Fil)
            {
                $UpdateStatus['pv_status'] = 2;
                DB::Connection('mysql2')->table('new_pv')->where('id', $Fil)->update($UpdateStatus);
                DB::Connection('mysql2')->table('new_pv_data')->where('master_id', $Fil)->update($UpdateStatus);
            }
            return Redirect::to('finance/viewBankPaymentNewVoucherList?pageType=viewlist&&parentCode=22&&m=1');
        }
        else{

        }
    }

    public function updateCashPayment(Request $request)
    {
//        echo "<pre>";
//        print_r($_POST);die;
        DB::Connection('mysql2')->beginTransaction();

        try {

        $id      = $request->id;
        $pv_no   = $request->pv_no;

        $payment = new NewPv();
        $payment = $payment->SetConnection('mysql2');
        $payment = $payment->find($id);
        $payment->pv_no     = $pv_no;
        $payment->pv_date   = $request->pv_date_1;
        $payment->bill_no   = $request->slip_no_1;
        $payment->bill_date = $request->bill_date;
        $payment->payment_type=2;
        $payment->description=$request->description_1;
        $payment->date=date('Y-m-d');
        $payment->status=1;
        $payment->verified_by = '';
        //$payment->pv_status=1;
        $payment->save();

        DB::Connection('mysql2')->table('new_pv_data')->where('master_id',$id)->delete();

        $account_data=$request->account_id;
        $index=0;
        $total_amount=0;
        $debit_credit=0;
        foreach($account_data as $row):

            $pv_data=new NewPvData();
            $pv_data=$pv_data->SetConnection('mysql2');
            $pv_data->master_id = $id;
            $pv_data->pv_no     = $pv_no;
            $pv_data->pv_date   = $request->pv_date_1;

            $acc_id=$row;
            $acc_id=explode(',',$acc_id);
            $paid_to=$request->paid_to;
            $desc=$request->desc;
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


            $pv_data->description=$desc[$index];
            $pv_data->amount=CommonHelper::check_str_replace($amount);
            $pv_data->debit_credit=$debit_credit;
            $pv_data->status=1;
            $pv_data->pv_status=2;
            $pv_data->save();

            $index++;
        endforeach;

            $count=CommonHelper::check_entry_in_transactions($pv_no,'new_pv_data',$request->pv_date_1,1,'pv_no');
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
        return Redirect::to('finance/viewCashPaymentVoucherList?m='.$m);
    }

    public function updateBankRv(Request $request)
    {

        //dd($request); die();
        DB::Connection('mysql2')->beginTransaction();

        try {
        $id      = $request->id;
        $rv_no   = $request->rv_no;

        $NewRvs = new NewRvs();
        $NewRvs = $NewRvs->SetConnection('mysql2');
        $NewRvs = $NewRvs->find($id);
        $NewRvs->rv_no     = $rv_no;
        $NewRvs->rv_date   = $request->rv_date_1;
        $NewRvs->ref_bill_no   = $request->slip_no_1;
        $NewRvs->cheque_no = $request->cheque_no;
        $NewRvs->cheque_date = $request->cheque_date;
        $NewRvs->rv_type=1;
        $NewRvs->description=$request->description_1;
        $NewRvs->date=date('Y-m-d');
        $NewRvs->status=1;
        $NewRvs->verified_by = '';
        //$payment->rv_status=1;
        $NewRvs->save();

        DB::Connection('mysql2')->table('new_rv_data')->where('master_id',$id)->delete();

        $account_data=$request->account_id;
        $index=0;
        $total_amount=0;
        foreach($account_data as $row):

            $NewRvData=new NewRvData();
            $NewRvData=$NewRvData->SetConnection('mysql2');
            $NewRvData->master_id = $id;
            $NewRvData->rv_no     = $rv_no;

            $acc_id=$row;
            $acc_id=explode(',',$acc_id);


            $desc=$request->desc;
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
            $NewRvData->acc_id=$row;


            $NewRvData->description=$desc[$index];
            $NewRvData->amount=CommonHelper::check_str_replace($amount);
            $NewRvData->debit_credit=$debit_credit;
            $NewRvData->status=1;
            $NewRvData->rv_status=1;
            $NewRvData->save();

            $index++;
        endforeach;
        //$id=$master_id;
        $count=CommonHelper::check_entry_in_transactions($rv_no,'new_rv_data',$request->rv_date_1,3,'rv_no');
        $m=Input::get('m');
        FinanceHelper::audit_trail($rv_no,$request->rv_date_1,$total_amount,3,'Update');
        DB::Connection('mysql2')->commit();
        }
    catch(\Exception $e)
    {
    DB::Connection('mysql2')->rollback();
    echo "EROOR"; //die();
    dd($e->getMessage());

    }
        return Redirect::to('finance/viewBankRvNew?pageType=&&parentCode=24&&m='.$m.'');
    }

    public function UpdateJv(Request $request)
    {


        //dd($request); die();
        $id      = $request->id;
        $jv_no   = $request->jv_no;
        DB::Connection('mysql2')->beginTransaction();

        try {

            $NewJvs = new NewJvs();
            $NewJvs = $NewJvs->SetConnection('mysql2');
            $NewJvs = $NewJvs->find($id);
            $NewJvs->jv_no = $jv_no;
            $NewJvs->jv_date = $request->jv_date_1;
            $NewJvs->description = $request->description_1;
            $NewJvs->date = date('Y-m-d');
            $NewJvs->status = 1;
            $NewJvs->verified_by = '';
            //$payment->rv_status=1;
            $NewJvs->save();

            DB::Connection('mysql2')->table('new_jv_data')->where('master_id', $id)->delete();

            $account_data = $request->account_id;
            $index = 0;
            $total_amount = 0;
            foreach ($account_data as $row):

                $NewJvData = new NewJvData();
                $NewJvData = $NewJvData->SetConnection('mysql2');
                $NewJvData->master_id = $id;
                $NewJvData->jv_no = $jv_no;

                $acc_id = $row;
                $acc_id = explode(',', $acc_id);

                $debit = $request->d_amount;
                $credit = $request->c_amount;
                $Desc = $request->desc;
                $sub_department_id = $request->sub_department_id;
                //$paid_to = $request->paid_to;

                if ($debit[$index] > 0):
                    $amount = $debit[$index];
                    $debit_amount = $amount;
                    $debit_amount = CommonHelper::check_str_replace($debit_amount);
                    $total_amount += $debit_amount;
                    $debit_credit = 1;
                else:
                    $amount = $credit[$index];
                    $debit_credit = 0;
                endif;
                $NewJvData->acc_id = $acc_id[0];
                $NewJvData->description = $Desc[$index];
                $NewJvData->sub_department_id = $sub_department_id[$index] ?? 0;
//                $PaidTo = explode(',', $paid_to[$index]);
//                $NewJvData->paid_to_id = $PaidTo[0];
//                $NewJvData->paid_to_type = $PaidTo[1];
                $NewJvData->amount = CommonHelper::check_str_replace($amount);
                $NewJvData->debit_credit = $debit_credit;
                $NewJvData->status = 1;
                $NewJvData->jv_status = 1;
                $NewJvData->save();

                $index++;
            endforeach;
            $count=CommonHelper::check_entry_in_transactions($jv_no,'new_jv_data',$request->jv_date_1,1,'jv_no');
            $count=CommonHelper::update_outstanding($jv_no,1);

        //$id=$master_id;
            $m=Input::get('m');
            FinanceHelper::audit_trail($jv_no,$request->jv_date_1,$total_amount,1,'Update');
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }



        return Redirect::to('finance/viewJournalVoucherNew?pageType=&&parentCode=33&&m='.$m.'');
    }


    public function updateCashRv(Request $request)
    {

        //dd($request); die();
        DB::Connection('mysql2')->beginTransaction();

        try {
        $id      = $request->id;
        $rv_no   = $request->rv_no;

        $NewRvs = new NewRvs();
        $NewRvs = $NewRvs->SetConnection('mysql2');
        $NewRvs = $NewRvs->find($id);
        $NewRvs->rv_no     = $rv_no;
        $NewRvs->rv_date   = $request->rv_date_1;
        $NewRvs->ref_bill_no   = $request->slip_no_1;
//        $NewRvs->cheque_no = $request->cheque_no;
//        $NewRvs->cheque_date = $request->cheque_date;
        $NewRvs->rv_type=2;
        $NewRvs->description=$request->description_1;
        $NewRvs->date=date('Y-m-d');
        $NewRvs->status=1;
        $NewRvs->verified_by = '';
        //$payment->rv_status=1;
        $NewRvs->save();

        DB::Connection('mysql2')->table('new_rv_data')->where('master_id',$id)->delete();

        $account_data=$request->account_id;
        $index=0;
        $total_amount=0;
        foreach($account_data as $row):

            $NewRvData=new NewRvData();
            $NewRvData=$NewRvData->SetConnection('mysql2');
            $NewRvData->master_id = $id;
            $NewRvData->rv_no     = $rv_no;

            $acc_id=$row;
            $acc_id=explode(',',$acc_id);

            $paid_to=$request->paid_to;
            $desc=$request->desc;
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
            $NewRvData->acc_id=$row;
            $NewRvData->amount=CommonHelper::check_str_replace($amount);
            $PaidTo = explode(',',$paid_to[$index]);
            $NewRvData->paid_to_id= $PaidTo[0];
            $NewRvData->paid_to_type= $PaidTo[1];
            $NewRvData->description=$desc[$index];
            $NewRvData->debit_credit=$debit_credit;
            $NewRvData->status=1;
            $NewRvData->rv_status=1;
            $NewRvData->save();

            $index++;
        endforeach;
        //$id=$master_id;
        $m=Input::get('m');
        $count=CommonHelper::check_entry_in_transactions($rv_no,'new_rv_data',$request->rv_date_1,3,'rv_no');
        FinanceHelper::audit_trail($rv_no,$request->rv_date_1,$total_amount,3,'Update');
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/viewCashRvNew?pageType=&&parentCode=24&&m='.$m.'');
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

    public function updateBankPaymentNew(Request $request)
    {
//        echo "<pre>";
//        print_r($_POST);die;
        DB::Connection('mysql2')->beginTransaction();

        try {
        $id      = $request->id;
        $pv_no   = $request->pv_no;

        $payment = new NewPv();
        $payment = $payment->SetConnection('mysql2');
        $payment = $payment->find($id);
        $payment->pv_no     = $pv_no;
        $payment->pv_date   = $request->pv_date_1;
        $payment->bill_no   = $request->slip_no_1;
        $payment->bill_date = $request->bill_date;
        $payment->cheque_no = $request->cheque_no_1;
        $payment->cheque_date = $request->cheque_date_1;
        $payment->payment_type=1;
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
//            $paid_to=$request->paid_to;
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
//            $pv_data->paid_to_id= $PaidTo[0];
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
        return Redirect::to('finance/viewBankPaymentNewVoucherList?m='.$m);
    }

    public function DeletePurchaseVoucher()
    {
        $pv_id = $_GET['pv_id'];
        $count = DB::connection('mysql2')->table('new_purchase_voucher_payment')->where('new_purchase_voucher_id', $pv_id)->where('status','1')->count();
        if($count>0)
        {
            echo "2";
        }
        else
        {
            $First = DB::connection('mysql2')->table('new_purchase_voucher')->where('id', $pv_id)->first();
            $Amount = DB::Connection('mysql2')->select('select SUM(amount) as amount from new_purchase_voucher_data where master_id = '.$pv_id.' ');
            DB::connection('mysql2')->table('new_purchase_voucher')->where('id', $pv_id)->update(['status' => 2]);
            DB::connection('mysql2')->table('new_purchase_voucher_data')->where('master_id', $pv_id)->update(['staus' => 2]);
            FinanceHelper::audit_trail($First->pv_no,$First->pv_date,$Amount[0]->amount,4,'Delete');
            echo "1";
        }

    }

    public function DeletePVoucherActivity()
    {

        DB::Connection('mysql2')->beginTransaction();

        try {
        $pv_id = $_GET['pv_id'];
        $pv_no = $_GET['pv_no'];
        $pv_date = $_GET['pv_date'];
        $pv_amount = $_GET['pv_amount'];
        $payment = new NewPv();
        $payment = $payment->SetConnection('mysql2');
        $payment = $payment->find($pv_id);
        $payment->status=2;
        $payment->save();


        $count=CommonHelper::get_transaction_enry($pv_no);
        if ($count>0):
            DB::connection('mysql2')->table('transactions')->where('voucher_no', $pv_no)->update(['status' => 0]);
        endif;

        DB::connection('mysql2')->table('new_pv_data')->where('master_id', $pv_id)->update(['status' => 2]);
        DB::connection('mysql2')->table('new_purchase_voucher_payment')->where('new_pv_no', $pv_no)->update(['status' => 2]);
        FinanceHelper::audit_trail($pv_no,$pv_date,$pv_amount,2,'Delete');
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }


    }


    public function payment_return()
    {

        DB::Connection('mysql2')->beginTransaction();

        try {
            $pv_id = $_GET['pv_id'];
            $pv_no = $_GET['pv_no'];
            $pv_date = $_GET['pv_date'];
            $pv_amount = $_GET['pv_amount'];
            $cheque_no = $_GET['cheque_no'];
            $Description = $_GET['Description'];

            $payment = new NewPv();
            $payment = $payment->SetConnection('mysql2');
            $payment = $payment->find($pv_id);
            $payment->status=1993;
            $payment->save();


            $count=CommonHelper::get_transaction_enry($pv_no);
            if ($count>0):
                DB::connection('mysql2')->table('transactions')->where('voucher_no', $pv_no)->update(['status' => 1993]);
            endif;

            DB::connection('mysql2')->table('new_pv_data')->where('master_id', $pv_id)->update(['status' => 1993]);
            DB::connection('mysql2')->table('new_purchase_voucher_payment')->where('new_pv_no', $pv_no)->update(['status' => 1993]);

            $ReturnData['master_id'] = $pv_id;
            $ReturnData['voucher_no'] = $pv_no;
            $ReturnData['cheque_no'] = $cheque_no;
            $ReturnData['description'] = $Description;

            $ReturnData['date'] = date('Y-m-d');
            $ReturnData['username'] = Auth::user()->name;
            DB::Connection('mysql2')->table('payment_return')->insert($ReturnData);

            FinanceHelper::audit_trail($pv_no,$pv_date,$pv_amount,2,'Payment Return');
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }


    }
    public function DeleteJVoucherActivity()
    {

        $jv_id = $_GET['jv_id'];
        $jv_no = $_GET['jv_no'];
        $jv_date = $_GET['jv_date'];
        $jv_amount = $_GET['jv_amount'];


        $jvs = new NewJvs();
        $jvs = $jvs->SetConnection('mysql2');
        $jvs = $jvs->find($jv_id);
        $jvs->status=2;
        $jvs->save();

        $count=CommonHelper::get_transaction_enry($jv_no);
        if ($count>0):
            DB::connection('mysql2')->table('transactions')->where('voucher_no', $jv_no)->update(['status' => 0]);
        endif;


        $count=CommonHelper::check_entry_in_outstanding($jv_no);
        if ($count>0):
            DB::connection('mysql2')->table('new_purchase_voucher_payment')->where('new_pv_no', $jv_no)->update(['status' => 0]);
        endif;


        DB::connection('mysql2')->table('new_jv_data')->where('master_id', $jv_id)->update(['status' => 2]);
        FinanceHelper::audit_trail($jv_no,$jv_date,$jv_amount,1,'Delete');
    }

    public function DeleteRVoucherActivity()
    {

        $rv_id = $_GET['rv_id'];
        $rv_no = $_GET['rv_no'];
        $rv_date = $_GET['rv_date'];
        $rv_amount = $_GET['rv_amount'];
        $rvs = new NewRvs();
        $rvs = $rvs->SetConnection('mysql2');
        $rvs = $rvs->find($rv_id);
        $rvs->status=2;
        $rvs->save();

        DB::connection('mysql2')->table('new_rv_data')->where('master_id', $rv_id)->update(['status' => 2]);
        DB::connection('mysql2')->table('transactions')->where('voucher_no', $rv_no)->update(['status' => 2]);
        DB::connection('mysql2')->table('received_paymet')->where('receipt_no', $rv_no)->update(['status' => 2]);
        FinanceHelper::audit_trail($rv_no,$rv_date,$rv_amount,3,'Delete');
        SalesHelper::sales_activity($rv_no,$rv_date,$rv_amount,5,'Delete');
    }


    public function PaymentPurchaseVoucher()
    {
        return view('Finance.PaymentPurchaseVoucher');
    }

    public function editPaymentPurchaseVoucher($new_pv_id)
    {
        //echo $id;
        $new_purchase_voucher_payment = DB::connection('mysql2')->table('new_purchase_voucher_payment')->select('*')->where('new_pv', $new_pv_id)->where('status',1)->get();
        return view('Finance.editPaymentPurchaseVoucher', compact('new_purchase_voucher_payment'));
    }

    public function updatePaymentPurchaseVoucher(Request $request)
    {
//        echo "<pre>";
//        print_r($_POST); die;
        DB::Connection('mysql2')->beginTransaction();
        try {
            $m = $_GET['m'];
            $new_pv_id = $request->new_pv_id;
            //$pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),$request->payment_type_mod);
            $payment = new NewPv();
            $payment=$payment->SetConnection('mysql2');
            $payment=$payment->find($new_pv_id);
            //$payment->pv_no=$pv_no;
            $payment->pv_date=$request->bpv_date;

            if ($request->payment_type_mod==1):
                $payment->cheque_no=$request->cheque_no;
                $payment->cheque_date=$request->cheque_date;
                $payment->payment_type=1;
            else:
                $payment->payment_type=2;
            endif;
            $payment->description=$request->description_1;
            $payment->date=date('Y-m-d');
            $payment->status=1;
            // $payment->pv_status=1;
            $payment->type=2;
            $payment->save();

            $new_purchase_voucher_payment_id = $request->new_purchase_voucher_payment_id;
            foreach($new_purchase_voucher_payment_id as $id):
                $NewPurchaseVoucherPayment = new NewPurchaseVoucherPayment();
                $NewPurchaseVoucherPayment = $NewPurchaseVoucherPayment->SetConnection('mysql2');
                $NewPurchaseVoucherPayment = $NewPurchaseVoucherPayment->find($id);
                //$NewPurchaseVoucherPayment->new_purchase_voucher_id     = $id;
                //$NewPurchaseVoucherPayment->pv_no       = $request->post('pv_no'.$id);
                //$NewPurchaseVoucherPayment->pv_date     = $request->post('purchase_date'.$id);
                $NewPurchaseVoucherPayment->amount      = $request->post('amount'.$id);
                $NewPurchaseVoucherPayment->status      = 1;
                $NewPurchaseVoucherPayment->username    = Auth::user()->name;
                $NewPurchaseVoucherPayment->date        = date('Y-m-d');
                //$NewPurchaseVoucherPayment->new_pv      = $master_id;
                //$NewPurchaseVoucherPayment->new_pv_no   = $pv_no;
                $NewPurchaseVoucherPayment->save();
            endforeach;

            DB::Connection('mysql2')->table('new_pv_data')->where('master_id',$new_pv_id)->delete();

            $account_data=$request->account_id;
            $index=0;
            foreach($account_data as $row):

                $pv_data=new NewPvData();
                $pv_data=$pv_data->SetConnection('mysql2');
                $pv_data->master_id=$new_pv_id;
                $pv_data->pv_no=$request->new_pv_no;
                $credit=$request->c_amount;
                $pv_data->pv_date=$request->bpv_date;

                if ($index==0):
                    $amount = $request->d_amount;
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
                if ($row!=0):
                    $pv_data->save();
                endif;
                //$pv_data_id=$pv_data->id;

                $index++;
            endforeach;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }

        return Redirect::to('finance/PurchaseVoucherList?m='.$m);
    }

    public function AddPaymentPurchaseVoucher(Request $request)
    {


        DB::Connection('mysql2')->beginTransaction();
        try {
        $pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),$request->payment_type_mod);
        $payment = new NewPv();
        $payment=$payment->SetConnection('mysql2');
        $payment->pv_no=$pv_no;
        $payment->pv_date=$request->bpv_date;
        //$payment->bill_no=$request->slip_no_1;
        //$payment->bill_date=$request->bill_date;
        if ($request->payment_type_mod==1):
        $payment->cheque_no=$request->cheque_no;
        $payment->cheque_date=$request->cheque_date;
        $payment->payment_type=1;
        else:
            $payment->payment_type=2;
        endif;
        $payment->description=$request->description_1;
        $payment->date=date('Y-m-d');
        $payment->status=1;
        $payment->pv_status=1;
        $payment->type=2;
        $payment->username=Auth::user()->name;
        $payment->save();
        $master_id=$payment->id;

        $new_purchase_voucher_id = $request->id;
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
                $pv_data->pv_date=$request->bpv_date;
                $pv_data->paid_to_id = $request->supplier_id;
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
            $Url = url('fdc/viewBankPaymentVoucherDetailInDetailPrint?id='.$master_id.'&&pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.Session::get('run_company').'#Murtaza Corp');
            return Redirect::to($Url);
        }
        else
        {
            return Redirect::to('finance/PaymentVoucherList?m='.Session::get('run_company'));
        }

    }

    public  function insertJournalVoucher(Request $request)
    {
        // dd($request);
    //    echo "<pre>";
    //    print_r($request->all());
    //    die;
        DB::Connection('mysql2')->beginTransaction();

        try {

            $jv_no = CommonHelper::uniqe_no_for_jv(date('y'), date('m'));
            $Jvs = new NewJvs();
            $Jvs = $Jvs->SetConnection('mysql2');
            $Jvs->jv_no = $jv_no;
            $Jvs->jv_date = $request->jv_date_1;

            $Jvs->description = $request->description_1;
            $Jvs->date = date('Y-m-d');
            $Jvs->status = 1;
            $Jvs->jv_status = 1;
            $Jvs->username = Auth::user()->name;
            $Jvs->save();
            $master_id = $Jvs->id;

            $account_data = $request->account_id;
            $index = 0;

            $total_amount = 0;
            foreach ($account_data as $row):

                $JvData = new NewJvdata();
                $JvData = $JvData->SetConnection('mysql2');
                $JvData->master_id = $master_id;
                $JvData->jv_no = $jv_no;
                //$JvData->pv_date=$request->pv_date_1;

                $acc_id = $row;
                $acc_id = explode(',', $acc_id);

                $desc = $request->desc;
                $sub_department_id = $request->sub_department_id;
                //$paid_to = $request->paid_to;
                $debit = $request->d_amount;
                $credit = $request->c_amount;
                if ($debit[$index] > 0):
                    $amount = $debit[$index];
                    $debit_amount = $amount;
                    $debit_amount = CommonHelper::check_str_replace($debit_amount);
                    $total_amount += $debit_amount;

                    $debit_credit = 1;
                else:
                    $amount = $credit[$index];
                    $debit_credit = 0;
                endif;

                $JvData->acc_id = $acc_id[0];
                $JvData->sub_department_id = $sub_department_id[$index] ?? 0;
                $JvData->amount = CommonHelper::check_str_replace($amount);
                $JvData->debit_credit = $debit_credit;
                $JvData->description = $desc[$index];
                //$PaidTo = explode(',', $paid_to[$index]);
                //$JvData->paid_to_id = $PaidTo[0];
                //$JvData->paid_to_type = $PaidTo[1];

                $JvData->status = 1;
                $JvData->jv_status = 1;
                $JvData->save();
                $pv_data_id = $JvData->id;
                $index++;
            endforeach;
            $id = $master_id;
            $m = Input::get('m');
            //return Redirect::to('finance/viewCashPaymentVoucherList?m='.$m);
            FinanceHelper::audit_trail($jv_no, $request->jv_date_1, $total_amount, 1, 'Insert');
            $subject = 'New Journal Voucher Created ' . $jv_no;
            NotificationHelper::send_email('Journal Voucher','Create',26,$jv_no,$subject);
            DB::Connection('mysql2')->commit();

        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            echo $e->getTraceAsString(); //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/viewJournalVoucherNew?pageType=&&parentCode=33&&m='.$m.'');

    }

    public  function uploadJournalVoucher(Request $request)
    {

        $jv_date_index;
        $description_index;
        $debit_account_index;
        $credit_account_index;
        $debit_amount_index;
        $credit_amount_index;

        if ($request->hasFile('jvexcel'))
        {
            DB::Connection('mysql2')->beginTransaction();
            try
            {
                // $file = request()->file('jvexcel');
                $file = $request->file('jvexcel');

                $data = Excel::toArray([], $file);
                $request->validate([
                    'jvexcel' => 'required|mimes:xlsx,xls|max:2048',
                ]);
                foreach ($data[0] as $key => $value) {
                    if (in_array('jv date', $value))
                    {
                        $jv_date_index = array_search('jv date', $value);
                        $description_index = array_search('description', $value);
                        $debit_account_index = array_search('debit account', $value);
                        $credit_account_index = array_search('credit account', $value);
                        $debit_amount_index = array_search('debit amount', $value);
                        $credit_amount_index = array_search('credit amount', $value);
                        break;
                    }

                }

                $ignoreFirst = true;
                foreach ($data[0] as $key => $value)
                {

                    $jv_no;
                    $jv_date;
                    $description;
                    $debit_account;
                    $credit_account;
                    $debit_amount = 0;
                    $credit_amount = 0;


                    if ($ignoreFirst) {
                        $ignoreFirst = false;
                        continue; // Skip the first iteration
                    }
                    else
                    {

                            $jv_no = CommonHelper::uniqe_no_for_jv(date('y'), date('m'));
                            $jv_date = $value[$jv_date_index];

                            if (is_numeric($jv_date)) {

                                $excelDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($jv_date);

                                $jv_date = $excelDate->format('Y-m-d');

                            }

                            $description = $value[$description_index];
                            $debit_account =  CommonHelper::get_specific_account_operat($value[$debit_account_index]);

                            $credit_account =  CommonHelper::get_specific_account_operat($value[$credit_account_index]);

                            $debit_amount = $value[$debit_amount_index];
                            $credit_amount = $value[$credit_amount_index];

                            if(empty($debit_account) || empty($credit_account) || !$jv_date || !$description || !$debit_amount || !$credit_amount )
                            {

                                return redirect()->to('finance/UploadJournalVoucherView?pageType=&&parentCode=33&&m='.$m.'')->withErrors(['error' => 'Validation failed. Please check the required fields.']);
                            }

                            $Jvs = new NewJvs();
                            $Jvs = $Jvs->SetConnection('mysql2');
                            $Jvs->jv_no = $jv_no;
                            $Jvs->jv_date = $jv_date;

                            $Jvs->description = $description;
                            $Jvs->date = date('Y-m-d');
                            $Jvs->status = 1;
                            $Jvs->jv_status = 1;
                            $Jvs->username = Auth::user()->name;
                            $Jvs->save();
                            $master_id = $Jvs->id;

                            $index = 0;
                            $account_data = [$value[$debit_account_index],$value[$credit_account_index]];
                            $total_amount = 0;

                            foreach ($account_data as $key_account_data => $account_datarow):

                                $JvData = new NewJvdata();
                                $JvData = $JvData->SetConnection('mysql2');
                                $JvData->master_id = $master_id;
                                $JvData->jv_no = $jv_no;
                                //$JvData->pv_date=$request->pv_date_1;

                                $desc = $description;
                                //$paid_to = $request->paid_to;
                                $debit = $debit_amount;
                                $credit = $credit_amount;
                                if ($key_account_data == 0):
                                    $amount = $debit;
                                    $debit_amount = $amount;
                                    $debit_amount = CommonHelper::check_str_replace($debit_amount);
                                    $total_amount += $debit_amount;
                                    $debit_credit = 1;
                                    $JvData->acc_id = $debit_account->id;

                                else:
                                    $JvData->acc_id = $credit_account->id;

                                    $amount = $credit;
                                    $debit_credit = 0;
                                endif;

                                $JvData->sub_department_id =  0;
                                $JvData->amount = CommonHelper::check_str_replace($amount);
                                $JvData->debit_credit = $debit_credit;
                                $JvData->description = $desc;

                                $JvData->status = 1;
                                $JvData->jv_status = 1;
                                $JvData->save();
                                $pv_data_id = $JvData->id;
                                $index++;
                            endforeach;

                            $id = $master_id;
                            $m = Input::get('m');
                            //return Redirect::to('finance/viewCashPaymentVoucherList?m='.$m);
                            FinanceHelper::audit_trail($jv_no, $jv_date, $total_amount, 1, 'Insert');
                            DB::Connection('mysql2')->commit();

                    }

                }

            }
            catch(\Exception $e)
            {
                DB::Connection('mysql2')->rollback();
                echo "EROOR"; //die();
          //      echo $e->getTraceAsString(); //die();
                dd($e->getMessage());

            }

        }
        return Redirect::to('finance/UploadJournalVoucherView?pageType=&&parentCode=33&&m='.$m.'');

    }



    public  function insertBankRv(Request $request)
    {
        //echo "<pre>";
        //print_r($_POST); die;
        DB::Connection('mysql2')->beginTransaction();

        try {
        $brv_no=CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
        $Rvs = new NewRvs();
        $Rvs=$Rvs->SetConnection('mysql2');
        $Rvs->rv_no=$brv_no;
        $Rvs->rv_date=$request->rv_date_1;
        $Rvs->ref_bill_no=$request->ref_bill_no_1;
        $Rvs->cheque_no=$request->cheque_no_1;
        $Rvs->cheque_date=$request->cheque_date_1;
        $Rvs->description=$request->description_1;
        $Rvs->date=date('Y-m-d');
        $Rvs->status=1;
        $Rvs->rv_status=1;
        $Rvs->rv_type=1;
        $Rvs->username    = Auth::user()->name;
        $Rvs->save();
        $master_id=$Rvs->id;

        $account_data=$request->account_id;
        $index=0;

        $total_amount=0;
        foreach($account_data as $row):

            $RvData=new NewRvdata();
            $RvData=$RvData->SetConnection('mysql2');
            $RvData->master_id=$master_id;
            $RvData->rv_no=$brv_no;
            //$JvData->pv_date=$request->pv_date_1;

            $acc_id=$row;
            $acc_id=explode(',',$acc_id);

            $paid_to=$request->paid_to;
            $desc=$request->desc;
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
            $RvData->acc_id=$row;
            $RvData->amount=CommonHelper::check_str_replace($amount);
            $RvData->debit_credit=$debit_credit;
            $PaidTo = explode(',',$paid_to[$index]);
            $RvData->paid_to_id= $PaidTo[0];
            $RvData->paid_to_type= $PaidTo[1];
            $RvData->description=$desc[$index];
            $RvData->status=1;
            $RvData->rv_status=1;
            $RvData->save();
            $pv_data_id=$RvData->id;
            $index++;
        endforeach;
        $id=$master_id;
        $m=Input::get('m');

        FinanceHelper::audit_trail($brv_no,$request->rv_date_1,$total_amount,3,'Insert');
        $subject = 'New Bank Receipt Voucher Created ' . $brv_no;
            NotificationHelper::send_email('Bank Receipt Voucher','Create',26,$brv_no,$subject);
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/viewBankRvNew?pageType=&&parentCode=24&&m='.$m.'');
    }

    public  function insertCashRv(Request $request)
    {
        //echo "<pre>";
        //print_r($_POST); die;
        DB::Connection('mysql2')->beginTransaction();

        try {

        $crv_no=CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),2);
        $Rvs = new NewRvs();
        $Rvs=$Rvs->SetConnection('mysql2');
        $Rvs->rv_no=$crv_no;
        $Rvs->rv_date=$request->rv_date_1;
        $Rvs->ref_bill_no=$request->ref_bill_no_1;
//        $Rvs->cheque_no=$request->cheque_no_1;
//        $Rvs->cheque_date=$request->cheque_date_1;
        $Rvs->description=$request->description_1;
        $Rvs->date=date('Y-m-d');
        $Rvs->status=1;
        $Rvs->rv_status=1;
        $Rvs->rv_type=2;
        $Rvs->username = Auth::user()->name;
        $Rvs->save();
        $master_id=$Rvs->id;

        $account_data=$request->account_id;
        $index=0;

        $total_amount=0;
        foreach($account_data as $row):

            $RvData=new NewRvdata();
            $RvData=$RvData->SetConnection('mysql2');
            $RvData->master_id=$master_id;
            $RvData->rv_no=$crv_no;
            //$JvData->pv_date=$request->pv_date_1;

            $acc_id=$row;
            $acc_id=explode(',',$acc_id);

            $paid_to=$request->paid_to;
            $desc=$request->desc;
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
            $RvData->acc_id=$row;
            $RvData->amount=CommonHelper::check_str_replace($amount);
            $RvData->debit_credit=$debit_credit;
            $PaidTo = explode(',',$paid_to[$index]);
            $RvData->paid_to_id= $PaidTo[0];
            $RvData->paid_to_type= $PaidTo[1];
            $RvData->description=$desc[$index];
            $RvData->status=1;
            $RvData->rv_status=1;
            $RvData->save();
            $pv_data_id=$RvData->id;
            $index++;
        endforeach;
        $id=$master_id;
        $m=Input::get('m');

        FinanceHelper::audit_trail($crv_no,$request->rv_date_1,$total_amount,3,'Insert');
        $subject = 'New Cash Receipt Voucher Created ' . $crv_no;
        NotificationHelper::send_email('Cash Receipt Voucher','Create',26,$crv_no,$subject);
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/viewCashRvNew?pageType=&&parentCode=24&&m='.$m.'');
    }


    public function DataSortBySupplier()
    {

        return view('Finance.AjaxPaymentPurchaseVoucher');
    }

    public function getVoucherDetailDataByVoucherNo()
    {

        $VoucherNo = $_GET['VoucherNo'];
        $Condition = substr($VoucherNo,0,2);
        if($Condition == "bp" || $Condition == "cp")
        {
            $NewPv = new NewPv();
            $NewPv = $NewPv->SetConnection('mysql2');
            $NewPv = $NewPv->where('pv_no',$VoucherNo)->first();
            $NewPvData = new NewPvData();
            $NewPvData = $NewPvData->SetConnection('mysql2');
            $NewPvData = $NewPvData->where('master_id',$NewPv->id)->get();
            return view('Finance.getVoucherDetailDataByVoucherNo',compact('NewPv','NewPvData','Condition'));
        }
        elseif($Condition == "jv")
        {
            $NewJvs = new NewJvs();
            $NewJvs = $NewJvs->SetConnection('mysql2');
            $NewJvs = $NewJvs->where('jv_no',$VoucherNo)->first();
            $NewJvData = new NewJvData();
            $NewJvData = $NewJvData->SetConnection('mysql2');
            $NewJvData = $NewJvData->where('master_id',$NewJvs->id)->get();
            return view('Finance.getVoucherDetailDataByVoucherNoJv',compact('NewJvs','NewJvData'));
        }

    }

    public function verify_voucher(Request $request)
    {
        // dd($request);
        $table=$request->table;
        $table_data=$request->table_data;
        $voucher_status=$request->voucher_status;
        $voucher_no=$request->voucher_no;
        $voucher_date=$request->voucher_date;
        $voucher_no_txt=$request->voucher_no_txt;
        $type=$request->type;
        DB::Connection('mysql2')->table($table)->where(''.$voucher_no_txt.'',$voucher_no)->update(['verified_by'=>Auth::user()->name]);
        FinanceHelper::audit_trail($voucher_no,$voucher_date,0,1,'Verify');
    }
    public function approve_voucher(Request $request)
    {
        // dd($request);
        DB::Connection('mysql2')->beginTransaction();

        try {
        $table=$request->table;
        $table_data=$request->table_data;
        $voucher_status=$request->voucher_status;
        $voucher_no=$request->voucher_no;
        $voucher_date=$request->voucher_date;
        $voucher_no_txt=$request->voucher_no_txt;
        $type=$request->type;
        $voucher_name = $request->voucher_name;

        $data=DB::Connection('mysql2')->selectOne('select '.$voucher_status.' as voucher_status , '.$voucher_date.'
         as voucher_date, description from '.$table.' where '.$voucher_no_txt.'="'.$voucher_no.'" and status=1 and '.$voucher_status.'=1');

        if ($data->voucher_status!=1):
            echo $voucher_no;
            die;
            endif;

        $voucher_date=$data->voucher_date;
        $description=$data->description;

        $detail_data=DB::Connection('mysql2')->select('select * from '.$table_data.' where status=1 and '.$voucher_no_txt.'="'.$voucher_no.'"');
        foreach($detail_data as $row):

            $trans1 = new Transactions();
            $trans1 = $trans1->SetConnection('mysql2');
            $trans1->acc_id = $row->acc_id;
            $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row->acc_id, '');
            $trans1->master_id = $row->id;
            $trans1->sub_department_id = $row->sub_department_id ?? 0;
            $trans1->particulars = $row->description;
            $trans1->opening_bal = 0;
            $trans1->debit_credit = $row->debit_credit;
            $trans1->amount = $row->amount;
            $trans1->voucher_no = $voucher_no;
            $trans1->voucher_type = $type;
            $trans1->v_date = $voucher_date;
            $trans1->date = date('Y-m-d');
            $trans1->action = 1;
            $trans1->username = Auth::user()->name;
            $trans1->save();

         endforeach;


            if ($type == 2 || $type == 3):
                $data_purchase_voucher = DB::Connection('mysql2')->table('new_pv_data as a')
                    ->join('new_pv as b', 'a.pv_no', '=', 'b.pv_no')
                    ->join('accounts as c', 'c.id', '=', 'a.acc_id')
                    ->join('supplier as d', 'd.acc_id', '=', 'c.id')
                    ->select('a.*','d.id as supp_id','b.id as newPVId')
                    ->where('a.pv_no', $voucher_no)
                    ->get();

                foreach($data_purchase_voucher as $row1):

                    $breakup = new NewPurchaseVoucherPayment();
                    $breakup=$breakup->SetConnection('mysql2');
                    $breakup->table=2;
                    $breakup->new_pv_no=$voucher_no;
                    $breakup->supplier_id=$row1->supp_id;

                    $breakup->new_pv=$row1->newPVId;
                    $breakup->date=$row1->pv_date;

                    $breakup->amount=$row1->amount;
                    $breakup->Payment_nature =$row1->debit_credit;
                    $breakup->status=1;
                    $breakup->type=3;
                    $breakup->username=Auth::user()->name;
                    $breakup->save();

                endforeach;
            endif;

            if ($type==1):
                $data_purchase_voucher = DB::Connection('mysql2')->table('new_jv_data as a')
                    ->join('new_jvs as b', 'a.jv_no', '=', 'b.jv_no')
                    ->join('accounts as c', 'c.id', '=', 'a.acc_id')
                    ->join('supplier as d', 'd.acc_id', '=', 'c.id')
                    ->select('a.*','d.id as supp_id')
                    ->where('a.jv_no', $voucher_no)
                    ->get();

                foreach($data_purchase_voucher as $row1):

                    $breakup=new NewPurchaseVoucherPayment();
                    $breakup=$breakup->SetConnection('mysql2');
                    $breakup->table=1;
                    $breakup->new_pv_no=$voucher_no;
                    $breakup->supplier_id=$row1->supp_id;
                    $breakup->amount=$row1->amount;
                    $breakup->Payment_nature =$row1->debit_credit;
                    $breakup->status=1;
                    $breakup->type=3;
                    $breakup->username=Auth::user()->name;
                    $breakup->save();

                endforeach;
            endif;


        DB::Connection('mysql2')->table($table)->where(''.$voucher_no_txt.'',$voucher_no)->update(['approved_user'=>Auth::user()->name]);
        DB::Connection('mysql2')->table($table)->where(''.$voucher_no_txt.'',$voucher_no)->update([''.$voucher_status.''=>2]);
        // dd($voucher_name);
        if($voucher_name != null){
            $subject = $voucher_name . ' Approved ' . $voucher_no;
            NotificationHelper::send_email($voucher_name,'Approve',26,$voucher_no,$subject);
        }
        DB::Connection('mysql2')->commit();
        }
    catch(\Exception $e)
    {
    DB::Connection('mysql2')->rollback();
    echo "EROOR"; //die();
    dd($e->getMessage());

    }
        echo $voucher_no;

    }

    public function get_advance_amount(Request $request)
    {
        $id=$request->supplier_id;
        $acc_id=CommonHelper::get_supplier_acc_id($id);
        $amount=CommonHelper::get_advance($acc_id,0,1);

        if ($amount<0):
            return      $amount=$amount;
        endif;
    }

    public static function adjust_amount($id,$supplier_id)
    {

        return view('Finance.adjust_amount',compact('id','supplier_id'));
    }

    public  function adjust_amount_entry(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {

            $data=$request->advance;
            $adjust_amount=$request->adjust_amount;



        foreach($data as $key=> $row):


            $outstanding=new NewPurchaseVoucherPayment();
            $outstanding=$outstanding->SetConnection('mysql2');

             $advance=explode(',',$row);
             $advance=$advance[0];
             $voucher_no=$advance;

            if ($voucher_no!='' && $adjust_amount[$key]>0):
                $outstanding->new_pv_no=$voucher_no;
                $outstanding->amount=$adjust_amount[$key];
                $outstanding->supplier_id=$request->supplier;
                $outstanding->Payment_nature=0;
                $outstanding->username=Auth::user()->username;
                $outstanding->status=1;
                $outstanding->table=2;
                $outstanding->date=date('Y-m-d');
                $outstanding->type=2;
                $outstanding->new_purchase_voucher_id=$request->purchase_voucher_id;
                $outstanding->save();


                echo 'amir';
            endif;
        endforeach;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }

    }

    public static function hit_vouchers()
    {
        $voucher_no='cpv2009019';
        $type=2;
        if ($type==2):
            $data_purchase_voucher = DB::Connection('mysql2')->table('new_pv_data as a')
                ->join('new_pv as b', 'a.pv_no', '=', 'b.pv_no')
                ->join('accounts as c', 'c.id', '=', 'a.acc_id')
                ->join('supplier as d', 'd.acc_id', '=', 'c.id')
                ->select('a.*','d.id as supp_id')
                ->where('a.pv_no', $voucher_no)
                ->where('b.type', 1)
                ->get();

            foreach($data_purchase_voucher as $row1):

                $breakup=new NewPurchaseVoucherPayment();
                $breakup=$breakup->SetConnection('mysql2');
                $breakup->table=2;
                $breakup->new_pv_no=$voucher_no;
                $breakup->supplier_id=$row1->supp_id;
                $breakup->amount=$row1->amount;
                $breakup->Payment_nature =$row1->debit_credit;
                $breakup->status=1;
                $breakup->type=3;
                $breakup->username=Auth::user()->username;
                //$breakup->save();

            endforeach;
        endif;

        if ($type==1):
            $data_purchase_voucher = DB::Connection('mysql2')->table('new_jv_data as a')
                ->join('new_jvs as b', 'a.jv_no', '=', 'b.jv_no')
                ->join('accounts as c', 'c.id', '=', 'a.acc_id')
                ->join('supplier as d', 'd.acc_id', '=', 'c.id')
                ->select('a.*','d.id as supp_id')
                ->where('a.jv_no', $voucher_no)
                ->get();

            foreach($data_purchase_voucher as $row1):

                $breakup=new NewPurchaseVoucherPayment();
                $breakup=$breakup->SetConnection('mysql2');
                $breakup->table=1;
                $breakup->new_pv_no=$voucher_no;
                $breakup->supplier_id=$row1->supp_id;
                $breakup->amount=$row1->amount;
                $breakup->Payment_nature =$row1->debit_credit;
                $breakup->status=1;
                $breakup->type=3;
                $breakup->username=Auth::user()->username;
                $breakup->save();

            endforeach;
        endif;
    }

    public static function CreateInvoiceOpening()
    {


        DB::Connection('mysql2')->beginTransaction();

        try {
          $data=  DB::COnnection('mysql2')->select('select * from new_jv_data where jv_no="jv2011008" and debit_credit=1');

            foreach($data as $row):

            $InvNo=SalesHelper::get_unique_no_inv(date('y'),date('m'));
            $invoice=new Invoice();
            $invoice=$invoice->SetConnection('mysql2');
            $invoice->inv_no=$InvNo;
            $invoice->job_order_no=$row->jv_no;
            $invoice->inv_date='2020-06-30';
            $branch_data=CommonHelper::get_single_row('branch','id',$row->paid_to_id);
            $invoice->ship_to=$branch_data->branch_name;
            $invoice->bill_to_client_id=2;
            $invoice->branch_id=$row->paid_to_id;
            $invoice->inv_desc_id = 0;
            $invoice->po_no = '-';
            $invoice->description='Opening Balance';
            $invoice->inv_status=1;
            $invoice->status=1;
            $invoice->date=date('Y-m-d');
            $invoice->username='Amir';
             $invoice->type=1;
            $invoice->save();
            $master_id=$invoice->id;


                $data1=  DB::COnnection('mysql2')->select('select * from new_jv_data where jv_no="jv2011008" and debit_credit=1 and paid_to_id="'.$row->paid_to_id.'"');
                $total_amount=0;
                foreach($data1 as $row1):
                $inv_data=new InvoiceData();
                $inv_data=$inv_data->SetConnection('mysql2');
                $inv_data->inv_no=$InvNo;
                $product_id=0;



                 $inv_data->master_id=$master_id;
                $inv_data->job_order_no=$row1->jv_no;
                $inv_data->product_id=0;
                $inv_data->branch_id=$row1->paid_to_id;
                $inv_data->description='Opening Balance';
                $inv_data->uom_id=0;
                $inv_data->qty=1;
                $inv_data->rate=$row1->amount;
                $inv_data->amount=$row1->amount;
                $inv_data->net_amount=$row1->amount;
                $total_amount+=$row1->amount;


                $inv_data->status=1;
                $inv_data->inv_status = 1;
                $inv_data->username = 'Amir';
                 $inv_data->type=1;
              $inv_data->save();
            endforeach;

                $invoice_total=new Invoice_totals();
                $invoice_total=$invoice_total->SetConnection('mysql2');
                $invoice_total->master_id=$master_id;
                $invoice_total->net_value_before_tax=$total_amount;
                $invoice_total->type=1;
                $invoice_total->net_value=$total_amount;
                $invoice_total->username='Amir';
                $invoice_total->save();

            endforeach;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }

    }

    public static function CreateInvoiceOpeningUpdate()
    {


        DB::Connection('mysql2')->beginTransaction();

        try {
            $data=  DB::COnnection('mysql2')->select('select * from new_jv_data where jv_no="jv2011018" and debit_credit=1');

            foreach($data as $row):

                $InvNo=SalesHelper::get_unique_no_inv(date('y'),date('m'));
                $invoice=new Invoice();
                $invoice=$invoice->SetConnection('mysql2');
                $invoice->inv_no=$InvNo;
                $invoice->job_order_no=$row->jv_no;
                $invoice->inv_date='2020-06-30';
                $branch_data=CommonHelper::get_single_row('branch','id',$row->paid_to_id);
                $invoice->ship_to=$branch_data->branch_name;
                $invoice->bill_to_client_id=15;
                $invoice->branch_id=$row->paid_to_id;
                $invoice->inv_desc_id = 0;
                $invoice->po_no = '-';
                $invoice->description=$row->description;
                $invoice->inv_status=1;
                $invoice->status=1;
                $invoice->date=date('Y-m-d');
                $invoice->username='Ilyas Qadri';
                $invoice->type=1;
                $invoice->client_ref=$row->description;
                 $invoice->save();
                $master_id=$invoice->id;


                $data1=  DB::COnnection('mysql2')->select('select * from new_jv_data where id="'.$row->id.'" and debit_credit=1 and paid_to_id="'.$row->paid_to_id.'"');
                $total_amount=0;
                $total_before=0;
                $net_value_tax=0;
                foreach($data1 as $row1):

                    $data2=  DB::COnnection('mysql2')->selectOne('select amount,acc_id from new_jv_data where jv_no="jv2011018"
                    and debit_credit=0 and description="'.$row1->description.'"');



                    if (isset($data2->amount)):
                     $amount1=$row1->amount-$data2->amount;
                      $total_before+=$amount1;
                     $amount=$row1->amount;
                    $tax=$data2->acc_id;
                    $tax_amount=$data2->amount;
                        $net_value_tax+=$tax_amount;
                    else:
                        $amount= $row1->amount;
                        $tax=0;
                        $tax_amount=0;
                        endif;
                    $inv_data=new InvoiceData();
                    $inv_data=$inv_data->SetConnection('mysql2');
                    $inv_data->inv_no=$InvNo;
                    $product_id=0;



                   $inv_data->master_id=$master_id;
                    $inv_data->job_order_no=$row1->jv_no;
                    $inv_data->product_id=0;
                    $inv_data->branch_id=$row1->paid_to_id;
                    $inv_data->description=$row->description;
                    $inv_data->uom_id=0;
                    $inv_data->qty=1;
                    $inv_data->txt_acc_id=$tax;
                    $inv_data->txt_amount=$tax_amount;
                    $inv_data->rate=$amount1;
                    $inv_data->amount=$amount1;
                    $inv_data->net_amount=$amount;
                    $total_amount+=$amount;


                    $inv_data->status=1;
                    $inv_data->inv_status = 1;
                    $inv_data->username = 'Ilyas Qadri';
                    $inv_data->type=1;
                   $inv_data->save();
                endforeach;

                $invoice_total=new Invoice_totals();
                $invoice_total=$invoice_total->SetConnection('mysql2');
              $invoice_total->master_id=$master_id;
                $invoice_total->net_value_before_tax=$total_before;
                $invoice_total->net_tax_value=$net_value_tax;
                $invoice_total->type=1;
                $invoice_total->net_value=$total_amount;
                $invoice_total->username='Ilyas Qadri';
               $invoice_total->save();

            endforeach;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }

    }

    function update_sales_order(Request $request)
    {
        // dd($request);
        $Cont = DB::Connection('mysql2')->table('delivery_note')->where('master_id',$request->sales_order_id)->where('status',1);
        if($Cont->count() > 0)
        {
            echo "Can't Edit ".strtoupper($request->so_no).": This Sales Order against Delivery Note Created  ".$Cont->first()->gd_no;
            die();
        }

        DB::Connection('mysql2')->beginTransaction();
        try {

            $byers_id=$request->buyers_id;
            $byers_id=explode('*',$byers_id);
            $byers_id=$byers_id[0];

            $sales_order=new Sales_Order();
            $sales_order=$sales_order->SetConnection('mysql2');
            $master_id=$request->sales_order_id;
            $sales_order=$sales_order->find($master_id);
            $sales_order->buyers_unit=$request->buyers_unit;
            $sales_order->so_date=$request->so_date;
            $sales_order->model_terms_of_payment=$request->model_terms_of_payment;
            $sales_order->order_no=$request->order_no;
            $sales_order->order_date=$request->order_date;
            $sales_order->other_refrence=$request->other_refrence;
            $sales_order->desptch_through=$request->desptch_through;
            $sales_order->destination=$request->destination;;
            $sales_order->terms_of_delivery=$request->terms_of_delivery;;
            $sales_order->due_date=$request->due_date;
            $sales_order->status=1;
            $sales_order->username=Auth::user()->name;
            $sales_order->amount_in_words=$request->rupeess;;
            $sales_order->date=date('Y-m-d');
            $sales_order->buyers_id=$byers_id;
            $sales_order->description=$request->description;
            $sales_order->verified=$request->verified;
            $sales_tax=CommonHelper::check_str_replace($request->sales_tax);
            $sales_tax_further=CommonHelper::check_str_replace($request->sales_tax_further);

            $sales_order->sales_tax=$sales_tax;
            $sales_order->sales_tax_further=$sales_tax_further;
            $sales_order->sales_tax_further=$sales_tax_further;
            $sales_order->p_type=$request->v_type;
            $sales_order->department=$request->department;
            $sales_order->save();
            $data=$request->item_id;
            $count=1;
            $total_amount = 0;
            DB::Connection('mysql2')->table('sales_order_data')->where('master_id',$master_id)->delete();
            DB::Connection('mysql2')->table('addional_expense_sales_order')->where('main_id',$master_id)->where('voucher_no',$request->so_no)->delete();

            foreach($data as $key=>$row):

                // dd($data, explode(',', $row));
                $itemDetail = explode(',', $row);
                // $product_id=$request->input('product_id')[$key];
                $product_id=$itemDetail[0];
                // dd($product_id);
                if ($product_id!=''){
                    // dd(explode(',',$request->input('tax')[$key]));
                    $tax_percent = explode(',',$request->input('tax')[$key]);
                    $sales_order =new Sales_Order_Data();
                    $sales_order=$sales_order->SetConnection('mysql2');

                    $sales_order->master_id=$master_id;
                    $sales_order->so_no=$request->so_no;

                    $sales_order->desc=$product_id;
                    $sales_order->item_id=$product_id;
                    $sales_order->qty=$request->input('actual_qty')[$key];
                    $sales_order->rate=$request->input('rate')[$key];
                    $sales_order->tax=$tax_percent[1] ?? 0;
                    $sales_order->tax_amount=$request->input('tax_amount')[$key];
                    $sales_order->rate=$request->input('rate')[$key];
                    $sales_order->amount=CommonHelper::check_str_replace($request->input('amount')[$key]);
                    $sales_order->sub_total=$request->input('amount')[$key];

                    $sales_order->amount=CommonHelper::check_str_replace($request->input('after_dis_amount')[$key]);
                    $total_amount+=CommonHelper::check_str_replace($request->input('after_dis_amount')[$key]);
                    // dd('in');

                    $sales_order->status=1;
                    $sales_order->date=date('Y-m-d');
                    $sales_order->username=Auth::user()->name;
                    $sales_order->groupby=$count;
                    $sales_order->save();

                }
                    // $update_bundles=array
                    // (

                    //     'qty'=>$request->input('actual_qty')[$key],
                    //     'rate'=>$request->input('rate')[$key],
                    //     'amount'=>$request->input('amount')[$key],
                    //     'discount_percent'=>$request->input('discount_percent')[$key],
                    //     'discount_amount'=>str_replace(',','',$request->input('discount_amount')[$key]),
                    //     'net_amount'=>$request->input('after_dis_amount')[$key]
                    // );
                //  DB::Connection('mysql2')->table('bundles')->where('id','=',$product_id)->update($update_bundles);

                $count++;
            endforeach;
            //Abdul Code
            $Loop = Input::get('account_id');

            if($Loop !="")
            {
                $Counta = 0;
                foreach($Loop as $LoopFil)
                {
                    $ExpData['voucher_no'] = $request->so_no;
                    $ExpData['main_id'] = $master_id;
                    $ExpData['acc_id'] = Input::get('account_id')[$Counta];
                    $ExpData['amount'] = Input::get('expense_amount')[$Counta];
                    $total_amount+=Input::get('expense_amount')[$Counta];
                    $ExpData['created_date'] = date('Y-m-d');
                    $ExpData['username'] = Auth::user()->name;
                    $Counta++;
                    DB::Connection('mysql2')->table('addional_expense_sales_order')->insert($ExpData);
                }
            }
            //Abdul Code
            SalesHelper::sales_activity($request->so_no,$request->so_date,$total_amount+$sales_tax,1,'Update');
            DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {


            DB::rollBack();

        }
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/viewSalesOrderList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#Murtaza');


    }

    public function company()
    {

        return view('company');
    }

    function set_company($id)
    {
        Session::put('run_company',$id);
       return Redirect::to('d');
    }

    public function abc()
    {

        return view('Sales.abc');

    }

    function insert_new_pv(Request $request)
    {
//        echo "<pre>";
//        print_r($_POST); die;
        DB::Connection('mysql2')->beginTransaction();

        try {
            $pv_no = CommonHelper::uniqe_no_for_stp(date('y'), date('m'), 1);
            $Master['pv_no'] = $pv_no;
            $Master['pv_date'] = $request->pv_date;
            $Master['supplier_invoice_no'] = $request->supplier_invoice_no;
            $Master['supplier_invoice_date'] = $request->supplier_invoice_date;
            $Master['supplier_id'] = $request->supplier_id;
            $Master['sales_tax_acc_id'] = $request->sales_tax;
            $Master['sales_tax_amount'] = $request->sales_tax_amount;

            $Master['description'] = $request->description_1;
            $Master['status'] = 1;
            $Master['pv_status'] = 1;
            $Master['username'] = Auth::user()->name;
            $Master['date'] = date('Y-m-d');
            $MasterId = DB::Connection('mysql2')->table('new_pvv')->insertGetId($Master);

            $Counta = 0;
            $Loop = $request->qty;
            foreach ($Loop as $row):
            $Detail['master_id'] = $MasterId;
            $Detail['pv_no'] = $pv_no;
            $Detail['description'] = Input::get('desc')[$Counta];
            $Detail['uom_id'] = Input::get('uom_id')[$Counta];
            $Detail['qty'] = Input::get('qty')[$Counta];
            $Detail['rate'] = Input::get('rate')[$Counta];
            $Detail['amount'] = Input::get('amount')[$Counta];

            $Counta ++;
            DB::Connection('mysql2')->table('new_pvv_data')->insert($Detail);

            endforeach;


            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/new_pv_list?m='.$_GET['m']);
    }


    function update_new_pv(Request $request)
    {
//        echo "<pre>";
//        print_r($_POST); die;
        DB::Connection('mysql2')->beginTransaction();

        try {
            $EditId = $request->EditId;
            $PvNo = $request->pv_no;

            $Master['pv_date'] = $request->pv_date;
            $Master['supplier_invoice_no'] = $request->supplier_invoice_no;
            $Master['supplier_invoice_date'] = $request->supplier_invoice_date;
            $Master['supplier_id'] = $request->supplier_id;
            $Master['sales_tax_acc_id'] = $request->sales_tax;
            $Master['sales_tax_amount'] = $request->sales_tax_amount;

            $Master['description'] = $request->description_1;
            $Master['status'] = 1;
            $Master['pv_status'] = 1;
            $Master['username'] = Auth::user()->name;
            $Master['date'] = date('Y-m-d');
            DB::Connection('mysql2')->table('new_pvv')->where('id',$EditId)->update($Master);
            DB::Connection('mysql2')->table('new_pvv_data')->where('master_id',$request->EditId)->delete();

            $Counta = 0;
            $Loop = $request->qty;
            foreach ($Loop as $row):
                $Detail['master_id'] = $EditId;
                $Detail['pv_no'] = $PvNo;
                $Detail['description'] = Input::get('desc')[$Counta];
                $Detail['uom_id'] = Input::get('uom_id')[$Counta];
                $Detail['qty'] = Input::get('qty')[$Counta];
                $Detail['rate'] = Input::get('rate')[$Counta];
                $Detail['amount'] = Input::get('amount')[$Counta];

                $Counta ++;
                DB::Connection('mysql2')->table('new_pvv_data')->insert($Detail);

            endforeach;


            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
        return Redirect::to('finance/new_pv_list?m='.$_GET['m']);
    }


    function  approve_new_pv(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {
        $id=$request->id;
            $data = DB::Connection('mysql2')->table('new_pvv as a')
            ->join('new_pvv_data as b', 'a.id', '=', 'b.master_id')
             ->where('a.pv_status',1)
             ->where('a.id',$id)
            ->select('a.pv_date','a.pv_no','a.description','a.sales_tax_acc_id','a.sales_tax_amount', DB::raw('sum(b.amount)amount'),'a.supplier_id')
            ->first();

            $data1=array
                        (
                            'pv_status'=>2,
                            'approve_username'=>Auth::user()->name
                        );
            DB::Connection('mysql2')->table('new_pvv')->where('id', $id)->update($data1);


            $trans1 = new Transactions();
            $trans1 = $trans1->SetConnection('mysql2');
            $trans1->acc_id = 806;
            $trans1->acc_code = '1-2-11';
            $trans1->master_id = 0;
            $trans1->particulars = $data->description;
            $trans1->opening_bal = 0;
            $trans1->debit_credit = 1;
            $trans1->amount = $data->amount;
            $trans1->paid_to=0;
            $trans1->paid_to_type=0;
            $trans1->voucher_no = $data->pv_no;
            $trans1->voucher_type = 10;
            $trans1->v_date = $data->pv_date;
            $trans1->date = date('Y-m-d');
            $trans1->action = 1;
            $trans1->username = Auth::user()->name;
            $trans1->save();


            $trans1 = new Transactions();
            $trans1 = $trans1->SetConnection('mysql2');
            $trans1->acc_id = $data->sales_tax_acc_id;
            $trans1->acc_code =  FinanceHelper::getAccountCodeByAccId($data->sales_tax_acc_id, '');;
            $trans1->master_id = 0;
            $trans1->particulars = $data->description;
            $trans1->opening_bal = 0;
            $trans1->debit_credit = 1;
            $trans1->amount = $data->sales_tax_amount;
            $trans1->paid_to=0;
            $trans1->paid_to_type=0;
            $trans1->voucher_no = $data->pv_no;
            $trans1->voucher_type = 10;
            $trans1->v_date = $data->pv_date;
            $trans1->date = date('Y-m-d');
            $trans1->action = 1;
            $trans1->username = Auth::user()->name;
            $trans1->save();

            $supp_acc_id=CommonHelper::get_supplier_acc_id($data->supplier_id);

            $trans1 = new Transactions();
            $trans1 = $trans1->SetConnection('mysql2');
            $trans1->acc_id = $supp_acc_id;
            $trans1->acc_code =  FinanceHelper::getAccountCodeByAccId($supp_acc_id, '');;
            $trans1->master_id = 0;
            $trans1->particulars = $data->description;
            $trans1->opening_bal = 0;
            $trans1->debit_credit = 0;
            $trans1->amount = $data->sales_tax_amount+ $data->amount;
            $trans1->paid_to=0;
            $trans1->paid_to_type=0;
            $trans1->voucher_no = $data->pv_no;
            $trans1->voucher_type = 10;
            $trans1->v_date = $data->pv_date;
            $trans1->date = date('Y-m-d');
            $trans1->action = 1;
            $trans1->username = Auth::user()->name;
            $trans1->save();

            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }


    }
}
