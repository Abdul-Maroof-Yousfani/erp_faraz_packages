<?php 
use App\Helpers\CommonHelper;
$i =1;
?>
@foreach($quotation_data as $q_data)
    
    <tr class="m-tab main" id="RemoveRows{{ $i }}">
        <td>
            <select onchange="get_item_name('{{ $i }}')" class="form-control item_id itemsclass" name="item_id[]" id="item_id{{ $i }}">
                @foreach($sub_item as $val)
                    <option @if($q_data->item_id == $val->id) selected @endif value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic . '@' . $val->pack_size . '@' . $val->type . '@' . $val->color }}">{{ $val->item_code . ' -- ' . $val->sub_ic . ' ' . $val->pack_size . ' ' . $val->uom_name . ' ' . $val->type . ' ' . $val->color }}</option> 
                @endforeach
            </select>             
        </td>

        <input style="display: none" class="form-control" type="text" name="item_code[]" id="item_code" value="{{$q_data->item_code}}">
        <input style="display: none" class="form-control" type="text" name="thickness[]" id="">
        <input style="display: none" class="form-control"  type="text" name="diameter[]" id="">
        <td>
            <input readonly type="text" name="pack_size[]" id="pack_size{{ $i }}" class="form-control" value="" />
        </td>
        <td>
            <input readonly class="form-control" type="text" name="uom[]" id="uom_id{{ $i }}" value="" />
        </td>
        <td>
            <input readonly type="text" name="color[]" id="color{{ $i }}" class="form-control" />
        </td>
        <td>
            <input class="form-control" onkeyup="calculation_amount()" type="text" name="qty[]" id="qty" value="{{$q_data->qty}}">
        </td>
        <td>
            <input class="form-control" onkeyup="calculation_amount()" type="text" name="rate[]" id="rate" value="{{$q_data->unit_price}}">
        </td>
        <td>
            <input readonly class="form-control" type="text" name="total[]" id="total" value="{{$q_data->total_amount}}">
        </td>
        @if($i == 1)
            <td class="text-center">
                <a href="#" onclick="AddMoreDetails()" class="btn btn-primary">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </a>
            </td>
        @else
            <td class="text-center">
                <a href="#" class="btn btn-sm btn-danger" onclick="RemoveSection('{{ $i }}')">
                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                </a>
            </td>
            
        @endif
        
    </tr>
    
    <script>
        $(document).ready(function(){
            get_item_name('{{ $i }}');

            calculation_amount();

        });
    </script>
    @php
    $i++;   
    @endphp
@endforeach
<input type="hidden" name="customer_id" value="{{$saleQuot->customer_id}}" id="customer_id">
<input type="hidden" name="sales_tax_group" value="{{$saleQuot->sale_tax_group}}" id="sales_tax_group">
<input type="hidden" name="sales_tax_rate" value="{{$saleQuot->sales_tax_rate}}" id="sales_tax_rate">
<input type="hidden" name="further_tax_group1" value="{{$saleQuot->further_tax_group}}" id="further_tax_group1">
<input type="hidden" name="further_tax1" value="{{$saleQuot->further_tax}}" id="further_tax1">
<input type="hidden" name="advance_tax_group1" value="{{$saleQuot->advance_tax_group}}" id="advance_tax_group1">
<input type="hidden" name="advance_tax1" value="{{$saleQuot->advance_tax}}" id="advance_tax1">
<input type="hidden" name="cartage_amount1" value="{{$saleQuot->cartage_amount}}" id="cartage_amount1">
<input type="hidden" name="customer_type" value="{{$saleQuot->customer_type}}" id="customer_type">
<input type="hidden" name="prospect_id" value="{{$saleQuot->prospect_id}}" id="prospect_id">
<input type="hidden" name="currency" value="{{$saleQuot->currency_id}}" id="currency">

