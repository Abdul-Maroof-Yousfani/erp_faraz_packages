<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$ids = array_values(array_filter((array) $ids));
$grnMasters = collect();
$allItems = collect();
$totalAmount = 0;
$salesTaxAmount = 0;
$salesTaxAccId = 0;
$termsOfPayment = '';
$dueDate = $currentDate;
$billDate = $currentDate;
$poDescription = [];

foreach ($ids as $id) {
    $grn = CommonHelper::get_goodreciptnotedata($id, 0);
    if (empty($grn)) {
        continue;
    }

    $grnTotal = 0;
    $purchaseRequest = CommonHelper::get_goodreciptnotedata($id, 1);
    $currencyRate = $purchaseRequest->currency_rate ?? 1;
    $supplier = CommonHelper::getSupplierDetail($grn->supplier_id);
    $noDays = $supplier->no_of_days ?? 0;
    $dueDate = date('Y-m-d', strtotime($grn->grn_date . ' + ' . $noDays . ' days'));
    $billDate = $grn->bill_date ?: $billDate;

    $rawTerms = $purchaseRequest->terms_of_paym ?? '';
    if($rawTerms == 1 || $rawTerms == 'Advance') {
        $termsOfPayment = 'Advance';
    } elseif($rawTerms == 2 || $rawTerms == 'Against Delivery') {
        $termsOfPayment = 'Against Delivery';
    } elseif($rawTerms == 3 || $rawTerms == 'Credit') {
        $termsOfPayment = 'Credit';
    } elseif($termsOfPayment == '') {
        $termsOfPayment = $rawTerms;
    }

    $poDate = '';
    if($grn->type == 0 && !empty($purchaseRequest->purchase_request_date)) {
        $poDate = CommonHelper::changeDateFormat($purchaseRequest->purchase_request_date);
    }
    $poDescription[] = trim($grn->po_no . '--' . $poDate, '-');

    $rawSalesTax = (string) ($purchaseRequest->sales_tax ?? '0');
    $rawSalesTaxParts = explode('@', $rawSalesTax);
    $salesTaxId = (float) ($rawSalesTaxParts[0] ?? 0);
    if ($salesTaxAccId == 0) {
        $salesTaxAccId = (int) ($purchaseRequest->sales_tax_acc_id ?? 0);
        if ($salesTaxAccId === 0 && !empty($rawSalesTaxParts[1])) {
            $salesTaxAccId = (int) $rawSalesTaxParts[1];
        }
    }

    $grnItems = CommonHelper::get_grndata($id);
    foreach ($grnItems as $item) {
        $poLine = $item->po_data_id ? DB::Connection('mysql2')->table('purchase_request_data')->where('id', $item->po_data_id)->first() : null;
        $rateCalBy = (int)($poLine->rate_cal_by ?? 2);
        $packSize = 0;
        if (!empty($poLine) && !empty($poLine->bags_qty) && (float) $poLine->bags_qty > 0) {
            $packSize = (float) $poLine->purchase_approve_qty / (float) $poLine->bags_qty;
        }
        if ($packSize <= 0) {
            $subItemDetail = CommonHelper::get_subitem_detail2($item->sub_item_id);
            $packSize = (float) ($subItemDetail->pack_size ?? 0);
        }

        $returnQty = ReuseableCode::purchase_return_qty_from_grn_line($item->id);
        $qty = $item->purchase_recived_qty - $item->qc_qty;
        $actualQty = $qty - $returnQty;
        $rateBasisQty = $actualQty;
        if ($rateCalBy === 1) {
            $rateBasisQty = $packSize > 0 ? ($actualQty / $packSize) : $actualQty;
        } elseif ($rateCalBy === 3) {
            $rateBasisQty = $actualQty * 2.2;
        }

        $amount = $rateBasisQty * $item->rate * $currencyRate;
        $discountPercent = $item->discount_percent;
        $discountAmount = $discountPercent > 0 ? (($amount / 100) * $discountPercent) : 0;
        $netAmount = $amount - $discountAmount;
        $totalAmount += $netAmount;
        $grnTotal += $netAmount;

        $item->display_qty = $qty;
        $item->return_qty = $returnQty;
        $item->actual_qty = $actualQty;
        $item->rate_cal_by = $rateCalBy;
        $item->rate_cal_by_label = $rateCalBy === 1 ? 'By BAGS' : ($rateCalBy === 3 ? 'By LBS' : 'By KGS');
        $item->line_amount = $amount;
        $item->line_discount_amount = $discountAmount;
        $item->line_net_amount = $netAmount;
        $item->master_grn_no = $grn->grn_no;
        $allItems->push($item);
    }

    $configuredSalesTaxAmount = (float) ($purchaseRequest->sales_tax_amount ?? 0);
    if ($configuredSalesTaxAmount > 0) {
        $salesTaxAmount += $configuredSalesTaxAmount;
    } elseif ($salesTaxId > 0) {
        $salesTaxAmount += ($grnTotal / 100) * $salesTaxId;
    }

    $grnMasters->push($grn);
}

$firstGrn = $grnMasters->first();
$pvNo = CommonHelper::uniqe_no_for_purcahseVoucher(date('y'), date('m'));
$netTotal = $totalAmount + $salesTaxAmount;
?>

@extends('layouts.default')
@section('content')
    @include('number_formate')
    @include('select2')

    <style>
        .select2-container { font-size: 11px; }
    </style>

    @if($grnMasters->isEmpty())
        <div class="alert alert-danger">No valid GRN selected.</div>
    @elseif($grnMasters->pluck('supplier_id')->unique()->count() > 1)
        <div class="alert alert-danger">Please select GRNs of the same supplier and then create purchase invoice.</div>
    @else
        <?php echo Form::open(array('url' => 'pad/addPurchaseVoucherThorughGrn','id'=>'cashPaymentVoucherForm')); ?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="demandsSection[]" value="1">
        <input type="hidden" name="grn_no1" value="{{ $grnMasters->pluck('grn_no')->implode(',') }}">
        <input type="hidden" name="grn_id1" value="{{ $firstGrn->id }}">
        <input type="hidden" name="dept_id1" value="{{ $firstGrn->sub_department_id }}">
        <input type="hidden" name="p_type_id1" value="{{ $firstGrn->p_type }}">
        <input type="hidden" name="p_type1" value="{{ $firstGrn->p_type }}">
        @foreach($grnMasters as $grn)
            <input type="hidden" name="grn_ids[]" value="{{ $grn->id }}">
        @endforeach

        <div class="row well_N">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Purchase Voucher Form</span>
                        </div>
                    </div>
                    <h3 style="text-align: center">{{ strtoupper($grnMasters->pluck('grn_no')->implode(', ')) }}</h3>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">PV No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input readonly type="text" class="form-control requiredField" name="pv_no1" id="pv_no1" value="{{ $pvNo }}" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">PV Date.</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="date" class="form-control requiredField" max="{{ date('Y-m-d') }}" name="purchase_date1" id="purchase_date1" value="{{ date('Y-m-d') }}" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">PV Day.</label>
                                    <input readonly type="text" class="form-control" name="pv_day1" id="pv_day1" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input readonly type="text" class="form-control" name="slip_no1" id="slip_no1" value="{{ $firstGrn->supplier_invoice_no }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Bill Date.</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input readonly type="date" class="form-control" name="bill_date1" id="bill_date1" value="{{ $billDate }}" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Due Date</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input readonly value="{{ $dueDate }}" type="date" name="due_date1" id="due_date1" class="form-control requiredField"/>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label"><a href="#" onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');" class="">Supplier</a></label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input readonly class="form-control" name="supp_id1" id="supp_id1" value="{{ ucwords(CommonHelper::get_supplier_name($firstGrn->supplier_id)) }}">
                                    <input type="hidden" id="supplier_id1" name="supplier_id1" value="{{ $firstGrn->supplier_id }}"/>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Mode/ Terms Of Payment<span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input readonly type="text" class="form-control" name="model_terms_of_payment1" id="model_terms_of_payment1" value="{{ $termsOfPayment }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Supplier Current Amount <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input readonly type="number" class="form-control" name="current_amount1" id="current_amount1" value="" />
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <label class="sf-label">GRN No<span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input readonly type="text" class="form-control requiredField" value="{{ $grnMasters->pluck('grn_no')->implode(', ') }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="sf-label">Description</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <textarea name="description1" id="description1" rows="4" cols="50" style="resize:none;" class="form-control requiredField">{{ implode(', ', array_unique($poDescription)) }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="width: 100px;" class="text-center">GRN No</th>
                                                <th style="width: 150px;" class="text-center hidden-print"><a tabindex="-1" href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a></th>
                                                <th style="width: 100px" class="text-center">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th style="width: 100px;" class="text-center">DO No.</th>
                                                <th style="width: 100px;" class="text-center">Godown No.</th>
                                                <th style="width: 120px;" class="text-center">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th style="width: 120px;" class="text-center">Return Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th style="width: 120px;" class="text-center">Rate Cal. By</th>
                                                <th style="width: 120px;" class="text-center">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th style="width: 120px;" class="text-center hide">Amount. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th style="width: 120px;" class="text-center hide">Discount Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th style="width: 140px;" class="text-center">Net Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $count = 1; ?>
                                            @foreach($allItems as $row1)
                                                <?php
                                                $subIcDetail = CommonHelper::get_subitem_detail($row1->sub_item_id);
                                                ?>
                                                <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" value="{{ $count }}" />
                                                <input type="hidden" name="grn_data_id_1_{{ $count }}" id="grn_data_id_1_{{ $count }}" value="{{ $row1->id }}"/>
                                                <input type="hidden" name="discount_percent{{ $count }}" value="{{ $row1->discount_percent }}"/>
                                                <tr>
                                                    <td class="text-center">{{ strtoupper($row1->master_grn_no) }}</td>
                                                    <td title="{{ CommonHelper::get_item_name($row1->sub_item_id) }}" class="text-center">
                                                        <input type="hidden" name="sub_item_id_1_{{ $count }}" value="{{ $row1->sub_item_id }}"/>
                                                        {{ CommonHelper::get_item_name($row1->sub_item_id) }}
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" value="{{ CommonHelper::get_uom_name($subIcDetail[0]) }}" class="form-control" />
                                                        <input type="hidden" name="uom_id_1_{{ $count }}" id="uom_id_1_{{ $count }}" value="{{ $subIcDetail[0] }}" />
                                                    </td>
                                                    <td><input type="text" class="form-control" readonly name="do_no_pv_{{ $count }}" id="do_no_pv_{{ $count }}" value="{{ $row1->do_no ?? '' }}" /></td>
                                                    <td><input type="text" class="form-control" readonly name="godown_no_pv_{{ $count }}" id="godown_no_pv_{{ $count }}" value="{{ $row1->godown_no ?? '' }}" /></td>
                                                    <td><input readonly value="{{ $row1->display_qty }}" type="number" step="0.01" name="qty_1_{{ $count }}" id="qty_1_{{ $count }}" class="form-control qty" /></td>
                                                    <td><input readonly value="{{ $row1->return_qty }}" type="number" step="0.01" name="return_qty_1_{{ $count }}" class="form-control qty" /></td>
                                                    <td class="text-center"><span class="label label-info" style="display:inline-block;padding:4px 8px;">{{ $row1->rate_cal_by_label }}</span></td>
                                                    <td><input readonly value="{{ $row1->rate }}" type="text" step="0.01" name="rate_1_{{ $count }}" id="rate_1_{{ $count }}" class="form-control requiredField rate" /></td>
                                                    <td class="hide"><input type="text" name="amount{{ $count }}" id="amount{{ $count }}" class="form-control requiredField amount" value="{{ $row1->line_amount }}" readonly /></td>
                                                    <td class="hide"><input readonly class="form-control" type="text" id="discount_amount{{ $count }}" name="discount_amount{{ $count }}" value="{{ $row1->line_discount_amount }}"></td>
                                                    <td><input readonly class="form-control" type="text" id="net_amount{{ $count }}" name="net_amount{{ $count }}" value="{{ $row1->line_net_amount }}"></td>
                                                </tr>
                                                <?php $count++; ?>
                                            @endforeach
                                            <tr class="text-center">
                                                <td class="text-center" colspan="8"></td>
                                                <td class="text-center">Total</td>
                                                <td colspan="2"><input type="text" maxlength="15" class="form-control text-right" name="Totalamount" value="{{ $totalAmount }}" id="Totalamount1" readonly></td>
                                            </tr>
                                            <tr class="text-center" style="background: gainsboro">
                                                <td class="text-center" colspan="4"></td>
                                                <td colspan="2">Sales Taxes</td>
                                                <td colspan="3">
                                                    <select name="SalesTaxesAccId1" class="form-control" id="SalesTaxesAccId1">
                                                        <option value="">Select Head</option>
                                                        @foreach(FinanceHelper::get_accounts() as $row)
                                                            <option @if($row->id == $salesTaxAccId) selected @endif value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td colspan="2"><input type="text" name="SalesTaxAmount1" id="SalesTaxAmount1" class="form-control text-right" value="{{ $salesTaxAmount }}" readonly></td>
                                            </tr>
                                            <tr>
                                                <td id="rupees1" class="text-center" colspan="8"></td>
                                                <td class="text-center">Net Total</td>
                                                <td colspan="2"><input type="text" name="NetTotal" id="NetTotal1" class="form-control number_form" readonly value="{{ $netTotal }}"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $expenseRows = [];
                            foreach ($grnMasters as $grn) {
                                $expenses = ReuseableCode::get_grn_additional_exp($grn->id);
                                if (!empty($expenses)) {
                                    foreach ($expenses as $row) {
                                        $expenseRows[] = [
                                            'grn_no' => $grn->grn_no,
                                            'acc_id' => $row->acc_id,
                                            'amount' => $row->amount,
                                        ];
                                    }
                                }
                            }
                            ?>
                            @if(count($expenseRows) > 0)
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">GRN No</th>
                                                    <th class="text-center">Account Head</th>
                                                    <th class="text-center">Expense Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($expenseRows as $expenseCount => $row)
                                                    <tr id="RemoveExpenseRow{{ $expenseCount }}">
                                                        <td class="text-center">{{ strtoupper($row['grn_no']) }}</td>
                                                        <td class="text-center">
                                                            <input class="form-control" type="text" name="account_1[]" value="{{ CommonHelper::get_account_name($row['acc_id']) }}">
                                                            <input type="hidden" name="acc_id_1[]" value="{{ $row['acc_id'] }}"/>
                                                        </td>
                                                        <td><input readonly type="number" name="expense_amount_1[]" class="form-control requiredField" value="{{ $row['amount'] }}"></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="main_count" value="2"/>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
            </div>
        </div>
        <?php echo Form::close(); ?>
    @endif

    <script>
        $(document).ready(function() {
            $('.number_form').number(true, 2);
            toWordss(1);
        });

        $(".btn-success").click(function(e){
            jqueryValidationCustom();
            if(validate != 0) {
                return false;
            }
        });

        var th = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];
        var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        function toWords(s) {
            s = s.toString();
            s = s.replace(/[\, ]/g, '');
            if (s != parseFloat(s)) return 'not a number';
            var x = s.indexOf('.');
            if (x == -1) x = s.length;
            if (x > 15) return 'too big';
            var n = s.split('');
            var str = '';
            var sk = 0;
            for (var i = 0; i < x; i++) {
                if ((x - i) % 3 == 2) {
                    if (n[i] == '1') {
                        str += tn[Number(n[i + 1])] + ' ';
                        i++;
                        sk = 1;
                    } else if (n[i] != 0) {
                        str += tw[n[i] - 2] + ' ';
                        sk = 1;
                    }
                } else if (n[i] != 0) {
                    str += dg[n[i]] + ' ';
                    if ((x - i) % 3 == 0) str += 'Hundred ';
                    sk = 1;
                }
                if ((x - i) % 3 == 1) {
                    if (sk) str += th[(x - i - 1) / 3] + ' ';
                    sk = 0;
                }
            }
            if (x != s.length) {
                str += 'point ';
                for (var i = x + 1; i < s.length; i++) str += dg[n[i]] + ' ';
            }
            return str.replace(/\s+/g, ' ');
        }

        function toWordss(id) {
            var s = $('#NetTotal' + id).val();
            $('#rupees' + id).html('In Words: <strong> ' + toWords(s) + '</strong>');
            $('#rupeess' + id).val(toWords(s));
        }
    </script>
@endsection
