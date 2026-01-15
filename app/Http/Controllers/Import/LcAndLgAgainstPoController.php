<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Import\BLDetail;
use App\Models\Import\InsuranceDetails;
use App\Models\Import\GDDetail;
use App\Models\Import\ShippingExpense;
use App\Models\Import\ClearingAgent;
use App\Models\Import\MaturityDetail;
use App\Models\Import\HsCode;
use App\Models\Import\LcAndLg;
use App\Models\Import\LcAndLgAgainstPo;
use App\Models\Import\LcAndLgAgainstPoData;
use App\Models\PurchaseRequest;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LcAndLgAgainstPoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('mysql2')->table('purchase_request as pr')
                ->join('lc_and_lg_against_po as lalap', 'pr.id', '=', 'lalap.po_id')
                ->where('lalap.status', 1)
                ->where('pr.status', 1)
                // ->where('pr.purchase_request_status', 2)
                ->select('lalap.id', 'pr.purchase_request_no', 'lalap.buyer_name', 'lalap.beneficiary_name', 'lalap.advising_bank', 'lalap.amount');
            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->orderBy('lalap.id', 'desc')->get();

            return view('Import.LcAndLgAgainstPo.ajax.listLcAndLgAgainstPoAjax', compact('data'));
        }

        return view('Import.LcAndLgAgainstPo.listLcAndLgAgainstPo');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $PurchaseRequest = new PurchaseRequest();
        $PurchaseRequest = $PurchaseRequest->SetConnection('mysql2')->where(['purchase_request_status' => 2])->where('status', 1)->get();

        if ($request->ajax()):
            $purchase_request_data = db::Connection('mysql2')->table('purchase_request as a')
                ->join('purchase_request_data as b', 'a.id', 'b.master_id')
                ->leftJoin('lc_and_lg_against_po as c', 'a.id', 'c.po_id')
                // ->leftJoin('bl_details as d','c.id','d.lc_and_lg_against_po_id')
                // ->leftJoin('insurence_details as e','c.id','e.lc_and_lg_against_po_id')
                // ->leftJoin('g_d_details as f','c.id','f.lc_and_lg_against_po_id')
                // ->leftJoin('shipping_expenses as g','c.id','g.lc_and_lg_against_po_id')
                // ->leftJoin('clearing_agents as h','c.id','h.lc_and_lg_against_po_id')
                // ->leftJoin('maturity_details as i','c.id','i.lc_and_lg_against_po_id')
                ->select(
                    'b.*',
                    'b.id as po_data_id',
                    'a.currency_rate',
                    'a.supplier_id',
                    'a.currency_id',
                    'a.terms_of_paym',
                    'c.*',
                    'c.hs_code as lc_lg_hs_code',
                    'c.description as lc_lg_description',
                    'c.sub_description as lc_lg_sub_description',
                    'c.bl_date as lc_lg_bl_date',
                    'c.id as lc_and_lg_against_po_id',
                    // 'd.*' ,'d.bl_no as b_bl_no','d.lot_no as b_lot_no','d.line as b_line','d.fwd as b_fwd',
                    //  'e.*' ,'e.lot_no as i_lot_no','e.cover_note as i_cover_note',
                    // 'e.policy_company as i_policy_company','e.tolerance as i_tolerance','e.policy_no as i_policy_no','e.remarks as i_remarks','e.policy_status as i_policy_status',
                    // 'f.*','f.lot_no as gd_lot_no','f.date as gd_date','f.description as gd_description','f.hs_code as gd_hs_code',
                    // 'g.*','g.bl_no as s_bl_no','g.lot_no as s_lot_no','g.amount as s_amount','g.deduction as s_deduction','g.refund_amount as s_refund_amount','g.remarks as s_remarks',
                    // 'h.*','h.lot_no as c_lot_no','h.amount as c_amount','h.deduction as c_deduction','h.paid_amount as c_paid_amount','h.remarks as c_remarks',
                    // 'i.*','i.lot_no as m_lot_no' , 'i.rate as m_rate', 'i.remarks as m_remarks'
                )
                ->selectraw('b.rate as po_rate')
                ->where('b.master_id', $request->po_id)
                ->where('a.status', 1)
                ->get();

            // dd($purchase_request_data->toArray());
            return view('Import.LcAndLgAgainstPo.createLcAndLgAgainstPo_new', compact('purchase_request_data', 'PurchaseRequest'));
        endif;
        return view('Import.LcAndLgAgainstPo.createLcAndLgAgainstPo', compact('PurchaseRequest'));
    }


    public function getPodata(Request $request)
    {
        $PurchaseRequest = new PurchaseRequest();
        $PurchaseRequest = $PurchaseRequest->SetConnection('mysql2')->where(['purchase_request_status' => 2, 'po_type' => 3])->where('status', 1)->get();

        $data_po_id = $request->data_po_id;
        $po_id = $request->po_id;
        if ($request->ajax()):
            $purchase_request_data = db::Connection('mysql2')->table('purchase_request as a')
                ->join('purchase_request_data as b', 'a.id', 'b.master_id')
                ->leftJoin('lc_and_lg_against_po as c', 'a.id', 'c.po_id')
                ->select(
                    'b.*',
                    'a.currency_rate',
                    'a.supplier_id',
                    'a.currency_id',
                    'a.terms_of_paym',
                    'c.*',
                    'c.hs_code as lc_lg_hs_code',
                    'c.description as lc_lg_description',
                    'c.bl_date as lc_lg_bl_date',
                    'c.id as lc_and_lg_against_po_id',
                )
                ->selectraw('b.rate as po_rate')
                ->where('b.master_id', $request->data_po_id)
                ->where('a.status', 1)
                ->get();
            // dd($purchase_request_data->toArray());
            return view('Import.LcAndLgAgainstPo.getDataOfPO', compact('purchase_request_data', 'PurchaseRequest', 'po_id'));
        endif;
        return view('Import.LcAndLgAgainstPo.createLcAndLgAgainstPo', compact('PurchaseRequest'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();

        DB::Connection('mysql2')->beginTransaction();

        try {

            $checkValue = LcAndLgAgainstPo::where('status', 1)->where('po_id', $request->po_id);
            $lg_id = 0;
            $against_po = new LcAndLgAgainstPo();
            $against_po = $against_po->SetConnection('mysql2');
            if ($checkValue->count() > 0):
                $against_po = $against_po->find($checkValue->first()->id);
                $lg_id = $against_po->id;
                LcAndLgAgainstPoData::where('master_id', $lg_id)->delete();
                $this->removeExisting($lg_id);
            else:
                $req_limit = $request->cd_total_pkr_amount;
                $lc_limit = LcAndLg::where('id', $request->applicant_bank)->first();
                $available_limit = $lc_limit->limit - $lc_limit->limit_utilize;
                if ($req_limit > $available_limit) {
                    DB::Connection('mysql2')->rollback();
                    // return redirect()->back()->withErrors('Out of LC - LG Bank Limit.')->withInput();
                    return response()->json(['status' => false, 'message' => 'Out of LC - LG Bank Limit.']);
                } else {
                    // $remaining_utilize = $available_limit - $req_limit;
                    // dd($req_limit , $lc_limit->limit_utilize , getType($req_limit) , getType($lc_limit->limit_utilize) ,  $req_limit + $lc_limit->limit_utilize); 
                    $limit_utilize = $req_limit + $lc_limit->limit_utilize;
                    $lc_limit->limit_utilize = $limit_utilize;
                    $lc_limit->save();

                }
                // $against_po->lc_no = $request->lc_no;
                // $against_po->lc_date = $request->lc_date;
            endif;
            $against_po->lc_no = $request->lc_no;
            $against_po->lc_date = $request->lc_date;
            $against_po->po_id = $request->po_id;
            $against_po->pi_no = $request->pi_no;
            $against_po->refrence_no = $request->refrence_no;
            $against_po->applicant_name = $request->applicant_name;
            $against_po->applicant_bank = $request->applicant_bank;
            $against_po->partial_shipment = $request->partial_shipment ?? 0;
            $against_po->transhipment = $request->transhipment ?? 0;
            $against_po->description = $request->descriptions;
            $against_po->sub_description = $request->sub_description;
            $against_po->buyer_name = $request->buyer_name ?? '';
            $against_po->beneficiary_name = $request->buyer_name ?? '';
            $against_po->buyer_id = $request->buyer_id ?? 0;
            $against_po->applicant_full_address = $request->applicant_full_address ?? '';
            $against_po->beneficiary_name = $request->beneficiary_name ?? '';
            $against_po->beneficiary_id = $request->beneficiary_id ?? 0;
            $against_po->beneficiary_full_address = $request->beneficiary_full_address ?? '';
            $against_po->advising_bank = $request->advising_bank ?? '';
            $against_po->advising_bank_id = $request->advising_bank_id ?? 0;
            $against_po->advising_bank_account_no = $request->advising_bank_account_no ?? 0;
            $against_po->advising_bank_swift_code = $request->advising_bank_swift_code ?? 0;
            $against_po->inter_mediary_bank = $request->inter_mediary_bank ?? '';
            $against_po->inter_mediary_bank_id = $request->inter_mediary_bank_id ?? 0;
            $against_po->inter_mediary_bank_account_no = $request->inter_mediary_bank_account_no ?? '';
            $against_po->inter_mediary_bank_swift_code = $request->inter_mediary_bank_swift_code ?? '';
            $against_po->Currency = $request->Currency ?? '';
            $against_po->Currency_id = $request->Currency_id ?? 0;
            $against_po->amount = $request->amount ?? 0;
            $against_po->fob = $request->fob ?? 0;
            $against_po->cfr = $request->cfr ?? 0;
            $against_po->cpt = $request->cpt ?? 0;
            $against_po->sight = $request->sight ?? 0;
            $against_po->shipment_from = $request->shipment_from ?? '';
            $against_po->shipment_to = $request->shipment_to ?? '';
            $against_po->latest_shipment_date = $request->latest_shipment_date ?? 0;
            $against_po->expirty_date = $request->expirty_date ?? 0;
            $against_po->days_from = $request->days_from ?? 0;
            $against_po->bl_date = $request->lc_lg_bl_date ?? '';
            $against_po->delivery_type = $request->delivery_type ?? '';
            $against_po->origin = $request->origin ?? '';
            $against_po->hs_code = $request->hs_code ?? 0;
            $against_po->insurance = $request->insurance ?? 0;
            $against_po->status = 1;
            $against_po->username = Auth::user()->name;
            $against_po->pkr_amount = $request->cd_total_pkr_amount ?? 0;
            $against_po->total_duty = $request->cd_total_duty ?? 0;
            $against_po->save();

            $master_id = $against_po->id;

            foreach ($request->item_id as $key => $item_id) {

                $against_po_data = new LcAndLgAgainstPoData();
                $against_po_data = $against_po_data->SetConnection('mysql2');


                $against_po_data->po_data_id = $request->po_data_id[$key];
                $against_po_data->master_id = $master_id;
                $against_po_data->item_id = $item_id;
                $against_po_data->qty = $request->qty[$key];
                $against_po_data->rate = $request->poRate[$key];
                $against_po_data->total_amount = $request->total_amount[$key];
                $against_po_data->ci_qty = $request->ci_qty[$key];
                $against_po_data->ci_rate = $request->ci_rate[$key];
                $against_po_data->ci_total_amount = $request->ci_total_value[$key];
                $against_po_data->hs_code_amount = $request->hs_code_amount[$key];
                $against_po_data->status = 1;
                $against_po_data->username = Auth::user()->name;

                $against_po_data->save();


            }

            $this->makeBLDetail($request, $master_id);
            $this->makeInsurenceDetail($request, $master_id);
            $this->makeGdDetail($request, $master_id);
            $this->makeShippingExpense($request, $master_id);
            $this->makeClearingAgent($request, $master_id);
            $this->makeMaturityDetail($request, $master_id);
            DB::Connection('mysql2')->commit();

            // return redirect()->back()->with('success', 'Record inserted successfully');
            return response()->json(['status' => true, 'message' => 'Record inserted successfully']);

        } catch (QueryException $e) {
            DB::Connection('mysql2')->rollback();
            // Log or handle the exception as needed
            // return redirect()->back()->withErrors('Error inserting record. Please try again.')->withInput();
            return response()->json(['status' => false, 'message' => 'Error inserting record. Please try again.']);
        }

    }


    public function makeBLDetail($request, $master_id)
    {
        foreach ($request->bl_no_bl_detail as $key => $value) {
            # code...
            BLDetail::create([
                'bl_so' => $request->bl_so[$key] ?? '',
                'lc_and_lg_against_po_id' => $master_id,
                'bl_no' => $request->bl_no_bl_detail[$key],
                'lot_no' => $request->lot_no_bl_detail[$key],
                'line' => $request->line_no[$key],
                'fwd' => $request->fwd_bl[$key],
                'by_sea' => $request->by_sea[$key],
                'bl_date' => $request->bl_date[$key],
                'bl_nbp' => $request->bl_nbp[$key] ? CommonHelper::check_str_replace($request->bl_nbp[$key]) : 0,
                'eta' => $request->eta[$key],
                'receving_date_factory' => $request->receving_date_factory[$key],
                'receving_no_factory' => $request->receving_no_factory[$key],
                'lcl' => $request->lcl[$key] ? CommonHelper::check_str_replace($request->lcl[$key]) : 0,
                'ft_20' => $request->ft_20[$key] ? CommonHelper::check_str_replace($request->ft_20[$key]) : 0,
                'ft_40' => $request->ft_40[$key] ? CommonHelper::check_str_replace($request->ft_40[$key]) : 0,
                'container' => $request->container[$key],
                'packege' => $request->packege[$key],
                'ioco' => $request->ioco[$key],
                'efs' => $request->efs[$key],
                'sch' => $request->sch[$key] ? CommonHelper::check_str_replace($request->sch[$key]) : 0,
                'normal' => $request->normal[$key],
                'new' => $request->new[$key] ? CommonHelper::check_str_replace($request->new[$key]) : 0,
                'gw' => $request->gw[$key] ? CommonHelper::check_str_replace($request->gw[$key]) : 0,
                'port_of_loading' => $request->port_of_loading[$key] ? CommonHelper::check_str_replace($request->port_of_loading[$key]) : 0,
                'shipment_status' => $request->shipment_status[$key] ?? '',
                'bl_remarks' => $request->bl_remarks[$key] ?? '',

            ]);
        }
    }
    public function makeInsurenceDetail($request, $master_id)
    {
        foreach ($request->policy_company as $key => $value) {

            InsuranceDetails::create([
                'i_so' => $request->i_so[$key] ?? '',
                'lc_and_lg_against_po_id' => $master_id,
                'policy_company' => $request->policy_company[$key],
                'lot_no' => $request->i_lot_no[$key],
                'cover_note' => $request->cover_note[$key],
                'cover_note_date' => $request->cover_note_date[$key],
                'tolerance' => $request->tolerance[$key],
                'policy_no' => $request->policy_no[$key],
                'policy_date' => $request->policy_date[$key],
                'policy_amount' => $request->policy_amount[$key],
                'remarks' => $request->i_remarks[$key],
                'policy_status' => $request->policy_status[$key],

            ]);
        }

    }
    public function makeGdDetail($request, $master_id)
    {
        foreach ($request->gd_no as $key => $value) {

            GDDetail::create([
                'gd_so' => $request->gd_so[$key] ?? '',
                'lc_and_lg_against_po_id' => $master_id,
                'gd_no' => $request->gd_no[$key],
                'lot_no' => $request->gd_lot_no[$key],
                'date' => $request->date[$key],
                'gd_rate' => $request->gd_rate[$key] ? CommonHelper::check_str_replace($request->gd_rate[$key]) : 0,
                'description' => $request->description[$key],
                'gd_new' => $request->gd_new[$key] ? CommonHelper::check_str_replace($request->gd_new[$key]) : 0,
                'gd_gw' => $request->gd_gw[$key] ?? 0,
                'uom' => $request->uom[$key] ? CommonHelper::check_str_replace($request->uom[$key]) : 0,
                'hs_code' => $request->gd_hs_code[$key],
                'gd_cfr_value' => $request->gd_cfr_value[$key] ? CommonHelper::check_str_replace($request->gd_cfr_value[$key]) : 0,
                'assessed_value' => $request->assessed_value[$key] ? CommonHelper::check_str_replace($request->assessed_value[$key]) : 0,
                'custome_duty_percent' => $request->custome_duty_percent[$key] ? CommonHelper::check_str_replace($request->custome_duty_percent[$key]) : 0,
                'custome_duty_amount' => $request->custome_duty_amount[$key] ? CommonHelper::check_str_replace($request->custome_duty_amount[$key]) : 0,
                'acd_percent' => $request->acd_percent[$key] ? CommonHelper::check_str_replace($request->acd_percent[$key]) : 0,
                'acd_amount' => $request->acd_amount[$key] ? CommonHelper::check_str_replace($request->acd_amount[$key]) : 0,
                'rd_percent' => $request->rd_percent[$key] ? CommonHelper::check_str_replace($request->rd_percent[$key]) : 0,
                'rd_amount' => $request->rd_amount[$key] ? CommonHelper::check_str_replace($request->rd_amount[$key]) : 0,
                'fed_percent' => $request->fed_percent[$key] ? CommonHelper::check_str_replace($request->fed_percent[$key]) : 0,
                'fed_amount' => $request->fed_amount[$key] ? CommonHelper::check_str_replace($request->fed_amount[$key]) : 0,
                'st_percent' => $request->st_percent[$key] ? CommonHelper::check_str_replace($request->st_percent[$key]) : 0,
                'st_amount' => $request->st_amount[$key] ? CommonHelper::check_str_replace($request->st_amount[$key]) : 0,
                'ast_percent' => $request->ast_percent[$key] ? CommonHelper::check_str_replace($request->ast_percent[$key]) : 0,
                'ast_amount' => $request->ast_amount[$key] ? CommonHelper::check_str_replace($request->ast_amount[$key]) : 0,
                'it_percent' => $request->it_percent[$key] ? CommonHelper::check_str_replace($request->it_percent[$key]) : 0,
                'it_amount' => $request->it_amount[$key] ? CommonHelper::check_str_replace($request->it_amount[$key]) : 0,
                'eto_percent' => $request->eto_percent[$key] ? CommonHelper::check_str_replace($request->eto_percent[$key]) : 0,
                'eto_amount' => $request->eto_amount[$key] ? CommonHelper::check_str_replace($request->eto_amount[$key]) : 0,

            ]);
        }

    }
    public function makeShippingExpense($request, $master_id)
    {
        foreach ($request->bl_no as $key => $value) {

            ShippingExpense::create([
                's_so' => $request->s_so[$key] ?? '',
                'lc_and_lg_against_po_id' => $master_id,
                'bl_no' => $request->bl_no[$key],
                'lot_no' => $request->s_lot_no[$key],
                'line' => $request->line[$key],
                'fwd' => $request->fwd[$key] ? CommonHelper::check_str_replace($request->fwd[$key]) : 0,
                'do_charges' => $request->do_charges[$key] ? CommonHelper::check_str_replace($request->do_charges[$key]) : 0,
                'lolo' => $request->lolo[$key] ? CommonHelper::check_str_replace($request->lolo[$key]) : 0,
                'port_charges' => $request->port_charges[$key] ? CommonHelper::check_str_replace($request->port_charges[$key]) : 0,
                'actual_port_charges' => $request->actual_port_charges[$key] ? CommonHelper::check_str_replace($request->actual_port_charges[$key]) : 0,
                'port_charges_balance' => $request->port_charges_balance[$key] ? CommonHelper::check_str_replace($request->port_charges_balance[$key]) : 0,
                'security_deposit' => $request->security_deposit[$key] ? CommonHelper::check_str_replace($request->security_deposit[$key]) : 0,
                'amount' => $request->s_amount[$key] ? CommonHelper::check_str_replace($request->s_amount[$key]) : 0,
                'deduction' => $request->s_deduction[$key] ? CommonHelper::check_str_replace($request->s_deduction[$key]) : 0,
                'refund_amount' => $request->refund_amount[$key] ? CommonHelper::check_str_replace($request->refund_amount[$key]) : 0,
                'cheque_no' => $request->cheque_no[$key] ? CommonHelper::check_str_replace($request->cheque_no[$key]) : 0,
                'cheque_date' => $request->cheque_date[$key],
                'remarks' => $request->s_remarks[$key],

            ]);
        }
    }


    public function makeMaturityDetail($request, $master_id)
    {
        foreach ($request->curreny_id as $key => $value) {

            MaturityDetail::create([
                'm_so' => $request->m_so[$key] ?? '',
                'lc_and_lg_against_po_id' => $master_id,
                'curreny_id' => $request->curreny_id[$key] ? CommonHelper::check_str_replace($request->curreny_id[$key]) : 0,
                'bank_doc_amount' => $request->bank_doc_amount[$key] ? CommonHelper::check_str_replace($request->bank_doc_amount[$key]) : 0,
                'rate' => $request->rate[$key] ? CommonHelper::check_str_replace($request->rate[$key]) : 0,
                'pkr' => $request->pkr[$key] ? CommonHelper::check_str_replace($request->pkr[$key]) : 0,
                'lot_no' => $request->m_lot_no[$key] ? CommonHelper::check_str_replace($request->m_lot_no[$key]) : '',
                'days' => $request->days[$key] ? CommonHelper::check_str_replace($request->days[$key]) : 0,
                'maturity_date' => $request->maturity_date[$key],
                'remarks' => $request->m_remarks[$key] ?? '',
            ]);
        }
    }


    public function makeClearingAgent($request, $master_id)
    {
        foreach ($request->c_so as $key => $value) {

            ClearingAgent::create([
                'c_so' => $request->c_so[$key] ?? '',
                'lc_and_lg_against_po_id' => $master_id,
                'clearing_agent_no' => $request->clearing_agent_no[$key] ?? null,
                'lot_no' => $request->c_lot_no[$key],
                'bill_no' => $request->bill_no[$key],
                'bill_date' => $request->bill_date[$key],
                'amount' => $request->c_amount[$key] ? CommonHelper::check_str_replace($request->c_amount[$key]) : 0,
                'deduction' => $request->c_deduction[$key] ? CommonHelper::check_str_replace($request->c_deduction[$key]) : 0,
                'paid_amount' => $request->paid_amount[$key] ? CommonHelper::check_str_replace($request->paid_amount[$key]) : 0,
                'remarks' => $request->c_remarks[$key],
                'shipment_clearing_days' => $request->shipment_clearing_days[$key] ?? 0,
                'transit_time' => $request->transit_time[$key],
            ]);
        }
    }

    public function removeExisting($id)
    {
        BLDetail::where('lc_and_lg_against_po_id', $id)->delete();
        ClearingAgent::where('lc_and_lg_against_po_id', $id)->delete();
        MaturityDetail::where('lc_and_lg_against_po_id', $id)->delete();
        ShippingExpense::where('lc_and_lg_against_po_id', $id)->delete();
        GDDetail::where('lc_and_lg_against_po_id', $id)->delete();
        InsuranceDetails::where('lc_and_lg_against_po_id', $id)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $LcAndLgAgainstPo = DB::connection('mysql2')->table('purchase_request as pr')
            ->join('lc_and_lg_against_po as lalap', 'pr.id', '=', 'lalap.po_id')
            ->where('lalap.status', 1)
            ->where('pr.status', 1)
            ->where('lalap.id', $id)
            ->select('lalap.*', 'pr.purchase_request_no')
            ->first();

        $LcAndLgAgainstPoData = DB::connection('mysql2')->table('lc_and_lg_against_po as lalap')
            ->join('lc_and_lg_against_po_data as lalapd', 'lalap.id', '=', 'lalapd.master_id')
            ->where('lalap.status', 1)
            ->where('lalapd.status', 1)
            ->where('lalap.id', $id)
            ->select('lalapd.*')
            ->get();

        return view('Import.LcAndLgAgainstPo.viewLcAndLgAgainstPo', compact('LcAndLgAgainstPo', 'LcAndLgAgainstPoData'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function import_ppi(Request $request)
    {
        $PurchaseRequest = new PurchaseRequest();
        $PurchaseRequest = $PurchaseRequest->SetConnection('mysql2')->where('status', 1)->get();



        if ($request->ajax()) {

            return view('Import.LcAndLgAgainstPo.createLcAndLgAgainstPo_new', compact('purchase_request_data', 'PurchaseRequest'));
        }
        return view('Import.LcAndLgAgainstPo.createLcAndLgAgainstPo_new', compact('PurchaseRequest'));
    }

    public function viewSecurityDepostiLetter(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $shipping_detail = DB::connection('mysql2')->table('shipping_expenses as sp')
            ->join('lc_and_lg_against_po as lc', 'lc.id', 'sp.lc_and_lg_against_po_id')
            ->join('purchase_request as p', 'p.id', 'lc.po_id')
            ->join('bl_details as bl', 'bl.lc_and_lg_against_po_id', 'lc.id')
            ->join('clearing_agents as c', 'c.lc_and_lg_against_po_id', 'lc.id')
            ->select(
                'lc.refrence_no',
                'p.purchase_request_no',
                'lc.lc_no',
                'lc.beneficiary_id',
                'lc.description',
                'sp.bl_no',
                'bl.container',
                'bl.lcl',
                'bl.ft_20',
                'bl.ft_40',
                'sp.lot_no',
                'c.clearing_agent_no',
                'sp.amount',
                'sp.deduction',
                'sp.refund_amount',
                'sp.cheque_no',
                'sp.cheque_date',
                'sp.remarks',
                'sp.port_charges',
                'lc.username'
            )
            ->where('sp.id', $id)
            ->first();
        // dd($shipping_detail);
        return view('Import.LcAndLgAgainstPo.ajax.viewSecurityDepostiLetter', compact('shipping_detail'));
    }

    public function createPayOrderForm(Request $request)
    {
        // dd($request);
        $lc_lg_data = DB::connection('mysql2')->table('lc_and_lg_against_po as lc')
            ->join('bl_details as b', 'b.lc_and_lg_against_po_id', 'lc.id')
            ->join('clearing_agents as c', 'c.lc_and_lg_against_po_id', 'lc.id')
            ->select('lc.*', 'b.*', 'c.*', 'sum(b.new) as total_net_weight')
            ->get();
        return view('Import.LcAndLgAgainstPo.ajax.createPayOrderForm', compact('lc_lg_data'));
    }
}
