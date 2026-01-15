@php
use App\Helpers\CommonHelper;
use App\Helpers\ImportHelper;


$applicant_data = DB::table('company')->where('id' , Auth::user()->company_id)->first();
$beneficiary_data = DB::connection('mysql2')->table('supplier')
->leftJoin('supplier_info','supplier_info.supp_id','=','supplier.id')
->where('supplier.id' , $purchase_request_data[0]->supplier_id)->first();

$applicant_bank = DB::connection('mysql2')->table('lc_and_lg as l')
                    ->join('accounts as a', 'a.id', '=', 'l.acc_id')
                    ->where('a.status', 1)
                    ->where('l.status', 1)
                    ->select('l.id', 'a.name', 'l.limit', 'l.type')->orderBy('l.id', 'desc')->get();
                    // dd($applicant_bank);

    // $buyer_name = $applicant_data->name ?? '';
    // $applicant_full_address = $applicant_data->address ?? '';
    $buyer_name = $purchase_request_data[0]->buyer_name ?? $applicant_data->name ;
    $applicant_full_address = $purchase_request_data[0]->applicant_full_address ?? $applicant_data->address;
    $pi_no = $purchase_request_data[0]->pi_no ?? '';
    $lc_no =
        $purchase_request_data[0]->lc_no ?? CommonHelper::generateUniquePosNo('lc_and_lg_against_po', 'lc_no', 'LC');
    $lc_date = $purchase_request_data[0]->lc_date ?? date('Y-m-d');
    $refrence_no = $purchase_request_data[0]->refrence_no ?? '';
    $applicant_bank = $applicant_bank ?? '';
    // $beneficiary_name = $beneficiary_data->name ?? $buyer_name;
    // $beneficiary_id = $beneficiary_data->id ?? '';
    // $beneficiary_full_address = $beneficiary_data->address ?? '';
    $beneficiary_name = $purchase_request_data[0]->beneficiary_name ?? $beneficiary_data->name;
    $beneficiary_id = $purchase_request_data[0]->beneficiary_id ?? $beneficiary_data->id ;
    $beneficiary_full_address = $purchase_request_data[0]->beneficiary_full_address ?? $beneficiary_data->address;

    $description = $purchase_request_data[0]->description ?? $hs_code_description ?? '';
    $sub_description = $purchase_request_data[0]->sub_description ?? $hs_code_description ?? '';
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
    $hs_code =
        $purchase_request_data[0]->lc_lg_hs_code ?? ImportHelper::get_hs_code($purchase_request_data[0]->sub_item_id);
    $insurance = $purchase_request_data[0]->insurance ?? '';

@endphp


    <!-- PO Number  -->
    <div class="col-md-4">
        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">LC NO #</label>
                </div>
                <input readonly name="lc_no" value="{{ $lc_no }}" id="lc_no" class="form-control"
                    type="text">
            </div>

        </div>
        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">LC Date #</label>
                </div>
                <input readonly name="lc_date" value="{{ $lc_date }}" id="lc_date" class="form-control"
                    type="date">
            </div>

        </div>
        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">PO Number </label>
                </div>
                @php
                    $purchase_request_no = DB::connection('mysql2')->table('purchase_request')->where('id' , $po_id)->first()->purchase_request_no;
                @endphp
                <input name="po_no" value="{{ $purchase_request_no }}" id="po_no"
                    class="form-control" type="text">
                <input name="po_id" value="{{ $po_id }}" id="po_id"
                    class="form-control" type="hidden">

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
                <input name="refrence_no" value="{{ $refrence_no }}" id="refrence_no" class="form-control"
                    type="text">
            </div>

        </div>
        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Applicant Name</label>
                </div>
                <input name="buyer_name" value="{{ $buyer_name }}" id="buyer_name" class="form-control"
                    type="text">
                <input name="buyer_id" id="buyer_id" class="form-control" type="hidden">
            </div>

        </div>
        <div class="form-group">
            <div class="flex_lable">

                <div class="lable_text">
                    <label class="control-label">Applicant Full Address </label>
                </div>
                <textarea name="applicant_full_address" class="form-control" cols="30">{{ $applicant_full_address }}</textarea>
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Beneficiary Name</label>
                </div>
                <input name="beneficiary_name" value="{{ $beneficiary_name }}" id="beneficiary_name"
                    class="form-control" type="text">
                <input name="beneficiary_id" id="beneficiary_id" value="{{ $beneficiary_id }}" class="form-control"
                    type="hidden">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Beneficiary Full address</label>
                </div>
                <textarea name="beneficiary_full_address" class="form-control" cols="30">{{ $beneficiary_full_address }}</textarea>
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Description</label>
                </div>
                <input name="descriptions" value="{{ $description }}" id="descriptions" class="form-control"
                    type="text">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">

                    <label class="control-label">Currency</label>
                </div>

                <input name="Currency" id="Currency" class="form-control" type="text"
                    value="{{ CommonHelper::get_curreny_name($purchase_request_data[0]->currency_id) }}">
                <input name="Currency_id" id="Currency_id" class="form-control" type="hidden"
                    value="{{ $purchase_request_data[0]->currency_id }}">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">

                    <label class="control-label">Amount</label>
                </div>
                <input name="amount" value="{{ $amount }}" id="d_t_amount_1" class="form-control"
                    type="text">
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
                <input value="{{ $insurance }}" name="insurance" id="insurance" class="form-control"
                    type="text">
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
                        <option value="{{ $applicant_bank->id }}"
                            {{ $purchase_request_data[0]->applicant_bank && $purchase_request_data[0]->applicant_bank == $applicant_bank->id ? 'selected' : '' }}>
                            {{ $applicant_bank->name }}({{ $applicant_bank->type }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="flex_lable">

                <div class="lable_text">
                    <label class="control-label">Advising Bank </label>

                </div>
                <input name="advising_bank" value="{{ $advising_bank }}"  id="advising_bank" class="form-control" type="text">
                <input name="advising_bank_id" value="{{ $advising_bank_id }}" id="advising_bank_id"
                    class="form-control" type="hidden">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">

                <div class="lable_text">
                    <label class="control-label">Account No </label>
                </div>
                <input name="advising_bank_account_no" value="{{ $advising_bank_account_no }}"
                    id="advising_bank_account_no" class="form-control" type="text">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Swift code </label>
                </div>
                <input name="advising_bank_swift_code" value="{{ $advising_bank_swift_code }}"
                    id="advising_bank_swift_code" class="form-control" type="text">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Inter mediary Bank</label>
                </div>
                <input name="inter_mediary_bank"  value="{{ $inter_mediary_bank }}" id="inter_mediary_bank" class="form-control" type="text">
                <input name="inter_mediary_bank_id" value="{{ $inter_mediary_bank_id }}" id="inter_mediary_bank_id"
                    class="form-control" type="hidden">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">

                <div class="lable_text">
                    <label class="control-label">Account No </label>
                </div>
                <input name="inter_mediary_bank_account_no" value="{{$inter_mediary_bank_account_no}}" id="inter_mediary_bank_account_no" class="form-control"
                    type="text">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Swift code </label>
                </div>
                <input name="inter_mediary_bank_swift_code" value="{{ $advising_bank_swift_code }}"
                    id="inter_mediary_bank_swift_code" class="form-control" type="text">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Sub Description</label>
                </div>
                <input name="sub_description" id="sub_description" value="{{ $sub_description }}"
                    class="form-control" type="text">
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">

                    <label class="control-label">Partail shipment</label>
                </div>
                <input name="partial_shipment" id="partial_shipment" class="" type="checkbox" value="1"
                    {{ $partial_shipment == 1 ? 'checked' : '' }}>
            </div>

        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">

                    <label class="control-label">Transhipment</label>
                </div>
                <input name="transhipment" id="transhipment" class="" type="checkbox" value="1"
                    {{ $transhipment == 1 ? 'checked' : '' }}>
            </div>

        </div>
        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">

                    <label class="control-label">Shipment From</label>
                </div>
                <input name="shipment_from" value="{{ $shipment_from }}" id="shipment_from" class="form-control"
                    type="text">
            </div>
        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">

                    <label class="control-label">Shipment To</label>
                </div>
                <input name="shipment_to" value="{{ $shipment_to }}" id="shipment_to" class="form-control"
                    type="text">
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
                <input name="fob" value="1" id="fob" class="form-control" type="checkbox"
                    {{ $fob == 1 ? 'checked' : '' }}>
            </div>
        </div>


        <div class="form-group">
            <div class="flex_lable">

                <div class="lable_text">
                    <label class="control-label">CFR</label>
                </div>
                <input name="cfr" value="1" id="cfr" class="form-control" type="checkbox"
                    {{ $cfr == 1 ? 'checked' : '' }}>
            </div>

        </div>


        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">CPT</label>
                </div>
                <input name="cpt" value="1" id="cpt" class="form-control" type="checkbox"
                    {{ $cpt == 1 ? 'checked' : '' }}>
            </div>
        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Lc At Sight </label>
                </div>
                <input name="sight" value="1" id="sight" class="form-control" type="checkbox"
                    {{ $sight == 1 ? 'checked' : '' }}>
            </div>
        </div>

        <div class="form-group hide">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">BL DATE</label>
                </div>
                <input name="lc_lg_bl_date" value="{{ $lc_lg_bl_date }}" id="lc_lg_bl_date" class="form-control"
                    type="date">
            </div>
        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Days from</label>
                </div>
                <input name="days_from" value="{{ $days_from }} Days from BL DATE" id="days_from"
                    class="form-control" type="text">
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
                <input name="origin" value="{{ $origin }}" id="origin" class="form-control"
                    type="text">
            </div>
        </div>

        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">

                    <label class="control-label">Hs Code</label>
                </div>
                <input name="hs_code" value="{{ $hs_code }}" step="any" id="hs_code"
                    class="form-control" type="text">
            </div>
        </div>



        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">
                    <label class="control-label">Latest Shipment date</label>
                </div>
                <input name="latest_shipment_date" value="{{ $latest_shipment_date }}" id="latest_shipment_date"
                    class="form-control" type="date">
            </div>

        </div>


        <div class="form-group">
            <div class="flex_lable">
                <div class="lable_text">

                    <label class="control-label">Expirty Date</label>
                </div>
                <input name="expirty_date" value="{{ $expirty_date }}" id="expirty_date" class="form-control"
                    type="date">
            </div>

        </div>





    </div>