@extends('layouts.default')
@section('content')
@include('select2')

<?php
use App\Helpers\CommonHelper;

$Sales_Order = [];
$Machine  = [];
$Operator   = [];
?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Production</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Create Production Packing</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
          <!-- <ul class="cus-ul2">
                <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                </li>
            </ul>  -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                               <form action="{{route('FarProduction.Packing')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" id="for_disabled_btn">

                                    <div class="row">
                                        <div class="col-lg-12 cus-tab">
                                            <div class="row qout-h" style="padding: 10px">
                                                <div class="col-md-12 bor-bo">
                                                    <div class="pips_create">
                                                        <h1 style="display: inline-block;">Packing</h1>
                                                    </div>
                                                   
                                                </div>
                                                <div class="col-md-12 padt pos-r">


                                                    <div class="row">
                                                   
                                                    
                                                    
                                                        <div class="col-md-2">
                                                            <label for="">Date</label>
                                                            <input 
                                                                type="date"
                                                                readonly
                                                                id="date"
                                                                class="form-control date"
                                                                value="{{ $out_source_productions_item->date }}" 
                                                            >
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">Initial Qty</label>
                                                            <input 
                                                                type="number"
                                                                readonly
                                                                id="initial_qty"
                                                                name="initial_qty"
                                                                class="form-control initial_qty"
                                                                value="{{ $out_source_productions_item->total_qty }}"
                                                            >
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">Selected Cutting & Sealing</label>
                                                            <input 
                                                                type="text"  
                                                                readonly
                                                                id="roll_item_name"
                                                                class="form-control initial_qty"
                                                                value="{{ CommonHelper::get_item_name($out_source_productions_item->item_id) }}"
                                                            >
                                                            <input type="hidden" name="roll_id" value="{{ $out_source_productions_item->id }}">
                                                            <input type="hidden" name="cutting_type" value="{{ $cutting_type }}">

                                                        </div>

                                                       
                                                    </div>
                                                    <?php
$global_avg_rate=0;
$global_avg_qty=0;
$global_avg_amt=0;
                                                    ?>
            <table class="hide table table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Item</th>
                    <th>Issued Qty</th>
                    <th>Consumed Qty</th>
                    <th>Not Used Qty</th>
                    <th>Rate</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
    @forelse($out_source_productions_details as $key => $detail)
        @php 
            $allowed_qty = ($detail->qty ?? $detail->gala_qty ?? 0) - ($detail->used_qty ?? 0);
            
            $rate = App\Helpers\CommonHelper::getAccurateFIFOAvgRate($detail->item_id, $out_source_productions_item->date);

            $base_qty = $detail->qty ?? $detail->gala_qty ?? 0;

$global_avg_amt += $rate * $base_qty;
$global_avg_qty += $base_qty;

        @endphp
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $detail->item_id }}</td>
            <td>{{ $base_qty }}</td>
            <td>{{ $detail->used_qty ?? 0 }}</td>

            {{-- Row-specific input --}}
            <td>
                <input 
                    type="text" 
                    class="form-control used_qty" 
                    data-max="{{ $allowed_qty }}" 
                    value="{{ $allowed_qty }}" 
                    min="0"
                    oninput="validateUsedQty(this)"
                />
            </td>

            <td>{{ $rate }}
            <input type="hidden" name="raw_item_id[]" value="{{ $detail->item_id }}">
            </td>
            <td>{{ number_format(($detail->qty ?? $detail->gala_qty ?? 0) * $rate, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center text-danger">No Detail Found</td>
        </tr>
    @endforelse

    @php
    $global_avg_rate = $global_avg_qty > 0 ? $global_avg_amt / $global_avg_qty : 0;
@endphp
</tbody>
        </table>
                                                    <div class="row">
                                                        {{-- <div class="col-md-3">
                                                            <label for="">Issued Item</label>
                                                            <select 
                                                                id="issued_item_id"
                                                                readonly
                                                                class="form-control item issued_item_id">
                                                                    <option value="">Select Item</option>
                                                                   @foreach($item as $Key => $val)
                                                                        <option @if( $out_source_productions->item_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->sub_ic }}</option>
                                                                   @endforeach
                                                            </select>

                                                        </div> --}}

                                                        {{-- <div class="col-md-3">
                                                            <label for="">Avg. Rate</label>
                                                            <input 
                                                                type="text"
                                                                readonly
                                                                id="issued_rate"
                                                                class="form-control issued_rate"
                                                            > --}}
                                                            <input id="raw_avg_amt" type="hidden" class="raw_avg_amt" value="{{ $global_avg_amt }}"/>
                                                        {{-- </div> --}}

                                                        

                                                        <div class="col-md-2">
                                                            <label for="">Not Used Qty</label>
                                                            <input 
                                                                type="number"
                                                                readonly
                                                                onkeyup="cal_amt()"
                                                                id="issued_qty"
                                                                class="form-control issued_qty"
                                                                value="{{ $out_source_productions_item->total_qty - ($out_source_productions_item->total_used_qty ?? 0) }}"
                                                            >
                                                        </div>


                                                        <div class="col-md-2">
                                                            <label for="">Used Cutting & Sealing</label>
                                                            <input 
                                                                type="text"
                                                                id="used_qty_total"
                                                                name="used_qty_total"
                                                                class="form-control move-next used_qty_total"
                                                                onkeyup="cal_amt()"
                                                                tabindex="1"
                                                                autofocus
                                                            >
                                                        </div>


                                                        <div class="col-md-2">
                                                            <label for="">Remaining Cutting & Sealing</label>
                                                            <input 
                                                                type="number"
                                                                readonly
                                                                id="remaining_qty"
                                                                class="form-control date"
                                                            >
                                                        </div>

                                                       
                                                       
                                                    </div>
                                                    
                                                </div>
                                                
                                    

                                                {{-- <div class="col-md-12 bor-bo">
                                                    <div class="pips_create">
                                                        <h1 style="display: inline-block;">Received Finish Good from Supplier</h1>
                                                         <a class="btn btn-primary mr-1" style="float: right;" onclick="addRawMaterial()">add Finish Good</a>
                                                    </div>

                                                </div> --}}
                                                {{-- <div class="col-md-12">
                                                    <hr> 
                                                    <div class="row">
                                                        <div class="col-md-12 text-right mr-4">
                                                            <a onclick="addRawMaterial()" class="btn btn-primary mr-1">Add More Packing</a>
                                                        </div>
                                                    </div>
                                                </div> --}}



                                                <h1 style="display: inline-block;">Packing Item Detail</h1>

                                                <div class="col-md-12 padt pos-r" id="out_source_production_data_to_finish_received" >

                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label for="">Item</label>
                                                             <select style="width: 100% !important;"
                                                                name="item_id[]"
                                                                id="item_id1"
                                                                class="form-control requiredField select2" disabled>
                                                                <option value="">Select</option>
                                                                @foreach($sub_item as $val)
                                                                    <option
                                                                        value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic }}"
                                                                        {{ $out_source_productions_item->item_id == $val->id ? 'selected' : '' }}>
                                                                        {{ $val->item_code . ' -- ' . $val->sub_ic }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="item_id[]" value="{{ $val->id }}">
                                                        </div>
                                                        
                                                        

                                                        <div class="col-md-2">
                                                            <label for="">Operator</label>
                                                             <select style="width: 100% !important;"
                                                                name="operator_id[]"
                                                                id="operator_id1"
                                                                class="form-control requiredField select2">
                                                                <option value="">Select</option>
                                                                @foreach($operators as $val)
                                                                    <option
                                                                        value="{{$val->id}}">
                                                                        {{ $val->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">Machine</label>
                                                             <select style="width: 100% !important;"
                                                                name="machine_id[]"
                                                                id="machine_id1"
                                                                class="form-control requiredField select2">
                                                                <option value="">Select</option>
                                                                @foreach($machines as $val)
                                                                    <option
                                                                        value="{{$val->id}}">
                                                                        {{ $val->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label for="">Shift</label>
                                                             <select style="width: 100% !important;"
                                                                name="shift_id[]"
                                                                id="shift_id1"
                                                                class="form-control requiredField select2">
                                                                <option value="">Select</option>
                                                                @foreach($shifts as $val)
                                                                    <option
                                                                        value="{{$val->id}}">
                                                                        {{ $val->shift_type_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                       
                                                        <div class="col-md-2">
                                                            <label for="">Date</label>
                                                            <input 
                                                                type="date"
                                                                name="date[]"
                                                                id="date_1"
                                                                class="form-control move-next date"
                                                                value="{{ date('Y-m-d') }}"
                                                                required 
                                                            >
                                                            
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="">C&S Qty</label>
                                                            <input 
                                                                type="text" 
                                                                name="qty[]"
                                                                id="qty_1"
                                                                class="form-control move-next qty_1 requiredField"
                                                                onkeyup="cal_amt()"
                                                                required 

                                                            >
                                                        </div>


                                                        <div class="col-md-1">
                                                            <label for="">Qty (Bags)</label>
                                                            <input 
                                                                type="text" 
                                                                name="bags_qty[]"
                                                                id="bags_qty_1"
                                                                class="form-control move-next bags_qty_1 requiredField"
                                                                onkeyup="cal_amt()"
                                                                required 

                                                            >
                                                        </div>

                                                       

                                                        {{-- <div class="col-md-1">
                                                            <label for="">Rate</label>
                                                            <input 
                                                                type="text"
                                                                id="finish_rate_1"
                                                                name="finish_rate[]"
                                                                class="form-control move-next finish_rate_1"
                                                                onkeyup="cal_amt()"
                                                                required
                                                            >
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="">Amount</label>
                                                            <input 
                                                                type="number"
                                                                readonly
                                                                id="finish_amount_1"
                                                                name="finish_amount[]"
                                                                class="form-control finish_amount_1"
                                                                value="0" 
                                                                required
                                                            >
                                                        </div> --}}

                                                        {{-- <div class="col-md-1">
                                                            <label for="">End Amount</label>
                                                            <input 
                                                                type="number"
                                                                readonly
                                                                id="finish_end_amount_1"
                                                                name="finish_end_amount[]"
                                                                class="form-control finish_end_amount_1"
                                                                value="0" 
                                                                required
                                                            >
                                                        </div> --}}
                                                    
                                                    </div>
                                                    
                                                </div>

                                                
                                                <div class="col-md-12 padt pos-r" id="" >

                                                    
                                                   

                                                    <div class="row">
                                                    
                                                        <div class="col-md-12 padtb text-right">
                                                            <button type="submit" id="save" class="btn btn-primary mr-1">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            <div class="row borderBtmMnd pTB40">
                   
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
<script>

    $('.select2').select2()

    let count = 2;
    function addRawMaterial() 
    {
        let html = `
                    <div class="row" id="row_${count}">
                        <div class="col-md-2">
                            <label for="">Item</label>
                             <select style="width: 100% !important;"
                                 name="item_id[]"
                                id="item_id${count}"
                                class="form-control requiredField select2">
                                <option value="">Select</option>
                                @foreach($sub_item as $val)
                                    <option
                                        value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic }}">
                                        {{ $val->item_code . ' -- ' . $val->sub_ic }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        

                        <div class="col-md-2">
                            <label for="">Operator</label>
                                <select style="width: 100% !important;"
                                name="operator_id[]"
                                id="operator_id${count}"
                                class="form-control requiredField select2">
                                <option value="">Select</option>
                                @foreach($operators as $val)
                                    <option
                                        value="{{$val->id}}">
                                        {{ $val->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Machine</label>
                                <select style="width: 100% !important;"
                                name="machine_id[]"
                                id="machine_id${count}"
                                class="form-control requiredField select2">
                                <option value="">Select</option>
                                @foreach($machines as $val)
                                    <option
                                        value="{{$val->id}}">
                                        {{ $val->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="">Shift</label>
                                <select style="width: 100% !important;"
                                name="shift_id[]"
                                id="shift_id${count}"
                                class="form-control requiredField select2">
                                <option value="">Select</option>
                                @foreach($shifts as $val)
                                    <option
                                        value="{{$val->id}}">
                                        {{ $val->shift_type_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="">Date</label>
                            <input 
                                type="date"
                                name="date[]"
                                id="date_${count}"
                                class="form-control move-next date"
                                value="{{ date('Y-m-d') }}" 
                                required 

                            >
                            
                        </div>

                        <div class="col-md-1">
                            <label for="">C&S Qty</label>
                            <input 
                                type="text" 
                                name="qty[]"
                                id="qty_${count}"
                                class="form-control move-next qty_${count} requiredField"
                                onkeyup="cal_amt()"
                                required 

                            >
                        </div>

                        <div class="col-md-1">
                            <label for="">Qty (KG)</label>
                            <input 
                                type="text" 
                                name="bags_qty[]"
                                id="bags_qty_${count}"
                                class="form-control move-next bags_qty_${count} requiredField"
                                onkeyup="cal_amt()"
                                required 

                            >
                        </div>
                      
                        {{-- <div class="col-md-1">
                            <label for="">rate</label>
                            <input 
                                type="text"
                                id="finish_rate_${count}"
                                name="finish_rate[]"
                                class="form-control move-next finish_rate_${count} requiredField"
                                onkeyup="cal_amt()" 
                                required
                            >
                        </div>

                        <div class="col-md-1">
                            <label for="">Amount</label>
                            <input 
                                type="number"
                                readonly
                                id="finish_amount_${count}"
                                name="finish_amount[]"
                                class="form-control move-next finish_amount_${count} requiredField"
                                value="0" 
                                required
                            >
                        </div> --}}


                        {{-- <div class="col-md-1">
                            <label for="">End Amount</label>
                            <input 
                                type="number"
                                readonly
                                id="finish_end_amount_${count}"
                                name="finish_end_amount[]"
                                class="form-control move-next finish_end_amount_${count} requiredField"
                                value="0" 
                                required
                            >
                        </div> --}}
                    
                        <div class="col-md-1" style="padding-top: 41px;">
                            <a class="btn btn-danger mr-1" style="" onclick="removeDiv('row_${count}')">-</a>
                        </div>
                    
                    </div>
                    `;

            $('#out_source_production_data_to_finish_received').append(html)        

        count++
    }

function validateUsedQty(input) {
    let maxAllowed = parseFloat(input.getAttribute('data-max')) || 0;
    let currentVal = parseFloat(input.value) || 0;

    if (currentVal > maxAllowed) {
        alert("Used Qty cannot be greater than remaining Qty (" + maxAllowed + ")");
        input.value = maxAllowed; // reset to max
    }
    if (currentVal < 0) {
        input.value = 0;
    }
}


function cal_amt() {
    let issued_qty = parseFloat($('.issued_qty').val()) || 0;
    let used_qty_total = parseFloat($('.used_qty_total').val()) || 0;
    let issued_rate = parseFloat($('.issued_rate').val()) || 0;
    let raw_avg_amt = parseFloat($('.raw_avg_amt').val()) || 0;
    let initial_qty = parseFloat($('.initial_qty').val()) || 0;

    if (used_qty_total > issued_qty) {
        alert("Used Qty cannot be greater than Not Used Qty!");
        $('.used_qty_total').val(issued_qty); // reset to max allowed
        used_qty_total = issued_qty;
    }

    // Always update remaining qty
    $('#remaining_qty').val(issued_qty - used_qty_total);
    $('#issued_rate').val(raw_avg_amt / used_qty_total);

    // Get all qty fields
    let qty_inputs = $("input[name='qty[]']");
    let printed_qty_inputs = $("input[name='printed_qty[]']");
    let count = qty_inputs.length;

    // ---- CASE 1: Auto distribute when Used Qty changes ----
    if (count > 0 && used_qty_total > 0 && event?.type === "keyup" && $(event.target).hasClass("used_qty_total")) {
        let qty_avg = used_qty_total / count;
        qty_inputs.each(function () {
            $(this).val(qty_avg.toFixed(2));
        });
        
    }

    // ---- CASE 2: Validate manual qty edits ----
    let sum_qty = 0;
    qty_inputs.each(function () {
        sum_qty += parseFloat($(this).val()) || 0;
    });

    if (sum_qty > used_qty_total) {
        alert("Total Raw Qty cannot exceed Used Qty!");
        $(event.target).val(0); // reset the last changed input
    }

    
     // ---- CASE 3: Calculate Finish Amount = Qty * Rate ----
$("[id^='qty_']").each(function (index) {
    let row = index + 1;

    let qty = parseFloat($(`#qty_${row}`).val()) || 0;
    let rate = parseFloat($(`#finish_rate_${row}`).val()) || 0;

    let amount = qty * rate;
    let raw_amount = raw_avg_amt / initial_qty;
    let end_amount = amount + raw_amount;

    $(`#finish_amount_${row}`).val(amount.toFixed(2));
    $(`#finish_end_amount_${row}`).val(end_amount.toFixed(2));
});


    
}








    function removeDiv(div) {
        $('#'+div).remove()
    }

    
    
    $(document).on("keydown", ".move-next", function(e) {
        if (e.key === "Tab") {
            e.preventDefault(); // Stop default tabbing
    
            // Get all inputs & selects
            let fields = $(".move-next");
            let index = fields.index(this);
    
            if (index > -1 && index + 1 < fields.length) {
                fields.eq(index + 1).focus(); // Move to next field
            }
        }
    });

</script>

@endsection