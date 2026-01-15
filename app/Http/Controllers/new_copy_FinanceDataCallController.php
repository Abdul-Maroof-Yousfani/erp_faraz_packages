<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\NewRvs;use Faker\Test\Provider\ProviderOverrideTest;use Illuminate\Http\Request;
use App\category;
use App\Models\Account;
use App\Models\TaxSection;
use App\Models\Jvs;
use App\Models\Jvs_data;
use App\Models\Pvs;
use App\Models\Pvs_data;
use App\Models\Rvs;
use App\Models\Rvs_data;
use App\Models\Supplier;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use App\Models\CostCenter;
use App\Models\NewPurchaseVoucher;
use App\Models\NewPurchaseVoucherData;
use App\Models\NewJvs;
use App\Models\NewJvData;
use App\Models\Transactions;
use App\Models\NewPurchaseVoucherPayment;
use App\Models\Client;
use App\Models\NewPv;
use App\Models\NewPvData;
use App\Models\Employee;
use App\Models\Customer;
use App\Helpers\NotificationHelper;
use Input;
use Auth;
use DB;
use Config;
use Session;
use PDF;
class FinanceDataCallController extends Controller
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

    public function viewJournalVoucherDetail(){
        return view('Finance.AjaxPages.viewJournalVoucherDetail');
    }
    public function viewJournalVoucherDetailPrint(){
        return view('Finance.AjaxPages.viewJournalVoucherDetailPrint');
    }

    public function viewBankRvDetailNew(){
        return view('Finance.AjaxPages.viewBankRvDetailNew');
    }
    public function viewBankRvDetailNewPrint(){
        return view('Finance.AjaxPages.viewBankRvDetailNewPrint');
    }

    public function viewCashRvDetailNew(){
        return view('Finance.AjaxPages.viewCashRvDetailNew');
    }
    public function viewCashRvDetailNewPrint(){
        return view('Finance.AjaxPages.viewCashRvDetailNewPrint');
    }



    public function viewPurchaseVoucherDetail(){
        return view('Finance.AjaxPages.viewPurchaseVoucherDetail');
    }

    public function viewDirectPurchaseVoucherDetail(){
        return view('Finance.AjaxPages.viewDirectPurchaseVoucherDetail');
    }


    public function viewCashPaymentVoucherDetail(){
        return view('Finance.AjaxPages.viewCashPaymentVoucherDetail');
	}

	public function viewBankPaymentVoucherDetail(){
        return view('Finance.AjaxPages.viewBankPaymentVoucherDetail');
	}
	public function viewBankPaymentVoucherDetailPrint(){
        return view('Finance.AjaxPages.viewBankPaymentVoucherDetailPrint');
	}


	public function viewExpenseVoucherDetail(){
        return view('Finance.AjaxPages.viewExpenseVoucherDetail');
	}

	public function viewBankPaymentVoucherDetailInDetail(){
        return view('Finance.AjaxPages.viewBankPaymentVoucherDetailInDetail');

	}
	public function viewBankPaymentVoucherDetailInDetailImport(){
        return view('Finance.AjaxPages.viewBankPaymentVoucherDetailInDetailImport');

	}

	public function viewBankPaymentVoucherDetailInDetailPrint(){
        return view('Finance.AjaxPages.viewBankPaymentVoucherDetailInDetailPrint');

	}

	public function viewBankPaymentVoucherDetailInDetailDirect(){
        return view('Finance.AjaxPages.viewBankPaymentVoucherDetailInDetailDirect');
	}

	public function viewCashReceiptVoucherDetail(){
        return view('Finance.AjaxPages.viewCashReceiptVoucherDetail');
	}

	public function viewBankReceiptVoucherDetail(){
        return view('Finance.AjaxPages.viewBankReceiptVoucherDetail');
	}
    public function viewContraVoucherDetail(){
        return view('Finance.AjaxPages.viewContraVoucherDetail');
    }
    public function filterJournalVoucherList(){
        return view('Finance.AjaxPages.filterJournalVoucherList');
    }

    
    public function getGJVDateAndAccontWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;


        $m = $request->m;
        $VoucherStatus = $request->VoucherStatus;
        
        return view('Finance.AjaxPages.getGJVDateAndAccontWise',compact('VoucherStatus','AccountId','m','FromDate','ToDate'));
    }

    public function getJvsDateAndAccontWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;


        $m = $request->m;
        $NewJvs=new NewJvs();
        $NewJvs=$NewJvs->SetConnection('mysql2');
        $VoucherStatus = $request->VoucherStatus;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'AND a.jv_status = '.$VoucherStatus;}

        if($AccountId !=""):

           $NewJvs= DB::Connection('mysql2')->select('select a.* from new_jvs a
            inner join new_jv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            and c.id="'.$AccountId.'"
            '.$Clause1.'
            and a.jv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            ');
        else:
        if($VoucherStatus !="")
        {
            $NewJvs=$NewJvs->where('status',1)->where('jv_status',$VoucherStatus)->whereBetween('jv_date',array($FromDate,$ToDate))->get();
        }
        else
        {
            $NewJvs=$NewJvs->where('status',1)->whereBetween('jv_date',array($FromDate,$ToDate))->get();
        }

        endif;



        return view('Finance.AjaxPages.getJvsDateAndAccontWise',compact('NewJvs','m','FromDate','ToDate'));
    }

    public function getRvsDateAndAccontWiseForSales(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;


        $m = $request->m;
        $NewRvs=new NewRvs();
        $NewRvs=$NewRvs->SetConnection('mysql2');
        $VoucherStatus = $request->VoucherStatus;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'AND a.rv_status = '.$VoucherStatus;}

        if($AccountId !=""):

           $NewRvs= DB::Connection('mysql2')->select('select a.* from new_rvs a
            inner join new_rv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            and c.id="'.$AccountId.'"
            '.$Clause1.'
            and a.rv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            and a.sales=1
            ');
        else:
        if($VoucherStatus !="")
        {
            $NewRvs=$NewRvs->where('status',1)->where('rv_status',$VoucherStatus)->whereBetween('rv_date',array($FromDate,$ToDate))->where('sales',1)->get();
        }
        else
        {
            $NewRvs=$NewRvs->where('status',1)->where('sales',1)->whereBetween('rv_date',array($FromDate,$ToDate))->get();
        }

        endif;



        return view('Finance.AjaxPages.getRvsDateAndAccontWiseForSales',compact('NewRvs','m','FromDate','ToDate'));
    }

    public function getBpvsDateAndAccontWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;
        $m = $request->m;
        $pvs=new NewPv();
        $pvs=$pvs->SetConnection('mysql2');

        $VoucherStatus = $request->VoucherStatus;
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
            ');
        else:
        if($VoucherStatus !="")
        {
            $pvs=$pvs->where('status',1)->where('pv_status',$VoucherStatus)->where('payment_type',1)->where('type',1)->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        else
        {
            $pvs=$pvs->where('status',1)->where('payment_type',1)->where('type',1)->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }

        endif;



        return view('Finance.AjaxPages.getBpvsDateAndAccontWise',compact('pvs','m','FromDate','ToDate'));
    }

    public function getCpvsDateAndAccontWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;
        $m = $request->m;
        $pvs=new NewPv();
        $pvs=$pvs->SetConnection('mysql2');

        $VoucherStatus = $request->VoucherStatus;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'AND a.pv_status = '.$VoucherStatus;}


        if($AccountId !=""):

           $pvs= DB::Connection('mysql2')->select('select a.* from new_pv a
            inner join new_pv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            '.$Clause1.'
            and a.payment_type = 2
            and a.type = 1
            and c.id="'.$AccountId.'"
            and a.pv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            ');
        else:
        if($VoucherStatus !="")
        {
            $pvs=$pvs->where('status',1)->where('pv_status',$VoucherStatus)->where('payment_type',2)->where('type',1)->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        else
        {
            $pvs=$pvs->where('status',1)->where('payment_type',2)->where('type',1)->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        endif;



        return view('Finance.AjaxPages.getCpvsDateAndAccontWise',compact('pvs','m','FromDate','ToDate'));
    }

    public function getCrvsDateAndAccontWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;
        $m = $request->m;
        $NewRvs=new NewRvs();
        $NewRvs=$NewRvs->SetConnection('mysql2');

        $VoucherStatus = $request->VoucherStatus;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'AND a.rv_status = '.$VoucherStatus;}

        if($AccountId !=""):

           $NewRvs= DB::Connection('mysql2')->select('select a.* from new_rvs a
            inner join new_rv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            '.$Clause1.'
            and a.rv_type = 2
            and c.id="'.$AccountId.'"
            and a.rv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            ');
        else:
        if($VoucherStatus != "")
        {
            $NewRvs=$NewRvs->where('status',1)->where('rv_status',$VoucherStatus)->where('rv_type',2)->whereBetween('rv_date',array($FromDate,$ToDate))->get();
        }
        else
        {
            $NewRvs=$NewRvs->where('status',1)->where('rv_type',2)->whereBetween('rv_date',array($FromDate,$ToDate))->get();
        }
        endif;



        return view('Finance.AjaxPages.getCrvsDateAndAccontWise',compact('NewRvs','m','FromDate','ToDate'));
    }

    public function getBrvsDateAndAccontWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;
        $m = $request->m;
        $NewRvs=new NewRvs();
        $NewRvs=$NewRvs->SetConnection('mysql2');

        $VoucherStatus = $request->VoucherStatus;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'AND a.rv_status = '.$VoucherStatus;}


        if($AccountId !=""):

           $NewRvs= DB::Connection('mysql2')->select('select a.* from new_rvs a
            inner join new_rv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            '.$Clause1.'
            and a.rv_type = 1
            and a.sales != 1
            and c.id="'.$AccountId.'"
            and a.rv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            ');
        else:
        if($VoucherStatus !="")
        {
            $NewRvs=$NewRvs->where('status',1)->where('rv_status',$VoucherStatus)->where('rv_type',1)->where('sales','!=',1)->whereBetween('rv_date',array($FromDate,$ToDate))->get();
        }
        else
        {
            $NewRvs=$NewRvs->where('status',1)->where('rv_type',1)->where('sales','!=',1)->whereBetween('rv_date',array($FromDate,$ToDate))->get();
        }
        endif;



        return view('Finance.AjaxPages.getBrvsDateAndAccontWise',compact('NewRvs','m','FromDate','ToDate'));
    }

    public function getOutstandingpvsDateAndAccontWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;
        $m = $request->m;
        $pvs=new NewPv();
        $pvs=$pvs->SetConnection('mysql2');

        $VoucherStatus = $request->VoucherStatus;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'a.pv_status = '.$VoucherStatus;}

        if($AccountId !=""):

           $pvs= DB::Connection('mysql2')->select('select a.* from new_pv a
            inner join new_pv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            '.$Clause1.'
            and a.type IN (2,3)
            and c.id="'.$AccountId.'"
            and a.pv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            ');
        else:
        if($VoucherStatus != "")
        {
            $pvs=$pvs->where('status',1)->where('pv_status',$VoucherStatus)->whereIn('type',[2,3])->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        else
        {
            $pvs=$pvs->where('status',1)->whereIn('type',[2,3])->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        endif;



        return view('Finance.AjaxPages.getOutstandingpvsDateAndAccontWise',compact('pvs','m','FromDate','ToDate'));
    }

    public function getOutstandingpvsDateAndAccontWiseImport(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccountId = $request->AccountId;
        $m = $request->m;
        $pvs=new NewPv();
        $pvs=$pvs->SetConnection('mysql2');

        $VoucherStatus = $request->VoucherStatus;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'a.pv_status = '.$VoucherStatus;}

        if($AccountId !=""):

           $pvs= DB::Connection('mysql2')->select('select a.* from new_pv a
            inner join new_pv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            '.$Clause1.'
            and a.type in (3.4)
            and c.id="'.$AccountId.'"
            and a.pv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            ');
        else:
        if($VoucherStatus != "")
        {
            $pvs=$pvs->where('status',1)->where('pv_status',$VoucherStatus)->whereIn('type',[3,4])->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        else
        {
            $pvs=$pvs->where('status',1)->whereIn('type',[3,4])->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        endif;



        return view('Finance.AjaxPages.getOutstandingpvsDateAndAccontWiseImport',compact('pvs','m','FromDate','ToDate'));
    }





    public function getprvsDateAndAccontWise(Request $request)
    {

        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
       $SupplierId = $request->SupplierId;
        $m = $request->m;
        $NewPurchaseVoucher=new NewPurchaseVoucher();
        $NewPurchaseVoucher=$NewPurchaseVoucher->SetConnection('mysql2');

        $VoucherStatus = $request->VoucherStatus;
        $Clause1 = '';
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'a.pv_status = '.$VoucherStatus;}

        if($SupplierId !=""):

           $NewPurchaseVoucher= DB::Connection('mysql2')->select('select * from new_purchase_voucher
            where status=1
            and grn_no = ""
            '.$Clause1.'
            and supplier="'.$SupplierId.'"
            and pv_date Between "'.$FromDate.'" and "'.$ToDate.'"
            ');
        else:
        if($VoucherStatus != "")
        {
            $NewPurchaseVoucher=$NewPurchaseVoucher->where('status',1)->where('pv_status',$VoucherStatus)->where('grn_no',"")->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        else
        {
            $NewPurchaseVoucher=$NewPurchaseVoucher->where('status',1)->where('grn_no',"")->whereBetween('pv_date',array($FromDate,$ToDate))->get();
        }
        endif;



        return view('Finance.AjaxPages.getprvsDateAndAccontWise',compact('NewPurchaseVoucher','m','FromDate','ToDate'));
    }



	public function filterCashPaymentVoucherList(){
        return view('Finance.AjaxPages.filterCashPaymentVoucherList');
    }

	public function filterBankPaymentVoucherList(){
        return view('Finance.AjaxPages.filterBankPaymentVoucherList');
	}
    public function createAccountFormAjax($id,$PageName = ""){

        $accounts = new Account;
        $accounts=$accounts->SetConnection('mysql2');
        $accounts = $accounts->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        return view('Finance.AjaxPages.createAccountFormAjax',compact('accounts','id','PageName'));

    }

    public function DeleteJvActivity()
    {

        $JvId = Input::get('JvId');
        $CostCenterDepartmentAllocationCount = DB::Connection('mysql2')->selectOne('select count(*) as c from cost_center_department_allocation
        where Main_master_id ="'.$JvId.'" and status = 1 ')->c;
        $DepartmentAllocationCount = DB::Connection('mysql2')->selectOne('select count(*) as c from cost_center_department_allocation
        where Main_master_id ="'.$JvId.'" and status = 1 ')->c;
        if($CostCenterDepartmentAllocationCount>0 || $CostCenterDepartmentAllocationCount !="")
        {
            $UpdateCostCenterDepartmentAllocation['status'] = 0;
            DB::connection('mysql2')->table('cost_center_department_allocation')->where('Main_master_id',$JvId)->update($UpdateCostCenterDepartmentAllocation);
        }
        if($DepartmentAllocationCount>0 || $DepartmentAllocationCount !="")
        {
            $UpdateDepartmentAllocation['status'] = 0;
            DB::connection('mysql2')->table('department_allocation')->where('Main_master_id',$JvId)->update($UpdateDepartmentAllocation);
        }
        $JvNo = Input::get('JvNo');
        $UserName = Input::get('UserName');
        $DeleteDate = Input::get('DeleteDate');
        $DeleteTime = Input::get('DeleteTime');
        $ActivityType = Input::get('ActivityType');

        $UpdateData['status'] = 0;
        $InsertData['jv_id'] = $JvId;
        $InsertData['jv_no'] = $JvNo;
        $InsertData['username'] = $UserName;
        $InsertData['date'] = $DeleteDate;
        $InsertData['time'] = $DeleteTime;
        $InsertData['activity_type'] = $ActivityType;

        $transaction['status']=0;
        $breakup['status']=0;

        DB::connection('mysql2')->table('jvs')->where('id',$JvId)->update($UpdateData);
        DB::connection('mysql2')->table('breakup_data')->where('jv_id',$JvId)->update($breakup);
        DB::connection('mysql2')->table('jv_data')->where('master_id',$JvId)->update($UpdateData);
        DB::connection('mysql2')->table('transactions')->where('master_id',$JvId)->where('voucher_type',1)->update($transaction);

        DB::Connection('mysql2')->table('jvs_activity')->insert($InsertData);

        echo $JvId;
    }

    public function DeleteRvActivity()
    {

        $RvId = Input::get('RvId');
        $Count = DB::Connection('mysql2')->selectOne('select count(*) as c from received_paymet
        where receipt_id ="'.$RvId.'" and status = 1 ')->c;

        if($Count>0)
        {
            $UpdatePaymentData['status'] = 0;
            $PaymentId = DB::Connection('mysql2')->selectOne('select id as c from received_paymet
            where receipt_id ="'.$RvId.'" and status = 1 ')->id;
            DB::connection('mysql2')->table('received_paymet')->where('id',$PaymentId)->update($UpdatePaymentData);
            $InsertData['payment_id'] = $PaymentId;
        }


        $RvNo = Input::get('RvNo');
        $UserName = Input::get('UserName');
        $DeleteDate = Input::get('DeleteDate');
        $DeleteTime = Input::get('DeleteTime');
        $ActivityType = Input::get('ActivityType');

        $UpdateData['status'] = 0;
        $InsertData['rv_id'] = $RvId;
        $InsertData['rv_no'] = $RvNo;
        $InsertData['username'] = $UserName;
        $InsertData['date'] = $DeleteDate;
        $InsertData['time'] = $DeleteTime;
        $InsertData['activity_type'] = $ActivityType;
        $transaction['status']=0;

        DB::connection('mysql2')->table('rvs')->where('id',$RvId)->update($UpdateData);
        DB::connection('mysql2')->table('rv_data')->where('master_id',$RvId)->update($UpdateData);
        DB::Connection('mysql2')->table('rvs_activity')->insert($InsertData);
        DB::connection('mysql2')->table('transactions')->where('master_id',$RvId)->where('voucher_type',3)->update($transaction);


    }

    public function DeletePvActivity()
    {

        $PvId = Input::get('PvId');
        $JvId = DB::Connection('mysql2')->selectOne('select purchase_id from pvs
        where id ="'.$PvId.'" and status = 1 ')->purchase_id;

        if($JvId >0 || $JvId!="")
        {
            $UpdateJvsData['paid'] = 0;
            $UpdateJvsData['payment_id'] = 0;

            DB::connection('mysql2')->table('jvs')->where('id',$JvId)->update($UpdateJvsData);
        }

        $PvNo = Input::get('PvNo');
        $UserName = Input::get('UserName');
        $DeleteDate = Input::get('DeleteDate');
        $DeleteTime = Input::get('DeleteTime');
        $ActivityType = Input::get('ActivityType');

        $UpdateData['status'] = 0;
        $InsertData['pv_id'] = $PvId;
        $InsertData['pv_no'] = $PvNo;
        $InsertData['purchase_id'] = $JvId;
        $InsertData['username'] = $UserName;
        $InsertData['date'] = $DeleteDate;
        $InsertData['time'] = $DeleteTime;
        $InsertData['activity_type'] = $ActivityType;
        $transaction['status']=0;

        $breakup['status']=0;
        DB::connection('mysql2')->table('pvs')->where('id',$PvId)->update($UpdateData);
        DB::connection('mysql2')->table('breakup_data')->where('pv_id',$PvId)->update($breakup);
        DB::connection('mysql2')->table('pv_data')->where('master_id',$PvId)->update($UpdateData);
        DB::Connection('mysql2')->table('pvs_activity')->insert($InsertData);
        DB::connection('mysql2')->table('transactions')->where('master_id',$PvId)->where('voucher_type',2)->update($transaction);


    }

    public function DeleteCvActivity()
    {

        $CvId = Input::get('CvId');
        $CvNo = Input::get('CvNo');
        $UserName = Input::get('UserName');
        $DeleteDate = Input::get('DeleteDate');
        $DeleteTime = Input::get('DeleteTime');
        $ActivityType = Input::get('ActivityType');

        $UpdateData['status'] = 0;
        $InsertData['cv_id'] = $CvId;
        $InsertData['cv_no'] = $CvNo;
        $InsertData['username'] = $UserName;
        $InsertData['date'] = $DeleteDate;
        $InsertData['time'] = $DeleteTime;
        $InsertData['activity_type'] = $ActivityType;
        $transaction['status']=0;

        DB::connection('mysql2')->table('contra')->where('id',$CvId)->update($UpdateData);
        DB::connection('mysql2')->table('contra_data')->where('master_id',$CvId)->update($UpdateData);
        DB::Connection('mysql2')->table('contra_activity')->insert($InsertData);
        DB::connection('mysql2')->table('transactions')->where('master_id',$CvId)->where('voucher_type',6)->update($transaction);



    }

    public function direct_invoice_approve($id)
    {

        DB::Connection('mysql2')->beginTransaction();
        try
        {


            $grn= DB::Connection('mysql2')->table('new_purchase_voucher_data as a')
                ->join('new_purchase_voucher as b', 'a.master_id', '=', 'b.id')
                ->join('subitem as c','c.id','a.sub_item')
                ->where('a.master_id',$id)
                ->select('a.*', 'b.supplier','b.pv_date','b.bill_date','c.type','b.warehouse','b.description','b.sales_tax_acc_id','b.sales_tax_amount','b.pv_no','b.sub_department_id')
                ->get();
         $cr_acc_id = $grn[0]->sub_department_id;
         $sales_tax_acc_id = $grn[0]->sales_tax_acc_id;
         $sales_tax_amount = $grn[0]->sales_tax_amount;
         $supplier = $grn[0]->supplier;
         $supplier_acc_id = CommonHelper::get_supplier_acc_id($supplier);
         $desc = $grn[0]->description;
         $pv_no = $grn[0]->pv_no;
         $pv_date = $grn[0]->pv_date;
         $exp_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id','=',$id)->where('additional_exp',1)->sum('net_amount');
         $item_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id','=',$id)->where('additional_exp',0)->sum('net_amount');


         $total_amount= 0;
            foreach($grn as $row):


                $item_amount_percent = ($row->net_amount / $item_amount) * 100;
                $exp_amount_apply = ($exp_amount /100) * $item_amount_percent;
                $status=1;
                if ($row->type==2):
                 $status = 1;
                endif;
                $stock['voucher_no']=$row->pv_no;
                $stock['main_id']=$id;
                $stock['master_id']=$row->id;
                $stock['supplier_id']=$row->supplier;
                $stock['voucher_date']=$row->pv_date;
                $stock['voucher_type']=1;
                $stock['sub_item_id']=$row->sub_item;
                $stock['qty']=$row->qty;
                $stock['rate']=$row->rate;
                $stock['amount_before_discount']=$row->amount;
                $stock['discount_percent']=0;
                $stock['discount_amount']=$row->discount_amount ;
                $stock['amount']=$row->net_amount + $exp_amount_apply;
                $stock['warehouse_id']=$row->warehouse;
                $stock['description']=$row->description;
                $stock['batch_code']=0;
                $stock['status']=$status;
                $stock['created_date']=date('Y-m-d');
                $stock['username']=Auth::user()->name;
                DB::Connection('mysql2')->table('stock')->insert($stock);
                $total_amount+=$row->net_amount;
            endforeach;

            $t_data=   DB::Connection('mysql2')->table('stock as a')
                   ->join('subitem as b','a.sub_item_id','=','b.id')
                   ->join('category as c','c.id','=','b.main_ic_id')
                  ->select('amount','sub_item_id','a.voucher_date','c.acc_id','a.supplier_id','a.voucher_no')
                  ->where('voucher_no',$row->pv_no)
                  ->whereIn('a.status',array(1,3))
                  ->get();


            foreach($t_data as $row1):

                $data4=array
                (
                    'master_id'=>$id,
                    'acc_id'=>$row1->acc_id,
                    'acc_code'=>FinanceHelper::getAccountCodeByAccId($row1->acc_id),
                    'cost_center'=>$row1->sub_item_id,
                    'particulars'=>$desc,
                    'opening_bal'=>0,
                    'debit_credit'=>1,
                    'amount'=>$row1->amount,
                    'voucher_no'=>$row1->voucher_no,
                    'voucher_type'=>4,
                    'v_date'=>$row1->voucher_date,
                    'date'=>date('Y-m-d'),
                    'action'=>'insert',
                    'username'=>Auth::user()->name,
                    'status'=>1
                );
                DB::Connection('mysql2')->table('transactions')->insertGetId($data4);
               // $total_amount+=$row1->amount;
            endforeach;
            $exp= DB::Connection('mysql2')->table('new_purchase_voucher_data as a')
                ->join('new_purchase_voucher as b', 'a.master_id', '=', 'b.id')
                ->where('a.additional_exp',1)
                ->where('a.master_id',$id)
                ->select('a.*','b.pv_no','b.pv_date')
                ->get();


                foreach($exp as $row1):

                    $data5=array
                    (
                        'master_id'=>$id,
                        'acc_id'=>$row1->category_id,
                        'acc_code'=>FinanceHelper::getAccountCodeByAccId($row1->category_id),
                        'cost_center'=>0,
                        'particulars'=>$desc,
                        'opening_bal'=>0,
                        'debit_credit'=>0,
                        'amount'=>$row1->net_amount,
                        'voucher_no'=>$row1->pv_no,
                        'voucher_type'=>4,
                        'v_date'=>$row1->pv_date,
                        'date'=>date('Y-m-d'),
                        'action'=>'insert',
                        'username'=>Auth::user()->name,
                        'status'=>1
                    );
                    DB::Connection('mysql2')->table('transactions')->insertGetId($data5);
                 //   $total_amount+=$row1->net_amount;
                endforeach;

            if ($sales_tax_acc_id!=0):
                $transaction=new Transactions();
                $transaction=$transaction->SetConnection('mysql2');
                $transaction->voucher_no=$pv_no;
                $transaction->v_date=$pv_date;
                $transaction->acc_id=$sales_tax_acc_id;
                $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($sales_tax_acc_id);
                $transaction->particulars= $desc;
                $transaction->opening_bal=0;
                $transaction->debit_credit=1;
                $transaction->amount=$sales_tax_amount;
                $transaction->username=Auth::user()->name;;
                $transaction->status=1;
                $transaction->voucher_type=4;
                $transaction->save();
                $total_amount+=$sales_tax_amount;
                endif;


                $transaction=new Transactions();
                $transaction=$transaction->SetConnection('mysql2');
                $transaction->voucher_no=$pv_no;
                $transaction->v_date=$pv_date;
                $transaction->acc_id=$supplier_acc_id ;
                $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($supplier_acc_id);
                $transaction->particulars= $desc;
                $transaction->opening_bal=0;
                $transaction->debit_credit=0;
                $transaction->amount=$total_amount;
                $transaction->username=Auth::user()->name;;
                $transaction->voucher_type=4;
                $transaction->status=1;
                $transaction->save();


                // update pv
                $purchase_voucher=new NewPurchaseVoucher();
                $purchase_voucher=$purchase_voucher->SetConnection('mysql2');
                $data = array
                (
                        'pv_status' =>2,
                        'approve_user_2'=>Auth::user()->name
                );
                $purchase_voucher=$purchase_voucher->where('id',$id)->update($data);

                echo "Approved";
            DB::Connection('mysql2')->commit();


          //  DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {


            DB::rollBack();
            print_r($goods_rece);
        }
    }

    public function approvePurchaseVoucherDetail(Request $request)
    {
        $master_id = $request->PvId;
        $grn_no = NewPurchaseVoucher::where('id',$master_id)->value('grn_no');
        if ($grn_no!=0):
            $this->direct_invoice_approve($master_id);
        return;
        endif;
        DB::Connection('mysql2')->beginTransaction();

        try {
        // Amir
            $purchase_voucher=new NewPurchaseVoucher();
            $purchase_voucher=$purchase_voucher->SetConnection('mysql2');
            $purchase_voucher=$purchase_voucher->where('id',$master_id)->first();

            $pv_no = $purchase_voucher->pv_no;
            $purchase_date = $purchase_voucher->pv_date;
            $desc = $purchase_voucher->description;
            $sales_tax_acc_id = $purchase_voucher->sales_tax_acc_id;
            $sales_tax_amount = $purchase_voucher->sales_tax_amount;
            $supplier = $purchase_voucher->supplier;
            $supp_acc_id = CommonHelper::get_supplier_acc_id($supplier);
            $grn_data=  DB::Connection('mysql2')->table('goods_receipt_note')->where('id',$purchase_voucher->grn_id);
            $po_no= $grn_data->value('po_no');
            $dept_id =  $grn_data->value('sub_department_id');
            $p_type=  $grn_data->value('p_type');


            $credit_amount=0;

            if ($purchase_voucher->approved_user!=''):
                $status=2;
                // $data = DB::Connection('mysql2')->selectRaw('select net_amount,category_id ,sub_item from new_purchase_voucher_data
                // where master_id="'.$master_id.'" and additional_exp=0 ');
                $data = DB::connection('mysql2')->table('new_purchase_voucher_data')->where([['master_id',$master_id],['additional_exp', 0 ]])->get();


                foreach ($data as $key => $value) {
                    # code...


                    // echo "<pre>";
                    // print_r($data);

                    $transaction=new Transactions();
                    $transaction=$transaction->SetConnection('mysql2');
                    $transaction->master_id=$master_id;
                    $transaction->voucher_no=$value->pv_no;
                    $transaction->v_date=$purchase_date;
                    $transaction->acc_id=$value->sub_item;
                    $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($value->sub_item);
                    $transaction->particulars= $desc;
                    $transaction->opening_bal=0;
                    $transaction->debit_credit=1;
                    $transaction->amount=$value->net_amount;
                    $transaction->username=Auth::user()->name;;
                    $transaction->status=1;
                    $transaction->voucher_type=4;
                    $transaction->save();

                    $credit_amount+=$value->net_amount;

                }

                if ($sales_tax_acc_id!=0):
                    $transaction=new Transactions();
                    $transaction=$transaction->SetConnection('mysql2');
                    $transaction->voucher_no=$pv_no;
                    $transaction->v_date=$purchase_date;
                    $transaction->acc_id=$sales_tax_acc_id;
                    $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($sales_tax_acc_id);
                    $transaction->particulars= $desc;
                    $transaction->opening_bal=0;
                    $transaction->debit_credit=1;
                    $transaction->amount=$sales_tax_amount;
                    $transaction->username=Auth::user()->name;;
                    $transaction->status=1;
                    $transaction->voucher_type=4;
                    $transaction->save();
                    $credit_amount+=$sales_tax_amount;
                endif;

                $data1= DB::Connection('mysql2')->select('select net_amount,category_id from new_purchase_voucher_data
                    where master_id="'.$master_id.'" and additional_exp=1');



                foreach($data1 as $exp ):

                    $transaction=new Transactions();
                    $transaction=$transaction->SetConnection('mysql2');
                    $transaction->voucher_no=$pv_no;
                    $transaction->v_date=$purchase_date;
                    $transaction->acc_id=$exp->category_id;
                    $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($exp->category_id);
                    $transaction->particulars= $desc;
                    $transaction->opening_bal=0;
                    $transaction->debit_credit=1;
                    $transaction->amount=$exp->net_amount;
                    $transaction->username=Auth::user()->name;;
                    $transaction->status=1;
                    $transaction->voucher_type=4;
                    $transaction->save();
                    $credit_amount+=$exp->net_amount;
                endforeach;



                $transaction=new Transactions();
                $transaction=$transaction->SetConnection('mysql2');
                $transaction->voucher_no=$pv_no;
                $transaction->v_date=$purchase_date;
                $transaction->acc_id=$supp_acc_id;
                $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($supp_acc_id);
                $transaction->particulars= $desc;
                $transaction->opening_bal=0;
                $transaction->debit_credit=0;
                $transaction->amount=$credit_amount;
                $transaction->username=Auth::user()->name;;
                $transaction->voucher_type=4;
                $transaction->status=1;
                $transaction->save();


                $purchase_voucher=new NewPurchaseVoucher();
                $purchase_voucher=$purchase_voucher->SetConnection('mysql2');

                $data = array
                (
                        'pv_status' =>$status,
                        'approve_user_2'=>Auth::user()->name
                );
                $purchase_voucher=$purchase_voucher->where('id',$master_id)->update($data);
                $pr_no = DB::Connection('mysql2')->table('purchase_request_data')->where('status',1)->where('purchase_request_no',$po_no)->value('demand_no');
                $voucher_no = $pv_no;
                $subject = 'Purchase Invoice Approved 2 For '.$pr_no;
                NotificationHelper::send_email('Purchase Invoice','Approve 2',$dept_id,$voucher_no,$subject,$p_type);
            else:
                $status=3;

                $purchase_voucher=new NewPurchaseVoucher();
                $purchase_voucher=$purchase_voucher->SetConnection('mysql2');

                $data = array
                (
                        'pv_status' =>$status,
                        'approved_user'=>Auth::user()->name
                );
                $purchase_voucher=$purchase_voucher->where('id',$master_id)->update($data);

                $pr_no = DB::Connection('mysql2')->table('purchase_request_data')->where('status',1)->where('purchase_request_no',$po_no)->value('demand_no');
                $voucher_no = $pv_no;
                $subject = 'Purchase Invoice Approved 1 For '. $pr_no;
                NotificationHelper::send_email('Purchase Invoice','Approve 1',$dept_id,$voucher_no,$subject,$p_type);

            endif;


                DB::Connection('mysql2')->commit();


        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
    }


	public function filterCashReceiptVoucherList(){
        return view('Finance.AjaxPages.filterCashReceiptVoucherList');
	}

	public function filterBankReceiptVoucherList(){
        return view('Finance.AjaxPages.filterBankReceiptVoucherList');
	}
    public function filterContraVoucherList(){
        return view('Finance.AjaxPages.filterContraVoucherList');
    }
	public function loadFilterLedgerReport(){
        return view('Finance.AjaxPages.loadFilterLedgerReport');
    }
    public function paidToExpenseReport(){
        return view('Finance.AjaxPages.paidToExpenseReport');
    }

    public function AuditTrialReport(){
        return view('Finance.AjaxPages.AuditTrialReport');
    }

    public function filterPurchaseCashPaymentVoucherList(){
        return view('Finance.AjaxPages.filterPurchaseCashPaymentVoucherList');
    }

    public function filterPurchaseBankPaymentVoucherList(){
        return view('Finance.AjaxPages.filterPurchaseBankPaymentVoucherList');
    }

    public function filterSaleCashReceiptVoucherList(){
        return view('Finance.AjaxPages.filterSaleCashReceiptVoucherList');
    }

    public function filterSaleBankReceiptVoucherList(){
        return view('Finance.AjaxPages.filterSaleBankReceiptVoucherList');
    }

    public function filterPurchaseJournalVoucherList(){
        return view('Finance.AjaxPages.filterPurchaseJournalVoucherList');
    }

    public function filterSaleJournalVoucherList(){
        return view('Finance.AjaxPages.filterSaleJournalVoucherList');
    }

    public function addChartOfAccount(){

        FinanceHelper::companyDatabaseConnection($_GET['m']);
        $parent_code = Input::get('account_id');
        $acc_name = Input::get('acc_name');
        $o_blnc = Input::get('o_blnc');
        $o_blnc_trans = Input::get('o_blnc_trans');
        $operational = Input::get('operational');
        $PageName = Input::get('PageName');
        $sent_code = $parent_code;


        $max_id = DB::selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \''.$parent_code.'\'')->id;
        if($max_id == '')
        {
            $code = $sent_code.'-1';
        }
        else
        {
            $max_code2 = DB::selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \''.$max_id.'\'')->code;
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
        $data2['v_date']     		= date("Y-m-d");
        $data2['time']     		  = date("H:i:s");
        $data2['action']     		  = 'create';
        DB::table('transactions')->insert($data2);

        FinanceHelper::reconnectMasterDatabase();
        if($PageName == "jvs")
        {
            echo $PageName.','.$acc_id.','.ucwords($acc_name).','.Input::get('id');
        }
        else
        {
            echo ucwords($acc_name).','.$acc_id.','.Input::get('id');
        }

       // return Redirect::to('finance/viewChartofAccountList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    public function editChartOfAccountForm($id)
    {

        $accounts=new Account();
        $accounts=$accounts->SetConnection('mysql2');
        $accounts_data=$accounts->where('status',1)->where('id',$id)->select('id','name','parent_code','id','operational')->first();
        $accounts = $accounts->where('status',1)->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        return view('Finance.AjaxPages.editChartOfAccountForm',compact('accounts_data','accounts'));
    }
    public function editCostCenterForm($id)
    {

        $cost_center=new CostCenter();
        $cost_center=$cost_center->SetConnection('mysql2');
        $cost_center_data=$cost_center->where('status',1)->where('id',$id)->select('id','name','parent_code','first_level','code','operational')->first();
        $cost_center = $cost_center->where('status',1)->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')

            ->get();
        return view('Finance.AjaxPages.editCostCenterForm',compact('cost_center','cost_center_data'));
    }

    function tax_calculation(Request $request)
    {
         $nature= $request->nature;
         $filer=$request->filer;
        $business=$request->business;
         $count=$request->number;
         $supplier_id=$request->supplier_id;
         $pv_data_counter=$request->pv_data_cocunter;
         if ($business==1 || $business==3):
            $business_type=2;
         else:
            $business_type=1;
            endif;

        $percent=DB::Connection('mysql2')->table('income_tax_slab')->where('tex_nature',$nature)->where('business_type',$business_type)->where('filer_status',$filer)->first();

         $percent->percent.','.$count.','.$percent->acc_id.','.$percent->id;
            $j=$count;

        $acc_id=$request->acc_id;
      $acc_name=   CommonHelper::get_account_name($acc_id);
       $income=$request->income;

        $cal_amount=($percent->percent / 100)*$income;
        $cal_amount=round($cal_amount);
        ?>
        <tr id="taxtation_row<?php echo $count ?>" class="taxtation_row<?php echo $count ?>">


        <td>
         <select    class="form-control requiredField select2 <?php echo $income ?>" name="account_id[]" id="account_id_1_<?php echo $pv_data_counter ?>">
         <option value="<?php echo $acc_id.',1,'.$nature.','.$acc_id.','.$income.','.$cal_amount.','.$percent->id.','.$supplier_id; ?>"><?php echo  $acc_name; ?></option>
        </select>
        </td>

        <td>
            <?php $MultiData = CommonHelper::getEmpSuppClientPaidTo();?>
            <select class="form-control select2" name="paid_to[]" id="paid_to_1_<?php echo $pv_data_counter?>" tabindex="-1">
                <option value=''>Select Paid To</option>
                <?php foreach($MultiData['Emp'] as $EmpFil):?>
                <option value='<?php echo $EmpFil->id.","."1";?>'><?php echo $EmpFil->emp_name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['Supp'] as $SuppFil):?>
                <option value='<?php echo $SuppFil->id.","."2";?>'><?php echo $SuppFil->name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['Client'] as $ClientFil):?>
                <option value='<?php echo $ClientFil->id.","."3";?>'><?php echo $ClientFil->client_name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['PaidTo'] as $PaidToFil):?>
                <option value='<?php echo $PaidToFil->id.","."4";?>'><?php echo $PaidToFil->name?></option>
                <?php endforeach;?>

            </select>
        </td>
        <td>
            <textarea class="form-control requiredField" name="desc[]" id="desc_1_<?php echo $pv_data_counter?>" required="required"/></textarea>
        </td>

       <td>
       <input onfocus="mainDisable('c_amount_1_<?php echo $pv_data_counter ?>','d_amount_1_<?php echo $pv_data_counter ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField" maxlength="15" min="0" type="any" name="d_amount[]" id="d_amount_1_<?php echo $pv_data_counter ?>" onkeyup="sum('1')" value="" required="required"/>
        </td>


        <td>
         <input onfocus="mainDisable('d_amount_1_<?php echo $pv_data_counter ?>','c_amount_1_<?php echo $pv_data_counter ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField" maxlength="15" min="0" type="any" name="c_amount[]" id="c_amount_1_<?php echo $pv_data_counter ?>" onkeyup="sum('1')" value="<?php echo $cal_amount ?>" required="required"/>
         </td>
         <td  style="color: red" class="text-center">
       <p class="text<?php echo $j ?>" id="text<?php echo $j ?>">  <?php echo $percent->percent.' % Tax Applied' ?></p>
         <button type='button' class='btn btn-sm btn-danger' id='BtnRemove' onclick='remove("taxtation_row<?php echo $count ?>")'>Remove</button></td>
         </td>
          </tr>
          <script !src="">
          var Num = '<?php echo $pv_data_counter?>';
          $('#paid_to_1_'+Num).select2();
</script>
        <?php

    }


    function fbr_tax_calculation(Request $request)
    {


         $registerd= $request->registerd;
         $active_status=$request->actice_status;
         $advertisment=$request->advertisment;
         $pv_data_counter=$request->pv_data_counter;
         $acc_id=$request->fbr_account;
         $acc_name=   CommonHelper::get_account_name($acc_id);
         $fbr_amount=$request->fbr_amount;



            $percent=DB::Connection('mysql2')->table('sindh_sales_tax_withholding')->where('register_in_sales_tax',$registerd)
                ->where('active_in_sales_tax',$active_status)
                ->where('advertisment_services',$advertisment)->select('deduction_txt','amount_deduction','tax_applicable','acc_id','id');
        if ($percent->count() >0 ):

             $percent->first()->deduction_txt.','.$percent->first()->amount_deduction.','.$percent->first()->tax_applicable.','.$percent->first()->acc_id;
             $cal_amount=($percent->first()->tax_applicable/100)*$fbr_amount;
             $cal_amount=round($cal_amount);
            ?>
             <tr id="fbr_row" class="fbr_row">
        <td>
         <select    class="form-control requiredField select2" name="account_id[]" id="account_id_1_<?php echo $pv_data_counter ?>">
         <option value="<?php echo $acc_id.',2,'.$percent->first()->id.','.$fbr_amount.','.$cal_amount ?>"><?php echo  $acc_name; ?></option>
        </select>
        </td>
        <td>
            <?php $MultiData = CommonHelper::getEmpSuppClientPaidTo();?>
            <select class="form-control select2" name="paid_to[]" id="paid_to_1_<?php echo $pv_data_counter?>">
                <option value=''>Select Paid To</option>
                <?php foreach($MultiData['Emp'] as $EmpFil):?>
                <option value='<?php echo $EmpFil->id.","."1";?>'><?php echo $EmpFil->emp_name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['Supp'] as $SuppFil):?>
                <option value='<?php echo $SuppFil->id.","."2";?>'><?php echo $SuppFil->name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['Client'] as $ClientFil):?>
                <option value='<?php echo $ClientFil->id.","."3";?>'><?php echo $ClientFil->client_name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['PaidTo'] as $PaidToFil):?>
                <option value='<?php echo $PaidToFil->id.","."4";?>'><?php echo $PaidToFil->name?></option>
                <?php endforeach;?>

            </select>
        </td>
        <td>
            <textarea class="form-control requiredField" name="desc[]" id="desc_1_<?php echo $pv_data_counter?>" required="required"/></textarea>
        </td>
       <td>
       <input onfocus="mainDisable('c_amount_1_<?php echo $pv_data_counter ?>','d_amount_1_<?php echo $pv_data_counter ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField" maxlength="15" min="0" type="any" name="d_amount[]" id="d_amount_1_<?php echo $pv_data_counter ?>" onkeyup="sum('1')" value="" required="required"/>
        </td>


        <td>
         <input onfocus="mainDisable('d_amount_1_<?php echo $pv_data_counter ?>','c_amount_1_<?php echo $pv_data_counter ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField" maxlength="15" min="0" type="any" name="c_amount[]" id="c_amount_1_<?php echo $pv_data_counter ?>" onkeyup="sum('1')" value="<?php echo $cal_amount ?>" required="required"/>
         </td>
         <td  style="color: red" class="text-center">
       <p class="fbr_txt" id="">  <?php echo $percent->first()->deduction_txt.' % Tax Applied' ?></p>
         <button type='button' class='btn btn-sm btn-danger' id='BtnRemove' onclick='remove("fbr_row")'>Remove</button></td>


          </tr>
          <script !src="">$('.select2').select2();</script>
          <?php
        else:
            echo '0';
            endif;

    }


    function pra_tax_calculation(Request $request)
    {


        $registerd= $request->registerd;

        $company=$request->company;
        $active=$request->active_pra;
        $advertisment=$request->advertisment;

         $pv_data_counter=$request->pv_data_counter;
         $acc_id=$request->acc_id;
         $acc_name=   CommonHelper::get_account_name($acc_id);
         $pra_amount=$request->pra_amount;
         $applicable_rate=$request->applicable_rate;

        //    $percent=DB::Connection('mysql2')->selectOne('select deduction_txt from sindh_sales_tax_withholding
        //  where 	register_in_sales_tax="'.$registerd.'" and active_in_sales_tax="'.$active_status.'" and advertisment_services="'.$advertisment.'"')->deduction_txt;

        $percent=DB::Connection('mysql2')->table('pra')
            ->where('register_in_punjab_sales_tax',$registerd)
            ->where('advertisment_services',$advertisment)
            ->where('company',$company)
            ->where('active_in_punjab_sales_tax',$active)
            ->select('deduction_txt','applicable','amount_deduction','tax_applicable','account_id','id');
        if ($percent->count() >0 ):

           $percent->first()->deduction_txt.','.$percent->first()->applicable.','.$percent->first()->amount_deduction.','.$percent->first()->tax_applicable.','.$percent->first()->account_id.','.$percent->first()->id;


                if ($percent->first()->applicable==0):
            $deduction=$percent->first()->deduction_txt.' '.$percent->first()->tax_applicable;
            $cal_amount=($percent->first()->tax_applicable /100)*$pra_amount;
            $percentage=$percent->first()->tax_applicable;
            else:
             $cal_amount=($applicable_rate /100)*$pra_amount;
            $deduction='Withholding From Bill Amount "'.$applicable_rate.'"';
            $percentage=$applicable_rate;
            endif;
            $cal_amount=round($cal_amount);
                ?>

                 <tr id="pra_row" class="pra_row">
        <td>
         <select    class="form-control requiredField select2" name="account_id[]" id="account_id_1_<?php echo $pv_data_counter ?>">
         <option value="<?php echo $acc_id.',4,'.$percent->first()->id.','.$pra_amount.','.$cal_amount.','.$percentage ?>"><?php echo  $acc_name; ?></option>
        </select>
        </td>
        <td>
            <?php $MultiData = CommonHelper::getEmpSuppClientPaidTo();?>
            <select class="form-control select2" name="paid_to[]" id="paid_to_1_<?php echo $pv_data_counter?>" tabindex="-1">
                <option value=''>Select Paid To</option>
                <?php foreach($MultiData['Emp'] as $EmpFil):?>
                <option value='<?php echo $EmpFil->id.","."1";?>'><?php echo $EmpFil->emp_name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['Supp'] as $SuppFil):?>
                <option value='<?php echo $SuppFil->id.","."2";?>'><?php echo $SuppFil->name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['Client'] as $ClientFil):?>
                <option value='<?php echo $ClientFil->id.","."3";?>'><?php echo $ClientFil->client_name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['PaidTo'] as $PaidToFil):?>
                <option value='<?php echo $PaidToFil->id.","."4";?>'><?php echo $PaidToFil->name?></option>
                <?php endforeach;?>

            </select>
        </td>
        <td>
            <textarea class="form-control requiredField" name="desc[]" id="desc_1_<?php echo $pv_data_counter?>" required="required"/></textarea>
        </td>
       <td>
       <input onfocus="mainDisable('c_amount_1_<?php echo $pv_data_counter ?>','d_amount_1_<?php echo $pv_data_counter ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField" maxlength="15" min="0" type="any" name="d_amount[]" id="d_amount_1_<?php echo $pv_data_counter ?>" onkeyup="sum('1')" value="" required="required"/>
        </td>


        <td>
         <input onfocus="mainDisable('d_amount_1_<?php echo $pv_data_counter ?>','c_amount_1_<?php echo $pv_data_counter ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField" maxlength="15" min="0" type="any" name="c_amount[]" id="c_amount_1_<?php echo $pv_data_counter ?>" onkeyup="sum('1')" value="<?php echo $cal_amount ?>" required="required"/>
         </td>
         <td  style="color: red" class="text-center">
       <p class="pra_txt" id="">  <?php echo $deduction.' % Tax Applied' ?></p>
         <button type='button' class='btn btn-sm btn-danger' id='BtnRemove' onclick='remove("srb_row")'>Remove</button></td>


          </tr>

        <script !src="">$('.select2').select2();</script>

                <?php
        else:
            echo 'Something Went Wrong';
        endif;

    }

    function srb_tax_calculation(Request $request)
    {


        $registerd= $request->registerd;
        $advertisment=$request->advertisment;
        $exclusion=$request->exclusion;
        $applicable_rate=$request->applicable_rate;

         $pv_data_counter=$request->pv_data_counter;
         $acc_id=$request->acc_id;
         $acc_name=   CommonHelper::get_account_name($acc_id);
         $srb_amount=$request->srb_amount;
         $exclusion_val=$request->exclusion_val;
        //    $percent=DB::Connection('mysql2')->selectOne('select deduction_txt from sindh_sales_tax_withholding
        //  where 	register_in_sales_tax="'.$registerd.'" and active_in_sales_tax="'.$active_status.'" and advertisment_services="'.$advertisment.'"')->deduction_txt;

        $percent=DB::Connection('mysql2')->table('srb')
            ->where('register',$registerd)
            ->where('advertisment',$advertisment)
            ->where('exclusion',$exclusion)
            ->where('applicable',$applicable_rate);
        if ($percent->count() >0 ):

             $percent->first()->deduction_in_words.','.$percent->first()->applicable.','.$percent->first()->amount_nature.','.$percent->first()->applicable_percent
            .','.$percent->first()->back_amount_calc.','.$percent->first()->acc_id.','.$percent->first()->id;
            if ($percent->first()->applicable==0):

            $percentage=$percent->first()->applicable_percent;
            $deduction=$percent->first()->deduction_in_words;
            $cal_amount=($percent->first()->applicable_percent /100)*$srb_amount;
            else:
            $percentage=$applicable_rate;
             $cal_amount=($applicable_rate /100)*$srb_amount;
            $deduction='Withholding From Bill Amount "'.$applicable_rate.'"';
            endif;
            $cal_amount=round($cal_amount);
            ?>
             <tr id="srb_row" class="srb_row">
        <td>
         <select    class="form-control requiredField select2" name="account_id[]" id="account_id_1_<?php echo $pv_data_counter ?>">
         <option value="<?php echo $acc_id.',3,'.$percent->first()->id.','.$srb_amount.','.$cal_amount.','.$percentage.','.$exclusion_val ?>"><?php echo  $acc_name; ?></option>
        </select>
        </td>
        <td>
            <?php $MultiData = CommonHelper::getEmpSuppClientPaidTo();?>
            <select class="form-control select2" name="paid_to[]" id="paid_to_1_<?php echo $pv_data_counter?>" tabindex="-1">
                <option value=''>Select Paid To</option>
                <?php foreach($MultiData['Emp'] as $EmpFil):?>
                <option value='<?php echo $EmpFil->id.","."1";?>'><?php echo $EmpFil->emp_name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['Supp'] as $SuppFil):?>
                <option value='<?php echo $SuppFil->id.","."2";?>'><?php echo $SuppFil->name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['Client'] as $ClientFil):?>
                <option value='<?php echo $ClientFil->id.","."3";?>'><?php echo $ClientFil->client_name?></option>
                <?php endforeach;?>
                <?php foreach($MultiData['PaidTo'] as $PaidToFil):?>
                <option value='<?php echo $PaidToFil->id.","."4";?>'><?php echo $PaidToFil->name?></option>
                <?php endforeach;?>

            </select>
        </td>
        <td>
            <textarea class="form-control requiredField" name="desc[]" id="desc_1_<?php echo $pv_data_counter?>" required="required"/></textarea>
        </td>

       <td>
       <input onfocus="mainDisable('c_amount_1_<?php echo $pv_data_counter ?>','d_amount_1_<?php echo $pv_data_counter ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField" maxlength="15" min="0" type="any" name="d_amount[]" id="d_amount_1_<?php echo $pv_data_counter ?>" onkeyup="sum('1')" value="" required="required"/>
        </td>


        <td>
         <input onfocus="mainDisable('d_amount_1_<?php echo $pv_data_counter ?>','c_amount_1_<?php echo $pv_data_counter ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField" maxlength="15" min="0" type="any" name="c_amount[]" id="c_amount_1_<?php echo $pv_data_counter ?>" onkeyup="sum('1')" value="<?php echo $cal_amount ?>" required="required"/>
         </td>
         <td  style="color: red" class="text-center">
       <p class="srb_txt" id="">  <?php echo $deduction.' % Tax Applied' ?></p>
         <button type='button' class='btn btn-sm btn-danger' id='BtnRemove' onclick='remove("srb_row")'>Remove</button></td>


          </tr>
          <script !src="">$('.select2').select2();</script>
            <?php

        else:
            echo '00';
        endif;

    }


    //Show Pages All Taxes//

    function showIncomeTaxWithholding()
    {
        return view('Finance.AjaxPages.income_tax_withHolding');
    }

    function showFbrSalesTaxWithholding()
    {
        return view('Finance.AjaxPages.fbr_sales_tax_withholding');
    }
    function showSrbSindhRevenue()
    {
        return view('Finance.AjaxPages.srb_sindh_revenue_board');
    }
    function showPunjabSalesTaxWithholding()
    {
        return view('Finance.AjaxPages.punjab_sales_tax_withholding');
    }
    function showTaxesData(Request $request)
    {
         $id=$request->id;
        return view('Finance.AjaxPages.show_taxes_data',compact('id'));
    }

    function ShowDetailData(Request $request)
    {
         $pv_no = $request->pv_no;
        return view('Finance.AjaxPages.ShowDetailData',compact('pv_no'));
    }



    function income_tax_calculation(Request $request)
    {

    ?>

    <?php
        $id=$request->supplier_id;
        $register_no='';
        $supplier_info=new Supplier();
        $supplier_info=$supplier_info->SetConnection('mysql2');
        $supplier_info=$supplier_info->where('id',$id)->select('resgister_income_tax','business_type','cnic','ntn','filer','strn','register_sales_tax','register_pra','pra','srb','register_srb')->first();

        if ($supplier_info->resgister_income_tax==1):
            if ($supplier_info->business_type==1):

                if ($supplier_info->cnic!='0' && $supplier_info->cnic!='-'):
                    $register_no=$supplier_info->cnic;
                else:
                    if ($supplier_info->ntn!=0):
                        $register_no=$supplier_info->ntn;
                    else:
                        $register_no='';
                    endif;
                endif;
                ?>



                <?php
            else:
                $register_no=$supplier_info->ntn;
            endif;
            ?>



            <div class="row">

                <div  class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">
                    <label class="radio-inline">
                        <input  <?php if ($supplier_info->filer==2): ?> checked <?php endif; ?>   type="radio" name="filer_nonfiler" id="filer3" value="1">Filer
                    </label>


                    <label class="radio-inline">
                        <input <?php if ($supplier_info->filer==1): ?> checked <?php endif; ?>    type="radio" name="filer_nonfiler" id="filer4" value="2">Non Filer
                    </label>

                </div>


                <div  class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                    <input type="hidden" name="business_type" id="business_type" value="<?php echo $supplier_info->business_type ?>"/>
                    <input type="hidden" name="srb" id="srb" value="<?php echo $supplier_info->srb ?>"/>
                    <input type="hidden" name="register_srb" id="register_srb" value="<?php echo $supplier_info->register_srb ?>"/>

                </div>
            </div>

    </br>
            <div id=""  class="row taxtation_row1">
                <div id="payment_mod_div1" style="display: block"  class="col-lg-3 col-md-3 col-sm-3 col-xs-12 payment_mod_div">

                    <select  style="width: 100%" onchange=""  id="payment_mod1" name="nature1"  class="form-control select2">
                        <option  value="0" style="color: red">SELECT</option>

                        <option value="1">ALL GOODS</option>
                        <option value="2">IN CASE OF RICE,COTTON,SEED,EDIBLE OIL</option>
                        <option value="3">DISTRIBUTORS OF FAST MOVING CONSUMER GOODS</option>
                        <option value="4">SERVICES</option>
                        <option value="5">TRANSPORT SERVICES</option>
                        <option value="6">ELECTRONIC AND PRINT MEDIA FOR ADVERTISING</option>
                        <option value="7">CONTRACTS</option>
                        <option value="8">SPORT PERSON</option>
                        <option value="9">Services of Stitching , Dyeing , Printing , Embroidery etc</option>


                    </select>
                    <input type="hidden" class="nature1" value=""/>

                </div>
                <?php
                $Accounts=new Account();
                $Accounts = $Accounts->SetConnection('mysql2');
                $Accounts = $Accounts->where('status',1)->where('parent_code','1-2-4-1')->get();
                ?>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 payment_mod_div">
                    <select name="tax_payment_section1" id="tax_payment_section1" class="form-control select2">
                        <option value="">Select Tax Payment Section</option>
                        <?php foreach($Accounts as $Filter):?>
                        <option value="<?php echo $Filter['id']?>"><?php echo $Filter['code'].'------'.$Filter['name']?></option>
                        <?php endforeach;?>

                    </select>
                </div>

                <div id="" style="display: block"  class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">
                    <input type="text" class="form-control income1" value="" name="income1" id="income1">
                </div>

                <div style=""  class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">

                    <p style="color: red" id="percent_cal1" class=""> </p>
                </div>
                <input type="hidden" name="income_tax_id1" id="income_tax_id1" value="">

                <div style="display:block;" id="submit" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">

                    <input  id="btn_cal1" type="button" onclick="calculation_text(1)" class="btn-primary" value="Calculate"/>
                    <input style="display: block; float: right" onclick="add_more()" type="button" class="btn-success add_field_button payment_mod_div" value="Add More"/>

                </div>
            </div>

            <div class="append"></div>
            <?php
        else:
            echo   $register_no="No Data Found";

        endif;

        //   return $register_no.','.$supplier_info->filer.','.$supplier_info->business_type.','.$supplier_info->register_sales_tax.','.$supplier_info->strn.','.$supplier_info->register_pra
        //  .','.$supplier_info->pra;


    }

    public function trialBalanceData()
    {
        ?>
<style>


</style>
            <?php
    $m=$_GET['m'];


   $CompanyId=Input::get('m');
    $from=Input::get('from');
    $to=Input::get('to');
    $nature=Input::get('nature').'%';
        $total_opening_debit = 0;
        $total_opening_credit = 0;
        $end_debit_total = 0;
        $end_credit_total = 0;
        $tx_trial_debit = 0;
        $tx_trial_credit = 0;

if ($nature!='%'):

    $clause="and code like '".$nature."'";
else:

    $clause="";
endif;
$newdate = strtotime('-1 day', strtotime($from));
$newdate = date('Y-m-d', $newdate);
//$acc_year_from = $this->session->userdata('accyearfrom');
        $acc_year_from = '2019-07-01';
?>



 <h5 style="text-align: center">
    <?php 	echo 'FROM <b>'.date_format(date_create($from),'F d, Y').'</b> TO <b>'.date_format(date_create($to),'F d, Y').'</b>'; ?>
 </h5>
        <form action="<?php echo url('/'); ?>/fad/CustomiseTrialbal?m=<?php echo $_GET['m']; ?>" method="post" id='formsubmit'>
        <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
<table id="header-fixed1" class="table table-bordered sf-table-th sf-table-list" style="background:#FFF;">
<thead class="">
    <th colspan="9" class="text-center">
    <h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?>

    </h3>
    </th>
</thead>
<thead class="">
    <th colspan="9" class="text-center">
        <h3 style="text-align: center;">Trial Balance 6th Column</h3>
    </th>
</thead>
<thead class="">
    <th colspan="9">
        <h3 style="text-align: center">
    <?php 	echo 'FROM <b>'.date_format(date_create($from),'F d, Y').'</b> TO <b>'.date_format(date_create($to),'F d, Y').'</b>'; ?>
</h3>
    </th>
</thead>
<thead>
    <th colspan="9" class="text-center">
        <p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p>
    </th>
</thead>
    <thead>
        <th colspan="3" class="text-center"></th>
        <th colspan="2" class="text-center">Opening Balance</th>
        <th colspan="2" class="text-center">Transactions</th>
        <th colspan="2" class="text-center">Closing Balance</th>
    </thead>
    <thead  class="fix" style="display: table-header-group">
        <th  class="text-center">Sr.No</th>
        <th  class="text-center col-sm-1">ACC.CODE</th>
        <th  class="text-center col-sm-5">ACCOUNT</th>
        <th  class="text-center col-sm-1">OPEN.DR</th>
        <th  class="text-center col-sm-1">OPEN.CR</th>
        <th  class="text-center col-sm-1">TX.DR</th>
        <th  class="text-center col-sm-1">TX.CR</th>
        <th  class="text-center col-sm-1">CL.DR</th>
        <th  class="text-center col-sm-1">CL.CR</th>
    </thead>
    <tbody>
    <?php
    CommonHelper::companyDatabaseConnection($CompanyId);
    $trial=  DB::select('select a.* from accounts a
               inner join
               transactions b
               on
               a.id=b.acc_id
               where a.status=1
               and b.amount>0
			 '.$clause.' group by a.id order by a.level1,a.level2,a.level3,a.level4,a.level5,a.level6,a.level7');//->result_array();
    //$trial=  DB::select('select * from accounts where status="1"
	//		 '.$clause.' order by level1,level2,level3,level4,level5,level6,level7');//->result_array();
    $Counter=1;
      $paramOne = "fdc/getSummaryLedgerDetail?m=".$m;
    foreach($trial as $row):

    $array = explode('-',$row->code);

    $level = count($array);







		 $tr_debit=0;
        $tx_credit=0;
        $tr_debit=DB::selectOne('select sum(amount)amount from transactions where acc_id="'.$row->id.'" and status=1 and opening_bal=0
					  and debit_credit=1
	                  and v_date between "'.$from.'" and "'.$to.'" and status=1');

        $tr_credit=DB::selectOne('select sum(amount)amount from transactions where acc_id="'.$row->id.'" and status=1 and opening_bal=0
	            and debit_credit=0
             	and v_date between "'.$from.'" and "'.$to.'" and status=1');

 $total_check=0.1;

 if ($total_check!=0):

    ?>
    <tr id="tr<?php echo $Counter ?>" class="<?php if($level == 1){echo 'smr-purple';}
    elseif($level == 2){echo 'smr-pink';}
    elseif($level == 3){echo 'smr-orange';}
    elseif($level == 4){echo 'smr-yellow';}
    elseif($level == 5){echo 'smr-lightgreen';}
    elseif($level == 6){echo 'smr-green';}
    elseif($level == 7){echo 'smr-lightblue';}

    ?>">


        <td class="text-center">
        <?php echo $Counter;?>
        <input type="hidden" name="acc_id[]" value="<?php echo $row->id; ?>">
        </td>
        <td class="text-left"><?php echo $row->code; ?></td>
        <td class="sf-uc-first text-left">


            <?php if($level ==1){ ?>	<div style="cursor: pointer" class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')"    ><?php echo  $row->name;}
                elseif($level ==2){?>	<div style="cursor: pointer"  class="link_hide"  onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')"  ><?php echo '<span class="SpacesCls">&emsp;</span>'.$row->name;}
                    elseif($level ==3){?>	<div style="cursor: pointer"  class="link_hide"  onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')"  ><?php echo '<span class="SpacesCls">&emsp;&emsp;</span>'.$row->name;}
                        elseif($level ==4){?>	<div style="cursor: pointer"  class="link_hide"  onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')"  ><?php echo '<span class="SpacesCls">&emsp;&emsp;&emsp;</span>'.$row->name;}
                            elseif($level ==5){?>	<div style="cursor: pointer"  class="link_hide"  onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')" ><?php echo '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;</span>'.$row->name;}
                                elseif($level ==6){?>	<div style="cursor: pointer"  class="link_hide"  onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')" ><?php echo '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;</span>'.$row->name;}
                                    elseif($level ==7){?>	<div style="cursor: pointer"  class="link_hide" onclick="newTabOpen('<?php echo $from?>','<?php echo $to?>','<?php echo $row->code?>')" ><?php echo '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'.$row->name;}
                                        ?>
        </td>


        <?php


        $open=0;

        $open_data = DB::selectOne('select amount,debit_credit  from transactions where acc_code="'.$row->code.'" and status=1 and opening_bal=1
				and status=1');
        if (!empty($open_data)):
        if ($open_data->debit_credit==1):
            $open=$open_data->amount;

        else:
            $open=$open_data->amount*-1;
        endif;
    endif;


$open_debit=DB::selectOne('select sum(amount)amount from transactions where acc_code="'.$row->code.'" and status=1 and opening_bal=0
				and status=1 and v_date between "'.$acc_year_from.'" and "'.$newdate.'" and debit_credit=1')->amount;


        $open_credit=DB::selectOne('select sum(amount)amount from transactions where acc_code="'.$row->code.'" and status=1 and opening_bal=0
				and status=1 and v_date between "'.$acc_year_from.'" and "'.$newdate.'" and debit_credit=0')->amount;




        $total=$open_debit-$open_credit;


        $open= $open+$total;



        ?>
        <td class="text-right">
            <?php


            $credit=0;
            $debit=0;


            if ($open>0):

                //	$open_debit_trial+=$open->amount;


                $debit=$open;
                $credit=0;
                echo number_format($open,2);
                $total_opening_debit += $open;
                ?>


                <?php



            endif; ?>

        </td>
        <td class="text-right"><?php if ($open<0):


                $credit=$open*-1;
                $debit=0;
                //	$open_credit_trial+=$open->amount;

                echo number_format($open*-1,2);

                $total_opening_credit+=$open;
                ?>


            <?php endif; ?></td>


        <?php



        ?>


        <td class="text-right">

            <?php

            $tx_trial_debit+=$tr_debit->amount;
            $tx_debit=$tr_debit->amount;

            echo number_format($tx_debit,2);
            ?>



        </td>
        <td class="text-right">
            <?php

            $tx_trial_credit+=$tr_credit->amount;
            $tx_credit=$tr_credit->amount;;
            echo number_format($tx_credit,2);



            ?>


        </td>


        <?php 	 $end_result=$tr_debit->amount+$debit-$tr_credit->amount-$credit;


        if ($tr_debit)

        ?>
        <td class="text-right">

            <?php

            if ($end_result>0):

                echo number_format($end_result,2);
                $end_debit_total+=$end_result;
                ?>


                <?php



            endif; ?>

        </td>
        <td class="text-right">

            <?php

            if ($end_result<0):



                echo number_format($end_result*-1,2);
                $end_credit_total+=$end_result;
                ?>


                <?php



            endif; ?>

        </td>

       <?php if ($end_result==0): ?> <input value="<?php echo $end_result  ?>"  type="hidden" id="remove<?php echo  $Counter ?>" class="remove" /> <?php endif; ?>
    </tr>
    <?php $Counter++; endif; endforeach; ?>

    </tbody>



    <tr>
        <td colspan="3">TOTAL</td>
        <td class="text-right" colspan="1"><?php echo number_format($total_opening_debit,2) ?></td>
        <td class="text-right" colspan="1"><?php echo number_format($total_opening_credit*-1,2) ?></td>
        <td class="text-right" colspan="1"><?php echo number_format($tx_trial_debit,2) ?></td>
        <td class="text-right" colspan="1"><?php echo number_format($tx_trial_credit,2) ?></td>
        <td class="text-right" colspan="1"><?php echo number_format($end_debit_total,2); ?></td>
        <td class="text-right" colspan="1"><?php echo number_format($end_credit_total*-1,2); ?></td>

    </tr>
</table>
            <input type="submit" value="Customise Trial Balance" class="btn btn-success">
        </form>

<script>
$( document ).ready(function() {
   $('.remove').each(function(i, obj) {
           var value= $(this).val();
           var id=  $(this).attr("id");
           var number=id.replace('remove','');
       //    $('#tr'+number).remove();

        });




</script>
<?php
    }

    public function filterBookDayList(){
        return view('Finance.AjaxPages.filterBookDayList');
	}

	public function getDataSupplierWise()
	{
	    $SupplierId = Input::get('SupplierId');
	 //   $jvs= new Jvs();
	//	$jvs=$jvs->SetConnection('mysql2');
		//$jvs=$jvs->where('purchase',1)->where('status',1)->where('supplier_id',$SupplierId)->get();
		$jvs=DB::Connection('mysql2')->select('select * from breakup_data where status=1 and supplier_id="'.$SupplierId.'" group by main_id');
	    return view('Finance.AjaxPages.filterviewOutstanding_bills_through_jvs',compact('SupplierId','jvs'));
	}

	function trialBalanceSheet()
	{


	    $data=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from=$data[0];
        $RadioVal = Input::get('RadioVal');
        $fom_date = Input::get('fom_date');
        $to_date = Input::get('to_date');
        $m = Input::get('m');
         $type=Input::get('type');

        if ($type==1 && $RadioVal == 2):
         return view('Finance.AjaxPages.balance_sheet_detaild',compact('fom_date','to_date','m'));
        endif;
        if ($type==5):

        CommonHelper::companyDatabaseConnection(Session::get('run_company'));
		$accounts1 = new Account;

		if ($RadioVal==1):
		$accounts1 = $accounts1->where('status',1)->where('code',1)->
		   where([
                ['level4', '=', 0],
                ['level5', '=', 0],
                ['level6', '=', 0],
                ['level7', '=', 0],])->

		    orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();
		$accounts2 = new Account;
		$accounts2 = $accounts2->where('status',1)->where('code',2)->orderBy('level1', 'ASC')->
		 where([
                ['level4', '=', 0],
                ['level5', '=', 0],
                ['level6', '=', 0],
                ['level7', '=', 0],])
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();
		$accounts3 = new Account;
		$accounts3 = $accounts3->where('status',1)->where('code',3)->where('type','!=',1)->orderBy('level1', 'ASC')->
		 where([
                ['level4', '=', 0],
                ['level5', '=', 0],
                ['level6', '=', 0],
                ['level7', '=', 0],])
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();
		else:

		$accounts1 = $accounts1->where('status',1)->where('code',1)->orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();
		$accounts2 = new Account;
		$accounts2 = $accounts2->where('status',1)->where('code',2)->orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();
		$accounts3 = new Account;
		$accounts3 = $accounts3->where('status',1)->where('code',3)->where('type','!=',1)->orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();

		endif;
			CommonHelper::reconnectMasterDatabase();
         return view('Finance.AjaxPages.balance_sheet_other',compact('fom_date','to_date','m','accounts1','accounts2','accounts3'));
        endif;


        die;




	}

    function IncomeStatementOld()
	{
	    return view('Finance.AjaxPages.getIncomeStatement');
	}

	function IncomeStatement_old_old()
	{
	    $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $CompanyId = Input::get('m');
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <p style="font-size:20px;">Income Statement</p>
                        </div>
                    </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive" >
                    <table class="table table-bordered sf-table-th sf-table-list" id="table_export1" style="background:#FFF !important;">
                        <thead>
                            <tr>
                                <th>Revenue</th>
                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  level4=0 and level5=0 and level6=0 and level7=0 and code like '5-%'  order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):
                            $head=strlen($row->code);
                        ?>
                            <tr>
                                <td><?php echo $row->code ?></td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> ><?php echo $row->name;?></td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">
                                <?php echo number_format(CommonHelper::get_ledger_amount($row->code,$CompanyId,0,1,$from_date,$to_date),2) ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                            <?php $revenue=CommonHelper::get_ledger_amount(5,$CompanyId,0,1,$from_date,$to_date) ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="2" style="font-size: large;font-weight: bolder">Total Revenue</td>
                                <td class="text-right"><?php echo number_format($revenue,2) ?></td>
                            </tr>
                        </tbody>
                        </table>


                        <table class="table table-bordered sf-table-th sf-table-list" id="table_export1" style="background:#FFF !important;">
                            <thead>
                                <tr>
                                    <th>Expense</th>
                                    <th style="" class="text-center">Account</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  level4=0 and level5=0 and level6=0 and level7=0 and code like '4-%'  order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):
                            $head=strlen($row->code);
                            ?>
                            <tr>
                                <td><?php echo $row->code ?></td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> ><?php echo $row->name;?></td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right"><?php echo number_format(CommonHelper::get_ledger_amount($row->code,$CompanyId,1,0,$from_date,$to_date),2); ?> </td>
                            </tr>
                            <?php endforeach ?>
                            <?php $expense=CommonHelper::get_ledger_amount(4,$CompanyId,1,0,$from_date,$to_date)?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="2" style="font-size: large;font-weight: bolder">Total Expenses</td>
                                <td class="text-right"><?php echo number_format($expense,2); ?></td>
                            </tr>
                            </tbody>
                        </table>


                        <table class="table table-bordered sf-table-th sf-table-list" id="table_export1" style="background:#FFF !important;">
                            <thead>
                            <?php $net_income_before=$revenue-$expense;
                            if ($net_income_before<0):
                                $net_income_before=$net_income_before*-1;
                                $net_income_before='('.number_format($net_income_before,2).')';
                            else:
                                $net_income_before=number_format($net_income_before,2);
                            endif;
                            ?>
                            <tr style="background-color: lightyellow">
                                <td colspan="2" style="font-size: large;font-weight: bolder">Net Income Before Taxes</td>
                                <td class="text-right" colspan="2"><?php echo  $net_income_before ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-size: large;font-weight: bolder">Income tax expense</td>
                                <td class="text-right" colspan="1"><?php echo  0 ?></td>
                            </tr>
                            <tr style="background-color: lightyellow">
                                <td colspan="2" style="font-size: large;font-weight: bolder">Net Income</td>
                                <td class="text-right" colspan="2"><?php echo  $net_income_before ?></td>
                            </tr>
                            </thead>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

	}


    function comparetiveIncome($from , $to)
    {
        $from_datee = new \DateTime($from);
        $to_datee = new \DateTime($to);
        $CompanyId = Input::get('m');

// Initialize an array to store the start and end dates of each month
        $months = [];

        // Loop through each month between the two dates
        while ($from_datee <= $to_datee)
        {

            $start_of_month = clone $from_datee;
            $start_of_month->modify('first day of this month');


            $end_of_month = clone $from_datee;
            $end_of_month->modify('last day of this month');


            $months[] = [
                'start_date' => $start_of_month->format('Y-m-d'),
                'end_date' => $end_of_month->format('Y-m-d')
            ];

            // Move to the next month
            $from_datee->modify('+1 month');
        }

        // Output the array of start and end dates for each month


?>
        <style>
        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {padding: 0px !important;}
        .popover {width: 100%}
        .popover {
    white-space: pre-wrap;
}
</style>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
<!--                    <div class="">-->
<!--                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">-->
<!--                            <p style="font-size:20px;">Profit & Loss Statement</p>-->
<!--                        </div>-->
<!--                    </div>-->
        <div class="row">


        <?php foreach ($months as $key => $row):

            $from_date = $row['start_date'];
            $to_date = $row['end_date'];
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive profit_responsive" style="pading:10px 8px !important;" >






                <table class="table table-bordered sf-table-th sf-table-list profit_Loss_Statement"style="pading:10px 8px !important;" id="exportIncomeStatement1" style="background:#FFF !important;">
                            <tbody>
                            <tr>
                                <td colspan="2" class="text-center"><h3 class="text-center"><?php  echo CommonHelper::getCompanyName(Session::get('run_company'));?></h3></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><p style="font-size:20px;" class="text-center">Profit & Loss Statement</p></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><h3 class="text-center">Form Date: (<?php  echo CommonHelper::changeDateFormat($from_date);?>) To Date: (<?php  echo CommonHelper::changeDateFormat($to_date);?>)</h3></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></td>
                            </tr>
                                <tr>
<!--                                    <th>Revenue</th>-->
                                    <th style="" class="text-center">Account</th>
                                    <th>Amount</th>
                                </tr>


                            <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                             and level1=5  order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):
                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                            $paramOne = "fdc/getSummaryLedgerDetail?m=".$CompanyId;
                            ?>
                            <tr>

                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php endif;?>
                                </td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">

                                <?php $amount= CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',0,1);

                                  if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount='('.$amount.')';

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;
                                 ?>
</td>
                            </tr>
                            <?php endforeach ?>

                            <?php $revenue=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,5,'1',0,1);
                            $revenuee=$revenue;

                            if ($revenue<0):
                                    $revenue_cal=$revenue;
                                    $revenue=($amount*-1);
                                    $revenue=number_format($revenue);
                                    $revenue='('.$revenue.')';

                                    else:
                                    $revenue_cal=$revenue;
                                    $revenue=number_format($revenue);

                                    endif;

                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total Revenue</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo $revenue ?></td>
                            </tr>



                                <tr>

                                    <th style="" class="text-center">Account</th>
                                    <th>Amount</th>
                                </tr>

                            <tr>
                            <td>Opening Inventory</td>
                             <td class="text-right">
                             <?php

                     $financial=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
                        if ($from_date==$financial[0]):


                        $open_amount=CommonHelper::get_opening_ball($from_date,$to_date,97,1,'1-2-1-1');
                        else:



                   $too= date('Y-m-d', strtotime('-1 days', strtotime($from_date)));;
                    $open_amount_dr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$financial[0],$too])
                    ->sum('amount');



                    $open_amount_cr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$financial[0],$too])
                    ->sum('amount');
            $open_amount=$open_amount_dr-$open_amount_cr;
                        endif;
                        echo number_format($open_amount,2);


                              ?></td>
                            </tr>

                              <tr>
                            <td  onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.'1-2-1-1'?>','Ledger Detail')">Purchases</td>
                             <td title="Net Purchase = Total Debit - Credit Note - Stock Return From Work order - Debit Note - Issuence For Work Order" class="text-right"><?php


                                        //
                     $net_purchase=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');


                    $credit_note=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->where('voucher_type',9)
                    ->sum('amount');


                    $stock_return_from_work_order=0;



            $issuence_from_work=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->where('voucher_type',13)
                    ->sum('amount');

                    //





                      $purchase_amount_dr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->whereNotIn('voucher_type',[9])
                    ->sum('amount');




                    $purchase_amount_cr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',97)
                    ->where('voucher_type',5)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');
                  $purchase_amount=  $purchase_amount_dr-$purchase_amount_cr-$issuence_from_work;

                     $net_purchase.' - '.$credit_note.' - '.$stock_return_from_work_order.' - '.$purchase_amount_cr.' - '.$issuence_from_work.' = ';
               echo       number_format($purchase_amount,2);

?>
                            </tr>


                              <tr>
                            <td>Closing Inventory</td>
                             <td class="text-right"><?php


         $sales_dr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',768)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');


                     $saless=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',768)
                    ->where('voucher_type',8)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');

                     $sales_return=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',768)
                    ->where('voucher_type',9)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');

                $sales_cr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',768)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');


        $sales=$sales_dr-$sales_cr;


                    $cogs=$open_amount+$purchase_amount-$sales;
                    echo number_format($cogs,2);
$in_amount=0;
$remianig_amount=0;
                             ?></td>
                            </tr>


                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Cost Of Goods Sold</td>
                                <?php ; ?>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($sales,2) ?></td>
                            </tr>




                            <tr data-toggle="Detailed" title="Detailed" data-content="Closing Inventory =Opening +Net Purchas+Sales Return-Sales
                             <?php echo number_format($open_amount,2).'+'.number_format($purchase_amount,2).'+'.number_format($sales_return,2).'-'.$saless.'='.number_format($open_amount+$purchase_amount+$sales_return-$saless,2) ?>" style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Gross Profit</td>
                                <?php

                               $gross_profit= $revenuee-$sales;
                                ?>

                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($revenuee-$sales,2) ?></td>
                            </tr>

                            <tr>

                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>

                        <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  level1=4
                            order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):

                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                        $amount =CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);

                        if ($amount!=0):
                        ?>
                            <tr>
<!--                                <td>< ?php echo $row->code.'=='.$level ?></td>-->
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php endif;?>
                                </td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">
                                <?php
                                    if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount='('.$amount.')';

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;

                                ?>
                                </td>
                            </tr>
                            <?php endif; endforeach ?>
                            <?php $expense=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,4,'1',1,0);

                            if ($expense<0):
                                     $expense_cal=$expense;
                                    $expense=($expense*-1);
                                    $expense=number_format($expense);
                                    $expense='('.$expense.')';


                                    else:
                                       $expense_cal=$expense;
                                    $expense=number_format($expense);
                                    endif;

                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total Expenses</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo $expense ?></td>
                            </tr>

                            <?php $net_income_before=$gross_profit-$expense_cal;
                            if ($net_income_before<0):
                                $net_income_before=$net_income_before*-1;
                                $net_income_before='('.number_format($net_income_before,2).')';
                            else:
                                $net_income_before=number_format($net_income_before,2);
                            endif;
                            ?>
                            <tr style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Net Income Before Taxes</td>
                                <td class="text-right" colspan="2"><?php echo  $net_income_before ?></td>
                            </tr>
                            <tr>
                                <td colspan="1" style="font-size: large;font-weight: bolder">Income tax expense</td>
                                <td class="text-right" colspan="1"><?php echo  0 ?></td>
                            </tr>
                            <tr style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Net Income</td>
                                <td class="text-right" colspan="2"><?php echo  $net_income_before ?></td>
                            </tr>


                            </table>
                        </div>

                    </div>

                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function(){

    $('[data-toggle="Detailed"]').popover();
});
</script>
    <?php



    }
	function IncomeStatement(Request $request)
	{

	    $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $comparetive = Input::get('comparetive');

        if ($comparetive == 1):
            return $this->comparetiveIncome($from_date , $to_date);
        endif;





        $CompanyId = Input::get('m');
        ?>
        <style>
        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {padding: 0px !important;}
        .popover {width: 100%}
        .popover {
    white-space: pre-wrap;
}
</style>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
<!--                    <div class="">-->
<!--                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">-->
<!--                            <p style="font-size:20px;">Profit & Loss Statement</p>-->
<!--                        </div>-->
<!--                    </div>-->
        <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive" >






                <table class="table table-bordered sf-table-th sf-table-list profit_Loss_Statement"style="pading:10px 8px !important;" id="exportIncomeStatement1" style="background:#FFF !important;">
                            <tbody>
                            <tr>
                                <td colspan="2" class="text-center"><h3 class="text-center"><?php  echo CommonHelper::getCompanyName(Session::get('run_company'));?></h3></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><p style="font-size:20px;" class="text-center">Profit & Loss Statement</p></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><h3 class="text-center">Form Date: (<?php  echo CommonHelper::changeDateFormat($from_date);?>) To Date: (<?php  echo CommonHelper::changeDateFormat($to_date);?>)</h3></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></td>
                            </tr>
                                <tr>
<!--                                    <th>Revenue</th>-->
                                    <th style="" class="text-center">Account</th>
                                    <th>Amount</th>
                                </tr>


                            <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                             and level1=5  order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):
                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                            $paramOne = "fdc/getSummaryLedgerDetail?m=".$CompanyId;
                            ?>
                            <tr>

                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php endif;?>
                                </td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">

                                <?php $amount= CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',0,1);

                                  if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount='('.$amount.')';

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;
                                 ?>
</td>
                            </tr>
                            <?php endforeach ?>

                            <?php $revenue=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,5,'1',0,1);
                            $revenuee=$revenue;

                            if ($revenue<0):
                                    $revenue_cal=$revenue;
                                    $revenue=($amount*-1);
                                    $revenue=number_format($revenue);
                                    $revenue='('.$revenue.')';

                                    else:
                                    $revenue_cal=$revenue;
                                    $revenue=number_format($revenue);

                                    endif;

                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total Revenue</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo $revenue ?></td>
                            </tr>



                                <tr>

                                    <th style="" class="text-center">Account</th>
                                    <th>Amount</th>
                                </tr>

                            <tr>
                            <td>Opening Inventory</td>
                             <td class="text-right">
                             <?php

                     $financial=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
                        if ($from_date==$financial[0]):


                        $open_amount=CommonHelper::get_opening_ball($from_date,$to_date,97,1,'1-2-1-1');
                        else:



                   $too= date('Y-m-d', strtotime('-1 days', strtotime($from_date)));;
                    $open_amount_dr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$financial[0],$too])
                    ->sum('amount');



                    $open_amount_cr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$financial[0],$too])
                    ->sum('amount');
            $open_amount=$open_amount_dr-$open_amount_cr;
                        endif;
                        echo number_format($open_amount,2);


                              ?></td>
                            </tr>

                              <tr>
                            <td  onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.'1-2-1-1'?>','Ledger Detail')">Purchases</td>
                             <td title="Net Purchase = Total Debit - Credit Note - Stock Return From Work order - Debit Note - Issuence For Work Order" class="text-right"><?php


                                        //
                     $net_purchase=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');


                    $credit_note=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->where('voucher_type',9)
                    ->sum('amount');


                    $stock_return_from_work_order=0;



            $issuence_from_work=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->where('voucher_type',13)
                    ->sum('amount');

                    //





                      $purchase_amount_dr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->whereNotIn('voucher_type',[9])
                    ->sum('amount');




                    $purchase_amount_cr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',97)
                    ->where('voucher_type',5)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');
                  $purchase_amount=  $purchase_amount_dr-$purchase_amount_cr-$issuence_from_work;

                     $net_purchase.' - '.$credit_note.' - '.$stock_return_from_work_order.' - '.$purchase_amount_cr.' - '.$issuence_from_work.' = ';
               echo       number_format($purchase_amount,2);

?>
                            </tr>


                              <tr>
                            <td>Closing Inventory</td>
                             <td class="text-right"><?php


         $sales_dr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',768)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');


                     $saless=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',768)
                    ->where('voucher_type',8)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');

                     $sales_return=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',768)
                    ->where('voucher_type',9)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');

                $sales_cr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',768)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');


        $sales=$sales_dr-$sales_cr;


                    $cogs=$open_amount+$purchase_amount-$sales;
                    echo number_format($cogs,2);
$in_amount=0;
$remianig_amount=0;
                             ?></td>
                            </tr>


                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Cost Of Goods Sold</td>
                                <?php ; ?>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($sales,2) ?></td>
                            </tr>




                            <tr data-toggle="Detailed" title="Detailed" data-content="Closing Inventory =Opening +Net Purchas+Sales Return-Sales
                             <?php echo number_format($open_amount,2).'+'.number_format($purchase_amount,2).'+'.number_format($sales_return,2).'-'.$saless.'='.number_format($open_amount+$purchase_amount+$sales_return-$saless,2) ?>" style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Gross Profit</td>
                                <?php

                               $gross_profit= $revenuee-$sales;
                                ?>

                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($revenuee-$sales,2) ?></td>
                            </tr>

                            <tr>

                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>

                        <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  level1=4
                            order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):

                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                        $amount =CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);

                        if ($amount!=0):
                        ?>
                            <tr>
<!--                                <td>< ?php echo $row->code.'=='.$level ?></td>-->
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '<span class="SpacesCls">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>'. $row->name?></a>
                                    <?php endif;?>
                                </td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">
                                <?php
                                    if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount='('.$amount.')';

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;

                                ?>
                                </td>
                            </tr>
                            <?php endif; endforeach ?>
                            <?php $expense=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,4,'1',1,0);

                            if ($expense<0):
                                     $expense_cal=$expense;
                                    $expense=($expense*-1);
                                    $expense=number_format($expense);
                                    $expense='('.$expense.')';


                                    else:
                                       $expense_cal=$expense;
                                    $expense=number_format($expense);
                                    endif;

                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total Expenses</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo $expense ?></td>
                            </tr>

                            <?php $net_income_before=$gross_profit-$expense_cal;
                            if ($net_income_before<0):
                                $net_income_before=$net_income_before*-1;
                                $net_income_before='('.number_format($net_income_before,2).')';
                            else:
                                $net_income_before=number_format($net_income_before,2);
                            endif;
                            ?>
                            <tr style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Net Income Before Taxes</td>
                                <td class="text-right" colspan="2"><?php echo  $net_income_before ?></td>
                            </tr>
                            <tr>
                                <td colspan="1" style="font-size: large;font-weight: bolder">Income tax expense</td>
                                <td class="text-right" colspan="1"><?php echo  0 ?></td>
                            </tr>
                            <tr style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Net Income</td>
                                <td class="text-right" colspan="2"><?php echo  $net_income_before ?></td>
                            </tr>


                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function(){

    $('[data-toggle="Detailed"]').popover();
});
</script>
    <?php

	}

	function flow_statement_ajax()
	{
	    $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $CompanyId = Input::get('m');
        ?>
        <style>
        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {padding: 0px !important;}
        .popover {width: 100%}
        .popover {
    white-space: pre-wrap;
}
</style>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
<!--                    <div class="">-->
<!--                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">-->
<!--                            <p style="font-size:20px;">Profit & Loss Statement</p>-->
<!--                        </div>-->
<!--                    </div>-->
        <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive" >

                <table class="table table-bordered sf-table-th sf-table-list profit_Loss_Statement" style="pading:10px 8px !important;" id="exportIncomeStatement1" style="background:#FFF !important;">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="text-center"><h3 class="text-center"><?php  echo CommonHelper::getCompanyName(Session::get('run_company'));?></h3></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center"><p style="font-size:20px;" class="text-center">Flow Statement</p></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center"><h3 class="text-center">Form Date: (<?php  echo CommonHelper::changeDateFormat($from_date);?>) To Date: (<?php  echo CommonHelper::changeDateFormat($to_date);?>)</h3></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></td>
                                </tr>
                                <tr>
                                    <!--  <th>Revenue</th>-->
                                    <th style="" class="text-center">Account</th>
                                    <th>Amount</th>
                                </tr>
                            <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                             and level1=5  order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):
                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                            $paramOne = "fdc/getSummaryLedgerDetail?m=".$CompanyId;
                            ?>
                            <tr>

                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '&emsp;&emsp;'. $row->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $row->code?>')"><?php echo  '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php endif;?>
                                </td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">

                                <?php $amount= CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',0,1);

                                  if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount='('.$amount.')';

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;
                                 ?>
                                </td>
                            </tr>
                            <?php endforeach ?>

                            <?php $revenue=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,5,'1',0,1);
                            $revenuee=$revenue;

                            if ($revenue<0):
                                    $revenue_cal=$revenue;
                                    $revenue=($amount*-1);
                                    $revenue=number_format($revenue);
                                    $revenue='('.$revenue.')';

                                    else:
                                    $revenue_cal=$revenue;
                                    $revenue=number_format($revenue);

                                    endif;
                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total Revenue</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo $revenue ?></td>
                            </tr>

                            <tr>

                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>

                            <tr>
                            <td>Opening Inventory</td>
                             <td class="text-right">
                             <?php

                        $financial=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
                            if ($from_date==$financial[0]):
                        $open_amount=CommonHelper::get_opening_ball($from_date,$to_date,97,1,'1-2-1-1');
                        else:
                        $too= date('Y-m-d', strtotime('-1 days', strtotime($from_date)));;
                        $open_amount_dr=DB::Connection('mysql2')->table('transactions')
                        ->where('status',1)
                        ->where('debit_credit',1)
                        ->where('acc_id',97)
                        ->whereBetween('v_date',[$financial[0],$too])
                        ->sum('amount');
                        $open_amount_cr=DB::Connection('mysql2')->table('transactions')
                        ->where('status',1)
                        ->where('debit_credit',0)
                        ->where('acc_id',97)
                        ->whereBetween('v_date',[$financial[0],$too])
                        ->sum('amount');
                        $open_amount=$open_amount_dr-$open_amount_cr;
                        endif;
                        echo number_format($open_amount,2);


                              ?></td>
                            </tr>

                              <tr>
                            <td  onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.'1-2-1-1'?>','Ledger Detail')">Purchases</td>
                             <td class="text-right"><?php


                     $purchase_amount_dr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->where('voucher_type','!=',9)
                    ->sum('amount');




                    $purchase_amount_cr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',97)
                    ->where('voucher_type',5)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');


                     $issuence_from_work=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',97)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->where('voucher_type',13)
                    ->sum('amount');
                  $purchase_amount=  $purchase_amount_dr-$purchase_amount_cr-$issuence_from_work;

                    echo number_format($purchase_amount,2);

?>
                            </tr>


                              <tr>
                            <td>Closing Inventory</td>
                             <td class="text-right"><?php


         $sales_dr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',768)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)

                    ->sum('amount');


                     $saless=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',1)
                    ->where('acc_id',768)
                    ->where('voucher_type',8)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');

                     $sales_return=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',768)
                    ->where('voucher_type',9)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');

                $sales_cr=DB::Connection('mysql2')->table('transactions')
                    ->where('status',1)
                    ->where('debit_credit',0)
                    ->where('acc_id',768)
                    ->whereBetween('v_date',[$from_date,$to_date])
                    ->where('opening_bal',0)
                    ->sum('amount');


        $sales=$sales_dr-$sales_cr;

                    $cogs=$open_amount+$purchase_amount-$sales;
                    echo number_format($cogs,2);
$in_amount=0;
$remianig_amount=0;
                             ?></td>
                            </tr>


                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Cost Of Goods Sold</td>
                                <?php ; ?>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($sales,2) ?></td>
                            </tr>




                            <tr data-toggle="Detailed" title="Detailed" data-content="Closing Inventory =Opening +Net Purchas+Sales Return-Sales
                             <?php echo number_format($open_amount,2).'+'.number_format($purchase_amount,2).'+'.number_format($sales_return,2).'-'.$saless.'='.number_format($open_amount+$purchase_amount+$sales_return-$saless,2) ?>" style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Gross Profit</td>
                                <?php

                               $gross_profit= $revenuee-$sales;
                                ?>

                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($revenuee-$sales,2) ?></td>
                            </tr>

                            <tr>

                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>

                        <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  level1=4
                            order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):

                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                        $amount =CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,$row->code,'1',1,0);

                        if ($amount!=0):
                        ?>
                            <tr>
<!--                                <td>< ?php echo $row->code.'=='.$level ?></td>-->
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 1):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 2):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;'. $row->name?></a>
                                    <?php elseif($level == 3):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php  elseif($level == 4):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php elseif($level == 5):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php elseif($level == 6):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php elseif($level == 7):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name?></a>
                                    <?php endif;?>
                                </td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">
                                <?php
                                    if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount='('.$amount.')';

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;

                                ?>
                                </td>
                            </tr>
                            <?php endif; endforeach ?>
                            <?php $expense=CommonHelper::get_parent_and_account_amount(1,$from_date,$to_date,4,'1',1,0);

                            if ($expense<0):
                                     $expense_cal=$expense;
                                    $expense=($expense*-1);
                                    $expense=number_format($expense);
                                    $expense='('.$expense.')';


                                    else:
                                       $expense_cal=$expense;
                                    $expense=number_format($expense);
                                    endif;

                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total Expenses</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo $expense ?></td>
                            </tr>

                            <?php
                            $net_income_beforee=0;
                            $net_income_before=$gross_profit-$expense_cal;
                            if ($net_income_before<0):
                                $net_income_before=$net_income_before*-1;
                                $net_income_beforee=$net_income_before;
                                $net_income_before='('.number_format($net_income_before,2).')';

                            else:
                            $net_income_beforee=$net_income_before;
                                $net_income_before=number_format($net_income_before,2);

                            endif;
                            ?>
                            <tr style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Net Income Before Taxes</td>
                                <td class="text-right" colspan="2"><?php echo  $net_income_before ?></td>
                            </tr>




                            <?php //flow start ?>
 <tr>

                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>

                        <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  code in ('1-2-3-3-2','1-2-3-3-2-1','1-2-3-3-2-2','1-2-3-3-2-3')
                            order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):

                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                        $amount =CommonHelper::get_parent_and_account_amount_flow(1,$from_date,$to_date,$row->code,'1',1,0);

                        if ($amount!=0):
                        ?>
                            <tr>
<!--                                <td>< ?php echo $row->code.'=='.$level ?></td>-->
                                <td <?php if($head==5){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 5):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 6):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;'. $row->name?></a>

                                    <?php endif;?>
                                </td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">
                                <?php
                                    if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount,2);
                                    $amount=$amount;

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;

                                ?>
                                </td>
                            </tr>
                            <?php endif; endforeach ?>
                            <?php $withhold=CommonHelper::get_parent_and_account_amount_flow(1,$from_date,$to_date,'1-2-3-3-2','1',1,0);


                            if($withhold<0):
                                $withhold=$withhold*-1;
                                endif;

                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total WITHHOLDING TAX RECEIVABLE</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($withhold,2) ?></td>
                            </tr>








                             <?php //flow start third  phase ?>
                                     <tr>

                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>

                        <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  code in ('2-2-7','2-2-7-1','2-2-7-2','2-2-7-3')
                            order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):

                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                        $amount =CommonHelper::get_parent_and_account_amount_flow(1,$from_date,$to_date,$row->code,'1',0,1);

                        if ($amount!=0):
                        ?>
                            <tr>
<!--                                <td>< ?php echo $row->code.'=='.$level ?></td>-->
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 3):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 4):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;'. $row->name?></a>

                                    <?php endif;?>
                                </td>
                                <td <?php if($head==3){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">
                                <?php
                                    if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount=$amount;

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;

                                ?>
                                </td>
                            </tr>
                            <?php endif; endforeach ?>
                            <?php $withhold_pa=CommonHelper::get_parent_and_account_amount_flow(1,$from_date,$to_date,'2-2-7','1',0,1);

                            if ($withhold_pa<0):
                            $withhold_pa=$withhold_pa*-1;
                            endif;
                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total WITHHOLDING TAX PAYABLE</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($withhold_pa,2) ?></td>
                            </tr>




                                     <?php //flow start second phase ?>
                                     <tr>

                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>

                        <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  code in ('1-2-9-5')
                            order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):

                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                        $amount =CommonHelper::get_parent_and_account_amount_flow(1,$from_date,$to_date,$row->code,'1',1,0);

                        if ($amount!=0):
                        ?>
                            <tr>
<!--                                <td>< ?php echo $row->code.'=='.$level ?></td>-->
                                <td <?php if($head==4){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 4):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo strtoupper($row->name)?></a></b>


                                    <?php endif;?>
                                </td>
                                <td <?php if($head==4){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">
                                <?php
                                    if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount=$amount;

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;

                                ?>
                                </td>
                            </tr>
                            <?php endif; endforeach ?>
                            <?php $bip=CommonHelper::get_parent_and_account_amount_flow(1,$from_date,$to_date,'1-2-9-5','1',1,0);

                                if ($bip<0):
                                $bip=$bip*-1;
                                endif;
                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total BIP CONSTRUCTION  ADVANCE</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($bip,2) ?></td>
                            </tr>


                             <?php //flow start fourth  phase ?>
                                     <tr>

                                <th style="" class="text-center">Account</th>
                                <th>Amount</th>
                            </tr>

                        <?php
                            CommonHelper::companyDatabaseConnection($CompanyId);
                            $accounts = DB::select("SELECT * FROM accounts where `status` = '1'
                            and  code in ('3-4','3-4-1','3-4-2','3-4-3')
                            order by level1,level2,level3,level4,level5,level6,level7");
                            $counter = 1;
                            CommonHelper::reconnectMasterDatabase();
                            foreach($accounts as $row):

                            $head=strlen($row->code);
                            $level=count(explode('-',$row->code));
                        $amount =CommonHelper::get_parent_and_account_amount_flow(1,$from_date,$to_date,$row->code,'1',0,1);

                        if ($amount!=0):
                        ?>
                            <tr>
<!--                                <td>< ?php echo $row->code.'=='.$level ?></td>-->
                                <td <?php if($head==2){ ?> style="font-size: large;font-weight: bolder" <?php } ?> >
                                    <?php if($level == 2):?>
                                        <b style="font-size: large;font-weight: bolder"><a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo strtoupper($row->name)?></a></b>
                                    <?php elseif($level == 3):?>
                                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $from_date.','.$to_date.','.$row->code?>','Ledger Detail')"><?php echo  '&emsp;&emsp;'. $row->name?></a>

                                    <?php endif;?>
                                </td>
                                <td <?php if($head==2){ ?> style="font-size: large;font-weight: bolder" <?php } ?> class="text-right">
                                <?php
                                    if ($amount<0):
                                    $amount=($amount*-1);
                                    $amount=number_format($amount);
                                    $amount=$amount;

                                    else:
                                    $amount=number_format($amount);
                                    endif;
                                    echo $amount;

                                ?>
                                </td>
                            </tr>
                            <?php endif; endforeach ?>
                            <?php $burhani=CommonHelper::get_parent_and_account_amount_flow(1,$from_date,$to_date,'3-4','1',0,1);
                        if ($burhani<0):
                        $burhani=$burhani*-1;
                        endif;

                            ?>
                            <tr style="background-color: lightsteelblue">
                                <td colspan="1" style="font-size: large;font-weight: bolder">Total BURHANI AQMS INDUSTRIES RPAYMENT	</td>
                                <td style="font-size: large;font-weight: bolder" class="text-right"><?php echo number_format($burhani,2) ?></td>
                            </tr>

                            <tr style="background-color: lightyellow">
                                <td colspan="1" style="font-size: large;font-weight: bolder">NET INCOME AFTER TAXES AND CAPITAL EXPENDITURE</td>
                                <td class="text-right" colspan="2"><?php echo number_format($net_income_beforee-$withhold-$withhold_pa-$bip-$burhani,2) ?></td>
                            </tr>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function(){

    $('[data-toggle="Detailed"]').popover();
});
</script>
    <?php

	}

	function getSummaryLedgerDetail()
	{
	    return view('Finance.AjaxPages.getSummaryLedgerDetail');
	}



function vendor_summery(Request $request)
	{

	$from=$request->FromDate;
	$to=$request->ToDate;
	$m=$request->m;
		$Supplier = new Supplier();
		$Supplier=$Supplier->SetConnection('mysql2');
		$Supplier=$Supplier
			->select('supplier.id','supplier.name','supplier.acc_id','transactions.acc_code')
			->join('transactions', 'transactions.acc_id', '=', 'supplier.acc_id')
			->join('accounts', 'transactions.acc_id', '=', 'accounts.id')
			->where('supplier.status','=',1)
			->where('transactions.status','=',1)
		//	->where('accounts.parent_code','=','2-2-1')

			->groupBy('transactions.acc_id')
			->get();

		return view('Finance.AjaxPages.vendor_summery',compact('Supplier','from','to','m'));
	}
	function vendor_summery_two(Request $request)
	{

	$from=$request->FromDate;
	$to=$request->ToDate;
	$m=$request->m;
		$Supplier = new Supplier();
		$Supplier=$Supplier->SetConnection('mysql2');
		$Supplier=$Supplier
			->select('supplier.id','supplier.name','supplier.acc_id','transactions.acc_code')
			->join('transactions', 'transactions.acc_id', '=', 'supplier.acc_id')
			->where('supplier.status','=',1)
			->where('transactions.status','=',1)

			->groupBy('transactions.acc_id')
			->get();

		return view('Finance.AjaxPages.vendor_summery_two',compact('Supplier','from','to','m'));
	}


	function receivablSummaryReport(Request $request)
	{

        $data=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from=$data[0];
        $to=$request->ToDate;
        $Format=$request->Format;
        $m=$request->m;
        if($Format == 1)
        {
            $Client = new Customer();
                $Client=$Client->SetConnection('mysql2');
                $Client=$Client
                    ->select('customers.id','customers.name','customers.acc_id','transactions.acc_code')
                    ->join('transactions', 'transactions.acc_id', '=', 'customers.acc_id')
                    ->where('customers.status','=',1)
                    ->where('transactions.status','=',1)
                    ->groupBy('transactions.acc_id')
                    ->get();

                return view('Finance.AjaxPages.receivablSummaryReport',compact('Client','from','to','m'));
        }
        else
        {
            $Client = new Client();
            $Client=$Client->SetConnection('mysql2');
            $Client=$Client
                ->select('client.id','client.client_name','client.acc_id','transactions.acc_code','transactions.paid_to','transactions.paid_to_type')
                ->join('transactions', 'transactions.acc_id', '=', 'client.acc_id')
                ->where('client.status','=',1)
                ->where('transactions.status','=',1)
                ->groupBy('transactions.acc_id')
                ->get();

            return view('Finance.AjaxPages.receivablDetailReport',compact('Client','from','to','m'));
        }

	}

	function employeeSummaryReport(Request $request)
	{

        $from=$request->FromDate;
        $to=$request->ToDate;
        $Format=$request->Format;
        $m=$request->m;
        if($Format == 1)
        {
            $Employee = new Employee();
                $Employee=$Employee->SetConnection('mysql2');
                $Employee=$Employee
                    ->select('employee.id','employee.emp_name','transactions.acc_id','transactions.acc_code')
                    ->join('transactions', 'transactions.paid_to', '=', 'employee.id')
                    ->where('employee.status','=',1)
                    ->where('transactions.status','=',1)
                    ->where('transactions.paid_to_type','=',1)
                    ->groupBy('transactions.acc_id')
                    ->get();

                return view('Finance.AjaxPages.employeeSummaryReport',compact('Employee','from','to','m'));
        }
        else
        {
            $Client = new Client();
            $Client=$Client->SetConnection('mysql2');
            $Client=$Client
                ->select('client.id','client.client_name','client.acc_id','transactions.acc_code','transactions.paid_to','transactions.paid_to_type')
                ->join('transactions', 'transactions.acc_id', '=', 'client.acc_id')
                ->where('client.status','=',1)
                ->where('transactions.status','=',1)
                ->groupBy('transactions.acc_id')
                ->get();

            return view('Finance.AjaxPages.receivablDetailReport',compact('Client','from','to','m'));
        }

	}

    function insertOpeningBalance(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {


            $acc_id=$request->Account_Id;
            $nature=$request->nature;
            $amount=$request->amount;
            $AccYearFrom=$request->AccYearFrom;
            $AccYearTo=$request->AccYearTo;

            $data['debit_credit']=$nature;

            $data['amount']=$amount;


            //$count=$this->db->query('select acc_id from transactions where acc_id="'.$acc_id.'" and opening_bal=1')->num_rows();
            $count = DB::Connection('mysql2')->table('transactions')->where('acc_id',$acc_id)->where('opening_bal',1);

            if ($count->count() > 0):
//            $this->db->where('acc_id',$acc_id);
//            $this->db->where('opening_bal',1);
//            $this->db->update('transactions',$data);
            DB::Connection('mysql2')->table('transactions')->where('acc_id',$acc_id)->where('opening_bal',1)->update($data);
                else:
                    $AccCode = DB::Connection('mysql2')->table('accounts')->where('id',$acc_id)->select('code')->first();
                    $data['debit_credit']=$nature;
                    $data['amount']=$amount;
                    $data['v_date']=$AccYearFrom;
                    $data['opening_bal']=1;
                    $data['username'] = Auth::user()->name;
                    $data['date']=date('Y-m-d');
                    $data['status']=1;
                    $data['acc_id']=$acc_id;
                    $data['debit_credit']=$nature;
                    $data['acc_code']= $AccCode->code;
                    //$this->db->insert('transactions',$data);
                    DB::Connection('mysql2')->table('transactions')->insert($data);

                endif;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());

        }
    }

    function getTrialBalanceDataAjax(Request $request)
    {
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AccYearFrom = $request->AccYearFrom;
        $AccYearTo = $request->AccYearTo;
        $m = $request->m;
        return view('Finance.AjaxPages.getTrialBalanceDataAjax',compact('FromDate','ToDate','AccYearFrom','AccYearTo','m'));
    }



            function get_rights(Request $request)
            {

            $cost_center=new CostCenter();
	    	//$cost_center=$cost_center->SetConnection('mysql2');

	    	$company_id=$request->company_id;

		    $cost_center=$cost_center->where('status',1)->select('*')

			->orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')

			->get();


            $user_id=$request->users;
            $MainModuelCode = $request->MainModuelCode;
            $crud_rights=DB::table('users')->where('emp_code',$user_id)->select('crud_rights')->first()->crud_rights;
            $crud_rights=explode(',',$crud_rights);


            $m=$_GET['m'];
            return view('Finance.rights',compact('user_id','cost_center','crud_rights','m','MainModuelCode','company_id'));
            }

    public function deleteNewPv(Request $request)
    {
        $DeleteId = $request->Id;

        $PvNo = $request->PvNo;



        $DeleteData['status'] = 2;

        DB::Connection('mysql2')->table('new_pvv')->where('id', $DeleteId)->update($DeleteData);
        DB::Connection('mysql2')->table('new_pvv_data')->where('pv_no', $PvNo)->update($DeleteData);
        DB::Connection('mysql2')->table('transactions')->where('voucher_no', $PvNo)->update($DeleteData);

        echo $DeleteId;

    }

    public function get_new_pvs_list_ajax()
    {

        $fromDate = $_GET['from'];
        $to = $_GET['to'];
        $SupplierId = $_GET['SupplierId'];
        $m = $_GET['m'];

        if($SupplierId == 'all'):
            $Data = DB::Connection('mysql2')->table('new_pvv')->where('status',1)
                ->whereBetween('pv_date', [$fromDate, $to])
                ->orderBy('pv_date','ASC')->orderBy('id', 'desc')->get();

        else:
                $Data = DB::Connection('mysql2')->table('new_pvv')->where('status',1)->where('supplier_id',$SupplierId)
                ->whereBetween('pv_date', [$fromDate, $to])
                ->orderBy('pv_date','ASC')->orderBy('id', 'desc')->get();
        endif;


        return view('Finance.AjaxPages.get_new_pvs_list_ajax', compact('Data','m'));

    }

    public function com_delete(Request $request)
    {
      $id=  $request->id;
      $data['status'] = 0;
       DB::connection('mysql2')->table('commision')->where('id',$id)->update($data);
       DB::connection('mysql2')->table('commision_data')->where('master_id',$id)->update($data);
       echo $id;
    }

     function trial_balance_other_format(Request $request)
    {
        $from = $request->FromDate;
        $to = $request->ToDate;
        $GetType = $request->GetType;
        $m = $request->m;
        return view('Finance.AjaxPages.trial_balance_other_format',compact('from','to','m','GetType'));
    }
}
