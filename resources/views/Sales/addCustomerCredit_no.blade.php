<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

$accType = Auth::user()->acc_type;

if($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
?>

<?php $cr_no = SalesHelper::generateCreditNotNo(date('y'), date('m')); ?>

@extends('layouts.default')
@section('content')

    @include('select2')
    @include('number_formate')

    <div class="well well_N">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">

                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                {!! Form::open(['url' => 'sad/addCreditNote?m='.$m, 'id' => 'bankPaymentVoucherForm']) !!}

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                    <label class="sf-label">Credit No.</label>
                                                    <span class="requiredField"><strong>*</strong></span>
                                                    <input readonly class="form-control" type="text" name="credit_not_no" value="{{$cr_no}}" />
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                    <label class="sf-label">Credit Date</label>
                                                    <span class="requiredField"><strong>*</strong></span>
                                                    <input class="form-control" type="date" name="credit_date" value="{{date('Y-m-d')}}" />
                                                </div>

                                                <?php $buyers_data = CommonHelper::byers_name($buyer_id); ?>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                    <label class="sf-label">Buyer's Name</label>
                                                    <input readonly class="form-control" type="text" name="customer" value="{{$buyers_data->name ?? ''}}"/>
                                                    <input type="hidden" name="byer_id" value="{{$buyer_id}}">
                                                </div>

                                                <input type="hidden" id="acc_id" name="acc_id" value="814">

                                            </div>

                                            <div class="lineHeight">&nbsp;</div>

                                            <div class="well">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive">
                                                                    <?php $counter = 1; ?>

                                                                    @foreach($values as $row)
                                                                        <?php
                                                                            $invoice_data = SalesHelper::get_data_from_invoice($row, $type);
                                                                            $so_id = $invoice_data->so_id ?? 0;

                                                                            if ($type == 1) {
                                                                                $return_qty = SalesHelper::return_qty($type, $invoice_data->so_data_id, $invoice_data->gd_no);
                                                                            } else {
                                                                                $return_qty = SalesHelper::return_qty($type, $row, $invoice_data->gi_no);
                                                                            }

                                                                            $bacth = ($type == 1) 
                                                                                ? SalesHelper::get_batch_code($row, $type) 
                                                                                : SalesHelper::get_batch_code($invoice_data->so_data_id, $type);
                                                                            $bacth = $bacth->batch_code ?? 0;
                                                                        ?>

                                                                        <input type="hidden" name="count[]" value="{{$counter}}">
                                                                        <input type="hidden" name="item_id{{$counter}}" value="{{$invoice_data->item_id}}"/>
                                                                        <input type="hidden" name="invoice_data_id{{$counter}}" value="{{$row}}"/>
                                                                        <input type="hidden" name="si_data_id{{$counter}}" value="{{$invoice_data->id}}"/>
                                                                        <input type="hidden" name="so_data_id{{$counter}}" value="{{$invoice_data->so_data_id}}"/>
                                                                        <input type="hidden" name="batch_code{{$counter}}" value="{{$bacth}}"/>
                                                                        <input type="hidden" name="gi_no{{$counter}}" value="{{$invoice_data->gi_no}}"/>
                                                                        <input type="hidden" name="gi_date{{$counter}}" value="{{$invoice_data->gi_date}}"/>
                                                                        <input type="hidden" id="actual_qty{{$counter}}" name="actual_qty{{$counter}}" 
                                                                               value="{{ $invoice_data->qty - $return_qty }}">

                                                                        <h5>
                                                                            <strong>({{$counter}}). GI No.:</strong> {{$invoice_data->gi_no}}
                                                                            &nbsp;&nbsp;&nbsp;
                                                                            <strong>GI Date:</strong> {{$invoice_data->gi_date}}
                                                                            &nbsp;&nbsp;&nbsp;
                                                                            <strong>Qty:</strong> {{number_format($invoice_data->qty, 2)}}
                                                                            &nbsp;&nbsp;&nbsp;
                                                                            <strong>Prev Return:</strong> {{number_format($return_qty, 2)}}
                                                                            &nbsp;&nbsp;&nbsp;
                                                                            <strong>Rate:</strong> {{number_format($invoice_data->rate, 2)}}
                                                                        </h5>

                                                                        <table class="table table-bordered sf-table-th sf-table-form-padding">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center">Item</th>
                                                                                    <th class="text-center">Bag Qty</th>
                                                                                    <th class="text-center">UOM</th>
                                                                                    <th class="text-center">Invoice Qty</th>
                                                                                    <th class="text-center">Prev Return Qty</th>
                                                                                    <th class="text-center">Balance Qty</th>
                                                                                    <th class="text-center">Return QTY</th>
                                                                                    <th class="text-center">Rate</th>
                                                                                    <th class="text-center">Amount</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="text-center">{{ CommonHelper::get_item_name($invoice_data->item_id) }}</td>
                                                                                    <td class="text-center">{{ number_format((float)($invoice_data->bag_qty ?? 0), 2) }}</td>
                                                                                    <td class="text-center">
                                                                                        <?php
                                                                                            $uomName = '';
                                                                                            if (!empty($invoice_data->uom)) {
                                                                                                $uomName = CommonHelper::get_uom_name($invoice_data->uom);
                                                                                                $uomName = $uomName ?: $invoice_data->uom;
                                                                                            } else {
                                                                                                $uom_data = CommonHelper::get_subitem_detail($invoice_data->item_id);
                                                                                                $uom_data = explode(',', $uom_data);
                                                                                                $uom_id = $uom_data[0] ?? 0;
                                                                                                $uomName = CommonHelper::get_uom_name($uom_id);
                                                                                            }
                                                                                        ?>
                                                                                        {{ $uomName }}
                                                                                    </td>
                                                                                    <td class="text-center">{{ number_format((float)$invoice_data->qty, 2) }}</td>
                                                                                    <td class="text-center">{{ number_format((float)$return_qty, 2) }}</td>
                                                                                    <td class="text-center">{{ number_format((float)($invoice_data->qty - $return_qty), 2) }}</td>
                                                                                    <td>
                                                                                        <input type="text" 
                                                                                               class="form-control number zerovalidate" 
                                                                                               name="qty{{$counter}}" 
                                                                                               id="qty{{$counter}}" 
                                                                                               value="{{ number_format((float)($invoice_data->qty - $return_qty), 2, '.', '') }}"
                                                                                               onkeyup="calculateRow({{$counter}})"
                                                                                               onblur="calculateRow({{$counter}})"/>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input readonly type="text" class="form-control number" 
                                                                                               name="rate{{$counter}}" 
                                                                                               id="rate{{$counter}}" 
                                                                                               value="{{$invoice_data->rate}}"/>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input readonly type="text" class="form-control number amount" 
                                                                                               name="amount{{$counter}}" 
                                                                                               id="amount{{$counter}}" value=""/>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>

                                                                        <input type="hidden" name="warehouse{{$counter}}" value="{{$invoice_data->warehouse_id}}"/>
                                                                        <?php $counter++; ?>
                                                                    @endforeach

                                                                    <input type="hidden" name="so_id" value="{{$so_id ?? 0}}"/>
                                                                    <input type="hidden" name="type" id="type" value="{{$type}}"/>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label class="sf-label">Description <span class="requiredField"><strong>*</strong></span></label>
                                                        <textarea required name="description_1" id="description_1" class="form-control requiredField" rows="3" style="resize:none;"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        {!! Form::submit('Submit', ['class' => 'btn btn-success submit']) !!}
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function() {
    $('.number').number(true, 2);

    // Initialize calculations on page load
    $('[id^=qty]').each(function() {
        var rowNo = this.id.replace('qty', '');
        if (rowNo) calculateRow(rowNo);
    });
});

// Calculate single row - Amount updates when Return QTY changes
function calculateRow(count) {
    var qty   = parseFloat($('#qty' + count).val()) || 0;
    var rate  = parseFloat($('#rate' + count).val()) || 0;
    var actualQty = parseFloat($('#actual_qty' + count).val()) || 0;

    // Prevent return qty from exceeding balance
    if (qty > actualQty) {
        alert('Returned QTY cannot be greater than Balance Qty!');
        $('#qty' + count).val(actualQty.toFixed(2));
        qty = actualQty;
    }

    // Calculate Amount
    var amount = qty * rate;
    $('#amount' + count).val(amount.toFixed(2));
}
</script>

@endsection
