@extends('layouts.default')

@section('content')
    <?php
    use App\Helpers\CommonHelper;
    use App\Helpers\StoreHelper;
    use App\Helpers\ReuseableCode;

    $m = Session::get('run_company');
    $approve = ReuseableCode::check_rights(16);

    CommonHelper::companyDatabaseConnection($m);
    $purchaseRequestDetail = DB::table('purchase_request')->where('id', '=', $id)->get();
    $purchaseRequestDataDetail = DB::table('purchase_request_data')->where('master_id', '=', $id)->get();
    $quotation_ref = DB::table('purchase_request_data as prd')
        ->join('demand as d', 'd.demand_no', '=', 'prd.demand_no')
        ->leftJoin('quotation as q', 'd.id', '=', 'q.pr_id')
        ->where('prd.master_id', '=', $id)
        ->select(['q.ref_no'])
        ->first();
    CommonHelper::reconnectMasterDatabase();

    $companyName = 'FARAZ PACKAGES';
    $companyAddress = 'F-98 S.I.T.E KARACHI.';
    $companyPhone = '0321 - 2254444';
    $companyEmail = 'farazpackages@gmail.com';
    ?>

    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        body {
            background: #f3f4f6;
        }

        .print-window-body {
            margin: 0;
            background: #fff;
        }

        .po-print-page {
            width: 190mm;
            max-width: 190mm;
            margin: 0 auto 18px;
        }

        .po-print-card {
            background: #fff;
            border: 1px solid #2d2d2d;
            padding: 12px 12px 8px;
            color: #111;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .po-title {
            text-align: center;
            font-weight: 700;
            font-size: 15px;
            margin: 0 0 10px;
            letter-spacing: 0.3px;
        }

        .po-topbar {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .po-topbar > div {
            display: table-cell;
            vertical-align: top;
        }

        .po-company {
            width: 46%;
            font-size: 11px;
            line-height: 1.45;
        }

        .po-company-name {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .po-main-heading {
            width: 54%;
            text-align: center;
        }

        .po-main-heading h1 {
            margin: 0 0 8px;
            font-size: 20px;
            font-weight: 700;
            font-family: "Times New Roman", serif;
        }

        .po-meta {
            width: 68%;
            margin-left: auto;
            border-collapse: collapse;
            font-size: 10px;
        }

        .po-meta td {
            border: 1px solid #4f4f4f;
            padding: 2px 5px;
        }

        .po-meta td:first-child {
            width: 34%;
            font-weight: 700;
            text-align: left;
            border-left: none;
            border-top: none;
            border-bottom: none;
        }

        .po-meta tr:last-child td:first-child {
            border-bottom: none;
        }

        .po-section-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            border-spacing: 0 8px;
        }

        .po-section {
            display: table-cell;
            width: 48.5%;
            vertical-align: top;
        }

        .po-section-gap {
            display: table-cell;
            width: 3%;
        }

        .po-section-header {
            background: #4d87cf;
            color: #111;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 8px;
            text-transform: uppercase;
        }

        .po-section-body {
            border: 1px solid #8f8f8f;
            border-top: none;
            min-height: 86px;
            padding: 6px 8px;
            font-size: 10px;
            line-height: 1.45;
        }

        .po-section-body strong {
            display: inline-block;
            min-width: 66px;
        }

        .po-line-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .po-line-table th,
        .po-line-table td {
            border: 1px solid #707070;
            padding: 3px 4px;
            vertical-align: top;
        }

        .po-line-table th {
            background: #4d87cf;
            text-align: center;
            font-weight: 700;
            line-height: 1.2;
        }

        .po-line-table .text-right {
            text-align: right;
        }

        .po-line-table .text-center {
            text-align: center;
        }

        .po-description-cell {
            min-width: 180px;
            line-height: 1.35;
        }

        .po-description-sub {
            display: block;
            font-size: 9px;
            margin-top: 1px;
        }

        .po-total-row td {
            font-weight: 700;
        }

        .po-note {
            margin-top: 8px;
            font-size: 10px;
            line-height: 1.4;
        }

        .po-note-label {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .po-signatures {
            width: 100%;
            margin-top: 14px;
            border-collapse: separate;
            border-spacing: 10px 0;
            font-size: 10px;
        }

        .po-signatures td {
            width: 25%;
            text-align: center;
            vertical-align: bottom;
        }

        .po-sign-box {
            border-top: 1px solid #9a9a9a;
            padding-top: 5px;
            min-height: 40px;
        }

        .po-sign-name {
            margin-top: 10px;
            color: #444;
        }

        .hidden-print-actions {
            margin-bottom: 10px;
            text-align: right;
        }

        @media print {
            .hidden-print-actions,
            .LinkHide,
            .printHide,
            .btn,
            .dropdown,
            .navbar,
            .main-menu,
            .header-navbar,
            .footer,
            .scroll-top {
                display: none !important;
            }

            .well_N,
            .dp_sdw,
            .po-print-card {
                box-shadow: none !important;
            }

            .po-print-page {
                width: 100%;
                max-width: 100%;
                margin: 0;
            }

            body {
                background: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    @foreach ($purchaseRequestDetail as $row)
                        <?php
                        $supplier = CommonHelper::getSupplierDetail($row->supplier_id);
                        $supplier = is_object($supplier) ? $supplier : (object) [
                            'name' => '',
                            'address' => '',
                            'mobile_no' => '',
                            'contact_person' => '',
                            'ntn' => '',
                        ];

                        $currencyName = CommonHelper::get_curreny_name($row->currency_id);
                        $currencyCode = 'PKR';
                        if ($row->currency_id == 4) {
                            $currencyCode = 'USD';
                        } elseif ($row->currency_id == 3) {
                            $currencyCode = 'PKR';
                        } elseif (!empty($currencyName)) {
                            $currencyCode = strtoupper(substr(strip_tags($currencyName), 0, 3));
                        }

                        $termsLabel = '';
                        if ((int) $row->terms_of_paym === 1) {
                            $termsLabel = 'Advance';
                        } elseif ((int) $row->terms_of_paym === 2) {
                            $termsLabel = 'Against Delivery';
                        } elseif ((int) $row->terms_of_paym === 3) {
                            $termsLabel = ($row->no_of_days ?? ($supplier->no_of_days ?? '')) . ' Days';
                        }

                        $preparedBy = strtoupper($row->username ?? '');
                        $approvedBy = strtoupper($row->approve_username ?? '');
                        $enteredBy = strtoupper($row->username ?? '');
                        $checkedBy = '';

                        $total = 0;
                        foreach ($purchaseRequestDataDetail as $line) {
                            $total += (float) ($line->net_amount ?? 0);
                        }
                        $salesTaxAmount = (float) ($row->sales_tax_amount ?? 0);
                        $grandTotal = $total + $salesTaxAmount;
                        $amountInWords = trim((string) ($row->amount_in_words ?? ''));
                        if ($amountInWords === '' && $grandTotal > 0) {
                            $amountInWords = ucwords(trim(CommonHelper::numberToWords((int) round($grandTotal)))) . ' Only';
                        }
                        ?>

                        <div class="hidden-print-actions">
                            @if ($approve == true)
                                {!! StoreHelper::displayApproveDeleteRepostButtonPurchaseRequest($m, $row->purchase_request_status, $row->status, $row->id, 'purchase_request_no', 'purchase_request_status', 'status', 'purchase_request', 'purchase_request_data') !!}
                            @endif
                            <button type="button" class="btn btn-primary" onclick="printPurchaseOrder('po_detail')">
                                <span class="glyphicon glyphicon-print"></span> Print
                            </button>
                        </div>

                        <div class="po-print-page" id="po_detail">
                            <div class="po-title">Purchase Order Format:</div>

                            <div class="po-print-card">
                                <div class="po-topbar">
                                    <div class="po-company">
                                        <div class="po-company-name">{{ $companyName }}</div>
                                        <div>{{ $companyAddress }}</div>
                                        <div style="height: 10px;"></div>
                                        <div><strong>Phone:</strong>{{ $companyPhone }}</div>
                                        <div><strong>Email :</strong> {{ $companyEmail }}</div>
                                    </div>

                                    <div class="po-main-heading">
                                        <h1>PURCHASE ORDER</h1>
                                        <table class="po-meta">
                                            <tr>
                                                <td>DATE</td>
                                                <td>{{ CommonHelper::changeDateFormat($row->purchase_request_date) }}</td>
                                            </tr>
                                            <tr>
                                                <td>PO #</td>
                                                <td>{{ strtoupper($row->purchase_request_no) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Purchase Invoice #</td>
                                                <td>{{ strtoupper($quotation_ref->ref_no ?? '') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="po-section-grid">
                                    <div class="po-section">
                                        <div class="po-section-header">Vendor</div>
                                        <div class="po-section-body">
                                            <div><strong>{{ $supplier->name }}</strong></div>
                                            <div>{{ $supplier->address }}</div>
                                            @if (!empty($supplier->mobile_no))
                                                <div style="margin-top: 6px;"><strong>Phone:</strong> {{ $supplier->mobile_no }}</div>
                                            @endif
                                            @if (!empty($supplier->ntn))
                                                <div><strong>NTN:</strong> {{ $supplier->ntn }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="po-section-gap"></div>

                                    <div class="po-section">
                                        <div class="po-section-header">Ship To</div>
                                        <div class="po-section-body">
                                            <div><strong>Company Name</strong> {{ $companyName }}.</div>
                                            <div>{{ $companyAddress }}</div>
                                            <div style="margin-top: 6px;"><strong>Phone:</strong> {{ $companyPhone }}</div>
                                            <div><strong>Email :</strong> {{ $companyEmail }}</div>
                                        </div>
                                    </div>
                                </div>

                                <table class="po-line-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 34%;">REQUISITIONER<br>DESCRIPTION</th>
                                            <th style="width: 11%;">PER POUND</th>
                                            <th style="width: 8%;">BAGS</th>
                                            <th style="width: 9%;">U.PRICE</th>
                                            <th style="width: 13%;">PAYMENT TERMS</th>
                                            <th style="width: 13%;">AMOUNT</th>
                                            <th style="width: 12%;">Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseRequestDataDetail as $line)
                                            <?php
                                            $subItemName = CommonHelper::get_item_name($line->sub_item_id);
                                            $bagsQty = (float) ($line->bags_qty ?? 0);
                                            $doNo = trim((string) ($line->do_no ?? ''));
                                            $godownNo = trim((string) ($line->godown_no ?? ''));
                                            $lineDescription = trim((string) ($line->description ?? ''));
                                            $displayRate = (float) ($line->rate ?? 0);
                                            $displayQty = (float) ($line->purchase_approve_qty ?? 0);
                                            $displayAmount = (float) ($line->net_amount ?? 0);
                                            ?>
                                            <tr>
                                                <td class="po-description-cell">
                                                    <strong>{{ $subItemName }}</strong>
                                                    @if ($doNo !== '')
                                                        <span class="po-description-sub">DO No. {{ $doNo }}</span>
                                                    @endif
                                                    @if ($godownNo !== '')
                                                        <span class="po-description-sub">Godown. {{ $godownNo }}</span>
                                                    @endif
                                                    @if ($lineDescription !== '')
                                                        <span class="po-description-sub">{{ $lineDescription }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ number_format($displayQty, 2) }}</td>
                                                <td class="text-center">{{ $bagsQty > 0 ? number_format($bagsQty, 0) : '' }}</td>
                                                <td class="text-center">{{ number_format($displayRate, 2) }}</td>
                                                <td class="text-center">{{ $termsLabel }}</td>
                                                <td class="text-right">{{ number_format($displayAmount, 2) }}</td>
                                                <td class="text-center">{{ CommonHelper::changeDateFormat($row->due_date) }}</td>
                                            </tr>
                                        @endforeach

                                        @for ($i = $purchaseRequestDataDetail->count(); $i < 3; $i++)
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        @endfor

                                        @if ($salesTaxAmount > 0)
                                            <tr>
                                                <td colspan="5" class="text-right"><strong>Sales Tax</strong></td>
                                                <td class="text-right">{{ number_format($salesTaxAmount, 2) }}</td>
                                                <td></td>
                                            </tr>
                                        @endif

                                        <tr class="po-total-row">
                                            <td colspan="5" class="text-right">TOTAL:</td>
                                            <td class="text-right">{{ number_format($grandTotal, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>

                                @if ($amountInWords !== '' || trim((string) $row->description) !== '')
                                    <div class="po-note">
                                        @if ($amountInWords !== '')
                                            <div><span class="po-note-label">Amount In Words:</span> {{ $amountInWords }}</div>
                                        @endif
                                        @if (trim((string) $row->description) !== '')
                                            <div style="margin-top: 6px;"><span class="po-note-label">Note:</span> {{ $row->description }}</div>
                                        @endif
                                    </div>
                                @endif

                                <table class="po-signatures">
                                    <tr>
                                        <td>
                                            <div class="po-sign-box">
                                                <strong>Prepared by</strong>
                                                <div class="po-sign-name">{{ $preparedBy ?: '--------------------' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="po-sign-box">
                                                <strong>Checked by</strong>
                                                <div class="po-sign-name">{{ $checkedBy ?: '--------------------' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="po-sign-box">
                                                <strong>Entry by</strong>
                                                <div class="po-sign-name">{{ $enteredBy ?: '--------------------' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="po-sign-box">
                                                <strong>Approved by</strong>
                                                <div class="po-sign-name">{{ $approvedBy ?: '--------------------' }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function printPurchaseOrder(elementId) {
            var printElement = document.getElementById(elementId);
            if (!printElement) {
                return;
            }

            var printWindow = window.open('', '_blank', 'width=1000,height=900');
            if (!printWindow) {
                alert('Please allow popups for printing.');
                return;
            }

            var styles = '';
            document.querySelectorAll('style, link[rel="stylesheet"]').forEach(function(node) {
                styles += node.outerHTML;
            });

            printWindow.document.open();
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Purchase Order Print</title>
                    <base href="${window.location.origin}">
                    ${styles}
                </head>
                <body class="print-window-body">
                    ${printElement.outerHTML}
                </body>
                </html>
            `);
            printWindow.document.close();

            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
            };
        }

        @if (request()->query('autoPrint') == '1')
            window.addEventListener('load', function() {
                setTimeout(function() {
                    printPurchaseOrder('po_detail');
                }, 250);
            });
        @endif
    </script>
@endsection
