<?php
use App\Helpers\CommonHelper;
use App\Helpers\ImportHelper;



$applicant_data = DB::table('company')->where('id' , Auth::user()->company_id)->first();
$beneficiary_data = DB::connection('mysql2')->table('supplier')
->leftJoin('supplier_info','supplier_info.supp_id','=','supplier.id')
->where('supplier.id' , $purchase_request_data[0]->supplier_id)->first();
$subitemData = DB::connection('mysql2')->table('subitem')
->leftJoin('sub_category','sub_category.id','=','subitem.sub_category_id')
->leftJoin('category','category.id','=','sub_category.category_id')
->where('subitem.id' , $purchase_request_data[0]->supplier_id)->select('category.main_ic','sub_category.sub_category_name')->first();
// $applicant_bank = CommonHelper::get_all_account_operat_with_unique_code('1-2-8');
$applicant_bank = DB::connection('mysql2')->table('lc_and_lg as l')
                    ->join('accounts as a', 'a.id', '=', 'l.acc_id')
                    ->where('a.status', 1)
                    ->where('l.status', 1)
                    ->select('l.id', 'a.name', 'l.limit', 'l.type')->orderBy('l.id', 'desc')->get();
                    // dd($applicant_bank);

$result_hs_code = DB::connection('mysql2')->table('subitem as s')
    ->join('hs_codes as h', 'h.id', '=', 's.hs_code_id')
    ->select('h.description')
    ->first();

$hs_code_description = $result_hs_code ? $result_hs_code->description : null;
// dd($beneficiary_data , $purchase_request_data[0]->supplier_id ,$description);
// dd($hs_code_description);


// $buyer_name = $purchase_request_data[0]->buyer_name ?? $applicant_data->name ;
// $applicant_full_address = $purchase_request_data[0]->buyer_name ?? $applicant_data->address;
$buyer_name = $purchase_request_data[0]->buyer_name ?? 'Zahabiya Chemicals Industries (Pvt) Limited' ;
$applicant_full_address = $purchase_request_data[0]->buyer_name ?? "43-E, Block-6, P.E.C.H.S., Behind FedEx, Off. Razi Road, Shahrah-e-Faisal, Karachi-Pakistan.	";
// $buyer_name = $purchase_request_data[0]->buyer_name ?? $applicant_data->name ;
// $applicant_full_address = $purchase_request_data[0]->applicant_full_address ?? $applicant_data->address;
$pi_no = $purchase_request_data[0]->pi_no ?? '';
$lc_no = $purchase_request_data[0]->lc_no ?? CommonHelper::generateUniquePosNo('lc_and_lg_against_po','lc_no','LC');
$lc_date = $purchase_request_data[0]->lc_date ?? date('Y-m-d');
$refrence_no = $purchase_request_data[0]->refrence_no ?? '';
$applicant_bank  = $applicant_bank  ?? '';
$beneficiary_name = $purchase_request_data[0]->beneficiary_name ?? $beneficiary_data->name ?? $buyer_name;
$beneficiary_id = $beneficiary_data->id ?? '';
$beneficiary_full_address = $purchase_request_data[0]->beneficiary_full_address ??  $beneficiary_data->address ?? '';
// $beneficiary_name = $purchase_request_data[0]->beneficiary_name ?? $beneficiary_data->name;
// $beneficiary_id = $purchase_request_data[0]->beneficiary_id ?? $beneficiary_data->id ;
// $beneficiary_full_address = $purchase_request_data[0]->beneficiary_full_address ?? $beneficiary_data->address;
// dd($purchase_request_data);
$item_id = array_column($purchase_request_data->toArray(), 'sub_item_id');
$item_description_name = CommonHelper::item_name_array($item_id);
$item_description_name = implode( ' , ',$item_description_name);
$description = $purchase_request_data[0]->lc_lg_description ?? $item_description_name ?? '';
$sub_description = $purchase_request_data[0]->lc_lg_sub_description ?? $item_description_name ?? '';
// $description = $hs_code_description ?? '';
// $sub_description = $hs_code_description ?? '';
// $description = $purchase_request_data[0]->lc_lg_description ?? $subitemData->main_ic ;
// $sub_description = $purchase_request_data[0]->sub_description ?? $subitemData->sub_category_name ;
$advising_bank = $purchase_request_data[0]->advising_bank ?? '';
$advising_bank_id = $purchase_request_data[0]->advising_bank_id ?? '';
$advising_bank_account_no = $purchase_request_data[0]->advising_bank_account_no ?? '';
$advising_bank_swift_code = $purchase_request_data[0]->advising_bank_swift_code ?? '';
$inter_mediary_bank = $purchase_request_data[0]->inter_mediary_bank ?? '';
$inter_mediary_bank_id = $purchase_request_data[0]->inter_mediary_bank_id ?? '';
$inter_mediary_bank_account_no = $purchase_request_data[0]->inter_mediary_bank_account_no ?? '';
$inter_mediary_bank_swift_code = $purchase_request_data[0]->inter_mediary_bank_swift_code ?? '';
$Currency = $purchase_request_data[0]->Currency ?? '';
$Currency_id = $purchase_request_data[0]->Currency_id ?? '';
$amount = $purchase_request_data[0]->amount ?? '';
$partial_shipment = $purchase_request_data[0]->partial_shipment ?? '';
$transhipment = $purchase_request_data[0]->transhipment ?? '';
$fob = $purchase_request_data[0]->fob ?? '';
$cfr = $purchase_request_data[0]->cfr ?? '';
$cpt = $purchase_request_data[0]->cpt ?? '';
$sight = $purchase_request_data[0]->sight ?? '';
$shipment_from = $purchase_request_data[0]->shipment_from ?? '';
$shipment_to = $purchase_request_data[0]->shipment_to ?? '';
$latest_shipment_date = $purchase_request_data[0]->latest_shipment_date ?? '';
$expirty_date = $purchase_request_data[0]->expirty_date ?? '';
// $days_from = $purchase_request_data[0]->terms_of_paym ?? '';
$days_from = $purchase_request_data[0]->days_from ?? $purchase_request_data[0]->terms_of_paym;
$lc_lg_bl_date = $purchase_request_data[0]->lc_lg_bl_date ?? '';
$delivery_type = $purchase_request_data[0]->delivery_type ?? '';
$origin = $purchase_request_data[0]->origin ?? '';
$hs_code = $purchase_request_data[0]->lc_lg_hs_code ?? implode(' , ' , ImportHelper::get_hs_code_array(array_column($purchase_request_data->toArray() , 'sub_item_id')));
$insurance = $purchase_request_data[0]->insurance ?? '';



// Bl Detail
// $b_bl_no = $purchase_request_data[0]->b_bl_no ?? '';
// $b_lot_no = $purchase_request_data[0]->b_lot_no ?? '';
// $b_line = $purchase_request_data[0]->b_line ?? '';
// $b_fwd = $purchase_request_data[0]->b_fwd ?? '';
// $by_sea = $purchase_request_data[0]->by_sea ?? '';
// $bl_date = $purchase_request_data[0]->bl_date ?? '';
// $bl_nbp = $purchase_request_data[0]->bl_nbp ?? '';
// $eta = $purchase_request_data[0]->eta ?? '';
// $receving_date_factory = $purchase_request_data[0]->receving_date_factory ?? '';
// $receving_no_factory = $purchase_request_data[0]->receving_no_factory ?? '';
// $lcl = $purchase_request_data[0]->lcl ?? '';
// $ft_20 = $purchase_request_data[0]->ft_20 ?? '';
// $ft_40 = $purchase_request_data[0]->ft_40 ?? '';
// $container = $purchase_request_data[0]->container ?? '';
// $packege = $purchase_request_data[0]->packege ?? '';
// $ioco = $purchase_request_data[0]->ioco ?? '';
// $efs = $purchase_request_data[0]->efs ?? '';
// $sch = $purchase_request_data[0]->sch ?? '';
// $normal = $purchase_request_data[0]->normal ?? '';
// $new = $purchase_request_data[0]->new ?? '';
// $gw = $purchase_request_data[0]->gw ?? '';
// $port_of_loading = $purchase_request_data[0]->port_of_loading ?? '';
// $shipment_status = $purchase_request_data[0]->shipment_status == 1 ? 'Received at Factory': '';

// // insurance detail
// $i_lot_no = $purchase_request_data[0]->i_lot_no ?? '';
// $i_policy_company = $purchase_request_data[0]->i_policy_company ?? '';
// $i_cover_note = $purchase_request_data[0]->i_cover_note ?? '';
// $i_tolerance = $purchase_request_data[0]->i_tolerance ?? '';
// $i_policy_no = $purchase_request_data[0]->i_policy_no ?? '';
// $i_remarks = $purchase_request_data[0]->i_remarks ?? '';
// $i_policy_status = $purchase_request_data[0]->i_policy_status ?? '';

// //gd detail
// $gd_no = $purchase_request_data[0]->gd_no ?? '';
// $gd_lot_no = $purchase_request_data[0]->gd_lot_no ?? '';
// $gd_date = $purchase_request_data[0]->gd_date ?? '';
// $gd_rate = $purchase_request_data[0]->gd_rate ?? '';
// $gd_description = $purchase_request_data[0]->gd_description ?? '';
// $gd_new = $purchase_request_data[0]->gd_new ?? '';
// $gd_gw = $purchase_request_data[0]->gd_gw ?? '';
// $gd_uom	 = $purchase_request_data[0]->uom?? '';
// $gd_hs_code = $purchase_request_data[0]->gd_hs_code ?? '';
// $gd_cfr_value = $purchase_request_data[0]->gd_cfr_value ?? 0;
// $assessed_value = $purchase_request_data[0]->assessed_value ?? 0;
// $custome_duty_percent = $purchase_request_data[0]->custome_duty_percent ?? 0;
// $custome_duty_amount = $purchase_request_data[0]->custome_duty_amount ?? 0;
// $acd_percent = $purchase_request_data[0]->acd_percent ?? 0;
// $acd_amount = $purchase_request_data[0]->acd_amount ?? 0;
// $rd_percent = $purchase_request_data[0]->rd_percent ?? 0;
// $rd_amount = $purchase_request_data[0]->rd_amount ?? 0;
// $fed_percent = $purchase_request_data[0]->fed_percent ?? 0;
// $fed_amount = $purchase_request_data[0]->fed_amount ?? 0;
// $st_percent = $purchase_request_data[0]->st_percent ?? 0;
// $st_amount = $purchase_request_data[0]->st_amount ?? 0;
// $ast_percent = $purchase_request_data[0]->ast_percent ?? 0;
// $ast_amount = $purchase_request_data[0]->ast_amount ?? 0;
// $it_percent = $purchase_request_data[0]->it_percent ?? 0;
// $it_amount = $purchase_request_data[0]->it_amount ?? 0;
// $eto_percent = $purchase_request_data[0]->eto_percent ?? 0;
// $eto_amount = $purchase_request_data[0]->eto_amount ?? 0;

// $total_duty = $custome_duty_amount + $acd_amount + $rd_amount + $fed_amount + $st_amount + $ast_amount + $it_amount + $eto_amount;

// // shipping expense
// $s_bl_no = $purchase_request_data[0]->s_bl_no ?? '';
// $s_lot_no = $purchase_request_data[0]->s_lot_no ?? '';
// $line = $purchase_request_data[0]->line ?? '';
// $fwd = $purchase_request_data[0]->fwd ?? '';
// $do_charges = $purchase_request_data[0]->do_charges ?? 0;
// $lolo = $purchase_request_data[0]->lolo ?? '';
// $port_charges = $purchase_request_data[0]->port_charges ?? 0;
// $actual_port_charges = $purchase_request_data[0]->actual_port_charges ?? 0;
// $port_charges_balance = $purchase_request_data[0]->port_charges_balance ?? 0;
// $security_deposit = $purchase_request_data[0]->security_deposit ?? 0;
// $s_amount = $purchase_request_data[0]->s_amount ?? 0;
// $s_deduction = $purchase_request_data[0]->s_deduction ?? 0;
// $s_refund_amount = $purchase_request_data[0]->s_refund_amount ?? 0;
// $cheque_no = $purchase_request_data[0]->cheque_no ?? '';
// $cheque_date = $purchase_request_data[0]->cheque_date ?? '';
// $s_remarks = $purchase_request_data[0]->s_remarks ?? '';


// //CLEARING AGENT BILL DETAIL
// $clearing_agent_no = $purchase_request_data[0]->clearing_agent_no ?? '';
// $c_lot_no = $purchase_request_data[0]->c_lot_no ?? '';
// $bill_no = $purchase_request_data[0]->bill_no ?? '';
// $bill_date = $purchase_request_data[0]->bill_date ?? '';
// $c_amount = $purchase_request_data[0]->c_amount ?? 0;
// $c_deduction = $purchase_request_data[0]->c_deduction ?? 0;
// $c_paid_amount = $purchase_request_data[0]->c_paid_amount ?? 0;
// $c_remarks = $purchase_request_data[0]->c_remarks ?? '';
// $shipment_clearing_days = $purchase_request_data[0]->shipment_clearing_days ?? '';
// $transit_time = $purchase_request_data[0]->transit_time ?? '';


// //MATURITY DETAIL
// $curreny_id = $purchase_request_data[0]->curreny_id ?? '';
// $bank_doc_amount = $purchase_request_data[0]->bank_doc_amount ?? 0;
// $m_rate = $purchase_request_data[0]->m_rate ?? '';
// $pkr = $purchase_request_data[0]->pkr ?? '';
// $m_lot_no = $purchase_request_data[0]->m_lot_no ?? '';
// $days = $purchase_request_data[0]->days ?? '';
// $maturity_date = $purchase_request_data[0]->maturity_date ?? '';
// $m_remarks = $purchase_request_data[0]->m_remarks ?? '';

$bl_data = null;
if ($purchase_request_data[0]->lc_and_lg_against_po_id) {
    
    $bl_data = db::Connection('mysql2')->table('bl_details')
    ->where('lc_and_lg_against_po_id',$purchase_request_data[0]->lc_and_lg_against_po_id)
    ->get();
}
$shipment_status = null;
if ($bl_data) {
    $shipment_status = array_column($bl_data->toArray() , 'shipment_status'); 
    $shipment_status = implode(" , " , $shipment_status);
}
?>

<style>
 .my-lab label{padding-top:0px;}
.head_ship{text-align:center;border:1px solid #000;margin-bottom:50px;}
.head_ship h2{color:#000;}
.lac{border-bottom:1px solid #000;margin-bottom:70px;}
.lac2 h2{  color: #000;}
.flex_lable{display:flex;align-items:center;}
.lable_text{width:267px;text-align:left;}
.lac3{display:flex;gap:70px;}
.paid{display:flex;gap:20px;align-items:center;margin-bottom:30px;}
.lac3 h2{font-size:15px;color:#000;}
.paid2{display:flex;gap:50px;}
.paid2 h2{border:2px solid #000;padding:3px 6px;}
.bldetailes{margin-bottom:20px;align-items:center;}
.Import_Lc_PII{width:100%;overflow-x:scroll;scroll-behavior:smooth;scrollbar-width:thin;}
input:read-only{background-color: rgb(199, 199, 199);}


</style>

<?php


?>
    <div class="row  align-items-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="cus-ul import_head_lc">
                <li>
                    <h1>Import</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;{{$purchase_request_data[0]->lc_and_lg_against_po_id ? 'Update' : 'Create'}} Lc and Lg Against PO</h3>
                </li>
            </ul>
        </div>
        <!-- <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">

        </div> -->
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N_create_lc_and_Lg">
                <div class="dp_sdw2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body create_lc_and_panel_body">
                                    <form id="LcAndLgAgainstPo" action="{{route('LcAndLgAgainstPo.store')}}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab2">
                                                <div class=" qout-h">
                                                    <!-- <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Purchase Request* </label>
                                                            <select onchange="nextForm()" name="po_id" class="form-control" id="po_id">
                                                                <option value=""> Select Purchase Request</option>
                                                                {{-- @foreach($PurchaseRequest as $key => $value)

                                                                <option value="{{ $value->id }}" > {{ $value->purchase_request_no }} </option>
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div> -->

                                                    <div class="col-md-12 padt pos-r" id="nextForm">

                                                    <div class="row">

                                                        <div class="col-md-4"> </div>
                                                        <div class="col-md-4">
                                                            <div class="head_ship">
                                                                <h2>Shipment status </h2>
                                                                <p id="all_shipment_status">{{$shipment_status}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4"> </div>

                                                        <!-- Lc Detail -->
                                                        <div class="col-md-12">
                                                            <div class="lac">
                                                                <h2>Lc Detail</h2>
                                                                
                                                                <div class="float-right" style="float: right">
                                                                     <div class="form-group">
                                                                        <div class="flex_lable">
                                                                            <div class="lable_text">
                                                                                <label class="control-label">Copy data from PO</label>
                                                                            </div>
                                                                            <select onchange="getPoData();" class="form-control" id="po_data">
                                                                                <option value=""> Select Purchase Request</option>
                                                                                @foreach($PurchaseRequest as $key => $value)
                                                                                <option value="{{ $value->id }}" > {{ $value->purchase_request_no }} </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="row" id="PoData">
                                                            <!-- PO Number  -->
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">LC NO #</label>
                                                                        </div>
                                                                        <input readonly name="lc_no"  value="{{ $lc_no }}" id="lc_no" class="form-control" type="text">
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">LC Date #</label>
                                                                        </div>
                                                                        <input readonly name="lc_date"  value="{{$lc_date}}" id="lc_date" class="form-control" type="date"   >
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">PO Number </label>
                                                                        </div>
                                                                        <input name="po_no" value="{{ $purchase_request_data[0]->purchase_request_no }}" id="po_no" class="form-control" type="text">
                                                                        <input name="po_id" value="{{ $purchase_request_data[0]->master_id }}" id="po_id" class="form-control" type="hidden">

                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">PI Number </label>
                                                                        </div>
                                                                        <input name="pi_no" value="{{ $pi_no }}" id="pi_no" class="form-control" type="text">
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">Reference #</label>
                                                                        </div>
                                                                        <input name="refrence_no"  value="{{ $refrence_no }}" id="refrence_no" class="form-control" type="text">
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">Applicant Name</label>
                                                                        </div>
                                                                        <input name="buyer_name" value="{{ $buyer_name }}" id="buyer_name" class="form-control" type="text">
                                                                        <input name="buyer_id"  id="buyer_id" class="form-control" type="hidden">
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="flex_lable">

                                                                        <div class="lable_text">
                                                                            <label class="control-label">Applicant Full Address </label>
                                                                        </div>
                                                                        <textarea name="applicant_full_address" class="form-control" cols="30" >{{ $applicant_full_address }}</textarea>
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                      <label class="control-label">Beneficiary Name</label>
                                                                      </div>
                                                                        <input name="beneficiary_name" value="{{ $beneficiary_name }}" id="beneficiary_name" class="form-control" type="text">
                                                                        <input name="beneficiary_id" id="beneficiary_id" value="{{ 	$beneficiary_id  }}" class="form-control" type="hidden">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                      <label class="control-label">Beneficiary Full address</label>
                                                                      </div>
                                                                      <textarea name="beneficiary_full_address" class="form-control" cols="30" >{{ $beneficiary_full_address }}</textarea>                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                      <label class="control-label">Description</label>
                                                                      </div>
                                                                      <input name="descriptions" value="{{ $description }}" id="descriptions" class="form-control" type="text">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                          <div class="lable_text">

                                                                            <label class="control-label">Currency</label>
                                                                        </div>

                                                                        <input name="Currency" id="Currency" class="form-control" type="text" value="{{CommonHelper::get_curreny_name( $purchase_request_data[0]->currency_id)}}">
                                                                        <input name="Currency_id"  id="Currency_id" class="form-control" type="hidden" value="{{$purchase_request_data[0]->currency_id}}">
                                                                    </div>

                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="flex_lable">
                                                                              <div class="lable_text">

                                                                                <label class="control-label">Amount</label>
                                                                            </div>
                                                                            <input name="amount" value="{{ $amount }}" id="d_t_amount_1" class="form-control" type="text">
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="flex_lable">
                                                                              <div class="lable_text">

                                                                                <label class="control-label">Amount in words</label>
                                                                            </div>
                                                                            <input name="insurance" id="rupees" class="form-control" type="text">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="flex_lable">
                                                                              <div class="lable_text">

                                                                                <label class="control-label">Insurance</label>
                                                                            </div>
                                                                            <input value="{{ $insurance }}" name="insurance" id="insurance"  class="form-control" type="text">
                                                                        </div>
                                                                    </div>



                                                            </div>
                                                            <!-- Advising Bank -->
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <div class="flex_lable ">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">Applicant Bank </label>
                                                                        </div>
                                                                        {{-- <input name="applicant_bank" value="" id="applicant_bank" value="{{ $applicant_bank }}" class="form-control" type="text"> --}}
                                                                        <select name="applicant_bank" class="form-control" id="applicant_bank">
                                                                            @foreach ($applicant_bank as $applicant_bank)
                                                                            <option value="{{ $applicant_bank->id}}" {{$purchase_request_data[0]->applicant_bank && $purchase_request_data[0]->applicant_bank ==  $applicant_bank->id ? 'selected' : ''}}>{{ $applicant_bank->name}}({{$applicant_bank->type}})</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">

                                                                        <div class="lable_text">
                                                                            <label class="control-label">Advising Bank </label>

                                                                        </div>
                                                                        <input name="advising_bank" id="advising_bank" value="{{ $advising_bank }}" class="form-control" type="text">
                                                                        <input name="advising_bank_id" value="{{ $advising_bank_id }}" id="advising_bank_id" class="form-control" type="hidden">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">

                                                                        <div class="lable_text">
                                                                            <label class="control-label">Account No </label>
                                                                        </div>
                                                                        <input name="advising_bank_account_no" value="{{ $advising_bank_account_no }}" id="advising_bank_account_no" class="form-control" type="text">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">Swift code </label>
                                                                        </div>
                                                                        <input name="advising_bank_swift_code" value="{{ $advising_bank_swift_code }}" id="advising_bank_swift_code" class="form-control" type="text">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">Inter mediary Bank</label>
                                                                        </div>
                                                                        <input name="inter_mediary_bank" id="inter_mediary_bank"  value="{{ $inter_mediary_bank }}" class="form-control" type="text">
                                                                        <input name="inter_mediary_bank_id" value="{{ $inter_mediary_bank_id }}" id="inter_mediary_bank_id" class="form-control" type="hidden">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">

                                                                        <div class="lable_text">
                                                                            <label class="control-label">Account No </label>
                                                                        </div>
                                                                        <input name="inter_mediary_bank_account_no"  id="inter_mediary_bank_account_no" value="{{ $inter_mediary_bank_account_no }}" class="form-control" type="text">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                    <label class="control-label">Swift code </label>
                                                                    </div>
                                                                    <input name="inter_mediary_bank_swift_code" value="{{ $advising_bank_swift_code }}" id="inter_mediary_bank_swift_code" class="form-control" type="text">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                    <label class="control-label">Sub Description</label>
                                                                    </div>
                                                                    <input name="sub_description" id="sub_description" value="{{$sub_description}}" class="form-control" type="text">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">

                                                                            <label class="control-label">Partail shipment</label>
                                                                        </div>
                                                                        <input name="partial_shipment"  id="partial_shipment" class="" type="checkbox" value="1" {{$partial_shipment == 1 ? 'checked' : ''}}>
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                            <div class="lable_text">

                                                                            <label class="control-label">Transhipment</label>
                                                                        </div>
                                                                        <input name="transhipment"   id="transhipment" class="" type="checkbox" value="1" {{$transhipment == 1 ? 'checked' : ''}}>                                                                        </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                            <div class="lable_text">

                                                                            <label class="control-label">Shipment From</label>
                                                                        </div>
                                                                        <input name="shipment_from" value="{{ $shipment_from }}" id="shipment_from" class="form-control" type="text">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                            <div class="lable_text">

                                                                            <label class="control-label">Shipment To</label>
                                                                        </div>
                                                                        <input name="shipment_to" value="{{ $shipment_to }}" id="shipment_to" class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- FOB -->
                                                            <div class="col-md-4">


                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">FOB </label>
                                                                        </div>
                                                                        <input name="fob" value="1" id="fob" class="form-control checkBox" onclick="checkBox(this)" type="checkbox" {{$fob==1 ? 'checked' : ''}}>
                                                                    </div>
                                                                </div>


                                                                <div class="form-group">
                                                                    <div class="flex_lable">

                                                                        <div class="lable_text">
                                                                            <label class="control-label">CFR</label>
                                                                        </div>
                                                                        <input name="cfr" value="1" id="cfr" class="form-control checkBox" type="checkbox" onclick="checkBox(this)" {{$cfr==1 ? 'checked' : ''}}>
                                                                    </div>

                                                                </div>


                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">CPT</label>
                                                                        </div>
                                                                        <input name="cpt" value="1" id="cpt" class="form-control checkBox" type="checkbox" onclick="checkBox(this)" {{$cpt==1 ? 'checked' : ''}}>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">Lc At Sight </label>
                                                                        </div>
                                                                        <input name="sight" value="1" id="sight" class="form-control checkBox" type="checkbox" onclick="checkBox(this)" {{$sight==1 ? 'checked' : ''}}>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group hide">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                        <label class="control-label">BL DATE</label>
                                                                        </div>
                                                                        <input name="lc_lg_bl_date" value="{{ $lc_lg_bl_date }}" id="lc_lg_bl_date" class="form-control" type="date" >
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label">Days from</label>
                                                                        </div>
                                                                        <input name="days_from" value="{{ $days_from }} Days from BL DATE" id="days_from" class="form-control" type="text">
                                                                    </div>
                                                                </div>


                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label"> </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                            <label class="control-label"> </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                            <div class="lable_text">

                                                                            <label class="control-label">Origin</label>
                                                                        </div>
                                                                        <input name="origin" value="{{ $origin }}" id="origin" class="form-control" type="text">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                            <div class="lable_text">

                                                                            <label class="control-label">Hs Code</label>
                                                                        </div>
                                                                        <input name="hs_code" value="{{ $hs_code }}"  step="any"  id="hs_code" class="form-control" type="text">
                                                                    </div>
                                                                </div>



                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                        <div class="lable_text">
                                                                      <label class="control-label">Latest Shipment date</label>
                                                                      </div>
                                                                      <input name="latest_shipment_date" value="{{ $latest_shipment_date }}" id="latest_shipment_date" class="form-control" type="date" >
                                                                    </div>

                                                                </div>


                                                                <div class="form-group">
                                                                    <div class="flex_lable">
                                                                            <div class="lable_text">

                                                                            <label class="control-label">Expirty Date</label>
                                                                        </div>
                                                                        <input name="expirty_date" value="{{ $expirty_date }}" id="expirty_date" class="form-control" type="date" >
                                                                    </div>

                                                                </div>





                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <div class="row">

                                                            <!-- Description of Goods -->
                                                            <div class="col-md-12">
                                                                <div class="lac2">
                                                                    <h2>Description of Goods</h2>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive    Import_Lc_PII">
                                                                    <?php
                                                                    $counter1 = 1;
                                                                    $all_total=0;
                                                                    ?>

                                                                    <table class="table table-bordered sf-table-list">
                                                                        <thead>
                                                                            <tr>

                                                                                <th class="text-center">Sr No</th>
                                                                                <th class="text-center" colspan="1">Item Name</th>
                                                                                <th class="text-center" >Quantity (PI)</th>
                                                                                <th class="text-center" >Unit</th>
                                                                                <th class="text-center" >Rate (PI)</th>
                                                                                <th class="text-center" >Total Value (PI)</th>
                                                                                <th class="text-center hide" >Hs Code amount</th>
                                                                                <th class="text-center" >Hs Code</th>
                                                                                <th class="text-center" >Quantity (CI)</th>
                                                                                <th class="text-center" >Rate (CI)</th>
                                                                                <th class="text-center" >Total Value (CI)</th>
                                                                                <th class="text-center" >DIFF QTY (CI/PI)</th>
                                                                                <th class="text-center" >DIFF Rate (CI/PI)</th>
                                                                                <th class="text-center" >DIFF Total Value (CI/PI)</th>
                                                                            </tr>

                                                                        </thead>
                                                                        <tbody id="append_dg">
                                                                            <?php
                                                                                $goods_total_quantity = 0;
                                                                                ?>
                                                                        @foreach($purchase_request_data as $key => $value)

                                                                        <?php
                                                                        $hs_code_data = ImportHelper::get_hs_code_data($value->sub_item_id);
                                                                        $duty = $hs_code_data->total_duty_with_taxes ?? 0;
                                                                        $amount  =$value->purchase_approve_qty * $value->po_rate;
                                                                        $hs_code_amount = ($amount * $duty) /100;
                                                                        
                                                                        $lc_and_lg_against_po_data = null;
                                                                        if ($value->lc_and_lg_against_po_id) {
                                                                            $lc_and_lg_against_po_data = DB::connection('mysql2')->table('lc_and_lg_against_po_data')
                                                                            ->where('po_data_id' , $value->po_data_id)->first();
                                                                        }


                                                                 

                                                                        ?>


                                                                            <tr>
                                                                                <td>{{ $counter1 }}</td>
                                                                                <td>
                                                                                    {{ CommonHelper::getCompanyDatabaseTableValueById(Session::get('run_company'),'subitem','sub_ic',$value->sub_item_id) }}
                                                                                    <input type="hidden" name="item_id[]" id="item_id{{$counter1}}" value="{{ $value->sub_item_id }}">
                                                                                    <input type="hidden" name="po_data_id[]" id="po_data_id{{$counter1}}"   value="{{ $value->po_data_id }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" step="any" class="qty" onkeyup="calculation_po({{$counter1}})"  min="0" name="qty[]" id="qty{{$counter1}}" value="{{ $lc_and_lg_against_po_data->qty ?? $value->purchase_request_qty }}">
                                                                                </td>
                                                                                <td>{{  ImportHelper::get_uom_name($value->sub_item_id) }}</td>
                                                                                <td>
                                                                                    <input type="text" step="any" class="pi_rate" onkeyup="calculation_po({{$counter1}})"  name="poRate[]" id="rate{{$counter1}}" value="{{ number_format($lc_and_lg_against_po_data->rate ?? $value->rate,2) }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text"  step="any" class="total_amount"  name="total_amount[]" id="total_amount{{$counter1}}" value="{{ number_format($lc_and_lg_against_po_data->amount ?? $value->amount,2) }}">
                                                                                </td>
                                                                                <td class="hide">
                                                                                    <input type="number" class="hs_code_amount" name="hs_code_amount[]"  step="any"  id="hs_code_amount{{ $counter1 }}" value="{{ number_format($hs_code_amount,2) }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="dg_hs_code" name="dg_hs_code[]"  step="any"  id="dg_hs_code{{ $counter1 }}" value="{{ $hs_code_data->hs_code ?? '' }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" step="any"  name="ci_qty[]" class="ci_qty" onkeyup="pi_ci_cal()" value="{{ $lc_and_lg_against_po_data->ci_qty ?? $value->purchase_request_qty }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" step="any"  name="ci_rate[]" class="ci_rate" onkeyup="pi_ci_cal()" value="{{ $lc_and_lg_against_po_data->ci_rate ?? $value->rate }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" step="any"  name="ci_total_value[]" class="ci_total_value" value="{{ $lc_and_lg_against_po_data->ci_total_amount ?? $value->amount }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" step="any"  name="diff_qty[]" class="diff_qty" value="{{$lc_and_lg_against_po_data ? $lc_and_lg_against_po_data->qty - $lc_and_lg_against_po_data->ci_qty : 0}}" readonly>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" step="any"  name="diff_rate[]" class="diff_rate" value="{{$lc_and_lg_against_po_data ? $lc_and_lg_against_po_data->rate - $lc_and_lg_against_po_data->ci_rate : 0}}" readonly>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" step="any"  name="diff_total_value[]" class="diff_total_value" value="{{$lc_and_lg_against_po_data ? $lc_and_lg_against_po_data->total_amount - $lc_and_lg_against_po_data->ci_total_amount : 0}}" readonly>
                                                                                </td>
                                                                            </tr>
                                                                            @php
                                                                            $goods_total_quantity += intval($value->purchase_request_qty);
                                                                                $counter1 ++;
                                                                            @endphp
                                                                        @endforeach
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                Total
                                                                            </td>
                                                                            <td id="goods_total_quantity">{{$goods_total_quantity}}</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td id="goods_total_amount"></td>
                                                                        </tr>
                                                                        <script>
                                                                            var hsCodeData = @json($hs_code_data);



                                                                      </script>
                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <!-- costing -->

                                                        <div class="row">


                                                            <div class="col-md-12">
                                                                <div class="lac3">
                                                                    <h2>Costing Detail</h2>
                                                                    <div class="paid">
                                                                        <button class="btn btn-primary" type="button"
                                                                            onclick="{{ $purchase_request_data[0]->lc_and_lg_against_po_id ? "showDetailModelOneParamerter('import/LcAndLgAgainstPo/createPayOrderForm', '" . $purchase_request_data[0]->lc_and_lg_against_po_id . "', 'Create PayOrder Form')" : '' }}">
                                                                            Create PayOrder
                                                                        </button>
                                                                        {{-- <h2>Paid</h2>
                                                                        <input name="delivery_type"  id="deleivery_type" class="" type="checkbox" value="transhipment" > --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive    Import_Lc_PII">


                                                                    <table class="table table-bordered sf-table-list">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="text-center">Sr No</th>
                                                                                <th class="text-center" colspan="1">Hs Code</th>
                                                                                <th class="text-center" >Description</th>
                                                                                <th class="text-center" >Amount</th>
                                                                                <th class="text-center" >@</th>
                                                                                <th class="text-center" >PKR</th>
                                                                                <th class="text-center" >Assessed Value</th>
                                                                                <th class="text-center" >CD%</th>
                                                                                <th class="text-center" >CD Amount</th>
                                                                                <th class="text-center" >ACD%</th>
                                                                                <th class="text-center" >ACD Amount</th>
                                                                                <th class="text-center" >RD%</th>
                                                                                <th class="text-center" >RD Amount</th>
                                                                                <th class="text-center" >FED%</th>
                                                                                <th class="text-center" >FED Amount</th>
                                                                                <th class="text-center" >ST%</th>
                                                                                <th class="text-center" >ST Amount</th>
                                                                                <th class="text-center" >AST%</th>
                                                                                <th class="text-center" >AST Amount</th>
                                                                                <th class="text-center" >IT%</th>
                                                                                <th class="text-center" >IT Amount</th>
                                                                                <th class="text-center" >Total Duty</th>
                                                                                <th class="text-center" >CA%</th>
                                                                                <th class="text-center" >CA Amount</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $total_amount = 0;
                                                                            $total_exchange_rate = 0;
                                                                            $total_convert_amount=0;
                                                                            $total_assessed_amount=0;
                                                                            $total_cd_amount=0;
                                                                            $total_acd_amount=0;
                                                                            $total_rd_amount=0;
                                                                            $total_fed_amount=0;
                                                                            $total_st_amount=0;
                                                                            $total_ast_amount=0;
                                                                            $total_it_amount=0;
                                                                            $total_total=0;
                                                                            $total_ca_amount=0;
                                                                                ?>
                                                                            @foreach($purchase_request_data as $key => $value)

                                                                            <?php
                                                                            // dd($value);
                                                                              $hs_code_data = ImportHelper::get_hs_code_data($value->sub_item_id);
                                                                              $acd = $hs_code_data->additional_custom_duty ?? 0;
                                                                              $cd = $hs_code_data->custom_duty ?? 0;
                                                                              $rd = $hs_code_data->regulatory_duty ?? 0;
                                                                              $fed = $hs_code_data->federal_excise_duty ?? 0;
                                                                              $st = $hs_code_data->sales_tax ?? 0;
                                                                              $ast = $hs_code_data->additional_sales_tax ?? 0;
                                                                              $it = $hs_code_data->income_tax ?? 0;
                                                                              $ca = $hs_code_data->clearing_expense ?? 0;




                                                                             $amount  =$value->purchase_approve_qty * $value->po_rate;
                                                                             $item_name =  CommonHelper::get_item_name($value->sub_item_id);
                                                                             $exchange_rate = $value->currency_rate;
                                                                             $convert_amount = $exchange_rate * $amount;
                                                                             $assessed_amount =  (( $convert_amount *2.01) /100)+$convert_amount;
                                                                             $cd_amount =  ($assessed_amount *  $cd ) /100;
                                                                             $acd_amount =  ($assessed_amount *  $acd ) /100;
                                                                             $rd_amount =  ($assessed_amount *  $rd ) /100;
                                                                             $fed_amount =  (($assessed_amount + $cd_amount + $acd_amount +  $rd_amount) *  $fed ) /100;
                                                                             $st_amount =  (($assessed_amount + $cd_amount + $acd_amount +  $rd_amount +$fed_amount) *  $st ) /100;
                                                                             $ast_amount =  (($assessed_amount + $cd_amount + $acd_amount +  $rd_amount +$fed_amount) *  $ast  ) /100;
                                                                             $it_amount =  (($assessed_amount + $cd_amount + $acd_amount +  $rd_amount +$fed_amount + $st_amount + $ast_amount) *  $it  ) /100;
                                                                             $ca_amount =  ($assessed_amount *  $ca  ) /100;
                                                                             $total = $cd_amount +$acd_amount+$rd_amount+$fed_amount+$st_amount+$ast_amount+ $it_amount;

                                                                            //  total values
                                                                            $total_amount +=$amount;
                                                                            $total_exchange_rate += $exchange_rate;
                                                                            $total_convert_amount += $convert_amount;
                                                                            $total_assessed_amount += $assessed_amount;
                                                                            $total_cd_amount += $cd_amount;
                                                                            $total_acd_amount += $acd_amount;
                                                                            $total_rd_amount += $rd_amount;
                                                                            $total_fed_amount += $fed_amount;
                                                                            $total_st_amount += $st_amount;
                                                                            $total_ast_amount += $ast_amount;
                                                                            $total_it_amount += $it_amount;
                                                                            $total_total += $total;
                                                                            $total_ca_amount += $ca_amount;
                                                                            
                                                                            ?>
                                                                            <tr>
                                                                                <td>{{ ++$key }}</td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ $hs_code_data->hs_code ?? ''  }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ $item_name }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{  number_format($exchange_rate,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($convert_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($assessed_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($cd,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($cd_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($acd,2)  }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($acd_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($rd,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($rd_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($fed,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($fed_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($st,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($st_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($ast,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($ast_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($it,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($it_amount,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($total,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($ca,2) }}" ></td>
                                                                                <td><input name=""  id="" class="" type="text" value="{{ number_format($ca_amount,2) }}" ></td>
                                                                            </tr>


                                                                            @endforeach
                                                                            <tr>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_amount,2)}}</td>
                                                                                <td>{{number_format($total_exchange_rate,2)}}</td>
                                                                                <td>{{number_format($total_convert_amount,2)}}
                                                                                <input type="hidden" name="cd_total_pkr_amount" value="{{$total_convert_amount}}"></td>
                                                                                <td>{{number_format($total_assessed_amount,2)}}</td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_cd_amount,2)}}</td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_acd_amount,2)}}</td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_rd_amount,2)}}</td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_fed_amount,2)}}</td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_st_amount,2)}}</td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_ast_amount,2)}}</td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_it_amount,2)}}</td>
                                                                                <td>{{number_format($total_total,2)}}
                                                                                <input type="hidden" name="cd_total_duty" value="{{$total_total}}"></td>
                                                                                <td></td>
                                                                                <td>{{number_format($total_ca_amount,2)}}</td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>


                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- BL Detail -->
                                                        <hr>
                                                        <div class="row">
                                                          <div class="col-md-12 d-flex" style="justify-content: space-between;">
                                                              <div class="lac3 bldetailes">
                                                                  <h2>BL Detail</h2>
                                                                  <div class="paid2">
                                                                      <h2>Arrival notice</h2>
                                                                      <h2>Under Clearance Report</h2>
                                                                  </div>
                                                                </div>
                                                                <div class="text-right">
                                                                  <button onclick="add_more('append_bl','bl_detail')" type="button" class="btn btn-success mr-1" data-dismiss="modal">Add More</button>
                                                                </div>
                                                          </div>
                                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                              <div class="table-responsive    Import_Lc_PII">
                                                                  <?php
                                                                  $counter1 = 1;
                                                                  $all_total=0;
                                                                  ?>
                                                                  
                                                                  <br>
                                                                  <table class="table table-bordered sf-table-list">
                                                                      <thead>
                                                                          <tr>
                                                                              <th class="text-center"></th>
                                                                              <th class="text-center">Sr No</th>
                                                                              <th class="text-center" colspan="1">BL #</th>
                                                                              <th class="text-center" >Lot #</th>
                                                                              <th class="text-center" >Line</th>
                                                                              <th class="text-center" >FWD</th>
                                                                              <th class="text-center" >By Sea / Air</th>
                                                                              <th class="text-center" >Bl Date</th>
                                                                              <th class="text-center" >BL NBP @</th>
                                                                              <th class="text-center" >ETA</th>
                                                                              <th class="text-center" >Receiving Date in Factory</th>
                                                                              <th class="text-center" >Receiving Number From Factory</th>
                                                                              <th class="text-center" >LCL</th>
                                                                              <th class="text-center" >20FT</th>
                                                                              <th class="text-center" >40FT</th>
                                                                              <th class="text-center" >Container #</th>
                                                                              <th class="text-center" >Packages</th>
                                                                              <th class="text-center" >IOCO</th>
                                                                              <th class="text-center" >EFS</th>
                                                                              <th class="text-center" >5th Sch</th>
                                                                              <th class="text-center" >NORMAL</th>
                                                                              <th class="text-center" >NW</th>
                                                                              <th class="text-center" >GW</th>
                                                                              <th class="text-center" >Port of Loading</th>
                                                                              <th class="text-center" >Shipment Status</th>
                                                                              <th class="text-center" >Remarks</th>
                                                                          </tr>


                                                                      </thead>
                                                                      <tbody id="append_bl">
                                                                        <?php
                                                                            
                                                                            // dd($bl_data);
                                                                            $bl_counter = 1;
                                                                            ?>
                                                                            @if ($bl_data)
                                                                                
                                                                                @foreach ($bl_data as $bl_data)
                                                                                    
                                                                                <tr id="bl_detail">
                                                                                        <td class="remove_button"></td>
                                                                                        <td><input name="bl_so[]" onkeyup="blDataAppend()" id="bl_so" class="" type="text"  value="{{ $bl_data->bl_so??'' }}"  ></td>
                                                                                        <td><input name="bl_no_bl_detail[]" onkeyup="blDataAppend()" value="{{ $bl_data->bl_no??'' }}"  id="bl_no_bl_detail" class="" type="text"  ></td>
                                                                                        <td><input name="lot_no_bl_detail[]" onkeyup="blDataAppend()" value="{{ $bl_data->lot_no??'' }}"  id="lot_no_bl_detail" class="" type="text"  ></td>
                                                                                        <td><input name="line_no[]" onkeyup="blDataAppend()" value="{{ $bl_data->line??'' }}"  id="line_no" class="" type="text"  ></td>
                                                                                        <td><input name="fwd_bl[]" onkeyup="blDataAppend()" value="{{ $bl_data->fwd??'' }}"  id="fwd_bl" class="" type="text"  ></td>
                                                                                        <td><input name="by_sea[]"  value="{{ $bl_data->by_sea??'' }}" id="" class="by_sea" type="text"  ></td>
                                                                                        <td><input name="bl_date[]" value="{{ $bl_data->bl_date??'' }}" onchange="blDateFuntion()" id="bl_date" class="" type="date" ></td>
                                                                                        <td><input name="bl_nbp[]"  value="{{ $bl_data->bl_nbp??'' }}" id="bl_nbp" class="" type="text" ></td>
                                                                                        <td><input name="eta[]" value="{{ $bl_data->eta??'' }}" onchange="blDateFuntion()" id="eta" class="" type="date"  ></td>
                                                                                        <td><input name="receving_date_factory[]" value="{{ $bl_data->receving_date_factory??'' }}"  id="receving_date_factory" class="" type="date"  onchange="blDateFuntion()" ></td>
                                                                                        <td><input name="receving_no_factory[]" value="{{ $bl_data->receving_no_factory??'' }}"  id="receving_no_factory" class="" type="text"  ></td>
                                                                                        <td><input name="lcl[]"  id="lcl" value="{{ $bl_data->lcl??'' }}" class="" type="text"></td>
                                                                                        <td><input name="ft_20[]"  id="20_ft" value="{{ $bl_data->ft_20??'' }}" class="" type="text" ></td>
                                                                                        <td><input name="ft_40[]"  id="40_ft" value="{{ $bl_data->ft_40??'' }}" class="" type="text"  ></td>
                                                                                        <td><input name="container[]" value="{{ $bl_data->container??'' }}"  id="container" class="" type="text"  ></td>
                                                                                        <td><input name="packege[]" value="{{ $bl_data->packege??'' }}"  id="packege" class="" type="text"  ></td>
                                                                                        <td><input name="ioco[]" value="{{ $bl_data->ioco??'' }}"  id="ioco" class="" type="text"  ></td>
                                                                                        <td><input name="efs[]" value="{{ $bl_data->efs??'' }}"  id="efs" class="" type="text"  ></td>
                                                                                        <td><input name="sch[]" value="{{ $bl_data->sch??'' }}"  id="sch" class="" type="text"  ></td>
                                                                                        <td><input name="normal[]" value="{{ $bl_data->normal??'' }}"  id="normal" class="" type="text"  ></td>
                                                                                        <td><input name="new[]" value="{{ $bl_data->new??'' }}"  id="new" class="" type="text"  ></td>
                                                                                        <td><input name="gw[]" value="{{ $bl_data->gw??'' }}"  id="gw" class="" type="text"  ></td>
                                                                                        <td><input name="port_of_loading[]" value="{{ $bl_data->port_of_loading??'' }}"  id="port_of_loading" class="" type="text"  ></td>
                                                                                        {{-- <td><input name="shipment_status[]" value="{{ $bl_data->shipment_status??'' }}" onkeyup="shipmentStatus()" id="shipment_status" class="" type="text"  ></td> --}}
                                                                                        <td>
                                                                                            <select name="shipment_status[]" id="shipment_status" onchange="shipmentStatus()">
                                                                                                <option value="Pending" {{$bl_data->shipment_status == 'Pending' ? 'selected' : ''}}>Pending</option>
                                                                                                <option value="Recieved" {{$bl_data->shipment_status == 'Recieved' ? 'selected' : ''}}>Recieved</option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td><input name="bl_remarks[]" value="{{ $bl_data->bl_remarks??'' }}" id="bl_remarks" class="" type="text"  ></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                @else
                                                                                @php
                                                                                    $grn_date = CommonHelper::getGrnDateAgainstPO($purchase_request_data[0]->purchase_request_no);
                                                                                @endphp
                                                                                <tr id="bl_detail">
                                                                                    <td class="remove_button"></td>
                                                                                    <td><input name="bl_so[]" onkeyup="blDataAppend()" id="bl_so" class="" type="text" value="" ></td>
                                                                                    <td><input name="bl_no_bl_detail[]"  onkeyup="blDataAppend()" id="bl_no_bl_detail" class="" type="text"  ></td>
                                                                                    <td><input name="lot_no_bl_detail[]" onkeyup="blDataAppend()"  id="lot_no_bl_detail" class="" type="text"  ></td>
                                                                                    <td><input name="line_no[]" onkeyup="blDataAppend()" id="line_no" class="" type="text"  ></td>
                                                                                    <td><input name="fwd_bl[]" onkeyup="blDataAppend()"  id="fwd_bl" class="" type="text"  ></td>
                                                                                    <td><input name="by_sea[]"   id="" class="by_sea" type="text"  ></td>
                                                                                    <td><input name="bl_date[]"   id="bl_date" class="" type="date"  onchange="blDateFuntion()"  ></td>
                                                                                    <td><input name="bl_nbp[]"   id="bl_nbp" class="" type="text" ></td>
                                                                                    <td><input name="eta[]"   id="eta" class="" type="date" onchange="blDateFuntion()" ></td>
                                                                                    <td><input name="receving_date_factory[]"   id="receving_date_factory" class="" type="date" onchange="blDateFuntion()" value="{{$grn_date}}" ></td>
                                                                                    <td><input name="receving_no_factory[]"   id="receving_no_factory" class="" type="text"  ></td>
                                                                                    <td><input name="lcl[]"  id="lcl"  class="" type="text"></td>
                                                                                    <td><input name="ft_20[]"  id="20_ft"  class="" type="text" ></td>
                                                                                    <td><input name="ft_40[]"  id="40_ft"  class="" type="text"  ></td>
                                                                                    <td><input name="container[]"   id="container" class="" type="text"  ></td>
                                                                                    <td><input name="packege[]"   id="packege" class="" type="text"  ></td>
                                                                                    <td><input name="ioco[]"   id="ioco" class="" type="text"  ></td>
                                                                                    <td><input name="efs[]"   id="efs" class="" type="text"  ></td>
                                                                                    <td><input name="sch[]"   id="sch" class="" type="text"  ></td>
                                                                                    <td><input name="normal[]"   id="normal" class="" type="text"  ></td>
                                                                                    <td><input name="new[]"   id="new" class="" type="text"  ></td>
                                                                                    <td><input name="gw[]"   id="gw" class="" type="text"  ></td>
                                                                                    <td><input name="port_of_loading[]"   id="port_of_loading" class="" type="text"  ></td>
                                                                                    {{-- <td><input name="shipment_status[]"  onkeyup="shipmentStatus()" id="shipment_status" class="" type="text"  ></td> --}}
                                                                                    <td>
                                                                                        <select name="shipment_status[]" id="shipment_status" onchange="shipmentStatus()">
                                                                                            <option value="Pending">Pending</option>
                                                                                            <option value="Recieved">Recieved</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td><input name="bl_remarks[]" value="" id="bl_remarks" class="" type="text"  ></td>
                                                                                </tr>
                                                                            @endif
                                                                      </tbody>
                                                                  </table>
                                                                  <div class="text-right">
                                                                    {{-- <button onclick="remove_more('append_bl')" type="button" class="btn btn-danger mr-1" data-dismiss="modal">Remove</button>
                                                                    <a href="{{ route('LcAndLgAgainstPo.cancel') }}" class="btnn btn-secondary">Cancel</a> --}}
                                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>


                                                        <!--Insurance Detail -->
                                                        <hr>
                                                        <div class="row">
                                                          <div class="col-md-12  d-flex" style="justify-content: space-between;">
                                                              <div class="lac3 bldetailes">
                                                                  <h2>Insurance Detail</h2>
                                                                  <div class="paid2">
                                                                      <h2>Insurance Report</h2>
                                                                  </div>
                                                              </div>
                                                              <div class="text-right">
                                                                <button onclick="add_more('append_in','in_detail')" type="button" class="btn btn-success mr-1" data-dismiss="modal">Add More</button>
                                                              </div>
                                                          </div>
                                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                              <div class="table-responsive    Import_Lc_PII">
                                                                  <?php
                                                                  $counter1 = 1;
                                                                  $all_total=0;
                                                                  ?>
                                                                  
                                                                  <br>
                                                                  <table class="table table-bordered sf-table-list">
                                                                      <thead>
                                                                          <tr>
                                                                              <th class="text-center"></th>
                                                                              <th class="text-center">Sr No</th>
                                                                              <th class="text-center" >Policy Company</th>
                                                                              <th class="text-center" >Lot #</th>
                                                                              <th class="text-center" >Cover note #</th>
                                                                              <th class="text-center" >Cover note Date</th>
                                                                              <th class="text-center" >Tolerance</th>
                                                                              <th class="text-center" >Policy Number</th>
                                                                              <th class="text-center" >Policy date</th>
                                                                              <th class="text-center" >Policy amount</th>
                                                                              <th class="text-center" >Remarks</th>
                                                                              <th class="text-center" >Policy Paid / Un Paid</th>
                                                                          </tr>
                                                                      </thead>
                                                                      <tbody id="append_in">
                                                                        <?php
                                                                            $insurence_data = null;
                                                                            if ($purchase_request_data[0]->lc_and_lg_against_po_id) {
                                                                                
                                                                                $insurence_data = db::Connection('mysql2')->table('insurence_details')
                                                                                ->where('lc_and_lg_against_po_id',$purchase_request_data[0]->lc_and_lg_against_po_id)
                                                                                ->get();
                                                                            }
                                                                            
                                                                            // dd($insurence_data);
                                                                            ?>
                                                                            @if ($insurence_data)
                                                                            @foreach ($insurence_data as $insurence_data)
                                                                                
                                                                            <tr id="in_detail">
                                                                                      <td class="remove_button"></td>
                                                                                      <td><input name="i_so[]" id="i_so" class="" type="text"  value="{{ $insurence_data->i_so??'' }}"  ></td>
                                                                                      <td><input name="policy_company[]"  id="policy_company" class="policy_company" type="text" value="{{$insurence_data->policy_company??''}}" ></td>
                                                                                      <td><input name="i_lot_no[]" id="lot_no" class="" type="text" value="{{$insurence_data->lot_no??''}}" ></td>
                                                                                      <td><input name="cover_note[]"  id="cover_note" class="" type="text" value="{{$insurence_data->cover_note??''}}" ></td>
                                                                                      <td><input name="cover_note_date[]"  id="cover_note_date" class="" type="date" value="{{$insurence_data->cover_note_date??''}}" ></td>
                                                                                      <td><input name="tolerance[]"  id="tolerance" class="" type="text" value="{{$insurence_data->tolerance??''}}" ></td>
                                                                                      <td><input name="policy_no[]"  id="policy_no" class="" type="text" value="{{$insurence_data->policy_no??''}}" ></td>
                                                                                      <td><input name="policy_date[]"  id="policy_date" class="" type="date" value="{{$insurence_data->policy_date??''}}" ></td>
                                                                                      <td><input name="policy_amount[]"  id="policy_amount" class="" type="text" value="{{$insurence_data->policy_amount??''}}" ></td>
                                                                                      <td><input name="i_remarks[]"  id="remarks" class="" type="text" value="{{$insurence_data->remarks??''}}" ></td>
                                                                                      {{-- <td><input name="policy_status[]"  id="policy_status" class="" type="text" value="{{$insurence_data->policy_status??''}}" ></td> --}}
                                                                                      <td>
                                                                                        <select name="policy_status[]" id="policy_status">
                                                                                            <option value="paid" {{$insurence_data->policy_status == 'paid' ? 'selected' :''}}>Paid</option>
                                                                                            <option value="unpaid" {{$insurence_data->policy_status == 'unpaid' ? 'selected' :''}}>Un Paid</option>
                                                                                        </select>
                                                                                    </td>
      
                                                                                  </tr>
                                                                            @endforeach
                                                                                
                                                                            @else
                                                                            <tr id="in_detail">
                                                                                <td class="remove_button"></td>
                                                                                <td><input name="i_so[]" id="i_so" class="" type="text" value="" ></td>
                                                                                <td><input name="policy_company[]"  id="policy_company" class="policy_company" type="text" ></td>
                                                                                <td><input name="i_lot_no[]" id="lot_no" class="" type="text" ></td>
                                                                                <td><input name="cover_note[]"  id="cover_note" class="" type="text" ></td>
                                                                                <td><input name="cover_note_date[]"  id="cover_note_date" class="" type="date" ></td>
                                                                                <td><input name="tolerance[]"  id="tolerance" class="" type="text" ></td>
                                                                                <td><input name="policy_no[]"  id="policy_no" class="" type="text" ></td>
                                                                                <td><input name="policy_date[]"  id="policy_date" class="" type="date"  ></td>
                                                                                <td><input name="policy_amount[]"  id="policy_amount" class="" type="text" ></td>
                                                                                <td><input name="i_remarks[]"  id="remarks" class="" type="text" ></td>
                                                                                <td><select name="policy_status[]" id="policy_status">
                                                                                    <option value="paid">Paid</option>
                                                                                    <option value="unpaid">Un Paid</option>
                                                                                </select></td>

                                                                            </tr>
                                                                            @endif
                                                                      </tbody>
                                                                  </table>
                                                                  <div class="text-right">
                                                                    {{-- <button onclick="remove_more('append_in')" type="button" class="btn btn-danger mr-1" data-dismiss="modal">Remove</button>
                                                                    <a href="{{ route('LcAndLgAgainstPo.cancel') }}" class="btnn btn-secondary">Cancel</a> --}}
                                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>

                                                        <!--GD Detail -->
                                                        <hr>
                                                        <div class="row">
                                                          <div class="col-md-12  d-flex"  style="justify-content: space-between;">
                                                              <div class="lac3 bldetailes">
                                                                  <h2>GD Detail</h2>
                                                              </div>
                                                              <div class="text-right">
                                                                <button onclick="add_more('append_gd','gd_detail')" type="button" class="btn btn-success mr-1" data-dismiss="modal">Add More</button>
                                                              </div>
                                                          </div>
                                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                              <div class="table-responsive    Import_Lc_PII">
                                                                  <?php
                                                                  $counter1 = 1;
                                                                  $all_total=0;
                                                                  ?>
                                                                   
                                                                  <br>
                                                                  <table class="table table-bordered sf-table-list">
                                                                      <thead>
                                                                          <tr>
                                                                              <th class="text-center"></th>
                                                                              <th class="text-center">Sr No</th>
                                                                              <th class="text-center" >GD Number</th>
                                                                              <th class="text-center" >Lot #</th>
                                                                              <th class="text-center" >Date</th>
                                                                              <th class="text-center" >GD Rate</th>
                                                                              <th class="text-center" >Description</th>
                                                                              <th class="text-center" >GD NW</th>
                                                                              <th class="text-center" >GD GW</th>
                                                                              <th class="text-center" >Unit</th>
                                                                              <th class="text-center" >Hs Code</th>
                                                                              <th class="text-center" >GD CFR Value</th>
                                                                              <th class="text-center" >Assesssed Value</th>
                                                                              <th class="text-center" >CD%</th>
                                                                              <th class="text-center" >CD Amount</th>
                                                                              <th class="text-center" >ACD%</th>
                                                                              <th class="text-center" >ACD Amount</th>
                                                                              <th class="text-center" >RD%</th>
                                                                              <th class="text-center" >RD Amount</th>
                                                                              <th class="text-center" >FED%</th>
                                                                              <th class="text-center" >FED Amount</th>
                                                                              <th class="text-center" >ST%</th>
                                                                              <th class="text-center" >ST Amount</th>
                                                                              <th class="text-center" >AST%</th>
                                                                              <th class="text-center" >AST Amount</th>
                                                                              <th class="text-center" >IT%</th>
                                                                              <th class="text-center" >IT Amount</th>
                                                                              <th class="text-center" >ETO%</th>
                                                                              <th class="text-center" >ETO Amount</th>
                                                                              <th class="text-center" >Total Duty</th>
                                                                          </tr>
                                                                      </thead>
                                                                      <tbody id="append_gd">
                                                                        <?php
                                                                            $gd_data = null;
                                                                            $total_duty = 0;
                                                                            if ($purchase_request_data[0]->lc_and_lg_against_po_id) {
                                                                                
                                                                                $gd_data = db::Connection('mysql2')->table('g_d_details')
                                                                                ->where('lc_and_lg_against_po_id',$purchase_request_data[0]->lc_and_lg_against_po_id)
                                                                                ->get();
                                                                                
                                                                            }
                                                                            
                                                                            // dd($gd_data);
                                                                            ?>
                                                                            @if ($gd_data)
                                                                                
                                                                            @foreach ($gd_data as $gd_data)
                                                                                <?php
                                                                                $total_duty = $gd_data->custome_duty_amount + $gd_data->acd_amount + $gd_data->rd_amount+$gd_data->fed_amount+$gd_data->st_amount+$gd_data->ast_amount+$gd_data->it_amount;
                                                                                ?>
                                                                            <tr id="gd_detail">
                                                                                      <td class="remove_button"></td>
                                                                                      <td><input name="gd_so[]" id="gd_so" class="" type="text" value="{{ $gd_data->gd_so??'' }}" ></td>
                                                                                      <td><input name="gd_no[]"  id="gd_no" class="" type="text" value="{{$gd_data->gd_no??''}}" ></td>
                                                                                      <td><input name="gd_lot_no[]" id="lot_no" class="" type="text" value="{{$gd_data->lot_no??''}}" ></td>
                                                                                      <td><input name="date[]"  id="date" class="" type="date" value="{{$gd_data->date??''}}" ></td>
                                                                                      <td><input name="gd_rate[]"  id="gd_rate" step="any" class="" type="number" value="{{$gd_data->gd_rate??''}}" ></td>
                                                                                      <td><input name="description[]"  id="description" class="" type="text" value="{{$gd_data->description??''}}" ></td>
                                                                                      <td><input name="gd_new[]"  id="gd_new" class="" step="any" type="number" value="{{$gd_data->gd_new??''}}" ></td>
                                                                                      <td><input name="gd_gw[]"  id="gd_gw" class="" type="number" step="any" value="{{$gd_data->gd_gw??''}}" ></td>
                                                                                      <td><input name="uom[]"  id="" class="uom" type="text" value="{{$gd_data->uom??''}}" ></td>
                                                                                      <td><input name="gd_hs_code[]"  id="hs_code" class="" type="text" value="{{$gd_data->hs_code??''}}" ></td>
                                                                                      <td><input name="gd_cfr_value[]"  id="gd_cfr_value" class="" step="any" type="text" value="{{number_format($gd_data->gd_cfr_value , 2)??''}}" ></td>
                                                                                      <td><input name="assessed_value[]"  id="assessed_value" step="any" class="" type="text" value="{{number_format($gd_data->assessed_value,2)??''}}" ></td>
                                                                                      <td><input name="custome_duty_percent[]"  id="custome_duty_percent" step="any" class="" type="text" value="{{number_format($gd_data->custome_duty_percent,2)??''}}" ></td>
                                                                                      <td><input name="custome_duty_amount[]"  id="custome_duty_amount" step="any" onkeyup="sum(this)" class="cd_amount" type="text" value="{{number_format($gd_data->custome_duty_amount,2)??''}}" ></td>
                                                                                      <td><input name="acd_percent[]"  id="acd_percent" step="any" class="" type="text" value="{{number_format($gd_data->acd_percent,2)??''}}" ></td>
                                                                                      <td><input name="acd_amount[]"  id="acd_amount" step="any" onkeyup="sum(this)" class="acd_amount" type="text" value="{{number_format($gd_data->acd_amount,2)??''}}" ></td>
                                                                                      <td><input name="rd_percent[]"  id="rd_percent" step="any" class="" type="text" value="{{number_format($gd_data->rd_percent,2)??''}}" ></td>
                                                                                      <td><input name="rd_amount[]"  id="rd_amount" step="any" onkeyup="sum(this)" class="rd_amount" type="text" value="{{number_format($gd_data->rd_amount,2)??''}}" ></td>
                                                                                      <td><input name="fed_percent[]"  id="fed_percent" step="any" class="" type="text" value="{{number_format($gd_data->fed_percent,2)??''}}" ></td>
                                                                                      <td><input name="fed_amount[]"  id="fed_amount" step="any" onkeyup="sum(this)" class="fed_amount" type="text" value="{{number_format($gd_data->fed_amount,2)??''}}" ></td>
                                                                                      <td><input name="st_percent[]"  id="st_percent" step="any" class="" type="text" value="{{number_format($gd_data->st_percent,2)??''}}" ></td>
                                                                                      <td><input name="st_amount[]"  id="st_amount" step="any" onkeyup="sum(this)" class="st_amount" type="text" value="{{number_format($gd_data->st_amount,2)??''}}" ></td>
                                                                                      <td><input name="ast_percent[]"  id="ast_percent" step="any" class="" type="text" value="{{number_format($gd_data->ast_percent,2)??''}}" ></td>
                                                                                      <td><input name="ast_amount[]"  id="ast_amount" step="any" onkeyup="sum(this)" class="ast_amount" type="text" value="{{number_format($gd_data->ast_amount,2)??''}}" ></td>
                                                                                      <td><input name="it_percent[]"  id="it_percent" step="any" class="" type="text" value="{{number_format($gd_data->it_percent,2)??''}}" ></td>
                                                                                      <td><input name="it_amount[]"  id="it_amount" step="any" onkeyup="sum(this)" class="it_amount" type="text" value="{{number_format($gd_data->it_amount,2)??''}}" ></td>
                                                                                      <td><input name="eto_percent[]"  id="eto_percent" step="any" class="" type="text" value="{{number_format($gd_data->eto_percent,2)??''}}" ></td>
                                                                                      <td><input name="eto_amount[]"  id="eto_amount" step="any" class="" type="text" value="{{number_format($gd_data->eto_amount,2)??''}}" ></td>
                                                                                      <td><input name="total_duty[]" id="total_duty" step="any" class="total_duty" type="text" value="{{number_format($total_duty,2)??''}}" readonly></td>
                                                                                  </tr>
                                                                            @endforeach
                                                                            @else
                                                                            <tr id="gd_detail">
                                                                                <td class="remove_button"></td>
                                                                                <td><input name="gd_so[]" id="gd_so" class="" type="text" value="" ></td>
                                                                                <td><input name="gd_no[]"  id="gd_no" class="" type="text" ></td>
                                                                                <td><input name="gd_lot_no[]" id="lot_no" class="" type="text" ></td>
                                                                                <td><input name="date[]"  id="date" class="" type="date" ></td>
                                                                                <td><input name="gd_rate[]"  id="gd_rate" step="any" class="" type="number" ></td>
                                                                                <td><input name="description[]"  id="description" class="" type="text" ></td>
                                                                                <td><input name="gd_new[]"  id="gd_new" class="" step="any" type="number" ></td>
                                                                                <td><input name="gd_gw[]"  id="gd_gw" class="" type="number" step="any" ></td>
                                                                                <td><input name="uom[]"  id="" class="uom" type="text" ></td>
                                                                                <td><input name="gd_hs_code[]"  id="hs_code" class="" type="text" ></td>
                                                                                <td><input name="gd_cfr_value[]"  id="gd_cfr_value" class="" step="any" type="number" ></td>
                                                                                <td><input name="assessed_value[]"  id="assessed_value" step="any" class="" type="number" ></td>
                                                                                <td><input name="custome_duty_percent[]"  id="custome_duty_percent" step="any" class="" type="number" ></td>
                                                                                <td><input name="custome_duty_amount[]"  id="custome_duty_amount" step="any" onkeyup="sum(this)" class="cd_amount" type="number" ></td>
                                                                                <td><input name="acd_percent[]"  id="acd_percent" step="any" class="" type="number" ></td>
                                                                                <td><input name="acd_amount[]"  id="acd_amount" step="any" onkeyup="sum(this)" class="acd_amount" type="number" ></td>
                                                                                <td><input name="rd_percent[]"  id="rd_percent" step="any" class="" type="number" ></td>
                                                                                <td><input name="rd_amount[]"  id="rd_amount" step="any" onkeyup="sum(this)" class="rd_amount" type="number" ></td>
                                                                                <td><input name="fed_percent[]"  id="fed_percent" step="any" class="" type="number" ></td>
                                                                                <td><input name="fed_amount[]"  id="fed_amount" step="any" onkeyup="sum(this)" class="fed_amount" type="number" ></td>
                                                                                <td><input name="st_percent[]"  id="st_percent" step="any" class="" type="number" ></td>
                                                                                <td><input name="st_amount[]"  id="st_amount" step="any" onkeyup="sum(this)" class="st_amount" type="number" ></td>
                                                                                <td><input name="ast_percent[]"  id="ast_percent" step="any" class="" type="number" ></td>
                                                                                <td><input name="ast_amount[]"  id="ast_amount" step="any" onkeyup="sum(this)" class="ast_amount" type="number" ></td>
                                                                                <td><input name="it_percent[]"  id="it_percent" step="any" class="" type="number" ></td>
                                                                                <td><input name="it_amount[]"  id="it_amount" step="any" onkeyup="sum(this)" class="it_amount" type="number" ></td>
                                                                                <td><input name="eto_percent[]"  id="eto_percent" step="any" class="" type="number" ></td>
                                                                                <td><input name="eto_amount[]"  id="eto_amount" step="any" class="" type="number" ></td>
                                                                                <td><input name="total_duty[]" id="total_duty" step="any" class="total_duty" type="number" readonly></td>
                                                                            </tr>
                                                                            @endif
                                                                      </tbody>
                                                                  </table>
                                                                  <div class="text-right">
                                                                    {{-- <button onclick="remove_more('append_gd')" type="button" class="btn btn-danger mr-1" data-dismiss="modal">Remove</button>
                                                                    <a href="{{ route('LcAndLgAgainstPo.cancel') }}" class="btnn btn-secondary">Cancel</a> --}}
                                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                        </div>


                                                        <!--SHIPPING LINE EXPENSE -->
                                                        <hr>
                                                        <div class="row">
                                                          <div class="col-md-12 d-flex " style="justify-content: space-between;">
                                                              <div class="lac3 bldetailes">
                                                                  <h2>SHIPPING LINE EXPENSE</h2>
                                                                  <div class="paid2">
                                                                      <h2 >Security Deposti Letter</h2>
                                                                      <h2>Security Deposti Report</h2>
                                                                  </div>
                                                                </div>
                                                                <div class="text-right">
                                                                <button onclick="add_more('append_sp','sp_detail')" type="button" class="btn btn-success mr-1" data-dismiss="modal">Add More</button>
                                                                </div>
                                                          </div>
                                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                              <div class="table-responsive    Import_Lc_PII">
                                                                  <?php
                                                                  $counter1 = 1;
                                                                  $all_total=0;
                                                                  ?>
                                                                    <br>
                                                                  <table class="table table-bordered sf-table-list">
                                                                      <thead>
                                                                          <tr>
                                                                              <th class="text-center"></th>
                                                                              <th class="text-center">Sr No</th>
                                                                              <th class="text-center" >BL #</th>
                                                                              <th class="text-center" >Lot #</th>
                                                                              <th class="text-center" >Line</th>
                                                                              <th class="text-center" >FWD</th>
                                                                              <th class="text-center" >DO Charges</th>
                                                                              <th class="text-center" >LOLO</th>
                                                                              <th class="text-center" >Port Charges</th>
                                                                              <th class="text-center" >Actual Port Charges</th>
                                                                              <th class="text-center" >Port Charges Balance</th>
                                                                              <th class="text-center" >Security Deposit (No. of Days)</th>
                                                                              <th class="text-center" >Amount</th>
                                                                              <th class="text-center" >Deduction</th>
                                                                              <th class="text-center" >Refund amount</th>
                                                                              <th class="text-center" >Cheque #</th>
                                                                              <th class="text-center" >Date</th>
                                                                              <th class="text-center" >Remarks</th>
                                                                          </tr>
                                                                      </thead>
                                                                      <tbody id="append_sp">
                                                                        <?php
                                                                        $shipping_data = null;
                                                                        if ($purchase_request_data[0]->lc_and_lg_against_po_id) {
                                                                            
                                                                            $shipping_data = db::Connection('mysql2')->table('shipping_expenses')
                                                                            ->where('lc_and_lg_against_po_id',$purchase_request_data[0]->lc_and_lg_against_po_id)
                                                                            ->get();
                                                                        }
                                                                        // dd($shipping_data);
                                                                        ?>
                                                                        @if ($shipping_data)
                                                                            
                                                                        @foreach ($shipping_data as $shipping_data)
                                                                            
                                                                        <tr id="sp_detail">
                                                                                  <td class="remove_button"><a onclick="showDetailModelOneParamerter('import/LcAndLgAgainstPo/viewSecurityDepostiLetter','<?php echo $shipping_data->id;?>','View Security Deposti Letter')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                                                                                  <td><input name="s_so[]" id="s_so" class="" type="text" value="{{ $shipping_data->s_so??'' }}" ></td>
                                                                                  <td><input name="bl_no[]" id="" class="bl_no" type="text" value="{{$shipping_data->bl_no??''}}" ></td>
                                                                                  <td><input name="s_lot_no[]" id="lot_no" class="" type="text" value="{{$shipping_data->lot_no??''}}" ></td>
                                                                                  <td><input name="line[]" id="" class="line" type="text" value="{{$shipping_data->line??''}}" ></td>
                                                                                  <td><input name="fwd[]" id="" class="fwd" type="text" value="{{$shipping_data->fwd??''}}" ></td>
                                                                                  <td><input name="do_charges[]"  id="do_charges" step="any" class="" type="text" value="{{ number_format($shipping_data->do_charges,2)??''}}" ></td>
                                                                                  <td><input name="lolo[]"  id="lolo" step="any" class="" type="text" value="{{number_format($shipping_data->lolo,2)??''}}" ></td>
                                                                                  <td><input name="port_charges[]"  id="port_charges" step="any" class="" type="text" value="{{number_format($shipping_data->port_charges,2)??''}}" onkeyup="portChargesBalance(this)"></td>
                                                                                  <td><input name="actual_port_charges[]"  id="actual_port_charges" step="any" class="" type="text" value="{{number_format($shipping_data->actual_port_charges,2)??''}}" onkeyup="portChargesBalance(this)"></td>
                                                                                  <td><input name="port_charges_balance[]"  id="port_charges_balance" step="any" class="" type="text" readonly value="{{number_format($shipping_data->port_charges_balance,2)??''}}" ></td>
                                                                                  <td><input name="security_deposit[]" readonly id="security_deposit" step="any" class="" type="text" value="{{number_format($shipping_data->security_deposit,2)??''}}" ></td>
                                                                                  <td><input name="s_amount[]" onkeyup="shippingRefundAmount(this)" id="amount" step="any" class="" type="text" value="{{number_format($shipping_data->amount,2)??''}}" ></td>
                                                                                  <td><input name="s_deduction[]" onkeyup="shippingRefundAmount(this)" id="deduction" step="any" class="" type="text" value="{{number_format($shipping_data->deduction,2)??''}}" ></td>
                                                                                  <td><input name="refund_amount[]" readonly id="refund_amount" step="any" class="" type="text" value="{{number_format($shipping_data->refund_amount,2)??''}}" ></td>
                                                                                  <td><input name="cheque_no[]"  id="cheque_no" class="" type="text" value="{{$shipping_data->cheque_no??''}}" ></td>
                                                                                  <td><input name="cheque_date[]"  id="cheque_date" class="" type="date" value="{{$shipping_data->cheque_date??''}}" ></td>
                                                                                  <td><input name="s_remarks[]"  id="remarks" class="" type="text" value="{{$shipping_data->remarks??''}}" ></td>
  
                                                                              </tr>
                                                                        @endforeach
                                                                        @else
                                                                        <tr id="sp_detail">
                                                                            <td class="remove_button"></td>
                                                                            <td><input name="s_so[]" id="s_so" class="" type="text" value="" ></td>
                                                                            <td><input name="bl_no[]" id="" class="bl_no" type="text" ></td>
                                                                            <td><input name="s_lot_no[]" id="lot_no" class="" type="text" ></td>
                                                                            <td><input name="line[]" id="" class="line" type="text" ></td>
                                                                            <td><input name="fwd[]" id="" class="fwd" type="text" ></td>
                                                                            <td><input name="do_charges[]"  id="do_charges" step="any" class="" type="number" ></td>
                                                                            <td><input name="lolo[]"  id="lolo" step="any" class="" type="number" ></td>
                                                                            <td><input name="port_charges[]" id="port_charges" step="any" class="" type="number" onkeyup="portChargesBalance(this)"></td>
                                                                            <td><input name="actual_port_charges[]" id="actual_port_charges" step="any" class="" type="number" onkeyup="portChargesBalance(this)"></td>
                                                                            <td><input name="port_charges_balance[]"  id="port_charges_balance" step="any" class="" readonly type="number" ></td>
                                                                            <td><input name="security_deposit[]" readonly id="security_deposit" step="any" class="" type="number" ></td>
                                                                            <td><input name="s_amount[]" onkeyup="shippingRefundAmount(this)" id="amount" step="any" class="" type="number" ></td>
                                                                            <td><input name="s_deduction[]" onkeyup="shippingRefundAmount(this)" id="deduction" step="any" class="" type="number" ></td>
                                                                            <td><input name="refund_amount[]" readonly id="refund_amount" step="any" class="" type="number" ></td>
                                                                            <td><input name="cheque_no[]"  id="cheque_no" class="" type="text" ></td>
                                                                            <td><input name="cheque_date[]"  id="cheque_date" class="" type="date" ></td>
                                                                            <td><input name="s_remarks[]"  id="remarks" class="" type="text" ></td>

                                                                        </tr>
                                                                        @endif
                                                                      </tbody>
                                                                  </table>
                                                                  <div class="text-right">
                                                                    {{-- <button onclick="remove_more('append_sp')" type="button" class="btn btn-danger mr-1" data-dismiss="modal">Remove</button>
                                                                    <a href="{{ route('LcAndLgAgainstPo.cancel') }}" class="btnn btn-secondary">Cancel</a> --}}
                                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                        </div>


                                                        <!--CLEARING AGENT BILL DETAIL -->
                                                        <hr>
                                                        <div class="row">
                                                          <div class="col-md-12  d-flex " style="justify-content: space-between;">
                                                              <div class="lac3 bldetailes">
                                                                  <h2>CLEARING AGENT BILL DETAIL</h2>
                                                              </div>
                                                              <div class="text-right">
                                                                <button onclick="add_more('append_cl','cl_detail')" type="button" class="btn btn-success mr-1" data-dismiss="modal">Add More</button>
                                                                </div>
                                                          </div>
                                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                              <div class="table-responsive    Import_Lc_PII">
                                                                  <?php
                                                                  $counter1 = 1;
                                                                  $all_total=0;
                                                                  ?>
                                                                    <br>
                                                                  <table class="table table-bordered sf-table-list">
                                                                      <thead>
                                                                          <tr>
                                                                              <th class="text-center"></th>
                                                                              <th class="text-center">Sr No</th>
                                                                              <th class="text-center" >Clearing agent</th>
                                                                              <th class="text-center" >Lot #</th>
                                                                              <th class="text-center" >Bill number</th>
                                                                              <th class="text-center" >Bill Date</th>
                                                                              <th class="text-center" >Amount</th>
                                                                              <th class="text-center" >Deuduction </th>
                                                                              <th class="text-center" >Paid Amount</th>
                                                                              <th class="text-center" >Remanrks</th>
                                                                              <th class="text-center" >Shipment Clearing Days</th>
                                                                              <th class="text-center" >Transit time</th>
                                                                          </tr>
                                                                      </thead>
                                                                      <tbody id="append_cl">
                                                                        <?php
                                                                            $clearance_data = null;
                                                                        if ($purchase_request_data[0]->lc_and_lg_against_po_id) {
                                                                            
                                                                            $clearance_data = db::Connection('mysql2')->table('clearing_agents')
                                                                            ->where('lc_and_lg_against_po_id',$purchase_request_data[0]->lc_and_lg_against_po_id)
                                                                            ->get();

                                                                            // dd($clearance_data);
                                                                        }
                                                                        ?>
                                                                        @if ($clearance_data)
                                                                            
                                                                        @foreach ($clearance_data as $clearance_data)
                                                                        
                                                                        <tr id="cl_detail">
                                                                                  <td class="remove_button"></td>
                                                                                  <td><input name="c_so[]" id="c_so" class="" type="text"  value="{{ $clearance_data->c_so??'' }}" ></td>
                                                                                  <td>
                                                                                    <select name="clearing_agent_no[]" id="">
                                                                                        @foreach (CommonHelper::getAllAgent() as $agent)
                                                                                            <option value="{{$agent->id}}" {{ $agent->id == $clearance_data->clearing_agent_no ? 'selected' : '' }}>{{$agent->agent_name}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                  </td>
                                                                                  {{-- <td><input name="clearing_agent_no[]"  id="clearing_agent_no" class="" type="text" value="{{$clearance_data->clearing_agent_no??''}}" ></td> --}}
                                                                                  <td><input name="c_lot_no[]" id="lot_no" class="" type="text" value="{{$clearance_data->lot_no??''}}" ></td>
                                                                                  <td><input name="bill_no[]"  id="bill_no" class="" type="text" value="{{$clearance_data->bill_no??''}}" ></td>
                                                                                  <td><input name="bill_date[]"  id="bill_date" class="" type="date" value="{{$clearance_data->bill_date??''}}" ></td>
                                                                                  <td><input name="c_amount[]" step="any"  id="amount" onkeyup="paidAmountdeduction(this)" class="" type="text" value="{{number_format($clearance_data->amount,2)??''}}" ></td>
                                                                                  <td><input name="c_deduction[]" step="any"  id="deduction" onkeyup="paidAmountdeduction(this)" class="" type="text" value="{{number_format($clearance_data->deduction,2)??''}}" ></td>
                                                                                  <td><input name="paid_amount[]" step="any"  id="paid_amount" readonly class="" type="text" value="{{number_format($clearance_data->paid_amount,2)??''}}" ></td>
                                                                                  <td><input name="c_remarks[]"   id="remarks" class="" type="text" value="{{$clearance_data->remarks??''}}" ></td>
                                                                                  <td><input name="shipment_clearing_days[]"  readonly id="shipment_clearing_days" class="" type="number" value="{{$clearance_data->shipment_clearing_days??''}}" ></td>
                                                                                  <td><input name="transit_time[]"  readonly id="transit_time" class="" type="number" value="{{$clearance_data->transit_time??''}}" ></td>
  
                                                                              </tr>
                                                                        @endforeach
                                                                        @else
                                                                        <tr id="cl_detail">
                                                                            <td class="remove_button"></td>
                                                                            <td><input name="c_so[]" id="c_so" class="" type="text" value="" ></td>
                                                                            <td>
                                                                                <select name="clearing_agent_no[]" id="">
                                                                                    @foreach (CommonHelper::getAllAgent() as $agent)
                                                                                        <option value="{{$agent->id}}">{{$agent->agent_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            {{-- <td><input name="clearing_agent_no[]"  id="clearing_agent_no" class="" type="text" ></td> --}}
                                                                            <td><input name="c_lot_no[]" id="lot_no" class="" type="text" ></td>
                                                                            <td><input name="bill_no[]"  id="bill_no" class="" type="text" ></td>
                                                                            <td><input name="bill_date[]"  id="bill_date" class="" type="date" ></td>
                                                                            <td><input name="c_amount[]" step="any" onkeyup="paidAmountdeduction(this)" id="amount" class="" type="number" ></td>
                                                                            <td><input name="c_deduction[]" step="any" onkeyup="paidAmountdeduction(this)" id="deduction" class="" type="number" ></td>
                                                                            <td><input name="paid_amount[]" step="any" readonly id="paid_amount" class="" type="number" ></td>
                                                                            <td><input name="c_remarks[]"   id="remarks" class="" type="text" ></td>
                                                                            <td><input name="shipment_clearing_days[]" readonly  id="shipment_clearing_days" class="" type="number" ></td>
                                                                            <td><input name="transit_time[]" readonly  id="transit_time" class="" type="number" ></td>

                                                                        </tr>
                                                                        @endif
                                                                      </tbody>
                                                                  </table>
                                                                  <div class="text-right">
                                                                    {{-- <button onclick="remove_more('append_cl')" type="button" class="btn btn-danger mr-1" data-dismiss="modal">Remove</button>
                                                                    <a href="{{ route('LcAndLgAgainstPo.cancel') }}" class="btnn btn-secondary">Cancel</a> --}}
                                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                        </div>


                                                        <!--MATURITY DETAIL -->
                                                        <hr>
                                                        <div class="row">
                                                          <div class="col-md-12  d-flex " style="justify-content: space-between;">
                                                              <div class="lac3 bldetailes">
                                                                  <h2>MATURITY DETAIL</h2>
                                                                  <div class="paid2">
                                                                      <h2>Maturity Sheet</h2>
                                                                  </div>
                                                                </div>
                                                                <div class="text-right">
                                                                 <button onclick="add_more('append_m','m_detail')" type="button" class="btn btn-success mr-1" data-dismiss="modal">Add More</button>
                                                                 </div>
                                                          </div>
                                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                              <div class="table-responsive    Import_Lc_PII">
                                                                  <?php
                                                                  $counter1 = 1;
                                                                  $all_total=0;
                                                                  ?>
                                                                    <br>
                                                                  <table class="table table-bordered sf-table-list">
                                                                      <thead>
                                                                          <tr>
                                                                              <th class="text-center"></th>
                                                                              <th class="text-center">Sr No</th>
                                                                              <th class="text-center" >Currency</th>
                                                                              <th class="text-center" >Bank doc amount</th>
                                                                              <th class="text-center" >Rate</th>
                                                                              <th class="text-center" >PKR</th>
                                                                              <th class="text-center" >Lot #</th>
                                                                              <th class="text-center" >Days</th>
                                                                              <th class="text-center" >Maturity Date</th>
                                                                              <th class="text-center" >Remanrks</th>
                                                                          </tr>
                                                                      </thead>
                                                                      <tbody id="append_m">
                                                                        <?php
                                                                        $maturity_data = null;
                                                                    if ($purchase_request_data[0]->lc_and_lg_against_po_id) {
                                                                        
                                                                        $maturity_data = db::Connection('mysql2')->table('maturity_details')
                                                                        ->where('lc_and_lg_against_po_id',$purchase_request_data[0]->lc_and_lg_against_po_id)
                                                                        ->get();

                                                                        // dd($maturity_data);
                                                                    }
                                                                    $m_counter = 1;
                                                                    ?>
                                                                    @if ($maturity_data)
                                                                        
                                                                    @foreach ($maturity_data as $maturity_data)
                                                                        
                                                                    <tr id="m_detail">
                                                                        <td class="remove_button"></td>
                                                                      <td><input name="m_so[]" id="m_so" class="" type="text" value="{{ $maturity_data->m_so??'' }}" ></td>
                                                                      @php
                                                                          if ($maturity_data->curreny_id != 0 && $maturity_data->curreny_id !='' ) {
                                                                            $curreny_id = $maturity_data->curreny_id;
                                                                          }
                                                                          else{
                                                                            $curreny_id = $purchase_request_data[0]->currency_id;
                                                                          }
                                                                      @endphp
                                                                      <td><input  class="" type="text" value="{{CommonHelper::get_curreny_name($curreny_id)}}" > 
                                                                        <input name="curreny_id[]"  id="curreny_id" class="" type="hidden" value="{{$curreny_id??''}}" ></td>
                                                                      <td><input name="bank_doc_amount[]" step="any" onkeyup="pkrFuntion(this)" id="bank_doc_amount" class="" type="text" value="{{number_format($maturity_data->bank_doc_amount,2)??''}}" ></td>
                                                                      <td><input name="rate[]" step="any"  id="rate" onkeyup="pkrFuntion(this)" class="" type="text" value="{{number_format($maturity_data->rate,2)??''}}" ></td>
                                                                      <td><input name="pkr[]" step="any"  id="pkr" class="" readonly type="text" value="{{number_format($maturity_data->pkr,2)??''}}" ></td>
                                                                      <td><input name="m_lot_no[]"  id="lot_no" class="" type="text" value="{{$maturity_data->lot_no??''}}" ></td>
                                                                      <td><input name="days[]"   id="days" class="" type="number" readonly value="{{$maturity_data->days??''}}" ></td>
                                                                      <td><input name="maturity_date[]"   id="maturity_date" class="" readonly type="date" value="{{$maturity_data->maturity_date??''}}" ></td>
                                                                      <td><input name="m_remarks[]"   id="remarks" class="" type="" value="{{$maturity_data->remarks??''}}" ></td>
                                                                          </tr>
                                                                    @endforeach
                                                                    @else
                                                                    <tr id="m_detail">
                                                                        <td class="remove_button"></td>
                                                                        <td><input name="m_so[]" id="m_so" class="" type="text" value="" ></td>
                                                                        <td><input class="" type="text" value="{{CommonHelper::get_curreny_name( $purchase_request_data[0]->currency_id)}}">
                                                                            <input name="curreny_id[]"  id="curreny_id" class="" type="hidden" value="{{ $purchase_request_data[0]->currency_id}}"></td>
                                                                        <td><input name="bank_doc_amount[]" step="any" onkeyup="pkrFuntion(this)" id="bank_doc_amount" class="" type="text" ></td>
                                                                        <td><input name="rate[]" step="any" onkeyup="pkrFuntion(this)" id="rate" class="" type="text" value="{{$purchase_request_data[0]->currency_rate}}" ></td>
                                                                        {{-- <td><input name="rate[]" step="any" onkeyup="pkrFuntion(this)" id="rate" class="" type="number" value="{{CommonHelper::get_exchange_rate( $purchase_request_data[0]->currency_id)}}" ></td> --}}
                                                                        <td><input name="pkr[]" step="any"  id="pkr" readonly class="" type="text" ></td>
                                                                        <td><input name="m_lot_no[]"  id="lot_no"  class="" type="text" ></td>
                                                                        <td><input name="days[]"   id="days" class="" type="number" readonly value="{{ $days_from }}"></td>
                                                                        <td><input name="maturity_date[]"   id="maturity_date" class="" readonly type="date" ></td>
                                                                        <td><input name="m_remarks[]"   id="remarks" class="" type="" ></td>
                                                                            </tr>
                                                                    @endif
                                                                      </tbody>
                                                                  </table>
                                                                  <div class="text-right">
                                                                    {{-- <button onclick="remove_more('append_m')" type="button" class="btn btn-danger mr-1" data-dismiss="modal">Remove</button>
                                                                    <a href="{{ route('LcAndLgAgainstPo.cancel') }}" class="btnn btn-secondary">Cancel</a> --}}
                                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                        </div>


                                                      <!-- buttons -->
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="col-md-12 padtb text-right">
                                                                    <div class="col-md-12 my-lab">
                                                                        <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                        <a href="{{ route('LcAndLgAgainstPo.cancel') }}" class="btnn btn-secondary">Cancel</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function getPoData()
        {
            
            let data_po_id = $('#po_data').val();
            let po_id = $('#po_id').val();
            $('#PoData').html('');
            // $('#PoData').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            if (data_po_id) {
                url = '<?php echo url('/')?>/import/LcAndLgAgainstPo/getPodata';
            }
            else{
                url = '<?php echo url('/')?>/import/LcAndLgAgainstPo/create';
            }
             console.log(po_id, data_po_id);
             if (data_po_id) {
                url = '<?php echo url('/')?>/import/LcAndLgAgainstPo/getPodata';
            }
            else{
                 url = '<?php echo url('/')?>/import/LcAndLgAgainstPo/create';
             }
            if(po_id)
            {
                $.ajax({
                    url: url,
                    type: 'Get',
                    data: {
                            po_id:po_id,
                            data_po_id:data_po_id
                        },
                    success: function (response) {
                        console.log('typeof' , typeof(response));
                        // return;
                        $('#PoData').html(response);

                        $('.qty').trigger('keyup');
                    }
                });

            }
            else
            {
                $('#nextForm').empty();

            }


        }


        // function nextForm()
        // {

        //     let po_id = $('#po_data').val();
        //      $('#nextForm').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
        //     if(po_id)
        //     {
        //     alert('check');
        //     console.log(po_id);
        //         $.ajax({
        //             url: '<?php echo url('/')?>/import/LcAndLgAgainstPo/create',
        //             type: 'Get',
        //             data: {
        //                     po_id:po_id
        //                 },
        //             success: function (response) {

        //                 $('#nextForm').html(response);

        //                 $('.qty').trigger('keyup');
        //             }
        //         });

        //     }
        //     else
        //     {
        //         $('#nextForm').empty();

        //     } 
        // }


        function calculation_po(number)
    {


        var  qty=$('#qty'+number).val();
        var  rate=$('#rate'+number).val();

        var total=parseFloat(qty*rate).toFixed(2);

        $('#total_amount'+number).val(total);

        hs_code_tax(number);

        // total quantity
        var dg_data = document.querySelectorAll('#append_dg tr');
        dg_qty = 0;
        for (var i = 0; i < dg_data.length - 1 ; i++) {
            var dg_qty = dg_qty + parseInt(dg_data[i].querySelectorAll('input[name="qty[]"]')[0].value);

        }
        $('#goods_total_quantity').text(dg_qty);

    }

    function hs_code_tax(number) {

        let index = number - 1
        let total_duty_with_taxes = + hsCodeData[index]?.total_duty_with_taxes || 0 ;
    //    let total_tax = custom_duty + regulatory_duty + federal_excise_duty + additional_custom_duty + sales_tax + additional_sales_tax + income_tax + clearing_expense +    total_duty_without_taxes + total_duty_with_taxes;
        let total_tax = total_duty_with_taxes;
        let amount = + $('#total_amount'+number).val();

        let hs_code_amount = amount * total_tax / 100;

     //   $('#hs_code_amount'+number).val(hs_code_amount.toFixed(2));


          var hsCodeAmountElements = document.querySelectorAll('.hs_code_amount');
          var total_amount = document.querySelectorAll('.total_amount');

            // Initialize a variable to store the sum
            var sum = 0;
            var total_amount_sum = 0;

            // Iterate through the elements and sum their values
            hsCodeAmountElements.forEach(function (element) {
                // Convert the text content to a number and add it to the sum
                sum += parseFloat(element.value);
            });
            total_amount.forEach(function (element) {
                // Convert the text content to a number and add it to the sum
                total_amount_sum += parseFloat(element.value);
            });

       // $('#hs_code').val(sum.toFixed(2));
        $('#d_t_amount_1').val(total_amount_sum.toFixed(2));
        $('#goods_total_amount').text(total_amount_sum.toFixed(2));
        toWords(1);

    }

    function add_more(append,clone_detail)
    {
        var clonedElement = $('#' + clone_detail).clone();

        // Add an extra column to the cloned element
        var extraColumn = $('<button onclick="remove_section(this)" type="button" class="btn btn-danger mr-1" data-dismiss="modal"> - </button>');
        clonedElement.closest('tr').find('.remove_button').append(extraColumn);

        // Clear the input values if needed
        // clonedElement.find("input").val("");

        $('#' + append).append(clonedElement);

        return;

        var clonedElement = $('#'+clone_detail).clone();
        console.log(clonedElement);
        // clonedElement.find("input").val("");
        $('#'+append).append(clonedElement);
        console.log(clonedElement);
        return;

        
        // var blElement = $('#bl_detail').clone();
        // console.log(blElement);
        // $('#append_bl').append(blElement);

        // var inElement = $('#in_detail').clone();
        // console.log(inElement);
        // $('#append_in').append(inElement);

        // var gdElement = $('#gd_detail').clone();
        // console.log(gdElement);
        // $('#append_gd').append(gdElement);

        // var spElement = $('#sp_detail').clone();
        // console.log(spElement);
        // $('#append_sp').append(spElement);

        // var clElement = $('#cl_detail').clone();
        // console.log(clElement);
        // $('#append_cl').append(clElement);

        // var mElement = $('#m_detail').clone();
        // console.log(mElement);
        // $('#append_m').append(mElement);

    }

    function remove_more(append)
    {

        var tbody = document.getElementById(append);
        if (tbody.childElementCount > 1) {
            var lastTr = tbody.lastElementChild;

            // Remove the last <tr> element
            if (lastTr) {
                lastTr.parentNode.removeChild(lastTr);
            }
        }
        return;

        // var tbody = document.getElementById('append_bl');
        // if (tbody.childElementCount > 1) {
        //     var lastTr = tbody.lastElementChild;
        //     var append_in = document.getElementById('append_in').lastElementChild;
        //     var append_gd = document.getElementById('append_gd').lastElementChild;
        //     var append_sp = document.getElementById('append_sp').lastElementChild;
        //     var append_cl = document.getElementById('append_cl').lastElementChild;
        //     var append_m = document.getElementById('append_m').lastElementChild;
        //     // Remove the last <tr> element
        //     if (lastTr) {
        //         lastTr.parentNode.removeChild(lastTr);
        //         append_in.parentNode.removeChild(append_in);
        //         append_gd.parentNode.removeChild(append_gd);
        //         append_sp.parentNode.removeChild(append_sp);
        //         append_cl.parentNode.removeChild(append_cl);
        //         append_m.parentNode.removeChild(append_m);
        //     }
        // }
    }

    function remove_section(instance){
        $(instance).closest('tr').remove();
    }

    function totaldutyfunction(counter){
        var sum = 0;
        $('.total_duty_event'+counter).each(function(){
            sum += parseFloat(this.value);
        });
        console.log(sum);
    }
    function sum(count) {
        console.log(count);
        
        var cd_amount = parseFloat($(count).closest('tr').find('.cd_amount').val().replace(/,/g,'')) || 0;
        var acd_amount = parseFloat($(count).closest('tr').find('.acd_amount').val().replace(/,/g,'')) || 0;
        var rd_amount = parseFloat($(count).closest('tr').find('.rd_amount').val().replace(/,/g,'')) || 0;
        var fed_amount = parseFloat($(count).closest('tr').find('.fed_amount').val().replace(/,/g,'')) || 0;
        var st_amount = parseFloat($(count).closest('tr').find('.st_amount').val().replace(/,/g,'')) || 0;
        var ast_amount = parseFloat($(count).closest('tr').find('.ast_amount').val().replace(/,/g,'')) || 0;
        var it_amount = parseFloat($(count).closest('tr').find('.it_amount').val().replace(/,/g,'')) || 0;
        var total = cd_amount+acd_amount+rd_amount+fed_amount+st_amount+ast_amount+it_amount;
        console.log(cd_amount,acd_amount,rd_amount,fed_amount,st_amount,ast_amount,it_amount);
        console.log(total);
        
        $(count).closest('tr').find('.total_duty').val(total.toLocaleString('en'));
    }

    function portChargesBalance(count) {
        
        var port_charges = parseFloat($(count).closest('tr').find('#port_charges').val().replace(/,/g,'')) || 0;
        var actual_port_charges = parseFloat($(count).closest('tr').find('#actual_port_charges').val().replace(/,/g,'')) || 0;
        var port_charges_balance = (port_charges - actual_port_charges).toLocaleString('en');
        parseFloat($(count).closest('tr').find('#port_charges_balance').val(port_charges_balance));
    }

    function paidAmountdeduction(count){
        var amount = parseFloat($(count).closest('tr').find('#amount').val().replace(/,/g,'')) || 0;
        var deduction = parseFloat($(count).closest('tr').find('#deduction').val().replace(/,/g,'')) || 0;
        var paid_amount_deduction = (amount - deduction).toLocaleString('en');
        parseFloat($(count).closest('tr').find('#paid_amount').val(paid_amount_deduction));
    }
    function pkrFuntion(count){
        var rate = parseFloat($(count).closest('tr').find('#rate').val().replace(/,/g,'')) || 0;
        var bank_doc_amount = parseFloat($(count).closest('tr').find('#bank_doc_amount').val().replace(/,/g,'')) || 0;
        var pkr = (bank_doc_amount * rate).toLocaleString('en');
        parseFloat($(count).closest('tr').find('#pkr').val(pkr));
    }
    function shippingRefundAmount(count){
        var amount = parseFloat($(count).closest('tr').find('#amount').val().replace(/,/g,'')) || 0;
        var deduction = parseFloat($(count).closest('tr').find('#deduction').val().replace(/,/g,'')) || 0;
        var refund = (amount - deduction).toLocaleString('en');
        parseFloat($(count).closest('tr').find('#refund_amount').val(refund));
    }
    function blDateFuntion(){
        // console.log(count);
        var elementsArray1 = document.querySelectorAll('#append_bl tr');
        var elementsArray2 = document.querySelectorAll('#append_m tr');
        var elementsArray3 = document.querySelectorAll('#append_cl tr');
        var elementsArray4 = document.querySelectorAll('#append_sp tr');
        // console.log(elementsArray1);

        for (var i = 0; i < elementsArray1.length && i < elementsArray2.length; i++) {
            
            
            var bl_date = elementsArray1[i].querySelectorAll('input[name="bl_date[]"]')[0].value;
            
            // Get the selected date from the date input field
            var bl_date = new Date(bl_date);
            
            // Parse the number of days to add
            var numberOfDaysToAdd = "{{$days_from}}";
            
            // Calculate the new date by adding the number of days to the selected date
            var newDate = new Date(bl_date.getTime() + numberOfDaysToAdd * 24 * 60 * 60 * 1000);
            console.log(newDate);
            // Format the new date as desired (e.g., YYYY-MM-DD)
            var formattedNewDate = newDate.toISOString().split('T')[0];
            console.log(formattedNewDate);

            elementsArray2[i].querySelectorAll('input[name="maturity_date[]"]')[0].value = formattedNewDate;

            var bl_eta = elementsArray1[i].querySelectorAll('input[name="eta[]"]')[0].value;
            var bl_eta = new Date(bl_eta);
            var numberOfDaysToAdd = ( bl_eta.getTime() - bl_date.getTime())/ (1000 * 3600 * 24);
            // var transmit_time = new Date();
            // var transmit_time = transmit_time.setDate(transmit_time.getDate() + numberOfDaysToAdd);
            // transmit_time = new Date(transmit_time);
            // var transmit_time = transmit_time.toISOString().split('T')[0];
            // console.log('adasdsadasd',transmit_time);
            elementsArray3[i].querySelectorAll('input[name="transit_time[]"]')[0].value = numberOfDaysToAdd;

            // for Shipment Clearing Days 
            var factory_receiving  = elementsArray1[i].querySelectorAll('input[name="receving_date_factory[]"]')[0].value;
            var factory_receiving = new Date(factory_receiving);
            var clearing_days = (bl_eta.getTime() - factory_receiving.getTime())/ (1000 * 3600 * 24);
            elementsArray3[i].querySelectorAll('input[name="shipment_clearing_days[]"]')[0].value = clearing_days;

            // for Security Deposit
            // var factory_receiving  = elementsArray1[i].querySelectorAll('input[name="receving_date_factory[]"]')[0].value;
            var current_date  = new Date(new Date().toJSON().slice(0, 10));
            var security_deposit = (factory_receiving.getTime() - current_date.getTime())/ (1000 * 3600 * 24);
            elementsArray4[i].querySelectorAll('input[name="security_deposit[]"]')[0].value = security_deposit;


        }

    }
    function blDataAppend(){
        var bl_data = document.querySelectorAll('#append_bl tr');
        var m_data = document.querySelectorAll('#append_m tr');
        var in_data = document.querySelectorAll('#append_in tr');
        var cl_data = document.querySelectorAll('#append_cl tr');
        var gd_data = document.querySelectorAll('#append_gd tr');
        var sp_data = document.querySelectorAll('#append_sp tr');
        
        for (var i = 0; i < bl_data.length; i++) {
            // s no 
            var bl_so = bl_data[i].querySelector('input[name="bl_so[]"]').value;
            if(m_data[i]) m_data[i].querySelector('input[name="m_so[]"]').value = bl_so;
            if(in_data[i]) in_data[i].querySelector('input[name="i_so[]"]').value = bl_so;
            if(cl_data[i]) cl_data[i].querySelector('input[name="c_so[]"]').value = bl_so;
            if(gd_data[i]) gd_data[i].querySelector('input[name="gd_so[]"]').value = bl_so;
            if(sp_data[i]) sp_data[i].querySelector('input[name="s_so[]"]').value = bl_so;

            // bl no 
            var bl_no = bl_data[i].querySelector('input[name="bl_no_bl_detail[]"]').value;
            if(sp_data[i]) sp_data[i].querySelector('input[name="bl_no[]"]').value = bl_no;
            
            // Lot # 
            var bl_lot_no = bl_data[i].querySelector('input[name="lot_no_bl_detail[]"]').value;
            if(m_data[i]) m_data[i].querySelector('input[name="m_lot_no[]"]').value = bl_lot_no;
            if(in_data[i]) in_data[i].querySelector('input[name="i_lot_no[]"]').value = bl_lot_no;
            if(cl_data[i]) cl_data[i].querySelector('input[name="c_lot_no[]"]').value = bl_lot_no;
            if(gd_data[i]) gd_data[i].querySelector('input[name="gd_lot_no[]"]').value = bl_lot_no;
            if(sp_data[i]) sp_data[i].querySelector('input[name="s_lot_no[]"]').value = bl_lot_no;

            //Line
            var bl_line_no = bl_data[i].querySelector('input[name="line_no[]"]').value;
            if(sp_data[i]) sp_data[i].querySelector('input[name="line[]"]').value = bl_line_no;

            //FWD
            var bl_fwd = bl_data[i].querySelector('input[name="fwd_bl[]"]').value;
            if(sp_data[i]) sp_data[i].querySelector('input[name="fwd[]"]').value = bl_fwd;

        }
        
    }
    </script>


<script>
    $(document).ready(function() {
        setTimeout(() => {
            document.querySelectorAll('form input').forEach(input => {
                if (input.getAttribute('readonly') == null) {
                    input.style.backgroundColor = !!input.value ? "white" : "rgb(255, 236, 182)";
                }
            });

            // document.querySelectorAll('form input[type="number"]').forEach(input => {
            //     // console.log('asdasd' , input.value);
            //     let formattedNumber  = input.value.toLocaleString('en-US', {
            //         minimumFractionDigits: 2,
            //         maximumFractionDigits: 2
            //     });
            //     console.log('asdas' , formattedNumber);
            //     input.value = formattedNumber;
            // });
        }, 200);
    });
    $(document).on('keyup','form input',function(e){
        // console.log(document.querySelectorAll('form input').length);
        document.querySelectorAll('form input').forEach(input => {
            if (input.getAttribute('readonly') == null) {
                input.style.backgroundColor = !!input.value ? "white" : "rgb(255, 236, 182)";
            }
        });
    })
    $(document).on('change','#insurance', function(){
        value = $(this).val();
        $('.policy_company').each(function(){
            $(this).val(value);
        });
    })

    function shipmentStatus(){
        var bl_data = document.querySelectorAll('#append_bl tr');
        var bl_ss = [];
        for (var i = 0; i < bl_data.length; i++) {
            data = bl_data[i].querySelectorAll('input[name="shipment_status[]"]')[0].value;
            bl_ss.push(data);
        }
        var all_shipment_status = bl_ss.join(" , ");
        $("#all_shipment_status").text(all_shipment_status);
    }

    function checkBox(instance){
        $('.checkBox').each(function(){
            $(this).prop('checked' , false);
        });
        $(instance).prop('checked' , true);
        // console.log($(instance).attr('name'));
        if ($(instance).attr('name') == 'sight') {
            console.log($(instance).attr('name'));
            $('#days_from').val('');
            $('#days_from').attr('disabled' , true);
        }
        else{
            $('#days_from').val('{{ $days_from }} Days from BL DATE');
            $('#days_from').attr('disabled' , false);
        }
        // $(instance).attr('name');
    }

    function pi_ci_cal(){

        $('.ci_qty').each(function(){
            ci_qty = parseFloat($(this).val());
            ci_rate = parseFloat($(this).closest('tr').find('.ci_rate').val());
            ci_total_value = ci_qty * ci_rate;
            console.log(typeof(ci_qty) , typeof(ci_rate) , ci_qty ,ci_rate );
            $(this).closest('tr').find('.ci_total_value').val(ci_total_value);

            pi_qty = parseFloat($(this).closest('tr').find('.qty').val());
            pi_rate = parseFloat($(this).closest('tr').find('.pi_rate').val());
            pi_total_value = pi_qty * pi_rate;

            console.log(pi_rate , ci_rate);
            diff_qty = pi_qty - ci_qty;
            diff_rate = pi_rate - ci_rate;
            diff_total_value = pi_total_value - ci_total_value;
            console.log(diff_qty , diff_rate , diff_total_value);

            $(this).closest('tr').find('.diff_qty').val(diff_qty);
            $(this).closest('tr').find('.diff_rate').val(diff_rate);
            $(this).closest('tr').find('.diff_total_value').val(diff_total_value);


        });
    }
    
    $('#LcAndLgAgainstPo').submit(function(e){
        e.preventDefault();
        data = $(this).serialize();
        $.ajax({
            url: $('#LcAndLgAgainstPo').attr('action'),
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.status == true) {
                    $('select[name="po_id"]').trigger('change');
                    $(".alert-message").html(' <div class="alert-success"> <span class="glyphicon glyphicon-ok"></span>'+response.message+'</div>');
                    toastr.success(response.message , "SUCCESS!");
                }
                else{
                    
                    $(".alert-message").html('<div class="alert-danger"><span class="glyphicon glyphicon-remove"></span>'+response.message+'</div>');
                    toastr.error(response.message , "ERROR!")
                }
                // var categoryDropdown = $('#category_id');
                // categoryDropdown.append('<option value="' + response.id + '">' + response.category_name + '</option>');
                // $('#showMasterEditModal').modal('hide');
            },
            error: function(xhr, status, error) {
                toastr.error(error , "ERROR!")
                swalError();
            }
        });
    })

    // const dateInput = document.getElementById('myDate');

    // Event listener for when the date input value changes
    // $('input[type=date]').on('change', function() {
    //     // Get the selected date
    //     const selectedDate = new Date(this.value);

    //     // Format the date as DD-MM-YYYY
    //     const formattedDate = `${selectedDate.getDate().toString().padStart(2, '0')}-${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}-${selectedDate.getFullYear()}`;

    //     // Set the input value to the formatted date
    //     this.value = formattedDate;
    //     console.log(formattedDate);
    // });

    // $("input[type='date']").on("change", function() {
    //     console.log(this.value , 'check date');
    //     if (this.value != '') {
    //         this.setAttribute(
    //             "data-date",
    //             moment(this.value, "YYYY-MM-DD")
    //                 .format( this.getAttribute("data-date-format") )
    //         );
    //     }

    // }).trigger("change");
</script>