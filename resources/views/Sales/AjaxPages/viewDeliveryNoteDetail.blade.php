<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;

//$m = $_GET['m'];
$currentDate = date('Y-m-d');
$isFilled = function ($value) {
    if (is_null($value)) {
        return false;
    }

    if (is_string($value)) {
        $value = trim($value);
    }

    return $value !== '' && $value !== '0000-00-00';
};

$formatViewDate = function ($value) use ($isFilled) {
    return $isFilled($value) ? CommonHelper::changeDateFormat($value) : '';
};

$detailRow = function ($label, $value, $labelStyle = '', $valueStyle = '') use ($isFilled) {
    if (!$isFilled($value)) {
        return '';
    }

    return '<tr><td class="text-left" style="border: solid 1px black;' . $labelStyle . '">' . e($label) . '</td><td class="text-left" style="border: solid 1px black;' . $valueStyle . '">' . e($value) . '</td></tr>';
};
?>


<style>
    textarea {
        border-style: none;
        border-color: Transparent;

    }

    @media print {
        .printHide {
            display: none !important;
        }

        .fa {
            font-size: small;
            !important;
        }

        .table-bordered {
            border: 1px solid black;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid black !important;
        }

        body {
            font-size: 10px;
        }
    }

    table {
        border: solid 1px black;
    }

    tr {
        border: solid 1px black;
    }

    td {
        border: solid 1px black;
    }

    th {
        border: solid 1px black;
    }

    body{
        font-size: 10px;
    }
</style>
<?php

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printPurchaseRequestVoucherDetail', '', '1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printPurchaseRequestVoucherDetail">
    <div class="">
        <!--
        < ?php  StoreHelper::displayApproveDeleteRepostButtonPurchaseRequest($m,$sales_order->purchase_request_status,$sales_order->status,$row->id,'purchase_request_no','purchase_request_status','status','purchase_request','purchase_request_data');?>
    </div>
    <!-->
        <div style="line-height:5px;">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h3 style="text-align: center;">Delivery Challan</h3>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        <h4 style="text-align:center; margin:5px 0 3px 0;">FARAZ PACKAGES</h4>
                        <p style="text-align:center; margin:0; font-size:12px; line-height:16px;">Plot No. F-188-E, Near D-1 Bus Stop SITE Area, Karachi</p>
                        <p style="text-align:center; margin:0; font-size:12px; line-height:16px;">Ph: 021-32544444 | Cell: 0321 3254444</p>
                        <p style="text-align:center; margin:0; font-size:12px; line-height:16px;">Email: farazpackages@gmail.com</p>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-left">

                        {{--<label style="border-bottom:2px solid #000 !important;">Printed On
                            Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">
                            < ?php echo CommonHelper::changeDateFormat($currentDate);?>
                        </label>--}}
                    </div>
        
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                        {{--< ?php $nameOfDay=date('l', strtotime($currentDate)); ?>--}}
                            {{--<label style="border-bottom:2px solid #000 !important;">Printed On
                                Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">
                                < ?php echo '&nbsp;' .$nameOfDay;?>
                            </label>--}}

                    </div>
                </div>


                <div style="line-height:5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div style="width:49%; float:left;">
                            <table class="table " style="border: solid 1px  black">
                                <tbody>
                                    <?php
                                        $customer_data = CommonHelper::byers_name($delivery_note->buyers_id);
                                        $SalesOrder = CommonHelper::get_single_row('sales_order', 'id', $delivery_note->master_id);

                                        echo $detailRow("Buyer's Name", ucwords($customer_data->name ?? ''));
                                        echo $detailRow("Buyer's Order NO", $delivery_note->order_no ?? '', 'width:60%;', 'width:40%;');
                                        echo $detailRow("Buyer's Order Date", $formatViewDate($delivery_note->order_date ?? ''), 'width:60%;', 'width:40%;');
                                        echo $detailRow("Buyer's Unit", $SalesOrder->buyers_unit ?? '', 'width:60%;', 'width:40%;');
                                        echo $detailRow("Buyer's Address", ucwords($customer_data->address ?? ''), '', 'font-size: xx-small;');
                                        echo $detailRow('Destination', $delivery_note->destination ?? '', 'width:60%;', 'width:40%;');
                                    ?>


                                </tbody>
                            </table>

                        </div>

                        <div style="width:50%; float:right;">
                            <table class="table " style="border: solid 1px black; border: solid 1px black;">
                                <tbody>
                                    <?php
                                        echo $detailRow('DN NO.', strtoupper($delivery_note->gd_no ?? ''), 'width:50%;', 'width:50%;');
                                        echo $detailRow('DN Date', $formatViewDate($delivery_note->gd_date ?? ''));
                                        echo $detailRow('SO NO.', strtoupper($delivery_note->so_no ?? ''), 'width:50%;', 'width:50%;');
                                        echo $detailRow('SO Date', $formatViewDate($delivery_note->so_date ?? ''));
                                        echo $detailRow('Other Reference(S)', $delivery_note->other_refrence ?? '');
                                        echo $detailRow('Terms Of Delivery', $delivery_note->terms_of_delivery ?? '', 'width:60%;', 'width:40%;');
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- <div style="text-align: left" class="printHide">
                        <label class="text-left"><input type="checkbox" onclick="show_hide()" id="formats" />&nbsp; Printable Format </label>
                        <label class="text-left"><input type="checkbox" onclick="show_hide2()" id="formats2" />Bundle Printable Format </label>
                    </div> -->

                    <div id="actual" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="tablee" class="table " style="border: solid 1px black;">
                                <thead>
                                    <tr>
                                        <th class="text-center hide" style="border:1px solid black;">S.NO</th>
                                        <th class="text-center" style="border:1px solid black;">Quantity.<span class="rflabelsteric"><strong></strong></span></th>
                                        <th class="text-center" style="border:1px solid black;">Item</th>
                                        <th class="text-center" style="border:1px solid black;">Bags</th>
                                        
                                        {{-- <th class="text-center" style="border:1px solid black;">Color</th> --}}
                                        <th class="text-center hide" style="border:1px solid black;">Rate</th>
                                        <th class="text-center hide" style="border:1px solid black;">Amount</th>
                                        <th class="text-center hide" style="border:1px solid black;">Tax %</th>
                                        <th class="text-center hide" style="border:1px solid black;">Tax Amount</th>
                                        <th class="text-center hide" style="border:1px solid black;">Net Amount</th>
                                        {{-- <th class="text-center" style="border:1px solid black;">Batch Codes</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    $total_before_tax = 0;
                                    $total_tax = 0;
                                    $total_after_tax = 0;
                                    ?>
                                    @foreach ($delivery_note_data as $row)

                                        <?php

                                        $total_before_tax += $row->qty * $row->rate;
                                        $total_tax += $row->tax_amount;
                                        $total_after_tax += $row->amount;
                                        $batch_codes = explode(',', $row->batch_code);
                                        $out_qty = explode(',', $row->out_qty_details);
                                        ?>
                                       @foreach ($batch_codes as $key => $batch_code)
                                            <tr>
                                                @php
                                                    $batchQty = (float) ($out_qty[$key] ?? 0);
                                                    $rate = (float) ($row->rate ?? 0);
                                                    $amountBeforeTax = $batchQty * $rate;
                                                    $taxPercent = (float) ($row->tax ?? 0);
                                                    $taxAmount = ($row->qty ?? 0) ? ((float) $row->tax_amount * $batchQty / (float) $row->qty) : ($amountBeforeTax * $taxPercent / 100);
                                                    $netAmount = ($row->qty ?? 0) ? ((float) $row->amount * $batchQty / (float) $row->qty) : ($amountBeforeTax + $taxAmount);
                                                    $packSize = (float) ($row->pack_size ?? 0);
                                                    $bags = $packSize > 0 ? ($batchQty / $packSize) : 0;
                                                @endphp
                                                <td class="text-center hide" style="text-align: center; border:1px solid black;">{{ $count++ }}</td>
                                                <td style="border:1px solid black;">{{ $row->sub_ic }}</td>
                                                <td style="border:1px solid black;">{{ number_format($bags, 3) }}</td>
                                                {{-- <td style="border:1px solid black;">{{ $row->color }}</td> --}}
                                                <td class="text-right" style="border:1px solid black;">{{ number_format($batchQty, 3) }}</td>
                                                <!-- <td class="text-right" style="border:1px solid black;">{{ $row->qty }}</td> -->
                                                <td class="text-right hide" style="border:1px solid black;">{{ number_format($rate, 2) }}</td>
                                                <td class="text-right hide" style="border:1px solid black;">{{ number_format($amountBeforeTax, 2) }}</td>
                                                <td class="text-right hide" style="border:1px solid black;">{{ number_format($taxPercent, 2) }}</td>
                                                <td class="text-right hide" style="border:1px solid black;">{{ number_format($taxAmount, 2) }}</td>
                                                <td class="text-right hide" style="border:1px solid black;">{{ number_format($netAmount, 2) }}</td>
                                                {{-- <td class="text-right" style="border:1px solid black;">{{ trim($batch_code) }}</td> --}}
                                            </tr>
                                        @endforeach
                                    @endforeach

                                    <tr class="text-center hide" style="font-size: large;font-weight: bold">
                                        <td colspan="5" style="border:1px solid black;"> Total </td>
                                        <td class="text-right" style="border:1px solid black;">
                                            {{ number_format($total_before_tax, 2) }} </td>
                                        <td class="hide" style="border:1px solid black;"></td>
                                        <td class="text-right hide" style="border:1px solid black;">
                                            {{ number_format($total_tax, 2) }} </td>
                                        <td class="text-right" style="border:1px solid black;">
                                            {{ number_format($total_after_tax, 2) }} </td>
                                    </tr>

                                </tbody>
                            </table>

                            @php
                                $dnSalesTax = (float) ($delivery_note->sales_tax_amount ?? $total_tax);
                                $dnFurtherTax = (float) ($delivery_note->sales_tax_further ?? 0);
                                $dnAdvanceTax = (float) ($delivery_note->advance_tax_amount ?? 0);
                                $dnCartage = (float) ($delivery_note->cartage_amount ?? 0);
                                $dnGrandTotal = $total_before_tax + $dnSalesTax + $dnFurtherTax + $dnAdvanceTax + $dnCartage;
                            @endphp

                            <table class="table table-bordered hide" style="margin-top: 15px;">
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-right"><strong>DN Amount</strong></td>
                                        <td class="text-right">{{ number_format($total_before_tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right"><strong>Sales Tax</strong></td>
                                        <td class="text-right">{{ number_format($dnSalesTax, 2) }}</td>
                                    </tr>
                                    @if($dnFurtherTax > 0)
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Further Tax</strong></td>
                                            <td class="text-right">{{ number_format($dnFurtherTax, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if($dnAdvanceTax > 0)
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Advance Tax</strong></td>
                                            <td class="text-right">{{ number_format($dnAdvanceTax, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if($dnCartage > 0)
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Cartage Amount</strong></td>
                                            <td class="text-right">{{ number_format($dnCartage, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr style="font-size: large; font-weight: bold; background-color: #f5f5f5;">
                                        <td colspan="6" class="text-right"><strong>DN Grand Total</strong></td>
                                        <td class="text-right">{{ number_format($dnGrandTotal, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if(isset($linkedInvoices) && $linkedInvoices->count() > 0)
                                <table class="table table-bordered hide" style="margin-top: 15px;">
                                    <thead>
                                        <tr>
                                            <th colspan="8" class="text-center" style="border:1px solid black;">Generated Invoice Detail</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="border:1px solid black;">S.NO</th>
                                            <th class="text-center" style="border:1px solid black;">Invoice No</th>
                                            <th class="text-center" style="border:1px solid black;">Invoice Date</th>
                                            <th class="text-center" style="border:1px solid black;">Sales Tax</th>
                                            <th class="text-center" style="border:1px solid black;">Further Tax</th>
                                            <th class="text-center" style="border:1px solid black;">Advance Tax</th>
                                            <th class="text-center" style="border:1px solid black;">Cartage</th>
                                            <th class="text-center printHide" style="border:1px solid black;">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $invoiceCounter = 1;
                                            $invoiceSalesTaxTotal = 0;
                                            $invoiceFurtherTaxTotal = 0;
                                            $invoiceAdvanceTaxTotal = 0;
                                            $invoiceCartageTotal = 0;
                                        @endphp
                                        @foreach($linkedInvoices as $invoice)
                                            @php
                                                $invoiceSalesTaxTotal += (float) ($invoice->sales_tax ?? 0);
                                                $invoiceFurtherTaxTotal += (float) ($invoice->sales_tax_further ?? 0);
                                                $invoiceAdvanceTaxTotal += (float) ($invoice->advance_tax_amount ?? 0);
                                                $invoiceCartageTotal += (float) ($invoice->cartage_amount ?? 0);
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $invoiceCounter++ }}</td>
                                                <td class="text-center">{{ strtoupper($invoice->gi_no) }}</td>
                                                <td class="text-center">{{ CommonHelper::changeDateFormat($invoice->gi_date) }}</td>
                                                <td class="text-right">{{ number_format((float) ($invoice->sales_tax ?? 0), 2) }}</td>
                                                <td class="text-right">{{ number_format((float) ($invoice->sales_tax_further ?? 0), 2) }}</td>
                                                <td class="text-right">{{ number_format((float) ($invoice->advance_tax_amount ?? 0), 2) }}</td>
                                                <td class="text-right">{{ number_format((float) ($invoice->cartage_amount ?? 0), 2) }}</td>
                                                <td class="text-center printHide">
                                                    <a onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','{{ $invoice->id }}','View Sales Tax Invoice')">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr style="font-weight: bold; background-color: #f5f5f5;">
                                            <td colspan="3" class="text-right">Invoice Totals</td>
                                            <td class="text-right">{{ number_format($invoiceSalesTaxTotal, 2) }}</td>
                                            <td class="text-right">{{ number_format($invoiceFurtherTaxTotal, 2) }}</td>
                                            <td class="text-right">{{ number_format($invoiceAdvanceTaxTotal, 2) }}</td>
                                            <td class="text-right">{{ number_format($invoiceCartageTotal, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <table class="table table-bordered" style="margin-top: 15px;">
                                    <tbody>
                                        <tr>
                                            <td class="text-center"><strong>Invoice Detail</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">No sales tax invoice generated against this delivery note yet.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif

                            <table style="display: none;" class="table table-bordered tra">
                                <thead>
                                    <tr class="">
                                        <th class="text-center" style="width:50px;">S.No</th>
                                        <th class="text-center">Account</th>
                                        <th class="text-center" style="width:150px;">Debit</th>
                                        <th class="text-center" style="width:150px;">Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = DB::Connection('mysql2')->table('transactions')->where('status', 1)->where('voucher_no', $delivery_note->gd_no)->orderBy('id', 'DESC')->get();
                                    $total_debit = 0;
                                    $total_credit = 0;
                                    $counter = 1;
                                    foreach ($data as $row1):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php    echo $counter++;?></td>
                                        <td><?php    echo FinanceHelper::getAccountNameByAccId($row1->acc_id, Session::get('run_company'));?>
                                        </td>
                                        <td class="debit_amount text-right">
                                            @if($row1->debit_credit == 1){{number_format($row1->amount, 2)}}
                                            @php $total_debit += $row1->amount  @endphp @endif </td>
                                        <td class="debit_amount text-right">
                                            @if($row1->debit_credit == 0){{number_format($row1->amount, 2)}}
                                            @php $total_credit += $row1->amount  @endphp @endif </td>

                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="sf-table-total">
                                        <td colspan="2">
                                            <label for="field-1" class="sf-label"><b>Total</b></label>
                                        </td>
                                        <td class="text-right"><b><?php echo number_format($total_debit, 2);?></b></td>
                                        <td class="text-right"><b><?php echo number_format($total_credit, 2);?></b></td>
                                    </tr>
                                </tbody>
                            </table>

                            <label class="check printHide">
                                Show Voucher
                                <input id="check" type="checkbox" onclick="checkk()" class="check">
                            </label>

                        </div>
                    </div>

                    <div style="line-height:8px;">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row text-left">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">

                                <?php if ($isFilled($delivery_note->description ?? '')): ?>
                                    <textarea><?php echo 'Description:' . ' ' . strtoupper($delivery_note->description); ?></textarea>
                                <?php endif; ?>
                            </div>
                        </div>
                        <style>
                            .signature_bor {
                                border-top: solid 1px #CCC;
                                padding-top: 7px;
                            }
                        </style>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                            <div class="container-fluid">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Prepared By: </h6>
                                        <b>
                                            <p><?php echo strtoupper($delivery_note->username);?></p>
                                        </b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Checked By:</h6>
                                        <b>
                                            <p><?php  ?></p>
                                        </b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Approved By:</h6>
                                        <b>
                                            <p>
                                        </b>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                        <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Request Voucher Detail (Office Use)'))!!} ">
                    </div>
                    <!-->
                </div>
            </div>
        </div>

    </div>

    <script>


        function change() {


            if (!$('.showw').is(':visible')) {
                $(".showw").css("display", "block");

            }
            else {
                $(".showw").css("display", "none");

            }

        }

        function show_hide() {
            if ($('#formats').is(":checked")) {
                $("#actual").css("display", "none");
                $("#printable").css("display", "block");
            }

            else {
                $("#actual").css("display", "block");
                $("#printable").css("display", "none");
            }
        }

        function show_hide2() {
            if ($('#formats2').is(":checked")) {
                $(".ShowHideHtml").fadeOut("slow");
                $(".bundleHide").fadeOut("slow");

                //                $("#printable").css("display", "block");
            }

            else {
                $(".ShowHideHtml").fadeIn("slow");
                $(".bundleHide").fadeIn("slow");

                //                $("#printable").css("display", "none");
            }
        }


        function remove_bundle(id) {
            //Q$('#'+id).css('display','none');
        }
        function diss(id) {
            $('#' + id).remove();
        }

        function checkk() {

            if ($("#check").is(":checked")) {


                $('.tra').css('display', 'block');
            }

            else {
                $('.tra').css('display', 'none');
            }
        }

    </script>
